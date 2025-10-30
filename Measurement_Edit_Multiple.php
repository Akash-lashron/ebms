<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'ExcelReader/excel_reader2.php';
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
	$id_str		= $_POST['txt_mbdetail_id'];
	$exp_id_str = explode("*",$id_str);
	$cnt 		= count($exp_id_str);
	$mbtype		= $_POST['txt_type'];
	if(($cnt >0) && ($mbtype != ""))
	{
		if($mbtype == "S")
		{
			for($i=0; $i<$cnt; $i++)
			{
				$mbid				= $exp_id_str[$i];
				$description		= $_POST['txt_description_'.$mbid];
				$m_no				= $_POST['txt_no_'.$mbid];
				$m_length			= $_POST['txt_length_'.$mbid];
				$m_dia				= $_POST['txt_dia_'.$mbid];
				$contentsarea 		= $_POST['txt_content_area_'.$mbid];
				
				$update_query = "update mbookdetail set descwork = '$description', measurement_no = '$m_no', 
				measurement_l = '$m_length', measurement_dia = '$m_dia',
				measurement_contentarea = '$contentsarea' where mbdetail_id = '$mbid'";
				$update_sql = mysql_query($update_query);
			}
		}
		else
		{
			for($i=0; $i<$cnt; $i++)
			{
				$mbid				= $exp_id_str[$i];
				$description		= $_POST['txt_description_'.$mbid];
				$m_no				= $_POST['txt_no_'.$mbid];
				$m_length			= $_POST['txt_length_'.$mbid];
				$m_breadth			= $_POST['txt_breadth_'.$mbid];
				$m_depth			= $_POST['txt_depth_'.$mbid];
				$contentsarea 		= $_POST['txt_content_area_'.$mbid];
				
				$update_query = "update mbookdetail set descwork = '$description', measurement_no = '$m_no', 
				measurement_l = '$m_length', measurement_b = '$m_breadth',  measurement_d = '$m_depth',
				measurement_contentarea = '$contentsarea' where mbdetail_id = '$mbid'";
				$update_sql = mysql_query($update_query);
			}
		}
		if($update_sql == true)
		{
			$msg = "Measurement Updated Sucessfully";
			$success = 1;
		}
		else
		{
			$msg = "Error";
		}
	}

	if($mbtype == "S"){
		$flag = 2;
	}else{
		$flag = 1;
	}
	
	$MaxRbn1 = 0; $MaxRbn2 = 0; $CurreRbn = 0; $sheetid  = $_SESSION['sheet-id'];
	$MaxRbnQuery 	= "SELECT max(rbn) as maxrbn FROM measurementbook WHERE sheetid = '$sheetid'";
	$MaxRbnSql 		= mysql_query($MaxRbnQuery);
	if($MaxRbnSql == true){
		if(mysql_num_rows($MaxRbnSql)>0){
			$MaxRbnList = mysql_fetch_object($MaxRbnSql);
			$MaxRbn1 = $MaxRbnList->maxrbn;
		}
	}
	
	$MaxRbnQuery1 = "SELECT max(rbn) as maxrbn1 FROM mbookgenerate_staff WHERE sheetid = '$sheetid' and rbn > '$MaxRbn1'";
	$MaxRbnSql1 = mysql_query($MaxRbnQuery1);
	if($MaxRbnSql1 == true){
		if(mysql_num_rows($MaxRbnSql1)>0){
			$MaxRbnList1 = mysql_fetch_object($MaxRbnSql1);
			$MaxRbn2 = $MaxRbnList1->maxrbn1;
		}
	}
	if($MaxRbn2 != 0){
		$DeleteQuery2 	= "delete from mbookgenerate where sheetid = '$sheetid' and rbn = '$MaxRbn2'";
		$DeleteSql2 	= mysql_query($DeleteQuery2);
				
		$DeleteQuery3 	= "delete from measurementbook_temp where sheetid = '$sheetid' and rbn = '$MaxRbn2'";
		$DeleteSql3 	= mysql_query($DeleteQuery3);
				
		$DeleteQuery4 	= "delete from abstractbook where sheetid = '$sheetid' and rbn = '$MaxRbn2'";
		$DeleteSql4 	= mysql_query($DeleteQuery4);
			
		$ZoneList = $_POST['txt_zone_list'];
		$ExpZoneList = explode(",",$ZoneList);
		for($j=0; $j<count($ExpZoneList); $j++){
			$mbzoneid = $ExpZoneList[$j];
			$DeleteQuery1 	= "delete from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$MaxRbn2' and zone_id = '$mbzoneid' and flag = '$flag'";
			$DeleteSql1 	= mysql_query($DeleteQuery1);
			$DeleteQuery5 	= "delete from mymbook where sheetid = '$sheetid' and rbn = '$MaxRbn2' and ((zone_id = '$mbzoneid' and mtype = '$mbtype') OR genlevel = 'composite' OR genlevel = 'abstract') ";
			$DeleteSql5 	= mysql_query($DeleteQuery5);
			//echo $DeleteQuery1."<br/>";
			//echo $DeleteQuery5."<br/>";
		}
	}
	//echo $DeleteQuery1;
	//exit;
}
if($_GET['edit'] != "")
{
	$type = $_GET['type'];
	$edit_id_list = $_SESSION['edit_id_list'];
	$cnt = count($edit_id_list);
}
?>
<?php require_once "Header.html"; ?>
<script>
     
	function goBack()
	{
	   	url = "ViewMeasurementEntryList.php";
		window.location.replace(url);
	}
	function content_area_gen()
	{
		var no = document.form.txt_no_gen.value;
		var len = document.form.txt_length_gen.value;
		var breadth = document.form.txt_breadth_gen.value;
		var depth = document.form.txt_depth_gen.value;
		var content_area = Number(no)*Number(len)*Number(breadth)*Number(depth);
		document.form.txt_content_area_gen.value = Number(content_area).toFixed(3);
	}
	function content_area_steel()
	{
		var no = document.form.txt_no_steel.value;
		var len = document.form.txt_length_steel.value;
		var dia = document.form.txt_dia_steel.value;
		var content_area = Number(no)*Number(len);
		document.form.txt_content_area_steel.value = Number(content_area).toFixed(3);
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
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="container">
						<br/>
							<table width="100%"  bgcolor="#E8E8E8" class="label table1" cellpadding="0" cellspacing="0" align="center">
								<!--<tr><td colspan="8">&nbsp;</td></tr>-->
						<?php
						$ZoneArr = array();
						if($type == "G")
						{
						?>
								<tr class="gradientbghead" style="">
									<!--<td align="center">Date</td>-->
									<td align="center" width="10%">Item No.</td>
									<td align="center" width="40%">Description</td>
									<td align="center">No.</td>
									<td align="center">Length</td>
									<td align="center">Depth</td>
									<td align="center">Breadth</td>
									<td align="center">Contents of Area</td>
									<td align="center">Unit</td>
								</tr>
						<?php
							if($cnt>0)
							{
								$tabindex = 0;
								for($i=0; $i<$cnt; $i++)
								{
									$id 			= $edit_id_list[$i];
									$exp_id 		= explode("*",$id);
									$mbdetail_id 	= $exp_id[0];
									$mbheaderid 	= $exp_id[1];
									$mbdetail_id_str .= $mbdetail_id."*";
									if(($mbdetail_id != "") && ($mbheaderid != ""))
									{
										$select_mbdetail_query 	= "select  subdiv_name, descwork, measurement_no, measurement_l, measurement_b, measurement_d, 
										measurement_contentarea, remarks, zone_id from mbookdetail where mbdetail_id = '$mbdetail_id' and mbheaderid = '$mbheaderid'";
										//echo $select_mbdetail_query."<br/>";
										$select_mbdetail_sql 	= mysql_query($select_mbdetail_query);
										if($select_mbdetail_sql == true)
										{
											$MList = mysql_fetch_object($select_mbdetail_sql);
											$itemno 		= $MList->subdiv_name;
											$description 	= $MList->descwork;
											$m_no 			= $MList->measurement_no;
											$m_length 		= $MList->measurement_l;
											$m_breadth 		= $MList->measurement_b;
											$m_depth 		= $MList->measurement_d;
											$contentsarea 	= $MList->measurement_contentarea;
											$unit 			= $MList->remarks;
											if(in_array($MList->zone_id,$ZoneArr)){
												// array_push($ZoneArr,$MList->zone_id);
												// Already Exists
											}else{
												array_push($ZoneArr,$MList->zone_id);
											}
											
											if($m_no == 0)		{ $m_no 		= ""; }
											if($m_length == 0)	{ $m_length 	= ""; }
											if($m_breadth == 0)	{ $m_breadth 	= ""; }
											if($m_depth == 0)	{ $m_depth 		= ""; }
											if(($m_no == "")&&($m_length == "")&&($m_breadth == "")&&($m_depth == ""))
											{ 
												$color = "#E1E1E1;";
												
											}
											else
											{
												$color = "white;";
											}
											?>
												<tr>
													<td align="center"><?php echo $itemno; ?></td>
													<td align="center">
														<input type="text" class="textboxnewl" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_description_<?php echo $mbdetail_id; ?>" 	id="txt_description_<?php echo $mbdetail_id; ?>"  	value="<?php echo $description; ?>" style="width:99%;">
													</td>
													<td align="center">
														<input type="text" class="textboxnewr" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_no_<?php echo $mbdetail_id; ?>" 			id="txt_no_<?php echo $mbdetail_id; ?>" 			value="<?php echo $m_no; ?>">
													</td>
													<td align="center">
														<input type="text" class="textboxnewr" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_length_<?php echo $mbdetail_id; ?>" 		id="txt_length_<?php echo $mbdetail_id; ?>" 		value="<?php echo $m_length; ?>">
													</td>
													<td align="center">
														<input type="text" class="textboxnewr" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_breadth_<?php echo $mbdetail_id; ?>" 		id="txt_breadth_<?php echo $mbdetail_id; ?>" 	 	value="<?php echo $m_breadth; ?>">
													</td>
													<td align="center">
														<input type="text" class="textboxnewr" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_depth_<?php echo $mbdetail_id; ?>" 		id="txt_depth_<?php echo $mbdetail_id; ?>" 		 	value="<?php echo $m_depth; ?>">
													</td>
													<td align="center">
														<input type="text" class="textboxnewr" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_content_area_<?php echo $mbdetail_id; ?>" 	id="txt_content_area_<?php echo $mbdetail_id; ?>" 	value="<?php echo $contentsarea; ?>" style="width:97%;">
													</td>
													<td align="center">&nbsp;<?php echo $unit; ?>&nbsp;</td>
												</tr>
											<?php
										} 
									}
								}
							}
						?>		
						<?php
						}
						if($type == "S")
						{
						?>
								<tr class="gradientbghead" style="color:#FFFFFF">
									<!--<td align="center">Date</td>-->
									<td align="center" width="10%">Item No.</td>
									<td align="center" width="40%">Description</td>
									<td align="center">Dia</td>
									<td align="center">No.</td>
									<td align="center">Length</td>
									<td align="center">Contents of Area</td>
									<td align="center">Unit</td>
								</tr>
						<?php
							if($cnt>0)
							{
								$tabindex = 0;
								for($i=0; $i<$cnt; $i++)
								{
									$id 			= $edit_id_list[$i];
									$exp_id 		= explode("*",$id);
									$mbdetail_id 	= $exp_id[0];
									$mbheaderid 	= $exp_id[1];
									$mbdetail_id_str .= $mbdetail_id."*";
									if(($mbdetail_id != "") && ($mbheaderid != ""))
									{
										$select_mbdetail_query 	= "select  subdiv_name, descwork, measurement_no, measurement_l,  measurement_dia, 
										measurement_contentarea, remarks, zone_id from mbookdetail where mbdetail_id = '$mbdetail_id' and mbheaderid = '$mbheaderid'";
										//echo $select_mbdetail_query."<br/>";
										$select_mbdetail_sql 	= mysql_query($select_mbdetail_query);
										if($select_mbdetail_sql == true)
										{
											$MList = mysql_fetch_object($select_mbdetail_sql);
											$itemno 		= $MList->subdiv_name;
											$description 	= $MList->descwork;
											$m_no 			= $MList->measurement_no;
											$m_length 		= $MList->measurement_l;
											$m_dia 			= $MList->measurement_dia;
											$contentsarea 	= $MList->measurement_contentarea;
											$unit 			= $MList->remarks;
											if(in_array($MList->zone_id,$ZoneArr)){
												// array_push($ZoneArr,$MList->zone_id);
												// Already Exists
											}else{
												array_push($ZoneArr,$MList->zone_id);
											}
											if($m_no == 0)		{ $m_no 		= ""; }
											if($m_length == 0)	{ $m_length 	= ""; }
											if($m_dia == 0)		{ $m_breadth 	= ""; }
											if(($m_no == "")&&($m_length == "")&&($m_dia == ""))
											{ 
												//$color = "#E1E1E1;";
												//$color = "white;";
												$font = "font-weight:bold;";
											}
											else
											{
												//$color = "white;";
												$font = "";
											}
											?>
												<tr>
													<td align="center"><?php echo $itemno; ?></td>
													<td align="center">
														<input type="text" class="textboxnewl" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_description_<?php echo $mbdetail_id; ?>" 	id="txt_description_<?php echo $mbdetail_id; ?>"  	value="<?php echo $description; ?>" style="width:99%;<?php echo $font; ?>">
													</td>
													<td align="center">
														<input type="text" class="textboxnewr" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_dia_<?php echo $mbdetail_id; ?>" 			id="txt_dia_<?php echo $mbdetail_id; ?>" 	 	value="<?php echo $m_dia; ?>">
													</td>
													<td align="center">
														<input type="text" class="textboxnewr" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_no_<?php echo $mbdetail_id; ?>" 			id="txt_no_<?php echo $mbdetail_id; ?>" 			value="<?php echo $m_no; ?>">
													</td>
													<td align="center">
														<input type="text" class="textboxnewr" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_length_<?php echo $mbdetail_id; ?>" 		id="txt_length_<?php echo $mbdetail_id; ?>" 		value="<?php echo $m_length; ?>">
													</td>
													<td align="center">
														<input type="text" class="textboxnewr" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_content_area_<?php echo $mbdetail_id; ?>" 	id="txt_content_area_<?php echo $mbdetail_id; ?>" 	value="<?php echo $contentsarea; ?>" style="width:97%;">
													</td>
													<td align="center">&nbsp;<?php echo $unit; ?>&nbsp;</td>
												</tr>
											<?php
										} 
									}
								}
							}
						?>		
						<?php
						}
						if(count($ZoneArr)>0){
							$ZoneList = implode(",",$ZoneArr);
						}else{
							$ZoneList = "";
						}
						?>

							</table>
							<input type="hidden" name="txt_mbdetail_id" id="txt_mbdetail_id" value="<?php echo rtrim($mbdetail_id_str,"*"); ?>">
							<input type="hidden" name="txt_type" id="txt_type" value="<?php echo $type; ?>">
							<input type="hidden" name="txt_zone_list" id="txt_zone_list" value="<?php echo $ZoneList; ?>">
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
$(document).on("keyup", function(e) {
	var type = $('#txt_type').val();
	if(type == 'S')
	{
        var code = e.which,
            elm = document.activeElement,
            currentTabIndex = elm.tabIndex,
            nextTabIndex = code == 40 || code == 39 ? currentTabIndex + 5 :
        code == 38 || code == 37 ? currentTabIndex - 5 : null,
            isHoriz = code == 39 || code == 37;
        $('[tabindex]').filter(function() {
            if( !$(elm).is(':text,textarea') || !isHoriz ) {
                return this.tabIndex == nextTabIndex;
            }
        })
        .focus();
	}
	else
	{
		 var code = e.which,
            elm = document.activeElement,
            currentTabIndex = elm.tabIndex,
            nextTabIndex = code == 40 || code == 39 ? currentTabIndex + 6 :
        code == 38 || code == 37 ? currentTabIndex - 6 : null,
            isHoriz = code == 39 || code == 37;
        $('[tabindex]').filter(function() {
            if( !$(elm).is(':text,textarea') || !isHoriz ) {
                return this.tabIndex == nextTabIndex;
            }
        })
        .focus();
	}	
});
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
<style>
	:focus {
    background:#BDE0FB;
	/*background: LightGreen;*/
}
</style>
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

