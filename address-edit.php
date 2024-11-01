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
			<input type="text" value="<?php echo esc_html($addressline1) ?>" name="addressline1" class="form-control" >
		</div>
	</div>
	<div class="form-group row">
		<label class="col-lg-3 control-label text-lg-right pt-2" for="addressline2">Addressline #2</label>
		<div class="col-lg-6">
			<input type="text" value="<?php echo esc_html($addressline2) ?>" name="addressline2" class="form-control" >
		</div>
	</div>
	<div class="form-group row">
		<label class="col-lg-3 control-label text-lg-right pt-2" for="addressline3">Addressline #3</label>
		<div class="col-lg-6">
			<input type="text" value="<?php echo esc_html($addressline3) ?>" name="addressline3" class="form-control" >
		</div>
	</div>		
	<div class="form-group row">
		<label class="col-lg-3 control-label text-lg-right pt-2" for="StreetNumber">Office Street Number</label>
		<div class="col-lg-6">
			<input type="text" value="<?php echo esc_html($number)  ?>" name="number" class="form-control" >
		</div>
	</div>
	<div class="form-group row">
		<label class="col-lg-3 control-label text-lg-right pt-2" for="PostalCode">Office Postal Code</label>
		<div class="col-lg-6">
			<input type="text" value="<?php echo esc_html($postal_code)  ?>" name="postal_code" class="form-control" >
		</div>
	</div>
	<div class="form-group row">
		<label class="col-lg-3 control-label text-lg-right pt-2" for="City">Office City</label>
		<div class="col-lg-6">
			<input type="text" value="<?php echo esc_html($city) ?>" name="city" class="form-control" >
		</div>
	</div>
	<div class="form-group row">
		<label class="col-lg-3 control-label text-lg-right pt-2" for="Country">Office Country</label>
		<div class="col-lg-6">
			<input type="text" value="<?php echo esc_html($country)  ?>" name="country" class="form-control" >
		</div>
	</div>		
</div>
