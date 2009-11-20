<?php
/*
  $Id: box_invoice.php,v 5.5 2005/05/15 00:37:30 PopTheTop Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class objectInfo {

// class constructor
    function objectInfo($object_array) {
      reset($object_array);
      while (list($key, $value) = each($object_array)) {
        $this->$key = tep_db_prepare_input($value);
      }
    }
  }

?>
<html>
<head>
<title><?php echo STORE_NAME; ?> </title>
<link rel="stylesheet" type="text/css" href="<?php echo $ei_css_path; ?>stylesheet.css">
</head>
<body bgcolor="#FFFFFF">

<table width="600"><tr><td height="6" colspan="2"> </td>
	</tr><tr>
	<td width="4"> </td>
	<td>

<!-- START Top Header -->
<table width=100% bgcolor="#FFFFFF" cellpadding="5" style="border-collapse: collapse" bordercolor="#FFFFFF" cellspacing="0" border="0">
	<tr>
		<td width="100%">
			<table width="100%">
				<tr>
					<td align="left"><?php echo tep_image(DIR_WS_IMAGES . 'yourlogo.gif', 'osCommerce', '205', '50'); ?></td>
					<td ALIGN="right" VALIGN="top" NOWRAP>
					<FONT FACE="Arial" SIZE="3" COLOR="#000000"><b>
					<?php echo nl2br(STORE_NAME_ADDRESS); ?></b></font>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<!-- END Top Header -->

<!-- START INVOICE -->
<table width=100% bgcolor="#C9C9C9" cellpadding="2">
  <tr class="dataTableHeadingRow">
    <td>&nbsp;<font face="arial, helvetica" size="2" color="#0000000"><i>INVOICE: <?php echo $oID; ?></i></font></td>
     <td align="right">&nbsp;<font face="arial, helvetica" size="2" color="#0000000"><?php echo $date; ?></td>
  </tr>
</table>
<!-- END INVOICE -->

<table width="100%" border="0" cellpadding="5" bordercolor="#FFFFFF" bgcolor="#FFFFFF" style="border-collapse: collapse">
  <tr>
    <td width="50%" valign="top">
<!-- START Billing Info -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main"><tr>
<td align="left" valign="top"><FONT FACE="Arial" SIZE="2" COLOR="#000000"><b><?php echo ENTRY_SOLD_TO; ?></b></font></td>
	</tr><tr>
<td NOWRAP>&nbsp;&nbsp;&nbsp;&nbsp;<FONT FACE="Arial" SIZE="2" COLOR="#000000"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>&nbsp;&nbsp;&nbsp;&nbsp;'); ?></font></td>
	</tr><tr>
<td>&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo $ei_image_dir; ?>pixel_trans.gif" width="1" height="10" alt=""></td>
	</tr><tr>
<td NOWRAP>&nbsp;&nbsp;&nbsp;&nbsp;<FONT FACE="Arial" SIZE="2" COLOR="#000000"><?php echo $order->customer['telephone']; ?></font></td>
	</tr><tr>
<td NOWRAP>&nbsp;&nbsp;&nbsp;&nbsp;<FONT FACE="Arial" SIZE="2" COLOR="#000000"><?php echo $order->customer['email_address']; ?></font></td>
	</tr><tr>
	  <td><img src="<?php echo $ei_image_dir; ?>pixel_trans.gif" width="1" height="7" alt=""></td>
</tr></table>
<!-- END Billing Info -->
    </td><td width="50%" valign="top">
<!-- START Shipping Info -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="main"><tr>
<td align="left" valign="top"><FONT FACE="Arial" SIZE="2" COLOR="#000000"><b><?php echo ENTRY_SHIP_TO; ?></b></font></td>
	</tr><tr>
<td NOWRAP>&nbsp;&nbsp;&nbsp;&nbsp;<FONT FACE="Arial" SIZE="2" COLOR="#000000"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>&nbsp;&nbsp;&nbsp;&nbsp;'); ?></font></td>
	</tr><tr>
<td>&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo $ei_image_dir; ?>pixel_trans.gif" width="1" height="10" alt=""></td>
	</tr><tr>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr><tr>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr><tr>
	  <td><img src="<?php echo $ei_image_dir; ?>pixel_trans.gif" width="1" height="7" alt=""></td>
</tr></table>
<!-- END Shipping Info -->
		</td>
	</tr>
</table>

<!-- START Product Info -->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr class="dataTableHeadingRow">
		<td class="dataTableHeadingContent" colspan="2">&nbsp;<font color="#000000"><?php echo TABLE_HEADING_PRODUCTS; ?></font></td>
		<td class="dataTableHeadingContent"><font color="#000000"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></font></td>
		<td class="dataTableHeadingContent" align="right"><font color="#000000"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></font></td>
		<td ALIGN="right" CLASS="dataTableHeadingContent"><font color="#000000"><?php echo TABLE_HEADING_TOTAL; ?></font>&nbsp;</td>
	</tr>
<?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
		echo '	<tr class="dataTableRow">' . "\n" .
		     '		<td class="dataTableContent" valign="top" align="right"><font size=2 face="arial, helvetica" color="#000000">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
		     '		<td class="dataTableContent" valign="top" NOWRAP><font size=2 face="arial, helvetica" color="#000000">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
        for ($j = 0; $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '		</td>' . "\n" .
           '		<td class="dataTableContent" valign="top"><font size=2 face="arial, helvetica" color="#000000">' . $order->products[$i]['model'] . '</td>' . "\n";
      echo '		<td class="dataTableContent" align="right" valign="top"><font size=2 face="arial, helvetica" color="#000000">' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
           '		<td class="dataTableContent" align="right" valign="top"><font size=2 face="arial, helvetica" color="#000000"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '&nbsp;</b></td>' . "\n";
      echo '	</tr>' . "\n";
    }
?>
	<tr>
		<td align="right" colspan="5">
		<table border="0" cellspacing="0" cellpadding="2">
<?php
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '			<tr>' . "\n" .
         '				<td align="right" class="smallText"><font size=2 face="arial, helvetica" color="#000000">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '				<td align="right" class="smallText"><font size=2 face="arial, helvetica" color="#000000">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '			</tr>' . "\n";
  }
?>
		</table>
		</td>
	</tr>
</table>
<!-- END Product Info -->
<!-- START Customer Thank You and Order Link -->
<table width="100%" border="0" cellpadding="5" bordercolor="#FFFFFF" bgcolor="#FFFFFF" style="border-collapse: collapse">
	<tr>
		<td colspan="2" align="center"><br><font size=2 face="arial, helvetica" color="#000000">Thank you for purchasing from <?php echo STORE_NAME; ?><br>Please print this invoice for your records</font><br><br></td>
	</tr>
<!-- END Customer Thank You and Order Link -->
</table>

		</td>
	</tr>
</table>
</body>
</html>