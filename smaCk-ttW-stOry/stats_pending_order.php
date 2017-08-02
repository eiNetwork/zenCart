<?php
/**
 * @package admin
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Author: DrByte  Sat Oct 17 21:23:07 2015 -0400 Modified in v1.5.5 $
 */

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>

<?php
?>

<?php
  // export to CSV

  if(isset($_GET["regenerateCSV"])) {

   //open the csv file
   //$csvFile = fopen("/home/einet/public_html/intranet/librarySystemOrders.csv", "w");
  $csvFile = fopen("../../pendingOrders.csv", "w");
   
   //define the query
   $sql_query_raw = "SELECT * from pendingOrders"; 
     
   //Write the heading lines to the spreadsheet
   $headingline1 = "Pending Orders Older than 7 Days";
   fwrite($csvFile, $headingline1 . "\n\n");

   $headingline2 = "\"Library System\",\"Location\",\"Order Nbr\",\"Ordered By\",\"Email Address\",\"Order Date\",\"Order Status\",\"Category\",\"Product Model\",\"Configuration Description\",\"Vendor Config Id\",\"Quantity\",\"Unit Price\",\"Total Price\"";
   fwrite($csvFile, $headingline2 . "\n");
   
   //run the query and get the data for the report
   $libsys = $db->Execute($sql_query_raw);
   while (!$libsys->EOF) {
       $line = "\"" . $libsys->fields['Library System'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Location'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Order Nbr'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Ordered By'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Email Address'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Order Date'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Order Status'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Category'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Product Model'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Configuration Description'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Config Id'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Quantity'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Unit Price'] . "\"";
       $line = $line . ",". "\"" . $libsys->fields['Total Price'] . "\"";
       fwrite($csvFile, $line . "\n");
       $libsys->MoveNext();
    }

    fclose($csvFile);

    echo "<script type='text/javascript'>window.location.href=\"/pendingOrders.csv?cachebuster=".time()."\";</script>";

  }
?>
</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<form method="get" id="generateCSVForm" target="_blank" action="#">
  <input type="hidden" name="regenerateCSV" value="true">
</form>

<button onclick="document.getElementById('generateCSVForm').submit(); return false;">Export to CSV</button><br><br>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>

<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      </tr>
    </table>
        </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="left"; width="13%"><?php echo TABLE_HEADING_LIBRARY; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="10%"><?php echo TABLE_HEADING_LOCATION; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="5%"><?php echo TABLE_HEADING_ORDERED_BY; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="5%"><?php echo TABLE_HEADING_EMAIL_ADDRESS; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="5%"><?php echo TABLE_HEADING_ORDER_NUMBER; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="left"; width="6%"><?php echo TABLE_HEADING_ORDER_DATE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="left"; width="5%"><?php echo TABLE_HEADING_ORDER_STATUS; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="left"; width="6%"><?php echo TABLE_HEADING_CATEGORY; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="left"; width="6%"><?php echo TABLE_HEADING_MODEL; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="left"; width="19%"><?php echo TABLE_HEADING_CONFIG_DESC; ?>&nbsp;</td>                
                <td class="dataTableHeadingContent" align="right"; width="5%"><?php echo TABLE_HEADING_CONFIG_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"; width="5%"><?php echo TABLE_HEADING_QTY; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"; width="5%"><?php echo TABLE_HEADING_UNIT_PRICE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"; width="5%"><?php echo TABLE_HEADING_TOTAL_PRICE; ?>&nbsp;</td>
              </tr>
<?php
  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS_REPORTS - MAX_DISPLAY_SEARCH_RESULTS_REPORTS;

  $rows=0;

   //define the query
   $sql_query_raw = "SELECT * from pendingOrders";
   
//echo $sql_query_raw . "<br>";
  $libsys = $db->Execute($sql_query_raw);  
  $libsys_query_numrows = $libsys->RecordCount();
  $libsys_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $sql_query_raw, $libsys_query_numrows);

  while (!$libsys->EOF) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow">
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Library System']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Location']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Ordered By']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Email Address']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Order Nbr']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Order Date']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Order Status']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Category']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Product Model']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Configuration Description']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $libsys->fields['Config Id']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo $libsys->fields['Quantity']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo number_format($libsys->fields['Unit Price'],2); ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo number_format($libsys->fields['Total Price'],2,".",","); ?>&nbsp;&nbsp;</td>
              </tr>
<?php
    $libsys->MoveNext();
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $libsys_split->display_count($libsys_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $libsys_split->display_links($libsys_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
