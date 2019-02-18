<?php
/**
 * Common Template - tpl_header.php
 *
 * this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * make a directory /templates/my_template/privacy<br />
 * copy /templates/templates_defaults/common/tpl_footer.php to /templates/my_template/privacy/tpl_header.php<br />
 * to override the global settings and turn off the footer un-comment the following line:<br />
 * <br />
 * $flag_disable_header = true;<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_header.php 4813 2006-10-23 02:13:53Z drbyte $
 */
?>
<?php

	
  // Display all header alerts via messageStack:
  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }
  if (isset($_GET['error_message']) && zen_not_null($_GET['error_message'])) {
    echo htmlspecialchars(urldecode($_GET['error_message']));
  }
  if (isset($_GET['info_message']) && zen_not_null($_GET['info_message'])) {
    echo htmlspecialchars($_GET['info_message']);
  }

  // change their selected address
  if( isset($_POST["changeAddress"]) && $_POST["changeAddress"] ) {
    $_SESSION["selected_address_id"] = $_POST["address_id"];
    $get_erate_query = "SELECT erate_discount FROM address_book WHERE address_book_id = :addressID";
    $get_erate_query = $db->bindVars($get_erate_query, ':addressID', $_SESSION["selected_address_id"], 'integer');
    $grab_erate = $db->Execute($get_erate_query);
    $_SESSION["selected_erate_discount"] = $grab_erate->fields['erate_discount'];
    unset($_SESSION["availableCarts"]);
  }

  // grab the carts if needed
  if( $_SESSION["selected_address_id"] && !isset($_SESSION["available_carts"]) ) {
    $grab_cart_query = "SELECT customers_basket_new_id, basket_name 
                        FROM customers_basket_new WHERE address_book_id = :addressID ORDER BY customers_basket_new_id ASC";
    $grab_cart_query = $db->bindVars($grab_cart_query, ':addressID', $_SESSION["selected_address_id"], 'integer');
    if( $_SESSION["selected_address_id"] == MASTER_CART ) {
        $grab_cart_query = "SELECT customers_basket_new_id, basket_name 
                            FROM customers_basket_new WHERE address_book_id in (" . MASTER_CART;
        foreach( $_SESSION["customer_addresses"] as $thisAddress ) { 
            $grab_cart_query .= ",:addressID" . $thisAddress["ID"];
        }
        $grab_cart_query .= ") ORDER BY customers_basket_new_id ASC";
        foreach( $_SESSION["customer_addresses"] as $thisAddress ) { 
            $grab_cart_query = $db->bindVars($grab_cart_query, ':addressID' . $thisAddress["ID"],  $thisAddress["ID"], 'integer');
        }
    }
    $grab_cart = $db->Execute($grab_cart_query);
          
    // if they don't exist, create them
    if (!$grab_cart->RecordCount()) {
      $selectedName = "";
      foreach($_SESSION['customer_addresses'] as $address) {
        if( $address["ID"] == $_SESSION["selected_address_id"] ) {
          $selectedName = $address["library_name"];
        }
      }

      $create_cart_query = "INSERT INTO customers_basket_new (address_book_id, customers_id, basket_name)
                            VALUES (:addressID, :customersID, :basketName)";
      $create_cart_query = $db->bindVars($create_cart_query, ':addressID', $_SESSION["selected_address_id"], 'integer');
      $create_cart_query = $db->bindVars($create_cart_query, ':customersID', $_SESSION['customer_id'], 'integer');
      $create_cart_query = $db->bindVars($create_cart_query, ':basketName', $selectedName . " Cart", 'string');
      $db->Execute($create_cart_query);

      // rerun the grab cart query
      $grab_cart = $db->Execute($grab_cart_query);
    }

    $_SESSION["availableCarts"] = [];
    while(!$grab_cart->EOF) {
      $_SESSION["availableCarts"][] = ["customers_basket_new_id" => $grab_cart->fields['customers_basket_new_id'], 
                                       "basket_name" => $grab_cart->fields['basket_name']];
      $grab_cart->MoveNext();
    }
    $_SESSION["selectedCartID"] = count($_SESSION["availableCarts"]) ? $_SESSION["availableCarts"][0]["customers_basket_new_id"] : null;
    if( $_SESSION["selected_address_id"] == MASTER_CART ) {
      $_SESSION["selectedCartID"] = MASTER_CART;
    }

    $_SESSION['cart']->reset(false);
    $_SESSION['cart']->restore_contents();
  }

?>


<?php
if (!isset($flag_disable_header) || !$flag_disable_header) {
?>

    <div id="header">
		<div class="userInfo" <?php echo isset($_SESSION["customer_id"]) ? "" : " style='display:none'"; ?>>
			Signed in as <?php echo $_SESSION["customer_first_name"] . " " . $_SESSION["customer_last_name"]; ?>, <?php
				if( count($_SESSION["customer_addresses"]) == 0 ) {
					echo "browse-only mode";
				} else if( count($_SESSION["customer_addresses"]) == 1 ) {
					echo "shopping for " . $_SESSION["customer_addresses"][0]["library_name"];
				} else {
					?> shopping for 
					<form action="." id="addressChange" method="post" style="display:inline-block">
						<input type="hidden" name="changeAddress" value="true">
						<select name="address_id" onchange="document.getElementById('addressChange').submit()">
							<?php foreach( $_SESSION["customer_addresses"] as $thisAddress ) { ?>
								<option value="<?php echo $thisAddress["ID"] . "\"" . (($thisAddress["ID"] == $_SESSION["selected_address_id"]) ? " selected" : ""); ?>><?php echo $thisAddress["library_name"]; ?></option>
							<?php } ?>
<!-- -->
							<option value="<?php echo MASTER_CART;?>"<?php echo (($_SESSION["selected_address_id"] == MASTER_CART) ? " selected" : ""); ?>>All of my Carts</option>
<!-- -->
						</select>
					</form>
					<?php
				}
			?>
		</div>
		<div class="logo">
			<!-- ========== LOGO ========== -->
				<a href="<?php echo zen_href_link(FILENAME_DEFAULT);?>"><?php echo zen_image(DIR_WS_TEMPLATE.'images/logo.jpg'); ?></a>
			<!-- ========================== -->
		</div>
		<div class="menu">
			<!-- ========== MENU ========== -->
			<?php if (EZPAGES_STATUS_HEADER == '1' or (EZPAGES_STATUS_HEADER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) { ?>
				<?php require($template->get_template_dir('tpl_ezpages_bar_header.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_bar_header.php'); ?>
			<?php } ?>
			<!-- ========================== -->
		</div>
		<div class="navigation">
			<!-- ========== NAVIGATION LINKS ========== -->					
				<?php if ($_SESSION['customer_id']) { ?>
					<a href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGOFF; ?></a>
					<a href="<?php echo zen_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'); ?>">My Order History</a> 
				<?php
					  } else {
						if (true || STORE_STATUS == '0') {
				?>
					<a href="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGIN; ?></a>  
				<?php } } ?>  
				
				<?php if ($_SESSION['cart']->count_contents() != 0) { ?>
					<a href="<?php echo zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'); ?>"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a>
                                        <?php if ( false ) { ?>
						<a href="<?php echo zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><?php echo HEADER_TITLE_CHECKOUT; ?></a>
                                        <?php } ?>
				<?php } ?>
			<!-- ====================================== -->
		</div>
		<?php if ($_SESSION['customer_id'] && count($_SESSION["customer_addresses"])) { ?>
		<div class="cart">
			<!-- ========== SHOPPING CART ========== -->
				<?php 
					if ($_SESSION['cart']->count_contents() == 1){
						$cart_text = '<span class="st3">Now in your cart</span><a class="on"><span class="st2"><span class="count">' . $_SESSION['cart']->count_contents() . '</span> item</span></a>';
					} else {
						$cart_text = '<span class="st3">Now in your cart</span><a class="on"><span class="st2"><span class="count">' . $_SESSION['cart']->count_contents() . '</span> items</span></a>';
					}
					
				?>
				<?php echo $cart_text ?> 
				<?php require($template->get_template_dir('tpl_shopping_cart_header.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_shopping_cart_header.php'); 
						echo $content;?>
			<!-- =================================== -->
		</div>
		<?php } else if($_SESSION['customer_id']) { ?>
                <div class="signedOutCart">Browse-only mode</div>
		<?php } else { ?>
                <div class="signedOutCart">Sign in to see your cart</div>
		<?php } ?>
		<div id="head-search">
			<!-- ========== SEARCH ========== -->
				<?php echo zen_draw_form('quick_find_header', zen_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get');?>
				<div>
				<?php 
					echo zen_draw_hidden_field('main_page',FILENAME_ADVANCED_SEARCH_RESULT);
					echo zen_draw_hidden_field('search_in_description', '1') . zen_hide_session_id();
				?>
				<?php echo zen_draw_input_field('keyword', '', 'size="18" class="input1" maxlength="100" style="width: ' . ($column_width-30) . 'px"');?>
			   <span class="input2"><?php echo zen_image_submit ('search.gif', HEADER_SEARCH_BUTTON);?></span>
					</div>
				</form>
			<!-- ============================ -->
		</div>
<?php if (false) { ?>
		<div class="currencies">
			<!-- ========== CURRENCIES ========= -->
				<?php echo zen_draw_form('currencies', zen_href_link(basename(ereg_replace('.php','', $PHP_SELF)), '', $request_type, false), 'get'); ?>
					<div>
						<span class="label"><?php echo BOX_HEADING_CURRENCIES;?>: &nbsp;</span>
				
						<?php
							if (isset($currencies) && is_object($currencies)) {
						
							  reset($currencies->currencies);
							  $currencies_array = array();
							  while (list($key, $value) = each($currencies->currencies)) {
								$currencies_array[] = array('id' => $key, 'text' => $value['title']);
							  }
						
							  $hidden_get_variables = '';
							  reset($_GET);
							  while (list($key, $value) = each($_GET)) {
								if ( ($key != 'currency') && ($key != zen_session_name()) && ($key != 'x') && ($key != 'y') ) {
								  $hidden_get_variables .= zen_draw_hidden_field($key, $value);
								}
							  }
							}
						?>
						<?php echo zen_draw_pull_down_menu('currency', $currencies_array, $_SESSION['currency'], 'class="select" onchange="this.form.submit();"') . $hidden_get_variables . zen_hide_session_id()?>
					</div>
				</form>
			<!-- ====================================== -->
		</div>
<?php } ?>
		<div class="horizontal-cat">
		<?php if ($_SESSION['customer_id']) { ?>
			<!--bof-drop down menu display-->
				<?php require($template->get_template_dir('tpl_drop_menu.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_drop_menu.php');?>
			<!--eof-drop down menu display-->
		<?php } ?>
		</div>
	</div>
    
	<?php 
		if (HEADER_SALES_TEXT != '' || (SHOW_BANNERS_GROUP_SET2 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET2))) {
			if (HEADER_SALES_TEXT != '') {
	?>
        		<div id="tagline"><?php echo HEADER_SALES_TEXT;?></div>
	<?php
    		}
		}
	?>
   




	<!-- ========== CATEGORIES TABS ========= -->
		<?php require($template->get_template_dir('tpl_modules_categories_tabs.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_categories_tabs.php'); ?>
	<!-- ==================================== -->
                
	<?php 
		$sql = "select banners_id from " . TABLE_BANNERS . " where status = 1 order by banners_sort_order";
		$banners_all = $db->Execute($sql);
		while (!$banners_all->EOF) {
	?>
			<div><?php echo zen_display_banner('static', $banners_all->fields['banners_id']);?></div><br>
	<?php 
			$banners_all->MoveNext();
		}
	?>
    
<?php } ?>