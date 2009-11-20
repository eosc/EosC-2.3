<?php
/*
  Copyright (c) 2000,2001 The Exchange Project
  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSLETTERS);
  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_NEWSLETTERS, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="JavaScript1.2" type="text/javascript">
        function verify(form)
        {
           var passed = false;
        var blnRetval, intAtSign, intDot, intComma, intSpace, intLastDot, intDomain, intStrLen;
        if (form.Email){
                       intAtSign=form.Email.value.indexOf("@");
                        intDot=form.Email.value.indexOf(".",intAtSign);
                        intComma=form.Email.value.indexOf(",");
                        intSpace=form.Email.value.indexOf(" ");
                        intLastDot=form.Email.value.lastIndexOf(".");
                        intDomain=intDot-intAtSign;
                        intStrLen=form.Email.value.length;
                // *** CHECK FOR BLANK EMAIL VALUE
                   if (form.Email.value == "" )
                   {
                alert("You have not entered an email address.");
                form.Email.focus();
                passed = false;
                }
                // **** CHECK FOR THE  @ SIGN?
                else if (intAtSign == -1)
                {

                alert("Your email address is missing the \"@\".");
                        form.Email.focus();
                passed = false;

                }
                // **** Check for commas ****

                else if (intComma != -1)
                {
                alert("Email address cannot contain a comma.");
                form.Email.focus();
                passed = false;
                }

                // **** Check for a space ****

                else if (intSpace != -1)
                {
                alert("Email address cannot contain spaces.");
                form.Email.focus();
                passed = false;
                }

                // **** Check for char between the @ and dot, chars between dots, and at least 1 char after the last dot ****

                else if ((intDot <= 2) || (intDomain <= 1)  || (intStrLen-(intLastDot+1) < 2))
                {
                alert("Please enter a valid Email address.\n" + form.Email.value + " is invalid.");
                form.Email.focus();
                passed = false;
                }
                else {
                        passed = true;
                }
        }
        else    {
                passed = true;
        }
        return passed;
  }
        //-->
</script>
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
    <td width="100%" valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"><?php // echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table>
				</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td align=center>
																 <P CLASS="main"><? echo TEXT_ORIGIN_EXPLAIN_TOP; ?></P>
				<br>




                               <form NAME="newsletter" ACTION="<? echo tep_href_link(FILENAME_NEWSLETTERS_SUBSCRIBE, '', 'NONSSL'); ?>" METHOD="post" onSubmit="return verify(this);">
                               <input type="hidden" name="submitted" value="true">
                               <table cellspacing=2 cellpadding=2 border=0 width="75%" class="topBarTitle">
				 <tr><td><P CLASS="main"><? echo TEXT_EMAIL; ?>&nbsp;&nbsp;&nbsp;</P></td>
				 <td>&nbsp;</td><td><input type="text" name="Email" value="" size="25" maxlength="50"></td>
				 </tr>
				 <tr><td><P CLASS="main"><? echo TEXT_EMAIL_FORMAT; ?>&nbsp;&nbsp;&nbsp;</P></td>
				 <td>&nbsp;</td><td><P CLASS="main">
				 <input type="radio" name="email_type" value="HTML"><? echo TEXT_EMAIL_HTML; ?></input> - <input type="radio" name="email_type" value="TEXT" checked><? echo TEXT_EMAIL_TXT; ?></input></P></td>
				 </tr>


                               <tr><td><P CLASS="main"><? echo TEXT_GENDER; ?>&nbsp;&nbsp;&nbsp;</P></td>
				 <td>&nbsp;</td><td><P CLASS="main">
				 <input type="radio" name="gender" value="m"  checked><? echo TEXT_GENDER_MR; ?></input> - <input type="radio" name="gender" value="f"><? echo TEXT_GENDER_MRS; ?></input></P></td>
				 </tr>

				 <tr><td><P CLASS="main"><? echo TEXT_FIRST_NAME; ?>&nbsp;&nbsp;&nbsp;</P></td>
				 <td>&nbsp;</td><td><input type="text" name="firstname" value="" size="25" maxlength="50"></td>
				 </tr>
				 <tr><td><P CLASS="main"><? echo TEXT_LAST_NAME; ?>&nbsp;&nbsp;&nbsp;</P></td>
				 <td>&nbsp;</td><td><input type="text" name="lastname" value="" size="25" maxlength="50"></td>
				 </tr>
				  <tr><td colspan=3><P CLASS="main"><? echo TEXT_ZIP_INFO; ?>&nbsp;</P></td>
				</tr>
				 <tr><td><P CLASS="main"><? echo TEXT_ZIP_CODE; ?>&nbsp;&nbsp;&nbsp;</P></td>
				 <td>&nbsp;</td><td><input type="text" name="zip" value="" size="6" maxlength="6"></td>
									 </tr>
					<tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td>&nbsp;</td><td class="main"><?php  echo tep_get_country_list('country',  $subscribers_country_id ) . '&nbsp;' ;?></td>
          </tr>
									 <tr>
	 <td colspan=3 valign="center" align="right"><br><input type="image" border="0" src="includes/languages/english/images/buttons/button_confirm.gif" name="submit" align="ABSCENTER"></td>
	 </tr>
	 </table>
	 <BR>
            <P CLASS="smallText"><? echo TEXT_ORIGIN_EXPLAIN_BOTTOM; ?></P>
            <br>
		</td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
    </table></td>
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