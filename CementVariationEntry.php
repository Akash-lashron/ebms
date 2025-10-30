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
if(isset($_POST['btn_submit'])=="btn_submit"){
	$sheetid 		= $_POST['cmb_shortname'];
	$subdivid 		= $_POST['cmb_item_no'];
	$as_agmt_wt 	= $_POST['txt_agmt_weight'];
	$as_used_wt 	= $_POST['txt_used_weight'];
	$difference_wt 	= $_POST['txt_differ_weight'];
	$rate 			= $_POST['txt_rate'];
	$insert_variation_query = "insert into cement_temp_variation set variat_type = 'C', sheetid = '$sheetid', subdivid = '$subdivid', as_agmt_wt = '$as_agmt_wt',
								as_used_wt = '$as_used_wt', difference_wt = '$difference_wt', rate = '$rate', modifieddate = NOW(), active = 1 , userid = ".$_SESSION['userid'];
	$insert_variation_sql = mysql_query($insert_variation_query);
	if($insert_variation_sql == true){
		$msg = 'Successfully Saved'; $success = 1;
	}else{
		$msg = 'Not Saved. Please try again !'; $success = 0;
	}
	//echo $success;exit;
}
?>
<?php require_once "Header.html"; ?>
<script>
	 $(function() {
	 	$("#txt_agmt_weight").change(function(event){
			var agmt_wt = $("#txt_agmt_weight").val();
			var used_wt = $("#txt_used_weight").val();
			if(agmt_wt != "" && used_wt != ""){
				var differ = Number(used_wt)-Number(agmt_wt);
				$("#txt_differ_weight").val(differ);
			}
		});
		$("#txt_used_weight").change(function(event){
			var agmt_wt = $("#txt_agmt_weight").val();
			var used_wt = $("#txt_used_weight").val();
			if(agmt_wt != "" && used_wt != ""){
				var differ = Number(used_wt)-Number(agmt_wt);
				$("#txt_differ_weight").val(differ);
			}
		});
	 });
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
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function getAllItem(){ 
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
        strURL = "find_all_itemno.php?sheetid=" + document.form.cmb_shortname.value;
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
                	var name = data.split("@#*#@");
					document.form.cmb_item_no.length = 0;
					var optn = document.createElement("option");
					optn.value = "";
					optn.text = "------------------------ Select Item No ------------------------";
					document.form.cmb_item_no.options.add(optn);
                    for(i = 0; i < name.length; i+=6)
                    {
                    	var optn = document.createElement("option")
						optn.value = name[i];
						optn.text = name[i+1];
						optn.setAttribute('data-itemno', name[i+1]);
						optn.setAttribute('data-itemrate', name[i+2]);
						optn.setAttribute('data-itemdecimal', name[i+3]);
						optn.setAttribute('data-itemunit', name[i+4]);
						optn.setAttribute('data-itemdescription', name[i+5]);
						//optn.setAttribute('data-itembaserate', name[i+6]);
						document.form.cmb_item_no.options.add(optn)
                    }

                }
            }
        }
     	xmlHttp.send(strURL);
	}
	function goBack(){
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
                        <div class="title">Variation Statement - Generate</div>
            <div class="container_12">
                <div class="grid_12" align="center">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="">
                            <div class="container">
                 <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                 <tr><td width="22%">&nbsp;</td></tr>
                 <tr>
					<td>&nbsp;</td> 
					<td  class="label">Work Short Name</td>
					<td  class="labeldisplay">
					<select name="cmb_shortname" id="cmb_shortname" onChange="find_workname(); getAllItem();" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
						<option value="">-------------------- Select --------------------</option>
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
					<textarea name="workname" class="textboxdisplay txtarea_style" style="width: 400px;" rows="3" disabled="disabled"></textarea>
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
                <tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
         </table>
		 <table class="table-outer-boder" width="60%" align="center">
		 	<tr>
				<th colspan="2" align="center">Variation Entry Form for Cement</th>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="center"></td>
			</tr>
			<tr>
				<td align="right">Item No</td>
				<td align="center">
					<select name="cmb_item_no" id="cmb_item_no" class="textboxdisplay">
						<option value="">------------------------ Select Item No ------------------------</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="left" style="color:red;" id="val_item_no"></td>
			</tr>
			<tr>
				<td align="right">Weight as per Agreement</td>
				<td align="center">
					<input type="text" name="txt_agmt_weight" id="txt_agmt_weight" class="textboxdisplay" >
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="left" style="color:red;" id="val_agmt_weight"></td>
			</tr>
			<tr>
				<td align="right">Actual Weight used at site</td>
				<td align="center">
					<input type="text" name="txt_used_weight" id="txt_used_weight" class="textboxdisplay" >
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="left" style="color:red;" id="val_used_weight"></td>
			</tr>
			<tr>
				<td align="right">Difference / variation</td>
				<td align="center">
					<input type="text" name="txt_differ_weight" id="txt_differ_weight" class="textboxdisplay" readonly="">
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="left" style="color:red;" id="val_differ"></td>
			</tr>
			<tr>
				<td align="right">Rate ( Rs.)</td>
				<td align="center">
					<input type="text" name="txt_rate" id="txt_rate" class="textboxdisplay" >
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="left" style="color:red;" id="val_rate"></td>
			</tr>
		 </table>
     	</div>
   		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
			</div>
			<div class="buttonsection" id="view_btn_section">
			<input type="submit" class="btn" value=" Submit " name="btn_submit" id="btn_submit"/>
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
	$.fn.validateItemNo = function(event) { 
		if($("#cmb_item_no").val()==""){ 
			var a="Please select the Item number";
			$('#val_item_no').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			var a="";
			$('#val_item_no').text(a);
		}
	}
	$.fn.validateAsPerAgmt = function(event) { 
		if($("#txt_agmt_weight").val()==""){ 
			var a="Please Enetr Weight as per Agreement";
			$('#val_agmt_weight').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			var a="";
			$('#val_agmt_weight').text(a);
		}
	}
	$.fn.validateusedWt = function(event) { 
		if($("#txt_used_weight").val()==""){ 
			var a="Please Enter Actual Weight used at site";
			$('#val_used_weight').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			var a="";
			$('#val_used_weight').text(a);
		}
	}
	$.fn.validateDifferenceWt = function(event) { 
		if($("#txt_differ_weight").val()==""){ 
			var a="Invalid Difference / Variation";
			$('#val_differ').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			if(isNaN($("#txt_differ_weight").val())){
				var a="Invalid Difference / Variation";
				$('#val_differ').text(a);
				event.preventDefault();
				event.returnValue = false;
			}else{
				var a="";
				$('#val_differ').text(a);
			}
		}
	}
	$.fn.validateRate = function(event) { 
		if($("#txt_rate").val()==""){ 
			var a="Please Enter rate";
			$('#val_rate').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			var a="";
			$('#val_rate').text(a);
		}
	}
	$("#top").submit(function(event){
		$(this).validateworkorder(event);
		$(this).validateItemNo(event);
		$(this).validateAsPerAgmt(event);
		$(this).validateusedWt(event);
		$(this).validateDifferenceWt(event);
		$(this).validateRate(event);
    });
	$("#cmb_shortname").change(function(event){
        $(this).validateworkorder(event);
    });
	$("#cmb_item_no").change(function(event){
        $(this).validateItemNo(event);
    });
	$("#txt_agmt_weight").keyup(function(event){
        $(this).validateAsPerAgmt(event);
		$(this).validateDifferenceWt(event);
    });
	$("#txt_used_weight").keyup(function(event){
        $(this).validateusedWt(event);
		$(this).validateDifferenceWt(event);
    });
	$("#txt_rate").keyup(function(event){
        $(this).validateRate(event);
    });
});
</script>
<script>
	//$("#cmb_shortname").chosen();
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

