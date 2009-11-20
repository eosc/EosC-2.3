<?php
/*
  $Id: product_info.php,v 1.97 2003/07/01 14:34:54 hpdl Exp $
  adapted for Separate Pricing Per Customer v4 2005/03/06
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/
 
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

// Tabbed or not tabbed?
 if(TABS_DISPLAY == 'false') { 
 include 'product_info.standard.php';
 } else {
// Tabbed it is! And a fine selection you've made if I may say so!

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);
  
// BOF Separate Price per Customer
     if(!tep_session_is_registered('sppc_customer_group_id')) { 
     $customer_group_id = '0';
     } else {
      $customer_group_id = $sppc_customer_group_id;
     }
// EOF Separate Price per Customer
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php
// HTC BEGIN
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?> 
  <title><?php echo TITLE; ?></title>
<?php
}
// HTC END
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3"><tr>
<td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
    <?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
<?php
  if ($product_check['total'] < 1) {
?>
<!-- Product Not Found Table -->
<p>
<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
<td><?php new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
</tr><td>
<table border="0" width="100%" cellspacing="0" cellpadding="2"><tr>
<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
<td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
</td></tr></table>

<?php
  } else {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, pd.products_spec, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
// EOF MaxiDVD: Modified For Ultimate Images Pack!
     $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {

// BOF Separate Price per Customer
     $scustomer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$_GET['products_id']. "' and customers_group_id =  '" . $customer_group_id . "'");
     if ($scustomer_group_price = tep_db_fetch_array($scustomer_group_price_query)) {
     $product_info['products_price']= $scustomer_group_price['customers_group_price'];
	}
// EOF Separate Price per Customer

     $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {

// BOF Separate Price per Customer
     $scustomer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$_GET['products_id']. "' and customers_group_id =  '" . $customer_group_id . "'");
     if ($scustomer_group_price = tep_db_fetch_array($scustomer_group_price_query)) {
     $product_info['products_price']= $scustomer_group_price['customers_group_price'];
	}
// EOF Separate Price per Customer

     $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

     $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, pd.products_spec, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_price as list_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
//     $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, pd.products_spec, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");

     $product_info = tep_db_fetch_array($product_info_query);

   tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

   if ($new_price = tep_get_products_special_price($product_info['products_id'])) {

// BOF Separate Price per Customer
     $scustomer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$_GET['products_id']. "' and customers_group_id =  '" . $customer_group_id . "'");
     if ($scustomer_group_price = tep_db_fetch_array($scustomer_group_price_query)) {
     $product_info['products_price']= $scustomer_group_price['customers_group_price'];
}
$products_price = '';
	 if ($customer_group_id != '0') {
	 $products_price .= '<span class="boxText">' . SHOW_PRICE_LIST;
	 $products_price .= $currencies->display_price($product_info['list_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
	 $products_price .= '</span><br>'; // customer group prices on the next line end of small text for retail prices 
}
	 $products_price .= '<span class="boxText">' . REGULAR_PRICE;
     $products_price .= '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s><BR>' . SPECIAL_PRICE . '<span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
// EOF Separate Price per Customer

   } else {

// BOF Separate Price per Customer
       $scustomer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$_GET['products_id']. "' and customers_group_id =  '" . $customer_group_id . "'");
       if ($scustomer_group_price = tep_db_fetch_array($scustomer_group_price_query)) {
       $product_info['products_price']= $scustomer_group_price['customers_group_price'];
}
$products_price = '';
	 if ($customer_group_id != '0') {
	 $products_price = '<span class="boxText">' . SHOW_PRICE_LIST;
	 $products_price .= $currencies->display_price($product_info['list_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
	 $products_price .= '</span><br>'; // customer group prices on the next line end of small text for retail prices 
}
     $products_price .= SHOW_YOUR_PRICE ;
     $products_price .= $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
// EOF Separate Price per Customer

   }
// EOF Show Price list v.3.5 1.0 for SPPC 4.1

// Uncomment below to restore model number
//    if (tep_not_null($product_info['products_model'])) {
//      $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
//    } else {
      $products_name = $product_info['products_name'];
//    }
?>
<!-- Product Name & Price Table -->
<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
            <td class="pageHeading" width="70%" valign="top"><?php echo $products_name; ?></td>
            <td class="pageHeading" width="30%" align="right" valign="top"><?php echo $products_price; ?></td>
            </tr><tr>
            <td colspan="2" width="100%"><?php echo tep_draw_separator('pixel_trans.gif', '10', '5'); ?></td>
</tr></table>
<!-- End Product Name & Price Table -->

<script type="text/javascript" src="tabs/tabpane.js"></script>
<script type="text/javascript">
	document.write('<link type="text/css" rel="StyleSheet" href="tabs/tab.css" />');
</script>
<!--[if IE]>
<script type="text/javascript">
	document.write('<link type="text/css" rel="StyleSheet" href="tabs/ietab.css" />');
</script>
<![endif]-->
<div class="tab-pane" id="tab-pane-1">

<div class="tab-page">
<h2 class="tab">Description</h2>
<!-- Product Description Table -->
<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr><tr>
<td class="main">
<!-- Product Main Image -->
<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! //-->
<?php
    if (tep_not_null($product_info['products_image'])) {
?>
          <table border="0" cellspacing="0" cellpadding="2" align="right">
            <tr>
              <td align="center" class="smallText">
<?php
 if ($product_info['products_image_med']!='') {
          $new_image = $product_info['products_image_med'];
          $image_width = MEDIUM_IMAGE_WIDTH;
          $image_height = MEDIUM_IMAGE_HEIGHT;
         } else {
          $new_image = $product_info['products_image'];
          $image_width = SMALL_IMAGE_WIDTH;
          $image_height = SMALL_IMAGE_HEIGHT;}?>
<?php if ($product_info['products_image_lrg']!='') { ?>
<script language="javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id'] . '&image=0') . '\\\')">' . tep_image(DIR_WS_IMAGES . $new_image, addslashes($product_info['products_name']), $image_width, $image_height, 'hspace="5" vspace="5"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>');
//--></script>
<noscript>
      <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_med']) . '">' . tep_image(DIR_WS_IMAGES . $new_image . '&image=0', addslashes($product_info['products_name']), $image_width, $image_height, 'hspace="5" vspace="5"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
</noscript>
<?php } else { ?>
     <?php echo '' . tep_image(DIR_WS_IMAGES . $new_image, addslashes($product_info['products_name']), $image_width, $image_height, 'hspace="5" vspace="5"'); ?>
<?php } ?>
              </td>
            </tr>
          </table>
<?php
    }
?>
<!-- // EOF MaxiDVD: Modified For Ultimate Images Pack! //-->
<!-- End Product Main Image -->

<?php echo nl2br(stripslashes($product_info['products_description'])); ?>
</td></tr></table>
<p>
<!-- Product Attributes Table -->
<?php
//++++ QT Pro: End Changed Code
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
//++++ QT Pro: Begin Changed code
      $products_id=(preg_match("/^\d{1,10}(\{\d{1,10}\}\d{1,10})*$/",$_GET['products_id']) ? $_GET['products_id'] : (int)$_GET['products_id']); 
      require(DIR_WS_CLASSES . 'pad_' . PRODINFO_ATTRIBUTE_PLUGIN . '.php');
      $class = 'pad_' . PRODINFO_ATTRIBUTE_PLUGIN;
      $pad = new $class($products_id);
      echo $pad->draw();
//++++ QT Pro: End Changed Code
    }
?>
<p>
<!-- End Product Attributes Table -->

<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
<td class="main" align="right"><?php echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART); ?></td>
</td></tr></table>
</div>
<!-- End Product Description Table -->
<?php
// Check to see if there are specs and if there are generate tab
    if (($product_info['products_spec'] != '')) {
?>
<div class="tab-page">
<h2 class="tab">More Info</h2> 
<!-- Product Spec Table -->
<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td class="main">                   
<?php echo nl2br(stripslashes($product_info['products_spec'])); ?>
</td></tr></table>
<!-- End Product Spec Table -->
</div>
<?php
 // End checking for specs
 	}
?>
<?php
// Check to see if there are extra images and if there are generate tab
    if (($product_info['products_image_sm_1'] != '')) {
?>
<div class="tab-page">
<h2 class="tab">More Images</h2> 
<!-- Additional Images Table -->
<?php
// BOF MaxiDVD: Modified For Ultimate Images Pack!
 if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') { include(DIR_WS_MODULES . 'additional_images.php'); }
// EOF MaxiDVD: Modified For Ultimate Images Pack!
; ?>
<!-- End Additional Images Table -->
</div>
<?php
// End checking for extra images
	}
?>

<?php
// Check to see if there are reviews and if there are generate tab
    if(MAX_REVIEWS_IN_PRODUCT_INFO != '0') { 
?>
<div class="tab-page">
<h2 class="tab">Reviews</h2> 
<!-- Product Reviews Table -->
<?php
//// Begin Reviews on Product Information page hack
if (MAX_REVIEWS_IN_PRODUCT_INFO <= 0) {
//// End Reviews on Product Information page hack
    $reviews = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . $_GET['products_id'] . "'");
    $reviews_values = tep_db_fetch_array($reviews);
    if ($reviews_values['count'] > 0) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
<td class="main"><br><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews_values['count']; ?></td>
</tr>
<?php
    }
//// Begin Reviews on Product Information page hack
}
?>
<?php
//// Begin Reviews on Product Information page hack
if (MAX_REVIEWS_IN_PRODUCT_INFO > 0) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td align="center">
<?php
  $reviews_query = tep_db_query("select r.reviews_id, rd.reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . $_GET['products_id'] . "' and rd.reviews_id = r.reviews_id and rd.languages_id = '" . $languages_id . "' and r.approved = '1' order by r.reviews_id DESC");  $num_rows = tep_db_num_rows($reviews_query);
  $num_rows = tep_db_num_rows($reviews_query);
  if ($num_rows > 0) {
    $row = 0;
    while (($reviews_values = tep_db_fetch_array($reviews_query)) && ($row < MAX_REVIEWS_IN_PRODUCT_INFO)) {
      $row++;
      $date_added = tep_date_short($reviews_values['date_added']);
?>
<tr>
<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr><tr>
<td valign="top" class="main"><?php echo sprintf(tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $reviews_values['reviews_rating']))) . ' &ndash; <i>' . $reviews_values['customers_name'] . ', ' . $date_added . '</i><br>' . htmlspecialchars(StripSlashes($reviews_values['reviews_text'])) ?></td>
</tr>
<?php
    }
?>
      <tr>
        <td class="smallText"><?php echo sprintf(TEXT_DISPLAY_NUMBER_OF_REVIEWS, '1', $row, $num_rows); ?></td>
        </tr><tr>
        <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, substr(tep_get_all_get_params(), 0, -1)) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?></td>
      </tr>
      </table>
<?php
    if ($num_rows > MAX_REVIEWS_IN_PRODUCT_INFO) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr><tr>
        <td><a href="<?php echo tep_href_link(FILENAME_PRODUCT_REVIEWS, substr(tep_get_all_get_params(), 0, -1)); ?>"><?php echo tep_image_button('button_more_reviews.gif', IMAGE_BUTTON_MORE_REVIEWS); ?></a></td>
      </tr></table>
<?php
    }
  } else {
?>
    <tr>
      <td class="smallText">
<?php echo TEXT_NO_REVIEWS; ?><br>
<?php echo 'Be the first to review ' . $products_name = $product_info['products_name'] . '!</tr><tr></td><td align="right"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, substr(tep_get_all_get_params(), 0, -1)) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?>
      </td></tr></table>
<?php
  	}
  }
} else {
?>

<?php
} //// End Reviews on Product Information page hack
?>
<?php
    }
?>


<!-- End Product Reviews Table -->
</div>
</div>
<table>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
      </tr></table>
<!-- Cross Sell & Also Purchased Table - Also Purchased code moved to /includes/modules/xsell_products.php -->
<?php
//added for cross -sell
   if ( (USE_CACHE == 'true') && !SID) {
    echo tep_cache_also_purchased(3600);
     include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);
   } else {
     include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);
    }
?>
<!-- End Cross Sell & Also Purchased Table -->
</form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
    <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');
	} // end tabbed if/else from the start of this file
?>
