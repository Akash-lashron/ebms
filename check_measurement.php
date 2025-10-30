<?php
session_start();
@ob_start();
require_once 'library/config.php';
$subdivid = $_GET['subdivid'];
$check_measurement_sql = "select * from mbookdetail WHERE subdivid = '$subdivid' AND  mbdetail_flag != 'd'";
$check_measurement_query = mysql_query($check_measurement_sql);
if(mysql_num_rows($check_measurement_query)>0)
{
	echo 0;
}
else
{
	echo 1;
}