$(document).ready(function(){
    var count = $("#addButton").attr("data-count");
    console.log(count);
    if(count <= 0){
        $("#addButton").prop("disabled",true);
        $("#importButton").prop("disabled",true);
        var toast = new bootstrap.Toast($("#errors-toast"));
        toast.show();

        var error = 'There are no existing categories. Create one first by clicking the category button.';
            
        $("#error-message").empty().append(error);
    }
})
    
