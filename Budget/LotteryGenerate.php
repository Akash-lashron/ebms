<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
//require_once 'ExcelReader/excel_reader2.php';			// 11-11-2022 COMMENTED LINE
include "common.php";
checkUser();
$msg = '';  $PageId = 0;
if(isset($_GET['loiid'])){
	$PageId = $_GET['loiid'];
}
if(isset($_POST['btn_assign'])){
	$UpdContName = '';	$BmIDArr = array();
	$PageId = $_POST['txt_page_id'];
	$WorkId = $_POST['cmb_shortname'];
	$BidderId = $_POST['cmb_bidder'];
	$SelContName = "SELECT name_contractor FROM contractor WHERE contid = '$BidderId'";
	$SelContSql = mysqli_query($dbConn,$SelContName);
	if($SelContSql == true){
		if(mysqli_num_rows($SelContSql)>0){
			$List2 = mysqli_fetch_object($SelContSql);
			$UpdContName = $List2->name_contractor;
		}
	}
	$AllBiddersQuery = "SELECT * FROM bidder_bid_master WHERE tr_id = '$WorkId' AND contid != '$BidderId' ORDER BY quoted_pos ASC, quoted_amt_af_reb ASC";
	$SelectSql1 = mysqli_query($dbConn,$AllBiddersQuery); 
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$TotUpdCount = mysqli_num_rows($SelectSql1);
			$UpdateCheck = 0;   $StatusPos = 2;
			while($List = mysqli_fetch_object($SelectSql1)){
				$BmID = $List->bmid;
				$ContId = $List->contid;
				if($BidderId == $BidderId){
					$UpdateQuery = "UPDATE bidder_bid_master SET is_lottery='' WHERE bmid = '$BmID'";
				}else{
					$UpdateQuery = "UPDATE bidder_bid_master SET quoted_pos='$StatusPos', is_lottery='' WHERE bmid = '$BmID'";
					$UpdateSql = mysqli_query($dbConn,$UpdateQuery);
					if($UpdateSql == true){
						$StatusPos++;
						$UpdateCheck++;
					}
				}
				
				if($UpdateCheck == $TotUpdCount){
					$msg = "Sucess : '".$UpdContName."' has been assigned as Lottery L1 Bidder..!!";
				}else{
					$msg = "sorry .. unable to assign Lottery L1 Bidder, please try again..!!";
				}
			}
		}
	}
}
$PageName = $PTPart1.$PTIcon.'Lottery - Generate';

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

//		Part To Show Works in Dropdown - STARTS HERE		//
$sheet = '';
$TrArr = array();
$sheetquery1 = "SELECT a.tr_id, a.globid, a.quoted_amt_af_reb, a.is_lottery FROM bidder_bid_master a 
				JOIN ( SELECT quoted_amt_af_reb FROM bidder_bid_master WHERE is_lottery = 'Y' GROUP BY quoted_amt_af_reb HAVING COUNT(quoted_amt_af_reb) > 1 ) 
				duplicates ON a.quoted_amt_af_reb = duplicates.quoted_amt_af_reb GROUP BY a.globid";
$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
if ($sheetsqlquery1 == true )
{
	while($row1 = mysqli_fetch_array($sheetsqlquery1))
	{
		$TrId1 = $row1['tr_id'];
		array_push($TrArr,$TrId1);
	}
	$ImplodeTrArr = implode(',',$TrArr);
	if($ImplodeTrArr != null ){
		if($_SESSION['isadmin'] == 1){
			$sheetquery = "SELECT * FROM tender_register WHERE tr_id IN(".$ImplodeTrArr.") ORDER BY tr_id ASC";
		}else{
			$sheetquery = "SELECT * FROM tender_register WHERE tr_id IN(".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
		}
		$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
		if ($sheetsqlquery == true )
		{
			while($row = mysqli_fetch_array($sheetsqlquery))
			{
				if($TrId == $row['tr_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				if(($row['ccno'] != "") && ($row['ccno'] != NULL)){
					$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['ccno'].'-'.$row['tr_no'].'</option>';
				}else{
					$sheet .=  '<option value="'. $row['tr_id'].'" '.$sel.'>'.$row['tr_no'].'</option>';
				}
			}
		}
	}
}
//		Part To Show Works in Dropdown - ENDS HERE		//

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "MyView.php";
		window.location.replace(url);
	}
	function goBackAcc()
	{
	   	url = "MyViewAccounts.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form name="form" method="post" action="LotteryGenerate.php">
		<?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1">
						<div class="row">
							<div class="box-container box-container-lg" align="center">
								<div class="div2">&nbsp;</div>
								<div class="div8">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="center">Lottery - Generate</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="row">																	

														<div class="row clearrow"></div>
														<div class="row">
															<div class="div3 lboxlabel">  
																Tender No.
															</div>
															<div class="div9"> 
																<select name="cmb_shortname" id="cmb_shortname" class="tboxclass">
																	<option value="">----------- Select -----------</option>
																	<?php echo $sheet; ?>
																</select>
															</div>
														</div>
														<div class="row clearrow"></div>
														<div class="row">
															<div class="div3 lboxlabel">
																Bidder Name
															</div>
															<div class="div9"> 
																<select name="cmb_bidder" id="cmb_bidder" class="tboxclass">
																	<option value="">----------- Select -----------</option>
																</select>
															</div>
														</div>
														<input type="hidden" class="btn btn-info" name="txt_pageid" id="txt_pageid" value="<?php if(isset($PageId)){ echo $PageId; } ?>" />
														<div class="row clearrow"></div>
														<div class="row">
															<div class="div12" align="center">
																<a data-url="Home" class="btn btn-info" name="view" id="view">Back</a>
																<input type="submit" class="btn btn-info" name="btn_assign" id="btn_assign" value=" Assign L1 " />
															</div>
														</div>
														<input type="hidden" class="btn btn-info" name="txt_all_bid" id="txt_all_bid" value="<?php if(isset($PageId)){ echo $PageId; } ?>" />
														<input type="hidden" class="btn btn-info" name="txt_page_id" id="txt_page_id" value="<?php if(isset($PageId)){ echo $PageId; } ?>" />
													</div>
													
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="div2">&nbsp;</div>
							</div>
						</div>
					</blockquote>
				</div>
			</div>
		</div>
		<!--==============================footer========================-->
    <?php include "footer/footer.html"; ?>
	<script>
		var msg = "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		var pageidval = $("#txt_page_id").val();
		if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						dialog.close();
						window.location.replace('LotteryGenerate.php');
					}
				}]
			});
		}

		$("#cmb_shortname").chosen();
		$('#cmb_bidder').chosen();
		$(document).ready(function(){ 
			$("body").on("change","#cmb_shortname", function(event){
				var Id = $(this).val();
				$("#txt_work_name").val('');
				$('#cmb_bidder').chosen('destroy');
				$('#cmb_bidder').children('option:not(:first)').remove();
				$("#cmb_bidder").chosen();
				$.ajax({
					type: 'POST',
					url: 'GetLotteryBidders.php',
					data: { MastId: Id, Page: 'LOTBID'},
					dataType: 'json',
					success: function (data) { 				//alert(JSON.stringify(data['ContArr']));
						var ContArr = data['ContArr']; 		// alert(ContArr);
						var ContL1  = data['ContL1']; 
						$('#cmb_bidder').chosen('destroy');
						var MyArr = {};
						$.each(ContArr, function(index, value) {
							$("#cmb_bidder").append('<option value="'+value.contid+'">'+value.contname+'</option>');
							MyArr[value.contid] = value.contname;
						});
							//alert(JSON.stringify(MyArr));
						$('#txt_all_bid').val(JSON.stringify(MyArr));
						$("#cmb_bidder").chosen();
					}
				});
			});
			var KillEvent = 0;
			$("body").on("click","#btn_assign", function(event){
				if(KillEvent == 0){
					var TendNum  = $("#cmb_shortname").val();
					var BidderVal = $("#cmb_bidder").val();
					var BidderName = $("#cmb_bidder option:selected").text();
					if(TendNum == ""){ 
						BootstrapDialog.alert("Please Select Tender Number.");
						event.preventDefault();
						return false;
					}else if(BidderVal == ""){ 
						BootstrapDialog.alert("Please Select Lottery L1 Bidder..!!");
						event.preventDefault();
						return false;
					}else{
						event.preventDefault();
						BootstrapDialog.confirm('Are you sure want to Assign "'+BidderName+'" as L1 Lottery Winner?', function(result){
							if(result) {
								KillEvent = 1;
								$("#btn_assign").trigger( "click" );
							}
						});
					}
				}
			});
		});
	</script>
    </body>
</html>

