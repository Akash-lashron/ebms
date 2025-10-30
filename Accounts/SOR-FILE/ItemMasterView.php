<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
?>

<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<script type="text/javascript" language="javascript">

function show_type()
{
	if(document.form.type[0].checked==true)
	{
		document.getElementById("single").style.display="";
		document.getElementById("multiple").style.display="none";
	}
	if(document.form.type[1].checked==true)
	{
		document.getElementById("single").style.display="none";
		document.getElementById("multiple").style.display="";
	}
}


function show_single_level2()
{
	//document.form.cmb_single_level1.value='Select';	
	document.form.cmb_single_level2.value='Select';	
	document.form.cmb_make.value='Select';	
	document.form.cmb_single_level3.value='Select';	
	
	document.getElementById("single_level2").style.display="none";
	document.getElementById("make").style.display="none";
	document.getElementById("single_level3").style.display="none";
	
	document.getElementById("cmb_single_level2").disabled=false;
	document.getElementById("cmb_make").disabled=false;
	document.getElementById("txt_make_new").style.display="none";
	document.getElementById("cmb_single_level3").disabled=false;
	
	
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
	
	//alert("find_level2.php?item_id="+document.form.cmb_single_level1.value)
	strURL="find_level2.php?item_id="+document.form.cmb_single_level1.value
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	//alert(strURL)
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			
			if(data=="")
			{
				alert("No Records Found");
			}
			else
			{
				document.form.cmb_single_level2.value='Select';	
				document.form.txt_single_level2.value='';	
				document.getElementById("single_level2").style.display="none";
				//document.getElementById("txt_single_level2").style.display="none";
				
				document.form.cmb_make.value='Select';	
				document.form.txt_make_new.value='';	
				document.getElementById("txt_make_new").style.display="none";
				
				document.form.cmb_make_select.value='Select';	
				document.form.txt_make_select_new.value='';	
				document.getElementById("make").style.display="none";
				
				document.form.cmb_single_level3.value='Select';	
				document.form.txt_single_level3.value='';	
				document.getElementById("single_level3").style.display="none";
				//document.getElementById("txt_single_level3").style.display="none";
				
				document.form.txt_single_level4.value=''
				
				var name=data.split("*");

				document.form.cmb_single_level2.length=0
				
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_single_level2.options.add(optn)
				
				var optn=document.createElement("option")
				optn.value="New";
				optn.text="New";
				document.form.cmb_single_level2.options.add(optn)
				
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
					document.form.cmb_single_level2.options.add(optn)
				}
			}
		}
    }
	xmlHttp.send(strURL);	
}



function func_make()
{
	if(document.form.cmb_single_level2.value=='New')
	{
		document.getElementById("single_level2").style.display="";
		
		document.getElementById("cmb_make").value='Select';
		document.getElementById("cmb_make").disabled=true;
		document.getElementById("make").style.display="";
	
		document.getElementById("single_level3").style.display="";
		document.getElementById("cmb_single_level3").value='Select';
		document.getElementById("cmb_single_level3").disabled=true;
	}
	else
	{
		document.getElementById("cmb_make").value='Select';
		document.getElementById("cmb_make").disabled=false;
		document.getElementById("make").style.display="none";
		document.getElementById("txt_make_new").style.display="none";
		
		document.getElementById("single_level2").style.display="none";
		document.getElementById("single_level3").style.display="none";
		document.getElementById("cmb_single_level3").disabled=false;

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
		strURL="find_level3_make.php?item_id="+document.form.cmb_single_level2.value
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
					optn.value="New";
					optn.text="New";
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
					optn.value="New";
					optn.text="New";
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
}



function func_make_select_new()
{
	if(document.form.cmb_make_select.value=='New')
	{
		document.getElementById("txt_make_select_new").style.display="";
	}
	else
	{
		document.getElementById("txt_make_select_new").style.display="none";
	}
}



function show_single_level3()
{
	if(document.form.cmb_make.value=='New')
	{
		document.getElementById("txt_make_new").style.display="";
		
		document.getElementById("single_level3").style.display="";
		document.getElementById("cmb_single_level3").value='Select';
		document.getElementById("cmb_single_level3").disabled=true;
	}
	if(document.form.cmb_make.value!='New')
	{
		document.getElementById("txt_make_new").style.display="none";
		
		document.getElementById("single_level3").style.display="none";
		document.getElementById("cmb_single_level3").disabled=false;

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
		alert("find_level3.php?item_id="+document.form.cmb_single_level2.value)
		strURL="find_level3.php?item_id="+document.form.cmb_single_level2.value+"&make="+document.form.cmb_make.value
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				document.write(data)
				
				if(data=="")
				{
					alert("No Records Found");
					document.form.cmb_single_level3.value='Select';	
				}
				else
				{
					//alert(data)
					var name=data.split("*");
					//alert(name)
					document.form.cmb_single_level3.length=0
					
					var optn=document.createElement("option")
					optn.value="Select";
					optn.text="Select";
					document.form.cmb_single_level3.options.add(optn)
	
					var optn=document.createElement("option")
					optn.value="New";
					optn.text="New";
					document.form.cmb_single_level3.options.add(optn)
					
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
						document.form.cmb_single_level3.options.add(optn)
					}
				}
			}
		}
		xmlHttp.send(strURL);	
	}
}


function show_single_new_level3()
{
	if(document.form.cmb_single_level3.value=='New')
	{
		document.getElementById("single_level3").style.display="";
	}
	if(document.form.cmb_single_level3.value!='New')
	{
		document.getElementById("single_level3").style.display="none";
	}
}



function show_multiple_level2()
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

	strURL="find_level2.php?item_id="+document.form.cmb_multiple_level1.value
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
				document.form.cmb_multiple_level2.value='Select';	
				document.form.cmb_multiple_level3.value='Select';	
			}
			else
			{
				//alert(data)
				var name=data.split("*");
				//alert(name)
				document.form.cmb_multiple_level2.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_multiple_level2.options.add(optn)
				
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
					document.form.cmb_multiple_level2.options.add(optn)
				}
			}
		}
    }
	xmlHttp.send(strURL);	
}


function show_multiple_make()
{

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
	strURL="find_level3_make.php?item_id="+document.form.cmb_multiple_level2.value
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
				document.form.cmb_multiple_make.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_multiple_make.options.add(optn)
				
				var optn=document.createElement("option")
				optn.value="None";
				optn.text="None";
				document.form.cmb_multiple_make.options.add(optn)
			}
			else
			{
				//alert(data)
				var name=data.split("*");
				//alert(name)
				document.form.cmb_multiple_make.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_multiple_make.options.add(optn)
				
				var optn=document.createElement("option")
				optn.value="None";
				optn.text="None";
				document.form.cmb_multiple_make.options.add(optn)
				
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
					document.form.cmb_multiple_make.options.add(optn)
				}
			}
		}
	}
	xmlHttp.send(strURL);	
}



function show_multiple_level3()
{
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
	//alert("find_level3.php?item_id="+document.form.cmb_multiple_level2.value)
	strURL="find_level3.php?item_id="+document.form.cmb_multiple_level2.value+"&make="+document.form.cmb_multiple_make.value
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
				document.form.cmb_multiple_level3.value='Select';	
			}
			else
			{
				//alert(data)
				var name=data.split("*");
				//alert(name)
				document.form.cmb_multiple_level3.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_multiple_level3.options.add(optn)
				
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
					document.form.cmb_multiple_level3.options.add(optn)
				}
			}
		}
    }
	xmlHttp.send(strURL);	
}

</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<table width="925" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr class="heading">
									<td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
									<td width="864" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Item Master</td>
									<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
								</tr>
							</table>
							<table width="925" border="1" align="center" cellpadding="0" cellspacing="0" class="color1">
								<tr class="labelbold">
									<td align="center" valign="middle">&nbsp;S.No.</td>
									<!--<td align="center" valign="middle">&nbsp;Item Id&nbsp;</td>-->
									<td align="center" valign="middle" nowrap="nowrap">&nbsp;Item Code&nbsp;</td>
									<td valign="middle">&nbsp;Item Description</td>
									<td align="center" valign="middle">Unit</td>
									<td align="right" valign="middle" height="30">Rate</td>
									<td>&nbsp;</td>
								</tr>
							<?php
							$sno = 1;
							$SelectQuery = "select * from item_master where item_id like '07%' and char_length( item_id ) > 4 order by item_code";
							$SelectSql = mysqli_query($dbConn,$SelectQuery);
							if($SelectSql == true){
								if(mysqli_num_rows($SelectSql)>0){
									while($ItemMasList = mysqli_fetch_object($SelectSql)){
							?>
									<tr class="labelbold" valign="middle">
										<td align="center" valign="middle"><?php echo $sno; ?></td>
										<!--<td align="center" valign="middle"><?php echo $ItemMasList->item_id; ?> </td>-->
										<td align="center" valign="middle"><?php echo $ItemMasList->item_code; ?></td>
										<td align="justify" valign="middle">&nbsp;<?php echo $ItemMasList->item_desc; ?></td>
										<td align="center" valign="middle"><?php echo $ItemMasList->unit; ?></td>
										<td align="right" valign="middle"><?php echo $ItemMasList->price; ?></td>
										<td align="center" width="100" height="28" valign="middle">
											<a href="item_master.php?id=<?php echo $ItemMasList->item_id; ?>" class="btn-primary" style="padding:3px 15px; border-radius:5px">Edit</a>
										</td>
									</tr>
							<?php 
										$sno++;
									}
								}
							}
							?>
								<tr><td colspan="2">&nbsp;</td></tr>
							</table>
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
$(document).ready(function(){
	$('.dropdown-submenu a.test').on("click", function(e){
    	$(this).next('ul').toggle();
    	e.stopPropagation();
    	e.preventDefault();
  	});
  	$('#btn_view_single').click(function(event){ 
  		$(location).attr("href","ItemMasterView.php");
		event.preventDefault();
		return false;
  	});
});
</script>
