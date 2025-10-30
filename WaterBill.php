<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
$msg = '';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);

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
    return $dd . '-' . $mm . '-' . $yy;
}
if (isset($_POST["submit"])) 
{
   	$sheetid 		= 	trim($_POST['cmb_shortname']);
	$meter_no 		= 	trim($_POST['txt_meterno']);
    $imr 			= 	trim($_POST['txt_initial']);
    $imr_date 		= 	dt_format(trim($_POST['txt_initial_date']));
    $rate 			= 	trim($_POST['txt_rate']);
	$meter_rent 	= 	trim($_POST['txt_meter_rent']);
    $limit 			= 	trim($_POST['txt_limit']);
    $wr_date 		= 	dt_format(trim($_POST['txt_date']));
    $wrecovery_sql 	= 	"INSERT INTO water_recovery set
                                            sheetid = '$sheetid',
                                            meter_no = '$meter_no',
											imr = '$imr',
                                            imr_date = '$imr_date',
                                            rate = '$rate',
											meter_rent = '$meter_rent',
                                            w_limit = '$limit',
                                            wr_date = '$wr_date',
											staffid = '$staffid',
                                            userid = '$userid',
											modifieddate = NOW(),
											active = 1";
											//modifieddate = NOW()";
    $wrecovery_query 	= 	mysql_query($wrecovery_sql);
	//echo $wrecovery_sql;
    if($wrecovery_query == true) 
	{
        $msg = "Water Charge Details Stored Successfully ";
		$success = 1;
    }
	else
	{
		$msg = " Something Error...!!! ";
		//die(mysql_error());
	}
} 
?>

  <?php require_once "Header.html"; ?>
<style>
    
</style>
<script>
  	 function goBack()
	 {
	   	url = "dashboard.php";
		window.location.replace(url);
	 }
     function workorderdetail()
     { //alert()
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
            strURL = "find_worder_details.php?workorderno=" + document.form.cmb_shortname.value;
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
                            document.form.txt_workname.value 	= name[3];
							document.form.txt_workorder.value 	= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function recoverydetail()
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
            strURL = "find_recovery_details.php?workorderno=" + document.form.cmb_shortname.value;
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
                        alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i++)
                        {
                            document.form.txt_rate.value 	= name[7];
							document.form.txt_limit.value 	= name[8];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function WaterMeterDetail()
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
            strURL = "find_water_meter.php?workorderno=" + document.form.cmb_shortname.value;
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
                        alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i++)
                        {
                            document.form.txt_meterno.value 	= name[0];
							document.form.txt_meter_rent.value 	= name[1];
							document.form.txt_limit.value 	= name[1];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
</script>
<script>
   $(function () {
        $( "#txt_initial_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });
		$( "#txt_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });	
        $.fn.validateshortname = function(event) { 
					if($("#cmb_shortname").val()==""){ 
					var a="Please Select Work Short Name";
					$('#val_shortname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_shortname').text(a);
					}
				}
		$.fn.validateworkname = function(event) { 
					if($("#txt_workname").val()==""){ 
					var a="Please Enter Work Name";
					$('#val_wname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_wname').text(a);
					}
				}
		$.fn.validateworkorder = function(event) { 
					if($("#txt_workorder").val()==""){ 
					var a="Please Enter Work Order Number";
					$('#val_workorder').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_workorder').text(a);
					}
				}
		$.fn.validatementerno = function(event) { 
					if($("#txt_meterno").val()==""){ 
					var a="Please Enter Meter No.";
					$('#val_meterno').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_meterno').text(a);
					}
				}
		$.fn.validateinitial = function(event) { 
					if($("#txt_initial").val()==""){ 
					var a="Please Enter Initial Reading";
					$('#val_initial').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_initial').text(a);
					}
				}
		$.fn.validateinitialdate = function(event) { 
					if($("#txt_initial_date").val()==""){ 
					var a="Please Select Initial Reading Date";
					$('#val_initialdate').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_initialdate').text(a);
					}
				}
		$.fn.validaterate = function(event) { 
					if($("#txt_rate").val()==""){ 
					var a="Please Enter Rate";
					$('#val_rate').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_rate').text(a);
					}
				}
		$.fn.validatelimit = function(event) { 
					if($("#txt_limit").val()==""){ 
					var a="Please Enter Limit";
					$('#val_limit').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_limit').text(a);
					}
				}		
		$.fn.vaidatedate = function(event) { 
					if($("#txt_date").val()==""){ 
					var a="Please Select Date";
					$('#val_date').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_date').text(a);
					}
				}			
				
		$("#cmb_shortname").keyup(function(event){
		$(this).validateshortname(event);
		});
		$("#txt_workname").keyup(function(event){
		$(this).validateworkname(event);
		});
		$("#txt_workorder").keyup(function(event){
		$(this).validateworkorder(event);
		});
		$("#txt_meterno").keyup(function(event){
		$(this).validatementerno(event);
		});
		$("#txt_initial").keyup(function(event){
		$(this).validateinitial(event);
		});
		$("#txt_initial_date").change(function(event){
		$(this).validateinitialdate(event);
		});
		$("#txt_rate").keyup(function(event){
		$(this).validaterate(event);
		});
		$("#txt_limit").keyup(function(event){
		$(this).validatelimit(event);
		});
		$("#txt_date").change(function(event){
		$(this).vaidatedate(event);
		});
		$("#top").submit(function(event){
		$(this).validateshortname(event);
		$(this).validateworkname(event);
		$(this).validateworkorder(event);
		$(this).validatementerno(event);
		$(this).validateinitial(event);
		$(this).validateinitialdate(event);
		$(this).validaterate(event);
		$(this).validatelimit(event);
		$(this).vaidatedate(event);
		});
   
            });
	   function goBack()
	   {
	   		url = "dashboard.php";
			window.location.replace(url);
	   }
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

						<div align="right"><a href="AgreementEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>
                        <blockquote class="bq1">
                            <div class="title">Water Bill</div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        <table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();recoverydetail();WaterMeterDetail();">
											<option value="">----------------------- Select Work Short Name ------------------------</option>
											<?php echo $objBind->BindWorkOrderNo(0);?>
										</select>
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_shortname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td><textarea name='txt_workname' id='txt_workname' class="textboxdisplay" rows="6" style="width: 465px;"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
                                    <td><input type="text" name='txt_workorder' id='txt_workorder' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_workorder" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Meter No.</td>
                                    <td><input type="text" name='txt_meterno' id='txt_meterno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_meterno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Initial Meter Reading (IMR)</td>
                                    <td>
									<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<label class="label">IMR Date </label>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="text" name='txt_initial_date' id='txt_initial_date' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_initial"></span>
									<span id="val_initialdate"></span>
									&nbsp;
									</td>
								</tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Rate of Water ( Rs.)</td>
                                    <td>
									<input type="text" name='txt_rate' id='txt_rate' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									&nbsp;&nbsp;
									<label class="label"> /&nbsp;&nbsp; </label>
									<input type="text" name='txt_limit' id='txt_limit' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									<label class="label"> &nbsp;&nbsp;Liters </label>
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_rate" style="padding-right:44px"></span>
									<span id="val_limit"></span>
									&nbsp;
									</td>
								</tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Rent for Meter ( Rs.)</td>
                                    <td>
									<input type="text" name='txt_meter_rent' id='txt_meter_rent' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_meter_rent" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Date</td>
                                    <td><input type="text" name="txt_date" id='txt_date' class="textboxdisplay" style="width: 120px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_date" style="color:red" colspan="">&nbsp;</td></tr>
							</table>
                            
									<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
											<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
											<input type="submit" name="submit" id="submit" value=" Save "/>
										</div>
									</div>
                        </blockquote>
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
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
        </form>
    </body>
</html>
