<?php 
	include '../functions/users.php';

	if(isset($_SESSION['id']) && isset($_SESSION['business_name'])){
		$id = $_SESSION['id'];
		$business_session = $_SESSION['business_name'];
		date_default_timezone_set('Asia/Manila');
		$last_login = date("Y-m-d  h:i:sa");
		$deleted = 0;

		if(isset($_POST['addButton'])){
			$pname = sanitize($_POST['pname']);
			$pattribute = sanitize($_POST['pattribute']);
			$pcategories = sanitize($_POST['pcategories']);
			$pprice = sanitize($_POST['pprice']);
			$pcprice = sanitize($_POST['pcprice']);
			$pstock = sanitize($_POST['pstock']);

			if($pcprice == ""){
				$pcprice = 0;
			}

			$sku_generator = strtoupper($pcategories.substr($pname, 0, 2).substr($pname, -1).$pprice.substr($last_login, 5, 6));
			$sku_gen = sanitize(str_replace(".", "", $sku_generator));

			if(product_exists($pname) === false){
				if ($_FILES["uploadFile"]["name"] != "") {
					$filename = $_FILES["uploadFile"]["name"];
					$tempname = $_FILES["uploadFile"]["tmp_name"];
					$folder = '../assets/'.$business_session.'/products/'.$filename;
					$folder2 = '../assets/'.$business_session.'/products/'. basename($_FILES["uploadFile"]["name"]);
                    $image_file_type = pathinfo($folder2, PATHINFO_EXTENSION);

                    if($image_file_type != "gif" && $image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png") {
                        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
                        //invalid file type error message
                    } else{
                    	if (move_uploaded_file($tempname, $folder)) {
							echo "Image uploaded successfully";
						} else{
							echo "LMAO FAIL UPLOAD!!";
						}

						include '../functions/user_conn.php';
						$sql = mysqli_query($conn, "SELECT product_name FROM products WHERE product_name = '$pname'");	
						$row = mysqli_fetch_array($sql);
						$existing_name = $row['product_name'];

						if($existing_name == $pname){
							$sql = add_product_on_update($pname, $pattribute, $sku_gen, $pstock, $pprice, $pcprice, $pcategories, $deleted, $last_login, $filename);
							$result = mysqli_query($conn, $sql);
							mysqli_close($conn);
							header("Location: ../products_page/products.php");
						} else{
							include '../functions/user_conn.php';
							$sql = add_product($pname, $pattribute, $sku_gen, $pstock, $pprice, $pcprice, $pcategories, $deleted, $last_login, $filename);
							$result = mysqli_query($conn, $sql);
							mysqli_close($conn);

							header("Location: ../products_page/products.php");
						}
                    }
				}else{

					include '../functions/user_conn.php';
					$filename = 'default_photo.png';
					$sql = mysqli_query($conn, "SELECT product_name FROM products WHERE product_name = '$pname'");	
					$row = mysqli_fetch_array($sql);
					$existing_name = $row['product_name'];

					if($existing_name == $pname ){
						$sql = add_product_on_update($pname, $pattribute, $sku_gen, $pstock, $pprice, $pcprice, $pcategories, $deleted, $last_login, $filename);
						$result = mysqli_query($conn, $sql);
						mysqli_close($conn);
						header("Location: ../products_page/products.php");

					} else {
						$filename = 'default_photo.png';

						include '../functions/user_conn.php';
						$sql = add_product($pname, $pattribute, $sku_gen, $pstock, $pprice, $pcprice, $pcategories, $deleted, $last_login, $filename);
						$result = mysqli_query($conn, $sql);
						mysqli_close($conn);

						header("Location: ../products_page/products.php");
					}
				}
			} else{
				//validation
				$message = "Product already exists. Please try again!";

				echo "<script> 
					alert('$message')
					window.location.replace('../products_page/products.php'); 
				</script>";
			}	
		}

		if(isset($_POST['updateButton'])){
			$pid = sanitize($_POST['epid']);
			$pname = sanitize($_POST['epname']);
			$pattribute = sanitize($_POST['epattribute']);
			$pcategories = sanitize($_POST['epcategories']);
			$psku = sanitize($_POST['epsku']);
			$pprice = sanitize($_POST['epprice']);
			$pcprice = sanitize($_POST['epcprice']);

			if ($_FILES["euploadFile"]["name"] != "") {
				$filename = $_FILES["euploadFile"]["name"];
				$tempname = $_FILES["euploadFile"]["tmp_name"];
				$folder = '../assets/'.$business_session.'/products/'.$filename;
				$folder2 = '../assets/'.$business_session.'/products/'. basename($_FILES["euploadFile"]["name"]);
                $image_file_type = pathinfo($folder2, PATHINFO_EXTENSION);

                if($image_file_type != "gif" && $image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png") {
                    echo "Only JPG, JPEG, PNG & GIF files are allowed.";
                    $upload_ok = 0;
                } else{
                	if (move_uploaded_file($tempname, $folder)) {
						echo "Image uploaded successfully";
					} else{
						echo "LMAO FAIL UPLOAD!!";
					}

					include '../functions/user_conn.php';
					$sql = update_product($pid, $pname, $pattribute, $psku, $pprice, $pcprice, $pcategories, $deleted, $last_login, $filename);
					$result = mysqli_query($conn, $sql);
					mysqli_close($conn);

					header("Location: ../products_page/products.php");
                }
			} else {
				include '../functions/user_conn.php';
				$sql = update_product_no_image($pid, $pname, $pattribute, $psku, $pprice, $pcprice,$pcategories, $deleted, $last_login);
				$result = mysqli_query($conn, $sql);
				mysqli_close($conn);

				header("Location: ../products_page/products.php");
			}
		}

		if(isset($_POST['updatecButton'])){
			$cname = sanitize($_POST['ecname']);
			$cid = sanitize($_POST['ecid']);

			include '../functions/user_conn.php';
			$sql = update_category($cname, $cid);
			$result = mysqli_query($conn, $sql);
			mysqli_close($conn);

			header("Location: ../products_page/products.php");
		}

		if(isset($_POST['deleteButton'])){
			$pid = sanitize($_POST['dpid']);
			$pname = sanitize($_POST['dpname']);

			include '../functions/user_conn.php';
			$sql = delete_product($pid, $pname);
			$result = mysqli_query($conn, $sql);
			mysqli_close($conn);

			header("Location: ../products_page/products.php");
		}

		if(isset($_POST['deletecButton'])){
			$cname = sanitize($_POST['dcname']);
			$cid = sanitize($_POST['dcid']);

			include '../functions/user_conn.php';
			$sql = delete_category($cid, $cname);
			$result = mysqli_query($conn, $sql);
			mysqli_close($conn);

			header("Location: ../products_page/products.php");
		}

		if(isset($_POST['downloadCSVFile'])){
			include '../functions/user_conn.php';

			$delimiter = ",";
			$filename = 'csv';

			$f = fopen('php://memory', 'w');

			$fields = array('Product Name','Attribute','SKU','Stock','Selling Price','Cost Price','Category ID','','','Category Legends:');
			fputcsv($f, $fields, $delimiter);

			$query = "SELECT * FROM categories WHERE deleted != 1";
			if($result = mysqli_query($conn, $query)){
				while($row = mysqli_fetch_array($result)){
					$c_id = $row['id'];
					$c_name = $row['category_name'];

					$array_categories = array("", "", "", "", "", "", "", "", "",$c_id, $c_name);
					fputcsv($f, $array_categories);
				}
			}
			
			fseek($f, 0);

			header('Content-Type:application/xls');
			header('Content-Disposition:attachment;filename=import_products.csv');

			fpassthru($f);
		}

		if(isset($_POST['importButton']))
		{
			include '../functions/user_conn.php';
			
		    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
		    
		    if(!empty($_FILES['uploadCSVFile']['name']) && in_array($_FILES['uploadCSVFile']['type'], $csvMimes)){
		        
		        if(is_uploaded_file($_FILES['uploadCSVFile']['tmp_name'])){
		            $csvFile = fopen($_FILES['uploadCSVFile']['tmp_name'], 'r');
		            fgetcsv($csvFile);
		            
		            while(($line = fgetcsv($csvFile)) !== FALSE){
		                // Get row data
		                $name = $line[0];
		                $attribute = $line[1];
		                $sku = $line[2];
		                $qty = $line[3];
		                $product_price = $line[4];
		                $product_cprice = $line[5];
		                $category = $line[6];
		                $photo = 'default_photo.png';
		                date_default_timezone_set('Asia/Manila');
						$date = date("Y-m-d  h:i:s");
		                $deleted = 0;

		                //add more validations if product already exists
		                //also if deleted yung file update
		                $insertCSV = mysqli_query($conn, add_product($name, $attribute, $sku, $qty, $product_price, $product_cprice, $category, $deleted, $date, $photo));
		            }
		            fclose($csvFile);
		            header("Location: ../products_page/products.php?success");
		            echo "unang if success";
		        }else{
		        	header("Location: ../products_page/products.php?error");
		            echo "error";
		        }
		    }else{
		        echo '<script>alert("Invalid file type. File extension is not allowed!")</script>';
				echo "<script>window.location= '../products_page/products.php';</script>";
		    }
		}
	}
	else{
		header("Location: ../functions/logout.php");
		exit();
	}
?>
