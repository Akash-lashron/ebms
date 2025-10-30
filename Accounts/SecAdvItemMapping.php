<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Secured Advance Item Mapping';
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
	$WnameCC = $_POST['cmb_cc'];
	if($WnameCC != ""){
		$ScheduleItnoArr = array();
		$MatDescArr = array();
		$EditSearchQuery = "SELECT * FROM sa_items_material_desc WHERE sheet_id='$WnameCC'";
		$EditSearchQuerySql	= mysqli_query($dbConn,$EditSearchQuery);
		//echo $EditSearchQuery;exit;
		if(($EditSearchQuerySql == true)&&(mysqli_num_rows($EditSearchQuerySql) > 0)){
			$SchTableQuery = "SELECT * FROM schdule WHERE sheet_id = '$WnameCC'";
			$SchTableQuerySql	= mysqli_query($dbConn,$SchTableQuery);
			if(($SchTableQuerySql == true)&&(mysqli_num_rows($SchTableQuerySql) > 0)){
				while($SchList = mysqli_fetch_object($SchTableQuerySql)){
					$ScheduleItnoArr[$SchList->subdiv_id] = $SchList->sno;
				}
			}
			$selectMaterialDesc    = "SELECT * FROM sa_mat_desc WHERE active=1";
			$selectMaterialDescsql = mysqli_query($dbConn,$selectMaterialDesc);
			if(($selectMaterialDescsql == true)&&(mysqli_num_rows($selectMaterialDescsql) > 0)){
				while($MatDesList = mysqli_fetch_object($selectMaterialDescsql)){
					$MatDescArr[$MatDesList->sa_id] = $MatDesList->mat_desc;
				}
			}
			//print_r($MatDescArr);exit;
		}
		

		$StaffQuery = "SELECT discipline_id FROM staff WHERE staffid='$staffid' AND active = 1";		//echo $StaffQuery;exit;
		$StaffQuerySql	= mysqli_query($dbConn,$StaffQuery);
		if(($StaffQuerySql == true)&&(mysqli_num_rows($StaffQuerySql)>0)){
			$StaffList = mysqli_fetch_object($StaffQuerySql);
			$discipline_id	= $StaffList->discipline_id;
		}	
	}
	if(($WnameCC == NULL)||($WnameCC == "")){
		$msg = "Please select Name of work..!!";
	}	
	//echo $WnameCC;exit;
}

if(isset($_POST['btn_save']) == " Save "){
	
	$CCWname = $_POST['cmb_cc'];
	$Desc = $_POST['cmb_desc'];
	$ITnumArr = $_POST['cmb_itemno'];
	if(($ITnumArr != NULL)&&($ITnumArr !='')){
		if(count($ITnumArr)>0){
			foreach($ITnumArr as $ArrKey => $ArrValue){
				$arr=implode(",",$ITnumArr);	
			}
		}
	}
	//print_r($Desc);exit;
	if($Desc != null){ 
		foreach($Desc as $key => $value){ 
			$Description = $Desc[$key];
			$Itemno = $ITnumArr[$key];
			$InsertQuery = "INSERT INTO sa_items_material_desc SET sheet_id='$CCWname',mat_desc_id ='$Description',items_nos='$Itemno'";
			echo $InsertQuery;exit;
			$InsertSql = mysqli_query($dbConn,$InsertQuery);
			/*if($InsertSql== true){
					$msg = "Details Successfully Saved..!!";}
				else{ echo "Details not saved";}
			*/
		}	
	}
}
if(isset($_POST['btn_update']) == " Update "){
	
	$CCWname = $_POST['cmb_cc'];
	$DescPrimaryId = $_POST['cmb_primary_id'];
	$DescId 		= $_POST['hid_cmb_desc_id'];
	$Desc 		= $_POST['cmb_desc'];
	$ITnumHidArr = $_POST['hid_items_nos_id'];
	$ITnumArr = $_POST['cmb_itemno'];
	//print_r($ITnumArr);exit;
	// if(($ITnumHidArr != NULL)&&($ITnumHidArr !='')){
	// 	if(count($ITnumHidArr)>0){
	// 		//foreach($ITnumArr as $ArrKey => $ArrValue){
	// 		$arr = implode(", ",$ITnumHidArr);	
	// 		//}
	// 	}
	// }
	if($DescPrimaryId != null){ 
		foreach($DescPrimaryId as $key => $value){ 
			$DescriptionId = $DescId[$key];
			$Description = $Desc[$key];
			$Itemno = $ITnumHidArr[$key];
			$Deletequery = "DELETE FROM sa_items_material_desc WHERE id='$value'";
			$DeletequerySql = mysqli_query($dbConn,$Deletequery);
			$InsertQuery = "INSERT INTO sa_items_material_desc SET sheet_id='$CCWname',mat_desc_id ='$DescriptionId',items_nos='$Itemno'";
			//echo $Deletequery;	echo $InsertQuery;exit;
			$InsertQuerySql = mysqli_query($dbConn,$InsertQuery);
			/*if($InsertSql== true){
					$msg = "Details Successfully Saved..!!";}
				else{ echo "Details not saved";}
			*/
		}	
	}
	if($InsertQuerySql == true){
		$msg = "Details Successfully Saved..!!";}
	else{ 
		$msg=  "Details not saved";
	}
}


?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>

<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "Home.php";
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
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1" style="overflow:auto">
					<div class="row">
					  <input type="hidden" name="max_group" id="max_group" value="1" />
						<div class="box-container box-container-lg">
						   <div class="div1">&nbsp;</div>
							<div class="div10">
								<div class="card cabox">
									<div class="face-static">
										<div class="card-header inkblue-card" align="center">&nbsp;Secured Advance Item Mapping</div>
										<div class="card-body padding-1 ChartCard" id="CourseChart">


											<div class="divrowbox pt-2">
												<div class="div6 pd-lr-1">
													<div class="lboxlabel">CCNo. & Work Name</div>
													<div>
														<select class="group select tboxsmclass" name="cmb_cc" id="cmb_cc">
															<option value="">---Select---</option>
															<?php echo $objBind->BindWorkOrderNo($WnameCC); ?>
															<?php	/*
															$selectquery  = "SELECT sheet_id,computer_code_no,work_name FROM sheet ";
															$selectsql      = mysqli_query($dbConn,$selectquery);           
															while($row = mysqli_fetch_array($selectsql)){		*/
															?>    
															<!-- <option value="<?php /*	echo $row["sheet_id"];?>"><?php echo $row["computer_code_no"]; echo " - " ;echo $row["work_name"]; */?></option>  -->
															<?php   
															//	}           
															?>  
														</select>
													</div>
												</div>
												<div class="div1 pd-lr-1">
													<div class="lboxlabel">&nbsp;</div>
													<div>
														<input type="submit" name="btn_go" id="btn_go" class="btn btn-sm btn-info" value=" GO">
													</div>
												</div>
											</div>


										</div>
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Secured Advance Item Mapping - List <?php echo $heading; ?> <span class="ralignbox"></div>
												<div class="card-body padding-1">
													<div class="row" id="table-stmt">		
														<table class="dynamicTable etable " align="center" width="100%" id="desctable">
															<tr class="lboxlabel" style="background-color:#FFF">
																<th>Material Description</th>
																<th>Items Nos. Assign For This Work</th>
																<th> </th>
															</tr>
															<tr>
																<td width="25%">
																	<select  name="cmb_desc_0" id="cmb_desc" class="tboxsmclass" >
																		<option value="">---Select---</option>
																		<?php
																		$selectMatDesc    = "SELECT * FROM sa_mat_desc WHERE active=1";
																		$selectMatDescsql      = mysqli_query($dbConn,$selectMatDesc);           
																		while($row = mysqli_fetch_array($selectMatDescsql)){
																			if(isset($discipline_id)) { 
																				if($row["discipline_id"] == $discipline_id)  {
																		?>    
																		<option value="<?php echo $row["sa_id"];?>"><?php echo $row["mat_desc"]; ?></option>
																		<?php   
																			}           
																		}           
																	}           
																		?>  
																	</select>
																</td>
																<td width="70%">
																	<select name="cmb_itemno_0[]" id="cmb_itemno" class="tboxsmclass itemnochosen" multiple="multiple">
																		<?php
																		$selectschdulequery    = "SELECT * FROM schdule ";
																		$selectschdulequerysql      = mysqli_query($dbConn,$selectschdulequery);           
																		while($row = mysqli_fetch_array($selectschdulequerysql)){
																			if(isset($WnameCC)) { 
																				if($row['sheet_id'] == $WnameCC)  {
																		?>    
																		<option value="<?php echo $row['subdiv_id'];?>"><?php echo $row['sno']; ?></option>
																		<?php   
																				}
																			}
																		} 
																		?>
																	</select>
																</td>
																<td width="5%" align="center" >
																	<input type="button" class="btn btn-sm btn-info" id="btn_add" name="btn_add" value=" ADD "/>
																</td>
															</tr>	

															<?php 
															if(isset($EditSearchQuerySql)){
																if(($EditSearchQuerySql == true)&&(mysqli_num_rows($EditSearchQuerySql) > 0)){
																	$UpdateButton = "ITSUPDATE";
																	//$ItNoDispArr=array();
																	while($SAItemList = mysqli_fetch_array($EditSearchQuerySql)){
																		$ItemsNosVal = $SAItemList['items_nos'];
																		//echo 1;
																		$ItemsNosValExp = explode(",",$ItemsNosVal);
																		foreach($ItemsNosValExp as $ItemsNo){																			
																			$ItNoDispArr[] = $ScheduleItnoArr[$ItemsNo];
																		}
																		$ItNoDisp = implode(" , ",$ItNoDispArr);
															?>
															<tr>
																<td width="25%">
																	<input type="text" readonly class="tboxsmclass" id="hid_cmb_desc_id" value="<?php echo $SAItemList['mat_desc_id']; ?>" name="hid_cmb_desc_id[]" >
																	<input type="text" readonly class="tboxsmclass" id="cmb_primary_id" value="<?php echo $SAItemList['id']; ?>" name="cmb_primary_id[]" >
																	<input type="text" readonly class="tboxsmclass" id="cmb_desc" value="<?php echo $MatDescArr[$SAItemList['mat_desc_id']]; ?>" name="cmb_desc[]" >
																</td>
																<td width="70%">
																	<input type="text" readonly class="tboxsmclass" id="hid_items_nos_id" value="<?php echo $SAItemList['items_nos']; ?>" name="hid_items_nos_id[]" >
																	<input type="text" class="tboxsmclass" readonly id="cmb_itemno" value="<?php echo $ItNoDisp; ?>" name="cmb_itemno[]" >
																</td>
																<td width="5%">
																	<input type="button" class="btn btn-sm btn-info delete" id="btn_delete" value="DELETE" name="btn_delete">
																</td>
															</tr>
															<?php }
																}
															}
															?>
														</table>
														<div class="row smclearrow"></div>
														<div class="div12" align="center">	
															<div class="row">
																<div class="div12" align="center">
																	<input type="button" onClick="goBack()" class="btn btn-info" name="back" id="back" value="Back">
																	<?php 
																	if((isset($UpdateButton))&&($UpdateButton == "ITSUPDATE")){
																	?>
																	<input type="submit" class="btn btn-info" data-type="submit" value=" Update " name="btn_update" id="btn_update"   />
																	<?php }else{ ?>
																	<input type="submit" class="btn btn-info" data-type="submit" value=" Save " name="btn_save" id="btn_save"   />
																	<?php } ?>
																</div>
															</div>
														</div>
														<div class="row clearrow"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
							 	</div>
							</div>
							<div class="div1">&nbsp;</div>
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
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
	$("#cmb_cc").chosen();
	$(".itemnochosen").chosen();

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.show({
			message: msg,
			buttons: [{
				label: ' OK ',
				action: function(dialog) {
					dialog.close();
					window.location.replace('SecAdvItemMapping.php');
				}
			}]
		});
	}
	/*
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
	*/
	$(document).ready(function(){ 
		$("body").on("click","#btn_go", function(event){
			var WnameCC		= $("#cmb_cc").val();
			if(WnameCC == ""){
				BootstrapDialog.alert("Please select Name of work..!!");
				event.preventDefault();
				event.returnValue = false;
			}
		});

		$("body").on("click","#btn_save",function(event){   //alert(1);    
			var Desc=$("#cmb_desc").val();
			var ItemNo=$("#cmb_itemno").val();
			
			// if(Desc==0){
			// alert("Description field should not be empty");
			//     return false;
			// }else if(ItemNo==0){
			//     alert("Item No. field should not be empty");
			//     return false;        
			// }
		});    

		$("body").on("click","#btn_add",function(event){    
			var Desc=$("#cmb_desc option:selected").text();
			var ItemNo=$("#cmb_itemno option:selected").toArray().map(item => item.text).join();
			var rowstr='<tr><td width="25%"><input type="text" readonly class="tboxsmclass" id="cmb_desc" value="'+Desc+'" name="cmb_desc[]" ></td>';
				rowstr+='<td width="70%"><input type="text" class="tboxsmclass" readonly id="cmb_itemno" value="'+ItemNo+'" name="cmb_itemno[]" ></td>';
				rowstr+='<td width="5%"><input type="button" class="btn btn-sm btn-info delete" id="btn_delete" value="DELETE" name="btn_delete"></td></tr>';

			// if(Desc==0){
			// alert("Description field should not be empty");
			//     return false;
			// }else if(ItemNo==0){
			//     alert("ItemNo field should not be empty");
			//     return false;        
			// }else{
			$("#desctable").append(rowstr);
			$("#cmb_desc").val(''); 
			$("#cmb_itemno").val('');
			// }
		});
		$("body").on("click", ".delete", function(){
			$(this).closest("tr").remove();
			//TotalUnitAmountCalc();
			$("#text_totalamt").val('');
		}); 
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

