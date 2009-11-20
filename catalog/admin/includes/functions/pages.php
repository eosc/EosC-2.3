<?php

  function tep_get_pages_name($page_id) {
    $page_query = tep_db_query("select pages_name from " . TABLE_PAGES . " where pages_id = '" . (int)$page_id . "'");
    $page = tep_db_fetch_array($page_query);

    return $page['pages_name'];
  }

  function tep_get_pages_title($page_id, $language_id = 0) {
    global $languages_id;

    if ($language_id == 0) $language_id = $languages_id;
    $page_query = tep_db_query("select pages_title from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
    $page = tep_db_fetch_array($page_query);

    return $page['pages_title'];
  }

  function tep_get_pages_body($page_id, $language_id = 0) {
    global $languages_id;

    if ($language_id == 0) $language_id = $languages_id;
    $page_query = tep_db_query("select pages_body from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
    $page = tep_db_fetch_array($page_query);

    return $page['pages_body'];
  }


?>
