<?php
include plugin_dir_path(  __FILE__  ).'../admin/dompdf/autoload.inc.php';
/**
 * The form-specific functionality of the plugin.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/form
 */
/**
 * The menu-specific functionality of the plugin.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/ajax
 * @author     <info@merchandise.nl>
 */
use Dompdf\Dompdf;
use Dompdf\Options;

class Merch_Stock_Form {
	
	private $plugin_name;
	private $version;
	private $functions;	
	private $user_functions;
	private $current_role;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->functions = new Merch_Stock_Functions();
		$this->user_functions = new Merch_Stock_User_Functions();
		$this->notification_functions = new Merch_Stock_Notifications();
		$this->isMerchStockUser = $this->user_functions->isMerchStockUser();
		$this->current_role = $this->user_functions->getMerchStockRoleName();
	}

	public function save_order() {
		$order_id = intval($_POST["order_id"]);
		$comments = sanitize_text_field($_POST["comments"]);
		$office_id = intval($_POST["hidden-order-office"]);
		$order = get_post($order_id);
		$dateTime = new DateTime($_POST['post_date']);
		$update_post = array(
	        'post_type' => 'order',
	        'ID' => $order_id,
	        'edit_date' => true,
	        'post_date' => $dateTime
	    );
	    update_post_meta($order_id, 'office_id', $office_id);	
	    update_post_meta($order_id, 'comments', $comments);	
    	wp_publish_post($order_id);
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'finished-order',
		    	'&order_id' => $order_id,
			), admin_url('admin.php') )
   		);
    	exit;
	}

	public function update_order(){		
		$status = sanitize_text_field($_POST["order_status"]);
		$order_id = intval($_POST["order_id"]);
		$comments = sanitize_text_field($_POST["comments"]);
		$order = get_post($order_id);	
		$cur_order_status = get_post_meta( $order_id, 'status', true );
		if ($cur_order_status=="Init"){
			update_post_meta( $order_id, "references", sanitize_text_field($_POST["references"]));
			update_post_meta( $order_id, "comments", sanitize_text_field($_POST["comments"]));
			if (intval($_POST["office_id"])>0){
				update_post_meta( $order_id, 'office_id', intval($_POST["office_id"]) , '' );	
				$office_id = intval($_POST["office_id"]);
				$office_shipping_costs = get_post_meta( $office_id, 'office_shipping_costs', true );
				update_post_meta( $id, "office_shipping_costs", $shipping_costs);
			}			
		}
		$orderlines = $this->functions->getOrderlines($order_id);		
		if(count($orderlines)>0){
			$changedAmounts = sanitize_text_field($_POST["changed-amounts"]);
			$errors = array();
			$changedPrices = sanitize_text_field($_POST["changed-prices"]);
			$newAmount = 0;
			foreach ($changedAmounts as $orderline_id => $amount) {
				if ($amount==0){
					wp_delete_post($orderline_id,true);
				}
				else{
					$orderline = get_post($orderline_id);
					$stockline_id = get_post_meta( $orderline->ID, 'stockline_id', true );
					$product_id = get_post_meta( $orderline->ID, 'product_id', true );
					$avbl = $this->functions->getProductStock($product_id, $stockline_id);
					$stock = intval(($avbl)["stock"]);
					$init = intval(($avbl)["init"]);
					$onthisorder = $this->functions->productsOnOrder($order_id, $stockline_id);
					if (($stock - $init + $onthisorder)>=$amount){
						update_post_meta( $orderline_id, 'amount', $amount, '' );					
					}
					else{
						array_push($errors, $stockline_id);
					}
					
				}
			}	
		}
		if  (($cur_order_status=="Submitted"||$cur_order_status=="submitted") && ($status=="Shipped"||$status=="shipped") && $this->current_role == "admin"){
			$tracking_number = sanitize_text_field($_POST["tracking_number"]);
			update_post_meta( $order_id, "tracking_number", $tracking_number);
		}
		if (count($errors)>0){
	   		wp_redirect(  
		   		add_query_arg( array(
			    	'page' => 'order',
			    	'order_id' => $order_id,
			    	'errors' => wp_json_encode( $errors ),
				), admin_url('admin.php') )
	   		);
	    	exit;
	    	wp_die();
		}
		else{
			if ($status!=="Init"){
				update_post_meta( $order_id, 'status', $status, '' );	
				$status_update_id = $this->notification_functions->addStatusUpdate($order->ID,'order',$status,'');	
			}
			if ($status=="Submitted"||$status=="submitted"){
				wp_redirect(  
			   		add_query_arg( array(
				    	'page' => 'order-submitted',
				    	'order_id' => $order_id
					), admin_url('admin.php') )
		   		);
		   		exit;
			}
	   		wp_redirect(  
		   		add_query_arg( array(
			    	'page' => 'order',
			    	'order_id' => $order_id,
			    	'errors' => 'none',
				), admin_url('admin.php') )
	   		);
	    	exit;
	    	wp_die();			
		}
	}		

	public function invoice_pdf(){ 
		$order_id = intval($_POST["order_id"]);
		$order = get_post($order_id);
		$options = new Options();
		
		$options->set('defaultFont', 'Open Sans');
		$options->set('isRemoteEnabled', TRUE);
		$options->set('debugKeepTemp', TRUE);
		$options->set('isHtml5ParserEnabled', TRUE);
		$options->set('chroot', '/');
		$options->setIsRemoteEnabled(true);
		$dompdf = new Dompdf($options);
		$html = $this->functions->getInvoicePDFHtml($order_id);
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream('Packing List #'.$order_id.'.pdf');		
	}

	public function decline_backorder(){
		$backorder_id = intval($_POST["backorder_id"]);
		$backorder = get_post($backorder_id);
		update_post_meta( $backorder->ID, 'status', 'declined', '' );
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'backorders'		    	
			), admin_url('admin.php') )
   		);
    	exit;		
	}	

	public function approve_order(){
		$order_id = intval($_POST["order_id"]);
		$order = get_post($order_id);
		update_post_meta( $order_id, 'status', 'Accepted');		
		$this->notification_functions->sendStatusChangeNotifications($order_id, 'Accepted');
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'orders',
			), admin_url('admin.php') )
   		);
    	exit;	
	}

	public function decline_order(){
		$order_id = intval($_POST["order_id"]);
		$order = get_post($order_id);
		update_post_meta( $order_id, 'status', 'Declined');		
		$this->notification_functions->sendStatusChangeNotifications($order_id, 'Declined');
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'orders',
			), admin_url('admin.php') )
   		);
    	exit;	
	}	

	public function approve_backorder(){
		$backorder_id = intval($_POST["backorder_id"]);
		$backorder = get_post($backorder_id);
		$stockline_id = get_post_meta( $backorder->ID, 'stockline_id', true );
		$stockline = get_post($stockline_id);
		$amount = intval(get_post_meta( $backorder->ID, 'amount', true ));
		$tmp = intval(get_post_meta( $stockline_id , 'product_stock', true ));
		update_post_meta( $stockline->ID, 'product_stock', $amount+$tmp, '' );
		update_post_meta( $backorder->ID, 'status', 'accepted', '' );
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'backorders'		    	
			), admin_url('admin.php') )
   		);
    	exit;			
	}

	public function remove_orderline(){
		$orderline_id = intval($_POST["orderline_id"]);
		$order = get_post(get_post_meta( $orderline_id, 'order_id', true ));
		wp_delete_post($orderline_id,true);
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'order',
		    	'order_id' => $order->ID
			), admin_url('admin.php') )
   		);
    	exit;			
	}
 
	public function update_office(){
		$office_id = intval($_POST["office_id"]);
		$office = get_post($_POST["office_id"]);
		$shipping_box_price = floatval($_POST["shipping_box_price"]);
		$shipping_box_weight = floatval($_POST["shipping_box_weight"]);	
		$addressline1 = sanitize_text_field($_POST["addressline1"]);
		$addressline2 = sanitize_text_field($_POST["addressline2"]);
		$addressline3 = sanitize_text_field($_POST["addressline3"]);
		$number = sanitize_text_field($_POST["number"]);
		$postal_code = sanitize_text_field($_POST["postal_code"]);
		$city = sanitize_text_field($_POST["city"]);
		$country = sanitize_text_field($_POST["country"]);
		update_post_meta( $office_id, "addressline1", $addressline1, '' );
		update_post_meta( $office_id, "addressline2", $addressline2, '' );
		update_post_meta( $office_id, "addressline3", $addressline3, '' );
		update_post_meta( $office_id, "number", $number, '' );
		update_post_meta( $office_id, "postal_code", $postal_code, '' );
		update_post_meta( $office_id, "city", $city, '' );
		update_post_meta( $office_id, "country", $country, '' );
		update_post_meta( $office_id, "shipping_box_price", $shipping_box_price, '' );
		update_post_meta( $office_id, "shipping_box_weight", $shipping_box_weight, '' );
   		wp_redirect(  
	   		add_query_arg( array( 
		    	'page' => 'office',
		    	'office_id' => $office_id,
			), admin_url('admin.php') )
   		);
    	exit;		
	}

	public function create_backorder_headoffice(){
		$product_id = intval($_POST["product_id"]);
		$stockline_id = intval($_POST["stockline_id"]);
		$amount = intval($_POST["backorder-amount"]);
		$backorder_request_id = wp_insert_post(array('post_title'=>'Backorder '.get_the_title( $product_id ), 'post_type'=>'backorder_request'));
		update_post_meta( $backorder_request_id, 'amount', $amount, '' );
		update_post_meta( $backorder_request_id, 'stockline_id', $stockline_id, '' );	
		update_post_meta( $backorder_request_id, 'status', 'accepted', '' );				 			
		update_post_meta( $backorder_request_id, 'user_id', wp_get_current_user()->ID, '' );
		$status_update_id = $this->notification_functions->addStatusUpdate($backorder_request_id,'backorder','accepted','');		
		$this->notification_functions->sendBackorderRequestChangeNotifications($backorder_request_id);
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'backorder',
		    	'backorder_id' => $backorder_request_id,
			), admin_url('admin.php') )
   		);
    	exit;			
	}

	public function reject_backorder(){
		$backorder_request_id = intval($_POST['backorder_request_id']);
		update_post_meta( $backorder_request_id, 'status', 'declined', '' );		
		$this->notification_functions->sendBackorderRequestChangeNotifications($backorder_request_id);
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'backorder-requests',
			), admin_url('admin.php') )
   		);
    	exit;		
	}

	public function update_backorder_request(){
		$backorder_request_id = intval($_POST['backorder_request_id']);		
		$backorder_request = get_post($backorder_request_id);
		$old_status = get_post_meta( $backorder_request_id, 'status', true );
		$backorder_status =  sanitize_text_field($_POST["backorder_status"]);
		$stockline_id = get_post_meta( $backorder_request->ID, 'stockline_id', true );
		$product_id = get_post_meta( $backorder_request->ID, 'product_id', true );
		$comments = sanitize_text_field($_POST["comments"]);
		
		if (($old_status=="pending"||$old_status=="Pending")&&($backorder_status=="refused"||$backorder_status=="Refused")){
			// remove backorder request
			update_post_meta( $backorder_request_id, 'status', $backorder_status, '' );
			update_post_meta( $backorder_request_id, 'comments', $comments, '' );
			$status_update_id = $this->notification_functions->addStatusUpdate($backorder_request_id,'backorder','refused',$comments);	
	   		wp_redirect(  
		   		add_query_arg( array(
			    	'page' => 'backorders',
				), admin_url('admin.php') )
	   		);
	    	exit;							
		}
		if ( ( ($old_status=="pending"||$old_status=="Pending") )&&($backorder_status=="accepted"||$backorder_status=="Accepted")){
			update_post_meta( $backorder_request_id, 'status', $backorder_status, '' );
			update_post_meta( $backorder_request_id, 'comments', $comments, '' );		
				
			$backorder_amount = intval($_POST['backorder-amount']);
			update_post_meta( $backorder_request_id, 'amount', $backorder_amount, '' );	
			$comments = 'ordered ' + intval($backorder_amount) + ' items';
			$status_update_id = $this->notification_functions->addStatusUpdate($backorder_request_id,'backorder','accepted',$comments);	
	   		wp_redirect(  
		   		add_query_arg( array(
			    	'page' => 'backorders',
				), admin_url('admin.php') )
	   		);
	    	exit;				
		}
		if ($backorder_status=="updated"||$backorder_status=="Updated"){		
			$status_update_id = $this->notification_functions->addStatusUpdate($backorder_request_id,'backorder','updated','');	
			update_post_meta( $backorder_request_id, 'comments', sanitize_text_field($_POST['comments']), '' );
			$status_update_id = $this->notification_functions->addStatusUpdate($backorder_request_id,'backorder','updated','');	
			update_post_meta( $status_update_id, 'order_id', $backorder_request_id, '' );
			update_post_meta( $status_update_id, 'status', 'updated', '' );
			update_post_meta( $status_update_id, 'comments', $comments, '' );
			update_post_meta( $status_update_id, 'time', date("Y-m-d H:i:s"), '' );	
			update_post_meta( $status_update_id, 'user_id', wp_get_current_user()->ID, '' );
		}
 
		$this->notification_functions->sendBackorderRequestChangeNotifications($backorder_request_id);
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'backorder_request',
		    	'backorder_request_id' => $backorder_request_id
			), admin_url('admin.php') )
   		);
    	exit;		
	}

	public function update_backorder(){
		$backorder_id = intval($_POST['backorder_id']);
		$comments = sanitize_text_field($_POST["comments"]);
		$status = sanitize_text_field($_POST["backorder_status"]);
		update_post_meta( $backorder_id, 'comments', $comments, '' );
		update_post_meta( $backorder_id, 'status', $status, '' );
		if ($status=='updated'){
			$status_update_id = $this->notification_functions->addStatusUpdate($backorder_id,'backorder','updated',$comments);
		}
		else{
		$status_update_id = $this->notification_functions->addStatusUpdate($backorder_id,'backorder','updated',$comments);
		}
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'backorder',
		    	'backorder_id' => $backorder_id
			), admin_url('admin.php') )
   		);
    	exit;					
	}



	public function update_product_request(){
		$product_request_id = intval($_POST["product_request_id"]);
		$comments = sanitize_text_field($_POST["comments"]);	
		$change_status = sanitize_text_field($_POST["change_status"]);
		if ($change_status=="refused"){
			update_post_meta( $product_request_id, 'status', 'refused', '' );
			$status_update_id = $this->notification_functions->addStatusUpdate($product_request_id,'backorder','refused',$comments);
			update_post_meta( $product_request_id, 'comments', $comments, '' );
			$this->notification_functions->sendProductRequestNotifications($product_request_id);
	   		wp_redirect(  
		   		add_query_arg( array(
			    	'page' => 'product-requests',
				), admin_url('admin.php') )
	   		);
	    	exit;						
		}
		elseif  ($change_status=="accepted"){
			$status_update_id = $this->notification_functions->addStatusUpdate($product_request_id,'backorder','accepted',$comments);
			update_post_meta( $product_request_id, 'status', 'accepted', '' );
			update_post_meta( $product_request_id, 'comments', $comments, '' );
			$this->notification_functions->sendProductRequestNotifications($product_request_id);
	   		wp_redirect(  
		   		add_query_arg( array(
			    	'page' => 'product-requests',
				), admin_url('admin.php') )
	   		);
	    	exit;					
		}		
		else {
			$status_update_id = $this->notification_functions->addStatusUpdate($product_request_id,'backorder','updated',$comments);
		}
		update_post_meta( $product_request_id, 'comments', $comments, '' );
		$this->notification_functions->sendProductRequestNotifications($product_request_id);
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'product_request',
		    	'product_request_id' => $product_request_id
			), admin_url('admin.php') )
   		);
    	exit;			
	}

	public function create_backorder(){
		$backorder_amount = intval($_POST["backorder_amount"]);
		$stockline_id = intval($_POST["stockline_id"]);
		$stockline = get_post($stockline_id);
		$product_id = get_post_meta( $stockline->ID, 'product_id', true );
		$product = get_post($product_id);
		$backorderAmount = intval($_POST["backorder_amount"]);
		$backorder_id = wp_insert_post(array('post_title'=>'Backorder '.get_the_title( $product_id ), 'post_type'=>'backorder'));
		update_post_meta( $backorder_id, 'amount', $backorderAmount, '' );
		update_post_meta( $backorder_id, 'product_id', $product_id, '' );	
		update_post_meta( $backorder_id, 'stockline_id', $stockline_id, '' );	
		update_post_meta( $backorder_id, 'status', 'Pending', '' );		
		$current_orderline_id = intval($_POST["orderline_id"]);
		$current_orderline = get_post($current_orderline_id);
		$current_orderline_amount = intval(get_post_meta( $current_orderline->ID, 'amount', true ));
		if ($backorder_amount>=$current_orderline_amount){
			update_post_meta( $current_orderline_id, 'status', 'orderline', '' );						
		}
		
		$current_order_id = intval(get_post_meta( $current_orderline_id, 'order_id', true ));
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'order',
		    	'order_id' => $current_order_id,
			), admin_url('admin.php') )
   		);
    	exit;
	}	

	public function delete_wilson_products(){
		$ids = sanitize_text_field($_POST["ids"]);
		$JSON_ids = json_decode(str_replace("\\","", $ids),true);
		foreach ($JSON_ids as $product_id){
			wp_delete_post($product_id);
		}
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'products',
			), admin_url('admin.php') )
   		);
    	exit;	
	}

	public function delete_wilson_product_requests(){
		$ids = sanitize_text_field($_POST["ids"]);
		$JSON_ids = json_decode(str_replace("\\","", $ids),true);
		foreach ($JSON_ids as $product_request_id){
			wp_delete_post($product_request_id);
		}
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'product_requests',
			), admin_url('admin.php') )
   		);
    	exit;		
	}

	public function delete_wilson_backorders(){
		$ids = sanitize_text_field($_POST["ids"]);
		$JSON_ids = json_decode(str_replace("\\","", $ids),true);
		foreach ($JSON_ids as $backorder_id){
			wp_delete_post($backorder_id);
		}
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'backorders',
			), admin_url('admin.php') )
   		);
    	exit;		
	}	
	
	public function delete_wilson_orders(){
		$ids = sanitize_text_field($_POST["ids"]);
		$JSON_ids = json_decode(str_replace("\\","", $ids),true);
		foreach ($JSON_ids as $order_id){
			wp_delete_post($order_id);
		}
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'orders',
			), admin_url('admin.php') )
   		);
    	exit;		
	}

	public function delete_wilson_offices(){
		$ids = sanitize_text_field($_POST["ids"]);
		$JSON_ids = json_decode(str_replace("\\","", $ids),true);
		foreach ($JSON_ids as $office_id){
			wp_delete_post($office_id);
		}
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'offices',
			), admin_url('admin.php') )
   		);
    	exit;		
	}








}