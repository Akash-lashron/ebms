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
$CCode = $_POST['ccno'];
$OutputArr 	= array(); 
$CheckVal 	= 0;
$SelectSheetQuery 	= "SELECT * FROM sheet WHERE computer_code_no = '$CCode'";
$SelectSheetQuerySql = mysqli_query($dbConn,$SelectSheetQuery);
if($SelectSheetQuerySql == true){
	if(mysqli_num_rows($SelectSheetQuerySql)>0){
		$List = mysqli_fetch_array($SelectSheetQuerySql);
		$CheckVal 		= 1;
		$WODate   		= $List['work_order_date'];
		$WODate1      	= dt_display($WODate);
		$AgreeDate  	= $List['agree_date'];
		$AgreeDate1   	= dt_display($AgreeDate);
		$CompletDate  	= $List['date_of_completion'];
		$CompletDate1 	= dt_display($CompletDate);
		$PgValiDate   	= $List['pg_validity'];
		$PgValiDate1  	= dt_display($PgValiDate);
		$PgDate        = $List['bg_date'];
		$PgDate1       = dt_display($PgDate);
		$PgExpDate     = $List['bg_exp_date'];
		$PgExpDate1    = dt_display($PgExpDate);
		$Pgamt1             				= $List['pg_amt'];
		$Pgamt              				= round(($Pgamt1),0);
		$List['work_order_date'] 		= $WODate1;
		$List['agree_date']           = $AgreeDate1;
		$List['date_of_completion']   = $CompletDate1;
		$List['pg_validity']          = $PgValiDate1;
		$List['bg_date']              = $PgDate1;
		$List['bg_exp_date']          = $PgExpDate1;
		$List['pg_amt']               = $Pgamt;
		$ContID  = $List['contid'];
		$GlobID	= $List['globid'];
		$SheetID = $List['sheetid'];
		$OutputArr['SheetData'] = $List;
	}
}else{
	$OutputArr['SheetData'] = null;
}
if($CheckVal == 0){
	$SelectWorkQuery 	= "SELECT * FROM works WHERE ccno = '$CCode'";
	$SelectWorkQuerySql	= mysqli_query($dbConn,$SelectWorkQuery);
	if($SelectWorkQuerySql == true){
		if(mysqli_num_rows($SelectWorkQuerySql)>0){
			$List1 = mysqli_fetch_array($SelectWorkQuerySql);
			$WODate   		= $List1['wo_date'];
			$WODate1      	= dt_display($WODate);
			// $AgreeDate  	= $List1['agree_date'];
			// $AgreeDate1   	= dt_display($AgreeDate);
			$CompletDate  	= $List1['sch_comp_date'];
			$CompletDate1 	= dt_display($CompletDate);
			/* $PgValiDate   	= $List1['pg_validity'];
			$PgValiDate1  	= dt_display($PgValiDate);
			$PgDate        = $List1['bg_date'];
			$PgDate1       = dt_display($PgDate);
			$PgExpDate     = $List1['bg_exp_date'];
			$PgExpDate1    = dt_display($PgExpDate);
			$Pgamt1             				= $List1['pg_amt'];
			$Pgamt              				= round(($Pgamt1),0); */
			$List1['wo_date'] = $WODate1;
			/* $List1['agree_date'] = $AgreeDate1;
			$List1['sch_comp_date'] = $CompletDate1;
			$List1['pg_validity']   = $PgValiDate1;
			$List1['bg_date']       = $PgDate1;
			$List1['bg_exp_date']   = $PgExpDate1;
			$List1['pg_amt']        = $Pgamt; */
			$ContID 	= $List1['contid'];
			$GlobID	= $List1['globid'];
			$SheetID = $List1['sheetid'];
			$OutputArr['WorkData'] = $List1;
				//$Result1[] = $List1;
		}
	}
}else{
	$OutputArr['WorkData'] = null;
}
//echo $SheetID;exit;
if($SheetID != null){ //echo 1;exit;
	$SelectMBHQuery = "SELECT mbheaderid FROM mbookheader WHERE sheetid = '$SheetID' LIMIT 1";
	$SelectMBHQuerySql = mysqli_query($dbConn,$SelectMBHQuery);
	if(mysqli_num_rows($SelectMBHQuerySql)>0){
		$OutputArr['WORKPROCESS'] = 1;
	}else{
		$OutputArr['WORKPROCESS'] = 0;
	}	
}else{
	$OutputArr['WORKPROCESS'] = 0;
}
if($ContID != null){
	$ContBankQuery = "SELECT * FROM contractor_bank_detail WHERE contid = '$ContID' ";	
	$SelectSql 	 	= mysqli_query($dbConn,$ContBankQuery);	
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			while($List = mysqli_fetch_array($SelectSql)){
				$ContBankID  = $List['cbdtid'];
				$BankAcc 	 = $List['bank_acc_no'];
				$BankName 	 = $List['bank_name'];
				$BankAddress = $List['branch_address'];
				$BankIfsc 	 = $List['ifsc_code'];
				$Result2[] 	 = $List;
			}
		}
	}
	$OutputArr['ContBankDet'] = $Result2;
}else{
	$OutputArr['ContBankDet'] = null;
}
if($GlobID != null){
	$PgdetailQuery = "SELECT * FROM bg_fdr_details WHERE globid = '$GlobID' ";	
	$SelectSql 	 	= mysqli_query($dbConn,$PgdetailQuery);	
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			while($List = mysqli_fetch_array($SelectSql)){
				$PgId       = $List['master_id'];
				$PgDTID     = $List['master_id'];
				$Pgtype     = $List['inst_type'];
				$BankAcc    = $List['bank_acc_no'];
				$BankName   = $List['inst_bank_name'];
				$BankSerial = $List['inst_serial_no'];
				$PgDate     = $List['inst_date'];
				$PgDate1    = dt_display($PgDate);
				$PgExpDate  = $List['inst_exp_date'];
				$PgExpDate1 = dt_display($PgExpDate);
				$Pgamt1     = $List['inst_amt'];
				$Pgamt      = round(($Pgamt1),0);
				$List['inst_date']     = $PgDate1;
				$List['inst_exp_date'] = $PgExpDate1;
				$List['inst_amt']      = $Pgamt;
				$Result3[] = $List;
			}
		}
	}
	$OutputArr['BgFdrDet'] = $Result3;
}else{
	$OutputArr['BgFdrDet'] = null;
}
$OutputArr['CheckVal'] = $CheckVal;
//$rows = array('row1'=>$Result1,'row2'=>$Result2,'row3'=>$Result3);
//print_r($Result3);exit;
echo json_encode($OutputArr);
/*	$SelectQuery 	= "SELECT works.*,sheet.*,staff.*,designation.designationname,staff_section.section_name, hoa.*,contractor.*,
loi_entry.* FROM works 
JOIN sheet ON works.sheetid = sheet.sheet_id
JOIN staff ON staff.staffid = sheet.assigned_staff
JOIN hoa ON works.hoaid = hoa.hoa_id
JOIN designation ON staff.designationid = designation.designationid
JOIN staff_section ON staff_section.sectionid = staff.sectionid
JOIN contractor ON works.contid = contractor.contid
JOIN loi_entry ON works.globid = loi_entry.globid
WHERE works.ccno = '$CCode'";	*/

?> 