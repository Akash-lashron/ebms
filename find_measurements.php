<?php
require_once 'library/config.php';

$givenqty = $_GET['givenqty'];
$SheetId  = $_GET['sheetid'];
$SchId    = $_GET['gvnschid'];
$OutputArr = array();
$errmsg   = ""; $errval = 0;
$DpmQty = 0; $Qty = 0;
$AggQty = 0; $UtilizedQty = 0;

$sql_desc = "SELECT total_quantity,subdiv_id FROM schdule where sheet_id='" . $SheetId . "' AND sch_id='" . $SchId . "'";
$rs_desc  = mysql_query($sql_desc);
if(($rs_desc == true) && (mysql_num_rows($rs_desc)>0)){
    $SchList  = mysql_fetch_object($rs_desc);
    $AggQty     = $SchList->total_quantity;        //  mysql_result($rs_desc,0,'total_quantity');
    $subdivid   = $SchList->subdiv_id;           //  mysql_result($rs_desc,0,'subdiv_id');
    $deci 	    = $SchList->decimal_placed;
}
$MaxToDate  = "0000-00-00";
$sql_work = "SELECT work_order_date FROM sheet where sheet_id='".$SheetId."'";
$rs_work  = mysql_query($sql_work);
if(($rs_work == true) && (mysql_num_rows($rs_work)>0)){
    $WoList  	= mysql_fetch_object($rs_work);
    $MaxToDate  = $WoList->work_order_date;        //  mysql_result($rs_desc,0,'total_quantity');
}
/// GET SUM oF MBTOTAL FROM MEASUREMENT BOOK ->USedQty where ->sheetid and ->subdivid and ->partpay flag = (0 OR 1)
/// GERT MAX TO FROM ABSTRACTBOOK
/// GET ALL ROW FROM MBOOKDETAIL WHERE BASED ON DATE -> ADD COntents of Area
/// USed QTy + Total COntnts of ARea + Given Qty -> TotalQtyUsed

$SelectDPMQtyQuery = "SELECT sum(mbtotal) AS dpmqty FROM measurementbook WHERE sheetid = '" . $SheetId . "' AND subdivid='" . $subdivid . "' 
AND (part_pay_flag = 1 OR part_pay_flag = 0) GROUP BY subdivid";
$SelectDPMQtySql = mysql_query($SelectDPMQtyQuery);
if(($SelectDPMQtySql == true) && (mysql_num_rows($SelectDPMQtySql)>0)){
    $DPMList = mysql_fetch_object($SelectDPMQtySql);
    $DpmQty = $DPMList->dpmqty;
    $DpmQty = round($DpmQty,$deci);
}

$sql_absbook = "SELECT MAX(todate) as todate FROM abstractbook where sheetid='" . $SheetId . "' AND rab_status='C'";
$rs_absbook  = mysql_query($sql_absbook);
if(($rs_absbook == true) && (mysql_num_rows($rs_absbook)>0)){
    $ABookList = mysql_fetch_object($rs_absbook);
    $MaxToDate = $ABookList->todate;
}

$select_measurementqty_sql = "SELECT a.measurement_contentarea AS usedqty FROM mbookdetail a 
inner join mbookheader b on (a.mbheaderid = b.mbheaderid) where b.sheetid = '$SheetId' AND a.subdivid = '$subdivid' 
AND b.date > '$MaxToDate' AND a.measurement_contentarea != ''";
$select_measurementqty_query = mysql_query($select_measurementqty_sql);
if(($select_measurementqty_query == true) && (mysql_num_rows($select_measurementqty_query) > 0)){
    while($QtyList = mysql_fetch_object($select_measurementqty_query))
    {
        $itemno = $itemno;
        if($QtyList->usedqty == ""){ $QtyList->usedqty = 0; }
        $Qty 	= $Qty + $QtyList->usedqty;
    }
}
$UtilizedQty = $Qty + $DpmQty + $givenqty;

$OutputArr['AggQty'] = $AggQty;
$OutputArr['UtilizedQty'] = $UtilizedQty;
echo json_encode($OutputArr);


//  echo @mysql_result($rs_desc,0,'work_name').'*'.$RunningBillCount.'*'.@mysql_result($rs_desc,0,'work_order_no');
?>
