<?php
include "db_connect.php";
$modify=false;

function dt_format($ddmmyyyy)
{
	$dt=explode('/',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	
	return $yy .'/'. $mm .'/'.$dd;
}


function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'/'. $mm .'/'.$yy;
}


if (isset($_POST['btn_update_x']))
{
	$valid_from=dt_format($_POST['txt_from_date']);
	$valid_to=dt_format($_POST['txt_to_date']);
	
	$sql_update_level3="update item_master set 
							valid_from='" . $valid_from . "' ,
							valid_to='" . $valid_to . "'
							where item_id='" . $_POST['item_id_level3'] . "' ";
	//echo $sql_update_level3.'</br>';
	$rs_update_level3=mysqli_query($dbConn,$sql_update_level3,$conn);
	
	
	$row=$_POST['item_rows'];
	for($x=1;$x<=$row;$x++)
	{
		$sql_update="update item_master set 
						item_desc='" . $_POST['txt_desc'.$x] . "' ,
						unit='" . $_POST['cmb_unit'.$x] . "' ,
						price='" . $_POST['txt_price'.$x] . "' ,
						factor='" . $_POST['txt_factor'.$x] . "' ,
						ED='" . $_POST['txt_ed'.$x] . "' ,
						CESS='" . $_POST['txt_cess'.$x] . "' ,
						VAT='" . $_POST['txt_vat'.$x] . "' ,
						CST='" . $_POST['txt_cst'.$x] . "' ,
						packing='" . $_POST['txt_packing'.$x] . "' ,
						freight='" . $_POST['txt_freight'.$x] . "' ,
						insurance_charge='" . $_POST['txt_insurance_charge'.$x] . "' ,
						Wastage='" . $_POST['txt_wastage'.$x] . "' ,
						Cartage='" . $_POST['txt_cartage'.$x] . "' ,
						Route_Indicator='" . $_POST['txt_route_indicator'.$x] . "' ,
						valid_from='" . $valid_from . "' ,
						valid_to='" . $valid_to . "'
						where item_id='" . $_POST['txt_item_id'.$x] . "' ";
		//echo $sql_update.'</br>';
		$rs_update=mysqli_query($dbConn,$sql_update,$conn);
	}
	if($rs_update!="")
	{
	?>
		<script type="text/javascript" language="javascript">
			alert("Updated Successfully")
		</script>
	<?php
	}
}


if (isset($_POST['btn_view_x']))
{
	$sql_level1="select item_id from item_master where item_id='" . $_POST['cmb_level1'] . "'";
	$rs_level1=mysqli_query($dbConn,$sql_level1,$conn);
	//echo $sql_level1.'</br>';
	
	$sql_level2="select item_id from item_master where item_id='" . $_POST['cmb_level2'] . "'";
	$rs_level2=mysqli_query($dbConn,$sql_level2,$conn);
	//echo $sql_level2.'</br>';
	//echo @mysqli_result($rs_level2,0,'item_id').'</br>';
	
	$sql_level3="select * from item_master where item_id='" . $_POST['cmb_level3'] . "'";
	$rs_level3=mysqli_query($dbConn,$sql_level3,$conn);
	//echo $sql_level3.'</br>';
	
	$level3_desc=@mysqli_result($rs_level3,0,'item_desc');
	$valid_from=dt_display(@mysqli_result($rs_level3,0,'valid_from'));
	$valid_to=dt_display(@mysqli_result($rs_level3,0,'valid_to'));
	
	$modify=true;
}


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link rel="stylesheet" href="font.css" />
<link rel="stylesheet" href="CSS/igstyle1.css" />
</head>
</head>

<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
<script type="text/javascript" language="javascript">


function func_level2()
{
	document.getElementById("detailed_desc_1").style.display="none";
	document.getElementById("detailed_desc_2").style.display="none";
	document.form.txt_level3_detailed_desc.value='';
	document.form.txt_level3_desc.value='';

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
	document.getElementById("detailed_desc_1").style.display="none";
	document.getElementById("detailed_desc_2").style.display="none";
	document.form.txt_level3_detailed_desc.value='';
	document.form.txt_level3_desc.value='';
	
	//alert(document.form.cmb_level2.value)
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
	
	/*if(document.form.cmb_level2.value == '0507')
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
	}*/
	
	
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
	document.getElementById("detailed_desc_1").style.display="none";
	document.getElementById("detailed_desc_2").style.display="none";
	document.form.txt_level3_detailed_desc.value='';
	document.form.txt_level3_desc.value='';
	
	//alert(document.form.cmb_level2.value)
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

function func_level3_desc()
{
	var selIndex = document.form.cmb_level3.selectedIndex;
	var comboValue = document.form.cmb_level3.options[selIndex].text;
	document.form.txt_level3_desc.value=comboValue
	
	func_level3_detailed_desc()
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


function list_search(id,val)
 {	
     cnt=document.getElementById(id).length
	 for(x=0; x<cnt; x++ )
	 {
		 if( document.getElementById(id).options(x).value==val)
		 {
			 document.getElementById(id).options(x).selected=true
			 break;
		 }
	 }
} 


function show_unit()
{
	var cnt=document.form.item_rows.value;
	for( var x=1;x<=cnt;x++)
	{
		document.getElementById("cmb_unit"+x).value=document.getElementById("txt_unit").value
	}
}


function show_factor()
{
	var cnt=document.form.item_rows.value;
	for( var x=1;x<=cnt;x++)
	{
		if(document.getElementById("txt_factor_per").value!='')
			document.getElementById("txt_factor"+x).value=document.getElementById("txt_factor_per").value
			
		if(document.getElementById("txt_ed_per").value!='')
			document.getElementById("txt_ed"+x).value=document.getElementById("txt_ed_per").value
		
		if(document.getElementById("txt_cess_per").value!='')
			document.getElementById("txt_cess"+x).value=document.getElementById("txt_cess_per").value
		
		if(document.getElementById("txt_vat_per").value!='')
			document.getElementById("txt_vat"+x).value=document.getElementById("txt_vat_per").value
		
		if(document.getElementById("txt_cst_per").value!='')
			document.getElementById("txt_cst"+x).value=document.getElementById("txt_cst_per").value
		
		if(document.getElementById("txt_packing_per").value!='')
			document.getElementById("txt_packing"+x).value=document.getElementById("txt_packing_per").value
		
		if(document.getElementById("txt_freight_per").value!='')
			document.getElementById("txt_freight"+x).value=document.getElementById("txt_freight_per").value
		
		if(document.getElementById("txt_insurance_charge_per").value!='')
			document.getElementById("txt_insurance_charge"+x).value=document.getElementById("txt_insurance_charge_per").value
		
		var price=parseFloat(document.getElementById("txt_price"+x).value)
		var factor=parseFloat(document.getElementById("txt_factor"+x).value)
		var basic_price=price*factor
		document.getElementById("txt_basic_price"+x).value=basic_price
		
		var ed=parseFloat(document.getElementById("txt_ed"+x).value)
		var x_amt=(basic_price*ed)/100
		document.getElementById("txt_x_amt"+x).value=x_amt
		
		var cess=parseFloat(document.getElementById("txt_cess"+x).value)
		var y_amt=(x_amt*cess)/100
		document.getElementById("txt_y_amt"+x).value=y_amt
		
		var subtotal1=basic_price+x_amt+y_amt
		document.getElementById("txt_sub_total1"+x).value=subtotal1
		
		var vat=parseFloat(document.getElementById("txt_vat"+x).value)
		var vat_amt=(subtotal1*vat)/100
		document.getElementById("txt_vat_amt"+x).value=vat_amt
		
		var cst=parseFloat(document.getElementById("txt_cst"+x).value)
		var cst_amt=(subtotal1*cst)/100
		document.getElementById("txt_cst_amt"+x).value=cst_amt
		
		var amount=subtotal1+vat_amt+cst_amt
		document.getElementById("txt_amount"+x).value=amount
		
		var packing=parseFloat(document.getElementById("txt_packing"+x).value)
		var packing_amt=(amount*packing)/100
		document.getElementById("txt_packing_amt"+x).value=packing_amt
		
		var freight=parseFloat(document.getElementById("txt_freight"+x).value)
		var freight_amt=(amount*freight)/100
		document.getElementById("txt_freight_amt"+x).value=freight_amt
		
		var subtotal2=amount+packing_amt+freight_amt
		document.getElementById("txt_sub_total2"+x).value=subtotal2
		
		var insurance_charge=parseFloat(document.getElementById("txt_insurance_charge"+x).value)
		var insurance_charge_amt=(subtotal2*insurance_charge)/100
		document.getElementById("txt_insurance_charge_amt"+x).value=insurance_charge_amt
		
		var total=subtotal2+insurance_charge_amt
		document.getElementById("txt_total"+x).value=total
	}
}


function show_factor_single(x)
{
	var price=parseFloat(document.getElementById("txt_price"+x).value)
	var factor=parseFloat(document.getElementById("txt_factor"+x).value)
	var basic_price=price*factor
	document.getElementById("txt_basic_price"+x).value=basic_price
	
	var ed=parseFloat(document.getElementById("txt_ed"+x).value)
	var x_amt=(basic_price*ed)/100
	document.getElementById("txt_x_amt"+x).value=x_amt
	
	var cess=parseFloat(document.getElementById("txt_cess"+x).value)
	var y_amt=(x_amt*cess)/100
	document.getElementById("txt_y_amt"+x).value=y_amt
	
	var subtotal1=basic_price+x_amt+y_amt
	document.getElementById("txt_sub_total1"+x).value=subtotal1
	
	var vat=parseFloat(document.getElementById("txt_vat"+x).value)
	var vat_amt=(subtotal1*vat)/100
	document.getElementById("txt_vat_amt"+x).value=vat_amt
	
	var cst=parseFloat(document.getElementById("txt_cst"+x).value)
	var cst_amt=(subtotal1*cst)/100
	document.getElementById("txt_cst_amt"+x).value=cst_amt
	
	var amount=subtotal1+vat_amt+cst_amt
	document.getElementById("txt_amount"+x).value=amount
	
	var packing=parseFloat(document.getElementById("txt_packing"+x).value)
	var packing_amt=(amount*packing)/100
	document.getElementById("txt_packing_amt"+x).value=packing_amt
	
	var freight=parseFloat(document.getElementById("txt_freight"+x).value)
	var freight_amt=(amount*freight)/100
	document.getElementById("txt_freight_amt"+x).value=freight_amt
	
	var subtotal2=amount+packing_amt+freight_amt
	document.getElementById("txt_sub_total2"+x).value=subtotal2
	
	var insurance_charge=parseFloat(document.getElementById("txt_insurance_charge"+x).value)
	var insurance_charge_amt=(subtotal2*insurance_charge)/100
	document.getElementById("txt_insurance_charge_amt"+x).value=insurance_charge_amt
	
	var total=subtotal2+insurance_charge_amt
	document.getElementById("txt_total"+x).value=total
}

function show_wastage()
{
	var cnt=document.form.item_rows.value;
	for( var x=1;x<=cnt;x++)
	{
		document.getElementById("txt_wastage"+x).value=document.getElementById("txt_wastage_per").value
	}
}

function show_cartage()
{
	var cnt=document.form.item_rows.value;
	for( var x=1;x<=cnt;x++)
	{
		document.getElementById("txt_cartage"+x).value=document.getElementById("txt_cartage_per").value
	}
}

function show_route_indicator()
{
	var cnt=document.form.item_rows.value;
	for( var x=1;x<=cnt;x++)
	{
		document.getElementById("txt_route_indicator"+x).value=document.getElementById("txt_route_indicator_per").value
	}
}
</script>


<body bgcolor="c5d1dc">
<form name="form" method="post">

<?php
if($modify==false)	
{
	?>
	<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="heading">
			<td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
			<td width="839" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Item Rate Master</td>
			<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
		</tr>
	</table>
	
	<table width="900" border="0" align="center" class="color1" cellpadding="0" cellspacing="0">
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td width="70" class="labelbold">&nbsp;&nbsp;Level 1 haiii</td>
			<td width="830">
				<?php
				$sql_level1="select * from item_master where item_id in ('03','05','07') order by item_id";
				$rs_level1=mysqli_query($dbConn,$sql_level1,$conn);
				?>
				<select class="text" style="width:800px;height:21px;" name="cmb_level1" ID="cmb_level1" onBlur="func_level2()">
					<option value="Select">Select</option>
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
		
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<td width="70" class="labelbold">&nbsp;&nbsp;Level 2</td>
			<td width="830">
				<select class="text" style="width:800px;height:21px;" name="cmb_level2" ID="cmb_level2" onBlur="func_make()">
					<option value="Select">Select</option>
				</select>
				<span id="cable_items" style="display:none"><img src="Buttons/search.gif" align="absmiddle" onClick="javascript:window.open('cable_items.php?item_id=0507','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=500,WIDTH=600,LEFT=200,TOP=200')" /></span>
				<span id="cable_glands_items" style="display:none"><img src="Buttons/search.gif" align="absmiddle" onClick="javascript:window.open('cable_glands_items.php?item_id=0509','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=500,WIDTH=600,LEFT=200,TOP=200')" /></span>
				<span id="cable_lugs_items" style="display:none"><img src="Buttons/search.gif" align="absmiddle" onClick="javascript:window.open('cable_lugs_items.php?item_id=0510','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=500,WIDTH=600,LEFT=200,TOP=200')" /></span>
				<span id="contractor_items" style="display:none"><img src="Buttons/search.gif" align="absmiddle" onClick="javascript:window.open('contractor_items.php?item_id=0513','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=500,WIDTH=600,LEFT=200,TOP=200')" /></span>
				<span id="joints_items" style="display:none"><img src="Buttons/search.gif" align="absmiddle" onClick="javascript:window.open('joints_items.php?item_id=0514','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=500,WIDTH=600,LEFT=200,TOP=200')" /></span>
			</td>
		</tr>
		
		<tr><td>&nbsp;</td></tr>
		
		<tr>
			<td width="70" class="labelbold">&nbsp;&nbsp;Make</td>
			<td width="830">
				<select class="text" style="width:250px;height:21px;" name="cmb_make" ID="cmb_make" onBlur="func_level3()">
					<option value="Select">Select</option>
				</select>
			</td>
		</tr>
		
		<tr><td>&nbsp;</td></tr>
		
		<tr>
			<td width="70" class="labelbold">&nbsp;&nbsp;Level 3</td>
			<td width="830">
				<select class="text" style="width:800px;height:21px;" name="cmb_level3" ID="cmb_level3" onBlur="func_level3_desc()">
					<option value="Select">Select</option>
				</select>
			</td>
		</tr>
		
		<tr><td>&nbsp;</td></tr>
		
		<tr>
			<td width="70" class="labelbold" nowrap="nowrap" valign="top">&nbsp;&nbsp;Level 3<br />&nbsp;&nbsp;Description</td>
			<td width="830"><textarea name="txt_level3_desc" id="txt_level3_desc" cols="75" rows="4" class="text" readonly="readonly"></textarea></td>
		</tr>
		
		<tr><td>&nbsp;</td></tr>
		
		<tr style="display:none" id="detailed_desc_1">
			<td width="70" class="labelbold" nowrap="nowrap" valign="top">&nbsp;&nbsp;Detailed<br />&nbsp;&nbsp;Description</td>
			<td width="830"><textarea name="txt_level3_detailed_desc" id="txt_level3_detailed_desc" cols="75" rows="4" class="text" readonly="readonly"></textarea></td>
		</tr>
		
		<tr style="display:none" id="detailed_desc_2"><td>&nbsp;</td></tr>
		
		<tr class="labelcenter">
			<td colspan="5">
				<input type="image" name="btn_view" id="btn_view" value="View" src="Buttons/View_Normal.png" onMouseOver="this.src='Buttons/View_Over.png'" onMouseOut="this.src='Buttons/View_Normal.png'" />
			</td>
		</tr>
		
		<tr><td>&nbsp;</td></tr>

	</table>
	<?php
}

else
{
	?>
	<!--<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="heading">
			<td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
			<td width="964" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="center">Item Update</td>
			<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
		</tr>
	</table>-->
	
	<table width="1000" border="1" align="center" cellpadding="0" cellspacing="0" class="color2">
		<tr class="labelboldcenter" height="25"><td colspan="26" background="Title bar/Titlebar_Centre_Piece.jpg">Item Update for <?php echo $level3_desc; ?></td></tr>
		
		<tr height="25">
			<td class="labelbold" colspan="26">
				&nbsp;&nbsp;&nbsp;&nbsp;Valid From
				&nbsp;&nbsp;&nbsp;<input type="text" name="txt_from_date" id="txt_from_date" value="<?php echo $valid_from; ?>" class="text" size="11" maxlength="20"/>
				<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onClick="displayDatePicker('txt_from_date', false, 'dmy', '/');" />
				&nbsp;&nbsp;&nbsp;&nbsp;Valid Upto
				&nbsp;&nbsp;&nbsp;<input type="text" name="txt_to_date" id="txt_to_date" value="<?php echo $valid_to; ?>" class="text" size="11" maxlength="20"/>
				<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onClick="displayDatePicker('txt_to_date', false, 'dmy', '/');" />
			</td>
		</tr>
		
		<tr class="labelboldcenter" height="25">
			<td>Name</td>
			<!--<td>Make</td>-->
			<td class="labelcenter">
				Unit<br />
				<select name="txt_unit" id="txt_unit" class="text" onBlur="show_unit()">
					<option value="Select">Select</option>
							<option value="Mtr">Mtr</option>
							<option value="Mtrs">Mtrs</option>
							<option value="CU.Mtr">Cu.Mtr</option>
							<option value="No.">No.</option>
							<option value="Nos">Nos</option>
							<option value="Set">Set</option>
							<option value="Sets">Sets</option>
							<option value="Kg">Kg</option>														
							<option value="gm">gm</option>
							<option value="mm">mm</option>
							<option value="Ltr">Ltr</option>
							<option value="Each">Each</option>
							<option value="Sqmtr">Sqmtr</option>
							<option value='Bag'>Bag</option>						
				</select>
				
				
			</td>
			<td>Price</td>
			<td>Factor<br /><input type="text" name="txt_factor_per" id="txt_factor_per" value="" size="2" onBlur="show_factor()" class="labelcenter" /></td>
			<td>Basic<br />Price</td>
			<td>ED<br /><input type="text" name="txt_ed_per" id="txt_ed_per" value="" size="2" onBlur="show_factor()" class="labelcenter" /></td>
			<td>X</td>
			<td>CESS<br /><input type="text" name="txt_cess_per" id="txt_cess_per" value="" size="2" onBlur="show_factor()" class="labelcenter" /></td>
			<td>Y</td>
			<td>Subtotal 1</td>
			<td>VAT<br /><input type="text" name="txt_vat_per" id="txt_vat_per" value="" size="2" onBlur="show_factor()" class="labelcenter" /></td>
			<td>VAT Amt</td>
			<td>CST<br /><input type="text" name="txt_cst_per" id="txt_cst_per" value="" size="2" onBlur="show_factor()" class="labelcenter" /></td>
			<td>CST Amt</td>
			<td>Amount</td>
			<td>Packing<br /><input type="text" name="txt_packing_per" id="txt_packing_per" value="" size="2" onBlur="show_factor()" class="labelcenter" /></td>
			<td>Packing<br />Amt</td>
			<td>Freight<br /><input type="text" name="txt_freight_per" id="txt_freight_per" value="" size="2" onBlur="show_factor()" class="labelcenter" /></td>
			<td>Freight<br />Amt</td>
			<td>Subtotal 2</td>
			<td>Insurance<br />Charge<br /><input type="text" name="txt_insurance_charge_per" id="txt_insurance_charge_per" value="" size="2" onBlur="show_factor()" class="labelcenter" /></td>
			<td>Insurance<br />Charge Amt</td>
			<td>Total<br />Supply Cost</td>
			<td>Wastage<br /><input type="text" name="txt_wastage_per" id="txt_wastage_per" value="" size="2" onBlur="show_wastage()" class="labelcenter" /></td>
			<td>Cartage<br /><input type="text" name="txt_cartage_per" id="txt_cartage_per" value="" size="2" onBlur="show_cartage()" class="labelcenter" /></td>
			<td>Route<br />Indicator<br /><input type="text" name="txt_route_indicator_per" id="txt_route_indicator_per" value="" size="2" onBlur="show_route_indicator()" class="labelcenter" /></td>
		</tr>
		
		<?php
		$row=1;
		
		$sql_level3="select * from item_master where item_id like '" . $_POST['cmb_level3'] . "%'
						and char_length(item_id) = '9' order by item_id";
		$rs_level3=mysqli_query($dbConn,$sql_level3,$conn);
		
		$no_rows=mysqli_num_rows($rs_level3);
		
		if(@mysqli_result($rs_level3,0,'item_id')!="")
		{
			$rs_level3=mysqli_query($dbConn,$sql_level3,$conn);
			while($rows=mysqli_fetch_assoc($rs_level3))
			{		 
				$basic_price=$rows['price']*$rows['factor'];
				$x=($basic_price*$rows['ED'])/100;
				$y=($x*$rows['CESS'])/100;
				$sub_total1=$basic_price+$x+$y;
				$vat_amt=($sub_total1*$rows['VAT'])/100;
				$cst_amt=($sub_total1*$rows['CST'])/100;
				$amount=$sub_total1+$vat_amt+$cst_amt;
				$packing_amt=($amount*$rows['packing'])/100;
				$freight_amt=($amount*$rows['freight'])/100;
				$sub_total2=$amount+$packing_amt+$freight_amt;
				$insurance_charge_amt=($sub_total2*$rows['insurance_charge'])/100;
				$total=$sub_total2+$insurance_charge_amt;
				
				echo "<tr style='overflow:auto'>";
				echo "<td class='labelcenter'><input type='text' name='txt_desc" . $row . "' id='txt_desc" . $row . "' value='" . $rows['item_desc'] . "' class='text' /></td>";
				//echo "<td class='labelcenter'><input type='text' name='txt_make" . $row . "' id='txt_make" . $row . "' value='" . $rows['make'] . "' size='15' class='text' /></td>";
				
				echo "<td nowrap>&nbsp;
				
				<select class='text' name='cmb_unit" . $row . "' id='cmb_unit" . $row . "'>
							<option value='Select'>Select</option>
							<option value='Mtr'>Mtr</option>
							<option value='Mtrs'>Mtrs</option>
							<option value='CU.Mtr'>Cu.Mtr</option>
							<option value='No.'>No.</option>
							<option value='Nos'>Nos</option>
							<option value='Set'>Set</option>
							<option value='Sets'>Sets</option>
							<option value='Kg'>Kg</option>							
							<option value='gm'>gm</option>
							<option value='mm'>mm</option>
							<option value='Ltr'>Ltr</option>
							<option value='Each'>Each</option>
							<option value='Sqmtr'>Sqmtr</option>
							<option value='Bag'>Bag</option>							
						</select>
						<script language=javascript type=text/javascript>
							list_search('cmb_unit" . $row . "', '" . $rows['unit'] . "')
						</script>
									
				&nbsp;</td>";
				
				echo "<td class='labelcenter'><input type='text' name='txt_price" . $row . "' id='txt_price" . $row . "' value='" . $rows['price'] . "' size='5' class='labelfieldright' onblur='show_factor_single($row)' /></td>";
				echo "<td class='labelcenter'><input type='text' name='txt_factor" . $row . "' id='txt_factor" . $row . "' value='" . $rows['factor'] . "' size='3' class='labelcenter' onblur='show_factor_single($row)' /></td>";
				
				echo "<td class='labelcenter'><input type='text' name='txt_basic_price" . $row . "' id='txt_basic_price" . $row . "' value='" . $basic_price . "' size='7' class='maroon_right' readonly /></td>";
				//echo "<td><span id='txt_basic_price'>" . $basic_price . "</span></td>";
				
				echo "<td class='labelcenter'><input type='text' name='txt_ed" . $row . "' id='txt_ed" . $row . "' value='" . $rows['ED'] . "' size='3' class='labelcenter' onblur='show_factor_single($row)' /></td>";
				echo "<td class='labelcenter'><input type='text' name='txt_x_amt" . $row . "' id='txt_x_amt" . $row . "' value='" . $x . "' size='7' class='maroon_right' readonly /></td>";
			
				echo "<td class='labelcenter'><input type='text' name='txt_cess" . $row . "' id='txt_cess" . $row . "' value='" . $rows['CESS'] . "' size='3' class='labelcenter' onblur='show_factor_single($row)' /></td>";
				echo "<td class='labelcenter'><input type='text' name='txt_y_amt" . $row . "' id='txt_y_amt" . $row . "' value='" . $y . "' size='7' class='maroon_right' readonly /></td>";
				
				echo "<td class='labelcenter'><input type='text' name='txt_sub_total1" . $row . "' id='txt_sub_total1" . $row . "' value='" . $sub_total1 . "' size='7' class='maroon_right' readonly /></td>";

				echo "<td class='labelcenter'><input type='text' name='txt_vat" . $row . "' id='txt_vat" . $row . "' value='" . $rows['VAT'] . "' size='3' class='labelcenter' onblur='show_factor_single($row)' /></td>";
				echo "<td class='labelcenter'><input type='text' name='txt_vat_amt" . $row . "' id='txt_vat_amt" . $row . "' value='" . $vat_amt . "' size='7' class='maroon_right' readonly /></td>";
				
				echo "<td class='labelcenter'><input type='text' name='txt_cst" . $row . "' id='txt_cst" . $row . "' value='" . $rows['CST'] . "' size='3' class='labelcenter' onblur='show_factor_single($row)' /></td>";
				echo "<td class='labelcenter'><input type='text' name='txt_cst_amt" . $row . "' id='txt_cst_amt" . $row . "' value='" . $cst_amt . "' size='7' class='maroon_right' readonly /></td>";
				
				echo "<td class='labelcenter'><input type='text' name='txt_amount" . $row . "' id='txt_amount" . $row . "' value='" . $amount . "' size='7' class='maroon_right' readonly /></td>";

				echo "<td class='labelcenter'><input type='text' name='txt_packing" . $row . "' id='txt_packing" . $row . "' value='" . $rows['packing'] . "' size='3' class='labelcenter' onblur='show_factor_single($row)' /></td>";
				echo "<td class='labelcenter'><input type='text' name='txt_packing_amt" . $row . "' id='txt_packing_amt" . $row . "' value='" . $packing_amt . "' size='7' class='maroon_right' readonly /></td>";
				
				echo "<td class='labelcenter'><input type='text' name='txt_freight" . $row . "' id='txt_freight" . $row . "' value='" . $rows['freight'] . "' size='3' class='labelcenter' onblur='show_factor_single($row)' /></td>";
				echo "<td class='labelcenter'><input type='text' name='txt_freight_amt" . $row . "' id='txt_freight_amt" . $row . "' value='" . $freight_amt . "' size='7' class='maroon_right' readonly /></td>";
				
				echo "<td class='labelcenter'><input type='text' name='txt_sub_total2" . $row . "' id='txt_sub_total2" . $row . "' value='" . $sub_total2 . "' size='7' class='maroon_right' readonly /></td>";

				echo "<td class='labelcenter'><input type='text' name='txt_insurance_charge" . $row . "' id='txt_insurance_charge" . $row . "' value='" . $rows['insurance_charge'] . "' size='3' class='labelcenter' onblur='show_factor_single($row)' /></td>";
				echo "<td class='labelcenter'><input type='text' name='txt_insurance_charge_amt" . $row . "' id='txt_insurance_charge_amt" . $row . "' value='" . $insurance_charge_amt . "' size='7' class='maroon_right' readonly /></td>";
				
				echo "<td class='labelcenter'><input type='text' name='txt_total" . $row . "' id='txt_total" . $row . "' value='" . $total . "' size='7' class='maroon_right' readonly /></td>";

				echo "<td class='labelcenter'><input type='text' name='txt_wastage" . $row . "' id='txt_wastage" . $row . "' value='" . $rows['Wastage'] . "' size='3' class='text' /></td>";
				echo "<td class='labelcenter'><input type='text' name='txt_cartage" . $row . "' id='txt_cartage" . $row . "' value='" . $rows['Cartage'] . "' size='3' class='text' /></td>";
				echo "<td class='labelcenter'><input type='text' name='txt_route_indicator" . $row . "' id='txt_route_indicator" . $row . "' value='" . $rows['Route_Indicator'] . "' size='3' class='text' /></td>";
				echo "<input type='hidden' name='txt_item_id" . $row . "'  id='txt_item_id" . $row ."' value='" . $rows['item_id'] . "' />";
				$row++;
				echo "</tr>";
			}
		}
		?>
		<input type="hidden" name="item_rows" id="item_rows" value="<?php echo ($row-1); ?>"  />
		<input type="hidden" name="item_id_level3" id="item_id_level3" value="<?php echo $_POST['cmb_level3']; ?>"  />
	</table>
	
	<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr><td colspan="5">&nbsp;</td></tr>
		<tr>
			<td colspan="5" class="labelcenter">
				<input type="image" name="btn_back" id="btn_back" value="Back" src="Buttons/Back_Normal.png"  onmouseover="this.src='Buttons/Back_Over.png'" onMouseOut="this.src='Buttons/Back_Normal.png'" />&nbsp;&nbsp;
				<input type="image" name="btn_update" id="btn_update" value="Update" src="Buttons/Update_Normal.png"  onmouseover="this.src='Buttons/Update_Over.png'" onMouseOut="this.src='Buttons/Update_Normal.png'" />
			</td>
		</tr>
		<tr><td colspan="5">&nbsp;</td></tr>
	</table>
	<?php
}
?>
</form>
</body>
</html>
