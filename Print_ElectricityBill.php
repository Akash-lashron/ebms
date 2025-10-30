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
/*if (isset($_POST["submit"])) 
{
   	$sheetid 			= 	trim($_POST['cmb_shortname']);
	$rbn 				= 	trim($_POST['txt_rbn']);
	$meter_no 			= 	trim($_POST['txt_meterno']);
	$ebill_no 			= 	trim($_POST['txt_billno']);
    $imr 				= 	trim($_POST['txt_initial']);
    $imr_date 			= 	dt_format(trim($_POST['txt_initial_date']));
	$fmr 				= 	trim($_POST['txt_final']);
    $fmr_date 			= 	dt_format(trim($_POST['txt_final_date']));
    $rate 				= 	trim($_POST['txt_rate']);
	$meter_rent 		= 	trim($_POST['txt_meter_rent']);
    $electricity_cost	= 	trim($_POST['txt_electricity_cost']);
    $er_date 			= 	dt_format(trim($_POST['txt_date']));
    $erecovery_sql 		= 	"INSERT INTO generate_electricitybill set
                                            sheetid 		= '$sheetid',
											rbn 			= '$rbn',
                                            meter_no 		= '$meter_no',
											ebill_no 		= '$ebill_no',
											imr 			= '$imr',
                                            imr_date 		= '$imr_date',
											fmr 			= '$fmr',
                                            fmr_date 		= '$fmr_date',
                                            rate 			= '$rate',
											meter_rent 		= '$meter_rent',
											electricity_cost = '$electricity_cost',
                                            er_date 		= '$er_date',
											staffid 		= '$staffid',
                                            userid 			= '$userid',
											modifieddate 	= NOW(),
											active 			= 1";
											//modifieddate = NOW()";
    $erecovery_query 	= 	mysql_query($erecovery_sql);
	//echo $erecovery_sql;
    if($erecovery_query == true) 
	{
        $msg = "Electricity Charge Details Stored Successfully ";
		$success = 1;
    }
	else
	{
		$msg = " Something Error...!!! ";
		//die(mysql_error());
	}
} 
*/$sheetid = $_SESSION['Sheetid'];

$staff_design_sql = "select staff.staffname, designation.designationname from staff INNER JOIN designation ON (designation.designationid = staff.designationid) WHERE staff.staffid = '$staffid' AND staff.active = 1 AND designation.active = 1";
$staff_design_query = mysql_query($staff_design_sql);
$staffList = mysql_fetch_object($staff_design_query);
$staffname = $staffList->staffname;
$designation = $staffList->designationname;


$select_rbn_query = "select distinct rbn from mbookgenerate where sheetid = '$sheetid'";
$select_rbn_sql = mysql_query($select_rbn_query);
if($select_rbn_sql == true)
{
	$RBNList = mysql_fetch_object($select_rbn_sql);
	$rbn = $RBNList->rbn;
}

$select_electricity_query = "select generate_electricitybill.meter_no, generate_electricitybill.ebill_no, generate_electricitybill.imr, generate_electricitybill.imr_date, 
								generate_electricitybill.fmr, generate_electricitybill.fmr_date, generate_electricitybill.rate, generate_electricitybill.meter_rent,
								generate_electricitybill.electricity_cost, generate_electricitybill.er_date, sheet.work_order_no, sheet.work_name, 
								sheet.short_name, sheet.tech_sanction, sheet.name_contractor, sheet.agree_no, sheet.computer_code_no 
								from generate_electricitybill 
								INNER JOIN sheet ON (generate_electricitybill.sheetid = sheet.sheet_id)
								where generate_electricitybill.sheetid = '$sheetid' and sheet.sheet_id = '$sheetid' and generate_electricitybill.rbn = '$rbn'";
$select_electricity_sql = mysql_query($select_electricity_query);
//echo $select_electricity_query;
if($select_electricity_sql == true)
{
	$RecList = mysql_fetch_object($select_electricity_sql);
	$Ebillno 	= $RecList->ebill_no;
	$Ebilldate 	= $RecList->er_date;
	$WorkName 	= $RecList->work_name;
	$TSName 	= $RecList->tech_sanction;
	$AggName 	= $RecList->agree_no;
	$MeterNo 	= $RecList->meter_no;
	$IMR 		= $RecList->imr;
	$IMRDate 	= $RecList->imr_date;
	$FMR 		= $RecList->fmr;
	$FMRDate 	= $RecList->fmr_date;
	$ERate 		= $RecList->rate;
	$MeterRent 	= $RecList->meter_rent;
	$EAmount 	= $RecList->electricity_cost;
}
?>

<?php require_once "Header.html"; ?>
<link rel="stylesheet" href="script/font.css" />
<style>
    tr{ height:25px; }
	.labelheadprint { border: none; font-size:20px; }
	@media print 
	{
		.printbutton
		{
			display: none !important;
		}
		.labelheadprint { border: none; font-size:20px; }
	}
</style>
<script>
  	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	function printBook()
	{
		window.print();
	}
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" style="background-color:#FFFFFF;" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
            <!--==============================Content=================================-->
                        	<table width="1060px" border="0" align="center" cellpadding="0" cellspacing="0" class="color4">
								<tr><td colspan="4" align="center" class="labelheadprint">Government of India</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Department of Atomic Energy</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Indira Gandhi Centre for Atomic Research</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Fast Reactor Fuel Cycle Facility (FRFCF)</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="25%" align="left" class="labelheadprint">Electricity Bill No  </td>
									<td align="left" class="labelheadprint">:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Ebillno; ?></td>
									<td align="center" class="labelheadprint">Date : &nbsp;&nbsp;&nbsp;&nbsp;<?php echo dt_display($Ebilldate); ?>&nbsp;&nbsp;</td>
								</tr>
								
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Name of work </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $WorkName; ?></b> 
									</td>
								</tr>
								
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Technical sanction No. </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $TSName; ?> 
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Agreement No. </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $AggName; ?> 
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Meter No. </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $MeterNo; ?>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Initial meter reading </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $IMR; ?> 
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Final meter reading </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $FMR; ?> 
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Consumption of Electricity </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($FMR-$IMR); ?>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Rate of Electricity </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:4px; width:4px; height:5px;'></i>&nbsp;&nbsp;&nbsp;<?php echo number_format($ERate, 2, '.', ''); ?> 
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Meter Rent </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:4px; width:4px; height:5px;'></i>&nbsp;&nbsp;&nbsp;<?php echo number_format($MeterRent, 2, '.', ''); ?> 
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Electricity charges </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:4px; width:4px; height:5px;'></i>&nbsp;&nbsp;&nbsp;<?php echo number_format($EAmount, 2, '.', ''); ?> 
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Proposed to be recorded in  </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo "RAB - ".$rbn; ?></b>
									</td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="25%" align="left" class="labelheadprint"> </td>
									<td align="left" class="labelheadprint"></td>
									<td align="center" class="labelheadprint">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $designation.", FRFCF"; ?></b>&nbsp;&nbsp;</td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="25%" align="left" class="labelheadprint"></td>
									<td align="left" class="labelheadprint"></td>
									<td align="center" class="labelheadprint">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo "L. Davy Herbert."; ?></b>&nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="25%" align="left" class="labelheadprint"></td>
									<td align="left" class="labelheadprint"></td>
									<td align="center" class="labelheadprint">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo "PE, CIVIL, FRFCF"; ?></b>&nbsp;&nbsp;</td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="25%" align="left" class="labelheadprint"><b>To</b></td>
									<td align="left" class="labelheadprint"></td>
									<td align="center" class="labelheadprint"></td>
								</tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="25%" align="left" class="labelheadprint"><b>AAO (Works)</b></td>
									<td align="left" class="labelheadprint"></td>
									<td align="center" class="labelheadprint"></td>
								</tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="25%" align="left" class="labelheadprint"><b>IGCAR</b></td>
									<td align="left" class="labelheadprint"></td>
									<td align="center" class="labelheadprint"></td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
							</table>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="close" id="close" value="Close" onClick="javascript:window.close();"/>
								</div>
								<div class="buttonsection">
									<input type="button" class="backbutton" name="print" id="print" value="Print" onClick="printBook();"/>
								</div>
							</div>
        </form>
    </body>
</html>
