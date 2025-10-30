<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Work Bidder Details';
checkUser();
$success = 0;
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'-'. $mm .'-'.$yy;
} 
$RowCount = 0;    
$Finacid  = array();                   
//$result = mysqli_query($dbConn," SELECT a.*,b.* FROM technical_sanction a INNER JOIN hoa b ON a.hoaid = b.hoa_id  ORDER BY a.ts_id ASC");
// $result = "SELECT a.work_name, a.computer_code_no,a.globid,a.sheet_id ,a.contid, a.gst_inc_exc,a.is_less_appl,a.rbn,a.gst_perc_rate,a.rbn,a.upto_dt_sd_rec_amt,a.rbn,a.upto_dt_sd_rbn, b.name_contractor, b.pan_no, b.gst_no FROM sheet a 
// 		INNER JOIN contractor b ON (a.contid = b.contid) 
// 		Where a.active=1 ORDER BY a.sheet_id ASC";
$HoaArr = array();
$QuoteAmtArr = array();
	$result = "SELECT a.work_name, a.computer_code_no,a.globid,a.sheet_id ,a.contid, a.gst_inc_exc,a.is_less_appl,a.rbn,a.gst_perc_rate,a.rbn, b.name_contractor, b.pan_no, b.gst_no FROM sheet a 
		INNER JOIN contractor b ON (a.contid = b.contid) 
		Where a.active=1 and a.under_civil_sheetid = 0 ORDER BY a.sheet_id ASC";
	
$MasterResult = mysqli_query($dbConn,$result);


//print_r($QuoteAmtArr);

// if(isset($_POST["Save"])){
// echo($_POST["Save"]);exit;

// 	$sheet_idArr         =	($_POST['txt_sheetid']);
// 	$contidArr           =	 ($_POST['txt_contid']);
// 	$GlobidArr           =	 ($_POST['txt_globid']);
// 	$pan_noArr           =	($_POST['txt_pan']);
// 	$gst_noArr          =	($_POST['txt_gst']);
// 	$gst_inc_excArr      =	($_POST['cmb_gst_inclu']);
// 	$gst_perc_rateArr    =	($_POST['gst_perc_rate']);
// 	$is_less_applArr     =	($_POST['cmb_lcess_appl']);
// 	$upto_dt_sd_rec_amArr =	($_POST['txt_sd']);
// 	$upto_dt_sd_rbnArr    =	($_POST['txt_updt_rbn']);
	
// 	foreach($sheet_idArr as $ArrKey => $ArrValue){
// 		$sheet_id         =	$sheet_idArr[$ArrKey];
// 		$contid           =	 $contidArr[$ArrKey];
// 		$Globid           =	 $GlobidArr[$ArrKey];
// 		$pan_no           =	$pan_noArr[$ArrKey];
// 		$gst_no           =	$gst_noArr[$ArrKey];
// 		$gst_inc_exc      =	$gst_inc_excArr[$ArrKey];
// 		$gst_perc_rate    =	$gst_perc_rateArr[$ArrKey];
// 		$is_less_appl     =	$is_less_applArr[$ArrKey];
// 		$upto_dt_sd_rec_amt =	$upto_dt_sd_rec_amArr[$ArrKey];
// 		$upto_dt_sd_rbn     =	$upto_dt_sd_rbnArr[$ArrKey];
// 			$UpdatesheetQuery 	= "UPDATE sheet SET gst_perc_rate = '$gst_perc_rate', is_less_appl = '$is_less_appl',gst_inc_exc = '$gst_inc_exc',
// 			                      upto_dt_sd_rec_amt='$upto_dt_sd_rec_amt',upto_dt_sd_rbn='$upto_dt_sd_rbn' WHERE sheet_id  = $sheet_id AND globid  = $Globid";
// 		    $Updatesheetsql     =  mysqli_query($dbConn,$UpdatesheetQuery);

// 			if (($contid!='')|| ($contid!=null)){
// 				$ContQuery          = "UPDATE contractor SET pan_no = '$pan_no', gst_no = '$gst_no' WHERE contid = $contid";
// 				$ContQuerysql       = mysqli_query($dbConn,$ContQuery);
// 			}
			
// 			$UpdateWorkQuery 	= "UPDATE works SET gst_perc_rate = '$gst_perc_rate', is_less_appl = '$is_less_appl',gst_inc_exc = '$gst_inc_exc', 
// 			                        upto_dt_sd_rec_amt='$upto_dt_sd_rec_amt',upto_dt_sd_rbn='$upto_dt_sd_rbn' WHERE sheetid = $sheet_id AND globid  = $Globid";
// 		     $UpdateWorkQuerysql = mysqli_query($dbConn,$UpdateWorkQuery);
			
// 			if($UpdatesheetQuery == true){
// 				$msg = "Work Bidder Deatils Updated";
// 				$success = 1;
// 			}else{
// 				$msg = "Error: Work Bidder Deatils Not Updated...!!! ";
// 			}
			
		
// 		}
	
			
// 	//echo $value;exit;
// }
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript">
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
						<blockquote class="bq1 stable" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<!-- <div class="div1">&nbsp;</div> -->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Work Bidder Details Entry</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																		<tr>
																			<th valign="middle" nowrap="nowrap">SNo.</th>
																			<th valign="middle" nowrap="nowrap">CC Number</th>
																			<th valign="middle" nowrap="nowrap">Work Name</th>
																			<th valign="middle" nowrap="nowrap">Bidder Name</th>
																			<th valign="middle" nowrap="nowrap">PAN NO.</th>
																			<th valign="middle" nowrap="nowrap">GST NO.</th>
																			<th valign="middle" nowrap="nowrap"> GST<br> Inc/Exc.</th>
																			<th valign="middle" nowrap="nowrap">GST-%</th>
																			<th valign="middle" nowrap="nowrap">LCESS Appl.</th>
																			<!-- <th valign="middle" nowrap="nowrap">SD Uptodate<br>( &#8377; )</th>
																			<th valign="middle" nowrap="nowrap">Last <br> RAB</th> -->
																			<th valign="middle" nowrap="nowrap">Action</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr class='labeldisplay'>
																			<?php
																				$SNO = 1; 
																				while($List = mysqli_fetch_object($MasterResult)){
																					$GlobID	 	    = $List->globid;
																					$SheetID	 	= $List->sheet_id ;
																					$Contid	 	    = $List->contid;
																					$CCNo	 	    = $List->computer_code_no;
																					$WORKNAME 	 	= $List->work_name;
																					$CONTNAME 	    = $List->name_contractor;
																					$PANNO 	 	    = $List->pan_no;
																					$GSTNO 	        = $List->gst_no;
																					$ISGSTINClL 	= $List->gst_inc_exc;
																					$GSTPER 	    = $List->gst_perc_rate;
																					$ISLCESS	    = $List->is_less_appl;
																					$SD	            = $List->upto_dt_sd_rec_amt;
																					$RBN	        = $List->upto_dt_sd_rbn;
																			?>
																			<td class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $CCNo; ?><input type="hidden" name="txt_sheetid[]" readonly id="txt_sheetid<?php echo $SheetID; ?>" class="tboxsmclass" value="<?php echo $SheetID; ?>"></td> 
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $WORKNAME ; ?><input type="hidden" name="txt_globid[]" readonly id="txt_globid<?php echo $SheetID; ?>" class="tboxsmclass" value="<?php echo $GlobID; ?>"></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $CONTNAME; ?><input type="hidden" name="txt_contid[]" readonly id="txt_contid<?php echo $SheetID; ?>" class="tboxsmclass" value="<?php echo $Contid; ?>"></td>
																			<?php 
																			if(($PANNO == '')||($PANNO == null)){?>
																			<td style="width:150px;"><input type="text" name="txt_pan[]" id="txt_pan<?php echo $SheetID; ?>" class="tboxsmclass" value=""></td>
																			<?php 
																			} else{?>
																			<td style="width:150px;"><input type="text" name="txt_pan[]" id="txt_pan<?php echo $SheetID; ?>" readonly class="tboxsmclass" value="<?php echo $PANNO; ?>"></td>
																			<?php  }?>
																			<?php 
																			if(($GSTNO == '')||($GSTNO == null)){?>
																			<td style="width:160px;"><input type="text" name="txt_gst[]" id="txt_gst<?php echo $SheetID; ?>" class="tboxsmclass" value=""></td>
																			<?php 
																			} else{?>
																			<td style="width:160px;"><input type="text" name="txt_gst[]" id="txt_gst<?php echo $SheetID; ?>" readonly class="tboxsmclass" value="<?php echo $GSTNO; ?>"></td>
																			<?php  }?>
																			<td style="width:150px;"><select name="cmb_gst_inclu[]" id="cmb_gst_inclu<?php echo $List->sheet_id; ?>"  class="tboxsmclass" >
																			<option value="">--Select--</option>
																					<option value="I"<?php if((isset($ISGSTINClL))&&($ISGSTINClL == 'I')){ echo 'selected="selected"'; } ?>>INCLUSIVE</option>
																					<option value="E" <?php if((isset($ISGSTINClL ))&&($ISGSTINClL == 'E')){ echo 'selected="selected"'; } ?>>EXCLUSIVE</option>
																			</select></td>
																			<?php 
																			if(($GSTPER == '')||($GSTPER == null)||($GSTPER == '0.00')){?>
																			<td style="width:98px;"><input type="text" name="gst_perc_rate[]" id="gst_perc_rate<?php echo $SheetID; ?>" class="tboxsmclass" value=""></td>
																			<?php 
																			} else{?>
																			<td style="width:98px;" valign='middle' class='tdrow' align = 'justify'><input type="text" readonly  class="tboxsmclass" name="gst_perc_rate[]" id="gst_perc_rate<?php echo $SheetID; ?>" value="<?php echo $GSTPER; ?>"></td>
																			<?php  }?>
																			<td valign='middle' class='tdrow' align = 'justify'><select name="cmb_lcess_appl[]" id="cmb_lcess_appl<?php echo $List->sheet_id; ?>"  class="tboxsmclass" >
																			        <option value="">--Select--</option>
																					<option value="Y"<?php if((isset($ISLCESS))&&($ISLCESS == 'Y')){ echo 'selected="selected"'; } ?>>YES</option>
																					<option value="N" <?php if((isset($ISLCESS ))&&($ISLCESS == 'N')){ echo 'selected="selected"'; } ?>>NO</option>
																			</select></td>
																			<!-- <td valign='middle' class='tdrow' align = 'justify'><input type="text" name="txt_sd[]" id="txt_sd<?php echo $List->sheet_id; ?>"class="tboxsmclass" value="<?php echo $List->upto_dt_sd_rec_amt; ?>"></td>
																			<td valign='middle' class='tdrow' align = 'justify'><input type="text" name="txt_updt_rbn[]" id="txt_updt_rbn<?php echo $List->sheet_id; ?>"class="tboxsmclass" value="<?php echo $RBN; ?>"></td> -->
																			<td valign='middle' class='tdrow'>
																			<input type="button" class="btn btn-info" name="Save[]" id="Save" data-id="<?php echo $SheetID; ?>" value=" Save " /> </td>
																		</tr>
																			<?php 
																			 		$SNO++; 
																				} 
																			?>
																   </tbody>
																</table>
															</div>
														</div>
														<div align="center">
															<!-- <input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" /> -->
															<a data-url="LOIEntry" class="btn btn-info" name="view" id="view">Back</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="div1">&nbsp;</div> -->
								</div>
							</div>
							<div align="center">&nbsp;</div>
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
$(document).ready(function(){ 
	$('.dataTable').DataTable({"paging":false,"ordering": false});
	$("#exportToExcel").click(function(e){ 
		var table = $('body').find('.table2excel');
		if(table.length){ 
			$(table).table2excel({
				exclude: ".noExl",
				name: "Excel Document Name",
				filename: "SingleLineAbstract-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
				fileext: ".xls",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true
				//preserveColors: preserveColors
			});
		}
	});
});
</script>
<script> 
	var msg = "<?php echo $msg; ?>";
	var titletext = "";
		document.querySelector('#top').onload = function(){
		if(msg != "")
		{
			//swal(msg, "");
			swal({
				 title: "",
				 text: msg,
				 confirmButtonColor: "#3dae38",
				 type:"success",
				 confirmButtonText: " OK ",
				 closeOnConfirm: false,
			},
			function(isConfirm){
				 if (isConfirm) {
					url = "WorkBidderDetails.php";
					window.location.replace(url);
				 }
			});
		}
	};
	$("body").on("click", "#Save", function(event){ 
		var saveid=$(this).attr('data-id'); //alert(saveid);
		var pan_no= $("#txt_pan"+saveid).val();//alert(pan_no);
		var gst_no= $("#txt_gst"+saveid).val(); //alert(gst_no);
		var gst_inc_exc= $("#cmb_gst_inclu"+saveid).val(); //alert(gst_inc_exc);
		var gst_perc_rate= $("#gst_perc_rate"+saveid).val(); //alert(gst_perc_rate);
		var is_less_appl= $("#cmb_lcess_appl"+saveid).val(); //alert(is_less_appl);
		//var upto_dt_sd_rec_amt= $("#txt_sd"+saveid).val();  //alert(upto_dt_sd_rec_amt);
		//var upto_dt_sd_rbn= $("#txt_updt_rbn"+saveid).val(); //alert(upto_dt_sd_rbn);
		var contid= $("#txt_contid"+saveid).val(); //alert(contid);
		var Globid= $("#txt_globid"+saveid).val(); //alert(Globid);
		if(saveid != ""){
			$.ajax({
				type:'POST',
				url: 'SaveWorkBidderDetails.php', 
				dataType: 'json',
				data:{'saveid':saveid,'pan_no':pan_no,'gst_no':gst_no,'gst_inc_exc':gst_inc_exc,
					'gst_perc_rate':gst_perc_rate,'gst_perc_rate':gst_perc_rate,'is_less_appl':is_less_appl,
					'contid':contid,'Globid':Globid,}, 
				success:function(data){  //alert(data);	
					var TenData = data['status'];
					$("#hid_status").val(TenData);
					if(TenData == 1){
						BootstrapDialog.show({
							message: "Work Bidder Details Updated",
							buttons: [{
								label: ' OK ',
								action: function(dialog) {
									dialog.close();
									window.location.replace('WorkBidderDetails.php');
								}
							}]
						});
					}else{
						BootstrapDialog.show({
							message: "Work Bidder Details Not Updated",
							buttons: [{
								label: ' OK ',
								action: function(dialog) {
									dialog.close();
									window.location.replace('WorkBidderDetails.php');
								}
							}]
						});
					}
				}
			
			});
		}

	});
</script>