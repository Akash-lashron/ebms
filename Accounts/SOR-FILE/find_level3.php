<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";

	
$sql_level3="select item_id,item_desc from item_master where item_id like '" . $_GET['item_id'] . "%'  and char_length( item_id ) = '9' order by item_desc";
$rs_level3=mysqli_query($dbConn,$sql_level3,$conn);
//echo $sql_level3.'</br>';

if(@mysqli_result($rs_level3,0,'item_id')!="")
{
	$rs_level3=mysqli_query($dbConn,$sql_level3,$conn);
	$id='level3';
	$name='level3';
	while($row=mysqli_fetch_assoc($rs_level3))
	{
		$id=$id . '*' . $row['item_id'];
		$name=$name . '*' . $row['item_desc'];
	}
	$items=$id . '*' . $name;
	echo $items;
}

?>