<?php
session_start();
@ob_start();
require_once 'library/config.php';
$data='';
$engineerno=$_GET['engineerno'];
$sheetid=$_GET['wkrorderno'];
$mbooktype = $_GET['mbooktype'];
if($mbooktype == "All")
{
    $whereclause = "";
}
else
{
    $whereclause = " AND agreementmbookallotment.mbooktype = '$mbooktype' AND agreementmbookallotment.active = 1 ";
}
$staffcheck="select allotmentid from mbookallotment where staffid='$engineerno' and sheetid='$sheetid' AND active = 1";
$rstaffcheck=mysql_query($staffcheck);

$Staffmbook="select agreementmbookallotment.mbno,agreementmbookallotment.allotmentid,agreementmbookallotment.mbooktype from agreementmbookallotment inner join mbookallotment on(mbookallotment.allotmentid = agreementmbookallotment.allotmentid) where mbookallotment.staffid='$engineerno'".$whereclause." and mbookallotment.sheetid='$sheetid' AND mbookallotment.active = 1 order by agreementmbookallotment.mbno";
$rsbook=mysql_query($Staffmbook);

$idA='function';
$nameA='function';

while($rows=mysql_fetch_assoc($rsbook))
{	
	$idA=$idA . '*' . $rows['allotmentid'];
	$nameA=$nameA . '*' . $rows['mbno'];
}
$listA=$idA . '*' . $nameA;

	$remainmbook="SELECT agreementmbookallotment.mbno,agreementmbookallotment.allotmentid FROM agreementmbookallotment
			  LEFT JOIN mbookallotment ON (agreementmbookallotment.allotmentid=mbookallotment.allotmentid)
			  WHERE mbookallotment.allotmentid IS NULL and agreementmbookallotment.sheetid='$sheetid' AND mbookallotment.active = 1 AND agreementmbookallotment.active = 1";

/************************************ GET ALL RECORDS FORM FIRST TABLE THAT ARE NOT PRESENT IN SECOND TABLE **********************************/
	/*SELECT * FROM table1 LEFT JOIN table2 ON table2.number = table1.number WHERE table2.number IS NULL*/
/************************************ GET ALL RECORDS FORM FIRST TABLE THAT ARE NOT PRESENT IN SECOND TABLE **********************************/
			  
$rsremainbook=mysql_query($remainmbook);

$idB='function';
$nameB='function';

while($rows=mysql_fetch_assoc($rsremainbook))
{	
	$idB=$idB . '*' . $rows['allotmentid'];
	$nameB=$nameB . '*' . $rows['mbno'];
}
$listB=$idB . '*' . $nameB;
$ID=$idA.'*'.$idB;

$s=explode('*',$ID);
sort($s);

$record='';
for($w=0;$w<count($s);$w++) {
	$rec=$s[$w]; 
	$record=$record.'*'.$rec;
	}	
	$record=ltrim($record,'*');
							
$NAME=$nameA.'*'.$nameB;

$s=explode('*',$NAME);
sort($s);

$recordName='';
for($w=0;$w<count($s);$w++) {
	$rec=$s[$w]; 
	$recordName=$recordName.'*'.$rec;
	}	
	$recordName=ltrim($recordName,'*');

if(@mysql_result($rstaffcheck,0,'allotmentid')!='') {
//$totalRecord=rtrim($record,'*function*function').'/'.rtrim($recordName,'*function*function').'/'.ltrim($nameA,'function*'); }
$totalRecord=ltrim($nameA,'function*').'/'.ltrim($idA,'function*'); }
else{
$totalRecord='';
}

echo $totalRecord;
//echo $Staffmbook;


?>