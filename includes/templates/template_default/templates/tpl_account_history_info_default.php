<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=account_edit.<br />
 * Displays information related to a single specific order
 *
 * @copyright Copyright 2003-2020 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 Oct 19 Modified in v1.5.7a $
 */

if( ($_SESSION["customer_first_name"] == "Mary") && ($_SESSION["customer_last_name"] == "Coyle") ) {
  echo "<a style='float:right' href='index.php?main_page=account_history_info&order_id=" . $order->info["order_id"] . "&resendEmail=true'>Resend email</a><br>";
  if( $_REQUEST["resendEmail"] ) {
    $order->resend_order_email($order->info["order_id"], 2);
  }
}
?>
<div class="centerColumn" id="accountHistInfo">

<div class="forward"><?php echo HEADING_ORDER_DATE . ' ' . zen_date_long($order->info['date_purchased']); ?></div>
<br class="clearBoth" />

<?php if ($current_page != FILENAME_CHECKOUT_SUCCESS) { ?>
<h2 id="orderHistoryDetailedOrder"><?php echo HEADING_TITLE . ORDER_HEADING_DIVIDER . sprintf(HEADING_ORDER_NUMBER, zen_output_string_protected($_GET['order_id'])); ?></h2>
<?php } ?>

<table id="orderHistoryHeading">
    <tr class="tableHeading">
        <th scope="col" id="myAccountQuantity"><?php echo HEADING_QUANTITY; ?></th>
        <th scope="col" id="myAccountProducts"><?php echo HEADING_PRODUCTS; ?></th>
<?php
  if (isset($order->info['tax_groups']) && count($order->info['tax_groups']) > 1) {
?>
        <th scope="col" id="myAccountTax"><?php echo HEADING_TAX; ?></th>
<?php
  }
?>
        <th scope="col"><?php echo ($order->products[0]['payment_plan'] && ($order->products[0]['products_type'] != WAP_TYPE_ID)) ? TABLE_HEADING_ANNUAL_COST : TABLE_HEADING_PRICE; ?></th>
        <th scope="col" id="myAccountTotal"><?php echo ($order->products[0]['payment_plan'] && ($order->products[0]['products_type'] != WAP_TYPE_ID)) ? TABLE_HEADING_TOTAL_COST : HEADING_TOTAL; ?></th>
    </tr>
<?php
  $erate_totals = ["p" => 0, "el" => 0, "er" => 0, "ap" => 0];
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    $wapConfig = in_array($order->products[$i]["hashedid"], [WAP_CONFIG_SERVICE,WAP_CLOUD_MANAGEMENT]);
?>
    <tr>
        <td class="accountQuantityDisplay"><?php echo  $order->products[$i]['qty'] . QUANTITY_SUFFIX; ?></td>
        <td class="accountProductDisplay"><?php echo  $order->products[$i]['name'] . ($order->products[$i]['config_id'] ? (' (Config ' . $order->products[$i]['config_id'] . ')') : "");

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      echo '<ul class="orderAttribsList">';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<li>' . $order->products[$i]['attributes'][$j]['option'] . TEXT_OPTION_DIVIDER . nl2br(zen_output_string_protected($order->products[$i]['attributes'][$j]['value'])) . '</li>';
      }
        echo '</ul>';
    }
?>
        </td>
<?php
    if (isset($order->info['tax_groups']) && count($order->info['tax_groups']) > 1) {
?>
        <td class="accountTaxDisplay"><?php echo zen_display_tax_value($order->products[$i]['tax']) . '%' ?></td>
<?php
    }
?>
        <td class="accountTotalDisplay">
        <?php
          $ppe = zen_round(zen_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), $currencies->get_decimal_places($order->info['currency']));
          if( $order->products[$i]["id"] != WAP_CONFIG_SERVICE ) {
            echo $currencies->format($ppe, true, $order->info['currency'], $order->info['currency_value']) . ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '');
          }
          if( $order->products[$i]['hashedid'] == "213:173abe7021f8faacc9edb9247d00b6f8" ) {
            echo "<br><br>(reduced from " . $currencies->format(530, true, $order->info['currency']) . ")";
          } else if( $order->products[$i]['hashedid'] == "213:654a31a174713758ea2d8cf3ff6b31a9" ) {
            echo "<br><br>(reduced from " . $currencies->format(435, true, $order->info['currency']) . ")";
          } else if( $order->products[$i]['hashedid'] == "213:5fe7213fe26a257f1064680c81919540" ) {
            echo "<br><br>(reduced from " . $currencies->format(465, true, $order->info['currency']) . ")";
          } else if( $order->products[$i]['hashedid'] == "213:a88d2ef1d1f8fbc87f30c3d39fe9f091" ) {
            echo "<br><br>(reduced from " . $currencies->format(508, true, $order->info['currency']) . ")";
          } else if( $order->products[$i]['hashedid'] == "213:dfb0111ddff1a1285fb9433734495482" ) {
            echo "<br><br>(reduced from " . $currencies->format(487, true, $order->info['currency']) . ")";
          } else if( $order->products[$i]['hashedid'] == "212:0eccfdd0a8ff67eab1218a6097315dd0" ) {
            echo "<br><br>(reduced from " . $currencies->format(300, true, $order->info['currency']) . ")";
          } else if( $order->products[$i]['hashedid'] == "212:603dc6ad481c72e4d8ae59b76bc6c23c" ) {
            echo "<br><br>(reduced from " . $currencies->format(323, true, $order->info['currency']) . ")";
          } else if( $order->products[$i]['hashedid'] == "212:f6fb85c78246254d9fed48d4a21dc2cb" ) {
            echo "<br><br>(reduced from " . $currencies->format(365, true, $order->info['currency']) . ")";
          } else if( $order->products[$i]['hashedid'] == "183:1b11182b61d2bb707cef8a829a3943b4" ) {
            echo "<br><br>(reduced from " . $currencies->format(219, true, $order->info['currency']) . ")";
          } else if( $order->products[$i]['hashedid'] == "211:6e7ac41b582dd1222285ba6d97664ad6" ) {
            echo "<br><br>(reduced from " . $currencies->format(270, true, $order->info['currency']) . ")";
          } else if( $order->products[$i]['hashedid'] == "211:e5c839b6660fcd118343d0490d19853e" ) {
            echo "<br><br>(reduced from " . $currencies->format(289, true, $order->info['currency']) . ")";
          }
        ?></td>
        <td class="accountTotalDisplay">
        <?php
          if( $order->products[$i]['payment_plan'] ) {
            $lines = explode("<br />", $order->products[$i]['payment_plan']);
            echo (($order->products[$i]["id"] == WAP_INSTALL) ? ("<strong>Base Price:</strong> " . $currencies->display_price(WAP_INSTALL_BASE_PRICE, 0, 1) . "<br />") : "");
            foreach( $lines as $lineIndex => $thisLine ) {
              $tokens = explode("[[[", $thisLine);
              $totalStr = "<strong>" . $tokens[0] . "</strong>";
              $total = $order->products[$i]['final_price'] * $order->products[$i]['qty'] + (($order->products[$i]['id'] == WAP_INSTALL) ? WAP_INSTALL_BASE_PRICE : 0);
              $eratable = $order->products[$i]['erate_eligible'] * $order->products[$i]['qty'] + (($order->products[$i]['id'] == WAP_INSTALL) ? WAP_INSTALL_BASE_PRICE : 0);
              for( $j=1; $j<count($tokens); $j++ ) {
                $tokenSplit = explode("]]]", $tokens[$j]);
                if( is_numeric($tokenSplit[0]) ) {
                  $totalStr .= $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'] * $tokenSplit[0], true, $order->info['currency'], $order->info['currency_value']) . ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '') . ((count($tokenSplit) > 1) ? ("<strong>" . $tokenSplit[1] . "</strong>") : "");
                } else if( $tokenSplit[0] == "p" ) {
                  $erate_totals[$tokenSplit[0]] += $wapConfig ? 0 : $total;
                  $totalStr .= $currencies->format($total, true, $order->info['currency'], $order->info['currency_value']) . ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '') . ((count($tokenSplit) > 1) ? ("<strong>" . $tokenSplit[1] . "</strong>") : "");
                } else if( $tokenSplit[0] == "el" ) {
                  $erate_totals[$tokenSplit[0]] += $wapConfig ? 0 : $eratable;
                  $totalStr .= $currencies->format($eratable, true, $order->info['currency'], $order->info['currency_value']) . ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '') . ((count($tokenSplit) > 1) ? ("<strong>" . $tokenSplit[1] . "</strong>") : "");
                } else if( $tokenSplit[0] == "er" ) {
                  $thisVal = $eratable * $_SESSION["selected_erate_discount"];
                  $erate_totals[$tokenSplit[0]] += $wapConfig ? 0 : $thisVal;
                  $totalStr .= $currencies->format($thisVal, true, $order->info['currency'], $order->info['currency_value']) . ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '') . ((count($tokenSplit) > 1) ? ("<strong>" . $tokenSplit[1] . "</strong>") : "");
                } else if( $tokenSplit[0] == "ap" ) {
                  $thisVal = $total - ($eratable * $_SESSION["selected_erate_discount"]);
                  $erate_totals[$tokenSplit[0]] += $wapConfig ? 0 : $thisVal;
                  $totalStr .= $currencies->format($thisVal, true, $order->info['currency'], $order->info['currency_value']) . ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '') . ((count($tokenSplit) > 1) ? ("<strong>" . $tokenSplit[1] . "</strong>") : "");
                } else if( $tokenSplit[0] == "r" ) {
                  $totalStr .= (100 * $_SESSION["selected_erate_discount"]) . "%" . ((count($tokenSplit) > 1) ? ("<strong>" . $tokenSplit[1] . "</strong>") : "");
                } else {
                  $totalStr .= $tokenSplit[0] . ((count($tokenSplit) > 1) ? ("<strong>" . $tokenSplit[1] . "</strong>") : "");
                }
              }
              echo $totalStr . (($lineIndex < (count($lines) - 1)) ? "<br />" : "");
            }
            if( $wapConfig ) {
              $thisVal = $total - ($eratable * $_SESSION["selected_erate_discount"]);
              echo "<br><strong>eiNetwork pays:</strong> " . $currencies->display_price($thisVal, 0, 1) . "<br><strong>Due up front by library:</strong> " . $currencies->display_price(0, 0, 1);
            }
          } else {
              $ppt = $ppe * $order->products[$i]['qty'];
              echo $currencies->format($ppt, true, $order->info['currency'], $order->info['currency_value']) . ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '');
          }
        ?></td>
    </tr>
<?php
  }
?>
</table>
<hr />
<div id="orderTotals">
<?php
  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
    if( $order->products[0]['payment_plan'] ) {
      $title = "";
      $text = "";
      $lines = explode("<br />", $order->products[0]['payment_plan']);
      foreach( $lines as $thisLine ) {
          $tokens = explode("[[[", $thisLine);
          foreach( $tokens as $thisToken ) {
              $tokenSplit = explode("]]]", $thisToken);
              if( count($tokenSplit) > 1 ) {
                if( is_numeric($tokenSplit[0]) ) {
                  $text .= $currencies->display_price($order->totals[$i]['value'], 0, $tokenSplit[0]);
                } else if( $tokenSplit[0] == "r" ) {
                  $title .= (100 * $_SESSION["selected_erate_discount"]) . "%";
                } else {
                  $text .= $currencies->display_price($erate_totals[$tokenSplit[0]], 0, 1);
                }
                $title .= "<strong>" . $tokenSplit[1] . "</strong>";
              } else {
                $title .= "<strong>" . $tokenSplit[0] . "</strong>";
              }
          }
          $title .= "<br />";
          $text .= "<br />";
      }
      if( count($lines) > 1 ) {
          $title = substr($title, 0, -6);
          $text = substr($text, 0, -6);
      }
?>
     <div class="amount larger forward"><?php echo $text; ?></div>
     <div class="lineTitle larger forward"><?php echo $title; ?></div>
<?php
    } else { 
?>
     <div class="amount larger forward"><?php echo $order->totals[$i]['text'] ?></div>
     <div class="lineTitle larger forward"><?php echo $order->totals[$i]['title'] ?></div>
<?php
    }
?>
<br class="clearBoth" />
<?php
  }
?>

</div>

<?php
/**
 * Used to display any downloads associated with the cutomers account
 */
  if (DOWNLOAD_ENABLED == 'true') require($template->get_template_dir('tpl_modules_downloads.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_downloads.php');
?>

<?php
/**
 * Used to show the terms and conditions for this order
 */
  if( $order->info['terms'] ) {
?>
<h2 id="orderTermsStatus"><?php echo HEADING_ORDER_TERMS; ?></h2>
<div>Agreed upon <a href="<?php echo $order->info['terms']; ?>" target="_blank">Terms and Conditions</a> for Order #<?php echo $order->info["order_id"]; ?></div>
<br>

<?php
  }
?>

<?php
/**
 * Used to loop thru and display order status information
 */
if (sizeof($statusArray)) {
?>

<h2 id="orderHistoryStatus"><?php echo HEADING_ORDER_HISTORY; ?></h2>
<table id="myAccountOrdersStatus">
    <tr class="tableHeading">
        <th scope="col" id="myAccountStatusDate"><?php echo TABLE_HEADING_STATUS_DATE; ?></th>
        <th scope="col" id="myAccountStatus"><?php echo TABLE_HEADING_STATUS_ORDER_STATUS; ?></th>
        <th scope="col" id="myAccountStatusComments"><?php echo TABLE_HEADING_STATUS_COMMENTS; ?></th>
       </tr>
<?php
  $first = true; 
  foreach ($statusArray as $statuses) {
?>
    <tr>
        <td><?php echo zen_date_short($statuses['date_added']); ?></td>
        <td><?php echo $statuses['orders_status_name']; ?></td>
        <td>
<?php 
    if (!empty($statuses['comments'])) {
      if ($first) { 
         echo nl2br(zen_output_string_protected($statuses['comments']));
         $first = false; 
      } else {
         echo nl2br(zen_output_string($statuses['comments']));
      }
    }
?>
       </td> 
     </tr>
<?php
  }
?>
</table>
<?php } ?>

<br class="clearBoth" />
</div>
