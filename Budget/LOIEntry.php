<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'LOI Entry';
checkUser();
$GlobGr1Id = 2; $GlobGr2Id = 3;
//include "DefaultMaster.php";
$msg = ""; $del = 0;  $InQueryCon =0;
$staffid = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];
//echo $List3;exit;
$EditTR ='';
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

if(isset($_POST['btn_save']) == " Save "){

	$CmbTenderNo = $_POST["cmb_tnder_no"];
	$WorkName	 = $_POST["txt_work_name"];
	$BidderId	 = $_POST["cmb_bidder"];
	$PgPer	    = $_POST["txt_pg_per"];
	$Pgamt	    = $_POST["txt_pg_value"];
	$LoiNo		 = 	trim($_POST["txt_loi_no"]);
	$LoiDate	 	 = dt_format($_POST["txt_loi_date"]);
	// $PgType		 = $_POST["cmb_pg_type"];
	// $BankName	 = $_POST["txt_bank_name"];
	// $SerialNo	 = $_POST["txt_serial_no"];
	// $PgAmount	 = trim($_POST["txt_pg_amt"]);
	// $BgDate		 = $_POST["txt_bg_date"];
	// $ExpiryDate	 = $_POST["txt_exp_date"];

	if($CmbTenderNo == NULL){
		$msg = "Please Select Tender Number..!!";
	}else if($WorkName == NULL){
		$msg = "Please Enter Work Name..!!";
	}else if($BidderId == NULL){
		$msg = "Please Select Contractor Name..!!";
	}else if($LoiNo == NULL){
		$msg = "Please Enter LOI Number..!!";
	}else if($LoiDate == NULL){
		$msg = "Please Select LOI Date..!!";
	}else{
		$InQueryCon = 1;
	}
	//echo $LoiDate;exit;
	$TSID = '';
	$SelectTSQuery = "SELECT ts_id FROM tender_register WHERE tr_id = '$CmbTenderNo'";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			$CList = mysqli_fetch_object($SelectTSSql);
			$TSID = $CList->ts_id;
		}
	}
	$GlobID= '';
	$SelectTSQuery = "SELECT globid FROM tender_register WHERE tr_id = '$CmbTenderNo'";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			$CList = mysqli_fetch_object($SelectTSSql);
			$GlobID = $CList->globid;
		}
	}
	$LOIID= '';
	$SelectTSQuery = "SELECT loa_pg_id FROM loi_entry WHERE tr_id = '$CmbTenderNo'";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			$CList = mysqli_fetch_object($SelectTSSql);
			$LOIID = $CList->loa_pg_id;
		}
	}

	//echo $ExpiryDate;exit;
	if($InQueryCon == 1){
		 if(($LOIID !=null)||($LOIPGID !=null)){

			$updateloi_query	= "UPDATE loi_entry SET ts_id='$TSID', tr_id='$CmbTenderNo', globid='$GlobID', contid='$BidderId',loa_no='$LoiNo',
			pg_per='$PgPer',  pg_amt='$Pgamt', loa_dt='$LoiDate', userid = '$UserId', createddate = NOW() WHERE tr_id='$CmbTenderNo'";
			$insert_sql = mysqli_query($dbConn,$updateloi_query);
	
			$updateWorkQuery	= "UPDATE works SET work_status='LOI', contid='$BidderId', loa_no='$LoiNo', loa_dt='$LoiDate'WHERE globid='$GlobID'";
			$InsertWorkQuerySql = mysqli_query($dbConn,$updateWorkQuery);
	
			$TrRegInsertquery	= "UPDATE tender_register SET loi_issue='Y' WHERE tr_id='$CmbTenderNo'";
			$TrRegInsertquerysql = mysqli_query($dbConn,$TrRegInsertquery);
			if($insert_sql == true){
				$msg = "LOI Entry Updated Successfully..!!";
				UpdateWorkTransaction($GlobID,0,0,"W","LOI Entry Updated by ".$UserId."","");
			}else{
				$msg = "LOI Entry is not Updated..!!";
				UpdateWorkTransaction($GlobID,0,0,"W","LOI Entry Tried to Update by ".$UserId." but not Updated","");
			}
		 }else{

			$insert_query	= "INSERT INTO loi_entry SET ts_id='$TSID', tr_id='$CmbTenderNo', globid='$GlobID', contid='$BidderId',loa_no='$LoiNo',
			pg_per='$PgPer',  pg_amt='$Pgamt', loa_dt='$LoiDate', userid = '$UserId', createddate = NOW()";
			$insert_sql = mysqli_query($dbConn,$insert_query);
			$LastInsertloiid = mysqli_insert_id($dbConn);
	
			$InsertWorkQuery	= "UPDATE works SET work_status='LOI', contid='$BidderId', loa_no='$LoiNo', loa_dt='$LoiDate'WHERE globid='$GlobID'";
			$InsertWorkQuerySql = mysqli_query($dbConn,$InsertWorkQuery);
	
			$TrRegInsertquery	= "UPDATE tender_register SET loi_issue='Y' WHERE tr_id='$CmbTenderNo'";
			$TrRegInsertquerysql = mysqli_query($dbConn,$TrRegInsertquery);
			if($insert_sql == true){
				$msg = "LOI Entry Saved Successfully..!!";
				UpdateWorkTransaction($GlobID,0,0,"W","LOI Entry Created by ".$UserId."","");
			}else{
				$msg = "LOI Entry is not Saved..!!";
				UpdateWorkTransaction($GlobID,0,0,"W","LOI Entry Tried to Create by ".$UserId." but not Created","");
			}
		 }
	}
}
if(isset($_GET['id'])){   
	$LOIPGID 	 = $_GET['id'];
	$ContArr  	 =  array();
	$ContNameArr = array();
	$GlobID= '';
	$result = "SELECT a.*,  b.tr_no, b.work_name, c.name_contractor FROM loi_entry a 
			INNER JOIN tender_register b ON (a.tr_id = b.tr_id) 
			INNER JOIN contractor c ON (a.contid = c.contid)where a.loa_pg_id = '$LOIPGID'";
		$GlobIDSql 	= mysqli_query($dbConn,$result);
		if($GlobIDSql == true){
			if(mysqli_num_rows($GlobIDSql)>0){
				$List = mysqli_fetch_object($GlobIDSql);
				$GlobID     = $List->globid;
				$TrId       = $List->tr_id;
				$EditTR     = $List->tr_id;
				$WorkName   = $List->work_name;
				$ContId     = $List->contid;
				$Contname    = $List->name_contractor;
				$LoiNum     = $List->loa_no;
				$LoiDat     = dt_display($List->loa_dt);
				$pgpaer     = $List->pg_per;
				$PGVal      =round(($List->pg_amt),0);
				//$contid
			}
		  }
	}
	   
	
		$RowCount =0;
		$FinaQuery = "SELECT tr_id FROM sheet WHERE tr_id = '$TrId' AND tr_id IS NOT NULL AND tr_id !=0 ";
		$FinaResult = mysqli_query($dbConn,$FinaQuery);
		if($FinaResult == true){
			if(mysqli_num_rows($FinaResult)>0){
				$RowCount = 1; 
			}
	   }
		$SelectQuery1 = "select * from bidder_bid_master where tr_id = '$TrId' and contid='$ContId'";
		$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
		if($SelectSql1 == true){
			if(mysqli_num_rows($SelectSql1)>0){
				while($List = mysqli_fetch_object($SelectSql1)){
					$ContId = $List->contid;
					$IsNego = $List->is_negotiate;
					$QuoteAmt = $List->quoted_amt;
					$RebatePerc = $List->rebate_perc;
					$NegoRebatePerc = $List->negotiate_rebate_perc;
					/* comment on 27-10-2023
					$RebateAmt = $QuoteAmt * $RebatePerc / 100;
					$TotalQuote = round(($QuoteAmt - $RebateAmt),0);
					$NegRebateAmt = $TotalQuote * $NegoRebatePerc / 100;
					$TotalamtafterNeg= round(($TotalQuote - $NegRebateAmt),0);
					if($IsNego == 'Y'){
						$QuoteAmtArr=  $TotalamtafterNeg;
					}else{
						$QuoteAmtArr=  $TotalQuote;
					}	
					$QuoteAmtEch = $QuoteAmtArr[$TrIdVal][$ContIdVal];	
					*/
					if($IsNego == 'Y'){
						$QuoteAmtArr=  $List->quoted_amt_af_neg;
					}else{
						$QuoteAmtArr=  $List->quoted_amt_af_reb;
					}	
					
				}
			}
		}
		$TSID = '';
		$SelectTSQuery = "SELECT ts_id FROM tender_register WHERE tr_id = '$TrId'";
		$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
		if($SelectTSSql == true){
			if(mysqli_num_rows($SelectTSSql)>0){
				$CList = mysqli_fetch_object($SelectTSSql);
				$TSID =$CList->ts_id;
			}
		}
		$TSdate= '';
		$SelectTSQuery = "SELECT * FROM technical_sanction WHERE ts_id = '$TSID'";
	
	
		$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
		if($SelectTSSql == true){
			if(mysqli_num_rows($SelectTSSql)>0){
				while($List = mysqli_fetch_object($SelectTSSql)){
				$TSdate         = $List->ts_date;
				$TSdate1        =dt_display($TSdate );
			
	
			}
		}
	}

   


?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	window.history.forward();
	function noBack() { window.history.forward(); }

	function ViewNITList(){
		url = "LOIViewEdit.php";
		window.location.replace(url);
	}
	function goBack(){
		url = "Home.php";
		window.location.replace(url);
	}
	function ViewLOIPrint(){
		url = "LOIPrintForm.php";
		window.location.replace(url);
	}
</script>	
<style>
	.tclass {
		border: -1px;
		width :30px;
		text-align: center;
		color: blue;
	}
</style>

	<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
		<!--==============================header=================================-->
		<form action="" method="post" enctype="multipart/form-data" name="form" id="form1">
			<?php include "Menu.php"; ?>
			<!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
								<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">LOI - Entry</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
																<div class="row">
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div2 lboxlabel">
																			Tender No.
																		</div>
																		<div class="div7">
																			<select id="cmb_tnder_no" name="cmb_tnder_no" class="tboxclass">
																				<option value="">--------------- Select --------------- </option>
																				<?php echo $objBind->BindLOITrNo($TrId);?>
																			</select>
																		</div>
																		<?php
																		if($RowCount==1){
																		?>
																		<div class="div3 lboxlabel " id="complete">
																			&emsp;<i class="fa fa-check-circle-o" style="font-size:20px; color:#EA253C;"></i> <span style="color:EA253C; top:-4px; position:relative;">Work Order Issued</span>
																		</div>
																		<?php
																		}else{}
																		?>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div2 lboxlabel">
																			Name of Work
																		</div>
																		<div class="div10">
																			<textarea name='txt_work_name' id='txt_work_name' class="tboxclass" readonly=""><?php if(isset($_GET['id'])!= ""){ echo $WorkName; } ?></textarea>
																		</div>
																		<input type="hidden" name='txt_ts_date'  id='txt_ts_date' readonly="" value="<?php if(isset($_GET['id'])!= "") { echo $TSdate1; } ?>">
																		<input type="hidden" name='txt_loi_pgid'  id='txt_loi_pgid' readonly="" value="<?php if(isset($_GET['id'])!= ""){ echo $LOIPGID; } ?>">
																		
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div2 lboxlabel">
																			Bidder Name
																		</div>
																		<div class="div4">
																			<select id="cmb_bidder" name="cmb_bidder" class="tboxclass" value="">
																				<option value="">--------------- Select --------------- </option>
																				<?php if($_GET['id'] != ""){ echo '<option value="'.$ContId.'" selected="selected"> '. $Contname.' </option>'; } ?>
																			</select>
																		</div>
																		<div class="div3 lboxlabel">&emsp;&emsp;Quoted  Amount ( &#8377; )</div>
																		<div class="div3">
																		
																			<input type="text" readonly name="txt_quote_amt_format" id="txt_quote_amt_format" class="tboxclass" value="<?php if(isset($_GET['id'])!= ""){ echo IndianMoneyFormat($QuoteAmtArr); } ?>">
																			<input type="hidden" readonly name="txt_quote_amt" id="txt_quote_amt" class="tboxclass" value="<?php if(isset($_GET['id'])!= ""){ echo $QuoteAmtArr; } ?>">
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																	<div class="div2 lboxlabel">PG ( % )</div>
																		<div class="div4" align="left">
																				<input type="text" readonly name="txt_pg_per" id="txt_pg_per" class="tboxclass " value="<?php if(isset($_GET['id'])!= ""){ echo $pgpaer; } ?>"> 
																		</div>
																		<div class="div3 lboxlabel">&emsp;&emsp;PG Value  ( &#8377; )</div>
																		<div class="div3" align="left">
																			<input type="text" name="txt_pg_value_format" id="txt_pg_value_format" readonly class="tboxclass" value="<?php if(isset($_GET['id'])!= ""){ echo IndianMoneyFormat($PGVal); } ?>">
																			<input type="hidden" name="txt_pg_value" id="txt_pg_value" readonly class="tboxclass" value="<?php if(isset($_GET['id'])!= ""){ echo $PGVal; } ?>">
																		</div>
																	</div>
																	
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div2 lboxlabel">LOI No.</div>
																		<div class="div4" align="left">
																			<input type="text" name="txt_loi_no" maxlength="150" id="txt_loi_no" class="tboxclass" value="<?php if(isset($_GET['id'])!= ""){ echo $LoiNum; } ?>">
																		</div>
																		<div class="div3 lboxlabel">&emsp;&emsp;LOI Date</div>
																		<div class="div3"  align="left">
																			<input type="text" placeholder="DD/MM/YYYY" readonly="" class="tboxclass expdate" name="txt_loi_date" id="txt_loi_date" class="tboxsmclass" value="<?php if(isset($_GET['id'])!= ""){ echo $LoiDat; } ?>">
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																		<!-- <div class="row">
																			<div class="div3 lboxlabel">PG Type</div>
																			<div class="div3" align="left">
																				<select id="cmb_pg_type" name="cmb_pg_type" class="tboxclass" style="width:205px">
																					<option value="">---- Select ---- </option>
																					<option value="BG">Bank Guarantee</option>
																					<option value="FDR">Fixed Deposit Receipt</option>
																				</select>
																			</div>
																			<div class="div3 lboxlabel">&emsp;Bank Name</div>
																			<div class="div3" align="left">
																				<input type="text" name="txt_bank_name" id="txt_bank_name" class="tboxsmclass">
																			</div>
																		</div>

																		<div class="row clearrow"></div>
																		<div class="row">
																			<div class="div3 lboxlabel">BG/FDR Serial No.</div>
																			<div class="div3" align="left">
																				<input type="text" name="txt_serial_no" id="txt_serial_no" class="tboxsmclass">
																			</div>
																			<div class="div3 lboxlabel">&emsp;PG Amount</div>
																			<div class="div3" align="left">
																				<input type="text" name="txt_pg_amt" id="txt_pg_amt" class="tboxsmclass">
																			</div>
																		</div>
																		
																		<div class="row clearrow"></div>
																		<div class="row">

																			<div class="div3 lboxlabel">BG/FDR Date</div>
																			<div class="div3" align="left">
																				<input type="date" name="txt_bg_date" id="txt_bg_date" class="tboxsmclass">
																			</div>

																			<div class="div3 lboxlabel">&emsp;Expiry Date</div>
																			<div class="div3" align="left">
																				<input type="date" name="txt_exp_date" id="txt_exp_date" class="tboxsmclass">
																			</div>											
																		</div>

																		<div class="row clearrow"></div>
																		<div class="row">
																			<div class="div3 lboxlabel">Authorized Signature</div>
																			<div class="div3" align="left">
																				<select id="cmb_auth_sign" name="cmb_auth_sign" class="tboxclass" style="width:205px">
																					<option value="">---- Select ---- </option>
																					<option value="1">Chief Engineer,CEG</option>
																				</select>
																			</div> -->
																			<!--<div class="div3 lboxlabel">&emsp;Bank Name</div>
																			<div class="div3" align="left">
																				<input type="text" name="txt_bank_name" id="txt_bank_name" class="tboxsmclass">
																			</div>-->
																	<div class="row clearrow"></div>
																	<div class="row">
																	    <div class="div12" align="center">
																		    <input type="button" class="btn btn-info" name="Back" id="Back" value="Back" onClick="goBack();"/>
																			<?php
																			if($RowCount==1){
																			?>
 																		   <?php
																			}else if(($RowCount==0)&&($_GET['id'] != "")){
																		   ?>
																			<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Update " />
																			<?php
																			}else{
																			?>
																			<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Save " />
																			<?php
																			}
																			?>
																		    <!-- <input type="button" class="btn btn-info" name="btn_view" id="btn_view" value="View" onClick="ViewNITList();"/> -->
 																			<!-- <input type="button" name="btn_pr_view" id="btn_pr_view" class="btn btn-info" onClick="ViewLOIPrint();" value=" Print View "> -->
																		</div>
																	</div>
																	<div class="row clearrow"></div>


																</div>
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
			<!--==============================footer=================================-->
			<?php   include "footer/footer.html"; ?>
			<script src="js/jquery.hoverdir.js"></script>
		</form>
	</body>
</html>
<script>
	$("#cmb_tnder_no").chosen();
	$("#cmb_pg_type").chosen();
	$("#cmb_bidder").chosen();
	$("#cmb_auth_sign").chosen();
	var KillEvent = 0;	
$("body").on("click","#btn_save", function(event){
	if(KillEvent == 0){
		var CheckVal = 0;
		var TsNumberVal	= $("#cmb_tnder_no").val();
		var TrNumberVal = $("#cmb_bidder").val();
		var TrLOINum	= $("#txt_loi_no").val();
		var TrLOIDATE	= $("#txt_loi_date").val();
		var TechDate    = $("#txt_ts_date").val();
		if((TrLOIDATE != "") && (TechDate != "") ){  
			var d1 = TechDate.split("/");
			var d2 = TrLOIDATE.split("/");
			var  NewTech  = new Date(d1[2], d1[1]-1, d1[0]); //alert(emdexpdate);
			var NewLOIdate= new Date(d2[2], d2[1]-1, d2[0]); //alert(emddate);
			if(NewTech>NewLOIdate){
				event.preventDefault();
				event.returnValue = false;
				CheckVal = 1;
			}else{
				var a="";
				CheckVal = 0;
			}
		}
		
		if(TsNumberVal == ""){
			BootstrapDialog.alert("Please Select Tender Number..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrNumberVal == ""){
			BootstrapDialog.alert("Bidder Name Should Not Be Empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrLOINum == ""){
			BootstrapDialog.alert("Please Enter LOI Number...!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrLOIDATE == ""){
			BootstrapDialog.alert("Please Enter LOI Date..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(CheckVal ==  1){
			BootstrapDialog.alert("LOI Date is lesser than Technical Sanction Date..Please Change..!!");
			return false;
	
		}else{
			event.preventDefault();
			BootstrapDialog.confirm({
				title: 'Confirmation Message',
				message: 'Are you sure want to save this LOI ?',
				closable: false, // <-- Default value is false
				draggable: false, // <-- Default value is false
				btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
				btnOKLabel: 'Ok', // <-- Default value is 'OK',
				callback: function(result) {
					if(result){
						KillEvent = 1;
						$("#btn_save").trigger( "click" );
					}else {
						KillEvent = 0;
					}
				}
			});
		}
	}
});

	$(document).ready(function(){ 
		// $("body").on("click","#View", function(event){
		// 	var ShortName 	= $("#cmb_tnder_no").val();
		// 	var WorkOrderNo = $("#txt_work_name").val();
		// 	var BidderName 	= $("#cmb_bidder").val();
		// 	if(ShortName.trim() == ""){
		// 		BootstrapDialog.alert("Please select work short name");
		// 		event.preventDefault();
		// 		event.returnValue = false;
		// 	}else if(WorkOrderNo.trim() == ""){
		// 		BootstrapDialog.alert("Tender no. should not be empty");
		// 		event.preventDefault();
		// 		event.returnValue = false;
		// 	}else if(BidderName == ""){
		// 		BootstrapDialog.alert("Bidder name should not be empty");
		// 		event.preventDefault();
		// 		event.returnValue = false;
		// 	}
		// });
		$("body").on("change","#cmb_tnder_no", function(event){
			var MastId = $(this).val();
			var Id = $(this).val();
			var TrId = $(this).val();
			
			$("#cmb_bidder").val('');
			$("#txt_pg_amt").val('');
			$("#txt_pg_value").val('');
			$("#txt_work_name").val('');
			$("#txt_pg_per").val('');
			$("#txt_quote_amt").val('');
			$("#txt_pg_value").val('');
			$("#txt_loi_no").val('');
			$("#txt_loi_date").val('');
			$("#txt_loi_pgid").val('');
			$.ajax({ 
				type: 'POST', 
				url: 'FindEstTsTrName.php', 
				data: { Id: Id, Page: 'TR'}, 
				dataType: 'json',
				success: function (data) {  
					if(data != null){ 
						$("#txt_work_name").val(data.work_name);
					}
				}
			});
			$.ajax({ 
				type: 'POST', 
				url: 'FindTechnicalsanctiondate.php', 
				data: { Id: Id}, 
				dataType: 'json',
				success: function (data) {  
					if(data != null){ 
						$("#txt_ts_date").val(data.ts_date);
					}
				}
			});
			$("#cmb_bidder").chosen('destroy'); 
			$('#cmb_bidder').children('option:not(:first)').remove();
			if(MastId != ""){
				$.ajax({ 
					type: 'POST', 
					url: 'FindBiddersName.php', 
					data: { MastId: MastId }, 
					dataType: 'json',
					success: function (data) { 
						var ContArr      = data['ContArr']; 
						var ContL1       = data['ContL1']; 
						if(data != null){ 
							$('#cmb_bidder').chosen('destroy');
							$.each(ContArr, function(index, value) {
							if(ContL1 != value.contid){
								$("#cmb_bidder").append('<option disabled="disabled" value="'+value.contid+'">'+value.contname+'</option>');
							}else{
								$("#cmb_bidder").append('<option value="'+value.contid+'">'+value.contname+'</option>');
							}
						});
							
							//$("#cmb_bidder").val(ContL1); 
							$("#cmb_bidder").find("option[value="+ContL1+"]").prop("selected", "selected");
							$("#cmb_bidder").chosen();
							//$("#txt_quote_amt").val(QuotedAmt); 
							var BidderId = $("#cmb_bidder").val(); //alert(BidderId);

							$.ajax({ 
								type: 'POST', 
								url: 'FindEmdLoiValue.php', 
								data: { BidderId: BidderId, TrId: TrId, Page: 'LOI'}, 
								dataType: 'json',
								success: function (data) {  
									if(data != null){ 
										var BidAmt =  data['bid_amt'];
										var PgAmt =  data['pg_amt'];
										var BidAmtFrmt = (BidAmt).toLocaleString('en-IN');
										var PgAmtFrmt = (PgAmt).toLocaleString('en-IN');
										$("#txt_quote_amt_format").val(BidAmtFrmt);
										$("#txt_quote_amt").val(BidAmt);
										$("#txt_pg_value_format").val(PgAmtFrmt);
										$("#txt_pg_value").val(PgAmt);
										$("#txt_pg_per").val(data['pg_per']); 
						      }
					        }
			             });

						 }
					}
				});
			}			
		});
	});
	$( ".date" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		defaultDate: new Date,
	});
	$( ".expdate" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		defaultDate: new Date,
	});
</script>
<script>
	var msg = "<?php echo $msg; ?>";
    document.querySelector('#top').onload = function(){
	if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						dialog.close();
						window.location.replace('LOIEntry.php');
					}
				}]
			});
		}
};
</script>
</script>