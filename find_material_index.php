<?php
require_once 'library/config.php';
$Sheetid 	 = $_GET['workorderno'];
$IndexCode 	 = $_GET['IndexCode'];
$MatCode 	 = $_GET['MatCode'];
$MatCata 	 = $_GET['MatCata'];
$WorkOrderMont = "";
$SelectQuery1  = "SELECT work_order_date FROM sheet WHERE sheet_id = '$Sheetid'";
$SelectSql1    = mysql_query($SelectQuery1);
if($SelectSql1 == true){
	if(mysql_num_rows($SelectSql1)>0){
		$List1 = mysql_fetch_object($SelectSql1);
		$WorkOrderDate  = $List1->work_order_date;
		$WorkOrderYear  = date('Y', strtotime($WorkOrderDate));
		$WorkOrderMonth = date('m', strtotime($WorkOrderDate));
	}
}
$IndexVal = "";
$SelectQuery2  = "SELECT * FROM monthly_index WHERE mat_code = '$MatCode' AND year = '$WorkOrderYear' and mat_category = '$MatCata'";
$SelectSql2    = mysql_query($SelectQuery2);
if($SelectSql2 == true){
	if(mysql_num_rows($SelectSql2)>0){
		$List2 = mysql_fetch_object($SelectSql2);
		if($WorkOrderMonth == "01"){ $IndexVal  = $List2->jan; }
		if($WorkOrderMonth == "02"){ $IndexVal  = $List2->feb; }
		if($WorkOrderMonth == "03"){ $IndexVal  = $List2->mar; }
		if($WorkOrderMonth == "04"){ $IndexVal  = $List2->apr; }
		if($WorkOrderMonth == "05"){ $IndexVal  = $List2->may; }
		if($WorkOrderMonth == "06"){ $IndexVal  = $List2->jun; }
		if($WorkOrderMonth == "07"){ $IndexVal  = $List2->jul; }
		if($WorkOrderMonth == "08"){ $IndexVal  = $List2->aug; }
		if($WorkOrderMonth == "09"){ $IndexVal  = $List2->sep; }
		if($WorkOrderMonth == "10"){ $IndexVal  = $List2->oct; }
		if($WorkOrderMonth == "11"){ $IndexVal  = $List2->nov; }
		if($WorkOrderMonth == "12"){ $IndexVal  = $List2->dece; }
	}
}
if($IndexVal == 0){ $IndexVal = ""; }   
echo $IndexVal;
	
?>
