<?php 
	include '../functions/users.php';

	if(isset($_SESSION['id']) && isset($_SESSION['business_name'])){
		$id = $_SESSION['id'];
		$business_session = $_SESSION['business_name'];

		if(isset($_POST['updateButton'])){
			$iid = $_POST['eiid'];
			$iname = $_POST['einame'];
			$istock = $_POST['eistock'];

			include '../functions/user_conn.php';
			$sql = update_stocks($iid, $iname, $istock);
			$result = mysqli_query($conn, $sql);
			mysqli_close($conn);

			header("Location: ../inventory_page/inventory.php");
		}

		if(isset($_POST['deleteButton'])){
			$iid = $_POST['diid'];
			$iname = $_POST['diname'];

			include '../functions/user_conn.php';
			$sql = delete_stock($iid, $iname);
			$result = mysqli_query($conn, $sql);
			mysqli_close($conn);

			header("Location: ../inventory_page/inventory.php");
		}
	}
	else{
		header("Location: ../functions/logout.php");
		exit();
	}
?>