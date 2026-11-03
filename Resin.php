<?php
require_once('./includes.php');
require_once('./ppswarehouse-oop.php');
$dateToday = date('Y-m-d');
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	var oTable = $('#tbl_ResinMain').dataTable({
			paginate:false,
			"autoWidth": false
	});
	
	$('#btn_ExportToPDF').click(function(){
		window.location.href = "./ExportResin-PDF.php";
	});
	
	$("#btn_ExportToExcel").click(function(){
		window.location.href = "./ExportMainPage.php";
	});
	
		
	$("#btn_ExportToExcelSummaryI").click(function(){
		$('#modal_export_summary_report').modal('show');
		$('#frm-select-date-range').data('id','I');
	});
	
	$("#btn_ExportToExcelSummaryR").click(function(){
		$('#modal_export_summary_report').modal('show');
		$('#frm-select-date-range').data('id','R');
	});

	$('#frm-select-date-range').submit(function(e){
		e.preventDefault();
		if ( $(this).data('id') == 'I' ){
			window.location.href = "./ExportMainPageSummaryI.php?start_date="+ $('#txt_date_start').val() +'&end_date=' + $('#txt_date_end').val();
		}
		if ( $(this).data('id') == 'R' ){
			window.location.href = "./ExportMainPageSummaryR.php?start_date="+ $('#txt_date_start').val() +'&end_date=' + $('#txt_date_end').val();
		}		
	});
	
	$("#btn_AddResin").click(function(){
		window.location.href = "./addResin.php";
	});
	
	var iPos,x,idGlobal,idForDelete;
	$("#tbl_ResinMain tbody tr").click(function(){
		idForDelete = $(this).find(".PartNumber").text();
		iPos = oTable.fnGetPosition( this );
		if ( $(this).hasClass('selected') ) {
			$(this).removeClass('selected');
		}
		else {
			oTable.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}
	});
	
	$('#btn_Delete').click(function(){
		if(confirm('Warning! All information about this product will be deleted! Proceed?')){
			var data = {
				"action" : "deleteAllProductInfo",
				"PartNumber" : idForDelete
			}
			data = $.param(data);
			$.ajax({
				type : "post",
				dataType : "json",
				data : data,
				url : "./ppswarehouseHandler.php",
				success : function(data){
					alert("Data Deleted!");
					window.location.href = "./Resin.php";
				}
			});
			
		}else{
			alert("Delete Cancelled!");
		}
	});
		
	//START DATE VALIDATION
	$('#txtStartDate').change(function(){
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
			if (startDate>endDate){
				$('#txtStartDate').val(endDate);  
			}
	});
	//END DATE VALIDATION
	$('#txtEndDate').change(function(){
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
			if (endDate<startDate){
				$('#txtEndDate').val(startDate);  
			}
	});
	
	$("#tbl_ResinMain tbody tr").dblclick(function(){
		var id = $(this).find('.id').text();
		var PartNumber = $(this).find('.PartNumber').text();
		window.location.href = "./updateResin.php?id="+id+"&PartNumber="+PartNumber;
	});
	
	$('#btn_ImportCSV').click(function(){ $("#ModalImportResinCSV").modal("show"); });
	$('#btn_ImportWarehouseInfo').click(function(){ importMaterials(); });
	$('#btn_Transfer_Slip').click(function(){ transfer_slip(); });
	
});

var row = 0;
function importMaterials(){
	if(confirm("Import these data?")){
	$('#tbl_ImportWarehouseData tr').each(function(row, tr){	//loop to each tr 
		if(row>0){ 	//REMOVE ROW 0 WHICH IS A LABEL
			//GET VALUES HERE
			var data = {
				"action"		: "ImportWarehouseData",
				"ProductLine"	: $(tr).find('td:eq(0)').text(),
				"PartNumber"	: $(tr).find('td:eq(1)').text(),
				"MaterialType"	: $.trim($(tr).find('td:eq(2)').text()),
				"Supplier"		: $(tr).find('td:eq(3)').text(),
				"Model"			: $(tr).find('td:eq(4)').text(),
				"LastUpdate"	: "<?php echo $dateToday;?>",
				"Username"		: "<?php echo $username;?>"
			}
			data = $.param(data);
			$.ajax({
				type	: "post",
				dataType: "json",
				data	: data,
				url		: "./ppswarehouseHandler.php",
				success	: function(data){
					
				}
			});
		}
		row++;			
	});
		alert("Material Import Success!");
		window.location.href = "./Resin.php";
	}else{
		alert("Import Cancelled");
		window.location.href = "./Resin.php";
	}
}
function transfer_slip(){
	window.location.href = "Resin_Transfer_Slip.php";
}
</script>
</head>
<body>
<div class="panel panel-primary">
	<div class="panel-heading">Warehouse Resin Inventory</div>
	<div class="panel-body">
		<?php
			if($_SESSION['date']==""){
				//echo "ginawa nya to!";
				if(isset($_POST['txtStartDate'])){
					$_SESSION['date'] = $_POST['txtStartDate'];
					$_SESSION['date_out'] = $_POST['txtEndDate'];
				}else{
					$_SESSION['date'] = $dateToday;
					$_SESSION['date_out'] = $dateToday;
				}
			}else{
				if($_POST['txtStartDate']!=""){
				$_SESSION['date'] = $_POST['txtStartDate'];
				$_SESSION['date_out'] = $_POST['txtEndDate'];
				}else{
					
				}
			}
			?>
			<!--
			<form class="form-inline" role="form" method="POST" action="updateRequest.php">
			  <div class="form-group">
				<label class="sr-only" for="txtStartDate">Start Date</label>
				<?php 
					//echo '<input type="date" class="form-control" id="txtStartDate" name="txtStartDate" placeholder="Start Date" value="'.$_SESSION['date'].'">';	
				?>
			  </div>
			  <div class="form-group">
				<label class="sr-only" for="txtEndDate">End Date</label>
				<?php 
					//echo '<input type="date" class="form-control" id="txtEndDate" name = "txtEndDate" placeholder="End Date" value="'.$_SESSION['date_out'].'">';
				?>
			  </div>
			  <button type="submit" class="btn btn-default" id="SetDateRange">SET DATE</button>
			</form>
			-->
		
		  <button id="btn_AddResin" type="button" class="btn btn-default glyphicon glyphicon-plus">ADD NEW MATERIAL</button>
		  <!--<button id="btn_ExportToExcel" type="button" class="btn btn-default glyphicon glyphicon-export">EXPORT TO EXCEL</button>
		  <button id="btn_ExportToPDF" type="button" class="btn btn-default glyphicon glyphicon-file">EXPORT TO PDF</button>-->
		  
		  <!--<button id="btn_ForPRList" type="button" class="btn btn-default glyphicon glyphicon-ok">FOR PR LIST</button>-->
		  <!--<button id="btn_ImportToDatabase" type="button" class="btn btn-default">IMPORT FROM EXCEL</button>-->
		<button id="btn_ImportCSV" type="button" class="btn btn-default glyphicon glyphicon-import">IMPORT CSV</button>
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle glyphicon glyphicon-export" data-toggle="dropdown">
				GENERATE REPORT
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
			<!-- dropdown menu links -->
				<li><a href="#" id="btn_ExportToPDF" class="glyphicon glyphicon-export">EXPORT TO PDF</a></li>
				<li><a href="#" id="btn_ExportToExcel" class="glyphicon glyphicon-export">EXPORT TO EXCEL</a></li>
				<li><a href="#" id="btn_ExportToExcelSummaryI" class="glyphicon glyphicon-export">EXPORT TO EXCEL (ISSUANCE SUMMARY)</a></li>
				<li><a href="#" id="btn_ExportToExcelSummaryR" class="glyphicon glyphicon-export">EXPORT TO EXCEL (RECEIVED SUMMARY)</a></li>
			</ul>
		</div>
		<button id="btn_Transfer_Slip" type="button" class="btn btn-default glyphicon glyphicon-file">TRANSFER SLIP</button>
		<!--<button id="btn_Delete" type="button" class="btn btn-default glyphicon glyphicon-remove">DELETE</button>-->
		<h5 style="float:right;border: 2px solid;padding:.5%;color:red;">Note: Double Click Row to Edit</h5>
		
		<table id="tbl_ResinMain" border="1">
			<thead>
				<tr>					
					<th>Product Line</th>
					<th>Part Number</th>
					<th>Material Type</th>
					<!--<th>BOH</th>
					<th>In</th>
					<th>Out</th>-->
					<th>WHS Stock</th>
					<th>Remarks</th>
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
						//echo "<td>".$row["Boh"]."</td>";
						//echo "<td>".$row["In"]."</td>";
						//echo "<td>".$row["Out"]."</td>";
						echo "<td>".$row["Balance"]."</td>";
						echo "<td>".$row["Remarks"]."</td>";
						echo "<td class='id' style='display:none;'>".$row["id"]."</td>";
						echo "</tr>";
					}
				?>
			</tbody>
			
			<!-- <thead>
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
					/* $result = PPSW::getInstance()->getResinInfoMain();
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
					} */
				?>
			</tbody>-->
		</table>
	</div>
</div>

<!--Modal-->
<div id="modalNameHere" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">TITLE</h4>
			</div>
			<div class="modal-body">
				<p>CONTENT</p>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btnYes" class="btn btn-success">YES</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
		</div>
	</div>
</div>

<div id="ModalImportResinCSV" class="modal fade" tabindex="-1" style="overflow:scroll;" role="dialog" aria-labelledby="ModalImportResinCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:600">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title">Upload CSV File</h4>
			</div>
			<form action="Resin.php" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
						<p><input type="file" name="file" id="file"></p>
				</div>
				<div class="modal-footer">
				   <input type="submit" class="btn btn-success" value="SUBMIT">
				   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
				</div>
			</form>
			<?php
			if(isset($_FILES['file']['name'])) {
				appendTableStart();
			}
			?>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_export_summary_report">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Export Summary Report</h4>
      </div>
      <div class="modal-body">
        <form id="frm-select-date-range" data-id="0">
			<h4>Enter a date range </h4>
			<label>Start Date:</label>
			<input class="form-control" type="date" id="txt_date_start" name="txt_date_start" required>
			<label>End Date:</label>
			<input class="form-control" type="date" id="txt_date_end" name="txt_date_end" required>
		
      </div>
      <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-success" id="export_report_summary">Export</button>
		</form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
function appendData(){
	if(isset($_FILES['file']['name'])) {
	$file = $_FILES['file']['name'];
	$fileCheck = explode(".",$file);
	if($fileCheck[1] != 'csv') {
		echo '<center><br><br>ERROR: PLEASE MAKE SURE YOU CONVERTED THE EXCEL FILE TO CSV FORMAT.<br><br>
			How to convert excel file to csv:<br>
			1. Open File <br>
			2. Save File as csv (example: itemlist.csv) then press save.
			<br><br><img src="/images/csv.png" style="width:700px"><br><br>';
		echo '<a href="Resin.php">BACK</a></center><br>';
	} else {
		if ($_FILES["file"]["error"] > 0) {
			$msg = "Error: " . $_FILES["file"]["error"] . "<br>";
		}
		else
		{
			//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
			//echo "Type: " . $_FILES["file"]["type"] . "<br>";
			//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br>";
			   
			$a=$_FILES["file"]["tmp_name"]; //FILENAME
							 
			$csv_file = $a; //PASS THE FILE NAME 
			 
			if (($getfile = fopen($csv_file, "r")) !== FALSE) { //OPEN FILE 
				$data = fgetcsv($getfile, 1000, ",");
				while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
					$result = $data;
					$str = implode(",", $data);	
					$slice = explode(",", $str);
					if(empty($slice[0]) && empty($slice[1]) && empty($slice[2])){
					
					}else{
						//SET VALUES OF EACH ITEM IN A ROW
						echo "<tr>";
						$dateAppend = strtotime($slice[0]);
						$dateAppend = date("Y-m-d",$dateAppend);
						//echo "<td>".$dateAppend."</td>";
						echo "<td>".$slice[0]."</td>";
						echo "<td>".$slice[1]."</td>";
						echo "<td>".$slice[2]."</td>";
						echo "<td>".$slice[3]."</td>";
						echo "<td>".$slice[4]."</td>";
						//echo "<td><a href='javascript:void(0);' class='remCF'>Remove</a></td>";
						echo "</tr>";
					}
				}
			}
		}
		echo "<script> $('#ModalImportResinCSV').modal('show'); </script>";
	}	
	}
}
function appendTableStart(){
	echo '<button class="btn btn-primary" id="btn_ImportWarehouseInfo" style="float:right;">IMPORT</button>';
	echo '<table id="tbl_ImportWarehouseData" class="table">
				<thead>
					<tr>
						<th>Product Line</th>
						<th>Part Number</th>
						<th>Material Type</th>
						<th>Supplier</th>
						<th>Model</th>
					</tr>
				</thead>
				<tbody>';
				 appendData();
				
	echo '</tbody>
		</table>';
}
?>
</body>
</html>