<?php

/**
 * @copyright Copyright 2003-2020 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Steve 2020 Mar 30 Modified in v1.5.7 $
 */

define('HEADING_TITLE', 'Categories / Products');
define('HEADING_TITLE_GOTO', 'Go To:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categories / Products');
define('TABLE_HEADING_CATEGORIES_SORT_ORDER', 'Sort');

define('TABLE_HEADING_PRICE','Price/Special/Sale');
define('TABLE_HEADING_QUANTITY','Quantity');

define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_STATUS', 'Status');

define('TEXT_CATEGORIES', 'Categories:');
define('TEXT_SUBCATEGORIES', 'Subcategories:');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Price:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Average Rating:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Quantity:');
define('TEXT_PRODUCT_TYPE_NAME', 'Product Type:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_DATE_AVAILABLE', 'Date Available:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Please insert a new category or product in this level.');
define('TEXT_PRODUCT_MORE_INFORMATION', 'For more information, please visit this products <a href="http://%s" target="blank">webpage</a>.');
define('TEXT_PRODUCT_DATE_ADDED', 'This product was added to our catalog on %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'This product will be in stock on %s.');

define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_CATEGORIES_ID', 'Category ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_EDIT_SORT_ORDER', 'Sort Order:');

define('TEXT_INFO_COPY_TO_INTRO', 'Please choose a new category you wish to copy this product to');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Current Categories: ');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'New Category');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Edit Category');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Delete Category');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Move Category');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Delete Product');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Move Product');
define('TEXT_INFO_HEADING_COPY_TO', 'Copy To');

define('TEXT_DELETE_CATEGORY_INTRO', 'Are you sure you want to delete this category?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Are you sure you want to permanently delete this product?<br /><br /><strong>Warning:</strong> On Linked Products<br />1 Make sure the Master Category has been changed if you are deleting the Product from the Master Category<br />2 Check the checkbox for the Category to Delete the Product from');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s (child-)categories still linked to this category!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> There are %s products still linked to this category!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE', 'Move <b>%s</b> to:');

define('TEXT_NEW_CATEGORY_INTRO', 'Please fill out the following information for the new category');
define('TEXT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_SORT_ORDER', 'Sort Order:');

define('TEXT_PRODUCTS_STATUS', 'Products Status:');
define('TEXT_PRODUCTS_VIRTUAL', 'Product is Virtual:');
define('TEXT_PRODUCTS_IS_ALWAYS_FREE_SHIPPING', 'Always Free Shipping:');
define('TEXT_PRODUCTS_QTY_BOX_STATUS', 'Products Quantity Box Shows:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Date Available:');
define('TEXT_PRODUCT_AVAILABLE', 'Enabled');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Disabled');
define('TEXT_PRODUCT_IS_VIRTUAL', 'Yes, Skip Shipping Address');
define('TEXT_PRODUCT_NOT_VIRTUAL', 'No, Shipping Address Required');
define('TEXT_PRODUCT_IS_ALWAYS_FREE_SHIPPING', 'Yes, Always Free Shipping');
define('TEXT_PRODUCT_NOT_ALWAYS_FREE_SHIPPING', 'No, Normal Shipping Rules');
define('TEXT_PRODUCT_SPECIAL_ALWAYS_FREE_SHIPPING', 'Special, Product/Download Combo Requires a Shipping Address');

define('TEXT_PRODUCTS_QTY_BOX_STATUS_ON', 'Yes, Show Quantity Box');
define('TEXT_PRODUCTS_QTY_BOX_STATUS_OFF', 'No, Do not show Quantity Box');
define('TEXT_PRODUCTS_QTY_BOX_STATUS_EDIT', 'Warning: Does not show Quantity Box, Default to Qty 1');
define('TEXT_PRODUCTS_QTY_BOX_STATUS_PREVIEW', 'Warning: Does not show Quantity Box, Default to Qty 1');

define('TEXT_PRODUCTS_MANUFACTURER', 'Products Manufacturer:');
define('TEXT_PRODUCTS_NAME', 'Products Name:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Products Description:');
define('TEXT_PRODUCTS_QUANTITY', 'Products Quantity:');
define('TEXT_PRODUCTS_IMAGE', 'Product Image:');
define('TEXT_EDIT_PRODUCTS_IMAGE', 'Edit Product Image:');
define('TEXT_PRODUCTS_MODEL', 'Products Model:');
define('TEXT_PRODUCTS_PART_NUMBER', 'Manufacturer Part Number:');
define('TEXT_PRODUCTS_QUOTE_NUMBER', 'Quote Number:');
define('TEXT_PRODUCTS_IMAGE', 'Products Image:');
define('TEXT_PRODUCTS_IMAGE_DIR', 'Upload to directory:');
define('TEXT_PRODUCTS_URL', 'Products URL:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(without http://)</small>');
define('TEXT_PRODUCTS_PRICE_NET', 'Products Price (Net):');
define('TEXT_PRODUCTS_PRICE_GROSS', 'Products Price (Gross):');
define('TEXT_PRODUCTS_COST', 'Wholesale Cost:');
define('TEXT_PRODUCTS_ERATE', 'E-rate Eligible Price:');
define('TEXT_PRODUCTS_WEIGHT', 'Products Shipping Weight:');

define('TEXT_PRODUCT_IS_FREE', 'Product is Free:');
define('TEXT_PRODUCTS_IS_FREE_PREVIEW', '*Product is marked as FREE');
define('TEXT_PRODUCTS_IS_FREE_EDIT', '*Product is marked as FREE');

define('TEXT_PRODUCT_IS_CALL', 'Product is Call for Price:');
define('TEXT_PRODUCTS_IS_CALL_PREVIEW', '*Product is marked as CALL FOR PRICE');
define('TEXT_PRODUCTS_IS_CALL_EDIT', '*Product is marked as CALL FOR PRICE');

define('TEXT_PRODUCTS_PRICED_BY_ATTRIBUTES', 'Product Priced by Attributes:');
define('TEXT_PRODUCT_IS_PRICED_BY_ATTRIBUTE', 'Yes');
define('TEXT_PRODUCT_NOT_PRICED_BY_ATTRIBUTE', 'No');
define('TEXT_PRODUCTS_PRICED_BY_ATTRIBUTES_PREVIEW', '*Display price will include lowest group attributes prices plus price');
define('TEXT_PRODUCTS_PRICED_BY_ATTRIBUTES_EDIT', '*Display price will include lowest group attributes prices plus price');

define('TEXT_PRODUCTS_TAX_CLASS', 'Tax Class:');

define('TEXT_PRODUCTS_QUANTITY_MIN_RETAIL', 'Product Qty Minimum:');
define('TEXT_PRODUCTS_QUANTITY_UNITS_RETAIL', 'Product Qty Units:');
define('TEXT_PRODUCTS_QUANTITY_MAX_RETAIL', 'Product Qty Maximum:');
define('TEXT_PRODUCTS_QTY_MIN_UNITS_PREVIEW', 'Warning: Minimum is less than Units');
define('TEXT_PRODUCTS_QTY_MIN_UNITS_MISMATCH_PREVIEW', 'Warning: Minimum is not a multiple of Units');

define('TEXT_PRODUCTS_QUANTITY_MAX_RETAIL_EDIT', '0 = Unlimited, 1 = No Qty Boxes');

define('TEXT_PRODUCTS_MIXED', 'Product Qty Min/Unit Mix:');

define('TEXT_PRODUCTS_SORT_ORDER', 'Sort Order:');

define('TEXT_PRODUCT_MORE_INFORMATION', 'For more information, please visit this products <a href="http://%s" target="blank">webpage</a>.');
define('TEXT_PRODUCT_DATE_ADDED', 'This product was added to our catalog on %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'This product will be in stock on %s.');

// meta tags
define('TEXT_META_TAG_TITLE_INCLUDES', '<strong>Select items to show in the page &lt;title&gt; tag (shown in this order):</strong><br><span class="alert">NOTE: If the Keywords and Description meta tag fields are both empty, all items (apart from the Title Additional Text) will be set to "yes". However, in this case the display of the Product Model and Product Price may be overriden (disabled) in Admin page Configuration->Product Info.</span>');
define('TEXT_PRODUCTS_METATAGS_PRODUCTS_NAME_STATUS', '<strong>Product Name:</strong>');
define('TEXT_PRODUCTS_METATAGS_TITLE_STATUS', '<strong>Title Additional Text:</strong><br>(defined below)');
define('TEXT_PRODUCTS_METATAGS_MODEL_STATUS', '<strong>Product Model:</strong>');
define('TEXT_PRODUCTS_METATAGS_PRICE_STATUS', '<strong>Product Price:</strong>');
define('TEXT_PRODUCTS_METATAGS_TITLE_TAGLINE_STATUS', '<strong>defined constant "SITE_TAGLINE":</strong>');
define('TEXT_META_TAGS_TITLE', '<strong>Title Additional Text:</strong><br><span class="alert">NOTE: Title Additional Text is not used if both Keywords and Description meta tag fields are empty.</span>');
define('TEXT_META_TAGS_KEYWORDS', '<strong>Keywords meta tag:</strong>');
define('TEXT_META_TAGS_DESCRIPTION', '<strong>Description meta tag:</strong>');
define('TEXT_META_EXCLUDED', '<span class="alert">EXCLUDED</span>');
define('TEXT_TITLE_PLUS_TAGLINE', 'Store Title+Tagline'); // this refers to whatever rules the storeowner has built into customizing their catalog /includes/modules/meta_tags.php and its lang file.

define('TEXT_PRODUCTS_PRICE_INFO', 'Price:');
