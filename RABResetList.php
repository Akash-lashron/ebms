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




if($_GET["suc"]){
	$success = $_GET["suc"];
	if($success == 1){
		$msg = "RAB Reset Successfully";
	}else if($success == 0){
		$msg = "Unable to reset RAB, please contact administrator..!!";
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
			<div class="title">Current RAB List</div>
			<div class="container_12">
				<div class="grid_12">
					<blockquote class="bq1" style="overflow:auto">
						<div class="container" align="center">
							<table class="table-bordered table1" align="center" id="dataTable">
								<thead>
									<tr>
										<th align="center" valign="middle">SlNo.</th>
										<th align="center" valign="middle" nowrap="nowrap">CC No.</th>
										<th align="left" valign="middle" style="width:15%">WO No.</th>
										<th align="center" valign="middle">Work Short Name</th>
										<th align="center" valign="middle">Name Of Work</th>
										<th align="center" valign="middle" nowrap="nowrap">RAB</th>
										<th align="center" valign="middle" nowrap="nowrap">Action</th>
									</tr>
								</thead>
								<tbody>
								    <?php 
									$sheetquery = "SELECT * FROM sheet WHERE active=1 ORDER BY sheet_id ASC";
									$sheetsqlquery = mysql_query($sheetquery);
									if($sheetsqlquery == true ){
										$AbstRbnStr = '';  $sno = 1; 
										while($SList = mysql_fetch_object($sheetsqlquery)){
											$IsData = 0;
											$sheetid = $SList->sheet_id;
											$select_abstbook = "select distinct * from abstractbook where sheetid = '$sheetid' and rab_status='P'";
											$select_abstbook_sql = mysql_query($select_abstbook);
											if($select_abstbook_sql == true){
												$count2 = mysql_num_rows($select_abstbook_sql);
												if($count2 >0){
													$AbstList = mysql_fetch_object($select_abstbook_sql);
													$reset  = 0;
													$count2 = 0;
													$count3 = 0;
													$AbstRbn = $AbstList->rbn;
													if($_SESSION['isadmin'] == 1) {
														if($workordernolistvalue == $SList->sheet_id){
															$sel = "selected";
														}else{
															$sel = "";
														}
														$IsData++;
													}else{
														$select_send_acc_query = "select distinct * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$AbstRbn'";
														$select_send_acc_sql = mysql_query($select_send_acc_query);
														if($select_send_acc_sql == true && mysql_num_rows($select_send_acc_sql)>0){
															$SacList = mysql_fetch_object($select_send_acc_sql);
															$count3 = mysql_num_rows($select_send_acc_sql);
														}
														if($count3 >0){
															$reset = 2;		// FOR ADMIN TO DELETE AFTER RETURNED FROM ACCOUNTS
														}
														if($reset != 2) {
															$assigned_staff = $SList->assigned_staff;
															$AssignStaff = explode(",",$assigned_staff);
															if(in_array($_SESSION['sid'],$AssignStaff))
															{
																if ($workordernolistvalue == $SList->sheet_id)
																{
																	$sel = "selected";
																}
																else
																{
																	$sel = "";
																}
																$IsData++;
															}
														}
													}
													if($IsData > 0){
														?>
														<tr>
															<td align="center" style="width:4%;"><?php echo $sno ?></td>
															<td align="center" style="width:7%;"><?php echo $SList->computer_code_no; ?></td>
															<td align="left" style="width:10%;"><?php echo $SList->work_order_no; ?></td>
															<td align="left" valign="middle" style="text-align: justify; text-justify: inter-word; width:30%"><?php echo $SList->short_name; ?></td>
															<td align="left" valign="middle" style="text-align: justify; text-justify: inter-word; width:31%"><?php echo $SList->work_name; ?></td>
															<td align="center" style="width:5%;"><?php echo $AbstRbn; ?></td>
															<td align="center" valign="middle" nowrap="nowrap" style="width:13%;">
																<a href="RABResetDetail.php?id=<?php echo $SList->sheet_id; ?>&rbn=<?php echo $AbstRbn ?>" class="ActBtn" name="btnReset" id="btnReset"> View & Reset </a>
															</td>
														</tr>
								    					<?php
														$sno++; 
													}  
												} 
											}  
										} 
									} ?>
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
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			event.preventDefault();
			BootstrapDialog.show({
				title: 'Information',
				message: msg,
				closable: false, 	// <-- Default value is false
				draggable: false, 	// <-- Default value is false
				buttons: [{
					label: 'OK',
					action: function(dialog) {
						//dialog.close();
						$(location).attr('href', 'RABResetList.php')
					}
				}]
			});
		}
	};

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
