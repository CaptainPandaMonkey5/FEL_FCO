<?php

	require_once('includes/main_db.php');


	$query = "SELECT *,product.ProductName FROM supplierorder JOIN product ON product.ProductID = supplierorder.ProductOrdered ORDER BY OrderNo ASC;";
	$stmnt = $pdo -> prepare ($query);
	$stmnt->execute();
	$allResults = $stmnt -> fetchAll(); 

    if(isset($_GET["searchResult"])){
		$dateSearch = $_GET["dateSearch"];
		$idSearch = $_GET["idSearch"];
		if ($idSearch == "")
			$idSearch = '#';
		$query = "SELECT * FROM supplierorder JOIN product ON product.ProductID = supplierorder.ProductOrdered WHERE `OrderNo` LIKE '$idSearch' OR `OrderDate` LIKE '$dateSearch' OR `ProductName` LIKE '$idSearch%' ORDER BY OrderNo ASC";
		$stmnt = $pdo -> prepare ($query);
		$stmnt->execute();
		$allResults = $stmnt -> fetchAll(); 
	}

	if(isset($_GET["deleteAll"])){
		
		//SAVE AS CSV FIRST
		$query = "SELECT *,product.ProductName FROM supplierorder JOIN product ON product.ProductID = supplierorder.ProductOrdered ORDER BY OrderNo ASC;";
		$stmnt = $pdo -> prepare ($query);
		$stmnt->execute();
		$allResults = $stmnt -> fetchAll(); 

		$filename = "data/stock_history_". date('Y-m-d_H-i-s'). ".csv";
		$file = fopen($filename, 'w');

		fputcsv($file, array('OrderNo', 'ProductOrdered', 'SupplierName', 'OrderDate', 'QuantityOrdered', 'CostPerUnit', 'TotalCost'));

		foreach ($allResults as $row) {
			fputcsv($file, array($row["OrderNo"], $row["ProductOrdered"], $row["SupplierName"], $row["OrderDate"], $row["QuantityOrdered"], $row["CostPerUnit"], $row["TotalCost"]));
		}

		fclose($file);

		//THEN DELETE
		$query = "DELETE FROM supplierorder;";
		$stmnt = $pdo -> prepare ($query);
		$stmnt->execute();

		header('Location: StockReport.php');

	}

    $pdo=null;
    $stmnt=null;

    //var_dump(count($allStockOrder));


  
?>

<html>
    <head>
		
		<title>AJC Bike Shop MIS</title>
		<link rel="stylesheet" href="css/main_style.css">
		<link rel="stylesheet" href="css/report_style.css">
    </head>
    <body>

        <!-- lists all report about transactions or order -->
		
		<div class="nav_bar">
			<ul class="main_nav">
				<a href = "POS.php">
					<li id="pos_btn">
						<svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 32 32" id="swipe-machine"><path fill="#231f20" d="M19.12 7H4.26a2.05 2.05 0 0 0-2 2v20.9A2.06 2.06 0 0 0 4.26 32H19.12a2.06 2.06 0 0 0 2.06-2.06V9A2.05 2.05 0 0 0 19.12 7zM7.62 28.72H5.73v-1.9H7.62zm0-4.71H5.73v-1.9H7.62zm0-4.71H5.73V17.4H7.62zm5 9.42H10.74v-1.9h1.91zm0-4.71H10.74v-1.9h1.91zm0-4.71H10.74V17.4h1.91zm5 9.42h-1.9v-1.9h1.9zm0-4.71h-1.9v-1.9h1.9zm0-4.71h-1.9V17.4h1.9zm.65-6a.34.34 0 0 1-.33.34H5.41a.34.34 0 0 1-.33-.34V10.59a.33.33 0 0 1 .33-.33H18a.33.33 0 0 1 .33.33zM28.45 10.4H28V28.58h.5a1.34 1.34 0 0 0 1.34-1.34V11.75A1.34 1.34 0 0 0 28.45 10.4z"></path><rect width="2.93" height="18.19" x="22.11" y="10.4" fill="#231f20"></rect><rect width="1.26" height="18.19" x="25.86" y="10.4" fill="#231f20"></rect><path fill="#231f20" d="M20.61 1.79A1.78 1.78 0 0 0 20.09.52 1.82 1.82 0 0 0 18.82 0 1.79 1.79 0 0 0 17 1.79V2.85h3.58zM16.21 1.79a1.52 1.52 0 0 1 0-.29 1.36 1.36 0 0 1 0-.21l-.1 0 .12 0A1 1 0 0 1 16.33 1 .86.86 0 0 1 16.4.8a2.39 2.39 0 0 1 .29-.52 1 1 0 0 1 .08-.11L16.91 0H8.81A1.63 1.63 0 0 0 7.18 1.62V6.06h9z"></path></svg><p>Order</p>
					</li>
				</a>
				<a href = "Inventory.php">
					<li id="inv_btn">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" id="box"><path d="M18.399 2H1.6c-.332 0-.6.267-.6.6V5h18V2.6a.6.6 0 0 0-.601-.6zM2 16.6c0 .77.629 1.4 1.399 1.4h13.2c.77 0 1.4-.631 1.4-1.4V6H2v10.6zM7 8h6v2H7V8z"></path></svg><p>Inventory</p>
					</li>
				</a>
				<a href = "OrderReport.php">
					<li id="report_btn">
						<svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 64 64" id="business-analysis"><path fill="#010101" d="M41.56,5.23h-4.1a1,1,0,0,1-1-1,4.43,4.43,0,0,0-8.85,0,1,1,0,0,1-1,1h-4.1L20.62,12.1H43.38ZM32,9.31a3.26,3.26,0,1,1,3.26-3.26A3.26,3.26,0,0,1,32,9.31Z"></path><path fill="#010101" d="M53.26 9.59h-8.4l.86 3.27a1 1 0 0 1-.17.9 1.06 1.06 0 0 1-.83.4H19.28a1 1 0 0 1-1-1.3l.86-3.27h-8.4a1 1 0 0 0-1 1V63a1 1 0 0 0 1 1H53.26a1 1 0 0 0 1-1V10.62A1 1 0 0 0 53.26 9.59zM23.59 60.88a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1V53.23a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1zM40.8 36.38a1 1 0 1 1 .63-2l3.4 1.08h0a1 1 0 0 1 .62.54 1 1 0 0 1 0 .8v0l-1.34 3.31a1 1 0 0 1-1 .65 1.08 1.08 0 0 1-.38-.08 1 1 0 0 1-.57-1.35l.36-.87L19.93 49.18a1.15 1.15 0 0 1-.44.09 1 1 0 0 1-.44-2L41.69 36.66zm-5.25 24.5a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1V48.07a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1zm12 0a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1v-18a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1zm.4-32.42H16.09a1 1 0 0 1 0-2.07H47.91a1 1 0 0 1 0 2.07zm0-6.89H16.09a1 1 0 1 1 0-2.06H47.91a1 1 0 1 1 0 2.06zM32 4.54a1.51 1.51 0 1 0 1.5 1.51A1.51 1.51 0 0 0 32 4.54z"></path><rect width="2.96" height="5.58" x="18.57" y="54.27" fill="#010101"></rect><rect width="2.96" height="10.75" x="30.52" y="49.1" fill="#010101"></rect><rect width="2.97" height="15.91" x="42.47" y="43.94" fill="#010101"></rect></svg><p>Report</p>
					</li>
				</a>
				<a href = "Account.php">
					<li id="acc_btn">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" id="Accountant"><path fill="#000000" d="m43.41 38.69-.44-.16c-1.74-.62-2.7-2.08-3.25-3.47 5.33-3.4 9.14-10.07 9.14-17.37C48.87 7.61 41.61 0 32.01 0 22.4 0 15.14 7.61 15.14 17.7c0 7.29 3.8 13.96 9.13 17.36-.69 1.78-1.78 2.95-3.25 3.48l-.44.16c-7.17 2.57-15.29 5.49-16.4 17.02-.06.59.25 1.14.76 1.41C13.69 61.69 22.69 64 31.71 64c9.01 0 18.21-2.31 27.31-6.86.53-.27.84-.83.78-1.42-1.1-11.53-9.22-14.45-16.39-17.03zM21.12 15.17c3.13.75 12.48 2.32 19.96-2.92.8 1.18 2.32 3.91 3.28 8.49h-.08c-.24-.57-.66-1.17-1.25-1.76-.59-.59-1.41-.93-2.23-.93H22.83c-.84 0-1.63.33-2.23.93-.25.25-.45.5-.64.74.43-1.28.86-2.82 1.16-4.55zm-2.33 7.75c.03 0 .05.01.07.01h.46c.37 1.34 1.4 4.21 3.62 5.25.57.27 1.16.4 1.78.4.92 0 1.9-.3 2.92-.89 2.39-1.39 3.69-3.02 3.86-4.86.08-.85-.1-1.72-.53-2.59h1.69c-.43.86-.61 1.73-.53 2.59.17 1.84 1.47 3.47 3.86 4.86 1.02.59 2 .89 2.93.89.61 0 1.21-.13 1.77-.4 2.16-1 3.19-3.66 3.62-5.25h.45c.05.32.09.64.14.98-2.19 6.05-7.35 10.65-12.88 10.65-5.86 0-11.27-5.11-13.23-11.64zm16.67 36.22L32 57.32l-3.46 1.82.66-3.85-2.8-2.73 3.87-.56L32 48.5l1.73 3.5 3.87.56-2.8 2.73.66 3.85zm-3.45-15.6a74.43 74.43 0 0 1-6.49-4.88c.52-.66.95-1.43 1.31-2.28 1.65.67 3.39 1.05 5.18 1.05 1.78 0 3.52-.38 5.16-1.04.36.86.79 1.63 1.31 2.29-2.69 2.28-5.37 4.12-6.47 4.86z" class="color292f64 svgShape"></path></svg><p>Account</p>
					</li>
				</a>
			</ul>
			
			<ul class="misc_nav">
				<a href = "Help.php">
					<li id="help_btn">
						<p>Help</p>
					</li>
				</a>
				<a href = "AboutUs.php">
					<li id="info_btn">
						<p>About Us</p>
					</li>
				</a>
			</ul>
		</div>

		<div class="main_section">
			<div class="report_header">
				<div class="report_title">Reports</div>
				<hr/>
				<div class="report_nav">
					<button id="orderreport_btn" onclick= "window.location.href='OrderReport.php'">Order History</button>
					<button id="salesreport_btn" onclick= "window.location.href='SalesReport.php'">Sales Report</button>
					<button id="stockreport_btn" onclick= "window.location.href='StockReport.php'">Stock History</button>
				</div>
			</div>
			<div class="report_search">
				<p>Stock History</p>
				<form method="GET" action="">
					<input type="date" id = "dateSearch" name="dateSearch">
					<input type="text" id = "idSearch" name="idSearch" placeholder="Search">
					<button type="submit" id="search_btn" name="searchResult" ><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" id="search"><path d="M46.599 40.236L36.054 29.691C37.89 26.718 39 23.25 39 19.5 39 8.73 30.27 0 19.5 0S0 8.73 0 19.5 8.73 39 19.5 39c3.75 0 7.218-1.11 10.188-2.943l10.548 10.545a4.501 4.501 0 0 0 6.363-6.366zM19.5 33C12.045 33 6 26.955 6 19.5S12.045 6 19.5 6 33 12.045 33 19.5 26.955 33 19.5 33z"></path></svg></button>
				</form>
			</div>
			<div class="report_table"> 
				<table cellspacing="0">
					<tr>
						<th>OrderNo</th>
						<th>ProductOrdered</th>
						<th>SupplierName</th>
						<th>OrderDate</th>
						<th>QuantityOrdered</th>
						<th>CostPerUnit</th>
						<th>TotalCost</th>
					</tr>

					<?php
						foreach ($allResults as $row){
					?>
						<tr>
							<td><?php echo $row["OrderNo"] ?></td>
							<td>[<?php echo $row["ProductOrdered"] ?>] - <?php echo $row["ProductName"] ?></td>
							<td><?php echo $row["SupplierName"] ?></td>
							<td ><?php echo $row["OrderDate"] ?></td>
							<td><?php echo $row["QuantityOrdered"] ?></td>
							<td ><?php echo $row["CostPerUnit"] ?></td>
							<td ><?php echo $row["TotalCost"] ?></td>
						</tr>

					<?php
						}
					?>
				</table>
			</div>
			<div class="report_table_footer"> 
				<form action="" method="GET">
					<button type="submit" name="deleteAll">Save and Delete</button>
				</form>
				<p> Showing <?php echo count($allResults)?> Results</p>
			</div>
		</div>
        
    </body>

</html>
