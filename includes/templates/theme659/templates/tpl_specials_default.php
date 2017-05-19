<?php
/**
 * Page Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_ssl_check_default.php 2540 2005-12-11 07:55:22Z birdbrain $
 */
?>
<div class="centerColumn" id="specialsListing"><h1 id="specialsListingHeading"><?php echo $breadcrumb->last(); ?></h1>

<?php
  if (($specials_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

<div id="specialsListingTopNumber" class="navSplitPagesResult back"><?php echo $specials_split->display_count(TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></div>
<div id="specialsListingTopLinks" class="navSplitPagesLinks forward"><?php echo TEXT_RESULT_PAGE . ' ' . $specials_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'x', 'y', 'main_page'))); ?></div>
<br class="clearBoth" />
<?php
  } // split page
?>
<!-- bof: specials -->
<?php
/**
 * require the list_box_content template to display the products
 */
?>
 
 
 
 
 
 
 
 
<?php
/**
 * Specials
 *
 * @package page
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: main_template_vars.php 6912 2007-09-02 02:23:45Z drbyte $
 */

if (MAX_DISPLAY_SPECIAL_PRODUCTS > 0 ) {
  $specials_query_raw = "SELECT p.products_id, p.products_image, pd.products_name, pd.products_description,
                          p.master_categories_id
                         FROM (" . TABLE_PRODUCTS . " p
                         LEFT JOIN " . TABLE_SPECIALS . " s on p.products_id = s.products_id
                         LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                         WHERE p.products_id = s.products_id and p.products_id = pd.products_id and p.products_status = '1'
                         AND s.status = 1
                         AND pd.language_id = :languagesID
                         ORDER BY s.specials_date_added DESC";

  $specials_query_raw = $db->bindVars($specials_query_raw, ':languagesID', $_SESSION['languages_id'], 'integer');
  $specials_split = new splitPageResults($specials_query_raw, MAX_DISPLAY_SPECIAL_PRODUCTS);
  $specials = $db->Execute($specials_split->sql_query);
  $row = 0;
  $col = 0;
  $list_box_contents = array();
  $title = '';

  $num_products_count = $specials->RecordCount();
  if ($num_products_count) {
    if ($num_products_count < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS || SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS==0 ) {
      $col_width = floor(100/$num_products_count);
    } else {
      $col_width = floor(100/SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS);
    }

    $list_box_contents = array();
    while (!$specials->EOF) {
	
		$products_img = '<a href="' . zen_href_link(zen_get_info_page($specials->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($specials->fields["master_categories_id"]) . '&products_id=' . $specials->fields['products_id']) . '">' . (($specials->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : zen_image(DIR_WS_IMAGES . $specials->fields['products_image'], $specials->fields['products_name'], MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT) . '</a>');
		
		$products_name = '<a class="name" href="' . zen_href_link(zen_get_info_page($specials->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($specials->fields["master_categories_id"]) . '&products_id=' . $specials->fields['products_id']) . '">' . zen_get_products_name($specials->fields['products_id']) . '</a>';
		
		$products_desc = substr(strip_tags($specials->fields['products_description']), 0, 60) . '...';
		
		$products_price = '<strong>' . zen_get_products_display_price($specials->fields['products_id']) . '</strong>';
		
		$products_butt = '<a href="' . zen_href_link(zen_get_info_page($specials->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($specials->fields["master_categories_id"]) . '&products_id=' . $specials->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS, BUTTON_GOTO_PROD_DETAILS_ALT) . '</a>';
		
		$products_butt2 = '<a href="' . zen_href_link(FILENAME_SPECIALS, zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials->fields["products_id"]) . '">' . zen_image_button(BUTTON_IMAGE_ADD_TO_CART, BUTTON_IN_CART_ALT) . '</a>';
		
		$img_col_w = SMALL_IMAGE_WIDTH + 15;
	
	
	
	if (SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS > 1 && $num_products_count > 1) {
	
		if ($col > 2 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS) {
			$tm_param = 'style="margin-left:15px;"';
			
		} elseif ($col > 1 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = 'style="margin-left:10px; margin-right:0px;"';
		}
		elseif ($col > 0 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = 'style="margin-left:5px; margin-right:0px;"';
		}			
		 else {
			$tm_param = '';
		}
	
    $list_box_contents[$row][$col] = array('params' =>'class="specialsListBoxContents centeredContent back"' . ' ' . 'style="width:' . $col_width . '%;"',
    'text' => 
	
			'<div class="product-col" ' . $tm_param . '>
				<div class="img">
					' . $products_img . '
				</div>
				<div class="prod-info">
					' . $products_name . '
					<div class="wrapper">
						<div class="price">
							' . str_replace('&nbsp;', '', $products_price) . '
						</div>
						<div class="button">' . $products_butt2 . '</div>
					</div>
				</div>
			</div>'
					
			);
	
	} else {

    $list_box_contents[$row][$col] = array('params' =>'class="specialsListBoxContents centeredContent back"' . ' ' . 'style="width:' . $col_width . '%;"',
    'text' => 
	
			'<div class="product-col" ' . $tm_param . '>
				<div class="img">
					' . $products_img . '
				</div>
				<div class="prod-info">
					' . $products_name . '
					<div class="wrapper">
						<div class="price">
							' . str_replace('&nbsp;', '', $products_price) . '
						</div>
						<div class="button">' . $products_butt2 . '</div>
					</div>
				</div>
			</div>'
					
			);
	
	}


      $col ++;
      if ($col > (SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS - 1)) {
        $col = 0;
        $row ++;
      }
      $specials->MoveNext();
    }

  }
}
?>
 
 
 
 
 
 
<?php 
  require($template->get_template_dir('tpl_columnar_display.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_columnar_display.php');
?>
<!-- eof: specials -->
<?php
  if (($specials_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

<div class="wrapper">
<div id="specialsListingBottomNumber" class="navSplitPagesResult back"><?php echo $specials_split->display_count(TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></div>
<div id="specialsListingBottomLinks" class="navSplitPagesLinks forward"><?php echo TEXT_RESULT_PAGE . ' ' . $specials_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'x', 'y', 'main_page'))); ?></div>
</div>
<br class="clearBoth" />
<?php
  } // split page
?>
<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
</div>