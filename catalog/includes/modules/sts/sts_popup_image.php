<?php
/*
$Id: sts_popup_image.php,v 1.0.0 2005/12/12 09:36:00 Rigadin Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2005 osCommerce

Released under the GNU General Public License

STS v4 module for popup_image.php by Rigadin (rigadin@osc-help.net)
* Requires STS v4.4 or newer
*/

class sts_popup_image {

  var $template_file;
  
  function sts_popup_image (){
    $this->code = 'sts_popup_image';
    $this->title = MODULE_STS_POPUP_IMAGE_TITLE;
    $this->description = MODULE_STS_POPUP_IMAGE_DESCRIPTION.' (v1.0.0)';
	  $this->sort_order=6;
    $this->enabled = ((MODULE_STS_POPUP_IMAGE_STATUS == 'true') ? true : false);
  }

  function find_template (){
  // Return an html file to use as template
    
		if ($this->enabled!=true) return ''; // Do not use any template if module not enabled
		
	  // Module enabled, is there a template for this particular page?
	  $check_file= STS_TEMPLATE_DIR . "popup_image.php.html";
	  if (file_exists($check_file)) {
	    // Use it
	    $this->template_file = $check_file;
	    return $check_file;
    } else return '';  // No specific template found, so we don't use template at all
  }

  function capture_fields () {
  // Returns list of files to include from folder sts_inc in order to build the $template fields
	    return MODULE_STS_POPUP_IMAGE_FILES;
  }

  function replace (&$template) {
  // If we do not use a content template, extract the content from buffer
    if ($this->content_template_file=='') {
	    //$template['content']= sts_strip_content_tags($template['content'], 'Product Info Content');
	    return;
	  }
  }
  
	
//======================================
// Private Functions
//======================================


//======================================
// Functions needed for admin
//======================================
  
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_STS_POPUP_IMAGE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_STS_POPUP_IMAGE_STATUS','MODULE_STS_POPUP_IMAGE_FILES');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use template for the image popup page', 'MODULE_STS_POPUP_IMAGE_STATUS', 'false', 'Do you want to use templates for the image popup page', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Files to include', 'MODULE_STS_POPUP_IMAGE_FILES', 'popup_image.php', 'Files to include for a normal template, separated by semicolon', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    } 
  
}// end class
?>