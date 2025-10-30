<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'User Management'.$PTIcon."Staff Creation";
checkUser();


function dt_format($ddmmyyyy)
{
	$dt=explode('/',$ddmmyyyy);
	
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	
	return $yy .'-'. $mm .'-'.$dd;
}
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'/'. $mm .'/'.$yy;
}
$msg = '';
if($_GET['staffid']!='') {
	$sqlstaffdetails="SELECT * FROM staff WHERE active = 1 AND staffid='" . $_GET['staffid'] . "'";
	$rsstaffquerysql=mysqli_query($dbConn,$sqlstaffdetails);

	$List = mysqli_fetch_object($rsstaffquerysql);
	$staffcode 		= trim($List->staffcode);
	$staffempcode	= trim($List->staff_emp_no);
	$staffname 		= trim($List->staffname); 
	$staffid 		= trim($List->staffid); 
	$email 			= trim($List->email);
	$DOB 				= trim($List->DOB);
	$DOJ 				= trim($List->DOJ);
	$mobile			= trim($List->mobile);
	$intercom		= trim($List->intercom);
	$subsecid		= trim($List->sub_sec_id);
	//$disciplineid	= trim($List->discipline_id);
	$designationid	= trim($List->designationid);
	$staffimage		= trim($List->image);
	$sectionid		= trim($List->sectionid);
	$levelid			= trim($List->levelid);
	$sroleid			= trim($List->sroleid);

	/*	$staffcode	=	trim(@mysqli_result($rsstaffquery,0,'staffcode'));
	$staffname		=	trim(@mysqli_result($rsstaffquery,0,'staffname'));
	$staffid			=	trim(@mysqli_result($rsstaffquery,0,'staffid'));
	$email			=	trim(@mysqli_result($rsstaffquery,0,'email'));
	$DOB				=	trim(dt_display(@mysqli_result($rsstaffquery,0,'DOB')));
	$DOJ				=	trim(dt_display(@mysqli_result($rsstaffquery,0,'DOJ')));
	$mobile			=	trim(@mysqli_result($rsstaffquery,0,'mobile'));
	$intercom		=	trim(@mysqli_result($rsstaffquery,0,'intercom'));
	$designationid	=	trim(@mysqli_result($rsstaffquery,0,'designationid'));	 
	$staffimage		=	trim(@mysqli_result($rsstaffquery,0,'image'));
	$sectionid		=	trim(@mysqli_result($rsstaffquery,0,'sectionid'));
	$levelid			=	trim(@mysqli_result($rsstaffquery,0,'levelid'));
	$sroleid			=	trim(@mysqli_result($rsstaffquery,0,'sroleid'));	*/
}

if($_POST['btn_save']){	
	$staffUnit 		=	trim($_POST['txt_unit']);
	$staffcode		=	trim($_POST['txt_ic_no']);
	$staffEmpNo		=	trim($_POST['txt_emp_no']);
	$staffname		=	trim($_POST['staffname']);
	$staffid		=	trim($_POST['staffid']);
	//$disciplineid	=	trim($_POST['cmb_discipline']);
	$subsectionid	=	trim($_POST['cmb_section']);	//$_SESSION['staff_section'];
	$designationid	=	trim($_POST['cmb_designation']);
	$sroleid		=	trim($_POST['cmb_staff_role']);
	$email			=	trim($_POST['email']);
	$mobile			=	trim($_POST['mobile']);
	$intercom		=	trim($_POST['intercom']);
	$dob			=	"";	//trim(dt_format($_POST['dob']));
	$doj			=	"";	//trim(dt_format($_POST['doj']));
	$image			=	trim($_POST['image']);
	$levelid		=	trim($_POST['txt_level']);
	$msg			=   '';

	if($staffcode == ""){
		$msg = 'Error : Staff IC Number should not be empty..!!!';
	}else if($staffEmpNo == ""){
		$msg = 'Error : Staff Employee Number should not be empty..!!!';
	}else if($staffname == ""){
		$msg = 'Error : Staff Name should not be empty..!!!';
	}/*else if($disciplineid == ""){
		$msg = 'Error : Staff Discipline should not be empty..!!!';
	}*/else if($subsectionid == ""){
		$msg = 'Error : Staff Section should not be empty..!!!';
	}else if($designationid == ""){
		$msg = 'Error : Staff Designation should not be empty..!!!';
	}else if($sroleid == ""){
		$msg = 'Error : Staff Role should not be empty..!!!';
	}else if($email == ""){
		$msg = 'Error : Staff E-Mail Id should not be empty..!!!';
	}else if($_FILES['image']['name'] != "") {
		$_FILES['image']['name'] = $staffcode.$_FILES['image']['name'];
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["image"]["name"]);
		$currentfilename = basename($_FILES["image"]["name"]);
		$checkupload = 0;
		$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
		if (strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "jpeg") {
			$checkupload = 3;
		}
		if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
			$checkupload = 0;
		} 
		if($checkupload == 3) { 
			$msg = $msg. "Upload file only JPG or JPEG formats";
		}else{			
			if($staffid!=''){	
				$staffupdate="UPDATE staff SET staff_unit='$staffUnit',staffcode='$staffcode',staff_emp_no='$staffEmpNo',staffname='$staffname',sub_sec_id='$subsectionid',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',image='$currentfilename' WHERE staffid='$staffid'";
				$stafupdateresult=mysqli_query($dbConn,$staffupdate);

				$staffupdate1="UPDATE users SET username='$staffcode', password='$staffcode' WHERE staffid='$staffid'";
				$stafupdateresult1=mysqli_query($dbConn,$staffupdate1);

				$status="u";
				if($stafupdateresult == true) {
					$msg = "Staff Updated Sucessfully..!!";
					$success = 1;
				}else{
					$msg = "Error...!!";
				}	
			}else{							
				$staffinsert="INSERT INTO staff SET staff_unit='$staffUnit',staffcode='$staffcode',staff_emp_no='$staffEmpNo',staffname='$staffname',sub_sec_id='$subsectionid',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',image='$currentfilename',active='1',userid='1' ";
				//	echo $staffinsert;exit;
				$staffinsertresult=mysqli_query($dbConn,$staffinsert);

				$status="i";
				if($staffinsertresult == true) {
					$msg = "Staff Added Sucessfully..!!";
					$success = 1;
				}else{
					$msg = "Error...!!";
				}	 
				$status="i";
			}
		}
	}else{
		if($staffid != ''){
			$sqlstaffdetails="SELECT * FROM staff WHERE staffid='$staffid' AND active = 1";
			//$rsstaffquery=mysqli_query($dbConn,$sqlstaffdetails);
			$rsstaffquerysql1=mysqli_query($dbConn,$sqlstaffdetails);
			
			$List = mysqli_fetch_object($rsstaffquerysql1);
			$staffimage	= trim($List->image);
			//$staffimage=trim(@mysqli_result($rsstaffquery,0,'image'));
			$staffupdate="UPDATE staff SET staff_unit='$staffUnit',staffcode='$staffcode',staff_emp_no='$staffEmpNo',staffname='$staffname',sub_sec_id='$subsectionid',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',image='$staffimage' WHERE staffid='$staffid'";
			$stafupdateresult=mysqli_query($dbConn,$staffupdate);

			$staffupdate1="UPDATE users SET username='$staffcode', password='$staffcode' WHERE staffid='$staffid'";
			$stafupdateresult1=mysqli_query($dbConn,$staffupdate1);
			if($stafupdateresult == true) {
				$msg = "Staff Updated Sucessfully";
				$success = 1;
			}else{
				$msg = "Error...!!";
			}	
		}else{	
			$currentfilename = "profile_default.png";
			$staffinsert="INSERT INTO staff SET staff_unit='$staffUnit',staffcode='$staffcode',staff_emp_no='$staffEmpNo',staffname='$staffname',sub_sec_id='$subsectionid',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',image='$currentfilename',active='1',userid='1' ";
			$staffinsertresult=mysqli_query($dbConn,$staffinsert);

			if($staffinsertresult == true){
				$msg = "Staff Added Sucessfully";
				$success = 1;
			}else{
				$msg = "Error...!!";
			}	 
			$status="i";
			//echo $staffinsert;
		}
	}
}
if($_POST['back']){
	header('Location: EngineerList.php');
}
?> 
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
	
<script type="text/javascript"  language="JavaScript">

function uploadfile()
{	
	document.getElementById("dbimage").style.display="none";	
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function onlyAlphabets(e, t) {
try {
	if (window.event) {
		var charCode = window.event.keyCode;
	}
	else if (e) {
		var charCode = e.which;
	}
	else { return true; }
		//alert(charCode)
	if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8) || (charCode == 46) || (charCode == 32))
		return true;
	else
		return false;
}
catch (err) {
	alert(err.Description);
}
}

 function checkAlphaNumeric(e) 
 {          
  if((e.keyCode >= 48 && e.keyCode <= 57)||(e.keyCode >= 65 && e.keyCode <= 90)||(e.keyCode >= 97 && e.keyCode <= 122))
  {
  	return true;
  }
  else
  {
  	return false; 
  }
 }
function View_page()
{
	 url = "EngineerList.php";
	 window.location.replace(url);
}

</script>
<style type="text/css">
.filehidden{
    width: 90px;
	display:none;
    overflow:hidden;
}
.filechange
{
	cursor:help;
	
}
</style>
<script>
function goBack()
{
	url = "EngineerList.php";
	window.location.replace(url);
}
function func_icno()
{	
 	var xmlHttp;
    var data;
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	strURL="check_icno.php?icno="+alltrim(document.form.txt_ic_no.value);
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			if(data>0)
			{	
				var a="Entered IC Number already exist";
				$('#val_icno').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				var a="";
				$('#val_icno').text(a);
			}
		}
    }
	xmlHttp.send(strURL);
}

function validateEmail(sEmail) 
{
	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	if (filter.test(sEmail)) 
	{
		return true;
	}
	else
	{
		return false;
	}
}

</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							
						<!----------===================================== HIDDEN FIELDS =========================================--------->
							<input type="hidden" name="staffid" id="staffid" value="<?php echo $_GET['staffid']; ?>"> 
								<div class="row">
									<div class="box-container box-container-lg" align="center">
										<div class="div2">&nbsp;</div>
										<div class="div8">
											<div class="card cabox">
												<div class="face-static">
													<div class="card-header inkblue-card" align="center">Staff List - Create</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">

																	<div class="row clearrow"></div>

																	<!-- <div class="div2 lboxlabel">Unit</div>
																	<div class="div4">
																		<input type="text" maxlength="100" class="tboxsmclass" name='txt_unit' readonly="" id='txt_unit' value="FRFCF">
																		<input type="hidden" class="tboxsmclass" name='txt_staffid' id='txt_staffid' value="" >
																	</div>
																	<div class="div2 cboxlabel">Section</div>
																	<div class="div4">
																		<select id="cmb_section" name="cmb_section" class="tboxsmclass" style="width:297px;height:22px;" tabindex="3">
																			<option value=""> ------------- Select ------------- </option>
																			<option value="3" <?php //if(($_GET['staffid']!='')&&($sectionid == 3)){ echo 'selected="selected"'; } ?>> E&C Section </option>
																			<option value="1" <?php //if(($_GET['staffid']!='')&&($sectionid == 1)){ echo 'selected="selected"'; } ?>> Other Civil Section </option>
																		</select>
																	</div>
																	<div class="row clearrow"></div> -->


																	<div class="div2 lboxlabel">Staff ICNO</div>
																	<div class="div4">
																	<input type="hidden" class="tboxsmclass" name='txt_unit' readonly="" id='txt_unit' value="6">
																		<?php if($_GET['staffid']!='') { ?>
																			<input type="text" maxlength="10" tabindex="1" class="tboxsmclass" name='txt_ic_no' id='txt_ic_no' value="<?php echo $staffcode; ?>">
																		<?php } else { ?>
																			<input type="text" maxlength="10" tabindex="1" class="tboxsmclass" name='txt_ic_no' id='txt_ic_no' oninput="this.value=this.value.replace(/[^0-9]/g,'');">
																		<?php } ?>
																	</div>
																	<div class="div2 lboxlabel">&emsp;&emsp;Employee No.</div>
																	<div class="div4">
																		<input type="text" class="tboxsmclass" maxlength="10" name='txt_emp_no' id='txt_emp_no' value="<?php if($_GET['staffid']!='') echo $staffempcode; ?>"  tabindex="2">
																	</div>
																	<div class="row clearrow"></div>

																	<div class="div2 lboxlabel">Staff Name</div>
																	<div class="div4">
																		<input type="text" maxlength="40" class="tboxsmclass" name='staffname' id='staffname' value="<?php if($_GET['staffid']!='') echo $staffname; ?>" tabindex="3">
																		<input type="hidden" class="tboxsmclass" name='txt_staffid' id='txt_staffid' value="" >
																	</div>
																	<!--<div class="div2 cboxlabel">&nbsp;&nbsp;Discipline</div>
																	<div class="div4">
																		<select id="cmb_discipline" name="cmb_discipline" class="tboxsmclass" style="" tabindex="4">
																			<option value=""> ------------- Select ------------- </option>
																			<?php echo $objBind->BindDiscipline($disciplineid,0); ?>
																		</select>
																	</div>-->
																	


																	<div class="div2 lboxlabel">&emsp;&emsp;Section</div>
																	<div class="div4">
																		<select id="cmb_section" name="cmb_section" class="tboxsmclass" style="" tabindex="5">
																			<option value=""> ------------- Select ------------- </option>
																			<?php echo $objBind->BindAllSubSection($subsecid,0); ?>
																		</select>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">Designation</div>
																	<div class="div4">
																		<select id="cmb_designation" name="cmb_designation" onChange="func_item_no()" class="tboxsmclass" style="" tabindex="6">
																			<option value=""> ------------- Select ------------- </option>
																			<?php echo $objBind->BindDesignation($designationid,0); ?>
																		</select>
																	</div>
																	


																	<div class="div2 lboxlabel">&emsp;&emsp;Staff Role</div>
																	<div class="div4">
																		<select name="cmb_staff_role" id="cmb_staff_role" class="tboxsmclass" style="" tabindex="7">
																			<option value=""> ------------- Select ------------- </option>
																			<?php echo $objBind->BindStaffRole($sroleid,1); ?>
																		</select>
																		<input type="hidden" name="txt_level" id="txt_level" value="<?php if($_GET['staffid']!=''){ echo $levelid; } ?>">
																	</div>
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">Email - ID</div>
																	<div class="div4">
																		<input type="text"  name='email' id='email' class="tboxsmclass" value="<?php if($_GET['staffid']!='') echo $email; ?>" style="" tabindex="8"  maxlength="30">
																	</div>
																	


																	<div class="div2 lboxlabel">&emsp;&emsp;Mobile No.</div>
																	<div class="div4">
																		<input type="text" name='mobile' id='mobile' class="tboxsmclass" value="<?php if($_GET['staffid']!='') echo $mobile; ?>" style="" maxlength="10" tabindex="9" oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">Intercom No.</div>
																	<div class="div4">
																		<input type="text" name='intercom' id='intercom' class="tboxsmclass" value="<?php if($_GET['staffid']!='') echo $intercom; ?>" style="" maxlength="8" tabindex="10" oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
																	</div>
																	


																	<div class="div2 lboxlabel">&emsp;&emsp;Photo Upload</div>
																	<div class="div4" style="text-align:left;">
																		<?php if($_GET['staffid']!='') { ?>
																			<input type="file" id="image" name="image" tabindex="11" onChange="this.style.width = '100%';" onBlur="uploadfile()" >
																			<span id="dbimage" style="display:"><?php echo $staffimage; ?></span></td>
																			<!--<input type="file" id="image" name="image" class="filehidden" >-->
																			<!--<td width="20%"><?php //echo $staffimage; ?></td>-->
																		<?php }else{ ?>
																			<input type="file" class="text" name="image" id="image" tabindex="11" size="38" style="height:23px;" accept="image/png, image/gif, image/jpeg" />
																		<?php } ?>
																	</div>
																	<div class="row clearrow"></div>
																	<input type="hidden" name="txt_edit_check_ic" id="txt_edit_check_ic" value="<?php if($_GET['staffid']!=''){ echo $staffcode; } ?>">
																	<input type="hidden" name="txt_edit_check_emp" id="txt_edit_check_emp" value="<?php if($_GET['staffid']!=''){ echo $staffempcode; } ?>">
																	<input type="hidden" name="txt_edit_check_mail" id="txt_edit_check_mail" value="<?php if($_GET['staffid']!=''){ echo $email; } ?>">
																	<div style="text-align:center; height:45px; line-height:45px;" class="">
																		<div class="row">
																			<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
																			<input type="button" class="btn btn-info" name="View" id="View" value="View" onClick="View_page();"/>
																			<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" data-type="submit" value=" Save "/>
																		</div>
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
			<?php include "footer/footer.html"; ?>
			<script src="js/jquery.hoverdir.js"></script>
			<script>
				$("#cmb_section").chosen();
				$("#cmb_designation").chosen();
				$("#cmb_staff_role").chosen();
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				if(msg != ""){
					BootstrapDialog.show({
						message: msg,
						buttons: [{
							label: ' OK ',
							action: function(dialog) {
								dialog.close();
								window.location.replace('EngineerList.php');
							}
						}]
					});
				}

				$("body").on("change","#txt_ic_no", function(event){ 
					var ICNOval = $(this).val(); //alert(ContID);
					var EditCheckIcNo = $("#txt_edit_check_ic").val();
					if(ICNOval == EditCheckIcNo){
						/////////
					}else{
						$.ajax({ 
							type: 'POST', 
							url: 'ajax/GetIcnoDetails.php',
							data: { ICNOval: ICNOval}, 
							dataType: 'json',
							success: function (data) {  //alert(data);
								if(data != null){ 
									var CheckVar 		= data['IsICExist'];
									var StaffNameVar 	= data['StaffName'];
									if(CheckVar == 1){
										BootstrapDialog.alert("Sorry..IC Number '"+ICNOval+"' Aldready exists for '"+StaffNameVar+"'..!!");
										$("#txt_ic_no").val('');
									}else{
										/////
									}
								}
							}
						});
					}
				});

				$("body").on("change","#txt_emp_no", function(event){ 
					var EmpNOval = $(this).val(); 
					var EditCheckEmp = $("#txt_edit_check_emp").val(); //alert(EditCheckEmp);
					if(EditCheckEmp == EmpNOval){
						////////
					}else{
						$.ajax({ 
							type: 'POST', 
							url: 'ajax/GetEmpnoDetails.php',
							data: { EmpNOval: EmpNOval}, 
							dataType: 'json',
							success: function (data) {  //alert(data);
								if(data != null){ 
									var CheckVar 		= data['IsICExist'];
									var StaffNameVar 	= data['StaffName'];
									if(CheckVar == 1){
										BootstrapDialog.alert("Sorry..Employee Number '"+EmpNOval+"' Aldready exists for '"+StaffNameVar+"'..!!");
										$("#txt_emp_no").val('');
									}else{
										/////
									}
								}
							}
						});
					}
				});

				$("body").on("change","#email", function(event){
					var Empmailval = $(this).val(); //alert(ContID);
					var EditCheckEMPNO = $("#txt_edit_check_mail").val();
					if(Empmailval == EditCheckEMPNO){

					}else{
						$.ajax({ 
							type: 'POST', 
							url: 'ajax/GetEmpEMailDetails.php',
							data: { Empmailval: Empmailval}, 
							dataType: 'json',
							success: function (data) {  //alert(JSON.stringify(data));
								if(data != null){ 
									var CheckVar 		= data['IsICExist']; //alert(CheckVar);
									var StaffNameVar 	= data['StaffName']; //alert(StaffNameVar);
									if(CheckVar == 1){
										BootstrapDialog.alert("Sorry..Employee E-Mail Id '"+Empmailval+"' Aldready exists for '"+StaffNameVar+"'..!!");
										$("#email").val('');
									}else{
										/////
									}
								}
							}
						});
					}
				});

				$(document).ready(function(){
					$('#image').validate({
						rules: {
							image: {
								required: true,
								extension:'jpe?g,png',
								uploadFile:true,
							}
						}
					});
				});

				var KillEvent = 0;
				$("body").on("click","#btn_save", function(event){
					if(KillEvent == 0){
						var EditCheck 			= $("#txt_edit_check").val();
						var IcnoVal 			= $("#txt_ic_no").val();
						var EmpnoVal 			= $("#txt_emp_no").val();
						var StaffNameVal 		= $("#staffname").val();
						//var DisplineVal 		= $("#cmb_discipline").val();
						var sectionVal  		= $('#cmb_section').val();
						var designationVal 	= $("#cmb_designation").val();
						var staffroleVal 		= $("#cmb_staff_role").val();
						var emailVal 			= $("#email").val();
						//var mobileVal 			= $("#mobile").val();
						//var intercomVal 		= $("#intercom").val();
						//var StaffPhotoVal 		= $("#image").val();
						if(IcnoVal == ""){
							BootstrapDialog.alert("Please Enter IC Number..!!");
							event.preventDefault();
							event.returnValue = false;
						}else if(EmpnoVal == ""){
							BootstrapDialog.alert("Please Enter Employee Number..!!");
							event.preventDefault();
							event.returnValue = false;
						}else if(StaffNameVal == ""){
							BootstrapDialog.alert("Please Enter Staff Name..!!");
							event.preventDefault();
							event.returnValue = false;
						}/*else if(DisplineVal == ""){
							BootstrapDialog.alert("Please Select Staff Discipline..!!");
							event.preventDefault();
							event.returnValue = false;
						}*/else if(sectionVal == ""){
							BootstrapDialog.alert("Please Select Staff Section..!!");
							event.preventDefault();
							event.returnValue = false;
						}else if(designationVal == ""){
							BootstrapDialog.alert("Please Select Staff Designation..!!");
							event.preventDefault();
							event.returnValue = false;
						}else if(staffroleVal == ""){
							BootstrapDialog.alert("Please Select Staff Role..!!");
							event.preventDefault();
							event.returnValue = false;
						}else if(emailVal == ""){
							BootstrapDialog.alert("Please Enter Staff E-Mail Id..!!");
							event.preventDefault();
							event.returnValue = false;
						}else{
							event.preventDefault();
							BootstrapDialog.confirm({
								title: 'Confirmation Message',
								message: 'Are you sure want to Create this Staff ?',
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

			</script>
        </form>
    </body>
</html>