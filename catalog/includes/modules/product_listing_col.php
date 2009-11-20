<?php
/*
  original: $Id: product_listing.php,v 1.44 2003/06/09 22:49:43 hpdl Exp $
  new: product_listing_col.php modified for SPPC 2005/03/10 Adam Bradley 
  corrected for proper HTML 2005/07/10 JanZ

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  

*/
//if number of column per row is 1, include the original product_listing.php
if (PRODUCT_LIST_NUMCOL == 1) {
	include(dirname(__FILE__).'/product_listing.php');

} else{
	//display the version 2.2 product_listing_col.php code
	
//bof product listing with attributes
$list_box_contents = array();
$list_box_contents[] = array('params' => 'class="productListing-heading"');
$cur_row = sizeof($list_box_contents) - 1;

for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
		case 'PRODUCT_LIST_MULTIPLE':
			$add_multiple = "1";
			echo '<form name="buy_now_" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=add_multiple', 'NONSSL') . '">';
			break;
	}
}
//eof product listing with attributes
?>
<?php
$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
  <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
  <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
</tr>
</table>
<?php
}

$list_box_contents = array();

if ($listing_split->number_of_rows > 0) {

	//BOF version 2.2 modification
	if (PRODUCT_LIST_NUMCOL <= 0) {
		$colnum = 3;
		$tdsize = floor(100/3);
	} else {
		$colnum = PRODUCT_LIST_NUMCOL;
		$tdsize = floor(100/PRODUCT_LIST_NUMCOL);
	}
	//EOF version 2.2 modification
	
  $row = 0;
  $rows = 0;
  $column = 0;
  $listing_query = tep_db_query($listing_split->sql_query);
// BOF Separate Pricing per Customer
  $no_of_listings = tep_db_num_rows($listing_query);
// global variable (session) $sppc_customer_group_id -> local variable customer_group_id

if(!tep_session_is_registered('sppc_customer_group_id')) { 
$customer_group_id = '0';
} else {
 $customer_group_id = $sppc_customer_group_id;
}
  
while ($_listing = tep_db_fetch_array($listing_query)) {
$listing[] = $_listing;
$list_of_prdct_ids[] = $_listing['products_id'];
} 
// next part is a debug feature, when uncommented it will print the info that this module receives
/* 
 echo '<pre>';
 print_r($listing);
 echo '</pre>';
*/   
$select_list_of_prdct_ids = "products_id = '".$list_of_prdct_ids[0]."' ";
if ($no_of_listings > 1) {
 for ($n = 1; $n < count($list_of_prdct_ids); $n++) {
 $select_list_of_prdct_ids .= "or products_id = '".$list_of_prdct_ids[$n]."' "; 
 }
}

// get all product prices for products with the particular customer_group_id
// however not necessary for customer_group_id = 0
if ($customer_group_id != '0') {
$pg_query = tep_db_query("select pg.products_id, customers_group_price as price from " . TABLE_PRODUCTS_GROUPS . " pg where (".$select_list_of_prdct_ids.") and pg.customers_group_id = '".$customer_group_id."' ");
//   $no_of_pg_products = tep_db_num_rows($pg_query);
while ($pg_array = tep_db_fetch_array($pg_query)) {
$new_prices[] = array ('products_id' => $pg_array['products_id'], 'products_price' => $pg_array['price'], 'specials_new_products_price' => '', 'final_price' => $pg_array['price']);
}
 for ($x = 0; $x < $no_of_listings; $x++) {
// replace products prices with those from customers_group table
    if(!empty($new_prices)) {
       for ($i = 0; $i < count($new_prices); $i++) {
   if( $listing[$x]['products_id'] == $new_prices[$i]['products_id'] ) {
$listing[$x]['products_price'] = $new_prices[$i]['products_price'];
$listing[$x]['specials_new_products_price'] = $new_prices[$i]['specials_new_products_price'];
$listing[$x]['final_price'] = $new_prices[$i]['final_price'];
}
   }
} // end if(!empty($new_prices)
$listing[$x]['specials_new_products_price'] = ''; // makes sure that a retail specials price doesn't carry over to another customer group
$listing[$x]['final_price'] = $listing[$x]['products_price']; // final price should not be the retail special price
} // end for ($x = 0; $x < $no_of_listings; $x++)
} // end if ($customer_group_id != '0')
// an extra query is needed for all the specials

$specials_query = tep_db_query("select products_id, specials_new_products_price from " . TABLE_SPECIALS . " where (".$select_list_of_prdct_ids.") and status = '1' and customers_group_id = '" .$customer_group_id. "'");
while ($specials_array = tep_db_fetch_array($specials_query)) {
$new_s_prices[] = array ('products_id' => $specials_array['products_id'], 'products_price' => '', 'specials_new_products_price' => $specials_array['specials_new_products_price'] , 'final_price' => $specials_array['specials_new_products_price']);
}

// add the correct specials_new_products_price and replace final_price
for ($x = 0; $x < $no_of_listings; $x++) {
    
      if(!empty($new_s_prices)) {
   for ($i = 0; $i < count($new_s_prices); $i++) {
 if( $listing[$x]['products_id'] == $new_s_prices[$i]['products_id'] ) {
   $listing[$x]['specials_new_products_price'] = $new_s_prices[$i]['specials_new_products_price'];
   $listing[$x]['final_price'] = $new_s_prices[$i]['final_price'];
 }
      }
  } // end if(!empty($new_s_prices)
} // end for ($x = 0; $x < $no_of_listings; $x++)
  
//    while ($listing = tep_db_fetch_array($listing_query)) { (was original code)

// WARNING the code assumes there are three products per row. To use a different number change the number
// at line 195: if ($column >= 3) and the code to fill up the table row below that accordingly
//  $counter = $row;
//  $class_for_buy_now = 'class="productListing-odd"';
//  $list_box_contents[$row] = array('params' => 'class="productListing-odd"');
for ($x = 0; $x < $no_of_listings; $x++) {

		$rows++;

	    if (($rows/2) == floor($rows/2) && ($row > $counter)) {
			$list_box_contents[$row] = array('params' => 'class="productListing-even"');
			//2.2 modification ,add width in td
			$class_for_buy_now = 'class="productListing-even" width="'.$tdsize.'%"';
			$counter = $row;
	    } else {
			if ($row > $counter) {
				$list_box_contents[$row] = array('params' => 'class="productListing-odd"');
				//2.2 modification ,add width in td
				$class_for_buy_now = 'class="productListing-odd" width="'.$tdsize.'%"';
				$counter = $row;
			}
	    }

    $product_contents = array();

    for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
      $lc_align = '';

      switch ($column_list[$col]) {
        case 'PRODUCT_LIST_MODEL':
          $lc_align = '';
          $lc_text = '&nbsp;' . $listing[$x]['products_model'] . '&nbsp;';
          break;
        case 'PRODUCT_LIST_NAME':
          $lc_align = '';
          if (isset($_GET['manufacturers_id'])) {
            $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing[$x]['products_id']) . '">' . $listing[$x]['products_name'] . '</a>';
          } else {
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing[$x]['products_id']) . '">' . $listing[$x]['products_name'] . '</a>&nbsp;';
          }
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $lc_align = '';
          $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing[$x]['manufacturers_id']) . '">' . $listing[$x]['manufacturers_name'] . '</a>&nbsp;';
          break;
        case 'PRODUCT_LIST_PRICE':
          $lc_align = 'right';
  
          if (tep_not_null($listing[$x]['specials_new_products_price'])) {
            $lc_text = '&nbsp;<s>' .  $currencies->display_price($listing[$x]['products_price'], tep_get_tax_rate($listing[$x]['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($listing[$x]['specials_new_products_price'], tep_get_tax_rate($listing[$x]['products_tax_class_id'])) . '</span>&nbsp;';
          } else {
            $lc_text = '&nbsp;' . $currencies->display_price($listing[$x]['products_price'], tep_get_tax_rate($listing[$x]['products_tax_class_id'])) . '&nbsp;';
          }
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $lc_align = 'right';
          $lc_text = '&nbsp;' . $listing[$x]['products_quantity'] . '&nbsp;';
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $lc_align = 'right';
          $lc_text = '&nbsp;' . $listing[$x]['products_weight'] . '&nbsp;';
          break;
        case 'PRODUCT_LIST_IMAGE':
          $lc_align = 'center';
          if (isset($_GET['manufacturers_id'])) {
            $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing[$x]['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing[$x]['products_image'], $listing[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
          } else {
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing[$x]['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing[$x]['products_image'], $listing[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
          }
          break; // EOF Separate Pricing per Customer
        case 'PRODUCT_LIST_BUY_NOW':
          $lc_align = 'center';
          $lc_text = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing[$x]['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW, 'style="padding-top: 5px;"') . '</a>&nbsp;';
          break; 
      }
      $product_contents[] = $lc_text;

}
    $lc_text = implode('<br>', $product_contents);
    $list_box_contents[$row][$column] = array('align' => 'center',
    										  'valign' => 'top',
                                              'params' => 'class="productListing-data"',
                                              'text'  => $lc_text);
    $column ++;
		//BOF version 2.2 modification
		//change the hardcoded value of 3 to $colnum variable
		if ($column >= $colnum || $x == ($no_of_listings -1) ) {
			$row ++; // we start a new tr here  with $list_box_contents
			$list_box_contents[$row] = array('params' => $class_for_buy_now);
			$column = 0;
		}
		//EOF version 2.2 modification
	} // end for ($x = 0; $x < $no_of_listings; $x++) 

  new productListingBox($list_box_contents);
} else {
  $list_box_contents = array();

  $list_box_contents[0] = array('params' => 'class="productListing-odd"');
  $list_box_contents[0][] = array('params' => 'class="productListing-data"',
                                 'text' => TEXT_NO_PRODUCTS);

  new productListingBox($list_box_contents);
}

if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
  <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
  <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
</tr>
</table>
<?php
	}
   }
?>