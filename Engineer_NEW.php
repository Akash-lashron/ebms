<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
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
if($_GET['staffid']!='') 
{
	 $sqlstaffdetails="select * from staff where active = 1 AND staffid='" . $_GET['staffid'] . "'";
         //echo $sqlstaffdetails;
	 $rsstaffquery=mysql_query($sqlstaffdetails);
	 
	 $staffcode		=	trim(@mysql_result($rsstaffquery,0,'staffcode'));
	 $staffname		=	trim(@mysql_result($rsstaffquery,0,'staffname'));
	 $staffid		=	trim(@mysql_result($rsstaffquery,0,'staffid'));
	 $email			=	trim(@mysql_result($rsstaffquery,0,'email'));
	 $DOB			=	trim(dt_display(@mysql_result($rsstaffquery,0,'DOB')));
     $DOJ			=	trim(dt_display(@mysql_result($rsstaffquery,0,'DOJ')));
	 $mobile		=	trim(@mysql_result($rsstaffquery,0,'mobile'));
     $intercom		=	trim(@mysql_result($rsstaffquery,0,'intercom'));
	 $designationid	=	trim(@mysql_result($rsstaffquery,0,'designationid'));	 
	 $staffimage	=	trim(@mysql_result($rsstaffquery,0,'image'));
	 $sectionid		=	trim(@mysql_result($rsstaffquery,0,'sectionid'));
	 $levelid		=	trim(@mysql_result($rsstaffquery,0,'levelid'));
	 $sroleid		=	trim(@mysql_result($rsstaffquery,0,'sroleid'));
}

if($_POST['submit'])
{	
		$staffcode		=	trim($_POST['txt_ic_no']);
		$staffid		=	trim($_POST['staffid']);
		$staffname		=	trim($_POST['staffname']);
		$designationid	=	trim($_POST['cmb_designation']);
		$email			=	trim($_POST['email']);
		$mobile			=	trim($_POST['mobile']);
        $intercom		=	trim($_POST['intercom']);
		$dob			=	"";//trim(dt_format($_POST['dob']));
        $doj			=	"";//trim(dt_format($_POST['doj']));
		$image			=	trim($_POST['image']);
		$sroleid		=	trim($_POST['cmb_staff_role']);
		$levelid		=	trim($_POST['txt_level']);
		
		$msg='';
		 if ($_FILES['image']['name'] != "") 
		 {
            $_FILES['image']['name'] = $staffcode.$_FILES['image']['name'];
			$target_dir = "uploads/";
			$target_file = $target_dir . basename($_FILES["image"]["name"]);
			$currentfilename = basename($_FILES["image"]["name"]);
			//$currentfilename = $staffcode.$currentfilename;
			$checkupload = 0;
			$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
			
			/*if (file_exists($target_file)) { $checkupload = 1;}*/
			//else if ($_FILES["image"]["size"] > 500000) {$checkupload = 2;}
			if (strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "jpeg") {
				$checkupload = 3;
			}
			// Check if $checkupload is set to 0 by an error
		   	if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
											$checkupload = 0;
																				   } 
			/*if($checkupload == 1) { $msg=$msg. "Sorry, Uploaded file already exists "; }*/
			//else if($checkupload == 2) { $msg=$msg. "Sorry, Uploaded file size too large"; }
			if($checkupload == 3) { $msg=$msg. "Upload file only JPG or JPEG formats";}
			else
			{			
				if($staffid!='')
				{	
					$staffupdate="update staff set staffcode='$staffcode',staffname='$staffname',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',sectionid='1',image='$currentfilename' where staffid='$staffid'";
					$stafupdateresult=mysql_query($staffupdate);
					$status="u";
					if($stafupdateresult == true)
					{
						$msg = "Staff Updated Sucessfully..!!";
						$success = 1;
					}
					else
					{
						$msg = "Error...!!";
					}	
					//echo $staffupdate;
				}
				else 
				{							
					$staffinsert="insert into staff set staffcode='$staffcode',staffname='$staffname',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',sectionid='1',image='$currentfilename',active='1',userid='1' ";
					$staffinsertresult=mysql_query($staffinsert);	
					$status="i";
					if($staffinsertresult == true)
					{
						$msg = "Staff Added Sucessfully..!!";
						$success = 1;
					}
					else
					{
						$msg = "Error:".mysql_error();//"Error...!!";
					}	 
					$status="i";
					//echo $staffinsert;
				}													   
			}
		}
		else
		{
			if($staffid!='')
			{
				$sqlstaffdetails="select * from staff where staffid='$staffid' AND active = 1";
				$rsstaffquery=mysql_query($sqlstaffdetails);
				$staffimage=trim(@mysql_result($rsstaffquery,0,'image'));
				$staffupdate="update staff set staffcode='$staffcode',staffname='$staffname',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',sectionid='1',image='$staffimage' where staffid='$staffid'";
				$stafupdateresult=mysql_query($staffupdate);
				if($stafupdateresult == true)
				{
					$msg = "Staff Updated Sucessfully";
					$success = 1;
				}
				else
				{
					$msg = "Error...!!";
				}	
				//echo $staffupdate; 
			}
			else
			{	
				$currentfilename = "profile_default.png";
				$staffinsert="insert into staff set staffcode='$staffcode',staffname='$staffname',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',sectionid='1',image='$currentfilename',active='1',userid='1' ";
				$staffinsertresult=mysql_query($staffinsert);
				if($staffinsertresult == true)
				{
					$msg = "Staff Added Sucessfully";
					$success = 1;
				}
				else
				{
					$msg = "Error:".mysql_error();//"Error...!!";
				}	 
				$status="i";
				//echo $staffinsert;
			}
		}
		//echo $staffinsert;exit;
		/*if($staffinsertresult!=''){ ?>
		<script type="text/javascript" language="javascript">
		alert("Sucessfully Saved")
		window.location.href='engineer.php';
		</script>
		<?php
		}
		else if($stafupdateresult!=''){ ?>
		<script type="text/javascript" language="javascript">
		alert("Sucessfully Updated")
		window.location.href='engineer.php';
		</script>
		<?php
		}*/
	}
	if($_POST['back'])
	{
		header('Location: EngineerList.php');
	}
?> 

<?php include "Header.html"; ?>
	
<script type="text/javascript"  language="JavaScript">

/*function func_icno()
//{	alert("hai");
	///*if(alltrim(document.form.dummyicno.value)!=alltrim(document.form.txt_ic_no.value))
	//{
	var ic_no = document.form.txt_ic_no.value;
	alert(ic_no)
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
			alert(data)
			if(data=='Y')
			{	
				alert("ICNO Already Exist");
				document.form.txt_ic_no.value='';
				return false;
			}
		}
    }
	xmlHttp.send(strURL);
///* }
}*/
function func_email()
{	
	
	if(alltrim(document.form.dummyemail.value)!=alltrim(document.form.email.value))
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
	strURL="check_email.php?email="+alltrim(document.form.email.value);
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data);
			
			if(data=='Y')
			{	
				alert("EMAIL - ID Already Exist");
				document.form.email.value='';
				return false;
			}
		}
    }
	xmlHttp.send(strURL);
 }
}
function validation()
{
	x=alltrim(document.form.txt_ic_no.value)
	if(x.length == 0)
	{	
	 alert("Please Enter the Staff ICNO");
	 document.form.txt_ic_no.value='';
	 document.form.txt_ic_no.focus();
	 return false;
	}
	
	x=alltrim(document.form.staffname.value)
	if(x.length == 0)
	{	
	 alert("Please Enter the Staff Name");
	 document.form.staffname.value='';
	 document.form.staffname.focus();
	 return false;
	}
	
	if(document.form.cmb_designation.value == "")
	{
  	 alert("Please Select the Designation");
	 document.form.cmb_designation.focus();
	 return false;
  	}
	x=alltrim(document.form.email.value)
	if(x.length == 0)
	{	
	 alert("Please Enter the Email ID");
	 document.form.email.value='';
	 document.form.email.focus();
	 return false;
	}
	var email = document.getElementById('email');
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if (!filter.test(email.value)) {
    alert('Please provide a valid email address');
    email.focus;
    return false;
	}
	
	x=alltrim(document.form.mobile.value)
	if(x.length == 0)
	{	
	 alert("Please Enter the Mobile No.");
	 document.form.mobile.value='';
	 document.form.mobile.focus();
	 return false;
	}
	var mobileno=document.getElementById("mobile").value;
	var len=mobileno.length;
	if(len<10 || len>10)
	{
		alert("Please Enter the Valid 10 Digit Mobile No.");
		document.form.mobile.value='';
		document.form.mobile.focus();
		return false;
	}
        x=alltrim(document.form.intercom.value)
	if(x.length == 0)
	{	
	 alert("Please Enter the Intercom No.");
	 document.form.intercom.value='';
	 document.form.intercom.focus();
	 return false;
	}
	var intercom=document.getElementById("intercom").value;
	var len=intercom.length;
	if(len<5 || len>6)
	{
		alert("Please Enter the Valid Intercom No.");
		document.form.intercom.value='';
		document.form.intercom.focus();
		return false;
	}
	var imageupload=parseInt(document.form.image.value)
	var imageload=parseInt(document.form.dbimage.value)
	if(imageload.length !=0 && imageupload.length ==0) { }
	else if(imageupload.length ==0) {
		alert("Please Upload the Photo");
		return false; }
	/*else if(imageload.length ==0 && imageupload.length ==0){
		alert("Please Upload the Photo");
		return false; }*/
}

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
	if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8))
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
  [
  	return false; 
  }
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
	document.form.txt_icno_exist.value = '';
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
				document.form.txt_icno_exist.value = 'Y';
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				var a="";
				//$('#val_icno').text(a);
			}
		}
    }
	xmlHttp.send(strURL);
}
function getStafflevel(obj)
{	
 	var xmlHttp;
    var data;
	var roleid = obj.value;
	//alert(roleid)
	document.form.txt_level.value = "";
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	strURL="find_staff_level.php?roleid="+roleid;
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//alert(data);
			if(data!= "")
			{	
				var idlist 		= data.split("*");
				//alert(idlist)
				var levelid 	= idlist[0];
				var sectionid 	= idlist[1];
				//alert(levelid)
				//alert(sectionid)
				document.form.txt_level.value = levelid;
			}
			else
			{
				document.form.txt_level.value = "";
				alert("No Records Found..");
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

$(function(){
	$.fn.validateemailid = function(event) { 
		if($("#email").val()!=""){
			var email = $("#email").val(); 
			if(validateEmail(email)){
				var a="";
			}else{
				var a = "Please Enter Valid Email ID.";
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
		}
	}
	$.fn.CheckICNOExist = function(event) { 
		var icno = $("#txt_ic_no").val();
		$('#val_icno').text('');
		if(icno != ''){ 
			var Exist = $("#txt_icno_exist").val();
			var EditIcno = $("#txt_edit_icno").val();
			if(EditIcno != icno){
				if(Exist == 'Y'){
					var a="Entered IC Number already exist";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}
			}
		}
	}
	
	$("#email").change(function(event){
		$(this).validateemailid(event);
	});
	$("#top").submit(function(event){
		$(this).validateemailid(event);
		$(this).CheckICNOExist(event);
	});
});
window.history.forward();
function noBack() { window.history.forward(); }
</script>
	<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="form">
		 <?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
	<div class="title">Staff</div>		
	<div class="container_12">
		<div class="grid_12">
		<div align="right"><a href="EngineerList.php?edit=new">View&nbsp;&nbsp;</a></div>
			<blockquote class="bq1">
				
					<!----------===================================== HIDDEN FIELDS =========================================--------->
					<input type="hidden" name="staffid" id="staffid" value="<?php echo $_GET['staffid']; ?>"> 
                    <div class="container">
					  
						<div class="main-content">
					  		<div class="grid2"></div>
							<div class="main-content grid8 main-content-head">Staff Details Entry Form</div>
							<div class="grid2"></div>
									
							<div class="grid2"></div>
							<div class="main-content grid8 main-content-body">
								<div class="main-content grid9 main-content-body" style="border:none">
									<div class="grid2">Staff ICNO</div>
									<div class="grid4">
										<?php if($_GET['staffid']!=''){ ?>
										<input type="text" name="txt_ic_no" id="txt_ic_no" onKeyPress="return isIntegerValue(event,this);" required value="<?php echo $staffcode; ?>" tabindex="1"/>
										<?php }else{ ?>
										<input type="text" name="txt_ic_no" id="txt_ic_no" onKeyPress="return isIntegerValue(event,this);" required tabindex="1" onBlur="func_icno();"/>
										<?php } ?>
										<input type="hidden" name="txt_icno_exist" id="txt_icno_exist" value="">
									</div>
									<div class="grid2" align="center">Staff Name</div>
									<div class="grid4">
										<?php if($_GET['staffid']!=''){ ?>
										<input type="text" name='staffname' id='staffname' required value="<?php echo $staffname; ?>" tabindex="2" onKeyPress="return onlyAlphabets(event,this);">
										<?php }else{ ?>
										<input type="text" name='staffname' id='staffname' required tabindex="2" onKeyPress="return onlyAlphabets(event,this);">
										<?php } ?>
									</div>
									<div class="grid12 grid-empty"></div>
									<div class="grid2">Staff Role</div>
									<div class="grid4">
										<select name="cmb_staff_role" id="cmb_staff_role" required onChange="getStafflevel(this)" tabindex="3">
											<option value="">--- Staff Role ---</option>
											 <?php echo $objBind->BindStaffRole($sroleid,1); ?>
										</select>
										<input type="hidden" name="txt_level" id="txt_level" value="<?php if($_GET['staffid']!=''){ echo $levelid; } ?>">
									</div>
									<div class="grid2" align="center">Designation</div>
									<div class="grid4">
										<select id="cmb_designation" name="cmb_designation" required onChange="func_item_no()" tabindex="4">
											<option value=""> --- Designation --- </option>
											<?php echo $objBind->BindDesignation($designationid,0); ?>
										</select>    
									</div>
									<div class="grid12 grid-empty"></div>
									<div class="grid2">Email - ID</div>
									<div class="grid4">
										<input type="text"  name='email' id='email' required value="<?php if($_GET['staffid']!='') echo $email; ?>" tabindex="5">
									</div>
									<div class="grid2" align="center">Mobile No.&emsp;</div>
									<div class="grid4">
										<input type="text" name='mobile' id='mobile' onKeyPress="return isIntegerValue(event,this);" required value="<?php if($_GET['staffid']!='') echo $mobile; ?>" tabindex="6"/>
									</div>
									<div class="grid12 grid-empty"></div>
									<div class="grid2">Intercom</div>
									<div class="grid4">
										<input type="text" name='intercom' id='intercom' onKeyPress="return isIntegerValue(event,this);" required value="<?php if($_GET['staffid']!='') echo $intercom; ?>" tabindex="7"/>
									</div>
									<div class="grid2" align="center">Staff Photo&nbsp;</div>
									<div class="grid4">
									<?php if($_GET['staffid']!=''){ ?>
										<input type="file" id="image" name="image" onChange="this.style.width = '100%';" onBlur="uploadfile()" tabindex="8">
										<span id="dbimage" style="display:"><?php echo $staffimage; ?></span>
									<?php }else{ ?>
										<input type="file" class="text" name="image" id="image" size="38" style="height:23px; width:130px; overflow:hidden" />
									<?php } ?>
									</div>
								</div>
								<div class="main-content grid3 main-content-body" style="border:none">
									<div class="grid12" style="border:1px solid #DFDFDF">
										<?php if(($_GET['icno'] != "")&&($staffImage != "")){ ?>					
											<img id='img-upload' src="uploads/<?php echo $staffimage; ?>" style="height:150px"/>
										<?php }else{ ?>
											<img id='img-upload' src="uploads/profile_default.png" style="height:150px"/>
										<?php } ?>
									</div>
								</div>
								<div class="grid12 grid-empty"></div>
							</div>
							<div class="grid2"></div>
					  	</div>
					  
					</div>
					<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
						<div class="buttonsection">
							<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
						</div>
						<div class="buttonsection">
							<input type="submit" name="submit" id="submit" data-type="submit" value=" Save "/>
						</div>
					</div>
				</blockquote>
			</div>
 		</div>
	</div>
		
<!--==============================footer=================================-->
		<?php   include "footer/footer.html"; ?>
		<script src="js/jquery.hoverdir.js"></script>
		<script>
			$("#cmb_staff_role").chosen();
			$("#cmb_designation").chosen();
			var msg = "<?php echo $msg; ?>";
			var success = "<?php echo $success; ?>";
			var titletext = "";
				document.querySelector('#top').onload = function(){
					if(msg != "")
					{
						if(success == 1)
						{
							swal("", msg, "success");
						}
						else
						{
							swal(msg, "", "");
						}
					}
				};
				$(document).ready( function() {
					/*$(document).on('change', '.btn-file :file', function() {
					var input = $(this),
						label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
					input.trigger('fileselect', [label]);
					});
			
					$('.btn-file :file').on('fileselect', function(event, label) {
						
						var input = $(this).parents('.input-group').find(':text'),
							log = label;
						
						if( input.length ) {
							input.val(log);
						} else {
							if( log ) alert(log);
						}
					
					});*/
					function readURL(input) {
						if (input.files && input.files[0]) {
							var reader = new FileReader();
							
							reader.onload = function (e) {
								$('#img-upload').attr('src', e.target.result);
							}
							
							reader.readAsDataURL(input.files[0]);
						}
					}
			
					$("#image").change(function(){
						readURL(this);
					}); 	
				});
			</script>
		</form>
	</body>
</html>
	