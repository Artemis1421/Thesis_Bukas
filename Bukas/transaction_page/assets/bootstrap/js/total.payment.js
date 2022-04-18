$("body").on("click","#addRow",function(ev){
        var sum = 0;
        $(".price").each(function() {
                var value = $(this).text();
                if(!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
                $('#total').html(sum.toFixed(2));
        }
        })
})