<!DOCTYPE html>
<html lang="en" style="height: 85%;">
<?php
include '../functions/users.php';

$user_level = $_SESSION['user_level'];
if (isset($_SESSION['id']) && isset($_SESSION['business_name']) && $user_level == 0 || $user_level == 1 || $user_level == 2 || $user_level == 4) {
    include '../functions/user_conn.php';
    include '../functions/log_runner.php';

    $business_session = sanitize($_SESSION['business_name']);
?>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <title>Transactions</title>
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/styles.min.css">
        <link rel="stylesheet" href="assets/css/styless.css">
        <link rel="stylesheet" href="assets/css/highlight.css">
        <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
        <link rel="icon" href="../landing_page/assets/img/BrandLogo.png">
        
        <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    </head>
    <?php
    //0 - Employee
    //1 - Owner
    //2 - Admin
    //3 - Accountant
    //4 - Manager
    if ($_SESSION['user_level'] == 0) {
        include '../includes/Navbar_employee.html';
    } elseif ($_SESSION['user_level'] == 1) {
        include '../includes/Navbar.html';
    } elseif ($_SESSION['user_level'] == 2) {
        include '../includes/Navbar.html';
    } elseif ($_SESSION['user_level'] == 3) {
        include '../includes/Navbar_accountant.html';
    } elseif ($_SESSION['user_level'] == 4) {
        include '../includes/Navbar_manager.html';
    }
    ?>

    <body style="height: 100%;">
        <div style="height: 100%;margin: 0px 12px;">
            <button id="how-to-toggle" class="btn btn-info position-absolute bottom-50 end-0" data-bs-target="#how-to-toast" data-bs-toggle="toast">
                <i class="fas fa-info-circle" bs-cut="1"></i>
            </button>
            <div class="row" style="height: 100%;width: 100%; height: 100%;width: 100%;margin-left: 0px;margin-right: 0px;">
                <div class="col-md-8 d-flex flex-column" style="width: 30%;margin-top: 12px; border-right-width: 1px; border-right-style: solid; ">
                    <div class="vstack" style="height: 70%;">
                        <div class="table-responsive" style="height: 100%;" id="tables">
                            <table class="table table-sm table-hover" id="orderList">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th style="text-align: center">Quantity</th>
                                        <th style="text-align: right">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Summary</td>
                                        <td> </td>
                                        <td class="text-end" colspan="2" id="total"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row d-flex flex-md-column flex-lg-row calculator" style="height: 30%;margin-bottom: 12px;margin-left: 0px;margin-right: 0px;">
                        <div id="checkout" class="col" style="padding: 0px;">
                            <button class="btn" id="checkout-button" type="button" style="background: #0a2635;color: rgb(255,255,255);height: 100%;width: 100%;" data-bs-target="#checkout-modal" data-bs-toggle="modal">Checkout</button>
                        </div>
                        <div class="col" id="numpad" style="padding: 0px;">
                            <div class="btn-toolbar" style="height: 100%;">
                                <div class="btn-group" role="group" style="width: 100%;">
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="number">1</button>
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="number">2</button>
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="number">3</button>
                                    <button id="qty" class="btn" type="button" style="background: #fdb750;width: 30%;color: #0a2635;" data-action="quantity">Qty</button>
                                </div>
                                <div class="btn-group" role="group" style="width: 100%;color: #0a2635;">
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="number">4</button>
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="number">5</button>
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="number">6</button>
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 30%;border-radius: 0px;color: #0a2635;"></button>
                                </div>
                                <div class="btn-group" role="group" style="width: 100%;color: #0a2635;">
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="number">7</button>
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="number">8</button>
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="number">9</button>
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 30%;border-radius: 0px;color: #0a2635;" data-action="clear">Clear</button>
                                </div>
                                <div class="btn-group" role="group" style="width: 100%;color: #0a2635;">
                                    <button id="plus" class="btn btn-sm" type="button" style="background: #d2d2d2;width: 20%;color: #0a2635;"></button>
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="number">0</button>
                                    <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;" data-action="decimal">.</button>
                                    <button id="btnDelete" name="deleteButton" class="btn" type="button" style="background: #d2d2d2;width: 30%;border-radius: 0px 0px 4px 0px;color: #0a2635;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-backspace">
                                            <path d="M5.83 5.146a.5.5 0 0 0 0 .708L7.975 8l-2.147 2.146a.5.5 0 0 0 .707.708l2.147-2.147 2.146 2.147a.5.5 0 0 0 .707-.708L9.39 8l2.146-2.146a.5.5 0 0 0-.707-.708L8.683 7.293 6.536 5.146a.5.5 0 0 0-.707 0z"></path>
                                            <path d="M13.683 1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7.08a2 2 0 0 1-1.519-.698L.241 8.65a1 1 0 0 1 0-1.302L5.084 1.7A2 2 0 0 1 6.603 1h7.08zm-7.08 1a1 1 0 0 0-.76.35L1 8l4.844 5.65a1 1 0 0 0 .759.35h7.08a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1h-7.08z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8" style="width: 70%;margin-top: 12px; padding-right: 0px;">
                    <div style="height: 100%;">
                        <ul class="nav nav-tabs" role="tablist">
                            <?php
                            $num_category = category_row_count();
                            $category = select_all_category();
                            foreach ($category as $categories) :
                            ?>
                                <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-bs-toggle="tab" href="#tab-<?php echo $categories['id'] ?>"><?php echo $categories['category_name'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content" style="height: 90%;">
                            <?php foreach ($category as $prod) : ?>
                                <div class="tab-pane " role="tabpanel" id="tab-<?php echo $prod['id'] ?>" style="height: 100%;">
                                    <div id="error_div" class="d-flex align-items-center justify-content-center">
                                        <h3 id="error_message" class="text-secondary" style="text-align: center;">
                                            No available product.
                                        </h3>
                                    </div>    
                                    <?php $products = join_product_table_by_category($prod['id']); ?>
                                    <div class="row row-cols-5 transactRows">
                                        <?php $i = 1;
                                        foreach ($products as $product) : if ($i++ > 31) break; ?>
                                            <div class="col" style="width: 20%; padding-bottom: 25px;">
                                                <?php $img = $product['image']; ?>
                                                <button id="addRow" name="button<?php echo $product['id'] ?>" value="<?php echo $product['product_price']; ?>" class="btn" type="button" style="background: url('../assets/<?php echo $business_session ?>/products/<?php echo $img ?>') center / cover no-repeat, #000000;color: var(--bs-red);width: 100%; height: 100px;" data-quantity="<?php echo $product['product_qty'] ?>">
                                                    <?php echo $product['product_name'] ?>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--CHECKOUT MODAL-->
        <div class="modal fade" role="dialog" tabindex="-1" id="checkout-modal">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 style="color: #0a2635;">Checkout</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container" style="height: 100%;">
                            <div class="row" style="height: 100%;">
                                <div class="col-md-6" style="border-right: 1px solid rgb(181,184,187) ;">
                                    <div class="vstack" style="height: 100%;">
                                        <div class="table-responsive" id="payTable">
                                            <table class="table table-sm" id="cashout">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th style="text-align: center">Quantity</th>
                                                        <th style="text-align: right">Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbodyid">
                                                </tbody>
                                                <tfoot>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col d-flex flex-column">
                                            <p>Amount to Enter</p>
                                            <input type="number" style="margin-bottom: 16px;" id="enteredAmount" name="enteredAmount" oninput="disableButton()">
                                            <p>Customer Name</p>
                                            <input type="text" style="margin-bottom: 16px;" id="customerName" name="customerName" placeholder="Optional">
                                            <div class="btn-group" role="group">
                                                <input type="radio" checked id="btnradio-1" class="btn-check" name="btnradio" autocomplete="off" value="Cash">
                                                <label class="form-label btn btn-outline-primary" for="btnradio-1">Cash</label>
                                                <input type="radio" id="btnradio-2" class="btn-check" name="btnradio" autocomplete="off" value="G-Cash">
                                                <label class="form-label btn btn-outline-primary" for="btnradio-2">Gcash</label>
                                                <input type="radio" id="btnradio-3" class="btn-check" name="btnradio" autocomplete="off" data-bs-toggle="collapse" data-bs-target="#other-payment">
                                                <label class="form-label btn btn-outline-primary" for="btnradio-3">Others</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col d-flex flex-row justify-content-center">
                                            <input type="text" id="other-payment" class="collapse" placeholder="Other payment method" style="width: 100%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="sbumit" style="width: 100px;" data-bs-target="#confirm-modal" id="payment" data-bs-toggle="modal" disabled>Pay</button>
                    </div>
                </div>
            </div>
        </div>
        <!--CONFIRM MODAL -->
        <div class="modal fade" role="dialog" tabindex="-1" id="confirm-modal">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Confirm Payment</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between">
                            <p>Total Price</p>
                            <p id="totalPrice"></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>Payment Amount</p>
                            <p id="totalPayment"></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>Mode of Payment</p>
                            <p id="modeOfPayment"></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>Change</p>
                            <p id="change"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Back</button>
                        <input class="btn btn-primary" type="button" id="pay" name="pay" value="Confirm" style="border-color: #ffffff;">
                    </div>
                </div>
            </div>
            <!--Checkout Errors Toast-->
            <div id="errors-toast" class="toast fade hide fixed-top top-0 start-50 translate-middle-x bg-danger" role="alert" style="margin:12px 0px">
                <div role="alert" class="toast-body text-danger d-flex justify-content-between">
                    <p id="error-message" class="text-white" style="font-weight: bold;"></p>
                    <button class="btn-close ms-2 mb-1 close" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
        
        <!--Insufficient Error Toast-->
        <div id="insufficient-toast" class="toast fade hide fixed-top top-0 start-50 translate-middle-x bg-danger" role="alert" style="margin:12px 0px" data-bs-delay="2000">
            <div role="alert" class="toast-body text-danger d-flex justify-content-between">
                <p class="text-white">
                    Current stock is less than entered quantity!
                    <br />
                    Current <span id="insufficientNameError" style="font-weight: bold;"></span> stock: <span id="currentStockError" style="font-weight: bold;"></span>
                    <br />
                    Entered quantity: <span id="quantityError" style="font-weight: bold;"></span>
                </p>
                <button class="btn-close ms-2 mb-1 close" data-bs-dismiss="toast"></button>
            </div>
        </div>
        <!--How To POS Toast -->
        <div role="alert" data-bs-autohide="false" class="toast fade hide" id="how-to-toast" style="margin:12px 24px; position:absolute; top:48px; right:0;">
            <div class="toast-header bg-info">
                <strong class="me-auto text-white">How to - Point of Sale</strong>
                <button class="btn-close ms-2 mb-1 close" data-bs-dismiss="toast"></button>
            </div>
            <div role="alert" class="toast-body">
                <p style="text-align:justify;">Welcome to the Point-of-sale page. Here is where all the transactions happen. This page allows the user to add, edit, and remove products from the shopping cart.
                    <br /><br />
                    <strong>Add a product</strong>
                    <br />
                    To add a product, look for the category tab at the top most part of the panel beside the shopping cart panel.
                    <br /><br />
                    If there are no tabs present, it means that there are no existing category or product.
                    <br /><br />
                    <strong>Edit the quantity</strong>
                    <br />
                    This system edits the last entry on the shopping cart. To change the quantity of the product, click the desired amount using the calculator on the bottom left of the page then click the QTY button to change its value.
                    <br /><br />
                    If the selected quantity does not exceed the current stock, it will update the cart, otherwise it will send an error.
                    <br /><br />
                    <strong>Remove product from the shopping cart.</strong>
                    <br />
                    The shopping cart allows the user to select a product by clicking its row. To remove a product from the shopping cart, select the desired product then click on the remove button (
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-backspace">
                        <path d="M5.83 5.146a.5.5 0 0 0 0 .708L7.975 8l-2.147 2.146a.5.5 0 0 0 .707.708l2.147-2.147 2.146 2.147a.5.5 0 0 0 .707-.708L9.39 8l2.146-2.146a.5.5 0 0 0-.707-.708L8.683 7.293 6.536 5.146a.5.5 0 0 0-.707 0z"></path>
                        <path d="M13.683 1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7.08a2 2 0 0 1-1.519-.698L.241 8.65a1 1 0 0 1 0-1.302L5.084 1.7A2 2 0 0 1 6.603 1h7.08zm-7.08 1a1 1 0 0 0-.76.35L1 8l4.844 5.65a1 1 0 0 0 .759.35h7.08a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1h-7.08z"></path>
                    </svg>
                    ).
                    <br /><br />
                    <strong>Clear the shopping cart</strong>
                    <br />
                    Clicking the clear button resets the contents of the shopping cart. 
                    <br />
                </p>
            </div>
        </div>

        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/bootstrap/js/bukas.button.function.js"></script>
        <script src="assets/bootstrap/js/table.highlights.js"></script>
        <script src="assets/bootstrap/js/bukas.payment.js"></script>
        <script src="assets/bootstrap/js/total.payment.js"></script>
        <script src="assets/bootstrap/js/bukas.price.js"></script>
        <script src="assets/bootstrap/js/bukas.confirm.js"></script>
        <script src="assets/bootstrap/js/bukas.calculator.js"></script>

        <script>
        function disableButton() {
          var x = parseFloat(document.getElementById("enteredAmount").value);
          var val =parseFloat(document.getElementById("total").innerHTML);
          if (isNaN(x) || x < val) {
          
            document.getElementById("payment").disabled = true;
            }else {
                document.getElementById("payment").disabled = false;
            }
        }
        </script>
        <script src="assets/bootstrap/js/no_results.js"></script>
    </body>

</html>
<?php
} else {
    header("Location: ../functions/logout.php");
    exit();
}
?>