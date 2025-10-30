<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
//checkUser();
$userid 	= 	$_SESSION['userid'];
$staffid 	= 	$_SESSION['sid'];
$sheet_id 	= 	$_POST['sheetid'];
$inputStr	=	$_POST['inputStr'];
$action		=	$_POST['action'];
$type		=	$_POST['type'];
if($action == "all")
{
	$cnt = 0;
	if($type == 'g')
	{
		$select_sql = "select mbheaderid from mbookheader_temp where sheetid = '$sheet_id' and staffid = '$staffid' and measure_type != 's'";
		$select_query = mysql_query($select_sql);
		if($select_query == true)
		{
			while($IDList = mysql_fetch_object($select_query))
			{
				$mbheaderid = $IDList->mbheaderid;
				$delete_mbookhead_sql = "delete from mbookheader_temp where mbheaderid = '$mbheaderid'";
				$delete_mbookhead_query = mysql_query($delete_mbookhead_sql);
				
				$delete_mbookdetail_sql = "delete from mbookdetail_temp where mbheaderid = '$mbheaderid'";
				$delete_mbookdetail_query = mysql_query($delete_mbookdetail_sql);
				if(($delete_mbookhead_query == true) && ($delete_mbookdetail_query == true))
				{
					$cnt++;
				}
			}
		}
		if($cnt > 0)
		{
			echo "Sucessfully Deleted.";
		}
		else
		{
			echo "Sorry, Unable to delete.";
		}
	}
	if($type == 's')
	{
		$select_sql = "select mbheaderid from mbookheader_temp where sheetid = '$sheet_id' and staffid = '$staffid' and measure_type = 's'";
		$select_query = mysql_query($select_sql);
		if($select_query == true)
		{
			while($IDList = mysql_fetch_object($select_query))
			{
				$mbheaderid = $IDList->mbheaderid;
				$delete_mbookhead_sql = "delete from mbookheader_temp where mbheaderid = '$mbheaderid'";
				$delete_mbookhead_query = mysql_query($delete_mbookhead_sql);
				
				$delete_mbookdetail_sql = "delete from mbookdetail_temp where mbheaderid = '$mbheaderid'";
				$delete_mbookdetail_query = mysql_query($delete_mbookdetail_sql);
				if(($delete_mbookhead_query == true) && ($delete_mbookdetail_query == true))
				{
					$cnt++;
				}
			}
		}
		if($cnt > 0)
		{
			echo "Sucessfully Deleted.";
		}
		else
		{
			echo "Sorry, Unable to delete.";
		}
		//echo $delete_mbookhead_sql;
	}
}
else
{
	$cnt2 = 0;
	$splitInput	=	explode("*",$inputStr);
	$mbheaderid = 	$splitInput[0];
	$mbdetailid = 	$splitInput[1];
	
	$mbooheader_sql = "select * from mbookdetail_temp where mbheaderid = '$mbheaderid'";
	$mbooheader_query = mysql_query($mbooheader_sql);
	if($mbooheader_query == true)
	{
		if(mysql_num_rows($mbooheader_query)>0)
		{
			$mbookdetail_delete_sql = "delete from mbookdetail_temp where mbdetail_id = '$mbdetailid'";
			$mbookdetail_delete_query = mysql_query($mbookdetail_delete_sql);
			if($mbookdetail_delete_query == true){ $cnt2++; }
		}
		else
		{
			$mbookdetail_delete_sql = "delete from mbookdetail_temp where mbdetail_id = '$mbdetailid'";
			$mbookdetail_delete_query = mysql_query($mbookdetail_delete_sql);
			
			$mbookheader_delete_sql = "delete from mbookheader_temp where mbheaderid = '$mbheaderid'";
			$mbookheader_delete_query = mysql_query($mbookheader_delete_sql);
			if($mbookdetail_delete_query == true){ $cnt2++; }
		}
	}
	if($cnt2 > 0)
	{
		echo "Sucessfully Deleted.";
	}
	else
	{
		echo "Sorry, Unable to Delete.";
	}
}
?>
