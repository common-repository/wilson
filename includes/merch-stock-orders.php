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
class Merch_Stock_Order_Functions {
	private $notification_functions;
	private $user_functions;
	private $functions;
	public function __construct( ) {
		$this->functions = new Merch_Stock_Functions();
		$this->user_functions = new Merch_Stock_User_Functions();
		$this->notification_functions = new Merch_Stock_Notifications();
	}
		
	public function getOrderStatus($order_id){
		$status = get_post_meta( intval($order_id), 'status', true );
		return $status;	
	}

	public function getOrderOffice($order_id){
		$office_id = get_post_meta( intval($order_id), 'office_id', true );
		if ($office_id>0){
			return get_the_title( intval($office_id) );
		}
	}	

	public function getOrderUser($order_id){
		$user_id = intval(get_post_meta( intval($order_id), 'user_id', true ));
		if ($user_id>0){
			return $this->user_functions->getUserNameByID($user_id);	
		}
	}

	public function getOrderDate($order_id){
		$order = get_post(intval($order_id));
		
		$date = strtotime($order->post_date);
		return date('d M Y - H:i:s', $date);
	}

	public function getOrderShippingCosts($order_id, $return_only_money=false, $return_formatted_money=false, $get_graphic = false, $returnArray = false){
		return $this->getOrderShippingCostsV2(intval($order_id), boolval($return_only_money), boolval($return_formatted_money));
	}

	public function getOrderShippingCostsV2($order_id, $return_only_money=false, $return_formatted_money=false, $get_graphic = false, $returnArray = false){
		$customer_id = $this->getCustomerFromOrder($order_id);
		$office_id = get_post_meta( $order_id, 'office_id', true );
		$shipping_box_price = floatval(get_post_meta( $office_id, 'shipping_box_price', true ));
		$shipping_box_weight = floatval(get_post_meta( $office_id, 'shipping_box_weight', true ));
		$meta_query = array('relation' => 'AND');
		array_push($meta_query, array('key'=>'order_id','compare' => '=','value'=>$order_id));
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'orderline',
		    'meta_query'			=> $meta_query
		);		
		$the_query = new WP_Query( $args );
		$nrOfBoxes = 0; 
		$pctOfBoxesFilled = 0;
		$price = 0.00;	
		
		foreach ($the_query->posts as $orderline){
			$amount = intval(get_post_meta( $orderline->ID, 'amount', true ));		
			$order_id = get_post_meta( $orderline->ID, 'order_id', true );
			$office_id = get_post_meta( $order_id, 'office_id', true );
			$office = get_post($office_id);
			$shipping_costs = get_post_meta( $office->ID, 'shipping_costs', true );		
			$stockline_id = get_post_meta( $orderline->ID, 'stockline_id', true );
			$product_id = get_post_meta( $stockline_id, 'product_id', true );
			$product = get_post($product_id);
			$product_weight = intval(get_post_meta( $product->ID, 'product_weight', true ));
			$total_orderline_weight = $product_weight*$amount;
			$pctOfBoxesFilled += ($total_orderline_weight/$shipping_box_weight);
		}
		$nrOfBoxes =  ceil($pctOfBoxesFilled);
		if ($returnArray){
			$arr = array();
			array_push($arr, $this->functions->formatMoney($nrOfBoxes*$shipping_box_price));
			array_push($arr, $nrOfBoxes);
			array_push($arr, $nrOfBoxes*$shipping_box_price);
			array_push($arr, $shipping_box_price);
			array_push($arr, $this->functions->formatMoney($shipping_box_price));
			return wp_json_encode( $arr );
		}
		if ($return_formatted_money){
			return floatval($nrOfBoxes*$shipping_box_price);
		}
		if ($return_only_money){
			return $this->functions->formatMoney($nrOfBoxes*$shipping_box_price);	
		}
		else{
			return $this->functions->formatMoney($nrOfBoxes*$shipping_box_price) . " / ". $nrOfBoxes . " unit(s)";	
		}		
	}

	public function getCustomerFromProduct($product_id){
		$product = get_post($product_id);
		$customer_id = get_post_meta( $product->ID, 'customer_id', true );
		return $customer_id;
	}

	public function getCustomerFromOrder($order_id){
		$order = get_post($order_id);
		$office_id = get_post_meta( $order->ID, 'office_id', true );
		$customer_id = get_post_meta( $office_id, 'customer_id', true );
		return $customer_id;
	}	

	public function getOrderProductionCosts($order_id){
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'order_id','compare' => '=','value'=>$order_id));
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'orderline',
		    'meta_query'			=> $meta_query
		);		
		$the_query = new WP_Query( $args );
		$total = 0.00;
		foreach ($the_query->posts as $orderline){
			$production_costs = get_post_meta( $orderline->ID ,'production_costs' , true );
			$amount =  get_post_meta( $orderline->ID ,'amount' , true );
			$total += ($amount*$production_costs);
		}
		return $this->functions->formatMoney($total);
	}		

	public function touchOrder($order_id){
		update_post_meta( $order_id, 'updated_at', time(), '' );
	}

	public function getShippingCosts($orderline_id, $return_only_stripped=false){
		$order_id = get_post_meta( $orderline_id, 'order_id', true );
		$product_id = get_post_meta( $orderline_id, 'product_id', true );
		$customer_id = $this->getCustomerFromProduct($product_id);
		$shipping_box_price = floatval(get_post_meta( $customer_id, 'shipping_box_price', true ));
		$shipping_box_weight = floatval(get_post_meta( $customer_id, 'shipping_box_weight', true ));
		$orderline = get_post($orderline_id);
		$amount = get_post_meta( $orderline->ID, 'amount', true );		
		$order_id = get_post_meta( $orderline->ID, 'order_id', true );
		$office_id = get_post_meta( $order_id, 'office_id', true );
		$office = get_post($office_id);
		$shipping_costs = get_post_meta( $office->ID, 'shipping_costs', true );		
		$product_id = get_post_meta( $orderline->ID, 'product_id', true );
		$product = get_post($product_id);
		$product_weight = get_post_meta( $product->ID, 'product_weight', true );
		$total_orderline_weight = $product_weight*$amount;		
		$nrOfBoxes = floor($total_orderline_weight/$shipping_box_weight) + 1;
		if ($return_only_stripped){
			return $nrOfBoxes*$shipping_box_price;
		}
		else{
			return $this->functions->formatMoney($nrOfBoxes*$shipping_box_price) . " / ". $nrOfBoxes . " unit(s)";	
		}
	}		

	public function getOrderTotalPrice($order_id){
		$orderlines = $this->functions->getOrderlines($order_id);
		$total = 0.00;		
		foreach ($orderlines as $orderline){
			$orderline_id = json_decode($orderline[0],true)['ID'];
			$total += (intval(get_post_meta( $orderline_id, 'amount', true )) * floatval(get_post_meta( $orderline_id, 'production_costs', true )));
		}
		return $total;
	}

	public function getStatusChanges($order_id){
		$order = get_post($order_id);
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'order_status_update',
		    'meta_key'			=> 'order_id',
		    'meta_value'		=> $order_id
		);		
		$the_query = new WP_Query( $args );		
		return wp_json_encode( $the_query->posts);
	}

	public function getNumberOfProducts($order_id){
		$total = 0;		
		foreach ($this->functions->getOrderlines($order_id) as $orderline){
			$orderline = json_decode($orderline[0], true);
			$total += intval(get_post_meta( $orderline['ID'], 'amount', true ));
		}
		return $total;
	}
}	