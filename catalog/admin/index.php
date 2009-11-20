<?php
/*
  $Id: index.php,v 1.19 2003/06/27 09:38:31 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2)

  Please note: DO NOT DELETE this file if disabling the above contribution.
  Edits are listed by number. Locate and modify as needed to disable the contribution.
*/

  require('includes/application_top.php');

// BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 1 of 10
// uncomment below line to disable this contribution
//	$cat = array(array('title' => BOX_HEADING_CONFIGURATION,
// comment below lines to disable this contribution
  $cat = array(array('title' => BOX_HEADING_ADMINISTRATOR,
                     'access' => tep_admin_check_boxes('administrator.php'),
                     'image' => 'administrator.gif',
                     'href' => tep_href_link(tep_selected_file('administrator.php'), 'selected_box=administrator'),
                     'children' => array(array('title' => BOX_ADMINISTRATOR_MEMBER, 'link' => tep_href_link(FILENAME_ADMIN_MEMBERS, 'selected_box=administrator'),
                                               'access' => tep_admin_check_boxes(FILENAME_ADMIN_MEMBERS, 'sub_boxes')),
                                         array('title' => BOX_ADMINISTRATOR_BOXES, 'link' => tep_href_link(FILENAME_ADMIN_FILES, 'selected_box=administrator'),
                                               'access' => tep_admin_check_boxes(FILENAME_ADMIN_FILES, 'sub_boxes')))),
								array('title' => BOX_HEADING_MY_ACCOUNT,
                     'access' => 'true',
                     'image' => 'my_account.gif',
                     'href' => tep_href_link(FILENAME_ADMIN_ACCOUNT),
                     'children' => array(array('title' => HEADER_TITLE_ACCOUNT, 'link' => tep_href_link(FILENAME_ADMIN_ACCOUNT),
                                               'access' => 'true'),
                                         array('title' => HEADER_TITLE_LOGOFF, 'link' => tep_href_link(FILENAME_LOGOFF),
                                               'access' => 'true'))),
                array('title' => BOX_HEADING_CONFIGURATION,
                     'access' => tep_admin_check_boxes('configuration.php'),
// EOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 1 of 10
                     'image' => 'configuration.gif',
                     'href' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=1'),
                     'children' => array(array('title' => BOX_CONFIGURATION_MYSTORE, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=1')),
                                         array('title' => BOX_CONFIGURATION_LOGGING, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=10')),
                                         array('title' => BOX_CONFIGURATION_CACHE, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=11')))),
              	array('title' => BOX_HEADING_MODULES,
// BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 2 of 10
// comment out below line to disable this contribution
                     'access' => tep_admin_check_boxes('modules.php'),
// EOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 2 of 10
                     'image' => 'modules.gif',
                     'href' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=payment'),
                     'children' => array(array('title' => BOX_MODULES_PAYMENT, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=payment')),
                                         array('title' => BOX_MODULES_SHIPPING, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=shipping')))),
                array('title' => BOX_HEADING_CATALOG,
// BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 3 of 10
// comment out below line to disable this contribution
                     'access' => tep_admin_check_boxes('catalog.php'),
// EOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 3 of 10
                     'image' => 'catalog.gif',
                     'href' => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'),
                     'children' => array(array('title' => CATALOG_CONTENTS, 'link' => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_MANUFACTURERS, 'link' => tep_href_link(FILENAME_MANUFACTURERS, 'selected_box=catalog')))),
               	array('title' => BOX_HEADING_LOCATION_AND_TAXES,
// BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 4 of 10
// comment out below line to disable this contribution
                     'access' => tep_admin_check_boxes('taxes.php'),
// EOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 4 of 10
                     'image' => 'location.gif',
                     'href' => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes'),
                     'children' => array(array('title' => BOX_TAXES_COUNTRIES, 'link' => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes')),
                                         array('title' => BOX_TAXES_GEO_ZONES, 'link' => tep_href_link(FILENAME_GEO_ZONES, 'selected_box=taxes')))),
               	array('title' => BOX_HEADING_CUSTOMERS,
// BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 5 of 10
// comment out below line to disable this contribution
                     'access' => tep_admin_check_boxes('customers.php'),
// EOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 5 of 10
                     'image' => 'customers.gif',
                     'href' => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers'),
                     'children' => array(array('title' => BOX_CUSTOMERS_CUSTOMERS, 'link' => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers')),
                                         array('title' => BOX_CUSTOMERS_ORDERS, 'link' => tep_href_link(FILENAME_ORDERS, 'selected_box=customers')))),
               	array('title' => BOX_HEADING_LOCALIZATION,
// BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 6 of 10
// comment out below line to disable this contribution
                     'access' => tep_admin_check_boxes('localization.php'),
// EOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 6 of 10
                     'image' => 'localization.gif',
                     'href' => tep_href_link(FILENAME_CURRENCIES, 'selected_box=localization'),
                     'children' => array(array('title' => BOX_LOCALIZATION_CURRENCIES, 'link' => tep_href_link(FILENAME_CURRENCIES, 'selected_box=localization')),
                                         array('title' => BOX_LOCALIZATION_LANGUAGES, 'link' => tep_href_link(FILENAME_LANGUAGES, 'selected_box=localization')))),
               	array('title' => BOX_HEADING_REPORTS,
// BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 7 of 10
// comment out below line to disable this contribution
                     'access' => tep_admin_check_boxes('reports.php'),
// EOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 7 of 10
                     'image' => 'reports.gif',
                     'href' => tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, 'selected_box=reports'),
                     'children' => array(array('title' => REPORTS_PRODUCTS, 'link' => tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, 'selected_box=reports')),
                                         array('title' => REPORTS_ORDERS, 'link' => tep_href_link(FILENAME_STATS_CUSTOMERS, 'selected_box=reports')))),
// BOF edit pages 
               array('title' => BOX_HEADING_PAGES,
                     'image' => 'pages.gif',
                     'href' => tep_href_link(FILENAME_PAGES, 'selected_box=pages'),
                     'children' => array(array('title' => PAGES_LIST_PAGES, 'link' => tep_href_link(FILENAME_PAGES, 'selected_box=pages')),
                                         array('title' => PAGES_ADD_PAGE, 'link' => tep_href_link(FILENAME_PAGES.'', 'selected_box=pages&action=new_page')))),

               array('title' => BOX_HEADING_NEWSLETTER,
                     'image' => 'newsletters.gif',
                     'href' => tep_href_link(FILENAME_NEWSLETTERS, 'selected_box=newsletter'),
                     'children' => array(array('title' => BOX_NEWSLETTER_SUBSCRIBERS_VIEW, 'link' => tep_href_link(FILENAME_NEWSLETTERS_SUBSCRIBERS_VIEW, '')))),
                                         											 

               	array('title' => BOX_HEADING_TOOLS,
// BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 8 of 10
// comment out below line to disable this contribution
                     'access' => tep_admin_check_boxes('tools.php'),
// EOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 8 of 10
                     'image' => 'tools.gif',
                     'href' => tep_href_link(FILENAME_BACKUP, 'selected_box=tools'),
                     'children' => array(array('title' => TOOLS_BACKUP, 'link' => tep_href_link(FILENAME_BACKUP, 'selected_box=tools')),
                                         array('title' => TOOLS_BANNERS, 'link' => tep_href_link(FILENAME_BANNER_MANAGER, 'selected_box=tools')),
                                         array('title' => TOOLS_FILES, 'link' => tep_href_link(FILENAME_FILE_MANAGER, 'selected_box=tools')))));

  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<!-- BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 9 of 10 -->
<link rel="stylesheet" type="text/css" href="style.css">
<!-- EOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 9 of 10 -->
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<table border="0" width="600" height="100%" cellspacing="0" cellpadding="0" align="center" valign="middle">
  <tr>
    <td><table border="0" width="600" height="440" cellspacing="0" cellpadding="1" align="center" valign="middle">
      <tr bgcolor="#000000">
        <td><table border="0" width="600" height="440" cellspacing="0" cellpadding="0">
          <tr bgcolor="#ffffff" height="50">
            <td height="50"><?php echo tep_image(DIR_WS_IMAGES . 'EosC.gif', 'osCommerce', '235', '44'); ?></td>
            <td align="right" class="text" nowrap><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . HEADER_TITLE_ADMINISTRATION . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="' . tep_catalog_href_link() . '">' . HEADER_TITLE_ONLINE_CATALOG . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.e-osc.com" target="_blank">' . HEADER_TITLE_SUPPORT_SITE . '</a>'; ?>&nbsp;&nbsp;</td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td colspan="2"><table border="0" width="460" height="390" cellspacing="0" cellpadding="2">
              <tr valign="top">
                <td width="140" valign="top"><table border="0" width="140" height="390" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top"><br>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => 'osCommerce');

  $contents[] = array('params' => 'class="infoBoxIndex"',
                      'text'  => '<a href="http://www.e-osc.com" target="_blank">' . BOX_ENTRY_SUPPORT_SITE . '</a><br>' .
                                 '<a href="http://www.e-osc.com/component/option,com_smf/Itemid,26/" target="_blank">' . BOX_ENTRY_SUPPORT_FORUMS . '</a><br>' .
                                 '<a href="http://www.e-osc.com/component/option,com_smf/Itemid,26/board,3.0" target="_blank">' . BOX_ENTRY_BUG_REPORTS . '</a><br>' .
                                 '<a href="http://www.e-osc.com/EosC_docs/EosC_UserManual.pdf" target="_blank">User Manual</a><br>' .
                                 '<a href="http://www.e-osc.com/content/category/3/7/25/" target="_blank">' . BOX_ENTRY_FAQ . '</a>');

  $box = new box;
  echo $box->menuBox($heading, $contents);

  echo '<br>';

  $orders_contents = '';
  $orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'");
    $orders_pending = tep_db_fetch_array($orders_pending_query);
// BOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 10 of 10
// reverse comments to below lines to disable this contribution
//    $orders_contents .= '<a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $orders_pending['count'] . '<br>';
    if (tep_admin_check_boxes(FILENAME_ORDERS, 'sub_boxes') == true) {
      $orders_contents .= '<a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $orders_pending['count'] . '<br>';
    } else {
      $orders_contents .= '' . $orders_status['orders_status_name'] . ': ' . $orders_pending['count'] . '<br>';
    }
// EOE Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2) 10 of 10
  }
  $orders_contents = substr($orders_contents, 0, -4);

  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_TITLE_ORDERS);

  $contents[] = array('params' => 'class="infoBoxIndex"',
                      'text'  => $orders_contents);

  $box = new box;
  echo $box->menuBox($heading, $contents);

  echo '<br>';

  $customers_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS);
  $customers = tep_db_fetch_array($customers_query);
  $products_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS . " where products_status = '1'");
  $products = tep_db_fetch_array($products_query);
  $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS);
  $reviews = tep_db_fetch_array($reviews_query);

  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_TITLE_STATISTICS);

  $contents[] = array('params' => 'class="infoBoxIndex"',
                      'text'  => BOX_ENTRY_CUSTOMERS . ' ' . $customers['count'] . '<br>' .
                                 BOX_ENTRY_PRODUCTS . ' ' . $products['count'] . '<br>' .
                                 BOX_ENTRY_REVIEWS . ' ' . $reviews['count']);

  $box = new box;
  echo $box->menuBox($heading, $contents);

  echo '<br>';

  $contents = array();

  if (getenv('HTTPS') == 'on') {
    $size = ((getenv('SSL_CIPHER_ALGKEYSIZE')) ? getenv('SSL_CIPHER_ALGKEYSIZE') . '-bit' : '<i>' . BOX_CONNECTION_UNKNOWN . '</i>');
    $contents[] = array('params' => 'class="infoBoxIndex"',
                        'text' => tep_image(DIR_WS_ICONS . 'locked.gif', ICON_LOCKED, '', '', 'align="right"') . sprintf(BOX_CONNECTION_PROTECTED, $size));
  } else {
    $contents[] = array('params' => 'class="infoBoxIndex"',
                        'text' => tep_image(DIR_WS_ICONS . 'unlocked.gif', ICON_UNLOCKED, '', '', 'align="right"') . BOX_CONNECTION_UNPROTECTED);
  }

  $box = new box;
  echo $box->tableBlock($contents);
?>
                    </td>
                  </tr>
                </table></td>
                <td width="460"><table border="0" width="460" height="390" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr><?php echo tep_draw_form('languages', 'index.php', '', 'get'); ?>
                        <td class="heading"><?php echo HEADING_TITLE; ?></td>
                        <td align="right"><?php echo tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"'); ?></td>
                      </form></tr>
                    </table></td>
                  </tr>
<?php
  $col = 2;
  $counter = 0;
  for ($i = 0, $n = sizeof($cat); $i < $n; $i++) {
    $counter++;
    if ($counter < $col) {
      echo '                  <tr>' . "\n";
    }

    echo '                    <td><table border="0" cellspacing="0" cellpadding="2">' . "\n" .
         '                      <tr>' . "\n" .
         '                        <td><a href="' . $cat[$i]['href'] . '">' . tep_image(DIR_WS_IMAGES . 'categories/' . $cat[$i]['image'], $cat[$i]['title'], '32', '32') . '</a></td>' . "\n" .
         '                        <td><table border="0" cellspacing="0" cellpadding="2">' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="main"><a href="' . $cat[$i]['href'] . '" class="indexmain">' . $cat[$i]['title'] . '</a></td>' . "\n" .
         '                          </tr>' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="sub">';

    $children = '';
    for ($j = 0, $k = sizeof($cat[$i]['children']); $j < $k; $j++) {
      $children .= '<a href="' . $cat[$i]['children'][$j]['link'] . '" class="sub">' . $cat[$i]['children'][$j]['title'] . '</a>, ';
    }
    echo substr($children, 0, -2);

    echo '</td> ' . "\n" .
         '                          </tr>' . "\n" .
         '                        </table></td>' . "\n" .
         '                      </tr>' . "\n" .
         '                    </table></td>' . "\n";

    if ($counter >= $col) {
      echo '                  </tr>' . "\n";
      $counter = 0;
    }
  }
?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php require(DIR_WS_INCLUDES . 'footer.php'); ?></td>
      </tr>
    </table></td>
  </tr>
</table>

</body>
</html>