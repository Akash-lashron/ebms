<?php
require_once 'library/config.php';
$sheetid		 =  $_GET['sheetid'];
$type			 =  $_GET['type'];
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '-' . $mm . '-' . $yy;
}

/*if($type == 'TCC')
{
	$select_esc_query 	= 	"select distinct max(esc_from_date) as fromdate, max(esc_to_date) as todate from escalation_tcc where sheetid = '$sheetid'";
}
else
{
	$select_esc_query 	= 	"select distinct max(esc_from_date) as fromdate, max(esc_to_date) as todate from escalation_10ca_details where sheetid = '$sheetid'";
}

$select_esc_sql 		= mysql_query($select_esc_query);
if($select_esc_sql == true)
{
	if(mysql_num_rows($select_esc_sql)>0)
	{
		$EList = mysql_fetch_object($select_esc_sql);
		$date1			= date_create($EList->fromdate);
		$date2			= date_create($EList->todate);
		$esc_from_date   = date_format($date1,"d-m-Y");
		$esc_to_date 	= date_format($date2,"d-m-Y");
		
		$Esc_str    .= $esc_from_date."*@*".$esc_to_date;
	}
	else
	{
		$Esc_str = "";
	}
}
else
{
	$Esc_str = "";
}*/
$select_rbn_query = 	"select distinct(mbookgenerate.rbn), escalation.esc_id, escalation.tca_fromdate, escalation.tca_todate,
						escalation.tcc_fromdate, escalation.tcc_todate 
						from mbookgenerate INNER JOIN escalation ON (escalation.rbn = mbookgenerate.rbn) 
						where mbookgenerate.sheetid = '$sheetid' and escalation.flag = 0";
						//echo $select_rbn_query;
$select_rbn_sql = mysql_query($select_rbn_query);
if($select_rbn_sql == true)
{
	if(mysql_num_rows($select_rbn_sql)>0)
	{
			$RbnList 		= mysql_fetch_object($select_rbn_sql);
			$esc_id 		= $RbnList->esc_id;
			$esc_rbn 		= $RbnList->rbn;
			$tca_fromdate 	= dt_display($RbnList->tca_fromdate);
			$tca_todate 	= dt_display($RbnList->tca_todate);
			$tcc_fromdate 	= dt_display($RbnList->tcc_fromdate);
			$tcc_todate 	= dt_display($RbnList->tcc_todate);
			$Esc_str    	.= $tca_fromdate."*@*".$tca_todate."*@*".$tcc_fromdate."*@*".$tcc_todate."*@*".$esc_rbn."*@*".$esc_id;
	}
	else
	{
		$Esc_str = "";
	}
}
else
{
	$Esc_str = "";
}

echo $Esc_str;
?>