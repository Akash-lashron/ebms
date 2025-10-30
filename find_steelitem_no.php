<?php
require_once 'library/config.php';
$type = $_GET['type'];
$sheetid = $_GET['sheetid'];
if($type == "S")
{
    $where_clause = " AND schdule.measure_type = 'S'";
}
else
{
    $where_clause = "";
}

$sql_itemno="SELECT   DISTINCT subdivision.div_id,division.div_name
					  FROM   subdivision
					  INNER JOIN schdule ON (subdivision.subdiv_id = schdule.subdiv_id)
					  INNER JOIN division ON (subdivision.div_id = division.div_id)
					  WHERE schdule.sheet_id='$sheetid'"."$where_clause";
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
