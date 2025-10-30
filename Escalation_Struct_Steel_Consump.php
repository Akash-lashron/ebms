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
	$from_rbn 		= 	trim($_POST['cmb_from_rbn']);
	$to_rbn 		= 	trim($_POST['cmb_to_rbn']);
	$_SESSION['escal_sheetid'] 	= $sheetid;
	$_SESSION['escal_from_rbn'] = $from_rbn;
	$_SESSION['escal_to_rbn'] 	= $to_rbn;
	header('Location: Escalation_Struct_Steel_Consump_View.php');
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
            strURL = "findrbnEscalation.php?workorderno=" + document.form.cmb_shortname.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
                    /*if (data == "")
                    {
                        alert("No Records Found");
                        document.form.cmb_rbn.value = 'Select';
                    }*/
					//alert(data);
                    if (data != "")
                    {	
                        var name = data.split("*");
						
                        document.form.cmb_from_rbn.length = 0;
                        var optn1 = document.createElement("option");
                        optn1.value = 0;
                        optn1.text = "---- Select RAB. ----";
                        document.form.cmb_from_rbn.options.add(optn1);
						
						document.form.cmb_to_rbn.length = 0;
						var optn2 = document.createElement("option");
						optn2.value = 0;
                        optn2.text = "---- Select RAB. ----";
						document.form.cmb_to_rbn.options.add(optn2);
						
                        var c = name.length;
                        for (i = 0 ; i < c ; i+=2)
                        {
                            var optn1 = document.createElement("option");
                            optn1.value = name[i];
							optn1.text = name[i+1]+" - "+name[i];
                            document.form.cmb_from_rbn.options.add(optn1);
							
							var optn2 = document.createElement("option");
                            optn2.value = name[i];
							optn2.text = name[i+1]+" - "+name[i];
							document.form.cmb_to_rbn.options.add(optn2);
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
                     <div class="title">Escalation - Structural Steel Consumption</div>
						<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        	<table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();getrbn();">
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
                                    <td class="label">RAB No.</td>
                                    <td class="label">
										From &emsp;
										<select name="cmb_from_rbn" id="cmb_from_rbn" class="textboxdisplay" style="width:150px">
											<option value="">---- Select RAB. ----</option>
										</select>
										&emsp;&emsp;&emsp;&emsp;&emsp;
										To &emsp;
										<select name="cmb_to_rbn" id="cmb_to_rbn" class="textboxdisplay" style="width:150px">
											<option value="">---- Select RAB. ----</option>
										</select>
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rbn" style="color:red" colspan="">&nbsp;</td></tr>
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
