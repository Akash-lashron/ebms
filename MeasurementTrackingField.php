<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
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
$view = 0;
?>
<?php require_once "Header.html"; ?>

<script>
function Getexitemnos()
{ 
	var xmlHttp;
	var data;
	var i, j;
	var measure_type    = $('input[type=radio][name=measure_type]:checked').val();
	var WorkName        = $('#cmb_shortname').val();
	$("#cmb_item_no").chosen("destroy");
	document.form.cmb_item_no.length = 1;
	document.form.cmb_item_no.value = "";
	if (window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) // For Internet Explorer
	{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	strURL = "find_ex_item.php?workorderno=" + document.form.cmb_shortname.value +"&"+ "measure_value=" + document.form.measure_type.value;
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function ()
	{
		if (xmlHttp.readyState == 4)
		{
			data = xmlHttp.responseText
			if((WorkName !=="")&(measure_type !=="")){
				if (data == "")
				{
					document.form.cmb_item_no.value='';
					//alert("No Item Found");
					BootstrapDialog.alert({ title: 'Error ! ', message: 'No Item Found' });
			        Error++;
				}
				else
				{
					var name = data.split("*");
					for(i = 0; i < name.length; i+=3)
					{
						var exitemno	= name[i+1]+ "  --  " +name[i+2];
						//alert(exitemno)
						var description	= name[i+2];
						var schduleid	= name[i+0];
						var optn 		= 	document.createElement("option")
						optn.value 		=  schduleid;
						optn.text 		=  exitemno;
						// var result     = value.bold();
						document.form.cmb_item_no.options.add(optn)
					}
				}
			}	
			$("#cmb_item_no").chosen();
		}
	}
	xmlHttp.send(strURL);
}
function Getzoneno()
{ 
	var xmlHttp;
	var data;
	var i, j;
	$("#cmb_zone_no").chosen("destroy");
	//document.form.cmb_zone_no.length = 1;
	document.form.cmb_zone_no.value = "";
	if (window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) // For Internet Explorer
	{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	strURL = "find_zone_name.php?workorderno=" + document.form.cmb_shortname.value +"&"+ "measure_value=" + document.form.measure_type.value;
		//alert(strURL)
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function ()
	{
		if (xmlHttp.readyState == 4)
		{
			data = xmlHttp.responseText
				//alert(data)
			if (data == "")
			{
				document.form.cmb_zone_no.value='';
				//alert("No Item Found");
				//BootstrapDialog.alert({ title: 'Error ! ', message: 'No Zone Found' });
			    Error++;
			}
			else
			{
				var name = data.split("*");
				for(i = 0; i < name.length; i+=2)
				{
						//var zonename	= name[i];
						//var zoneid	    = name[i];
					var optn 		=  document.createElement("option")
					optn.value 		=  name[i+0];
					optn.text 		=  name[i+1];
					document.form.cmb_zone_no.options.add(optn)
				}
			}
			$("#cmb_zone_no").chosen();
		}
	}
	xmlHttp.send(strURL);
}
</script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
 <div class="title">Measurement Tracking </div>
	<div class="container_12">
		<div class="grid_12">
			<blockquote class="bq1" style="overflow:auto">
				<form name="form" method="post" action="MeasurementTrackingList.php">
					<div class="container">
						<div class="row ">
							<div class="div12 grid-empty"></div>
							<div class="div2">&nbsp;</div>
							<div class="div8">
								<div class="row"><div class="div12" style="margin-top:0px;"><div class="row divhead" align="center">Measurement Tracking - Date / Item No / Description wise</div></div></div>
								<div class="row innerdiv">
									<div class="row">
										<div class="div12" align="center"> 
											<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:98%; text-align:left" onChange="Getexitemnos(); Getzoneno();" >
												<option value=""> ------------------------------------ Select Name Of Work ---------------------------------- </option>
												<?php echo $objBind->BindWorkOrderNo($sid);?>
											</select>
										</div>
										<div class="div12 grid-empty"></div>
										<div class="div12" align="center">
											<div class="inputGroup div6" >
												<input id="rad_den" name="measure_type" class="divtbox" type="radio" value="G"/ onChange="Getexitemnos();Getzoneno();">
												<label for="rad_den" class="" id="rad_den_label" style="width: 96%; padding: 6px 1px 8px 1px;">&nbsp; General Measurements</label>
											</div>
											<div class="inputGroup div6">
												<input id="rad_ste" name="measure_type" class="divtbox" type="radio" value="S"/ onChange="Getexitemnos();Getzoneno();">
												<label for="rad_ste" class="" id="rad_ste_label" style="width: 96%; padding: 6px 1px 8px 1px;">&nbsp; Steel Measurements</label>
											</div>
										</div>
										<div class="div12 grid-empty"></div>
										<div class="div6" align="center">
											<div class="innerdiv2">
												<div class="row innerdiv" align="center">
													<div class="boxdiv1 label boxtitle trackbox-1" align="left">
														<input type="radio" name="searchtype" id="date_wise" value="DW" onClick="func_book();"/>
														Date Wise
													</div>
													<div class="boxdiv1 label boxtitle trackbox-2" align="left">
														<input type="radio" name="searchtype" id="item_wise" value="IW" onClick="func_book();"/>
														Item No Wise
													</div>
													<div class="boxdiv1 label boxtitle  trackbox-3" align="left">
														<input type="radio" name="searchtype" id="zone_wise" value="ZW" onClick="func_book();"/>
														Zone Wise
													</div>
													<div class="boxdiv1 label boxtitle  trackbox-4" align="left">
														<input type="radio" name="searchtype" id="des_wise" value="DSW" onClick="func_book();"/>
														Description Wise
													</div>
													<div class="div12" style="line-height:55%;">&nbsp;</div>
												</div>
											</div>
										</div>
										<div class="div6" align="center">
											<div class="innerdiv2">
												<div class="row innerdiv">
													<div class="div12 grid-empty"></div>
													<div class="div6">
														<input type="text" name="txt_from_date" id="txt_from_date" autocomplete="off" class="divtbox"  value="" placeholder="Select From Date " style="width:90%;" > 
													</div>
													<div class="div6">
														<input type="text" name="txt_to_date" id="txt_to_date" class="divtbox" autocomplete="off"  value="" placeholder="Select To Date" style="width:90%;">
													</div>
													<div class="div12 grid-empty"></div>
													<div class="div12" align="left">
													<div class="div12"  style="margin-left:2%;"> 
														<select name="cmb_item_no" id="cmb_item_no" class="textboxdisplay" style="width:96%;">
															<option value="">------------- Select Item No. ------------- </option>
														</select>
													</div>
													<div class="div12 grid-empty"></div>
													</div>
													<div class="div12" align="left">
													<div class="div12"  style="margin-left:2%;"> 
														<select name="cmb_zone_no" id="cmb_zone_no" class="textboxdisplay" style="width:96%;">
															<option value=""> ------------ Select Zone Name ------------ </option>
														</select>
													</div>
													<div class="div12 grid-empty"></div>
													</div>
													<div class="div12">
														<div class="div12">
															<input type="text" name="txt_description" id="txt_description" class="divtbox" value="" placeholder="Enter Description " style="width:95%; margin-left:0px;">
														</div>
													</div>	
													<div class="div12" style="line-height:5%;">&nbsp;</div>
													<div class="div12 grid-empty"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="div2">&nbsp;</div>
							
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton div12">
								<div class="buttonsection">
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/></div>
								<div class="buttonsection"><input type="submit" class="backbutton" name="btn_next" id="btn_next" value="Next" onClick="View_page();"/></div>
							</div>
						</div>
					</div>
					<div class="row" <?php if($view == 0){ ?> style="display:none" <?php } ?>>
					    <div class="div4">&nbsp;</div>
						<div class="div4">
						 <?php if($count2 > 0){ ?>
							<div class="col-md-2 well-A level rspangreen" align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> SOQ Uploaded </div> <br/>
						 <?php }else{?>
						   <div class="col-md-2 well-A level rspanred" align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> SOQ Not Uploaded</div> <br/>
						<?php }?>	
						<?php if($staff_assign != ""){ ?>
							<div class="col-md-2 well-A level rspangreen" align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> Staff  Assigned</div> <br/>
						<?php }else{ ?>
							<div class="col-md-2 well-A level rspanred" align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> Staff Not Assigned</div> <br/>
						<?php } ?>
						<?php if($count2 > 0 ){ ?>
							<div class="col-md-2 well-A level rspangreen" align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> Mbook Assigned </div> <br/>
						<?php }else{ ?>
							<div class="col-md-2 well-A level rspanred" align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> Mbook Not Assigned </div> <br/>
						<?php } ?>
						</div>
						<div class="div4">&nbsp;</div>
					</div>
				</form>
			</blockquote>
		</div>
	</div>
</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
$("#cmb_work_no").chosen();
$(function() {
	$.fn.validateCCno = function(event) { 
		if($("#txt_cc_no").val()==""){ 
			var a="Please Enter your CC Number";
			$('#val_ccno').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else if($("#txt_cc_no").val()==0){ 
			var a="Please Enter valid CC Number";
			$('#val_ccno').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_ccno').text(a);
		}
	}
	$("#top").submit(function(event){
		$(this).validateCCno(event);
	});
	$("#txt_cc_no").keyup(function(event){
    	$(this).validateCCno(event);
    });
});
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
				swal("", msg, "");
			}
			else
			{
				swal(msg, "", "");
			}
		}
	};
	$("#txt_from_date").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		maxDate: new Date,
		defaultDate: new Date,
	});
	$("#txt_to_date").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		maxDate: new Date,
		defaultDate: new Date,
	});
</script>
<script>
$("#cmb_shortname").chosen();
$("#cmb_zone").chosen();
$("#cmb_item_no").chosen();
	$("#btn_next").click(function(e){
		var search_type		= $('input[type=radio][name=searchtype]:checked').val();//$("input[name='pass_search_type']:checked").val();
		var WorkName        = $('#cmb_shortname').val();
		var Error = 0;
		if(WorkName == ""){
			BootstrapDialog.alert({ title: 'Error ! ', message: 'Please select Name Of Work ' });
			Error++;
		}else if(typeof(search_type) === "undefined"){
			BootstrapDialog.alert({ title: 'Error ! ', message: 'Please select any one of search option ' });
			Error++;
		}
		if(search_type == "DW"){
			var measure_type    = $('input[type=radio][name=measure_type]:checked').val();
			var FromDate        = $('#txt_from_date').val();
			var ToDate          = $('#txt_to_date').val();
			var WorkName        = $('#cmb_shortname').val();
			var ItemNo          = $('#cmb_item_no').val();
			if(typeof(measure_type) === "undefined"){
			   BootstrapDialog.alert({ title: 'Error ! ', message: 'Please select any General Or Steel ' });
			   Error++;
		    }else if(FromDate == ""){
				BootstrapDialog.alert({ title: 'Error ! ', message: 'Please Enter From Date. ' });
				Error++;
			}else if(ToDate == ""){
				BootstrapDialog.alert({ title: 'Error ! ', message: 'Please Enter To Date. ' });
				Error++;
			/*}else if(WorkName == ""){
				BootstrapDialog.alert({ title: 'Error ! ', message: 'Please Select Name Of Work ' });
				Error++;
			}else if(ItemNo == ""){
				BootstrapDialog.alert({ title: 'Error ! ', message: 'Please Select Item No. ' });
				Error++;*/
			}
		}
		if(search_type == "IW"){
			var measure_type    = $('input[type=radio][name=measure_type]:checked').val();
			var WorkName        = $('#cmb_shortname').val();
			var item_no         = $('#cmb_item_no').val();
			var zone            = $('#cmb_zone').val();
			if(typeof(measure_type) === "undefined"){
			   BootstrapDialog.alert({ title: 'Error ! ', message: 'Please select any General Or Steel ' });
			   Error++;
		    }else if(WorkName == ""){
			    BootstrapDialog.alert({ title: 'Error ! ', message: 'Please Select Name Of Work ' });
		        Error++;
		    }else if(item_no == ""){
				BootstrapDialog.alert({ title: 'Error ! ', message: 'Please Select Item No. ' });
				Error++;
			}/*else if(zone == ""){
				BootstrapDialog.alert({ title: 'Error ! ', message: 'Please select zone. ' });
				Error++;
			}*/
		}
		if(search_type == "ZW"){
			var measure_type    = $('input[type=radio][name=measure_type]:checked').val();
			var WorkName        = $('#cmb_shortname').val();
			var item_no         = $('#cmb_item_no').val();
			var zone            = $('#cmb_zone_no').val();
			if(typeof(measure_type) === "undefined"){
			   BootstrapDialog.alert({ title: 'Error ! ', message: 'Please select any General Or Steel ' });
			   Error++;
		    }else if(WorkName == ""){
			    BootstrapDialog.alert({ title: 'Error ! ', message: 'Please Select Name Of Work ' });
		        Error++;
		   /* }else if(item_no == ""){
				BootstrapDialog.alert({ title: 'Error ! ', message: 'Please Select Item No. ' });
				Error++;*/
			}else if(zone == ""){
				BootstrapDialog.alert({ title: 'Error ! ', message: 'Please select zone. ' });
				Error++;
			}
		}
		if(search_type == "DSW"){
			var Description = $('#txt_description').val();
			if(Description == ""){
				BootstrapDialog.alert({ title: 'Error ! ', message: 'Please Enter Description. ' });
				Error++;
			}
		}
		if(Error > 0){
			e.preventDefault();
			return false;
		}
	});
	function ClearInput(){
		//$("#rad_den").prop("checked",true);
		//$("#rad_ste").prop("checked",true);
		$('#txt_from_date').val('');
		$('#txt_to_date').val('');
		$('#txt_description').val('');
		$('#cmb_zone').chosen('destroy');
		$('#cmb_item_no').chosen('destroy');
		$('#cmb_shortname').chosen('destroy');
		$('#cmb_zone').val('');
		$('#cmb_item_no').val('');
		$('#cmb_shortname').val('');
		$('#cmb_zone').chosen();
		$('#cmb_item_no').chosen();
		$('#cmb_shortname').chosen();
	}
	/*$("input[name='searchtype']").change(function(){
			var pass_view = $(this).val();
			$(".common").prop("disabled",false);
			$(".common2").prop('disabled', false).trigger("chosen:updated");
			$(".common").removeClass("disable1");
			$(".common3").removeClass("disable1");
			
			
			$(".common").prop("disabled",true);
			$(".common2").prop('disabled', true).trigger("chosen:updated");
			$(".common").addClass("disable1");
			$(".common3").addClass("disable1");
			$(".clabel").css("color","#ACACAD");
			if(pass_view == 'DW'){
				$(".al").prop("disabled",false);
				$(".al").prop('disabled', false).trigger("chosen:updated");
				$(".al").removeClass("disable1");
				$(".deslabel").css("color","#000000");
				$("#rad_den").prop("checked",true);
			}
			if(pass_view == 'IW'){
				$(".al2").prop("disabled",false);
				$(".al2").prop('disabled', false).trigger("chosen:updated");
				$(".al2").removeClass("disable1");
				//$(".deslabel").css("color","#000000");
				$("#rad_den").prop("checked",true);
			}
			if(pass_view == 'ZW'){
				$(".al3").prop("disabled",false);
				$(".al3").prop('disabled', false).trigger("chosen:updated");
				$(".al3").removeClass("disable1");
				//$(".anolabel").css("color","#000000");
				$("#rad_den").prop("checked",true);
			}
			if(pass_view == 'DSW'){
				$(".al4").prop("disabled",false);
				$(".al4").prop('disabled', false).trigger("chosen:updated");
				$(".al4").removeClass("disable1");
				$(".deslabel").css("color","#000000");
				$("#rad_den").attr("checked",false);
		        //$("#rad_ste").prop("checked",false);
			}
			ClearInput();
	});*/
	function goBack()
	{
		url = "dashboard.php";
		window.location.replace(url);
	}			
</script>
<script> 
$("#cmb_shortname").chosen();
$("#cmb_zone_no").chosen();
$("#cmb_item_no").chosen();
	 $('input[type=radio][name=measure_type]').change(function() {
	    var WorkName        = $('#cmb_shortname').val();
		if(WorkName == ""){
			   BootstrapDialog.alert({ title: 'Error ! ', message: 'Please select Name of Work ' });
			   Error++;
		}
	});
	$('input[type=radio][name=searchtype]').change(function() {
	    var WorkName        = $('#cmb_shortname').val();
		if(WorkName == ""){
			   BootstrapDialog.alert({ title: 'Error ! ', message: 'Please select Name of Work ' });
			   Error++;
		}
	});
	
</script>
<style>
.inputGroup label::after {
    width: 12px;
    height: 13px;
}
</style>
</body>
</html>

