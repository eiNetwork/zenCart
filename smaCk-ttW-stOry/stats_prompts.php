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
  $_GET['library_system_name'] = (!isset($_GET['library_system_name']) ? '0' : $_GET['library_system_name']);
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

</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php

  //populate the library sytem drop down
  $library_system_query = "select distinct `Library System` from librarySystemOrders";
  $library_systems_select = $db->Execute($library_system_query);
  $library_dropdown = array();
  //$library_dropdown[]=array('id' => '0', 'text' => 'Carnegie Library of Pittsburgh');
  while (!$library_systems_select->EOF) {
    $library_dropdown[] = array('id' => $library_systems_select->fields['Library System'],'text' => $library_systems_select->fields['Library System']);
    $library_systems_select->MoveNext();
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
<table border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td class="main">
        <?php
         echo zen_draw_form('new_date', FILENAME_STATS_TEST, '', 'get') . 
         'Library System&nbsp;&nbsp;' . 
         zen_draw_pull_down_menu('library_system_name', $library_dropdown, $_GET['library_system_name'], 'onChange="this.form.submit();"') .
         '&nbsp;&nbsp; Order Status &nbsp;&nbsp; ' .
         zen_draw_pull_down_menu('order_status', $order_status_dropdown, $_GET['order_status'], 'onChange="this.form.submit();"') .
         zen_hide_session_id() .
         zen_draw_hidden_field('action', 'new_date') .
         zen_draw_hidden_field('start_date', $_GET['start_date']) .
         zen_draw_hidden_field('end_date', $_GET['end_date']);
         ?>'
         &nbsp;&nbsp;</form>
        </td>
      </tr>
   </tr>
   <tr>
       <table border="0" width="100%" cellspacing="2" cellpadding="2">
       <tr><?php echo zen_draw_form('search', FILENAME_STATS_TEST, '', 'get'); 
         echo zen_draw_hidden_field('libraryselect', $_GET['library_system_name']);
         echo zen_draw_hidden_field('statusselect', $_GET['order_status']); ?>
         <td class="main" align="right">Start Date<?php echo ' ' . zen_draw_input_field('start_date', $_GET['start_date']); ?></td>
         <td class="main" align="right">End Date<?php echo ' ' . zen_draw_input_field('end_date', $_GET['end_date']) . zen_hide_session_id(); ?></td>
         <td class="main" align="right"><?php echo zen_image_submit('button_display.gif', IMAGE_DISPLAY); ?></td>
        </tr>
        </form></table>
   </tr>
</table>

<p>Report for  <?php echo htmlspecialchars($_GET['library_system_name']); ?></p>
<p>Order Status  <?php echo htmlspecialchars($_GET['order_status']); ?></p>
<p>Start Date <?php echo htmlspecialchars($_GET['start_date']); ?></p>
<p>End Date <?php echo htmlspecialchars($_GET['end_date']); ?></p>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
