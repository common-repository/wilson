<?php 
	$user = wp_get_current_user();
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$notification_functions = new Merch_Stock_Notifications();
	$current_order_id = $user_functions->getUserSessionOrder();	
	$orderlines = $functions->getOrderLines($current_order_id);	
	$notifications = $notification_functions->getNotifications();	
?>
			<header class="header">
				<input type="hidden" name="customer_id" id="customer_id" value="<?php echo esc_attr($user_functions->getUserCustomer()) ?>"/>
				<div class="logo-container">
					<a href="#" class="logo">
						<img src="<?php echo esc_url(plugin_dir_url( dirname( __FILE__ ) ) . '/wilson/public/images/merchandise_logo_small.svg'); ?>" width="75" height="35" alt="Merchandise.nl">
					</a>
					<div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
			
				<div class="header-right">
					<span class="separator"></span>
			
					<ul class="notifications">
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-bell" aria-hidden="true"></i>
								<span class="badge">
									<?php 	
										echo esc_attr(count($notifications));
									?>									
								</span>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="float-right badge badge-default"></span>
									Notifications
								</div>
			
								<div class="content">
									<ul id="notifications">
										<?php 	
											foreach ($notifications as $key => $not) {																					
												?>	
														<?php 
															$type = get_post_meta( $not->ID, 'type', true );
															if ($type=="order"){
																echo "<li class='order-notification'>";
															}
															elseif ($type=="backorder"){
																echo "<li class='backorder-notification'>";
															}
															elseif ($type=="backorder_request"){
																echo "<li class='backorder_request-notification'>";
															}	
															elseif ($type=="product_request"){
																echo "<li class='product_request-notification'>";
															}	
															elseif ($type=="product_out_of_stock"){
																echo "<li class='product_out_of_stock-notification'>";
															}													
														?>
														<a href="<?php  
															$type = get_post_meta( $not->ID, 'type', true );
															if ($type=="order"){
																echo admin_url( 'admin.php?page=order&order_id='. intval(get_post_meta( $not->ID, 'item_id', true )) );
															}
															elseif ($type=="backorder"){
																echo admin_url( 'admin.php?page=backorder&backorder_id='. intval(get_post_meta( $not->ID, 'item_id', true )) );
															}
															elseif ($type=="backorder_request"){
																echo admin_url( 'admin.php?page=backorder_request&backorder_request_id='. intval(get_post_meta( $not->ID, 'item_id', true )) );
															}	
															elseif ($type=="product_request"){
																echo admin_url( 'admin.php?page=product_request&product_request_id='. intval(get_post_meta( $not->ID, 'item_id', true )) );
															}
															elseif ($type=="product_out_of_stock"){
																echo admin_url( 'admin.php?page=products&product_id='. intval(get_post_meta( $not->ID, 'item_id', true )) );
															}																															
														?>" class="clearfix">
															<div class="image">
															<?php 
 
																$type = get_post_meta( $not->ID, 'type', true );
																if ($type=="order"){
																	echo "<span style='color:#00a1f2' class='fa fa-tag'></span>";																
																}
																elseif ($type=="backorder"){
																	echo "<span style='color:#7bcce4' class='fa fa-tag'></span>";																
																}
																elseif ($type=="backorder_request"){
																	echo "<span style='color:#f0ac4b' class='fa fa-tag'></span>";		
																}	
																elseif ($type=="product_request"){
																	echo "<span style='color:#a21de0' class='fa fa-tag'></span>";		
																}	
																elseif ($type=="product_out_of_stock"){
																	echo "<span style='color:#aeaeae' class='fa fa-tag'></span>";		
																}																																	
															?>
																
															</div>
															<span class="title"><?php echo esc_html(get_post_meta( $not->ID, 'text', true )); ?></span>
															<span class="message"><?php esc_html(get_post_meta( $not->ID, 'timestamp', true )); ?></span>
														</a>
													</li>
												<?php
											}
										?>		
									</ul>			
									<hr />			
									<div class="text-right">										
									</div>
								</div>
							</div>
						</li>
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-shopping-cart" aria-hidden="true"></i>
								<span class="badge badge-orderlines">
									<?php 	
										echo esc_html(intval(count($orderlines)));
									?>
								</span>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="float-right badge badge-default"></span>
									<a id="order-number" href="<?php echo esc_url(admin_url( 'admin.php?page=order&order_id='. $current_order_id )) ?>">
										Current Order #<?php echo esc_attr($current_order_id) ?>
									</a>
								</div>
			
								<div class="content">
									<ul id="current_order">
										<?php 	
											if (count($orderlines)==0){
												?>				
													<li>
														<div class="row">
															<div class="col-lg-12 order_product_name"><span class="message">There are no products here, start adding.</span></div>								
														</div>
													</li>
												<?php
											}
											else{
												$totalOrderPrice = 0.00;
												foreach ($orderlines as $key => $orderline) {
													$orderline_id = json_decode($orderline[0],true)["ID"];
													
													$product_title = json_decode($orderline[0],true)["post_title"];
													$amount = $orderline[1]["amount"];
													$stockline = $orderline[4]["stockline"];
													$production_costs = number_format($orderline[3]["production_costs"], 2,".",",");
													$shipping_costs = number_format($order_functions->getOrderShippingCosts($current_order_id, false, true), 2,".",",");
													$total = number_format($amount*$production_costs, 2,".",",");
													$totalOrderPrice += $total;
													?>				
														<li>
															<div class="row">
																<div class="col-lg-12 order_product_name"><span class="message"><?php echo esc_html($product_title . " " . $stockline) ?></span></div>
																<div class="col-lg-6 order_product_price"><span class="message"><?php echo esc_html($amount) ?> X € <?php echo esc_html($production_costs) ?></span></div>
																<div class="col-lg-6 order_product_total"><span class="message">€ <?php echo esc_html($total) ?></span></div>												
															</div>
														</li>
													<?php
												}
												$orderShippingCosts = $order_functions->getOrderShippingCosts($current_order_id, true, false);
												$orderShippingCosts1 = $order_functions->getOrderShippingCosts($current_order_id, false, true);
												$totalOrderPrice += $orderShippingCosts1;												
											}
										?>
										<?php 
											if (count($orderlines)>0){
												?>
													<li class="shipping_costs"><div class="row"><div class="col-lg-4 order_product_name"><span class="message">Shipping costs:</span></div><div class="col-lg-4 order_product_price"><span class="message"></span></div><div class="col-lg-4 order_product_total"><span class="message bold"><?php echo esc_html($order_functions->getOrderShippingCosts($current_order_id, true, false)); ?></span></div></div></li>											
													<li class="total"><div class="row"><div class="col-lg-4 order_product_name"><span class="message">Total:</span></div><div class="col-lg-4 order_product_price"><span class="message"></span></div><div class="col-lg-4 order_product_total"><span class="message bold">€ <?php echo esc_html(number_format($totalOrderPrice, 2,".",",")) ?></span></div></div></li>												
												<?php
											}
										?>										
									</ul>		
								</div>
							</div>
						</li>
					</ul>
			
					<span class="separator"></span>
			
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<?php
									echo "<img src='".esc_url(get_the_post_thumbnail_url($user_functions->getUserCustomer()))."' alt='Joseph Doe' class='rounded-circle' data-lock-picture='".esc_url(get_the_post_thumbnail_url($user_functions->getUserCustomer()))."' />"	
									
								?>
							</figure>
							<div class="profile-info">
								<span class="name">
									<?php 
										echo esc_html($user->user_firstname);
										echo esc_html(" ");
										echo esc_html($user->user_lastname);
									?>
								</span>
								<span class="role">
									<?php 
										echo esc_html($user_functions->getMerchStockRoleDescription());
									?>
								</span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled mb-2">
								<li>
									<a href="<?php echo esc_url(get_admin_url('profile.php')) ?>/">
										My profile
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="<?php echo esc_url(wp_logout_url()) ?>"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a>	
								</li>
							<a id="open-succes-modal" class="mb-1 mt-1 mr-1 modal-basic btn btn-success" href="#modalSuccess">Success</a>		
							<a id="open-warning-modal" class="mb-1 mt-1 mr-1 modal-basic btn btn-warning" href="#modalWarning">Warning</a>		
							<a id="open-danger-modal" class="mb-1 mt-1 mr-1 modal-basic btn btn-danger" href="#modalDanger">Danger</a>		
							<a id="open-backorder-modal" class="mb-1 mt-1 mr-1 modal-basic btn btn-warning" href="#requestBackorder">Backorder</a>	
							<a id="open-backorder-manager-modal" class="mb-1 mt-1 mr-1 modal-basic btn btn-warning" href="#requestManagerBackorder">Backorder for manager</a>		
							<a id="open-request-product-modal" class="modal-with-form btn btn-default" href="#emailForm">Request product</a>						
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
				<input type="hidden" name="current_order_id" id="current_order_id" value="<?php echo esc_attr($current_order_id) ?>">
			</header>
			<!-- end: header -->
<div id="modalSuccess" class="modal-block modal-block-success mfp-hide">
	<section class="card">
		<header class="card-header">
			<h2 class="card-title">Success!</h2>
		</header>
		<div class="card-body">
			
				<div class="modal-icon">
					<i class="fa fa-check"></i>
				</div>				
				<div >
					<h4 class="modal-title"></h4>
					<p class="modal-text"></p>
				</div>
			
		</div>
		<footer class="card-footer">
			<div class="row">
				<div class="col-md-12 text-right">					
					<button class="btn btn-default modal-dismiss exit-modal-button">Continue Shopping</button>
					<button class="btn btn-success modal-dismiss ">Proceed to order<i class="fa fa-shopping-cart" style="color:#fff!important"></i></button>
				</div>
			</div>
		</footer>
	</section>
</div>
<div id="modalWarning" class="modal-block modal-block-warning mfp-hide">
	<section class="card">
		<header class="card-header">
			<h2 class="card-title">Warning!</h2>
		</header>
		<div class="card-body">
			<!-- <div class="modal-wrapper"> -->
				<div class="modal-icon">
					<i class="fa fa-exclamation-triangle"></i>
				</div>
				<div class="modal-text">
					<p></p>
				</div>
			<!-- </div> -->
		</div>
		<footer class="card-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="button" class="modal-dismiss btn btn-default">Continue shopping</button>
					<button class="btn btn-primary request-backorder-from-product ">Request more</button>					
					<button class="btn btn-success order-available">Order available amount</button>					
				</div>
			</div>
		</footer>
	</section>
</div>
<div id="requestBackorder" class="modal-block modal-block-warning mfp-hide">
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
		<input type="hidden" name="action" value="create_backorder_headoffice">
		<input class="product_id" type="hidden" name="product_id">
		<input class="stockline_id" type="hidden" name="stockline_id">
		<input class="backorder_request_id" type="hidden" name="backorder_request_id">		
		<input class="amount" type="hidden" name="amount">
		<section class="card">
			<header class="card-header">
				<h2 class="card-title">Request more</h2>
			</header>
			<div class="card-body">
				<!-- <div class="modal-wrapper"> -->
					<div class="modal-icon">
						<i class="fa fa-exclamation-triangle"></i>
					</div>
					<div class="modal-text">
						<p class="product-info">Product</p>
						<p><select name="backorder-amount" class="backorder-amount"></select></p>					
						<p class="info"></p>
					</div>
				<!-- </div> -->
			</div>
			<footer class="card-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="modal-dismiss btn btn-default">Continue shopping</button>
						<button type="submit" class="btn btn-success" id="request_backorder_from_modal">Request more</button>	
						<!-- <button type='submit'>Press me</button> -->
					</div>
				</div>
			</footer>
		</section>
	</form>
</div>
<div id="requestManagerBackorder" class="modal-block modal-block-info mfp-hide">
	<input type="hidden" class="product_id" name="product_id">
	<section class="card">
		<header class="card-header">
			<h2 class="card-title">Request backorder</h2>
		</header>
		<div class="card-body">
			<!-- <div class="modal-wrapper"> -->
				<div class="modal-icon">
					<i class="fa fa-info-circle"></i>
				</div>
				<div class="modal-text">
				</div>
		</div>
		<footer class="card-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="modal-dismiss btn btn-default">Continue shopping</button>
					<button class="btn btn-success add-from-backorder-modal request_backorder_manager" >Request more</button>
				</div>
			</div>
		</footer>
	</section>
</div>
<div id="modalDanger" class="modal-block modal-block-danger mfp-hide">
	<section class="card">
		<header class="card-header">
			<h2 class="card-title">Danger!</h2>
		</header>
		<div class="card-body">
			<div class="modal-wrapper">
				<div class="modal-icon">
					<i class="fa fa-times-circle"></i>
				</div>
				<div >
					<h4 class="modal-title"></h4>
					<p class="modal-text"></p>
				</div>
			</div>
		</div>
		<footer class="card-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="modal-dismiss btn btn-default ">Continue shopping (product is not added)</button>					
					<button id="request_backorder" class="btn btn-success">Request more</button>
				</div>
			</div>
		</footer>
	</section>
</div>								
<!-- Modal Form -->
<div id="emailForm" class="modal-block modal-block-primary mfp-hide">
	<section class="card">
		<header class="card-header">
			<h2 class="card-title">Registration Form</h2>
		</header>
		<div class="card-body">
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="inputdescription">Describe the product you would like to request</label>
						<textarea  class="form-control" id="message" placeholder=""></textarea>
					</div>
				</div>			
		</div>
		<footer class="card-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="modal-dismiss btn btn-default">Continue shopping</button>
					<button id="request-product" class="btn btn-success">Request product</button>
				</div>
			</div>
		</footer>
	</section>
</div>
								