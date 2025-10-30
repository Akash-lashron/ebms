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
	$ItemSubTypeArr = array();
	//$ItemWoQty		= array();
	//$ItemRebate		= array();
	$ItemQty		= array();
	$UsedQty		= array();
	//$UnitList		= array();
	$sheetid = $_GET['sheetid'];
	$sheetdetail_sql = "select work_order_no, work_name, short_name, agree_no from sheet where sheet_id = '$sheetid'";
	$sheetdetail_query = mysql_query($sheetdetail_sql);
	$SheetList 		= mysql_fetch_object($sheetdetail_query);
	$workorderno 	= $SheetList->work_order_no;
	$workname 		= $SheetList->work_name;
	$workshortname 	= $SheetList->short_name;
	$agreementno 	= $SheetList->agree_no;
	
	$select_dia_sql = "select DISTINCT(mbookdetail.measurement_dia) from mbookdetail INNER JOIN  mbookheader ON (mbookdetail.mbheaderid = mbookheader.mbheaderid) where mbookheader.sheetid = '$sheetid' and mbookheader.staffid = '$staffid'";
	$select_dia_query = mysql_query($select_dia_sql);
	if(mysql_num_rows($select_dia_query)>0)
	{
		while($DiaList = mysql_fetch_object($select_dia_query))
		{
			$dia = $DiaList->measurement_dia;
			$DiaStr .= $dia."*";
		}
	}
	$MasSubDivIdArr = array();
	$select_itemid_query = "select DISTINCT subdiv_id from schdule where sheet_id = '$sheetid' and measure_type = 's' and subdiv_id != 0";
	$select_itemid_sql = mysql_query($select_itemid_query);
	if($select_itemid_sql == true){
		if(mysql_num_rows($select_itemid_sql)>0){
			while($ItemIDList = mysql_fetch_object($select_itemid_sql)){
				$MasSubDivId = $ItemIDList->subdiv_id;
				array_push($MasSubDivIdArr,$MasSubDivId);
			}
		}
	}
	
	$MaxToDate = '0000-00-00';
	$SelectMaxDateQuery = "select DATE(max(todate)) as maxdate from measurementbook where sheetid = '$sheetid'";
	$SelectMaxDateSql = mysql_query($SelectMaxDateQuery);
	if($SelectMaxDateSql == true){
		if(mysql_num_rows($SelectMaxDateSql)>0){
			$DtList = mysql_fetch_object($SelectMaxDateSql);
			$MaxToDate = $DtList->maxdate;
		}
	}
	

	$select_wodate_query = "select work_order_date from sheet where sheet_id = '$sheetid'";
	$select_wodate_sql = mysql_query($select_wodate_query);
	if($select_wodate_sql == true){
		if(mysql_num_rows($select_wodate_sql)>0){
			$WODtList = mysql_fetch_object($select_wodate_sql);
			$WoDate = $WODtList->work_order_date;
		}
	}
	
	$expDiaStr = explode("*",rtrim($DiaStr,"*"));
	$select_itemlist_sql 	= 	"select distinct(mbookheader_temp.subdivid), subdivision.subdiv_id, subdivision.subdiv_name, subdivision.div_id, schdule.total_quantity,schdule.per, schdule.decimal_placed, schdule.deviate_qty_percent, schdule.sub_type from subdivision 
								INNER JOIN schdule ON (schdule.subdiv_id = subdivision.subdiv_id)
								INNER JOIN mbookheader_temp ON (mbookheader_temp.subdivid = subdivision.subdiv_id) 
								where subdivision.sheet_id = '$sheetid' and schdule.measure_type = 's' and subdivision.active = '1'";
								//echo $select_itemlist_sql."<br/>";exit;
	$select_itemlist_query 	= 	mysql_query($select_itemlist_sql);
	while($ItemList = mysql_fetch_object($select_itemlist_query))
	{
		$divid 		= $ItemList->div_id;
		$subdivid 	= $ItemList->subdiv_id;
		$itemno 	= $ItemList->subdiv_name;
		$deci 		= $ItemList->decimal_placed;
		//$itemunit 	= $ItemList->per;
		$itemwoqty 	= $ItemList->total_quantity;
		$itemdevqty	= $ItemList->deviate_qty_percent;
		$totalQty = $itemwoqty  + ($itemwoqty * $itemdevqty / 100);
		$DivIdList[$itemno] 	= $divid;
		$SubDivIdList[$itemno] 	= $subdivid;
		$ItemNoList[$itemno] 	= $itemno;
		$ItemQty[$itemno] 		= $totalQty;
		$sub_type 				= $ItemList->sub_type;	
		$ItemSubTypeArr[$itemno] = $sub_type;
		
		$DpmQty = 0;
		$SelectDPMQtyQuery = "select sum(mbtotal) as dpmqty from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' and (part_pay_flag = 1 OR part_pay_flag = 0) group by subdivid";
		//echo $SelectDPMQtyQuery;//exit;
		$SelectDPMQtySql = mysql_query($SelectDPMQtyQuery);
		if($SelectDPMQtySql == true){
			if(mysql_num_rows($SelectDPMQtySql)>0){
				$DPMList = mysql_fetch_object($SelectDPMQtySql);
				$DpmQty = $DPMList->dpmqty;
				$DpmQty = round($DpmQty,$deci);
			}
		}
		
		
		//echo $DpmQty."<br/>";exit;
		//$UnitList[$itemno] 		= $itemunit;
		//$ItemWoQty[$itemno] 	= $itemwoqty;
		//$ItemRebate[$itemno] 	= $itemrebate;
			$totalweight_8 = 0;$totalweight_10 = 0;$totalweight_12 = 0;$totalweight_16 = 0;$totalweight_20 = 0;
			$totalweight_25 = 0;$totalweight_28 = 0;$totalweight_32 = 0;$totalweight_36 = 0;
		for($x=0; $x<count($expDiaStr); $x++)
		{
			$dia = $expDiaStr[$x];
			if($sub_type != 'c')
			{
				$Qty = 0;
			}
			
			
			//if($dia != "")
			//{
				//$select_measurementqty_sql = "SELECT SUM(mbookdetail.measurement_contentarea) AS usedqty, mbookheader.subdiv_name FROM mbookdetail INNER JOIN mbookheader ON (mbookdetail.subdivid = mbookheader.subdivid) where mbookdetail.measurement_dia = '$dia' and mbookdetail.subdivid = '$subdivid' and mbookheader.sheetid = '$sheetid' and mbookheader.staffid = '$staffid'";// GROUP BY mbookdetail.subdivid";
				$select_measurementqty_sql = "SELECT a.measurement_contentarea AS usedqty FROM mbookdetail a inner join mbookheader b on (a.mbheaderid = b.mbheaderid) where a.measurement_dia = '$dia' and a.subdivid = '$subdivid' and measurement_contentarea != '' and b.date > '$MaxToDate'";// GROUP BY mbookdetail.subdivid";
				$select_measurementqty_query = mysql_query($select_measurementqty_sql);
				//echo $select_measurementqty_sql."<br/>";
				if($select_measurementqty_query == true)
				{
					while($QtyList = mysql_fetch_object($select_measurementqty_query))
					{
						if($sub_type == 'c')
						{
							$itemno = 	$itemno;//$QtyList->subdiv_name;
							//echo $itemno."<br/>";
							$Qty 	= 	$Qty+$QtyList->usedqty;
							//$UsedQty[$itemno] 	= $Qty;
						}
						else
						{
							$itemno = 	$itemno;//$QtyList->subdiv_name;
							$Qty 	= 	$Qty+$QtyList->usedqty;
							if($dia == 8){ 	$totalweight_8 = $Qty * 0.395; }//else{ $totalweight_8 = 0; }
							if($dia == 10){ $totalweight_10 = $Qty * 0.617; }//else{ $totalweight_10 = 0; }
							if($dia == 12){ $totalweight_12 = $Qty * 0.888; }//else{ $totalweight_12 = 0; }
							if($dia == 16){ $totalweight_16 = $Qty * 1.578; }//else{ $totalweight_16 = 0; }
							if($dia == 20){ $totalweight_20 = $Qty * 2.466; }//else{ $totalweight_20 = 0; }
							if($dia == 25){ $totalweight_25 = $Qty * 3.853; }//else{ $totalweight_25 = 0; }
							if($dia == 28){ $totalweight_28 = $Qty * 4.834; }//else{ $totalweight_28 = 0; }
							if($dia == 32){ $totalweight_32 = $Qty * 6.313; }//else{ $totalweight_32 = 0; }
							if($dia == 36){ $totalweight_36 = $Qty * 7.990; }//else{ $totalweight_36 = 0; }
							/*if($dia == 8){ 	$totalweight_8 = $Qty * 0.395; }else{ $totalweight_8 = 0; }
							if($dia == 10){ $totalweight_10 = $Qty * 0.617; }else{ $totalweight_10 = 0; }
							if($dia == 12){ $totalweight_12 = $Qty * 0.888; }else{ $totalweight_12 = 0; }
							if($dia == 16){ $totalweight_16 = $Qty * 1.580; }else{ $totalweight_16 = 0; }
							if($dia == 20){ $totalweight_20 = $Qty * 2.470; }else{ $totalweight_20 = 0; }
							if($dia == 25){ $totalweight_25 = $Qty * 3.860; }else{ $totalweight_25 = 0; }
							if($dia == 28){ $totalweight_28 = $Qty * 4.830; }else{ $totalweight_28 = 0; }
							if($dia == 32){ $totalweight_32 = $Qty * 6.313; }else{ $totalweight_32 = 0; }
							if($dia == 36){ $totalweight_36 = $Qty * 8.000; }else{ $totalweight_36 = 0; }
							$totalweight = ($totalweight_8+$totalweight_10+$totalweight_12+$totalweight_16+$totalweight_20+$totalweight_25+$totalweight_28+$totalweight_32+$totalweight_36);
							$TotQty_mt = $totalweight/1000;
							$UsedQty[$itemno] 	= $TotQty_mt;*/
						}
						
					}
					//if($sub_type == 'c')
					//{
						//$UsedQty[$itemno] 	= $Qty;
					//}
					//else
					/*if($sub_type != 'c')
					{
						if($dia == 8){ 	$totalweight_8 = $Qty * 0.395; }//else{ $totalweight_8 = 0; }
						if($dia == 10){ $totalweight_10 = $Qty * 0.617; }//else{ $totalweight_10 = 0; }
						if($dia == 12){ $totalweight_12 = $Qty * 0.888; }//else{ $totalweight_12 = 0; }
						if($dia == 16){ $totalweight_16 = $Qty * 1.580; }//else{ $totalweight_16 = 0; }
						if($dia == 20){ $totalweight_20 = $Qty * 2.470; }//else{ $totalweight_20 = 0; }
						if($dia == 25){ $totalweight_25 = $Qty * 3.860; }//else{ $totalweight_25 = 0; }
						if($dia == 28){ $totalweight_28 = $Qty * 4.830; }//else{ $totalweight_28 = 0; }
						if($dia == 32){ $totalweight_32 = $Qty * 6.313; }//else{ $totalweight_32 = 0; }
						if($dia == 36){ $totalweight_36 = $Qty * 8.000; }//else{ $totalweight_36 = 0; }
					}*/
				}
				//echo $totalweight."@@".""."<br/>";
			//}
		}
		$totalweight = 0; $TotQty_mt = 0;
		if($sub_type == 'c')
		{
			$UsedQty[$itemno] 	= round(($Qty + $DpmQty),$deci);
			$Qty = 0;
		}
		else
		{
			$totalweight = ($totalweight_8+$totalweight_10+$totalweight_12+$totalweight_16+$totalweight_20+$totalweight_25+$totalweight_28+$totalweight_32+$totalweight_36);
			$TotQty_mt = $totalweight/1000;
			//echo $TotQty_mt."<br/>";
			$UsedQty[$itemno] 	= round(($TotQty_mt + $DpmQty),$deci);
		}
		
	}
//print_r($ItemSubTypeArr);
//echo "<br/>";
//print_r($UsedQty);
//exit;
/*$getmeasurement_sql = "select mbookheader_temp.mbheaderid, DATE_FORMAT(mbookheader_temp.date,'%d/%m/%Y') AS mdate, 
mbookheader_temp.divid, mbookheader_temp.subdiv_name, mbookdetail_temp.mbdetail_id , mbookdetail_temp.subdivid, 
mbookdetail_temp.descwork, mbookdetail_temp.measurement_no, mbookdetail_temp.measurement_no2, mbookdetail_temp.measurement_l, 
mbookdetail_temp.measurement_b, mbookdetail_temp.measurement_d,  mbookdetail_temp.structdepth_unit,  mbookdetail_temp.measurement_dia, 
mbookdetail_temp.measurement_contentarea, mbookdetail_temp.remarks,  mbookdetail_temp.mbdetail_flag, schdule.sub_type from mbookheader_temp 
INNER JOIN mbookdetail_temp ON (mbookheader_temp.mbheaderid = mbookdetail_temp.mbheaderid) 
INNER JOIN schdule ON (schdule.subdiv_id = mbookheader_temp.subdivid)
where mbookdetail_temp.measure_type = 's' and mbookheader_temp.sheetid = '$sheetid' and schdule.sheet_id = '$sheetid' and mbookheader_temp.staffid = '$staffid' 
ORDER BY mbookdetail_temp.mbdetail_id ASC";*/
$getmeasurement_sql = "select mbookheader_temp.mbheaderid, DATE_FORMAT(mbookheader_temp.date,'%d/%m/%Y') AS mdate, 
mbookheader_temp.divid, mbookheader_temp.subdiv_name, mbookdetail_temp.mbdetail_id , mbookdetail_temp.subdivid, 
mbookdetail_temp.descwork, mbookdetail_temp.measurement_no, mbookdetail_temp.measurement_no2, mbookdetail_temp.measurement_l, 
mbookdetail_temp.measurement_b, mbookdetail_temp.measurement_d,  mbookdetail_temp.structdepth_unit,  mbookdetail_temp.measurement_dia, 
mbookdetail_temp.measurement_contentarea, mbookdetail_temp.remarks,  mbookdetail_temp.mbdetail_flag from mbookheader_temp 
INNER JOIN mbookdetail_temp ON (mbookheader_temp.mbheaderid = mbookdetail_temp.mbheaderid) 
where mbookdetail_temp.measure_type = 's' and mbookheader_temp.sheetid = '$sheetid' and mbookheader_temp.staffid = '$staffid' 
ORDER BY mbookdetail_temp.mbdetail_id ASC";
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
$ToDay = date('d-m-Y');
$FutDateErr = 0; $WoDateErr = 0;
?>
<?php require_once "Header.html"; ?>
<script>
  	function goBack()
	{
	   url = "MeasurementUpload_View.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() 
	{ 
		window.history.forward(); 
	}
	function GeneralEditData(id)
	{
		var idstr 			= id.trim();
		var splitid 		= idstr.split("*");
		var mbheaderid 		= splitid[0];
		var mbdetailid 		= splitid[1];
		
		var height = $('#edit_img_'+mbdetailid).height();
		$('#'+mbdetailid).css('background-color', '#F0F0F0');
		//alert(height);
		if(height != 0)
		{
			var heightstyle = "style='height:"+height+"px'";
		}
		else
		{
			var heightstyle = "";
		}
		
		$("#edit_img_"+mbdetailid).addClass('hide');
		$("#update_img_"+mbdetailid).removeClass('hide');
		
		var esno_id 		= "td_sno_"+mbdetailid;
		var edate_id 		= "td_date_"+mbdetailid;
		var eitemno_id 		= "td_itemno_"+mbdetailid;
		var edescrip_id 	= "td_descrip_"+mbdetailid;
		var enumber_id 		= "td_number_"+mbdetailid;
		var elength_id 		= "td_length_"+mbdetailid;
		var edia_id 		= "td_dia_"+mbdetailid;
		//var edepth_id 		= "td_depth_"+mbdetailid;
		var earea_id 		= "td_coarea_"+mbdetailid;
		
		var eunit_id 		= "td_unit_"+mbdetailid;
		var eerror_id		= "td_error_"+mbdetailid;
		
		var esno 		= $("#"+esno_id).text().trim();
		var edate 		= $("#"+edate_id).text().trim();
		var eitemno 	= $("#"+eitemno_id).text().trim();
		var edescrip 	= $("#"+edescrip_id).text().trim();
		var enumber 	= $("#"+enumber_id).text().trim();
		var elength 	= $("#"+elength_id).text().trim();
		var edia 		= $("#"+edia_id).text().trim();
		//var edepth 		= $("#"+edepth_id).text().trim();
		var earea 		= $("#"+earea_id).text().trim();
		
		var eunit 		= $("#"+eunit_id).text().trim();
		var eerror 		= $("#"+eerror_id).text().trim();
		
		/*$("#"+esno_id).empty();
		$("#"+esno_id).append('<input type="text" size="1" id="dummy_sno_'+mbdetailid+'" value="'+esno+'"/>');*/
		
		//$("#"+esno_id).empty();
		//$("#"+esno_id).append('<input type="text" class="snotextbox" id="dummy_sno_'+mbdetailid+'" value="'+esno+'"/>');
		var w1 = $("#"+edate_id).width();
		$("#"+edate_id).empty();
		$("#"+edate_id).append('<input type="text" class="datetextbox" id="dummy_date_'+mbdetailid+'" value="'+edate+'"'+heightstyle+'/>');
		$("#dummy_date_"+mbdetailid).width(w1);
		
		var w2 = $("#"+eitemno_id).width();
		$("#"+eitemno_id).empty();
		$("#"+eitemno_id).append('<input type="text" class="itemnotextbox" id="dummy_itemno_'+mbdetailid+'" value="'+eitemno+'"'+heightstyle+'/>');
		$("#dummy_itemno_"+mbdetailid).width(w2);
		
		var w3 = $("#"+edescrip_id).width();
		$("#"+edescrip_id).empty();
		$("#"+edescrip_id).append('<input type="text" class="desctextbox" id="dummy_desc_'+mbdetailid+'" value="'+edescrip+'"'+heightstyle+'/>');
		$("#dummy_desc_"+mbdetailid).width(w3);
		
		var w4 = $("#"+enumber_id).width();
		$("#"+enumber_id).empty();
		$("#"+enumber_id).append('<input type="text" class="notextbox" id="dummy_number_'+mbdetailid+'" value="'+enumber+'"'+heightstyle+'/>');
		$("#dummy_number_"+mbdetailid).width(w4);
		
		var w5 = $("#"+elength_id).width();
		$("#"+elength_id).empty();
		$("#"+elength_id).append('<input type="text" class="lentextbox" id="dummy_length_'+mbdetailid+'" value="'+elength+'"'+heightstyle+'/>');
		$("#dummy_length_"+mbdetailid).width(w5);
		
		var w6 = $("#"+edia_id).width();
		$("#"+edia_id).empty();
		$("#"+edia_id).append('<input type="text" class="bretextbox" id="dummy_dia_'+mbdetailid+'" value="'+edia+'"'+heightstyle+'/>');
		$("#dummy_dia_"+mbdetailid).width(w6);
		
		//$("#"+edepth_id).empty();
		//$("#"+edepth_id).append('<input type="text" class="deptextbox" id="dummy_depth_'+mbdetailid+'" value="'+edepth+'"/>');
		
		$("#"+eunit_id).empty();
		$("#"+eunit_id).append('<input type="text" class="unitextbox" id="dummy_unit_'+mbdetailid+'" value="'+eunit+'"'+heightstyle+'/>');
		//alert(esno_id+".."+eson)
		//alert(edescrip)
		/*alert(rowid)
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
		alert(rowid)*/
	}
	function GeneralUpdateData(id)
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
		var edia_id 			= "td_dia_"+mbdetailid;
		//var edepth_id 			= "td_depth_"+mbdetailid;
		
		//var earea_id 			= "td_coarea_"+mbdetailid;
		
		var eunit_id 			= "td_unit_"+mbdetailid;
		var eerror_id			= "td_error_"+mbdetailid;
		var dummy_esno_id 		= "dummy_sno_"+mbdetailid;
		var dummy_edate_id 		= "dummy_date_"+mbdetailid;
		var dummy_eitemno_id 	= "dummy_itemno_"+mbdetailid;
		var dummy_edescrip_id 	= "dummy_desc_"+mbdetailid;
		var dummy_enumber_id 	= "dummy_number_"+mbdetailid;
		var dummy_elength_id 	= "dummy_length_"+mbdetailid;
		var dummy_edia_id 		= "dummy_dia_"+mbdetailid;
		//var dummy_edepth_id 	= "dummy_depth_"+mbdetailid;
		var dummy_eunit_id 		= "dummy_unit_"+mbdetailid;
		//var esno 		= $("#"+dummy_esno_id).val().trim();
		var edate 		= $("#"+dummy_edate_id).val().trim();
		var eitemno 	= $("#"+dummy_eitemno_id).val().trim();
		var edescrip 	= $("#"+dummy_edescrip_id).val().trim();
		var enumber 	= $("#"+dummy_enumber_id).val().trim();
		var elength 	= $("#"+dummy_elength_id).val().trim();
		var edia 		= $("#"+dummy_edia_id).val().trim();
		//var edepth 		= $("#"+dummy_edepth_id).val().trim();
		var eunit 		= $("#"+dummy_eunit_id).val().trim();
		var workordername = $("#txt_sheetid").val()
		//alert(eitemno)
		var edepth = "";
		var inputStr = mbheaderid+"@*@"+mbdetailid+"@*@"+edate+"@*@"+eitemno+"@*@"+edescrip+"@*@"+enumber+"@*@"+elength+"@*@"+edia+"@*@"+edepth+"@*@"+eunit;
		$.post('MeasurementUpload_UpdateData_Steel.php', { sheetid: workordername, inputStr: inputStr }, function(data) {
			//alert(data);
			if(data != "")
			{
				var splitdata = data.split("@@");
				var msg = splitdata[2];
				//alert(msg);
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
				$("#"+edia_id).empty();
				$("#"+edia_id).text(edia)
				//$("#"+edepth_id).empty();
				//$("#"+edepth_id).text(edepth)
				$("#"+eunit_id).empty();
				$("#"+eunit_id).text(eunit)
				var url = "MeasurementUpload_View_Steel.php?sheetid="+workordername;
				//$(location).attr('href',url);
				window.location.reload();
			}
		});
		
	}
$(function () {	

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
		  //alert(data);
		  //swal("", data, "success");
		  		swal({
				  title: "",
				  text: data,
				  type: "success",
				  showCancelButton: false,
				  confirmButtonText: " OK ",
				  closeOnConfirm: false,
				  closeOnCancel: false
				},
				function(isConfirm)
				{
				  if (isConfirm) 
				  {
						var url = "MeasurementUpload_View_Steel.php?sheetid="+workordername;
						//$(location).attr('href',url);
						window.location.reload();
				  } 
				  else 
				  {
						var url = "MeasurementUpload_View_Steel.php?sheetid="+workordername;
						//$(location).attr('href',url);;
						window.location.reload();
				  }
				});
		  	//var url = "MeasurementUpload_View_Steel.php?sheetid="+workordername;
			//$(location).attr('href',url);
		  });
		});
		
		$( "#btn_confirm" ).click(function() {
		  var workordername = $("#txt_sheetid").val();
		  var type = "s";
		  if (confirm('Are you sure ?')) 
		  {
		  		$.post('MeasurementUpload_ConfirmData.php', { sheetid: workordername, type: type }, function(data) {
		  		//alert(data);
				//swal("", data, "success");
				swal({
				  title: "",
				  text: data,
				  type: "success",
				  showCancelButton: false,
				  confirmButtonText: " OK ",
				  closeOnConfirm: false,
				  closeOnCancel: false
				},
				function(isConfirm)
				{
				  if (isConfirm) 
				  {
						var url = "MeasurementUpload_View_Steel.php?sheetid="+workordername;
						$(location).attr('href',url);
				  } 
				  else 
				  {
						var url = "MeasurementUpload_View_Steel.php?sheetid="+workordername;
						$(location).attr('href',url);;
				  }
				});
		  		//var url = "MeasurementUpload_View_Steel.php?sheetid="+workordername;
				//$(location).attr('href',url);
		  	});
		  }
		});
		
		$( "#btn_cancel" ).click(function() {
		  var workordername = $("#txt_sheetid").val();
		  var type = "s";
		  var action = "all";
		  if (confirm('Are you sure ?')) 
		  {
		  		$.post('MeasurementUpload_DeleteData.php', { sheetid: workordername, type: type, action: action }, function(data) {
		  		//alert(data);
				//swal("", data, "success");
				swal({
				  title: "",
				  text: data,
				  type: "success",
				  showCancelButton: false,
				  confirmButtonText: " OK ",
				  closeOnConfirm: false,
				  closeOnCancel: false
				},
				function(isConfirm)
				{
				  if (isConfirm) 
				  {
						var url = "MeasurementUpload.php";
						$(location).attr('href',url);
				  } 
				  else 
				  {
						var url = "MeasurementUpload.php";
						$(location).attr('href',url);;
				  }
				});
		  		//var url = "MeasurementUpload_View.php";
				//$(location).attr('href',url);
		  	});
		  }
		});
		
});
function ConfirmNewItemNo()
{
	swal({
	  title: 'Are you sure?',
	  text: "You want to create new item for the selected rows!",
	  type: '',
	  showCancelButton: true,
	  html:true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: ' Create '
	},
	function(isConfirm)
	{
		if (isConfirm) 
		{
			//var url = "ExtraItemCreation.php";
			//$("form").submit();
			$("form").submit();
		} 
	});
}

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
	.contentdata
	{
		/*height:505px;*/
		overflow:auto;
	}
	.snotextbox
	{ 	
		width:32px; 
    	position: relative;
    	outline: none;
    	border: 1px solid #98D8FE;
   		/* border-color: rgba(0,0,0,.15);*/
    	background-color: white;
		color:#0000cc;
		font-family:Verdana, Arial, Helvetica, sans-serif;
  	}
	.datetextbox
	{ 
		/*width:68px;*/ 
    	position: relative;
    	outline: none;
    	border: 1px solid #98D8FE;
   		/* border-color: rgba(0,0,0,.15);*/
    	background-color: white;
		color:#0000cc;
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	.itemnotextbox
	{ 
		/*width:50px;*/ 
    	position: relative;
    	outline: none;
    	border: 1px solid #98D8FE;
   		/* border-color: rgba(0,0,0,.15);*/
    	background-color: white;
		color:#0000cc;
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	.desctextbox
	{ 
		/*width:285px;*/ 
		position: relative;
    	outline: none;
    	border: 1px solid #98D8FE;
   		/* border-color: rgba(0,0,0,.15);*/
    	background-color: white;
		color:#0000cc;
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	.notextbox
	{ 
		width:25px; 
		position: relative;
    	outline: none;
    	border: 1px solid #98D8FE;
   		/* border-color: rgba(0,0,0,.15);*/
    	background-color: white;
		color:#0000cc;
		font-family:Verdana, Arial, Helvetica, sans-serif; 
	}
	.lentextbox
	{ 
		width:52px; 
		position: relative;
    	outline: none;
    	border: 1px solid #98D8FE;
   		/* border-color: rgba(0,0,0,.15);*/
    	background-color: white;
		color:#0000cc;
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	.bretextbox
	{ 
		width:52px; 
		position: relative;
    	outline: none;
    	border: 1px solid #98D8FE;
   		/* border-color: rgba(0,0,0,.15);*/
    	background-color: white;
		color:#0000cc;
		font-family:Verdana, Arial, Helvetica, sans-serif; 
	}
	.deptextbox
	{ 
		width:52px; 
		position: relative;
    	outline: none;
    	border: 1px solid #98D8FE;
   		/* border-color: rgba(0,0,0,.15);*/
    	background-color: white;
		color:#0000cc;
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	.unitextbox	
	{ 
		width:40px; 
		position: relative;
    	outline: none;
    	border: 1px solid #98D8FE;
   		/* border-color: rgba(0,0,0,.15);*/
    	background-color: white;
		color:#0000cc;
		font-family:Verdana, Arial, Helvetica, sans-serif; 
	}
	.ErrBox{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		color:red;
		font-weight:bold;
		background:#F7F7F7;
		padding:8px;
		margin-top:15px;
		border:1px solid #FFA8AE;
		font-size:13px;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="ExtraItemCreation.php" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                            <div class="title">Measurement Upload - View ( Steel )</div>
                <div class="container_12">
                    <div class="grid_12" align="center">
                        <blockquote class="bq1" style="overflow:auto">
							<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $_GET['sheetid'];?>">
							
							<!--<table width="1078px" align="center" cellpadding="3" cellspacing="3" class="">
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
												
											<?php	
											}
											?>
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
										<td  height="30px;" colspan="4" align="center"><input type="button" class="backbutton" id="btn_view" value=" View "></td>
									</tr>
									<tr>
										<td width="">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
							</table>-->
							<?php
							if($sheetdetail_query == true)
							{
							?>
							<br/>
							<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3" class="table1">
								<tr>
									<td class="label">Name of Work</td>
									<td class="labelhead"><?php echo $workname; ?></td>
								</tr>
								<tr>
									<td class="label">Short - Name of Work</td>
									<td class="labelhead"><?php echo $workshortname; ?></td>
								</tr>
								<tr>
									<td class="label">Work Order No.</td>
									<td class="labelhead"><?php echo $workorderno; ?></td>
								</tr>
								<tr>
									<td class="label">Agreement No.</td>
									<td class="labelhead"><?php echo $agreementno; ?></td>
								</tr>
							</table>
							<?php
							}
							?>
							
				<?php 
				if($getmeasurement_query == true)
				{
				?>
					<div class="contentdata" id="contentdata">
							<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3" class="table1">
								<tr height="28px">
									<td rowspan="2" class="label" align="center" valign="middle"><input type="checkbox" name="check_all" id="check_all"></td>
									<td rowspan="2" class="label" align="center">S.No.</td>
									<td rowspan="2" class="label" align="center">Date</td>
									<td rowspan="2" class="label" align="center">Item No.</td>
									<td rowspan="2" class="label" align="center">Description</td>
									<td rowspan="2" class="label" align="center">Dia <br/>of<br/>rod</td>
									<td rowspan="2" class="label" align="center">No.</td>
									<td rowspan="2" class="label" align="center">No.</td>
									<td rowspan="2" class="label" align="center">Length</td>
									<td class="label" align="center" colspan="9">Total Length im Meters</td>
									<td rowspan="2" class="label" align="center">Unit</td>
									<td rowspan="2" class="label" align="center" width="">Remarks</td>
									<td rowspan="2" class="label" align="center" colspan="2">Operation</td>
								</tr>
								<tr>
									<td class="label" align="center">8</td>
									<td class="label" align="center">10</td>
									<td class="label" align="center">12</td>
									<td class="label" align="center">16</td>
									<td class="label" align="center">20</td>
									<td class="label" align="center">25</td>
									<td class="label" align="center">28</td>
									<td class="label" align="center">32</td>
									<td class="label" align="center">36</td>
								</tr>
				<?php
					$DiaArr = array(8,10,12,16,20,25,28,32,36); $SteelErr = 0; $DiaErr = 0; $ItemTypExcErr = 0;
					if(mysql_num_rows($getmeasurement_query)>0)
					{
						$sno = 1; $QtyCheck = array(); $ItemCheck = array();
						while($MList = mysql_fetch_object($getmeasurement_query))
						{
							$Mbdetailid 	= $MList->mbdetail_id;
							$Mbheaderid		= $MList->mbheaderid;
							$MidStr			= $Mbheaderid."*".$Mbdetailid;
							$Mdate 			= $MList->mdate;
							$subdivid		= $MList->subdivid;
							$MItemno 		= $MList->subdiv_name;
							$MDescription 	= $MList->descwork;
							$MNumber 		= $MList->measurement_no;
							$MNumber2 		= $MList->measurement_no2;
							$MLength 		= $MList->measurement_l;
							$MBreadth 		= $MList->measurement_b;
							$MDepth 		= $MList->measurement_d;
							$MStDepthUnit 	= $MList->structdepth_unit;
							$MDia 			= $MList->measurement_dia;
							$MConArea 		= $MList->measurement_contentarea;
							$MUnit 			= $MList->remarks;
							$MError 		= $MList->mbdetail_flag;
							$MSubType 		= $ItemSubTypeArr[$MItemno];//$MList->sub_type;
							$experror		= explode("@@@",$MError);
							$ErrorItemNo	= $experror[0];
							$DateError		= $experror[1];
							$ItemError		= $experror[2];
							$QtyError		= $experror[3];
							$MaxdtError		= $experror[4];
							$DiaErrStyle = '';
							if($MDia != ''){
								if(in_array($MDia,$DiaArr)){
									
								}else{
									$DiaErr++; $SteelErr++; 
									$DiaErrStyle = 'style="background:#FA3941; color:white"';
								}
							}
							$IETErrStyle = '';
							if($subdivid != 0){
								if(in_array($subdivid,$MasSubDivIdArr)){
									
								}else{
									$ItemTypExcErr++; $SteelErr++; 
									$IETErrStyle = 'style="background:#FA3941; color:white"';
								}
							}
							//echo $MError."</br>";
							if($MDia == 8) { $content_area_8  = $MConArea;  $totweight_8  = $content_area_8 * 0.395; } else { $totweight_8 = 0; $content_area_8  = ""; }
							if($MDia == 10){ $content_area_10 = $MConArea;  $totweight_10 = $content_area_10 * 0.617;  } else { $totweight_10 = 0; $content_area_10 = ""; }
							if($MDia == 12){ $content_area_12 = $MConArea;  $totweight_12 = $content_area_12 * 0.888;  } else { $totweight_12 = 0; $content_area_12 = ""; }
							if($MDia == 16){ $content_area_16 = $MConArea;  $totweight_16 = $content_area_16 * 1.578;  } else { $totweight_16 = 0; $content_area_16 = ""; }
							if($MDia == 20){ $content_area_20 = $MConArea;  $totweight_20 = $content_area_20 * 2.466;  } else { $totweight_20 = 0; $content_area_20 = ""; }
							if($MDia == 25){ $content_area_25 = $MConArea;  $totweight_25 = $content_area_25 * 3.853;  } else { $totweight_25 = 0; $content_area_25 = ""; }
							if($MDia == 28){ $content_area_28 = $MConArea;  $totweight_28 = $content_area_28 * 4.834;  } else { $totweight_28 = 0; $content_area_28 = ""; }
							if($MDia == 32){ $content_area_32 = $MConArea;  $totweight_32 = $content_area_32 * 6.313;  } else { $totweight_32 = 0; $content_area_32 = ""; }
							if($MDia == 36){ $content_area_36 = $MConArea;  $totweight_36 = $content_area_36 * 7.990;  } else { $totweight_36 = 0; $content_area_36 = ""; }
							$total_weight_kg = $totweight_8+$totweight_10+$totweight_12+$totweight_16+$totweight_20+$totweight_25+$totweight_28+$totweight_32+$totweight_36;
							$total_weight_mt = $total_weight_kg/1000;
							$eno = 1;
							$ItemErr = "";
							if($MSubType == 'c')
							{
								$QtyStr			.=	$subdivid."*".$MConArea."*";
							}
							else
							{
								$QtyStr			.=	$subdivid."*".$total_weight_mt."*";
							}
							if(($DateError == "") && ($ItemError == "") && ($QtyError == "") && ($MaxdtError == ""))
							{
								$checkbox = 0;
							}
							else
							{
								$checkbox = 1;
								$checkboxcount++;
							}
							
							$BgStyle = '';
							$MdateTemp = str_replace('/', '-', $Mdate);
							if(strtotime($MdateTemp) > strtotime($ToDay)){
								$FutDateErr++; $SteelErr++;
								$BgStyle = 'style="background:#FA3941; color:white;"';
							}
							
							if(strtotime($WoDate) > strtotime($MdateTemp)){
								$WoDateErr++; $SteelErr++;
								$BgStyle = 'style="background:#FA3941; color:white;"';
							}
							
							
							//echo $QtyStr."<br/>";
							//$QtyCheck[$MItemno] = $MConArea;
							//$ItemCheck[$MItemno] = $MItemno;
							//array_push($QtyCheck,$MConArea);
							//array_push($ItemCheck,$MItemno);
							//$MUnit 			= $UnitList[$MItemno];
							$usedQty_now 	= CheckExceededQty($QtyStr,$subdivid);
							//echo $total_weight_kg."@==@".$total_weight_mt."@==@".$usedQty_now."<br/>";
							$usedQty_old  	= $UsedQty[$MItemno];
							$OverAllUsedQty = $usedQty_now+$usedQty_old;
							$TotalWoQty 	= $ItemQty[$MItemno];
							//echo $OverAllUsedQty."=".$usedQty_now." + ".$usedQty_old."@@".$TotalWoQty."@@".$MConArea."<br/>";
							if($OverAllUsedQty > $TotalWoQty)
							{
								$Exceeded = 1;
								//echo $TotalWoQty;exit;
							}
							else
							{
								$Exceeded = 0;
								
							}
							
							if($ItemError != "")
							{
								$ItemErr = $eno.". ".$ItemError."<br/>";
								$eno++;
							}
							if($DateError != "")
							{
								$ItemErr = $ItemErr.$eno.". ".$DateError."<br/>";
								$eno++;
							}
							if($QtyError != "")
							{
								$ItemErr = $ItemErr.$eno.". ".$QtyError."<br/>";
								$eno++;
							}
							if($MaxdtError != "")
							{
								$ItemErr = $ItemErr.$eno.". ".$MaxdtError."<br/>";
								$eno++;
							}
							if(($Exceeded == 1) && ($QtyError == ""))
							{ 
								$color = "red";
								//echo "efviewfgyewfye";
								$QtyError = "Quantity Exceeded.";//"Quantity is Exceeded than the work order Quantity.";
								$ItemErr = $ItemErr.$eno.". ".$QtyError."<br/>"; 
								$Errorstr =  $MItemno."@@@".$DateError."@@@".$ItemError."@@@".$QtyError."@@@".$MaxdtError;
								UpdateExceededError($Mbdetailid,$sheetid,$Errorstr);
								$eno++;
							}
							if($eno != 1) 
							{ 
								$color = "red"; 
							} 
							else 
							{ 
								$color = "#0B29B9"; 
							}
				?>
								<tr class="labelhead" style="color:<?php echo $color; ?>" id="<?php echo $Mbdetailid; ?>">
									<td align="center" 	id="td_check_box_<?php echo $Mbdetailid; ?>">
									<?php if($checkbox == 1){ ?>
									<input type="checkbox" name="check_single[]" id="check_single" class="check" value="<?php echo $MidStr; ?>">
									<?php } ?>
									</td>
									<td align="center" 	id="td_sno_<?php echo $Mbdetailid; ?>" >
									<?php echo $sno; ?>
		<input type="hidden" name="txt_sno_<?php echo $Mbdetailid; ?>" id="txt_sno_<?php echo $Mbdetailid; ?>" value="<?php echo $sno; ?>">
									</td>
									
									
									<td align="left" 	id="td_date_<?php echo $Mbdetailid; ?>" <?php echo $BgStyle ; ?>>
									<?php echo $Mdate; ?>
		<input type="hidden" name="txt_date_<?php echo $Mbdetailid; ?>" id="txt_date_<?php echo $Mbdetailid; ?>" value="<?php echo $Mdate; ?>">							
									</td>
									
									
									<td align="center" 	id="td_itemno_<?php echo $Mbdetailid; ?>" <?php echo $IETErrStyle; ?>>
									<?php echo $MItemno; ?>
		<input type="hidden" name="txt_itemno_<?php echo $Mbdetailid; ?>" id="txt_itemno_<?php echo $Mbdetailid; ?>" value="<?php echo $MItemno; ?>">							
									</td>
									
									
									<td align="left" 	id="td_descrip_<?php echo $Mbdetailid; ?>">
									<?php echo $MDescription; ?>
		<input type="hidden" name="txt_descrip_<?php echo $Mbdetailid; ?>" id="txt_descrip_<?php echo $Mbdetailid; ?>" value="<?php echo $MDescription; ?>">							
									</td>
									
									<td align="right" 	id="td_dia_<?php echo $Mbdetailid; ?>" <?php echo $DiaErrStyle; ?>>
									<?php if(($MDia != "") && ($MDia != 0)){ echo $MDia; } ?>
		<input type="hidden" name="txt_dia_<?php echo $Mbdetailid; ?>" id="txt_dia_<?php echo $Mbdetailid; ?>" value="<?php echo $MBreadth; ?>">							
									</td>
									
									<td align="right" 	id="td_number2_<?php echo $Mbdetailid; ?>">
									<?php if(($MNumber2 != "") && ($MNumber2 != 0)){ echo $MNumber2; } ?>
		<input type="hidden" name="txt_number_<?php echo $Mbdetailid; ?>" id="txt_number_<?php echo $Mbdetailid; ?>" value="<?php echo $MNumber2; ?>">							
									</td>
									
									
									<td align="right" 	id="td_number_<?php echo $Mbdetailid; ?>">
									<?php if(($MNumber != "") && ($MNumber != 0)){ echo $MNumber; } ?>
		<input type="hidden" name="txt_number_<?php echo $Mbdetailid; ?>" id="txt_number_<?php echo $Mbdetailid; ?>" value="<?php echo $MNumber; ?>">							
									</td>
									
									
									
									<td align="right" 	id="td_length_<?php echo $Mbdetailid; ?>">
									<?php if(($MLength != "") && ($MLength != 0)){ echo $MLength; } ?>
		<input type="hidden" name="txt_length_<?php echo $Mbdetailid; ?>" id="txt_length_<?php echo $Mbdetailid; ?>" value="<?php echo $MLength; ?>">							
									</td>
									
									
									<td align="right" 	id="td_content_area_8_<?php echo $Mbdetailid; ?>">
									<?php echo $content_area_8; ?>
		<input type="hidden" name="txt_content_area_8_<?php echo $Mbdetailid; ?>" id="txt_content_area_8_<?php echo $Mbdetailid; ?>" value="<?php echo $MBreadth; ?>">							
									</td>
									
									<td align="right" 	id="td_content_area_10_<?php echo $Mbdetailid; ?>">
									<?php echo $content_area_10; ?>
		<input type="hidden" name="txt_content_area_10_<?php echo $Mbdetailid; ?>" id="txt_content_area_10_<?php echo $Mbdetailid; ?>" value="<?php echo $MBreadth; ?>">							
									</td>
									
									<td align="right" 	id="td_content_area_12_<?php echo $Mbdetailid; ?>">
									<?php echo $content_area_12; ?>
		<input type="hidden" name="txt_content_area_12_<?php echo $Mbdetailid; ?>" id="txt_content_area_12_<?php echo $Mbdetailid; ?>" value="<?php echo $MBreadth; ?>">							
									</td>
									
									<td align="right" 	id="td_content_area_16_<?php echo $Mbdetailid; ?>">
									<?php echo $content_area_16; ?>
		<input type="hidden" name="txt_content_area_16_<?php echo $Mbdetailid; ?>" id="txt_content_area_16_<?php echo $Mbdetailid; ?>" value="<?php echo $MBreadth; ?>">							
									</td>
									
									<td align="right" 	id="td_content_area_20_<?php echo $Mbdetailid; ?>">
									<?php echo $content_area_20; ?>
		<input type="hidden" name="txt_content_area_20_<?php echo $Mbdetailid; ?>" id="txt_content_area_20_<?php echo $Mbdetailid; ?>" value="<?php echo $MBreadth; ?>">							
									</td>
									
									<td align="right" 	id="td_content_area_25_<?php echo $Mbdetailid; ?>">
									<?php echo $content_area_25; ?>
		<input type="hidden" name="txt_content_area_25_<?php echo $Mbdetailid; ?>" id="txt_content_area_25_<?php echo $Mbdetailid; ?>" value="<?php echo $MBreadth; ?>">							
									</td>
									
									<td align="right" 	id="td_content_area_28_<?php echo $Mbdetailid; ?>">
									<?php echo $content_area_28; ?>
		<input type="hidden" name="txt_content_area_28_<?php echo $Mbdetailid; ?>" id="txt_content_area_28_<?php echo $Mbdetailid; ?>" value="<?php echo $MBreadth; ?>">							
									</td>
									
									<td align="right" 	id="td_content_area_32_<?php echo $Mbdetailid; ?>">
									<?php echo $content_area_32; ?>
		<input type="hidden" name="txt_content_area_32_<?php echo $Mbdetailid; ?>" id="txt_content_area_32_<?php echo $Mbdetailid; ?>" value="<?php echo $MBreadth; ?>">							
									</td>
									
									<td align="right" 	id="td_content_area_36_<?php echo $Mbdetailid; ?>">
									<?php echo $content_area_36; ?>
		<input type="hidden" name="txt_content_area_36_<?php echo $Mbdetailid; ?>" id="txt_content_area_36_<?php echo $Mbdetailid; ?>" value="<?php echo $MDepth; ?>">							
									</td>
									
									
									
									
									<td align="left" 	id="td_unit_<?php echo $Mbdetailid; ?>">
									<?php echo $MUnit; ?>
		<input type="hidden" name="txt_unit_<?php echo $Mbdetailid; ?>" id="txt_unit_<?php echo $Mbdetailid; ?>" value="<?php echo $MUnit; ?>">							
									</td>
									
									
									<td align="left" 	id="td_error_<?php echo $Mbdetailid; ?>">
									<?php echo $ItemErr; ?>
		<input type="hidden" name="txt_error_<?php echo $Mbdetailid; ?>" id="txt_error_<?php echo $Mbdetailid; ?>" value="<?php echo $ItemErr; ?>">							
									</td>
									
									
									<td align="center" id="edit_img_<?php echo $Mbdetailid; ?>">
										<?php if($eno != 1){ ?>
										<img src="images/edit.png" class="edit" id="<?php echo $MidStr."*edit"; ?>" width="24" height="24" >
										<?php } else { ?>
										<!--<img src="images/edit.png" class="edit" id="<?php echo $MidStr."*edit"; ?>" width="24" height="24" >-->
										<?php } ?>
									</td>
									<td class="hide" align="center" id="update_img_<?php echo $Mbdetailid; ?>">
										<?php if($eno != 1){ ?>
										<img src="images/update.png" class="update" id="<?php echo $MidStr."*update"; ?>" width="24" height="24">
										<?php } else { ?>
										<!--<img src="images/update.png" class="update" id="<?php echo $MidStr."*update"; ?>" width="24" height="24">-->
										<?php } ?>
									</td>

									<td class="label" align="center">
									<?php if($eno != 1){ ?>
									<img src="images/delete.png" class="delete" id="<?php echo $MidStr; ?>" width="20" height="20">
									<?php } else { ?>
									<!--<img src="images/delete.png" class="delete" id="<?php echo $MidStr; ?>" width="20" height="20">-->
									<?php } ?>
									</td>
								</tr>
								<input type="hidden" name="txt_error_str_<?php echo $Mbdetailid; ?>" id="txt_error_str_<?php echo $Mbdetailid; ?>" value="<?php echo $MError; ?>">
				<?	
							$sno++;
						}
						//print_r("Arr3);
					}
				}
				?>
							
							</table>
						<input type="hidden" name="txt_checkbox_count" id="txt_checkbox_count" value="<?php echo $checkboxcount; ?>">
						</div>
						<?php if($WoDateErr > 0){ ?>
						<div style="text-align:left" class="ErrBox">
							<i style="font-size:24px; color:#FD0013" class="fa">&#xf056;</i> 
							Measurement Date should be less than Work Order Date.
						</div>
						<?php } ?>
						<?php if($FutDateErr > 0){ ?>
						<div style="text-align:left" class="ErrBox">
							<i style="font-size:24px; color:#FD0013" class="fa">&#xf056;</i> 
							Measurement Date should be less than or equal to Today Date. Future Date will not be accepted. Please 'Cancel' your measurements and re-upload.
						</div>
						<?php } ?>
						<?php if($DiaErr > 0){ ?>
						<div style="text-align:left" class="ErrBox">
							<i style="font-size:24px; color:#FD0013" class="fa">&#xf056;</i> 
							Invalid Dia exists. Please 'Cancel' your measurements and re-upload.
						</div>
						<?php } ?>
						<?php if($ItemTypExcErr > 0){ ?>
						<div style="text-align:left" class="ErrBox">
							<i style="font-size:24px; color:#FD0013" class="fa">&#xf056;</i> 
							General Item exists in steel measurements. Please 'Cancel' your measurements and re-upload.
						</div>
						<?php } ?>
						<div style="text-align:center">
							<div class="buttonsection" style="display:inline-table">
								<input type="button" class="backbutton" name="btn_back" value=" Back " id="btn_back" onClick="goBack();"/>
							</div>
							<?php if($SteelErr == 0){ ?>
							<div class="buttonsection" style="display:inline-table">
								<input type="button" class="backbutton" name="btn_confirm" id="btn_confirm" value=" Confirm ">
							</div>
							<?php } ?>
							<div class="buttonsection" style="display:inline-table">
								<input type="button" class="backbutton" name="btn_cancel" value=" Cancel " id="btn_cancel"/>
							</div>
							<?php if($SteelErr == 0){ ?>
							<div class="buttonsection" style="display:inline-table; width:140px;">
								<input type="button" class="backbutton" name="btn_new_item" value="Addl. Qty Beyond the Deviation Limit" id="btn_new_item" onClick="ConfirmNewItemNo();"/>
							</div>
							<?php } ?>
						</div>
							<!--<table width="1070px" border="0" align="center" cellpadding="3" cellspacing="3" class="">
								<tr><td>&nbsp;</td></tr>
								<tr height="30px">
									<td align="center"></td>
								</tr>
							</table>-->
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
	
	var checkboxcount = $('#txt_checkbox_count').val();
	if(checkboxcount == 0)
	{
		$("#check_all").attr("disabled", true);
	}
	else
	{
		$("#check_all").removeAttr("disabled");
	}
	
	$("#check_all").click(function () {
		  $('.check').attr('checked', this.checked);
	});
	
});
function AutoResizeContentData()
{
	var PageHeight = document.getElementById('bq1').offsetHeight;
	var contentheight = Number(PageHeight)-200;
	document.getElementById('contentdata').setAttribute("style","height:"+contentheight+"px");
}
window.onresize = AutoResizeContentData;
AutoResizeContentData();
</script>
    </body>
</html>
