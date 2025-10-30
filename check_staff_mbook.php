<?php
require_once 'library/config.php';
$staffid = $_GET['staffid'];
$check_staff_mbook_sql = "SELECT * FROM mbookallotment WHERE active = '1' AND staffid = '$staffid'";
$check_staff_mbook_query = mysql_query($check_staff_mbook_sql);
if(mysql_num_rows($check_staff_mbook_query)>0)
{
	echo 1;
}
else
{
	echo 0;
}
//echo $check_staff_mbook_sql;
?>
