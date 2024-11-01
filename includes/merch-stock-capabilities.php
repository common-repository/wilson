<?php
/**
 * The capabilities-specific functionality of the plugin.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/capabilities
 */
/**
 * The capabilities-specific functionality of the plugin.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/ajax
 * @author     <info@merchandise.nl>
 */
class Merch_Stock_Capabilities {
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
	public function add_custom_capabilities() {
		$msheadoffice = get_role( 'ms_headoffice' );
		$msmanager = get_role( 'ms_manager' );
		$msadmin = get_role( 'ms_admin' );
		$wpmanager = get_role( 'administrator' );
		$caps_to_remove = array(
			'read',
			'edit_customer',
			'read_customer',
			'delete_customer',			
			'edit_customers',
			'edit_other_customers',
			'publish_customers',
			'read_private_customers',
			'edit_product',
			'read_product',
			'delete_product',			
			'edit_products',
			'edit_other_products',
			'publish_products',
			'read_private_products',
			'add_product',
			'edit_office',
			'read_office',
			'delete_office',			
			'edit_offices',
			'edit_other_offices',
			'publish_offices',
			'read_private_offices',
			'add_office',			
			'edit_order',
			'read_order',
			'delete_order',			
			'edit_orders',
			'edit_other_orders',
			'publish_orders',
			'read_private_orders',
			'add_order',
			'add_orderline',						
			'edit_orderline',
			'read_orderline',
			'delete_orderline',			
			'edit_orderlines',
			'edit_other_orderlines',
			'publish_orderlines',
			'read_private_orderlines',		
			'edit_address',
			'read_address',
			'delete_address',			
			'edit_addresss',
			'edit_other_addresss',
			'publish_addresss',
			'read_private_addresss',
			'add_address',	
			'create_posts',
			'edit_posts',
			'publish_posts',
			'edit_other_posts',
			'edit_invoice',
			'read_invoice',
			'delete_invoice',			
			'edit_invoices',
			'edit_other_invoicers',
			'publish_invoices',
			'read_private_invoices',
			'add_invoice',	
			'edit_stockline',
			'read_stockline',
			'delete_stockline',			
			'edit_stocklines',
			'edit_other_stocklines',
			'publish_stocklines',
			'read_private_stocklines',
			'add_stockline',
			'edit_notification',
			'read_notification',
			'delete_notification',			
			'edit_notifications',
			'edit_other_notifications',
			'publish_notifications',
			'read_private_notifications',
			'add_notification',	
			
		);
		foreach ($caps_to_remove as $cap){
			$msheadoffice->remove_cap($cap);
			$msmanager->remove_cap($cap);
			$msadmin->remove_cap($cap);
			$wpmanager->remove_cap($cap);
			$wpmanager->add_cap($cap);
			// $msmanager->add_cap($cap);
		}	
		$msmanager->add_cap( 'read' );	// can see dashboard
		$msadmin->add_cap( 'read' );	// can see dashboard
		$msheadoffice->add_cap( 'read' );	// can see dashboard
		
		
	}
	
}