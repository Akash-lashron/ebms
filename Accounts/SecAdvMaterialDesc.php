<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Secured Advance Material Description';
$msg = "";


if(isset($_POST['btn_save'])){

	$DiscipVal = $_POST['cmb_discip'];
	$DescVal = $_POST['txt_desc'];  
	$HiddenIdVal = $_POST["hid_id"];
  
	if(($HiddenIdVal==null)||($HiddenIdVal=='')){
		$InsertQuery = "INSERT INTO sa_mat_desc SET discipline_id ='$DiscipVal', mat_desc='$DescVal', active=1";
		$InsertSql = mysqli_query($dbConn,$InsertQuery);
		if($InsertSql== true){
			$msg = "Details Successfully Saved..!!";}
		else{ 
			$msg = "Details not saved";
		}
	}else{
		//echo $UpdateQuery;exit;		
		$UpdateQuery = "UPDATE sa_mat_desc SET discipline_id ='$DiscipVal', mat_desc='$DescVal', active=1 WHERE sa_id ='$HiddenIdVal'"; 	
		$UpdateSql = mysqli_query($dbConn,$UpdateQuery);
		if($UpdateSql == true){
			$msg = "Details Successfully Updated..!!";}
		else{ 
			$msg = "Details not saved";
		} 
	}    
}    
$selectquery = "SELECT * FROM sa_mat_desc WHERE active = 1";
$selectsql   = mysqli_query($dbConn,$selectquery);
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
								<input type="hidden" name="max_group" id="max_group" value="1" />
								
								
								
								
								<div class="box-container box-container-lg">
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Secured Advance Material Description<span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
														<!--
														<div class="divrowbox pt-2" id="table">
															<div class="lboxlabel-sm">Discipline</div>
															<div>
																<select class="tboxsmclass" name="cmb_discip" id="cmb_discip">
																	<option value="">--select--</option>
																	<?php //echo $objBind->BindDiscipline(); ?>	
																</select>
															</div>
														</div>
														<div class="divrowbox pt-2" id="table">
															<div class="lboxlabel-sm">Description</div>
															<div>
																<input type="text"  class="tboxsmclass"  name="txt_desc" id="txt_desc" >
															</div>
														</div>
														<input type="hidden" id="hid_id" name="hid_id"  placeholder="" value="">
														<div class="div2 pd-lr-1">
															<div class="row">
																<div align="center">
																	<input type="submit" id="btn_save" name="btn_save" class="btn btn-sm btn-info" value="Save"/>
																
																	<input type="reset" id="btn_delete" name="btn_delete" value="Delete">
																</div>
															</div>
														</div>
														-->

															
															<!-- <tr>
																<td align="center">Discipline</td>
																<td>
																	<select name="cmb_discip" id="cmb_discip" class="tboxsmclass">
																			<option value="">--select--</option>
																			<?php //echo $ObjBind->BindDisicipline($Displineid); ?>
																			
																	</select>
																</td>
																<td >
																	<input type="hidden" id="hid_id" name="hid_id"  placeholder="" value="">
																	<input type="text"  class="tboxsmclass"  name="txt_desc" id="txt_desc" >Description
																</td>
															</tr><br><br>
															<tr>
																<td>
																	<input type="submit" id="btn_save" name="btn_save" value="Save"/>
																</td>
																<td>
																	<input type="reset" id="btn_delete" name="btn_delete" value="Delete">
																</td>
															</tr> -->





														<div class="div1 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel">Discipline</div>
															<div>
																<select class="tboxsmclass" name="cmb_discip" id="cmb_discip">
																	<option value="">--select--</option>
																	<?php $SelDiscip=""; echo $objBind->BindDisciplineSecAdv($SelDiscip); ?>
																</select>
															</div>
														</div>
														<div class="div3 pd-lr-1">
															<div class="lboxlabel">Description</div>
															<div>
																<input type="text"  class="tboxsmclass"  name="txt_desc" id="txt_desc" >
															</div>
														</div>
														<div class="div3 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div class="row" align="center">
																<input type="submit" id="btn_save" name="btn_save" class="btn btn-sm btn-info" value="Save"/>
																<input type="reset" id="btn_delete" name="btn_delete" class="btn btn-sm btn-info" value="Delete">
															</div>
															<!-- <div>	
																<input type="reset" id="btn_delete" name="btn_delete" value="Delete">
															</div> -->
														</div>
														<input type="hidden" id="hid_id" name="hid_id"  placeholder="" value="">
														<div class="row clearrow"></div>
														<!-- 
														<div class="div3 pd-lr-1" id="MO">
															<div class="lboxlabel-sm">Upto Month</div>
															<div>
																<select class="group selectlgbox" name="cmb_month" id="cmb_month">
																	<option value="1" selected="selected">January</option>
																	<option value="2">February</option>
																	<option value="3">March</option>
																	<option value="4">April</option>
																	<option value="5">May</option>
																	<option value="6">June</option>
																	<option value="7">July</option>
																	<option value="8">August</option>
																	<option value="9">September</option>
																	<option value="10">October</option>
																	<option value="11">November</option>
																	<option value="12">December</option>
																</select>
															</div>
														</div>

														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Rupees &#x20b9; In</div>
															<div>
																<select class="group selectlgbox" name="cmb_rupees" id="cmb_rupees">
																	<option value="L" selected="selected">Lakhs</option>
																	<option value="C">Crores</option>
																</select>
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<input type="button" name="btnView" id="btnView" class="btn btn-sm btn-info" value=" VIEW ">
															</div>
														</div> -->
														
												</div>
											</div>
										</div>
									</div>
									<div class="div2">&nbsp;</div>
								</div>
								
								<div class="row smclearrow"></div>
								<div class="box-container box-container-lg" align="center">
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Secured Advance Material Description - List <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="row">
																<?php 
																	if(($selectsql == true)&&(mysqli_num_rows($selectsql))) {
																?>  
																<table class="display dataTable mgtb-8" style="width:100%">
																	<thead>
																		<tr>
																			<th> </th>
																			<th class="lboxlabel">Discipline</th>
																			<th class="lboxlabel">Description</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php 
																		while($row = mysqli_fetch_array($selectsql)){     
																			$SelDiscipId = $row["discipline_id"];
																		?>       		
																		<tr>                                                                             
																			<td align="center">
																				<input type="checkbox" class="checkboxcla tboxsmclass" data-id="<?php echo $row["sa_id"]; ?>" id="<?php echo $row["sa_id"]; ?>" name="chkRead[]" value="<?php echo $row["sa_id"]; ?>" />
																			</td> 
																			<td>
																				<select class="tboxsmclass" name="cmb_discip_1" id="cmb_discip_1<?php echo $row["sa_id"]; ?>">
																					<option value="">--select--</option>
																					<?php  echo $objBind->BindDisciplineSecAdv($SelDiscipId); ?>
																				</select>
																				<!-- <input type="text" readonly="" name="cmb_discip_1[]" id="cmb_discip_1<?php //echo $row["sa_id"]; ?>" class="tboxsmclass " style="text-align:right;"  value="<?php //echo $row["discipline_id"]; ?>"> -->
																			</td>
																			<td>
																				<input type="text" readonly="" name="txt_desc_1[]" id="txt_desc_1<?php echo $row["sa_id"]; ?>" class="tboxsmclass" style="text-align:left;" tittle="<?php echo $row["mat_desc"]; ?>" value="<?php echo $row["mat_desc"]; ?>">
																			</td>
																		</tr> 
																	<?php   
																		}
																	}
																	?>                
																	</tbody>
																</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="div2">&nbsp;</div>
								</div>
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
<script src="js/CommonJSLibrary.js"></script>
<script type="text/javascript" language="javascript">
	$(function(){
		var msg = "<?php echo $msg; ?>";
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
							$(location).attr("href","SecAdvMaterialDesc.php");
						}
					}]
				});
			}
		};
	});
	$("body").on('click','#btn_delete',function(){
		$(this).parent().parent().remove();
		$("#cmb_discip").val('');
		$("#txt_desc").val('');
	});   
	//===============get val while slecting chckbox=======//
	$(document).on('click','.checkboxcla',function(){    
		var proId = $(this).attr('data-id');
		if($(this).is(':checked')){ 
			var cmb_discip_1 = $("#cmb_discip_1"+proId).val();  
			var txt_desc_1 = $("#txt_desc_1"+proId).val();   
			$("#cmb_discip").val(cmb_discip_1);
			$("#txt_desc").val(txt_desc_1);
			$("#hid_id").val(proId);
		}else{
			$("#cmb_discip").val('');
			$("#txt_desc").val('');
			$("#hid_id").val('');
		}
	});    
	//=============avoid slecting multiple chckbox=======//
	$(document).ready(function(){
		$('.checkboxcla').click(function() {
			$('.checkboxcla').not(this).prop('checked', false);
		});
	});

	$("body").on("click","#btn_save",function(event){      
        var cmb_discip=$("#cmb_discip").val();
        var txt_desc=$("#txt_desc").val();
        
        if(cmb_discip == 0){
			BootstrapDialog.alert("Discipline field should not be empty");
            return false;
        }else if(txt_desc == 0){
			BootstrapDialog.alert("Description field should not be empty");
            return false;        
        }
	});      
</script>

<style>
	.tboxsmclass{
		width:99%;
	}
	.chosen-container-single .chosen-single{
		padding: 3px 4px;
	}
	
	.modal{
		box-sizing:border-box;
		padding-right: 12px !important;
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
	.modal-header{
		padding: 6px;
	}
	.bootstrap-dialog .bootstrap-dialog-title{
		font-size: 13px;
	}
	.close{
		font-size: 16px;
	}
	th.tabtitle{
		text-align:left !important;
	}
</style>
