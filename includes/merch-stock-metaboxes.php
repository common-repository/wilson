<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/metabox
 */
/**
 * The metabox-specific functionality of the plugin.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/metabox
 * @author     <info@merchandise.nl>
 */
class Merch_Stock_Metaboxes {
	private $plugin_name;
	private $version;
	private $functions;	
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->functions = new Merch_Stock_Functions();
	}	

	function add_product_meta_boxes() {	
	  	add_meta_box( 'product_price_meta_boxes', 'Prices / packaging', array('Merch_Stock_Metaboxes', 'price_meta_v2'),'wilson-product', 'normal', 'low');
	  	add_meta_box( 'product_stock', 'Stock', array('Merch_Stock_Metaboxes', 'stock_meta_v2'),'wilson-product', 'normal', 'low');
	  	add_meta_box( 'Add Customer', 'Customer Relationship', array('Merch_Stock_Metaboxes','customer_field') , 'wilson-product' );	
	  	add_meta_box( 'Add Customer', 'Customer Relationship', array('Merch_Stock_Metaboxes','customer_field') , 'office' );	
	  	add_meta_box( 'Add Users', 'Offices Relationship', array('Merch_Stock_Metaboxes','office_user') , 'office' );	
	}	

	function add_customer_meta_boxes(){		
	}

	function add_office_meta_boxes() {
		add_meta_box("office_meta_boxes", "Office Order Settings", array('Merch_Stock_Metaboxes', 'office_meta'),"office", "normal", "low");
		add_meta_box("office_address_meta_boxes", "Office Address", array('Merch_Stock_Metaboxes', 'office_meta_address'),"office", "normal", "low");		
	}	

	function orderv2(){
		global $post;
		
		$order = get_post($post->ID);
		$orderlines = Merch_Stock_Functions::getOrderlines($post->ID);
		echo count($orderlines);
		foreach ($orderlines as $key => $orderline){
			$output .= "<div></div>";
		}
		echo $output;
	}

	function office_meta(){
		global $post;
		$custom = get_post_custom($post->ID);
		$office_shipping_costs = $custom["office_shipping_costs"][0];
		$shipping_box_price = $custom["shipping_box_price"][0];
		$shipping_box_weight = $custom["shipping_box_weight"][0];
		?>
			<p><label>Office Shipping Costs</label><br />
			<input type="number" name="office_shipping_costs" step=".01" value="<?php echo $office_shipping_costs; ?>" />
			</p>
			<div>
			<p><label>Shipping Box Price</label><br />
				<input type="number" name="shipping_box_price" value="<?php echo $shipping_box_price; ?>" />	</p>				
			<p><label>Shipping Box Weight</label><br />
				<input type="number" name="shipping_box_weight" step=".01" value="<?php echo $shipping_box_weight; ?>" /></p>
			</div>			
		<?php
	}

	function office_meta_address(){
		global $post;		
		$custom = get_post_custom($post->ID);
		$street = $custom["street"][0];
		$street_nr = $custom["street_nr"][0];
		$postal_code = $custom["postal_code"][0];
		$country = $custom["country"][0];
		$county = $custom["county"][0];
		$city = $custom["city"][0];		
		$region = $custom["region"][0];		
		$telephone = $custom["telephone"][0];		
		$addressline1 = $custom["addressline1"][0];		
		$addressline2 = $custom["addressline2"][0];		
		$addressline3 = $custom["addressline3"][0];				
			?>		
				<p><label>Addressline #1</label><br />
				<input type="text" name="addressline1" value="<?php echo $addressline1; ?>" />
				</p>
				<p><label>Addressline #2</label><br />
				<input type="text" name="addressline2" value="<?php echo $addressline2; ?>" />
				</p>
				<p><label>Addressline #3</label><br />
				<input type="text" name="addressline3" value="<?php echo $addressline3; ?>" />
				</p>								
				<p><label>Postal Code</label><br />
				<input type="integer" name="postal_code" value="<?php echo $postal_code; ?>" />
				</p>	
				<p><label>City</label><br />
				<input type="text" name="city" value="<?php echo $city; ?>" />
				</p>		
				<p><label>Region</label><br />
				<input type="text" name="region" value="<?php echo $region; ?>" />
				</p>	
				<p><label>County</label><br />
				<input type="text" name="county" value="<?php echo $county; ?>" />
				</p>	
				<p><label>Country</label><br />
				<input type="text" name="country" value="<?php echo $country; ?>" />
				</p>	
				<p><label>Telephone</label><br />
				<input type="text" name="telephone" value="<?php echo $telephone; ?>" />
				</p>																			
			<?php	
	}	

	public static function stock_meta_v2(){		
		global $post;
		$stockline_id = get_post_meta( $post->ID, 'stockline_id', true );
  		$args = array(
	    	'posts_per_page'   => -1,
	    	'post_type'        => 'stockline',
	    	'meta_key'			=> 'product_id',
	    	'meta_value'		=> $post->ID
		);
		$the_query = new WP_Query( $args );
		?>
			<div id="product-stock">
		<?php
		foreach ($the_query->posts as $key => $stockline){
			$custom = get_post_custom($stockline->ID);	
			$product_stock = $custom["product_stock"][0];
			$description = $custom["description"][0];		
			?>
				<div>
					<label>Description</label><br />
					<input type="text" name="product_stock_description[<?php echo $stockline->ID; ?>]" value="<?php echo $description; ?>" />					
					<label>Product Stock</label><br />
					<input type="number" name="product_stock[<?php echo $stockline->ID; ?>]" step=".01" value="<?php echo $product_stock; ?>" />
				</div>
			<?php				
		}
		?>
			</div>	
			<input id="add-stock-line" type="button" name="">
		<?php
	}	

	public static function customer_field_OLD() {	
		$isMerchStockUser = Merch_Stock_User_Functions::isMerchStockUser();
		if ($isMerchStockUser){
			global $post;
	    	$current_customer = get_post_meta( $post->ID, 'customer_id', true );
	    	$all_customers = get_posts( array(
		        'post_type' => 'customer',
		        'numberposts' => 100,
		        'orderby' => 'id',
		        'order' => 'ASC'
		    ) );		
	    	?>	    		
	    	    <input type="hidden" name="customers_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
			    <table class="form-table">
			    <tr valign="top"><th scope="row">
			    <label for="customer_id">Customer</label></th>
			    <td><select name="customer_id">
			    <?php foreach ( $all_customers as $customer ) : ?>
	                <option value="<?php echo $customer->ID; ?>" <?php echo ( $customer->ID==$current_customer ) ? ' selected="selected"' : ''; ?> ><?php echo get_the_title($customer->ID); ?></option>
			    <?php endforeach; ?>
			    </select></td></tr>
			    </table>
	    	<?php
		}
	}	

	public static function office_user() {
		global $post;
		// echo "USER IDS: ";
		$user_ids = get_post_meta( $post->ID, 'user_ids', true );
		$all_users = get_users();
		foreach ( $all_users as $user ) {
			$functions = new Merch_Stock_Functions();
			// if ($functions->getMerchStockRoleByID($user->ID)=="ms_manager"){
				echo '<label>';				
				echo "<br/>";
				if (strpos($user_ids, '|'.$user->ID.'|') !== false){
					echo '<input checked="checked" type="checkbox" name="users[]" value="'.$user->ID.'">';
				} 
				else{
					echo '<input type="checkbox" name="users[]" value="'.$user->ID.'">';
				}
		    	echo '<span>' . esc_html( $user->display_name ) . '</span><br/>';
		    	echo '</label>';				
			// }
		}		
	}	

	public static function customer_field_user() {
		$isMerchStockUser = Merch_Stock_User_Functions::isMerchStockUser();
		if (!$isMerchStockUser){
		global $user_id;
		$current_customer = get_user_meta($user_id, 'customer_id', true );
		$all_customers = get_posts( array(
	        'post_type' => 'customer',
	        'numberposts' => 100,
	        'orderby' => 'id',
	        'order' => 'ASC'
	    ) );		
    	?>	    		
    	    <input type="hidden" name="customers_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
		    <table class="form-table">
		    <tr valign="top"><th scope="row">
		    <label for="customer_id">Customer</label></th>
		    <td><select name="customer_id">
		    <?php foreach ( $all_customers as $customer ) : ?>
                <option value="<?php echo $customer->ID; ?>" <?php echo ( $customer->ID==$current_customer ) ? ' selected="selected"' : ''; ?> ><?php echo get_the_title($customer->ID); ?></option>
		    <?php endforeach; ?>
		    </select></td></tr>
		    </table>
    	<?php
}
	}
	public static function price_meta_v2(){
			global $post;			
	  		$args = array(
		    	'posts_per_page'   => -1,
		    	'post_type'        => 'priceline',
		    	'meta_key'			=> 'product_id',
		    	'meta_value'		=> $post->ID
			);
			$the_query = new WP_Query( $args );
			?>
				<div id="product-production_costs">
			<?php			
			foreach ($the_query->posts as $key => $priceline){
				$custom = get_post_custom($priceline->ID);	
				$amount = $custom['amount'][0];
				$production_costs = $custom['production_costs'][0];
				?>
					<div>
						<label>Amount</label><br />
						<input type="text" name="product_amount[<?php echo $priceline->ID; ?>]" value="<?php echo $amount ?>" />					
						<label>Costs per unit</label><br />
						<input type="decimal" name="product_production_costs[<?php echo $priceline->ID; ?>]"  value="<?php echo $production_costs ?>" />
					</div>
				<?php				
			}
		?>
			</div>	
			<input id="add-production_cost-line" type="button" name="">
		<?php
			$custom = get_post_custom($post->ID);
			// $product_price = $custom["product_price"][0];
			$product_weight = $custom["product_weight"][0];
			$production_costs = $custom["production_costs"][0];
			$order_per = $custom["order_per"][0];
			$order_term = $custom["order_term"][0];				
			$warn_amount = $custom["warn_amount"][0];
			$minimal_order_amount = $custom["minimal_order_amount"][0];
			?>
				<p><label>Product Weight</label><br />
				<input type="decimal" name="product_weight" step=".01" value="<?php echo $product_weight; ?>" />
				<p><label>Costs per unit</label><br />
				<input type="decimal" name="production_costs" step=".01" value="<?php echo $production_costs; ?>" />		
				<p><label>Minimal Order Amount</label><br />
				<input type="number" name="minimal_order_amount" step="1" value="<?php echo $minimal_order_amount; ?>" />
				<p><label>Order Amount Per</label><br />
				<input type="number" name="order_per" step="1" value="<?php echo $order_per; ?>" />
				<p><label>Order term</label><br />
				<input type="number" name="order_term" step="1" value="<?php echo $order_term; ?>" />	
				<p><label>Warn when XX items left</label><br />
				<input type="number" name="warn_amount" step="1" value="<?php echo $warn_amount; ?>" />								
			<?php
	}	

	function save_product(){
  		global $post;
		$product_id = $post->ID;  		
  		$new_lines = sanitize_text_field($_POST["stock_description"]);
  		foreach ($new_lines as $key => $new_line){
  			$stockline_id = wp_insert_post(array('post_title'=>'Stock '.get_the_title( $post->ID ), 'post_type'=>'stockline'));
  			update_post_meta( $stockline_id, 'product_id', $product_id );
  			update_post_meta( $stockline_id, 'product_stock', intval($_POST['product_stock'][$key]) );
  			update_post_meta( $stockline_id, 'description', sanitize_text_field($_POST['stock_description'][$key] ) );
  		}
  		$old_lines = sanitize_text_field($_POST["product_stock_description"]);
  		foreach ($old_lines as $key => $old_line){
  			$stockline = get_post($key);
  			if (intval($_POST['product_stock'][$key])==0){
				// REMOVE LINE
  			}
  			else{
  				update_post_meta( $stockline->ID, 'product_id', $product_id );
  				update_post_meta( $stockline->ID, 'product_stock', intval($_POST['product_stock'][$key]) );
  				update_post_meta( $stockline->ID, 'description', sanitize_text_field($_POST['product_stock_description'][$key]) );  				
  			}
  		}  		
  		$new_price_lines = intval($_POST["add_amount"]);
  		foreach ($new_price_lines as $key => $new_price_line){
  			$priceline_id = wp_insert_post(array('post_title'=>'Priceline '.get_the_title( $post->ID ), 'post_type'=>'priceline'));
  			update_post_meta( $priceline_id, 'product_id', $product_id );
  			update_post_meta( $priceline_id, 'amount', intval($_POST['add_amount'][$key] ) );
  			update_post_meta( $priceline_id, 'production_costs', floatval($_POST['add_production_costs'][$key] ) );
  		}  		
  		$old_price_lines = sanitize_text_field($_POST["product_amount"]);
  		foreach ($old_price_lines as $key => $old_price_line){
  			if (intval($_POST['product_amount'][$key])==0){
  					// REMOVE LINE
  			}
  			else{
  				$priceline = get_post($key);  			
  				update_post_meta( $priceline->ID, 'amount', intval($_POST['product_amount'][$key] ));
  				update_post_meta( $priceline->ID, 'production_costs', floatval($_POST['product_production_costs'][$key] ) );  				
  			}
  		}  		  		
		update_post_meta($post->ID, 'customer_id', intval($_POST['customer_id']));		
		update_post_meta($post->ID, "product_weight", floatval($_POST["product_weight"]));  
		update_post_meta($post->ID, "production_costs", floatval($_POST["production_costs"]));
		update_post_meta($post->ID, "order_per", intval($_POST["order_per"]));
		update_post_meta($post->ID, "warn_amount", intval($_POST["warn_amount"]));
		update_post_meta($post->ID, "order_term", intval($_POST["order_term"]));
		update_post_meta($post->ID, "minimal_order_amount", intval($_POST["minimal_order_amount"]));		
	}
	function save_customer(){
	}
	function save_office(){	
		global $post;
  		update_post_meta($post->ID, "addressline1", sanitize_text_field($_POST["addressline1"]));  
  		update_post_meta($post->ID, "addressline2", sanitize_text_field($_POST["addressline2"]));  
  		update_post_meta($post->ID, "addressline3", sanitize_text_field($_POST["addressline3"]));  
  		update_post_meta($post->ID, "postal_code", sanitize_text_field($_POST["postal_code"]));  
  		update_post_meta($post->ID, "city", sanitize_text_field($_POST["city"]));  
  		update_post_meta($post->ID, "region", sanitize_text_field($_POST["region"]));  
  		update_post_meta($post->ID, "county", sanitize_text_field($_POST["county"]));  
  		update_post_meta($post->ID, "country", sanitize_text_field($_POST["country"]));  
  		update_post_meta($post->ID, "telephone", sanitize_text_field($_POST["telephone"]));  
		update_post_meta($post->ID, "office_shipping_costs", floatval($_POST["office_shipping_costs"]));  
		update_post_meta($post->ID, "shipping_box_price", floatval($_POST["shipping_box_price"]));  
		update_post_meta($post->ID, "shipping_box_weight", floatval($_POST["shipping_box_weight"]));  
		update_post_meta($post->ID, "customer_id", intval($_POST["customer_id"]));
  		
  		$users = "";
  		foreach ($_POST["users"] as $key => $user) {	  			
  			$users .= "|".$user."|";
  		}
  		update_post_meta($post->ID, "user_ids", $users);  
  		wp_publish_post($post->ID);	
	}		
	function save_user_meta_boxes( $user_id ) {		
		update_user_meta( $user_id, 'customer_id', intval($_POST['customer_id'] ) );
	}	
}