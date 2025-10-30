<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
require_once 'library/common.php';
checkUser();
$msg = '';
$userid = $_SESSION['userid'];
$staffid 	= 	$_SESSION['sid'];
if($_GET['sheetid'] != "")
{
	$ItemNoList 	= array();
	$DivIdList 		= array();
	$SubDivIdList 	= array();
	//$ItemWoQty		= array();
	//$ItemRebate		= array();
	$ItemQty		= array();
	$UsedQty		= array();
	$sheetid = $_GET['sheetid'];
	$sheetdetail_sql = "select work_order_no, work_name, short_name, agree_no from sheet where sheet_id = '$sheetid'";
	$sheetdetail_query = mysql_query($sheetdetail_sql);
	$SheetList 		= mysql_fetch_object($sheetdetail_query);
	$workorderno 	= $SheetList->work_order_no;
	$workname 		= $SheetList->work_name;
	$workshortname 	= $SheetList->short_name;
	$agreementno 	= $SheetList->agree_no;
	
	$select_itemlist_sql 	= 	"select subdivision.subdiv_id, subdivision.subdiv_name, subdivision.div_id, schdule.total_quantity, schdule.rebate_percent from subdivision 
								INNER JOIN schdule ON (schdule.subdiv_id = subdivision.subdiv_id)
								where subdivision.sheet_id = '$sheetid' and subdivision.active = '1'";
								//echo $select_itemlist_sql."<br/>";
	$select_itemlist_query 	= 	mysql_query($select_itemlist_sql);
	while($ItemList = mysql_fetch_object($select_itemlist_query))
	{
		$divid 		= $ItemList->div_id;
		$subdivid 	= $ItemList->subdiv_id;
		$itemno 	= $ItemList->subdiv_name;
		$itemwoqty 	= $ItemList->total_quantity;
		$itemrebate	= $ItemList->rebate_percent;
		$totalQty = $itemwoqty  + ($itemwoqty * $itemrebate / 100);
		$DivIdList[$itemno] 	= $divid;
		$SubDivIdList[$itemno] 	= $subdivid;
		$ItemNoList[$itemno] 	= $itemno;
		$ItemQty[$itemno] 		= $totalQty;	
		//$ItemWoQty[$itemno] 	= $itemwoqty;
		//$ItemRebate[$itemno] 	= $itemrebate;
		
	}
	$select_measurementqty_sql = "SELECT SUM(mbookdetail.measurement_contentarea) as 'usedqty', mbookheader.subdiv_name FROM mbookdetail INNER JOIN mbookheader ON (mbookdetail.subdivid = mbookheader.subdivid) where mbookheader.sheetid = '$sheetid' and mbookheader.staffid = '$staffid'";
	$select_measurementqty_query = mysql_query($select_measurementqty_sql);
	while($QtyList = mysql_fetch_object($select_itemlist_query))
	{
		$itemno = 	$QtyList->subdiv_name;
		$Qty 	= 	$QtyList->usedqty;
		if($Qty == ""){ $Qty = 0; }
		$UsedQty[$itemno] 	= $Qty;
	}
//print_r($UsedQty);
$getmeasurement_sql = "select mbookheader_temp.mbheaderid, DATE_FORMAT(mbookheader_temp.date,'%d/%m/%Y') AS mdate, mbookheader_temp.divid, mbookheader_temp.subdiv_name, mbookdetail_temp.mbdetail_id , mbookdetail_temp.subdivid, mbookdetail_temp.descwork, mbookdetail_temp.measurement_no, mbookdetail_temp.measurement_l, mbookdetail_temp.measurement_b, mbookdetail_temp.measurement_d,  mbookdetail_temp.structdepth_unit,  mbookdetail_temp.measurement_dia, mbookdetail_temp.measurement_contentarea, mbookdetail_temp.remarks,  mbookdetail_temp.mbdetail_flag from mbookheader_temp INNER JOIN mbookdetail_temp ON (mbookheader_temp.mbheaderid = mbookdetail_temp.mbheaderid) where mbookheader_temp.sheetid = '$sheetid' and mbookheader_temp.staffid = '$staffid'";
$getmeasurement_query = mysql_query($getmeasurement_sql);
//echo $getmeasurement_sql;exit;
}
function CheckExceededQty($QtyStr,$subdivid)
{
	$splitStr = explode("*",$QtyStr);
	//natsort($splitStr);
	$totalQty = 0;
	for($i=0; $i<count($splitStr); $i+=2)
	{
		$itemid 	 = $splitStr[$i+0]; 
		$contentarea = $splitStr[$i+1]; 
		if($itemid == $subdivid)
		{
			$totalQty = $totalQty + $contentarea;
		}
	}
	return $totalQty;
}
function UpdateExceededError($Mbdetailid,$sheetid,$Errorstr)
{
	$updatembookdetail_sql = "update mbookdetail_temp set mbdetail_flag = '$Errorstr' where mbdetail_id = '$Mbdetailid'";
	$updatembookdetail_query = mysql_query($updatembookdetail_sql);
	if($updatembookdetail_query == true)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}
?>
<?php require_once "Header.html"; ?>
<script>
  	function goBack()
	{
	   url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() 
	{ 
		window.history.forward(); 
	}
/*	function GeneralEditData(id)
	{
		var idstr 			= id.trim();
		var splitid 		= idstr.split("*");
		var mbheaderid 		= splitid[0];
		var mbdetailid 		= splitid[1];
		
		$("#edit_img_"+mbdetailid).addClass('hide');
		$("#update_img_"+mbdetailid).removeClass('hide');
		
		var esno_id 		= "td_sno_"+mbdetailid;
		var edate_id 		= "td_date_"+mbdetailid;
		var eitemno_id 		= "td_itemno_"+mbdetailid;
		var edescrip_id 	= "td_descrip_"+mbdetailid;
		var enumber_id 		= "td_number_"+mbdetailid;
		var elength_id 		= "td_length_"+mbdetailid;
		var ebreadth_id 	= "td_breadth_"+mbdetailid;
		var edepth_id 		= "td_depth_"+mbdetailid;
		var earea_id 		= "td_coarea_"+mbdetailid;
		var eunit_id 		= "td_unit_"+mbdetailid;
		var eerror_id		= "td_error_"+mbdetailid;
		
		var esno 		= $("#"+esno_id).text().trim();
		var edate 		= $("#"+edate_id).text().trim();
		var eitemno 	= $("#"+eitemno_id).text().trim();
		var edescrip 	= $("#"+edescrip_id).text().trim();
		var enumber 	= $("#"+enumber_id).text().trim();
		var elength 	= $("#"+elength_id).text().trim();
		var ebreadth 	= $("#"+ebreadth_id).text().trim();
		var edepth 		= $("#"+edepth_id).text().trim();
		var earea 		= $("#"+earea_id).text().trim();
		var eunit 		= $("#"+eunit_id).text().trim();
		var eerror 		= $("#"+eerror_id).text().trim();
		
		//$("#"+esno_id).empty();
		//$("#"+esno_id).append('<input type="text" size="1" id="dummy_sno_'+mbdetailid+'" value="'+esno+'"/>');
		
		//$("#"+esno_id).empty();
		//$("#"+esno_id).append('<input type="text" class="snotextbox" id="dummy_sno_'+mbdetailid+'" value="'+esno+'"/>');
		
		$("#"+edate_id).empty();
		$("#"+edate_id).append('<input type="text" class="datetextbox" id="dummy_date_'+mbdetailid+'" value="'+edate+'"/>');
		
		$("#"+eitemno_id).empty();
		$("#"+eitemno_id).append('<input type="text" class="itemnotextbox" id="dummy_itemno_'+mbdetailid+'" value="'+eitemno+'"/>');
		
		$("#"+edescrip_id).empty();
		$("#"+edescrip_id).append('<input type="text" class="desctextbox" id="dummy_desc_'+mbdetailid+'" value="'+edescrip+'"/>');
		
		$("#"+enumber_id).empty();
		$("#"+enumber_id).append('<input type="text" class="notextbox" id="dummy_number_'+mbdetailid+'" value="'+enumber+'"/>');
		
		$("#"+elength_id).empty();
		$("#"+elength_id).append('<input type="text" class="lentextbox" id="dummy_length_'+mbdetailid+'" value="'+elength+'"/>');
		
		$("#"+ebreadth_id).empty();
		$("#"+ebreadth_id).append('<input type="text" class="bretextbox" id="dummy_breadth_'+mbdetailid+'" value="'+ebreadth+'"/>');
		
		$("#"+edepth_id).empty();
		$("#"+edepth_id).append('<input type="text" class="deptextbox" id="dummy_depth_'+mbdetailid+'" value="'+edepth+'"/>');
		
		$("#"+eunit_id).empty();
		$("#"+eunit_id).append('<input type="text" class="unitextbox" id="dummy_unit_'+mbdetailid+'" value="'+eunit+'"/>');
		//alert(esno_id+".."+eson)
		//alert(edescrip)
		alert(rowid)
		alert(rowid)
		alert(rowid)
		alert(rowid)
		alert(rowid)
		alert(rowid)
		alert(rowid)
		alert(rowid)
		alert(rowid)
		alert(rowid)
		alert(rowid)
		alert(rowid)
	}
	/*function GeneralUpdateData(id)
	{
		var idstr 			= id.trim();
		var splitid 		= idstr.split("*");
		var mbheaderid 		= splitid[0];
		var mbdetailid 		= splitid[1];
		$("#update_img_"+mbdetailid).addClass('hide');
		$("#edit_img_"+mbdetailid).removeClass('hide');
		var esno_id 			= "td_sno_"+mbdetailid;
		var edate_id 			= "td_date_"+mbdetailid;
		var eitemno_id 			= "td_itemno_"+mbdetailid;
		var edescrip_id 		= "td_descrip_"+mbdetailid;
		var enumber_id 			= "td_number_"+mbdetailid;
		var elength_id 			= "td_length_"+mbdetailid;
		var ebreadth_id 		= "td_breadth_"+mbdetailid;
		var edepth_id 			= "td_depth_"+mbdetailid;
		var earea_id 			= "td_coarea_"+mbdetailid;
		var eunit_id 			= "td_unit_"+mbdetailid;
		var eerror_id			= "td_error_"+mbdetailid;
		var dummy_esno_id 		= "dummy_sno_"+mbdetailid;
		var dummy_edate_id 		= "dummy_date_"+mbdetailid;
		var dummy_eitemno_id 	= "dummy_itemno_"+mbdetailid;
		var dummy_edescrip_id 	= "dummy_desc_"+mbdetailid;
		var dummy_enumber_id 	= "dummy_number_"+mbdetailid;
		var dummy_elength_id 	= "dummy_length_"+mbdetailid;
		var dummy_ebreadth_id 	= "dummy_breadth_"+mbdetailid;
		var dummy_edepth_id 	= "dummy_depth_"+mbdetailid;
		var dummy_eunit_id 		= "dummy_unit_"+mbdetailid;
		//var esno 		= $("#"+dummy_esno_id).val().trim();
		var edate 		= $("#"+dummy_edate_id).val().trim();
		var eitemno 	= $("#"+dummy_eitemno_id).val().trim();
		var edescrip 	= $("#"+dummy_edescrip_id).val().trim();
		var enumber 	= $("#"+dummy_enumber_id).val().trim();
		var elength 	= $("#"+dummy_elength_id).val().trim();
		var ebreadth 	= $("#"+dummy_ebreadth_id).val().trim();
		var edepth 		= $("#"+dummy_edepth_id).val().trim();
		var eunit 		= $("#"+dummy_eunit_id).val().trim();
		var workordername = $("#txt_sheetid").val()
		//alert(eitemno)
		var inputStr = mbheaderid+"@*@"+mbdetailid+"@*@"+edate+"@*@"+eitemno+"@*@"+edescrip+"@*@"+enumber+"@*@"+elength+"@*@"+ebreadth+"@*@"+edepth+"@*@"+eunit;
		$.post('MeasurementUpload_UpdateData.php', { sheetid: workordername, inputStr: inputStr }, function(data) {
			//alert(data);
			$("#"+edate_id).empty();
			$("#"+edate_id).text(edate)
			$("#"+eitemno_id).empty();
			$("#"+eitemno_id).text(eitemno)
			$("#"+edescrip_id).empty();
			$("#"+edescrip_id).text(edescrip)
			$("#"+enumber_id).empty();
			$("#"+enumber_id).text(enumber)
			$("#"+elength_id).empty();
			$("#"+elength_id).text(elength)
			$("#"+ebreadth_id).empty();
			$("#"+ebreadth_id).text(ebreadth)
			$("#"+edepth_id).empty();
			$("#"+edepth_id).text(edepth)
			$("#"+eunit_id).empty();
			$("#"+eunit_id).text(eunit)
			var url = "MeasurementUpload_View.php?sheetid="+workordername;
			$(location).attr('href',url);
		});
		
	}*/
/*$(function () {	

		$( ".edit" ).click(function() {
		  	var id = this.id
		  	GeneralEditData(id);
		});
		
		$( ".update" ).click(function() {
		  	var id = this.id
		  	GeneralUpdateData(id);
		});
		
		$( ".delete" ).click(function() {
		  var idstr = this.id;
		  //alert(idstr)
		  var workordername = $("#txt_sheetid").val();
		  $.post('MeasurementUpload_DeleteData.php', { sheetid: workordername, inputStr: idstr}, function(data) {
		  alert(data);
		  	var url = "MeasurementUpload_View.php?sheetid="+workordername;
			$(location).attr('href',url);
		  });
		});
		
		$( "#btn_confirm" ).click(function() {
		  var workordername = $("#txt_sheetid").val();
		  $.post('MeasurementUpload_ConfirmData.php', { sheetid: workordername }, function(data) {
		  alert(data);
		  	var url = "MeasurementUpload_View.php?sheetid="+workordername;
			$(location).attr('href',url);
		  });
		});
		
});*/
</script>
<style>
	.hide
	{
		display:none;
	}
	.table1
	{
		border:1px solid #D3D3D3;
		background:#FFFFFF;
	}
	.table1 tr {
		border:1px solid #D3D3D3;
		color:#0B29B9;
	}
	.table1 td {
		border:1px solid #D3D3D3;
		padding:1px;
	}
	.snotextbox{ width:32px; }
	.datetextbox{ width:68px; }
	.itemnotextbox{ width:50px; }
	.desctextbox{ width:285px; }
	.notextbox{ width:25px; }
	.lentextbox{ width:52px; }
	.bretextbox{ width:52px; }
	.deptextbox{ width:52px; }
	.unitextbox	{ width:40px; }
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                            <div class="title">Measurement Upload - View</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1">
							<table width="100%" align="center" cellpadding="3" cellspacing="3" class="">
									<tr>
										<td width="20%">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td width="">&nbsp;</td>
									</tr>
									<tr>
										<td width="20%">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td width="">&nbsp;</td>
									</tr>
									<tr>
										<td width="20%">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td width="">&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td class="label">Work Short Name</td> 
										<td class="labeldisplay">
											<select name="txt_workshortname" id="txt_workshortname" class="textboxdisplay" style="width:439px;height:22px;" onChange="workorderdetail();" tabindex="7">
												<option value=""> ----------------------- Select Work Short Name ------------------------ </option>
												<?php echo $objBind->BindWorkOrderNo($sheetid );?>
											</select>
											<?php 
											if($_GET['sheetid'] != "")
											{
											?>
												<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $_GET['sheetid'];?>">
											<?php	
											}
											?>
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td width="20%">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td width="">&nbsp;</td>
									</tr>
									<tr>
										<td width="">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td width="">&nbsp;</td>
										<td class="label">Measurement Type</td>
										<td>
											<input type="radio" name="rad_measurementtype" id="rad_others" value="G">&nbsp;&nbsp;<label class="label">General</label>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rad_measurementtype" id="rad_steel" value="S">&nbsp;&nbsp;<label class="label">Steel</label>
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td width="">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td width="20%">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td width="">&nbsp;</td>
									</tr>
									<tr>
										<td  height="30px;" colspan="4" align="center"><input type="button" class="backbutton" id="btn_view" id="btn_view" value=" View "></td>
									</tr>
									<tr>
										<td width="">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
							</table>
                        </blockquote>
                    </div>
                </div>
            </div>
	  </form>
      <!--==============================footer=================================-->
      <?php   include "footer/footer.html"; ?>
<script>
  var $container = $("#example1");
  var handsontable = $container.data('handsontable');
$(function () {

	$("#btn_view").click(function() {
	
		var workordername = $("#txt_workshortname").val();
		var type = $('input[name=rad_measurementtype]:checked').val();
		if(workordername != "")
		{
			if(type == 'S')
			{
				var url = "MeasurementUpload_View_Steel.php?sheetid="+workordername;
				$(location).attr('href',url);
			}
			else
			{
				var url = "MeasurementUpload_View_General.php?sheetid="+workordername;
				$(location).attr('href',url);
			}
			//var url = "MeasurementUpload_View.php?sheetid="+workordername;
			//$(location).attr('href',url);
		}
	});
});
</script>
    </body>
</html>
