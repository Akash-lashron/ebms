<?php
ob_start();
require_once 'library/config.php';
//require_once 'ExcelReader/excel_reader2.php';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
class BindList
{
	public function BindWorkOrderNoListAccounts($workordernolistvalue)
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
	public function BindFinancialYear($FYear)
	{
		$StartYear = 2013;
		$CurrYear = date('Y');
		$NextYear = date('Y', strtotime('+1 year'));

		for($i=$NextYear; $i>$StartYear; $i--){
			$FinYear1 = $i;
			$FinYear2 = $i-1;
			$FinYear = $FinYear2."-".$FinYear1;
			if($FYear == $FinYear){
				$sel = "selected";
			}else{
				$sel = "";
			}
			$FinYearsheet .=  '<option value="'. $FinYear.'" '.$sel.'>'.$FinYear.'</option>';
		}
		return $FinYearsheet;        
	}
	public function BindDiscipline()
	{
		global $dbConn;
		$SelectDiscQuery = "SELECT * FROM discipline";
		$Discsqlquery = mysqli_query($dbConn, $SelectDiscQuery);
		if ($Discsqlquery == true )
		{
			while($row = mysqli_fetch_array($Discsqlquery))
			{
				$Disc_Id 	= $row['disciplineid'];
				$Disc_Name 	= $row['discipline_name'];
				//if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1))
				//{
					if ($workordernolistvalue == $row['disciplineid'])
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
		$sheetquery = "SELECT * FROM sheet WHERE active=1 ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
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
					$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$row['short_name'].'</option>';
				//}
			}            
		}
		return $sheet;            
	}
	public function BindLiveWorks($WorkId)
	{
		global $dbConn;
		$sheetquery = "SELECT * FROM works WHERE work_status != 'C' ORDER BY work_name ASC";
		$sheetsqlquery = mysqli_query($dbConn, $sheetquery);
		if ($sheetsqlquery == true ){
			while($row = mysqli_fetch_array($sheetsqlquery)){
				if($WorkId == $row['globid']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$sheet .=  '<option value="'. $row['globid'].'"'.$sel.'>'.$row['ccno'].' - '.$row['work_name'].'</option>';
			}            
		}
		return $sheet;            
	}
	public function BindShortCode($ScodeArr,$year)
	{   
		global $dbConn;
		$ExplodeScodeArr = explode(",",$ScodeArr);
		if($ExplodeScodeArr != null){ 
			$mbookquery =  "SELECT * FROM shortcode_master WHERE fin_year ='" . $year . "' AND active = 1 ORDER BY shortcode_id ASC";
			$mbooksqlquery = mysqli_query($dbConn,$mbookquery);
			if ($mbooksqlquery == true ) {
				while($row = mysqli_fetch_array($mbooksqlquery)) {
					if(in_array($row['shortcode_id'], $ExplodeScodeArr)){
						$sel = "selected";
					}else{
						$sel = "";
					}
					$mbook .=  '<option value="'.$row['shortcode_id'].'"'.$sel.'>'.$row['shortcode'].' - '.$row['rec_code'].'</option>';     
				}            
			}
		}
		return $mbook;     
	}
	public function BindShortCodeForMop($RecCode)
	{   
		global $dbConn;
		$CurrYear = date('Y');
		$NextYear = date('Y', strtotime('+1 year'));
		$PrevYear = date('Y', strtotime('-1 year'));
		$CurrMonth = date('n');
		if($CurrMonth > 3){
			$FinYear = $CurrYear."-".$NextYear;
		}else{
			$FinYear = $PrevYear."-".$CurrYear;
		}
		$SelectSCodeQuery 	= "SELECT * FROM shortcode_master WHERE fin_year ='$FinYear' AND active = 1 ORDER BY shortcode_desc ASC";
		$SelectSCodeSql 	= mysqli_query($dbConn,$SelectSCodeQuery);
		if($SelectSCodeSql == true ){
			while($row = mysqli_fetch_array($SelectSCodeSql)){
				if($row['rec_code'] == $RecCode){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$SCodes .=  '<option value="'.$row['rec_code'].'" data-id="'.$row['shortcode_id'].'" '.$sel.'>'.$row['shortcode'].'</option>';     
			}            
		}
		return $SCodes;     
	}
	
	public function BindHoaWithSCode($HoaId)
	{   
		global $dbConn;
		$CurrYear = date('Y');
		$NextYear = date('Y', strtotime('+1 year'));
		$PrevYear = date('Y', strtotime('-1 year'));
		$CurrMonth = date('n');
		if($CurrMonth > 3){
			$FinYear = $CurrYear."-".$NextYear;
		}else{
			$FinYear = $PrevYear."-".$CurrYear;
		}
		$SelectSCodeQuery 	= "SELECT a.*, b.* FROM hoa_master a INNER JOIN shortcode_master b ON (a.shortcode_id = b.shortcode_id) WHERE a.fin_year ='$FinYear' AND a.active = 1 ORDER BY b.shortcode_desc ASC";
		$SelectSCodeSql 	= mysqli_query($dbConn,$SelectSCodeQuery);
		if($SelectSCodeSql == true ){
			while($row = mysqli_fetch_array($SelectSCodeSql)){
				if($row['hoamast_id'] == $HoaId){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$SCodes .=  '<option value="'.$row['hoamast_id'].'" data-scodeid="'.$row['shortcode_id'].'" data-hoa="'.$row['new_hoa_no'].'" '.$sel.'>'.$row['shortcode'].'</option>';     
			}            
		}
		return $SCodes;     
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
		$UnitsQuery = "SELECT * FROM sheet WHERE active = 1 and work_name != '' ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
		$UnitsSql 	= mysqli_query($dbConn,$UnitsQuery);
		if($UnitsSql == true ){
			while($UnitsList = mysqli_fetch_array($UnitsSql)){
				if($SheetId == $UnitsList['sheet_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$Units .=  '<option value="'. $UnitsList['sheet_id'].'"'.$sel.'>'.$UnitsList['computer_code_no'].' - '.$UnitsList['short_name'].'</option>'; 
			}            
		}
		return $Units; 
	} 
	public function BindHOA($hoaid)
    {   
        //echo $hoaid;exit;
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
    public function BindAllStaff($Staffid)
    {
        global $dbConn; $Staffs = "";
        $DeptsQuery = "SELECT * FROM staff WHERE active = 1 AND sectionid = 1 AND staffid != 0 ORDER BY staffname ASC";
        $DeptsSql   = mysqli_query($dbConn,$DeptsQuery);
        if($DeptsSql == true ){
            while($StaffList = mysqli_fetch_array($DeptsSql)){
                if($Staffid == $StaffList['staffid']){
                    $sel = "selected";
                }else{
                    $sel = "";
                }
                $Staffs .=  '<option value="'.$StaffList['staffid'].'"'.$sel.'>'.$StaffList['staffname'].'</option>'; 
            }            
        }
        return $Staffs; 
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
	
	public function DisplayPageDetails($currentmbookname1,$currentmbookname,$sheetid,$generatetype,$rbn,$staffid)
	{
		global $dbConn;
		$select_mbook_page_sql 		= 	"select MAX(mbpage) from mbookgenerate_staff WHERE staffid = '$staffid' AND sheetid = '$sheetid' AND mbno = '$currentmbookname' AND rbn = '$rbn'";
		$select_mbook_page_query	=	mysqli_query($dbConn,$select_mbook_page_sql);
		$ResList5 					=   mysqli_fetch_object($select_mbook_page_query);
		$mbookpageno_temp 			= 	$ResList5->mbpage;
		//$mbookpageno_temp			=	@mysql_result($select_mbook_page_query,'mbpage');
		
		if($mbookpageno_temp != "")
		{
			$mbpage		=	$mbookpageno_temp;
		}
		else
		{
			$select_mbook_page_sql_1		=	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$currentmbookname'";
			$select_mbook_page_query_1		=	mysqli_query($dbConn,$select_mbook_page_sql_1);
			$ResList5 						=   mysqli_fetch_object($select_mbook_page_query_1);
			$mbpage 						= 	$ResList5->mbpage;
			//$mbpage							=	@mysql_result($select_mbook_page_query_1,'mbpage');
		}
		return $mbpage;
	}
	public function BindCCNoListAccounts($workordernolistvalue){
   		global $dbConn;
		$sheetquery = "SELECT sheet_id, short_name, assigned_staff, computer_code_no FROM sheet WHERE active != 'x' AND under_civil_sheetid = 0 AND work_name != '' ORDER BY CAST(computer_code_no AS UNSIGNED) ASC";
		$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
		if ($sheetsqlquery == true ){
			while($row = mysqli_fetch_array($sheetsqlquery)){
				if ($workordernolistvalue == $row['sheet_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$row['short_name'].'</option>';
			}            
		}
		return $sheet;            
	} 
	public function BindCCNoMopStmt($workordernolistvalue){
   		global $dbConn;
		$sheetquery = "SELECT a.sheet_id, a.short_name, a.assigned_staff, a.computer_code_no, b.sheetid FROM sheet a 
					   INNER JOIN bill_register b ON (b.sheetid = a.sheet_id)
					   WHERE a.active = '1' AND a.under_civil_sheetid = 0 AND a.work_name != '' 
					   AND b.acc_status = 'P' 
					   ORDER BY CAST(a.computer_code_no AS UNSIGNED) ASC";
		$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
		if ($sheetsqlquery == true ){
			while($row = mysqli_fetch_array($sheetsqlquery)){
				if ($workordernolistvalue == $row['sheet_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$row['short_name'].'</option>';
			}            
		}
		return $sheet;            
	} 
	public function BindCstTrNo($TrId,$Page)
	{
		global $dbConn;
		if($Page == "TOACC"){
			$sheetquery = "SELECT * FROM tender_register WHERE status IS NULL AND cst_acc_status IS NULL ORDER BY tr_id ASC";
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
		}else if($Page == "TOUSER"){
			$sheetquery = "SELECT * FROM tender_register WHERE status = 'ACC' NULL AND cst_acc_status IS NULL ORDER BY tr_id ASC";
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
		}else if($Page == "NEGOENT"){
			$TrArr = array();
			//select distinct contid from bidder_bid_master where tr_id = '$MastId' and is_negotiate IS NULL
			$sheetquery = "SELECT tr_id FROM bidder_bid_master WHERE is_negotiate IS NULL ORDER BY tr_id ASC";
			$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
			if ($sheetsqlquery == true )
			{
				while($row = mysqli_fetch_array($sheetsqlquery))
				{
					$TrId1 = $row['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			//$ImplodeTrArr = implode(',',$TrArr);

			$sheetquery1 = "SELECT * FROM tender_register WHERE nego_status IS NULL AND ncst_acc_status IS NULL OR tr_id IN(".implode(',',$TrArr).") ORDER BY tr_id ASC";
			$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
			if ($sheetsqlquery1 == true )
			{
				while($row1 = mysqli_fetch_array($sheetsqlquery1))
				{
					if($TrId == $row1['tr_id']){
						$sel = "selected";
					}else{
						$sel = "";
					}
					$sheet .=  '<option value="'. $row1['tr_id'].'" '.$sel.'>'.$row1['tr_no'].'</option>';
				}
			}
		}else if($Page == "NEGOGEN"){
			$TrArr = array();
			//select distinct contid from bidder_bid_master where tr_id = '$MastId' and is_negotiate IS NULL
			$sheetquery = "SELECT tr_id FROM bidder_bid_master WHERE is_negotiate = 'Y' ORDER BY tr_id ASC";
			$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
			if ($sheetsqlquery == true )
			{
				while($row = mysqli_fetch_array($sheetsqlquery))
				{
					$TrId1 = $row['tr_id'];
					array_push($TrArr,$TrId1);
				}
			}
			//$ImplodeTrArr = implode(',',$TrArr);

			$sheetquery1 = "SELECT * FROM tender_register WHERE nego_status IS NULL AND ncst_acc_status IS NULL OR tr_id IN(".implode(',',$TrArr).") ORDER BY tr_id ASC";
			$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
			if ($sheetsqlquery1 == true )
			{
				while($row1 = mysqli_fetch_array($sheetsqlquery1))
				{
					if($TrId == $row1['tr_id']){
						$sel = "selected";
					}else{
						$sel = "";
					}
					$sheet .=  '<option value="'. $row1['tr_id'].'" '.$sel.'>'.$row1['tr_no'].'</option>';
				}
			}
		}else if($Page == "CCNOGEN"){
			//$WhereClauseSt = "WHERE is_negotiate = 'Y' AND nego_status = 'ACC' NULL AND ncst_acc_status IS NULL";
			//$sheetquery = "SELECT * FROM tender_register " . $WhereClauseSt . " ORDER BY tr_id ASC";
			$sheetquery = "SELECT * FROM tender_register WHERE nego_status = 'ACC' OR status  = 'ACC' ORDER BY tr_id ASC";
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
	public function BindPGTrNo($TrId)
	{
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
		if($ImplodeTrArr != null){ 
			$sheetquery = "SELECT * FROM tender_register WHERE loi_issue = 'Y' AND  globid NOT IN (".$ImplodeTrArr.") ORDER BY tr_id ASC";
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
			$sheetquery = "SELECT * FROM tender_register WHERE  loi_issue = 'Y' AND  globid NOT IN (".$ImplodeTrArr.") ORDER BY tr_id ASC";
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
			$sheetquery = "SELECT sheet_id,short_name,computer_code_no FROM sheet WHERE active = 1 AND  globid NOT IN (".$ImplodeTrArr.") ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
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
			$sheetquery = "SELECT tr_id,tr_no FROM tender_register WHERE loi_issue = 'Y' AND globid NOT IN (".$ImplodeTrArr.") ORDER BY tr_id ASC";
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
	public function BindYear()
	{
		$StartYear = 2013;
		$CurrYear = date('Y');
		//$NextYear = date('Y', strtotime('+1 year'));
		for($i=$StartYear; $i<=$CurrYear; $i++){
			/* $FinYear1 = $i;
			$FinYear2 = $i-1;
			$FinYear = $FinYear2."-".$FinYear1; */
			$FinYear = $i;
			$FinYearsheet .=  '<option value="'. $FinYear.'">'.$FinYear.'</option>';
		}
		return $FinYearsheet;        
	}
	public function BindAllCCno($SheetId)
	{
		global $dbConn;
		$CcNoArr1 = array();
		$SelectCcnoQuery1 = "SELECT DISTINCT computer_code_no FROM sheet WHERE active = 1 ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
		$SelectCcnoSql1   = mysqli_query($dbConn,$SelectCcnoQuery1);
		if($SelectCcnoSql1 == true ){
			while($CCNOList1 = mysqli_fetch_object($SelectCcnoSql1)){
				array_push($CcNoArr1,$CCNOList1->computer_code_no);
			}            
		}
		$CcNoArr2 = array();
		$SelectCcnoQuery2 = "SELECT DISTINCT ccno FROM sheet WHERE active = 1 ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) ASC";
		$SelectCcnoSql2   = mysqli_query($dbConn,$SelectCcnoQuery2);
		if($SelectCcnoSql2 == true ){
			while($CCNOList2 = mysqli_fetch_object($SelectCcnoSql2)){
				array_push($CcNoArr2,$CCNOList2->ccno);
			}            
		}
		$CcNoArr3 = array_merge($CcNoArr1,$CcNoArr2);
		$CcNoArr  = array_unique($CcNoArr3);
		$CCNos = '';
		if(count($CcNoArr)>0){
			foreach($CcNoArr as $CcNoKey => $CcNoValue){
				$CCNos .=  '<option value="'.$CcNoValue.'">';
			}
		}
		
		return $CCNos; 
	} 
	public function BindDisciplineSecAdv($Sellistvalue)
	{
		global $dbConn;
		$SelectDiscQuery = "SELECT * FROM discipline";
		$Discsqlquery = mysqli_query($dbConn, $SelectDiscQuery);
		if ($Discsqlquery == true )
		{
			while($row = mysqli_fetch_array($Discsqlquery))
			{
				$Disc_Id 	= $row['disciplineid'];
				$Disc_Name 	= $row['discipline_name'];
				//if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1))
				//{
				if($Sellistvalue != ""){
					$sel = "disabled";
				}
				
				if ($Sellistvalue == $row['disciplineid']) {
					$sel = "selected";
				}
					
				$DiscData .=  '<option value="'. $row['disciplineid'].'"'.$sel.'>'.$row['discipline_name'].'</option>';
				//}
			}            
		}
		return $DiscData;            
	}
	public function BindAllMiscellItems($MisItemId,$Module)
	{
		global $dbConn;
		$SelectQuery = "SELECT * FROM miscell_items WHERE active = 1 and misc_module = '$Module' ORDER BY mis_item_desc ASC";
		$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
		if($SelectSql == true ){
			while($List = mysqli_fetch_array($SelectSql)){
				if($MisItemId == $List['mis_item_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$Result .=  '<option data-moptype="'. $List['mop_type'].'" data-misc_module="'. $List['misc_module'].'" value="'. $List['mis_item_id'].'"'.$sel.'>'.$List['mis_item_desc'].'</option>'; 
			}            
		}
		return $Result; 
	} 
		
}
$objBind = new BindList();

?>