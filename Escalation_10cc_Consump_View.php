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
$sheetid  	= $_SESSION['escal_sheetid'];
$from_date  = $_SESSION['escal_from_date'];
$to_date  	= $_SESSION['escal_to_date'];
if(($from_date != "")&&($to_date != ""))
{
	$fromdate 	= dt_format($from_date);
	$todate 	= dt_format($to_date);
}
//$fromdate = "2016-06-01";
//$todate = "2016-10-01";

$union_query = "(SELECT measurementbook.measurementbookdate, measurementbook.divid, measurementbook.subdivid, measurementbook.fromdate, 
				measurementbook.todate, measurementbook.mbno, measurementbook.mbpage, measurementbook.mbtotal, measurementbook.abstmbookno, 
				measurementbook.abstmbpage, measurementbook.rbn, measurementbook.flag, schdule.description, schdule.shortnotes, schdule.rate, 
				schdule.per, schdule.sno
				FROM measurementbook 
				INNER JOIN schdule ON (measurementbook.subdivid = schdule.subdiv_id)
				WHERE measurementbook.sheetid = '$sheetid' and schdule.sheet_id = '$sheetid' and 
				(DATE(measurementbook.fromdate) BETWEEN '$fromdate' AND '$todate') and 
				(DATE(measurementbook.todate) BETWEEN '$fromdate' AND '$todate'))
			UNION
				(SELECT measurementbook_temp.measurementbookdate, measurementbook_temp.divid, measurementbook_temp.subdivid, measurementbook_temp.fromdate, 
				measurementbook_temp.todate, measurementbook_temp.mbno, measurementbook_temp.mbpage, measurementbook_temp.mbtotal, measurementbook_temp.abstmbookno, 
				measurementbook_temp.abstmbpage, measurementbook_temp.rbn, measurementbook_temp.flag, schdule.description, schdule.shortnotes, schdule.rate, 
				schdule.per, schdule.sno
				FROM measurementbook_temp 
				INNER JOIN schdule ON (measurementbook_temp.subdivid = schdule.subdiv_id)
				WHERE measurementbook_temp.sheetid = '$sheetid' and schdule.sheet_id = '$sheetid' and 
				(DATE(measurementbook_temp.fromdate) BETWEEN '$fromdate' AND '$todate') and 
				(DATE(measurementbook_temp.todate) BETWEEN '$fromdate' AND '$todate'))  ORDER BY rbn ASC"; 
$union_sql = mysql_query($union_query);
//echo $union_query;			
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
							ORDER BY mbookheader.date ASC, mbookheader.subdivid ASC, mbookheader.zone_id ASC";
$escal_measure_sql = mysql_query($escal_measure_query);*/

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
if(isset($_POST['submit']) == " Save ")
{
	$date_wise_data = $_POST['txt_date_wise_data'];
	if($date_wise_data != "")
	{
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
											sheetid = '$sheetid',
											item_code = 'CIo',
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
											staffid = '$staffid',
											modifieddate = NOW(),
											active = '1'";
			$insert_cement_consum_sql = mysql_query($insert_cement_consum_query);
			//echo $insert_cement_consum_query;
		}
		
	}
	//print_r($date_wise_data);
	//echo "<br/>";
	//echo count($date_wise_data);
	//exit;
}
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
input[type="date"]:before {
    content: attr(placeholder) !important;
    color: #aaa;
    margin-right: 0.5em;
  }
  input[type="date"]:focus:before,
  input[type="date"]:valid:before {
    content: "";
  }
.extraItemTextbox {
    height: 30px;
    position: relative;
    outline: none;
    border: 1px solid #98D8FE;
    background-color: white;
	color:#0000cc;
	width:98%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	text-align:center;
}
.extraItemTextArea
{
    position: relative;
    outline: none;
	border: 1px solid #98D8FE;
    background-color: white;
	color:#0000cc;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
}
.extraItemTextboxDisable {
    height: 30px;
    position: relative;
    outline: none;
    border:none;
    background-color: #EAEAEA;
	color:#0000cc;
	width:98%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	text-align:center;
	vertical-align:middle;
	cursor:default;
}
.gradientbg {
 	background-color: #014D62;
  	width:90%; height:25px; color:#FFFFFF; vertical-align:middle;
  	background: url(images/linear_bg_2.png);
  	background-repeat: repeat-x;
  	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#037595), to(#0A9CC5));
  	background: -webkit-linear-gradient(top, #0A9CC5, #037595);
  	background: -moz-linear-gradient(top, #0A9CC5, #037595);
  	background: -ms-linear-gradient(top, #0A9CC5, #037595);
  	background: -o-linear-gradient(top, #0A9CC5, #037595);
}
.buttonstyle
{
	background-color:#0A9CC5;
	height:25px;
	color:#FFFFFF;
	-moz-box-shadow: 0px 1px 0px 0px #0A9CC5;
	-webkit-box-shadow: 0px 1px 0px 0px #0A9CC5;
	box-shadow: 0px 1px 0px 0px #0A9CC5;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0080FF), color-stop(1, #0A9CC5));
	background:-moz-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-webkit-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-o-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-ms-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:linear-gradient(to bottom, #0080FF 5%, #0A9CC5 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0080FF', endColorstr='#0A9CC5',GradientType=0);
	border:1px solid #0080FF;
	display:inline-block;
	cursor:pointer;
	font-weight:bold;
}
.buttonstyle:hover
{
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E80017;
	border:1px solid #E80017;
}
.buttonstyledisable
{
	background-color:#CECECE;
	color:#A0A0A0;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #E6E6E6), color-stop(1, #CECECE));
	background:-moz-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-webkit-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-o-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-ms-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:linear-gradient(to bottom, #E6E6E6 5%, #CECECE 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#E6E6E6', endColorstr='#CECECE',GradientType=0);
	border:1px solid #CECECE;
}
.buttonstyledisable:hover
{
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E6E6E6;
	border:1px solid #E6E6E6;
}
.hide
{
	display:none;
}
sub {font-size:xx-small; vertical-align:sub;}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="AgreementEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1" style="overflow:scroll">
                            <div class="title">Escalation - 10CC RAB Total Amount View</div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
							</br>
							<div style="width:100%;" align="center" id="myDiv">
								<table width="100%" class="table1" id="table1">
									<tr class="label" style="height:35px;">
										<td align="center" valign="middle">Sl.No.</td>
										<td align="center" valign="middle">MBook No</td>
										<td align="center" valign="middle">Page No.</td>
										<td align="center" valign="middle">RAB.</td>
										<td align="center" valign="middle">Item No</td>
										<td align="center" valign="middle">Quantity</td>
										<td align="center" valign="middle">Unit</td>
										<td align="center" valign="middle">Rate</td>
										<td align="center" valign="middle">Amount</td>
									</tr>
						<?php
						if($union_sql == true)
						{
							if(mysql_num_rows($union_sql)>0)
							{
								$prev_rbn = ""; $slno = 1; $rbn_amount = 0; $prev_month_year = "";
								while($MList = mysql_fetch_object($union_sql))
								{
									$divid 			= $MList->divid;
									$subdivid 		= $MList->subdivid;
									$fromdate 		= $MList->fromdate;
									$todate 		= $MList->todate;
									$mbno 	 		= $MList->mbno;
									$mbpage 		= $MList->mbpage;
									$mbtotal 		= $MList->mbtotal;
									$abstmbookno 	= $MList->abstmbookno;
									$abstmbpage  	= $MList->abstmbpage;
									$rbn  		 	= $MList->rbn;
									$flag  		 	= $MList->flag;
									$rate  		 	= $MList->rate;
									$description  	= $MList->description;
									$shortnotes  	= $MList->shortnotes;
									$per  			= $MList->per;
									$itemno			= $MList->sno;
									$temp = 0;
									$date_temp		= strtotime($fromdate);
									$month_year		= date("F-Y",$date_temp);
									$amount = round($mbtotal*$rate,2);
									
									if($shortnotes != "")
									{
										$description = $shortnotes;
									}
									if(($prev_rbn != "") && ($prev_rbn != $rbn))
									{
										$temp = 1;
									}
									if($temp == 1)
									{
										echo '<tr class="label">';
											echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '<td align="right" valign="middle">&nbsp;&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '<td align="right" valign="middle">&nbsp;Total&nbsp;</td>';
											echo '<td align="right" valign="middle">&nbsp;'.$rbn_amount.'&nbsp;</td>';
										echo '</tr>';
										$rbn_amount = 0;
										$temp = 0;
									}
									if($month_year != $prev_month_year)
									{
										echo '<tr class="label">';
											echo '<td align="left" colspan="8" valign="middle">&nbsp;'.$month_year.'</td>';
											echo '<td align="right" valign="middle">&nbsp;&nbsp;</td>';
										echo '</tr>';
									}
									echo '<tr class="labeldisplay">';
										echo '<td align="center" valign="middle">'.$slno.'</td>';
										echo '<td align="center" valign="middle">'.$abstmbookno.'</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$abstmbpage.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$rbn.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$itemno.'&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;'.$mbtotal.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$per.'&nbsp;</td>';
										echo '<td align="right" valign="middle">'.$rate.'&nbsp;</td>';
										echo '<td align="right" valign="middle">'.$amount.'&nbsp;</td>';
									echo '</tr>';
									$slno++;
									$rbn_amount = $rbn_amount+$amount;
									$prev_rbn = $rbn;
									$prev_month_year = $month_year;
								}
								echo '<tr class="label">';
									echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
									echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
									echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
									echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
									echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
									echo '<td align="right" valign="middle">&nbsp;&nbsp;</td>';
									echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
									echo '<td align="right" valign="middle">&nbsp;Total&nbsp;</td>';
									echo '<td align="right" valign="middle">&nbsp;'.$rbn_amount.'&nbsp;</td>';
								echo '</tr>';
								$rbn_amount = 0;
							}
						}
						/*if($escal_measure_sql == true)
						{
							if(mysql_num_rows($escal_measure_sql)>0)
							{
								$slno = 1; $total_cem_consum = 0; $total_item_qty = 0; $total_item_qty_month = 0;
								$prev_mdate = ""; $prev_subdivid = ""; $prev_zone_id = ""; $prev_qty = ""; $prev_itemno = ""; $prev_month = "";
								while($MList = mysql_fetch_object($escal_measure_sql))
								{
									$mdate 			 = dt_display($MList->mdate);
									$month_ts		 = strtotime($mdate);
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
									$qty 			 = $MList->measurement_contentarea;
									$itemunit 		 = $MList->remarks;
									$tc_unit 		 = $MList->tc_unit;
									$decimal_placed  = $MList->decimal_placed;
									$zone_id  		 = $MList->zone_id;
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
										$Datares = get_mbook_page_rbn($sheetid, $prev_zone_id, $prev_subdivid, $prev_month_num, $prev_year);
										$ExpDatares = explode("*",$Datares);
										$mbookno 	= $ExpDatares[0];
										$mbpage 	= $ExpDatares[1];
										$rbn 		= $ExpDatares[2];
										//echo $res."<br/>";
										echo '<tr class="labeldisplay">';
										echo '<td align="center" valign="middle">'.$slno.'</td>';
										echo '<td align="center" valign="middle">'.$prev_mdate.'</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$mbpage.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$mbookno.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$rbn.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.getzonename($sheetid,$prev_zone_id).'&nbsp;</td>';
										echo '<td align="center" valign="middle">'.$prev_itemno.'</td>';
										echo '<td align="left" valign="middle">'.$prev_description.'</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">'.$itemunit.'</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($prev_tc_unit,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
										// This is hidden box which is used to store date wise 'data' for each item.
										$date_wise_data1 = "";
										$date_wise_data1 = $prev_mdate."@*@".$mbpage."@*@".$mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$total_item_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
										echo '<input type="hidden" name="txt_date_wise_data[]" id="txt_date_wise_data" value="'.$date_wise_data1.'">';
										$total_item_qty = 0;
										$slno++;
									}
									if(($month != $prev_month)&&($prev_month != ""))
									{
										// This Row for dispaly every month wise total in kg.
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
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;kg&nbsp;</td>';
										echo '</tr>';
										// This Row for display Qty in Metric Tone for every Month
										$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
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
										echo '</tr>';
										$total_item_qty_month = 0;
										$slno = 1;
									}
									// Display Every month Title
									if($month != $prev_month)
									{
										echo '<tr class="label">';
										echo '<td align="left" valign="middle" colspan="8">&nbsp;&nbsp;Cement Consumption for the month of '.$month.' - '.$year.'&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
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
								}
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
								$total_item_qty = round($total_item_qty,$prev_decimal_placed);
								echo '<tr class="labeldisplay">';
								echo '<td align="center" valign="middle">'.$slno.'</td>';
								echo '<td align="center" valign="middle">'.$prev_mdate.'</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$mbpage.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$mbookno.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$rbn.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.getzonename($sheetid,$prev_zone_id).'&nbsp;</td>';
								echo '<td align="center" valign="middle">'.$prev_itemno.'</td>';
								echo '<td align="left" valign="middle">'.$prev_description.'</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">'.$itemunit.'</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($prev_tc_unit,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '</tr>';
								$date_wise_data2 = "";
								$date_wise_data2 = $prev_mdate."@*@".$mbpage."@*@".$mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$total_item_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
								echo '<input type="hidden" name="txt_date_wise_data[]" id="txt_date_wise_data" value="'.$date_wise_data2.'">';
								$total_item_qty = 0;
								// Last Row for dispaly Last row of month wise total in kg.
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
								echo '<td align="right" valign="middle">&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '</tr>';
								// Last Row for display Qty in Metric Tone
								$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
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
								echo '</tr>';
								$total_item_qty_month = 0;
							}
						}*/
						?>
								</table>
							</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
									<input type="submit" name="submit" id="submit" value=" Save "/>
								</div>
							</div>
                        </blockquote>
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal("", msg, "success");
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
</html>
