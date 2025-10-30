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
$CCNoArray = array();

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
	if($MonthYr == "PREV"){
		$FromDate 	= date('Y-m-01', strtotime('-1 month'));
		$ToDate 	= date('Y-m-t', strtotime('-1 month'));
		$MonthYrStr = date('M-Y', strtotime('-1 month'));
		$MonthStr 	= date('m', strtotime('-1 month'));
		$YearStr 	= date('Y', strtotime('-1 month'));
	}else{
		$FromDate   = $MonthYr;
		$ToDate 	= date('Y-m-t', strtotime($FromDate));
		$MonthYrStr = date('M-Y', strtotime($FromDate));
		$MonthStr 	= date('m', strtotime($FromDate));
		$YearStr 	= date('Y', strtotime($FromDate));
	}
	//echo $PageCode;exit;
	$ContNameArr = array();
	$ContPanArr = array();
	$ContGstArr = array();
	$ContNameListArr = array();
	$ContPanListArr = array();
	$ContGstListArr = array();
	$SelectcontractorQuery = "SELECT * FROM contractor WHERE active=1";
	$SelectcontractorSql = mysqli_query($dbConn,$SelectcontractorQuery);
	if($SelectcontractorSql == true){
		if(mysqli_num_rows($SelectcontractorSql)>0){
			while($ContList = mysqli_fetch_object($SelectcontractorSql)){
				$ContNameArr[$ContList->contid] = $ContList->name_contractor;
				$ContPanArr[$ContList->contid] = $ContList->pan_no;
				$ContGstArr[$ContList->contid] = $ContList->gst_no;
			}
		}
	}
	switch ($PageCode) {
		case "ITSTMT":
			$SelectQuery = "SELECT * FROM memo_payment_accounts_edit WHERE payment_dt >= '$FromDate' AND payment_dt <= '$ToDate' AND incometax_amt != 0 ORDER BY payment_dt ASC";
			$SelectSql = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true){
				if(mysqli_num_rows($SelectSql)>0){
					while($List = mysqli_fetch_assoc($SelectSql)){
						$ContId = $List['contid'];
						$List['payment_dt'] = dt_display($List['payment_dt']);
						$List['pan_no'] = $ContPanArr[$ContId];
						$List['cont_name'] = $ContNameArr[$ContId];
						$OutputArray[] = $List;
					}
				}
			}

			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		case "GSTSTMT":
			$SelectQuery = "SELECT * FROM memo_payment_accounts_edit WHERE payment_dt >= '$FromDate' AND payment_dt <= '$ToDate' AND gst_amount != 0  ORDER BY payment_dt ASC";
			$SelectSql = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true){
				if(mysqli_num_rows($SelectSql)>0){
					while($List = mysqli_fetch_assoc($SelectSql)){
						$List['payment_dt'] = dt_display($List['payment_dt']);
						$SheetID = $List['sheetid'];
						if($SheetID != null){
							$SelectSheetQuery = " SELECT * FROM sheet WHERE sheet_id = '$SheetID'";
							$SelectSheetSql = mysqli_query($dbConn,$SelectSheetQuery);
							if($SelectSheetSql == true){
								if(mysqli_num_rows($SelectSheetSql)>0){
									$SheetList = mysqli_fetch_assoc($SelectSheetSql);
									$ContID 	 = $SheetList['contid'];
									if($ContID != null){
										$ContNameVal = $ContNameArr[$ContID];
										$ContPanVal = $ContPanArr[$ContID];
										$ContGstVal = $ContGstArr[$ContID];
									}
									$CcnoVal = $SheetList['computer_code_no'];
									$CCNoArray[$SheetList['sheet_id']] = $CcnoVal;
									$ContNameListArr[$SheetList['sheet_id']] = $ContNameVal;
									$ContPanListArr[$SheetList['sheet_id']] = $ContPanVal;
									$ContGstListArr[$SheetList['sheet_id']] = $ContGstVal;
								}
							}
						}
						$OutputArray[] = $List;
					}
				}
			}
			
			if($RowCount == 0){
				$IsAvailable = false;
			}			
		break;
		case "LCESSSTMT":
			$SelectQuery = "SELECT * FROM memo_payment_accounts_edit WHERE payment_dt >= '$FromDate' AND payment_dt <= '$ToDate' AND lw_cess_amt != 0 ORDER BY payment_dt ASC";
			$SelectSql = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true){
				if(mysqli_num_rows($SelectSql)>0){
					while($List = mysqli_fetch_assoc($SelectSql)){
						$List['payment_dt'] = dt_display($List['payment_dt']);
						$SheetID = $List['sheetid'];
						if($SheetID != null){
							$SelectSheetQuery = " SELECT * FROM sheet WHERE sheet_id = '$SheetID'";
							$SelectSheetSql = mysqli_query($dbConn,$SelectSheetQuery);
							if($SelectSheetSql == true){
								if(mysqli_num_rows($SelectSheetSql)>0){
									$SheetList = mysqli_fetch_assoc($SelectSheetSql);
									$ContID 	 = $SheetList['contid'];
									if($ContID != null){
										$ContNameVal = $ContNameArr[$ContID];
									}
									$CcnoVal = $SheetList['computer_code_no'];
									$WrkNameVal = $SheetList['work_name'];
									$CCNoArray[$SheetList['sheet_id']] = $CcnoVal;
									$WrkNameArr[$SheetList['sheet_id']] = $WrkNameVal;
									$ContNameListArr[$SheetList['sheet_id']] = $ContNameVal;
								}
							}
						}
						$OutputArray[] = $List;
					}
				}
			}
			
			if($RowCount == 0){
				$IsAvailable = false;
			}			
		break;
		case "SDRCSTMT":
			$EicNameArr 	= array();
			$SelectStaffQuery = "SELECT staffid,staffname FROM staff";
			$SelectStaffSql = mysqli_query($dbConn,$SelectStaffQuery);
			if($SelectStaffSql == true){
				if(mysqli_num_rows($SelectStaffSql)>0){
					while($StaffList = mysqli_fetch_object($SelectStaffSql)){
						$EicNameArr[$StaffList->staffid] = $StaffList->staffname;
					}
				}
			}
			$SelectQuery = "SELECT * FROM memo_payment_accounts_edit WHERE payment_dt >= '$FromDate' AND payment_dt <= '$ToDate' AND sd_amt != 0 ORDER BY payment_dt ASC";
			$SelectSql = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true){
				if(mysqli_num_rows($SelectSql)>0){
					while($List = mysqli_fetch_assoc($SelectSql)){
						$List['payment_dt'] = dt_display($List['payment_dt']);
						$SheetID = $List['sheetid'];
						if($SheetID != null){
							$SelectSheetQuery = " SELECT * FROM sheet WHERE sheet_id = '$SheetID'";
							$SelectSheetSql = mysqli_query($dbConn,$SelectSheetQuery);
							if($SelectSheetSql == true){
								if(mysqli_num_rows($SelectSheetSql)>0){
									$SheetList = mysqli_fetch_assoc($SelectSheetSql);
									$ContID 	 = $SheetList['contid'];
									$StaffID	 = $SheetList['eic'];
									if($ContID != null){
										$ContNameVal = $ContNameArr[$ContID];
									}
									if($StaffID != null){
										$EicNameVal = $EicNameArr[$StaffID];
									}
									$CcnoVal 	= $SheetList['computer_code_no'];
									$WrkNameVal = $SheetList['work_name'];
									$WoNumVal 	= $SheetList['work_order_no'];
									$CCNoArray[$SheetList['sheet_id']] 			= $CcnoVal;
									$WrkNameArr[$SheetList['sheet_id']] 		= $WrkNameVal;
									$WoNumArr[$SheetList['sheet_id']] 			= $WoNumVal;
									$ContNameListArr[$SheetList['sheet_id']] 	= $ContNameVal;
									$EicNameListArr[$SheetList['sheet_id']] 	= $EicNameVal;
								}
							}
						}
						$OutputArray[] = $List;
					}
				}
			}
			
			if($RowCount == 0){
				$IsAvailable = false;
			}			
		break;
		case "PSDBRSH":
			$SelectQuery = "SELECT * FROM `bg_fdr_details` WHERE inst_purpose ='PG' AND ((createdon >= '$FromDate' AND createdon <= '$ToDate') or (released_date >= '$FromDate' AND released_date <= '$ToDate')) ORDER BY  master_id ASC";
			//echo $SelectQuery;
			$SelectSql = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true){
				if(mysqli_num_rows($SelectSql)>0){
					while($PgDetList = mysqli_fetch_assoc($SelectSql)){
						$PgDetList['createdon'] = dt_display($PgDetList['createdon']);
						$PgDetList['released_date'] = dt_display($PgDetList['released_date']);
						$GlobID	= $PgDetList['globid'];
						$ContID 	= $PgDetList['contid'];
						if($ContID != null){
							$ContNameVal = $ContNameArr[$ContID];
						}
						$SelectworksQuery = " SELECT * FROM works WHERE globid = '$GlobID'";
						$SelectworksSql 	= mysqli_query($dbConn,$SelectworksQuery);
						if($SelectworksSql == true){
							if(mysqli_num_rows($SelectworksSql)>0){
								$WorksList = mysqli_fetch_assoc($SelectworksSql);
								$CCnumber 	= $WorksList['ccno'];
								$WorkName 	= $WorksList['work_name'];
								$CCNoArray[$WorksList['globid']]  = $CCnumber;
								$WrkNameArr[$WorksList['globid']] = $WorkName;
								$ContNameListArr[$WorksList['globid']] = $ContNameVal;
							}
						}
						$OutputArray[] = $PgDetList;
					}
				}
			}
			
			if($RowCount == 0){
				$IsAvailable = false;
			}			
		break;
		case "SDBRSH":
			$SelectQuery = "SELECT * FROM memo_payment_accounts_edit WHERE payment_dt >= '$FromDate' AND payment_dt <= '$ToDate' AND ((mop_type = 'RAB' AND sd_amt != 0) OR (mop_type = 'SDR' AND net_payable_amt != 0)) ORDER BY payment_dt ASC";
			$SelectSql = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true){
				if(mysqli_num_rows($SelectSql)>0){
					while($List = mysqli_fetch_assoc($SelectSql)){
						$List['payment_dt'] = dt_display($List['payment_dt']);
						$SheetID = $List['sheetid'];
						if($SheetID != null){
							$SelectSheetQuery = " SELECT * FROM sheet WHERE sheet_id = '$SheetID'";
							$SelectSheetSql = mysqli_query($dbConn,$SelectSheetQuery);
							if($SelectSheetSql == true){
								if(mysqli_num_rows($SelectSheetSql)>0){
									$SheetList = mysqli_fetch_assoc($SelectSheetSql);
									$ContID 	 = $SheetList['contid'];
									if($ContID != null){
										$ContNameVal = $ContNameArr[$ContID];
									}
									$CcnoVal 	= $SheetList['computer_code_no'];
									$WoNumVal 	= $SheetList['work_order_no'];
									$WrkNameVal = $SheetList['work_name'];
									$CCNoArray[$SheetList['sheet_id']] 	= $CcnoVal;
									$WrkNameArr[$SheetList['sheet_id']] = $WrkNameVal;
									$WoNumArr[$SheetList['sheet_id']] 	= $WoNumVal;
									$ContNameListArr[$SheetList['sheet_id']] = $ContNameVal;
								}
							}
						}
						$OutputArray[] = $List;
					}
				}
			}
			
			if($RowCount == 0){
				$IsAvailable = false;
			}			
		break;
		case "VOUCH":
			$RowCount = 0; 
			$SelectQuery1 = "SELECT * FROM voucher_upt WHERE vr_dt >= '$FromDate' AND vr_dt <= '$ToDate' ORDER BY vr_dt ASC";
			$SelectResult1 = mysqli_query($dbConn, $SelectQuery1);
			if($SelectResult1 == true){
				if(mysqli_num_rows($SelectResult1)>0){
					while($Rows = mysqli_fetch_assoc($SelectResult1)){
						$TotVrAmt = $TotVrAmt + $VouchList3->vr_amt;
						$GlobId  = $Rows['globid'];
						$SheetId  = $Rows['sheetid'];
						if($SheetId != ''){
							$SelectQuery3 	= "SELECT * FROM works WHERE sheetid = '$SheetId'";
						}else{
							$SelectQuery3 	= "SELECT * FROM works WHERE globid = '$GlobId'";
						}
						$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
						if($SelectSql3 == true){
							if(mysqli_num_rows($SelectSql3)>0){
								$List3 	 = mysqli_fetch_object($SelectSql3);
								$WorkName = $List3->work_name; //echo $SelectQuery3;exit;
								$WoNo 	 = $List3->wo_no;
								$WoAmt 	 = $List3->wo_amount;
								$WoDate 	 = dt_display($List3->wo_date);
								$CcNo 	 = $List3->ccno;
								$ContName = $List3->name_contractor;
								if($List3->contid != null){
									$ContName = $ContNameArr[$List3->contid];
								}
								$WrkNameArr[$List3->sheet_id] = $WorkName;
								$Rows['item'] 		= $WorkName;
								$Rows['ccno'] 		= $CcNo;
								$Rows['indentor'] = $ContName;
							}
						}
						$Rows['vr_dt'] = dt_display($Rows['vr_dt']);
						$OutputArray[] = $Rows;
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
   'valid' => $IsAvailable, 'data' => $OutputArray,'worknamedata' => $WrkNameArr, 'ccnodata' => $CCNoArray, 
	'contnamedata' => $ContNameListArr, 'contpandata' => $ContPanListArr, 'contgstdata' => $ContGstListArr, 'month_yr_dp' => $MonthYrStr, 
	'month_str' => $MonthStr, 'year_str' => $YearStr, 'wonumdata' => $WoNumArr, 'eicnamedata' => $EicNameListArr
));


?>