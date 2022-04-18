$(document).on("shown.bs.tab",function(){
    var product_count = $(".active").find("button").length;
    if(product_count === 0){
        $("#error_div").show();
        $("#error_div h3").show();
    }else{
        $("#error_div").hide();
        $("#error_div h3").hide();
    }
})