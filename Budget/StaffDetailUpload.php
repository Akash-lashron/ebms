<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Staff Detail Upload';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
function dt_format($ddmmyyyy){
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	return $yy .'-'. $mm .'-'.$dd;
}
function dt_display($ddmmyyyy){
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}
$PriceBidLocation = 'uploads/';//"PriceBid/";
//echo $PriceBidLocation;exit;
$RowCount = 0;

if(isset($_POST["upload"]) == "Upload File"){
	$SheetName 		= $_POST['txt_sheetname'];
	$StartRow 		= $_POST['txt_start_row'];
	$EndRow 		= $_POST['txt_end_row'];
	$UploadFile 	= $_FILES['file']['name'];

	// $ContMasterArr1 	= array();	
	// //$ContSelQuery 		= "SELECT cont_temp_id FROM cont_master_temp WHERE active ='1'";
	// $ContSelQuery 		= "SELECT contid,name_contractor FROM contractor WHERE active ='1'";
	// $ContSelQuerySql 	= mysqli_query($dbConn,$ContSelQuery);
	// if($ContSelQuerySql == true){
	// 	while($ContList 	= mysqli_fetch_object($ContSelQuerySql)){
	// 		$ContTempId = $ContList->contid;
	// 		$ContMasterArr1[] = $ContTempId;
	// 	}
	// }
	// if(count($ContMasterArr1) != 0){
	// 	$DeleteMasterQuery 		="TRUNCATE TABLE contractor";
	// 	$DeleteMasterQuerySql 	= mysqli_query($dbConn,$DeleteMasterQuery);
	// 	$DeleteDetailQuery 		="TRUNCATE TABLE contractor_bank_detail_temp";
	// 	$DeleteDetailQuerySql 	= mysqli_query($dbConn,$DeleteDetailQuery);
	// }else{
	// 	//echo 2;exit;
	// }
	if($_FILES['file']['name'] != ""){
      $target_dir 		= $PriceBidLocation;	//$_SERVER['DOCUMENT_ROOT'].'/wcms/mbook/IGCwcMSCIVIL/PriceBid/';//"PriceBid/";
		//echo $target_dir; exit;
		$UploadDate 		= date('dmYHis');
        $target_file 		= $target_dir.$TrId.basename($_FILES["file"]["name"]);
        $currentfilename 	= $TrId.basename($_FILES["file"]["name"]);
        $checkupload 		= 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if(file_exists($target_file)){
			unlink($target_file);
        }
        if($_FILES["file"]["size"] > 500000) {
            $msg = $msg." Sorry, your file is too large." . "<br/>";
            $checkupload = 0;
        }
        if(strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") {
            $msg = $msg." Sorry, only xls files are allowed." . "<br/>";
            $checkupload = 0;
        }
        if($checkupload == 0) {
            $msg = $msg." Sorry, your file was not uploaded." . "<br/>";
        }else{
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            	$checkupload = 1;
            }else{
                $checkupload = 0;
                $msg = $msg."Sorry, there was an error uploading your file." . "<br/>";
            }
        }
    } 
	//echo $target_file;exit;
	$work_order_cost = 0;
	$first = 0; $prev_item =''; $subdivisionlast_id = 0; $sheetCnt = 0;  $Exectemp = 0; $InsertTemp = 0;
	$slno = '';
}

if(isset($_POST["confirm"]) == " CONFIRM "){

	// $ContMasterArr 	= array();	
	// $ContSelQuery 		= "SELECT contid,name_contractor FROM contractor WHERE active ='1'";
	// $ContSelQuerySql 	= mysqli_query($dbConn,$ContSelQuery);
	// if($ContSelQuerySql == true){
	// 	while($ContList 	= mysqli_fetch_object($ContSelQuerySql)){
	// 		$ContTempId = $ContList->contid;
	// 		$ContName 	= $ContList->name_contractor;
	// 		$ContMasterArr[$ContName] = $ContTempId;
	// 	}
  	// }
	//print_r($ContMasterArr);exit;
	$StaffIcArr 		    = $_POST['txt_ic_no'];
	$StaffNoArr 	        = $_POST['txt_emp_no'];
	$StaffNameArr 	        = $_POST['txt_staff_name'];
	$StaffSecArr   	        = $_POST['txt_staff_sec'];
	$StaffDesignationArr	= $_POST['txt_designation'];
	$StaffMailArr 	        = $_POST['txt_mail'];
	$StaffIntercomArr 	    = $_POST['txt_intercom'];
    $StaffMobArr 	        = $_POST['txt_staff_mob'];
    $StaffRoleArr 	        = $_POST['txt_staff_role'];
	$Execute = 0;
	foreach($StaffNameArr as $ArrKey => $ArrValue){
		$StaffICNO 		        = $StaffIcArr[$ArrKey];
		$StaffEmpNo 		    = $StaffNoArr[$ArrKey];
		$StaffName              = $StaffNameArr[$ArrKey];
		$StaffSection           = $StaffSecArr[$ArrKey];
		$StaffDesignation 		= $StaffDesignationArr[$ArrKey];
		$StaffEmailId 		    = $StaffMailArr[$ArrKey];
		$StaffIntercomNo 		= $StaffIntercomArr[$ArrKey];
		$StaffMobileNo 		    = $StaffMobArr[$ArrKey];
		$StaffRole 		        = $StaffRoleArr[$ArrKey];
		//$StaffEmailStr 			= $StaffEmailId."@igcar.gov.in";
		//echo $StaffEmailId;exit;
		/*
		$SelectStaffQuery = "SELECT * FROM staff WHERE staffcode= '$StaffICNO' OR staff_emp_no = '$StaffEmpNo'";
		$SelectStaffQuerySql = mysqli_query($dbConn,$SelectStaffQuery);
		if(($SelectStaffQuerySql == true)&&(mysqli_num_rows($SelectStaffQuerySql) > 0)){
			$InsertQuery1 =  "UPDATE staff SET staffcode= ' $StaffICNO', staff_emp_no = '$StaffEmpNo', staffname = '$StaffName', section_name = '$StaffSection', 
			designation_name = '$StaffDesignation', email = '$StaffEmailId', intercom = '$StaffIntercomNo', active='1'";
		}else{
			$InsertQuery1 =  "INSERT INTO staff SET staffcode= ' $StaffICNO', staff_emp_no = '$StaffEmpNo', staffname = '$StaffName', section_name = '$StaffSection', 
			designation_name = '$StaffDesignation', email = '$StaffEmailId', intercom = '$StaffIntercomNo', active='1'";
		}
		*/
		$InsertQuery1 =  "INSERT INTO staff SET staffcode= ' $StaffICNO', staff_emp_no = '$StaffEmpNo', staffname = '$StaffName', section_name = '$StaffSection', 
			designation_name = '$StaffDesignation', email = '$StaffEmailId', intercom = '$StaffIntercomNo', temp_flag = 'OFF', active='1'";
		//mobile = '$StaffMobileNo', active='1', srole_name = '$StaffRole'";
		$InsertSql1	= mysqli_query($dbConn,$InsertQuery1);
		if($InsertSql1 == true){
			$Execute++;
		}
	}
	//print_r($ItemDescArr);
	//exit;
	if($Execute > 0){
		$msg = "Staff Details Saved Successfully";
		$success = 1;
	}else{
		$msg = "Error : Staff Details Not Saved.. Please Try Again.";
		$success = 0;
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function goBack()
	{
	   	url = "StaffDetailGenerate.php";
		window.location.replace(url);
	}
	
</script>
<style>
	.DispTable{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
	}
	.DispTable th, .DispTable td{
		border:1px solid #BCBEBF;
		border-collapse:collapse;
		padding:2px 3px;
	}
	.DispTable th{
		background-color:#035a85;
		color:#fff;
		vertical-align:middle;
		text-align:center;
	}
	.DispTable td{
		color:#062C73;
	}
	.HideDesc{
		max-width : 768px; 
	  	white-space : nowrap;
	  	overflow : hidden;
	  	text-overflow: ellipsis;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="" method="post" enctype="multipart/form-data" name="form">
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1" style="overflow:auto">
						  		<div class="row">
									<div class="box-container box-container-lg" align="center">
										<div class="div12">
											<div class="card cabox">
												<div class="face-static">
													<div class="card-header inkblue-card" align="center">Staff Details- Upload</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																	<table class="DispTable" width="100%">
																		<thead>
																			 <tr>
																				<th>Staff ICNO</th>
																				<th>Employee No</th>
																				<th>Staff Name</th>
																				<th>Section</th>
																				<th>Designation</th>
																				<th>Email ID</th>
																				<th>Intercom No</th>
																				<th>Mobile No</th>
																				<th>Staff Role</th>
																		      </tr>
                                                                           </thead>
																		<tbody>
																		<?php 
																		//echo $currentfilename;exit;
																		
																		if($checkupload == 1) {	//echo $PriceBidLocation.$currentfilename;exit;
																			$Spreadsheet = new SpreadsheetReader($PriceBidLocation.$currentfilename);
																			$Sheets = $Spreadsheet -> Sheets();
																			foreach ($Sheets as $Index => $Name){ // Loop to get all sheets in a file.
																				$Spreadsheet -> ChangeSheet($Index);
																				$ExcelSheetName = $Name;
																				if($SheetName == $ExcelSheetName)
																				{
																					if(strtolower($imageFileType) == "xls"){
																						$StartRow = $StartRow - 1;
																					}
																					if(strtolower($imageFileType) == "xlsx"){
																						$StartRow = $StartRow - 1;
																					}
																					foreach ($Spreadsheet as $Key => $Row) { // loop used to get each row of the sheet
																						if(($Key >= $StartRow)&&($Key <= $EndRow)){
																							if(trim($Row[1]) != ''){
																								$StaffICNO              = trim($Row[0]);
																								$StaffEmpNo             = trim($Row[1]);
																								$StaffName              = trim($Row[2]);
																								$StaffSection           = trim($Row[3]);
																								$StaffDesignation       = trim($Row[4]);
																								$StaffEmailId           = trim($Row[5]);
																								$StaffIntercomNo        = trim($Row[6]);
																								$StaffMobileNo          = trim($Row[7]);
																								$StaffRole              = trim($Row[8]);																							
																							?>

                                                                                          <tr>
																							<td align="right"><?php echo $StaffICNO; ?><input type="hidden" name="txt_ic_no[]" value="<?php echo $StaffICNO; ?>"></td>
																								<td align="left"><?php echo $StaffEmpNo; ?><input type="hidden" name="txt_emp_no[]" value="<?php echo $StaffEmpNo; ?>"></td>
																								<td align="left"><?php echo $StaffName; ?><input type="hidden" name="txt_staff_name[]" value="<?php echo $StaffName; ?>"></td>
																								<td align="left"><?php echo $StaffSection; ?><input type="hidden" name="txt_staff_sec[]" value="<?php echo $StaffSection; ?>"></td>
																								<td align="left"><?php echo $StaffDesignation; ?><input type="hidden" name="txt_designation[]" value="<?php echo $StaffDesignation; ?>"></td>
																								<!-- <td align="left"><?php //echo $StaffEmailId."@igcar.gov.in"; ?><input type="hidden" name="txt_mail[]" value="<?php //echo $StaffEmailId."@igcar.gov.in"; ?>"></td> -->
																								<td align="left"><?php echo $StaffEmailId; ?><input type="hidden" name="txt_mail[]" value="<?php echo $StaffEmailId; ?>"></td>
																								<td align="left"><?php echo $StaffIntercomNo; ?><input type="hidden" name="txt_intercom[]" value="<?php echo $StaffIntercomNo; ?>"></td>
																								<td align="left"><?php echo $StaffMobileNo; ?><input type="hidden" name="txt_staff_mob[]" value="<?php echo $StaffMobileNo; ?>"></td>
																								<td align="left"><?php echo $StaffRole; ?><input type="hidden" name="txt_staff_role[]" value="<?php echo $StaffRole; ?>"></td>
																								
																							</tr>
																							<?php
																								
																							}
																						}
																					} 
																				}
																			}
																		?>
																			
																		<?php } ?>
																			</tbody>
																	</table>
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
							<div class="row">
								<div class="div12" align="center">
									<input type="button" class="btn btn-info" name="back" id="back" value=" BACK " onClick="goBack();"/>
									<input type="submit" class="btn btn-info" name="confirm" id="confirm" value=" CONFIRM "/>
								</div>
							</div>  
							<div class="row">&nbsp;</div>                         
						</blockquote>
					</div>

            </div>
        </div>
	</form>
         <!--==============================footer=================================-->
	<?php include "footer/footer.html"; ?>
	<script>
		var msg 	= "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						dialog.close();
						window.location.replace('StaffDetailGenerate.php');
					}
				}]
			});
		}
		//var KillEvent = 0;
		
	</script>
    </body>
</html>

