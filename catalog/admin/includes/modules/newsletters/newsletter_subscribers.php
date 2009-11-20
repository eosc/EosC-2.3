<?php
/*
  $Id: newsletter.php,v 1.1 2002/03/08 18:38:18 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// ################# Contribution Newsletter v050 ##############
  class newsletter_subscribers {
    var $show_choose_audience, $newsletters_id, $module_subscribers, $title, $header, $contenta, $unsubscribea, $unsubscribeb;

    function newsletter_subscribers($newsletters_id, $module_subscribers, $title, $header, $contenta, $unsubscribea, $unsubscribeb) {
      $this->show_choose_audience = false;
      $this->newsletters_id = $newsletters_id;
      $this->module_subscribers = $module_subscribers;
      $this->title = $title;
	  $this->header = $header;
      $this->contenta = $contenta;
      $this->unsubscribea = $unsubscribea;
      $this->unsubscribeb = $unsubscribeb;      					
    }
// ################# END - Contribution Newsletter v050 ##############

    function choose_audience() {
      return false;
    }

    function confirm() {
      global $_GET;

      $mail_query = tep_db_query("select count(*) as count from " . TABLE_SUBSCRIBERS . " where customers_newsletter = '1' and subscribers_blacklist = '0' ");
      $mail = tep_db_fetch_array($mail_query);

// ################# Contribution Newsletter v050 ##############
      $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . tep_draw_separator('pixel_trans.gif', '20', '1') .  TEXT_TITRE_INFO . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .												
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main">' . sprintf(TEXT_COUNT_CUSTOMERS, $mail['count']) . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main">' . TEXT_BULLETIN_NUMB . "&nbsp;" . '<font color="#0000ff">' . $this->newsletters_id . '</font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main">' . TEXT_MODULE . "&nbsp;" . '<font color="#0000ff">' . $this->module_subscribers . '</font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main">' . TEXT_TITRE_MAIL . "&nbsp;" . '<font color="#0000ff">' . $this->title . '</font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . tep_draw_separator('pixel_trans.gif', '20', '1') . TEXT_TITRE_VIEW . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .												
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . $this->header . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . $this->contenta . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . $this->unsubscribea . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . $this->unsubscribeb . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .												
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td align="right"><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm_send') . '">' . tep_image_button('button_send.gif', IMAGE_SEND) . '</a> <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '</table>';
// ################# END - Contribution Newsletter v050 ##############

      return $confirm_string;
    }
// ################# Contribution Newsletter v050 ##############
    function send($newsletter_id) {
      $mail_query = tep_db_query("select subscribers_firstname, subscribers_lastname, subscribers_email_address from " . TABLE_SUBSCRIBERS . " where customers_newsletter = '1' and subscribers_blacklist = '0' ");
      while ($mail = tep_db_fetch_array($mail_query)) {
      $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
      // Pr�paration de l'envoie du mail en HTML 
      $mimemessage->add_html_newsletter($this->header . "\n\n" . $this->contenta  . "\n\n" . $this->unsubscribea . " " . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_NEWSLETTERS_UNSUBSCRIBE . "?action=view&email=" . $mail['subscribers_email_address']  . '">'  . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_NEWSLETTERS_UNSUBSCRIBE . "?action=view&email=" . $mail['subscribers_email_address']  . '</a>' . "\n\n" . $this->unsubscribeb);

      $mimemessage->build_message();

// ################# END - Contribution Newsletter v050 ##############

      $mimemessage->send('', $mail['subscribers_email_address'], '', EMAIL_FROM, $this->title);
      }

      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");
    }
  }
?>
