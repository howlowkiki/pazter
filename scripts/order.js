
$(document).ready(function(){
/*-------------------------
//	ORDER
//-------------------------*/
	
//	#BUY_NOW	ON CLICK
$("#buy_now").click(function(){
		
		$.ajax({	//	AJAX
			type: "POST",  
			url: "controller/order_controller.php",  
			data: {
				action:"add_to_order"
			},
			success: function(the_response) {								
				
				if(the_response=="-20"){
					$('#function_login').prettyPopin();					
					$('#form_status').html("請先登入。").fadeIn(300);					
				}
				
				if(the_response=="-10"){

				}
				
			}
				
		});//	AJAX
							
	
}); // end submit event
	
});