<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/admin
 * @author     <info@merchandise.nl>
 */
class Merch_Stock_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $functions;	
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $user_functions;		
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0 
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->functions = new Merch_Stock_Functions();	
		$this->user_functions = new Merch_Stock_User_Functions();
		$this->order_functions = new Merch_Stock_Order_Functions();
		remove_action( 'in_admin_header', 'action_in_admin_header', 10, 2 ); 
	}

	public static function add_order_headers(){
		$defaults['ID'] = 'ID';
		$defaults['customer'] = 'Customer';
		$defaults['office'] = 'Office';
		$defaults['user'] = 'User';
		$defaults['status'] = 'Status';
		$defaults['date'] = 'Date';
		$defaults['shipping'] = 'Shipping';
		$defaults['production'] = 'Production';
		$defaults['actions'] = 'Actions';
	    return $defaults;
	}

	public static function order_table_content($column_name, $post_id){
		$order = get_post(intval($post_id));
		$office_id = intval( get_post_meta( intval($post_id), 'office_id', true ) );
		$office = get_post($office_id);
		$customer_id = intval( get_post_meta( $office_id, 'customer_id', true ) );
		$user_id = intval( get_post_meta( $post_id, 'user_id', true ) );	 	
		$order_user = get_user_by( 'ID', $user_id );

		if ($column_name=='ID'){
			echo intval($order->ID);
		}
		if ($column_name=='status'){
			echo $this->functions->getStatusBubble(get_post_meta( $post_id, 'status', true ));
		}
		if ($column_name=='office'){			
			$office_name = esc_html(get_the_title( $office_id ));
			echo "<a href='post.php?post=$office_id&action=edit'>$office_name </a>";			
		}
		if ($column_name=='customer'){
			$customer_name =  esc_html(get_the_title( $customer_id ));
			echo "<a href='post.php?post=$customer_id&action=edit'>$customer_name </a>";			
		}	
		if ($column_name=='user'){			
			$order_user_name =  esc_html($order_user->user_firstname . ' ' . $order_user->user_lastname); 
			echo "<a href='user-edit.php?user_id=$user_id'>$order_user_name </a>";
		}
		if ($column_name=='shipping'){
			echo  esc_html($this->order_functions->getOrderShippingCostsV2($order->ID));
		}
		if ($column_name=='production'){
			echo  esc_html($this->order_functions->getOrderProductionCosts($post_id));
		} 
		if ($column_name=='actions'){
			$ajaxURL = esc_url( admin_url("admin-post.php") );
			$output = "<a href='" . esc_ulr(admin_url( 'post.php?post='.intval($post_id).'&action=edit' )) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>"; 
			$output .= '<form action="'.$ajaxURL.'" method="POST">';
			$output .=	'<input type="hidden" name="action" value="invoice_pdf">';
			$output .=	'<input type="hidden" name="order_id" value="'.intval($post_id).'">		';
			$output .=	'<button class="mb-1 mt-1 mr-1 btn btn-primary" type="submit" name="generate_posts_pdf" value="generate">Generate PDF</button>';
			$output .= '</form>';
			echo $output;
		}										
	}

	function remove_core_updates () {
	     global $wp_version;
	     return(object) array(
	          'last_checked'=> time(),
	          'version_checked'=> $wp_version
	     );
	}	
	
	public static function create_admin_header(){	
		$template = plugin_dir_path( __FILE__ ) . '/../../admin-header.php';
		echo load_template( $template, true );	
	}

	public function accept_backorder(){
		$amount = intval($_POST["amount"]);
		$backorder_id = intval($_POST["backorder_id"]);
		$status = sanitize_text_field($_POST["button"]);
		update_post_meta( $backorder_id, 'amount', $amount, '' );
		update_post_meta( $backorder_id, 'status', $status, '' );
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'admin_backorder',
		    	'&backorder_id' => $backorder_id,
			), admin_url('admin.php') )
   		);				
	}

	public function add_customer(){
		$title = sanitize_text_field($_POST["title"]);
		$description = sanitize_text_field($_POST["description"]);
		$customer_id = intval( wp_insert_post(array('post_title'=>$title, 'post_status'   => 'publish', 'post_type'=>'customer')) );
		$post = get_post($customer_id);
		$post->post_content = $description;
		$productImage = intval($_POST['selected_logo']);
		set_post_thumbnail( $customer_id, $productImage );
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'customers',
			), admin_url('admin.php') )
   		);
    	exit;		
	}

	public function update_product_request_admin(){
		$product_request_id = intval($_POST["product_request_id"]);
		$comments = sanitize_text_field($_POST["comments"]);
		$status = sanitize_text_field($_POST["button"]);
		update_post_meta( $product_request_id, 'comments', $comments, '' );
		update_post_meta( $product_request_id, 'status', $status, '' );
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'product_requests',
			), admin_url('admin.php') )
   		);
    	exit;			
	}

	public function update_wilson_customer(){
		$customer_id = intval($_POST["customer_id"]);
	  	$my_update = array(
	     	'ID'           => intval($customer_id),
	      	'post_status'   => 'publish',
	      	'post_title'			=> sanitize_text_field($_POST["title"]),
	      	'post_content'		=> sanitize_text_field($_POST["description"])
	  	);	  	
	  	wp_update_post($my_update);			
		$productImage = intval($_POST['selected_logo']);
		set_post_thumbnail( $customer_id, $productImage );
	  	update_post_meta( $customer_id, 'shipping_box_price', floatval($_POST['shipping_box_price']), '' );	
	  	update_post_meta( $customer_id, 'shipping_box_weight', intval($_POST['shipping_box_weight']), '' );	
	  	update_post_meta( $customer_id, 'addressline1', sanitize_text_field($_POST['addressline1']), '' );	
	  	update_post_meta( $customer_id, 'addressline2', sanitize_text_field($_POST['addressline2']), '' );	
	  	update_post_meta( $customer_id, 'addressline3', sanitize_text_field($_POST['addressline3']), '' );	
	  	update_post_meta( $customer_id, 'postal_code', sanitize_text_field($_POST['postal_code']), '' );	
	  	update_post_meta( $customer_id, 'city', sanitize_text_field($_POST['city']), '' );	
	  	update_post_meta( $customer_id, 'region', sanitize_text_field($_POST['region']), '' );	
	  	update_post_meta( $customer_id, 'county', sanitize_text_field($_POST['county']), '' );	
	  	update_post_meta( $customer_id, 'country', sanitize_text_field($_POST['country']), '' );	
	  	update_post_meta( $customer_id, 'telephone', sanitize_text_field($_POST['telephone']), '' );	
	  	update_post_meta( $customer_id, 'email', sanitize_text_field($_POST['email']), '' );	
	  
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'admin_customer',
		    	'&customer_id' => $customer_id,
			), admin_url('admin.php') )
   		);	  	
	 }



	public function update_wilson_office(){
		$office_id = intval($_POST["office_id"]);
	  	$my_update = array(
	     	'ID'           => $office_id,
	      	'post_status'   => 'publish',
	      	'post_title'			=> sanitize_text_field($_POST["title"]),
	  	);
	  	wp_update_post($my_update);	
	  	update_post_meta( $office_id, 'shipping_box_price', floatval($_POST['shipping_box_price']), '' );	
	  	update_post_meta( $office_id, 'shipping_box_weight', intval($_POST['shipping_box_weight']), '' );	
	  	update_post_meta( $office_id, 'addressline1', sanitize_text_field($_POST['addressline1']), '' );	
	  	update_post_meta( $office_id, 'addressline2', sanitize_text_field($_POST['addressline2']), '' );	
	  	update_post_meta( $office_id, 'addressline3', sanitize_text_field($_POST['addressline3']), '' );	
	  	update_post_meta( $office_id, 'postal_code', sanitize_text_field($_POST['postal_code']), '' );	
	  	update_post_meta( $office_id, 'city', sanitize_text_field($_POST['city']), '' );	
	  	update_post_meta( $office_id, 'region', sanitize_text_field($_POST['region']), '' );	
	  	update_post_meta( $office_id, 'county', sanitize_text_field($_POST['county']), '' );	
	  	update_post_meta( $office_id, 'country', sanitize_text_field($_POST['country']), '' );	
	  	update_post_meta( $office_id, 'customer_id', sanitize_text_field($_POST['select_customer']), '' );	
	  	$post = get_post($office_id);
	  	$post->post_content = sanitize_text_field($_POST["description"]);
	  	
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'admin_office_edit',
		    	'&office_id' => $office_id,
			), admin_url('admin.php') )
   		);	  	
	}			

	public function update_wilson_product(){
		$product_id = intval($_POST["product_id"]);
	  	$my_update = array(
	     	'ID'           => $product_id,
	      	'post_status'   => 'publish',
	      	'post_title'			=> sanitize_text_field($_POST["product_name"] ),
	      	'post_content'		=> sanitize_text_field($_POST["product_description"] )
	  	);
	  	wp_update_post($my_update);
		update_post_meta( $product_id, 'article_number', sanitize_text_field($_POST['article_number'], '' ));
		update_post_meta( $product_id, 'product_weight', floatval($_POST['product_weight'], '' ));
		update_post_meta( $product_id, 'minimal_order_amount', intval($_POST['minimal_order_amount'], '' ));
		update_post_meta( $product_id, 'order_per', intval($_POST['order_per'], '' ));
		update_post_meta( $product_id, 'order_term', intval($_POST['order_term'], '' ));
		update_post_meta( $product_id, 'warn_amount', intval($_POST['warn_amount'], '' ));
		$descriptions = sanitize_text_field($_POST["description"]);
		$amounts = intval($_POST["amount"]);
		$cost = floatval($_POST["cost"]);
		$stocks = intval($_POST["stock"]);
		$productImage = intval($_POST['selected_logo']);
		set_post_thumbnail( $product_id, $productImage );

		$price = floatval(get_post_meta( $product_id, 'production_costs', true ));

		foreach (Merch_Stock_Functions::getStocklines($product_id) as $key => $stockline){			
			$stockline_id = intval($stockline->ID);
			if (!isset($descriptions[$stockline_id])){
				wp_delete_post($stockline_id);
			}
		}

		foreach ($descriptions as $key => $desc){
			if (!(bool)strtotime($key)){	
				$stockline_id = intval(wp_insert_post(array('post_title' => $desc, 'post_type'=>'stockline')));		
				update_post_meta( $stockline_id, 'product_stock', intval($stocks[$key]), '' );
				update_post_meta( $stockline_id, 'product_id', $product_id, '' );
				update_post_meta( $stockline_id, 'description', sanitize_text_field( $desc ), '' );
			}
			else{
				$variation = get_post($key);
				update_post_meta($key,'description',sanitize_text_field($desc));
				update_post_meta($key,'product_stock',intval($stocks[$key]));
			}
		}

		foreach ($this->functions->getPricelines($product_id, 'ASC') as $key => $priceline){		
			$priceline_id = $priceline["priceline"]->ID;
			if (!isset($amounts[$priceline_id])){
				$amount = get_post_meta( $priceline_id, 'amount', true );
				if ($amount>1){
					wp_delete_post($priceline_id);
				}
			}
		}
		$priceForOne = 0.00;

		foreach ($amounts as $key => $amount){
			if ($amount==1){
				$priceForOne=floatval($cost[$key]);
			}
			if (!(bool)strtotime($key)){					
				$priceline_id = wp_insert_post(array('post_type'=>'priceline'));		
				update_post_meta( $priceline_id, 'amount', intval($amounts[$key]), '' );
				update_post_meta( $priceline_id, 'product_id', $product_id, '' );
				update_post_meta( $priceline_id, 'production_costs', floatval($cost[$key]), '' );				
			}
			else{
				$priceline = get_post($key);
				update_post_meta($priceline->ID,'amount',intval($amounts[$key]));
				update_post_meta($priceline->ID,'production_costs', floatval($cost[$key]));
			}
		}


   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'admin_product_edit',
		    	'&product_id' => $product_id,
			), admin_url('admin.php') )
   		);	
	}	

	public function delete_wilson_customers(){
		$ids = sanitize_text_field($_POST["ids"]);
		$JSON_ids = json_decode(str_replace("\\","", $ids),true);
		foreach ($JSON_ids as $product_id){
			wp_delete_post($product_id);
		}
   		wp_redirect(  
	   		add_query_arg( array(
		    	'page' => 'customers',
			), admin_url('admin.php') )
   		);
    	exit;	
	}		
}
