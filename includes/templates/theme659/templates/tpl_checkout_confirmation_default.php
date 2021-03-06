<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=checkout_confirmation.<br />
 * Displays final checkout details, cart, payment and shipping info details.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_checkout_confirmation_default.php 6247 2007-04-21 21:34:47Z wilt $
 */
?>
<div class="centerColumn" id="checkoutConfirmDefault">

<h1 id="checkoutConfirmDefaultHeading"><?php echo HEADING_TITLE; ?></h1>

<?php if ($messageStack->size('redemptions') > 0) echo $messageStack->output('redemptions'); ?>
<?php if ($messageStack->size('checkout_confirmation') > 0) echo $messageStack->output('checkout_confirmation'); ?>
<?php if ($messageStack->size('checkout') > 0) echo $messageStack->output('checkout'); ?>

<div id="checkoutBillto" style="display:none">
<h2 id="checkoutConfirmDefaultBillingAddress"><?php echo HEADING_BILLING_ADDRESS; ?></h2>
<?php if (!$flagDisablePaymentAddressChange) { ?>
<div class="buttonRow forward"><?php echo '<a href="' . zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_EDIT_SMALL, BUTTON_EDIT_SMALL_ALT) . '</a>'; ?></div>
<?php } ?>

<address><?php echo zen_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?></address>

<?php
  $class =& $_SESSION['payment'];
?>

<h3 id="checkoutConfirmDefaultPayment"><?php echo HEADING_PAYMENT_METHOD; ?></h3> 
<h4 id="checkoutConfirmDefaultPaymentTitle"><?php echo $GLOBALS[$class]->title; ?></h4>

<?php
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
?>
<div class="important"><?php echo $confirmation['title']; ?></div>
<?php
    }
?>
<div class="important">
<?php
      for ($i=0, $n=is_array($confirmation['fields'])?sizeof($confirmation['fields']):0; $i<$n; $i++) {
?>
<div class="back"><?php echo $confirmation['fields'][$i]['title']; ?></div>
<div ><?php echo $confirmation['fields'][$i]['field']; ?></div>
<?php
     }
?>
      </div>
<?php
  }
?>

<br class="clearBoth" />
</div>

<?php
  if ($_SESSION['sendto'] != false) {
?>
<div id="checkoutShipto">
<h2 id="checkoutConfirmDefaultShippingAddress"><?php echo HEADING_DELIVERY_ADDRESS; ?></h2>
<div class="buttonRow forward" style="display:none"><?php echo '<a href="' . $editShippingButtonLink . '">' . zen_image_button(BUTTON_IMAGE_EDIT_SMALL, BUTTON_EDIT_SMALL_ALT) . '</a>'; ?></div>

<address><?php echo zen_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?></address>

<?php
    if ($order->info['shipping_method']) {
?>
<h3 id="checkoutConfirmDefaultShipment"><?php echo HEADING_SHIPPING_METHOD; ?></h3>
<h4 id="checkoutConfirmDefaultShipmentTitle"><?php echo $order->info['shipping_method']; ?></h4>

<?php
    }
?>
</div>
<?php
  }
?>
<br class="clearBoth" />
<hr />

<?php
//print_r($order->info);
 if ($order->info['comments']) {
?>
   <h2 id="checkoutConfirmDefaultHeadingComments"><?php echo HEADING_ORDER_COMMENTS; ?></h2>
   <!---<div class="buttonRow forward"><?php echo  '<a href="' . zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_EDIT_SMALL, BUTTON_EDIT_SMALL_ALT) . '</a>'; ?></div> -->
   <div><?php echo (empty($order->info['comments']) ? NO_COMMENTS_TEXT : nl2br(zen_output_string_protected($order->info['comments'])) . zen_draw_hidden_field('comments', $order->info['comments'])); ?></div>
   <br class="clearBoth" />
<?php
  }
?>
<hr />

<h2 id="checkoutConfirmDefaultHeadingCart"><?php echo HEADING_PRODUCTS; ?></h2>

<div class="buttonRow forward" style="display:none"><?php echo '<a href="' . zen_href_link(FILENAME_SHOPPING_CART, '', 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_EDIT_SMALL, BUTTON_EDIT_SMALL_ALT) . '</a>'; ?></div>
<br class="clearBoth" />

<?php  if ($flagAnyOutOfStock) { ?>
<?php    if (STOCK_ALLOW_CHECKOUT == 'true') {  ?>
<div class="messageStackError"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></div>
<?php    } else { ?>
<div class="messageStackError"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></div>
<?php    } //endif STOCK_ALLOW_CHECKOUT ?>
<?php  } //endif flagAnyOutOfStock ?>


      <table border="0" width="100%" cellspacing="0" cellpadding="0" id="cartContentsDisplay">
        <tr class="cartTableHeading">
        <th scope="col" id="ccTypeHeading" width="10" class="showBorder"><?php echo TABLE_HEADING_TERMS; ?></th>
        <th scope="col" id="ccProductsHeading" class="showBorder"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
        <th scope="col" id="ccQuantityHeading" width="10" class="showBorder"><?php echo TABLE_HEADING_QUANTITY; ?></th>
        <th scope="col" id="ccProductsHeading" width="5" class="showBorder"><?php echo ($order->products[0]['payment_plan'] && ($order->products[0]['products_type'] != WAP_TYPE_ID)) ? TABLE_HEADING_ANNUAL_COST : TABLE_HEADING_ITEM_COST;  ?></th>
        <th scope="col" id="ccTotalHeading" class="showBorder"><?php echo ($order->products[0]['payment_plan'] && ($order->products[0]['products_type'] != WAP_TYPE_ID)) ? TABLE_HEADING_TOTAL_COST : TABLE_HEADING_TOTAL; ?></th>
        </tr>
<?php
    // bubble WAP config to the bottom of the list
    $leaveInOrder = [];
    $pushToEnd = [];
    foreach ($order->products as $product) {
      if( in_array($product["id"], [WAP_CONFIG_SERVICE,WAP_CLOUD_MANAGEMENT]) ) {
        $pushToEnd[] = $product;
      } else {
        $leaveInOrder[] = $product;
      }
    }
    $order->products = array_merge($leaveInOrder, $pushToEnd);
?>
<?php // now loop thru all products to display quantity and price ?>
<?php for ($i=0, $n=sizeof($order->products); $i<$n; $i++) { ?>
        <tr class="<?php echo $order->products[$i]['rowClass']; ?>">
          <td  class="cartProductDisplay showBorder"><a href="<?php echo $order->products[$i]['terms_link']; ?>"><?php echo $order->products[$i]['type_name']; ?></a></td>
          <td class="cartProductDisplay showBorder"><?php echo $order->products[$i]['name']; ?>
          <?php  echo $stock_check[$i]; ?>

<?php // if there are attributes, loop thru them and display one per line
    if (isset($order->products[$i]['attributes']) && sizeof($order->products[$i]['attributes']) > 0 ) {
    echo '<ul class="cartAttribsList">';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
?>
      <li><?php echo $order->products[$i]['attributes'][$j]['option'] . ': ' . nl2br(zen_output_string_protected($order->products[$i]['attributes'][$j]['value'])); ?></li>
<?php
      } // end loop
      echo '</ul>';
    } // endif attribute-info
    $wapConfig = in_array($order->products[$i]["id"], [WAP_CONFIG_SERVICE,WAP_CLOUD_MANAGEMENT]);
?>
        </td>
        <td class="cartQuantity showBorder"><?php echo $order->products[$i]['qty']; ?></td>  
        <td class="cartProductDisplay showBorder"><?php echo $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], 1); ?>
        <td class="cartTotalDisplay showBorder">
<?php 
  // show the payment plan
  if( $order->products[$i]['payment_plan'] ) {  
    $tokens = explode("[[[", $order->products[$i]['payment_plan']);
    echo (($order->products[$i]["id"] == WAP_INSTALL) ? ("Base Price: " . $currencies->display_price(WAP_INSTALL_BASE_PRICE, 0, 1) . "<br>") : "") . $tokens[0];
    $total = $order->products[$i]['final_price'] * $order->products[$i]['qty'] + (($order->products[$i]["id"] == WAP_INSTALL) ? WAP_INSTALL_BASE_PRICE : 0);
    $eratable = $order->products[$i]['erate_eligible'] * $order->products[$i]['qty'] + (($order->products[$i]["id"] == WAP_INSTALL) ? WAP_INSTALL_BASE_PRICE : 0);
    for( $j=1; $j<count($tokens); $j++ ) {
      $tokenSplit = explode("]]]", $tokens[$j]);
      if( is_numeric($tokenSplit[0]) ) {
        echo $currencies->display_price($order->products[$i]['final_price'] * $tokenSplit[0], $order->products[$i]['tax'], $order->products[$i]['qty']) . ((count($tokenSplit) > 1)? $tokenSplit[1] : "");
      } else if( $tokenSplit[0] == "p" ) {
        echo $currencies->display_price($total, 0, 1) . ((count($tokenSplit) > 1) ? $tokenSplit[1] : "");
      } else if( $tokenSplit[0] == "el" ) {
        echo $currencies->display_price($eratable, 0, 1) . ((count($tokenSplit) > 1) ? $tokenSplit[1] : "");
      } else if( $tokenSplit[0] == "er" ) {
        $thisVal = $eratable * $_SESSION["selected_erate_discount"];
        echo $currencies->display_price($thisVal, 0, 1) . ((count($tokenSplit) > 1) ? $tokenSplit[1] : "");
      } else if( $tokenSplit[0] == "ap" ) {
        $thisVal = $total - $eratable * $_SESSION["selected_erate_discount"];
        echo $currencies->display_price($thisVal, 0, 1) . ((count($tokenSplit) > 1) ? $tokenSplit[1] : "");
      } else if( $tokenSplit[0] == "r" ) {
        echo (100 * $_SESSION["selected_erate_discount"]) . "%" . ((count($tokenSplit) > 1) ? $tokenSplit[1] : "");
      } else {
        echo $tokenSplit[0] . ((count($tokenSplit) > 1) ? $tokenSplit[1] : "");
      }
    }
    if( $wapConfig ) {
      $thisVal = ($order->products[$i]['final_price'] - ($order->products[$i]['erate_eligible'] * $_SESSION["selected_erate_discount"])) * $order->products[$i]['qty'];
      echo "<br>eiNetwork pays: " . $currencies->display_price($thisVal, 0, 1) . "<br>Due up front by library: " . $currencies->display_price(0, 0, 1);
    }
  // show the single payment
  } else {
    echo $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']);
    if ($order->products[$i]['onetime_charges'] != 0 )
      echo '<br /> ' . $currencies->display_price($order->products[$i]['onetime_charges'], $order->products[$i]['tax'], 1);
  }
?>
        </td>
      </tr>
<?php  }  // end for loopthru all products ?>
      </table>
      <hr />

<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_totals = $order_total_modules->process();
?>
<div id="orderTotals"><?php $order_total_modules->output(); ?></div>
<?php
  }
?>

<?php
  echo zen_draw_form('checkout_confirmation', $form_action_url . "&products_type=" . $_REQUEST["products_type"], 'post', 'id="checkout_confirmation" onsubmit="submitonce();"');

  if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button();
  }
?>
<div class="buttonRow forward"><?php echo zen_image_submit(BUTTON_IMAGE_CONFIRM_ORDER, BUTTON_CONFIRM_ORDER_ALT, 'name="btn_submit" id="btn_submit"') ;?></div>
</form>
<div class="buttonRow back"><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '<br />' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></div>

</div>
