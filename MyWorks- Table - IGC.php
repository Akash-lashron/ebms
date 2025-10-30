<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';//include "common.php";
checkUser();
$msg = ''; $Scount = 0;
$sheetid = $_SESSION['Sheetid'];
$staffid = $_SESSION['sid'];
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
if($_SESSION['isadmin'] == 1){
	$SelectSheetQuery = "select * from sheet where (active = 1 OR active = 2) ORDER BY short_name ASC";
}else{
	$SelectSheetQuery = "select * from sheet where (active = 1 OR active = 2) and CONCAT(',' ,assigned_staff, ',') LIKE '%,$staffid,%' ORDER BY short_name ASC";
}
//echo $SelectSheetQuery;exit;
$SelectSheetSql   = mysql_query($SelectSheetQuery);
if($SelectSheetSql == true){
  if(mysql_num_rows($SelectSheetSql)>0){
	 $Scount  =1;
  }
}
?>
<?php require_once "Header.html"; ?>
<style>
.container{
		width:100%;
		border-collapse: collapse;
	}
		
	.table-row{  
		display:table-row;
		text-align: left;
	}
	.col{
		display:table-cell;
		border: 1px solid #CCC;
	}
/*a:link {
    text-decoration: none;
}
a:visited {
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
	color: #fff;
}
a:active {
    text-decoration: underline;
}*/
.circle {
    background: #19bc8b ;
    color: #fff;
    display: block;
    padding: 3px 8px;
    text-align: center;
    text-decoration: none;
    border-radius: 10px;
}
.circle:hover {
    background: #EC2951;
	color:#fff;
}
</style>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
</SCRIPT>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
	<!--==============================header=================================-->
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
		<?php include "Menu.php"; ?>
		<!--==============================Content=================================-->
		<div class="content">
			<div class="title">My Works in Billing</div>
			<div class="container_12">
				<div class="grid_12">
					<blockquote class="bq1" style="overflow:auto">
						<div class="container" align="center">
							<table class="table-bordered table1" align="center" id="dataTable">
								<thead>
									<tr>
										<th align="center" valign="middle">SlNo.</th>
										<th align="center" valign="middle" nowrap="nowrap">CC No.</th>
										<th align="left" valign="middle">WO No.</th>
										<th align="center" valign="middle" style="width:15%">Name Of Work</th>
										<th align="center" valign="middle" nowrap="nowrap">Cost of Work &#x20B9;</th>
										<th align="center" valign="middle" nowrap="nowrap">Contractor Name</th>
										<th align="center" valign="middle" nowrap="nowrap">Work Commence Date</th>
										<th align="center" valign="middle" nowrap="nowrap">Schedule D.O.C</th>
										<th align="center" valign="middle" nowrap="nowrap">Work Duration</th>
										<th align="center" valign="middle">Current RAB</th>
										<!--<th align="center" valign="middle">Status</th>-->
									</tr>
								</thead>
								<tbody>
								    <?php $sno = 1;  if ($Scount==1) { while($SList = mysql_fetch_object($SelectSheetSql)){
										  $SheetId = $SList->sheet_id;
										  $PinNo   = $SList->pinid;
										  $SelectMaxRabQuery  = "select rbn,fromdate,todate,sheetid from abstractbook where rbn = (SELECT MAX(rbn) FROM abstractbook where sheetid = '$SheetId') ";
										  $SelectMaxRabSql 	  = mysql_query($SelectMaxRabQuery);
											if($SelectMaxRabSql == true){
											     $CRLIST 		= mysql_fetch_object($SelectMaxRabSql);
											     $RBN           = $CRLIST->rbn;
											     $FromDate      = $CRLIST->fromdate;
											     $Todate        = $CRLIST->todate;
											}
										  $SelectPinNoQuery  = "select pin_no FROM  pin_entry where pinid = '$PinNo'";
										  $SelectPinNoSql 	 = mysql_query($SelectPinNoQuery);
											if($SelectPinNoSql == true){
													 $PINLIST 	= mysql_fetch_object($SelectPinNoSql);
													 $PinNO     = $PINLIST->pin_no;
													
											}
									?>
									<tr>
										<td align="center"><?php echo $sno ?></td>
										<td align="center"><?php echo $SList->computer_code_no; ?></td>
										<td align="center"><?php echo $SList->work_order_no; ?></td>
										<td align="left" valign="middle" style="text-align: justify; text-justify: inter-word; width:15%"><?php echo $SList->work_name; ?></td>
										<td align="right" valign="middle"><?php echo IND_money_format($SList->work_order_cost); ?></td>
										<td align="left" valign="middle" style="text-align: justify; text-justify: inter-word;"><?php echo $SList->name_contractor; ?></td>
										<td align="center" valign="middle"><?php echo dt_display($SList->work_commence_date); ?></td>
										<td align="center" valign="middle"><?php echo dt_display($SList->date_of_completion); ?></td>
										<td align="center" valign="middle"><?php if($SList->work_duration != ''){ echo $SList->work_duration." - Months"; } ?></td>
										<td align="center" style="width:12%;"><?php if ($RBN !="") { echo $RBN; }else{ echo "New Work"; } ?></td>
										<!--<td align="center" style="width:10%;" nowrap="nowrap">
									       <a href="CurrentRabStatus.php?SheetId=<?php echo $SheetId;?>&&CRBN=<?php echo $RBN;?>" class="circle">View Status</a>
										</td>-->
									</tr>
								    <?php $sno++; } } ?>
								</tbody>
							</table>
						</div>
						<!--<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
							<div class="buttonsection"><input type="button" name="back" id="back" value="Back" class="backbutton"></div>
						</div>-->
					</blockquote>
				</div>
			</div>
		</div>
		<!--==============================footer=================================-->
		<?php include "footer/footer.html"; ?>
	</form>
</body>
</html>
<script>
	$(document).ready(function() {
		$('#dataTable').DataTable({
			responsive: true,
			paging: true,
		});
		$('#back').click(function(){
			$(location).attr('href', 'MyView.php')
		});
	});
</script>
<style>
	/*#dataTable_wrapper{
		width:75% !important;
	}*/
	table.table3.dataTable thead th{
	    text-align:left !important;
	}
	.dataTables_wrapper{
		width:95% !important;
	}
	#dataTable th, td{
		font-size:11px;
		line-height:18px;
		padding:3px;
	}
	#dataTable th{
		padding:5px;
	}
</style>
