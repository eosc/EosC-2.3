<?php
/*
  $Id: order.php,v 1.33 2003/06/09 22:25:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class order {
    var $info, $totals, $products, $customer, $delivery, $content_type;

    function order($order_id = '') {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      if (tep_not_null($order_id)) {
        $this->query($order_id);
      } else {
// >>> BEGIN REGISTER_GLOBALS
        link_post_variable('cc_type'); 
        link_post_variable('cc_owner'); 
        link_post_variable('cc_number'); 
        link_post_variable('cc_expires'); 
        link_post_variable('comments'); 
// <<< END REGISTER_GLOBALS
        $this->cart();
      }
    }

    function query($order_id) {
      global $languages_id;

      $order_id = tep_db_prepare_input($order_id);

      $order_query = tep_db_query("select customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
      $order = tep_db_fetch_array($order_query);

      $totals_query = tep_db_query("select title, text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while ($totals = tep_db_fetch_array($totals_query)) {
        $this->totals[] = array('title' => $totals['title'],
                                'text' => $totals['text']);
      }

      $order_total_query = tep_db_query("select text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_total'");
      $order_total = tep_db_fetch_array($order_total_query);

      $shipping_method_query = tep_db_query("select title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_shipping'");
      $shipping_method = tep_db_fetch_array($shipping_method_query);

      $order_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . $order['orders_status'] . "' and language_id = '" . (int)$languages_id . "'");
      $order_status = tep_db_fetch_array($order_status_query);

      $this->info = array('currency' => $order['currency'],
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
                          'cc_type' => $order['cc_type'],
                          'cc_owner' => $order['cc_owner'],
                          'cc_number' => $order['cc_number'],
                          'cc_expires' => $order['cc_expires'],
                          'date_purchased' => $order['date_purchased'],
                          'orders_status' => $order_status['orders_status_name'],
                          'last_modified' => $order['last_modified'],
                          'total' => strip_tags($order_total['text']),
                          'shipping_method' => ((substr($shipping_method['title'], -1) == ':') ? substr(strip_tags($shipping_method['title']), 0, -1) : strip_tags($shipping_method['title'])));

      $this->customer = array('id' => $order['customers_id'],
                              'name' => $order['customers_name'],
                              'company' => $order['customers_company'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'email_address' => $order['customers_email_address']);

      $this->delivery = array('name' => $order['delivery_name'],
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $order['delivery_country'],
                              'format_id' => $order['delivery_address_format_id']);

      if (empty($this->delivery['name']) && empty($this->delivery['street_address'])) {
        $this->delivery = false;
      }

      $this->billing = array('name' => $order['billing_name'],
                             'company' => $order['billing_company'],
                             'street_address' => $order['billing_street_address'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $order['billing_country'],
                             'format_id' => $order['billing_address_format_id']);

      $index = 0;
      $orders_products_query = tep_db_query("select orders_products_id, products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
      while ($orders_products = tep_db_fetch_array($orders_products_query)) {
        $this->products[$index] = array('qty' => $orders_products['products_quantity'],
	                                'id' => $orders_products['products_id'],
                                        'name' => $orders_products['products_name'],
                                        'model' => $orders_products['products_model'],
                                        'tax' => $orders_products['products_tax'],
                                        'price' => $orders_products['products_price'],
                                        'final_price' => $orders_products['final_price']);

// BOF Separate Pricing Per Customer
  if(!tep_session_is_registered('sppc_customer_group_id')) { 
  $customer_group_id = '0';
  } else {
   $customer_group_id = $sppc_customer_group_id;
  }

   if ($customer_group_id != '0'){	
   $orders_customers_price = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '". $customer_group_id . "' and products_id = '" . $products[$i]['id'] . "'");      	
   if ($orders_customers = tep_db_fetch_array($orders_customers_price)){	
      $this->products[$index] = array('price' => $orders_customers['customers_group_price'], 'final_price' => $orders_customers['customers_group_price']);
  }                              
}
// EOF Separate Pricing Per Customer
        $subindex = 0;
        $attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($attributes_query)) {
          while ($attributes = tep_db_fetch_array($attributes_query)) {
            $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
                                                                     'value' => $attributes['products_options_values'],
                                                                     'prefix' => $attributes['price_prefix'],
                                                                     'price' => $attributes['options_values_price']);

            $subindex++;
          }
        }

        $this->info['tax_groups']["{$this->products[$index]['tax']}"] = '1';

        $index++;
      }
    }

    function cart() {
      global $customer_id, $sendto, $billto, $cart, $languages_id, $currency, $currencies, $shipping, $payment;

      $this->content_type = $cart->get_content_type();

      $customer_address_query = tep_db_query("select c.customers_firstname, c.customers_lastname, c.customers_telephone, c.customers_email_address, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id, ab.entry_state from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " co on (ab.entry_country_id = co.countries_id) where c.customers_id = '" . (int)$customer_id . "' and ab.customers_id = '" . (int)$customer_id . "' and c.customers_default_address_id = ab.address_book_id");
      $customer_address = tep_db_fetch_array($customer_address_query);

      $shipping_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$customer_id . "' and ab.address_book_id = '" . (int)$sendto . "'");
      $shipping_address = tep_db_fetch_array($shipping_address_query);
      
      $billing_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$customer_id . "' and ab.address_book_id = '" . (int)$billto . "'");
      $billing_address = tep_db_fetch_array($billing_address_query);

      $tax_address_query = tep_db_query("select ab.entry_country_id, ab.entry_zone_id from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) where ab.customers_id = '" . (int)$customer_id . "' and ab.address_book_id = '" . (int)($this->content_type == 'virtual' ? $billto : $sendto) . "'");
      $tax_address = tep_db_fetch_array($tax_address_query);

      $this->info = array('order_status' => DEFAULT_ORDERS_STATUS_ID,
                          'currency' => $currency,
                          'currency_value' => $currencies->currencies[$currency]['value'],
                          'payment_method' => $payment,
                          'cc_type' => (isset($GLOBALS['cc_type']) ? $GLOBALS['cc_type'] : ''),
                          'cc_owner' => (isset($GLOBALS['cc_owner']) ? $GLOBALS['cc_owner'] : ''),
                          'cc_number' => (isset($GLOBALS['cc_number']) ? $GLOBALS['cc_number'] : ''),
                          'cc_expires' => (isset($GLOBALS['cc_expires']) ? $GLOBALS['cc_expires'] : ''),
                          'shipping_method' => $shipping['title'],
                          'shipping_cost' => $shipping['cost'],
                          'subtotal' => 0,
                          'tax' => 0,
                          'tax_groups' => array(),
                          'comments' => (isset($GLOBALS['comments']) ? $GLOBALS['comments'] : ''));

      if (isset($GLOBALS[$payment]) && is_object($GLOBALS[$payment])) {
        $this->info['payment_method'] = $GLOBALS[$payment]->title;

        if ( isset($GLOBALS[$payment]->order_status) && is_numeric($GLOBALS[$payment]->order_status) && ($GLOBALS[$payment]->order_status > 0) ) {
          $this->info['order_status'] = $GLOBALS[$payment]->order_status;
        }
      }

      $this->customer = array('firstname' => $customer_address['customers_firstname'],
                              'lastname' => $customer_address['customers_lastname'],
                              'company' => $customer_address['entry_company'],
                              'street_address' => $customer_address['entry_street_address'],
                              'suburb' => $customer_address['entry_suburb'],
                              'city' => $customer_address['entry_city'],
                              'postcode' => $customer_address['entry_postcode'],
                              'state' => ((tep_not_null($customer_address['entry_state'])) ? $customer_address['entry_state'] : $customer_address['zone_name']),
                              'zone_id' => $customer_address['entry_zone_id'],
                              'country' => array('id' => $customer_address['countries_id'], 'title' => $customer_address['countries_name'], 'iso_code_2' => $customer_address['countries_iso_code_2'], 'iso_code_3' => $customer_address['countries_iso_code_3']),
                              'format_id' => $customer_address['address_format_id'],
                              'telephone' => $customer_address['customers_telephone'],
                              'email_address' => $customer_address['customers_email_address']);

      $this->delivery = array('firstname' => $shipping_address['entry_firstname'],
                              'lastname' => $shipping_address['entry_lastname'],
                              'company' => $shipping_address['entry_company'],
                              'street_address' => $shipping_address['entry_street_address'],
                              'suburb' => $shipping_address['entry_suburb'],
                              'city' => $shipping_address['entry_city'],
                              'postcode' => $shipping_address['entry_postcode'],
                              'state' => ((tep_not_null($shipping_address['entry_state'])) ? $shipping_address['entry_state'] : $shipping_address['zone_name']),
                              'zone_id' => $shipping_address['entry_zone_id'],
                              'country' => array('id' => $shipping_address['countries_id'], 'title' => $shipping_address['countries_name'], 'iso_code_2' => $shipping_address['countries_iso_code_2'], 'iso_code_3' => $shipping_address['countries_iso_code_3']),
                              'country_id' => $shipping_address['entry_country_id'],
                              'format_id' => $shipping_address['address_format_id']);

      $this->billing = array('firstname' => $billing_address['entry_firstname'],
                             'lastname' => $billing_address['entry_lastname'],
                             'company' => $billing_address['entry_company'],
                             'street_address' => $billing_address['entry_street_address'],
                             'suburb' => $billing_address['entry_suburb'],
                             'city' => $billing_address['entry_city'],
                             'postcode' => $billing_address['entry_postcode'],
                             'state' => ((tep_not_null($billing_address['entry_state'])) ? $billing_address['entry_state'] : $billing_address['zone_name']),
                             'zone_id' => $billing_address['entry_zone_id'],
                             'country' => array('id' => $billing_address['countries_id'], 'title' => $billing_address['countries_name'], 'iso_code_2' => $billing_address['countries_iso_code_2'], 'iso_code_3' => $billing_address['countries_iso_code_3']),
                             'country_id' => $billing_address['entry_country_id'],
                             'format_id' => $billing_address['address_format_id']);

      $index = 0;
      $products = $cart->get_products();
      for ($i=0, $n=sizeof($products); $i<$n; $i++) {
        $this->products[$index] = array('qty' => $products[$i]['quantity'],
                                        'name' => $products[$i]['name'],
                                        'model' => $products[$i]['model'],
                                        'tax' => tep_get_tax_rate($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']),
                                        'tax_description' => tep_get_tax_description($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']),
                                        'price' => $products[$i]['price'],
                                        'final_price' => $products[$i]['price'] + $cart->attributes_price($products[$i]['id']),
                                        'weight' => $products[$i]['weight'],
                                        'id' => $products[$i]['id']);
// BOF Separate Pricing Per Customer
  if(!tep_session_is_registered('sppc_customer_group_id')) { 
  $customer_group_id = '0';
  } else {
   $customer_group_id = $sppc_customer_group_id;
  }
  if ($customer_group_id != '0'){
  $orders_customers_price = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '". $customer_group_id . "' and products_id = '" . $products[$i]['id'] . "'");
  $orders_customers = tep_db_fetch_array($orders_customers_price);
    if ($orders_customers = tep_db_fetch_array($orders_customers_price)) {	
    $this->products[$index] = array('price' => $orders_customers['customers_group_price'],
                                        'final_price' => $orders_customers['customers_group_price'] + $cart->attributes_price($products[$i]['id']));
    }
  }
// EOF Separate Pricing Per Customer
        if ($products[$i]['attributes']) {
          $subindex = 0;
          reset($products[$i]['attributes']);
          while (list($option, $value) = each($products[$i]['attributes'])) {
//++++ QT Pro: Begin Changed code
            $attributes_query = tep_db_query("select popt.products_options_name, popt.products_options_track_stock, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . (int)$products[$i]['id'] . "' and pa.options_id = '" . (int)$option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . (int)$value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . (int)$languages_id . "' and poval.language_id = '" . (int)$languages_id . "'");
//++++ QT Pro: End Changed Code
            $attributes = tep_db_fetch_array($attributes_query);

//++++ QT Pro: Begin Changed code
            $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options_name'],
                                                                     'value' => $attributes['products_options_values_name'],
                                                                     'option_id' => $option,
                                                                     'value_id' => $value,
                                                                     'prefix' => $attributes['price_prefix'],
                                                                     'price' => $attributes['options_values_price'],
                                                                     'track_stock' => $attributes['products_options_track_stock']);
//++++ QT Pro: End Changed Code

            $subindex++;
          }
        }

        $shown_price = tep_add_tax($this->products[$index]['final_price'], $this->products[$index]['tax']) * $this->products[$index]['qty'];
        $this->info['subtotal'] += $shown_price;

        $products_tax = $this->products[$index]['tax'];
        $products_tax_description = $this->products[$index]['tax_description'];
// BOF Separate Pricing Per Customer, show_tax modification
// next line was original code
//      if (DISPLAY_PRICE_WITH_TAX == 'true') {
	global $sppc_customer_group_show_tax;
        if(!tep_session_is_registered('sppc_customer_group_show_tax')) { 
        $customer_group_show_tax = '1';
        } else {
        $customer_group_show_tax = $sppc_customer_group_show_tax;
        }		
        if (DISPLAY_PRICE_WITH_TAX == 'true' && $customer_group_show_tax == '1') {
// EOF Separate Pricing Per Customer, show_tax modification		
          $this->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          if (isset($this->info['tax_groups']["$products_tax_description"])) {
            $this->info['tax_groups']["$products_tax_description"] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          } else {
            $this->info['tax_groups']["$products_tax_description"] = $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          }
        } else {
          $this->info['tax'] += ($products_tax / 100) * $shown_price;
          if (isset($this->info['tax_groups']["$products_tax_description"])) {
            $this->info['tax_groups']["$products_tax_description"] += ($products_tax / 100) * $shown_price;
          } else {
            $this->info['tax_groups']["$products_tax_description"] = ($products_tax / 100) * $shown_price;
          }
        }

        $index++;
      }

// BOF Separate Pricing Per Customer, show_tax modification
// next line was original code
//      if (DISPLAY_PRICE_WITH_TAX == 'true') {
	global $sppc_customer_group_show_tax;
        if(!tep_session_is_registered('sppc_customer_group_show_tax')) { 
        $customer_group_show_tax = '1';
        } else {
        $customer_group_show_tax = $sppc_customer_group_show_tax;
        }	
        if ((DISPLAY_PRICE_WITH_TAX == 'true') && ($customer_group_show_tax == '1')) {
// EOF Separate Pricing Per Customer, show_tax modification		
        $this->info['total'] = $this->info['subtotal'] + $this->info['shipping_cost'];
      } else {
        $this->info['total'] = $this->info['subtotal'] + $this->info['tax'] + $this->info['shipping_cost'];
      }
    }
  }
?>