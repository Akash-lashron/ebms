<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Bidders List';
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
$select_query = "SELECT name_contractor FROM contractor WHERE pan_no = '$ContPan'";

$select_sql = mysqli_query($dbConn,$select_query);
if($select_sql == true){
	while ($row = $select_sql->fetch_assoc()) {
		$output = $row['name_contractor'];
	}
}else{
	$output = null;
}
$RowSpanArr=array(); $BankDataArr=array();
//$ContDetQuery = "SELECT a.*,b.* FROM contractor a INNER JOIN contractor_bank_detail b ON (a.contid = b.contid) WHERE b.bk_dt_conf_status = 'AAO' ORDER BY a.contid ASC";
$ContDetQuery = "SELECT a.*,b.* FROM contractor a INNER JOIN contractor_bank_detail b ON (a.contid = b.contid) ORDER BY a.contid ASC";
//echo $ContDetQuery;exit;
$result = mysqli_query($dbConn,$ContDetQuery);
if($result == true){
	if(mysqli_num_rows($result)>0){
		$RowCount = 1;
		while($List = mysqli_fetch_object($result)){
			if(isset($RowSpanArr[$List->contid])){
				$RowSpanArr[$List->contid] = $RowSpanArr[$List->contid] + 1;
			}else{
				$RowSpanArr[$List->contid] = 1;
			}
			$BankDataArr[] = $List;
		}
	}
}
// ORDER BY type asc, group_id asc");
//$result_sql = mysqli_query($dbConn,$result); //mysqli_query($insert_query);


?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript">
</script>
<style>
	.smtd, th.smtd, td.smtd{
		width:10Px !important;
	}
	.AddNewBtn {
		padding: 0px 7px;
		top: 0px;
	}
</style>
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
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">&nbsp;Bidder/Contractor Details <button type="button" data-url="Bidders?pageid=1" class="AddNewBtn BtnHref" id="AddNewBtn" style="float:right"><i class="fa fa-plus" style="font-size:13px; padding-top:2px;"></i> Create New</button></div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<!--<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">-->
																	<table width="100%" align="center" class="dataTable table2excel">
																		<thead>
																			<tr>
																				<th rowspan='2' valign="middle">SNo.</th>
																				<th rowspan='2' valign="middle">Contractor Name</th>
																				<th class="smtd" rowspan='2' valign="middle">Contractor Address</th>
																				<th rowspan='2' valign="middle">GST No.</th>
																				<th rowspan='2' valign="middle">PAN No.</th>
																				<th colspan='3' valign="middle">Bank Details</th>
																				<th rowspan='2' valign="middle">Action</th>																				
																			</tr>
																			<tr>
																				<th valign="middle">Acc No.</th>
																				<th valign="middle" nowrap="nowrap">Bank Name</th>
																				<!--<th valign="middle" nowrap="nowrap">Branch Name</th>-->
																				<th valign="middle">IFSC Code</th>
																			</tr>
																		</thead>
																		<!-- <tbody>
																			<tr class='labeldisplay'>
																				<?php
																					//$SNO = 1;
																					//while($List = mysqli_fetch_object($result)){
																				?>
																				<td class='tdrowbold' valign='middle' align='center'><?php //echo $SNO; ?></td>
																				<td valign='middle' class='tdrow' align = 'justify'><?php //echo $List->name_contractor; ?></td>
																				<td valign='middle' class='tdrow' align = 'justify'><?php //echo $List->addr_contractor; ?></td>
																				<td class='tdrow' align='center' valign='middle'><?php //echo $List->gst_no; ?></td>
																				<td class='tdrow' align='center' valign='middle'><?php //echo $List->pan_no; ?></td>
																				<td class='tdrow' align='left' valign='middle'><?php //echo $List->bank_acc_no; ?></td>
																				<td class='tdrow' align='left' valign='middle'><?php //echo $List->bank_name; ?></td>
																				<td class='tdrow' align='left' valign='middle'><?php//echo $List->branch_address; ?></td>
																				<td class='tdrow' align='left' valign='middle'><?php //echo $List->ifsc_code; ?></td>
																				<td class='tdrow' align='left' valign='middle'>
																					<input type="button" data-url="Bidders?coneditid=<?php //echo $List->contid; ?>" class="BtnHref btn btn-info" Value="View Details">
																					</td>
																			</tr>
																			<?php  //$SNO++; } ?>
																		</tbody> -->
																		<tbody>
																			<?php $SNO = 1; $PrevContId="";
																			//$EMDCountArr = array_count_values(array_column($MasterResult, 'inst_type'));
																			if($RowCount == 1){ foreach($BankDataArr as $BDkey => $BDValue){ 
																				$ContRowSpan = $RowSpanArr[$BDValue->contid];
																				//$TrRowspan = array_sum($RowSpanArr1);
																				if($PrevContId != $BDValue->contid){
																					$y = 0;
																				}
																				if($y == 0){ 
																			?>
																			<tr class='labeldisplay'>
																				<td rowspan='<?php echo $ContRowSpan ?>' class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																				<td rowspan='<?php echo $ContRowSpan ?>' valign='middle' class='tdrow' align = 'justify'><?php echo $BDValue->name_contractor; ?></td>
																				<td rowspan='<?php echo $ContRowSpan ?>' valign='middle' class='tdrow smtd' align = 'justify'><?php echo $BDValue->addr_contractor; ?></td>
																				<td rowspan='<?php echo $ContRowSpan ?>' class='tdrow' align='center' valign='middle'><?php echo $BDValue->gst_no; ?></td>
																				<td rowspan='<?php echo $ContRowSpan ?>' class='tdrow' align='center' valign='middle'><?php echo $BDValue->pan_no; ?></td>
																				<td class='tdrow' align='center' valign='middle'><?php echo $BDValue->bank_acc_no; ?></td>
																				<td class='tdrow' align='left' valign='middle'><?php echo $BDValue->bank_name; ?></td>
																				<!--<td class='tdrow' align='left' valign='middle'><?php echo $BDValue->branch_address; ?></td>-->
																				<td class='tdrow' align='center' valign='middle'><?php echo $BDValue->ifsc_code; ?></td>
																				<td rowspan='<?php echo $ContRowSpan ?>' valign='middle' class='tdrow' align = 'center'>
																					<input type="button" data-url="Bidders?coneditid=<?php echo $BDValue->contid; ?>&pageid=1" class="BtnHref btn btn-info" Value="View & Edit">
																				</td>
																			</tr>
																			<?php 
																					$y++;  $SNO++;
																				}else{
																			?>
																			<tr class='labeldisplay'>
																				<td class='tdrow' align='center' valign='middle'><?php echo $BDValue->bank_acc_no; ?></td>
																				<td class='tdrow' align='left' valign='middle'><?php echo $BDValue->bank_name; ?></td>
																				<!--<td class='tdrow' align='center' valign='middle'><?php echo $BDValue->branch_address; ?></td>-->
																				<td class='tdrow' align='center' valign='middle'><?php echo $BDValue->ifsc_code; ?></td>
																			</tr>
																				<?php 
																					$y++;
																				}
																			?>
																			<?php $PrevContId = $BDValue->contid; } } ?>
																		</tbody>
																	</table>
																<!--</div>
															</div>-->
														</div>
													</div>
												</div>
												<div align="center"><br/>
													<a data-url="Home" class="btn btn-info" name="btn_save" id="btn_save">Back</a>
												</div>
												<div align="center">&nbsp;</div>
												<div align="center">&nbsp;</div>
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
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
$(document).ready(function(){ 
	$('.dataTable').DataTable({"paging":true,"ordering": true});
	$(window).load(function() {
		$("#DataTables_Table_0_wrapper").prepend('<button type="button" data-url="Bidders?pageid=1" class="AddNewBtn BtnHref" id="AddNewBtn" style=""><i class="fa fa-plus" style="font-size:13px; padding-top:2px;"></i> Create New</button>');
	});
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
					url = "ShortDescCreate.php";
					window.location.replace(url);
				 }
			});
		}
	};
</script>
<style>
table.dataTable > thead > tr > th{
	padding:2px !important;
	font-size:11px !important;
}

</style>
