<?php
//session_start();
@ob_start();
require_once 'library/config.php';
$userid 			= 	$_SESSION['userid'];
$sheet_id 			= 	$_POST['sheetid'];
$getmeasurement_sql = "select mbookheader_temp.mbheaderid, DATE_FORMAT(mbookheader_temp.date,'%d/%m/%Y') AS mdate, mbookheader_temp.divid, mbookheader_temp.subdiv_name, mbookdetail_temp.mbdetail_id , mbookdetail_temp.subdivid, mbookdetail_temp.descwork, mbookdetail_temp.measurement_no, mbookdetail_temp.measurement_l, mbookdetail_temp.measurement_b, mbookdetail_temp.measurement_d,  mbookdetail_temp.structdepth_unit,  mbookdetail_temp.measurement_dia, mbookdetail_temp.measurement_contentarea, mbookdetail_temp.remarks,  mbookdetail_temp.mbdetail_flag from mbookheader_temp INNER JOIN mbookdetail_temp ON (mbookheader_temp.mbheaderid = mbookdetail_temp.mbheaderid) where mbookheader_temp.sheetid = '$sheet_id'";

$getmeasurement_query = mysql_query($getmeasurement_sql);
if($getmeasurement_query == true)
{
	while($MList = mysql_fetch_array($getmeasurement_query))
	{
		/*$divid = $MList['divid'];
		if($divid == 0)
		{
			$itemno = "";
		}
		else
		{
			$itemno = $MList['sno'];
		}*/
		$json[]= array(
		  'date' => $MList['mdate'],
		  'mbdetailid' => $MList['mbdetail_id'],
		  'mbheaderid' => $MList['mbheaderid'],
		 'itemno' => $MList['subdiv_name'],  
		 'description' => $MList['descwork'],
		 'number' => $MList['measurement_no'],
		 'length' => $MList['measurement_l'],
		 'breadth' => $MList['measurement_b'],
		 'depth' => $MList['measurement_d'],
		 'structdepth_unit' => $MList['structdepth_unit'],
		 'dia' => $MList['measurement_dia'],
		 'contentarea' => $MList['measurement_contentarea'],
		 'errorflag' => $MList['mbdetail_flag'],
		 'iunit' => $MList['remarks']
		);
	}
	$jsonstring = json_encode($json);
 	echo $jsonstring;
	
}
//$json[]= array('qur' => $getmeasurement_sql);
//$jsonstring = json_encode($json);
 	//echo $jsonstring;
//echo $sheet_id;
//echo json_encode($MList);
//echo $getmeasurement_sql;
?>
