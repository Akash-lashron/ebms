<?php
require_once 'library/config.php';
//$sql_workorder="select work_order_no,work_name from sheet where sheet_id='" . $_GET['item_no'] . "'";
$sql_workorder="select distinct rbn from measurementbook where sheetid='" . $_GET['workordernumber'] . "'";
$rs_workorder=mysql_query($sql_workorder);
/*$workorder_no=@mysql_result($rs_workorder,0,'work_order_no');

$worknames=@mysql_result($rs_workorder,0,'work_name');

$sql_itemno="SELECT   DISTINCT subdivision.div_id,division.div_name
					  FROM   subdivision
					  INNER JOIN schdule ON (subdivision.subdiv_id = schdule.subdiv_id)
					  INNER JOIN sheet   ON (schdule.sheet_id = sheet.sheet_id)
					  INNER JOIN division ON (subdivision.div_id = division.div_id)
					  WHERE sheet.work_order_no='$workorder_no'";

$rs_itemno=mysql_query($sql_itemno);

$id='group2';
$desc='group2';*/
while($rows=mysql_fetch_assoc($rs_workorder))
{
	/*$id=$id . '*' . $rows['rbn'];
	$desc=$desc . '*' . $rows['rbn'];*/
	$rbn .= $rows['rbn']."*"; 
}

//$group=$id . '*' . $desc;
echo rtrim($rbn,"*");
	
?>
