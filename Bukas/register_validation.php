<?php 
    include 'functions/users.php';
 
	if(isset($_POST['agreeCondition']) && isset($_POST['signup']))
	{
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$username = $_POST['username'];
		$pass = $_POST['password'];
		$cpass = $_POST['cpassword'];
		$email = $_POST['email'];
		$contact = $_POST['contact'];
		$bname = $_POST['bname'];
		$bassets = $_POST['bassets'];
		$baddress = $_POST['baddress'];
		$password = md5($pass);
		$cpassword = md5($cpass);

		$_SESSION['emailReg'] = $email; 
		$user_level = 1;
		$_SESSION['reg_userlevel'] = $user_level;
		date_default_timezone_set('Asia/Manila');
		$last_login = date("Y-m-d  h:i:sa");
		$_SESSION['reg_lastlogin'] = $last_login;

		$clean_bname = str_replace(str_split('!@#$%^&*()+=- '),"_",sanitize($bname));

		$_SESSION['clean_bname'] = $clean_bname;
		$_SESSION['reg_fname'] = $fname;
		$_SESSION['reg_lname'] = $lname;
		$_SESSION['reg_username'] = $username;
		$_SESSION['reg_email'] = $email;
		$_SESSION['reg_contact'] = $contact;
		$_SESSION['reg_bname'] = $bname;
		$_SESSION['reg_bassets'] = $bassets;
		$_SESSION['reg_baddress'] = $baddress;
		$_SESSION['reg_password'] = $password;
		$_SESSION['reg_cpassword'] = $cpassword;

		// one time connection only, after conditions die 
		$sname= "localhost";
		$uname= "root";
		$dbpassword = "";
		$business_name  = $bname;

		$connection = mysqli_connect($sname, $uname, $dbpassword);

		// check if username username exists if true cancel insertion
		if(username_exists($username) == true){
			header("Location: register.php?key=5");
			exit();	
		} 
		if(email_exists($email) == true){
			header("Location: register.php?key=14");
			exit();	
		}
		if($pass != $cpass){
			header("Location: register.php?key=7");
			exit();	
		}
		else
		{	
			if(!$connection){
				header("Location: register.php?key=6");
				exit();
	  			die('Could not create database:'); // error
	  			echo mysqli_error($retval);	
			}
			else
			{
				$account = $_SESSION['emailReg'];
				
				$code = rand(999999, 111111);
				$_SESSION['regCode'] = $code;

				$src_img = 'https://i.imgur.com/61RYJB7.png';

				if($result = mysqli_query($connection,"SELECT username, business_name FROM users WHERE email = '$account'")){
					while($row = mysqli_fetch_assoc($result))
					{
						$user_change = $row['username']; 
						$_SESSION['business_change'] = $row['business_name'];
					}
				}
				require "PHPmailer/PHPMailerAutoload.php";
				try 
				{
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
				    $mail->Subject = 'Your Bukas Registration Code Verification';
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
						                        <p>Hi, '.$account.'!<br>We noticed you have to verify your account! Please enter this code to finish verifying your account:</p>
						                    </div>
						                    <div class="d-flex justify-content-center">
						                        <span style="width: 140px;border-width: 1px;border-style: solid;font-size: 32px;">'.$code.'</span>
						                    </div>
						                </div>
						            </div>
						            <div class="row">
						                <div class="col" style="margin-top: 12px;">
						                    <p style="color: rgb(140,140,140);">If you did NOT initiate this register, we highly recommend you please contact our support.<br><br>© 2021 Bukas PH, All rights reserved<br></p>
						                </div>
						            </div>
						        </div>
						    </div>
						    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
						</body>
				    ';
				    $mail->send();

				    header("Location: register_confirmation.php");
				    exit();
				} 
				catch (Exception $e) {
				    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				}	
			}
		}
	}
		
	

	if(isset($_POST['codeButton']))
	{
		$codeInput = $_POST['code'];
		$code = $_SESSION['regCode'];
		$clean_bname = $_SESSION['clean_bname'];
		$fname = $_SESSION['reg_fname'];
		$lname = $_SESSION['reg_lname'];
		$username = $_SESSION['reg_username'];
		$email = $_SESSION['reg_email'];
		$contact = $_SESSION['reg_contact'];
		$bname = $_SESSION['reg_bname'];
		$bassets = $_SESSION['reg_bassets'];
		$baddress = $_SESSION['reg_baddress'];
		$password = $_SESSION['reg_password'];
		$cpassword = $_SESSION['reg_cpassword'];
		$user_level = $_SESSION['reg_userlevel'];
		$last_login = $_SESSION['reg_lastlogin'];

		$sname= "localhost";
		$uname= "root";
		$dbpassword = "";
		$business_name  = $bname;

		$connection = mysqli_connect($sname, $uname, $dbpassword);

		if($codeInput == $code)
		{
			$sql = "CREATE Database ".$clean_bname;
			$retval = mysqli_query($connection, $sql);
			if(!$retval){
	  			header("Location: register_confirmation.php?key=4");
				exit();	
	  			die('Could not create database:'); // error
	  			echo mysqli_error($retval);
			}
			// create folders 
			mkdir('assets/'.$clean_bname, 0777);
			mkdir('assets/'.$clean_bname.'/users', 0777);
			mkdir('assets/'.$clean_bname.'/products', 0777);
			mkdir('forecast_page/assets/forecast/'.$clean_bname, 0777);

			$filePath = 'assets/img/default_photo.png';
			$filePath2 = 'assets/img/default_photo.png';
			$destination = 'assets/'.$clean_bname.'/users/default_photo.png';
			$destination2 = 'assets/'.$clean_bname.'/products/default_photo.png';

			copy($filePath, $destination);
			copy($filePath2, $destination2);
			// file reader, reach each line as is and ignore if cases as comments 
			$query = '';
			$sqlScript = file('functions/bukas.sql');
			foreach ($sqlScript as $line){
				$startWith = substr(trim($line), 0 ,2);
				$endWith = substr(trim($line), -1 ,1);
				
				if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//'){
					continue;
				}
					
				$query = $query . $line;
				if ($endWith == ';'){
					mysqli_select_db($connection, $clean_bname);
					mysqli_query($connection,$query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
					$query= '';		
				}
			}
			// if success insert user in that databases else connection die
			echo '<div class="success-response sql-import-response">SQL file imported successfully</div>';
			$filename = 'default_photo.png';
			$sql = register_user($fname,$lname,$username,$password,$email,$contact,$clean_bname,$bassets,$baddress,$user_level,$last_login,$code,$filename);
			$result = mysqli_query($connection, $sql);
			// $insert_code = mysqli_query($connection,"UPDATE users SET code = '$codeInput' WHERE email = '$account'");

			mysqli_close($connection); // die connection to insert in users_db

			//after insert on business_name DB insert in users_db for user tracking then throw to Lonin page for AUTH
   			include 'functions/conn.php';

   			$filename = 'default_photo.png';
			$query = register_user($fname,$lname,$username,$password,$email,$contact,$clean_bname,$bassets,$baddress,$user_level,$last_login,$code,$filename);
			$result = mysqli_query($conn, $sql);
			// $insert_code = mysqli_query($conn,"UPDATE users SET code = '$codeInput' WHERE email = '$account'");
			
			header("Location: login.php");
			exit();
		}else{
			header("Location: register_confirmation.php?key=15");
			exit();
		}
	}
?>