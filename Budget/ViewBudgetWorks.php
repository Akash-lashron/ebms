<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Price Bid Upload';
checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
function dt_format($ddmmyyyy){
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	return $yy .'-'. $mm .'-'.$dd;
}
function dt_display($ddmmyyyy){
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}
if(isset($_POST["save"]) == " SAVE "){
	
	$GlobIdArr 	= $_POST['txt_globid'];
	$PoAmtArr 	= $_POST['txt_po_amt'];
	if(count($GlobIdArr)>0){
		foreach($GlobIdArr as $GlobKey => $GlobValue){
			$SaveGlobId = $GlobValue;
			$PostPoAmt = $PoAmtArr[$GlobKey];
			$UpdateQuery = "UPDATE works SET wo_amount = '$PostPoAmt' WHERE globid = '$SaveGlobId'";
			$UpdateSql 	 = mysqli_query($dbConn,$UpdateQuery);
		}
	}
}
$RowCount = 0;
$SelectQuery = "SELECT * FROM works ORDER BY CAST(ccno AS UNSIGNED INTEGER) ASC";
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql) > 0){
		$RowCount = 1;
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function goBack()
	{
	   	url = "UploadWorks.php";
		window.location.replace(url);
	}
</script>
<style>
	.DispTable{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
	}
	.DispTable th, .DispTable td{
		border:1px solid #BCBEBF;
		border-collapse:collapse;
		padding:2px 3px;
	}
	.DispTable th{
		background-color:#035a85;
		color:#fff;
		vertical-align:middle;
		text-align:center;
	}
	.DispTable td{
		color:#062C73;
	}
	.HideDesc{
		max-width : 768px; 
	  	white-space : nowrap;
	  	overflow : hidden;
	  	text-overflow: ellipsis;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="" method="post" enctype="multipart/form-data" name="form">
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1" style="overflow:auto">
                            <div class="container">
								<div class="row ">
									<div class="div12">
										<div class="row">
											<div class="div1">&nbsp;</div>
											<div class="div10">
											<table class="DispTable" width="100%">
												<thead>
													<tr>
														<th>SNo.</th>
														<th nowrap="nowrap">CCNo.</th>
														<th>Name of Work</th>
														<th>PO Date.</th>
														<th>PO Amount</th>
													</tr>
												</thead>
												<tbody>
												<?php if($RowCount == 1){ $Sno = 1;
														while($WorkList = mysqli_fetch_object($SelectSql)){
												?>
													<tr>
														<td align="center"><?php echo $Sno; ?></td>
														<td align="center"><?php echo $WorkList->ccno; ?><input type="hidden" name="txt_globid[]" value="<?php echo $WorkList->globid; ?>"></td>
														<td align="left"><?php echo $WorkList->work_name; ?></td>
														<td align="center"><?php if($WorkList->wo_date != '0000-00-00'){ echo dt_display($WorkList->wo_date); } ?></td>
														<td align="right"><input type="text" name="txt_po_amt[]" value="<?php echo $WorkList->wo_amount; ?>"></td>
													</tr>
												<?php $Sno++; } } ?>
												</tbody>
											</table>
											</div>
											<div class="div1">&nbsp;</div>
										</div>
										<div class="smediv">&nbsp;</div>
									</div>
								</div>
								<?php if($TotalError > 0){ ?>
								<div class="row">
									<div class="div1">&nbsp;</div>
									<div class="div10" style="background-color:#FFFFFF; color:#DA0532; font-weight:bold;">
										Total Error/s in Excel File : <?php echo $TotalError; ?>
									</div>
									<div class="div1">&nbsp;</div>
								</div>
								<?php } ?>
								<div class="row">
									<div class="div12" align="center">
										<input type="button" class="btn btn-info" name="back" id="back" value=" BACK " onClick="goBack();"/>
										<input type="submit" class="btn btn-info" name="save" id="save" value=" SAVE "/>
									</div>
								</div>  
								<div class="row">&nbsp;</div>                         
                            </div>
                    </blockquote>
                </div>

            </div>
        </div>
	</form>
         <!--==============================footer=================================-->
	<?php include "footer/footer.html"; ?>
	<script>
		var msg 	= "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						dialog.close();
						window.location.replace('UploadWorks.php');
					}
				}]
			});
		}

		var KillEvent = 0;
		$(document).ready(function(){ 
			$("body").on("click","#confirm", function(event){
				if(KillEvent == 0){
					var TrId 	= $("#txt_TrId").val();
					var BidderId 	= $("#txt_bidderid").val();
					var RebatePerc 	= $("#txt_rebate_perc").val();
					if(TrId == ""){
						BootstrapDialog.alert("Invalid Work. Unable to Save.");
						event.preventDefault();
						event.returnValue = false;
					}else if(BidderId == ""){
						BootstrapDialog.alert("Invalid Bidder. Unable to Save");
						event.preventDefault();
						event.returnValue = false;
					}else{
						event.preventDefault();
						BootstrapDialog.confirm('Are you sure want to confirm ?', function(result){
							if(result) {
								KillEvent = 1;
								$("#confirm").trigger( "click" );
							}
						});
					}
				}
			});
			function FormatNumberToINR(num) {
				input = num;
				var n1, n2;
				num = num + '' || '';
				// works for integer and floating as well
				n1 = num.split('.');
				n2 = n1[1] || null;
				n1 = n1[0].replace(/(\d)(?=(\d\d)+\d$)/g, "$1,");
				num = n2 ? n1 + '.' + n2 : n1;
				console.log("Input:",input)
				console.log("Output:",num)
				return num;
			}
			$("body").on("change","#txt_rebate_perc", function(event){
				var RebatePerc 	 = $("#txt_rebate_perc").val();
				var TotalAmount  = $("#txt_total_amt").val();
				var RebateAmount = Number(TotalAmount)*Number(RebatePerc) / 100;
				var TotAmtWithRebate = Number(TotalAmount)-Number(RebateAmount);
					TotAmtWithRebate = TotAmtWithRebate.toFixed(2);
				if(TotAmtWithRebate != ""){
					var FinalAmount = FormatNumberToINR(TotAmtWithRebate);
				}else{
					var FinalAmount = "";
				}
				var RebatePerc1 = Number(RebatePerc).toFixed(2);
				var RebatePerc2 = FormatNumberToINR(RebatePerc1);
				$("#txt_rebate_perc").val(RebatePerc2);
				$("#txt_total_with_rebate").val(FinalAmount);
			});
		});
	</script>
    </body>
</html>

