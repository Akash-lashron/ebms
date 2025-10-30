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
	//print_r($TotStaffListArr);exit;

	$CcnoArr		   	 = $_POST['txt_hid_ccno'];
	$ExistStaffNameArr = $_POST['txt_hid_eicname'];
	$SelStaffIdArr 	 = $_POST['cmb_assigned_eic'];
	foreach($CcnoArr as $Key => $Value){
		$SelStaffId 	 = $SelStaffIdArr[$Key];
		$ExistStaffName = $ExistStaffNameArr[$Key];
		$SelStaffName 	 = $TotStaffListArr[$SelStaffId];
		if($SelStaffId != ""){
			if(($Value != 0)&&($Value != NULL)&&($Value != "")){
				$UpdateQuery = "UPDATE sheet SET eic='$SelStaffId', eic_name='$SelStaffName' WHERE computer_code_no = '$Value'";
				$UpdateQuerySql = mysqli_query($dbConn,$UpdateQuery);
				$UpdateworkQuery = "UPDATE works SET eic='$SelStaffId', eic_name='$SelStaffName' WHERE ccno = '$Value'";
				$UpdateworkQuerySql = mysqli_query($dbConn,$UpdateworkQuery);
			}
		}		
	}
	if($UpdateQuerySql == true){
		$msg = "EIC Details Saved Successfully ";
		$success = 1;
	}else{
		$msg = "Error...EIC Details Not Saved..!!!";
	}
}

$LiveWorksResult = "SELECT * FROM sheet WHERE active = 1 ORDER BY sheet_id DESC";
$LiveWorksResultSql = mysqli_query($dbConn,$LiveWorksResult);

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
																			<th valign="middle"> CCNo. </th>
																			<th valign="middle"> Work Order No. </th>
																			<th valign="middle"> Name of Work </th>
																			<th valign="middle"> Work Order Date </th>
																			<th valign="middle"> EIC Name </th>
																			<th valign="middle"> Select / Assign EIC </th>
																			<th valign="middle"> EIC Assigned Status </th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr class='labeldisplay'>
																			<?php
																				$SNO = 1; 
																				if($LiveWorksResultSql == true){
																					if(mysqli_num_rows($LiveWorksResultSql)>0){
																						while($WrkList = mysqli_fetch_object($LiveWorksResultSql)){
																							$SheetIdVal	 	= $WrkList->sheet_id;
																							$CcnoVal 	 	= $WrkList->computer_code_no;
																							$WorkNameVal 	= $WrkList->work_name;
																							$WONumbVal	 	= $WrkList->work_order_no;
																							$WODateVal 		= $WrkList->work_order_date;
																							$WODateValDisp = dt_display($WODateVal);
																							$EICNameVal 	= $WrkList->eic_name;
																							$SelEICVal 		= $WrkList->eic;
																							//$splitCreateDateTimeStamp = explode(" ",$CreateDateTime);
																							//$CreateDate = $splitCreateDateTimeStamp[0];
																							//echo $CreateDate;
																			?>
																			<td class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'>
																				<?php echo $CcnoVal; ?>
																				<input type="hidden" name="txt_hid_ccno[]" id="txt_hid_ccno" value="<?php echo $CcnoVal; ?>">
																			</td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $WONumbVal; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $WorkNameVal; ?></td>
																			<td class='tdrow' align='right' valign='middle'><?php echo $WODateValDisp; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'>
																				<?php echo $EICNameVal; ?>
																				<input type="hidden" name="txt_hid_eicname[]" id="txt_hid_eicname" value="<?php echo $EICNameVal; ?>">
																			</td>
																			<td align='center' valign='middle' class='tdrow' style="width:18%;">
																				<!-- <select name="" id="" class="tboxsmclass"> -->
																				<select name="cmb_assigned_eic[]" id="cmb_assigned_eic" class="tboxsmclass" >
																					<option value="">--- Select ---</option>
																					<option value=""><?php echo $objBind->BindStaff($SelEICVal); ?></option>
																				</select>
																			</td>
																			<?php if(($SelEICVal != 0) && ($EICNameVal != "")){ ?>
																				<td valign='middle' class='tdrow' align = 'center'>&nbsp;<i class="fa fa-check-circle-o" style="font-size:20px; color:#046929;"></i></td>
																			<?php }else{ ?>
																				<td valign='middle' class='tdrow' align = 'center'>&nbsp;<i class="fa fa-times-circle" style="font-size:20px; color:#EA253C;"></i></td>
																			<?php	} ?>
																		</tr>
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
						window.location.replace('EICAssignStaff.php');
					}
				}]
			});
		}
};
</script>