<?php
/**
 * All extra functions needed for MerchStock
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/functions
 */
/**
 * Define extra functions needed for MerchStock
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/ajax
 * @author     <info@merchandise.nl>
 */
class Merch_Stock_User_Functions {
	public function __construct( ) {}

	public static function getUserName(){
		$user = wp_get_current_user();
		return $user->user_firstname . " " . $user->user_lastname;
	}

	public static function getUserNameByID($user_id){
		$user = get_user_by( 'id', $user_id );
		return $user->user_firstname . " " . $user->user_lastname;
	}

	public static function getOrderUser($order_id){
		$user_id = get_post_meta( $order_id, 'user_id', true );
		if ($user_id>0){
			return Merch_Stock_User_Functions::getUserNameByID($user_id);	
		}
	}

	public static function isMerchStockUser(){	
		if (!Merch_Stock_User_Functions::getMerchStockRole()){
			return false;
		}	
		else{
			return true;
		}
	}

	public static function getMerchStockRole(){
		if(!function_exists('wp_get_current_user')) {
		    include(ABSPATH . "wp-includes/pluggable.php"); 
		}
		$user = wp_get_current_user();
		if ( in_array( 'ms_headoffice', (array) $user->roles ) ) {
		    return "ms_headoffice";
		}
		else if ( in_array( 'ms_manager', (array) $user->roles ) ) {
			return "ms_manager";
		}	
		else if ( in_array( 'ms_admin', (array) $user->roles ) ) {
			return "ms_admin";
		}
		else {
			return false;
		}							
	}

	public function getMerchStockRoleDescription(){
		$user = wp_get_current_user();
		if ( in_array( 'ms_headoffice', (array) $user->roles ) ) {
		    return "Wilson Headoffice";
		}
		else if ( in_array( 'ms_manager', (array) $user->roles ) ) {
			return "Wilson Manager";
		}	
		else if ( in_array( 'ms_admin', (array) $user->roles ) ) {
			return "Wilson Admin";
		}
		else {
			return false;
		}	
	}

	public static function getMerchStockRoleName(){
		$user = wp_get_current_user();
		if ( in_array( 'ms_headoffice', (array) $user->roles ) ) {
		    return "headoffice";
		}
		else if ( in_array( 'ms_manager', (array) $user->roles ) ) {
			return "manager";
		}	
		else if ( in_array( 'ms_admin', (array) $user->roles ) ) {
			return "admin";
		}
		else {
			return false;
		}				
	}		

	public static function getMerchStockRoleByID($user_id){
		$user = get_user_by( 'ID', $user_id );
		if ( in_array( 'ms_headoffice', (array) $user->roles ) ) {
		    return "ms_headoffice";
		}
		else if ( in_array( 'ms_manager', (array) $user->roles ) ) {
			return "ms_manager";
		}	
		else if ( in_array( 'ms_admin', (array) $user->roles ) ) {
			return "ms_admin";
		}
		else {
			return 0;
		}							
	}	

	public static function getUserCustomer(){		
		$user = wp_get_current_user();
		$customer_id = get_user_meta( $user->ID, 'customer_id', true );
		if ($customer_id>0){
			return $customer_id;
		}
		else{
			return null;
		}
	}	

	public static function getManagerUsersFromCustomer($customer_id){		
		$users = array();
		$all_users = get_users();
		foreach ( $all_users as $user ) {	
			$tmp_customer_id = intval(get_user_meta( $user->ID, 'customer_id', true ));
			if ($customer_id == $tmp_customer_id){
				if (!in_array( $user, $users)){
					array_push($users, array('ID'=>$user->ID, 'name'=>$user->user_firstname . " " . $user->user_lastname ));
				}				
			}
		}		
		return json_encode($users);
	}

	public static function getHeadofficeUsersFromCustomer($customer_id){
		$users = array();
		$all_users = get_users();
		foreach ( $all_users as $user ) {
			$tmp_customer_id = intval(get_user_meta( $user->ID, 'customer_id', true ));
			$role = Merch_Stock_User_Functions::getMerchStockRoleByID($user->ID);
			if (intval($customer_id)==$tmp_customer_id && $role=="ms_headoffice" ){
				if (!in_array( $user, $users)){
					array_push($users, $user);
				}
			}
		}	
		return json_encode($users);		
	}

	public static function getUserOffices(){		
		$user_id = get_current_user_id();		
		$merchstock_role = Merch_Stock_User_Functions::getMerchStockRole();
		$offices = array();
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'office'
		);
		$the_query = new WP_Query( $args );
		if ($merchstock_role=="ms_manager"){
			// For the manager only get offices they are connected to
			$the_query = new WP_Query( $args );
			foreach ($the_query->posts as $key => $value) {
				$user_ids = (string)get_post_meta( $value->ID, 'user_ids', true );
				if (strpos($user_ids, '|'.$user_id.'|') !== false){	
					$office = get_post($value->ID);
					array_push($offices, $office);
				}		
			}			
		}
		elseif ($merchstock_role=="ms_headoffice" || $merchstock_role=="ms_admin"){	
			$the_query = new WP_Query( $args );
			$customer_id = get_user_meta($user_id, 'customer_id',true);
			foreach ($the_query->posts as $key => $value) {
				$tmp_customer_id = get_post_meta( $value->ID, 'customer_id', true );
				if ($customer_id==$tmp_customer_id){
					$office = get_post($value->ID);
					array_push($offices, $office);		
				}
			}				
		}
		return $offices;
	}	

	public static function removeOldOrders(){
		$tkn = wp_get_session_token();
		$meta_query = array('relation'=>'AND');
		array_push($meta_query, array('key'=>'wp_token','compare'=>'!=', 'value'=>$tkn));
		array_push($meta_query, array('key'=>'status','value'=>'Init'));		
  		$args = array(
	    	'posts_per_page'   => -1,
	    	'post_type'        => 'order',
	    	'meta_query'		=> $meta_query
		);
		$the_query = new WP_Query( $args );			
		foreach ($the_query->posts as $key => $order) {
			$now =  new DateTime(date('m/d/Y H:i:s', time())); 
			$last_updated_at = new DateTime(date('m/d/Y H:i:s', get_post_meta( $order->ID, 'updated_at', true )));
			$totaltime = $last_updated_at->diff($now);
			$minutes_difference = intval($totaltime->i);
			if ($minutes_difference>10){				
				foreach (Merch_Stock_Functions::getOrderlines($order->ID) as $orderline){
					$tmporderline = json_decode($orderline[0],true);
					wp_delete_post($tmporderline["ID"]);
				}
				wp_delete_post($order->ID);
			}
		}
		return true;
	}

	public static function getUserSessionOrder(){		
		$tkn = wp_get_session_token();
		Merch_Stock_User_Functions::removeOldOrders();
		$office_id = Merch_Stock_User_Functions::getUserOffices()[0]->ID;
		$shipping_box_price = get_post_meta( $office_id, 'shipping_box_price', true );		
		$shipping_box_weight = get_post_meta( $office_id, 'shipping_box_weight', true );		
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'wp_token','value'=>$tkn));
		array_push($meta_query, array('key'=>'status','value'=>'Init'));		
  		$args = array(
	    	'posts_per_page'   => -1,
	    	'post_type'        => 'order',
	    	'meta_query'		=> $meta_query
		);
		$id = null;
		$the_query = new WP_Query( $args );		
		if (count($the_query->posts)==1){
			$order = $the_query->posts[0];
			$id = $order->ID;
		}
		else{
			$id = wp_insert_post(array('post_title'=>'Order', 'post_type'=>'order', 'post_content'=>'demo text'));		
			$invoice_id = wp_insert_post(array('post_title'=>'Order', 'post_type'=>'invoice', 'post_content'=>'demo text'));
			update_post_meta( $id, "user_id", get_current_user_id());
			update_post_meta( $id, "invoice_id", $invoice_id);
			update_post_meta( $id, "office_id", $office_id);
			update_post_meta( $id, "shipping_box_price", $shipping_box_price);
			update_post_meta( $id, "shipping_box_weight", $shipping_box_weight);
			update_post_meta( $id, "status", "Init");
			update_post_meta( $id, "status_changed", date("Y-m-d H:i:s"));
			update_post_meta( $id, "wp_token", $tkn);
			update_post_meta( $invoice_id, "status", "Open");	
			wp_publish_post( $id );			
		}
		Merch_Stock_Order_Functions::touchOrder($id);
		return $id;
	}	

	//  USER CAN FUNCTIONS
	public static function currentUserCanChangeOffice($order_id){
		$order = get_post($order_id);
		$status = get_post_meta( $order_id, 'status', true );
		$role = Merch_Stock_User_Functions::getMerchStockRole();
		if ($status=="Init"){
			return true;	
		}		
		if ($status="Submitted"){
			return false;
		}
		if ($role=="ms_admin" && ($status=="Init"||$status=="Submitted" ) ){
			return true;	
		}
		return false;
	}

	public static function currentUserCanChangeOrderlines($order_id){
		$order = get_post($order_id);
		$status = get_post_meta( $order_id, 'status', true );
		$role = Merch_Stock_User_Functions::getMerchStockRole();
		if ($status=="Cancelled"){
			return false;
		} 
		if ($status=="Approved" && $role=="ms_headoffice"){
			return true;
		} 		
		if ($status=="Approved" && $role=="ms_manager"){
			return false;
		} 		
		if ($status=="Init" || $status = "Submitted"){
			return true;	
		}		
		if ($role=="ms_headoffice" && ($status=="Init"||$status=="Submitted" ) ){
			return true;
		}
		if ($role=="ms_admin" && ($status=="Init"||$status=="Submitted" ) ){
			return true;	
		}
		return false;		
	}	

	public static function getUsersFromCustomer($customer_id){
		$users = get_users();
		$result = array();
		foreach ($users as $user){
			$tmpCustomerId = get_user_meta( $user->ID, 'customer_id', true );
			if ($customer_id==$tmpCustomerId){					
				array_push($result, $user->ID);
			}
		}
		return json_encode($result);
	}

	public static function getUsersFromOrder($order_id){
		$order = get_post($order_id);
		$office = get_post(get_post_meta( $order_id, 'office_id', true ));
		$office_users = get_post_meta( $office->ID, 'user_ids', true );
		$office_ids = explode("|", $office_users);
		$users = array();
		foreach ($office_ids as $id){
			if ($id){
				array_push($users, get_user_by('ID', $id));
			}
		}
		$customer_id = get_post_meta( $order->ID, 'customer_id', true );
		// $customer = get_post(get_post_meta($office->ID,'customer_id',true));
		
		$all_users = get_users();
		foreach ( $all_users as $user ) {
			$role = Merch_Stock_User_Functions::getMerchStockRoleByID($user->ID);
			if ($role=="ms_headoffice"){
				$user_customer_id = get_user_meta( $user->ID, 'customer_id', true );
				if ($user_customer_id==$customer_id){
					if (!in_array( $user, $users)){
						array_push($users, $user);	
					}
				}
			};
		}
		return json_encode($users);
	}	

	public static function getUsersFromProductRequest($product_request_id){
		$product_request = get_post($product_request_id);
		$user_id = get_post_meta( $product_request_id, 'user_id', true );
		$customer_id = get_post_meta( $product_request_id, 'customer_id', true );
		$users = array();
		$product_request = get_post($product_request_id);
		array_push($users, get_user_by('ID', $user_id));
		
		$customer = get_post(get_post_meta($product_request_id,'customer_id',true));
		// var_dump($product_request);
		$customer_id = intval(get_post_meta( $product_request->ID, 'customer_id', true ));
		$customer = get_post($customer_id);
		$all_users = get_users();
		foreach ( $all_users as $user ) {
			$role = Merch_Stock_User_Functions::getMerchStockRoleByID($user->ID);
			if ($role=="ms_headoffice"){
				$user_customer_id = get_user_meta( $user->ID, 'customer_id', true );
				if ($user_customer_id==$customer->ID){
					if (!in_array( $user, $users)){
						array_push($users, $user);	
					}
				}
			};
		}
		return json_encode($users);	
	}

	public static function getUsersFromBackorderRequest($backorder_request_id){
		$backorder_request = get_post($backorder_request_id);
		$user_id = get_post_meta( $backorder_request->ID, 'user_id', true );
		$customer_id = get_post_meta( $backorder_request->ID, 'customer_id', true );
		$users = array();
		array_push($users, get_user_by('ID', $user_id));
		$customer = get_post(get_post_meta($backorder_request->ID,'customer_id',true));
		
		$all_users = get_users();
		foreach ( $all_users as $user ) {
			$role = Merch_Stock_User_Functions::getMerchStockRoleByID($user->ID);
			if ($role=="ms_headoffice"){
				$user_customer_id = get_user_meta( $user->ID, 'customer_id', true );
				if ($user_customer_id==$customer->ID){
					if (!in_array( $user, $users)){
						array_push($users, $user);	
					}
				}
			};
		}
		return json_encode($users);
	}

	public static function getUsersFromBackorder($backorder_id){
		$backorder = get_post($backorder_id);
		$user_id = get_post_meta( $backorder->ID, 'user_id', true );
		$customer_id = get_post_meta( $backorder->ID, 'customer_id', true );
		$users = array();
		array_push($users, get_user_by('ID', $user_id));
		$customer = get_post(get_post_meta($backorder->ID,'customer_id',true));
		
		$all_users = get_users();
		foreach ( $all_users as $user ) {
			$role = Merch_Stock_User_Functions::getMerchStockRoleByID($user->ID);
			if ($role=="ms_headoffice"){
				$user_customer_id = get_user_meta( $user->ID, 'customer_id', true );
				if ($user_customer_id==$customer->ID){
					if (!in_array( $user, $users)){
						array_push($users, $user);	
					}
				}
			};
		}
		return json_encode($users);		
	}	

	public static function getBackorderRequestUser($backorder_request_id){
		$user_id = get_post_meta( $backorder_request_id, 'user_id', true );
		$user = get_user_by( 'id', $user_id );
		return $user->user_firstname . " " . $user->user_lastname;
	}

	public static function getBackorderUser($backorder_id){
		$user_id = get_post_meta( $backorder_id, 'user_id', true );
		$user = get_user_by( 'id', $user_id );
		return $user->user_firstname . " " . $user->user_lastname;
	}	
}