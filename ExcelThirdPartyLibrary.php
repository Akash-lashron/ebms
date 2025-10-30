<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');

$userid 	= 	$_SESSION['userid'];
$staffid 	= 	$_SESSION['sid'];
$count = 0; $shtcnt = 0;
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
$TotalLines = 0; $InsertedLines = 0; $NotUploadedArr = array();
$sheet_id 			= 	trim($_POST['txt_workshortname']);
$workorderno		= 	trim($_POST['txt_workorder_no']);
$workname 			= 	trim($_POST['txt_workname']);
$startrow 			= 	trim($_POST['txt_xl_startrow']);
$startrow = $startrow-1;
$endrow 			= 	trim($_POST['txt_xl_endrow']);
$xlsheetname 		= 	trim($_POST['txt_xl_sheetname']);
$mtype 				= 	trim($_POST['rad_measurementtype']);
$zone_id 			= 	trim($_POST['cmb_zone_name']);
//$sheetname 			= 	@mysql_result($selectworder_query,0,'sheet_name');
$uploadfilename 	= 	$_FILES["file"]["name"];

$DeleteQuery = "DELETE a.*, b.* FROM mbookheader_temp a, mbookdetail_temp b WHERE a.sheetid = '$sheet_id' AND a.mbheaderid = b.mbheaderid";
$DeleteSql   = mysql_query($DeleteQuery);

$maxdateFlag = 0;
$select_maxdate_query = "select date(max(todate)) as maxdate from measurementbook where sheetid = '$sheet_id'";
$select_maxdate_sql = mysql_query($select_maxdate_query);
if($select_maxdate_sql == true)
{
	if(mysql_num_rows($select_maxdate_sql)>0)
	{
		$MDList = mysql_fetch_object($select_maxdate_sql);
		$MaxDate = $MDList->maxdate;
		$maxdateFlag = 1;
	}
}
if($maxdateFlag == 0)
{
	$select_maxdate_query = "select work_order_date from sheet where sheet_id = '$sheet_id'";
	$select_maxdate_sql = mysql_query($select_maxdate_query);
	if($select_maxdate_sql == true)
	{
		if(mysql_num_rows($select_maxdate_sql)>0)
		{
			$MDList = mysql_fetch_object($select_maxdate_sql);
			$MaxDate = $MDList->work_order_date;
		}
	}	
}
//echo $MaxDate;exit;
//echo $uploadfilename;
$ItemNoList 	= array();
$DivIdList 		= array();
$SubDivIdList 	= array();	
$UnitList 		= array();
$TypeList 		= array();
$DecimalList 	= array();
$select_itemlist_sql 	= 	"select subdivision.subdiv_id, subdivision.subdiv_name, subdivision.div_id, schdule.per, schdule.decimal_placed, schdule.measure_type from subdivision INNER JOIN schdule ON (schdule.subdiv_id = subdivision.subdiv_id) where subdivision.sheet_id = '$sheet_id' and subdivision.active = '1'";
$select_itemlist_query 	= 	mysql_query($select_itemlist_sql);
while($ItemList = mysql_fetch_object($select_itemlist_query))
{
	$divid 		= $ItemList->div_id;
	$subdivid 	= $ItemList->subdiv_id;
	$itemno 	= $ItemList->subdiv_name;
	$unit 		= $ItemList->per;
	$type 		= $ItemList->measure_type;
	$decimal 		= $ItemList->decimal_placed;
	if($type == "")
	{
		$type = "g";
	}
	$DivIdList[$itemno] 	= $divid;
	$SubDivIdList[$itemno] 	= $subdivid;
	$ItemNoList[$itemno] 	= $itemno;
	$UnitList[$itemno] 		= $unit;
	$TypeList[$itemno] 		= $type;
	$DecimalList[$itemno] 	= $decimal;
	//print_r($DivIdList);
	
}

if ($_FILES['file']['name'] != "") 
{
	$target_dir 		= 	"measurments/";
    $target_file 		= 	$target_dir . $mtype."_".basename($_FILES["file"]["name"]);
    $currentfilename 	=	$mtype."_".basename($_FILES["file"]["name"]);
    $checkupload 		= 	1;
    $imageFileType 		= 	pathinfo($target_file, PATHINFO_EXTENSION);
   /* if (file_exists($target_file)) 
	{
       $msg 			= 	$msg . " Sorry, file already exists." . "<BR>";
       $checkupload 	= 	0;
    }*/
    if ($_FILES["file"]["size"] > 500000) 
	{
       $msg 			= 	$msg . " Sorry, your file is too large." . "<BR>";
       $checkupload 	= 	0;
    }
    if (strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") 
	{
       $msg 			= 	$msg . " Sorry, only xls files are allowed." . "<BR>";
       $checkupload 	= 	0;
    }
    if ($checkupload == 0) 
	{
       $msg 			= 	$msg . " Sorry, your file was not uploaded." . "<BR>";
    } 
	else 
	{

      	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) 
		{
            $checkupload 	= 	1;
        } 
		else 
		{
           $checkupload 	=	0;
           $msg 			= 	$msg .  "Sorry, there was an error uploading your file." . "<BR>";
        }
    }
} 
$errorflag 			= "";	$deflag 			= "";	$ieflag 		= "";	$feflag 		= "";	$steflag = 0;
$prev_mdate 		= "";	$prev_itemno 		= "";	$prev_divid 	= "";	$prev_subdivid 	= "";
$prev_mbheader_id 	= "";	$prev_mbdetail_id 	= "";	$idtemp 		= "";	
if ($checkupload == 1) 
{
   	$Spreadsheet 	= new SpreadsheetReader("measurments/" . $currentfilename, false, 'UTF-8');
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
			if($Key == $endrow)
			{
				break 2;
			}
			if($xlsheetname != $sheetname)
			{
				break 1;
			}
			else
			{
				$shtcnt++;
				$steflag++;
			}
// ( A )******************************** Title Format check is starts Here *******************************//
			if($Key == $startrow)
			{
				if($mtype == 'G')
				{
					$formaterror = 0;
					if(trim($Row[0]) == "") { $formaterror = 1; }
					if(trim($Row[1]) == "") { $formaterror = 1; }
					if(trim($Row[2]) == "") { $formaterror = 1; }
					if(trim($Row[3]) == "") { $formaterror = 1; }
					if(trim($Row[4]) == "") { $formaterror = 1; }
					if(trim($Row[5]) == "") { $formaterror = 1; }
					if(trim($Row[6]) == "") { $formaterror = 1; }
				}
				if($mtype == 'S')
				{
					$formaterror = 0;
					if(trim($Row[0]) == "") { $formaterror = 1; }
					if(trim($Row[1]) == "") { $formaterror = 1; }
					if(trim($Row[2]) == "") { $formaterror = 1; }
					if(trim($Row[3]) == "") { $formaterror = 1; }
					if(trim($Row[4]) == "") { $formaterror = 1; }
					if(trim($Row[5]) == "") { $formaterror = 1; }
					if(trim($Row[6]) == "") { $formaterror = 1; }
				}
			}
			$Ent = trim($Row[0])."@@".trim($Row[1])."@@".trim($Row[2])."@@".trim($Row[3])."@@".trim($Row[4])."@@".trim($Row[5])."@@".trim($Row[6]);
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

			if ($Key>$startrow)
			{
				$TotalLines++;
				if($mtype == 'G')
				{
					$check1 = 0;
					$date 			= 	trim($Row[0]);
					$itemno 		= 	trim($Row[1]);
					$description 	= 	trim($Row[2]);
					$number 		= 	trim($Row[3]);
					$length 		= 	trim($Row[4]);
					$breadth		= 	trim($Row[5]);
					$depth 			= 	trim($Row[6]);
					//$escaped = preg_replace(array('', '', ''), array('ddd', 'dddd', 'ddddd'), $description);    
        			$description = preg_replace($patterns, $replacements, $description);
					$description = str_replace("'", "'", $description);
					//$description = str_replace('°', 'DEG', $description);
				}
				if($mtype == 'S')
				{
					$check1 = 0;
					$date 			= 	trim($Row[0]);
					$itemno 		= 	trim($Row[1]);
					$description 	= 	trim($Row[2]);
					$dia 			= 	trim($Row[3]);
					$number2 		= 	trim($Row[4]);
					$number 		= 	trim($Row[5]);
					$length			= 	trim($Row[6]);
				}
				
				$datestring .= $date."@@";
// ( B )***************************** First Row Format check is starts Here *****************************//					
				if($Key == 7)
				{
					if(($date == "") || ($itemno == ""))
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
				if($date != "")
				{
					$returnStr1 	= 	datevalidation($date);
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
				if($mdate <= $MaxDate)
				{
					$dteflag = "Measurement already generated for this date";
				}
				else
				{
					$dteflag = "";
				}
				//echo $mdate." @@@ ".$MaxDate." @@@ ".$Err;
				//exit;
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Date Format check is ends Here !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!//

// ( D )************************** Item Number Format check is starts Here *****************************//
				if($itemno != "")
				{				
					if(in_array($itemno, $ItemNoList))
					{
						$ieflag = "";
					}
					else
					{
						$ieflag = "Invalid Item No.";//"This Item Number is invalid / Does not exist in aggreement sheet";
						$resu .= $itemno."***".$ieflag."@@@";
					}
				}
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Item Number Format check is ends Here !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!//	

// ( E )*********************** Content Area Calculation check is starts Here **************************//	
				if($mtype == 'G')
				{		
					if(($itemno == "") && ($description == "") && ($number == "") && ($length == "") && ($breadth == "") && ($depth == "")) 
					{
						$check1 = 1;
					}
				}
				if($mtype == 'S')
				{		
					if(($itemno == "") && ($description == "") && ($dia == "") && ($number == "") && ($length == "") && ($number2 == "")) 
					{
						$check1 = 1;
					}
				}
				if($check1 != 1)
				{
					if($description != "")
					{
						if(($mdate == "") && ($prev_mdate != ""))
						{ 
							$mdate = $prev_mdate; 
						}
						if(($itemno == "") && ($prev_itemno != ""))
						{ 
							$itemno = $prev_itemno;
							if($itemno != "")
							{				
								if(in_array($itemno, $ItemNoList))
								{
									$ieflag = "";
								}
								else
								{
									$ieflag = "Invalid Item No.";//"This Item Number is invalid / Does not exist in aggreement sheet";
									//$resu .= $itemno."***".$ieflag."@@@";
								}
							} 
						}
						if(in_array($itemno, $ItemNoList))
						{
							$itemno = (string)$itemno;
							$divid 		= 	$DivIdList[$itemno];
							$subdivid 	= 	$SubDivIdList[$itemno];
							$itemunit 	= 	$UnitList[$itemno];
							$itemdecimal 	= 	$DecimalList[$itemno];
							//echo $itemno."*".$SubDivIdList[$itemno]."@@";
							//print_r($SubDivIdList)."<br/>";
						}
						else
						{
							$divid		=	0;
							$subdivid	=	0;
							$itemunit	=	"";
							$itemdecimal = 3; /// It may be Changed.
						}
						//echo $itemno."*".$subdivid."@@";
						/*if($number 	== "") 	{ $number 	= 1; }
						if($length 	== "") 	{ $length 	= 1; }
						if($depth 	== "")  { $depth 	= 1; }
						if($breadth == "")	{ $breadth 	= 1; }
						if($dia 	== "")	{ $dia 		= 1; }*/
						if($mtype == 'G')
						{
							if(($number == "") && ($length == "") && ($depth == "") && ($breadth == ""))
							{
								$contentarea = "";
							}
							else
							{
								if($number 	== "") 	{ $number_temp 		= 1; } else { $number_temp 	= $number; }
								if($length 	== "") 	{ $length_temp  	= 1; } else { $length_temp 	= $length; }
								if($depth 	== "")  { $depth_temp  		= 1; } else { $depth_temp 	= $depth; }
								if($breadth == "")	{ $breadth_temp 	= 1; } else { $breadth_temp = $breadth; }
								$contentarea 	= 	$number_temp * $length_temp * $depth_temp * $breadth_temp;
								$contentarea = round($contentarea,$itemdecimal);
							}
						}
						if($mtype == 'S')
						{
							if(($number == "") && ($length == "") && ($dia == "") && ($number2 == ""))
							{
								$contentarea 	= 	"";
							}
							else
							{
								if($number 	== "") 	{ $number_temp 	= 1; } else { $number_temp 	= $number; }
								if($number2 == "") 	{ $number2_temp = 1; } else { $number2_temp = $number2; }
								if($length 	== "") 	{ $length_temp 	= 1; } else { $length_temp 	= $length; }
								$contentarea 	= 	$number_temp * $length_temp*$number2_temp;
								$contentarea = round($contentarea,$itemdecimal);
							}
						}
						if(($mdate != $prev_mdate) || ($itemno != $prev_itemno))
						{
							if($mtype == 'S'){ $type = "s"; } else { $type = $TypeList[$itemno]; if($type == 's'){ $type = ''; } /* in case steel / general item wrongly uploaded in General / Steel vice versa */ }
							$mbookheader_sql 	= 	"insert into mbookheader_temp set date = '$mdate', sheetid ='$sheet_id', divid = '$divid', subdivid = '$subdivid', subdiv_name = '$itemno', measure_type = '$type', staffid = '$staffid', zone_id = '$zone_id', active = '1'";
							$mbookheader_query 	= 	mysql_query($mbookheader_sql);
							$mbheader_id 		= 	mysql_insert_id();
							$idtemp				=	1;
						}
						//============ insert into mbook details
						if($idtemp == 0)
						{
							$mbheader_id		=	$prev_mbheader_id;
						}
						$errorflag 				 =  $itemno."@@@".$deflag."@@@".$ieflag."@@@".""."@@@".$dteflag;
						if($mtype == 'G')
						{
							$type = $TypeList[$itemno];
							if($type == 's'){ $type = ''; }
							$mbookdetail_sql 	= 	"insert into mbookdetail_temp set mbheaderid = '$mbheader_id', subdivid = '$subdivid', subdiv_name = '$itemno', descwork = '$description', measurement_no = '$number', measurement_l = '$length', measurement_b = '$breadth', measurement_d = '$depth', structdepth_unit = '$structdepth_unit', measurement_contentarea = '$contentarea', measure_type = '$type', remarks = '$itemunit', zone_id = '$zone_id', mbdetail_flag = '$errorflag', entry_date = NOW()";
						}
						if($mtype == 'S')
						{
							$mbookdetail_sql 	= 	"insert into mbookdetail_temp set mbheaderid = '$mbheader_id', subdivid = '$subdivid', subdiv_name = '$itemno', descwork = '$description', measurement_no = '$number', measurement_no2 = '$number2', measurement_l = '$length',  measurement_dia = '$dia', measurement_contentarea = '$contentarea', measure_type = 's', remarks = '$itemunit', zone_id = '$zone_id', mbdetail_flag = '$errorflag', entry_date = NOW()";
						}
						$mbookdetail_query	= 	mysql_query($mbookdetail_sql);
						if($mbookdetail_query == true) { $count++; $InsertedLines++; }else{ array_push($NotUploadedArr,$Key+1); }
						$mbdetail_id 		= 	mysql_insert_id();

						$prev_mdate 			= 	$mdate;
						$prev_itemno 			= 	$itemno;
						$prev_divid 			= 	$divid;
						$prev_subdivid 			= 	$subdivid;
						$prev_mbheader_id		=	$mbheader_id;
						$prev_mbdetail_id		=	$mbdetail_id;
						$idtemp					=	0;
						//$itemnoList .= $itemno."*".$subdivid."@@";
						
					}else{
						array_push($NotUploadedArr,$Key+1);
					}
				}else{
					array_push($NotUploadedArr,$Key+1);
				}
				
//!!!!!!!!!!!!!!!!!!!!!!!!!!!! Content Area Calculation check is ends Here !!!!!!!!!!!!!!!!!!!!!!!!!!!!//	
				
				//$res .= $mdate."*".$itemno."*".$description."*".$number."*".$length."*".$depth."*".$breadth."*".$contentarea."@@@@<br/>";
				$res .= $returnStr1."@@@@";
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

echo $steflag."@@".$feflag."@@".$msg."@@".$TotalLines."@@".$InsertedLines."@@".$NotUploaded;
//echo $itemnoList;?>
