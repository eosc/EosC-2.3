<?php
/*
  $Id: shipping_estimator.php,v 2.20 2004/07/01 15:16:07 eml Exp $

  v2.00 by Acheron
  (see Install.txt for partial version history)

  Copyright (c) 2004

  Released under the GNU General Public License
  
  azer:
  qq modif
*/

define('CART_ITEM', 'Products quantity :'); // azer for 2.20
  define('CART_SHIPPING_CARRIER_TEXT', 'Carrier');
  define('CART_SHIPPING_OPTIONS', 'Estimate Shipping'); //azer
  define('CART_SHIPPING_OPTIONS_LOGIN', '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">Log In Here</a> to display your personal shipping costs.<br />&nbsp;<br />');
  define('CART_SHIPPING_METHOD_TEXT','Delivery Methods');
  define('CART_SHIPPING_METHOD_RATES','Rates');
  define('CART_SHIPPING_METHOD_TO','Ship to : ');
  define('CART_SHIPPING_METHOD_TO_NOLOGIN', '<b>Ship to:</b>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Log In</u></a>');
  define('CART_SHIPPING_METHOD_FREE_TEXT','Free Shipping');
  define('CART_SHIPPING_METHOD_ALL_DOWNLOADS',' (Virtual Products)');
  define('CART_SHIPPING_METHOD_RECALCULATE','Recalculate');
  define('CART_SHIPPING_METHOD_ADDRESS','Address:');
  define('CART_OT','Order Total Estimate'); //tradish
  define('CART_SELECT','select');
  define('CART_SELECT_THIS_METHOD','Click to select this shipping method in the total.'); // added for 2.10

?>