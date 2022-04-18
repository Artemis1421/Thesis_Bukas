$("body #how-to-toggle").on("click",function(){
    var toast = new bootstrap.Toast($("#how-to-toast"));

    toast.show();
})

function productTracker(){
    var product = $("body table tbody tr:last-child td:first-child").text();

    return product;
}

function clearTable(){
    $("body table tbody .rows").each(function(){
        $(this).remove();
    })
    var zero = 0;
    $("body table #total").text(zero.toFixed(2));
}

function putQuantity(selected, quantityValue){
    var currentQuantity = $("body button:contains("+selected+")").attr("data-quantity"); 

    if(currentQuantity < quantityValue){
        var toast = new bootstrap.Toast($("#insufficient-toast"));
        toast.show();
        
        $("#insufficientNameError").empty().append(selected);
        $("#currentStockError").empty().append(currentQuantity);
        $("#quantityError").empty().append(quantityValue);

    }else{
        $("body table tbody tr:contains("+selected+") .qty").text(quantityValue);
    }
}

function getTotal(){
    var sum = 0.00;
    $("body table tbody tr .price").each(function(){
        var price = parseFloat($(this).text());
        console.log("price:"+price);
        if(!isNaN(price) && price >= 0){
            sum += price;
            console.log(sum);
            $("#total").text(sum.toFixed(2));
        } 
    })
}

function updateTable(){
    $("body table tbody .rows").each(function(){
        var productName = $(".id", this).text();
        var productQuantity = ($(".qty", this).text());
        var productPrice = ($("body button:contains("+ productName +")").val());
        console.log("productquantity:"+productQuantity);
        console.log("productprice:"+productPrice);
        var countedPrice = parseFloat(productPrice*productQuantity).toFixed(2)
        console.log("countedprice:"+countedPrice);

        $(".price", this).text(countedPrice);
        getTotal();
    })
}

var quantity = "";
$(".calculator button").on("click",function(ev){
    var action = $(this).attr("data-action");
    var product = productTracker();
    
    if(action === "number"){
        if($(".rows").length > 0){
            quantity+=$(this).text();
        }
    }else if(action === "decimal"){
        if(quantity.search(".") != -1){
            quantity+=($(this).text());
        }
    }else if(action === "quantity"){
        if(quantity.trim() !== ""){
            putQuantity(product,parseFloat(quantity));
            updateTable();
            quantity="";
        }
    }else if(action === "clear"){
        clearTable();
    }
})

