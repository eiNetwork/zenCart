<?php
/**
* Template designed by 12leaves.com
* 12leaves.com - Free ecommerce templates and design services
*
 * @copyright Copyright 2009-2010 12leaves.com
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */
 
  $content ="";
  $product_amount = 0;
  
  if ($_GET['main_page']) {
	$current_page = $_GET['main_page'];
  } else {
  	$current_page = FILENAME_DEFAULT;
  }

  if( $_SESSION['cart']->get_products()['multiCart'] ) {
  	$content .='<div class="none">Multiple Carts, click for details<br><br><div class="btns"><a href="' . zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . ' "><span class="cssButton">' . BOX_HEADING_SHOPPING_CART . '</span></a></div></div>';
  } else if ($_SESSION['cart']->count_contents() > 0) {
    $products = $_SESSION['cart']->get_products();
	$content .='<div class="cart-down">';	
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
		$product_amount = $products[$i]['quantity'] + $product_amount;
		
		$content .= '<div class="cart_item">
						<div class="left-info">
							<a class="cart-img" href="' . zen_href_link(zen_get_info_page($products[$i]['id']), 'products_id=' . $products[$i]['id']) . '"><img src="'. DIR_WS_IMAGES. '' . $products[$i]['image'] . '"/></a>
						</div>
						<div class="center-info">
							<a class="cart-name" href="' . zen_href_link(zen_get_info_page($products[$i]['id']), 'products_id=' . $products[$i]['id']) . '">' . $products[$i]['name'] . '</a><br />
							<span class="model">' . $products[$i]['model'] . '</span>
							<span class="quantity">' . $products[$i]['quantity'] . ' <em class="spr">x</em> </span><span class="cart-price">' . $currencies->format($products[$i]['final_price']) . '</span>
						</div>';
        $content .= '<a class="delete" href="' . zen_href_link($current_page, zen_get_all_get_params() . 'action=remove_product&product_id=' . $products[$i]['id']) . '"><img src="'. DIR_WS_TEMPLATE . 'images/icons/' . '' . ICON_IMAGE_TRASH2 . '"/> </a>';
		$content .= '</div>';
	}
	
	$content .= '<div class="cart-bottom"><span class="total"><strong>Total:</strong>&nbsp;' .'<span>' . $currencies->format($_SESSION['cart']->show_total()) . '</span></span><div class="btns"><a href="' . zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . ' "><span class="cssButton">' . BOX_HEADING_SHOPPING_CART . '</span></a>';
        $canCheckout = false;
        foreach($_SESSION['customer_addresses'] as $address) {
          if( $address["ID"] == $_SESSION["selected_address_id"] ) {
            $canCheckout = $address["approve_cart"];
          }
        }
        if( false && $canCheckout ) {
          $content .= '<a href="' . zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_CHECKOUT, BUTTON_CHECKOUT_ALT) . '</a>';
        }
	$content .= '</div></div></td></div>';
  }
  else{
  	$content .='<div class="none"> ' . BOX_SHOPPING_CART_EMPTY . '</div>';
  } 
  
  
 /* if ($_SESSION['cart']->count_contents() > 0) {
    $content .= HEADER_CART_SUBTOTAL .'<span>' . $currencies->format($_SESSION['cart']->show_total()) . '</span>';
  }*/
?>
