<?php
@ob_start();
require_once '../../library/config.php';
$rbn	=	$_POST[rbn];
$subdivid	=	$_POST[subdivid];
$sheetid	=	$_POST[sheetid];

$SelectDateQuery = "select DATE(MIN(fromdate)) as mindate, DATE(MIN(todate)) as maxdate from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' and rbn= '$rbn'";// and part_pay_flag != 'DMY' order by rbn desc";// and rbn = '$rbn'";
$SelectDateSql = mysql_query($SelectDateQuery);
if($SelectDateSql == true){
	if(mysql_num_rows($SelectDateSql)>0){
		$DateList = mysql_fetch_object($SelectDateSql);
		$fromdate = $DateList->mindate;
		$todate = $DateList->maxdate;
	}
}
ini_set("max_execution_time", "-1");
ini_set("memory_limit", "-1");
ignore_user_abort(true);
set_time_limit(0);

$SelectDetailsQuery = "select DATE_FORMAT(a.date, '%d/%m/%Y' ) as date, b.mbdetail_id, b.descwork,b.measurement_contentarea, b.remarks from mbookheader a inner join mbookdetail b on (b.mbheaderid = a.mbheaderid) where a.sheetid = '$sheetid' and a.subdivid = '$subdivid' and a.date >= '$fromdate' and a.date <= '$todate' order by a.date asc, b.mbheaderid asc, b.mbdetail_id asc";
$SelectDetailsSql 	= mysql_query($SelectDetailsQuery);
if($SelectDetailsSql == true){
	if(mysql_num_rows($SelectDetailsSql)>0){ 
		while($List = mysql_fetch_array($SelectDetailsSql)){
			$rows[] = $List;
		}
	}
}
echo json_encode($rows);
?> 