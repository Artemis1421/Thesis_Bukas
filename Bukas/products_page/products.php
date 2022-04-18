<!DOCTYPE html>
<html lang="en">
<?php 
    include '../functions/users.php';

    $user_level = $_SESSION['user_level'];
    if (isset($_SESSION['id']) && isset($_SESSION['business_name']) && $user_level == 1 || $user_level == 2){
        include '../functions/user_conn.php';
        include '../functions/log_runner.php';

        $business_session = sanitize($_SESSION['business_name']);

        $limit = isset($_POST['limit-records']) ? $_POST["limit-records"]: 20;
        $page = isset($_GET['page']) ? $_GET['page']: 1;
        $start = ($page - 1) * $limit; 
        $products = join_product_table($limit, $start);

        $result1 = mysqli_query($conn, "SELECT count(id) AS id FROM products"); 
        $prodCount = $result1->fetch_all(MYSQLI_ASSOC);
        $total = $prodCount[0]['id'];
        $pages = ceil($total/$limit);

        $Previous = $page - 1;
        $Next = $page + 1;
 ?>
<head>
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Products</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
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
    <?php
        $sql = mysqli_query($conn,"SELECT * FROM categories WHERE deleted != 1");
        $count = mysqli_num_rows($sql);
    ?>
    <div style="margin: 12px;padding: 12px;">
        <div class="row" style="padding-bottom: 12px;">
            <div class="col">
                <header>
                    <div class="col d-flex justify-content-between align-items-md-center">
                        <div class="d-flex align-items-center"></div>
                            <div style="width: 35%;">
                                <input type="text" id="search_input" placeholder="Search" style="width: 100%;border-radius: 10px;padding-left: 8px;padding-right: 8px;border-width: 1px;border-color: #0a2635;padding-top: 2px;">
                            </div>
                        <div>
                            <button id="categoryButton" class="btn" data-bs-toggle="modal" data-bss-tooltip="" type="button" style="background: #4ecf63;color: #0a2635;font-weight: bold;" data-bs-target="#add-category-modal">Category</button>
                            <button id="addButton" class="btn" type="button" style="background: #4ecf63;color: #0a2635;font-weight: bold;" data-bs-target="#add-product-modal" data-bs-toggle="modal" data-count="<?php echo $count?>">Add Product</button>
                            <button id="importButton" class="btn" type="button" style="background: #4ecf63;color: #0a2635;font-weight: bold;" data-bs-target="#import-product-modal" data-bs-toggle="modal">Import Products</button>
                        </div>
                    </div>
                </header>
            </div>
        </div>
    </div>

    <!--Empty Category Toast-->
    <div id="errors-toast" class="toast fade hide fixed-top top-0 start-50 translate-middle-x bg-danger" role="alert" style="margin:12px 0px">
            <div role="alert" class="toast-body text-danger d-flex justify-content-between">
                <p id="error-message" class="text-white"></p>
                <button class="btn-close ms-2 mb-1 close" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table sortable" id ="ptable">
                    <thead>
                        <tr>
                            <th class="sorttable_nosort" style="width: 10%;">Thumbnail</th>
                            <th style="display: none;">Product ID</th>
                            <th>Product Name</th>
                            <th>Attribute</th>
                            <th style="display: none;">Category ID</th>
                            <th>Categories</th>
                            <th>SKU</th>
                            <th>Selling Price</th>
                            <th>Cost Price</th>
                            <th class="sorttable_nosort">Action</th>
                        </tr>
                    </thead>
                    <tbody id = "products_table">
                            <?php 
                                foreach ($products as $product):
                            ?>
                            <script>
                                var businessVar = '<?=$business_session?>';
                            </script>   
                        <tr>
                            <td style="width: 10%;">
                                <img class="d-md-inline" style="width: 150px;height: 100px" src="<?php echo '../assets/'.$business_session.'/products/'.$product['image']; ?>" width="75%">
                                <p class="invisible" style="width: 1px;height: 1px;margin: 0px;"><?php echo $product['image'] ?></p>
                            </td> 
                            <td style="display: none;" id = "p_id"><?php echo $product['id'];?></td>
                            <td style="width: 15%;" id = "p_nme"><?php echo $product['product_name'];?></td>
                            <td style="width: 15%;" id = "p_att"><?php echo $product['product_attribute'];?></td>
                            <td style="display: none;" id = "p_cat_id"><?php echo $product['category_id'];?></td>
                            <td style="width: 15%;" id = "p_cat"><?php echo $product['category_name'];?></td>
                            <td style="width: 15%;" id = "p_sku"><?php echo $product['product_sku'];?></td>
                            <td style="width: 10%;" id = "p_prc"><?php echo $product['product_price'];?></td>
                            <td style="width: 10%;" id = "p_cprc"><?php echo $product['product_cprice']; ?></td>
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
                        <tr id="empty_products_tr" >
                            <td style="text-align: center;" colspan="10">
                                <p id="empty_product" class="text-secondary"></p>
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
                    <a class="page-link" href="products.php?page=<?=$Previous?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                <?php for ($i = 1; $i <= $pages; $i++) :
                ?>
                    <li class="page-item">
                        <a class="page-link" href="products.php?page=<?=$i;?>"><?=$i;?></a></li>
                    <?php endfor;?>
                <li class="page-item <?php echo $page == $pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="products.php?page=<?=$Next?>" aria-label="Next"><span aria-hidden="true">»</span></a></li>
            </ul>
        </nav>
    </footer>

<!-- ADD PRODUCT MODAL -->

<div class="modal fade" role="dialog" tabindex="-1" id="add-product-modal">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Product</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="../products_page/products_validation.php" enctype="multipart/form-data">
            <div class="modal-body d-flex justify-content-md-center">
                <div class="row d-flex flex-column">
                    <div class="col d-flex flex-column align-items-center">
                    <img id="productImage" src='../assets/img/BrandLogo.png' width="50%" style="margin-bottom: 12px;">
                        <div class="d-flex justify-content-center">
                            <input class="form-control-sm form-control" type="file" name="uploadFile" style="margin: 0px 0px 12px;width: 70%;">
                        </div>
                    </div>
                    <div class="col d-flex flex-column align-items-center">
                        <div>
                            <p style="margin: 0px;">Product Name</p>
                            <input type="text" name="pname" required="">
                        </div>
                        <div>
                            <p style="margin: 0px;">Attribute</p>
                            <input type="text" name="pattribute" required="">
                        </div>
                        <div>
                            <p style="margin: 0px;">Category</p>
                            <select required="" name="pcategories" style="padding: 1px 2px;width: 100%;">
                                <option value="">Select Category</option>
                                <?php 
                                    include '../functions/user_conn.php';
                                    $sql = "SELECT * FROM categories WHERE deleted != 1 ORDER BY id";
                                    if($result = mysqli_query($conn, $sql)):
                                        while($row = mysqli_fetch_assoc($result)):
                                ?>
                                <option value=" <?php echo $row['id']; ?>"><?php echo $row['category_name']; ?></option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <p style="margin: 0px;">Selling Price</p>
                            <input type="number" step="any" name="pprice" required="" min="0.01">
                        </div>
                        <div>
                            <p style="margin: 0px;">Cost Price</p>
                            <input type="number" placeholder="Optional" step="any" name="pcprice" min="0">
                        </div>
                        <div>
                            <p style="margin: 0px;">Stock</p>
                            <input type="number" name="pstock" required="" min="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" name="addButton" type="submit">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>

<!-- IMPORT PRODUCTS MODAL -->

<div class="modal fade" role="dialog" tabindex="-1" id="import-product-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Import Products</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="../products_page/products_validation.php" enctype="multipart/form-data">
            <div class="modal-body">
                <p><strong>How to</strong> <strong>-</strong>&nbsp;Import multiple products</p>
                <p><strong>1.</strong>&nbsp;Download CSV Template</p>
                    <button class="btn btn-primary" type="submit" style="width: 100%" name="downloadCSVFile">Download CSV File</button>
                <p style="margin-top: 12px;">
                    <strong>2.</strong> Fill-up the necessary details<br>- <em>Product Name</em><br>- <em>Attribute</em><br>- <em>SKU</em><br>- <em>Stock</em><br>- <em>Selling Price</em><br>- <em>Cost Price</em><br>- <em>Category</em><br></p>
                <p style="margin-top: 12px;"><strong>3.</strong>&nbsp;Upload CSV File</p>
                <input class="form-control-sm form-control" type="file" name="uploadCSVFile">
                <p style="margin-top: 12px;">
                    <i>*Note: Please re-download the template everytime that you want to import products. Do not re-use previously used templates.</i>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit" name="importButton">Save</button></div>
            </form>
        </div>
    </div>
</div>


<!-- EDIT PRODUCT MODAL -->

<div class="modal fade" role="dialog" tabindex="-1" id="edit-product-modal">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Product</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="../products_page/products_validation.php" enctype="multipart/form-data">
            <div class="modal-body d-flex flex-column align-items-xl-center">
                <div class="row d-flex flex-column">
                    <div class="col d-flex flex-column align-items-center">
                    <img id="epthumbnail" src='assets/img/3x.gif' width="50%" style="margin-bottom: 12px;">
                        <div class="d-flex justify-content-center">
                            <input class="form-control-sm form-control" type="file" name="euploadFile" style="margin: 0px 0px 12px;width: 70%;">
                        </div>
                    </div>
                    <div class="col d-flex flex-column align-items-center">
                        <div>
                            <p style="display: none">Product ID</p>
                            <input style="display: none" type="number" id="epid" name="epid" readonly="">
                        </div>
                        <div>
                            <p style="margin: 0px;">Product Name</p>
                            <input type="text" id="epname" name="epname">
                        </div>
                        <div>
                            <p style="margin: 0px;">Attribute</p>
                            <input type="text" id="epattribute" name="epattribute">
                        </div>
                        <div>
                            <p style="margin: 0px;">Category</p>
                            <select id="edit_categories" name="epcategories" style="padding: 1px 2px;width: 100%;" required="">
                                <option value="">Select Category</option>
                                <?php 
                                    include '../functions/user_conn.php';
                                    $sql = "SELECT * FROM categories WHERE deleted != 1 ORDER BY id";
                                    if($result = mysqli_query($conn, $sql)):
                                        while($row = mysqli_fetch_assoc($result)):
                                ?>
                                <option value=" <?php echo $row['id']; ?>"><?php echo $row['category_name']; ?></option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <p style="margin: 0px;">SKU</p>
                            <input type="text" id="epsku" name="epsku">
                        </div>
                        <div>
                            <p style="margin: 0px;">Selling Price</p>
                            <input type="number" step="any" id="epprice" name="epprice" min="0.01">
                        </div>
                        <div>
                            <p style="margin: 0px;">Cost Price</p>
                            <input type="number" step="any" id="epcprice" name="epcprice" min="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" name="updateButton" type="submit">Update</button>
            </div>
        </div>
        </form>
    </div>
</div>

<!-- DELETE PRODUCT MODAL -->

<div class="modal fade" role="dialog" tabindex="-1" id="delete-product-modal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Product</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="../products_page/products_validation.php">
                <div class="modal-body">
                <p style="margin: 0px;">Are you sure? This process is irreversible.
                    <input class="invisible" type="text" id="dpid" name="dpid" style="width: 10%;">
                    <input class="invisible" type="text" id="dpname" name="dpname" style="width: 10%;"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" name="deleteButton" type="submit" style="background: var(--bs-red);">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CATEGORY MODAL -->

<div class="modal fade" role="dialog" tabindex="-1" id="add-category-modal">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Categories</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="../products_page/category_validation.php">
                <div class="row" style="border-bottom: 1px solid rgb(207,207,207);padding: 0px 0px 12px;">
                    <div class="col d-flex justify-content-between">
                        <input type="text" name="cname" placeholder="Add new category" style="width: 90%;padding-left: 6px;" required="">
                        <button class="btn" name="addButton" type="submit" style="background: #4ecf63;color: #0a2635;margin: 0px 2px;"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                </form>
                <div style="padding: 12px 0px;">
                    <div class="table-responsive">
                        <table class="table" id="categories_table">
                            <thead>
                                <tr>
                                    <th>Category Name</th>
                                    <th class="invisible">Category ID</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="empty_tr" >
                                    <td style="text-align: center;" colspan="3">
                                        <p id="empty_category" class="text-secondary"></p>
                                    </td>
                                </tr>
                                <?php 
                                    include '../functions/user_conn.php';
                                    $sql = "SELECT * FROM categories WHERE deleted != 1 ORDER BY id";
                                    if($result = mysqli_query($conn, $sql)):
                                        while($row = mysqli_fetch_assoc($result)):
                                ?>
                                <!-- Category Modal #2 -->
                                <tr>
                                    <td id="category"><?php echo $row['category_name'] ?></td>
                                    <td class="invisible"><?php echo $row['id']?></td>
                                    <td class="text-end">
                                        <div>
                                            <button class="btn editcbtn" type="button" style="background: #fdb750;color: #0a2635;margin: 0px 2px;">
                                                <i class="fa fa-edit"></i></button>
                                            <button class="btn deletecbtn" type="button" style="background: #cf4e4e;color: #0a2635;margin: 0px 2px;">
                                                <i class="fa fa-remove"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DELETE CATEGORY MODAL -->

<div class="modal fade" role="dialog" tabindex="-1" id="delete-category-modal">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Category</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="../products_page/products_validation.php">
                <div class="modal-body">
                <p style="margin: 0px;">Are you sure? This process is irreversible.
                    <input class="invisible" type="text" id="dcname" name="dcname" style="width: 10%;">
                    <input class="invisible" type="text" id="dcid" name="dcid" style="width: 10%;">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" name="deletecButton" type="submit" style="background: var(--bs-red);">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT CATEGORY MODAL -->

<div class="modal fade" role="dialog" tabindex="-1" id="edit-category-modal">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="../products_page/products_validation.php">
            <div class="modal-body d-flex flex-column align-items-xl-center">
                <div class="row d-flex flex-column">
                    <div class="col d-flex flex-column">
                        <div>
                            <p style="margin: 0px;">Category Name</p>
                            <input type="text" id="ecname" name="ecname">
                        </div>
                        <input class="invisible" type="text" id="ecid" name="ecid" style="width: 1%;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" name="updatecButton" type="submit">Update</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/bootstrap/js/search.js"></script>
<script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function(){
        var categories = $("#categories_table #category").length;
        console.log(categories);
        if(categories < 1){
            $("#empty_tr").show();
            $("#empty_category").empty().append("You have no existing category.");
        }else{
            $("#empty_tr").hide();
        }
        
        $("#search_input").on("keyup",function(){
            var search = $(this).val();

            if($("#products_table tr:visible").length < 1){
                $("#empty_products_tr").show();
                $("#empty_product").empty().append("No results found.");
            }else{
                $("#empty_products_tr").hide();
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
            $('#edit-product-modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();

            console.log(data);

            var thumb = data[0].trim();
            var category = data[4];

            $('#epThumbnail').val(data[0]);
            $('#epid').val(data[1]);
            $('#epname').val(data[2]);
            $('#epattribute').val(data[3]);
            $('#epcatid').val(data[4]);
            $('#epcategories').val(data[5]);
            $('#epsku').val(data[6]);
            $('#epprice').val(data[7]);
            $('#epcprice').val(data[8]);

            console.log(category);

            document.getElementById("epthumbnail").src = '../assets/'.concat(businessVar, '/products/', thumb);
            document.getElementById('edit_categories').getElementsByTagName('option')[category].selected = 'selected';
        });

        $('.deletebtn').on('click', function(){
            $('#delete-product-modal').modal('show');

            $tr1 = $(this).closest('tr');

            var dataD = $tr1.children("td").map(function(){
                return $(this).text();
            }).get();

            console.log(dataD);

            $('#dpid').val(dataD[1]);
            $('#dpname').val(dataD[2]);
        });

        $('.editcbtn').on('click', function(){
            $('#edit-category-modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();

            console.log(data);

            $('#ecname').val(data[0]);
            $('#ecid').val(data[1]);
        });

        $('.deletecbtn').on('click', function(){
            $('#delete-category-modal').modal('show');

            $tr1 = $(this).closest('tr');

            var dataD = $tr1.children("td").map(function(){
                return $(this).text();
            }).get();

            console.log(dataD);

            $('#dcname').val(dataD[0]);
            $('#dcid').val(dataD[1]);
        });
    });
</script>
</body>
<script src="assets/js/bukas.products.button.js"></script>
</html>
<?php 
}else{
     header("Location: ../functions/logout.php");
     exit();
}
?>