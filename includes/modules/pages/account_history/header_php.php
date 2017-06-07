<?php
/**
 * Header code file for the Account History page
 *
 * @package page
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header_php.php 3160 2006-03-11 01:37:18Z drbyte $
 */
// This should be first line of the script:
$zco_notifier->notify('NOTIFY_HEADER_START_ACCOUNT_HISTORY');


if (!$_SESSION['customer_id']) {
  $_SESSION['navigation']->set_snapshot();
  zen_redirect(zen_href_link(FILENAME_LOGIN, '', 'SSL'));
}

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
require(DIR_WS_CLASSES . 'order.php');

$breadcrumb->add(NAVBAR_TITLE_1, zen_href_link(FILENAME_ACCOUNT, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2);

$orders_total = zen_count_customer_orders();

if (true || $orders_total > 0) {
  $history_query_raw = "SELECT o.orders_id, o.date_purchased, o.delivery_name, o.customers_name, ab.address_book_id, ab.entry_company, 
                               o.billing_name, ot.title as order_title, ot.text as order_total, ot.value as order_value, s.orders_status_name
                        FROM   " . TABLE_ORDERS . " o 
                        JOIN   " . TABLE_ORDERS_TOTAL . " ot USING (orders_id) 
                        JOIN   " . TABLE_ORDERS_STATUS . " s ON (o.orders_status = s.orders_status_id)
                        JOIN   " . TABLE_ADDRESS_BOOK . " ab USING (librarycode)  ";
  if( $_SESSION['selectedCartID'] == MASTER_CART ) {
    //$history_query_raw .= "WHERE      ab.address_book_id in (:addressID";
    $history_query_raw .= "WHERE      o.delivery_address_id in (:addressID";
    foreach( $_SESSION["customer_addresses"] as $thisAddress ) {
      $history_query_raw .= "," . $thisAddress["ID"];
    }
    $history_query_raw .= ")  ";
  } else {
    //$history_query_raw .= "WHERE      ab.address_book_id = :addressID  ";
    $history_query_raw .= "WHERE      o.delivery_address_id = :addressID  ";
  }
  $history_query_raw .= "AND        ot.class = 'ot_total'
                         AND        s.language_id = :languagesID
                         ORDER BY   entry_company, orders_id DESC";

  //$history_query_raw = $db->bindVars($history_query_raw, ':customersID', $_SESSION['customer_id'], 'integer');
  $history_query_raw = $db->bindVars($history_query_raw, ':addressID', $_SESSION['selected_address_id'], 'string');
  $history_query_raw = $db->bindVars($history_query_raw, ':languagesID', $_SESSION['languages_id'], 'integer');
  $history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
  $history = $db->Execute($history_split->sql_query);

  $accountHistory = array();
  //$accountHasHistory = true;
  $accountHasHistory = false;
  while (!$history->EOF) {
    $products_query = "SELECT sum(products_quantity) AS count
                       FROM   " . TABLE_ORDERS_PRODUCTS . "
                       WHERE  orders_id = :ordersID";

    $products_query = $db->bindVars($products_query, ':ordersID', $history->fields['orders_id'], 'integer');
    $products = $db->Execute($products_query);

    if (zen_not_null($history->fields['delivery_name'])) {
      $order_type = TEXT_ORDER_SHIPPED_TO;
      $order_name = $history->fields['delivery_name'];
    } else {
      $order_type = TEXT_ORDER_BILLED_TO;
      $order_name = $history->fields['billing_name'];
    }
    $extras = array('order_type'=>$order_type,
    'order_name'=>$order_name,
    'product_count'=>$products->fields['count']);
    $accountHistory[] = array_merge($history->fields, $extras);
    $history->moveNext();
    $accountHasHistory = true;
  }
} else {
  $accountHasHistory = false;
}


// dump the cart to a csv
if( isset($_GET["regenerateCSV"]) ) {
  $csvFile = fopen("/home/einet/public_html/intranet/orderHistory.csv", "w");

  fwrite($csvFile, "\"Location\",\"Order Number\",\"Confirmed By\",\"Order Date\",\"Quantity\",\"Item Name\",\"Additional Attributes (expand row height to see all)\",\"Unit Cost\",\"Annual Unit Cost\",\"Total Year 1 Cost\",\"Total Year 2 Cost\",\"Total Year 3 Cost\",\"Total Year 4 Cost\",\"Total Cost\"\n");

  // get the full history
  $fullHistory = $db->Execute($history_query_raw);
  while( !$fullHistory->EOF ) {
    $libraryName = "";
    foreach( $_SESSION["customer_addresses"] as $thisAddress ) {
      if( $thisAddress["ID"] == $fullHistory->fields['address_book_id'] ) {
        $libraryName = $thisAddress["library_name"];
      }
    }

    $order = new order($fullHistory->fields['orders_id']);
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      fwrite($csvFile, "\"" . $fullHistory->fields['entry_company']/*libraryName*/ . "\"," . $fullHistory->fields['orders_id'] . ",\"" . $fullHistory->fields['customers_name'] . "\",\"" . substr($fullHistory->fields['date_purchased'], 0, 10) . "\"," .
                       $order->products[$i]['qty'] . ",\"" . $order->products[$i]['name'] . "\",\"");
      if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
        for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
          fwrite($csvFile, ($j ? "\n" : "") . $order->products[$i]['attributes'][$j]['option'] . " - " . nl2br(zen_output_string_protected($order->products[$i]['attributes'][$j]['value'])));
        }
      }
      $ppe = zen_round(zen_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), $currencies->get_decimal_places($order->info['currency']));
      if( $order->products[$i]['payment_freq'] == "annually" || $order->products[$i]['payment_freq'] == "siteserver" ) {
        fwrite($csvFile, "\",,\"" . $currencies->format($ppe, true, $order->info['currency'], $order->info['currency_value']) . "\",\"" . 
                         $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'] * 0.5, true, $order->info['currency'], $order->info['currency_value']) . "\",\"" . 
                         $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . "\",\"" . 
                         $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . "\",\"" . 
                         $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'] * 0.5, true, $order->info['currency'], $order->info['currency_value']) . "\",\"" . 
                         $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'] * 3, true, $order->info['currency'], $order->info['currency_value']) . "\"\n");
      } else {
        fwrite($csvFile, "\",\"" . $currencies->format($ppe, true, $order->info['currency'], $order->info['currency_value']) . "\",,,,,,\"" . 
                         $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . "\"\n");
      }
    }
    $fullHistory->moveNext();
  }

/*
  if( $products['multiCart'] ) {
    foreach( $productArray as $key => $thisCart ) {
      if($key == "multiCart") {
        continue;
      }

      $name_query = "select entry_company as name
                     from " . TABLE_ADDRESS_BOOK . " 
                     join " . TABLE_CUSTOMERS_BASKET_NEW . " using (address_book_id) 
                     where customers_basket_new_id = :cart_id";
      $name_query = $db->bindVars($name_query, ':cart_id', $key, 'integer');
      $name = $db->Execute($name_query);

      foreach($thisCart as $thisProduct) {
        fwrite($csvFile, "\"" . $name->fields['name'] . "\"," . $thisProduct['quantity'] . ",\"" . $thisProduct['productsName'] . "\",\"");
        $addComma = false;
        foreach($thisProduct["attributes"] as $thisAttr) {
          fwrite($csvFile, ($addComma ? "," : "") . $thisAttr['products_options_name'] . " - " . nl2br($thisAttr['products_options_values_name']));
          $addComma = true;
        }
        $ppe = floatval(substr($thisProduct['productsPriceEach'], 1));
        if( $thisProduct["purchased"] ) {
          fwrite($csvFile, "\",\"" . $thisProduct['productsPriceEach'] . "\",,,,,,\"" . $currencies->display_price($ppe * $thisProduct['quantity']));
        } else {
          fwrite($csvFile, "\",,\"" . $thisProduct['productsPriceEach'] . "\",\"" . $currencies->display_price($ppe * $thisProduct['quantity'] * 0.5) . "\",\"" . 
                                                                                    $currencies->display_price($ppe * $thisProduct['quantity']) . "\",\"" . 
                                                                                    $currencies->display_price($ppe * $thisProduct['quantity']) . "\",\"" . 
                                                                                    $currencies->display_price($ppe * $thisProduct['quantity'] * 0.5) . "\",\"" .
                                                                                    $currencies->display_price($ppe * $thisProduct['quantity'] * 3));
        }
        fwrite($csvFile, "\"\n");
      }
    }
  } else {
    $name_query = "select entry_company as name
                   from " . TABLE_ADDRESS_BOOK . " 
                   join " . TABLE_CUSTOMERS_BASKET_NEW . " using (address_book_id) 
                   where customers_basket_new_id = :cart_id";
    $name_query = $db->bindVars($name_query, ':cart_id', $_SESSION['selectedCartID'], 'integer');
    $name = $db->Execute($name_query);

    foreach($productArray as $thisProduct) {
      fwrite($csvFile, "\"" . $name->fields['name'] . "\"," . $thisProduct['quantity'] . ",\"" . $thisProduct['productsName'] . "\",\"");
      $addComma = false;
      foreach($thisProduct["attributes"] as $thisAttr) {
        fwrite($csvFile, ($addComma ? "," : "") . $thisAttr['products_options_name'] . " - " . nl2br($thisAttr['products_options_values_name']));
        $addComma = true;
      }
      $ppe = floatval(substr($thisProduct['productsPriceEach'], 1));
      if( $thisProduct["purchased"] ) {
        fwrite($csvFile, "\",\"" . $thisProduct['productsPriceEach'] . "\",,,,,,\"" . $currencies->display_price($ppe * $thisProduct['quantity']));
      } else {
        fwrite($csvFile, "\",,\"" . $thisProduct['productsPriceEach'] . "\",\"" . $currencies->display_price($ppe * $thisProduct['quantity'] * 0.5) . "\",\"" . 
                                                                                  $currencies->display_price($ppe * $thisProduct['quantity']) . "\",\"" . 
                                                                                  $currencies->display_price($ppe * $thisProduct['quantity']) . "\",\"" . 
                                                                                  $currencies->display_price($ppe * $thisProduct['quantity'] * 0.5) . "\",\"" .
                                                                                  $currencies->display_price($ppe * $thisProduct['quantity'] * 3));
      }
      fwrite($csvFile, "\"\n");
    }
  }
*/

  fclose($csvFile);
  echo "<script type='text/javascript'>window.location.href=\"/orderHistory.csv?cacheBuster=" . time() . "\"; setTimeout(window.close, 1000);</script>";
}

// This should be last line of the script:
$zco_notifier->notify('NOTIFY_HEADER_END_ACCOUNT_HISTORY');
?>
