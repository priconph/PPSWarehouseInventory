<?php
class PPSW extends mysqli
{
	private static $instance = null;
	
	//db connection config variables
	private $user = "root";
	private $pass = "r@p1d0nl1n3";
	private $dbName = "db_pps";
	private $dbHost = "localhost";

	public static function getInstance() {
		if(!self::$instance instanceof self) 
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __clone()
	{
		trigger_error('Clone is not allowed.',E_USER_ERROR);
	}
	
	public function __wakeup()
	{
		trigger_error('Deserializing is not allowed.',E_USER_ERROR);
	}
	
	private function __construct() {
		parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
		if(mysqli_connect_error())
		{
			exit('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		}
		parent::set_charset('utf-8');
	}

	////////////////////////////////////PUBLIC FUNCTIONS STARTS HERE//////////////////////////////////////////////
	
	public function addNewProduct($ProductLine,$PartNumber,$MaterialType,$Model,$Balance,$LastUpdate,$Username,$Supplier,$Cost){
		$query = "INSERT INTO tbl_Warehouse (ProductLine,PartNumber,MaterialType,Model,Balance,LastUpdate,Username,Supplier,Cost) VALUES('$ProductLine','$PartNumber','$MaterialType','$Model','$Balance','$LastUpdate','$Username','$Supplier','$Cost')";
		return $this->query($query);
	}
	
	public function addNewProductWithrawal($fkid,$Date,$InvoiceNo,$TransferSlipNo,$BOH,$IN,$OUT,$EOH,$RecordedBy,$Remarks,$LastUpdate,$Username){
		$query = "INSERT INTO tbl_WarehouseTransaction VALUES('','$fkid','$Date','$InvoiceNo','$TransferSlipNo','$BOH','$IN','$OUT','$EOH','$RecordedBy','$Remarks','$LastUpdate','$Username')";
		return $this->query($query);
	}
	//used on add module
	public function addNewProductWithrawal2($Date,$InvoiceNo,$TransferSlipNo,$BOH,$IN,$OUT,$EOH,$RecordedBy,$Remarks,$LastUpdate,$Username){
		$get_max_id = $this->query("SELECT MAX(`id`) AS id FROM tbl_Warehouse;");
		while($row = mysqli_fetch_array($get_max_id)){
			$fkid = $row['id'];
		}
		$query = "INSERT INTO tbl_WarehouseTransaction VALUES('','$fkid','$Date','$InvoiceNo','$TransferSlipNo','$BOH','$IN','$OUT','$EOH','$RecordedBy','$Remarks','$LastUpdate','$Username')";
		return $this->query($query);
	}
	
	public function getResinInfoMain(){
		/* $query = "SELECT c.*, p1.*
				FROM tbl_Warehouse c
				LEFT JOIN tbl_WarehouseTransaction p1 ON (c.id = p1.pkid)
				LEFT OUTER JOIN tbl_WarehouseTransaction p2 ON (c.id = p2.fkid AND 
					(p1.pkid < p2.pkid))
				WHERE p2.pkid IS NULL";  */
		$query = "SELECT * FROM tbl_Warehouse;";
		return $this->query($query);
	}
	
	public function getResin_TransferSlip(){
		/* $query = "SELECT c.*, p1.*
				FROM tbl_Warehouse c
				LEFT JOIN tbl_WarehouseTransaction p1 ON (c.id = p1.pkid)
				LEFT OUTER JOIN tbl_WarehouseTransaction p2 ON (c.id = p2.fkid AND 
					(p1.pkid < p2.pkid))
				WHERE p2.pkid IS NULL";  */
		$query = "SELECT * FROM tbl_Warehouse_TranSlip ORDER BY `ControlNo` DESC;";
		return $this->query($query);
	}
	
	public function getResin_TransferSlip_lst_ctrl(){
		/* $query = "SELECT c.*, p1.*
				FROM tbl_Warehouse c
				LEFT JOIN tbl_WarehouseTransaction p1 ON (c.id = p1.pkid)
				LEFT OUTER JOIN tbl_WarehouseTransaction p2 ON (c.id = p2.fkid AND 
					(p1.pkid < p2.pkid))
				WHERE p2.pkid IS NULL";  */
		$query = "SELECT MAX(`ControlNo`) AS ControlNo FROM `tbl_Warehouse_TranSlip`;";
		return $this->query($query);
	}
	
	public function update_transfer_slip($pkid,$ControlNo){
		/* $query = "SELECT c.*, p1.*
				FROM tbl_Warehouse c
				LEFT JOIN tbl_WarehouseTransaction p1 ON (c.id = p1.pkid)
				LEFT OUTER JOIN tbl_WarehouseTransaction p2 ON (c.id = p2.fkid AND 
					(p1.pkid < p2.pkid))
				WHERE p2.pkid IS NULL";  */
		$query = "UPDATE `tbl_WarehouseTransaction` SET `TransferSlipNo`='$ControlNo' WHERE `pkid`='$pkid';";
		return $this->query($query);
	}
	
	public function save_new_transfer_slip($ProductLine,$ControlNo,$Date,$IssuedBy,$LastUpdate,$Username){
		$query = "INSERT INTO `tbl_Warehouse_TranSlip`(`ProductLine`, `ControlNo`, `Date`, `IssuedBy`, `LastUpdate`, `Username`) VALUES ('$ProductLine','$ControlNo','$Date','$IssuedBy','$LastUpdate','$Username');";
		return $this->query($query);
		//return $query;
	}
	
	public function get_max_control_no(){
		$query = $this->query("SELECT MAX(`ControlNo`) AS ControlNo FROM `tbl_Warehouse_TranSlip`;");
		while($row = mysqli_fetch_array()){
			$max_control = $row['ControlNo'];
		}
	}
	
	public function load_transfer_slip_transactions(){
		//di ka pa tapos dito, filter mo kung existing na sya sa mga list or check mo kung meron na sya transfer slip no
		//temporary, di pala ok to kase i loload ko pala kada add mag pwede mag double input kung sakali man
		$query  = "SELECT `tw`.`PartNumber`,`tw`.`MaterialType`,`twt`.`pkid`,`twt`.`Out`,`twt`.`Remarks` ";
		$query .= "FROM `tbl_Warehouse` `tw` ";
		$query .= "INNER JOIN tbl_WarehouseTransaction `twt` ";
		$query .= "ON `tw`.`id` = `twt`.`fkid` ";
		$query .= "WHERE `twt`.`Out` <> '0' AND `twt`.`TransferSlipNo` = '' ";
		$query .= "ORDER BY `pkid`;";
		return $this->query($query);
	}
	
		
	public function getResinInfoById($id){
		$query = "SELECT * FROM tbl_Warehouse WHERE id='$id'";
		return $this->query($query);
	}
	
	public function getResinTransactionInfo($fkid){
		$query = "SELECT * FROM tbl_WarehouseTransaction WHERE fkid='$fkid' ORDER BY LastUpdate DESC";
		return $this->query($query);
	}
	
	public function updateBalance($id,$ProductLine,$PartNumber,$MaterialType,$Model,$Balance,$Cost,$Supplier,$LastUpdate,$Username){
		$query = "UPDATE tbl_Warehouse SET ProductLine='$ProductLine', PartNumber = '$PartNumber', MaterialType = '$MaterialType', Model = '$Model', Supplier = '$Supplier', Balance='$Balance', LastUpdate='$LastUpdate',Username='$Username' WHERE `id` ='$id'";
		return $this->query($query);
		//return $query;
	}
	
	public function load_transfer_slip($id){
		$query = "SELECT * FROM `tbl_Warehouse_TranSlip` WHERE `id`='$id';";
		return $this->query($query);
	}
	
	public function load_transfer_slip_per_control($ControlNo){
		$query = "SELECT `tw`.`PartNumber`,`tw`.`MaterialType`,`twt`.`pkid`, `twt`.`Out`,`twt`.`Remarks` FROM `tbl_WarehouseTransaction` `twt` INNER JOIN `tbl_Warehouse` `tw` ON `tw`.`id` = `twt`.`fkid` WHERE `twt`.`TransferSlipNo` = '$ControlNo';";
		return $this->query($query);
	}
	
	public function deleteResinRecord($pkid){
		$query = "DELETE FROM tbl_WarehouseTransaction WHERE pkid='$pkid'";
		return $this->query($query);
	}
	
	//Export to Excel Update Page query
	public function exportResinExcel($fkid,$startDate,$endDate){
		if($startDate!=""){
			$query = "SELECT * FROM tbl_WarehouseTransaction WHERE fkid='$fkid' AND Date BETWEEN '$startDate' AND '$endDate' ORDER BY Date";
		}else{
			$query = "SELECT * FROM tbl_WarehouseTransaction WHERE fkid='$fkid' ORDER BY Date";
		}
		return $this->query($query);
	}
	//Export to Excel Main Page query
	public function exportResinExcelMain(){
		$query = "SELECT * FROM tbl_Warehouse";
		return $this->query($query);
	}
	
	public function exportResinExcelIssuanceSummary($start_date,$end_date){
		$query = "SELECT * FROM `vw_resin_issuance_summary` WHERE `DateIssued` BETWEEN '$start_date' AND '$end_date';";
		return $this->query($query);
	}
	
	public function exportResinExcelReceivedSummary($start_date,$end_date){
		$query = "SELECT * FROM `vw_resin_received_summary` WHERE `DateReceived` BETWEEN '$start_date' AND '$end_date';";
		return $this->query($query);
	}
	
	//getMaxPkid of this partnumber
	public function getLastID($PartNumber){
		$query = "SELECT pkid FROM tbl_WarehouseTransaction WHERE fkPartNumber='$PartNumber' ORDER BY pkid DESC";
		return $this->query($query);
	}
	
	//Delete whole material record
	public function deleteAllProductInfo($PartNumber){
		$query = "DELETE FROM tbl_Warehouse WHERE PartNumber ='$PartNumber'";
		$this->query($query);
		$query = "DELETE FROM tbl_WarehouseTransaction WHERE fkPartNumber ='$PartNumber'";
		return $this->query($query);
	}
	
	public function ImportWarehouseData($ProductLine,$PartNumber,$MaterialType,
										$Supplier,$Model,$LastUpdate,
										$Username){
		$checkExist = $this->query("SELECT * FROM `tbl_Warehouse` WHERE `PartNumber` = '$PartNumber';");
		if($checkExist->num_rows == 0){
			$query = "INSERT INTO `tbl_Warehouse`(`ProductLine`, `PartNumber`, `MaterialType`, `Supplier`, `Model`, `LastUpdate`, `Username`) VALUES ('$ProductLine','".$PartNumber."','".$MaterialType."','$Supplier','$Model','$LastUpdate','$Username');";
			return $this->query($query);
		}else{
			if($PartNumber == ""){
				$query = "INSERT INTO `tbl_Warehouse`(`ProductLine`, `PartNumber`, `MaterialType`, `Supplier`, `Model`, `LastUpdate`, `Username`) VALUES ('$ProductLine','".$PartNumber."','".$MaterialType."','$Supplier','$Model','$LastUpdate','$Username');";
				return $this->query($query);
			}else{
				//return $checkExist;
			}
		}
	}
	
	public function get_product_line(){
		$query = "SELECT * FROM tbl_warehouse_product_line ORDER BY `id`";
		return $this->query($query);
	}

}
	
class YPICS{
	//MALACHIXD CONNECTION INFO
	private $serverM = "192.168.3.251\MSSQLSERVER";
	private $userM ="builder"; 
	private $passM = ""; //empty
	private $databaseM = "";
	private $db_handleM =""; //empty
	
	//FUNCTION TO CONNECT TO MALACHI SERVER	
    private function connect_DBM(){
        $this->db_handleM = mssql_connect($this->serverM,$this->userM,$this->passM);
            return mssql_select_db($this->databaseM,$this->db_handleM);
    }
   
    //FUNCTION TO CLOSE MALACHI SERVER
    private function close_DBM(){
        if(isset($this->db_handleM)){
            mssql_close($this->db_handleM);
        }
    }
	
	/////////////////////////////////PUBLIC FUNCTIONS///////////////////////////////////////////////	
	//FUNCTION TO LOAD PO NUM FROM MALACHI DATABASE
	public function selectPoNum(){ //sample
		$db_found = $this->connect_DBM();
		if($db_found){
			$SQL = "SELECT * FROM VwProdSchedMaster ORDER BY PONum";
			$result = mssql_query($SQL);
			return $result;
			mssql_free_result($result);
		}
		else{
			$errorMsg = "Database not found, Please contact your administrator";
		}
		$this->close_DBM();
	}
	
}
?>
