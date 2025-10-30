<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
$msg = '';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
$success = 0; $failure = 0;
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
$sheetid  		= $_SESSION['escal_sheetid'];
$from_date  	= $_SESSION['escal_from_date'];
$to_date  		= $_SESSION['escal_to_date'];
$sc_quarter  	= $_SESSION['escal_quarter'];
$sc_esc_rbn 	= $_SESSION['escal_rbn'];
$sc_esc_id 		= $_SESSION['escal_esc_id'];
$sc_mbookno  	= $_SESSION['sc_mbook_no'];
$sc_mbookpageno = $_SESSION['sc_mbook_pageno'];
$sc_mbid 		= $_SESSION['sc_mbook_id'];
$start_page = $sc_mbookpageno;

$old_mb_no 		= $sc_mbookno;
$old_mb_page 	= $sc_mbookpageno;

$MonthList = array(); $MonthYrArr = array();
if(($from_date != "") && ($to_date != ""))
{
	$time1   = strtotime($from_date);
	$last1   = date('F', strtotime($to_date));
	while ($month1 != $last1) 
	{
		$month1 = date('F', $time1);
		$total1 = date('t', $time1);
		array_push($MonthList,$month1);
		$MonYr = date('M-Y', $time1);

		if(in_array($MonYr,$MonthYrArr)){
		
		}else{
			array_push($MonthYrArr,$MonYr);
		}
		$time1 = strtotime('+1 month', $time1);
	}
}

if(($from_date != "")&&($to_date != ""))
{
	$fromdate 	= dt_format($from_date);
	$todate 	= dt_format($to_date);
}
/*$escal_fromdate_query = "select work_order_date from sheet where sheet_id = '$sheetid'";
$escal_fromdate_sql = mysql_query($escal_fromdate_query);
if($escal_fromdate_sql == true)
{
	$MinDateList = mysql_fetch_object($escal_fromdate_sql);
	$min_date = $MinDateList->work_order_date;
}
//$min_date = '2016-07-14';
$start_month_ts = strtotime("+1 month",strtotime($min_date));
$start_month 	= date('Y-m-d', $start_month_ts);
$fromdate 		= date("Y-m-01", strtotime($start_month));

$end_month_ts 	= strtotime("+3 month",strtotime($min_date));
$end_month 		= date('Y-m-d', $end_month_ts);
$todate 		= date("Y-m-t", strtotime($end_month));*/


/*$escal_measure_query = 	"SELECT mbookheader.mbheaderid, DATE(mbookheader.date) as mdate, mbookheader.sheetid, 
							mbookheader.subdivid, mbookheader.subdiv_name, mbookheader.zone_id, 
							mbookdetail.mbheaderid, mbookdetail.subdivid, mbookdetail.subdiv_name, mbookdetail.descwork, mbookdetail.measurement_no,
							mbookdetail.measurement_l, mbookdetail.measurement_b, mbookdetail.measurement_dia, mbookdetail.measurement_d, 
							mbookdetail.measurement_contentarea, mbookdetail.remarks, mbookdetail.zone_id, schdule.sno, schdule.tc_unit, 
							schdule.measure_type, schdule.subdiv_id, schdule.per, schdule.decimal_placed, schdule.description, schdule.shortnotes,
							schdule.total_quantity, schdule.deviate_qty_percent
							FROM mbookheader
							INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
							INNER JOIN schdule ON (mbookheader.subdivid = schdule.subdiv_id)
							WHERE schdule.measure_type = 's' AND mbookheader.sheetid = '$sheetid' 
							AND (mbookheader.date BETWEEN '$fromdate' AND '$todate') 
							AND schdule.sheet_id = '$sheetid'
							AND mbookdetail.mbdetail_flag != 'd'
							ORDER BY mbookheader.subdivid ASC, mbookheader.date ASC";*/
$escal_measure_query = 	"SELECT mbookheader.mbheaderid, DATE(mbookheader.date) as mdate, mbookheader.sheetid, 
							mbookheader.subdivid, mbookheader.subdiv_name, mbookheader.zone_id, 
							mbookdetail.mbheaderid, mbookdetail.subdivid, mbookdetail.subdiv_name, mbookdetail.descwork, mbookdetail.measurement_no, mbookdetail.measurement_no2,
							mbookdetail.measurement_l, mbookdetail.measurement_b, mbookdetail.measurement_dia, mbookdetail.measurement_d, 
							mbookdetail.measurement_contentarea, mbookdetail.remarks, mbookdetail.zone_id, schdule.sno, schdule.tc_unit, 
							schdule.measure_type, schdule.subdiv_id, schdule.per, schdule.decimal_placed, schdule.description, schdule.shortnotes,
							schdule.total_quantity, schdule.deviate_qty_percent, schdule.item_flag
							FROM mbookheader
							INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
							INNER JOIN schdule ON (mbookheader.subdivid = schdule.subdiv_id)
							WHERE schdule.measure_type = 's' AND mbookheader.sheetid = '$sheetid' 
							AND (mbookheader.date BETWEEN '$fromdate' AND '$todate') 
							AND schdule.sheet_id = '$sheetid' AND (schdule.item_flag = 'NI' OR schdule.escalation_flag = 'Y')
							AND mbookdetail.mbdetail_flag != 'd' AND schdule.sub_type != 'c' AND schdule.subdiv_id != '0' AND schdule.mat_code = 'SSTE'
							AND NOT EXISTS 
							( SELECT esc_consumption_10ca.subdivid FROM esc_consumption_10ca WHERE esc_consumption_10ca.subdivid = mbookheader.subdivid 
							AND esc_consumption_10ca.mdate<'$fromdate' AND esc_consumption_10ca.sheetid = '$sheetid' AND esc_consumption_10ca.dev_flag = 'Y'
							AND esc_consumption_10ca.esc_item_type = 'STL')
							ORDER BY mbookheader.subdivid ASC, mbookheader.date ASC";							
$escal_measure_sql = mysql_query($escal_measure_query);
//echo $escal_measure_query;

function get_mbook_page_rbn($sheetid, $zone_id, $subdivid, $month, $year)
{
	$select_mbook_data_query = "select mbno, mbpage, rbn from mbookgenerate_staff where sheetid = '$sheetid' and zone_id = '$zone_id' 
								and subdivid = '$subdivid' and YEAR(fromdate)<='$year' and MONTH(fromdate)<='$month' and 
								YEAR(todate)>='$year' and MONTH(todate)>='$month' ORDER BY fromdate LIMIT 1";
	$select_mbook_data_sql = mysql_query($select_mbook_data_query);
	if($select_mbook_data_sql == true)
	{
		if(mysql_num_rows($select_mbook_data_sql)>0)
		{
			$MBList = mysql_fetch_object($select_mbook_data_sql);
			$mbookno = $MBList->mbno;
			$mbpage = $MBList->mbpage;
			$rbn = $MBList->rbn;
			$DataStr = $mbookno."*".$mbpage."*".$rbn;
		}
		else
		{
			$DataStr = "";
		}
	}
	else
	{
		$DataStr = "";
	}
	return $DataStr;
}

$select_sheet_query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
$select_sheet_sql 			= 	mysql_query($select_sheet_query);
if ($select_sheet_sql == true) 
{
    $List 					= 	mysql_fetch_object($select_sheet_sql);
    $work_name 				= 	$List->work_name; 
	$short_name 			= 	$List->short_name;   
	$tech_sanction 			= 	$List->tech_sanction;  
    $name_contractor 		= 	$List->name_contractor; 
	$ccno 					= 	$List->computer_code_no;    
	$agree_no 				= 	$List->agree_no; 
	$overall_rebate_perc 	= 	$List->rebate_percent; 
	$runn_acc_bill_no 		= 	$rbn;
	$work_order_no 			= 	$List->work_order_no; /*   if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
	$length1 				= 	strlen($work_name);
 	$start_line1 			= 	ceil($length1/70); 
	$length2 				= 	strlen($agree_no);
	$start_line2 			= 	ceil($length2/27);  
	$WrapReturn1 = getWordWrapCount($List->work_name,65);
	$work_name = $WrapReturn1[0];
	$wrap_cnt = $WrapReturn1[1];
	$LineIncr 				= 	$start_line1 + $wrap_cnt + 2 + 3;  
}
$line = 0;
$line = $line+$LineIncr;
function getWordWrapCount($description,$char)
{
	$wrap_cnt 	= 0; 
	$descwork 	= "";
	$char_no 	= $char;
	$work_desc 	= $description;
	$desc 		= wordwrap($work_desc,$char_no,'<br>');
	$exp_line 	= explode('<br>', $desc);
	$wlcnt 		= count($exp_line);
	for($xc=0; $xc<$wlcnt; $xc++)
	{
		if($exp_line[$xc] != "")
		{
			$wrap_cnt++;
			$descwork .= $exp_line[$xc]."<br/> ";
		}
	}
	return array($descwork, $wrap_cnt);
}
function GetUsedQty($subdivid,$sheetid,$fromdate,$decimal_placed)
{
	$usedQty = 0; $tot_8 = 0; $tot_10 = 0; $tot_12 = 0; $tot_16 = 0; $tot_20 = 0; $tot_25 = 0; $tot_28 = 0; $tot_32 = 0; $tot_36 = 0;
	$tot_weight_8 = 0; $tot_weight_10 = 0; $tot_weight_12 = 0; $tot_weight_16 = 0; $tot_weight_20 = 0; $tot_weight_25 = 0; 
	$tot_weight_28 = 0; $tot_weight_32 = 0; $tot_weight_36 = 0;
	$select_qty_query =  "select mbookheader.mbheaderid, DATE(mbookheader.date) as mdate, mbookheader.sheetid, 
						 mbookheader.subdivid, mbookdetail.mbheaderid, mbookdetail.subdivid, mbookdetail.measurement_no, mbookdetail.measurement_no2,
						 mbookdetail.measurement_l, mbookdetail.measurement_dia, mbookdetail.measurement_contentarea
						 from mbookheader 
						 INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
						 where mbookheader.date<'$fromdate' and mbookheader.sheetid = '$sheetid' and mbookheader.subdivid = '$subdivid'
						 and mbookdetail.mbdetail_flag != 'd'";
	$select_qty_sql = mysql_query($select_qty_query);
	if($select_qty_sql == true)
	{
		if(mysql_num_rows($select_qty_sql)>0)
		{
			while($QtyList = mysql_fetch_object($select_qty_sql))
			{
				$Qty = $QtyList->measurement_contentarea;
				$dia = $QtyList->measurement_dia;
				if($dia == 8){ $tot_8 = $tot_8+$Qty; }
				if($dia == 10){ $tot_10 = $tot_10+$Qty; }
				if($dia == 12){ $tot_12 = $tot_12+$Qty; }
				if($dia == 16){ $tot_16 = $tot_16+$Qty; }
				if($dia == 20){ $tot_20 = $tot_20+$Qty; }
				if($dia == 25){ $tot_25 = $tot_25+$Qty; }
				if($dia == 28){ $tot_28 = $tot_28+$Qty; }
				if($dia == 32){ $tot_32 = $tot_32+$Qty; }
				if($dia == 36){ $tot_36 = $tot_36+$Qty; }
				$usedQty = $usedQty+$Qty;
			}
			$tot_weight_8 = round(($tot_8*0.395),$decimal_placed);
			$tot_weight_10 = round(($tot_10*0.617),$decimal_placed);
			$tot_weight_12 = round(($tot_12*0.888),$decimal_placed);
			$tot_weight_16 = round(($tot_16*1.578),$decimal_placed);
			$tot_weight_20 = round(($tot_20*2.466),$decimal_placed);
			$tot_weight_25 = round(($tot_25*3.853),$decimal_placed);
			$tot_weight_28 = round(($tot_28*4.834),$decimal_placed);
			$tot_weight_32 = round(($tot_32*6.313),$decimal_placed);
			$tot_weight_36 = round(($tot_36*7.990),$decimal_placed);
			$tot_weight = $tot_weight_8+$tot_weight_10+$tot_weight_12+$tot_weight_16+$tot_weight_20+$tot_weight_25+$tot_weight_28+$tot_weight_32+$tot_weight_36;
			$usedQty = ($tot_weight/1000);
		}
	}
	$usedQty = round($usedQty,$decimal_placed);
	return $usedQty;
}


if(isset($_POST['submit']) == " Save ")
{
	$date_wise_data = $_POST['txt_date_wise_data'];
	$month_wise_data = $_POST['txt_month_wise_data'];
	$dev_date_wise_data = $_POST['txt_dev_date_wise_data'];
	//print_r($dev_date_wise_data);exit;
	$esc_id 	= $_POST['txt_sc_esc_id'];
	$esc_rbn 	= $_POST['txt_sc_esc_rbn'];
	$quarter 	= $_POST['txt_sc_quarter'];
	$consum_page = $_POST['txt_consum_page'];
	$consum_mbook = $_POST['txt_consum_mbook'];
	
	$ConsumCalcArr 	= $_POST['txt_sc_calc'];
	
	if($date_wise_data != "")
	{
		$delete_tca_cons_query = "delete ecm, ec from esc_consumption_10ca_master ecm 
		JOIN esc_consumption_10ca ec ON ecm.esc_id = ec.esc_id 
		where ecm.sheetid = '$sheetid' and ec.sheetid = '$sheetid' and ecm.esc_rbn='$esc_rbn' and ec.esc_rbn='$esc_rbn' 
		and ecm.esc_id='$esc_id' and ecm.esc_item_type = 'STL' and ec.esc_item_type = 'STL' and ec.item_code = 'SsIo' and ecm.item_code = 'SsIo' and ecm.quarter = '$quarter'";
		$delete_tca_cons_sql = mysql_query($delete_tca_cons_query);
		//echo $delete_tca_cons_query;
		//$insert_tca_master_query = "insert into ";
		
		//exit;
		$count1 = count($month_wise_data);
		for($j=0; $j<$count1; $j++)
		{
			$exp_month_wise_data = explode("@*@",$month_wise_data[$j]);
			$mdate1 					= dt_format($exp_month_wise_data[0]);
			$sc_mbookno 				= $consum_mbook;//$exp_month_wise_data[1];
			$page 						= $consum_page;//$exp_month_wise_data[2];
			$total_item_qty_month_mt 	= $exp_month_wise_data[3];
			
			$DMY1	=	strtotime($mdate1);
			$M1		=	date("M",$DMY1);
			$Y1		=	date("Y",$DMY1);
			$esc_month1 = $M1."-".$Y1;
			
			$insert_master_query = "insert into esc_consumption_10ca_master set esc_id = '$esc_id', sheetid = '$sheetid',
									esc_rbn = '$esc_rbn', quarter = '$quarter', item_code = 'SsIo', esc_cons_mbook = '$sc_mbookno', 
									esc_cons_mbpage = '$page', esc_month = '$esc_month1', esc_cons_total = '$total_item_qty_month_mt', 
									esc_item_type = 'STL', modifieddate = NOW(), staffid = '$staffid', active = 1";
			$insert_master_sql 	= mysql_query($insert_master_query);
			$ec_mas_id 			= mysql_insert_id();
			if($insert_master_sql == true)
			{
				$success = 1;
			}
			else
			{
				$failure++;
			}
			//echo $insert_master_query."<br/>";
		}
		//echo "ghgh1"; exit;
		$count = count($date_wise_data);
		for($i=0; $i<$count; $i++)
		{
			$exp_date_wise_data = explode("@*@",$date_wise_data[$i]);
			$mdate 		= dt_format($exp_date_wise_data[0]);
			$mbpage 	= $exp_date_wise_data[1];
			$mbookno 	= $exp_date_wise_data[2];
			$rbn 		= $exp_date_wise_data[3];
			$zone_id 	= $exp_date_wise_data[4];
			$subdivid 	= $exp_date_wise_data[5];
			$itemno 	= $exp_date_wise_data[6];
			$item_qty 	= $exp_date_wise_data[7];
			$tc_unit 	= $exp_date_wise_data[8];
			$cem_consum = $exp_date_wise_data[9];
			
			$DMY	=	strtotime($mdate);
			$M		=	date("M",$DMY);
			$Y	=	date("Y",$DMY);
			$esc_month = $M."-".$Y;
			//echo $item_qty ."<br/>";
			$insert_cement_consum_query = "insert into esc_consumption_10ca set
											ec_mas_id = '$ec_mas_id', 
											sheetid = '$sheetid',
											esc_id = '$esc_id',
											esc_rbn = '$esc_rbn',
											item_code = 'SsIo',
											mdate = '$mdate',
											esc_month = '$esc_month',
											mbpage = '$mbpage',
											mbookno = '$mbookno',
											rbn = '$rbn',
											zone_id = '$zone_id',
											subdivid = '$subdivid',
											itemno = '$itemno',
											item_qty = '$item_qty',
											tc_unit = '$tc_unit',
											esc_item_type = 'STL',
											dev_flag = 'N',
											staffid = '$staffid',
											modifieddate = NOW(),
											active = '1'";
			$insert_cement_consum_sql = mysql_query($insert_cement_consum_query);
			if($insert_cement_consum_sql == true)
			{
				$success = 1;
			}
			else
			{
				$failure++;
			}
			//echo $insert_cement_consum_query;
		}
		
		$count3 = count($dev_date_wise_data);
		for($m=0; $m<$count3; $m++)
		{
			$exp_dev_date_wise_data = explode("@*@",$dev_date_wise_data[$m]);
			$mdate 		= dt_format($exp_dev_date_wise_data[0]);
			$mbpage 	= $exp_dev_date_wise_data[1];
			$mbookno 	= $exp_dev_date_wise_data[2];
			$rbn 		= $exp_dev_date_wise_data[3];
			$zone_id 	= $exp_dev_date_wise_data[4];
			$subdivid 	= $exp_dev_date_wise_data[5];
			$itemno 	= $exp_dev_date_wise_data[6];
			$item_qty 	= $exp_dev_date_wise_data[7];
			$tc_unit 	= $exp_dev_date_wise_data[8];
			$cem_consum = $exp_dev_date_wise_data[9];
			
			$DMY	=	strtotime($mdate);
			$M		=	date("M",$DMY);
			$Y	=	date("Y",$DMY);
			$esc_month = $M."-".$Y;
			//echo $item_qty ."<br/>";
			$insert_cement_consum_query1 = "insert into esc_consumption_10ca set
											ec_mas_id = '$ec_mas_id', 
											sheetid = '$sheetid',
											esc_id = '$esc_id',
											esc_rbn = '$esc_rbn',
											item_code = 'SsIo',
											mdate = '$mdate',
											esc_month = '$esc_month',
											mbpage = '$mbpage',
											mbookno = '$mbookno',
											rbn = '$rbn',
											zone_id = '$zone_id',
											subdivid = '$subdivid',
											itemno = '$itemno',
											item_qty = '$item_qty',
											tc_unit = '$tc_unit',
											esc_item_type = 'STL',
											dev_flag = 'Y',
											staffid = '$staffid',
											modifieddate = NOW(),
											active = '1'";
			$insert_cement_consum_sql1 = mysql_query($insert_cement_consum_query1);
			if($insert_cement_consum_sql1 == true)
			{
				$success = 1;
			}
			else
			{
				$failure++;
			}
			//echo $insert_cement_consum_query;
		}

		$DeleteQuery = "delete from esc_consumption_10ca_site where sheetid = '$sheetid' and esc_rbn = '$esc_rbn' and rbn = '$esc_rbn' and esc_id = '$esc_id' and item_code = 'SsIo' and quarter = '$quarter'";
		$DeleteSql 	 = mysql_query($DeleteQuery);
		//echo $DeleteQuery;exit;
		if(count($ConsumCalcArr)>0){
			foreach($ConsumCalcArr as $Value){
				$ConsumValue 	= $Value;
				$ExpConsumValue = explode("@*@",$Value);
				$InvoiceMonth 	= $ExpConsumValue[0];
				$RABMonth 		= $ExpConsumValue[1];
				$InvoiceQty 	= $ExpConsumValue[2];
				$EligibleQty 	= $ExpConsumValue[3];
				$MBookNo 		= $ExpConsumValue[4];
				$MBookpage 		= $ExpConsumValue[5];
				$InsertQuery 	= "insert into esc_consumption_10ca_site set ec_mas_id = '$ec_mas_id', sheetid = '$sheetid', esc_rbn = '$esc_rbn', rbn = '$esc_rbn', 
								  esc_id = '$esc_id', item_code = 'SsIo', quarter = '$quarter', mbook_no = '$MBookNo', mbook_pg = '$MBookpage', invoice_mon = '$InvoiceMonth', 
								  rab_mon = '$RABMonth', qty_brt_to_site = '$InvoiceQty', eligible_qty = '$EligibleQty', staffid = '$staffid', active = 1, createddate = NOW()";
				$InsertSql 		= mysql_query($InsertQuery);
			}
		}
		
		$start_page_old = $_POST['txt_start_page_old'];
		$end_page_old 	= $_POST['txt_end_page_old'];
		$scmbook_old 	= $_POST['txt_scmbook_old'];
		
		$start_page_new = $_POST['txt_start_page_new'];
		$end_page_new 	= $_POST['txt_end_page_new'];
		$scmbook_new 	= $_POST['txt_scmbook_new'];
		
		//$delete_mbook_query = "delete from mymbook where mbno = '$scmbook' and sheetid = '$sheetid' and mtype = 'SC' and rbn = '$esc_rbn' and esc_id = '$esc_id' and quarter = '$quarter'";
		$delete_mbook_query = "delete from mymbook where sheetid = '$sheetid' and mtype = 'SC' and rbn = '$esc_rbn' and esc_id = '$esc_id' and quarter = '$quarter'";
		$delete_mbook_sql = mysql_query($delete_mbook_query);
		//echo $delete_mbook_query;exit;
		$insert_mbook_query  = "insert into mymbook set mbno = '$scmbook_old', startpage = '$start_page_old', endpage = '$end_page_old', sheetid = '$sheetid', 
								staffid = '$staffid', rbn = '$esc_rbn', esc_id = '$esc_id', quarter = '$quarter', active =1, mtype = 'SC', genlevel = 'stl_consum', mbookorder = 1";
		$insert_mbook_sql = mysql_query($insert_mbook_query);
		
		if($scmbook_new != ""){
			$insert_mbook_query  = "insert into mymbook set mbno = '$scmbook_new', startpage = '$start_page_new', endpage = '$end_page_new', sheetid = '$sheetid', 
								staffid = '$staffid', rbn = '$esc_rbn', esc_id = '$esc_id', quarter = '$quarter', active =1, mtype = 'SC', genlevel = 'stl_consum', mbookorder = 2";
			$insert_mbook_sql = mysql_query($insert_mbook_query);
		}
		
		if($insert_mbook_sql == true)
		{
			$success = 1;
		}
		else
		{
			$failure++;
		}
	}
	//print_r($date_wise_data);
	//echo "<br/>";
	//echo count($date_wise_data);
	//exit;
	if($failure>0)
	{
		$msg = "Steel Consumption Not Saved";
	}
	else
	{
		$msg = "Steel Consumption Saved Successfully";
	}
}

if($_GET['newmbook'] != "")
{
	$newmbookno = $_GET['newmbook'];
	$newmbookpage_query = "select mbpage, allotmentid from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
	$newmbookpage_sql = mysql_query($newmbookpage_query);
	$newmbookpage = @mysql_result($newmbookpage_sql,0,'mbpage')+1;
	$new_mb_no 		= $newmbookno;
	$new_mb_page 	= $newmbookpage;
}
//echo $newmbookpage;exit;
?>
<?php require_once "Header.html"; ?>
<style>
    
</style>
<script>
	function goBack()
	{
		url = "Escalation_Steel_Consump.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
	$(function() {
		$(".dialogwindow").dialog({ autoOpen: false,
		minHeight: 200,
		maxHeight:200,
		minWidth: 300,
		maxWidth: 300,
		modal: true,});
		$(".dialogwindow").dialog("open");
		$( ".dialogwindow" ).dialog( "option", "draggable", false );
		$('#btn_cancel').click(function(){
		$(".dialogwindow").dialog("close");
			window.location.href="Escalation_Steel_Consump.php";
		});
		$('#btn').click(function(){
			var x = $('#newmbooklist option:selected').val();
				//alert(x);
			if(x == "")
			{
				var a="* Please select Next Mbook number";
				$('#error_msg').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				$(".dialogwindow").dialog("close");       
				var newmbookvalue = $("#newmbooklist option:selected").text(); //alert(newmbookvalue);
				var oldmbookdetails = '';//document.form.txt_mbno_id.value;
				$.post("GetOldMbookNo.php", {oldmbook: oldmbookdetails}, function (data) {
					window.location.href="Escalation_Steel_Consump_View.php?newmbook="+newmbookvalue;
					return false; // avoid to execute the actual submit of the form.
				});
			}
		});
		$.fn.validatenewmbook = function(event) 
		{ 
			if($('#newmbooklist option:selected').val()=="")
			{ 
				var a="Please select Next Mbook number";
				$('#error_msg').text(a);
				event.preventDefault();
				event.returnValue = false;
							//return false;
			}
			else
			{
				var a="";
				$('#error_msg').text(a);
			}
		}
		$("#newmbooklist").change(function(event){
			$(this).validatenewmbook(event);
		});
	});
</script>
<style>
.pagetitle
{
	text-shadow:
    -1px -1px 0 #7F7F7F,
    1px -1px 0 #7F7F7F,
    -1px 1px 0 #7F7F7F,
    1px 1px 0 #7F7F7F; 
}
.table1
{
	color:#BF0602;
	/*color:#921601;*/
	border: 0px solid #cacaca;
	border-collapse: collapse;
}
.table1 td
{ 
	border: 1px solid #cacaca;
	border-collapse: collapse;
	padding:3px;
}
.fontcolor1
{
	color:#FFFFFF;
}

.popuptitle
{
	background-color:#0080FF;
	font-weight:bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFFFFF;
	line-height:25px;
	border:1px solid #9b9da0;
}
.table2
{
	color:#071A98;
	/*border:1px solid #cacaca;*/
	border-collapse: collapse;
}
.table2 td
{
	border:1px solid #cacaca;
	border-collapse: collapse;
}
.bottomsection
{
 	position: absolute;
    bottom: 0;
	width:100%;
	line-height:38px;
}
.buttonsection
{
	display: inline-block;
	line-height:38px;
}
.buttonstyle
{
	background-color:#0080FF;
	width:80px;
	height:25px;
	color:#FFFFFF;
	-moz-box-shadow: 0px 1px 0px 0px #0080FF;
	-webkit-box-shadow: 0px 1px 0px 0px #0080FF;
	box-shadow: 0px 1px 0px 0px #0080FF;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0080FF), color-stop(1, #0080FF));
	background:-moz-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:-webkit-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:-o-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:-ms-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:linear-gradient(to bottom, #0080FF 5%, #0080FF 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0080FF', endColorstr='#0080FF',GradientType=0);
	border:1px solid #0080FF;
	display:inline-block;
	cursor:pointer;
	font-weight:bold;

}
.buttonstyle:hover
{
	font-size:14px;
	padding: 0.1em 1em;
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E80017;
	border:1px solid #E80017;
}
.popuptextbox
{
	border:none;
	font-family:Verdana;
	font-size:12px;
	font-weight:bold;
	color:#DE0117;
	text-align:center;
	pointer-events: none;
}
.dynamictextbox
{
	border:1px solid #ffffff;
	height:21px;
	color:#DE0117;
	font-weight:bold;
}
.dynamictextbox:hover, .dynamictextbox:focus
{
	/*outline: none;*/
	border:1px solid #2aade4;
	box-shadow: 0 0 7px #2aade4;
	color:#DE0117;
    /*border-color: #9ecaed;
    box-shadow: 0 0 10px #9ecaed;*/
}
.dynamictextbox2
{
	border:1px solid #2aade4;
	box-shadow: 0 0 7px #2aade4;
	color:#DE0117;	
}
.dynamicrowcell
{
	padding-bottom:0px;
	padding-top:0px; 
	padding-left:0px; 
	padding-right:0px;
	text-align:right;
	font:Verdana, Arial, Helvetica, sans-serif;
}
.hide
{
	display:none;
}
.labelprint
{
	font-weight:normal;
	color:#000000;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10pt;
}
.hidtextbox
{
	border:none;
	width:98%;
	text-align:right;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10pt;
	font-weight:bold;
	color:#071A98;
}
@media print 
{
	.printbutton
	{
		display: none !important;
	}
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			 <div class="content">
                <div align="center" class="container_12">
						<?php
						$page = $sc_mbookpageno;
						$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labeldisplay">
									<tr style="border:none;">
										<td align="center" style="border:none;">
											&nbsp;&nbsp;&nbsp;MBook No. '.$sc_mbookno.'
										</td>
									</tr>
								 </table>';
						echo $title;
						$table = $table . "<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labeldisplay' >";
						$table = $table . "<tr>";
						$table = $table . "<td width='17%' class=''>Name of work</td>";
						$table = $table . "<td width='43%' style='word-wrap:break-word' class=''>" .$work_name."</td>";
						$table = $table . "<td width='18%' class=''>Name of the contractor</td>";
						$table = $table . "<td width='22%' class='' colspan='3'>" . $name_contractor . "</td>";
						$table = $table . "</tr>";
						$table = $table . "<tr>";
						$table = $table . "<td class=''>Technical Sanction No.</td>";
						$table = $table . "<td class=''>" . $tech_sanction . "</td>";
						$table = $table . "<td class=''>Agreement No.</td>";
						$table = $table . "<td class='' colspan='3'>" . $agree_no . "</td>";
						$table = $table . "</tr>";
						$table = $table . "<tr>";
						$table = $table . "<td class=''>Work order No.</td>";
						$table = $table . "<td class=''>" . $work_order_no . "</td>";
						$table = $table . "<td class='' colspan='2'>CC No. </td>";
						$table = $table . "<td class='' colspan='2'>" . $ccno . "</td>";
						$table = $table . "</tr>";
						$table = $table . "</table>";
						
						$head = '<tr class="labeldisplay" style="height:35px;">';
						$head .= '<td align="center" valign="middle">Sl.No.</td>';
						$head .= '<td align="center" valign="middle">Date</td>';
						$head .= '<td align="center" valign="middle">page</td>';
						$head .= '<td align="center" valign="middle">MBook <br/>No</td>';
						$head .= '<td align="center" valign="middle">RAB <br/>No.</td>';
						$head .= '<td align="center" valign="middle">Zone</td>';
						//$head .= '<td align="center" valign="middle">Item<br/> No.</td>';
						//$head .= '<td align="center" valign="middle">Description of Item No.</td>';
						$head .= '<td align="center" valign="middle">Qty </td>';
						$head .= '<td align="center" valign="middle">Unit </td>';
						$head .= '<td align="center" valign="middle">Theoritical <br/>Cement <br/>Consump. </td>';
						$head .= '<td align="center" valign="middle">Total <br/>Cement <br/>Consump. </td>';
						//$head .= '<td align="center" valign="middle">&nbsp; </td>';
						$head .= '</tr>';
						?>
						<input type="hidden" name="txt_mbno_id" value="<?php echo $sc_mbid."*".$sc_mbookno."*"."SC"."*".$staffid."*".$sheetid."*".$zone_id; ?>" id="txt_mbno_id" />
						<?php echo $table; ?>
						<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='table1' bgcolor='#FFFFFF' id='table1'>
						<?php echo $head; ?>
						<?php
						if($escal_measure_sql == true)
						{
							if(mysql_num_rows($escal_measure_sql)>0)
							{
								$summary_arr = array(); $subdivid_arr = array(); $itemNo_arr = array(); $summary_ref_arr = array(); $summary_txtbox_arr = array();
								$usedQtyArr = array(); $WorkOrderQtyArr = array(); $DevItemArr = array();
								$slno = 1; $total_cem_consum = 0; $total_item_qty = 0; $total_item_cem_consum_month = 0; $tbid = 0;
								$prev_mdate = ""; $prev_subdivid = ""; $prev_zone_id = ""; $prev_qty = ""; $prev_itemno = ""; $prev_month = "";
								$co_total_qty = 0;
								while($MList = mysql_fetch_object($escal_measure_sql))
								{
								 if (in_array($MList->subdivid, $DevItemArr))
								 {
								 	//$xyxz = 0;
									$mdate 		 = "";//$mdate;
									//$prev_subdivid 		 = "";//$subdivid;
									$itemno 		 = "";//$itemno;
									$description 	 = "";//$description;
									$qty 			 = "";//$qty;
									$zone_id 		 = "";//$zone_id;
									$itemunit 		 = "";//$itemunit;
									$tc_unit 		 = "";//$tc_unit;
									$decimal_placed = "";//$decimal_placed;
									$month 		 = "";//$month;
									$month_num 	 = "";//$month_num;
									$year 		 	 = "";//$year;
									$total_item_qty = "";
									$item_cem_consum = "";
									
									
									$prev_mdate 		 = "";//$mdate;
									$prev_subdivid 		 = "";//$subdivid;
									$prev_itemno 		 = "";//$itemno;
									$prev_description 	 = "";//$description;
									$prev_qty 			 = "";//$qty;
									$prev_zone_id 		 = "";//$zone_id;
									$prev_itemunit 		 = "";//$itemunit;
									$prev_tc_unit 		 = "";//$tc_unit;
									$prev_decimal_placed = "";//$decimal_placed;
									$prev_month 		 = "";//$month;
									$prev_month_num 	 = "";//$month_num;
									$prev_year 		 	 = "";//$year;
									//$itemunit = "";
									//echo "hai"."<br/>";
								 }
								 else
								 {
									$qty = 0;
									$mdate 			 = dt_display($MList->mdate);
									$month_ts		 = strtotime($MList->mdate);
									$month			 = date("F",$month_ts);
									$month_num		 = date("m",$month_ts);	
									$year			 = date("Y",$month_ts);								
									//$mbpage 		 = $MList->mbpage;
									//$mbno 		 = $MList->mbno;
									//$rbn 			 = $MList->rbn;
									$subdivid 		 = $MList->subdivid;
									$itemno 		 = $MList->subdiv_name;
									$description 	 = $MList->description;
									$shortnotes 	 = $MList->shortnotes;
									$no 			 = $MList->measurement_no;
									$no2 			 = $MList->measurement_no2;
									$length 		 = $MList->measurement_l;
									$dia 		 	 = $MList->measurement_dia;
									if(($no2 != "")&&($no2 != 0))
									{
									$qty 			 = $no*$no2*$length;//$MList->measurement_contentarea;
									}
									else
									{
									$qty 			 = $no*$length;//$MList->measurement_contentarea;
									}
									
									$itemunit 		 = $MList->remarks;
									$tc_unit 		 = $MList->tc_unit;
									$decimal_placed  = $MList->decimal_placed;
									$zone_id  		 = $MList->zone_id;
									
									
									
									
									if($page > 100)
									{ 
										if($_GET['varid'] == 1)
										{
											?>
											<div id="dialog" class="dialogwindow" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
											<p style="font-size:12px; font-weight:bold; color:#911200;">Select Next MBook Number</p>
											<select id="newmbooklist" name="mb" style="width:275px;">
											<option value="">---------------------Select--------------------</option>
											<?php echo $objBind->BindMBookList($mbookno,$sheetid,$staffid,'SC'); ?>
											</select>
											<br/>
											<span id="error_msg" style="color:#FF0000; font-weight:bold;"></span>
											<input type="button" class="submit_btn" id="btn" style="color:#FFFFFF;background-color:#9c27b0;border:none;" name="btn" value="Submit"/>
											<input type="button" class="cancel_btn" id="btn_cancel" style="color:#FFFFFF;background-color:#e51c23;border:none;" name="btn_cancel" value="Cancel"/>
											</div>
											<?php
										}
										$line = $start_line + 7;
										//$prevpage 	= 100;
										$page 		= $newmbookpage;
										$sc_mbookno 	= $newmbookno;
									}
									
									
									
									if($subdivid != $prev_subdivid)
									{
										$usedQty = 0;
										$total_work_order_qty = 0;
										array_push($subdivid_arr,$subdivid);
										$itemNo_arr[$subdivid] = $itemno;
										$usedQty = GetUsedQty($subdivid,$sheetid,$fromdate,$decimal_placed);
										$usedQtyArr[$subdivid] = $usedQty;
										$deviate_qty_percent = $MList->deviate_qty_percent;
										$work_order_qty = $MList->total_quantity;
										$total_work_order_qty = round(($work_order_qty+($work_order_qty*$deviate_qty_percent/100)),$decimal_placed);
										$WorkOrderQtyArr[$subdivid] = $total_work_order_qty;
									}
									
									if($line >= 25)
									{
										if($co_total_qty != 0){
											if($page == 100){
												echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".$newmbookpage." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}else{
												echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
										}
										echo "<tr style='border-style:none;'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
										echo "</table>";
										echo "<p style='page-break-after:always;'>&nbsp;</p>";
										
										if($page == 100){ $sc_mbookno = $newmbookno; }
										echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labeldisplay">
											<tr style="border:none;">
												<td align="center" style="border:none;">
													&nbsp;&nbsp;&nbsp;MBook No. '.$sc_mbookno.'
												</td>
											</tr>
										 </table>';
										
										
										echo $table;
										echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
										echo $head;
										$line = 0;
										$line = $line+$LineIncr;
										if($co_total_qty != 0){
											echo "<tr><td colspan='9' align='right'>B/f from Mbook No / Page ".$page."&nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										}
										$page++;
										//$co_total_qty = 0;
									}
									
if($page > 100)
{ 
	if($_GET['varid'] == 1)
	{
		?>
		<div id="dialog" class="dialogwindow" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
		<p style="font-size:12px; font-weight:bold; color:#911200;">Select Next MBook Number</p>
		<select id="newmbooklist" name="mb" style="width:275px;">
		<option value="">---------------------Select--------------------</option>
		<?php echo $objBind->BindMBookList($mbookno,$sheetid,$staffid,'SC'); ?>
		</select>
		<br/>
		<span id="error_msg" style="color:#FF0000; font-weight:bold;"></span>
		<input type="button" class="submit_btn" id="btn" style="color:#FFFFFF;background-color:#9c27b0;border:none;" name="btn" value="Submit"/>
		<input type="button" class="cancel_btn" id="btn_cancel" style="color:#FFFFFF;background-color:#e51c23;border:none;" name="btn_cancel" value="Cancel"/>
		</div>
		<?php
	}
	$line = $start_line + 7;
	$prevpage 	= 100;
	$page 		= $newmbookpage;
	$sc_mbookno 	= $newmbookno;  
}									
									
									
									//$item_cem_consum = $tc_unit*$qty;
									if($shortnotes != "")
									{
										$description = $shortnotes;
									}
									if(($subdivid != $prev_subdivid) && ($prev_subdivid != ""))
									{
										$temp1 = 1;
									}
									else if(($mdate != $prev_mdate) && ($prev_mdate != ""))
									{
										$temp1 = 1;
									}
									else if(($zone_id != $prev_zone_id) && ($prev_zone_id != ""))
									{
										$temp1 = 1;
									}
									else
									{
										$temp1 = 0;
									}
									// This Row for dispaly every date wise total.
									if($temp1 == 1)
									{
										$tot_8 = round(($totaldiaeight * 0.395),$prev_decimal_placed);
										$tot_10 = round(($totaldiaten * 0.617),$prev_decimal_placed);
										$tot_12 = round(($totaldiatwelve * 0.888),$prev_decimal_placed);
										$tot_16 = round(($totaldiasixteen * 1.578),$prev_decimal_placed);
										$tot_20 = round(($totaldiatwenty * 2.466),$prev_decimal_placed);
										$tot_25 = round(($totaldiatwentyfive * 3.853),$prev_decimal_placed);
										$tot_28 = round(($totaldiatwentyeight * 4.834),$prev_decimal_placed);
										$tot_32 = round(($totaldiathirtytwo * 6.313),$prev_decimal_placed);
										$tot_36 = round(($totaldiathirtysix * 7.990),$prev_decimal_placed);
										$totalweight_KGS = round(($tot_8+$tot_10+$tot_12+$tot_16+$tot_20+$tot_25+$tot_28+$tot_32+$tot_36),$prev_decimal_placed);
										$totalweight_MT = round(($totalweight_KGS/1000),$prev_decimal_placed);
										//echo "8 = ".$tot_8."<br/>";
										//echo "10 = ".$tot_10."<br/>";
										//echo "12 = ".$tot_12."<br/>";
										//echo "16 = ".$tot_16."<br/>";
										//echo "20 = ".$tot_20."<br/>";
										//echo "25 = ".$tot_25."<br/>";
										//echo "28 = ".$tot_28."<br/>";
										//echo "32 = ".$tot_32."<br/>";
										//echo "36 = ".$tot_36."<br/>";
										if($prev_tc_unit == 0)
										{
											$tc_unit_temp1 = "";
											$tc_unit_temp2 = 1;
										}
										else
										{
											$tc_unit_temp1 = number_format($prev_tc_unit,$prev_decimal_placed,".",",");
											$tc_unit_temp2 = $prev_tc_unit;
										}

										$item_cem_consum 		= round($tc_unit_temp2*$totalweight_MT,$prev_decimal_placed);
										$total_item_cem_consum_month 	= $total_item_cem_consum_month+$item_cem_consum;
										$Datares = get_mbook_page_rbn($sheetid, $prev_zone_id, $prev_subdivid, $prev_month_num, $prev_year);
										$ExpDatares = explode("*",$Datares);
										$mbookno 	= $ExpDatares[0];
										$mbpage 	= $ExpDatares[1];
										$rbn 		= $ExpDatares[2];
										
										$wrap_cnt2 = 0;
										//$WrapReturn2 = getWordWrapCount($prev_description,40);
										//$shortnotes = $WrapReturn2[0];
										//$wrap_cnt2 = $WrapReturn2[1];
										//$line = $line+$wrap_cnt2;
										$shortnotes = $description;
										if($line >= 25)
										{
											if($co_total_qty != 0){
											//echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											if($page == 100){
												echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".$newmbookpage." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}else{
												echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											
											}
											echo "<tr style='border-style:none;'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
											echo "</table>";
											echo "<p style='page-break-after:always;'>&nbsp;</p>";
											
											if($page == 100){ $sc_mbookno = $newmbookno; }
											echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labeldisplay">
												<tr style="border:none;">
													<td align="center" style="border:none;">
														&nbsp;&nbsp;&nbsp;MBook No. '.$sc_mbookno.'
													</td>
												</tr>
											 </table>';
											
											echo $table;
											echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
											echo $head;
											$line = 0;
											$line = $line+$LineIncr;
											if($co_total_qty != 0){
												echo "<tr><td colspan='9' align='right'>B/f from Mbook No / Page ".$page."&nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											$page++;
											//$co_total_qty = 0;
										}
										
if($page > 100)
{ 
	if($_GET['varid'] == 1)
	{
		?>
		<div id="dialog" class="dialogwindow" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
		<p style="font-size:12px; font-weight:bold; color:#911200;">Select Next MBook Number</p>
		<select id="newmbooklist" name="mb" style="width:275px;">
		<option value="">---------------------Select--------------------</option>
		<?php echo $objBind->BindMBookList($mbookno,$sheetid,$staffid,'SC'); ?>
		</select>
		<br/>
		<span id="error_msg" style="color:#FF0000; font-weight:bold;"></span>
		<input type="button" class="submit_btn" id="btn" style="color:#FFFFFF;background-color:#9c27b0;border:none;" name="btn" value="Submit"/>
		<input type="button" class="cancel_btn" id="btn_cancel" style="color:#FFFFFF;background-color:#e51c23;border:none;" name="btn_cancel" value="Cancel"/>
		</div>
		<?php
	}
	$line = $start_line + 7;
	$prevpage 	= 100;
	$page 		= $newmbookpage;
	$sc_mbookno 	= $newmbookno;  
}									
										
										
										$item_wise_curr_used_qty = $item_wise_curr_used_qty+$totalweight_MT;
										//echo $res."<br/>";
										echo '<tr class="labeldisplay">';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">'.$prev_mdate.'</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$mbpage.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$mbookno.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$rbn.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.getzonename($sheetid,$prev_zone_id).'&nbsp;</td>';
										//echo '<td align="center" valign="middle">'.$prev_itemno.'</td>';
										//echo '<td align="left" valign="middle">'.$shortnotes.'</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($totalweight_MT,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">'.$itemunit.'</td>';
										echo '<td align="right" valign="middle">&nbsp;'.$tc_unit_temp1.'&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
										//echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
										$line++;
										$co_total_qty = $co_total_qty + $item_cem_consum;
										$date_wise_data1 = "";
										$date_wise_data1 = $prev_mdate."@*@".$mbpage."@*@".$mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$totalweight_MT."@*@".$prev_tc_unit."@*@".$item_cem_consum;
										echo '<input type="hidden" name="txt_date_wise_data[]" id="txt_date_wise_data" value="'.$date_wise_data1.'">';
										
										$total_item_qty = 0;
										$totaldiaeight = 0; $totaldiaten = 0; $totaldiatwelve = 0; $totaldiasixteen = 0; $totaldiatwenty = 0;
										$totaldiatwentyfive = 0; $totaldiatwentyeight = 0; $totaldiathirtytwo = 0; $totaldiathirtysix = 0;
										$totalweight_KGS = 0;
										$totalweight_MT = 0;
										$tot_8 = 0; $tot_10 = 0; $tot_12 = 0; $tot_16 = 0; $tot_20 = 0; $tot_25 = 0; $tot_28 = 0; $tot_32 = 0; $tot_36 = 0;
										$slno++;
									}
									if(($month != $prev_month)&&($prev_month != ""))
									{
									
										//  This row is check Deviated Qty
										$TotalWorkOrderQty = $WorkOrderQtyArr[$prev_subdivid];//20;
										if($item_wise_curr_used_qty>$TotalWorkOrderQty)
										{
											array_push($DevItemArr,$prev_subdivid); 
											$Ded_dev_qty = $TotalWorkOrderQty-$item_wise_curr_used_qty;
											$item_wise_curr_used_qty = 0;
											$item_cem_consum 		= round(1*$Ded_dev_qty,$prev_decimal_placed);
											$total_item_cem_consum_month 	= $total_item_cem_consum_month+$item_cem_consum;
										
											echo '<tr class="labeldisplay">';
											echo '<td align="center" valign="middle">&nbsp;</td>';
											echo '<td align="right" valign="middle" colspan="5">&nbsp; Deviated Quantity&nbsp;&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;'.$prev_itemno.'</td>';
											echo '<td align="right" valign="middle">&nbsp;'.$Ded_dev_qty.'&nbsp;</td>';
											echo '<td align="center" valign="middle">'.$itemunit.'</td>';
											echo '<td align="right" valign="middle">&nbsp;&nbsp;</td>';
											echo '<td align="right" valign="middle">&nbsp;&nbsp;'.number_format($Ded_dev_qty,$prev_decimal_placed,".",",").'&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '</tr>';
											
										$dev_date_wise_data1 = "";
										$dev_date_wise_data1 = $prev_mdate."@*@".$page."@*@".$sc_mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$Ded_dev_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
										echo '<input type="hidden" name="txt_dev_date_wise_data[]" id="txt_dev_date_wise_data" value="'.$dev_date_wise_data1.'">';
											
										}
										if($subdivid != $prev_subdivid)
										{
											$item_wise_curr_used_qty = 0;
										}
									
									
									
										// This Row for dispaly every month wise total in kg.
										//exit;
										echo '<tr class="label">';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle" colspan="4"><input type="text" name="txt_ref_'.$prev_subdivid.'" id="txt_ref_'.$prev_subdivid.'" class="hidtextbox"></td>';
										//echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="left" valign="middle">&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										//echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_cem_consum_month,$prev_decimal_placed,".",",").'&nbsp;</td>';
										//echo '<td align="center" valign="middle">&nbsp;'.$prev_itemunit.'&nbsp;</td>';
										echo '</tr>';
										
										$summary_arr[$prev_subdivid][$prev_month] = $total_item_cem_consum_month;
										$summary_ref_arr[$prev_subdivid][$prev_month] = "B/f MB-".$sc_mbookno."/Pg-".$page;
										$summary_txtbox_arr[$prev_subdivid][$prev_month] = $tbid;
										$tbid++;
										
										$month_wise_data1 = "";
										$month_wise_data1 = $prev_mdate."@*@".$sc_mbookno."@*@".$page."@*@".$total_item_cem_consum_month;
										echo '<input type="hidden" name="txt_month_wise_data[]" id="txt_month_wise_data" value="'.$month_wise_data1.'">';
										$line++;
										$co_total_qty = 0;
										// This Row for display Qty in Metric Tone for every Month
										/*$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
										echo '<tr class="label">';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="left" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;Qc&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month_mt,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;mt&nbsp;</td>';
										echo '</tr>';*/
										$total_item_cem_consum_month = 0;
										$slno = 1;
										if($line >= 25)
										{
											if($co_total_qty != 0){
											//echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											if($page == 100){
												echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".$newmbookpage." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}else{
												echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											}
											echo "<tr style='border-style:none;'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
											echo "</table>";
											echo "<p style='page-break-after:always;'>&nbsp;</p>";
											
											//echo $title;
											if($page == 100){ $sc_mbookno = $newmbookno; }
											echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labeldisplay">
												<tr style="border:none;">
													<td align="center" style="border:none;">
														&nbsp;&nbsp;&nbsp;MBook No. '.$sc_mbookno.'
													</td>
												</tr>
											 </table>';
											
											echo $table;
											echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
											echo $head;
											$line = 0;
											$line = $line+$LineIncr;
											if($co_total_qty != 0){
												echo "<tr><td colspan='9' align='right'>B/f from Mbook No / Page ".$page."&nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											$page++;
											//$co_total_qty = 0;
										}
									}
									
if($page > 100)
{ 
	if($_GET['varid'] == 1)
	{
		?>
		<div id="dialog" class="dialogwindow" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
		<p style="font-size:12px; font-weight:bold; color:#911200;">Select Next MBook Number</p>
		<select id="newmbooklist" name="mb" style="width:275px;">
		<option value="">---------------------Select--------------------</option>
		<?php echo $objBind->BindMBookList($mbookno,$sheetid,$staffid,'SC'); ?>
		</select>
		<br/>
		<span id="error_msg" style="color:#FF0000; font-weight:bold;"></span>
		<input type="button" class="submit_btn" id="btn" style="color:#FFFFFF;background-color:#9c27b0;border:none;" name="btn" value="Submit"/>
		<input type="button" class="cancel_btn" id="btn_cancel" style="color:#FFFFFF;background-color:#e51c23;border:none;" name="btn_cancel" value="Cancel"/>
		</div>
		<?php
	}
	$line = $start_line + 7;
	$prevpage 	= 100;
	$page 		= $newmbookpage;
	$sc_mbookno 	= $newmbookno;  
}									
									
									// Display Every Item Title
									if($subdivid != $prev_subdivid)
									{
										echo '<tr class="labeldisplay">';
										echo '<td align="center" valign="middle">&nbsp;'.$itemno.'&nbsp;</td>';
										echo '<td align="left" valign="middle" colspan="7">&nbsp;'.$shortnotes.'&nbsp;</td>';
										//echo '<td align="left" valign="middle" colspan="8">&nbsp;'.$shortnotes.'&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
										$line++;
									}
									// Display Every month Title

									
									// Display Every month Title
									if($month != $prev_month)
									{
										echo '<tr class="label">';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;&nbsp;'.$month.' - '.$year.'&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
										$line++;
										if($line >= 25)
										{
											if($co_total_qty != 0){
											//echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											if($page == 100){
												echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".$newmbookpage." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}else{
												echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											}
											echo "<tr style='border-style:none;'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
											echo "</table>";
											echo "<p style='page-break-after:always;'>&nbsp;</p>";
											
											//echo $title;
											if($page == 100){ $sc_mbookno = $newmbookno; }
											echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labeldisplay">
												<tr style="border:none;">
													<td align="center" style="border:none;">
														&nbsp;&nbsp;&nbsp;MBook No. '.$sc_mbookno.'
													</td>
												</tr>
											 </table>';
											
											echo $table;
											echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
											echo $head;
											$line = 0;
											$line = $line+$LineIncr;
											if($co_total_qty != 0){
												echo "<tr><td colspan='9' align='right'>B/f from Mbook No / Page ".$page."&nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											$page++;
											//$co_total_qty = 0;
										}
									}
									
if($page > 100)
{ 
	if($_GET['varid'] == 1)
	{
		?>
		<div id="dialog" class="dialogwindow" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
		<p style="font-size:12px; font-weight:bold; color:#911200;">Select Next MBook Number</p>
		<select id="newmbooklist" name="mb" style="width:275px;">
		<option value="">---------------------Select--------------------</option>
		<?php echo $objBind->BindMBookList($mbookno,$sheetid,$staffid,'SC'); ?>
		</select>
		<br/>
		<span id="error_msg" style="color:#FF0000; font-weight:bold;"></span>
		<input type="button" class="submit_btn" id="btn" style="color:#FFFFFF;background-color:#9c27b0;border:none;" name="btn" value="Submit"/>
		<input type="button" class="cancel_btn" id="btn_cancel" style="color:#FFFFFF;background-color:#e51c23;border:none;" name="btn_cancel" value="Cancel"/>
		</div>
		<?php
	}
	$line = $start_line + 7;
	$prevpage 	= 100;
	$page 		= $newmbookpage;
	$sc_mbookno 	= $newmbookno;  
}									
									
									
									if($dia == 8) { $totaldiaeight 		= $totaldiaeight		+	$qty; }
									if($dia == 10){ $totaldiaten 		= $totaldiaten			+	$qty; }
									if($dia == 12){ $totaldiatwelve 	= $totaldiatwelve		+	$qty; }
									if($dia == 16){ $totaldiasixteen 	= $totaldiasixteen		+	$qty; }
									if($dia == 20){ $totaldiatwenty 	= $totaldiatwenty		+	$qty; }
									if($dia == 25){ $totaldiatwentyfive = $totaldiatwentyfive	+	$qty; }
									if($dia == 28){ $totaldiatwentyeight= $totaldiatwentyeight	+	$qty; }
									if($dia == 32){ $totaldiathirtytwo 	= $totaldiathirtytwo	+	$qty; }
									if($dia == 36){ $totaldiathirtysix 	= $totaldiathirtysix	+	$qty; }
									$total_cem_consum = $total_cem_consum + $item_cem_consum;
									$prev_mdate 		 = $mdate;
									$prev_subdivid 		 = $subdivid;
									$prev_itemno 		 = $itemno;
									$prev_description 	 = $description;
									$prev_qty 			 = $qty;
									$prev_zone_id 		 = $zone_id;
									$prev_itemunit 		 = $itemunit;
									$prev_tc_unit 		 = $tc_unit;
									$prev_decimal_placed = $decimal_placed;
									$prev_month 		 = $month;
									$prev_month_num 	 = $month_num;
									$prev_year 		 	 = $year;
								 }
								}
								if (in_array($prev_subdivid, $DevItemArr))
								{
								 	$xyxz = 0;
									$mdate 		 = "";//$mdate;
									//$prev_subdivid 		 = "";//$subdivid;
									$itemno 		 = "";//$itemno;
									$description 	 = "";//$description;
									$qty 			 = "";//$qty;
									$zone_id 		 = "";//$zone_id;
									$itemunit 		 = "";//$itemunit;
									$tc_unit 		 = "";//$tc_unit;
									$decimal_placed = "";//$decimal_placed;
									$month 		 = "";//$month;
									$month_num 	 = "";//$month_num;
									$year 		 	 = "";//$year;
									$total_item_qty = "";
									$item_cem_consum = "";
									
									
									$prev_mdate 		 = "";//$mdate;
									$prev_subdivid 		 = "";//$subdivid;
									$prev_itemno 		 = "";//$itemno;
									$prev_description 	 = "";//$description;
									$prev_qty 			 = "";//$qty;
									$prev_zone_id 		 = "";//$zone_id;
									$prev_itemunit 		 = "";//$itemunit;
									$prev_tc_unit 		 = "";//$tc_unit;
									$prev_decimal_placed = "";//$decimal_placed;
									$prev_month 		 = "";//$month;
									$prev_month_num 	 = "";//$month_num;
									$prev_year 		 	 = "";//$year;
									//$itemunit = "";
									//echo "hai"."<br/>";
								}
								else
								{
								$tot_8 = round(($totaldiaeight * 0.395),$prev_decimal_placed);
								$tot_10 = round(($totaldiaten * 0.617),$prev_decimal_placed);
								$tot_12 = round(($totaldiatwelve * 0.888),$prev_decimal_placed);
								$tot_16 = round(($totaldiasixteen * 1.578),$prev_decimal_placed);
								$tot_20 = round(($totaldiatwenty * 2.466),$prev_decimal_placed);
								$tot_25 = round(($totaldiatwentyfive * 3.853),$prev_decimal_placed);
								$tot_28 = round(($totaldiatwentyeight * 4.834),$prev_decimal_placed);
								$tot_32 = round(($totaldiathirtytwo * 6.313),$prev_decimal_placed);
								$tot_36 = round(($totaldiathirtysix * 7.990),$prev_decimal_placed);
								$totalweight_KGS = round(($tot_8+$tot_10+$tot_12+$tot_16+$tot_20+$tot_25+$tot_28+$tot_32+$tot_36),$prev_decimal_placed);
								$totalweight_MT = round(($totalweight_KGS/1000),$prev_decimal_placed);
								if($prev_tc_unit == 0)
								{
									$tc_unit_temp1 = "";
									$tc_unit_temp2 = 1;
								}
								else
								{
									$tc_unit_temp1 = number_format($prev_tc_unit,$prev_decimal_placed,".",",");
									$tc_unit_temp2 = $prev_tc_unit;
								}

								//echo "ggrfg = ".$totalweight_MT."<br/>";
								// Last Row for dispaly Last row of date wise item qty.
								$item_cem_consum = round($tc_unit_temp2*$totalweight_MT,$prev_decimal_placed);
								$total_item_cem_consum_month 	= $total_item_cem_consum_month+$item_cem_consum;
								$Datares 	= get_mbook_page_rbn($sheetid, $prev_zone_id, $prev_subdivid, $prev_month_num, $prev_year);
								$ExpDatares = explode("*",$Datares);
								$mbookno 	= $ExpDatares[0];
								$mbpage 	= $ExpDatares[1];
								$rbn 		= $ExpDatares[2];
								
								$wrap_cnt3 = 0;
								$WrapReturn3 = getWordWrapCount($prev_description,40);
								$shortnotes = $WrapReturn3[0];
								$wrap_cnt3 = $WrapReturn3[1];
								$line = $line+$wrap_cnt3;
								if($line >= 25)
									{
										if($co_total_qty != 0){
										//echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											if($page == 100){
												echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".$newmbookpage." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}else{
												echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
										}
										echo "<tr style='border-style:none;'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
										echo "</table>";
										echo "<p style='page-break-after:always;'>&nbsp;</p>";
										
										//echo $title;
										if($page == 100){ $sc_mbookno = $newmbookno; }
										echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labeldisplay">
											<tr style="border:none;">
												<td align="center" style="border:none;">
													&nbsp;&nbsp;&nbsp;MBook No. '.$sc_mbookno.'
												</td>
											</tr>
										 </table>';
										
										echo $table;
										echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
										echo $head;
										$line = 0;
										$line = $line+$LineIncr;
										if($co_total_qty != 0){
											echo "<tr><td colspan='9' align='right'>B/f from Mbook No / Page ".$page."&nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										}
										$page++;
										//$co_total_qty = 0;
									}
									
if($page > 100)
{ 
	if($_GET['varid'] == 1)
	{
		?>
		<div id="dialog" class="dialogwindow" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
		<p style="font-size:12px; font-weight:bold; color:#911200;">Select Next MBook Number</p>
		<select id="newmbooklist" name="mb" style="width:275px;">
		<option value="">---------------------Select--------------------</option>
		<?php echo $objBind->BindMBookList($mbookno,$sheetid,$staffid,'SC'); ?>
		</select>
		<br/>
		<span id="error_msg" style="color:#FF0000; font-weight:bold;"></span>
		<input type="button" class="submit_btn" id="btn" style="color:#FFFFFF;background-color:#9c27b0;border:none;" name="btn" value="Submit"/>
		<input type="button" class="cancel_btn" id="btn_cancel" style="color:#FFFFFF;background-color:#e51c23;border:none;" name="btn_cancel" value="Cancel"/>
		</div>
		<?php
	}
	$line = $start_line + 7;
	$prevpage 	= 100;
	$page 		= $newmbookpage;
	$sc_mbookno 	= $newmbookno;  
}									
									
								
								$item_wise_curr_used_qty = $item_wise_curr_used_qty+$totalweight_MT;
								
								echo '<tr class="labeldisplay">';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">'.$prev_mdate.'</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$mbpage.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$mbookno.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$rbn.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.getzonename($sheetid,$prev_zone_id).'&nbsp;</td>';
								//echo '<td align="center" valign="middle">'.$prev_itemno.'</td>';
								//echo '<td align="left" valign="middle">'.$shortnotes.'</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($totalweight_MT,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">'.$itemunit.'</td>';
								echo '<td align="right" valign="middle">&nbsp;'.$tc_unit_temp1.'&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
								//echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '</tr>';
								
								
										$TotalWorkOrderQty = $WorkOrderQtyArr[$prev_subdivid];//20;
										if($item_wise_curr_used_qty>$TotalWorkOrderQty)
										{
											array_push($DevItemArr,$prev_subdivid); 
											$Ded_dev_qty = $TotalWorkOrderQty-$item_wise_curr_used_qty;
											$item_wise_curr_used_qty = 0;
											$item_cem_consum 		= round(1*$Ded_dev_qty,$prev_decimal_placed);
											$total_item_cem_consum_month 	= $total_item_cem_consum_month+$item_cem_consum;
										
											echo '<tr class="labeldisplay">';
											echo '<td align="center" valign="middle">&nbsp;</td>';
											echo '<td align="right" valign="middle" colspan="5">&nbsp; Deviated Quantity&nbsp;&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;'.$prev_itemno.'</td>';
											echo '<td align="right" valign="middle">&nbsp;'.$Ded_dev_qty.'&nbsp;</td>';
											echo '<td align="center" valign="middle">'.$itemunit.'</td>';
											echo '<td align="right" valign="middle">&nbsp;&nbsp;</td>';
											echo '<td align="right" valign="middle">&nbsp;&nbsp;'.number_format($Ded_dev_qty,$prev_decimal_placed,".",",").'&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '</tr>';
											
										$dev_date_wise_data2 = "";
										$dev_date_wise_data2 = $prev_mdate."@*@".$page."@*@".$sc_mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$Ded_dev_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
										echo '<input type="hidden" name="txt_dev_date_wise_data[]" id="txt_dev_date_wise_data" value="'.$dev_date_wise_data2.'">';
											
										}
										if($subdivid != $prev_subdivid)
										{
											$item_wise_curr_used_qty = 0;
										}
								
								$line++;
								$date_wise_data2 = "";
								$date_wise_data2 = $prev_mdate."@*@".$mbpage."@*@".$mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$totalweight_MT."@*@".$prev_tc_unit."@*@".$item_cem_consum;
								echo '<input type="hidden" name="txt_date_wise_data[]" id="txt_date_wise_data" value="'.$date_wise_data2.'">';
								$total_item_qty = 0;
								// Last Row for dispaly Last row of month wise total in kg.
								echo '<tr class="label">';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle" colspan="4"><input type="text" name="txt_ref_'.$prev_subdivid.'" id="txt_ref_'.$prev_subdivid.'" class="hidtextbox"></td>';
								//echo '<td align="center" valign="middle">&nbsp;</td>';
								//echo '<td align="left" valign="middle">&nbsp;</td>';
								//echo '<td align="right" valign="middle">&nbsp;</td>';
								//echo '<td align="center" valign="middle">&nbsp;</td>';
								//echo '<td align="right" valign="middle">&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_cem_consum_month,$prev_decimal_placed,".",",").'&nbsp;</td>';
								//echo '<td align="center" valign="middle">&nbsp;'.$prev_itemunit.'&nbsp;</td>';
								echo '</tr>';
								
										$summary_arr[$prev_subdivid][$prev_month] = $total_item_cem_consum_month;
										$summary_ref_arr[$prev_subdivid][$prev_month] = "B/f MB-".$sc_mbookno."/Pg-".$page;
										$summary_txtbox_arr[$prev_subdivid][$prev_month] = $tbid;
										
								$line++;
								
								
								
							    }	
								echo "<tr style='border-style:none;'><td colspan='10' align='center' style='border-style:none;'> page ".$page."</td></tr>";
								// Last Row for display Qty in Metric Tone
								/*$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
								echo '<tr class="label">';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="left" valign="middle">&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;Qc&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month_mt,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;mt&nbsp;</td>';
								echo '</tr>';*/
								$co_total_qty = 0;
								$month_wise_data2 = "";
								$month_wise_data2 = $prev_mdate."@*@".$sc_mbookno."@*@".$page."@*@".$total_item_cem_consum_month;
								echo '<input type="hidden" name="txt_month_wise_data[]" id="txt_month_wise_data" value="'.$month_wise_data2.'">';
								$total_item_cem_consum_month = 0;
							}
						}
						//print_r($DevItemArr);
						$page++;
if($page > 100)
{ 
	if($_GET['varid'] == 1)
	{
		?>
		<div id="dialog" class="dialogwindow" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
		<p style="font-size:12px; font-weight:bold; color:#911200;">Select Next MBook Number</p>
		<select id="newmbooklist" name="mb" style="width:275px;">
		<option value="">---------------------Select--------------------</option>
		<?php echo $objBind->BindMBookList($mbookno,$sheetid,$staffid,'SC'); ?>
		</select>
		<br/>
		<span id="error_msg" style="color:#FF0000; font-weight:bold;"></span>
		<input type="button" class="submit_btn" id="btn" style="color:#FFFFFF;background-color:#9c27b0;border:none;" name="btn" value="Submit"/>
		<input type="button" class="cancel_btn" id="btn_cancel" style="color:#FFFFFF;background-color:#e51c23;border:none;" name="btn_cancel" value="Cancel"/>
		</div>
		<?php
	}
	$line = $start_line + 7;
	$prevpage 	= 100;
	$page 		= $newmbookpage;
	$sc_mbookno 	= $newmbookno;  
}									
						
						?>
								</table>
								
								
							<p style='page-break-after:always;'></p>
						<?php
						$MonthQtyArr = array(); $UsedQtyArr = array();
						if(count($summary_arr)>0)
						{
							$mon1 = $MonthList[0];
							$mon2 = $MonthList[1];
							$mon3 = $MonthList[2];
							
							//echo $title;
							if($page == 100){ $sc_mbookno = $newmbookno; }
							echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labeldisplay">
									<tr style="border:none;">
										<td align="center" style="border:none;">
											&nbsp;&nbsp;&nbsp;MBook No. '.$sc_mbookno.'
										</td>
									</tr>
								</table>';
							
							
							echo $table;
							echo "<br/>";
							echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
							//echo $head;
							echo '<tr class="label"><td colspan="7" align="center">Summary</td></tr>';
							echo '<tr class="label">';
								echo '<td align="center">Item No</td>';
								//echo '<td align="center">Page</td>';
								//echo '<td align="center">MB</td>';
								echo '<td align="center" colspan="2">'.$MonthList[0].'</td>';
								echo '<td align="center" colspan="2">'.$MonthList[1].'</td>';
								echo '<td align="center" colspan="2">'.$MonthList[2].'</td>';
							echo '</tr>';
							$tbid_pg_str = "";
							//print_r($summary_txtbox_arr);
							$tot_qty1 = 0; $tot_qty2 = 0; $tot_qty3 = 0;
							for($s1=0; $s1<count($subdivid_arr); $s1++)
							{
								$summ_subdivid = $subdivid_arr[$s1];
								$qty1 = $summary_arr[$summ_subdivid][$mon1];
								$qty2 = $summary_arr[$summ_subdivid][$mon2];
								$qty3 = $summary_arr[$summ_subdivid][$mon3];
								$tot_qty1 = $tot_qty1+$qty1;
								$tot_qty2 = $tot_qty2+$qty2;
								$tot_qty3 = $tot_qty3+$qty3;
								?>
								<tr>
									<td align="center"><?php echo $itemNo_arr[$summ_subdivid]; ?></td>
									<td align="center"><?php echo $summary_ref_arr[$summ_subdivid][$mon1]; ?></td>
									<td align="center"><?php echo $summary_arr[$summ_subdivid][$mon1]; ?></td>
									<td align="center"><?php echo $summary_ref_arr[$summ_subdivid][$mon2]; ?></td>
									<td align="center"><?php echo $summary_arr[$summ_subdivid][$mon2]; ?></td>
									<td align="center"><?php echo $summary_ref_arr[$summ_subdivid][$mon3]; ?></td>
									<td align="center"><?php echo $summary_arr[$summ_subdivid][$mon3]; ?></td>
								</tr>
								<?php
								$tbid_pg_str .= $summ_subdivid."*".$sc_mbookno."*".$page."@";
							}
							$tot_qty1 = round($tot_qty1,3);
							$tot_qty2 = round($tot_qty2,3);
							$tot_qty3 = round($tot_qty3,3);
							
							$MonthQtyArr[$mon1] = $tot_qty1;
							$MonthQtyArr[$mon2] = $tot_qty2;
							$MonthQtyArr[$mon3] = $tot_qty3;
							
							array_push($UsedQtyArr,$tot_qty1);
							array_push($UsedQtyArr,$tot_qty2);
							array_push($UsedQtyArr,$tot_qty3);
							?>
								<tr>
									<td align="center">Total Consumption.</td>
									<td align="center"></td>
									<td align="center"><?php echo $tot_qty1; ?></td>
									<td align="center"></td>
									<td align="center"><?php echo $tot_qty2; ?></td>
									<td align="center"></td>
									<td align="center"><?php echo $tot_qty3; ?></td>
								</tr>
								<input type="hidden" name="txt_consum_page" id="txt_consum_page" value="<?php echo $page; ?>">
								<input type="hidden" name="txt_consum_mbook" id="txt_consum_mbook" value="<?php echo $sc_mbookno; ?>">
							<?php
							echo "<tr style='border-style:none;'><td colspan='7' align='center' style='border-style:none;'> page ".$page."</td></tr>";
							echo "</table>";
						//echo $tbid_pg_str;
						}
						$InvoiceArr = array(); $InvoiceQtyMastArr = array(); $InvoiceMonMastArr = array();
						$SelectInvoiceQuery = "select * from mat_invoice where sheetid = '$sheetid' and mat_code = 'SSTE' and mat_type = 'S'";
						$SelectInvoiceSql = mysql_query($SelectInvoiceQuery);
						if($SelectInvoiceSql == true){
							if(mysql_num_rows($SelectInvoiceSql)>0){
								while($IVList = mysql_fetch_array($SelectInvoiceSql)){
									$InvoiceDate 	= date('M-Y', strtotime($IVList['invoice_dt']));
									$ReceivedDate 	= date('M-Y', strtotime($IVList['received_dt']));
									$IVList['invoice_mon_yr'] 	=  $InvoiceDate;
									$IVList['received_mon_yr'] 	=  $ReceivedDate;
									$InvoiceArr[] 	= $IVList; 
									$InvoiceQty 	= $IVList['qty']+$IVList['dia_8_qty']+$IVList['dia_10_qty']+$IVList['dia_12_qty']+$IVList['dia_16_qty']+$IVList['dia_20_qty']+$IVList['dia_25_qty']+$IVList['dia_28_qty']+$IVList['dia_32_qty']+$IVList['dia_36_qty'];
									array_push($InvoiceQtyMastArr,$InvoiceQty);
									array_push($InvoiceMonMastArr,$InvoiceDate);
								}
							}
						}
						echo "<br/>";
						//print_r($InvoiceQtyMastArr);exit;
						$InvoiceQtyArr  = $InvoiceQtyMastArr;
						$UtilizedQtyArr = $UsedQtyArr;
						$InvoiceMonArr	= $InvoiceMonMastArr;
						$UtilizedMonArr	= $MonthYrArr;
						
						$i = 1; $x = 0;
						echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF'>";
						echo "<tr class='label'><td colspan='4' align='center'>Quantity of steel brought to site and 10CA qty. calculation</td></tr>";
						echo "<tr class='label'>";
						echo "<td align='center'>Invoice Month</td>";
						echo "<td align='center'>RAB/Billing Month</td>";
						echo "<td align='right'>Qty. Brought to Site (in MT) </td>";
						echo "<td align='right'>Qty. Eligible For 10CA (in MT) </td>";
						echo "</tr>";
						
						$InvoiceBalQty = 0; $UtilizeBalQty = 0; $PrevInvoiceMon = ""; $PrevRABMon = ""; $TotalInvoiceQty = 0; $TotalUtilizedQty = 0;
						while($x < 1){ $i++;
							if($InvoiceBalQty == 0){ 
								$InvoiceQty = $InvoiceQtyArr[0];
								$InvoiceMon = $InvoiceMonArr[0];
								$TotalInvoiceQty = $TotalInvoiceQty + $InvoiceQty;
								unset($InvoiceQtyArr[0]);
								unset($InvoiceMonArr[0]);
								$InvoiceQtyArr 	= array_values($InvoiceQtyArr);
								$InvoiceMonArr 	= array_values($InvoiceMonArr);
							}else{
								$InvoiceQty = $InvoiceBalQty;
								$InvoiceMon = "";
							}
							if($UtilizeBalQty == 0){
								$UtilizeQty = $UtilizedQtyArr[0];
								$UtilizeMon = $UtilizedMonArr[0];
								$TotalUtilizedQty = $TotalUtilizedQty + $UtilizeQty;
								unset($UtilizedQtyArr[0]);
								unset($UtilizedMonArr[0]);
								$UtilizedQtyArr = array_values($UtilizedQtyArr);
								$UtilizedMonArr = array_values($UtilizedMonArr);
							}else{
								$UtilizeQty = $UtilizeBalQty;
								//$UtilizeMon = "";
							}
							$OutPutStr = "";
							if($InvoiceQty >= $UtilizeQty){
								echo "<tr>";
								if($InvoiceBalQty == 0){ 
									echo "<td align='center'>".$InvoiceMon."</td>";
									echo "<td>".$UtilizeMon."</td>";
									echo "<td align='right'>".$InvoiceQty."&nbsp;</td>";
									$OutPutStr = $InvoiceMon."@*@".$UtilizeMon."@*@".$InvoiceQty;
								}else{
									echo "<td align='center'>".$PrevInvoiceMon."</td>";
									echo "<td>".$UtilizeMon."</td>";
									echo "<td></td>";
									$OutPutStr = $PrevInvoiceMon."@*@".$UtilizeMon."@*@".'';
								}
								echo "<td align='right'>".$UtilizeQty."&nbsp;</td>";
								$OutPutStr = $OutPutStr."@*@".$UtilizeQty."@*@".$sc_mbookno."@*@".$page;
								echo "</tr>";
								$InvoiceBalQty 	= $InvoiceQty - $UtilizeQty;
								$UtilizeBalQty	= 0;
							}else if($UtilizeQty >= $InvoiceQty){
								echo "<tr>";
								if($InvoiceBalQty > 0){
									echo "<td align='center'>".$PrevInvoiceMon."</td>";
									echo "<td>".$UtilizeMon."</td>";
									echo "<td></td>";
									$OutPutStr = $PrevInvoiceMon."@*@".$UtilizeMon."@*@".'';
								}else{
									echo "<td align='center'>".$InvoiceMon."</td>";
									echo "<td>".$UtilizeMon."</td>";
									echo "<td align='right'>".$InvoiceQty."&nbsp;</td>";
									$OutPutStr = $InvoiceMon."@*@".$UtilizeMon."@*@".$InvoiceQty;
								}
								echo "<td align='right'>".$InvoiceQty."&nbsp;</td>";
								$OutPutStr = $OutPutStr."@*@".$InvoiceQty."@*@".$sc_mbookno."@*@".$page;
								echo "</tr>";
								$InvoiceBalQty	= 0;
								$UtilizeBalQty	= $UtilizeQty - $InvoiceQty;
							}
					
							$Count1 		= count($InvoiceQtyArr);
							$Count2 		= count($UtilizedQtyArr);
							if(($Count1 == 0)&&($UtilizeBalQty == 0)&&($Count2 == 0)){
								$x = 1; 
							}else if(($Count2 == 0)&&($UtilizeBalQty == 0)){
								$x = 1; 
							}else if(($Count2 == 0)&&($Count1 == 0)){
								$x = 1; 
							}else if(($Count1 == 0)&&($UtilizeBalQty > 0)){
								$x = 1; 
							}
							if($i == 20){
								$x = 1;
							}
							if($InvoiceMon != ''){
								$PrevInvoiceMon = $InvoiceMon;
							}
							echo '<input type="hidden" name="txt_sc_calc[]" value = "'.$OutPutStr.'">';
						}
						$page++;
						echo "<tr style='border-style:none;'><td colspan='7' align='center' style='border-style:none;'> page ".$page."</td></tr>";
						echo "</table>";
						if($InvoiceBalQty < 0){
							$ErrorMsg = "Error : Consumed quantity should be less than or equal to material brought to site quantity";
							$Error = 1;
						}else if($TotalInvoiceQty < $TotalUtilizedQty){
							$ErrorMsg = "Error : Consumed quantity should be less than or equal to material brought to site quantity";
							$Error = 1;
						}else{
							$Error = 0;
						}
						
						?>
							<input type="hidden" name="txt_sc_esc_id" id="txt_sc_esc_id" value="<?php echo $sc_esc_id;?>">
							<input type="hidden" name="txt_sc_esc_rbn" id="txt_sc_esc_rbn" value="<?php echo $sc_esc_rbn;?>">
							<input type="hidden" name="txt_start_page_new" id="txt_start_page_new" value="<?php echo $new_mb_page;?>">
							<input type="hidden" name="txt_end_page_new" id="txt_end_page_new" value="<?php echo $page;?>">
							<input type="hidden" name="txt_scmbook_new" id="txt_scmbook_new" value="<?php echo $new_mb_no;?>">
							<input type="hidden" name="txt_start_page_old" id="txt_start_page_old" value="<?php echo $old_mb_page;?>">
							<input type="hidden" name="txt_end_page_old" id="txt_end_page_old" value="<?php if($new_mb_no != ""){ echo 100; }else{ echo $page; } ?>">
							<input type="hidden" name="txt_scmbook_old" id="txt_scmbook_old" value="<?php echo $old_mb_no;?>">
							
							
							<input type="hidden" name="txt_sc_quarter" id="txt_sc_quarter" value="<?php echo $sc_quarter;?>">
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
									<input type="submit" name="submit" id="submit" value=" Save "/>
								</div>
							</div>
				</div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				//alert(success)
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal({
								  title: "",
								  text: msg,
								  type: "success",
								  confirmButtonText: "OK",
								  closeOnConfirm: false
								},
								function(){
								  window.location.href = "Escalation_Steel_Site_Consump.php";
							});
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
			</script>
        </form>
    </body>
	<script>
		var txtbox_str = "<?php echo $tbid_pg_str; ?>";
		if(txtbox_str != "")
		{
			var splitData = txtbox_str.split("@");
			for(var i=0; i<splitData.length; i++)
			{
		//alert(splitData[i])
				if(splitData[i] != "")
				{
					var resData = splitData[i].split("*");
					var id = resData[0];
					var mb = resData[1];
					var pg = resData[2];
					var len = document.getElementsByName('txt_ref_'+id).length;
					for(var j=0; j<len; j++)
					{
						var textbox = document.getElementsByName('txt_ref_'+id)[j];
						textbox.value = "C/o to MB - "+mb+" /Pg-"+pg;
					}
					
				}
			}
		}
	</script>
</html>
