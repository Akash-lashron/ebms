<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'EIC Creation';
checkUser();
$success = 0;
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}
/*
$TenNumArr = array();
$WrkNameArr = array();
$TenRegResult = "SELECT * FROM tender_register WHERE active = 1";
$TenRegResultSql = mysqli_query($dbConn,$TenRegResult);
if($TenRegResultSql == true){
	if(mysqli_num_rows($TenRegResultSql)>0){
		while($TRList = mysqli_fetch_object($TenRegResultSql)){
			$TenNumArr[$TRList->tr_id] = $TRList->tr_no;
			$WrkNameArr[$TRList->tr_id] = $TRList->work_name;
		}
	}
}
*/
if(isset($_POST['btn_back'])){
	header("Location:UploadModules.php");
}
if(isset($_POST['btn_save'])){
	/*
	$TotStaffListArr = array();
	$StaffQuery = "select * from staff where active = 1";
	$StaffQuerySql = mysqli_query($dbConn,$StaffQuery);
	if ($StaffQuerySql == true ) {
		if(mysqli_num_rows($StaffQuerySql) > 0){
			while($StaffRow = mysqli_fetch_array($StaffQuerySql)){
				$TotStaffListArr[$StaffRow['staffid']] = $StaffRow['staffname'];
			}
		}
	}
	*/
	//print_r($TotStaffListArr);exit;		

	$StaffIdArr 		= $_POST['txt_hid_staffid'];
	$ExistSecNameArr 	= $_POST['txt_hid_secname'];
	$SelSubSecIdArr 	= $_POST['cmb_sub_sec'];
	$ExistRoleNameArr 	= $_POST['txt_hid_rolename'];
	$SelStaffRoleIdArr 	= $_POST['cmb_staff_role'];

	foreach($StaffIdArr as $Key => $Value){
		$SelSubSecId 	= $SelSubSecIdArr[$Key];
		$ExistSecName 	= $ExistSecNameArr[$Key];
		$SelStaffRoleId = $SelStaffRoleIdArr[$Key];
		$ExistRoleName 	= $ExistRoleNameArr[$Key];
		if($SelSubSecId != ""){
			if(($Value != 0)&&($Value != NULL)&&($Value != "")){
				$UpdateQuery = "UPDATE staff SET sub_sec_id='$SelSubSecId' WHERE staffid = '$Value'";
				$UpdateQuerySql = mysqli_query($dbConn,$UpdateQuery);
			}
		}
		if($SelStaffRoleId != ""){
			if(($Value != 0)&&($Value != NULL)&&($Value != "")){
				$SRoleName = "";
				$SRQuery = "SELECT sroleid, role_name FROM staffrole WHERE sroleid='$SelStaffRoleId' AND active = 1 ORDER BY levelid desc";
				$SRQuerysql = mysqli_query($dbConn,$SRQuery);
				if($SRQuerysql == true){
					if(mysqli_num_rows($SRQuerysql)>0){
						$SrList = mysqli_fetch_object($SRQuerysql);
						$SRoleName = $SrList->role_name;
					}
				}
				$UpdateRoleQuery = "UPDATE staff SET sroleid='$SelStaffRoleId', srole_name='$SRoleName' WHERE staffid = '$Value'";
				$UpdateRoleQuerySql = mysqli_query($dbConn,$UpdateRoleQuery);
			}
		}
	}
	if($UpdateQuerySql == true){
		$msg = "Staff Section Details Saved Successfully ";
		$success = 1;
	}else{
		$msg = "Error...Staff Section Details Not Saved..!!!";
	}
}
$StaffSecArr = array();
$LiveWorksResult = "SELECT * FROM staff WHERE active = 1 ORDER BY designationid ASC";
$LiveWorksResultSql = mysqli_query($dbConn,$LiveWorksResult);
if($LiveWorksResultSql == true){
	if(mysqli_num_rows($LiveWorksResultSql)>0){
		//$SubSecResult = "SELECT sub_sec_id,sub_sec_name FROM sub_section WHERE active = 1 ORDER BY sub_sec_id ASC";
		$SubSecResult = "SELECT sub_sec_id,sub_sec_name FROM sub_section WHERE active = 1 AND par_dept_id != 0 ORDER BY sub_sec_id ASC";
		$SubSecResultSql = mysqli_query($dbConn,$SubSecResult);
		if($SubSecResultSql == true){
			if(mysqli_num_rows($SubSecResultSql)>0){
				while($WrkList = mysqli_fetch_object($SubSecResultSql)){
					$StaffSecArr[$WrkList->sub_sec_id] = $WrkList->sub_sec_name;
				}
			}
		}
	}
}
//print_r($StaffSecArr);
?>

<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript">
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
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
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center"> Engineer In Charge - View & Creation </div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																		<tr>
																			<th valign="middle"> SNo. </th>
																			<th valign="middle"> IC NO. </th>
																			<th valign="middle"> EMP NO. </th>
																			<th valign="middle"> EIC Name </th>
																			<th valign="middle"> Section Name </th>
																			<th valign="middle"> Select / Assign Section </th>
																			<th valign="middle"> Staff Section<br>Assigned Status </th>
																			<th valign="middle"> Staff Role Name </th>
																			<th valign="middle"> Select / Assign Role </th>
																			<th valign="middle"> Staff Role<br>Assigned Status </th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr class='labeldisplay'>
																			<?php
																				$SNO = 1; 
																				if($LiveWorksResultSql == true){
																					if(mysqli_num_rows($LiveWorksResultSql)>0){
																						while($WrkList = mysqli_fetch_object($LiveWorksResultSql)){
																							$StaffIdVal	 	= $WrkList->staffid;
																							$ICNumbVal 	 	= $WrkList->staffcode;
																							$EMPNumbVal 	= $WrkList->staff_emp_no;
																							$SelSubSecCVal 	= $WrkList->sub_sec_id;
																							$SECNameVal 	= $StaffSecArr[$SelSubSecCVal];
																							$EICNameVal     = $WrkList->staffname;
																							$SelStaffRoleCVal = $WrkList->sroleid;
																							$RoleNameVal     = $WrkList->srole_name;
																							//$splitCreateDateTimeStamp = explode(" ",$CreateDateTime);
																							//$CreateDate = $splitCreateDateTimeStamp[0];
																							//echo $CreateDate;
																			?>

																			<td class='tdrowbold' valign='middle' align='center'>
																				<?php echo $SNO; ?>
																			</td>
																			<td valign='middle' class='tdrow' align = 'justify'>
																				<?php echo $ICNumbVal; ?>
																			</td>
																			<td valign='middle' class='tdrow' align = 'justify'>
																				<?php echo $EMPNumbVal; ?>
																			</td>
																			<td valign='middle' class='tdrow' align = 'justify'>
																				<?php echo $EICNameVal; ?>
																				<input type="hidden" name="txt_hid_staffid[]" id="txt_hid_staffid" value="<?php echo $StaffIdVal; ?>"> 
																			</td>
																			<td valign='middle' class='tdrow' align = 'justify'>
																				<?php echo $SECNameVal; ?>
																				<input type="hidden" name="txt_hid_secname[]" id="txt_hid_secname" value="<?php echo $SECNameVal; ?>">
																			</td>
																			<td align='center' valign='middle' class='tdrow' style="width:18%;">
																				<!-- <select name="" id="" class="tboxsmclass"> -->
																				<select name="cmb_sub_sec[]" id="cmb_sub_sec" class="tboxsmclass" >
																					<option value="">--- Select ---</option>
																					<option value=""><?php echo $objBind->BindAllSubSection($SelSubSecCVal,0); ?></option>
																				</select>
																			</td>
																			<?php if(($SelSubSecCVal != 0) && ($SECNameVal != "")){ ?>
																				<td valign='middle' class='tdrow' align = 'center'>&nbsp;<i class="fa fa-check-circle-o" style="font-size:20px; color:#046929;"></i></td>
																			<?php }else{ ?>
																				<td valign='middle' class='tdrow' align = 'center'>&nbsp;<i class="fa fa-times-circle" style="font-size:20px; color:#EA253C;"></i></td>
																			<?php } ?>

									<!--			------------------------------------------------------------------------			-->

																			<td valign='middle' class='tdrow' align = 'justify'>
																				<?php echo $RoleNameVal; ?>
																				<input type="hidden" name="txt_hid_rolename[]" id="txt_hid_rolename" value="<?php echo $RoleNameVal; ?>">
																			</td>
																			<td align='center' valign='middle' class='tdrow' style="width:18%;">
																				<!-- <select name="" id="" class="tboxsmclass"> -->
																				<select name="cmb_staff_role[]" id="cmb_staff_role" class="tboxsmclass" >
																					<option value="">--- Select ---</option>
																					<option value=""><?php echo $objBind->BindAllStaffRole($SelStaffRoleCVal); ?></option>
																				</select>
																			</td>

																			<?php if(($SelStaffRoleCVal != 0) && ($RoleNameVal != "")){ ?>
																				<td valign='middle' class='tdrow' align = 'center'>&nbsp;<i class="fa fa-check-circle-o" style="font-size:20px; color:#046929;"></i></td>
																			<?php }else{ ?>
																				<td valign='middle' class='tdrow' align = 'center'>&nbsp;<i class="fa fa-times-circle" style="font-size:20px; color:#EA253C;"></i></td>
																			<?php } ?>

																		</tr>




																			<!--	<td class='tdrowbold' valign='middle' align='center'><?php// echo $SNO; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'>
																				<?php //echo $CcnoVal; ?>
																				<input type="hidden" name="txt_hid_ccno[]" id="txt_hid_ccno" value="<?php// echo $CcnoVal; ?>">
																			</td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php //echo $WONumbVal; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php// echo $WorkNameVal; ?></td>
																			<td class='tdrow' align='right' valign='middle'><?php// echo $WODateValDisp; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'>
																				<?php// echo $EICNameVal; ?>
																				<input type="hidden" name="txt_hid_eicname[]" id="txt_hid_eicname" value="<?php //echo $EICNameVal; ?>">
																			</td>
																			<td align='center' valign='middle' class='tdrow' style="width:18%;">
																				<select name="cmb_assigned_eic[]" id="cmb_assigned_eic" class="tboxsmclass" >
																					<option value="">--- Select ---</option>
																					<option value=""><?php //echo $objBind->BindStaff($SelEICVal); ?></option>
																				</select>
																			</td>
																			<?php //if(($SelEICVal != 0) && ($EICNameVal != "")){ ?>
																				<td valign='middle' class='tdrow' align = 'center'>&nbsp;<i class="fa fa-check-circle-o" style="font-size:20px; color:#046929;"></i></td>
																			<?php// }else{ ?>
																				<td valign='middle' class='tdrow' align = 'center'>&nbsp;<i class="fa fa-times-circle" style="font-size:20px; color:#EA253C;"></i></td>
																			<?php	//} ?>
																		</tr>	-->
																		<?php $SNO++; 
																						} 
																					} 
																				} 
																			?>
																   </tbody>
																</table>
															</div>
														</div>
														<div align="center">
															<!-- <input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" /> -->
															<!-- <a data-url="Administrator" class="btn btn-info" name="btn_back" id="btn_back">Back</a> -->
															<input type="submit" class="btn btn-info" name="btn_back" id="btn_back" value=" Back ">
															<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Save ">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div align="center">&nbsp;</div>
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
<script>
$(document).ready(function(){ 
	$('.dataTable').DataTable({"paging":false,"ordering": false});
	$("#exportToExcel").click(function(e){ 
		var table = $('body').find('.table2excel');
		if(table.length){ 
			$(table).table2excel({
				exclude: ".noExl",
				name: "Excel Document Name",
				filename: "SingleLineAbstract-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
				fileext: ".xls",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true
				//preserveColors: preserveColors
			});
		}
	});
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
						window.location.replace('StaffSectionAssignMod.php');
					}
				}]
			});
		}
};
</script>