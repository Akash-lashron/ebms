<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();

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
        strURL = "find_worder_details.php?workorderno=" + document.form.txt_workorderno.value;
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
                        document.form.txt_workname.value 		= name[3];
                        document.form.txt_workorder_no.value 	= name[5];
                    }

                }
            }
        }
        xmlHttp.send(strURL);
    }
    </script>
	<style>
	.hide{
		display:none;
	}
	</style>
	<script type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</script>
    <body class="page1" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="MeasurementQuantityEntry.php" method="post" enctype="multipart/form-data" name="form" id="top">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">Measurement Quantity Entry</div>		
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1">
                          
                                <input type="hidden" name="txt_item_no" id="txt_item_no" value="">
                                <input type="hidden" name="txt_work_no" id="txt_work_no" value="">
								<input type="hidden" name="hid_length" id="hid_length" value="">
                                <div class="container">
                                    <table width="1078" border="1" cellpadding="0" cellspacing="0" align="center" >
                                        <tr><td width="23%">&nbsp;</td>
											<td colspan="4">&nbsp;</td>
                                        </tr>	
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label" width="17%" nowrap="nowrap">Work Short Name</td>
                                            <td class="labeldisplay">
                                                <select id="txt_workorderno" name="txt_workorderno" class="tboxsmclass" style="width:593px;height:22px;" tabindex="7"  onChange="workorderdetail();">
                                                        <option value=""> ---------------------------- Select Work Name ---------------------------- </option>
                                                        <?php echo $objBind->BindWorkOrderNo(0); ?>
                                              </select>     
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr><td>&nbsp;</td><td>&nbsp;</td><td colspan="3" id="val_work" style="color:red"></td></tr>
										<tr>
                                            <td>&nbsp;</td>
                                            <td class="label">Work Order No</td>
                                            <td class="labeldisplay">
                                                <input type="text" name='txt_workorder_no' id='txt_workorder_no' class="tboxsmclass" value="" style="width:588px;"/>
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
                                        <tr class="hide" id="zrow2">
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td id="val_zone_name" colspan="2" style="color:red"></td>
                                            <td></td>
                                            
                                        </tr>
										
                                        <tr id="subitem_3" class="hide"><td colspan="5">&nbsp;&nbsp;</td></tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="label">Name of the Work</td>
                                            <td class="labeldisplay">
                                                <textarea type="text" name='txt_workname' id='txt_workname' class="tboxsmclass" value="" style="width:586px;height:80px;"></textarea>
                                            </td>
                                            <td class="label">&nbsp;</td>
                                        </tr>

                                        
                                        <!--<tr>
                                            <td>&nbsp;</td>
                                            <td class="label">From Date</td>
                                            <td class="label">
                                                <input type="text" name='fromdate' id='fromdate' class="tboxsmclass" value="" style="width:150px;"/>
                                                <span class=""> &emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;&emsp;&emsp;&emsp;To Date</span>
                                                &emsp;&emsp;&emsp;&nbsp;
                                                <input type="text" name='todate' id='todate' class="tboxsmclass" value="" style="width:150px;"/>
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td class="label">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td id="val_shortnotes" colspan="2" style="color:red"></td>
                                            <td></td>
                                            
                                        </tr>-->
                                        <tr>
                                            <td colspan="5">
                                                <center>
                                                    <input type="hidden" class="text" name="submit" value="true" />
                                                    <input type="hidden"  id="sno_hide" name="sno_hide">
                                                                                              <!--<button type="button" class="btn" id="submit" data-type="submit" value=" Submit ">Submit</button>-->
                                                       <!--<input type="submit" name="submit" value=" View " id="submit" onClick="getlength();"/>&nbsp;&nbsp;&nbsp;
													   <input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>-->
                                                </center>
                                            </td>
                                        </tr>
										 
                                  </table>
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
<?php include "footer/footer.html"; ?>
<script>
    var msg = "<?php echo $msg; ?>";
    if(msg != ""){
        BootstrapDialog.alert(msg);
    }
	$("#txt_workorderno").chosen();
    
    $(function() {
		$.fn.validateworkorder = function(event) { 
			if($("#txt_workorderno").val()==""){ 
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
		$("#txt_workorderno").change(function(event){
           	$(this).validateworkorder(event);
        });
		

    });
			 
		
		

	
	</script>
		      
    
</body>
</html>