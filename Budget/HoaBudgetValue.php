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
	$HoaMasIDstr    = $_POST['txt_master_id']; 
	$ObjHeadIdArr 	= $_POST['txt_obj_head'];
	$ObjHeadNamearr = $_POST['txt_obj_headname'];
	$OldHoaArr 		= $_POST['txt_old_hoa'];
	$OldShrArr 		= $_POST['txt_new_shcod'];
	$NewHoaArr 		= $_POST['txt_new_hoa'];
	$NewShrArr 		= $_POST['txt_old_shcod'];
	$BePropAmtArr   = $_POST['txt_be_prop_amt'];
	$BeApprAmtArr   = $_POST['txt_be_appr_amt'];
	$RePropAmtArr   = $_POST['txt_re_prop_amt'];
	$ReApprAmtArr   = $_POST['txt_re_appr_amt'];
	$Exe = 0;
	//print_r($ObjHeadIdArr);exit;
	foreach($ObjHeadIdArr as $Key => $Value){ 
		$Hoamaster   = $HoaMasIDstr[$Key];  
		$ObjHeadName = $ObjHeadNamearr[$Key];
		$ObjHeadID   = $ObjHeadIdArr[$Key];
		$OldHoaNo 	=  $OldHoaArr[$Key];
		$Oldshrc 	=  $OldShrArr[$Key];
		$NewHoaNo 	=  $NewHoaArr[$Key];
		$NewShrc 	=  $NewShrArr[$Key];
		$BePropAmt  =  $BePropAmtArr[$Key];
		$BeApprAmt  =  $BeApprAmtArr[$Key];
		$RePropAmt  =  $RePropAmtArr[$Key];
		$ReApprAmt  =  $ReApprAmtArr[$Key];

		//if($Hoamaster!=null){
		if(($Hoamaster != NULL)&&($Hoamaster != '')){
			//$DetailsDetailsQuery = "DELETE FROM hoa_master WHERE hoamast_id ='$Hoamaster'";
			//$IDeleDetailsSql 	= mysqli_query($dbConn, $DetailsDetailsQuery);
			
			//$InsertDetailsQuery = "insert into hoa_master set pin_id = '$PinId', fin_year = '$FinYear', obj_head_id = '$ObjHeadID' ,obj_head='$ObjHeadName', short_code_old_hoa='$Oldshrc',short_code_new_hoa='$NewShrc', old_hoa_no  = '$OldHoaNo', new_hoa_no = '$NewHoaNo' ,be_prop_amt ='$BePropAmt', be_appr_amt ='$BeApprAmt',re_prop_amt ='$RePropAmt',re_appr_amt='$ReApprAmt', hoa_type ='C', modified_on=NOW(), modified_by='$UserId', active = '1'";
			$InsertDetailsQuery = "update hoa_master set pin_id = '$PinId', fin_year = '$FinYear', obj_head_id = '$ObjHeadID' ,obj_head='$ObjHeadName', old_hoa_no  = '$OldHoaNo', new_hoa_no = '$NewHoaNo' ,be_prop_amt ='$BePropAmt', be_appr_amt ='$BeApprAmt',re_prop_amt ='$RePropAmt',re_appr_amt='$ReApprAmt', hoa_type ='C', modified_on=NOW(), modified_by='$UserId', active = '1' where hoamast_id = '$Hoamaster'";
			//echo $InsertDetailsQuery; exit;
			//echo "IF = ".$InsertDetailsQuery; echo "<br/>"; exit;
			$InsertDetailsSql 	= mysqli_query($dbConn, $InsertDetailsQuery);
			if($InsertDetailsSql == true){ 
				$Exe++;
			}
		}else{
			//$InsertDetailsQuery = "insert into hoa_master set pin_id = '$PinId', fin_year = '$FinYear', obj_head_id = '$ObjHeadID' ,obj_head='$ObjHeadName', short_code_old_hoa='$Oldshrc',short_code_new_hoa='$NewShrc',old_hoa_no  = '$OldHoaNo', new_hoa_no = '$NewHoaNo' ,be_prop_amt ='$BePropAmt', be_appr_amt ='$BeApprAmt',re_prop_amt ='$RePropAmt',re_appr_amt='$ReApprAmt', hoa_type ='C', modified_on=NOW(), modified_by='$UserId', active = '1'";
			$InsertDetailsQuery = "insert into hoa_master set pin_id = '$PinId', fin_year = '$FinYear', obj_head_id = '$ObjHeadID' ,obj_head='$ObjHeadName',  old_hoa_no  = '$OldHoaNo', new_hoa_no = '$NewHoaNo' ,be_prop_amt ='$BePropAmt', be_appr_amt ='$BeApprAmt',re_prop_amt ='$RePropAmt',re_appr_amt='$ReApprAmt', hoa_type ='C', modified_on=NOW(), modified_by='$UserId', active = '1'";
			//echo $InsertDetailsQuery; exit;
			//echo "ELSE = ".$InsertDetailsQuery; echo "<br/>"; exit;
			$InsertDetailsSql 	= mysqli_query($dbConn, $InsertDetailsQuery);
			if($InsertDetailsSql == true){ 
				$Exe++;
			}
			
		}
	}
//exit;
	// $SelectExHoaQuery = "SELECT * FROM hoa_master WHERE active = 1";
	// $SelectExHoaSql   = mysqli_query($dbConn,$SelectExHoaQuery);
	// if($SelectExHoaSql == true){
	// 	if(mysqli_num_rows($SelectExHoaSql)>0){
	// 		while($ExHoaList = mysqli_fetch_object($SelectExHoaSql)){
	// 			$ExPinId 	 = $ExHoaList->pin_id;
	// 			$ExFinYr 	 = $ExHoaList->fin_year;
	// 			$ExObjHeadId = $ExHoaList->obj_head_id;
	// 			$ExOldHoaNo  = $ExHoaList->old_hoa_no;
	// 			$ExNewHoaNo	 = $ExHoaList->new_hoa_no;
	// 			$ExActive 	 = $ExHoaList->active;
	// 			$InsertExQuery  = "insert into hoa_detail set pin_id = '$ExPinId', fin_year = '$ExFinYr', obj_head_id = '$ExObjHeadId', 
	// 							  old_hoa_no  = '$ExOldHoaNo', new_hoa_no = '$ExNewHoaNo', active = '$ExActive'";
	// 			$InsertExSql 	= mysqli_query($dbConn, $InsertExQuery);
	// 		}
	// 	}
	// }
	// if(count($ObjHeadIdArr) > 0){
	// 	foreach($ObjHeadIdArr as $Key => $Value){
	// 		$ObjHeadName=$ObjHeadNamearr[$Key];
	// 		$OldHoaNo 	= $OldHoaArr[$Key];
	// 		$NewHoaNo 	= $NewHoaArr[$Key];
	// 		$BePropAmt = $BePropAmtArr[$Key];
	// 		$BeApprAmt = $BeApprAmtArr[$Key];
	// 		$RePropAmt = $RePropAmtArr[$Key];
	// 		$ReApprAmt = $ReApprAmtArr[$Key];
	// 		$InsertDetailsQuery = "insert into hoa_master set pin_id = '$PinId', fin_year = '$FinYear', obj_head_id = '$Value' ,obj_head='$ObjHeadName',old_hoa_no  = '$OldHoaNo', new_hoa_no = '$NewHoaNo' ,be_prop_amt ='$BePropAmt', be_appr_amt ='$BeApprAmt',re_prop_amt ='$RePropAmt',re_appr_amt='$ReApprAmt',modified_on=NOW(),modified_by='$UserId', active = '1'";
	// 		//echo $InsertDetailsQuery; exit;
	// 		$InsertDetailsSql 	= mysqli_query($dbConn, $InsertDetailsQuery);
	// 		if($InsertDetailsSql == true){ 
	// 			$Exe++;
	// 		}
			
	// 	}
	// }
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
// $HoaArr = array();
// $SelectObjHeadQuery = "SELECT * FROM hoa_master WHERE active = 1 ORDER BY hoamast_id ASC";
// $SelectObjHeadSql   = mysqli_query($dbConn,$SelectObjHeadQuery);
// if($SelectObjHeadSql == true){
// 	if(mysqli_num_rows($SelectObjHeadSql)>0){
// 		while($OHList = mysqli_fetch_object($SelectObjHeadSql)){
// 			$ObjHeadId 	 = $OHList->obj_head_id;
// 			$ObjHeadName = $OHList->obj_head;
// 			$OldHoaNo 	 = $OHList->old_hoa_no;
// 			$NewHoaNo 	 = $OHList->new_hoa_no;
// 			$NewBePropAmt= $OHList->be_prop_amt;
// 			$NewBeApprAmt= $OHList->be_appr_amt;
// 			$NewRePropAmt=$OHList->re_prop_amt; 
// 			$NewReApprAmt=$OHList->re_appr_amt; 

// 			$HoaArr[$ObjHeadId][0] = $ObjHeadId;
// 			$HoaArr[$ObjHeadId][1] = $ObjHeadName;
// 			$HoaArr[$ObjHeadId][2] = $OldHoaNo;
// 			$HoaArr[$ObjHeadId][3] = $NewHoaNo;
// 			$HoaArr[$ObjHeadId][4] = $NewBePropAmt;
// 			$HoaArr[$ObjHeadId][5] = $NewBeApprAmt;
// 			$HoaArr[$ObjHeadId][6] = $NewRePropAmt;
// 			$HoaArr[$ObjHeadId][7] = $NewReApprAmt;

// 		}
// 	}
// }
// $IsHoaExist = count($HoaArr);
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function goBack(){
		url = "Administrator.php";
		window.location.replace(url);
	}
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
								<input type="hidden" name="max_group" id="max_group" value="1" />
								<div class="box-container box-container-lg">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Head of Account No. BE & RE</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="div1 pd-lr-1">
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
														<div class="div1 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<input type="button" name="btnView" id="btnView" class="btn btn-sm btn-info" value=" VIEW ">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="box-container box-container-lg">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;HOA  Details <span class="ralignbox"></span></div>
													 <div class=" hidden Details">	
														 <div class="card-body padding-1 display ftable mgtb-8">
															 <div class="row clearrow display ftable mgtb-8" id="HOADetails"></div>
														 </div>																		
														 <div class="buttonsection hidden Details" style="display:inline-table">
															<input type="button" onClick="goBack()" class="btn btn-info" name="back" id="back" value="Back">
														 </div>
														  <div class="buttonsection hidden Details" style="display:inline-table">
															<input type="submit" class="btn btn-info" data-type="submit" value=" Save " name="btn_save" id="btn_save"   />
														  </div>
													  </div>
													  <div class="row smclearrow"></div>
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
						$(location).attr("href","HoaBudgetValue.php");
					}
				}]
			});
		}
	};
	$("#cmb_unit").chosen();
	$("#cmb_fy").chosen();
	$("#cmb_month").chosen();
	$("#cmb_rupees").chosen();
	$(document).ready(function(){ 
		$('body').on("click","#btnView", function(event){
			$(".Details").removeClass("hidden");
			$("#HOADetails").html(''); 
			var BudMonth 	 	= $("#cmb_month").val();
			var Pinid 	 	    = $("#txt_pin_no").val();
			var TitleFinYear 	= $("#cmb_fy option:selected").text();
			var TitleMonth 		= $("#cmb_month option:selected").text();  
			//$("#table-stmt").html('');
			var sp = TitleFinYear.split('-')[0]; // split("-",TitleFinYear);
			var sp1 = TitleFinYear.split('-')[1]; // split("-",TitleFinYear);
			$.ajax({ 
					type: 'POST', 
					url: 'GetHOADetails.php',  
					data: {TitleFinYear:TitleFinYear, Pinid,Pinid}, 
					dataType: 'json',
					success: function (data) {  
						var Result1 = data['row1']; 
						var Result2 = data['row2']; 
						var EmptyStr = ""; 
						var	BankStr  = "<table class='display ftable mgtb-8' align='center' >";
							BankStr += "<tr>";
							BankStr += "<th> Object Heads</th>";
							BankStr += "<th style='text-align:center'>Old HOA No.</th>";
							// BankStr += "<th style='text-align:center'>Short Code</th>";
							BankStr += "<th style='text-align:center'>New HOA No.</th>";
							// BankStr += "<th style='text-align:center'>Short Code</th>";
							BankStr += "<th style='text-align:center'>BE Proposed <br/>(&#8377 in Lakhs)</th>";
							BankStr += "<th style='text-align:center'>BE Approved <br/>(&#8377 in Lakhs)</th>";
							BankStr += "<th style='text-align:center'>RE Proposed <br/>(&#8377 in Lakhs)</th>";
							BankStr += "<th style='text-align:center'>RE Approved <br/>(&#8377 in Lakhs)</th>";
							BankStr += "</tr>";
							if(Result1 != null){
								$.each(Result1, function(index, element){
									var propesamt = element.be_prop_amt;
									if(propesamt == null){
										propesamt = 0;
									}
									var Approamt = element.be_appr_amt;
									if(Approamt == null){
										Approamt = 0;
									}
									var reproamt = element.re_prop_amt;
									if(reproamt == null){
										reproamt = 0;
									}
									var reappramt = element.re_appr_amt;
									if(reappramt == null){
										reappramt = 0;
									}
									if(element.old_hoa_no == null){
										element.old_hoa_no = '';
									}
									if(element.new_hoa_no == null){
										element.new_hoa_no = '';
									}
									BankStr += "<tr>";
									BankStr +="<td align='center'><input type='hidden' align='centre' class='tboxsmclass' name='txt_master_id[]' id='txt_master_id[]' value='"+element.hoamast_id +"' ><input type='hidden' align='centre' class='tboxsmclass' name='txt_obj_head[]' id='txt_obj_head[]' value='"+element.obj_head_id+"' ><input type='text' align='centre' class='tboxsmclass' name='txt_obj_headname[]' id='txt_obj_headname[]' value='"+element.ojectname+"' ></td>";
									BankStr +="<td align='left'><input type='text'class='tboxsmclass EmAmt' name='txt_old_hoa[]' id='txt_old_hoa[]'  value='"+element.old_hoa_no+"' ></td>";
									// BankStr +="<td align='left'><input type='text'class='tboxclass EmAmt' name='txt_old_shcod[]' id='txt_old_shcod[]'  value='"+element.short_code_old_hoa+"' ></td>";
									BankStr +="<td align='left'><input type='text'  class='tboxsmclass' name='txt_new_hoa[]' id='txt_new_hoa[]'  value='"+element.new_hoa_no+"' ></td>";
									// BankStr +="<td align='left'><input type='text'class='tboxclass EmAmt' name='txt_new_shcod[]' id='txt_new_shcod[]'  value='"+element.short_code_new_hoa+"' ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass'  onKeyPress='return isNumberWithTwoDecimal(event,this);' name='txt_be_prop_amt[]' id='txt_be_prop_amt[]'  value='"+propesamt+"' ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' onKeyPress='return isNumberWithTwoDecimal(event,this);'  name='txt_be_appr_amt[]' id='txt_be_appr_amt[]'  value='"+Approamt+"' ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' onKeyPress='return isNumberWithTwoDecimal(event,this);'  name='txt_re_prop_amt[]' id='txt_re_prop_amt[]'  value='"+reproamt+"' ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass'onKeyPress='return isNumberWithTwoDecimal(event,this);'   name='txt_re_appr_amt[]' id='txt_re_appr_amt[]'  value='"+reappramt+"' ></td></tr>";
								});
				       		}
						
						
						
				     	else{	
							$.each(Result2, function(index, element){
								if(element.old_hoa_no == null){
									element.old_hoa_no = '';
								}
								if(element.new_hoa_no == null){
									element.new_hoa_no = '';
								}
						       	BankStr += "<tr>";
						 		BankStr +="<td align='center'><input type='hidden' align='centre' class='tboxsmclass' name='txt_master_id[]' id='txt_master_id[]' value='' ><input type='hidden' align='centre' class='tboxsmclass' name='txt_obj_head[]' id='txt_obj_head[]' value='"+element.ohid+"' ><input type='text' align='centre' class='tboxsmclass' name='txt_obj_headname[]' id='txt_obj_headname[]' value='"+element.obj_head+"' ></td>";
								BankStr +="<td align='left'><input type='text'class='tboxsmclass EmAmt' readonly name='txt_old_hoa[]' id='txt_old_hoa[]'  value='"+element.new_hoa_no+"' ></td>";
								BankStr +="<td align='left'><input type='text'  class='tboxsmclass' name='txt_new_hoa[]' id='txt_new_hoa[]'  value='' ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' onKeyPress='return isNumberWithTwoDecimal(event,this);'  name='txt_be_prop_amt[]' id='txt_be_prop_amt[]'  value='' ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' onKeyPress='return isNumberWithTwoDecimal(event,this);'  name='txt_be_appr_amt[]' id='txt_be_appr_amt[]'  value='' ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' onKeyPress='return isNumberWithTwoDecimal(event,this);'  name='txt_re_prop_amt[]' id='txt_re_prop_amt[]'  value='' ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' onKeyPress='return isNumberWithTwoDecimal(event,this);'  name='txt_re_appr_amt[]' id='txt_re_appr_amt[]'  value='' ></td></tr>";
							});
							
							}
							BankStr += "</table>";
						$("#HOADetails").html(BankStr);
				
				}
				});
			});
		}); 

		
		

		
		// $("#PsAmt").html('');
		// $("#TotActTaken").html('');
		// $("#TotCommAmt").html('');
		// $("#ActExpUpDt").html('');
		// $("#FinPro").html('');
		// $("#PhyPro").html('');
		// var Month = $("#cmb_month option:selected").text();
		// if($("#cmb_rupees").val() == "C"){
		// 	var RupeesStr = "(&#x20b9; in Crores)";
		// }else{
		// 	var RupeesStr = "(&#x20b9; in Lakhs)";
		// }
		// //var TitleStr  = "Financial and Physical Progress Statement for the Finanial Year - "+TitleFinYear+" up to Month "+TitleMonth;
		// //var TableStr  = '<table class="example display rtable mgtb-8" style="width:100%"><thead><tr><th class="tabtitle" colspan="2" style="text-align:left;">'+TitleStr+'</th></tr><tr><th>Description</th><th class="sum">Amount'+RupeesStr+'</th></tr></thead><tbody></tbody>';
		// 	//TableStr += '<tfoot><tr><th></th><th></th></tr></tfoot></table>';
		// //$("#table-stmt").html(TableStr);
		// FinancialPhysicalProgress();
			
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
