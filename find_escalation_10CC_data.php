<?php
require_once 'library/config.php';
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
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
    return $dd . '-' . $mm . '-' . $yy;
}
function GetAllEscMonth($fromdate,$todate)
{
	$MonthList 	= array();
	$time   	= strtotime($fromdate);
	$last   	= date('M-Y', strtotime($todate));
	while ($month != $last) 
	{
		$month 	= date('M-Y', $time);
		$total 	= date('t', $time);
		array_push($MonthList,$month);
		$time 	= strtotime('+1 month', $time);
	}
	return $MonthList;
}
function GetAllRbnMonth($fromdate,$todate)
{
	$MonthList 	= array();
	$time   	= strtotime($fromdate);
	$last   	= date('M-Y', strtotime($todate));
	while ($month != $last) 
	{
		$month 	= date('M-Y', $time);
		$total 	= date('t', $time);
		array_push($MonthList,$month);
		$time 	= strtotime('+1 month', $time);
	}
	return $MonthList;
}
function GetElecricityRecovery($sheetid,$rbn)
{
	$tot_amt = 0;
	$select_elec_rec_query = "select electricity_cost from generate_electricitybill where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_elec_rec_sql = mysql_query($select_elec_rec_query);
	if($select_elec_rec_sql == true)
	{
		if(mysql_num_rows($select_elec_rec_sql)>0)
		{
			while($EList = mysql_fetch_object($select_elec_rec_sql))
			{
				$amt = $EList->electricity_cost;
				$tot_amt = $tot_amt+$amt;
			}
		}
		else
		{
			$tot_amt = 0;
		}
	}
	else
	{
		$tot_amt = 0;
	}
	return $tot_amt;
}
function GetWaterRecovery($sheetid,$rbn)
{
	$tot_amt = 0;
	$select_water_rec_query = "select water_cost from generate_waterbill where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_water_rec_sql = mysql_query($select_water_rec_query);
	if($select_water_rec_sql == true)
	{
		if(mysql_num_rows($select_water_rec_sql)>0)
		{
			while($WList = mysql_fetch_object($select_water_rec_sql))
			{
				$amt = $WList->water_cost;
				$tot_amt = $tot_amt+$amt;
			}
		}
		else
		{
			$tot_amt = 0;
		}
	}
	else
	{
		$tot_amt = 0;
	}
	return $tot_amt;
}

/*function GetWaterRecovery($sheetid,$rbn)
{
	$tot_amt = 0;
	$select_water_rec_query = "select water_cost from generate_waterbill where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_water_rec_sql = mysql_query($select_water_rec_query);
	if($select_water_rec_sql == true)
	{
		if(mysql_num_rows($select_water_rec_sql)>0)
		{
			while($WList = mysql_fetch_object($select_water_rec_sql))
			{
				$amt = $WList->water_cost;
				$tot_amt = $tot_amt+$amt;
			}
		}
	}
	return round($tot_amt,2);
}*/
$sheetid	=  $_GET['sheetid'];
$type		=  $_GET['type'];
$bid		=  $_GET['bid'];
$from_date	=  $_GET['fromdate'];
$to_date	=  $_GET['todate'];
$quarter	=  $_GET['quarter'];
$fromdate 	=  dt_format($from_date);
$todate 	=  dt_format($to_date);

//$EscMonthArr 	=	array();
$EscTestArr1		 = array();
$EscTestArr2		 = array();
$MonRowSpanArr		 = array();
$EscMonthRowSpanList = array();
$RbnRowSpanList 	 = array();
$MonRowSpanList 	 = array();

$RbnMonthList 	 	 = array();
$MbookMonthList 	 = array();
$MbPageMonthList 	 = array();
$RbnAmtMonthList 	 = array();

$SameFromToMonData 	 = array();
$DiffFromToMonData 	 = array();

// Extraxt all month from the escalaton period and store it in array
$EscMonthList		=	GetAllEscMonth($fromdate,$todate);
$EscMonthCnt		=	count($EscMonthList);
for($i=0; $i<$EscMonthCnt; $i++)
{
	$escMonth 		= $EscMonthList[$i];
	// Initially set the value of escalation month is to zero for checking which month is exist in "abstract rbn"
	$EscTestArr1[$escMonth] 		= 0;
	// Initially set the value of escalation month is to one for calculate the month column rowspan.
	$EscMonthRowSpanList[$escMonth] = 1;
}


$fomdate_temp	=	strtotime($fromdate);
$from_mon_yr	=	date("M-Y",$fomdate_temp);

$todate_temp	=	strtotime($todate);
$to_mon_yr		=	date("M-Y",$todate_temp);

$diff_month_cnt 	= 0;
$total_rbn_amount 	= 0;
$month_cnt 			= 0;
$PiStr 				= "";
$CntStr 			= "";
$AbsStr 			= "";
$ExistMonthList			= array();
$NonExistMonthList		= array();
$RbnAllFromToMonthList 	= array();
$AbsBookIDList 			= array();


$select_abs_month_query = 	"SELECT * FROM abstractbook WHERE sheetid = '$sheetid' 
							and ((DATE(fromdate) BETWEEN '$fromdate' AND '$todate') OR (DATE(todate) BETWEEN '$fromdate' AND '$todate'))";
$select_abs_month_sql 	= 	mysql_query($select_abs_month_query);
//echo $select_abs_month_query;
if($select_abs_month_sql == true)
{
	if(mysql_num_rows($select_abs_month_sql)>0)
	{
		while($AbsList = mysql_fetch_object($select_abs_month_sql))
		{
			$absbookid  		= $AbsList->absbookid;
			//$rbn  				= $AbsList->rbn;
			$rbn_fromdate 		= $AbsList->fromdate;
			$rbn_todate 		= $AbsList->todate;
			//$mbookno 			= $AbsList->mbookno;
			//$mbookpage 			= $AbsList->mbookpage;
			//$rbn_amount 		= $AbsList->total_amount;
			//$rbn_amonut_85_perc = round($rbn_amount*85/100,2);
			//$total_rbn_amount 	= $total_rbn_amount + $rbn_amonut_85_perc;
			array_push($AbsBookIDList,$absbookid);
			// Change the Fromdate format from "yyyy-mm-dd" to "Mon-yyyy"
			$date_temp1			= strtotime($rbn_fromdate);
			$from_month_year	= date("M-Y",$date_temp1);
			// Change the Todate format from "yyyy-mm-dd" to "Mon-yyyy"
			$date_temp2			= strtotime($rbn_todate);
			$to_month_year		= date("M-Y",$date_temp2);
			// Check Month-Year of Fromdate and Todate is equal or not
			if($from_month_year == $to_month_year)
			{
				// If Fromdate date month and Two date month is same then assign any one one of them to the variable $rbn_month_year and store it in a array
				$rbn_month_year = $from_month_year;
				array_push($RbnAllFromToMonthList,$rbn_month_year);
			}
			else
			{
				// If Fromdate month and Todate month is different then extract all month between these two date in "Mon-yyyy" fromat
				$RbnMonthList	=	GetAllRbnMonth($rbn_fromdate,$rbn_todate);
				// Using for loop get each month and store it in a array for future purpose
				for($x1=0; $x1<count($RbnMonthList); $x1++)
				{
					$rbn_month_year = $RbnMonthList[$x1];
					array_push($RbnAllFromToMonthList,$rbn_month_year);
				}
			}
			
		}
	}
}


$RbnAllMonCnt 		= count($RbnAllFromToMonthList);
$UniqRbnMonthList 	= array_values(array_unique($RbnAllFromToMonthList)); // remove the mutiple entry of same month and get unique value. ( Note: array_values used to set array key value as array[0], array[1]....)
$UniqRbnMonthCnt 	= count($UniqRbnMonthList);
for($x2=0; $x2<$UniqRbnMonthCnt; $x2++)
{
	$rbn_month 	 	= $UniqRbnMonthList[$x2];
	if(in_array($rbn_month, $EscMonthList))  // Check Whether Month is exist in Escalation Period
	{
		array_push($ExistMonthList,$rbn_month);
		// Set the month rowspan value of each month.
		//$EscMonthRowSpanList[$rbn_month] = $MonthCount[$rbn_month];
	}
	else // Check Whether Month is not exist in Escalation Period.
	{
		array_push($NonExistMonthList,$rbn_month);
	}
}
$ExistMonthCnt 		= count($ExistMonthList);
$NonExistMonthCnt 	= count($NonExistMonthList);
if($NonExistMonthCnt>0)
{
	// Some month which is not exist in the period of escalation so we need to generate rough MBook, Abstract etc.
	$Error_msg = "Need to generate rough MBook, Abstract etc";
}
else
{
	$Error_msg = "";
}
// This part is very very important  - To get all data of rbn, abstarct mbook, page, and price index details etc.
if($ExistMonthCnt>0)
{
	//$ret = "Correct no need";
	$final_from 	= $ExistMonthList[0];
	$final_to 		= $ExistMonthList[$ExistMonthCnt-1];
	
	$final_from_temp 	= 	new DateTime($final_from);
	$final_to_temp 		= 	new DateTime($final_to);
	
	$final_from_date 	=	date_format($final_from_temp,'Y-m-d');
	$final_to_date 		=	date_format($final_to_temp,'Y-m-t');
		
	$select_rbndata_query = "select abstractbook.absbookid, abstractbook.sheetid, abstractbook.rbn, abstractbook.fromdate, abstractbook.todate, 
							abstractbook.mbookno,  abstractbook.mbookpage, abstractbook.slm_total_amount, abstractbook.secured_adv_amt,
							abstractbook.upto_date_total_amount, abstractbook.dpm_total_amount,
							abstractbook.slm_total_amount_esc, abstractbook.dpm_total_amount_esc, abstractbook.upto_date_total_amount_esc, 
							abstractbook.mbookno_esc, abstractbook.mbookpage_esc 
							from abstractbook where 
							abstractbook.sheetid = '$sheetid' 
							and abstractbook.fromdate >= '$final_from_date' and abstractbook.todate <= '$final_to_date'";
	$select_rbndata_sql = mysql_query($select_rbndata_query);
	if($select_rbndata_sql == true)
	{
		if(mysql_num_rows($select_rbndata_sql)>0)
		{
			while($RList = mysql_fetch_object($select_rbndata_sql))
			{
				$absbookid  			= $RList->absbookid;
				$rbn  					= $RList->rbn;
				$fromdate 				= $RList->fromdate;
				$todate 				= $RList->todate;
				$mbookno 				= $RList->mbookno;
				$mbookpage 				= $RList->mbookpage;
				$secured_adv_amt 		= 0;//$RList->secured_adv_amt;
				
				$rbn_amount 			= $RList->slm_total_amount;
				$upto_date_total_amount = $RList->upto_date_total_amount;
				$dpm_total_amount 		= $RList->dpm_total_amount;
				
				$rbn_amount2 			= $RList->slm_total_amount_esc;
				$upto_date_total_amount2 = $RList->upto_date_total_amount_esc;
				$dpm_total_amount2 		= $RList->dpm_total_amount_esc;
				
				$mbookno_esc 				= $RList->mbookno_esc;
				$mbookpage_esc 				= $RList->mbookpage_esc;
				if(($mbookno_esc != 0) && ($mbookno_esc != "")){ $mbookno = $mbookno_esc; }
				if(($mbookpage_esc != 0) && ($mbookpage_esc != "")){ $mbookpage = $mbookpage_esc; }
				
				if(($rbn_amount2 != 0) && ($rbn_amount2 != "")){ $rbn_amount = $rbn_amount2; }
				if(($upto_date_total_amount2 != 0) && ($upto_date_total_amount2 != "")){ $upto_date_total_amount = $upto_date_total_amount2; }
				if(($dpm_total_amount2 != 0) && ($dpm_total_amount2 != "")){ $dpm_total_amount = $dpm_total_amount2; }
				
				$rbn_amonut_85_perc 	= round($rbn_amount*85/100,2);
				$total_rbn_amount 		= $total_rbn_amount + $rbn_amonut_85_perc;
				if($upto_date_total_amount == 0)
				{
					$upto_date_total_amount = "Z";
				}
				if($dpm_total_amount == 0)
				{
					$dpm_total_amount = "Z";
				}
				if($secured_adv_amt == 0)
				{
					$secured_adv_amt = "Z";
				}
				if($rbn_amount == 0)
				{
					$rbn_amount = "Z";
				}
				
				$elec_rec 	= 	GetElecricityRecovery($sheetid,$rbn);
				//$ret .= $rbn."<br/>";
				if($elec_rec == 0)
				{
					$elec_rec = "Z";
				}
				$water_rec 	= GetWaterRecovery($sheetid, $rbn);
				if($water_rec == 0)
				{
					$water_rec = "Z";
				}
				//$ret .= $water_rec."<br/>";
				$date_temp3			= strtotime($fromdate);
				$from_mon_yr		= date("M-Y",$date_temp3);
			
				$date_temp4			= strtotime($todate);
				$to_mth_yr			= date("M-Y",$date_temp4);
				if($from_mon_yr == $to_mth_yr)
				{
					$mon_yr = $from_mon_yr;
					// Change the below array value from 0 to 1, to find out the which month price index is calculated.
					$EscTestArr1[$mon_yr] = 1;
					//$MonRowSpanArr[$rbn] = 1 ///10 o clock comment
					$MonRowSpanArr[$mon_yr] = 1;
					// Get base index and price index for this month
					$RbnRowSpan = 1;
					// Following Two arrays are for calculating Rbn Rows Span and Month Rows Span Calculation Respectively
					array_push($RbnRowSpanList,$rbn);
					array_push($MonRowSpanList,$mon_yr);
					// Following arrays are to get rbn, mbookno, page, amount and 85% amt etc...
					$RbnMonthList[$mon_yr] 		= $rbn;
					$MbookMonthList[$mon_yr] 	= $mbookno;
					$MbPageMonthList[$mon_yr] 	= $mbookpage;
					$RbnAmtMonthList[$mon_yr] 	= $rbn_amount;
					
					array_push($SameFromToMonData,$mon_yr);
					array_push($SameFromToMonData,$rbn);
					array_push($SameFromToMonData,$mbookno);
					array_push($SameFromToMonData,$mbookpage);
					array_push($SameFromToMonData,$rbn_amount);
					array_push($SameFromToMonData,$secured_adv_amt);
					array_push($SameFromToMonData,$elec_rec);
					array_push($SameFromToMonData,$water_rec);
					array_push($SameFromToMonData,$upto_date_total_amount);
					array_push($SameFromToMonData,$dpm_total_amount);
					//$RbnAmtMonthList[$mon_yr] 	= $rbn;
				}
				else
				{
					//$RbnMonYrList	=	GetAllRbnMonth($from_mon_yr,$to_mth_yr); ///10 o clock comment
					$RbnMonYrList	=	GetAllRbnMonth($fromdate,$todate);
					$RbnMonYrCnt	=	count($RbnMonYrList);
					for($x3=0; $x3<$RbnMonYrCnt; $x3++)
					{
						$mon_yr = $RbnMonYrList[$x3];
						// Change the below array value from 0 to 1, to find out the which month price index is calculated.
						$EscTestArr1[$mon_yr] = 1;
						if($mon_yr == $from_mon_yr)
						{
							$MonRowSpanArr[$mon_yr] = $RbnMonYrCnt;
							array_push($SameFromToMonData,$mon_yr);
							array_push($SameFromToMonData,$rbn);
							array_push($SameFromToMonData,$mbookno);
							array_push($SameFromToMonData,$mbookpage);
							array_push($SameFromToMonData,$rbn_amount);
							array_push($SameFromToMonData,$secured_adv_amt);
							array_push($SameFromToMonData,$elec_rec);
							array_push($SameFromToMonData,$water_rec);
							array_push($SameFromToMonData,$upto_date_total_amount);
							array_push($SameFromToMonData,$dpm_total_amount);
						}
						else
						{
							$MonRowSpanArr[$mon_yr] = 0;
							array_push($SameFromToMonData,$mon_yr);
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
						}
						// Following Two arrays are for calculating Rbn Rows Span and Month Rows Span Calculation Respectively
						array_push($RbnRowSpanList,$rbn);
						array_push($MonRowSpanList,$mon_yr);
						// Following arrays are to get rbn, mbookno, page, amount and 85% amt etc...
						$RbnMonthList[$mon_yr] 		= $rbn;
						$MbookMonthList[$mon_yr] 	= $mbookno;
						$MbPageMonthList[$mon_yr] 	= $mbookpage;
						$RbnAmtMonthList[$mon_yr] 	= $rbn_amount;
						
						//$RbnAmtMonthList[$mon_yr] 	= $rbn;
						//Get base index and price index for each month
					}
					$RbnRowSpan = $RbnMonYrCnt;
					//$MonRowSpanArr[$rbn] = $RbnMonYrCnt."*".$from_mon_yr."*".$to_mth_yr; ///10 o clock comment
				}
				$RbnRowSpanStr .= $rbn."*".$RbnRowSpan."@@";
			}
		}
	}
}

//print_r($SameFromToMonData);exit;
//MONTH ROW SPAN Calculation Part
// Count the number of occurrance of each month in following array to fin out the MONTH row span count.
$MonthCount = array_count_values($MonRowSpanList);
for($x5=0; $x5<$EscMonthCnt; $x5++)
{
	$rowspan_rbn_month 	 	= $EscMonthList[$x5];
	if(in_array($rowspan_rbn_month, $MonRowSpanList))
	{
		$EscMonthRowSpanList[$rowspan_rbn_month] = $MonthCount[$rowspan_rbn_month];
	}
}
//RBN ROW SPAN Calculation Part
$RbnCount = array_count_values($RbnRowSpanList);

$overall_month_rowspan = array_sum($EscMonthRowSpanList);
$overall_rbn_rowspan = array_sum($EscMonthRowSpanList);
if($overall_month_rowspan > $overall_rbn_rowspan)
{
	$overall_rowspan = $overall_month_rowspan;
}
else
{
	$overall_rowspan = $overall_rbn_rowspan;
}
// Get Base Index and all Price Index details - Section
$BiStr = ""; $RbnOutStr = "";
for($x4=0; $x4<$EscMonthCnt; $x4++)
{
	$esc_Month 		= $EscMonthList[$x4];
	$select_tcc_query 	= 	"select base_index.bid, base_index.base_index_item, base_index.base_index_code, base_index.base_index_rate,
							base_index.base_breakup_code, base_index.base_breakup_perc, price_index.pi_from_date, price_index.pi_to_date, 
							price_index.avg_pi_code, price_index.avg_pi_rate,
							price_index_detail.pi_month, price_index_detail.pi_rate, price_index.pid, price_index.quarter
							from base_index
							INNER JOIN price_index ON (price_index.bid = base_index.bid)
							INNER JOIN price_index_detail ON (price_index_detail.pid = price_index.pid)
							WHERE base_index.active=1 AND price_index_detail.pid = price_index.pid
							AND price_index.sheetid = '$sheetid' AND base_index.sheetid = '$sheetid' 
							AND base_index.bid = '$bid' AND price_index.bid = '$bid'
							AND price_index_detail.pi_month = '$esc_Month' and price_index.quarter = '$quarter'";
	$select_tcc_sql 	= 	mysql_query($select_tcc_query);
	if($select_tcc_sql == true)
	{
		$TCCList = mysql_fetch_object($select_tcc_sql);
		$base_index_item 	= $TCCList->base_index_item;
		$base_index_code 	= $TCCList->base_index_code;
		$base_index_rate 	= $TCCList->base_index_rate;
		$base_breakup_code 	= $TCCList->base_breakup_code;
		$base_breakup_perc 	= $TCCList->base_breakup_perc;
		$avg_pi_code 	 	= $TCCList->avg_pi_code;
		$avg_pi_rate 	 	= $TCCList->avg_pi_rate;
		$pi_rate 	 		= $TCCList->pi_rate;
		$pi_month 		 	= $TCCList->pi_month;
		$bid 		 	 	= $TCCList->bid;
		$pid 		 	 	= $TCCList->pid;
		$month_row_span 	= $EscMonthRowSpanList[$pi_month];
		if(($bid != "") && ($pid != "") && ($avg_pi_rate != ""))
		{
			$text = str_replace(' ', '*', $BiStr);
			if(trim($text) == "")
			{
				$BiStr = $pi_month."*".$bid."*".$base_index_item."*".$base_index_code."*".$base_index_rate."*".$base_breakup_code."*".$base_breakup_perc."*".$avg_pi_code."*".$avg_pi_rate."*".$month_row_span;
			}
		}
		/*$rbn 				= $RbnMonthList[$pi_month];
		if($rbn == "")
		{
			$rbn_row_span = 1;
		}
		else
		{
			$rbn_row_span 	= $RbnCount[$rbn];
		}
		$mbookno 		= $MbookMonthList[$pi_month];
		$mbookpage 		= $MbPageMonthList[$pi_month];
		$rbn_amount 	= $RbnAmtMonthList[$pi_month];*/
		//$outStr = ""; $os = 0;
		//print_r($new_arr);exit;
		if(in_array($pi_month, $SameFromToMonData))
		{
			$stre .= $pi_month."@";
			for($x6=0; $x6<count($SameFromToMonData); $x6+=10)
			{
				$abs_mon_yr 	= $SameFromToMonData[$x6+0];
				$abs_rbn 		= $SameFromToMonData[$x6+1];
				$abs_mbookno 	= $SameFromToMonData[$x6+2];
				$abs_mbpage 	= $SameFromToMonData[$x6+3];
				$abs_rbn_amt 	= $SameFromToMonData[$x6+4];
				$abs_sa_amt 	= $SameFromToMonData[$x6+5];
				$abs_er_amt 	= $SameFromToMonData[$x6+6];
				$abs_wr_amt 	= $SameFromToMonData[$x6+7];
				$abs_upto_date_amt 	= $SameFromToMonData[$x6+8];
				$abs_dpm_amt 	= $SameFromToMonData[$x6+9];
				if($abs_er_amt == "Z")
				{
					$abs_er_amt = 0;
				}
				//$abs_wr_amt 	= $SameFromToMonData[$x6+7];
				if($abs_wr_amt == "Z")
				{
					$abs_wr_amt = 0;
				}
				if($abs_upto_date_amt == "Z")
				{
					$abs_upto_date_amt = 0;
				}
				if($abs_dpm_amt == "Z")
				{
					$abs_dpm_amt = 0;
				}
				if($abs_sa_amt == "Z")
				{
					$abs_sa_amt = 0;
				}
				if($abs_rbn_amt == "Z")
				{
					$abs_rbn_amt = 0;
				}
				
				$abs_rbn_amt_85perc = round(($abs_rbn_amt*85/100),2);
				if($RbnCount[$abs_rbn] != "")
				{
					$rbn_row_span 	= $RbnCount[$abs_rbn];
				}
				else
				{
					$rbn_row_span = 1;
				}
				if($abs_mon_yr == $pi_month)
				{
					$RbnOutStr .= $abs_mon_yr."*".$abs_rbn."*".$abs_mbookno."*".$abs_mbpage."*".$abs_rbn_amt."*".$abs_rbn_amt_85perc."*".$abs_sa_amt."*".$abs_er_amt."*".$abs_wr_amt."*".$rbn_row_span."*".$month_row_span."*".$abs_upto_date_amt."*".$abs_dpm_amt."##";
					$os++;
				}
			}
		}
		else
		{
			//$RbnOutStr .= $pi_month."*"."X"."*"."X"."*"."X"."*"."X"."*"."1XX"."##";
			$RbnOutStr .= $pi_month."*".""."*".""."*".""."*".""."*".""."*".""."*".""."*".""."*"."1"."*".$month_row_span."*".""."*".""."##";
			//$RbnOutStr1 .= $pi_month."*".""."*".""."*".""."*".""."*".""."*".""."*".""."*".""."*"."1"."*".$month_row_span."##";
		}
		//if($outStr == "")
		//{
			//$outStr .= "X"."*"."X"."*"."X"."*"."X"."*"."X"."##";
		//}
		
	$piStr .= $pi_month."*".$base_index_item."*".$base_index_code."*".$pi_rate."*".$pid."*".$month_row_span."##";
	}
}

$TCCStr = rtrim($RbnOutStr,"##")."@@##@@".rtrim($piStr,"##")."@@##@@".$BiStr."@@##@@".$overall_rowspan."@@##@@".$EscMonthCnt."@@##@@".$total_rbn_amount;
//echo $ret;
//$RbnRowSpanArr = array_count_values($RbnRowSpanList);
//print_r($occurences);
//echo $RbnRowSpanStr;
//echo $select_rbndata_query;

echo $TCCStr;

//print_r($SameFromToMonData); 


//print_r($ExistMonthList);
//echo $final_from_month."#######".$final_to_month;

/*// Check for Escalation period month and All rbn month (Unique Month - Removal of mutiple entery of same value in array) is equal or not 
	
if($UniqRbnMonthCnt == $EscMonthCnt) // Check for Unique rbn month and Escalation period month is equal
{
	// Esc period and Rbn period is same. Then no problem so no need to generate rough MBook, Abstract etc. 
	$ret = "equal";
}
else if($UniqRbnMonthCnt < $EscMonthCnt) 	// Check for Unique rbn month is less than Escalation period month.
{
	// Esc period and Rbn period is same. Then no problem so no need to generate rough MBook, Abstract etc. 
	$ret = "Less than";
}
else 		// Check for Unique rbn month is greater than Escalation period month.
{
	for($x2=0; $x2<$RbnAllMonCnt; $x2++)
	{
		if(in_array($rbn_month, $EscMonthList))
		{
			// Month is exist in Escalation Period
		}
		else
		{
			// Month is not exist in Escalation Period. So we need to split the Rbn So we need to generate rough MBook, Abstract etc.
		}
	}
	$ret = "Greater than";
}*/
	
			//$RbnMonthList	=	GetAllRbnMonth($rbn_fromdate,$rbn_todate);
			
/*			if($from_month_year != $to_month_year)
			{
				$diff_month_cnt++;
				$RbnMonthList1	=	GetAllRbnMonth($rbn_fromdate,$rbn_todate);
				$RbnMonthCnt	=	count($RbnMonthList1);
				$RbnMonCheck	=	0;
				for($x1=0; $x1<$RbnMonthCnt; $x1++)
				{
					$RbnMonth 	= $RbnMonthList1[$x1];
					if(in_array($RbnMonth, $EscMonthList)) // Check RBN Month is exist in Escalation Period. This is for if RBN generated in mre than one month.
					{ 
						$RbnMonCheck++;// Month is exist in Escalation Month Period
						array_push($ExistMonthList,$RbnMonth);
					}
					else
					{
						array_push($NonExistMonthList,$RbnMonth);
					}
				}
				if($RbnMonCheck == $RbnMonthCnt)
				{
					// All Rbn Month is exist in Escalation Period.
				}
				else
				{
					// Some Rbn Month is not exist in Escalation Period.
				}
			}
			else
			{
				
			}
			$AbsStr .= $rbn."@*@".$mbookno."@*@".$mbookpage."@*@".$rbn_amount."@*@".$pi_month."@*@".$pi_rate."@*@".$bid."@*@".$pid."@*@";
		}










$select_abstract_query 	= 	"SELECT * FROM abstractbook WHERE sheetid = '$sheetid' 
							and ((DATE(fromdate) BETWEEN '$fromdate' AND '$todate') OR (DATE(todate) BETWEEN '$fromdate' AND '$todate'))";
$select_abstract_sql 	= 	mysql_query($select_abstract_query);
if($select_abstract_sql == true)
{
	if(mysql_num_rows($select_abstract_sql)>0)
	{
		while($AbsList = mysql_fetch_object($select_abstract_sql))
		{
			$rbn  				= $AbsList->rbn;
			$rbn_fromdate 		= $AbsList->fromdate;
			$rbn_todate 		= $AbsList->todate;
			$mbookno 			= $AbsList->mbookno;
			$mbookpage 			= $AbsList->mbookpage;
			$rbn_amount 		= $AbsList->slm_total_amount;
			$rbn_amonut_85_perc = round($rbn_amount*85/100,2);
			$total_rbn_amount =  $total_rbn_amount + $rbn_amonut_85_perc;
			
			//for($x1=0; $x1<count($MonthList); $x1++)
			//{
				//$MonthStr1 .= $MonthList[$x1]."*";
			//}
			//$MonthStr = $MonthStr."@@".rtrim($MonthStr1,"*");
			//$MonthStr1 = "";
			$date_temp1			= strtotime($rbn_fromdate);
			$from_month_year	= date("M-Y",$date_temp1);
			
			$date_temp2			= strtotime($rbn_todate);
			$to_month_year		= date("M-Y",$date_temp2);
			if($from_month_year != $to_month_year)
			{
				$diff_month_cnt++;
				$RbnMonthList	=	GetAllRbnMonth($rbn_fromdate,$rbn_todate);
				$RbnMonthCnt	=	count($RbnMonthList);
				$RbnMonCheck	=	0;
				for($x1=0; $x1<$RbnMonthCnt; $x1++)
				{
					$RbnMonth 	= $RbnMonthList[$x1];
					if(in_array($RbnMonth, $EscMonthList)) // Check RBN Month is exist in Escalation Period. This is for if RBN generated in mre than one month.
					{ 
						$RbnMonCheck++;// Month is exist in Escalation Month Period
						array_push($ExistMonthList,$RbnMonth);
					}
					else
					{
						array_push($NonExistMonthList,$RbnMonth);
					}
				}
				if($RbnMonCheck == $RbnMonthCnt)
				{
					// All Rbn Month is exist in Escalation Period.
				}
				else
				{
					// Some Rbn Month is not exist in Escalation Period.
				}
			}
			else
			{
				$month_year = $from_month_year;
				array_push($ExistMonthList,$month_year);
				$select_tcc_query 	= 	"select base_index.bid, base_index.base_index_item, base_index.base_index_code, base_index.base_index_rate,
										base_index.base_breakup_code, base_index.base_breakup_perc, price_index.pi_from_date, price_index.pi_to_date, 
										price_index.avg_pi_code, price_index.avg_pi_rate,
										price_index_detail.pi_month, price_index_detail.pi_rate, price_index.pid
										from base_index
										INNER JOIN price_index ON (price_index.bid = base_index.bid)
										INNER JOIN price_index_detail ON (price_index_detail.pid = price_index.pid)
										WHERE base_index.active=1 AND price_index_detail.pid = price_index.pid
										AND price_index.sheetid = '$sheetid' AND base_index.sheetid = '$sheetid' 
										AND base_index.bid = '$bid' AND price_index.bid = '$bid'
										AND price_index_detail.pi_month = '$month_year'";
				$select_tcc_sql 	= 	mysql_query($select_tcc_query);
				if($select_tcc_sql == true)
				{
					$TCCList = mysql_fetch_object($select_tcc_sql);
					$base_index_item 	= $TCCList->base_index_item;
					$base_index_code 	= $TCCList->base_index_code;
					$base_index_rate 	= $TCCList->base_index_rate;
					$base_breakup_code 	= $TCCList->base_breakup_code;
					$base_breakup_perc 	= $TCCList->base_breakup_perc;
					$avg_pi_code 	 	= $TCCList->avg_pi_code;
					$avg_pi_rate 	 	= $TCCList->avg_pi_rate;
					$pi_rate 	 		= $TCCList->pi_rate;
					$pi_month 		 	= $TCCList->pi_month;
					$bid 		 	 	= $TCCList->bid;
					$pid 		 	 	= $TCCList->pid;
				}
				//$qur .= $select_tca_query."</br>";
				$month_cnt++;
			}
			$AbsStr .= $rbn."@*@".$mbookno."@*@".$mbookpage."@*@".$rbn_amount."@*@".$pi_month."@*@".$pi_rate."@*@".$bid."@*@".$pid."@*@";
		}
		$PiStr  = $base_index_item."@*@".$base_index_code."@*@".$base_index_rate."@*@".$avg_pi_code."@*@".$avg_pi_rate."@*@".$total_rbn_amount."@*@".$base_breakup_code."@*@".$base_breakup_perc;
		$CntStr = $diff_month_cnt."@*@".$month_cnt;
		$TCCStr = $CntStr."@@##@@".rtrim($AbsStr,"@*@")."@@##@@".$PiStr;
	}
	else
	{
		$TCCStr = "";
	}
}
else
{
	$TCCStr = "";
}
$Mon = "";
for($k=0; $k<count($ExistMonthList); $k++)
{
	$Mon .= $ExistMonthList[$k]."*";
}

$Mon1 = "";
for($l=0; $l<count($NonExistMonthList); $l++)
{
	$Mon1 .= $NonExistMonthList[$l]."*";
}
echo $select_abstract_query_1;*/
?>


<?php
/*require_once 'library/config.php';
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
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
    return $dd . '-' . $mm . '-' . $yy;
}
$sheetid	=  $_GET['sheetid'];
$type		=  $_GET['type'];
$bid		=  $_GET['bid'];
$from_date	=  $_GET['fromdate'];
$to_date	=  $_GET['todate'];
$fromdate 	=  dt_format($from_date);
$todate 	=  dt_format($to_date);

$fomdate_temp	=	strtotime($fromdate);
$from_mon_yr	=	date("M-Y",$fomdate_temp);

$todate_temp	=	strtotime($todate);
$to_mon_yr		=	date("M-Y",$todate_temp);

$diff_month_cnt 	= 0;
$total_rbn_amount 	= 0;
$month_cnt 			= 0;
$PiStr 				= "";
$CntStr 			= "";
$AbsStr 			= "";

$select_abstract_query 	= 	"SELECT * FROM abstractbook WHERE sheetid = '$sheetid' 
							and (DATE(fromdate) BETWEEN '$fromdate' AND '$todate') and (DATE(todate) BETWEEN '$fromdate' AND '$todate')";
$select_abstract_sql 	= 	mysql_query($select_abstract_query);
if($select_abstract_sql == true)
{
	if(mysql_num_rows($select_abstract_sql)>0)
	{
		while($AbsList = mysql_fetch_object($select_abstract_sql))
		{
			$rbn  				= $AbsList->rbn;
			$fromdate 			= $AbsList->fromdate;
			$todate 			= $AbsList->todate;
			$mbookno 			= $AbsList->mbookno;
			$mbookpage 			= $AbsList->mbookpage;
			$rbn_amount 		= $AbsList->slm_total_amount;
			$rbn_amonut_85_perc = round($rbn_amount*85/100,2);
			$total_rbn_amount =  $total_rbn_amount + $rbn_amonut_85_perc;
			
			$date_temp1			= strtotime($fromdate);
			$from_month_year	= date("M-Y",$date_temp1);
			
			$date_temp2			= strtotime($todate);
			$to_month_year		= date("M-Y",$date_temp2);
			if($from_month_year != $to_month_year)
			{
				$diff_month_cnt++;
			}
			else
			{
				$month_year = $from_month_year;
				$select_tcc_query 	= 	"select base_index.bid, base_index.base_index_item, base_index.base_index_code, base_index.base_index_rate,
										base_index.base_breakup_code, base_index.base_breakup_perc, price_index.pi_from_date, price_index.pi_to_date, 
										price_index.avg_pi_code, price_index.avg_pi_rate,
										price_index_detail.pi_month, price_index_detail.pi_rate, price_index.pid
										from base_index
										INNER JOIN price_index ON (price_index.bid = base_index.bid)
										INNER JOIN price_index_detail ON (price_index_detail.pid = price_index.pid)
										WHERE base_index.active=1 AND price_index_detail.pid = price_index.pid
										AND price_index.sheetid = '$sheetid' AND base_index.sheetid = '$sheetid' 
										AND base_index.bid = '$bid' AND price_index.bid = '$bid'
										AND price_index_detail.pi_month = '$month_year'";
				$select_tcc_sql 	= 	mysql_query($select_tcc_query);
				if($select_tcc_sql == true)
				{
					$TCCList = mysql_fetch_object($select_tcc_sql);
					$base_index_item 	= $TCCList->base_index_item;
					$base_index_code 	= $TCCList->base_index_code;
					$base_index_rate 	= $TCCList->base_index_rate;
					$base_breakup_code 	= $TCCList->base_breakup_code;
					$base_breakup_perc 	= $TCCList->base_breakup_perc;
					$avg_pi_code 	 	= $TCCList->avg_pi_code;
					$avg_pi_rate 	 	= $TCCList->avg_pi_rate;
					$pi_rate 	 		= $TCCList->pi_rate;
					$pi_month 		 	= $TCCList->pi_month;
					$bid 		 	 	= $TCCList->bid;
					$pid 		 	 	= $TCCList->pid;
				}
				//$qur .= $select_tca_query."</br>";
				$month_cnt++;
			}
			$AbsStr .= $rbn."@*@".$mbookno."@*@".$mbookpage."@*@".$rbn_amount."@*@".$pi_month."@*@".$pi_rate."@*@".$bid."@*@".$pid."@*@";
		}
		$PiStr  = $base_index_item."@*@".$base_index_code."@*@".$base_index_rate."@*@".$avg_pi_code."@*@".$avg_pi_rate."@*@".$total_rbn_amount."@*@".$base_breakup_code."@*@".$base_breakup_perc;
		$CntStr = $diff_month_cnt."@*@".$month_cnt;
		$TCCStr = $CntStr."@@##@@".rtrim($AbsStr,"@*@")."@@##@@".$PiStr;
	}
	else
	{
		$TCCStr = "";
	}
}
else
{
	$TCCStr = "";
}
echo $TCCStr;*/
?>