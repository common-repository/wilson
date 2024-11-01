<?php 
	$functions = new Merch_Stock_Functions();
	$customer = $functions->getUserCustomer();
	$current_role = $functions->getMerchStockRoleName();

	$offices = $functions->getUserOffices();
	$months = array(
		1=>"January",
		2=>"February",
		3=>"March",
		4=>"April",
		5=>"May",
		6=>"June",
		7=>"July",
		8=>"August",
		9=>"September",
		10=>"October",
		11=>"November",
		12=>"December",
	)
?>
<header class="page-header col-lg-12">
	<h2>
		Invoices
	</h2>

	<div class="right-wrapper text-right">
	</div>
</header>
<section role="main" class="content-body">	
	<div class="row">
		<div class="col">
			<div class="tabs tabs-dark">
				<ul class="nav nav-tabs">
					<?php 
						foreach ($offices as $key => $office){
							if ($key==0){
								echo esc_html("<li class='nav-item active'>");
							}
							else{
								echo esc_html("<li class='nav-item'>");
							}
							?>	
									<a class="nav-link" href="#office<?php echo $office->ID ?>" data-toggle="tab">
										<?php echo esc_html(get_the_title( $office->ID )) ?>
									</a>
								</li>							
							<?php
						}
					?>
				</ul>
				<div class="tab-content">
					<?php 
						foreach ($offices as $key => $office){
							if ($key==0){
								echo esc_html("<div id='office".$office->ID."' class='tab-pane active'>");
							}
							else{
								echo esc_html("<div id='office".$office->ID."' class='tab-pane '>");
							}							
							?>								
									<div class="row">
										<div class="col-md-12">
											<section class="card mb-12">
												<header class="card-header">
													<div class="card-actions">
													</div>

													<h2 class="card-title">Order Invoices</h2>
												</header>
												<div class="card-body">
													Content.
												</div>
											</section>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<section class="card mb-12">
												<header class="card-header">
													<div class="card-actions">
													</div>

													<h2 class="card-title">Monthly Invoices</h2>
												</header>
												<div class="card-body">
													<?php 	
														$current_month = date('m');
														$current_year = date('Y');

														for ($i=0; $i < 24; $i++) {
															if ($current_month==1){
																$current_month=12;
																$current_year-=1;
															}
															
															echo esc_html("<a href='admin.php?page=monthly-invoice&month=".$current_month."&year=".$current_year."&office_id=".$office->ID."' class='mb-1 mt-1 mr-1 btn btn-default btn-lg btn-block'>".$months[$current_month] . " - " . $current_year."</a>");
															$current_month -= 1;
															
														}

													?>
												</div>
											</section>
										</div>
									</div>							
								</div>						
							<?php
						}
					?>
				</div>
			</div>
		</div>
	</div>
</section>