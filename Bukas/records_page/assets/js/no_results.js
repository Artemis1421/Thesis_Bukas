$("#search_input").on("keyup",function(){
    var search = $(this).val();

    if($("#records_table tr:visible").length < 1){
        $("#empty_record_tr").show();
        $("#table_with_data tfoot #report_tr").hide();
        $("#empty_record").empty().append("No results found.");

        $(".pdf").prop("disabled",true);
        $(".csv").prop("disabled",true);
        $(".excel").prop("disabled",true);
    }else{
        $("#empty_record_tr").hide();
        $("#table_with_data tfoot #report_tr").show();

        $(".pdf").prop("disabled",false);
        $(".csv").prop("disabled",false);
        $(".excel").prop("disabled",false);
    }

    if(search.length > 1){
        $(".pagination").hide();
    }else{
        $(".pagination").show();
    }
})