<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
checkUser();
$PageName = $PTPart1.$PTIcon.'Revised Budget Expenditure Proposal & Approval';
$msg = '';
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

$SelectQuery1 = "SELECT * FROM hoa_re WHERE status='A'";
$SelectSql1   = mysqli_query($dbConn, $SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1) > 0){
		$RowCount = 1;
	}
}

if((isset($_POST['btn_prop']) == ' Proposed ')||(isset($_POST['btn_appr']) == ' Approved ')){
	$PinNo 				= $_POST['txt_pin_no'];
	$PinId 				= $_POST['hid_pin_id'];
	$FinYear	 		= $_POST['cmb_fin_year'];
	$HeadofAccArr 		= $_POST['txt_Hoa'];
	$BudValueArr 		= $_POST['txt_bud_val'];
	$HoaIdArr 			= $_POST['hid_Hoa_id'];
	$HoaBeIdArr 		= $_POST['hid_Hoa_be_id'];
	if(isset($_POST['btn_prop']) == ' Proposed '){
		$PropFlag 		= 'P';
		$PropDate 		= date('d-m-Y');
	}
	if(isset($_POST['btn_appr']) == ' Approved '){
		$PropFlag 		= 'A';
		$ApprovedDate 	= date('d-m-Y');
	}
	foreach($HeadofAccArr as $key => $Value){
		$HeadofAcc 	= $HeadofAccArr[$key];
		$BudValue 	= $BudValueArr[$key];
		$HoaId 		= $HoaIdArr[$key];
		$HoaBeId	= $HoaBeIdArr[$key];
		if(isset($_POST['btn_prop']) == ' Proposed '){
			$UpdateClause = "re_prop_amt = '$BudValue', re_prop_on ='$PropDate',";
		}
		if(isset($_POST['btn_appr']) == ' Approved '){
			$UpdateClause = "re_appr_amt ='$BudValue',re_appr_on ='$ApprovedDate',";
		}
		$UpdateDetailsQuery = "UPDATE hoa_re SET fin_year = '$FinYear', hoa_no = '$HeadofAcc', pin_id = '$PinId', 
		pin_no = '$PinNo', ".$UpdateClause." status = '$PropFlag', userid = '$UserId', active = '1' WHERE hreid='$HoaBeId'";

		$UpdateDetailsSql 	= mysqli_query($dbConn, $UpdateDetailsQuery);
	}
	if($UpdateDetailsSql == true){
		if($PropFlag == 'P'){
			$msg = " RE Proposed successfully..!! ";
		}else{
			$msg = "RE Approved successfully.";
		}
	}else{
		if($PropFlag == 'P'){
			$msg = "Error : RE Not Proposed. Please try again..!!";
		}else{
			$msg = "Error : RE Not Approved. Please try again..!!";
		}
	}
}


if(isset($_POST['btn_save']) == ' Save Proposed Amount '){
	$PinNo 				= $_POST['txt_pin_no'];
	$PinId 				= $_POST['hid_pin_id'];
	$FinYear	 		= $_POST['cmb_fin_year'];
	$HeadofAccArr 		= $_POST['txt_Hoa'];
	$BudValueArr 		= $_POST['txt_bud_val'];
	$HoaIdArr 			= $_POST['hid_Hoa_id'];

	foreach($HeadofAccArr as $key => $Value){
		$HeadofAcc 	= $HeadofAccArr[$key];
		$BudValue 	= $BudValueArr[$key];
		$HoaId 		= $HoaIdArr[$key];
		
		$InsertDetailsQuery = "insert into hoa_re set hoa_id = '$HoaId', fin_year = '$FinYear', hoa_no = '$HeadofAcc', pin_id  = '$PinId', 
		pin_no = '$PinNo', re_prop_amt  = '$BudValue', status  = 'P', userid  = '$UserId', active = '1'";
		$InsertDetailsSql 	= mysqli_query($dbConn, $InsertDetailsQuery);
	}
	if($InsertDetailsSql == true){
		$msg = "RE Proposed successfully..!!";
	}else{
		$msg = "Error : RE Not Proposed. Please try again.";
	}
}
$pinquery = "SELECT * FROM pin WHERE active = 1";
$pinsqlquery = mysqli_query($dbConn, $pinquery);

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
					<div class="grid_12">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
								<div class="div12">&nbsp;</div>
								<div class="div12">
									<div class="div2">&nbsp;</div>
									<div class="div8 card-div-body">
										<div class="row divhead head-b" align="center">HOA Revised Budget estimated value Entry</div>
										<div class="top-card">
											<!--<div class="top-card-header">sdsdf</div>-->
											<div class="top-card-container">
												<div class="row">
													<div class="div12 lboxlabel" style="text-align:center">
														<font color="#2769AC" style="font-size:20px"></font>
													</div>
												</div>

												<div class="div12">
													<div class="div3 lboxlabel">PIN No.</div>
													<div class="div3">
														<?php while($row = mysqli_fetch_object($pinsqlquery)) { ?>
														<input type="text" readonly="" name="txt_pin_no" id="txt_pin_no" class="card-label-tbox-lg" value="<?php echo $row->pin_no; ?>">
														<input type="hidden" name="hid_pin_id" id="hid_pin_id" class="card-label-tbox-lg" value="<?php echo $row->pin_id; ?>">
													<?php } ?>
													</div>
													<div class="div3 lboxlabel" align="center">Year</div>
													<div class="div3">
														<select name="cmb_fin_year" id="cmb_fin_year" class="tboxclass" style="overflow-y:auto; z-index:9999 !important;">
															<option value="">----Select----</option>
															<?php echo $objBind->BindFinancialYear(''); ?>
														</select>
													</div>
												</div>
												
												<div class="row clearrow"></div>
												<div class="div12" id="appyear">
													<!-- <div class="div3">
														<label for="name" class="card-label">Head of Account </label>
														</div>
														<div class="div3">
															<input type="text" name="txt_Hoa[]" id="txt_Hoa" class="card-label-tbox-lg" value="<?php echo $List1->hoa_no; ?>">
															<input type="hidden" name="hid_Hoa_id[]" id="hid_Hoa_id" class="card-label-tbox-lg" value="<?php echo $List1->hoa_id; ?>">
														</div>
														<div class="div3" align="center">
														<label for="name" class="card-label">Budget Value (in Cr) </label>
														</div>
														<div class="div3">
															<input type="text" name="txt_bud_val[]" id="txt_bud_val" class="card-label-tbox-lg" value="<?php echo $List1->budget_value; ?>">
														</div>
													</div> -->
												</div>
												<!--<div class="div12">&nbsp;</div>
												<div class="div12">
													<div class="div3">
													<label for="name" class="card-label">Head of Account </label>
													</div>
													<div class="div3">
														<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" value="4861 60 203 44 00 61">
													</div>
													<div class="div3" align="center">
													<label for="name" class="card-label">Budget Value (in Cr) </label>
													</div>
													<div class="div3">
														<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg">
													</div>
												</div>
												<div class="div12">&nbsp;</div>
												<div class="div12">
													<div class="div3">
													<label for="name" class="card-label">Head of Account </label>
													</div>
													<div class="div3">
														<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" value="4861 60 203 44 00 62">
													</div>
													<div class="div3" align="center">
													<label for="name" class="card-label">Budget Value (in Cr) </label>
													</div>
													<div class="div3">
														<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg">
													</div>
												</div>-->												
												<div class="div12">&nbsp;</div>
											</div>
										</div>
									</div>
									<div class="div2"></div>
								</div>
								<div style="text-align:center" id="appbtn">
									<!-- <div class="buttonsection" style="display:inline-table">
										<input type="button" onClick="goBack()" class="btn btn-info" name="back" id="back" value="Back">
									</div>
									<div class="buttonsection" style="display:inline-table">
										<input type="submit" class="btn btn-info" data-type="submit" value=" Save " name="btn_save" id="btn_save"   />
									</div> -->
								</div>
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
	$('#cmb_fin_year').chosen();


	$("body").on("change", "#cmb_fin_year", function(event) {
		var selyear = $(this).val(); //alert(selyear);
		var CourseLoadStr = "";	//alert(2);
		var Btnappend = "";	//alert(2);
		$.ajax({
			type:'GET',
			url: 'ajax/budexpenfinyeardata.php', 
			data: { 'selyear': selyear, 'page':'REHOA' }, 
			dataType: 'json',
			success:function(data){ 	//alert(JSON.stringify(data));
				var HData 	= data['HoaData']; 	//alert(JSON.stringify(AdmHosRoomData));
				var FlagDat	= data['TableFlag']; 
				$.each(HData, function(index, element) {
					CourseLoadStr += 	"<div class='div3 lboxlabel'>Head of Account</div>";
					CourseLoadStr += 	"<div class='div3'>";
					CourseLoadStr += 	"<input type='text' name='txt_Hoa[]' id='txt_Hoa' class='card-label-tbox-lg' value='"+element.hoa_no+"'>";
					CourseLoadStr +=	"<input type='hidden' name='hid_Hoa_id[]' id='hid_Hoa_id' class='card-label-tbox-lg' value='"+element.hoa_id+"'>";
					if(FlagDat == 0){
						if(element.be_prop_amt != null){
							CourseLoadStr +=	"<input type='hidden' name='hid_Hoa_be_id[]' id='hid_Hoa_be_id' class='card-label-tbox-lg' value='"+element.hbeid+"'>";
						}else{
							CourseLoadStr +=	"<input type='hidden' name='hid_Hoa_be_id[]' id='hid_Hoa_be_id' class='card-label-tbox-lg' value=''>";
						}
					}else{
						if(element.re_prop_amt != null){
							CourseLoadStr +=	"<input type='hidden' name='hid_Hoa_be_id[]' id='hid_Hoa_be_id' class='card-label-tbox-lg' value='"+element.hreid+"'>";
						}else{
							CourseLoadStr +=	"<input type='hidden' name='hid_Hoa_be_id[]' id='hid_Hoa_be_id' class='card-label-tbox-lg' value=''>";
						}
					}
					CourseLoadStr += 	"</div>";
					CourseLoadStr += 	"<div class='div3 lboxlabel' align='center'>Budget Value (in Cr)</div>";
					CourseLoadStr += 	"<div class='div3'>";
					if(FlagDat == 0){
						CourseLoadStr +=	"<input type='text' name='txt_bud_val[]' id='txt_bud_val' class='card-label-tbox-lg' value='"+element.be_prop_amt+"'>";
					}else{
						if(FlagDat == 2){
							CourseLoadStr +=	"<input type='text' name='txt_bud_val[]' id='txt_bud_val' class='card-label-tbox-lg' value='"+element.re_appr_amt+"'>";
						}else{
							CourseLoadStr +=	"<input type='text' name='txt_bud_val[]' id='txt_bud_val' class='card-label-tbox-lg' value='"+element.re_prop_amt+"'>";
						}
					}
					CourseLoadStr += 	"</div><div class='row clearrow'></div>";
					CourseLoadStr += 	"</div>";
				});
				if(FlagDat == 1){
					Btnappend += 	"<div class='buttonsection' style='display:inline-table'>";
					Btnappend += 	"<input type='submit' class='btn btn-info' value=' Proposed ' name='btn_prop' id='btn_prop'/>";
					Btnappend += 	"</div>&nbsp;&nbsp;&nbsp;";
					Btnappend += 	"<div class='buttonsection' style='display:inline-table'>";
					Btnappend += 	"<input type='submit' class='btn btn-info' value=' Approved ' name='btn_appr' id='btn_appr'/>";
					Btnappend += 	"</div>";
				}else if(FlagDat == 2){
					Btnappend += 	"<div class='buttonsection' style='display:inline-table'>";
					Btnappend += 	"";
					Btnappend += 	"</div>&nbsp;&nbsp;&nbsp;";
				}else{
					Btnappend += 	"<div class='buttonsection' style='display:inline-table'>";
					Btnappend += 	"<input type='submit' class='btn btn-info' data-type='submit' value=' Save Proposed Amount' name='btn_save' id='btn_save'/>";
					Btnappend += 	"</div>&nbsp;&nbsp;&nbsp;";
				}
				$("#appbtn").html(Btnappend);
				$("#appyear").html(CourseLoadStr);
			}
		});
	});

	$("#btn_save").click(function(event){ 
		var pinnojval 	= $("#txt_pin_no").val(); 
		var finyear 	= $("#cmb_fin_year").val();

		if(pinnojval == ""){ 
			BootstrapDialog.alert("Please Enter PIN No..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(finyear == ""){ 
			BootstrapDialog.alert("Please Select Financial Year..!!!");
			event.preventDefault();
			event.returnValue = false;
		}
	});

</script>
<script>
	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			BootstrapDialog.alert(msg);
		}
	};
</script>
<style>
	#txt_wef_date{
		border:2px solid #09090D;
	}
	.tboxclass{
		padding: 6px 5px;
	}
	.overlay {
		/*-moz-opacity: 0;
		opacity: 0;*/
		rgba(51, 170, 51, .3); 
	}
	.overlay td{
		color:#fff;
		/*border:1px solid #979BA0 !important;*/
		-moz-opacity: 0;
		opacity: 0;
		-webkit-transition: opacity 0.35s, transform 0.35s;
		-moz-transition: opacity 0.35s, transform 0.35s;
		transition: opacity 0.35s, transform 0.35s;
		-webkit-transform: translate3d(50%,50%,0);
		-moz-transform: translate3d(50%,50%,0);
		-o-transform: translate3d(50%,50%,0);
		transform: translate3d(50%,50%,0);
		-ms-transform: translate3d(50%,50%,0);
		/*background-color: rgba(1, 23, 46, 0.8);*/
		background-color: rgba(87, 90, 97, 0.2);
		-moz-opacity: 1;
		opacity: 1;
		-webkit-transform: translate3d(0,0,0);
		-moz-transform: translate3d(0,0,0);
		-o-transform: translate3d(0,0,0);
		-ms-transform: translate3d(0,0,0);
		transform: translate3d(0,0,0);
		color:#05216C;
		/*box-shadow: 0px 1px 2px -2px #979BA0;*/
	}
	.top-card-container{
		min-height:300px;
	}
</style>
