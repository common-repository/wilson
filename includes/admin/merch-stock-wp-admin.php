<?php
/**
 * The menu-specific functionality of the plugin.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/menu 
 */
/**
 * The menu-specific functionality of the plugin.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/menu
 * @author     <info@merchandise.nl>
 */
class Merch_Stock_WP_Admin {
	public static function draw_dashboard(){
 		$template = plugin_dir_path( __FILE__ ) . '../dashboard-admin.php';
		echo load_template( $template, true );	
  	} 
	
	public function add_order_meta_boxes(){
		global $post;
		add_meta_box( 'Orderlines', 'Orderlines ' . $post->ID, array('Merch_Stock_WP_Admin','drawOrder') , 'order' );	
		add_meta_box( 'Total', 'Total ' . $post->ID, array('Merch_Stock_WP_Admin','drawOrderTotal') , 'order' );	
		add_meta_box( 'Comments', 'Comments ' . $post->ID, array('Merch_Stock_WP_Admin','drawOrderComments') , 'order' );	
		add_meta_box( 'History', 'History ' . $post->ID, array('Merch_Stock_WP_Admin','drawOrderHistory') , 'order' );	
	}

	public function drawOrder($order_id){
		$functions = new Merch_Stock_Functions;		
		$post = get_post(intval($order_id));
		$output = "";
		$output .= "<table cellpadding='0' cellspacing='0' class='admin-order'>";
		$output .= "<tr>";
		$output .= "<th>Product ID";
		$output .= "</th>";
		$output .= "<th>Product name";
		$output .= "</th>";		
		$output .= "<th>Amount";
		$output .= "</th>";	
		$output .= "<th>Costs per unit";
		$output .= "</th>";			
		$output .= "<th>Total";
		$output .= "</th>";		
		$output .= "</tr>";
		$orderlines = Merch_Stock_Functions::getOrderlines($post->ID);
		foreach ($orderlines as $key => $orderline){
			$orderline = json_decode($orderline[0]);
			$stockline_id = intval(get_post_meta( $orderline->ID, 'stockline_id', true ));
			$product_id = intval(get_post_meta( $stockline_id, 'product_id', true ));
			$production_costs = floatval(get_post_meta( $orderline->ID, 'production_costs', true ));
			$amount = intval(get_post_meta( $orderline->ID, 'amount', true ));
			if($key % 2 == 0){ 
				$output .= "<tr class='even'>";
			}
			else{
				$output .= "<tr class='uneven'>";
			}
			$output .= "<td>";			
			$output	.= $product_id;
			$output .= "</td>";	
			$output .= "<td>";			
			$output	.= esc_html(get_the_title( $product_id )) . ' ' . esc_html(get_post_meta( $stockline_id, 'description', true ));
			$output .= "</td>";		
			$output .= "<td>";			
			$output	.= $amount;
			$output .= "</td>";		
			$output .= "<td>";			
			$output	.= esc_html($functions->formatMoney($production_costs));
			$output .= "</td>";		
			$output .= "<td>";			
			$output .= esc_html($functions->formatMoney(($production_costs*$amount)));		
			$output .= "</td>";						
 			$output .= "</tr>";
		}		
		$output .= "</table>";
		echo $output;
	}

	public function drawOrderTotal($order_id){
		$functions = new Merch_Stock_Functions;
		$order_functions = new Merch_Stock_Order_Functions;
		$post = get_post(intval($order_id));
		$shipping_costs = json_decode($order_functions->getOrderShippingCostsV2($post->ID, 0, 0, 0,true),true);
		$output = "";
		$output .= "<table cellpadding='0' cellspacing='0' class='admin-order-total'>";
		$output .= "<tr class='even'>";
		$output .= "<td>";
		$output .= "Number of product(s)";
		$output .= "</td>";
		$output .= "<td>";
		$output .= intval($order_functions->getNumberOfProducts($post->ID));
		$output .= "</td>";		
		$output .= "</tr>";
		$output .= "<tr>";
		$output .= "<td>";
		$output .= "Number of carton(s)";
		$output .= "</td>";
		$output .= "<td>";
		$output .= floatval($shipping_costs[1]);
		$output .= "</td>";		
		$output .= "</tr>";
		$output .= "<tr class='even'>";
		$output .= "<td>";
		$output .= "Shipping costs";
		$output .= "</td>";
		$output .= "<td>";
		$output .= floatval($shipping_costs[0]);
		$output .= "</td>";		
		$output .= "</tr>";	
		$output .= "<tr>";
		$output .= "<td>";
		$output .= "Production costs";
		$output .= "</td>";
		$output .= "<td>";
		$output .= esc_html($functions->formatMoney($order_functions->getOrderTotalPrice($post->ID)));
		$output .= "</td>";		
		$output .= "</tr>";		
		$output .= "<tr class='even total'>";
		$output .= "<td>";
		$output .= "Order total";
		$output .= "</td>";
		$output .= "<td>";
		$output .= esc_html($functions->formatMoney($shipping_costs[2]+$order_functions->getOrderTotalPrice($post->ID)));
		$output .= "</td>";		
		$output .= "</tr>";						
		$output .= "</table>";
		echo $output;
	}

	public function drawOrderComments($order_id){
		$post = get_post(intval($order_id));
		$references = get_post_meta( $post->ID, 'references', true );
		$comments = get_post_meta( $post->ID, 'comments', true );
		
		$status = get_post_meta( $post->ID, 'status', true );
		$output = "<div class='double-columns'>";
		$output .= "<div>";
		if (strlen($comments)>0){
			$output .= "<div class='comments'>Comments</div><div>";
			$output .= esc_html(get_post_meta( $post->ID, 'comments', true ));
			$output .= "</div>";				
		}
		
		$output .= "</div>";
		$output .= "<div>";
		if (strlen($references)>0){
			$output .= "<div class='comments'>References</div><div>";
			$output .= esc_html(get_post_meta( $post->ID, 'references', true ));
			$output .= "</div>";		
		}	
		$output .= "</div>";		
		$output .= "</div>";
		$output .= "<div class='tracking-number'>Tracking number ";
		$output .= esc_html(get_post_meta( $post->ID, 'tracking_number', true ));
		$output .= "</div>";
		if ($status=='submitted'||$status=='Submitted'){
			$output .= "<input class='tracking-number-input' type='text'/>";
			$output .= "<input data-order_id='$post->ID' class='button ship-order' type='button' value='Ship order'/>";
		}
		echo $output;
	}

	public function drawOrderHistory($order_id){
		$post = get_post(intval($order_id));
		$output = "";
		$functions = new Merch_Stock_Functions;
		$order_functions = new Merch_Stock_Order_Functions;
		$notification_functions = new Merch_Stock_Notifications;
		$status_changes = json_decode($order_functions->getStatusChanges($post->ID),true);	
		$order_status = get_post_meta( $post->ID, 'status', false );
		echo $notification_functions->drawTimeLine($status_changes, $order_status[0], 'Order');
	}

	public function get_admin_dashboard(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/dashboard-admin.php';
		echo load_template( $template, true );		
	}
	public function draw_new_customer(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/new-customer.php';
		echo load_template( $template, true );
	}
	public function admin_customer_edit(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/edit-customer.php';
		echo load_template( $template, true );
	}
	public function draw_admin_office_edit(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/office_edit.php';
		echo load_template( $template, true );
	}
	public function draw_admin_order(){
 		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/order.php';
		echo load_template( $template, true );			
	}	
	public function draw_admin_office(){
 		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/office.php';
		echo load_template( $template, true );			
	}		
	public function draw_admin_orders(){
 		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/orders.php';
		echo load_template( $template, true );	
	}
	public function draw_admin_backorders(){
 		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/backorders.php';
		echo load_template( $template, true );			
	}
	public function draw_admin_product_requests(){
 		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/product_requests.php';
		echo load_template( $template, true );					
	}
	public function draw_admin_offices(){
 		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/offices.php';
		echo load_template( $template, true );				
	}
	public function draw_admin_product_request(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/product_request.php';
		echo load_template( $template, true );
	}
	public function draw_admin_backorder(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/backorder.php';
		echo load_template( $template, true );
	}	
	public function draw_admin_customers(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/customers.php';
		echo load_template( $template, true );
	}		
	public function draw_admin_customer(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/customer.php';
		echo load_template( $template, true );		
	}
	public function draw_admin_products(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/products.php';
		echo load_template( $template, true );			
	}
	public function draw_admin_product(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/product.php';
		echo load_template( $template, true );			
	}	
	public function draw_admin_edit_product(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/product_edit.php';
		echo load_template( $template, true );			
	}
	public function draw_admin_dashboard(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/dashboard-admin.php';
		echo load_template( $template, true );			
	}	
	public function draw_admin_new_product(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/new-product.php';
		echo load_template( $template, true );					
	}
	public function draw_admin_new_office(){
		$template = plugin_dir_path( __FILE__ ) . '../../admin-templates/new-office.php';
		echo load_template( $template, true );					
	}	
	
	public function getOrders($customer_id=0, $status = 'status', $office_id = 0){
		$meta_query = array('relation' => 'AND');		
		if ($status!=='status'){
			array_push($meta_query, array('key'=>'status','compare' => '=','value'=>$status));	
		}
		else{
			array_push($meta_query, array('key'=>'status','compare' => '!=','value'=>'init'));		
		}
		
		if ($office_id>0){
			array_push($meta_query, array('key'=>'office_id','compare' => '=','value'=>$office_id));				
		}
		else{
			if ($customer_id>0){
				$offices = json_decode($this->getOffices(intval($customer_id)), TRUE);
				$meta_query2 = array('relation' => 'OR');
				foreach ($offices as $office){
					array_push($meta_query2, array('key'=>'office_id','compare' => '=','value'=>intval($office["ID"])));	
				}
				array_push($meta_query, $meta_query2);
			}				
		}
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'order',
		    'meta_query'		=> $meta_query
		);
		$the_query = new WP_Query( $args );
		return wp_json_encode($the_query->posts);			
	}
	public function getBackorders(){
		$meta_query = array('relation' => 'AND');		
		array_push($meta_query, array('key'=>'status','compare' => '!=','value'=>'init'));	
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'backorder_request',
		    'meta_query'		=> $meta_query
		);
		$the_query = new WP_Query( $args );
		return wp_json_encode($the_query->posts);			
	}	
	public function getCustomers(){
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'customer'		    
		);
		$the_query = new WP_Query( $args );
		return wp_json_encode($the_query->posts);
	}
	public function getProductRequests($customer_id=0,$status='status'){
		$customer_id = intval($customer_id);
		if ($customer_id>0){
			$meta_query2 = array('relation' => 'OR');	
			$users = json_decode(Merch_Stock_User_Functions::getUsersFromCustomer($customer_id), TRUE);
			foreach ($users as $key => $user) {	
				array_push($meta_query2, array('key'=>'user_id','compare' => '=','value'=>$user));	
			}	
		}
		
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'product_request',
		    'meta_query'		=> $meta_query2		
		);
		$the_query = new WP_Query( $args );
		$result = array();
		if ($status!=='status' && count($the_query->posts)>0){
			foreach ($the_query->posts as $post){
				$tmpStatus = get_post_meta( $post->ID, 'status', true );
				if ($tmpStatus==$status){
					array_push($result, $post);
				}
			}
			return wp_json_encode($result);
		}
		else{
			return wp_json_encode( $the_query->posts );
		}
	}
	public function getOffices($customer_id = 0){		
		$customer_id = intval($customer_id);
		$meta_query = array();
		if ($customer_id>0){
			$meta_query = array('relation' => 'AND');		
			array_push($meta_query, array('key'=>'customer_id','compare' => '=','value'=>$customer_id));					
		}
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'office',
		    'meta_query'		=> $meta_query		    
		);
		$the_query = new WP_Query( $args );
		return wp_json_encode($the_query->posts);		
	}
	public function getProducts($customer_id = 0, $status = "status"){
		$customer_id = intval($customer_id);
		$meta_query = array();
		if (intval($customer_id>0)){
			$meta_query = array('relation' => 'AND');		
			array_push($meta_query, array('key'=>'customer_id','compare' => '=','value'=>$customer_id));					
		}
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'wilson-product',
		    'meta_query'		=> $meta_query		
		);
		$the_query = new WP_Query( $args );
		return wp_json_encode($the_query->posts);			
	}

	public function ajax_new_office_1(){
		$new_office_id = intval($_POST['new_office_id']);
		$customer_id = intval($_POST['customer_id']);
		$customer = get_post($customer_id);		
		if (intval($new_office_id==0)){
			// CREATE NEW PRODUCT
			$new_office_id = wp_insert_post(array('post_type'=>'office'));
			wp_publish_post($new_office_id);
			update_post_meta( $new_office_id, 'customer_id', $customer_id, '' );
		}
		else{
			$office = get_post($new_office_id);
			update_post_meta( $new_office_id, 'customer_id', $customer_id, '' );
		}	
		$users = Merch_Stock_User_Functions::getManagerUsersFromCustomer($customer_id);
		echo wp_json_encode( array(
			'result'				=>		true,
			'new_office_id'			=>		intval($new_office_id),
			'customer_name'			=>		get_the_title( $customer->ID ),			
			'customer_id'			=>		$customer->ID,
			'users'					=>		$users,
		) );
		exit();
		wp_die();					
	}

	public function ajax_new_office_2(){
		$new_office_id = intval($_POST['new_office_id']);
		$user_id = intval($_POST['user_id']);
		$user_ids = get_post_meta( $new_office_id, 'user_ids', true );
		$user_ids .= '|'.$user_id.'|';
		update_post_meta( $new_office_id, 'user_ids', $user_ids, '' );
		$user = get_user_by( 'ID', $user_id );
		echo wp_json_encode( array(
			'result'				=>		true,
			'new_office_id'			=>		$new_office_id,
			'user_id'				=>		$user_id,
			'user_ids'				=>		$user_ids,
			'first_name'			=>		$user->first_name,
			'last_name'				=>		$user->last_name,
		) );
		exit();
		wp_die();			  		
	}

	public function ajax_new_office_3(){
		$new_office_id = intval($_POST['new_office_id']);		
		$description = sanitize_text_field($_POST["description"]);

	 	$my_post = array(
	    	'ID'           => $new_office_id,
	      	'post_title'   => $description
	  	);
		wp_update_post( $my_post );


		$addressline1 = sanitize_text_field($_POST["addressline1"]);
		$addressline2 = sanitize_text_field($_POST["addressline2"]);
		$addressline3 = sanitize_text_field($_POST["addressline3"]);
		$postal_code = sanitize_text_field($_POST["postal_code"]);
		$city = sanitize_text_field($_POST["city"]);
		$region = sanitize_text_field($_POST["region"]);
		$county = sanitize_text_field($_POST["county"]);
		$country = sanitize_text_field($_POST["country"]);
		$mail = sanitize_email($_POST["mail"]);
		$telephone = sanitize_text_field($_POST["telephone"]);
		update_post_meta( $new_office_id, 'addressline1', $addressline1, '' );
		update_post_meta( $new_office_id, 'addressline2', $addressline2, '' );
		update_post_meta( $new_office_id, 'addressline3', $addressline3, '' );
		update_post_meta( $new_office_id, 'postal_code', $postal_code, '' );
		update_post_meta( $new_office_id, 'city', $city, '' );
		update_post_meta( $new_office_id, 'region', $region, '' );
		update_post_meta( $new_office_id, 'county', $county, '' );
		update_post_meta( $new_office_id, 'telephone', $telephone, '' );
		update_post_meta( $new_office_id, 'mail', $mail, '' );
		echo wp_json_encode( array(
			'result'				=>		true,
			'new_office_id'			=>		intval($new_office_id),
			'addressline1'			=>		$addressline1,
			'addressline2'			=>		$addressline2,
			'addressline3'			=>		$addressline3,
			'postal_code'			=>		$postal_code,
			'city'					=>		$city,
			'region'				=>		$region,
			'county'				=>		$county,
			'country'				=>		$country,
			'mail'					=>		$mail,
			'telephone'				=>		$telephone,
			'description'			=>		$description
		) );
		exit();
		wp_die();		
	}
	public function ajax_new_office_4(){
		$new_office_id = intval($_POST['new_office_id']);		
		$shipping_box_price = floatval($_POST["shipping_box_price"]);
		$shipping_box_weight = floatval($_POST["shipping_box_weight"]);
		update_post_meta( $new_office_id, 'shipping_box_price', $shipping_box_price, '' );
		update_post_meta( $new_office_id, 'shipping_box_weight', $shipping_box_weight, '' );
		echo wp_json_encode( array(
			'result'				=>		true,
			'new_office_id'			=>		intval($new_office_id),
			'shipping_box_price'			=>		$shipping_box_price,
			'shipping_box_weight'			=>		$shipping_box_weight,
		));				
		exit();
		wp_die();
	}
	public function ajax_new_product_1(){
		$new_product_id = intval($_POST['new_product_id']);
		$customer_id = intval($_POST['customer_id']);
		$customer = get_post($customer_id);
		if ($new_product_id==0){
			// CREATE NEW PRODUCT
			$new_product_id = intval(wp_insert_post(array('post_type'=>'wilson-product')));
			update_post_meta( $new_product_id, 'customer_id', $customer_id, '' );
		}
		else{
			$product = get_post($new_product_id);
			update_post_meta( $new_product_id, 'customer_id', $customer_id, '' );
		}
		echo wp_json_encode( array(
			'result'				=>		true,
			'new_product_id'		=>		intval($new_product_id),
			'customer_name'			=>		esc_html(get_the_title( $customer->ID )),			
			'customer_id'			=>		$customer_id
		) );
		exit();
		wp_die();		
	}
	public function ajax_new_product_2(){
		$new_product_id = intval($_POST['new_product_id']);
		$product = get_post($new_product_id);
		$productName = sanitize_text_field($_POST['productname']);
		$productDescription = sanitize_text_field($_POST['productdescription']);
		$articleNumber = sanitize_text_field($_POST['articlenumber']);
		$productImage = intval($_POST['productimage']);
		set_post_thumbnail( $new_product_id, $productImage );
	 	$my_post = array(
	    	'ID'           => $new_product_id,
	      	'post_title'   => $productName,
	      	'post_content' => $productDescription,
	  	);
		wp_update_post( $my_post );
		update_post_meta( $new_product_id, 'article_number', $articleNumber, '' );
		echo wp_json_encode( array(
			'result'				=>		true,
			'new_product_id'		=>		intval($new_product_id),
			'product_name'			=>		esc_html(get_the_title( $new_product_id )),						
			'meta'					=>		esc_html(get_post_meta( $new_product_id, '', false )),
			'post'					=>		esc_html(get_post($new_product_id)),
			'image_url'				=>		esc_url(get_the_post_thumbnail_url($new_product_id))
		) );
		exit();
		wp_die();			
	}
	public function ajax_new_product_3(){
		$new_product_id = intval($_POST['new_product_id']);
		$product = get_post($new_product_id);
		$productWeight = floatval($_POST['productweight']);
		$minimalOrderAmount = intval($_POST['minimalorderamount']);
		$orderPer = intval($_POST['orderper']);
		$orderTerm = intval($_POST['orderterm']);
		$warningAmount = intval($_POST['warningamount']);
		update_post_meta( $new_product_id, 'product_weight', $productWeight, '' );
		update_post_meta( $new_product_id, 'minimal_order_amount', $minimalOrderAmount, '' );
		update_post_meta( $new_product_id, 'order_per', $orderPer, '' );
		update_post_meta( $new_product_id, 'order_term', $orderTerm, '' );
		update_post_meta( $new_product_id, 'warn_amount', $warningAmount, '' );
		echo wp_json_encode( array(
			'result'				=>		true,
			'new_product_id'		=>		intval($new_product_id),
			'product_name'			=>		esc_html(get_the_title( $new_product_id )),						
			'meta'					=>		get_post_meta( $new_product_id, '', false ),
			'post'					=>		get_post($new_product_id)
		) );
		exit();
		wp_die();			
	}
	public function ajax_new_product_4(){	
		$new_product_id = intval($_POST['new_product_id']);
		$variations = sanitize_text_field($_POST["variations"] );
		$JSON_variations = str_replace("\\","", $variations);		
		$arr = json_decode($JSON_variations,TRUE);
		
		foreach ($arr as $variation){			
			$stockline_id = wp_insert_post(array('post_type'=>'stockline'));
			update_post_meta( $stockline_id, 'product_id', $new_product_id, '' );			
			update_post_meta( $stockline_id, 'product_stock', intval($variation['stock']), '' );
			update_post_meta( $stockline_id, 'description', esc_html($variation['description'], '' ));
		}
		echo wp_json_encode( array(
			'result'				=>		true,
			'new_product_id'		=>		intval($new_product_id),
			'variations'			=>		json_encode($arr),
			'meta'					=>		get_post_meta( $new_product_id, '', false ),
			'post'					=>		get_post($new_product_id)			
		) );
		exit();
		wp_die();			
	}
	public function ajax_new_product_5(){	
		$new_product_id = intval($_POST['new_product_id']);
		$production_costs = floatval($_POST["production_costs"]);
		$JSON_production_costs = str_replace("\\","", $production_costs);		
		$arr = json_decode($JSON_production_costs,TRUE);

		$first_amount = intval($_POST["first_amount"]);
		$first_production_costs = floatval($_POST["first_production_cost"]);			
		$priceForOne = 0.00;
		foreach ($arr as $production_costs){
			$priceline_id = wp_insert_post(array('post_type'=>'priceline'));
			update_post_meta( $priceline_id, 'product_id', $new_product_id, '' );			
			update_post_meta( $priceline_id, 'amount', intval($production_cost['amount']), '' );
			update_post_meta( $priceline_id, 'production_costs', floatval($production_cost['production_cost']), '' );		
			if (intval($production_cost['amount'])==0){
				$priceForOne = floatval($production_cost['production_cost']);
			}
		}
		update_post_meta( $new_product_id, 'production_costs', $priceForOne, '' );	
		echo wp_json_encode( array(
			'result'				=>		true,
			'new_product_id'		=>		intval($new_product_id),
			'production_costs'		=>		json_encode($arr),
			'meta'					=>		get_post_meta( $new_product_id, '', false ),
			'post'					=>		get_post($new_product_id)			
		) );
		exit();
		wp_die();			
	}	
	public function ajax_add_customer(){
		$customerName = sanitize_text_field($_POST['customer_name']);
		$customerDescription = sanitize_text_field($_POST['customer_description']);
		$customerImage = intval($_POST['customer_image']);		
		$customer_id = intval(wp_insert_post(array('post_content'=>$customerDescription, 'post_title'=> $customerName, 'post_type'=>'customer' )));
		set_post_thumbnail( $customer_id, $customerImage );
		wp_publish_post($customer_id);
		echo wp_json_encode( array(
			'result'				=>		true,
			'new_customer_id'		=>		intval($customer_id),
			'post'					=>		get_post($customer_id)			
		) );
		exit();
		wp_die();			
	}
	public function ajax_add_manager(){
		$manmagerFirstName = sanitize_text_field($_POST["manager_first_name"]);
		$manmagerLastname = sanitize_text_field($_POST["manager_last_name"]);
		$manmagerEmail = sanitize_email($_POST["manager_email"]);
		$manmagerPassword = sanitize_text_field($_POST["manager_password"]);
		$new_office_id = intval($_POST["new_office_id"]);
		$is_headoffice = boolval($_POST["is_headoffice"]);
		$customerID = intval(get_post_meta( $new_office_id, 'customer_id', true ));
		$userdata = array(
		    'user_login'  =>  $manmagerEmail,
		    'user_pass'   =>  $manmagerPassword,
		    'pre_user_email'=> $manmagerEmail,
		    'first_name'	=>$manmagerFirstName,
		    'last_name'=>$manmagerLastname
		);
		$user_id = intval(wp_insert_user( $userdata )) ;
		$user = get_user_by( 'ID', $user_id );
		$user->add_role( 'ms_manager' );
		update_user_meta( $user_id, 'customer_id', $customerID );
		echo wp_json_encode( array(
			'result'			=>		true,
			'new_user_id'		=>		intval($user_id),
			'first_name'		=>		$manmagerFirstName,
			'last_name'			=>		$manmagerLastname
		) );
		exit();
		wp_die();			
	}
}
