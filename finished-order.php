<?php 
	$functions = new Merch_Stock_Functions();
	$order_id = intval($_GET['order_id']);
	$orderlines = json_decode($functions->getOrderLines($order_id));
?>
<div class="row nopadding nomargin">
	<h1>Order</h1>
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
				$total_order = floatval(0.00);
				foreach ($orderlines as $key => $orderline) {
					$orderline_id = intval($orderline->ID);
					$product_id = intval(get_post_meta( $orderline_id, 'product_id', true ));
					$product = get_post($product_id);	
					$amount = intval(get_post_meta( $orderline->ID, 'amount', true ));
					$product_price =  floatval(get_post_meta( $product_id, 'product_price', true ));				
					$product_weight = floatval(get_post_meta( $product_id, 'product_weight', true ));
					$total_orderline = floatval($amount * $product_price);
					$total_order += floatval($total_orderline);
					$output .= "<tr>";
					$output .= "<td>";
					$output .= $orderline_id;
					$output .= "</td>";
					$output .= "<td>";
					$output .= $product_id;
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
				echo $output;
			?>		
		</table>
	</div>
	<div id="product_information_popup" class="row nopadding nomargin">
		<div id="close_product_information_popup">X</div>
		<div class="col-lg-4 nopadding nomargin">
			Product Name: <div class="product-name"></div><br>
			Product Price: <div class="product-price"></div><br>
			Product Weight: <div class="product-weight"></div><br>			
		</div>
		<div class="col-lg-8 nopadding nomargin">				
			<div class="product-content"></div>
		</div>
	</div>
</div>
