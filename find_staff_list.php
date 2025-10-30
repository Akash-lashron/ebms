<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
$StaffStr = "";
$sheetid = $_GET['workorderno'];
$staff_query = "select assigned_staff from sheet WHERE sheet_id = '$sheetid' AND active = 1";
$staff_sql = mysql_query($staff_query);
if($staff_sql == true)
{
	if(mysql_num_rows($staff_sql)>0)
	{
		$SList = mysql_fetch_object($staff_sql);
		$StaffList = $SList->assigned_staff;
		$expStaffList = explode(",",$StaffList);
		for($i=0; $i<count($expStaffList); $i++)
		{
			$staffid = $expStaffList[$i];
			$select_staff_name_query = "select staffname from staff where staffid = '$staffid'";
			$select_staff_name_sql = mysql_query($select_staff_name_query );
			if($select_staff_name_sql == true)
			{
				if(mysql_num_rows($select_staff_name_sql)>0)
				{
					$List = mysql_fetch_object($select_staff_name_sql);
					$staffname = $List->staffname;
					$StaffStr .= $staffid."@*@".$staffname."@*@";
				}
			}
		}
		$StaffStr = rtrim($StaffStr,"@*@");
	}
}
echo $StaffStr;
?>
