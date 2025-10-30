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
if ($_POST["submit"] == ' View ') 
{
   	$sheetid 		= 	trim($_POST['cmb_shortname']);
	$from_date 		= 	trim($_POST['txt_from_date']);
	$to_date 		= 	trim($_POST['txt_to_date']);
	//echo $from_date;exit;
	$_SESSION['escal_sheetid'] 		= $sheetid;
	$_SESSION['escal_from_date'] 	= $from_date;
	$_SESSION['escal_to_date'] 		= $to_date;
	header('Location: EscalationPrintPage_10CA.php');
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
		function find_escalation_period()
     	{ 
			document.getElementById("txt_from_date").value  = "";
			document.getElementById("txt_to_date").value  = "";
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
            strURL = "find_escalation_period.php?sheetid=" + document.form.cmb_shortname.value+"&type=TCA";/// This is for Cement - So base_index_code is CIo;
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
						var esc_from_date 		 = name[0];
						var esc_to_date 		 = name[1];
						document.getElementById("txt_from_date").value  = esc_from_date;
						document.getElementById("txt_to_date").value  = esc_to_date;
                    }
                }
            }
            xmlHttp.send(strURL);
     	}
		function func_GenerateMBno()
        { 
		//alert()
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
                         optn.text = "------------- Select -------------- ";
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
		
		
</script>
<script>
   $(function () {
		/*$('.date-picker').datepicker( {
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'M-yy',
			onClose: function(dateText, inst) { 
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
			}
		});	*/	
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
		/*$.fn.validatesecadv = function(event) { 
					if($("#txt_sec_adv").val()==""){ 
					var a="Please Enter Secured Advance";
					$('#val_sec_adv').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_sec_adv').text(a);
					}
				}*/
				
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
		/*$("#txt_sec_adv").keyup(function(event){
		$(this).validatesecadv(event);
		});*/
		$("#top").submit(function(event){
		$(this).validateshortname(event);
		$(this).validateworkname(event);
		$(this).validateworkorder(event);
		$(this).validaterbn(event);
		//$(this).validatesecadv(event);
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
			<!--<div align="right"><a href="View_Electricity_generate_Bill.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                 <blockquote class="bq1">
                     <div class="title">Escalation Print - 10CA</div>
						<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        	<table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();find_escalation_period();func_GenerateMBno();">
											<option value="">--------------------------- Select Work Short Name ----------------------------</option>
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
                                    <td class="label">Esacaltion Period</td>
                                    <td class="label">
										From &emsp;
										<input type="text" name='txt_from_date' readonly="" id='txt_from_date' class="textboxdisplay date-picker" style="width: 150px;">
										&emsp;&emsp;&emsp;&emsp;&nbsp;
										To &emsp;
										<input type="text" name='txt_to_date' readonly="" id='txt_to_date' class="textboxdisplay date-picker" style="width: 150px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rbn" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">RAB</td>
                                    <td class="label">
										<input type="text" name='txt_rbn' readonly="" id='txt_rbn' class="textboxdisplay" style="width: 210px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rbn" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">MBook No.</td>
                                    <td class="label">
										<select name="currentmbookno" id="currentmbookno" class="textboxdisplay" tabindex="6" style="width:215px;height:22px;" tabindex="7">
                                             <option value="0" selected="selected"> ------------- Select -------------- </option>
                                        </select>
										&emsp;&emsp;
										<label class="label">Page No.</label>&nbsp;
										<input type="hidden" name='bookno' readonly="" id='bookno' class="textboxdisplay" style="width: 150px;">
										<input type="text" name='bookpageno' readonly="" id='bookpageno' class="textboxdisplay" style="width: 140px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_esc_mbook" style="color:red" colspan="">&nbsp;</td></tr>
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
