<?php
require_once 'library/config.php';

$sql_workorder="select work_order_no from sheet where sheet_id='" . $_GET['work_no'] . "'";
$rs_workorder=mysql_query($sql_workorder);
$workorder_no=@mysql_result($rs_workorder,0,'work_order_no');
	$sql_itemno="SELECT   DISTINCT subdivision.subdiv_id,subdivision.subdiv_name
					  FROM   subdivision
					  INNER JOIN schdule ON (schdule.subdiv_id=subdivision.subdiv_id)
					  INNER JOIN sheet   ON (schdule.sheet_id = sheet.sheet_id)
					  INNER JOIN division ON (subdivision.div_id = division.div_id)
					  WHERE sheet.work_order_no='$workorder_no' and division.div_id='" . $_GET['div_id'] . "'";	
					  				  
$rs_itemno=mysql_query($sql_itemno);

$id='group2';
$desc='group2';
while($rows=mysql_fetch_assoc($rs_itemno))
{
	$id=$id . '*' . $rows['subdiv_id'];
	$desc=$desc . '*' . $rows['subdiv_name'];
}

$group=$id . '*' . $desc;
echo $group;
	
?>
