$(function () {
    $('#checkout-button').on('click', function () {
        var tr = $("#tables").find("table").find("tr:has(td)").clone();
        $("#cashout tbody").append(tr);
    });
});

$("#close").click(function() {
    $("#tbodyid").empty();
});

$("#exit").click(function() {
    $("#tbodyid").empty();
});

$('#checkout-modal').on('hidden.bs.modal', function () {
    $(this).find('input').val('');
    $(this).find('#payment').prop('disabled', true);
    $("#tbodyid").empty();
})

document.onkeydown = function(evt) {
    evt = evt || window.event;
    var isEscape = false;
    if ("key" in evt) {
        isEscape = (evt.key === "Escape" || evt.key === "Esc");
    } else {
        isEscape = (evt.keyCode === 27);
    }
    if (isEscape) {
        $("#tbodyid").empty();
    }
};