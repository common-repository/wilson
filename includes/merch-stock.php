<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    MerchStock
 * @subpackage MerchStock/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MerchStock
 * @subpackage MerchStock/includes
 * @author     <web@merchandise.nl>
 */
class Merchstock {
	// public $functions = null;
	// public $user_functions = null;
		
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'Wilson';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/admin/merch-stock-admin.php';		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/admin/merch-stock-wp-admin.php';
		/**
		 * The class responsible for the menu
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-menu.php';		
		/**
		 * The class responsible for the menu
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-custom.php';				
		/**
		 * The class responsible for the dashboard
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-dashboard.php';			
		/**
		 * The class responsible for the ajax functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-ajax-frontend.php';						
		/**
		 * The class responsible for the form functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-form.php';										
		/**
		 * The class responsible for the capabilities functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-capabilities.php';	
		/**
		 * The class responsible for the capabilities functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-metaboxes.php';														
		/**
		 * The class responsible for the enqueue functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-enqueue.php';																
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/merch-stock-public.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-functions.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-user-functions.php';		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-notifications.php';				
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/merch-stock-orders.php';		
		$this->loader = new Merch_Stock_Loader();
	}
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Merch_Stock_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_menu = new Merch_Stock_Menu( $this->get_plugin_name(), $this->get_version() );
		$plugin_custom = new Merch_Stock_Custom( $this->get_plugin_name(), $this->get_version() );
		$plugin_dashboard = new Merch_Stock_Dashboard( $this->get_plugin_name(), $this->get_version() );
		$plugin_ajax_frontend = new Merch_Stock_Ajax_Frontend( $this->get_plugin_name(), $this->get_version() );
		$plugin_form = new Merch_Stock_Form( $this->get_plugin_name(), $this->get_version() );
		$plugin_capabilities = new Merch_Stock_Capabilities( $this->get_plugin_name(), $this->get_version() );
		$plugin_metaboxes = new Merch_Stock_Metaboxes( $this->get_plugin_name(), $this->get_version() );
		$plugin_enqueue = new Merch_Stock_Enqueue( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin = new Merch_Stock_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_ajax_backend = new Merch_Stock_WP_Admin( $this->get_plugin_name(), $this->get_version() );

		// Add all Ajax Frontend functions
		$this->loader->add_action( 'wp_ajax_ajax_get_product_information', $plugin_ajax_frontend, 'ajax_get_product_information' );
		$this->loader->add_action( 'wp_ajax_ajax_get_order_information', $plugin_ajax_frontend, 'ajax_get_order_information' );
		$this->loader->add_action( 'wp_ajax_ajax_get_office_information', $plugin_ajax_frontend, 'ajax_get_office_information' );
		$this->loader->add_action( 'wp_ajax_ajax_add_orderline', $plugin_ajax_frontend, 'ajax_add_orderline' );
		$this->loader->add_action( 'wp_ajax_ajax_delete_orderline', $plugin_ajax_frontend, 'ajax_delete_orderline' );
		$this->loader->add_action( 'wp_ajax_ajax_request_backorder', $plugin_ajax_frontend, 'ajax_request_backorder' );
		$this->loader->add_action( 'wp_ajax_ajax_request_backorder_v2', $plugin_ajax_frontend, 'ajax_request_backorder_v2' );
		$this->loader->add_action( 'wp_ajax_request_backorder_manager', $plugin_ajax_frontend, 'request_backorder_manager' );
		$this->loader->add_action( 'wp_ajax_ajax_request_product', $plugin_ajax_frontend, 'ajax_request_product' );
		$this->loader->add_action( 'wp_ajax_ajax_ship_order', $plugin_ajax_frontend, 'ajax_ship_order' );

		$this->loader->add_action( 'wp_ajax_ajax_new_product_1', $plugin_ajax_backend, 'ajax_new_product_1' );
		$this->loader->add_action( 'wp_ajax_ajax_new_office_1', $plugin_ajax_backend, 'ajax_new_office_1' );
		$this->loader->add_action( 'wp_ajax_ajax_new_office_2', $plugin_ajax_backend, 'ajax_new_office_2' );
		$this->loader->add_action( 'wp_ajax_ajax_new_office_3', $plugin_ajax_backend, 'ajax_new_office_3' );
		$this->loader->add_action( 'wp_ajax_ajax_new_office_4', $plugin_ajax_backend, 'ajax_new_office_4' );
		$this->loader->add_action( 'wp_ajax_ajax_new_product_2', $plugin_ajax_backend, 'ajax_new_product_2' );
		$this->loader->add_action( 'wp_ajax_ajax_new_product_3', $plugin_ajax_backend, 'ajax_new_product_3' );
		$this->loader->add_action( 'wp_ajax_ajax_new_product_4', $plugin_ajax_backend, 'ajax_new_product_4' );
		$this->loader->add_action( 'wp_ajax_ajax_new_product_5', $plugin_ajax_backend, 'ajax_new_product_5' );
		$this->loader->add_action( 'wp_ajax_ajax_add_customer', $plugin_ajax_backend, 'ajax_add_customer' );
		$this->loader->add_action( 'wp_ajax_ajax_add_manager', $plugin_ajax_backend, 'ajax_add_manager' );
		
		// Add form submit function
		$this->loader->add_action( 'admin_post_nopriv_save_order', $plugin_form, 'save_order' );
		$this->loader->add_action( 'admin_post_save_order', $plugin_form, 'save_order' );
		$this->loader->add_action( 'admin_post_update_order', $plugin_form, 'update_order' );
		$this->loader->add_action( 'admin_post_update_order_status', $plugin_form, 'update_order_status' );
		$this->loader->add_action( 'admin_post_approve_order', $plugin_form, 'approve_order' );
		$this->loader->add_action( 'admin_post_decline_order', $plugin_form, 'decline_order' );
		$this->loader->add_action( 'admin_post_approve_backorder', $plugin_form, 'approve_backorder' );
		$this->loader->add_action( 'admin_post_decline_backorder', $plugin_form, 'decline_backorder' );		
		$this->loader->add_action( 'admin_post_remove_orderline', $plugin_form, 'remove_orderline' );
		$this->loader->add_action( 'admin_post_update_office', $plugin_form, 'update_office' );
		$this->loader->add_action( 'admin_post_create_backorder', $plugin_form, 'create_backorder' );
		$this->loader->add_action( 'admin_post_create_backorder_headoffice', $plugin_form, 'create_backorder_headoffice' );
		$this->loader->add_action( 'admin_post_reject_backorder', $plugin_form, 'reject_backorder' );
		$this->loader->add_action( 'admin_post_update_backorder_request', $plugin_form, 'update_backorder_request' );
		$this->loader->add_action( 'admin_post_update_backorder', $plugin_form, 'update_backorder' );	
		$this->loader->add_action( 'admin_post_update_product_request', $plugin_form, 'update_product_request' );	
		$this->loader->add_action( 'admin_post_invoice_pdf', $plugin_form, 'invoice_pdf' );	

		
		$this->loader->add_action( 'admin_post_delete_wilson_customers', $plugin_admin, 'delete_wilson_customers' );	
		$this->loader->add_action( 'admin_post_delete_wilson_products', $plugin_form, 'delete_wilson_products' );	
		$this->loader->add_action( 'admin_post_delete_wilson_offices', $plugin_form, 'delete_wilson_offices' );	
		$this->loader->add_action( 'admin_post_delete_wilson_product_requests', $plugin_form, 'delete_wilson_product_requests' );	
		$this->loader->add_action( 'admin_post_delete_wilson_backorders', $plugin_form, 'delete_wilson_backorders' );	
		$this->loader->add_action( 'admin_post_delete_wilson_orders', $plugin_form, 'delete_wilson_orders' );	
		
		
		$this->loader->add_action( 'admin_post_update_wilson_product', $plugin_admin, 'update_wilson_product' );	
		$this->loader->add_action( 'admin_post_ship_order', $plugin_admin, 'ship_order' );	
		$this->loader->add_action( 'admin_post_accept_backorder', $plugin_admin, 'accept_backorder' );	
		$this->loader->add_action( 'admin_post_update_wilson_office', $plugin_admin, 'update_wilson_office' );	
		$this->loader->add_action( 'admin_post_add_customer', $plugin_admin, 'add_customer' );	
		$this->loader->add_action( 'admin_post_update_product_request_admin', $plugin_admin, 'update_product_request_admin' );	
		$this->loader->add_action( 'admin_post_update_wilson_customer', $plugin_admin, 'update_wilson_customer' );	
		
		// Enqueue style and script files
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_enqueue, 'enqueue_styles' );		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_enqueue, 'enqueue_scripts' );		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_enqueue, 'enqueue_datatable_styles' );		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_enqueue, 'enqueue_datatable_scripts' );	
		
		// Change loginscreen logo
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_enqueue, 'my_login_logo' );
	
		// Set all customer roles and capabilities
		$this->loader->add_action( 'admin_init', $plugin_capabilities, 'add_custom_capabilities');
		$this->loader->add_action( 'admin_head', $plugin_menu, 'add_custom_css');
		// Strip profile page
		$this->loader->add_action( 'admin_head', $plugin_menu, 'hide_personal_options');
		$this->loader->add_action( 'admin_head', $plugin_menu, 'hide_admin_notices');
		
		// Add all custom post types
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_products' );
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_product_requests' );
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_customers' );		
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_offices' );
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_orders' );		
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_order_status_update' );	
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_orderlines' );		
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_stocklines' );		
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_pricelines' );	
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_backorders' );	
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_backorder_requests' );
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_invoices' );		
		$this->loader->add_action( 'init', $plugin_custom, 'add_custom_notifications' );
		// Add meta boxes
		$this->loader->add_action( 'admin_init', $plugin_metaboxes, 'add_product_meta_boxes' );
		$this->loader->add_action( 'admin_init', $plugin_metaboxes, 'add_office_meta_boxes' );
		$this->loader->add_action( 'admin_init', $plugin_metaboxes, 'add_customer_meta_boxes' );
		$this->loader->add_action( 'show_user_profile', $plugin_metaboxes, 'customer_field_user' );
		$this->loader->add_action( 'edit_user_profile', $plugin_metaboxes, 'customer_field_user' );
 		// Save all meta boxes
		$this->loader->add_action( 'save_post_wilson-product', $plugin_metaboxes, 'save_product' );
		$this->loader->add_action( 'save_post_office', $plugin_metaboxes, 'save_office' );
		$this->loader->add_action( 'save_post_customer', $plugin_metaboxes, 'save_customer' );
		// Add address to customer
		$this->loader->add_action( 'personal_options_update', $plugin_metaboxes, 'save_user_meta_boxes' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_metaboxes, 'save_user_meta_boxes' );		
		// Load the admin menu
		$this->loader->add_action( 'admin_menu', $plugin_menu, 'add_wilson_menu');
		$this->loader->add_action( 'admin_menu', $plugin_menu, 'add_menu_item');
		// Remove admin menu items
		$this->loader->add_action( 'admin_init', $plugin_menu, 'remove_menu_item');		
		$this->loader->add_action( 'admin_menu', $plugin_menu, 'add_seperate_pages');
		// Change start page
		$this->loader->add_action( 'login_redirect', $plugin_menu, 'admin_default_page', 10, 3 );	
		// Add custom classes to menu
		$this->loader->add_action( 'admin_menu', $plugin_menu, 'add_menu_item_class');
 		// Edit admin theme - remove items
		$this->loader->add_action( 'init', $plugin_menu, 'edit_admin_theme' );
		$this->loader->add_action( 'init', $plugin_menu, 'add_admin_theme' );			
		// Setup dashboard
		$this->loader->add_action( 'wp_dashboard_setup', $plugin_dashboard, 'setup_dashboard' );
		// Remove WordPress update
		$this->loader->add_filter( 'manage_order_posts_columns', $plugin_admin, 'add_order_headers' );
		$this->loader->add_action( 'manage_order_posts_custom_column', $plugin_admin, 'order_table_content', 10, 3  );
		$this->loader->add_action( 'admin_init', $plugin_wp_admin, 'add_order_meta_boxes' );
		$this->loader->add_action( 'restrict_manage_posts', $plugin_wp_admin, 'my_post_type_filter',10,2 );
		add_filter( 'woocommerce_prevent_admin_access', '__return_false' );
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
 