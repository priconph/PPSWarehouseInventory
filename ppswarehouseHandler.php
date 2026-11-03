<?php
	//echo "test";
	if(is_ajax()) {
		if(isset($_POST["action"]) && !empty($_POST["action"])) {
			$action = $_POST["action"];
			switch($action) {
				case "addNewProduct" 					: addNewProduct(); break;
				case "addNewProductWithrawal" 			: addNewProductWithrawal(); break;
				case "addNewProductWithrawal2" 			: addNewProductWithrawal2(); break;
				case "updateBalance" 					: updateBalance(); break;
				case "deleteResinRecord" 				: deleteResinRecord(); break;
				case "getLastID" 						: getLastID(); break;
				case "deleteAllProductInfo" 			: deleteAllProductInfo(); break;
				case "ImportWarehouseData" 				: ImportWarehouseData(); break;
				case "load_transfer_slip_transactions" 	: load_transfer_slip_transactions(); break;
				case "save_new_transfer_slip" 			: save_new_transfer_slip(); break;
				case "update_transfer_slip" 			: update_transfer_slip(); break;
			}
		}
	}

	function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	function addNewProduct() {
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->addNewProduct($return['ProductLine'],$return["PartNumber"],$return["MaterialType"],$return["Model"],$return["Balance"],$return["LastUpdate"],$return["Username"],$return["Supplier"],$return["Cost"]);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function addNewProductWithrawal() {
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->addNewProductWithrawal($return["fkid"],$return["Date"],$return["InvoiceNo"],$return["TransferSlipNo"],$return["BOH"],$return["IN"],$return["OUT"],$return["EOH"],$return["RecordedBy"],$return["Remarks"],$return["LastUpdate"],$return["Username"]);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function addNewProductWithrawal2() {
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->addNewProductWithrawal2($return["Date"],$return["InvoiceNo"],$return["TransferSlipNo"],$return["BOH"],$return["IN"],$return["OUT"],$return["EOH"],$return["RecordedBy"],$return["Remarks"],$return["LastUpdate"],$return["Username"]);
		//$return['last_id'] = $result;
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function updateBalance() {
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->updateBalance($return['id'],$return['ProductLine'],$return['PartNumber'],$return['MaterialType'],$return['Model'],$return['Balance'],$return['Cost'],$return['Supplier'],$return["LastUpdate"],$return["Username"]);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function load_transfer_slip_transactions() {
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->load_transfer_slip_transactions();
		$ctr = 0;
		while ($row = mysqli_fetch_array($result)){
			$return['pkid'][$ctr] = $row['pkid'];
			$return['PartNumber'][$ctr] = $row['PartNumber'];
			$return['MaterialType'][$ctr] = $row['MaterialType'];
			$return['Qty'][$ctr] = $row['Out'];
			$return['Remarks'][$ctr] = $row['Remarks'];
			$ctr++;
		}
		$return['ctr'] = $ctr;
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function deleteResinRecord() {
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->deleteResinRecord($return['pkid']);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function getLastID(){
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->getLastID($return['PartNumber']);
		if($row = mysqli_fetch_array($result)){
			$return['lastID'] = $row['pkid'];
		}
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function deleteAllProductInfo(){
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->deleteAllProductInfo($return['PartNumber']);
		if($row = mysqli_fetch_array($result)){
			$return['lastID'] = $row['pkid'];
		}
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function save_new_transfer_slip(){
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->save_new_transfer_slip($return['ProductLine'],$return['ControlNo'],$return['Date'],
															$return['IssuedBy'],$return['LastUpdate'],$return['Username']);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function update_transfer_slip(){
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->update_transfer_slip($return['pkid'],$return['Trans_Slip']);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function ImportWarehouseData(){
		require_once('ppswarehouse-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSW::getInstance()->ImportWarehouseData($return['ProductLine'],$return['PartNumber'],$return['MaterialType'],
															$return['Supplier'],$return['Model'],$return['LastUpdate'],
															$return['Username']);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	//YPICS Database access
	function searchPoNum(){
		$return = $_POST;
		$result = "";
		require_once('ppswarehouse-oop.php');
		$YPICS = new YPICS();
		$result = $YPICS->searchPoNum($return['PONum']);
			while ( $row = mssql_fetch_array($result) ) {			
				$return["PONum"]  = $row['PONum'];
				$return["DeviceName"]  = $row['DeviceName'];	
				$return["ProdCode"]  = $row['ProdCode'];	
				$return["POQty"]  = $row['POQty'];
				$return["CUST"]  = $row['CUST'];
			}
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}

?>