$("#search_input").on("keyup",function(){
    var search = $(this).val();

    if($("#inventory_table tr:visible").length < 1){
        $("#empty_record_tr").show();
        $("#empty_record").empty().append("No results found.");
    }else{
        $("#empty_record_tr").hide();

    }

    if(search.length > 1){
        $(".pagination").hide();
    }else{
        $(".pagination").show();
    }
})