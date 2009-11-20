<?php
  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch($action){
      case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if (isset($_GET['pID'])) {
            tep_db_query("update pages set pages_status='".$_GET['flag']."' where pages_id='".$_GET['pID']."'");
          }
          if (USE_CACHE == 'true') {
            tep_reset_cache_block('pages');
          }
        }

        tep_redirect(tep_href_link(FILENAME_PAGES, 'pID=' . $_GET['pID']));
        break;
      case 'insert_page':
      case 'update_page':
        if (isset($_POST['edit_x']) || isset($_POST['edit_y'])) {
          $action = 'new_page';
        } else {
          if (isset($_GET['pID'])) $pages_id = tep_db_prepare_input($_GET['pID']);

          $sql_data_array = array('pages_name' => tep_db_prepare_input($_POST['pages_name']),
                                  'pages_image' => tep_db_prepare_input($_POST['pages_image']),
                                  'pages_status' => tep_db_prepare_input($_POST['pages_status']),
                                  'sort_order' => tep_db_prepare_input($_POST['sort_order']));

          if ($action == 'insert_page') {
            tep_db_perform(TABLE_PAGES, $sql_data_array);
            $pages_id = tep_db_insert_id();
          }
          elseif ($action == 'update_page') {
            tep_db_perform(TABLE_PAGES, $sql_data_array, 'update', "pages_id = '" . (int)$pages_id . "'");
          }

          $languages = tep_get_languages();
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $pages_title_array = $_POST['pages_title'];
            $pages_body_array = $_POST['pages_body'];
            $language_id = $languages[$i]['id'];

            $sql_data_array = array('pages_title' => tep_db_prepare_input($pages_title_array[$language_id]),
                                    'pages_body' => tep_db_prepare_input($pages_body_array[$language_id]));

            if ($action == 'insert_page') {
              $insert_sql_data = array('pages_id' => $pages_id,
                                       'language_id' => $languages[$i]['id']);

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              tep_db_perform(TABLE_PAGES_DESCRIPTION, $sql_data_array);
            }
            elseif ($action == 'update_page') {
              tep_db_perform(TABLE_PAGES_DESCRIPTION, $sql_data_array, 'update', "pages_id = '" . (int)$pages_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
            }
          }

          if ($pages_image = new upload('pages_image', DIR_FS_CATALOG_IMAGES)) {
            tep_db_query("update " . TABLE_PAGES . " set pages_image = '" . tep_db_input($pages_image->filename) . "' where pages_id = '" . (int)$pages_id . "'");
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('pages');
          }

          tep_redirect(tep_href_link(FILENAME_PAGES, 'pID=' . $pages_id));
        }
        break;

      case 'delete_page_confirm':
        if (isset($_POST['pages_id'])) {
          $pages_id = tep_db_prepare_input($_POST['pages_id']);
          if($pages_id!=1 && $pages_id!=2){
            tep_db_query("delete from " . TABLE_PAGES . " where pages_id = '" . (int)$pages_id . "'");
            tep_db_query("delete from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$pages_id . "'");
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('pages');
        }

        tep_redirect(tep_href_link(FILENAME_PAGES));
        break;
      case 'new_page_preview':
        $pages_image = new upload('pages_image');
        $pages_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($pages_image->parse() && $pages_image->save()) {
          $pages_image_name = $pages_image->filename;
        } else {
          $pages_image_name = (isset($_POST['pages_previous_image']) ? $_POST['pages_previous_image'] : '');
        }
        break;

    }
  }

// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();" >
<div id="spiffycalendar" class="text"></div>
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
    <td width="100%" valign="top">

<?php
  if ($action == 'new_page') {
    $parameters = array('pages_name' => '',
                        'pages_title' => '',
                        'pages_body' => '',
                        'pages_id' => '',
                        'pages_image' => '',
                        'pages_status' => '',
                        'sort_order' => '');

    $pInfo = new objectInfo($parameters);

    if (isset($_GET['pID']) && empty($_POST)) {
      $page_query = tep_db_query("select pd.pages_title, pd.pages_body, p.pages_id, p.pages_name, p.pages_image, p.pages_status, p.sort_order from " . TABLE_PAGES . " p, " . TABLE_PAGES_DESCRIPTION . " pd where p.pages_id = '" . (int)$_GET['pID'] . "' and p.pages_id = pd.pages_id and pd.language_id = '" . (int)$languages_id . "'");
      $page = tep_db_fetch_array($page_query);
      $pInfo->objectInfo($page);
    } elseif (tep_not_null($_POST)) {
      $pInfo->objectInfo($_POST);
      $pages_name = $_POST['pages_name'];
      $pages_title = $_POST['pages_title'];
      $pages_body = $_POST['pages_body'];
    }

    $languages = tep_get_languages();

    if (!isset($pInfo->pages_status)) $pInfo->pages_status = '1';
    switch ($pInfo->pages_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
    }
?>
    <?php echo tep_draw_form('new_page', FILENAME_PAGES, 'action=new_page_preview' . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : ''), 'post', 'enctype="multipart/form-data"'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PAGE) ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_PAGES_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('pages_status', '1', $in_status) . '&nbsp;' . TEXT_PAGE_ACTIVE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PAGE_NOT_ACTIVE; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PAGES_NAME; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('pages_name', (isset($pages_name) ? $pages_name : tep_get_pages_name($pInfo->pages_id))); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PAGES_IMAGE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('pages_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $pInfo->pages_image . tep_draw_hidden_field('pages_previous_image', $pInfo->pages_image); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PAGES_TITLE; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('pages_title[' . $languages[$i]['id'] . ']', (isset($pages_title[$languages[$i]['id']]) ? $pages_title[$languages[$i]['id']] : tep_get_pages_title($pInfo->pages_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PAGES_BODY; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('pages_body[' . $languages[$i]['id'] . ']', 'soft', '60', '15', (isset($pages_body[$languages[$i]['id']]) ? $pages_body[$languages[$i]['id']] : tep_get_pages_body($pInfo->pages_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SORT_ORDER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('sort_order', $pInfo->sort_order); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PAGES, (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
    </table></form>
<?php
  } elseif ($action == 'new_page_preview') {
    if (tep_not_null($_POST)) {
      $pInfo = new objectInfo($_POST);
      $pages_name = $_POST['pages_name'];
      $pages_title = $_POST['pages_title'];
      $pages_body = $_POST['pages_body'];
    } else {
      $page_query = tep_db_query("select p.pages_id, p.pages_name, p.pages_status, p.pages_image, p.sort_order, pd.language_id, pd.pages_title, pd.pages_body from " . TABLE_PAGES . " p, " . TABLE_PAGES_DESCRIPTION . " pd where p.pages_id = pd.pages_id and p.pages_id = '" . (int)$_GET['pID'] . "'");
      $page = tep_db_fetch_array($page_query);

      $pInfo = new objectInfo($page);
      $pages_image_name = $pInfo->pages_image;
    }

    $form_action = (isset($_GET['pID'])) ? 'update_page' : 'insert_page';

    echo tep_draw_form($form_action, FILENAME_PAGES, 'action=' . $form_action . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : ''), 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
        $pInfo->pages_title = tep_get_pages_title($pInfo->pages_id, $languages[$i]['id']);
        $pInfo->pages_body = tep_get_pages_body($pInfo->pages_id, $languages[$i]['id']);
      } else {
        $pInfo->pages_title = tep_db_prepare_input($pages_title[$languages[$i]['id']]);
        $pInfo->pages_body = tep_db_prepare_input($pages_body[$languages[$i]['id']]);
      }
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $pInfo->pages_title; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $pages_image_name, $pInfo->pages_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . nl2br($pInfo->pages_body); ?></td>
      </tr>
<?php
    }
    if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
      if (isset($_GET['origin'])) {
        $pos_params = strpos($_GET['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($_GET['origin'], 0, $pos_params);
          $back_url_params = substr($_GET['origin'], $pos_params + 1);
        } else {
          $back_url = $_GET['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_PAGES;
        $back_url_params = 'pID=' . $pInfo->pages_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if (!is_array($_POST[$key])) {
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        echo tep_draw_hidden_field('pages_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($pages_title[$languages[$i]['id']])));
        echo tep_draw_hidden_field('pages_body[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($pages_body[$languages[$i]['id']])));
      }
      echo tep_draw_hidden_field('pages_image', stripslashes($pages_image_name));

// Causes problems with escape characters
//	    echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($_GET['pID'])) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PAGES, (isset($_GET['pID']) ? 'pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?>
        </td>
      </tr>
    </table></form>
<?php
    }
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAGE_NAME; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_PAGE_TITLE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PAGE_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $pages_count = 0;
    $pages_query = tep_db_query("select p.pages_id, p.pages_name, p.pages_image, p.pages_status, p.sort_order, pd.pages_title  from " . TABLE_PAGES . " p, " . TABLE_PAGES_DESCRIPTION . " pd where p.pages_id = pd.pages_id and pd.language_id = '" . (int)$languages_id . "' order by p.sort_order");
    while ($pages = tep_db_fetch_array($pages_query)) {
      $pages_count++;
      $rows++;

      if ( (!isset($_GET['pID']) || (isset($_GET['pID']) && ($_GET['pID'] == $pages['pages_id']))) && !isset($pInfo) && (substr($action, 0, 3) != 'new')) {
        $pInfo = new objectInfo($pages);
      }

      if (isset($pInfo) && is_object($pInfo) && ($pages['pages_id'] == $pInfo->pages_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PAGES, 'pID=' . $pages['pages_id'] . '&action=new_page_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PAGES, 'pID=' . $pages['pages_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_PAGES, 'pID=' . $pages['pages_id'] . '&action=new_page_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $pages['pages_name']; ?></td>
                <td class="dataTableContent"><?php echo $pages['pages_title']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($pages['pages_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PAGES, 'action=setflag&flag=0&pID=' . $pages['pages_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_PAGES, 'action=setflag&flag=1&pID=' . $pages['pages_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?>
                </td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($pages['pages_id'] == $pInfo->pages_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PAGES, 'pID=' . $pages['pages_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_PAGES . '&nbsp;' . $pages_count; ?></td>
                    <td align="right" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_PAGES, 'action=new_page') . '">' . tep_image_button('button_new_page.gif', IMAGE_NEW_PAGE) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'delete_page':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PAGE . '</b>');

        $contents = array('form' => tep_draw_form('pages', FILENAME_PAGES, 'action=delete_page_confirm') . tep_draw_hidden_field('pages_id', $pInfo->pages_id));
        $contents[] = array('text' => TEXT_DELETE_PAGE_INTRO);
        $contents[] = array('text' => '<br><b>' . $pInfo->pages_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_PAGES, 'pID=' . $pInfo->pages_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if (isset($pInfo) && is_object($pInfo)) { // page info box contents
          $heading[] = array('text' => '<b>' . tep_get_pages_title($pInfo->pages_id, $languages_id) . '</b>');

          $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PAGES, 'pID=' . $pInfo->pages_id . '&action=new_page') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_PAGES, 'pID=' . $pInfo->pages_id . '&action=delete_page') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
          $contents[] = array('text' => '<br>' . TEXT_PAGES_NAME . ' ' . $pInfo->pages_name);
          $contents[] = array('text' => '<br>' . tep_info_image($pInfo->pages_image, $pInfo->pages_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->pages_image);
          $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . ' ' . $pInfo->sort_order);
        }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
    </table>
<?php
  }
?>

    </td>
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