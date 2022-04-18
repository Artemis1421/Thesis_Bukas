<?php 
include '../functions/users.php';
include '../functions/user_conn.php';

date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d  h:i:sa");

$name = array(trim($_POST['name'])); 
$quantity = array($_POST['qty']);
$price = array($_POST['price']);

foreach($name as $p_name){	

	foreach ($quantity as $p_qty){
			
			$quey =mysqli_query($conn, "UPDATE products SET product_qty = product_qty-'$p_qty' WHERE product_name = '$p_name'");
			$sql = mysqli_query($conn, "SELECT id, product_price FROM products WHERE product_name = '$p_name' AND deleted != 1");

		foreach ($price as $p_price) {
			$lastid = last_id();
			while ($row = mysqli_fetch_array($sql)){
				$id = $row['id'];
				$product_price = $row['product_price'];
                $query1 = "INSERT INTO `sales` (`id`, `order_id`, `product_id`, `sales_qty`, `sales_price`, `date`,`product_price`) VALUES (default, '$lastid','$id', '$p_qty','$p_price', '$date', '$product_price')";
				mysqli_query($conn, $query1);
			    header("Location: pos.php");
			    exit();
			}
		}
	}
}
?>