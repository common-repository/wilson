<?php 
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();
	$customer = $user_functions->getUserCustomer();
	$current_role = $user_functions->getMerchStockRoleName();
	$offices = $user_functions->getUserOffices();
?>
<header class="page-header col-lg-12">
	<h2>Products</h2>

	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>
				<a href="admin.php?page=Dashboard">
					<i class="fa fa-home"></i>
				</a>
			</li>			
			<li><span>Offices</span></li>
		</ol>		
	</div>
</header>
<section role="main" class="content-body">	
<div class="plugin-content col-lg-12 nopadding nomargin card">
	<header class="card-header">
		<div class="card-actions">
		</div>				
		<h2 class="card-title">Offices</h2>
	</header>
	<div class="col-lg-12">
		<table class="table table-bordered table-striped mb-0 dataTable no-footer" id="datatable-default">
			<thead>
				<tr>
					<th>ID</th>
					<th>Office</th>
					<th>Actions</th>
				</tr>
			</thead>
			<?php
				foreach ($offices as $key => $office) {		
						$office_id = intval($office->ID);
						$custom = get_post_custom($office_id);
						$actions = "<a href='" . admin_url( 'admin.php?page=office&office_id='.$office_id ) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>"; 	
						$output = "<tr>";
						$output .= "<td>";
						$output .= $office_id;
 						$output .= "</td>";
						$output .= "<td>";
						$output .= esc_html(get_the_title($office_id));
 						$output .= "</td>";
	
						$output .= "<td class='actions'>";
						$output .= $actions;
 						$output .= "</td>"; 						 						 						
						$output .= "</tr>";
						echo $output;	
				}		
			?>		
		</table>
	</div>
</div>
	<div id="office_information_popup" class="row nopadding nomargin card">
		<header class="card-header">
			<div class="card-actions">
			</div>				
			<h2 class="office-name"></h2>
		</header>		
		<div id="close_office_information_popup">X</div>
		<div class="col-lg-4 nopadding nomargin">		
		</div>
		<div class="col-lg-8 nopadding nomargin">				
			<div class="office-content"></div>
		</div>
	</div>	
</section>