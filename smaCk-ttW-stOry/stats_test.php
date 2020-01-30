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

  //Set defaults for input variables
  $_GET['start_date'] = (!isset($_GET['start_date']) ? date("m-d-Y",(time())) : $_GET['start_date']);
  $_GET['end_date'] = (!isset($_GET['end_date']) ? date("m-d-Y",(time())) : $_GET['end_date']);
  $_GET['manufacturer'] = (!isset($_GET['manufacturer']) ? 'Hewlett Packard' : $_GET['manufacturer']);
  $_GET['order_staus'] = (!isset($_GET['order_status']) ? 'Pending' : $_GET['order_staus']);

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
  // export to CSV

  if(isset($_GET["regenerateCSV"])) {

   //open the csv file
   $csvFile = fopen("/home/einet/public_html/intranet/vendorOrder.csv", "w");
   
   // reverse date from m-d-y to y-m-d
    $date1 = explode("-", $_GET['start_date']);
    $m1 = $date1[0];
    $d1 = $date1[1];
    $y1 = $date1[2];

    $date2 = explode("-", $_GET['end_date']);
    $m2 = $date2[0];
    $d2 = $date2[1];
    $y2 = $date2[2];

    $sd = $y1 . '-' . $m1 . '-' . $d1 . ' 00:00:00';
    $ed = $y2. '-' . $m2 . '-' . $d2 .  ' 23:59:59';


   //define the query
   $sql_query = "SELECT `Product Type`, `Category`, `Product Model`, `Configuration Description`, `Config Id`, "
   . " sum(`Nbr Orders`) as `Nbr Orders`, "
   . " sum(Quantity) as `Quantity`, "
   . " sum(`Unit Price`) as `Unit Price`, "
   . " sum(`Total Price`) as `Total Price`, "
   . " FROM vendorOrders "
   . " WHERE `Manufacturer` = '" . $_GET['manufacturer'] ."'"
   . " and `Order Status` = '" . $_GET['order_status'] ."'"
   . " and (`Order Date` >= '" . $sd . "'"
   . " and `Order Date` <= '" . $ed . "')"
   . " GROUP BY `Product Type`, `Category`, `Product Model`, `Configuration Description`, `Config Id` "
   . " ORDER BY `Product Type`, `Category`, `Product Model`, `Configuration Description`, `Config Id` ";

   //Write the heading line to the spreadsheet
   $headingline = "\"Product Type\",\"Category\",\"Product Model\",\"Configuration Description\",\"Config Id\",\"Nbr Orders\",\"Qty\",\"Unit Price\",\"Total Price\"";
   fwrite($csvFile, $headingline . "\n");
   
   //run the query and get the data for the report
   $vendors = $db->Execute($sql_query);
   while (!$vendors->EOF) {
       $line = "\"" . $vendors->fields['Product Type'] . "\"";
       $line = $line . ",". "\"" . $vendors->fields['Category'] . "\"";
       $line = $line . ",". "\"" . $vendors->fields['Product Model'] . "\"";
       $line = $line . ",". "\"" . $vendors->fields['Configuration Description'] . "\"";
       $line = $line . ",". "\"" . $vendors->fields['Config Id'] . "\"";
       $line = $line . ",". "\"" . $vendors->fields['Nbr Orders'] . "\"";
       $line = $line . ",". "\"" . $vendors->fields['Quantity'] . "\"";
       $line = $line . ",". "\"" . $vendors->fields['Unit Price'] . "\"";
       $line = $line . ",". "\"" . $vendors->fields['Total Price'] . "\"";
       fwrite($csvFile, $line . "\n");
       $vendors->MoveNext();
    }

    fclose($csvFile);

    echo "<script type='text/javascript'>window.location.href=\"/vendorOrder.csv\";</script>";

  }
?>
</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<?php
  //populate the manufacturer drop down
  $mfg_query = "select distinct `Manufacturer` from vendorOrders";
  $mfg_select = $db->Execute($mfg_query);
  $mfg_dropdown = array();
  //$mfg_dropdown[]=array('id' => '0', 'text' => 'Hewlett Packard');
  while (!$mfg_select->EOF) {
    $mfg_dropdown[] = array('id' => $mfg_select->fields['Manufacturer'],'text' => $mfg_select->fields['Manufacturer']);
    $mfg_select->MoveNext();
  }

  //populate the order status drop down
  $order_status_query = "select distinct `Order Status` from librarySystemOrders";
  $order_status_select = $db->Execute($order_status_query);
  $order_status_dropdown = array();
  //$order_status_dropdown[]=array('id' => '0', 'text' => 'Pending');
  while (!$order_status_select->EOF) {
    $order_status_dropdown[] = array('id' => $order_status_select->fields['Order Status'],'text' => $order_status_select->fields['Order Status']);
    $order_status_select->MoveNext();
  }
?>

<form method="get" id="generateCSVForm" target="_blank" action="#">
  <input type="hidden" name="regenerateCSV" value="true">
  <input type="hidden" name="manufacturer" value="<?php echo $_GET['manufacturer'];?>">
  <input type="hidden" name="order_status" value="<?php echo $_GET['order_status'];?>">
  <input type="hidden" name="start_date" value="<?php echo $_GET['start_date'];?>">
  <input type="hidden" name="end_date" value="<?php echo $_GET['end_date'];?>">
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
      <tr>
        <td><table border="0" cellspacing="2" cellpadding="2">
        <td class="main">
        <?php
         echo zen_draw_form('new_date', FILENAME_STATS_TEST, '', 'get') .
         'Manufacturer&nbsp;&nbsp;' .
         zen_draw_pull_down_menu('manufacturer', $mfg_dropdown, $_GET['manufacturer'], 'onChange="this.form.submit();"') .
         '&nbsp;&nbsp; Order Status &nbsp;&nbsp; ' .
         zen_draw_pull_down_menu('order_status', $order_status_dropdown, $_GET['order_status'], 'onChange="this.form.submit();"') .
         zen_hide_session_id() .
         zen_draw_hidden_field('action', 'new_date') .
         zen_draw_hidden_field('start_date', $_GET['start_date']) .
         zen_draw_hidden_field('end_date', $_GET['end_date']);
         ?>'
         &nbsp;&nbsp;</form></table>
       </td>
      </tr>
   <tr>
       <table border="0" width="100%" cellspacing="2" cellpadding="2">
       <tr><?php echo zen_draw_form('search', FILENAME_STATS_TEST, '', 'get');
         echo zen_draw_hidden_field('mfgselect', $_GET['manufacturer']);
         echo zen_draw_hidden_field('statusselect', $_GET['order_status']); ?>
         <td class="main" align="left">Start Date<?php echo ' ' . zen_draw_input_field('start_date', $_GET['start_date']); ?>
         &nbsp;&nbsp; End Date<?php echo ' ' . zen_draw_input_field('end_date', $_GET['end_date']) . zen_hide_session_id(); ?>
         &nbsp;&nbsp; <?php echo zen_image_submit('button_display.gif', IMAGE_DISPLAY); ?></td>
        </tr>
        </form></table>
   </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="left"; width="14%"><?php echo TABLE_HEADING_PRODUCT_TYPE; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="6%"><?php echo TABLE_HEADING_CATEGORY; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="8%"><?php echo TABLE_HEADING_PRODUCT_MODEL; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="left"; width="14%"><?php echo TABLE_HEADING_CONFIG_DESC; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="left"; width="6%"><?php echo TABLE_HEADING_CONFIG_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"; width="6%"><?php echo TABLE_HEADING_NBR_ORDERS; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"; width="6%"><?php echo TABLE_HEADING_QUANTITY; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"; width="6%"><?php echo TABLE_HEADING_UNIT_PRICE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"; width="6%"><?php echo TABLE_HEADING_TOTAL_PRICE; ?>&nbsp;</td>
              </tr>
<?php
  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS_REPORTS - MAX_DISPLAY_SEARCH_RESULTS_REPORTS;

  // reverse date from m-d-y to y-m-d
    $date1 = explode("-", $_GET['start_date']);
    $m1 = $date1[0];
    $d1 = $date1[1];
    $y1 = $date1[2];

    $date2 = explode("-", $_GET['end_date']);
    $m2 = $date2[0];
    $d2 = $date2[1];
    $y2 = $date2[2];

    $sd = $y1 . '-' . $m1 . '-' . $d1 . ' 00:00:00';
    $ed = $y2. '-' . $m2 . '-' . $d2 .  ' 23:59:59';

  $rows=0;

   $sql_query = "SELECT `Product Type`, `Category`, `Product Model`, `Configuration Description`, `Config Id`, "
   . " sum(`Nbr Orders`) as `Nbr Orders`, "
   . " sum(Quantity) as `Quantity`, "
   . " sum(`Unit Price`) as `Unit Price`, "
   . " sum(`Total Price`) as `Total Price` "
   . " FROM vendorOrders "
   . " WHERE `Manufacturer` = '" . $_GET['manufacturer'] ."'"
   . " and `Order Status` = '" . $_GET['order_status'] ."'"
   . " and (`Order Date` >= '" . $sd . "'"
   . " and `Order Date` <= '" . $ed . "')"
   . " GROUP BY `Product Type`, `Category`, `Product Model`, `Configuration Description`, `Config Id` "
   . " ORDER BY `Product Type`, `Category`, `Product Model`, `Configuration Description`, `Config Id` ";

  $vendors = $db->Execute($sql_query);
  $vendors_query_numrows = $vendors->RecordCount();
  $vendors_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $sql_query, $vendors_query_numrows);
  
  while (!$vendors->EOF) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow">
                <td class="dataTableContent" align="left"><?php echo $vendors->fields['Product Type']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $vendors->fields['Category']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $vendors->fields['Product Model']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $vendors->fields['Configuration Description']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $vendors->fields['Config Id']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo $vendors->fields['Nbr Orders']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo $vendors->fields['Quantity']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo number_format($vendors->fields['Unit Price'],2); ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo number_format($vendors->fields['Total Price'],2,".",","); ?>&nbsp;&nbsp;</td>
              </tr>
<?php
    $vendors->MoveNext();
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $vendor_split->display_count($vendor_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $vendor_split->display_links($vendor_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
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
