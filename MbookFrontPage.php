<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
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
	$_SESSION['mbooktype']		= $_POST['cmb_mbook_type'];
	$_SESSION['mbookno'] 		= $_POST['txt_mbook_no'];
	$_SESSION['mbookname'] 		= $_POST['txt_mbook_name'];
	$_SESSION['issue_authority'] = $_POST['cmb_issue_authority'];
	$_SESSION['workno'] 		= $_POST['cmb_work_no'];
	$_SESSION['mbookdate'] 		= $_POST['txt_mbook_date'];
	header('Location:MbookFrontPageView.php');
}

?>
<?php require_once "Header.html"; ?>
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
	function goBack()
	{
	   	url = "MyView.php";
		window.location.replace(url);
	}
	function find_mbookno()
	{	
		$('#txt_mbook_no').chosen('destroy');	
		document.form.txt_mbook_no.length = 0;
		var optn = document.createElement("option");
			optn.value = "";
			optn.text = "---select---";
		document.form.txt_mbook_no.options.add(optn);
		$('#txt_mbook_no').chosen();
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
		strURL="find_billformmbno.php?sheetid="+document.form.cmb_work_no.value+"&type="+document.form.cmb_mbook_type.value+"&staffid="+document.form.txt_staffid.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				//var name=data.split("*");
				//document.form.txt_mbook_no.value = "";
				if(data=="")
				{
					alert("No Records Found");
					//document.form.workname.value='';	
				}
				else
				{	//alert(data);
					var SplitData = data.split(",");
					$('#txt_mbook_no').chosen('destroy');
					document.form.txt_mbook_no.length = 0;
					var optn = document.createElement("option");
					optn.value = "";
					optn.text = "---select---";
					document.form.txt_mbook_no.options.add(optn);
					for(i=0; i<SplitData.length; i++){
						var optn = document.createElement("option");
							optn.value = SplitData[i];
							optn.text = SplitData[i];
						document.form.txt_mbook_no.options.add(optn);
					}
					$('#txt_mbook_no').chosen();
				}
			}
		}
		xmlHttp.send(strURL);	
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
                        <div class="title">MBook Front Page - Generate</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<input type="hidden" class="text" name="txt_staffid" id="txt_staffid" value="<?php echo $staffid; ?>" />
                            <div class="container">
							<br/>
                 <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                 <tr><td width="22%">&nbsp;</td></tr>
                 <tr>
					<td>&nbsp;</td> 
					<td  class="label">Work Short Name</td>
					<td  class="labeldisplay">
						<select name="cmb_work_no" id="cmb_work_no" onChange="find_workname()" class="textboxdisplay" style="width:465px;height:22px;" tabindex="7">
							<option value="">----------------------- Select ------------------------</option>
							<?php echo $objBind->BindWorkOrderNo(0); ?>
						</select>
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
				<tr>
                    <td>&nbsp;</td>
                    <td  class="label">Work Order No.</td>
                    <td  class="labeldisplay">
					<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplay" style="width:465px;" disabled="disabled">
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
                <tr><td>&nbsp;</td><td></td><td id="val_workorder" style="color:red"></td></tr>
				<tr>
                    <td>&nbsp;</td>
                    <td  class="label">Name of the Work </td>
                    <td  class="labeldisplay">
					<textarea name="workname" class="textboxdisplay txtarea_style" style="width: 465px;" rows="5" disabled="disabled"></textarea>
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
                <tr><td>&nbsp;</td><td></td><td id="val_workname" style="color:red"></td></tr>
                <tr> 
                    <td>&nbsp;</td> 
                    <td  class="label">Measurement Book Type </td>
                    <td  class="labeldisplay">
                      <select name="cmb_mbook_type" id="cmb_mbook_type" class="textboxdisplay" style="width:465px;height:22px;" onChange="find_mbookno();" tabindex="7">
                        <option value="">----------------------- Select ------------------------</option>
						<option value="G">General M.Book</option>
						<option value="S">Steel M.Book</option>
						<option value="A">Abstract M.Book</option>
						<option value="E">Escalation M.Book</option>
                      </select>
					</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
				<tr>
                   <td>&nbsp;&nbsp;</td><td width="25%" class="label"></td>
                   <td id="val_mbooktype" style="color:red">
                </tr>
                <tr> 
                    <td>&nbsp;</td> 
                    <td  class="label">Measurement Book No. </td>
                    <td  class="labeldisplay">
						<input type="text" name="txt_mbook_name" id="txt_mbook_name" value="BARC/NRB/FRFCF/CIVIL/" class="textboxdisplay" style="width:300px;">
						<!--<input type="text" name="txt_mbook_no" id="txt_mbook_no" class="textboxdisplay" style="width:82px;" readonly="">-->
						<select name="txt_mbook_no" id="txt_mbook_no" class="textboxdisplay" style="width:155px;">
							<option value="">---select---</option>
						</select>
					</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
				<tr>
                   <td>&nbsp;&nbsp;</td><td width="25%" class="label"></td>
                   <td id="val_mbookname" style="color:red">
                </tr>
                <tr> 
                    <td>&nbsp;</td> 
                    <td  class="label">Measurement Book Type </td>
                    <td  class="labeldisplay">
						<input type="text" name="cmb_issue_authority" id="cmb_issue_authority" value="" class="textboxdisplay" style="width:300px;">
                      <!--<select name="cmb_issue_authority" id="cmb_issue_authority" class="textboxdisplay" style="width:465px;height:22px;" tabindex="7">
                        <option value="">----------------------- Select -----------------------</option>
						<option value="DH">L. Davy Herbert. ACE,FRFCF</option>
                      </select>-->
					</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
				<tr>
                   <td>&nbsp;&nbsp;</td><td width="25%" class="label"></td>
                   <td id="val_mbooktype" style="color:red">
                </tr>
				<tr>
                    <td>&nbsp;</td>
                    <td  class="label">MBook Date</td>
                    <td  class="labeldisplay">
					<input type="text" name="txt_mbook_date" id="txt_mbook_date" class="textboxdisplay" style="width:120px;">
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
                <tr><td>&nbsp;</td><td></td><td id="val_workorder" style="color:red"></td></tr>
                <tr>
                   <td colspan="6">
                     <center>
                        <input type="hidden" class="text" name="submit" value="true" />
						<input  type="hidden" class="text" name="runningbilltext" id="runningbilltext" value=""/>
                        <!--<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"   />&nbsp;&nbsp;&nbsp;
						<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />--> 
                      </center>	    
					</td>
                </tr>
         </table>
     </div>
	 <div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
			</div>
			<div class="buttonsection">
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
	$("#cmb_work_no").chosen();
	$("#cmb_mbook_type").chosen();
	//$("#cmb_issue_authority").chosen();
	$("#txt_mbook_no").chosen();
	
    $(function() {
	
	$( "#txt_mbook_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });
	
	
	$.fn.validatembooktype = function(event) {	
				if($("#cmb_mbook_type").val()==""){ 
					var a="Please select the Measurement Type";
					$('#val_mbooktype').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
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
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
		$.fn.validatmbookname = function(event) { 
					if($("#txt_mbook_name").val()==""){ 
					var a="Please Enter MBook Name";
					$('#val_mbookname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_mbookname').text(a);
				}
			}

		$("#top").submit(function(event){
				$(this).validatembooktype(event);
				$(this).validateworkorder(event);
				$(this).validatmbookname(event);
			 });
		$("#cmb_work_no").change(function(event){
			   $(this).validateworkorder(event);
			 });
		$("#cmb_mbook_type").change(function(event){
			   $(this).validatembooktype(event);
			 });
		 $("#txt_mbook_name").change(function(event){
			   $(this).validatmbookname(event);
			 });
			
	 });
</script>
    </body>
</html>

