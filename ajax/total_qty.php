<?php
require_once '../library/config.php';;
//Total_amt= "";
$sheet_id	=	$_POST[sheet_id];
$item_no	=	$_POST[item_no];
$rbn        =   $_POST[rbn];
$TotalQty == 0;
$select_mb_amt_query 		= "select mbtotal from measurementbook where sheetid = '$sheet_id' AND subdivid ='$item_no' AND rbn='$rbn' AND (part_pay_flag = '0' OR part_pay_flag = '1') ";
$select_mb_amt_sql = mysql_query($select_mb_amt_query);
if($select_mb_amt_sql == true){
	if(mysql_num_rows($select_mb_amt_sql)>0){
		while($List = mysql_fetch_object($select_mb_amt_sql)){
			$mb_total = $List->mbtotal;
			$TotalQty = $TotalQty + $mb_total;
		}
	}
}
if($TotalQty == 0){
$select_mb_amt_query1 		= "select mbtotal from mbookgenerate where sheetid = '$sheet_id' AND subdivid ='$item_no' AND rbn='$rbn' AND (part_pay_flag = '0' OR part_pay_flag = '1') ";
$select_mb_amt_sql1 = mysql_query($select_mb_amt_query1);
if($select_mb_amt_sql1 == true){
	if(mysql_num_rows($select_mb_amt_sql1)>0){
		while($List1 = mysql_fetch_object($select_mb_amt_sql1)){
			$mb_total = $List1->mbtotal;
			$TotalQty = $TotalQty + $mb_total;
		}
	}
  }
}
//echo json_encode($TotalQty);
echo $TotalQty;