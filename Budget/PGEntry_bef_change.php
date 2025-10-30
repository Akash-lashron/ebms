<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require('SpreadsheetReader.php');
include "common.php";
checkUser();
$PageName = $PTPart1.$PTIcon.'PG Entry';
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
	$TenderNum 		= $_POST["cmb_tnder_no"];
	$LOIPGID        = $_POST["txt_loi_pgid"];
	$Contractorid	= $_POST["txt_contid"];
	$Emdinstypestr	= $_POST["cmd_instype"];
	$EmdBanchstr   	= $_POST["txt_branch_pg"];
	$Emdinstnumstr	= $_POST["instrunum"];
	$Emdbnamestr	= $_POST["txt_bankname_pg"];
	$Emddatestr		= $_POST["txt_date_pg"];
	$Emdexdatestr	= $_POST["txt_expir_date_pg"]; 
	$Emdextensiondatestr	= $_POST["txt_exten_date_pg"];
	$AmountListstr	= $_POST["txt_part_amt"];
	$DDinstnumstr	= $_POST["instrunum_DD"];
	$DDbnamestr     = $_POST["txt_bankname_pg_DD"]; 
	$DDbaddstr	 	= $_POST["txt_sno_pg_DD"];	
	$DDdatestr		= $_POST["txt_date_pg_DD"];
	$DDexdatestr	= $_POST["txt_expir_date_pg_DD"];
	$DDAmountListstr = $_POST["txt_part_amt_DD"]; 
	$DDCallannostr	 = $_POST["txt_challNo_pg_DD"];
	$DDCallandatstr    = $_POST["txt_challandate_pg_DD"]; 
	$DDrealDatstr	 	= $_POST["txt_Challanrealdate_pg_DD"];	
	$DDDraweBankstr		= $_POST["txt_draweebank_DD"];

	if($TenderNum == null){
		$msg = 'Error : Tender Number should not be empty..!!!';
	}else if($Emdinstnumstr == null ){
		$msg = 'Error : Please Add Atleast One Type';
	}else if(count($Emdinstnumstr) <= 0 ){
		$msg = 'Error : Please Add Atleast One Type';
	}else if(count($AmountListstr) <= 0 ){
		$msg = 'Error : Please Enter amount';
	}else{
		$InQueryCon = 1;
	}

		$GlobID= '';
			$SelectTSQuery = "SELECT globid FROM tender_register where tr_id = '$TenderNum'";
			$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
			if($SelectTSSql == true){
				if(mysqli_num_rows($SelectTSSql)>0){
					$CList = mysqli_fetch_object($SelectTSSql);
					$GlobID = $CList->globid;
		      }
	      }
		  $LOiID= '';
		  $SelectTSQuery = "SELECT loa_pg_id FROM loi_entry where tr_id = '$TenderNum'";
		  $SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
		  if($SelectTSSql == true){
			  if(mysqli_num_rows($SelectTSSql)>0){
				  $CList = mysqli_fetch_object($SelectTSSql);
				  $LOiID = $CList->loa_pg_id;
				 
			}
		}
		
		$BGDID= '';
		$SelectTSQuery = "SELECT master_id FROM bg_fdr_details where master_id = '$LOiID'";
		$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
		if($SelectTSSql == true){
			if(mysqli_num_rows($SelectTSSql)>0){
				$CList = mysqli_fetch_object($SelectTSSql);
				$BGDID = $CList->master_id;
			   
		  }
	  }

	  if($LOIPGID != null){ 
			$Deletequery    = "DELETE FROM bg_fdr_details WHERE globid='$GlobID' AND  master_id='$LOIPGID' AND inst_purpose='PG'";
			$BFDeletequery    = mysqli_query($dbConn,$Deletequery);	
			if(count($Emdinstnumstr)>0){
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
						$insert_query1	= "insert into bg_fdr_details set master_id='$LOIPGID',globid='$GlobID', contid='$Contractorid', inst_purpose='PG', inst_branch_name='$TrimBranc', inst_type='$Emdinstype',inst_serial_no='$TrimInstnum', inst_bank_name='$TrimBankname',
						inst_date='$Insertdate', inst_exp_date='$InsertExpdate', inst_ext_date='$Emdextendate', inst_amt='$TrimAmount', userid='$userid', inst_status='EIC', createdby='$staffid ',  created_section='EIC',  createdon= NOW() , active='1'";

						$insert_sql1 = mysqli_query($dbConn,$insert_query1);
				}
			}
			if(count($DDinstnumstr)>0){
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
	
	
	
					$insert_query1	= "insert into bg_fdr_details set master_id='$LOIPGID',globid='$GlobID', contid='$Contractorid', inst_purpose='PG', inst_branch_name='$TrimBranc', inst_type='DD',inst_serial_no='$TrimInstnum', inst_bank_name='$TrimBankname',
					inst_date='$Insertdate', inst_exp_date='$InsertExpdate',  inst_amt='$TrimAmount', userid='$userid', inst_status='EIC', createdby='$staffid ',  created_section='EIC',  createdon= NOW() ,ga_challan_no='$TrimChallnum', ga_challan_date='$Challandate', ga_realisation_date='$Realdate', 
					ga_drawee_bank='$TrimDrawBankname', active='1'"; 
					$insert_sql1 = mysqli_query($dbConn,$insert_query1);
				
					if($insert_sql1 == true){
						$msg = "PG Details Updated Successfully ";
						UpdateWorkTransaction($GlobID,0,0,"W","PG Details created by ".$UserId."","");
						$success = 1;
					}else{
						$msg = " PG Details Details Not Updated. Error...!!! ";
						UpdateWorkTransaction($GlobID,0,0,"W"," Tried to create PG Details by ".$UserId." but not created","");
						$success = 0;
					}
				}
			}
	    }else{

			if($Emdinstnumstr != null){
				if(count($Emdinstnumstr)>0){
					foreach($Emdinstnumstr as $Key => $Value){
					//	$EmdPur      	= $EmdPurstr[$Key];
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
						
						$insert_query1	= "insert into bg_fdr_details set master_id='$LOiID',globid='$GlobID', contid='$Contractorid', inst_purpose='PG',  inst_branch_name='$TrimBranc', inst_type='$Emdinstype',inst_serial_no='$TrimInstnum', inst_bank_name='$TrimBankname',
						inst_date='$Insertdate', inst_exp_date='$InsertExpdate', inst_ext_date='$Emdextendate', inst_amt='$TrimAmount', userid='$userid', inst_status='EIC', createdby='$staffid ',  created_section='EIC',  createdon= NOW() , active='1'";
						$insert_sql1 = mysqli_query($dbConn,$insert_query1);
					}
				}
				if(count($DDinstnumstr)>0){
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
		
		
		
						$insert_query1	= "insert into bg_fdr_details set master_id='$LOiID',globid='$GlobID', contid='$Contractorid', inst_purpose='PG', inst_branch_name='$TrimBranc', inst_type='DD',inst_serial_no='$TrimInstnum', inst_bank_name='$TrimBankname',
						inst_date='$Insertdate', inst_exp_date='$InsertExpdate',  inst_amt='$TrimAmount', userid='$userid', inst_status='EIC', createdby='$staffid ',  created_section='EIC',  createdon= NOW() ,ga_challan_no='$TrimChallnum', ga_challan_date='$Challandate', ga_realisation_date='$Realdate', 
						ga_drawee_bank='$TrimDrawBankname', active='1'"; 
						$insert_sql1 = mysqli_query($dbConn,$insert_query1);
					
						if($insert_sql1 == true){
							$msg = "PG Details Saved Successfully ";
							UpdateWorkTransaction($GlobID,0,0,"W","PG Details created by ".$UserId."","");
							$success = 1;
						}else{
							$msg = " PG Details Details Not Saved. Error...!!! ";
							UpdateWorkTransaction($GlobID,0,0,"W"," Tried to create PG Details by ".$UserId." but not created","");
							$success = 0;
							}
					}
				}
					//echo trim($AmountList);exit;
				
			  }
		  }
	}
	if(isset($_GET['id'])){   
		$LOIPGID 	 = $_GET['id'];
	
		$ContArr  	 =  array();
		$ContNameArr = array();
		$GlobID= '';
		$result =  "SELECT a.*, b.*, c.tr_no, c.work_name, c.ccno, c.eic, d.name_contractor FROM loi_entry a 
						INNER JOIN bg_fdr_details b ON (a.loa_pg_id = b.master_id) 
						INNER JOIN tender_register c ON (a.tr_id = c.tr_id) 
						INNER JOIN contractor d ON (a.contid = d.contid) WHERE b.inst_purpose='PG' AND c.eic='$staffid' AND a.loa_pg_id='$LOIPGID'
						ORDER BY a.tr_id ASC, a.contid ASC";
			$GlobIDSql 	= mysqli_query($dbConn,$result);
			if($GlobIDSql == true){
				if(mysqli_num_rows($GlobIDSql)>0){
					$List = mysqli_fetch_object($GlobIDSql);
					$GlobID     = $List->globid;
					$TrId       = $List->tr_id;
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
			
	//echo $_SESSION['userid'];exit;
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
												<div class="card-header inkblue-card" align="center">Performance Guarantee (BG/FDR/DD ) Entry</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="row clearrow"></div>
																<div class="row">
																   <div class="div3 dataFont">
																		Tender No.
																   </div>
																   <div class="div7 dataFont">
																       <select id="cmb_tnder_no" name="cmb_tnder_no" class="tboxsmclass">
																		 <option value="">--------------- Select --------------- </option>
																		 <?php echo $objBind->BindPGTrNo($TrId);?>
																	   </select>
																   </div>
															   </div>
															   <div class="row clearrow"></div>
															   <div class="row">
																  <div class="div3 dataFont">
																	  Name of Work
																  </div>
																  <div class="div7">
																	 <textarea name='txt_work_name' id='txt_work_name' class="tboxsmclass" readonly=""><?php if(isset($_GET['id'])!= ""){ echo $WorkName; } ?></textarea>
																 </div>
															   </div>
															   <div class="row clearrow"></div>
															   <input type="hidden" name='txt_loi_pgid'  id='txt_loi_pgid' readonly="" value="<?php if(isset($_GET['id'])!= ""){ echo $LOIPGID; } ?>">

															   <div class="row">
																   <div class="div3 dataFont">
																	  Bidder's Name
																   </div>
																   <div class="div7">
																      <input type="text" name='txt_bidder' id='txt_bidder' readonly class="tboxsmclass" value="<?php if(isset($_GET['id'])!= ""){ echo $Contname; } ?>"></td>
																       <input type="hidden" name='txt_contid' id='txt_contid' readonly class="tboxsmclass" value="<?php if(isset($_GET['id'])!= ""){ echo $ContId; } ?>"></td>
																   </div>
															   </div>
															   <div class="row clearrow"></div>
															   <div class="row">
																  <div class="div3 dataFont" >LOI No.</div>
																     <div class="div3 dataFont" align="left">
																	    <input type="text" name="txt_loi_no" id="txt_loi_no" readonly class="tboxsmclass"  value="<?php if(isset($_GET['id'])!= ""){ echo $LoiNum; } ?>">
																     </div>
																     <div class="div2 dataFont"> &emsp;&emsp;&emsp;LOI Date</div>
																     <div class="div3 dataFont" align="left">
																	     <input type="text" name="txt_loi_date" id="txt_loi_date"  readonly class="tboxsmclass"  value="<?php if(isset($_GET['id'])!= ""){ echo $LoiDat; } ?>">
																     </div>
																     <div class="row clearrow"></div>
																     <div class="row">
																        <div class="div3 dataFont">PG %</div>
																        <div class="div3" align="left">
																	       <input type="text" name="txt_pg_per" id="txt_pg_per" readonly class="tboxsmclass" value="<?php if(isset($_GET['id'])!= ""){ echo $pgpaer; } ?>">
																       </div>
																       <div class="div2 dataFont"> &emsp;&emsp;&emsp;PG Value</div>
																       <div class="div3" align="left">
																	        <input type="text" name="txt_pg_value" id="txt_pg_value" readonly class="tboxsmclass" value="<?php if(isset($_GET['id'])!= ""){ echo $PGVal; } ?>">
																       </div>
																       <div class="row clearrow isappcheck" style="display-none"></div>
																											
																	        <!--    2nd Div Starts Here   -->
																	   <div class="face-static">
																		    <div class="card-header inkblue-card" align="left">&nbsp;BG/FDR Details</div>
																			<div class="card-body padding-1">
																			   <div class="row clearrow"></div>		
																				<table class="dataTable etable " align="center" width="100%" id="pgtable1">
																					<tr class="label" style="background-color:#FFF">
																						<!-- <td align="center" >Purpose</td> -->
																						<th align="center">Instrument<br> Type</th>
																						<th align="center">Bank Name</th>
																						<th align="center">Branch </th>
																						<th align="center">BG/FDR Serial No.</th>
																						<th align="center">BG/FDR Date</th>
																						<th align="center">Expiry Date&nbsp;</th>
																						<th align="center">Extension Date</th>
																						<th align="center"> Amount ( &#8377; )</th>
																						<th align="center" >Action</th>
																					</tr>
																					<tr>
																						<!-- <td align="center">
																							<input type=text name="cmd_purposes_0" id ="cmd_purposes_0"  class="tboxsmclass" value="PG"></input>
																						</td> -->
																						<td align="center" style="width:20px;">
																							<select name="cmd_instype_0" id ="cmd_instype_0"   class="tboxsmclass">  
																								<option value="">-Select-</option>
																								<option value="BG">BG</option>
																								<option value="FDR">FDR</option>
																							</select>
																						</td>
																						<td align="center" style="width:250px;"><input type="text" class="tboxsmclass" data-index="0" name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
																						<td align="center" style="width:150px;"><input type="text" class="tboxsmclass" data-index="0" name="txt_branch_pg_0" id="txt_branch_pg_0"></td>
																						<td align="center"  style="width:120px;">
																						<input type="text" name="instrunum_0" id ="instrunum_0" data-index="0" class="tboxsmclass">
																						</td>
																						<td align="center" style="width:100px;"><input type="text" placeholder="DD/MM/YYYY" data-index="0" class="tboxsmclass date EmdDt ValDate" name="txt_date_pg_0" id="txt_date_pg_0"></td>
																						<td align="center" style="width:100px;"><input type="text" placeholder="DD/MM/YYYY"  class="tboxsmclass expdate ExpDt ValDate" data-index = '0' name="txt_expir_date_pg_0" id="txt_expir_date_pg_0"></td>
																						<td align="center" style="width:100px;"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass expdate Extndt ValDate"  data-index = '0' name="txt_exten_date_pg_0" id="txt_exten_date_pg_0"></td>
																						<td align="center" style="width:50px;"><input type="number" style="text-align:right;" class="tboxsmclass" data-index = '0' name="txt_part_amt_0" onKeyPress="return isPercentageValue(event,this);"  id="txt_part_amt_0"></td>
																						<td align="center"  style="width:50px;"><input type="button"  name="emp_add" id="emp_add"  value="ADD" class="btn btn-info" style="margin-top:0px;"></td>
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
																								$Emdexpirdat        = dt_display( $Result2->inst_exp_date);
																								$Emdextensiondat    = dt_display( $Result2->inst_ext_date) ;
																								$Emdamnt            = $Result2->inst_amt;
																								$Totalamt           =  $Emdamnt+$Totalamt
																								
																						
																					?>
																					<tr id="<?php echo $Index; ?>">
																					<td align="center "  style="width:20px;">
																						<select name="cmd_instype[]" id ="cmd_instype_<?php echo $Index; ?>" data-index="<?php echo $Index; ?>"  class="tboxsmclass">  
																								<option value="">-Select-</option>
																								<option value="BG"<?php if((isset($Result2->inst_type ))&&($Result2->inst_type == 'BG')){ echo 'selected="selected"'; } ?>>BG</option>
																								<option value="FDR" <?php if((isset($Result2->inst_type ))&&($Result2->inst_type == 'FDR')){ echo 'selected="selected"'; } ?>>FDR</option>
																						</select> 
																					</td>
																					<td align="left"  style="width:250px;"><input type="text" class="tboxsmclass"  data-index="<?php echo $Index; ?>"  name="txt_bankname_pg[]" id="txt_bankname_pg_<?php echo $Index; ?>" value="<?php echo $Bankname?>"></td>
																					<td align="left" style="width:150px;"><input type="text" class="tboxsmclass" data-index="<?php echo $Index; ?>" name="txt_branch_pg[]" id="txt_branch_pg_<?php echo $Index; ?>" value="<?php echo $Branchname?>"></td>
																					<td align="left" style="width:100px;">
																						<input type="text" name="instrunum[]"  id ="instrunum<?php echo $Index; ?>" value="<?php echo $Instrunum ?>" class="tboxsmclass">
																					</td>
																					<td align="center" style="width:80px;"><input type="text" align="left"  placeholder="DD/MM/YYYY" data-index="<?php echo $Index; ?>" class="tboxsmclass date EmdDt ValDate"  name="txt_date_pg[]" id="txt_date_pg_<?php echo $Index; ?>" value="<?php echo dt_display( $Result2->inst_date);?>"></td>
																					<td align="center" style="width:80px;"><input type="text" align="left"  placeholder="DD/MM/YYYY" data-index="<?php echo $Index; ?>" class="tboxsmclass expdate ExpDt ValDate" name="txt_expir_date_pg[]" id="txt_expir_date_pg_<?php echo $Index; ?>" value="<?php echo dt_display( $Result2->inst_exp_date);?>"></td>
																					<td align="center" style="width:80px;"><input type="text"  placeholder="DD/MM/YYYY" class="tboxsmclass expdate  Extndt ValDate" data-index="<?php echo $Index; ?>" name="txt_exten_date_pg[]" id="txt_exten_date_pg_<?php echo $Index; ?>"  value="<?php echo dt_display( $Result2->inst_ext_date);?>"></td>
																					<td align="center" style="width:100px;"><input type="text" style="text-align:right;"  maxlength="12" class="tboxsmclass EmAmt" data-index="<?php echo $Index; ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);" name="txt_part_amt[]" id="txt_part_amt_<?php echo $Index; ?>" value="<?php  echo $Emdamnt?>"></td>
																					<td><input type="button"  class="delete btn btn-info" name="emp_delete" id="emp_delete" data-id="<?php echo $Detailid; ?>" data-index="<?php echo $Index; ?>" value="DELETE"  style="margin-top:0px;"/>
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
																					<td align="center"><input type="button"  name="emp_DD_add" id="emp_DD_add"  value="ADD" class="btn btn-info" style="margin-top:0px;"></td>
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
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row clearrow"></div>												
												<div class="div12" align="center">
													<div class="row">
														<div class="div12" align="center">
															<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
															<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Save And Send to Accounts " />
															<a data-url="PGEntryView" class="btn btn-info" name="btn_view" id="btn_view">View</a>

														</div>
													</div> 
												</div>
												<div class="row clearrow"></div>												
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
	function goBack(){
			url = "PGandSD.php";
			window.location.replace(url);
		}
	$("#cmb_tnder_no").chosen();
	$("#cmb_engineer").chosen();
	var Index = "<?php echo $Index; ?>"		
	var msg = "<?php echo $msg; ?>";
	var DDIndex = "<?php echo $DDIndex; ?>"	
    document.querySelector('#top').onload = function(){
	if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						dialog.close();
						window.location.replace('PGEntry.php');
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
			var TrId = $(this).val();
			
			$("#txt_loi_date").val('');
			$("#txt_loi_no").val('');
			$("#txt_pg_amt").val('');
			$("#txt_pg_value").val('');
			$("#txt_work_name").val('');
			$("#txt_pg_per").val('');
			$("#txt_bidder").val('');
			$("#txt_contid").val('');
			$("#txt_pg_value").val('');
			$("#txt_branch_pg_0").val('');
			$("#cmd_instype_0").val('');
			$("#instrunum_0").val('');
			$("#txt_bankname_pg_0").val('');
			$("#txt_date_pg_0").val('');
			$("#txt_expir_date_pg_0").val('');
			$("#txt_part_amt_0").val('');
			$("#text_totalamt").val(''); 
			$("#txt_exten_date_pg_0").val('');
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
			// $.ajax({ 
			// 	type: 'POST', 
			// 	url: 'FindEstTsTrName.php', 
			// 	data: { Id: Id, Page: 'TR'}, 
			// 	dataType: 'json',
			// 	success: function (data) {  
			// 		if(data != null){ 
			// 			$("#txt_work_name").val(data.work_name);
			// 		}
			// 	}
			// });
		
			if(MastId != ""){
				$.ajax({ 
					type: 'POST', 
					url: 'FindBiddersNamePG.php', 
					data: { MastId: MastId }, 
					dataType: 'json',
					success: function (data) { 
					if(data != null){ 
						var WorkName 	= data['WorkName'];
						//var Totalamt = data.Totalamt;
						$("#txt_contid").val(data.contid);
						$("#txt_bidder").val(data.name_contractor);
						$("#txt_loi_no").val(data.loa_no);
						$("#txt_loi_date").val(data.loa_dt);
						$("#txt_pg_per").val(data.pg_per);
						//$("#txt_totalamt").val(Totalamt);
						$("#txt_pg_per").val(data.pg_per);
						$("#txt_pg_value").val(data.pg_amt);
						$("#txt_work_name").val(WorkName);
					  }
					}
				});
				
			}
			
		});
	});
	$("body").on("change", ".ValDate", function(event){  //alert(1)
	var DateIndex = $(this).attr("data-index");
	var DateofIssue  = $("#txt_date_pg_"+DateIndex).val(); 
	var DateofExpiry = $("#txt_expir_date_pg_"+DateIndex).val();
	var DateofExtension = $("#txt_exten_date_pg_"+DateIndex).val();
	if((DateofIssue != "") && (DateofExpiry != "") ){  
		var d1 = DateofExpiry.split("/");
		var d2 = DateofIssue.split("/");
		var d3 = DateofExtension.split("/");
		var emdexpdate = new Date(d1[2], d1[1]-1, d1[0]); //alert(emdexpdate);
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
			var Purpose 	 = $("#cmd_purposes_0").val();
			var InstType 	 = $("#cmd_instype_0").val();
			var BankName   	 = $("#txt_bankname_pg_0").val(); 
			var BranchName    = $("#txt_branch_pg_0").val(); 
			var InstNum 	 = $("#instrunum_0").val();
			var DateofIssue  = $("#txt_date_pg_0").val();
			var DateofExpiry = $("#txt_expir_date_pg_0").val();
			var DateofExtension = $("#txt_exten_date_pg_0").val();
			var AmtDetail	 = $("#txt_part_amt_0").val();//
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
			var RowStr = '<tr id="'+Index+'"><td><input type="text" name="cmd_instype[]" readonly  id="cmd_instype_'+Index+'"  data-index="'+Index+'" class="tboxsmclass"  value="'+InstType+'"></td><td><input type="text" readonly name="txt_bankname_pg[]" id="txt_bankname_pg_'+Index+'"  data-index="'+Index+'" class="tboxsmclass"  value="'+BankName+'"></td><td><input type="text" readonly name="txt_branch_pg[]" id="txt_branch_pg_'+Index+'"  data-index="'+Index+'" class="tboxsmclass"  value="'+BranchName+'"></td><td><input type="text" readonly name="instrunum[]"  id="instrunum_'+Index+'"  data-index="'+Index+'" class="tboxsmclass"  value="'+InstNum+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg_'+Index+'"  data-index="'+Index+'" class="tboxsmclass EmdDt ValDate"  value="'+DateofIssue+'"></td><td><input type="text"  readonly name="txt_expir_date_pg[]" id="txt_expir_date_pg_'+Index+'"  data-index="'+Index+'" class="tboxsmclass ExpDt ValDate"  value="'+DateofExpiry+'"></td><td><input type="text"  readonly name="txt_exten_date_pg_[]" id="txt_exten_date_pg_'+Index+'"  data-index="'+Index+'" class="tboxsmclass Extndt ValDate" value="'+DateofExtension+'"></td><td><input type="number" name="txt_part_amt[]" id="txt_part_amt_'+Index+'"  data-index="'+Index+'" readonly class="tboxsmclass EmAmt" style="text-align:right;"  data-index="'+Index+'" value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete btn btn-info"  data-index="'+Index+'" name="emp_delete" id="emp_delete" value="DELETE" style="margin-top:0px;"></td></tr>'; 
			if(InstType == 0){
				BootstrapDialog.alert("Please Select atleast one type");
				return false;
			}else if(InstNum == 0){
				BootstrapDialog.alert("Instrument Number should not be empty");
				return false;
			}else if(BankName == 0){
				BootstrapDialog.alert("Bank Name should not be empty");
				return false;
			}else if(BranchName == 0){
				BootstrapDialog.alert("Bank Name should not be empty");
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
				BootstrapDialog.alert("BG/FDR Expiry date is lesser than BG/FDR Date..Please Change..!!");
				return false;
			}else{
				$("#pgtable1").append(RowStr);
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
			$("#text_totalamt").val('');
			$(this).closest("tr").remove();
			TotalUnitAmountCalc();
			
			// var bababa = $(this).attr('data-id');	alert(bababa);
			// var partamt1 = $("#txt_part_amt"+bababa).val();
			// alert(partamt1);
		});
	// function TotalUnitAmountCalc(){
	// 				var TotalAmt = 0;
	// 				$(".EmAmt").each(function(){
	// 					var Amt = $(this).val(); 
	// 					TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt);
	// 					$("#text_totalamt").val(TotalAmt);
					
	// 				});
	// 			}

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
				RowStr +='<td align="center"><input type="button" data-DDindex="'+DDIndex+'" class="DDdelete btn btn-info" name="emp_DD_delete" id="emp_DD_delete" value="DELETE" style="margin-top:0px;"></td></tr>'; 

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
	// $("#btn_save").click(function(){ //alert(1);
	// 			var pgamt = $("#txt_pg_value").val(); 
	// 		    var totalamt = $("#text_totalamt").val();  
	
	// 			if(pgamt!=totalamt){
	// 				var a="PG Amount is not Equal to the Total BG/FDR Amout";
	// 				BootstrapDialog.alert(a);
	// 				event.preventDefault();
	// 				event.returnValue = false;
	// 			}else{
	// 				var a="";
	// 				//$('#val_date').text(a);
	// 			}
	// });

	$("body").on("click","#btn_save", function(event){
		var TotalAmt =0;
		var TotalEmdAmt =0;
		var TotalDDAmt =0;
			$(".EmAmt").each(function(){
				var Amt = $(this).val();  //alert(Amt);
			    TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt); //alert(TotalAmt);
			});
			// $(".DDAmt").each(function(){
			// 	var DDAmt = $(this).val(); 
			// 	TotalDDAmt = parseFloat(TotalAmt) + parseFloat(DDAmt); alert(TotalDDAmt);
			// });
			// TotalAmt  =TotalEmdAmt + TotalDDAmt; alert(TotalAmt);
			$("#text_totalamt").val(TotalAmt); 
			
					
		if(KillEvent == 0){
			var CheckVal = 0;
			    var MastId          = $("#cmb_tnder_no").val();
				var ShortName   	= $("#cmb_tnder_no").val();
				var WorkName 	    = $("#txt_workname").val();
				//var EnginnerName 	= $("#cmb_engineer").val();
				var BidderName 	    = $("#cmb_bidder").val();
				var LoINum	 	    = $("#txt_loi_no").val();
				var LoIDate 	    = $("#txt_loi_date").val();
				var EmdAmount 	    = $("#txt_full_emd_amt").val(); 
				var rowCount        = $('#pgtable1 tr').length;
				var pgamt           = $("#txt_pg_value").val(); 
			    var totalamt        = $("#text_totalamt").val();  
				var pgamt1           =Number(pgamt); //alert(pgamt);
			    var totalamt1       = Number(totalamt);
			
				if(ShortName == ""){
					BootstrapDialog.alert("Please select Tender Number..!!");
					event.preventDefault();
					event.returnValue = false;
				}else if(rowCount <= 2 ) {
					BootstrapDialog.alert(" Please Add Atleast One BG/FDR  Detail..!!");
					event.preventDefault();
					event.returnValue = false;
			   }else if(totalamt1 < pgamt1){
					BootstrapDialog.alert("Total BG/FDR/DD Amount  is not Equal to the PG Amount");
					event.preventDefault();
					event.returnValue = false;
				}else if(CheckVal ==  1){
					BootstrapDialog.alert("BG/FDR Expiry date is lesser than BG/FDR Date..Please Change..!!");
					return false;
				}else{
					event.preventDefault();
					
					$.ajax({ 
					type: 'POST', 
					url: 'GetEMDReturndetails.php', 
					data: { MastId: MastId }, 
					dataType: 'json',
					success: function (data) {
						if(data == null){
							event.preventDefault();
							BootstrapDialog.confirm({
								title: 'Confirmation Message',
								message: 'Are you sure want to save this PG Details ?',
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
						}else{
						var Result1 = data['row1']; 
						var Result2 = data['row2']; 
						var RowSpanContArr = data['row3']; 
						var PrevContId ="";
					
						var	BankStr  = "<table class='dataTable etable' align='center' width='200%' id='emdtable1'>";
							BankStr += "<tr class='label' style ='background-color:#FFF'>";
							BankStr += "<th align='center'>Bidder's Name</th>";
							BankStr += "<th align='center'>Instrument<br> Type</th>";
							BankStr += "<th align='center'>Instrument <br>Number</th>";
							BankStr += "<th align='center'> Bank Name</th>";
							BankStr += "<th align='center'>Branch</th>";
							BankStr += "<th align='center'>Date of <br>Issue</th>";
							BankStr += "<th align='center'>Expiry <br> Date</th>";
							BankStr += "<th align='center'>Amount<br> ( &#8377; )</th>";
							BankStr += "<th align='center'>Status</th>";
							var x2 = 0;
						
						if(data != null){ 
							$.each(Result2, function(index, element) {
									var ContID = element.contid;
									var contname = element.name_contractor;
									var RowSpan  = RowSpanContArr[ContID];
									if(PrevContId != ContID){
										x2 = 0;
									}
									if(x2 == 0){
									//alert (RowSpan);
									BankStr += "<tr>";
									BankStr +="<td align='left'  rowspan='"+RowSpan+"'>"+contname+"</td>";
									BankStr +="<td align='left'>"+element.inst_type+"</td>";
									BankStr +="<td align='left'>"+element.inst_no+"</td>";
									BankStr +="<td align='left'>"+element.bank_name+"</td>";
									BankStr +="<td align='left'>"+element.branch_addr+"</td>";
									BankStr +="<td align='left'>"+element.issue_dt+"</td>";
									BankStr +="<td align='left'>"+element.valid_dt+"</td>";
									BankStr +="<td align='left'>"+element.emd_amt+"</td>";
									BankStr +="<td align='left' rowspan='"+RowSpan+"'>Not Returned</td>";
									x2++;
									}else{
									BankStr += "<tr>";
									BankStr +="<td align='left'>"+element.inst_type+"</td>";
									BankStr +="<td align='left'>"+element.inst_no+"</td>";
									BankStr +="<td align='left'>"+element.bank_name+"</td>";
									BankStr +="<td align='left'>"+element.branch_addr+"</td>";
									BankStr +="<td align='left'>"+element.issue_dt+"</td>";
									BankStr +="<td align='left'>"+element.valid_dt+"</td>";
									BankStr +="<td align='left'>"+element.emd_amt+"</td>";
									x2++;
									}
									PrevContId = ContID; 
							});
								BankStr += "</table>";
								BootstrapDialog.confirm({
								title: 'EMD Not Returned Details',
								message: BankStr,
								closable: false, // <-- Default value is false
								draggable: false, // <-- Default value is false
								btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
								btnOKLabel: 'Ok',
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
					}
				});
		
			 }
			}
		});
	//});
		// 			BootstrapDialog.confirm({
		// 				title: 'Confirmation Message',
		// 				message: 'Are you sure want to save this BG/FDR Detail ?',
		// 				closable: false, // <-- Default value is false
		// 				draggable: false, // <-- Default value is false
		// 				btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
		// 				btnOKLabel: 'Ok', // <-- Default value is 'OK',
		// 				callback: function(result) {
		// 					if(result){
		// 						KillEvent = 1;
		// 						$("#btn_save").trigger( "click" );
		// 					}else {
		// 						KillEvent = 0;
		// 					}
		// 				}
		// 			});
		// 		}
		// 	}
		// });


</script>


