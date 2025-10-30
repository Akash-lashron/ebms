<?php
if(isset($_POST["btn_add"]) == " ADD "){ 
	$Sheetid 		= $_POST['txt_sheetid'];
	$TxtRbn 		= $_POST['txt_rbn'];
	$TxtCCNo 		= $_POST['txt_ccno'];
	$TxtSection 	= $_POST['txt_section'];
	$DocDesc 		= $_POST['txt_doc_desc'];
	if($_FILES['txt_upload_file']['name'] != ""){ 
		//echo $_FILES['txt_upload_file']['name'][$DocDescKey]."<br/>";
		$NewDocName 		= str_ireplace( array( '-', ' ' ), '_', $DocDesc);
		$target_dir 		= "BillSupportingDoc/";
		$temp 				= explode(".", $_FILES["txt_upload_file"]["name"]);
		$newfilename 		= $TxtSection."_".$TxtCCNo."_".$TxtRbn."_".$NewDocName.'.'.end($temp);
		$target_file 		= $target_dir . $newfilename;
		if(file_exists($target_file)){ unlink($target_file); }
		//$currentfilename 	= basename($_FILES["staff_photo"]["name"]);
		$checkupload 		= 1;
		$imageFileType 		= pathinfo($target_file, PATHINFO_EXTENSION);
		if($checkupload == 1){ 
			if(move_uploaded_file($_FILES["txt_upload_file"]["tmp_name"], $target_file)){
				$checkupload = 2;
				$AttchedFileUpload = $newfilename;
				$InsertQuery = "insert into send_acc_supp_doc set sheetid = '$Sheetid', rbn = '$TxtRbn', doc_desc = '$DocDesc', doc_name = '$AttchedFileUpload', active = 1, createddate = NOW(), staffid = '$staffid'";
				$InsertSql 	 = mysql_query($InsertQuery);
				if($InsertSql == true){
					$msg = "Document Uploaded Successfully";
					$success = 1;
				}else{
					$msg = "Error : Sorry, Unable to upload files. Try again !";
					$success = 0;
				}
			}
		} 
		//echo $InsertQuery;
		//exit;
	}
}
$UploadCnt = 0; $Rbn = "";
if(isset($_SESSION['UpSheetid'])){ 
	$Sheetid = $_SESSION['UpSheetid'];
	$SelectSheetQuery 	= "select * from sheet where sheet_id = '$Sheetid'";
	$SelectSheetSql 	= mysql_query($SelectSheetQuery);
	if($SelectSheetSql == true){
		if(mysql_num_rows($SelectSheetSql)>0){
			$SheetList 		= mysql_fetch_object($SelectSheetSql);
			$WorkShortName 	= $SheetList->short_name;
			$WorkName 		= $SheetList->work_name;
			$WorkOrderNo 	= $SheetList->work_order_no;
			$CCNo 			= $SheetList->computer_code_no;
			$SectionType 	= $SheetList->section_type;
		}
	}
	$SelectRbnQuery = "select distinct rbn from measurementbook_temp where sheetid = '$Sheetid'";
	$SelectRbnSql 	= mysql_query($SelectRbnQuery);
	if($SelectRbnSql == true){
		if(mysql_num_rows($SelectRbnSql)>0){
			$RbnList 	= mysql_fetch_object($SelectRbnSql);
			$Rbn 		= $RbnList->rbn;
		}
	}
	$Section = "CIVIL";
	$SelectSecQuery = "select section_name from section_name where section_type = '$SectionType' and active = 1";
	$SelectSecSql 	= mysql_query($SelectSecQuery);
	if($SelectSecSql == true){
		if(mysql_num_rows($SelectSecSql)>0){
			$SecList 	= mysql_fetch_object($SelectSecSql);
			$Section 	= $SecList->section_name;
		}
	}
	
	$SelectUploadQuery 	= "select * from send_acc_supp_doc where sheetid = '$Sheetid' and rbn = '$Rbn' and active = 1";
	$SelectUploadSql 	= mysql_query($SelectUploadQuery);
	if($SelectUploadSql == true){
		if(mysql_num_rows($SelectUploadSql)>0){
			$UploadCnt = 1;
		}
	}
}
?>
<?php require_once "Header.html"; ?>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->

         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Supporting Documents (RAB/Final Bill) - Send Accounts</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="AccountsSupportingDocument.php" enctype="multipart/form-data">
                            <div class="container">
								<div class="row">
									<div class="div12 grid-empty"></div>
									<div class="div12 grid-empty"></div>
									<div class="div2" align="center">&nbsp;</div>
									<div class="div8" align="center">
										<div class="innerdiv2">
											<div class="row divhead head-b" align="center">Supporting Documents Upload Form</div>
											<div class="row innerdiv group-div" align="center">
											
												<!--<div class="div12 grid-empty"></div>
												<div class="div12 lboxlabel" align="left">
													<span class="rspan-pink">Work Order No. : 
													<?php if(isset($WorkOrderNo)){ echo $WorkOrderNo; } ?> 
													&emsp;<span class="htext1">CC No. : <?php if(isset($CCNo)){ echo $CCNo; } ?></span>
													&emsp;<span class="htext2">RAB : <?php if(isset($Rbn)){ echo $Rbn; } ?></span>
													</span>
												</div>-->
												<div class="div12 divempty"></div>
												<div class="div12" align="left";>
													<span class="rspan-pink">
													<?php if(isset($WorkName)){ echo $WorkName; } ?>
													&emsp;<span class="htext1">CC No. : <?php if(isset($CCNo)){ echo $CCNo; } ?></span>
													&emsp;<span class="htext2">RAB : <?php if(isset($Rbn)){ if($Rbn != ""){ echo $Rbn; }else{ echo "--"; } } ?></span>
													</span>
												</div>
												<div class="div12 divempty"></div>
												<div class="div12 lboxlabel" align="left">
													<table class="table1 itemtable MaddTable" width="100%">
														<thead>
															<tr>
																<th style="text-align:left">File Description</th>
																<th style="text-align:left">Upload File</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody>
														<?php if($Rbn != ""){ ?>
															<tr id="CloneRow">
																<td width="50%"><input type="text" name="txt_doc_desc" id="txt_doc_desc" class="divtbox DocDesc"></td>
																<td><input type="file" name="txt_upload_file" id="txt_upload_file" class="divtbox UploadFile"></td>
																<td valign="middle" align="center"><input type="submit" name="btn_add" id="btn_add" class="madd-btn" value=" ADD "></td>
															</tr>
															<?php if($UploadCnt == 1){ while($UpList = mysql_fetch_object($SelectUploadSql)){ ?>
															<tr>
																<td class="lboxlabel" width="50%" align="left"><?php echo $UpList->doc_desc; ?></td>
																<td class="lboxlabel"><?php echo $UpList->doc_name; ?></td>
																<td valign="middle" align="center"><input type="button" name="btn_delete" id="btn_delete" class="mdel-btn delete" data-id="<?php echo $UpList->sasdid; ?>" value=" DELETE "></td>
															</tr>
															<?php } } ?>
														<?php }else{ ?>
															<tr>
																<td colspan="3" align="center">No RAB found to upload documents</td>
															</tr>
														<?php } ?>
														</tbody>
													</table>
												</div>
												<div class="div12 divempty"></div>
											</div>
										</div>
									</div>
									<div class="div2" align="center">&nbsp;</div>
								</div>
     						</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection" id="view_btn_section">
									<input type="hidden" name="txt_ccno" id="txt_ccno" value="<?php if(isset($CCNo)){ echo $CCNo; } ?>">
									<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php if(isset($Rbn)){ echo $Rbn; } ?>">
									<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php if(isset($Sheetid)){ echo $Sheetid; } ?>">
									<input type="hidden" name="txt_section" id="txt_section" value="<?php if(isset($Section)){ echo $Section; } ?>">
									<input type="button" class="backbutton" value=" Back " name="back" id="back" onClick="goBack()"/>
								</div>
							</div>
                    	</form>
                 	</blockquote>
               	</div>
            </div>
        </div>
         <!--==============================footer=================================-->
    <?php include "footer/footer.html"; ?>
	<script>
		$(function() {
			$('.UploadFile').on('change', function(event){ 
				var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'txt'];
				if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
					BootstrapDialog.alert("Only formats are allowed : "+fileExtension.join(', '));
					$(this).val('');
					event.preventDefault();
					event.returnValue = false;
				}else if(this.files[0].size > 1048576){  //5242880
					$(this).val('');
					BootstrapDialog.alert("Upload file size should be less than 1MB!"); 
					event.preventDefault();
					event.returnValue = false;
				}
				
			});
			$("body").on("click", "#btn_add", function(event){
				if($("#txt_doc_desc").val() == ""){
					BootstrapDialog.alert("Please Enter File Description");
					event.preventDefault();
					event.returnValue = false;
				}else if($("#txt_upload_file").val() == ""){
					BootstrapDialog.alert("Please Choose Upload File");
					event.preventDefault();
					event.returnValue = false;
				}
			});
			$(".MaddTable").on("click", ".delete", function(){
				var id = $(this).attr("data-id");
				$.ajax({ 
					type: 'POST', 
					url: 'ajax/DeleteUploadFile.php', 
					data: { id: id }, 
					success: function (data) { 
						if((data != null)&&(data == 1)){
							BootstrapDialog.show({
								title: 'Information',
								message: 'Uploaded Document Deleted Successfully',
								buttons: [{
									label: ' OK ',
									action: function(dialog) {
										location.reload();
										dialog.close();
									}
								}]
							});
						}else{
							BootstrapDialog.alert("Error : Sorry, Unable to delete. Try again later.");
						}
					}
				})
			});
			/*$(".MaddTable").on("click", ".delete", function(){
				$(this).closest("tr").remove();
			});*/
			/*$("body").on("click", "#btn_save", function(event){
				var DescError = 0; var FileError = 0;
				var tr = $(".MaddTable").find("tr:gt(1)");
				$(tr).each(function() {
					var Desc = $(this).find(".DocDesc").val();
					if(Desc == ""){
						DescError++;
					}
				});
				$(tr).each(function() {
					var UFile = $(this).find(".UploadFile").val();
					if(UFile == ""){
						FileError++;
					}
				});
				if(DescError > 0){
					BootstrapDialog.alert("File description filed should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(FileError > 0){
					BootstrapDialog.alert("Upload file field should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}
			});*/
		});

		$("#txt_workshortname").chosen();
		var msg = "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		var titletext = "";
		document.querySelector('#top').onload = function(){
			if(msg != ""){
				if(success == 1){
					swal("", msg, "success");
				}else{
					swal(msg, "", "");
				}
							
			}
		};
		function goBack(){
			url = "AccountsSupportingDocumentGen.php";
			window.location.replace(url);
		}
		if(window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
		}
	</script>
	<style>
		.divtbox{
			height:28px;
			border:1px solid #D2D2D3;
		}
		.div6, .div12{
			box-sizing: border-box;
			padding:0px 2px;
		}
		.divtarea{
			border:1px solid #939699;
		}
		.bootstrap-dialog-close-button .close{
			display:none;
		}
	</style>
    </body>
</html>

