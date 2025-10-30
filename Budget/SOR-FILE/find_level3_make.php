<?php 
include "db_connect.php";

$sql_make="select distinct(make) as make1 from item_master where item_id like '" . $_GET['item_id'] . "%' 
				and char_length( item_id ) = '6' and make!='' order by make";
$rs_make=mysqli_query($dbConn,$sql_make,$conn);
//echo $sql_make.'</br>';

if(@mysqli_result($rs_make,0,'make1')!="")
{
	$rs_make=mysqli_query($dbConn,$sql_make,$conn);
	$id='make3';
	$name='make3';
	while($row=mysqli_fetch_assoc($rs_make))
	{
		$make=$make . '*' . $row['make1'];
	}
	$items=$make . '*' . $make;
	echo $items;
}
else
{
	echo '*';
}
?>