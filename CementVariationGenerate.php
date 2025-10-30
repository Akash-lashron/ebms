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
if(isset($_POST['btn_submit'])==" Submit "){
	$sheetid 		= $_POST['cmb_shortname'];
	$subdivid 		= $_POST['cmb_item_no'];
	$rbn 			= $_POST['txt_rbn'];
	$difference_wt 	= $_POST['txt_differ_weight'];
	$rate 			= $_POST['txt_rate'];
	$UtzQty 		= $_POST['txt_utz_qty'];
	$VariAmt 		= $_POST['txt_var_amt'];
	$PayableRbn 	= $_POST['cmb_payable_rbn'];
	
	$DeleteVariationQuery = "update cement_temp_variation_dt set active = 0 where variat_type = 'C' and sheetid = '$sheetid' and rbn = '$rbn' and subdivid = '$subdivid' and qty_from_rbn = '$PayableRbn'";
	$DeleteVariationSql   = mysql_query($DeleteVariationQuery);
	
	$InsertVariationQuery = "insert into cement_temp_variation_dt set variat_type = 'C', sheetid = '$sheetid', rbn = '$rbn', subdivid = '$subdivid', utz_qty = '$UtzQty',
								difference_wt = '$difference_wt', rate = '$rate', variat_amt = '$VariAmt', qty_from_rbn = '$PayableRbn', modifieddate = NOW(), active = 1 , userid = ".$_SESSION['userid'];
	$InsertVariationSql   = mysql_query($InsertVariationQuery);
	
	//echo $InsertVariationQuery;exit;
	
	if($InsertVariationSql == true){
		$msg = 'Successfully Saved'; $success = 1;
	}else{
		$msg = 'Not Saved. Please try again !'; $success = 0;
	}
	//echo $success;exit;
}

if(isset($_POST['btn_delete']) == " Delete "){
	$sheetid 		= $_POST['cmb_shortname'];
	$subdivid 		= $_POST['cmb_item_no'];
	$rbn 			= $_POST['txt_rbn'];
	$difference_wt 	= $_POST['txt_differ_weight'];
	$rate 			= $_POST['txt_rate'];
	$UtzQty 		= $_POST['txt_utz_qty'];
	$VariAmt 		= $_POST['txt_var_amt'];
	$PayableRbn 	= $_POST['cmb_payable_rbn'];
	
	$DeleteVariationQuery = "update cement_temp_variation_dt set active = 0 where variat_type = 'C' and sheetid = '$sheetid' and rbn = '$rbn' and subdivid = '$subdivid' and qty_from_rbn = '$PayableRbn'";
	$DeleteVariationSql   = mysql_query($DeleteVariationQuery);
	
	//$InsertVariationQuery = "insert into cement_temp_variation_dt set variat_type = 'C', sheetid = '$sheetid', rbn = '$rbn', subdivid = '$subdivid', utz_qty = '$UtzQty',
								//difference_wt = '$difference_wt', rate = '$rate', variat_amt = '$VariAmt', qty_from_rbn = '$PayableRbn', modifieddate = NOW(), active = 1 , userid = ".$_SESSION['userid'];
	//$InsertVariationSql   = mysql_query($InsertVariationQuery);
	
	//echo $InsertVariationQuery;exit;
	
	if($DeleteVariationSql == true){
		$msg = 'Successfully Deleted'; $success = 1;
	}else{
		$msg = 'Not Deleted. Please try again !'; $success = 0;
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
		function getrbn()
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
            strURL = "findabstract_mbookno.php?sheetid=" + document.form.cmb_shortname.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
                    if (data == "")
                    {
                        BootstrapDialog.alert("Sub Abstract Not Generated. Please Generate Sub-Abstract");
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i++)
                        {
                            document.form.txt_rbn.value = name[3];
                        }

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
        strURL = "find_cenment_itemno.php?sheetid=" + document.form.cmb_shortname.value;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
        	if (xmlHttp.readyState == 4)
            {
			
            	data = xmlHttp.responseText; 
                if (data == "")
                {
                	BootstrapDialog.alert("No Cement vaiation item found. Please assign cement variation item.");
                }
                else
                {
                	var name = data.split("@#*#@");
					document.form.cmb_item_no.length = 0;
					var optn = document.createElement("option");
					//alert(optn)
					optn.value = "";
					optn.text = "------------------------ Select Item No ------------------------";
					document.form.cmb_item_no.options.add(optn);
                    for(i = 0; i < name.length; i+=6)
                    {
                    	var optn = document.createElement("option")
						//alert(optn)
						optn.value = name[i];
						optn.text = name[i+5];
						optn.setAttribute('data-agmt_wt', name[i+1]);
						//alert(name[i+1])
						optn.setAttribute('data-used_wt', name[i+2]);
						optn.setAttribute('data-difference_wt', name[i+3]);
						optn.setAttribute('data-rate', name[i+4]);
						
					
						document.form.cmb_item_no.options.add(optn)
                    }

                }
            }
        }
     	xmlHttp.send(strURL);
	}
     	function find_cement_var_rbn() { 
            var sheet_id = $("#cmb_shortname option:selected").attr('value');
			var item_no  = $("#cmb_item_no option:selected").attr('value');
			//alert(item_no)
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/find_cement_var_rbn.php', 
				data: { sheet_id: sheet_id, item_no: item_no }, 
				dataType: 'json',
				success: function (data) {
					$('#cmb_payable_rbn').children('option:not(:first)').remove();
					if(data != null){
						$.each(data, function(index, element) {
							$("#cmb_payable_rbn").append('<option>'+element.rbn+'</option>');
						});
					}
					//$("#cmb_payable_rbn").chosen();
				}
			});
        }
		 function total_qty() { 
            var sheet_id  = $("#cmb_shortname option:selected").attr('value');
			var item_no   = $("#cmb_item_no option:selected").attr('value');
			var rbn       = $("#cmb_payable_rbn option:selected").attr('value');
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/total_qty.php', 
				data: { sheet_id: sheet_id, item_no: item_no, rbn: rbn }, 
				success: function (data) {
					if(data != null){
						$("#txt_utz_qty").val(data);
						$('#txt_var_amt').val('');
						var Rate   = $("#txt_rate").val();
						var UtzQty = data;//$("#txt_utz_qty").val();
						var DiffWt = $("#txt_differ_weight").val();
						if((Rate != '')&&(UtzQty != '')&&(DiffWt != '')){
							var Amount = Number(Rate) * Number(UtzQty)* Number(DiffWt);
								Amount = Number(Amount).toFixed(2);
						}
						$('#txt_var_amt').val(Amount);
					}
				}
			});
			//CalculateVariAmount();
        }
	function goBack(){
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	function CalculateVariAmount(){ //alert();
		$('#txt_var_amt').val('');
		var Rate   = $("#txt_rate").val();
		var UtzQty = $("#txt_utz_qty").val();
		var DiffWt = $("#txt_differ_weight").val();
		if((Rate != '')&&(UtzQty != '')&&(DiffWt != '')){
			var Amount = Number(Rate) * Number(UtzQty)* Number(DiffWt);
				Amount = Number(Amount).toFixed(2);
		}
		$('#txt_var_amt').val(Amount);
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
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="">
                            <div class="container">
							
                 <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                 <tr><td width="22%">&nbsp;</td></tr>
                 <tr>
					<td>&nbsp;</td> 
					<td  class="label">Work Short Name</td>
					<td  class="labeldisplay">
					<select name="cmb_shortname" id="cmb_shortname" onChange="find_workname(); getrbn(); getAllItem();" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
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
				<tr>
                    <td>&nbsp;</td>
                    <td  class="label">RAB </td>
                    <td  class="labeldisplay">
					<input type="text" name="txt_rbn" id="txt_rbn" class="textboxdisplay" style="width:100px;" readonly="">
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
					<select name="cmb_item_no" id="cmb_item_no" onChange="find_cement_var_rbn();" class="textboxdisplay">
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
					<input type="text" name="txt_agmt_weight" id="txt_agmt_weight" class="textboxdisplay" readonly="" >
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="left" style="color:red;" id="val_agmt_weight"></td>
			</tr>
			<tr>
				<td align="right">Actual Weight used at site</td>
				<td align="center">
					<input type="text" name="txt_used_weight" id="txt_used_weight" class="textboxdisplay" readonly="">
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
					<input type="text" name="txt_rate" id="txt_rate" class="textboxdisplay" readonly="">
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="left" style="color:red;" id="val_rate"></td>
			</tr>
			
			<tr>
				<td align="right">Payable RAB</td>
				<td align="center">
					<select name="cmb_payable_rbn" id="cmb_payable_rbn" class="textboxdisplay"  onChange="total_qty();">
						<option value=0>--------------RBN.--------------</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="left" style="color:red;" id="val_rate"></td>
			</tr>
			<tr>
				<td align="right">Utilized Qty.</td>
				<td align="center">
					<input type="text" name="txt_utz_qty" id="txt_utz_qty" class="textboxdisplay">
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="left" style="color:red;" id="val_rate"></td>
			</tr>
			
			<tr>
				<td align="right">Variation Amount</td>
				<td align="center">
					<input type="text" name="txt_var_amt" id="txt_var_amt" class="textboxdisplay"  readonly="">
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="left" style="color:red;" id="val_rate"></td>
			</tr>
			
			
		 </table>
		 <!--<table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
		 <tr><td width="22%">&nbsp;</td></tr>
                 <tr>
					<td>&nbsp;</td> 
					<td  class="label">RAB  Bill No </td>
					<td  class="labeldisplay">
					<select name="cmb_rbn" id="cmb_rbn" class="textboxdisplay"  onChange="total_qty();" style="width:400px;height:22px;" tabindex="7">
						<option value=0>---------------------------------RBN.----------------------------------</option>
					</select>
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
				<tr>
                    <td>&nbsp;</td>
                    <td  class="label">Total Amount</td>
                    <td  class="labeldisplay">
					<input type="text" name="txt_utz_qty" id="txt_utz_qty" class="textboxdisplay" style="width:397px;">
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
		 </table>-->
     	</div>
   		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
			</div>
			<div class="buttonsection" id="view_btn_section">
			<input type="submit" class="btn" value=" Submit " name="btn_submit" id="btn_submit"/>
			</div>
			<div class="buttonsection" id="view_btn_section">
			<input type="submit" class="btn" value=" Delete " name="btn_delete" id="btn_delete"/>
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
	$('#txt_utz_qty').change(function(event){
		CalculateVariAmount();
	});
	/*$("#txt_agmt_weight").keyup(function(event){
        $(this).validateAsPerAgmt(event);
		$(this).validateDifferenceWt(event);
    });
	$("#txt_used_weight").keyup(function(event){
        $(this).validateusedWt(event);
		$(this).validateDifferenceWt(event);
    });
	$("#txt_rate").keyup(function(event){
        $(this).validateRate(event);
    });*/
});

</script>
<script>
$('body').on('change','#cmb_item_no',function(){
        var agmt_wt 	    = $("#cmb_item_no option:selected").attr("data-agmt_wt");
		var used_wt 	    = $("#cmb_item_no option:selected").attr("data-used_wt");
		var difference_wt 	= $("#cmb_item_no option:selected").attr("data-difference_wt");
		var rate	        = $("#cmb_item_no option:selected").attr("data-rate");
		  $('#txt_agmt_weight').val(agmt_wt);
		  $('#txt_used_weight').val(used_wt);
		  $('#txt_differ_weight').val(difference_wt);
		  $('#txt_rate').val(rate);
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

