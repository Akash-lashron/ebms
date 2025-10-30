<?php
session_start();
@ob_start();
require_once 'library/config.php';
$data='';
$workorderno=$_GET['wkrorderno'];
$mbooktype=$_GET['mbooktype'];
//$remainmbook="SELECT agreementmbookallotment.mbno,agreementmbookallotment.allotmentid FROM agreementmbookallotment
//			  LEFT JOIN mbookallotment ON (agreementmbookallotment.allotmentid=mbookallotment.allotmentid)
//			  WHERE mbookallotment.allotmentid IS NULL and agreementmbookallotment.sheetid='$workorderno' order by agreementmbookallotment.mbno";  

$remainmbook="SELECT agreementmbookallotment.mbno, agreementmbookallotment.mbooktype, agreementmbookallotment.allotmentid FROM agreementmbookallotment
WHERE NOT EXISTS ( SELECT allotmentid FROM mbookallotment WHERE agreementmbookallotment.allotmentid = mbookallotment.allotmentid)
AND agreementmbookallotment.mbooktype ='$mbooktype' AND agreementmbookallotment.active = 1 AND agreementmbookallotment.sheetid ='$workorderno' ORDER BY agreementmbookallotment.mbno";

$rsremainbook=mysql_query($remainmbook);


if(@mysql_result($rsremainbook,0,'allotmentid')!='')
{
	$idB='function';
	$nameB='function';
	$rsremainbook=mysql_query($remainmbook);
	while($rows=mysql_fetch_assoc($rsremainbook))
	{	
		$idB=$idB . '*' . $rows['allotmentid'];
		$nameB=$nameB . '*' . $rows['mbno'];
	}
	$listB=$idB . '*' . $nameB;
}
else
{	
	$listB='NO';
}
echo $listB;
?>