<?php
require_once 'library/config.php';
$measuretype = $_GET['measure_type'];
if($measuretype == "S")
{
    $where_clause = " AND schdule.measure_type = 'S'";
}
else if($measuretype == "G")
{
    $where_clause = " AND schdule.measure_type != 'S'";
}
else
{
    $where_clause = "";
}
$sql_workorder="select work_order_no,work_name from sheet where sheet_id='" . $_GET['item_no'] . "'";
$rs_workorder=mysql_query($sql_workorder);
$workorder_no=@mysql_result($rs_workorder,0,'work_order_no');

$worknames=@mysql_result($rs_workorder,0,'work_name');

$sql_itemno="SELECT   DISTINCT subdivision.div_id,division.div_name
					  FROM   subdivision
					  INNER JOIN schdule ON (subdivision.subdiv_id = schdule.subdiv_id)
					  INNER JOIN sheet   ON (schdule.sheet_id = sheet.sheet_id)
					  INNER JOIN division ON (subdivision.div_id = division.div_id)
					  WHERE sheet.work_order_no='$workorder_no'"."$where_clause";
$rs_itemno=mysql_query($sql_itemno);

$id='group2';
$desc='group2';
while($rows=mysql_fetch_assoc($rs_itemno))
{
	$id=$id . '*' . $rows['div_id'];
	$desc=$desc . '*' . $rows['div_name'];
}

$group=$id . '*' . $desc;
echo $group;
	
?>
