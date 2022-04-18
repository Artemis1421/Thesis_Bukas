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
<body>
	<?php 
		if(isset($_POST['displayButton'])){
			$display = $_POST['display_options'];
	?>
	<div class="col offset-xl-0 responsive" style="padding: 12px; width: 50%;">
	    <div class="card shadowsm">
	        <div class="card-body">
	            <div class="row">
	                <div class="col">
	                    <h3 data-bs-toggle="tooltip" title="Graph of total sales by day" data-bs-placement="right" data-bss-tooltip="">Projection of Sales&nbsp;<i class="fa fa-info-circle" style="font-size: 12px;"></i></h3>
	                </div>
	            </div>
	            <div>
	                <canvas id=<?php echo $display; ?> width="100" height="40"></canvas>
	            </div>
	        </div>
	    </div>
	</div>
	<?php } else { ?>
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
	<?php } ?>

	<div>
        <form action='../dashboard/projection.php' method="post">
            <input type="date" name="date_start" required>
            <input type="date" name="date_end" required>
            <button class="btn" type="submit" style="background: #4ecf63;color: #0a2635;font-weight: bold;margin: 0px 6px;">Search</button>
        </form>
    </div>

	<div>
		<form action='../dashboard/projection.php' method="post">
		    <p style="margin: 0px;">Category</p>
		    <select required="" name="display_options" style="padding: 1px 2px;width: 25%;">
		        <option value="">Select Display</option>
		        <option value="linechart">Line Chart</option>
		        <option value="barchart">Bar Chart</option>
		        <option value="piechart">Pie Chart</option>
		    </select>
		    <button name="displayButton" class="btn" type="submit" style="height: 100%; margin-left: 12px; background: #4ecf63;color: #0a2635;font-weight: bold;">Sort</button>
		</form>
	</div>

<?php 
	if(isset($_POST['date_start']) && isset($_POST['date_end'])){
		$date_start = $_POST['date_start'];
		$date_end = $_POST['date_end'];

	    $query = mysqli_query($conn, "SELECT date FROM orders WHERE date >= '$date_start' AND date <= '$date_end' AND deleted != 1 GROUP BY date ORDER BY date DESC LIMIT 10"); 
	    $query1 = mysqli_query($conn, "SELECT sum(total_payment) FROM orders WHERE deleted != 1 GROUP BY date ORDER BY date DESC LIMIT 10"); 	
	} else{
		$query = mysqli_query($conn, "SELECT date FROM orders WHERE deleted != 1 GROUP BY DATE ORDER BY date DESC LIMIT 10"); 
   		$query1 = mysqli_query($conn, "SELECT sum(total_payment) FROM orders WHERE deleted != 1 GROUP BY DATE ORDER BY date DESC LIMIT 10"); 
	}
?>

<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/chart.min.js"></script>
<script src="assets/js/bs-init.js"></script>

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
	if(isset($_POST['date_start']) && isset($_POST['date_end'])){
		$date_start = $_POST['date_start'];
		$date_end = $_POST['date_end'];

	    $query = mysqli_query($conn, "SELECT date FROM orders WHERE date >= '$date_start' AND date <= '$date_end' AND deleted != 1 GROUP BY date ORDER BY date DESC LIMIT 10"); 
	    $query1 = mysqli_query($conn, "SELECT sum(total_payment) FROM orders WHERE deleted != 1 GROUP BY date ORDER BY date DESC LIMIT 10"); 	
	} else{
		$query = mysqli_query($conn, "SELECT date FROM orders WHERE deleted != 1 GROUP BY DATE ORDER BY date DESC LIMIT 10"); 
   		$query1 = mysqli_query($conn, "SELECT sum(total_payment) FROM orders WHERE deleted != 1 GROUP BY DATE ORDER BY date DESC LIMIT 10"); 
	}
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