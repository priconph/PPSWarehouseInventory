<?php
require_once('./includes.php');
require_once('./ppswarehouse-oop.php');
$dateToday = date('Y-m-d');
?>
<html>
<head>
<title></title>
</head>
<body>
	<table id="tbl_ResinMain" border="1" style="font-size:12px;">
		<thead>
			<tr>					
				<th>Product Line</th>
				<th>Part Number</th>
				<th>Material Type</th>
				<th>Supplier</th>
				<th>Model</th>
				<th>Balance</th>
				<th class="id" style="display:none;">ID</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$result = PPSW::getInstance()->getResinInfoMain();
				while ($row = mysqli_fetch_array($result)){
					echo "<tr>";
					echo "<td>".$row["ProductLine"]."</td>";
					echo "<td class='PartNumber'>".$row["PartNumber"]."</td>";
					echo "<td>".$row["MaterialType"]."</td>";
					echo "<td>".$row["Supplier"]."</td>";
					echo "<td>".$row["Model"]."</td>";
					echo "<td>".$row["Balance"]."</td>";
					echo "<td class='id' style='display:none;'>".$row["id"]."</td>";
					echo "</tr>";
				}
			?>
		</tbody>
	</table>
</body>
</html>
<script>
	window.print();
	window.location.href = "./Resin.php";
</script>