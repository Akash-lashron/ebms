<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);

    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$MastId 	 = $_POST['MastId'];
$OutputArr = array();
$SelectWorkQuery = "SELECT * FROM tender_register WHERE tr_id = '$MastId' AND active = 1";
//echo $SelectWorkQuery;exit;
$SelectWorkQuerySql = mysqli_query($dbConn,$SelectWorkQuery);
if($SelectWorkQuerySql == true){
	if(mysqli_num_rows($SelectWorkQuerySql)>0){
		$WrkList = mysqli_fetch_object($SelectWorkQuerySql);
		$CCNO 	   = $WrkList->ccno;
		$GlobID 	= $WrkList->globid;
		$WorkName 	= $WrkList->work_name;
		$SelectQuery1 = "select loi_entry.*, contractor.name_contractor from  loi_entry
						JOIN contractor ON loi_entry.contid = contractor.contid 
						where tr_id = '$MastId'";
						
		$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
		if($SelectSql1 == true){
			if(mysqli_num_rows($SelectSql1)>0){
				while($List = mysqli_fetch_object($SelectSql1)){
					$TenderID                  =     $List->tr_id;
					$LOID 	                   =     $List->loa_pg_id;
					$ContId                    =     $List->contid;
					$ContName                  =     $List->name_contractor;
					$LoaNo                     =     $List->loa_no;
					$LoaDate                   =     dt_display($List->loa_dt);
					$Pgper                     =     $List->pg_per;
					$PgAmt                     =    round(( $List->pg_amt),0);
				
				
					$OutputArr[] =        $List;
				}
			}
		}
	}
}

$OutputArr['contid'] = $ContId;
$OutputArr['name_contractor'] = $ContName;
$OutputArr['loa_no'] = $LoaNo;
$OutputArr['loa_dt'] = $LoaDate;
$OutputArr['pg_per'] = $Pgper;
$OutputArr['pg_amt'] = $PgAmt;
$OutputArr['WorkName'] = $WorkName;
echo json_encode($OutputArr);
?>
