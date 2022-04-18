$("body table tbody").on("click", "tr", function () {
  $("tr.selected")  // find any selected rows
    .not(this)  // ignore the one that was clicked
    .removeClass("selected");  // remove the selection
    $(this).toggleClass("selected");  // toggle the selection clicked row

    // if clicked remove button delete table row
    $("body").on("click","#btnDelete", function(){
        //delete selected table row
        $("tr.selected").remove();
        var sum = 0;
        $(".price").each(function() {
                var value = $(this).text();
                if(!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
        }
        });
                $('#total').html(sum.toFixed( 2 ));
    });

});