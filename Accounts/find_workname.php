<?php
require_once 'library/config.php';
$pagesno=0;
/*$sql_desc="SELECT work_name, work_order_no, rbn FROM sheet where sheet_id='" . $_GET['sheetid'] . "'";
$rs_desc=mysqli_query($dbConn,$sql_desc);


$MeasurementBookQuery = "SELECT rbn FROM measurementbook WHERE sheet_id='" . $_GET['sheetid'] . "'";
$MeasurementBookSQL = mysqli_query($dbConn,$MeasurementBookQuery);*/

$RunningBillCount = 1; $WorkName = ""; $WorkOrderNo = "";
$MeasurementBookQuery = "SELECT * FROM sheet WHERE sheet_id='" . $_GET['sheetid'] . "'";
$MeasurementBookSQL = mysqli_query($dbConn,$MeasurementBookQuery);
if($MeasurementBookSQL == true){
	if(mysqli_num_rows($MeasurementBookSQL)>0){
		$List = mysqli_fetch_object($MeasurementBookSQL);
		$RunningBillCount = $List->rbn+1;
		$WorkName = $List->work_name;
		$WorkOrderNo = $List->work_order_no;
	}
}


/*if ($MeasurementBookSQL == false) { } else { $RowCount = mysqli_num_rows($MeasurementBookSQL);    }
if ($MeasurementBookSQL == true && $RowCount > 0) { $List = mysqli_fetch_object($MeasurementBookSQL); $RunningBillCount = $List->rbn+1; }
else { $RunningBillCount = 1;}*/
echo $WorkName.'*'.$RunningBillCount.'*'.$WorkOrderNo;
?>
