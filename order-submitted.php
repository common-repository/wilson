<?php 
	$functions = new Merch_Stock_Functions();
	$order_id = intval($_GET['order_id']);
	$orderlines = json_decode($functions->getOrderLines($order_id));	
	$office_order_id = get_post_meta( $order_id, 'office_id', true );	
?>
<header class="page-header col-lg-12">
	<h2>
		<?php 	
			echo esc_html((get_the_title($order_id) . " #" . $order_id . " - " . get_the_title( $office_order_id )));
		?>
	</h2>
	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>
				<a href="admin.php?page=Dashboard">
					<i class="fa fa-home"></i>
				</a>
			</li>
			<li><a href="admin.php?page=orders"><span>Orders</span></a></li>
			<li><a href="admin.php?page=order?order_id=<?php echo esc_attr($order_id) ?>">Order #<?php echo(esc_html($order_id . " - " . get_the_title( $office_order_id ))); ?></a></li>
			<li><span>Thanks for submitting your order</span></li>
		</ol>		
	</div>
</header>
<section role="main" class="content-body">	
	<div class="col-lg-12">
		<header class="card-header">
			<div class="card-actions">
			</div>

			<h2 class="card-title">Thanks for submitting your order</h2>
		</header>
		<div class="card-body">			
			You will recieve updates about this order while it is being processed
			<br><br>				
			<a href="<?php echo esc_url(admin_url( 'admin.php?page=invoice&order_id='.$order_id )) ?>">
				<button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>Packing list</button>	
			</a>
		</div>	
	</div>
</section>