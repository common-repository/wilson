$select_type = "select";

function makeid(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

console.log(makeid(5));

jQuery(document).ready(function() {

	jQuery(".reset-filter").on('click',this,function(){
		window.location.href = $(this).attr('data-baseurl');
	})

	jQuery(".tab-button").on('click',this,function(){
		console.log("tab-button clicked!!!!");

		$stockline_id = $(this).attr('data-stockline_id');

		$('.tab-button').removeClass('btn-primary').addClass('btn-default');

		$(this).removeClass('btn-default').addClass('btn-primary');

		$(".tab").hide();

		$("#tab_stock_"+$stockline_id).show();

	})

    var $ = jQuery;
    if ($('.set_custom_logo').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $(document).on('click', '.set_custom_logo', function(e) {
                e.preventDefault();
                var button = $(this);
                var id = button.prev();
                wp.media.editor.send.attachment = function(props, attachment) {
                    id.val(attachment.id);
                };
                wp.media.editor.open(button);
                return false;
            });
        }
    }
    if ($('.set_custom_logo_2').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $(document).on('click', '.set_custom_logo_2', function(e) {
                e.preventDefault();
                var button = $(this);
                var id = button.prev();
                wp.media.editor.send.attachment = function(props, attachment) {
                    id.val(attachment.id);
                };
                wp.media.editor.open(button);
                return false;
            });
        }
    } 
    if ($('.set_custom_logo_3').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $(document).on('click', '.set_custom_logo_3', function(e) {
            	console.log("click set_custom_logo_3");
                e.preventDefault();
                var button = $(this);
                var id = button.prev();
                wp.media.editor.send.attachment = function(props, attachment) {
                    id.val(attachment.id);
                };
                wp.media.editor.open(button);
                return false;
            });
        }
    }        
	jQuery('.dataTable').DataTable();
});

jQuery(document).ready(function($){

	// Enable enters to continue
	$("input.product_name, input.article_number, textarea.product_description").on('keyup',this,function(e){
		if (e.which==13){
			$("#step2-next-button-product").trigger("click");
		}
	})

	$("input#product_weight, input#minimal_order_amount, input#order_per, input#order_term, input#warning_amount").on('keyup',this,function(e){
		console.log("UP!");
		if (e.which==13){
			$("#step3-next-button-product").trigger("click");
		}
	})	

	$("input.description, input.stock").on('keyup',this,function(e){
		if (e.which==13){
			$("#step4-next-button-product").trigger("click");
		}
	})	

	$("input.amount, input.production-cost").on('keyup',this,function(e){
		if (e.which==13){
			$("#step5-next-button-product").trigger("click");
		}
	})		

	$(".selected_backorders").on("click",this,function(){
		// console.log("select clicked!");
		// console.log($(".selected_products"));
		$selected = new Array();
		$.each($(".selected_backorders"),function(key,value){
			console.log(key);
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-backorder_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));
	})
		
	$(".selected_products").on("click",this,function(){
		console.log("select clicked!");
		console.log($(".selected_products"));
		$selected = new Array();
		$.each($(".selected_products"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-product_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));
	})

	$(".selected_orders").on("click",this,function(){
		console.log("select clicked!");
		console.log($(".selected_orders"));
		$selected = new Array();
		$.each($(".selected_orders"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-order_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));
	})	

	$(".selected_customers").on("click",this,function(){
		$selected = new Array();
		$.each($(".selected_customers"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-customer_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));
	})	

	$(".selected_offices").on("click",this,function(){		
		console.log($(".selected_offices"));
		$selected = new Array();
		$.each($(".selected_offices"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-office_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));
	})	

	$(".selected_product_requests").on("click",this,function(){		
		console.log($(".selected_product_requests"));
		$selected = new Array();
		$.each($(".selected_product_requests"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-product_request_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));
	})	

	

	$(".filter").on("change",this,function(){
		$urlParameters = "&" + $(this).attr("data-filter_item") + "=" + $(this).val();
		console.log($urlParameters);
		window.location.href = window.location.href+$urlParameters;
	})

	$("#select_all_product_requests").on("click",this,function(){
		if ($select_type=="select"){
			$(".selected_product_requests").prop("checked",true);		
			$select_type = "deselect";
		}
		else{
			$(".selected_product_requests").prop("checked",false);		
			$select_type = "select";
		}
		
		$selected = new Array();
		$.each($(".selected_product_requests"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-product_request_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));		
	})

	$("#select_all_products").on("click",this,function(){
		if ($select_type=="select"){
			$(".selected_products").prop("checked",true);		
			$select_type = "deselect";
		}
		else{
			$(".selected_products").prop("checked",false);		
			$select_type = "select";
		}
		
		$selected = new Array();
		$.each($(".selected_products"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-product_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));		
	})

	$("#select_all_customers").on("click",this,function(){
		if ($select_type=="select"){
			$(".selected_customers").prop("checked",true);		
			$select_type = "deselect";
		}
		else{
			$(".selected_customers").prop("checked",false);		
			$select_type = "select";
		}
		
		$selected = new Array();
		$.each($(".selected_customers"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-customer_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));		
	})	

	$("#select_all_backorders").on("click",this,function(){
		console.log("SELCET ALL BACKORDERS");
		if ($select_type=="select"){
			$(".selected_backorders").prop("checked",true);		
			$select_type = "deselect";
		}
		else{
			$(".selected_backorders").prop("checked",false);		
			$select_type = "select";
		}
		
		$selected = new Array();
		$.each($(".selected_backorders"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-backorder_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));		
	})	

	$("#select_all_offices").on("click",this,function(){
		if ($select_type=="select"){
			$(".selected_offices").prop("checked",true);
			$select_type = "deselect";
		}
		else{
			$(".selected_offices").prop("checked",false);			
			$select_type = "select";
		}
		
		$selected = new Array();
		$.each($(".selected_offices"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-office_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));		
	})	

	$("#select_all_orders").on("click",this,function(){
		if ($select_type=="select"){
			$(".selected_orders").prop("checked",true);
			$select_type = "deselect";
		}
		else{
			$(".selected_orders").prop("checked",false);			
			$select_type = "select";
		}
		
		$selected = new Array();
		$.each($(".selected_orders"),function(key,value){
			$checkbox = $(value);
			if ($checkbox.prop("checked")){
				$selected.push($checkbox.attr("data-order_id"));
			}
		});
		console.log(JSON.stringify($selected));
		$(".ids").val(JSON.stringify($selected));		
	})		

	$("#generate_password").on("click",this,function(){
		$("#new_manager_password").val(makeid(4)+'-'+makeid(4)+'-'+makeid(4)+'-'+makeid(4));
	})

	$("#new_customer").on("click",this,function(){
		console.log("new customer clicked!");
		$("#new_product_overlay").show();
		$("#new_customer_container").show();
	})

	$("#new_manager").on("click",this,function(){
		console.log("new manager clicked!");
		$("#new_product_overlay").show();
		$("#new_manager_container").show();
	})

	$("#add-manager-back-button").on("click",this,function(){
		$("#new_manager_container").hide();
		$("#new_product_overlay").hide();
	})	

	$("#add-customer-back-button").on("click",this,function(){
		$("#new_customer_container").hide();
		$("#new_product_overlay").hide();
	})
	
	$("#add-manager-next-button").on("click",this,function(){
		console.log("add-manager-next-button clicked!");
		$managerFirstname = $("#new_manager_first_name").val();
		$managerLastname = $("#new_manager_last_name").val();
		$managerEmail = $("#new_manager_email").val();
		$managerPassword = $("#new_manager_password").val();
		$is_headoffice = $("#is_headoffice").prop("checked");

		$newOfficeId = $("#new_office_id").val();
		var data = {
			'action': 'ajax_add_manager',			
			'manager_first_name' : $managerFirstname,
			'manager_last_name' : $managerLastname,
			'manager_email' : $managerEmail,
			'manager_password' : $managerPassword,
			'new_office_id' : $newOfficeId,
			'is_headoffice' : $is_headoffice
		};	
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			if($result){
				$firstName = as["first_name"];
				$lastName = as["last_name"];
				$userID = as["new_user_id"];
				$option = $("<option></option>");
				$option.val($userID);
				$option.html($firstName + ' ' + $lastName);
				$option.attr("selected","selected");
				$select = $("#select_manager");
				$select.append($option);
				$("#new_manager_container").hide();
				$("#new_product_overlay").hide();
			}
		})			
	})


	$("#add-customer-next-button").on("click",this,function(){
		console.log("add-customer-next-button clicked!");
		$customerName = $("#new_customer_name").val();
		$customerDescription = $("#new_customer_description").val();
		$customerImage = $(".add_customer_image").val();
		var data = {
			'action': 'ajax_add_customer',			
			'customer_name' : $customerName,
			'customer_description' : $customerDescription,
			'customer_image' : $customerImage,
		};	
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			if($result){
				$customerName = as["post"]["post_title"];
				$customerID = as["post"]["ID"];
				$option = $("<option></option>");
				$option.val($customerID);
				$option.html($customerName);
				$option.attr("selected","selected");
				$select = $("#select_customer");
				$select.append($option);
				$("#new_customer_container").hide();
				$("#new_product_overlay").hide();
			}
		})			
	})

	
	console.log("custom_admin.js loaded!");

	function checkProductVariationNumbers($container){
		$start = 1;
		$.each($container.find(".product-variation"),function(){
			console.log($(this).find(".title"));
			$(this).find(".title").html("Product Variation #"+$start);
			$start += 1;
		})
	}

	function checkProductionCosts($container){
		$start = 1;
		$.each($container.find("div.production-cost"),function(){			
			console.log($(this).find(".title"));
			$(this).find(".title").html("Production cost #"+$start);
			$start += 1;
		})
	}	
	
	$(document).on('click','.add-variation-button',function(){
		var $uniqid = Date.now();

		console.log("add-variation-button clicked!");
		$isEditScreen = false;
		if ($(this).attr("data-is_edit_screen")==true){
			$isEditScreen=true;
		}
		$container = $("#step4").find(".product-variations");
		if ($container.length==0){
			$container = $(this).parent().parent();
		}		
		$productVariations = $("#step4").find(".product-variations");
		if ($productVariations.length==0){				
			$productVariations = $(this).parent().parent().find(".product-variations");
		}		
		$productVar = $("<div></div>");
		$productVar.addClass("product-variation");

		$h4 = $("<h4></h4>");
		$h4.addClass("title");
		$h4.html("Product Variation #?");

		$productVar.append($h4);

		$div1 = $("<div></div>");
		$div1.addClass("description-container");
		$label1 = $("<label></label>");
		$label1.html("Description");
		$input1 = $("<input></input>");
		$input1.addClass("description");
		$input1.attr("type","integer");
		$input1.attr("name","description['"+$uniqid+"']");
		$div1.append($label1);
		$div1.append($input1);
		$productVar.append($div1);

		$div2 = $("<div></div>");
		$div2.addClass("stock-container");
		$label2 = $("<label></label>");
		$label2.html("Stock");
		$input2 = $("<input></input>");
		$input2.addClass("stock");
		$input2.attr("type","integer");	
		$input2.attr("name","stock['"+$uniqid+"']");
		$div2.append($label2);
		$div2.append($input2);
		$productVar.append($div2);

		$div3 = $("<div></div>");
		$div3.addClass("delete-container");
		$button = $("<button></button>");
		$button.addClass("button").addClass("delete-product-variation");
		$button.html("<i class='fa fa-trash'></i>Delete variation");
		
		$div3.append($button);

		$productVar.append($div3);

		$productVariations.append($productVar);

		checkProductVariationNumbers($productVariations);
	})

	$(document).on("click",".add-product-cost-button",function(){
		var $uniqid = Date.now();
		console.log("add-product-cost-button clicked!");
		$container = $("#step5").find(".production-costs");
		if ($container.length==0){
			$container = $(this).parent().parent();
		}
		console.log($container);
		// $.each($container.find(".production-cost"),function(){
		// 	console.log($(this));
		// })
		$productionCosts = $("#step5").find(".production-costs");

		if ($productionCosts.length==0){				
			$productionCosts = $(this).parent().parent().find(".production-costs");
		}

		$productionCost = $("<div></div>");
		$productionCost.addClass("production-cost");

		$h4 = $("<h4></h4>");
		$h4.addClass("title");
		$h4.html("Product Cost #?");

		$productionCost.append($h4);

		$div1 = $("<div></div>");
		$div1.addClass("amount-container");
		$label1 = $("<label></label>");
		$label1.html("Amount");
		$input1 = $("<input></input>");
		$input1.addClass("amount");
		$input1.attr("name","amount['"+$uniqid+"']");
		$input1.attr("type","integer");
		$div1.append($label1);
		$div1.append($input1);
		$productionCost.append($div1);

		$div2 = $("<div></div>");
		$div2.addClass("production-cost-container");
		$label2 = $("<label></label>");
		$label2.html("Production cost");
		$input2 = $("<input></input>");
		$input2.addClass("production-cost");
		$input2.attr("type","integer");	
		$input2.attr("name","cost['"+$uniqid+"']");
		$div2.append($label2);
		$div2.append($input2);
		$productionCost.append($div2);

		$div3 = $("<div></div>");
		$div3.addClass("delete-container");
		$button = $("<button></button>");
		$button.addClass("button").addClass("delete-production-cost");
		$button.html("<i class='fa fa-trash'></i>Delete priceline");
		// $button.attr("type","button");
		// $button.val("Delete");
		
		$div3.append($button);

		$productionCost.append($div3);

		$productionCosts.append($productionCost);

		checkProductionCosts($productionCosts);

	})

	$(document).on('click','.delete-product-variation',function(){
		console.log("delete-product-variation clicked!");
		$container = $(this).parent().parent().parent();
		$(this).parent().parent().remove();
		checkProductVariationNumbers($container);
	})

	$(document).on('click','.delete-production-cost',function(){
		console.log("delete-product-variation clicked!");
		$container = $(this).parent().parent().parent();
		$(this).parent().parent().remove();
		checkProductionCosts($container);
	})	


		
	$(document).on('click','#step1-next-button-product',function(){
		$newProductId = $("#new_product_id").val();
		$customerId = $(this).parent().find('select').val();
		var data = {
			'action': 'ajax_new_product_1',			
			'customer_id' : $customerId,
			'new_product_id' : $newProductId,
		};	
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			if($result){

				$("#step1").hide();
				$("#step1-original").find(".content").html("<b>Customer: </b>" + as["customer_name"]);
				$("#step1-original").show();
				$("#new_product_id").val(as["new_product_id"]);					
				$("#step2").show();
			}
		})			
	})

	$(document).on('click','#step1-next-button-office',function(){
		$newOfficeId = $("#new_office_id").val();
		$customerId = $(this).parent().find('select').val();
		var data = {
			'action': 'ajax_new_office_1',			
			'customer_id' : $customerId,
			'new_office_id' : $newOfficeId,
		};	
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			if($result){
				$("#step1").hide();
				$("#step1-original").find(".content").html("<b>Customer: </b>" + as["customer_name"]);
				$("#step1-original").show();
				$("#new_office_id").val(as["new_office_id"]);	
				$selectManager = $("#select_manager");
				$selectManager.html("");
				$.each(JSON.parse(as["users"]),function(key,value){
					$option = $("<option></option>");
					console.log(JSON.parse(as["users"])[key]["ID"]);
					console.log(JSON.parse(as["users"])[key]["name"]);
					$option.val(JSON.parse(as["users"])[key]["ID"]);
					$option.html(JSON.parse(as["users"])[key]["name"]);
					$selectManager.append($option);
				})
				$("#step2").show();
			}
		})			
	})	

	$(document).on('click','#step2-back-button-office',function(){
		console.log("step2-back-button click!");
		$("#select_manager").removeClass("mandatory");
		$("#step2").hide();
		$("#step1-original").hide();
		$("#step1").show();
	})

	$(document).on('click','#step2-next-button-office',function(){
		$newOfficeId = $("#new_office_id").val();
		$userID = $(this).parent().find('select').val();

		if ($userID==null){
			$("#select_manager").addClass("mandatory");
			window.alert("Add a manager");
			return;
		}

		var data = {
			'action': 'ajax_new_office_2',			
			'user_id' : $userID,
			'new_office_id' : $newOfficeId,
		};	
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			if($result){
				$("#step2").hide();
				$("#step2-original").find(".content").html("<b>Manager: </b>" + as["first_name"] + ' ' + as["last_name"]);
				$("#step2-original").show();
				$("#new_office_id").val(as["new_office_id"]);	
				$("#step3").show();
			}
		})			
	})

	$(document).on('click','#step3-back-button-office',function(){
		console.log("step3-back-button click!");
		$("#step3").hide();
		$("#step2-original").hide();
		$("#step2").show();
	})	

	$(document).on('click','#step3-next-button-office',function(){
		console.log("step3-next-button click!");
		$newOfficeId = $("#new_office_id").val();
		$addressline1 = $(".addressline1").val();	
		$addressline2 = $(".addressline2").val();	
		$addressline3 = $(".addressline3").val();	
		$postal_code = $(".postal_code").val();	
		$description = $(".description").val();	
		// $telephone = $(".telephone").val();	
		// $email = $(".email").val();	
		$city = $(".city").val();	
		$region = $(".region").val();	
		$county = $(".county").val();	
		$country = $(".country").val();	
		$mail = $(".email").val();	
		$telephone = $(".telephone").val();			
		$allFilled = true;
		if ($addressline1.length==0){
			$allFilled=false;
			$(".addressline1").addClass('mandatory');
		}
		if ($postal_code.length==0){
			$allFilled=false;
			$(".postal_code").addClass('mandatory');
		}
		if ($city.length==0){
			$allFilled=false;
			$(".city").addClass('mandatory');
		}
		if ($country.length==0){
			$allFilled=false;
			$(".country").addClass('mandatory');
		}		
		if ($telephone.length==0){
			$allFilled=false;
			$(".telephone").addClass('mandatory');
		}	
		if ($mail.length==0){
			$allFilled=false;
			$(".email").addClass('mandatory');
		}
		if ($description.length==0){
			$allFilled=false;
			$(".description").addClass('mandatory');
		}									
		if (!$allFilled){
			return;
		}	
		var data = {
			'action': 'ajax_new_office_3',	
			'description' : $description,
			'new_office_id' : $newOfficeId,
			'addressline1' : $addressline1,
			'addressline2' : $addressline2,
			'addressline3' : $addressline3,
			'postal_code' : $postal_code,
			'city' : $city,
			'region' : $region,
			'county' : $county,
			'country' : $country,
			'telephone' : $telephone,
			'mail' : $mail,
		};		
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			if($result){
				$("#step3").hide();
				$("#step3-original").find(".content").html(
					"<b>Description: </b>" + as["description"] + "<br/>" +
					"<b>Adressline #1: </b>" + as["addressline1"] + "<br/>" +
					"<b>Adressline #2: </b>" + as["addressline2"] + "<br/>" +
					"<b>Adressline #3: </b>" + as["addressline3"] + "<br/>" +
					"<b>Postal code: </b>" + as["postal_code"] + "<br/>" +
					"<b>City: </b>" + as["city"] + "<br/>" +
					"<b>Region: </b>" + as["region"] + "<br/>" +
					"<b>County: </b>" + as["county"] + "<br/>" +
					"<b>Country: </b>" + as["country"] + "<br/>" +
					"<b>E-mail: </b>" + as["mail"] + "<br/>" +
					"<b>Telephone: </b>" + as["telephone"]
				);
				$("#step3-original").show();
				$("#new_office_id").val(as["new_office_id"]);	
				$("#step4").show();
			}
		})			
	})	

	$(document).on('click','#step4-back-button-office',function(){
		console.log("step3-back-button click!");
		$("#step4").hide();
		$("#step3-original").hide();
		$("#step3").show();
	})		

	$(document).on('click','#step4-next-button-office',function(){
		console.log("step4-next-button click!");
		$newOfficeId = $("#new_office_id").val();
		$shipping_box_price = $(".shipping_box_price").val();	
		$shipping_box_weight = $(".shipping_box_weight").val();
		$allFilled = true;
		if ($shipping_box_price.length==0){
			$allFilled=false;
			$(".shipping_box_price").addClass('mandatory');
		}
		if ($shipping_box_weight.length==0){
			$allFilled=false;
			$(".shipping_box_weight").addClass('mandatory');
		}									
		if (!$allFilled){
			return;
		}


		var data = {
			'action': 'ajax_new_office_4',	
			'new_office_id' : $newOfficeId,
			'shipping_box_price' : $shipping_box_price,
			'shipping_box_weight' : $shipping_box_weight
		}
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			console.log($result);
			if($result){
				console.log('YESSS');
				$("#step4").hide();
				$("#step4-original").find(".content").html(
					"<b>Shipping box price: </b>" + as["shipping_box_price"] + "<br/>" +
					"<b>Shipping box weight: </b>" + as["shipping_box_weight"] + "<br/>" 				
				)
				$("#step4-original").show();
				$("#step5").show();
			}
		})				
	})

	

	$(document).on('click','#step2-back-button-product',function(){
		console.log("step2-back-button click!");
		$("#step2").hide();
		$("#step1-original").hide();
		$("#step1").show();
	})

	$(document).on('click','#step2-next-button-product',function(){		
		console.log("step2-next-button click!");
		$newProductId = $("#new_product_id").val();	
		$productName = $(".product_name").val();	
		$articleNumber = $(".article_number").val();	
		$productDescription = $(".product_description").val();	
		$productImage = $(".add_product_image").val();
		if ($productName.length==0){
			$('#step2').find(".product_name").addClass('mandatory');
			return;
		}
		else{
			$('#step2').find(".product_name").removeClass('mandatory');
		}

		var data = {
			'action': 'ajax_new_product_2',			
			'new_product_id' : $newProductId,
			'productname' : $productName,
			'productdescription' : $productDescription,
			'articlenumber' : $articleNumber,
			'productimage' : $productImage,
		};		

		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			var $image_url = as["image_url"];
			if($result){		
				$("#step2").hide();
				$("#step2-original").find(".content").html(
					"<b>Product name</b>: " + as["product_name"] + "<br/>" +
					"<b>Article number</b>: " + as["meta"]["article_number"] + "<br/>" +
					"<b>Product description:</b> " + as["post"]["post_content"] + "<br/>" +
					"<b>Product image:</b> <img src='" + $image_url + "'/><br/>"
				);
				$("#step2-original").show();
				$("#step3").show();
			}
		})		
	});

	$(document).on('click','#step3-back-button-product',function(){
		console.log("step3-back-button click!");
		$("#step3").hide();
		$("#step3-original").hide();
		$("#step2").show();
		$("#step2-original").hide();
	})

	$(document).on('click','#step3-next-button-product',function(){
		console.log("step3-next-button click!");

		$newProductId = $("#new_product_id").val();	
		$allFilled = true;
		$productWeight = $("#product_weight").val();	
		$minimalOrderAmount = $("#minimal_order_amount").val();	
		$orderPer = $("#order_per").val();	
		$orderTerm = $("#order_term").val();	
		$warningAmount = $("#warning_amount").val();	

		$errorMessage = "";
		if ($productWeight.length==0){
			$("#product_weight").addClass('mandatory');
			$allFilled = false;
		}
		else{
			$("#product_weight").removeClass('mandatory');
		}
		if ($minimalOrderAmount.length==0){
			$("#minimal_order_amount").addClass('mandatory');
			$allFilled = false;
		}
		else{
			$("#minimal_order_amount").removeClass('mandatory');
		}
		if ($orderPer.length==0){
			$("#order_per").addClass('mandatory');
			$allFilled = false;
		}
		else{
			$("#order_per").removeClass('mandatory');		
		}
		if ($orderTerm.length==0){
			$("#order_term").addClass('mandatory');
			$allFilled = false;
		}
		else{
			$("#order_term").removeClass('mandatory');
		}
		if ($warningAmount.length==0){
			$("#warning_amount").addClass('mandatory');
			$allFilled = false;
		}	
		else{
			$("#warning_amount").removeClass('mandatory');
		}							

		if (!$allFilled){		
			return;
		}

		var data = {
			'action': 'ajax_new_product_3',			
			'new_product_id' : $newProductId,
			'productweight' : $productWeight,
			'minimalorderamount' : $minimalOrderAmount,
			'orderper' : $orderPer,
			'orderterm' : $orderTerm,
			'warningamount' : $warningAmount,
		};			
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			if($result){
				$("#step3").hide();
				$("#step3-original").find(".content").html(
					"<b>Product weigt:</b> " + as["meta"]["product_weight"] + "<br/>" +
					"<b>Minamal order amount:</b> " + as["meta"]["minimal_order_amount"] + "<br/>" +
					"<b>Order per:</b> " + as["meta"]["order_per"] + "<br/>" +
					"<b>Order term:</b> " + as["meta"]["order_term"] + "<br/>" +
					"<b>Warning amount:</b> " + as["meta"]["warn_amount"] + "<br/>"
				);
				$("#step3-original").show();
				$("#step4").show();		
			}
		})			
	})

	$(document).on('click','#step4-back-button-product',function(){
		console.log("step4-back-button click!");
		$("#step4").hide();
		$("#step4-original").hide();
		$("#step3").show();
		$("#step3-original").hide();
	})	

	$(document).on('click','#step4-next-button-product',function(){
		console.log("step3-next-button click!");
		$arr =[];
		$cnt = 1;
		$allFilled = true;
		$container = $("#step4").find(".product-variations");
		$newProductId = $("#new_product_id").val();	
		$.each($container.find(".product-variation"),function(){	
			$description =  $(this).find(".description-container").find(".description").val();
			$stock = $(this).find(".stock-container").find(".stock").val();
			if ($description==""||$stock==""){
				$allFilled = false;
			}
			$arr.push({"description":$description, "stock":$stock });			
		})
		if (!$allFilled){
			window.alert("Fill all variations before continuing!");
			return;
		}
		var data = {
			'action': 'ajax_new_product_4',			
			'new_product_id' : $newProductId,
			'variations' : JSON.stringify($arr)
		};			
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			console.log($result);
			if($result){
				$("#step4").hide();
				$html = "";
				$cnt = 1;
				$.each($arr,function(key,value){
					$html += "<b>Product variation #"+$cnt+": </b>";
					$html += $arr[key]["description"] + " - ";
					$html += $arr[key]["stock"];
					$html += "<br/>";
					$cnt += 1;
				})
				$("#step4-original").find(".content").html($html);
				$("#step4-original").show();
				$("#step5").show();		
			}			
		})		
	})	
	
	$(document).on('click','#step5-back-button-product',function(){
		console.log("step5-back-button click!");
		$("#step5").hide();
		$("#step5-original").hide();
		$("#step4").show();
		$("#step4-original").hide();
	})		

	$(document).on('click','#step5-next-button-product',function(){
		console.log("step5-next-button click!");
		$arr =[];
		$cnt = 1;
		$allFilled = true;
		$container = $("#step5").find(".production-costs");
		$newProductId = $("#new_product_id").val();	
		$.each($container.find("div.production-cost"),function(){	
			$amount =  $(this).find(".amount-container").find(".amount").val();
			$production_cost = $(this).find(".production-cost-container").find(".production-cost").val();
			if ($amount==""||$production_cost==""){
				$allFilled = false;
			}
			$arr.push({"amount":$amount, "production_cost":$production_cost });			
		})
		$first_price = $(".first_production_cost").val();
		console.log($arr.length);
		if (!$allFilled){
			window.alert("Fill all pricelines before continuing!");
			return;
		}
		var data = {
			'action': 'ajax_new_product_5',			
			'new_product_id' : $newProductId,
			'production_costs' : JSON.stringify($arr),
			'first_production_costs' : $first_price
		};			
		jQuery.post(ajaxurl, data, function(response) {
			var as = JSON.parse(response);
			$result = as["result"];
			console.log($result);
			if($result){
				$("#step5").hide();
				$html = "";
				$cnt = 1;
				$.each($arr,function(key,value){
					$html += "<b>Priceline #"+$cnt+": </b>";
					$html += $arr[key]["amount"] + " : €";
					$html += $arr[key]["production_cost"];
					$html += "<br/>";
					$cnt += 1;
				})
				$("#step5-original").find(".content").html($html);
				$("#step5-original").show();
				$("#step6").show();		
			}			
		})		
	})		

	$("#add-stock-line").on('click',this,function(){

		$rnd = Math.floor((Math.random() * 10000) + 1);

		console.log("Add stock line");

		$cnt = $("#product-stock");

		$stockline = $("<div></div>");



		$label = $("<label></label>");

		$label.html("Description");

		$br = $("</br>");

		$input = $("<input></input>");

		$input.attr('type','text');

		$input.attr('name','stock_description['+$rnd+']');

		$stockline.append($label);

		$stockline.append($input);

		$stockline.append($br);

		$cnt.append($stockline);	



		$label = $("<label></label>");

		$label.html("Product Stock");

		$br = $("</br>");

		$input = $("<input></input>");

		$input.attr('type','number');

		$input.attr('name','product_stock['+$rnd+']');

		$input.attr('step','0.1');	

		$stockline.append($label);

		$stockline.append($input);

		$stockline.append($br);

		$cnt.append($stockline);

	})



	$("#add-production_cost-line").on('click',this,function(){

		$rnd = Math.floor((Math.random() * 10000) + 1);

		console.log("Add production cost line");

		$cnt = $("#product-production_costs");

		$priceline = $("<div></div>");



		$label = $("<label></label>");

		$label.html("Amount");

		$br = $("</br>");

		$input = $("<input></input>");

		$input.attr('type','number');

		$input.attr('name','add_amount['+$rnd+']');

		$priceline.append($label);

		$priceline.append($input);

		$priceline.append($br);

		$cnt.append($priceline);	



		$label = $("<label></label>");

		$label.html("Production Cost");

		$br = $("</br>");

		$input = $("<input></input>");

		$input.attr('type','decimal');

		$input.attr('name','add_production_costs['+$rnd+']');

		$priceline.append($label);

		$priceline.append($input);

		$priceline.append($br);

		$cnt.append($priceline);

	})	



	// $(".filter").on('change',this,function(){

	// 	alert($(this).attr('data-filter_item'));

	// })



	$(".ship-order").on('click',this,function(){

		$tracking_number = $(".tracking-number-input").val();

		if ($tracking_number.length==0){

			alert("Enter a tracking number before shipping this order");

		}

		else{

			$order_id = $(this).attr('data-order_id')

			jQuery.ajax({

		    	type: "POST",

		    	url: ajaxurl,

		    	data: { action: 'ajax_ship_order' , order_id: $order_id, tracking_number: $tracking_number }

		  	}).done(function( msg ) {

		  		location.reload();

		 	});	

		}		

	})

});

