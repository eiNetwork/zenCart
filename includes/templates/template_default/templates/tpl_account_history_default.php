<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=account_history.<br />
 * Displays all customers previous orders
 *
 * @package templateSystem
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Author: DrByte  Sat Jan 9 13:13:41 2016 -0500 Modified in v1.5.5 $
 */
?>
<div class="centerColumn" id="accountHistoryDefault">

<h1 id="accountHistoryDefaultHeading"><?php echo HEADING_TITLE; ?><a href="#" target="_blank"><button style="float:right;margin-right:20px;padding:2px 10px" onclick="$(this).parent().attr('href', window.location.href + '&regenerateCSV=true');">Export to CSV</button></a></h1>

<?php
  if ($accountHasHistory === true) {
    $prevID = "";
    foreach ($accountHistory as $history) {
      $orderYear = (substr($history['date_purchased'], 0, 7) > "2016-11") ? 2017 : 2014;
      if( $_SESSION["selectedCartID"] == MASTER_CART && $prevID != $history['address_book_id'] ) {
        if( $prevID != "" ) {
          echo "</fieldset>";
        }
?>
<fieldset style="background:#f8f8f8">
<legend><?php echo $history['entry_company'] . " Orders"; ?></legend>
<?
      }
?>
<fieldset style="background:#fff">
<legend><?php echo TEXT_ORDER_NUMBER . $history['orders_id']; ?></legend>
<div class="notice forward"><?php echo "Confirmed by: " . $history['customers_name']; ?></div>
<br class="clearBoth" />
    <div class="content back" style="margin-right:50px"><?php echo '<strong>' . TEXT_ORDER_DATE . '</strong> ' . zen_date_long($history['date_purchased']) . '<br />'; ?></div>
    <div class="content back" style="margin-right:50px"><?php echo '<strong>' . TEXT_ORDER_STATUS . '</strong> ' . $history['orders_status_name'] . '<br />'; ?></div>
    <div class="content">
<?php
      echo '<strong>' . TEXT_ORDER_PRODUCTS . '</strong> ' . $history['product_count'] . '<br />';
      if( strpos($history['order_title'], "Shipping") !== false ) {
        echo '<strong>' . TEXT_ORDER_COST . '</strong> ' . strip_tags(substr($history['order_total'], strrpos($history['order_total'], "<br />", -7))); 
      } else {
        echo ((strpos($history['order_total'], "<br />") !== false)
          ? ("<table style='display:inline-table'><tr><td><strong>" . $orderYear . ":</strong></td><td>" . $currencies->display_price($history['order_value'], 0, 0.5) . "</td></tr>" . 
             "<tr><td><strong>" . ($orderYear + 1) . ":</strong></td><td>" . $currencies->display_price($history['order_value'], 0, 1) . "</td></tr>" . 
             "<tr><td><strong>" . ($orderYear + 2) . ":</strong></td><td>" . $currencies->display_price($history['order_value'], 0, 1) . "</td></tr>" . 
             "<tr><td><strong>" . ($orderYear + 3) . ":</strong></td><td>" . $currencies->display_price($history['order_value'], 0, 0.5) . "</td></tr>" . 
             "<tr><td><strong>Total:</strong></td><td>" . $currencies->display_price($history['order_value'], 0, 3) . "</td></tr>" . 
             "</table>")
          : ('<strong>' . TEXT_ORDER_COST . '</strong> ' . strip_tags($history['order_total']))); 
      }
?></div>
    <div class="content forward"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'order_id=' . $history['orders_id'], 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_VIEW_SMALL, BUTTON_VIEW_SMALL_ALT) . '</a>'; ?></div>
<br class="clearBoth" />
</fieldset>
<?php
      $prevID = $history['address_book_id'];
    }
    if( $_SESSION["selectedCartID"] == MASTER_CART && $prevID != "" ) {
      echo "</fieldset>";
    }
?>
<div class="navSplitPagesLinks forward"><?php echo TEXT_RESULT_PAGE . $history_split->display_links($max_display_page_links, zen_get_all_get_params(array('page', 'info', 'x', 'y', 'main_page')), $paginateAsUL); ?></div>
<div class="navSplitPagesResult"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></div>
<?php
  } else {
?>
<div class="centerColumn" id="noAcctHistoryDefault">
<?php echo TEXT_NO_PURCHASES; ?>
</div>
<?php
  }
?>

<div style="display:none" class="buttonRow forward"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>

</div>