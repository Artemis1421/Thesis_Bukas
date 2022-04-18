<!DOCTYPE html>
<html lang="en">
<?php 
    include '../functions/users.php';
    
    $user_level = $_SESSION['user_level'];
    if (isset($_SESSION['id']) && isset($_SESSION['username']) && isset($_SESSION['business_name']) && $user_level == 1 || $user_level == 2 || $user_level == 3){
        $business_name = $_SESSION['business_name'];
        include '../functions/user_conn.php';
        include '../functions/log_runner.php';
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Forecast</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css">
    <link rel="stylesheet" href="assets/css/loading-css.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="icon" href="../landing_page/assets/img/BrandLogo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
    <script type="text/javascript">
        $(window).load(function() {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");;
        });
    </script>
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
    <div class="se-pre-con"></div>
    <button id="how-to-toggle" class="btn btn-info position-absolute bottom-50 end-0" data-bs-target="#how-to-toast" data-bs-toggle="toast">
        <i class="fas fa-info-circle" bs-cut="1"></i>
    </button>
    <!--How To Forecast Toast -->
    <div role="alert" data-bs-autohide="false" class="toast fade hide" id="how-to-toast" style="margin:12px 24px; position:absolute; top:48px; right:0;">
        <div class="toast-header bg-info">
            <strong class="me-auto text-white">How to - Forecast</strong>
            <button class="btn-close ms-2 mb-1 close" data-bs-dismiss="toast"></button>
        </div>
        <div role="alert" class="toast-body">
            <p style="text-align:justify;">This is the Forecast page where we provide you with the tool to plan for tomorrow. Forecasts are placed in cards with their name, id, lastest sale, and the results of the forecast.
                <br /><br />
                
                <strong>MAPE & Accuracy</strong>
                <br />
                <strong>Mean Absolute Percentage Error (MAPE)</strong>, refers to the accuracy of the forecast in terms of percent error.
                <br />
                <span class="text-secondary" style="font-style: italic;"> If the MAPE value is 5, on average, the forecast is off by 5%. </span>
                <br />

                <div class="table-responsive">
                    <table class="table table-sm table-borderless">
                        <thead>
                            <tr>
                                <th style="text-align:center">MAPE Value</th>
                                <th>Interpretation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center;font-weight: bold;">&lt; 10</td>
                                <td>Highly Accurate Forecasting<br /></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;font-weight: bold;">10 - 20</td>
                                <td>Good Forecasting<br /></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;font-weight: bold;">20 - 50</td>
                                <td>Reasonable Forecasting<br /></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;font-weight: bold;">&gt; 50</td>
                                <td>Inaccurate Forecasting<br /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <strong>What do the colors mean?</strong>
                <br />
                <span class="text-success" style="font-weight:bold;">Green</span> - Good Forecast
                <br />
                <span class="text-warning" style="font-weight:bold;">Yellow</span> - Uncertain Forecast
                <br />
                <span class="text-danger" style="font-weight:bold;">Red</span> - Poor Forecast
                <br /><br />
                
                Do take note that the forecasting takes into account the drops and peaks of sales. <strong>This by therefore no means predict the 100% outcome</strong> of the sales by the coming months and <strong>shall only be used as a guide to your marketing strategies</strong>.
            </p>
        </div>
    </div>
<?php 
        $group_pid = mysqli_query($conn, 'SELECT product_id FROM sales GROUP BY product_id');
        $row_collections = array();
        while ($row = mysqli_fetch_array($group_pid)){

            $check_empty = mysqli_query($conn,'SELECT DATE, SUM(sales_qty) AS qty FROM sales WHERE product_id ='.$row["product_id"].' GROUP BY DATE');
            $row_collections[] = mysqli_num_rows($check_empty);

        }
        if (sizeof($row_collections) == 0){
            echo '<p class="info d-flex justify-content-center align-items-center" style="border: 1px solid; margin:10px auto; padding: 15px 10px 15px 4px; background-repeat: no-repeat; background-position: 10px center; max-width:750px; color: #00529B; background-color: #BDE5F8; "><i class="fa fa-info-circle" style="font-size:34px;"></i> &nbsp; Not Enough Data to Forecast. Recommended at least 2 months of sales per product to continue </p>';
        }elseif (max($row_collections) < 59 ){
            echo '<p class="info d-flex justify-content-center align-items-center" style="border: 1px solid; margin:10px auto; padding: 15px 10px 15px 4px; background-repeat: no-repeat; background-position: 10px center; max-width:750px; color: #00529B; background-color: #BDE5F8; "><i class="fa fa-info-circle" style="font-size:34px;"></i> &nbsp; Not Enough Data to Forecast. Recommended at least 2 months of sales per product to continue </p>';
        }else{

            $p_id = mysqli_query($conn, 'SELECT product_id FROM sales GROUP BY product_id');

            while ($rows = mysqli_fetch_array($p_id)){
                $pid = $rows['product_id'];

                $order_date = mysqli_query($conn,"SELECT date FROM sales WHERE product_id = ".$pid." order by date desc limit 1");
                $forecast_date = mysqli_query($conn,"SELECT date FROM forecast WHERE product_id = ".$pid." order by date desc limit 1");
                $check_numrow = mysqli_query($conn,'SELECT DATE, SUM(sales_qty) AS qty FROM sales WHERE product_id ='.$pid.' GROUP BY DATE');

                $row_order = mysqli_fetch_array($order_date);
                $row_forecast =  mysqli_fetch_array($forecast_date);

                $forecast = mysqli_query($conn, "SELECT * FROM forecast");

                if (mysqli_num_rows($forecast) == 0){
                    $command = shell_exec('python ../py/sagumDataSet.py '.$business_name);
                } 
                elseif( $row_order != $row_forecast && mysqli_num_rows($check_numrow) > 59){
                    $command = shell_exec('python ../py/emptyForecast.py '.$business_name.' '.$pid);
                }

            }

?>
    <div style="width: 100%; margin-top: 24px">
        <div class="row g-0" style="padding: 6px 48px;">
            <div class="col d-flex justify-content-between">
                <h3>Forecast</h3>
                <div>
                    <form class="d-flex" method="post" action="forecast.php">
                        <select class="form-select" required="" name="fsort" style="height: 100%;">
                            <option value="f.mape">Sort By</option>
                            <option value="p.product_name">Product Name</option>
                            <option value="p.category_id">Category</option>
                            <option value="f.mape">Accuracy</option>
                        </select>
                        <button name="sortButton" class="btn" type="submit" style="height: 100%; margin-left: 12px; background: #4ecf63;color: #0a2635;font-weight: bold;">Sort</button>
                    </form>
                </div>
            </div>
        </div>
      <?php 
        if (isset($_POST['sortButton'])) {
            $sort = $_POST['fsort'];
        } else{
            $sort = 'f.mape';
        }
        $f_canvas = get_forecast($sort);
        foreach($f_canvas as $data):
            ?>
            <div class="d-flex flex-column align-items-center forecast_body" style="padding: 12px 0px;">
                <div class="row product_forecast" style="background: #ebebeb;border-radius: 10px; border: 1px solid rgba(33,37,41,0);width: 90%;margin: 6px 0px;">
                    <div class="col d-flex flex-row" id="forecast-col-1" style="border-right: 1px solid rgb(225,230,235);max-width: 20%;">
                        <div class="text-break">
                            <h5 style="font-size: 40px; margin: 0px"><?php echo $data['product_name'] ?></h5>
                            <p style="margin: 0px;">Category: <?php echo $data['category_name'] ?></p><br>
                            <p style="margin: 0px;">Last Sales Date</p>
                                <p><?php echo $data['date'] ?></p>
                        </div>
                    </div>
                    <div class="col" id="forecast-col-2" style="max-width: 20%;">
                        <div class="row">
                            <div class="col">
                                <h5>Results</h5>
                                <?php 
                                    $totalPercent = 100 - $data['mape'];
                                    if($totalPercent >= 80) {
                                        echo "<p class=\"text-success\" style=\"font-weight:bold; margin:0px;\">Accuracy: ".$totalPercent."%</p>";
                                    }
                                    if($totalPercent < 80 && $totalPercent >= 50){
                                        echo "<p class=\"text-warning\" style=\"font-weight:bold; margin:0px;\">Accuracy: ".$totalPercent."%</p>";
                                    } 
                                    if($totalPercent < 50){
                                        echo "<p class=\"text-danger\" style=\"font-weight:bold; margin:0px;\">Accuracy: ".$totalPercent."%</p>";
                                    }
                                ?>
                                <p>MAPE: <?php echo $data['mape'] ?></p>
                                <p>
                                    <?php 
                                        if ($totalPercent < 50 ){
                                            echo "<p class=\"text-danger\" style=\"font-weight:bold;\">Activity in this Product is Highly Unstable.</p>"; 
                                        }elseif ($totalPercent < 60){ 
                                            echo "<p class=\"text-warning\" style=\"font-weight:bold;\">Activity in this Product is Unstable.</p>";} 
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col" id="forecast-col-3" style="border-left: 1px solid rgb(225,230,235);max-width: 60%;">
                        <div style="padding-top: 6px; padding-bottom: 6px">
                            <canvas class="line" id="linechart<?php echo $data['product_id']  ?>" height="150" style="display: block; width: 493px; height: 150px;" width="493"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="assets/js/chart.min.js"></script>

    <?php 
        $query = mysqli_query($conn, "SELECT date FROM orders GROUP BY DATE LIMIT 10"); 
        $query1 = mysqli_query($conn, "SELECT sum(total_payment) FROM orders GROUP BY DATE LIMIT 10"); 
        if(!isset($_POST['sort'])){
            $forecasts = get_forecast('p.id');
        } else{
            $forecasts = get_forecast($sort);
        }
        foreach ($forecasts as $forecast):
        $p_id = $forecast['product_id'];
        $dataMonth = shell_exec('python ../py/month_getter.py '.$business_name.' '.$p_id);
        $rowCount = shell_exec('python ../py/rowCount.py '.$business_name.' '.$p_id);
     ?>
    <script>
        $('#linechart<?php echo $forecast['product_id']  ?>').each(function (index, element) {
        var ctx = element.getContext('2d');
        var data = {
            datasets: [{
                data: <?php echo $forecast['forecast'] ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ], borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                            ],
            }],
            labels: [<?php 

                    $ctr = $dataMonth+$rowCount+1;

                    $months = array('January', 'February', 'March',
                                    'April','May','June',
                                    'July','August','September',
                                    'October','November','December');
                    $lenghtOfArray = sizeof($months);
                    for ($i = $rowCount; $i <$ctr-1 ; $i++) { 
                        $fun = ($months[$i % $lenghtOfArray]);
                        echo "'".$fun."',";
    }?>]
        };
        var myDoughnutChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                 }
            }
        });
    });
</script>
<?php endforeach; ?>
<?php } ?>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/script.min.js"></script>
    <script src="assets/js/bukas.toast.js"></script>
</body>
</html>
<?php 
}else{
     header("Location: ../functions/logout.php");
     exit();
}
?>