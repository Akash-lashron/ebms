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
$GlobPartARecArr = array("LCESS"=>"LCess","MOB"=>"Mob.Adv. Rec.","PM"=>"P&M.Adv. Rec.","HIRE"=>"Hire Charges","OTH"=>"Other Recoveries");
$GlobPartBRecArr = array("CGST"=>"CGST","SGST"=>"SGST","IGST"=>"IGST","IT"=>"IT","SD"=>"SD","WC"=>"Water Charges","EC"=>"Electricity Charges","MOBINT"=>"Mob. Adv. Interest","PMINT"=>"P&M Adv. Interest");
//$GlobPartOthArr = array("WORKS"=>"Works","SAL"=>"Salary","OTH"=>"Others");
$GlobPartRecPayArr = array("COMPCESS"=>"Ded of Health & Education Cess from Contractor","ITSAL"=>"Ded of IT at source incl. pen (IT on Union Emol.)","ITCESS"=>"Ded of Health & Education Cess from Contractor (IT on other than Union Emol.)","AERMISBARCF"=>"Misc. Receipts - BARCF/PRP","IRREC"=>"Other Advances - Immediate Relief (Receipt)","IMPREC"=>"Permanent Cash Imprest","FRFCF01LTC"=>"Leave Travel Concession (Sal)","FRFCF01GOPRIS"=>"G.O. PRIS (O) (Sal)","FRFCF01NGOPRIS"=>"N.G.O. PRIS (O) (Sal)","FRFCF11DTE"=>"Domestic Travel Expenses","FRFCF13FOE"=>"Furnitures (Off. Exp.)","FRFCF13MFOE"=>"Maintenance of Furniture (Off. Exp.)","FRFCF13OOEOE"=>"Other Office Equipment (Off. Exp.)","FRFCF13MOOOE"=>"Maintenance of Other Office equipment (Off. Exp.)","FRFCF13TRCOE"=>"Telephone Rental Charges (Off. Exp.)","FRFCF13PSOE"=>"Printing & Stationary (Off. Exp.)","FRFCF13FROE"=>"Freight (Off. Exp.)","FRFCF13COE"=>"Conveyance (Off. Exp.)","FRFCF13LOE"=>"Liveries (Off. Exp.)","FRFCF13HKMOE"=>"House Keeping Materials (Off. Exp.)","FRFCF13PTCOE"=>"Postage & Telegram Charges (Off. Exp.)","FRFCF13HOE"=>"Hospitality (Off. Exp.)","FRFCF13BPPOE"=>"Books & Periodical Publications (Off. Exp.)","FRFCF13HCOE"=>"Hire Charges (Off. Exp.)","FRFCF13MMVOE"=>"Maintenance of Motor Vehicle (Off. Exp.)","FRFCF13MOE"=>"Miscellaneous (Off. Exp.)","FRFCF13FEOE"=>"Fuel (Off. Exp.)","FRFCF28PSOE"=>"Professional Services (Off. Exp.)","FRFCF51MVOE"=>"Motor Vehicle  (Off. Exp.)","FRFCF52MEOE"=>"Machinary & Equipment (Off. Exp.)","FRFCF53OCEOE"=>"Other Capital Expenditure (Off. Exp.)","FRFCF60MWOE"=>"Major Works (Off. Exp.)","HBAPMT"=>"House building advance (Loan & Advance to govt servant)","PCAPMT"=>"Personal Computer (Loan & Advance to govt servant)","GPFPMT"=>"G.P.F (State PF)","CPFPMT"=>"C.P.F (State PF)","SDFPMT"=>"Security Deposit (Payment)");

$GlobPartRecPayArr = array();
$SelectPRQuery = "SELECT * FROM pay_rec_master WHERE active = 1";
$SelectPRSql   = mysqli_query($dbConn,$SelectPRQuery);
if($SelectPRSql == true){
	if(mysqli_num_rows($SelectPRSql)>0){
		while($PRListA = mysqli_fetch_object($SelectPRSql)){
			$GlobPartRecPayArr[$PRListA->prcode] = $PRListA->pr_desc;
		}
	}
}


$GlobPartOthArr = array("WORKS"=>"Works");
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
	if($year == null){
		$msg = "Please Select Year of financial year..!!";
	}

	if(isset($_POST['cmb_fy'])){
		if($_POST['cmb_fy'] != null){
			$year = $_POST['cmb_fy'];
	
		}
	}
	if($year != null){
		$sql_date = "SELECT * FROM shortcode_master WHERE fin_year ='" . $year . "' ORDER BY shortcode_id ASC;";

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

	$Shortcodestr    	= $_POST["txt_shrt_code"];
	$ShorDescstr	    = $_POST["cmb_rec_desc"];
	$FinYear	 	    = $_POST['text_finyear'];
	$ShrtcdIDstr	 	= $_POST['txt_shrt_id'];
	$flagstr	 	    = $_POST['txt_flag'];
	$RecCodeStr	 	    = $_POST['txt_rec_code'];

	if($Shortcodestr == NULL){
		$msg = "Please Enter Short Code Number..!!";
	}else if($ShorDescstr == NULL){
		$msg = "Please Select Description..!!";
	}else{
		$InQueryCon = 1;
	}
	$Hoamastr=array();
	$sql_date = "SELECT shortcode_id FROM shortcode_master WHERE fin_year ='" . $FinYear . "'  ORDER BY shortcode_id ASC;";
	$hoa_date_sql = mysqli_query($dbConn,$sql_date);
	if($hoa_date_sql == true){
		if(mysqli_num_rows($hoa_date_sql)>0){
			while($CList = mysqli_fetch_array($hoa_date_sql)){
				array_push($Hoamastr,$CList['shortcode_id']);
		
			}
		}
	}
	if($ShrtcdIDstr!=null){
		if(in_array($Hoamastr, $ShrtcdIDstr)){
			$DeletID = 1;
		}
	}
	
	if(count($Hoamastr) > 0){ 
		foreach($Hoamastr as $Key => $Value){
			//$Hoamas    	=     $Hoamastr[$Key];
			if(in_array($Value, $ShrtcdIDstr)){
			
			}else{
				$Deletequery    = "DELETE FROM shortcode_master WHERE shortcode_id = '$Value' AND fin_year = '$FinYear'";
				$BFDeletequery    = mysqli_query($dbConn,$Deletequery);	 
			}
		}
	}

	foreach($Shortcodestr as $Key => $Value){
		$Shortcode    	= $Shortcodestr[$Key];
		$ShorDesc    	= $ShorDescstr[$Key];
		$ShrtcdID    	= $ShrtcdIDstr[$Key];
		$flag    	    = $flagstr[$Key];
		$RecCode    	= $RecCodeStr[$Key];

		if($InQueryCon == 1){
			if($ShrtcdID == ''){ 
				$insert_query = "insert into shortcode_master set shortcode='$Shortcode', rec_code = '$RecCode', shortcode_desc='$ShorDesc', fin_year='$FinYear', active ='1'";
				$insert_sql = mysqli_query($dbConn,$insert_query);

			}else {
				//$Deletequery    = "DELETE FROM shortcode_master WHERE shortcode_id='$ShrtcdID' ";
				//$BFDeletequery    = mysqli_query($dbConn,$Deletequery);	
				$insert_query = "update shortcode_master set shortcode='$Shortcode', rec_code = '$RecCode',  shortcode_desc='$ShorDesc', fin_year='$FinYear', active ='1' WHERE shortcode_id='$ShrtcdID'";
			   	$insert_sql = mysqli_query($dbConn,$insert_query);
			}
			if($insert_sql == true){
				$msg = "Short Code Successfully Saved..!!";
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
												<div class="card-header inkblue-card" align="center">&nbsp;Short Code Creation </div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-3">
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
													<div class="card cabox">
													   <div class="">
													   <div class="card-header inkblue-card" align="left">&nbsp;Short Code Entry <?php echo $heading; ?> <span class="ralignbox"></div>
																<div class="card-body padding-1">
																	
																		<table class="dataTable etable " align="center" width="100%" id="pgtable1">
																			<tr class="label" style="background-color:#FFF">
																				<!-- <td align="center" >Purpose</td> -->
																				<th align="center">Short Code</th>
																				<th align="center">Short Code Description </th>
																				<th align="center" colspan="2">Action</th>
																			</tr>
																			<tr>
																			<td align="center"><input type="text" class="tboxsmclass"  name="txt_shrt_code_0" id="txt_shrt_code_0"></td>
																				<td align="center">
																					<select name="cmb_rec_desc_0" id ="cmb_rec_desc_0"  class="tboxsmclass"> 
																						<option value="">---- Select ---- </option>	 
																						<!--<optgroup label="Part A:">
																						<?php //foreach($GlobPartARecArr as $PARecKey => $PARecValue) { ?>
																								<option value="<?php echo $PARecKey; ?>"><?php echo $PARecValue; ?></option>
																						<?php //} ?>
																						</optgroup>-->
																						<optgroup label="Part B:">
																						<?php foreach($GlobPartBRecArr as $PBRecKey => $PBRecValue) { ?>
																							<option value="<?php echo $PBRecKey; ?>"><?php echo $PBRecValue; ?></option>
																						<?php } ?>
																						</optgroup>
																						<optgroup label="Others:">
																						<?php foreach($GlobPartOthArr as $OthKey => $OthValue) { ?>
																							<option value="<?php echo $OthKey; ?>"><?php echo $OthValue; ?></option>
																						<?php } ?>
																						</optgroup>
																						<optgroup label="Receipt & Payment:">
																						<?php foreach($GlobPartRecPayArr as $RecPayKey => $RecPayValue) { ?>
																							<option value="<?php echo $RecPayKey; ?>"><?php echo $RecPayValue; ?></option>
																						<?php } ?>
																						</optgroup>
																					</select>
																				</td>
																				<td align="center"><input type="button"  name="emp_add" id="emp_add"  value="ADD" class="btn btn-sm btn-info" style="margin-top:0px;"></td>
																			</tr>
																			<?php
																			if($rs_date_sql == true){
																				while($List = mysqli_fetch_object($rs_date_sql)){
																					$ShortId 	= $List->shortcode_id ;
																					$Shocode 	= $List->shortcode; 
																					$Shodescr    = $List->shortcode_desc;
																					$RecCode    = $List->rec_code;
																					$Flag = "N";
																					//$slectshort =  "SELECT shortcode_id FROM hoa_master WHERE FIND_IN_SET ($ShortId, shortcode_id)  ORDER BY shortcode_id ASC";
																					$slectshort =  "SELECT shortcode_id FROM hoa_master WHERE fin_year = '$year' AND 
																					(shortcode_id = '$ShortId' OR shortcode_id LIKE '$ShortId,%' OR shortcode_id LIKE '%,$ShortId' OR shortcode_id LIKE '%,$ShortId,%') 
																					ORDER BY shortcode_id ASC";
																					$shortcod_sql = mysqli_query($dbConn,$slectshort);
																					if($shortcod_sql == true){
																						if(mysqli_num_rows($shortcod_sql)>0){
																							while($List = mysqli_fetch_object($shortcod_sql)){
																								$Shortcd 	= $List->shortcode_id;
																								if($Shortcd != null){
																									$Flag = "Y";
																								}else{
																									$Flag = "N";
																								}
																								
																							}
																						}
																					}
																				?>
																			<tr>
																				
																			<td align="center"><input type="hidden" class="tboxsmclass"  name="txt_shrt_id[]" id="txt_shrt_id[]" value="<?php echo $ShortId; ?>"><input type="hidden" class="tboxsmclass"  name="txt_flag[]" id="txt_flag[]" value="<?php echo $Flag; ?>"><input type="text" class="tboxsmclass" readonly  name="txt_shrt_code[]" id="txt_shrt_code[]" value="<?php echo $Shocode; ?>"> </td>
																			<td align="center">
																			<input type="text" name="cmb_rec_desc[]" id ="cmb_rec_desc[]" readonly  class="tboxsmclass"  value="<?php echo $Shodescr; ?>"> 
																			<input type="hidden" name="txt_rec_code[]" id ="txt_rec_code[]" readonly  class="tboxsmclass"  value="<?php echo $RecCode; ?>"> 
																			</td>
																			<?php
																		    if($Flag == "Y"){ 
																			?>
																			<td align="center" nowrap="nowrap">Already Mapped with HOA</td>
																			<?php
																			}else{
																			?>
																			<td align="center"><input type="button"  name="emp_delete" id="emp_delete"  value="DELETE" class="btn btn-sm btn-info delete"></td>
																			<?php
																			}															
																			}
																			?>
																				</tr>
																			<?php
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
			var ShortName   	= $("#txt_shrt_code").val();
			var SDPErc	       = $("#cmb_rec_desc").val();
		
			var rowCount	   = $('#pgtable1 tr').length;  //alert(rowCount);
			if(rowCount <= 2){
				BootstrapDialog.alert("Please Enter a Atleast one  Short Code..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(ShortName == ""){
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
	$("body").on("click", "#emp_add", function(event){ 
		var CheckVal = 0;
		var ShortName   	= $("#txt_shrt_code_0").val();
		var RecCode	       = $("#cmb_rec_desc_0").val();
		var SDPErc	       = $("#cmb_rec_desc_0 option:selected").text();
		var RowStr = '<tr><td><input type="hidden" class="tboxsmclass"  name="txt_shrt_id[]" id="txt_shrt_id[]" vlaue=""><input type="hidden" class="tboxclass"  name="txt_shrt_codeid[]" id="txt_shrt_codeid[]"><input type="text" name="txt_shrt_code[]" readonly  id="txt_shrt_code[]" class="tboxsmclass"  value="'+ShortName+'"></td><td><input type="text" readonly name="cmb_rec_desc[]" id="cmb_rec_desc[]" class="tboxsmclass"  value="'+SDPErc+'"><input type="hidden" name="txt_rec_code[]" id ="txt_rec_code[]" readonly  class="tboxclass"  value="'+RecCode+'"> </td><td align="center"><input type="button" class="delete btn btn-info" name="emp_delete" id="emp_delete" value="DELETE" style="margin-top:0px;"></td></tr>'; 
		if(ShortName == 0){
			BootstrapDialog.alert("Short Code should not be empty");
			return false;
		}else if(SDPErc == 0){
			BootstrapDialog.alert("Please Select atleast one type");
			return false;
		}else{
			$("#pgtable1").append(RowStr);
			$("#txt_shrt_code_0").val('');
			$("#cmb_rec_desc_0").val('');
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

