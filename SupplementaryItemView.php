<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$userid = $_SESSION['userid'];
$msg = '';
?>
<?php require_once "Header.html"; ?>

<script type="text/javascript"  language="JavaScript">
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
        strURL = "find_worder_details.php?workorderno=" + document.form.workorderno.value;
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
						document.form.txt_workorder_no.value 	= name[5];
                    }

                }
            }
        }
        xmlHttp.send(strURL);
     }
	function GetSupplementaryWorkOrder()
	{ 
		var xmlHttp;
		var data;
		var i, j;
		$("#workorderno_supp").chosen('destroy');
		document.form.workorderno_supp.length = 1;
		document.form.txt_workorder_no_supp.value = "";
		if (window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) // For Internet Explorer
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL = "find_supp_worder.php?workorderno=" + document.form.workorderno.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function ()
		{
			if (xmlHttp.readyState == 4)
			{
				data = xmlHttp.responseText
				if (data == "")
				{
					sweetAlert("Supplementary Agreement not created for this Work Order", "", "");
				}
				else
				{
					var name = data.split("*");
					/*document.form.workorderno_supp.length = 0
					var optn1 	= 	document.createElement("option")
					optn1.value 	= 	"";
					optn1.text 	= 	" -------------------------- Select Supplementary Work Name ------------------------- ";
					document.form.workorderno_supp.options.add(optn1)*/
									
					for(i = 0; i < name.length; i+=7)
					{
						var workname	= name[i+5];
						var workid		= name[i+6];
						var optn 		= 	document.createElement("option")
						optn.value 		= 	workid;
						optn.text 		= 	workname;
						document.form.workorderno_supp.options.add(optn)
					}
				}
				$("#workorderno_supp").chosen();
			}
		}
		xmlHttp.send(strURL);
	}
	function GetSupplementaryWorkOrderDetails()
	{ 
		var xmlHttp;
		var data;
		var i, j;
		$("#workorderno_supp").chosen('destroy');
		document.form.txt_workorder_no_supp.value = "";
		if (window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) // For Internet Explorer
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL = "find_supp_worder_details.php?workorderno=" + document.form.workorderno_supp.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function ()
		{
			if (xmlHttp.readyState == 4)
			{
				data = xmlHttp.responseText
				if (data == "")
				{
					sweetAlert("Work Order No. is not exist", "", "");
				}
				else
				{
					var name = data.split("*");
					document.form.txt_workorder_no_supp.value = name[5];
				}
			}
			$("#workorderno_supp").chosen();
		}
		xmlHttp.send(strURL);
	}
</script>
<style>
	.hide
	{
		display:none;
	}
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="SupplementaryItemList.php" method="post" enctype="multipart/form-data" name="form" id="top">
<?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                            <div class="title">Supplementary Item View <?php //print_r($MbHead); ?> </div>		
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow-y:scroll;">
                          	
                                <div class="container">
                                    <table width="100%" border="1" cellpadding="0" cellspacing="0" align="center">
                                        <tr><td width="19%">&nbsp;</td>
                                        </tr>	
                                        <tr><td colspan="5">&nbsp;&nbsp;</td></tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label" width="14%" nowrap="nowrap">Work Short Name</td>
                                            <td class="labeldisplay">
                                                <select id="workorderno" name="workorderno" onChange="workorderdetail(); GetSupplementaryWorkOrder();" class="textboxdisplay" style="width:505px;height:22px;" tabindex="7">
                                                    <option value=""> --------------------- Select Work Name -------------------- </option>
                                                   	<?php echo $objBind->BindWorkOrderNo(0); ?>
                                              	</select>     
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr><td>&nbsp;</td><td></td><td colspan="3" id="val_work" style="color:red"></tr>
										<tr>
                                            <td>&nbsp;</td>
                                            <td class="label">Work Order No</td>
                                            <td class="labeldisplay">
                                                <input type="text" name='txt_workorder_no' id='txt_workorder_no' class="textboxdisplay" value="" style="width:500px;"/>
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td class="label">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td id="val_workorderno" colspan="2" style="color:red"></td>
                                            <td></td>
                                            
                                        </tr>
										<tr>
                                            <td>&nbsp;</td>
                                            <td class="label">Name of Work</td>
                                            <td class="labeldisplay">
                                                <textarea name='txt_workname' id='txt_workname' class="textboxdisplay" rows="6" style="width: 501px;"></textarea>
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td class="label">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td id="val_workorderno" colspan="2" style="color:red"></td>
                                            <td></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label" width="25%" nowrap="nowrap">Supplementary Work Short Name</td>
                                            <td class="labeldisplay">
                                                <select id="workorderno_supp" name="workorderno_supp" onChange="GetSupplementaryWorkOrderDetails()" class="textboxdisplay" style="width:505px;height:22px;" tabindex="7">
                                                   <option value=""> ------------- Select Supplementary Agreement ------------- </option>
                                                </select>     
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr><td>&nbsp;</td><td></td><td colspan="3" id="val_work_supp" style="color:red"></tr>
										
										<tr>
                                            <td>&nbsp;</td>
                                            <td class="label">Supplementary Work Order No</td>
                                            <td class="labeldisplay">
                                                <input type="text" name='txt_workorder_no_supp' id='txt_workorder_no_supp' class="textboxdisplay" value="" style="width:500px;"/>
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td class="label">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td id="val_workorderno_supp" colspan="2" style="color:red"></td>
                                            <td></td>
                                            
                                        </tr>
										
                                  </table>
								 <input type="hidden" name="txt_mbheader_id_str" id="txt_mbheader_id_str" value="<?php echo $MbhMbdIDStr; ?>">
                            		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<input type="submit" name="submit" value=" View " id="submit"/>
										</div>
									</div>
                         		</div>
                        </blockquote>
                    </div>

                </div>
            </div>
    </form>
<!--==============================footer=================================-->
<script>
	$("#workorderno").chosen();
	$("#workorderno_supp").chosen();
</script>
<style>
.extraItemTextbox {
    height: 30px;
    position: relative;
    outline: none;
    border: 1px solid #98D8FE;
   /* border-color: rgba(0,0,0,.15);*/
    background-color: white;
	color:#0000cc;
	width:155px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
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
.gradientbg {
  /* fallback */
  background-color: #014D62;
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
</style>
<?php include "footer/footer.html"; ?>
</body>
</html>