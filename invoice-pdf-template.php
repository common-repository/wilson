<?php 
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();	
	$order_functions = new Merch_Stock_Order_Functions();		
	$order = get_post($order_id);
	$invoice_id = get_post_meta( $order_id, 'invoice_id', true );
	$invoice = get_post($invoice_id);
	$invoice_custom = get_post_custom($invoice_id);
	$invoice_status = $invoice_custom["status"];
	$office_id = get_post_meta( $order_id, 'office_id', true );
	$customer_id = get_post_meta( $office_id, 'customer_id', true );
	$office = get_post($office_id);
	$office_custom = get_post_custom( $office->ID );
?>
<!DOCTYPE html>
<html>
<head>
	<title>Package List #<?php echo intval($order_id) ?></title>
	<style>
		table{
			border-collapse: collapse;
		}
		th, td{
  			padding: 15px;
		}		
		th{
			background-color: #777777;
    		color: #ffffff;			
		}
		h1{
			margin-bottom: 20px;
			padding-bottom: 20px;
		}
		td.title{
			vertical-align: top;
			valign:top;
		}
		td.title img{
			max-width: 100px;
			max-height: 100px;
			display: block;
		}
		td.address{
			text-align: right;
		}
		tr.total{
			font-weight: bold;
		}
		tr.bordered>td {
		    border-top: 1px solid #000!important;
		}
		.text-right{
			text-align: right;
		}
	</style>
</head>
<body>
	<table style="width: 100%;">
		<tr>
			<td class="title address-to" colspan="3">
				<h1>
					Packing List <br/> #<?php echo esc_attr($order_id) ?>
				</h1>	
				<address>
					<?php 	
						echo "<img src='".esc_url(get_the_post_thumbnail_url($user_functions->getUserCustomer()))."'  class='invoice-image' data-lock-picture='".esc_url(get_the_post_thumbnail_url($user_functions->getUserCustomer()))."' />"	
					?>				
					<p>	
						<b><?php echo esc_html(get_the_title($office->ID)) ?></b>						
						<?php echo (strlen($office_custom["addressline1"][0])>0 ? '<br/>'.esc_html($office_custom["addressline1"][0]):'') ?>	
						<?php echo (strlen($office_custom["addressline2"][0])>0 ? '<br/>'.esc_html($office_custom["addressline2"][0]):'') ?>	
						<?php echo (strlen($office_custom["addressline3"][0])>0 ? '<br/>'.esc_html($office_custom["addressline3"][0]):'') ?>	
						<?php echo (strlen($office_custom["postal_code"][0])>0 ? '<br/>'.esc_html($office_custom["postal_code"][0]):'') ?>						
						<?php echo (strlen($office_custom["city"][0])>0 ? '<br/>'.esc_html($office_custom["city"][0]):'') ?>									
						<?php echo (strlen($office_custom["region"][0])>0 ? '<br/>'.esc_html($office_custom["region"][0]):'') ?>									
						<?php echo (strlen($office_custom["county"][0])>0 ? '<br/>'.esc_html($office_custom["county"][0]):'') ?>									
						<?php echo (strlen($office_custom["country"][0])>0 ? '<br/>'.esc_html($office_custom["country"][0]):'') ?>	
					</p>	
				</address>				
			</td>
			<td class="address" colspan="2"> 
				<img src=" <?php echo esc_url(get_the_post_thumbnail_url($customer_id)) ?>" width="100" height="100" class="" />
					<address class="ib mr-5">
						<?php
							echo $functions->drawAddress($customer_id);
						?>
					</address>
			</td>
		</tr>
	</table>
	<table style="width: 100%;">
		<tr>
			<th>ID</th>
			<th>Product</th>
			<th class="text-right" colspan="4">Amount</th>
		</tr>		
		<?php 
			foreach ($functions->getOrderlines($order->ID) as $key1 => $orderline){	
				$output = "";
				$tmpOrderline = json_decode($orderline[0],true);	
				$tmpOrderlineCustom = get_post_custom($tmpOrderline["ID"]);
				
				$amount = $tmpOrderlineCustom["amount"][0];
				$product_price = $tmpOrderlineCustom["product_price"][0];
				$production_costs = $tmpOrderlineCustom["production_costs"][0];
				$production_costs = $tmpOrderlineCustom["production_costs"][0];
				$stockline_id = intval($tmpOrderlineCustom["stockline_id"][0]);
				$stockline = get_post($stockline_id);
				$product = get_post(get_post_meta( $stockline_id, 'product_id', true ));				
				$total = $amount*$production_costs;
				$orderTotal += $total;
				$orderProductionCosts += $amount*$production_costs;
				$totalProductCostMonth += $total;
				$class = "uneven";
				$output .= "<tr>";
				$output .= "<td>";
				$output .= $tmpOrderline["ID"];
				$output .= "</td>";
				$output .= "<td>";
				$output .= get_the_title( $product->ID );
				if ($stockline_id>0){
					$output .= " " . get_post_meta( $stockline_id, 'description', true );
				}				
				$output .= "</td>";
				$output .= "<td class='text-right' colspan='4'>";
				$output .=  $tmpOrderlineCustom["amount"][0];
				$output .= "</td>";																
				$output .= "</tr>";
				echo $output;
			}

			$orderTotal += $office_shipping_costs;
			$totalMonth += $orderTotal;
			$productionCostMonth += $orderProductionCosts;
			$shippingCostMonth += $office_shipping_costs;
			echo "<tr class='bordered'><td colspan='2' ></td><td class='text-right' colspan='4'>Total carton(s): ".json_decode($order_functions->getOrderShippingCostsV2($order_id, false, false, false, true),true)[1]."</td></tr>";	
		?>
	</table>
</body>
</html>