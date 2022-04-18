$(function () {
    $('body #pay').on('click', function () {
        var enteredAmount = parseFloat($('#enteredAmount').val());
        var customerName = $('#customerName').val();
        var modeOfPay = $('#modeOfPayment').text();
        if(customerName == ""){
            customerName = "NONE";
        }
        $("#payTable tr:last").each(function (){
            var total = $(this).find("td:last-child").text();
        if ( enteredAmount >= total && !isNaN(enteredAmount) && modeOfPay != "") {
                $.ajax({
                    type: 'POST',
                    url:'price.php',
                    data: {
                        total:total,
                        amount:enteredAmount,
                        mode:modeOfPay,
                        name:customerName
                    },
                    success: function( result ) {
                        var enteredAmount = parseFloat($('#enteredAmount').val()).toFixed(2);
                        $("#payTable tr:gt(0)").not(':last').each(function (){
                            var $tds = $(this).find("td:not(last)"),
                                id = $tds.eq(0).text(); 
                                qty = $tds.eq(1).text(); 
                                price = $tds.eq(2).text();

                        $.ajax({
                            type: 'POST',
                            url:'payment.php',
                            data: {
                                name: id,
                                qty: qty,
                                price: price
                            },
                                success: function( result ) {
                                    $(location).attr('href', 'pos.php');
                            }

                            })

                        });
                    }
                });
            }else {
                var toast = new bootstrap.Toast($("#errors-toast"));
                toast.show();

                var error = 'Insufficient Payment!';
            
                $("#error-message").empty().append(error);
            }
        });
    });
});