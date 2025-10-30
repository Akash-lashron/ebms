<?php
require_once 'library/config.php';
$sheetid 	= $_POST['sheetid'];
$itemid 	= $_POST['itemid'];
$remarks 	= $_POST['remarks'];
$mbookno 	= $_POST['mbookno'];
$rbn 		= $_POST['rbn'];
$staffid 	= $_POST['staffid'];
$levelid 	= $_POST['levelid'];
$zone_id 	= 0;
$mtype		= "A";
if($remarks != "")
{
	$accounts_comment = 1;
}
else
{
	$accounts_comment = 0;
}
$comment_query 	= "update send_accounts_and_civil set accounts_comment = '$accounts_comment' 
where sheetid  = '$sheetid' and  rbn  = '$rbn' and mtype = 'A' and genlevel = 'abstract'";
$comment_sql 	= mysql_query($comment_query);	

$remarks_query = "update measurementbook_temp set accounts_remarks = '$remarks' where sheetid = '$sheetid' and subdivid = '$itemid'";
$remarks_sql = mysql_query($remarks_query);
if($remarks_sql == true)
{
	$data = 1;
}
else
{
	$data = 0;
}

/*$linsert_log_query 	= "insert into acc_log_detail set linkid = '$linkid', sheetid = '$sheetid', rbn = '$rbn', mbookno = '$mbookno', 
					 zone_id = '$zone_id', mtype = '$mtype', genlevel = 'abstract', log_dt_date = NOW(), comments = '$remarks', 
					 mbdetail_id = '$itemid', staffid = '$staffid', levelid = '$levelid'";
$linsert_log_sql 	= mysql_query($linsert_log_query);*/

echo $data;
?>
