$('#edit').click(function() {
   $("#ptable tr").click(function() {
    var cells = $(this).find('td');
    var id = cells.eq(1).text(); // This isn't used?
    $.ajax({
        type: 'POST',
        url: 'edit_products.php',
        data: {id: id},
        cache: false,
        success: function(data) {
            alert("id: " + id);

        }
    });
});
});