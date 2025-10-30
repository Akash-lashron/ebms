<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "common.php";
checkUser();
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
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
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
<script>
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
					document.form.workname.value		=	name[0].trim();
					document.form.txt_workorder_no.value=	name[2].trim();
					document.form.txt_ccno.value		=	name[3].trim();
					document.form.txt_hoa.value			=	name[4].trim();
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function goBack()
	{
	   	url = "AccountsStatementSteps.php";
		window.location.replace(url);
	}
	function goBackAcc()
	{
	   	url = "MyViewAccounts.php";
		window.location.replace(url);
	}
</script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <!--==============================Content=================================-->
        <div class="content">
            <?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
						<div class="row clearrow"></div>
                        <form name="form" method="post" action="MopMemoForStatements.php">
						<div class="row">
							<div class="box-container box-container-lg" align="center">
								<div class="div2">&nbsp;</div>
								<div class="div8">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="center">&nbsp;Memo of Payment Statements</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<table width="90%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
														<tr>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
														<tr>
															<td>&nbsp;</td> 
															<td  class="lboxlabel">Computer Code No.</td>
															<td  class="labeldisplay">
															   <select name="cmb_work_no" id="cmb_work_no" class="textboxdisplay" style="width:470px;height:22px;" tabindex="7">
																 <option value="">-------------- Select CCNO ----------------</option>
																	<?php echo $objBind->BindCCNoMopStmt(0); ?> 
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
															<td  class="lboxlabel">Work Order No. </td>
															<td  class="labeldisplay"><input type="text" name="txt_workorder_no" id="txt_workorder_no" readonly="" class="textboxdisplay" style="width: 465px;"></td>
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
															<td class="lboxlabel">Name of the Work </td>
															<td class="labeldisplay"><textarea name="workname" id="workname" readonly="" rows="6" class="textboxdisplay" style="width: 465px;"></textarea></td>
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
															<td  class="lboxlabel">C.C. No.</td>
															<td  class="labeldisplay"><input type="text" name="txt_ccno" id="txt_ccno" class="textboxdisplay" style="width: 465px;"></td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
														<tr>
															<td>&nbsp;</td>
															<td></td>
															<td id="val_head_acc" style="color:red" colspan="3"></td>
														</tr>
														<tr>
															<td>&nbsp;</td> 
															<td  class="lboxlabel">Head of Account</td>
															<td  class="labeldisplay"><input type="text" name="txt_hoa" id="txt_hoa" class="textboxdisplay" style="width: 465px;"></td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
														<tr>
															<td>&nbsp;</td>
															<td></td>
															<td id="val_head_acc" style="color:red" colspan="3"></td>
														</tr>
														<tr>
															<td>&nbsp;</td>
															<td  class="lboxlabel">RAB No. </td>
															<td  class="labeldisplay">
																<select name="cmb_rbn" id="cmb_rbn" class="textboxdisplay" style="width:470px;height:22px;" tabindex="7">
																	<option value="">-------------- Select RAB ----------------</option>
																</select>
															</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
														<tr>
															<td>&nbsp;&nbsp;</td>
															<td width="" class="label"></td>
															<td id="val_rbn" style="color:red" colspan="3"></td>
														</tr>
													   <tr>
															<td colspan="5"></td>
													   </tr>
						
													</table>
													<div class="div12">
														<input type="hidden" name="txt_pay_type" id="txt_pay_type" class="textboxdisplay" value="FPAY">
														<input type="button" name="back" value="Back" id="back" class="btn btn-info" onClick="goBack();" />
														<input type="submit" class="btn btn-info" value=" GO " name="btn_go" id="btn_go"/>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="div2">&nbsp;</div>
								</div>
							</div>
						</div>
       				</form>
      			</blockquote>
    		</div>
   		</div>
	</div>
	<link rel="stylesheet" href="css/timeline.css">
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
$("#cmb_work_no").chosen();
//$("#cmb_head_acc").chosen();
$("#cmb_rbn").chosen();
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
	$("body").on("change","#cmb_work_no", function(event){
		GetWorkDetails();
		GetAllRabData();
	});
	function GetWorkDetails(){
		var WorkId = $("#cmb_work_no").val();
		$("#workname").val('');
		$("#txt_workorder_no").val('');
		$("#txt_ccno").val('');
		$("#txt_hoa").val('');
		$.ajax({ 
			type: 'POST', 
			dataType:'json',
			url: 'ajax/FindWorkDetails.php', 
			data: ({ WorkId: WorkId}), 
			success: function (data) {
				if(data != null){ 
					$.each(data, function(index, element) {
						$("#workname").val(element.work_name);
						$("#txt_workorder_no").val(element.work_order_no);
						$("#txt_ccno").val(element.computer_code_no);
						$("#txt_hoa").val(element.hoa);
					});
				}
			}
		});
	}
	function GetAllRabData(){
		var WorkId = $("#cmb_work_no").val();
		$.ajax({ 
			type: 'POST', 
			dataType:'json',
			url: 'find_AllRAB.php', 
			data: ({ WorkId: WorkId}), 
			success: function (data) {
				//alert(data);		
				$("#cmb_rbn").chosen("destroy");
				$("#cmb_rbn").find('option:not(:first)').remove();	
				if(data != null){ 
					$.each(data, function(index, element) {
						$("#cmb_rbn").append('<option value="'+element.rbn+'"><span class="testing">RAB - '+element.rbn+'</option>');
					});
				}
				$("#cmb_rbn").chosen();
			}
		});
	}
	
	
});
</script>

</body>
</html>

