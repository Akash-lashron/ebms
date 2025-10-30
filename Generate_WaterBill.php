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
   	$sheetid 			= 	trim($_POST['cmb_shortname']);
	$rbn 				= 	trim($_POST['txt_rbn']);
	$meter_no 			= 	trim($_POST['txt_meterno']);
	$wbill_no 			= 	trim($_POST['txt_billno']);
    $imr 				= 	trim($_POST['txt_initial']);
    $imr_date 			= 	dt_format(trim($_POST['txt_initial_date']));
	$fmr 				= 	trim($_POST['txt_final']);
    $fmr_date 			= 	dt_format(trim($_POST['txt_final_date']));
    $rate 				= 	trim($_POST['txt_rate']);
	$meter_rent 		= 	trim($_POST['txt_meter_rent']);
	$w_limit			= 	trim($_POST['txt_limit']);
    $water_cost			= 	trim($_POST['txt_water_cost']);
    $wr_date 			= 	dt_format(trim($_POST['txt_date']));
	if(($rbn != "") && ($sheetid != ""))
	{
		$select_query = "select * from measurementbook where sheetid = '$sheetid' and rbn = '$rbn'";
		$select_sql = mysql_query($select_query);
		//echo $select_query;
		if($select_sql == true)
		{
			if(mysql_num_rows($select_sql) == 0)
			{
				$delete_query = "delete from generate_waterbill where sheetid = '$sheetid' and rbn = '$rbn'";
				$delete_sql = mysql_query($delete_query);
    			$erecovery_sql 		= 	"INSERT INTO generate_waterbill set
                                            sheetid 		= '$sheetid',
											rbn 			= '$rbn',
                                            meter_no 		= '$meter_no',
											wbill_no 		= '$wbill_no',
											imr 			= '$imr',
                                            imr_date 		= '$imr_date',
											fmr 			= '$fmr',
                                            fmr_date 		= '$fmr_date',
                                            rate 			= '$rate',
											meter_rent 		= '$meter_rent',
											w_limit 		= '$w_limit',
											water_cost 		= '$water_cost',
                                            wr_date 		= '$wr_date',
											staffid 		= '$staffid',
                                            userid 			= '$userid',
											modifieddate 	= NOW(),
											active 			= 1";
											//modifieddate = NOW()";
    			$erecovery_query 	= 	mysql_query($erecovery_sql);
			}
		}
	}
	//echo $erecovery_sql;
    if($erecovery_query == true) 
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
							//document.form.txt_techsanction.value 	= name[0];
							//document.form.txt_agreemntno.value 		= name[2];
                            document.form.txt_workname.value 		= name[3];
							document.form.txt_workorder.value 		= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function getrbn()
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
            strURL = "findabstract_mbookno.php?sheetid=" + document.form.cmb_shortname.value;
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
                            document.form.txt_rbn.value = name[3];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function recovery()
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
            strURL = "find_water_recovery.php?workorderno=" + document.form.cmb_shortname.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
					//alert(data);
                    if (data == "")
                    {
                        alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i++)
                        {
							var fmr 		= name[7];
							var fmr_date	= name[8];
							if(fmr != "")
							{
								document.form.txt_initial.value 		= fmr;
								document.form.txt_initial_date.value 	= fmr_date;
							}
							else
							{
								document.form.txt_initial.value 		= name[1];
								document.form.txt_initial_date.value 	= name[2];
							}
                            document.form.txt_meterno.value 		= name[0];
							//document.form.txt_initial.value 		= name[1];
							//document.form.txt_initial_date.value 	= name[2];
							document.form.txt_rate.value 			= name[3];
							document.form.txt_meter_rent.value 		= name[4];
							document.form.txt_limit.value 			= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function calculateEBamount()
		{
			
			var imr 		= Number(document.form.txt_initial.value);
			var fmr 		= Number(document.form.txt_final.value);
			
			if(fmr>imr)
			{
				var unitrate 	= document.form.txt_rate.value;
				var meterrent 	= document.form.txt_meter_rent.value;
				var usedunit = Number(fmr)-Number(imr);
				var usedamount = (Number(unitrate)*Number(usedunit))+Number(meterrent);
				//alert(usedamount);
				document.form.txt_water_cost.value = usedamount.toFixed(2);
			}
			else
			{
				swal("FMR should be greater than IMR", "", "");
			}
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
		$( "#txt_final_date" ).datepicker({
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
		$.fn.validaterbn = function(event) { 
					if($("#txt_rbn").val()==""){ 
					var a="Please Enter RBN No.";
					$('#val_rbn').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_rbn').text(a);
					}
				}
		$.fn.validatebillno = function(event) { 
					if($("#txt_billno").val()==""){ 
					var a="Please Enter Water Bill No.";
					$('#val_billno').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_billno').text(a);
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
		$.fn.validatefinal = function(event) { 
					if($("#txt_final").val()==""){ 
					var a="Please Enter Final Reading";
					$('#val_final').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_final').text(a);
					}
				}
		$.fn.validatefinaldate = function(event) { 
					if($("#txt_final_date").val()==""){ 
					var a="Please Select Final Reading Date";
					$('#val_finaldate').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_finaldate').text(a);
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
					var a="Please Enter Water Limit";
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
		$.fn.validatemeterrent = function(event) { 
					if($("#txt_meter_rent").val()==""){ 
					var a="Please Enter Meter Rent";
					$('#val_meter_rent').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_meter_rent').text(a);
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
				
		$("#cmb_shortname").change(function(event){
		$(this).validateshortname(event);
		});
		$("#txt_workname").keyup(function(event){
		$(this).validateworkname(event);
		});
		$("#txt_workorder").keyup(function(event){
		$(this).validateworkorder(event);
		});
		$("#txt_rbn").keyup(function(event){
		$(this).validaterbn(event);
		});
		$("#txt_billno").keyup(function(event){
		$(this).validatebillno(event);
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
		$("#txt_final").keyup(function(event){
		$(this).validatefinal(event);
		});
		$("#txt_final_date").change(function(event){
		$(this).validatefinaldate(event);
		});
		$("#txt_rate").keyup(function(event){
		$(this).validaterate(event);
		});
		$("#txt_meter_rent").keyup(function(event){
		$(this).validatemeterrent(event);
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
		$(this).validaterbn(event);
		$(this).validatebillno(event);
		$(this).validatementerno(event);
		$(this).validateinitial(event);
		$(this).validateinitialdate(event);
		$(this).validatefinal(event);
		$(this).validatefinaldate(event);
		$(this).validaterate(event);
		$(this).validatelimit(event);
		$(this).validatemeterrent(event);
		$(this).vaidatedate(event);
		calculateEBamount();
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

						<!--<div align="right"><a href="View_Water_generate_Bill.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1">
                            <div class="title">Generate - Water Bill</div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        <table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();getrbn();recovery();">
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
                                    <td class="label">RBN No.</td>
                                    <td><input type="text" name='txt_rbn' id='txt_rbn' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rbn" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Meter No.</td>
                                    <td><input type="text" name='txt_meterno' id='txt_meterno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_meterno" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Water Bill No.</td>
                                    <td><input type="text" name='txt_billno' id='txt_billno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_billno" style="color:red" colspan="">&nbsp;</td></tr>
								
								<!--<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Abstract Net Amount (Rs)</td>
                                    <td>
										<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label class="label">RAB No. </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_initial_date' id='txt_initial_date' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_initial" style="color:red" colspan="">&nbsp;</td></tr>-->
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Initial Meter Reading (IMR)</td>
                                    <td>
										<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                                    <td class="label">Final Meter Reading (FMR)</td>
                                    <td>
										<input type="text" name='txt_final' id='txt_final' onBlur="calculateEBamount();" class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label class="label">FMR Date </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_final_date' id='txt_final_date' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_final"></span>
									<span id="val_finaldate"></span>
									&nbsp;
									</td>
								</tr>	
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Rate of Water ( Rs.)</td>
                                    <td>
									<input type="text" name='txt_rate' id='txt_rate' onBlur="calculateEBamount();" class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
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
									<td class="label"> Rent for Meter ( Rs.)</td>
									<td>
										<input type="text" name='txt_meter_rent' id='txt_meter_rent' onBlur="calculateEBamount();" class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_meter_rent" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Date</td>
                                    <td><input type="text" name="txt_date" id='txt_date' class="textboxdisplay" style="width: 120px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_date" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
									<td>&nbsp;</td>
									<td class="label">Water Charges (Rs.)</td>
									<td>
										<input type="text" name='txt_water_cost' id='txt_water_cost' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;">
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_water_cost" style="color:red" colspan="">&nbsp;</td></tr>
							
							</table>
								
							<!--<table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
								<tr>
									<td>&nbsp;</td>
									<td colspan="5" style="background-color:#D8D8D8; height:25px; vertical-align:middle" class="label"> &nbsp;Electricity Recovery Details</td>
								</tr>
								<tr>
									<td width="18%">&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td width="18%">&nbsp;</td>
								</tr>
								<tr>
									<td width="18%">&nbsp;</td>
									<td class="label" width="227px;">Meter No.</td>
									<td colspan="4">
										<input type="text" name='txt_meterno' id='txt_meterno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 430px;">
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_meterno" style="color:red" colspan="4">&nbsp;</td></tr>
								<tr>
									<td>&nbsp;</td>
									<td class="label">Initial Meter Reading</td>
									<td colspan="4">
										<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label class="label">IMR Date </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_initial_date' id='txt_initial_date' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="4">
									<span id="val_initial"></span>
									<span id="val_initialdate"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td class="label">Final Meter Reading</td>
									<td colspan="4">
										<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label class="label">FMR Date </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_initial_date' id='txt_initial_date' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="4">
									<span id="val_initial"></span>
									<span id="val_initialdate"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td class="label">Rate of Electricity ( Rs.)</td>
									<td colspan="4">
										<input type="text" name='txt_rate' id='txt_rate' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;
										<label class="label"> /&nbsp;unit </label>
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rate" style="color:red" colspan="4">&nbsp;</td></tr>
								<tr>
									<td>&nbsp;</td>
									<td class="label">Date</td>
									<td colspan="4"><input type="text" name="txt_date" id='txt_date' class="textboxdisplay" style="width: 120px;"></td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_date" style="color:red" colspan="4">&nbsp;</td></tr>
							</table>-->
                            
									<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<?php 
										if($_GET['sheet_id'] != '')
										{ 
										?>
											<input type="submit" name="update" id="update" value=" Update "/>
										<?php
										}
										else
										{
										?>
											<input type="submit" name="submit" id="submit" value=" Submit "/>
										<?php
										}
										?>
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
