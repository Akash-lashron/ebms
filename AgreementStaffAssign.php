<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
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
$popupwindow =0;
if(isset($_POST["submit"])) 
{
	$sheetid 	= $_POST['cmb_shortname'];
	$staff 		= $_POST['cmb_staff'];
	$discipline = $_POST['cmb_disp'];
	$plant 		= $_POST['cmb_plant'];
	$activity 	= $_POST['cmb_act'];
	$item		= $_POST['cmb_item'];
	$staffidStr = "";
	for($i=0; $i<count($staff); $i++)
	{
		$staffidStr .= $staff[$i].",";
	}
	$staffidStr = rtrim($staffidStr,",");
	
	$update_sheet_query = "update sheet set assigned_staff = '$staffidStr', discipline='$discipline', plant_service='$plant', sch_act='$activity', major_item='$item' where sheet_id = '$sheetid'";
	$update_sheet_sql = mysql_query($update_sheet_query );
	if($update_sheet_sql == true)
	{
		$msg = "Staff Assigned for Work Successfully";
		$success = 1;
	}
	else
	{
		$msg = "Staff Not Assigned for Work";
		$success = 0;
	}
}

?>
<?php require_once "Header.html"; ?>
<script>
	function goBack()
	{
	   	url = "MyView.php";
		window.location.replace(url);
	}
    function workorderdetail()
    { 
		var xmlHttp;
        var data;
        var i, j;
		
		var elements = document.getElementById("cmb_staff").options;
		for(var k = 0; k < elements.length; k++)
		{
			elements[k].selected = false;
		}
	
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
					document.form.txt_workname.value 		= name[3];
					document.form.txt_workorder.value 		= name[5];
					document.form.cmb_disp.value 		= name[7];
					document.form.cmb_plant.value 		= name[8];
					document.form.cmb_act.value 		= name[9];
					document.form.cmb_item.value 		= name[10];
					var assigned_staff 	= name[6];
					var stafflist = assigned_staff.split(",");
					for(j=0; j<stafflist.length; j++)
					{
						var staff  =  stafflist[j];
						var StaffSelect = document.getElementById("cmb_staff").options;
						var m = 0;
						for(m = 0; m < StaffSelect.length; m++)
						{
							if(staff == StaffSelect[m].value)
							{
							 	StaffSelect[m].selected="selected";
							}
						}
					}
                }
            }
        }
		xmlHttp.send(strURL);
	}
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->

         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
           	<div class="title">Agreement Sheet - Staff Assign</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                       
                            <div class="container">

                                <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                                    <tr>
										<td width="17%">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td> 
									 	<td  class="label">Work Short Name </td>
									 	<td  class="labeldisplay">
											<select name="cmb_shortname" id="cmb_shortname" onChange="workorderdetail()" class="textboxdisplay" style="width:470px;height:22px;color:black;" tabindex="7">
												<option value="">---------------- Select Work Short Name ----------------</option>
												<?php echo $objBind->BindWorkOrderNoListStaff(0);?>
											</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
								 	<tr>
										<td>&nbsp;</td>
										<td></td>
										<td id="val_work" style="color:red" colspan="3"></td>
									</tr>
									<tr>
									   	<td>&nbsp;</td>
									   	<td  class="label">Work Order No. </td>
									   	<td  class="labeldisplay"><input type="text" name="txt_workorder" id="txt_workorder" readonly="" rows="6" class="textboxdisplay" style="width: 465px;"></td>
									   	<td>&nbsp;</td>
									   	<td>&nbsp;</td>
									</tr>
                                    <tr>
										<td>&nbsp;</td>
										<td></td>
										<td id="val_work" style="color:red" colspan="3"></td>
									</tr>
								 	<tr>
									   	<td>&nbsp;</td>
									   	<td  class="label">Name of the Work </td>
									   	<td  class="labeldisplay"><textarea name="txt_workname" id="txt_workname" readonly="" rows="6" class="textboxdisplay" style="width: 465px;"></textarea></td>
									   	<td>&nbsp;</td>
									   	<td>&nbsp;</td>
									</tr>
                                    <tr>
										<td>&nbsp;</td>
										<td></td>
										<td id="val_work" style="color:red" colspan="3"></td>
									</tr>
									
									<tr>
										<td>&nbsp;</td> 
									 	<td  class="label">Schedule of Activity </td>
									 	<td  class="labeldisplay">
											<select name="cmb_act" id="cmb_act"  class="textboxdisplay" style="width:470px;height:22px;color:black;" tabindex="7">
												<option value="">---------------- Select schedule of Activity----------------</option>
												<?php echo $objBind->Bindactivity(0);?>
											</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
									<tr>
										<td>&nbsp;</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>&nbsp;</td> 
									 	<td  class="label">Major Items </td>
									 	<td  class="labeldisplay">
											<select name="cmb_item" id="cmb_item"  class="textboxdisplay" style="width:470px;height:22px;color:black;" tabindex="7">
												<option value="">---------------- Select Major Items ----------------</option>
												<?php echo $objBind->Bind_major_items(0);?>
											</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
									<tr><td>&nbsp;</td><td></td><td></td></tr>
									<!--
									<tr>
										<td>&nbsp;</td> 
									 	<td  class="label">Group </td>
									 	<td  class="labeldisplay">
											<select name="cmb_grp" id="cmb_grp"  class="textboxdisplay" style="width:470px;height:22px;" tabindex="7">
												<option value="">---------------- Select Group----------------</option>
												<option value="Civil">Civil</option>											</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
									<tr><td>&nbsp;</td><td></td><td></td></tr>
									<tr>
										<td>&nbsp;</td> 
									 	<td  class="label">Division </td>
									 	<td  class="labeldisplay">
											<select name="cmb_division" id="cmb_division"  class="textboxdisplay" style="width:470px;height:22px;" tabindex="7">
												<option value="">---------------- Select Division----------------</option>
												<option value="Civil">Civil</option>	</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
									<tr><td>&nbsp;</td><td></td><td></td></tr>
									<tr>
										<td>&nbsp;</td> 
									 	<td  class="label">Section </td>
									 	<td  class="labeldisplay">
											<select name="cmb_section" id="cmb_section"  class="textboxdisplay" style="width:470px;height:22px;" tabindex="7">
												<option value="">---------------- Select Section----------------</option>
												<?php echo $objBind->Bindsection(0);?>
											</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
									<tr><td>&nbsp;</td><td></td><td></td></tr> -->
									<tr>
										<td>&nbsp;</td> 
									 	<td  class="label">Discipline </td>
									 	<td  class="labeldisplay">
											<select name="cmb_disp" id="cmb_disp"  class="textboxdisplay" style="width:470px;height:22px;color:black;" tabindex="7">
												<option value="">---------------- Select Discipline----------------</option>
												<?php echo $objBind->BindDiscipline(0);?>
											</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
									<tr><td>&nbsp;</td><td></td><td></td></tr>
									<tr>
										<td>&nbsp;</td> 
									 	<td  class="label">Plant Service </td>
									 	<td  class="labeldisplay">
											<select name="cmb_plant" id="cmb_plant"  class="textboxdisplay" style="width:470px;height:22px;color:black;" tabindex="7">
												<option value="">---------------- Select Plant Service----------------</option>
												<?php echo $objBind->Bindplant(0);?>
											</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
									<tr>
										<td>&nbsp;</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
									   	<td>&nbsp;</td>
									   	<td  class="label">Staff List<br/><font color="#F20006" style="font-size:11px">( Press Ctrl to select multiple staff )</font></td>
									   	<td  class="labeldisplay">
											<select name="cmb_staff[]" id="cmb_staff" multiple="multiple" style="width:465px;height:150px;color:black;" class="textboxdisplay"  onChange="func_mbook()">
												<?php echo $objBind->BindStaff($Mstaffid,1); ?>
											</select>
										</td>
									   	<td>&nbsp;</td>
									   	<td>&nbsp;</td>
									</tr>
									
                                    <tr>
										<td>&nbsp;</td>
										<td></td>
										<td id="val_staff" style="color:red" colspan="3"></td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;</td>
										<td width="" class="label"></td>
										<td id="val_rbn" style="color:red" colspan="3"></td>
									</tr>
                                    <tr>
                                       <td colspan="5">
										</td>
                                   </tr>
                                </table>
                            </div>
							
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="submit" data-type="submit" value=" Save " name="submit" id="submit"/>
								</div>
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
							</div>
                        
                        </form>
                    </blockquote>
                </div>

            </div>
			<br><br><br><br><br><br><br><br>
        </div>
         <!--==============================footer=================================-->
        <?php   include "footer/footer.html"; ?>
<script>
	$("#cmb_shortname").chosen();
    $(function() {
		$.fn.validateworkorder = function(event) { 
			if($("#cmb_shortname").val()==""){ 
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
		$.fn.validatestaff = function(event) { 
			//if($("#cmb_staff").val()==""){
			var staff = $("#cmb_staff").val(); 
			if(!staff){ 
				var a="Please select any one of staff from Staff List";
				$('#val_staff').text(a);
				event.preventDefault();
				event.returnValue = false;
				//return false;
			}else{
				var a="";
				$('#val_staff').text(a);
			}
		}
		$("#top").submit(function(event){
			$(this).validateworkorder(event);
			$(this).validatestaff(event);
        });
		$("#cmb_shortname").change(function(event){
           	$(this).validateworkorder(event);
        });
	});
	 
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
    </body>
</html>

