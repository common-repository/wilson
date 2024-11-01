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
class Merch_Stock_Notifications {
	private $user_functions;	
	
	public function __construct( ) {
		$this->user_functions = new Merch_Stock_User_Functions();			
	}	

	public function sendMail($to, $subject, $message){
		// wp_mail($to, $subject, $message, '',  array() );
	}

	public function sendBackorderRequestChangeNotifications($backorder_request_id){
		$backorder_request = get_post($backorder_request_id);
		$status = get_post_meta( $backorder_request->ID, 'status', true );
		$user_id = get_post_meta( $backorder_request->ID, 'user_id', true );
		$customer_id = get_post_meta( $backorder_request->ID, 'customer_id', true );
		$users = json_decode($this->user_functions->getUsersFromBackorderRequest($backorder_request_id),true);
		foreach ($users as $key => $user) {
			$not_id = wp_insert_post(array('post_title'=>'Notification '.get_the_title( $order_id ), 'post_type'=>'notification'));					
			update_post_meta( $not_id, 'user_id', $user["ID"], '' );
			update_post_meta( $not_id, 'status', 'new', '' );
			update_post_meta( $not_id, 'item_id', $backorder_request_id, '' );
			update_post_meta( $not_id, 'type', 'backorder_request', '' );
			update_post_meta( $not_id, 'timestamp', date("Y-m-d H:i:s"), '' );
			update_post_meta( $not_id, 'text', 'Backorderrequest #'.$backorder_request_id.' status changed to ' . $status , '' );
			$this->sendMail('web@merchandise.nl','Notification backorder request update', $backorder_request_id . " is updated!");
		}
	}

	public function sendBackorderChangeNotifications($backorder_id){
		$backorder = get_post($backorder_id);
		$users = json_decode($this->user_functions->getUsersFromBackorder($backorder_id),true);
		$status = get_post_meta( $backorder->ID, 'status', true );
		foreach ($users as $key => $user) {
			$not_id = wp_insert_post(array('post_title'=>'Notification '.get_the_title( $order_id ), 'post_type'=>'notification'));					
			update_post_meta( $not_id, 'user_id', $user["ID"], '' );
			update_post_meta( $not_id, 'type', 'backorder', '' );
			update_post_meta( $not_id, 'status', 'new', '' );
			update_post_meta( $not_id, 'item_id', $backorder_id, '' );
			update_post_meta( $not_id, 'timestamp', date("Y-m-d H:i:s"), '' );
			update_post_meta( $not_id, 'text', 'Backorder #'.$backorder_id.' status changed to ' . $status , '' );
			$this->sendMail('web@merchandise.nl','Notification backorder update', $backorder_id . " is updated!");
		}		
	}

	public function sendStatusChangeNotifications($order_id, $status){
		$order = get_post($order_id);
		$users = json_decode($this->user_functions->getUsersFromOrder($order_id),true);
		foreach ($users as $key => $user) {
			// create notification
			$not_id = wp_insert_post(array('post_title'=>'Notification '.get_the_title( $order_id ), 'post_type'=>'notification'));		
			update_post_meta( $not_id, 'order_id', $order_id, '' );
			update_post_meta( $not_id, 'user_id', $user["ID"], '' );
			update_post_meta( $not_id, 'type', 'order', '' );
			update_post_meta( $not_id, 'item_id', $order_id, '' );
			update_post_meta( $not_id, 'status', 'new', '' );
			update_post_meta( $not_id, 'timestamp', date("Y-m-d H:i:s"), '' );
			update_post_meta( $not_id, 'text', 'Order #' . $order_id . ' status updated to '.$status, '' );
			$this->sendMail('web@merchandise.nl','Notification order update', $order_id . " is updated!");
		}
	}

	public function getNotifications($number = -1){
		$user = wp_get_current_user();
		$meta_query = array('relation' => 'AND');
		array_push($meta_query, array('key'=>'user_id','compare' => '=','value'=>$user->ID));				
		array_push($meta_query, array('key'=>'status','compare' => '=','value'=>'new'));		
		$args = array(
		    'posts_per_page'   => $number,
		    'post_type'        => 'notification',
		    'meta_query'		=> $meta_query
		);	
		$the_query = new WP_Query( $args );		
		return $the_query->posts;			
	}

	public function sendProductRequestNotifications($product_request_id){
		$product_request = get_post($product_request_id);
		$status = get_post_meta( $product_request->ID, 'status', true );
		$users = json_decode($this->user_functions->getUsersFromProductRequest($product_request_id),true);
		foreach ($users as $key => $user) {
			// create notification
			$not_id = wp_insert_post(array('post_title'=>'Notification '.get_the_title( $order_id ), 'post_type'=>'notification'));		
			// update_post_meta( $not_id, 'user_id', $user["ID"], '' );
			update_post_meta( $not_id, 'user_id', intval($user["ID"]) );
			update_post_meta( $not_id, 'type', 'product_request' );
			update_post_meta( $not_id, 'item_id', $product_request_id, '' );
			update_post_meta( $not_id, 'status', 'new' );
			update_post_meta( $not_id, 'timestamp', date("Y-m-d H:i:s"), '' );
			update_post_meta( $not_id, 'text', 'New product request #'.$product_request_id.' status changed to ' . $status , '' );
			
		}		
	}

	public function updateNotifications($item_id, $item_type){
		$user = wp_get_current_user();
		$meta_query = array('relation' => 'AND');
		array_push($meta_query, array('key'=>'user_id','compare' => '=','value'=>$user->ID));				
		array_push($meta_query, array('key'=>'status','compare' => '=','value'=>'new'));		
		array_push($meta_query, array('key'=>'item_id','compare' => '=','value'=>$item_id));		
		array_push($meta_query, array('key'=>'type','compare' => '=','value'=>$item_type));
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'notification',
		    'meta_query'		=> $meta_query
		);	
		$the_query = new WP_Query( $args );		
		foreach ($the_query->posts as $key => $post){
			update_post_meta( $post->ID, 'status', 'read', '' );
		}		
	}

	public function sendProductAmountWarningNotification($product_id){
		$product = get_post($product_id);
		$customer_id = get_post_meta( $product->ID, 'customer_id', true );
		$users = json_decode($this->user_functions->getHeadofficeUsersFromCustomer($customer_id),true);
		foreach ($users as $key => $user) {
			// create notification
			$not_id = wp_insert_post(array('post_title'=>'Notification '.get_the_title( $product->ID ) . ' is running out of stock', 'post_type'=>'notification'));		
			// update_post_meta( $not_id, 'order_id', $order_id, '' );
			update_post_meta( $not_id, 'user_id', $user["ID"], '' );
			update_post_meta( $not_id, 'type', 'product_out_of_stock', '' );
			update_post_meta( $not_id, 'item_id', $product_id, '' );
			update_post_meta( $not_id, 'status', 'new', '' );
			update_post_meta( $not_id, 'timestamp', date("Y-m-d H:i:s"), '' );
			update_post_meta( $not_id, 'text', get_the_title( $product->ID ) . ' is running out of stock');
		}		
	}

	public function addStatusUpdate($item_id, $type, $status, $comments){
		$status_update_id = wp_insert_post(array('post_title'=>$type.' #'.$item_id .' changed to ' . $status, 'post_type'=>'order_status_update'));
		update_post_meta( $status_update_id, 'order_id', $item_id, '' );
		update_post_meta( $status_update_id, 'status', $status, '' );
		update_post_meta( $status_update_id, 'time', date("Y-m-d H:i:s"), '' );	
		update_post_meta( $status_update_id, 'user_id', wp_get_current_user()->ID, '' );		
		if (strlen($comments)>0){
			update_post_meta( $status_update_id, 'comments', $comments, '' );		
		}
		return $status_update_id;
	}

	public function drawTimeLine($updates, $status, $type=""){
		if (count($updates)==0){
			return '';
		}
		$html = "";
		$html .= "<header class='card-header'>";
		$html .= "<div class='card-actions'>";
		$html .= "</div>";				
		$html .= "<h2 class='card-title'>" . $type . " status: $status</h2>";
		$html .= "</header>";
		$html .= "<div class='card-body'>";
		$html .= "<div class='col-lg-12'>";
		$html .= "<div class='timeline timeline-simple'>";
		$html .= "<div class='tm-body'>";
		$html .= "<ol class='tm-items'>";
		foreach ($updates as $status_change){	
			$statuschange =  get_post($status_change["ID"]);
			$status = get_post_meta( $statuschange->ID, 'status', true );
			$bubble = Merch_Stock_Functions::getStatusHTML($status);
			$time = get_post_meta( $statuschange->ID, 'time', true );
			$comments = get_post_meta( $statuschange->ID, 'comments', true );
			$user_id = get_post_meta( $statuschange->ID, 'user_id', true );
			$user = get_user_by( 'ID', $user_id );			
			$html .= "<li>";
			$html .= "<div class='tm-box'>";
			$html .= "<p class='text-muted mb-0'>";
			$html .= $time . " / changed by: ";
			$html .= $user->user_firstname  . " " . $user->user_lastname;
			$html .= "</p>";
			$html .= "<p>";
			$html .="<h2 class='card-title'>$bubble</h2>";
			$html .="</p>";
			$html .="<br>";	
			$html .="<p style='margin-top:25px'>";	
			$html .= get_the_title( $statuschange->ID );
			if (strlen($comments)>0){
			$html .= "<div>$comments</div>";
			}		
			$html .= "</p>";
			$html .= "</div>";
			$html .= "</li>";
		}
		return $html;
	}
}