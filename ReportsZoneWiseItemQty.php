<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
checkUser();
$msg = '';
$newmbookno='';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
$mbooktype = "G";
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
	$sheetid 	= $_POST['cmb_workshortname'];
	$ReportType = $_POST['rad_report_type'];
	$rbn 		= $_POST['cmb_rbn'];
	
	if($ReportType == "OTRAB")
	{
		$whereClause = " and rbn = '$rbn'";
		$whereClause1 = " and mbookgenerate_staff.rbn = '$rbn'";
	}
	else if($ReportType == "UTRAB")
	{
		$whereClause = " and rbn <= '$rbn'";
		$whereClause1 = " and mbookgenerate_staff.rbn <= '$rbn'";
	}
	else
	{
		$whereClause = "";
		$whereClause1 = "";
	}
}
$query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery 	= 	mysql_query($query);
if ($sqlquery == true) 
{
    $List 				= 	mysql_fetch_object($sqlquery);
    $work_name 			= 	$List->work_name;    
	$tech_sanction 		= 	$List->tech_sanction;
    $name_contractor 	= 	$List->name_contractor;    
	$agree_no 			= 	$List->agree_no; 
	$work_order_no 		= 	$List->work_order_no; 
	$ccno 				= 	$List->computer_code_no;
	$runn_acc_bill_no = $rbn;
}

$length 	= 	strlen($work_name);
$start_line = 	ceil($length/87);
$ZoneArr = array();
$ZnameArr = array();
$ItemIdArr = array();
$ItemNoArr = array();
$select_zone_query 	= 	"select zone_id, zone_name from zone where sheetid = '$sheetid' ORDER BY zone_name ASC";
$select_zone_sql 	= 	mysql_query($select_zone_query);
if($select_zone_sql == true)
{
	if(mysql_num_rows($select_zone_sql)>0)
	{
		while($ZoneList = mysql_fetch_object($select_zone_sql))
		{
			$zone = $ZoneList->zone_id;
			$zname = $ZoneList->zone_name;
			array_push($ZoneArr,$zone);
			array_push($ZnameArr,$zname);
		}
	}
}
$zone_count = count($ZoneArr);
if($zone_count>0)
{
	$col_span_count_1 = $zone_count+1;
	$col_span_count_2 = $zone_count;
}
$select_item_query	= "select DISTINCT mbookgenerate_staff.subdivid, subdivision.subdiv_name FROM mbookgenerate_staff 
							INNER JOIN subdivision ON (subdivision.subdiv_id = mbookgenerate_staff.subdivid)
							WHERE mbookgenerate_staff.sheetid = '$sheetid' AND subdivision.sheet_id = '$sheetid' ".$whereClause1." 
							ORDER BY mbookgenerate_staff.subdivid ASC";
							//echo $select_item_query;exit;
$select_item_sql = mysql_query($select_item_query);
if($select_item_sql == true)
{
	if(mysql_num_rows($select_item_sql)>0)
	{
		while($ItemList = mysql_fetch_object($select_item_sql))
		{
			$itemid = $ItemList->subdivid;
			$itemname = $ItemList->subdiv_name;
			array_push($ItemIdArr,$itemid);
			array_push($ItemNoArr,$itemname);
		}
	}
}
$item_count = count($ItemIdArr);
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack(){
		url = "ReportsZoneWiseItemQtyGenerate.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack(){ window.history.forward(); }
</script>

    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
		<div class="content">
            <div class="title">Report - Zone / Building Wise Utilized Qty. </div>
			<div class="container_12">
				<div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="ReportsZoneWiseItemQty.php">
                            <div class="container">
								<div class="row">
									<div class="div12 grid-empty"></div>
									<div class="div1" align="center">&nbsp;</div>
									<div class="div10" align="center">
										<div class="innerdiv2">
											<div class="row divhead head-b" align="center">Zone / Building Wise Utilized Qty. </div>
											<div class="row innerdiv group-div" align="center" style="overflow:scroll">
											<?php
											//$table2 = "<table width='100%' align='center' bgcolor='#ffffff' class='table table-bordered dataTable no-footer'>";
											$table2 = $table2 . "<tr>";;
											$table2 = $table2 . "<td nowrap='nowrap' class='label' colspan='2'>Name of work</td>";
											$table2 = $table2 . "<td class='label' colspan='".($zone_count+2)."'>" . $work_name . "</td>";
											$table2 = $table2 . "</tr>";
											$table2 = $table2 . "<tr>";
											$table2 = $table2 . "<td nowrap='nowrap' class='label' valign='top' colspan='2'>Technical Sanction No.</td>";
											$table2 = $table2 . "<td class='label' colspan='".($zone_count+2)."'> " . $tech_sanction . "</td>";
											$table2 = $table2 . "</tr>";
											$table2 = $table2 . "<tr>";
											$table2 = $table2 . "<td nowrap='nowrap' class='label' valign='top' colspan='2'>Name of the contractor</td>";
											$table2 = $table2 . "<td class='label' colspan='".($zone_count+2)."'>" . $name_contractor . "</td>";
											$table2 = $table2 . "</tr>";
											$table2 = $table2 . "<tr>";
											$table2 = $table2 . "<td nowrap='nowrap' class='label' valign='top' colspan='2'>Agreement No.</td>";
											$table2 = $table2 . "<td class='label' colspan='".($zone_count+2)."'>" . $agree_no . "</td>";
											$table2 = $table2 . "</tr>";
											$table2 = $table2 . "<tr>";
											$table2 = $table2 . "<td nowrap='nowrap' class='label' valign='top' colspan='2'>Work Order No.</td>";
											$table2 = $table2 . "<td class='label' colspan='".($zone_count+2)."'>" . $work_order_no . "</td>";
											$table2 = $table2 . "</tr>";
											$table2 = $table2 . "<tr>";
											$table2 = $table2 . "<td nowrap='nowrap' class='label' valign='top' colspan='2'>Running Account bill No.</td>";
											$table2 = $table2 . "<td class='label'>" . $runn_acc_bill_no . "</td>";
											$table2 = $table2 . "<td class='label' align='right'>CC No.</td>";
											$table2 = $table2 . "<td class='label' colspan='".$zone_count."'>" . $ccno . "</td>";
											$table2 = $table2 . "</tr>";
											//$table2 = $table2 . "</table>";
											
											//$table = "<table width='100%' border='0'  cellpadding='3' cellspacing='3' align='center' bgcolor='#ffffff'>";
											$table = $table . "<tr>";;
											$table = $table . "<td nowrap='nowrap' class='label' colspan='2'>Name of work:</td>";
											$table = $table . "<td width='' class='label' colspan='".($zone_count+2)."'>" . $work_name . "</td>";
											$table = $table . "</tr>";
											$table = $table . "<tr>";
											$table = $table . "<td nowrap='nowrap' class='label' valign='top' colspan='2'>Name of the contractor</td>";
											$table = $table . "<td class='label' colspan='".($zone_count+2)."'>" . $name_contractor . "</td>";
											$table = $table . "</tr>";
											$table = $table . "<tr>";
											$table = $table . "<td nowrap='nowrap' class='label' valign='top' colspan='2'>Agreement No.</td>";
											$table = $table . "<td class='label' colspan='".($zone_count+2)."'>" . $agree_no . "</td>";
											$table = $table . "</tr>";
											$table = $table . "<tr>";
											$table = $table . "<td nowrap='nowrap' class='label' valign='top' colspan='2'>Running Account bill No.</td>";
											$table = $table . "<td class='label'>" . $runn_acc_bill_no . "</td>";
											$table = $table . "<td class='label' align='right'>CC No.</td>";
											$table = $table . "<td class='label' colspan='".$zone_count."'>" . $ccno . "</td>";
											$table = $table . "</tr>";
											//$table = $table . "</table>";
										   
											$table1 = $table1 . "<tr height='25' bgcolor='#DEE4E7' class='headr'>";
											$table1 = $table1 . "<td rowspan='2' class='labelcenter labelheadblue' nowrap='nowrap'>Item No.</td>";
											$table1 = $table1 . "<td rowspan='2' class='labelcenter labelheadblue desch' nowrap='nowrap'>Item Description</td>";
											$table1 = $table1 . "<td colspan='".$col_span_count_1."' class='labelcenter labelheadblue' style='text-align:center'> Zone / Building Wise Measurements Upto Date</td>";
											$table1 = $table1 . "<td rowspan='2' class='labelcenter labelheadblue' align='center'>Per</td>";
											$table1 = $table1 . "</tr>";
											$table1 = $table1 . "<tr height='25' bgcolor='#DEE4E7' class='headr'>";
											for($zc1=0; $zc1<$zone_count; $zc1++){
												$zc2 = 0;
												$table1 = $table1 . "<td width='35' class='labelcenter labelheadblue' align='center'>".$ZnameArr[$zc1]."</td>";
											}
											$table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>Total Qty</td>";
											$table1 = $table1 . "</tr>";
											//echo $table2; 
											?>
											<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid; ?>" id="txt_mbno_id" />
											<table width='100%' align='center' bgcolor='#ffffff' class='table table-bordered dataTable no-footer' id="ReportTable">
											<?php echo $table2; ?>
											<?php echo $table1; ?>
											<?php
												for($zc3=0; $zc3<$item_count; $zc3++)
												{
													$subdivid = $ItemIdArr[$zc3];
													$subdivname = $ItemNoArr[$zc3]; //echo $subdivname ."<br/>";
													$select_desc_query = "select description, shortnotes, per, decimal_placed from schdule where sheet_id = '$sheetid' and subdiv_id = '$subdivid'";
													$select_desc_sql = mysql_query($select_desc_query);
													if($select_desc_sql == true)
													{
														if(mysql_num_rows($select_desc_sql)>0)
														{
															$DescList 			= mysql_fetch_object($select_desc_sql);
															$item_description 	= $DescList->description;
															$item_shortnotes 	= $DescList->shortnotes;
															$item_unit 			= $DescList->per;
															$decimal_placed 	= $DescList->decimal_placed;
															if($item_shortnotes != "")
															{
																$item_description = $item_shortnotes;
															}
														}
													}
													echo "<tr>";
													echo "<td align='center'>".$subdivname."</td>";
													echo "<td class='desch' align='justify'>".$item_description."</td>";
													$total_item_qty = 0;
													$QtyArr = array();
													$MBookCurrArr = array();
													$MBPageCurrArr = array();
													$select_item_detail_query = "select * from mbookgenerate_staff where sheetid = '$sheetid' and subdivid = '$subdivid' ".$whereClause;
													$select_item_detail_sql = mysql_query($select_item_detail_query);
													if($select_item_detail_sql == true)
													{
														if(mysql_num_rows($select_item_detail_sql)>0)
														{
															while($ItemData = mysql_fetch_object($select_item_detail_sql))
															{
																$zone_id = $ItemData->zone_id;
																$item_qty = $ItemData->mbtotal;
																$mbno = $ItemData->mbno;
																$mbpage = $ItemData->mbpage;
																$total_item_qty = $total_item_qty+$item_qty;
																$QtyArr[$zone_id] = $QtyArr[$zone_id] + $item_qty;
																$MBookCurrArr[$zone_id] = $mbno;
																$MBPageCurrArr[$zone_id] = $mbpage;
															}
															for($zc4=0; $zc4<$zone_count; $zc4++)
															{
																$zone_curr = $ZoneArr[$zc4];
																if($QtyArr[$zone_curr] != 0){
																	echo "<td align='right'>".number_format($QtyArr[$zone_curr],$decimal_placed,".",",")."</td>";
																}else{
																	echo "<td align='right'>&nbsp;</td>";
																}
																if($QtyArr[$zone_curr] != "")
																{
																	$mb_page_format = $MBPageCurrArr[$zone_curr]."/".$MBookCurrArr[$zone_curr];
																}
																else
																{
																	$mb_page_format = "";
																}
															}
														}
														
													}
													$total_item_qty = round($total_item_qty,$decimal_placed);  //number_format($contentarea,$prev_decimal,".",",");
													$schduledetails = getschduledetails($sheetid,$subdivid);
													$rateandremarks = explode('*',$schduledetails);
													echo "<td align='right'><b>".number_format($total_item_qty,$decimal_placed,".",",")."</b></td>";
													echo "<td align='center'>".$rateandremarks[1]."</td>";
													echo "</tr>";
												}
											?>
											</table>
											</div>
											<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
												<div class="buttonsection">
												<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> 
												</div>
												<div class="buttonsection">
												<input type="button" name="exportToExcel" value="Export Excel" id="exportToExcel" class="backbutton" /> 
												</div>
											</div>
										</div>
									</div>
									<div class="div1" align="center">&nbsp;</div>
								</div>
							</div>
     					</form>
   					</blockquote>
  				</div>

  			</div>
		</div>
         <!--==============================footer=================================-->
        <?php include "footer/footer.html"; ?>
<script>
	$("#cmb_workshortname").chosen();
	$("#cmb_rbn").chosen();
    $(function() {
		$.fn.validatembooktype = function(event) {	
			if($("#cmb_mbook_type").val()==""){ 
				var a = "Please select the Measurement Type";
				BootatrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
		}
		$.fn.validateworkorder = function(event) { 
			if($("#cmb_work_no").val()==""){ 
				var a = "Please select the work order number";
				BootatrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
		}
		$.fn.validateworkorder = function(event) { 
			var SearchType = $('input[type=radio][name=rad_report_type]:checked').val();
			if(SearchType == ""){ 
				var a = "Please select RAB search type";
				BootatrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
		}
		$("#top").submit(function(event){
			$(this).validatembooktype(event);
			$(this).validateworkorder(event);
         });
		 $("#exportToExcel").click(function(e){ 
			var table = $('body').find('#ReportTable');
			if(table.length){ 
				$(table).table2excel({
					exclude: ".ReportTable",
					name: "ZoneWiseItemQty",
					filename: "ZoneWiseItemQty-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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
<style>
	.headr td{
		background:#EDEEF0 !important;
	}
	.no-footer td{
		font-size:11px !important;
		padding:3px 2px !important;
		line-height:15px;
	}
	td.desch{
		width:250px !important;
	}
</style>
    </body>
</html>

