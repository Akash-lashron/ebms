<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Technical Sanction View';
checkUser();
$success = 0;
$Finacid  = array();
$staffid  = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];
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
if($_SESSION['isadmin'] == 1){
	$TecSanQuery = "SELECT * FROM technical_sanction ORDER BY ts_id ASC";
}else{
	$TecSanQuery = "SELECT * FROM technical_sanction WHERE (eic = '$staffid' OR created_by = '$UserId') ORDER BY ts_id ASC";
}
$resultSql = mysqli_query($dbConn,$TecSanQuery);
//echo $TecSanQuery;exit;
$Hoaresult = " SELECT hoamast_id,new_hoa_no FROM hoa_master ORDER BY hoamast_id ASC";
$HoaresultSql = mysqli_query($dbConn,$Hoaresult);
if($HoaresultSql == true){
	if(mysqli_num_rows($HoaresultSql) > 0){
		//foreach($Hoaresult as $key => $value){
		while($ListHoa = mysqli_fetch_object($HoaresultSql)){
			$HoaArr[$ListHoa->hoamast_id] = $ListHoa->new_hoa_no;
		}
	}
}
//print_r($HoaArr);
// ORDER BY type asc, group_id asc");
//$result_sql = mysqli_query($dbConn,$result); //mysqli_query($insert_query);
$FinaQuery = "SELECT ts_id FROM tender_register";
$FinaResult = mysqli_query($dbConn,$FinaQuery);
if(mysqli_num_rows($FinaResult)>0){
	while($List = mysqli_fetch_array($FinaResult)){
		array_push($Finacid,$List['ts_id']);
	}
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript">
	function goBack(){
		url = "Home.php";
		window.location.replace(url);
	}
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
						<blockquote class="bq1 stable" style="overflow:auto">
						<div class="row">
								<div class="box-container box-container-lg" align="center">
									<!-- <div class="div1">&nbsp;</div> -->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Technical Sanction - View</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																<thead>
																	<tr>
																		<th valign="middle">SNo.</th>
																		<th valign="middle">Technical Sanction No.</th>
																		<th valign="middle">Work Name</th>
																		<th valign="middle">Head Of Account</th>
																		<th valign="middle">Technical Sanction Amount</br>( &#8377; )</th>
																		<th valign="middle">Technical Sanction</br>Date</th>
																		<th valign="middle">Action</th>
																	</tr>
																</thead>
																<tbody>
																	
																		<?php
																			$SNO = 1; 
																			while($List = mysqli_fetch_object($resultSql)){
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
																	<tr class='labeldisplay'>
																		<td class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																		<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->ts_no; ?></td>
																		<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->ts_work_name; ?></td>
																		<td class='tdrow' align='justify' valign='justify'><?php echo $HoaNameDispay; ?></td>
																		<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($List->ts_amount); ?></td>
																		<td class='tdrow' align='center' valign='middle'><?php echo dt_display($List->ts_date); ?></td>
																		<td valign='middle' class='tdrow' align='center'>
																		<?php if (in_array($List->ts_id, $Finacid)){ 
																			$BtnName = " View "; $TTMess = "NIT has been updated, you can't Edit but you can View";
																		?>
																		<?php }else{ $BtnName = " View & Edit "; $TTMess = "View or Edit Technical Sanction"?>
																		<?php } ?><a data-url="TechnicalSanction?id=<?php echo $List->ts_id; ?>" class="BtnHref btn btn-info" title="<?php echo $TTMess ?>" name="View" id="View"><?php echo $BtnName;?></a></td>
																	</tr>
								                                   <?php  $SNO++; } ?>
																   </tbody>
																</table>
															</div>
														</div>
														<div align="center">
														<input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" />
														    <input type="button" class="btn btn-info" name="Back" id="Back" value="Back" onClick="goBack();"/>
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