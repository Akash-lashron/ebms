<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
checkUser();
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
if ($_POST["submit"] == ' View ') 
{
   	$sheetid 		= 	trim($_POST['cmb_shortname']);
	$from_date 		= 	trim($_POST['txt_from_date']);
	$to_date 		= 	trim($_POST['txt_to_date']);
	$quarter 		= 	trim($_POST['cmb_quarter']);
	$esc_rbn 		= 	trim($_POST['txt_rbn']);
	$esc_id 		= 	trim($_POST['txt_esc_id']);
	$mbook 			= 	trim($_POST['bookno']);
	$mbookpage 		= 	trim($_POST['bookpageno']);
	//echo $quarter;exit;
	$_SESSION['escal_sheetid'] 		= $sheetid;
	$_SESSION['escal_from_date'] 	= $from_date;
	$_SESSION['escal_to_date'] 		= $to_date;
	$_SESSION['escal_quarter'] 		= $quarter;
	$_SESSION['escal_rbn'] 			= $esc_rbn;
	$_SESSION['escal_esc_id'] 		= $esc_id;
	$_SESSION['cc_mbook_no'] 		= $mbook;
	$_SESSION['cc_mbook_pageno'] 	= $mbookpage;
	header('Location: Escalation_Cement_Site_Consump_General_View.php');
} 
?>

<?php require_once "Header.html"; ?>
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
		function GetEscQuarterRBN()
     	{ 
            var xmlHttp;
            var data;
            var i, j;
			document.form.cmb_quarter.length = 0;
			document.getElementById("txt_rbn").value  = "";
			var optn = document.createElement("option");
				optn.value = "";
				optn.text = "------- Select -------";
			document.form.cmb_quarter.options.add(optn);
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_EscQuarterRBN.php?sheetid=" + document.form.cmb_shortname.value;
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
                        var name 		= data.split("@");
						var rbn 		= name[0];
						var QtrStr 		= name[1];
						document.getElementById("txt_rbn").value  = rbn;
						var SplitQtrStr = QtrStr.split("*");
						document.form.cmb_quarter.length = 0;
						var optn = document.createElement("option");
						optn.value = "";
						optn.text = "------- Select -------";
						document.form.cmb_quarter.options.add(optn);
                        for(i = 0; i < SplitQtrStr.length; i++)
                        {
							//document.form.txt_techsanction.value 	= name[0];
							//document.form.txt_agreemntno.value 		= name[2];
                            //document.form.txt_workname.value 		= name[3];
							//document.form.txt_workorder.value 		= name[5];
							var optn = document.createElement("option")
							optn.value = SplitQtrStr[i];
							optn.text = SplitQtrStr[i];
							document.form.cmb_quarter.options.add(optn)  
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function find_priceindex_period()
     	{ 
			document.getElementById("txt_from_date").value  = "";
			document.getElementById("txt_to_date").value  = "";
			document.getElementById("txt_esc_id").value = "";
            var xmlHttp;
            var data;
            var i, j;
			var month1 = month1;
			var month3 = month3;
			
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
			
			var sheetid = document.form.cmb_shortname.value;
			var quarter = document.form.cmb_quarter.value;
			var rbn 	= document.form.txt_rbn.value;
            //strURL = "find_priceindex_period.php?sheetid=" + document.form.cmb_shortname.value+"&type=TCA&base_index_code=CIo";/// This is for Cement - So base_index_code is CIo;
            strURL = "find_priceindex_period.php?sheetid="+sheetid+"&quarter="+quarter+"&rbn="+rbn;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
					//alert(data);
                    if (data != "")
                    {
                        var name = data.split("*@*");
						var esc_id 		 	= name[0];
						var tcc_fromdate 	= name[1];
						var tcc_todate 		= name[2];
						var tca_fromdate 	= name[3];
						var tca_todate  	= name[4];
						
						
						
						//var pid 		 = name[0];
//						var bid 		 = name[1];
//						var pi_from_date = name[2];
//						var pi_to_date 	 = name[3];
//						var avg_pi_code  = name[4];
//						var avg_pi_rate  = name[5];
//						var type 		 = name[6];
//						var quarter		 = name[7];
//						var rbn		 	 = name[8];
//						var esc_id		 = name[9];
//						//alert(pi_from_date)
						document.getElementById("txt_from_date").value  = tca_fromdate;
						document.getElementById("txt_to_date").value  = tca_todate;
						//document.getElementById("txt_quarter").value  = quarter;
						//document.getElementById("txt_rbn").value  = rbn;
						document.getElementById("txt_esc_id").value  = esc_id;
						//document.getElementById("txt_price_index_rate_m3"+bid).value  = pi_rate3;
						//document.getElementById("txt_avg_price_index_code"+bid).value = avg_pi_code;
						//document.getElementById("txt_avg_price_index_rate"+bid).value = avg_pi_rate;

                    }
                }
            }
            xmlHttp.send(strURL);
     	}
		function func_GenerateMBno()
        { 
            var xmlHttp;
            var data;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                 xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                 xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_generatembno.php?sheetid=" + document.form.cmb_shortname.value + "&mtype=E";
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {

                if (xmlHttp.readyState == 4)
                {
                     data = xmlHttp.responseText;
                     if (data == "")
                     {
                         alert("No Records Found");
                         document.form.itemno.value = 'Select';
                     }
                     else
					{ 
                         var name = data.split("*");
                         document.form.currentmbookno.length = 0;
						 document.form.bookpageno.value = "";
						 document.form.bookno.value = "";
                         var optn = document.createElement("option");
                         optn.value = 0;
                         optn.text = "------------ Select ------------- ";
                         document.form.currentmbookno.options.add(optn);
                         var c = name.length;
                         var a = c / 2;
                         var b = a + 1;
                         for (i = 1, j = b; i < a, j < c; i++, j++)
                         {
                             var optn = document.createElement("option")
                             optn.value = name[i];
                             // optn.value = name[j];
                             optn.text = name[j];
                             document.form.currentmbookno.options.add(optn)  
                         }
                   	}
                }
             }
             xmlHttp.send(strURL);
        }
		function Clear()
		{
			document.form.txt_workname.value = "";
			document.form.txt_workorder.value = "";
			document.getElementById("txt_rbn").value  = "";
			
			document.form.cmb_quarter.length = 0;
			var optn1 = document.createElement("option");
				optn1.value = "";
				optn1.text = "------- Select -------";
			document.form.cmb_quarter.options.add(optn1);
			
			document.getElementById("txt_from_date").value  = "";
			document.getElementById("txt_to_date").value  = "";
			document.getElementById("txt_esc_id").value = "";
			
			document.form.currentmbookno.length = 0;
			document.form.bookpageno.value = "";
			document.form.bookno.value = "";
			var optn2 = document.createElement("option");
                optn2.value = 0;
                optn2.text = "------------ Select ------------- ";
           document.form.currentmbookno.options.add(optn2);
		}
		
</script>
<script>
   $(function () {
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
		/*$("#cmb_shortname").change(function(event){
		$(this).validateworkname(event);
		});*/
		$("#txt_workname").keyup(function(event){
		$(this).validateworkname(event);
		});
		$("#txt_workorder").keyup(function(event){
		$(this).validateworkorder(event);
		});
		$("#txt_rbn").keyup(function(event){
		$(this).validaterbn(event);
		});
		$("#top").submit(function(event){
		$(this).validateworkname(event);
		$(this).validateworkorder(event);
		$(this).validaterbn(event);
		//$(this).validatesecadv(event);
		//calculateEBamount();
		});
		
        $("#currentmbookno").bind("change", function () {   
            DisplayPageDetails();
        });
		
        function DisplayPageDetails() {
            var currentmbooknovalue 	= 	$("#currentmbookno option:selected").attr('value');
            var currentmbooknotext 		= 	$("#currentmbookno option:selected").text();
			var quarter					=	$("#cmb_quarter").val();
			var rbn						=	$("#txt_rbn").val();
			var esc_id					=	$("#txt_esc_id").val();
			var wordordernovalue 		= 	$("#cmb_shortname option:selected").attr('value');
			var staffid					=	$("#hid_staffid").val();
			var mtype 					= 	"E";
			var generatetype 			= 	"cem_consum";
			
			if(currentmbooknovalue != "")
			{
				$("#bookno").val(currentmbooknotext);
			}
			else
			{
				$("#bookno").val('');
			}
			
            $.post("MBookNoServiceEsc.php", {currentmbook: currentmbooknovalue, currentbmookname: currentmbooknotext, sheetid: wordordernovalue, generatetype: generatetype, quarter: quarter, rbn: rbn, esc_id: esc_id, mtype: mtype}, function (data) {
				$("#bookpageno").val(data);
                
            });
        }
		
   
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
        <div class="title">Escalation - Cement Consumption (Site Consumption)</div>
       	<div class="container_12">
           	<div class="grid_12">
			<!--<div align="right"><a href="View_Electricity_generate_Bill.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                 <blockquote class="bq1" style="overflow:auto">
						<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
						<input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid; ?>">
                        	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="22%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="Clear();workorderdetail();GetEscQuarterRBN();func_GenerateMBno();">
										<!--<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();">-->
											<option value="">--------------- Select ---------------</option>
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
                                    <td class="label">RAB</td>
                                    <td class="label">
									<input type="text" name='txt_rbn' id='txt_rbn' class="textboxdisplay" value="" style="width: 200px;">
									&emsp;&nbsp;&nbsp;
										&emsp;Quarter &emsp;
									<select name="cmb_quarter" id="cmb_quarter" style="width:143px;" class="textboxdisplay" onChange="find_priceindex_period();">
										<option value="">------- Select -------</option>
									</select>
									<input type="hidden" name='txt_esc_id' id='txt_esc_id' class="textboxdisplay" value="" style="width: 140px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_quarter" style="color:red" colspan="">&nbsp;</td></tr>

								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Esacaltion Period</td>
                                    <td class="label">
										From &emsp;
										<input type="text" name='txt_from_date' readonly="" id='txt_from_date' class="textboxdisplay date-picker" style="width: 140px;">
										&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;
										To &emsp;
										<input type="text" name='txt_to_date' readonly="" id='txt_to_date' class="textboxdisplay date-picker" style="width: 140px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rbn" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">MBook No.</td>
                                    <td>
										<select name="currentmbookno" id="currentmbookno" class="textboxdisplay" tabindex="6" style="width:205px;height:22px;">
                                             <option value="0" selected="selected"> ------------ Select ------------- </option>
                                        </select>
										&emsp;&emsp;
										<label class="label">Page No.</label>&nbsp;&nbsp;&nbsp;
										<input type="hidden" name='bookno' readonly="" id='bookno' class="textboxdisplay" style="width: 150px;">
										<input type="text" name='bookpageno' readonly="" id='bookpageno' class="textboxdisplay" style="width: 140px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_quarter" style="color:red" colspan="">&nbsp;</td></tr>
							
							</table>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
									<input type="submit" name="submit" id="submit" value=" View "/>
								</div>
							</div>
                  </blockquote>
              </div>
        </div>
	</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
		   		$("#cmb_shortname").chosen();
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
