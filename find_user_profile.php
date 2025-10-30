<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
$temp1 = 0; $temp2 = 0;
$user_details = $_GET['user_details'];
$explode_result = explode("***",$user_details);
$staffname 		= $explode_result[0];
$designation 	= $explode_result[1];
$icno			= $explode_result[2];
$username 		= $explode_result[3];
$email 			= $explode_result[4];
$mobileno 		= $explode_result[5];
$intercomno 	= $explode_result[6];
$dob 			= dt_format($explode_result[7]);
$doj 			= dt_format($explode_result[8]);
$staffid 		= $explode_result[9];
if($staffid != 0)
{
	$update_staff_query = "UPDATE staff set staffname = '$staffname', email = '$email', designationid = '$designation', mobile = '$mobileno', intercom = '$intercomno', DOJ = '$doj', DOB = '$dob' WHERE staffid = '$staffid'";
	$update_staff_sql 	= mysql_query($update_staff_query);
	if($update_staff_sql == true)
	{
		$temp1 = 1;
	}
	else
	{
		$temp1 = 0;
	}
}
else
{
	$temp1 = 1;
}
$update_user_query	= "UPDATE users set username = '$username' WHERE staffid = '$staffid'";
$update_user_sql 	= mysql_query($update_user_query);
if($update_user_sql == true)
{
	$temp2 = 1;
}
else
{
	$temp2 = 0;
}
if(($temp1 == 1)&&($temp2 == 1))
{
	echo "S";
}
else
{
	echo "F";
}
?>
