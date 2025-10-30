<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
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
if($_POST["submit"] == " View ") 
{
	$workno = $_POST['cmb_work_no'];
	$PODate = $_POST['txt_po_date'];
	$PinIdList = $_POST['txt_pinid'];
	$PinAmtList = $_POST['txt_pin_amt'];
	if(count($PinIdList)>0){
		$PinIdStr = implode("@*@",$PinIdList);
		$PinAmtStr = implode("@*@",$PinAmtList);
	}else{
		$PinIdStr = "";
		$PinAmtStr = "";
	}
	$_SESSION['PinIdStr'] = $PinIdStr;
	$_SESSION['PinAmtStr'] = $PinAmtStr;
	//echo $PinIdStr."<br/>";
	//echo $PinAmtStr."<br/>";
	//exit;
	$abs_last_page = $_POST['txt_abs_last_page'];
	$_SESSION['abs_last_page'] = $abs_last_page;
	$_SESSION['PassOrderDate'] = $PODate;
	
	
	
	$emptypage = $_POST['txt_empty_page'];
	$_SESSION['emptypage'] = $emptypage;
	header('Location: AbstMBook_Bill_Confirm.php?workno='.$workno); 
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
	function goBack(){
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	function check_bill_confirm(){		
		var xmlHttp;
		var data;
		var i,j;
		document.form.txt_rbn.value	= "";
		document.form.txt_empty_page.value	= "";	
		document.form.txt_po_amt.value = "";
		document.form.txt_mbno.value = "";
		document.form.txt_end_page.value = "";
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_bill_confirm.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				//alert(data);
				if(data=="")
				{
					swal("No Bill available to confirm", "");
					document.getElementById("view_btn_section").style.display = "none";
				}
				else if(data == 0)
				{	
					swal("No Bill available to confirm", "");
					document.getElementById("view_btn_section").style.display = "none";
				}
				else
				{
					document.getElementById("view_btn_section").style.display = "";
					document.form.txt_rbn.value			= name[0];
					document.form.txt_empty_page.value	= name[1];
					document.form.txt_end_page.value	= name[2];
					document.form.txt_mbno.value		= name[3];
					document.form.txt_po_amt.value		= name[5];
					/*var PinList		= name[4];
					var SplitPinList = PinList.split("@@#@@");
					document.getElementById("PinDt").innerHTML  = "";
					var str = '';
					for(i=0; i<SplitPinList.length; i+=2){
						var PinId = SplitPinList[i+0];
						var PinNo = SplitPinList[i+1];
						str += '<div class="grid_3" align="left">&emsp;PIN No.<input type="hidden" name="txt_pinid[]" id="txt_pinid" value="'+PinId+'"></div>';
						str += '<div class="grid_3" align="center"><input type="text" name="txt_pin_no[]" id="txt_pin_no" value="'+PinNo+'" class="textboxdisplay disable" readonly="" style="width:85%;"/></div>';
						str += '<div class="grid_3" align="left">&emsp;Amount</div>';
						str += '<div class="grid_3" align="center"><input type="text" name="txt_pin_amt[]" id="txt_pin_amt" value="" class="textboxdisplay" style="width:85%;"/></div>';
						str += '<div class="grid_12" align="center">&nbsp;</div>';
					}
					document.getElementById("PinDt").innerHTML  = str;*/
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	.grid-box{
		
	}
	.grid-box-header{
		background: #165EA5;
		border:1px solid #165EA5;
		color:#ffffff;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
		font-weight:bold;
		text-align:left;
		padding:4px 6px;
	}
	.grid-box-body{
		border:1px solid #165EA5;
		padding:4px 6px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
		color:#00008B;
		font-weight:bold;
	}
	.grid-box-body .disable{
		background:#E1E1E1;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Pass Order - Confirm</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="container">
								<br/>
								
								<div class="grid_2" align="center">&nbsp;</div>
								<div class="grid_8 grid-box" align="center">
									<div class="grid_12 grid-box-header" align="center">
										Pass Order Details
									</div>
									<div class="grid_12 grid-box-body" align="center">
										<div class="grid_12" align="left" style="color:#FF0000; font-size:11px;">* Only Electrical, Mechanical, MHE, ACV Composite works are displayed here. Other works pass order will be done by Accounts section.&nbsp;</div>
										<div class="grid_12" align="center">&nbsp;</div>
										<div class="grid_3" align="left">&emsp;Work Short Name</div>
										<div class="grid_9" align="center" style="font-weight:200;">
											<select name="cmb_work_no" id="cmb_work_no" onChange="find_workname();check_bill_confirm();" class="textboxdisplay" style="width:95%;height:22px;text-align:left">
												<option value="">---------------------- Select ----------------------</option>
												<?php echo $objBind->BindWorkOrderNoPassOrder(0); ?>
												<?php //echo $objBind->BindWorkOrderNo(0); ?>
											</select>
										</div>
										<div class="grid_12" align="center">&nbsp;</div>
										
										<div class="grid_3" align="left">&emsp;Work Order No.</div>
										<div class="grid_9" align="center">
											<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplay disable" style="width:95%;" disabled="disabled">
										</div>
										<div class="grid_12" align="center">&nbsp;</div>
										
										<div class="grid_3" align="left" style="line-height:30px;">&emsp;Name of the Work</div>
										<div class="grid_9" align="center">
											<textarea name="workname" class="textboxdisplay txtarea_style disable" style="width:95%;" rows="2" disabled="disabled"></textarea>
										</div>
										<div class="grid_12" align="center">&nbsp;</div>
										
										<div class="grid_3" align="left">&emsp;RAB</div>
										<div class="grid_3" align="center">
											<input type="text" name="txt_rbn" id="txt_rbn" value="" class="textboxdisplay disable" readonly="" style="width:85%;"/>
										</div>
										<div class="grid_3" align="left">&emsp;MBook No.</div>
										<div class="grid_3" align="center">
											<input type="text" name="txt_mbno" id="txt_mbno" value="" class="textboxdisplay disable" readonly="" style="width:85%;"/>
										</div>
										<div class="grid_12" align="center">&nbsp;</div>
										
										<div class="grid_3" align="left">&emsp;End Page</div>
										<div class="grid_3" align="center">
											<input type="text" name="txt_end_page" id="txt_end_page" value="" class="textboxdisplay disable" readonly="" style="width:85%;"/>
										</div>
										<div class="grid_3" align="left">&emsp;Abst. Last Page</div>
										<div class="grid_3" align="center">
											<input type="text" name="txt_abs_last_page" id="txt_abs_last_page" value="" class="textboxdisplay" style="width:85%;"/>
										</div>
										<div class="grid_12" align="center">&nbsp;</div>
										
										<div class="grid_3" align="left">&emsp;Pass Order Amount</div>
										<div class="grid_3" align="center">
											<input type="text" name="txt_po_amt" id="txt_po_amt" value="" class="textboxdisplay disable" style="width:85%;"/>
										</div>
										<div class="grid_3" align="left">&emsp;Pass Order Date</div>
										<div class="grid_3" align="center">
											<input type="text" name="txt_po_date" id="txt_po_date" value="" class="textboxdisplay" style="width:85%;"/>
										</div>
										<div class="grid_12" align="center">&nbsp;</div>
										<div class="PinDt" id="PinDt">
											<!--<div class="grid_3" align="left">&emsp;PIN No.</div>
											<div class="grid_3" align="center">
												<input type="text" name="txt_po_date" id="txt_po_date" value="" class="textboxdisplay" style="width:85%;"/>
											</div>
											<div class="grid_3" align="left">&emsp;Amount</div>
											<div class="grid_3" align="center">
												<input type="text" name="txt_po_date" id="txt_po_date" value="" class="textboxdisplay" style="width:85%;"/>
											</div>
											<div class="grid_12" align="center">&nbsp;</div>-->
										</div>
									</div>
								</div>
								<div class="grid_2" align="center">&nbsp;</div>
								
                 				<!--<table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                 					<tr><td width="22%">&nbsp;</td></tr>
									<tr>
										<td>&nbsp;</td> 
										<td class="label">Work Short Name</td>
										<td class="labeldisplay">
											<select name="cmb_work_no" id="cmb_work_no" onChange="find_workname();check_bill_confirm();" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
												<option value="">---------------------- Select ----------------------</option>
												<?php echo $objBind->BindWorkOrderNoPassOrder(0); ?>
												<?php //echo $objBind->BindWorkOrderNo(0); ?>
											</select>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
									<tr>
										<td>&nbsp;</td>
										<td class="label">Work Order No.</td>
										<td class="labeldisplay">
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
										<td  class="label">RAB No.</td>
										<td  class="labeldisplay">
											<input type="text" name="txt_rbn" id="txt_rbn" value="" class="textboxdisplay" readonly="" style="width:50px;"/>
											&emsp;&nbsp;
											<span class="label">MB No.</span>
											<input type="text" name="txt_mbno" id="txt_mbno" value="" class="textboxdisplay" readonly="" style="width:100px;"/>
											&emsp;&emsp;
											<span class="label">End Page</span>
											<input type="text" name="txt_end_page" id="txt_end_page" value="" class="textboxdisplay" readonly="" style="width:50px;"/>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
                					<tr><td>&nbsp;</td><td></td><td id="val_rbn" style="color:red"></td></tr>
									<tr>
										<td>&nbsp;</td>
										<td  class="label">Abstract Last Page</td>
										<td  class="labeldisplay">
											<input type="number" name="txt_abs_last_page" id="txt_abs_last_page" value="" class="textboxdisplay" style="width:100px;"/>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
                					<tr><td>&nbsp;</td><td></td><td id="val_abs_last_page" style="color:red"></td></tr>
									<tr>
										<td>&nbsp;</td>
										<td  class="label">Pass Order Date</td>
										<td  class="labeldisplay">
											<input type="text" name="txt_po_date" id="txt_po_date" value="" class="textboxdisplay" style="width:100px;"/>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
                					<tr><td>&nbsp;</td><td></td><td id="val_po_date" style="color:red"></td></tr>
									<tr>
									   <td colspan="6">
											<input type="hidden" class="text" name="submit" value="true" />
											<input type="hidden" class="text" name="runningbilltext" id="runningbilltext" value=""/>
										</td>
									</tr>
               						<tr><td></td></tr>
         						</table>-->
								<input type="hidden" class="text" name="submit" value="true" />
								<input type="hidden" class="text" name="runningbilltext" id="runningbilltext" value=""/>
                				<input type="hidden" name="txt_empty_page" id="txt_empty_page" value="" class="textboxdisplay" style="width:100px;"/>
								<div style="text-align:center; height:45px; line-height:45px;" class="grid_11 printbutton">
									<div class="buttonsection">
									<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
									</div>
									<div class="buttonsection" id="view_btn_section" style="display:none">
									<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"/>
									</div>
								</div>
								
     						</div>
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
         <!--==============================footer=================================-->
	<?php include "footer/footer.html"; ?>
<script>
$("#cmb_work_no").chosen();
$(function() {
	$.fn.validatelastpage = function(event) {
		var EndPage = $("#txt_end_page").val();
		var LastPage = $("#txt_abs_last_page").val();
		if((LastPage == "")||(Number(LastPage)<1)){ 
			if(LastPage == ""){
				var a="Please Enter Abstract Last Page";
			}else{
				var a="Please Enter Valid Page No.";
			}
			//$('#val_abs_last_page').text(a);
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{  //alert();
			if(Number(EndPage) <= 100){
				if(Number(LastPage) < Number(EndPage)){
					var a="Last Page Number should be greater than End Page Number";
					//$('#val_abs_last_page').text(a);
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}else{
					var a="";
					$('#val_abs_last_page').text(a);
				}
			}
		}
	}
	$.fn.validateworkorder = function(event) { 
		if($("#cmb_work_no").val()==""){ 
			var a="Please select the work order number";
			//$('#val_work').text(a);
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			var a="";
			$('#val_work').text(a);
		}
	}
	$.fn.validatePODate = function(event) { 
		if($("#txt_po_date").val()==""){ 
			var a="Please enter Pass Order Date";
			//$('#val_po_date').text(a);
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			var a="";
			$('#val_po_date').text(a);
		}
	}
	$("#top").submit(function(event){
		$(this).validatelastpage(event);
		$(this).validateworkorder(event);
		$(this).validatePODate(event);
    });
	$("#cmb_work_no").change(function(event){
    	$(this).validateworkorder(event);
    });
    $("#txt_abs_last_page").change(function(event){
        $(this).validatelastpage(event);
    });
	$("#txt_po_date").datepicker({
      	changeMonth: true,
      	changeYear: true,
	   	dateFormat: "dd/mm/yy",
    });
});
</script>
    </body>
</html>

