<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
include "library/common.php";
$sectionid = $_SESSION['staff_section'];
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
//echo $sectionid;exit;
$msg='';
if($_POST['btn_save'])
{
	//echo "submit";exit;
	 $ch_is_admin=$_POST['ch_is_admin'];
	 if($ch_is_admin == 1)
	 {
		$is_admin  = 1;
	 }
	 else
	 {
		$is_admin  = 0;
	 }
	 $msg='';
	 $staffid=trim($_POST['cmb_engname']);
	 //echo $staffid;exit;
	 $icno = getICNO($staffid);
	 $username=$icno;//trim($_POST['username']);
	 $password=$icno;//trim($_POST['password']);
	 
	 
 	 $sectionid = $_SESSION['staff_section'];
	 $sqlcreateuser="insert into users set username='$username',password='$password',staffid='$staffid',sectionid='$sectionid',isadmin='$is_admin',active='1',usersid=".$_SESSION['userid'];
	 $rsuserquery=mysql_query($sqlcreateuser);
	 
	 $updatestaff="update staff set useracc='1' where staffid='$staffid'";
	 $rsupdatestaff=mysql_query($updatestaff);
	 
	 if(($rsuserquery == true) && ($rsupdatestaff == true))
	 { 
		 $msg='User Created Successfully..!!!';
		 $success = 1;
	 }
	 else
	 {
	 	$msg='Something Error..!!!';
	 }
}
if($_POST['btn_update'])
{
	 $ch_is_admin=$_POST['ch_is_admin'];
	 if($ch_is_admin == 1)
	 {
		$is_admin  = 1;
	 }
	 else
	 {
		$is_admin  = 0;
	 }
	 $msg='';
	 $staffid=trim($_POST['cmb_engname']);
	 $icno = getICNO($staffid);
	 $userid=trim($_POST['txt_userid']);
	 $username=$icno;//trim($_POST['username']);
	 $password=$icno;//trim($_POST['password']);
 	 $sectionid = $_SESSION['staff_section'];
	 $sqlcreateuser="update users set isadmin='$is_admin',usersid='".$_SESSION['userid']."' where userid = '$userid'";
	 $rsuserquery=mysql_query($sqlcreateuser);
	 
	 if($rsuserquery == true)
	 { 
		 $msg='User Updated Successfully..!!!';
		 $success = 1;
	 }
	 else
	 {
	 	$msg='Something Error..!!!';
	 }
}
if($_GET['userid']!='')
{
	$userid = $_GET['userid'];
	$select_user_query = "select * from users where userid = '$userid'";
	$select_user_sql = mysql_query($select_user_query);
	if($select_user_sql == true)
	{
	 	$UList = mysql_fetch_object($select_user_sql);
		$staffid = $UList->staffid;
		$username = $UList->username;
		$isadmin = $UList->isadmin;
		$password = $UList->password;
	}
}
?>
<?php include "Header.html"; ?>
   <script language="JavaScript" type="text/javascript" src="script/validfn.js"></script>
   <script type="text/javascript"  language="JavaScript">
   	 function goBack()
	 {
	   	url = "UsersList.php";
		window.location.replace(url);
	 }
	function validation()
	{
		if(document.form.cmb_engname.value == "")
		{	
		 alert("Please Select Engineer Name");
		 document.form.cmb_engname.focus();
		 return false;
		}
			
		x=alltrim(document.form.username.value)
		if(x.length == 0)
		{	
		 alert("Please Enter the User Name");
		 document.form.username.value='';
		 document.form.username.focus();
		 return false;
		}
		x=alltrim(document.form.password.value)
		if(x.length == 0)
		{	
		 alert("Please Enter the Password");
		 document.form.password.value='';
		 document.form.password.focus();
		 return false;
		}
		x=alltrim(document.form.confpassword.value)
		if(x.length == 0)
		{	
		 alert("Please Enter the Confirm Password");
		 document.form.confpassword.value='';
		 document.form.confpassword.focus();
		 return false;
		}
	}
	function checkcpassword()
	{
		var pass=document.form.password.value
		var cpass=document.form.confpassword.value
		if(pass.length!=0 && cpass.length!=0)
		{
			if(pass!=cpass)
			{
				alert("Password and Confirm Password should be equal");
				document.form.confpassword.focus();
				document.form.confpassword.value='';
				return false;
			}
		}
	}
	
	function checkpassword()
	{
		checkcpassword()
		
		var pass=document.form.password.value
		if(pass.length==0)
		{
			alert("Please Enter Password");
			document.form.confpassword.value='';
			document.form.passworddd.focus();
			return false;
		}
	}
	function View_page()
	{
	 url = "UsersList.php";
	 window.location.replace(url);
	}

	
	function func_check_username()
	{	
		var xmlHttp;
		var data; 
		document.form.txt_username_check.value = "";
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="check_username.php?username="+alltrim(document.form.username.value)+"&userid="+alltrim(document.form.txt_userid.value);
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText;
				//alert(data)
				if(data>0)
				{	//alert(data);
					document.form.txt_username_check.value = 1;
				}
				else
				{
					document.form.txt_username_check.value = "";
				}
			}
		}
		xmlHttp.send(strURL);
	}
	
	$(function(){
				$.fn.validateenggname = function(event) { 
					if($("#cmb_engname").val()==""){ 
					var a="Please Select Staff's Name";
					$('#val_engname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_engname').text(a);
					}
				}
   				$.fn.validateusername = function(event) { 
					if($("#username").val()==""){ 
					var a="Please Enter User Name";
					$('#val_uname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_uname').text(a);
					}
				}
				$.fn.validatepassword = function(event) { 
					if($("#password").val()==""){ 
					var a="Please Enter Password";
					$('#val_pwd').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_pwd').text(a);
					}
				}
				$.fn.validateconfpassword = function(event) { 
					if($("#confpassword").val()==""){ 
					var a="Please Enter Confirm Password";
					$('#val_conf_pwd').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_conf_pwd').text(a);
					}
				}
				$.fn.validatecheckpassword = function(event) { 
				
					var pwd = $("#password").val();
					var conf_pwd = $("#confpassword").val();
					if((pwd != "") && (conf_pwd != ""))
					{ 
						if(pwd != conf_pwd)
						{
							var a="Passwords did not match with each other.";
							$('#val_check_pwd').text(a);
							event.preventDefault();
							event.returnValue = false;
						}
						else
						{
							var a="";
							$('#val_check_pwd').text(a);
						}
						//return false;
					}
					/*else{
					var a="";
					$('#val_conf_pwd').text(a);
					}*/
				}
				$.fn.validatecheckusername = function(event) { 
					var check = $("#txt_username_check").val();
					if(check == 1)
					{ 
					var a="Username already exist. Please try another name";
					$('#val_check_uname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_check_uname').text(a);
					}
				}
		$("#cmb_engname").change(function(event){
		$(this).validateenggname(event);
		});
		$("#username").keyup(function(event){
		$(this).validateusername(event);
		});
		$("#password").keyup(function(event){
		$(this).validatepassword(event);
		});
		$("#confpassword").keyup(function(event){
		$(this).validateconfpassword(event);
		});
		$("#btn_save").click(function(event){
		$(this).validatecheckpassword(event);
		});
		$("#top").submit(function(event){
		$(this).validateenggname(event);
		$(this).validateusername(event);
		$(this).validatepassword(event);
		$(this).validateconfpassword(event);
		$(this).validatecheckpassword(event);
		$(this).validatecheckusername(event);
		});
   });
	</script>
	<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content" align="center">
                <div class="title">Create User</div>
                <div class="container_12">
                    <div class="grid_12">
					<div align="right"><!--<a href="UsersList.php">View&nbsp;&nbsp;</a>-->&nbsp;</div>
                        <blockquote class="bq1">
                            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="25%">&nbsp;</td></tr>
								<tr>
								    <td>&nbsp;</td>
                                    <td class="label">Staff Name</td>
									<td class="labeldisplay">
									<?php if($_GET['userid'] != ""){ ?>
									<select name="cmb_engname" id="cmb_engname" style="width:298px;height:22px;" class="textboxdisplay">
										<?php
											$sqleng	= "select staffid, staffname from staff where active = 1 AND sectionid = '$sectionid' and staffid = '$staffid'";
											$rseng	= mysql_query($sqleng);
											$SList 	= mysql_fetch_object($rseng);
										?>
										<option value="<?php echo $SList->staffid; ?>"><?php echo $SList->staffname; ?></option>
									 </select>
									<?php } else { ?>
									 <select name="cmb_engname" id="cmb_engname" style="width:298px;height:22px;" class="textboxdisplay">
										<option value="">---------- Select Staff ----------</option>
										<?php
											$sqleng="select staffid,staffname from staff where useracc!=1 AND active = 1 AND sectionid = '$sectionid'";
											$rseng=mysql_query($sqleng);
											
											while($row=@mysql_fetch_assoc($rseng))
											{
												?>
												<option value="<?php echo $row['staffid']; ?>"><?php echo $row['staffname']; ?></option>
												<?php
											}
										 ?>
									 </select>
									<?php } ?>
									 </td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_engname" style="color:red">&nbsp;</td>
								</tr>
								<!--<tr>
								    <td>&nbsp;</td>
								    <td colspan="2"  class="labelhead"><u>Account Detail</u> </td>
								</tr>
								<tr><td>&nbsp;</td></tr>-->
								<tr> 
								    <td>&nbsp;</td>
								    <td  class="label">Is Admin</td>
									<td><input type="checkbox" name='ch_is_admin' id='ch_is_admin' class="textboxdisplay" value="1" size="40" <?php if($isadmin == 1){ echo 'checked="checked"'; } ?>></td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<?php if($_GET['userid'] != ""){ ?>
								<tr><td>&nbsp;</td></tr>
								<? }else{ ?>
								<tr> 
								    <td>&nbsp;</td>
									<td></td>
								    <td  class="label" style="color:#E80207">* Username and Password will be staff&nbsp;&nbsp;&nbsp;'ICNO'</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<?php } ?>
								<!--<tr> 
								    <td>&nbsp;</td>
								    <td  class="label">User Name</td>
									<td><input type="text" name='username' id='username' class="textboxdisplay" value="<?php if($_GET['userid'] != ""){ echo $username; } ?>" size="40" onBlur="func_check_username();"></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay"><span id="val_uname" style="color:red"></span><span id="val_check_uname" style="color:red"></span>&nbsp;</td>
								</tr>
								<tr>
								    <td>&nbsp;</td>
								    <td  class="label">Password</td>
									<td> 
									<?php if($_GET['userid'] != ""){ ?>
										<input type="password" name='password' id='password' class="textboxdisplay" value="<?php echo $password; ?>" disabled="disabled" style="background-color:#E1E1E1" maxlength="8" size="40" >
									<? }else{ ?>
										<input type="password" name='password' id='password' class="textboxdisplay" maxlength="8" size="40" >
									<?php } ?>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_pwd" style="color:red">&nbsp;</td>
								</tr>
                                <tr>
								    <td>&nbsp;</td>
                                    <td  class="label">Confirm Password</td> 
                                    <td>
									<?php if($_GET['userid'] != ""){ ?>
										<input type="password"  name='confpassword' id='confpassword' class="textboxdisplay" value="<?php echo $password; ?>" disabled="disabled" style="background-color:#E1E1E1" maxlength="8"   size="40" />
									<? }else{ ?>
										<input type="password"  name='confpassword' id='confpassword' class="textboxdisplay" maxlength="8"   size="40" />
									<?php } ?>
									</td>
                                </tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_conf_pwd" style="color:red">&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_check_pwd" style="color:red">&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay">&nbsp;</td>
								</tr>-->
                                <!--<tr>
                                    <td colspan="3" height="40px;">
									    <center>
										  <input type="submit" data-type="submit" value="Submit" name="submit" id="submit" onClick="func_check_username();"/>&nbsp;&nbsp;&nbsp;
										  <input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>&nbsp;&nbsp;&nbsp;
										</center>
									</td>
								</tr>
								
								<tr><td>&nbsp;&nbsp;</td></tr>-->
                            </table>
							<input type="hidden" name="txt_username_check" id="txt_username_check">
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection"><input type="button" class="backbutton" name="View" id="View" value="View" onClick="View_page();"/></div>
								<div class="buttonsection">
								<?php if($_GET['userid'] != ""){ ?>
									<input type="submit" value="Update" name="btn_update" id="btn_update"/>
								<?php }else{ ?>
									<input type="submit" value="Create" name="btn_save" id="btn_save"/>
								<?php } ?>
								<input type="hidden" name="txt_userid" id="txt_userid" value="<?php if($_GET['userid'] != ""){ echo $_GET['userid']; } ?>">
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
				$("#cmb_engname").chosen();
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					//swal(msg, "");
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
