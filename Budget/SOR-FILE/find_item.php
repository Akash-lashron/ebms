<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";

?>

<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ecms</title>
</head>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>

<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
<script language="javascript" type="text/javascript">

function func_level2()
{
	document.form.cmb_level2.value='Select';
	document.form.cmb_make.value='Select';
	document.form.cmb_level3.value='Select';
	document.form.txt_level3_desc.value='';	
	document.form.cmb_level4.value='Select';
	document.form.txt_item_desc.value='';
	
	document.getElementById("detailed_desc_1").style.display="none";
	document.getElementById("detailed_desc_2").style.display="none";
	document.form.txt_level3_detailed_desc.value='';
	
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
	//alert("find_level2.php?item_id="+document.form.cmb_level1.value+"&level="+'2')
	//strURL="find_level2.php?item_id="+document.form.cmb_level1.value+"&level="+'2'
	strURL="find_level2.php?item_id="+document.form.cmb_level1.value
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			
			if(data=="")
			{
				alert("No Records Found");
				document.form.cmb_level2.value='Select';	
				document.form.cmb_level3.value='Select';	
				document.form.cmb_level4.value='Select';
			}
			else
			{
				//alert(data)
				var name=data.split("*");
				//alert(name)
				document.form.cmb_level2.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_level2.options.add(optn)
				
				var c= name.length 
				//alert(name.length)
				var a=c/2;
				var b=a+1;
				
				for(i=1,j=b;i<a,j<c;i++,j++)
				{
					//alert("i="+i);
					//alert("name i="+name[i]);
					//alert("j="+j);
					//alert("name j="+name[j]);
					var optn=document.createElement("option")
					optn.value=name[i];
					optn.text=name[j];
					document.form.cmb_level2.options.add(optn)
				}
			}
		}
    }
	xmlHttp.send(strURL);	
}

function func_make()
{
	document.form.cmb_make.value='Select';
	document.form.cmb_level3.value='Select';
	document.form.txt_level3_desc.value='';	
	document.form.cmb_level4.value='Select';
	document.form.txt_item_desc.value='';
	
	document.getElementById("detailed_desc_1").style.display="none";
	document.getElementById("detailed_desc_2").style.display="none";
	document.form.txt_level3_detailed_desc.value='';
	
	var xmlHttp;
    var data;
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if(document.form.cmb_level2.value == '0507')
	{
		document.getElementById("cable_items").style.display="";
		document.getElementById("cable_glands_items").style.display="none";
		document.getElementById("cable_lugs_items").style.display="none";
		document.getElementById("contractor_items").style.display="none";
		document.getElementById("joints_items").style.display="none";
	}
	else if(document.form.cmb_level2.value == '0509')
	{
		document.getElementById("cable_items").style.display="none";
		document.getElementById("cable_glands_items").style.display="";
		document.getElementById("cable_lugs_items").style.display="none";
		document.getElementById("contractor_items").style.display="none";
		document.getElementById("joints_items").style.display="none";
	}
	else if(document.form.cmb_level2.value == '0510')
	{
		document.getElementById("cable_items").style.display="none";
		document.getElementById("cable_glands_items").style.display="none";
		document.getElementById("cable_lugs_items").style.display="";
		document.getElementById("contractor_items").style.display="none";
		document.getElementById("joints_items").style.display="none";
	}
	else if(document.form.cmb_level2.value == '0513')
	{
		document.getElementById("cable_items").style.display="none";
		document.getElementById("cable_glands_items").style.display="none";
		document.getElementById("cable_lugs_items").style.display="none";
		document.getElementById("contractor_items").style.display="";
		document.getElementById("joints_items").style.display="none";
	}
	else if(document.form.cmb_level2.value == '0514')
	{
		document.getElementById("cable_items").style.display="none";
		document.getElementById("cable_glands_items").style.display="none";
		document.getElementById("cable_lugs_items").style.display="none";
		document.getElementById("contractor_items").style.display="none";
		document.getElementById("joints_items").style.display="";
	}
	else 
	{
		document.getElementById("cable_items").style.display="none";
		document.getElementById("cable_glands_items").style.display="none";
		document.getElementById("cable_lugs_items").style.display="none";
		document.getElementById("contractor_items").style.display="none";
		document.getElementById("joints_items").style.display="none";
	}
	
	
	//alert("find_level3.php?item_id="+document.form.cmb_level2.value)
	strURL="find_level3_make.php?item_id="+document.form.cmb_level2.value
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			
			if(data=="*")
			{
				document.form.cmb_make.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_make.options.add(optn)
				
				var optn=document.createElement("option")
				optn.value="None";
				optn.text="None";
				document.form.cmb_make.options.add(optn)
			}
			else
			{
				//alert(data)
				var name=data.split("*");
				//alert(name)
				document.form.cmb_make.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_make.options.add(optn)
				
				var optn=document.createElement("option")
				optn.value="None";
				optn.text="None";
				document.form.cmb_make.options.add(optn)
				
				var c= name.length 
				//alert(name.length)
				var a=c/2;
				var b=a+1;
				
				for(i=1,j=b;i<a,j<c;i++,j++)
				{
					//alert("i="+i);
					//alert("name i="+name[i]);
					//alert("j="+j);
					//alert("name j="+name[j]);
					var optn=document.createElement("option")
					optn.value=name[i];
					optn.text=name[j];
					document.form.cmb_make.options.add(optn)
				}
			}
		}
    }
	xmlHttp.send(strURL);	
}

function func_level3()
{
	//document.form.cmb_level3.value='Select';
	//document.form.txt_level3_desc.value='';	
	//document.form.cmb_level4.value='Select';
	//document.form.txt_item_desc.value='';
	
	document.getElementById("detailed_desc_1").style.display="none";
	document.getElementById("detailed_desc_2").style.display="none";
	document.form.txt_level3_detailed_desc.value='';
	
	var xmlHttp;
    var data;
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	//alert("find_level3.php?item_id="+document.form.cmb_level2.value)
	strURL="find_level3.php?item_id="+document.form.cmb_level2.value+"&make="+document.form.cmb_make.value
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			
			if(data=="")
			{
				alert("No Records Found");
				document.form.cmb_level3.value='Select';	
				document.form.cmb_level4.value='Select';
			}
			else
			{
				//alert(data)
				var name=data.split("*");
				//alert(name)
				document.form.cmb_level3.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_level3.options.add(optn)
				
				var c= name.length 
				//alert(name.length)
				var a=c/2;
				var b=a+1;
				
				for(i=1,j=b;i<a,j<c;i++,j++)
				{
					//alert("i="+i);
					//alert("name i="+name[i]);
					//alert("j="+j);
					//alert("name j="+name[j]);
					var optn=document.createElement("option")
					optn.value=name[i];
					optn.text=name[j];
					document.form.cmb_level3.options.add(optn)
				}
			}
		}
    }
	xmlHttp.send(strURL);	
}

function func_level4()
{
	//document.form.cmb_level4.value='Select';
	//document.form.txt_item_desc.value='';
	
	//document.getElementById("detailed_desc_1").style.display="none";
	//document.getElementById("detailed_desc_2").style.display="none";
	//document.form.txt_level3_detailed_desc.value='';
	
	var xmlHttp;
    var data;
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	strURL="find_level4.php?item_id="+document.form.cmb_level3.value
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			
			if(data=="")
			{
				alert("No Records Found");
				document.form.cmb_level4.value='Select';
			}
			else
			{
				//alert(data)
				var name=data.split("*");
				//alert(name)
				document.form.cmb_level4.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_level4.options.add(optn)
				
				var c= name.length 
				//alert(name.length)
				var a=c/2;
				var b=a+1;
				
				for(i=1,j=b;i<a,j<c;i++,j++)
				{
					//alert("i="+i);
					//alert("name i="+name[i]);
					//alert("j="+j);
					//alert("name j="+name[j]);
					var optn=document.createElement("option")
					optn.value=name[i];
					optn.text=name[j];
					document.form.cmb_level4.options.add(optn)
				}
			}
		}
    }
	xmlHttp.send(strURL);
	func_level3_desc();
	func_item();
	//func_level3_detailed_desc()
}


function func_level3_desc()
{
	var selIndex = document.form.cmb_level3.selectedIndex;
	var comboValue = document.form.cmb_level3.options[selIndex].text;
	document.form.txt_level3_desc.value=comboValue
}

function func_level3_detailed_desc()
{
	//alert(document.form.cmb_level5.value)
	var xmlHttp;
    var data;
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	strURL="find_level3_detailed_description.php?item_id="+document.form.cmb_level3.value
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			if(data!='*')
			{
				document.getElementById("detailed_desc_1").style.display="";
				document.getElementById("detailed_desc_2").style.display="";
				document.form.txt_level3_detailed_desc.value=data;
			}
			else
			{
				document.getElementById("detailed_desc_1").style.display="none";
				document.getElementById("detailed_desc_2").style.display="none";
				document.form.txt_level3_detailed_desc.value='';
			}
	   }
   }
	xmlHttp.send(strURL);	
}

function func_item()
{
	//alert(document.form.cmb_level5.value)
	var xmlHttp;
    var data;
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	strURL="find_item_description.php?item_id="+document.form.cmb_level3.value;
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			if(data!='*')
			{
				x=data.split('*')
				document.form.txt_itemid.value=document.form.cmb_level3.value;
				document.form.txt_item_desc.value="[ "+x[3]+" ] - "+x[0];
				document.form.txt_item_unit.value=x[1];
				document.form.txt_item_rate.value=x[2];
				//alert();
			}
	   }
   }
	xmlHttp.send(strURL);	
}


function send_to()
{
	var SearchType = document.form.search_type.value;
	window.opener.document.getElementById("txt_item_id").value=document.getElementById("txt_itemid").value;
	window.opener.document.getElementById("txt_desc").value=document.getElementById("txt_item_desc").value;
	window.opener.document.getElementById("txt_unit").value=document.getElementById("txt_item_unit").value;
	if(SearchType == 'A'){
		window.opener.document.getElementById("txt_rate").value=document.getElementById("txt_item_rate").value;
	}
	if(SearchType == 'SD'){
		window.opener.document.getElementById("txt_rate").value=document.getElementById("txt_item_rate_sd").value;
	}
	window.close()
	
}

</script>


<body>
<form name="form" method="post">
<table width="732" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#b3b2b2">
	<tr class="heading">
		<td width="20" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
		<td width="676" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Select Item</td>
		<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
	</tr>
</table>

<table width="732" border="0" cellpadding="0" cellspacing="0" align="center" class="color1">
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan="2" class="label" style="text-align:center; vertical-align:middle; padding-bottom:3px;">
			<input type="radio" name="search_type" id="search_type_all" value="A" checked="checked" style="margin:0px !important">&nbsp;Material/Labour/Hire Charges&emsp;&emsp;&emsp;
			<input type="radio" name="search_type" id="search_type_sd" value="SD">&nbsp;Sub Data
		</td>
	</tr>
	<tr style="display:none">
		<td width="70" class="label">&nbsp;&nbsp;Level 1</td>
		<td width="530">
			<?php
			$sql_level1="select * from item_master where item_id in ('07') order by item_id";
			$rs_level1=mysqli_query($dbConn,$sql_level1,$conn);
			?>
			<select class="text" style="width:400px;height:21px;" name="cmb_level1" ID="cmb_level1" onChange="func_level2()">
				<!--option value="Select">Select</option-->
				<?php
				while($row=mysqli_fetch_assoc($rs_level1))
				{
					?>
						<option value="<?php echo $row['item_id'];?>"><?php echo $row['item_desc'];?></option>
					<?php
				}
			?>
			</select>
	  	</td>
	</tr>
	<tr class="all"><td>&nbsp;</td></tr>
	<tr class="all">
		<td width="70" class="label">&nbsp;&nbsp;Select</td>
		<td width="530">
				<?php
						$sql_single_level2="select * from item_master where item_id like '07%' and char_length( item_id ) = '4' order by item_id";
						$rs_single_level2=mysqli_query($dbConn,$sql_single_level2,$conn);
				?>
			<select class="text" style="width:564px;height:21px;" name="cmb_level2" ID="cmb_level2" onChange="func_level3()">
				<option value="Select">Select</option>
							<?php
							while($row=mysqli_fetch_assoc($rs_single_level2))
							{
								?>
								<option value="<?php echo $row['item_id'];?>"><?php echo $row['item_desc'];?></option>
								<?php
							}
						?>
			</select>
	  	</td>
	</tr>
	<tr class="all"><td>&nbsp;</td></tr>
	
	<tr style="display:none">
		<td width="70" class="label">&nbsp;&nbsp;Make</td>
		<td width="530">
			<select class="text" style="width:360px;height:21px;" name="cmb_make" ID="cmb_make" >
				<option value="None">None</option>
			</select>
		</td>
	</tr>
	
	<tr class="all">
		<td width="70" class="label">&nbsp;&nbsp;Select Item </td>
		<td width="530">
			<select class="text" style="width:564px;height:21px;" name="cmb_level3" ID="cmb_level3" onChange="func_level4()">
				<option value="Select">Select</option>
			</select>
	  	</td>
	</tr>
	<tr class="all"><td>&nbsp;</td></tr>
	
	<tr class="all">
		<td width="70" class="label" nowrap="nowrap" valign="top">&nbsp;&nbsp;Item&nbsp;&nbsp;Desc.</td>
		<td width="530"><textarea name="txt_level3_desc" id="txt_level3_desc" class="text" cols="75" rows="4" readonly="readonly"></textarea></td>
	</tr>
	<tr class="all"><td>&nbsp;</td></tr>
	<tr class="all">
		<td width="70" class="label">&nbsp;&nbsp;Rate (Rs.)</td>
		<td width="530">
			<input type="text" style="width:60px;" name="txt_item_rate" id="txt_item_rate" readonly="" />
	  	</td>
	</tr>
	<tr class="all"><td>&nbsp;</td></tr>
	<tr style="display:none" id="detailed_desc_1">
		<td width="70" class="label" nowrap="nowrap" valign="top">&nbsp;&nbsp;Detailed&nbsp;&nbsp;Desc</td>
		<td width="530"><textarea name="txt_level3_detailed_desc" id="txt_level3_detailed_desc" class="text" cols="75" rows="4" readonly="readonly"></textarea></td>
	</tr>
	<tr style="display:none" id="detailed_desc_2"><td>&nbsp;</td></tr>
	
	<tr style="display:none">
		<td width="70" class="label">&nbsp;&nbsp;Level 4</td>
		<td width="530">
			<select class="text" style="width:400px;height:21px;" name="cmb_level4" ID="cmb_level4" onBlur="func_item()" onChange="func_item()">
				<option value="Select">Select</option>
			</select>
	  	</td>
	</tr>
	
	<tr style="display:nonew">
		<td width="70" class="label">&nbsp;&nbsp;Item</td>
		<td width="530">
			<input type="hidden" style="width:36px;" name="txt_item_desc" id="txt_item_desc" class="text" readonly="" />
			<input type="text" style="width:36px;" name="txt_itemid" id="txt_itemid" readonly="" />
			<input type="hidden" style="width:36px;" name="txt_item_unit" id="txt_item_unit" readonly="" />
	  	</td>
	</tr>
	
	<tr class="sd hide"><td>&nbsp;</td></tr>
	<tr class="sd hide">
		<td width="70" class="label">&nbsp;&nbsp;Code</td>
		<td width="530">
			<input type="text" class="labelfield" list="ItemCodeList" size="10" style="width:564px" name="txt_code" id="txt_code" value="" autocomplete="off"/>
			<datalist id="ItemCodeList" style="color:#C80B5B; font-size:16px">
			<?php echo $objBind->BindSubData(0); ?>
			</datalist>
	  	</td>
	</tr>
	<tr class="sd hide"><td>&nbsp;</td></tr>
	<tr class="sd hide">
		<td width="70" class="label" nowrap="nowrap" valign="top">&nbsp;&nbsp;Item&nbsp;&nbsp;Desc.</td>
		<td width="530"><textarea name="txt_desc" id="txt_desc" class="text" cols="75" rows="4" readonly="readonly"></textarea></td>
	</tr>
	<tr class="sd hide"><td>&nbsp;</td></tr>
	<tr class="sd hide">
		<td width="70" class="label">&nbsp;&nbsp;Rate (Rs.)</td>
		<td width="530">
			<input type="text" style="width:60px;" name="txt_item_rate_sd" id="txt_item_rate_sd" readonly="" />
	  	</td>
	</tr>
	<tr class="all"><td>&nbsp;</td></tr>
	<tr align="center">
	  <td colspan="2"><input type="button" name="btn_insert" value="Insert" onClick="send_to()" /></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>

</form>
</body>
</html>
<script>
	$(function(){
		$('input[type=radio][name=search_type]').click(function(event){ 
			var type = $(this).val();
			if(type == 'A'){
				$('.sd').addClass('hide');
				$('.all').removeClass('hide');
			}
			if(type == 'SD'){
				$('.all').addClass('hide');
				$('.sd').removeClass('hide');
			}
		});
		$('#txt_code').change(function(){
			var itemCode = $(this).val();
			$('#txt_desc').val('');
			$("#txt_item_rate_sd").val('');
			$("#txt_item_desc").val('');
			$("#txt_itemid").val('');
			$("#txt_item_unit").val('');
			var desc 	= $('#ItemCodeList [value="' + itemCode + '"]').data('desc');
			var group_id = $('#ItemCodeList [value="' + itemCode + '"]').data('group_id');
			$('#txt_desc').val(desc);
			$("#txt_item_desc").val(desc);
			$("#txt_itemid").val(group_id);
			$.ajax({ 
			type: 'POST', 
			url: 'find_rate_calculation_sd.php', 
			data: { itemCode: itemCode }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){
					var TSRate 		 = data['TSRate'];
					var IGCARRate 	 = data['IGCARRate'];
					var MasterDesc 	 = data['MasterDesc'];
					var WoutCalcRate = data['WoutCalcRate'];
					var ItemUnit 	 = data['ItemUnit'];
					$("#txt_item_rate_sd").val(WoutCalcRate);
					$("#txt_item_unit").val(ItemUnit);
				}
			}
		});
		});
		txt_code
	});
</script>
