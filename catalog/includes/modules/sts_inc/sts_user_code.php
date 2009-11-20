<?php
/*
$Id: sts_user_code.php,v 4.1 2005/02/05 05:57:21 rigadin Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2005 osCommerce

Released under the GNU General Public License

Based on: Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com
STS v4.1 by Rigadin (rigadin@osc-help.net)
*/
/* The following code is a sample of how to add new boxes easily.
  Use as many blocks as you need and just change the block names.
   $sts->start_capture();
   require(DIR_WS_BOXES . 'new_thing_box.php');
   $sts->stop_capture('newthingbox', 'box');  // 'box' makes the system remove some html code before and after the box. Otherwise big mess!
 Note: If $sts->stop_capture('newthingbox', 'box') is followed by $sts->start_capture, you can replace both by $sts->restart_capture('newthingbox', 'box')
 Another way to declare STS variables is to enter them directly into the STS array:
   $sts->template['MyText']='Hello World';
*/

    $sts->start_capture();
    echo "\n<!-- Start Category Menu -->\n";
    echo tep_draw_form('goto', FILENAME_DEFAULT, 'get', '');
    echo tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo "</form>\n";
    echo "<!-- End Category Menu -->\n";
    $sts->stop_capture('catmenu');

   $sts->start_capture();
   require(DIR_WS_BOXES . 'newsletter.php');
   $sts->stop_capture('newsletter', 'box');
   
  function tep_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
    global $languages_id;

    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => "Catalog");

    if ($include_itself) {
      $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$parent_id . "'");
      $category = tep_db_fetch_array($category_query);
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
    }

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
      $category_tree_array = tep_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    }

    return $category_tree_array;
  }

$sts->start_capture();
$productno = $cart->count_contents();
$totalprice = $currencies->format($cart->show_total());

if ($productno > 1) {
echo ENTRY_HCART_CONTAINS;
echo " ";
echo $productno;
echo " ";
echo ENTRY_HCART_ITEMS;
echo " "; echo " ";
echo ENTRY_HCART_PRICE;
echo " ";
echo $totalprice;
} elseif ($productno == 0) {
echo ENTRY_HCART_EMPTY;
} else {
echo ENTRY_HCART_CONTAINS;
echo " ";
echo $productno;
echo " ";
echo ENTRY_HCART_ITEM;
echo " "; echo " ";
echo ENTRY_HCART_PRICE;
echo " ";
echo $totalprice;
}
$sts->stop_capture ('headcart');

/* // START COMPATIBILITY WITH STS 2 AND 3: $footer
// Uncomment this piece of code if you want $footer like in STS.
// Keep it as comment if you want to have $footer displaying only the copyright info. 
  $sts->start_capture();
?>
<table border="0" width="100%" cellspacing="0" cellpadding="1">
  <tr class="footer">
    <td class="footer">&nbsp;&nbsp;<?php echo strftime(DATE_FORMAT_LONG); ?>&nbsp;&nbsp;</td>
    <td align="right" class="footer">&nbsp;&nbsp;<?php echo $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted; ?>&nbsp;&nbsp;</td>
  </tr>
</table>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="smallText">
<?php echo FOOTER_TEXT_BODY ?>
    </td>
  </tr>
</table>
<?php  
  $sts->stop_capture('footer');
// END COMPATIBILITY WITH STS 2 AND 3: $footer */

/* // START COMPATIBILITY WITH STS 2 AND 3: $banner
  $sts->start_capture();
  if ($banner = tep_banner_exists('dynamic', '468x50')) {
?>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><?php echo $sts->template['banner_only']; ?></td>
  </tr>
</table>
<?php
  }
$sts->stop_capture('banner');
// END COMPATIBILITY WITH STS 2 AND 3: $banner */

/* // START COMPATIBILITY WITH STS 2 AND 3: $cat_ and $urlcat_
// See if there are any $url_ or $urlcat_ variables in the template file, if so, flag to read them
if (strpos($sts->template['template_html'], "\$cat_") or strpos($sts->template['template_html'], "\$urlcat_") ) {
	print "<!-- STS: Reading $cat_ and $urlcat_ tags, recommend not using them -->";
	$get_categories_description_query = tep_db_query("SELECT categories_id, categories_name FROM " . TABLE_CATEGORIES_DESCRIPTION);
	// Loop through each category (in each language) and create template variables for each name and path
	while ($categories_description = tep_db_fetch_array($get_categories_description_query)) {
	      $cPath_new = tep_get_path($categories_description['categories_id']);
	      $path = substr($cPath_new, 6); // Strip off the "cPath=" from string
	
	      $catname = $categories_description['categories_name'];
	      $catname = str_replace(" ", "_", $catname); // Replace Spaces in Category Name with Underscores
	
	      $sts->template["cat_" . $catname] = tep_href_link(FILENAME_DEFAULT, $cPath_new);
	      $sts->template["urlcat_" . $catname] = tep_href_link(FILENAME_DEFAULT, $cPath_new);
	      $sts->template["cat_" . $path] = tep_href_link(FILENAME_DEFAULT, $cPath_new);
	      $sts->template["urlcat_" . $path] = tep_href_link(FILENAME_DEFAULT, $cPath_new);
	}
}
*/ // END COMPATIBILITY WITH STS 2 AND 3: $cat_ and $urlcat_
?>
