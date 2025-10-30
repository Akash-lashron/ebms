<?php
require_once 'library/config.php';
$mbdetail_id = $_POST['mbdetail_id'];
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
	$accounts_remarks = $remarks."@R@".$mbookno;
	$accounts_comment = 1;
}
else
{
	$accounts_remarks = "";
	$accounts_comment = 0;
}
$comment_query 	= "update send_accounts_and_civil set accounts_comment = '$accounts_comment' 
where sheetid  = '$sheetid' and  rbn  = '$rbn' and mbookno = '$mbookno' and zone_id = '$zone_id'";
$comment_sql 	= mysql_query($comment_query);	

$remarks_query 	= "update mbookdetail set accounts_remarks = '$accounts_remarks' where mbdetail_id  = '$mbdetail_id'";
$remarks_sql 	= mysql_query($remarks_query);
if($remarks_sql == true)
{
	$data = 1;
}
else
{
	$data = 0;
}
/*$linsert_log_query 	= "insert into acc_log_detail set linkid = '$linkid', sheetid = '$sheetid', rbn = '$rbn', mbookno = '$mbookno', 
					 zone_id = '$zone_id', mtype = '$mtype', genlevel = 'staff', log_dt_date = NOW(), comments = '$remarks', 
					 mbdetail_id = '$mbdetail_id', staffid = '$staffid', levelid = '$levelid'";
$linsert_log_sql 	= mysql_query($linsert_log_query);*/
echo $data;
?>
