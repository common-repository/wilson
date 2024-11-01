/* Add here all your JS customizations */
// jQuery.fn.dataTable.Buttons.swfPath = '../flashExport.swf';
jQuery(document).ready(function($){
	console.log("custom.js loaded!");
	$(".filter-item").on("change",this,function(){
		console.log($(this).val());
		$type = $(this).attr('data-filterkey');		
		location.href += '&'+$type+'='+$(this).val();
		console.log(location.href);
	})
	// END MODAL STUFF
	$(".create_backorder").on("click",this,function(){
		console.log("create_backorder");
		
		$arr = JSON.parse($(this).attr("data-pricelines"));
		console.log($arr);
		$product_id = $(this).attr("data-product_id");
		$stockline_id = $(this).attr("data-stockline_id");
		$backorder_request_id = $(this).attr("data-backorder_request_id");
		$("#requestBackorder").find(".product_id").val($product_id);
		$("#requestBackorder").find(".stockline_id").val($stockline_id);
		$("#requestBackorder").find(".backorder_request_id").val($backorder_request_id);
		$("#requestBackorder").find(".product-info").html($(this).attr("data-product_name") + " " + $(this).attr("data-stockline_name"));
		
		$(".backorder-amount").empty();
		$.each($arr,function($key, $value){
			$amount = $value['custom']['amount'][0];
			$product_costs_formatted = $value['product_costs_formatted'];
			console.log($amount);
			console.log($product_costs_formatted);
			$option = $("<option></option");
			$option.html($amount + " (" + $product_costs_formatted + " per item)");
			$option.val($amount);	
			$(".backorder-amount").append($option);				
		})
		$("#open-backorder-modal").trigger("click");
	});
	$("#request_backorder").on("click", this, function(){
		$stockline_id = $(this).attr('data-stockline_id');
		$product_id = $(this).attr('data-product_id');
		var data = {
			'action': 'ajax_request_backorder',
			'order_id': parseInt($("#current_order_id").val()),
			'product_id' : $(this).attr('data-product_id'),
			'stockline_id' : $(this).attr('data-stockline_id'),
			'amount' : $(this).attr('data-amount'),
			'missing' : $(this).attr('data-missing'),
		};		
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			console.log(as['new_production_costs']);
			$(".product-price-"+$product_id).html(as['new_production_costs']);
			$(".add-product-to-order-from-product-"+$product_id).trigger("click");
		})
	});
	$(".request_backorder_manager").on("click", this, function(){
		$product_id = parseInt($(this).attr('data-product_id'));
		$amount = parseInt($(this).attr('data-amount'));
		$available = parseInt($(this).attr('data-available'));
		$product_section = $("#product_section_"+$product_id);
		$stockline_id = $product_section.find('.add-product-stockline').val();
		
		console.log($product_id);
		var data = {
			'action': 'request_backorder_manager',
			'product_id' : $product_id,
			'stockline_id' : $stockline_id,
			'amount' : $amount,
			'available' : $available,
		};		
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			showSuccedModal('Request created succesfully!','Your request has been sent, you will receive an e-mail after it has been reviewed', false, "","OK",false, false);	
		})
	});	
	$(".add-product-stockline").on("change",this,function(){
		$stockline_id = $(this).val();
		$(this).parent().find(".product-stock-line").hide();
		$(this).parent().find(".product-stock-info-"+$stockline_id).show();		
		$(this).parent().find(".create_backorder").hide();
		$(this).parent().find(".create_backorder_"+$stockline_id).show();
	});
	// $("#add-stock-line").on("click",this,function(){		
	// 	// console.log("Add stock line");
	// 	$rnd = Math.floor((Math.random() * 10000) + 1);
	// 	$cnt = $("#product-stock");
	// 	$stockline = $("<div></div>");
	// 	$label = $("<label></label>");
	// 	$br = $("</br");
	// 	$input = $("<input></input>");
	// 	$label.html("Product Stock");
	// 	$input.attr('type','number');
	// 	$input.attr('name','product_stock['+$rnd+']');
	// 	$input.attr('step','0.1');
	// 	$stockline.append($label);
	// 	$stockline.append($input);				
	// 	$stockline.append($br);
	// })
	// $("#add-price-line").on("click",this,function(){		
	// 	// console.log("Add price line");
	// 	// $rnd = Math.floor((Math.random() * 10000) + 1);
	// 	// $cnt = $("#product-prices");
	// 	// $stockline = $("<div></div>");
	// 	// $label = $("<label></label>");
	// 	// $br = $("</br"); 
	// 	// $input = $("<input></input>");
	// 	// $label.html("Product Price");
	// 	// $input.attr('type','number');
	// 	// $input.attr('name','product_price['+$rnd+']');
	// 	// $input.attr('step','0.1');
	// 	// $stockline.append($label);
	// 	// $stockline.append($input);				
	// 	// $stockline.append($br);
	// })	
	$(".show-product-stock").on("mouseenter",this,function(){
		$selectedStockline = $(this).parent().parent().find('.add-product-stockline').val();
		console.log($selectedStockline);
		// $(this).parent().parent().find(".product-stock-line").hide();
		$(this).parent().parent().find(".product-stock-info-"+$selectedStockline).find('.product-stock-line-info').show();
		// $(this).parent().parent().find('.product-stock-info').position($(this).position());
		// $(this).parent().parent().find('.product-stock-info').show();
		// console.log($(this).css('left'));
	})
	$(".show-product-stock").on("mouseleave",this,function(){
		$selectedStockline = $(this).parent().parent().find('.add-product-stockline').val();
		console.log($selectedStockline);
		// $(this).parent().parent().find(".product-stock-line").hide();
		$(this).parent().parent().find(".product-stock-info-"+$selectedStockline).find('.product-stock-line-info').hide();
	})	
	$(".dismiss-backorder-modal").on("click",this,function(){
		window.location.href="admin.php?page=products";
	})
		
	$(".order-title").on("click",this,function(){
		$(this).find('ul').toggle();
	});
	$(".offer-status").on("change",this,function(){ 
		$("#hidden-order-status").val($(this).val())
	})
	$(".selected-office").on("change",this,function(){
		$("#hidden-order-office").val($(this).val())
	})
	$('.request-new-product').on('click',this,function(){
		showRequestProductModal('I want to request a new product');
	})
	$(".tab-button").on('click',this,function(){
		$stockline_id = $(this).attr('data-stockline_id');
		$('.tab-button').removeClass('btn-primary').addClass('btn-default');
		$(this).removeClass('btn-default').addClass('btn-primary');
		$(".tab").hide();
		$("#tab_stock_"+$stockline_id).show();
		console.log("sdfs");
	})
	$(".change-to-shipped").on('click',this,function(e){
		e.preventDefault();
		$tracking_number = $("#tracking_number").val();
		if ($tracking_number.length==0){
			alert("Don't forget to enter a tracking number");
		}		
		else{
			var $input = $("<input>").attr("type", "hidden").attr("name", "order_status").val("Shipped");
			$("#order_form").append($input);
			$("#order_form").submit();			
		}
	});
	$("#request-product").on('click',this,function(){
		console.log($("#customer_id").val());
		// $customer_id = $("#customer_id").val();
		// console.log($customer_id);
		$modal = $("#emailForm");
		var data = {
			'action': 'ajax_request_product',
			'message': $modal.find('#message').val(),
			'customer_id' : $("#customer_id").val(),
		};		
		jQuery.post(ajaxurl, data, function(response) {		
			console.log("product requested!");
			showSuccedModal('Product request created succesfully!','Your request has been sent, you will receive an e-mail after it has been reviewed', false, "","OK",false, false);	
		})
	})
	$(document).on("mouseenter",".stock",function(e) {		
		$(this).parent().find(".stock-info").show();
	});
	$(document).on("mouseleave",".stock",function(e) {
		$(this).parent().find(".stock-info").hide();
	});	
	$(document).on("click",".change-order",function(e) {		
		$(this).parent().parent().find(".visible-input").hide();
		$(this).parent().parent().find(".hidden-input").show();
	})
	// Init Datatable
	$('#datatable-default').dataTable({
		"order": [[ 0, "desc" ]]
	});
	// Get product information when we click view in datatable
	$(".view-product").on("click",this,function() {
		$product_id = $(this).attr("data-product_id");
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		var data = {
			'action': 'ajax_get_product_information',
			'product_id': $product_id
		};		
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			// console.log(as);
			$("#product_information_popup .product-name").html(as['product']['post_title']);
			$("#product_information_popup .product-price").html("€ " + as['product_custom']['product_price']);
			$("#product_information_popup .product-weight").html(as['product_custom']['product_weight']);
			$("#product_information_popup .minimal-order-amount").html(as['stock_custom']['minimal_order_amount']);
			$("#product_information_popup .order-per").html(as['stock_custom']['order_per']);
			$("#product_information_popup .order-term").html(as['stock_custom']['order_term']);
			$("#product_information_popup .product-content").html(as['product']['post_content']);
			$("#current_product_id").val(as['product']['ID']);
			$stock = "Totaal: ";
			$stock += as['stock_custom']['product_stock'];
			$stock += "<br/>";
			$stock += "Approved: ";
			$stock += "<br/>";
			$stock += "<br/>";
			$("#product_information_popup .product-stock").html($stock);
			// Populate dropdown
			$order_per = parseInt(as['stock_custom']['order_per']);
			$order_minimal = parseInt(as['stock_custom']['minimal_order_amount']);
			
			for (var i = 0; i < 100; i++) {
				// console.log(i);				
				$option = $("<option></option");
				$option.html($order_minimal);
				$option.val($order_minimal);
				$order_minimal += parseInt($order_per);
				$("#add-product-amount").append($option);
			}
			$("#product_information_popup").show();
		});
	})
	// Get product information when we click view in datatable
	// $(".view-order").on("click",this,function(e) {
	$(document).on("click",".view-order",function(e) {		
		e.preventDefault(); 
		// console.log("get irder info");
		$order_id = $(this).attr("data-order_id");
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		var data = {
			'action': 'ajax_get_order_information',
			'order_id': $order_id
		};		
		jQuery.post(ajaxurl, data, function(response) {
			$("#order_information_popup .orderlines-table").find('tbody').detach();
			$("#order_information_popup .orderlines-table").append($('<tbody>'));  
			var as = JSON.parse(response);
			// // console.log(as);
			var order_weight = as["order_weight"];
			var order_price = as["order_price"];
			var order = as["order"];
			var orderlines = as["orderlines"];
			$(orderlines).each(function(key,value){
				$orderline = JSON.parse(value[0]);
				$product_title = $orderline["post_title"];
				$amount = value[1]["amount"];
				$price = value[2]["price"];
				$total = $amount*$price;
				// console.log(value);
				$tmp = $("<tr></tr>");
				$tmptd = $("<td></td>");
				$tmptd.html($orderline["ID"]);
				$tmp.append($tmptd);
				
				$tmptd = $("<td></td>");
				$tmptd.html($orderline["post_title"]);
				$tmp.append($tmptd);
				$tmptd = $("<td></td>");
				$tmptd.html($amount);
				$tmp.append($tmptd);
				$tmptd = $("<td></td>");
				$tmptd.html($price);
				$tmp.append($tmptd);	
				$tmptd = $("<td></td>");
				$tmptd.html($total);
				$tmp.append($tmptd);								
				$("#order_information_popup .orderlines-table").append($tmp);
			})
			$(".order-weight").html(order_weight);
			$(".order-price").html(order_price);
			$("#order_information_popup .order-name").html(order["post_title"] + ' #' + order["ID"]);
			$("#order_information_popup .product-content").html(as['post_content']);
			$("#order_information_popup").show();
		});
	})	
	$(".view-office").on("click",this,function() {
		$office_id = $(this).attr("data-office_id");
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		var data = {
			'action': 'ajax_get_office_information',
			'office_id': $office_id
		};		
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$("#office_information_popup .product-name").html(as['post_title']);			
			$("#office_information_popup .product-content").html(as['post_content']);
			$("#office_information_popup").show();
		});
	})	
	$("#close_product_information_popup").on("click",this,function() {
		$("#add-product-to-order-from-product-message").html("");
		$("#product_information_popup").hide();
	})
	$("#close_order_information_popup").on("click",this,function() {
		$("#order_information_popup").hide();
	})	
	$("#close_office_information_popup").on("click",this,function() {
		$("#office_information_popup").hide();
	})		
	// $("#add-product-to-order").on("click",this,function(e) {
	// 	e.preventDefault(); 
	// 	$product_id = parseInt($(".selected-product").val());
	// 	$amount = parseInt($("#add-product-amount").val());
	// 	$order_id = parseInt($("#order_id").val());		
	// 	var data = {
	// 		'action': 'ajax_add_orderline',
	// 		'product_id': $product_id,
	// 		'amount': $amount,
	// 		'order_id':$order_id
	// 	};		
	// 	jQuery.post(ajaxurl, data, function(response) {
	// 		var orderline_info = JSON.parse(response);			
	// 		createOrderline(orderline_info);	
	// 		// console.log("nu trigger popup");
	// 		$("#modal_succes_a").trigger("click");
	// 	});		
	// })
	// $("#request_backorder_from_modal").on("click",this,function(e){
	// 	e.preventDefault();
	// 	$product_id = $(this).attr("product_id");
	// 	$stockline_id = $(this).attr("stockline_id");
	// 	$amount = $(".backorder-amount").val();
	// 	console.log($product_id);
	// 	console.log($stockline_id);
	// 	console.log($amount);
	// })
	$(".add-from-backorder-modal").on("click",this,function(){
		// console.log("tesdt")
		// $product_id = $(this).parent().parent().parent().parent().find(".product_id").val();
		// console.log($product_id);
		// $amount = $(this).attr('data-amount');
		// $product_id = $(this).attr('data-product_id');
		// $stockline_id = $(this).attr('data-stockline_id');
		// $order_id = $(this).attr('data-order_id');
		// var data = {
		// 	'action': 'ajax_add_orderline',
		// 	'product_id': $product_id,
		// 	'stockline_id': $stockline_id,
		// 	'amount': $amount,			
		// 	'order_id' : $order_id
		// };			
		// jQuery.post(ajaxurl, data, function(response) {
		// });		
	});
	$(".order-available").on("click",this,function(){	
		console.log("order-available");
		$amount = $(this).attr("data-available");
		$order_id = $("#current_order_id").val();
		$product_id = $(this).attr("data-product_id");
		$stockline_id = $(this).attr("data-stockline_id");
		var data = {
			'action': 'ajax_add_orderline',
			'product_id': $product_id,
			'stockline_id': $stockline_id,
			'amount': $amount,
			'order_id':$order_id
		};	
		jQuery.post(ajaxurl, data, function(response) {
			$backorder_request_amount = parseInt(JSON.parse(response)["in_backorder_request"]);
			$backorder_amount = parseInt(JSON.parse(response)["in_backorder"]);
			$avbl=(JSON.parse(response)["new_stock"]["stock"]-JSON.parse(response)["new_stock"]["init"]);
			// $avbl = response["new_stock"]["stock"] - response["new_stock"]["init"];
			$(".product-stock-info-"+$stockline_id).find("product-stockline-available").html("Available: "+$avbl);
			$(".product-stock-info-"+$stockline_id).find("product-stockline-available").attr("data-available",$avbl);			
			$("#modalWarning").hide();
			showSuccedModal($product_title+ ' added succesfully!', "The selected product is added to your order, you can either continue shopping or proceed to the order", true, "", "Proceed to order<i class='fa fa-shopping-cart' style='color:#fff!important'></i>", true, true);	
		})		
	});	
	$(".request-backorder-from-product").on("click",this,function(){
		$amount = $(this).attr('data-amount');
		$available = $(this).attr('data-available');
		$in_backorder = $(this).attr('data-inbackorder');
		showManagerRequestModal(this, "The request for will be sent to the responsible person within your organisation for approval. You will receive an update upon their response",$amount,$available, $in_backorder);
	});
	$(".add-product-to-order-from-product-v2").on("click",this,function(e) {		
		// Click parent so the focus on the button is gone so we can play with classes on the button
		// $(this).parent().click();
		e.preventDefault(); 
		$product_id = parseInt($(this).attr('data-product_id'));
		$product_name = $(this).attr('data-product_name');
		$amount = parseInt($(this).parent().parent().find(".add-product-amount").val());
		console.log($(this).parent().parent().find(".add-product-amount"));
		console.log($amount);
		$stockline_id = parseInt($(this).parent().find(".add-product-stockline").val());
		$stockline_name = $(this).parent().find(".add-product-stockline  option:selected").text();
		$order_id = parseInt($("#current_order_id").val());				
		$add_product_message = $(this).parent().find(".add-product-message");
		// Add values to modalWarning backorder button
		$modal = $("#modalWarning");
		$backorderButton = $modal.find(".request-backorder-from-product");
		$backorderButton.attr("data-product_id",$product_id);
		$backorderButton.attr("data-product_name",$product_name);
		$backorderButton.attr("data-stockline_name",$stockline_name);
		$backorderButton.addClass("request-backorder-from-product-"+$product_id);
		$product_section = $(this).parent().parent();
		$stockline_id = $product_section.find('.add-product-stockline option:selected').val();
		$backorderButton.attr("data-stockline_id",$stockline_id);
		var data = {
			'action': 'ajax_add_orderline',
			'product_id': $product_id,
			'stockline_id': $stockline_id,
			'amount': $amount,
			'order_id':$order_id
		};	
		jQuery.post(ajaxurl, data, function(response) {
			$product_title = JSON.parse(JSON.parse(response)['product'])["post_title"];
			$missing = parseInt(JSON.parse(response)["missing"]);
			$order_per = parseInt($("#order_per_"+$product_id).val());
			$minimal_order_amount = parseInt($("#order_minimal_"+$product_id).val());
			$avbl=(JSON.parse(response)["new_stock"]["stock"]-JSON.parse(response)["new_stock"]["init"]);
			// console.log(parseInt($available));
			console.log(parseInt($avbl));
			console.log($stockline_id);
			$backorder_request_amount = parseInt(JSON.parse(response)["in_backorder_request"]);
			$backorder_amount = parseInt(JSON.parse(response)["in_backorder"]);
			$(".product-stock-info-"+$stockline_id).find(".product-stockline-available").html("Available: "+$avbl);
			$(".product-stock-info-"+$stockline_id).find(".product-stockline-available").attr("data-available",$avbl);
			// product-stock-info
			if ($missing>0){
				showWarningModal($product_title + ' ' + $stockline_name +  ' not added to your order, not enough available items', "The selected product is <b>NOT</b> added to your order, you can either continue shopping, order the available items or send in a request for more products", parseInt($avbl), parseInt($order_per), parseInt($minimal_order_amount), $product_id, $stockline_id, parseInt($amount));
				return;
			}
			if (JSON.parse(response)["error"]=="nostock"){		
				showFailedModal("Failed, not enough stock","",false,true, response, $amount);
			}
			else{			
				drawOrderDropDown(response);
				if (JSON.parse(response)['orderline_stats']=="backorder"){
					showWarningModal($product_title+ ' added succesfully, but backorder required to complete order!', true);
				}
				else{					
					showSuccedModal($product_title+ ' added succesfully!', "The selected product is added to your order, you can either continue shopping or proceed to the order", true, "", "Proceed to order<i class='fa fa-shopping-cart' style='color:#fff!important'></i>", true, true);	
				} 
			}
		});				
	})
	$(".add-product-to-order-from-product").on("click",this,function(e) {
		e.preventDefault(); 
		$product_id = parseInt($("#current_product_id").val());
		$amount = parseInt($("#add-product-amount").val());
		$order_id = parseInt($("#current_order_id").val());		
		var data = {
			'action': 'ajax_add_orderline',
			'product_id': $product_id,
			'amount': $amount,
			'order_id':$order_id
		};		
		jQuery.post(ajaxurl, data, function(response) {
			$response = JSON.parse(response);
			$backorder_request_amount = parseInt(JSON.parse(response)["in_backorder_request"]);
			$backorder_amount = parseInt(JSON.parse(response)["in_backorder"]);
			$avbl=(JSON.parse(response)["new_stock"]["stock"]-JSON.parse(response)["new_stock"]["init"]);
			// console.log($avbl)
			// $avbl = response["new_stock"]["stock"] - response["new_stock"]["init"];
			$(".product-stock-info-"+$stockline_id).find(".product-stockline-available").html("Available: "+$avbl);
			$(".product-stock-info-"+$stockline_id).find(".product-stockline-available").attr("data-available",$avbl);			
			if ($response=="nostock"){
				$("#add-product-to-order-from-product-message").html("Not enough in Stock, request for a backorder..");
			}
			else{			
				drawOrderDropDown(response);
				$("#close_product_information_popup").trigger("click");
			}
		});		
	})	
	$(document).on( "click", ".delete-orderline", function(e){
		$orderline_id = parseInt($(this).attr("data-orderline_id"));
		$row = $(this).parent().parent();
		var data = {
			'action': 'ajax_delete_orderline',
			'orderline_id': $orderline_id,
		};		
		jQuery.post(ajaxurl, data, function(response) {			
			var orderline_info = JSON.parse(response);			
			var dataTable = $("#datatable-default").DataTable();
			dataTable.row($row).remove().draw();
			$("#total-order-price").html("Totaal: " + orderline_info["order_price"]);
			$("#number-of-lines").html("<br/>Aantal lijnen: " + orderline_info["orderlines"].length);						
		});				
	})
	function createOrderline(orderline_info){	
		$orderline = JSON.parse(orderline_info["orderline"]);
		$product = JSON.parse(orderline_info["product"]);
		$orderlines = JSON.parse(orderline_info["orderlines"]);
		$amount = orderline_info["amount"];		
		$product_price = orderline_info["product_price"];
		$total_price = orderline_info["order_price"];
		$orderline_id = $orderline["ID"];
				
		var dataTable = $("#datatable-default").DataTable();
		$actions = "<a data-orderline_id='"+$orderline_id+"' href='#' class='on-default edit-row delete-orderline'>Delete</i></a>";
		dataTable.row.add( {
	        0:$orderline_id,
	        1:$product['post_title'],
	        2:$product_price,
	        3:$amount,
	        4:$actions,
	    } ).draw();
		$("#total-order-price").html("Totaal: " + orderline_info["order_price"]);
		$("#number-of-lines").html("<br/>Aantal lijnen: " + $orderlines.length);
	}
	function showSuccedModal($title, $text, $show_cart_link = false, $continue_button_class, $continue_button_text, $continue_to_order, $show_continue_shopping_button = true){
		console.log("continue_to_order: " + $continue_to_order);
		$modal = $('#modalSuccess');
		$orderLink = $("#order-number").attr("href");
		console.log($orderLink);
		$modal.find('.card-title').html($title); 
		$modal.find('.modal-text').html($text); 
		$orderLink = $(".order-number").find('a').attr('href');
		
		if ($show_cart_link){
			$modal.find('.btn-info').show();
		}
		else{
			$modal.find('.btn-info').hide();
		}
		$modal.find(".btn-success").html($continue_button_text);
		if ($show_continue_shopping_button){
			$modal.find('.btn-default').show();
		}
		else{
			$modal.find('.btn-default').hide();
		}
		if ($continue_to_order){
			console.log("continue_to_order");
			$modal.find('.btn-success').on("click",this,function(){
				console.log("BTN SUCCES CLICKED!");
				window.location.href = $("#order-number").attr("href");
			})				
		}
		else{
			$modal.find('.btn-success').off();
		}
	
		$("#open-succes-modal").trigger('click');
	}
	function showWarningModal($title, $text, $available = 0, $order_per = 0, $order_minimal = 0, $product_id, $stockline_id, $amount = 0, $inbackorder = 0){
		console.log($title);
		console.log($text);
		console.log($available);
		console.log($available);
		console.log($order_per);
		console.log($order_minimal);
		console.log($amount);
		
		// $avbl = Math.floor($available/$order_per) * $order_per;
		$tmpavbl = $(".product-stockline-available-"+$stockline_id);
		$avbl = $tmpavbl.attr('data-available');
		// console.log($avbl);
		$modal = $('#modalWarning');
		$modal.find('.card-title').html($title);
		if ($text!==null){
			$modal.find('.modal-text').html($text);	
		}
		if ($avbl>0){
			$modal.find('.order-available').show();
			$modal.find('.order-available').html('Order available (' + $avbl + ')');	
			$modal.find('.order-available').attr("data-available",$avbl);
			$modal.find('.order-available').attr("data-product_id",$product_id);
			$modal.find('.order-available').attr("data-stockline_id",$stockline_id);
			$modal.find('.request-backorder-from-product').attr("data-amount",$amount);
			$modal.find('.request-backorder-from-product').attr("data-available",$available);
		}		
		else{
			$modal.find('.request-backorder-from-product').attr("data-amount",$amount);
			$modal.find('.request-backorder-from-product').attr("data-available",$available);			
			$modal.find('.order-available').hide();
		}
		$("#open-warning-modal").trigger('click');
	}	
	function showRequestProductModal($title){
		console.log("showRequestProductModal");
		$button = $("#open-request-product-modal");
		$modal = $("#emailForm");
		$("#message").val('');
		$modal.find('#message').html('');
		$modal.find(".card-title").html($title);
		$button.trigger('click');
	}
	function showManagerRequestModal(obj, $text, $amount, $available, $inbackorder){
		console.log("showManagerRequestModal");
		$product_name = $(obj).attr("data-product_name");
		$product_id = $(obj).attr("data-product_id");
		$stockline_name = "";
		console.log($(obj).text());
		$stockline_name = $(obj).attr("data-stockline_name");
		if ($stockline_name===undefined){
			$stockline_name = $(obj).parent().parent().parent().find('.add-product-stockline option:selected').text();		
		}
		console.log("get name");
		console.log($product_name);
		console.log($product_id);
		console.log($(obj).parent());
		
		console.log($stockline_name);
		$modal = $("#requestManagerBackorder");
		console.log($modal);
		console.log($text);
		$modal.find(".product_id").val($product_id);
		$modal.find(".request_backorder_manager").attr("data-product_id",$product_id);
		$modal.find(".request_backorder_manager").attr("data-amount",$amount);
		$modal.find(".request_backorder_manager").attr("data-available",$available);
		$modal.find(".card-title").html("Request for " + $product_name + " " + $stockline_name );
		// if ($inbackorder>0){
		// 	$modal.find(".card-title").html($modal.find(".card-title").html()+ " - already " + $inbackorder + " in backorder" );
		// }
		// $modal.find(".modal-text").html("<b>already " + $inbackorder + " in backorder </b><br/>"+$text);
		
		$("#open-backorder-manager-modal").trigger("click");
	}
	function showRequestModal($missing, $product, $product_custom, $stockline, $amount, $pricelines, $stockline_custom){
		$modal = $("#requestBackorder");
		$product = JSON.parse($product);
		$stockline = JSON.parse($stockline);
		$product_custom = JSON.parse($product_custom);
		$stockline_custom = JSON.parse($stockline_custom);
		$order_id = $("#current_order_id").val();
		$btn = $("#request_backorder_from_modal");
		$btn.attr('data-product_id',$product["ID"]);
		$btn.on("click",this,function(){
			var data = {
				'action': 'ajax_request_backorder_v2',
				'product_id': $product["ID"],
				'stockline_id': $stockline["ID"],
				'amount' : $modal.find(".backorder-amount").val(),
			};		
			jQuery.post(ajaxurl, data, function(response) {
				var as = JSON.parse(response);
				var $current_stock = JSON.parse(as["current_stock"]);
				console.log($current_stock);
				$("#requestBackorder").find(".info").html("Current stock: " + parseInt($current_stock["stock"]) + "<br/>Backorder: " + parseInt($current_stock["backorder"]) + "<br/>Vrije items: " + as["free_items"] );
				if ($amount<=parseInt(as["free_items"])){
					$(".add-from-backorder-modal").attr("data-amount",$amount);
					$(".add-from-backorder-modal").attr("data-product_id",$product["ID"]);
					$(".add-from-backorder-modal").attr("data-stockline_id",$stockline["ID"]);
					$(".add-from-backorder-modal").attr("data-order_id",$order_id);
					$(".add-from-backorder-modal").show();
				}
				
			})
		});
		// Populate dropdown
		$order_per = parseInt($product_custom['order_per']);
		$order_minimal = parseInt($product_custom['minimal_order_amount']);
		// console.log($pricelines[0]['custom']);
		$modal.find(".backorder-amount").empty();
		$.each($pricelines,function(key, value){
			$priceline = value["priceline"];
			$custom = value["custom"];
			$product_costs_formatted = value["product_costs_formatted"];
			// console.log('123123123');
			$option = $("<option></option");
			$option.html($custom["amount"] + " (" + $product_costs_formatted + " per item)");
			$option.val($custom["amount"]);			
			$modal.find(".backorder-amount").append($option);
		});
		// console.log($pricelines);
		console.log($product["post_title"]);
		$modal.find(".product-info").html($product["post_title"]);
		$modal.find(".card-title").html("Missing " + $missing + " items from " + $product["post_title"] + " " + $stockline_custom["description"]);
		$("#open-backorder-modal").trigger('click');
	}
	function showFailedModal($title, $text, $show_cart_link = false, $show_request_backorder_link = true, $response, $amount){
		// console.log(JSON.parse($response));
		$product = JSON.parse(JSON.parse($response)["product"]);
		$stockline = JSON.parse(JSON.parse($response)["stockline"]);
		$missing = JSON.parse(JSON.parse($response)["missing"]);
		// console.log($product);
		$modal = $('#modalDanger');
		$modal.find('.card-title').html($title);
		$modal.find('.modal-text').html($text);		
		if ($show_cart_link){
		}
		if ($show_request_backorder_link){
			$("#request_backorder").attr("data-product_id",$product["product_title"]);
			$("#request_backorder").attr("data-product_id",$product["ID"]);
			$("#request_backorder").attr("data-stockline_id",$stockline["ID"]);
			$("#request_backorder").attr("data-amount",$amount);
			$("#request_backorder").attr("data-missing", $missing);
		}		
		$("#open-danger-modal").trigger('click');		
	}
	function drawOrderDropDown(orderinfo){	
		$totalOrderPrice = 0.00;
		$order_id = JSON.parse(orderinfo)["order_id"];
		$stockline_custom = JSON.parse(orderinfo)["stockline_custom"];
		$shipping_costs = JSON.parse(orderinfo)["orderShippingCosts"];
		// // console.log($stockline_custom);
		$orderlines = JSON.parse(orderinfo)["orderlines"];
		// console.log(orderinfo["product"]);
		$ul = $("#current_order");
		$ul.html("");
		$.each($orderlines,function(key,value){
			$orderline = JSON.parse(value[0]);
			$amount = value[1]["amount"];
			$product_price = parseFloat(value[3]["production_costs"]).toFixed(2);
			$total = ($product_price*$amount).toFixed(2);
			$totalOrderPrice += parseFloat($total);
			$product_title = $orderline["post_title"] + " " + value[4]["stockline"];
			console.log("status: " + value[5]["status"]);
			if (value[5]["status"]=="backorder"){
				$li = $("<li class='backorder-line'></li>");	
			}
			else{
				$li = $("<li></li>");
			}
			
			$divRow = $("<div></div>");
			$divRow.addClass("row");
			$div1 = $("<div></div>");
			$div2 = $("<div></div>");
			$div3 = $("<div></div>");
			$div1.addClass("col-lg-12");
			$div1.addClass("order_product_name");
			$span = $("<span></span>");
			$span.addClass("message");
			$span.html($product_title);
			$span.appendTo($div1);
			$div2.addClass("col-lg-6");
			$div2.addClass("order_product_price");
			$span = $("<span></span>");
			$span.addClass("message");
			$span.html($amount + " X € " + $product_price);
			$span.appendTo($div2);			
			$div3.addClass("col-lg-6");
			$div3.addClass("order_product_total");
			$span = $("<span></span>");
			$span.addClass("message");
			$span.addClass("bold");
			$span.html("€ " +  $total);
			$span.appendTo($div3);			
			$divRow.append($div1);
			$divRow.append($div2);
			$divRow.append($div3);
			$li.append($divRow);
			$li.appendTo($ul);	
		});
		// make shipping costs line
			$li = $("<li></li>");
			$li.addClass("shipping_costs");
			$divRow = $("<div></div>");
			$divRow.addClass("row");
			$div1 = $("<div></div>");
			$div2 = $("<div></div>");
			$div3 = $("<div></div>");
			$div1.addClass("col-lg-4");
			$div1.addClass("order_product_name");
			$span = $("<span></span>");
			$span.addClass("message");
			$span.html("Shipping costs:");
			$span.appendTo($div1);
			$div2.addClass("col-lg-4");
			$div2.addClass("order_product_price");
			$span = $("<span></span>");
			$span.addClass("message");
			$span.html("");
			$span.appendTo($div2);			
			$div3.addClass("col-lg-4");
			$div3.addClass("order_product_total");
			$span = $("<span></span>");
			$span.addClass("message");
			$span.addClass("bold");
			$span.html($shipping_costs);
			$span.appendTo($div3);	
			$divRow.append($div1);
			$divRow.append($div2);
			$divRow.append($div3);
			$li.append($divRow);
			$li.appendTo($ul);				
		// make total line
			$li = $("<li></li>");
			$li.addClass("total");
			$divRow = $("<div></div>");
			$divRow.addClass("row");
			$div1 = $("<div></div>");
			$div2 = $("<div></div>");
			$div3 = $("<div></div>");
			$div1.addClass("col-lg-4");
			$div1.addClass("order_product_name");
			$span = $("<span></span>");
			$span.addClass("message");
			$span.html("Total:");
			$span.appendTo($div1);
			$div2.addClass("col-lg-4");
			$div2.addClass("order_product_price");
			$span = $("<span></span>");
			$span.addClass("message");
			$span.html("");
			$span.appendTo($div2);			
			$div3.addClass("col-lg-4");
			$div3.addClass("order_product_total");
			$span = $("<span></span>");
			$span.addClass("message");
			$span.addClass("bold");
			$span.html("€ " +  $totalOrderPrice.toFixed(2));
			$span.appendTo($div3);			
			$divRow.append($div1);
			$divRow.append($div2);
			$divRow.append($div3);
			$li.append($divRow);
			$li.appendTo($ul);	
			$(".badge-orderlines").html($orderlines.length);
			$(".order-number").html("<a href='admin.php?page=order&order_id="+$order_id+"'>order #"+$order_id+"</a>");			
	}
})