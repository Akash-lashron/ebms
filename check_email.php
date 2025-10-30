<?php
session_start();
@ob_start();
require_once 'library/config.php';
$data=$_GET['email'];

//$icno="select email from staff where email like '". $_GET['email']. "%'";
$icno="select email from staff where email ='". $_GET['email']. "'";
//echo $icno.'<Br>';
$rsicno=mysql_query($icno);

$result='';
while($rows=mysql_fetch_assoc($rsicno))
{
	$email=$rows['email'];
	if($data == $email) { $result='Y'; } else { $result='N'; }
}

/*if(@mysql_result($rsicno,0,'email')!='')
{
	$result='Y';
}
else
{
	$result='N';
}
*/
echo $result;
?>