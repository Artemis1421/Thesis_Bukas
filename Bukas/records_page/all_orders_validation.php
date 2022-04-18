<?php 
include '../functions/users.php';

if (isset($_SESSION['id']) && isset($_SESSION['business_name'])){
	include '../functions/user_conn.php';

	if(isset($_POST['updateButton'])){
		$id = $_POST['eoid'];
		$customer = $_POST['eocustomer'];
		$payment = $_POST['eopayment'];
		$notes = $_POST['eonotes'];
		$date = $_POST['eodate'];

		include '../functions/user_conn.php';
		$sql = update_order($id, $customer, $payment, $notes, $date);
		$result = mysqli_query($conn, $sql);
		mysqli_close($conn);

		header("Location: ../records_page/all_orders.php");
	}

	if(isset($_POST['deleteButton'])){
		$id = sanitize($_POST['doid']);

		include '../functions/user_conn.php';
		$sql = delete_order($id);

		if($result1 = mysqli_query($conn, "SELECT s.id, s.order_id, s.deleted, s.sales_qty, s.product_id, s.sales_price, p.product_qty FROM sales s LEFT JOIN products p ON s.product_id = p.id WHERE order_id = '$id' AND s.deleted != 1")){
			while($row = mysqli_fetch_array($result1)){
				$sql1 = mysqli_query($conn, "UPDATE products SET product_qty = $row[sales_qty] + $row[product_qty] WHERE id = '$row[product_id]'");
			}
		}

		$sql2 = delete_sale($id);

		$result = mysqli_query($conn, $sql);
		$result2 = mysqli_query($conn, $sql2);
		mysqli_close($conn);

		header("Location: ../records_page/all_orders.php");
	}

	if(isset($_POST['updateDButton'])){
		$id = $_POST['eodid'];
		$name = $_POST['eodname'];
		$qty = $_POST['eodqty'];
		$addqty = $_POST['addqty'];
		$subqty = $_POST['subqty'];

		$pid = $_POST['eodpid'];
		$pqty = $_POST['eodpqty'];
		$pprice = $_POST['eodpprice'];
		$sid = $_POST['eodsid'];

		$page_id = $_SESSION['page_id'];

		if($addqty && $subqty){
			echo '<script>alert("Please input only one of the fields at a time.")</script>';
			echo "<script>window.location= '../records_page/all_orders_details.php?id=$page_id';</script>";
		}elseif($addqty){
			include '../functions/user_conn.php';
			$sql = mysqli_query($conn, "UPDATE sales SET sales_qty = '$qty' + '$addqty', sales_price = ('$qty' + '$addqty') * '$pprice' WHERE id = '$sid'");
			$sql2 = mysqli_query($conn, "UPDATE products SET product_qty = '$pqty' - '$addqty' WHERE id = '$pid'");

			if($result = mysqli_query($conn, "SELECT sum(sales_price) FROM sales WHERE order_id = '$page_id' AND deleted != 1")){
				while($row = mysqli_fetch_array($result)){
					$summation = $row['sum(sales_price)'];
				}
			}

			$sql1 = mysqli_query($conn, "UPDATE orders SET total_payment = '$summation' WHERE id = '$page_id'");

			mysqli_close($conn);

			header("Location: ../records_page/all_orders_details.php?id=$page_id");
		}elseif($subqty){
			include '../functions/user_conn.php';
			$sql3 = mysqli_query($conn, "UPDATE sales SET sales_qty = '$qty' - '$subqty', sales_price = ('$qty' - '$subqty') * '$pprice' WHERE id = '$sid'");
			$sql5 = mysqli_query($conn, "UPDATE products SET product_qty = '$pqty' + '$subqty' WHERE id = '$pid'");

			if($result1 = mysqli_query($conn, "SELECT sum(sales_price) FROM sales WHERE order_id = '$page_id' AND deleted != 1")){
				while($row1 = mysqli_fetch_array($result1)){
					$summation1 = $row1['sum(sales_price)'];
				}
			}

			$sql4 = mysqli_query($conn, "UPDATE orders SET total_payment = '$summation1' WHERE id = '$page_id'");
			
			mysqli_close($conn);

			header("Location: ../records_page/all_orders_details.php?id=$page_id");
		}
		else{
			header("Location: ../records_page/all_orders_details.php?id=$page_id");
		}
	}

	if(isset($_POST['deleteDButton'])){
		$id = $_POST['doid'];
		$qty = $_POST['dodqty'];

		$pid = $_POST['dodpid'];
		$pqty = $_POST['dodpqty'];

		$page_id = $_SESSION['page_id'];

		$sql = mysqli_query($conn, "UPDATE products SET product_qty = '$pqty' + '$qty' WHERE id = '$pid'");
		$sql2 = mysqli_query($conn, delete_sale_order($id));
		if($result = mysqli_query($conn, "SELECT sum(sales_price) FROM sales WHERE order_id = '$page_id' AND deleted != 1")){
			while($row = mysqli_fetch_array($result)){
				$summation = $row['sum(sales_price)'];
			}
		}

		$sql1 = mysqli_query($conn, "UPDATE orders SET total_payment = '$summation' WHERE id = '$page_id' AND deleted != 1");
		
		header("Location: ../records_page/all_orders.php");
	}
}else{
	header("Location: ../functions/logout.php");
 	exit();
}
?>