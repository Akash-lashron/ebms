<?php
require_once 'library/config.php';
$mbgenerateid 	= $_POST['mbgenerateid'];
$remarks 	= $_POST['remarks'];
$mbookno 	= $_POST['mbookno'];
$sheetid 	= $_POST['sheetid'];
$zone_id 	= $_POST['zone_id'];
$rbn 		= $_POST['rbn'];
$mtype 		= $_POST['mtype'];
$linkid 	= $_POST['linkid'];
$staffid 	= $_POST['staffid'];
$levelid 	= $_POST['levelid'];
if($remarks != "")
{
	$accounts_comment = 1;
}
else
{
	$accounts_comment = 0;
}
$comment_query 	= "update send_accounts_and_civil set accounts_comment = '$accounts_comment' 
where sheetid  = '$sheetid' and  rbn  = '$rbn' and genlevel = 'composite'";
$comment_sql 	= mysql_query($comment_query);	

$remarks_query = "update mbookgenerate_staff set accounts_remarks = '$remarks' where mbgenerateid  = '$mbgenerateid'";
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
					 zone_id = '$zone_id', mtype = '$mtype', genlevel = 'composite', log_dt_date = NOW(), comments = '$remarks', 
					 mbdetail_id = '$mbgenerateid', staffid = '$staffid', levelid = '$levelid'"; 
$linsert_log_sql 	= mysql_query($linsert_log_query);*/
echo $data;
?>
