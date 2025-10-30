<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/common.php';
checkUser();
$msg='';
$userid = $_SESSION['userid'];
$select_pwd_query = "select password from users where userid = '$userid'";
$select_pwd_sql = mysql_query($select_pwd_query);
if($select_pwd_sql == true)
{
	$PwdList = mysql_fetch_object($select_pwd_sql);
	$curr_password = md5($PwdList->password);
}
//echo $curr_password;
if($_POST['submit'])
{

	 $msg='';
	 
	 $old_password = trim($_POST['txt_curr_password']); 
	 $new_password = trim($_POST['txt_new_password']);
	 $new_conf_password = trim($_POST['txt_new_conf_password']);
	 $PwdMatch = 0;
	$OldPassword = md5($old_password);
	$SelectQuery = "SELECT * FROM users WHERE password = '$OldPassword' AND userid = '$userid'"; 
	$SelectSql = mysql_query($SelectQuery);
	if($SelectSql == true){
		if(mysql_num_rows($SelectSql) > 0){
			$PwdMatch = 1;
		}
	}
	if($PwdMatch == 1){
		 if($new_password == $new_conf_password)
		 {
			 $new_password = md5($new_password);
			 $change_pwd_query="update users set password = '$new_password' where userid = '$userid'";
			 //echo $change_pwd_query;exit;
			 $change_pwd_sql=mysql_query($change_pwd_query);
		 }
		 
		 if($change_pwd_sql == true)
		 { 
			 $msg='Password Changed Successfully..!!!';
			 $success = 1;
		 }
		 else
		 {
			$msg='Something Error..!!!';
		 }
	}else{
		$msg='Error : Invalid current password..!!!';
	}
}
  
?>
<?php include "Header.html"; ?>
   <script language="JavaScript" type="text/javascript" src="script/validfn.js"></script>
   <script type="text/javascript"  language="JavaScript">
   	 function goBack()
	 {
	   	url = "dashboard.php";
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
				/*$.fn.validateenggname = function(event) { 
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
				}*/
				$.fn.validatepassword = function(event) { 
					if($("#txt_curr_password").val()==""){ 
					var a="Please Enter Current Password";
					$('#val_curr_pwd').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_curr_pwd').text(a);
					}
				}
				$.fn.validatenewpassword = function(event) { 
					if($("#txt_new_password").val()==""){ 
					var a="Please Enter New Password";
					$('#val_new_pwd').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_new_pwd').text(a);
					}
				}
				$.fn.validatenewconfpassword = function(event) { 
					if($("#txt_new_conf_password").val()==""){ 
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
				$.fn.checkcurentpassword = function(event) { 
					var pwd = $("#txt_password").val();
					var curr_pwd = $("#txt_curr_password").val();
					//alert(pwd)
					//alert(curr_pwd)
					if(curr_pwd != "")
					{
						if(pwd != curr_pwd)
						{ 
							var a="Please Enter Valid Current Password";
							$('#val_curr_pwd').text(a);
							event.preventDefault();
							event.returnValue = false;
							//return false;
						}
						else
						{
							var a="";
							$('#val_curr_pwd').text(a);
						}
					}
				}
				$.fn.validatecheckpassword = function(event) { 
				
					var pwd = $("#txt_new_password").val();
					var conf_pwd = $("#txt_new_conf_password").val();
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
		/*$("#cmb_engname").change(function(event){
		$(this).validateenggname(event);
		});*/
		$("#txt_curr_password").keyup(function(event){
		$(this).validatepassword(event);
		});
		$("#txt_new_password").keyup(function(event){
		$(this).validatenewpassword(event);
		});
		$("#txt_new_conf_password").keyup(function(event){
		$(this).validatenewconfpassword(event);
		});
		$("#submit").click(function(event){
		$(this).validatecheckpassword(event);
		});
		$("#top").submit(function(event){
		//$(this).validateenggname(event);
		$(this).validatepassword(event);
		$(this).validatenewpassword(event);
		$(this).validatenewconfpassword(event);
		$(this).validatecheckpassword(event);
		//$(this).checkcurentpassword(event);
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
                            <div class="title">Change Password</div>
                <div class="container_12">
                    <div class="grid_12">
					<!--<div align="right"><a href="UsersList_Accounts.php">View&nbsp;&nbsp;</a></div>-->
                        <blockquote class="bq1">
                            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="19%">&nbsp;</td></tr>
								<tr><td><input type="hidden" name='txt_password' id='txt_password' class="textboxdisplay" value="<?php echo $curr_password; ?>" size="40"></td></tr>
								<tr> 
								    <td>&nbsp;</td>
								    <td  class="label">Current Password</td>
									<td><input type="password" name='txt_curr_password' id='txt_curr_password' class="textboxdisplay" size="40"></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_curr_pwd" style="color:red">&nbsp;</td>
								</tr>
								<tr>
								    <td>&nbsp;</td>
								    <td  class="label">New Password</td>
									<td> <input type="password" name='txt_new_password' id='txt_new_password' class="textboxdisplay" maxlength="8" size="40" ></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_new_pwd" style="color:red">&nbsp;</td>
								</tr>
                                <tr>
								    <td>&nbsp;</td>
                                    <td  class="label">Confirm New Password</td> 
                                    <td><input type="password"  name='txt_new_conf_password' id='txt_new_conf_password' class="textboxdisplay" maxlength="8"   size="40" /></td>
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
										<div class="buttonsection"> <?php //echo "GRG".$userid; ?>
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
