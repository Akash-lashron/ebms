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
		$Exp = explode("-",$year);
		$SYr = $Exp[0]-1;
		$EYr = $Exp[1]-1;
		$Finacyr = $SYr."-".$EYr;
		if($year == null){
			$msg = "Please Select Year of financial year..!!";
		}
	
		if(isset($_POST['cmb_fy'])){
			if($_POST['cmb_fy'] != null){
				$year = $_POST['cmb_fy'];
			

			}
		}
		if($year != null){
			$sql_date = "SELECT * FROM hoa_master WHERE fin_year ='" . $year . "' AND hoa_type ='REC' ORDER BY hoamast_id ASC";

			$heading='For The Year - '. $year;
			$Alertheading='For The Year - '. $Finacyr;
			if($sql_date != null){
				$rs_date_sql = mysqli_query($dbConn,$sql_date);
				if($rs_date_sql == true){
					if(mysqli_num_rows($rs_date_sql)>0){
						$report = true;
						$Displaycount =1;
					}
				}
			}
			
		}
		if($Displaycount != 1){						
			$Previousyr= "SELECT * FROM hoa_master WHERE fin_year ='" . $year . "'-1 AND hoa_type ='REC' ORDER BY hoamast_id ASC";
			$Previodate_sql = mysqli_query($dbConn,$Previousyr);
			if (mysqli_num_rows($Previodate_sql)>0){
				$Display =1;
			}
	}
}
$DeletID=0;
if(isset($_POST['btn_save']) == " Save "){
	$NewHOAstr    	    = $_POST["txt_hoa_code"];
	$FinYear	 	    = $_POST['text_finyear'];
	$FinceYear	 	    = $_POST['txt_finaceyr'];
	$HoaIDstr	 	= $_POST['txt_master_id'];
	$flagstr	 	= $_POST['txt_flag'];
	$Hoamastr=array();
	$sql_date = "SELECT hoamast_id FROM hoa_master WHERE fin_year ='" . $FinYear . "' AND hoa_type ='REC' ORDER BY hoamast_id ASC;";
	$hoa_date_sql = mysqli_query($dbConn,$sql_date);
	if($hoa_date_sql == true){
		if(mysqli_num_rows($hoa_date_sql)>0){
			while($CList = mysqli_fetch_array($hoa_date_sql)){
				array_push($Hoamastr,$CList['hoamast_id']);
			
		    }
		}
	}
	if($HoaIDstr!=null){
		if(in_array($Hoamastr, $HoaIDstr)){
			$DeletID=1;
		}
	}
		
	/*if($DeletID !=1){ 
		foreach($Hoamastr as $Key => $Value){
			$Hoamas    	=     $Hoamastr[$Key];
			$Deletequery    = "DELETE FROM hoa_master WHERE hoamast_id='$Hoamas' AND fin_year='$FinYear'";
			$BFDeletequery    = mysqli_query($dbConn,$Deletequery);	
		}
	}*/
	if(count($Hoamastr) > 0){ 
		foreach($Hoamastr as $Key => $Value){
			//$Hoamas    	=     $Hoamastr[$Key];
			if(in_array($Value, $HoaIDstr)){
			
			}else{
				$Deletequery    = "DELETE FROM hoa_master WHERE hoamast_id = '$Value' AND fin_year = '$FinYear'";
				$BFDeletequery    = mysqli_query($dbConn,$Deletequery);	 
			}
		}
	}

	// Get Existing Hoa Mast Id -> Fin Year
	// check this id in (if in array) $HoaIDstr
	// If it is there dont do anything
	// else delete query
	
	foreach($NewHOAstr as $Key => $Value){
		$NewHOA    	= $NewHOAstr[$Key];
		$HoaID   	= $HoaIDstr[$Key];
		$flag    	= $flagstr[$Key];
		if($HoaID == ''){ 
			$insert_query = "insert into hoa_master set new_hoa_no='$NewHOA', hoa_type = 'REC', modified_on= NOW(), modified_by='$staffid', fin_year='$FinYear', pin_id='$PinId', active ='1'";
			$insert_sql = mysqli_query($dbConn,$insert_query);
			if($insert_sql == true){
				$msg = "Head Of Account Successfully Saved..!!";
			}
		}else{ 
			//$Deletequery    = "DELETE FROM hoa_master WHERE hoamast_id='$ShrtcdID' AND fin_year='$FinYear'";
			//$BFDeletequery    = mysqli_query($dbConn,$Deletequery);	
			$insert_query = "update hoa_master set new_hoa_no='$NewHOA', hoa_type = 'REC', modified_on=NOW(), modified_by='$staffid', fin_year='$FinYear', pin_id='$PinId', active ='1' WHERE hoamast_id = '$HoaID'";
			$insert_sql = mysqli_query($dbConn,$insert_query);
			if($insert_sql == true){
				$msg = "Head Of Account Successfully Saved..!!";
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
								<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">&nbsp;Add New Recovery HOA </div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-3">
													   <div class="div3 pd-lr-1">
														 <div class="lboxlabel-sm">PIN</div>
													    <div>
															<input type="text" readonly="" name="txt_pin_no" id="txt_pin_no" class="card-label-tbox-lg" value="<?php if(isset($PinNo)){ echo $PinNo; } ?>">
															<input type="hidden" name="hid_pin_id" id="hid_pin_id" class="card-label-tbox-lg" value="<?php if(isset($PinId)){ if($PinId != 0){ echo $PinNo; } } ?>">
													    </div>
													</div>
													 <div class="div3 pd-lr-1">
														<div class="lboxlabel-sm">Financial Year</div>
															<input type="hidden" name="text_finyear" id ="text_finyear" class="textbox-new"  value="<?php echo $year; ?>" style="width:110px;">
														<div>
															<select class="group selectlgbox" name="cmb_fy" id="cmb_fy">
																<option value="">---Select---</option>
																<?php echo $objBind->BindFinancialYear($year); ?>
															</select>
													    </div>
													</div>
													<div class="div1 pd-lr-1">
													  <div class="lboxlabel-sm">&nbsp;</div>
													  <div>
													     <input type="submit" name="btn_go" id="btn_go" class="btn btn-sm btn-info" value=" GO ">
													   </div>
													 </div>
												   </div>
													<div class="div9 ">
													<?php
														if($Display==1){
														?>
															&emsp;<i class="fa fa-check-circle-o" style="font-size:25px; color:#EA253C;"></i> <span style="color:EA253C; top:-4px; position:relative;">HOA is Not Created  <?php echo $heading; ?> Showing HOA <?php echo $Alertheading; ?> </span>
														<?php
														}
														?>
														
													</div>
												 </div>
												  <div class="card cabox">
													 <div class="">
													   <div class="card-header inkblue-card" align="left">&nbsp;HOA Entry <?php echo $heading; ?> <span class="ralignbox"></div>
															<div class="card-body padding-1">
															
																	<table class="dataTable etable " align="center" width="100%" id="pgtable1">
																		<tr class="label" style="background-color:#FFF">
																				<!-- <td align="center" >Purpose</td> -->
																			<th align="center">New HOA</th>
																			<th align="center" colspan="2">Action</th>
																		</tr>
																		<tr>
																		    <td align="center"><input type="text" class="tboxsmclass"  name="txt_hoa_code_0" id="txt_hoa_code_0"></td>
                                                                            <td align="center"><input type="button"  name="emp_add" id="emp_add"  value="ADD" class="btn btn-sm btn-info" style="margin-top:0px;"></td>
																		</tr>
																		<?php
																		if($rs_date_sql == true){
																			if (mysqli_num_rows($rs_date_sql)>0){
																				$Rowcount =1;
																			}
																		}if($Rowcount == 1) {
																				while($List = mysqli_fetch_object($rs_date_sql)){
																					$NewHoa 	= $List->new_hoa_no ;
																					$Hoamasid    = $List->hoamast_id;
																					$RecCode    = $List->rec_code;
																					$Finacyr    = $List->fin_year;

																					$Flag = "N";
																				//$slectshort =  "SELECT shortcode_id FROM hoa_master WHERE FIND_IN_SET ($ShortId, shortcode_id)  ORDER BY shortcode_id ASC";
																				
																			?>
																			<tr>
																				<td align="center"><input type="hidden" class="tboxsmclass"  name="txt_master_id[]" id="txt_master_id[]" value="<?php echo $Hoamasid; ?>"><input type="hidden" class="tboxsmclass"  name="txt_flag[]" id="txt_flag[]" value="<?php echo $Flag; ?>">
																				<input type="text" class="tboxsmclass" readonly  name="txt_hoa_code[]" id="txt_hoa_code[]" value="<?php echo $NewHoa; ?>"> </td>
																				<input type="hidden" class="tboxsmclass"  name="txt_finaceyr[]" id="txt_finaceyr[]" value="<?php echo $Finacyr; ?>">
																				<td align="center">
																				<?php if($List->shortcode_id == ""){ ?>
																				<input type="button"  name="emp_delete" id="emp_delete"  value="DELETE" class="btn btn-sm btn-info delete">
																				<?php }else{ ?>
																				HOA already mapped with Shortcode
																				<?php } ?>
																				</td>
																			</tr>
																			<?php
																			} }else{
																				$Previousyr= "SELECT * FROM hoa_master WHERE fin_year ='" . $year . "'-1 AND hoa_type ='REC' ORDER BY hoamast_id ASC";
																				$Previodate_sql = mysqli_query($dbConn,$Previousyr);
																				if($Previodate_sql == true){
																					if(mysqli_num_rows($Previodate_sql)>0){
																						while($CList = mysqli_fetch_object($Previodate_sql)){
																							$NewHoa 	= $CList->new_hoa_no ;
																							$Hoamasid    = $CList->hoamast_id;
																							$RecCode    = $CList->rec_code;
																							$prevFiancyr    = $CList->fin_year;
																							$Exp = explode("-",$prevFiancyr);
																							$SYr = $Exp[0]--;
																							$EYr = $Exp[1]--;
																							$Finacyr = $SYr."-".$EYr;
																							$Flag = "N";
																			  ?>
																			  <tr>
																				<td align="center"><input type="hidden" class="tboxsmclass"  name="txt_master_id[]" id="txt_master_id[]" value="<?php echo $Hoamasid; ?>"><input type="hidden" class="tboxsmclass"  name="txt_flag[]" id="txt_flag[]" value="<?php echo $Flag; ?>">
																				<input type="hidden" class="tboxsmclass"  name="txt_finaceyr[]" id="txt_finaceyr[]" value="<?php echo $Finacyr; ?>">
																				<input type="text" class="tboxsmclass" readonly  name="txt_hoa_code[]" id="txt_hoa_code[]" value="<?php echo $NewHoa; ?>"> </td>
																				<td align="center"><input type="button"  name="emp_delete" id="emp_delete"  value="DELETE" class="btn btn-sm btn-info delete"></td>
																				</tr>
																				<?php
																					}
																				}
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
																		</div>
																		<div class="row clearrow"></div>
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
														
													</div>
												</div>
											</div>
										</div>
										<div class="div2">&nbsp;</div>
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

</div>
</blockquote>
</div>
</div>
</div>
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
	$("#cmb_fy").chosen();
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
				var rowCount	= $('#pgtable1 tr').length;  //alert(rowCount);
			if(rowCount <= 2){
				BootstrapDialog.alert("Please Enter a Atleast one  HOA ..!!");
				event.preventDefault();
				event.returnValue = false;
		
			}else{
				   event.preventDefault();
				    BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to save this HOA  ?',
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
	$("body").on("click", "#emp_add", function(event){ 
		var CheckVal = 0;
		var Hoacode   	= $("#txt_hoa_code_0").val();
		var RowStr = '<tr><td> <input type="hidden" class="tboxclass"  name="txt_master_id[]" id="txt_master_id[]"><input type="text" name="txt_hoa_code[]" readonly  id="txt_hoa_code[]" class="tboxsmclass"  value="'+Hoacode+'"></td><td align="center"><input type="button" class="delete btn btn-info" name="emp_delete" id="emp_delete" value="DELETE" style="margin-top:0px;"></td></tr>'; 
		if(Hoacode == 0){
			BootstrapDialog.alert("Short Code should not be empty");
			return false;
		
		}else{
			$("#pgtable1").append(RowStr);
			$("#txt_hoa_code_0").val('');
		}

	});
	$("body").on("click", ".delete", function(){
		$(this).closest("tr").remove();
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
</style>
</body>
</html>

