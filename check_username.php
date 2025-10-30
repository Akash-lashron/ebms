<?php
session_start();
@ob_start();
require_once 'library/config.php';
$username = strtolower(trim($_GET['username']));
$count = 0;
$check_username_sql = "select username from users WHERE active = 1";
$check_username_query = mysql_query($check_username_sql);
while($rows=mysql_fetch_assoc($check_username_query))
{	
	$new_username = $rows['username'];
	$res_username = strtolower(trim($new_username));
	if($username  == $res_username)
	{
		$count++;	
	}
}
echo $count;
?>