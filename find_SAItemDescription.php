<?php
require_once 'library/config.php';
$sheetid 	= $_GET['sheetid'];
$Itemid 	= $_GET['Itemid'];
$Desc = "";
$sql_desc	= "select description from secured_advance_dt where sheetid = '$sheetid' AND subdivid = '$Itemid' order by sadtid desc LIMIT 1";
$rs_desc	= mysql_query($sql_desc);
if($rs_desc == true){
	$List = mysql_fetch_object($rs_desc);
	$Desc = $List->description;
}
echo $Desc;
?>