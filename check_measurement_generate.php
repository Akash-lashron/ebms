<?php
session_start();
@ob_start();
function dt_format($ddmmyyyy) 
{
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}

require_once 'library/config.php';
$sheetid = $_GET['sheetid'];
$fromdate = dt_format($_GET['fromdate']);
$todate = dt_format($_GET['todate']);

$measure_type = $_GET['measure_type'];
if($measure_type == "G"){ $where_clause = "AND schdule.measure_type != 's'"; }
if($measure_type == "S"){ $where_clause = "AND schdule.measure_type = 's'"; }
$check_measurement_sql =  "SELECT DATE_FORMAT( mbookheader.date , '%d/%m/%Y' ) AS date ,  mbookdetail.subdivid , subdivision.subdiv_name , subdivision. div_id, 
mbookdetail.descwork, mbookdetail.measurement_no , mbookdetail.measurement_l , mbookdetail.measurement_b,  mbookdetail.structdepth_unit, 
mbookdetail.measurement_d , mbookdetail.measurement_contentarea , mbookdetail.remarks, schdule.measure_type, schdule.shortnotes, schdule.description, mbookheader.sheetid    
FROM mbookheader
INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) WHERE  mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' ".$where_clause." AND mbookdetail.mbdetail_flag != 'd' AND mbookheader.sheetid = '$sheetid' ORDER BY mbookheader.date, mbookdetail.subdivid ASC" ;
 
$check_measurement_query = mysql_query($check_measurement_sql);
if(mysql_num_rows($check_measurement_query)>0)
{
	echo 1;
}
else
{
	echo 0;
}