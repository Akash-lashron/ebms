<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';

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
      	function func_items()
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
            strURL = "findrbntest.php?workordernumber=" + document.form.cmb_work_no.value;
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
                    if (data != "")
                    {	
                        var name = data.split("*");
                        document.form.cmb_rbn.length = 0
                        var optn = document.createElement("option")
                        optn.value = 0;
                        optn.text = "------------------------------------ Select RBN. -------------------------------------";
                        document.form.cmb_rbn.options.add(optn)
                        var c = name.length;
                        for (i = 0 ; i < c ; i++)
                        {
                            var optn = document.createElement("option")
                            optn.value = name[i];
							optn.text = "RAB"+name[i];
                            document.form.cmb_rbn.options.add(optn)
                        }
                    }
                }
            }
            xmlHttp.send(strURL);
        }

	function find_workname()
	{		
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
		strURL="findworknametest.php?sheetid="+document.form.cmb_work_no.value;
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
					document.form.workname.value		=	name[0].trim();
					document.form.txt_workorder_no.value=	name[2].trim();
					document.form.txt_book_no1.value	=	Number(name[1]) + Number(1);
					document.form.txt_book_no.value		=	Number(name[1]) + Number(1);
					document.form.txt_bookpage_no1.value=	Number(name[2]) + Number(1);
					document.form.txt_bookpage_no.value	=	Number(name[2]) + Number(1);
					document.form.txt_rab_no1.value		=	Number(name[3]) + Number(1);
					document.form.txt_rab_no.value		=	Number(name[3]) + Number(1);
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

</script>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->

         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <div class="title">MBook Test</div>
                        <form name="form" method="post" action="MbookViewTest.php">
                            <div class="container">
								<br/>
                                <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                                    <tr>
										<td width="190px">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
                                    <tr>
										<td>&nbsp;</td> 
										<td  class="label">Work Short Name</td>
										<td  class="labeldisplay">
											<select id="cmb_work_no" name="cmb_work_no" onChange="func_items();find_workname();zonename();" class="textboxdisplay" style="width:470px;height:22px;" tabindex="7">
												<option value=""> -------------------------------- Select Work Name -------------------------------- </option>
												<?php echo $objBind->BindWorkOrderNo(0); ?>
											</select>     
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td colspan="3" id="val_work" style="color:red"></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td  class="label">Work Order No.</td>
										<td  class="labeldisplay">
										<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplay" style="width:465px;" disabled="disabled">
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td colspan="3" id="val_workorder" style="color:red"></td>
									</tr>	
									<tr>
										<td>&nbsp;</td>
										<td  class="label">Name of the Work </td>
										<td  class="labeldisplay">
											<textarea name="workname" class="textboxdisplay txtarea_style" style="width: 468px;" rows="5" disabled="disabled"></textarea>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td colspan="3" id="val_work" style="color:red"></td>
									</tr>
									<tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">Running Account Bill No </td>
                                        <td  class="labeldisplay">
                                            <select name="cmb_rbn" id="cmb_rbn" class="textboxdisplay" style="width:470px;height:22px;" size="" tabindex="7" onChange="cmb_runningbilltext()">
                                                <option value=0>------------------------------------ Select RBN. -------------------------------------</option>
                                            </select>
										</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
									</tr>
										
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td colspan="3" id="val_rbn" style="color:red">
									</tr>
									<tr>
										<td>&nbsp;&nbsp;</td>
										<td>&nbsp;&nbsp;</td>
										<td>&nbsp;&nbsp;</td>
										<td>&nbsp;&nbsp;</td>
										<td>&nbsp;&nbsp;</td>
									</tr>
                                </table>
                            </div>
							<div style="text-align:center">
									<div class="buttonsection" style="display:inline-table">
										<input type="button" onClick="goBack()" class="backbutton" name="back" id="back" value="Back">
									</div>
									<div class="buttonsection" style="display:inline-table">
										<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"   />
									</div>
							</div>
                        </form>
                    </blockquote>
                </div>

            </div>
        </div>
         <!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
    $(function() {
	$.fn.validaterbnno = function(event) {	
				if($("#cmb_rbn").val()==0){ 
					var a="Please select the Bill number";
					$('#val_rbn').text(a);
					event.preventDefault();
					event.returnValue = false;
					}
				else{
				var a="";
				$('#val_rbn').text(a);
				}
			}
	$.fn.validateworkorder = function(event) { 
					if($("#cmb_work_no").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
	$("#top").submit(function(event){
           	$(this).validaterbnno(event);
			$(this).validateworkorder(event);
			
         });
	$("#cmb_work_no").change(function(event){
           	$(this).validateworkorder(event);
         });
    $("#cmb_rbn").change(function(event){
           	$(this).validaterbnno(event);
         });
	 });
</script>
</body>
</html>

