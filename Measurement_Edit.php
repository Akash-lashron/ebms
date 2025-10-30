<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
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
if($_POST["submit"] == " Update ") 
{
	$mbdetailid		= $_POST['txt_mbdetail_id'];
	$mbtype			= $_POST['txt_type'];
	//echo 
	if(($mbdetailid != "") && ($mbtype != ""))
	{
		if($mbtype == "S")
		{
			$desc_work_steel		= $_POST['txt_desc_work_steel'];
			$dia_steel				= $_POST['txt_dia_steel'];
			$no_steel			= $_POST['txt_no_steel'];
			$no2_steel			= $_POST['txt_no2_steel'];
			$length_steel		= $_POST['txt_length_steel'];
			$content_area_steel 	= $_POST['txt_content_area_steel'];
			
			$update_query = "update mbookdetail set descwork = '$desc_work_steel', measurement_no = '$no_steel', 
			measurement_no2 = '$no2_steel', measurement_l = '$length_steel', measurement_dia = '$dia_steel', 
			measurement_contentarea = '$content_area_steel' where mbdetail_id = '$mbdetailid'";
			$update_sql = mysql_query($update_query);
		}
		else
		{
			$desc_work_gen		= $_POST['txt_desc_work_gen'];
			$no_gen				= $_POST['txt_no_gen'];
			$length_gen			= $_POST['txt_length_gen'];
			$breadth_gen		= $_POST['txt_breadth_gen'];
			$depth_gen			= $_POST['txt_depth_gen'];
			$content_area_gen 	= $_POST['txt_content_area_gen'];
			
			$update_query = "update mbookdetail set descwork = '$desc_work_gen', measurement_no = '$no_gen', 
			measurement_l = '$length_gen', measurement_b = '$breadth_gen',  measurement_d = '$depth_gen',
			measurement_contentarea = '$content_area_gen' where mbdetail_id = '$mbdetailid'";
			$update_sql = mysql_query($update_query);
		}
		if($update_sql == true)
		{
			$msg = "Measurement Updated Sucessfully";
			$success = 1;
			
			if($mbtype == "S"){
				$flag = 2;
			}else{
				$flag = 1;
			}
			
			$SelectZoneQuery 	= "select b.sheetid, b.zone_id from mbookdetail a inner join mbookheader b on (a.mbheaderid = b.mbheaderid) where a.mbdetail_id = '$mbdetailid'";
			$SelectZoneSql 		= mysql_query($SelectZoneQuery);
			if($SelectZoneSql == true){
				if(mysql_num_rows($SelectZoneSql)>0){
					$ZList = mysql_fetch_object($SelectZoneSql);
					$ResetZone = $ZList->zone_id;
					$ResetSheet = $ZList->sheetid;
				}
			}
			if($ResetSheet != ''){
				$MaxRbn1 = 0; $MaxRbn2 = 0; $CurreRbn = 0;
				$MaxRbnQuery 	= "SELECT max(rbn) as maxrbn FROM measurementbook WHERE sheetid = '$ResetSheet'";
				$MaxRbnSql 		= mysql_query($MaxRbnQuery);
				if($MaxRbnSql == true){
					if(mysql_num_rows($MaxRbnSql)>0){
						$MaxRbnList = mysql_fetch_object($MaxRbnSql);
						$MaxRbn1 = $MaxRbnList->maxrbn;
					}
				}
				
				$MaxRbnQuery1 = "SELECT max(rbn) as maxrbn1 FROM mbookgenerate_staff WHERE sheetid = '$ResetSheet' and rbn > '$MaxRbn1'";
				$MaxRbnSql1 = mysql_query($MaxRbnQuery1);
				if($MaxRbnSql1 == true){
					if(mysql_num_rows($MaxRbnSql1)>0){
						$MaxRbnList1 = mysql_fetch_object($MaxRbnSql1);
						$MaxRbn2 = $MaxRbnList1->maxrbn1;
					}
				}
				if($MaxRbn2 != 0){
					$DeleteQuery2 	= "delete from mbookgenerate where sheetid = '$ResetSheet' and rbn = '$MaxRbn2'";
					$DeleteSql2 	= mysql_query($DeleteQuery2);
							
					$DeleteQuery3 	= "delete from measurementbook_temp where sheetid = '$ResetSheet' and rbn = '$MaxRbn2'";
					$DeleteSql3 	= mysql_query($DeleteQuery3);
							
					$DeleteQuery4 	= "delete from abstractbook where sheetid = '$ResetSheet' and rbn = '$MaxRbn2'";
					$DeleteSql4 	= mysql_query($DeleteQuery4);
					
					$DeleteQuery1 	= "delete from mbookgenerate_staff where sheetid = '$ResetSheet' and rbn = '$MaxRbn2' and zone_id = '$ResetZone' and flag = '$flag'";
					$DeleteSql1 	= mysql_query($DeleteQuery1);
					
					$DeleteQuery5 	= "delete from mymbook where sheetid = '$ResetSheet' and rbn = '$MaxRbn2' and ((zone_id = '$ResetZone' and mtype = '$mbtype') OR genlevel = 'composite' OR genlevel = 'abstract')";
					$DeleteSql5 	= mysql_query($DeleteQuery5);
				
				}
			}
			
			
		}
		else
		{
			$msg = "Error";
		}
		//echo $update_query;
	}
}
if($_GET['mbdetail_id'] != "")
{
	$mbdetail_id = $_GET['mbdetail_id'];
	$type = $_GET['type'];
	$sheetid = $_GET['sheetid'];
	$select_data_query = "select * from mbookdetail where mbdetail_id = '$mbdetail_id'";
	$select_data_sql = mysql_query($select_data_query);
	//echo $select_data_query;
}
?>
<?php require_once "Header.html"; ?>
<script>
     
	function goBack()
	{
	   	url = "ViewMeasurementEntry.php";
		window.location.replace(url);
	}
	function content_area_gen()
	{
		var no = document.form.txt_no_gen.value;
		var len = document.form.txt_length_gen.value;
		var breadth = document.form.txt_breadth_gen.value;
		var depth = document.form.txt_depth_gen.value;
		var decimal = Number(document.form.txt_decimal.value);
		if(no == 0){ no = 1; }
		if(len == 0){ len = 1; }
		if(breadth == 0){ breadth = 1; }
		if(depth == 0){ depth = 1; }
		var content_area = Number(no)*Number(len)*Number(breadth)*Number(depth);
		document.form.txt_content_area_gen.value = Number(content_area).toFixed(decimal);
	}
	function content_area_steel()
	{
		var no = document.form.txt_no_steel.value;
		var no2 = document.form.txt_no2_steel.value;
		var len = document.form.txt_length_steel.value;
		var dia = document.form.txt_dia_steel.value;
		var decimal = Number(document.form.txt_decimal.value);
		if(no == 0){ no = 1; }
		if(no2 == 0){ no2 = 1; }
		if(len == 0){ len = 1; }
		if(dia == 0){ dia = 1; }
		var content_area = Number(no)*Number(no2)*Number(len);
		document.form.txt_content_area_steel.value = Number(content_area).toFixed(decimal);
	}

</script>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->

         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
           <div class="title">Measurement - Edit</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="container">
							<br/>
                 <table width="100%"  bgcolor="#E8E8E8" border="0" class="label" cellpadding="0" cellspacing="0" align="center" >
                 	<tr><td width="24%">&nbsp;</td></tr>
					<?php
						if($select_data_sql == true)
						{
							$MDataList = mysql_fetch_object($select_data_sql);
							$subdivid = $MDataList->subdivid;
							$item_no = $MDataList->subdiv_name;
							$descwork = $MDataList->descwork;
							$measurement_no = $MDataList->measurement_no;
							$measurement_no2 = $MDataList->measurement_no2;
							$measurement_l = $MDataList->measurement_l;
							$measurement_b 	 = $MDataList->measurement_b;
							$measurement_d = $MDataList->measurement_d;
							$measurement_dia = $MDataList->measurement_dia;
							$remarks = $MDataList->remarks;
							$content_area = $MDataList->measurement_contentarea;
							$decimal = get_decimal_placed($subdivid,$sheetid);
							if($type == "S")
							{
								?>
									<tr>
										<td></td><td>Item No</td>
										<td>
										<?php echo $item_no; ?>
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>Description of Work</td>
										<td>
										<textarea name="txt_desc_work_steel" id="txt_desc_work_steel" class="textboxdisplay" rows="5" style="width:390px;" ><?php echo $descwork; ?></textarea>
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>Dia od Rod</td>
										<td>
										<input type="text" name="txt_dia_steel" id="txt_dia_steel" onBlur="content_area_steel();" class="textboxdisplay" value="<?php echo $measurement_dia; ?>">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>No.</td>
										<td>
										<input type="text" name="txt_no_steel" id="txt_no_steel" onBlur="content_area_steel();" class="textboxdisplay" value="<?php echo $measurement_no; ?>">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>No.</td>
										<td>
										<input type="text" name="txt_no2_steel" id="txt_no2_steel" onBlur="content_area_steel();" class="textboxdisplay" value="<?php echo $measurement_no2; ?>">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									
									<tr>
										<td></td><td>Length</td>
										<td>
										<input type="text" name="txt_length_steel" id="txt_length_steel" onBlur="content_area_steel();" class="textboxdisplay" value="<?php echo $measurement_l; ?>">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>Contents of Area</td>
										<td>
										<input type="text" name="txt_content_area_steel" id="txt_content_area_steel" class="textboxdisplay" value="<?php echo $content_area; ?>">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>Remarks</td>
										<td>
										<?php echo $remarks; ?>
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
								<?php
							}
							else
							{
								?>
									<tr>
										<td></td><td>Item No</td><td><?php echo $item_no; ?></td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>Description of Work</td>
										<td>
										<textarea name="txt_desc_work_gen" id="txt_desc_work_gen" rows="5" style="width:390px;" class="textboxdisplay"><?php echo $descwork; ?></textarea>
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>No.</td>
										<td>
										<input type="text" name="txt_no_gen" id="txt_no_gen" onBlur="content_area_gen();" class="textboxdisplay" value="<?php echo $measurement_no; ?>">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>Length</td>
										<td>
										<input type="text" name="txt_length_gen" id="txt_length_gen" onBlur="content_area_gen();" class="textboxdisplay" value="<?php echo $measurement_l; ?>">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>Breadth</td>
										<td>
										<input type="text" name="txt_breadth_gen" id="txt_breadth_gen" onBlur="content_area_gen();" class="textboxdisplay" value="<?php echo $measurement_b; ?>">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>Depth</td>
										<td>
										<input type="text" name="txt_depth_gen" id="txt_depth_gen" onBlur="content_area_gen();" class="textboxdisplay" value="<?php echo $measurement_d; ?>">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>Contents of Area</td>
										<td>
										<input type="text" name="txt_content_area_gen" id="txt_content_area_gen" readonly="" class="textboxdisplay" value="<?php echo $content_area; ?>">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td> </tr>
									<tr>
										<td></td><td>Remarks</td><td><?php echo $remarks; ?></td>
									</tr>
								<?php
							}
						}
					?>
         		</table>
				<input type="hidden" name="txt_mbdetail_id" id="txt_mbdetail_id" value="<?php echo $mbdetail_id; ?>">
				<input type="hidden" name="txt_type" id="txt_type" value="<?php echo $type; ?>">
				<input type="hidden" name="txt_decimal" id="txt_decimal" value="<?php echo $decimal; ?>">
     </div>
	 <div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
			</div>
			<div class="buttonsection">
			<input type="submit" class="btn" data-type="submit" value=" Update " name="submit" id="submit"   />
			</div>
		</div>
     </form>
   </blockquote>
  </div>

  </div>
 </div>
         <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
<script>
   /* $(function() {
	
	$( "#txt_mbook_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });
	
	
	$.fn.validatembooktype = function(event) {	
				if($("#cmb_mbook_type").val()==""){ 
					var a="Please select the Measurement Type";
					$('#val_mbooktype').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_mbooktype').text(a);
				}
			}
	$.fn.validateworkorder = function(event) { 
					if($("#cmb_work_no").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
		$.fn.validatmbookname = function(event) { 
					if($("#txt_mbook_name").val()==""){ 
					var a="Please Enter MBook Name";
					$('#val_mbookname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_mbookname').text(a);
				}
			}

		$("#top").submit(function(event){
				$(this).validatembooktype(event);
				$(this).validateworkorder(event);
				$(this).validatmbookname(event);
			 });
		$("#cmb_work_no").change(function(event){
			   $(this).validateworkorder(event);
			 });
		$("#cmb_mbook_type").change(function(event){
			   $(this).validatembooktype(event);
			 });
		 $("#txt_mbook_name").change(function(event){
			   $(this).validatmbookname(event);
			 });
			
	 });*/
</script>
			<script>
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal("", msg, "success");
					}
					else
					{
						swal(msg, "", "");
					}
					
				}
				};
			</script>

    </body>
</html>

