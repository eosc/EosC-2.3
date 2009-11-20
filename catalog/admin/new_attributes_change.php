<?

/*
  $Id: new_attributes_change.php 
  
 New Attribute Manager v4b, Author: Mike G.
  
  Updates for New Attribute Manager v.5.0 and multilanguage support by: Kiril Nedelchev - kikoleppard
  kikoleppard@hotmail.bg
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// I found the easiest way to do this is just delete the current attributes & start over =)
MYSQL_QUERY( "DELETE FROM products_attributes WHERE products_id = '$current_product_id'" );

// Simple, yet effective.. loop through the selected Option Values.. find the proper price & prefix.. insert.. yadda yadda yadda.
for ($i = 0; $i < sizeof($optionValues); $i++) {

    $query = "SELECT * FROM products_options_values_to_products_options where products_options_values_id = '$optionValues[$i]'";

    $result = mysql_query($query) or die(mysql_error());

    $matches = mysql_num_rows($result);

       while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                                               	
            $optionsID = $line['products_options_id'];
            
       }


    $value_price =  $_POST[$optionValues[$i] . '_price'];

    $value_prefix = $_POST[$optionValues[$i] . '_prefix'];
    
    if ( $optionTypeInstalled == "1" ) {
                                       	
        $value_type = $_POST[$optionValues[$i] . '_type'];
        $value_qty = $_POST[$optionValues[$i] . '_qty'];
        $value_order = $_POST[$optionValues[$i] . '_order'];
        $value_linked = $_POST[$optionValues[$i] . '_linked'];
        
        MYSQL_QUERY( "INSERT INTO products_attributes ( products_id, options_id, options_values_id, options_values_price, price_prefix, options_type_id, options_values_qty, attribute_order, collegamento )
                     VALUES( '$current_product_id', '$optionsID', '$optionValues[$i]', '$value_price', '$value_prefix', '$value_type', '$value_qty', '$value_order', '$value_linked' )" ) or die(mysql_error());

// Linda McGrath's contribution or Forrest Miller's Product Attrib Sort 
                     
    } else if ( $optionSortCopyInstalled == "1" ) {
                                                  	
        $value_sort = $_POST[$optionValues[$i] . '_sort'];

        MYSQL_QUERY( "INSERT INTO products_attributes ( products_id, options_id, options_values_id, options_values_price, price_prefix, attribute_sort )
                     VALUES( '$current_product_id', '$optionsID', '$optionValues[$i]', '$value_price', '$value_prefix', '$value_sort' )" ) or die(mysql_error());
                     
    } else {

        MYSQL_QUERY( "INSERT INTO products_attributes ( products_id, options_id, options_values_id, options_values_price, price_prefix )
                     VALUES( '$current_product_id', '$optionsID', '$optionValues[$i]', '$value_price', '$value_prefix' )" ) or die(mysql_error());
                 
    }             

}

// For text input option type feature by chandra
if ( $optionTypeTextInstalled == "1" && is_array( $_POST['optionValuesText'] )) {
   
   for ($i = 0; $i < sizeof($optionValuesText); $i++) {
                                                      	
        $value_price =  $_POST[$optionValuesText[$i] . '_price'];

        $value_prefix = $_POST[$optionValuesText[$i] . '_prefix'];
        
        $value_product_id = $_POST[$optionValuesText[$i] . '_options_id'];
        
        MYSQL_QUERY( "INSERT INTO products_attributes ( products_id, options_id, options_values_id, options_values_price, price_prefix )
        VALUES( '$current_product_id', '$value_product_id', '0', '$value_price', '$value_prefix' )" ) or die(mysql_error());
        
   }
   
}










?>
