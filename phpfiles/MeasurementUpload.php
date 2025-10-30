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
					
					document.form.cmb_zone_name.length=0;
					var optn1 	= document.createElement("option")
					optn1.value = "";
					optn1.text 	= "------------------------ Select Zone Name --------------------------";
					document.form.cmb_zone_name.options.add(optn1)
					
					var optnall 	= document.createElement("option")
					optnall.value 	= "all";
					optnall.text 	= "All";
					document.form.cmb_zone_name.options.add(optnall);
					
                    if (data == "")
                    {
                        alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i+=2)
                        {
							var optn 	= document.createElement("option")
							optn.value 	= name[i];
							optn.text 	= name[i+1];
							document.form.cmb_zone_name.options.add(optn)
                        }
                    }
                }
            }
            xmlHttp.send(strURL);
        }
</script>
<script>
   $(function () {
                   
				
				$.fn.validateworkorderno = function(event) { 
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
				$.fn.validatezonename = function(event) { 
					if($("#cmb_zone_name").val()=="")
					{ 
					var a="Please Select Zone Name";
					$('#val_zone_name').text(a);
					event.preventDefault();
					event.returnValue = false;
					}
					else
					{
					var a="";
					$('#val_zone_name').text(a);
					}
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
				});
				 	 
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

                <div class="container_12">
                    <div class="grid_12">

						<div align="right"><a href="MeasurementUpload_View.php">View&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
                        <blockquote class="bq1">
                            <div class="title">Measurement Upload </div>
							<div align="right">
								<font style="font-size:12px; font-weight:bold; color:#0066FF">
									Upload File Format :&nbsp;&nbsp;&nbsp;&nbsp;
									<!--<a href="download.php?filename=Upload_Format_General.xlsx"><u>General</u>&nbsp;&nbsp;</a>&&nbsp;&nbsp;
									<a href="download.php?filename=Upload_Format_Steel.xlsx"><u>Steel</u>&nbsp;&nbsp;&nbsp;&nbsp;</a>-->
									<a href="" onClick="OpenInNewTabWinBrowser('MeasurementUpload_File_Sample_General.php');"><u>General</u>&nbsp;&nbsp;</a>&&nbsp;&nbsp;
									<a href="" onClick="OpenInNewTabWinBrowser('MeasurementUpload_File_Sample_Steel.php');"><u>Steel</u>&nbsp;&nbsp;&nbsp;&nbsp;</a>
								</font>
							</div>
                        <table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="21%">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                   <td  class="labeldisplay">
										<select name="txt_workshortname" id="txt_workshortname" class="textboxdisplay" style="width:439px;height:22px;" onChange="workorderdetail();zonename();" tabindex="7">
											<option value=""> ----------------------- Select Work Short Name ------------------------ </option>
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
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Zone name</td>
									<td>
										<select name='cmb_zone_name' id='cmb_zone_name' class="textboxdisplay" style="width:435px;">
											<option value=""> --------------------------- Select Zone Name ----------------------------- </option>
										</select>
									</td>
                                </tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_zone_name" style="color:red" colspan="">&nbsp;</td></tr>
								
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
                                    <td colspan="3" align="center" class="smalllabcss">Upload files allow the file formats of : .xls  , .xlsx</td>
                                </tr>
                                <!--<tr><td>&nbsp;</td></tr>-->
                                <tr>
                                    <td colspan="3">
                                <!--<center>
								<input type="button"  class="backbutton" name="btn_upload" id="btn_upload" value="Upload File" />&nbsp;&nbsp;&nbsp;
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
                                </center>-->
                                </td>
                                </tr>
                            </table>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection" style="width:115px">
								<input type="button"  class="backbutton" name="btn_upload" id="btn_upload" value="Upload File" />
								</div>
							</div>
                            <!--</td>
                            </tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><td width="500" colspan="5" class="green">
                                </td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr class="labelcenter">
                                <td colspan="5" align="center">&nbsp;

                                </td>
                            </tr>
                            <tr><td colspan="5">&nbsp;</td></tr>
                            </table>-->
                        </blockquote>
						
                    </div>

                </div>
            </div>
	  </form>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
  
    </body>
</html>
