<?php
require_once 'library/config.php';
$sheetid 	= $_POST['sheetid'];
$zone_id 	= $_POST['zone_id'];
$rbn 		= $_POST['rbn'];
$mtype 		= $_POST['mtype'];
$genlevel 	= $_POST['genlevel'];
$lock_release_query = "update send_accounts_and_civil set locked_status = '' where sheetid  = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = '$mtype' and genlevel = '$genlevel'";
$lock_release_sql = mysql_query($lock_release_query);
if($lock_release_sql == true)
{
	$data = 1;
}
else
{
	$data = 0;
}
echo $data;
?>
