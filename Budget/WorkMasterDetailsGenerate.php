<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Work Master Details Upload';
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

if ($_POST["submit"]) {
	echo 1;
    if($_FILES['file']['name'] != "") {
        $target_dir 		= "uploads/";
		$UploadDate 		= date('dmYHis');
        $target_file 		= $target_dir .$UploadDate. basename($_FILES["file"]["name"]);
        $currentfilename 	= $UploadDate.basename($_FILES["file"]["name"]);
        $checkupload 		= 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if(file_exists($target_file)) {
            $msg = $msg . " Sorry, file already exists." . "<br/>";
            $checkupload = 0;
        }
		// Check file size
        if($_FILES["file"]["size"] > 500000) {
            $msg = $msg . " Sorry, your file is too large." . "<br/>";
            $checkupload = 0;
        }
		// Allow certain file formats
        if(strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") {
            $msg = $msg . " Sorry, only xls files are allowed." . "<br/>";
            $checkupload = 0;
        }
		// Check if $checkupload is set to 0 by an error
        if($checkupload == 0) {
            $msg = $msg . " Sorry, your file was not uploaded." . "<br/>";
		// if everything is ok, try to upload file
        }else{
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
			// echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
            	$checkupload = 1;
            }else{
                $checkupload =0;
                $msg = $msg .  "Sorry, there was an error uploading your file." . "<br/>";
            }
        }
    } 
			//echo $checkupload;exit;
	$work_order_cost = 0;
    $first = 0; $prev_item =''; $subdivisionlast_id = 0; $sheetCnt = 0;  $Execute = 0; $InsertTemp = 0;
    $slno = '';
    if($checkupload == 1) {
    	$Spreadsheet = new SpreadsheetReader("uploads/" . $currentfilename);
		$Sheets = $Spreadsheet -> Sheets();
		//echo $xlsheetname;exit;
        foreach ($Sheets as $Index => $Name){ // Loop to get all sheets in a file.
			$Spreadsheet -> ChangeSheet($Index);
			$sheetname = $Name;
			//echo $sheetname;exit;
			if($xlsheetname == $sheetname)
			{
    
				$sheetCnt = 1;
				if(strtolower($imageFileType) == "xls")
				{
					$startRow = 1;
				} 
				if(strtolower($imageFileType) == "xlsx")
				{
					$startRow = 0;
				}
				//if (count($data -> ChangeSheet($Index)) > 0) { // checking sheet not empty
				foreach ($Spreadsheet as $Key => $Row) { // loop used to get each row of the sheet
					if ($Key>$startRow){
						if(trim($Row[1]) != ''){
							$ContId 			    = trim($Row[0]);
							$ContName	            = trim($Row[1]);
							$ContAccNo 		        = trim($Row[1]);
							$ContBankName 		    = trim($Row[2]);
							$ContBranchName 		= trim($Row[3]);
							$ContModeofPayment 	    = trim($Row[4]);
							$ContAmount 	        = trim($Row[5]);
							$ContIFSC 		        = trim($Row[6]);
						
						
							if ($item != '' && $description != '') 
							{
								$InsertQuery1 	= "insert into contractor_bank_detail set   contid = '$ContId',bank_acc_hold_name = ' $ContName',
								bank_acc_no = '$ContAccNo', bank_name = '$ContBankName', branch_address = '$ContBranchName', ifsc_code = '$ContIFSC', active = '1',
								pay_mode ='$ContModeofPayment', amount ='$ContAmount'";
								$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
								$BidderMastId	= mysqli_insert_id($dbConn);
								if($InsertSql1 == true){
									$Execute++;
								}
							}
						}//end of if (count($Spreadsheet)>4) condition
					} //for  // loop used to get each row of the sheet----------------------
				}
				// } // checking sheet not empty	
			} // Loop to get all sheets in a file.
		} //checkupload
		
		if($Execute > 0){
			$msg = "Work Master Details Saved Successfully";
			$success = 1;
		}else{
			$msg = "Error : Work Master Details Not Saved. Please Try Again.";
			$success = 0;
		}
	}
} //submit 

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
		url = "MyView.php";
		window.location.replace(url);
	}
	function OpenInNewTabWinBrowser(url) 
	{
	  	var win = window.open(url, '_blank');
	  	win.focus();
	}
	function View_page()
	{
		url = "AgreementDetailsView.php";
		window.location.replace(url);
	}

	// $(function () {
	// 	$("#sheet_name_info").click(function(event){
	// 		BootstrapDialog.show({
	// 			title: 'Sample Sheet Format',
	// 			closable: false,
	// 			message: $('<img src="images/sheet_name.png">'),
	// 			buttons: [{
	// 				label: ' Close ',
	// 				cssClass: 'btn-default',
	// 				action: function(dialog) {
	// 					dialog.close();
	// 				}
	// 			}]
	// 		});
	// 	});
	// });
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="WorkMasterDetailsUpload.php" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <?php include "MainMenu.php"; ?>
                <div class="container_12">
                    <div class="grid_12">
						<div align="right" class="users-icon-part">&nbsp;</div>
                        <blockquote class="bq1" style="overflow:auto">
							<!--<div align="right">
								<font style="font-size:12px; font-weight:bold; color:#0066FF">
									Upload File Format :&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="" onClick="OpenInNewTabWinBrowser('AgreementUpload_File_Sample.php');"><u>Agreement Sheet</u>&nbsp;&nbsp;&nbsp;&nbsp;</a>
								</font>
							</div>-->
							<div class="container">
								<div class="row ">
									<div class="row clearrow"></div>
									<div class="row clearrow"></div>
									<div class="div3">&nbsp;</div>
									<div class="div6">
										<div class="row divhead head-b" align="center">Work Master Detail - Upload</div>
										<div class="row innerdiv">
											
											<div class="row clearrow"></div>
											<div class="row">
											<div class="div1">&nbsp;</div>
												<div class="div3 lboxlabel">
													Sheet Name
												</div>
												<div class="div4">
													<input type="text" name='txt_sheetname' id='txt_sheetname' class="tboxsmclass">
												</div>
												<div class="div4" align="left">
													&nbsp;&nbsp;<i class="fa fa-info-circle" aria-hidden="true" style="padding-top:0px; color:#0078F0; cursor:pointer; font-size:25px" id="sheet_name_info" title="Click here to View Sample"></i>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
											<div class="div1">&nbsp;</div>
												<div class="div3 lboxlabel">
													Starting Row
												</div>
												<div class="div4">
													<input type="text" name='txt_start_row' id='txt_start_row' class="tboxsmclass">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div1">&nbsp;</div>
												<div class="div3 lboxlabel">
													Ending Row
												</div>
												<div class="div4">
													<input type="text" name='txt_end_row' id='txt_end_row' class="tboxsmclass">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div1">&nbsp;</div>
												<div class="div3 lboxlabel">
													Work Type
												</div>
												<div class="div4">
													<select name="cmb_worktype" id="cmb_worktype" class="tboxsmclass">
														<option value="">----- Select -----</option>
														<option value="C">Completed</option>
														<option value="R">Running</option>
													</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div1">&nbsp;</div>
												<div class="div3 lboxlabel">
													Upload File
												</div>
												<div class="div8">
													<input type="file" class="text" name="file" id="file" size="44" style="height:23px;" />
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4">&nbsp;</div>
												<div class="div8 lboxlabel">
													File should be in the formats of : .xls , .xlsx
												</div>
											</div>
											<div class="row clearrow"></div>
											<!-- <div class="row">
												<div class="div12" align="center">
													<input type="submit" class="btn btn-info" data-type="submit" name="upload" id="upload" value="Upload File" />
													<a data-url="PriceBidViewGenerate" class="btn btn-info" name="view" id="view">View</a>
												</div>
											</div> -->											
										</div>
									</div>
									<div class="div3">&nbsp;</div>
								</div>
								 <div class="row">
									<div class="div12" align="center">
										<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
										<input type="submit" class="btn btn-info" data-type="submit" name="upload" id="upload" value="Upload File" />
										<input type="submit" class="btn btn-info" data-type="submit" name="updatedata" id="updatedata" value="Update Data" />
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
    </body>
</html>
<script>
	$("#cmb_tr_no").chosen();
	$("#cmb_bidder").chosen();
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	$('body').on("change","#cmb_tr_no", function(e){ 
		var Id = $(this).val();
		$("#txt_work_name").val('');
		$.ajax({ 
			type: 'POST', 
			url: 'FindEstTsTrName.php', 
			data: { Id: Id, Page: 'TR'}, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					$("#txt_work_name").val(data.work_name);
				}
			}
		});
	});
	$(document).ready(function(){ 
		$("body").on("click","#upload", function(event){
			var ShortName 	= $("#cmb_shortname").val();
			var WorkOrderNo = $("#txt_workorder").val();
			var BidderName 	= $("#cmb_bidder").val();
			var SheetName 	= $("#txt_sheetname").val();
			var StartRow 	= $("#txt_start_row").val();
			var EndRow 		= $("#txt_end_row").val();
			if(ShortName.trim() == ""){
				BootstrapDialog.alert("Please select work short name");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkOrderNo.trim() == ""){
				BootstrapDialog.alert("Tender no. should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(BidderName == ""){
				BootstrapDialog.alert("Bidder name should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(SheetName.trim() == ""){
				BootstrapDialog.alert("Sheet name should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(StartRow.trim() == ""){
				BootstrapDialog.alert("Starting row should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(EndRow.trim() == ""){
				BootstrapDialog.alert("Ending row should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}
		});
		$("body").on("change","#cmb_shortname", function(event){
			$("#txt_workorder").val('')
			var TenderNo = $("#cmb_shortname option:selected").attr('data-tr');
			if(TenderNo != ""){
				$("#txt_workorder").val(TenderNo);
			}
		});
		
	});
</script>


