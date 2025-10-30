<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";

checkUser();
$success = 0;
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'/'. $mm .'/'.$yy;
}                       
//$result = mysqli_query($dbConn," SELECT a.*,b.* FROM technical_sanction a INNER JOIN hoa b ON a.hoaid = b.hoa_id  ORDER BY a.ts_id ASC");
$ViewTypeArr = array(); $MopTypeArr = array();
$SelectQuery = "SELECT * FROM miscell_items WHERE active = 1 and misc_module = 'CCNO' ORDER BY mis_item_desc ASC";
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true ){
	while($List = mysqli_fetch_array($SelectSql)){
		array_push($ViewTypeArr,"'".$List['mop_type']."'");
		$MopType = $List['mop_type'];
		$MopTypeArr[$MopType] = $List['mis_item_desc'];
	}            
}
if(count($ViewTypeArr)>0){
	$ViewTypeArrStr = implode(",",$ViewTypeArr);
}else{
	$ViewTypeArrStr = "";	
}
$HoaArr = array();
$ContNameArr = array();
$CCnoArr = array();
$TrueValue = 0;
$MopSdRecQuery = "SELECT * FROM memo_payment_accounts_edit WHERE mop_type IN($ViewTypeArrStr) ORDER BY memoid DESC";
$MopSdRecQuerySql = mysqli_query($dbConn,$MopSdRecQuery);
if($MopSdRecQuerySql == true){
	if(mysqli_num_rows($MopSdRecQuerySql) > 0){
		$TrueValue = 1;
		$ContMastQuery = "SELECT * FROM contractor where active = 1";
		$ContMastQuerySql = mysqli_query($dbConn,$ContMastQuery);
		if($ContMastQuerySql == true){
			if(mysqli_num_rows($ContMastQuerySql) > 0){
				while($ListCont = mysqli_fetch_object($ContMastQuerySql)){
					$ContNameArr[$ListCont->contid] = $ListCont->name_contractor;
				}
			}
		}
		$SheetMastQuery = "SELECT sheet_id,computer_code_no FROM sheet where active = 1";
		$SheetMastQuerySql = mysqli_query($dbConn,$SheetMastQuery);
		if($SheetMastQuerySql == true){
			if(mysqli_num_rows($SheetMastQuerySql) > 0){
				while($ListSheet = mysqli_fetch_object($SheetMastQuerySql)){
					$CCnoArr[$ListSheet->sheet_id] = $ListSheet->computer_code_no;
				}
			}
		}
	}
}
//print_r($CCnoArr);exit;
$Hoaresult = mysqli_query($dbConn," SELECT hoamast_id,new_hoa_no FROM hoa_master ORDER BY hoamast_id ASC");
if($Hoaresult == true){
	if(mysqli_num_rows($Hoaresult) > 0){
		//foreach($Hoaresult as $key => $value){
		while($ListHoa = mysqli_fetch_object($Hoaresult)){
			$HoaArr[$ListHoa->hoamast_id] = $ListHoa->new_hoa_no;
		}
	}
}
//print_r($HoaArr);
// ORDER BY type asc, group_id asc");
//$result_sql = mysqli_query($dbConn,$result); //mysqli_query($insert_query);


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
						<blockquote class="bq1 stable" style="overflow:auto">
						<div class="row">
								<div class="box-container box-container-lg" align="center">
									<!-- <div class="div1">&nbsp;</div> -->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Memo of Payment for SD Release / EPF / GST Reimbursement  - View</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																		<tr>
																			<th valign="middle">SNo.</th>
																			<th valign="middle">Memo of Payment for</th>
																			<th valign="middle">CCNo.</th>
																			<th valign="middle">Name Of Payee</th>
																			<th valign="middle">Head Of Account</th>
																			<!--<th valign="middle">Bill Amount ( &#8377; )</th>-->
																			<!--<th valign="middle">Bill No. & Bill Date</th>-->
																			<th valign="middle">Payment Amount ( &#8377; )</th>
																			<th valign="middle">Action</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr class='labeldisplay'>
																			<?php
																				$SNO = 1;
																				if($TrueValue == 1){
																					while($List = mysqli_fetch_object($MopSdRecQuerySql)){
																					
																					$HoaNameDisp = array();
																					$ArrVal = explode(",",$List -> hoaid);
																					//print_r($ArrVal);
																					//echo $List -> hoaid;
																					foreach($ArrVal as $key => $value){
																						//echo $value;
																						$HoaName = $HoaArr[$value];
																						array_push($HoaNameDisp,$HoaName);
																					}
																					//print_r($HoaNameDisp);
																					$HoaNameDispay = implode(",",$HoaNameDisp);
																					
																			?>
																			<td class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																			<td class='tdrowbold' valign='middle' align='center'><?php echo $MopTypeArr[$List->mop_type]; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $CCnoArr[$List->sheetid]; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $ContNameArr[$List->contid]; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $HoaNameDispay; ?></td>
																			<!--<td class='tdrow' align='right' valign='justify'><?php echo IndianMoneyFormat($List->net_payable_amt); ?></td>-->
																			<!--<td class='tdrow' align='left' valign='middle'>
																			<?php 
																			/*$BillNoStr = "";
																			if($List->bill_no != ''){
																				$BillNoStr = $List->bill_no;
																			}
																			if(($List->bill_dt != '0000-00-00')&&($List->bill_dt != '')&&($List->bill_dt != NULL)){
																				if($BillNoStr != ''){
																					$BillNoStr = "  &  ".dt_display($List->bill_dt);
																				}else{
																					$BillNoStr = dt_display($List->bill_dt);
																				}
																			}*/
																			?>
																			</td>-->
																			<td class='tdrow' align='right' valign='justify'><?php echo IndianMoneyFormat($List->net_payable_amt); ?></td>
																			<td valign='middle' class='tdrow' align='center'>
																				<!--<a data-url="#" class="BtnHref btn btn-info" name="btn_edit" id="btn_edit" style="margin-top:0px;">
																					Edit
																				</a>-->
																				<!--<a data-url="MopSDRPrint?id=<?php echo $List->memoid; ?>" class="BtnHref btn btn-info" name="btn_print" id="btn_print" style="margin-top:0px;">
																					View & Print
																				</a>-->
																				<input type="button" name="btnDelete" id="btnDelete" class="btn btn-info" data-id="<?php echo $List->memoid; ?>" value="DELETE">
																				<?php /*if(($List->vr_no == "")&&(($List->vr_dt == "0000-00-00")||($List->vr_dt == NULL))){ ?>
																				<i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i>
																				<?php } else{ ?>
																				<i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i>
																				<?php }*/ ?>
																			</td>
																		</tr>
																		<?php 
																				$SNO++; 
																				} 
																			}else{ 
																		?>
																		<tr class='labeldisplay'>
																			<td colspan='8' class='tdrowbold' valign='middle' align='center'> No Records Found </td>
																		</tr>
																		<?php 
																			} 
																		?>
																	</tbody>
																</table>
															</div>
														</div>
														<div align="center">
															<!-- <input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" /> -->
															<a data-url="MemoOfPaymentSDRelease" class="btn btn-info" name="view" id="view">Back</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="div1">&nbsp;</div> -->
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
	
	var KillEvent = 0;
	$('body').on("click","#btnDelete", function(event){ 
		var MopId = $(this).attr("data-id");
		BootstrapDialog.confirm({
			title: 'Confirmation Message',
			message: 'Are you sure want to Delete ?',
			closable: false, // <-- Default value is false
			draggable: false, // <-- Default value is false
			btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
			btnOKLabel: 'Ok', // <-- Default value is 'OK',
			callback: function(result) {
				// result will be true if button was click, while it will be false if users close the dialog directly.
				if(result){
					
					$.ajax({ 
						type: 'POST', 
						url: 'ajax/DeleteMop.php', 
						data: { Page: 'SDR', MopId: MopId }, 
						dataType: 'json',
						success: function (data) {  // alert(data['computer_code_no']);
							if(data != null){
								var Msg = data['msg'];
								BootstrapDialog.show({
									title: 'Alert Information',
									message: Msg,
									buttons: [{
										label: 'OK',
										cssClass: 'btn btn-info',
										action: function(dialog) {
											window.location.href = 'MopSDRList.php';
										}
									}]
								});
							}
						}
					});
				}
			}
		});
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
					url = "ShortDescCreate.php";
					window.location.replace(url);
				 }
			});
		}
	};
</script>