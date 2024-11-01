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
class Merch_Stock_Functions {
	private $user_functions;
	private $functions;
	private $notification_functions;

	public function __construct( ) {
		$this->user_functions = new Merch_Stock_User_Functions();
		$this->notification_functions = new Merch_Stock_Notifications();
	}	

	public function drawAddress($id){		
		$post = get_post($id);
		// return get_post_meta($post->ID, 'addressline1', true);
		$html = "";
		$addressline1 = esc_html(get_post_meta($post->ID, 'addressline1', true));
		$addressline2 = esc_html(get_post_meta($post->ID, 'addressline2', true));
		$addressline3 = esc_html(get_post_meta($post->ID, 'addressline3', true));
		$postal_code = esc_html(get_post_meta($post->ID, 'postal_code', true));
		$city = esc_html(get_post_meta($post->ID, 'city', true));
		$region = esc_html(get_post_meta($post->ID, 'region', true));
		$county = esc_html(get_post_meta($post->ID, 'county', true));
		$country = esc_html(get_post_meta($post->ID, 'country', true));
		$telephone = esc_html(get_post_meta($post->ID, 'telephone', true));
		$email = esc_html(get_post_meta($post->ID, 'email', true));
	}

	public function getOrderChart($office_id){
		$html = "";
		$html .= '<div id="salesSelectorItems" class="chart-data-selector-items mt-3">';
		$html .= '<div class="chart chart-sm" data-sales-rel="Porto Admin" id="flotDashSales'.$office_id.'" class="chart-active" style="height: 203px;"></div>';
		$html .= '<script>';
		$currentMonth = date('m', strtotime('-1 month'));
		$currentYear =  date('y');
		$html .= '		var flotDashSales'.$office_id.'Data = [{';
		$html .= '		    data: [';
		$now = new DateTime();
		$now->sub(new DateInterval('P12M'));
		for ($i = 12; $i > 0; $i--) {
			$now->add(new DateInterval('P1M'));
			$month =  $now->format('M');
			$year =  $now->format('Y');
			$monthNumber =  $now->format('m');
			$orders = $this->getNumberOfOrders($office_id,$monthNumber,$year);
			$totalOrders = intval($orders['submitted'])+intval($orders['shipped']);
			$html .= '["'.$month.' '.$year.'", '.$totalOrders.'],';
		}
		$html .= '		    ],';
		$html .= '		    color: "#0088cc"';
		$html .= '		}];	';
		$html .= '	if( jQuery("#flotDashSales'.$office_id.'").get(0) ) {';
		$html .= 'var flotDashSales'.$office_id.' = jQuery.plot("#flotDashSales'.$office_id.'", flotDashSales'.$office_id.'Data, {';
		$html .= '	series: {';
		$html .= '		lines: {';
		$html .= '			show: true,';
		$html .= '			lineWidth: 2';
		$html .= '		},';
		$html .= '		points: {';
		$html .= '			show: true';
		$html .= '		},';
		$html .= '		shadowSize: 0';
		$html .= '	},';
		$html .= '	grid: {';
		$html .= '		hoverable: true,';
		$html .= '		clickable: true,';
		$html .= '		borderColor: "rgba(0,0,0,0.1)",';
		$html .= '		borderWidth: 1,';
		$html .= '		labelMargin: 15,';
		$html .= '		backgroundColor: "transparent"';
		$html .= '	},';
		$html .= '	yaxis: {';
		$html .= '		min: 0,';
		$html .= '		color: "rgba(0,0,0,0.1)"';
		$html .= '	},';
		$html .= '	xaxis: {';
		$html .= '		mode: "categories",';
		$html .= '		color: "rgba(0,0,0,0)"';
		$html .= '	},';
		$html .= '	legend: {';
		$html .= '		show: false';
		$html .= '	},';
		$html .= '	tooltip: true,';
		$html .= '	tooltipOpts: {';
		$html .= '		content: "%x: %y",';
		$html .= '		shifts: {';
		$html .= '			x: -30,';
		$html .= '			y: 25';
		$html .= '		},';
		$html .= '		defaultTheme: false';
		$html .= '	}';
		$html .= '	});';
		$html .= '	} ';
		$html .= '</script>';
		$html .= '</div>';
		return $html;
	}

	public function getNumberOfOrders($office_id, $month=null, $year=null){
		$office = get_post($office_id);
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'office_id','value'=>$office_id));
		array_push($meta_query, array('key'=>'status', 'compare'=>'=', 'value'=>'Submitted'));
	  	$args = array(
	  		'date_query' => array(
	  			'month'=>$month,
	  			'year'=>$year
	  		),
		   	'posts_per_page'   => -1,
		   	'post_type'        => 'order',
		   	'meta_query'		=> $meta_query
		);
		$the_query = new WP_Query( $args );		
		$submittedOrders = count($the_query->posts);
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'office_id','value'=>$office_id));
		array_push($meta_query, array('key'=>'status', 'compare'=>'=', 'value'=>'Shipped'));
	  	$args = array(
	  		'date_query' => array(
	  			'month'=>$month,
	  			'year'=>$year
	  		), 		
		   	'posts_per_page'   => -1,
		   	'post_type'        => 'order',
		   	'meta_query'		=> $meta_query
		);
		$the_query = new WP_Query( $args );		
		$shippedOrders = count($the_query->posts);
		return array('submitted'=>$submittedOrders,'shipped'=>$shippedOrders);
	}

	public function getOfficeInfo($office_id){
		$html = "";
		$office = get_post(intval($office_id));
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'office_id','value'=>$office_id));
		array_push($meta_query, array('key'=>'status', 'compare'=>'!=', 'value'=>'Init'));
	  	$args = array(
		   	'posts_per_page'   => -1,
		   	'post_type'        => 'order',
		   	'meta_query'		=> $meta_query
		);
		$the_query = new WP_Query( $args );		
		$shipping_box_price = get_post_meta( $office_id, 'shipping_box_price', true );
		$shipping_box_weight = get_post_meta( $office_id, 'shipping_box_weight', true );
		$html .=  "<b>Shipping costs:</b> " . $this->formatMoney($shipping_box_price);
		$html .=  "<br/><b>Shipping box maximum weight:</b> " . intval($shipping_box_weight) . " kilo";
		$html .= "<br/><b>Number of orders:</b> " . intval(count($the_query->posts));
		return $html;
	}	

	public function calculateNewProductionCosts($product_id, $amount_added){
		$product = get_post($product_id);
		$product_custom = get_post_custom( $product->ID );
		$production_costs = $product_custom["production_costs"][0];
		$add_price = 0.00;
		$old_stock = 0;
		foreach ($this->getStockLines($product_id) as $stockline){
			$old_stock += get_post_meta( $stockline->ID, 'amount', true );
		}
		
		// $old_stock = $this->getProductStock($product_id);
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'product_id','value'=>$product_id));
		array_push($meta_query, array('key'=>'amount', 'compare'=>'>', 'value'=>0));
	  	$args = array(
		   	'posts_per_page'   => -1,
		   	'post_type'        => 'priceline',
			'orderby'   => 'meta_value_num',
			'meta_key'  => 'amount',
		   	'meta_query'		=> $meta_query
		);
		$the_query = new WP_Query( $args );		
		foreach ($the_query->posts as $key => $priceline) {
			// return $priceline;
			$priceline_custom = get_post_custom( $priceline->ID );
			$price = $priceline_custom["price"][0];
			$amount = $priceline_custom["amount"][0];
			if ($amount_added>$amount){
				$add_price = $price;
				break;
			}
		}
		$new_production_costs = (($old_stock*$production_costs) + ($amount_added*$add_price)) / ($amount_added+$old_stock);
		return $new_production_costs;
	}

	public function getPricelines($product_id, $sort_order){
		$product = get_post($product_id);
		// $meta_query = array();
		$meta_query = array('relation' => 'AND');
		array_push($meta_query, array('key'=>'product_id','compare'=>'=','value'=>$product_id));
		array_push($meta_query, array('key'=>'amount', 'compare'=>'>', 'value'=>0));
	  	$args = array(
		   	'posts_per_page'   => -1,
		   	'post_type'        => 'priceline',
		   	'meta_query' => $meta_query
		);
		$the_query = new WP_Query( $args );	
		$arr = array();
		foreach ($the_query->posts as $priceline){
			$tmp = array();
			$tmp["priceline"]=$priceline;
			$tmp["custom"]=get_post_custom( $priceline->ID );
			$tmp["product_costs_formatted"] = $this->formatMoney(get_post_meta( $priceline->ID, 'production_costs', true ));
			array_push($arr, $tmp);
		}
		return $arr;
	}

	public function formatDate($date){
		$date=date_create($date);
		return date_format($date,"H:i:s d/m/Y");
	}

	public function getStatusHTML($status){
		if ($status=="Submitted"||$status=="submitted"){
			return "<span class='status-submitted'>Submitted</span>";
		}
		elseif ($status=="Approved"||$status=="approved"){
			return "<span class='status-approved'>Approved</span>";
		}
		elseif ($status=="Waiting"||$status=="waiting"){
			return "<span class='status-waiting'>Waiting</span>";
		}		
		elseif ($status=="Completed"||$status=="completed"){
			return "<span class='status-completed'>Completed</span>";
		}
		elseif ($status=="Shipped"||$status=="shipped"){
			return "<span class='status-shipped'>Shipped</span>";
		}
		elseif ($status=="Declined"||$status=="declined"){
			return "<span class='status-declined'>Declined</span>";
		}
		elseif ($status=="Cancelled"||$status=="cancelled"){
			return "<span class='status-cancelled'>Cancelled</span>";
		}	
		elseif ($status=="Pending"||$status=="pending"){
			return "<span class='status-pending'>Pending</span>";
		}	
		elseif ($status=="Declined"||$status=="declined"){
			return "<span class='status-declined'>Declined</span>";
		}			
		elseif ($status=="Rejected"||$status=="rejected"){
			return "<span class='status-rejected'>Rejected</span>";
		}
		elseif ($status=="Accepted"||$status=="accepted"){
			return "<span class='status-accepted'>Accepted</span>";
		}
		elseif ($status=="Request"||$status=="request"||$status=="requested"||$status=="Requested"){
			return "<span class='status-request'>Requested</span>";
		}
		elseif ($status=="Updated"||$status=="updated"){
			return "<span class='status-updated'>Updated</span>";
		}	
		elseif ($status=="Refused"||$status=="refused"){
			return "<span class='status-refused'>Refused</span>";
		}		
		elseif ($status=="Sent"||$status=="sent"){
			return "<span class='status-sent'>Sent</span>";
		}	
		elseif ($status=="Finished"||$status=="finished"){
			return "<span class='status-finished'>Finished</span>";
		}															
	}

	public function getOrderStatusHTML($order_id){
		$status = get_post_meta( $order_id, 'status', true );
		if ($status=="Submitted"){
			return "<span class='status-submitted'>Submitted</span>";
		}
		elseif ($status=="Approved"){
			return "<span class='status-approved'>Approved</span>";
		}
		elseif ($status=="Waiting"){
			return "<span class='status-waiting'>Waiting</span>";
		}		
		elseif ($status=="Completed"){
			return "<span class='status-completed'>Completed</span>";
		}
		elseif ($status=="Shipped"){
			return "<span class='status-shipped'>Shipped</span>";
		}
		elseif ($status=="Declined"||$status=="declined"){
			return "<span class='status-declined'>Declined</span>";
		}
		elseif ($status=="Cancelled"){
			return "<span class='status-cancelled'>Cancelled</span>";
		}
		elseif ($status=="Finished"||$status=="finished"){
			return "<span class='status-finished'>Finished</span>";
		}						
		else{
			return "<span class='status-cancelled'>".esc_html($status)."</span>";
		}										
	}	

	public function getStatusBubble($status){
		return $this->getStatusHTML($status);				
	}

	public function getProductRequests($filter=null){
		$user = wp_get_current_user();
		$meta_query = null;
		if ($filter!==null && $filter!=='all' ){
			$meta_query = array('relation' => 'AND');		
			array_push($meta_query, array('key'=>'status','compare' => '=','value'=>$filter));
		}		
		$customer_id = $this->user_functions->getUserCustomer();
	
  		$args = array(
	    	'posts_per_page'   => -1,
	    	'post_type'        => 'product_request',
	    	'meta_query'		=> $meta_query
		);
		$the_query = new WP_Query( $args );	
		return json_encode($the_query->posts);		 
	}
	
	public function getBackorderRequests(){
		$user = wp_get_current_user();
		
		if ($this->user_functions->getMerchStockRoleName()=="manager"){			
			$meta_query = array('relation' => 'AND');		
			array_push($meta_query, array('key'=>'user_id','value'=>$user->ID));	
			array_push($meta_query, array('key'=>'customer_id','value'=>$this->user_functions->getUserCustomer()));					
	  		$args = array(
		    	'posts_per_page'   => -1,
		    	'post_type'        => 'backorder_request',
		    	'meta_query'		=> $meta_query
			);					
		}
		else{
	  		$args = array(
		    	'posts_per_page'   => -1,
		    	'post_type'        => 'backorder_request',
		    	'meta_key'		=> 'customer_id',
		    	'meta_value'		=> $this->user_functions->getUserCustomer()
			);									
		}
		$the_query = new WP_Query( $args );	
		return wp_json_encode( $the_query->posts );
	}

	public function getProducts(){
		$customer_id = $this->user_functions->getUserCustomer();
  		$args = array(
	    	'posts_per_page'   => -1,
	    	'post_type'        => 'wilson-product',
	    	'meta_key'			=> 'customer_id',
	    	'meta_value'		=> $customer_id
		);
		$the_query = new WP_Query( $args );	
		return $the_query->posts;
	}

	public function getAdminProducts(){
  		$args = array(
	    	'posts_per_page'   => -1,
	    	'post_type'        => 'wilson-product',
		);
		$the_query = new WP_Query( $args );	
		return $the_query->posts;		
	}

	public function getStockLines($product_id){
		$product = get_post($product_id);
  		$args = array(
	    	'posts_per_page'   => -1,
	    	'post_type'        => 'stockline',
	    	'meta_key'			=> 'product_id',
	    	'meta_value'		=> $product_id
		);
		$the_query = new WP_Query( $args );	
		return $the_query->posts;		
	}

	public function createNewOrder(){
		$id = wp_insert_post(array('post_title'=>'Order', 'post_type'=>'order', 'post_content'=>''));		
		$invoice_id = wp_insert_post(array('post_title'=>'Invoice', 'post_type'=>'invoice', 'post_content'=>''));
		$office_id = $this->user_functions->getUserOffices()[0]->ID;
		$office_shipping_costs = get_post_meta( $office_id, 'office_shipping_costs', true );
		update_post_meta( $id, "customer_id", get_current_user_id());
		update_post_meta( $id, "invoice_id", $invoice_id);
		update_post_meta( $id, "office_shipping_costs", $office_shipping_costs);
		update_post_meta( $id, "office_id", $office_id);
		update_post_meta( $id, "status", "Init");
		update_post_meta( $id, "status_changed", date('Y-m-d H:i:s') );
		update_post_meta( $invoice_id, "status", "Open");
		return $id;
	}

	public function calculateOrderPrice($order_id){
		$total = 0.00;
		$orderlines = json_decode($this->getOrderlines($order_id),true);
		foreach ($orderlines as $key => $orderline){				
			$array = (array) $orderline;
			$orderline_id = $array["ID"];
			$product_id = $this->getOrderlineProductID($orderline_id);			
			$product_price = get_post_meta( $product_id, 'product_price', true);
			$total += $product_price;
		}
		return $total;
	}

	// get all orderlines from an order
	public function getOrderlines($order_id){
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'orderline',
		    'meta_key'			=> 'order_id',
		    'meta_value'		=> $order_id
		);
		$the_query = new WP_Query( $args );
		$orderlines = array();
		foreach ($the_query->posts as $key => $post) {
			// return $key;
			$product = get_post(get_post_meta( $post->ID, "product_id", true ));
			$stockline = get_post(get_post_meta( $post->ID, "stockline_id", true ));
			$tmp = array();
			array_push($tmp, wp_json_encode($post));
			array_push($tmp, array("amount"=>get_post_meta( $post->ID, 'amount', true )));			
			array_push($tmp, array("price"=>get_post_meta( $post->ID, 'product_price', true )));
			array_push($tmp, array("production_costs"=>get_post_meta( $post->ID, 'production_costs', true )));
			array_push($tmp, array("stockline"=>get_post_meta( $stockline->ID, 'description', true )));			
			array_push($tmp, array("status"=>get_post_meta( $post->ID, 'status', true )));
			array_push($tmp, array("stockline_id"=>$stockline->ID));
			array_push($orderlines, $tmp);
		}
		return $orderlines;
	}

	public function getProductStockNumberFreeItems($product_id, $stockline_id){
		$stock = $this->getProductStock($product_id, $stockline_id);		
		return intval($stock['stock']-$stock['init']);
	}

	public function getBackorderAmount($product_id, $stockline_id){
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'stockline_id','compare' => '=','value'=>$stockline_id));
		array_push($meta_query, array('key'=>'status','compare' => '=','value'=>"pending"));
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'backorder',
		    'meta_query'			=> $meta_query
		);		
	
		$the_query = new WP_Query( $args );
		$backorderAmount = 0;
		foreach ($the_query->posts as $key => $backorder) {
			$backorderAmount += get_post_meta( $backorder->ID, 'amount', true );
		}		
		return $backorderAmount;
	}

	public function getBackorderRequestAmount($product_id, $stockline_id){
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'stockline_id','compare' => '=','value'=>$stockline_id));
		array_push($meta_query, array('key'=>'status','compare' => '=','value'=>"pending"));
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'backorder_request',
		    'meta_query'			=> $meta_query
		);		
	
		$the_query = new WP_Query( $args );
		$backorderRequestAmount = 0;
		foreach ($the_query->posts as $key => $backorder) {
			$backorderRequestAmount += get_post_meta( $backorder->ID, 'amount', true );
		}		
		return $backorderRequestAmount;
	}	

	// get the stock for a product
	public function getProductStock($product_id, $stockline_id){
		$product = get_post($product_id);
		$product_custom = get_post_custom( $product_id ); 
		$stockline = get_post($stockline_id);
		$stockline_custom = get_post_custom( $stockline_id ); 
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'stockline_id','compare' => '=','value'=>$stockline_id));
		array_push($meta_query, array('key'=>'status','compare' => '=','value'=>"pending"));
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'backorder',
		    'meta_query'			=> $meta_query
		);		
	
		$the_query = new WP_Query( $args );
		$backorderAmount = 0;
		foreach ($the_query->posts as $key => $backorder) {
			$backorderAmount += get_post_meta( $backorder->ID, 'amount', true );
		}
		$initOrderlines = $this->getOrderlinesByStatus("Init", $product_id, $stockline_id);
		$submittedOrderlines = $this->getOrderlinesByStatus("Submitted", $product_id, $stockline_id);
		$approvedOrderlines = $this->getOrderlinesByStatus("Approved", $product_id, $stockline_id);
		$shippedOrderlines = $this->getOrderlinesByStatus("Shipped", $product_id, $stockline_id);
		$completedOrderlines = $this->getOrderlinesByStatus("Completed", $product_id, $stockline_id);
		$declinedOrderlines = $this->getOrderlinesByStatus("Declined", $product_id, $stockline_id);
		$cancelledOrderlines = $this->getOrderlinesByStatus("Cancelled", $product_id, $stockline_id);
		$submittedAmount =  0;
		$approvedAmount =  0;
		$shippedAmount = 0;
		$completedAmount = 0;
		$declinedAmount = 0;
		$cancelledAmount = 0;
		$initAmount = 0;
		$onthisorder = 0;
		foreach ($shippedOrderlines as $key => $orderline) {
			$shippedAmount += get_post_meta( $orderline->ID, 'amount', true );
		}
		foreach ($submittedOrderlines as $key => $orderline) {
			$submittedAmount += get_post_meta( $orderline->ID, 'amount', true );
		}
		foreach ($approvedOrderlines as $key => $orderline) {
			$approvedAmount += get_post_meta( $orderline->ID, 'amount', true );
		}	
		foreach ($completedOrderlines as $key => $orderline) {
			$completedAmount += get_post_meta( $orderline->ID, 'amount', true );
		}	
		foreach ($declinedOrderlines as $key => $orderline) {
			$declinedAmount += get_post_meta( $orderline->ID, 'amount', true );
		}	
		foreach ($cancelledOrderlines as $key => $orderline) {
			$cancelledAmount += get_post_meta( $orderline->ID, 'amount', true );
		}		
		foreach ($initOrderlines as $key => $orderline) {
			$initAmount += get_post_meta( $orderline->ID, 'amount', true );
		}							
		$stock = $stockline_custom["product_stock"][0];
		$stock_arr = array(
			'stock' => intval($stock),
			'submitted' => $submittedAmount,
			'approved' => $approvedAmount,
			'shipped' => $shippedAmount,
			'completed' => $completedAmount,
			'declined' => $declinedAmount,
			'cancelled' => $declinedAmount,
			'backorder' => $backorderAmount,
			'init' => $initAmount,			
		);
		return $stock_arr;
	}

	public function getBackorders($filter=null){
		$meta_query = null;
		if ($filter!==null && $filter!=='all' ){
			$meta_query = array('relation' => 'AND');		
			array_push($meta_query, array('key'=>'status','compare' => '=','value'=>$filter));
		}
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => array('backorder_request'),
		    'orderby'			=> 'ID',
		    'order'				=> 'DESC',
		    'meta_query'			=> $meta_query
		);
		$the_query = new WP_Query( $args );
		return wp_json_encode($the_query->posts);			
	}

	public function orderHasBackorderLines($order_id){
		$order = get_post($order_id);
		$orderlines = $this->getOrderlines($order_id);
		foreach ($orderlines as $orderline){
			$orderline_id = json_decode($orderline[0],true)["ID"];		
			if (get_post_meta( $orderline_id, 'status', true )=="backorder"){
				return true;
			}
		}
		return false;
	}
	// get all orders associated with the offices associated to the logged in user
	public function getOrders($number=-1, $status = null, $office = null,  $office_id = null){ 
		
		$offices = $this->user_functions->getUserOffices(); 
		
		$meta_query = array('relation' => 'AND');
		if ($status=='all'){
			$status=null;
		}
		if ($office=='all'){
			$office=null;
		}		
	
		if ($status!==null){
			array_push($meta_query, array('key'=>'status','compare' => '=','value'=>$status));
		}
		else{
			array_push($meta_query, array('key'=>'status','compare' => '!=','value'=>'init'));	
		}
		if ($office!==null){
			array_push($meta_query, array('key'=>'office_id','compare' => '=','value'=>$office));
		}
		$meta_query_office = array('relation' => 'OR');
		if ($office_id == 0){
			foreach ($offices as $key => $office) {
				array_push($meta_query_office, array('key'=>'office_id','value'=>$office->ID));
			}
		}
		else{
			array_push($meta_query_office, array('key'=>'office_id','value'=>$office_id));
		}
		array_push($meta_query, $meta_query_office);
		$args = array(
		    'posts_per_page'   => $number,
		    'post_type'        => 'order',
		    'meta_query'		=> $meta_query,
		);
		$the_query = new WP_Query( $args );
		return wp_json_encode($the_query->posts);		
	}

	public function formatMoney($money){
		return "€ ".number_format(floatval($money), 2,".",",");
	}

	public function getMonthlyOrders($month, $year, $office_id){
		$date = date($year.'-'.$month.'-01');		
		$firstDay = $date;
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$lastDay = strtotime ( '+ '.$daysInMonth.' day' , strtotime ( $date ) ) ;
		$lastDay = date ( 'Y-m-j' , $lastDay );
		$meta_query = array('relation' => 'AND');
		array_push($meta_query, array('key'=>'status','compare' => '=','value'=>'Completed'));
		array_push($meta_query, array('key'=>'status_changed','compare' => '>=','value'=>$firstDay, 'type'=>'DATE'));
		array_push($meta_query, array('key'=>'status_changed','compare' => '<','value'=>$lastDay, 'type'=>'DATE'));
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'order',
		    'meta_query'			=> $meta_query
		);		
		$the_query = new WP_Query( $args );
		return wp_json_encode($the_query->posts);
	}

	// get all orderlines with a product and a status (use for counting stock per status)
	public function getOrderlinesByStatus($status, $product_id, $stockline_id){
		$orderlines = array();
		$args = array(
			'posts_per_page'   => -1,
			'post_type' => 'order',
    		'meta_key' => 'status',
    		'meta_value' => $status
		);
		$the_query = new WP_Query( $args );
		foreach ($the_query->posts as $key => $post) {
			$meta_query = array('relation' => 'AND');
			array_push($meta_query, array('key'=>'order_id','value'=>$post->ID));
			array_push($meta_query, array('key'=>'stockline_id','value'=>$stockline_id));
			$args1 = array(
				'posts_per_page'   => -1,
				'post_type' => 'orderline',
		    	'meta_query'		=> $meta_query
			);
			$the_query1 = new WP_Query( $args1 );			
			foreach ($the_query1->posts as $key1 => $post1) {
				array_push($orderlines, $post1);
			}
		}
		return $orderlines;		
	}

	public function getOrderlineProductID($orderline_id){		
		$orderline = get_post($orderline_id);
		$product_id =  get_post_meta($orderline_id, 'product_id', true);		
		return $product_id;
	}

	public function getOrderPrice($order_id){
		$order_price = 0.00;
		$orderlines = $this->getOrderlines($order_id);		
		foreach ($orderlines as $key => $tmporderline) {
			$orderline = json_decode($tmporderline[0],true);
			$product_id = get_post_meta( $orderline["ID"], 'product_id', true );
			$amount = get_post_meta(   $orderline["ID"], 'amount', true );
			$product_price = get_post_meta( $orderline["ID"], 'product_price', true );
			$order_price += ($amount*$product_price);
		}
		return $order_price;
	}

	public function getOrderWeight($order_id){
		$order_weight = 0.00;
		$orderlines = $this->getOrderlines($order_id);		
		foreach ($orderlines as $key => $tmporderline) {
			$orderline = json_decode($tmporderline[0],true);
			$product_id = get_post_meta( $orderline["ID"], 'product_id', true );
			$amount = get_post_meta(   $orderline["ID"], 'amount', true );
			$product_weight = get_post_meta( $product_id, 'product_weight', true );
			$order_weight += ($amount*$product_weight);
		}
		return $order_weight;
	}

	public function getOrderStatuses(){
		$statuses = array('Submitted','Approved','Shipped','Declined','Completed');
		return $statuses;
	}

	public function getOrderHistoryV2($product_id){
		$my_offices = $this->user_functions->getUserOffices(); 
		$orders = array();
	
		foreach ($my_offices as $key => $office) {			
			$meta_query = array('relation'=>'AND');
			array_push($meta_query, array('key'=>'office_id','compare' => '=','value'=>$office->ID));	
			array_push($meta_query, array('key'=>'product_id','compare' => '=','value'=>$product_id));	
			$args = array(
			    'posts_per_page'   => -1,
			    'post_type'        => 'orderline',
			    'meta_query'		=> $meta_query
			);			
			$the_query = new WP_Query( $args );	
			
			foreach ($the_query->posts as $key => $post) {
				$orderline_id = $post->ID;
				$orderline = get_post($orderline_id);
				$order_id = get_post_meta( $orderline->ID, 'order_id', true );
				$order = get_post($order_id);
				$order_status = get_post_meta( $order->ID, 'status', true );
				if ($order_status!=='Init'){
					if (!in_array($order, $orders)){
						array_push($orders, $order);
					}
				}
			}
		}
		return wp_json_encode( $orders );
	}

	// get order history from product
	public function getOrderHistory($product_id){
		$orderlines = array();
		// $result = array();
		$product = get_post($product_id);
		$my_offices = $this->user_functions->getUserOffices();
		// return var_dump($my_offices);
		foreach ($my_offices as $key => $office) {
			$office_id = $office->ID;
			
			$meta_query = array('relation' => 'AND');
		
			array_push($meta_query, array('key'=>'office_id','compare' => '=','value'=>$office_id));
			$meta_query_office = array('relation' => 'OR');
			foreach ($this->getOrderStatuses() as $key => $status) {
				if ($status!=='Init'){
					array_push($meta_query_office, array('key'=>'status','value'=>$status));	
				}
			}
			array_push($meta_query, $meta_query_office);
			$args = array(
			    'posts_per_page'   => -1,
			    'post_type'        => 'order',
			    'meta_query'		=> $meta_query
			);			
			$the_query = new WP_Query( $args );	
			// array_push($result, $the_query->posts);
			foreach ($the_query->posts as $key => $post) {
				$order_id = $post->ID;
				
				$meta_query1 = array('relation' => 'AND');
				array_push($meta_query1, array('key'=>'order_id','compare' => '=','value'=>$order_id));				
				array_push($meta_query1, array('key'=>'product_id','compare' => '=','value'=>$product_id));		
				$args1 = array(
				    'posts_per_page'   => -1,
				    'post_type'        => 'orderline',
				    'meta_query'		=> $meta_query1
				);					
				$the_query1 = new WP_Query( $args1 );	
				if (count($the_query1->posts)>0){
					array_push($orderlines , $the_query1->posts);	
				}
			}
		}
		return wp_json_encode( $orderlines );
	}

	public function productsOnOrder($order_id, $stockline_id){
		$order = get_post($order_id);
		$orderlines = $this->getOrderlines($order_id);
		$stockline = get_post($stockline_id);
		foreach ($orderlines as $key => $orderline) {	
			$tmpOrderline = json_decode($orderline[0],true);
			$orderline = get_post($tmpOrderline["ID"]);
			$tmp_stockline_id = get_post_meta( $orderline->ID, 'stockline_id', true);				
			if ($tmp_stockline_id == $stockline_id){
				return intval(get_post_meta( $orderline->ID, 'amount', true));
			}
		}
		return 0;
	}

	public function checkOrderBeforeSubmit($order_id){
		$order = get_post($order_id);
		$orderlines = $this->getOrderlines($order_id);
		$arr = array();		
		foreach ($orderlines as $key => $orderline) {	
			$tmpOrderline = json_decode($orderline[0],true);	
			$orderline = get_post($tmpOrderline["ID"]);
			$tmpOrderlineCustom = get_post_custom($tmpOrderline["ID"]);
			$product = get_post($tmpOrderlineCustom["product_id"][0]);
			$amount = $tmpOrderlineCustom["amount"][0];
			$stockline_id = get_post_meta( $orderline->ID, 'stockline_id', true );
			// $product_price = $tmpOrderlineCustom["product_price"][0];
			$production_costs = $tmpOrderlineCustom["production_costs"][0];
			$stock = $this->getProductStock($product->ID, $stockline_id);
			$onthisorder = $this->productsOnOrder($order_id, $stockline_id);
			if ($amount<=intval($stock['stock'])){
				// array_push($arr, get_the_title( $product_id ) . " oke") ;
				// array_push($arr, get_the_title( $product_id ) . " " . get_post_meta( $stockline_id, 'description', true ) . " not oke");
			}
			else{				
				// array_push($arr, $product_id."_".$stockline_id);
				array_push($arr, $stockline_id);
				// array_push($arr, urlencode(get_the_title( $product->ID ) . " " . get_post_meta( $stockline_id, 'description', true ) . " not oke")) ;
			}

		}
		return  $arr ;		
	}

	public function updatOrderProductStock($order_id){
		$order = get_post($order_id);
		$orderlines = $this->getOrderlines($order_id);
		foreach ($orderlines as $key => $orderline) {
			
			$arr_orderline = json_decode($orderline[$key], true);
			$orderline_id = $arr_orderline["ID"];
			// return $orderline_id;
			$product_id = get_post_meta( $orderline_id, 'product_id', true );
			$stockline_id = get_post_meta( $orderline_id, 'stockline_id', true );
			
			
			$product = get_post($product_id);
			$product_custom = get_post_custom( $product_id );
			$stockline = get_post($stockline_id);
			// return $stockline;
			$stockline_custom = get_post_custom( $stockline->ID ); 
			$amount = get_post_meta( $orderline_id, 'amount', true );
			$stock = $stockline_custom["product_stock"][0];
			$new_stock = $stock-$amount;
			update_post_meta( $stockline->ID, "product_stock", $new_stock);
			$warning_amount = intval(get_post_meta( $product_id, 'warn_amount', true ));
			if ($new_stock<=$warning_amount){
				$this->notification_functions->sendProductAmountWarningNotification($product_id);
			}
		}
		return true;
	}

	public function getInvoicePDFHtml($order_id){				
		$template = plugin_dir_path( __FILE__ ) . '../invoice-pdf-template.php';
		ob_start();
		$order_id = $order_id;
		include $template;
		$myvar = ob_get_clean();
		return $myvar;
	}
}
	