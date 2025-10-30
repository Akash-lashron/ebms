<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
include "common.php";
$msg = '';
$staffid 		= $_SESSION['sid'];
$staffid_acc 	= $_SESSION['sid_acc'];
$userid 		= $_SESSION['userid'];
$acc_levelid 	= $_SESSION['levelid'];
$section 		= $_SESSION['staff_section'];
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
if(isset($_POST["btn_save"]) == " Save "){
	$tmp1 = 0; $tmp2 = 0;
	$RelList 		= $_POST['txt_rel_list'];
	$ExpRelList 	= explode(",",$RelList);
	for($i=0; $i<count($ExpRelList); $i++){
		$bgid = $ExpRelList[$i];
		$action = $_POST['cmb_action_'.$bgid];
		//echo $action; exit;
		if($action != ""){
			$tmp1++; 
			$UpdateQuery = "update bg_release set bg_status = '$action' where bgid  = '$bgid'";
			$UpdateSql = mysqli_query($dbConn,$UpdateQuery);
			if($UpdateSql == true){
				$tmp2++;
			}
		}
	}
	//if(($tmp1>0)&&($tmp2>0)){
		if($tmp1 == $tmp2){
			$msg = "PG Released Successfully ";
			$success = 1;
		}else{
			$msg = " PG Not Released ...!!! ";
		}
	//}
}

$PGRelCount = 0;
$SelectPGQuery = "select a.*, b.*, c.name_contractor from technical_sanction a inner join loi_pg b on (a.ts_id = b.ts_id) inner join contractor c on(b.contid = c.contid) where b.bg_status = 'L' 
				and b.bg_exp_date - INTERVAL 30 DAY <= CURDATE() and b.loa_pg_id = (select max(c.loa_pg_id) from loi_pg c where c.ts_id = b.ts_id)";
$SelectPGSql = mysqli_query($dbConn,$SelectPGQuery);
if($SelectPGSql == true){
	if(mysqli_num_rows($SelectPGSql)>0){
		$PGRelCount = mysqli_num_rows($SelectPGSql);
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
	function ViewMBook(obj)
	{
		var id = obj.id;
		//alert(id);
		$("#txt_post_id").val(id);
		$("#form_mbook").submit();
	}
</script>
<link rel="stylesheet" href="dashboard/css/verticalTab.css">
<script src="dashboard/js/verticalTab.js"></script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<style>
	.blink_me {
	  animation: blinker 1s linear infinite;
	}
	.btn1{
		width:70%;
	}
	@keyframes blinker {  
	  50% { opacity: 0; }
	}
	.accordionItem {
		height: auto;
		overflow: auto;
	}
</style>
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
	<?php include "MainMenu.php"; ?>
   		<div class="container_12">
       		<div class="grid_12">
            	<blockquote class="bq1" style="background-color:#FFFFFF; overflow:auto">
					<br/>
                	<form name="form" id="form_mbook" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<input type="hidden" name="txt_view" id="txt_view" value="<?php echo $_GET['view']; ?>">
						<?php $TabId = 1; if($PGRelCount > 0){ while($PGRList = mysqli_fetch_object($SelectPGSql)){ ?>
							<div class="accordion">
								<dl>
									<dt>
										<a href="#accordion<?php echo $PGRList->sheetid; ?>" id="sheet-<?php echo $PGRList->sheetid; ?>" aria-expanded="false" aria-controls="accordion<?php echo $TabId; ?>" class="accordion-title accordionTitle js-accordionTrigger blue-bg">
											&nbsp;
											<font style="color:#DF0979; font-weight:bold; background:#edeaea; border-radius:7px; padding:2px;">
											<?php echo $PGRList->ts_no; ?>
											</font>&nbsp;
											<font style="color:#DF0979; font-weight:bold; background:#edeaea; border-radius:7px; padding:2px;">
											Contractor Name : <?php echo $PGRList->name_contractor; ?>
											</font>
											&nbsp;&nbsp; 
											<?php echo " : "; ?> 
											<?php echo $PGRList->ts_work_name; ?>
											<font class="test" style="color:#F4003E; background:#edeaea; border-radius:5px; padding:2px; animation: blinker 1s linear infinite;"><i class="fa fa-hand-o-left blink_me" aria-hidden="true" style="padding-top:4px;"></i> Click Here</font>											 
										</a>
									</dt>
									<dd class="accordion-content accordionItem  is-collapsed" id="accordion<?php echo $PGRList->sheetid; ?>" aria-hidden="true">
										<div align="center">
											<?php //if($TabId == 1){ ?>	
												<table width="100%" class="table1" id="table1">
													<tr class="label" style="background-color:#EAEAEA">
														<td align="center">BG Type</td>
														<td align="center">Bank Name</td>
														<td align="center">BG/FDR Serial No</td>
														<td align="center">BG  Amount</td>
														<td align="center">BG/FDR Date</td>
														<td align="center">Expiry Date</td>
														<td align="center">Status</td>
														<td align="center">Extended Date</td>
														<td align="center">Claim Date</td>
														<td align="center">Action</td>
													</tr>
											<?php //} ?>
													<tr>
														<td align="center"><?php echo $PGRList->bg_type; ?></td>
														<td align="center"><?php echo $PGRList->bank_name; ?></td>
														<td align="center"><?php echo $PGRList->bg_serial_no; ?></td>
														<td align="center"><?php echo $PGRList->bg_amt; ?></td>
														<td align="center"><?php echo dt_display($PGRList->bg_date); ?></td>
														<td align="center"><?php echo dt_display($PGRList->bg_exp_date); ?></td>
														<td align="center">
														<?php 
															if($PGRList->bg_status == "L"){ 
																if($PGRList->bg_exp_date < date("Y-m-d")){
																	echo "Expired";
																}else{
																	echo "Active";
																}
																 
															}else if($PGRList->bg_status == "R"){ 
																echo "Released"; 
															}else{ 
															 " -- "; 
															}  
														?>
														</td>
														 <td align="center"><input type="text" placeholder="DD-MM-YYYY" class="textbox-new" name="txt_ext_date_pg" id="txt_ext_date_pg"></td>
													     <td align="center"><input type="text" placeholder="DD-MM-YYYY" class="textbox-new" name="txt_claim_date_pg" id="txt_claim_date_pg"></td>
														<td align="center">
															<select name="cmb_action_<?php echo $PGRList->bgid; ?>" id="<?php echo $PGRList->bgid; ?>" class="textbox-new action">
																<option value="">----Select----</option>
																<!--<option value="L">Active</option>-->
																<option value="R">Release</option>
															</select>
														</td>
													</tr>
											<?php //if($TabId == $PGRelCount){ ?>
												</table>
											<?php //} ?>
										</div>
									</dd>
								</dl>
							</div>
						<?php $TabId++; } } ?>
	 					<div style="text-align:center; height:45px; line-height:45px; color:#07DCED" class="printbutton">
							<div class="buttonsection">
								<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
							</div>
							<div class="buttonsection">
								<input type="submit" name="btn_save" id="btn_save" class="backbutton" value=" Save "/>
							</div>
						</div>
						<input type="hidden" name="txt_rel_list" id="txt_rel_list">
     				</form>
   			</blockquote>
  		</div>
  	</div>
</div>
<script>
  
	$(document).ready(function(){
		$(".action").change(function(event){
			var bgid = $(this).attr('id');
			var RelList = $('#txt_rel_list').val();
			if(RelList == ""){
				var dates=[];
					dates.push(bgid);
			}else{
				var dates=RelList.split(",");
				var a = dates.indexOf(bgid);
				if(a == -1){
					dates.push(bgid);
				}
			}
			var outStr = dates.toString();
			$('#txt_rel_list').val(outStr);
    	});
		var msg 		= "<?php echo $msg; ?>";
		var success 	= "<?php echo $success; ?>";
		var titletext 	= "";
		document.querySelector('#top').onload = function(){
			if(msg != ""){
				if(success == 1){
					swal("", msg, "success");
				}else{
					swal(msg, "", "");
				}
			}
		};
	});
	
</script>
<script>
   	$(function () {
		
		$( "#txt_claim_date_pg" ).datepicker({
        	changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            minDate: new Date,
            defaultDate: new Date,
        });
		$( "#txt_ext_date_pg" ).datepicker({
        	changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            minDate: new Date,
            defaultDate: new Date,
        });
		
        $.fn.validateshortname = function(event) { 
					if($("#cmb_shortname").val()==""){ 
					var a="Please Select Work Short Name";
					$('#val_shortname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_shortname').text(a);
					}
				}
		$.fn.validateworkname = function(event) { 
					if($("#txt_workname").val()==""){ 
					var a="Please Enter Work Name";
					$('#val_wname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_wname').text(a);
					}
				}
		
		
		
   
      });
	   /*function goBack()
	   {
	   		url = "MyView.php";
			window.location.replace(url);
	   }*/
</script>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
</body>
</html>
