<?php
$rbn = ""; $rbn1 = "";
require_once 'library/config.php';
$MaxRbn = 0; $RABArr = array(); $RunnRABList = "";

$SelectMaxRABQuery	= "select max(rbn) as maxrbn from abstractbook where sheetid='".$_GET['workordernumber']."' and rab_status = 'C'";
$SelectMaxRABSql	= mysql_query($SelectMaxRABQuery);
if($SelectMaxRABSql == true){
	if(mysql_num_rows($SelectMaxRABSql)>0){
		$MaxList = mysql_fetch_object($SelectMaxRABSql);
		$MaxRbn = $MaxList->maxrbn;
	}
}

$SelectRunnRABQuery	= "select max(rbn) as rbn from abstractbook where sheetid='".$_GET['workordernumber']."' and rab_status = 'P'";//rbn > '$MaxRbn'";
$SelectRunnRABSql = mysql_query($SelectRunnRABQuery);
if($SelectRunnRABSql == true){
	if(mysql_num_rows($SelectRunnRABSql)>0){
		while($List = mysql_fetch_object($SelectRunnRABSql)){
			array_push($RABArr,$List->rbn);
		}
		$RunnRABList = implode(",",$RABArr);
	}
}
echo $MaxRbn."@@".$RunnRABList;

/*$sql_workorder	= "select distinct rbn from measurementbook where sheetid='".$_GET['workordernumber']."'";
$rs_workorder	= mysql_query($sql_workorder);
while($rows = mysql_fetch_object($rs_workorder)){
	$rbn .= $rows->rbn.","; 
}
$rbn = rtrim($rbn,",");
if($rbn != ""){
	$sql_workorder1	= "select distinct rbn from mbookgenerate_staff where sheetid='".$_GET['workordernumber']."' and rbn NOT IN (".$rbn.")";
}else{
	$sql_workorder1	= "select distinct rbn from mbookgenerate_staff where sheetid='".$_GET['workordernumber']."'";
}
$rs_workorder1	= mysql_query($sql_workorder1);
while($rows1 = mysql_fetch_object($rs_workorder1)){
	$rbn1 .= $rows1->rbn.","; 
}
$rbn1 = rtrim($rbn1,",");

echo $rbn."@@".$rbn1;*/
//echo $sql_workorder1;	
?>
