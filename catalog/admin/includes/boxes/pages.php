<!-- pages //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_PAGES,
                     'link'  => tep_href_link(FILENAME_PAGES, 'selected_box=pages'));

  if ($selected_box == 'pages') {
    $contents[] = array('text'  => '<a href="' . tep_href_link("pages.php") . '" class="menuBoxContentLink">' . PAGES_LIST_PAGES . '</a><br>' .
                                   '<a href="' . tep_href_link("pages.php?action=new_page") . '" class="menuBoxContentLink">' . PAGES_ADD_PAGE . '</a><br>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- pages_eof //-->