<?php
/*
  $Id: header.php,v 1.19 2002/04/13 16:11:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2)

  Please note: DO NOT DELETE this file if disabling the above contribution.
  Edits are listed by number. Locate and modify as needed to disable the contribution.
*/

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?>
<?php
 if(TINYMCE_DISPLAY == 'true') {
 include("./includes/javascript/tiny.inc");
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo tep_image(DIR_WS_IMAGES . 'EosC.gif', 'EosC', '235', '44'); ?></td>
    <td align="right" class="main">Your Current Version is <?php echo EOSC_VERSION; ?><br>The Most Current Version is <a href="http://www.e-osc.com" target="_blank"><img src="https://www.e-osc.com:4442/EosC_docs/newest.gif" border="0"></a></td>
  </tr>
  <tr class="headerBar">
<!-- BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 1 of 1 -->
<!-- reverse comments to below lines to disable this contribution -->
<!--    <td class="headerBarContent">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>'; ?></td> -->
    <td class="headerBarContent">&nbsp;&nbsp;
<?php
		if (tep_session_is_registered('login_id')) {
    echo '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT, '', 'SSL') . '" class="headerLink">' . HEADER_TITLE_ACCOUNT . '</a> | <a href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_LOGOFF . '</a>';
  } else {
    echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>';
  }
	?></td>
<!-- BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 1 of 1 -->
	<td class="headerBarContent" align="right"><?php echo '<a href="http://www.e-osc.com" target="_blank" class="headerLink">' . HEADER_TITLE_SUPPORT_SITE . '</a> &nbsp;|&nbsp; <a href="http://www.e-osc.com/EosC_docs/EosC_UserManual.pdf" target="_blank" class="headerLink">' . 'User Manual' . '</a> &nbsp;|&nbsp; <a href="' . tep_catalog_href_link() . '" class="headerLink">' . HEADER_TITLE_ONLINE_CATALOG . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_ADMINISTRATION . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>