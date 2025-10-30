<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'User Management'.$PTIcon."User Creation";
checkUser();
$sectionid 	= $_SESSION['staff_section'];
$staffid 	= $_SESSION['sid'];
$userid 	= $_SESSION['userid'];
$msg		= '';
//echo $sectionid;exit;
//$ModuleArr = array("ACC"=>"Accounting","BIL"=>"Billing","BUD"=>"Budget","TEN"=>"Tendering");
$ModuleArr = array("BIL"=>"Billing","BUD"=>"Budget","TEN"=>"Tendering");
$ModuleSecCodeArr = array("BIL"=>"1","ACC"=>"2","BUD"=>"3","TEN"=>"4");
if($_POST['btn_save']){
	$UserType = $_POST['usertype'];
	if($UserType == 'A'){
		$IsAdmin  = 1;
		$ModuleRights = 'DESA,DESE,DESD,DESV,ENGA,ENGD,ENGV,ENGE,CRUA,CRUV,BKUP,MODR,AGEN,AGED,AGDE,AGUP,AGVW,SNCR,SNED,SNVW,MSTA,MSTE,MSTD,MSTV,DEAC,DEAV,DEQC,DEQV,MWOA,MWOE,MWOD,MWOV,MSTU,MSTC,MSTE,MSTD,MSTV,MBKG,SABG,MBKH,SABH,ABSG,MBKP,SABP,ABSP,RABL';
	}else{
		$IsAdmin  = 0;
		$ModuleRights = 'DESA,DESE,DESD,DESV,ENGA,ENGD,ENGV,ENGE,CRUA,CRUV,BKUP,MODR,AGEN,AGED,AGDE,AGUP,AGVW,SNCR,SNED,SNVW,MSTA,MSTE,MSTD,MSTV,DEAC,DEAV,DEQC,DEQV,MWOA,MWOE,MWOD,MWOV,MSTU,MSTC,MSTE,MSTD,MSTV,MBKG,SABG,MBKH,SABH,ABSG,MBKP,SABP,ABSP,RABL';
	}
	$ModAcc 	 = trim($_POST['cmb_modules']);
	$staffid 	 = trim($_POST['cmb_engname']);
	$sectionid	 = $ModuleSecCodeArr[$ModAcc];
	$SelectQuery = "SELECT * FROM staff WHERE staffid = '$staffid' AND active = 1 AND sectionid != 2";
	$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$SList = mysqli_fetch_object($SelectSql);
			$ICNO  = $SList->staffcode;
		}
	}
	$username	 = $ICNO;		//trim($_POST['username']);
	$password	 = md5($ICNO);		//trim($_POST['password']);
	$InsertQuery = "INSERT INTO users SET username='$username', password='$password', staffid='$staffid', sectionid='$sectionid', module_access='$ModAcc',
	ModuleRights = '$ModuleRights', isadmin='$IsAdmin', active='1', usersid=".$_SESSION['userid'];
	$InsertSql		= mysqli_query($dbConn,$InsertQuery);
	
	$UpdateQuery 	= "UPDATE staff SET sectionid='$sectionid', useracc='1' WHERE staffid = '$staffid'";
	$UpdateSql 		= mysqli_query($dbConn,$UpdateQuery);
	 
	if($InsertSql == true){
		$msg = 'User Created Successfully..!!!';
		$success = 1;
	}else{
		$msg = 'Error : User not created.. Please try again..!!';
	}
}
if($_POST['btn_update']){
	$UserType = $_POST['usertype'];
	if($UserType == 'A'){
		$IsAdmin  = 1;
	}else{
		$IsAdmin  = 0;
	}
	$msg='';
	$staffid 		= $_POST['cmb_engname'];
	$ModAcc 	 	= trim($_POST['cmb_modules']);
	$userid 		= trim($_POST['txt_userid']);
	$sectionid	 	= $ModuleSecCodeArr[$ModAcc];
	$UpdateQuery 	= "UPDATE users SET isadmin='$IsAdmin',module_access='$ModAcc',usersid='".$_SESSION['userid']."' WHERE userid = '$userid'";
	$UpdateSql 		= mysqli_query($dbConn,$UpdateQuery);
	
	$UpdateQuery 	= "UPDATE staff SET sectionid='$sectionid', useracc='1' WHERE staffid = '$staffid'";
	$UpdateSql 		= mysqli_query($dbConn,$UpdateQuery);
	 
	if($UpdateSql == true){ 
		$msg		= 'User Updated Successfully..!!!';
		$success 	= 1;
	}else{
		$msg = 'Error : User details not updated. Please try again.';
	}
}
if($_GET['userid']!=''){
	$userid 	 	= $_GET['userid'];
	$SelectQuery 	= "SELECT * FROM users WHERE userid = '$userid'";
	$SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
	 	$UList 		= mysqli_fetch_object($SelectSql);
		$staffid 	= $UList->staffid;
		$username 	= $UList->username;
		$isadmin 	= $UList->isadmin;
		$password 	= $UList->password;
		$ModuleAcc 	= $UList->module_access;
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">

<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
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
																<div class="div2 rboxlabel">&nbsp;</div>
																<div class="div2 lboxlabel">Staff Name</div>
																<div class="div6" align="center">
																	<?php if($_GET['userid'] != ""){ ?>
																		<select name="cmb_engname" id="cmb_engname" class="group" style="width:100%">
																			<?php
																				$SelectQuery = "SELECT a.* FROM staff a WHERE a.staffid = '$staffid' AND a.active = 1 order by a.staffcode asc";
																				$SelectSql	 = mysqli_query($dbConn,$SelectQuery);
																				if($SelectSql == true){
																					if(mysqli_num_rows($SelectSql)>0){
																						$SList 	= mysqli_fetch_object($SelectSql);
																						echo '<option value="'.$SList->staffid.'">'.$SList->staffcode.' - '.$SList->staffname.'</option>';
																					}
																				}
																			?>
																		</select>
																	<?php }else{ ?>
																		<select name="cmb_engname" id="cmb_engname" class="group" style="width:100%">
																			<option value=""> ------------ Select Staff ------------ </option>
																			<?php
																				$SelectQuery = "SELECT a.* FROM staff a WHERE NOT EXISTS (SELECT * FROM users b WHERE b.staffid = a.staffid AND b.active = 1) AND a.active = 1 order by a.staffcode asc";
																				$SelectSql	 = mysqli_query($dbConn,$SelectQuery);
																				if($SelectSql == true){
																					if(mysqli_num_rows($SelectSql)>0){
																						while($SList = mysqli_fetch_object($SelectSql)){
																							echo '<option value="'.$SList->staffid.'">'.$SList->staffcode.' - '.$SList->staffname.'</option>';
																						}
																					}
																				}
																			?>
																		</select>
																	<?php } ?>
																</div>
																<div class="div2 rboxlabel">&nbsp;</div>
																<div class="row clearrow"></div>
																<div class="div2 rboxlabel">&nbsp;</div>
																
																<div class="div2 lboxlabel">User Type</div>
																		<div class="div1 no-padding-lr" style="padding-left:1px; width:24%;">
																			<div class="inputGroup">
																				<input id="usertype_a" name="usertype" type="radio" value="A" <?php if($isadmin == '1'){ echo "checked=checked";} ?>/>
																				<label for="usertype_a" style="padding:4px 0px 4px 10px; width:100%; font-size:11px;" class="lboxlabel">&nbsp;Admin</label>
																			</div>
																		</div>
																		<div class="div1" style="padding-left:19px; width:24%;">
																			<div class="inputGroup">
																				<input id="usertype_u" name="usertype" type="radio" value="U" <?php if($isadmin == '0'){ echo "checked=checked";} ?>/>
																				<label for="usertype_u" style="padding:4px 0px 4px 10px; width:100%; font-size:11px;" class="lboxlabel">&nbsp;User</label>
																			</div>
																		</div>
																
																<div class="row clearrow"></div>
																<div class="div2 rboxlabel">&nbsp;</div>
																<div class="div2 lboxlabel">Module Access</div>
																<div class="div6" align="center">
																	<select name="cmb_modules" id="cmb_modules" class="" style="width:100%">
																		<option value=""> ------------ Select Module Access ------------ </option>
																		<?php
																			foreach($ModuleArr as $key => $value){
																				if((isset($ModuleAcc))&&($key == $ModuleAcc)){
																					$SelStr = 'selected="selected"';
																				}else{
																					$SelStr = '';
																				}
																				echo '<option value="'.$key.'"'.$SelStr.'>'.$value.'</option>';
																			}
																		?>
																	</select>
																</div>

																<?php //if($_GET['userid'] != ""){ ?>
																<div class="div2 rboxlabel">&nbsp;</div>
																<div class="div2 rboxlabel">&nbsp;</div>
																<div class="div2 rboxlabel">&nbsp;</div>
																<div class="div6 lboxlabel" align="left" style="color:#F90013">
																	* Username and Password will be staff&nbsp;&nbsp;'ICNO'
																</div>
																<div class="div2 rboxlabel">&nbsp;</div>
																<div class="row clearrow"></div>
																<?php //} ?>
																<input type="hidden" name="txt_username_check" id="txt_username_check">
																</div>
																<div class="row">
																	<div class="div12" align="center">
																		<a data-url="UserList" class="btn btn-info">Back</a>
																		<?php if($_GET['userid'] != ""){ ?>
																			<input type="submit" value="Update" name="btn_update" id="btn_update" class="btn btn-info"/>
																		<?php }else{ ?>
																			<input type="submit" value="Create" name="btn_save" id="btn_save" class="btn btn-info"/>
																		<?php } ?>
																		<input type="hidden" name="txt_userid" id="txt_userid" value="<?php if($_GET['userid'] != ""){ echo $_GET['userid']; } ?>">
																	</div>
																</div>
																<div class="row clearrow">&nbsp;</div>
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
				$("#cmb_engname").chosen();
				$("#cmb_modules").chosen();
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
					if(msg != ""){
						if(success == 1){
							BootstrapDialog.alert(msg);//swal("", msg, "success");
						}else{
							BootstrapDialog.alert("Error : "+msg);//swal(msg, "", "");
						}
					}
				};

				var KillEvent = 0;
				$("body").on("click","#btn_save", function(event){
					if(KillEvent == 0){
						var StaffNameVal 		= $("#cmb_engname").val();
						var StaffNameText		= $("#cmb_engname option:selected").text();
						var UserTypeVal 		= $("#usertype").val();
						var ModuleAccessVal	= $("#cmb_modules").val();
						
						if(StaffNameVal == ""){
							BootstrapDialog.alert("Please Select Staff Name..!!");
							event.preventDefault();
							event.returnValue = false;
						}else if ($('input[name="usertype"]:checked').length == 0){
							BootstrapDialog.alert("Please Select User Type Admin or User..!!");
							event.preventDefault();
							event.returnValue = false;
						}else if(ModuleAccessVal == ""){
							BootstrapDialog.alert("Please Select Access For the Modules..!!");
							event.preventDefault();
							event.returnValue = false;
						}else{
							event.preventDefault();
							BootstrapDialog.confirm({
								title: 'Confirmation Message',
								message: 'Are you sure want to Give Access to the Staff '+StaffNameText+' as User ?',
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
