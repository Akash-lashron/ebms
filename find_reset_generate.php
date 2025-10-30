<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
$sheetid 	= $_POST['mbsid'];
$mbheaderid = $_POST['mbhid'];
$mbdetailid = $_POST['mbdid'];
$mbzoneid 	= $_POST['mbzid'];
$mbtype 	= $_POST['mtype'];
if($mbtype == "G"){
	$flag = 1;
}
if($mbtype == "S"){
	$flag = 2;
}
$MaxRbn = 0; $CurreRbn = 0;
$MaxRbnQuery = "SELECT max(rbn) as maxrbn FROM measurementbook WHERE sheetid = '$sheetid'";
$MaxRbnSql = mysql_query($MaxRbnQuery);
if($MaxRbnSql == true){
	if(mysql_num_rows($MaxRbnSql)>0){
		$MaxRbnList = mysql_fetch_object($MaxRbnSql);
		$MaxRbn = $MaxRbnList->maxrbn;
	}
}

$MaxRbnQuery1 = "SELECT max(rbn) as maxrbn1 FROM mbookgenerate_staff WHERE sheetid = '$sheetid' and rbn > '$MaxRbn'";
$MaxRbnSql1 = mysql_query($MaxRbnQuery1);
if($MaxRbnSql1 == true){
	if(mysql_num_rows($MaxRbnSql1)>0){
		$MaxRbnList1 = mysql_fetch_object($MaxRbnSql1);
		$MaxRbn = $MaxRbnList1->maxrbn1;
	}
}

$DeleteQuery1 	= "delete from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$MaxRbn' and zone_id = '$mbzoneid' and flag = '$flag'";
$DeleteSql1 	= mysql_query($DeleteQuery1);
		
$DeleteQuery2 	= "delete from mbookgenerate where sheetid = '$sheetid' and rbn = '$MaxRbn'";
$DeleteSql2 	= mysql_query($DeleteQuery2);
		
$DeleteQuery3 	= "delete from measurementbook_temp where sheetid = '$sheetid' and rbn = '$MaxRbn'";
$DeleteSql3 	= mysql_query($DeleteQuery3);
		
$DeleteQuery4 	= "delete from abstractbook where sheetid = '$sheetid' and rbn = '$MaxRbn'";
$DeleteSql4 	= mysql_query($DeleteQuery4);
		
$DeleteQuery5 	= "delete from mymbook where sheetid = '$sheetid' and rbn = '$MaxRbn' and ((zone_id = '$mbzoneid' and mtype = '$mbtype') OR genlevel = 'composite' OR genlevel = 'abstract') ";
$DeleteSql5 	= mysql_query($DeleteQuery5);
/*else
{
    $sql3="SELECT date_upt FROM  sheet where sheet_id=".$_GET['sheetid'];
    $rs3=mysql_query($sql3);
    $fdate = @mysql_result($rs3,'date_upt');
    $tmp = strtotime($fdate);
    $fromdate = date("d/m/y",$tmp);
    $todate = date("d/m/y");
    echo $fromdate."*".$todate;
}*/
?>
