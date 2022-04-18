<?php 
include '../functions/users.php';
include '../functions/user_conn.php';
date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d");
  
 $total = array($_POST['total']);
 $payedamount = $_POST['amount'];
 $payMode = $_POST['mode'];
 $name = $_POST['name'];

 foreach ($total as $total_price){
        
    $sql1 = "INSERT INTO `orders` (`id`, `customer`, `notes`, `paymethod`, `date`, `total_payment`, `payment`) VALUES (default, '$name','NONE', '$payMode', '$date',$total_price, $payedamount)";
    mysqli_query($conn, $sql1); 
    header("Location: pos.php");
    exit();
 }

 ?>
