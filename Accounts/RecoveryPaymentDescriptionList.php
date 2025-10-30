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
$HoaArr = array();
$ContNameArr = array();
$RowCnt = 0;
$MopSdRecQuery = "SELECT * FROM pay_rec_master WHERE active = 1 ORDER BY pr_type ASC";
$MopSdRecQuerySql = mysqli_query($dbConn,$MopSdRecQuery);
if($MopSdRecQuerySql == true){
	if(mysqli_num_rows($MopSdRecQuerySql) > 0){
		$RowCnt = 1;
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
												<div class="card-header inkblue-card" align="center">Payment / Recovery Description - View</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
								                                  <thead>
																	<tr>
																		<th valign="middle">SNo.</th>
																		<th valign="middle">Code</th>
																		<th valign="middle">Description</th>
																		<th valign="middle">Payment / Recovery</th>
																		<th valign="middle">Recovery Type</th>
																		<th valign="middle">Action</th>
																	</tr>
																</thead>
																<tbody>
																	
																	<?php
																		$SNO = 1;
																		if($RowCnt == 1){
																			while($List = mysqli_fetch_object($MopSdRecQuerySql)){
																				?>
																				<tr class='labeldisplay'>
																					<td class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																					<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->prcode; ?></td>
																					<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->pr_desc; ?></td>
																					<td valign='middle' class='tdrow' align = 'justify'><?php if($List->pr_type == "P"){ echo "PAYMENT"; }else if($List->pr_type == "R"){ echo "RECOVERY"; } ?></td>
																					<td valign='middle' class='tdrow' align = 'justify'><?php if($List->rec_type == "A"){ echo "PART A"; }else if($List->rec_type == "B"){ echo "PART B"; } ?></td>
																					<td valign='middle' class='tdrow' align='center'>
																						<a data-url="NewRecoveryPaymentDescription?id=<?php echo $List->prid; ?>" class=" BtnHref btn btn-info" name="btn_print" id="btn_print" style="margin-top:0px;">
																							View & Edit
																						</a>
																					</td>
																				</tr>
																				<?php 
																				$SNO++;
																			}
																		}else{ 
																	?>
																	<tr class='labeldisplay'>
																		<td colspan='5' class='tdrowbold' valign='middle' align='center'> No Records Found </td>
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
															<a data-url="MemoOfPaymentSalary" class="btn btn-info" name="view" id="view">Back</a>
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
						data: { Page: 'LCESS', MopId: MopId }, 
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
											window.location.href = 'MopSalaryList.php';
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