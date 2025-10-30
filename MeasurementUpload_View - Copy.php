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
if($_GET['sheetid'] != "")
{
	$sheetid = $_GET['sheetid'];
	$sheetdetail_sql = "select work_order_no, work_name, short_name, agree_no from sheet where sheet_id = '$sheetid'";
	$sheetdetail_query = mysql_query($sheetdetail_sql);
	$SheetList 		= mysql_fetch_object($sheetdetail_query);
	$workorderno 	= $SheetList->work_order_no;
	$workname 		= $SheetList->work_name;
	$workshortname 	= $SheetList->short_name;
	$agreementno 	= $SheetList->agree_no;
	
$getmeasurement_sql = "select mbookheader_temp.mbheaderid, DATE_FORMAT(mbookheader_temp.date,'%d/%m/%Y') AS mdate, mbookheader_temp.divid, mbookheader_temp.subdiv_name, mbookdetail_temp.mbdetail_id , mbookdetail_temp.subdivid, mbookdetail_temp.descwork, mbookdetail_temp.measurement_no, mbookdetail_temp.measurement_l, mbookdetail_temp.measurement_b, mbookdetail_temp.measurement_d,  mbookdetail_temp.structdepth_unit,  mbookdetail_temp.measurement_dia, mbookdetail_temp.measurement_contentarea, mbookdetail_temp.remarks,  mbookdetail_temp.mbdetail_flag from mbookheader_temp INNER JOIN mbookdetail_temp ON (mbookheader_temp.mbheaderid = mbookdetail_temp.mbheaderid) where mbookheader_temp.sheetid = '$sheetid'";
$getmeasurement_query = mysql_query($getmeasurement_sql);
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
	function GeneralEditData(id)
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
		
		/*$("#"+esno_id).empty();
		$("#"+esno_id).append('<input type="text" size="1" id="dummy_sno_'+mbdetailid+'" value="'+esno+'"/>');*/
		
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
		
		var inputStr = mbheaderid+"@*@"+mbdetailid+"@*@"+edate+"@*@"+eitemno+"@*@"+edescrip+"@*@"+enumber+"@*@"+elength+"@*@"+ebreadth+"@*@"+edepth+"@*@"+eunit;
		$.post('MeasurementUpload_Getdata.php', { sheetid: workordername }, function(data) {
			
		});
		
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
		  alert(this.id)
		});
});
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

                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1">
                            <div class="title">Measurement Upload - View</div>
							<table width="1078px" align="center" cellpadding="3" cellspacing="3" class="">
									<tr>
										<td width="50px;">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td width="250px;">&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td class="label">Work Short Name</td> 
										<td  height="30px;" class="labeldisplay">
											<select name="txt_workshortname" id="txt_workshortname" class="textboxdisplay" style="width:439px;height:22px;" onChange="workorderdetail();" tabindex="7">
												<option value=""> ----------------------- Select Work Short Name ------------------------ </option>
												<?php echo $objBind->BindWorkOrderNo(0);?>
											</select>
										</td>
										<td><input type="button" class="backbutton" id="btn_view" id="btn_view" value=" View "></td>
									</tr>
									<tr>
										<td width="21%">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
							</table>
							<?php
							if($sheetdetail_query == true)
							{
							?>
							<table width="1078px" border="0" align="center" cellpadding="3" cellspacing="3" class="table1">
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
							<table width="1078px" border="0" align="center" cellpadding="3" cellspacing="3" class="table1">
								<tr height="28px">
									<td class="label" align="center">S.No.</td>
									<td class="label" align="center">Date</td>
									<td class="label" align="center">Item No.</td>
									<td class="label" align="center">Description</td>
									<td class="label" align="center">No.</td>
									<td class="label" align="center">Length</td>
									<td class="label" align="center">Breadth</td>
									<td class="label" align="center">Depth</td>
									<td class="label" align="center">Contents <br/>of<br/> Area</td>
									<td class="label" align="center">Unit</td>
									<td class="label" align="center">Remarks</td>
									<td class="label" align="center" colspan="2">Operation</td>
								</tr>
				<?php
					if(mysql_num_rows($getmeasurement_query)>0)
					{
						$sno = 1;
						while($MList = mysql_fetch_object($getmeasurement_query))
						{
							$Mbdetailid 	= $MList->mbdetail_id;
							$Mbheaderid		= $MList->mbheaderid;
							$MidStr			= $Mbheaderid."*".$Mbdetailid;
							$Mdate 			= $MList->mdate;
							$MItemno 		= $MList->subdiv_name;
							$MDescription 	= $MList->descwork;
							$MNumber 		= $MList->measurement_no;
							$MLength 		= $MList->measurement_l;
							$MBreadth 		= $MList->measurement_b;
							$MDepth 		= $MList->measurement_d;
							$MStDepthUnit 	= $MList->structdepth_unit;
							$MDia 			= $MList->measurement_dia;
							$MConArea 		= $MList->measurement_contentarea;
							$MUnit 			= $MList->remarks;
							$MError 		= $MList->mbdetail_flag;
							$experror		= explode("@@@",$MError);
							$ErrorItemNo	= $experror[0];
							$DateError		= $experror[1];
							$ItemError		= $experror[2];
							$eno = 1;
							$ItemErr = "";
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
							if($eno == 1) { $color = "#0B29B9"; } else { $color = "red"; }
				?>
								<tr class="labelhead" style="color:<?php echo $color; ?>" id="<?php echo $Mbdetailid; ?>">
									<td align="center" 	id="td_sno_<?php echo $Mbdetailid; ?>">
									<?php echo $sno; ?>
		<input type="hidden" name="txt_sno_<?php echo $Mbdetailid; ?>" id="txt_sno_<?php echo $Mbdetailid; ?>" value="<?php echo $sno; ?>">
									</td>
									<td align="left" 	id="td_date_<?php echo $Mbdetailid; ?>">
									<?php echo $Mdate; ?>
		<input type="hidden" name="txt_date_<?php echo $Mbdetailid; ?>" id="txt_date_<?php echo $Mbdetailid; ?>" value="<?php echo $Mdate; ?>">							
									</td>
									<td align="center" 	id="td_itemno_<?php echo $Mbdetailid; ?>">
									<?php echo $MItemno; ?>
		<input type="hidden" name="txt_itemno_<?php echo $Mbdetailid; ?>" id="txt_itemno_<?php echo $Mbdetailid; ?>" value="<?php echo $MItemno; ?>">							
									</td>
									<td align="left" 	id="td_descrip_<?php echo $Mbdetailid; ?>">
									<?php echo $MDescription; ?>
		<input type="hidden" name="txt_descrip_<?php echo $Mbdetailid; ?>" id="txt_descrip_<?php echo $Mbdetailid; ?>" value="<?php echo $MDescription; ?>">							
									</td>
									<td align="right" 	id="td_number_<?php echo $Mbdetailid; ?>">
									<?php echo $MNumber; ?>
		<input type="hidden" name="txt_number_<?php echo $Mbdetailid; ?>" id="txt_number_<?php echo $Mbdetailid; ?>" value="<?php echo $MNumber; ?>">							
									</td>
									<td align="right" 	id="td_length_<?php echo $Mbdetailid; ?>">
									<?php echo $MLength; ?>
		<input type="hidden" name="txt_length_<?php echo $Mbdetailid; ?>" id="txt_length_<?php echo $Mbdetailid; ?>" value="<?php echo $MLength; ?>">							
									</td>
									<td align="right" 	id="td_breadth_<?php echo $Mbdetailid; ?>">
									<?php echo $MBreadth; ?>
		<input type="hidden" name="txt_breadth_<?php echo $Mbdetailid; ?>" id="txt_breadth_<?php echo $Mbdetailid; ?>" value="<?php echo $MBreadth; ?>">							
									</td>
									<td align="right" 	id="td_depth_<?php echo $Mbdetailid; ?>">
									<?php echo $MDepth; ?>
		<input type="hidden" name="txt_depth_<?php echo $Mbdetailid; ?>" id="txt_depth_<?php echo $Mbdetailid; ?>" value="<?php echo $MDepth; ?>">							
									</td>
									<td align="right" 	id="td_coarea_<?php echo $Mbdetailid; ?>">
									<?php echo $MConArea; ?>
		<input type="hidden" name="txt_coarea_<?php echo $Mbdetailid; ?>" id="txt_coarea_<?php echo $Mbdetailid; ?>" value="<?php echo $MConArea; ?>">							
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
										<img src="images/edit.png" class="edit" id="<?php echo $MidStr."*edit"; ?>" width="24" height="24" onClick="GeneralEditData(this);">
									</td>
									<td class="hide" align="center" id="update_img_<?php echo $Mbdetailid; ?>">
										<img src="images/update.png" class="update" id="<?php echo $MidStr."*update"; ?>" width="24" height="24" onClick="GeneralEditData(this);">
									</td>

									<td class="label" align="center"><img src="images/delete.png" class="delete" id="<?php echo $MidStr; ?>" width="20" height="20" onClick="GeneralDeleteData(this);"></td>
								</tr>	
				<?	
							$sno++;
						}
					}
				}
				?>
							
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
		if(workordername != "")
		{
			var url = "MeasurementUpload_View.php?sheetid="+workordername;
			$(location).attr('href',url);
		}
	});
});
</script>
    </body>
</html>
