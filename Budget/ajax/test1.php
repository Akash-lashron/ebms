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

if(isset($_POST['code'])){
	$PageCode 		= $_POST['code'];
	$BudUnit 		= $_POST['BudUnit'];
	$BudFinYear 	= $_POST['BudFinYear'];
	$BudDiscipline 	= $_POST['BudDiscipline'];
	$BudHoa 		= $_POST['BudHoa'];
	$BudMode 		= $_POST['BudMode'];
	$BudMonth 		= $_POST['BudMonth'];
	$BudYear 		= $_POST['BudYear'];
	$BudThreeQtr 	= $_POST['BudThreeQtr'];
	$BudHalfYear 	= $_POST['BudHalfYear'];
	$BudQuarter 	= $_POST['BudQuarter'];
	$BudRupeesIn 	= $_POST['RupeesIn'];
	$ExpBudFinYear	= explode("-",$BudFinYear);
	$FinStartYear	= $ExpBudFinYear[0];
	$FinEndYear		= $ExpBudFinYear[1];
	$FinStartDate	= $FinStartYear."-04-01";
	$FinEndDate		= $FinEndYear."-03-31";
	$GlobWhereClause = "";
	if(($BudUnit != "ALL")&&($BudUnit != "")){
		$GlobWhereClause .= " AND unitid = '$BudUnit'";
	}
	if(($BudDiscipline != "ALL")&&($BudDiscipline != "")){
		$GlobWhereClause .= " AND grp_div_sec = '$BudDiscipline'";
	}
	if(($BudHoa != "ALL")&&($BudHoa != "")){
		$GlobWhereClause .= " AND (hoa = '$BudHoa' OR new_hoa = '$BudHoa')";
	}
	$GlobQ1Arr = array("apr","may","jun",4,5,6);
	$GlobQ2Arr = array("jul","aug","sep",7,8,9);
	$GlobQ3Arr = array("oct","nov","dec",10,11,12);
	$GlobQ4Arr = array("jan","feb","mar",1,2,3);
	//echo $BudHoa;exit;
	/*$OldHoa = 90;
	$NewHoa = 1000;
	$Hoa = ($NewHoa != '') ? $NewHoa : $OldHoa;
	echo $Hoa;exit;*/
	switch ($PageCode) {
		case "EXPMO":
			$RowCount = 0;
			$WorksArr = array(); 
			
			if($BudHoa == "ALL"){
				$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no fromsd hoa_master WHERE fin_year = '$BudFinYear'"; 
			}else{ 
				$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no fromfdfdf hoa_master WHERE fin_year = '$BudFinYear' AND (old_hoa_no = '$BudHoa' OR new_hoa_no = '$BudHoa')"; 
			} 
			$SelectHoaSql = mysqli_query($dbConn, $SelectHoaQuery);
			if($SelectHoaSql == true){
				if(mysqli_num_rows($SelectHoaSql)>0){
					while($HoaList = mysqli_fetch_object($SelectHoaSql)){
						$OldHoa = $HoaList->old_hoa_no;
						$NewHoa = $HoaList->new_hoa_no;
						$Hoa = ($NewHoa != '') ? $NewHoa : $OldHoa;
						$WorkList = array(); $HoaWorkArr = array();
						$MonthArr = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0);
						$SelectVouchQuery1 = "SELECT * FROM voucher_upt WHERE MONTH(vr_dt) = '$BudMonth' AND (hoa = '$Hoa' OR new_hoa = '$Hoa') AND vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate'".$GlobWhereClause;

						$SelectVouchSql1 = mysqli_query($dbConn, $SelectVouchQuery1);
						if($SelectVouchSql1 == true){
							if(mysqli_num_rows($SelectVouchSql1)>0){
								while($VouchList = mysqli_fetch_object($SelectVouchSql1)){
									
									//if(isset($WorkList['globid'])){
									if(isset($HoaWorkArr[$VouchList->globid])){
										$WorkList = $HoaWorkArr[$VouchList->globid]; // Work exist in current HOA array
									}else{
										if(isset($WorksArr[$VouchList->globid])){
											$WorkList = $WorksArr[$VouchList->globid]; // Work exist in current global work array
										}else{
											$SelectQuery2  = "SELECT * FROM works WHERE globid = '$VouchList->globid'";
											$SelectResult2 = mysqli_query($dbConn, $SelectQuery2);
											if($SelectResult2 == true){
												if(mysqli_num_rows($SelectResult2) > 0){
													$WorkList = mysqli_fetch_assoc($SelectResult2);
													if($WorkList['ccno'] != 0){
														$WorkList['ccno_wono'] = "CCNO : ".$WorkList['ccno']." / W.O. : ".$WorkList['wo_no'];
													}else{
														$WorkList['ccno_wono'] = "";
													}
													if($BudRupeesIn == "L"){
														//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 100000),2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}else if($BudRupeesIn == "C"){
														//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 10000000),2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}else{
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}
													$WorksArr[$VouchList->globid] = $WorkList;
												}
											}
										}
									}
									
									
									
									$Month = date("n", strtotime($VouchList->vr_dt));
									if(isset($WorkList['month'])){
										$WorkList['month'] = $WorkList['month'] + $VouchList->vr_amt;
									}else{
										$WorkList['month'] = $VouchList->vr_amt;
									}
									$HoaWorkArr[$VouchList->globid] = $WorkList;
									
								}
							}
						}
						if(count($HoaWorkArr)>0){
							foreach($HoaWorkArr as $HoaWorkKey => $HoaWorkValue){
								$Rows = array();
								$Rows = $HoaWorkValue;
								/*if($BudRupeesIn == "L"){
									$Rows['month'] = round(($Rows['month'] / 100000),2);
								}else if($BudRupeesIn == "C"){
									$Rows['month'] = round(($Rows['month'] / 10000000),2);
								}else{
									$Rows['month'] = round($Rows['month'],2);
								}*/
								$Rows['hoa_no'] = $Hoa;
								$Rows['total_prev_fy'] = '';
								$Rows['total_curr_fy'] = '';
								$Rows['total_exp_upto_dt'] = '';
								$OutputArray[] = $Rows;
								//print_r($Rows);echo "</br>";echo "</br>";
							}
						}
					}
				}
			}
			
			//exit;
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "EXPQU":
			$RowCount = 0;
			if($BudQuarter == "Q1"){
				$WhereClause = " AND (MONTH(vr_dt) = '4' OR MONTH(vr_dt) = '5' OR MONTH(vr_dt) = '6')";
			}else if($BudQuarter == "Q2"){
				$WhereClause = " AND (MONTH(vr_dt) = '7' OR MONTH(vr_dt) = '8' OR MONTH(vr_dt) = '9')";
			}else if($BudQuarter == "Q3"){
				$WhereClause = " AND (MONTH(vr_dt) = '10' OR MONTH(vr_dt) = '11' OR MONTH(vr_dt) = '12')";
			}else if($BudQuarter == "Q4"){
				$WhereClause = " AND (MONTH(vr_dt) = '1' OR MONTH(vr_dt) = '2' OR MONTH(vr_dt) = '3')";
			}else{
				$WhereClause = "";
			}
			
			$WorksArr = array(); 
			if($BudHoa == "ALL"){
				$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no from hoa_master WHERE fin_year = '$BudFinYear'"; 
			}else{
				$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no from hoa_master WHERE fin_year = '$BudFinYear' AND (old_hoa_no = '$BudHoa' OR new_hoa_no = '$BudHoa')"; 
			}
			$SelectHoaSql = mysqli_query($dbConn, $SelectHoaQuery);
			if($SelectHoaSql == true){
				if(mysqli_num_rows($SelectHoaSql)>0){
					while($HoaList = mysqli_fetch_object($SelectHoaSql)){
						$OldHoa = $HoaList->old_hoa_no;
						$NewHoa = $HoaList->new_hoa_no;
						$Hoa = ($NewHoa != '') ? $NewHoa : $OldHoa;
						$WorkList = array(); $HoaWorkArr = array();
						$MonthArr = array("jan"=>0, "feb"=>0, "mar"=>0, "apr"=>0, "may"=>0, "jun"=>0, "jul"=>0, "aug"=>0, "sep"=>0, "oct"=>0, "nov"=>0, "dec"=>0);
						$SelectVouchQuery1 = "SELECT * FROM voucher_upt WHERE (hoa = '$Hoa' OR new_hoa = '$Hoa') AND vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate'".$WhereClause.$GlobWhereClause;
						$SelectVouchSql1 = mysqli_query($dbConn, $SelectVouchQuery1);
						if($SelectVouchSql1 == true){
							if(mysqli_num_rows($SelectVouchSql1)>0){
								while($VouchList = mysqli_fetch_object($SelectVouchSql1)){
									
									if(isset($HoaWorkArr[$VouchList->globid])){
										$WorkList = $HoaWorkArr[$VouchList->globid]; // Work exist in current HOA array
									}else{
										if(isset($WorksArr[$VouchList->globid])){
											$WorkList = $WorksArr[$VouchList->globid]; // Work exist in current global work array
										}else{
											$SelectQuery2  = "SELECT * FROM works WHERE globid = '$VouchList->globid'";
											$SelectResult2 = mysqli_query($dbConn, $SelectQuery2);
											if($SelectResult2 == true){
												if(mysqli_num_rows($SelectResult2) > 0){
													$WorkList = mysqli_fetch_assoc($SelectResult2);
													if($WorkList['ccno'] != 0){
														$WorkList['ccno_wono'] = "CCNO : ".$WorkList['ccno']." / W.O. : ".$WorkList['wo_no'];
													}else{
														$WorkList['ccno_wono'] = "";
													}
													if($BudRupeesIn == "L"){
														//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 100000),2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}else if($BudRupeesIn == "C"){
														//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 10000000),2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}else{
														//$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}
													foreach($MonthArr as $key => $Value){
														$WorkList[$key] = 0;
													}
													$WorksArr[$VouchList->globid] = $WorkList;
												}
											}
										}
									}
									
									$Month = strtolower(date("M", strtotime($VouchList->vr_dt)));
									if(isset($WorkList[$Month])){
										$WorkList[$Month] = $WorkList[$Month] + $VouchList->vr_amt;
									}else{
										$WorkList[$Month] = $VouchList->vr_amt;
									}
									$HoaWorkArr[$VouchList->globid] = $WorkList;
								}
							}
						}
						
						if(count($HoaWorkArr)>0){
							foreach($HoaWorkArr as $HoaWorkKey => $HoaWorkValue){
								$Rows = array();
								$Rows = $HoaWorkValue;
								/*foreach($MonthArr as $key => $Value){
									if($BudRupeesIn == "L"){
										$Rows[$key] = round(($Rows[$key] / 100000),2);
									}else if($BudRupeesIn == "C"){
										$Rows[$key] = round(($Rows[$key] / 10000000),2);
									}else{
										$Rows[$key] = round($Rows[$key],2);
									}
									if(($Rows[$key] == NULL)||($Rows[$key] == 0)){
										//$Rows[$key] = 0;
									}
								}*/
								$Rows['hoa_no'] = $Hoa;
								$Rows['total_prev_fy'] = '';
								$Rows['total_curr_fy'] = '';
								$Rows['total_exp_upto_dt'] = '';
								$OutputArray[] = $Rows;
								//print_r($HoaWorkArr);echo "</br>";echo "</br>";
							}
						}
					}
				}
			}
			
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "EXPHY":
			$RowCount = 0;
			if($BudHalfYear == "H1"){
				$WhereClause = " AND MONTH(vr_dt) >= '4' AND MONTH(vr_dt) <= '9'";
			}else if($BudHalfYear == "H2"){
				$WhereClause = " AND ((MONTH(vr_dt) >= '10' AND MONTH(vr_dt) <= '12') OR (MONTH(vr_dt) >= '1' AND MONTH(vr_dt) <= '3'))";
			}else{
				$WhereClause = "";
			}
			
			$WorksArr = array(); 
			if($BudHoa == "ALL"){
				$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no from hoa_master WHERE fin_year = '$BudFinYear'"; 
			}else{
				$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no from hoa_master WHERE fin_year = '$BudFinYear' AND (old_hoa_no = '$BudHoa' OR new_hoa_no = '$BudHoa')"; 
			}
			$SelectHoaSql = mysqli_query($dbConn, $SelectHoaQuery);
			if($SelectHoaSql == true){
				if(mysqli_num_rows($SelectHoaSql)>0){
					while($HoaList = mysqli_fetch_object($SelectHoaSql)){
						$OldHoa = $HoaList->old_hoa_no;
						$NewHoa = $HoaList->new_hoa_no;
						$Hoa = ($NewHoa != '') ? $NewHoa : $OldHoa;
						$WorkList = array(); $HoaWorkArr = array();
						$MonthArr = array("jan"=>0, "feb"=>0, "mar"=>0, "apr"=>0, "may"=>0, "jun"=>0, "jul"=>0, "aug"=>0, "sep"=>0, "oct"=>0, "nov"=>0, "dec"=>0);
						$SelectVouchQuery1 = "SELECT * FROM voucher_upt WHERE (hoa = '$Hoa' OR new_hoa = '$Hoa') AND vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate'".$WhereClause.$GlobWhereClause;
						$SelectVouchSql1 = mysqli_query($dbConn, $SelectVouchQuery1);
						if($SelectVouchSql1 == true){
							if(mysqli_num_rows($SelectVouchSql1)>0){
								while($VouchList = mysqli_fetch_object($SelectVouchSql1)){
									
									if(isset($HoaWorkArr[$VouchList->globid])){
										$WorkList = $HoaWorkArr[$VouchList->globid]; // Work exist in current HOA array
									}else{
										if(isset($WorksArr[$VouchList->globid])){
											$WorkList = $WorksArr[$VouchList->globid]; // Work exist in current global work array
										}else{
											$SelectQuery2  = "SELECT * FROM works WHERE globid = '$VouchList->globid'";
											$SelectResult2 = mysqli_query($dbConn, $SelectQuery2);
											if($SelectResult2 == true){
												if(mysqli_num_rows($SelectResult2) > 0){
													$WorkList = mysqli_fetch_assoc($SelectResult2);
													if($WorkList['ccno'] != 0){
														$WorkList['ccno_wono'] = "CCNO : ".$WorkList['ccno']." / W.O. : ".$WorkList['wo_no'];
													}else{
														$WorkList['ccno_wono'] = "";
													}
													if($BudRupeesIn == "L"){
														//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 100000),2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}else if($BudRupeesIn == "C"){
														//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 10000000),2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}else{
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}
													foreach($MonthArr as $key => $Value){
														$WorkList[$key] = 0;
													}
													$WorksArr[$VouchList->globid] = $WorkList;
												}
											}
										}
									}
									
									
									
									$Month = strtolower(date("M", strtotime($VouchList->vr_dt)));
									if(isset($WorkList[$Month])){
										$WorkList[$Month] = $WorkList[$Month] + $VouchList->vr_amt;
									}else{
										$WorkList[$Month] = $VouchList->vr_amt;
									}
									$HoaWorkArr[$VouchList->globid] = $WorkList;
									
								}
							}
						}
						if(count($HoaWorkArr)>0){
							foreach($HoaWorkArr as $HoaWorkKey => $HoaWorkValue){
								$Rows = array();
								$WorkQtr1Amt = 0; //$WorkQtr2Amt = 0; $WorkQtr3Amt = 0; $WorkQtr4Amt = 0;
								$Rows = $HoaWorkValue;
								/*foreach($MonthArr as $key => $Value){
									if($BudRupeesIn == "L"){
										$Rows[$key] = round(($Rows[$key] / 100000),2);
									}else if($BudRupeesIn == "C"){
										$Rows[$key] = round(($Rows[$key] / 10000000),2);
									}else{
										$Rows[$key] = round($Rows[$key],2);
									}
									if(($Rows[$key] == NULL)||($Rows[$key] == 0)){
										//$Rows[$key] = 0;
									}
								}*/
								$Rows['hoa_no'] = $Hoa;
								$Rows['total_prev_fy'] = '';
								$Rows['total_curr_fy'] = '';
								$Rows['total_exp_upto_dt'] = '';
								$OutputArray[] = $Rows;
							}
						}
					}
				}
			}
			
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "EXPTQ":
			$RowCount = 0;
			if($BudThreeQtr == "TQ1"){
				$WhereClause = " AND MONTH(vr_dt) >= '4' AND MONTH(vr_dt) <= '12'";
			}else{
				$WhereClause = "";
			}
			
			$WorksArr = array(); 
			if($BudHoa == "ALL"){
				$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no from hoa_master WHERE fin_year = '$BudFinYear'"; 
			}else{
				$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no from hoa_master WHERE fin_year = '$BudFinYear' AND (old_hoa_no = '$BudHoa' OR new_hoa_no = '$BudHoa')"; 
			}
			$SelectHoaSql = mysqli_query($dbConn, $SelectHoaQuery);
			if($SelectHoaSql == true){
				if(mysqli_num_rows($SelectHoaSql)>0){
					while($HoaList = mysqli_fetch_object($SelectHoaSql)){
						$OldHoa = $HoaList->old_hoa_no;
						$NewHoa = $HoaList->new_hoa_no;
						$Hoa = ($NewHoa != '') ? $NewHoa : $OldHoa;
						$WorkList = array(); $HoaWorkArr = array();
						$MonthArr = array("jan"=>0, "feb"=>0, "mar"=>0, "apr"=>0, "may"=>0, "jun"=>0, "jul"=>0, "aug"=>0, "sep"=>0, "oct"=>0, "nov"=>0, "dec"=>0);
						$SelectVouchQuery1 = "SELECT * FROM voucher_upt WHERE (hoa = '$Hoa' OR new_hoa = '$Hoa') AND vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate'".$WhereClause.$GlobWhereClause;
						$SelectVouchSql1 = mysqli_query($dbConn, $SelectVouchQuery1);
						if($SelectVouchSql1 == true){
							if(mysqli_num_rows($SelectVouchSql1)>0){
								while($VouchList = mysqli_fetch_object($SelectVouchSql1)){
									
									if(isset($HoaWorkArr[$VouchList->globid])){
										$WorkList = $HoaWorkArr[$VouchList->globid]; // Work exist in current HOA array
									}else{
										if(isset($WorksArr[$VouchList->globid])){
											$WorkList = $WorksArr[$VouchList->globid]; // Work exist in current global work array
										}else{
											$SelectQuery2  = "SELECT * FROM works WHERE globid = '$VouchList->globid'";
											$SelectResult2 = mysqli_query($dbConn, $SelectQuery2);
											if($SelectResult2 == true){
												if(mysqli_num_rows($SelectResult2) > 0){
													$WorkList = mysqli_fetch_assoc($SelectResult2);
													if($WorkList['ccno'] != 0){
														$WorkList['ccno_wono'] = "CCNO : ".$WorkList['ccno']." / W.O. : ".$WorkList['wo_no'];
													}else{
														$WorkList['ccno_wono'] = "";
													}
													if($BudRupeesIn == "L"){
														//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 100000),2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}else if($BudRupeesIn == "C"){
														//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 10000000),2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}else{
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}
													foreach($MonthArr as $key => $Value){
														$WorkList[$key] = 0;
													}
													$WorksArr[$VouchList->globid] = $WorkList;
												}
											}
										}
									}
									
									
									
									$Month = strtolower(date("M", strtotime($VouchList->vr_dt)));
									if(isset($WorkList[$Month])){
										$WorkList[$Month] = $WorkList[$Month] + $VouchList->vr_amt;
									}else{
										$WorkList[$Month] = $VouchList->vr_amt;
									}
									$HoaWorkArr[$VouchList->globid] = $WorkList;
									
								}
							}
						}
						if(count($HoaWorkArr)>0){
							foreach($HoaWorkArr as $HoaWorkKey => $HoaWorkValue){
								$Rows = array();
								$WorkQtr1Amt = 0; //$WorkQtr2Amt = 0; $WorkQtr3Amt = 0; $WorkQtr4Amt = 0;
								$Rows = $HoaWorkValue;
								/*foreach($MonthArr as $key => $Value){
									if($BudRupeesIn == "L"){
										$Rows[$key] = round(($Rows[$key] / 100000),2);
									}else if($BudRupeesIn == "C"){
										$Rows[$key] = round(($Rows[$key] / 10000000),2);
									}else{
										$Rows[$key] = round($Rows[$key],2);
									}
									if(($Rows[$key] == NULL)||($Rows[$key] == 0)){
										//$Rows[$key] = 0;
									}
								}*/
								$Rows['hoa_no'] = $Hoa;
								$Rows['total_prev_fy'] = '';
								$Rows['total_curr_fy'] = '';
								$Rows['total_exp_upto_dt'] = '';
								$OutputArray[] = $Rows;
							}
						}
					}
				}
			}
			
			
			
			
			
			
			/*if($BudHoa == "ALL"){
				$HoaArr = array(); $HoaCount = 0;
				$SelectHoaQuery = "SELECT hoa, new_hoa FROM voucher_upt WHERE vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate'".$WhereClause.$GlobWhereClause." GROUP BY hoa, new_hoa";
				$SelectHoaSql = mysqli_query($dbConn, $SelectHoaQuery);
				if($SelectHoaSql == true){
					if(mysqli_num_rows($SelectHoaSql)>0){
						while($HoaList = mysqli_fetch_object($SelectHoaSql)){
							array_push($HoaArr,$HoaList->hoa);
							array_push($HoaArr,$HoaList->new_hoa);
						}
						if(count($HoaArr)>0){
							$HoaArr = array_unique($HoaArr);
							sort($HoaArr);
							$HoaCount = 1;
						}
					}
				}
				if($HoaCount == 1){
					foreach($HoaArr as $Key => $HoaValue){ 
						$SelectCCNoQuery = "SELECT DISTINCT ccno FROM voucher_upt WHERE vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate' AND (hoa = '$HoaValue' OR new_hoa = '$HoaValue')".$WhereClause.$GlobWhereClause;
						$SelectQuery2 = "SELECT * FROM works WHERE (ccno = '0' OR ccno IN ($SelectCCNoQuery))";
						$SelectResult2 = mysqli_query($dbConn, $SelectQuery2);
						if($SelectResult2 == true){
							if(mysqli_num_rows($SelectResult2)>0){
								$RowCount = 1;
								while($Rows = mysqli_fetch_assoc($SelectResult2)){
									$GlobId = $Rows['globid']; 
									$Rows['ccno_wono'] = "CCNO : ".$Rows['ccno']." / W.O. : ".$Rows['wo_no'];
									$MonthArr = array("jan"=>0, "feb"=>0, "mar"=>0, "apr"=>0, "may"=>0, "jun"=>0, "jul"=>0, "aug"=>0, "sep"=>0, "oct"=>0, "nov"=>0, "dec"=>0);
									$SelectQuery3 = "SELECT * FROM voucher_upt WHERE globid = '$GlobId'".$WhereClause;// AND MONTH(vr_dt) = '$BudMonth'";
									$SelectResult3 = mysqli_query($dbConn, $SelectQuery3);
									if($SelectResult3 == true){
										if(mysqli_num_rows($SelectResult3)>0){
											while($VouchList3 = mysqli_fetch_object($SelectResult3)){
												$Month = strtolower(date("M", strtotime($VouchList3->vr_dt)));
												$MonthArr[$Month] = $MonthArr[$Month] + $VouchList3->vr_amt;
											}
										}
									}
									$WorkQtr1Amt = 0; $WorkQtr2Amt = 0; $WorkQtr3Amt = 0; $WorkQtr4Amt = 0;
									foreach($MonthArr as $key => $Value){
										if($BudRupeesIn == "L"){
											$Rows[$key] = round(($MonthArr[$key] / 100000),2);
										}else if($BudRupeesIn == "C"){
											$Rows[$key] = round(($MonthArr[$key] / 10000000),2);
										}else{
											$Rows[$key] = round($MonthArr[$key],2);
										}
										if(in_array($key, $GlobQ1Arr)){
											$WorkQtr1Amt = $WorkQtr1Amt + $Rows[$key];
										}
										if(in_array($key, $GlobQ2Arr)){
											$WorkQtr2Amt = $WorkQtr2Amt + $Rows[$key];
										}
										if(in_array($key, $GlobQ3Arr)){
											$WorkQtr3Amt = $WorkQtr3Amt + $Rows[$key];
										}
										if(in_array($key, $GlobQ4Arr)){
											$WorkQtr4Amt = $WorkQtr4Amt + $Rows[$key];
										}
									}
									if($BudRupeesIn == "L"){
										$Rows['wo_amount'] = round(($Rows['wo_amount'] / 100000),2);
									}else if($BudRupeesIn == "C"){
										$Rows['wo_amount'] = round(($Rows['wo_amount'] / 10000000),2);
									}else{
										$Rows['wo_amount'] = round($Rows['wo_amount'],2);
									}
									$Rows['work_qtr1_amt'] = $WorkQtr1Amt;
									$Rows['work_qtr2_amt'] = $WorkQtr2Amt;
									$Rows['work_qtr3_amt'] = $WorkQtr3Amt;
									$Rows['work_qtr4_amt'] = $WorkQtr4Amt;
									$Rows['total_prev_fy'] = '';
									$Rows['total_curr_fy'] = '';
									$Rows['total_exp_upto_dt'] = '';
									$Rows['hoa_no'] = $HoaValue;
									$OutputArray[] = $Rows;
								}
							}
						}
					}
				}
			}else{
				$SelectCCNoQuery = "SELECT DISTINCT ccno FROM voucher_upt WHERE vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate'".$WhereClause.$GlobWhereClause;
				$SelectQuery2 = "SELECT * FROM works WHERE (ccno = '0' OR ccno IN ($SelectCCNoQuery))";
				$SelectResult2 = mysqli_query($dbConn, $SelectQuery2);
				if($SelectResult2 == true){
					if(mysqli_num_rows($SelectResult2)>0){
						$RowCount = 1;
						while($Rows = mysqli_fetch_assoc($SelectResult2)){
							$GlobId = $Rows['globid']; 
							$Rows['ccno_wono'] = "CCNO : ".$Rows['ccno']." / W.O. : ".$Rows['wo_no'];
							$MonthArr = array("jan"=>0, "feb"=>0, "mar"=>0, "apr"=>0, "may"=>0, "jun"=>0, "jul"=>0, "aug"=>0, "sep"=>0, "oct"=>0, "nov"=>0, "dec"=>0);
							$SelectQuery3 = "SELECT * FROM voucher_upt WHERE globid = '$GlobId'".$WhereClause;// AND MONTH(vr_dt) = '$BudMonth'";
							$SelectResult3 = mysqli_query($dbConn, $SelectQuery3);
							if($SelectResult3 == true){
								if(mysqli_num_rows($SelectResult3)>0){
									while($VouchList3 = mysqli_fetch_object($SelectResult3)){
										$Month = strtolower(date("M", strtotime($VouchList3->vr_dt)));
										$MonthArr[$Month] = $MonthArr[$Month] + $VouchList3->vr_amt;
									}
								}
							}
							$WorkQtr1Amt = 0; $WorkQtr2Amt = 0; $WorkQtr3Amt = 0; $WorkQtr4Amt = 0;
							foreach($MonthArr as $key => $Value){
								if($BudRupeesIn == "L"){
									$Rows[$key] = round(($MonthArr[$key] / 100000),2);
								}else if($BudRupeesIn == "C"){
									$Rows[$key] = round(($MonthArr[$key] / 10000000),2);
								}else{
									$Rows[$key] = round($MonthArr[$key],2);
								}
								if(in_array($key, $GlobQ1Arr)){
									$WorkQtr1Amt = $WorkQtr1Amt + $Rows[$key];
								}
								if(in_array($key, $GlobQ2Arr)){
									$WorkQtr2Amt = $WorkQtr2Amt + $Rows[$key];
								}
								if(in_array($key, $GlobQ3Arr)){
									$WorkQtr3Amt = $WorkQtr3Amt + $Rows[$key];
								}
								if(in_array($key, $GlobQ4Arr)){
									$WorkQtr4Amt = $WorkQtr4Amt + $Rows[$key];
								}
							}
							if($BudRupeesIn == "L"){
								$Rows['wo_amount'] = round(($Rows['wo_amount'] / 100000),2);
							}else if($BudRupeesIn == "C"){
								$Rows['wo_amount'] = round(($Rows['wo_amount'] / 10000000),2);
							}else{
								$Rows['wo_amount'] = round($Rows['wo_amount'],2);
							}
							$Rows['work_qtr1_amt'] = $WorkQtr1Amt;
							$Rows['work_qtr2_amt'] = $WorkQtr2Amt;
							$Rows['work_qtr3_amt'] = $WorkQtr3Amt;
							$Rows['work_qtr4_amt'] = $WorkQtr4Amt;
							$Rows['total_prev_fy'] = '';
							$Rows['total_curr_fy'] = '';
							$Rows['total_exp_upto_dt'] = '';
							$OutputArray[] = $Rows;
						}
					}
				}
			}*/
			//exit;
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "EXPYE":
			$RowCount = 0;
			$WhereClause = "";
			$WorksArr = array(); 
			if($BudHoa == "ALL"){
				$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no from hoa_master WHERE fin_year = '$BudFinYear'"; 
			}else{
				$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no from hoa_master WHERE fin_year = '$BudFinYear' AND (old_hoa_no = '$BudHoa' OR new_hoa_no = '$BudHoa')"; 
			}
			$SelectHoaSql = mysqli_query($dbConn, $SelectHoaQuery);
			if($SelectHoaSql == true){
				if(mysqli_num_rows($SelectHoaSql)>0){
					while($HoaList = mysqli_fetch_object($SelectHoaSql)){
						$OldHoa = $HoaList->old_hoa_no;
						$NewHoa = $HoaList->new_hoa_no;
						$Hoa = ($NewHoa != '') ? $NewHoa : $OldHoa;
						$WorkList = array(); $HoaWorkArr = array();
						$MonthArr = array("jan"=>0, "feb"=>0, "mar"=>0, "apr"=>0, "may"=>0, "jun"=>0, "jul"=>0, "aug"=>0, "sep"=>0, "oct"=>0, "nov"=>0, "dec"=>0);
						$SelectVouchQuery1 = "SELECT * FROM voucher_upt WHERE (hoa = '$Hoa' OR new_hoa = '$Hoa') AND vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate'".$WhereClause.$GlobWhereClause;
						$SelectVouchSql1 = mysqli_query($dbConn, $SelectVouchQuery1);
						if($SelectVouchSql1 == true){
							if(mysqli_num_rows($SelectVouchSql1)>0){
								while($VouchList = mysqli_fetch_object($SelectVouchSql1)){
									
									if(isset($HoaWorkArr[$VouchList->globid])){
										$WorkList = $HoaWorkArr[$VouchList->globid]; // Work exist in current HOA array
									}else{
										if(isset($WorksArr[$VouchList->globid])){
											$WorkList = $WorksArr[$VouchList->globid]; // Work exist in current global work array
										}else{
											$SelectQuery2  = "SELECT * FROM works WHERE globid = '$VouchList->globid'";
											$SelectResult2 = mysqli_query($dbConn, $SelectQuery2);
											if($SelectResult2 == true){
												if(mysqli_num_rows($SelectResult2) > 0){
													$WorkList = mysqli_fetch_assoc($SelectResult2);
													if($WorkList['ccno'] != 0){
														$WorkList['ccno_wono'] = "CCNO : ".$WorkList['ccno']." / W.O. : ".$WorkList['wo_no'];
													}else{
														$WorkList['ccno_wono'] = "";
													}
													if($BudRupeesIn == "L"){
														//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 100000),2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}else if($BudRupeesIn == "C"){
														//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 10000000),2);
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}else{
														$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
													}
													foreach($MonthArr as $key => $Value){
														$WorkList[$key] = 0;
													}
													$WorksArr[$VouchList->globid] = $WorkList;
												}
											}
										}
									}
									
									
									
									$Month = strtolower(date("M", strtotime($VouchList->vr_dt)));
									if(isset($WorkList[$Month])){
										$WorkList[$Month] = $WorkList[$Month] + $VouchList->vr_amt;
									}else{
										$WorkList[$Month] = $VouchList->vr_amt;
									}
									$HoaWorkArr[$VouchList->globid] = $WorkList;
									
								}
							}
						}
						if(count($HoaWorkArr)>0){
							foreach($HoaWorkArr as $HoaWorkKey => $HoaWorkValue){
								$Rows = array();
								$WorkQtr1Amt = 0; //$WorkQtr2Amt = 0; $WorkQtr3Amt = 0; $WorkQtr4Amt = 0;
								$Rows = $HoaWorkValue;
								/*foreach($MonthArr as $key => $Value){
									if($BudRupeesIn == "L"){
										$Rows[$key] = round(($Rows[$key] / 100000),2);
									}else if($BudRupeesIn == "C"){
										$Rows[$key] = round(($Rows[$key] / 10000000),2);
									}else{
										$Rows[$key] = round($Rows[$key],2);
									}
									if(($Rows[$key] == NULL)||($Rows[$key] == 0)){
										//$Rows[$key] = 0;
									}
								}*/
								$Rows['hoa_no'] = $Hoa;
								$Rows['total_prev_fy'] = '';
								$Rows['total_curr_fy'] = '';
								$Rows['total_exp_upto_dt'] = '';
								$OutputArray[] = $Rows;
							}
						}
					}
				}
			}
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "EXPPE":
			$FromDate 	= dt_format($_POST['FromDate']);
			$ToDate 	= dt_format($_POST['ToDate']);
			$WhereClause = "";
			$FDateStr 	= explode("-",$FromDate);
			$EDateStr 	= explode("-",$ToDate);
			
			$StartYear 	= $FDateStr[0];
			$StartMonth = $FDateStr[1];
			if($StartMonth > 3){
				$StartPeriodYr = $StartYear;
			}else{
				$StartPeriodYr = $StartYear - 1;
			}
			
			$EndYear 	= $EDateStr[0];
			$EndMonth 	= $EDateStr[1];
			if($EndMonth > 3){
				$EndPeriodYr = $EndYear+1;
			}else{
				$EndPeriodYr = $EndYear;
			}
			$FinYearWhereClause = ""; $HoaArr = array();
			for($i=$StartPeriodYr; $i<$EndPeriodYr; $i++){
				$SYear = $i; $YYear = $i+1;
				$FinYearTemp = $SYear."-".$YYear;
				if($BudHoa == "ALL"){
					$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no from hoa_master WHERE fin_year = '$FinYearTemp'"; 
				}else{
					$SelectHoaQuery = "select distinct new_hoa_no,'' as old_hoa_no from hoa_master WHERE fin_year = '$FinYearTemp' AND (old_hoa_no = '$BudHoa' OR new_hoa_no = '$BudHoa')"; 
				}
				$SelectHoaSql = mysqli_query($dbConn, $SelectHoaQuery);
				if($SelectHoaSql == true){
					if(mysqli_num_rows($SelectHoaSql)>0){
						while($HoaList = mysqli_fetch_object($SelectHoaSql)){
							$OldHoa = $HoaList->old_hoa_no;
							$NewHoa = $HoaList->new_hoa_no;
							$HoaTemp = ($NewHoa != '') ? $NewHoa : $OldHoa;
							if(in_array($Hoa, $HoaArr)){
								
							}else{
								array_push($HoaArr,$HoaTemp);
							}
						}
					}
				}
			}
			//echo $FinYearWhereClause;
			//echo $StartPeriodYr." - ".$EndPeriodYr;
			//print_r($HoaArr);
			//exit;
			
			$RowCount = 0;
			
			$WhereClause = "";
			$WorksArr = array(); 
			if(count($HoaArr)>0){
				foreach($HoaArr as $HoaKey => $HoaValue){
					$Hoa = $HoaValue;
					$WorkList = array(); $HoaWorkArr = array();
					$MonthArr = array("jan"=>0, "feb"=>0, "mar"=>0, "apr"=>0, "may"=>0, "jun"=>0, "jul"=>0, "aug"=>0, "sep"=>0, "oct"=>0, "nov"=>0, "dec"=>0);
					$SelectVouchQuery1 = "SELECT * FROM voucher_upt WHERE (hoa = '$Hoa' OR new_hoa = '$Hoa') AND vr_dt >= '$FromDate' AND vr_dt <= '$ToDate'".$WhereClause.$GlobWhereClause;
					$SelectVouchSql1 = mysqli_query($dbConn, $SelectVouchQuery1);
					if($SelectVouchSql1 == true){
						if(mysqli_num_rows($SelectVouchSql1)>0){
							while($VouchList = mysqli_fetch_object($SelectVouchSql1)){
								
								if(isset($HoaWorkArr[$VouchList->globid])){
									$WorkList = $HoaWorkArr[$VouchList->globid]; // Work exist in current HOA array
								}else{
									if(isset($WorksArr[$VouchList->globid])){
										$WorkList = $WorksArr[$VouchList->globid]; // Work exist in current global work array
									}else{
										$SelectQuery2  = "SELECT * FROM works WHERE globid = '$VouchList->globid'";
										$SelectResult2 = mysqli_query($dbConn, $SelectQuery2);
										if($SelectResult2 == true){
											if(mysqli_num_rows($SelectResult2) > 0){
												$WorkList = mysqli_fetch_assoc($SelectResult2);
												if($WorkList['ccno'] != 0){
													$WorkList['ccno_wono'] = "CCNO : ".$WorkList['ccno']." / W.O. : ".$WorkList['wo_no'];
												}else{
													$WorkList['ccno_wono'] = "";
												}
												if($BudRupeesIn == "L"){
													//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 100000),2);
													$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
												}else if($BudRupeesIn == "C"){
													//$WorkList['wo_amount'] = round(($WorkList['wo_amount'] / 10000000),2);
													$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
												}else{
													$WorkList['wo_amount'] = round($WorkList['wo_amount'],2);
												}
												$WorksArr[$VouchList->globid] = $WorkList;
											}
										}
									}
								}
								
								
								
								$Month = date("n", strtotime($VouchList->vr_dt));
								if(isset($WorkList['month'])){
									$WorkList['month'] = $WorkList['month'] + $VouchList->vr_amt;
								}else{
									$WorkList['month'] = $VouchList->vr_amt;
								}
								$HoaWorkArr[$VouchList->globid] = $WorkList;
								
							}
						}
					}
					if(count($HoaWorkArr)>0){
						foreach($HoaWorkArr as $HoaWorkKey => $HoaWorkValue){
							$Rows = array();
							$WorkQtr1Amt = 0; //$WorkQtr2Amt = 0; $WorkQtr3Amt = 0; $WorkQtr4Amt = 0;
							$Rows = $HoaWorkValue;
							/*if($BudRupeesIn == "L"){
								$Rows['month'] = round(($Rows['month'] / 100000),2);
							}else if($BudRupeesIn == "C"){
								$Rows['month'] = round(($Rows['month'] / 10000000),2);
							}else{
								$Rows['month'] = round($Rows['month'],2);
							}
							if(($Rows['month'] == NULL)||($Rows['month'] == 0)){
								//$Rows[$key] = 0;
							}*/
							$Rows['hoa_no'] = $Hoa;
							$Rows['total_prev_fy'] = '';
							$Rows['total_curr_fy'] = '';
							$Rows['total_exp_upto_dt'] = '';
							$OutputArray[] = $Rows;
						}
					}
				}
			}
			
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "FPPM":
			$ExpFinYear = explode("-",$BudFinYear);
			$FinFirstYear = $ExpFinYear[0];
			$FinLastYear  = $ExpFinYear[1];
			$FinLastYearDate = $FinLastYear."-03-31";
			$RowCount = 0;
			$ProSanctAmt = 0; $TotalActAmt = 0; $TotalCommitAmt = 0; $ActualExpAmt = 0; $FinProgPerc = 0; $PhyProgAmt = 0;
			
			$SelectQuery1 	= "SELECT * FROM project_proposal";
			$SelectSql1 	= mysqli_query($dbConn, $SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					$List1 = mysqli_fetch_object($SelectSql1);
					$ProSanctAmt = $List1->proj_prop_amt;
				}
			}
			
			//$SelectQuery2 	= "SELECT nit_amount, nit_date, wo_amount, wo_date FROM budget_action_taken WHERE ((wo_date = '0000-00-00' AND nit_date <= '$FinLastYearDate') OR (wo_date != '0000-00-00' AND wo_date <= '$FinLastYearDate'))";
			$SelectQuery2 	= "SELECT tr_amount, wo_amount, wo_date FROM works";// WHERE ((wo_date = '0000-00-00' AND ts_date <= '$FinLastYearDate') OR (wo_date != '0000-00-00' AND wo_date <= '$FinLastYearDate'))";
			$SelectSql2 	= mysqli_query($dbConn, $SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					while($List2 = mysqli_fetch_object($SelectSql2)){
						$NitAmount 	= $List2->tr_amount;
						//0$NitDate 	= $List2->nit_date;
						$WoAmount 	= $List2->wo_amount;
						$WoDate 	= $List2->wo_date;
						//if($WoDate != '0000-00-00'){
						if(($WoAmount != '0')&&($WoAmount != 0)&&($WoAmount != '')&&($WoAmount != NULL)){
							$TotalActAmt = $TotalActAmt + $WoAmount;
							$TotalCommitAmt = $TotalCommitAmt + $WoAmount;
						}else{
							$TotalActAmt = $TotalActAmt + $NitAmount;
						}
					}
				}
			}
			
			$SelectQuery3 	= "SELECT SUM(vr_amt) as expAmt FROM voucher_upt";// WHERE vr_dt <= '$FinLastYearDate'";
			//$SelectQuery3 	= "SELECT SUM(vr_amt) as expAmt FROM voucher_upt WHERE vr_dt <= '2023-02-28'";
			$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
			if($SelectSql3 == true){
				if(mysqli_num_rows($SelectSql3)>0){
					$List3 = mysqli_fetch_object($SelectSql3);
					$ActualExpAmt = $List3->expAmt;
					/*if($BudRupeesIn == "L"){
						$ActualExpAmt = round(($ActualExpAmt / 100000),2);
					}else if($BudRupeesIn == "C"){
						$ActualExpAmt = round(($ActualExpAmt / 10000000),2);
					}*/
				}
			}
			$FinProgPerc = round(($ActualExpAmt * 100 / $ProSanctAmt),2);
			//echo $ActualExpAmt."/".$ProSanctAmt." = ".$FinProgPerc;exit;
			$PhyProgPerc = (($ActualExpAmt / $ProSanctAmt) + ((($TotalCommitAmt - $ActualExpAmt)/$ProSanctAmt) * 0.1) + ((($TotalActAmt - $TotalCommitAmt)/$ProSanctAmt) * 0.05));
			$PhyProgPerc = round(($PhyProgPerc * 100),2);
			$OutputArray = array("ProSanctAmt"=>$ProSanctAmt,"TotalActAmt"=>$TotalActAmt,"TotalCommitAmt"=>$TotalCommitAmt,"ActualExpAmt"=>$ActualExpAmt,"FinProgPerc"=>$FinProgPerc,"PhyProgPerc"=>$PhyProgPerc);
			//echo $ActualExpAmt;exit;
		break;
		
		case "EXPVOU":
			$FromDate 	= dt_format($_POST['FromDate']);
			$ToDate 	= dt_format($_POST['ToDate']);
			$RowCount = 0; 
			
			$SelectQuery1 = "SELECT * FROM voucher_upt WHERE vr_dt >= '$FromDate' AND vr_dt <= '$ToDate'";
			$SelectResult1 = mysqli_query($dbConn, $SelectQuery1);
			if($SelectResult1 == true){
				if(mysqli_num_rows($SelectResult1)>0){
					while($Rows = mysqli_fetch_assoc($SelectResult1)){
						$TotVrAmt = $TotVrAmt + $VouchList3->vr_amt;
						$GlobId  = $Rows['globid'];
						$SelectQuery3 	= "SELECT * FROM works WHERE globid = '$GlobId'";
						$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
						if($SelectSql3 == true){
							if(mysqli_num_rows($SelectSql3)>0){
								$List3 		= mysqli_fetch_object($SelectSql3);
								$WorkName 	= $List3->work_name; //echo $SelectQuery3;exit;
								$WoNo 		= $List3->wo_no;
								$WoAmt 		= $List3->wo_amount;
								$WoDate 	= dt_display($List3->wo_date);
								$CcNo 		= $List3->ccno;
								$ContName 	= $List3->name_contractor;
								
								$SelectQuery4 	= "SELECT * FROM sheet WHERE globid = '$GlobId'";
								$SelectSql4 	= mysqli_query($dbConn, $SelectQuery4);
								if($SelectSql4 == true){
									if(mysqli_num_rows($SelectSql4)>0){
										$List4 		= mysqli_fetch_object($SelectSql4);
										$ContName 	= $List4->name_contractor;
									}
								}
								
								$Rows['item'] 			= $WorkName;
								$Rows['ccno'] 			= $CcNo;
								$Rows['indentor'] 		= $ContName;
								
								
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
		
		case "BEPV":
			$FromDate 	= dt_format($_POST['FromDate']);
			$ToDate 	= dt_format($_POST['ToDate']);
			$RowCount = 0; 
			$ExpFinYear = explode("-",$BudFinYear);
			$FinFirstYear = $ExpFinYear[0];
			$FinLastYear  = $ExpFinYear[1];
			$FinYearStartDate = $FinFirstYear."-04-01";
			$FinYearCloseDate = $FinLastYear."-03-31";
			
			$TotalProposedAmt = 0; $TotalRevisedAmt = 0; $BeStatus = "";
			$SelectQuery1 	= "SELECT * FROM hoa_be WHERE fin_year = '$BudFinYear'";
			$SelectSql1 	= mysqli_query($dbConn, $SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					while($List1 = mysqli_fetch_object($SelectSql1)){
						if($List1->status == "P"){
							$TotalProposedAmt = $TotalProposedAmt + $List1->be_prop_amt;
						}
						if($List1->status == "A"){
							$TotalProposedAmt = $TotalProposedAmt + $List1->be_appr_amt; 
						}
						$BeStatus = $List1->status;
					}
				}
			}

			$TotalRevisedAmt = 0; $ReStatus = "";
			$SelectQuery2 	= "SELECT * FROM hoa_re WHERE fin_year = '$BudFinYear'";
			$SelectSql2 	= mysqli_query($dbConn, $SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					while($List2 = mysqli_fetch_object($SelectSql2)){
						if($List2->status == "P"){
							$TotalRevisedAmt = $TotalRevisedAmt + $List2->re_prop_amt;
						}
						if($List2->status == "A"){
							$TotalRevisedAmt = $TotalRevisedAmt + $List2->re_appr_amt;
						}
						$ReStatus = $List2->status;
					}
				}
			}
			$ActExpMonthArr = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0);
			$SelectQuery3 	= "SELECT vr_amt, MONTH(vr_dt) as vr_month FROM voucher_upt WHERE vr_dt >= '$FinYearStartDate' AND vr_dt <= '$FinYearCloseDate'";
			$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
			if($SelectSql3 == true){
				if(mysqli_num_rows($SelectSql3)>0){
					while($List3 = mysqli_fetch_object($SelectSql3)){
						$VouchAmt = $List3->vr_amt;
						$VouchMon = $List3->vr_month;
						$ActExpMonthArr[$VouchMon] = $ActExpMonthArr[$VouchMon] + $VouchAmt;
						/*if($BudRupeesIn == "L"){
							$ActualExpAmt = round(($ActualExpAmt / 100000),2);
						}else if($BudRupeesIn == "C"){
							$ActualExpAmt = round(($ActualExpAmt / 10000000),2);
						}*/
					}
				}
			}
			
			$PlanExpMonthArr = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0);
			$SelectQuery4 	= "SELECT * FROM budget_expenditure WHERE fin_year = '$BudFinYear'";
			$SelectSql4 	= mysqli_query($dbConn, $SelectQuery4);
			if($SelectSql4 == true){
				if(mysqli_num_rows($SelectSql4)>0){
					while($List4 = mysqli_fetch_object($SelectSql4)){
						$PlanExpMonthArr[1] = $PlanExpMonthArr[1] + $List4->jan; 	$PlanExpMonthArr[2] = $PlanExpMonthArr[2] + $List4->feb;
						$PlanExpMonthArr[3] = $PlanExpMonthArr[3] + $List4->march; 	$PlanExpMonthArr[4] = $PlanExpMonthArr[4] + $List4->april;
						$PlanExpMonthArr[5] = $PlanExpMonthArr[5] + $List4->may; 	$PlanExpMonthArr[6] = $PlanExpMonthArr[6] + $List4->june;
						$PlanExpMonthArr[7] = $PlanExpMonthArr[7] + $List4->july; 	$PlanExpMonthArr[8] = $PlanExpMonthArr[8] + $List4->aug;
						$PlanExpMonthArr[9] = $PlanExpMonthArr[9] + $List4->sep; 	$PlanExpMonthArr[10] = $PlanExpMonthArr[10] + $List4->oct;
						$PlanExpMonthArr[11] = $PlanExpMonthArr[11] + $List4->nov; 	$PlanExpMonthArr[12] = $PlanExpMonthArr[12] + $List4->dece;
						
					}
				}
			}
			/*if($BudRupeesIn == "L"){
				$ActExpMonthArr = array_map( function($val) { return $val / 100000; }, $ActExpMonthArr);
				$PlanExpMonthArr = array_map( function($val) { return $val / 100000; }, $PlanExpMonthArr);
				$TotalProposedAmt = round(($TotalProposedAmt / 100000),2);
				$TotalRevisedAmt = round(($TotalRevisedAmt / 100000),2);
			}else if($BudRupeesIn == "C"){
				$ActExpMonthArr = array_map( function($val) { return $val / 10000000; }, $ActExpMonthArr);
				$PlanExpMonthArr = array_map( function($val) { return $val / 10000000; }, $PlanExpMonthArr);
				$TotalProposedAmt = round(($TotalProposedAmt / 10000000),2);
				$TotalRevisedAmt = round(($TotalRevisedAmt / 10000000),2);
			}*/
			
			$OutputArray  = array('PropAmt'=>$TotalProposedAmt, "RevAmt"=>$TotalRevisedAmt, "CommitPlan"=>$PlanExpMonthArr, "ActPlan"=>$ActExpMonthArr);
			
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "OHCE":
			$FromDate 	= dt_format($_POST['FromDate']);
			$ToDate 	= dt_format($_POST['ToDate']);
			$RowCount = 0;
			/*$ExpFromDate = explode("-",$FromDate);
			$StYear = $ExpFromDate[0];
			$ExpToDate = explode("-",$ToDate);
			$EndYear = $ExpToDate[0]; 
			$CurrFy = $StYear."-".$EndYear;*/
			
			
			$FDateStr 	= explode("-",$FromDate);
			$EDateStr 	= explode("-",$ToDate);
			
			$StartYear 	= $FDateStr[0];
			$StartMonth = $FDateStr[1];
			if($StartMonth > 3){
				$StartPeriodYr = $StartYear;
			}else{
				$StartPeriodYr = $StartYear - 1;
			}
			
			$EndYear 	= $EDateStr[0];
			$EndMonth 	= $EDateStr[1];
			if($EndMonth > 3){
				$EndPeriodYr = $EndYear+1;
			}else{
				$EndPeriodYr = $EndYear;
			}
			$CurrFy = $StartPeriodYr."-".$EndPeriodYr;
			
			$SelectQuery = "SELECT a.*, b.* FROM hoa_master a INNER JOIN object_head b ON (a.obj_head_id = b.ohid) WHERE a.active = 1 AND b.active = 1 AND fin_year = '$CurrFy' ORDER BY new_hoa_no ASC";
			//echo $SelectQuery;exit;
			$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true ){
				while($List = mysqli_fetch_object($SelectSql)){
					$NewHoa 	 = $List->new_hoa_no; 
					$OldHoa 	 = $List->old_hoa_no; 
					$ObjHead = $List->obj_head;
					$SelectQuery3 	= "SELECT SUM(vr_amt) as expAmt FROM voucher_upt WHERE vr_dt >= '$FromDate' AND vr_dt <= '$ToDate' AND (hoa = '$OldHoa' OR new_hoa = '$NewHoa')";
					$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
					if($SelectSql3 == true){
						if(mysqli_num_rows($SelectSql3)>0){
							$List3 = mysqli_fetch_object($SelectSql3);
							$ActualExpAmt = $List3->expAmt;
							if(($ActualExpAmt == "")||($ActualExpAmt == NULL)){
								$ActualExpAmt = 0;
							}
							if($BudRupeesIn == "L"){
								$ActualExpAmt = round(($ActualExpAmt / 100000),2);
							}else if($BudRupeesIn == "C"){
								$ActualExpAmt = round(($ActualExpAmt / 10000000),2);
							}
						}
					}
					if($NewHoa != ""){ $Hoa = $NewHoa; }else{ $Hoa = $OldHoa; }
					$Rows = array();
					$Rows['object_head']	= $Hoa." - ".$ObjHead;
					$Rows['amount'] 		= $ActualExpAmt;
					$OutputArray[] 			= $Rows;
				}            
			}
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "OHCA":
			$RowCount = 0; 
			$FinStartDate	= $FinStartYear."-04-01";
			
			if($BudMonth > 3){
				$TempDate		= $FinStartYear."-".$BudMonth."-01";
				$Days 			= date('t',strtotime($TempDate));
				$FinEndDate		= $FinStartYear."-".$BudMonth."-".$Days;
			}else{
				$TempDate		= $FinEndYear."-".$BudMonth."-01";
				$Days 			= date('t',strtotime($TempDate));
				$FinEndDate		= $FinEndYear."-".$BudMonth."-".$Days;
			}
			
			$ExpFinYear = explode("-",$BudFinYear);
			$FinFirstYear = $ExpFinYear[0];
			$FinLastYear  = $ExpFinYear[1];
			$FinYearStartDate = $FinFirstYear."-04-01";
			$FinYearCloseDate = $FinLastYear."-03-31";
			
			$SelectQuery = "SELECT a.*, b.*, c.be_prop_amt as oh_be_prop_amt, c.be_appr_amt as oh_be_appr_amt, c.re_prop_amt as oh_re_prop_amt, c.re_appr_amt as oh_re_appr_amt 
							FROM hoa_master a INNER JOIN object_head b ON (a.obj_head_id = b.ohid) INNER JOIN project_proposal_obj_head c ON (c.obj_head_id = b.ohid)  
							WHERE a.fin_year = '$BudFinYear' AND a.active = 1 AND b.active = 1 
							ORDER BY new_hoa_no ASC";
			$SelectSql 	 = mysqli_query($dbConn,$SelectQuery); //echo $SelectQuery;exit;
			if($SelectSql == true ){
				while($List = mysqli_fetch_object($SelectSql)){
					$Hoa 	 = $List->new_hoa_no; 
					$ObjHead = $List->obj_head;
					$SancPropAmt = 0; $SancApprAmt = 0; $SancStatus = "";
					/*$SelectQuery1 	= "SELECT * FROM hoa_be WHERE fin_year = '$BudFinYear' AND hoa_no = '$Hoa' ORDER BY hbeid DESC LIMIT 1";
					$SelectSql1 	= mysqli_query($dbConn, $SelectQuery1);
					if($SelectSql1 == true){
						if(mysqli_num_rows($SelectSql1)>0){
							$List1 = mysqli_fetch_object($SelectSql1);
							$SancPropAmt = $List1->be_prop_amt;
							$SancApprAmt = $List1->be_appr_amt;
							$SancStatus  = $List1->status;
						}
					}*/
					$SancPropAmt = $List->oh_be_prop_amt;
					$SancApprAmt = $List->oh_be_appr_amt; 
					$SancStatus  = $List->status;
					$RevPropAmt = 0; $RevApprAmt = 0; $ReviStatus = "";
					/*$SelectQuery2 	= "SELECT * FROM hoa_re WHERE fin_year = '$BudFinYear' AND hoa_no = '$Hoa' ORDER BY hreid DESC LIMIT 1";
					$SelectSql2 	= mysqli_query($dbConn, $SelectQuery2);
					if($SelectSql2 == true){
						if(mysqli_num_rows($SelectSql2)>0){
							$List2 = mysqli_fetch_object($SelectSql2);
							$RevPropAmt = $List2->re_prop_amt;
							$RevApprAmt = $List2->re_appr_amt;
							$ReviStatus = $List2->status;
						}
					}*/
					$RevPropAmt = $List->oh_re_prop_amt;
					$RevApprAmt = $List->oh_re_appr_amt;
					$ReviStatus = $List->status;
					
					if($RevApprAmt != 0){ 
						$SanctAmt = $RevApprAmt; 
					}else if(($RevApprAmt == 0)||($RevApprAmt == "")){
						$SanctAmt = $SancApprAmt; 
					}else{
						$SanctAmt = $SancPropAmt; 
					}
					$TotalCommitAmt = 0;
					//echo $SanctAmt;exit;
					//echo $RevApprAmt;exit;
					//$SelectQuery3 	= "SELECT nit_amount, nit_date, wo_amount, wo_date FROM budget_action_taken WHERE ((wo_date = '0000-00-00' AND nit_date <= '$FinLastYearDate') OR (wo_date != '0000-00-00' AND wo_date <= '$FinLastYearDate'))";
					//$SelectQuery3 	= "SELECT SUM(wo_amount) as wo_amount FROM budget_action_taken WHERE hoa_no = '$Hoa' AND wo_date >= '$FinStartDate' AND wo_date <= '$FinEndDate' AND wo_date != '0000-00-00' AND wo_date IS NOT NULL";
					$SelectQuery3 	= "SELECT SUM(wo_amount) as wo_amount FROM works WHERE hoa_no = '$Hoa'";// AND wo_date <= '$FinYearCloseDate'";
					$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
					if($SelectSql3 == true){
						if(mysqli_num_rows($SelectSql3)>0){
							$List3 = mysqli_fetch_object($SelectSql3);
							$WoAmount 	= $List3->wo_amount;
							$TotalCommitAmt = $TotalCommitAmt + $WoAmount;
							/*if(($TotalCommitAmt == "")||($TotalCommitAmt == NULL)){
								$TotalCommitAmt = 0;
							}
							if($BudRupeesIn == "L"){
								$TotalCommitAmt = round(($TotalCommitAmt / 100000),2);
							}else if($BudRupeesIn == "C"){
								$TotalCommitAmt = round(($TotalCommitAmt / 10000000),2);
							}*/
						}
					}
					$SelectQuery3A 	= "SELECT SUM(vr_amt) as vr_amt FROM voucher_upt WHERE globid = '1046' AND (hoa = '$Hoa' OR new_hoa = '$Hoa')";// AND wo_date <= '$FinYearCloseDate'";
					$SelectSql3A 	= mysqli_query($dbConn, $SelectQuery3A);
					if($SelectSql3A == true){
						if(mysqli_num_rows($SelectSql3A)>0){
							$List3A = mysqli_fetch_object($SelectSql3A);
							$WoAmount 	= $List3A->wo_amount;
							$TotalCommitAmt = $TotalCommitAmt + $WoAmount;
							/*if(($TotalCommitAmt == "")||($TotalCommitAmt == NULL)){
								$TotalCommitAmt = 0;
							}
							if($BudRupeesIn == "L"){
								$TotalCommitAmt = round(($TotalCommitAmt / 100000),2);
							}else if($BudRupeesIn == "C"){
								$TotalCommitAmt = round(($TotalCommitAmt / 10000000),2);
							}*/
						}
					}
					//echo $SelectQuery3."<br/>";
					$ActualExpAmt = 0;
					//$SelectQuery3 	= "SELECT SUM(vr_amt) as expAmt FROM voucher_upt WHERE vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate' AND hoa = '$Hoa'";
					$SelectQuery3 	= "SELECT SUM(vr_amt) as expAmt FROM voucher_upt WHERE (hoa = '$Hoa' OR new_hoa = '$Hoa')";//vr_dt <= '$FinYearCloseDate' AND 
					$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
					if($SelectSql3 == true){
						if(mysqli_num_rows($SelectSql3)>0){
							$List3 = mysqli_fetch_object($SelectSql3);
							$ActualExpAmt = $List3->expAmt;
							/*if(($ActualExpAmt == "")||($ActualExpAmt == NULL)){
								$ActualExpAmt = 0;
							}
							if($BudRupeesIn == "L"){
								$ActualExpAmt = round(($ActualExpAmt / 100000),2);
							}else if($BudRupeesIn == "C"){
								$ActualExpAmt = round(($ActualExpAmt / 10000000),2);
							}*/
						}
					}
					//echo $SelectQuery3;exit;
					$BalanceCommit = $TotalCommitAmt - $ActualExpAmt;
					$BalanceToBeCommit = $SanctAmt - $TotalCommitAmt;
					$Rows = array();
					$Rows['object_head']		= $Hoa."<br/>".$ObjHead;
					$Rows['sanc_amount'] 		= $SanctAmt;
					$Rows['commited'] 			= $TotalCommitAmt;
					$Rows['exp_upto_amt'] 		= $ActualExpAmt;
					$Rows['bal_commit'] 		= $BalanceCommit;
					$Rows['bal_to_be_commit'] 	= $BalanceToBeCommit;
					$OutputArray[] 				= $Rows;
				}            
			}
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "OHBERE":
			$RowCount = 0; 
			$FinStartDate	= $FinStartYear."-04-01";
			
			if($BudMonth > 3){
				$TempDate		= $FinStartYear."-".$BudMonth."-01";
				$Days 			= date('t',strtotime($TempDate));
				$FinEndDate		= $FinStartYear."-".$BudMonth."-".$Days;
			}else{
				$TempDate		= $FinEndYear."-".$BudMonth."-01";
				$Days 			= date('t',strtotime($TempDate));
				$FinEndDate		= $FinEndYear."-".$BudMonth."-".$Days;
			}
			$SelectQuery = "SELECT a.*, b.* FROM hoa_master a INNER JOIN object_head b ON (a.obj_head_id = b.ohid) WHERE a.active = 1 AND b.active = 1 AND fin_year = '$BudFinYear' ORDER BY new_hoa_no ASC";
			$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true ){
				while($List = mysqli_fetch_object($SelectSql)){
					$Hoa 	 = $List->new_hoa_no; 
					$ObjHead = $List->obj_head;
					$SancPropAmt = 0; $SancApprAmt = 0; $SancStatus = "";
					$SelectQuery1 	= "SELECT * FROM hoa_be WHERE fin_year = '$BudFinYear' AND hoa_no = '$Hoa' ORDER BY hbeid DESC LIMIT 1";
					$SelectSql1 	= mysqli_query($dbConn, $SelectQuery1);
					if($SelectSql1 == true){
						if(mysqli_num_rows($SelectSql1)>0){
							$List1 = mysqli_fetch_object($SelectSql1);
							$SancPropAmt = $List1->be_prop_amt;
							$SancApprAmt = $List1->be_appr_amt;
							$SancStatus  = $List1->status;
						}
					}
					$RevPropAmt = 0; $RevApprAmt = 0; $ReviStatus = "";
					$SelectQuery2 	= "SELECT * FROM hoa_re WHERE fin_year = '$BudFinYear' AND hoa_no = '$Hoa' ORDER BY hreid DESC LIMIT 1";
					$SelectSql2 	= mysqli_query($dbConn, $SelectQuery2);
					if($SelectSql2 == true){
						if(mysqli_num_rows($SelectSql2)>0){
							$List2 = mysqli_fetch_object($SelectSql2);
							$RevPropAmt = $List2->re_prop_amt;
							$RevApprAmt = $List2->re_appr_amt;
							$ReviStatus = $List2->status;
						}
					}
					if($ReviStatus == "A"){
						$SanctAmt = $RevApprAmt;
					}else if(($ReviStatus == "P")&&($SancStatus == "A")){
						$SanctAmt = $SancApprAmt;
					}else{
						$SanctAmt = $SancPropAmt;
					}
					$ActualExpAmt = 0;
					$SelectQuery3 	= "SELECT SUM(vr_amt) as expAmt FROM voucher_upt WHERE vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate' AND hoa = '$Hoa'";
					$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
					if($SelectSql3 == true){
						if(mysqli_num_rows($SelectSql3)>0){
							$List3 = mysqli_fetch_object($SelectSql3);
							$ActualExpAmt = $List3->expAmt;
							if(($ActualExpAmt == "")||($ActualExpAmt == NULL)){
								$ActualExpAmt = 0;
							}
							if($BudRupeesIn == "L"){
								$ActualExpAmt = round(($ActualExpAmt / 100000),2);
							}else if($BudRupeesIn == "C"){
								$ActualExpAmt = round(($ActualExpAmt / 10000000),2);
							}
						}
					}
					$Rows = array();
					$Rows['object_head']	= $Hoa." - ".$ObjHead;
					$Rows['be_proposed'] 	= $SancPropAmt;
					$Rows['be_approved'] 	= $SancApprAmt;
					$Rows['re_proposed'] 	= $RevPropAmt;
					$Rows['re_approved'] 	= $RevApprAmt;
					$Rows['act_emp_amt'] 	= $ActualExpAmt;
					$OutputArray[] 			= $Rows;
				}            
			}
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "BECFY":
			$RowCount = 0; 
			$FinStartDate	= $FinStartYear."-04-01";
			
			$CurrYear = date('Y');
			$NextYear = date('Y', strtotime('+1 year'));
			$PrevYear = date('Y', strtotime('-1 year'));
			$CurrMonth = date('n');
			if($CurrMonth > 3){
				$BudFinYear = $CurrYear."-".$NextYear;
			}else{
				$BudFinYear = $PrevYear."-".$CurrYear;
			}
			
			/*if($BudMonth > 3){
				$TempDate		= $FinStartYear."-".$BudMonth."-01";
				$Days 			= date('t',strtotime($TempDate));
				$FinEndDate		= $FinStartYear."-".$BudMonth."-".$Days;
			}else{
				$TempDate		= $FinEndYear."-".$BudMonth."-01";
				$Days 			= date('t',strtotime($TempDate));
				$FinEndDate		= $FinEndYear."-".$BudMonth."-".$Days;
			}*/
			/*$SelectQuery1 	= "SELECT status, SUM(be_prop_amt) as be_prop_amt, SUM(be_appr_amt) as be_appr_amt FROM hoa_be WHERE fin_year = '$BudFinYear' GROUP BY status";
			$SelectSql1 	= mysqli_query($dbConn, $SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					$List1 = mysqli_fetch_object($SelectSql1);
					$BePropAmt = $List1->be_prop_amt;
					$BeApprAmt = $List1->be_appr_amt;
					$BeStatus  = $List1->status;
				}
			}*/
			$HoaArr = array();
			$TotalPropExp = 0; $TotalApprExp = 0; $TotalPropRevExp = 0; $TotalApprRevExp = 0;
			$SelectQuery1 	= "SELECT * FROM hoa_be WHERE fin_year = '$BudFinYear'";
			$SelectSql1 	= mysqli_query($dbConn, $SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					while($List1 = mysqli_fetch_object($SelectSql1)){
						$BePropAmt = $List1->be_prop_amt;
						$BeApprAmt = $List1->be_appr_amt;
						$BeStatus  = $List1->status;
						$TotalPropExp = $TotalPropExp + $BePropAmt;
						$TotalApprExp = $TotalApprExp + $BeApprAmt;
						array_push($HoaArr,$List1->hoa_no);
					}
				}
			}
			$RePropCnt = 0; $ReApprCnt = 0;
			$TotalPropRevExp = 0; $TotalApprRevExp = 0;
			if(count($HoaArr)>0){
				foreach($HoaArr as $HoaKey => $HoaValue){
					$SelectQuery2 	= "SELECT * FROM hoa_re WHERE fin_year = '$BudFinYear' AND hoa_no = '$HoaValue' ORDER BY hreid DESC LIMIT 1";
					$SelectSql2 	= mysqli_query($dbConn, $SelectQuery2);
					if($SelectSql2 == true){
						if(mysqli_num_rows($SelectSql2)>0){
							$List2 = mysqli_fetch_object($SelectSql2);
							$RePropAmt = $List2->re_prop_amt;
							$ReApprAmt = $List2->re_appr_amt;
							$ReStatus  = $List2->status;
							if($ReStatus == "P"){
								$RePropCnt++;
							}
							if($ReStatus == "A"){
								$ReApprCnt++;
							}
							$TotalPropRevExp = $TotalPropRevExp + $RePropAmt;
							$TotalApprRevExp = $TotalApprRevExp + $ReApprAmt;
						}
					}
				}
			}
			$Rows['be_proposed'] 	= $TotalPropExp;
			$Rows['be_approved'] 	= $TotalApprExp;
			$Rows['re_proposed'] 	= $TotalPropRevExp;
			$Rows['re_approved'] 	= $TotalApprRevExp;
			
			$AprAmt = 0; $MayAmt = 0; $JunAmt = 0; $JulAmt = 0; $AugAmt = 0; $SepAmt = 0; $OctAmt = 0; $NovAmt = 0; $DecAmt = 0; $JanAmt = 0; $FebAmt = 0; $MarAmt = 0;
			$SelectQuery3 	= "SELECT fin_year, SUM(april)as april, SUM(may) as may, SUM(june) as june, SUM(july) as july, SUM(aug) as aug, SUM(sep) as sep, 
							   SUM(oct) as oct, SUM(nov) as nov, SUM(dece) as dece, SUM(jan) as jan, SUM(feb) as feb, SUM(march) as march FROM budget_expenditure 
							   WHERE fin_year = '$BudFinYear' GROUP BY fin_year";
			$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
			if($SelectSql3 == true){
				if(mysqli_num_rows($SelectSql3)>0){
					$List3 = mysqli_fetch_object($SelectSql3);
					$AprAmt = $List3->april;
					$MayAmt = $List3->may;
					$JunAmt = $List3->june;
					$JulAmt = $List3->july;
					$AugAmt = $List3->aug;
					$SepAmt = $List3->sep;
					$OctAmt = $List3->oct;
					$NovAmt = $List3->nov;
					$DecAmt = $List3->dece;
					$JanAmt = $List3->jan;
					$FebAmt = $List3->feb;
					$MarAmt = $List3->march;
				}
			}
			$Rows['M1'] = $AprAmt;
			$Rows['M2'] = $MayAmt;
			$Rows['M3'] = $JunAmt;
			$Rows['M4'] = $JulAmt;
			$Rows['M5'] = $AugAmt;
			$Rows['M6'] = $SepAmt;
			$Rows['M7'] = $OctAmt;
			$Rows['M8'] = $NovAmt;
			$Rows['M9'] = $DecAmt;
			$Rows['M10'] = $JanAmt;
			$Rows['M11'] = $FebAmt;
			$Rows['M12'] = $MarAmt;
			$OutputArray[] 	= $Rows;
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "BEUCFY":
			$RowCount = 0; 
			$FinStartDate	= $FinStartYear."-04-01";
			
			$CurrYear = date('Y');
			$NextYear = date('Y', strtotime('+1 year'));
			$NextYear2 = date('Y', strtotime('+2 year'));
			$PrevYear = date('Y', strtotime('-1 year'));
			$CurrMonth = date('n');
			if($CurrMonth > 3){
				$BudFinYear = $NextYear."-".$NextYear2;
			}else{
				$BudFinYear = $CurrYear."-".$NextYear;
			}
			
			/*if($BudMonth > 3){
				$TempDate		= $FinStartYear."-".$BudMonth."-01";
				$Days 			= date('t',strtotime($TempDate));
				$FinEndDate		= $FinStartYear."-".$BudMonth."-".$Days;
			}else{
				$TempDate		= $FinEndYear."-".$BudMonth."-01";
				$Days 			= date('t',strtotime($TempDate));
				$FinEndDate		= $FinEndYear."-".$BudMonth."-".$Days;
			}*/
			/*$SelectQuery1 	= "SELECT status, SUM(be_prop_amt) as be_prop_amt, SUM(be_appr_amt) as be_appr_amt FROM hoa_be WHERE fin_year = '$BudFinYear' GROUP BY status";
			$SelectSql1 	= mysqli_query($dbConn, $SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					$List1 = mysqli_fetch_object($SelectSql1);
					$BePropAmt = $List1->be_prop_amt;
					$BeApprAmt = $List1->be_appr_amt;
					$BeStatus  = $List1->status;
				}
			}*/
			$HoaArr = array();
			$TotalPropExp = 0; $TotalApprExp = 0; $TotalPropRevExp = 0; $TotalApprRevExp = 0;
			$SelectQuery1 	= "SELECT * FROM hoa_be WHERE fin_year = '$BudFinYear'";
			$SelectSql1 	= mysqli_query($dbConn, $SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					while($List1 = mysqli_fetch_object($SelectSql1)){
						$BePropAmt = $List1->be_prop_amt;
						$BeApprAmt = $List1->be_appr_amt;
						$BeStatus  = $List1->status;
						$TotalPropExp = $TotalPropExp + $BePropAmt;
						$TotalApprExp = $TotalApprExp + $BeApprAmt;
						array_push($HoaArr,$List1->hoa_no);
					}
				}
			}
			$RePropCnt = 0; $ReApprCnt = 0;
			$TotalPropRevExp = 0; $TotalApprRevExp = 0;
			if(count($HoaArr)>0){
				foreach($HoaArr as $HoaKey => $HoaValue){
					$SelectQuery2 	= "SELECT * FROM hoa_re WHERE fin_year = '$BudFinYear' AND hoa_no = '$HoaValue' ORDER BY hreid DESC LIMIT 1";
					$SelectSql2 	= mysqli_query($dbConn, $SelectQuery2);
					if($SelectSql2 == true){
						if(mysqli_num_rows($SelectSql2)>0){
							$List2 = mysqli_fetch_object($SelectSql2);
							$RePropAmt = $List2->re_prop_amt;
							$ReApprAmt = $List2->re_appr_amt;
							$ReStatus  = $List2->status;
							if($ReStatus == "P"){
								$RePropCnt++;
							}
							if($ReStatus == "A"){
								$ReApprCnt++;
							}
							$TotalPropRevExp = $TotalPropRevExp + $RePropAmt;
							$TotalApprRevExp = $TotalApprRevExp + $ReApprAmt;
						}
					}
				}
			}
			$Rows['be_proposed'] 	= $TotalPropExp;
			$Rows['be_approved'] 	= $TotalApprExp;
			$Rows['re_proposed'] 	= $TotalPropRevExp;
			$Rows['re_approved'] 	= $TotalApprRevExp;
			
			$AprAmt = 0; $MayAmt = 0; $JunAmt = 0; $JulAmt = 0; $AugAmt = 0; $SepAmt = 0; $OctAmt = 0; $NovAmt = 0; $DecAmt = 0; $JanAmt = 0; $FebAmt = 0; $MarAmt = 0;
			$SelectQuery3 	= "SELECT fin_year, SUM(april)as april, SUM(may) as may, SUM(june) as june, SUM(july) as july, SUM(aug) as aug, SUM(sep) as sep, 
							   SUM(oct) as oct, SUM(nov) as nov, SUM(dece) as dece, SUM(jan) as jan, SUM(feb) as feb, SUM(march) as march FROM budget_expenditure 
							   WHERE fin_year = '$BudFinYear' GROUP BY fin_year";
			$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
			if($SelectSql3 == true){
				if(mysqli_num_rows($SelectSql3)>0){
					$List3 = mysqli_fetch_object($SelectSql3);
					$AprAmt = $List3->april;
					$MayAmt = $List3->may;
					$JunAmt = $List3->june;
					$JulAmt = $List3->july;
					$AugAmt = $List3->aug;
					$SepAmt = $List3->sep;
					$OctAmt = $List3->oct;
					$NovAmt = $List3->nov;
					$DecAmt = $List3->dece;
					$JanAmt = $List3->jan;
					$FebAmt = $List3->feb;
					$MarAmt = $List3->march;
				}
			}
			$Rows['M1'] = $AprAmt;
			$Rows['M2'] = $MayAmt;
			$Rows['M3'] = $JunAmt;
			$Rows['M4'] = $JulAmt;
			$Rows['M5'] = $AugAmt;
			$Rows['M6'] = $SepAmt;
			$Rows['M7'] = $OctAmt;
			$Rows['M8'] = $NovAmt;
			$Rows['M9'] = $DecAmt;
			$Rows['M10'] = $JanAmt;
			$Rows['M11'] = $FebAmt;
			$Rows['M12'] = $MarAmt;
			$OutputArray[] 	= $Rows;
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "DICE":
			$FromDate 	= dt_format($_POST['FromDate']);
			$ToDate 	= dt_format($_POST['ToDate']);
			$RowCount = 0; 
			
			$SelectQuery = "SELECT * FROM discipline WHERE active = 1 ORDER BY disciplineid ASC";
			$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
			if($SelectSql == true ){
				while($List = mysqli_fetch_object($SelectSql)){
					$DiscipCode 	= $List->discipline_code; 
					$DiscipName 	= $List->discipline_name;
					$SelectQuery3 	= "SELECT SUM(vr_amt) as expAmt FROM voucher_upt WHERE vr_dt >= '$FromDate' AND vr_dt <= '$ToDate' AND grp_div_sec = '$DiscipCode'";
					$SelectSql3 	= mysqli_query($dbConn, $SelectQuery3);
					if($SelectSql3 == true){
						if(mysqli_num_rows($SelectSql3)>0){
							$List3 = mysqli_fetch_object($SelectSql3);
							$ActualExpAmt = $List3->expAmt;
							if(($ActualExpAmt == "")||($ActualExpAmt == NULL)){
								$ActualExpAmt = 0;
							}
							if($BudRupeesIn == "L"){
								$ActualExpAmt = round(($ActualExpAmt / 100000),2);
							}else if($BudRupeesIn == "C"){
								$ActualExpAmt = round(($ActualExpAmt / 10000000),2);
							}
						}
					}
					$Rows = array();
					$Rows['object_head']	= $DiscipName;
					$Rows['amount'] 		= $ActualExpAmt;
					$OutputArray[] 			= $Rows;
				}            
			}
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "MODMIS":
			$BudUnit 		= $_POST['BudUnit'];
			$BudFinYear 	= $_POST['BudFinYear'];
			$BudDiscipline 	= $_POST['BudDiscipline'];
			$BudHoa 		= $_POST['BudHoa'];
			$BudMode 		= $_POST['BudMode'];
			$BudMonth 		= $_POST['BudMonth'];
			$BudGlobId 		= $_POST['BudGlobId'];
			$BudView 		= $_POST['BudView'];
			if($BudMode == "PE"){
				$FromDate 	= dt_format($_POST['FromDate']);
				$ToDate 	= dt_format($_POST['ToDate']);
			}else{
				$ExpBudFinYear	= explode("-",$BudFinYear);
				$FinStartYear	= $ExpBudFinYear[0];
				$FinEndYear		= $ExpBudFinYear[1];
				$FromDate		= $FinStartYear."-04-01";
				$ToDate			= $FinEndYear."-03-31";
			}
			$ModalWhereClause = "";
			if(($BudUnit != "ALL")&&($BudUnit != "")){
				$ModalWhereClause .= " AND unitid = '$BudUnit'";
			}
			if(($BudDiscipline != "ALL")&&($BudDiscipline != "")){
				$ModalWhereClause .= " AND grp_div_sec = '$BudDiscipline'";
			}
			if(($BudHoa != "ALL")&&($BudHoa != "")){
				$ModalWhereClause .= " AND (hoa = '$BudHoa' OR new_hoa = '$BudHoa')";
			}
			$RowCount 		= 0; 
			if($BudMode == "PE"){
				$SelectQuery3 = "SELECT * FROM voucher_upt WHERE globid = '$BudGlobId' AND vr_dt >= '$FromDate' AND vr_dt <= '$ToDate'".$ModalWhereClause;
			}else{
				$SelectQuery3 = "SELECT * FROM voucher_upt WHERE globid = '$BudGlobId' AND MONTH(vr_dt) = '$BudMonth' AND vr_dt >= '$FromDate' AND vr_dt <= '$ToDate'".$ModalWhereClause;
			}
			$SelectResult3 = mysqli_query($dbConn, $SelectQuery3);
			//echo $SelectQuery3;exit;
			if($SelectResult3 == true){
				if(mysqli_num_rows($SelectResult3)>0){
					while($VouchList3 = mysqli_fetch_object($SelectResult3)){
						$VouchList3->item_desc 	= $VouchList3->item;
						$VouchList3->vr_dt 		= dt_display($VouchList3->vr_dt);
						$VouchList3->hoa 		= $BudHoa;
						$OutputArray[] 			= $VouchList3;
					}
				}
			}
			if($RowCount == 0){
				$IsAvailable = false;
			}
		break;
		
		case "DISMO":
			$RowCount = 0;
			
			if($BudDiscipline == "ALL"){
				$HoaArr = array(); $HoaCount = 0;
				$SelectHoaQuery = "SELECT * FROM discipline WHERE active = 1 ORDER BY disciplineid ASC";
				$SelectHoaSql = mysqli_query($dbConn, $SelectHoaQuery);
				if($SelectHoaSql == true){
					if(mysqli_num_rows($SelectHoaSql)>0){
						while($HoaList = mysqli_fetch_object($SelectHoaSql)){
							array_push($HoaArr,$HoaList->discipline_code);
						}
						if(count($HoaArr)>0){
							$HoaCount = 1;
						}
					}
				}
				//print_r($HoaArr);exit;
				if($HoaCount == 1){
					foreach($HoaArr as $Key => $HoaValue){ 
						$SelectCCNoQuery = "SELECT DISTINCT ccno FROM voucher_upt WHERE vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate' AND MONTH(vr_dt) = '$BudMonth' AND grp_div_sec = '$HoaValue'".$GlobWhereClause;
						$SelectQuery2 = "SELECT * FROM works WHERE (ccno = '0' OR ccno IN ($SelectCCNoQuery))";
						//echo $SelectQuery2."<br/>";
						$SelectResult2 = mysqli_query($dbConn, $SelectQuery2);
						if($SelectResult2 == true){
							if(mysqli_num_rows($SelectResult2)>0){
								$RowCount = 1;
								while($Rows = mysqli_fetch_assoc($SelectResult2)){
									$GlobId = $Rows['globid']; 
									$Rows['ccno_wono'] = "CCNO : ".$Rows['ccno']." / W.O. : ".$Rows['wo_no'];
									$MonthArr = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0);
									$SelectQuery3 = "SELECT * FROM voucher_upt WHERE globid = '$GlobId' AND MONTH(vr_dt) = '$BudMonth' AND grp_div_sec = '$HoaValue' AND vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate'";
									$SelectResult3 = mysqli_query($dbConn, $SelectQuery3);
									if($SelectResult3 == true){
										if(mysqli_num_rows($SelectResult3)>0){
											while($VouchList3 = mysqli_fetch_object($SelectResult3)){
												$Month = date("n", strtotime($VouchList3->vr_dt));
												$MonthArr[$Month] = $MonthArr[$Month] + $VouchList3->vr_amt;
												if(($Rows['ccno'] == "")||($Rows['ccno'] == '0')){
													$VouchData[] = $VouchList3;
												}
											}
										}
									}
									if($BudRupeesIn == "L"){
										//$Rows['wo_amount'] = round(($Rows['wo_amount'] / 100000),2);
										$Rows['wo_amount'] = round($Rows['wo_amount'],2);
										//$Rows['month'] = round(($MonthArr[$BudMonth] / 100000),2);
										$Rows['month'] = round($MonthArr[$BudMonth],2);
									}else if($BudRupeesIn == "C"){
										//$Rows['wo_amount'] = round(($Rows['wo_amount'] / 10000000),2);
										$Rows['wo_amount'] = round($Rows['wo_amount'],2);
										//$Rows['month'] = round(($MonthArr[$BudMonth] / 10000000),2);
										$Rows['month'] = round($MonthArr[$BudMonth],2);
									}else{
										$Rows['wo_amount'] = round($Rows['wo_amount'],2);
										$Rows['month'] = round($MonthArr[$BudMonth],2);
									}
									/*if(($Rows['ccno'] == "")||($Rows['ccno'] == '0')){
										if($Rows['month'] != 0){
											$Rows['month'] = "<a href = '#'><u>".$Rows['month']."</u></a>";
										}
									}*/
									$Rows['total_prev_fy'] = '';
									$Rows['total_curr_fy'] = '';
									$Rows['total_exp_upto_dt'] = '';
									$Rows['grp_div_sec'] = $HoaValue;
									$Rows['vouch_data'] = $VouchData;
									$OutputArray[] = $Rows;
									//print_r($OutputArray);
									//echo "</br>";
									//echo "HI</br>";
								}
							}
						}
					}
					//print_r($OutputArray);
					//exit;
				}
			}else{
				$SelectCCNoQuery = "SELECT DISTINCT ccno FROM voucher_upt WHERE vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate' AND MONTH(vr_dt) = '$BudMonth'".$GlobWhereClause;
				$SelectQuery2 = "SELECT * FROM works WHERE (ccno = '0' OR ccno IN ($SelectCCNoQuery))";
				$SelectResult2 = mysqli_query($dbConn, $SelectQuery2);
				if($SelectResult2 == true){
					if(mysqli_num_rows($SelectResult2)>0){
						$RowCount = 1;
						while($Rows = mysqli_fetch_assoc($SelectResult2)){
							$GlobId = $Rows['globid']; 
							$Rows['ccno_wono'] = "CCNO : ".$Rows['ccno']." / W.O. : ".$Rows['wo_no'];
							$MonthArr = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0);
							$SelectQuery3 = "SELECT * FROM voucher_upt WHERE globid = '$GlobId' AND MONTH(vr_dt) = '$BudMonth' AND vr_dt >= '$FinStartDate' AND vr_dt <= '$FinEndDate'".$GlobWhereClause;
							$SelectResult3 = mysqli_query($dbConn, $SelectQuery3);
							if($SelectResult3 == true){
								if(mysqli_num_rows($SelectResult3)>0){
									while($VouchList3 = mysqli_fetch_object($SelectResult3)){
										$Month = date("n", strtotime($VouchList3->vr_dt));
										$MonthArr[$Month] = $MonthArr[$Month] + $VouchList3->vr_amt;
									}
								}
							}
							if($BudRupeesIn == "L"){
								//$Rows['wo_amount'] = round(($Rows['wo_amount'] / 100000),2);
								//$Rows['month'] = round(($MonthArr[$BudMonth] / 100000),2);
								$Rows['wo_amount'] = round($Rows['wo_amount'],2);
								$Rows['month'] = round($MonthArr[$BudMonth],2);
							}else if($BudRupeesIn == "C"){
								//$Rows['wo_amount'] = round(($Rows['wo_amount'] / 10000000),2);
								//$Rows['month'] = round(($MonthArr[$BudMonth] / 10000000),2);
								$Rows['wo_amount'] = round($Rows['wo_amount'],2);
								$Rows['month'] = round($MonthArr[$BudMonth],2);
							}else{
								$Rows['wo_amount'] = round($Rows['wo_amount'],2);
								$Rows['month'] = round($MonthArr[$BudMonth],2);
							}
							$Rows['grp_div_sec'] = $BudDiscipline;
							$Rows['total_prev_fy'] = '';
							$Rows['total_curr_fy'] = '';
							$Rows['total_exp_upto_dt'] = '';
							$OutputArray[] = $Rows;
						}
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
    'valid' => $IsAvailable,'data'=>$OutputArray
));


?>