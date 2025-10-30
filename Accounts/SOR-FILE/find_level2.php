<?php 
include "db_connect.php";

if($_GET['item_id']=='03')
{
	$sql_level2="select item_id,item_desc from item_master where item_id in (02,03,04,06,07,08,09) order by item_desc";
	$rs_level2=mysqli_query($dbConn,$sql_level2,$conn);
	//echo $sql_level2.'</br>';
	
	if(@mysqli_result($rs_level2,0,'item_id')!="")
	{
		$rs_level2=mysqli_query($dbConn,$sql_level2,$conn);
		$id='level2';
		$name='level2';
		while($row=mysqli_fetch_assoc($rs_level2))
		{
			$sql_item="select item_id,item_desc from item_master where item_id like '" . $row['item_id'] . "%'
						and char_length( item_id ) = '4' order by item_id";
			$rs_item=mysqli_query($dbConn,$sql_item,$conn);
			//echo $sql_item.'</br>';
			
			while($rows=mysqli_fetch_assoc($rs_item))
			{
				$id=$id . '*' . $rows['item_id'];
				$name=$name . '*' . $rows['item_desc'];
			}
		}
		$items=$id . '*' . $name;
		echo $items;
	}
}


if($_GET['item_id']=='05')
{
	$sql_level2="select item_id,item_desc from item_master where item_id in (02,04,05,06,07,08,09) order by item_desc";
	$rs_level2=mysqli_query($dbConn,$sql_level2,$conn);
	//echo $sql_level2.'</br>';
	
	if(@mysqli_result($rs_level2,0,'item_id')!="")
	{
		$rs_level2=mysqli_query($dbConn,$sql_level2,$conn);
		$id='level2';
		$name='level2';
		while($row=mysqli_fetch_assoc($rs_level2))
		{
			$sql_item="select item_id,item_desc from item_master where item_id like '" . $row['item_id'] . "%'
						and char_length( item_id ) = '4' order by item_id";
			$rs_item=mysqli_query($dbConn,$sql_item,$conn);
			//echo $sql_item.'</br>';
			
			while($rows=mysqli_fetch_assoc($rs_item))
			{
				$id=$id . '*' . $rows['item_id'];
				$name=$name . '*' . $rows['item_desc'];
			}
		}
		$items=$id . '*' . $name;
		echo $items;
	}
}


if($_GET['item_id']=='07')
{
	$sql_level2="select item_id,item_desc from item_master where item_id in (07) order by item_desc";
	$rs_level2=mysqli_query($dbConn,$sql_level2,$conn);
	//echo $sql_level2.'</br>';
	
	if(@mysqli_result($rs_level2,0,'item_id')!="")
	{
		$rs_level2=mysqli_query($dbConn,$sql_level2,$conn);
		$id='level2';
		$name='level2';
		while($row=mysqli_fetch_assoc($rs_level2))
		{
			$sql_item="select item_id,item_desc from item_master where item_id like '" . $row['item_id'] . "%'
						and char_length( item_id ) = '4' order by item_id";
			$rs_item=mysqli_query($dbConn,$sql_item,$conn);
			//echo $sql_item.'</br>';
			
			while($rows=mysqli_fetch_assoc($rs_item))
			{
				$id=$id . '*' . $rows['item_id'];
				$name=$name . '*' . $rows['item_desc'];
			}
		}
		$items=$id . '*' . $name;
		echo $items;
	}
}


?>