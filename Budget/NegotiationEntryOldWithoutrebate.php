<?php
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
checkUser();
//require_once 'ExcelReader/excel_reader2.php';
$msg = '';

$RowCount = 0;
if(isset($_POST['View'])){
	if(isset($_GET['ncsid'])){
		$PageId = $_GET['ncsid'];
		//echo $PageId;exit;	
	}
	$MastId 	 = $_POST['cmb_shortname'];
	$ContractId  = $_POST['cmb_bidder'];
	
	$GlobID= '';
	$GlobIDQuery = "SELECT globid,work_name FROM tender_register WHERE tr_id = '$MastId'";
	$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobID = $List->globid;
			$WorkName = $List->work_name;
		}
	}//echo $GlobID;exit;	
	$SelectQuery1 	= "SELECT * FROM bidder_bid_master WHERE globid = '$GlobID' AND is_negotiate = 'Y'";
	//echo $SelectQuery1;exit;
	$SelectQuerySql1 	= mysqli_query($dbConn,$SelectQuery1);
	if($SelectQuerySql1 == true){
		if(mysqli_num_rows($SelectQuerySql1)>0){
			$List1 = mysqli_fetch_object($SelectQuerySql1);
			$NegoRebPerc = $List1->negotiate_rebate_perc;
			$AmtAftReb 	= $List1->quoted_amt_af_neg;
		}
	}

	//echo $NegoRebPerc;
	$BidderRateArr  = array();
	$SelectQuery 	= "SELECT * FROM bidder_bid_details where tr_id = '$MastId' and contid = '$ContractId' order by bdid asc";
	$SelectSql 		= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$RowCount = 1;
		}
	}
}
// if(isset($_POST['back'])){
//      header('Location: NegotiationEntryGenerate.php');
// }
if(isset($_POST['btn_save'])){

	$hid_tr_id			= $_POST["hid_tr_id"];
	$hid_cont_id		= $_POST["hid_cont_id"];
	$ItemNoArr			= $_POST["txt_item_no"];
	$ItemRateArr		= $_POST["txt_nego_rate"];
	$ItemAmountArr		= $_POST["txt_nego_amt"];
	$txt_nego_tot_amt	= $_POST["txt_nego_tot_amt"];
	
	$txt_nego_rebate_perc	= $_POST["txt_nego_rebate_perc"];
	$txt_amt_after_rebate	= $_POST["txt_amt_after_rebate"];
	$Execute = 0;
	if($ItemRateArr == NULL){
		$msg = "Please Enter Negotiation Rate..!!";
	}else if($ItemAmountArr == NULL){
		$msg = "Please Enter Negotiation Amount..!!";
	}else{
		$InQueryCon = 1;
	}
	 
	$GlobID= '';
	$GlobIDQuery = "SELECT globid FROM tender_register WHERE tr_id = '$hid_tr_id'";
	$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobID = $List->globid;
		}
	}
	//$cmb_approve_auth	= $_POST["cmb_approve_auth"];
	if($InQueryCon == 1){
		foreach($ItemRateArr as $ArrKey => $ArrValue){
			$ItemNo			= $ItemNoArr[$ArrKey];
			$NegItemRate 	= $ItemRateArr[$ArrKey];
			$NegItemAmt 	= $ItemAmountArr[$ArrKey];
			//echo $NegItemRate;exit;
			$UpdateQuery 	= "UPDATE bidder_bid_details SET globid='$GlobID', negotiate_rate = '$NegItemRate', negotiate_value = '$NegItemAmt' WHERE tr_id = '$hid_tr_id' AND contid = '$hid_cont_id' AND item_no = '$ItemNo'";
			$UpdateSql 		= mysqli_query($dbConn,$UpdateQuery);
			if($UpdateSql == true){
				$Execute++;
			}
		}
		if($UpdateSql == true){
			if($txt_nego_rebate_perc == ''){
				$rebatepec = 0;
				$rebateamt = 0;
			}else{
				$rebatepec = $txt_nego_rebate_perc;
				$rebateamt = $txt_amt_after_rebate;
			}
			//echo $rebatepec;exit;

			$UpdateQuery1 	= "UPDATE bidder_bid_master SET globid='$GlobID', is_negotiate = 'Y', negotiate_rebate_perc = '$rebatepec', quoted_amt_af_neg = '$rebateamt' WHERE tr_id = '$hid_tr_id' AND contid = '$hid_cont_id'";
			$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1);
			if($UpdateSql1 == true){
				$Execute++;
			}
		}
	}
	if($Execute > 0){
		$msg = "Negotiation Details Saved Successfully";
		$success = 1;
	}else{
		$msg = "Error : Negotiation Details Not Saved. Please Try Again.";
		$success = 0;
	}
	//header('Location: NegotiationViewGenerate.php');
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
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
		max-width : 868px; 
	  	white-space : nowrap;
	  	overflow : hidden;
	  	text-overflow: ellipsis;
	}
	.DispSelectBox{
		border:1px solid #0195D5;
		font-size:11px;
		padding:4px 4px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		width:100%;
		margin-top:2px;
		margin-bottom:2px;
		color:#03447E;
		font-weight:600;
	}
	.dataTable {
        line-height: 16px !important;
        font-weight: 700 !important;
        color: #74048C;
       font-size: 12px;
	   border-collapse: collapse;
       text-shadow: none;
       text-transform: none;
       font-family: Verdana, Arial, Helvetica, sans-serif;
       line-height: 17px;
}
</style>
<script type="text/javascript">
		function goBack()
	{
		url = "NegotiationEntryGenerate.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">  
				<?php include "MainMenu.php"; ?>
                <div class="container_12">  
                    <div class="grid_12" align="center"> 
						<div align="right" class="users-icon-part">&nbsp;</div>
                        <blockquote class="bq1" id="bq1" style="overflow:auto;">
						<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Negotitation Entry</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
															   <div class="div12 dataTable" align="left"><b><input type="text" readonly="" class=" dataTable tboxclass" value="Name Of Work : <?php if(isset($WorkName)){ echo $WorkName; } ?>"></b> </div>
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																		<tr>
																			<th nowrap="nowrap">Item No.</th>
																			<th>Description</th>
																			<th>Qty</th>
																			<th>Unit</th>
																			<th nowrap="nowrap">Rate ( &#8377 )</th>
																			<th nowrap="nowrap">Amount ( &#8377 )</th>
																			<th nowrap="nowrap">Negotiated</br>Rate</br>( &#8377 )</th>
																			<th nowrap="nowrap">Negotiated</br>Amount</br>( &#8377 )</th>
																		</tr>
																	</thead>
																	<tbody>
																	<?php
																	$IndexQty = 0;
																	$TotalAmount = 0;
																	$TotalNegoAmount = 0;
																	if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
																		<tr>
																			<td align="center"><?php echo $List->item_no; ?>
																				<?php if($List->item_qty != 0){ ?>
																					<input type="hidden" name="txt_item_no[]" id="txt_item_no" class="tboxsmclass" value="<?php if($List->item_no != 0){ echo $List->item_no; } ?>" style="width:70px">
																				<?php }else{ ?>
																					&nbsp;
																				<?php } ?>
																			</td>
																			<td align="justify"><?php echo $List->item_desc; ?></td>
																			<td align="right"><?php if($List->item_qty != 0){ echo $List->item_qty; } ?>
																			</td>
																			<td align="center"><?php echo $List->item_unit; ?></td>
																			<td align="right"><?php if($List->item_rate != 0){ echo IndianMoneyFormat($List->item_rate); } ?></td>
																			<td align="right">
																			<?php 
																				$Amount = round(($List->item_qty * $List->item_rate),2);
																				$TotalAmount = $TotalAmount + $Amount;
																				if($Amount != 0){
																					echo IndianMoneyFormat($Amount); 
																				}
																			?>
																			</td>
																			<td align="right">
																				<?php if($List->item_qty != 0){ ?>
																				<input type="text" name="txt_nego_rate[]" id="txt_nego_rate"  onKeyPress="return isNumberWithTwoDecimal(event,this);" class="tboxsmclass negorate" value="<?php if($List->negotiate_rate != 0){ echo $List->negotiate_rate; } ?>" data-index="<?php echo $IndexQty; ?>" data-qty="<?php if($List->item_qty != 0){ echo $List->item_qty; } ?>" style="text-align:right; width:70px">
																				<?php }else{ ?>
																					&nbsp;
																				<?php } ?>
																				<input type="hidden" readonly="" name="txt_qty" id="txt_qty"  class="tboxsmclass itqty" value="<?php if($List->item_qty != 0){ echo $List->item_qty; } ?>" style="width:100px">
																			</td>
																			<td align="right">
																				<?php if($List->item_qty != 0){ ?>
																				<input type="text" readonly="" name="txt_nego_amt[]" id="txt_nego_amt<?php echo $IndexQty; ?>" class="tboxsmclass negoamt" value="<?php if($List->negotiate_value != 0){ echo $List->negotiate_value; } ?>" style="text-align:right; width:100px">
																				<?php }else{ ?>
																					&nbsp;
																				<?php } ?>
																				<?php 
																					$NegAmount = $List->negotiate_value;
																					$TotalNegoAmount = $TotalNegoAmount + $NegAmount;
																				?>
																			</td>
																			<input type="hidden" name="hid_tr_id" id="hid_tr_id" class="tboxsmclass" value="<?php echo $List->tr_id; ?>" style="width:100px">
																			<input type="hidden" name="hid_cont_id" id="hid_cont_id" class="tboxsmclass" value="<?php echo $List->contid; ?>" style="width:100px">
																		</tr>
																	<?php $IndexQty++; } ?> 
																		<tr>
																			<td align="right">&nbsp;</td>
																			<td align="right"><b>TOTAL AMOUNT ( &#8377 ) &nbsp;</b></td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">
																			<?php 
																				$Amount = round($TotalAmount,2);
																				if($TotalAmount != 0){
																					echo '<b>'.IndianMoneyFormat($TotalAmount).'</b>'; 
																				}
																			?>
																			</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">
																				<input type="text" readonly="" name="txt_nego_tot_amt" id="txt_nego_tot_amt" class="tboxsmclass" value="<?php if($TotalNegoAmount != 0){ echo $TotalNegoAmount; }?>" style="text-align:right; width:100px">
																			</td>
																		</tr>
																		<tr>
																			<td align="right">&nbsp;</td>
																			<td align="right"><b>REBATE ( % ) &nbsp;</b></td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">
																				<input type="text" name="txt_nego_rebate_perc" onKeyPress="return isPercentageValue(event,this);" id="txt_nego_rebate_perc" class="tboxsmclass" value="<?php if($NegoRebPerc != 0){ echo $NegoRebPerc; }?>" style="text-align:right; width:100px">
																			</td>
																		</tr>
																		<tr>
																			<td align="right">&nbsp;</td>
																			<td align="right"><b>TOTAL AMOUNT AFTER REBATE ( &#8377 ) &nbsp;</b></td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">
																				<input type="text" readonly="" name="txt_amt_after_rebate" id="txt_amt_after_rebate" class="tboxsmclass" value="<?php if($AmtAftReb != 0){ echo $AmtAftReb; }?>" style="text-align:right; width:100px">
																			</td>
																		</tr>
																	<?php } ?>
																	</tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- <div class="div1">&nbsp;</div> -->
					 				</div>
				                	</div>		
									<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
										<div class="buttonsection">
										  <input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<!--<div class="buttonsection">
											<input type="button" name="exportToExcel" id="exportToExcel" value="Export To Excel" class="btn btn-info">
										</div>-->
										<div class="buttonsection">
									      <input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
								       </div>
									</div>
					             </div>
				                <div align="center">&nbsp;</div>
				              </blockquote>
				        </div>
				  </div>
			</div>
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
	<script>
		$(document).ready(function(){ 
			$("#exportToExcel").click(function(e){ 
				var table = $('body').find('.table2excel');
				if(table.length){ 
					$(table).table2excel({
						exclude: ".xlTable",
						name: "SOQ",
						filename: "PriceBid -" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
						fileext: ".xls",
						exclude_img: true,
						exclude_links: true,
						exclude_inputs: true
						//preserveColors: preserveColors
					});
				}
			});
		});				
		//FindTotalAmountNew();
		function FindTotalAmountNew(){  
				var TotalAmt = 0;
				$(".negoamt").each(function(){ 
					var Amt = $(this).val();
					//alert(Amt);
					if(Amt!= ''){
						TotalAmt = Number(TotalAmt)+Number(Amt);
						$('#txt_nego_tot_amt').val(TotalAmt);
						var NegRebPerc = $('#txt_nego_rebate_perc').val();
						RebAmtCalc = Number(TotalAmt) * Number(NegRebPerc) / 100; 
						RebAmt = Number(TotalAmt) - Number(RebAmtCalc);

						$('#txt_amt_after_rebate').val(RebAmt);
					}
				});
		}
		
		$(".negorate").change(function(){
			var NegRate = $(this).val();
			var qty 	= $(this).attr("data-qty");
			var RowID 	= $(this).attr("data-index"); 
			//var qty = $(".itqty").val();
			var TotalAmt = 0;
			var Amt = 0;
			$(".negorate").each(function(){
				var Amt = Number(qty)*Number(NegRate);
				$("#txt_nego_amt"+RowID).val(Amt); 
				//alert(Amt);
				FindTotalAmountNew();
			});
			
		});
		var KillEvent = 0;
		$(document).ready(function(){ 
			$("body").on("click","#btn_save", function(event){
				if(KillEvent == 0){
					var TrId 	= $("#txt_TrId").val();
					///var BidderId 	= $("#txt_bidderid").val();
					var RebatePerc 	= $("#txt_nego_rebate_perc").val();
					if(TrId == ""){
						BootstrapDialog.alert("Invalid Work. Unable to Save.");
						event.preventDefault();
						event.returnValue = false;
					}else{
						event.preventDefault();
						BootstrapDialog.confirm('Are you sure want to confirm with '+RebatePerc+' % Rebate ?', function(result){
							if(result) {
								KillEvent = 1;
								$("#btn_save").trigger( "click" );
							}
						});
					}
				}
			});
		});
		$("#txt_nego_rebate_perc").change(function(){
			var NegRebPerc 	= $(this).val();
			var NegTotal	= $("#txt_nego_tot_amt").val();		//alert(NegRebPerc); alert(NegTotal);
			//var qty = $(".itqty").val();
			var RebCal 		= (Number(NegRebPerc) / 100) * Number(NegTotal);	
			var RebTotal 	= Number(NegTotal) - Number(RebCal);	//alert(RebTotal);
			$("#txt_amt_after_rebate").val(RebTotal);
		});

	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			BootstrapDialog.alert(msg);
		}
	};
	</script>
</html>
<style>
.table1 td{
	background:#fff;
}
</style>
