<?php
/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Merch_Stock_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		remove_role( 'ms_headoffice' );	
		remove_role( 'ms_manager' );	
		remove_role( 'ms_user' );
		remove_role( 'ms_admin' );
		remove_role( 'wp_user' );
		remove_role( 'wp_manager' );
		remove_role( 'wp_headoffice' );
		// remove_role( 'subscriber' );
		// remove_role( 'contributor' );
		// remove_role( 'author' );
// 
		// remove_role( 'editor' );		
		// add MS manager
		$service_rep_caps = array(
		    // 'read'              => true,
		    // 'create_posts'      => true,
		    // 'edit_posts'        => true,
		    // 'edit_others_posts' => true,
		    // 'publish_posts'     => true,
		    // 'manage_categories' => true,
		    // 'manage_options'    => true,
		);
		add_role('ms_manager', __('Wilson Manager'), $service_rep_caps);	
		// add MS headoffice
		$service_rep_caps = array(
		    // 'read'              => true,
		    // 'create_posts'      => true,
		    // 'edit_posts'        => true,
		    // 'edit_others_posts' => true,
		    // 'publish_posts'     => true,
		    // 'manage_categories' => true,
		    // 'manage_options'    => true,
		);
		add_role('ms_headoffice', __('Wilson headoffice'), $service_rep_caps);	
		// add MS manager
		$service_rep_caps = array(
		    // 'read'              => true,
		    // 'create_posts'      => true,
		    // 'edit_posts'        => true,
		    // 'edit_others_posts' => true,
		    // 'publish_posts'     => true,
		    // 'manage_categories' => true,
		    // 'manage_options'    => true,
		);
		add_role('ms_admin', __('Wilson admin'), $service_rep_caps);								
		// $wpmanager = get_role( 'administrator' );
		// $wpmanager->add_cap('read_customer');
		// $wpmanager->add_cap('create_customer');
		// $wpmanager->add_cap('edit_customer');
		// // Add the three roles needed for this plugin
		// $service_rep_caps = array(
		//     // 'read'              => true,
		//     // 'create_posts'      => false,
		//     // 'edit_posts'        => false,
		//     // 'edit_others_posts' => false,
		//     // 'publish_posts'     => false,
		//     // 'manage_categories' => false,
		//     // 'manage_options'    => false,
		// );	
		// $service_rep_caps = array( 
		// 	'read' => true, 
		// 	'edit_posts'   => true, 
		// 	'delete_posts' => true,
		// );
		// add_role('ms_user', __('MS user'), $service_rep_caps);	
		// $service_rep_caps = array(
		// 	// 'read'							=> true,
		// 	// 'edit_product'              	=> true,
		// 	// 'read_product'      			=> true,
		// 	// 'delete_private__product'				=> false,
		// 	// 'edit_post'              	=> true,
		// 	// 'read_post'      			=> true,
		// 	// 'delete_post'				=> false,			
		// 	// 'read'						=> true,
		//  //    'edit_product'              => true,
		//  //    'read_product'      		=> true,
		//  //    'delete_product'        	=> true,
		//  //    'create_products'			=> true,
		//  //    'edit_products' 			=> true,
		//  //    'edit_others_products'     	=> true,
		//  //    'publish_products' 			=> true,
		//  //    'read_private_products'    	=> true,
		//  //    'edit_products'    			=> true,
		// );
		// $service_rep_caps = array( 
		// 	'read' => true, //able to see dashboard
		// 	'edit_posts'   => false, 
		// 	'edit_products'   => true, 
		// 	'edit_offices'   => true, 
		// 	'edit_customers'   => true,
		// 	'new-order'		=> true,
		// 	'create_order'	=> true,
		// 	'edit_order'	=> true,
		// 	'manage_options' => true,
		// 	// 'delete_posts' => false,
		// 	// 'delete_published_posts' => false,
		// 	// 'publish_posts' => false,
		// 	// 'upload_files' => false,
		// 	// 'edit_published_posts' => false,
		// 	// 'manage_categories' => false 
		// );
		// add_role('ms_manager', __('MS manager'), $service_rep_caps);		
		// $service_rep_caps = array(
		//     // 'read'              => true,
		//     // 'create_posts'      => true,
		//     // 'edit_posts'        => true,
		//     // 'edit_others_posts' => true,
		//     // 'publish_posts'     => true,
		//     // 'manage_categories' => true,
		//     // 'manage_options'    => true,
		// );
		// add_role('ms_headoffice', __('MS headoffice'), $service_rep_caps);			
		
			
		// $roles = array('wp_headoffice','wp_manager','wp_user');
  //       foreach($roles as $the_role) {    
  //           $role = get_role($the_role);               
  //           $role->add_cap( 'read_product' );
  //           $role->add_cap( 'edit_product');
  //           $role->add_cap( 'delete_product' );
  //           $role->add_cap( 'edit_course_documents' );
  //           $role->add_cap( 'edit_published_course_documents' );
  //           $role->add_cap( 'publish_course_documents' );
  //           $role->add_cap( 'delete_published_course_documents' );
  //       }		
		
		// Remove all dashboard items and add one for merch-stock
		// global $wp_meta_boxes;
		// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
		// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
		// // bbpress
		// unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
		// // yoast seo
		// unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
		// // gravity forms
		// unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);			
		// wp_add_dashboard_widget('custom_help_widget', 'Merchstock',  array('Dashboard','custom_dashboard_help'));			
	}
	// function custom_dashboard_help() {
	// 	$functions = new Functions();
	// 	if ($functions->isAdmin()){
	// 		echo "wel admin";
	// 	}
	// 	else{
	// 		echo "geen admin";
	// 	}
	// }	
}
