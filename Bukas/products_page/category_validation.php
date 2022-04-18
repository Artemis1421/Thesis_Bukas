<?php 
    include '../functions/users.php';
    include '../functions/user_conn.php';

    if(isset($_SESSION['id']) && isset($_SESSION['business_name'])){
        if(isset($_POST['addButton'])){
            $cname = $_POST['cname'];
            $deleted = 0;

            $sql = mysqli_query($conn, "SELECT category_name FROM categories WHERE category_name = '$cname'");    
            $row = mysqli_fetch_array($sql);
            $existing_name = $row['category_name'];

            if ($existing_name == $cname){
                $sql = "UPDATE categories SET deleted = 0 WHERE category_name = '$cname'";
                $result = mysqli_query($conn, $sql);
                mysqli_close($conn);
                header("Location: ../products_page/products.php");
            }else{
                $sql = add_category($cname, $deleted);
                $result = mysqli_query($conn, $sql);
                mysqli_close($conn);
                header("Location: ../products_page/products.php");
            }

        }
    }
    else{
        header("Location: ../functions/logout.php");
        exit();
    }
?>