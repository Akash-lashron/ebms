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
?>

  <?php require_once "Header.html"; ?>
  <script>
  	 function goBack()
	 {
	   	url = "dashboard.php";
		window.location.replace(url);
	 }
	 function OpenInNewTabWinBrowser(url) 
	 {
	  	var win = window.open(url, '_blank');
	  	win.focus();
	 }
     function workorderdetail()
     { 
            var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_worder_details.php?workorderno=" + document.form.txt_workshortname.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText

                    if (data == "")
                    {
                        alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i++)
                        {
                            document.form.txt_workname.value 		= name[3];
							document.form.txt_workorder_no.value 	= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function zonename()
     	{ 
            var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_zone_name.php?workorderno=" + document.form.txt_workshortname.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
					$('#cmb_zone_name').chosen('destroy');
					document.form.cmb_zone_name.length=0;
					/*var optn1 	= document.createElement("option")
					optn1.value = "";
					optn1.text 	= "------------------------ Select Zone Name --------------------------";
					document.form.cmb_zone_name.options.add(optn1)*/
					
                    if (data == "")
                    {
                        //alert("No Records Found");
						var row1 = document.getElementById("zrow1").classList.add("hide");
						var row2 = document.getElementById("zrow2").classList.add("hide");
						var row3 = document.getElementById("zrow3").classList.add("hide");
						var optnall 	= document.createElement("option")
						optnall.value 	= "all";
						optnall.text 	= "All";
						document.form.cmb_zone_name.options.add(optnall);
                    }
                    else
                    {
						var row1 = document.getElementById("zrow1").classList.remove("hide");
						var row2 = document.getElementById("zrow2").classList.remove("hide");
						var row3 = document.getElementById("zrow3").classList.remove("hide");
						//var row3 = document.getElementById("zrow1").classList.add("show");
						//var row4 = document.getElementById("zrow2").classList.add("show");
							
						var optn1 	= document.createElement("option")
						optn1.value = "";
						optn1.text 	= "----------- Select Zone Name  ----------- ";
						document.form.cmb_zone_name.options.add(optn1)
                        var name = data.split("*");
                        for(i = 0; i < name.length; i+=2)
                        {
							var optn 	= document.createElement("option")
							optn.value 	= name[i];
							optn.text 	= name[i+1];
							document.form.cmb_zone_name.options.add(optn)
                        }
                    }
					$("#cmb_zone_name").chosen();
                }
            }
            xmlHttp.send(strURL);
        }
</script>
<script>
   $(function () {
		/*$.fn.validateworkorderno = function(event) { 
			if($("#txt_workshortname").val()==""){ 
				var a="Please Select Work Order Number";
				$('#val_woredrno').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else{
				var a="";
				$('#val_woredrno').text(a);
			}
		}
		$.fn.validatestartrow = function(event) { 
			if($("#txt_xl_startrow").val()==""){ 
				var a="Please Select Start Row of Excel Sheet";
				$('#val_xlstartrow').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else{
				var a="";
				$('#val_xlstartrow').text(a);
			}
		}
		$.fn.validateendrow = function(event) { 
			if($("#txt_xl_endrow").val()==""){ 
				var a="Please Select End Row of Excel Sheet";
				$('#val_xlendrow').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else{
				var a="";
				$('#val_xlendrow').text(a);
			}
		}
		$.fn.validatesheetname = function(event) { 
			if($("#txt_xl_sheetname").val()==""){ 
				var a="Please Select Excel Sheet Name";
				$('#val_xlsheetname').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else{
				var a="";
				$('#val_xlsheetname').text(a);
			}
		}
				
		$.fn.validatetype = function(event) { 
			if ($('[name="rad_measurementtype"]').is(':checked')){
				var a="";
				$('#val_measuretype').text(a);
			}
			else{
				var a="Please select Measurement Type";
				$('#val_measuretype').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
		}
		$.fn.validatezonename = function(event) { 
			if($("#cmb_zone_name").val()==""){ 
				var a="Please Select Zone Name";
				$('#val_zone_name').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else{
				var a="";
				$('#val_zone_name').text(a);
			}
		}
					
		$("#txt_workshortname").change(function(event){
			$(this).validateworkorderno(event);
		});
		$("#txt_xl_startrow").keyup(function(event){
			$(this).validatestartrow(event);
		});
		$("#txt_xl_endrow").keyup(function(event){
			$(this).validateendrow(event);
		});
		$("#txt_xl_sheetname").keyup(function(event){
			$(this).validatesheetname(event);
		});
		$("#rad_steel").change(function(event){
			$(this).validatetype(event);
		});
		$("#rad_others").change(function(event){
			$(this).validatetype(event);
		});
		$("#cmb_zone_name").change(function(event){
			$(this).validatezonename(event);
		});*/
				 	 
		/*$("#btn_upload").click(function(event){
			$(this).validateworkorderno(event);
			$(this).validatestartrow(event);
			$(this).validateendrow(event);
			$(this).validatesheetname(event);
			$(this).validatetype(event);
		});*/
	});
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                            <div class="title">Measurement Upload </div>
                <div class="container_12">
                    <div class="grid_12">
						<div align="right"><a href="MeasurementUpload_View.php">View&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
                        <blockquote class="bq1" style="overflow:auto">
							<!--<div align="right">
								<font style="font-size:12px; font-weight:bold; color:#0066FF" class="labeldisplay">
									Upload File Format :&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="" onClick="OpenInNewTabWinBrowser('MeasurementUpload_File_Sample_General.php');"><u>General</u>&nbsp;&nbsp;</a>&&nbsp;&nbsp;
									<a href="" onClick="OpenInNewTabWinBrowser('MeasurementUpload_File_Sample_Steel.php');"><u>Steel</u>&nbsp;&nbsp;&nbsp;&nbsp;</a>
								</font>
							</div>-->
							
							
							<div class="container">
								<div class="main-content">
									<div class="grid1"></div>
									<div class="grid10" align="right">
										<font style="font-size:11px; font-weight:bold; color:#0066FF" class="labeldisplay">
											Upload File Format :&nbsp;&nbsp;&nbsp;&nbsp;
											<a href="" onClick="OpenInNewTabWinBrowser('MeasurementUpload_File_Sample_General.php');"><u>General</u>&nbsp;&nbsp;</a>&&nbsp;&nbsp;
											<a href="" onClick="OpenInNewTabWinBrowser('MeasurementUpload_File_Sample_Steel.php');"><u>Steel</u>&nbsp;&nbsp;&nbsp;&nbsp;</a>
										</font>
									</div>
									<div class="grid1"></div>
									
									<div class="grid1"></div>
									<div class="main-content grid10 main-content-head">Measurement Upload Details</div>
									<div class="grid1"></div>
									
									<div class="grid1"></div>
									<div class="main-content grid10 main-content-body">
										<div class="main-content grid8 main-content-body" style="border:none">
											<div class="grid3" align="left">Work Short Name</div>
											<div class="grid9">
												<select name="txt_workshortname" id="txt_workshortname" onChange="workorderdetail();zonename();" tabindex="1">
													<option value=""> ----------- Select Work Short Name ---------- </option>
													<?php echo $objBind->BindWorkOrderNo(0);?>
												</select>
											</div>
											<div class="grid12 grid-empty"></div>
											<div class="grid3" align="left">Work Order No.</div>
											<div class="grid9">
												<input type="text" name='txt_workorder_no' id='txt_workorder_no' readonly="">
											</div>
											<div class="grid12 grid-empty"></div>
											<div class="grid3" align="left">Name of Work</div>
											<div class="grid9">
												<textarea name='txt_workname' id='txt_workname' readonly="readonly"  required rows="3" class="grid-textarea"></textarea>
											</div>
											<div class="grid12 grid-empty"></div>
											
											<div class="grid3" align="left">Measurement Type</div>
											<div class="main-content grid9 main-content-body" style="border:none; padding:0px !important;">
												<div class="grid6" style="border:none">
													<div class="inputGroup">
														<input id="rad_others" name="rad_measurementtype" type="radio" value="G"/>
														<label for="rad_others" style="padding:3px 0px; width:95%;" > &nbsp;GENERAL </label>
													</div>
												</div>
												<div class="grid6" style="border:none">
													<div class="inputGroup">
														<input id="rad_steel" name="rad_measurementtype" type="radio" value="S"/>
														<label for="rad_steel" style="padding:3px 0px; width:99%;" > &nbsp;STEEL </label>
													</div>
												</div>
											</div>
											<div class="grid12 grid-empty"></div>
											<div class="grid3 hide" align="left" id="zrow1">Zone Name </div>
											<div class="grid9 hide" id="zrow2">
												<select name='cmb_zone_name' id='cmb_zone_name'>
													<option value=""> ----------- Select Zone Name  ----------- </option>
												</select>
											</div>
											<div class="grid12 hide grid-empty" id="zrow3"></div>
											<div class="grid3" align="left">Sheet Name</div>
											<div class="grid9">
												<input type="text" name='txt_xl_sheetname' id='txt_xl_sheetname'>
											</div>
											<div class="grid12 grid-empty"></div>
											<div class="grid3" align="left">Start Row</div>
											<div class="grid3">
												<input type="text" name='txt_xl_startrow' id='txt_xl_startrow' value="7" readonly="">
											</div>
											<div class="grid3" align="center">End Row</div>
											<div class="grid3">
												<input type="text" name='txt_xl_endrow' id='txt_xl_endrow'>
											</div>
											<div class="grid12 grid-empty"></div>
											<div class="grid3" align="left">Upload File</div>
											<div class="grid9">
												<input type="file" class="text" name="file" id="file" style="height:25px;" />
											</div>
											<div class="grid10" align="right" style="color:#ACACAC">Upload files allow only the file formats of : .xls, .xlsx</div>
											
										</div>
										<div class="main-content grid4 main-content-body" style="border:none">
											<div class="grid12" style="border:1px solid #ACACAC">
												<img src="images/xls.png" style="opacity:0.5">
											</div>
										</div>
										<div class="grid12 grid-empty"></div>
									</div>
									<div class="grid1"></div>
								</div>
							</div>
							
                        	<!--<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
								<tr>
                                    <td width="21%">&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                   <td  class="labeldisplay">
										<select name="txt_workshortname" id="txt_workshortname" class="textboxdisplay" style="width:439px;height:22px;" onChange="workorderdetail();zonename();" tabindex="7">
											<option value=""> ----------- Select Work Short Name ---------- </option>
											<?php echo $objBind->BindWorkOrderNo(0);?>
										</select>
                                    </td>
                                </tr>
                                
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
									
                                    <td><input type="text" name='txt_workorder_no' id='txt_workorder_no' class="textboxdisplay" style="width:435px;"></td>
                                </tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Name</td>
									
                                    <td><textarea name='txt_workname' id='txt_workname' class="textboxdisplay" value="" rows="6" style="width:434px"></textarea></td>
                                </tr>
								
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Measurement Type</td>
									<td>
										<input type="radio" name="rad_measurementtype" id="rad_others" value="G">&nbsp;&nbsp;<label class="label">General</label>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rad_measurementtype" id="rad_steel" value="S">&nbsp;&nbsp;<label class="label">Steel</label>
									</td>
                                </tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_measuretype" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr class="hide" id="zrow1">
                                    <td>&nbsp;</td>
                                    <td class="label">Zone name</td>
									<td>
										<select name='cmb_zone_name' id='cmb_zone_name' class="textboxdisplay" style="width:435px;">
											<option value=""> --------------------------- Select Zone Name ----------------------------- </option>
										</select>
									</td>
                                </tr>
								<tr class="hide" id="zrow2"><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_zone_name" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Sheet name</td>
									<td>
										<input type="text" name='txt_xl_sheetname' id='txt_xl_sheetname' class="textboxdisplay" style="width:435px;">
									</td>
                                </tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_xlsheetname" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Start Row</td>
									<td>
										<input type="text" name='txt_xl_startrow' id='txt_xl_startrow' class="textboxdisplay" value="7" readonly="" style="width:135px;">
									</td>
                                </tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_xlstartrow" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">End Row</td>
									<td>
										<input type="text" name='txt_xl_endrow' id='txt_xl_endrow' class="textboxdisplay" style="width:135px;">
									</td>
                                </tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_xlendrow" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Upload File</td>
                                    <td><input type="file" class="text" name="file" style="height:25px; width:175px;" /></td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td colspan="3" align="center" class="labeldisplay smalllabcss" style="text-align:center">Upload files allow the file formats of : .xls  , .xlsx</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                </td>
                                </tr>
                            </table>-->
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection" style="width:115px">
								<input type="button"  class="backbutton" name="btn_upload" id="btn_upload" value="Upload File" />
								</div>
							</div>
                        </blockquote>
                    </div>
                </div>
            </div>
	  	</form>
		<style>
			#overlay {
			  background:rgba(0,0,0, 0.65); /* 65% black */
			  position:fixed;
			  width:100%; height:100%;
			  left:0; top:0;
			  z-index:9999;
			  display:none;
			}
			
			#overlay .indicator{
			  background:url('css/images/loading-animated-circle-32x32.gif') center no-repeat #FFF;
			  border-radius:8px;
			  width:50px;
			  height: 50px;
			  margin-left: -25px; /* half width */
			  margin-top: -25px; /* half height  */
			  position:fixed;
			  left:50%;
			  top:50%;  
			  box-shadow: 0 0 15px #000;
			}
			.inputGroup label::after {
				width: 10px;
				height: 12px;
				top: 49%;
				right:20px;
			}
		</style>
		<div id="overlay">
	  		<div class="indicator"></div>
		</div>

	  
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
<script>
    $(function () {
	/*$('#btn-overlay').click(function(e){ alert();
	  $('#overlay').show();
	});*/
		$("#txt_workshortname").chosen();
		function UploadMeasurements() 
		{
			//alert();
			
			var form = $('form')[0]; // You need to use standart javascript object here
			var formData = new FormData(form);
        	$.ajax({ 
				type      	: 'POST', 
				url       	: 'MeasurementUpload_Ajax.php',
				data	  	:  formData,
				contentType	:  false,       // The content type used when sending data to the server.
				cache		:  false,             // To unable request pages to be cached
				processData	:  false,        // To send DOMDocument or non processed data file it is set to false
				success   	: function(data) 
				{ //alert(data);
					var result 		= data.split("@@");
					var sheeterror 	= result[0];
					var formaterror = result[1];
					var msg 		= result[2];
					var TotalLines 	= result[3];
					var InsertLines = result[4];
					var NotUploaded = result[5];
					var ErrorLines	= Number(TotalLines)-Number(InsertLines);
					
					if(sheeterror == 0)
					{
						swal("Invalid Sheet Name.", "", "");
					}
					if(formaterror != "")
					{
						swal("Invalid Excel File Format.", "", "");
					}
					if(msg != "")
					{
						if(Number(ErrorLines) == 0){
							var UploadMsg = "Measurements Uploaded Sucessfully";
						}else{
							var UploadMsg = "Partial Measurements Uploaded";
						}
						var $ReturnMsg = $('<div></div>');
						$ReturnMsg.append('<div><i style="font-size:20px; color:#169C71;" class="fa">&#xf058;</i> '+UploadMsg+'</div>');
						$ReturnMsg.append('<div>&nbsp;</div>');
						$ReturnMsg.append('<div class="alert-success-row"><i style="font-size:20px; color:#0483B0;" class="fa">&#xf05a;</i>&nbsp;Total number of rows in Excel file : <span class="round-span">'+TotalLines+'</span></div>');
						$ReturnMsg.append('<div class="alert-success-row"><i style="font-size:20px; color:#169C71;" class="fa">&#xf058;</i>&nbsp;Total number of rows Uploaded : <span class="round-span">'+InsertLines+'</span></div>');
						$ReturnMsg.append('<div class="alert-danger-row"><i style="font-size:20px; color:#F30307;" class="fa">&#xf057;</i>&nbsp;Total number of rows not Uploaded : <span class="round-span">'+ErrorLines+'</span></div>');
						if(NotUploaded != ""){
							var SplitNotUploaded = NotUploaded.split(",");
							$ReturnMsg.append('<div class="alert-row-head">Not Uploaded Rows List</div>');
							var $RowList = $('<div class="alert-row-body"></div>');
							for(var i=0; i<SplitNotUploaded.length; i++){
								$RowList.append('<div class="grid_3 bottom-margin">Row : '+SplitNotUploaded[i]+'</div>');
							}
							$ReturnMsg.append($RowList);
							
						}
						BootstrapDialog.show({
							title: 'Uploaded Measurements Details',
							message: $ReturnMsg,
							buttons: [{
								label: 'OK',
								action: function(dialogRef){
									var sheetid = $("#txt_workshortname").val();
									if($('[name="rad_measurementtype"]').is(':checked')){
										var mtype = $("input:radio[name=rad_measurementtype]:checked").val();//$('[name="rad_measurementtype"]').val();
									}else{
										var mtype = "";
									}
									if(mtype == "S"){
										var url = "MeasurementUpload_View_Steel.php?sheetid="+sheetid;
										$(location).attr('href',url);
										$('#overlay').show();
									}else if(mtype == "G"){
										var url = "MeasurementUpload_View_General.php?sheetid="+sheetid;
										$(location).attr('href',url);
										$('#overlay').show();
									}else{
										$('#overlay').show();
										window.location.replace("MeasurementUpload.php");
									}
									dialogRef.close();
								}
							}]
						});
						
						
						
						/*swal({
							  title: "",
							  text: "Measurement Uploaded Sucessfully",
							  type: "success",
							  confirmButtonText: " OK ",
							},
							function(isConfirm){
							  	if(isConfirm) {
									var sheetid = $("#txt_workshortname").val();
									if($('[name="rad_measurementtype"]').is(':checked')){
										var mtype = $("input:radio[name=rad_measurementtype]:checked").val();//$('[name="rad_measurementtype"]').val();
									}else{
										var mtype = "";
									}
									if(mtype == "S"){
										var url = "MeasurementUpload_View_Steel.php?sheetid="+sheetid;
										$(location).attr('href',url);
									}else if(mtype == "G"){
										var url = "MeasurementUpload_View_General.php?sheetid="+sheetid;
										$(location).attr('href',url);
									}else{
										window.location.replace("MeasurementUpload.php");
									}
							  	} 
							});*/
					}	  
				}
        	});
		}
		$("#btn_upload").click(function () {   
            //UploadMeasurements();
			var error = 0;
			var workordername 	= $("#txt_workshortname").val();
			var startrow 		= $("#txt_xl_startrow").val();
			var endrow 			= $("#txt_xl_endrow").val();
			var xlsheetname 	= $("#txt_xl_sheetname").val();
			var zone_name 		= $("#cmb_zone_name").val();
			var mtype = $('input[type=radio][name=rad_measurementtype]:checked').val();
			if(workordername == ""){
				var a1	=	"Please Select Work Short Name";
				BootstrapDialog.alert(a1);
				error = 1;
			}else if(mtype == undefined){
				var a5	= 	"Please Select Measurement Type"
				BootstrapDialog.alert(a5);
				error = 1;
			}else if(xlsheetname == ""){
				var a4	=	"Please Enter Sheet Name of Excel Sheet";
				BootstrapDialog.alert(a4);
				error = 1;
			}else if(startrow == ""){
				var a2	=	"Please Enter Start Row of Excel Sheet";
				BootstrapDialog.alert(a2);
				error = 1;
			}else if(endrow == ""){
				var a3	=	"Please Enter End Row of Excel Sheet";
				BootstrapDialog.alert(a3);
				error = 1;
			}else if(zone_name == ""){
				var a6	=	"Please Select Zone Name";
				BootstrapDialog.alert(a6);
				error = 1;
			}else{
				error = 0;
			}
			
			if(error == 0){
				UploadMeasurements();
			}else{
				event.preventDefault();
				event.returnValue = false;
			}
			
        });
		
		function readURL(input){
			if(input.files && input.files[0]){
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#img-upload').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		$("#file").change(function(){
			readURL(this);
		}); 
		
		
		//BootstrapDialog.alert('I want banana!');
	});
</script>
    </body>
</html>
