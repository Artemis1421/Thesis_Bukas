<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Bukas</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Advent+Pro&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/login.css">
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
            <div class="col-md-6" style="margin-top: 248px;">
                <div>
                    <div>
                        <div>
                            <p class="login-text">Change your password</p>
                            <form action="login_validation.php" method="post">
                            <div>
                                <p>Please enter your new password</p>
                            </div>
                            <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;margin-top: 10px;">
                                <i class="fa fa-lock" style="margin: 11px;color: rgb(123,123,123);"></i>
                                <input required oninvalid="this.setCustomValidity('Please input a valid password')" oninput="setCustomValidity('')" type="password" style="padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;" minlength="8" placeholder="Enter new password" name="newpass" required="">
                            </div>
                            <div style="background: #dddddd;border-radius: 25px;border-style: none;width: 360px;margin-top: 10px;">
                                <i class="fa fa-lock" style="margin: 11px;color: rgb(123,123,123);"></i>
                                <input required oninvalid="this.setCustomValidity('Please input a valid password')" oninput="setCustomValidity('')" type="password" style="padding: 2px 2px;margin: -1px;width: 289px;color: rgb(123,123,123);background: #dddddd;border-width: 48px;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;" minlength="8" placeholder="Confirm new password" name="cnewpass" required="">
                            </div>
                            <div style="background: var(--bs-teal);border-radius: 25px;margin-top: 10px;margin-bottom: 15px;width: 360px;">
                                <button class="btn login-button" type="submit" name="savePassButton">Save</button>
                            </div>
                            </form>
                            <p class="not-match-pw">
                            <?php  
                             if (isset($_GET['key'])){
                                $error_id = $_GET['key'];
                                include 'functions/conn.php';
                                if ($error_id == 7){
                                    echo $errors[7];
                                }
                             }
                            ?></p>
                            <a style="color: rgb(123,123,123);margin-left: 152px;" href="login.php">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>