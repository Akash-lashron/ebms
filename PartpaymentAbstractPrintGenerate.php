<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
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

?>
<?php require_once "Header.html"; ?>
<script>
	function find_workname(){		
		var xmlHttp;
		var data;
		var i,j;
			
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_workname.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				if(data=="")
				{
					alert("No Records Found");
					document.form.workname.value='';	
				}
				else
				{	
					document.form.workname.value			=	name[0].trim();
					document.form.txt_workorder_no.value	=	name[2].trim();
					document.form.txt_book_no1.value		=	Number(name[1]) + Number(1);
					document.form.txt_book_no.value			=	Number(name[1]) + Number(1);
					document.form.txt_bookpage_no1.value	=	Number(name[2]) + Number(1);
					document.form.txt_bookpage_no.value		=	Number(name[2]) + Number(1);
					document.form.txt_rab_no1.value			=	Number(name[3]) + Number(1);
					document.form.txt_rab_no.value			=	Number(name[3]) + Number(1);
	
							}
			}
		}
		xmlHttp.send(strURL);	
	}
	function func_GenerateAbstractMBno()
    { //alert("x")
		var xmlHttp;
        var data;
		var mtype = "PA"; 
        if (window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
        	xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
        	xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "find_generatembno.php?sheetid=" + document.form.cmb_work_no.value + "&mtype=" + mtype;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {

        	if(xmlHttp.readyState == 4)
            {
            	data = xmlHttp.responseText;
                if (data == "")
                {
                    document.form.cmb_mbook_no.length = 0;
                    var optn = document.createElement("option");
                    optn.value = 0;
                    optn.text = " ----- Select ----- ";
                    document.form.cmb_mbook_no.options.add(optn);
                }
                else
                { 
                	var name = data.split("*");
                    document.form.cmb_mbook_no.length = 0;
                    var optn = document.createElement("option");
                    optn.value = 0;
                    optn.text = " ----- Select ----- ";
                    document.form.cmb_mbook_no.options.add(optn);
                    var c = name.length;
                    var a = c / 2;
                    var b = a + 1;
                    for (i = 1, j = b; i < a, j < c; i++, j++)
                    {
                    	var optn = document.createElement("option")
                        optn.value = name[i];
                        // optn.value = name[j];
                        optn.text = name[j];
                        document.form.cmb_mbook_no.options.add(optn)  
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
        strURL = "findabstract_mbookno.php?sheetid=" + document.form.cmb_work_no.value;
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
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->

         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
                        <div class="title">PartPayment Abstract Print Generate</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="PartpaymentAbstractPrint.php">
                       
                            <div class="container">
							<br/>
								 <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
									 <tr><td width="23%">&nbsp;</td></tr>
									 <tr>
										<td>&nbsp;</td> 
										<td  class="label">Work Short Name</td>
										<td  class="labeldisplay">
										<select name="cmb_work_no" id="cmb_work_no" onChange="find_workname();getrbn();" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
											<option value="">---------- Select Work Short Name ----------</option>
											<?php echo $objBind->BindWorkOrderNo(0); ?>
										</select></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
									<tr>
										<td>&nbsp;</td>
										<td  class="label">Work Order No.</td>
										<td  class="labeldisplay">
										<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplay" style="width:397px;" disabled="disabled">
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr><td>&nbsp;</td><td></td><td id="val_workorder" style="color:red"></td></tr>
									<tr>
										<td>&nbsp;</td>
										<td  class="label">Name of the Work </td>
										<td  class="labeldisplay">
										<textarea name="workname" class="textboxdisplay txtarea_style" style="width: 400px;" rows="5" disabled="disabled"></textarea>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
									<tr>
										<td>&nbsp;</td>
										<td  class="label">RAB</td>
										<td  class="labeldisplay">
										<input type="text" name="txt_rbn" id="txt_rbn" class="textboxdisplay" style="width:130px;" readonly="">
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<!--<tr><td>&nbsp;</td><td></td><td id="val_rbn" style="color:red"></td></tr>
									<tr>
										<td>&nbsp;</td>
										<td  class="label">MBook No.</td>
										<td  class="labeldisplay" colspan="3">
										<select name="cmb_mbook_no" id="cmb_mbook_no"  class="textboxdisplay" style="width:133px;height:22px;">
											<option value="">----- Select -----</option>
										</select>
										<input type="hidden" name="txt_mbookno" id="txt_mbookno">
										&emsp;&emsp;&emsp;&emsp;<span class="label">page No.&emsp;</span>
										<input type="text" name="txt_mbook_page_no" id="txt_mbook_page_no" class="textboxdisplay" style="width:130px;" readonly="">
										</td>
									</tr>
									<tr><td>&nbsp;</td><td></td><td id="val_rbn" style="color:red"></td></tr>
									<tr><td></td></tr>-->
								</table>
							</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> 
								</div>
								<div class="buttonsection">
								<input type="submit" class="btn" value=" View " name="btn_view" id="btn_view"   />
								</div>
								<input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid; ?>">
							</div>
						</form>
					</blockquote>
				</div>
			</div>
		</div>
<!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
	$('#cmb_work_no').chosen();
    $(function() {
		function DisplayPageDetails() {
			$("#txt_mbook_page_no").val('');
			$("#txt_mbookno").val('');
            var currentmbooknovalue 	= 	$("#cmb_mbook_no option:selected").attr('value');//alert(currentmbooknovalue);
            var currentmbooknotext 		= 	$("#cmb_mbook_no option:selected").text();
			var wordordernovalue 		= 	$("#cmb_work_no option:selected").attr('value');
			var staffid					=	$("#hid_staffid").val();
			var currentrbn				=	$("#txt_rbn").val();
			var generatetype 			= 	"sw";
            $.post("MBookNoService.php", {currentmbook: currentmbooknovalue, currentbmookname: currentmbooknotext, sheetid: wordordernovalue, generatetype: generatetype, staffid: staffid, currentrbn: currentrbn}, function (data) { //alert(data);
                //$("#bookpageno1").val(Number(data) + 1);$("#bookpageno").val(Number(data) + 1);
				if(currentmbooknovalue != 0){
					$("#txt_mbook_page_no").val(data);
					$("#txt_mbookno").val(currentmbooknotext);
				}
            });
        }
	
	
		$.fn.validatembooktype = function(event) {	
			if($("#cmb_mbook_type").val()==""){ 
				var a="Please select the Measurement Type";
				$('#val_mbooktype').text(a);
				event.preventDefault();
				event.returnValue = false;
				//return false;
			}else{
				var a="";
				$('#val_mbooktype').text(a);
			}
		}
		$.fn.validateworkorder = function(event) { 
			if($("#cmb_work_no").val()==""){ 
				var a="Please select the work order number";
				$('#val_work').text(a);
				event.preventDefault();
				event.returnValue = false;
				//return false;
			}else{
				var a="";
				$('#val_work').text(a);
			}
		}
		$("#top").submit(function(event){
            $(this).validatembooktype(event);
			$(this).validateworkorder(event);
        });
		$("#cmb_work_no").change(function(event){
            $(this).validateworkorder(event);
        });
        $("#cmb_mbook_no").bind("change", function () {   
            DisplayPageDetails();
        });
	});
</script>
</body>
</html>

