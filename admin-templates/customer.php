<?php 	
	$admin_functions = new Merch_Stock_WP_Admin();
	$functions = new Merch_Stock_Functions();
	$customer_id = intval($_GET['customer_id']);
	$customer = get_post($customer_id);
	$meta = get_post_custom( $customer_id );
	$url = esc_url(get_the_post_thumbnail_url($customer_id));
?>
<div class="wrap order wilson">	
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">	
	<h1 class='wp-heading-inline'>Customer overview - <?php echo esc_html(get_the_title( $customer_id )); ?></h1>

	<div class="button-bar">
		<button type="button" class="button"><a href="<?php echo admin_url( 'admin.php?page=admin_customer_edit&customer_id='.$customer_id ) ?>" class="page-title-action"><i class="fa fa-edit"></i>Edit office</a></button>
		<button type="button" class="button"><a href="<?php echo admin_url( 'admin.php?page=new_customer' ) ?>" class="page-title-action"><i class="fa fa-plus"></i>Add new</a></button>		
	</div>	
	<div class='filters'>
		<label>&nbsp;</label>
	</div>
	<div class="content backorders">
		<h5>
			<span>
				Customer properties
			</span>
		</h5>
		<div>	
			<label>ID</label>
			<label><?php echo $customer_id ?></label>
		</div>
		<div>	
			<label>Title</label>
			<label><?php echo esc_html(get_the_title( $customer_id )); ?></label>
		</div>
		<div>	
			<label>Description</label>
			<label><?php echo esc_html($customer->post_content) ?></label>
		</div>						
		<div>
			<label>Office image</label>
			<img class="edit-thumbnail" src="<?php echo $url ?>" /><br>	
		</div>		
	</div>
	<div class="content backorders">
		<h5>
			<span>
				Customer address
			</span>
		</h5>
		<div>	
			<label>Addressline #1</label>
			<label><?php echo esc_html($meta["addressline1"][0]) ?></label>
		</div>
		<div>	
			<label>Addressline #2</label>
			<label><?php echo esc_html($meta["addressline2"][0]) ?></label>
		</div>
		<div>	
			<label>Addressline #3</label>
			<label><?php echo esc_html($meta["addressline3"][0]) ?></label>
		</div>
		<div>	
			<label>Postal code</label>
			<label><?php echo esc_html($meta["postal_code"][0]) ?></label>
		</div>
		<div>	
			<label>City</label>
			<label><?php echo esc_html($meta["city"][0]) ?></label>
		</div>
		<div>	
			<label>Region</label>
			<label><?php echo esc_html($meta["region"][0]) ?></label>
		</div>
		<div>	
			<label>County</label>
			<label><?php echo esc_html($meta["county"][0]) ?></label>
		</div>
		<div>	
			<label>Country</label>
			<label><?php echo esc_html($meta["country"][0]) ?></label>
		</div>
		<hr>	
		<div>	
			<label>Telephone</label>
			<label><?php echo esc_html($meta["telephone"][0]) ?></label>
		</div>
		<div>	
			<label>E-mail</label>
			<label><?php echo esc_html($meta["email"][0]) ?></label>
		</div>					
	</div>