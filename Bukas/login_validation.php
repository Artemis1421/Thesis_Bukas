<?php 
	include 'functions/users.php';
	include 'functions/conn.php';

	if(isset($_POST['username']) && isset($_POST['password'])){
		if (empty($_POST) === false) {
			$username = $_POST['username'];
			$password = $_POST['password'];
		if (user_exists($username) === false) {
			header("Location: login.php?error_id=2");
	        exit();
		} else {
		$pass = md5($password);
		$sql = mysqli_query($conn,"SELECT * FROM users WHERE username = '$username' AND password = '$pass'");
		if (mysqli_num_rows($sql) === 1) {
				$row = mysqli_fetch_assoc($sql);
				if ($row['username'] === $username && $row['password'] === $pass) {
					$_SESSION['id'] = $row['id'];
					$_SESSION['username'] = $row['username'];
					$_SESSION['business_name'] = $row['business_name'];
					$_SESSION['user_level'] = $row['user_level'];

					$business_session = $_SESSION['business_name'];
					$user_level_session = $_SESSION['user_level'];
					date_default_timezone_set('Asia/Manila');
					$last_login = date("Y-m-d  h:i:sa");

					$sql1 = mysqli_query($conn, "UPDATE users SET last_login = '$last_login' WHERE username = '$username' AND business_name = '$business_session'");
					mysqli_close($conn);

					include 'functions/user_conn.php';
					$sql2 = mysqli_query($conn, "UPDATE users SET last_login = '$last_login' WHERE username = '$username' AND business_name = '$business_session'");
					mysqli_close($conn);

					if($user_level_session == 3){
						header("Location: dashboard/dashboard.php");
					} elseif ($user_level_session == 0){
						header("Location: transaction_page/pos.php");
					} else{
						header("Location: dashboard/dashboard.php");
					}
				 }
			}else{
				header("Location: login.php?key=1");
		        exit();
			}
		}
	}
	mysqli_close($conn);
	}

	if(isset($_POST['searchButton'])) {

		$account = $_POST['findAccount'];
		$_SESSION['email'] = $account;
		
		if(email_exists($account) == true){
			
			$code = rand(999999, 111111);
			$src_img = 'https://i.imgur.com/61RYJB7.png';

			$insert_code = mysqli_query($conn,"UPDATE users SET code = '$code' WHERE email = '$account'");
			if($result = mysqli_query($conn,"SELECT username, business_name FROM users WHERE email = '$account'")){
				while($row = mysqli_fetch_assoc($result)){
					$user_change = $row['username']; 
					$_SESSION['business_change'] = $row['business_name'];
				}
			}

			require "PHPmailer/PHPMailerAutoload.php";
			try {
				$mail = new PHPMailer(true);
			    $mail->isSMTP();
                $mail->Host='smtp.gmail.com';
                $mail->Port=587;
                $mail->SMTPAuth=true;
                $mail->SMTPSecure='tls';

                $mail->Username='bukas.ph.authenticate@gmail.com';
                $mail->Password='bukasbukas';

			    //Recipients
			    $mail->setFrom('bukas.ph.authenticate@gmail.com', 'BukasPH');
			    $mail->addAddress($account);

			    //Content
			    $mail->isHTML(true);
			    $mail->Subject = 'Your Bukas Forgot Password Verification';
			    $mail->Body    = '
		    		<head>
					    <meta charset="utf-8">
					    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
					    <title>Untitled</title>
					    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
					</head>

					<body style="background: rgb(224,224,224);">
					    <div class="d-flex justify-content-center">
					        <div class="container" style="text-align: center;">
					            <div class="row">
					                <div class="col-md-12" style="background: #ffffff;">
					                    <div>
					                    <img src='.$src_img.' style="width: 100px;height: 100px;">
					                        <p>Hi, '.$user_change.'!<br>We noticed you forgot your password! Please enter this code to finish resetting your password:</p>
					                    </div>
					                    <div class="d-flex justify-content-center">
					                        <span style="width: 140px;border-width: 1px;border-style: solid;font-size: 32px;">'.$code.'</span>
					                    </div>
					                </div>
					            </div>
					            <div class="row">
					                <div class="col" style="margin-top: 12px;">
					                    <p style="color: rgb(140,140,140);">If you did NOT initiate this log-in, we highly recommend you change your password. If you are unable to do these things, please contact support.<br><br>© 2021 Bukas PH, All rights reserved<br></p>
					                </div>
					            </div>
					        </div>
					    </div>
					    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
					</body>
			    ';
			    $mail->send();

			    header("Location: otp_verification.php");
			    exit();
			} catch (Exception $e) {
			    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}else{
			header("Location: forgot_password.php?key=9");
			exit();
		}
	}

	if(isset($_POST['otpButton'])){
		$otp = $_POST['otp'];
		$account = $_SESSION['email'];

		if(get_user_otp($otp, $account) == true){
			header("Location: change_password.php");
			exit();
		}else{
			header("Location: otp_verification.php?key=10");
			exit();
		}
	}

	if(isset($_POST['savePassButton'])){
		$newpass = $_POST['newpass'];
		$cnewpass = $_POST['cnewpass'];
		$account = $_SESSION['email'];
		$business = $_SESSION['business_change'];

		if($newpass == $cnewpass){
			$pass = md5($newpass);
			$query = mysqli_query($conn,"UPDATE users SET password = '$pass' WHERE email = '$account'");
			mysqli_close($conn);

			$sname= "localhost";
			$unmae= "root";
			$password = "";
			$db_name = str_replace(" ","_",sanitize($business));

			$conn = mysqli_connect($sname, $unmae, $password, $db_name);
			if (!$conn) {
				echo "Connection failed!";
			}else{
				$query = mysqli_query($conn,"UPDATE users SET password = '$pass' WHERE email = '$account'");
				mysqli_close($conn);

				header("Location: login.php?key=8");
				exit();
			}
		}else{
			header("Location: change_password.php?key=7");
		    exit();
		}
	}
?>
