<?php
session_start();
@ob_start();
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
require_once 'library/config.php';
$data='';
$workorderno=$_GET['sheetid'];
$abstarctmbook_query = "select DISTINCT abstmbookno, rbn from mbookgenerate WHERE sheetid = '$workorderno'";
$abstarctmbook_sql = mysql_query($abstarctmbook_query);
$abstractmbookno = @mysql_result($abstarctmbook_sql,0,'abstmbookno');
$rbn = @mysql_result($abstarctmbook_sql,0,'rbn');
if(($rbn != "") && ($abstractmbookno != ""))
{
$abstractmbookpage_query = "select mbpage,allotmentid from mbookallotment WHERE sheetid = '$workorderno' AND staffid = '$staffid' AND active = '1' AND mbno = '$abstractmbookno'";
$abstractmbookpage_sql = mysql_query($abstractmbookpage_query);
$abstractmbookpage = @mysql_result($abstractmbookpage_sql,0,'mbpage');
$abstractmbookid = @mysql_result($abstractmbookpage_sql,0,'allotmentid');
echo $abstractmbookno."*".($abstractmbookpage+1)."*".$abstractmbookid."*".$rbn;
}
else
{
echo $abstarctmbook_query;
}
?>