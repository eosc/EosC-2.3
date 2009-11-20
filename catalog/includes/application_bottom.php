<?php
/*
  $Id: application_bottom.php,v 1.14 2003/02/10 22:30:41 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// START STS 4.4
if ($sts->display_template_output) {
  $sts->stop_capture();
include DIR_WS_MODULES.'sts_inc/sts_display_output.php';
}
//END STS 4.4
// close session (store variables)
  tep_session_close();

// Ultimate SEO Profiling - ?profile=on to end of any url
 if ( $_REQUEST['profile'] == 'on' || $_SESSION['profile'] == 'on' ) {
  	$_SESSION['profile'] = isset($_REQUEST['profile']) ? $_REQUEST['profile'] : 'on';
	$seo_urls->profile();
  }
// End Ultimate SEO

  if (STORE_PAGE_PARSE_TIME == 'true') {
    $time_start = explode(' ', PAGE_PARSE_START_TIME);
    $time_end = explode(' ', microtime());
    $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
    error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);

    if (DISPLAY_PAGE_PARSE_TIME == 'true') {
      echo '<span class="smallText">Parse Time: ' . $parse_time . 's</span>';
    }
  }
// Added for Queries Debug Output 1.7
include(DIR_WS_INCLUDES . 'performance.php');

  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded == true) && ($ini_zlib_output_compression < 1) ) {
    if ( (PHP_VERSION < '4.0.4') && (PHP_VERSION >= '4') ) {
      tep_gzip_output(GZIP_LEVEL);
    }
  }
// Added For Queries Debug Output 1.7
function print_array ($array, $exit = false) {
    		print "<pre>";
    		print_r ($array);
		print "</pre>";
	  	if ($exit) exit();
	  }
?>
