<!DOCTYPE html>
<html lang="en" style="height: 85%;">
<?php 
    include '../functions/users.php';
    if (isset($_SESSION['id']) && isset($_SESSION['business_name'])){
        include '../functions/user_conn.php';

 ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Transactions</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styless.css">
    <link rel="stylesheet" href="assets/css/highlight.css">
    <link rel="icon" href="../landing_page/assets/img/BrandLogo.png">
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    
</head>
<?php include '../includes/Navbar.html'; ?>
<body style="height: 100%;">
    <div style="height: 100%;margin: 0px 12px;">
        <div class="row" style="height: 100%;width: 100%;">
            <div class="col-md-8 d-flex flex-column" style="width: 30%;margin-top: 12px; border-right-width: 1px; border-right-style: solid; ">
                <div class="vstack" style="height: 70%;">
                    <div class="table-responsive" style="height: 100%;" id="tables" >
                        <table class="table table-sm table-hover" id="orderList">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th style="text-align: center">Quantity</th>
                                    <th style="text-align: right">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Summary</td>
                                    <td> </td>
                                    <td class="text-end" colspan="" id="total"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div id="calculator" class="row d-flex d-md-flex flex-row flex-wrap flex-md-column flex-lg-row flex-xl-row flex-xxl-row" style="height: 30%;margin-bottom: 12px;margin-left: 0px;margin-right: 0px;">
                    <div class="col-md-10 col-xl-7"  style="width: 30%;padding: 0px 0px;">
                        <button class="btn" id="payment" type="button" style="background: #0a2635;height: 100%;border-radius: 4px 0px 0px 4px;width: 100%;color: rgb(255,255,255);" data-bs-target="#modal-1" data-bs-toggle="modal">Payment</button>
                    </div>
                    <div class="col-md-11 col-xl-7 offset-md-0 d-flex flex-row" style="padding: 0px;width: 70%;margin: -px;">
                        <div class="btn-toolbar">
                            <div class="btn-group" role="group" style="width: 100%;">
                                <button data-bs-target="#modal-1" data-bs-toggle="modal" roleid ="numpad"class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">1</button>
                                <button id ="numpad"class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">2</button>
                                <button id ="numpad"class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">3</button>
                                <button id = "quantity" class="btn" type="button" style="background: #fdb750;width: 30%;border-radius: 0px 4px 0px 0px;color: #0a2635;">Qty</button>
                            </div>
                            <div class="btn-group" role="group" style="width: 100%;color: #0a2635;">
                                <button id ="numpad"class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">4</button>
                                <button id ="numpad"class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">5</button>
                                <button id ="numpad"class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">6</button>
                                <button class="btn" type="button" style="background: #d2d2d2;width: 30%;border-radius: 0px;color: #0a2635;">Disc</button>
                            </div>
                            <div class="btn-group" role="group" style="width: 100%;color: #0a2635;">
                                <button id ="numpad"class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">7</button>
                                <button id ="numpad"class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">8</button>
                                <button id ="numpad"class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">9</button>
                                <button class="btn" type="button" style="background: #d2d2d2;width: 30%;border-radius: 0px;color: #0a2635;">Price</button>
                            </div>
                            <div class="btn-group" role="group" style="width: 100%;color: #0a2635;">
                                <button class="btn btn-sm" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">+/-</button>
                                <button id ="numpad"class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">0</button>
                                <button class="btn" type="button" style="background: #d2d2d2;width: 20%;border-radius: 0px;color: #0a2635;">.</button>
                                <button id = "btnDelete"class="btn" type="button" style="background: #d2d2d2;width: 30%;border-radius: 0px 0px 4px 0px;color: #0a2635;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-backspace">
                                        <path d="M5.83 5.146a.5.5 0 0 0 0 .708L7.975 8l-2.147 2.146a.5.5 0 0 0 .707.708l2.147-2.147 2.146 2.147a.5.5 0 0 0 .707-.708L9.39 8l2.146-2.146a.5.5 0 0 0-.707-.708L8.683 7.293 6.536 5.146a.5.5 0 0 0-.707 0z"></path>
                                        <path d="M13.683 1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7.08a2 2 0 0 1-1.519-.698L.241 8.65a1 1 0 0 1 0-1.302L5.084 1.7A2 2 0 0 1 6.603 1h7.08zm-7.08 1a1 1 0 0 0-.76.35L1 8l4.844 5.65a1 1 0 0 0 .759.35h7.08a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1h-7.08z"></path>
                                    </svg></button></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8" style="width: 70%;margin-top: 12px;">
                <div style="height: 100%;">
                    <ul class="nav nav-tabs" role="tablist">

                    <?php 
                        $num_category = category_row_count();
                        $category = select_all_category();
                        foreach ($category as $categories):
                    ?>
                        <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-bs-toggle="tab" href="#tab-<?php echo $categories['id'] ?>"><?php echo $categories['category_name'] ?></a></li>
                    <?php endforeach; ?>

                    </ul>
                    <div class="tab-content" style="height: 90%;">

                    <?php foreach($category as $prod): ?>

                        <div class="tab-pane " role="tabpanel" id="tab-<?php echo $prod['id'] ?>"style="height: 100%;">
                            <?php $products = join_product_table_by_category($prod['id']); ?>  
                            <div class="row row-cols-5 transactRows">
                            <?php $i = 1; foreach($products as $product):if($i++ >31) break; ?>
                                <div class="col" style="width: 20%; padding-bottom: 25px;">
                                    <button id="addRow" value="<?php echo $product['product_price']; ?>" class="btn" type="button" style="background: #000000; width: 100%; height: 100%;color: var(--bs-red); margin-bottom: 75px; ">
                                    <?php echo $product['product_name'];   ?>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <div class="modal" role="dialog" tabindex="-1" id="modal-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="color: #0a2635;">Checkout</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id ="exit"></button>
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
                                        <input type="number" min="0" name="amount">
                                        <div class="btn-group justify-content-xl-center" role="group" id="paymethod">
                                            <button class="btn" type="button" style="color: #fdb750;background: #0a2635;" name="cash">Cash</button>
                                            <button class="btn" type="button" style="color: #fdb750;background: #0a2635;" name="gcash">Gcash</button>
                                            <button class="btn" type="button" style="color: #fdb750;background: #0a2635;" name="utang">Utang</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input class="btn btn-primary" type="submit" id="pay" name="pay" value="PAY" style="width:100px;">
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/bootstrap/js/bukas.button.function.js"></script>
    <script src="assets/bootstrap/js/table.highlights.js"></script>
    <script src="assets/bootstrap/js/bukas.payment.js"></script>
    <script src="assets/bootstrap/js/total.payment.js"></script>
    <script src="assets/bootstrap/js/bukas.pay.js"></script>
    <script src="assets/bootstrap/js/bukas.price.js"></script>
</body>
</html>
<?php 
}else{
     header("Location: ../login.php");
     exit();
}
 ?>