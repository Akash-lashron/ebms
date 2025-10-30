<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
//$sheetid = $_GET['sheetid'];
//$rbn = $_SESSION["rbn"];
//$sql1="SELECT max(fromdate) FROM measurementbook WHERE rbn = '$rbn' AND sheetid =".$_GET['sheetid'];
//$rs1 = mysql_query($sql1);
$sql2="SELECT max(todate) FROM measurementbook WHERE sheetid =".$_GET['sheetid'];
$rs2 = mysql_query($sql2);
//$res1 = @mysql_result($rs1,'fromdate');
$res2 = @mysql_result($rs2,'todate');
if($res2 != "")
{
    $result2 = strtotime($res2);
    $mindat = date("Y-m-d",$result2);
    $maxdat = date("Y-m-d");
	$nextdate = date('Y-m-d', strtotime('+1 day', strtotime($mindat)));
	
    echo dt_display($nextdate)."*".dt_display($maxdat);
}
else
{
    $sql3="SELECT date_upt FROM  sheet where sheet_id=".$_GET['sheetid'];
    $rs3=mysql_query($sql3);
    $fdate = @mysql_result($rs3,'date_upt');
    $tmp = strtotime($fdate);
    /*$fromdate = date("d/m/y",$tmp);
    $todate = date("d/m/y");*/
	$fromdate = "";
    $todate = "";
    echo $fromdate."*".$todate;
}
?>
