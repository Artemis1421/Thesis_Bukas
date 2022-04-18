$("body").on("click", "#addRow", function(ev) {

    var qty = 1;
    var btnval = parseFloat($(this).val()).toFixed(2);
    var btntext = $(this).text();

    // create new table row and data if this button is clicked 

    var newRow = "<tr class='rows' >" +
        "<td class = 'id'>"+btntext+"</td>" +
        "<td class = 'qty' style='text-align: center'>" + qty + "</td>" +
        "<td class='text-end price'>" + btnval + "</td>" +
        "</tr>";

    // append newRow to table with id of 'orderList'
    if(!$("#orderList tbody:contains("+btntext+")").length){
        $(newRow).appendTo("#orderList tbody");
    }

});