<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');
$userid 			= 	$_SESSION['userid'];
function datevalidation($inputdate)
{
	$temp = 0; $error = 0;
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
				$error = 1; 
			}
			else if(($monthfield1>12) || ($monthfield1<1)) 
			{ 
				$error = 2; 
			}
			else if(($length1>2) || ($length1<1)) 
			{
				$error = 3;
			}
			else if(($length2>2) || ($length2<1)) 
			{
				$error = 4;
			}
			else if($length3 != 4) 
			{
				$error = 5;
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
			$error = 6;
		}
	}
	//$temp1 = str_replace('-', '/', $inputdate);
	$returnStr = $ddmmyyyy."**".$yyyymmdd."**".$error."***".$count1;
	return $returnStr;
}
$sheet_id 			= 	trim($_POST['txt_workshortname']);
$workorderno		= 	trim($_POST['txt_workorder_no']);
$workname 			= 	trim($_POST['txt_workname']);
$startrow 			= 	trim($_POST['txt_xl_startrow']);
$endrow 			= 	trim($_POST['txt_xl_endrow']);
$xlsheetname 		= 	trim($_POST['txt_xl_sheetname']);
$mtype 				= 	trim($_POST['rad_measurementtype']);
//$sheetname 			= 	@mysql_result($selectworder_query,0,'sheet_name');
$uploadfilename 	= 	$_FILES["file"]["name"];
//echo $uploadfilename;
$ItemNoList 	= array();
$DivIdList 		= array();
$SubDivIdList 	= array();	
$select_itemlist_sql 	= 	"select subdiv_id, subdiv_name, div_id from subdivision where  sheet_id = '$sheet_id' and active = '1'";
$select_itemlist_query 	= 	mysql_query($select_itemlist_sql);
while($ItemList = mysql_fetch_object($select_itemlist_query))
{
	$divid 		= $ItemList->div_id;
	$subdivid 	= $ItemList->subdiv_id;
	$itemno 	= $ItemList->subdiv_name;
	$DivIdList[$itemno] 	= $divid;
	$SubDivIdList[$itemno] 	= $subdivid;
	$ItemNoList[$itemno] 	= $itemno;
	
}
if ($_FILES['file']['name'] != "") 
{
	$target_dir 		= 	"measurments/";
    $target_file 		= 	$target_dir . basename($_FILES["file"]["name"]);
    $currentfilename 	=	basename($_FILES["file"]["name"]);
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
   	$Spreadsheet 	= new SpreadsheetReader("measurments/" . $currentfilename);
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
			if($xlsheetname != $sheetname)
			{
				break 1;
			}
			else
			{
				$steflag++;
			}
// ( A )******************************** Title Format check is starts Here *******************************//
			if($Key == 10)
			{
				$formaterror = 0;
				if($Row[0] == "") { $formaterror = 1; }
               	if($Row[1] == "") { $formaterror = 1; }
               	if($Row[2] == "") { $formaterror = 1; }
               	if($Row[3] == "") { $formaterror = 1; }
				if($Row[4] == "") { $formaterror = 1; }
				if($Row[5] == "") { $formaterror = 1; }
				if($Row[6] == "") { $formaterror = 1; }
			}
			if($formaterror == 1)
			{
				$feflag = 1;
				/*if (file_exists($target_file)) 
				{ 
					unlink($target_file); 
				}*/
				break 2;
			}
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Title Format check is ends Here !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!//
			if ($Key>10)
			{
				if($mtype == 'G')
				{
					$check1 = 0;
					$date 			= 	$Row[0];
					$itemno 		= 	$Row[1];
					$description 	= 	$Row[2];
					$number 		= 	$Row[3];
					$length 		= 	$Row[4];
					$breadth		= 	$Row[5];
					$depth 			= 	$Row[6];
				}
				if($mtype == 'S')
				{
					$check1 = 0;
					$date 			= 	$Row[0];
					$itemno 		= 	$Row[1];
					$description 	= 	$Row[2];
					$dia 			= 	$Row[3];
					$number 		= 	$Row[4];
					$length			= 	$Row[5];
				}
// ( B )***************************** First Row Format check is starts Here *****************************//					
				if($Key == 11)
				{
					if(($date == "") || ($itemno == ""))
					{
						$feflag = 2;
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
						$ieflag = 1;
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
					if(($itemno == "") && ($description == "") && ($dia == "") && ($number == "") && ($length == "")) 
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
						}
						if(in_array($itemno, $ItemNoList))
						{
							$divid 		= 	$DivIdList[$itemno];
							$subdivid 	= 	$SubDivIdList[$itemno];
						}
						else
						{
							$divid		=	0;
							$subdivid	=	0;
						}
						if($number 	== "") 	{ $number 	= 1; }
						if($length 	== "") 	{ $length 	= 1; }
						if($depth 	== "")  { $depth 	= 1; }
						if($breadth == "")	{ $breadth 	= 1; }
						if($dia 	== "")	{ $dia 		= 1; }
						if($mtype == 'G')
						{
							if(($number == "") && ($length == "") && ($depth == "") && ($breadth == ""))
							{
								$contentarea = "";
							}
							else
							{
								if($number 	== "") 	{ $number 	= 1; }
								if($length 	== "") 	{ $length 	= 1; }
								if($depth 	== "")  { $depth 	= 1; }
								if($breadth == "")	{ $breadth 	= 1; }
								$contentarea 	= 	$number * $length * $depth * $breadth;
							}
						}
						if($mtype == 'S')
						{
							if(($number == "") && ($length == "") && ($dia == ""))
							{
								$contentarea 	= 	"";
							}
							else
							{
								if($length 	== "") 	{ $length 	= 1; }
								$contentarea 	= 	$number * $length;
							}
						}
						if(($mdate != $prev_mdate) || ($itemno != $prev_itemno))
						{
							$mbookheader_sql 	= 	"insert into mbookheader_temp set date = '$mdate', sheetid ='$sheet_id', divid = '$divid', subdivid = '$subdivid', active = '1'";
							$mbookheader_query 	= 	mysql_query($mbookheader_sql);
							$mbheader_id 		= 	mysql_insert_id();
							$idtemp				=	1;
						}
						//============ insert into mbook details
						if($idtemp == 0)
						{
							$mbheader_id		=	$prev_mbheader_id;
						}
						$errorflag  =  $deflag.",".$ieflag;
						if($mtype == 'G')
						{
							$mbookdetail_sql 	= 	"insert into mbookdetail_temp set mbheaderid = '$mbheader_id', subdivid = '$subdivid', descwork = '$description', measurement_no = '$number', measurement_l = '$length', measurement_b = '$breadth', measurement_d = '$depth', structdepth_unit = '$structdepth_unit', measurement_contentarea = '$contentarea', remarks = '$itemunit', mbdetail_flag = '$errorflag', entry_date = NOW()";
						}
						if($mtype == 'S')
						{
							$mbookdetail_sql 	= 	"insert into mbookdetail_temp set mbheaderid = '$mbheader_id', subdivid = '$subdivid', descwork = '$description', measurement_no = '$number', measurement_l = '$length',  measurement_dia = '$dia', measurement_contentarea = '$contentarea', remarks = '$itemunit', mbdetail_flag = '$errorflag', entry_date = NOW()";
						}
						$mbookdetail_query	= 	mysql_query($mbookdetail_sql);
						$mbdetail_id 		= 	mysql_insert_id();

						$prev_mdate 			= 	$mdate;
						$prev_itemno 			= 	$itemno;
						$prev_divid 			= 	$divid;
						$prev_subdivid 			= 	$subdivid;
						$prev_mbheader_id		=	$mbheader_id;
						$prev_mbdetail_id		=	$mbdetail_id;
						$idtemp					=	0;
						
					}
				}
				
//!!!!!!!!!!!!!!!!!!!!!!!!!!!! Content Area Calculation check is ends Here !!!!!!!!!!!!!!!!!!!!!!!!!!!!//	
				
				//$res .= $mdate."*".$itemno."*".$description."*".$number."*".$length."*".$depth."*".$breadth."*".$contentarea."@@@@<br/>";
				$res .= $returnStr1."@@@@";
			}
        } 
    } 

} 
//echo $res."@@@@".$msg;
echo $steflag."@@".$feflag."@@".$msg;
//print_r($ItemNoList);
//echo $mtype;
?>
