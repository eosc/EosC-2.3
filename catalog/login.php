<?php
/*
  $Id: login.php,v 1.80 2003/06/05 23:28:24 hpdl Exp $
  adapted for Separate Price Per Customer v3.6 2005/02/22

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $email_address = tep_db_prepare_input($_POST['email_address']);
    $password = tep_db_prepare_input($_POST['password']);

// Check if email exists
// BOF Separate Pricing per Customer
/*    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'"); */
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_group_id, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
// EOF Separate Pricing Per Customer
    if (!tep_db_num_rows($check_customer_query)) {
      $error = true;
    } else {
      $check_customer = tep_db_fetch_array($check_customer_query);
// Check that password is good
      if (!tep_validate_password($password, $check_customer['customers_password'])) {
        $error = true;
      } else {
        if (SESSION_RECREATE == 'True') {
          tep_session_recreate();
        }
// BOF Separate Pricing Per Customer: choice for logging in under any customer_group_id
// note that tax rates depend on your registered address!
if ($_GET['skip'] != 'true' && $_POST['email_address'] == SPPC_TOGGLE_LOGIN_PASSWORD ) {
   $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
echo '<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">';
print ("\n<html ");
echo HTML_PARAMS; 
print (">\n<head>\n<title>Choose a Customer Group</title>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=");
echo CHARSET;
print ("\"\n<base href=\"");
echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG;
print ("\">\n<link rel=\"stylesheet\" type=\"text/css\" href=\"stylesheet.css\">\n");
echo '<body bgcolor="#ffffff" style="margin:0">';
print ("\n<table border=\"0\" width=\"100%\" height=\"100%\">\n<tr>\n<td style=\"vertical-align: middle\" align=\"middle\">\n");
echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process&skip=true', 'SSL'));
print ("\n<table border=\"0\" bgcolor=\"#f1f9fe\" cellspacing=\"10\" style=\"border: 1px solid #7b9ebd;\">\n<tr>\n<td class=\"main\">\n");
  $index = 0;
  while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
 $existing_customers_array[] = array("id" => $existing_customers['customers_group_id'], "text" => "&#160;".$existing_customers['customers_group_name']."&#160;");
    ++$index;
  }
print ("<h1>Choose a Customer Group</h1>\n</td>\n</tr>\n<tr>\n<td align=\"center\">\n");
echo tep_draw_pull_down_menu('new_customers_group_id', $existing_customers_array, $check_customer['customers_group_id']);
print ("\n<tr>\n<td class=\"main\">&#160;<br />\n&#160;");
print ("<input type=\"hidden\" name=\"email_address\" value=\"".$_POST['email_address']."\">");
print ("<input type=\"hidden\" name=\"password\" value=\"".$_POST['password']."\">\n</td>\n</tr>\n<tr>\n<td align=\"right\">\n");
echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE);
print ("</td>\n</tr>\n</table>\n</form>\n</td>\n</tr>\n</table>\n</body>\n</html>\n");
exit;
}
// EOF Separate Pricing Per Customer: choice for logging in under any customer_group_id
        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);

        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];
	// BOF Separate Pricing per Customer
	if ($_GET['skip'] == 'true' && $_POST['email_address'] == SPPC_TOGGLE_LOGIN_PASSWORD && isset($_POST['new_customers_group_id']))  {
	$sppc_customer_group_id = $_POST['new_customers_group_id'] ;
	$check_customer_group_tax = tep_db_query("select customers_group_show_tax, customers_group_tax_exempt from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" .(int)$_POST['new_customers_group_id'] . "'");
	} else {
	$sppc_customer_group_id = $check_customer['customers_group_id'];
	$check_customer_group_tax = tep_db_query("select customers_group_show_tax, customers_group_tax_exempt from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" .(int)$check_customer['customers_group_id'] . "'");
	}
	$customer_group_tax = tep_db_fetch_array($check_customer_group_tax);
	$sppc_customer_group_show_tax = (int)$customer_group_tax['customers_group_show_tax'];
	$sppc_customer_group_tax_exempt = (int)$customer_group_tax['customers_group_tax_exempt'];
	// EOF Separate Pricing per Customer
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');
	// BOF Separate Pricing per Customer
	tep_session_register('sppc_customer_group_id');
	tep_session_register('sppc_customer_group_show_tax');
	tep_session_register('sppc_customer_group_tax_exempt');
	// EOF Separate Pricing per Customer	
        tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");

// restore cart contents
        $cart->restore_contents();

        if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
          tep_redirect($origin_href);
        } else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT));
        }
      }
    }
  }

  if ($error == true) {
    $messageStack->add('login', TEXT_LOGIN_ERROR);
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function session_win() {
  window.open("<?php echo tep_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
<?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
<td class="pageHeading"><?php echo HEADING_TITLE; ?>

<?php
  if ($messageStack->size('login') > 0) {
?>
<?php echo $messageStack->output('login'); ?>

<?php
  }

  if ($cart->count_contents() > 0) {
?>
<?php
  }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2"><tr>
<td><b><?php echo HEADING_NEW_CUSTOMER; ?></td>
</tr><tr>
<td class="main" valign="top"><?php echo TEXT_NEW_CUSTOMER_INTRODUCTION; ?></td>
</tr><tr>
<td>
<table border="0" width="100%" cellspacing="0" cellpadding="2"><tr>
<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
</td>
<td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
</td></tr></table>
</td></tr></table>                                   

<table border="0" width="100%" cellspacing="0" cellpadding="2"><tr>
<td colspan="2"><b><?php echo HEADING_RETURNING_CUSTOMER; ?></b></td>
</tr><tr>
<td class="main" colspan="2"><?php echo TEXT_RETURNING_CUSTOMER; ?> <?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></td>
</tr><tr>
<td class="main" width="20%"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
<td class="main"><?php echo tep_draw_input_field('email_address'); ?></td>
</tr><tr>
<td class="main" width="20%"><b><?php echo ENTRY_PASSWORD; ?></b></td>
<td class="main"><?php echo tep_draw_password_field('password'); ?></td>
</tr><tr>
<td colspan="2">
<table border="0" width="100%" cellspacing="0" cellpadding="2"><tr>
<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
<td align="right"><?php echo tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN); ?>
</td></tr></table>
</td></tr></table>

</td></tr></table>
</form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
