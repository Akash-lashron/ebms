<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
//include "common.php";
checkUser();
$PageName = $PTPart1.$PTIcon.'Head Of Account-Entry';
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$UserId  = $_SESSION['userid'];


if(isset($_POST['btn_save']) == ' Save '){ 
	$PinNo 			= $_POST['txt_pin_no'];
	$PinId 			= $_POST['hid_pin_id'];
	$FinYear	 	= $_POST['cmb_fy'];
	$ObjHeadIdArr 	= $_POST['txt_obj_head'];
	$ObjHeadNamearr    =$_POST['txt_obj_headname'];
	
	
	$OldHoaArr 		= $_POST['txt_old_hoa'];
	$NewHoaArr 		= $_POST['txt_new_hoa'];
	$BePropAmtArr   =$_POST['txt_be_prop_amt'];
	$BeApprAmtArr   =$_POST['txt_be_appr_amt'];
	$RePropAmtArr   =$_POST['txt_re_prop_amt'];
	$ReApprAmtArr   =$_POST['txt_re_appr_amt'];
	$Exe = 0;
	
	$SelectExHoaQuery = "SELECT * FROM hoa_master WHERE active = 1";
	$SelectExHoaSql   = mysqli_query($dbConn,$SelectExHoaQuery);
	if($SelectExHoaSql == true){
		if(mysqli_num_rows($SelectExHoaSql)>0){
			while($ExHoaList = mysqli_fetch_object($SelectExHoaSql)){
				$ExPinId 	 = $ExHoaList->pin_id;
				$ExFinYr 	 = $ExHoaList->fin_year;
				$ExObjHeadId = $ExHoaList->obj_head_id;
				$ExOldHoaNo  = $ExHoaList->old_hoa_no;
				$ExNewHoaNo	 = $ExHoaList->new_hoa_no;
				$ExActive 	 = $ExHoaList->active;
				$InsertExQuery  = "insert into hoa_detail set pin_id = '$ExPinId', fin_year = '$ExFinYr', obj_head_id = '$ExObjHeadId', 
								  old_hoa_no  = '$ExOldHoaNo', new_hoa_no = '$ExNewHoaNo', active = '$ExActive'";
				$InsertExSql 	= mysqli_query($dbConn, $InsertExQuery);
			}
		}
	}
	if(count($ObjHeadIdArr) > 0){
		foreach($ObjHeadIdArr as $Key => $Value){
			$ObjHeadName=$ObjHeadNamearr[$Key];
			$OldHoaNo 	= $OldHoaArr[$Key];
			$NewHoaNo 	= $NewHoaArr[$Key];
			$BePropAmt = $BePropAmtArr[$Key];
			$BeApprAmt = $BeApprAmtArr[$Key];
			$RePropAmt = $RePropAmtArr[$Key];
			$ReApprAmt = $ReApprAmtArr[$Key];
			$InsertDetailsQuery = "insert into hoa_master set pin_id = '$PinId', fin_year = '$FinYear', obj_head_id = '$Value' ,obj_head='$ObjHeadName',old_hoa_no  = '$OldHoaNo', new_hoa_no = '$NewHoaNo' ,be_prop_amt ='$BePropAmt', be_appr_amt ='$BeApprAmt',re_prop_amt ='$RePropAmt',re_appr_amt='$ReApprAmt',modified_on=NOW(),modified_by='$UserId', active = '1'";
			//echo $InsertDetailsQuery; exit;
			$InsertDetailsSql 	= mysqli_query($dbConn, $InsertDetailsQuery);
			if($InsertDetailsSql == true){ 
				$Exe++;
			}
			
		}
	}
	if($Exe > 0){
		$msg = "Head of Account data saved successfully";
	}else{
		$msg = "Head of Account data not saved. Please try again.";
	}
}
$PinNo = ""; $PinId = 0; $RowCount = 0;
$SelectQuery1 = "SELECT * FROM object_head WHERE active = 1";
$SelectSql1   = mysqli_query($dbConn,$SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1) > 0){
		$RowCount = 1;
	}
}
$SelectPinQuery = "SELECT * FROM pin WHERE active = 1";
$SelectPinSql   = mysqli_query($dbConn,$SelectPinQuery);
if($SelectPinSql == true){
	if(mysqli_num_rows($SelectPinSql)>0){
		$PinList = mysqli_fetch_object($SelectPinSql);
		$PinNo 	 = $PinList->pin_no;
		$PinId 	 = $PinList->pin_id;
	}
}
$HoaArr = array();
$SelectObjHeadQuery = "SELECT * FROM hoa_master WHERE active = 1 ORDER BY hoamast_id ASC";
$SelectObjHeadSql   = mysqli_query($dbConn,$SelectObjHeadQuery);
if($SelectObjHeadSql == true){
	if(mysqli_num_rows($SelectObjHeadSql)>0){
		while($OHList = mysqli_fetch_object($SelectObjHeadSql)){
			$ObjHeadId 	 = $OHList->obj_head_id;
			$ObjHeadName = $OHList->obj_head;
			$OldHoaNo 	 = $OHList->old_hoa_no;
			$NewHoaNo 	 = $OHList->new_hoa_no;
			$NewBePropAmt= $OHList->be_prop_amt;
			$NewBeApprAmt= $OHList->be_appr_amt;
			$NewRePropAmt=$OHList->re_prop_amt; 
			$NewReApprAmt=$OHList->re_appr_amt; 

			$HoaArr[$ObjHeadId][0] = $ObjHeadId;
			$HoaArr[$ObjHeadId][1] = $ObjHeadName;
			$HoaArr[$ObjHeadId][2] = $OldHoaNo;
			$HoaArr[$ObjHeadId][3] = $NewHoaNo;
			$HoaArr[$ObjHeadId][4] = $NewBePropAmt;
			$HoaArr[$ObjHeadId][5] = $NewBeApprAmt;
			$HoaArr[$ObjHeadId][6] = $NewRePropAmt;
			$HoaArr[$ObjHeadId][7] = $NewReApprAmt;

		}
	}
}
$IsHoaExist = count($HoaArr);
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
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
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
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Head of Account No. BE & RE <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="card-body padding-1 ChartCard" id="CourseChart">
																	<div class="pt-2">
																		<div class="div2 pd-lr-1">
																			<div class="lboxlabel-sm">PIN</div>
																			<div>
																				<input type="text" readonly="" name="txt_pin_no" id="txt_pin_no" class="card-label-tbox-lg" value="<?php if(isset($PinNo)){ echo $PinNo; } ?>">
																				<input type="hidden" name="hid_pin_id" id="hid_pin_id" class="card-label-tbox-lg" value="<?php if(isset($PinId)){ if($PinId != 0){ echo $PinNo; } } ?>">
																			</div>
																		</div>
																		<div class="div2 pd-lr-1">
																			<div class="lboxlabel-sm">Financial Year</div>
																			<div>
																				<select class="group selectlgbox" name="cmb_fy" id="cmb_fy">
																					<?php echo $objBind->BindFinancialYear(''); ?>
																				</select>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="row clearrow"></div>
																<table id="example" class="display ftable mgtb-8" style="width:100%">
																	<thead>
																		<tr>
																			<th>Object Heads</th>
																			<th style="text-align:center">Old HOA No.</th>
																			<th style="text-align:center">New HOA No.</th>
																			<th style="text-align:center">BE Proposed <br/>(&#8377 in Lakhs)</th>
																			<th style="text-align:center">BE Approved <br/>(&#8377 in Lakhs)</th>
																			<th style="text-align:center">RE Proposed <br/>(&#8377 in Lakhs)</th>
																			<th style="text-align:center">RE Approved <br/>(&#8377 in Lakhs)</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php if($RowCount == 1){ while($ObjHList = mysqli_fetch_object($SelectSql1)){ 
																			if($IsHoaExist == 0){
																				$DisableOldHoa = 'disabled="disabled"';
																			}else{
																				$DisableOldHoa = '';
																			}
																			if(isset($HoaArr[$ObjHList->ohid])){
																				$ObjHeadId  = $HoaArr[$ObjHList->ohid][0];
																				$ObjHeadName= $HoaArr[$ObjHList->ohid][1];
																				$OldHoa      =$HoaArr[$ObjHList->ohid][2];
																				$NewHoa      =$HoaArr[$ObjHList->ohid][3];
																				$NewBePropAmt=$HoaArr[$ObjHList->ohid][4];
																				$NewBeApprAmt=$HoaArr[$ObjHList->ohid][5];
																				$NewRePropAmt=$HoaArr[$ObjHList->ohid][6];
																				$NewReApprAmt=$HoaArr[$ObjHList->ohid][7];
																				//print $ExistNewHoa; exit;
																			}else{
																				$ObjHeadId = '';
																				$ObjHeadName = '';	
																				$OldHoa = '';
																				$NewHoa = '';
																				$NewBePropAmt='';
																				$NewBeApprAmt='';
																				$NewRePropAmt='';
																				$NewReApprAmt='';
																			}
																			
																		?>
																			<tr>
																				<td nowrap="nowrap"><?php echo $ObjHList->obj_head; ?><input type="hidden" name="txt_obj_head[]" id="txt_obj_head" class="card-label-tbox-lg" value="<?php echo $ObjHList->ohid; echo $ObjHeadId; ?>"></td>
																				<input type="hidden" name="txt_obj_headname[]" id="txt_obj_headname" class="card-label-tbox-lg" value="<?php echo  $ObjHList->obj_head; echo $ObjHeadName ;?>" ></td> 
																				<td align="center"><input type="text" name="txt_old_hoa[]" id="txt_old_hoa" class="tboxclass card-label-tbox-lg"         value="<?php echo $OldHoa;   ?>" <?php echo $DisableOldHoa; ?> autocomplete="off"></td>
																				<td align="center"><input type="text" name="txt_new_hoa[]" id="txt_new_hoa" class="tboxclass card-label-tbox-lg"         value="<?php echo $NewHoa; ?>" <?php echo $DisableOldHoa; ?> autocomplete="off"></td>
																				<td align="center"><input type="text" name="txt_be_prop_amt[]" id="txt_be_prop_amt" class="tboxclass card-label-tbox-lg" value="<?php echo $NewBePropAmt; ?>"<?php echo $DisableOldHoa; ?> autocomplete="off"></td>
																				<td align="center"><input type="text" name="txt_be_appr_amt[]" id="txt_be_appr_amt" class="tboxclass card-label-tbox-lg" value="<?php echo $NewBeApprAmt; ?>"<?php echo $DisableOldHoa; ?> autocomplete="off"></td>
																				<td align="center"><input type="text" name="txt_re_prop_amt[]" id="txt_re_prop_amt" class="tboxclass card-label-tbox-lg" value="<?php echo $NewRePropAmt; ?>"<?php echo $DisableOldHoa; ?> autocomplete="off"></td>
																				<td align="center"><input type="text" name="txt_re_appr_amt[]" id="txt_re_appr_amt" class="tboxclass card-label-tbox-lg" value="<?php echo $NewReApprAmt; ?>"<?php echo $DisableOldHoa; ?> autocomplete="off"></td>
																			</tr>
																		<?php } } ?>
																	</tbody>
																</table>
																<div class="buttonsection" style="display:inline-table">
																	<input type="button" onClick="goBack()" class="btn btn-info" name="back" id="back" value="Back">
																</div>
																<div class="buttonsection" style="display:inline-table">
																	<input type="submit" class="btn btn-info" data-type="submit" value=" Save " name="btn_save" id="btn_save"   />
																	

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
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script src="js/CommonJSLibrary.js"></script>
<script type="text/javascript" language="javascript">
$(function(){
	var msg = "<?php echo $msg; ?>";
	/*$('#example').DataTable( {
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
		lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
    } );*/
	/*var table = $('#example').DataTable( {
		scrollY:        "300px",
		scrollX:        true,
		scrollCollapse: true,
		paging:         false
	} );
	new $.fn.dataTable.FixedColumns( table );*/
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			//BootstrapDialog.alert(msg);
			BootstrapDialog.show({
				title: 'Information',
				closable: false,
				message: msg,
				buttons: [{
					label: '&nbsp; OK &nbsp;',
					action: function(dialog) {
						$(location).attr("href","HoaNew.php");
					}
				}]
			});
		}
	};
	$("#cmb_unit").chosen();
	$("#cmb_fy").chosen();
	$("#cmb_month").chosen();
	$("#cmb_rupees").chosen();
	$('body').on("click","#btnView", function(event){
		var BudMonth 	 	= $("#cmb_month").val();
		var TitleFinYear 	= $("#cmb_fy option:selected").text();
		var TitleMonth 		= $("#cmb_month option:selected").text();
		//$("#table-stmt").html('');
		$("#PsAmt").html('');
		$("#TotActTaken").html('');
		$("#TotCommAmt").html('');
		$("#ActExpUpDt").html('');
		$("#FinPro").html('');
		$("#PhyPro").html('');
		var Month = $("#cmb_month option:selected").text();
		if($("#cmb_rupees").val() == "C"){
			var RupeesStr = "(&#x20b9; in Crores)";
		}else{
			var RupeesStr = "(&#x20b9; in Lakhs)";
		}
		//var TitleStr  = "Financial and Physical Progress Statement for the Finanial Year - "+TitleFinYear+" up to Month "+TitleMonth;
		//var TableStr  = '<table class="example display rtable mgtb-8" style="width:100%"><thead><tr><th class="tabtitle" colspan="2" style="text-align:left;">'+TitleStr+'</th></tr><tr><th>Description</th><th class="sum">Amount'+RupeesStr+'</th></tr></thead><tbody></tbody>';
			//TableStr += '<tfoot><tr><th></th><th></th></tr></tfoot></table>';
		//$("#table-stmt").html(TableStr);
		FinancialPhysicalProgress();
			
	});
});
if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
</script>
<style>
	.tboxclass{
		width:99%;
	}
	.chosen-container-single .chosen-single{
		padding: 3px 4px;
	}
	div.dt-buttons{
		padding-left: 5px;
	}
	table.dataTable thead > tr > th.sorting::before{
		bottom: 0%;
		content: "";
	}
	table.dataTable thead > tr > th.sorting::after{
		top: 0%;
		content: "";
	}
	th.tabtitle{
		text-align:left !important;
	}
</style>
