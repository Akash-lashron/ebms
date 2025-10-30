<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';

if (isset($_POST["view"])) 
{
	$sheetid = $_POST['workorderno'];
	header('Location: ShortnotesList.php?sheetid='.$sheetid);
}
?>
<?php require_once "Header.html"; ?>

    <script type="text/javascript"  language="JavaScript">
		function goBack()
	   	{
	   		url = "dashboard.php";
			window.location.replace(url);
	   	}
		window.history.forward();
		function noBack() 
		{ 
			window.history.forward(); 
		}
		function func_item_no()
        {
            var xmlHttp;
            var data;
            var i, j;
			document.form.txt_workorder_no.value = "";
			document.form.txt_workname.value = "";
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_item_no.php?item_no=" + document.form.workorderno.value;
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
                    }
                    else
                    {
                        var wrkname = data.split("##");
						document.form.txt_workorder_no.value 	= wrkname[6];
                        document.form.txt_workname.value 		= wrkname[0];
					}
                }
            }
            xmlHttp.send(strURL);
            /*var e = document.getElementById("workorderno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_work_no.value = strUser;*/
        }
	</script>
    <body class="page1" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="top">
<?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1">
                            <div class="title">Short Notes Creation </div>		
                          
                                <input type="hidden" name="txt_item_no" id="txt_item_no" value="">
                                <input type="hidden" name="txt_work_no" id="txt_work_no" value="">

                                <div class="container">
                                    <table width="1078" border="1" cellpadding="0" cellspacing="0" align="center" >
                                        <tr><td width="17%">&nbsp;</td>
                                        </tr>	
                                        <tr><td colspan="5">&nbsp;&nbsp;</td></tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label" width="14%" nowrap="nowrap">Work Short Name</td>
                                            <td class="label">
                                                <select id="workorderno" name="workorderno" onChange="func_item_no();" class="textboxdisplay" style="width:470px;height:22px;" tabindex="7">
                                                        <option value=""> ------------------------------- Select Work Name -------------------------------- </option>
                                                        <?php echo $objBind->BindWorkOrderNo(0); ?>
                                              </select>     
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr><td>&nbsp;</td><td></td><td colspan="3" id="val_work" style="color:red"></tr>
										<tr>
											<td>&nbsp;</td>
											<td  class="label">Work Order No. </td>
											<td  class="labeldisplay"><input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplay" style="width: 465px;"></td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td></td>
											<td id="val_workorder" style="color:red" colspan="3"></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td  class="label">Name of the Work </td>
											<td  class="labeldisplay"><textarea name="txt_workname" id="txt_workname" rows="6" class="textboxdisplay" style="width: 465px;"></textarea></td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td></td>
											<td id="val_work" style="color:red" colspan="3"></td>
										</tr>
										   
										<tr>
											<td>&nbsp;&nbsp;</td>
											<td width="" class="label"></td>
											<td id="val_rbn" style="color:red" colspan="3"></td>
										</tr>

                    
                                  </table>
                            
                         
                                </div>
								<div style="text-align:center">
									<div class="buttonsection" style="display:inline-table">
										<input type="button" onClick="goBack()" class="backbutton" name="back" id="back" value=" Back ">
									</div>
																 
									<div class="buttonsection" style="display:inline-table">
										<input type="submit" class="backbutton" name="view" value=" View " id="view"/>
									</div>
								</div>
                        </blockquote>
                    </div>

                </div>
            </div>
    </form>
            <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
	$(function() {
		 	$.fn.validateworkorder = function(event) { 
					if($("#workorderno").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
			
			
			$("#top").submit(function(event){
			$(this).validateworkorder(event);
         	});
		 
			$("#workorderno").change(function(event){
           	$(this).validateworkorder(event);
         	});
	 });
</script>
		      
    
</body>
</html>