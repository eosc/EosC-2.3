<?php
/*
  $Id: latest_news.php,v 1.1.1.1 2002/11/11 06:15:14 will Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 Will Mays

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'setflag': //set the status of a news item.
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if ($_GET['latest_news_id']) {
            tep_db_query("update " . TABLE_SUBSCRIBERS_DEFAULT . " set status = '" . $_GET['flag'] . "' where news_id = '" . $_GET['latest_news_id'] . "'");
          }
        }

        tep_redirect(tep_href_link(FILENAME_NEWSLETTERS_EXTRA_DEFAULT));
        break;

      case 'delete_latest_news_confirm': //user has confirmed deletion of news article.
        if ($_POST['latest_news_id']) {
          $latest_news_id = tep_db_prepare_input($_POST['latest_news_id']);
          tep_db_query("delete from " . TABLE_SUBSCRIBERS_DEFAULT . " where news_id = '" . tep_db_input($latest_news_id) . "'");
        }

        tep_redirect(tep_href_link(FILENAME_NEWSLETTERS_EXTRA_DEFAULT));
        break;

      case 'insert_latest_news': //insert a new news article.
        if ($_POST['module_subscribers']) {
          $sql_data_array = array('module_subscribers'   => tep_db_prepare_input($_POST['module_subscribers']),
                                  'header'    => tep_db_prepare_input($_POST['header']),
                                  'unsubscribea'    => tep_db_prepare_input($_POST['unsubscribea']),
                                  'unsubscribeb'    => tep_db_prepare_input($_POST['unsubscribeb']),																																		
                                  'date_added' => 'now()', //uses the inbuilt mysql function 'now'
                                  'status'     => '1' );

          tep_db_perform(TABLE_SUBSCRIBERS_DEFAULT, $sql_data_array);
          $news_id = tep_db_insert_id(); //not actually used ATM -- just there in case
        }

        tep_redirect(tep_href_link(FILENAME_NEWSLETTERS_EXTRA_DEFAULT));
        break;

      case 'update_latest_news': //user wants to modify a news article.
        if($_GET['latest_news_id']) {
          $sql_data_array = array('unsubscribea' => tep_db_prepare_input($_POST['unsubscribea']),
                                  'unsubscribeb' => tep_db_prepare_input($_POST['unsubscribeb']),																																		
                                  'header'  => tep_db_prepare_input($_POST['header']));
                                  
          tep_db_perform(TABLE_SUBSCRIBERS_DEFAULT, $sql_data_array, 'update', "news_id = '" . tep_db_prepare_input($_GET['latest_news_id']) . "'");
        }
        
        tep_redirect(tep_href_link(FILENAME_NEWSLETTERS_EXTRA_DEFAULT));
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" footer="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!-- Contribution WYSIWYG HTML v1.7 //-->
<script language="Javascript1.2"><!-- // load htmlarea
// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Newsletter <head>
      _editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files
        var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
         if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
          if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
           if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
       <?php if (HTML_AREA_WYSIWYG_BASIC_NEWSLETTER == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php } else{ ?> if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php }?>
// --></script>
<!-- Fin contribution WYSIWYG HTML v1.7 //-->
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
<?php
  if ($_GET['action'] == 'new_latest_news') { //insert or edit a news item
    if ( isset($_GET['latest_news_id']) ) { //editing exsiting news item

      $latest_news_query = tep_db_query("select news_id, module_subscribers, header, unsubscribea, unsubscribeb from " . TABLE_SUBSCRIBERS_DEFAULT . " where news_id = '" . $_GET['latest_news_id'] . "'");
      $latest_news = tep_db_fetch_array($latest_news_query);
    } else { //adding new news item
      $latest_news = array();
    }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TEXT_NEW_NEWSLETTER_INFO; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('new_latest_news', FILENAME_NEWSLETTERS_EXTRA_DEFAULT, isset($_GET['latest_news_id']) ? 'latest_news_id=' . $_GET['latest_news_id'] . '&action=update_latest_news' : 'action=insert_latest_news', 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
				
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_MODULE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $latest_news['module_subscribers']; ?></td>
          </tr>
          <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_HEADER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('header', 'soft', '70', '10', stripslashes($latest_news['header'])); ?></td>
            <!-- Contribution WYSIWYG HTML v1.7 //-->
            <?php if (HTML_AREA_WYSIWYG_DISABLE_NEWSLETTER == 'Enable') { ?>
            <script language="JavaScript1.2" defer>
            // MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Newsletter <body>
              var config = new Object();  // create new config object
              config.width = "<?php echo NEWSLETTER_EMAIL_WYSIWYG_WIDTH; ?>px";
              config.height = "<?php echo NEWSLETTER_EMAIL_WYSIWYG_HEIGHT; ?>px";
              config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
              config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
              editor_generate('header',config);
            <?php }
           // MaxiDVD Added HTML is ON when WYSIWYG BOX Enabled, HTML is OFF when WYSIWYG Disabled
            ?>
            </script>
           <!-- Fin contribution WYSIWYG HTML v1.7 //-->
          </tr>
          <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>
          <tr>
            <td class="main" align="center" colspan="2"><?php echo TEXT_NEWSLETTER_UNSUBSCRIBEA_INFO; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_UNSUBSCRIBEA; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('unsubscribea', 'soft', '70', '10', stripslashes($latest_news['unsubscribea'])); ?></td>
          </tr>
          <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>					
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_UNSUBSCRIBEB; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('unsubscribeb', 'soft', '70', '10', stripslashes($latest_news['unsubscribeb'])); ?></td>
            <!-- Contribution WYSIWYG HTML v1.7 //-->
            <?php if (HTML_AREA_WYSIWYG_DISABLE_NEWSLETTER == 'Enable') { ?>
            <script language="JavaScript1.2" defer>
            // MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Newsletter <body>
              var config = new Object();  // create new config object
              config.width = "<?php echo NEWSLETTER_EMAIL_WYSIWYG_WIDTH; ?>px";
              config.height = "<?php echo NEWSLETTER_EMAIL_WYSIWYG_HEIGHT; ?>px";
              config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
              config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
              editor_generate('unsubscribea',config);
              editor_generate('unsubscribeb',config);
            <?php }
            // MaxiDVD Added HTML is ON when WYSIWYG BOX Enabled, HTML is OFF when WYSIWYG Disabled
            ?>
            </script>
            <!-- Fin contribution WYSIWYG HTML v1.7 //-->
          </tr>										
          <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>								
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right">
          <?php
            isset($_GET['latest_news_id']) ? $cancel_button = '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_NEWSLETTERS_EXTRA_DEFAULT, 'latest_news_id=' . $_GET['latest_news_id']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' : $cancel_button = '';
            echo tep_image_submit('button_confirm.gif', IMAGE_CONFIRM) . $cancel_button;
          ?>
        </td>
      </form></tr>
<?php

  } else {
?>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_INFOS_TITLE; ?></td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            </tr>
          </table>
        </td>
      </tr>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NEWSLETTER_HEADER; ?></td>
		<td></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_LATEST_NEWS_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $rows = 0;

    $latest_news_count = 0;
    $latest_news_query = tep_db_query('select p.news_id, p.module_subscribers, p.header, p.status from ' . TABLE_SUBSCRIBERS_DEFAULT . ' p order by p.date_added desc');
    
    while ($latest_news = tep_db_fetch_array($latest_news_query)) {
      $latest_news_count++;
      $rows++;
      
      if ( ((!$_GET['latest_news_id']) || (@$_GET['latest_news_id'] == $latest_news['news_id'])) && (!$selected_item) && (substr($_GET['action'], 0, 4) != 'new_') ) {
        $selected_item = $latest_news;
      }
      if ( (is_array($selected_item)) && ($latest_news['news_id'] == $selected_item['news_id']) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_NEWSLETTERS_EXTRA_DEFAULT, 'latest_news_id=' . $latest_news['news_id']) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_NEWSLETTERS_EXTRA_DEFAULT, 'latest_news_id=' . $latest_news['news_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '&nbsp;' . $latest_news['module_subscribers']; ?></td>
                <td colspan="2" class="dataTableContent" align="right"><?php if ($latest_news['news_id'] == $_GET['latest_news_id']) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_NEWSLETTERS_EXTRA_DEFAULT, 'latest_news_id=' . $latest_news['news_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

?>
            </table></td>
<?php
    $heading = array();
    $headers = array();
    switch ($_GET['action']) {
      case 'delete_latest_news': //generate box for confirming a news article deletion
        $heading[] = array('text'   => '<b>' . TEXT_INFO_HEADING_DELETE_ITEM . '</b>');
        
        $headers = array('form'    => tep_draw_form('news', FILENAME_NEWSLETTERS_EXTRA_DEFAULT, 'action=delete_latest_news_confirm') . tep_draw_hidden_field('latest_news_id', $_GET['latest_news_id']));
        $headers[] = array('text'  => TEXT_DELETE_ITEM_INTRO);
        $headers[] = array('text'  => '<br><b>' . $selected_item['module_subscribers'] . '</b>');
        
        $headers[] = array('align' => 'center',
                            'text'  => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_NEWSLETTERS_EXTRA_DEFAULT, 'latest_news_id=' . $selected_item['news_id']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      default:
        if ($rows > 0) {
          if (is_array($selected_item)) { //an item is selected, so make the side box
            $heading[] = array('text' => '<b>' . $selected_item['module_subscribers'] . '</b>');

            $headers[] = array('align' => 'center', 
                                'text' => '<a href="' . tep_href_link(FILENAME_NEWSLETTERS_EXTRA_DEFAULT, 'latest_news_id=' . $selected_item['news_id'] . '&action=new_latest_news') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
            $headers[] = array('text' => '<br>' . $selected_item['header']);
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $headers[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));
        }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($headers)) ) {
      echo '            <td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $headers);

      echo '            </td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
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
