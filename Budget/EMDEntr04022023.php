<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require('php-excel-reader/excel_reader2.php');
require_once 'library/binddata.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'EMD Entry';
checkUser();
$msg = ''; $success = '';
$userid = $_SESSION['userid'];
$staffid  = $_SESSION['sid'];
$InQueryCon =0;
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

if(isset($_POST['submit']) == " Save "){

	$TenderNum 		= $_POST["cmb_tnder_no"];
	$WorkName	 	= $_POST["txt_work_name"];
	$TrId   	 		= $_POST["txt_tender_id"];
	$EMdmasid	 	= $_POST["txt_emdmas_id"];
	$TotEmdAmount	= trim($_POST["txt_full_emd_amt"]);
	$EmdContstr    = $_POST["cmd_contid"];
	$Emdinstypestr	= $_POST["cmd_instype"];
	$Emdinstnumstr	= $_POST["instrunum"];
	$Emdbnamestr	= $_POST["txt_bankname_pg"]; 
	$Emdbaddstr	 	= $_POST["txt_sno_pg"];	
	$Emddatestr		= $_POST["txt_date_pg"];
	$Emdexdatestr	= $_POST["txt_expir_date_pg"];
	$AmountListstr	= $_POST["txt_part_amt"]; 
	$DDContstr  	= $_POST["cmd_contid_DD"];
	//$DDinstypestr	        = $_POST["cmd_instype"];
	$DDinstnumstr	= $_POST["instrunum_DD"];
	$DDbnamestr    = $_POST["txt_bankname_pg_DD"]; 
	$DDbaddstr	 	= $_POST["txt_sno_pg_DD"];	
	$DDdatestr		= $_POST["txt_date_pg_DD"];
	$DDexdatestr	= $_POST["txt_expir_date_pg_DD"];
	$DDAmountListstr = $_POST["txt_part_amt_DD"]; 
	$DDCallannostr	  = $_POST["txt_challNo_pg_DD"];
	$DDCallandatstr  = $_POST["txt_challandate_pg_DD"]; 
	$DDrealDatstr	  = $_POST["txt_Challanrealdate_pg_DD"];	
	$DDDraweBankstr  = $_POST["txt_draweebank_DD"];
 
	if($TenderNum == null){
		$msg = 'Error : Tender Number should not be empty..!!!';
	// }else if($EmdContstr == null){
	// 	$msg = 'Error :  Please Add Atleast Bidder..!!!';
	// }else if($TotEmdAmount == null){
	// 	$msg = 'Error : Please Enter EMD Amount..!!!';
 	}else if($Emdinstnumstr == NUll){
		//else if (count($request->input('txt_bank_accno')) <= 0 )
	 	$msg = 'Error : Please Add Atleast One Type';
	}else if(count($Emdinstnumstr) == 0 ){
		//else if (count($request->input('txt_bank_accno')) <= 0 )
	 	$msg = 'Error : Please Add Atleast One Type';
	}else{
		$InQueryCon = 1;
	}

	$GlobID= '';
	$GlobIdQuery ="SELECT globid FROM tender_register where tr_id = '$TenderNum'";
	$GlobIdSql 	= mysqli_query($dbConn,$GlobIdQuery);
	if($GlobIdSql == true){
		if(mysqli_num_rows($GlobIdSql)>0){
			$List = mysqli_fetch_object($GlobIdSql);
			$GlobID = $List->globid;
		}
	}

	if(($TrId != null)&&($EMdmasid != null)){
		$update_query	= "UPDATE works SET work_status='EMD' WHERE globid = '$GlobID'";
		$update_query_sql = mysqli_query($dbConn,$update_query);
		$upate_query1	= "UPDATE emd_master set tr_id='$TenderNum', emd_lot_amt='$TotEmdAmount',
		active='1', created_by = '$staffid', created_on = NOW() WHERE emid = '$EMdmasid'";
		$insert_sql = mysqli_query($dbConn,$upate_query1);
		$Deletequery = "DELETE FROM emd_detail WHERE emid='$EMdmasid'";

		$BFDeletequery = mysqli_query($dbConn,$Deletequery);	
		foreach($Emdinstnumstr as $Key => $Value){
			$EmdCont     	= $EmdContstr[$Key];
			$Emdinstype    	= $Emdinstypestr[$Key];
			$Emdinstnum    	= $Emdinstnumstr[$Key];
			$Emdbname      	= $Emdbnamestr[$Key];
			$Emdbadd       	= $Emdbaddstr[$Key];
			$Emddate       	= $Emddatestr[$Key];
			$Emdexdate     	= $Emdexdatestr[$Key];
			$AmountList     = $AmountListstr[$Key];

			$TrimBankname 	= trim($Emdbname);
			$TrimBranc 	 = trim($Emdbadd);
			$TrimInstnum 	= trim($Emdinstnum);
			$TrimAmount 	= trim($AmountList);
			$Insertdate 	= dt_format($Emddate);
			$InsertExpdate 	= dt_format($Emdexdate);

			$insert_query1	= "insert into emd_detail set emid='$EMdmasid', contid='$EmdCont', inst_type='$Emdinstype',inst_no='$TrimInstnum', bank_name='$TrimBankname',
			branch_addr='$TrimBranc', issue_dt='$Insertdate', valid_dt='$InsertExpdate', emd_amt='$TrimAmount', active='1'";
			$insert_sql1 = mysqli_query($dbConn,$insert_query1);
		}
		foreach($DDinstnumstr as $Key => $Value){
			$DDCont     	= $DDContstr[$Key];
			//$Emdinstype   = $Emdinstypestr[$Key];
			$DDinstnum    	= $DDinstnumstr[$Key];
			$DDbname      	= $DDbnamestr[$Key];
			$DDbadd       	= $DDbaddstr[$Key];
			$DDdate       	= $DDdatestr[$Key];
			$DDexdate     	= $DDexdatestr[$Key];
			$AmountList     = $DDAmountListstr[$Key];
			$DDChallannum   = $DDCallannostr[$Key];
			$DDChalldate    = $DDCallandatstr[$Key];
			$DDChallRealate = $DDrealDatstr[$Key];
			$DDDrawbname    = $DDDraweBankstr[$Key];
			
			$TrimBankname 	= trim($Emdbname);
			$TrimBranc 	= trim($DDbname);
			$TrimInstnum 	= trim($DDinstnum);
			$TrimAmount 	= trim($AmountList);
			$Insertdate 	= dt_format($DDdate);
			$InsertExpdate 	= dt_format($DDexdate);
			
			$TrimDrawBankname   = trim($DDDrawbname);
			$TrimChallnum 	    = trim($DDChallannum);
			$Challandate 	    = dt_format($DDChalldate);
			$Realdate 	    = dt_format($DDChallRealate);

			$insert_query1	= "insert into emd_detail set emid='$EMdmasid', contid='$DDCont', inst_type='DD',inst_no='$TrimInstnum', bank_name='$TrimBankname',
			branch_addr='$TrimBranc', issue_dt='$Insertdate', valid_dt='$InsertExpdate', emd_amt='$TrimAmount',  ga_challan_no='$TrimChallnum', ga_challan_date='$Challandate', ga_realisation_date='$Realdate', 
			ga_drawee_bank='$TrimDrawBankname', active='1'";
			$insert_sql1 = mysqli_query($dbConn,$insert_query1);

			if($insert_sql1 == true){
						$msg = "EMD Entry Updated Successfully ..!!";
						UpdateWorkTransaction($GlobInTsID,0,0,"W","EMD details Updated by ".$UserId."","");
			}else{
					$msg = "Error: EMD Entry Not Updated..!!";
					UpdateWorkTransaction($GlobInTsID,0,0,"W","EMD details Tried to Update by ".$UserId." but not Updated","");
			}
		}
	}else{ 

					
		$update_query	= "UPDATE works SET  work_status='EMD' WHERE globid = '$GlobID'";
		$update_query_sql = mysqli_query($dbConn,$update_query);

		$insert_query	= "insert into emd_master set tr_id='$TenderNum', globid ='$GlobID', emd_lot_amt='$TotEmdAmount',
		active='1', created_by = '$staffid', created_on = NOW()";
		$insert_sql = mysqli_query($dbConn,$insert_query);
		$LastInsertid = mysqli_insert_id($dbConn);

		foreach($Emdinstnumstr as $Key => $Value){
			$EmdCont     	= $EmdContstr[$Key];
			$Emdinstype    	= $Emdinstypestr[$Key];
			$Emdinstnum    	= $Emdinstnumstr[$Key];
			$Emdbname      	= $Emdbnamestr[$Key];
			$Emdbadd       	= $Emdbaddstr[$Key];
			$Emddate       	= $Emddatestr[$Key];
			$Emdexdate     	= $Emdexdatestr[$Key];
			$AmountList     = $AmountListstr[$Key];
		
			$TrimBankname 	= trim($Emdbname);
			$TrimBranc 	 = trim($Emdbadd);
			$TrimInstnum 	= trim($Emdinstnum);
			$TrimAmount 	= trim($AmountList);
			$Insertdate 	= dt_format($Emddate);
			$InsertExpdate 	= dt_format($Emdexdate);

			$insert_query1	= "insert into emd_detail set emid='$LastInsertid', contid='$EmdCont', inst_type='$Emdinstype',inst_no='$TrimInstnum', bank_name='$TrimBankname',
			branch_addr='$TrimBranc', issue_dt='$Insertdate', valid_dt='$InsertExpdate', emd_amt='$TrimAmount', active='1'";
			$insert_sql1 = mysqli_query($dbConn,$insert_query1);
		}
		foreach($DDinstnumstr as $Key => $Value){
			$DDCont     	= $DDContstr[$Key];
			//$Emdinstype    	= $Emdinstypestr[$Key];
			$DDinstnum    	= $DDinstnumstr[$Key];
			$DDbname      	= $DDbnamestr[$Key];
			$DDbadd       	= $DDbaddstr[$Key];
			$DDdate       	= $DDdatestr[$Key];
			$DDexdate     	= $DDexdatestr[$Key];
			$AmountList     = $DDAmountListstr[$Key];
			$DDChallannum   = $DDCallannostr[$Key];
			$DDChalldate    = $DDCallandatstr[$Key];
			$DDChallRealate = $DDrealDatstr[$Key];
			$DDDrawbname    = $DDDraweBankstr[$Key];
			
			$TrimBankname 	= trim($DDbname);
			$TrimBranc 	    = trim($DDbadd);
			$TrimInstnum 	= trim($DDinstnum);
			$TrimAmount 	= trim($AmountList);
			$Insertdate 	= dt_format($DDdate);
			$InsertExpdate 	= dt_format($DDexdate);
			
			$TrimDrawBankname 	= trim($DDDrawbname);
			$TrimChallnum 	    = trim($DDChallannum);
			$Challandate 	    = dt_format($DDChalldate);
			$Realdate 	        = dt_format($DDChallRealate);

			$insert_query1	= "insert into emd_detail set emid='$LastInsertid', contid='$DDCont', inst_type='DD',inst_no='$TrimInstnum', bank_name='$TrimBankname',
			branch_addr='$TrimBranc', issue_dt='$Insertdate', valid_dt='$InsertExpdate', emd_amt='$TrimAmount',  ga_challan_no='$TrimChallnum', ga_challan_date='$Challandate', ga_realisation_date='$Realdate', 
			ga_drawee_bank='$TrimDrawBankname', active='1'";
			$insert_sql1 = mysqli_query($dbConn,$insert_query1);
			if($insert_sql1 == true){
				$msg = "EMD Entry Successfully Saved..!!";
				UpdateWorkTransaction($GlobInTsID,0,0,"W","EMD details Saved by ".$UserId."","");
			}else{
				$msg = "Error: EMD Entry Not Saved..!!";
				UpdateWorkTransaction($GlobInTsID,0,0,"W","EMD details Tried to Saved by ".$UserId." but not Updated","");
			}
		}
	}
}
if(isset($_GET['id'])){   
	$TRId 	 = $_GET['id'];

	$ContArr  	 =  array();
	$ContNameArr = array();
	$GlobID= '';
	$GlobIDQuery =  "select emd_master.*,tender_register.work_name from emd_master 
	JOIN tender_register ON emd_master.tr_id = tender_register.tr_id  where emd_master.tr_id = '$TRId'";
	
	// "select emd_detail.*, emd_master.* from emd_detail
	// 					JOIN emd_master ON emd_detail.emid = emd_master.emid       
	// 					  WHERE emd_master.tr_id = '$TRId'";
	$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobID     = $List->globid;
			$TsId       = $List->ts_id;
			$TrId       = $List->tr_id;
			$Emdid      = $List->emid;
			$WorkName   = $List->work_name;
			$EMdamtt     = $List->emd_lot_amt;
			//$contid
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
	function ViewBidder(){
		url = "BiddersList.php";
		window.location.replace(url);
	}
</script>
<style>
	.head-b {
		background: #136BCA;
		border-color: #136BCA;
	}

	.dataFont {
		font-weight: bold;
		color: #001BC6;
		font-size: 12px;
		text-align: left;
}
</style>

<script type="text/javascript" language="javascript">
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1 stable" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div1">&nbsp;</div>
									<div class="div10">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">EMD- Entry</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="row clearrow"></div>									
																<div class="row">
																	<div class="div4 dataFont"> 	
																		Tender No.
																	</div>
																    <div class="div6">
																		<select id="cmb_tnder_no" name="cmb_tnder_no" class="tboxsmclass">
																			<option value="">--------------- Select --------------- </option>
																			<?php echo $objBind->BindEMDTrNo($TrId);?>
																		</select>
																    </div>
																    <div class="row clearrow"></div>
																    <div class="row">
																	    <div class="div4 dataFont">
																		   Name of Work
																	    </div>
																	    <div class="div6">
																		   <textarea name='txt_work_name' id='txt_work_name' class="tboxsmclass" readonly=""><?php if($_GET['id'] != ""){ echo $WorkName; } ?></textarea>
																	    </div>
																   </div>
																   <div class="row clearrow"></div>
																   <div class="row">
																	 <div class="div4 dataFont">
																		EMD Amount ( &#8377; )
																	 </div>
																	 <div class="div6">
																		<input type='hidden' readonly="" name='txt_full_emd_amt' id='txt_full_emd_amt' class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $EMdamtt; } ?>">
																		<input type='text' readonly="" name='txt_full_emd_amt_indform' id='txt_full_emd_amt_indform' class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo IndianMoneyFormat($EMdamtt); } ?>">
																		<input type="hidden" name="txt_tender_id" id="txt_tender_id" maxlength="50" class="tboxsmclass" style="width:99%" value="<?php if($_GET['id'] != ""){ echo $TRId; } ?>"></div>
																		<input type="hidden" name="txt_emdmas_id" id="txt_emdmas_id" maxlength="50" class="tboxsmclass" style="width:99%" value="<?php if($_GET['id'] != ""){ echo $Emdid; } ?>"></div>
																	  </div>
																   </div>
																   <div class="row clearrow isappcheck" style="display-none"></div>
																   <div class="row clearrow"></div>														
																	<!--    2nd Div Starts Here   -->
																	<div class="card-header inkblue-card" align="left">&nbsp;EMD Details</div>
																		<table class="dataTable etable " align="center" width="100%" id="emdtable1">
																			<tr class="label" style="background-color:#FFF">
																				<th align="center">Bidder's Name</th>
																				<th align="center">Instrument <br>Type</th>
																				<th align="center">Instrument <br> Number</th>
																				<th align="center"> Bank Name </th>
																				<th align="center">Branch </th>
																				<th align="center">Date of <br> Issued</th>
																				<th align="center">Date of <br> Expiry</th>
																				<th align="center">Amount ( &#8377; )</th>
																				<th align="center">Action</th>
																			</tr>
																		<tr>
																		    <td align="center">
																				<select id="cmb_bidder_0" name="cmb_bidder_0" class="tboxsmclass">
																					<option value="">---- Select ----</option>
																					<?php echo $objBind->BindCont('');?>
																				</select>
																			 </td>
																			 <td align="center">
																				<select name="cmd_instype_0" id ="cmd_instype_0" class="tboxsmclass">  
																					<option value="">- Select - </option>
																					<option value="BG">BG</option>
																					<option value="FDR">FDR</option>
																				</select>
																			 </td>
																		 	 <td align="center">
																				<input type="text" name="instrunum_0" id ="instrunum_0"  maxlength="100" class="tboxsmclass">
																			 </td>
																			 <td align="center"><input type="text" class="tboxsmclass"  maxlength="50" name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
																			 <td align="center"><input type="text" class="tboxsmclass" maxlength="100" name="txt_sno_pg_0" id="txt_sno_pg_0"></td>
																			 <td align="center"><input type="text" placeholder="DD/MM/YYYY" readonly="" data-index = '0' class="tboxsmclass date EmdDt ValDate" name="txt_date_pg_0" id="txt_date_pg_0"></td>
																			 <td align="center"><input type="text" placeholder="DD/MM/YYYY" readonly="" data-index = '0' class="tboxsmclass expdate ExpDt ValDate" name="txt_expir_date_pg_0" id="txt_expir_date_pg_0"></td>
																			 <td align="center"><input type="text" style="text-align:right;" maxlength="12" class="tboxsmclass" onKeyPress="return isNumberWithTwoDecimal(event,this);" onpaste="return false" name="txt_part_amt_0" id="txt_part_amt_0"></td>
																			 <td align="center"><input type="button"  name="emp_add" id="emp_add"  value="ADD" class="btn btn-info"></td>
																				<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
																		 </tr>
																		
																			<!-- For Update Function -->
																			<?php
																				$Index = 1;
																				$SelectQuery2 = "SELECT * FROM emd_detail where  emid='$Emdid' AND inst_type !='DD'  order by emdtid asc";
																				//echo $SelectQuery2;exit;
																				$ResultQuery2 = mysqli_query($dbConn,$SelectQuery2);
																		if($ResultQuery2 == true){
																					if(mysqli_num_rows($ResultQuery2)>0){
																						while($Result2 = mysqli_fetch_object($ResultQuery2)){
																							$contid  		    = $Result2->contid;
																							$Instatype  		= $Result2->inst_type;
																							$Detailid 		    = $Result2->emdtid ;
																							$Instrunum   	    = $Result2->inst_no;
																							$Bankname    	    = $Result2->bank_name;
																							$Branchname   	    = $Result2->branch_addr;
																							$Emnddate   	    = dt_display($Result2->issue_dt);
																							$Emdexpirdat        = dt_display($Result2->valid_dt) ;
																							$Emdamnt            = $Result2->emd_amt;
																					
																				?>
																		   <tr id="<?php echo $Index; ?>">
																			  <td align="center">
																				<select name="cmb_bidder[]" id="cmb_bidder<?php echo $Index; ?>"  data-index="<?php echo $Index; ?>" class="tboxsmclass">
																						<option value="">---- Select ----</option>
																						<?php echo $objBind->BindCont($contid);?>
																	
																				 </select>
																				 <input type="hidden" name="cmd_contid[]" id="cmb_contid<?php echo $Index; ?>" data-index="<?php echo $Index; ?>"  class="tboxsmclass" value="<?php echo $Result2->contid ?>">
																			  </td>
																			  <td align="center">
																				<select name="cmd_instype[]" id ="cmd_instype<?php echo $Index; ?>" data-index="<?php echo $Index; ?>"  class="tboxsmclass">  
																						<option value="">-Select-</option>
																						<option value="BG"<?php if((isset($Result2->inst_type ))&&($Result2->inst_type == 'BG')){ echo 'selected="selected"'; } ?>>BG</option>
																						<option value="FDR" <?php if((isset($Result2->inst_type ))&&($Result2->inst_type == 'FDR')){ echo 'selected="selected"'; } ?>>FDR</option>
																				</select> 
																			  </td>
																			  <td align="center">
																				<input type="text" name="instrunum[]" id ="instrunum<?php echo $Index; ?>" value="<?php echo $Instrunum ?>" class="tboxsmclass">
																				<input type="hidden" name="cmd_detail[]"  id ="cmd_detail<?php echo $Index; ?>" class="tboxsmclass" value="<?php echo $Result2->emdtid ?>">
																			 </td>
																			  <td align="center"><input type="text" class="tboxsmclass"  name="txt_bankname_pg[]" id="txt_bankname_pg_<?php echo $Index; ?>" data-index="<?php echo $Index; ?>" value="<?php echo $Bankname?>"></td>
																			  <td align="center"><input type="text" class="tboxsmclass" name="txt_sno_pg[]" id="txt_sno_pg_<?php echo $Index; ?>" data-index="<?php echo $Index; ?>" value="<?php echo $Branchname?>"></td>
																			  <td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass date EmdDt ValDate"  name="txt_date_pg[]" id="txt_date_pg_<?php echo $Index; ?>" data-index="<?php echo $Index; ?>" value="<?php echo dt_display($Result2->issue_dt);?>"></td>
																			  <td align="center"><input type="text"  placeholder="DD/MM/YYYY" class="tboxsmclass expdate ExpDt ValDate" name="txt_expir_date_pg[]" id="txt_expir_date_pg_<?php echo $Index; ?>" data-index="<?php echo $Index; ?>" value="<?php echo dt_display($Result2->valid_dt);?>"></td>
																			  <td align="center"><input type="text" style="text-align:right;" maxlength="12" class="tboxsmclass EmAmt<?php echo $contid; ?>" onpaste="return false" data-id="<?php echo $contid; ?>" data-index="<?php echo $Index; ?>"  onKeyPress="return isNumberWithTwoDecimal(event,this);" name="txt_part_amt[]" id="txt_part_amt_<?php echo $Index; ?>" value="<?php  echo $Emdamnt?>"></td>
																			  <td><input type="button"  class="delete btn btn-info" name="emp_delete" id="emp_delete" data-id="<?php echo $Detailid; ?>" data-index="<?php echo $Index; ?>" value="DELETE" />
																		  </tr>
																		  <?php
																			$Index++;
																         	}
																			}
																		   }
																			?>
												<!-- End for Update Function -->
																     </table>
															     </div>
																 <div class="row clearrow"></div>
																 <div class="card-header inkblue-card " align="left">&nbsp;DD/Banker's Cheque Details</div>
																 <table class="dataTable etable table-responsive " align="center" width="100%" id="emdtable2">
																	<tr class="label" style="background-color:#FFF">
																		<th align="center">Bidder's Name</th>
																		<!-- <th align="center">Instrument <br>Type</th> -->
																		<th align="center">DD <br> Number</th>
																		<th align="center"> Bank Name </th>
																		<th align="center">Branch </th>
																		<th align="center">Date of <br> Issued</th>
																		<th align="center">Date of <br> Expiry</th>
																		<th align="center">Amount <br>( &#8377; )</th>
																		<th align="center">Challan<br>No. </th>
																		<th align="center"> Challan <br> Date</th>
																		<th align="center">Challan <br> Realisation<br>Date</th>
																		<th align="center">Drawee <br> Bank<br>Details</th>
																		<th align="center">Action</th>
																	</tr>
																    <tr>
																	   <td align="center">
																			<select id="cmb_bidder_DD_0" name="cmb_bidder_DD_0" class="tboxsmclass">
																				<option value="">---- Select ----</option>
																				<?php echo $objBind->BindCont('');?>
																		 	</select>	
																	   </td>
																	   <!-- <td align="center">
																			<select name="cmd_instype_DD_0" id ="cmd_instype_DD_0" class="tboxsmclass">  
																				<option value="DD">DD/ Banker's Cheque</option>
																			</select>
																		</td> -->
																		<td align="center">
																		    <input type="text" name="instrunum_DD_0" id ="instrunum_DD_0"  maxlength="100" class="tboxsmclass">
																		</td>
																		<td align="center"><input type="text" class="tboxsmclass"  maxlength="50" name="txt_bankname_pg_DD_0" id="txt_bankname_pg_DD_0"></td>
																		<td align="center"><input type="text" class="tboxsmclass" maxlength="100" name="txt_sno_pg_DD_0" id="txt_sno_pg_DD_0"></td>
																		<td align="center"><input type="text" placeholder="DD/MM/YYYY" readonly="" data-DDindex = '0' class="tboxsmclass date EmdDDt ValDDDate" name="txt_date_pg_DD_0" id="txt_date_pg_DD_0"></td>
																		<td align="center"><input type="text" placeholder="DD/MM/YYYY" readonly="" data-DDindex = '0' class="tboxsmclass expdate ExpDDt ValDDDate" name="txt_expir_date_pg_DD_0" id="txt_expir_date_pg_DD_0"></td>
																		<td align="center"><input type="text" style="text-align:right;" maxlength="12" class="tboxsmclass" onpaste="return false" onKeyPress="return isNumberWithTwoDecimal(event,this);" name="txt_part_amt_DD_0" id="txt_part_amt_DD_0"></td>
																		<td align="center"><input type="text" class="tboxsmclass" maxlength="100" name="txt_challNo_pg_DD_0" id="txt_challNo_pg_DD_0"></td>
																		<td align="center"><input type="text" placeholder="DD/MM/YYYY" readonly="" data-index = '0' class="tboxsmclass date EmdDDt" name="txt_challandate_pg_DD_0" id="txt_challandate_pg_DD_0"></td>
																		<td align="center"><input type="text" placeholder="DD/MM/YYYY" readonly="" data-index = '0' class="tboxsmclass expdate ExpDDt " name="txt_Challanrealdate_pg_0" id="txt_Challanrealdate_pg_0"></td>
																		<td align="center"><input type="text"  class="tboxsmclass" name="txt_draweebank_DD_0" id="txt_draweebank_DD_0"></td>
																		<td align="center"><input type="button"  name="emp_DD_add" id="emp_DD_add"  value="ADD" class="btn btn-info"></td>
																	</tr>
																
																	<!-- For Update Function -->
																		<?php
																			$DDIndex = 1;
																					$SelectQuery3 = "SELECT * FROM emd_detail where  emid='$Emdid' AND inst_type ='DD'  order by emdtid asc";
																					//echo $SelectQuery3;exit;
																					$ResultQuery3 = mysqli_query($dbConn,$SelectQuery3);
																			if($SelectQuery3 == true){
																						if(mysqli_num_rows($ResultQuery3)>0){
																							while($Result2 = mysqli_fetch_object($ResultQuery3)){
																								$contid  		    = $Result2->contid;
																								//$Instatype  		= $Result2->inst_type;
																								$Detailid 		    = $Result2->emdtid ;
																								$DDinstnum   	    = $Result2->inst_no;
																								$DDbname    	    = $Result2->bank_name;
																								$DDBranc   	        = $Result2->branch_addr;
																								$DDdate   	        = dt_display($Result2->issue_dt);
																								$DDexdate           = dt_display($Result2->valid_dt) ;
																								$AmountList         = $Result2->emd_amt;
																								$DDChallannum    	= $Result2->ga_challan_no;
																								$DDChalldate        = dt_display($Result2->ga_challan_date) ;
																								$DDChallRealate   	= dt_display($Result2->ga_realisation_date);
																								$DDDrawBank  	    = $Result2->ga_drawee_bank;
																			?>
																		   <tr id="<?php echo $DDIndex; ?>">
																			  <td align="center">
																			  <select name="cmb_bidder_DD[]" id="cmb_bidder_DD_<?php echo $DDIndex; ?>"  data-DDindex="<?php echo $DDIndex; ?>" class="tboxsmclass">
																						<option value="">---- Select ----</option>
																						<?php echo $objBind->BindCont($contid);?>
																	
																				 </select>
																				 <input type="hidden" name="cmd_contid_DD[]" id="cmb_contid_DD_<?php echo $DDIndex; ?>" data-DDindex="<?php echo $DDIndex; ?>"  class="tboxsmclass" value="<?php echo $Result2->contid ?>">
																			  </td>
																	

																			  <td align="center">
																			  <input type="text" name="instrunum_DD[]" id ="instrunum_DD_<?php echo $DDIndex; ?>" value="<?php echo $DDinstnum ?>" class="tboxsmclass">
																				<input type="hidden" name="cmd_detail_DD[]"  id ="cmd_detail_DD_<?php echo $DDIndex; ?>" class="tboxsmclass" value="<?php echo $Detailid ?>">
																			 </td>
																			 <td align="center"><input type="text" class="tboxsmclass"  name="txt_bankname_pg_DD[]" id="txt_bankname_pg_DD_<?php echo $DDIndex; ?>" data-DDindex="<?php echo $DDIndex; ?>" value="<?php echo $DDbname?>"></td>
																				<td align="center"><input type="text" class="tboxsmclass" name="txt_sno_pg_DD[]" id="txt_sno_pg_DD_<?php echo $DDIndex; ?>" data-DDindex="<?php echo $DDIndex; ?>" value="<?php echo $DDBranc?>"></td>
																				<td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass date EmdDt ValDate"  name="txt_date_pg_DD[]" id="txt_date_pg_DD_<?php echo $DDIndex; ?>" data-DDindex="<?php echo $Index; ?>" value="<?php echo $DDdate;?>"></td>
																				<td align="center"><input type="text"  placeholder="DD/MM/YYYY" class="tboxsmclass expdate ExpDt ValDate" name="txt_expir_date_pg_DD[]" id="txt_expir_date_pg_DD_<?php echo $DDIndex; ?>" data-DDindex="<?php echo $Index; ?>" value="<?php echo $DDexdate;?>"></td>
																				<td align="center"><input type="text" style="text-align:right;" maxlength="12" class="tboxsmclass DDAmt<?php echo $contid; ?>"  data-id="<?php echo $contid; ?>" data-DDindex="<?php echo $DDIndex; ?>"  onKeyPress="return isNumberWithTwoDecimal(event,this);" name="txt_part_amt_DD[]" id="txt_part_amt_DD_<?php echo $DDIndex; ?>" value="<?php  echo $AmountList?>"></td>
																				<td align="center"><input type="text" class="tboxsmclass" name="txt_challNo_pg_DD[]" id="txt_challNo_pg_DD_<?php echo $Index; ?>" data-DDindex="<?php echo $DDIndex; ?>" value="<?php echo $DDChallannum?>"></td>
																				<td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass  date EmdDDt ValDDDate"  name="txt_challandate_pg_DD[]" id="txt_challandate_pg_DD_<?php echo $Index; ?>" data-DDindex="<?php echo $DDIndex; ?>" value="<?php echo $DDChalldate;?>"></td>
																				<td align="center"><input type="text"  placeholder="DD/MM/YYYY" class="tboxsmclass expdate ExpDDt ValDDDate" name="txt_Challanrealdate_pg_DD[]" id="txt_Challanrealdate_pg_DD_<?php echo $DDIndex; ?>" data-DDindex="<?php echo $DDIndex; ?>" value="<?php echo $DDChallRealate ;?>"></td>
																				<td align="center"><input type="text"  class="tboxsmclass"  data-id="<?php echo $contid; ?>" data-DDindex="<?php echo $DDIndex; ?>"   name="txt_draweebank_DD[]" id="txt_draweebank_DD_<?php echo $Index; ?>" value="<?php  echo $DDDrawBank?>"></td>
																				<td><input type="button"  class="DDdelete btn btn-info" name="emp_DD_delete" id="emp_DD_delete" data-id="<?php echo $Detailid; ?>" data-DDindex="<?php echo $DDIndex; ?>" value="DELETE" />
																		  </tr>
																		  <?php
																			$DDIndex++;
																         	}
																			}
																		   }
																			?>
										<!-- End for Update Function -->
																</table>
															     </div>
															   </div>
													    	</div>
													   </div>
												  </div>
											  </div>
											<div class="div12" align="center">
											   <a data-url="Home" class="btn btn-info" name="Back" id="Back">Back</a>
												<input type="submit" class="btn btn-info" name="submit" id="submit" value=" Save " />
												<a data-url="EMDEntryView" class="btn btn-info" name="btn_view" id="btn_view">View</a>
											</div>
											<div class="row clearrow"></div>												
										</div>
									</div>
									<div class="div1">&nbsp;</div>
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
	$("#cmb_shortname").chosen();
	$("#cmb_engineer").chosen();
	var Index = "<?php echo $Index; ?>"		
	var DDIndex = "<?php echo $DDIndex; ?>"		

	var msg = "<?php echo $msg; ?>";
    document.querySelector('#top').onload = function(){
	if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						dialog.close();
						window.location.replace('EMDEntry.php');
					}
				}]
			});
		}
};
	var KillEvent = 0;		
	$(document).ready(function(){ 
		
		$("body").on("change","#cmb_tnder_no", function(event){
			var MastId = $(this).val();
			var Id = $(this).val();
			$("#txt_work_name").val('');
		    $("#txt_full_emd_amt").val('');
			$("#cmb_bidder_0").val('');
			$("#cmd_instype_0").val('');
			$("#instrunum_0").val('');
			$("#txt_bankname_pg_0").val('');
			$("#txt_sno_pg_0").val('');
			$("#txt_date_pg_0").val('');
			$("#txt_expir_date_pg_0").val('');
			$("#txt_part_amt").val('');
			$("#txt_work_name").val('');
			$("#cmb_bidder_DD_0").val('');
			$("#cmd_instype_DD_0").val('');
			$("#instrunum_DD_0").val('');
			$("#txt_bankname_pg_DD_0").val('');
			$("#txt_sno_pg_DD_0").val('');
			$("#txt_date_pg_DD_0").val('');
			$("#txt_expir_date_pg_DD_0").val('');
			$("#txt_part_amt_DD_0").val('');
			$("#txt_challNo_pg_DD_0").val('');
			$("#txt_challandate_pg_DD_0").val('');
			$("#txt_Challanrealdate_pg_0").val('');
			$("#txt_draweebank_DD_0").val('');
			$.ajax({ 
				type: 'POST', 
				url: 'FindEstTsTrName.php', 
				data: { Id: Id, Page: 'TR'}, 
				dataType: 'json',
				success: function (data) {  
					if(data != null){ 
						var EmdAmout = Math.round(data.emd);
						var EmdAmoutIndForm = (EmdAmout).toLocaleString('en-IN')
						$("#txt_work_name").val(data.work_name);
						$("#txt_full_emd_amt_indform").val(EmdAmoutIndForm);
						$("#txt_full_emd_amt").val(EmdAmout);
					}
				}
			});
			
			
			
		});

		$("body").on("change", ".ValDate", function(event){ 
			var DateIndex = $(this).attr("data-index");
			var DateofIssue  = $("#txt_date_pg_"+DateIndex).val(); 
			var DateofExpiry = $("#txt_expir_date_pg_"+DateIndex).val();
			if((DateofIssue != "") && (DateofExpiry != "") ){  
				var d1 = DateofExpiry.split("/");
				var d2 = DateofIssue.split("/");
				var emdexpdate = new Date(d1[2], d1[1]-1, d1[0]); //alert(emdexpdate);
				var emddate = new Date(d2[2], d2[1]-1, d2[0]); //alert(emddate);
				if(emdexpdate<emddate){
					var a="EMD Expiry date should be greater than EMD Date";
					BootstrapDialog.alert(a);
					$(this).val('');
					event.preventDefault();
					event.returnValue = false;
					//CheckVal = 1;
				}
			}
		});
		$("body").on("change", ".ValDDDate", function(event){ 
			var DateDDIndex = $(this).attr("data-DDindex");
			var DateofIssue  = $("#txt_date_pg_DD_"+DateDDIndex).val(); 
			var DateofExpiry = $("#txt_expir_date_pg_DD_"+DateDDIndex).val();
			if((DateofIssue != "") && (DateofExpiry != "") ){  
				var d1 = DateofExpiry.split("/");
				var d2 = DateofIssue.split("/");
				var emdexpdate = new Date(d1[2], d1[1]-1, d1[0]); //alert(emdexpdate);
				var emddate = new Date(d2[2], d2[1]-1, d2[0]); //alert(emddate);
				if(emdexpdate<emddate){
					var a="DD Expiry date should be greater than DD Date";
					BootstrapDialog.alert(a);
					$(this).val('');
					event.preventDefault();
					event.returnValue = false;
					//CheckVal = 1;
				}
			}
		});

		$("body").on("click", "#emp_add", function(event){ 
			var CheckVal = 0;
			var ContName   	 = $("#cmb_bidder_0 option:selected").text(); 
			var EmdTotAmt 	 = $("#txt_full_emd_amt").val(); 
			var ContId 	     = $("#cmb_bidder_0").val(); 
			var InstType 	 = $("#cmd_instype_0").val();
			var InstNum 	 = $("#instrunum_0").val();
			var BankName   	 = $("#txt_bankname_pg_0").val();
			var BankAddress  = $("#txt_sno_pg_0").val();
			var DateofIssue  = $("#txt_date_pg_0").val(); 
			var DateofExpiry = $("#txt_expir_date_pg_0").val();
			var AmtDetail	 = $("#txt_part_amt_0").val(); //alert(AmtDetail);
			var TotalAmt     = AmtDetail; 

			// if(EmdTotAmt > TotalAmt){
			// 	       BootstrapDialog.alert("Total Amount EMD Amount is lesser than Bidder's EMD amount");
			// 		    event.preventDefault();
			// 			event.returnValue = false;
					
					//}
			// $(".EmAmt"+ContId).each(function(){
			// 	var Amt = $(this).val(); //alert(Amt);
			// 	TotalAmt =  parseFloat(TotalAmt) + parseFloat(Amt);
			// 	if(EmdTotAmt > TotalAmt){
			// 		BootstrapDialog.alert("Total Amount EMD Amount is lesser than Bidder's EMD amount");
			// 		event.preventDefault();
			// 		event.returnValue = false;
				
			// 	}
			// });
			/*if((DateofIssue != "") && (DateofExpiry != "") ){  
				var d1 = DateofExpiry.split("/");
				var d2 = DateofIssue.split("/");
				var emdexpdate = new Date(d1[2], d1[1]-1, d1[0]); //alert(emdexpdate);
				var emddate = new Date(d2[2], d2[1]-1, d2[0]); //alert(emddate);
				if(emdexpdate<emddate){ 
					//var a="EMD Expiry date  should be greater than EMD  Date";
					//BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
					CheckVal = 1;
					//$("#txt_date_pg").val(''); 
					//$("#txt_expir_date_pg").val(''); 
				}else{
					var a="";
					CheckVal = 0;
					//$('#val_date').text(a);
				}
			}*/

		

			var RowStr = '<tr id="'+Index+'"><td><input type="hidden" name="cmd_contid[]" id="cmd_contid_'+Index+'" data-index="'+Index+'" class="tboxsmclass" readonly value="'+ContId+'"><input type="text" name="cmb_bidder[]" id="cmb_bidder_'+Index+'" data-index="'+Index+'" class="tboxsmclass" readonly value="'+ContName+'"><td><input type="text" name="cmd_instype[]" data-index="'+Index+'" id="cmd_instype_'+Index+'"  readonly class="tboxsmclass" value="'+InstType+'"></td><td><input type="text" name="instrunum[]" data-index="'+Index+'" id="instrunum_'+Index+'" readonly class="tboxsmclass" value="'+InstNum+'"></td><td><input type="text" name="txt_bankname_pg[]" data-index="'+Index+'" id="txt_bankname_pg_'+Index+'" readonly class="tboxsmclass"  value="'+BankName+'"></td><td><input type="text" readonly name="txt_sno_pg[]" data-index="'+Index+'" id="txt_sno_pg_'+Index+'" class="tboxsmclass" value="'+BankAddress+'"></td><td><input type="text" readonly name="txt_date_pg[]" data-index="'+Index+'" id="txt_date_pg_'+Index+'" class="tboxsmclass EmdDt ValDate" value="'+DateofIssue+'"></td><td><input type="text" readonly name="txt_expir_date_pg[]" data-index="'+Index+'" id="txt_expir_date_pg_'+Index+'" class="tboxsmclass ExpDt ValDate" value="'+DateofExpiry+'"></td><td><input type="number" data-index="'+Index+'" readonly onKeyPress="return isNumberWithTwoDecimal(event,this);" name="txt_part_amt[]" id="txt_part_amt_'+Index+'" class="tboxsmclass EmAmt'+ContId+'" data-id="'+ContId+'" style="text-align:right;" value="'+AmtDetail+'"></td><td align="center"><input type="button" data-index="'+Index+'" class="delete btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 

			if(InstType == 0){
				BootstrapDialog.alert("Instrument Type should not be empty");
				return false;
			}else if(InstNum == 0){
				BootstrapDialog.alert("Instrument Number should not be empty");
				return false;
			}else if(BankName == 0){
				BootstrapDialog.alert("Bank Name should not be empty");
				return false;
			}else if(BankAddress == 0){
				BootstrapDialog.alert("Bank Address should not be empty");
				return false;
			}else if(DateofIssue == 0){
				BootstrapDialog.alert("Date of Issue should not be empty");
				return false;
			}else if(DateofExpiry == 0){
				BootstrapDialog.alert("Date of Expiry should not be empty");
				return false;
			}else if(AmtDetail == 0){
				BootstrapDialog.alert("Amount should not be empty");
				return false;
			}else if(CheckVal ==  1){
				BootstrapDialog.alert("EMD Expiry date is lesser than EMD Date..Please Change..!!");
				return false;
			}else{
				$("#emdtable1").append(RowStr);
				$("#cmb_bidder_0").val('');
				$("#cmd_instype_0").val('');
				$("#instrunum_0").val('');
				$("#txt_bankname_pg_0").val('');
				$("#txt_sno_pg_0").val('');
				$("#txt_date_pg_0").val('');
				$("#txt_expir_date_pg_0").val('');
				$("#txt_part_amt_0").val('');
				//$("#text_totalamt").val('');
				Index++;
			}
			TotalUnitAmountCalc();

			
		});
		$("body").on("click", ".delete", function(){
			$(this).closest("tr").remove();
			///$("#text_totalamt").val('');
			//TotalUnitAmountCalc();
			//DateValidation();
		});
		$("body").on("click", "#emp_DD_add", function(event){ 
			var CheckVal = 0;
			var ContName   	 = $("#cmb_bidder_DD_0 option:selected").text(); 
			var ContId 	     = $("#cmb_bidder_DD_0").val(); 
			//var InstType 	 = $("#cmd_instype_0").val();
			var InstNum 	 = $("#instrunum_DD_0").val();
			var BankName   	 = $("#txt_bankname_pg_DD_0").val();
			var BankAddress  = $("#txt_sno_pg_DD_0").val();
			var DateofIssue  = $("#txt_date_pg_DD_0").val(); 
			var DateofExpiry = $("#txt_expir_date_pg_DD_0").val();
			var AmtDetail	 = $("#txt_part_amt_DD_0").val();
			var ChallanNum 	 = $("#txt_challNo_pg_DD_0").val();
			var ChallanDate  = $("#txt_challandate_pg_DD_0").val(); 
			var RealisatDate = $("#txt_Challanrealdate_pg_0").val();
			var DraweeBank   = $("#txt_draweebank_DD_0").val();
			var TotalAmt     = AmtDetail; 


		

			var RowStr = '<tr id="'+DDIndex+'"><td><input type="hidden" name="cmd_contid_DD[]" id="cmd_contid_DD_'+DDIndex+'" data-DDindex="'+DDIndex+'" class="tboxsmclass" readonly value="'+ContId+'"><input type="text" name="cmb_bidder_DD[]" id="cmb_bidder_DD'+DDIndex+'" data-DDindex="'+DDIndex+'" class="tboxsmclass" readonly value="'+ContName+'"></td>';
			   // RowStr +='<td><input type="text" name="cmd_instype[]" data-DDindex="'+DDIndex+'" id="cmd_instype_'+DDIndex+'"  readonly class="tboxsmclass" value="'+InstType+'"></td>';
				RowStr +='<td><input type="text" name="instrunum_DD[]" data-DDindex="'+DDIndex+'" id="instrunum_DD_'+DDIndex+'" readonly class="tboxsmclass" value="'+InstNum+'"></td>';
				RowStr +='<td><input type="text" name="txt_bankname_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_bankname_pg_DD_'+DDIndex+'" readonly class="tboxsmclass"  value="'+BankName+'"></td>';
				RowStr +='<td><input type="text" readonly name="txt_sno_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_sno_pg_DD_'+DDIndex+'" class="tboxsmclass" value="'+BankAddress+'"></td>';
				RowStr +='<td><input type="text" readonly name="txt_date_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_date_pg_DD_'+DDIndex+'" class="tboxsmclass EmdDt ValDate" value="'+DateofIssue+'"></td>';
				RowStr +='<td><input type="text" readonly name="txt_expir_date_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_expir_date_pg_DD_'+DDIndex+'" class="tboxsmclass ExpDt ValDate" value="'+DateofExpiry+'"></td>';
				RowStr +='<td><input type="number" data-DDindex="'+DDIndex+'" readonly onKeyPress="return isNumberWithTwoDecimal(event,this);" name="txt_part_amt_DD[]" id="txt_part_amt_DD_'+DDIndex+'" class="tboxsmclass DDAmt'+ContId+'" data-id="'+ContId+'" style="text-align:right;" value="'+AmtDetail+'"></td>';
				RowStr +='<td><input type="text" data-DDindex="'+DDIndex+'" readonly  name="txt_challNo_pg_DD[]" id="txt_challNo_pg_DD_'+DDIndex+'" class="tboxsmclass" data-id="'+ContId+'" style="text-align:right;" value="'+ChallanNum+'"></td>';
				RowStr +='<td><input type="text" readonly name="txt_challandate_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_challandate_pg_DD_'+DDIndex+'" class="tboxsmclass EmdDDt ValDDDate" value="'+ChallanDate+'"></td>';
				RowStr +='<td><input type="text" readonly name="txt_Challanrealdate_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_Challanrealdate_pg_DD_'+DDIndex+'" class="tboxsmclass expdate ExpDDt ValDDDate" value="'+RealisatDate+'"></td>';
				RowStr +='<td><input type="text" data-DDindex="'+DDIndex+'" readonly  name="txt_draweebank_DD[]" id="txt_draweebank_DD_'+DDIndex+'" class="tboxsmclass" data-id="'+ContId+'" style="text-align:right;" value="'+DraweeBank+'"></td>';
				RowStr +='<td align="center"><input type="button" data-DDindex="'+DDIndex+'" class="DDdelete btn btn-info" name="emp_DD_delete" id="emp_DD_delete" value="DELETE"></td></tr>'; 

			// if(InstType == 0){
			// 	BootstrapDialog.alert("Instrument Type should not be empty");
			// 	return false;
			// }else
			 if(InstNum == 0){
				BootstrapDialog.alert("DD Number should not be empty");
				return false;
			}else if(BankName == 0){
				BootstrapDialog.alert("Bank Name should not be empty");
				return false;
			}else if(BankAddress == 0){
				BootstrapDialog.alert("Bank Address should not be empty");
				return false;
			}else if(DateofIssue == 0){
				BootstrapDialog.alert("Date of Issue should not be empty");
				return false;
			}else if(DateofExpiry == 0){
				BootstrapDialog.alert("Date of Expiry should not be empty");
				return false;
			}else if(AmtDetail == 0){
				BootstrapDialog.alert("Amount should not be empty");
				return false;
			}else if(ChallanNum == 0){
				BootstrapDialog.alert("Amount should not be empty");
				return false;
			}else if(ChallanDate == 0){
				BootstrapDialog.alert("Amount should not be empty");
				return false;
			// }else if(DraweeBank == 0){
			// 	BootstrapDialog.alert("Amount should not be empty");
			// 	return false;
			}else{
				$("#emdtable2").append(RowStr);
				$("#cmb_bidder_DD_0").val('');
				$("#cmd_instype_DD_0").val('');
				$("#instrunum_DD_0").val('');
				$("#txt_bankname_pg_DD_0").val('');
				$("#txt_sno_pg_DD_0").val('');
				$("#txt_date_pg_DD_0").val('');
				$("#txt_expir_date_pg_DD_0").val('');
				$("#txt_part_amt_DD_0").val('');
				$("#txt_challNo_pg_DD_0").val('');
				$("#txt_challandate_pg_DD_0").val('');
				$("#txt_Challanrealdate_pg_0").val('');
				$("#txt_draweebank_DD_0").val('');
				//$("#text_totalamt").val('');
				DDIndex++;
			}
			TotalUnitAmountCalc();

			
		});
		$("body").on("click", ".DDdelete", function(){
			$(this).closest("tr").remove();
			///$("#text_totalamt").val('');
			//TotalUnitAmountCalc();
			//DateValidation();
		});
		function TotalUnitAmountCalc(){
			var TotalAmt = 0; 
			$(".Amt").each(function(){
				var Amt = $(this).val(); 
				//TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt);
				//$("#text_totalamt").val(TotalAmt);
			
			});
		}
		$('#cmb_tr_no').chosen();
		$("body").on("click","#submit", function(event){ 
			var TotalAmt =0;

			if(KillEvent == 0){
				var partamt1 =       $("#txt_part_amt").val();
				var ShortName 	  = $("#cmb_tnder_no").val(); 
				var WorkName 	  = $("#txt_work_name").val();
				//var EnginnerName 	= $("#cmb_engineer").val();
				//var BidderName 	  = $("#cmb_bidder").val();
				var EmdAmount 	  = $("#txt_full_emd_amt").val();
				var EmdContractor = $("#instrunum").val(); 
				var rowCount      = $('#emdtable1 tr').length; 
				var AllCont = [];
				var DDCont = [];

				$("input[name='cmd_contid[]']").each(function(){  
					AllCont.push($(this).val());
				});
				$("input[name='cmd_contid_DD[]']").each(function(){
					DDCont.push($(this).val());
				});
				var ContArr = Array.from(new Set(AllCont)); 
				var ContDDArr = Array.from(new Set(DDCont));
				var ContErr = 0; var ErrContName = "";
				for (var i = 0; i < ContArr.length; i++) {
					var ContEmdTotAmt = 0;
					var EmdContId = ContArr[i];
					var DDContId = ContDDArr[i];
					$(".EmAmt"+EmdContId).each(function(){
						var EmdAmt = $(this).val();  
						ContEmdTotAmt = parseFloat(ContEmdTotAmt) + parseFloat(EmdAmt); 
					});
					$(".DDAmt"+DDContId).each(function(){
						var DDAmt = $(this).val(); 
						ContDDTotAmt = parseFloat(ContEmdTotAmt) + parseFloat(DDAmt); 
					});
					var contamt = ContEmdTotAmt + ContDDTotAmt; // alert(contamt);
					if(parseFloat(contamt) < parseFloat(EmdAmount)){ 
						ErrContName = $("#cmb_bidder_0 option[value='"+EmdContId+"']").text(); 
						ContErr = 1;
					}
					//console.log(parseFloat(ContEmdTotAmt));
					//console.log(parseFloat(EmdAmount));
				}
				// for (var i = 0; i < ContArr.length; i++) {
				// 	var ContEmdTotAmt = 0;
				// 	var EmdContId = ContArr[i];
				// 	var DDContId = ContDDArr[i];
				// 	$(".EmAmt"+EmdContId).each(function(){
				// 		var EmdAmt = $(this).val();  
				// 		ContEmdTotAmt = parseFloat(ContEmdTotAmt) + parseFloat(EmdAmt); 
				// 	});
				// 	$(".DDAmt"+DDContId).each(function(){
				// 		var DDAmt = $(this).val(); 
				// 		ContDDTotAmt = parseFloat(ContEmdTotAmt) + parseFloat(DDAmt); 
				// 	});
				// 	var contamt = ContEmdTotAmt + ContDDTotAmt;  alert(contamt);
				// 	if(parseFloat(contamt) < parseFloat(EmdAmount)){ 
				// 		ErrContName = $("#cmb_bidder_0 option[value='"+EmdContId+"']").text(); 
				// 		ContErr = 1;
				// 	}
				// 	//console.log(parseFloat(ContEmdTotAmt));
				// 	//console.log(parseFloat(EmdAmount));
				// }
				

				if(ShortName == ""){
					BootstrapDialog.alert("Please select Tender Number..!!");
					event.preventDefault();
					event.returnValue = false;
				}else if(WorkName == ""){
					BootstrapDialog.alert("Please Enter Name of work..!!");
					event.preventDefault();
					event.returnValue = false;

				}else if(rowCount <= 2 ) {
					BootstrapDialog.alert(" Please Add Atleast One EMD Detail..!!");
					event.preventDefault();
					event.returnValue = false;
				}else if(ContErr == 1){
					BootstrapDialog.alert(" Total EMD Amount should be greater than or equal to "+EmdAmount+" for the bidder "+ErrContName);
					event.preventDefault();
					event.returnValue = false;
				}else{
					event.preventDefault();
					BootstrapDialog.confirm({
						title: 'Confirmation Message',
						message: 'Are you sure want to save this EMD Detail ?',
						closable: false, // <-- Default value is false
						draggable: false, // <-- Default value is false
						btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
						btnOKLabel: 'Ok', // <-- Default value is 'OK',
						callback: function(result) {
							if(result){
								KillEvent = 1;
								$("#submit").trigger( "click" );
							}else {
								KillEvent = 0;
							}
						}
					});
				}
			}
		});
		$('body').on("change",".ddselapp", function(e){ 
		var checkval = $('input[name="gstapplicable"]:checked').val();
		if(checkval == 'Y'){
			$(".gstapplicab").show();
		}else if(checkval == 'N'){
			$(".gstapplicab").hide();
		}
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

	});
</script>


