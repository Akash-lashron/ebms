<?php
 @ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
$msg = '';
?>
<?php include "Header.html"; ?>
   <script type="text/javascript" language="javascript">
   	function goBack()
	{
	   	url = "designationlist.php";
		window.location.replace(url);
	}
	function func_desc()
	{	
		if(alltrim(document.form.dummyname.value)!=alltrim(document.form.designame.value))
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
			strURL="check_desc.php?desc="+alltrim(document.form.designame.value);
			xmlHttp.open('POST', strURL, true);
			xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xmlHttp.onreadystatechange = function()
			{
				if (xmlHttp.readyState == 4)
				{
					data=xmlHttp.responseText
					if(data=='Y')
					{	
						alert("Designation Name Already Exist");
						document.form.designame.value='';
						return false;
					}
				}
			}
			xmlHttp.send(strURL);
		}
	}
  	
  	var index = 1;
    function insertRow(table_id)
	{
        var table=document.getElementById(table_id);
        var row=table.insertRow(table.rows.length);
        	row.id = "rowid"+index;
			row.style.align = "center";
					
        var cell1=row.insertCell(0);
            cell1.innerHTML = "";
				
		var cell2=row.insertCell(1);
            cell2.innerHTML = "";
				
		var cell3=row.insertCell(2);
			cell3.setAttribute('class', "dynamicrowcell");
					
        var txt_box=document.createElement("input");
			txt_box.name = "txt_menu";
            txt_box.id = "txt_menu"+index;
			txt_box.setAttribute('class', "textboxdisplay dynamictextbox"); 
            cell3.appendChild(txt_box);
				
		var cell4=row.insertCell(3);
			cell4.style.width = 85+"px";
			cell4.style.textAlign = "center";
        var delbtn=document.createElement("input");
        	delbtn.type = "button";
        	delbtn.value = " DELETE ";
        	delbtn.id = "btn_delete"+index;
			delbtn.name = "btn_delete";
			delbtn.setAttribute('class', "delbtnstyle");
			delbtn.style.width = 80+"px";
        	delbtn.onclick = function () {
                        	  myDeleteFunction(row.id)
                    		}
        	cell4.appendChild(delbtn);
					
		var cell5=row.insertCell(4);
			cell5.style.width = 85+"px";
            cell5.innerHTML = "";
					
		var cell6=row.insertCell(5);
			cell6.style.width = 85+"px";
            cell6.innerHTML = "";
                
       	index++;

    }
function myDeleteFunction(id) 
{
   var row = document.getElementById(id);
   row.parentNode.removeChild(row);
}
function DispalyMenuTypeSection(obj)
{
	var elemt = document.getElementById("cmb_menu_type");
	var menutype_text  = elemt.options[elemt.selectedIndex].text;
	var menutype_value = document.getElementById("cmb_menu_type").value;
	//alert(menutype_text)
	//alert(menutype_value)
	if(menutype_value != 0)
	{
		if(menutype_value == 1)
		{
			document.getElementById("mainmenusection").className = "menutypesection";
			document.getElementById("sublevelonesection").className = "hide";
			document.getElementById("subleveltwosection").className = "hide";
			document.getElementById("save_btn_section").className = "";
		}
		else if(menutype_value == 2)
		{
			document.getElementById("mainmenusection").className = "hide";
			document.getElementById("sublevelonesection").className = "menutypesection";
			document.getElementById("subleveltwosection").className = "hide";
			document.getElementById("save_btn_section").className = "";
		}
		else
		{
			document.getElementById("mainmenusection").className = "hide";
			document.getElementById("sublevelonesection").className = "hide";
			document.getElementById("subleveltwosection").className = "menutypesection";
			document.getElementById("save_btn_section").className = "";
		}
	}
	else
	{
			document.getElementById("mainmenusection").className = "hide";
			document.getElementById("sublevelonesection").className = "hide";
			document.getElementById("subleveltwosection").className = "hide";
			document.getElementById("save_btn_section").className = "hide";
	}
}
function addFuntion(section_id)
{
	var sectid = section_id;
	if(sectid == "mainmenusection")
	{
		if(document.getElementById('txt_mainmenu').disabled == true)
		{
			document.getElementById('txt_mainmenu').disabled = false;
			document.getElementById('txt_mainmenu').className = "textboxdisplay dynamictextbox";
			document.getElementById('cmb_mainmenu_optn').className = "hide";
			document.getElementById('mainmenu_edit').className = "hide";
		}
		else
		{
			insertRow('mainmenutable');
		}
	}
	else if(sectid == "sublevelonesection")
	{
		if(document.getElementById('txt_sublevel_1').disabled == true)
		{
			document.getElementById('txt_sublevel_1').disabled = false;
			document.getElementById('txt_sublevel_1').className = "textboxdisplay dynamictextbox";
			document.getElementById('cmb_sublevel_1_optn').className = "hide";
			document.getElementById('sublevel_one_edit').className = "hide";
		}
		else
		{
			insertRow('sublevelonetable');
		}
	}
	else if(sectid == "subleveltwosection")
	{
		if(document.getElementById('txt_sublevel_2').disabled == true)
		{
			document.getElementById('txt_sublevel_2').disabled = false;
			document.getElementById('txt_sublevel_2').className = "textboxdisplay dynamictextbox";
			document.getElementById('cmb_sublevel_2_optn').className = "hide";
		}
		else
		{
			insertRow('subleveltwotable');
		}
	}
	else
	{
		alert("nothing");
	}
}
function editFunction(section_id)
{
	var sectid = section_id;
	if(sectid == "mainmenusection")
	{
		var elemt = document.getElementById("cmb_mainmenu_optn");
		var mainmenu_text  = elemt.options[elemt.selectedIndex].text;
		var mainmenu_value = document.getElementById("cmb_mainmenu_optn").value;
		if(mainmenu_value == 0)
		{
			alert("Please select Main Menu and Click Edit Button")
			document.getElementById('txt_mainmenu').className = "hide";
			document.getElementById('cmb_mainmenu_optn').className = "textboxdisplay dynamictextbox";
			document.getElementById('txt_mainmenu').disabled == true
			document.getElementById('txt_mainmenu_edit').value = "";
			
		}
		else
		{
			document.getElementById('mainmenu_edit').className = "";
			document.getElementById('cmb_mainmenu_optn').className = "textboxdisplay dynamictextbox";
			document.getElementById('txt_mainmenu').className = "hide";
			document.getElementById('txt_mainmenu_edit').value = mainmenu_text;
		}
	}
	if(sectid == "sublevelonesection")
	{
		var elemt = document.getElementById("cmb_sublevel_1_optn");
		var mainmenu_text  = elemt.options[elemt.selectedIndex].text;
		var mainmenu_value = document.getElementById("cmb_sublevel_1_optn").value;
		if(mainmenu_value == 0)
		{
			alert("Please select Main Menu and Click Edit Button")
			document.getElementById('txt_sublevel_1').className = "hide";
			document.getElementById('cmb_sublevel_1_optn').className = "textboxdisplay dynamictextbox";
			document.getElementById('txt_sublevel_1').disabled == true
			document.getElementById('txt_sublevel1_edit').value = "";
			
		}
		else
		{
			document.getElementById('sublevel_one_edit').className = "";
			document.getElementById('txt_sublevel1_edit').value = mainmenu_text;
			document.getElementById('cmb_sublevel_1_optn').className = "textboxdisplay dynamictextbox";
			document.getElementById('txt_sublevel_1').className = "hide";
		}
	}
}
function changeMenu(section_id)
{
	var sectid = section_id;
	if(sectid == "mainmenusection")
	{
		document.getElementById('txt_mainmenu_edit').value = "";
		var elemt = document.getElementById("cmb_mainmenu_optn");
		var mainmenu_text  = elemt.options[elemt.selectedIndex].text;
		var mainmenu_value = document.getElementById("cmb_mainmenu_optn").value;
		document.getElementById('txt_mainmenu_edit').value = mainmenu_text;
	}
	if(sectid == "sublevelonesection")
	{
		document.getElementById('txt_sublevel1_edit').value = "";
		var elemt = document.getElementById("cmb_sublevel_1_optn");
		var mainmenu_text  = elemt.options[elemt.selectedIndex].text;
		var mainmenu_value = document.getElementById("cmb_sublevel_1_optn").value;
		document.getElementById('txt_sublevel1_edit').value = mainmenu_text;
	}
}
</script>
<style>
.menutypesection
{
	border:1px solid #20b2aa;
	border-top:none;
}
.dynamicrowcell
{
	width:200px;
	height:30px;
	text-align:center;
}
.dynamictextbox
{
	width:190px;
	border:1px solid #05BCE2;
}
.dynamictextbox:hover, .dynamictextbox:focus
{
	outline: none;
    border-color: #9ecaed;
    box-shadow: 0 0 10px #9ecaed;
}
.hide
{
	display:none;
}
.TitleDiv
{
	color:#C70592;
	font-weight:bold;
	font-size:14px;
	height:25px;
	border-bottom:1px solid #20b2aa;
	/*background-color:#EAFFEB;*/
	background-color:#f3ecf4;
	line-height:25px;
}
</style>
<body class="page1" id="top" oncontextmenu="return false">
<!--==============================header=================================-->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
  <div class="content">
     <div class="container_12">
        <div class="grid_12">
			<div align="right"><a href="">View&nbsp;&nbsp;</a></div>
            <blockquote class="bq1">
               	<div class="title">Menu Creation</div>
				<!-----------------MENU TYPE SECTION STARTS HERE---------------->
				<div align="center" class="menutypesection" id="menutypesection">
				<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						
						<td class="label" align="center">Select Your Option</td>
						<td>&nbsp;</td>
						<td align="center" width="290px">
							<select name="cmb_menu_type" id="cmb_menu_type" class="textboxdisplay" onChange="DispalyMenuTypeSection(this)" style="width:287px;">
							<option value="0">---------------Select Menu Type--------------</option>
							<option value="1">Main Menu</option>
							<option value="2">Sub Level I</option>
							<option value="3">Sub Level II</option>
							</select>
						</td>
						<td width="55px">&nbsp;</td>
						<td width="55px">&nbsp;</td>
						<td width="55px">&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				</div>	
				<!-----------------MAIN MENU SECTION STARTS HERE---------------->
				<div align="center" class="menutypesection hide" id="mainmenusection">
				<div class="TitleDiv">MAIN MENU CREATION </div>
				<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" id="mainmenutable">
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr align="center">
						
						<td class="label" align="center">Main Menu Name</td>
						<td>&nbsp;</td>
						<td width="200px">
							<select name="cmb_mainmenu_optn" id="cmb_mainmenu_optn" class="textboxdisplay" style="width:196px;" onChange="changeMenu('mainmenusection');">
								<option value="0">------Select Main Menu------</option>
								<option value="1">Main Menu</option>
								<option value="2">Sub Level I</option>
								<option value="3">Sub Level II</option>
							</select>
							<input type="text" name="txt_mainmenu" id="txt_mainmenu" disabled="disabled" class="textboxdisplay hide" style="width:190px;">
						</td>
						<td width="85px">
							<input type="button" name="btn_mainmenu_add" id="btn_mainmenu_add" class="updatebtnstyle" value=" ADD " style="width:80px; height:21px" onClick="addFuntion('mainmenusection');">
						</td>
						<td width="85px">
							<input type="button" name="btn_mainmenu_edit" id="btn_mainmenu_edit" class="editbtnstyle" value=" EDIT " style="width:80px" onClick="editFunction('mainmenusection');">
						</td>
						<td width="85px">
							<input type="button" name="btn_mainmenu_delete" id="btn_mainmenu_delete" class="delbtnstyle" value=" DEL " style="width:80px">
						</td>
					</tr>
					<tr id="mainmenu_edit" class="hide" align="center">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							<br/><input type="text" name="txt_mainmenu_edit" id="txt_mainmenu_edit" class="textboxdisplay dynamictextbox" style="width:190px;"><br/>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				</div>	
				<!-----------------SUB LEVEL - I SECTION STARTS HERE---------------->
				<div align="center" class="menutypesection hide" id="sublevelonesection">
				<div class="TitleDiv">SUB LEVEL - I CREATION </div>
				<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" id="sublevelonetable">
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						
						<td class="label" align="center">Main Menu Name</td>
						<td>&nbsp;</td>
						<td align="center">
							<select name="cmb_main_menu" id="cmb_main_menu" class="textboxdisplay" style="width:196px;">
								<option value="0">------Select Main Menu------</option>
								<option value="1">Main Menu 1</option>
								<option value="2">Main Menu 2</option>
								<option value="3">Main Menu 3</option>
							</select>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr align="center">
						
						<td class="label" align="center">Sub Level I Name</td>
						<td>&nbsp;</td>
						<td width="200px">
							<select name="cmb_sublevel_1_optn" id="cmb_sublevel_1_optn" class="textboxdisplay" style="width:196px;" onChange="changeMenu('sublevelonesection');">
								<option value="0">------Select Sub Level I------</option>
								<option value="1">Main Menu</option>
								<option value="2">Sub Level I</option>
								<option value="3">Sub Level II</option>
							</select>
							<input type="text" name="txt_sublevel_1" id="txt_sublevel_1" disabled="disabled" class="textboxdisplay hide" style="width:190px;">
						</td>
						<td width="85px">
							<input type="button" name="btn_sublevel_1_add" id="btn_sublevel_1_add" class="updatebtnstyle" value=" ADD " style="width:80px; height:21px" onClick="addFuntion('sublevelonesection')">
						</td>
						<td width="85px">
							<input type="button" name="btn_sublevel_1_edit" id="btn_sublevel_1_edit" class="editbtnstyle" value=" EDIT " style="width:80px" onClick="editFunction('sublevelonesection');">
						</td>
						<td width="85px">
							<input type="button" name="btn_sublevel_1_delete" id="btn_sublevel_1_delete" class="delbtnstyle" value=" DEL " style="width:80px">
						</td>
					</tr>
					<tr id="sublevel_one_edit" class="hide" align="center">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							<br/><input type="text" name="txt_sublevel1_edit" id="txt_sublevel1_edit" class="textboxdisplay dynamictextbox" style="width:190px;"><br/>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				</div>	
				<!-----------------SUB LEVEL - II SECTION STARTS HERE---------------->
				<div align="center" class="menutypesection hide" id="subleveltwosection">
				<div class="TitleDiv">SUB LEVEL - II CREATION </div>
				<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" id="subleveltwotable">
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					</tr>
					<tr>
						
						<td class="label" align="center">Main Menu Name</td>
						<td>&nbsp;</td>
						<td align="center">
							<select name="cmb_main_menu" id="cmb_main_menu" class="textboxdisplay" style="width:196px;">
								<option value="0">------Select Main Menu------</option>
								<option value="1">Main Menu 1</option>
								<option value="2">Main Menu 2</option>
								<option value="3">Main Menu 3</option>
							</select>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						
						<td class="label" align="center">Sub Level I Name</td>
						<td>&nbsp;</td>
						<td align="center">
							<select name="cmb_sublevel_1" id="cmb_sublevel_1" class="textboxdisplay" style="width:196px;">
								<option value="0">-----Select Sub Level I ------</option>
								<option value="1">sub level a</option>
								<option value="2">sub level b</option>
								<option value="3">sub level c</option>
							</select>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr align="center">
						
						<td class="label" align="center">Sub Level II Name</td>
						<td>&nbsp;</td>
						<td width="200px">
							<select name="cmb_sublevel_2_optn" id="cmb_sublevel_2_optn" class="textboxdisplay" style="width:196px;">
								<option value="0">------Select Sub Level I------</option>
								<option value="1">Main Menu</option>
								<option value="2">Sub Level I</option>
								<option value="3">Sub Level II</option>
							</select>
							<input type="text" name="txt_sublevel_2" id="txt_sublevel_2" disabled="disabled" class="textboxdisplay hide" style="width:190px;">
						</td>
						<td width="85px">
							<input type="button" name="btn_sublevel_2_add" id="btn_sublevel_2_add" class="updatebtnstyle" value=" ADD " style="width:80px; height:21px" onClick="addFuntion('subleveltwosection')">
						</td>
						<td width="85px">
							<input type="button" name="btn_sublevel_2_edit" id="btn_sublevel_2_edit" class="editbtnstyle" value=" EDIT " style="width:80px">
						</td>
						<td width="85px">
							<input type="button" name="btn_sublevel_2_delete" id="btn_sublevel_2_delete" class="delbtnstyle" value=" DEL " style="width:80px">
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				</div>	
				<div class="hide" id="save_btn_section" align="center">
					<br/>
					<input type="submit" name="save" id="save" value=" Save " >
				</div>		
            </blockquote>
        </div>
    </div>
 </div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script src="js/jquery.hoverdir.js"></script>
<script>
var msg = "<?php echo $msg; ?>";
var titletext = "Hi ";
document.querySelector('#top').onload = function(){
	if(msg != "")
	{
		swal({
			title: titletext,
			text: msg,
			timer: 4000,
			showConfirmButton: true
		});
	}
};
</script>
</form>
</body>
</html>
