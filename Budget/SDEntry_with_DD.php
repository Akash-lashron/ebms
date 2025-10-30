<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require('php-excel-reader/excel_reader2.php');
require_once 'library/binddata.php';
require('SpreadsheetReader.php');
include "common.php";	
checkUser();
$PageName = $PTPart1.$PTIcon.'SD Entry';
$msg = ''; $success = '';
$staffid  = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];
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

if(isset($_POST['btn_save']) == " Save "){
	$LOIPGID        	= $_POST["txt_loi_pgid"];
	$Workname	    	= $_POST["cmb_shortname"];
	$SDPEr	        	= $_POST["txt_sd_per"];
	$SDAMT	        	= $_POST["txt_sd_value"];
	$Contractorid		= $_POST["txt_contid"];
	$EmdBanchstr   	= $_POST["txt_branch_pg"];
	$Emdinstypestr		= $_POST["cmd_instype"];
	$Emdinstnumstr		= $_POST["instrunum"];
	$Emdbnamestr		= $_POST["txt_bankname_pg"];
	$Emddatestr			= $_POST["txt_date_pg"];
	$Emdexdatestr		= $_POST["txt_expir_date_pg"];
	$Emdextensiondatestr	= $_POST["txt_exten_date_pg"];
	$AmountListstr		= $_POST["txt_part_amt"];
	$DDinstnumstr		= $_POST["instrunum_DD"];
	$DDbnamestr     	= $_POST["txt_bankname_pg_DD"]; 
	$DDbaddstr	 		= $_POST["txt_sno_pg_DD"];	
	$DDdatestr			= $_POST["txt_date_pg_DD"];
	$DDexdatestr		= $_POST["txt_expir_date_pg_DD"];
	$DDAmountListstr 	= $_POST["txt_part_amt_DD"]; 
	$DDCallannostr	 	= $_POST["txt_challNo_pg_DD"];
	$DDCallandatstr   = $_POST["txt_challandate_pg_DD"]; 
	$DDrealDatstr	 	= $_POST["txt_Challanrealdate_pg_DD"];	
	$DDDraweBankstr	= $_POST["txt_draweebank_DD"];
	

	if($Workname == null){
		$message = 'Error : Please Select Work Short Name..!!!';
	}else if(count($Emdinstnumstr) <= 0 ){
		$message = 'Error : Please Add Atleast One Type';
	}else{
		$InQueryCon = 1;
	}
	$GlobID= '';
	$SelectTSQuery = "SELECT globid FROM sheet where sheet_id = '$Workname'";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			$CList = mysqli_fetch_object($SelectTSSql);
			$GlobID = $CList->globid;
		}
	}
	if($LOIPGID != null){ 
		$Deletequery   = "DELETE FROM bg_fdr_details WHERE globid='$GlobID' AND master_id='$LOIPGID' AND inst_purpose='SD'";
		$BFDeletequery = mysqli_query($dbConn,$Deletequery);
		if($Emdinstnumstr != null){
			foreach($Emdinstnumstr as $Key => $Value){
				$Emdinstype  	= $Emdinstypestr[$Key];
				$Emdinstnum  	= $Emdinstnumstr[$Key];
				$Emdbname    	= $Emdbnamestr[$Key];
				$EmdBanch    	= $EmdBanchstr[$Key];
				$Emddate     	= $Emddatestr[$Key];
				$Emdexdate   	= $Emdexdatestr[$Key];
				$Emdextendate  = $Emdextensiondatestr[$Key];
				$AmountList    = $AmountListstr[$Key];

				$TrimBankname 	= trim($Emdbname);
				$TrimBranc 	   = trim($EmdBanch);
				$TrimInstnum 	= trim($Emdinstnum);
				$TrimAmount 	= trim($AmountList);
				$Insertdate 	= dt_format($Emddate);
				$InsertExpdate = dt_format($Emdexdate);
				$Insertextendate = dt_format($Emdextendate);

				$insert_query1	= "INSERT INTO bg_fdr_details SET master_id='$Workname',globid='$GlobID', contid='$Contractorid', inst_purpose='SD', inst_branch_name='$TrimBranc', inst_type='$Emdinstype',inst_serial_no='$TrimInstnum', inst_bank_name='$TrimBankname',
				inst_date='$Insertdate', inst_exp_date='$InsertExpdate', inst_ext_date='$Insertextendate', inst_amt='$TrimAmount', userid='$userid', inst_status='EIC' ,createdby='$staffid',  created_section='EIC',  createdon= NOW() , active='1'";
				$insert_sql1 = mysqli_query($dbConn,$insert_query1);

				$update_query1	="UPDATE works SET sd_perc='$SDPEr', sd_amt ='$SDAMT' WHERE globid = '$GlobID' AND  sheetid = '$Workname'";
				$update_sql1 = mysqli_query($dbConn,$update_query1);
			}
		}
		if($DDinstnumstr != null){
			foreach($DDinstnumstr as $Key => $Value){
				$DDCont     		= $DDContstr[$Key];
				//$Emdinstype    	= $Emdinstypestr[$Key];
				$DDinstnum    		= $DDinstnumstr[$Key];
				$DDbname      		= $DDbnamestr[$Key];
				$DDbadd       		= $DDbaddstr[$Key];
				$DDdate       		= $DDdatestr[$Key];
				$DDexdate     		= $DDexdatestr[$Key];
				$AmountList     	= $DDAmountListstr[$Key];
				$DDChallannum   	= $DDCallannostr[$Key];
				$DDChalldate    	= $DDCallandatstr[$Key];
				$DDChallRealate 	= $DDrealDatstr[$Key];
				$DDDrawbname    	= $DDDraweBankstr[$Key];
				
				$TrimBankname 		= trim($DDbname);
				$TrimBranc 	    	= trim($DDbadd);
				$TrimInstnum 		= trim($DDinstnum);
				$TrimAmount 		= trim($AmountList);
				$Insertdate 		= dt_format($DDdate);
				$InsertExpdate 	= dt_format($DDexdate);
				
				$TrimDrawBankname = trim($DDDrawbname);
				$TrimChallnum 	   = trim($DDChallannum);
				$Challandate 	   = dt_format($DDChalldate);
				$Realdate 	      = dt_format($DDChallRealate);

				$insert_query1	= "INSERT INTO bg_fdr_details SET master_id='$Workname',globid='$GlobID', contid='$Contractorid', inst_purpose='SD', 
				inst_branch_name='$TrimBranc', inst_type='DD',inst_serial_no='$TrimInstnum', inst_bank_name='$TrimBankname', 
				inst_date='$Insertdate', inst_exp_date='$InsertExpdate', inst_amt='$TrimAmount', userid='$userid', inst_status='EIC', 
				createdby='$staffid ', created_section='EIC', createdon= NOW(), ga_challan_no='$TrimChallnum', ga_challan_date='$Challandate', 
				ga_realisation_date='$Realdate', ga_drawee_bank='$TrimDrawBankname', active='1'"; 
				$insert_sql1 = mysqli_query($dbConn,$insert_query1);
			}
		}
		if($insert_sql1 == true){
			$msg = "SD Details Updated Successfully ";
			$success = 1;
		}else{
			$msg = " SD Details Details Not Updated. Error...!!! ";
			//echo trim($AmountList);exit;
		}
	}else{		
		if($Emdinstnumstr != null){
			foreach($Emdinstnumstr as $Key => $Value){
				$Emdinstype    	= $Emdinstypestr[$Key];
				$Emdinstnum    	= $Emdinstnumstr[$Key];
				$Emdbname      	= $Emdbnamestr[$Key];
				$EmdBanch      	= $EmdBanchstr[$Key];
				$Emddate       	= $Emddatestr[$Key];
				$Emdexdate     	= $Emdexdatestr[$Key];
				$Emdextendate   = $Emdextensiondatestr[$Key];
				$AmountList     = $AmountListstr[$Key];

				$TrimBankname 	= trim($Emdbname);
				$TrimBranc 	    = trim($EmdBanch);
				$TrimInstnum 	= trim($Emdinstnum);
				$TrimAmount 	= trim($AmountList);
				$Insertdate 	= dt_format($Emddate);
				$InsertExpdate 	= dt_format($Emdexdate);
				$Insertextendate	= dt_format($Emdextendate);
				$insert_query1	= "insert into bg_fdr_details set master_id='$Workname',globid='$GlobID', contid='$Contractorid', inst_purpose='SD', 
				inst_branch_name='$TrimBranc', inst_type='$Emdinstype',inst_serial_no='$TrimInstnum', inst_bank_name='$TrimBankname',
				inst_date='$Insertdate', inst_exp_date='$InsertExpdate', inst_ext_date='$Insertextendate', inst_amt='$TrimAmount', userid='$userid', 
				inst_status='EIC' ,createdby='$staffid',  created_section='EIC',  createdon= NOW() , active='1'";
				$insert_sql1 = mysqli_query($dbConn,$insert_query1);

				$update_query1	="UPDATE works SET  sd_perc='$SDPEr', sd_amt ='$SDAMT' WHERE globid = '$GlobID' AND  sheetid = '$Workname'";
				$update_query1Sql = mysqli_query($dbConn,$update_query1);
			}
		}
		if($DDinstnumstr != null){
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

				$insert_query1	= "INSERT INTO bg_fdr_details SET master_id='$Workname', globid='$GlobID', contid='$Contractorid', inst_purpose='SD', 
				inst_branch_name='$TrimBranc', inst_type='DD', inst_serial_no='$TrimInstnum', inst_bank_name='$TrimBankname', 
				inst_date='$Insertdate', inst_exp_date='$InsertExpdate', inst_amt='$TrimAmount', userid='$userid', inst_status='EIC', createdby='$staffid ', 
				created_section='EIC', createdon= NOW() , ga_challan_no='$TrimChallnum', ga_challan_date='$Challandate', ga_realisation_date='$Realdate', 
				ga_drawee_bank='$TrimDrawBankname', active='1'"; 
				$insert_sql1 = mysqli_query($dbConn,$insert_query1);
			}
		}
		if($insert_sql1 == true){
			$msg = "SD Details Saved Successfully ";
			$success = 1;
		}else{
			$msg = " SD Details Details Not Saved. Error...!!! ";
		}
	}
}
if(isset($_GET['id'])){
	$LOIPGID 	 = $_GET['id'];
	$ContArr  	 =  array();
	$ContNameArr = array();
	$GlobID= '';
	$result =  "SELECT a.*, b.name_contractor, c.*, d.sd_perc, d.sd_amt FROM sheet a 
	INNER JOIN  contractor b ON (a.contid = b.contid)  
	INNER JOIN bg_fdr_details c ON (a.sheet_id = c.master_id)
	INNER JOIN works d ON (a.sheet_id = d.sheetid) WHERE c.inst_purpose='SD'  AND c.inst_status !='R' AND c.master_id='$LOIPGID'";
	$GlobIDSql 	= mysqli_query($dbConn,$result);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobID     = $List->globid;
			$SheetId     = $List->sheet_id;
			$WorkName = $List->work_name;
			$WorkCost = $List->work_order_cost;
			$ContId   = $List->contid;
			$Contname = $List->name_contractor;
			$sd_perc  = $List->sd_perc;
			$SDVal    =round(($List->sd_amt),0);
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
	function goBack(){
			url = "Home.php";
			window.location.replace(url);
		}
</script>
<style>
	.head-b {
		background: #136BCA;
		border-color: #136BCA;
	}
	/* .lboxlabel {
  color: #04498E;
  text-align: left;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 11px;
  font-weight: bold;
} */
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
												<div class="card-header inkblue-card" align="center">SD (BG/FDR/DD) Entry</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="row clearrow"></div>	
																<div class="row">
																<div class="div3 lboxlabel"> 
																		Work Short Name
																</div>
																<div class="div7">
																<select id="cmb_shortname" name="cmb_shortname" class="tboxsmclass">
																		<option value="">--------------- Select --------------- </option>
																		<?php echo $objBind->BindWorkOrderNoSD($SheetId);?>
																	</select>
																</div>
															</div>
															<div class="row clearrow"></div>
															<div class="row">
												           <div class="div3 lboxlabel">
													          Work Order No.
												           </div>
												           <div class="div7">
													         <textarea name='txt_order_num' id='txt_order_num' class="tboxsmclass" readonly=""><?php if(isset($_GET['id'])!= ""){ echo $WorkName; } ?></textarea>
												             </div>
											             </div>
											             <div class="row clearrow"></div>
											             <div class="row">
														 <input type="hidden" name='txt_loi_pgid'  id='txt_loi_pgid' readonly="" value="<?php if(isset($_GET['id'])!= ""){ echo $LOIPGID; } ?>">
											                <div class="div3 lboxlabel">Work Order Cost (&#8377;)</div>
												             <div class="div3" align="left">
													          <input type="text" name="txt_Wo_Cost" id="txt_Wo_Cost" readonly value="<?php if(isset($_GET['id'])!= ""){ echo $WorkCost; } ?>" class="tboxsmclass">
												        </div>
												        <div class="div2 lboxlabel"> &emsp;&emsp;Bidder's Name</div>
												        <div class="div3" align="left">
															<input type="text" name='txt_bidder' id='txt_bidder' readonly class="tboxsmclass" value="<?php if(isset($_GET['id'])!= ""){ echo $Contname; } ?>"></td>
															<input type="hidden" name='txt_contid' id='txt_contid' readonly class="tboxsmclass" value="<?php if(isset($_GET['id'])!= ""){ echo $ContId; } ?>"></td>
												        </div>
														<div class="row clearrow"></div>
														<div class="row">
														<div class="div3 lboxlabel" >SD %</div>
														<div class="div3" align="left">
													        <input type="text" name="txt_sd_per" id="txt_sd_per"  class="tboxsmclass" value="<?php if(isset($_GET['id'])!= ""){ echo $sd_perc; } ?>">
												        </div>
												        <div class="div2 lboxlabel"> &emsp;&emsp;SD Value (&#8377;)</div>
												        <div class="div3" align="left">
													        <input type="text" name="txt_sd_value" onKeyPress="return isPercentageValue(event,this);"  id="txt_sd_value" value="<?php if(isset($_GET['id'])!= ""){ echo $SDVal; } ?>" readonly class="tboxsmclass">
												        </div>
																<div class="row clearrow isappcheck" style="display-none"></div>
																<div class="row clearrow"></div>														
																	<!--    2nd Div Starts Here   -->
																		<div class="card-header inkblue-card" align="left">&nbsp; BG/FDR Details</div>
																		<table class="dataTable etable " align="center" width="100%" id="pgtable1">
																			<tr class="label" style="background-color:#FFF">
																				<th align="center">Instrument <br>Type</th>
																				<th align="center">Bank Name</th>
																				<th align="center">Branch </th>
																				<th align="center">BG/FDR Serial No.</th>
																				<th align="center">BG/FDR Date</th>
																				<th align="center">Expiry Date&nbsp;</th>
																				<th align="center">Extension Date</td>
																				<th align="center"> Amount ( &#8377; )</th>
																				<th align="center" >Action</th>
																		</tr>
																		<tr>
																		<!-- <td align="center">
																	       <select name="cmd_purposes_0" id ="cmd_purposes_0" style="width:155px" class="tboxsmclass">
																					<option value="">- Select BG Purpose-</option>
																					<option value="PG">PG</option>
																					<option value="SD">SD</option>
																					<option value="SA">SA</option>
																					<option value="MOB1">MOB 1</option>
																					<option value="MOB2">MOB 2</option>
																					<option value="MOB3">MOB 3</option>
																					<option value="MOB4">MOB 4</option>
																					<option value="MOB5">MOB 5</option>
																					<option value="MOB6">MOB 6</option>
																					<option value="MOB7">MOB 7</option>
																					<option value="MOB8">MOB 8</option>
																					<option value="MOB9">MOB 9</option>
																			 </select> 
																				</td> -->
																				<td align="center" style="width:50px;">
																					<select name="cmd_instype_0" id ="cmd_instype_0"  class="tboxsmclass">  
																						<option value="">-Select- </option>
																						<option value="BG">BG</option>
																						<option value="FDR">FDR</option>
																					</select>
																				</td>
																				<td align="center"  style="width:250px;"><input type="text" class="tboxsmclass"  name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
																				<td align="center" style="width:150px;"><input type="text" class="tboxsmclass"  name="txt_branch_pg_0" id="txt_branch_pg_0"></td>

																				<td align="center" style="width:120px;">
																					<input type="text" name="instrunum_0" id ="instrunum_0" class="tboxsmclass" >
																				</td>
																				<td align="center" style="width:100px;" ><input type="text" placeholder="DD/MM/YYYY" data-index = '0' class="tboxsmclass date EmdDt ValDate" name="txt_date_pg_0" id="txt_date_pg_0"></td>
																				<td align="center" style="width:100px;" ><input type="text" placeholder="DD/MM/YYYY" data-index = '0' class="tboxsmclass expdate ExpDt ValDate"  name="txt_expir_date_pg_0" id="txt_expir_date_pg_0"></td>
																				<td align="center" style="width:100px;" ><input type="text" placeholder="DD/MM/YYYY" data-index = '0' class="tboxsmclass expdate Extndt ValDate"  name="txt_exten_date_pg_0" id="txt_exten_date_pg_0"></td>
																				<td align="center" style="width:50px;"><input type="number" style="text-align:right;" class="tboxsmclass" onKeyPress="return isPercentageValue(event,this);"  name="txt_part_amt_0" id="txt_part_amt_0"></td>
																				<td align="center" style="width:50px;"><input type="button"  name="emp_add" id="emp_add"  value="ADD" class=" btn btn-info"></td>
																					<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
																			</tr>
																			<?php
																					$Index = 1;
																					$SelectQuery2 = "SELECT * FROM bg_fdr_details where  master_id='$LOIPGID' AND inst_type !='DD'  order by master_id asc";
																					//echo $SelectQuery2;exit;
																					$ResultQuery2 = mysqli_query($dbConn,$SelectQuery2);
																					if($ResultQuery2 == true){
																						if(mysqli_num_rows($ResultQuery2)>0){
																							$Totalamt = 0;
																							while($Result2 = mysqli_fetch_object($ResultQuery2)){
																								$Instatype  		= $Result2->inst_type;
																								$Detailid 		    = $Result2->bfdid  ;
																								$Instrunum   	    = $Result2->inst_serial_no;
																								$Bankname    	    = $Result2->inst_bank_name;
																								$Branchname   	    = $Result2->inst_branch_name;
																								$Emnddate   	    = dt_display( $Result2->inst_date);
																								$Emdexpirdat        = dt_display( $Result2->inst_exp_date) ;
																								$Emdextensiondat    = dt_display( $Result2->inst_ext_date) ;
																								$Emdamnt            = $Result2->inst_amt;
																								$Totalamt           =  $Emdamnt+$Totalamt
																					?>
																					<tr id="<?php echo $Index; ?>">
																					<td align="center "  style="width:20px;">
																						<select name="cmd_instype[]" id ="cmd_instype_<?php echo $Index;?>" data-index="<?php echo $Index; ?>"  class="tboxsmclass">  
																								<option value="">-Select-</option>
																								<option value="BG"<?php if((isset($Result2->inst_type ))&&($Result2->inst_type == 'BG')){ echo 'selected="selected"'; } ?>>BG</option>
																								<option value="FDR" <?php if((isset($Result2->inst_type ))&&($Result2->inst_type == 'FDR')){ echo 'selected="selected"'; } ?>>FDR</option>
																						</select> 
																					</td>
																					<td align="left"  style="width:250px;"><input type="text" class="tboxsmclass" data-index="<?php echo $Index; ?>"  name="txt_bankname_pg[]" id="txt_bankname_pg_<?php echo $Index; ?>" value="<?php echo $Bankname?>"></td>
																					<td align="left" style="width:150px;"><input type="text" class="tboxsmclass" data-index="<?php echo $Index; ?>" name="txt_branch_pg[]" id="txt_branch_pg_<?php echo $Index; ?>" value="<?php echo $Branchname?>"></td>
																					<td align="left" style="width:100px;">
																						<input type="text" name="instrunum[]"  id ="instrunum<?php echo $Index; ?>" data-index="<?php echo $Index; ?>" value="<?php echo $Instrunum ?>" class="tboxsmclass">
																					</td>
																					<td align="center" style="width:80px;"><input type="text" align="left"  placeholder="DD/MM/YYYY" data-index="<?php echo $Index; ?>" class="tboxsmclass date EmdDt ValDate"  name="txt_date_pg[]" id="txt_date_pg_<?php echo $Index; ?>" value="<?php echo dt_display( $Result2->inst_date);?>"></td>
																					<td align="center" style="width:80px;"><input type="text" align="left"  placeholder="DD/MM/YYYY" data-index="<?php echo $Index; ?>" class="tboxsmclass expdate ExpDt ValDate" name="txt_expir_date_pg[]" id="txt_expir_date_pg_<?php echo $Index; ?>" value="<?php echo dt_display( $Result2->inst_exp_date);?>"></td>
																					<td align="center" style="width:80px;"><input type="text"  placeholder="DD/MM/YYYY" class="tboxsmclass expdate Extndt ValDate "data-index="<?php echo $Index; ?>" name="txt_exten_date_pg[]" id="txt_exten_date_pg_<?php echo $Index; ?>" value="<?php echo dt_display( $Result2->inst_ext_date);?>"></td>
																					<td align="center" style="width:100px;"><input type="text" style="text-align:right;"  maxlength="12" data-index="<?php echo $Index; ?>" class="tboxsmclass EmAmt" onKeyPress="return isNumberWithTwoDecimal(event,this);" name="txt_part_amt[]" id="txt_part_amt_<?php echo $Index; ?>" value="<?php  echo $Emdamnt?>"></td>
																					<td><input type="button"  class="delete btn btn-info" name="emp_delete" id="emp_delete" data-id="<?php echo $Detailid; ?>" data-index="<?php echo $Index; ?>" value="DELETE" />
																				</tr>
																				<?php
																				   $Index++;
																					}
																					}
																					}
																					?>
																					<input type="hidden" name="text_totalamt" id ="text_totalamt" class="totamtcl textbox-new" style="width:110px;" value="<?php  echo $Totalamt?>">
																			</table>      
																			</div>
																			<div class="row clearrow"></div>
																			<div class="card-header inkblue-card " align="left">&nbsp;DD/Banker's Cheque Details</div>
																			<table class="dataTable etable table-responsive " align="center" width="100%" id="emdtable2">
																				<tr class="label" style="background-color:#FFF">
																					<!-- <th align="center">Bidder's Name</th> -->
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
																					<td align="center"><input type="text" style="text-align:right;" maxlength="12" class="tboxsmclass" onKeyPress="return isNumberWithTwoDecimal(event,this);" name="txt_part_amt_DD_0" id="txt_part_amt_DD_0"></td>
																					<td align="center"><input type="text" class="tboxsmclass" maxlength="100" name="txt_challNo_pg_DD_0" id="txt_challNo_pg_DD_0"></td>
																					<td align="center"><input type="text" placeholder="DD/MM/YYYY" readonly="" data-index = '0' class="tboxsmclass date EmdDDt" name="txt_challandate_pg_DD_0" id="txt_challandate_pg_DD_0"></td>
																					<td align="center"><input type="text" placeholder="DD/MM/YYYY" readonly="" data-index = '0' class="tboxsmclass expdate ExpDDt " name="txt_Challanrealdate_pg_0" id="txt_Challanrealdate_pg_0"></td>
																					<td align="center"><input type="text"  class="tboxsmclass" name="txt_draweebank_DD_0" id="txt_draweebank_DD_0"></td>
																					<td align="center"><input type="button"  name="emp_DD_add" id="emp_DD_add"  value="ADD" class="btn btn-info"></td>
																				</tr>
																			
																				<!-- For Update Function -->
																					<?php
																						$DDIndex = 1;
																						$SelectQuery3 ="SELECT * FROM bg_fdr_details where  master_id='$LOIPGID' AND inst_type ='DD'  order by master_id asc";
																						//echo $SelectQuery3;exit;
																						$ResultQuery3 = mysqli_query($dbConn,$SelectQuery3);
																						if($SelectQuery3 == true){
																									if(mysqli_num_rows($ResultQuery3)>0){
																										while($Result2 = mysqli_fetch_object($ResultQuery3)){
																											$contid  		    = $Result2->contid;
																											//$Instatype  		= $Result2->inst_type;
																											$Detailid 		    = $Result2->bfdid  ;
																											$DDinstnum   	    = $Result2->inst_serial_no;
																											$DDbname    	    = $Result2->inst_bank_name;
																											$DDBranc   	        = $Result2->inst_branch_name;
																											$DDdate   	        = dt_display($Result2->inst_date);
																											$DDexdate           = dt_display($Result2->inst_exp_date) ;
																											$AmountList         = $Result2->inst_amt;
																											$DDChallannum    	= $Result2->ga_challan_no;
																											$DDChalldate        = dt_display($Result2->ga_challan_date) ;
																											$DDChallRealate   	= dt_display($Result2->ga_realisation_date);
																											$DDDrawBank  	    = $Result2->ga_drawee_bank;
																						?>
																						<tr id="<?php echo $DDIndex; ?>">
																							<td align="center">
																							<input type="text" name="instrunum_DD[]" id ="instrunum_DD_<?php echo $DDIndex; ?>" value="<?php echo $DDinstnum ?>" class="tboxsmclass">
																								<input type="hidden" name="cmd_detail_DD[]"  id ="cmd_detail_DD_<?php echo $DDIndex; ?>" class="tboxsmclass" value="<?php echo $Detailid ?>">
																							</td>
																							<td align="center"><input type="text" class="tboxsmclass"  name="txt_bankname_pg_DD[]" id="txt_bankname_pg_DD_<?php echo $DDIndex; ?>" data-DDindex="<?php echo $DDIndex; ?>" value="<?php echo $DDbname?>"></td>
																								<td align="center"><input type="text" class="tboxsmclass" name="txt_sno_pg_DD[]" id="txt_sno_pg_DD_<?php echo $DDIndex; ?>" data-DDindex="<?php echo $DDIndex; ?>" value="<?php echo $DDBranc?>"></td>
																								<td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass date EmdDt ValDate"  name="txt_date_pg_DD[]" id="txt_date_pg_DD_<?php echo $DDIndex; ?>" data-DDindex="<?php echo $Index; ?>" value="<?php echo $DDdate;?>"></td>
																								<td align="center"><input type="text"  placeholder="DD/MM/YYYY" class="tboxsmclass expdate ExpDt ValDate" name="txt_expir_date_pg_DD[]" id="txt_expir_date_pg_DD_<?php echo $DDIndex; ?>" data-DDindex="<?php echo $Index; ?>" value="<?php echo $DDexdate;?>"></td>
																								<td align="center"><input type="text" style="text-align:right;" maxlength="12" class="tboxsmclass EmAmt"  data-id="<?php echo $contid; ?>" data-DDindex="<?php echo $DDIndex; ?>"  onKeyPress="return isNumberWithTwoDecimal(event,this);" name="txt_part_amt_DD[]" id="txt_part_amt_DD_<?php echo $DDIndex; ?>" value="<?php  echo $AmountList?>"></td>
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
																<div class="row clearrow"></div>												
																<div class="div12" align="center">
																	<div class="row">
																		<div class="div12" align="center">
																			<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
																			<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value="  Save And Send to Accounts " />
																			<!-- <a data-url="SDEntryView" class="btn btn-info" name="btn_view" id="btn_view">View</a> -->
																		</div>
																	</div> 
																</div>
															</div>
														</div>
													</div>
												</div>
												<!-- <div class="row clearrow"></div>-->
												<!-- <div class="row clearrow"></div>-->
											</div>
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
	var Index = "<?php echo $Index; ?>"	
	var DDIndex = "<?php echo $DDIndex; ?>"	
	
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	document.querySelector('#top').onload = function(){
	if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ', 
					action: function(dialog) { 
						dialog.close();
						window.location.replace('SDEntry.php');
					}
				}]
			});
		}
};
	var KillEvent = 0;	
	$(document).ready(function(){ 
		$("body").on("change","#cmb_shortname", function(event){
			var Id = $(this).val(); 
			$("#text_totalamt").val('');
			$("#txt_order_num").val('');
			$("#txt_Wo_Cost").val('');
			$("#txt_sd_per").val('');
			$("#txt_bidder").val('');
			$("#txt_contid").val('');
			$("#txt_sd_value").val('');
			$("#cmd_purposes_0").val('');
			$("#cmd_instype_0").val('');
			$("#instrunum_0").val('');
			$("#txt_bankname_pg_0").val('');
			$("#txt_branch_pg_0").val('');
			$("#txt_date_pg_0").val('');
			$("#txt_expir_date_pg_0").val('');
			$("#txt_part_amt_0").val('');
			$("#txt_exten_date_pg_0").val(''); 
			$("#text_totalamt").val('');
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
				data: { Id: Id, Page: 'SD'}, 
				dataType: 'json',
				success: function (data) {  
					if(data != null){ 
						$("#txt_work_name").val(data.work_name);
						$("#txt_order_num").val(data.work_order_no);
						$("#txt_bidder").val(data.name_contractor);
						$("#txt_contid").val(data.contid);
						$("#txt_Wo_Cost").val(data.work_order_cost);
					}
				}
			});
			if(Id!= ""){
				$.ajax({ 
					type: 'POST', 
					url: 'FindBiddersNameSD.php', 
					data: { Id: Id }, 
					dataType: 'json',
					success: function (data) { 
						if(data != null){ 
							$("#txt_sd_per").val(data.sd_per);
							var sdper = $("#txt_sd_per").val();  		//alert(sdper);
							var workcost = $("#txt_Wo_Cost").val();  //alert(workcost);
							var SDvalue = (Number(sdper) / 100)*Number(workcost); 	//alert(SDvalue);
							var Sdvalue1 = SDvalue.toFixed(2);
							$("#txt_sd_value").val(Sdvalue1); 
						}
					}
				});
			}
		});
	});
	$("body").on("change", ".ValDate", function(event){ // alert(1)
	var DateIndex = $(this).attr("data-index");
	var DateofIssue  = $("#txt_date_pg_"+DateIndex).val(); 
	var DateofExpiry = $("#txt_expir_date_pg_"+DateIndex).val();
	var DateofExtension = $("#txt_exten_date_pg_"+DateIndex).val();
	if((DateofIssue != "") && (DateofExpiry != "") ){  
		var d1 = DateofExpiry.split("/");
		var d2 = DateofIssue.split("/");
		var d3 = DateofExtension.split("/");
		var emdexpdate = new Date(d1[2], d1[1]-1, d1[0]); 		//alert(emdexpdate);
		var emddate = new Date(d2[2], d2[1]-1, d2[0]);
		var extensiondate = new Date(d3[2], d3[1]-1, d3[0]);  //alert(emddate);
		if(emdexpdate<emddate){
			var a="BG/DD/FDR Expiry date should be greater than BG/DD/FDR Date";
			BootstrapDialog.alert(a);
			$(this).val('');
			event.preventDefault();
			event.returnValue = false;
			//CheckVal = 1;
		//  }else if((extensiondate<emdexpdate)){
		// 	var a="BG/DD/FDR Extennsion date should be greater than BG/DD/FDR Expiry Date";
		// 	BootstrapDialog.alert(a);
		// 	$(this).val('');
		// 	event.preventDefault();
		// 	event.returnValue = false;
		// 	//CheckVal = 1;

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
			var InstType 	 = $("#cmd_instype_0").val();
			var BankName   	 = $("#txt_bankname_pg_0").val();
			var BranchName    = $("#txt_branch_pg_0").val(); 
			var InstNum 	 = $("#instrunum_0").val();
			var DateofIssue  = $("#txt_date_pg_0").val();
			var DateofExpiry = $("#txt_expir_date_pg_0").val();
			var AmtDetail	 = $("#txt_part_amt_0").val(); 
			var DateofExtension = $("#txt_exten_date_pg_0").val();	//alert(AmtDetail);
			var RowStr = '<tr id="'+Index+'"><td><input type="text" readonly name="cmd_instype[]"  id="cmd_instype_'+Index+'" data-index="'+Index+'" class="tboxsmclass"  value="'+InstType+'"></td><td><input type="text" readonly  name="txt_bankname_pg[]" id="txt_bankname_pg_'+Index+'" class="tboxsmclass" data-index="'+Index+'"  value="'+BankName+'"></td><td><input type="text" readonly name="txt_branch_pg[]" id="txt_branch_pg_'+Index+'" class="tboxsmclass" data-index="'+Index+'" value="'+BranchName+'"></td><td><input type="text" readonly name="instrunum[]"  id="instrunum_'+Index+'" data-index="'+Index+'" class="tboxsmclass"  value="'+InstNum+'"></td><td><input type="text" readonly  name="txt_date_pg[]" id="txt_date_pg_'+Index+'" class="tboxsmclass date EmdDt ValDate" data-index="'+Index+'" value="'+DateofIssue+'"></td><td><input type="text" readonly  name="txt_expir_date_pg[]" id="txt_expir_date_pg_'+Index+'"  data-index="'+Index+'" class="tboxsmclass expdate ExpDt ValDate"  value="'+DateofExpiry+'"></td><td><input type="text"  readonly name="txt_exten_date_pg_[]" id="txt_exten_date_pg_'+Index+'"  data-index="'+Index+'" class="tboxsmclass expdate Extndt ValDate" value="'+DateofExtension+'"></td><td><input type="number"  readonly name="txt_part_amt[]" id="txt_part_amt'+Index+'"  data-index="'+Index+'"  class="tboxsmclass EmAmt" style="text-align:right;"  value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete btn btn-info" name="emp_delete" id="emp_delete"  data-index="'+Index+'" value="DELETE"></td></tr>'; 
			

			if(InstType == 0){
				alert("Instrument Type should not be empty");
				return false;
			}else if(InstNum == 0){
				alert("Instrument Number should not be empty");
				return false;
			}else if(BankName == 0){
				alert("Bank Name should not be empty");
				return false;
			}else if(DateofIssue == 0){
				alert("Date of Issue should not be empty");
				return false;
			}else if(DateofExpiry == 0){
				alert("Date of Expiry should not be empty");
				return false;
			}else if(AmtDetail == 0){
				alert("Amount should not be empty");
				return false;
			}else if(CheckVal ==  1){
				BootstrapDialog.alert("BG/FDR Expiry date is lesser than BG/FDR Date..Please Change..!!");
				return false;
			}else{
				$("#pgtable1").append(RowStr);
				$("#cmd_purposes_0").val('');
				$("#cmd_instype_0").val('');
				$("#instrunum_0").val('');
				$("#txt_bankname_pg_0").val('');
				$("#txt_branch_pg_0").val(''); 
				$("#txt_date_pg_0").val('');
				$("#txt_expir_date_pg_0").val('');
				$("#txt_part_amt_0").val('');
				$("#txt_exten_date_pg_0").val(''); 
				$("#text_totalamt").val('');
				Index++;
			}
			TotalUnitAmountCalc();

		});
		$("body").on("click", ".delete", function(){
			$(this).closest("tr").remove();
			TotalUnitAmountCalc();
			$("#text_totalamt").val('');
		});
		function TotalUnitAmountCalc(){
						var TotalAmt = 0;
						$(".EmAmt").each(function(){
							var Amt = $(this).val(); 
							TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt);
							$("#text_totalamt").val(TotalAmt);
						
						});
					}
		$('#cmb_tr_no').chosen();
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


		

			var RowStr = '<tr id="'+DDIndex+'">';
			   // RowStr +='<td><input type="text" name="cmd_instype[]" data-DDindex="'+DDIndex+'" id="cmd_instype_'+DDIndex+'"  readonly class="tboxsmclass" value="'+InstType+'"></td>';
				RowStr +='<td><input type="text" name="instrunum_DD[]" data-DDindex="'+DDIndex+'" id="instrunum_DD_'+DDIndex+'" readonly class="tboxsmclass" value="'+InstNum+'"></td>';
				RowStr +='<td><input type="text" name="txt_bankname_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_bankname_pg_DD_'+DDIndex+'" readonly class="tboxsmclass"  value="'+BankName+'"></td>';
				RowStr +='<td><input type="text" readonly name="txt_sno_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_sno_pg_DD_'+DDIndex+'" class="tboxsmclass" value="'+BankAddress+'"></td>';
				RowStr +='<td><input type="text" readonly name="txt_date_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_date_pg_DD_'+DDIndex+'" class="tboxsmclass EmdDt ValDate" value="'+DateofIssue+'"></td>';
				RowStr +='<td><input type="text" readonly name="txt_expir_date_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_expir_date_pg_DD_'+DDIndex+'" class="tboxsmclass ExpDt ValDate" value="'+DateofExpiry+'"></td>';
				RowStr +='<td><input type="number" data-DDindex="'+DDIndex+'" readonly onKeyPress="return isNumberWithTwoDecimal(event,this);" name="txt_part_amt_DD[]" id="txt_part_amt_DD_'+DDIndex+'" class="tboxsmclass EmAmt"  style="text-align:right;" value="'+AmtDetail+'"></td>';
				RowStr +='<td><input type="text" data-DDindex="'+DDIndex+'" readonly  name="txt_challNo_pg_DD[]" id="txt_challNo_pg_DD_'+DDIndex+'" class="tboxsmclass"  style="text-align:right;" value="'+ChallanNum+'"></td>';
				RowStr +='<td><input type="text" readonly name="txt_challandate_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_challandate_pg_DD_'+DDIndex+'" class="tboxsmclass EmdDDt ValDDDate" value="'+ChallanDate+'"></td>';
				RowStr +='<td><input type="text" readonly name="txt_Challanrealdate_pg_DD[]" data-DDindex="'+DDIndex+'" id="txt_Challanrealdate_pg_DD_'+DDIndex+'" class="tboxsmclass expdate ExpDDt ValDDDate" value="'+RealisatDate+'"></td>';
				RowStr +='<td><input type="text" data-DDindex="'+DDIndex+'" readonly  name="txt_draweebank_DD[]" id="txt_draweebank_DD_'+DDIndex+'" class="tboxsmclass"  style="text-align:right;" value="'+DraweeBank+'"></td>';
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
				BootstrapDialog.alert("Challan Number should not be empty");
				return false;
			}else if(ChallanDate == 0){
				BootstrapDialog.alert("Challan Date should not be empty");
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
		$("body").on("click","#btn_save", function(event){
			var TotalAmt =0;
			$(".EmAmt").each(function(){
				var Amt = $(this).val(); 
				TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt); //alert(TotalAmt);
			});
			$("#text_totalamt").val(TotalAmt);
			if(KillEvent == 0){
			var ShortName     	   = $("#cmb_shortname").val();
			var SDPErc	           = $("#txt_sd_per").val();
			var pgamt              =  $("#txt_sd_value").val();
			var pgamt2             = Math.round( pgamt ); 
			var totalamt           = $("#text_totalamt").val();   
			var rowCount           = $('#pgtable1 tr').length; 
			var pgamt1             =Number(pgamt2);// alert(pgamt);
			var totalamt1          = Number(totalamt); //alert(totalamt);
			if(ShortName == ""){
				BootstrapDialog.alert("Please select Name of Work..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(SDPErc == ""){
				BootstrapDialog.alert("Please Enter SD Percentage..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(rowCount <= 2 ) {
				BootstrapDialog.alert(" Please Add Atleast One BG/FDR  Detail..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(totalamt1 < pgamt2){
					BootstrapDialog.alert(" Total BG/FDR/DD Amount  is not Equal to the SD Amount");
					event.preventDefault();
					event.returnValue = false;
			}else{
					event.preventDefault();
					BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to save this SD  ?',
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
	$( ".date" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		yearRange: "2000:+15",
		maxDate: new Date,
		defaultDate: new Date,
	});
	$( ".expdate" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		yearRange: "2000:+25",
		defaultDate: new Date,
	});

	$('#txt_sd_per').change(function() {
		var SDper= $(this).val(); //alert(SDper);
		var Workvalue = $("#txt_Wo_Cost").val(); 
		$("#txt_sd_value").val('');
			var SDvalue= ((Number(SDper) / 100) *Number(Workvalue)).toFixed(2);
			//var Round = round((SDvalue),0); alert(Round);
			$("#txt_sd_value").val(SDvalue); 
	});

</script>





