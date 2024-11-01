<?php
/**
 * The custom-post-types-specific functionality of the plugin.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/custom
 */
/**
 * The custom-post-types-specific functionality of the plugin.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/ajax
 * @author     <info@merchandise.nl>
 */
class Merch_Stock_Custom {
	private $plugin_name;
	private $version;
	private $functions;	
	private $user_functions;
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->functions = new Merch_Stock_Functions();
		$this->user_functions = new Merch_Stock_User_Functions();
	}
	public function add_custom_customers() {
		$customer_args = array(
			'exclude_from_search' => true,
			'public' =>false,
			'show_in_menu'=>false,
			'show_in_admin_bar'=>false,
			'query_var' => 'customer',
			'menu_position' => 10,
			'capability_type' => 'customer',
			'show_ui' => true,
			'rewrite' => array(
				'slug' => 'customers',
				'with_front' => true,
			),
			'supports' => array(
				'title',
				'thumbnail',
				'editor'
			),
			'labels' =>array(
				'name' => 'Customers',
				'singular_name' => 'Customer',
				'add_new' => 'Add New Customer',
				'add_new_item' => 'Add New Customer',
				'edit_item' => 'Edit Customer',
				'new_item' => 'New Customer',
				'view_item' => 'View Customer',
				'search_items' => 'Search Customers',
				'not_found' => 'No Customers Found',
				'not_found_in_trash' => 'No Customers Found In Trash'
			),
		);
		register_post_type( 'customer', $customer_args );	
	}	 	
	public function add_custom_products() {
		$product_args = array(
			'exclude_from_search' => true, 
			'public' =>false,
			'show_in_menu'=>false,
			'show_in_admin_bar'=>false,
			'menu_position' => 1,
			'query_var' => 'wilson-product',
			// 'capability_type' => 'post',
			'rewrite' => array(
				'slug' => 'wilson-products',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
				'editor',
				'gallery'
			),
			'labels' =>array(
				'name' => 'Wilson Products',
				'singular_name' => 'Wilson Product', 
				'add_new' => 'Add New Wilson Product',
				'add_new_item' => 'Add New Wilson Product',
				'edit_item' => 'Edit Wilson Product',
				'new_item' => 'New Wilson Product',
				'view_item' => 'View Wilson Product',
				'search_items' => 'Search Wilson Products',
				'not_found' => 'No Wilson Products Found',
				'not_found_in_trash' => 'No Wilson Products Found In Trash'
			), 
			// 'capabilities' => array(
	  //           'edit_post' => 'edit_wilson-product',
	  //           'edit_posts' => 'edit_wilson-products',
	  //           'edit_others_posts' => 'edit_other_wilson-products',
	  //           'publish_posts' => 'publish_wilson-products',
	  //           'read_post' => 'read_wilson-product',
	  //           'read_private_posts' => 'read_private_wilson-products',
	  //           'delete_post' => 'delete_wilson-product'
			// )
		);
		register_post_type( 'wilson-product', $product_args );	
	} 
	public function add_custom_product_requests() {
		$product_request_args = array(
			'exclude_from_search' => true,
			'public' =>false,
			'show_in_menu'=>false,
			'show_in_admin_bar'=>false,
			'query_var' => 'product_request',
			'capability_type' => 'product',
			'rewrite' => array(
				'slug' => 'product_requests',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
				'editor',
				'gallery'
			),
			'labels' =>array(
				'name' => 'Product request',
				'singular_name' => 'Product request',
				'add_new' => 'Add New Product request',
				'add_new_item' => 'Add New Product request',
				'edit_item' => 'Edit Product request',
				'new_item' => 'New Product request',
				'view_item' => 'View Product request',
				'search_items' => 'Search Product requests',
				'not_found' => 'No Product requests Found',
				'not_found_in_trash' => 'No Product requests Found In Trash'
			),
			'capabilities' => array(
	            'edit_post' => 'edit_product',
	            'edit_posts' => 'edit_products',
	            'edit_others_posts' => 'edit_other_products',
	            'publish_posts' => 'publish_products',
	            'read_post' => 'read_product',
	            'read_private_posts' => 'read_private_products',
	            'delete_post' => 'delete_product'
			)
		);
		register_post_type( 'product_request', $product_request_args );	
	}	
	
	public function add_custom_notifications() {
		$notification_args = array(
			'exclude_from_search' => true,
			'public' =>false,
			'show_in_menu'=>false,
			'show_in_admin_bar'=>false,
			'query_var' => 'notification',
			'capability_type' => 'notification',
			'rewrite' => array(
				'slug' => 'notifications',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
				'editor',
				'gallery'
			),
			'labels' =>array(
				'name' => 'Notification',
				'singular_name' => 'Notification',
				'add_new' => 'Add New Notification',
				'add_new_item' => 'Add New Notification',
				'edit_item' => 'Edit Notification',
				'new_item' => 'New Notification',
				'view_item' => 'View Notification',
				'search_items' => 'Search Notifications',
				'not_found' => 'No Notifications Found',
				'not_found_in_trash' => 'No Notifications Found In Trash'
			),
			'capabilities' => array(
	            'edit_post' => 'edit_notification',
	            'edit_posts' => 'edit_notification',
	            'edit_others_posts' => 'edit_other_notifications',
	            'publish_posts' => 'publish_notifications',
	            'read_post' => 'read_notification',
	            'read_private_posts' => 'read_private_notifications',
	            'delete_post' => 'delete_notification'
			)
		);
		register_post_type( 'notification', $notification_args );	
	}	
	public function add_custom_offices() {
		$office_args = array(
			'exclude_from_search' => true,
			'public' =>true,
			'show_in_menu'=>true,
			'show_in_admin_bar'=>true,
			'capability_type' => 'office',
			'query_var' => 'office',
			'rewrite' => array(
				'slug' => 'offices',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
				'editor',
			),
			'labels' =>array(
				'name' => 'Offices',
				'singular_name' => 'Office',
				'add_new' => 'Add New Office',
				'add_new_item' => 'Add New Office',
				'edit_item' => 'Edit Office',
				'new_item' => 'New Office',
				'view_item' => 'View Office',
				'search_items' => 'Search Offices',
				'not_found' => 'No Offices Found',
				'not_found_in_trash' => 'No Offices Found In Trash'
			)			
		);
		register_post_type( 'office', $office_args );		
	}		
	public function add_custom_orders() {
		$order_args = array(
			'exclude_from_search' => true,
			'public' =>false,
			'show_in_menu'=>0,
			'show_in_admin_bar'=>false,
			'capability_type' => 'order',
			'query_var' => 'order',
			'rewrite' => array(
				'slug' => 'orders',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
			),
			'labels' =>array(
				'name' => 'Orders',
				'singular_name' => 'Order',
				'add_new' => 'Add New Order',
				'add_new_item' => 'Add New Order',
				'edit_item' => 'Edit Order',
				'new_item' => 'New Order',
				'view_item' => 'View Order',
				'search_items' => 'Search Orders',
				'not_found' => 'No Orders Found',
				'not_found_in_trash' => 'No Orders Found In Trash'
			)		
		);
		register_post_type( 'order', $order_args );		
	}	
	public function add_custom_order_status_update(){
		$order_status_update_args = array(
			'exclude_from_search' => true,			
			'public' =>false,
			'show_in_menu'=>false,
			'show_in_admin_bar'=>false,
			'capability_type' => 'order',
			'query_var' => 'order_status_update',
			'rewrite' => array(
				'slug' => 'order_status_updates',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
			),
			'labels' =>array(
				'name' => 'Order Status Update',
				'singular_name' => 'Order Status Update',
				'add_new' => 'Add New Order Status Update',
				'add_new_item' => 'Add New Order Status Update',
				'edit_item' => 'Edit Order Status Update',
				'new_item' => 'New Order Status Update',
				'view_item' => 'View Order Status Update',
				'search_items' => 'Search Order Status Updates',
				'not_found' => 'No Order Status Updates Found',
				'not_found_in_trash' => 'No Order Status Updates Found In Trash'
			)		
		);
		register_post_type( 'order_status_update', $order_status_update_args );			
	}
	public function add_custom_stocklines() {
		$stockline_args = array(
			'exclude_from_search' => true,			
			'public' =>false,
			'show_in_menu'=>false,
			'show_in_admin_bar'=>false,
			'capability_type' => 'stockline',
			'query_var' => 'stockline',
			'rewrite' => array(
				'slug' => 'stocklines',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
			),
			'labels' =>array(
				'name' => 'Stockline',
				'singular_name' => 'Stockline',
				'add_new' => 'Add New Stockline',
				'add_new_item' => 'Add New Stockline',
				'edit_item' => 'Edit Stockline',
				'new_item' => 'New Stockline',
				'view_item' => 'View Stockline',
				'search_items' => 'Search Stocklines',
				'not_found' => 'No Stocklines Found',
				'not_found_in_trash' => 'No Stocklines Found In Trash'
			)		
		);
		register_post_type( 'stockline', $stockline_args );		
	}	
	public function add_custom_pricelines(){
		$priceline_args = array(
			'exclude_from_search' => true,			
			'public' =>false,
			'show_in_menu'=>true,
			'show_in_admin_bar'=>false,
			'capability_type' => 'priceline',
			'query_var' => 'priceline',
			'rewrite' => array(
				'slug' => 'pricelines',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
			),
			'labels' =>array(
				'name' => 'Priceline',
				'singular_name' => 'Priceline',
				'add_new' => 'Add New Priceline',
				'add_new_item' => 'Add New Priceline',
				'edit_item' => 'Edit Priceline',
				'new_item' => 'New Priceline',
				'view_item' => 'View Priceline',
				'search_items' => 'Search Pricelines',
				'not_found' => 'No Pricelines Found',
				'not_found_in_trash' => 'No Pricelines Found In Trash'
			)		
		);
		register_post_type( 'priceline', $priceline_args );				
	}	
	public function add_custom_backorders() {
		$backorder_args = array(
			'exclude_from_search' => true,			
			'public' =>false,
			'show_in_menu'=>true,
			'show_in_admin_bar'=>false,
			'capability_type' => 'backorder',
			'query_var' => 'backorder',
			'rewrite' => array(
				'slug' => 'backorders',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
			),
			'labels' =>array(
				'name' => 'Backorder',
				'singular_name' => 'Backorder',
				'add_new' => 'Add New Backorder',
				'add_new_item' => 'Add New Backorder',
				'edit_item' => 'Edit Backorder',
				'new_item' => 'New Backorder',
				'view_item' => 'View Backorder',
				'search_items' => 'Search Backorders',
				'not_found' => 'No Backorders Found',
				'not_found_in_trash' => 'No Backorders Found In Trash'
			)		
		);
		register_post_type( 'backorder', $backorder_args );		
	}	
	public function add_custom_backorder_requests() {
		$backorder_request_args = array(
			'exclude_from_search' => true,			
			'public' =>false,
			'show_in_menu'=>false,
			'show_in_admin_bar'=>false,
			'capability_type' => 'backorder_request',
			'query_var' => 'backorder_request',
			'rewrite' => array(
				'slug' => 'backorder_requests',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
			),
			'labels' =>array(
				'name' => 'Backorder Request',
				'singular_name' => 'Backorder Request',
				'add_new' => 'Add New Backorder Request',
				'add_new_item' => 'Add New Backorder Request',
				'edit_item' => 'Edit Backorder Request',
				'new_item' => 'New Backorder Request',
				'view_item' => 'View Backorder Request',
				'search_items' => 'Search Backorder Requests',
				'not_found' => 'No Backorder Requests Found',
				'not_found_in_trash' => 'No Backorder Requests Found In Trash'
			)		
		);
		register_post_type( 'backorder_request', $backorder_request_args );		
	}				
	public function add_custom_orderlines() {
		$orderline_args = array(
			'exclude_from_search' => true,			
			'public' =>false,
			'show_in_menu'=>false,
			'show_in_admin_bar'=>false,
			'capability_type' => 'orderline',
			'query_var' => 'orderline',
			'rewrite' => array(
				'slug' => 'orderlines',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
			),
			'labels' =>array(
				'name' => 'Orderline',
				'singular_name' => 'Orderline',
				'add_new' => 'Add New Orderline',
				'add_new_item' => 'Add New Orderline',
				'edit_item' => 'Edit Orderline',
				'new_item' => 'New Orderline',
				'view_item' => 'View Orderline',
				'search_items' => 'Search Orderlines',
				'not_found' => 'No Orderlines Found',
				'not_found_in_trash' => 'No Orderlines Found In Trash'
			)	
		);
		register_post_type( 'orderline', $orderline_args );		
	}	
	public function add_custom_invoices() {
		$invoice_args = array(
			'exclude_from_search' => true,			
			'public' =>false,
			'show_in_menu'=>false,
			'show_in_admin_bar'=>false,
			'capability_type' => 'invoice',
			'query_var' => 'invoice',
			'rewrite' => array(
				'slug' => 'invoices',
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'thumbnail',
			),
			'labels' =>array(
				'name' => 'Invoice',
				'singular_name' => 'Invoice',
				'add_new' => 'Add New Invoice',
				'add_new_item' => 'Add New Invoice',
				'edit_item' => 'Edit Invoice',
				'new_item' => 'New Invoice',
				'view_item' => 'View Invoice',
				'search_items' => 'Search Invoices',
				'not_found' => 'No Invoices Found',
				'not_found_in_trash' => 'No Invoices Found In Trash'
			)		
		);
		register_post_type( 'invoice', $invoice_args );		
	}		
	public function add_custom_taxanomies(){
		/* Set up the genre taxonomy arguments. */
		 $client_args = array(
		 'hierarchical' => true,
		 'query_var' => 'product_customer',
		 'show_tagcloud' => false,
		 'rewrite' => array(
		 'slug' => 'customers',
		 'with_front' => false
		 ),
		 'labels' =>array(
		 'name' => 'Customers',
		 'singular_name' => 'Customer',
		 'edit_item' => 'Edit Customer',
		 'update_item' => 'Update Customer',
		 'add_new_item' => 'Add New Customer',
		 'new_item_name' => 'New Customer Name',
		 'all_items' =>'All Customers',
		 'search_items' => 'Search Customers',
		 'parent_item' => 'Parent Customer',
		 'parent_item_colon' => 'Parent Customer:',
		 ),
		 );		
		  register_taxonomy( 'product_customer', array( 'product' ), $client_args );
	}	
}