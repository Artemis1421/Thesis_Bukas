<!DOCTYPE html>
<html lang="en">
<?php 
    include '../functions/users.php';

    $user_level = $_SESSION['user_level'];
    if (isset($_SESSION['id']) && isset($_SESSION['username']) && isset($_SESSION['business_name']) && $user_level == 1 || $user_level == 2 || $user_level == 3 || $user_level == 4){
        include '../functions/user_conn.php';
        include '../functions/log_runner.php';

        $business_session = sanitize($_SESSION['business_name']);
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css">
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
<body class="align-items-xl-end">
    <div style="padding-top: 24px">
        <div class="row">
            <div class="col data-bs-toggle="tooltip" title="All-time total of Sales" data-bs-placement="right" data-bss-tooltip=""">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between" style="background: #4ecf63;">
                        <?php 
                            if($result = mysqli_query($conn,"SELECT sum(total_payment) FROM orders WHERE deleted != 1")):
                                while($row = mysqli_fetch_array($result)):
                        ?>
                        <div>
                            <?php 
                                if($row['sum(total_payment)'] == ""){
                                    echo '<h4 style="color: rgb(255,255,255);">0</h4>';
                                } else{
                                    echo '<h4 style="color: rgb(255,255,255);">'.number_format($row['sum(total_payment)'], 2).'</h4>';
                                }
                            ?>
                            <h6>Sales</h6>
                        </div>
                        <div class="d-flex align-items-center"><i class="fa fa-shopping-basket" id="card-icon" style="color: rgb(255,255,255);"></i>
                        </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col data-bs-toggle="tooltip" title="All-time number of Orders" data-bs-placement="right" data-bss-tooltip=""">
                <div class="card-body d-flex justify-content-between" style="background: #fdb750;">
                    <?php 
                        if($result = mysqli_query($conn,"SELECT count(id) FROM orders WHERE deleted != 1")):
                            while($row = mysqli_fetch_array($result)):
                    ?>
                    <div>
                        <h4 style="color: rgb(255,255,255);"><?php echo number_format($row['count(id)']); ?></h4>
                        <h6>Orders</h6>
                    </div>
                    <div class="d-flex align-items-center"><i class="fa fa-truck" id="card-icon" style="color: rgb(255,255,255);"></i>
                    </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col data-bs-toggle="tooltip" title="Overall number of Items Sold" data-bs-placement="right" data-bss-tooltip=""">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between" style="background: #44acd9;">
                        <?php 
                            if($result = mysqli_query($conn,"SELECT sum(sales_qty) FROM sales WHERE deleted != 1")):
                                while($row = mysqli_fetch_array($result)):
                        ?>
                        <div>
                            <?php 
                                if($row['sum(sales_qty)'] == ""){
                                    echo '<h4 style="color: rgb(255,255,255);">0</h4>';
                                } else{
                                    echo '<h4 style="color: rgb(255,255,255);">'.number_format($row['sum(sales_qty)']).'</h4>';
                                }
                            ?>
                            <h6>Items Sold</h6>
                        </div>
                        <div class="d-flex align-items-center"><i class="fa fa-shopping-cart" id="card-icon" style="color: rgb(255,255,255);"></i></div>
                    </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col data-bs-toggle="tooltip" title="Total sales for today" data-bs-placement="right" data-bss-tooltip=""">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between" style="background: #fd5050;">
                        <?php 
                            date_default_timezone_set('Asia/Manila');
                            $current_date = date("Y-m-d");

                            if($result = mysqli_query($conn,"SELECT sum(total_payment) FROM orders WHERE date = '$current_date' AND deleted != 1")):
                                while($row = mysqli_fetch_array($result)):
                        ?>
                        <div>
                            <?php 
                                if($row['sum(total_payment)'] == ""){
                                    echo '<h4 style="color: rgb(255,255,255);">0.00</h4>';
                                } else{
                                    echo '<h4 style="color: rgb(255,255,255);">'.number_format($row['sum(total_payment)'],2).'</h4>';
                                }
                            ?>
                            <h6>Sales for Today</h6>
                        </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        <div class="d-flex align-items-center"><i class="fa fa-star" id="card-icon" style="color: rgb(255,255,255);"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col offset-xl-0 responsive" style="padding: 12px; width: 50%;">
                <div class="card shadowsm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 data-bs-toggle="tooltip" title="Graph of total sales by day" data-bs-placement="right" data-bss-tooltip="">Projection of Sales&nbsp;<i class="fa fa-info-circle" style="font-size: 12px;"></i></h3>
                            </div>
                        </div>
                        <div>
                            <canvas id="linechart" width="100" height="40"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col responsive" style="width: 50%; padding: 12px;">
                <div class="card shadow-sm" style="height: 100%;">
                    <div class="card-body">
                        <h4 data-bs-toggle="tooltip" title="Chart of sales of Top 10 sold products" data-bs-placement="right" data-bss-tooltip="">Sales Per Product&nbsp;<i class="fa fa-info-circle" style="font-size: 12px;"></i></h4>
                        <div>
                            <canvas id="piechart" width="100" height="40"></canvas>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
        <div class="row row-cols-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="d-xl-flex align-items-xl-center top-product-header-col" data-bs-toggle="tooltip" title="Quantity of Top 10 sold products" data-bs-placement="right" data-bss-tooltip="">Most Sold Product&nbsp;<i class="fa fa-info-circle" style="font-size: 12px;"></i></h5>
                        <div class="vstack gap-2">
                            <?php 
                            $sql = join_msold_table();
                            if($result = mysqli_query($conn,$sql)):
                                if(mysqli_num_rows($result)==0):?>
                                    <div class="d-flex justify-content-center">
                                        <p class="text-secondary">No data available.</p>
                                    </div>
                                <?php else: 
                                    while($row = mysqli_fetch_array($result)):?>
                                        <div class="d-flex">
                                            <img src='<?php echo '../assets/'.$business_session.'/products/'.$row['image'] ?>' width="150px" height="100px">
                                            <div class="d-flex justify-content-between" style="width: 90%;padding: 0px 12px;">
                                                <h6 style="margin: 0px;"><?php echo $row['product_name']; ?></h6>
                                                <p style="margin: 0px;"><?php echo $row['sum(s.sales_qty)']; ?></p>
                                            </div>
                                        </div>
                                    <?php endwhile; 
                                endif;
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="d-xl-flex align-items-xl-center top-product-header-col" data-bs-toggle="tooltip" title="Top 10 products with the lowest stock" data-bs-placement="right" data-bss-tooltip="">Lowest Product Stocks&nbsp;
                            <i class="fa fa-info-circle" style="font-size: 12px;"></i></h5>
                        <div class="vstack gap-2">
                            <?php 
                            $sql = lowprod_table();
                            if($result = mysqli_query($conn, $sql)):
                                if(mysqli_num_rows($result)==0):?>
                                    <div class="d-flex justify-content-center">
                                        <p class="text-secondary">No data available.</p>
                                    </div>
                                <?php else: 
                                    while($row = mysqli_fetch_array($result)):?>
                                        <div class="d-flex">
                                            <img src='<?php echo '../assets/'.$business_session.'/products/'.$row['image'] ?>' width="150px" height="100px">
                                            <div class="d-flex justify-content-between" style="width: 90%;padding: 0px 12px;">
                                                <h6 style="margin: 0px;"><?php echo $row['product_name']; ?></h6>
                                                <p style="margin: 0px;"><?php echo $row['product_qty']; ?></p>
                                            </div>
                                        </div>
                                    <?php endwhile;
                                endif;
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="d-xl-flex align-items-xl-center top-product-header-col" data-bs-toggle="tooltip" title="10 Most recently sold product/s" data-bs-placement="right" data-bss-tooltip=""  >Recently Sold Product&nbsp;
                            <i class="fa fa-info-circle" style="font-size: 12px;"></i></h5>
                        <div class="vstack gap-2">
                            <?php 
                            date_default_timezone_set('Asia/Manila');
                            $current_date = date("Y-m-d");  
                            $sql = join_rsold_table(sanitize($current_date));
                            if($result = mysqli_query($conn, $sql)):
                                if(mysqli_num_rows($result)==0):?>
                                    <div class="d-flex justify-content-center">
                                        <p class="text-secondary">No data available.</p>
                                    </div>
                                <?php else: 
                                 while($row = mysqli_fetch_array($result)):?>
                                    <div class="d-flex">
                                        <img src='<?php echo '../assets/'.$business_session.'/products/'.$row['image'] ?>' width="150px" height="100px">
                                        <div class="d-flex justify-content-between" style="width: 90%;padding: 0px 12px;">
                                            <h6 style="margin: 0px;"><?php echo $row['product_name'] ?></h6>
                                            <p style="margin: 0px;"><?php echo $row['sales_qty']; ?></p>
                                        </div>
                                    </div>
                                <?php endwhile;
                                endif; 
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/chart.min.js"></script>
<script src="assets/js/bs-init.js"></script>

<?php 
    $query = mysqli_query($conn, "SELECT date FROM orders WHERE deleted != 1 GROUP BY DATE ORDER BY date DESC LIMIT 10"); 
    $query1 = mysqli_query($conn, "SELECT sum(total_payment) FROM orders WHERE deleted != 1 GROUP BY DATE ORDER BY date DESC LIMIT 10"); 
?>

<script>
    var line = document.getElementById("linechart");
    var myChart = new Chart(line, {

        type: 'line',
        data: {
            labels: [<?php while($row = mysqli_fetch_array($query)){ echo  '"'.$row['date'].'",';} ?>].reverse(),
            datasets: [{
                    label: 'Total sales',
                    data: [<?php while($row1 = mysqli_fetch_array($query1)){echo '"'.$row1['sum(total_payment)'].'",';} ?>].reverse(),
                    backgroundColor: [
                    'rgba(255, 99, 132,0.2)',
                    'rgba(54, 162, 235,0.2)',
                    'rgba(255, 206, 86,0.2)',
                    'rgba(75, 192, 192,0.2)',
                    'rgba(153, 102, 255,0.2)',
                    'rgba(255, 159, 64,0.2)',
                    'rgba(255, 99, 132,0.2)',
                    'rgba(54, 162, 235,0.2)',
                    'rgba(255, 206, 86,0.2)',
                    'rgba(75, 192, 192,0.2)',
                    'rgba(153, 102, 255,0.2)',
                    'rgba(255, 159, 64,0.2)'
                    ],
                    borderColor: [
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
                    borderWidth: 2,
                    parseTime: false
                }]
        },
        options: {
            legend: {
            display: false
            },
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            }
        }
    });
</script>

<?php 
    $query = mysqli_query($conn, "SELECT s.product_id, s.deleted, sum(s.sales_price) AS price, p.product_name FROM sales s INNER JOIN products p ON s.product_id = p.id WHERE s.deleted != 1 GROUP BY product_id LIMIT 10;"); 
    $query1 = mysqli_query($conn, "SELECT s.product_id, s.deleted, sum(s.sales_price) AS price, p.product_name FROM sales s INNER JOIN products p ON s.product_id = p.id WHERE s.deleted != 1 GROUP BY product_id LIMIT 10;"); 
?>

<script>
    var pie = document.getElementById("piechart");
    var myChart = new Chart(pie, {
        type: 'pie',
        data: {
            labels: [<?php while($row = mysqli_fetch_array($query)){ echo  '"'.$row['product_name'].'",';} ?>],
            datasets: [{
                    label: 'Total sales',
                    data: [<?php while($row1 = mysqli_fetch_array($query1)){echo '"'.$row1['price'].'",';} ?>],
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
                    ],
                    borderColor: [
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
                    borderWidth: 2
                }]
        },
       options: {
            "legend":{
                "display": true,
                "labels":{
                    "fontStyle": "normal"
                    },
                "reverse": false,
                "position": "right"
                        },
            scales: {
            }
        }
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