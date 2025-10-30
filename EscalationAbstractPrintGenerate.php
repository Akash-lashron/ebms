<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
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
//$check_accounts_sheetid = checkSendAccounts();
if($_POST["submit"] == " View ") 
{
	$sheetid = $_POST["cmb_shortname"];
	$quarter = $_POST["cmb_quarter"];
	header('Location: EscalationAbstractPrint.php?sheetid='.$sheetid.'&quarter='.$quarter);
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
	function GetEscQuarterRBN()
     	{ 
            var xmlHttp;
            var data;
            var i, j;
			document.form.cmb_quarter.length = 0;
			document.getElementById("txt_rbn").value  = "";
			var optn = document.createElement("option");
				optn.value = "";
				optn.text = "------------- Select -------------";
			document.form.cmb_quarter.options.add(optn);
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_EscQuarterRBN.php?sheetid=" + document.form.cmb_shortname.value;
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
                        var name 		= data.split("@");
						var rbn 		= name[0];
						var QtrStr 		= name[1];
						document.getElementById("txt_rbn").value  = rbn;
						var SplitQtrStr = QtrStr.split("*");
						document.form.cmb_quarter.length = 0;
						var optn = document.createElement("option");
						optn.value = "";
						optn.text = "------------- Select -------------";
						document.form.cmb_quarter.options.add(optn);
                        for(i = 0; i < SplitQtrStr.length; i++)
                        {
							//document.form.txt_techsanction.value 	= name[0];
							//document.form.txt_agreemntno.value 		= name[2];
                            //document.form.txt_workname.value 		= name[3];
							//document.form.txt_workorder.value 		= name[5];
							var optn = document.createElement("option")
							optn.value = SplitQtrStr[i];
							optn.text = SplitQtrStr[i];
							document.form.cmb_quarter.options.add(optn)  
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
                        <div class="title">Escalation Abstract Print</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="container">
					<br/>
                 <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                 <tr><td width="20%">&nbsp;</td></tr>
                 <tr>
					<td>&nbsp;</td> 
					<td  class="label">Work Short Name</td>
					<td  class="labeldisplay"><?php 
					//$sql_itemno="select sheet_id ,short_name from sheet WHERE active =1"; 
					//$rs_itemno=mysql_query($sql_itemno);
					?>
					<select name="cmb_shortname" id="cmb_shortname" onChange="find_workname();GetEscQuarterRBN();" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
					<option value="">---------------------------------Select---------------------------------</option>
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
                    <td class="label">
						<input type="text" name='txt_rbn' readonly="" id='txt_rbn' class="textboxdisplay" style="width: 210px;">
						<input type="hidden" name='txt_esc_id' readonly="" id='txt_esc_id' class="textboxdisplay" style="width: 210px;">
					</td>
                </tr>
                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rbn" style="color:red" colspan="">&nbsp;</td></tr>
				<tr>
                    <td>&nbsp;</td>
                    <td class="label">Quarter</td>
                    <td class="label">
						<select name="cmb_quarter" id="cmb_quarter" style="width:210px;" class="textboxdisplay">
							<option value="">------------- Select -------------</option>
						</select>
					</td>
                </tr>
                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rbn" style="color:red" colspan="">&nbsp;</td></tr>
                <tr>
                   <td colspan="6">
                        <input type="hidden" class="text" name="submit" value="true" />
						<input  type="hidden" class="text" name="runningbilltext" id="runningbilltext" value=""/>
                       <!-- <input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"   />&nbsp;&nbsp;&nbsp;
						<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> -->
					</td>
                </tr>
                <tr><td></td></tr>
         </table>
     	</div>
   		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
			</div>
			<div class="buttonsection" id="view_btn_section">
			<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"/>
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

