<?php
	$office_id = intval($_GET['office_id']);
	$office = get_post($office_id);
	$custom = get_post_custom($office_id);
	$addressline1 = $custom["addressline1"][0];		
	$addressline2 = $custom["addressline2"][0];		
	$addressline3 = $custom["addressline3"][0];		
	$number = $custom["number"][0];		
	$postal_code = $custom["postal_code"][0];		
	$city = $custom["city"][0];		
	$country = $custom["country"][0];	
		
?>
<header class="card-header">
	<h2 class="card-title">Address</h2>
</header>
<div class="card-body address">
	<div class="form-group row">
		<label class="col-lg-3 control-label text-lg-right pt-2" for="addressline1">Addressline #1</label>
		<div class="col-lg-6">
			<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_html($addressline1) ?></label>
		</div>
	</div>
	<?php 
		if (strlen($addressline2)>0){
			?>
				<div class="form-group row">
					<label class="col-lg-3 control-label text-lg-right pt-2" for="addressline2">Addressline #2</label>
					<div class="col-lg-6">
						<label class="col-lg-9 control-label text-lg-left pt-2"><?php echo esc_html($addressline2) ?></label>
					</div>
				</div>
			<?php
		}
	?>
	<?php 
		if (strlen($addressline3)>0){
			?>
				<div class="form-group row">
					<label class="col-lg-3 control-label text-lg-right pt-2" for="addressline3">Addressline #3</label>
					<div class="col-lg-6">
						<label class="col-lg-9 control-label text-lg-left pt-2"><?php echo esc_html($addressline3) ?></label>
					</div>
				</div>			
			<?php
		}
	?>			
	<div class="form-group row">
		<label class="col-lg-3 control-label text-lg-right pt-2" for="PostalCode">Office Postal Code</label>
		<div class="col-lg-6">			
			<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_html($postal_code) ?></label>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-lg-3 control-label text-lg-right pt-2" for="City">Office City</label>
		<div class="col-lg-6">			
			<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_html($city) ?></label>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-lg-3 control-label text-lg-right pt-2" for="Country">Office Country</label>
		<div class="col-lg-6">			
			<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_html($country)  ?></label>
		</div>
	</div>		
</div>
