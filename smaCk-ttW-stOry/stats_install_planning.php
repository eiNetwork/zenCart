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
  // export to CSV

  if(isset($_POST["regenerateCSV"])) {

   //open the csv file
   $csvFile = fopen("/home/einet/public_html/intranet/installPlanning.csv", "w");

   //define the query
   $sql_query_raw = "SELECT * from installPlanning";

   //Write the heading line to the spreadsheet
   $headingline = "\"Library Location\",\"Loc Code\",\"Category\",\"Configuration Description\",\"Qty Ordered\",\"Make\",\"Model\",\"Vendor Config Id\",\"Asset Code\"";
   fwrite($csvFile, $headingline . "\n");

   //run the query and get the data for the report
   $install = $db->Execute($sql_query_raw);
   while (!$install->EOF) {
       $line = "\"" . $install->fields['Location'] . "\"";
       $line = $line . ",". "\"" . $install->fields['Location Code'] . "\"";
       $line = $line . ",". "\"" . $install->fields['Category'] . "\"";
       $line = $line . ",". "\"" . $install->fields['Configuration Description'] . "\"";
       $line = $line . ",". "\"" . $install->fields['Quantity Ordered'] . "\"";
       $line = $line . ",". "\"" . $install->fields['Make'] . "\"";
       $line = $line . ",". "\"" . $install->fields['Model'] . "\"";
       $line = $line . ",". "\"" . $install->fields['Config Id'] . "\"";
       $line = $line . ",". "\"" . $install->fields['Assetcode'] . "\"";
       fwrite($csvFile, $line . "\n");
       $install->MoveNext();
    }

    fclose($csvFile);

    echo "<script type='text/javascript'>window.location.href=\"/installPlanning.csv?cachebuster=".time()."\";</script>";

  }
?>

</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<form method="post" id="generateCSVForm" target="_blank" action="#">
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="left"; width="15%"><?php echo TABLE_HEADING_LIBRARY; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="4%"><?php echo TABLE_HEADING_LOCATION; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="10%"><?php echo TABLE_HEADING_CATEGORY; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="35%"><?php echo TABLE_HEADING_CONFIG_DESC; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="5%"><?php echo TABLE_HEADING_QUANTITY; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="8%"><?php echo TABLE_HEADING_MAKE; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="8%"><?php echo TABLE_HEADING_MODEL; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="5%"><?php echo TABLE_HEADING_CONFIG_ID; ?></td>
                <td class="dataTableHeadingContent" align="left"; width="5%"><?php echo TABLE_HEADING_ASSETCODE; ?></td>
              </tr>
<?php
  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS_REPORTS - MAX_DISPLAY_SEARCH_RESULTS_REPORTS;
  $sql_query_raw = "SELECT * from installPlanning";
  //$sql_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $sql_query_raw, $sql_query_numrows);

  $rows = 0;
  $install = $db->Execute($sql_query_raw);
  $sql_query_numrows = $install->RecordCount();
  $sql_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $sql_query_raw, $sql_query_numrows);

  while (!$install->EOF) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow">
                <td class="dataTableContent" align="left"><?php echo $install->fields['Location']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $install->fields['Location Code']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $install->fields['Category']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $install->fields['Configuration Description']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $install->fields['Quantity Ordered']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $install->fields['Make']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $install->fields['Model']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $install->fields['Config Id']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="left"><?php echo $install->fields['Assetcode']; ?>&nbsp;&nbsp;</td>
              </tr>
<?php
    $install->MoveNext();
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $sql_split->display_count($sql_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                <td class="smallText" align="right"><?php echo $sql_split->display_links($sql_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
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
