<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'User Management'.$PTIcon."User Creation";
checkUser();
$sectionid 	= $_SESSION['sectionid'];
$staffid 	= $_SESSION['sid'];
$userid 	= $_SESSION['userid'];
$msg		= '';
if($_POST['btn_save']){
	$ChIsAdmin = $_POST['ch_is_admin'];
	if($ChIsAdmin == 1){
		$IsAdmin  = 1;
	}else{
		$IsAdmin  = 0;
	}
	$staffid 	 = trim($_POST['cmb_engname']);
	$SelectQuery = "select * from staff where staffid = '$staffid' and active = 1 and sectionid != 2";
	$SelectSql 	 = mysqli_query($dbConn,$SelectQuery,$conn2);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$SList = mysqli_fetch_object($SelectSql);
			$ICNO  = $SList->staffcode;
		}
	}
	//echo $ICNO;exit;
	$username		= $ICNO;//trim($_POST['username']);
	$password		= $ICNO;//trim($_POST['password']);
	$InsertQuery	= "insert into users set username='$username', password='$password', staffid='$staffid', sectionid='$sectionid', isadmin='$IsAdmin', active='1', usersid=".$_SESSION['userid'];
	$InsertSql		= mysqli_query($dbConn,$InsertQuery);
	 
	if($InsertQuery == true){ 
		$msg = 'User Created Successfully..!!!';
		$success = 1;
	}else{
		$msg = 'Error : User not created. Please try again.';
	}
}
if($_POST['btn_update']){
	$ChIsAdmin = $_POST['ch_is_admin'];
	if($ChIsAdmin == 1){
		$IsAdmin  = 1;
	}else{
		$IsAdmin  = 0;
	}
	$msg='';
	$staffid 		= $_POST['cmb_engname'];
	$userid 		= trim($_POST['txt_userid']);
	$UpdateQuery 	= "update users set isadmin='$IsAdmin',usersid='".$_SESSION['userid']."' where userid = '$userid'";
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
	$SelectQuery 	= "select * from users where userid = '$userid'";
	$SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
	 	$UList 		= mysqli_fetch_object($SelectSql);
		$staffid 	= $UList->staffid;
		$username 	= $UList->username;
		$isadmin 	= $UList->isadmin;
		$password 	= $UList->password;
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
								<div class="div2" align="center">
									&nbsp;
								</div>
								<div class="div8" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-b" align="center">Users Create </div>
										<div class="row" align="center">
											<div class="row">
												<div class="row innerdiv" align="center">
													<div class="row clearrow"></div>
													<div class="div2 rboxlabel">&nbsp;</div>
													<div class="div2 rboxlabel">Staff Name</div>
													<div class="div2 rboxlabel">&nbsp;</div>
													<div class="div4" align="center">
														<?php if($_GET['userid'] != ""){ ?>
															<select name="cmb_engname" id="cmb_engname" class="group" style="width:100%">
																<?php
																	$SelectQuery = "SELECT a.* FROM ".$dbName2.".staff a WHERE a.staffid = '$staffid' AND a.active = 1 AND a.sectionid != 2 order by a.staffcode asc";
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
																	$SelectQuery = "SELECT a.* FROM ".$dbName2.".staff a WHERE NOT EXISTS (SELECT * FROM ".$dbName.".users b WHERE b.staffid = a.staffid AND b.active = 1 AND b.sectionid != 2) AND a.active = 1 AND a.sectionid != 2 order by a.staffcode asc";
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
													<div class="div2 rboxlabel">Is Admin</div>
													<div class="div2 rboxlabel">&nbsp;</div>
													<div class="div6" align="left">
														<input type="checkbox" name='ch_is_admin' id='ch_is_admin' class="group" value="1" <?php if($isadmin == 1){ echo 'checked="checked"'; } ?>>
													</div>
													<div class="row clearrow"></div>
													<?php if($_GET['userid'] != ""){ ?>
													<div class="div2 rboxlabel">&nbsp;</div>
													<div class="div2 rboxlabel">&nbsp;</div>
													<div class="div2 rboxlabel">&nbsp;</div>
													<div class="div6 lboxlabel" align="left" style="color:#F90013">
														* Username and Password will be staff&nbsp;&nbsp;&nbsp;'ICNO'
													</div>
													<div class="row clearrow"></div>
													<?php } ?>
												</div>
												<input type="hidden" name="txt_username_check" id="txt_username_check">
                       						</div>
											<div class="row clearrow">&nbsp;</div>
											<div class="row">
												<div class="div12" align="center">
												<a data-url="UserList" class="btn btn-primary" style="margin-top:0px; padding:2px 14px 4px 14px;">Back</a>
												<?php if($_GET['userid'] != ""){ ?>
													<input type="submit" value="Update" name="btn_update" id="btn_update" class="btn btn-primary"/>
												<?php }else{ ?>
													<input type="submit" value="Create" name="btn_save" id="btn_save" class="btn btn-primary"/>
												<?php } ?>
												<input type="hidden" name="txt_userid" id="txt_userid" value="<?php if($_GET['userid'] != ""){ echo $_GET['userid']; } ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="div2" align="center"></div>
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
			</script>
        </form>
    </body>
</html>
