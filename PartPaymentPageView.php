<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
include "sysdate.php";
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
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
    return $dd . '/' . $mm . '/' . $yy;
}
if($_POST["submit"] == " View ") 
{
	$rbn 					= 	$_POST['cmb_rbn'];
	$sheetid				=	$_POST['cmb_work_no'];
	$selectmbook_detail = " select DISTINCT fromdate, todate, abstmbookno FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1' AND rbn = '$rbn'";
	$selectmbook_detail_sql = mysql_query($selectmbook_detail);
    if ($selectmbook_detail_sql == true) 
    {
	    $Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	    $fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno;
    }
}
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack()
	{
	   	url = "PartPaymentPage.php";
		window.location.replace(url);
	}
	function printPage()
	{
		var printContents = document.getElementById('printSection').innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	.table1 tr td {
		padding:3px;
		vertical-align:middle;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
				<div class="title">Part Payment View</div>
	<div class="container_12">
		<div class="grid_12">
			<blockquote class="bq1" style="overflow:auto">
				<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<div id="printSection">
						<br/>
						<table width="100%" class="table1">
							<tr>
								<td class="label" colspan="9" align="center">Part Payment Report</td>
							</tr>
							<tr class="label">
								<td align="center">S.No.</td>
								<td align="center">Date</td>
								<td align="center" nowrap="nowrap">Item No.</td>
								<td align="center">Description</td>
								<td align="center">Contents <br/>of Area</td>
								<td align="center" nowrap="nowrap">Total <br/>Paid %</td>
								<td align="center">Balance % </td>
							</tr>
							<tbody>
								<?php
								$prev_subdivid = ""; $prev_contentarea = 0; $currentline = $start_line + 10; $line = $currentline; $prev_date = "";$page = $mpage; $txtboxid = 1;
								$query = "SELECT DATE_FORMAT( mbookheader.date , '%d/%m/%Y' ) AS date ,  mbookdetail.subdivid , subdivision.subdiv_name , subdivision. div_id, 
								mbookdetail.descwork,mbookdetail.mbdetail_id, mbookdetail.measurement_contentarea , mbookheader.sheetid,
								mbookdetail.prev_paid_perc, mbookdetail.curr_paid_perc  
								FROM mbookheader
								INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
								INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
								INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) WHERE  mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND mbookheader.sheetid = '$sheetid' AND mbookheader.staffid = '$staffid' 
								ORDER BY mbookdetail.subdivid, mbookheader.mbheaderid, mbookdetail.mbdetail_id ASC" ;
								$sqlquery = mysql_query($query);
								//echo $query ;exit;		 
								$slno = 1;
								if(mysql_num_rows($sqlquery)>0){
									while($List = mysql_fetch_object($sqlquery)){  
										$decimal        = get_decimal_placed($List->subdivid,$sheetid);
										$subdivid       =$List->subdivid;
									    $curr_paid_perc =$List->curr_paid_perc;
									?>
									<tr>
										<td align="center"><?php echo $slno; ?></td>
										<td align="left"><?php echo $List->date; ?></td>
										<td align="center" nowrap="nowrap"><?php echo $List->subdiv_name; ?></td>
										<td align="left"><?php echo $List->descwork; ?></td>
										<td align="right"><?php if($List->measurement_contentarea != 0){ echo number_format($List->measurement_contentarea,$decimal,".",","); } ?></td>
										<?php
										   /*$percent_query="select subdivid, measurementbookid, pay_percent from measurementbook WHERE rbn =$rbn AND sheetid = '$sheetid' AND subdivid ='$subdivid'";
										   $pecent_sqlquery = mysql_query($percent_query);
										   if(mysql_num_rows($sqlquery)>0){
											   while($List1 = mysql_fetch_object($pecent_sqlquery)){
													$pay_percent=$List1->pay_percent;
										?>
										<?php
										   $total_paid_per=100;
										    $balance_paid_per=$total_paid_per-$pay_percent;*/
											$TotalPaidPercent = $List->prev_paid_perc + $List->curr_paid_perc;
											$BalancePercent = 100 - $TotalPaidPercent;
										?>
										<td align="right"><?php echo $TotalPaidPercent; ?>%</td>
										<td align="right"><?php echo $BalancePercent; ?>%</td>
									</tr>
									<?php $slno++; }
                                        }else{ ?>
                                    <tr><td colspan="6">No records Found</td></tr>
									<?php } ?>
							</tbody>
						</table>
					</div>
					<div>&nbsp;</div>
					<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
						<div class="buttonsection">
							<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> 
						</div>
						<div class="buttonsection">
							<input type="button" class="backbutton" value=" Print " name="btn_print" id="btn_print" onClick="printPage();"/>
						</div>
					</div>
				</form>
			</blockquote>
		</div>
	</div>
</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
    $(function() {
	$.fn.validatembooktype = function(event) {	
				if($("#cmb_mbook_type").val()==""){ 
					var a="Please select the Measurement Type";
					$('#val_mbooktype').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_mbooktype').text(a);
				}
			}
	$.fn.validateworkorder = function(event) { 
					if($("#cmb_work_no").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
	$("#top").submit(function(event){
            $(this).validatembooktype(event);
			$(this).validateworkorder(event);
         });
	$("#cmb_work_no").change(function(event){
           $(this).validateworkorder(event);
         });
    $("#cmb_mbook_type").change(function(event){
           $(this).validatembooktype(event);
         });
			
	 });
</script>
    </body>
</html>

