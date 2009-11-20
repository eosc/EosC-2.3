<?php
/*
  $Id: ul_categories.php,v 1.00 2006/04/30 01:13:58 nate_02631 Exp $
	
	Outputs the store category list as a proper unordered list, opening up
	possibilities to use CSS to style as drop-down/flyout, collapsable or 
	other menu types.

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 Nate Welch http://www.natewelch.com

  Released under the GNU General Public License
*/

// BEGIN Configuration options

  // Set to false to display the unordered list only. Set to true to display in
	// a regular box. The former is useful for better integrating the menu with your layout.
	$show_ulcats_as_box = true;
	
	// Indicates whether or not to render your entire category list or just the root categories
	// and the currently selected submenu tree. Rendering the full list is useful for dynamic menu
	// generation where you want the user to have instant access to all categories. The other option
	// is the default oSC behaviour, when the subcats aren't available until the parent is clicked. 
	$show_full_tree = true;	
  
	// This is the CSS *ID* you want to assign to the UL (unordered list) containing
	// your category menu. Used in conjuction with the CSS list you create for the menu.
	// This value cannot be blank.
	$idname_for_menu = 'nav';
  
	// This is the *CLASSNAME* you want to tag a LI to indicate the selected category.
	// The currently selected category (and its parents, if any) will be tagged with
	// this class. Modify your stylesheet as appropriate. Leave blank or set to false to not assign a class. 
	$classname_for_selected = 'selected';
  
	// This is the *CLASSNAME* you want to tag a LI to indicate a category has subcategores.
	// Modify your stylesheet to draw an indicator to show the users that subcategories are
	// available. Leave blank or set to false to not assign a class. 	
	$classname_for_parent = 'daddy';
	
	// This is the HTML that you would like to appear before your categories menu if *not*
	// displaying in a standard "box". This is useful for reconciling tables or clearing
	// floats, depending on your layout needs.	
	$before_nobox_html = '';
	
	// This is the HTML that you would like to appear after your categories menu if *not*
	// displaying in a standard "box". This is useful for reconciling tables or clearing
	// floats, depending on your layout needs.	
  $after_nobox_html = '<div style="clear: both;">';	


// END Configuration options

// Global Variables
$GLOBALS['this_level'] = 0;

// Initialize HTML and info_box class if displaying inside a box
if ($show_ulcats_as_box) {
    echo '<tr><td>';
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_CATEGORIES);
    new infoBoxHeading($info_box_contents, true, false);					
}

// Generate a bulleted list (uses configuration options above)
$categories_string = tep_make_cat_ullist();

// Output list inside a box if specified, otherwise just output unordered list
if ($show_ulcats_as_box) {
    $info_box_contents = array();
    $info_box_contents[] = array('text' => $categories_string);
    new infoBox($info_box_contents);
		echo '</td></tr>';	
} else {
		echo $before_nobox_html;	
    echo $categories_string;
		echo $after_nobox_html;
}


// Create the root unordered list
function tep_make_cat_ullist($rootcatid = 0, $maxlevel = 0){

    global $idname_for_menu, $cPath_array, $show_full_tree, $languages_id;

    // Modify category query if not fetching all categories (limit to root cats and selected subcat tree)
		if (!$show_full_tree) {
        $parent_query	= 'AND (c.parent_id = "0"';	
				
				if (isset($cPath_array)) {
				
				    $cPath_array_temp = $cPath_array;
				
				    foreach($cPath_array_temp AS $key => $value) {
						    $parent_query	.= ' OR c.parent_id = "'.$value.'"';
						}
						
						unset($cPath_array_temp);
				}	
				
        $parent_query .= ')';				
		} else {
        $parent_query	= '';	
		}
		
		$result = tep_db_query('select c.categories_id, cd.categories_name, c.parent_id from ' . TABLE_CATEGORIES . ' c, ' . TABLE_CATEGORIES_DESCRIPTION . ' cd where c.categories_id = cd.categories_id and cd.language_id="' . (int)$languages_id .'" '.$parent_query.' order by sort_order, cd.categories_name');
    
		while ($row = tep_db_fetch_array($result)) {				
        $table[$row['parent_id']][$row['categories_id']] = $row['categories_name'];
    }

    $output .= '<ul id="'.$idname_for_menu.'">';
    $output .= tep_make_cat_ulbranch($rootcatid, $table, 0, $maxlevel);

		// Close off nested lists
    for ($nest = 0; $nest <= $GLOBALS['this_level']; $nest++) {
        $output .= '</ul>';		
		}
			 
    return $output;
}

// Create the branches of the unordered list
function tep_make_cat_ulbranch($parcat, $table, $level, $maxlevel) {

    global $cPath_array, $classname_for_selected, $classname_for_parent;
		
    $list = $table[$parcat];
	
    while(list($key,$val) = each($list)){
			 
        if ($GLOBALS['this_level'] != $level) {

		        if ($GLOBALS['this_level'] < $level) {
				        $output .= "\n".'<ul>';
				    } else {
                for ($nest = 1; $nest <= ($GLOBALS['this_level'] - $level); $nest++) {
                    $output .= '</ul></li>'."\n";	
		            }
	/*							
								if ($GLOBALS['this_level'] -1 == $level)
$output .= '</ul></li>'."\n";
elseif ($GLOBALS['this_level'] -2 == $level)
$output .= '</ul></li></ul></li>'."\n";
elseif ($GLOBALS['this_level'] -3 == $level)
$output .= '</ul></li></ul></li></ul></li>'."\n";
elseif ($GLOBALS['this_level'] -4 == $level)
$output .= '</ul></li></ul></li></ul></li></ul></li>'."\n"; 
	*/							
						}			
		
		        $GLOBALS['this_level'] = $level;
        }

        if (isset($cPath_array) && in_array($key, $cPath_array) && $classname_for_selected) {
            $this_cat_class = ' class="'.$classname_for_selected.'"';
        } else {
            $this_cat_class = '';		
		    }	
		
        $output .= '<li'.$this_cat_class.'><a href="';

        if (!$level) {
				    unset($GLOBALS['cPath_set']);
						$GLOBALS['cPath_set'][0] = $key;
            $cPath_new = 'cPath=' . $key;

        } else {
						$GLOBALS['cPath_set'][$level] = $key;		
            $cPath_new = 'cPath=' . implode("_", array_slice($GLOBALS['cPath_set'], 0, ($level+1)));
        }
	
        if (tep_has_category_subcategories($key) && $classname_for_parent) {
            $this_parent_class = ' class="'.$classname_for_parent.'"';
        } else {
            $this_parent_class = '';		
		    }				

        $output .= tep_href_link(FILENAME_DEFAULT, $cPath_new) . '"'.$this_parent_class.'>'.$val;		

        if (SHOW_COUNTS == 'true') {
            $products_in_category = tep_count_products_in_category($key);
            if ($products_in_category > 0) {
                $output .= '&nbsp;(' . $products_in_category . ')';
            }
        }
		
        $output .= '</a>';	

        if (!tep_has_category_subcategories($key)) {
            $output .= '</li>'."\n";	
        }						 
								
        if ((isset($table[$key])) AND (($maxlevel > $level + 1) OR ($maxlevel == '0'))) {
            $output .= tep_make_cat_ulbranch($key,$table,$level + 1,$maxlevel);
        }
    
		} // End while loop

    return $output;
}	

?>
