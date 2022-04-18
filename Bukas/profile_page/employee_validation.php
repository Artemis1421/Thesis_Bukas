<?php 
	include '../functions/users.php';

	if (isset($_SESSION['id']) && isset($_SESSION['username']) && isset($_SESSION['business_name'])){
		$id = $_SESSION['id'];
		$user_session = $_SESSION['username'];
		$business_session = $_SESSION['business_name'];

		if(isset($_POST['saveButton'])){
			$employee_first_name = sanitize($_POST['employee_first_name']);
			$employee_last_name = sanitize($_POST['employee_last_name']);
			$employee_username = sanitize($_POST['employee_username']);
			$employee_contact_info = sanitize($_POST['employee_contact_info']);
			$employee_email = sanitize($_POST['employee_email']);
			$employee_password = sanitize($_POST['employee_password']);
			$employee_position = sanitize($_POST['employee_position']);
			date_default_timezone_set('Asia/Manila');
			$last_login = date("Y-m-d  h:i:sa");

			if(empty($employee_position)){
				$employee_position == 0;
			}

			if(username_exists($employee_username) === false){
				// insert data into users database
				include '../functions/user_conn.php';
				$filename = 'default_photo.png';
				$sql = register_user_employee($employee_first_name, $employee_last_name, $employee_username, md5($employee_password), $employee_email, $employee_contact_info, $business_session, "", "", $employee_position, $last_login, $filename);
				$result = mysqli_query($conn, $sql);
				mysqli_close($conn);

				// insert data into users_db database
				include '../functions/conn.php';
				$filename = 'default_photo.png';
				$sql = register_user_employee($employee_first_name, $employee_last_name, $employee_username, md5($employee_password), $employee_email, $employee_contact_info, $business_session, "", "", $employee_position, $last_login, $filename);
				$result = mysqli_query($conn, $sql);
				mysqli_close($conn);

				header("Location: ../profile_page/profile.php");
			} else{
				echo '<script>alert("Username already exists")</script>';
				echo "<script>window.location= '../profile_page/profile.php';</script>";
			}
		}

		if (isset($_POST['updateButton'])) {
			$employee_first_name = sanitize($_POST['employee_first_name']);
			$employee_last_name = sanitize($_POST['employee_last_name']);
			$employee_username = sanitize($_POST['employee_username']);
			$employee_contact_info = sanitize($_POST['employee_contact_info']);
			$employee_email = sanitize($_POST['employee_email']);
			$employee_position = sanitize($_POST['employee_position']);

			$oemployee_username = sanitize($_POST['oemployee_username']);
			$oemployee_email = sanitize($_POST['oemployee_email']);

			if($employee_username == $oemployee_username && $employee_email == $oemployee_email){
				include '../functions/user_conn.php';
				$sql = update_user($employee_first_name, $employee_last_name, $employee_username, $employee_email, $employee_contact_info, $employee_position);
				$result = mysqli_query($conn, $sql);
				mysqli_close($conn);

				// update data in users_db database
				include '../functions/conn.php';
				$sql = update_user($employee_first_name, $employee_last_name, $employee_username, $employee_email, $employee_contact_info, $employee_position);
				$result = mysqli_query($conn, $sql);
				mysqli_close($conn);

				header("Location: ../profile_page/profile.php");
			}elseif(email_exists($employee_email) == true && $employee_email != $oemployee_email) {
				echo '<script>alert("Email address is already taken")</script>';
				echo "<script>window.location= '../profile_page/profile.php';</script>";
			}elseif(username_exists($employee_username) == true && $employee_username != $oemployee_username){
				echo '<script>alert("Username is already taken")</script>';
				echo "<script>window.location= '../profile_page/profile.php';</script>";
			}
			else{
				include '../functions/user_conn.php';
				$sql = update_user($employee_first_name, $employee_last_name, $employee_username, $employee_email, $employee_contact_info, $employee_position);
				$result = mysqli_query($conn, $sql);
				mysqli_close($conn);

				// update data in users_db database
				include '../functions/conn.php';
				$sql = update_user($employee_first_name, $employee_last_name, $employee_username, $employee_email, $employee_contact_info, $employee_position);
				$result = mysqli_query($conn, $sql);
				mysqli_close($conn);

				$_SESSION['username'] = $employee_username;
				header("Location: ../profile_page/profile.php");
			}
		}

		if(isset($_POST['deleteButton'])){
			$employee_username = sanitize($_POST['demployee_username']);

			//update data in users database
			include '../functions/user_conn.php';
			$sql = delete_user($employee_username);
			$result = mysqli_query($conn, $sql);
			mysqli_close($conn);

			// update data in users_db database
			include '../functions/conn.php';
			$sql = delete_user($employee_username);
			$result = mysqli_query($conn, $sql);
			mysqli_close($conn);

			header("Location: ../profile_page/profile.php");
		}
	}else{
		header("Location: ../functions/logout.php");
		exit();
	}
?>