<?php
require_once 'library/config.php';
$sheetid		 	=  $_GET['sheetid'];
$staffnameStr = "";
$select_sa_query 	= "select distinct a.staffid,b.staffname,b.staffid from mbookgenerate_staff a inner join staff b on (a.staffid=b.staffid)
                       where a.sheetid = '$sheetid' order by a.staffid ASC";
$select_sa_sql 	= mysql_query($select_sa_query);
if($select_sa_sql == true)
{
	if(mysql_num_rows($select_sa_sql)>0)
	{
		while($SAList = mysql_fetch_object($select_sa_sql))
		{
			$staffname 	 = $SAList->staffname;
			$staffid 	 = $SAList->staffid;
			$staffnameStr = $staffname;
			$staffidStr   = $staffid;
		}
		$staffnameStr = rtrim($staffnameStr."*".$staffidStr);
	}
}
echo $staffnameStr;
?>