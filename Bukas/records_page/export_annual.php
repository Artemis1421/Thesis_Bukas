<?php 
include '../functions/users.php';

if (isset($_SESSION['id']) && isset($_SESSION['business_name'])){
	$date_start = $_SESSION['date_start'];
    $date_end = $_SESSION['date_end'];
    $yr_month = $_SESSION['yr_month'];
	include '../functions/user_conn.php';
	
	if(isset($_POST['excelButton'])){
		$query = "SELECT p.product_name, c.category_name, p.product_price, p.product_cprice, SUM(sales_qty) AS total_qty, SUM(p.product_price * s.sales_qty) AS total_selling_price, SUM(s.sales_qty * (p.product_price - p.product_cprice)) AS total_income_price, s.date, s.deleted
					FROM sales s 
					LEFT JOIN products p 
					ON s.product_id = p.id 
					LEFT JOIN categories c 
					ON c.id = p.category_id 
					WHERE s.date >= '$date_start' AND s.date <= '$date_end' AND s.deleted != 1
					GROUP BY EXTRACT(YEAR_MONTH FROM s.date), s.product_id;";
		$result = mysqli_query($conn, $query);

		$html = '
			<table>
				<tr>
					<th>Product Name</th>
		            <th>Category</th>
		            <th>Selling Price</th>
		            <th>Cost Price</th>
		            <th>Quantity</th>
		            <th>Gross Total</th>
		            <th>Income Total</th>
		            <th>Date</th>
				</tr>
		';

		while($row = mysqli_fetch_assoc($result)){
			$html .= '
					<tr>
						<td>'.$row['product_name'].'</td>
	                    <td>'.$row['category_name'].'</td>
	                    <td>'.'PHP '.number_format($row['product_price'], 2).'</td>
	                   	<td>'.'PHP '.number_format($row['product_cprice'], 2).'</td>
	                    <td>'.number_format($row['total_qty']).'</td>
	                    <td>'.'PHP '.number_format($row['total_selling_price'], 2).'</td>
	                    <td>'.'PHP '.number_format($row['total_income_price'], 2).'</td>
	                    <td>'.$row['date'].'</td>
	                </tr>';
			@$total += $row['total_selling_price'];
    		@$total_formatted = sprintf('%0.2f', $total); 

    		@$total2 += $row['total_income_price'];
    		@$total_formatted2 = sprintf('%0.2f', $total2);
    		$date = $row['date'];
		}
		$html .= '
			    	<tr>
			    		<td>TOTAL</td>
		                <td></td>
		                <td></td>
		                <td></td>
		                <td></td>
		                <td>PHP '.
		                	number_format($total_formatted, 2)
		                .'</td>
		                <td>PHP '.
		                	number_format($total_formatted2, 2)
		                .'</td>
		                <td>'.$yr_month.'</td>
			    	</tr>    
		    	</tbody>
		    	<tfoot>
                 ';

        $html .= '
                    <tr>
                        <td colspan="8"><b>Payment Methods</td>
                    </tr>
                 ';
                        if($result = mysqli_query($conn, "SELECT paymethod, sum(total_payment) FROM orders WHERE date >= '$date_start' AND date <= '$date_end' AND deleted != 1 GROUP BY paymethod")){
                            while($row = mysqli_fetch_array($result)){
                                $paymethod = $row['paymethod'];
                            if($result1 = mysqli_query($conn, "SELECT s.id, s.order_id, s.product_id, s.date, s.sales_qty, o.id, o.paymethod, p.id, p.product_price, p.product_cprice, sum(s.sales_qty * (p.product_price - p.product_cprice)) AS 'total_income' FROM sales s LEFT JOIN products p ON s.product_id = p.id LEFT JOIN orders o ON s.order_id = o.id WHERE paymethod = '$paymethod' AND s.date >= '$date_start' AND s.date <= '$date_end' AND s.deleted != 1")){
                                while($row1 = mysqli_fetch_array($result1)){
                                    if($row1['total_income'] == ""){
                                        $total_income = '0.00';
                                    }else{
                                        $total_income = $row1['total_income'];
                                    }
                        $html .= '
                                <tr>
                                    <td>'.$row['paymethod'].'</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td> PHP '.number_format($row['sum(total_payment)'], 2).'</td>
                                    <td> PHP '.number_format($total_income, 2).'</td>
                                    <td></td>
                                </tr>
                                 ';
                            	}
                        	}
                        	}
                    	}
        $html .= '
                </tfoot>';
	    $html .= '
	    	</table>	
				 ';
		header('Content-Type:application/xls');
		header('Content-Disposition:attachment;filename='.$yr_month.'.xls');
		echo $html;
	}

	if(isset($_POST['csvButton'])){
		$query = "SELECT p.product_name, c.category_name, p.product_price, p.product_cprice, SUM(sales_qty) AS total_qty, SUM(p.product_price * s.sales_qty) AS total_selling_price, SUM(s.sales_qty * (p.product_price - p.product_cprice)) AS total_income_price, s.date, s.deleted
					FROM sales s 
					LEFT JOIN products p 
					ON s.product_id = p.id 
					LEFT JOIN categories c 
					ON c.id = p.category_id 
					WHERE s.date >= '$date_start' AND s.date <= '$date_end' AND s.deleted != 1
					GROUP BY EXTRACT(YEAR_MONTH FROM s.date), s.product_id;";

		$delimiter = ",";
		$filename = $date_start.' - '.$date_end.'csv';

		$f = fopen('php://memory', 'w');

		$fields = array('Product Name','Category','Selling Price','Cost Price','Quantity','Gross Total','Income Total','Date');
		fputcsv($f, $fields, $delimiter);

		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_assoc($result)){
			$lineData = array($row['product_name'], $row['category_name'], 'PHP '.number_format($row['product_price'], 2), 'PHP '.number_format($row['product_cprice'], 2), number_format($row['total_qty']), 'PHP '.number_format($row['total_selling_price'], 2), 'PHP '.number_format($row['total_income_price'], 2), $row['date']);
			fputcsv($f, $lineData, $delimiter);
			@$total += $row['total_selling_price'];
			$total_formatted = sprintf('%0.2f', $total);

			@$total2 += $row['total_income_price'];
			$total_formatted2 = sprintf('%0.2f', $total2);
		}

		$fields2 = array('TOTAL', '', '', '', '', 'PHP '.number_format($total_formatted, 2), 'PHP '.number_format($total_formatted2, 2), $yr_month);
		fputcsv($f, $fields2, $delimiter);

		$fields3 = array('Payment Methods');
		fputcsv($f, $fields3, $delimiter);

		if($result = mysqli_query($conn, "SELECT paymethod, sum(total_payment) FROM orders WHERE date >= '$date_start' AND date <= '$date_end' AND deleted != 1 GROUP BY paymethod")):
            while($row = mysqli_fetch_array($result)):
                $paymethod = $row['paymethod'];
            if($result1 = mysqli_query($conn, "SELECT s.id, s.deleted, s.order_id, s.product_id, s.date, s.sales_qty, o.id, o.paymethod, p.id, p.product_price, p.product_cprice, sum(s.sales_qty * (p.product_price - p.product_cprice)) AS 'total_income' FROM sales s LEFT JOIN products p ON s.product_id = p.id LEFT JOIN orders o ON s.order_id = o.id WHERE paymethod = '$paymethod' AND s.date >= '$date_start' AND s.date <= '$date_end' AND s.deleted != 1")):
                while($row1 = mysqli_fetch_array($result1)):
                	if($row1['total_income'] == ""){
                        $total_income = '0.00';
                    }else{
                        $total_income = $row1['total_income'];
                    }
                	$lineData2 = array($row['paymethod'], '', '', '', '', 'PHP '.number_format($row['sum(total_payment)'], 2), 'PHP '.number_format($total_income, 2), '');
                	fputcsv($f, $lineData2, $delimiter);
                endwhile;
            endif;
            endwhile;
        endif;

		fseek($f, 0);

		header('Content-Type:application/xls');
		header('Content-Disposition:attachment;filename='.$yr_month.'.csv');

		fpassthru($f);
	}
} else{
	header("Location: ../functions/logout.php");
 	exit();
}
?>