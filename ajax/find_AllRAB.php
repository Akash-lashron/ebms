<?php
@ob_start();
require_once '../library/config.php';
$sheetid	=	$_POST[sheetid];
$select_query 		= "select distinct rbn from mbookgenerate_staff where sheetid = '$sheetid' order by rbn desc";
$select_sql = mysql_query($select_query);
if($select_sql == true){
	if(mysql_num_rows($select_sql)>0){
		while($List = mysql_fetch_array($select_sql)){
			$rows[] = $List;
		}
	}
}
echo json_encode($rows);
//echo $select_query;
?> 