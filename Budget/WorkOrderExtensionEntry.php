<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Work Extension';
checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];

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

if(isset($_GET["id"])) {
	$sheet_id = $_GET['id'];
	$select_sheet_query = "select * from sheet WHERE sheet_id='$sheet_id' AND (active = 1 OR active = 2 )";
	$select_sheet_sql 		= mysqli_query($dbConn,$select_sheet_query);
	if($select_sheet_sql == true){
	   if(mysqli_num_rows($select_sheet_sql)>0){
		  $count=1;
			$List 				= mysqli_fetch_object($select_sheet_sql);
			$work_name		    = $List->short_name;
			$work_order_no 		= $List->work_order_no;
			$work_comp_date 	= dt_display($List->date_of_completion);
			$work_oldextn_date 	= dt_display($List->work_ext_date);
	   }
	}	  
	$extCount = 0;
	$select_wo_ex_query = "select * from work_orders_ext where sheetid = '$sheet_id'";
	$select_wo_ex_sql = mysqli_query($dbConn,$select_wo_ex_query);
	if($select_wo_ex_sql == true){
		if(mysqli_num_rows($select_wo_ex_sql)>0){
			$extCount = 1;
		}
	}



 }
if(isset($_POST['btn_save']) == ' Save '){
	//$project_id = mysql_insert_id($insert_query);
	//$cmb_work_name		= $_POST["cmb_work_name"];
	$cmb_shortname		    = $_POST["txt_sheet_id"];
	$txt_work_orderNum		= $_POST["txt_order_num"];
	$txt_wo_extn		    =  dt_format($_POST["txt_work_extn_date"]);


	// if($cmb_work_name == NULL){
	// 	$msg = "Please Select Project Title..!!";
	// }
	
	if($cmb_shortname == NULL){
		$msg = "Please Enter Work Name..!!";
	}else if($txt_wo_extn == NULL){
		$msg = "Please Select Work Extension Date..!!";
	}else{
		$InQueryCon = 1;
	}


	if($InQueryCon == 1){

		$GlobID= '';
		$GlobIdQuery = "SELECT globid FROM sheet where sheet_id = '$cmb_shortname'";
		$GlobIdSql 	= mysqli_query($dbConn,$GlobIdQuery);
		if($GlobIdSql == true){
			if(mysqli_num_rows($GlobIdSql)>0){
				$List = mysqli_fetch_object($GlobIdSql);
				$GlobID = $List->globid;
			}
		}	//echo $GlobID;exit;

		//	est_id='$cmb_work_name',

	//if ($GlobID != Null){ //echo 1; exit;
				$update_query	= "UPDATE works SET work_ext_date='$txt_wo_extn'  WHERE sheetid  = '$cmb_shortname' AND  globid = '$GlobID'";
				$update_query_sql = mysqli_query($dbConn,$update_query);
				$update_query1	= "UPDATE sheet  SET  work_ext_date='$txt_wo_extn' WHERE sheet_id  = '$cmb_shortname' AND  globid = '$GlobID'";
				$update_query_sql = mysqli_query($dbConn,$update_query1);
				$insert_query	= "insert into work_orders_ext set  globid = '$GlobID', sheetid  = '$cmb_shortname', work_ext_date='$txt_wo_extn', userid='$UserId', createddate = NOW()";
				$insert_sql = mysqli_query($dbConn,$insert_query);
				if($insert_sql == true){
					$msg = "Work Extension Date Updated Successfully..!!";
					}else{
						$msg = "Error: Work Extension Date Not Updated..!!";
					}
	
	//echo $insert_sql;exit;
 }
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">

<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
window.history.forward();
	function noBack() { window.history.forward(); }
	function ViewBidder(){
		url = "home.php";
		window.location.replace(url);
	}
	function goBack(){
			url = "WorkStatusList.php";
			window.location.replace(url);
		}
</script>	
<style>
	.head-b {
		background: #136BCA;
		border-color: #136BCA;
	}
	/* .lboxlabel {
  color: #04498E;
  text-align: left;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 11px;
  font-weight: bold;
} */
	.dataFont {
		font-weight: bold;
		color: #001BC6;
		font-size: 12px;
		text-align: left;
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="" method="post" enctype="multipart/form-data" name="form" id="form1">
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
								 <div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
									           <div class="card-header inkblue-card" align="center">Work Extension Entry</div>
										       <div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																	<div class="row clearrow"></div>
																<div class="row">
																<div class="div3 dataFont">
																		Work Short Name
																</div>
																<div class="div7">
																<textarea name="txt_work_name" id="txt_work_name" class="tboxclass" required readonly="" disabled="disabled"><?php if($_GET['id'] != ""){ echo $work_name; } ?></textarea>
																</div>
															</div>
															<div class="row clearrow"></div>
															<div class="row">
												           <div class="div3 dataFont">
													          Work Order No.
												           </div>
												           <div class="div7">
													         <input type="text" name='txt_order_num' id='txt_order_num' class="tboxclass" readonly=""  value="<?php if($_GET['id'] != ""){ echo $work_order_no; } ?>">
												             </div>
											              </div>
														  <div class="row clearrow"></div>
														  <div class="row">
											                 <div class="div3 dataFont">Work Completion Date</div>
												                <div class="div3" align="left">
													               <input type="text" name="txt_work_comple_date" id="txt_work_comple_date" readonly class="tboxclass extdate"  value="<?php if($_GET['id'] != ""){ echo $work_comp_date; } ?>">
																   <input type="hidden" name="txt_work_oldext_date" id="txt_work_oldext_date" readonly class="tboxclass extdate" value="<?php if($_GET['id'] != "") {echo($work_oldextn_date);} ?>" required >
												                 </div>	
                                                              </div>
                                                           </div>	
														   <div class="row clearrow"></div>
														   <?php if($extCount == 1){ $slno = 1; while($ExtList = mysqli_fetch_object($select_wo_ex_sql)){ ?>
														   <div class="row">
											                 <div class="div3 dataFont">Work Extension Date - <?php echo $slno; ?></div>
												                <div class="div3" align="left">
																<input type="text" name="txt_work_comple_date" id="txt_work_comple_date" readonly class="tboxclass extdate" value="<?php echo dt_display($ExtList->work_ext_date); ?>" required >
												                 </div>	
                                                              </div>
															  <div class="row clearrow"></div>
															  <?php $slno++; } } ?>
                                                           </div>	
														   <div class="row clearrow"></div>
														   <div class="row">
											                 <div class="div3 dataFont">Work Extension Date </div>
												                <div class="div3" align="left">
													               <input type="text" name="txt_work_extn_date" id="txt_work_extn_date"  placeholder="DD/MM/YYYY" class="tboxclass expdate" >
												                 </div>	
                                                              </div>
                                                           </div>	
															  <div class="row clearrow"></div>
																	<div class="div12" align="center">
																	   <input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
																	   <input type="hidden" name="txt_sheet_id" id="txt_sheet_id" value="<?php if($_GET['id'] != ""){ echo $sheet_id; } ?>" required>
																		<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" data-type="submit" value=" Submit "/>
																		<!-- <input type="button" class="btn btn-info" name="btn_view" id="btn_view" value="View" onClick="ViewTSList();"/> -->
																		<input type="hidden" name='hid_status' id='hid_status' class="tboxsmclass">
																	</div>	
																	<div class="row clearrow"></div>											
																 </div>
																</div>
															</div>
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
<script type="text/javascript" language="javascript">
	$("#cmb_hoa").chosen();



	$( ".date" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		yearRange: "2000:+15",
		maxDate: new Date,
		defaultDate: new Date,
	});
	$( ".expdate" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		yearRange: "2000:+25",
		defaultDate: new Date,
	});


	$('body').on("change","#cmb_shortname", function(e){ 
		var Id = $(this).val(); 
		$("#txt_order_num").val('');
        $("#txt_work_comple_date").val('');
		$("#txt_work_extn_date").val('');
		$.ajax({ 
			type: 'POST', 
			url: 'FindWorkOrderDetails.php', 
			data: { Id: Id}, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					$("#txt_order_num").val(data.work_order_no);
					$("#txt_work_comple_date").val(data.date_of_completion);
				}
			}
		});
	});
</script>
<script>
$(document).ready(function(){
	$("#cmb_work_name").chosen();
	$("#cmb_approve_auth").chosen();
	$("#btn_view").click(function(event){ 
		var WorkName 		= $("#cmb_work_name").val(); 
		if(WorkName == ""){ 
			BootstrapDialog.alert("Please Select Name of Work.");
			event.preventDefault();
			event.returnValue = false;
		}
	});
});

var KillEvent = 0;
$("body").on("click","#btn_save", function(event){
	if(KillEvent == 0){
		var CheckVal = 0;
		var CheckExtndate = 0;
		var TsWorkNameVal   = $("#cmb_shortname").val();
		var Completiondat	= $("#txt_work_comple_date").val(); 
		var TsExtensiond  	= $("#txt_work_extn_date").val();
		var OldExtension  	= $("#txt_work_oldext_date").val();
		if((Completiondat != "") && (TsExtensiond != "") ){  
			var d1 = TsExtensiond.split("/");
			var d2 = Completiondat.split("/");
			var extendate = new Date(d1[2], d1[1]-1, d1[0]); //alert(emdexpdate);
			var completedate = new Date(d2[2], d2[1]-1, d2[0]); //alert(emddate);
			if(extendate<completedate){ 
				event.preventDefault();
				event.returnValue = false;
				CheckVal = 1;
			}else{
				var a="";
				CheckVal = 0;
			}
		}
		if((OldExtension != "") && (TsExtensiond != "") ){  
			var d1 = TsExtensiond.split("/");
			var d2 = OldExtension.split("/");
			var extendate = new Date(d1[2], d1[1]-1, d1[0]); //alert(emdexpdate);
			var olddate = new Date(d2[2], d2[1]-1, d2[0]); //alert(emddate);
			if(extendate<olddate){ 
				event.preventDefault();
				event.returnValue = false;
				CheckExtndate = 1;
			}else{
				var a="";
				CheckExtndate = 0;
			}
		}
		
		if(TsWorkNameVal == ""){
			BootstrapDialog.alert("Please Select Work Name..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(TsExtensiond == ""){
			BootstrapDialog.alert("Please Select Extension date..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(CheckVal ==  1){
			BootstrapDialog.alert("Work Extension date is lesser than Work Completion Date..Please Change..!!");
			return false;
		}else if(CheckExtndate ==  1){
			BootstrapDialog.alert("Work Extension date is lesser than Work Completion Date..Please Change..!!");
			return false;
		}else{
			event.preventDefault();
			BootstrapDialog.confirm({
				title: 'Confirmation Message',
				message: 'Are you sure want to save Work Extension Date ?',
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


</script>
<script>
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
</script>