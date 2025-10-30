<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
$msg = '';
?>

<?php include "Header.html"; ?>
<script>
			function goBack()
			{
				url = "designationlist.php";
				window.location.replace(url);
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
			var add_row_s = 2;
			var prev_edit_row = 0
			function addmenu()
			{
				var new_row = document.getElementById("menutable").insertRow(add_row_s);
				new_row.setAttribute("id", "row_" + add_row_s)
				new_row.className = "labelcenter labelhead";
				var c1 = new_row.insertCell(0);
				c1.align = "center";c1.style.border = "thin solid lightgray";
				var c2 = new_row.insertCell(1);
				c2.align = "center";c2.style.border = "thin solid lightgray";
				var c3 = new_row.insertCell(2);
				c3.align = "center";c3.style.border = "thin solid lightgray";
				var c4 = new_row.insertCell(3);
				c4.align = "center";c4.style.border = "thin solid lightgray";
				var c5 = new_row.insertCell(4);
				c5.align = "center";c5.style.border = "thin solid lightgray";
			
				c1.innerText = c1.textContent = document.form.txt_sno.value;
				c2.innerText = c2.textContent = document.form.txt_menuname.value;
				c3.innerText = c3.textContent = document.form.txt_menucode.value;
				c4.innerText = c4.textContent = document.form.txt_menuurl.value;
				c5.innerHTML = "<input type='button' class='dynamicbutton2' name='btn_edit_" + add_row_s + "' id='btn_edit_" + add_row_s + "' title = 'Edit' value='EDIT' onClick=editmenu(" + add_row_s + ",'n')><input class='dynamicbutton3' type='button'  name='btn_del_" + add_row_s + "'  id='btn_del_" + add_row_s + "' title = 'DELETE' value=' DEL ' onClick=delmenu(" + add_row_s + ");>";
				var hide_values = "";
				hide_values = "<input type='hidden' value='" + c1.innerText + "' name='txt_sno" + add_row_s + "' id='txt_sno" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c2.innerText + "' name='txt_menuname" + add_row_s + "' id='txt_menuname" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c3.innerText + "' name='txt_menucode" + add_row_s + "' id='txt_menucode" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c4.innerText + "' name='txt_menuurl" + add_row_s + "' id='txt_menuurl" + add_row_s + "' >";
				document.getElementById("dynamicrow").innerHTML = document.getElementById("dynamicrow").innerHTML + hide_values; 
	
				if (document.getElementById("add_set_a1").value == "")
					document.getElementById("add_set_a1").value = add_row_s;
				else
					document.getElementById("add_set_a1").value = document.getElementById("add_set_a1").value + "." + add_row_s; 
				document.form.txt_sno.value=parseInt(document.form.txt_sno.value)+1;
				add_row_s++;
				cleartxt();
			}
			function cleartxt()
			{
				document.getElementById("txt_menuname").value = "";
				document.getElementById("txt_menucode").value = "";
				document.getElementById("txt_menuurl").value = "";
			}
			function editmenu(rowno, update)
			{
				var temp = 0;		
				var total;
				var net_value;
				var edit_row = document.getElementById("menutable").rows[rowno].cells;
				var sno=document.form.txt_sno.value;
				if(document.form.sno_hide.value=="")
				{
					document.form.sno_hide.value=document.form.txt_sno.value;
				}
				if (update == 'y') // transfer controls to table row   // THIS PART IS FOR CLICK OK BUTTON AFTER FOR SET NEW VALUE EDIT
				{	
					edit_row[0].innerText = edit_row[0].textContent = document.form.txt_sno.value;
					edit_row[1].innerText = edit_row[1].textContent = document.form.txt_menuname.value;
					edit_row[2].innerText = edit_row[2].textContent= document.form.txt_menucode.value;
					edit_row[3].innerText = edit_row[3].textContent= document.form.txt_menuurl.value;
					document.getElementById("txt_sno" + rowno).value = edit_row[0].innerText = edit_row[0].textContent
					document.getElementById("txt_menuname" + rowno).value = edit_row[1].innerText = edit_row[1].textContent
					document.getElementById("txt_menucode" + rowno).value = edit_row[2].innerText = edit_row[2].textContent
					document.getElementById("txt_menuurl" + rowno).value = edit_row[3].innerText = edit_row[3].textContent
				}//update=='y'
	
				else  //transfer table row to controls   //// THIS PART IS FOR FIRST TIME CLICK EDIT BUTTON
				{
					document.form.txt_sno.value = edit_row[0].innerText = edit_row[0].textContent
					document.form.txt_menuname.value = edit_row[1].innerText = edit_row[1].textContent
					document.form.txt_menucode.value = edit_row[2].innerText = edit_row[2].textContent
					document.form.txt_menuurl.value = edit_row[3].innerText = edit_row[3].textContent
				}
	
				if (prev_edit_row == 0)//first time edit the row  //// THIS PART IS FOR FIRST TIME CLICK EDIT BUTTON
				{
					document.getElementById("row_" + rowno).style.color = "red";
					document.getElementById("btn_edit_" + rowno).className = "dynamicbutton2";
					document.getElementById("btn_add").outerHTML = "<input type='button' title='Accept' class='dynamicbutton2' name='btn_add' id='btn_add' value=' OK ' onClick=\"editmenu(" + rowno + ",'y');\"><input type='button' class='dynamicbutton3' title='Reset' name='btn_clr' id='btn_clr' value='RESET' onClick=\"cancel_gen(" + rowno + ",'c')\">";
					prev_edit_row = rowno;
				}
				else
				{	
					if (rowno == prev_edit_row)
					{
						document.getElementById("row_" + prev_edit_row).style.color = "#770000";
						document.getElementById("btn_edit_" + rowno).className = "dynamicbutton2";
						document.getElementById("btn_clr").style.display="none";
						document.getElementById("btn_add").outerHTML = "<input type='button' title='Add' class='dynamicbutton1' name='btn_add' id='btn_add' value='ADD' onClick='addmenu();'>";
						prev_edit_row = 0;
						cleartxt();
					}
					else
					{   document.getElementById("txt_sno").value=document.getElementById("sno_hide").value;
						document.getElementById("sno_hide").value="";
						document.getElementById("row_" + prev_edit_row).style.color = "#770000";
						document.getElementById("btn_edit_" + prev_edit_row).className = "dynamicbutton2";
						document.getElementById("row_" + rowno).style.color = "red";
						document.getElementById("btn_edit_" + rowno).className = "dynamicbutton3";
						document.getElementById("btn_add").outerHTML = "<input type='button' title='Accept' claas='dynamicbutton1' name='btn_add' id='btn_add' value=' OK ' onClick=\"editmenu(" + rowno + ",'y');\">";
						prev_edit_row = rowno;
					}
					document.getElementById("txt_sno").value=document.getElementById("sno_hide").value;
					document.getElementById("sno_hide").value="";
				}
			}
			function delmenu(rownum)
			{
				var no=document.getElementById("sno_hide").value=document.getElementById("txt_sno").value;
				var src_row = (rownum + 1)
				var tar_row = rownum
				var noofadd = (add_row_s - 1)
				
				for (x = rownum; x < noofadd; x++)
				{	
					document.getElementById("txt_sno" + tar_row).value= document.getElementById("txt_sno" + src_row).value;
					document.getElementById("txt_menuname" + tar_row).value = document.getElementById("txt_menuname" + src_row).value
					document.getElementById("txt_menucode" + tar_row).value = document.getElementById("txt_menucode" + src_row).value
					document.getElementById("txt_menuurl" + tar_row).value = document.getElementById("txt_menuurl" + src_row).value
					tar_row++;
					src_row++;
					var trow = document.getElementById("menutable").rows[x].cells;
					var srow = document.getElementById("menutable").rows[x + 1].cells;
					trow[0].innerText = trow[0].textContent = srow[0].innerText = srow[0].textContent 
					trow[1].innerText = trow[1].textContent  = srow[1].innerText = srow[1].textContent
					trow[2].innerText = trow[2].textContent  = srow[2].innerText = srow[2].textContent
					trow[3].innerText = trow[3].textContent  = srow[3].innerText = srow[3].textContent
				}
				document.getElementById("txt_sno" + tar_row).outerHTML = ""
				document.getElementById("txt_menuname" + tar_row).outerHTML = ""
				document.getElementById("txt_menucode" + tar_row).outerHTML = ""
				document.getElementById("txt_menuurl" + tar_row).outerHTML = ""
				document.getElementById('menutable').deleteRow(noofadd)
				document.getElementById("add_set_a1").value = "";
				for (x = 2; x < noofadd; x++)
				{
					if (document.getElementById("add_set_a1").value == "")
					{
						document.getElementById("add_set_a1").value = x;
						document.getElementById("txt_sno").value=x-1;
					}
					else
					{
						document.getElementById("add_set_a1").value += ("." + x);
						document.getElementById("txt_sno").value=x-1;
					}
				}
				add_row_s = noofadd++; 
				for(i=1;i<no-1;i++)
				{
					var trow = document.getElementById("menutable").rows[i+2].cells; 
					trow[0].innerText = trow[0].textContent = i;
				}
				document.getElementById("sno_hide").value="";
			}
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
	width:280px;
	height:25px;
	background:#FFFFFF;
	border:1px solid #dfdfdf;
}
.dynamictextbox1
{
	/*width:190px;*/
	width:50px;
	height:25px;
	border:1px solid #dfdfdf;
}
.dynamicbutton1
{
	/*width:190px;*/
	width:90px;
	height:28px;
	background:#49d1a3;
	border:1px solid #49d1a3;
	color:#FFFFFF;
	cursor:pointer;
}
.dynamicbutton2
{
	/*width:190px;*/
	width:43px;
	height:28px;
	background:#50b8de;
	border:1px solid #50b8de;
	color:#FFFFFF;
	cursor:pointer;
}
.dynamicbutton3
{
	/*width:190px;*/
	width:43px;
	height:28px;
	background:#eeb34f;
	border:1px solid #eeb34f;
	color:#FFFFFF;
	cursor:pointer;
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
<style>
th
{
	background-color:#656565;
	color:#FFFFFF;
	height:25px;
}
td{
	border:1px solid #CCCCCC;
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
            <blockquote id="bq1" class="bq1" style="background:none">
               	<div class="title">Menu Creation</div>
				<form name="form" action="" method="post">
				<!-----------------MENU TYPE SECTION STARTS HERE---------------->
				<div align="center" class="menutypesection" id="menutypesection">
				<table width="1078" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						
						<td class="label" width="278" align="center">Choose  your menu option</td>
						<td align="center">&nbsp;</td>
						<td width="">&nbsp;</td>
						<td width="">&nbsp;</td>
						<td>&nbsp;</td>
						<!--<td width="55px">&nbsp;</td>-->
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						
						<td class="label" width="278" align="center">&nbsp;</td>
						<td align="center">
							<input type="radio" name="rad_menu" id="rad_mainmenu">
							<label class="label">Main Menu</label>
						</td>
						<td width=""><input type="radio" name="rad_menu" id="rad_submenu_l1">
							<label class="label">Sub Menu  Level1</label>
						</td>
						<td width=""><input type="radio" name="rad_menu" id="rad_submenu_l2">
							<label class="label">Sub Menu  Level2</label>
						</td>
						<td width=""><input type="radio" name="rad_menu" id="rad_submenu_l3">
							<label class="label">Sub Menu  Level3</label>
						</td>
						
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				</div>	
				<div align="center" class="menutypesection" id="menutypesection">
				<table width="995" align="center" cellpadding="0" cellspacing="0" id="menutable">
					<tr class="" height="27">
						<th width="50">S.No.</th>
						<th width="280">Menu Name</th>
						<th width="280">Menu Code</th>
						<th width="280">Menu Url</th>
						<th width="90">Action</th>
					</tr>
					<tr height="30">
						<td><input type="text" name="txt_sno" id="txt_sno" value="1" class="dynamictextbox1"></td>
						<td><input type="text" name="txt_menuname" id="txt_menuname"  class="dynamictextbox"></td>
						<td><input type="text" name="txt_menucode" id="txt_menucode" class="dynamictextbox"></td>
						<td><input type="text" name="txt_menuurl" id="txt_menuurl" class="dynamictextbox"></td>
						<td><input type="button" name="btn_add" id="btn_add" value=" ADD " class="dynamicbutton1" onClick="addmenu();"></td>
					</tr>
					
					<span id="dynamicrow"></span>
				</table>
				</div>
				 <input type="hidden" value="" name="add_set_a1" id="add_set_a1"/>	
				 <input type="hidden"  id="sno_hide" name="sno_hide">
				</form>
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
