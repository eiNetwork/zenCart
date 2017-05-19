<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=product_info.<br />
 * Displays details of a typical product
 *
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_product_info_display.php 19690 2011-10-04 16:41:45Z drbyte $
 */
 //require(DIR_WS_MODULES . '/debug_blocks/product_info_prices.php');
?>

<div class="centerColumn" id="productGeneral">

<?php require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_CATEGORY_ICON_DISPLAY)); ?>
<!--bof Product Name-->
<h1 id="productName" class="productGeneral"><?php echo $category_icon_display_name; ?></h1>
<!--eof Product Name-->

<div class="tie">
	<div class="tie-indent">
	

<div class="page-content">

<div class="wrapper">

<!--bof Form start-->
<?php echo zen_draw_form('cart_quantity', zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params(array('action')) . 'action=add_product'), 'post', 'enctype="multipart/form-data"') . "\n"; ?>
<!--eof Form start-->

<?php if ($messageStack->size('product_info') > 0) echo $messageStack->output('product_info'); ?>

<!--bof Category Icon -->

<?php /*
<?php if ($module_show_categories != 0) {?>
<?php

 // display the category icons
require($template->get_template_dir('/tpl_modules_category_icon_display.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_category_icon_display.php'); } 
<!--eof Category Icon -->
   */ ?>

<!--bof Prev/Next top position -->
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == 1 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
<?php
/**
 * display the product previous/next helper
 */
require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
<?php } ?>
<!--eof Prev/Next top position-->

<div class="fleft">
<!--bof Main Product Image -->
<?php
  if (zen_not_null($products_image)) {
  ?>
<?php
/**
 * display the main product image
 */
   require($template->get_template_dir('/tpl_modules_main_product_image.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_main_product_image.php'); ?>
<?php
  }
?>

<!--eof Main Product Image-->
<div class="clear"></div>
<?php
/**
 * Module Template:
 * Loaded by product-type template to display additional product images.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_additional_images.php 3215 2006-03-20 06:05:55Z birdbrain $
 */

  require(DIR_WS_MODULES . zen_get_module_directory('additional_images.php'));
 ?>
 <?php
 if ($flag_show_product_info_additional_images != 0 && $num_images > 0) {
  ?>
<div id="productAdditionalImages">
	<ul id="gallery">
<?php
  require($template->get_template_dir('tpl_columnar_display.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_columnar_display.php'); ?>
  </ul>
</div>
<?php 
  }
?>
</div>

<div class="fleft desc2">
<!--bof free ship icon  -->
	<?php if(zen_get_product_is_always_free_shipping($products_id_current) && $flag_show_product_info_free_shipping) { ?>
	<div id="freeShippingIcon"><?php echo TEXT_PRODUCT_FREE_SHIPPING_ICON; ?></div>
	<?php } ?>
	<!--eof free ship icon  -->
	<div class="name-type bot-border"><?php echo $products_name; ?></div>
	
	
	<!--bof Product details list  -->
	<?php if ( (($flag_show_product_info_model == 1 and $products_model != '') or ($flag_show_product_info_weight == 1 and $products_weight !=0) or ($flag_show_product_info_quantity == 1) or ($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name))) ) { ?>
	<ul class="instock">
	  <?php echo (($flag_show_product_info_model == 1 and $products_model !='') ? '<li>' . '<strong>'.TEXT_PRODUCT_MODEL.'</strong>' . $products_model . '</li>' : '') . "\n"; ?>
	  <?php echo (($flag_show_product_info_weight == 1 and $products_weight !=0) ? '<li>' . '<strong>'.TEXT_PRODUCT_WEIGHT.'</strong>' .  $products_weight . TEXT_PRODUCT_WEIGHT_UNIT . '</li>'  : '') . "\n"; ?>
	  <?php echo (($flag_show_product_info_quantity == 1) ? '<li>'.'<strong>'.TEXT_PRODUCT_QUANTITY.': </strong>' . $products_quantity . '</li>'  : '') . "\n"; ?>
	  <?php echo (($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name)) ? '<li>' . '<strong>'.TEXT_PRODUCT_MANUFACTURER.'</strong>' . $manufacturers_name . '</li>' : '') . "\n"; ?>
	</ul>
	<?php
	  }
	?>
	<!--eof Product details list -->
	
	<!--bof Product Price block -->
	<h2 id="productPrices" class="productGeneral">
		<span class="price-text">Price: &nbsp;</span>
	<?php
	// base price
	  if ($show_onetime_charges_description == 'true') {
		$one_time = '<span >' . TEXT_ONETIME_CHARGE_SYMBOL . TEXT_ONETIME_CHARGE_DESCRIPTION . '</span><br />';
	  } else {
		$one_time = '';
	  }
	  echo $one_time . ((zen_has_product_attributes_values((int)$_GET['products_id']) and $flag_show_product_info_starting_at == 1) ? TEXT_BASE_PRICE : '') . zen_get_products_display_price((int)$_GET['products_id']);
	?></h2>
	<!--eof Product Price block -->
	
	<!--bof Add to Cart Box -->
	<?php
	if ((count($_SESSION['customer_addresses']) == 0) or (CUSTOMERS_APPROVAL == 3 and TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE_SHOWROOM == '')) {
	  // do nothing
	} else {
	?>
				<?php
				$display_qty = (($flag_show_product_info_in_cart_qty == 1 and $_SESSION['cart']->in_cart($_GET['products_id'])) ? '<p>' . PRODUCTS_ORDER_QTY_TEXT_IN_CART . $_SESSION['cart']->get_quantity($_GET['products_id']) . '</p>' : '');
				if ($products_qty_box_status == 0 or $products_quantity_order_max== 1) {
				  // hide the quantity box and default to 1
				  $the_button = '<input type="hidden" name="cart_quantity" value="1" />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . '<span class="fright">'.zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT).'</span>';
				} else {
				  // show the quantity box
		$the_button = '<strong class="fleft text2">'.PRODUCTS_ORDER_QTY_TEXT . '&nbsp; &nbsp;<input type="text" class="qty" name="cart_quantity" value="' . (zen_get_buy_now_qty($_GET['products_id'])) . '" maxlength="6" size="8" />' . zen_get_products_quantity_min_units_display((int)$_GET['products_id']) . zen_draw_hidden_field('products_id', (int)$_GET['products_id']).'</strong>' . '&nbsp; &nbsp; &nbsp; <span class="buttonRow">'.zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT).'</span>';
				}
		$display_button = zen_get_buy_now_button($_GET['products_id'], $the_button);
	  ?>
	  <?php if ($display_qty != '' or $display_button != '') { ?>
		<div id="cartAdd">
		<?php
		  echo $display_button;
		  echo $display_qty;
				?>
		 </div>
	  <?php } // display qty and button ?>
	<?php } // CUSTOMERS_APPROVAL == 3 ?>
	<!--eof Add to Cart Box-->
	</div>
</div>






 <!--bof Product description -->
<?php if ($products_description != '') { ?>
<div id="productDescription" class="description biggerText"><strong>Details: </strong><?php echo stripslashes($products_description); ?></div>
<?php } ?>
<!--eof Product description -->





<!--bof Attributes Module -->
<?php
  if ($pr_attr->fields['total'] > 0) {
?>
<?php
/**
 * display the product atributes
 */
  require($template->get_template_dir('/tpl_modules_attributes.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_attributes.php'); ?>
<?php
  }
?>
<!--eof Attributes Module -->

<!--bof Quantity Discounts table -->
<?php
  if ($products_discount_type != 0) { ?>
<?php
/**
 * display the products quantity discount
 */
 require($template->get_template_dir('/tpl_modules_products_quantity_discounts.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_products_quantity_discounts.php'); ?>
<?php
  }
?>
<!--eof Quantity Discounts table -->

<!--bof Additional Product Images -->
<?php
/**
 * display the products additional images
 */
  require($template->get_template_dir('/tpl_modules_additional_images.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_additional_images.php'); ?>
<!--eof Additional Product Images -->

<div class="wrapper bot-border">



<!--bof Prev/Next bottom position -->
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == 2 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
<?php
/**
 * display the product previous/next helper
 */
 require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
<?php } ?>
<!--eof Prev/Next bottom position -->


<div class="prod-buttons">
<!--bof Reviews button and count-->
<?php
  if ($flag_show_product_info_reviews == 1) {
    // if more than 0 reviews, then show reviews button; otherwise, show the "write review" button
    if ($reviews->fields['count'] > 0 ) { ?>
<div id="productReviewLink" class="buttonRow forward"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS, zen_get_all_get_params()) . '">' . zen_image_button(BUTTON_IMAGE_REVIEWS, BUTTON_REVIEWS_ALT) . '</a>&nbsp; &nbsp;'; ?></div>

<?php } else { ?>
<div id="productReviewLink" class="buttonRow forward"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, zen_get_all_get_params(array())) . '">' . zen_image_button(BUTTON_IMAGE_WRITE_REVIEW, BUTTON_WRITE_REVIEW_ALT) . '</a>&nbsp; &nbsp;'; ?></div>

<?php
  }
}
?>
<!--eof Reviews button and count -->
</div>

</div>

<div class="text2">

<p class="reviewCount"><strong><?php echo ($flag_show_product_info_reviews_count == 1 ? TEXT_CURRENT_REVIEWS . ' ' . $reviews->fields['count'] : ''); ?></strong></p>

<!--bof Product date added/available-->
<?php
  if ($products_date_available > date('Y-m-d H:i:s')) {
    if ($flag_show_product_info_date_available == 1) {
?>
  <p id="productDateAvailable" class="productGeneral centeredContent"><?php echo sprintf(TEXT_DATE_AVAILABLE, zen_date_long($products_date_available)); ?></p>
<?php
    }
  } else {
    if ($flag_show_product_info_date_added == 1) {
?>
      <p id="productDateAdded" class="productGeneral centeredContent"><?php echo sprintf(TEXT_DATE_ADDED, zen_date_long($products_date_added)); ?></p>
<?php
    } // $flag_show_product_info_date_added
  }
?>
<!--eof Product date added/available -->

<!--bof Product URL -->
<?php
  if (zen_not_null($products_url)) {
    if ($flag_show_product_info_url == 1) {
?>
    <p id="productInfoLink" class="productGeneral centeredContent"><?php echo sprintf(TEXT_MORE_INFORMATION, zen_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($products_url), 'NONSSL', true, false)); ?></p>
<?php
    } // $flag_show_product_info_url
  }
?>
<!--eof Product URL -->

</div>

<!--bof also purchased products module-->
<?php require($template->get_template_dir('tpl_modules_also_purchased_products.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_also_purchased_products.php');?>
<!--eof also purchased products module-->

<!--bof Form close-->
</form>
<!--bof Form close-->




<div class="wrapper" id="social">
	
	<!-- bof Social Media Icons -->
	<?php
	  if (SOCIAL_MEDIA_STATUS == 1) {
		if (SOCIAL_POSITION == 'bottom' || SOCIAL_POSITION == 'both') {
		  echo '<div id="socialIcons" class="fright">';
		  echo social_media($products_id_current, $products_name);
		  echo '</div>';
		}
	  }
	?>
	<!-- eof Social Media Icons -->
	
</div>

</div>

</div>
</div>

</div>