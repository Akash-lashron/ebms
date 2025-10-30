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
$designation_list_sql = "select designationid, designationname from designation WHERE active = 1";
$designation_list_query = mysql_query($designation_list_sql);
if($designation_list_query == true)
{
	if(mysql_num_rows($designation_list_query)>0)
	{
		while($designList = mysql_fetch_object($designation_list_query))
		{
			$result .= $designList->designationid."*".$designList->designationname."*";
		}
		$designlist = rtrim($result,"*");
	}
	else
	{
		$designlist = "";
	}
	echo $designlist;
}
else
{
	echo "";
}
?>
