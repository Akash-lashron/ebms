<?php
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
checkUser();
//require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$sheetid = $_SESSION['Sheetid'];
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$RowCount = 0;
$ViewTypeArr = array("'LCESS'","'SAL'"); $MopTypeArr = array('LCESS'=>'LABOUR CESS','SAL'=>'SALARY');
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
//$SelectWorkQuery = "SELECT a.*,a.rbn as mpac_rbn, b.* FROM memo_payment_accounts_edit a LEFT JOIN sheet b ON (a.sheetid = b.sheet_id) WHERE mop_type = 'MISC'";
$SelectWorkQuery = "SELECT a.*, a.rbn as mpac_rbn, b.*, c.mis_item_desc, d.contractor_title, d.name_contractor FROM memo_payment_accounts_edit a 
LEFT JOIN sheet b ON (a.sheetid = b.sheet_id) 
LEFT JOIN miscell_items c ON (c.mis_item_id = a.mis_item_id AND a.mis_item_id != 0)
LEFT JOIN contractor d ON (a.contid = d.contid) 
WHERE (a.mop_type = 'MISC' OR a.mop_type IN($ViewTypeArrStr))";
$SelectWorkSql 	 = mysqli_query($dbConn,$SelectWorkQuery);
if($SelectWorkSql == true){
	if(mysqli_num_rows($SelectWorkSql)>0){
		$RowCount = 1;
	}
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
 <style>
	.container{
		display:table;
		width:100%;
		border-collapse: collapse;
	}
	.table-row{  
		display:table-row;
		text-align: left;
	}
	.col{
		display:table-cell;
		border: 1px solid #CCC;
	}
</style>
<script type="text/javascript" language="javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <?php include "MainMenu.php"; ?>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Miscellaneous Memo of Payment Statements - List</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																	<table width="100%" border="0" align="center" class="table1 table2" id="dataTable">
																		<thead>
																			<tr class="note heading">
																				<th align="center" valign="middle">SNo.</th>
																				<th align="center" valign="middle">Ref No.</th>
																				<th align="center" valign="middle" nowrap="nowrap">CC No.</th>
																				<th align="center" valign="middle">Name of Work</th>
																				<th align="center" valign="middle">Bill No.</th>
																				<th align="center" valign="middle">Bill Date</th>
																				<th align="center" valign="middle">Name of Payee</th>
																				<th align="center" valign="middle" nowrap="nowrap">Bill Amount (&#8377;)</th>
																				<th align="center" valign="middle">Action</th>
																			</tr>
																		</thead>
																		<tbody>
																		<?php if($RowCount == 0){ ?>
																			<tr>
																				<td colspan="9"> No Records Found </td>
																			</tr>
																		<?php }else{ $sno = 1; while($List = mysqli_fetch_object($SelectWorkSql)){ 
																			
																			if(($List->sheetid == 0)||($List->sheetid == '')||($List->sheetid == NULL)){
																						if($List->mop_type == "SAL"){
																							$WorkDesc = $List->mis_item_desc." for the period of ".dt_display($List->lcess_fdate)." - ".dt_display($List->lcess_tdate);
																						}else if($List->mop_type == "LCESS"){
																							$WorkDesc = $List->mis_item_desc." for the period of ".dt_display($List->lcess_fdate)." - ".dt_display($List->lcess_tdate);
																						}else{
																							$WorkDesc = $List->mis_item_desc;
																						}
																						
																					}else{
																						$WorkDesc = $List->work_name;
																					}
																					if(($List->computer_code_no != '')&&($List->computer_code_no != NULL)){
																						if($List->mop_type == "RAB"){
																							$WorkDesc = $List->computer_code_no." - ".$WorkDesc;
																						}else{
																							$WorkDesc = $List->computer_code_no." - ".$List->mis_item_desc." - ".$WorkDesc;
																						}
																					}else{
																						$WorkDesc = $WorkDesc;
																					}
																		?>
																			<tr>
																				<td align="center" ><?php echo $sno; ?></td>
																				<td align="left"><?php echo $List->misc_ref_no; ?></td>
																				<td align="center"><?php echo $List->computer_code_no; ?></td>
																				<td align="left">
																				<?php 
																				echo $WorkDesc; 
																				?>
																				</td>
																				<td align="left"><?php echo $List->bill_no; ?></td>
																				<?php
																					if(($List->bill_dt != "")||($List->bill_dt != NULL)){
																						$BillDateDisp = dt_display($List->bill_dt);
																						if($BillDateDisp == "00/00/0000"){
																							$BillDateDisp = "";
																						}
																					}else{
																						$BillDateDisp = "";
																					}
																				?>
																				<td nowrap="nowrap"><?php echo $BillDateDisp; ?></td>
																				<td><?php echo $List->contractor_title." ".$List->name_contractor; ?></td>
																				<td align="right" nowrap="nowrap">
																					<?php 
																					if(($List->net_payable_amt != NULL)&&($List->net_payable_amt != "")){ 
																						echo IND_money_format($List->net_payable_amt);
																					}else{ 
																						echo ""; 
																					} 
																					$BtnName = " View & Print"; 
																					?>
																				</td>
																				<td align="center">
																					<a data-url="MopMemoForStatementsMisc?view=<?php echo $List->memoid; ?>" class="btn btn-info" name="btnView" id="btnView" style="margin-top:0px;"><?php echo $BtnName; ?></a>
																				</td>
																			</tr>
																			<?php $sno++; } } ?>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div align="center">&nbsp;</div>
									<div class="div12" align="center">&nbsp;</div>
								</div>
							</div>
					</blockquote>
				</div>
			</div>	
		</div>
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
</html>
<script>
	$(document).ready(function() {
		$('#dataTable').DataTable({
			responsive: true,
			paging: true, 
		});
	});
</script>
<style>
	.dataTables_wrapper{
		width:98% !important;
	}
</style>
