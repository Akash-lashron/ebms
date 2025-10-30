<?php
require_once 'library/config.php';
$sheetid = $_GET['sheetid'];
$quarter_query 	= "select quarter from escalation where sheetid = '$sheetid'";
$quarter_sql 		= mysql_query($quarter_query);
if($quarter_sql == true)
{
	while($QtrList = mysql_fetch_object($quarter_sql))
	{
		$Qtr 	= $QtrList->quarter;
		$QtrData 	.= $Qtr."*";
	}
	$QtrData 		= rtrim($QtrData,"*");
}
else
{
	$QtrData = "";
}
echo $QtrData;	
?>
