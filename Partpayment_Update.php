<?php
require_once 'library/config.php';
$resultdata = $_POST['resultdata'];
$sheetid 	= $_POST['sheetid'];

$RemarksStr = $_POST['RemarksStr'];
$splitRemarksStr = explode("@#*#@", $RemarksStr);
$SlmRemarks = $splitRemarksStr[0];
$DpmRemarks = $splitRemarksStr[1];

$itemStr 	= $_POST['itemStr'];
$splititemStr = explode("@@@", $itemStr);
$slmStr = $splititemStr[0]; 
$dpmStr = $splititemStr[1];
if($slmStr != "")
{
	$splitslmStr = explode("*@*",$slmStr);
	$subdivid 				= $splitslmStr[0];
	$subdivname 			= $splitslmStr[1];
	$divid 					= $splitslmStr[2];
	$description 			= $splitslmStr[3];
	$slm_measurement_qty 	= $splitslmStr[4];
	$mbookno_slm 			= $splitslmStr[5];
	$mbpageno_slm 			= $splitslmStr[6];
	$absmbookno_slm 		= $splitslmStr[7];
	$absmbpageno_slm 		= $splitslmStr[8];
	$flag_slm 				= $splitslmStr[9];
	$partpay_flag_slm 		= $splitslmStr[10];
	$staffid 				= $splitslmStr[11];
	$userid 				= $splitslmStr[12];
	$fromdate_slm 			= $splitslmStr[13];
	$todate_slm 			= $splitslmStr[14];
	$slmtemp = 1;
}
else
{
	$slmtemp = 0;
}

if($dpmStr != "")
{
	$splitdpmStr = explode("*@*",$dpmStr);
	$subdivid 				= $splitdpmStr[0];
	$subdivname 			= $splitdpmStr[1];
	$divid 					= $splitdpmStr[2];
	$description 			= $splitdpmStr[3];
	$dpm_measurement_qty 	= $splitdpmStr[4];
	$mbookno_dpm 			= $splitdpmStr[5];
	$mbpageno_dpm 			= $splitdpmStr[6];
	$absmbookno_dpm 		= $splitdpmStr[7];
	$absmbpageno_dpm 		= $splitdpmStr[8];
	$flag_dpm 				= $splitdpmStr[9];
	$partpay_flag_dpm 		= $splitdpmStr[10];
	$staffid 				= $splitdpmStr[11];
	$userid 				= $splitdpmStr[12];
	$fromdate_dpm 			= $splitdpmStr[13];
	$todate_dpm 			= $splitdpmStr[14];
	$dpmtemp = 1;
}
else
{
	$dpmtemp = 0;
}
$explodesection = explode("###", $resultdata);
$slmsection = $explodesection[0];
$dpmsection = $explodesection[1];
//------------------ SINCE LAST MEASUREMENT SECTION -------------------------//

$splitresult = explode("@", $slmsection);
natsort($splitresult);
foreach($splitresult as $key => $summ_1)
{
   if($summ_1 != "")
   {
      $splitresult_1 .= $summ_1.",";
   }
}
$temp = 1; $prev_percent = ""; $qty_sum = 0; $tempslm = 0;
$splitresult = explode(',',rtrim($splitresult_1,","));
for($i=0; $i<count($splitresult); $i++)
{
	$dataStr = $splitresult[$i];
	//if(($dataStr != 'X') && ($dataStr != ""))
	if($dataStr != "")	
	{
		$splitdataStr = explode("*", $dataStr);
		$percent 	= $splitdataStr[0];
		$currentrbn = $splitdataStr[1];
		$itemqty 	= $splitdataStr[2];
		$itemid 	= $splitdataStr[3];
		$flag 		= $itemid."*".$currentrbn;
		if($prev_percent != "")
		{
			if($prev_percent != $percent)
			{
				
				$insertpartpay_sql = "insert into measurementbook_temp  
				(measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage, pay_percent, flag, part_pay_flag, rbn, active, userid, remarks) 
				values 
				(NOW(), '$staffid', '$sheetid', '$divid', '$subdivid', '$fromdate_slm', '$todate_slm', '$mbookno_slm', '$mbpageno_slm', '$qty_sum', '$absmbookno_slm', '$absmbpageno_slm', '$prev_percent', '$flag_slm', '1', '$prev_currentrbn', '1', '$userid', '$SlmRemarks')";
				$insertpartpay_query = mysql_query($insertpartpay_sql);
				if($insertpartpay_query == false)
				{
					$temp = 0;
				}
				$qty_sum = 0;
			}
		}
		if($tempslm == 0)
		{
			$delete_old_row_sql = "DELETE FROM measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$itemid' AND (part_pay_flag = 0 OR part_pay_flag = 1)";
			$delete_old_row_query = mysql_query($delete_old_row_sql);
		}
		$qty_sum		 = $qty_sum + $itemqty;
		$prev_itemid 	 = $itemid;
		$prev_currentrbn = $currentrbn;
		$prev_itemqty 	 = $itemqty;
		$prev_flag 		 = $flag;
		$prev_percent 	 = $percent;
		$tempslm++;
	}
}
/*if(($dataStr != 'X') && ($dataStr != ""))
{
	$insertpartpay_sql3 = "insert into measurementbook_temp  
	(measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage, pay_percent, flag, part_pay_flag, rbn, active, userid) 
	values 
	(NOW(), '$staffid', '$sheetid', '$divid', '$subdivid', '$fromdate_slm', '$todate_slm', '$mbookno_slm', '$mbpageno_slm', '$qty_sum', '$absmbookno_slm', '$absmbpageno_slm', '$prev_percent', '$flag_slm', '1', '$prev_currentrbn', '1', '$userid')";
	$insertpartpay_query3 = mysql_query($insertpartpay_sql3);
	if($insertpartpay_query3 == false)
	{
		$temp = 0;
	}
}*/
//------------------ DEDUCT PREVIOUS MEASUREMENT SECTION -------------------------//
$splitresult2 = explode("@", $dpmsection);
$dpmtemp = 0;
for($j=0; $j<count($splitresult2); $j++)
{
	$dataStr2 = $splitresult2[$j];
	if(($dataStr2 != 'Y') && ($dataStr2 != ""))	
	{
		$splitdataStr2  = explode("*", $dataStr2);
		$percent_dpm 	= $splitdataStr2[0];
		$currentrbn_dpm = $splitdataStr2[1];
		$itemqty_dpm 	= $splitdataStr2[2];
		$itemid_dpm 	= $splitdataStr2[3];
		$rbn_dpm 		= $splitdataStr2[4];
		$mbid_dpm 		= $splitdataStr2[5];
		$partpay_flag_dpm 	= $itemid_dpm."*".$rbn_dpm."*".$mbid_dpm;
		if($dpmtemp == 0)
		{
			$delete_old_row_sql2 = "DELETE FROM measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$itemid_dpm' AND part_pay_flag !=0 AND part_pay_flag != 1";
			$delete_old_row_query2 = mysql_query($delete_old_row_sql2);
		}
		if(($percent_dpm != "") && ($percent_dpm != 0))
		{
			$insertpartpay_sql2 = "insert into measurementbook_temp  
			(measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage, pay_percent, flag, part_pay_flag, rbn, active, userid) 
			values 
			(NOW(), '$staffid', '$sheetid', '$divid', '$subdivid', '$fromdate_dpm', '$todate_dpm', '$mbookno_dpm', '$mbpageno_dpm', '$itemqty_dpm', '', '', '$percent_dpm', '$flag_dpm', '$partpay_flag_dpm', '$currentrbn_dpm', '1', '$userid')";
			$insertpartpay_query2 = mysql_query($insertpartpay_sql2);
			if($insertpartpay_query2 != true)
			{
				$temp = 0;
			}

		}
		$dpmtemp++;
	}
}
echo $temp;
//echo $insertpartpay_sql2;	
?>
