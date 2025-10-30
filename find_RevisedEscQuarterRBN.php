<?php
require_once 'library/config.php';
$sheetid		 	=  $_GET['sheetid'];
$quarter		 	=  $_GET['quarter'];
function dt_display($ddmmyyyy) 
{
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '-' . $mm . '-' . $yy;
}
$QtrStr = "";
$RbnQtrStr = "";
$select_curr_rbn_query 	= "select distinct(rbn) from mbookgenerate where sheetid = '$sheetid'";
$select_curr_rbn_sql = mysql_query($select_curr_rbn_query);
if($select_curr_rbn_sql == true)
{
	if(mysql_num_rows($select_curr_rbn_sql)>0)
	{
		$CurrRbnList = mysql_fetch_object($select_curr_rbn_sql);
		$curr_rbn = $CurrRbnList->rbn;
	}
}
						
$select_rbn_query = "select * from escalation where sheetid = '$sheetid' and flag = 0 and quarter = '$quarter'";					
$select_rbn_sql 	= mysql_query($select_rbn_query);
if($select_rbn_sql == true)
{
	if(mysql_num_rows($select_rbn_sql)>0)
	{
		while($RbnList = mysql_fetch_object($select_rbn_sql))
		{
			$rbn = $RbnList->rbn;
			$esc_id = $RbnList->esc_id;
			$tcc_fromdate 	= dt_display($RbnList->tcc_fromdate);
			$tcc_todate 	= dt_display($RbnList->tcc_todate);
			$tca_fromdate 	= dt_display($RbnList->tca_fromdate);
			$tca_todate 	= dt_display($RbnList->tca_todate);
			$QtrStr = $rbn."*".$tcc_fromdate."*".$tcc_todate."*".$tca_fromdate."*".$tca_todate."*".$esc_id."*".$curr_rbn;
		}
	}
}
echo $QtrStr;
?>