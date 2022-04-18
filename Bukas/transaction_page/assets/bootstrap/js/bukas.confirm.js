function getTotalPrice(){
	var totalPrice = '';
	$('body table tfoot tr').each(function(){
		totalPrice = $(' #total',this).text();
		// console.log(totalPrice);
		$('#totalPrice').text(totalPrice)
	})
	var amount = parseFloat($('#enteredAmount').val()).toFixed(2);
	// console.log(totalPrice);
	if (isNaN(amount)){
		amount = "";
		$('#change').text("");
	}else{
		$('#totalPayment').text(amount);
		$('#change').text(parseFloat(amount - totalPrice).toFixed(2));
	}
	var cash = $('body input[id="btnradio-1"]:checked').val();
	var gcash = $('body input[id="btnradio-2"]:checked').val();
	var other = $('body input[id="btnradio-3"]:checked').val();
	if (cash){
		$('#modeOfPayment').text(cash);
	}
	if( gcash){
		$('#modeOfPayment').text(gcash);
	} 
	if (other){
		var otherPayment = $('#other-payment').val();
		// console.log(otherPayment);
		if (otherPayment == ""){
			var toast = new bootstrap.Toast($("#errors-toast"));
        	toast.show();

			var error = 'Other payment field must not be empty!';
        
        	$("#error-message").empty().append(error);
			   // $('#confirm-modal').modal('hide');
		}

		$('#modeOfPayment').text(otherPayment);
	}
}
	
$('#payment').on('click', function(){
	getTotalPrice();

})