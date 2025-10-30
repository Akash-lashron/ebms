<?php
require_once 'library/config.php';
function dt_display($ddmmyyyy)
{
 $dt=explode('-',$ddmmyyyy);
 $dd=$dt[2];
 $mm=$dt[1];
 $yy=$dt[0];
 return $dd . '-' . $mm . '-' . $yy;
}
$sheetid 	= $_GET['workorderno'];
$TempType 	= $_GET['TempType'];
if($TempType == 1)
{
	$select_rbnno_query = "select DISTINCT rbn from measurementbook_temp where sheetid = '$sheetid'";
	$select_rbnno_sql = mysql_query($select_rbnno_query);
	if(mysql_num_rows($select_rbnno_sql)>0)
	{
		$RBNList = mysql_fetch_object($select_rbnno_sql);
		$rbn = $RBNList->rbn;
	}
	else
	{
		$rbn = "";
	}
	echo $rbn;
}
else if($TempType == 2)
{
	$rbn 		= $_GET['rbn'];
	$mbooktype 	= $_GET['mbooktype'];
	if(($rbn != "") && ($mbooktype != ""))
	{
		if($mbooktype == 'S')
		{
			$flag = 2;
		}
		else
		{
			$flag = 1;
		}
		$select_staff_query  =  "select DISTINCT mbookgenerate_staff.staffid, staff.staffname from mbookgenerate_staff 
								INNER JOIN staff ON (mbookgenerate_staff.staffid = staff.staffid)
								where mbookgenerate_staff.sheetid = '$sheetid' and mbookgenerate_staff.rbn = '$rbn' and  mbookgenerate_staff.flag = '$flag'";
		$select_staff_sql = mysql_query($select_staff_query);
		if(mysql_num_rows($select_staff_sql)>0)
		{
			while($StaffList = mysql_fetch_object($select_staff_sql))
			{
				$staffid 	= $StaffList->staffid;
				$staffname 	= $StaffList->staffname;
				$StaffData .= $staffid."*".$staffname."*";
			}
			$StaffData = rtrim($StaffData,"*");
		}
		else
		{
			$StaffData = "";
		}
	}
	else
	{
		$StaffData = "";
	}
	echo $StaffData;
}
else if($TempType == 3)
{
	$rbn 		= $_GET['rbn'];
	$mbooktype 	= $_GET['mbooktype'];
	$staffid 	= $_GET['staffid'];
	if(($rbn != "") && ($mbooktype != "") && ($staffid != ""))
	{
		if($mbooktype == 'S')
		{
			$flag = 2;
		}
		else
		{
			$flag = 1;
		}
		/*$select_zone_query  =  "select DISTINCT mbookgenerate_staff.zone_id, zone.zone_name from mbookgenerate_staff 
								INNER JOIN zone ON (mbookgenerate_staff.zone_id = zone.zone_id)
								where mbookgenerate_staff.sheetid = '$sheetid' and mbookgenerate_staff.rbn = '$rbn' 
								and mbookgenerate_staff.flag = '$flag' and  mbookgenerate_staff.staffid = '$staffid'";*/
		/*$select_zone_query  =  "select DISTINCT mbookgenerate_staff.zone_id, zone.zone_name from mbookgenerate_staff 
								INNER JOIN zone ON (mbookgenerate_staff.zone_id = zone.zone_id)
								INNER JOIN send_accounts_and_civil ON (mbookgenerate_staff.zone_id = send_accounts_and_civil.zone_id)
								where mbookgenerate_staff.zone_id NOT IN 
								(SELECT send_accounts_and_civil.sheetid FROM send_accounts_and_civil 
								where send_accounts_and_civil.mb_ac = 'SC' and send_accounts_and_civil.mtype = '$mbooktype')
								and mbookgenerate_staff.sheetid = '$sheetid' and mbookgenerate_staff.rbn = '$rbn' 
								and mbookgenerate_staff.flag = '$flag' and  mbookgenerate_staff.staffid = '$staffid'";*/
		$select_zone_query  =  "select DISTINCT mbookgenerate_staff.zone_id, zone.zone_name from mbookgenerate_staff 
								INNER JOIN zone ON (mbookgenerate_staff.zone_id = zone.zone_id)
								INNER JOIN send_accounts_and_civil ON (mbookgenerate_staff.zone_id = send_accounts_and_civil.zone_id)
								where send_accounts_and_civil.mb_ac = 'SA' and send_accounts_and_civil.mtype = '$mbooktype'
								and mbookgenerate_staff.sheetid = '$sheetid' and mbookgenerate_staff.rbn = '$rbn' 
								and mbookgenerate_staff.flag = '$flag' and  mbookgenerate_staff.staffid = '$staffid'";
		$select_zone_sql = mysql_query($select_zone_query);
		if(mysql_num_rows($select_zone_sql)>0)
		{
			while($ZoneList = mysql_fetch_object($select_zone_sql))
			{
				$zone_id 	= $ZoneList->zone_id;
				$zone_name 	= $ZoneList->zone_name;
				$ZoneData .= $zone_id."*".$zone_name."*";
			}
			$ZoneData = rtrim($ZoneData,"*");
		}
		else
		{
			$ZoneData = "";
		}
	}
	else
	{
		$ZoneData = "";
	}
	echo $ZoneData;
	//echo $select_zone_query;
}
else
{
	echo "";
}
?>
