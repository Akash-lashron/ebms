<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
include "library/common.php";
$msg = '';
function dt_display($ddmmyyyy)
{
 $dt=explode('-',$ddmmyyyy);
 $dd=$dt[2];
 $mm=$dt[1];
 $yy=$dt[0];
 return $dd . '/' . $mm . '/' . $yy;
}
$staffid = $_SESSION['sid'];
$userid  = $_SESSION['userid'];
$rbn     = $_SESSION["rbn"]; 
//echo $rbn;exit;
$abstsheetid    = $_SESSION["abstsheetid"];   $abstmbno = $_SESSION["abs_mbno"];  $abstmbpage  = $_SESSION["abs_page"];	
$fromdate       = $_SESSION['fromdate'];      $todate   = $_SESSION['todate'];    $abs_mbno_id = $_SESSION["abs_mbno_id"];
$paymentpercent = $_SESSION["paymentpercent"];
$emptypage = $_SESSION['emptypage'];
//echo $abstmbno; echo "NEXT".$abstmbpage;
function get_max_measurementbookid($measurementbookid_str)
{
	$measurementbookid_list = explode("*",rtrim($measurementbookid_str,"*"));
	$max_id = max($measurementbookid_list);
	return $max_id;
}
function get_dpm_paid_perc($subdivid,$part_pay_flag,$abstsheetid)
{
	$select_dpm_paid_perc_sql = "select * from measurementbook WHERE subdivid = '$subdivid' AND part_pay_flag = '$part_pay_flag' AND sheetid = '$abstsheetid'";
	$select_dpm_paid_perc_query = mysql_query($select_dpm_paid_perc_sql);
	$paid_percent = 0;
	if(mysql_num_rows($select_dpm_paid_perc_query)>0)
	{
		while($resList = mysql_fetch_array($select_dpm_paid_perc_query))
		{
			$paid_percent = $paid_percent + $resList['pay_percent'];
		}
	}
	return $paid_percent;
}
/*if($abstsheetid =='') { echo "<script>alert('Please try again...') </script>"; header('Location: AbsGenerate.php'); }*/
if($_POST["Submit"] == "Submit")
{	
	/*$pay_percent = $_POST['hid_perc_subdivid'];
	$dpm_pay_percent = $_POST['hid_dpm_withpartpay_result'];
	print_r($pay_percent);
	exit;*/
	$max_page_abs = $_POST['txt_maxpage'];
	$abstmbno = $_POST['txt_abstmbno'];
	$mbook_start_page_abs = get_mbook_startpage($abstmbno,$abstsheetid);
	$start_page_abs = explode('*', $mbook_start_page_abs);
	$insert_mybmook_sql_3 = "insert into mymbook set allotmentid = '$start_page_abs[1]', mbno = '$abstmbno', startpage = '$start_page_abs[0]', endpage = '$max_page_abs', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 0, flag = 'A'";
	$insert_mybmook_query_3 = mysql_query($insert_mybmook_sql_3);
	$update_asb_maxpage = "update mbookallotment set mbpage = '$max_page_abs' WHERE allotmentid	= '$abs_mbno_id' AND sheetid = '$abstsheetid'";
	$update_asb_maxpage_sql = mysql_query($update_asb_maxpage);
	$oldmbook_query = "SELECT * from oldmbook WHERE sheetid = '$abstsheetid'";
	$oldmbook_sql = mysql_query($oldmbook_query);
	if(mysql_num_rows($oldmbook_sql)>0)
	{
		while($res = mysql_fetch_array($oldmbook_sql))
		{
			$mbno = $res['mbname'];
			$mbooktype = $res['mbook_type'];
	$update_mbookallot_query = "UPDATE mbookallotment set active = '0' WHERE sheetid = '$abstsheetid' AND staffid = '$staffid' AND allotmentid = '".$res['old_id']."'";
	$update_mbookallot_sql = mysql_query($update_mbookallot_query);
	$update_aggreement_mbookallot_query = "UPDATE agreementmbookallotment set active = '0' WHERE sheetid = '$abstsheetid' AND allotmentid = '".$res['old_id']."'";
	$update_aggreement_mbookallot_sql = mysql_query($update_aggreement_mbookallot_query); 
	$oldmbook .= $res['mbname']."*"; 
	$mbook_start_page_old = get_mbook_startpage($mbno,$abstsheetid);
	$start_page_old = explode('*', $mbook_start_page_old);
	$insert_mybmook_sql = "insert into mymbook set allotmentid = '$start_page_old[1]', mbno = '$mbno', startpage = '$start_page_old[0]', endpage = '100', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 0, flag = '$mbooktype'";	
	$insert_mybmook_query = mysql_query($insert_mybmook_sql);	
		} 
	} 
    $currentquantity = trim($_POST['currentquantity']);
    $mbookquery="INSERT INTO measurementbook  (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbtotal, pay_percent, part_pay_flag, flag, rbn, active, userid,abstquantity,abstmbookno,abstmbpage)
                            SELECT   measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbtotal, pay_percent, part_pay_flag, flag, rbn, active, userid,(abstquantity+mbtotal), $abstmbno,$abstmbpage FROM measurementbook_temp";// WHERE flag =1 OR flag = 2"; //AND STAFFID
   	$mbooksql = mysql_query($mbookquery);   
    $sheetquery = "UPDATE sheet SET rbn = '$rbn' WHERE sheet_id ='$abstsheetid'";//AND STAFFID
    $sheetsql = dbQuery($sheetquery);
	$mbookpage_query = "select distinct mbno from mbookgenerate a WHERE NOT EXISTS(select mbname from oldmbook b WHERE a.mbno = b.mbname AND b.sheetid = '$abstsheetid') AND a.sheetid = '$abstsheetid'";
	$mbookpage_sql = mysql_query($mbookpage_query);
	while($result3 = mysql_fetch_array($mbookpage_sql))
	{
		$mbno = $result3['mbno'];
		$selectmaxpage_query = "select max(mbpage) from mbookgenerate WHERE sheetid	= '$abstsheetid' AND mbno ='".$result3['mbno']."'";
		//echo $selectmaxpage_query;
		$selectmaxpage_sql = mysql_query($selectmaxpage_query);
		$mbookmaxpage = @mysql_result($selectmaxpage_sql,'mbpage');
		$mbook_start_page = get_mbook_startpage($mbno,$abstsheetid);
		$strat_page = explode('*', $mbook_start_page);
		$insert_mybmook_sql_2 = "insert into mymbook set allotmentid = '$strat_page[1]', mbno = '$mbno', startpage = '$strat_page[0]', endpage = '$mbookmaxpage', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 1, flag = '$mbooktype'";
		$insert_mybmook_query_2 = mysql_query($insert_mybmook_sql_2);
		$upademaxpage_query = "update mbookallotment set mbpage = '$mbookmaxpage' WHERE sheetid = '$abstsheetid' AND mbno ='".$result3['mbno']."'";
		$upademaxpage_sql = mysql_query($upademaxpage_query);
		//echo $upademaxpage_query."<br/>";
	}//exit;
	$newmbooksql = "DELETE FROM oldmbook WHERE sheetid = '$abstsheetid'";// DELETE NEW MBOOK TABLE
	$result2 = dbQuery($newmbooksql);
	$mbookgeneratedelsql = "DELETE FROM mbookgenerate WHERE sheetid ='$abstsheetid'"; //DELETE MBOOK GENERATE TABLE
    $result1 = dbQuery($mbookgeneratedelsql);
	$mbooktempdelsql = "DELETE FROM measurementbook_temp WHERE sheetid ='$abstsheetid'"; //DELETE MBOOK GENERATE TABLE
    $result4 = dbQuery($mbooktempdelsql);
	/*$pay_percent = $_POST['hid_perc_subdivid'];
	for($x1=0;$x1<count($pay_percent);$x1++)
	{
		$explod_result = explode('*',$pay_percent[$x1]);
		$pay_perc_update_query = "update measurementbook set pay_percent = '$explod_result[0]', part_pay_flag = '0' where sheetid = '$abstsheetid' AND rbn = '$rbn' AND subdivid = '$explod_result[1]'";
		//echo $pay_perc_update_query;
		$result3 = dbQuery($pay_perc_update_query);
	}
	$dpm_pay_percent = $_POST['hid_dpm_withpartpay_result'];
	if(($dpm_pay_percent != "") && ($dpm_pay_percent != 0))
	{
		for($x3 =0;$x3<count($dpm_pay_percent);$x3++)
		{
			if($dpm_pay_percent[$x3] != "")
			{
				$explod_result1 = explode('*',$dpm_pay_percent[$x3]);
				if(($explod_result1[2] != "") && ($explod_result1[2] != 0))
				{
					$dpm_payperc_insert_query = "insert into measurementbook set staffid = '$staffid', pay_percent = '$explod_result1[2]', mbtotal = '0', part_pay_flag = '$explod_result1[1]', subdivid = '$explod_result1[0]', sheetid = '$abstsheetid', rbn = '$rbn'";
					//echo $dpm_payperc_insert_query;exit;
					$result4 = dbQuery($dpm_payperc_insert_query);
				}
			}
			
		}
	}*/

    header('Location: AbstractBookPrint_Partpay.php');   
}
/*if($_POST["Back"] == "Back")
{
     header('Location: AbstractBookPrint_Partpay.php');
}*/
$query = "SELECT sheet_id, sheet_name, work_order_no, work_name, tech_sanction, name_contractor, agree_no, rebate_percent, rbn FROM sheet WHERE sheet_id ='$abstsheetid' ";
//echo $query;
$sqlquery = mysql_query($query);
if ($sqlquery == true) 
{
    $List = mysql_fetch_object($sqlquery);
    	$work_name = $List->work_name;    
		$tech_sanction = $List->tech_sanction;  
    	$name_contractor = $List->name_contractor;    
		$agree_no = $List->agree_no;
		$overall_rebate_perc = $List->rebate_percent; 
		$runn_acc_bill_no = $rbn;
		$work_order_no = $List->work_order_no; /*   if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
 		$length = strlen($work_name);
 		$start_line = ceil($length/70);  
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Abstrack MBook</title>
        <link rel="stylesheet" href="script/font.css" />
		<!--<link rel="stylesheet" href="Font style/font.css" />-->
     <script type="text/javascript">
		function showpage(textvalue,txtvalue)
		{
			//alert("text   " +textvalue +"  value   " +txtvalue)
			document.getElementById(textvalue).value = "B/f from P"+txtvalue +" TMS";
			  
		}
   	</script>
     <style>
		 .largetextbox
		 {
			border-style:none;
			width:95px;
			text-align:right;
			pointer-events: none;
		 }
		 .percenttextbox
		 {
		 	width:28px;
			text-align:center;
			border: 0px solid #219286;
			box-shadow: 0 0 5px #219286;
    		border-radius: 2px;
		 }
		 .percenttextbox:focus
		 {
		 	width:28px;
			text-align:center;
			outline: none;
			border-color: #9ecaed;
			box-shadow: 0 0 10px red;
		 }
		.labelheadblue
		{
			color:#0000CD;
			font-weight:bold;
			font-size:12px;
		}
		.labelcontentblue
		{
			color:#0000CD;
			font-weight:bold;
			font-size:12pt;	
		}
		.right {
			position: absolute;
			right: 0px;
			width: 300px;
			background-color: #b0e0e6;
		}
		table{ border-collapse: collapse; }
		td { border: 1px solid #CACACA; }
		@media screen {
				div.divFooter {
					display: none;
				}
		}
		@media print {
				div.divFooter {
					position: fixed;
					bottom: 0;
				}
				.printbutton
				{
				display: none !important;
				 }
				 .printstyle
				 {
				 	display: none !important;
				 }
		}
	</style>
    </head>

    <script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
    <script language="javascript" type="text/javascript" src="script/validfn.js"></script>
	<link rel="stylesheet" href="css/button_style.css"></link>
	<link rel="stylesheet" href="js/jquery-ui.css">
  	<script src="js/jquery-1.10.2.js"></script>
  	<script src="js/jquery-ui.js"></script>
  	<link rel="stylesheet" href="/resources/demos/style.css">
	<script src="js/printPage.js"></script>
    <script type="text/javascript" language="javascript">
        function pnr()
        {
            x = confirm("NOTE: Set Paper Size A4, and 0.5 inch Margin to TOP,BOTTOM,LEFT,RIGHT.\n\nReady to Print-out ?")
            if (x == true)
            {
                document.getElementById("btn_print").style.display = 'none'
                window.print();
            }
        }
		function slm_calculation(obj,slm_textboxcnt,rate,qty, page)
		{
			var textboxid = obj.id;
			var percent = obj.value;
			var slmqty = qty; 
			var pageno = page;
			var new_rate = Number(rate)*Number(percent)/100;
			if(percent <= 100)
			{
				document.getElementById("txt_rate_percent_slm"+slm_textboxcnt).value = new_rate.toFixed(2)+" / "+rate;
			}
			else
			{
				alert("Enterd % of amount should be equal to or less than 100");
				return false;
				exit();
			}
			var subdivid = document.getElementById("hid_subdivid"+slm_textboxcnt).value; // get sudiv id
			document.getElementById("hid_perc_subdivid"+slm_textboxcnt).value = percent+"*"+subdivid; // assign subdivid and percent as string for db update
			<!--- NEW SLM CALCULATION SECTION STARTS HERE----->
			var slm_amt_new = (Number(rate) * Number(slmqty) * Number(percent))/100;
			var slm_amt_old = document.getElementById("hid_amount_slm"+slm_textboxcnt).value;
			document.getElementById("txt_amount_slm"+slm_textboxcnt).value = slm_amt_new.toFixed(2);
			document.getElementById("hid_amount_slm"+slm_textboxcnt).value = slm_amt_new.toFixed(2);
			<!---(SLM+DPM) TOTAL AMOUNT SECTION STARTS HERE----->
			var slm_totalamt_current = document.getElementById("txt_slm_total_amt"+slm_textboxcnt).value;
			var slm_totalamt_new = Number(slm_totalamt_current)-Number(slm_amt_old)+Number(slm_amt_new);
			document.getElementById("txt_slm_total_amt"+slm_textboxcnt).value = slm_totalamt_new.toFixed(2);
			<!--- SLM TO DATE AMOUNT SECTION  STARTS HERE----->
			document.getElementById("txt_amount_slm_todate"+slm_textboxcnt).value = slm_amt_new.toFixed(2);
			<!--- SLM TOTAL TODATE AMOUNT SECTION ( SLM + DPM ) STARTS HERE----->
			var total_amt_todate_current = document.getElementById("txt_totalamt_todate"+slm_textboxcnt).value;
			var total_amt_todate_new = Number(total_amt_todate_current)-Number(slm_amt_old)+Number(slm_amt_new);
			document.getElementById("txt_totalamt_todate"+slm_textboxcnt).value = total_amt_todate_new.toFixed(2);
			<!--- SLM TOTAL COST SECTION STARTS HERE----->
			var total_cost_slm_current = document.getElementById("txt_total_cost").value;
			var total_cost_slm_new = Number(total_cost_slm_current)-Number(slm_amt_old)+Number(slm_amt_new);
			document.getElementById("txt_total_cost").value = total_cost_slm_new.toFixed(2);
			<!--- TO DATE TOTAL COST SECTION STARTS HERE----->
			var total_cost_todate_current = document.getElementById("txt_totalcost_todate").value;
			var total_cost_todate_new = Number(total_cost_todate_current)-Number(slm_amt_old)+Number(slm_amt_new);
			document.getElementById("txt_totalcost_todate").value = total_cost_todate_new.toFixed(2);
			
			
			
			
			<!--- SLM LESS OVERALL REBATE PERCENT AMT SECTION STARTS HERE----->
			var overall_rebate_perc = document.getElementById("hid_overall_rebate_perc").value;
			
			var rebate_perc_amt_slm_new = Number(total_cost_slm_new.toFixed(2))*Number(overall_rebate_perc)/100;
			document.getElementById("txt_slm_rebate_perc").value = rebate_perc_amt_slm_new.toFixed(2);
			<!--- TO DATE LESS OVERALL REBATE PERCENT AMT SECTION STARTS HERE----->
			var rebate_perc_amt_todate_new = Number(total_cost_todate_new.toFixed(2))*Number(overall_rebate_perc)/100;
			document.getElementById("txt_todate_rebate_perc").value = rebate_perc_amt_todate_new.toFixed(2);
			
			<!--- SLM GROSS AMOUNT SECTION STARTS HERE----->
			var gross_amt_slm_new = Number(total_cost_slm_new.toFixed(2))-Number(rebate_perc_amt_slm_new.toFixed(2));
			document.getElementById("txt_slm_gross_amt").value = gross_amt_slm_new.toFixed(2);
			<!--- TO DATE GROSS AMOUNT SECTION STARTS HERE----->
			var gross_amt_todate_new = Number(total_cost_todate_new.toFixed(2))-Number(rebate_perc_amt_todate_new.toFixed(2));
			document.getElementById("txt_todate_gross_amt").value = gross_amt_todate_new.toFixed(2);
			
			
			
			
			<!---- C/O and B/F TOTAL COST SECTION STARTS HERE ------->
			var page_str1 = document.getElementById("hid_page_str").value;
			var page_str2 = page_str1.substring(page_str1.indexOf(pageno) + 0);
			var page_str = page_str2.split("@");
			for(var x=0; x<page_str.length; x++)
			{
				<!--- C/O and B/F of SLM TOTAL COST SECTION STARTS HERE ----------->
				var co_slm_amt_current = document.getElementById("txt_co_slm_amt"+page_str[x]).value;
				var co_slm_amt_new = Number(co_slm_amt_current)-Number(slm_amt_old)+Number(slm_amt_new);
				document.getElementById("txt_co_slm_amt"+page_str[x]).value = co_slm_amt_new.toFixed(2);
				document.getElementById("txt_bf_slm_amt"+page_str[x]).value = co_slm_amt_new.toFixed(2);
				<!--- C/O and B/F of TODATE TOTAL COST SECTION STARTS HERE ----------->
				var co_totalamt_todate_current = document.getElementById("txt_co_totalamt_todate"+page_str[x]).value;
				var co_totalamt_todate_new = Number(co_totalamt_todate_current)-Number(slm_amt_old)+Number(slm_amt_new);
				document.getElementById("txt_co_totalamt_todate"+page_str[x]).value = co_totalamt_todate_new.toFixed(2);
				document.getElementById("txt_bf_totalamt_todate"+page_str[x]).value = co_totalamt_todate_new.toFixed(2);
				
			}
		}
		function calculate_partpay_dpm(dpm_textboxcnt,slm_textboxcnt,obj,page,rate,paid_percent)
		{
			var textboxid = obj.id;
			var percent = obj.value;
			var pageno = page;
			var paid_percent = paid_percent;
			var total_percent = (Number(percent)+Number(paid_percent));
			var rate_perc_paid = Number(rate)*Number(paid_percent)/100;
			var rate_perc_new = Number(rate)*Number(percent)/100;
			var rate_perc_total = (Number(rate_perc_paid)+Number(rate_perc_new));
			if(total_percent <= 100)
			{
				document.getElementById("txt_dpm_pay_rate"+dpm_textboxcnt).value = rate_perc_total.toFixed(2)+'/'+rate;
			}
			else
			{
				var dpm_paypercent_remain = (100 - Number(paid_percent));
				alert("Enterd % of amount should be equal to or less than "+dpm_paypercent_remain+"%.");
				return false;
				exit();
			}
			var dpm_measurementbookid = document.getElementById("hid_dpm_measurementbookid"+dpm_textboxcnt).value; 
			var subdivid = document.getElementById("hid_subdivid"+slm_textboxcnt).value;
			document.getElementById("hid_dpm_withpartpay_result"+dpm_textboxcnt).value = subdivid+"*"+dpm_measurementbookid+"*"+percent;
			var dpm_partpay_qty = document.getElementById("hid_dpm_partpay_qty"+dpm_textboxcnt).value;
			<!---NEW DPM AMOUNT CALCULATION SECTION STARTS HERE----->
			var dpm_amt_new = Number(dpm_partpay_qty)*Number(rate)*Number(percent)/100;
			var dpm_amt_old = document.getElementById("hid_partpay_amount_dpm"+dpm_textboxcnt).value;
			document.getElementById("txt_partpay_amount_dpm"+dpm_textboxcnt).value = dpm_amt_new.toFixed(2);
			document.getElementById("hid_partpay_amount_dpm"+dpm_textboxcnt).value = dpm_amt_new.toFixed(2);
			<!---(SLM+DPM) TOTAL AMOUNT SECTION STARTS HERE----->
			var slm_totalamt_current = document.getElementById("txt_slm_total_amt"+slm_textboxcnt).value;
			var slm_totalamt_new = Number(slm_totalamt_current)-Number(dpm_amt_old)+Number(dpm_amt_new);
			document.getElementById("txt_slm_total_amt"+slm_textboxcnt).value = slm_totalamt_new.toFixed(2);
			<!--- DPM TO DATE AMOUNT SECTION STARTS HERE----->
			var dpm_amt_todate_current = document.getElementById("txt_dpm_todate_amt"+dpm_textboxcnt).value;
			var dpm_amt_todate_new = Number(dpm_amt_todate_current)-Number(dpm_amt_old)+Number(dpm_amt_new);
			document.getElementById("txt_dpm_todate_amt"+dpm_textboxcnt).value = dpm_amt_todate_new.toFixed(2);
			<!--- DPM TOTAL TO DATE AMOUNT ( SLM + DPM ) SECTION STARTS HERE----->
			var total_amt_todate_current = document.getElementById("txt_totalamt_todate"+slm_textboxcnt).value;
			var total_amt_todate_new = Number(total_amt_todate_current)-Number(dpm_amt_old)+Number(dpm_amt_new);
			document.getElementById("txt_totalamt_todate"+slm_textboxcnt).value = total_amt_todate_new.toFixed(2);
			<!--- DPM TOTAL COST (RIGHT SIDE )SECTION STARTS HERE----->
			var total_cost_slm_current = document.getElementById("txt_total_cost").value;
			var total_cost_slm_new = Number(total_cost_slm_current)-Number(dpm_amt_old)+Number(dpm_amt_new);
			document.getElementById("txt_total_cost").value = total_cost_slm_new.toFixed(2);
			<!--- TO DATE TOTAL COST SECTION STARTS HERE----->
			var total_cost_todate_current = document.getElementById("txt_totalcost_todate").value;
			var total_cost_todate_new = Number(total_cost_todate_current)-Number(dpm_amt_old)+Number(dpm_amt_new);
			document.getElementById("txt_totalcost_todate").value = total_cost_todate_new.toFixed(2);
			
			
			
			
			<!--- SLM LESS OVERALL REBATE PERCENT AMT SECTION STARTS HERE----->
			var overall_rebate_perc = document.getElementById("hid_overall_rebate_perc").value;
			
			var rebate_perc_amt_slm_new = Number(total_cost_slm_new.toFixed(2))*Number(overall_rebate_perc)/100;
			document.getElementById("txt_slm_rebate_perc").value = rebate_perc_amt_slm_new.toFixed(2);
			<!--- TO DATE LESS OVERALL REBATE PERCENT AMT SECTION STARTS HERE----->
			var rebate_perc_amt_todate_new = Number(total_cost_todate_new.toFixed(2))*Number(overall_rebate_perc)/100;
			document.getElementById("txt_todate_rebate_perc").value = rebate_perc_amt_todate_new.toFixed(2);
			
			<!--- SLM GROSS AMOUNT SECTION STARTS HERE----->
			var gross_amt_slm_new = Number(total_cost_slm_new.toFixed(2))-Number(rebate_perc_amt_slm_new.toFixed(2));
			document.getElementById("txt_slm_gross_amt").value = gross_amt_slm_new.toFixed(2);
			<!--- TO DATE GROSS AMOUNT SECTION STARTS HERE----->
			var gross_amt_todate_new = Number(total_cost_todate_new.toFixed(2))-Number(rebate_perc_amt_todate_new.toFixed(2));
			document.getElementById("txt_todate_gross_amt").value = gross_amt_todate_new.toFixed(2);
			
			
			
			
			<!---- C/O and B/F TOTAL COST SECTION STARTS HERE ------->
			var page_str1 = document.getElementById("hid_page_str").value;
			var page_str2 = page_str1.substring(page_str1.indexOf(pageno) + 0);
			var page_str = page_str2.split("@");
			for(var x=0; x<page_str.length; x++)
			{
				<!--- C/O and B/F of SLM TOTAL COST SECTION STARTS HERE ----------->
				var co_slm_amt_current = document.getElementById("txt_co_slm_amt"+page_str[x]).value;
				var co_slm_amt_new = Number(co_slm_amt_current)-Number(dpm_amt_old)+Number(dpm_amt_new);
				document.getElementById("txt_co_slm_amt"+page_str[x]).value = co_slm_amt_new.toFixed(2);
				document.getElementById("txt_bf_slm_amt"+page_str[x]).value = co_slm_amt_new.toFixed(2);
				<!--- C/O and B/F of TODATE TOTAL COST SECTION STARTS HERE ----------->
				var co_totalamt_todate_current = document.getElementById("txt_co_totalamt_todate"+page_str[x]).value;
				var co_totalamt_todate_new = Number(co_totalamt_todate_current)-Number(dpm_amt_old)+Number(dpm_amt_new);
				document.getElementById("txt_co_totalamt_todate"+page_str[x]).value = co_totalamt_todate_new.toFixed(2);
				document.getElementById("txt_bf_totalamt_todate"+page_str[x]).value = co_totalamt_todate_new.toFixed(2);
			}

		}
		function goBack()
		{
			url = "AbstractBookPrint_Partpay.php";
			window.location.replace(url);
		}
    </script>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
<body bgcolor="black" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--<table width="1087px" style="position:fixed; text-align:center; left:88px;" height="60px" align="center" bgcolor="#20b2aa" class='header label printstyle'>
<tr>
<td style="color:#FFFFFF; border:none; font-size:18px;">ABSTRACT MEASUREMENT BOOK - PART PAYMENT</td>
</tr>
</table><br/><br/><br/>-->
<form name="form" method="post" onsubmit="return confirm('Do you really want to submit the Book?');">
<?php
$title = '<table width="1087px" border="0"  cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="right" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
echo $title;
$table = $table . "<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='label' >";
$table = $table . "<tr>";
$table = $table . "<td width='17%' class='labelbold'>Name of work:</td>";
$table = $table . "<td width='43%' style='word-wrap:break-word' class='label'>" .$work_name."</td>";
$table = $table . "<td width='18%' class='labelbold'>Name of the contractor</td>";
$table = $table . "<td width='22%' class='label'>" . $name_contractor . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class='labelbold'>Technical Sanction No.</td>";
$table = $table . "<td class='label'>" . $tech_sanction . "</td>";
$table = $table . "<td class='labelbold'>Agreement No.</td>";
$table = $table . "<td class='label'>" . $agree_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class='labelbold'>Work order No.</td>";
$table = $table . "<td class='label'>" . $work_order_no . "</td>";
$table = $table . "<td class='labelbold'>Running Account bill No.</td>";
$table = $table . "<td class='label'>" . $runn_acc_bill_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td colspan ='4' class='label' align='center'>Abstract Cost for Ware House for the period of ".date("d/m/Y", strtotime($fromdate))." to ".date("d/m/Y", strtotime($todate))."</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
$table = $table . "<table width='1087px'  bgcolor='#FFFFFF' border='1' cellpadding='1' cellspacing='1' align='center' id='mbookdetail' class='label'>";
$table = $table . "<tr>";
$table = $table . "<td  align='center' class='labelsmall' width='7%' rowspan='2'>Item No.</td>";
$table = $table . "<td  align='center' class='labelsmall' width='19%' rowspan='2'>Description of work</td>";
$table = $table . "<td  align='center' class='labelsmall' width='8%' rowspan='2'>Contents of Area</td>";
$table = $table . "<td  align='center' class='labelsmall' width='7%' rowspan='2'>Rate<br />Rs.  P.</td>";
$table = $table . "<td  align='center' class='labelsmall' width='4%' rowspan='2'>Per</td>";
/*$table = $table . "<td  align='center' class='labelsmall labelheadblue'  width='3%' rowspan='2'>Pay<br/>(%)</td>";*/
$table = $table . "<td  align='center' class='labelsmall' width='9%' rowspan='2'>Total value to Date<br />Rs.  P.</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='3'>Deduct previous Measurements</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='3'>Since Last Measurement</td>";
/*$table = $table . "<td  align='center' class='labelsmall labelheadblue' width='4%' rowspan='2'></td>";*/
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td width='5%' align='center' class='labelsmall'>Page</td>";
$table = $table . "<td width='7%' align='center' class='labelsmall'>Quantity</td>";
/*$table = $table . "<td width='3%' align='center' class='labelsmall labelheadblue'>Pay<br/>(%)</td>";*/
$table = $table . "<td  width='10%'align='center' class='labelsmall'>Amount<br />Rs.  P.</td>";
$table = $table . "<td width='6%' align='center' class='labelsmall'>Quantity</td>";
//$table = $table . "<td width='3%' align='center' class='labelsmall'>Pay<br/>(%)</td>";
$table = $table . "<td  width='9%' align='center' class='labelsmall'>Value<br />Rs.  P.</td>";
$table = $table . "<td width='5%' align='center' class='labelsmall'>Remark</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
?>
<?php echo $table; ?>

<table width='1087px' border='0' cellpadding='1' cellspacing='1' align='center' class='label' bgcolor="#FFFFFF">
<?php 
$prev_subdivid = ""; $prev_measure_qty = 0;$currentline = $start_line + 6; $page = $abstmbpage;//echo $currentline;
$unionqur = "(SELECT subdivid FROM measurementbook_temp WHERE sheetid = '$abstsheetid' AND  part_pay_flag = '0') UNION (SELECT subdivid  FROM measurementbook WHERE sheetid = '$abstsheetid' AND part_pay_flag = '0')";
//echo $unionqur;exit; 
$unionsql = mysql_query($unionqur);
while($Listsubdivid = mysql_fetch_array($unionsql)) { $subdivid_list .= $Listsubdivid['subdivid']."*"; }
$subdivisionlist_1 = explode("*",rtrim($subdivid_list,"*"));
//echo $subdivid_list;exit;
natsort($subdivisionlist_1);
foreach($subdivisionlist_1 as $key => $summ_1)
{
   if($summ_1 != "")
   {
      $subdivisionlist_2 .= $summ_1.",";
   }
}
$subdivisionlist = explode(',',rtrim($subdivisionlist_2,","));
$co_bf_tot_qty = 0; $co_bf_tot_amt = 0; $co_bf_prev_total_qty = 0; $co_bf_prev_total_amt = 0; $co_bf_slast_total_qty = 0; $co_bf_slast_total_amt = 0;
$slm_textboxid_cnt = 1;  $dpm_todate_amt = 0; $dpm_todate_amt_total = 0; $dpm_paid_amt_total = 0; $paymentpercent = 0;
for($i=0;$i<count($subdivisionlist);$i++)
{
	$cnt_1 = 0; $cnt_2 = 0; $slm_total_amt = 0; $dpmqty_with_100_perc_str = "";
	$decimal = get_decimal_placed($subdivisionlist[$i],$abstsheetid);
	$actual_rebate_rate = getactual_rebaterate($abstsheetid,$subdivisionlist[$i]);
	$mbookquery = "SELECT * FROM measurementbook WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid' AND  part_pay_flag = '0'";
	$mbooksql = mysql_query($mbookquery);
	if(mysql_num_rows($mbooksql)>0)
	{
		$temp1 = 1;
		while($MBList = mysql_fetch_array($mbooksql))
		{

			if($MBList['pay_percent'] == 100)
			{
				$mesurementbook_details .= $MBList['subdivid']."*".$MBList['mbtotal']."*".$MBList['abstmbookno']."*".$MBList['abstmbpage']."*";
				$cnt_1++;
			}
			if($MBList['pay_percent'] != 100)
			{
				$mesurementbook_details_new .= $MBList['pay_percent'].",".$MBList['measurementbookid'].",".$MBList['subdivid'].",".$MBList['mbtotal'].",".$MBList['abstmbookno'].",".$MBList['abstmbpage']."*";
				$cnt_2++;
			}

		}
		if($cnt_1>0)
		{
			$explodval = explode("*",rtrim($mesurementbook_details,"*"));//echo count($explodval)."<br/>";
			$prev_measurement_qty = 0;
			for($j=0;$j<count($explodval);$j+=4)
			{
				$prev_measurement_qty = $prev_measurement_qty + $explodval[$j+1];
				$mbno_dp = $explodval[$j+2];
				$mbpageno_dp = $explodval[$j+3];
			}
		}
	}
	else
	{
		$temp1 = 0;
		$prev_measurement_qty = 0;
		$mbno_dp = "";
		$mbpageno_dp = "";
	} 

	
	$mesurementbook_details = "";
	$mbookgeneratequery = "SELECT * FROM measurementbook_temp WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid' AND part_pay_flag = '0'";
	$mbookgeneratesql = mysql_query($mbookgeneratequery);
	if(mysql_num_rows($mbookgeneratesql)>0)
	{
		while($MBGENList = mysql_fetch_array($mbookgeneratesql))
		{
			$mesurementgenerate_details .= $MBGENList['subdivid']."*".$MBGENList['mbtotal']."*".$MBGENList['mbno']."*".$MBGENList['mbpage']."*".$MBGENList['pay_percent']."*";
		}
		$explodedval = explode("*",rtrim($mesurementgenerate_details,"*"));//echo $mesurementgenerate_details."<br/>";
		$sincelast_measurement_qty = 0;
		for($k=0;$k<count($explodedval);$k+=5)
		{
			$sincelast_measurement_qty = $sincelast_measurement_qty + $explodedval[$k+1];
			$mbno_sl = $explodedval[$k+2];
			$mbpageno_sl = $explodedval[$k+3];
			$paymentpercent = $explodedval[$k+4];
		}
	}
	else
	{
		$sincelast_measurement_qty = 0;
		$mbno_sl = "";
		$mbpageno_sl = "";	
		$paymentpercent = 0;	
	} 
$schduledetails = getschduledetails($abstsheetid,$subdivisionlist[$i]);
$rateandremarks = explode('*',$schduledetails);
$descript_len = strlen(getscheduledescription($subdivisionlist[$i]));
$line_increment = ceil($descript_len/150);	
$currentline = $currentline + $line_increment;
if($currentline>32)
	{
?>
<tr>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='19%' class='labelsmall'><?php echo "C/o to Page ".($page+1)."/Abstract MB No ".$abstmbno;?></td>
	<td  align='right' width='8%' class='labelsmall'><?php //echo $co_bf_tot_qty; ?></td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" class="largetextbox" name="txt_co_totalamt_todate" id="txt_co_totalamt_todate<?php echo $page; ?>" value="<?php echo number_format($co_bf_tot_amt, 2, '.', ''); ?>"  />
	<input type="hidden" name="hid_co_totalamt_todate" id="hid_co_totalamt_todate<?php echo $page; ?>" value="<?php echo number_format($co_bf_tot_amt, 2, '.', ''); ?>"  />
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='7%' class='labelsmall'><?php //echo $co_bf_prev_total_qty; ?></td>
	<!--<td  align='right' width='3%' class='labelsmall'><?php //echo $co_bf_slast_total_qty; ?></td>-->
	<td  align='right' width='10%' class='labelsmall'><?php echo number_format($co_bf_prev_total_amt, 2, '.', ''); ?></td>
	<td  align='right' width='6%' class='labelsmall'><?php //echo $co_bf_slast_total_qty; ?></td>
	<!--<td  align='right' width='3%' class='labelsmall'><?php //echo $co_bf_slast_total_qty; ?></td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" class="largetextbox" name="txt_co_slm_amt" id="txt_co_slm_amt<?php echo $page; ?>" value="<?php echo number_format($co_bf_slast_total_amt, 2, '.', ''); ?>"  />
	<input type="hidden" name="hid_co_slm_amt" id="hid_co_slm_amt<?php echo $page; ?>" value="<?php echo number_format($co_bf_slast_total_amt, 2, '.', ''); ?>"  />
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>
<?php 
		echo "<tr style='border:none'><td colspan='12' style='border:none' align='center'><br/>Page ".$page."</td></tr>";
		echo "</table>"; //echo "SSSS".$currentline;

		echo "<p  style='page-break-after:always;'></p>";
		$currentline = $start_line + 8;
		$page++;
		echo $title;
		echo $table; 
		echo "<table width='1087px' bgcolor='white'   border='0' cellpadding='0' cellspacing='0' align='center' class='label'>";
?>
<tr>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='19%' class='labelsmall'><?php echo "B/f from Page ".$prevpage."/Abstract MB No ".$abstmbno;?></td>
	<td  align='right' width='8%' class='labelsmall'><?php //echo $co_bf_tot_qty; ?></td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" class="largetextbox" name="txt_bf_totalamt_todate" id="txt_bf_totalamt_todate<?php echo $prevpage; ?>" value="<?php echo number_format($co_bf_tot_amt, 2, '.', ''); ?>"  />
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='7%' class='labelsmall'><?php //echo $co_bf_prev_total_qty; ?></td>
	<!--<td  align='right' width='3%' class='labelsmall'><?php //echo $co_bf_slast_total_qty; ?></td>-->
	<td  align='right' width='10%' class='labelsmall'><?php echo number_format($co_bf_prev_total_amt, 2, '.', ''); ?></td>
	<td  align='right' width='6%' class='labelsmall'><?php //echo $co_bf_slast_total_qty; ?></td>
	<!--<td  align='right' width='3%' class='labelsmall'><?php //echo $co_bf_slast_total_qty; ?></td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" class="largetextbox" name="txt_bf_slm_amt" id="txt_bf_slm_amt<?php echo $prevpage; ?>" value="<?php echo number_format($co_bf_slast_total_amt, 2, '.', ''); ?>"  />
	
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>
<?php 
	}
?>
<tr>
	<td width="7%" align="center"><?php echo getsubdivname($subdivisionlist[$i]);?></td>
	<td colspan="8"><?php echo getscheduledescription($subdivisionlist[$i]); ?></td>
	<td><?php echo "&nbsp;" ?></td>
	<!--<td><?php echo "&nbsp;" ?></td>-->
	<td><?php echo "&nbsp;" ?></td>
	<td>&nbsp;</td>
</tr>
<?php
$mbooktype_query = "select flag from mbookgenerate WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid'";
//echo "$mbooktype_query"."<br/>";
$mbooktype_sql = mysql_query($mbooktype_query);
$flagtype = @mysql_result($mbooktype_sql,0,'flag');
if($flagtype == 1) { $mbookdescription = "/General MB No."; }
if($flagtype == 2) { $mbookdescription = "/Steel MB No."; }

$partpay_dpm_qty = 0;
if($temp1 != 0) 
{ 
	if($cnt_2>0)
	{ 
		$explodval2 = explode("*",$mesurementbook_details_new);
		natsort($explodval2);
		foreach($explodval2 as $key => $sum2)
		{
		   if($sum2 != "")
		   {
			  $sum3 .= $sum2.",";
		   }
		}
		$payment_dpm_row = explode(",",rtrim($sum3,','));
		$old_percent = ""; $cnt = 0; $old_partpay_dpm_qty = 0; $c = 0; $dpm_textbox_cnt = 0;
		for($x2=0; $x2<count($payment_dpm_row); $x2+=6)
		{
			$dpm_textbox_cnt++;
			$new_percent = $payment_dpm_row[$x2];
			$new_partpay_dpm_qty = $payment_dpm_row[$x2+3];
			$new_measurementbookid = $payment_dpm_row[$x2+1];
			$new_abst_bmook_no = $payment_dpm_row[$x2+4];
			$new_abst_bmook_page = $payment_dpm_row[$x2+5];
			if($old_percent != "")
			{
				if($old_percent == $new_percent)
				{
					$partpay_dpm_qty = $partpay_dpm_qty + $new_partpay_dpm_qty;
					$measurementbookid_str .= $new_measurementbookid."*";
					$cnt++;
					if($x2 == (count($payment_dpm_row)-6))
					{
						$dpm_textbox_cnt++;
						$max_measurementbookid = get_max_measurementbookid($measurementbookid_str);
						$dpm_paid_percent = get_dpm_paid_perc($subdivisionlist[$i],$max_measurementbookid,$abstsheetid);
						$check_percent = $new_percent + $dpm_paid_percent;
						if($check_percent<100)
						{
						$dpm_todate_amt = number_format(($partpay_dpm_qty*$rateandremarks[0]*$new_percent/100), 2, '.', '');
						$dpm_todate_amt_total = $dpm_todate_amt_total + $dpm_todate_amt;
						$dpm_paid_amt = number_format(($partpay_dpm_qty*$rateandremarks[0]*$new_percent/100), 2, '.', '');
						$dpm_paid_amt_total = $dpm_paid_amt_total + $dpm_paid_amt;
						?>
						<tr>
							<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
							<td  align='left' width='19%' class='labelsmall'><?php echo "Prev-Qty Vide P ".$new_abst_bmook_page."/ Abstract MB No ".$new_abst_bmook_no; ?></td>
							<td  align='right' width='8%' class='labelsmall'>
							<?php echo $partpay_dpm_qty; ?>
							<input type="hidden" name="hid_dpm_partpay_qty" id="hid_dpm_partpay_qty<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $partpay_dpm_qty; ?>"  />
							</td>
							<td  align='left' width='7%' class='labelsmall'>
							<input type="text" name="txt_dpm_pay_rate" id="txt_dpm_pay_rate<?php echo $dpm_textbox_cnt; ?>" value="<?php echo ($rateandremarks[0]*$new_percent/100).'/'.$rateandremarks[0]; ?>" style="width:69px;" />
							</td>
							<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
							<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
							<td  align='right' width='9%' class='labelsmall'>
							<input type="text" name="txt_dpm_todate_amt" id="txt_dpm_todate_amt<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $dpm_todate_amt; ?>" class="largetextbox" />
							<!--<input type="hidden" name="hid_dpm_todate_amt" id="hid_dpm_todate_amt<?php //echo $dpm_textbox_cnt; ?>" value="<?php //echo $dpm_todate_amt; ?>" />-->
							</td>
							<td  align='left' width='5%' class='labelsmall'><?php //echo $slm_textboxid_cnt; ?></td>
							<td  align='right' width='7%' class='labelsmall'><?php echo $partpay_dpm_qty;//echo $new_dpm_rbn; ?></td>
							<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
							<td  align='right' width='10%' class='labelsmall'><?php echo $dpm_paid_amt; ?></td>
							<td  align='right' width='6%' class='labelsmall'><?php echo $partpay_dpm_qty; ?></td>
							<!--<td  align='right' width='3%' class='labelsmall'>
							<a title="<?php echo 'Remaining - '.(100-$new_percent).'%'; ?>" class="tooltip">
							<input type="text" name="txt_dpm_with_partpay" id="txt_dpm_with_partpay<?php echo $dpm_textbox_cnt; ?>" value="<?php echo 0;//100-$new_percent; ?>" class="percenttextbox" onblur="calculate_partpay_dpm(<?php echo $dpm_textbox_cnt; ?>,<?php echo $slm_textboxid_cnt; ?>,this,<?php echo $page; ?>,<?php echo $rateandremarks[0]; ?>,<?php echo $new_percent; ?>);" />
							</a>
							<!--<input type="hidden" name="hid_dpm_with_partpay_paid" id="hid_dpm_with_partpay_paid<?php //echo $dpm_textbox_cnt; ?>" value="<?php //echo $new_percent; ?>" />-->
							<!--<input type="hidden" name="hid_dpm_measurementbookid" id="hid_dpm_measurementbookid<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $max_measurementbookid; ?>"  />
							<input type="hidden" name="hid_dpm_withpartpay_result[]" id="hid_dpm_withpartpay_result<?php echo $dpm_textbox_cnt; ?>"/>
							</td>-->
							<td  align='right' width='9%' class='labelsmall'>
							<input type="text" name="txt_partpay_amount_dpm" id="txt_partpay_amount_dpm<?php echo $dpm_textbox_cnt; ?>" class="largetextbox labelsmall" value="<?php echo '0.00';//number_format(($partpay_dpm_qty*$rateandremarks[0]*(100-$new_percent)/100), 2, '.', ''); ?>" />
							<input type="hidden" name="hid_partpay_amount_dpm" id="hid_partpay_amount_dpm<?php echo $dpm_textbox_cnt; ?>" value="<?php echo 0;//number_format(($partpay_dpm_qty*$rateandremarks[0]*(100-$new_percent)/100), 2, '.', ''); ?>" />
							</td>
							<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
						</tr>
						<?php
						}
						else
						{
							$dpmqty_with_100_perc_str .= $partpay_dpm_qty."*".$new_abst_bmook_page."*".$new_abst_bmook_no."*";
							$total_quantity = $total_quantity + $partpay_dpm_qty;
							//echo $x2." 1= ".$dpmqty_with_100_perc_str."<br/>";
						}
						$measurementbookid_str = "";
					}		
				}
				if($old_percent != $new_percent)
				{
					//$measurementbookid_str .= $new_measurementbookid."*"; --------***DONT ADD THESE HERE..BCOZ THIS LOOP EXECUTED WITH OLD VALUE***------------
					$max_measurementbookid = get_max_measurementbookid($measurementbookid_str);
					$dpm_paid_percent = get_dpm_paid_perc($subdivisionlist[$i],$max_measurementbookid,$abstsheetid);
					$check_percent = $old_percent + $dpm_paid_percent;
					if($check_percent<100)
					{
					$dpm_todate_amt = number_format(($partpay_dpm_qty*$rateandremarks[0]*$old_percent/100), 2, '.', '');
					$dpm_todate_amt_total = $dpm_todate_amt_total + $dpm_todate_amt;
					$dpm_paid_amt = number_format(($partpay_dpm_qty*$rateandremarks[0]*$old_percent/100), 2, '.', '');
					$dpm_paid_amt_total = $dpm_paid_amt_total + $dpm_paid_amt;
					?>
					<tr>
						<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
						<td  align='left' width='19%' class='labelsmall'><?php echo "Prev-Qty Vide P ".$old_abst_bmook_page."/ Abstract MB No ".$old_abst_bmook_no; ?></td>
						<td  align='right' width='8%' class='labelsmall'>
						<?php echo $partpay_dpm_qty; ?>
						<input type="hidden" name="hid_dpm_partpay_qty" id="hid_dpm_partpay_qty<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $partpay_dpm_qty; ?>"  />
						</td>
						<td  align='left' width='7%' class='labelsmall'>
						<input type="text" name="txt_dpm_pay_rate" id="txt_dpm_pay_rate<?php echo $dpm_textbox_cnt; ?>" value="<?php echo ($rateandremarks[0]*$old_percent/100).'/'.$rateandremarks[0]; ?>" style="width:69px;" />
						</td>
						<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
						<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
						<td  align='right' width='9%' class='labelsmall'>
						<input type="text" name="txt_dpm_todate_amt" id="txt_dpm_todate_amt<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $dpm_todate_amt; ?>" class="largetextbox" />
						<!--<input type="hidden" name="hid_dpm_todate_amt" id="hid_dpm_todate_amt<?php //echo $dpm_textbox_cnt; ?>" value="<?php //echo $dpm_todate_amt; ?>" />-->
						</td>
						<td  align='left' width='5%' class='labelsmall'><?php //echo $slm_textboxid_cnt; ?></td>
						<td  align='right' width='7%' class='labelsmall'><?php echo $partpay_dpm_qty;//echo $new_dpm_rbn; ?></td>
						<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
						<td  align='right' width='10%' class='labelsmall'><?php echo $dpm_paid_amt; ?></td>
						<td  align='right' width='6%' class='labelsmall'><?php echo $partpay_dpm_qty; ?></td>
						<!--<td  align='right' width='3%' class='labelsmall'>
						<a title="<?php echo 'Remaining - '.(100-$old_percent).'%'; ?>" class="tooltip">
						<input type="text" name="txt_dpm_with_partpay" id="txt_dpm_with_partpay<?php echo $dpm_textbox_cnt; ?>" value="<?php echo 0;//100-$old_percent; ?>" class="percenttextbox" onblur="calculate_partpay_dpm(<?php echo $dpm_textbox_cnt; ?>,<?php echo $slm_textboxid_cnt; ?>,this,<?php echo $page; ?>,<?php echo $rateandremarks[0]; ?>,<?php echo $old_percent; ?>);" />
						</a>
						<!--<input type="hidden" name="hid_dpm_with_partpay_paid" id="hid_dpm_with_partpay_paid<?php //echo $dpm_textbox_cnt; ?>" value="<?php //echo $old_percent; ?>" />-->
						<!--<input type="hidden" name="hid_dpm_measurementbookid" id="hid_dpm_measurementbookid<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $max_measurementbookid; ?>"  />
						<input type="hidden" name="hid_dpm_withpartpay_result[]" id="hid_dpm_withpartpay_result<?php echo $dpm_textbox_cnt; ?>"/>
						</td>-->
						<td  align='right' width='9%' class='labelsmall'>
						<input type="text" name="txt_partpay_amount_dpm" id="txt_partpay_amount_dpm<?php echo $dpm_textbox_cnt; ?>" class="largetextbox labelsmall" value="<?php echo '0.00';//number_format(($partpay_dpm_qty*$rateandremarks[0]*(100-$old_percent)/100), 2, '.', ''); ?>" />
						<input type="hidden" name="hid_partpay_amount_dpm" id="hid_partpay_amount_dpm<?php echo $dpm_textbox_cnt; ?>" value="<?php echo 0;//number_format(($partpay_dpm_qty*$rateandremarks[0]*(100-$old_percent)/100), 2, '.', ''); ?>" />
						</td>
						<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
					</tr>
					<?php
					}
					else
					{
						$dpmqty_with_100_perc_str .= $partpay_dpm_qty."*".$old_abst_bmook_page."*".$old_abst_bmook_no."*";
						$total_quantity = $total_quantity + $partpay_dpm_qty;
						//echo $x2." 2= ".$dpmqty_with_100_perc_str."<br/>";
					}
					$measurementbookid_str = "";
					$partpay_dpm_qty = 0;
					$partpay_dpm_qty = $partpay_dpm_qty + $new_partpay_dpm_qty;	
					$measurementbookid_str .= $new_measurementbookid."*";
					if($x2 == (count($payment_dpm_row)-6))
					{
						$dpm_textbox_cnt++;
						$max_measurementbookid = get_max_measurementbookid($measurementbookid_str);
						$dpm_paid_percent = get_dpm_paid_perc($subdivisionlist[$i],$max_measurementbookid,$abstsheetid);
						$check_percent = $new_percent + $dpm_paid_percent;
						if($check_percent<100)
						{
						$dpm_todate_amt = number_format(($partpay_dpm_qty*$rateandremarks[0]*$new_percent/100), 2, '.', '');
						$dpm_todate_amt_total = $dpm_todate_amt_total + $dpm_todate_amt;
						$dpm_paid_amt = number_format(($partpay_dpm_qty*$rateandremarks[0]*$new_percent/100), 2, '.', '');
						$dpm_paid_amt_total = $dpm_paid_amt_total + $dpm_paid_amt;
						?>
						<tr>
							<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
							<td  align='left' width='19%' class='labelsmall'><?php echo "Prev-Qty Vide P ".$new_abst_bmook_page."/ Abstract MB No ".$new_abst_bmook_no; ?></td>
							<td  align='right' width='8%' class='labelsmall'>
							<?php echo $partpay_dpm_qty; ?>
							<input type="hidden" name="hid_dpm_partpay_qty" id="hid_dpm_partpay_qty<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $partpay_dpm_qty; ?>"  />
							</td>
							<td  align='left' width='7%' class='labelsmall'>
							<input type="text" name="txt_dpm_pay_rate" id="txt_dpm_pay_rate<?php echo $dpm_textbox_cnt; ?>" value="<?php echo ($rateandremarks[0]*$new_percent/100).'/'.$rateandremarks[0]; ?>" style="width:69px;" />
							</td>
							<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
							<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
							<td  align='right' width='9%' class='labelsmall'>
							<input type="text" name="txt_dpm_todate_amt" id="txt_dpm_todate_amt<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $dpm_todate_amt; ?>" class="largetextbox" />
							<!--<input type="hidden" name="hid_dpm_todate_amt" id="hid_dpm_todate_amt<?php //echo $dpm_textbox_cnt; ?>" value="<?php //echo $dpm_todate_amt; ?>" />-->
							</td>
							<td  align='left' width='5%' class='labelsmall'><?php //echo $slm_textboxid_cnt; ?></td>
							<td  align='right' width='7%' class='labelsmall'><?php echo $partpay_dpm_qty;//echo $new_dpm_rbn; ?></td>
							<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
							<td  align='right' width='10%' class='labelsmall'><?php echo $dpm_paid_amt; ?></td>
							<td  align='right' width='6%' class='labelsmall'><?php echo $partpay_dpm_qty; ?></td>
							<!--<td  align='right' width='3%' class='labelsmall'>
							<a title="<?php echo 'Remaining - '.(100-$new_percent).'%'; ?>" class="tooltip">
							<input type="text" name="txt_dpm_with_partpay" id="txt_dpm_with_partpay<?php echo $dpm_textbox_cnt; ?>" value="<?php echo 0;//100-$new_percent; ?>" class="percenttextbox" onblur="calculate_partpay_dpm(<?php echo $dpm_textbox_cnt; ?>,<?php echo $slm_textboxid_cnt; ?>,this,<?php echo $page; ?>,<?php echo $rateandremarks[0]; ?>,<?php echo $new_percent; ?>);"/>
							</a>
							<!--<input type="hidden" name="hid_dpm_with_partpay_paid" id="hid_dpm_with_partpay_paid<?php //echo $dpm_textbox_cnt; ?>" value="<?php //echo $new_percent; ?>" />-->
							<!--<input type="hidden" name="hid_dpm_measurementbookid" id="hid_dpm_measurementbookid<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $max_measurementbookid; ?>"  />
							<input type="hidden" name="hid_dpm_withpartpay_result[]" id="hid_dpm_withpartpay_result<?php echo $dpm_textbox_cnt; ?>"/>
							</td>-->
							<td  align='right' width='9%' class='labelsmall'>
							<input type="text" name="txt_partpay_amount_dpm" id="txt_partpay_amount_dpm<?php echo $dpm_textbox_cnt; ?>" class="largetextbox labelsmall" value="<?php echo '0.00';//number_format(($partpay_dpm_qty*$rateandremarks[0]*(100-$new_percent)/100), 2, '.', ''); ?>" />
							<input type="hidden" name="hid_partpay_amount_dpm" id="hid_partpay_amount_dpm<?php echo $dpm_textbox_cnt; ?>" value="<?php echo 0;//number_format(($partpay_dpm_qty*$rateandremarks[0]*(100-$new_percent)/100), 2, '.', ''); ?>" />
							</td>
							<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
						</tr>
						<?php
						}
						else
						{
							$dpmqty_with_100_perc_str .= $partpay_dpm_qty."*".$new_abst_bmook_page."*".$new_abst_bmook_no."*";
							$total_quantity = $total_quantity + $partpay_dpm_qty;
							//echo $x2." 3= ".$dpmqty_with_100_perc_str."<br/>";
						}
						$measurementbookid_str = "";
					}
					
				}
			}
			if($old_percent == "")
			{
				$partpay_dpm_qty = $partpay_dpm_qty + $new_partpay_dpm_qty;
				$measurementbookid_str .= $new_measurementbookid."*";
				if($x2 == (count($payment_dpm_row)-6))
					{
						$dpm_textbox_cnt++;
						$max_measurementbookid = get_max_measurementbookid($measurementbookid_str);
						$dpm_paid_percent = get_dpm_paid_perc($subdivisionlist[$i],$max_measurementbookid,$abstsheetid);
						$check_percent = $new_percent + $dpm_paid_percent;
						if($check_percent<100)
						{
						$dpm_todate_amt = number_format(($partpay_dpm_qty*$rateandremarks[0]*$new_percent/100), 2, '.', '');
						$dpm_todate_amt_total = $dpm_todate_amt_total + $dpm_todate_amt;
						$dpm_paid_amt = number_format(($partpay_dpm_qty*$rateandremarks[0]*$new_percent/100), 2, '.', '');
						$dpm_paid_amt_total = $dpm_paid_amt_total + $dpm_paid_amt;
						?>
						<tr>
							<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
							<td  align='left' width='19%' class='labelsmall'><?php echo "Prev-Qty Vide P ".$new_abst_bmook_page."/ Abstract MB No ".$new_abst_bmook_no; ?></td>
							<td  align='right' width='8%' class='labelsmall'>
							<?php echo $partpay_dpm_qty; ?>
							<input type="hidden" name="hid_dpm_partpay_qty" id="hid_dpm_partpay_qty<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $partpay_dpm_qty; ?>"  />
							</td>
							<td  align='left' width='7%' class='labelsmall'>
							<input type="text" name="txt_dpm_pay_rate" id="txt_dpm_pay_rate<?php echo $dpm_textbox_cnt; ?>" value="<?php echo ($rateandremarks[0]*$new_percent/100).'/'.$rateandremarks[0]; ?>" style="width:69px;"/>
							</td>
							<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
							<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
							<td  align='right' width='9%' class='labelsmall'>
							<input type="text" name="txt_dpm_todate_amt" id="txt_dpm_todate_amt<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $dpm_todate_amt; ?>" class="largetextbox" />
							<!--<input type="hidden" name="hid_dpm_todate_amt" id="hid_dpm_todate_amt<?php //echo $dpm_textbox_cnt; ?>" value="<?php //echo $dpm_todate_amt; ?>" />-->
							</td>
							<td  align='left' width='5%' class='labelsmall'><?php //echo $slm_textboxid_cnt; ?></td>
							<td  align='right' width='7%' class='labelsmall'><?php echo $partpay_dpm_qty;//echo $new_dpm_rbn; ?></td>
							<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
							<td  align='right' width='10%' class='labelsmall'><?php echo $dpm_paid_amt; ?></td>
							<td  align='right' width='6%' class='labelsmall'><?php echo $partpay_dpm_qty; ?></td>
							<!--<td  align='right' width='3%' class='labelsmall'>
							<a title="<?php echo 'Remaining - '.(100-$new_percent).'%'; ?>" class="tooltip">
							<input type="text" name="txt_dpm_with_partpay" id="txt_dpm_with_partpay<?php echo $dpm_textbox_cnt; ?>" value="<?php echo 0;//100-$new_percent; ?>" class="percenttextbox" onblur="calculate_partpay_dpm(<?php echo $dpm_textbox_cnt; ?>,<?php echo $slm_textboxid_cnt; ?>,this,<?php echo $page; ?>,<?php echo $rateandremarks[0]; ?>,<?php echo $new_percent; ?>);"/>
							</a>
							<!--<input type="hidden" name="hid_dpm_with_partpay_paid" id="hid_dpm_with_partpay_paid<?php //echo $dpm_textbox_cnt; ?>" value="<?php //echo $new_percent; ?>" />-->
							<!--<input type="hidden" name="hid_dpm_measurementbookid" id="hid_dpm_measurementbookid<?php echo $dpm_textbox_cnt; ?>" value="<?php echo $max_measurementbookid; ?>"  />
							<input type="hidden" name="hid_dpm_withpartpay_result[]" id="hid_dpm_withpartpay_result<?php echo $dpm_textbox_cnt; ?>" />
							</td>-->
							<td  align='right' width='9%' class='labelsmall'>
							<input type="text" name="txt_partpay_amount_dpm" id="txt_partpay_amount_dpm<?php echo $dpm_textbox_cnt; ?>" class="largetextbox labelsmall" value="<?php echo '0.00';//number_format(($partpay_dpm_qty*$rateandremarks[0]*(100-$new_percent)/100), 2, '.', ''); ?>" />
							<input type="hidden" name="hid_partpay_amount_dpm" id="hid_partpay_amount_dpm<?php echo $dpm_textbox_cnt; ?>" value="<?php echo 0;//number_format(($partpay_dpm_qty*$rateandremarks[0]*(100-$new_percent)/100), 2, '.', ''); ?>" />
							</td>
							<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
						</tr>
						<?php
						}
						else
						{
							$dpmqty_with_100_perc_str .= $partpay_dpm_qty."*".$new_abst_bmook_page."*".$new_abst_bmook_no."*";
							$total_quantity = $total_quantity + $partpay_dpm_qty;
							//echo $x2." 4= ".$dpmqty_with_100_perc_str."<br/>";
						}
						$measurementbookid_str = "";
					}
				$c++;
			}
			$old_percent = $new_percent;
			$old_partpay_dpm_qty = $new_partpay_dpm_qty;
			$old_measurementbookid = $new_measurementbookid;
			$old_measurementbookid_str = $measurementbookid_str;
			$old_abst_bmook_no = $new_abst_bmook_no;
			$old_abst_bmook_page = $new_abst_bmook_page;
			//$measurementbookid_str = "";
		}
		$cnt_2 = 0; $mesurementbook_details_new = "";
	}
	$total_quantity = $prev_measurement_qty + $partpay_dpm_qty;
	if(($cnt_1>0) || ($dpmqty_with_100_perc_str != ""))
	{
		$dpm_qty_with_100_perc = 0;
		if($dpmqty_with_100_perc_str != "")
		{
			$explode_100_perc_str = explode("*",rtrim($dpmqty_with_100_perc_str,"*"));
			for($x3 = 0; $x3<count($explode_100_perc_str); $x3+=3)
			{
				$dpm_qty_with_100_perc = $dpm_qty_with_100_perc + $explode_100_perc_str[$x3];
				if($x3 == (count($explode_100_perc_str)-3)) /// THIS CONDTION IS FOR SET MAX ABSTRACT NO AND PAGE AS DPM ABST PAGE NO AND ABST NO...
				{
					$mbpageno_dp = $explode_100_perc_str[$x3+1];
					$mbno_dp = $explode_100_perc_str[$x3+2];
				}
			}
			$dpmqty_with_100_perc_str = "";
		}
		$prev_measurement_qty = $prev_measurement_qty + $dpm_qty_with_100_perc;
		$dpm_amt_with_100_perc = number_format(($prev_measurement_qty * $rateandremarks[0]), 2, '.', '');
	?>
		<tr>
			<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
			<td  align='left' width='19%' class='labelsmall'><?php echo "Prev-Qty Vide P ".$mbpageno_dp."/Abstract MB No ".$mbno_dp; ?></td>
			<td  align='right' width='8%' class='labelsmall'><?php echo number_format($prev_measurement_qty, $decimal, '.', ''); ?></td>
			<td  align='left' width='7%' class='labelsmall'>
			<input type="text" name="txt_rate_percent_dpm" id="txt_rate_percent_dpm<?php echo $slm_textboxid_cnt; ?>" style="width:72px;text-align:right;border-style:none;" 
			value="<?php echo ($rateandremarks[0]*$paymentpercent/100)." / ".round($rateandremarks[0],2); ?>"/></td>
			<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
			<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
			<td  align='right' width='9%' class='labelsmall'>&nbsp;</td>
			<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
			<td  align='right' width='7%' class='labelsmall'>
			<?php 
			echo number_format($prev_measurement_qty, $decimal, '.', ''); 
			$co_bf_prev_total_qty = $co_bf_prev_total_qty + $prev_measurement_qty;
			?>
			</td>
			<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
			<td  align='right' width='10%' class='labelsmall'>
			<?php echo $dpm_amt_with_100_perc; ?>
			</td>
			<td  align='left' width='6%' class='labelsmall'>&nbsp;</td>
			<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
			<td  align='right' width='9%' class='labelsmall'>&nbsp;</td>
			<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
		</tr>
	<?php 
	$cnt_1 = 0;
	}
} 
?>
<?php if($sincelast_measurement_qty != 0) { ?>
<tr>
	<td  align='left' width='7%' class='labelsmall'><?php //echo(round(7.01,2)); ?></td>
	<td  align='left' width='19%' class='labelsmall'><?php echo "Qty Vide P ".$mbpageno_sl.$mbookdescription.$mbno_sl; ?></td>
	<td  align='right' width='8%' class='labelsmall'><?php echo number_format($sincelast_measurement_qty, $decimal, '.', ''); ?></td>
	<td  align='left' width='7%' class='labelsmall'>
	<input type="text" name="txt_rate_percent_slm" id="txt_rate_percent_slm<?php echo $slm_textboxid_cnt; ?>" style="width:72px;text-align:right;border-style:none;" 
	value="<?php echo ($rateandremarks[0]*$paymentpercent/100)." / ".round($rateandremarks[0],2); ?>"/>
	</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<!--<td  align='left' width='3%' class='labelsmall'>
	<input type="text" name="txt_percent_slm_leftside" id="txt_percent_slm_leftside<?php echo $slm_textboxid_cnt; ?>" value="<?php echo $paymentpercent; ?>" style="width:26px; text-align:center; background:#8AF29A"/>
	</td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" name="txt_amount_slm_todate" id="txt_amount_slm_todate<?php echo $slm_textboxid_cnt; ?>" class="largetextbox labelsmall" value="<?php echo number_format(($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100), 2, '.', ''); ?>" />
	<!--<input type="hidden" name="hid_amount_slm_leftside" id="hid_amount_slm_leftside<?php //echo $slm_textboxid_cnt; ?>" class=" labelsmall" value="<?php //echo number_format(($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100), 2, '.', ''); ?>" />-->
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='10%' class='labelsmall'><?php //echo $slm_textboxid_cnt; ?></td>
	<td  align='right' width='6%' class='labelsmall'>
	<?php 
		echo number_format($sincelast_measurement_qty, $decimal, '.', ''); 
		$co_bf_slast_total_qty = $co_bf_slast_total_qty + $sincelast_measurement_qty; 
		$total_quantity = $total_quantity + $sincelast_measurement_qty;
	?>
	</td>
	<!--<td  align='right' width='3%' class='labelsmall'>
	<a title="You can edit this" class="tooltip">
	<input type="text" class="percenttextbox" name="txt_percent_slm[]" id="txt_percent_slm<?php echo $slm_textboxid_cnt; ?>" value="<?php echo $paymentpercent; ?>" onblur="slm_calculation(this,<?php echo $slm_textboxid_cnt; ?>,<?php echo $rateandremarks[0]; ?>,<?php echo number_format($sincelast_measurement_qty, $decimal, '.', ''); ?>,<?php echo $page; ?>);"/>
	</a>
	</td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" name="txt_amount_slm" id="txt_amount_slm<?php echo $slm_textboxid_cnt; ?>" class="largetextbox labelsmall" value="<?php echo number_format(($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100), 2, '.', ''); ?>" />
	<input type="hidden" name="hid_amount_slm<?php echo $page; ?>" id="hid_amount_slm<?php echo $slm_textboxid_cnt; ?>" value="<?php echo number_format(($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100), 2, '.', ''); ?>" />
	<?php 
		
		$co_bf_slast_total_amt = $co_bf_slast_total_amt + number_format(($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100), 2, '.', '');
		//echo $co_bf_slast_total_amt."<br/>";
		//echo ($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100)."<br/>";
		?>
	</td>
	<td  align='center' width='5%' class='labelsmall'>
	<?php
	if($paymentpercent<100) { echo $paymentpercent." %<br/>Paid"; } 
	 ?>
	</td>
</tr>
<?php } ?>
<!--<input type="hidden" name="hid_actual_rate" id="hid_actual_rate<?php //echo $slm_textboxid_cnt; ?>" value="<?php // echo $rateandremarks[0]; ?>"  />-->
<!--<input type="hidden" name="hid_rate_with_rebate" id="hid_rate_with_rebate<?php //echo $slm_textboxid_cnt; ?>" value="<?php //echo $rateandremarks[0]; ?>"  />-->
<input type="hidden" name="hid_subdivid" id="hid_subdivid<?php echo $slm_textboxid_cnt; ?>" value="<?php echo $subdivisionlist[$i]; ?>"  />
<input type="hidden" name="hid_perc_subdivid[]" id="hid_perc_subdivid<?php echo $slm_textboxid_cnt; ?>" value="<?php echo $paymentpercent.'*'.$subdivisionlist[$i]; ?>"  />
<?php 
$total_amt_todate = 0;
$dpm_amt_total_now = number_format(($prev_measurement_qty*$rateandremarks[0]), 2, '.', '');
$dpm_amt_total_overall = number_format(($dpm_amt_total_now + $dpm_paid_amt_total), 2, '.', '');
$slm_dpm_todate_amt_total = number_format(($dpm_amt_total_now+($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100)), 2, '.', '');
$total_amt_todate = number_format(($dpm_todate_amt_total + $slm_dpm_todate_amt_total), 2, '.', '');
$dpm_todate_amt_total = 0;
$dpm_paid_amt_total = 0;
?>
<tr>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='19%' class='labelsmall'>Total</td>
	<td  align='right' width='8%' class='labelsmall'>
		<?php 
		echo number_format($total_quantity, $decimal, '.', ''); 
		$co_bf_tot_qty = $co_bf_tot_qty + $total_quantity; 
		?>
	</td>
	<td  align='right' width='7%' class='labelsmall'><?php echo $rateandremarks[0]; ?></td>
	<td  align='center' width='4%' class='labelsmall'><?php echo $rateandremarks[1]; ?></td>
	<!--<td  align='left' width='3%' class='labelsmall'>
	<input type="text" name="txt_percent_slm_leftside" id="txt_percent_slm_leftside<?php echo $slm_textboxid_cnt; ?>" value="<?php echo $paymentpercent; ?>" style="width:26px; text-align:center; background:#F58D9F"/>
	</td>-->
	<td  align='right' width='9%' class='labelsmall'>
		<input type="text" class="largetextbox labelsmall" name="txt_totalamt_todate" id="txt_totalamt_todate<?php echo $slm_textboxid_cnt; ?>" value="<?php echo $total_amt_todate;  //number_format((($prev_measurement_qty*$rateandremarks[0])+($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100)), 2, '.', '');?>" />
		<?php 
		//echo number_format(($total_quantity*$rateandremarks[0]), 2, '.', ''); 
		$co_bf_tot_amt = $co_bf_tot_amt + $total_amt_todate;//(($prev_measurement_qty*$rateandremarks[0])+($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100)); 
		?>
		<!--<input type="hidden" name="hid_totalamt_todate" id="hid_totalamt_todate<?php //echo $slm_textboxid_cnt; ?>" value="<?php //echo $total_amt_todate;//number_format((($prev_measurement_qty*$rateandremarks[0])+($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100)), 2, '.', '');?>" />-->
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='7%' class='labelsmall'>
		<?php 
		//echo number_format($prev_measurement_qty, 3, '.', ''); 
		//$co_bf_prev_total_qty = $co_bf_prev_total_qty + $prev_measurement_qty;
		?>
	</td>
	<!--<td  align='center' width='3%' class='labelsmall'>
			<input type="text" name="txt_percent_dpm" id="txt_percent_dpm" value="<?php echo "100"; ?>" style="width:26px; text-align:center; background:#58DEE9"/>
	</td>-->
	<td  align='right' width='10%' class='labelsmall'>
		<?php 
		echo $dpm_amt_total_overall; 
		$co_bf_prev_total_amt = $co_bf_prev_total_amt + $dpm_amt_total_overall;
		$dpm_amt_total_overall = 0;
		//echo "<br/>".$co_bf_prev_total_amt."<br/>";
		?>
	</td>
	<td  align='right' width='6%' class='labelsmall'>
		<?php 
		//echo number_format($sincelast_measurement_qty, 3, '.', ''); 
		//$co_bf_slast_total_qty = $co_bf_slast_total_qty + $sincelast_measurement_qty; 
		?>
	</td>
	<!--<td  align='center' width='3%' class='labelsmall'>
		
	</td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" name="txt_slm_total_amt" class="largetextbox" id="txt_slm_total_amt<?php echo $slm_textboxid_cnt; ?>" value="<?php echo number_format(($sincelast_measurement_qty*$rateandremarks[0]*$paymentpercent/100), 2, '.', ''); ?>"  />
	
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>
<?php
$str .= $subdivisionlist[$i].",".$page.",";
if($page != $prevpage ) { $pagestr .= $page."@"; }
$total_quantity = 0;$mesurementgenerate_details = ""; $currentline+=4; $prevpage = $page; $slm_textboxid_cnt++;

}
?>
<input type="hidden" name="hid_page_str" id="hid_page_str" value="<?php echo rtrim($pagestr,'@'); ?>" />
<!--<tr bgcolor="#CCFFFF">
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='20%' class='labelsmall'><?php //echo "B/f from Page ".$prevpage;?></td>
	<td  align='right' width='8%' class='labelsmall'><?php //echo $co_bf_tot_qty; ?></td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='10%' class='labelsmall'><?php //echo number_format($co_bf_tot_amt, 2, '.', ''); ?></td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='7%' class='labelsmall'><?php //echo $co_bf_prev_total_qty; ?></td>
	<td  align='right' width='10%' class='labelsmall'><?php //echo number_format($co_bf_prev_total_amt, 2, '.', ''); ?></td>
	<td  align='right' width='7%' class='labelsmall'><?php //echo $co_bf_slast_total_qty; ?></td>
	<td  align='right' width='10%' class='labelsmall'><?php //echo number_format($co_bf_slast_total_amt, 2, '.', ''); ?></td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>-->
<?php 

if($currentline>32)
	{
?>
<tr>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='19%' class='labelsmall'><?php echo "C/o to Page ".($page+1);?></td>
	<td  align='right' width='8%' class='labelsmall'><?php //echo $co_bf_tot_qty; ?></td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='9%' class='labelsmall'><?php echo number_format($co_bf_tot_amt, 2, '.', ''); ?></td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='7%' class='labelsmall'><?php //echo $co_bf_prev_total_qty; ?></td>
	<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='10%' class='labelsmall'><?php echo number_format($co_bf_prev_total_amt, 2, '.', ''); ?></td>
	<td  align='right' width='6%' class='labelsmall'><?php //echo $co_bf_slast_total_qty; ?></td>
	<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='9%' class='labelsmall'><?php echo number_format($co_bf_slast_total_amt, 2, '.', ''); ?></td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>
<?php 
		echo "<tr style='border:none'><td colspan='12' style='border:none' align='center'><br/>Page ".$page."</td></tr>";
		echo "</table>"; //echo "SSSS".$currentline;
		/*for($x=$currentline;$x<43;$x++)
		{
			echo "&nbsp"."<br/>";
		}*/
		echo "<p  style='page-break-after:always;'></p>";
		$currentline = $start_line + 8;
		$page++;
		echo $title;
		echo $table; 
		echo "<table width='1087px' bgcolor='white'   border='0' cellpadding='0' cellspacing='0' align='center' class='label'>";
?>
<tr>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='19%' class='labelsmall'><?php echo "B/f from Page ".$prevpage;?></td>
	<td  align='right' width='8%' class='labelsmall'><?php //echo $co_bf_tot_qty; ?></td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='9%' class='labelsmall'><?php echo number_format($co_bf_tot_amt, 2, '.', ''); ?></td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='7%' class='labelsmall'><?php //echo $co_bf_prev_total_qty; ?></td>
	<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='10%' class='labelsmall'><?php echo number_format($co_bf_prev_total_amt, 2, '.', ''); ?></td>
	<td  align='right' width='6%' class='labelsmall'><?php //echo $co_bf_slast_total_qty; ?></td>
	<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='9%' class='labelsmall'><?php echo number_format($co_bf_slast_total_amt, 2, '.', ''); ?></td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>
<?php 
	}
?>

<tr bgcolor="">
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='19%' class='labelsmall'><?php echo "Total Cost"; ?></td>
	<td  align='right' width='8%' class='labelsmall'><?php //echo $co_bf_tot_qty; ?></td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" name="txt_totalcost_todate" class="largetextbox labelsmall" id="txt_totalcost_todate" value="<?php echo number_format($co_bf_tot_amt, 2, '.', ''); ?>" />
	<!--<input type="hidden" name="hid_totalcost_todate" id="hid_totalcost_todate" value="<?php //echo number_format($co_bf_tot_amt, 2, '.', ''); ?>" />-->
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='7%' class='labelsmall'><?php //echo $co_bf_prev_total_qty; ?></td>
	<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='10%' class='labelsmall'>
	<?php echo number_format($co_bf_prev_total_amt, 2, '.', ''); ?>
	</td>
	<td  align='right' width='6%' class='labelsmall'><?php //echo $co_bf_slast_total_qty; ?></td>
	<!--<td  align='right' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<!--<input type="hidden" name="hid_total_cost" id="hid_total_cost" value="<?php echo number_format($co_bf_slast_total_amt, 2, '.', ''); ?>" />-->
	<input type="text" name="txt_total_cost" id="txt_total_cost" class="largetextbox labelsmall" value="<?php echo number_format($co_bf_slast_total_amt, 2, '.', ''); ?>" />
	
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>
<?php
$todate_rebate_amt 	= number_format($co_bf_tot_amt, 2, '.', '') * $overall_rebate_perc / 100;
$dpm_rebate_amt 	= number_format($co_bf_prev_total_amt, 2, '.', '') * $overall_rebate_perc / 100;
$slm_rebate_amt		= number_format($co_bf_slast_total_amt, 2, '.', '') * $overall_rebate_perc / 100;

$overall_todate_amt_with_rebate = number_format($co_bf_tot_amt, 2, '.', '') - number_format($todate_rebate_amt, 2, '.', '');
$overall_dpm_amt_with_rebate 	= number_format($co_bf_prev_total_amt, 2, '.', '') - number_format($dpm_rebate_amt, 2, '.', '');
$overall_slm_amt_with_rebate 	= number_format($co_bf_slast_total_amt, 2, '.', '') - number_format($slm_rebate_amt, 2, '.', '');
?>
<input type="hidden" name="hid_overall_rebate_perc" id="hid_overall_rebate_perc" value="<?php echo $overall_rebate_perc; ?>" />
<tr bgcolor="">
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='19%' class='labelsmall'><?php echo "Less Overall Rebate ".$overall_rebate_perc." %"; ?></td>
	<td  align='right' width='8%' class='labelsmall'></td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" name="txt_todate_rebate_perc" id="txt_todate_rebate_perc" class="largetextbox labelsmall" value="<?php echo number_format($todate_rebate_amt, 2, '.', ''); ?>" />
	<?php //echo number_format($todate_rebate_amt, 2, '.', ''); ?>
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='7%' class='labelsmall'></td>
	<td  align='right' width='10%' class='labelsmall'>
	<input type="text" name="txt_dpm_rebate_perc" id="txt_dpm_rebate_perc" class="largetextbox labelsmall" value="<?php echo number_format($dpm_rebate_amt, 2, '.', ''); ?>" />
	<?php //echo number_format($dpm_rebate_amt, 2, '.', ''); ?>
	</td>
	<td  align='right' width='6%' class='labelsmall'></td>
	<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" name="txt_slm_rebate_perc" id="txt_slm_rebate_perc" class="largetextbox labelsmall" value="<?php echo number_format($slm_rebate_amt, 2, '.', ''); ?>" />
	<?php //echo number_format($slm_rebate_amt, 2, '.', ''); ?>
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>
<tr bgcolor="">
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='19%' class='labelsmall'><?php echo "Gross Amount (Rs.) "; ?></td>
	<td  align='right' width='8%' class='labelsmall'></td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" name="txt_todate_gross_amt" id="txt_todate_gross_amt" class="largetextbox labelsmall" value="<?php echo number_format($overall_todate_amt_with_rebate, 2, '.', ''); ?>" />
	<?php //echo number_format($overall_todate_amt_with_rebate, 2, '.', ''); ?>
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='7%' class='labelsmall'></td>
	<td  align='right' width='10%' class='labelsmall'>
	<input type="text" name="txt_dpm_gross_amt" id="txt_dpm_gross_amt" class="largetextbox labelsmall" value="<?php echo number_format($overall_dpm_amt_with_rebate, 2, '.', ''); ?>" />
	<?php //echo number_format($overall_dpm_amt_with_rebate, 2, '.', ''); ?>
	</td>
	<td  align='right' width='6%' class='labelsmall'></td>
	<!--<td  align='left' width='3%' class='labelsmall'>&nbsp;</td>-->
	<td  align='right' width='9%' class='labelsmall'>
	<input type="text" name="txt_slm_gross_amt" id="txt_slm_gross_amt" class="largetextbox labelsmall" value="<?php echo number_format($overall_slm_amt_with_rebate, 2, '.', ''); ?>" />
	<?php //echo number_format($overall_slm_amt_with_rebate, 2, '.', ''); ?>
	</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>
<tr style='border:none'>
	<td colspan='6' style='border:none' align='right'><br/><br/><br/>Page <?php echo $page; ?></td>
	<td colspan='6' style='border:none' align='center'><!--<br/><br/><br/>Prepared By--></td>
</tr>
</table>
<?php 

echo "<p  style='page-break-after:always;'></p>";
for($x=0;$x<$emptypage;$x++)
{
$page++;
echo $title;
echo $table;
echo "<table width='1087px' bgcolor='white'   border='0' cellpadding='0' cellspacing='0' align='center' class='label'>";
$y=1;
while($y<20)
{
	echo "<tr style='border:none'><td colspan='12' style='border:none; height:25px; color:lightgrey' align='center'>
	.........................................................................................................................................................................................................................
	</td></tr>";
	$y++;		
}
echo "<tr style='border:none'><td colspan='12' style='border:none' align='center'>Page ".$page."</td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";
//$page++;
}
?>
<?php 
$abstractpagelist = explode(",",rtrim($str,","));
for($k=0; $k<count($abstractpagelist); $k+=2)
{
$update_abstractpage_query = "update mbookgenerate set abstmbpage = '".$abstractpagelist[$k+1]."' WHERE subdivid = '".$abstractpagelist[$k]."' AND sheetid = '$abstsheetid'";
$update_abstractpage_sql = mysql_query($update_abstractpage_query);
//echo $update_abstractpage_query."<br/>";
}
 ?>
<input type="hidden" name="txt_abstmbno" id="txt_abstmbno" value="<?php echo $abstmbno; ?>" />
<input type="hidden" name="txt_maxpage" id="txt_maxpage" value="<?php echo ($page+$emptypage); ?>" /> <!--- This is for page no update - inclue empty page($page+4) -->
<!--<table border="0" width="18%" align="center" style="border-style:none" class="printbutton">
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none">&nbsp;
		</td>
	</tr>
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none">
			<input type="Submit" name="Submit" value="Submit" style="border:none" /> &nbsp;&nbsp;&nbsp;
			<input type="submit" name="Back" value="Back" style="border:none" />  &nbsp;&nbsp;&nbsp;
			<input type="button" class="addbtnstyle" name="print" onclick="printPage();" value="   Print   " style="border:none; font-size:12px;" />
		</td>
	</tr>
</table> -->
<div align="center" class="btn_outside_sect">
	<div class="btn_inside_sect"><input type="Submit" name="Submit" value="Submit" /> </div>
	<div class="btn_inside_sect"><input type="button" name="back" value="Back" id="back" class="backbutton" onclick="goBack();" /> </div>
	<div class="btn_inside_sect"><input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" /></div>
</div>              
        </form>
    </body>
</html>