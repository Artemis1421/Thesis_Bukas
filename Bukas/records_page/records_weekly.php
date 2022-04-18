<!DOCTYPE html>
<html lang="en">
<?php 
	include '../functions/users.php';

	$user_level = $_SESSION['user_level'];
	if (isset($_SESSION['id']) && isset($_SESSION['business_name']) && $user_level == 1 || $user_level == 2 || $user_level == 3 || $user_level == 4){
		include '../functions/user_conn.php';
		include '../functions/log_runner.php';
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Records</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/styles.css">
    <link rel="stylesheet" href="assets/css/styles.min.css">
    <link rel="icon" href="../assets/img/BrandLogo.png">
</head>

<body>
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
    <div style="margin: 12px;padding: 12px;">
        <div class="row" style="padding-bottom: 12px;">
            <div class="col">
                <header>
                    <div class="col d-flex justify-content-between align-items-md-center">
                        <div class="d-flex justify-content-between">
                            <h5><b>Weekly Sales</b></h5>
                        </div>
                        <div>
                            <input type="text" id="search_input" placeholder="Search" style="width: 100%;border-radius: 10px;padding-left: 8px;padding-right: 8px;border-width: 1px;border-color: #0a2635;padding-top: 2px;">
                        </div> 
                        <div>
                            <form action='../records_page/records_weekly.php' method="post">
                                <input type="date" name="date_start" required>
                                <input type="date" name="date_end" required>
                                <button class="btn" type="submit" style="background: #4ecf63;color: #0a2635;font-weight: bold;margin: 0px 6px;">Search</button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <form action="../records_page/export_weekly.php" method="post">
                            <div role="group" class="btn-group">
                                <button class="btn btn-primary pdf <?php if(isset($_POST['date_start'])){echo "";}else{ echo "disabled";} ?>" type="button" onclick="generatePDFW()" style="background: #d94444;border-style: none;">PDF</button>
                                <button class="btn btn-primary csv <?php if(isset($_POST['date_start'])){echo "";}else{ echo "disabled";} ?>" type="submit" name="csvButton" style="background: #fdb750;border-style: none;">CSV</button>
                                <button class="btn btn-primary excel <?php if(isset($_POST['date_start'])){echo "";}else{ echo "disabled";} ?>" type="submit" name="excelButton" style="background: #4ecf63;border-style: none;">Excel</button>
                            </div>
                        </form>
                    </div>
                </header>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table sortable" id="table_with_data">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Selling Price</th>
                                <th>Cost Price</th>
                                <th>Quantity</th>
                                <th data-toggle="tooltip" title="Total before expenses" data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-placement="right" >Gross Total <i class="fa fa-info-circle"style="font-size: 12px;"></i></th>
                                <th data-toggle="tooltip" title="Total after expenses" data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-placement="right">Income Total <i class="fa fa-info-circle" style="font-size: 12px;"></i></th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id = "records_table">
                            <tr>
                            	<?php 
                            		if(isset($_POST['date_start']) && isset($_POST['date_end'])){
                            			$date_start = $_POST['date_start'];
                                        $date_end = $_POST['date_end'];

                                        if($date_start > $date_end){

                                        } else{
                                            $dayformatS = date("d", strtotime($date_start));
                                            $dayformatE = date("d", strtotime($date_end));

                                            $formatDateS = date("Y-m-".intval($dayformatS), strtotime($date_start));
                                            $formatDateE = date("Y-m-".intval($dayformatE), strtotime($date_end));

                                            $_SESSION['dateReportStart'] = $formatDateS;
                                            $_SESSION['dateReportEnd'] = $formatDateE;

                                            $sales = join_sales_weekly_table($formatDateS, $formatDateE);
                                            foreach($sales as $sale):
                        		?>
                        		<script>
                        			var formDateS = '<?=$formatDateS?>';
                                    var formDateE = '<?=$formatDateE?>';
                        		</script>

	                            <td style="width: 10%;"><?php echo $sale['product_name']; ?></td>
                                <td style="width: 10%;"><?php echo $sale['category_name']; ?></td>
                                <td style="width: 10%;"><?php echo "PHP ".number_format($sale['product_price'], 2); ?></td>
                                <td style="width: 10%;"><?php echo "PHP ".number_format($sale['product_cprice'], 2); ?></td>
                                <td style="width: 10%;"><?php echo number_format($sale['total_qty']); ?></td>
                                <td style="width: 15%;"><?php echo "PHP ".number_format($sale['total_selling_price'], 2); ?></td>
                                <td style="width: 15%;"><?php echo "PHP ".number_format($sale['total_income_price'], 2); ?></td>
                                <td style="width: 15%;"><?php echo $sale['date']; ?></td>
                        	</tr>
                        	<?php
                                @$total += $sale['total_selling_price'];
                                @$total_formatted = sprintf('%0.2f', $total); 

                                @$total2 += $sale['total_income_price'];
                                @$total_formatted2 = sprintf('%0.2f', $total2); 

                                $date = $sale['date'];
                                endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr id="report_tr">
                                <td>TOTAL</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <?php 
                                    if (@$total_formatted == "") {
                                        echo "<td>PHP 0.00</td>";
                                    } else{
                                        echo "<td>PHP ".number_format($total_formatted, 2)."</td>";
                                    }
                                ?>   
                                <?php 
                                    if (@$total_formatted2 == "") {
                                        echo "<td>PHP 0.00</td>";
                                    } else{
                                        echo "<td>PHP ".number_format($total_formatted2, 2)."</td>";
                                    }
                                ?>
                                <td><?php echo $formatDateS." - ".$formatDateE; ?></td>
                            </tr>
                            <tr id="report_tr">
                                <td colspan="8"><b>Payment Methods</td>
                            </tr>
                            <?php 
                                if($result = mysqli_query($conn, "SELECT paymethod, sum(total_payment) FROM orders WHERE date BETWEEN '$date_start' AND '$date_end' AND deleted != 1 GROUP BY paymethod")):
                                    while($row = mysqli_fetch_array($result)):
                                        $paymethod = $row['paymethod'];
                                    if($result1 = mysqli_query($conn, "SELECT s.id, s.deleted, s.order_id, s.product_id, s.date, s.sales_qty, o.id, o.paymethod, p.id, p.product_price, p.product_cprice, sum(s.sales_qty * (p.product_price - p.product_cprice)) AS 'total_income' FROM sales s LEFT JOIN products p ON s.product_id = p.id LEFT JOIN orders o ON s.order_id = o.id WHERE paymethod = '$paymethod' AND s.date BETWEEN '$date_start' AND '$date_end' AND s.deleted != 1")):
                                        while($row1 = mysqli_fetch_array($result1)):
                                            if($row1['total_income'] == ""){
                                                $total_income = '0.00';
                                            }else{
                                                $total_income = $row1['total_income'];
                                            }
                                        echo '<tr id="report_tr">';
                                            echo '<td>'.$row['paymethod'].'</td>';
                                            echo '<td></td>';
                                            echo '<td></td>';
                                            echo '<td></td>';
                                            echo '<td></td>';
                                            echo '<td> PHP '.number_format($row['sum(total_payment)'], 2).'</td>';
                                            echo '<td> PHP '.number_format($total_income, 2).'</td>';
                                            echo '<td></td>';
                                        echo '</tr>';
                            ?>          
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            <tr id="empty_record_tr" style="display: none;">
                                <td style="text-align: center;" colspan="8">
                                    <p id="empty_record" class="text-secondary"></p>
                                </td>
                            </tr>
                        </tfoot>
                            <?php } }?>
                    </table>
                </div>
            </div>
        </div> 		
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/export_weekly.js"></script>
    <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.0.37/jspdf.plugin.autotable.js"></script> 

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

<script>
$(document).ready(function(){
    $("#search_input").on("keyup", function(){
        var value = $(this).val().toLowerCase();
        $("#records_table tr").filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
<script src="assets/js/no_results.js"></script>
</html>
<?php 
}else{
     header("Location: ../functions/logout.php");
     exit();
}
?>