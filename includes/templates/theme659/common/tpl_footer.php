<?php
/**
 * Common Template - tpl_footer.php
 *
 * this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * make a directory /templates/my_template/privacy<br />
 * copy /templates/templates_defaults/common/tpl_footer.php to /templates/my_template/privacy/tpl_footer.php<br />
 * to override the global settings and turn off the footer un-comment the following line:<br />
 * <br />
 * $flag_disable_footer = true;<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_footer.php 15511 2010-02-18 07:19:44Z drbyte $
 */
require(DIR_WS_MODULES . zen_get_module_directory('footer.php'));
?>

<?php
if (!isset($flag_disable_footer) || !$flag_disable_footer) {
?>

<div id="footer">
	<div class="wrapper">
<?php if (false) { ?>
		<div class="footer-menu">
			<div id="navSupp">
			<?php //echo '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'. HEADER_TITLE_CATALOG . '</a>'; ?>
			<?php if (EZPAGES_STATUS_FOOTER == '1' or (EZPAGES_STATUS_FOOTER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) { ?>
			<?php require($template->get_template_dir('tpl_ezpages_bar_footer.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_bar_footer.php'); ?>
			<?php } ?>
			
			</div>
		</div>
<?php } ?>
		<div class="copyright">
			<!-- ========== COPYRIGHT ========== -->
				<?php echo FOOTER_TEXT_BODY; ?><?php if (false) { ?> &nbsp;| &nbsp;<a href="<?php echo zen_href_link(FILENAME_PRIVACY)?>"><?php echo BOX_INFORMATION_PRIVACY?></a><?php } ?>
			
				<?php
					if (SHOW_FOOTER_IP == '1') {
				?>
						<div id="siteinfoIP"><?php echo TEXT_YOUR_IP_ADDRESS . '  ' . $_SERVER['REMOTE_ADDR']; ?></div>
				<?php
					}
				?>
			<!-- =============================== -->
		</div>
		<?php
			if(false && $this_is_home_page){
		?>
		<div>More <a rel="nofollow" href="http://www.templatemonster.com/category/computer-store-zen-cart-templates/" target="_blank">Computer Store Zen Cart Templates at TemplateMonster.com</a></div>
		<?php
			}
		?>
		<!--<div class="cards">
			<?php echo zen_image(DIR_WS_TEMPLATE.'images/paypal.gif'); ?>
		</div>-->
		
	</div>
</div>






<!-- BOF- BANNER #5 display -->
<?php
	if (SHOW_BANNERS_GROUP_SET5 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET5)) {
		if ($banner->RecordCount() > 0) {
?>
			<div id="bannerFive"><?php echo zen_display_banner('static', $banner);?></div>
<?php 
		}
	}
?>
<!-- EOF- BANNER #5 display -->

<?php
} // flag_disable_footer
?>
