<?php
@ob_start();
require_once '../library/config.php';
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
$PageCode	= $_POST['pagecode'];
$Frdateval	= $_POST['frdateval'];
$Todateval	= $_POST['todateval'];
$GlobID= '';
$OutputArray = array();
$OutputArray1 = array();
$TodayDate = "";
//echo $Frdateval;exit;
if(($Frdateval != "")&&($Todateval != "")){
	$FromDate = dt_format($Frdateval);
	$ToDate = dt_format($Todateval);
	//echo $FromDate;exit;
	switch ($PageCode){
		case "BAQQ":
			// $SelectQuery = "SELECT * FROM memo_payment_accounts_edit WHERE pay_order_dt >= '$FromDate' AND pay_order_dt <= '$ToDate' ORDER BY pay_order_dt ASC";
			$SelectQuery = "SELECT a.*,b.*,c.* FROM memo_payment_accounts_edit a
								LEFT JOIN contractor b ON (a.contid=b.contid)
								RIGHT JOIN contractor_bank_detail c ON (a.cbdtid=c.cbdtid)
								WHERE a.pay_order_dt >= '$FromDate' AND a.pay_order_dt <= '$ToDate' ORDER BY a.pay_order_dt ASC";
			$SelectSql = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true){
				if(mysqli_num_rows($SelectSql) > 0){
					while($List = mysqli_fetch_object($SelectSql)){
						$IfscCode = $List->ifsc_code;
						$Ifsc3Lett = substr($IfscCode,0,3);
						if(($Ifsc3Lett == 'SBI')||($Ifsc3Lett == 'Sbi')||($Ifsc3Lett == 'sbi')){
							$OutputArray[] = $List;
						}else{
							$OutputArray1[] = $List;
						}
					}
				}
			}
			$TodayDate = date('F d, Y');	//date("d/m/Y");
			//print_r($OutputArray);exit;
			if($RowCount == 0){
				$IsAvailable = false;
			}			
		break;
	}         	
}
			


$rows = array('row1'=>$OutputArray,'row2'=>$OutputArray1,'currdate'=>$TodayDate);

echo json_encode($rows);
?> 