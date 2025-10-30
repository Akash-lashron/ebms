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
		strURL="find_workname.php?sheetid="+document.form.cmb_shortname.value;
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
					//document.form.txt_book_no1.value		=	Number(name[1]) + Number(1);
					//document.form.txt_book_no.value			=	Number(name[1]) + Number(1);
					//document.form.txt_bookpage_no1.value	=	Number(name[2]) + Number(1);
					//document.form.txt_bookpage_no.value		=	Number(name[2]) + Number(1);
					//document.form.txt_rab_no1.value			=	Number(name[3]) + Number(1);
					//document.form.txt_rab_no.value			=	Number(name[3]) + Number(1);
	
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function GetSecAdvRAB()
    { 
    	var xmlHttp;
        var data;
		var i, j;
		document.form.cmb_rbn.length = 0;
		var optn = document.createElement("option");
			optn.value = "";
			optn.text = "------------- Select -------------";
		document.form.cmb_rbn.options.add(optn);
        if(window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
        	xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "../find_SecuredAdvRBN.php?sheetid=" + document.form.cmb_shortname.value;
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
                    var name 		= data.split("*");
					document.form.cmb_rbn.length = 0;
					var optn = document.createElement("option");
					optn.value = "";
					optn.text = "------ Select ------";
					document.form.cmb_rbn.options.add(optn);
                    for(i = 0; i < name.length; i++)
                    {
						var optn = document.createElement("option")
						optn.value = name[i];
						optn.text  = " RAB - "+name[i];
						document.form.cmb_rbn.options.add(optn)  
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
            <?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="SecuredAdvanceView.php">
                            <div class="container">
					<br/>
                 <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                 <tr><td width="22%">&nbsp;</td></tr>
                 <tr>
					<td>&nbsp;</td> 
					<td  class="label">Work Short Name</td>
					<td  class="labeldisplay"><?php 
					//$sql_itemno="select sheet_id ,short_name from sheet WHERE active =1"; 
					//$rs_itemno=mysql_query($sql_itemno);
					?>
					<select name="cmb_shortname" id="cmb_shortname" onChange="find_workname();GetSecAdvRAB();" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
					<option value="">-------------------- Select --------------------</option>
						<?php echo $objBind->BindWorkOrderNo(0); ?>
						<?php //echo $objBind->BindWorkOrderNo_CIVIL(0); ?>
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
                    <td class="label">RAB</td>
                    <td class="">
						<select name="cmb_rbn" id="cmb_rbn" style="width:210px;" class="textboxdisplay">
							<option value="">------ Select ------</option>
						</select>
					</td>
                </tr>
                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rbn" style="color:red" colspan="">&nbsp;</td></tr>
         </table>
     	</div>
   		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" name="back" value="Back" id="back" class="btn btn-info" onClick="goBack();" />
			</div>
			<div class="buttonsection" id="view_btn_section">
			<input type="submit" class="btn btn-info" value=" View " name="btn_view" id="btn_view"/>
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
			$.fn.validateworkorder = function(event) { 
					if($("#cmb_shortname").val()==""){ 
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
			$("#cmb_shortname").change(function(event){
           		$(this).validateworkorder(event);
         	});
	 });
</script>
<script>
	$("#cmb_shortname").chosen();
	//$("#cmb_rbn").chosen();
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

