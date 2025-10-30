<?php
 @ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once("ajax_table.class.php");
$obj = new ajax_table();
$obj1 = new ajax_table();
$temp = "menu";
$temp1 = "mainmenu";
$menurecords = $obj->getRecords($temp,0);
$records = $obj1->getRecords($temp1,0);
$msg = '';
?>
<script type="text/javascript" language="javascript">
    var columns = new Array("menuname","menucode");
	 var placeholder = new Array(" Enter Menu Name"," Enter Menu Code (eg. ADMN,AGMN,GMBG etc)");
	 var inputType = new Array("text","text");
	 //var table = "mainmenu";
	 var table1 = new Array("mainmenu","sublevel1","sublevel2","sublevel3");
	 var temp = "menu";
	 // Set button class names 
	 var savebutton = "ajaxSave";
	 var deletebutton = "ajaxDelete";
	 var editbutton = "ajaxEdit";
	 var updatebutton = "ajaxUpdate";
	 var cancelbutton = "cancel";
	 
	 var saveImage = "images/save.png"
	 var editImage = "images/edit.png"
	 var deleteImage = "images/remove.png"
	 var cancelImage = "images/back.png"
	 var updateImage = "images/save.png"

	 // Set highlight animation delay (higher the value longer will be the animation)
	 var saveAnimationDelay = 3000; 
	 var deleteAnimationDelay = 1000;
	  
	 // 2 effects available available 1) slide 2) flash
	 var effect = "flash"; 
</script>
<?php include "Header.html"; ?>
<script src="js/createmenuscript.js"></script>	
<link rel="stylesheet" href="css/createmenustyle.css">
<script>
   	function goBack()
	{
	   	url = "designationlist.php";
		window.location.replace(url);
	}
	
	/*function func_desc()
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
	}*/
  	
  	/*var index = 1;
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

    }*/
/*function myDeleteFunction(id) 
{
   var row = document.getElementById(id);
   row.parentNode.removeChild(row);
}*/
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
/*function addFuntion(section_id)
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
}*/
/*function editFunction(section_id)
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
}*/
/*function changeMenu(section_id)
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
}*/
</script>
<style>
.menutypesection
{
	border:0px solid #20b2aa;
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
	/*width:190px;*/
	width:320px;
	height:25px;
	border:1px solid #EFEFEF;
}
.dynamictextbox:hover, .dynamictextbox:focus
{
	outline: none;
    border-color: #9ecaed;
    box-shadow: 0 0 5px #9ecaed;
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
	border-bottom:0px solid #20b2aa;
	/*background-color:#EAFFEB;*/
	background-color:#f3ecf4;
	line-height:25px;
}
.TitileDiv1
{
    background: rgba(0, 0, 0, 0) -moz-linear-gradient(center top , darkgray, #ffffff 1px, #ededed 25px) repeat scroll 0 0;
    border-color: #ffffff #ffffff #ffffff #ededed;
    border-style: solid;
    border-width: 1px;
    color: #e20404;
    font-size: 13px;
    font-weight: bold;
    padding: 1px 4px;
    text-decoration: none;
}
</style>
<!--<body class="page1" id="top" oncontextmenu="return false">-->
<body class="page1" id="top">
<!--==============================header=================================-->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
  <div class="content">
     <div class="container_12">
        <div class="grid_12">
			<div align="right"><a href="">View&nbsp;&nbsp;</a></div>
            <blockquote id="bq1" class="bq1">
               	<div class="title">Menu Creation</div>
				<!-----------------MENU TYPE SECTION STARTS HERE---------------->
				<div align="center" class="menutypesection" id="menutypesection">
				<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<!--<td>&nbsp;</td>
						<td>&nbsp;</td>-->
					</tr>
					<tr>
						
						<td class="label" align="center">Select Your Option</td>
						<td align="center" width="290px">
							<select name="cmb_menu_type" id="cmb_menu_type" class="textboxdisplay" onChange="DispalyMenuTypeSection(this)" style="width:287px;">
										<option value="">--------------Select Menu Name---------------</option>
							<?php 
									if(count($menurecords))
									{
										foreach($menurecords as $key=>$eachMenu)
										{
											echo "<option value=".$eachMenu['menu_type'].">".$eachMenu['menu_desc']."</option>";
										}
									}
							 ?>
									</select>
						</td>
						<td width="55px">&nbsp;</td>
						<td width="55px">&nbsp;</td>
						<!--<td width="55px">&nbsp;</td>-->
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<!--<td>&nbsp;</td>
						<td>&nbsp;</td>-->
					</tr>
				</table>
				</div>	
				<!-----------------MAIN MENU SECTION STARTS HERE---------------->
				<div align="center" class="menutypesection hide" id="mainmenusection">
					<div class="TitleDiv TitileDiv1">MAIN MENU CREATION </div>
						<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
						<table border="0" class="mainmenu bordered">
							<tr class="ajaxTitle">
								<th width="50px" align="left">S.No.</th>
								<th width="170px" align="left">Main Menu Name</th>
								<th width="170px" align="left">Menu Code Name</th>
								
								
								<th width="100px">Action</th>
							</tr>
							<!--<?
							/*if(count($records)){
							 $i = 1;	
							 foreach($records as $key=>$eachRecord){*/
							?>
							<tr id="<?=$eachRecord['id'];?>">
								<td><?=$i++;?></td>
								<td class="menuname"><?=$eachRecord['fname'];?></td>
								<td class="menucode"><?=$eachRecord['lname'];?></td>
								
								
								<td align="center">
									<input type="button" id="<?=$eachRecord['id'];?>" value=" EDIT " class="editbtnstyle ajaxEdit">
									<input type="button" id="<?=$eachRecord['id'];?>" value=" DEL " class="delbtnstyle ajaxDelete">
								</td>
							</tr>
							<? /*}
							}*/
							?>-->
						</table> 
				</div>	
				<!-----------------SUB LEVEL - I SECTION STARTS HERE---------------->
				<div align="center" class="menutypesection hide" id="sublevelonesection">
					<div class="TitleDiv TitileDiv1">SUB LEVEL - I CREATION </div>
					<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="label" align="center">Select Main Menu</td>
							<td width="290px">
								<select name="cmb_mainmenu_L1" id="cmb_mainmenu_L1" class="textboxdisplay" style="width:287px;">
									<option value="0">---------------Select Main Menu--------------</option>
									<!--<option value="1">Main Menu 1</option>
									<option value="2">Main Menu 2</option>
									<option value="3">Main Menu 3</option>
									<option value="4">Main Menu 4</option>-->
								</select>
							</td>
							<td width="55px">&nbsp;</td>
							<td width="55px">&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
<!----//////////////////////////////XXXXXXXXXXXXXXXXXXXX///////////////////////////////////////-->	
				<div style="height:500px; overflow:scroll">					
					<table border="0" class="sublevel1 bordered">
						<tr class="ajaxTitle">
							<th width="2%">S.No.</th>
							<th width="16%">Sub Menu Level-I Name</th>
							<th width="16%">Menu Code Name</th>
							
							
							<th width="14%">Action</th>
						</tr>
						<!--<?
						/*if(count($records)){
						 $i = 1;	
						 foreach($records as $key=>$eachRecord){*/
						?>
						<tr id="<?=$eachRecord['id'];?>">
							<td><?=$i++;?></td>
							<td class="menuname"><?=$eachRecord['fname'];?></td>
							<td class="menucode"><?=$eachRecord['lname'];?></td>
							
							
							<td align="center">
								<input type="button" id="<?=$eachRecord['id'];?>" value=" EDIT " class="editbtnstyle ajaxEdit">
								<input type="button" id="<?=$eachRecord['id'];?>" value=" DEL " class="delbtnstyle ajaxDelete">
							</td>
						</tr>
						<? /*}
						}*/
						?>-->
				</table> 
				</div>
						
					<!--</div>-->
				</div>	
				<!-----------------SUB LEVEL - II SECTION STARTS HERE---------------->
				<div align="center" class="menutypesection hide" id="subleveltwosection">
					<div class="TitleDiv TitileDiv1">SUB LEVEL - II CREATION </div>
					<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="label" align="center">Select Main Menu</td>
							<td width="290px">
								<select name="cmb_mainmenu_L2" id="cmb_mainmenu_L2" class="textboxdisplay" style="width:287px;">
									<option value="0">---------------Select Main Menu--------------</option>
									<!--<option value="1">Main Menu 1</option>
									<option value="2">Main Menu 2</option>
									<option value="3">Main Menu 3</option>
									<option value="4">Main Menu 4</option>-->
								</select>
							</td>
							<td width="55px">&nbsp;</td>
							<td width="55px">&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="label" align="center">Select Sub Level - I</td>
							<td width="290px">
								<select name="cmb_submenu_1_L2" id="cmb_submenu_1_L2" class="textboxdisplay" style="width:287px;">
									<option value="0">-------------Select Sub level - I------------</option>
									<!--<option value="1">Sub Menu 1</option>
									<option value="2">Sub Menu 2</option>
									<option value="3">Sub Menu 3</option>
									<option value="4">Sub Menu 4</option>-->
								</select>
							</td>
							<td width="55px">&nbsp;</td>
							<td width="55px">&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
<!----//////////////////////////////XXXXXXXXXXXXXXXXXXXX///////////////////////////////////////-->	
				<div style="height:500px; overflow:scroll">					
					<table border="0" class="sublevel2 bordered">
						<tr class="ajaxTitle">
							<th width="2%">S.No.</th>
							<th width="16%">Sub Menu Level-II Name</th>
							<th width="16%">Menu Code Name</th>
							
							
							<th width="14%">Action</th>
						</tr>
						<!--<?
						/*if(count($records)){
						 $i = 1;	
						 foreach($records as $key=>$eachRecord){*/
						?>
						<tr id="<?=$eachRecord['id'];?>">
							<td><?=$i++;?></td>
							<td class="menuname"><?=$eachRecord['fname'];?></td>
							<td class="menucode"><?=$eachRecord['lname'];?></td>
							
							
							<td align="center">
								<input type="button" id="<?=$eachRecord['id'];?>" value=" EDIT " class="editbtnstyle ajaxEdit">
								<input type="button" id="<?=$eachRecord['id'];?>" value=" DEL " class="delbtnstyle ajaxDelete">
							</td>
						</tr>
						<? /*}
						}*/
						?>-->
				</table> 
				</div>
				</div>	
				<div class="hide" id="save_btn_section" align="center">
					<!--<input type="submit" name="save" id="save" value=" Save " >-->
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
