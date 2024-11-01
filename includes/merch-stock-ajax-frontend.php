<?php
/**
 * The ajax-specific functionality of the plugin.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/ajax
 */
/**
 * The menu-specific functionality of the plugin.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/ajax
 * @author     <info@merchandise.nl>
 */
class Merch_Stock_Ajax_Frontend {
	private $plugin_name;
	private $version;
	private $functions;	
	private $order_functions;
	private $notification_functions;
	private $current_role;
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->functions = new Merch_Stock_Functions();
		$this->user_functions = new Merch_Stock_User_Functions();
		$this->notification_functions = new Merch_Stock_Notifications();
		$this->order_functions = new Merch_Stock_Order_Functions();
		$this->isMerchStockUser = $this->user_functions->isMerchStockUser();
		$this->current_role = $this->user_functions->getMerchStockRoleName();
	}
	
	public function ajax_get_product_information() {
		// if (!$this->functions->isAdmin()){		
			global $wpdb; // this is how you get access to the database
			$product_id = intval( $_POST['product_id'] );	
			$product = get_post($product_id);
			// global $post;
			$stockline_id = get_post_meta( $product_id, 'stockline_id', true );
			$stockline = get_post($stockline_id);
			$custom = get_post_custom($stockline_id);
		
			$product_stock = $custom["product_stock"][0];
			$order_per = $custom["order_per"][0];
			$order_term = $custom["order_term"][0];		
			$minimal_order_amount = $custom["minimal_order_amount"][0];
			$custom_product =  get_post_custom($product_id);
			$custom_stock =  get_post_custom($stockline_id);
			$return = array();
			$return["stock"]=get_post($custom_product["stockline_id"]);
			$return["stock_custom"]=$custom_stock;
			$return["product"]=get_post($product_id);
			$return["product_custom"]=$custom_product;
			echo wp_json_encode($return);
			wp_die(); // this is required to terminate immediately and return a proper response
		// }
	}

	public function ajax_get_order_information() {
		global $wpdb; // this is how you get access to the database
		$order_id = intval( $_POST['order_id'] );
		$order_weight = $this->functions->getOrderWeight($order_id);
		$order_price = $this->functions->getOrderPrice($order_id);
		$order = get_post($order_id);
		$orderlines = $this->functions->getOrderlines($order_id);
		$return = array(
			'order'=>get_post($order_id),
			'orderlines'=>$orderlines,
			'order_weight'=>$order_weight,
			'order_price' => $order_price
		);
		echo wp_json_encode($return);
		wp_die(); // this is required to terminate immediately and return a proper response		
	}

	public function ajax_get_office_information() {
		global $wpdb; // this is how you get access to the database
		$office_id = intval( $_POST['office_id'] );
		echo wp_json_encode(get_post($office_id));
		wp_die(); // this is required to terminate immediately and return a proper response		
	}	

	public function ajax_ship_order(){
		$order_id = intval($_POST['order_id']);
		$tracking_number = sanitize_text_field($_POST['tracking_number']);
		$order = get_post($order_id);
		update_post_meta( $order_id, 'status', 'shipped', '' );
		update_post_meta( $order_id, 'tracking_number', $tracking_number, '' );
		$status_update_id = $this->notification_functions->addStatusUpdate($order_id,'order','shipped','');
		$return = array(
			'result'=>true,
			'order'=>$order
		);
		echo wp_json_encode( $return );
		wp_die();
	}

	public function ajax_add_orderline() {
		global $wpdb; // this is how you get access to the database
		$product_id = intval( $_POST['product_id'] );	
		$product = get_post($product_id);
		$amount =  intval($_POST['amount']);
		$order_id = intval( $_POST['order_id']);
		$stockline_id = intval( $_POST['stockline_id']);		
		$orderline_status = "";
		$stockline = get_post($stockline_id);
		$office_id = get_post_meta( $order_id, 'office_id', true );
		
		$product_price = get_post_meta($product_id,'product_price',true);
		$production_costs = get_post_meta($product_id,'production_costs',true);
		$product_weight = get_post_meta($product_id,'product_weight',true);
		// check if this order has an orderline with this product
		$orderHasThisProduct = false;
		$orderlineThatHasThisProduct = false;
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'order_id','value'=>$order_id));
		array_push($meta_query, array('key'=>'stockline_id', 'value'=>$stockline_id));
	  	$args = array(
		   	'posts_per_page'   => -1,
		   	'post_type'        => 'orderline',
		   	'meta_query'		=> $meta_query
		);
		$the_query = new WP_Query( $args );		
		$orderlines = $the_query->posts;		
		foreach ($orderlines as $key => $orderline){
			$orderline_stockline_id = intval(get_post_meta( $orderline->ID, 'stockline_id', true ));
			if ($orderline_stockline_id==$stockline_id){
				$orderHasThisProduct = true;
				$orderlineThatHasThisProduct = get_post($orderline->ID);
				$orderline_id = $orderline->ID;
			}
		}
		$freeItems = $this->functions->getProductStockNumberFreeItems($product_id, $stockline_id);
		if (!$orderHasThisProduct){
			if ($amount<=$freeItems){
				// new orderline, enough stock
				$orderline_id = wp_insert_post(array('post_title'=>get_the_title( $product_id ), 'post_type'=>'orderline'));
				add_post_meta($orderline_id, 'amount', $amount, true);				
				add_post_meta($orderline_id, 'stockline_id', $stockline_id, true);
				add_post_meta($orderline_id, 'product_id', $product_id, true);
				add_post_meta($orderline_id, 'office_id', $office_id, true);
				
				add_post_meta($orderline_id, 'product_price', $product_price , true);
				add_post_meta($orderline_id, 'order_id', $order_id, true);					
				add_post_meta($orderline_id, 'status', 'orderline', true);	
				add_post_meta($orderline_id, 'production_costs', $production_costs, true );		
				add_post_meta($orderline_id, 'product_weight', $product_weight, true );	
				wp_publish_post( $orderline_id );		
				$orderline_status = "orderline";
			}
			else{
				// Not enough free stock, ask for backorder
				$obj = new stdClass();		
				$obj->orderShippingCosts = $this->order_functions->getOrderShippingCosts($order_id, true, false);
				$obj->in_backorder = $this->functions->getBackorderAmount($product_id, $stockline_id); 
				$obj->in_backorder_request = $this->functions->getBackorderRequestAmount($product_id, $stockline_id);
				$obj->missing = $amount-$freeItems;
				$obj->new_stock = $this->functions->getProductStock($product_id, $stockline_id);
				$obj->product = wp_json_encode($product);
				$obj->product_custom = wp_json_encode(get_post_custom( $product_id ));
				$obj->production_costs = $production_costs;
				$obj->product_weight = $product_weight;
				$obj->product_price = $product_price;
				// $obj->product_weight = get_post_meta($product_id,'product_weight',true);
				$obj->orderline = wp_json_encode(get_post($orderline_id)); 
				$obj->amount = get_post_meta($orderline_id,'amount',true);
				$obj->product_id = get_post_meta($orderline_id,'product_id',true);
				$obj->order_id = get_post_meta($orderline_id,'order_id',true);
				$obj->order_price = $this->functions->calculateOrderPrice($order_id);	
				$obj->stockline = wp_json_encode($stockline);
				$obj->orderline_stats = $orderline_status;
				$obj->stockline_custom = wp_json_encode(get_post_custom( $stockline_id ));
				$obj->orderlines = $this->functions->getOrderlines($order_id);
				$obj->pricelines = $this->functions->getPricelines($product_id,"ASC");
				echo wp_json_encode($obj);
				wp_die();
			}
		}
		else{	
			$old_amount = get_post_meta( $orderlineThatHasThisProduct->ID, 'amount', true);
			if ($amount<=$freeItems){
				update_post_meta( $orderlineThatHasThisProduct->ID, 'amount', $amount+$old_amount, '' );
				update_post_meta( $orderlineThatHasThisProduct->ID, 'status','orderline');
				$orderline_status = "orderline";
			}
			else{
				// Not enough free stock, ask for backorder
				$obj = new stdClass();				
				$obj->orderShippingCosts = $this->order_functions->getOrderShippingCosts($order_id, true, false);
				$obj->missing = $amount+$old_amount-$freeItems;
				$obj->new_stock = $this->functions->getProductStock($product_id, $stockline_id);
				$obj->in_backorder = $this->functions->getBackorderAmount($product_id, $stockline_id); 
				$obj->in_backorder_request = $this->functions->getBackorderRequestAmount($product_id, $stockline_id);				
				$obj->product = wp_json_encode($product);
				$obj->product_custom = wp_json_encode(get_post_custom( $product_id ));
				$obj->production_costs = $production_costs;
				$obj->product_price = $product_price;
				$obj->product_weight = get_post_meta($product_id,'product_weight',true);
				$obj->orderline = wp_json_encode(get_post($orderline_id)); 
				$obj->amount = get_post_meta($orderline_id,'amount',true);
				$obj->product_id = get_post_meta($orderline_id,'product_id',true);
				$obj->order_id = get_post_meta($orderline_id,'order_id',true);
				$obj->order_price = $this->functions->calculateOrderPrice($order_id);	
				$obj->stockline = wp_json_encode($stockline);
				$obj->orderline_stats = $orderline_status;
				$obj->stockline_custom = wp_json_encode(get_post_custom( $stockline_id ));
				$obj->orderlines = $this->functions->getOrderlines($order_id);
				$obj->pricelines = $this->functions->getPricelines($product_id,"ASC");
				echo wp_json_encode($obj);
				wp_die();				
			}
		}		
		$obj = new stdClass();
		$obj->product = wp_json_encode($product);
		$obj->orderShippingCosts = $this->order_functions->getOrderShippingCosts($order_id, true, false);
		$obj->in_backorder = $this->functions->getBackorderAmount($product_id, $stockline_id); 
		$obj->in_backorder_request = $this->functions->getBackorderRequestAmount($product_id, $stockline_id);		
		$obj->production_costs = $production_costs;
		$obj->new_stock = $this->functions->getProductStock($product_id, $stockline_id);
		$obj->product_price = $product_price;
		$obj->product_weight = get_post_meta($product_id,'product_weight',true);
		$obj->orderline = wp_json_encode(get_post($orderline_id)); 
		$obj->amount = get_post_meta($orderline_id,'amount',true);
		$obj->product_id = get_post_meta($orderline_id,'product_id',true);
		$obj->order_id = get_post_meta($orderline_id,'order_id',true);
		$obj->order_price = $this->functions->calculateOrderPrice($order_id);	
		$obj->stockline = wp_json_encode($stockline);
		$obj->orderline_stats = $orderline_status;
		$obj->stockline_custom = get_post_custom( $stockline_id );
		$obj->orderlines = $this->functions->getOrderlines($order_id);
		$obj->pricelines = $this->functions->getPricelines($product_id,"ASC");
		$this->order_functions->touchOrder($order_id);
		echo wp_json_encode($obj);
		wp_die();
	}

	public function ajax_delete_orderline() {
		global $wpdb; // this is how you get access to the database
		$orderline_id = intval($_POST['orderline_id']);
		$orderline = get_post($orderline_id);
		$order_id = get_post_meta( $orderline_id, 'order_id', true );
		wp_delete_post($orderline_id,true);
		$obj = new stdClass();
		$obj->order_price = $this->functions->calculateOrderPrice($order_id);
		$obj->orderlines = $this->functions->getOrderlines($order_id);
		echo wp_json_encode($obj);
		wp_die(); 	
	}

	public function ajax_request_backorder() {
		$order_id = intval($_POST['order_id']);
		$product_id = intval($_POST['product_id']);
		$stockline_id = intval($_POST['stockline_id']);
		$amount = intval($_POST['amount']);
		$missing = intval($_POST['missing']);
		$product = get_post($product_id);
		$stockline = get_post($stockline_id);
		$stockline_custom = get_post_custom($stockline_id);
		$product_custom = get_post_custom( $product_id );
		$minimal_order_amount = $product_custom["minimal_order_amount"][0];
		$order_per = $product_custom["order_per"][0];
		$nr_items_left = $stockline_custom['amount'][0];
		$backorderAmount = 0;
		if ($amount<=$minimal_order_amount){
			$backorderAmount = $minimal_order_amount;
		}
		else{
			$backorderAmount = ((floor($amount / $order_per)) + 1) * $order_per;
		}
		$old_production_costs = $product_custom["production_costs"][0];
		$new_production_costs = $this->functions->calculateNewProductionCosts($product_id, $amount);
		$backorder_id = wp_insert_post(array('post_title'=>'Backorder '.get_the_title( $product_id ), 'post_type'=>'backorder'));
		update_post_meta( $product->ID, 'production_costs', $new_production_costs, '' );
		update_post_meta( $backorder_id, 'amount', $backorderAmount, '' );
		update_post_meta( $backorder_id, 'product_id', $product_id, '' );	
		update_post_meta( $backorder_id, 'stockline_id', $stockline->ID, '' );	
		update_post_meta( $backorder_id, 'status', 'Pending', '' );	
		$obj = new stdClass();		
		$obj->backorder_id = $backorder_id;
		$obj->new_stock = $backorderAmount+$amount;
		$obj->new_production_costs = $this->functions->formatMoney($new_production_costs);
		echo wp_json_encode($obj);
		wp_die(); 	
	}

	public function ajax_request_backorder_v2(){
		$product_id = intval($_POST["product_id"]);
		$stockline_id = intval($_POST["stockline_id"]);
		$stockline = get_post($stockline_id);
		$current_stock = intval(get_post_meta( $stockline->ID, 'product_stock', true ));
		$amount = intval($_POST["amount"]);
		$product = get_post($product_id);		
		$backorder_id = wp_insert_post(array('post_title'=>'Backorder '.get_the_title( $product_id ), 'post_type'=>'backorder'));
		update_post_meta( $backorder_id, 'amount', $amount, '' );
		update_post_meta( $backorder_id, 'stockline_id', $stockline_id, '' );	
		update_post_meta( $backorder_id, 'status', 'Pending', '' );			
		$product_stock = $this->functions->getProductStock($product_id, $stockline_id);
		$obj = new stdClass();		
		$obj->result = true;		
		$obj->product_id = $product_id;
		$obj->stockline_id = $stockline_id;
		$obj->newamount = intval($amount)+$current_stock;
		$obj->current_stock = wp_json_encode( $product_stock );
		$obj->free_items = $this->functions->getProductStockNumberFreeItems($product_id, $stockline_id);
		echo wp_json_encode($obj);
		wp_die(); 		
	}

	public function request_backorder_manager(){
		$product_id = intval($_POST["product_id"]);
		$amount = intval($_POST["amount"]);
		$available = intval($_POST["available"]);
		$stockline_id = intval($_POST["stockline_id"]);				
		$backorder_request_id = wp_insert_post(array('post_title'=>'Backorder request '.get_the_title( $product_id ) . " " . get_the_title( $stockline_id ), 'post_type'=>'backorder_request'));
		update_post_meta( $backorder_request_id, 'status', 'pending', '' );
		update_post_meta( $backorder_request_id, 'amount', $amount, '' );
		update_post_meta( $backorder_request_id, 'type', 'backorder_request', '' );
		update_post_meta( $backorder_request_id, 'available', $available, '' );
		update_post_meta( $backorder_request_id, 'stockline_id', $stockline_id, '' );
		update_post_meta( $backorder_request_id, 'user_id', wp_get_current_user()->ID, '' );
		$status_update_id = $this->notification_functions->addStatusUpdate($backorder_request_id,'backorder','requested','');
		$status_update_id = $this->notification_functions->addStatusUpdate($backorder_request_id,'backorder','pending','');
		$obj = new stdClass();
		$obj->result = true;
		$obj->backorder_request = wp_json_encode( get_post($backorder_request_id) );
		$obj->backorder_request_custom = wp_json_encode( get_post_custom($backorder_request_id) );
		$this->notification_functions->sendBackorderRequestChangeNotifications($backorder_request_id);
		echo wp_json_encode($obj);
		wp_die(); 			
	}	
	
	public function ajax_request_product(){
		$message = sanitize_text_field($_POST['message']);
		$customer_id = intval($_POST['customer_id']);
		$status = 'pending';
		if ($this->current_role == "headoffice"){
			$status = 'accepted';
		}
		$product_request_id = wp_insert_post(array('post_title'=>'Product request '.get_the_title( $customer_id ) , 'post_type'=>'product_request'));
		update_post_meta( $product_request_id, 'message', $message);
		update_post_meta( $product_request_id, 'status', $status);
		update_post_meta( $product_request_id, 'user_id', get_current_user_id());
		$status_update_id = $this->notification_functions->addStatusUpdate($product_request_id,'backorder',$status,'');
		echo $status_update_id;
		wp_die();
		$this->notification_functions->sendProductRequestNotifications($product_request_id);
		$obj = new stdClass();
		$obj->result = true;
		$obj->message = $message;
		echo wp_json_encode( $obj);
		wp_die();
	}
}
