You should add this code if you are using enabled-disabled categories :
//Cache
			$cache_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$_GET['cID'] . "'");
            while ($cache = tep_db_fetch_array($cache_query)) {
              $cachedir = DIR_FS_CACHE_XSELL . $cache['products_id'];
              if(is_dir($cachedir)) { rdel($cachedir); }
              }
			//Fin Cache

Like this :

      case 'setflag_cat':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if (isset($_GET['cID'])) {
            tep_set_categories_status($_GET['cID'], $_GET['flag']);
            //Cache
			$cache_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$_GET['cID'] . "'");
            while ($cache = tep_db_fetch_array($cache_query)) {
              $cachedir = DIR_FS_CACHE_XSELL . $cache['products_id'];
              if(is_dir($cachedir)) { rdel($cachedir); }
              }
			//Fin Cache
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&cID=' . $_GET['cID']));
        break;
