<?php 
	include '../functions/users.php';

	$user_level = $_SESSION['user_level'];
	if (isset($_SESSION['id']) && isset($_SESSION['username']) && isset($_SESSION['business_name'])){
		$id = $_SESSION['id'];
		$user_session = $_SESSION['username'];
		$business_session = $_SESSION['business_name'];

		if (isset($_POST['changePhoto'])) {
			if ($_FILES["uploadFile"]["name"] != "") {
				$filename = $_FILES["uploadFile"]["name"];
				$tempname = $_FILES["uploadFile"]["tmp_name"];
				$folder = '../assets/'.$business_session.'/users/'.$filename;
				$folder2 = '../assets/'.$business_session.'/products/'. basename($_FILES["uploadFile"]["name"]);
                $image_file_type = pathinfo($folder2, PATHINFO_EXTENSION);

                if($image_file_type != "gif" && $image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png") {
                    if($user_level == 1){
						header("Location: ../profile_page/profile.php?key=16");
					}elseif($user_level == 2){
						header("Location: ../profile_page/profile_admin.php?key=16");
					}elseif($user_level == 0 || $user_level == 3 || $user_level == 4){
						header("Location: ../profile_page/profile_employee.php?key=16");
					}
                } else{
                	if (move_uploaded_file($tempname, $folder)) {
						echo "Image uploaded successfully";
					} else{
						echo "LMAO FAIL UPLOAD!!";
					}

					include '../functions/user_conn.php';
					$query = "UPDATE users SET image = '$filename' WHERE username = '$user_session'";
					mysqli_query($conn, $query);
					mysqli_close($conn);

					include '../functions/conn.php';
					$query = "UPDATE users SET image = '$filename' WHERE id = $id";
					mysqli_query($conn, $query);
					mysqli_close($conn);

					if($user_level == 1){
						header("Location: ../profile_page/profile.php");
					}elseif($user_level == 2){
						header("Location: ../profile_page/profile_admin.php");
					}elseif($user_level == 0 || $user_level == 3 || $user_level == 4){
						header("Location: ../profile_page/profile_employee.php");
					}
                }
			} else {
				if($user_level == 1){
					header("Location: ../profile_page/profile.php");
				}elseif($user_level == 2){
					header("Location: ../profile_page/profile_admin.php");
				}elseif($user_level == 0 || $user_level == 3 || $user_level == 4){
					header("Location: ../profile_page/profile_employee.php");
				}
			}
		}

		if(isset($_POST['saveUser']) || isset($_POST['saveBusiness'])){
			$username = sanitize($_POST['username']);
			$fname = sanitize($_POST['fname']);
			$lname = sanitize($_POST['lname']);
			$password = sanitize($_POST['pass']);
			$npassword = sanitize($_POST['npass']);
			$confirmPass = sanitize($_POST['confirmPass']);
			$bname = sanitize($_POST['bname']);
			$bAdd = sanitize($_POST['bAdd']);
			$contact = sanitize($_POST['contact']);
			$email = sanitize($_POST['email']);

			// if all are empty, do nothing
			if(empty($username) && empty($password) && empty($fname) && empty($lname) && empty($npassword) && empty($confirmPass) && empty($bAdd) && empty($city) && empty($contact) && empty($estimatedAssets) && empty($country) && empty($email)){
				if($user_level == 1){
					header("Location: ../profile_page/profile.php");
				}elseif($user_level == 2){
					header("Location: ../profile_page/profile_admin.php");
				}elseif($user_level == 0 || $user_level == 3 || $user_level == 4){
					header("Location: ../profile_page/profile_employee.php");
				}
			}

			// if one of the password fields are missing, refresh
			if(empty($password) || empty($npassword) || empty($confirmPass)){
				if($user_level == 1){
					header("Location: ../profile_page/profile.php");
				}elseif($user_level == 2){
					header("Location: ../profile_page/profile_admin.php");
				}elseif($user_level == 0 || $user_level == 3 || $user_level == 4){
					header("Location: ../profile_page/profile_employee.php");
				}
			}

			if(!empty($password) || !empty($npassword) || !empty($confirmPass)){
				if($user_level == 1){
					header("Location: ../profile_page/profile.php?key=7");
				}elseif($user_level == 2){
					header("Location: ../profile_page/profile_admin.php?key=7");
				}elseif($user_level == 0 || $user_level == 3 || $user_level == 4){
					header("Location: ../profile_page/profile_employee.php?key=7");
				}
			}

			// change password, must require all password fields
			if(!empty($password) && !empty($npassword) && !empty($confirmPass)){
				$pass = md5($password);
				include '../functions/conn.php';
				$query = mysqli_query($conn, "SELECT `password` FROM users WHERE id = $id AND password = '$pass'");
				if (mysqli_num_rows($query) === 1) {
					$row = mysqli_fetch_array($query);
					if($pass == $row['password'] && $npassword == $confirmPass){
						$confPass = md5($confirmPass);
						$query1 = mysqli_query($conn, "UPDATE users SET password = '$confPass' WHERE id = $id");
						header("Location: ../functions/logout.php");
					} 
				}else{
					if($user_level == 1){
						header("Location: ../profile_page/profile.php?key=13");
					}elseif($user_level == 2){
						header("Location: ../profile_page/profile_admin.php?key=13");
					}elseif($user_level == 0 || $user_level == 3 || $user_level == 4){
						header("Location: ../profile_page/profile_employee.php?key=13");
					}else{
						header("Location: ../functions/logout.php");
					}
				}
				mysqli_close($conn);
			}

			// if username is not empty, change username
			if($username){
				if(username_exists($username) === false){
					include '../functions/user_conn.php';
					$query = "UPDATE users SET username = '$username' WHERE username = '$user_session'";
					mysqli_query($conn, $query);
					mysqli_close($conn);

					include '../functions/conn.php';
					$query = "UPDATE users SET username = '$username' WHERE id = $id";
					mysqli_query($conn, $query);
					mysqli_close($conn);

					header("Location: ../functions/logout.php");
				} else{
					if($user_level == 1){
					header("Location: ../profile_page/profile.php?key=11");
					}elseif($user_level == 2){
						header("Location: ../profile_page/profile_admin.php?key=11");
					}elseif($user_level == 0 || $user_level == 3 || $user_level == 4){
							header("Location: ../profile_page/profile_employee.php?key=11");
					}	
				}
			}

			// if first name is not empty, change first name
			if($fname){
				include '../functions/user_conn.php';
				$query = "UPDATE users SET first_name = '$fname' WHERE username = '$user_session'";
				mysqli_query($conn, $query);
				mysqli_close($conn);

				include '../functions/conn.php';
				$query = "UPDATE users SET first_name = '$fname' WHERE id = $id";
				mysqli_query($conn, $query);
				mysqli_close($conn);

				header("Location: ../functions/logout.php");
			}

			// if last name is not empty, change last name
			if($lname){
				include '../functions/user_conn.php';
				$query = "UPDATE users SET last_name = '$lname' WHERE username = '$user_session'";
				mysqli_query($conn, $query);
				mysqli_close($conn);

				include '../functions/conn.php';
				$query = "UPDATE users SET last_name = '$lname' WHERE id = $id";
				mysqli_query($conn, $query);
				mysqli_close($conn);

				header("Location: ../functions/logout.php");
			}

			// if business address is not empty, change business address
			if($bAdd){
				include '../functions/user_conn.php';
				$query = "UPDATE users SET business_address = '$bAdd' WHERE username = '$user_session'";
				mysqli_query($conn, $query);
				mysqli_close($conn);

				include '../functions/conn.php';
				$query = "UPDATE users SET business_address = '$bAdd' WHERE id = $id";
				mysqli_query($conn, $query);
				mysqli_close($conn);

				header("Location: ../functions/logout.php");
			}

			// if contact is not empty, change contact
			if($contact){
				include '../functions/user_conn.php';
				$query = "UPDATE users SET contact_info = '$contact' WHERE username = '$user_session'";
				mysqli_query($conn, $query);
				mysqli_close($conn);

				include '../functions/conn.php';
				$query = "UPDATE users SET contact_info = '$contact' WHERE id = $id";
				mysqli_query($conn, $query);
				mysqli_close($conn);

				header("Location: ../functions/logout.php");
			}

			// if email is not empty, change email
			if($email){
				if(email_exists($email) == false){
					include '../functions/user_conn.php';
					$query = "UPDATE users SET email = '$email' WHERE username = '$user_session'";
					mysqli_query($conn, $query);
					mysqli_close($conn);

					include '../functions/conn.php';
					$query = "UPDATE users SET email = '$email' WHERE id = $id";
					mysqli_query($conn, $query);
					mysqli_close($conn);

					header("Location: ../functions/logout.php");
				}else{
					if($user_level == 1){
					header("Location: ../profile_page/profile.php?key=14");
					}elseif($user_level == 2){
						header("Location: ../profile_page/profile_admin.php?key=14");
					}elseif($user_level == 0 || $user_level == 3 || $user_level == 4){
							header("Location: ../profile_page/profile_employee.php?key=14");
					}
				}
			}
		}
	}
	else
	{
         header("Location: ../functions/logout.php");
         exit();
    }
?>