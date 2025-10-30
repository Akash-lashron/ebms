<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
include "sysdate.php";
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
$msg = '';
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
if($_POST["submit"] == " View ") 
{
	$rbn 					= 	$_POST['cmb_rbn'];
	//echo $rbn ;exit;
	$sheetid				=	$_POST['cmb_work_no'];
	$selectmbook_detail = " select DISTINCT fromdate, todate, abstmbookno FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1' AND rbn = '$rbn'";
	$selectmbook_detail_sql = mysql_query($selectmbook_detail);
	//echo $selectmbook_detail;
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno;
}
}
if(isset($_POST['update']))
 {
	$parent_id       = count($_POST['hid_measure_id']);
	$bmdetail_id     = count($_POST['hid_mbdetail_id']);
	$percantage      = count($_POST['txt_percent']);
	
	$ParentIdArr 	= $_POST['hid_measure_id'];
	$MBIdArr     	= $_POST['hid_mbdetail_id'];
	$PercArr      	= $_POST['txt_percent'];
	$rbnArr      	= $_POST['hid_rbn'];
	$temp = 0;
	for($i=0; $i<$parent_id; $i++){
		//$res = $_POST['hid_percent'.$i];
		$ParentId    = $ParentIdArr[$i];
		$MBId        = $MBIdArr[$i];
		$Percentage  = $PercArr[$i];
		//echo $ParentId."<br/>";
		$update_percentage_query = "update `mbookdetail` set prev_paid_perc = '$Percentage', prev_paid_rbn ='$rbnArr ', prev_parent_id ='$ParentId' WHERE mbdetail_id = '$MBId'";
		//echo $update_percentage_query; exit;
		$update_percentage_sql = mysql_query($update_percentage_query);
		if($update_percentage_sql != true){ $temp++; }
	} 
	if($temp>0) 
	{ 
	   $msg = 'Data Updation Error ...!!!'; 
	}
	if($temp==0)
	{ 
	 $msg = "Sucessfully Updated...!!!"; 
	 $success = 1;
	}
}
?>
<?php require_once "Header.html"; ?>
<script type="text/javascript" language="javascript">
	function goBack(){
	   	url = "MBookTest.php";
		window.location.replace(url);
	}
</script>
<style>
	.container{
    	display:table;
    	width:100%;
    	border-collapse: collapse;
    }/*.table-row{  
		 display:table-row;
		 text-align: left;
	}.col{
		display:table-cell;
		border: 1px solid #CCC;
		padding:1px;
	}*/.chbox-style{
		height: 12px;   
		width: 15px;
	}
	.table-bordered > thead > tr > th, .table-bordered > tbody > tr > td{
		color:#0241D2;
	}
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<script src="dashboard/MyView/bootstrap.min.js"></script>

    <body class="page1" id="top">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">
					<div align="right" style="color:#0000cd; font-weight:bold; font-size:13px;"><!--<b> <font color="#E6061D">*</font> To Edit click Item No. &nbsp; &nbsp;&nbsp;</b>--></div>
                        <blockquote class="bq1" style=" height:500px;overflow:scroll;">
                          <div class="title">Test Measurements List</div>
                            <div class="container">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th style="text-align:center">S.No.</th>
											<th>Date</th>
											<th nowrap="nowrap" style="text-align:center">Item No.</th>
											<th>Description</th>
											<th style="text-align:right">Contents of Area</th>
											<th style="text-align:right">Paid % <input type="text" name="text" class="textboxdisplay" id="TestALL"  style="width: 50px;"/></th>
										</tr>
									</thead>
									<tbody>
									
									<?php
									$prev_subdivid = ""; $prev_contentarea = 0; $currentline = $start_line + 10; $line = $currentline; $prev_date = "";$page = $mpage; $txtboxid = 1;
									$query = "SELECT DATE_FORMAT( mbookheader.date , '%d/%m/%Y' ) AS date ,  mbookdetail.subdivid , subdivision.subdiv_name , subdivision. div_id, 
									mbookdetail.descwork, mbookdetail.mbdetail_id, mbookdetail.measurement_contentarea, mbookheader.sheetid, mbookdetail.prev_paid_perc FROM mbookheader
									INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
									INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
									INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) WHERE  mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND mbookheader.sheetid = '$sheetid' AND mbookheader.staffid = '$staffid' 
									ORDER BY mbookdetail.subdivid, mbookheader.mbheaderid, mbookdetail.mbdetail_id ASC" ;
									$sqlquery = mysql_query($query);
									//echo $query ;exit;		 
									$slno = 1;
									if(mysql_num_rows($sqlquery)>0){
										while($List = mysql_fetch_object($sqlquery)){  
										    
											$decimal = get_decimal_placed($List->subdivid,$sheetid);
											$subdivid=$List->subdivid;
											$PrevPaidPrec = $List->prev_paid_perc;
										// echo $subdivid;exit;
									    ?>
										
										<tr>
											<td align="center"><?php echo $slno; ?></td>
											<td><?php echo $List->date; ?></td>
											<td align="center" nowrap="nowrap"><?php echo $List->subdiv_name; ?></td>
											<td><?php echo $List->descwork; ?></td>
											<td align="right"><?php if($List->measurement_contentarea != 0){ echo number_format($List->measurement_contentarea,$decimal,".",","); } ?></td>
											<?php
								               	$percent_query="select subdivid, measurementbookid, pay_percent from measurementbook WHERE rbn =$rbn AND sheetid = '$sheetid' AND subdivid ='$subdivid'";
										       	$pecent_sqlquery = mysql_query($percent_query);
											   //if(mysql_num_rows($sqlquery)>0){
										           //while($List1 = mysql_fetch_object($pecent_sqlquery)){
												$List1 = mysql_fetch_object($pecent_sqlquery);
											    
												if($PrevPaidPrec == ''){
													$pay_percent = $List1->pay_percent;
												}else{
													$pay_percent = $PrevPaidPrec;
												}
												
								        	?>
											<td align="right"><input type="text" name="txt_percent[]" id="txt_percent" class="textboxdisplay test" style="width: 50px;" value="<?php echo $pay_percent; ?>"/>
											<input type="hidden" name="hid_measure_id[]" id="txt_measure_id" value="<?php echo $List1->measurementbookid; ?>">
											<input type="hidden" name="hid_mbdetail_id[]" id="txt_mbdetail_id" value="<?php echo $List->mbdetail_id; ?>">
											</td>
										</tr>
										<?php $slno++; } } ?>
									</tbody>
								</table>
                            </div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php echo $sheetid; ?>">
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="hidden" name="hid_rbn" id="hid_rbn" value="<?php echo $rbn; ?>">
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
								<input type="submit" name="update" value=" Update ">
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
<style>
	.bootstrap-dialog-footer-buttons > .btn-default{
		color:#fff;
		background-color:#FA5B45;
	}
</style>
<script>
$(document).ready(function () { 
	$('#TestALL').change(function(){
		var test = $(this).val();
		$(".test").each(function() {
			$(this).val(test);
		});
	});
});
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
				url = "MbookViewTest.php";
						window.location.replace(url);
			  }
			});
	}
};
</script>
