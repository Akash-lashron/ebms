<?php
require_once 'library/config.php';
function dt_display($ddmmyyyy)
{
 $dt=explode('-',$ddmmyyyy);
 $dd=$dt[2];
 $mm=$dt[1];
 $yy=$dt[0];
 return $dd . '-' . $mm . '-' . $yy;
}
$sheetid = $_GET['workorderno'];
$select_recovery_query 	= 	"SELECT wr1.* FROM water_recovery wr1 WHERE wr1.wrecoverid = (select max(wr2.wrecoverid) from water_recovery wr2 where wr2.sheetid = '$sheetid') and wr1.sheetid = '$sheetid'";
$select_recovery_sql	=	mysql_query($select_recovery_query);
if($select_recovery_sql == true) 
{
	$List = mysql_fetch_object($select_recovery_sql);
	$meter_no 		= 	$List->meter_no;
	$imr 			= 	$List->imr;
	$imr_date 		= 	dt_display($List->imr_date);
	$rate 			= 	$List->rate;
	$meter_rent 	= 	$List->meter_rent;
	$w_limit 		= 	$List->w_limit;
	$wr_date 		= 	dt_display($List->wr_date);
	$recoverydata 	= $meter_no."*".$imr."*".$imr_date."*".$rate."*".$meter_rent."*".$w_limit."*".$wr_date; 
}

$select_recovery_query_2 	= 	"SELECT wb1.* FROM generate_waterbill wb1 WHERE wb1.wid  = (select max(wb2.wid ) from generate_waterbill wb2 where wb2.sheetid = '$sheetid') and wb1.sheetid = '$sheetid'";
$select_recovery_sql_2	=	mysql_query($select_recovery_query_2);
if($select_recovery_sql_2 == true) 
{
	if(mysql_num_rows($select_recovery_sql_2)>0)
	{
		$FMRList = mysql_fetch_object($select_recovery_sql_2);
		$fmr 		= 	$FMRList->fmr;
		$fmr_date 	= 	dt_display($FMRList->fmr_date);
	}
}
$recoverydata 	= $recoverydata."*".$fmr."*".$fmr_date; 

echo $recoverydata;
	
?>
