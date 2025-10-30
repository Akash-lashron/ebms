<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Periodical Rate Confirm';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
function dt_format($ddmmyyyy){
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	return $yy .'-'. $mm .'-'.$dd;
}
function dt_display($ddmmyyyy){
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}

/*if(isset($_POST['btn_save']) == " Confirm "){ 
	$POSTItemIdArr		= $_POST['txt_item_id'];
	$POSTItemRateArr	= $_POST['txt_item_rate'];
	$POSTWefDate 		= dt_format($_POST['txt_wef_date']);
	
	$SelectQuery1 		= "select distinct valid_from from item_master where valid_from != '0000-00-00' and par_id != 0";
	$SelectSql1 		= mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$List1 		= mysqli_fetch_object($SelectSql1);
			$ExistWefDate = $List1->valid_from;
		}
	}
	
	$SelectQuery2 		= "select distinct valid_from from item_master_temp where valid_from != '0000-00-00' and par_id != 0";
	$SelectSql2 		= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 		 = mysqli_fetch_object($SelectSql2);
			$CurrWefDate = $List2->valid_from;
			$ValidUptoDate = date('Y-m-d', strtotime('-1 day', strtotime($CurrWefDate)));
		}
	}
	
	$InsertQuery1 	= "insert into pru_master set with_effect_from = '$ExistWefDate', valid_upto = '$ValidUptoDate', confirmed_by = '$staffid', confirmed_on = NOW()";
	$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
	$MasterPruId 	= mysqli_insert_id($dbConn);
	include "PrevSORUpdate.php";
	
	$SelectQuery3 	= "select * from item_master where active = 1";
	$SelectSql3 	= mysqli_query($dbConn,$SelectQuery3);
	if($SelectSql3 == true){
		if(mysqli_num_rows($SelectSql3)>0){
			while($List3 = mysqli_fetch_object($SelectSql3)){
				$ItemDesc 	 	= mysqli_real_escape_string($dbConn,$List3->item_desc);
				$Description 	= mysqli_real_escape_string($dbConn,$List3->description);
				$InsertQuery3 	= "insert into pru_detail set pruid = '$MasterPruId', item_id = '$List3->item_id', item_id_1 = '$List3->item_id_1', item_code = '$List3->item_code', item_desc = '$ItemDesc', description = '$Description', par_id = '$List3->par_id', item_type = '$List3->item_type', unit = '$List3->unit', price = '$List3->price', valid_from = '$List3->valid_from', valid_to = '$List3->valid_to', active = 1";
				$InsertSql3 	= mysqli_query($dbConn,$InsertQuery3);
			}
		}
	}
	if(count($POSTItemIdArr)>0){
		foreach($POSTItemIdArr as $Key => $Value){
			$POSTItemRate 	= $POSTItemRateArr[$Key];
			$UpdateQuery 	= "update item_master set price = '$POSTItemRate', valid_from = '$POSTWefDate' where item_id = '$Value'";
			$UpdateSql 		= mysqli_query($dbConn,$UpdateQuery);
		}
		$UpdateQuery2 		= "update item_master set valid_from = '$POSTWefDate' where par_id = 0";
		$UpdateSql2 		= mysqli_query($dbConn,$UpdateQuery2);
		$DeleteQuery 		=  "TRUNCATE TABLE item_master_temp";
		$DeleteSql 			=  mysqli_query($dbConn,$DeleteQuery);
	}
}*/
$Confirm = 0; $PostForm = 0;
if(isset($_POST['btn_save']) == " Confirm "){ 
	$PostForm = 1;
}
$Error = "";
if($PostForm  == 1){ 
	$PuId = "";
	$SelectQuery1 = "select * from pu_master where is_confirmed != 'Y' and puid = (select max(a.puid) from pu_master a)";
	$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$List1 	= mysqli_fetch_object($SelectSql1);
			$PuId 	= $List1->puid;
		}
	}
	//echo $PuId;exit;
	if($PuId != ""){
		$SelectQuery2 	= "select * from item_master where active = 1";
		$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
		if($SelectSql2 == true){
			if(mysqli_num_rows($SelectSql2)>0){
				while($List2 = mysqli_fetch_object($SelectSql2)){
					$ItemDesc 	 	= mysqli_real_escape_string($dbConn,$List2->item_desc);
					$Description 	= mysqli_real_escape_string($dbConn,$List2->description);
					$InsertQuery1 	= "insert into pru_detail set puid = '$PuId', item_id = '$List2->item_id', item_id_1 = '$List2->item_id_1', item_code = '$List2->item_code', item_desc = '$ItemDesc', description = '$Description', par_id = '$List2->par_id', item_type = '$List2->item_type', unit = '$List2->unit', price = '$List2->price', valid_from = '$List2->valid_from', valid_to = '$List2->valid_to', active = 1";
					$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
				}
			}
		}
		
		$SelectQuery3 	= "select * from default_master";
		$SelectSql3 	= mysqli_query($dbConn,$SelectQuery3);
		if($SelectSql3 == true){
			if(mysqli_num_rows($SelectSql3)>0){
				while($List3 = mysqli_fetch_object($SelectSql3)){
					$TaxDesc 	 	= mysqli_real_escape_string($dbConn,$List3->de_name);
					$InsertQuery2 	= "insert into pdm_detail set puid = '$PuId', de_id = '$List3->de_id', de_name = '$TaxDesc', de_perc = '$List3->de_perc', de_code = '$List3->de_code', valid_from = '$List3->valid_from'";
					$InsertSql2 	= mysqli_query($dbConn,$InsertQuery2);
				}
			}
		}
		
		include "PrevSORUpdate.php";
		
		$SelectQuery4 	= "select * from item_master_temp where active = 1";
		$SelectSql4 	= mysqli_query($dbConn,$SelectQuery4);
		if($SelectSql4 == true){
			if(mysqli_num_rows($SelectSql4)>0){
				while($List4 = mysqli_fetch_object($SelectSql4)){
					$UpdateQuery1 	= "update item_master set price = '$List4->price', valid_from = '$List4->valid_from' where item_id = '$List4->item_id'";
					$UpdatetSql1 	= mysqli_query($dbConn,$UpdateQuery1);
				}
			}
		}
		
		$SelectQuery5 	= "select * from default_master_temp";
		$SelectSql5 	= mysqli_query($dbConn,$SelectQuery5);
		if($SelectSql5 == true){
			if(mysqli_num_rows($SelectSql5)>0){
				while($List5 = mysqli_fetch_object($SelectSql5)){
					$UpdateQuery2 	= "update default_master set de_perc = '$List5->de_perc', valid_from = '$List5->valid_from' where de_id = '$List5->de_id'";
					$UpdatetSql2 	= mysqli_query($dbConn,$UpdateQuery2);
				}
			}
		}
		
		$UpdateQuery3 	= "update pu_master set is_confirmed = 'Y' where puid = '$PuId'";
		$UpdateSql3 	= mysqli_query($dbConn,$UpdateQuery3);
		
		$DeleteQuery1	= "TRUNCATE TABLE item_master_temp";
		$DeleteSql1 	= mysqli_query($dbConn,$DeleteQuery1);
		
		$DeleteQuery2 	= "TRUNCATE TABLE default_master_temp";
		$DeleteSql2 	= mysqli_query($dbConn,$DeleteQuery2);
		if($UpdateQuery3 == true){
			$msg = "Item Rate and Taxes Overheads confirmed successfully";
			$Error = 0;
		}else{
			$msg = "Item Rate and Taxes Overheads not confirmed. Please try again";
			$Error = 1;
		}
	}
	$Confirm = 1;
}

//$SelectQuery = "select a.*, b.price as irate, b.valid_from as wef_date from item_master a left join item_master_temp b on (a.item_id = b.item_id) where a.par_id != 0 order by a.item_code asc"; 
/*$SelectQuery = "select * from item_master_temp where par_id != 0 order by item_code asc"; 
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$RowCount = 1;
	}
}*/
//$Error = 1;
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
								<div class="div12" align="center">&nbsp;</div>
								<div class="div12" align="center">&nbsp;</div>
							</div>
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="dropdown">
											<div class="instBox">
												<!----- This will be displayed when click on a submit button --->
												<div align="center" class="hide" id="BefProcessBox">
													<div>&nbsp;<br/>&nbsp;<br/>&nbsp;</div>
													<div style="color:#FC0450"> <br/>Processing, Please Wait...</div>
													<div class="rupee" align="center"><i class="fa fa-rupee"></i> </div>
												</div>
												<!----- This will be displayed after processing submit action --->
												<?php if($Error == '0'){ ?>
												<div align="center">
													<div style="color:#038646; font-size:15px; line-height:33px"> 
														<i class="fa fa-check-circle-o" style="font-size:36px; color:#03AE57"></i> 
														&nbsp;Success : Periodical Rate Confirmation Process Completed 
													</div>
												</div>
												<?php }else if($Error == 1){ ?>
												<div align="center">
													<div style="color:#FC0450; font-size:15px; line-height:33px"> 
														<i class="fa fa-times-circle-o" style="font-size:36px"></i> 
														&nbsp;Error : Periodical Rate Confirmation Process Not Completed
													</div>
												</div>
												<?php }else{ ?>
												<div id="InstructBox">
													<div align="center" style="padding:5px 15px 15px 15px;"><span class="InstHead">Instructions / Know Yourself</span></div>
													If you confirm that '<font style="color:#F0155E">The Item Rates and Taxes & Overheads</font>' will be updated in the original. Spontaneously Schedule of Rates also updated / modified.
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="div2">&nbsp;</div>
								</div>
								<div class="div12" align="center">
									<!--<a data-url="<?php if($Confirm == 1){ echo 'PeriodicalRateConfirmAccess'; }else{ echo 'PeriodicalRateConfirmPart2'; } ?>" class="btn btn-info">Back</a>-->
									<?php if($Confirm == 0){ ?>
									<a data-url="PeriodicalRateConfirmPart2" class="btn btn-info" id="btn_back">Back</a>
									<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Confirm ">
									<?php } ?>
								</div>
								<div class="div2" align="center">&nbsp;</div>
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
if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
$(document).ready(function(){
	
	var msg = "<?php echo $msg; ?>"; //BootstrapDialog.alert(msg);
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			//BootstrapDialog.alert(msg);
			BootstrapDialog.show({
				title: 'Information',
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						//dialog.setTitle('Title 1');
						$(location).attr('href','Home.php');
					}
				}]
			});
		}
	};
	var KillEvent = 0;
	$('body').on("click","#btn_save", function(event){ 
		if(KillEvent == 0){
			event.preventDefault();
			BootstrapDialog.confirm('Are you sure want to confirm ?', function(result){
				if(result) {
					event.preventDefault();
					BootstrapDialog.show({
						title: 'Authentication',
						message: "Click below '<span>OTP Generate</span>' button to generate One Time Password (OTP)",
						closable: false,
						buttons: [{
							label: '&nbsp; Cancel &nbsp;',
							action: function(dialog) {
								dialog.close();
							}
						}, {
							label: '&nbsp; OTP Generate &nbsp;',
							action: function(dialog) {
								$.ajax({ 
									type: 'POST', 
									url: 'ajax/OTPGenerate.php', 
									data: { Page: 'PRCA' }, 
									success: function (data) {   //alert(data);
										if(data != 0){
											dialog.close();
											BootstrapDialog.show({
												message: '<div style="padding:20px 10px 40px 10px"><span style="float:left;">Enter Your One Time Password '+data+' : &nbsp;</span> <span style="float:left; padding: 0px 5px;"><input type="text" class="form-control" style="width:150px; border:2px solid #171B20; border-radius:8px;"></span></div><div style="color:#E81645; font-size:11px; font-weight:bold; padding:2px 10px 10px 10px">* Please check your email for OTP. </div><div style="color:#E81645; font-size:11px; font-weight:bold; padding:2px 10px 40px 10px">** If you click the Cancel button you will be redirected to Home Page. </div>',
												closable: false,
												buttons: [{
														label: '&nbsp; Cancel &nbsp;',
														action: function(dialogRef) {
															//dialogRef.close();
															$(location).attr('href','PeriodicalRateConfirmAccess.php');
														}
													}, {
													label: '&nbsp; Next &nbsp;',
													action: function(dialogRef) {
														var otp = dialogRef.getModalBody().find('input').val();
														if($.trim(otp) !== $.trim(data)) {
															BootstrapDialog.alert('Invalid OTP. Please try again !');
															dialogRef.close();
															return false;
														}else{
															KillEvent = 1;
															$("#BefProcessBox").removeClass("hide");
															$("#btn_save").trigger( "click" );
															$("#btn_save").hide();
															$("#btn_back").hide();
															$("#InstructBox").hide();
															dialogRef.close();
														}
													}
												}]
											});
										}else{
											BootstrapDialog.alert('Sorry, OTP Not Generated please try again !');
										}
									}
								});
							}
						}]
					});
				}
			});
		}
	});
});
</script>
<style>
	.BtnBox{
		padding:10px 15px;
		background:#fff;
		color:#01389F;
		font-size:13px;
		width:95%;
		text-align:left;
		cursor:default;
	}
	.BtnBox:hover, .BtnBox:focus, .BtnBox:active{
		background:#fff !important;
	}
</style>
