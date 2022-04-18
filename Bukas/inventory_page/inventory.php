<!DOCTYPE html>
<html lang="en">
<?php 
    include '../functions/conn.php';
    include '../functions/users.php';

    $user_level = $_SESSION['user_level'];
    if (isset($_SESSION['id']) && isset($_SESSION['business_name']) && $user_level == 1 || $user_level == 2 || $user_level == 4){
        include '../functions/user_conn.php';
        include '../functions/log_runner.php';

        $business_session = sanitize($_SESSION['business_name']);

        $limit = isset($_POST['limit-records']) ? $_POST["limit-records"]: 20;
        $page = isset($_GET['page']) ? $_GET['page']: 1;
        $start = ($page - 1) * $limit; 
        $inventory = join_inventory_table($limit, $start);

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
    <title>Inventory</title>
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
                    <div class="col d-flex justify-content-between align-items-md-center">
                        <div class="d-flex align-items-center"></div>
                            <div style="width: 35%;">
                                <input type="text" id="search_input" placeholder="Search" style="width: 100%;border-radius: 10px;padding-left: 8px;padding-right: 8px;border-width: 1px;border-color: #0a2635;padding-top: 2px;">
                            </div> 
                        <div>
                            <div></div>
                        </div>  
                    </div>
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
                            <th class="sorttable_nosort">Thumbnail</th>
                            <th style="display: none;">Product ID</th>
                            <th>Product Name</th>
                            <th>Category Name</th>
                            <th>SKU</th>
                            <th>Stock</th>
                            <th class="sorttable_nosort" style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id = "inventory_table">
                        <tr>
                            <?php 
                                foreach($inventory as $invent):
                            ?>
                            <td style="width: 10%;"><img class="d-md-inline" style="width: 150px;height: 100px" src="<?php echo '../assets/'.$business_session.'/products/'.$invent['image']; ?>" width="75%"></td>
                            <td style="display: none;"><?php echo $invent['id']?></td>
                            <td style="width: 15%;"><?php echo $invent['product_name']?></td>
                            <td style="width: 15%;"><?php echo $invent['category_name']?></td>
                            <td style="width: 15%;"><?php echo $invent['product_sku']?></td>
                            <td style="width: 10%;"><?php echo $invent['product_qty']?></td>
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
                    <tfoot>
                        <tr id="empty_record_tr" style="display: none;">
                            <td style="text-align: center;" colspan="8">
                                <p id="empty_record" class="text-secondary"></p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <footer class="d-flex justify-content-between align-items-center">
        <nav>
            <ul class="pagination">     
                <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="inventory.php?page=<?=$Previous?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                <?php for ($i = 1; $i <= $pages; $i++) :
                ?>
                    <li class="page-item">
                        <a class="page-link" href="inventory.php?page=<?=$i;?>"><?=$i;?></a></li>
                    <?php endfor;?>
                <li class="page-item <?php echo $page == $pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="inventory.php?page=<?=$Next?>" aria-label="Next"><span aria-hidden="true">»</span></a></li>
            </ul>
        </nav>
    </footer>

    <!-- EDIT STOCK MODAL -->

    <div class="modal fade" role="dialog" tabindex="-1" id="edit-stock-modal">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Stock</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="../inventory_page/inventory_validation.php">
                <div class="modal-body d-flex flex-column align-items-xl-center">
                    <div class="row d-flex flex-column">
                        <div class="col d-flex flex-column">
                            <div>
                                <p style="margin: 0px;">Product Name</p>
                                <input type="text" id="einame2" name="einame2" disabled>
                                <input style="display: none" type="text" id="einame" name="einame" readonly>
                                <p style="margin: 0px;">Stock</p>
                                <input type="number" id="eistock" name="eistock" min="0">
                            </div>
                            <input class="invisible" type="text" id="eiid" name="eiid" style="width: 1%;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" name="updateButton" type="submit">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DELETE STOCK MODAL -->
    
    <div class="modal fade" role="dialog" tabindex="-1" id="delete-stock-modal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="../inventory_page/inventory_validation.php">
                    <div class="modal-body">
                    <p style="margin: 0px;">Are you sure? This process is irreversible.
                        <input class="invisible" type="text" id="diid" name="diid" style="width: 10%;">
                        <input class="invisible" type="text" id="diname" name="diname" style="width: 10%;"></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" name="deleteButton" type="submit" style="background: var(--bs-red);">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/bootstrap/js/search.js"></script>
    <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function(){
        $("#search_input").on("keyup",function(){
            var search = $(this).val();
            console.log(search);
            if(search.length > 1){
                $(".pagination").hide();
            }else{
                $(".pagination").show();
            }
        })
        
        $("#limit-records").change(function(){
            $('#limiting').submit();
        })

        $('.editbtn').on('click', function(){
            $('#edit-stock-modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();

            console.log(data);

            $('#eiid').val(data[1]);
            $('#einame').val(data[2]);
            $('#einame2').val(data[2]);
            $('#eistock').val(data[5]);
        });

        $('.deletebtn').on('click', function(){
            $('#delete-stock-modal').modal('show');

            $tr1 = $(this).closest('tr');

            var dataD = $tr1.children("td").map(function(){
                return $(this).text();
            }).get();

            console.log(dataD);

            $('#diid').val(dataD[1]);
            $('#diname').val(dataD[2]);
        });

        $("#search_input").on("keyup", function(){
            var value = $(this).val().toLowerCase();
            $("#inventory_table tr").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
</body>
<script src="assets/bootstrap/js/no_results.js"></script>
</html>
<?php 
}else{
     header("Location: ../functions/logout.php");
     exit();
}
?>