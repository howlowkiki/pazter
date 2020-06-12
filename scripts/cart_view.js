$(document).ready(function(){ 
	$("#select_color a").click(function(){
		$("#select_color a").removeClass("color_current");
		$("#"+this.id).addClass("color_current");
	});
	
	$("#basketItemsWrap li:first").hide();
	
	$(".detail_item a img").click(function(){
		var productIDValSplitter 	= (this.id).split("_");
		var productIDVal 			= productIDValSplitter[1];
		var p_qty_val	=	$('#p_qty').val();
		var productColorSplitter=$("#select_color a.color_current").attr("id").split("_");
		var productColorVal 			= productColorSplitter[1];
		
		// OPEN CART PANEL
		$('#cart_panel').attr("display","block").slideDown("fast");
		$("a.cart_menu").addClass("menu-open");
		$("#notificationsLoader").show();
		
		setTimeout(function(){
			
					$.ajax({	//	AJAX
						type: "POST",  
						url: "controller/cart_controller.php",  
						data: {
							action:"add_to_basket",
							data_type:"html",
							product_id:productIDVal,
							product_color:productColorVal,
							product_qty:p_qty_val
						},			
						success: function(the_response) {					
							
							if( $("#product_id_" + productIDVal + "_" + productColorVal ).length > 0){									
									$("#product_id_" + productIDVal + "_" + productColorVal ).before(the_response).remove();
									$("#product_id_" + productIDVal + "_" + productColorVal ).hide();
									$("#product_id_" + productIDVal + "_" + productColorVal ).fadeIn(300);
				
							} else {
								
								$("#basketItemsWrap li:first").before(the_response);		
								$("#basketItemsWrap li:first").hide();
								$("#basketItemsWrap li:first").fadeIn(300);
				
							}
						}
						
					});//	AJAX
					
					//	GET SUM_COLUMN
					setTimeout(function(){
						$.ajax({	//	AJAX
							type: "POST",  
							url: "controller/cart_controller.php",  
							data: {
								action:"get_total_data"
							},			
							success: function(the_response) {					
								
							$("#basketItemsWrap li:last").before(the_response).remove();
							$("#basketItemsWrap li:last").hide();
							$("#basketItemsWrap li:last").fadeIn(300);
							}						
						});//	AJAX	
					},500);
					
				  $("#notificationsLoader").hide();
				  
				  setTimeout(function(){
				  	$('#cart_panel').fadeOut(300);
				  	$("a.cart_menu").removeClass("menu-open");
				  },4000);
		},500);

		
	});
	
	
	//	DELETE ITEM
	$("#basketItemsWrap li img").live("click", function(event) {
														
		var productIDValSplitter 	= (this.id).split("_");		
		var productIDVal 			= productIDValSplitter[1];
		var productColorVal 	= productIDValSplitter[2];
	
		//	DELETE THE ITME
		$.ajax({	//	AJAX
		type: "POST",  
		url: "controller/cart_controller.php",  
		data: {
		action:"delete_item",
		product_color:productColorVal,
		product_id:productIDVal
		},			
		success: function(the_response) {
			$("#product_id_" + productIDVal+"_"+productColorVal).fadeOut(300);
			$("#product_id_" + productIDVal+"_"+productColorVal).before(the_response).remove();
		}						
		});//	AJAX 
		
		//	GET SUM_COLUMN
		setTimeout(function(){
			$.ajax({	//	AJAX
				type: "POST",  
				url: "controller/cart_controller.php",  
				data: {
					action:"get_total_data"
				},				
			
				success: function(the_response) {						
				$("#basketItemsWrap li:last").before(the_response).remove();
				$("#basketItemsWrap li:last").hide();
				$("#basketItemsWrap li:last").fadeIn(300);
				}	
			});//	AJAX			
		},500);
		
		setTimeout(function(){
	  	$('#cart_panel').fadeOut(300);
	  	$("a.cart_menu").removeClass("menu-open");
	  },4000);

		
	});
	
	//	刪除購物清單項目
	$("table.list_item a").live("click", function(event) {
							
		var productIDValSplitter 	= (this.id).split("_");
		var productIDVal 			= productIDValSplitter[1];
		var productColorVal 	= productIDValSplitter[2];
		
	
		//	DELETE THE ITME
		$.ajax({	//	AJAX
		type: "POST",  
		url: "controller/cart_controller.php",  
		data: {
		action:"delete_item",
		product_color:productColorVal,
		product_id:productIDVal
		},			
		success: function(the_response) {
			$("#od_" + productIDVal+"_"+productColorVal).fadeOut(300);
		}						
		});//	AJAX 

		setTimeout(function(){
			$.ajax({	//	AJAX
				type: "POST",  
				url: "controller/cart_controller.php",  
				data: {
					action:"get_order_sum_data"
				},				
			
				success: function(the_response) {
					if(the_response=="0"){												
						location.reload();
					}else{
						var array_sum_data 	= (the_response).split("_");
						//array_sum_data[0] sum_item
						$("span#sum_qty").html(array_sum_data[0]).fadeIn(300);
						$("span#sum_price").html(array_sum_data[1]).fadeIn(300);
						$("span#total_price").html(array_sum_data[1]).fadeIn(300);
					}
				}	
			});//	AJAX
		},500);

		
	});
	
	
	//	購物清單數量更新
	$('table.order_list_table :input').change(function(){
			var qty_id 	= (this.id).split("_");
			var qty = $(this).val();
//			alert(qty_id[1]);
//			alert($(this).val());
			
			if(!(/^[0-9]*[1-9][0-9]*$/.test($(this).val()))){
				alert("請不是一個數字。");
				return false;
			}
			
			$.ajax({	//	AJAX
					type: "POST",  
					url: "controller/cart_controller.php",  
					data: {
						action:"add_to_basket",
						data_type:"text",
						product_id:qty_id[1],
						product_color:qty_id[2],
						product_qty:qty
					},			
					success: function(the_response) {						
						$("p#subtotal_"+qty_id[1]+"_"+qty_id[2]).hide();
						$("p#subtotal_"+qty_id[1]+"_"+qty_id[2]).html(the_response).fadeIn(300);
					}
					
				});//	AJAX
				
				//	GET SUM_COLUMN
    		setTimeout(function(){
    			$.ajax({	//	AJAX
    				type: "POST",  
    				url: "controller/cart_controller.php",  
    				data: {
    					action:"get_order_sum_data"
    				},				
			
    				success: function(the_response) {
    					if(the_response=="0"){
    						$("#sum_column").fadeOut(300);
    						$("#order_empty_msg").fadeIn(300);						
    					}else{
    						var array_sum_data 	= (the_response).split("_");
    						//array_sum_data[0] sum_item
								$("span#sum_qty").html(array_sum_data[0]).fadeIn(300);
    						$("span#sum_price").html(array_sum_data[1]).fadeIn(300);
    						$("span#total_price").html(array_sum_data[1]).fadeIn(300);
    					}
    				}	
    			});//	AJAX
    		},500);
			
	});
	
	//	清空購物車
	$("#empty_basket").click(function(){
		
		$("#loading_mini").show();
		
   		setTimeout(function(){
   			$.ajax({	//	AJAX
   				type: "POST",  
   				url: "controller/cart_controller.php",  
   				data: {
   					action:"empty_basket"
   				},				
					success: function(the_response) {
   					if(the_response=="30"){   						
   						location.reload();
   					}
   				}	
   			});//	AJAX
   		},800);    	
	});
	


});
