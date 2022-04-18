<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Register</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Advent+Pro&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/register.css">
    <link rel="icon" href="assets/img/BrandLogo.png">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand bg-light navigation-clean">
        <div class="container">
            <a class="navbar-brand" href="landing_page/bukas.html">Bukas</a>
            <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1">
            <span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav ms-auto">
                    <a class="btn btn-primary ms-auto" role="button" href="login.php" style="background: #FDB750;color: #0A2635; margin-right: 30px; border-radius: 25px; width: 90px;">Login</a>
                    <a class="navbar-text" href="register.php">Sign up</a>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-0" style="text-align: center;">
                <img src="assets/img/BrandLogo.png" style="margin-top: 200px;">
            </div>
            <div class="col-md-6 offset-md-0" style="margin-top: -5%;">
                <div style="width: 60%;">
                    <div style="width: 60%;">
                        <div style="margin-top: 248px;">
                            <form method="POST" action="register_validation.php">
                                <p style="font-size: 20px;font-weight: bold;font-family: 'Advent Pro', sans-serif;margin-top: -87px;">Sign Up</p>
                                <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;"><i class="fa fa-user" style="margin: 11px;color: rgb(123,123,123);"></i><input required oninvalid="this.setCustomValidity('Please input your first name')" oninput="setCustomValidity('')" type="text" style="padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;" placeholder="First Name" name="fname" required=""></div>
                                <div style="background: #dddddd;border-radius: 25px;margin-top: 10px;width: 360px;"><i class="fa fa-user" style="margin: 11px;color: rgb(123,123,123);"></i><input required oninvalid="this.setCustomValidity('Please input your last name')" oninput="setCustomValidity('')" type="text" style="background: #dddddd;border-radius: 25px;padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);border-style: none;border-color: rgb(224,224,224);" placeholder="Last Name" name="lname" required=""></div>
                                <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;margin-top: 10px;"><i class="fa fa-user-plus" style="margin: 11px;color: rgb(123,123,123);"></i><input required oninvalid="this.setCustomValidity('Please input your username')" oninput="setCustomValidity('')" type="text" style="padding: 2px 2px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;margin-left: -6px;" placeholder="Username" name="username" required=""></div>
                                <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;margin-top: 10px;"><i class="fa fa-lock" style="margin: 11px;color: rgb(123,123,123);"></i><input type="password" style="padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;" placeholder="Password" name="password" minlength = "8" required=""></div>
                                <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;margin-top: 10px;"><i class="fa fa-lock" style="margin: 11px;color: rgb(123,123,123);"></i><input type="password" style="padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;" placeholder="Confirm Password" name="cpassword" minlength = "8" required=""></div>
                                <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;margin-top: 10px;"><i class="fa fa-envelope-o" style="margin: 11px;color: rgb(123,123,123);"></i><input required oninvalid="this.setCustomValidity('Please input a valid email')" oninput="setCustomValidity('')" type="email" style="padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;" placeholder="Email Address" name="email" required=""></div>
                                <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;margin-top: 10px;"><i class="fa fa-phone" style="color: rgb(123,123,123);width: 16;margin: 11px;margin-left: 15px;"></i><input required oninvalid="this.setCustomValidity('Please input a valid contact info')" oninput="setCustomValidity('')" type="tel" style="padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;margin-left: -2px;" minlength="11" maxlength="11" placeholder="Contact Info" name="contact" required=""></div>
                                <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;margin-top: 10px;"><i class="fa fa-briefcase" style="margin: 11px;color: rgb(123,123,123);"></i><input required oninvalid="this.setCustomValidity('Please input your business name')" oninput="setCustomValidity('')" type="text" style="padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;" placeholder="Business Name" name="bname" required=""></div>
                                <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;margin-top: 10px;"><i class="fa fa-map" style="margin: 11px;color: rgb(123,123,123);"></i><input required oninvalid="this.setCustomValidity('Please input your business address')" oninput="setCustomValidity('')" type="text" style="padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;" placeholder="Business Address" name="baddress" required /></div>
                                <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;margin-top: 10px;"><i class="fa" style="margin: 11px;color: rgb(123,123,123);width: 12px;margin-left: 13px;">₱</i><input required oninvalid="this.setCustomValidity('The system is recommended only for businesses with total assets of PHP 500,000.00 or more')" oninput="setCustomValidity('')" type="number" style="padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;margin-left: 1px;" min="500000" placeholder="Estimated Assets" name=bassets required=""></div>
                            <p style="color:red; margin-top: 10px; margin-left: 80px; min-width: 500px">
                            <?php  
                             if (isset($_GET['key'])){
                                $error_id = $_GET['key'];
                                 include 'functions/conn.php';
                                 if ($error_id == 4){
                                    echo $errors[4];
                                }elseif($error_id == 5) {
                                    echo $errors[5];
                                }elseif($error_id == 6){
                                    echo $errors[6];
                                }elseif($error_id == 14){
                                    echo $errors[14];
                                }
                             }
                            ?></p>
                            <p style="color:red; margin-top: 10px; margin-left: 10px; min-width: 500px">
                                <?php
                                if(isset($_GET['key'])){
                                    $error_id = $_GET['key'];
                                    include'functions/conn.php';
                                    if($error_id==7){
                                        echo $errors[7];
                                    }
                                }?>
                            </p>
                                <div class="form-check" style="margin-top: 10px;margin-left: 10%;width: 130%;"><input class="form-check-input" name="agreeCondition" required="" type="checkbox" id="formCheck-1"><label class="form-check-label" for="formCheck-1" style="min-width: 130%;">I agree with the <a href="landing_page/tos.html">terms and conditions</a></label></div>
                                <div style="background: var(--bs-teal);border-radius: 25px;margin-top: 10px;margin-bottom: 15px;width: 360px;"><button class="btn btn-primary" type="submit" name="signup" style="background: rgb(253,183,80);height: 35px;width: 360px;border-radius: 25px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;color: rgb(10,38,53);">Sign Up</button></div>
                                <a style="color: rgb(123,123,123);margin-left: 152px;" href="login.php">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>