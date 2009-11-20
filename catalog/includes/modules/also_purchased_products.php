<?php
/*-----------------------------------------------------------------------------*\

----
-- MODS
-- 12-10-05 simon@ibridge.co.uk  rewrite of SQL logic
----

#################################################################################
#	Script name: Optimized also_puchased_products.php module
#	Version: v 1.0
#
#	Copyright (C) 2005 Bobby Easland
#	Internet moniker: Chemo
#	Contact: chemo@mesoimpact.com
#
#	This script is free software; you can redistribute it and/or
#	modify it under the terms of the GNU General Public License
#	as published by the Free Software Foundation; either version 2
#	of the License, or (at your option) any later version.
#
#	This program is distributed in the hope that it will be useful,
#	but WITHOUT ANY WARRANTY; without even the implied warranty of
#	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#	GNU General Public License for more details.
#
#	Script is intended to be used with:
#	osCommerce, Open Source E-Commerce Solutions
#	http://www.oscommerce.com
#	Copyright (c) 2003 osCommerce
#
#	I have rewritten the way the initial selections are done, this makes
#	mySQL do all of the work and means there is no limit or penalty on very
#	popular products. This now also only returns the number of items you
#	would like to display (MAX_DISPLAY_ALSO_PURCHASED) and you can also
#	change how this selects them by changing line 111&118 which currently
#	selects the most popular products :-
#
#	Or
#	To select the most expensive products :-
#	111 - SELECT op2.products_id, p2.products_image, p2.products_price
#	118 - GROUP BY op2.products_id, p2.products_image, p2.products_price
#
#	Or
#	To select by the newest products :-
#	111 - SELECT op2.products_id, p2.products_image, p2.products_date_added
#	118 - GROUP BY op2.products_id, p2.products_image, p2.products_date_added
#
#	Or
#	To select by the products you have the most of :-
#	111 - SELECT op2.products_id, p2.products_image, p2.products_quantity
#	118 - GROUP BY op2.products_id, p2.products_image, p2.products_quantity
################################################################################
\*-----------------------------------------------------------------------------*/

/*---------------------------------------------------------*\
#############################################################
#	Use the define below to turn the stock code on and off	#
# 	DO NOT use 'true' 										#
#	It has to be true or false without the single quotes	#
#	since the type is used below for comparison				#
#############################################################
\*---------------------------------------------------------*/
  define('USE_STOCK_OSC', false);

  if (isset($_GET['products_id']) && USE_STOCK_OSC === true) {
    $orders_query = tep_db_query("select p.products_id, p.products_image from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . (int)$_GET['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$_GET['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>
<!-- also_purchased_products //-->
<?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => TEXT_ALSO_PURCHASED_PRODUCTS);

      new contentBoxHeading($info_box_contents);

      $row = 0;
      $col = 0;
      $info_box_contents = array();
die($orders_query);
      while ($orders = tep_db_fetch_array($orders_query)) {
        $orders['products_name'] = tep_get_products_name($orders['products_id']);
        $info_box_contents[$row][$col] = array('align' => 'center',
                                               'params' => 'class="smallText" width="33%" valign="top"',
                                               'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a>');

        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }

      new contentBox($info_box_contents);
?>
<!-- also_purchased_products_eof //-->
<?php
    }
  }
 
/*-------------------------------------------*\
############################################### 
#	Modified query for also_purchased module. #	
###############################################
\*-------------------------------------------*/

  if (isset($_GET['products_id']) && USE_STOCK_OSC === false) {
/*
//**si**
    # First let's get all the orders for this product
	$num_orders = tep_db_query("select orders_id from " . TABLE_ORDERS_PRODUCTS . "  where products_id = '" . (int)$_GET['products_id'] . "'");
    # If there are no sales then skip the rest. Not likely but why not.
	if ( tep_db_num_rows($num_orders) ){
		# Let's loop the array		
		while ($temp = tep_db_fetch_array($num_orders)){
			$orders_array[] = $temp['orders_id'];
		}
		# Create the select union query
		foreach($orders_array as $index => $oID){
			$union[] = "
			SELECT op.orders_id, op.products_id, p.products_image
			FROM orders_products op
			LEFT JOIN products p USING (products_id)
			WHERE op.orders_id=".$oID." and op.products_id!='".(int)$_GET['products_id']."' and p.products_status='1'";			
		}
		# Implode the array with a UNION and finish it off with the order by and limit
		$union_str = implode(" UNION ", $union) . " ORDER BY orders_id desc LIMIT ".MAX_DISPLAY_ALSO_PURCHASED;
		# Execute the query
		$orders_query = tep_db_query($union_str);
*/

$sql = "SELECT op2.products_id, p2.products_image, COUNT( op2.orders_id ) nbr_times_ordered ".
       "FROM " . TABLE_ORDERS_PRODUCTS . " op1 ".
       "INNER JOIN " . TABLE_ORDERS_PRODUCTS . " op2 ON op2.orders_id = op1.orders_id ".
       "AND op2.products_id != '" . (int)$_GET['products_id'] . "' ".
       "INNER JOIN " . TABLE_PRODUCTS . " p2 ON p2.products_id = op2.products_id AND p2.products_status='1' ".
       "WHERE op1.products_id = '" . (int)$_GET['products_id'] . "' ".
       "GROUP BY op2.products_id, p2.products_image ".
       "ORDER BY 3 DESC ".
       "LIMIT ".MAX_DISPLAY_ALSO_PURCHASED;
$orders_query = tep_db_query($sql);
///**si**	end

	    # If more rows than the minimum was returned continue
		if ( tep_db_num_rows($orders_query) >= MIN_DISPLAY_ALSO_PURCHASED ){		
/*------------------------------------------------------------------------------*\
#	It's stock osC code for the rest of the script.  The only excepttion is 
#	that there is one more ending bracket at the EOF.
\*------------------------------------------------------------------------------*/				
?>
<!-- also_purchased_products //-->
<?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => TEXT_ALSO_PURCHASED_PRODUCTS);

      new contentBoxHeading($info_box_contents);

      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($orders = tep_db_fetch_array($orders_query)) {
        $orders['products_name'] = tep_get_products_name($orders['products_id']);
        $info_box_contents[$row][$col] = array('align' => 'center',
                                               'params' => 'class="smallText" width="33%" valign="top"',
                                               'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a>');

        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }

      new contentBox($info_box_contents);
?>
<!-- also_purchased_products_eof //-->
<?php
///**si**
///	}
///**si**	end
  } # END if $orders_query
 }
?>