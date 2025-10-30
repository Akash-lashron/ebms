<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
$sql1="SELECT min(fromdate) FROM mbookgenerate WHERE sheetid =".$_GET['sheetid'];
$rs1 = mysql_query($sql1);
$sql2="SELECT max(todate) FROM mbookgenerate WHERE sheetid =".$_GET['sheetid'];
$rs2 = mysql_query($sql2);
$res1 = @mysql_result($rs1,'fromdate');
$res2 = @mysql_result($rs2,'todate');
if(($res1 != "") && ($res2 != ""))
{
    $result1 = strtotime($res1);
    $mindat = date("d/m/Y",$result1);
    $result2 = strtotime($res2);
    $maxdat = date("d/m/Y",$result2);
    echo $mindat."*".$maxdat;
}
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
