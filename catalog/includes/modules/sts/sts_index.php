<?php
/*
$Id: sts_index.php,v 2.0.0 2007/01/17 09:36:00 rigadin $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2006 osCommerce

Released under the GNU General Public License
* 
STS v4 module for index.php by Rigadin (rigadin@osc-help.net)
*/

class sts_index {

  var $template_file;
  
  function sts_index (){
    $this->code = 'sts_index';
    $this->title = MODULE_STS_INDEX_TITLE;
    $this->description = MODULE_STS_INDEX_DESCRIPTION.' (v2.0.0)';
	$this->sort_order=2;
	$this->enabled = ((MODULE_STS_INDEX_STATUS == 'true') ? true : false);  
  }

  function find_template (){
  // Return an html file to use as template
    global $cPath;
	
	// If no cPath defined, then we are on the main page. Code modified in v1.0.3, was not working before.
	if (tep_not_null($cPath)) $sts_cpath=$cPath; 
	  else $sts_cpath='0';

	// Added in v1.0.2: check for a specific manufacturer template
	if (isset($_GET['manufacturers_id'])) {
	  $check_file = STS_TEMPLATE_DIR . "index.php_mfr_".$_GET['manufacturers_id'].".html";
      if (file_exists($check_file)) {
      // Use it
		$this->template_file = $check_file;
		return $check_file;
      }	  
	}
		
	// Added in v1.0.2: check for a general manufacturer template
	if (isset($_GET['manufacturers_id'])) {
	  $sts_cpath = "mfr"; // This template will be checked during the next loop.
	}
    
	// Check if template for this category exists
    $check_file = STS_TEMPLATE_DIR . "index.php_$sts_cpath.html";
    if (file_exists($check_file)) {
    // Use it
	  $this->template_file = $check_file;
	  return $check_file;
	  
	}
	
	// Check parent categories templates if configured so in admin. Option since v1.0.3
	if (MODULE_STS_INDEX_PARENT=='true') {
	  $sts_cpath = substr($sts_cpath, 0, (strrpos($sts_cpath, "_")));
	  while ($sts_cpath != "") {
      // Look for category-specific template file like "index.php_1_17.html", then "index.php_1.html
        $check_file = STS_TEMPLATE_DIR . "index.php_$sts_cpath.html";
        if (file_exists($check_file)) {
        // Use it
		  $this->template_file = $check_file;
		  return $check_file;
        }
	    $sts_cpath = substr($sts_cpath, 0, (strrpos($sts_cpath, "_")));
	  } //end while
	}

	// No specific template for this category or its parents. Is there one for all categories?
	$check_file = STS_TEMPLATE_DIR . "index.php.html";
	if (file_exists($check_file)) return $check_file;
	
	// No specific template found, use default template
	return STS_DEFAULT_TEMPLATE;
	
  } // End function

  function capture_fields () {
  // Returns list of files to include from folder sts_inc in order to build the $template fields
    return 'general.php;'.MODULE_STS_INDEX_NORMAL;
  }
  
  function replace (&$template) {
    $template['content']=sts_strip_content_tags($template['content'], 'Index content');
  }

//======================================
// Functions needed for admin
//======================================
  
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_STS_INDEX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_STS_INDEX_STATUS', 'MODULE_STS_INDEX_NORMAL', 'MODULE_STS_INDEX_PARENT');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use template for index page', 'MODULE_STS_INDEX_STATUS', 'true', 'Do you want to use templates for index page?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Files for index.php template', 'MODULE_STS_INDEX_NORMAL', 'sts_user_code.php', 'Files to include for an index.php template, separated by semicolon', '6', '2', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Check parent templates', 'MODULE_STS_INDEX_PARENT', 'true', 'Do you want to check for parent categories templates?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }  

}// end class
?>
