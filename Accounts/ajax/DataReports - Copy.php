<?php
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/declaration.php';
header( 'Content-Type: text/html; charset=utf-8' );
// The code bellow demonstrates a simple back-end written in PHP
// Determine which field you want to check its existence
$IsAvailable = true;
$OutputArray = array();

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

if(isset($_POST['StmtCode'])){
	$PageCode 		= $_POST['StmtCode'];
	$MonthYr 		= $_POST['MonthYr'];
	/*$ExpBudFinYear	= explode("-",$BudFinYear);
	$FinStartYear	= $ExpBudFinYear[0];
	$FinEndYear		= $ExpBudFinYear[1];
	$FinStartDate	= $FinStartYear."-04-01";
	$FinEndDate		= $FinEndYear."-03-31";
	$GlobWhereClause = "";
	$GlobQ1Arr = array("apr","may","jun",4,5,6);
	$GlobQ2Arr = array("jul","aug","sep",7,8,9);
	$GlobQ3Arr = array("oct","nov","dec",10,11,12);
	$GlobQ4Arr = array("jan","feb","mar",1,2,3);*/
	if($MonthYr == "PREV"){
		$FromDate 	= date('Y-m-01', strtotime('-1 month'));
		$ToDate 	= date('Y-m-t', strtotime('-1 month'));
		$MonthYrStr = date('M-Y', strtotime('-1 month'));
	}else{
		$FromDate   = $MonthYr;
		$ToDate 	= date('Y-m-t', strtotime($FromDate));
	}
	//echo $PageCode;exit;
	switch ($PageCode) {
		case "ITSTMT":
			$SelectQuery = "SELECT * FROM memo_payment_accounts_edit WHERE payment_dt >= '$FromDate' AND payment_dt <= '$ToDate'";
			$SelectSql = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true){
				if(mysqli_num_rows($SelectSql)>0){
					while($List = mysqli_fetch_assoc($SelectSql)){
						$List['payment_dt'] = dt_display($List['payment_dt']);
						$OutputArray[] = $List;
					}
				}
			}

			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		
		
	  	default:
			 $IsAvailable = true;
		break;
	}
}

// Finally, return a JSON
echo json_encode(array(
    'valid' => $IsAvailable, 'data' => $OutputArray, 'month_yr_dp' => $MonthYrStr
));


?>