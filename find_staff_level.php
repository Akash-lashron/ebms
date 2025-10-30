<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
$roleid = $_GET['roleid'];
$staff_level_query = "select levelid, sectionid from staffrole WHERE active = 1 AND sroleid = '$roleid'";
$staff_level_sql = mysql_query($staff_level_query);
if($staff_level_sql == true)
{
	if(mysql_num_rows($staff_level_sql)>0)
	{
		$LevelList = mysql_fetch_object($staff_level_sql);
		$IDList = $LevelList->levelid."*".$LevelList->sectionid;
		//$IDList = $result;
	}
	else
	{
		$IDList = "";
	}
	echo $IDList;
}
else
{
	echo "";
}
//echo $staff_level_query;
?>
