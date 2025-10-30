<?php
$UploadCnt = 0;
$RBNArr = array();
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
	
	$SelectRbnQuery = "select distinct rbn from send_acc_supp_doc where sheetid = '$Sheetid' and rbn != '$Rbn' and active = 1";
	$SelectRbnSql 	= mysql_query($SelectRbnQuery);
	if($SelectRbnSql == true){
		if(mysql_num_rows($SelectRbnSql)>0){
			while($RBNList = mysql_fetch_object($SelectRbnSql)){
				array_push($RBNArr,$RBNList->rbn);
			}
		}
	}
}
$UploadExist = 0;
?>
<?php require_once "Header.html"; ?>

    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->

         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Previous RAB Supporting Documents - View</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="">
                            <div class="container">
								<div class="row">
									<div class="div12 grid-empty"></div>
									<div class="div12 grid-empty"></div>
									<div class="div2" align="center">&nbsp;</div>
									<div class="div8" align="center">
										<div class="innerdiv2">
											<!--<div class="row divhead head-b" align="center">List of Uploaded Documents in Previous RAB</div>-->
											<div class="row innerdiv1 group-div" align="center">
												<div class="div12">
													<table class="table1 itemtable MaddTable" width="100%">
														<thead>
															<tr>
																<th style="text-align:left">RAB</th>
																<th style="text-align:left">File Description</th>
																<th style="text-align:left">Uploaded File</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody>
													<?php if(count($RBNArr) > 0){ 
														$Color1 = "#F0F1F3"; $Color2 = "#FFFFFF"; $y = 1;
														foreach($RBNArr as $key => $Value){
															$x = 0;
															if($y == 1){ $Color = $Color1; }
															if($y == 2){ $Color = $Color2; $y = 0; }
															$y++;
															$SelectUploadQuery 	= "select * from send_acc_supp_doc where sheetid = '$Sheetid' and rbn = '$Value'";
															$SelectUploadSql 	= mysql_query($SelectUploadQuery);
															if($SelectUploadSql == true){
																$RowSpan = mysql_num_rows($SelectUploadSql);
																if(mysql_num_rows($SelectUploadSql)>0){
																	while($UpList = mysql_fetch_object($SelectUploadSql)){ 
																		$UploadExist = 1;
																		if($x == 0){
																		?>
																	<tr>
																		<td class="cboxlabel" style="background:<?php echo $Color;?>" align="center" valign="middle" rowspan="<?php echo $RowSpan; ?>"><?php echo $Value; ?></td>
																		<td class="lboxlabel" style="background:<?php echo $Color;?>" width="40%" align="left"><?php echo $UpList->doc_desc; ?></td>
																		<td class="lboxlabel" style="background:<?php echo $Color;?>"><?php echo $UpList->doc_name; ?></td>
																		<td valign="middle" style="background:<?php echo $Color;?>" align="center">
																			<a href="AccountsSupportingDocumentDownload.php?filename=<?php echo $UpList->doc_name; ?>" class="mdload-btn delete">DOWNLOAD</a>
																		</td>
																	</tr>
																	<?php }else{ ?>
																	<tr>
																		<td class="lboxlabel" style="background:<?php echo $Color;?>" width="40%" align="left"><?php echo $UpList->doc_desc; ?></td>
																		<td class="lboxlabel" style="background:<?php echo $Color;?>"><?php echo $UpList->doc_name; ?></td>
																		<td valign="middle" style="background:<?php echo $Color;?>" align="center">
																			<a href="AccountsSupportingDocumentDownload.php?filename=<?php echo $UpList->doc_name; ?>" class="mdload-btn delete">DOWNLOAD</a>
																		</td>
																	</tr>
																	<?php } ?>
													<?php $x++; } } } } } ?>
													<?php if($UploadExist == 0){ ?>
																	<tr>
																		<td colspan="4" align="center">No Uploaded Documents Found</td>
																	</tr>
													<?php } ?>
														</tbody>
													</table>
												</div>
												<div class="div12 grid-empty"></div>
											</div>
										</div>
									</div>
									<div class="div2" align="center">&nbsp;</div>
								</div>
     						</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection" id="view_btn_section">
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
		function goBack(){
			url = "AccountsSupportingDocumentGen.php";
			window.location.replace(url);
		}
	</script>
    </body>
</html>

