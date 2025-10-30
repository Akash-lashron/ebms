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
           	$sheetquery = "SELECT sheet_id,short_name,assigned_staff FROM  sheet WHERE active = 1 ORDER BY sheet_id ASC";
           	$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysqli_fetch_array($sheetsqlquery))
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
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['short_name'].'</option>';
					//}
				}            
           	}
            return $sheet;            
        } 
       public function BindWorkOrderNoListStaff($workordernolistvalue)
       {
           	$sheetquery = "SELECT sheet_id,short_name,assigned_staff FROM  sheet WHERE (active='x' OR active = 1) ORDER BY sheet_id ASC";
           	$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysqli_fetch_array($sheetsqlquery))
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
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['short_name'].'</option>';
					//}
				}            
           	}
            return $sheet;            
        } 
		public function BindWorkOrderNoListUpload($workordernolistvalue)
       	{
           	$sheetquery = "SELECT sheet_id,short_name,assigned_staff FROM  sheet WHERE active='x' ORDER BY sheet_id ASC";
           	$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysqli_fetch_array($sheetsqlquery))
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
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['short_name'].'</option>';
					//}
				}            
           	}
            return $sheet;            
        } 
		public function BindWorkOrderNo($workordernolistvalue)
       	{
           	$sheetquery = "SELECT sheet_id,short_name,assigned_staff FROM  sheet WHERE active=1 ORDER BY sheet_id ASC";
           	$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
           	if ($sheetsqlquery == true )
           	{
				while($row = mysqli_fetch_array($sheetsqlquery))
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
						$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['short_name'].'</option>';
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
            $ItemNoFetchResult = mysqli_query($dbConn,$ItemNoFetchQuery);
            
            if ($ItemNoFetchResult == true && mysqli_num_rows($ItemNoFetchResult) >= 1)
           	{
				while($row = mysqli_fetch_array($ItemNoFetchResult))
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
            $SubItemNoFetchResult = mysqli_query($dbConn,$SubItemNoFetchQuery);
            
            if ($SubItemNoFetchResult == true && mysqli_num_rows($SubItemNoFetchResult) >= 1)
           {
            while($row = mysqli_fetch_array($SubItemNoFetchResult))
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
            $staffquery = "SELECT staffid,staffcode,staffname FROM  staff WHERE active=1 and sectionid = '$sectionid' ORDER BY staffid ASC";
            $staffsqlquery = mysqli_query($dbConn,$staffquery);
           if ($staffsqlquery == true )
           {
            while($row = mysqli_fetch_array($staffsqlquery))
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
            $mbooksqlquery = mysqli_query($dbConn,$mbookquery);
           // echo $mbooksqlquery;
           if ($mbooksqlquery == true )
           {
                    
            while($row = mysqli_fetch_array($mbooksqlquery))
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
            $PerSQL = mysqli_query($dbConn,$PerQuery);          $per='';
            if ($PerSQL == true )        {    $row = mysqli_fetch_array($PerSQL);    $per = $row['per'];   }
            return $per;  
        }
        public function DisplayPageDetails($currentmbookname,$currentmbookname,$sheetid,$generatetype,$rbn,$staffid)
        {
			$select_mbook_page_sql 		= 	"select MAX(mbpage) from mbookgenerate_staff WHERE staffid = '$staffid' AND sheetid = '$sheetid' AND mbno = '$currentmbookname' AND rbn = '$rbn'";
			$select_mbook_page_query	=	mysqli_query($dbConn,$select_mbook_page_sql);
			$mbookpageno_temp			=	@mysqli_result($select_mbook_page_query,'mbpage');
			
			if($mbookpageno_temp != "")
			{
				$mbpage		=	$mbookpageno_temp;
			}
			else
			{
				$select_mbook_page_sql_1		=	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$currentmbookname'";
				$select_mbook_page_query_1		=	mysqli_query($dbConn,$select_mbook_page_sql_1);
				$mbpage							=	@mysqli_result($select_mbook_page_query_1,'mbpage');
			}
			/*$current_rbn_sql = "select max(mbpage) as mbpage, max(rbn) as rbn from mbookgenerate_staff a WHERE NOT EXISTS (select rbn from measurementbook b WHERE a.rbn = b.rbn AND b.sheetid = '$sheetid') AND a.sheetid = '$sheetid' AND a.mbno = '$currentmbookname'";
			//echo $current_rbn_sql
			$current_rbn_query = mysqli_query($dbConn,$current_rbn_sql);
			if($current_rbn_query == true)
			{
				$res = mysqli_fetch_array($current_rbn_query); 
				$mbpage = $res['mbpage']; 
				$currentrbn = $res['rbn'];
				if($mbpage === NULL)
				{
				
			//}
			//else
			//{
			
					$MBookQuery ="SELECT mbpage  FROM mbookallotment WHERE active=1 AND  allotmentid  ='$currentmbook'";
					$MBookSQL = mysqli_query($dbConn,$MBookQuery);          $mbook='';
					if ($MBookSQL == true )
					{    
						$row = mysqli_fetch_array($MBookSQL);    
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
			$current_rbn_query = mysqli_query($dbConn,$current_rbn_sql);
			if($current_rbn_query == true)
			{
				$res = mysqli_fetch_array($current_rbn_query); 
				$mbpage = $res['mbpage']; 
				$currentrbn = $res['rbn'];
				if($mbpage === NULL)
				{
				
			//}
			//else
			//{
			
					$MBookQuery ="SELECT mbpage  FROM mbookallotment WHERE active=1 AND  allotmentid  ='$currentmbook'";
					$MBookSQL = mysqli_query($dbConn,$MBookQuery);          $mbook='';
					if ($MBookSQL == true )
					{    
						$row = mysqli_fetch_array($MBookSQL);    
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
            $RBNSQL = mysqli_query($dbConn,$RBNQuery);          $rbn='';
            if ($RBNSQL == true )        
			{    
				$row = mysqli_fetch_array($RBNSQL);     
				$rbn = $row['work_name'];   
				$rbn = $rbn."*".$row['rbn'];  
				$rbn = $rbn."*".$row['work_order_no'];
            }
            return $rbn;  
        }
        public function BindDesignation($designationlistvalue,$sectionid)
        {
            $designationquery = "SELECT designationid, designationname FROM `designation` WHERE active=1 AND sectionid = '$sectionid' ORDER BY designationid ASC";
            $designationsqlquery = mysqli_query($dbConn,$designationquery);
           if ($designationsqlquery == true )
           {
            while($row = mysqli_fetch_array($designationsqlquery))
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
            $mbooksqlquery = mysqli_query($dbConn,$mbookquery);
                     
           if ($mbooksqlquery == true ) {
            while($row = mysqli_fetch_array($mbooksqlquery))
            {
                $mbook .=  '<option value="'. $row['mballotmentid'].'"'.$sel.'>'.$row['mbno'].'</option>';     
            }            
           }
		   
           return $mbook;     
        }
		public function BindStaffSection($sectionvalue)
        {
            $sectionquery = "SELECT sectionid,section_name FROM staff_section WHERE active=1 ORDER BY sectionid ASC";
            $sectionsql = mysqli_query($dbConn,$sectionquery);
           	if ($sectionsql == true )
           	{
            	while($row = mysqli_fetch_array($sectionsql))
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
		
        public function BindWorkOrderNo_CIVIL($workordernolistvalue)
        {
            //$sheetquery = "SELECT sheet_id,short_name FROM  sheet WHERE active=1 ORDER BY sheet_id ASC";
			$sheetquery = "SELECT sheet_id, short_name, assigned_staff FROM sheet WHERE sheet_id NOT IN (SELECT sheetid FROM send_accounts_and_civil 
			where (mb_ac = 'SA' OR  sa_ac = 'SA' OR  ab_ac = 'SA') and active=1 ORDER BY sheet_id ASC)";
            $sheetsqlquery = mysqli_query($dbConn,$sheetquery);
           if ($sheetsqlquery == true )
           {
            //$sheet = '<option value=""> -- Select Work Order No -- </option>';
            while($row = mysqli_fetch_array($sheetsqlquery))
            {
				$assigned_staff = $row['assigned_staff'];
				$AssignStaff = explode(",",$assigned_staff);
				if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1))
				{
					if ($workordernolistvalue == $row['sheet_id'])
					{
						$sel = "selected";
					}
					else{
						$sel = "";
					}
					$sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['short_name'].'</option>';
				}
            }            
           }
            return $sheet;            
        } 
		
        public function BindWorkOrderNo_ACCOUNTS($workordernolistvalue)
        {
            //$sheetquery = "SELECT sheet_id,short_name FROM  sheet WHERE active=1 ORDER BY sheet_id ASC";
			$sheetquery = "SELECT sheet_id, short_name FROM sheet WHERE sheet_id NOT IN (SELECT sheetid FROM send_accounts_and_civil 
			where (mb_ac = 'SC' OR  sa_ac = 'SC' OR  ab_ac = 'SC') and active=1 ORDER BY sheet_id ASC)";
            $sheetsqlquery = mysqli_query($dbConn,$sheetquery);
           if ($sheetsqlquery == true )
           {
            //$sheet = '<option value=""> -- Select Work Order No -- </option>';
            while($row = mysqli_fetch_array($sheetsqlquery))
            {
                if ($workordernolistvalue == $row['sheet_id'])
                {
                    $sel = "selected";
                }
                else{
                    $sel = "";
                }
                $sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['short_name'].'</option>'; 
            }            
           }
            return $sheet;            
        } 
		public function BindStaffRole($rolevalue,$sectionid)
        {
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
		
}
$objBind = new BindList();
?>