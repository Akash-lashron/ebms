<?php
require_once '../library/config.php';;

$sheetid	=	$_POST[sheet_id];
$subdivid	=	$_POST[item_no];

$select_rbn_query 		= "select DISTINCT rbn from mbookgenerate_staff where sheetid  = '$sheetid' and subdivid ='$subdivid' ";
$select_rbn_sql = mysql_query($select_rbn_query);
if($select_rbn_sql == true){
	if(mysql_num_rows($select_rbn_sql)>0){
		while($List = mysql_fetch_array($select_rbn_sql)){
			$rows[] = $List;
		}
	}
}
echo json_encode($rows);