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

        $limit = isset($_POST['limit-records']) ? $_POST["limit-records"]: 100;
        $page = isset($_GET['page']) ? $_GET['page']: 1;
        $start = ($page - 1) * $limit; 
        $orders = join_orders_table($limit, $start);

        $result1 = mysqli_query($conn, "SELECT count(id) AS id FROM orders"); 
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
                            <th style="text-align: center;">Order ID</th>
                            <th>Customer Name</th>
                            <th>Payment Method</th>
                            <th>Notes</th>
                            <th>Date</th>
                            <th class="sorttable_nosort" style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id = "order_table">
                        <tr>
                            <?php 
                                foreach($orders as $order):
                            ?>
                            <td style="width: 7%; text-align: center;">
                                <a href="../records_page/all_orders_details.php?id=<?php echo $order['id']?>"><?php echo $order['id']?></a>
                            </td>
                            <td style="width: 10%;"><?php echo $order['customer']?></td>
                            <td style="width: 15%;"><?php echo $order['paymethod']?></td>
                            <td style="width: 15%;"><?php echo $order['notes']?></td>
                            <td style="width: 10%;"><?php echo $order['date']?></td>
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
                        <tr id="empty_record_tr" >
                            <td style="text-align: center;" colspan="6">
                                <p id="empty_record" class="text-secondary"></p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <footer class="d-flex justify-content-between align-items-center">
        <?PHP
          $NUMPERPAGE = 20; // max. number of items to display per page
          $this_page = '';
          $data = range(1, $pages * 20); // data array to be paginated
          $num_results = count($data);
        ?>
        <nav>    
            <ul class="pagination"> 
        <?PHP
          if(!isset($_GET['page']) || !$page = intval($_GET['page'])) {
            $page = 1;
          }

          // extra variables to append to navigation links (optional)
          $linkextra = [];
          if(isset($_GET['var1']) && $var1 = $_GET['var1']) { // repeat as needed for each extra variable
            $linkextra[] = "var1=" . urlencode($var1);
          }
          $linkextra = implode("&amp;", $linkextra);
          if($linkextra) {
            $linkextra .= "&amp;";
          }

          // build array containing links to all pages
          $tmp = [];
          for($p=1, $i=0; $i < $num_results; $p++, $i += $NUMPERPAGE) {
            if($page == $p) {
              // current page shown as bold, no link
              $tmp[] = "<li class=\"page-item\"><a class='page-link'>{$p}</a></li>";
            } else {
              $tmp[] = "<li class=\"page-item\"><a class='page-link' href=\"{$this_page}?{$linkextra}page={$p}\">{$p}</a></li>";
            }
          }

          // thin out the links (optional)
          for($i = count($tmp) - 3; $i > 1; $i--) {
            if(abs($page - $i - 1) > 2) {
              unset($tmp[$i]);
            }
          }

          // display page navigation iff data covers more than one page
          if(count($tmp) > 1) {
            echo "<p>";

            if($page > 1) {
              // display 'Prev' link
                echo "<li class=\"page-item <?php echo $page == 1 ? 'disabled' : ''; ?>\">";
                echo "<a class='page-link' aria-label=\"Previous\" href=\"{$this_page}?{$linkextra}page=" . ($page - 1) . "\"><span aria-hidden='true'> « </span></a></li>";
            }

            $lastlink = 0;
            foreach($tmp as $i => $link) {
              if($i > $lastlink + 1) {
                echo "<li class=\"page-item\"><a class='page-link'>...</a></li>"; // where one or more links have been omitted
              }
              echo $link;
              $lastlink = $i;
            }

            if($page <= $lastlink) {
              // display 'Next' link
                echo "<li class=\"page-item <?php echo $page == $pages ? 'disabled' : ''; ?>\">";
                echo "<a class='page-link' aria-label=\"Next\" href=\"{$this_page}?{$linkextra}page=" . ($page + 1) . "\"><span aria-hidden='true'> » </span></a></li>";
            }

            echo "</ul>";
            echo "</nav>";
          }
        ?>
            </ul>
        </nav>
    </footer>

    <!-- EDIT ORDERS MODAL -->

    <div class="modal fade" role="dialog" tabindex="-1" id="edit-order-modal">
        <div class="modal-dialog modal-sm" role="document">
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
                                <p style="margin: 0px;">Customer Name</p>
                                <input type="text" id="eocustomer" name="eocustomer">
                                <p style="margin: 0px;">Payment Method</p>
                                <input type="text" id="eopayment" name="eopayment">
                                <p style="margin: 0px;">Notes</p>
                                <input type="text" id="eonotes" name="eonotes">
                                <p style="margin: 0px;">Date</p>
                                <input type="text" id="eodateinvi" name="eodate" disabled>
                                <input style="display: none" type="text" id="eodate" name="eodate">
                            </div>
                            <input type="text" id="eoid" name="eoid" style="width: 1%; display: none">
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
                            <input class="d-none" type="text" id="doid" name="doid" style="width: 100%;">
                        </p>
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
    <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function(){
        
        $("#search_input").on("keyup",function(){
            var search = $(this).val();

            if($("#order_table tr:visible").length < 1){
                $("#empty_record_tr").show();
                $("#empty_record").empty().append("No results found.");
            }else{
                $("#empty_record_tr").hide();
            }

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
            $('#edit-order-modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();

            console.log(data);

            $('#eoid').val(data[0]);
            $('#eocustomer').val(data[1]);
            $('#eopayment').val(data[2]);
            $('#eonotes').val(data[3]);
            $('#eodate').val(data[4]);
            $('#eodateinvi').val(data[4]);
        });

        $('.deletebtn').on('click', function(){
            $('#delete-order-modal').modal('show');

            $tr1 = $(this).closest('tr');

            var dataD = $tr1.children("td").map(function(){
                return $(this).text();
            }).get();

            console.log(dataD);

            $('#doid').val(dataD[0]);
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