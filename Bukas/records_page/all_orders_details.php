<!DOCTYPE html>
<html lang="en">
<?php 
    include '../functions/conn.php';
    include '../functions/users.php';

    $user_level = $_SESSION['user_level'];
    if (isset($_SESSION['id']) && isset($_SESSION['business_name']) && $user_level == 0 || $user_level == 1 || $user_level == 2 || $user_level == 3 || $user_level == 4){
        include '../functions/user_conn.php';
        include '../functions/log_runner.php';

        $business_session = sanitize($_SESSION['business_name']);

        $limit = isset($_POST['limit-records']) ? $_POST["limit-records"]: 1000;
        $page = isset($_GET['page']) ? $_GET['page']: 1;
        $start = ($page - 1) * $limit; 

        $id = $_GET['id'];
        $_SESSION['page_id'] = $id;

        $details = join_details_table($id, $limit, $start);

        $result1 = mysqli_query($conn, "SELECT count(id) AS id FROM products"); 
        $prodCount = $result1->fetch_all(MYSQLI_ASSOC);
        $total = $prodCount[0]['id'];
        $pages = ceil($total/$limit);

        $Previous = $page - 1;
        $Next = $page + 1;
 ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Orders</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/styles.css">
    <link rel="stylesheet" href="../assets/fonts/font-awesome.min.css">
    <link rel="icon" href="../assets/img/BrandLogo.png">
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
        <div class="row"style="padding-bottom: 12px;">
            <div class="col">
                <header>
                    <?php 
                        $order_details = join_orders_table_by_id($id);
                        foreach ($order_details as $order_detail):
                    ?>
                    <div class="col d-flex justify-content-between align-items-md-center">
                        <div class="d-flex align-items-center">
                            <div>
                                CUSTOMER: <?php echo $order_detail['customer']; ?> <br>
                                PAYMENT METHOD: <?php echo $order_detail['paymethod']; ?>
                            </div>
                            <div style="margin-left: 50px">
                                DATE: <?php echo $order_detail['date']; ?> <br>
                                NOTES: <?php echo $order_detail['notes']; ?>
                            </div>
                        </div>
                            <div style="width: 35%;">
                                <input type="text" id="search_input" placeholder="Search" style="width: 100%;border-radius: 10px;padding-left: 8px;padding-right: 8px;border-width: 1px;border-color: #0a2635;padding-top: 2px;">
                            </div> 
                        <div>
                            <div><strong>ORDER ID # <?php echo $id; ?></strong></div>
                        </div>  
                    </div>
                    <?php endforeach; ?>
                </header>
             </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table sortable" id="ptable">
                    <thead>
                        <tr>
                            <th style="text-align: center; display: none;">Order ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th class="d-none" style="width: 1%;">Product ID</th>
                            <th class="d-none" style="width: 1%;">Product Quantity</th>
                            <th class="d-none" style="width: 1%;">Product Price</th>
                            <th class="d-none" style="width: 1%;">Sales ID</th>
                            <th class="sorttable_nosort" style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id = "order_table">
                        <tr>
                            <?php 
                                foreach($details as $detail):
                            ?>
                            <td style="width: 7%; text-align: center; display: none;"><?php echo $detail['order_id']?></td>
                            <td style="width: 10%;"><?php echo $detail['product_name']?></td>
                            <td style="width: 15%;"><?php echo $detail['sales_qty']?></td>
                            <td style="width: 15%;"><?php echo 'PHP '.number_format($detail['sales_price'], 2)?></td>
                            <td class="d-none" style="width: 1%;"><?php echo $detail['pid']; ?></td>
                            <td class="d-none" style="width: 1%;"><?php echo $detail['product_qty'];?></td>
                            <td class="d-none" style="width: 1%;"><?php echo $detail['product_price'];?></td>
                            <td class="d-none" style="width: 1%;"><?php echo $detail['sid']; ?></td>
                            <td style="width: 10%;">
                                <div class="btn-group" role="group">
                                    <button class="btn editbtn" type="button" style="background: #fdb750;">
                                        <i class="fa fa-pencil" style="color: #0a2635;"></i>
                                    </button>
                                    <button class="btn deletebtn" type="button" style="background: rgb(217,68,68);">
                                        <i class="fa fa-trash" style="color: #0a2635;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- EDIT ORDERS MODAL -->

    <div class="modal fade" role="dialog" tabindex="-1" id="edit-order-modal">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Order</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="../records_page/all_orders_validation.php">
                <div class="modal-body d-flex flex-column align-items-xl-center">
                    <div class="row d-flex flex-column">
                        <div class="col d-flex flex-column">
                            <div>
                                <p style="margin: 0px;">Product Name</p>
                                <input type="text" id="eodname2" name="eodname2" disabled>
                                <input type="text" id="eodname" name="eodname" style="display: none;" readonly>
                                <p style="margin: 0px;">Current Quantity</p>
                                <input type="text" id="eodqty2" name="eodqty2" disabled>
                                <input type="text" id="eodqty" name="eodqty" style="display: none;" readonly>
                                <p style="margin: 0px;">Add Quantity</p>
                                <input id="add-limit" type="number" oninvalid="this.setCustomValidity('Not enough stocks. Please try again')" oninput="setCustomValidity('')" name="addqty" min="0" max= "" style="width: 100%">
                                <p style="margin: 0px;">Subtract Quantity</p>
                                <input id ="sub-limit" type="number" oninvalid="this.setCustomValidity('Invalid input. Cannot exceed more than sale quantity')" oninput="setCustomValidity('')" name="subqty" min="0" max= "" style="width: 100%"> 
                            </div>
                            <input type="text" id="eodid" name="eodid" style="display: none;">
                            <input type="text" id="eodpid" name="eodpid" style="display: none;">
                            <input type="text" id="eodpqty" name="eodpqty" style="display: none;">
                            <input type="text" id="eodpprice" name="eodpprice" style="display: none;">
                            <input type="text" id="eodsid" name="eodsid" style="display: none;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" name="updateDButton" type="submit">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DELETE ORDER MODAL -->
    
    <div class="modal fade" role="dialog" tabindex="-1" id="delete-order-modal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="../records_page/all_orders_validation.php">
                    <div class="modal-body">
                        <p style="margin: 0px;">Are you sure? This process is irreversible.
                            <input class="invisible" type="text" id="doid" name="doid" style="width: 10%;">
                            <input class="invisible" type="text" id="dodqty" name="dodqty" style="width: 10%;">
                            <input class="invisible" type="text" id="dodpid" name="dodpid" style="width: 10%;">
                            <input class="invisible" type="text" id="dodpqty" name="dodpqty" style="width: 10%;">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" name="deleteDButton" type="submit" style="background: var(--bs-red);">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function(){
        $('#edit-order-modal').on('hidden.bs.modal', function () {
            $(this).find('input').val('');
        })

        $("#limit-records").change(function(){
            $('#limiting').submit();
        })

        $('.editbtn').on('click', function(){
            $('#edit-order-modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();

            // console.log(data);
            var subLimit = document.querySelector('#sub-limit');
            var addLimit = document.querySelector('#add-limit');
            subLimit.setAttribute("max",data[2]);
            addLimit.setAttribute("max",data[5]);

            $('#eodid').val(data[0]);
            $('#eodname').val(data[1]);
            $('#eodname2').val(data[1]);
            $('#eodqty').val(data[2]);
            $('#eodqty2').val(data[2]);
            $('#eodpid').val(data[4]);
            $('#eodpqty').val(data[5]);
            $('#eodpprice').val(data[6]);
            $('#eodsid').val(data[7]);
        });

        $('.deletebtn').on('click', function(){
            $('#delete-order-modal').modal('show');

            $tr1 = $(this).closest('tr');

            var dataD = $tr1.children("td").map(function(){
                return $(this).text();
            }).get();

            // console.log(dataD);

            $('#dodqty').val(dataD[2]);
            $('#dodpid').val(dataD[4]);
            $('#dodpqty').val(dataD[5]);
            $('#doid').val(dataD[7]);
        });

        $("#search_input").on("keyup", function(){
            var value = $(this).val().toLowerCase();
            $("#order_table tr").filter(function(){
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