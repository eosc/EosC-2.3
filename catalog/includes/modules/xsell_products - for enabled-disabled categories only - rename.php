<?php
/*
$Id: xsell_products.php, v1  2002/09/11
// adapted for Separate Pricing Per Customer v4 2005/02/24

osCommerce, Open Source E-Commerce Solutions
<http://www.oscommerce.com>

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_XSELL_PRODUCTS);

// BOF Separate Pricing Per Customer
 if(!tep_session_is_registered('sppc_customer_group_id')) {
 $customer_group_id = '0';
 } else {
  $customer_group_id = $sppc_customer_group_id;
 }

if ($_GET['products_id']) {

//Cache
$dircache = DIR_FS_CACHE_XSELL . $_GET['products_id'] . '/';
$filename = $dircache  . $languages_id . '-' . $customer_group_id . '.php';
$cache = '<?php 
     $info_box_contents = array();
     $info_box_contents[] = array(\'align\' => \'left\', \'text\' => TEXT_XSELL_PRODUCTS);
     new contentBoxHeading($info_box_contents);
     $info_box_contents = array();';
if (file_exists($filename))
 {require $filename;}
else
 {
//Fin cache

if ($customer_group_id != '0') {
// ################" Added Enable / Disable Catgories ###################
$xsell_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, IF(pg.customers_group_price IS NOT NULL, pg.customers_group_price, p.products_price) as products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' and pg.customers_group_id = '".$customer_group_id."' order by xp.products_id asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
//$xsell_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, IF(pg.customers_group_price IS NOT NULL, pg.customers_group_price, p.products_price) as products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' and pg.customers_group_id = '".$customer_group_id."' order by sort_order asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
// ################" End Added Enable / Disable Catgories ###################
} else {
// ################" Added Enable / Disable Catgories ###################
//$xsell_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' order by xp.products_id asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
//$xsell_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where c.categories_status='1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' order by xp.products_id asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
$xsell_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' order by xp.products_id asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
// ################" End Added Enable / Disable Catgories ###################
//$xsell_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' order by sort_order asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
}
// EOF Separate Pricing Per Customer

$num_products_xsell = tep_db_num_rows($xsell_query);
if ($num_products_xsell > 0) {
?>
<!-- xsell_products //-->
<?php
     $info_box_contents = array();
     $info_box_contents[] = array('align' => 'left', 'text' => TEXT_XSELL_PRODUCTS);
     new contentBoxHeading($info_box_contents);

     $row = 0;
     $col = 0;
     $info_box_contents = array();
     while ($xsell = tep_db_fetch_array($xsell_query)) {
       $xsell['specials_new_products_price'] = tep_get_products_special_price($xsell['products_id']);

if ($xsell['specials_new_products_price']) {
     $xsell_price =  '<s>' . $currencies->display_price($xsell['products_price'], tep_get_tax_rate($xsell['products_tax_class_id'])) . '</s><br>';
     $xsell_price .= '<span class="productSpecialPrice">' . $currencies->display_price($xsell['specials_new_products_price'], tep_get_tax_rate($xsell['products_tax_class_id'])) . '</span>';
   } else {
     $xsell_price =  $currencies->display_price($xsell['products_price'], tep_get_tax_rate($xsell['products_tax_class_id']));
   }
       //Cache
	   $text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $xsell['products_image'], $xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . $xsell['products_name'] .'</a><br>' . $xsell_price. '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $xsell['products_id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', TEXT_BUY . $xsell['products_name'] . TEXT_NOW) .'</a>';
	   //Fin cache
	   $info_box_contents[$row][$col] = array('align' => 'center',
                                              'params' => 'class="smallText" width="33%" valign="top"',
                                              'text' => $text) ; //Modifi� Cache
       //Cache
       $cache .= '$info_box_contents[' .$row . '][' . $col . '] = array(\'align\' => \'center\',
                                              \'params\' => \'class="smallText" width="33%" valign="top"\',
                                              \'text\' => \'' . str_replace("'", "\'", $text) .'\');';
       //Fin cache	   
	   $col ++;
       if ($col > 2) {
         $col = 0;
         $row ++;
       }
     }
new contentBox($info_box_contents);
//Cache
	 $cache .= 'new contentBox($info_box_contents); ?>';
	 if(!is_dir($dircache)) { mkdir($dircache,0777); }
	 $fp = fopen($filename , 'w');
     $fout = fwrite($fp , $cache);
     fclose($fp);
}
//Fin Cache
     
?>
<!-- xsell_products_eof //-->
<?php
   }
 }
?>