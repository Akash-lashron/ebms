<?php
ob_start();
require_once 'library/config.php';
//require_once 'ExcelReader/excel_reader2.php';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
class BindList
{
	public function BindFinancialYear($FinYr)
	{
		$StartYear = 2013;
		$CurrYear = date('Y');
		$NextYear = date('Y', strtotime('+1 year'));

		for($i=$NextYear; $i>$StartYear; $i--){
			$FinYear1 = $i;
			$FinYear2 = $i-1;
			$FinYear = $FinYear2."-".$FinYear1;
			if($FinYear == $FinYr){
				$FinYearsheet .=  '<option value="'. $FinYear.'" selected="selected">'.$FinYear.'</option>';
			}else{
				$FinYearsheet .=  '<option value="'. $FinYear.'">'.$FinYear.'</option>';
			}
		}
		return $FinYearsheet;        
	}
	
	public function BindScheduleofActivity()
	{
		global $dbConn;
			$Selectquery = "SELECT * FROM schedule_act ORDER BY sl_no ASC ";
			$Selectsql = mysqli_query($dbConn, $Selectquery);
			if ($Selectsql == true ){
				while($Row = mysqli_fetch_array($Selectsql)){
					$sheet .= '<option value="'.  $Row['sl_no'].'">'.$Row['sl_no'].'-'.$Row['activity'].'</option>';

				}         
			}
			return $sheet;      
	}
	public function BindMajorItems()
	{
		global $dbConn;
			$Selectquery = "SELECT * FROM major_misc_items ORDER BY id ASC ";
			$Selectsql = mysqli_query($dbConn, $Selectquery);
			if ($Selectsql == true ){
				while($Row = mysqli_fetch_array($Selectsql)){
					$sheet .= '<option value="'.  $Row['id'].'">'.$Row['id'].'-'.$Row['items'].'</option>';

				}         
			}
			return $sheet;      
	}
	public function BindPlantService()
	{
		global $dbConn;
			$Selectquery = "SELECT * FROM plant_service ORDER BY id ASC ";
			$Selectsql = mysqli_query($dbConn, $Selectquery);
			if ($Selectsql == true ){
				while($Row = mysqli_fetch_array($Selectsql)){
					$sheet .= '<option value="'.  $Row['id'].'">'.$Row['plant'].'</option>';

				}         
			}
			return $sheet;      
	}
	public function BindStates($StateId)
	{
		global $dbConn;
		$sheetquery = "SELECT * FROM state_master ORDER BY state_name ASC";
		$sheetsqlquery = mysqli_query($dbConn, $sheetquery);
		if ($sheetsqlquery == true ){
			while($row = mysqli_fetch_array($sheetsqlquery)){
				if($StateId == $row['state_code']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$sheet .=  '<option value="'. $row['state_code'].'"'.$sel.'>'.$row['state_name'].'</option>';
			}            
		}
		return $sheet;            
	}
	public function BindDiscipline($disciplineId,$StateId)
	{
		global $dbConn;
		$SelectDiscQuery = "SELECT disciplineid,discipline_name FROM discipline WHERE active = 1";
		$Discsqlquery = mysqli_query($dbConn, $SelectDiscQuery);
		if ($Discsqlquery == true )
		{
			while($row = mysqli_fetch_array($Discsqlquery))
			{
				$Disc_Id 	= $row['disciplineid'];
				$Disc_Name 	= $row['discipline_name'];
				//if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1))
				//{
					if ($disciplineId == $row['disciplineid'])
					{
						$sel = "selected";
					}
					else
					{
						$sel = "";
					}
					$DiscData .=  '<option value="'. $row['disciplineid'].'"'.$sel.'>'.$row['discipline_name'].'</option>';
				//}
			}            
		}
		return $DiscData;            
	}
	public function BindWorkOrderNo($workordernolistvalue)
	{
		global $dbConn;
		$sheetquery = "SELECT sheet_id,short_name,assigned_staff FROM sheet WHERE active=1 ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
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
	public function BindLiveWorks($WorkId,$staffid)
	{
		global $dbConn;
		if($_SESSION['isadmin'] == 1){
			$sheetquery = "SELECT * FROM sheet WHERE active = 1 ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
		}else{
			$sheetquery = "SELECT * FROM sheet WHERE active = 1 AND (eic='".$_SESSION['sid']."' OR FIND_IN_SET(".$_SESSION['sid'].",assigned_staff)) ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
		}
		$sheetsqlquery = mysqli_query($dbConn, $sheetquery);
		if ($sheetsqlquery == true ){
			while($row = mysqli_fetch_array($sheetsqlquery)){
				if($WorkId == $row['globid']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				if(($row['short_name'] != '')&&($row['short_name'] != NULL)){
					$WorkName = $row['short_name'];
				}else{
					$WorkName = $row['work_name'];
				}
				$sheet .=  '<option value="'. $row['globid'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkName.'</option>';
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
	public function BindWorkOrderNoListBudget($workordernolistvalue)
	{
		global $dbConn;
		$sheetquery = "SELECT sheet_id,short_name,assigned_staff,computer_code_no FROM sheet WHERE active = 1 ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
		$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
		if ($sheetsqlquery == true )
		{
			while($row = mysqli_fetch_array($sheetsqlquery))
			{
				
					if ($workordernolistvalue == $row['sheet_id'])
					{
						$sel = "selected";
					}
					else
					{
						$sel = "";
					}
					$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].'-'.$row['short_name'].'</option>';
				//}
			}            
		}
		return $sheet;            
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
		$UnitsQuery = "SELECT * FROM sheet WHERE active = 1 ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
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
		/*$UnitsQuery = "SELECT * FROM hoa WHERE active = 1 ORDER BY hoa_no ASC";
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
		}*/
		$HoaArr = array(); $Hoas = "";
		$UnitsQuery = "(SELECT old_hoa_no, new_hoa_no FROM hoa_master WHERE active = 1) UNION (SELECT old_hoa_no, new_hoa_no FROM hoa_detail WHERE active = 1)";
		$UnitsSql 	= mysqli_query($dbConn,$UnitsQuery);
		if($UnitsSql == true ){
			if(mysqli_num_rows($UnitsSql)>0){
				while($HoaList = mysqli_fetch_object($UnitsSql)){
					if(in_array($HoaList->old_hoa_no, $HoaArr)){
						
					}else{
						array_push($HoaArr,$HoaList->old_hoa_no);
					}
					if(in_array($HoaList->new_hoa_no, $HoaArr)){
						
					}else{
						array_push($HoaArr,$HoaList->new_hoa_no);
					}
					
				}
			}           
		}
		if(count($HoaArr)>0){
			sort($HoaArr);
			foreach($HoaArr as $HoaValue){
				$Hoas .=  '<option value="'. $HoaValue.'"'.$sel.'>'.$HoaValue.'</option>'; 
			}
		}
		return $Hoas; 
	}
	public function BindHOAMaster($hoaid,$financial_year)
	{   
		global $dbConn;
		$ExpHoa = explode(",",$hoaid);
		$mbookquery =  "SELECT hoamast_id,new_hoa_no FROM hoa_master WHERE fin_year ='" . $financial_year . "' AND active = 1 ORDER BY hoamast_id ASC";
		$mbooksqlquery = mysqli_query($dbConn,$mbookquery);
			if ($mbooksqlquery == true ) {
			while($row = mysqli_fetch_array($mbooksqlquery))
			{
					//if($hoa == $row['hoa']){
					if(in_array($row['hoamast_id'], $ExpHoa)){
						$sel = "selected";
					}else{
						$sel = "";
					}
						$mbook .=  '<option value="'.$row['hoamast_id'].'"'.$sel.'>'.$row['new_hoa_no'].'</option>';     
			}            
			}
		return $mbook;     
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
	public function BindAllSubSection($SubSecid,$SubSecid1)
	{
		global $dbConn;
		$SubSecQuery = "SELECT sub_sec_id,sub_sec_name FROM sub_section WHERE active = 1 AND par_dept_id != 0 ORDER BY sub_sec_id ASC";
		$SubSecQuerySql 	= mysqli_query($dbConn,$SubSecQuery);
		if($SubSecQuerySql == true ){
			while($SubSecList = mysqli_fetch_array($SubSecQuerySql)){
				if($SubSecid == $SubSecList['sub_sec_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$Units .=  '<option value="'. $SubSecList['sub_sec_id'].'"'.$sel.'>'.$SubSecList['sub_sec_name'].'</option>';
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
		if(($TsId != '')||($TsId != null)){
		   	global $dbConn;
		   	if($_SESSION['isadmin'] == 1){
		   		$selectquery = "select * from technical_sanction ORDER BY ts_id asc";
		   	}else{
				$selectquery = "select * from technical_sanction WHERE (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY ts_id asc";
		   	}
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
		}else{	
			global $dbConn;
			$TrArr = array();
				if($_SESSION['isadmin'] == 1){
					$sheetNegoquery = "SELECT ts_id FROM tender_register  ORDER BY ts_id ASC";
				}else{
					$sheetNegoquery = "SELECT ts_id FROM tender_register WHERE (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY ts_id ASC";
				}
				$sheetNegoquerysql = mysqli_query($dbConn,$sheetNegoquery);
				if ($sheetNegoquerysql == true )
				{
					while($row1 = mysqli_fetch_array($sheetNegoquerysql))
					{
						$TrId1 = $row1['ts_id'];
						array_push($TrArr,$TrId1);
					}
				}
			$ImplodeTrArr = implode(',',$TrArr);
			if (($ImplodeTrArr != '')||($ImplodeTrArr != null)){
				$selectquery 	= "select * from technical_sanction WHERE ts_id NOT IN(".$ImplodeTrArr.") AND ts_nit ='S' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY ts_id ASC";
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
			}else{
			$selectquery 	= "select * from technical_sanction  WHERE ts_nit ='S' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY ts_id ASC";
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
			}
			
	
		}
		return $res;            
	}
	public function BindPriceBidTrNo($TrId)
	{
		global $dbConn;
		$sheetquery = "SELECT * FROM tender_register WHERE loi_issue != 'Y' ORDER BY tr_id ASC";
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
	/*public function BindCstTrNo($TrId,$Page)			//////// Before 04-01-2023 Update From Rakel
	{
		global $dbConn;
		if($Page == "TOACC"){
			$TrArr = array();
			$sheetNegoquery = "SELECT tr_id FROM bidder_bid_master WHERE is_negotiate = 'Y' ORDER BY tr_id ASC";
			$sheetNegoquerysql = mysqli_query($dbConn,$sheetNegoquery);
			if ($sheetNegoquerysql == true )
			{
				while($row1 = mysqli_fetch_array($sheetNegoquerysql))
				{
					$TrId1 = $row1['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			$ImplodeTrArr = implode(',',$TrArr);
			$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE loi_issue != 'Y' AND cst_status != 'A' AND cst_acc_status != 'C' AND tr_id NOT IN(".$ImplodeTrArr.") ORDER BY tr_id ASC";
		}else if($Page == "TOUSERVIEW"){
			$sheetquery = "SELECT tr_id,tr_no FROM tender_register ORDER BY tr_id ASC";
		}else if($Page == "NEGOENT"){
			$TrArr = array();
			//select distinct contid from bidder_bid_master where tr_id = '$MastId' and is_negotiate IS NULL
			$sheetquery1 = "SELECT tr_id FROM bidder_bid_master WHERE is_negotiate IS NULL ORDER BY tr_id ASC";
			$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
			if ($sheetsqlquery1 == true )
			{
				while($row1= mysqli_fetch_array($sheetsqlquery1))
				{
					$TrId1 = $row1['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			//$ImplodeTrArr = implode(',',$TrArr);
			$sheetquery1 = "SELECT * FROM tender_register WHERE loi_issue != 'Y' AND ncst_acc_status IS NULL";// IS NULL AND nego_status IS NULL AND ncst_acc_status IS NULL OR tr_id IN(".implode(',',$TrArr).") AND loi_issue IS NULL ORDER BY tr_id ASC";
		}else if($Page == "NEGOACC"){
			$TrArr = array();
			//select distinct contid from bidder_bid_master where tr_id = '$MastId' and is_negotiate IS NULL
			$sheetquery1 = "SELECT tr_id FROM bidder_bid_master WHERE is_negotiate = 'Y' ORDER BY tr_id ASC";
			$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
			if ($sheetsqlquery1 == true )
			{
				while($row1 = mysqli_fetch_array($sheetsqlquery1))
				{
					$TrId1 = $row1['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			$ImplodeTrArr = implode(',',$TrArr);
			$sheetquery = "SELECT * FROM tender_register WHERE loi_issue IS NULL AND nego_status IS NULL AND ncst_acc_status IS NULL OR tr_id IN(".$ImplodeTrArr.") ORDER BY tr_id ASC";
		}else if($Page == "NEGOTOUSERVIEW"){
			$TrArr = array();
			//select distinct contid from bidder_bid_master where tr_id = '$MastId' and is_negotiate IS NULL
			$sheetquery1 = "SELECT tr_id FROM bidder_bid_master WHERE is_negotiate = 'Y' ORDER BY tr_id ASC";
			$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
			if ($sheetsqlquery1 == true )
			{
				while($row1 = mysqli_fetch_array($sheetsqlquery1))
				{
					$TrId1 = $row1['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			$ImplodeTrArr = implode(',',$TrArr);
			$sheetquery = "SELECT * FROM tender_register WHERE loi_issue IS NULL AND nego_status IS NULL AND ncst_acc_status IS NULL OR tr_id IN(".$ImplodeTrArr.") ORDER BY tr_id ASC";
		}else if($Page == "CCNOGEN"){
			//$WhereClauseSt = "WHERE is_negotiate = 'Y' AND nego_status = 'ACC' NULL AND ncst_acc_status IS NULL";
			//$sheetquery = "SELECT * FROM tender_register " . $WhereClauseSt . " ORDER BY tr_id ASC";
			$sheetquery = "SELECT * FROM tender_register WHERE loi_issue IS NULL AND nego_status = 'ACC' OR status  = 'ACC' ORDER BY tr_id ASC";
		}else if($Page == "WOENTRY"){
			//$WhereClauseSt = "WHERE is_negotiate = 'Y' AND nego_status = 'ACC' NULL AND ncst_acc_status IS NULL";
			//$sheetquery = "SELECT * FROM tender_register " . $WhereClauseSt . " ORDER BY tr_id ASC";
			$sheetquery = "SELECT * FROM tender_register WHERE loi_issue ='Y' ORDER BY tr_id ASC";
		}
		//echo "XX = ".$sheetquery;exit;
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
	}*/
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
	
	public function BindDesignation($designationlistvalue,$sectionid)
	{
		global $dbConn;
		$designationquery = "SELECT designationid, designationname FROM `designation` WHERE active=1 AND sectionid = '$sectionid' ORDER BY designationid ASC";
		$designationsqlquery = mysqli_query($dbConn,$designationquery);
		if ($designationsqlquery == true ){
			while($row = mysqli_fetch_array($designationsqlquery)){
				if ($designationlistvalue == $row['designationid'])
				{
					$sel = "selected";
				}else{
					$sel = "";
				}
				$designation .=  '<option value="'. $row['designationid'].'"'.$sel.'>'.$row['designationname'].'</option>'; 
			}            
		}
		return $designation;            
	} 


	public function BindStaffRole($rolevalue,$sectionid)
	{
		global $dbConn;
		$levelquery = "SELECT sroleid, role_name FROM staffrole WHERE active=1 AND sectionid = '$sectionid' ORDER BY levelid desc";
		$levelsql = mysqli_query($dbConn,$levelquery);
		   if ($levelsql == true )
		   {
			while($row = mysqli_fetch_array($levelsql))
			{
				if ($rolevalue == $row['sroleid'])
				{
					$sel = "selected";
				}
				else
				{
					$sel = "";
				}
				$section .=  '<option value="'. $row['sroleid'].'"'.$sel.'>'.$row['role_name'].'</option>'; 
			}            
		   }
		return $section;            
	}
	public function BindAllStaffRole($rolevalue)
	{
		global $dbConn;
		$levelquery = "SELECT sroleid, role_name FROM staffrole WHERE active=1 ORDER BY levelid desc";
		$levelsql = mysqli_query($dbConn,$levelquery);
		   if ($levelsql == true )
		   {
			while($row = mysqli_fetch_array($levelsql))
			{
				if ($rolevalue == $row['sroleid'])
				{
					$sel = "selected";
				}
				else
				{
					$sel = "";
				}
				$section .=  '<option value="'. $row['sroleid'].'"'.$sel.'>'.$row['role_name'].'</option>'; 
			}            
		   }
		return $section;            
	}
	
	public function BindObjHeadWithHoa($ObjHead)
	{
		global $dbConn;
		$SelectQuery = "SELECT a.*, b.* FROM hoa_master a INNER JOIN object_head b ON (a.obj_head_id = b.ohid) WHERE a.active = 1 AND b.active = 1 ORDER BY new_hoa_no ASC";
		$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
		if($SelectSql == true ){
			while($List = mysqli_fetch_object($SelectSql)){
				if($ObjHead == $List->new_hoa_no){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$Result .=  '<option value="'.$List->new_hoa_no.'"'.$sel.'>'.$List->obj_head.' - '.$List->new_hoa_no.'</option>'; 
			}            
		}
		return $Result;            
	}
	public function BindHOA($hoaid)
    {   
        global $dbConn;
        $ExpHoa = explode(",",$hoaid);
        $mbookquery = "select * from hoa where active = 1";
        $mbooksqlquery = mysqli_query($dbConn,$mbookquery);
           if ($mbooksqlquery == true ) {
            while($row = mysqli_fetch_array($mbooksqlquery))
            {
                //if($hoa == $row['hoa']){
                if(in_array($row['hoa_id'], $ExpHoa)){
                    $sel = "selected";
                }else{
                    $sel = "";
                }
                   $mbook .=  '<option value="'.$row['hoa_id'].'"'.$sel.'>'.$row['hoa_no'].'</option>';     
            }            
           }
       return $mbook;     
    }
	 public function BindStaff($EICid)
	 { 	
		 global $dbConn;
		 $mbookquerys = "select * from staff where active = 1 and sectionid != 2";
		 $mbooksqlquerys = mysqli_query($dbConn,$mbookquerys);
		 if ($mbooksqlquerys == true ) {
			 while($row = mysqli_fetch_array($mbooksqlquerys))
			 {
				 if($EICid == $row['staffid']){
					 $sel = "selected";
				 }else{
					 $sel = "";
				 }
				 $mbooks .=  '<option value="'. $row['staffid'].'" '.$sel.' >'.$row['staffcode'].' -'.$row['staffname'].'</option>';     
			 }            
		 }
		 return $mbooks;     
	 }
	 public function BindStaffAcc($EICid)
	 { 	
		 global $dbConn;
		 $mbookquerys = "select * from staff where active = 1 and sectionid = 2";
		 $mbooksqlquerys = mysqli_query($dbConn,$mbookquerys);
		 if ($mbooksqlquerys == true ) {
			 while($row = mysqli_fetch_array($mbooksqlquerys))
			 {
				 if($EICid == $row['staffid']){
					 $sel = "selected";
				 }else{
					 $sel = "";
				 }
				 $mbooks .=  '<option value="'. $row['staffid'].'" '.$sel.'>'.$row['staffcode'].' -'.$row['staffname'].'</option>';     
			 }            
		 }
		 return $mbooks;     
	 }
	 public function BindDepEstTrNo($TrId)
	 {
		 global $dbConn;
		 if($_SESSION['isadmin'] == 1){
		 	$sheetquery = "SELECT * FROM tender_register WHERE ((cst_status != 'A' AND cst_status != 'C') OR cst_status IS NULL) ORDER BY tr_id ASC";
		 }else{
		 	$sheetquery = "SELECT * FROM tender_register WHERE ((cst_status != 'A' AND cst_status != 'C') OR cst_status IS NULL) AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
		 }
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
	 public function BindEMDTrNo($TrId)
	 {
		 if($TrId != null){
			 global $dbConn;
			 if($_SESSION['isadmin'] == 1){
			 	$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE tr_id = '$TrId' ORDER BY tr_id ASC";
			 }else{
			 	$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE tr_id = '$TrId' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			 }
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
		 }else{
			 global $dbConn;
			 $TrArr = array();
			 $ImplodeTrArr = NULL;
			 $sheettablequery = "SELECT globid FROM works WHERE work_status = 'NIT'  ORDER BY globid ASC";
			 $sheetNegoquerysql = mysqli_query($dbConn,$sheettablequery);
			 if ($sheetNegoquerysql == true )
			 {
				 while($row1 = mysqli_fetch_array($sheetNegoquerysql))
				 {
					 $TrId1 = $row1['globid'];
					 array_push($TrArr,$TrId1);
				 }
			 }
			 $ImplodeTrArr = implode(',',$TrArr);
			 if($ImplodeTrArr != null){
			 	 if($_SESSION['isadmin'] == 1){
				 	$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE globid IN (".$ImplodeTrArr.") ORDER BY tr_id ASC";
				 }else{
				 	$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE globid IN (".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
				 }
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
			 }else{
			 	 if($_SESSION['isadmin'] == 1){
				 	$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE globid IN ('".$ImplodeTrArr."') ORDER BY tr_id ASC";
				 }else{
				 	$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE globid IN ('".$ImplodeTrArr."') AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
				 }
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
			  }
		 }
		 
		 return $sheet;            
	 }
	 public function BindPriceuploadTrNo($TrId)
	 {
		global $dbConn;
		$TrArr = array();
		$sheettablequery = "SELECT tr_id FROM partab_master ORDER BY tr_id ASC";
		$sheetNegoquerysql = mysqli_query($dbConn,$sheettablequery);
		if ($sheetNegoquerysql == true )
		{
			while($row1 = mysqli_fetch_array($sheetNegoquerysql))
			{
				$TrId1 = $row1['tr_id'];
				array_push($TrArr,$TrId1);
			}
		}
		$ImplodeTrArr = implode(',',$TrArr);
		if($ImplodeTrArr != null){
		if($_SESSION['isadmin'] == 1){
			$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE loi_issue != 'Y'  AND ((cst_status != 'A' AND cst_status != 'C') OR cst_status IS NULL) AND tr_id IN (".$ImplodeTrArr.") ORDER BY tr_id ASC";
		}else{
			$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE loi_issue != 'Y'  AND ((cst_status != 'A' AND cst_status != 'C') OR cst_status IS NULL) AND tr_id IN (".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
		}
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
	}
		return $sheet;            
	}

	public function BindLOITrNo($TrId)
	{  
		if(($TrId != '')||($TrId != null)){
			global $dbConn;
			//$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE (cst_acc_status = 'C' AND nego_status  !='A') OR ncst_acc_status ='C' ORDER BY tr_id ASC";
			if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE (cst_acc_status = 'C' AND (nego_status != 'A' OR nego_status IS NULL)) OR ncst_acc_status ='C' ORDER BY tr_id ASC";
			}else{
				$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE ((cst_acc_status = 'C' AND (nego_status != 'A' OR nego_status IS NULL)) OR ncst_acc_status ='C') AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			}
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
					$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
				}            
			}
		}else{ 
			global $dbConn;
			$TrArr = array();
			$sheetNegoquery = "SELECT tr_id FROM loi_entry  ORDER BY tr_id ASC";
			$sheetNegoquerysql = mysqli_query($dbConn,$sheetNegoquery);
			if ($sheetNegoquerysql == true )
			{
				while($row1 = mysqli_fetch_array($sheetNegoquerysql))
				{
					$TrId1 = $row1['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			$ImplodeTrArr = implode(',',$TrArr);
			if(($ImplodeTrArr != '')||($ImplodeTrArr != null)){
				if($_SESSION['isadmin'] == 1){
					$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE (cst_acc_status = 'C' AND (nego_status !='A' OR nego_status IS NULL) AND tr_id NOT IN(".$ImplodeTrArr.")) OR (ncst_acc_status ='C'AND tr_id NOT IN(".$ImplodeTrArr.")) ORDER BY tr_id ASC";
				}else{
					$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE (cst_acc_status = 'C' AND (nego_status !='A' OR nego_status IS NULL) AND tr_id NOT IN(".$ImplodeTrArr.")) OR (ncst_acc_status ='C'AND tr_id NOT IN(".$ImplodeTrArr.")) AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
				}
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
						$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
					}            
				}
			}else{
				if($_SESSION['isadmin'] == 1){
					$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE (cst_acc_status = 'C' AND (nego_status  !='A' OR nego_status IS NULL) AND tr_id NOT IN('".$ImplodeTrArr."')) OR (ncst_acc_status ='C'AND tr_id NOT IN('".$ImplodeTrArr."'))  ORDER BY tr_id ASC";
				}else{
					$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE (cst_acc_status = 'C' AND (nego_status  !='A' OR nego_status IS NULL) AND tr_id NOT IN('".$ImplodeTrArr."')) OR (ncst_acc_status ='C'AND tr_id NOT IN('".$ImplodeTrArr."')) AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
				}
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
						$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
					}            
				}
			}
		   
		}
		return $sheet;      
	}
	public function BindPGTrNo($TrId)
	{  
		if(($TrId != '')||($TrId != null)){
			global $dbConn;
			$TrArr = array();
			$sheettablequery = "SELECT DISTINCT globid FROM bg_fdr_details WHERE approved_session = 'ACC' ORDER BY globid ASC";
			$sheetNegoquerysql = mysqli_query($dbConn,$sheettablequery);
			if ($sheetNegoquerysql == true )
			{
				while($row1 = mysqli_fetch_array($sheetNegoquerysql))
				{
					$TrId1 = $row1['globid'];
					array_push($TrArr,$TrId1);
				}
			}
			$ImplodeTrArr = implode(',',$TrArr);
				if($_SESSION['isadmin'] == 1){
					$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE loi_issue = 'Y' AND globid NOT IN ('".$ImplodeTrArr."') ORDER BY tr_id ASC";
				}else{
					$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE loi_issue = 'Y' AND globid NOT IN ('".$ImplodeTrArr."') AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
				}
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
						$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
					}            
				}
		}else{
			global $dbConn;
			$TrArr = array();
			$sheettablequery = "SELECT DISTINCT globid FROM bg_fdr_details WHERE inst_purpose = 'PG' ORDER BY globid ASC";
			$sheetNegoquerysql = mysqli_query($dbConn,$sheettablequery);
			if ($sheetNegoquerysql == true )
			{
				while($row1 = mysqli_fetch_array($sheetNegoquerysql))
				{
					$TrId1 = $row1['globid'];
					array_push($TrArr,$TrId1);
				}
			} 	$ImplodeTrArr = implode(',',$TrArr);
			if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE loi_issue = 'Y' AND globid NOT IN ('".$ImplodeTrArr."') ORDER BY tr_id ASC";
			}else{
				$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE loi_issue = 'Y' AND globid NOT IN ('".$ImplodeTrArr."') AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			}
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
					$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['ccno'].' - '.$row['tr_no'].'</option>';
				}            
			}
		 }
		
		return $sheet;            
	}
	public function BindWorkOrderNoSD($SheetId)
	{
		if(($SheetId != '')||($SheetId != null)){
			global $dbConn;
			if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT sheet_id,work_name,short_name,assigned_staff,computer_code_no FROM sheet WHERE active = 1 ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
			}else{
				$sheetquery = "SELECT sheet_id,work_name,short_name,assigned_staff,computer_code_no FROM sheet WHERE active = 1 AND (eic = '".$_SESSION['sid']."' OR FIND_IN_SET(".$_SESSION['sid'].",assigned_staff)) ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
			}
			$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
			if ($sheetsqlquery == true )
			{
				while($row = mysqli_fetch_array($sheetsqlquery))
				{
					if($SheetId == $row['sheet_id'])
					{
						$sel = "selected";
					}
					else
					{
						$sel = "";
					}
					if(($row['short_name'] != '')&&($row['short_name'] != NULL)){
						$WorkName = $row['short_name'];
					}else{
						$WorkName = $row['work_name'];
					}
					$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkName.'</option>';
				}            
			}
		}else{
			global $dbConn;
			$TrArr = array();
			$sheettablequery = "SELECT DISTINCT globid FROM bg_fdr_details WHERE inst_purpose = 'SD' ORDER BY globid ASC";
			$sheetNegoquerysql = mysqli_query($dbConn,$sheettablequery);
			if ($sheetNegoquerysql == true )
			{
				while($row1 = mysqli_fetch_array($sheetNegoquerysql))
				{
					$TrId1 = $row1['globid'];
					array_push($TrArr,$TrId1);
				}
			}
			$ImplodeTrArr = implode(',',$TrArr);
			if($ImplodeTrArr != null){
				if($_SESSION['isadmin'] == 1){
					$sheetquery = "SELECT sheet_id,short_name,work_name,assigned_staff,computer_code_no,globid FROM sheet WHERE active = 1 AND globid NOT IN (".$ImplodeTrArr.") ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
				}else{
					$sheetquery = "SELECT sheet_id,short_name,work_name,assigned_staff,computer_code_no,globid FROM sheet WHERE active = 1 AND globid NOT IN (".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR FIND_IN_SET(".$_SESSION['sid'].",assigned_staff)) ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
				}
				$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
				if ($sheetsqlquery == true )
				{
					while($row = mysqli_fetch_array($sheetsqlquery))
					{
						
						if ($SheetId == $row['sheet_id'])
						{
							$sel = "selected";
						}
						else
						{
							$sel = "";
						}
						if(($row['short_name'] != '')&&($row['short_name'] != NULL)){
							$WorkName = $row['short_name'];
						}else{
							$WorkName = $row['work_name'];
						}
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkName.'</option>';
					}            
			  	}
	  	 	}else{
				if($_SESSION['isadmin'] == 1){
					$sheetquery = "SELECT sheet_id,short_name,work_name,assigned_staff,computer_code_no,globid FROM sheet WHERE active = 1  ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
				}else{
					$sheetquery = "SELECT sheet_id,short_name,work_name,assigned_staff,computer_code_no,globid FROM sheet WHERE active = 1 AND (eic = '".$_SESSION['sid']."' OR FIND_IN_SET(".$_SESSION['sid'].",assigned_staff)) ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
				}
				$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
				if ($sheetsqlquery == true )
				{
					while($row = mysqli_fetch_array($sheetsqlquery))
					{
						
						if ($SheetId == $row['sheet_id'])
						{
							$sel = "selected";
						}
						else
						{
							$sel = "";
						}
						if(($row['short_name'] != '')&&($row['short_name'] != NULL)){
							$WorkName = $row['short_name'];
						}else{
							$WorkName = $row['work_name'];
						}
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkName.'</option>';
					}            
			  	}
		   	}
		}
		return $sheet;            
	} 
	public function BindPriceviewTrNo($TrId)
	{
		global $dbConn;
		$TrArr = array();
			$sheettablequery = "SELECT tr_id FROM bidder_bid_master ORDER BY tr_id ASC";
			$sheetNegoquerysql = mysqli_query($dbConn,$sheettablequery);
			if ($sheetNegoquerysql == true )
			{
				while($row1 = mysqli_fetch_array($sheetNegoquerysql))
				{
					$TrId1 = $row1['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
		$ImplodeTrArr = implode(',',$TrArr);
		if($ImplodeTrArr != null){
			if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE tr_id IN (".$ImplodeTrArr.") ORDER BY tr_id ASC";
			}else{
				$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE tr_id IN (".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			}
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
		}
		return $sheet;            
	}
	public function BindWorkOderNo($TrId){
		if(($TrId != '')||($TrId != null)){
		   	global $dbConn;
		   	if($_SESSION['isadmin'] == 1){
		   		$selectquery 	= "SELECT * FROM tender_register WHERE loi_issue ='Y' ORDER BY tr_id ASC";
		   	}else{
		   		$selectquery 	= "SELECT * FROM tender_register WHERE loi_issue ='Y' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
		   	}
		   $selectsql 		= mysqli_query($dbConn,$selectquery);
		   if($selectsql == true ){
			  while($row = mysqli_fetch_array($selectsql)){
				if($TrId == $row['tr_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$res .=  '<option value="'. $row['tr_id'].'"'.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
			  }            
		   }
		}else{	
			global $dbConn;
			$TrArr = array();
			if($_SESSION['isadmin'] == 1){
				$sheetNegoquery = "SELECT tr_id FROM sheet ORDER BY tr_id ASC";
			}else{
				$sheetNegoquery = "SELECT tr_id FROM sheet WHERE (eic = '".$_SESSION['sid']."' OR FIND_IN_SET(".$_SESSION['sid'].",assigned_staff)) ORDER BY tr_id ASC";
			}
			$sheetNegoquerysql = mysqli_query($dbConn,$sheetNegoquery);
			if ($sheetNegoquerysql == true )
			{
				while($row1 = mysqli_fetch_array($sheetNegoquerysql))
				{
					$TrId1 = $row1['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			$ImplodeTrArr = implode(',',$TrArr);
			if(($ImplodeTrArr != '')||($ImplodeTrArr != null)){
				if($_SESSION['isadmin'] == 1){
					$selectquery 	= "select * from tender_register  WHERE tr_id NOT IN(".$ImplodeTrArr.") AND loi_issue ='Y' ORDER BY tr_id ASC";
				}else{
					$selectquery 	= "select * from tender_register  WHERE tr_id NOT IN(".$ImplodeTrArr.") AND loi_issue ='Y' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
				}
				$selectsql 		= mysqli_query($dbConn,$selectquery);
				if($selectsql == true ){
					while($row = mysqli_fetch_array($selectsql)){
						if($TrId == $row['tr_id']){
							$sel = "selected";
						}else{
							$sel = "";
						}
						$res .=  '<option value="'. $row['tr_id'].'"'.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
					}            
				}
			}else{
				if($_SESSION['isadmin'] == 1){
					$selectquery    = "select * from tender_register  WHERE tr_id NOT IN('".$ImplodeTrArr."') AND loi_issue ='Y' ORDER BY tr_id ASC";
				}else{
					$selectquery    = "select * from tender_register  WHERE tr_id NOT IN('".$ImplodeTrArr."') AND loi_issue ='Y' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
				}
				$selectsql 		= mysqli_query($dbConn,$selectquery);
				if($selectsql == true ){
					while($row = mysqli_fetch_array($selectsql)){
						if($TsId == $row['tr_id']){
							$sel = "selected";
						}else{
							$sel = "";
						}
						$res .=  '<option value="'. $row['tr_id'].'"'.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
					}            
				}
			}
		}
		return $res;            
	}
	public function BindPGReturnTrNo($TrId)
	{
		global $dbConn;
		$TrArr = array();
		$sheettablequery = "SELECT DISTINCT globid FROM bg_fdr_details WHERE inst_status = 'R' ORDER BY globid ASC";
		$sheetNegoquerysql = mysqli_query($dbConn,$sheettablequery);
		if ($sheetNegoquerysql == true )
		{
			while($row1 = mysqli_fetch_array($sheetNegoquerysql))
			{
				$TrId1 = $row1['globid'];
				array_push($TrArr,$TrId1);
			}
		}
		$ImplodeTrArr = implode(',',$TrArr);
		if($ImplodeTrArr != null){
			if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT * FROM tender_register WHERE  loi_issue = 'Y' AND  globid NOT IN (".$ImplodeTrArr.") ORDER BY tr_id ASC";
			}else{
				$sheetquery = "SELECT * FROM tender_register WHERE  loi_issue = 'Y' AND  globid NOT IN (".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			}
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
	}
	public function BindSDReturn($workordernolistvalue)
	{
		global $dbConn;
		$TrArr = array();
		$sheettablequery = "SELECT DISTINCT globid FROM bg_fdr_details WHERE inst_status = 'R' ORDER BY globid ASC";
		$sheetNegoquerysql = mysqli_query($dbConn,$sheettablequery);
		if ($sheetNegoquerysql == true )
		{
			while($row1 = mysqli_fetch_array($sheetNegoquerysql))
			{
				$TrId1 = $row1['globid'];
				array_push($TrArr,$TrId1);
			}
		}
		$ImplodeTrArr = implode(',',$TrArr);
		if($ImplodeTrArr != null){ 
			if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT sheet_id,short_name,computer_code_no FROM sheet WHERE active = 1 AND  globid NOT IN (".$ImplodeTrArr.") ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
			}else{
				$sheetquery = "SELECT sheet_id,short_name,computer_code_no FROM sheet WHERE active = 1 AND  globid NOT IN (".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR FIND_IN_SET(".$_SESSION['sid'].",assigned_staff)) ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
			}
			$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
			if ($sheetsqlquery == true )
			{
				while($row = mysqli_fetch_array($sheetsqlquery))
				{
					
						if ($workordernolistvalue == $row['sheet_id'])
						{
							$sel = "selected";
						}
						else
						{
							$sel = "";
						}
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].'-'.$row['short_name'].'</option>';
					//}
				}            
			}
			return $sheet;            
	  } 
  	}
	public function BindEMDReturnTrNo($TrId)
	{
		global $dbConn;
		$TrArr = array();
		$sheettablequery = "SELECT DISTINCT emd_master.globid FROM emd_master INNER JOIN emd_detail ON emd_master.emid = emd_detail.emid
		WHERE emd_detail.status = 'R' ORDER BY emd_master.globid ASC";
		$sheetNegoquerysql = mysqli_query($dbConn,$sheettablequery);
		if ($sheetNegoquerysql == true ) {
			while($row1 = mysqli_fetch_array($sheetNegoquerysql)) {
				$TrId1 = $row1['globid'];
				array_push($TrArr,$TrId1);
			}
		}
		$ImplodeTrArr = implode(',',$TrArr);
		if($ImplodeTrArr != null){ 
			if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE loi_issue = 'Y' AND globid NOT IN (".$ImplodeTrArr.") ORDER BY tr_id ASC";
			}else{
				$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE loi_issue = 'Y' AND globid NOT IN (".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			}
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
					$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
				}            
			}
			return $sheet;            
		}else{
			if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE loi_issue = 'Y' ORDER BY tr_id ASC";
			}else{
				$sheetquery = "SELECT tr_id,tr_no,ccno FROM tender_register WHERE loi_issue = 'Y' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			}
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
					$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['ccno'].' - '.$row['tr_no'].'</option>';
				}            
			}
			return $sheet; 
		}
	}
	public function BindCstLottery($TrId,$Page){
		$sheet = '';
		$TrArr = array();
		$sheetquery1 = "SELECT count(bmid) as count_bmid,bmid,tr_id,quoted_pos FROM bidder_bid_master WHERE quoted_pos = 1 GROUP BY tr_id ASC";
		$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
		if ($sheetsqlquery1 == true )
		{
			while($row1 = mysqli_fetch_array($sheetsqlquery1))
			{
				if($row1['count_bmid'] > 1){
					$TrId1 = $row1['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			$ImplodeTrArr = implode(',',$TrArr);
			if($ImplodeTrArr != null ){
				if($_SESSION['isadmin'] == 1){
					$sheetquery = "SELECT * FROM tender_register WHERE tr_id IN(".$ImplodeTrArr.") ORDER BY tr_id ASC";
				}else{
					$sheetquery = "SELECT * FROM tender_register WHERE tr_id IN(".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
				}
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
						if(($row['ccno'] != "") && ($row['ccno'] != NULL)){
							$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
						}else{
							$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['tr_no'].'</option>';
						}
					}
				}
			}
			return $sheet;
		}
	}


	public function BindCstTrNo($TrId,$Page)			
	{
		global $dbConn;
		if($Page == "TOACC"){
			if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE loi_issue != 'Y' AND ((cst_status = '' OR cst_status IS NULL) AND (cst_acc_status = '' OR cst_acc_status IS NULL)) ORDER BY tr_id ASC"; 
			}else{
				$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE loi_issue != 'Y' AND ((cst_status = '' OR cst_status IS NULL) AND (cst_acc_status = '' OR cst_acc_status IS NULL)) AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC"; 
			}
		}else if($Page == "TOUSERVIEW"){
			if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT tr_id,tr_no FROM tender_register ORDER BY tr_id ASC";
			}else{
				$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			}
		}else if($Page == "NEGOENT"){
			$TrArr = array();
			$sheetquery1 = "SELECT count(bmid) as count_bmid,bmid,tr_id,quoted_pos FROM bidder_bid_master WHERE quoted_pos = 1 GROUP BY tr_id ASC";
			$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
			if ($sheetsqlquery1 == true )
			{
				while($row1 = mysqli_fetch_array($sheetsqlquery1))
				{
					if($row1['count_bmid'] == 1){
						$TrId1 = $row1['tr_id'];
						array_push($TrArr,$TrId1);
					}
				}
				$ImplodeTrArr = implode(',',$TrArr);
				if($ImplodeTrArr != null ){
					if($_SESSION['isadmin'] == 1){	
						$sheetquery = "SELECT * FROM tender_register WHERE loi_issue != 'Y' AND (nego_status = '' OR nego_status IS NULL) AND ncst_acc_status !='C' AND cst_acc_status = 'C'";
					}else{
						$sheetquery = "SELECT * FROM tender_register WHERE loi_issue != 'Y' AND (nego_status = '' OR nego_status IS NULL) AND ncst_acc_status !='C' AND cst_acc_status = 'C' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."')";
					}
				}
			}
			//$sheetquery = "SELECT * FROM tender_register WHERE loi_issue IS NULL AND ((nego_status = '' OR nego_status IS NULL) AND (ncst_acc_status = '' OR ncst_acc_status IS NULL))  AND tr_id IN('".$ImplodeTrArr."') ORDER BY tr_id ASC";
		}else if($Page == "NEGOACC"){
			$TrArr = array();
			//select distinct contid from bidder_biNEGOENTd_master where tr_id = '$MastId' and is_negotiate IS NULL
			$sheetquery1 = "SELECT tr_id FROM bidder_bid_master WHERE is_negotiate = 'Y' ORDER BY tr_id ASC";
			$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
			if ($sheetsqlquery1 == true )
			{
				while($row1 = mysqli_fetch_array($sheetsqlquery1))
				{
					$TrId1 = $row1['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			$ImplodeTrArr = implode(',',$TrArr);
			if($ImplodeTrArr != null ){
				if($_SESSION['isadmin'] == 1){	
					$sheetquery = "SELECT * FROM tender_register WHERE loi_issue != 'Y' AND (nego_status = '' OR nego_status IS NULL) AND ncst_acc_status !='C' AND cst_acc_status = 'C' AND tr_id IN(".$ImplodeTrArr.") ORDER BY tr_id ASC";
				}else{
					$sheetquery = "SELECT * FROM tender_register WHERE loi_issue != 'Y' AND (nego_status = '' OR nego_status IS NULL) AND ncst_acc_status !='C' AND cst_acc_status = 'C' AND tr_id IN(".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
				}
			}else{
				if($_SESSION['isadmin'] == 1){	
					$sheetquery = "SELECT * FROM tender_register WHERE loi_issue != 'Y' AND (nego_status = '' OR nego_status IS NULL) AND ncst_acc_status !='C' AND cst_acc_status = 'C' AND tr_id IN('".$ImplodeTrArr."') ORDER BY tr_id ASC";
				}else{
					$sheetquery = "SELECT * FROM tender_register WHERE loi_issue != 'Y' AND (nego_status = '' OR nego_status IS NULL) AND ncst_acc_status !='C' AND cst_acc_status = 'C' AND tr_id IN('".$ImplodeTrArr."') AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
				}
			}
		}else if($Page == "NEGOTOUSERVIEW"){
			$TrArr = array();
			//select distinct contid from bidder_bid_master where tr_id = '$MastId' and is_negotiate IS NULL
			$sheetquery1 = "SELECT tr_id FROM bidder_bid_master WHERE quoted_pos = 1 AND is_negotiate = 'Y' ORDER BY tr_id ASC";
			$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
			if ($sheetsqlquery1 == true )
			{
				while($row1 = mysqli_fetch_array($sheetsqlquery1))
				{
					$TrId1 = $row1['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			$ImplodeTrArr = implode(',',$TrArr);
			if($_SESSION['isadmin'] == 1){	
				$sheetquery = "SELECT * FROM tender_register WHERE ((loi_issue IS NULL AND nego_status IS NULL AND ncst_acc_status IS NULL) OR (tr_id IN('".$ImplodeTrArr."'))) ORDER BY tr_id ASC";
			}else{
				$sheetquery = "SELECT * FROM tender_register WHERE ((loi_issue IS NULL AND nego_status IS NULL AND ncst_acc_status IS NULL) OR (tr_id IN('".$ImplodeTrArr."'))) AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			}
		}else if($Page == "CCNOGEN"){
			//$WhereClauseSt = "WHERE is_negotiate = 'Y' AND nego_status = 'ACC' NULL AND ncst_acc_status IS NULL";
			//$sheetquery = "SELECT * FROM tender_register " . $WhereClauseSt . " ORDER BY tr_id ASC";
			if($_SESSION['isadmin'] == 1){	
				$sheetquery = "SELECT * FROM tender_register WHERE loi_issue IS NULL AND (nego_status = 'ACC' OR status  = 'ACC') ORDER BY tr_id ASC";
			}else{
				$sheetquery = "SELECT * FROM tender_register WHERE loi_issue IS NULL AND (nego_status = 'ACC' OR status  = 'ACC') AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			}
		}else if($Page == "WOENTRY"){
			//$WhereClauseSt = "WHERE is_negotiate = 'Y' AND nego_status = 'ACC' NULL AND ncst_acc_status IS NULL";
			//$sheetquery = "SELECT * FROM tender_register " . $WhereClauseSt . " ORDER BY tr_id ASC";
			if($_SESSION['isadmin'] == 1){	
				$sheetquery = "SELECT * FROM tender_register WHERE loi_issue ='Y' ORDER BY tr_id ASC";
			}else{
				$sheetquery = "SELECT * FROM tender_register WHERE loi_issue ='Y' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
			}
		}
		//echo "XX = ".$sheetquery;exit;
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
				if(($row['ccno'] != '')||($row['ccno'] !=null)){
					$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
				}else{
					$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['tr_no'].'</option>';
				}
			}
		}
		return $sheet;
	}	
	 public function BIndEstimateForTs($MastId)
	 {
		 global $dbConn;
		 if(($MastId != '')&&($MastId != 0)&&($MastId != NULL)){
			 if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT a.* FROM partab_master a INNER JOIN works b ON (a.globid = b.globid) WHERE a.is_confirmed = 'Y' AND a.mastid = '$MastId' ORDER BY a.tr_id ASC";
			 }else{
				$sheetquery = "SELECT a.* FROM partab_master a INNER JOIN works b ON (a.globid = b.globid) WHERE a.is_confirmed = 'Y' AND a.mastid = '$MastId' AND a.created_by = '".$_SESSION['sid']."' ORDER BY tr_id ASC";
			 }
		 }else{
			 if($_SESSION['isadmin'] == 1){
				$sheetquery = "SELECT a.* FROM partab_master a INNER JOIN works b ON (a.globid = b.globid) WHERE a.is_confirmed = 'Y' AND b.work_status = 'DEU' ORDER BY a.tr_id ASC";
			 }else{
				$sheetquery = "SELECT a.* FROM partab_master a INNER JOIN works b ON (a.globid = b.globid) WHERE a.is_confirmed = 'Y' AND b.work_status = 'DEU' AND a.created_by = '".$_SESSION['sid']."' ORDER BY tr_id ASC";
			 }
		 }
		 $sheetsqlquery = mysqli_query($dbConn,$sheetquery);
		 if ($sheetsqlquery == true )
		 {
			 while($row = mysqli_fetch_array($sheetsqlquery))
			 {
				 if($MastId == $row['mastid']){
					 $sel = "selected";
				 }else{
					 $sel = "";
				 }
				 $sheet .=  '<option value="'. $row['mastid'].'" '.$sel.'>'.$row['work_name'].'</option>';
			 }            
		 }
		 return $sheet;            
	 }
	
}
$objBind = new BindList();
?>