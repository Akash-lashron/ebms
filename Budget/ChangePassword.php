<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
checkUser();
$msg='';
$PageName = $PTPart1.$PTIcon.'Change Password';
$userid = $_SESSION['userid'];
if($_POST['btn_change'] == " CHANGE "){
	$msg='';
	$OldPassword = trim($_POST['txt_curr_pwd']);
	$NewPassword = trim($_POST['txt_new_pwd']);
	$ConfNewPassword = trim($_POST['txt_conf_new_pwd']); 
	$PwdMatch = 0;
	$OldPassword = md5($OldPassword);
	$SelectQuery = "SELECT * FROM users WHERE password = '$OldPassword' AND userid = '$userid'";
	$SelectSql = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql) > 0){
			$PwdMatch = 1;
		}
	}
	if($PwdMatch == 1){
		if($NewPassword == $ConfNewPassword){
			$NewPassword = md5($NewPassword);
			$ChangeQuery = "UPDATE users SET password = '$NewPassword' WHERE userid = '$userid'";
			$ChangeSql = mysqli_query($dbConn,$ChangeQuery);
			if($ChangeSql == true){
				$msg = "Password changed successfully.";
			}else{
				$msg = "Password not changed. Please try again.";
			}
		}else{
			$msg = "Confirm password did not math with cinfirm new password. Enter valid password.";
		}
	}else{
		$msg = "Sorry current password is invalid. Enter valid password.";
	}
}
  
?>
<?php include "Header.html"; ?>
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
                    <div class="grid_12">
						
                        <blockquote class="bq1">
                           
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">&nbsp;Change Password</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
													
														<div class="row">
															<div class="div12" align="center">
																<div class="innerdiv2">
																	<div class="row" align="center">
																		<div class="row">
																			<div class="row clearrow"></div>
																			<div class="row clearrow"></div>
																			
																			<div class="div4 lboxlabel">Current Password</div>
																			<div class="div8" align="left">
																				<input type="password" name="txt_curr_pwd" id="txt_curr_pwd" required class="tboxsmclass" value="<?php if(isset($_GET['id'])){ if($PrDesc != ""){ echo $PrDesc; } } ?>">
																			</div>
																			<div class="div12"></div>
																			<div class="row clearrow"></div>
																			<div class="row clearrow"></div>
																			<div class="div4 lboxlabel">New Password</div>
																			<div class="div8" align="left">
																				<input type="password" name="txt_new_pwd" id="txt_new_pwd" required class="tboxsmclass" value="<?php if(isset($_GET['id'])){ if($PrCode != ""){ echo $PrCode; } } ?>">
																			</div>
																			<div class="div12"></div>
																			<div class="row clearrow"></div>
																			<div class="row clearrow"></div>
																			<div class="div4 lboxlabel">Confirm New Password</div>
																			<div class="div8" align="left">
																				<input type="password" name="txt_conf_new_pwd" id="txt_conf_new_pwd" required class="tboxsmclass" value="<?php if(isset($_GET['id'])){ if($PrCode != ""){ echo $PrCode; } } ?>">
																			</div>
																			<div class="div12"></div>
																			<div class="div4">&nbsp;</div>
																			<div class="div8 errtext" id="val_work" align="left">&nbsp;</div>
																			<div class="div12"></div>
																			<div class="row clearrow"></div>
																			<div class="div12">
																				<input type="submit" class="btn btn-info" value=" CHANGE " name="btn_change" id="btn_change"/>
																			</div>
																		</div>
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
							</div>
                        </blockquote>
                    </div>
                </div>
            </div>
            <!--==============================footer=================================-->
            <?php include "footer/footer.html"; ?>
        </form>
	<script>
		$(document).ready(function(){
			var KillEvent = 0;
			$("body").on("click","#btn_change", function(event){
				if(KillEvent == 0){
					var CurrPwd   	= alltrim($("#txt_curr_pwd").val());
					var NewPwd   	= alltrim($("#txt_new_pwd").val());
					var ConfNewPwd 	= alltrim($("#txt_conf_new_pwd").val());
					if(CurrPwd == ""){
						BootstrapDialog.alert("Current password should not be empty..!!");
						event.preventDefault();
						event.returnValue = false;
					}else if(NewPwd == ""){
						BootstrapDialog.alert("New Password should not be empty..!!");
						event.preventDefault();
						event.returnValue = false;
					}else if(ConfNewPwd == ""){
						BootstrapDialog.alert("Confirm new password should not be empty..!!");
						event.preventDefault();
						event.returnValue = false;
					}else if(NewPwd != ConfNewPwd){
						BootstrapDialog.alert("New Password & Confirm new password did not match with each other..!!");
						event.preventDefault();
						event.returnValue = false;
					}else{
						event.preventDefault();
						BootstrapDialog.confirm({
							title: 'Confirmation Message',
							message: 'Are you sure want to change Password ?',
							closable: false, // <-- Default value is false
							draggable: false, // <-- Default value is false
							btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
							btnOKLabel: 'Ok', // <-- Default value is 'OK',
							callback: function(result) {
								if(result){
									KillEvent = 1;
									$("#btn_change").trigger( "click" );
								}else {
									KillEvent = 0;
								}
							}
						});
					}
				}
			});
		});
		
		var msg = "<?php echo $msg; ?>";
		document.querySelector('#top').onload = function(){
			if(msg != ""){
				BootstrapDialog.show({
					message: msg,
					buttons: [{
						label: ' OK ',
						action: function(dialog) {
							dialog.close();
							window.location.replace('ChangePassword.php');
						}
					}]
				});
			}
		};
		</script>
    </body>
</html>
