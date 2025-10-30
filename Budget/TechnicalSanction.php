<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Technical Sanction';
checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$Delete = 0;
$staffid  = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];

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
$financial_year = (date('Y')-1) . '-' . date('Y');
//$financial_year = (date('Y')) . '-' . (date('Y')+1);
//echo $financial_year;
//exit;
$FinYearArr = array();
$FinMonth 	= date("m");
if($FinMonth > 3){
	$FinYear1 = (date("Y"))."-".(date("Y")+1);
	$FinYear2 = (date("Y")-1)."-".(date("Y"));
	array_push($FinYearArr,$FinYear1);
	array_push($FinYearArr,$FinYear2);
}else{
	$FinYear1 = (date("Y")-1)."-".(date("Y"));
	$FinYear2 = (date("Y")-2)."-".(date("Y")-1);
	array_push($FinYearArr,$FinYear1);
	array_push($FinYearArr,$FinYear2);
}
if($EndMonth > 3){
	$EndPeriodYr = $EndYear+1;
}else{
	$EndPeriodYr = $EndYear;
}

if(isset($_POST['Back'])){
	header("Location:Home.php");
}
if(isset($_POST['btn_save'])){
	//$project_id = mysql_insert_id($insert_query);
	$MastId       	= $_POST["cmb_estimate"];
	$GlobTsID       = $_POST["txt_glob_id"];
	$TSId    		= $_POST["txt_ts_id"];
	$txt_work_name	= $_POST["txt_work_name"];
	$txt_ts_no		= $_POST["txt_ts_no"];  
	$txt_ts_amount	= trim($_POST["txt_ts_amount"]);
	$txt_ts_date	= dt_format($_POST["txt_ts_date"]);
	$hoa		    = $_POST['cmb_hoa'];
	$hoaStr         = implode(",",$hoa);
	$staffname      = trim($_POST['cmb_staffid']);
	//echo $hoaStr;exit;
	// if($cmb_work_name == NULL){
	// 	$msg = "Please Select Project Title..!!";
	// }
	
	if($txt_work_name == NULL){
		$msg = "Please Enter Work Name..!!";
	}else if($txt_ts_no == NULL){
		$msg = "Please Enter Technical Sanction Number..!!";
	}else if($txt_ts_amount == NULL){
		$msg = "Please Enter Technical Sanction Amount..!!";
	}else if($txt_ts_date == NULL){
		$msg = "Please Enter Technical Sanction Date..!!";
	}else{
		$InQueryCon = 1;
	}

	if($InQueryCon == 1){
		$GlobID= '';
		$GlobIdQuery = "SELECT globid FROM partab_master WHERE mastid = '$MastId'";
		$GlobIdSql 	= mysqli_query($dbConn,$GlobIdQuery);
		if($GlobIdSql == true){
			if(mysqli_num_rows($GlobIdSql)>0){
				$List = mysqli_fetch_object($GlobIdSql);
				$GlobID = $List->globid;
			}
		}
		$PinIdQuery = "SELECT * FROM pin WHERE active = 1";
		$PinIdQuerySql = mysqli_query($dbConn,$PinIdQuery);
		if($PinIdQuerySql == true){
			if(mysqli_num_rows($PinIdQuerySql)>0){
				$List = mysqli_fetch_object($PinIdQuerySql);
				$PinID  = $List->pin_id;
				$PinNum = $List->pin_no;
			}
		}
		$HoaNoArr = array();
		$HoaMastQuery = "SELECT * FROM hoa_master WHERE hoamast_id IN($hoaStr)";
		$HoaMastSql   = mysqli_query($dbConn,$HoaMastQuery);
		if($HoaMastSql == true){
			if(mysqli_num_rows($HoaMastSql)>0){
				while($HoaList = mysqli_fetch_object($HoaMastSql)){
					$HoaNo = $HoaList->new_hoa_no;
					array_push($HoaNoArr,$HoaNo);
				}
			}
		}
		if(count($HoaNoArr)>0){
			$HoaNoStr = implode(",",$HoaNoArr);
		}else{
			$HoaNoStr = "";
		}
		//echo $HoaNoStr;
		//exit;

		if(($TSId != NULL)&&($TSId != 0)&&($TSId != '')){ 
			$update_query	= "UPDATE works SET work_name='$txt_work_name', ts_workname='$txt_work_name',  eic='$staffname ', work_status='TS', hoaid='$hoaStr', hoa_no = '$HoaNoStr',  
									ts_no='$txt_ts_no', ts_amount='$txt_ts_amount', ts_date='$txt_ts_date', pinid='$PinID', pin_no='$PinNum' WHERE globid = '$GlobID'";
			//echo $update_query;exit;
			$update_query_sql = mysqli_query($dbConn,$update_query);
			$update_query1	= "UPDATE technical_sanction SET globid = '$GlobID', est_id = '$MastId', hoaid='$hoaStr',  eic='$staffname ', ts_work_name='$txt_work_name', ts_no='$txt_ts_no', 
									ts_date='$txt_ts_date', ts_nit='S', ts_amount='$txt_ts_amount', active = '1', created_by = '$UserId', 
									created_date = NOW() WHERE globid = '$GlobID'";
			$update_query_sql = mysqli_query($dbConn,$update_query1);
			if($update_query_sql == true){
				$msg = "Technical Sanction Updated Successfully..!!";
				UpdateWorkTransaction($GlobID,0,0,"W","Technical Sanction details Updated by ".$_SESSION['staffname'],"");
			}else{
				$msg = "Error: Technical Sanction Not Updated..!!";
				UpdateWorkTransaction($GlobID,0,0,"W","Technical Sanction details Tried to Update by ".$_SESSION['staffname']." but not Updated","");			
			}
		}else{
			$update_query	= "UPDATE works SET work_name='$txt_work_name', ts_workname='$txt_work_name',  eic='$staffname ', work_status='TS', hoaid='$hoaStr', hoa_no = '$HoaNoStr',  
									ts_no='$txt_ts_no', ts_amount='$txt_ts_amount', ts_date='$txt_ts_date', pinid='$PinID', pin_no='$PinNum' WHERE globid = '$GlobID'";
			$update_query_sql = mysqli_query($dbConn,$update_query);
			$insert_query1	= "INSERT INTO technical_sanction SET globid = '$GlobID', est_id = '$MastId', eic='$staffname ', hoaid='$hoaStr', ts_work_name='$txt_work_name', ts_no='$txt_ts_no', 
									ts_date='$txt_ts_date', ts_nit='S', ts_amount='$txt_ts_amount', active = '1', created_by = '$UserId', created_date = NOW()";
			$insert_sql1 = mysqli_query($dbConn,$insert_query1);
			if($insert_sql1 == true){
				$msg = "Technical Sanction Created Successfully..!!";
				UpdateWorkTransaction($LastInsertId,0,0,"W","Technical Sanction details Inserted by ".$_SESSION['staffname'],"");
			}else{
				$msg = "Error: Technical Sanction Not Created..!!";
				UpdateWorkTransaction(0,0,0,"W","Technical Sanction details Tried to Create by ".$_SESSION['staffname']." but not Created","");
			}
		}
	}
	//echo $insert_sql;exit;
 }
 if(isset($_POST['btn_Del'])){
	$TSId    		 	= $_POST["txt_ts_id"];
	$GlobTsID         	= $_POST["txt_glob_id"];
	$DeleteQuery1		= "DELETE FROM works WHERE globid='$GlobTsID'"; 
	$update_query_sql 	= mysqli_query($dbConn,$DeleteQuery1);
	$DeleteQuery2		=  "DELETE FROM technical_sanction WHERE globid='$GlobTsID'";
	$update_query_sql 	= mysqli_query($dbConn,$DeleteQuery2);
	
	$DeleteQuery2		=  "UPDATE partab_master SET is_confirmed = 'D' WHERE globid='$GlobTsID'";
	$update_query_sql 	= mysqli_query($dbConn,$DeleteQuery2);
	
	if($update_query_sql == true){
		$msg = "Technical Sanction Deleted Successfully..!!";
		UpdateWorkTransaction($GlobID,0,0,"W","Technical Sanction details Deleted  by ".$_SESSION['staffname'],"");
	}else{
		$msg = "Error: Technical Sanction Not Deleted..!!";
		UpdateWorkTransaction($GlobID,0,0,"W","Technical Sanction details Tried to Deleted by ".$_SESSION['staffname']." but not Updated","");			
	}
 }
 $MastId = '';
 if(isset($_GET['id'])){   
	$TSId 	 = $_GET['id'];
	$ContArr  	 =  array();
	$ContNameArr = array();
	$GlobID= '';
	$GlobIDQuery = "SELECT a.*, b.mastid FROM technical_sanction a INNER JOIN partab_master b ON (a.globid = b.globid) WHERE a.ts_id = '$TSId'";
	$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobTsID = $List->globid;
			$TSId    = $List->ts_id;
			$Tennum    = $List->ts_no;
			$Tenest = $List->tr_est;
			$WorkName = $List->ts_work_name;
			$Tencost = $List->ts_amount;
			$Tsdate =dt_display($List->ts_date);
			$hoaid =$List->hoaid; 
			$EICid =$List->eic; 
			$MastId = $List->mastid;
		}
	}
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
$FinaQuery = "SELECT ts_id FROM tender_register WHERE ts_id = '$TSId'";
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
	function ViewTSList(){
		url = "TSViewEdit.php";
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
						<div align="right" class="users-icon-part">&nbsp;</div>
						 <blockquote class="bq1 stable" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
								 <div class="div1">&nbsp;</div>
									<div class="div10">
										<div class="card cabox">
											<div class="face-static">
									           <div class="card-header inkblue-card" align="center">Technical Sanction - Create</div>
										       <div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="row smclearrow"></div> <?php echo $MastId; ?>
														<div class="div3 lboxlabel">Name of Work</div>
														<div class="div6" align="left">
															<select name="cmb_estimate" id="cmb_estimate" class="tboxsmclass">
																<option value=""> ---------- Select ---------- </option>
																	<?php echo $objBind->BIndEstimateForTs($MastId); ?>
															</select>
														</div>
														<?php
														if($RowCount==1){
														?>
														<div class="div3 lboxlabel " id="complete">
															&emsp;<i class="fa fa-check-circle-o" style="font-size:20px; color:#EA253C;"></i> <span style="color:EA253C; top:-4px; position:relative;">NIT Created</span>
														</div>
														<?php
														}
														?>
														<div class="row clearrow"></div>
														<div class="div3 lboxlabel">Work Description</div>
														<div class="div9"><textarea name="txt_work_name" id="txt_work_name" class="tboxsmclass"  maxlength="250" ><?php if($_GET['id'] != ""){ echo $WorkName; } ?></textarea></div>
														<div class="row clearrow"></div>
														<div class="div3 lboxlabel">Technical Sanction Number</div>
														<div class="div9"><input type="text" name="txt_ts_no" id="txt_ts_no"  maxlength="30" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $Tennum; } ?>"></div>
														
														<div class="div9"><input type="hidden" name="txt_ts_id" id="txt_ts_id"  maxlength="30" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $TSId; } ?>"><input type="hidden" name="txt_glob_id" id="txt_glob_id"  maxlength="30"  required class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $GlobTsID; } ?>"></div>
														<div class="row clearrow"></div>
														
														<div class="div3 lboxlabel">Technical Sanction Amount (&#x20B9;)</div>
														<div class="div3" align="left">
															<input type="text"  name="txt_ts_amount"  maxlength="20" id="txt_ts_amount" class="tboxsmclass" onKeyPress="return isNumberWithTwoDecimal(event,this);" value="<?php if($_GET['id'] != ""){ echo $Tencost; } ?>">
														</div>
														<div class="div3 lboxlabel">&emsp;Technical Sanction Date</div>
														<div class="div3" align="left">
															<input type="text" readonly="" name="txt_ts_date" id="txt_ts_date" class="tboxsmclass datepicker" value="<?php if($_GET['id'] != ""){ echo $Tsdate; } ?>">
														</div>
														<div class="row clearrow"></div>
														<div class="div3 lboxlabel">HOA Code</div>
														<div class="div3">
															<select name='cmb_hoa[]' id='cmb_hoa' class="tboxsmclass" required  multiple="multiple">
																<?php 
																if(count($FinYearArr)>0){
																	foreach($FinYearArr as $FinYear){
																		echo $objBind->BindHOAMaster($hoaid,$FinYear); 
																	}
																}
																?>														  
																</select>
														</div>
														<div class="div3 lboxlabel">&emsp;Engineer in Charge</div>
														<div class="div3" align="left">
															<select name="cmb_staffid" id="cmb_staffid" class="tboxsmclass">
																<option value=""> ---------- Select ---------- </option>
																	<?php echo $objBind->BindStaff($EICid); ?>
															</select>
														</div>
														<div class="row clearrow"></div>
														<div class="div3 lboxlabel">Designation</div>
														<div class="div3" align="left">
															<input type="text" name="txt_staffrole" readonly id="txt_staffrole" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $Desgnation; } ?>">
														</div>
														<div class="div3 lboxlabel">&emsp;Email</div>
														<div class="div3" align="left">
															<input type="text" name="txt_email" readonly id="txt_email" class="tboxsmclass" value="<?php if($_GET['id'] != ""){ echo $Email; } ?>">
														</div>
														<div class="row clearrow"></div>
														<div class="div12" align="center">
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
															<!-- <input type="button" class="btn btn-info" name="btn_view" id="btn_view" value="View" onClick="ViewTSList();"/> -->
															<input type="hidden" name='hid_status' id='hid_status' class="tboxsmclass">
														</div>		
												</div>
											</div>
										</div>
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
<script type="text/javascript" language="javascript">
	$("#cmb_hoa").chosen();
	$("#cmb_staffid").chosen();
	$("#cmb_estimate").chosen();
	$('body').on("change","#cmb_estimate", function(e){ 
	 	var Id = $(this).val();
		$("#txt_work_name").val('');
		$("#txt_ts_amount").val('');
	 	$.ajax({ 
	 		type: 'POST', 
	 		url: 'FindEstTsTrName.php', 
			dataType: 'json',
	 		data: { Id: Id, Page: 'EST'}, 
	 		success: function (data) {  
				if(data != null){
	 				$("#txt_work_name").val(data['work_name']);
					$("#txt_ts_amount").val(data['partA_amount']);
				}
	 		}
	 	});
	 });
	$('body').on("change","#txt_ts_no", function(e){ 
		$("#hid_status").val('');
		var TenNo = $(this).val(); //alert(TenNo);
		if(TenNo != ""){
			$.ajax({
				type:'POST',
				url: 'GetTechnicalSanctionRecord.php', 
				dataType: 'json',
				data:{'TenNo':TenNo}, 
				success:function(data){  //alert(data);	
					var TenData = data['status'];
					$("#hid_status").val(TenData);
				}
			});
		}
 });
	// $('body').on("change","#cmb_work_name", function(e){ 
	// 	var Id = $(this).val();
	// 	$("#txt_work_name").val('');
    //     $("#txt_ts_no").val('');
	// 	$.ajax({ 
	// 		type: 'POST', 
	// 		url: 'FindEstTsTrName.php', 
	// 		data: { Id: Id, Page: 'EST'}, 
	// 		dataType: 'json',
	// 		success: function (data) {  
	// 			if(data != null){ 
	// 				$("#txt_work_name").val(data.work_name);
	// 			}
	// 		}
	// 	});
	// });
</script>
<script>
$(document).ready(function(){
	$("#cmb_work_name").chosen();
	$("#cmb_approve_auth").chosen();
	$("#btn_view").click(function(event){ 
		var WorkName 		= $("#cmb_work_name").val(); 
		if(WorkName == ""){ 
			BootstrapDialog.alert("Please Select Name of Work.");
			event.preventDefault();
			event.returnValue = false;
		}
	});
});

var KillEvent = 0;
$("body").on("click","#btn_Del", function(event){
	if(KillEvent == 0){
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to Delete this Technical Sanction ?',
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
		var TsWorkNameVal   = $("#txt_work_name").val();
		var TsNumberVal   	= $("#txt_ts_no").val();
		var TsAmountVal 	= $("#txt_ts_amount").val();
		var TsDateVal		= $("#txt_ts_date").val();
		var TsHOa		    = $('#cmb_hoa > option:selected');
		var Hidstatus 	    = $("#hid_status").val();
		var TreEngname 	    = $("#cmb_staffid").val();
		if(TsWorkNameVal == ""){
			BootstrapDialog.alert("Name of Work should not be empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TsNumberVal == ""){
			BootstrapDialog.alert("Please Select Tender No..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TsAmountVal == ""){
			BootstrapDialog.alert("Technical Sanction Amount should not be empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TsDateVal == ""){
			BootstrapDialog.alert("Technical Sanction Date should not be empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TsHOa.length == 0){
			BootstrapDialog.alert("Please Select atleast one HOA..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(Hidstatus == 1){ 
			BootstrapDialog.alert("Technical Sanction Number Already Exits..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TreEngname == ""){
			BootstrapDialog.alert("Please Select Engineer Name..!!");
			event.preventDefault();
		}else{
			event.preventDefault();
			BootstrapDialog.confirm({
				title: 'Confirmation Message',
				message: 'Are you sure want to save Technical Sanction ?',
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

$('body').on("change","#cmb_staffid", function(event){ 
	var StaffCode = $(this).val();
	$("#txt_staffrole").val('');
	$("#txt_email").val('');
	$.ajax({ 
		type: 'POST', 
		url: 'GetEngineerDesignation.php', 
		data: { StaffCode: StaffCode}, 
		dataType: 'json',
		success: function (data) { //alert(data);
			if(data != null){
				$.each(data, function(index, element) {
					$("#txt_staffrole").val(element.designationname);
					$("#txt_email").val(element.email);
			 });
		  }
      }
   })
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
						window.location.replace('TechnicalSanction.php');
					}
				}]
			});
		}
};

</script>