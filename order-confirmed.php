<?php 
	$functions = new Merch_Stock_Functions();
	$order_id = intval($_GET['order_id']);
	$orderlines = json_decode($functions->getOrderLines($order_id));	
?>
<header class="page-header col-lg-12">
	<h2>
		<?php 	
			echo esc_html(get_the_title($order_id));
		?>
	</h2>
	<div class="right-wrapper text-right">
	</div>
</header>
<section role="main" class="content-body">	
<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
<div class="plugin-content col-lg-12 nopadding nomargin card">
	<header class="card-header">
		<div class="card-actions">
		</div>				
		<h2 class="card-title">Order Status</h2>
	</header>	
	<div class="card-body">
	<div class="col-lg-12">	
		<select data-plugin-selecttwo="" class="form-control populate select2-hidden-accessible offer-status" name="offer-status" data-select2-id="1" tabindex="-1" aria-hidden="true">
			<option value='init'>init</option>
			<option value='confirmed'>confirmed</option>
		</select>
		</div>
	</div>
</div>


<div class="plugin-content col-lg-12 nopadding nomargin card">
	<header class="card-header">
		<div class="card-actions">
		</div>				
		<h2 class="card-title">Order Confirmed!!!</h2>
	</header>	
	<div class="card-body">
	<div class="col-lg-12">
		<table class="table table-bordered table-striped mb-0 dataTable no-footer">
			<thead>
				<tr>
					<th>ID</th>
					<th>Product</th>					
					<th>Weight</th>
					<th>Amount</th>
					<th>Price</th>
					<th>Price Total</th>			
				</tr>
			</thead>
			<?php
				$output = "";
				$total_order = 0.00;
				foreach ($orderlines as $key => $orderline) {
					$product_id = get_post_meta( $orderline->ID, 'product_id', true );
					$product = get_post($product_id);	
					$amount = get_post_meta( $orderline->ID, 'amount', true );
					$product_price =  get_post_meta( $product->ID, 'product_price', true );				
					$product_weight = get_post_meta( $product->ID, 'product_weight', true );
					$total_orderline = $amount * $product_price;
					$total_order += $total_orderline;
					$output .= "<tr>";
					$output .= "<td>";
					$output .= $orderline->ID;
					$output .= "</td>";
					$output .= "<td>";
					$output .= $product->ID;
					$output .= "</td>";
					$output .= "<td>";
					$output .= $product_weight;
					$output .= "</td>";
					$output .= "<td>";
					$output .= $amount;
					$output .= "</td>"; 	
					$output .= "<td>";
					$output .= $product_price;
					$output .= "</td>"; 
					$output .= "<td>";
					$output .= $total_orderline;
					$output .= "</td>"; 											 						 						
					$output .= "</tr>";					
				}		
				$output .= "<tr>";
				$output .= "<td colspan='5'>";
				$output .= "Totaal:";
				$output .= "</td>";
				$output .= "<td>";
				$output .= $total_order;
				$output .= "</td>";
				$output .= "</tr>";
				echo esc_html($output);
			?>		
		</table>
	</div>
</div>
</div>	
<button data-order_id="<?php echo esc_attr($order_id) ?>" type="submit" id="addToTable" class="btn btn-primary">Save order +</i></button>
<input type="hidden" name="order_id" value="<?php echo esc_attr($order_id) ?>">
<input type="hidden" name="action" value="update_order">	
</section>