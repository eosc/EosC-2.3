<!-- pages //-->
          <tr>
            <td>
<?php
  include_once('includes/application_top.php');

  $page_query = tep_db_query("select pd.pages_title, pd.pages_body, p.pages_id, p.pages_name, p.pages_image, p.pages_status, p.sort_order from " . TABLE_PAGES . " p, " . TABLE_PAGES_DESCRIPTION . " pd where p.pages_id = pd.pages_id and p.pages_status = '1' and pd.language_id = '" . (int)$languages_id . "'");

  $page_menu_text = '';
  while($page = tep_db_fetch_array($page_query)){
    if($page["pages_id"]!=1 && $page["pages_id"]!=2 && $page["pages_id"]!=8 && $page["pages_id"]!=9)
      $page_menu_text .= '<a href="' . tep_href_link(FILENAME_PAGES, 'page='.$page["pages_name"]) . '">' . $page["pages_title"] . '</a><br>';
    if (($page["pages_id"] == '2' && $page['pages_status'] == '1')) 
      $page_menu_text .= '<a href="' . tep_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '</a><br>';
    if (($page["pages_id"] == '8' && $page['pages_status'] == '1')) 
      $page_menu_text .= '<a href="' . tep_href_link(FILENAME_GV_FAQ) . '">' . BOX_INFORMATION_GV . '</a><br>';
    if (($page["pages_id"] == '9' && $page['pages_status'] == '1')) 
      $page_menu_text .= '<a href="' . tep_href_link(FILENAME_SITEMAP) . '">' . BOX_INFORMATION_SITEMAP . '</a><br>';
  }

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text'  => BOX_HEADING_PAGES
                              );
  new infoBoxHeading($info_box_contents, false, false);
//  new infoBoxHeadingCurved($info_box_contents, false, false);

  $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => $page_menu_text,
                                );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- pages_eof //-->