<?php
/*
  $Id: shipping_estimator.php,v 2.20 2004/07/01 15:16:07 eml Exp $

  v2.00 by Acheron + installed Fix for v2.0 and all other versions Acheron 7 Jul 2004
  (see Install.txt for partial version history)

  Copyright (c) 2004

  Released under the GNU General Public License

+ installed Fix for v2.0 and all other versions Acheron 7 Jul 2004 
*/
?>
<!-- shipping_estimator //-->
<script language="JavaScript">
  function shipincart_submit(sid){
    if(sid){
      document.estimator.sid.value=sid;
    }
    document.estimator.submit();
    return false;
  }
</script>
              <table width="60%" border="0" align="center"><tr valign="top"><td>

<?php

  require(DIR_WS_LANGUAGES . $language . '/modules/' . FILENAME_SHIPPING_ESTIMATOR);

if (($cart->count_contents() > 0)) {

  // shipping cost
  require('includes/classes/http_client.php'); // shipping in basket

  //if($cart->get_content_type() !== 'virtual') {
    if (tep_session_is_registered('customer_id')) {
      // user is logged in
      if (isset($_POST['address_id'])){
        // user changed address
        $sendto = $_POST['address_id'];
      }elseif (tep_session_is_registered('cart_address_id')){
        // user once changed address
        $sendto = $cart_address_id;
      }else{
        // first timer
        $sendto = $customer_default_address_id;
      }
      // set session now
      $cart_address_id = $sendto;
      tep_session_register('cart_address_id');
      // set shipping to null ! multipickup changes address to store address...
      $shipping='';
      // include the order class (uses the sendto !)
      require(DIR_WS_CLASSES . 'order.php');
      $order = new order;
    }else{
// user not logged in !
      if (isset($_POST['country_id'])){
        // country is selected
        $country_info = tep_get_countries($_POST['country_id'],true);
        $order->delivery = array('postcode' => $_POST['zip_code'],
                                 'country' => array('id' => $_POST['country_id'], 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
                                 'country_id' => $_POST['country_id'],
//add state zone_id
                                 'zone_id' => $_POST['state'],
                                 'format_id' => tep_get_address_format_id($_POST['country_id']));
        $cart_country_id = $_POST['country_id'];
        tep_session_register('cart_country_id');
//add state zone_id
        $cart_zone = $_POST['zone_id'];
        tep_session_register('cart_zone');
        $cart_zip_code = $_POST['zip_code'];
        tep_session_register('cart_zip_code');
      }elseif (tep_session_is_registered('cart_country_id')){
        // session is available
        $country_info = tep_get_countries($cart_country_id,true);
        $order->delivery = array('postcode' => $cart_zip_code,
                                 'country' => array('id' => $cart_country_id, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
                                 'country_id' => $cart_country_id,
                                 'format_id' => tep_get_address_format_id($cart_country_id));
      } else {
        // first timer
        $cart_country_id = STORE_COUNTRY;
        tep_session_register('cart_country_id');
        $country_info = tep_get_countries(STORE_COUNTRY,true);
        tep_session_register('cart_zip_code');
        $order->delivery = array(//'postcode' => '',
                                 'country' => array('id' => STORE_COUNTRY, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
                                 'country_id' => STORE_COUNTRY,
                                 'format_id' => tep_get_address_format_id($_POST['country_id']));
      }
      // set the cost to be able to calculate free shipping
      $order->info = array('total' => $cart->show_total(), // TAX ????
                           'currency' => $currency,
                           'currency_value'=> $currencies->currencies[$currency]['value']);
    }
// weight and count needed for shipping
    $total_weight = $cart->show_weight();
    $total_count = $cart->count_contents();
    require(DIR_WS_CLASSES . 'shipping.php');
    $shipping_modules = new shipping;
    $quotes = $shipping_modules->quote();
    $order->info['subtotal'] = $cart->total;

// set selections for displaying
    $selected_country = $order->delivery['country']['id'];
    $selected_address = $sendto;
  //}
// eo shipping cost

  // check free shipping based on order total
  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true')) {
    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true; break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true; break;
      case 'both':
        $pass = true; break;
      default:
        $pass = false; break;
    }
    $free_shipping = false;
    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) {
      $free_shipping = true;
      include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }
  // begin shipping cost
  if(!$free_shipping && $cart->get_content_type() !== 'virtual'){
    if (tep_not_null($_POST['sid'])){
      list($module, $method) = explode('_', $_POST['sid']);
      $cart_sid = $_POST['sid'];
      tep_session_register('cart_sid');
    }elseif (tep_session_is_registered('cart_sid')){
      list($module, $method) = explode('_', $cart_sid);
    }else{
      $module="";
      $method="";
    }
    if (tep_not_null($module)){
      $selected_quote = $shipping_modules->quote($method, $module);
      if($selected_quote[0]['error'] || !tep_not_null($selected_quote[0]['methods'][0]['cost'])){
        $selected_shipping = $shipping_modules->cheapest();
        $order->info['shipping_method'] = $selected_shipping['title'];
        $order->info['shipping_cost'] = $selected_shipping['cost'];
        $order->info['total']+= $selected_shipping['cost'];
      }else{
        $order->info['shipping_method'] = $selected_quote[0]['module'].' ('.$selected_quote[0]['methods'][0]['title'].')';
        $order->info['shipping_cost'] = $selected_quote[0]['methods'][0]['cost'];
        $order->info['total']+= $selected_quote[0]['methods'][0]['cost'];
        $selected_shipping['title'] = $order->info['shipping_method'];
        $selected_shipping['cost'] = $order->info['shipping_cost'];
        $selected_shipping['id'] = $selected_quote[0]['id'].'_'.$selected_quote[0]['methods'][0]['id'];
      }
    }else{
      $selected_shipping = $shipping_modules->cheapest();
      $order->info['shipping_method'] = $selected_shipping['title'];
      $order->info['shipping_cost'] = $selected_shipping['cost'];
      $order->info['total']+= $selected_shipping['cost'];
    }
  }
// virtual products use free shipping
  if($cart->get_content_type() == 'virtual') {
    $order->info['shipping_method'] = CART_SHIPPING_METHOD_FREE_TEXT . ' ' . CART_SHIPPING_METHOD_ALL_DOWNLOADS;
    $order->info['shipping_cost'] = 0;
  }
  if($free_shipping) {
    $order->info['shipping_method'] = MODULE_ORDER_TOTAL_SHIPPING_TITLE;
    $order->info['shipping_cost'] = 0;
  }
  $shipping=$selected_shipping;
// end of shipping cost
// end free shipping based on order total

  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<b>' . CART_SHIPPING_OPTIONS . '</b>'); // azer for 2.20 cosmetic change
  new infoBoxHeading($info_box_contents, false, false);

  $ShipTxt= tep_draw_form('estimator', tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'), 'post'); //'onSubmit="return check_form();"'
  $ShipTxt.=tep_draw_hidden_field('sid', $selected_shipping['id']);
  $ShipTxt.='<table border="0" width="100%">';
  if(sizeof($quotes)) {
    if (tep_session_is_registered('customer_id')) {
      // logged in

  if (CARTSHIP_SHOWWT == 'true') {
    $showweight = '&nbsp;(' . $total_weight . '&nbsp;' . CARTSHIP_WTUNIT . ')';
  } else {
    $showweight = '';
  }

        if(CARTSHIP_SHOWIC == 'true'){
      //ishazer remover hard code for version 2.20 : $ShipTxt.='<tr><td class="main">' . ($total_count == 1 ? ' <b>Item:</b></td><td colspan="2" class="main">' : ' <b>' . CART_ITEM . '</b></td><td colspan="2" class="main">') . $total_count . $showweight . '</td></tr>';
      $ShipTxt.='<tr><td class="main">' . ($total_count == 1 ? ' <b>' . CART_ITEM . '</b></td><td colspan="2" class="main">' : ' <b>' . CART_ITEM . '</b></td><td colspan="2" class="main">') . $total_count . $showweight . '</td></tr>';
      
       }
      $addresses_query = tep_db_query("select address_book_id, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "'");
      // only display addresses if more than 1
      if (tep_db_num_rows($addresses_query) > 1){
        while ($addresses = tep_db_fetch_array($addresses_query)) {
          $addresses_array[] = array('id' => $addresses['address_book_id'], 'text' => tep_address_format(tep_get_address_format_id($addresses['country_id']), $addresses, 0, ' ', ' '));
        }
        $ShipTxt.='<tr><td colspan="3" class="main" nowrap>' .
                  CART_SHIPPING_METHOD_ADDRESS .'&nbsp;'. tep_draw_pull_down_menu('address_id', $addresses_array, $selected_address, 'onchange="return shipincart_submit(\'\');"').'</td></tr>';
      }
      $ShipTxt.='<tr valign="top"><td class="main"><b>' . CART_SHIPPING_METHOD_TO .'</b>&nbsp;</td><td colspan="2" class="main">'. tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>') . '</td></tr>';

    } else {
// not logged in
      $ShipTxt.=CART_SHIPPING_OPTIONS_LOGIN;

        if(CARTSHIP_SHOWIC == 'true'){
 //azer for 2.20:      $ShipTxt.='<tr><td class="main">' . ($total_count == 1 ? ' <b>Item:</b></td><td colspan="2" class="main">' : ' <b>Items:</b></td><td colspan="2" class="main">') . $total_count . $showweight . '</td></tr>';
              $ShipTxt.='<tr><td class="main" nowrap>' . ($total_count == 1 ? ' <b>' . CART_ITEM . '</b></td><td colspan="2" class="main" nowrap>' : ' <b>' . CART_ITEM . '</b></td><td colspan="2" class="main">') . $total_count . $showweight . '</td></tr>';
             
       }

      if($cart->get_content_type() != 'virtual'){

        if(CARTSHIP_SHOWCDD == 'true'){
        $ShipTxt.='<tr><td colspan="3" class="main" nowrap>' .
                  ENTRY_COUNTRY .'&nbsp;'. tep_get_country_list('country_id', $selected_country,'style="width=200"').'<br />';
        }

//add state zone_id
        $state_array[] = array('id' => '', 'text' => 'Please Select');
        $state_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '$selected_country' order by zone_country_id DESC, zone_name");
        while ($state_values = tep_db_fetch_array($state_query)) {
          $state_array[] = array('id' => $state_values['zone_id'],
                                 'text' => $state_values['zone_name']);
        }

        if(CARTSHIP_SHOWSDD == 'true'){
         $ShipTxt.=ENTRY_STATE .'&nbsp;'. tep_draw_pull_down_menu('state',$state_array).'<br />';
        }

        if(CARTSHIP_SHOWZDD == 'true'){
          $ShipTxt.=ENTRY_POST_CODE .'&nbsp;'. tep_draw_input_field('zip_code', $selected_zip, 'size="5"');
        }
//        $ShipTxt.='&nbsp;<a href="_" onclick="return shipincart_submit(\'\');">'.CART_SHIPPING_METHOD_RECALCULATE.'</a></td></tr>';

        if(CARTSHIP_SHOWUB == 'true'){
$ShipTxt.='&nbsp;<td><a href="_" onclick="return shipincart_submit(\'\');">'. tep_image_button('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART) . ' </a></td></td></tr>';
        }
        }
    }
    if($cart->get_content_type() == 'virtual'){
      // virtual product-download
      //$ShipTxt.='<tr><td colspan="3" class="main">'.tep_draw_separator().'</td></tr>';
      $ShipTxt.='<tr><td class="main" colspan="3">&nbsp;</td></tr><tr><td class="main" colspan="3"><i>' . CART_SHIPPING_METHOD_FREE_TEXT . ' ' . CART_SHIPPING_METHOD_ALL_DOWNLOADS . '</i></td></tr>';
    }elseif ($free_shipping==1) {
      // order $total is free
      //$ShipTxt.='<tr><td colspan="3" class="main">'.tep_draw_separator().'</td></tr>';
      $ShipTxt.='<tr><td class="main" colspan="3">&nbsp;</td></tr><tr><td class="main" colspan="3"><i>' . sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . '</i></td><td>&nbsp;</td></tr>';
    }else{
      // shipping display
	  if ( empty($quotes[0]['error']) || (!empty($quotes[1])&&empty($quotes[1]['error'])) ) {
        $ShipTxt.='<tr><td colspan="3" class="main">&nbsp;</td></tr><tr><td class="main"><b>' . CART_SHIPPING_CARRIER_TEXT . '</b></td><td class="main" align="left"><b>' . CART_SHIPPING_METHOD_TEXT . '</b></td><td class="main" align="right"><b>' . CART_SHIPPING_METHOD_RATES . '</b></td></tr>';
        $ShipTxt.='<tr><td colspan="3" class="main">'.tep_draw_separator().'</td></tr>';
	  } else {
	    $ShipTxt.='<tr><td colspan="3" class="main">&nbsp;</td></tr>';
	  }
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
        if(sizeof($quotes[$i]['methods'])==1){
          // simple shipping method
          $thisquoteid = $quotes[$i]['id'].'_'.$quotes[$i]['methods'][0]['id'];
          $ShipTxt.= '<tr class="'.$extra.'">';
          $ShipTxt.='<td class="main">'.$quotes[$i]['icon'].'&nbsp;&nbsp;&nbsp;</td>';
          if($quotes[$i]['error']){
            $ShipTxt.='<td colspan="2" class="main">'.$quotes[$i]['module'].'&nbsp;';
            $ShipTxt.= '('.$quotes[$i]['error'].')</td></tr>';
          }else{
            if($selected_shipping['id'] == $thisquoteid){
             // commented for v2.10 : $ShipTxt.='<td class="main"><a title="Select this method" href="_"  onclick="return shipincart_submit(\''.$thisquoteid.'\');"><b>'.$quotes[$i]['module'].'&nbsp;';
$ShipTxt.='<td class="main"><a title="' . CART_SELECT_THIS_METHOD .'" href="_"  onclick="return shipincart_submit(\''.$thisquoteid.'\');"><b>'.$quotes[$i]['module'].'&nbsp;';

              $ShipTxt.= '('.$quotes[$i]['methods'][0]['title'].')</b></a>&nbsp;&nbsp;&nbsp;</td><td align="right" class="main"><b>'.$currencies->format(tep_add_tax($quotes[$i]['methods'][0]['cost'], $quotes[$i]['tax'])).'</b></td></tr>';
            }else{
             // commented for v2.10 : $ShipTxt.='<td class="main"><a title="Select this method" href="_" onclick="return shipincart_submit(\''.$thisquoteid.'\');">'.$quotes[$i]['module'].'&nbsp;';
 $ShipTxt.='<td class="main"><a title="' . CART_SELECT_THIS_METHOD .'" href="_" onclick="return shipincart_submit(\''.$thisquoteid.'\');">'.$quotes[$i]['module'].'&nbsp;';

              $ShipTxt.= '('.$quotes[$i]['methods'][0]['title'].')</a>&nbsp;&nbsp;&nbsp;</td><td align="right" class="main">'.$currencies->format(tep_add_tax($quotes[$i]['methods'][0]['cost'], $quotes[$i]['tax'])).'</td></tr>';
            }
          }
        } else {
          // shipping method with sub methods (multipickup)
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
            $thisquoteid = $quotes[$i]['id'].'_'.$quotes[$i]['methods'][$j]['id'];
            $ShipTxt.= '<tr class="'.$extra.'">';
            $ShipTxt.='<td class="main">'.$quotes[$i]['icon'].'&nbsp;&nbsp;&nbsp;</td>';
            if($quotes[$i]['error']){
              $ShipTxt.='<td colspan="2" class="main">'.$quotes[$i]['module'].'&nbsp;';
              $ShipTxt.= '('.$quotes[$i]['error'].')</td></tr>';
            }else{
              if($selected_shipping['id'] == $thisquoteid){
               // commented for v2.10 :  $ShipTxt.='<td class="main"><a title="Select this method" href="_" onclick="return shipincart_submit(\''.$thisquoteid.'\');"><b>'.$quotes[$i]['module'].'&nbsp;';
$ShipTxt.='<td class="main"><a title="' . CART_SELECT_THIS_METHOD .'" href="_" onclick="return shipincart_submit(\''.$thisquoteid.'\');"><b>'.$quotes[$i]['module'].'&nbsp;';

                $ShipTxt.= '('.$quotes[$i]['methods'][$j]['title'].')</b></a>&nbsp;&nbsp;&nbsp;</td><td align="right" class="main"><b>'.$currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])).'</b></td><td class="main">'.tep_image(DIR_WS_ICONS . 'selected.gif', 'Selected').'</td></tr>';
              }else{
              // commented for v2.10 :   $ShipTxt.='<td class="main"><a title="Select this method" href="_" onclick="return shipincart_submit(\''.$thisquoteid.'\');">'.$quotes[$i]['module'].'&nbsp;';
 $ShipTxt.='<td class="main"><a title="' . CART_SELECT_THIS_METHOD .'" href="_" onclick="return shipincart_submit(\''.$thisquoteid.'\');">'.$quotes[$i]['module'].'&nbsp;';

                $ShipTxt.= '('.$quotes[$i]['methods'][$j]['title'].')</a>&nbsp;&nbsp;&nbsp;</td><td align="right" class="main">'.$currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])).'</td><td class="main">&nbsp;</td></tr>';
              }
            }
          }
        }
      }
    }
  }
  $ShipTxt.= '</table></form>';

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $ShipTxt);
  new infoBox($info_box_contents);

  if (CARTSHIP_SHOWOT == 'true'){
    // BOF get taxes if not logged in
    if (!tep_session_is_registered('customer_id')){
      $products = $cart->get_products();
      for ($i=0, $n=sizeof($products); $i<$n; $i++) {
        $products_tax = tep_get_tax_rate($products[$i]['tax_class_id'], $order->delivery['country_id'],$order->delivery['zone_id']);
        $products_tax_description = tep_get_tax_description($products[$i]['tax_class_id'], $order->delivery['country_id'], $order->delivery['zone_id']);
        if (DISPLAY_PRICE_WITH_TAX == 'true') {
         //Modified by Strider 42 to correct the tax calculation when a customer is not logged in
         // $tax_val = ($products[$i]['final_price']-(($products[$i]['final_price']*100)/(100+$products_tax)))*$products[$i]['quantity'];
          $tax_val = (($products[$i]['final_price']/100)*$products_tax)*$products[$i]['quantity'];
        } else {
          $tax_val = (($products[$i]['final_price']*$products_tax)/100)*$products[$i]['quantity'];
        }
        $order->info['tax'] += $tax_val;
        $order->info['tax_groups']["$products_tax_description"] += $tax_val;
        // Modified by Strider 42 to correct the order total figure when shop displays prices with tax
        if (DISPLAY_PRICE_WITH_TAX == 'true') {
           $order->info['total'];
        } else {
        $order->info['total']+=$tax_val;
               }
      }
    }
    // EOF get taxes if not logged in (seems like less code than in order class)
    require(DIR_WS_CLASSES . 'order_total.php');
    $order_total_modules = new order_total;
    //echo '</td><td align="right">';
    // order total code
    $order_total_modules->process();

    $info_box_contents = array();
  $info_box_contents[] = array('text' => '<b>' . CART_OT . '</b>'); //azer version 2.20

    new infoBoxHeading($info_box_contents, false, false);
    $otTxt='<table border="0" align="right">';
    $otTxt.=$order_total_modules->output().'</table>';

    $info_box_contents = array();
    $info_box_contents[] = array('text' => $otTxt);

    new infoBox($info_box_contents);
  }
} // Use only when cart_contents > 0

?>
             </td></tr></table>