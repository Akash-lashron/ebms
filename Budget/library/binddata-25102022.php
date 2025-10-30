<?php
ob_start();
require_once 'library/config.php';
//require_once 'ExcelReader/excel_reader2.php';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
class BindList
{
	public function BindFinancialYear()
	{
		$StartYear = 2013;
		$CurrYear = date('Y');
		$NextYear = date('Y', strtotime('+1 year'));

		for($i=$NextYear; $i>$StartYear; $i--){
			$FinYear1 = $i;
			$FinYear2 = $i-1;
			$FinYear = $FinYear2."-".$FinYear1;
			$FinYearsheet .=  '<option value="'. $FinYear.'">'.$FinYear.'</option>';
		}
		return $FinYearsheet;        
	}
	public function BindWorkOrderNo($workordernolistvalue)
	{
		global $dbConn;
		$sheetquery = "SELECT sheet_id,short_name,assigned_staff FROM sheet WHERE active=1 ORDER BY sheet_id ASC";
		$sheetsqlquery = mysqli_query($dbConn, $sheetquery);
		if ($sheetsqlquery == true )
		{
			while($row = mysqli_fetch_array($sheetsqlquery))
			{
				$assigned_staff = $row['assigned_staff'];
				$AssignStaff = explode(",",$assigned_staff);
				//if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1))
				//{
					if ($workordernolistvalue == $row['sheet_id'])
					{
						$sel = "selected";
					}
					else
					{
						$sel = "";
					}
					$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['short_name'].'</option>';
				//}
			}            
		}
		return $sheet;            
	}
	public function BindPinNo()
	{
		global $dbConn;
		$pinquery = "SELECT * FROM pin WHERE active = 1";
		$pinsqlquery = mysqli_query($dbConn, $pinquery);

		while($row = mysql_fetch_array($sheetsqlquery))
		{
			if ($workordernolistvalue == $row['pin_id'])
			{
				$sel = "selected";
			}
			else
			{
				$sel = "";
			}
			$pinout .=  '<option value="'. $row['pin_id'].'"'.$sel.'>'.$row['pin_no'].'</option>';
		}            
		return $pinvalue;            
	}
	public function BindAllDaeUnits($UnitId)
	{
		global $dbConn;
		$UnitsQuery = "SELECT * FROM dae_units WHERE active = 1 ORDER BY unit_name ASC";
		$UnitsSql 	= mysqli_query($dbConn,$UnitsQuery);
		if($UnitsSql == true ){
			while($UnitsList = mysqli_fetch_array($UnitsSql)){
				if($UnitId == $UnitsList['unitid']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$Units .=  '<option value="'. $UnitsList['unitid'].'"'.$sel.'>'.$UnitsList['unit_name'].'</option>'; 
			}            
		}
		return $Units; 
	} 
	
	public function BindAllWorksBudget($SheetId)
	{
		global $dbConn;
		$UnitsQuery = "SELECT * FROM sheet WHERE active = 1 ORDER BY short_name ASC";
		$UnitsSql 	= mysql_query($UnitsQuery);
		if($UnitsSql == true ){
			while($UnitsList = mysql_fetch_array($UnitsSql)){
				if($SheetId == $UnitsList['sheet_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$Units .=  '<option value="'. $UnitsList['sheet_id'].'"'.$sel.'>'.$UnitsList['short_name'].'</option>'; 
			}            
		}
		return $Units; 
	} 
	public function BindAllHOABudget($HoaId)
	{
		global $dbConn;
		$UnitsQuery = "SELECT * FROM hoa WHERE active = 1 ORDER BY hoa_no ASC";
		$UnitsSql 	= mysql_query($UnitsQuery);
		if($UnitsSql == true ){
			while($UnitsList = mysql_fetch_array($UnitsSql)){
				if($HoaId == $UnitsList['hoa_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$Units .=  '<option value="'. $UnitsList['hoa_id'].'"'.$sel.'>'.$UnitsList['hoa_no'].'</option>'; 
			}            
		}
		return $Units; 
	}
	public function BindAllDepartment($Deptid)
	{
		global $dbConn;
		$DeptsQuery = "SELECT * FROM department WHERE active = 1 AND  par_dept_id != 0 ORDER BY dept_name ASC";
		$DeptsSql 	= mysql_query($DeptsQuery);
		if($DeptsSql == true ){
			while($DeptList = mysql_fetch_array($DeptsSql)){
				if($Deptid == $DeptList['deptid']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$Units .=  '<option value="'. $DeptList['deptid'].'"'.$sel.'>'.$DeptList['dept_name'].'</option>'; 
			}            
		}
		return $Units; 
	}
	public function BindAllDesignation($DesignId)
	{
		global $dbConn;
		$DesignQuery = "SELECT * FROM `designation` WHERE active = 1 ORDER BY designationname ASC";
		$DesignSql   = mysql_query($DesignQuery);
		if($DesignSql == true ){
			while($row = mysql_fetch_array($DesignSql)){
				if ($DesignId == $row['designationid']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$designation .=  '<option value="'. $row['designationid'].'"'.$sel.'>'.$row['designationname'].'</option>'; 
			}            
		}
		return $designation;            
	} 
	public function BindTechnicalNo($TsId){
		global $dbConn;
		$selectquery 	= "select * from technical_sanction ORDER BY ts_id asc";
		$selectsql 		= mysqli_query($dbConn,$selectquery);
		if($selectsql == true ){
			while($row = mysqli_fetch_array($selectsql)){
				if($TsId == $row['ts_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$res .=  '<option value="'. $row['ts_id'].'"'.$sel.'>'.$row['ts_no'].'</option>';
			}            
		}
		return $res;            
	}
	public function BindPriceBidTrNo($TrId)
	{
		global $dbConn;
		$sheetquery = "SELECT * FROM tender_register ORDER BY tr_id ASC";
		$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
		if ($sheetsqlquery == true )
		{
			while($row = mysqli_fetch_array($sheetsqlquery))
			{
				if($TrId == $row['tr_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['tr_no'].'</option>';
			}            
		}
		return $sheet;            
	}
	public function BindCont($contid)
	{ 	
		global $dbConn;
		$mbookquerys = "select * from contractor where active = 1";
		$mbooksqlquerys = mysqli_query($dbConn,$mbookquerys);
		if ($mbooksqlquerys == true ) {
			while($row = mysqli_fetch_array($mbooksqlquerys))
			{
				if($contid == $row['contid']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$mbooks .=  '<option value="'. $row['contid'].'"'.$sel.'>'.$row['name_contractor'].'</option>';     
			}            
		}
		return $mbooks;     
	}
		
}
$objBind = new BindList();
?>