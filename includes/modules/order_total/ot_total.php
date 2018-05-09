<?php
/**
 * ot_total order-total module
 *
 * @package orderTotal
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Author: DrByte  Thu Apr 2 14:27:45 2015 -0400 Modified in v1.5.5 $
 */
  class ot_total {
    var $title, $output;

    function __construct() {
      $this->code = 'ot_total';
      $this->title = MODULE_ORDER_TOTAL_TOTAL_TITLE;
      $this->description = MODULE_ORDER_TOTAL_TOTAL_DESCRIPTION;
      $this->sort_order = MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies;
      if( $order->products[0]['payment_plan'] ) {
        $title = "";
        $text = "";
        $lines = explode("<br />", $order->products[0]['payment_plan']);
        foreach( $lines as $thisLine ) {
            $tokens = explode("[[[", $thisLine);
            foreach( $tokens as $thisToken ) {
                $tokenSplit = explode("]]]", $thisToken);
                if( count($tokenSplit) > 1 ) {
                  $text .= $currencies->format($order->info['total'] * $tokenSplit[0], true, $order->info['currency'], $order->info['currency_value']);
                  $title .= $tokenSplit[1];
                } else {
                  $title .= $tokenSplit[0];
                }
            }
            $title .= "<br />";
            $text .= "<br />";
        }
        if( count($lines) > 1 ) {
            $title = substr($title, 0, -6);
            $text = substr($text, 0, -6);
        }

        $this->output[] = array('title' => $title,
                                'text' => $text,
                                'RTItitle' => '',
                                'RTItext' => '',
                                'value' => $order->info['total']);
      } else if( $order->info['shipping_cost'] || $order->info['RTI'] ) {
        $this->output[] = array('title' => 'Sub-Total:<br />Shipping & Handling:<br />' . $this->title . ':',
                                'text' => $currencies->format($order->info['total'] - $order->info['shipping_cost'], true, $order->info['currency'], $order->info['currency_value']) . "<br />" . 
                                          $currencies->format($order->info['shipping_cost'], true, $order->info['currency'], $order->info['currency_value']) . "<br />" . 
                                          $currencies->format($order->info['total'], true, $order->info['currency'], $order->info['currency_value']) . "<br />",
                                'RTItitle' => 'Sub-Total:<br />RTI Imaging Services:<br />' . $this->title . ':',
                                'RTItext' => $currencies->format($order->info['cost'] - $order->info['RTI'], true, $order->info['currency'], $order->info['currency_value']) . "<br />" . 
                                          $currencies->format($order->info['RTI'], true, $order->info['currency'], $order->info['currency_value']) . "<br />" . 
                                          $currencies->format($order->info['cost'], true, $order->info['currency'], $order->info['currency_value']) . "<br />",
                                'value' => $order->info['total']);
      } else {
        $this->output[] = array('title' => $this->title . ':',
                                'text' => $currencies->format($order->info['total'], true, $order->info['currency'], $order->info['currency_value']),
                                'RTItitle' => '',
                                'RTItext' => '',
                                'value' => $order->info['total']);
      }
    }

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_TOTAL_STATUS'");
        $this->_check = $check_query->RecordCount();
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_TOTAL_STATUS', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER');
    }

    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('This module is installed', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', '', '6', '1','zen_cfg_select_option(array(\'true\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '999', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      global $db, $messageStack;
      if (!isset($_GET['override']) && $_GET['override'] != '1') {
        $messageStack->add('header', ERROR_MODULE_REMOVAL_PROHIBITED . $this->code);
        return false;
      }
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
