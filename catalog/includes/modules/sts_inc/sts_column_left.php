<?php
/*
  $Id: sts_column_left.php,v 4.3.3 2006/03/12 22:06:41 rigadin Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License

STS v4.3.3 by Rigadin (rigadin@osc-help.net)
Based on: Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com
*/

  $sts->restart_capture(); // Clear buffer but do not save it nowhere, no interesting information in buffer.
// Get categories box from db or cache  
  if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_categories_box();
  } else {
    include(DIR_WS_BOXES . 'categories.php');
  }  
  $sts->restart_capture ('categorybox', 'box');  

// Get manufacturer box from db or cache  
  if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_manufacturers_box();
  } else {
    include(DIR_WS_BOXES . 'manufacturers.php');
  }
  $sts->restart_capture ('manufacturerbox', 'box');

  require(DIR_WS_BOXES . 'whats_new.php');
  $sts->restart_capture ('whatsnewbox', 'box'); // Get What's new box
  
  require(DIR_WS_BOXES . 'search.php');
  $sts->restart_capture ('searchbox', 'box'); // Get search box
  
  require(DIR_WS_BOXES . 'pages.php');
  $sts->restart_capture ('informationbox', 'box');  // Get information box

  require(DIR_WS_BOXES . 'shopping_cart.php');
  $sts->restart_capture ('cartbox', 'box'); // Get shopping cart box

  if (isset($_GET['products_id'])) include(DIR_WS_BOXES . 'manufacturer_info.php');
  $sts->restart_capture ('maninfobox', 'box'); // Get manufacturer info box (empty if no product selected)

  if (tep_session_is_registered('customer_id')) include(DIR_WS_BOXES . 'order_history.php');
  $sts->restart_capture ('orderhistorybox', 'box'); // Get customer's order history box (empty if visitor not logged)
  
  include(DIR_WS_BOXES . 'best_sellers.php');
  $sts->restart_capture ('bestsellersbox_only', 'box'); // Get bestseller box only, new since v4.0.5

// Get bestseller or product notification box. If you use this, do not use these boxes separately!  
  if (isset($_GET['products_id'])) {
    include(DIR_WS_BOXES . 'product_notifications.php');
	$sts->restart_capture ('notificationbox', 'box'); // Get product notification box
  
// Get bestseller or product notification box. If you use this, do not use these boxes separately!    
    if (tep_session_is_registered('customer_id')) {
      $check_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "' and global_product_notifications = '1'");
      $check = tep_db_fetch_array($check_query);
      if ($check['count'] > 0) {
        $sts->template['bestsellersbox']=$sts->template['bestsellersbox_only']; // Show bestseller box if customer asked for general notifications
      } else {
        $sts->template['bestsellersbox']=$sts->template['notificationbox']; // Otherwise select notification box
      }
    } else {
      $sts->template['bestsellersbox']=$sts->template['notificationbox']; // 
    }
  } else {
    $sts->template['bestsellersbox']=$sts->template['bestsellersbox_only'];
	$sts->template['notificationbox']='';
  }

  include(DIR_WS_BOXES . 'specials.php');
  $sts->restart_capture ('specialbox', 'box'); // Get special box
  $sts->template['specialfriendbox']=$sts->template['specialbox']; // Shows specials or tell a friend
  
  if (isset($_GET['products_id']) && basename($PHP_SELF) != FILENAME_TELL_A_FRIEND) {
    include(DIR_WS_BOXES . 'tell_a_friend.php');
	$sts->restart_capture ('tellafriendbox', 'box'); // Get tell a friend box
	$sts->template['specialfriendbox']=$sts->template['tellafriendbox']; // Shows specials or tell a friend
  } else $sts->template['tellafriendbox']='';
  

// Get languages and currencies boxes, empty if in checkout procedure  
  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
    include(DIR_WS_BOXES . 'languages.php');
    $sts->restart_capture ('languagebox', 'box');

    include(DIR_WS_BOXES . 'currencies.php');
    $sts->restart_capture ('currenciesbox', 'box');
  } else {
    $sts->template['languagebox']='';
    $sts->template['currenciesbox']='';
  }
  if (basename($PHP_SELF) != FILENAME_PRODUCT_REVIEWS_INFO) {
    require(DIR_WS_BOXES . 'reviews.php');
    $sts->restart_capture ('reviewsbox', 'box');  // Get the reviews box
  } else {
    $sts->template['reviewsbox']='';
  }	
?>