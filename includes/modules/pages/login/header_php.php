<?php
/**
 * Login Page
 *
 * @package page
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Author: DrByte  Mon Oct 19 10:48:31 2015 -0400 Modified in v1.5.5 $
 */

// This should be first line of the script:
$zco_notifier->notify('NOTIFY_HEADER_START_LOGIN');

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
if ($session_started == false) {
  zen_redirect(zen_href_link(FILENAME_COOKIE_USAGE));
}

// if the customer is logged in already, redirect them to the My account page
if (isset($_SESSION['customer_id']) and $_SESSION['customer_id'] != '') {
  zen_redirect(zen_href_link(FILENAME_ACCOUNT, '', 'SSL'));
}

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_CREATE_ACCOUNT));

$error = false;
if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
  $email_address = zen_db_prepare_input($_POST['email_address']);

  if( substr(trim($email_address), -14) != "@einetwork.net" ) {
    $email_address = trim($email_address) . "@einetwork.net";
  }
  $CLP_email_address = substr($email_address, 0, -14) . "@carnegielibrary.org";

  $password = zen_db_prepare_input($_POST['password']);
  $loginAuthorized = false;

  /* Privacy-policy-read does not need to be checked during "login"
  if (DISPLAY_PRIVACY_CONDITIONS == 'true') {
  if (!isset($_POST['privacy_conditions']) || ($_POST['privacy_conditions'] != '1')) {
  $error = true;
  $messageStack->add('create_account', ERROR_PRIVACY_STATEMENT_NOT_ACCEPTED, 'error');
  }
  }
  */

    // Check if email exists
    $check_customer_query = "SELECT customers_id, customers_firstname, customers_lastname, customers_password,
                                    customers_email_address, customers_default_address_id,
                                    customers_authorization, customers_referral
                           FROM " . TABLE_CUSTOMERS . "
                           WHERE customers_email_address = :emailAddress";

    $check_customer_query  =$db->bindVars($check_customer_query, ':emailAddress', $email_address, 'string');
    $check_customer = $db->Execute($check_customer_query);

    if ($check_customer->RecordCount() && $check_customer->fields['customers_authorization'] == '4') {
      // this account is banned
      $zco_notifier->notify('NOTIFY_LOGIN_BANNED');
      $messageStack->add('login', TEXT_LOGIN_BANNED);
    } else {

      /*** REPLACED DATABASE CHECK WITH LDAP ***\
      $dbPassword = $check_customer->fields['customers_password'];

      // Check whether the password is good
      if (zen_validate_password($password, $dbPassword)) {
        $loginAuthorized = true;
        if (password_needs_rehash($dbPassword, PASSWORD_DEFAULT)) {
          $newPassword = zcPassword::getInstance(PHP_VERSION)->updateNotLoggedInCustomerPassword($password, $email_address);
        }
      }
      \*** REPLACED DATABASE CHECK WITH LDAP ***/
      $ldap = ldap_connect("208.89.32.143") or die("can't connect");
      ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
      $loginAuthorized = @ldap_bind($ldap, $email_address, $password);      
      /*** REPLACED DATABASE CHECK WITH LDAP ***/

      $zco_notifier->notify('NOTIFY_PROCESS_3RD_PARTY_LOGINS', $email_address, $password, $loginAuthorized);

      if (!$loginAuthorized) {
        $error = true;
        $messageStack->add('login', TEXT_LOGIN_ERROR);
      } else {
        // grab some ldap info
        $ldap_dn = "DC=einetwork,DC=net";
        $results = ldap_search($ldap, $ldap_dn,"(|(mail=$email_address)(mail=$CLP_email_address))",array("givenName","sn","memberOf"));
	$entries = ldap_get_entries($ldap, $results);

        // if they don't exist, create them
        if (!$check_customer->RecordCount()) {
          $firstName = $entries[0]["givenname"][0];
          $lastName = $entries[0]["sn"][0];

          $create_customer_query = "INSERT INTO customers (customers_firstname, customers_lastname, customers_email_address)
                                    VALUES (:firstName, :lastName, :emailAddress)";
          $create_customer_query = $db->bindVars($create_customer_query, ':firstName', $firstName, 'string');
          $create_customer_query = $db->bindVars($create_customer_query, ':lastName', $lastName, 'string');
          $create_customer_query = $db->bindVars($create_customer_query, ':emailAddress', $email_address, 'string');
          $db->Execute($create_customer_query);

          // rerun the check customer query
          $check_customer = $db->Execute($check_customer_query);
        }

        if (SESSION_RECREATE == 'True') {
          zen_session_recreate();
        }

/***
        $check_country_query = "SELECT entry_country_id, entry_zone_id
                              FROM " . TABLE_ADDRESS_BOOK . "
                              WHERE customers_id = :customersID
                              AND address_book_id = :addressBookID";

        $check_country_query = $db->bindVars($check_country_query, ':customersID', $check_customer->fields['customers_id'], 'integer');
        $check_country_query = $db->bindVars($check_country_query, ':addressBookID', $check_customer->fields['customers_default_address_id'], 'integer');
        $check_country = $db->Execute($check_country_query);
***/

        $_SESSION['customer_id'] = $check_customer->fields['customers_id'];
/***
        $_SESSION['customer_default_address_id'] = $check_customer->fields['customers_default_address_id'];
        $_SESSION['customers_authorization'] = $check_customer->fields['customers_authorization'];
***/
        $_SESSION['customer_first_name'] = $check_customer->fields['customers_firstname'];
        $_SESSION['customer_last_name'] = $check_customer->fields['customers_lastname'];
/***
        $_SESSION['customer_country_id'] = $check_country->fields['entry_country_id'];
        $_SESSION['customer_zone_id'] = $check_country->fields['entry_zone_id'];
***/

        // load up all of the locations they are allowed to shop for
        $_SESSION['customer_addresses'] = [];
        $groupNames = '"Dummy Security Group Name"';
        foreach( $entries[0]["memberof"] as $group ) {
          $startIndex = strpos($group, "CN=") + 3;
          if( strlen($group) > $startIndex ) {
            $endIndex = strpos($group, ",OU=", $startIndex);
            if( !$endIndex ) {
              $endIndex = strpos($group, ",DC=", $startIndex);
            }
            if( $endIndex ) {
              $groupNames .= ',"' . substr($group, $startIndex, $endIndex - $startIndex) . '"';
            }
          }
        }

        // I'm not using bindVars below because I couldn't find a way to use it to insert an array of strings.  This is less secure than doing it the bindVars way, but
        // being that everything we're feeding in there is a group name coming from Active directory, I think we can be reasonably certain it contains no SQL injection. -- BJP
        $checkGroupNameQuery = "SELECT address_book.address_book_id as ID, 1 as modify_cart, if(security_level='Order',1,0) as approve_cart, 1 as view_orders, 
                                       entry_company as library_name, address_book.librarycode, address_book.library_system_id 
                                FROM user_authorization LEFT JOIN library_system USING (library_system_id) 
                                LEFT JOIN address_book ON (user_authorization.address_book_id=address_book.address_book_id OR 
                                                           (address_book.library_system_id=library_system.library_system_id AND user_authorization.address_book_id=0))
                                WHERE security_group_name IN (" . $groupNames . ")
                                ORDER BY library_name";
        $checkGroupName = $db->Execute($checkGroupNameQuery);

        while(!$checkGroupName->EOF) {
          // see if it's new to the list
          $foundLocation = -1;
          foreach($_SESSION['customer_addresses'] as $index => $address) {
            if( $address["ID"] == $checkGroupName->fields['ID'] ) {
              $foundLocation = $index;
            }
          }

          // if so, add it
          if( $foundLocation == -1 ) {
            $_SESSION['customer_addresses'][] = ["ID" => $checkGroupName->fields['ID'], "library_name" => $checkGroupName->fields['library_name'], 
                                                 "modify_cart" => $checkGroupName->fields['modify_cart'], "approve_cart" => $checkGroupName->fields['approve_cart'], 
                                                 "view_orders" => $checkGroupName->fields['view_orders'], "librarycode" => $checkGroupName->fields['librarycode']];
          // if not, update it
          } else {
            if( $_SESSION['customer_addresses'][$foundLocation]["modify_cart"] == 0 ) {
              $_SESSION['customer_addresses'][$foundLocation]["modify_cart"] = $checkGroupName->fields['modify_cart'];
            }
            if( $_SESSION['customer_addresses'][$foundLocation]["approve_cart"] == 0 ) {
              $_SESSION['customer_addresses'][$foundLocation]["approve_cart"] = $checkGroupName->fields['approve_cart'];
            }
            if( $_SESSION['customer_addresses'][$foundLocation]["view_orders"] == 0 ) {
              $_SESSION['customer_addresses'][$foundLocation]["view_orders"] = $checkGroupName->fields['view_orders'];
            }
          }
          $checkGroupName->MoveNext();
        }
//echo "<!--" . json_encode($_SESSION['customer_addresses']) . "-->";
/***
        $grab_addresses_query = "SELECT address_book_id as ID, modify_cart, approve_cart, view_orders, entry_company as library_name, librarycode 
                                 FROM customers_address JOIN address_book USING (address_book_id) 
                                 WHERE customers_address.customers_id = :customersID";
        $grab_addresses_query = $db->bindVars($grab_addresses_query, ':customersID', $check_customer->fields['customers_id'], 'integer');
        $grab_addresses = $db->Execute($grab_addresses_query);
        $_SESSION['customer_addresses'] = [];
        while(!$grab_addresses->EOF) {
          $_SESSION['customer_addresses'][] = ["ID" => $grab_addresses->fields['ID'], "library_name" => $grab_addresses->fields['library_name'], 
                                               "modify_cart" => $grab_addresses->fields['modify_cart'], "approve_cart" => $grab_addresses->fields['approve_cart'], 
                                               "view_orders" => $grab_addresses->fields['view_orders'], "librarycode" => $grab_addresses->fields['librarycode']];
          $grab_addresses->MoveNext();
        }
        $_SESSION["selected_librarycode"] = count($_SESSION['customer_addresses']) ? $_SESSION['customer_addresses'][0]['librarycode'] : null;
***/
        $_SESSION["selected_address_id"] = count($_SESSION['customer_addresses']) ? $_SESSION['customer_addresses'][0]['ID'] : null;

        // enforce db integrity: make sure related record exists
        $sql = "SELECT customers_info_date_of_last_logon FROM " . TABLE_CUSTOMERS_INFO . " WHERE customers_info_id = :customersID";
        $sql = $db->bindVars($sql, ':customersID',  $_SESSION['customer_id'], 'integer');
        $result = $db->Execute($sql);
        if ($result->RecordCount() == 0) {
          $sql = "insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id) values (:customersID)";
          $sql = $db->bindVars($sql, ':customersID',  $_SESSION['customer_id'], 'integer');
          $db->Execute($sql);
        }

        // update login count
        $sql = "UPDATE " . TABLE_CUSTOMERS_INFO . "
              SET customers_info_date_of_last_logon = now(),
                  customers_info_number_of_logons = IF(customers_info_number_of_logons, customers_info_number_of_logons+1, 1)
              WHERE customers_info_id = :customersID";

        $sql = $db->bindVars($sql, ':customersID',  $_SESSION['customer_id'], 'integer');
        $db->Execute($sql);
        $zco_notifier->notify('NOTIFY_LOGIN_SUCCESS');

        // bof: contents merge notice
        // save current cart contents count if required
        if (SHOW_SHOPPING_CART_COMBINED > 0) {
          $zc_check_basket_before = $_SESSION['cart']->count_contents();
        }

        // bof: not require part of contents merge notice
        // restore cart contents
        $_SESSION['cart']->restore_contents();
        // eof: not require part of contents merge notice

        // check current cart contents count if required
        $zc_check_basket_after = $_SESSION['cart']->count_contents();
        if (($zc_check_basket_before != $zc_check_basket_after) && $_SESSION['cart']->count_contents() > 0 && SHOW_SHOPPING_CART_COMBINED > 0) {
          if (SHOW_SHOPPING_CART_COMBINED == 2) {
            // warning only do not send to cart
            $messageStack->add_session('header', WARNING_SHOPPING_CART_COMBINED, 'caution');
          }
          if (SHOW_SHOPPING_CART_COMBINED == 1) {
            // show warning and send to shopping cart for review
            if (!(isset($_GET['gv_no']))) {
              $messageStack->add_session('shopping_cart', WARNING_SHOPPING_CART_COMBINED, 'caution');
              zen_redirect(zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
            } else {
              $messageStack->add_session('header', WARNING_SHOPPING_CART_COMBINED, 'caution');
            }
          }
        }
        // eof: contents merge notice

        if (sizeof($_SESSION['navigation']->snapshot) > 0) {
          //    $back = sizeof($_SESSION['navigation']->path)-2;
          $origin_href = zen_href_link($_SESSION['navigation']->snapshot['page'], zen_array_to_string($_SESSION['navigation']->snapshot['get'], array(zen_session_name())), $_SESSION['navigation']->snapshot['mode']);
          //            $origin_href = zen_back_link_only(true);
          $_SESSION['navigation']->clear_snapshot();
          zen_redirect($origin_href);
        } else {
          zen_redirect(zen_href_link(FILENAME_DEFAULT, '', $request_type));
        }
      }
    }
}
if ($error == true) {
  $zco_notifier->notify('NOTIFY_LOGIN_FAILURE');
}

$breadcrumb->add(NAVBAR_TITLE);

// Check for PayPal express checkout button suitability:
$paypalec_enabled = (defined('MODULE_PAYMENT_PAYPALWPP_STATUS') && MODULE_PAYMENT_PAYPALWPP_STATUS == 'True' && defined('MODULE_PAYMENT_PAYPALWPP_ECS_BUTTON') && MODULE_PAYMENT_PAYPALWPP_ECS_BUTTON == 'On');
// Check for express checkout button suitability (must have cart contents, value > 0, and value < 10000USD):
$ec_button_enabled = ($paypalec_enabled && $_SESSION['cart']->count_contents() > 0 && $_SESSION['cart']->total > 0 && $currencies->value($_SESSION['cart']->total, true, 'USD') <= 10000);


// This should be last line of the script:
$zco_notifier->notify('NOTIFY_HEADER_END_LOGIN');
