<?php

/****************************************************** 
* Email Invoice 1.1.
* Author Contact: federicorodriguez911@gmail.com
******************************************************/

// Why go through all the processing if we don't have to, to begin with?
// This is kind of a shortcoming of the tep mail function that only tests
// this value after the email has already been compiled

if (SEND_EMAILS == 'true') {
	// One could assume that if you want to send the HTML invoice then this would be redundant,
	// but if for some reason the store owner changed their mind and ceased all HTML emails but forgot
	// to disable this mod then this would still honor their decision and not add the extra overhead
	// of compiling the HTML version, ond only send the original text version 
	if (EMAIL_USE_HTML == 'true') {
		
		$ei_admin = DIR_FS_ADMIN;
		$ei_template_dir = DIR_WS_MODULES . EMAIL_INVOICE_DIR . INVOICE_TEMPLATE_DIR ;
		$ei_image_dir = HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES;
		$ei_css_path = HTTP_SERVER . DIR_WS_CATALOG . $ei_template_dir;
		$ei_template_file = $ei_template_dir . EMAIL_TEMPLATE_FILE;
		$ei_temp_file = DIR_WS_MODULES . EMAIL_INVOICE_DIR . FILENAME_EMAIL_CACHE_FILE;

		require(DIR_FS_ADMIN . DIR_WS_LANGUAGES . $language . "/" . FILENAME_ORDERS_INVOICE);

		$currencies = new currencies();
		$oID = $insert_id;
		$orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
		$order = new order($oID);
		$date = date('M d, Y');
	
		ob_start();
		include($ei_template_file);
		//this can be done in one funciton call in PHP >= 4.3.0 but to keep it compatible, I use 2
		$ei_html_email = ob_get_contents();
		ob_end_clean();

		// Replace relative paths to absolute paths
		// and space since the email class adds tons of <br> tags if you don't
		// strip them out first

		$ei_search = array(	"\n" ,
							"\r"
						);

		$ei_html_email = str_replace($ei_search, "", $ei_html_email);
		$ei_html_email = str_replace('src="images/', "src=\"$ei_image_dir", $ei_html_email);
		
	}

	// Build the standard email using OSC code
  	$email_order = 	STORE_NAME . "\n" . 
                 		EMAIL_SEPARATOR . "\n" . 
                 		EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 		EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" .
                 		EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
  	
	if ($order->info['comments']) {
    		
		$email_order .= tep_db_output($order->info['comments']) . "\n\n";
 	 }
  	
	$email_order .= 	EMAIL_TEXT_PRODUCTS . "\n" . 
                  	EMAIL_SEPARATOR . "\n" . 
                  	$products_ordered . 
                  	EMAIL_SEPARATOR . "\n";

  	for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    		
		$email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
  	}

  	if ($order->content_type != 'virtual') {
    		
		$email_order .= "\n" . 	EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
                    			EMAIL_SEPARATOR . "\n" .
                    			tep_address_label($customer_id, $sendto, 0, '', "\n") . "\n";
  	}

  	$email_order .= "\n" . 	EMAIL_TEXT_BILLING_ADDRESS . "\n" .
                 			 EMAIL_SEPARATOR . "\n" .
                  		tep_address_label($customer_id, $billto, 0, '', "\n") . "\n\n";
  	
	if (is_object($$payment)) {
    		
		$email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
                    		EMAIL_SEPARATOR . "\n";
    		$payment_class = $$payment;
    		$email_order .= $payment_class->title . "\n\n";
    		
		if ($payment_class->email_footer) { 
      		
			$email_order .= $payment_class->email_footer . "\n\n";
    		}
  	}
  
	// Add both versions to the email to accomodate people who see html and those that don't
	$ei_message = new email(array('X-Mailer: osCommerce Mailer'));
	
	// Build the text version
    	$ei_text = strip_tags($email_order);


	if (!empty($ei_html_email)) {
	
		$ei_message->add_html($ei_html_email, $ei_text);
		
	} else {
		
		$ei_message->add_text($ei_text);	
	
	}
		
	$ei_message->build_message();
	$ei_message->send($order->customer['name'], $order->customer['email_address'], STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_TEXT_SUBJECT);
		
	if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    	
		$ei_message->send('', SEND_EXTRA_ORDER_EMAILS_TO, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_TEXT_SUBJECT);
  	
	}	
	

}

?>
