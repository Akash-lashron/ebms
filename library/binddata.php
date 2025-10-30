<?php
ob_start();
require_once 'library/config.php';
//require_once 'ExcelReader/excel_reader2.php';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
class BindList
{
		public function BindWorkOrderNoForReset($workordernolistvalue)
       	{
			if($workordernolistvalue != '' && $workordernolistvalue != NULL){
				$sheetquery = "SELECT * FROM sheet WHERE sheet_id = '$workordernolistvalue' ORDER BY sheet_id ASC";
			}else{
				$sheetquery = "SELECT * FROM sheet WHERE active=1 ORDER BY sheet_id ASC";
				$sheet .= '<option value="">---------------------- Select ----------------------</option>';
			}
           	$sheetsqlquery = mysql_query($sheetquery);
			$sheet = '';
           	if($sheetsqlquery == true){
				while($row = mysql_fetch_array($sheetsqlquery)){
					$sheetid = $row['sheet_id'];
					if($workordernolistvalue == $sheetid){
						$sel = "selected";
					}else{
						$sel = "";
					}
					if($row['short_name'] != ''){
						$WorkDesc = $row['short_name'];
					}else{
						$WorkDesc = $row['work_name'];
					}
					$sheet .= '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkDesc.'</option>';
					/*
					$select_abstbook = "select distinct * from abstractbook where sheetid = '$sheetid' and rab_status='P'";
					$select_abstbook_sql = mysql_query($select_abstbook);
					if($select_abstbook_sql == true){
						$count2 = mysql_num_rows($select_abstbook_sql);
						if($count2 > 0){
							$AbstList = mysql_fetch_object($select_abstbook_sql);
							$reset = 0;   $count2 = 0;   $count3 = 0;
							$AbstRbn = $AbstList->rbn;
							if($_SESSION['isadmin'] == 1) {
								if($workordernolistvalue == $row['sheet_id']){
									$sel = "selected";
								}else{
									$sel = "";
								}
								if($row['short_name'] != ''){
									$WorkDesc = $row['short_name'];
								}else{
									$WorkDesc = $row['work_name'];
								}
								$sheet .= '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkDesc.'</option>';
							}else{
								$select_send_acc_query = "select distinct * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$AbstRbn'";
								$select_send_acc_sql = mysql_query($select_send_acc_query);
								if($select_send_acc_sql == true && mysql_num_rows($select_send_acc_sql)>0){
									$SacList = mysql_fetch_object($select_send_acc_sql);
									$count3 = mysql_num_rows($select_send_acc_sql);
								}
								if($count3 >0){
									$reset = 2;		// FOR ADMIN TO DELETE AFTER RETURNED FROM ACCOUNTS
								}
								if($reset != 2) {
									$assigned_staff = $row['assigned_staff'];
									$AssignStaff = explode(",",$assigned_staff);
									if(in_array($_SESSION['sid'],$AssignStaff)){
										if($workordernolistvalue == $row['sheet_id']){
											$sel = "selected";
										}else{
											$sel = "";
										}
										if($row['short_name'] != ''){
											$WorkDesc = $row['short_name'];
										}else{
											$WorkDesc = $row['work_name'];
										}
										$sheet .= '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkDesc.'</option>';
									}
								}
							}
						}
					}
					*/
				}
           	}
            return $sheet;
        }
       public function BindWorkOrderNoListAccounts($workordernolistvalue)
       {
           	$sheetquery = "SELECT * FROM sheet WHERE active = 1 ORDER BY sheet_id ASC";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysql_fetch_array($sheetsqlquery))
				{
					//$assigned_staff = $row['assigned_staff'];
					//$AssignStaff = explode(",",$assigned_staff);
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
						if($row['short_name'] != ''){
							$WorkDesc = $row['short_name'];
						}else{
							$WorkDesc = $row['work_name'];
						}
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkDesc.'</option>';
					//}
				}            
           	}
            return $sheet;            
        } 
		 public function Bindactivity($act)
       {
           	$sheetquery = "SELECT * FROM schedule_act ";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysql_fetch_array($sheetsqlquery))
				{
						/*if ($act == $row['sl_no'])
						{
							$sel = "selected";
						}
						else
						{
							$sel = "";
						}*/
						$sel = "";
						$sheet .=  '<option value="'. $row['sl_no'].'"'.$sel.' style="color:black;">'.$row['activity'].' ('. $row['sl_no'].')</option>';
					
				}            
           	}
            return $sheet;            
        } 
		 public function Bind_major_items($act)
       {
           	$sheetquery = "SELECT * FROM major_misc_items  ORDER BY id ASC";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysql_fetch_array($sheetsqlquery))
				{
					/*	if ($act == $row['id'])
						{
							$sel = "selected";
						}
						else
						{
							$sel = "";
						}*/
						$sel = "";
						$id=$row['id'];
				$str=substr($id,0,2);
				if($str=="ME"){ $styl="style='color:black;'"; }
				if($str=="MI"){ $styl="style='color:green;'"; }
				if($str=="MW"){ $styl="style='color:brown;'"; }
						$sheet .=  '<option value="'. $id.'"'.$sel.' '.$styl.'>'.$row['items'].' ('. $id.')</option>';
					
				}            
           	}
            return $sheet;            
        }
		public function BindDiscipline($act)
       {
           	$sheetquery = "SELECT disciplineid,discipline_name FROM discipline WHERE active = 1";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysql_fetch_array($sheetsqlquery))
				{
					/*	if ($act == $row['id'])
						{
							$sel = "selected";
						}
						else
						{
							$sel = "";
						}*/
						$sel = "";
						$sheet .=   '<option value="'. $row['disciplineid'].'"'.$sel.' style="color:black;">'.$row['discipline_name'].'</option>';
					
				}            
           	}
            return $sheet;            
        }
		public function Bindsection($act)
       {
           	$sheetquery = "SELECT distinct sub_sec_id,sub_sec_name FROM sub_section ";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysql_fetch_array($sheetsqlquery))
				{
					/*	if ($act == $row['id'])
						{
							$sel = "selected";
						}
						else
						{
							$sel = "";
						}*/
						$sel = "";
						$sheet .=   '<option value="'. $row['sub_sec_id'].'"'.$sel.'>'.$row['sub_sec_name'].'</option>';
					
				}            
           	}
            return $sheet;            
        }
		public function Bindplant($act)
       {
           	$sheetquery = "SELECT DISTINCT plant FROM plant_service WHERE plant != ''";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysql_fetch_array($sheetsqlquery))
				{
					/*	if ($act == $row['id'])
						{
							$sel = "selected";
						}
						else
						{
							$sel = "";
						}*/
						$sel = "";
						$sheet .=   '<option value="'. $row['plant'].'"'.$sel.' style="color:black;">'.$row['plant'].'</option>';
					
				}            
           	}
            return $sheet;            
        }
		
       public function BindWorkOrderNoListStaff($workordernolistvalue)
       {
           	$sheetquery = "SELECT * FROM sheet WHERE (active = 'x' OR active = 1) ORDER BY sheet_id ASC";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysql_fetch_array($sheetsqlquery))
				{
					//$assigned_staff = $row['assigned_staff'];
					//$AssignStaff = explode(",",$assigned_staff);
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
						if($row['short_name'] != ''){
							$WorkDesc = $row['short_name'];
						}else{
							$WorkDesc = $row['work_name'];
						}
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.' style="color:black;">'.$row['computer_code_no'].' - '.$WorkDesc.'</option>';
					//}
				}            
           	}
            return $sheet;            
        } 
		public function BindWorkOrderNoListUpload($workordernolistvalue)
       	{
           	$sheetquery = "SELECT * FROM sheet WHERE active='x' ORDER BY sheet_id ASC";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysql_fetch_array($sheetsqlquery))
				{
					//$assigned_staff = $row['assigned_staff'];
					//$AssignStaff = explode(",",$assigned_staff);
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
						if($row['short_name'] != ''){
							$WorkDesc = $row['short_name'];
						}else{
							$WorkDesc = $row['work_name'];
						}
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkDesc.'</option>';
					//}
				}            
           	}
            return $sheet;            
        } 
		public function BindWorkOrderNo($workordernolistvalue)
       	{
           	$sheetquery = "SELECT * FROM sheet WHERE active=1 ORDER BY sheet_id ASC";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysql_fetch_array($sheetsqlquery))
				{
					$assigned_staff = $row['assigned_staff'];
					$AssignStaff = explode(",",$assigned_staff);
					if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1))
					{
						if ($workordernolistvalue == $row['sheet_id'])
						{
							$sel = "selected";
						}
						else
						{
							$sel = "";
						}
						if($row['short_name'] != ''){
							$WorkDesc = $row['short_name'];
						}else{
							$WorkDesc = $row['work_name'];
						}
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkDesc.'</option>';
					}
				}            
           	}
            return $sheet;            
        }
        public function BindItemNo($workordernolistvalue)
        {
              $ItemNoList = '<option value=""> -- Select Item No -- </option>';
            if($workordernolistvalue == 0)
            {
                $workorderno=$_POST[workorderno];
               
            }
            else {      
                $workorderno=$workordernolistvalue;
                $itemno =$itemnolistvalue;
            }
            $ItemNoFetchQuery =" SELECT   division.div_id  , division.div_name   , division.active
                                FROM    sheet    INNER JOIN division         ON (sheet.sheet_id = division.sheet_id)
                                WHERE division.active =1 AND division.sheet_id = '$workorderno'";                   
            $ItemNoFetchResult = mysql_query($ItemNoFetchQuery);
            
            if ($ItemNoFetchResult == true && mysql_num_rows($ItemNoFetchResult) >= 1)
           	{
				while($row = mysql_fetch_array($ItemNoFetchResult))
				{
					if ($itemno == $row['div_id'])
					{
						$sel = "selected";
					}
					else
					{
						$sel = "";
					}
					$ItemNoList .= '<option value="' . $row['div_id'].'"'.$sel.'>' . $row['div_name'] . '</option>';
				}
           	}
            echo $ItemNoList;
        }
        public function BindSubItemNo($itemnolistvalue,$subitemnolistvalue)
        {
//               $SubItemNoList = '<option value=""> -- Select SubItem No -- </option>';
            if($itemnolistvalue == 0 && $subitemnolistvalue == 0)
            {
                $workorderno=$_POST[workorderno];
                $itemno=$_POST[itemno];
             
            }
            else {
                $subitemno=$subitemnolistvalue;
                $itemno =$itemnolistvalue;
            }            
            $SubItemNoFetchQuery =" SELECT     subdivision.subdiv_id   , subdivision.subdiv_name    , subdivision.active
                                    FROM     subdivision    INNER JOIN division         ON (subdivision.div_id = division.div_id)
                                    WHERE subdivision.active =1 AND subdivision.div_id = '$itemno'";                                      
            $SubItemNoFetchResult = mysql_query($SubItemNoFetchQuery);
            
            if ($SubItemNoFetchResult == true && mysql_num_rows($SubItemNoFetchResult) >= 1)
           {
            while($row = mysql_fetch_array($SubItemNoFetchResult))
            {
                if ($subitemno == $row['subdiv_id'])
                {
                    $sel = "selected";
                }
                else{
                    $sel = "";
                }
                $SubItemNoList .= '<option value="' . $row['subdiv_id'] .'"'.$sel.'>' . $row['subdiv_name'] . '</option>';
            }
           }
            return $SubItemNoList;
        }
        public function BindStaff($stafflistvalue,$sectionid)
        {
            $staffquery = "SELECT staffid,staffcode,staffname FROM staff WHERE active=1 and sectionid = '$sectionid' ORDER BY staffid ASC";
            $staffsqlquery = mysql_query($staffquery);
           if ($staffsqlquery == true )
           {
            while($row = mysql_fetch_array($staffsqlquery))
            {
                if ($stafflistvalue == $row['staffid'])
                {
                    $sel = "selected";
                }
                else{
                    $sel = "";
                }
                $staff .=  '<option value="'. $row['staffid'].'"'.$sel.'>'.$row['staffname'].'</option>'; 
            }            
           }
            return $staff;            
        } 
        public function BindMBook($mbooklistvalue,$staffid)
        {
            
            if($staffid ==0) {  $WhereClause = "";} else  {  $WhereClause = "  AND staffid ='$staffid'  ";}
            $mbookquery = "SELECT    agreementmbookallotment.allotmentid,    agreementmbookallotment.mbno
                          FROM   mbookallotment
                            INNER JOIN agreementmbookallotment      ON (mbookallotment.sheetid = agreementmbookallotment.sheetid) AND (mbookallotment.allotmentid = agreementmbookallotment.allotmentid)
                            WHERE mbookallotment.active=1 AND agreementmbookallotment.active=1 AND mbookallotment.flag = 1  ".$WhereClause."  ORDER BY mbookallotment.mballotmentid ASC";
            $mbooksqlquery = mysql_query($mbookquery);
           // echo $mbooksqlquery;
           if ($mbooksqlquery == true )
           {
                    
            while($row = mysql_fetch_array($mbooksqlquery))
            {
                if ($mbooklistvalue == $row['mballotmentid'])
                {
                    $sel = "selected";
                }
                else{
                    $sel = "";
                }
                $mbook .=  '<option value="'. $row['mballotmentid'].'"'.$sel.'>'.$row['mbno'].'</option>'; 
            }            
           }
            return $mbook;            
        } 
        public function DisplayPer($subitemno)
        {
            $PerQuery ="SELECT per FROM schdule WHERE active=1 AND subdiv_id ='$subitemno'";
            $PerSQL = mysql_query($PerQuery);          $per='';
            if ($PerSQL == true )        {    $row = mysql_fetch_array($PerSQL);    $per = $row['per'];   }
            return $per;  
        }
        public function DisplayPageDetails($currentmbookname,$currentmbookname,$sheetid,$generatetype,$rbn,$staffid)
        {
			$select_mbook_page_sql 		= 	"select MAX(mbpage) from mbookgenerate_staff WHERE staffid = '$staffid' AND sheetid = '$sheetid' AND mbno = '$currentmbookname' AND rbn = '$rbn'";
			$select_mbook_page_query	=	mysql_query($select_mbook_page_sql);
			$mbookpageno_temp			=	@mysql_result($select_mbook_page_query,'mbpage');
			
			if($mbookpageno_temp != "")
			{
				$mbpage		=	$mbookpageno_temp;
			}
			else
			{
				$select_mbook_page_sql_1		=	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$currentmbookname'";
				$select_mbook_page_query_1		=	mysql_query($select_mbook_page_sql_1);
				$mbpage							=	@mysql_result($select_mbook_page_query_1,'mbpage');
			}
			/*$current_rbn_sql = "select max(mbpage) as mbpage, max(rbn) as rbn from mbookgenerate_staff a WHERE NOT EXISTS (select rbn from measurementbook b WHERE a.rbn = b.rbn AND b.sheetid = '$sheetid') AND a.sheetid = '$sheetid' AND a.mbno = '$currentmbookname'";
			//echo $current_rbn_sql
			$current_rbn_query = mysql_query($current_rbn_sql);
			if($current_rbn_query == true)
			{
				$res = mysql_fetch_array($current_rbn_query); 
				$mbpage = $res['mbpage']; 
				$currentrbn = $res['rbn'];
				if($mbpage === NULL)
				{
				
			//}
			//else
			//{
			
					$MBookQuery ="SELECT mbpage  FROM mbookallotment WHERE active=1 AND  allotmentid  ='$currentmbook'";
					$MBookSQL = mysql_query($MBookQuery);          $mbook='';
					if ($MBookSQL == true )
					{    
						$row = mysql_fetch_array($MBookSQL);    
						$mbookpage = $row['mbpage']; 
					}
					return  $mbookpage;
				}
				else
				{
					return $mbpage;
				}
			}*/
			return $mbpage;
        }
		 /*public function DisplayPageDetails($currentmbook,$currentmbookname,$sheetid,$generatetype)
        {
		
			$current_rbn_sql = "select max(mbpage) as mbpage, max(rbn) as rbn from mbookgenerate_staff a WHERE NOT EXISTS (select rbn from measurementbook b WHERE a.rbn = b.rbn AND b.sheetid = '$sheetid') AND a.sheetid = '$sheetid' AND a.mbno = '$currentmbookname'";
			//echo $current_rbn_sql
			$current_rbn_query = mysql_query($current_rbn_sql);
			if($current_rbn_query == true)
			{
				$res = mysql_fetch_array($current_rbn_query); 
				$mbpage = $res['mbpage']; 
				$currentrbn = $res['rbn'];
				if($mbpage === NULL)
				{
				
			//}
			//else
			//{
			
					$MBookQuery ="SELECT mbpage  FROM mbookallotment WHERE active=1 AND  allotmentid  ='$currentmbook'";
					$MBookSQL = mysql_query($MBookQuery);          $mbook='';
					if ($MBookSQL == true )
					{    
						$row = mysql_fetch_array($MBookSQL);    
						$mbookpage = $row['mbpage']; 
					}
					return  $mbookpage;
				}
				else
				{
					return $mbpage;
				}
			}
        }*/
        public function DisplayRBNDetails($wordorderno)
        {
            $RBNQuery ="SELECT work_name, rbn, work_order_no FROM sheet  WHERE active=1 AND sheet_id  ='$wordorderno'";
            $RBNSQL = mysql_query($RBNQuery);          $rbn='';
            if ($RBNSQL == true )        
			{    
				$row = mysql_fetch_array($RBNSQL);     
				$rbn = $row['work_name'];   
				$rbn = $rbn."*".$row['rbn'];  
				$rbn = $rbn."*".$row['work_order_no'];
            }
            return $rbn;  
        }
        public function BindDesignation($designationlistvalue,$sectionid)
        {
            $designationquery = "SELECT designationid, designationname FROM `designation` WHERE active=1 AND sectionid = '$sectionid' ORDER BY designationid ASC";
            $designationsqlquery = mysql_query($designationquery);
           if ($designationsqlquery == true )
           {
            while($row = mysql_fetch_array($designationsqlquery))
            {
                if ($designationlistvalue == $row['designationid'])
                {
                    $sel = "selected";
                }
                else{
                    $sel = "";
                }
                $designation .=  '<option value="'. $row['designationid'].'"'.$sel.'>'.$row['designationname'].'</option>'; 
            }            
           }
            return $designation;            
        } 
        public function BindMBookList($mbookno,$sheetid,$staffid,$mbooktype)
        { //echo "hello";
           // $mbookquery = "SELECT mballotmentid,mbno FROM  mbookallotment WHERE mbno != '$mbookno' ORDER BY mballotmentid ASC";
			$mbookquery = "SELECT agreementmbookallotment.mbooktype, mbookallotment.mballotmentid, agreementmbookallotment.mbno FROM mbookallotment INNER JOIN agreementmbookallotment ON (agreementmbookallotment.allotmentid = mbookallotment.allotmentid) WHERE mbookallotment.sheetid = '$sheetid' AND mbookallotment.staffid = '$staffid' AND agreementmbookallotment.mbooktype = '$mbooktype' AND agreementmbookallotment.mbno != '$mbookno' AND mbookallotment.active = 1 AND agreementmbookallotment.active = 1";
			//echo $mbookquery; 
            $mbooksqlquery = mysql_query($mbookquery);
                     
           if ($mbooksqlquery == true ) {
            while($row = mysql_fetch_array($mbooksqlquery))
            {
                $mbook .=  '<option value="'. $row['mballotmentid'].'"'.$sel.'>'.$row['mbno'].'</option>';     
            }            
           }
		   
           return $mbook;     
        }
		public function BindStaffSection($sectionvalue)
        {
            $sectionquery = "SELECT sectionid,section_name FROM staff_section WHERE active=1 ORDER BY sectionid ASC";
            $sectionsql = mysql_query($sectionquery);
           	if ($sectionsql == true )
           	{
            	while($row = mysql_fetch_array($sectionsql))
            	{
					if ($sectionvalue == $row['sectionid'])
					{
						$sel = "selected";
					}
					else
					{
						$sel = "";
					}
                	$section .=  '<option value="'. $row['sectionid'].'"'.$sel.'>'.$row['section_name'].'</option>'; 
            	}            
           	}
            return $section;            
        } 
		
        public function BindWorkOrderNo_CIVIL($workordernolistvalue){
			$userid = $_SESSION['sid'];
			if($_SESSION['isadmin'] == 1){
				$SelectSheetQuery = "select * from sheet where active=1 ORDER BY short_name ASC";
			}else{
				$SelectSheetQuery = "select * from sheet where active=1 and CONCAT(',' ,assigned_staff, ',') LIKE '%,$userid,%' ORDER BY short_name ASC";
			}
			$SelectSheetSql = mysql_query($SelectSheetQuery);
			if($SelectSheetSql == true){
				while($SheetList = mysql_fetch_object($SelectSheetSql)){
					//////// This is for MBook, SubAbstract and Abstract Generation Checking ==========> (A)
					$sheetid = $SheetList->sheet_id;
					$GenExist = 0; $SendAccExist = 0; $SendAccStatus = 0;
					$SelectRbnQuery = "select distinct rbn, staffid from measurementbook_temp where sheetid = '$sheetid'";
					$SelectRbnSql = mysql_query($SelectRbnQuery);
					if($SelectRbnSql == true){
						if(mysql_num_rows($SelectRbnSql)>0){
							$GenExist = 1;
							while($RbnList = mysql_fetch_object($SelectRbnSql)){
								
								///////// This is for Send to Accounts Checking ====================> (C)
								$SelectAccQuery = "select mb_ac, sa_ac, ab_ac from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$RbnList->rbn'";
								$SelectAccSql = mysql_query($SelectAccQuery);
								if($SelectAccSql == true){
									if(mysql_num_rows($SelectAccSql)>0){
										$SendAccExist = 1;
										while($AccList = mysql_fetch_object($SelectAccSql)){
											if($AccList->mb_ac == "SC" || $AccList->sa_ac == "SC" || $AccList->ab_ac == "SC"){
												$SendAccStatus = 1;
											}
										}
									}
								}
								///////// (C) Ends here
							}
						}
					}
					/////////(A) Ends Here
					if($GenExist == 0){
						$Allow = 1;
					}else if(($GenExist == 1)&&($SendAccExist == 0)){
						$Allow = 1;
					}else if(($SendAccExist == 1)&&($SendAccStatus == 1)){
						$Allow = 1;
					}else{
						$Allow = 0;
					}
					if($Allow == 1){
						if($workordernolistvalue == $SheetList->sheet_id){
							$sel = "selected";
						}else{
							$sel = "";
						}
						$sheet .=  '<option value="'. $SheetList->sheet_id.'"'.$sel.'>'.$SheetList->computer_code_no.' - '.$SheetList->short_name.'</option>';
					}
					//echo $SendAccExist."<br/>";
				}
			}
            return $sheet;            
        } 
		
		/*public function BindWorkOrderNo_CIVIL($workordernolistvalue)
        {
			
           	$sheetquery = "SELECT distinct a.sheetid, a.rbn, b.short_name, b.assigned_staff FROM measurementbook_temp a, sheet b where a.sheetid = b.sheet_id ORDER BY a.measurementbookdate ASC";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true ){
				while($SheetList = mysql_fetch_object($sheetsqlquery)){
					$SASCCnt = 1;
					
					$send_acc_query = "SELECT * FROM send_accounts_and_civil where sheetid = '$SheetList->sheetid' and rbn = '$SheetList->rbn'";
					$send_acc_sql 	= mysql_query($send_acc_query);
					if($send_acc_sql == true ){
						if(mysql_num_rows($send_acc_sql)>0){
							$SASCCnt = 0;
						}
						while($SList = mysql_fetch_object($send_acc_sql)){
							if(($SList->mb_ac == 'SC')||($SList->sa_ac == 'SC')||($SList->ab_ac == 'SC')){
								$SASCCnt = 1;
							}
						}
					}
					
					$assigned_staff = $SheetList->assigned_staff;
					$AssignStaff = explode(",",$assigned_staff);
					if($SASCCnt == 1){
						if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1)){
							if ($workordernolistvalue == $SheetList->sheetid){
								$sel = "selected";
							}else{
								$sel = "";
							}
							$sheet .=  '<option value="'. $SheetList->sheetid.'"'.$sel.'>'.$SheetList->short_name.'</option>';
						}
					}
				}            
           	}
            return $sheetquery;   
        
		} */
		
        public function BindWorkOrderNo_ACCOUNTS($workordernolistvalue)
        {
            //$sheetquery = "SELECT sheet_id,short_name FROM  sheet WHERE active=1 ORDER BY sheet_id ASC";
			$sheetquery = "SELECT * FROM sheet WHERE sheet_id NOT IN (SELECT sheetid FROM send_accounts_and_civil 
			where (mb_ac = 'SC' OR  sa_ac = 'SC' OR  ab_ac = 'SC') and active=1 ORDER BY sheet_id ASC)";
            $sheetsqlquery = mysql_query($sheetquery);
           if ($sheetsqlquery == true )
           {
            //$sheet = '<option value=""> -- Select Work Order No -- </option>';
            while($row = mysql_fetch_array($sheetsqlquery))
            {
                if ($workordernolistvalue == $row['sheet_id'])
                {
                    $sel = "selected";
                }
                else{
                    $sel = "";
                }
				if($row['short_name'] != ''){
					$WorkDesc = $row['short_name'];
				}else{
					$WorkDesc = $row['work_name'];
				}
                $sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['computer_code_no'].' - '.$WorkDesc.'</option>'; 
            }            
           }
            return $sheet;            
        } 
		public function BindStaffRole($rolevalue,$sectionid)
        {
            $levelquery = "SELECT sroleid, role_name FROM staffrole WHERE active=1 AND sectionid = '$sectionid' ORDER BY levelid desc";
            $levelsql = mysql_query($levelquery);
           	if ($levelsql == true )
           	{
            	while($row = mysql_fetch_array($levelsql))
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
		
		public function BindWorkOrderNoSendAcc($workordernolistvalue)
       	{
           	$sheetquery = "SELECT distinct a.sheetid, a.rbn, b.short_name, b.assigned_staff, b.computer_code_no FROM measurementbook_temp a, sheet b where a.sheetid = b.sheet_id ORDER BY a.measurementbookdate ASC";
           	$sheetsqlquery = mysql_query($sheetquery);
           	if ($sheetsqlquery == true ){
				while($SheetList = mysql_fetch_object($sheetsqlquery)){
					$SASCCnt = 1;
					
						$send_acc_query = "SELECT * FROM send_accounts_and_civil where sheetid = '$SheetList->sheetid' and rbn = '$SheetList->rbn'";
						$send_acc_sql 	= mysql_query($send_acc_query);
						if($send_acc_sql == true ){
							if(mysql_num_rows($send_acc_sql)>0){
								$SASCCnt = 0;
							}
							while($SList = mysql_fetch_object($send_acc_sql)){
								if(($SList->mb_ac == 'SC')||($SList->sa_ac == 'SC')||($SList->ab_ac == 'SC')){
									$SASCCnt = 1;
								}
							}
						}
					
						$assigned_staff = $SheetList->assigned_staff;
						$AssignStaff = explode(",",$assigned_staff);
						if($SASCCnt == 1){
							if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1)){
								if ($workordernolistvalue == $SheetList->sheetid){
									$sel = "selected";
								}else{
									$sel = "";
								}
								$sheet .=  '<option value="'. $SheetList->sheetid.'"'.$sel.'>'.$SheetList->computer_code_no.' - '.$SheetList->short_name.'</option>';
							}
						}
					
				}            
           	}
            return $sheet;   
        }
		public function BindWorkOrderNoPassOrder($workordernolistvalue)
       	{
			$SASCCnt = 0; $sheet = "";
           	$sheetquery 	= "SELECT distinct a.sheetid, a.rbn, b.short_name, b.assigned_staff, b.computer_code_no FROM measurementbook_temp a, sheet b where a.sheetid = b.sheet_id and b.under_civil_sheetid != 0 ORDER BY a.measurementbookdate ASC";
           	$sheetsqlquery 	= mysql_query($sheetquery);
           	if ($sheetsqlquery == true ){
				while($SheetList = mysql_fetch_object($sheetsqlquery)){
					$SASCCnt = 0;
					$send_acc_query = "SELECT * FROM send_accounts_and_civil where sheetid = '$SheetList->sheetid' and rbn = '$SheetList->rbn'";
           			$send_acc_sql 	= mysql_query($send_acc_query);
           			if($send_acc_sql == true ){
						if(mysql_num_rows($send_acc_sql)>0){
							$SASCCnt = 1;
						}
						while($SList = mysql_fetch_object($send_acc_sql)){
							if(($SList->mb_ac != 'AC')&&($SList->sa_ac == '')&&($SList->ab_ac == '')){
								$SASCCnt = 0;
							}
							if(($SList->mb_ac == '')&&($SList->sa_ac != 'AC')&&($SList->ab_ac == '')){
								$SASCCnt = 0;
							}
							if(($SList->mb_ac == '')&&($SList->sa_ac == '')&&($SList->ab_ac != 'AC')){
								$SASCCnt = 0;
							}
						}
					}
					
					$assigned_staff = $SheetList->assigned_staff;
					$AssignStaff = explode(",",$assigned_staff);
					if($SASCCnt == 1){
						if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1)){
							if ($workordernolistvalue == $SheetList->sheetid){
								$sel = "selected";
							}else{
								$sel = "";
							}
							$sheet .=  '<option value="'. $SheetList->sheetid.'"'.$sel.'>'.$SheetList->computer_code_no.' - '.$SheetList->short_name.'</option>';
						}
					}
				}            
           	}
            return $sheet;   
			//echo  $sheetquery;         
        }
		public function BindNextMBlist($sheetid,$MBType,$PrevMBNo)
        {
            $NextMBQuery = "SELECT a.allotmentid, a.mbno, a.mbpage FROM mbookallotment a inner join agreementmbookallotment b on (a.allotmentid = b.allotmentid) 
			WHERE a.active=1 AND a.sheetid = '$sheetid' and b.active=1 AND b.sheetid = '$sheetid' and (b.mbooktype = '$MBType' OR b.mbookmode = 'SINMB') and a.mbno != '$PrevMBNo' and a.staffid = '".$_SESSION['sid']."' ORDER BY a.mbno asc";
            $NextMBSql = mysql_query($NextMBQuery);
           	if($NextMBSql == true ){
            	while($List = mysql_fetch_object($NextMBSql)){
                	$MBList .=  '<option value="'.$List->allotmentid.'"'.$sel.'>'.$List->mbno.'</option>'; 
            	}            
           	}
            return $MBList;            
        } 
		
		
		public function BindSectionName($SectionType)
        {
           $sheetquery = "SELECT * FROM section_name WHERE active=1 ORDER BY secid ASC";
           $sheetsqlquery = mysql_query($sheetquery);
           if ($sheetsqlquery == true )
           {
            while($row = mysql_fetch_array($sheetsqlquery))
            {
                if ($SectionType == $row['section_type'])
                {
                    $sel = "selected";
                }
                else{
                    $sel = "";
                }
                $sheet .=  '<option value="'. $row['section_type'].'"'.$sel.'>'.$row['section_name'].'</option>'; 
            }            
           }
            return $sheet;            
        } 
		
		public function BindSectionCode($SectionCode)
        {
           $sheetquery = "SELECT * FROM  section_code WHERE active=1 ORDER BY section_code ASC";
           $sheetsqlquery = mysql_query($sheetquery);
           if ($sheetsqlquery == true )
           {
            while($row = mysql_fetch_array($sheetsqlquery))
            {
                if ($SectionCode == $row['section_code'])
                {
                    $sel = "selected";
                }
                else{
                    $sel = "";
                }
                $sheet .=  '<option value="'. $row['section_code'].'"'.$sel.'>'.$row['section_code'].'</option>'; 
            }            
           }
            return $sheet;            
        } 
		
		public function BindAllRABList($sheetid,$rbn)
		{
			$select_rab_query 	= "select distinct rbn from mbookgenerate_staff where sheetid = '$sheetid' order by rbn desc";
			$select_rab_sql 	= mysql_query($select_rab_query);
			if ($select_rab_sql == true ){
				while($RList = mysql_fetch_object($select_rab_sql)){
					$Rab .=  '<option value="'.$RList->rbn.'">';
				}            
			}
			return $Rab;            
		} 
		public function BindAllDaeUnits($UnitId)
		{
			$UnitsQuery = "SELECT * FROM dae_units WHERE active = 1 ORDER BY unit_name ASC";
           	$UnitsSql 	= mysql_query($UnitsQuery);
           	if($UnitsSql == true ){
				while($UnitsList = mysql_fetch_array($UnitsSql)){
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
			$UnitsQuery = "SELECT * FROM sheet WHERE active = 1 ORDER BY short_name ASC";
           	$UnitsSql 	= mysql_query($UnitsQuery);
           	if($UnitsSql == true ){
				while($UnitsList = mysql_fetch_array($UnitsSql)){
					if($SheetId == $UnitsList['sheet_id']){
						$sel = "selected";
					}else{
						$sel = "";
					}
					if($UnitsList['short_name'] != ''){
						$WorkDesc = $UnitsList['short_name'];
					}else{
						$WorkDesc = $UnitsList['work_name'];
					}
					$Units .=  '<option value="'. $UnitsList['sheet_id'].'"'.$sel.'>'.$UnitsList['computer_code_no'].' - '.$WorkDesc.'</option>'; 
				}            
           	}
            return $Units; 
		} 
		public function BindAllHOABudget($HoaId)
		{
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
		public function BindCont($contid)
		{ 	
		$mbookquerys = "select * from contractor where active = 1";
		$mbooksqlquerys = mysql_query($mbookquerys);
		if ($mbooksqlquerys == true ) {
			while($row = mysql_fetch_array($mbooksqlquerys))
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
	
	
	
	public function BindAllSecAdvItems($Sheetid){
		$selectQuery = "select * from schdule where sheet_id = '$Sheetid' and subdiv_id != 0";
		$selectSql = mysql_query($selectQuery);
		if($selectSql == true){
			while($List = mysql_fetch_object($selectSql)){
				if($List->shortnotes != ""){
					$desc = $List->shortnotes;
				}else{
					$desc = $List->description;
				}
				$ItemQty 	 = $List->total_quantity;
				$DeviatePerc = $List->deviate_qty_percent;
				$DeviateQty  = round(($ItemQty * $DeviatePerc /100),2);
				$TotalItemQty = round(($ItemQty + $DeviateQty),2);
				
				//$OutPutStr .= $List->subdiv_id."@#*#@".$List->sno."@#*#@".$List->rate."@#*#@".$List->decimal_placed."@#*#@".$List->per."@#*#@".$desc."@#*#@".$List->base_rate."@#*#@".$TotalItemQty."@#*#@";
				$items .=  '<option data-itemno="'.$List->sno.'" data-itemrate="'.$List->rate.'" data-itemdecimal="'.$List->decimal_placed.'" data-itemunit="'.$List->per.'" data-itemdescription="'.$desc.'" data-itembaserate="'.$List->base_rate.'" data-itemtotalqty="'.$TotalItemQty.'"  value="'. $List->subdiv_id.'"'.$sel.'>'.$List->sno.'</option>';     
			}
			//$OutPutStr = rtrim($OutPutStr ,"@#*#@");
		}
		return $items;     
	}
	
	
	public function BindEscMaterial($MatCode,$MatCata,$MatType)
	{
		if($matCata == "ALL"){
			$WhereClause = "";
		}else{
			$WhereClause = "and mat_category = '$MatCata'";
		}
		if($MatType == "S"){
			$WhereClause2 = " and mat_type = 'S'";
		}else if($MatType == "G"){
			$WhereClause2 = " and mat_type != 'S'";
		}else{
			$WhereClause2 = "";
		}
		
		$SelectQuery = "select * from material where active = 1 ".$WhereClause.$WhereClause2." order by mat_desc asc";
		$ResultQuery = mysql_query($SelectQuery);
		if($ResultQuery == true ){
			while($List = mysql_fetch_object($ResultQuery)){
				if($MatCode == $List->mat_code){
					$sel = " selected";
				}else{
					$sel = "";
				}
				$MatList .=  '<option value="'.$List->mat_code.'"'.$sel.'>'.$List->mat_desc.'</option>'; 
			}            
		}
		return $MatList;            
	}
	public function BindAllUnits($UnitId)
	{
		global $dbConn3;
		$SelectQuery = "select * from unit order by unit_name asc";
		$ResultQuery = mysql_query($SelectQuery);
		if($ResultQuery == true ){
			while($List = mysql_fetch_object($ResultQuery)){
				if($UnitId == $List->id){
					$sel = " selected";
				}else{
					$sel = "";
				}
				$UnitList .=  '<option value="'.$List->id.'"'.$sel.'>'.$List->unit_name.'</option>'; 
			}            
		}
		return $UnitList;            
	}
	public function BindAllAgreement($workordernolistvalue)
	{
		$sheetquery = "SELECT sheet_id,short_name,assigned_staff FROM  sheet WHERE active != 'x' ORDER BY sheet_id ASC";
		$sheetsqlquery = mysql_query($sheetquery);
		if ($sheetsqlquery == true )
		{
			while($row = mysql_fetch_array($sheetsqlquery))
			{
				$assigned_staff = $row['assigned_staff'];
				$AssignStaff = explode(",",$assigned_staff);
				if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1))
				{
					if ($workordernolistvalue == $row['sheet_id'])
					{
						$sel = "selected";
					}
					else
					{
						$sel = "";
					}
					if($row['short_name'] != ''){
						$WorkDesc = $row['short_name'];
					}else{
						$WorkDesc = $row['work_name'];
					}
					$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$WorkDesc.'</option>';
				}
			}            
		}
		return $sheet;            
	}
}
$objBind = new BindList();
?>