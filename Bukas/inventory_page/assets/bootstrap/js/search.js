$(document).ready(function(){
	$("#search_input").on("keyup", function(){
		var value = $(this).val().toLowerCase();
		$("#inventory_table tr").filter(function(){
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
});