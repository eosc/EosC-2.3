<?php
/*
  $Id: headertags.php,v 1.0 2005/11/03 23:55:58 rigadin Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License

STS PLUS v4 include module by Rigadin (rigadin@osc-help.net) for Header Tags Controller (contribution 207)
*/

  $sts->start_capture();
  if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
    require(DIR_WS_FUNCTIONS . 'header_tags.php');
    require(DIR_WS_INCLUDES . 'header_tags.php');
  } 
  $sts->stop_capture('headertags');

?>
