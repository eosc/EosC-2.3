<?php
/*
osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 osCommerce

Released under the GNU General Public License

Featured Products V1.5.2
adapted for Separate Pricing Per Customer v4.1 2006/02/26
and made flexible for number of columns (default 3), added dividing lines ($color_v and $color_h)
see line 28 and further to define these variables (set to background color if you don't want them)
Displays a list of featured products, selected from admin
For use as an Infobox instead of the "New Products" Infobox 
*/
?>
<!-- featured_products //-->
<?php
if(FEATURED_PRODUCTS_DISPLAY == 'true')
{
$featured_products_category_id = $new_products_category_id;
$cat_name_query = tep_db_query("select categories_name from categories_description where categories_id = '" . $featured_products_category_id . "' limit 1");
$cat_name_fetch = tep_db_fetch_array($cat_name_query);
$cat_name = $cat_name_fetch['categories_name'];
$info_box_contents = array();

$color_h = '#bbc3d3'; // color of horizontal lines
$color_v = '#bbc3d3'; // color of vertical lines
$border_style_h = "solid"; // style of horizontal lines: solid, dashed, dotted
$border_style_v = ""; // style of vertical lines
$no_of_columns = 3;

// BOF Separate Pricing Per Customer
if(!tep_session_is_registered('sppc_customer_group_id')) { 
$customer_group_id = '0';
} else {
$customer_group_id = $sppc_customer_group_id;
}
// EOF Separate Pricing Per Customer

if ( (!isset($featured_products_category_id)) || ($featured_products_category_id == '0') ) {
$info_box_contents[] = array('align' => 'left', 'text' => '<a class="infoBoxHeading" href="' . tep_href_link(FILENAME_FEATURED_PRODUCTS) . '">' . TABLE_HEADING_FEATURED_PRODUCTS . '</a>');

list($usec, $sec) = explode(' ', microtime());
srand( (float) $sec + ((float) $usec * 100000) );
$mtm= rand();

$featured_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, NULL as specstat, NULL as specials_new_products_price, p.products_price, pd.products_name from " . TABLE_PRODUCTS . " p left join " . TABLE_FEATURED . " f using(products_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd using(products_id) where p.products_status = '1' and f.status = '1' and pd.language_id = '" . (int)$languages_id . "' order by rand($mtm) DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
} else {
$info_box_contents[] = array('align' => 'left', 'text' => sprintf(TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY, $cat_name));
$featured_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, NULL as specstat, NULL as specials_new_products_price, p.products_price, pd.products_name from " . TABLE_PRODUCTS . " p left join " . TABLE_FEATURED . " f using(products_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd using(products_id), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . $featured_products_category_id . "' and p.products_status = '1' and f.status = '1' and pd.language_id = '" . (int)$languages_id . "' order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
}

if (($no_of_featured_prdcts = tep_db_num_rows($featured_products_query)) > 0) {
while ($_featured_products = tep_db_fetch_array($featured_products_query)) {
$featured_products[] = $_featured_products;
$list_of_prdct_ids[] = $_featured_products['products_id'];
} 

$_select_list_of_prdct_ids = implode("','", $list_of_prdct_ids);
$select_list_of_prdct_ids = "'". $_select_list_of_prdct_ids . "'";

// get all customers_group_prices for products with the particular customer_group_id
// however not necessary for customer_group_id = 0
if ($customer_group_id != '0') {
$pg_query = tep_db_query("select pg.products_id, customers_group_price as price from " . TABLE_PRODUCTS_GROUPS . " pg where products_id in (".$select_list_of_prdct_ids.") and pg.customers_group_id = '".$customer_group_id."'");
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
$specials_query = tep_db_query("select products_id, specials_new_products_price, status as specstat from specials where products_id in (".$select_list_of_prdct_ids.") and status = '1' and customers_group_id = '" .$customer_group_id. "' ");
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


// EOF Separate Pricing Per Customer

$row = 0;
$col = 0; 
$num = 1;
// we need to know what the last row is, because the td on that row should not have a bottom border
$last_row = ( $no_of_featured_prdcts % $no_of_columns == 0 ? ($no_of_featured_prdcts / $no_of_columns) -1 : floor($no_of_featured_prdcts / $no_of_columns ));

// BOF Separate Pricing Per Customer 
// while ($featured_products = tep_db_fetch_array($featured_products_query)) {
for ($x = 0; $x < $no_of_featured_prdcts; $x++) {
if ($num == 1) { new contentBoxHeading($info_box_contents); }
// $featured_products[$x]['products_name'] = tep_get_products_name($featured_products[$x]['products_id']);
if($featured_products[$x]['specstat']) {
$info_box_contents[$row][$col] = array('align' => 'center',
'params' => 'class="smallText" width="' . (floor(100 / $no_of_columns)) . '%" valign="top" style="padding-top: 4px; '. ( $row < $last_row ? 'border-bottom: 1px ' . $border_style_h . ' ' . $color_h . '; ': '') . ( $col < ($no_of_columns - 1) ? 'border-right: 1px ' . $border_style_v . ' ' . $color_v . ';"': '"'),
'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products[$x]['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products[$x]['products_image'], $featured_products[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products[$x]['products_id']) . '">' . $featured_products[$x]['products_name'] . '</a><br><s>' . $currencies->display_price($featured_products[$x]['products_price'], tep_get_tax_rate($featured_products[$x]['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' . 
$currencies->display_price($featured_products[$x]['specials_new_products_price'], tep_get_tax_rate($featured_products[$x]['products_tax_class_id'])) . '</span>');
} else {
$info_box_contents[$row][$col] = array('align' => 'center',
'params' => 'class="smallText" width="' . (floor(100 / $no_of_columns)) . '%" valign="top" style="padding-top: 4px; '. ( $row < $last_row ? 'border-bottom: 1px ' . $border_style_h . ' ' . $color_h . '; ': '') . ( $col < ($no_of_columns - 1) ? 'border-right: 1px ' . $border_style_v . ' ' . $color_v . ';"': '"'),
'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products[$x]['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products[$x]['products_image'], $featured_products[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products[$x]['products_id']) . '">' . $featured_products[$x]['products_name'] . '</a><br>' . $currencies->display_price($featured_products[$x]['products_price'], tep_get_tax_rate($featured_products[$x]['products_tax_class_id'])));
}
// EOF Separate Pricing Per Customer
// if we reached the last of the products, make sure the row of table cells is filled with empty cells if needed
// just adding spaces for text does not produce an empty cell: use a  instead
if ($num == $no_of_featured_prdcts ) {
for ($i = $col + 1; $i < $no_of_columns; $i++) {
$info_box_contents[$row][$i] = array('align' => 'center', 'params' => 'class="smallText" width="' . (floor(100 / $no_of_columns)) . '%" valign="top" style="padding-top: 4px; '. ( $row < $last_row ? 'border-bottom: 1px ' . $border_style_h . ' ' . $color_h . '; ': '') . ( $i < ($no_of_columns - 1) ? 'border-right: 1px ' . $border_style_v . ' ' . $color_v . ';"': '"'),
'text' => '');
}
} // end if ($num == $no_of_featured_prdcts )

$col ++;
$num++;
if ($col > ($no_of_columns -1)) {
$col = 0;
$row ++;
}
} // end for ($x=0; $x < $no_of_featured_prdcts; $x++)
if($num > 1) {

new contentBox($info_box_contents);
}
} else // If it's disabled, then include the original New Products box
{
include (DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);
}
?>
<!-- featured_products_eof //-->