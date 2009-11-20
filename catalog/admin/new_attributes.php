<?
   
/*
  $Id: new_attributes.php 
  
   New Attribute Manager v4b, Author: Mike G.
  
  Updates for New Attribute Manager v.5.0 and multilanguage support by: Kiril Nedelchev - kikoleppard
  kikoleppard@hotmail.bg
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  
  $adminImages = "includes/languages/english/images/buttons/";
  $backLink = "<a href=\"javascript:history.back()\">";

  require('new_attributes_config.php');
  require('includes/application_top.php');
  
 
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEW_ATTRIBUTE_MANAGER);

// Evolve Edit for Register Globals Off
 link_post_variable('current_product_id');
 link_post_variable('x');
 link_post_variable('y');
 link_post_variable('action');
 link_post_variable('optionValues');
// link_post_variable('value_sort');
 
// End Evolve Edit for Register Globals Off

  if ( $cPathID && $action == "change" )
  {
        require('new_attributes_change.php');

        tep_redirect( './' . FILENAME_CATEGORIES . '?cPath=' . $cPathID . '&pID=' . $current_product_id );

  }
  
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
     <table border="0" width="100%" cellspacing="2" cellpadding="2">
     <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
     <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>

<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
    
<?
function findTitle( $current_product_id, $languageFilter )
{
  $query = "SELECT * FROM products_description where language_id = '$languageFilter' AND products_id = '$current_product_id'";

  $result = mysql_query($query) or die(mysql_error());

  $matches = mysql_num_rows($result);

  if ($matches) {

  while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                                          	
        $productName = $line['products_name'];
        
  }
  
  return $productName;
  
  } else { return HEADING_ERROR; }
  
}

function attribRedirect( $cPath )
{

 return '<SCRIPT LANGUAGE="JavaScript"> window.location="./configure.php?cPath=' . $cPath . '"; </script>';
 
}

switch( $action )
{
  case 'select':
  $pageTitle = HEADING_TITLE_VAL_PRODUCT . findTitle( $current_product_id, $languageFilter );
  require('new_attributes_include.php');
  break;
  
  case 'change':
  $pageTitle = HEADING_UPDATE;
  require('new_attributes_change.php');
  require('new_attributes_select.php');
  break;

  default:
  $pageTitle = HEADING_TITLE_VAL;
  require('new_attributes_select.php');
  break;
  
}

?>

    </table></TD>
    </TR>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
