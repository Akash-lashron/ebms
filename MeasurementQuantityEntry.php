<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
include "library/common.php";
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy.'-'.$mm.'-'.$dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
if(isset($_POST["btn_save"])){
    $SheetId  = $_POST['hid_sheetid'];
    $SchId    = $_POST['hid_schid'];
	$MeasDate = dt_format($_POST['txt_meas_date']);
    $MesDet = "Sorry Unable to save Measurement Quantity Entry.Please Try again..!!";
    if((count($SchId) == 0)){
        $msg = $MesDet;
    }else if((trim($SheetId) == 0) || (trim($SheetId) == "") || (trim($SheetId) == NULL)){
        $msg = $MesDet;
    }else{
		$SubDivIdArr    = $_POST['hid_subdiv'];
		$SubDivNameArr  = $_POST['hid_subdivname'];
		$AggreQtyArr    = $_POST['txt_aqty'];
		$UtiliQtyArr 	= $_POST['txt_uqty'];
		$DescripArr  	= $_POST['hid_desc'];
		$MeasQtyArr  	= $_POST['txt_mqty'];
		$UnitArr  		= $_POST['txt_unit'];
		foreach($SchId as $key => $value){
			$SubDivId  = $SubDivIdArr[$key];
			$SubDivName	= $SubDivNameArr[$key];
			$AggreQty  = $AggreQtyArr[$key];
			$UtiliQty  = $UtiliQtyArr[$key];
			$Descrip   = $DescripArr[$key];
			$MeasQty   = $MeasQtyArr[$key];
			$Unit	   = $UnitArr[$key];

			if($MeasQty != ""){
				$InsMasQuery = "INSERT INTO mbookheader SET sheetid = '$SheetId', subdivid = '$SubDivId', subdiv_name = '$SubDivName', 
				staffid = '$staffid', userid='$userid', date = '$MeasDate', active = 1";
				$InsMasQuerySql = mysql_query($InsMasQuery);
				$MbHeaderId = mysql_insert_id();
				if(($MbHeaderId != "") && ($MbHeaderId != NULL)){
					$InsDetQuery = "INSERT INTO mbookdetail SET mbheaderid = '$MbHeaderId', subdivid = '$SubDivId', subdiv_name = '$SubDivName', 
					descwork = '$Descrip', measurement_no = '$MeasQty', measurement_contentarea = '$MeasQty', 
					remarks = '$Unit', entry_date = '$CurrDate'";
					$InsDetQuerySql = mysql_query($InsDetQuery);
				}
				if((($MbHeaderId != NULL) || ($MbHeaderId != "")) && ($InsDetQuerySql == true)){
					$msg = "Measurement Quantity Entry Saved Successfully..!!";
				}
			}
		}
    }
}

$fromdate	= ""; $todate = "";
$sheetid	= $_POST['txt_workorderno'];

$MaxToDate  = "0000-00-00";
$sql_work = "SELECT work_order_date FROM sheet where sheet_id='".$sheetid."'";
$rs_work  = mysql_query($sql_work);
if(($rs_work == true) && (mysql_num_rows($rs_work)>0)){
    $WoList  	= mysql_fetch_object($rs_work);
    $MaxToDate  = $WoList->work_order_date;        //  mysql_result($rs_desc,0,'total_quantity');
}
$sql_absbook = "SELECT MAX(todate) as todate, rbn, MAX(rbn) as max_rbn FROM abstractbook where sheetid='".$sheetid."' AND rab_status='C'";
$rs_absbook  = mysql_query($sql_absbook);
if(($rs_absbook == true) && (mysql_num_rows($rs_absbook)>0)){
	$ABookList = mysql_fetch_object($rs_absbook);
	$MaxToDate = $ABookList->todate;
}
$sql_absbook_max = "SELECT MAX(rbn) as max_rbn FROM abstractbook where sheetid='".$sheetid."' AND rab_status='C'";
$rs_absbook_max  = mysql_query($sql_absbook_max);
if(($rs_absbook_max == true) && (mysql_num_rows($rs_absbook_max)>0)){
	$ABookListRbn = mysql_fetch_object($rs_absbook_max);
	$CurrRbn = $ABookListRbn->max_rbn;
}

$PrevQtyArr = array();
$measbook_query = "SELECT rbn, subdivid, SUM(mbtotal) as sum_mbtotal FROM measurementbook WHERE sheetid='".$sheetid."' AND rbn!='".$CurrRbn."' AND part_pay_flag IN (0,1) GROUP BY subdivid";
//	$measbook_query = "SELECT rbn, mbtotal, subdivid FROM measurementbook where sheetid='".$sheetid."' AND rbn!='".$CurrRbn."'";	//	partpay_flag = 0 and 1;
$sql_measbook  = mysql_query($measbook_query);
if(($sql_measbook == true) && (mysql_num_rows($sql_measbook)>0)){
	while($ABookListMeas = mysql_fetch_object($sql_measbook)){
		$SubDivID = $ABookListMeas->subdivid;
		$MbTotal = $ABookListMeas->sum_mbtotal;
		if(isset($PrevQtyArr[$SubDivID])){
			$PrevQtyArr[$SubDivID] = $PrevQtyArr[$SubDivID] + $MbTotal;
		}else{
			$PrevQtyArr[$SubDivID] = $MbTotal;
		}
	}
}

$MaxToDate = dt_display($MaxToDate);
if(isset($_POST['back']))
{
	header('Location: MeasurementQuantityEntryGenerate.php');
}
 
?>
<?php require_once "Header.html"; ?>
<script type="text/javascript" language="javascript">
	function goBack(){
	   	url = "MeasurementQuantityEntryGenerate.php";
		window.location.replace(url);
	}
</script>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<script src="dashboard/MyView/bootstrap.min.js"></script>

<style>
	.container{
    	display:table;
    	width:100%;
    	border-collapse: collapse;
    }
	.chbox-style{
		height: 12px;   
		width: 15px;
	}
	.table-bordered > thead > tr > th, .table-bordered > tbody > tr > td{
		color:#0241D2;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
	}
	.label{
		font-family: verdana, helvetica, arial, sans-serif;
		font-weight: bold;
		font-size: 13px;
		color: #00008B;
	}
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>

    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">Measurement Quantity Entry</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style=" height:500px;overflow:scroll;">
                            <div class="container">
								<div>&nbsp;</div>
								<div>
									<span class="label">Measurement Date : </span>
									<span>
										<input type="text" name="txt_meas_date" id="txt_meas_date" class="textboxdisplay datepicker" required/>
										<input type="hidden" name="txt_max_date" id="txt_max_date" class="textboxdisplay" value="<?php echo $MaxToDate; ?>" required/>
									</span>
								</div>
								<div>&nbsp;</div>
								<table class="table table-bordered" id="dataTable">
									<thead>
										<tr>
											<th>Item.No.</th>
											<th>Description</th>
											<th nowrap="nowrap">Aggrement Qty.</th>
											<th nowrap="nowrap">Previous Used Qty.</th>
											<th nowrap="nowrap">Measurement Qty.</th>
											<th>Unit</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$itementeredsql = "SELECT * FROM schdule WHERE sheet_id = '$sheetid' ORDER BY sch_id ASC";
									$rs_itementeredsql = mysql_query($itementeredsql);
											
									$slno = 1; $total = 0; $AggQtyArr = array();
									if(mysql_num_rows($rs_itementeredsql)>0){
										while($List = mysql_fetch_object($rs_itementeredsql)){
											if($List->total_quantity != ""){ 
												$AggQtyArr[$List->sch_id] = $List->total_quantity;
											}
											?>
										<tr>
											<td align="center"><?php echo $List->sno; ?></td>
											<td align="justify"><?php echo $List->description; ?></td>
												<?php if(($List->total_quantity != "")&&($List->total_quantity != 0)){ ?>
												<input type="hidden" name="hid_desc[]" id="hid_desc_<?php echo $List->sch_id; ?>" value="<?php echo $List->description; ?>">
												<input type="hidden" name="hid_subdiv[]" id="hid_subdiv_<?php echo $List->subdiv_id; ?>" value="<?php echo $List->subdiv_id; ?>">
												<input type="hidden" name="hid_subdivname[]" id="hid_subdivname_<?php echo $List->subdiv_id; ?>" value="<?php echo $List->sno; ?>">
												<?php } ?>
											<td align="center" nowrap="nowrap">
												<?php if(($List->total_quantity != "")&&($List->total_quantity != 0)){ echo $List->total_quantity; } ?>
												<?php if(($List->total_quantity != "")&&($List->total_quantity != 0)){ ?>
												<input type="hidden" name="txt_aqty[]" id="txt_aqty_<?php echo $List->sch_id; ?>" data-id="<?php echo $List->sch_id; ?>" class="Aqty" value="<?php echo $List->total_quantity; ?>">
												<input type="hidden" name="hid_schid[]" id="hid_schid_<?php echo $List->sch_id; ?>" value="<?php echo $List->sch_id; ?>">
												<?php } ?>
											</td>
											<td align="right">
												<?php if((isset($PrevQtyArr[$List->subdiv_id])) && ($PrevQtyArr[$List->subdiv_id] != 0)){ echo $PrevQtyArr[$List->subdiv_id]; } ?>
											</td>
											<td align="right">
												<?php if(($List->total_quantity != "")&&($List->total_quantity != 0)){ ?>
												<input type="text" name="txt_mqty[]" id="txt_mqty_<?php echo $List->sch_id; ?>" data-id="<?php echo $List->sch_id; ?>" class="tboxsmclass qtymeas" value="">
												<?php } ?>
											</td>
											<td align="right">
												<?php if(($List->total_quantity != "")&&($List->total_quantity != 0)){ ?>
												<input type="hidden" name="txt_unit[]" id="txt_unit_".<?php echo $List->sch_id; ?> data-id="<?php echo $List->sch_id; ?>" value="<?php echo $List->per; ?>">
												<?php } echo $List->per; ?>
											</td>
										</tr>
									<?php
										} $slno++;
									}else{ ?>
										<tr><td colspan="5">No records Found</td></tr>
									<?php } ?>
									<input type="hidden" name="txt_aggqtyarr" id="txt_aggqtyarr" class="" value='<?php echo json_encode($AggQtyArr); ?>'>
									</tbody>
								</table>
                            </div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php echo $sheetid; ?>">
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
									<input type="submit" name="btn_save" id="btn_save" value=" Save " />
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
	var msg = "<?php echo $msg; ?>";
    if(msg != ""){
		BootstrapDialog.show({
			message: msg,
			buttons: [{
				label: ' OK ',
				action: function(dialog) {
					dialog.close();
					window.location.replace('MeasurementQuantityEntryGenerate.php');
				}
			}]
		});
    }
	$(document).ready(function(){
		/*$('#dataTable').DataTable({
			responsive: true,
			paging: false, 
		});*/
		
		$("#txt_meas_date").change(function(event){
			var MeasDate = $("#txt_meas_date").val();
			var MaxDate = $("#txt_max_date").val();
			if((MeasDate != "") && (MaxDate != "")){  
				var d1 = MeasDate.split("/");
				var d2 = MaxDate.split("/");
				var MsDate = new Date(d1[2], d1[1]-1, d1[0]);
				var MxDate = new Date(d2[2], d2[1]-1, d2[0]);
				if(MsDate < MxDate){
					var a="Date of Measurement should be greater than "+MaxDate;
					$("#txt_meas_date").val('');
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}
			}
		});
		
		$( "#txt_meas_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd/mm/yy",
			yearRange: "2010:+15",
			maxDate: new Date,
			defaultDate: new Date,
			zIndex : 3,
		});
		$('body').on('change','.qtymeas',function(event){
			var gvnschid = $(this).attr("data-id");
			var givenqty = $(this).val();
			var sheetid  = $("#hid_sheetid").val();	//alert(sheetid);	alert(gvnschid);	alert(givenqty);
			$.ajax({ 
				type: 'GET', 
				url: 'find_measurements.php',
				data: { sheetid: sheetid, gvnschid: gvnschid, givenqty: givenqty },
				dataType: 'json',
				success: function (data) {  
					if(data != ''){
						var errval = 0;
						var UtiQty = data['UtilizedQty'];
						var AggQty = data['AggQty'];
						//alert(AggQty);
						if(Number(UtiQty) > Number(AggQty)){
							BootstrapDialog.show({
								message: "Measurement quantity is greater than the Aggrement Quantity..!!",
								buttons: [{
									label: ' OK ',
									action: function(dialog) {
										$("#txt_mqty_"+gvnschid).val('');
										dialog.close();
									}
								}]
							});
						}
					}
				}
			});
		});

		
	});
</script>
<style>
	.bootstrap-dialog-footer-buttons > .btn-default{
		color:#fff;
		background-color:#FA5B45;
	}
	.dataTables_wrapper{
		width:99% !important;
	}
	#dataTable td, #dataTable th{
		font-size:11px !important;
	}
	input[type="checkbox"], input[type="radio"] {
    	margin: 0px 0 0;
	}
</style>
