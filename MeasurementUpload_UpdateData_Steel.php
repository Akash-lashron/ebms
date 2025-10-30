<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');
//require_once 'library/functions.php';
//require_once 'library/binddata.php';
//checkUser();
$userid 	= 	$_SESSION['userid'];
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
				$error = "Day value in Date field is Greater than 31."; 
			}
			else if(($monthfield1>12) || ($monthfield1<1)) 
			{ 
				$error = "month value in Date field is Greater than 12."; 
			}
			else if(($length1>2) || ($length1<1)) 
			{
				$error = "Day value in Date field is more than two digit.";
			}
			else if(($length2>2) || ($length2<1)) 
			{
				$error = "month value in Date field is more than two digit.";
			}
			else if($length3 != 4) 
			{
				$error = "Year value in Date field is not in YYYY format.";
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
			$error = "Date format is invalid which should be ddmmyyyy.";
		}
	}
	//$temp1 = str_replace('-', '/', $inputdate);
	$returnStr = $ddmmyyyy."**".$yyyymmdd."**".$error."***".$count1;
	return $returnStr;
}
$inputStr		=	$_POST['inputStr'];
$splitInput		=	explode("@*@",$inputStr);
$mbheaderid 	= 	$splitInput[0];
$mbdetailid 	= 	$splitInput[1];
$date 			= 	$splitInput[2];
$mitemno 		=	$splitInput[3];
$description 	= 	$splitInput[4];
$number 		= 	$splitInput[5];
$length 		= 	$splitInput[6];
$dia 			= 	$splitInput[7];
$depth 			= 	$splitInput[8];
$unit 			= 	$splitInput[9];
$sheet_id 		= 	$_POST['sheetid'];
$ItemNoList 	= array();
$DivIdList 		= array();
$SubDivIdList 	= array();	
$UnitList 		= array();

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

$select_itemlist_sql 	= 	"select subdivision.subdiv_id, subdivision.subdiv_name, subdivision.div_id, schdule.per from subdivision INNER JOIN schdule ON (schdule.subdiv_id = subdivision.subdiv_id) where subdivision.sheet_id = '$sheet_id' and subdivision.active = '1'";
$select_itemlist_query 	= 	mysql_query($select_itemlist_sql);
while($ItemList = mysql_fetch_object($select_itemlist_query))
{
	$divid 		= $ItemList->div_id;
	$subdivid 	= $ItemList->subdiv_id;
	$itemno 	= $ItemList->subdiv_name;
	$unit 		= $ItemList->per;
	$DivIdList[$itemno] 	= $divid;
	$SubDivIdList[$itemno] 	= $subdivid;
	$ItemNoList[$itemno] 	= $itemno;
	$UnitList[$itemno] 	= $unit;
	
}
$errorflag 	= "";	
$deflag 	= "";	
$ieflag 	= "";	
$feflag 	= "";
$idtemp		= 0;
// ( C )******************************** Date Format check is starts Here *******************************//	
if($date != "")
{
	$returnStr1 	= 	datevalidation($date);
	$expresult1 	= 	explode("**",$returnStr1);
	$ddmmyyyy  		= 	$expresult1[0];
	$yyyymmdd  		= 	$expresult1[1];
	$checkerror1  	= 	$expresult1[2];
	if($checkerror1 == "")
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
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Date Format check is ends Here !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!//

// ( D )************************** Item Number Format check is starts Here *****************************//
if($mitemno != "")
{				
	if(in_array($mitemno, $ItemNoList))
	{
		$ieflag = "";
		$divid 		= 	$DivIdList[$mitemno];
		$subdivid 	= 	$SubDivIdList[$mitemno];
		$itemunit 	= 	$UnitList[$mitemno];
		//if($unit == "") { $unit = $itemunit; }
	}
	else
	{
		$ieflag = "Invalid Item No.";//"This Item Number is invalid / Does not exist in aggreement sheet";
		$divid		=	0;
		$subdivid	=	0;
		$itemunit	=	"";
	}
}
if(($mitemno != "") && ($description != "") && ($mdate != ""))
{
	if($description != "")
	{
		if($number 	== "") 	{ $number_temp 	= 1; } else { $number_temp = $number; }
		if($length 	== "") 	{ $length_temp 	= 1; } else { $length_temp = $length; }
		//if($depth 	== "")  { $depth 	= 1; }
		//if($breadth == "")	{ $breadth 	= 1; }
		if($dia 	== "")	{ $dia_temp 	= 1; } else { $dia_temp = $dia; }
		if(($number == "") && ($length == "") && ($dia == ""))
		{
			$contentarea = "";
		}
		else
		{
			/*if($number 	== "") 	{ $number 	= 1; }
			if($length 	== "") 	{ $length 	= 1; }*/
			//if($depth 	== "")  { $depth 	= 1; }
			//if($breadth == "")	{ $breadth 	= 1; }
			$contentarea 	= 	$number_temp * $length_temp;// * $dia_temp;// * $breadth;
		}
		$type = 's';
		$errorflag 				 =  $mitemno."@@@".$deflag."@@@".$ieflag."@@@".""."@@@".$dteflag;
		$mbookdetail_sql 	= 	"update mbookdetail_temp set subdivid = '$subdivid', subdiv_name = '$mitemno', descwork = '$description', measurement_no = '$number', measurement_l = '$length', measurement_dia = '$dia', measurement_contentarea = '$contentarea', remarks = '$itemunit', mbdetail_flag = '$errorflag', entry_date = NOW() where mbdetail_id  = '$mbdetailid'";
		$mbookdetail_query 	= 	mysql_query($mbookdetail_sql);
		
		$mbookheader_sql 	= 	"update mbookheader_temp set date = '$mdate', divid = '$divid', subdivid = '$subdivid', subdiv_name = '$mitemno' where mbheaderid  = '$mbheaderid'";
		$mbookheader_query 	= 	mysql_query($mbookheader_sql);
		
		//$mbookdetail_sql_1		= 	"update mbookdetail_temp set subdivid = '$subdivid', subdiv_name = '$mitemno', mbdetail_flag = '$errorflag', measure_type = '$type', remarks = '$itemunit' where mbheaderid  = '$mbheaderid'";
		//$mbookdetail_query_1 	= 	mysql_query($mbookdetail_sql_1);
		
		if($mbookdetail_query == true){ $msg = "updated sucessfully."; }
	}
}
//echo $steflag."@@".$feflag."@@".$msg;
echo $inputStr;
//print_r($UnitList);
//echo $mbookdetail_sql;
?>
