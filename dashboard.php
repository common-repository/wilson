<?php 
	$user = wp_get_current_user();
	$functions = new Merch_Stock_Functions();	
	$user_functions = new Merch_Stock_User_Functions();	
	$notification_functions = new Merch_Stock_Notifications();
	$orders = json_decode($functions->getOrders(10),true);
	$notifications = $notification_functions->getNotifications(10);	
?>
<header class="page-header col-lg-12">
	<h2>
		My Dashboard
	</h2>

	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>				
				<i class="fa fa-home"></i>
			</li>
		</ol>		
	</div>
</header>
<section role="main" class="content-body">	
	<div class="row">
		<div class="col-lg-6">			
			<header class="card-header card-header-transparent">
				<div class="card-actions">
				</div>
				<h2 class="card-title">Orders</h2>
			</header>
			<div class="card-body">
				<table class="table table-responsive-md table-striped mb-0">
					<thead>
						<tr>
							<th>#</th>
							<th>Office</th>
							<th style="padding-left: 20px;">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php 	
							foreach ($orders as $key => $order){
								$tmpOrder = get_post($order["ID"]);
								$output = "";
								$output .= "<tr>";
								$output .= "<td>";
								$output .= "<a href='".esc_url(admin_url( 'admin.php?page=order&order_id='.$order["ID"] ))."'>";
								$output .= esc_html($order["ID"]);
								$output .= "</a>";
								$output .= "</td>";
								$output .= "<td>";								
								$output .= esc_attr(get_the_title(get_post_meta( $order['ID'], 'office_id', true )));
								$output .= "</td>";
								$output .= "<td>";
								$output .= $functions->getStatusBubble(get_post_meta( $order['ID'], 'status', true ));
								$output .= "</td>";
								$output .= "</tr>";
								echo $output;
							}
						?>
					</tbody>
				</table>
			</div>
			<br>	
			<header class="card-header card-header-transparent">
				<div class="card-actions">
				</div>

				<h2 class="card-title">Notifications</h2>
			</header>
			<div class="card-body">
				<table class="table table-responsive-md table-striped mb-0">
					<thead>
						<tr>
							<th>#</th>
							<th>Notification</th>
						</tr>
					</thead>
					<tbody>
						<?php 	
							foreach ($notifications as $key => $notification){
								$item_id = intval(get_post_meta( $notification->ID, 'item_id', true ));
								$output = "";
								$output .= "<tr>";
								$output .= "<td><a href='";
								$type = get_post_meta( $notification->ID, 'type', true );
								if ($type=="order"){
									$output .= esc_url(admin_url( 'admin.php?page=order&order_id='.$item_id  ));
								}
								elseif ($type=="backorder"){										
									$output .= esc_url(admin_url( 'admin.php?page=backorder&backorder_id='.$item_id));
								}
								elseif ($type=="backorder_request"){
									$output .= esc_url(admin_url( 'admin.php?page=backorder_request&backorder_request_id='. $item_id ));
								}
								elseif ($type=="product_request"){
									$output .= esc_url(admin_url( 'admin.php?page=product_request&product_request_id='.$item_id ));
								}
								elseif ($type=="product_out_of_stock"){
									$output .= esc_url(admin_url( 'admin.php?page=product&product_id='. $item_id ));							
								}								
								$output .= "'>";
								$output .= intval($notification->ID);
								$output .= "</a>";
								$output .= "</td>";
								$output .= "<td>";
								$output .= esc_html(get_post_meta( $notification->ID, 'text', true ));
								$output .= "</td>";
								$output .= "</tr>";
								echo $output;
							}
						?>
					</tbody>
				</table>
			</div>			
		</div>
		<!-- </div> -->
		<div class="col-lg-6">
			<div class="row">
				<div class="col-lg-12">
					<header class="card-header bg-primary">					
						<div class="widget-profile-info">
							<div class="profile-picture">
							</div>
							<div class="profile-info">
								<h4 class="name font-weight-semibold">
									<?php 	
										echo esc_html($user->user_firstname);
										echo " ";
										echo esc_html($user->user_lastname);								
									?>
								</h4>
									<?php 	
										echo "<b>Company:</b> " . esc_html(get_the_title( $user_functions->getUserCustomer() ));
										echo "<br/><b>My offices: </b>";
										foreach ($user_functions->getUserOffices() as $key => $office) {
											echo esc_html(get_the_title( $office->ID ));
											if ($key+1<count($user_functions->getUserOffices())){
												echo " / ";
											}
										}
									?>
							</div>
						</div>					
					</header>
				</div>
				<div class="col-lg-12 ">
					<div class="card-body button-bar">
						<button type="button" class="mb-1 mt-1 mr-1 btn btn-primary request-new-product" name="">Request New Product</button>
					</div>	
				</div>
			</div>			
		</div>
	
		<div class="col-lg-12">	
				<?php 	
					foreach ($user_functions->getUserOffices() as $key => $office) {
						$office_id = intval($office->ID);
						?>
							<div class="office-section col-lg-12">
								<div class="card-body row">
									<div class="col-lg-6 office-overview">
										<section class="card card-horizontal mb-4 nopadding">

											<div class="card-body p-4 office-info">
												<a href="<?php 	echo esc_url(admin_url( 'admin.php?page=office&office_id='.$office->ID ))  ?>">
													<h3 class="font-weight-semibold mt-3">
														<?php 	
															echo esc_html(get_the_title( $office_id ));
														?>
													</h3>
												</a>
												<p>	
													<?php 	
														echo $functions->getOfficeInfo($office_id);
													?>
												</p>
												<p>	

											</div>

											<div class='office-submitted-orders'>	
												<div>Submitted</div>
												<div class="card-header-icon card-header-submitted ml-3">
													<?php 	
														echo esc_html($functions->getNumberOfOrders($office_id )['submitted']);
													?>										
												</div>
											</div>
											<div class='office-shipped-orders'>	
												<div>Shipped</div>
												<div class="card-header-icon card-header-shipped ml-3">
													<?php 	
														echo esc_html($functions->getNumberOfOrders($office_id )['shipped']);
													?>										
												</div>									
											</div>
										</section>
									</div>
									<div class='col-lg-6'>	
										<p>	
											<?php 	
												// echo $functions->getOrderChart($office->ID);
											?>
										</p>
									</div>
								</div>
							</div>
						<?php
					}
				?>	
			</div>
	</div>					
</section>

