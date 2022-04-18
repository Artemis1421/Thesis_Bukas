$("body #how-to-toggle").on("click", function () {
  var toast = new bootstrap.Toast($("#how-to-toast"));

  toast.show();
  console.log("Hello");
});