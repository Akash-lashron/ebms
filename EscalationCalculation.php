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
	//$meter_no 		= 	trim($_POST['txt_meterno']);
    //$imr 			= 	trim($_POST['txt_initial']);
    //$imr_date 		= 	dt_format(trim($_POST['txt_initial_date']));
    //$rate 			= 	trim($_POST['txt_rate']);
	//$meter_rent 	= 	trim($_POST['txt_meter_rent']);
    //$limit 			= 	trim($_POST['txt_limit']);
    //$er_date 		= 	dt_format(trim($_POST['txt_date']));
    /*$erecovery_sql 	= 	"INSERT INTO electricity_recovery set
                                            sheetid = '$sheetid',
                                            meter_no = '$meter_no',
											imr = '$imr',
                                            imr_date = '$imr_date',
                                            rate = '$rate',
											meter_rent = '$meter_rent',
                                            e_limit = '$limit',
                                            er_date = '$er_date',
											staffid = '$staffid',
                                            userid = '$userid',
											modifieddate = NOW(),
											active = 1";*/
											//modifieddate = NOW()";
    //$erecovery_query 	= 	mysql_query($erecovery_sql);
	//echo $erecovery_sql;
	$rec = explode(".", $_POST['add_set_a1']);
	//echo $_POST['add_set_a1'];exit;
	for ($c = 0; $c < count($rec); $c++) 
	{
		$x = $rec[$c];
		if($x != "")
		{
			$meter_no	=	chop($_POST['txt_meter_no'.$x]);
			$imr		=	chop($_POST['txt_imr'.$x]);
			$imr_date	=	dt_format(chop($_POST['txt_imr_date'.$x]));
			$rate		=	chop($_POST['txt_rate_unit'.$x]);
			$meter_rent	=	chop($_POST['txt_rent'.$x]);
			$factor		=	chop($_POST['txt_factor'.$x]);
			$limit		=	"";
			if($meter_no != "")
			{
				$erecovery_sql 	= 	"INSERT INTO electricity_recovery set
													sheetid = '$sheetid',
													meter_no = '$meter_no',
													imr = '$imr',
													imr_date = '$imr_date',
													rate = '$rate',
													meter_rent = '$meter_rent',
													factor = '$factor',
													e_limit = '$limit',
													staffid = '$staffid',
													userid = '$userid',
													modifieddate = NOW(),
													active = 1";
				//$erecovery_query 	= 	mysql_query($erecovery_sql);
			}
		}
	}
    if($erecovery_query == true) 
	{
        $msg = "Electricity Charge Details Stored Successfully ";
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
	var add_row_s 		= 5;
	var prev_edit_row 	= 0;
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
                            document.form.txt_workname.value 	= name[3];
							document.form.txt_workorder.value 	= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
</script>
<script>
   $(function () {
        $('.date-picker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'M-yy',
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
				function isNumberKey(evt, element) 
				{
					var charCode = (evt.which) ? evt.which : event.keyCode
					if (charCode > 31 && (charCode < 48 || charCode > 57) && !(charCode == 46 || charcode == 8))
						return false;
					else 
					{
						var len = $(element).val().length;
						var index = $(element).val().indexOf('.');
						if (index > 0 && charCode == 46) 
						{
						  return false;
						}
						if (index > 0) 
						{
						  var CharAfterdot = (len + 1) - index;
						  if (CharAfterdot > 3) 
						  {
							return false;
						  }
						}
					
					}
					return true;
				}
		/*$( "#txt_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });*/	
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
		$.fn.validatemeterno = function(event) { 
					if($("#txt_meter_no").val()==""){ 
					var a="Please Enter Meter No.";
					//$('#val_meterno').text(a);
					swal(a, "", "");
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_meterno').text(a);
					}
				}
		$.fn.validateimr = function(event) { 
					if($("#txt_imr").val()==""){ 
					var a="Please Enter Initial Reading";
					swal(a, "", "");
					//$('#val_initial').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_initial').text(a);
					}
				}
		$.fn.validateimrdate = function(event) { 
					if($("#txt_imr_date").val()==""){ 
					var a="Please Select Initial Reading Date";
					swal(a, "", "");
					//$('#val_initialdate').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_initialdate').text(a);
					}
				}
		$.fn.validaterate = function(event) { 
					if($("#txt_rate_unit").val()==""){ 
					var a="Please Enter Rate";
					swal(a, "", "");
					//$('#val_rate').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_rate').text(a);
					}
				}			
		$.fn.vaidaterent = function(event) { 
					if($("#txt_rent").val()==""){ 
					var a="Please Select Meter Rent";
					swal(a, "", "");
					//$('#val_date').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_date').text(a);
					}
				}
		$.fn.vaidatefactor = function(event) { 
					if($("#txt_factor").val()==""){ 
					var a="Please Enter Factor.";
					swal(a, "", "");
					//$('#val_date').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_date').text(a);
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
		$("#txt_meter_no").keyup(function(event){
		$(this).validatemeterno(event);
		});
		$("#txt_imr").keyup(function(event){
		$(this).validateimr(event);
		});
		$("#txt_imr_date").change(function(event){
		$(this).validateimrdate(event);
		});
		$("#txt_rate_unit").keyup(function(event){
		$(this).validaterate(event);
		});
		$("#txt_rent").keyup(function(event){
		$(this).vaidaterent(event);
		});
		$("#txt_factor").keyup(function(event){
		$(this).vaidatefactor(event);
		});
		$("#top").submit(function(event){
		$(this).validateshortname(event);
		$(this).validateworkname(event);
		$(this).validateworkorder(event);
		//$(this).validatemeterno(event);
		//$(this).validateimr(event);
		//$(this).validateimrdate(event);
		//$(this).validaterate(event);
		//$(this).vaidaterent(event);
		//$(this).vaidatefactor(event);
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
<style>
input[type="date"]:before {
    content: attr(placeholder) !important;
    color: #aaa;
    margin-right: 0.5em;
  }
  input[type="date"]:focus:before,
  input[type="date"]:valid:before {
    content: "";
  }
.extraItemTextbox {
    height: 30px;
    position: relative;
    outline: none;
    border: 1px solid #98D8FE;
   /* border-color: rgba(0,0,0,.15);*/
    background-color: white;
	color:#0000cc;
	width:98%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	text-align:center;
}
.extraItemTextArea
{
    position: relative;
    outline: none;
    /*border: 1px solid #cdcdcd;*/
	border: 1px solid #98D8FE;
    /*border-color: rgba(0,0,0,.15);*/
    background-color: white;
	color:#0000cc;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
}
.extraItemTextboxDisable {
    height: 30px;
    position: relative;
    outline: none;
   /* border: 1px solid #EAEAEA;*/
   border:none;
    background-color: #EAEAEA;
	color:#0000cc;
	width:98%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	text-align:center;
	vertical-align:middle;
	cursor:default;
}
.gradientbg {
  /* fallback */
  background-color: #014D62;
  width:90%; height:25px; color:#FFFFFF; vertical-align:middle;
  background: url(images/linear_bg_2.png);
  background-repeat: repeat-x;

  /* Safari 4-5, Chrome 1-9 */
  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#037595), to(#0A9CC5));

  /* Safari 5.1, Chrome 10+ */
  background: -webkit-linear-gradient(top, #0A9CC5, #037595);

  /* Firefox 3.6+ */
  background: -moz-linear-gradient(top, #0A9CC5, #037595);

  /* IE 10 */
  background: -ms-linear-gradient(top, #0A9CC5, #037595);

  /* Opera 11.10+ */
  background: -o-linear-gradient(top, #0A9CC5, #037595);
}
.buttonstyle
{
	background-color:#0A9CC5;
	/*width:80px;*/
	height:25px;
	color:#FFFFFF;
	-moz-box-shadow: 0px 1px 0px 0px #0A9CC5;
	-webkit-box-shadow: 0px 1px 0px 0px #0A9CC5;
	box-shadow: 0px 1px 0px 0px #0A9CC5;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0080FF), color-stop(1, #0A9CC5));
	background:-moz-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-webkit-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-o-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-ms-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:linear-gradient(to bottom, #0080FF 5%, #0A9CC5 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0080FF', endColorstr='#0A9CC5',GradientType=0);
	border:1px solid #0080FF;
	display:inline-block;
	cursor:pointer;
	font-weight:bold;

}
.buttonstyle:hover
{
	/*font-size:14px;*/
	/*padding: 0.1em 1em;*/
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E80017;
	border:1px solid #E80017;
}
.buttonstyledisable
{
	background-color:#CECECE;
	color:#A0A0A0;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #E6E6E6), color-stop(1, #CECECE));
	background:-moz-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-webkit-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-o-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-ms-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:linear-gradient(to bottom, #E6E6E6 5%, #CECECE 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#E6E6E6', endColorstr='#CECECE',GradientType=0);
	border:1px solid #CECECE;
}
.buttonstyledisable:hover
{
	/*font-size:14px;*/
	/*padding: 0.1em 1em;*/
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E6E6E6;
	border:1px solid #E6E6E6;
}
sub {font-size:xx-small; vertical-align:sub;}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="AgreementEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1">
                            <div class="title">Escalation Calculation</div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        <!--<table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();">
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
									<td colspan="3" align="center">-->
										<div style="width:100%;" class="label" align="center">&nbsp;</div>
										<!--<div style="width:100%;" class="label gradientbg" align="center">Price Index</div>-->
										<div style="width:100%; height:auto;" align="center">
											<table width="100%" class="table1" id="table1">
												<tr class="label" style="background-color:#EAEAEA; height:35px;">
													<td align="center" rowspan="2" valign="middle" nowrap="nowrap">&nbsp;</td>
													<td align="center" rowspan="2" colspan="2" valign="middle" nowrap="nowrap">Base Index</td>
													<td align="center" rowspan="2" colspan="2" valign="middle">Escalation Breakup</td>
													<!--<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox" name="txt_material_desc" id="txt_material_desc">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox" name="txt_material_desc" id="txt_material_desc">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox" name="txt_material_desc" id="txt_material_desc">
													</td>-->
													<td align="center" valign="middle">
														Month - 1
													</td>
													<td align="center" valign="middle">
														Month - 2
													</td>
													<td align="center" valign="middle">
														Month - 3
													</td>
													<td align="center" rowspan="2" colspan="2" valign="middle" nowrap="nowrap">
														Average Index
													</td>
												</tr>
												<tr class="label" style="background-color:#EAEAEA; height:35px;">
													<td align="center" valign="middle">
														<!--<select class="extraItemTextbox" name="txt_material_desc" id="txt_material_desc">
															<option value="">-Select-</option>
															<option value="Jan">Jan</option>
															<option value="Feb">Feb</option>
															<option value="Mar">Mar</option>
															<option value="Apr">Apr</option>
															<option value="May">May</option>
															<option value="Jun">Jun</option>
															<option value="Jul">Jul</option>
															<option value="Aug">Aug</option>
															<option value="Sep">Sep</option>
															<option value="Oct">Oct</option>
															<option value="Nov">Nov</option>
															<option value="Dec">Dec</option>
														</select>-->
														<input type="text" class="extraItemTextbox date-picker" name="txt_material_baseindex1" id="txt_material_baseindex1">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox date-picker" name="txt_material_baseindex2" id="txt_material_baseindex2">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox date-picker" name="txt_material_baseindex3" id="txt_material_baseindex3">
													</td>
												</tr>
												<tr class="labeldisplay" style="background-color:#EAEAEA">
													<td align="left" valign="middle" nowrap="nowrap">
														&nbsp;Material&nbsp;&nbsp;
														<input type="hidden" name="txt_material_desc" id="txt_material_desc" value="Material (MIo)">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( MIo )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="178.00">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( Xm )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="20">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox" name="txt_material_desc" id="txt_material_desc">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_escal_perc" id="txt_material_escal_perc">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( MI )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_escal_perc" id="txt_material_escal_perc">
													</td>
												</tr>
												<tr class="labeldisplay" style="background-color:#EAEAEA">
													<td align="left" valign="middle" nowrap="nowrap">
														&nbsp;Cement
														<input type="hidden" name="txt_cement_desc" id="txt_cement_desc" value="Material (MIo)">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( CIo )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="172.00">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( Xc )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="0">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_cement_baseindex" id="txt_cement_baseindex">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox" name="txt_material_desc" id="txt_material_desc">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_cement_escal_perc" id="txt_cement_escal_perc">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( CI )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_escal_perc" id="txt_material_escal_perc">
													</td>
												</tr>
												<tr class="labeldisplay" style="background-color:#EAEAEA">
													<td align="left" valign="middle" nowrap="nowrap">
														&nbsp;Steel
														<input type="hidden" name="txt_steel_desc" id="txt_steel_desc" value="Material (MIo)">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( SIo )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="162.00">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( Xs )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="0">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_steel_baseindex" id="txt_steel_baseindex">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox" name="txt_material_desc" id="txt_material_desc">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_steel_escal_perc" id="txt_steel_escal_perc">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( SI )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_escal_perc" id="txt_material_escal_perc">
													</td>
												</tr>
												<tr class="labeldisplay" style="background-color:#EAEAEA">
													<td align="left" valign="middle" nowrap="nowrap">
														&nbsp;Labour
														<input type="hidden" name="txt_labour_desc" id="txt_labour_desc" value="Material (MIo)">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( LIo )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="278.00">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( Y )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="25">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_labour_baseindex" id="txt_labour_baseindex">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox" name="txt_material_desc" id="txt_material_desc">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_labour_escal_perc" id="txt_labour_escal_perc">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( LI )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_escal_perc" id="txt_material_escal_perc">
													</td>
												</tr>
												<!--<tr>
                                                    <span id="add_hidden"></span>
												</tr>-->
											</table>
                                             <input type="hidden" value="" name="add_set_a1" id="add_set_a1"/>
										</div>
										
									<!--</td>
								</tr>
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
