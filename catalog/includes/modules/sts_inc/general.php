<?php
/*
$Id: general.php,v 4.5 2005/11/03 05:57:21 rigadin Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2005 osCommerce

Released under the GNU General Public License

Based on: Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com
STS v4.3 by Rigadin (rigadin@osc-help.net)
*/

// Set $templatedir and $templatepath (aliases) to current template path on web server, allowing for HTTP/HTTPS differences, removing the trailing slash
	$sts->template['templatedir'] = substr(((($request_type == 'SSL') ? DIR_WS_HTTPS_CATALOG : DIR_WS_HTTP_CATALOG) . STS_TEMPLATE_DIR),0,-1);
//	$sts->template['templatepath'] = $sts->template['templatedir']; // Deprecated in v4.3, use $templatedir instead
	
	$sts->template['htmlparams'] = HTML_PARAMS; // Added in v4.0.7
	
    $sts->template['date'] = strftime(DATE_FORMAT_LONG);
    
	$sts->template['sid'] =  tep_session_name() . '=' . tep_session_id();
    $sts->template['cataloglogo'] = '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(STS_TEMPLATE_DIR.'images/'.$language . '/header_logo.gif', STORE_NAME) . '</a>'; // Modified in v4.3
    $sts->template['urlcataloglogo'] = tep_href_link(FILENAME_DEFAULT);

    // Deprecated in v4.3, use $urlmyaccount instead.
    //$sts->template['urlmyaccountlogo'] = tep_href_link(FILENAME_ACCOUNT, '', 'SSL');

    $sts->template['cartlogo'] = '<a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL') . '">' . tep_image(STS_TEMPLATE_DIR.'images/'.$language. '/header_cart.gif', HEADER_TITLE_CART_CONTENTS) . '</a>';

			$sts->template['myaccountlogo'] = '<a href=' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . ' class="headerNavigation">' . tep_image(STS_TEMPLATE_DIR.'images/'.$language . '/header_account.gif', HEADER_TITLE_MY_ACCOUNT) . '</a>';
    // Deprecated in v4.3, use $urlcartcontents instead.
//    $sts->template['urlcartlogo'] = tep_href_link(FILENAME_SHOPPING_CART);

    // Get logo from template folder, depending on language. Changed in v4.3
		$sts->template['checkoutlogo'] = '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image(STS_TEMPLATE_DIR.'images/'.$language.'/header_checkout.gif', HEADER_TITLE_CHECKOUT) . '</a>';
		// Deprecated in v4.3, use $urlcheckout instead
    //$sts->template['urlcheckoutlogo'] = tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL');

    $sts->template['breadcrumbs'] = $breadcrumb->trail(' &raquo; ');
	
    if (tep_session_is_registered('customer_id')) {
      $sts->template['myaccount'] = '<a href=' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_MY_ACCOUNT . '</a>';
      $sts->template['urlmyaccount'] = tep_href_link(FILENAME_ACCOUNT, '', 'SSL');
      $sts->template['logoff'] = '<a href=' . tep_href_link(FILENAME_LOGOFF, '', 'SSL')  . ' class="headerNavigation">' . HEADER_TITLE_LOGOFF . '</a>';
      $sts->template['urllogoff'] = tep_href_link(FILENAME_LOGOFF, '', 'SSL');
      $sts->template['myaccountlogoff'] = $sts->template['myaccount'] . " | " . $sts->template['logoff'];
// Next tags added in v4.3
			$sts->template['loginofflogo'] = '<a href=' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . ' class="headerNavigation">' . tep_image(STS_TEMPLATE_DIR.'images/'.$language . '/header_logoff.gif', HEADER_TITLE_LOGOFF) . '</a>';
    } else {
      $sts->template['myaccount'] = '<a href=' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_MY_ACCOUNT . '</a>';
      $sts->template['urlmyaccount'] = tep_href_link(FILENAME_ACCOUNT, '', 'SSL');
      $sts->template['logoff'] = '';
      $sts->template['urllogoff'] = '';
      $sts->template['myaccountlogoff'] = $sts->template['myaccount'];
// Next tags added in v4.3
			$sts->template['loginofflogo'] = '<a href=' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . ' class="headerNavigation">' . tep_image(STS_TEMPLATE_DIR.'images/'.$language . '/header_login.gif', HEADER_TITLE_LOGIN) . '</a>';
    }
// v4.5: use SSL if possible.
    $sts->template['cartcontents']    = '<a href=' . tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_CART_CONTENTS . '</a>';
    $sts->template['urlcartcontents'] = tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL');  // A real URL since v4.3, before was same as $cartcontents

    $sts->template['checkout'] = '<a href=' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_CHECKOUT . '</a>';
    $sts->template['urlcheckout'] = tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL');
    $sts->template['headertags']= "<title>" . TITLE ."</title>";
		
// Next tags added in v4.3 to display an image according to language and linking to the contact us page.
		$sts->template['contactlogo'] = '<a href=' . tep_href_link(FILENAME_CONTACT_US) . ' class="headerNavigation">' . tep_image(STS_TEMPLATE_DIR.'images/'.$language . '/header_contact_us.gif', BOX_INFORMATION_CONTACT) . '</a>';

// Tags generally displayed in the footer. =============================================
  // Get the number of requests
  require(DIR_WS_INCLUDES . 'counter.php');
  $sts->template['numrequests'] = $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted;
	
	$sts->template['footer_text']= FOOTER_TEXT_BODY;
	
// Get the banner if any
//  $sts->start_capture();
//  if ($banner = tep_banner_exists('dynamic', '468x50')) {
//    echo tep_display_banner('static', $banner);
//  }
//  $sts->stop_capture ('banner_only');
?>
