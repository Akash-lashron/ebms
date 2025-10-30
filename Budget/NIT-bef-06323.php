<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'NIT Entry';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0; $InQueryCon =0;
$UserId  = $_SESSION['userid'];
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
$TsId = "";
$DefValuesQuery = "SELECT * FROM default_values";
$DefValuesSql 	= mysqli_query($dbConn,$DefValuesQuery);
if($DefValuesSql == true){
	if(mysqli_num_rows($DefValuesSql)>0){
		$DefList = mysqli_fetch_object($DefValuesSql);
		$DefEMDPerc = $DefList->emd;
	}
}
if(isset($_POST['btn_save']) == ' Save '){
	//$project_id = mysql_insert_id($insert_query);
	$cmb_ts_number		= $_POST["cmb_ts_number"];
	$txt_tender_no		= $_POST["txt_tender_no"];
	//$txt_entry_date	= $_POST["txt_entry_date"];
	$txt_work_name		= $_POST["txt_work_name"];
	$txt_tech_est		= trim($_POST["txt_tech_est"]);
	$txt_tender_cost	= trim($_POST["txt_tender_cost"]);
	$txt_emd_amt		= trim($_POST["txt_emd_amt"]);
	$txt_emd_per		= trim($_POST["txt_emd_perc"]);
	$txt_sd_value		= trim($_POST["txt_sd_value"]);
	$txt_pbg_value		= trim($_POST["txt_pbg_value"]);
	$txt_time_month		= $_POST["txt_time_month"];
	$staffname      	= trim($_POST['text_staffid']);

	if($txt_tender_cost == NULL){
		$txt_tender_cost = 0;
	}

	if($cmb_ts_number == NULL){
		$msg = "Please Select Technical Sanction Number..!!";
	}else if($txt_tender_no == NULL){
		$msg = "Please Enter Tender Number..!!";
	}else if($txt_work_name == NULL){
		$msg = "Please Enter Work Name..!!";
	}else if($txt_tech_est == NULL){
		$msg = "Please Enter Technical Estimate..!!";
	}else if($txt_tender_cost == NULL){
		$msg = "Please Enter Cost of Tender..!!";
	}else if($txt_emd_amt == NULL){
		$msg = "Please Enter EMD Amount..!!";
	}else if($txt_sd_value == NULL){
		$msg = "Please Enter SD Value..!!";
	}else if($txt_pbg_value == NULL){
		$msg = "Please Enter PBG Value..!!";
	}else if($txt_time_month == NULL){
		$msg = "Please Enter Time Allowed..!!";
	}else{
		$InQueryCon = 1;
	}
	// $EstId = 0;
	// $SelectQuery2 	= "select est_id from technical_sanction where ts_id = '$cmb_ts_number'";
	// $SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
	// if($SelectSql2 == true){
	// 	if(mysqli_num_rows($SelectSql2)>0){
	// 		$List2 = mysqli_fetch_object($SelectSql2);
	// 		$EstId = $List2->est_id;
	// 	}
	// }

	//$cmb_approve_auth	= $_POST["cmb_approve_auth"];
	if($InQueryCon == 1){

		$GlobID= '';
		$GlobIdQuery = "SELECT globid FROM technical_sanction where ts_id = '$cmb_ts_number'";
		$GlobIdSql 	= mysqli_query($dbConn,$GlobIdQuery);
		if($GlobIdSql == true){
			if(mysqli_num_rows($GlobIdSql)>0){
				$List = mysqli_fetch_object($GlobIdSql);
				$GlobID = $List->globid;
			}
		}

		$GlobInTsID= '';
		$GlobIdQuery = "SELECT globid FROM tender_register where ts_id = '$cmb_ts_number'";
		//echo $GlobIdQuery;exit;
		$GlobIdSql 	= mysqli_query($dbConn,$GlobIdQuery);
		if($GlobIdSql == true){
			if(mysqli_num_rows($GlobIdSql)>0){
				$List = mysqli_fetch_object($GlobIdSql);
				$GlobInTsID = $List->globid;
			}
		}
		
		$TRID= '';
		$GlobIdQuery = "SELECT tr_id FROM tender_register where ts_id = '$cmb_ts_number'";
		//echo $GlobIdQuery;exit;
		$GlobIdSql 	= mysqli_query($dbConn,$GlobIdQuery);
		if($GlobIdSql == true){
			if(mysqli_num_rows($GlobIdSql)>0){
				$List = mysqli_fetch_object($GlobIdSql);
				$TRID = $List->tr_id;
			}
		}//echo $GlobID;exit;
		if (($GlobInTsID != Null)|| ($TRId  != Null)){
			$update_query	= "UPDATE works SET tr_workname='$txt_work_name',  tr_no='$txt_tender_no', tr_id='$TRID', work_status='NIT', eic='$staffname ', emd_perc='$txt_emd_per', tr_amount='$txt_tech_est', sd_perc='$txt_sd_value'
							   WHERE globid = '$GlobInTsID'";
			//echo $update_query;exit;
			$update_query_sql = mysqli_query($dbConn,$update_query);
			$update_query2	=  "UPDATE partab_master SET tr_id='$TRID' WHERE globid = '$GlobInTsID'";
			$update_query_sql2 = mysqli_query($dbConn,$update_query2);
			$update_query1	= "UPDATE tender_register SET globid = '$GlobInTsID', ts_id='$cmb_ts_number', tr_no='$txt_tender_no', work_name='$txt_work_name', 
								tr_est='$txt_tech_est', tr_cost='$txt_tender_cost', emd='$txt_emd_amt', emd_perc='$txt_emd_per', sd_per='$txt_sd_value', eic='$staffname ', pg_per='$txt_pbg_value', 
								time_month='$txt_time_month', active = '1', created_by = '$UserId', created_date = NOW()
							    WHERE globid = '$GlobInTsID'";
			//echo $update_query1;exit;
			$update_query_sql = mysqli_query($dbConn,$update_query1);
			if($update_query_sql == true){
				$msg = "NIT Entry Successfully Updated..!!";
				UpdateWorkTransaction($GlobInTsID,0,0,"W","NIT details Updated by ".$UserId."","");
			}else{
				$msg = "Error: NIT Entry Not Updated..!!";
				UpdateWorkTransaction($GlobInTsID,0,0,"W","NIT details Tried to Update by ".$UserId." but not Updated","");
			}
	
		}else{

			$insert_query	= "insert into tender_register set globid = '$GlobID', ts_id='$cmb_ts_number',  tr_no='$txt_tender_no', work_name='$txt_work_name', 
			tr_est='$txt_tech_est', tr_cost='$txt_tender_cost', emd='$txt_emd_amt', sd_per='$txt_sd_value', eic='$staffname ', emd_perc='$txt_emd_per', pg_per='$txt_pbg_value', 
			time_month='$txt_time_month', active = '1', created_by = '$UserId', created_date = NOW()";
			$insert_sql = mysqli_query($dbConn,$insert_query); 
			$LastInsertid = mysqli_insert_id($dbConn);
			$update_query	= "UPDATE works SET tr_workname='$txt_work_name', tr_no='$txt_tender_no', work_status='NIT', tr_id='$LastInsertid', eic='$staffname ', tr_amount='$txt_tech_est', emd_perc='$txt_emd_per', sd_perc='$txt_sd_value' WHERE globid = '$GlobID'";
		  //echo $update_query;exit;
			$update_query_sql = mysqli_query($dbConn,$update_query);
			$update_query2	= "UPDATE partab_master SET tr_id='$LastInsertid' WHERE globid = '$GlobID'";
			$update_query_sql2 = mysqli_query($dbConn,$update_query2);
			if($insert_sql == true){
				$msg = "NIT Entry Successfully Saved..!!";
				UpdateWorkTransaction($GlobID,0,0,"W","NIT details Created by ".$UserId."","");
			}else{
				$msg = "Error: NIT Entry Not Saved..!!";
				UpdateWorkTransaction(0,0,0,"W","NIT details Tried to Create by ".$UserId." but not Created","");
			}
       }
    }
	//echo $msg;
	//echo $insert_sql;exit;
}
if(isset($_POST['btn_Del'])){
	$TrId    		  = $_POST["txt_tender_id"];
	$GlobInTsID    	  = $_POST["txt_glob_id"];
	$DeleteQuery1	  = "UPDATE works SET tr_workname='', tr_no='', work_status='TS', tr_id='', eic='', tr_amount='', emd_perc='', sd_perc='' WHERE globid = '$GlobInTsID'"; 
	$update_query_sql = mysqli_query($dbConn,$DeleteQuery1);
	$DeleteQuery2	=  "DELETE FROM tender_register WHERE tr_id='$TrId'";
	$update_query_sql = mysqli_query($dbConn,$DeleteQuery2);
			if($update_query_sql == true){
				$msg = "NIT Deleted Successfully..!!";
				UpdateWorkTransaction($GlobID,0,0,"W","NIT details Deleted  by ".$UserId,"");
			}else{
				$msg = "Error: NIT Not Deleted..!!";
				UpdateWorkTransaction($GlobID,0,0,"W","NIT details Tried to Deleted by "+$UserId+" but not Updated","");			
			}
 }
if(isset($_GET['id'])){   
	$TRId 	 = $_GET['id'];

	$ContArr  	 =  array();
	$ContNameArr = array();
	$GlobID= '';
	$GlobIDQuery = "SELECT * FROM tender_register WHERE tr_id = '$TRId'";
	$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobID  = $List->globid;
			$TsId     = $List->ts_id;
			$Tennum   = $List->tr_no;
			$Tenest  = $List->tr_est;
			$WorkName = $List->work_name;
			$Tencost = $List->tr_cost;
			$Emdper   =$List->emd_perc;
			$pg = $List->pg_per;
			$sd = $List->sd_per;
			$month = $List->time_month;
			$emd =$List->emd;
			$EICid =$List->eic; 
		}
	}
	if($GlobID != ''){
		$TsanctionQuery = "SELECT * FROM technical_sanction WHERE globid = '$GlobID'";
		$TsanctionQuerySql 	= mysqli_query($dbConn,$TsanctionQuery);
		if($TsanctionQuerySql == true){
			if(mysqli_num_rows($TsanctionQuerySql)>0){
				$TSList = mysqli_fetch_object($TsanctionQuerySql);
				$TsAmount = $TSList->ts_amount;
			}
		}	
	}
	$Degignation ='';
	$SelectQuery 	= "select staff.*, designation.designationname from staff JOIN designation ON staff.designationid = designation.designationid where staff.staffid = '$EICid'";
	$SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$List = mysqli_fetch_object($SelectSql);
			$staffname = $List->staffname;
			$eicid  = $List->staffid;
			$Desgnation = $List->designationname;
			$Email = $List->email;
			$Icno = $List->staffcode;
		}
	}
	   
	
}
$RowCount = 0; 
$FinaQuery = "SELECT tr_id FROM emd_master WHERE tr_id = '$TRId'";
$FinaResult = mysqli_query($dbConn,$FinaQuery);
if($FinaResult == true){
	if(mysqli_num_rows($FinaResult)>0){
		$RowCount = 1; 
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }

	function ViewNITList(){
		url = "NITViewEdit.php";
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
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<blockquote class="bq1" style="overflow:auto">
						<div class="row">
							<div class="box-container box-container-lg" align="center">
								<div class="div1">&nbsp;</div>
								<div class="div10">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&emsp;NIT - Entry<span class="subheadtext">* Minimum data required for Budget & Accounts Section</span></div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="row">
														<div class="row clearrow"></div>
												        	<div class="div3 lboxlabel">Technical Sanction Number</div>
																<div class="div6" align="left">
																	 <select name="cmb_ts_number" id="cmb_ts_number" class="tboxsmclass">
																		<option value=""> ---------- Select ---------- </option>
																		<?php echo $objBind->BindTechnicalNo($TsId); ?>
																	</select>
																</div>
																<?php
																if($RowCount==1){
																?>
																<div class="div3 lboxlabel " id="complete">
																	&emsp;<i class="fa fa-check-circle-o" style="font-size:20px; color:#EA253C;"></i> <span style="color:EA253C; top:-4px; position:relative;">EMD Details Entered</span>
																</div>
																<?php
																}
																?>	
																</div>									
																<div class="row clearrow"></div>
																<div class="div3 lboxlabel">Tender Number</div>
																<div class="div9" align="left">
																     <input type="text" name="txt_tender_no" id="txt_tender_no" maxlength="50" class="tboxsmclass" style="width:99%" value="<?php if($_GET['id'] != ""){ echo $Tennum; } ?>">
																     <input type="hidden" name="txt_tender_id" id="txt_tender_id" maxlength="50" class="tboxsmclass" style="width:99%" value="<?php if($_GET['id'] != ""){ echo $TRId; } ?>">											
																	 <input type="hidden" name="txt_glob_id" id="txt_glob_id" maxlength="50" class="tboxsmclass" style="width:99%" value="<?php if($_GET['id'] != ""){ echo $GlobInTsID; } ?>">					</div>
																	 
																<div class="row clearrow"></div>
																<div class="div3 lboxlabel">Name Of Work</div>
																<div class="div9" align="left">
																	<textarea name="txt_work_name" id="txt_work_name" class="tboxsmclass" maxlength="250"><?php if($_GET['id'] != ""){ echo $WorkName; } ?></textarea>
																</div>
																<div class="row clearrow"></div>
																<div class="div3 lboxlabel">Tender Estimate (Rs.)</div>
																<div class="div3" align="left">
																	<input type="text" name="txt_tech_est" id="txt_tech_est" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $Tenest; } ?>">
																	<input type="hidden" name="txt_techsanc_est" id="txt_techsanc_est" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $TsAmount; } ?>">
																</div>
																<div class="div3 lboxlabel">&emsp;Cost of Tender</div>
																<div class="div3" align="left">
																	<input type="text" name="txt_tender_cost" id="txt_tender_cost"  maxlength="12"   onKeyPress="return isNumberWithTwoDecimal(event,this);" value="<?php if($_GET['id'] != ""){ echo $Tencost; } ?>" class="tboxsmclass">
																</div>

																<div class="row clearrow"></div>
																<div class="div3 lboxlabel">
																	EMD&nbsp;
																	<input type="text"  name="txt_emd_perc" id="txt_emd_perc" style="width:30px; text-align:right" onKeyPress="return isPercentageValue(event,this);" class="tboxsmclass" value="<?php if(isset($DefEMDPerc)){ echo $DefEMDPerc; } else if($_GET['id'] != ""){ echo $Emdper; }  ?>" size="1">
																	(%)  (Rs.)
																</div>
																<div class="div3">
																	<input type="text" name="txt_emd_amt" id="txt_emd_amt" readonly  onKeyPress="return isNumberWithTwoDecimal(event,this);" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $emd; } ?>">
																</div>
																<div class="div3 lboxlabel">&emsp;SD (% of Tender Value)</div>
																<div class="div3" align="left">
																	<input type="text" name="txt_sd_value" id="txt_sd_value" onKeyPress="return isPercentageValue(event,this);" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $sd; } ?>">
																</div>
																<div class="row clearrow"></div>

																<div class="div3 lboxlabel">PBG (% of Tender Value)</div>
																<div class="div3" align="left">
																	<input type="text" name="txt_pbg_value" id="txt_pbg_value"  onKeyPress="return isPercentageValue(event,this);" class="tboxsmclass"value="<?php if($_GET['id'] != ""){ echo $pg; } ?>">
																</div>

																<div class="div3 lboxlabel">&emsp;Time Allowed in Months</div> 
																<div class="div1" align="left">
																	<input type="text" name="txt_time_month"  maxlength="3" id="txt_time_month"  onKeyPress="return isIntegerValue(event,this);" class="tboxsmclass"  value="<?php if($_GET['id'] != ""){ echo $month; } ?>">
																</div>	
																<div class="div2" align="left">
																&emsp;<span style="font-size:10px">(Max. 3 digit)</span>
																</div>	
																<div class="row clearrow"></div>
																<div class="div3 lboxlabel">Engineer Incharge</div>
																<div class="div3" align="left">
																<input type="text" name="cmb_staffid" id="cmb_staffid" readonly class="tboxsmclass"  value="<?php if($_GET['id'] != ""){ echo $staffname; } ?>">
																<input type="hidden" name="text_staffid" id="text_staffid" readonly class="tboxsmclass"  value="<?php if($_GET['id'] != ""){ echo $eicid; } ?>">
																</div>
																<div class="div3 lboxlabel"> &emsp;IC.No</div>
																<div class="div3" align="left">
																   <input type="text" name="txt_icno" readonly id="txt_icno" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $Icno; } ?>">
																</div>
																<div class="row clearrow"></div>
																<div class="div3 lboxlabel">Designation</div>
																<div class="div3" align="left">
																	<input type="text" name="txt_staffrole" readonly id="txt_staffrole" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $Desgnation; } ?>">
																</div>	
																<div class="div3 lboxlabel">&emsp;Engineer Email</div>
																<div class="div3" align="left">
																   <input type="text" name="txt_email" readonly id="txt_email" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $Email; } ?>">
																</div>
																<div class="row clearrow"></div>
																<div class="row" align="center">
																<input type="button" class="btn btn-info" name="Back" id="Back" value="Back" onClick="goBack();"/>
																<?php
																if($RowCount==1){
																?>
																<?php
																}else if(($RowCount==0)&&($_GET['id'] != "")){
																?>
																<input type="submit" class="btn btn-info" name="btn_Del" id="btn_Del" value=" Delete " /> 
																<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Update " />
																<?php
																}else{
																?>
																<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Save " />
																<?php
																}
																?>
																<!-- <input type="button" class="btn btn-info" name="btn_view" id="btn_view" value="View" onClick="ViewNITList();"/> -->

																</div>											
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
												
									</div>
							<div class="div1">&nbsp;</div>
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
$(document).ready(function(){
	$("#cmb_ts_number").chosen();
	$("#btn_view").click(function(event){ 
		var WorkName 		= $("#cmb_work_name").val(); 
		if(WorkName == ""){ 
			BootstrapDialog.alert("Please Select Name of Work.");
			event.preventDefault();
			event.returnValue = false;
		}
	});
	$('body').on("change","#cmb_ts_number", function(e){ 
		var Id = $(this).val(); 
		
		var EmdPerc = $("#txt_emd_perc").val();
		$("#txt_work_name").val('');
		$("#txt_tech_est").val('');
		$("#txt_emd_amt").val('');
		$("#txt_tender_no").val('');
	    $("#txt_tender_cost").val('');
		$("#txt_emd_amt").val('');
		$("#txt_sd_value").val('');
		$("#txt_pbg_value").val('');
		$("#txt_time_month").val('');
		$("#cmb_staffid").val(''); 
		$("#txt_staffrole").val(''); 
		$("#txt_email").val(''); 
		$("#txt_techsanc_est").val('');
		$.ajax({  
			type: 'POST', 
			url: 'FindEstTsTrName.php', 
			data: { Id: Id, Page: 'TS'}, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					var WorkFullName	= data['WROKNAME'];
					var TsAMT           = data['TsAMT'];
					var EmdAmt          = EmdPerc*TsAMT/100;
					var EnggId  		= data['ENGGID'];
					var EnggName  		= data['ENGGNAME'];
					var EnggICno  		= data['ENGGICNO'];
					var EnggDesig 		= data['ENGGDESIG'];
					var EnggEmail 		= data['ENGGEmail'];
					$("#txt_work_name").val(WorkFullName);
					$("#txt_tech_est").val(TsAMT);
					$("#txt_techsanc_est").val(TsAMT);
					$("#txt_tender_cost").val(0);
					if(Id != ""){
						$("#txt_emd_amt").val(EmdAmt);
					}else{
						$("#txt_emd_amt").val('');
					}
					
					$("#text_staffid").val(EnggId);
					$("#cmb_staffid").val(EnggName);
					$("#txt_staffrole").val(EnggDesig);
					$("#txt_icno").val(EnggICno);
					$("#txt_email").val(EnggEmail);
				}
			}
		});
	});
	
	$('body').on("change","#txt_emd_perc", function(e){ 
		var EmdPerc = $("#txt_emd_perc").val();
		var TsAMT=	$("#txt_tech_est").val();
		var EmdAmt = EmdPerc*TsAMT/100;
		$("#txt_emd_amt").val(EmdAmt);
	});
});

$('body').on("change","#txt_tech_est", function(e){ 
	var TenEstVal 	= $("#txt_tech_est").val();
	var TsAMT		=	$("#txt_techsanc_est").val();
	if(TenEstVal > TsAMT){
		BootstrapDialog.alert("Tender Estimate Amount Should Not Be Greater than Technical Sanction Amount..!!");
		e.preventDefault();
		e.returnValue = false;
		$("#txt_tech_est").val('');
	}
});
var KillEvent = 0;
$("body").on("click","#btn_Del", function(event){
	if(KillEvent == 0){
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to Delete this NIT ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
						if(result){
							KillEvent = 1;
							$("#btn_Del").trigger( "click" );
						}else {
							KillEvent = 0;
						}
					}
				});
	      }
   });
$("body").on("click","#btn_save", function(event){
	if(KillEvent == 0){
		var TsNumberVal	    = $("#cmb_ts_number").val();
		var TrNumberVal 	= $("#txt_tender_no").val();
		var TrWorkNameVal	= $("#txt_work_name").val();
		var TrTechEstVal	= $("#txt_tech_est").val();
		var TrCostVal		= $("#txt_tender_cost").val();
		var TrEmdAmtVal  	= $("#txt_emd_amt").val();
		var TrSDPercVal	    = $("#txt_sd_value").val();
		var TrPBGPercVal	= $("#txt_pbg_value").val();
		var TrNoMonthsVal	= $("#txt_time_month").val();
		var TreEngname	    = $("#cmb_staffid").val();
	
		if(TsNumberVal == ""){
			BootstrapDialog.alert("Please Select Technical Sanction Number..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrNumberVal == ""){
			BootstrapDialog.alert("Tender Number Should Not Be Empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrWorkNameVal == ""){
			BootstrapDialog.alert("Name of Work should not be empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrTechEstVal == ""){
			BootstrapDialog.alert("Tender Estimate Amount should not be empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrCostVal == ""){
			BootstrapDialog.alert("Cost of Tender should not be empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrEmdAmtVal == ""){
			BootstrapDialog.alert("EMD Amount should not be empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrSDPercVal == ""){
			BootstrapDialog.alert("SD Percentage of Tender should not be empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrPBGPercVal == ""){
			BootstrapDialog.alert("PBG Percentage Of Tender should not be empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TrNoMonthsVal == ""){
			BootstrapDialog.alert("Time Allowed in Months should not be empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TreEngname == ""){
			BootstrapDialog.alert("Please Select Engineer Name..!!");
			event.preventDefault();
			event.returnValue = false;
		}else{
			event.preventDefault();
			BootstrapDialog.confirm({
				title: 'Confirmation Message',
				message: 'Are you sure want to save this NIT ?',
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
// $('body').on("change","#cmb_staffid", function(event){ 
// 	var StaffCode = $(this).val();
// 	$("#txt_staffrole").val('');
// 	$.ajax({ 
// 		type: 'POST', 
// 		url: 'GetEngineerDesignation.php', 
// 		data: { StaffCode: StaffCode}, 
// 		dataType: 'json',
// 		success: function (data) { //alert(data);
// 			if(data != null){
// 				$.each(data, function(index, element) {
// 					$("#txt_staffrole").val(element.designationname);
// 					$("#txt_email").val(element.email);
// 			 });
// 		  }
//       }
//    })
// });
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
						window.location.replace('NIT.php');
					}
				}]
			});
		}
};

</script>