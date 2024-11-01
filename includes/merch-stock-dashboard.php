<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/dashboard
 */
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/dashboard
 * @author     <info@merchandise.nl>
 */
class Merch_Stock_Dashboard {
	private $plugin_name;
	private $version;
	private $functions;	
	private $user_functions;
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->functions = new Merch_Stock_Functions();
		$this->user_functions = new Merch_Stock_User_Functions();
		$this->isMerchStockUser = $this->user_functions->isMerchStockUser();
	}
	public function setup_dashboard() {
		if ($this->isMerchStockUser){
			global $wp_meta_boxes;
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
			// bbpress
			unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
			// yoast seo
			unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
			// gravity forms
			unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);		
		}
	}	
}