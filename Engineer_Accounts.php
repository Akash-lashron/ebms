<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
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
		$mobile			=	"";//trim($_POST['mobile']);
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
			$target_dir 		= "uploads/";
			$target_file 		= $target_dir . basename($_FILES["image"]["name"]);
			$currentfilename 	= basename($_FILES["image"]["name"]);
			//$currentfilename = $staffcode.$currentfilename;
			$checkupload 		= 0;
			$imageFileType 		= pathinfo($target_file, PATHINFO_EXTENSION);
			
			/*if (file_exists($target_file)) { $checkupload = 1;}*/
			//else if ($_FILES["image"]["size"] > 500000) {$checkupload = 2;}
			if (strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "jpeg") 
			{
				$checkupload = 3;
			}
			// Check if $checkupload is set to 0 by an error
		   	if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) 
			{
				$checkupload = 0;
			} 
			/*if($checkupload == 1) { $msg=$msg. "Sorry, Uploaded file already exists "; }*/
			//else if($checkupload == 2) { $msg=$msg. "Sorry, Uploaded file size too large"; }
			if($checkupload == 3) 
			{ 
				$msg=$msg. "Upload file only JPG or JPEG formats";
			}
			else
			{			
				if($staffid!='')
				{	
					$staffupdate="update staff set staffcode='$staffcode',staffname='$staffname',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',sectionid='2',image='$currentfilename' where staffid='$staffid'";
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
					$staffinsert="insert into staff set staffcode='$staffcode',staffname='$staffname',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',sectionid='2',image='$currentfilename',active='1',userid='1' ";
					$staffinsertresult=mysql_query($staffinsert);	
					$status="i";
					if($staffinsertresult == true)
					{
						$msg = "Staff Added Sucessfully..!!";
						$success = 1;
					}
					else
					{
						$msg = "Error...!!";
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
				$staffupdate="update staff set staffcode='$staffcode',staffname='$staffname',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',sectionid='2',image='$staffimage' where staffid='$staffid'";
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
				$staffinsert="insert into staff set staffcode='$staffcode',staffname='$staffname',designationid='$designationid',sroleid='$sroleid',levelid='$levelid',email='$email',mobile='$mobile',intercom='$intercom',dob='$dob',doj='$doj',sectionid='2',image='$currentfilename',active='1',userid='1' ";
				$staffinsertresult=mysql_query($staffinsert);
				if($staffinsertresult == true)
				{
					$msg = "Staff Added Sucessfully";
					$success = 1;
				}
				else
				{
					$msg = "Error...!!";
				}	 
				$status="i";
				//echo $staffinsert;exit;
			}
		}
		
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
{	alert("hai");
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
	url = "EngineerList_Accounts.php";
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
		
	/*$( "#dob" ).datepicker({
    changeMonth: true,
    changeYear: true,
	dateFormat: "dd/mm/yy",
	yearRange: "1950:+20",
	maxDate: new Date,
	defaultDate: new Date,
    });
	
    $( "#doj" ).datepicker({
    changeMonth: true,
    changeYear: true,
	dateFormat: "dd/mm/yy",
	yearRange: "1950:+20",
	maxDate: new Date,
	defaultDate: new Date,
    });*/
				//var design = $("#hid_designation").val();
				//$("#cmb_designation").val(design);
				$.fn.validateicnumber = function(event) { 
					if($("#txt_ic_no").val()==""){ 
					var a="Please Enter IC Number";
					$('#val_icno').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_icno').text(a);
					}
				}
				$.fn.validatestaffname = function(event) { 
					if($("#staffname").val()==""){ 
					var a="Please Enter Staff Name";
					$('#val_staffname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_staffname').text(a);
					}
				}
				$.fn.validatedesignation = function(event) { 
					if($("#cmb_designation").val()==""){ 
					var a="Please select Designation Name";
					$('#val_designation').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_designation').text(a);
					}
				}
				$.fn.validateemailid = function(event) { 
					if($("#email").val()=="")
					{ 
						var a="Please Enter Email ID";
						$('#val_emailid').text(a);
						event.preventDefault();
						event.returnValue = false;
						//return false;
					}
					else
					{
						var email = $("#email").val(); 
						if (validateEmail(email)) 
						{
							var a="";
							$('#val_emailid').text(a);
						}
						else
						{
							var a = "Please Enter Valid Email ID.";
							$('#val_emailid').text(a);
							event.preventDefault();
							event.returnValue = false;
						}
				
					}
				}
				/*$.fn.validatemobileno = function(event) { 
					if($("#mobile").val()==""){ 
					var a="Please Enter Mobile Number";
					$('#val_mobileno').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_mobileno').text(a);
					}
				}*/
				$.fn.validateintercomno = function(event) { 
					if($("#intercom").val()==""){ 
					var a="Please Enter Intercom Number";
					$('#val_intercom').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_intercom').text(a);
					}
				}
				/*$.fn.validatedob = function(event) { 
					if($("#dob").val()==""){ 
					var a="Please Enter Date Of Birth";
					$('#val_dob').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_dob').text(a);
					}
				}*/
				/*$.fn.validatedoj = function(event) { 
					if($("#doj").val()==""){ 
					var a="Please Enter Date of Joining";
					$('#val_doj').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_doj').text(a);
					}
				}*/
				$.fn.validatestaffrole = function(event) { 
					if($("#cmb_staff_role").val()=="")
					{ 
					var a="Please Select Staff Role";
					$('#val_staff_role').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else
					{
					var a="";
					$('#val_staff_role').text(a);
					}
				}
		$("#txt_ic_no").keyup(function(event){
		$(this).validateicnumber(event);
		});
		$("#staffname").keyup(function(event){
		$(this).validatestaffname(event);
		});
		$("#cmb_designation").change(function(event){
		$(this).validatedesignation(event);
		});
		$("#email").change(function(event){
		$(this).validateemailid(event);
		});
		/*$("#mobile").keyup(function(event){
		$(this).validatemobileno(event);
		});*/
		$("#intercom").keyup(function(event){
		$(this).validateintercomno(event);
		});
		/*$("#dob").keyup(function(event){
		$(this).validatedob(event);
		});
		$("#doj").keyup(function(event){
		$(this).validatedoj(event);
		});*/
		$("#cmb_staff_role").change(function(event){
		$(this).validatestaffrole(event);
		});
		$("#top").submit(function(event){
		$(this).validateicnumber(event);
		$(this).validatestaffname(event);
		$(this).validatedesignation(event);
		$(this).validateemailid(event);
		//$(this).validatemobileno(event);
		$(this).validateintercomno(event);
		//$(this).validatedob(event);
		//$(this).validatedoj(event);
		$(this).validatestaffrole(event);
		});
});
</script>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
</SCRIPT>
	<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="form">
		 <?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
	<div class="container_12">
		<div class="grid_12">
		<div align="right"><a href="EngineerList_Accounts.php?edit=new">View&nbsp;&nbsp;</a></div>
			<blockquote class="bq1">
				<div class="title">Staff Registration</div>		
				
					<!----------===================================== HIDDEN FIELDS =========================================--------->
					<input type="hidden" name="staffid" id="staffid" value="<?php echo $_GET['staffid']; ?>"> 
                      <div class="container">
						<table width="100%"  bgcolor="#E8E8E8" border="1" cellpadding="0" cellspacing="0" align="center" >
							<tr><td width="5%">&nbsp;</td><td colspan="5">&nbsp;</td></tr>	
							<tr><td width="5%">&nbsp;</td><td colspan="5">&nbsp;</td></tr>	
								<input type="hidden" name="dummyicno" id="dummyicno" class="textboxdisplay" style="width:297px;" value="<?php if($_GET['staffid']!='') echo $staffcode; ?>"/>
								<input type="hidden"  name='dummyemail' id='dummyemail' class="textboxdisplay" value="<?php if($_GET['staffid']!='') echo $email; ?>">
                             <tr height="25px">
								<td>&nbsp;</td>
								<td class="label" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Staff ICNO</td>
                                <td class="labeldisplay">
								<?php 
								if($_GET['staffid']!='')
								{
								?>
								<input type="text" name="txt_ic_no" id="txt_ic_no" class="textboxdisplay" style="width:297px;" value="<?php echo $staffcode; ?>" tabindex="1" maxlength="10"/>
								<?php
								}
								else
								{
								?>
								<input type="text" name="txt_ic_no" id="txt_ic_no" class="textboxdisplay" style="width:297px;" tabindex="1" onBlur="func_icno();" maxlength="10"/>
								<?php
								}
								?>
								</td> 
								<td width="5%">&nbsp;</td>    										
								<td  class="label">Staff Name</td>
								<td class="labeldisplay">
								<?php 
								if($_GET['staffid']!='')
								{
								?>
								<input type="text" name='staffname' id='staffname' class="textboxdisplay" value="<?php echo $staffname; ?>" style="width:297px;" maxlength="50" tabindex="2" onKeyPress="return onlyAlphabets(event,this);">
								<?php
								}
								else
								{
								?>
								<input type="text" name='staffname' id='staffname' class="textboxdisplay" style="width:297px;" maxlength="40" tabindex="2" onKeyPress="return onlyAlphabets(event,this);">
								<?php
								}
								?>
								</td>
                            </tr>

							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay" id="val_icno" style="color:red">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay" id="val_staffname" style="color:red">&nbsp;</td>
							</tr>
							<tr height="25px">
								<td>&nbsp;</td>
								<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Role Name</td>
								<td class="labeldisplay">
									<select name="cmb_staff_role" id="cmb_staff_role" class="textboxdisplay" style="width:297px;" onChange="getStafflevel(this)">
										<option value="">--------------- Select Staff Role ---------------</option>
										 <?php echo $objBind->BindStaffRole($sroleid,2); ?>
									</select>
									<input type="hidden" name="txt_level" id="txt_level" value="<?php if($_GET['staffid']!=''){ echo $levelid; } ?>">
								</td>
								<td>&nbsp;</td>
								<td class="label">Designation</td> 
                                <td class="labeldisplay">
									<select id="cmb_designation" name="cmb_designation" onChange="func_item_no()" class="textboxdisplay" style="width:297px;height:22px;" tabindex="3">
										<option value=""> ------------------- Select Role ------------------ </option>
											<?php echo $objBind->BindDesignation($designationid,2); ?>
										</select> 
									<input type="hidden" name="hid_designation" id="hid_designation" value="<?php if($_GET['staffid']!='') echo $role_code; ?>">   
								</td>
							</tr>
 							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay" id="val_staff_role" style="color:red">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay" id="val_designation" style="color:red">&nbsp;</td>
							</tr>
                            <tr height="25px">
								<td>&nbsp;</td>
                                <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email - ID</td> 
                                <td class="labeldisplay">
									<input type="text"  name='email' id='email' class="textboxdisplay" value="<?php if($_GET['staffid']!='') echo $email; ?>" style="width:297px;" tabindex="4"  maxlength="30">
								</td>
								<td>&nbsp;</td>
								<td class="label">Intercom No.</td> 
								<td>
								<input type="text" name='intercom' id='intercom' class="textboxdisplay" value="<?php if($_GET['staffid']!='') echo $intercom; ?>" style="width:297px;" maxlength="10" tabindex="5" onKeyPress="return isNumber(event)"/>
								</td>                            
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay" id="val_emailid" style="color:red">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay" id="val_intercom" style="color:red">&nbsp;</td>
							</tr>
                            <tr height="25px">
								<td>&nbsp;</td>
                                <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Photo Upload</td> 
								<?php
								if($_GET['staffid']!='')
								{
								?>
									<td>
									<input type="file" id="image" name="image" style="width: 80px;" onChange="this.style.width = '100%';" onBlur="uploadfile()" >
									<!--<input type="file" id="image" name="image" class="filehidden" >-->
									<span id="dbimage" style="display:"><?php echo $staffimage; ?></span></td>
									<!--<td width="20%"><?php echo $staffimage; ?></td>-->
									</td>
								<?php
								}
								else
								{
								?>
									<td><input type="file" class="text" name="image" id="image" size="38" style="height:23px;" /></td>
								<?php
								}
								?>
								<td>&nbsp;</td>
								<td class="label">&nbsp;</td> 
								</td>                            
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay">&nbsp;</td>
							</tr>
							<!--<tr height="25px">
								<td>&nbsp;</td>
                                <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date of Birth</td> 
                                <td class="labeldisplay"><input type="text"  name='dob' id='dob' class="textboxdisplay" value="<?php if($_GET['staffid']!='') { echo $DOB; } else { echo date('d/m/Y'); } ?>"size="13" tabindex="7" ></td>
                                <td>&nbsp;</td>
								<td class="label">Date of Joining</td> 
                                <td class="labeldisplay"><input type="text"  name='doj' id='doj' class="textboxdisplay" value="<?php if($_GET['staffid']!='') { echo $DOJ; } else { echo date('d/m/Y'); } ?>"size="13" tabindex="8" ></td>
                            </tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay" id="val_dob" style="color:red">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay" id="val_doj" style="color:red">&nbsp;</td>
							</tr>	-->			
							<!--<tr>
								<td>&nbsp;</td>
								<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Photo Upload</td>
								<?php
								//if($_GET['staffid']!='')
								//{
								?>
									<td colspan="2">
									<input type="file" id="image" name="image" style="width: 80px;" onChange="this.style.width = '100%';" onBlur="uploadfile()" >
									<span id="dbimage" style="display:"><?php echo $staffimage; ?></span></td>
									</td>
								<?php
								//}
								//else
								//{
								?>
									<td colspan="2"><input type="file" class="text" name="image" id="image" size="38" style="height:23px;" /></td>
								<?php
								//}
								?>
								</td>
								<td class="label">Staff Section</td>
								<td>
									<select name="cmb_staff_section" id="cmb_staff_section" class="textboxdisplay" style="width:297px;">
										<option value="">-----------------  Select Section  -----------------</option>
										 <?php //echo $objBind->BindStaffSection($sectionid); ?>
									</select>
								</td>
								</tr>-->
									
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td class="labeldisplay" id="val_staff_section" style="color:red">&nbsp;</td>
							</tr>
                           <!-- <tr>
                                <td colspan="8" height="35px;">
									<center>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="submit" name="submit" id="submit" data-type="submit" value=" Save "/>&nbsp;&nbsp;&nbsp;
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
									</center>
								</td>
							</tr>
							<tr><td colspan="6">&nbsp;</td></tr>-->
						</table>
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
			</script>
		</form>
	</body>
</html>
	