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
} */
$mbooktype 		= $_SESSION['mbooktype'];
$mbookno 		= $_SESSION['mbookno'];
$mbookname 		= $_SESSION['mbookname'];
$issue_authority = $_SESSION['issue_authority'];
$sheetid 		= $_SESSION['workno'];
$mbookdate = $_SESSION['mbookdate'];
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

$select_electricity_query = "select generate_waterbill.meter_no, generate_waterbill.wbill_no, generate_waterbill.imr, generate_waterbill.imr_date, 
								generate_waterbill.fmr, generate_waterbill.fmr_date, generate_waterbill.rate, generate_waterbill.w_limit,
								generate_waterbill.meter_rent, generate_waterbill.water_cost, generate_waterbill.wr_date, sheet.work_order_no, sheet.work_name, 
								sheet.short_name, sheet.tech_sanction, sheet.name_contractor, sheet.agree_no, sheet.computer_code_no 
								from generate_waterbill 
								INNER JOIN sheet ON (generate_waterbill.sheetid = sheet.sheet_id)
								where generate_waterbill.sheetid = '$sheetid' and sheet.sheet_id = '$sheetid' and generate_waterbill.rbn = '$rbn'";
$select_electricity_sql = mysql_query($select_electricity_query);
//echo $select_electricity_query;
if($select_electricity_sql == true)
{
	$RecList = mysql_fetch_object($select_electricity_sql);
	$Wbillno 	= $RecList->wbill_no;
	$Wbilldate 	= $RecList->wr_date;
	$WorkName 	= $RecList->work_name;
	$TSName 	= $RecList->tech_sanction;
	$AggName 	= $RecList->agree_no;
	$MeterNo 	= $RecList->meter_no;
	$IMR 		= $RecList->imr;
	$IMRDate 	= $RecList->imr_date;
	$FMR 		= $RecList->fmr;
	$FMRDate 	= $RecList->fmr_date;
	$WRate 		= $RecList->rate;
	$WLimit 	= $RecList->w_limit;
	$MeterRent 	= $RecList->meter_rent;
	$WAmount 	= $RecList->water_cost;
}
?>

<?php require_once "Header.html"; ?>
<link rel="stylesheet" href="script/font.css" />
<style>
    tr{ height:25px; }
	table { border:2px double #000000; }
	tr { border: none; }
	.labelheadprint { border: none; font-size:15px; }
	/*.labelheadprint
	{
		font-weight:normal;
		color:#000000;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:15px;
	}*/
	@media print 
	{
		.printbutton
		{
			display: none !important;
		}
		.labelheadprint { border: none; font-size:15px; }
	}
</style>
<script>
  	function goBack()
	{
	   	url = "MbookFrontPage.php";
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
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                            <div class="title printbutton">MBook Front Page - Print View</div>
                <div class="container_12">
                    <div class="grid_12" align="center">
                        <blockquote class="bq1" style="overflow-y:auto;">
							<br/>
							<div style="">
                        	<table width="1060px" border="0" align="center" cellpadding="0" cellspacing="0" class="color4">
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Government of India</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Department of Atomic Energy</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Indira Gandhi Centre for Atomic Research</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Fast Reactor Fuel Cycle Facility (FRFCF)</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="30%" align="left" class="labelheadprint"><b>MEASUREMENT BOOK NO </b>  </td>
									<td width="30%" align="left" class="labelheadprint">:&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $mbookname; ?></b></td>
									<td align="left" class="labelheadprint">
										<label style="border:2px solid #000000; padding:4px 12px 4px 12px;"><b><?php echo $mbookno; ?></b></label>
									</td>
								</tr>
								
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Issued to (Name) </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<b><u><?php echo "Shri. ".$staffname; ?></u></b> 
									</td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" class="labelheadprint">Designation </td>
									<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<b><u><?php echo $designation; ?></u></b> 
									</td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" colspan="3" class="labelheadprint">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Certified that this measurement book contains Page No. <b><u>01</u></b> to <b><u>100</u></b> Pages only.
									 </td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="25%" align="left" class="labelheadprint">Date : <?php echo $mbookdate; ?></td>
									<!--<td align="left" class="labelheadprint"></td>-->
									<td align="right" colspan="2" class="labelheadprint">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "Signature of the Issuing Authority."; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
							</table>
							</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
									<input type="button" class="backbutton" name="print" id="print" value="Print" onClick="javascript:window.open('Print_MbookFrontPage.php','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=350,WIDTH=685,LEFT=100,TOP=40')"/>
								</div>
							</div>
                        </blockquote>
                    </div>
                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
</html>
