<?php
require_once('./includes.php');
require_once('./ppswarehouse-oop.php');
$dateToday = date("Y-m-d");
$lastUpdate = date("Y-m-d H-i-s");
$dateReset = '2000-01-01';
session_start();
$username = $_SESSION["username"];
$id = $_GET["id"];
$PartNumber = $_GET["PartNumber"];
$result = PPSW::getInstance()->getResinInfoById($id);
if($row = mysqli_fetch_array($result)){
	$ProductLine = $row["ProductLine"];
	$MaterialType = $row["MaterialType"];
	$Model = $row["Model"];
	$Balance = $row["Balance"];
	$dateforBalance = $row["LastUpdate"];
	$Supplier = $row["Supplier"];
}
echo '<input type="date" id="txt_dateReset" style="display:none;" value="'.$dateReset.'">';
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
				<label style="margin-right:4%;width:22%;">Product Line:</label></label><input type="text" id="txt_ProductLine" style="width:20%;" value="<?php echo $ProductLine; ?>">
				<label style="margin-right:4%;width:22%;">Part Number:</label></label><input type="text" id="txt_PartNumber" style="width:20%;" value="<?php echo $PartNumber; ?>">
				<label style="margin-right:4%;width:22%;">Material Type:</label></label><input type="text" id="txt_MaterialType" style="width:20%;" value="<?php echo $MaterialType; ?>">
				<label style="margin-right:4%;width:22%;">Model:</label></label><input type="text" id="txt_Model" style="width:20%;" value="<?php echo $Model; ?>">
				<label style="margin-right:4%;width:22%;">Supplier:</label></label><input type="text" id="txt_Supplier" style="width:20%;" value="<?php echo $Supplier; ?>">
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
				<label style="margin-right:5%;width:15%;">Recorded By:</label><input type="text" value= "<?php echo $username; ?>" id="txt_RecordedByDaily" style="width:25%;" readonly>
				<label style="margin-right:5%;width:15%;">Remarks:</label><input type="" id="txt_RemarksDaily" style="width:25%;">
				<button id="btn_ModalAddNewData" class="btn btn-success">ADD TRANSACTION</button>
			</div>
		</div>
		
		<div class="panel panel-primary">
			<div class="btn-group" style="padding-top:1%;padding-left:1%;">
					<!--<button id="btn_AddNewData" type="button" class="btn btn-default">ADD NEW RECORD</button>-->
					<button id="btn_GenerateReport" type="button" class="btn btn-default">GENERATE REPORT</button>
					<!--<button id="btn_ImportFromDatabase" type="button" class="btn btn-default">IMPORT FROM EXCEL</button>-->
				</div>
			<div class="panel-body" style="height:50%;overflow:scroll;">
				<?php
					//echo '<label style="margin-right:5%;width:20%;">Date:</label><input type="date" id="txt_Date" value="'.$dateToday.'" style="width:20%;">';
				?>
				
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
							<th style="display:none;">id</th>
						</tr>
					</thead>
					<tbody>
						<?php
							// $prev_boh = 0;
							$prev_eoh = 0;
							$boh = 0;
							$eoh = 0;
							$ctr = 1;
							$result = PPSW::getInstance()->getResinTransactionInfo($id);
							while($row = mysqli_fetch_array($result)){
								// $prev_eoh = $eoh;
								// $boh = $prev_eoh + $row["Boh"] + $row["In"];
								// $eoh = $boh - $row["Out"];
								echo "<tr>";
								echo "<td>".$row["Date"]."</td>";
								echo "<td>".$row["InvoiceNo"]."</td>";
								echo "<td>".$row["TransferSlipNo"]."</td>";
								echo "<td>".$row["Boh"]."</td>";
								// echo "<td>".$boh."</td>";
								echo "<td>".$row["In"]."</td>";
								echo "<td>".$row["Out"]."</td>";
								echo "<td>".$row["Eoh"]."</td>";
								echo "<td>".$row["RecordedBy"]."</td>";
								echo "<td>".$row["Remarks"]."</td>";
								echo "<td><a href='#' onclick='remCF(".$row["pkid"].")'>Delete</a></td>";
								echo "<td class='id' style='display:none;'>".$row["pkid"]."</td>";
								echo "</tr>";
								$ctr++;
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="panel panel-primary">
			<div class="panel-body" align="right">
				<!--<button id="btn_SaveTransaction" class="btn btn-success" style="width:10%;">Save</button>-->
				<a href="#"><button class="btn btn-success glyphicon glyphicon-ok" id="btn_UpdateData" style="width:10%;">DONE</button></a>
			</div>
		</div>
	  </div>
	</div>
	
<!--Modal
<div id="modal_AddNewData" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:600px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">ADD NEW DATA</h4>
			</div>
			<div class="modal-body">
				<?php
					//echo '<label style="margin-right:5%;width:20%;">Date:</label></label><input type="date" id="txt_DateDaily" style="width:50%;" value="'.$dateToday.'">';
				?>
				<label style="margin-right:5%;width:20%;">Invoice No:</label></label><input type="" id="txt_InvoiceNoDaily" style="width:50%;">
				<label style="margin-right:5%;width:20%;">Transfer Slip No:</label></label><input type="" id="txt_TransferSlipNoDaily" style="width:50%;">
				<label style="margin-right:5%;width:20%;">BOH:</label></label><input onkeypress="CheckNumeric()" type="" id="txt_BOHDaily" style="width:50%;" value="0" readonly>
				<label style="margin-right:5%;width:20%;">IN:</label></label><input onkeypress="CheckNumeric()" type="" id="txt_INDaily" style="width:50%;" value="0">
				<label style="margin-right:5%;width:20%;">OUT:</label></label><input onkeypress="CheckNumeric()" type="" id="txt_OUTDaily" style="width:50%;" value="0">
				<label style="margin-right:5%;width:20%;">EOH:</label></label><input onkeypress="CheckNumeric()" type="" id="txt_EOHDaily" style="width:50%;" value="0" readonly>
				<label style="margin-right:5%;width:20%;">Recorded By:</label></label><input type="" id="txt_RecordedByDaily" style="width:50%;">
				<label style="margin-right:5%;width:20%;">Remarks:</label></label><input type="" id="txt_RemarksDaily" style="width:50%;">
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_ModalAddNewData" class="btn btn-success">SAVE</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
		</div>
	</div>
</div>-->
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
			   <a href="#" id="btn_GenereteReportExcelAll" class="btn btn-success">ALL RECORD</a>
			   <a href="#" id="btn_GenereteReportExcel" class="btn btn-success">GENERATE REPORT</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>
</body>
<?php
?>
<script>
$('document').ready(function(){
	// var dt_tbl_Process = $('#tbl_Process').DataTable({
		// "paginate"	: false
	// });
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
	
	var LargestDay= $('#txt_dateReset').val();
	var total_BOH=0,total_EOH=0,total_IN=0;total_OUT=0;
		
	//load initial value here
	row = 0;
	var thisDate; 
	$('#tbl_Process tr').each(function(row, tr){	//loop to each tr 
		if(row>0){ 	//REMOVE ROW 0 WHICH IS A LABEL
			//GET VALUES HERE
			thisDate = $(tr).find('td:eq(0)').text();
			if(thisDate!=""){
				if(thisDate>LargestDay){
					LargestDay = thisDate;
				}
			}
			total_IN = (parseFloat(total_IN) + parseFloat($(tr).find('td:eq(4)').text())).toFixed(2);
			total_OUT = (parseFloat(total_OUT) + parseFloat($(tr).find('td:eq(5)').text())).toFixed(2);
		}
		row++;	
	});
	
	total_EOH = (parseFloat(total_IN) - parseFloat(total_OUT)).toFixed(2);
	
	//set txt_eom value
	$('#txt_EOH').val(total_EOH);
	$('#txt_EOHDaily').val(total_EOH);
	$('#lbl_RecentDate').text(LargestDay);
	$("#txt_BOHDaily").val(total_EOH);
		
	//set values after adding a product out data
	$("#btn_AddNewData").click(function(){
		$("#modal_AddNewData").modal("show");
		$("#txt_BOHDaily").val(total_EOH);
	});
	
	$('#btn_UpdateData').click(function(){
		UpdateRecord("2");
	});
	
	$('#btn_ModalAddNewData').click(function(){
		check_session_status();
	});
	
	//On change functions, recompute data on user input
	$("#txt_INDaily").change(function(){
		$("#txt_EOHDaily").val((parseFloat($('#txt_INDaily').val())+parseFloat($('#txt_BOHDaily').val())).toFixed(2));
		//$("#txt_BOHDaily").val(parseFloat($('#txt_INDaily').val())-parseFloat($('#txt_OUTDaily').val()));
	});
	$("#txt_OUTDaily").change(function(){
		$("#txt_EOHDaily").val(((parseFloat($('#txt_BOHDaily').val()) + parseFloat($('#txt_INDaily').val())) - parseFloat($('#txt_OUTDaily').val())).toFixed(2));
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
	
	//Remove function here	
	$("#tbl_Process").on('click','.remCF',function(){ //TABLE ID = tblMain AND TABLE CLASS = remCF
		if (confirm('Delete this data?')) {
				var idforDelete = $(this).find(".id").text();
				var data = {
					"action" : "deleteRecordTransaction",
					"id" : "1"
				}
				alert(idforDelete);
				/*
				data = $.param(data);
				$.ajax({
					type : "post",
					dataType : "json",
					data : data,
					url : "./ppswarehouseHandler.php",
				});
				*/
				//
				//do delete here with ajax
				computetableData();
		}else{
			
		}		
	});
		
	$("#btn_GenereteReportExcel").click(function(){
		var productLine =  $("#txt_ProductLine").val();
		var fkid =  "<?php echo $id; ?>";
		var partNumber =  $("#txt_PartNumber").val();
		var materialType =  $("#txt_MaterialType").val();
		var model =  $("#txt_Model").val();
		var startDate = $('#txt_DateDailyStart').val();
		var endDate = $('#txt_DateDailyEnd').val();
		//alert(productLine);
		alert(fkid);
		window.location.href = "./ExportUpdate.php?startDate="+startDate+"&endDate="+endDate+"&ProductLine="+productLine+"&fkid="+fkid+"&MaterialType="+materialType+"&Model="+model;
	});
			
	$("#btn_GenereteReportExcelAll").click(function(){
		var productLine =  $("#txt_ProductLine").val();
		var fkid =  "<?php echo $id; ?>";
		var partNumber =  $("#txt_PartNumber").val();
		var materialType =  $("#txt_MaterialType").val();
		var model =  $("#txt_Model").val();
		var startDate = "";
		var endDate = "";
		//alert(productLine);
		window.location.href = "./ExportUpdate.php?startDate="+startDate+"&endDate="+endDate+"&ProductLine="+productLine+"&fkid="+fkid+"&MaterialType="+materialType+"&Model="+model;
	});
	
});

function UpdateRecord(redirect){
	var data = {
		"action" 		: "updateBalance",
		"id" 			: "<?php echo $id; ?>",
		"ProductLine" 	: $("#txt_ProductLine").val(),
		"PartNumber"	: $("#txt_PartNumber").val(),
		"MaterialType" 	: $("#txt_MaterialType").val(),
		"Model" 		: $("#txt_Model").val(),
		"Balance"		: $("#txt_EOH").val(),
		"Cost" 			: $("#txt_Cost").val(),
		"Supplier" 		: $('#txt_Supplier').val(),
		"LastUpdate" 	: "<?php echo $lastUpdate;?>",
		"Username" 		: "<?php echo $username;?>"
	}

	data = $.param(data);
	$.ajax({
		type : "post",
		dataType : "json",
		data : data,
		url : "./ppswarehouseHandler.php",
		success : function(data){
			//alert(data['result']);
			if(redirect == "1"){
				
			}else{	
				//alert("YES redirect");
				window.location.href = './Resin.php';
			}
			
		}
	});
}


function SaveRecord(){
	if($('#txt_INDaily').val() == "0" && $('#txt_OUTDaily').val() == "0"){
		alert("IN and OUT = 0, Transaction was cancelled");
		return false;
	}
	
	if($("#txt_EOHDaily").val() < 0){
		alert("Item withrawn is greater than stock!");
		return false;
	}
	if($("#txt_DateDaily").val() != ""){
		if($('#txt_EOHDaily').val()=="0"){
			$('#txt_EOHDaily').val($('#txt_EOH').val());
		}
		
		var data = {
				"action" : "addNewProductWithrawal",
				"fkid" : "<?php echo $id; ?>",
				"Date" : $("#txt_DateDaily").val(),
				"InvoiceNo" : $("#txt_InvoiceNoDaily").val(),
				"TransferSlipNo" : $("#txt_TransferSlipNoDaily").val(),
				"BOH" : $("#txt_BOHDaily").val(),
				"IN" : $("#txt_INDaily").val(),
				"OUT" : $("#txt_OUTDaily").val(),
				"EOH" : $("#txt_EOHDaily").val(),
				"RecordedBy" : $("#txt_RecordedByDaily").val(),
				"Remarks" : $("#txt_RemarksDaily").val(),
				"LastUpdate" : "<?php echo $lastUpdate;?>",
				"Username" : "<?php echo $username;?>"
		}				
		data = $.param(data);
		$.ajax({
			type : "post",
			dataType : "json",
			data : data,
			url : "./ppswarehouseHandler.php",
			success : function(data){
				UpdateRecord("1");
			}
		});
						
		var AppendThis = '<tr class=""><td>'+$("#txt_DateDaily").val()+'</td><td>'+$("#txt_InvoiceNoDaily").val()+'</td><td>'+$("#txt_TransferSlipNoDaily").val()+'</td><td>'+$("#txt_BOHDaily").val()+'</td><td>'+$("#txt_INDaily").val()+'</td><td>'+$("#txt_OUTDaily").val()+'</td><td>'+$("#txt_EOHDaily").val()+'</td><td>'+$("#txt_RecordedByDaily").val()+'</td><td>'+$("#txt_RemarksDaily").val()+'</td><td><a href="#" onclick="remCF('+data['lastID']+')">Delete</a></td></tr>';
		$('#tbl_Process tbody').prepend(AppendThis); //APPEND NEW DATA INTO THE TABLE
		computetableData();
		
	}
	else{
		alert("Please add a Date!");
	}
}

function lastID(pn){
	var data = {
		"action" : "getLastID",
		"PartNumber" : pn
	}
	data = $.param(data);
	$.ajax({
		type : "post",
		dataType : "json",
		data : data,
		url : "./ppswarehouseHandler.php",
		success : function(data){
			
		}
	});
}

function remCF(id){
	alert("Temporary Unavailable!");
	return false;
	if (confirm('Delete this data?')) {
		var data = {
		"action" : "deleteResinRecord",
		"pkid" : id
		}
		data = $.param(data);
		$.ajax({
			type : "post",
			dataType : "json",
			data : data,
			url : "./ppswarehouseHandler.php",
			success : function(data){
				alert("Record has been deleted!");
			}
		});
		location.reload();
		//$(this).parent().remove(); //REMOVES THE ROW
		//computetableData();
	}else{
		
	}		
}

function updateBalance(){
	var data = {
		"action" 		: "updateBalance",
		"id" 			: "<?php echo $id;?>",
		"ProductLine" 	: $("#txt_ProductLine").val(),
		"PartNumber"	: $("#txt_PartNumber").val(),
		"Balance"		: $("#txt_EOHDaily").val(),
		"LastUpdate" 	: "<?php echo $lastUpdate;?>",
		"Username" 		: "<?php echo $username;?>"
	}
	data = $.param(data);
	$.ajax({
		type : "post",
		dataType : "json",
		data : data,
		url : "./ppswarehouseHandler.php",
		success : function(data){
			
		}
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
	//Load last translip no
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
</script>
</html>
<?php
//Notes: On Remove delete the unique ID then remove row
?>