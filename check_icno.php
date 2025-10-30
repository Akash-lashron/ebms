<?php
session_start();
@ob_start();
require_once 'library/config.php';
$icno = strtolower(trim($_GET['icno']));
$count = 0;
$check_icno_sql = "select staffcode from staff WHERE active = 1";
$check_icno_query = mysql_query($check_icno_sql);
while($rows=mysql_fetch_assoc($check_icno_query))
{	
	$new_icno = $rows['staffcode'];
	$res_icno = strtolower(trim($new_icno));
	if($icno == $res_icno)
	{
		$count++;	
	}
}
echo $count;
?>