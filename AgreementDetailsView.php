<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$sheetid = $_SESSION['Sheetid'];
function dt_display($ddmmyyyy)
{
	 $dt	=	explode('-',$ddmmyyyy);
	 $dd	=	$dt[2];
	 $mm	=	$dt[1];
	 $yy	=	$dt[0];
	 return $dd . '/' . $mm . '/' . $yy;
}
function check_workorder_measurements($sheetid){
	$CheckMeasQuery = "select mbheaderid from mbookheader where sheetid = '$sheetid' LIMIT 1";
	$CheckMeasSql = mysql_query($CheckMeasQuery);
	if(mysql_num_rows($CheckMeasSql)>0){
		return 1;
	}else{
		return 0;
	}
}
$schdulesql ="SELECT * FROM sheet WHERE active = 1 ";
$schdulequery=mysql_query($schdulesql);
$RowCount =0;
if(isset($_POST['submit'])){
	header('Location: ViewAgreementSheet.php');
}
?>
<?php require_once "Header.html"; ?>
<style>
	.container{
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
   	function goBack(){
	  	url = "sheet.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">View Agreement Sheet</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow:auto">
							<div class="container">
								
								<div style="height:5px"></div>
								<table class="table-bordered table1" align="center" id="dataTable">
									<thead>
										<tr>
											<th>SNo</th>
											<th>Work Order No.</th>
											<th>Name of Work</th>
											<th>Technical Sanction No.</th>
											<th>Contractor Name</th>
											<th>Agreement No.</th>
											<th>C.C.No.</th>
											<th>W.O. Date</th>
										</tr>
									</thead>
									<tbody>
                             	<?php
							   	$RowCount = mysql_num_rows($schdulequery);
							   	if($RowCount > 0){
							   		$sno = 1;
								   	while($List = mysql_fetch_object($schdulequery)){
								   		$assigned_staff = $List->assigned_staff;
										$AssignStaff = explode(",",$assigned_staff);
										if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1)){
								?>
										<tr>
											<td align="center"><?php echo $sno; ?></td>
											<td>
											<?php $check = check_workorder_measurements($List->sheet_id);
											if($check == 0){ ?>
												<a href="AgreementDetailsEdit.php?sheet_id=<?php echo $List->sheet_id; ?>"><u><?php echo $List->work_order_no; ?></u> </a>
											<?php }else{ ?>
												<a class="tooltipwarning" title="Already Measurements Entered for this work order. Unable to Edit."><?php echo $List->work_order_no; ?></a>
											<?php } ?>
											</td>
											<td><?php echo $List->work_name; ?></td>
											<td><?php echo $List->tech_sanction; ?></td>
											<td><?php echo $List->name_contractor; ?></td>
											<td><?php echo $List->agree_no; ?></td>
											<td><?php echo $List->computer_code_no; ?></td>
											<td><?php echo dt_display($List->work_order_date); ?></td>
										</tr>
							   	<?php $sno++; } } }else{ ?>
										<tr>
											<td colspan="8" align="center">No Records Found</td>
										</tr>
							   	<?php } ?>
							   		</tbody>
							   	</table>
							   	<br/>
								<table width="100%">
									<tr><td align="center">&nbsp;</td></tr>
									<tr><td align="center"><input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/></td></tr>
								</table>
                            </div>
                        </blockquote>
                        <div>&nbsp;</div>
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
