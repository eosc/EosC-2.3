<?php
/*
  $Id: newsletter & subscribers.php, v0.53  2003/06/09 23:03:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

   require('includes/application_top.php');
   require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSLETTERS);

  $subscribers_info = tep_db_query("select subscribers_id from " . TABLE_SUBSCRIBERS . " where subscribers_email_address = '" . $_POST['Email'] . "' ");
    $date_now = date('Ymd');

  if (!tep_db_num_rows($subscribers_info)) {
   $gender = '' ;
    tep_db_query("insert into " . TABLE_SUBSCRIBERS . " (subscribers_email_address, subscribers_lastname, language, subscribers_email_type, date_account_created, customers_newsletter,  subscribers_blacklist, hardiness_zone, status_sent1,  source_import) values ('" . strtolower($_POST['Email']) . "',  '" . ucwords(strtolower($_POST['lastname'])) . "',  'English',  '" . $_POST['email_type'] . "',  now() ,  '1',  '0', '" . $domain4 . "', '1', 'subscribe_newsletter')");
   } else {
    tep_db_query("update " . TABLE_SUBSCRIBERS . " set customers_newsletter = '" . '1' . "', subscribers_email_type = '" . $_POST['email_type'] . "'  where subscribers_email_address  = '" . $_POST['Email'] . "' ");
   }

 if ($email_type  == "HTMLXX") {
  // build the message content
    $newsletter_id='3';
    $newsletter_query = "select p.newsletter_info_subject, p.newsletter_info_logo, p.newsletter_info_title, p.newsletter_info_greetings, p.newsletter_info_intro, p.newsletter_info_promo1_name, p.newsletter_info_promo1_des, p.newsletter_info_promo1_img, p.newsletter_info_promo1_url, p.newsletter_info_promo1_link, p.newsletter_info_promo2_name, p.newsletter_info_promo2_des, p.newsletter_info_promo2_img, p.newsletter_info_promo2_url, p.newsletter_info_promo2_link, p.newsletter_info_final_para, p.newsletter_info_closing, q.newsletter_email_address, q.newsletter_template, q.newsletter_user, q.newsletter_site_name, q.newsletter_site_url, q.newsletter_phone, q.newsletter_mailing_address, q.newsletter_template from newsletter_info p , newsletter q where p.newsletter_id = '" . $newsletter_id . "' and q.newsletter_id = p.newsletter_id ";
    $newsletter = tep_db_query($newsletter_query);
    $newsletter_values = tep_db_fetch_array($newsletter);
    $gender = $_POST['gender'];
     if ($gender == 'F') {
          $email_greet1 = EMAIL_GREET_MS;
       } else {
          $email_greet1 = EMAIL_GREET_MR;
       }
    $customers_email_address = $_POST['Email']  ;
    $from = 'STORE <customerservice@mystore.com>'  ;
    $subject = $newsletter_values['newsletter_info_subject']  ;
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $name = $_POST['firstname'] . " " . $_POST['lastname'];
    $store_owner = '';
    $store_owner_email = '';
    $domain4 = trim($domain4);
    $email_address = strtolower($_POST['Email']);
    $gender = $gender;
    $email_text .= BLOCK1 . $newsletter_values['newsletter_info_title'] . BLOCK2 . $newsletter_values['newsletter_info_promo1_name'] . BLOCK3 . $newsletter_values['newsletter_info_promo1_url'] . BLOCK4 . $newsletter_values['newsletter_info_promo1_img'] . BLOCK5 . $newsletter_values['newsletter_info_promo1_des'] . BLOCK6 . $newsletter_values['newsletter_info_promo1_url'] . BLOCK7 . $newsletter_values['newsletter_info_promo1_link'] . BLOCK8 . BLOCK9 . $email_greet1  . $firstname . ' ' .  $lastname . ', ' . $newsletter_values['newsletter_info_greetings'] . '<br>' . BLOCK10 . '<br>' . $newsletter_values['newsletter_info_intro'] . BLOCK11 .  BLOCK12 .  BLOCK13 .  BLOCK14 .  BLOCK15 .  BLOCK16 .  BLOCK17 . $newsletter_values['newsletter_info_final_para'] . BLOCK18 . $newsletter_values['newsletter_info_closing'] . BLOCK19 . BLOCK20 . $email_address . BLOCK22  . BLOCK23 . 'email=' . $email_address . '&action=view' . BLOCK23A . BLOCK24 . 'email=' . $email_address . '&action=view' . BLOCK24A . BLOCK25 ;
    tep_mail1($name, $email_address, $subject, $email_text, $store_owner, $store_owner_email, '');

     } else {

 $message .= EMAIL_WELCOME . CLOSING_BLOCK1 . CLOSING_BLOCK2 . CLOSING_BLOCK3 . UNSUBSCRIBE . $_POST['Email'] ;
 mail(strtolower($_POST['Email']), EMAIL_WELCOME_SUBJECT, $message, "From: " . EMAIL_FROM);
}

   if ($_POST['origin']) {

      if (@$_POST['connection'] == 'SSL') {
        $connection_type = 'SSL';
      } else {
        $connection_type = 'NONSSL';
      }
  tep_redirect(tep_href_link($_POST['origin'], '', $connection_type));

  } else {

  tep_redirect(tep_href_link(FILENAME_NEWSLETTERS_SUBSCRIBE_SUCCESS, '', 'NONSSL'));
  }

?>



