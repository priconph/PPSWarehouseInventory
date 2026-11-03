<?php
require_once('./includes.php');
require_once('./ppswarehouse-oop.php');
$dateToday = date("Y-m-d");
$lastUpdate = date("Y-m-d H-i-s");
$dateReset = '2000-01-01';
session_start();
$username = $_SESSION["username"];
echo '<input type="date" id="txt_dateReset" style="display:none;" value="'.$dateReset.'">';
//REMINDER: MAG ADD KA NG DELETE NA AJAX SA MISMONG REMOVE
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<style>
#btn_ModalAddNewData{
	float:right;
	margin-right:5;
}

.hidden_div{
	display:none;
}
</style>
<script>
//nasa ilalim yung ibang script kase may niloload sya na base sa database tapos mag cocompute ng data basta yun na yun
function CheckNumeric()
{
  // Get ASCII value of key that user pressed
   var key = window.event.keyCode;
  // Was key that was pressed a numeric character (0-9)?
   if ( key > 45 && key < 58 )
	 return; // if so, do nothing
   else
	 window.event.returnValue = null; // otherwise, 
								   // discard character
}
</script>
</head>
<body>
	<div class="panel panel-primary" style="width:100%;margin:auto;">
	  <div class="panel-heading">ADD FGS RECORD</div>
	  <div class="panel-body">
		<div class="panel panel-primary">
			<div class="panel-body">
				<label style="margin-right:3.7%;width:22%;">Product Line:</label>
				<select id="txt_ProductLine" style="width:20%;height:28px;">
				<?php
					$result = PPSW::getInstance()->get_product_line();
					echo '<option value=""></option>';
					while($row = mysqli_fetch_array($result)){
						echo '<option>'.$row['ProductLine'].'</option>';
					}
				?>	
				</select>
				<label style="margin-right:4%;width:22%;">Part Number:</label><input type="text" id="txt_PartNumber" style="width:20%;">
				<label style="margin-right:4%;width:22%;">Material Type:</label></label><input type="text" id="txt_MaterialType" style="width:20%;">
				<label style="margin-right:4%;width:22%;">Model:</label></label><input value="" type="text" id="txt_Model" style="width:20%;">
				<label style="margin-right:3.7%;width:22%;">Supplier:</label></label>
				<select id="txt_Supplier" style="width:20%;height:28px;">
					<option value=""></option>
					<option>INABATA</option>
					<option>NAGASE</option>
					<option>MORIMURA</option>
					<option>YEC</option>
					<option>DOHO</option>
				</select>
				<!--<label style="margin-right:4%;width:22%;">Cost/kg:</label></label><input value="" type="text" id="txt_Cost" style="width:20%;">-->
				<br>
				<br>
				<label style="margin-right:4%;width:22%;">Balance as of (<label id="lbl_RecentDate"></label>)</label><input value="0" type="text" id="txt_EOH" style="width:20%;" readonly>
				<button class='btn btn-primary' id="btn_hide_unhide_container_add_record" style="float:right;">Add New Transaction</button>
			</div>
		  </div>
		
		<div class="panel panel-primary" id="container_add_record">
			<div class="panel-body">
				<?php
					echo '<label style="margin-right:5%;width:15%;">Date:</label></label><input type="date" id="txt_DateDaily" style="width:25%;" value="'.$dateToday.'">';
				?>
				<label style="margin-right:5%;width:15%;">Invoice No:</label><input type="" id="txt_InvoiceNoDaily" style="width:25%;">
				<label style="margin-right:5%;width:15%;">Transfer Slip No:</label><input type="" id="txt_TransferSlipNoDaily" style="width:25%;">
				<label style="margin-right:5%;width:15%;">BOH:</label><input onkeypress="CheckNumeric()" type="" id="txt_BOHDaily" style="width:25%;" value="0" readonly>
				<label style="margin-right:5%;width:15%;">IN:</label><input onkeypress="CheckNumeric()" type="" id="txt_INDaily" style="width:25%;" value="0">
				<label style="margin-right:5%;width:15%;">OUT:</label><input onkeypress="CheckNumeric()" type="" id="txt_OUTDaily" style="width:25%;" value="0">
				<label style="margin-right:5%;width:15%;">EOH:</label><input onkeypress="CheckNumeric()" type="" id="txt_EOHDaily" style="width:25%;" value="0" readonly>
				<label style="margin-right:5%;width:15%;">Recorded By:</label><input type="" value= "<?php echo $username; ?>" id="txt_RecordedByDaily" style="width:25%;" readonly>
				<label style="margin-right:5%;width:15%;">Remarks:</label><input type="" id="txt_RemarksDaily" style="width:25%;">
				<button id="btn_ModalAddNewData" class="btn btn-success">ADD</button>
			</div>
		</div>
		
		<div class="panel panel-primary">
			<div class="panel-body">
				<?php
					//echo '<label style="margin-right:5%;width:20%;">Date:</label><input type="date" id="txt_Date" value="'.$dateToday.'" style="width:20%;">';
				?>
				<div class="btn-group">
					<button id="btn_ImportCSV" type="button" class="btn btn-default">IMPORT CSV FILE</button>
					<!--<button id="btn_ImportFromDatabase" type="button" class="btn btn-default">IMPORT FROM EXCEL</button>-->
				</div><br><br>
				<table id="tbl_Process" border="" class= "table"width="100%">
					<thead>
						<tr>
							<th>Date</th>
							<th>Invoice No.</th>
							<th>Transfer Slip No.</th>
							<th>BOH</th>
							<th>IN</th>
							<th>OUT</th>
							<th>EOH</th>
							<th>Recorded By</th>
							<th>Remarks</th>
							<th>(X)</th>
						</tr>
					</thead>
					<tbody>
						<?php
							appendData();
						?>
					</tbody>
				</table>	
			</div>
		</div>
		
		<div class="panel panel-primary">
			<div class="panel-body" align="right">
				<button id="btn_SaveTransaction" class="btn btn-success" style="width:10%;">Save</button>
				<a href="./Resin.php"><button class="btn btn-danger" id="btn_Cancel" style="width:10%;">Cancel</button></a>
			</div>
		</div>
	  </div>
	</div>
	
<!--Modal-->
<div id="modal_DeleteData" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">DELETE</h4>
			</div>
			<div class="modal-body">
				<p>WARNING! DATA WILL BE PERMANENTLY DELETED!</p>
				<p>PROCEED?</p>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_Delete_Yes" class="btn btn-success">YES</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
		</div>
	</div>
</div>
<!--Modal-->
<div id="modal_GenerateReport" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">GENERATE REPORT</h4>
			</div>
			<div class="modal-body">
				<?php
					echo '<label style="margin-right:5%;width:20%;">Start Date:</label></label><input type="date" id="txt_DateDailyStart" style="width:50%;" value="'.$dateToday.'"><br>';
					echo '<label style="margin-right:5%;width:20%;">End Date:</label></label><input type="date" id="txt_DateDailyEnd" style="width:50%;" value="'.$dateToday.'">';
				?>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_GenereteReportExcel" class="btn btn-success">GENERATE REPORT</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>
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
		echo '<a href="poReceived.php">BACK</a></center><br>';
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
						echo "<td>".$dateAppend."</td>";
						echo "<td>".$slice[1]."</td>";
						echo "<td>".$slice[2]."</td>";
						echo "<td>".$slice[3]."</td>";
						echo "<td>".$slice[4]."</td>";
						echo "<td>".$slice[5]."</td>";
						echo "<td>".$slice[6]."</td>";
						echo "<td>".$slice[7]."</td>";
						echo "<td>".$slice[8]."</td>";
						echo "<td><a href='javascript:void(0);' class='remCF'>Remove</a></td>";
						echo "</tr>";
					}
				}
			}
		}
	}	
	}
	
}
?>
<!--MODALS-->

<div id="ModalImportResinCSV" class="modal fade" tabindex="-1" style="overflow:scroll;" role="dialog" aria-labelledby="ModalImportResinCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title">Upload CSV File</h4>
			</div>
			<form action="addResin.php" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
						<p><input type="file" name="file" id="file"></p>
				</div>
				<div class="modal-footer">
				   <input type="submit" class="btn btn-success" value="SUBMIT">
				   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
				</div>
			</form>
		</div>
	</div>
</div>

</body>
<script>

$('document').ready(function(){
	$('#container_add_record').attr('class','hidden_div');
	$('#btn_hide_unhide_container_add_record').click(function(){
		if($('#container_add_record').is(":visible") == false ){
			$('#btn_hide_unhide_container_add_record').text("CLOSE");
			$('#container_add_record').removeAttr('class');
		}else{
			$('#container_add_record').removeAttr();
			$('#btn_hide_unhide_container_add_record').text("Add New Transaction");
			$('#container_add_record').attr('class','hidden_div');
		}
		//alert($('#container_add_record').attr());
	});
	
	computetableData();
			
	//set values after adding a product out data
	$("#btn_AddNewData").click(function(){
		$("#modal_AddNewData").modal("show");
		$("#txt_BOHDaily").val(total_EOH);
	});
		
	$('#btn_ModalAddNewData').click(function(){
		if($("#txt_EOHDaily").val() < 0){
			alert("Item withrawn is greater than stock!");
			return false;
		}
		if($("#txt_DateDaily").val() == ""){
			alert("Please add a Date!");
		} else{
			$('#tbl_Process tbody').append('<tr class=""><td>'+$("#txt_DateDaily").val()+'</td><td>'+$("#txt_InvoiceNoDaily").val()+'</td><td>'+$("#txt_TransferSlipNoDaily").val()+'</td><td>'+$("#txt_BOHDaily").val()+'</td><td>'+$("#txt_INDaily").val()+'</td><td>'+$("#txt_OUTDaily").val()+'</td><td>'+$("#txt_EOHDaily").val()+'</td><td>'+$("#txt_RecordedByDaily").val()+'</td><td>'+$("#txt_RemarksDaily").val()+'</td><td><a href="javascript:void(0);" class="remCF">Remove</a></td></tr>'); //APPEND NEW DATA INTO THE TABLE
			computetableData();
		}
	});
	//On change functions, recompute data on user input
	$("#txt_INDaily").change(function(){
		$("#txt_EOHDaily").val(parseFloat($('#txt_INDaily').val())+parseFloat($('#txt_BOHDaily').val()));
		//$("#txt_BOHDaily").val(parseFloat($('#txt_INDaily').val())-parseFloat($('#txt_OUTDaily').val()));
	});
	$("#txt_OUTDaily").change(function(){
		$("#txt_EOHDaily").val((parseFloat($('#txt_BOHDaily').val()) + parseFloat($('#txt_INDaily').val())) - parseFloat($('#txt_OUTDaily').val()));
		//$("#txt_BOHDaily").val(parseFloat($('#txt_INDaily').val())-parseFloat($('#txt_OUTDaily').val()));
	});
	
	//FUNCTION FOR REMOVING A ROW INTO THE TABLE 
	$('#btn_Delete_Yes').click(function(){
		$(this).parent().parent().remove(); //REMOVES THE ROW
		window.location.href="./addResin";
	});
	
	//click functions
	$('#btn_GenerateReport').click(function(){
		$('#modal_GenerateReport').modal('show');
	});
	
	$("#btn_SaveTransaction").click(function(){
		check_session_status();
	});
	
	$("#tbl_Process").on('click','.remCF',function(){ //TABLE ID = tblMain AND TABLE CLASS = remCF
		$(this).parent().parent().remove(); //REMOVES THE ROW
		computetableData();
	});
	
});

function SaveRecord(){
		var data = {
			"action" 		: "addNewProduct",
			"ProductLine" 	: $("#txt_ProductLine").val(),
			"PartNumber" 	: $("#txt_PartNumber").val(),
			"MaterialType" 	: $("#txt_MaterialType").val(),
			"Model" 		: $("#txt_Model").val(),
			"Balance" 		: $("#txt_EOH").val(),
			"Cost" 			: $("#txt_Cost").val(),
			"LastUpdate" 	: "<?php echo $lastUpdate;?>",
			"Username" 		: "<?php echo $username;?>",
			"Supplier" 		: $('#txt_Supplier').val()
		}
		
		data = $.param(data);
		$.ajax({
			type 	 : "post",
			dataType : "json",
			data 	 : data,
			url	 	 : "./ppswarehouseHandler.php",
			success  : function(data){
				
				SaveTransaction();
			}
		});
		alert("New Material has been added!");
		window.location.href = "./Resin.php";
}

function SaveTransaction(){
	var row=0;
	$("#tbl_Process tr").each(function(row,tr){
		if(row>0){ //this first row is the label kaya di sya isasama
			var data = {
				"action" 		: "addNewProductWithrawal2",
				//"fkPartNumber" 	: $("#txt_PartNumber").val(),
				"Date"			: $(tr).find('td:eq(0)').text(),
				"InvoiceNo" 	: $(tr).find('td:eq(1)').text(),
				"TransferSlipNo": $(tr).find('td:eq(2)').text(),
				"BOH" 			: $(tr).find('td:eq(3)').text(),
				"IN" 			: $(tr).find('td:eq(4)').text(),
				"OUT" 			: $(tr).find('td:eq(5)').text(),
				"EOH" 			: $(tr).find('td:eq(6)').text(),
				"RecordedBy" 	: $(tr).find('td:eq(7)').text(),
				"Remarks" 		: $(tr).find('td:eq(8)').text(),
				"LastUpdate" 	: "<?php echo $lastUpdate;?>",
				"Username" 		: "<?php echo $username;?>"
			}				
			
			data = $.param(data);
			$.ajax({
				type 	: "post",
				dataType: "json",
				data 	: data,
				url 	: "./ppswarehouseHandler",
				success : function(data){
					
				}
			});
		}
		row++;
	});
}
function computetableData(){
	var total_BOH=0,total_EOH=0,total_IN=0;total_OUT=0;
	row = 0;
	var thisDate; 
	$('#tbl_Process tr').each(function(row, tr){	//loop to each tr
		if(row>0){ 	//REMOVE ROW 0 WHICH IS A LABEL
			//GET VALUES HERE
			//total_BOH = parseFloat(total_BOH) + parseFloat($(tr).find('td:eq(3)').text());
			total_IN = (parseFloat(total_IN) + parseFloat($(tr).find('td:eq(4)').text())).toFixed(2);
			total_OUT = (parseFloat(total_OUT) + parseFloat($(tr).find('td:eq(5)').text())).toFixed(2);
		}
		row++;
	});
	total_EOH = (parseFloat(total_IN) - parseFloat(total_OUT)).toFixed(2);
	$("#txt_EOH").val(total_EOH);
	//Reset Values of all Modal
	$("#txt_DateDaily").val("<?php echo $dateToday;?>");
	$("#txt_InvoiceNoDaily").val("");
	$("#txt_TransferSlipNoDaily").val("");
	$("#txt_BOHDaily").val($("#txt_EOH").val());
	$("#txt_INDaily").val("0");
	$("#txt_OUTDaily").val("0");
	$("#txt_EOHDaily").val("0");
	//$("#txt_RecordedByDaily").val("");
	$("#txt_RemarksDaily").val("");
}

function check_session_status(){
	var data = {
		"action"	: "session_check"
	}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: "./session_check.php",
		success	: function(data){
			if(data['username'] == null){
				alert('Session expired, Please relogin.');
				parent.parent.location.reload();
			}else{
				SaveRecord();
			}
		} 
	});
}

$('#btn_ImportCSV').click(function(){ $("#ModalImportResinCSV").modal("show"); });
</script>
</html>