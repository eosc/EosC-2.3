<?php
/*
  $Id: popup_image.php,v 1.18 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// Ultrapics mods
  $products_query = tep_db_query("select pd.products_name, p.products_model, p.products_image, p.products_image_lrg, p.products_image_xl_1, p.products_image_xl_2, p.products_image_xl_3, p.products_image_xl_4, p.products_image_xl_5, p.products_image_xl_6 from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_status = '1' and p.products_id = '" . (int)$_GET['pID'] . "' and pd.language_id = '" . (int)$languages_id . "'");
  $products = tep_db_fetch_array($products_query);
  $sts->template['productname'] = $products['products_name'];
  $sts->template['productmodel'] =  $products['products_model'];

           if ($_GET['image'] ==0) {
     $sts->template['popupimage'] = tep_image(DIR_WS_IMAGES . $products['products_image_lrg'],'','','', 'name="prodimage"');
     } elseif ($_GET['image'] ==1) {
     $sts->template['popupimage'] = tep_image(DIR_WS_IMAGES . $products['products_image_xl_1'],'','','', 'name="prodimage"');
     } elseif ($_GET['image'] ==2) {
     $sts->template['popupimage'] = tep_image(DIR_WS_IMAGES . $products['products_image_xl_2'],'','','', 'name="prodimage"');
     } elseif ($_GET['image'] ==3) {
     $sts->template['popupimage'] = tep_image(DIR_WS_IMAGES . $products['products_image_xl_3'],'','','', 'name="prodimage"');
     } elseif ($_GET['image'] ==4) {
     $sts->template['popupimage'] = tep_image(DIR_WS_IMAGES . $products['products_image_xl_4'],'','','', 'name="prodimage"');
     } elseif ($_GET['image'] ==5) {
     $sts->template['popupimage'] = tep_image(DIR_WS_IMAGES . $products['products_image_xl_5'],'','','', 'name="prodimage"');
     } elseif ($_GET['image'] ==6) {
     $sts->template['popupimage'] = tep_image(DIR_WS_IMAGES . $products['products_image_xl_6'],'','','', 'name="prodimage"');
     } elseif ($_GET['image'] ==7) {
     $sts->template['popupimage'] = tep_image(DIR_WS_IMAGES . $products['products_image'],'','','', 'name="prodimage"');
     } ?>