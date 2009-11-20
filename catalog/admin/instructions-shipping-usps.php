<html>
<head>
<title>Instructions - USPS Real Time Shipping</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="includes/stylesheet.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="450" border="0" cellspacing="0" cellpadding="4">
  <tr> 
    <td width="443" align="left" valign="top" bgcolor="efefef" class="pageHeading">USPS  
      Real Time Shipping Module:</td>
  </tr>
  <tr> 
    <td align="left" valign="top" class="main">USPS  Methods   
      for OSCommerce 2.2 MS2<br>
      AUTHOR: Brad Waite (brad@wcubed.net)<br>
      <p>VERSION: 2.72</p></td>
  </tr>
  <tr> 
    <td align="left" valign="top" bgcolor="efefef" class="main"><strong>OVERVIEW:</strong></td>
  </tr>
  <tr> 
    <td align="left" valign="top" class="main"> <p> This module connects directly 
        to the USPS website to extract real time live shipping quotes, while allowing you to select which shipping methods you wish to offer your customers. This requires you to have registered for a free USPS account. </p>
      <p> <strong>To sign up for an account, please follow these steps:</strong></p>
      <p>1. Go to <a href="http://www.uspsprioritymail.com/et_regcert.html" target="_blank" class="splitPageLink">http://www.uspsprioritymail.com/et_regcert.html</a> and complete registration. <br>
        <br>
          You will receive an email confirmation with your ID and password, but only for<br>
      TESTING. </p>
      <p>2. Reply to the message indicating you want to go live. Say something<br>
        like this:</p>
      <p> &quot;Please enable for production immediately, we are operating on a platform<br>
  that has already tested the API.&quot;</p>
      <p>Then, usually within 24 hours, USPS sends you a message like this:</p>
      <p> &quot;Congratulations on completing your testing using the U.S. Postal Service's<br>
  Internet Shipping Application Program Interfaces (APIs). Your profile has<br>
  been updated to allow you access to the Production Server.&quot;</p>
      <p>After you receive this confirmation you are ready to install USPSMethods.</p>
      <p>3. Click on the INSTALL button to install the USPS Methods module.</p>
      <p>4. Click the EDIT button and follow the configuration instructions below.<br>
      </p>
      </td>
  </tr>
  <tr> 
    <td align="left" valign="top" bgcolor="efefef" class="main"><strong>CONFIGURATION 
      OPTIONS:</strong></td>
  </tr>
  <tr> 
    <td align="left" valign="top" class="main"><p><strong>1. <B>Account Login: </B></strong>Enter your USPS User ID and password.</p>
      <p><strong>2. <B>Server</B>:</strong> Set your server option to production. </p>
      <p><strong>3. <B>Handling Fee</B>:</strong> If you would like to charge a handling fee, enter it here without the $ sign. Example: 5.00</p>
      <p><strong>4. </strong><B>Shipping Zone</B>: Select the zone you want to offer UPS shipping to. To allow this method for everyone, leave the default.</p>
      <p><strong>5. <B>USPS Options</B>:</strong> Check the options available to display weight and transit time to your customers. </p>
      <p><strong>6. <B>Shipping Methods</B>: </strong>Check the shipping options you wish to offer your customers. </p>
      <p><strong>7. Update: </strong>Click the UPDATE button and you are done! </p>
      <p>NOTE: Any options not mentioned above are optional. </p></td>
  </tr>
  <tr> 
    <td align="left" valign="top" bgcolor="EFEFEF" class="main"><strong>BUGS / TROUBLESHOOTING:</strong></td>
  </tr>
  <tr> 
    <td align="left" valign="top" class="main"><p> <strong>NOTE: </strong>SELECTING ANY GIVEN SHIPPING METHOD DOES NOT ENSURE IT WILL BE
AVAILABLE TO YOUR CUSTOMERS. USPS RETURNS A SET OF POSSIBLE SHIPPING METHODS
DEPENDING ON POINT OF ORIGIN AND DESTINATION, AND THIS CONTRIBUTION MERELY
FILTERS THEM SO THAT NON-SELECTED METHODS ARE NOT PRESENTED TO THE CUSTOMER<br>
AS CHOICES. </p>
      <p>IF YOU DO NOT ENABLE ENOUGH SHIPPING METHODS, IT IS QUITE
  POSSIBLE A CUSTOMER WILL NOT BE ABLE TO COMPLETE HIS/HER ORDER. ENABLE AS
  MANY METHODS AS MAKE SENSE BASED ON THE NATURE OF YOUR STORE'S MERCHANDISE,
  AND THE DESTINATIONS YOU SEEK TO ACCOMODATE.</p>
      <p>Make sure you have set your store's country and postal code 
    in admin -&gt; Configuration -&gt; Shipping/Packaging, as these are used in 
    the rate quote request and if not valid will cause an error.</p>
      <p>To research persistent errors or to satisfy raw curiosity, you can enable an embedded hook in the catalog/includes/modules/shipping/usps.php script that
    sends an email containing the rate quote response from USPS. </p>
      <p>The email text
    will be in XML format, but careful examination will reveal useful details.
    Find the commented statement at line 270, insert your correct email address
    in place of &quot;you@yourdomain.com&quot; in both places it occurs, and remove<br>
    the leading &quot;//&quot; characters that make the line a comment. </p>
      <p>Restore the comment
        characters after you have tested using this hook. If you don't receive any
        email after testing with this hook, that may indicate a failure in http socket
        handling on your server.</p></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
