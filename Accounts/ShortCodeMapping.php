<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Short Code Creation';
$msg = ""; $del = 0;
$RowCount =0; $InQueryCon = 0;
$staffid = $_SESSION['sid'];
$PGDataArr=array();
$GlobPartARecArr = array("LCESS"=>"LCess","MOB"=>"Mob.Adv. Rec.","PM"=>"P&M.Adv. Rec.","HIRE"=>"Hire Charges","OTH"=>"Other Recoveries");


$GlobPartBRecArr = array("CSGT"=>"CSGT","SGST"=>"SGST","IGST"=>"IGST","IT"=>"IT","SD"=>"SD","WC"=>"Water Charges","EC"=>"Electricity Charges","MOBINT"=>"Mob. Adv. Interest","PMINT"=>"P&M Adv. Interest");
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

$PinNo = '';
$PinId = '';
$SelectPinQuery = "SELECT * FROM pin";
$SelectPinSql 	= mysqli_query($dbConn,$SelectPinQuery);
if($SelectPinSql == true){
	if(mysqli_num_rows($SelectPinSql)>0){
		$CList = mysqli_fetch_object($SelectPinSql);
		$PinNo = $CList->pin_no;
		$PinId = $CList->pin_id;
	}
}
	if(isset($_POST['btn_go'])){
	
		$year = $_POST['cmb_fy'];
		$finyear =$_POST['cmb_fy'];
		
		if($year == null){
			$msg = "Please Select Year of financial year..!!";
		}
	
		if(isset($_POST['cmb_fy'])){
			if($_POST['cmb_fy'] != null){
				$year = $_POST['cmb_fy'];
				$finyear =$_POST['cmb_fy'];
		
			}
		}
		if($year != null){
			$sql_date = "SELECT * FROM hoa_master WHERE fin_year ='" . $year . "' ORDER BY new_hoa_no ASC";
			$heading='For The Year - '. $year;
			if($sql_date != null){
				$rs_date_sql = mysqli_query($dbConn,$sql_date);
				if($rs_date_sql == true){
					if(mysqli_num_rows($rs_date_sql)>0){
						$report = true;
					}
				}
			}

		}
	}

if(isset($_POST['btn_save']) == " Save "){

	$FinYear	 	= $_POST['text_finyear'];
	$HoamasterIDstr = $_POST['text_hoamaster'];
	//print_r($HoamasterIDstr  );exit;

	//$ShortcodeStr      = $_POST['cmb_shortcode'];
	//print_r($ShortcodeStr);exit;
	//$ObjHead = $_POST["txt_obj_head"];

	if($Shortcode == NULL){
		$msg = "Please Select Short Code ..!!";
	
	}else{
		$InQueryCon = 1;
	}
	
	foreach($HoamasterIDstr as $Key => $Value){
		//$Shortcodes    	= $ShortcodeStr[$Key];
		$MasterID   		= $HoamasterIDstr[$Key];
		$ShortcodeStr       = $_POST['cmb_shortcode_'.$MasterID];
		//print_r($ShortcodeStr);
		if(($ShortcodeStr != NULL)&&($ShortcodeStr !='')){
			if(count($ShortcodeStr)>0){
				$Shortcode  = implode(",",$ShortcodeStr);
				$update_query =  "UPDATE hoa_master SET shortcode_id = '$Shortcode' WHERE hoamast_id = '$MasterID'";
				$insert_sql = mysqli_query($dbConn,$update_query);

				if($insert_sql == true){
					$msg =  "Short Code Successfully Mapped..!!";
				}
			}
		}
		
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
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "Configuration.php";
		window.location.replace(url);
	}
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
                    <blockquote class="bq1" style="overflow:auto">
					<div class="row">
					  <input type="hidden" name="max_group" id="max_group" value="1" />
						<div class="box-container box-container-lg">
						   <!-- <div class="div2">&nbsp;</div>-->
							<div class="div12">
								<div class="card cabox">
									<div class="face-static">
										<div class="card-header inkblue-card" align="center">&nbsp; Short Code Mapping with HOA </div>
										<div class="card-body padding-1 ChartCard" id="CourseChart">
											<div class="divrowbox pt-2">
												<div class="div2 pd-lr-1">
													<div class="lboxlabel-sm">PIN</div>
												    <div>
														<input type="text" readonly="" name="txt_pin_no" id="txt_pin_no" class="card-label-tbox-lg" value="<?php if(isset($PinNo)){ echo $PinNo; } ?>">
														<input type="hidden" name="hid_pin_id" id="hid_pin_id" class="card-label-tbox-lg" value="<?php if(isset($PinId)){ if($PinId != 0){ echo $PinNo; } } ?>">
													</div>
												</div>
												<div class="div3 pd-lr-1">
													<div class="lboxlabel-sm">Financial Year</div>
													<div>
													   <input type="hidden" name="text_finyear" id ="text_finyear" class="textbox-new"  value="<?php echo $year; ?>" style="width:110px;">
														<select class="group selectlgbox" name="cmb_fy" id="cmb_fy">
															<option value="">---Select---</option>
															<?php echo $objBind->BindFinancialYear( $year); ?>
														</select>
													 </div>
												</div>
												<div class="div1 pd-lr-1">
													<div class="lboxlabel-sm">&nbsp;</div>
													<div>
														<input type="submit" name="btn_go" id="btn_go" class="btn btn-sm btn-info" value=" GO">
													</div>
												</div>
												<div class="div6 pd-lr-1">
													<div class="lboxlabel-sm">&nbsp;</div>
													<!--<div align="right">
														<a data-url="Shortcodecreation" class="btn btn-sm btn-info">Add New Short Code</a>
													</div>-->
												</div>
											</div>
										</div>
										<div class="card cabox">
											 <div class="">
												 <div class="card-header inkblue-card" align="left">&nbsp;Short Code Mapping <?php echo $heading; ?> <span class="ralignbox"></div>
												<div class="card-body padding-1">
															
															<?php if($report==true){ ?>
																<table class="dynamicTable etable " align="center" width="100%" id="pgtable1">
																	<tr class="label" style="background-color:#FFF">
																		<th align="center">New HOA No.</th>
																		<th align="center">Short Code </th>
																	</tr>
																	<tr>
																	<?php
																	if($rs_date_sql == true){
																		while($List = mysqli_fetch_object($rs_date_sql)){
																			$HOANo  	= $List->new_hoa_no ;
																			$MasteID 	= $List->hoamast_id ;
																			$ScodeArr 	= $List->shortcode_id ;
																		?>
																		<td align="center" style="width:200px"><input type="hidden" class="tboxclass"  name="text_hoamaster[]" id="text_hoamaster[]" value="<?php if(isset($MasteID)){ if($MasteID != 0){ echo $MasteID; } } ?>"><input type="text" class="tboxsmclass"  name="txt_hoa[]" id="txt_hoa[]" value="<?php if(isset($HOANo)){ if($HOANo != 0){ echo $HOANo; } } ?>"></td>
																		<td align="center">
																			<select name="cmb_shortcode_<?php echo $MasteID; ?>[]" id ="cmb_shortcode[]"  class="tboxsmclass ShortCode labeldisplay" multiple="multiple"> 
																			<?php echo $objBind->BindShortCode($ScodeArr, $year); ?>
																			</select>
																		</td>
																	</tr>
																	<?php
																	} 	
																	}
																?>
																</table>
																<div class="row clearrow"></div>
																<div class="div12" align="center">	
																	<div class="row">
																		<div class="div12" align="center">
																			<input type="button" onClick="goBack()" class="btn btn-info" name="back" id="back" value="Back">																
																			<input type="submit" class="btn btn-info" data-type="submit" value=" Save " name="btn_save" id="btn_save"   />
																		 </div>
																	 </div>
																	 <div class="row clearrow"></div>
																</div>
															<?php } ?>
																
															
												   </div>
												</div>
											</div>
										</div>
							    	</div>
								<!--<div class="div2">&nbsp;</div>-->
							 </div>
						</div>
					</div>
			    </div>
			</div>
		</div>
	</div>
</div></div>
</div>
</div>
</blockquote>
</div>
</div>
</div>			
														<!-- <div class="card-header inkblue-card" align="left">&nbsp;Short Code  Entry <span class="ralignbox"></div>
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
														</div> -->
														
										    
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
	$("#cmb_fy").chosen();
	$(".ShortCode").chosen();
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	var KillEvent = 0;	
	$(document).ready(function(){ 
		$("body").on("click","#btn_save", function(event){
			if(KillEvent == 0){
			var ShortName   	= $("#txt_shrt_code").val();
			var SDPErc	       = $("#cmb_short_desc").val();
		
			if(ShortName == ""){
				BootstrapDialog.alert("Please Enter a Short Code..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(SDPErc == ""){
				BootstrapDialog.alert("Please Select Description..!!");
				event.preventDefault();
				event.returnValue = false;
			}else{
				   event.preventDefault();
				    BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to save this Short Code  ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
						if(result){
							KillEvent = 1;
							$("#btn_save").trigger( "click" );
						}else {
							KillEvent = 0;
						}
					}
				});
			   }
		     }
	     });
	});
		
	$("body").on("click","#btn_go", function(event){
	var SelYear		= $("#cmb_fy").val();
	if(SelYear == ""){
		BootstrapDialog.alert("Please select atleast one period..!!");
		event.preventDefault();
		event.returnValue = false;
	}
});

	</script>

<style>
.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
.chosen-container-multi .chosen-choices li.search-field input[type="text"]{
	height:18px;
}
.chosen-container-multi .chosen-choices li.search-choice{
	color: #001BC6;
	background-image: linear-gradient(#ffffff 20%,#ffffff 50%,#ffffff 52%,#ffffff 100%);
	background-image: -webkit-gradient(linear,50% 0,50% 100%,color-stop(20%,#ffffff),color-stop(50%,#ffffff),color-stop(52%,#ffffff),color-stop(100%,#ffffff));
	background-image: -webkit-linear-gradient(#ffffff 20%,#ffffff 50%,#ffffff 52%,#ffffff 100%);
	background-image: -moz-linear-gradient(#ffffff 20%,#ffffff 50%,#ffffff 52%,#ffffff 100%);
	background-image: -o-linear-gradient(#ffffff 20%,#ffffff 50%,#ffffff 52%,#ffffff 100%);
	font-size: 12px;
	margin: 1px 5px 1px 0;
	font-weight:500;
}
</style>
</body>
</html>

