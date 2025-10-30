<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/declaration.php';
require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');
//checkUser();
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
$userid 	= 	$_SESSION['userid'];
$staffid 	= 	$_SESSION['sid'];
$count = 0; $shtcnt = 0;
$TotalLines = 0; $InsertedLines = 0; $NotUploadedArr = array();
$UnitId 			= 	$_POST['cmb_unit'];
$ExcelSheetName 	= 	trim($_POST['txt_sheet_name']);
$ExcelEndRow 		= 	trim($_POST['txt_end_row']); 
$uploadfilename 	= 	$_FILES["file"]["name"];
$UpLoadedDataArr = array(); $NotUpLoadedDataArr = array(); $NewWorkArr = array();
function datevalidation($inputdate)
{
	$temp = 0; $error = "";
	//if(strstr($inputdate, '/'))
	if(preg_match('~/~', $inputdate))
	{
		$expdate1 	= 	explode('/',$inputdate);
		$temp  		= 	1;
		$operator 	= 	"sla";
	}
	//if(strstr($inputdate, '-'))
	else if(preg_match("#-#", $inputdate))
	{
		$expdate1 	= 	explode('-',$inputdate);
		$temp  		= 	1;
		$operator 	= 	"hif";
	}
	//if(strstr($inputdate, '.'))
	else if(preg_match("#.#", $inputdate))
	{
		$expdate1 	= 	explode('.',$inputdate);
		$temp  		= 	1;
		$operator 	= 	"dot";
		
	}
	if($temp == 1)
	{
		$datefield1 	= 	trim($expdate1[0]);
		$monthfield1 	= 	trim($expdate1[1]);
		$yearfield1 	= 	trim($expdate1[2]);
		$length1		=	strlen($datefield1);
		$length2		=	strlen($monthfield1);
		$length3		=	strlen($yearfield1);
		$count1 		= 	count($expdate1);
		if($count1 == 3)
		{
			if(($datefield1>31) || ($datefield1<1)) 
			{ 
				$error = "Invalid Date";//"Day value in Date field is Greater than 31."; 
			}
			else if(($monthfield1>12) || ($monthfield1<1)) 
			{ 
				$error = "Invalid Date";//"month value in Date field is Greater than 12."; 
			}
			else if(($length1>2) || ($length1<1)) 
			{
				$error = "Invalid Date";//"Day value in Date field is more than two digit.";
			}
			else if(($length2>2) || ($length2<1)) 
			{
				$error = "Invalid Date";//"month value in Date field is more than two digit.";
			}
			else if($length3 != 4) 
			{
				$error = "Invalid Date";//"Year value in Date field is not in YYYY format.";
			}
			else
			{
				if($length1 == 1){ $datefield1	=	"0".$datefield1; }
				if($length2 == 1){ $monthfield1	=	"0".$monthfield1; }
				$ddmmyyyy = $datefield1."/".$monthfield1."/".$yearfield1;
				$yyyymmdd = $yearfield1."-".$monthfield1."-".$datefield1;
			}
		}
		else
		{
			$error = "Invalid Date Format";//"Date format is invalid which should be ddmmyyyy.";
		}
	}
	//$temp1 = str_replace('-', '/', $inputdate);
	$returnStr = $ddmmyyyy."**".$yyyymmdd."**".$error."**".$count1;
	return $returnStr;
}
$HoaArr = array(); $VouchDataArr = array();
//$SelectHoaQuery1 = "select * from hoa_master where active = 1";
$SelectHoaQuery1 = "(SELECT old_hoa_no, new_hoa_no FROM hoa_master WHERE active = 1) UNION (SELECT old_hoa_no, new_hoa_no FROM hoa_detail WHERE active = 1)";
$SelectHoaSql1 	 = mysqli_query($dbConn,$SelectHoaQuery1);
if($SelectHoaSql1 == true){
	if(mysqli_num_rows($SelectHoaSql1)>0){
		while($HoaList = mysqli_fetch_object($SelectHoaSql1)){
			$OldHoaNo = $HoaList->old_hoa_no;
			$NewHoaNo = $HoaList->new_hoa_no;
			//$HoaId = $HoaList->hoamast_id;
			$OldHoaNo = str_replace(' ','',$OldHoaNo);
			$NewHoaNo = str_replace(' ','',$NewHoaNo);
			if($OldHoaNo != ''){
				$HoaArr[$OldHoaNo] = $OldHoaNo;
			}
			if($NewHoaNo != ''){
				$HoaArr[$NewHoaNo] = $NewHoaNo;
			}
		}
	}
}
$DiscArr = array();
$SelectDiscipQuery1  = "select * from discipline where active = 1";
$SelectDiscipSql1 	 = mysqli_query($dbConn,$SelectDiscipQuery1);
if($SelectDiscipSql1 == true){
	if(mysqli_num_rows($SelectDiscipSql1)>0){
		while($DiscList = mysqli_fetch_object($SelectDiscipSql1)){
			$DiscipName = $DiscList->discipline_name;
			$DiscipId   = $DiscList->disciplineid;
			$DiscipCode = $DiscList->discipline_code;
			$DiscArr[$DiscipCode] = $DiscipId;
		}
	}
}
//print_r($HoaArr);exit;
if ($_FILES['file']['name'] != "") 
{
	$CurrentDtTime = date("Ymdhms");
	$target_dir 		= 	"Voucher/";
    $target_file 		= 	$target_dir . $CurrentDtTime."_".basename($_FILES["file"]["name"]);
    $currentfilename 	=	$CurrentDtTime."_".basename($_FILES["file"]["name"]);
    $checkupload 		= 	1;
    $imageFileType 		= 	pathinfo($target_file, PATHINFO_EXTENSION);
    if($_FILES["file"]["size"] > 500000){
       $msg 			= 	$msg . " Sorry, your file is too large." . "<BR>";
       $checkupload 	= 	0;
    }
    if(strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx"){
       $msg 			= 	$msg . " Sorry, only xls files are allowed." . "<BR>";
       $checkupload 	= 	0;
    }
    if ($checkupload == 0){
       $msg 			= 	$msg . " Sorry, your file was not uploaded." . "<BR>";
    } 
	else{
      	if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)){
            $checkupload 	= 	1;  //echo $target_file;exit;
        }else{
           $checkupload 	=	0;
           $msg 			= 	$msg .  "Sorry, there was an error uploading your file." . "<BR>";
        }
    }
} 
if($checkupload == 1) 
{
   	$Spreadsheet 	= new SpreadsheetReader("Voucher/" . $currentfilename, false, 'UTF-8');
	$Sheets 		= $Spreadsheet -> Sheets();
    foreach ($Sheets as $Index => $Name) 
	{ // Loop to get all sheets in a file.
		$Spreadsheet -> ChangeSheet($Index);
        foreach ($Spreadsheet as $Key => $Row) 
		{ // loop used to get each row of the sheet
			$errorflag 	= "";	 
			$deflag 	= "";	
			$ieflag 	= "";	
			$feflag 	= "";
			$idtemp		= 0;
			$sheetname 	= $Name;
			if($Key == $ExcelEndRow)
			{
				break 2;
			}
			if($ExcelSheetName != $sheetname)
			{
				break 1;
			}
			else
			{
				$shtcnt++;
				$steflag++;
			}
			
// ( A )******************************** Title Format check is starts Here *******************************//
			if($Key == 0)
			{ 
				$formaterror = 0;
				if(trim($Row[0]) == "") { $formaterror = 1; } 
				if(trim($Row[1]) == "") { $formaterror = 1; }
				if(trim($Row[2]) == "") { $formaterror = 1; }
				if(trim($Row[3]) == "") { $formaterror = 1; } 
				if(trim($Row[4]) == "") { $formaterror = 1; }
				if(trim($Row[5]) == "") { $formaterror = 1; }
				if(trim($Row[6]) == "") { $formaterror = 1; }
				if(trim($Row[7]) == "") { $formaterror = 1; }
				if(trim($Row[8]) == "") { $formaterror = 1; }
				if(trim($Row[9]) == "") { $formaterror = 1; }
				if(trim($Row[10]) == "") { $formaterror = 1; }
				if(trim($Row[11]) == "") { $formaterror = 1; }
				if(trim($Row[12]) == "") { $formaterror = 1; }
				if(trim($Row[13]) == "") { $formaterror = 1; }
				if(trim($Row[14]) == "") { $formaterror = 1; }
				if(trim($Row[15]) == "") { $formaterror = 1; }
				if(trim($Row[16]) == "") { $formaterror = 1; }
				if(trim($Row[17]) == "") { $formaterror = 1; }
				if(trim($Row[18]) == "") { $formaterror = 1; }
				if(trim($Row[19]) == "") { $formaterror = 1; }
				if(trim($Row[20]) == "") { $formaterror = 1; }
				if(trim($Row[21]) == "") { $formaterror = 1; }
			}
			//echo $target_file."<br/>";
			if($formaterror == 1)
			{
				$feflag = 1;
				if (file_exists($target_file)) 
				{ 
					unlink($target_file); 
				}
				break 2;
			}
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Title Format check is ends Here !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!//
			$patterns = array();
			$patterns[0] = '/"/';
			$patterns[1] = "/'/";
			$patterns[2] = '/°/';
			
			$replacements = array();
			$replacements[0] = '"';
			$replacements[1] = "\'";
			$replacements[2] = '°';
			
			if ($Key>0)
			{ 
				$TotalLines++;
				$FilePo 	= 	trim($Row[0]); //File/PO
				$Item 		= 	trim($Row[1]); //Item
				$ContName 	= 	trim($Row[2]); //FIRM Name
				$PoValue 	= 	trim($Row[3]); //PO-val[L]
				$VrNo 		= 	trim($Row[4]); //Vr. no
				$VrDate 	= 	trim($Row[5]); //Vr. Date
				$VrAmt		= 	trim($Row[6]); //VrAmt
				$PoRelDt 	= 	trim($Row[7]); //PO-Rel-dt.
				$PoWoCompDt = 	trim($Row[8]); //PO/WO Completion Date
				$FinalPayDt = 	trim($Row[9]); //Final Payment completed Date
				$WorkStatus = 	trim($Row[10]); //Status of completion (ON GOING/ COMPLETED)
				$OPin 		= 	trim($Row[11]); //O PIN
				$NPin 		= 	trim($Row[12]); //N PIN
				$CCno 		= 	trim($Row[13]); //CC NO
				//$Code 		= 	trim($Row[14]); //CODE
				$PaidAmt 	= 	trim($Row[14]); //Paid[L]
				$Hoa		= 	trim($Row[15]); //Head of Account
				$NewHoa 	= 	trim($Row[16]); //New HoA
				$Indentor 	= 	trim($Row[17]); //Indentor
				$GrpDivSec 	= 	trim($Row[18]); //GrpDivSec
				$PlantServ 	= 	trim($Row[19]); //Plant/Service
				$SancOmAct 	= 	trim($Row[20]); //Sanction OM activity Sl.No
				$SancOmNwAct= 	trim($Row[21]); //Sanction OM MW/ME Sl.No
				//$escaped = preg_replace(array('', '', ''), array('ddd', 'dddd', 'ddddd'), $description);    
				$Item 		= preg_replace($patterns, $replacements, $Item);
				$Item 		= str_replace("'", "'", $Item);
				$HoaWoSpace = str_replace(' ','',$Hoa);
				if(isset($HoaArr[$HoaWoSpace])){
					$HoaId 	= $HoaArr[$HoaWoSpace];
				}else{
					$HoaId  = 0;
				}
				$NewHoaWoSpace = str_replace(' ','',$NewHoa);
				if(isset($HoaArr[$NewHoaWoSpace])){
					$NewHoaId 	= $HoaArr[$NewHoaWoSpace];
				}else{
					$NewHoaId  = 0;
				}
				
				$GrpDivSec = str_replace(' ','',$GrpDivSec);
				$GrpDivSec = trim($GrpDivSec);
				if(isset($DiscArr[$GrpDivSec])){
					$DiscId  = $DiscArr[$GrpDivSec];
				}else{
					$DiscId  = 0;
				}
				
// ( B )***************************** First Row Format check is starts Here *****************************//					
				if($Key > 0)
				{
					//if(($FilePo == "") || ($VrNo == "") || ($VrDate == "") || ($VrAmt == ""))
					if(($Item == "") || ($VrNo == "") || ($VrDate == "") || ($VrAmt == ""))
					{
						$feflag = 2;
						if (file_exists($target_file)) 
						{ 
							unlink($target_file); 
						}
						break 2;
					}
				}
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!! First Row Format Format check is ends Here !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!//
				
// ( C )******************************** Date Format check is starts Here *******************************//	
				if($VrDate != "")
				{
					$returnStr1 	= 	datevalidation($VrDate);
					$expresult1 	= 	explode("**",$returnStr1);
					$ddmmyyyy  		= 	$expresult1[0];
					$yyyymmdd  		= 	$expresult1[1];
					$checkerror1  	= 	$expresult1[2];
					if($checkerror1 == 0)
					{
						$VDate 		= 	$yyyymmdd;
						$deflag 	= 	$checkerror1;
					}
					else
					{
						$VDate 		= 	NULL;
						$deflag 	= 	$checkerror1;
					}
				}
				if($PoRelDt != "")
				{
					$returnStr2 	= 	datevalidation($PoRelDt);
					$expresult2 	= 	explode("**",$returnStr2);
					$ddmmyyyy2  	= 	$expresult2[0];
					$yyyymmdd2  	= 	$expresult2[1];
					$checkerror2  	= 	$expresult2[2];
					if($checkerror2 == 0)
					{
						$PoDate 	= 	$yyyymmdd2;
						$deflag 	= 	$checkerror2;
					}
					else
					{
						$PoDate 	= 	NULL;
						$deflag 	= 	$checkerror2;
					}
				}
				if($PoWoCompDt != "")
				{
					$returnStr3 	= 	datevalidation($PoWoCompDt);
					$expresult3 	= 	explode("**",$returnStr2);
					$ddmmyyyy3  	= 	$expresult3[0];
					$yyyymmdd3  	= 	$expresult3[1];
					$checkerror3  	= 	$expresult3[2];
					if($checkerror3 == 0)
					{
						$WoCompDt 	= 	$yyyymmdd3;
						$deflag 	= 	$checkerror3;
					}
					else
					{
						$WoCompDt 	= 	NULL;
						$deflag 	= 	$checkerror3;
					}
				}
				if($FinalPayDt != "")
				{
					$returnStr4 	= 	datevalidation($FinalPayDt);
					$expresult4 	= 	explode("**",$returnStr4);
					$ddmmyyyy4  	= 	$expresult4[0];
					$yyyymmdd4  	= 	$expresult4[1];
					$checkerror4  	= 	$expresult4[2];
					if($checkerror4 == 0)
					{
						$LastPayDt 	= 	$yyyymmdd4;
						$deflag 	= 	$checkerror4;
					}
					else
					{
						$LastPayDt 	= 	NULL;
						$deflag 	= 	$checkerror4;
					}
				}
				$RemarksArr = array();
				$DataError = 0;
				$VrAmtStr = str_replace(',','',$VrAmt);
				$VrAmtStr = str_replace(' ','',$VrAmtStr);
				$VrDataStr = $UnitId."-".$VrNo."-".$VDate."-".$VrAmtStr;
				if(in_array($VrDataStr,$VouchDataArr)){
					$DataError = 1;
					$Remarks = ">> Voucher data already exists in Excel";
					array_push($RemarksArr,$Remarks);
				}
				$SelectDupQuery1 = "select vuid from voucher_upt where unitid = '$UnitId' and vr_no = '$VrNo' and vr_dt = '$VDate' and vr_amt = '$VrAmtStr'";
				$SelectDupSql1 	 = mysqli_query($dbConn,$SelectDupQuery1);
				if($SelectDupSql1 == true){
					if(mysqli_num_rows($SelectDupSql1)>0){
						$DataError = 1;
						$Remarks = ">> Voucher data already exists";
						array_push($RemarksArr,$Remarks);
					}
				}

				
				if($Item == ""){ //FRFCF
					array_push($RemarksArr,">> Item should not be blank");
					$DataError = 1;
				}
				if($VrNo == ""){ //FRFCF
					array_push($RemarksArr,">> Voucher no. should not be blank");
					$DataError = 1;
				}
				if($VDate == ""){ //FRFCF
					array_push($RemarksArr,">> Voucher date should not be blank");
					$DataError = 1;
				}
				if($VrAmt == ""){ //FRFCF
					array_push($RemarksArr,">> Voucher date should not be blank");
					$DataError = 1;
				}
				if($Hoa == ""){ //FRFCF
					array_push($RemarksArr,">> HOA should not be blank");
					$DataError = 1;
				}
				/*if($CCno == ""){ //FRFCF
					array_push($RemarksArr,">> CCNo. should not be blank");
					$DataError = 1;
				}*/
				if($HoaId == 0){ //FRFCF
					array_push($RemarksArr,">> HOA does not exist");
					$DataError = 1;
				}
				
				if($UnitId == 6){
					/*if($FilePo == ""){ 
						array_push($RemarksArr,">> Item should not be blank");
						$DataError = 1;
					}
					if(($PoValue == "")||($PoValue == 0)){
						array_push($RemarksArr,">> PO value should not be blank or 0");
						//echo "PO - ".$PoValue."<br/>";
						$DataError = 1;
					}
					if(($PoRelDt == "")&&($PoDate == NULL)){
						array_push($RemarksArr,">> Invalid PO Date");
						$DataError = 1;
					}*/
				}
				if(count($RemarksArr)>0){
					$RemarksStr = implode("\n",$RemarksArr);
				}else{
					$RemarksStr = "";
				}
				$Executed = 0;
				
				if($DataError == 0){
					if(($CCno == "")||($CCno == NULL)||($CCno == 0)){
						$MiscGlobId = $MiscGlobDeclarId;
					}else{
						$MiscGlobId = 0;
					}
					$InsertQuery = "insert into voucher_upt set globid = '$MiscGlobId', unitid = '$UnitId', disciplineid = '$DiscId', wo = '$FilePo', item = '$Item', name_contractor = '$ContName', contid = '', wo_amt = '$PoValue', vr_no = '$VrNo', vr_dt = '$VDate',
									vr_amt = '$VrAmt', wo_dt = '$PoDate', wo_comp_dt = '$WoCompDt', final_pay_dt = '$LastPayDt', work_status = '$WorkStatus', o_pin = '$OPin', n_pin = '$NPin', ccno = '$CCno', code= '$CCno', paid_amt = '$PaidAmt',
									hoa = '$Hoa', hoa_id = '$HoaId', new_hoa = '$NewHoa', new_hoa_id = '$NewHoaId', indentor = '$Indentor', grp_div_sec = '$GrpDivSec', 
									plant_serv = '$PlantServ', sanct_om_act_sno = '$SancOmAct', sanct_om_nwme_sno = '$SancOmNwAct', createdon = NOW(), staffid = '$staffid', 
									userid = '$userid', entry_flag = 'XL'";
					$InsertSql 	 = 	mysqli_query($dbConn,$InsertQuery);
					$VouchId	 =  mysqli_insert_id($dbConn);
					
					
					$VouchDataArr[] = $VrDataStr;
					if($InsertSql == true){
						$Executed = 1;
						$count++;
						$InsertedLines++;
						if(($CCno != "")&&($CCno != NULL)&&($CCno != 0)){
							$NewRow = 1; $WorkOrderAmt = 0; $WorkGlobId = 0;
							$SelectQuery = "select globid, ccno, wo_amount from works where ccno = '$CCno'";
							$SelectSql 	 = 	mysqli_query($dbConn,$SelectQuery);
							if($SelectSql == true){
								if(mysqli_num_rows($SelectSql)){
									$NewRow = 0;
									$WAList = mysqli_fetch_object($SelectSql);
									$WorkOrderAmt 	= $WAList->wo_amount;
									$WorkGlobId 	= $WAList->globid;
									$GlobId 		= $WorkGlobId;
								}
							}
							if($WorkOrderAmt == 0){
								if($NewRow == 0){
									//$UpdateQuery1 	= "update works set wo_amount = '$PoValue', pin_no = '$PinNo', hoa_no = '$Hoa' where globid = '$WorkGlobId'";
									//$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1);
								}
							}
							if($NewRow == 1){
								if($NPin != ""){
									$PinNo = $NPin;
								}else{
									$PinNo = $OPin;
								}
								$InsertQuery1 	= "insert into works set ccno = '$CCno', work_name = '$Item', wo_no = '$FilePo', wo_amount = '$PoValue', pin_no = '$PinNo', hoa_no = '$Hoa', active = 1";
								$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
								$NewGlobId		= mysqli_insert_id($dbConn);
								$GlobId 		= $NewGlobId;
								$InsertQuery2 	= "insert into budget_action_taken set globid = '$NewGlobId', ccno = '$CCno', work_name = '$Item', wo_no = '$FilePo', wo_date = '$PoDate', wo_amount = '$PoValue', pin_no = '$PinNo', hoa_no = '$Hoa', active = 1";
								$InsertSql2 	= mysqli_query($dbConn,$InsertQuery2);
							}else{
								$UpdateQuery1 	= "update works set wo_amount = '$PoValue' where globid = '$WorkGlobId'";
								$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1);
							}
							//07-12-2023
							$SelectQuery_vou = "select plant_service,discipline,sch_act,major_item from sheet where globid = '$GlobId' and discipline!='' ";
							$SelectSql_vou 	 = 	mysqli_query($dbConn,$SelectQuery_vou);
							if($SelectSql_vou == true){
								if(mysqli_num_rows($SelectSql_vou)){
									$NewRow = 0;
									$WAList_vou = mysqli_fetch_object($SelectSql_vou);
									$plant_service 	= $WAList_vou->plant_service;
									$discipline 	= $WAList_vou->discipline;
									$sch_act 	= $WAList_vou->sch_act;
									$major_item 	= $WAList_vou->major_item;
								}
							}
							$UpdateQuery2 	= "update voucher_upt set globid = '$GlobId', plant_serv = '$plant_service', sanct_om_act_sno = '$sch_act', sanct_om_nwme_sno = '$major_item', disciplineid = '$discipline' where vuid = '$VouchId'";
							
							$UpdateQuery2 	= "update voucher_upt set globid = '$GlobId' where vuid = '$VouchId'";							
							$UpdateSql2 	= mysqli_query($dbConn,$UpdateQuery2);
						}
					}
				}
				
				
				
				$DataArr = array();
				if($VDate != NULL){
					$VDateStr = dt_display($VDate);
				}else{
					$VDateStr = "";
				}
				if($PoDate != NULL){
					$PoDateStr = dt_display($PoDate);
				}else{
					$PoDateStr = "";
				}
				if($WoCompDt != NULL){
					$PoWoCompDtStr = dt_display($WoCompDt);
				}else{
					$PoWoCompDtStr = "";
				}
				if($LastPayDt != NULL){
					$FinalPayDtStr = dt_display($LastPayDt);
				}else{
					$FinalPayDtStr = "";
				}
				if($Executed == 0){
					array_push($NotUploadedArr,$Key+1);
					$DataArr['sno'] = $Key+1; 
					$DataArr['wo'] = $FilePo; 
					$DataArr['item'] = $Item; 
					$DataArr['name_contractor'] = $ContName; 
					$DataArr['wo_amt'] = $PoValue; 
					$DataArr['vr_no'] = $VrNo; 
					$DataArr['vr_dt'] = $VDateStr;
					$DataArr['vr_amt'] = $VrAmt; 
					$DataArr['wo_dt'] = $PoDateStr; 
					$DataArr['wo_comp_dt'] = $PoWoCompDtStr; 
					$DataArr['final_pay_dt'] = $FinalPayDtStr; 
					$DataArr['work_status'] = $WorkStatus; 
					$DataArr['o_pin'] = $OPin; 
					$DataArr['n_pin'] = $NPin; 
					$DataArr['ccno'] = $CCno; 
					//$DataArr['code'] = $Code; 
					$DataArr['paid_amt'] = $PaidAmt;
					$DataArr['hoa'] = $Hoa; 
					$DataArr['hoa_id'] = $HoaId; 
					$DataArr['new_hoa'] = $NewHoa; 
					$DataArr['new_hoa_id'] = $NewHoaId; 
					$DataArr['indentor'] = $Indentor; 
					$DataArr['grp_div_sec'] = $GrpDivSec; 
					$DataArr['plant_serv'] = $PlantServ; 
					$DataArr['sanct_om_act_sno'] = $SancOmAct;
					$DataArr['sanct_om_nwme_sno'] = $SancOmNwAct; 
					$DataArr['remarks'] = $RemarksStr; //$DataArr['data_error'] = $DataError;
					$NotUpLoadedDataArr[] = $DataArr;
					$NotUploaded++;
				}
			}
        } 
    } 
	if($shtcnt == 0)
	{
		if (file_exists($target_file)) 
		{ 
			unlink($target_file); 
		}
	}
} 
//echo $res."@@@@".$msg;
if($count >0){ $msg = "Voucher Data Uploaded Sucessfully"; }
if(count($NotUploadedArr)>0){ $NotUploaded = implode(",",$NotUploadedArr); }else{ $NotUploaded = ""; }
//print_r($SubDivIdList);
//$UpLoadedDataArrJson = json_encode($UpLoadedDataArr);
$OutputArr = array('0'=>$steflag,'1'=>$feflag,'2'=>$msg,'3'=>$TotalLines,'4'=>$InsertedLines,'5'=>$NotUploaded,'datas'=>$NotUpLoadedDataArr);
//echo $steflag."@@".$feflag."@@".$msg."@@".$TotalLines."@@".$InsertedLines."@@".$NotUploaded;
//echo $itemnoList;
echo json_encode($OutputArr);

?>
