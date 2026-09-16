( function( $ ) {
			
			$('.elementor-control-default-c835').change(function() {

				var myValue = $(this).val();
				
				alert(myValue);
				
				$.get("",{val:myValue},function(data){
					$("body").html(data);
				});               
			 //you can try with many option eg. "$.post","$.get","$.ajax"
			 //But this function are not reload page so need to replace update data to current html
			});  

} )( jQuery );	