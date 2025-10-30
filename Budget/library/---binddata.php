<?php
ob_start();
require_once 'library/config.php';
require_once 'ExcelReader/excel_reader2.php';
$userid = $_SESSION['userid'];
class BindList
{
        public function BindWorkOrderNo($workordernolistvalue)
        {
            $sheetquery = "SELECT sheet_id,work_order_no FROM  sheet WHERE active=1 ORDER BY sheet_id ASC";
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
                $sheet .=  '<option value="'. $row['sheet_id'].'"'.$sel.'>'.$row['work_order_no'].'</option>'; 
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
                else{
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
        public function BindStaff($stafflistvalue)
        {
            $staffquery = "SELECT staffid,staffcode,staffname FROM  staff WHERE active=1 ORDER BY staffid ASC";
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
                            WHERE mbookallotment.active=1 AND mbookallotment.flag = 1  ".$WhereClause."  ORDER BY mbookallotment.mballotmentid ASC";
            echo $mbookquery;
            $mbooksqlquery = mysqli_query($dbConn,$mbookquery);
            
            
            if($staffid ==0) {  $WhereClause = "";} else  {  $WhereClause = "  AND staffid ='$staffid'  ";}
            $mbookquery = "SELECT    agreementmbookallotment.allotmentid,    agreementmbookallotment.mbno
                          FROM   mbookallotment
                            INNER JOIN agreementmbookallotment      ON (mbookallotment.sheetid = agreementmbookallotment.sheetid) AND (mbookallotment.allotmentid = agreementmbookallotment.allotmentid)
                            WHERE mbookallotment.active=1 AND mbookallotment.flag = 1  ".$WhereClause."  ORDER BY mbookallotment.mballotmentid ASC";
            $mbooksqlquery = mysqli_query($dbConn,$mbookquery);
            
            
            
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
        public function DisplayPageDetails($currentmbook)
        {
            $MBookQuery ="SELECT mbpage  FROM mbookallotment WHERE active=1 AND  mballotmentid  ='$currentmbook'";
            $MBookSQL = mysqli_query($dbConn,$MBookQuery);          $mbook='';
            if ($MBookSQL == true )        {    $row = mysqli_fetch_array($MBookSQL);    $mbook = $row['mbpage'];   }
            return $mbook;  
        }
        public function DisplayRBNDetails($wordorderno)
        {
            $RBNQuery ="SELECT work_name,rbn  FROM sheet  WHERE active=1 AND sheet_id  ='$wordorderno'";
            $RBNSQL = mysqli_query($dbConn,$RBNQuery);          $rbn='';
            if ($RBNSQL == true )        {    $row = mysqli_fetch_array($RBNSQL);     $rbn = $row['work_name'];   $rbn = $rbn."*".$row['rbn'];  
            }
            return $rbn;  
        }
        public function BindDesignation($designationlistvalue)
        {
            $designationquery = "SELECT designationid,designationname FROM `designation` WHERE active=1 ORDER BY designationid ASC";
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
        public function BindMBookList($mbookno)
        {
            $mbookquery = "SELECT mballotmentid,mbno FROM  mbookallotment WHERE mbno != '$mbookno' ORDER BY mballotmentid ASC";
            $mbooksqlquery = mysqli_query($dbConn,$mbookquery);
                      
           if ($mbooksqlquery == true ) {
            while($row = mysqli_fetch_array($mbooksqlquery))
            {
                $mbook .=  '<option value="'. $row['mballotmentid'].'"'.$sel.'>'.$row['mbno'].'</option>';     
            }            
           }
            return $mbook;     
        }
}
$objBind = new BindList();
?>