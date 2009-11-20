<?php
/*
  $Id: featured_products.php,v 1.27 2003/06/09 22:35:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FEATURED_PRODUCTS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FEATURED_PRODUCTS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php // echo tep_image(DIR_WS_IMAGES . 'table_background_products_new.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
///// To random featured products
//  list($usec, $sec) = explode(' ', microtime());
//  srand( (float) $sec + ((float) $usec * 100000) );
//  $mtm= rand();
//////  
// BOF Separate Pricing Per Customer
// global variable (session): $sppc_customers_group_id -> local variable $customer_group_id

if(!tep_session_is_registered('sppc_customer_group_id')) { 
$customer_group_id = '0';
} else {
$customer_group_id = $sppc_customer_group_id;
}

$featured_products_array = array();
$featured_products_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, NULL as specials_new_products_price, NULL as specstat, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by p.products_date_added DESC, pd.products_name";
// EOF Separate Pricing Per Customer   $featured_products_split = new splitPageResults($featured_products_query_raw, MAX_DISPLAY_FEATURED_PRODUCTS);
$featured_products_split = new splitPageResults($featured_products_query_raw, MAX_DISPLAY_FEATURED_PRODUCTS);

  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $featured_products_split->display_count(TEXT_DISPLAY_NUMBER_OF_FEATURED_PRODUCTS); ?> </td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td> <!-- Featured Products Main Page Box -->
		 <table bgcolor="ffffff" border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($featured_products_split->number_of_rows > 0) {
// BOF Separate Pricing Per Customer

$featured_products_query = tep_db_query($featured_products_split->sql_query);
if (($no_of_featured_prdcts = tep_db_num_rows($featured_products_query)) > 0) {
while ($_featured_products = tep_db_fetch_array($featured_products_query)) {
$featured_products[] = $_featured_products;
$list_of_prdct_ids[] = $_featured_products['products_id'];
} 

$select_list_of_prdct_ids = "products_id = '".$list_of_prdct_ids[0]."' ";
if ($no_of_featured_prdcts > 1) {
for ($n = 1; $n < count($list_of_prdct_ids); $n++) {
$select_list_of_prdct_ids .= "or products_id = '".$list_of_prdct_ids[$n]."' "; 
}
}
// get all customers_group_prices for products with the particular customer_group_id
// however not necessary for customer_group_id = 0
if ($customer_group_id != '0') {
$pg_query = tep_db_query("select pg.products_id, customers_group_price as price from " . TABLE_PRODUCTS_GROUPS . " pg where (".$select_list_of_prdct_ids.") and pg.customers_group_id = '".$customer_group_id."'");
while ($pg_array = tep_db_fetch_array($pg_query)) {
$new_prices[] = array ('products_id' => $pg_array['products_id'], 'products_price' => $pg_array['price'], 'specials_new_products_price' => '');
}

for ($x = 0; $x < $no_of_featured_prdcts; $x++) {
// replace products prices with those from customers_group table
if(!empty($new_prices)) {
for ($i = 0; $i < count($new_prices); $i++) {
if( $featured_products[$x]['products_id'] == $new_prices[$i]['products_id'] ) {
$featured_products[$x]['products_price'] = $new_prices[$i]['products_price'];
}
}
} // end if(!empty($new_prices)
} // end for ($x = 0; $x < $no_of_featured_prdcts; $x++)
} // end if ($customer_group_id != '0')

// an extra query is needed for all the specials
$specials_query = tep_db_query("select products_id, specials_new_products_price, status as specstat from specials where (".$select_list_of_prdct_ids.") and status = '1' and customers_group_id = '" .$customer_group_id. "' ");
while ($specials_array = tep_db_fetch_array($specials_query)) {
$new_s_prices[] = array ('products_id' => $specials_array['products_id'], 'specials_new_products_price' => $specials_array['specials_new_products_price'], 'specstat' => $specials_array['specstat']);
}

// add correct specials_new_products_price
if(!empty($new_s_prices)) {
for ($x = 0; $x < $no_of_featured_prdcts; $x++) { 
for ($i = 0; $i < count($new_s_prices); $i++) {
if( $featured_products[$x]['products_id'] == $new_s_prices[$i]['products_id'] ) {
$featured_products[$x]['specials_new_products_price'] = $new_s_prices[$i]['specials_new_products_price'];
$featured_products[$x]['specstat'] = $new_s_prices[$i]['specstat'];
}
}
} 
} // // end if(!empty($new_s_prices)
} // end if ($no_of_featured_prdcts = tep_db_num_rows($featured_products_query)) > 0)


// while ($featured_products = tep_db_fetch_array($featured_products_query)) {
for ($x = 0; $x < $no_of_featured_prdcts; $x++) {
if ($featured_products[$x]['specstat']) {
$products_price = '<s>' . $currencies->display_price($featured_products[$x]['products_price'], tep_get_tax_rate($featured_products[$x]['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($featured_products[$x]['specials_new_products_price'], tep_get_tax_rate($featured_products[$x]['products_tax_class_id'])) . '</span>';
} else {
$products_price = $currencies->display_price($featured_products[$x]['products_price'], tep_get_tax_rate($featured_products[$x]['products_tax_class_id']));
}
?>
<tr>
<td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products[$x]['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products[$x]['products_image'], $featured_products[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
<td valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products[$x]['products_id']) . '"><b><u>' . $featured_products[$x]['products_name'] . '</u></b></a><br>' . TEXT_DATE_ADDED . ' ' . tep_date_long($featured_products[$x]['products_date_added']) . '<br>' . TEXT_MANUFACTURER . ' ' . $featured_products[$x]['manufacturers_name'] . '<br><br>' . TEXT_PRICE . ' ' . $products_price; ?></td>
<td align="right" valign="middle" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_FEATURED_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products[$x]['products_id']) . '">' . tep_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>'; ?></td>
</tr>
<tr>
<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr>
<?php
    }
  } else {
?>
          <tr>
            <td class="main"><?php echo TEXT_NO_NEW_PRODUCTS; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  }
?>
        </table>
		</td>
      </tr>
<?php
  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td>
		  <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $featured_products_split->display_count(TEXT_DISPLAY_NUMBER_OF_FEATURED_PRODUCTS); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
	   </td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
