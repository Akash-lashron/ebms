<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";

$sql_group3="select group_id,group_desc from group_datasheet where group_id like '" . $_GET['group_id'] . "%'
			and char_length( group_id ) = '6' and delete_In='' order by group_id";
$rs_group3=mysqli_query($dbConn,$sql_group3,$conn);
//echo $sql_group3.'</br>';

$id='group3';
$desc='group3';
while($rows=mysqli_fetch_assoc($rs_group3))
{
	$id=$id . '*' . $rows['group_id'];
	$desc=$desc . '*' . $rows['group_desc'];
}

$group=$id . '*' . $desc;
echo $group;



?>