<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=shopping_cart.<br />
 * Displays shopping-cart contents
 *
 * @package templateSystem
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_shopping_cart_default.php 15881 2010-04-11 16:32:39Z wilt $
 */
?>
<div class="centerColumn" id="shoppingCartDefault">
<?php
  if ($flagHasCartContents) {
?>

<?php
    if (false && $_SESSION['cart']->count_contents() > 0) {
?>
<div class="help-cart"><?php
      echo TEXT_VISITORS_CART; 
?></div>
<?php
    } //endif (false && $_SESSION['cart']->count_contents() > 0)
?>

<h1 id="cartDefaultHeading"><?php echo HEADING_TITLE; ?><a href="#" target="_blank"><button style="float:right;margin-right:20px;padding:2px 10px" onclick="$(this).parent().attr('href', window.location.href + '&regenerateCSV=true');">Export to CSV</button></a></h1>

<?php
    $cartArray = [];
    if( $_SESSION['selectedCartID'] == MASTER_CART ) {
      $cartArray = $productArray;
    } else {
      $cartArray[] = $productArray;
    }

    $skipBreaks = false;
    foreach( $cartArray as $key => $thisCart ) {
      if( !is_numeric($key) && $key == "multiCart" ) {
        $skipBreaks = true;
        //continue;
        echo "<div style='width:100%;text-align:center'>Click the button above to export all of your carts.</div><br>";
        break;
      }

      if( $_SESSION['selectedCartID'] == MASTER_CART ) {
        $name_query = "select entry_company as name
                       from " . TABLE_ADDRESS_BOOK . " 
                       join " . TABLE_CUSTOMERS_BASKET_NEW . " using (address_book_id) 
                       where customers_basket_new_id = :cart_id";
        $name_query = $db->bindVars($name_query, ':cart_id', $key, 'integer');
        $name = $db->Execute($name_query);

        echo ($skipBreaks ? "" : "<br><br><br>") . "<div style=\"border:2px solid #cdcdcd; padding:10px\"><h1>" . $name->fields['name'] . " Cart</h1><br>";
        $skipBreaks = false;
      }

      // split the items in your cart into purchase vs. leased
      $purchaseItems = array();
      $leaseItems = array();
      $siteServerItems = array();
      $purchaseQuantity = 0;
      $leaseQuantity = 0;
      $siteServerQuantity = 0;
      $purchaseTotal = 0;
      $leaseTotal = 0;
      $siteServerTotal = 0;
      $purchaseMonitors = 0;
      $leaseMonitors = 0;
      $siteServerMonitors = 0;
      foreach ($thisCart as $product) {
        if( $product['purchased'] ) {
          $purchaseItems[] = $product;
          $purchaseTotal += str_replace(",", "", str_replace("$", "", $product['productsPrice']));
          $purchaseQuantity += $product['quantity'];
          $purchaseMonitors += $product['neededMonitors'];
        } else if( $product['siteserver'] ) {
          $siteServerItems[] = $product;
          $siteServerTotal += str_replace(",", "", str_replace("$", "", $product['productsPrice']));
          $siteServerQuantity += $product['quantity'];
          $siteServerMonitors += $product['neededMonitors'];
        } else {
          $leaseItems[] = $product;
          $leaseTotal += str_replace(",", "", str_replace("$", "", $product['productsPrice']));
          $leaseQuantity += $product['quantity'];
          $leaseMonitors += $product['neededMonitors'];
        }
      }
      $purchaseTotalsDisplay = TEXT_TOTAL_ITEMS . $purchaseQuantity . TEXT_TOTAL_AMOUNT . $currencies->format($purchaseTotal);
      $siteServerTotalsDisplay = TEXT_TOTAL_ITEMS . $siteServerQuantity . TEXT_TOTAL_AMOUNT . $currencies->display_price($siteServerTotal, 0, 3);
      $leaseTotalsDisplay = TEXT_TOTAL_ITEMS . $leaseQuantity . TEXT_TOTAL_AMOUNT . $currencies->display_price($leaseTotal, 0, 3);

      if( count($purchaseItems) ) { 
?>
<h1><?php echo $purchaseItems[0]['type_name']; ?> Items</h1><br>
<div class="tie text2">
	<div class="tie-indent">
	
	

<?php
        if ($messageStack->size('shopping_cart') > 0) echo $messageStack->output('shopping_cart'); 
?>

<?php
        echo zen_draw_form('cart_quantity', zen_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); 
?>
<div id="cartInstructionsDisplay" class="content"><?php
        echo TEXT_INFORMATION; 
?></div>

<?php 
        if (!empty($purchaseTotalsDisplay)) { 
?>
  <div class="cartTotalsDisplay important"><?php
          echo $purchaseTotalsDisplay; 
?></div>
  <br class="clearBoth" />
<?php
        } //endif (!empty($purchaseTotalsDisplay))
?>

<?php
        if ($flagAnyOutOfStock) { 
          if (STOCK_ALLOW_CHECKOUT == 'true') {
?>

<div class="messageStackError"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></div>

<?php
          } else { 
?>
<div class="messageStackError"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></div>

<?php
          } //endif STOCK_ALLOW_CHECKOUT
        } //endif flagAnyOutOfStock 
?>

<table  border="0" width="100%" cellspacing="0" cellpadding="0" class="cartContentsDisplay">
     <tr class="tableHeading">
        <th scope="col" id="scQuantityHeading"><?php echo TABLE_HEADING_QUANTITY; ?></th>
        <th scope="col" id="scUpdateQuantity"><?php echo REFRESH; ?></th>
        <th scope="col" id="scProductsHeading"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
        <th scope="col" id="scUnitHeading"><?php echo TABLE_HEADING_PRICE; ?></th>
        <th scope="col" id="scTotalHeading"><?php echo TABLE_HEADING_TOTAL; ?></th>
        <th scope="col" id="scRemoveHeading"><?php echo DELETE; ?></th>
     </tr>
         <!-- Loop through all products /-->
<?php
        foreach ($purchaseItems as $product) {
?>
     <tr class="<?php echo $product['rowClass']; ?>">
       <td class="cartQuantity">
<?php
          if ($product['flagShowFixedQuantity']) {
            echo $product['showFixedQuantityAmount'] . '<br /><span class="alert bold">' . $product['flagStockCheck'] . '</span><br /><br />' . $product['showMinUnits'];
          } else {
            echo $product['quantityField'] . '<br /><span class="alert bold">' . $product['flagStockCheck'] . '</span><br /><br />' . $product['showMinUnits'];
          }
?>
       </td>
       <td class="cartQuantityUpdate buttonRowz">
<?php
          if ($product['buttonUpdate'] == '') {
            echo '' ;
          } else {
            echo $product['buttonUpdate'];
          }
?>
       </td>
       <td class="cartProductDisplay">
<a href="<?php echo $product['linkProductsName']; ?>"><span id="cartProdTitle"><?php echo $product['productsName'] . '<span class="alert bold">' . $product['flagStockCheck'] . '</span>'; ?></span><span id="cartImage" class="back"><?php echo $product['productsImage']; ?></span></a>
<br class="clearBoth" />


<?php
          echo $product['attributeHiddenField'];
          if (isset($product['attributes']) && is_array($product['attributes'])) {
            echo '<div class="cartAttribsList">';
            echo '<ul>';
            reset($product['attributes']);
            foreach ($product['attributes'] as $option => $value) {
?>

<li><?php echo $value['products_options_name'] . TEXT_OPTION_DIVIDER . nl2br($value['products_options_values_name']); ?></li>

<?php
            } //endforeach ($product['attributes'] as $option => $value)
            echo '</ul>';
            echo '</div>';
          } //endif (isset($product['attributes']) && is_array($product['attributes']))
?>
       </td>
       <td class="cartUnitDisplay price"><?php echo $product['productsPriceEach']; ?></td>
       <td class="cartTotalDisplay"><?php echo $product['productsPrice']; ?></td>
       <td class="cartRemoveItemDisplay">
<?php
          if ($product['buttonDelete']) { /*
?>
           <a href="<?php echo zen_href_link(FILENAME_SHOPPING_CART, 'action=remove_product&product_id=' . $product['id']); ?>"><?php echo zen_image($template->get_template_dir(ICON_IMAGE_TRASH, DIR_WS_TEMPLATE, $current_page_base,'images/icons'). '/' . ICON_IMAGE_TRASH, ICON_TRASH_ALT); ?></a>
<?php */
          } //endif ($product['buttonDelete'])
          if ($product['checkBoxDelete'] ) {
            echo zen_draw_checkbox_field('cart_delete[]', $product['id']);
          }
?>
</td>
     </tr>
<?php
        } // end foreach ($purchaseItems as $product)
?>
       <!-- Finished loop through all products /-->
      </table>
	 </div>
</div>

<div id="cartSubTotal"><?php echo SUB_TITLE_SUB_TOTAL; ?> <span class="price"><?php echo $currencies->format($purchaseTotal); ?></span></div>
<br class="clearBoth" />

<!--bof shopping cart buttons-->

<div class="wrapper">

	<div class="buttonRow forward"><?php echo zen_image_submit(ICON_IMAGE_TRASH, ICON_UPDATE_ALT); ?></div>
	
<?php
        // show update cart button
        if (SHOW_SHOPPING_CART_UPDATE == 2 or SHOW_SHOPPING_CART_UPDATE == 3) {
?>
	<div class="buttonRow forward"><?php echo zen_image_submit(ICON_IMAGE_UPDATE, ICON_UPDATE_ALT); ?></div>
<?php
        } else { // don't show update button below cart
?>
<?php
        } // show update button
?>
	<!--eof shopping cart buttons-->
	


	<div class="shcart_btn">
<? 
        $canCheckout = false;
        foreach($_SESSION['customer_addresses'] as $address) {
          if( $address["ID"] == $_SESSION["selected_address_id"] ) {
            $canCheckout = $address["approve_cart"];
          }
        }
        if( $canCheckout ) {
?>
  <span>
    <input type="checkbox" onclick="ToggleCheckoutLink(this)" />
    By clicking this box, I acknowledge that I am authorized to submit this order, I have read and agree to the <a href="<?php echo $purchaseItems[0]['terms_link']; ?>" target="_blank">Terms and Conditions for <?php echo $purchaseItems[0]['type_name']; ?></a>,
    and acknowledge that submitting this order will result in a binding contract subject to the Terms and Conditions.
  </span><br><br>
  <div class="btn1">
    <a href="<?php echo zen_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL') . '&conditions=1&payment_freq=onetime' . (($_SESSION['selectedCartID'] == MASTER_CART) ? ('&cart_id=' . $key) : ''); ?>" onclick="alert('You must accept the terms and conditions.'); return false;">
    <?php echo zen_image_button(BUTTON_IMAGE_CHECKOUT, BUTTON_CHECKOUT_ALT); ?>
    </a>
  </div>
  <script type="text/javascript">
    function ToggleCheckoutLink(checkbox) {
      var canCheckout = true;
      $(checkbox).parent().find('input').each( function() { canCheckout &= ($(this).attr('checked') == 'checked'); } );
      $(checkbox).parent().siblings('.btn1').find('a:has(.button_checkout)').attr('onclick', (canCheckout ? null : 'alert("You must accept the terms and conditions."); return false;'));
    }
  </script>
<?php
        } //endif ( $canCheckout )
?>
        
	<div class="btn1"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_CONTINUE_SHOPPING, BUTTON_CONTINUE_SHOPPING_ALT) . '</a>'; ?></div>
	
<?php
        if (false && SHOW_SHIPPING_ESTIMATOR_BUTTON == '1') {
?>
	
	<?php echo '<div class="btn1"><a href="javascript:popupWindow(\'' . zen_href_link(FILENAME_POPUP_SHIPPING_ESTIMATOR) . '\')">' . zen_image_button(BUTTON_IMAGE_SHIPPING_ESTIMATOR, BUTTON_SHIPPING_ESTIMATOR_ALT) . '</a></div>'; ?>
<?php
        } //endif (false && SHOW_SHIPPING_ESTIMATOR_BUTTON == '1')
?>
	</div>
	
</div>


</form>




<!-- ** BEGIN PAYPAL EXPRESS CHECKOUT ** -->
<?php  // the tpl_ec_button template only displays EC option if cart contents >0 and value >0
        if (defined('MODULE_PAYMENT_PAYPALWPP_STATUS') && MODULE_PAYMENT_PAYPALWPP_STATUS == 'True') {
          include(DIR_FS_CATALOG . DIR_WS_MODULES . 'payment/paypal/tpl_ec_button.php');
        }
?>
<!-- ** END PAYPAL EXPRESS CHECKOUT ** -->

<?php
        if (SHOW_SHIPPING_ESTIMATOR_BUTTON == '2') {
/**
 * load the shipping estimator code if needed
 */
          require(DIR_WS_MODULES . zen_get_module_directory('shipping_estimator.php'));
        }
      } //endif ( count($purchaseItems) )
?>
	<div class="clear"></div>
<?php
      if( count($leaseItems) ) { 
        if( count($purchaseItems) )  echo "<br><br>";
?>
<h1><?php echo $leaseItems[0]['type_name']; ?> Items</h1><br>
<div class="tie text2">
	<div class="tie-indent">
	
	

<?php if ($messageStack->size('shopping_cart') > 0) echo $messageStack->output('shopping_cart'); ?>

<?php echo zen_draw_form('cart_quantity', zen_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?>
<div id="cartInstructionsDisplay" class="content"><?php echo TEXT_INFORMATION; ?></div>

<?php 
        if (!empty($leaseTotalsDisplay)) { 
?>
  <div class="cartTotalsDisplay important"><?php echo $leaseTotalsDisplay; ?></div>
  <br class="clearBoth" />
<?php 
        } //endif (!empty($leaseTotalsDisplay))
        if ($flagAnyOutOfStock) { 
          if (STOCK_ALLOW_CHECKOUT == 'true') {  
?>

<div class="messageStackError"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></div>

<?php
          } else { 
?>
<div class="messageStackError"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></div>

<?php
          } //endif STOCK_ALLOW_CHECKOUT
        } //endif flagAnyOutOfStock 
?>

<table  border="0" width="100%" cellspacing="0" cellpadding="0" class="cartContentsDisplay">
     <tr class="tableHeading">
        <th scope="col" id="scQuantityHeading"><?php echo TABLE_HEADING_QUANTITY; ?></th>
        <th scope="col" id="scUpdateQuantity"><?php echo REFRESH; ?></th>
        <th scope="col" id="scProductsHeading"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
        <th scope="col" id="scUnitHeading"><?php echo TABLE_HEADING_ANNUAL_COST; ?></th>
        <th scope="col" id="scTotalHeading"><?php echo TABLE_HEADING_TOTAL_COST; ?></th>
        <th scope="col" id="scRemoveHeading"><?php echo DELETE; ?></th>
     </tr>
         <!-- Loop through all products /-->
<?php
        foreach ($leaseItems as $product) {
?>
     <tr class="<?php echo $product['rowClass']; ?>">
       <td class="cartQuantity">
<?php
          if ($product['flagShowFixedQuantity']) {
            echo $product['showFixedQuantityAmount'] . '<br /><span class="alert bold">' . $product['flagStockCheck'] . '</span><br /><br />' . $product['showMinUnits'];
          } else {
            echo $product['quantityField'] . '<br /><span class="alert bold">' . $product['flagStockCheck'] . '</span><br /><br />' . $product['showMinUnits'];
          }
?>
       </td>
       <td class="cartQuantityUpdate buttonRow">
<?php
          if ($product['buttonUpdate'] == '') {
            echo '' ;
          } else {
            echo $product['buttonUpdate'];
          }
?>
       </td>
       <td class="cartProductDisplay">
<a href="<?php echo $product['linkProductsName']; ?>"><span id="cartProdTitle"><?php echo $product['productsName'] . '<span class="alert bold">' . $product['flagStockCheck'] . '</span>'; ?></span><span id="cartImage" class="back"><?php echo $product['productsImage']; ?></span></a>
<br class="clearBoth" />


<?php
          echo $product['attributeHiddenField'];
          if (isset($product['attributes']) && is_array($product['attributes'])) {
            echo '<div class="cartAttribsList">';
            echo '<ul>';
            reset($product['attributes']);
            foreach ($product['attributes'] as $option => $value) {
?>

<li><?php echo $value['products_options_name'] . TEXT_OPTION_DIVIDER . nl2br($value['products_options_values_name']); ?></li>

<?php
            } //endforeach ($product['attributes'] as $option => $value)
            echo '</ul>';
            echo '</div>';
          } //endif (isset($product['attributes']) && is_array($product['attributes']))
?>
       </td>
       <td class="cartUnitDisplay price"><?php echo $product['productsPriceEach']; ?></td>
       <td class="cartTotalDisplay"><?php 
         echo "2017:  " . $currencies->display_price(substr(str_replace(",", "", $product['productsPrice']), 1), 0, 0.5) . "<br />";
         echo "2018:  " . $currencies->display_price(substr(str_replace(",", "", $product['productsPrice']), 1), 0, 1) . "<br />";
         echo "2019:  " . $currencies->display_price(substr(str_replace(",", "", $product['productsPrice']), 1), 0, 1) . "<br />";
         echo "2020:  " . $currencies->display_price(substr(str_replace(",", "", $product['productsPrice']), 1), 0, 0.5) . "<br />";
         echo "Total:  " . $currencies->display_price(substr(str_replace(",", "", $product['productsPrice']), 1), 0, 3);
       ?></td>
       <td class="cartRemoveItemDisplay">
<?php
          if ($product['buttonDelete']) { /*
?>
           <a href="<?php echo zen_href_link(FILENAME_SHOPPING_CART, 'action=remove_product&product_id=' . $product['id']); ?>"><?php echo zen_image($template->get_template_dir(ICON_IMAGE_TRASH, DIR_WS_TEMPLATE, $current_page_base,'images/icons'). '/' . ICON_IMAGE_TRASH, ICON_TRASH_ALT); ?></a>
<?php */
          } //endif ($product['buttonDelete'])
          if ($product['checkBoxDelete'] ) {
            echo zen_draw_checkbox_field('cart_delete[]', $product['id']);
          }
?>
</td>
     </tr>
<?php
        } // end foreach ($leaseItems as $product) 
?>
       <!-- Finished loop through all products /-->
      </table>
	 </div>
</div>

<div id="cartSubTotal"><?php echo SUB_TITLE_SUB_TOTAL; ?> <span class="price"><?php echo $currencies->display_price($leaseTotal, 0, 3); ?></span></div>
<br class="clearBoth" />

<!--bof shopping cart buttons-->

<div class="wrapper">

	<div class="buttonRow forward"><?php echo zen_image_submit(ICON_IMAGE_TRASH, ICON_UPDATE_ALT); ?></div>
	
<?php
        // show update cart button
        if (SHOW_SHOPPING_CART_UPDATE == 2 or SHOW_SHOPPING_CART_UPDATE == 3) {
?>
	<div class="buttonRow forward"><?php echo zen_image_submit(ICON_IMAGE_UPDATE, ICON_UPDATE_ALT); ?></div>
<?php
        } else { // don't show update button below cart
        } // show update button
?>
	<!--eof shopping cart buttons-->
	


	<div class="shcart_btn">
<? 
        $canCheckout = false;
        foreach($_SESSION['customer_addresses'] as $address) {
          if( $address["ID"] == $_SESSION["selected_address_id"] ) {
            $canCheckout = $address["approve_cart"];
          }
        }
        if( $canCheckout ) {
?>
  <span>
    <input type="checkbox" onclick="ToggleCheckoutLink(this)" />
    By clicking this box, I acknowledge that I am authorized to submit this order, I have read and agree to the <a href="<?php echo $leaseItems[0]['terms_link']; ?>" target="_blank">Terms and Conditions for <?php echo $leaseItems[0]['type_name']; ?></a>,
    and acknowledge that submitting this order will result in a binding contract subject to the Terms and Conditions.
    <br><br>
<?php
          if( $leaseMonitors != 0 ) {
?>
    <input type="checkbox" onclick="ToggleCheckoutLink(this)" />
    I am intentionally selecting <?php echo abs($leaseMonitors); ?> more <?php echo (($leaseMonitors > 0) ? "computers" : "monitors"); ?> than <?php echo (($leaseMonitors < 0) ? "computers" : "monitors"); ?>.<br><br>
<?php
          } //endif ( $leaseMonitors != 0 )
?>
  </span>
  <div class="btn1">
    <a href="<?php echo zen_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL') . '&conditions=1&payment_freq=annually' . (($_SESSION['selectedCartID'] == MASTER_CART) ? ('&cart_id=' . $key) : ''); ?>" onclick="alert('You must check all of the boxes above.'); return false;">
    <?php echo zen_image_button(BUTTON_IMAGE_CHECKOUT, BUTTON_CHECKOUT_ALT); ?>
    </a>
  </div>
  <script type="text/javascript">
    function ToggleCheckoutLink(checkbox) {
      var canCheckout = true;
      $(checkbox).parent().find('input').each( function() { canCheckout &= ($(this).attr('checked') == 'checked'); } );
      $(checkbox).parent().siblings('.btn1').find('a:has(.button_checkout)').attr('onclick', (canCheckout ? null : 'alert("You must check all of the boxes above."); return false;'));
    }
  </script>
<?php
        } //endif ( $canCheckout )
?>
        
	<div class="btn1"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_CONTINUE_SHOPPING, BUTTON_CONTINUE_SHOPPING_ALT) . '</a>'; ?></div>
	
<?php
        if (false && SHOW_SHIPPING_ESTIMATOR_BUTTON == '1') {
          echo '<div class="btn1"><a href="javascript:popupWindow(\'' . zen_href_link(FILENAME_POPUP_SHIPPING_ESTIMATOR) . '\')">' . zen_image_button(BUTTON_IMAGE_SHIPPING_ESTIMATOR, BUTTON_SHIPPING_ESTIMATOR_ALT) . '</a></div>';
        }
?>
	</div>
	
</div>


</form>




<!-- ** BEGIN PAYPAL EXPRESS CHECKOUT ** -->
<?php  // the tpl_ec_button template only displays EC option if cart contents >0 and value >0
        if (defined('MODULE_PAYMENT_PAYPALWPP_STATUS') && MODULE_PAYMENT_PAYPALWPP_STATUS == 'True') {
          include(DIR_FS_CATALOG . DIR_WS_MODULES . 'payment/paypal/tpl_ec_button.php');
        }
?>
<!-- ** END PAYPAL EXPRESS CHECKOUT ** -->

<?php
        if (SHOW_SHIPPING_ESTIMATOR_BUTTON == '2') {
/**
 * load the shipping estimator code if needed
 */
?>
      <?php require(DIR_WS_MODULES . zen_get_module_directory('shipping_estimator.php')); ?>

<?php
        } //endif(SHOW_SHIPPING_ESTIMATOR_BUTTON == '2')
      } //endif ( count($leaseItems) )
?>
	<div class="clear"></div>
<?php
      if( count($siteServerItems) ) { 
        if( count($purchaseItems) || count($leaseItems) )  echo "<br><br>";
?>
<h1><?php echo $siteServerItems[0]['type_name']; ?> Items</h1><br>
<div class="tie text2">
	<div class="tie-indent">
	
	

<?php if ($messageStack->size('shopping_cart') > 0) echo $messageStack->output('shopping_cart'); ?>

<?php echo zen_draw_form('cart_quantity', zen_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?>
<div id="cartInstructionsDisplay" class="content"><?php echo TEXT_INFORMATION; ?></div>

<?php 
        if (!empty($siteServerTotalsDisplay)) { 
?>
  <div class="cartTotalsDisplay important"><?php echo $siteServerTotalsDisplay; ?></div>
  <br class="clearBoth" />
<?php 
        } //endif (!empty($siteServerTotalsDisplay))
        if ($flagAnyOutOfStock) { 
          if (STOCK_ALLOW_CHECKOUT == 'true') {  
?>

<div class="messageStackError"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></div>

<?php
          } else { 
?>
<div class="messageStackError"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></div>

<?php
          } //endif STOCK_ALLOW_CHECKOUT
        } //endif flagAnyOutOfStock 
?>

<table  border="0" width="100%" cellspacing="0" cellpadding="0" class="cartContentsDisplay">
     <tr class="tableHeading">
        <th scope="col" id="scQuantityHeading"><?php echo TABLE_HEADING_QUANTITY; ?></th>
        <th scope="col" id="scUpdateQuantity"><?php echo REFRESH; ?></th>
        <th scope="col" id="scProductsHeading"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
        <th scope="col" id="scUnitHeading"><?php echo TABLE_HEADING_ANNUAL_COST; ?></th>
        <th scope="col" id="scTotalHeading"><?php echo TABLE_HEADING_TOTAL_COST; ?></th>
        <th scope="col" id="scRemoveHeading"><?php echo DELETE; ?></th>
     </tr>
         <!-- Loop through all products /-->
<?php
        foreach ($siteServerItems as $product) {
?>
     <tr class="<?php echo $product['rowClass']; ?>">
       <td class="cartQuantity">
<?php
          if ($product['flagShowFixedQuantity']) {
            echo $product['showFixedQuantityAmount'] . '<br /><span class="alert bold">' . $product['flagStockCheck'] . '</span><br /><br />' . $product['showMinUnits'];
          } else {
            echo $product['quantityField'] . '<br /><span class="alert bold">' . $product['flagStockCheck'] . '</span><br /><br />' . $product['showMinUnits'];
          }
?>
       </td>
       <td class="cartQuantityUpdate buttonRow">
<?php
          if ($product['buttonUpdate'] == '') {
            echo '' ;
          } else {
            echo $product['buttonUpdate'];
          }
?>
       </td>
       <td class="cartProductDisplay">
<a href="<?php echo $product['linkProductsName']; ?>"><span id="cartProdTitle"><?php echo $product['productsName'] . '<span class="alert bold">' . $product['flagStockCheck'] . '</span>'; ?></span><span id="cartImage" class="back"><?php echo $product['productsImage']; ?></span></a>
<br class="clearBoth" />


<?php
          echo $product['attributeHiddenField'];
          if (isset($product['attributes']) && is_array($product['attributes'])) {
            echo '<div class="cartAttribsList">';
            echo '<ul>';
            reset($product['attributes']);
            foreach ($product['attributes'] as $option => $value) {
?>

<li><?php echo $value['products_options_name'] . TEXT_OPTION_DIVIDER . nl2br($value['products_options_values_name']); ?></li>

<?php
            } //endforeach ($product['attributes'] as $option => $value)
            echo '</ul>';
            echo '</div>';
          } //endif (isset($product['attributes']) && is_array($product['attributes']))
?>
       </td>
       <td class="cartUnitDisplay price"><?php echo $product['productsPriceEach']; ?></td>
       <td class="cartTotalDisplay"><?php 
         echo "2017:  " . $currencies->display_price(substr(str_replace(",", "", $product['productsPrice']), 1), 0, 0.5) . "<br />";
         echo "2018:  " . $currencies->display_price(substr(str_replace(",", "", $product['productsPrice']), 1), 0, 1) . "<br />";
         echo "2019:  " . $currencies->display_price(substr(str_replace(",", "", $product['productsPrice']), 1), 0, 1) . "<br />";
         echo "2020:  " . $currencies->display_price(substr(str_replace(",", "", $product['productsPrice']), 1), 0, 0.5) . "<br />";
         echo "Total:  " . $currencies->display_price(substr(str_replace(",", "", $product['productsPrice']), 1), 0, 3);
       ?></td>
       <td class="cartRemoveItemDisplay">
<?php
          if ($product['buttonDelete']) { /*
?>
           <a href="<?php echo zen_href_link(FILENAME_SHOPPING_CART, 'action=remove_product&product_id=' . $product['id']); ?>"><?php echo zen_image($template->get_template_dir(ICON_IMAGE_TRASH, DIR_WS_TEMPLATE, $current_page_base,'images/icons'). '/' . ICON_IMAGE_TRASH, ICON_TRASH_ALT); ?></a>
<?php */
          } //endif ($product['buttonDelete'])
          if ($product['checkBoxDelete'] ) {
            echo zen_draw_checkbox_field('cart_delete[]', $product['id']);
          }
?>
</td>
     </tr>
<?php
        } // end foreach ($siteServerItems as $product) 
?>
       <!-- Finished loop through all products /-->
      </table>
	 </div>
</div>

<div id="cartSubTotal"><?php echo SUB_TITLE_SUB_TOTAL; ?> <span class="price"><?php echo $currencies->display_price($siteServerTotal, 0, 3); ?></span></div>
<br class="clearBoth" />

<!--bof shopping cart buttons-->

<div class="wrapper">

	<div class="buttonRow forward"><?php echo zen_image_submit(ICON_IMAGE_TRASH, ICON_UPDATE_ALT); ?></div>
	
<?php
        // show update cart button
        if (SHOW_SHOPPING_CART_UPDATE == 2 or SHOW_SHOPPING_CART_UPDATE == 3) {
?>
	<div class="buttonRow forward"><?php echo zen_image_submit(ICON_IMAGE_UPDATE, ICON_UPDATE_ALT); ?></div>
<?php
        } else { // don't show update button below cart
        } // show update button
?>
	<!--eof shopping cart buttons-->
	


	<div class="shcart_btn">
<? 
        $canCheckout = false;
        foreach($_SESSION['customer_addresses'] as $address) {
          if( $address["ID"] == $_SESSION["selected_address_id"] ) {
            $canCheckout = $address["approve_cart"];
          }
        }
        if( $canCheckout ) {
?>
  <span>
    <input type="checkbox" onclick="ToggleCheckoutLink(this)" />
    By clicking this box, I acknowledge that I am authorized to submit this order, I have read and agree to the <a href="<?php echo $siteServerItems[0]['terms_link']; ?>" target="_blank">Terms and Conditions for <?php echo $siteServerItems[0]['type_name']; ?></a>,
    and acknowledge that submitting this order will result in a binding contract subject to the Terms and Conditions.
  </span><br><br>
  <div class="btn1">
    <a href="<?php echo zen_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL') . '&conditions=1&payment_freq=siteserver' . (($_SESSION['selectedCartID'] == MASTER_CART) ? ('&cart_id=' . $key) : ''); ?>" onclick="alert('You must accept the terms and conditions.'); return false;">
    <?php echo zen_image_button(BUTTON_IMAGE_CHECKOUT, BUTTON_CHECKOUT_ALT); ?>
    </a>
  </div>
  <script type="text/javascript">
    function ToggleCheckoutLink(checkbox) {
      var canCheckout = true;
      $(checkbox).parent().find('input').each( function() { canCheckout &= ($(this).attr('checked') == 'checked'); } );
      $(checkbox).parent().siblings('.btn1').find('a:has(.button_checkout)').attr('onclick', (canCheckout ? null : 'alert("You must accept the terms and conditions."); return false;'));
    }
  </script>
<?php
        } //endif ( $canCheckout )
?>
        
	<div class="btn1"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_CONTINUE_SHOPPING, BUTTON_CONTINUE_SHOPPING_ALT) . '</a>'; ?></div>
	
<?php
        if (false && SHOW_SHIPPING_ESTIMATOR_BUTTON == '1') {
          echo '<div class="btn1"><a href="javascript:popupWindow(\'' . zen_href_link(FILENAME_POPUP_SHIPPING_ESTIMATOR) . '\')">' . zen_image_button(BUTTON_IMAGE_SHIPPING_ESTIMATOR, BUTTON_SHIPPING_ESTIMATOR_ALT) . '</a></div>';
        }
?>
	</div>
	
</div>


</form>




<!-- ** BEGIN PAYPAL EXPRESS CHECKOUT ** -->
<?php  // the tpl_ec_button template only displays EC option if cart contents >0 and value >0
        if (defined('MODULE_PAYMENT_PAYPALWPP_STATUS') && MODULE_PAYMENT_PAYPALWPP_STATUS == 'True') {
          include(DIR_FS_CATALOG . DIR_WS_MODULES . 'payment/paypal/tpl_ec_button.php');
        }
?>
<!-- ** END PAYPAL EXPRESS CHECKOUT ** -->

<?php
        if (SHOW_SHIPPING_ESTIMATOR_BUTTON == '2') {
/**
 * load the shipping estimator code if needed
 */
?>
      <?php require(DIR_WS_MODULES . zen_get_module_directory('shipping_estimator.php')); ?>

<?php
        } //endif(SHOW_SHIPPING_ESTIMATOR_BUTTON == '2')
      } //endif ( count($leaseItems) )
?>
	<div class="clear"></div>
<?php
      if( $_SESSION['selectedCartID'] == MASTER_CART ) {        
        echo "</div>";
      }
    } //endforeach ( $cartArray as $thisCart )
  } //endif ($flagHasCartContents)
?>
	

</div>
