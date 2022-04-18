<!DOCTYPE html>
<html lang="en">
<?php 
    include '../functions/users.php';

    $user_level = $_SESSION['user_level'];
    if (isset($_SESSION['id']) && isset($_SESSION['username']) && isset($_SESSION['business_name']) && $user_level == 2){
        $id = $_SESSION['id'];
        $user_session = $_SESSION['username'];
        $business_session = $_SESSION['business_name'];

        include '../functions/user_conn.php';
        include '../functions/log_runner.php';
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Advent+Pro&amp;display=swap">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/untitled.css">
    <link rel="icon" href="../landing_page/assets/img/BrandLogo.png">
</head>
<?php
    //0 - Employee
    //1 - Owner
    //2 - Admin
    //3 - Accountant
    //4 - Manager
    if($_SESSION['user_level'] == 0){
        include '../includes/Navbar_employee.html';
    } 
    elseif($_SESSION['user_level'] == 1){
        include '../includes/Navbar.html'; 
    }
    elseif($_SESSION['user_level'] == 2){
        include '../includes/Navbar_admin.html';
    }
    elseif($_SESSION['user_level'] == 3){
        include '../includes/Navbar_accountant.html';
    }
    elseif($_SESSION['user_level'] == 4){
        include '../includes/Navbar_manager.html';
    }    
?>
<body>
<div style="margin: 12px;padding: 12px;">
    <form method="post" action="../profile_page/profile_validation.php" enctype="multipart/form-data">
    <div class="container" style="width: 100%;">
        <div class="row d-md-flex" style="width: 100%;height: 20%;">
            <div class="col-md-3 col-xxl-3 offset-xxl-0 d-flex flex-row" style="width: 30%;">
                <div class="card shadow d-flex flex-row" style="width: 100%;height: 100%;border-radius: 25px;background: rgb(255,255,255);border-style: none;border-color: rgb(10,38,53);">
                    <div class="card-body d-inline-block d-flex flex-column" style="width: 100%;height: 100%;">
                        <?php 
                            $query = "SELECT * FROM users WHERE username = '$user_session'";
                            
                            if($result = mysqli_query($conn, $query)):
                                while($row = mysqli_fetch_assoc($result)):
                        ?>
                        <img class="rounded-circle d-inline-block" style="width: 80%;height: 75%; align-self: center;" src='<?php echo '../assets/'.$business_session.'/users/'.$row['image']; ?>'>
                        <div class="custom-file">
                            <input type="file" name="uploadFile" class="custom-file-input" style="margin-top: 8px;width: 100%;max-width: 100%;">
                        </div>
                        <button class="btn btn-primary text-center d-flex justify-content-center" type="submit" style="background: rgb(10,38,53);width: 100%;height: 10%;margin-top: 6px;" name="changePhoto">Change Photo</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xxl-8 offset-md-0 offset-xxl-0" style="width: 70%;">
                <div class="card shadow" style="width: 100%;height: 100%;border-radius: 25px;background: rgb(253,183,80);border: 0px none rgb(33,37,41) ;">
                    <div class="card-body" style="width: 100%;height: 100%;padding: 0%;">
                        <div style="margin-left: 2%;margin-top: 2%;border-radius: 51px;">
                            <p style="color: rgb(10,38,53);font-weight: bold;width: 100%;padding-left: 4px;">User Settings</p>
                        </div>
                        <div class="card" style="border-radius: 0px;border-bottom-left-radius: 25px;height: 87.5%;border-bottom-right-radius: 25px;">
                            <div class="card-body d-flex flex-row">
                                <div style="height: 100%;width: 50%;">
                                    <p style="color: #222222;">Username</p>
                                    <div style="background: #dddddd;border-radius: 25px;"><input class="d-flex flex-row" type="text" style="width: 90%;border-radius: 25px;background: #dddddd;border-width: 0px;margin-left: 4%;" name="username" placeholder='<?php echo $row['username']; ?>'></div>
                                    <p style="color: #222222;margin-top: 9px;">First Name</p>
                                    <div style="background: #dddddd;border-radius: 25px;">
                                        <input class="d-flex" type="text" style="width: 90%;border-radius: 25px;background: #dddddd;border-width: 0px;margin-left: 4%;" name="fname" placeholder='<?php echo $row['first_name']; ?>'>
                                    </div>
                                    <p style="color: #222222;margin-top: 9px;">Last Name</p>
                                    <div style="background: #dddddd;border-radius: 25px;">
                                        <input class="d-flex" type="text" style="width: 90%;border-radius: 25px;background: #dddddd;border-width: 0px;margin-left: 4%;" name="lname" placeholder='<?php echo $row['last_name']; ?>'>
                                    </div>
                                    <div style="width: 100%;height: 13%;margin-top: -11%;">
                                        <button class="btn btn-primary d-flex justify-content-center" type="submit" style="text-align: center;background: rgb(10,38,53);margin-top: 20%;width: 100%;" name="saveUser">Save User Settings</button>
                                    </div>
                                </div>
                                <div style="margin-left: 5%;width: 50%;">
                                    <p style="color: #222222;">Password</p>
                                    <div style="background: #dddddd;border-radius: 25px;">
                                        <input class="d-flex" type="password" style="width: 90%;border-radius: 25px;background: #dddddd;border-width: 0px;margin-left: 4%;" name="pass" placeholder="" minlength="8">
                                    </div>
                                    <p style="color: #222222;margin-top: 9px;">New Password</p>
                                    <div style="background: #dddddd;border-radius: 25px;">
                                        <input class="d-flex" type="password" style="width: 90%;border-radius: 25px;background: #dddddd;border-width: 0px;margin-left: 4%;" minlength="8" name="npass">
                                    </div>
                                    <p style="color: #222222;margin-top: 9px;">Confirm New Password</p>
                                    <div style="background: #dddddd;border-radius: 25px;">
                                        <input class="d-flex" type="password" style="width: 90%;border-radius: 25px;background: #dddddd;border-width: 0px;margin-left: 4%;" minlength="8" name="confirmPass">
                                    </div>
                                    <p class="profile-errors" style="margin-top: 23px">
                                        <?php  
                                         if (isset($_GET['key'])){
                                            $error_id = $_GET['key'];
                                            include '../functions/conn.php';
                                            if ($error_id == 11){
                                                echo $errors[11];
                                            }elseif($error_id==12){
                                                echo $errors[12];
                                            }elseif($error_id==13){
                                                echo $errors[13];
                                            }elseif($error_id==16){
                                                echo $errors[16];
                                            }
                                        }
                                        ?>    
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="width: 100%;height: 100%;">
    <div class="container">
        <div class="row" style="width: 100%;">
            <div class="col-md-4 col-xxl-8 offset-md-0 offset-xxl-0" style="width: 100%;">
                <div class="card shadow" style="border-radius: 25px;background: rgb(253,183,80);margin-top: 20px;height: 303px;">
                    <div class="card-body" style="width: 99.9%;padding: 0%;border-radius: 25px;">
                        <div style="margin-left: 2%;margin-top: 2%;">
                            <p style="color: rgb(10,38,53);font-weight: bold;width: 100%;">Business Info</p>
                        </div>
                        <div class="card" style="height: 80.5%;border-bottom-right-radius: 25px;border-bottom-left-radius: 25px;width: 100.3%;border-top-left-radius: 0px;border-top-right-radius: 0px;border-width: 1px;margin-left: -1px;">
                            <div class="card-body d-flex flex-row">
                                <div style="width: 50%;">
                                    <p style="color: #222222;">Business Name</p>
                                    <div style="background: #ffffff;border-radius: 25px;">
                                        <?php 
                                            $business = $row['business_name']; 
                                            $business = str_replace("_", " ", $business);
                                        ?>
                                        <input class="d-flex" type="text" style="width: 90%;border-radius: 25px;border-width: 0px;background: #ffffff;margin-left: 3%;" value='<?php echo $business ?>' disabled>
                                        <input type="text" style="display: none;" name="bname" placeholder='<?php echo $business ?>'>
                                    </div>
                                    <p style="color: #222222;margin-top: 9px;">Contact Info</p>
                                    <div style="background: #dddddd;border-radius: 25px;">
                                        <input class="d-flex" type="text" style="width: 90%;background: #dddddd;border-radius: 25px;border-width: 0px;margin-left: 3%;" maxlength="11" name="contact" placeholder='<?php echo $row['contact_info']; ?>'>
                                    </div>
                                    <p style="color: #222222;margin-top: 9px;">Email</p>
                                    <div style="background: #dddddd;border-radius: 25px;">
                                        <input class="d-flex" type="email" style="width: 90%;border-radius: 25px;border-width: 0px;background: #dddddd;margin-left: 3%;" name="email" placeholder='<?php echo $row['email']; ?>'>
                                    </div>
                                </div>
                                <div style="margin-left: 5%;width: 50%;">
                                    <p style="color: #222222;">Position</p>
                                    <?php 
                                        if($row['user_level'] == 0){
                                            $user_level = "Employee";
                                        } 
                                        elseif($row['user_level'] == 1){
                                            $user_level = "Owner";
                                        }
                                        elseif($row['user_level'] == 2){
                                            $user_level = "Admin";
                                        }
                                        elseif($row['user_level'] == 3){
                                            $user_level = "Accountant";
                                        }
                                        elseif($row['user_level'] == 4){
                                            $user_level = "Manager";
                                        }
                                    ?>
                                    <div style="background: #ffffff;border-radius: 25px;">
                                        <input class="d-flex" type="text" style="width: 90%;border-width: 0px;border-radius: 25px;background: #ffffff;margin-left: 3%;" name="" value='<?php echo $user_level; ?>' disabled="">
                                    </div>
                                    <p style="color: #222222;margin-top: 9px;">Business Owner</p>
                                    <?php 
                                        $query2 = "SELECT `first_name`, `last_name` FROM users WHERE user_level = 1 AND business_name = '$business_session' LIMIT 1";
                                        
                                        if($result2 = mysqli_query($conn, $query2)):
                                            while($row2 = mysqli_fetch_assoc($result2)):
                                    ?>
                                    <div style="background: #ffffff;border-radius: 25px;"><input class="d-flex" type="text" style="width: 90%;background: #ffffff;border-radius: 25px;border-width: 0px;margin-left: 3%;" disabled="" name="contact" value='<?php echo $row2['first_name']; echo " "; echo $row2['last_name'] ?>'></div>
                                            <?php endwhile; ?>
                                        <?php endif ?>
                                    <div class="d-flex flex-row justify-content-between align-items-end" style="height: 90px;">
                                        <button class="btn btn-primary d-flex justify-content-xxl-center" type="submit" name="saveBusiness" style="text-align: center;background: rgb(10,38,53);margin-top: 119px;">Save Business Info</button>
                                        <p class="profile-errors" style="margin-top: 23px">
                                        <?php  
                                         if (isset($_GET['key'])){
                                            $error_id = $_GET['key'];
                                            include '../functions/conn.php';
                                            if ($error_id == 14){
                                                echo $errors[14];
                                            }
                                        }
                                        ?>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
            <?php endwhile; ?>
        <?php endif ?>
    </div>


<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/bootstrap/js/search.js"></script>
<script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
<script src="assets/js/popup.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function(){
        $('.editbtn').on('click', function(){
            $('#edit-employee-modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();

            console.log(data);

            var position = data[4].trim();

            $('#thumbnail').val(data[0]);
            $('#fname').val(data[1]);
            $('#lname').val(data[2]);
            $('#username').val(data[3]);
            $('#position').val(data[4]);
            $('#contact').val(data[5]);
            $('#email').val(data[6]);

            console.log(position);
        });

        $('.deletebtn').on('click', function(){
            $('#delete-employee-modal').modal('show');

            $tr1 = $(this).closest('tr');

            var dataD = $tr1.children("td").map(function(){
                return $(this).text();
            }).get();

            console.log(dataD);

            $('#dusername').val(dataD[2]);
        });

        $("#search_input").on("keyup", function(){
            var value = $(this).val().toLowerCase();
            $("#employees_table tr").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

    });
</script>

</body>
</html>
<?php 
    }else{
         header("Location: ../functions/logout.php");
         exit();
    }
?>