<?php
 @ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
 $msg='';
 if($_POST['submit'])
 {

	 $msg='';
	 $username=trim($_POST['username']);
	 $password=trim($_POST['password']);
	 $staffid=trim($_POST['cmb_engname']);
	 
	 $sqlcreateuser="insert into users set username='$username',password='$password',staffid='$staffid',isadmin='0',active='1',sectionid = '2',usersid='1'";
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
  
?>
<?php include "Header.html"; ?>
   <script language="JavaScript" type="text/javascript" src="script/validfn.js"></script>
   <script type="text/javascript"  language="JavaScript">
   	 function goBack()
	 {
	   	url = "UsersList_Accounts.php";
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
	
	function func_check_username()
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
		strURL="check_username.php?username="+alltrim(document.form.username.value);
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				if(data>0)
				{	//alert(data);
					var a="Entered User Name already exists.";
					$('#val_uname').text(a);
					event.preventDefault();
					event.returnValue = false;
				}
				else
				{
					var a="";
					$('#val_uname').text(a);
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
		$("#submit").click(function(event){
		$(this).validatecheckpassword(event);
		});
		$("#top").submit(function(event){
		$(this).validateenggname(event);
		$(this).validateusername(event);
		$(this).validatepassword(event);
		$(this).validateconfpassword(event);
		$(this).validatecheckpassword(event);
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
                <div class="container_12">
                    <div class="grid_12">
					<div align="right"><a href="UsersList_Accounts.php">View&nbsp;&nbsp;</a></div>
                        <blockquote class="bq1">
                            <div class="title">Create User</div>
                            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="17%">&nbsp;</td></tr>
								<tr>
								    <td>&nbsp;</td>
                                                                    <td class="label">Staff Name</td>
									<td>
									<select name="cmb_engname" id="cmb_engname" style="width:298px;height:22px;" class="textboxdisplay">
									<option value="">----------------Select Staff-----------------</option>
									<?php

										$sqleng="select staffid,staffname from staff where useracc!=1 AND active = 1 AND sectionid = 2";
										$rseng=mysql_query($sqleng);
										
										while($row=@mysql_fetch_assoc($rseng))
										{
											?>
											<option value="<?php echo $row['staffid']; ?>"><?php echo $row['staffname']; ?></option>
											<?php
										}
									 ?>
									 
									 </select>
									 </td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_engname" style="color:red">&nbsp;</td>
								</tr>
								<tr>
								    <td>&nbsp;</td>
								    <td colspan="2"  class="labelhead"><u>Account Detail</u> </td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr> 
								    <td>&nbsp;</td>
								    <td  class="label">User Name</td>
									<td><input type="text" name='username' id='username' class="textboxdisplay" value="" size="40" onBlur="func_check_username();"></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_uname" style="color:red">&nbsp;</td>
								</tr>
								<tr>
								    <td>&nbsp;</td>
								    <td  class="label">Password</td>
									<td> <input type="password" name='password' id='password' class="textboxdisplay" value="" maxlength="8" size="40" ></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_pwd" style="color:red">&nbsp;</td>
								</tr>
                                <tr>
								    <td>&nbsp;</td>
                                    <td  class="label">Confirm Password</td> 
                                    <td><input type="password"  name='confpassword' id='confpassword' class="textboxdisplay" maxlength="8"   size="40" /></td>
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
								</tr>
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
									<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<input type="submit" data-type="submit" value="Submit" name="submit" id="submit" onClick="func_check_username();"/>
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
