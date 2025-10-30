<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
checkUser();
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
$cc_quarter  	= $_SESSION['escal_quarter'];
$cc_esc_rbn 	= $_SESSION['escal_rbn'];
$cc_esc_id 		= $_SESSION['escal_esc_id'];
$cc_mbookno  	= $_SESSION['cc_mbook_no'];
$cc_mbookpageno = $_SESSION['cc_mbook_pageno'];
$start_page = $cc_mbookpageno;
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
//$fromdate = '2016-07-01';
//$todate = '2016-07-31';
//echo $fromdate."<br/>";
//echo $todate."<br/>";exit;
//$escal_fromdate_query = "select work_order_date from sheet where sheet_id = '$sheetid'";
//$escal_fromdate_sql = mysql_query($escal_fromdate_query);
//if($escal_fromdate_sql == true)
//{
	//$MinDateList = mysql_fetch_object($escal_fromdate_sql);
	//$min_date = $MinDateList->work_order_date;
//}
//$min_date = '2016-07-14';
//$start_month_ts = strtotime("+1 month",strtotime($min_date));
//$start_month 	= date('Y-m-d', $start_month_ts);
//$fromdate 		= date("Y-m-01", strtotime($start_month));

//$end_month_ts 	= strtotime("+3 month",strtotime($min_date));
//$end_month 		= date('Y-m-d', $end_month_ts);
//$todate 		= date("Y-m-t", strtotime($end_month));


/*$escal_measure_query = 	"SELECT mbookheader.mbheaderid, DATE(mbookheader.date) as mdate, mbookheader.sheetid, 
							mbookheader.subdivid, mbookheader.subdiv_name, mbookheader.zone_id, 
							mbookdetail.mbheaderid, mbookdetail.subdivid, mbookdetail.subdiv_name, mbookdetail.descwork, mbookdetail.measurement_no,
							mbookdetail.measurement_l, mbookdetail.measurement_b, mbookdetail.measurement_d, mbookdetail.measurement_contentarea, 
							mbookdetail.remarks, mbookdetail.zone_id,
							schdule.sno, schdule.tc_unit, 
							schdule.measure_type, schdule.subdiv_id, schdule.per, schdule.decimal_placed, schdule.description, schdule.shortnotes 
							FROM mbookheader
							INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
							INNER JOIN schdule ON (mbookheader.subdivid = schdule.subdiv_id)
							WHERE schdule.measure_type != 's' AND schdule.measure_type != 'st' AND mbookheader.sheetid = '$sheetid' 
							AND (mbookheader.date BETWEEN '$fromdate' AND '$todate') 
							AND schdule.sheet_id = '$sheetid' AND schdule.tc_unit != '0' 
							ORDER BY mbookheader.date ASC, mbookheader.subdivid ASC, mbookheader.zone_id ASC";*/
$escal_measure_query = 	"SELECT mbookheader.mbheaderid, DATE(mbookheader.date) as mdate, mbookheader.sheetid, 
							mbookheader.subdivid, mbookheader.subdiv_name, mbookheader.zone_id, 
							mbookdetail.mbheaderid, mbookdetail.subdivid, mbookdetail.subdiv_name, mbookdetail.descwork, mbookdetail.measurement_no,
							mbookdetail.measurement_l, mbookdetail.measurement_b, mbookdetail.measurement_d, mbookdetail.measurement_contentarea, 
							mbookdetail.remarks, mbookdetail.zone_id,
							schdule.sno, schdule.tc_unit, schdule.total_quantity, schdule.deviate_qty_percent, schdule.item_flag,
							schdule.measure_type, schdule.subdiv_id, schdule.per, schdule.decimal_placed, schdule.description, schdule.shortnotes,
							schdule.covert_to_unit 
							FROM mbookheader
							INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
							INNER JOIN schdule ON (mbookheader.subdivid = schdule.subdiv_id)
							WHERE schdule.measure_type != 's' AND schdule.measure_type != 'st' AND mbookheader.sheetid = '$sheetid' 
							AND (mbookheader.date BETWEEN '$fromdate' AND '$todate') 
							AND schdule.sheet_id = '$sheetid' AND schdule.tc_unit != '0' AND (schdule.item_flag = 'NI' OR schdule.escalation_flag = 'Y') AND schdule.mat_code = 'STE'  
							AND NOT EXISTS 
							( SELECT esc_consumption_10ca.subdivid FROM esc_consumption_10ca WHERE esc_consumption_10ca.subdivid = mbookheader.subdivid 
							AND esc_consumption_10ca.mdate<'$fromdate' AND esc_consumption_10ca.sheetid = '$sheetid' AND esc_consumption_10ca.dev_flag = 'Y'
							AND esc_consumption_10ca.esc_item_type = 'CEM')
							ORDER BY mbookheader.subdivid ASC, mbookheader.date ASC, mbookheader.zone_id ASC";
							
							
							
$escal_measure_sql = mysql_query($escal_measure_query);
///echo $escal_measure_query."<br/>";exit;
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
	$usedQty = 0;
	$select_qty_query =  "select mbookheader.mbheaderid, DATE(mbookheader.date) as mdate, mbookheader.sheetid, 
						 mbookheader.subdivid, mbookdetail.mbheaderid, mbookdetail.subdivid, mbookdetail.measurement_contentarea
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
				$usedQty = $usedQty+$Qty;
			}
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
	$esc_id 		= $_POST['txt_cc_esc_id'];
	$esc_rbn 		= $_POST['txt_cc_esc_rbn'];
	$quarter 		= $_POST['txt_cc_quarter'];
	$consum_page 	= $_POST['txt_consum_page'];
	$consum_mbook 	= $_POST['txt_consum_mbook'];
	
	$ConsumCalcArr 	= $_POST['txt_sc_calc'];
	
	
	if($date_wise_data != "")
	{
		$delete_tca_cons_query = "delete ecm, ec from esc_consumption_10ca_master ecm 
		JOIN esc_consumption_10ca ec ON ecm.esc_id = ec.esc_id 
		where ecm.sheetid = '$sheetid' and ec.sheetid = '$sheetid' and ecm.esc_rbn='$esc_rbn' and ec.esc_rbn='$esc_rbn' 
		and ecm.esc_id='$esc_id' and ecm.esc_item_type = 'CEM' and ec.item_code = 'CsIo' and ecm.item_code = 'CsIo' and ecm.quarter = '$quarter'";
		//echo $delete_tca_cons_query;exit;
		$delete_tca_cons_sql = mysql_query($delete_tca_cons_query);
		//echo $delete_tca_cons_query;exit;
		//$insert_tca_master_query = "insert into ";
		
		//exit;
		$count1 = count($month_wise_data);
		for($j=0; $j<$count1; $j++)
		{
			$exp_month_wise_data = explode("@*@",$month_wise_data[$j]);
			$mdate1 					= dt_format($exp_month_wise_data[0]);
			$cc_mbookno 				= $consum_mbook;//$exp_month_wise_data[1];
			$page 						= $consum_page;//$exp_month_wise_data[2];
			$total_item_qty_month_mt 	= $exp_month_wise_data[3];
			
			$DMY1	=	strtotime($mdate1);
			$M1		=	date("M",$DMY1);
			$Y1		=	date("Y",$DMY1);
			$esc_month1 = $M1."-".$Y1;
			
			$insert_master_query = "insert into esc_consumption_10ca_master set esc_id = '$esc_id', sheetid = '$sheetid',
									esc_rbn = '$esc_rbn', quarter = '$quarter', item_code = 'CsIo', esc_cons_mbook = '$cc_mbookno', 
									esc_cons_mbpage = '$page', esc_month = '$esc_month1', esc_cons_total = '$total_item_qty_month_mt', 
									esc_item_type = 'CEM', modifieddate = NOW(), staffid = '$staffid', active = 1";
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
											item_code = 'CsIo',
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
											esc_item_type = 'CEM',
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
		
		// Insert Deviated Quantity Details
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
											item_code = 'CsIo',
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
											esc_item_type = 'CEM',
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
		$DeleteQuery = "delete from esc_consumption_10ca_site where sheetid = '$sheetid' and esc_rbn = '$esc_rbn' and rbn = '$esc_rbn' and esc_id = '$esc_id' and item_code = 'CsIo' and quarter = '$quarter'";
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
								  esc_id = '$esc_id', item_code = 'CsIo', quarter = '$quarter', mbook_no = '$MBookNo', mbook_pg = '$MBookpage', invoice_mon = '$InvoiceMonth', 
								  rab_mon = '$RABMonth', qty_brt_to_site = '$InvoiceQty', eligible_qty = '$EligibleQty', staffid = '$staffid', active = 1, createddate = NOW()";
				$InsertSql 		= mysql_query($InsertQuery);
			}
		}

		
		
		$start_page = $_POST['txt_start_page'];
		$end_page = $_POST['txt_end_page'];
		$ccmbook = $_POST['txt_ccmbook'];
		
		//$delete_mbook_query = "delete from mymbook where mbno = '$ccmbook' and sheetid = '$sheetid' and mtype = 'CC' and rbn = '$esc_rbn' and esc_id = '$esc_id' and quarter = '$quarter'";
		// Commented for Single Escalation MBook....Above is for multiple MBook
		$delete_mbook_query = "delete from mymbook where mbno = '$ccmbook' and sheetid = '$sheetid' and mtype = 'E' and genlevel = 'cem_consum' and rbn = '$esc_rbn' and esc_id = '$esc_id' and quarter = '$quarter'";
		$delete_mbook_sql = mysql_query($delete_mbook_query);
		//echo $delete_mbook_query;exit;
		$insert_mbook_query  = "insert into mymbook set mbno = '$ccmbook', startpage = '$start_page', endpage = '$end_page', sheetid = '$sheetid', 
								staffid = '$staffid', rbn = '$esc_rbn', esc_id = '$esc_id', quarter = '$quarter', active =1, mtype = 'E', genlevel = 'cem_consum', mbookorder = 1";
		$insert_mbook_sql = mysql_query($insert_mbook_query);
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
		$msg = "Cement Consumption Not Saved";
	}
	else
	{
		$msg = "Cement Consumption Saved Successfully";
	}
}
$UnitsArr = array();
$SelectUnitQuery = "SELECT * FROM unit";
$SelectUnitSql 	 = mysql_query($SelectUnitQuery);
if($SelectUnitSql == true){
	if(mysql_num_rows($SelectUnitSql)>0){
		while($List = mysql_fetch_object($SelectUnitSql)){
			$UnitsArr[$List->id][0] = $List->unit_name;
			$UnitsArr[$List->id][1] = $List->conv_action;
			$UnitsArr[$List->id][2] = $List->conv_factor;
		}
	}
}
///print_r($UnitsArr);exit;
?>
<?php require_once "Header.html"; ?>
<style>
    
</style>
<script>
	function goBack()
	{
		url = "Escalation_Cement_Consump_General.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
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
	<div class="content">  
		<div class="title">Escalation - Cement Consumption (Site Consumption)</div> 
		<div align="center" class="container_12">
			<blockquote class="bq1" style="overflow:auto">
				<div>&nbsp;</div>
            <!--==============================Content=================================-->
					<?php
						$page = $cc_mbookpageno;
						$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labeldisplay">
									<tr style="border:none;">
										<td align="center" style="border:none;">
											&nbsp;&nbsp;&nbsp;MBook No. '.$cc_mbookno.'
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
						$head .= '<td align="center" valign="middle">Item No.</td>';
						$head .= '<td align="center" valign="middle">Date</td>';
						$head .= '<td align="center" valign="middle">page</td>';
						$head .= '<td align="center" valign="middle">MBook <br/>No</td>';
						$head .= '<td align="center" valign="middle">RAB <br/>No.</td>';
						//$head .= '<td align="center" valign="middle">Zone</td>';
						$head .= '<td align="center" valign="middle">Item<br/> No.</td>';
						//$head .= '<td align="center" valign="middle">Description of Item No.</td>';
						$head .= '<td align="center" valign="middle">Qty </td>';
						$head .= '<td align="center" valign="middle">Unit </td>';
						$head .= '<td align="center" valign="middle">Theoritical <br/>Cement <br/>Consump. </td>';
						$head .= '<td align="center" valign="middle">Total <br/>Cement <br/>Consump. </td>';
						$head .= '<td align="center" valign="middle">&nbsp; </td>';
						$head .= '</tr>';
						?>
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
								$slno = 1; $total_cem_consum = 0; $total_item_qty = 0; $total_item_qty_month = 0; $tbid = 0;
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
								 	//print_r($DevItemArr);
									$mdate 			 = dt_display($MList->mdate);
									$month_ts		 = strtotime($MList->mdate);
									$month			 = date("F",$month_ts);
									$month_num		 = date("m",$month_ts);	
									$year			 = date("Y",$month_ts);	
									//echo $mdate."<br/>";					
									//$mbpage 		 = $MList->mbpage;
									//$mbno 		 = $MList->mbno;
									//$rbn 			 = $MList->rbn;
									$subdivid 		 = $MList->subdivid;
									$itemno 		 = $MList->subdiv_name;
									$description 	 = $MList->description;
									$shortnotes 	 = $MList->shortnotes;
									$qty 			 = $MList->measurement_contentarea;
									$itemunit 		 = $MList->remarks;
									$tc_unit 		 = $MList->tc_unit;
									$decimal_placed  = $MList->decimal_placed;
									$zone_id  		 = $MList->zone_id;
									$covert_to_unit  = $MList->covert_to_unit;
									
									
									
									
									if($subdivid != $prev_subdivid)
									{
										//echo "Hi".$item_wise_curr_used_qty."<br/>";
										$usedQty = 0;
										$total_work_order_qty = 0;
										//$item_wise_curr_used_qty = 0;
										array_push($subdivid_arr,$subdivid);
										$itemNo_arr[$subdivid] = $itemno;
										$usedQty = GetUsedQty($subdivid,$sheetid,$fromdate,$decimal_placed);
										$usedQtyArr[$subdivid] = $usedQty;
										$deviate_qty_percent = $MList->deviate_qty_percent;
										
										
										$work_order_qty = $MList->total_quantity;
										$total_work_order_qty = round(($work_order_qty+($work_order_qty*$deviate_qty_percent/100)),$decimal_placed);
										//echo "h".$work_order_qty."<br/>";
										$WorkOrderQtyArr[$subdivid] = $total_work_order_qty;
										//print_r($usedQtyArr);
										//echo $usedQty."<br/>";
									}
									
									if($line >= 25)
									{
										if($co_total_qty != 0){
										echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										}
										echo "<tr style='border-style:none;'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
										echo "</table>";
										echo "<p style='page-break-after:always;'>&nbsp;</p>";
										echo $title;
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
										/// The Below round of is doubt which should be cleared with them.
										$total_item_qty 		= round($total_item_qty,$prev_decimal_placed);
										$item_cem_consum 		= round($prev_tc_unit*$total_item_qty,$prev_decimal_placed);
										$total_item_qty_month 	= $total_item_qty_month+$item_cem_consum;
										
										$item_wise_curr_used_qty = $item_wise_curr_used_qty+$total_item_qty;
										//echo $total_item_qty." === ".$item_wise_curr_used_qty."<br/>";
										//if($subdivid != $prev_subdivid)
										//{
											//$item_wise_curr_used_qty = 0;
										//}
										
										$Datares = get_mbook_page_rbn($sheetid, $prev_zone_id, $prev_subdivid, $prev_month_num, $prev_year);
										$ExpDatares = explode("*",$Datares);
										$mbookno 	= $ExpDatares[0];
										$mbpage 	= $ExpDatares[1];
										$rbn 		= $ExpDatares[2];
										
										$wrap_cnt2 = 0;
										//$WrapReturn2 = getWordWrapCount($description,120);
										//$shortnotes = $WrapReturn2[0];
										//$wrap_cnt2 = $WrapReturn2[1];
										//$line = $line+$wrap_cnt2;
										$shortnotes = $description;
										if($line >= 25)
										{
											if($co_total_qty != 0){
											echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											echo "<tr style='border-style:none;'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
											echo "</table>";
											echo "<p style='page-break-after:always;'>&nbsp;</p>";
											echo $title;
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
										
										//echo $res."<br/>";
										echo '<tr class="labeldisplay">';
										//echo '<td align="center" valign="middle">'.$slno.'</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">'.$prev_mdate.'</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$mbpage.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$mbookno.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$rbn.'&nbsp;</td>';
										//echo '<td align="center" valign="middle">&nbsp;'.getzonename($sheetid,$prev_zone_id).'&nbsp;</td>';
										echo '<td align="center" valign="middle">'.$prev_itemno.'</td>';
										//echo '<td align="left" valign="middle">'.$shortnotes.'</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">'.$itemunit.'</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($prev_tc_unit,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
										$line++;
										$co_total_qty = $co_total_qty + $item_cem_consum;
										// This is hidden box which is used to store date wise 'data' for each item.
										$date_wise_data1 = "";
										$date_wise_data1 = $prev_mdate."@*@".$mbpage."@*@".$mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$total_item_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
										echo '<input type="hidden" name="txt_date_wise_data[]" id="txt_date_wise_data" value="'.$date_wise_data1.'">';
										$total_item_qty = 0;
										$slno++;
									}
									
									if(($month != $prev_month)&&($prev_month != ""))
									{
										$end = 1;
									}
									else if(($subdivid != $prev_subdivid) && ($prev_subdivid != ""))
									{
										$end = 1;
									}
									else
									{
										$end = 0;
									}
									
									//if(($month != $prev_month)&&($prev_month != ""))
									if($end == 1)
									{
										//  This row is check Deviated Qty
										//echo $item_wise_curr_used_qty."<br/>";
										//print_r($usedQtyArr);
										$TotalWorkOrderQty = $WorkOrderQtyArr[$prev_subdivid];//920
										//echo "TWOQ = ".$TotalWorkOrderQty."<br/>";
										if($item_wise_curr_used_qty>$TotalWorkOrderQty)
										{
											array_push($DevItemArr,$prev_subdivid); 
											$Ded_dev_qty = $TotalWorkOrderQty-$item_wise_curr_used_qty;
											$item_wise_curr_used_qty = 0;
											$item_cem_consum 		= round($prev_tc_unit*$Ded_dev_qty,$prev_decimal_placed);
											$total_item_qty_month 	= $total_item_qty_month+$item_cem_consum;
										
											echo '<tr class="labeldisplay">';
											echo '<td align="center" valign="middle">&nbsp;</td>';
											echo '<td align="right" valign="middle" colspan="4">&nbsp; Deviated Quantity&nbsp;&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;'.$prev_itemno.'</td>';
											echo '<td align="right" valign="middle">&nbsp;'.$Ded_dev_qty.'&nbsp;</td>';
											//echo '<td align="right" valign="middle">&nbsp;</td>';
											echo '<td align="center" valign="middle">'.$itemunit.'</td>';
											echo '<td align="right" valign="middle">&nbsp;'.number_format($prev_tc_unit,$prev_decimal_placed,".",",").'&nbsp;</td>';
											echo '<td align="right" valign="middle">&nbsp;&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '</tr>';
											
										$dev_date_wise_data1 = "";
										$dev_date_wise_data1 = $prev_mdate."@*@".$page."@*@".$cc_mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$Ded_dev_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
										echo '<input type="hidden" name="txt_dev_date_wise_data[]" id="txt_dev_date_wise_data" value="'.$dev_date_wise_data1.'">';
											
										}
										if($subdivid != $prev_subdivid)
										{
											$item_wise_curr_used_qty = 0;
										}
										// This Row for dispaly every month wise total in kg.
										
										echo '<tr class="label">';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="left" valign="middle">&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;kg&nbsp;</td>';
										echo '</tr>';
										$line++;
										// This Row for display Qty in Metric Tone for every Month
										$ConvUnitTo = $UnitsArr[$prev_covert_to_unit][0]; 
										$ConvAction = $UnitsArr[$prev_covert_to_unit][1]; 
										$ConvFactor = $UnitsArr[$prev_covert_to_unit][2]; 
										if($ConvAction != ''){
											if($ConvAction == "A"){
												$total_item_qty_month_mt = round(($total_item_qty_month+$ConvFactor),$prev_decimal_placed);
											}else if($ConvAction == "S"){
												$total_item_qty_month_mt = round(($total_item_qty_month-$ConvFactor),$prev_decimal_placed);
											}else if($ConvAction == "M"){
												$total_item_qty_month_mt = round(($total_item_qty_month*$ConvFactor),$prev_decimal_placed);
											}else if($ConvAction == "D"){
												if($ConvFactor != 0){
													$total_item_qty_month_mt = round(($total_item_qty_month/$ConvFactor),$prev_decimal_placed);
												}else{
													$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
												}
											}else{
												$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
											}
										}else{
											$ConvUnitTo = "Tonne";
											$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
										}
										//$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed); // Convert to Metric Tonne
										//$total_item_qty_month_mt = round(($total_item_qty_month/50),$prev_decimal_placed); // Convert to Bag
										
										echo '<tr class="label">';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="left" valign="middle">&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										//echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle" colspan="4"><input type="text" name="txt_ref_'.$prev_subdivid.'" id="txt_ref_'.$prev_subdivid.'" class="hidtextbox"></td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month_mt,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$ConvUnitTo.'&nbsp;</td>';
										echo '</tr>';
										
										$summary_arr[$prev_subdivid][$prev_month] = $total_item_qty_month_mt;
										$summary_ref_arr[$prev_subdivid][$prev_month] = "B/f MB-".$cc_mbookno."/Pg-".$page;
										$summary_txtbox_arr[$prev_subdivid][$prev_month] = $tbid;
										$tbid++;
										
										$co_total_qty = 0;
										$month_wise_data1 = "";
										$month_wise_data1 = $prev_mdate."@*@".$cc_mbookno."@*@".$page."@*@".$total_item_qty_month_mt;
										echo '<input type="hidden" name="txt_month_wise_data[]" id="txt_month_wise_data" value="'.$month_wise_data1.'">';
										$line++;
										$total_item_qty_month = 0;
										$slno = 1;
										if($line >= 25)
										{
											if($co_total_qty != 0){
											echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											echo "<tr style='border-style:none;'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
											echo "</table>";
											echo "<p style='page-break-after:always;'>&nbsp;</p>";
											echo $title;
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
									// Display Every Item Title
									if($subdivid != $prev_subdivid)
									{
										echo '<tr class="labeldisplay">';
										echo '<td align="center" valign="middle">&nbsp;'.$itemno.'&nbsp;</td>';
										echo '<td align="left" valign="middle" colspan="8">&nbsp;'.$shortnotes.'&nbsp;</td>';
										//echo '<td align="left" valign="middle" colspan="8">&nbsp;'.$shortnotes.'&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
										$line++;
									}
									// Display Every month Title
									if($month != $prev_month)
									{
										//print_r($DevItemArr);echo "<br/>";
										echo '<tr class="label">';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$month.' - '.$year.'&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										//echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
										$line++;
										if($line >= 25)
										{
											if($co_total_qty != 0){
											echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											echo "<tr style='border-style:none;'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
											echo "</table>";
											echo "<p style='page-break-after:always;'>&nbsp;</p>";
											echo $title;
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
									$total_item_qty = $total_item_qty + $qty;
									//$total_cem_consum = $total_cem_consum + $item_cem_consum;
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
									$prev_covert_to_unit = $covert_to_unit;
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
								
								$item_wise_curr_used_qty = $item_wise_curr_used_qty+$total_item_qty;
								//echo "Hi".$item_wise_curr_used_qty."<br/>";
								// Last Row for dispaly Last row of date wise item qty.
								$total_item_qty = round($total_item_qty,$prev_decimal_placed);
								$item_cem_consum = round($prev_tc_unit*$total_item_qty,$prev_decimal_placed);
								//echo $total_item_qty_month."=".$item_cem_consum;
								$total_item_qty_month 	= $total_item_qty_month+$item_cem_consum;
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
										echo "<tr><td colspan='9' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										}
										echo "<tr style='border-style:none;'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
										echo "</table>";
										echo "<p style='page-break-after:always;'>&nbsp;</p>";
										echo $title;
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
								
								
								$total_item_qty = round($total_item_qty,$prev_decimal_placed);
								echo '<tr class="labeldisplay">';
								//echo '<td align="center" valign="middle">'.$slno.'</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">'.$prev_mdate.'</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$mbpage.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$mbookno.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$rbn.'&nbsp;</td>';
								//echo '<td align="center" valign="middle">&nbsp;'.getzonename($sheetid,$prev_zone_id).'&nbsp;</td>';
								echo '<td align="center" valign="middle">'.$prev_itemno.'</td>';
								//echo '<td align="left" valign="middle">'.$shortnotes.'</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">'.$itemunit.'</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($prev_tc_unit,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '</tr>';
								$line++;
								$date_wise_data2 = "";
								$date_wise_data2 = $prev_mdate."@*@".$mbpage."@*@".$mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$total_item_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
								echo '<input type="hidden" name="txt_date_wise_data[]" id="txt_date_wise_data" value="'.$date_wise_data2.'">';
								$total_item_qty = 0;
								
								
								
										//  This row is check Deviated Qty
										//echo $item_wise_curr_used_qty."<br/>";
										//print_r($usedQtyArr);
										$TotalWorkOrderQty = $WorkOrderQtyArr[$prev_subdivid];//920
										//echo "TWOQ = ".$TotalWorkOrderQty."<br/>";
										if($item_wise_curr_used_qty>$TotalWorkOrderQty)
										{
											array_push($DevItemArr,$prev_subdivid); 
											$Ded_dev_qty = $TotalWorkOrderQty-$item_wise_curr_used_qty;
											$item_wise_curr_used_qty = 0;
											$item_cem_consum 		= round($prev_tc_unit*$Ded_dev_qty,$prev_decimal_placed);
											$total_item_qty_month 	= $total_item_qty_month+$item_cem_consum;
										
											echo '<tr class="labeldisplay">';
											echo '<td align="center" valign="middle">&nbsp;</td>';
											echo '<td align="right" valign="middle" colspan="4">&nbsp; Deviated Quantity&nbsp;&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;'.$prev_itemno.'</td>';
											echo '<td align="right" valign="middle">&nbsp;'.$Ded_dev_qty.'&nbsp;</td>';
											//echo '<td align="right" valign="middle">&nbsp;</td>';
											echo '<td align="center" valign="middle">'.$itemunit.'</td>';
											echo '<td align="right" valign="middle">&nbsp;'.number_format($prev_tc_unit,$prev_decimal_placed,".",",").'&nbsp;</td>';
											echo '<td align="right" valign="middle">&nbsp;&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '</tr>';
											
										$dev_date_wise_data2 = "";
										$dev_date_wise_data2 = $prev_mdate."@*@".$page."@*@".$cc_mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$Ded_dev_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
										echo '<input type="hidden" name="txt_dev_date_wise_data[]" id="txt_dev_date_wise_data" value="'.$dev_date_wise_data2.'">';
											
										}
										if($subdivid != $prev_subdivid)
										{
											$item_wise_curr_used_qty = 0;
										}
								
								
								// Last Row for dispaly Last row of month wise total in kg.
								echo '<tr class="label">';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								//echo '<td align="center" valign="middle">&nbsp;</td>';
								//echo '<td align="left" valign="middle">&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '</tr>';
								$line++;
								// Last Row for display Qty in Metric Tone
								//$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed); // Convert to Metric Tonne
								$ConvUnitTo = $UnitsArr[$prev_covert_to_unit][0]; 
								$ConvAction = $UnitsArr[$prev_covert_to_unit][1]; 
								$ConvFactor = $UnitsArr[$prev_covert_to_unit][2]; 
								
								if($ConvAction != ''){
									if($ConvAction == "A"){
										$total_item_qty_month_mt = round(($total_item_qty_month+$ConvFactor),$prev_decimal_placed);
									}else if($ConvAction == "S"){
										$total_item_qty_month_mt = round(($total_item_qty_month-$ConvFactor),$prev_decimal_placed);
									}else if($ConvAction == "M"){
										$total_item_qty_month_mt = round(($total_item_qty_month*$ConvFactor),$prev_decimal_placed);
									}else if($ConvAction == "D"){
										if($ConvFactor != 0){
											$total_item_qty_month_mt = round(($total_item_qty_month/$ConvFactor),$prev_decimal_placed);
										}else{
											$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
										}
									}else{
										$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
									}
								}else{
									$ConvUnitTo = "Tonne";
									$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
								}
								//$total_item_qty_month_mt = round(($total_item_qty_month/50),$prev_decimal_placed); // Convert to Bag
								echo '<tr class="label">';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								//echo '<td align="center" valign="middle">&nbsp;</td>';
								//echo '<td align="center" valign="middle">&nbsp;</td>';
								//echo '<td align="left" valign="middle">&nbsp;</td>';
								//echo '<td align="right" valign="middle">&nbsp;</td>';
								//echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle" colspan="4"><input type="text" name="txt_ref_'.$prev_subdivid.'" id="txt_ref_'.$prev_subdivid.'" class="hidtextbox"></td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month_mt,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$ConvUnitTo.'&nbsp;</td>';
								echo '</tr>';
								$summary_arr[$prev_subdivid][$prev_month] = $total_item_qty_month_mt;
								$summary_ref_arr[$prev_subdivid][$prev_month] = " B/f MB-".$cc_mbookno."/Pg-".$page;
								$summary_txtbox_arr[$prev_subdivid][$prev_month] = $tbid;
								
								$co_total_qty = 0;
								//echo "<tr style='border-style:none;'><td colspan='12' align='center' style='border-style:none;'> page ".$page."</td></tr>";
								
								$month_wise_data2 = "";
								$month_wise_data2 = $prev_mdate."@*@".$cc_mbookno."@*@".$page."@*@".$total_item_qty_month_mt;
								echo '<input type="hidden" name="txt_month_wise_data[]" id="txt_month_wise_data" value="'.$month_wise_data2.'">';
										
								$line++;
								$total_item_qty_month = 0;
							 }
							}
							$end_page = $page;
							//print_r($summary_arr);
						}
						
						echo "<tr style='border-style:none;' class='labelprint'><td colspan='11' align='center' style='border-style:none;'> page ".$page."</td></tr>";
						
						$page++;
						//print_r($DevItemArr);
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
							
							echo $title;
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
								$tbid_pg_str .= $summ_subdivid."*".$cc_mbookno."*".$page."@";
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
								<input type="hidden" name="txt_consum_mbook" id="txt_consum_mbook" value="<?php echo $cc_mbookno; ?>">
							<?php
							echo "<tr style='border-style:none;'><td colspan='7' align='center' style='border-style:none;'> page ".$page."</td></tr>";
							echo "</table>";
						//echo $tbid_pg_str;
						}
						$InvoiceArr = array(); $InvoiceQtyMastArr = array(); $InvoiceMonMastArr = array();
						$SelectInvoiceQuery = "select matid, invoice_no, qty, invoice_dt, received_dt from mat_invoice where sheetid = '$sheetid' and mat_code = 'STE' and mat_type = 'G'";
						$SelectInvoiceSql = mysql_query($SelectInvoiceQuery);
						if($SelectInvoiceSql == true){
							if(mysql_num_rows($SelectInvoiceSql)>0){
								while($IVList = mysql_fetch_array($SelectInvoiceSql)){
									$InvoiceDate 	= date('M-Y', strtotime($IVList['invoice_dt']));
									$ReceivedDate 	= date('M-Y', strtotime($IVList['received_dt']));
									$IVList['invoice_mon_yr'] 	=  $InvoiceDate;
									$IVList['received_mon_yr'] 	=  $ReceivedDate;
									$InvoiceArr[] 	= $IVList; 
									array_push($InvoiceQtyMastArr,$IVList['qty']);
									array_push($InvoiceMonMastArr,$InvoiceDate);
								}
							}
						}
						echo "<br/>";
						
						$InvoiceQtyArr  = $InvoiceQtyMastArr;
						$UtilizedQtyArr = $UsedQtyArr;
						$InvoiceMonArr	= $InvoiceMonMastArr;
						$UtilizedMonArr	= $MonthYrArr;
						
						$i = 1; $x = 0;
						echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF'>";
						echo "<tr class='label'><td colspan='4' align='center'>Quantity of cement brought to site and 10CA qty. calculation</td></tr>";
						echo "<tr class='label'>";
						echo "<td align='center'>Invoice Month</td>";
						echo "<td align='center'>RAB/Billing Month</td>";
						echo "<td align='right'>Qty. Brought to Site (in ".$ConvUnitTo.") </td>";
						echo "<td align='right'>Qty. Eligible For 10CA </td>";
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
								$OutPutStr = $OutPutStr."@*@".$UtilizeQty."@*@".$cc_mbookno."@*@".$page;
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
								$OutPutStr = $OutPutStr."@*@".$InvoiceQty."@*@".$cc_mbookno."@*@".$page;
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
							<input type="hidden" name="txt_cc_esc_id" id="txt_cc_esc_id" value="<?php echo $cc_esc_id;?>">
							<input type="hidden" name="txt_cc_esc_rbn" id="txt_cc_esc_rbn" value="<?php echo $cc_esc_rbn;?>">
							<input type="hidden" name="txt_start_page" id="txt_start_page" value="<?php echo $start_page;?>">
							<input type="hidden" name="txt_end_page" id="txt_end_page" value="<?php echo $page;?>">
							<input type="hidden" name="txt_ccmbook" id="txt_ccmbook" value="<?php echo $cc_mbookno;?>">
							<input type="hidden" name="txt_cc_quarter" id="txt_cc_quarter" value="<?php echo $cc_quarter;?>">
							
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
									<input type="submit" name="submit" id="submit" value=" Save "/>
								</div>
							</div>
						</div>
					</blockquote>
            <!--==============================footer=================================-->
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
								  window.location.href = "Escalation_Cement_Site_Consump_General.php";
							});
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
			</script>
			</div>
			
           <?php include "footer/footer.html"; ?>
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
