<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');

$userid 	= 	$_SESSION['userid'];
$staffid 	= 	$_SESSION['sid'];
$count = 0; $shtcnt = 0;
$TotalLines = 0; $InsertedLines = 0; $NotUploadedArr = array();
$UnitId 			= 	$_POST['cmb_unit'];
$ExcelSheetName 	= 	trim($_POST['txt_sheet_name']);
$ExcelEndRow 		= 	trim($_POST['txt_end_row']);
$uploadfilename 	= 	$_FILES["file"]["name"];
$UpLoadedDataArr = array();
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
	$returnStr = $ddmmyyyy."**".$yyyymmdd."**".$error."***".$count1;
	return $returnStr;
}

if ($_FILES['file']['name'] != "") 
{
	$target_dir 		= 	"Voucher/";
    $target_file 		= 	$target_dir . $mtype."_".basename($_FILES["file"]["name"]);
    $currentfilename 	=	$mtype."_".basename($_FILES["file"]["name"]);
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
            $checkupload 	= 	1;
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
			}
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
				$FilePo 	= 	trim($Row[0]);
				$Item 		= 	trim($Row[1]);
				$PoValue 	= 	trim($Row[2]);
				$VrNo 		= 	trim($Row[3]);
				$VrDate 	= 	trim($Row[4]);
				$VrAmt		= 	trim($Row[5]);
				$PoRelDt 	= 	trim($Row[6]);
				$OPin 		= 	trim($Row[7]);
				$NPin 		= 	trim($Row[8]);
				$Code 		= 	trim($Row[9]);
				$PaidAmt 	= 	trim($Row[10]);
				$Hoa		= 	trim($Row[11]);
				$NewHoa 	= 	trim($Row[12]);
				$Indentor 	= 	trim($Row[13]);
				$GrpDivSec 	= 	trim($Row[14]);
				$PlantServ 	= 	trim($Row[15]);
				$SancOmAct 	= 	trim($Row[16]);
				$SancOmNwAct= 	trim($Row[17]);
				//$escaped = preg_replace(array('', '', ''), array('ddd', 'dddd', 'ddddd'), $description);    
				$Item = preg_replace($patterns, $replacements, $Item);
				$Item = str_replace("'", "'", $Item);
				
// ( B )***************************** First Row Format check is starts Here *****************************//					
				if($Key == 7)
				{
					if(($FilePo == "") || ($VrNo == "") || ($VrDate == "") || ($VrAmt == ""))
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
						$mdate 		= 	$yyyymmdd;
						$deflag 	= 	$checkerror1;
					}
					else
					{
						$mdate 		= 	$date;
						$deflag 	= 	$checkerror1;
					}
				}

				if(($FilePo == "") && ($VrNo == "") && ($VrDate == "") && ($VrAmt == "")) 
				{
					$check1 = 1;
				}
				if($check1 != 1)
				{
					$InsertQuery = "insert into voucher_upt set unitid = '$UnitId', wo = '$FilePo', item = '$Item', wo_amt = '$PoValue', vr_no = '$VrNo', vr_dt = '$VrDate',
									vr_amt = '$VrAmt', wo_dt = '$PoRelDt', o_pin = '$OPin', n_pin = '$NPin', code= '$Code', paid_amt = '$PaidAmt',
									hoa = '$Hoa', new_hoa = '$NewHoa', indentor = '$Indentor', grp_div_sec = '$GrpDivSec', plant_serv = '$PlantServ', 
									sanct_om_act_sno = '$SancOmAct', sanct_om_nwme_sno = '$SancOmNwAct', createdon = NOW(), staffid = '$staffid', userid = '$userid', 
									entry_flag = 'XL'";
					$InsertSql 	 = 	mysql_query($InsertQuery);
					if($InsertSql == true){
						$count++;
						$InsertedLines++;
						$DataArr = array();
						$DataArr['wo'] = $FilePo; $DataArr['item'] = $Item; $DataArr['wo_amt'] = $PoValue; $DataArr['vr_no'] = $VrNo; $DataArr['vr_dt'] = $VrDate;
						$DataArr['vr_amt'] = $VrAmt; $DataArr['wo_dt'] = $PoRelDt; $DataArr['o_pin'] = $OPin; $DataArr['n_pin'] = $NPin; $DataArr['code'] = $Code; $DataArr['paid_amt'] = $PaidAmt;
						$DataArr['hoa'] = $Hoa; $DataArr['new_hoa'] = $NewHoa; $DataArr['indentor'] = $Indentor; $DataArr['grp_div_sec'] = $GrpDivSec; $DataArr['plant_serv'] = $PlantServ; $DataArr['sanct_om_act_sno'] = $SancOmAct;
						$DataArr['sanct_om_nwme_sno'] = $SancOmNwAct;
						$UpLoadedDataArr[] = $DataArr;
					}else{
						array_push($NotUploadedArr,$Key+1);
					}
				}else{
					array_push($NotUploadedArr,$Key+1);
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
if($count >0){ $msg = "Measurements Uploaded Sucessfully"; }
if(count($NotUploadedArr)>0){ $NotUploaded = implode(",",$NotUploadedArr); }else{ $NotUploaded = ""; }
//print_r($SubDivIdList);
//$UpLoadedDataArrJson = json_encode($UpLoadedDataArr);
$OutputArr = array('0'=>$steflag,'1'=>$feflag,'2'=>$msg,'3'=>$TotalLines,'4'=>$InsertedLines,'5'=>$NotUploaded,'datas'=>$UpLoadedDataArr);
//echo $steflag."@@".$feflag."@@".$msg."@@".$TotalLines."@@".$InsertedLines."@@".$NotUploaded;
//echo $itemnoList;
echo json_encode($OutputArr);

?>
