<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";

$sheet_id         = $_POST['saveid'];
$contid           = $_POST['contid'];
$Globid           = $_POST['Globid'];
$pan_no           = $_POST['pan_no'];
$gst_no          = $_POST['gst_no'];
$gst_inc_exc     = $_POST['gst_inc_exc'];
$gst_perc_rate    = $_POST['gst_perc_rate'];
$is_less_appl     = $_POST['is_less_appl'];

$OutputArr  = array();
$OutputArr['status'] = 0;
            $UpdatesheetQuery 	= "UPDATE sheet SET gst_perc_rate = '$gst_perc_rate', is_less_appl = '$is_less_appl',gst_inc_exc = '$gst_inc_exc'
			                     WHERE (sheet_id  = $sheet_id OR under_civil_sheetid = '$sheet_id') AND globid  = $Globid";
		    $Updatesheetsql     =  mysqli_query($dbConn,$UpdatesheetQuery);

			if (($contid!='')|| ($contid!=null)){
				$ContQuery          = "UPDATE contractor SET pan_no = '$pan_no', gst_no = '$gst_no' WHERE contid = $contid";
				$ContQuerysql       = mysqli_query($dbConn,$ContQuery);
			}
			
			$UpdateWorkQuery 	= "UPDATE works SET gst_perc_rate = '$gst_perc_rate', is_less_appl = '$is_less_appl',gst_inc_exc = '$gst_inc_exc'
			                        WHERE sheetid = $sheet_id AND globid  = $Globid";
		     $UpdateWorkQuerysql = mysqli_query($dbConn,$UpdateWorkQuery);
			
if($UpdateWorkQuerysql == true){
	    $OutputArr['status'] = 1;
}
//print_r( $OutputArr);  exit;
echo json_encode($OutputArr);
?>