<?php 	
	$admin_functions = new Merch_Stock_WP_Admin();
	$order_id = intval($_GET['order_id']);
?>
<div class="wrap order wilson">	
	<h1 class='wp-heading-inline'>Order #<?php echo $order_id ?></h1>

	<div class="button-bar">
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="invoice_pdf">
			<input type="hidden" value="<?php echo $order_id ?>" name="order_id">
			<button class="button"><i class="fa fa-file-pdf"></i>Generate PDF</button>		
		</form>			
	</div>	
	<div class='filters'>
		<label>&nbsp;</label>
	</div>	
	<div class="content">
		<h5>
			<span>
				Orderlines
			</span>
		</h5>
		<?php 	
			echo $admin_functions->drawOrder($order_id);
		?>
	</div>	

	<div class="content">
		<h5>
			<span>
				Order total
			</span>
		</h5>
		<?php 	
			echo $admin_functions->drawOrderTotal($order_id);
		?>
	</div>

	<div class="content">
		<h5>
			<span>
				Order comments
			</span>
		</h5>
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">	
			<input type="hidden" name="action" value="ship_order">
			<?php 	
				echo $admin_functions->drawOrderComments($order_id);
			?>
		</form>
	</div>		

	<div class="content">
		<h5>
			<span>
				Order history
			</span>
		</h5>
		<?php 	
			echo $admin_functions->drawOrderHistory($order_id);
		?>
	</div>	
	<?php 	

	?>
</div>

