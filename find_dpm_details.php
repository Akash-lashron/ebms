<?php
require_once 'library/config.php';
function dt_display($ddmmyyyy)
{
 $dt=explode('-',$ddmmyyyy);
 $dd=$dt[2];
 $mm=$dt[1];
 $yy=$dt[0];
 return $dd . '/' . $mm . '/' . $yy;
}
function checkPartpayment($sheetid,$itemid,$currentrbn,$mbid)
{
	$PayPercent = 0;
	$select_partpay_sql = "select mbtotal, pay_percent, rbn, part_pay_flag from measurementbook_temp where part_pay_flag != '0' AND part_pay_flag != '1' AND sheetid = '$sheetid' AND subdivid = '$itemid'";// AND rbn = '$currentrbn'";
	$select_partpay_query = mysql_query($select_partpay_sql);
	if(mysql_num_rows($select_partpay_query)>0)
	{
		while($List = mysql_fetch_object($select_partpay_query))
		{
			$PartpayFlag = $List->part_pay_flag;
			$ExplodePartpayFlag = explode("*",$PartpayFlag);
			$PartpayMbid 		= $ExplodePartpayFlag[2];
			if($mbid == $PartpayMbid)
			{
				$PayPercent = $List->pay_percent;
			}
			//$PartPayList .= $List->rbn."*".$List->mbtotal."*".$List->pay_percent."*";
		}
	}
	return $PayPercent;
}
$sheetid = $_GET['sheetid'];
$itemid = $_GET['itemid'];

$SlmDpmArrPercent 	= array();
$SlmDpmArrQty 		= array();
$SlmDpmArrRbn 		= array();
$SlmDpmArrMbId 		= array();
$SlmDpmArrDate 		= array();

$FlagArrPercent 	= array();
$FlagArrQty 		= array();
$FlagArrRbn 		= array();
$FlagArrMbId 		= array();
$DPM = ""; $percStr = "";
$dpm_details_sql = "select measurementbookid, DATE(measurementbookdate) AS measurementbookdate, mbtotal, pay_percent, part_pay_flag, rbn from measurementbook where sheetid = '$sheetid' AND subdivid = '$itemid' AND part_pay_flag != 'DMY' ORDER BY rbn ASC ";
$dpm_details_query = mysql_query($dpm_details_sql);
if(mysql_num_rows($dpm_details_query)>0)
{
	while($DPMList = mysql_fetch_object($dpm_details_query))
	{
		if(($DPMList->part_pay_flag == '0') || ($DPMList->part_pay_flag == '1'))
		{
			$DPM .= $DPMList->rbn."*".$DPMList->mbtotal."*".$DPMList->pay_percent."*".$DPMList->measurementbookid."*".$DPMList->measurementbookdate."*";
		}
		else
		{
			$DpmPercent = $DPMList->pay_percent;
			$DpmRbn 	= $DPMList->rbn;
			$DpmDate 	= $DPMList->measurementbookdate;
			if($DPMList->part_pay_flag != '')
			{
				$partpay_flag = $DPMList->part_pay_flag;
				$explodeFlag = explode("*", $partpay_flag);
				$RbnFlag 	= $explodeFlag[1];
				$MbIdFlag 	= $explodeFlag[2];
				array_push($FlagArrMbId,$MbIdFlag);
				array_push($SlmDpmArrPercent,$DpmPercent);
				array_push($SlmDpmArrRbn,$DpmRbn);
				array_push($SlmDpmArrDate,$DpmDate);
			}
		}
	}
	$DpmStrExplode = explode("*",rtrim($DPM,"*"));
	for($i=0; $i<count($DpmStrExplode); $i+=5)
	{
		$percentSum = 0; $SearchFlag = "";
		$ExplodeMbDate 	= $DpmStrExplode[$i+4];
		$ExplodeMbId 	= $DpmStrExplode[$i+3];
		$ExplodePercent = $DpmStrExplode[$i+2];
		$ExplodeQty		= $DpmStrExplode[$i+1];
		$ExplodeRbn		= $DpmStrExplode[$i+0];
		$percentSum = $ExplodePercent;
		for($j=0; $j<count($FlagArrMbId); $j++)
		{
			if($ExplodeMbId == $FlagArrMbId[$j])
			{
				$percentSum = $percentSum + $SlmDpmArrPercent[$j];
				//if($j == 0)
				//{
					$SearchFlag .= $ExplodePercent ."@". $ExplodeRbn ."@". dt_display($ExplodeMbDate) ."@";
				//}
				$SearchFlag .= $SlmDpmArrPercent[$j] ."@". $SlmDpmArrRbn[$j] ."@". dt_display($SlmDpmArrDate[$j]) ."@";
			}
		}
		if($SearchFlag == ""){ $SearchFlag = "X"; }
		$DpmSlmPercent = checkPartpayment($sheetid,$itemid,$ExplodeRbn,$ExplodeMbId);
		//$str .= $DpmSlmPercent."##";
		$OutputStr .= $ExplodeRbn."*".$ExplodeQty."*".$percentSum."*".$ExplodeMbId."*".$SearchFlag."*".$DpmSlmPercent."*";
	}
}
echo rtrim($OutputStr,"*");
//echo $str;
	
?>
