<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
//checkUser();

if (isset($_POST['btn_save_x']))
{
	$sql_equip_id="select max(equip_id) as equip_id from equip_master";
	$rs_equip_id=mysqli_query($dbConn,$sql_equip_id,$conn);
	
	$equip=mysqli_result($rs_equip_id,0,'equip_id');

	$equip_id=$equip+1;
	//echo $equip.'<br />';
	
	if($_POST['cmb_type']=='Percentage')
	{
		$sql_insert="insert into equip_master(equip_id,equip_desc,description,type)
						values('" . $equip_id . "' ,
							   '" . $_POST['txt_equip_name'] . "' ,
							   '" . $_POST['txt_equip_name'] . "' ,
							   '" . $_POST['cmb_type'] . "' )";
		$rs_insert=mysqli_query($dbConn,$sql_insert,$conn);
		//echo $sql_insert.'<br />';
	}
	else
	{
		$sql_insert="insert into equip_master(equip_id,equip_desc,description,type,rate_hour)
						values('" . $equip_id . "' ,
							   '" . $_POST['txt_equip_name'] . "' ,
							   '" . $_POST['txt_equip_name'] . "' ,
							   '" . $_POST['cmb_type'] . "' ,
							   '" . $_POST['txt_rate_hour'] . "' )";
		$rs_insert=mysqli_query($dbConn,$sql_insert,$conn);
		//echo $sql_insert.'<br />';
	}
	 
	if($rs_insert!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Saved")
		</script>
		<?php
	}
	$rs_insert='';
	unset($_GET['equip_id']);
}

if (isset($_POST['btn_update_x']))
{
	if($_POST['type_hidden']=='Percentage')
	{
		$sql_update="update equip_master set 
						equip_desc='" . $_POST['txt_equip_name_1'] . "' ,
						description='" . $_POST['txt_equip_name_1'] . "' 
						where equip_id='" . $_GET['equip_id'] . "'";
		$rs_update=mysqli_query($dbConn,$sql_update,$conn);
		//echo "if".$sql_update.'<br />';
	}
	else
	{
		$sql_update="update equip_master set 
						equip_desc='" . $_POST['txt_equip_name_1'] . "' ,
						description='" . $_POST['txt_equip_name_1'] . "' ,
						rate_hour='" . $_POST['txt_rate_hour1'] . "'
						where equip_id='" . $_GET['equip_id'] . "'";
		$rs_update=mysqli_query($dbConn,$sql_update,$conn);
		//echo $sql_update.'<br />';
	}
	
	if($rs_update!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Updated")
			window.location="equipmentmaster.php"
		</script>
		<?php
	}
	//unset($_GET['equip_id']);
	//$rs_update='';
}


if (isset($_POST['btn_back_x']))
{
	unset($_GET['equip_id']);
}

/*if(isset($_POST['btn_delete_x']))
{
	?>
	<script type="text/javascript" language="javascript">
	x=confirm("Are you sure want to delete?")
	if(x==true)
	{
		url="equipmentmaster.php?action=delete&equip_id=<?php echo $_GET['equip_id'] ?>"
		window.location.href="equipmentmaster.php?action=delete&equip_id=<?php echo $_GET['equip_id'] ?>"
	}
	</script>
	<?php 
}*/

	$sql_count="select count(ref_id) as ref_id from datasheet_a2_details where  equip_id like '" . $_GET['equip_id'] . "%'";
	$rs_count=mysqli_query($dbConn,$sql_count,$conn);
	$ref_count=@mysqli_result($rs_count,0,'ref_id');
//echo $sql_count;
//echo $sql_count;
/*if($_GET['action']=="delete1")
{
	$sql_delete="delete from equip_master where equip_id='" . $_GET['equip_id'] . "'";
	$rs_delete=mysqli_query($dbConn,$sql_delete,$conn);
	if($rs_delete!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Deleted")
			window.location="equipmentmaster.php"
		</script>
		<?php
	}
	unset($_GET['equip_id']);
	$rs_delete='';
}*/
if(isset($_POST['btn_delete_x']))
{	
	if ($ref_count>0)
	{
		?>
			<script type="text/javascript" language="javascript">
				var x= <?php echo $ref_count; ?>;
				alert(("Under this Item ") + x +  (" dependencies are there. If you need to Delete, First you have to delete the dependencies under this Item in Group 2"));
			</script>
			<?php
	}
	else
	{
		//$sql_delete="update group_datasheet set delete_In='D' where group_id like '" . $_GET['gp_id'] . "%'";
		$sql_delete="delete from equip_master where equip_id='" . $_GET['equip_id'] . "'";
		$rs_delete=mysqli_query($dbConn,$sql_delete,$conn);
		if($rs_delete!="")
		{
 
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Deleted")
			window.location="equipmentmaster.php"
		</script>
		<?php
		}
	}
	
}
if($_GET['equip_id']!="")
{
	$sql_modify="select * from equip_master where equip_id='" . $_GET['equip_id'] . "'";
	//echo $sql_modify;
	$rs_modify=mysqli_query($dbConn,$sql_modify,$conn);
	$type=@mysqli_result($rs_modify,0,'type');
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<script type="text/javascript" language="javascript">
function validation()
{	
	x=alltrim(document.form.txt_equip_name.value)
	if(x.length==0)
	{
		alert("Please Enter the Equipment Name")
		document.form.txt_equip_name.value="";
		document.form.txt_equip_name.focus();
		return false
	}
 
 	if(document.form.cmb_type.value=='Select')
	{
		alert("Please Select the Type")
		document.form.cmb_type.value="1";
		document.form.cmb_type.focus();
		return false
	}
	
	if(document.form.cmb_type.value=='Hour')
	{
		x=alltrim(document.form.txt_rate_hour.value)
		if(x.length==0)
		{
			alert("Please Enter the Equipment Name")
			document.form.txt_rate_hour.value="";
			document.form.txt_rate_hour.focus();
			return false
		}
	}
}

/*function valid_ename()
{	
	var letters = /^[A-Za-z]+$/; 
	
	//x=alltrim(document.form.txt_equip_name.value)
	if(document.form.txt_equip_name.value.match(letters))  
	{  
	return true;  
	}  
	else  
	{  
	alert('Username must have alphabet characters only');  
	document.form.txt_equip_name.value="";
	document.form.txt_equip_name.focus();  
	return false;  
	}  	
}
*/

/*function valid_erate()
{	
	var letters = /^[0-9]+$/; 
	
	//x=alltrim(document.form.txt_equip_name.value)
	if(document.form.txt_rate_hour.value.match(letters))  
	{  
	return true;  
	}  
	else  
	{  
	alert('Rate should be Numeric Value only');  
	document.form.txt_rate_hour.value="";
	document.form.txt_rate_hour.focus();  
	return false;  
	}  	
}*/


function fn_default_code()
{
	var rows=document.getElementById("equip_rows").value
	var x=0;
	if (rows>0)
	{
		for(x=0;x<=rows;x++)
		{
			if(document.form.rad[x].checked==true )
			{	
				document.form.equip_code.value=alltrim(document.getElementById("equip_id"+x).value);
			}
		}
	}
	else
	{
		document.form.equip_code.value=document.getElementById("equip_id"+x).value;
	}
	func_default_id()
}

function func_default_id()
{
	document.form.method="post";
	document.form.action="equipmentmaster.php?equip_id="+document.form.equip_code.value
	document.form.submit();
}

function list_search(id,val)
 {
     cnt=document.getElementById(id).length
	 //alert(cnt)
	 for(x=0; x<cnt; x++ )
	 {
		 if( document.getElementById(id).options(x).value==val)
		 {
		 		//alert()
			 document.getElementById(id).options(x).selected=true
			 break;
		 }
	 }
} 


function show_hour_unit()
{
	if(document.form.cmb_type.value!='Percentage')
	{
		document.getElementById("type").style.display="";
	}
	else
	{
		document.getElementById("type").style.display="none";
	}
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
	<table width="725" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="heading">
			<td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
			<td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Equipment Master</td>
			<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
		</tr>
	</table>
	<table width="725" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
		<tr><td width="15">&nbsp;</td>
		</tr>
		<?php
		if($_GET['equip_id']!="")
		{
			?>
			<tr>
				<td>&nbsp;&nbsp;</td>
				<td class="labelbold">Equipment Name</td>
				<td align="left" class="text"><input type="text" name="txt_equip_name_1" id="txt_equip_name_1" value="<?php echo @mysqli_result($rs_modify,0,'description');?>" size="35" class="label" /></td>
				<td width="76" class="labelbold">Type</td>
				<td width="198" align="left" class="text"><?php echo $type; ?></td>	
				<input type="hidden" name="type_hidden" id="type_hidden" value="<?php echo $type; ?>" />
			</tr>
			<?php
		}
		else
		{
			?>
			<tr>
				<td>&nbsp;&nbsp;</td>
				<td class="labelbold">Equipment Name</td>
				<td align="left" class="text"><input type="text" name="txt_equip_name" id="txt_equip_name" value="" size="35" class="label" /></td>
				<td width="76" class="labelbold">Type</td>
				<td width="198" align="left" class="text">
					<select name="cmb_type" id="cmb_type" onBlur="show_hour_unit()" class="label" >
						<option value="Select">Select</option>
						<option value="Percentage">Percentage</option>
						<option value="Hour">Hour</option> 
						<option value="Unit">Unit</option>
					</select>
			  	</td>
			</tr>
			<?php
		}
		?>
		
		<tr><td>&nbsp;&nbsp;</td></tr>
		
		<?php
		if(($_GET['equip_id']!="") && ($type!='Percentage'))
		{
			?>
			<tr id="type">
				<td>&nbsp;&nbsp;</td>
				<td width="124" class="labelbold">Rate/Hour (or) Unit Rate</td>
			  <td width="263" align="left" class="text"><input type="text" name="txt_rate_hour1" id="txt_rate_hour1" value="<?php if($_GET['equip_id']!="") echo @mysqli_result($rs_modify,0,'rate_hour'); else echo ''; ?>" size="25" class="label" /></td><!--  onBlur="return valid_erate()" -->
			</tr>
			<?php
		}
		else
		{
			?>
			<tr style="display:none" id="type">
				<td>&nbsp;&nbsp;</td>
				<td width="124" class="labelbold">Rate/Hour (or) Unit Rate</td>
			  <td width="263" align="left" class="text"><input type="text" name="txt_rate_hour" id="txt_rate_hour" value="<?php if($_GET['equip_id']!="") echo @mysqli_result($rs_modify,0,'rate_hour'); else echo ''; ?>" size="25" class="label" /></td><!--  onBlur="return valid_erate()" -->
			</tr>
			<?php
		}
		?>
		
		<tr><td>&nbsp;&nbsp;</td></tr>
		
		<tr align="center">
			<td colspan="7">
				<?php 
				if($_GET['equip_id']!="")
				{
					?>
					<input type="image" name="btn_update" id="btn_update" value="Update" src="Buttons/Update_Normal.png" onMouseOver="this.src='Buttons/Update_Over.png'" onMouseOut="this.src='Buttons/Update_Normal.png'" />&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="image" name="btn_back" id="btn_back" value="Back" src="Buttons/Back_Normal.png" onMouseOver="this.src='Buttons/Back_Over.png'" onMouseOut="this.src='Buttons/Back_Normal.png'" />&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="image" name="btn_delete" id="btn_delete" value="Delete" src="Buttons/Delete_Normal.png" onMouseOver="this.src='Buttons/Delete_Over.png'" onMouseOut="this.src='Buttons/Delete_Normal.png'" />
					<?php
				}
				else
				{
					?>
					<input type="image" name="btn_save" id="btn_save" value="Save" src="Buttons/Save_Normal.jpg" onMouseOver="this.src='Buttons/Save_Over.jpg'" onMouseOut="this.src='Buttons/Save_Normal.jpg'" onClick="return validation()" />&nbsp;&nbsp;
					<?php
				}
				?>
			</td>
		</tr>
		
		<tr><td>&nbsp;&nbsp;</td></tr>
	
	</table>
	<br  />
	<table width="725" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="heading">
			<td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
			<td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">List - Equipment Master</td>
			<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
		</tr>
	</table>
	<table width="725" border="1" align="center" cellpadding="0" cellspacing="0" class="color2">
		<tr>
			<td>&nbsp;</td>
<!--			<td class="labelboldcenter">Equipment ID</td>
-->			
			<td class="labelboldcenter">S. No.</td>
			<td class="labelboldcenter">Equipment Name</td>
			<td class="labelboldcenter">Type</td>
			<td class="labelboldcenter">Rate/Hour</td>
		</tr>
		<?php
			$sql_module="select * from equip_master order by equip_id";
			$rs_module=mysqli_query($dbConn,$sql_module,$conn);
			$row=0;
			while($rows=mysqli_fetch_assoc($rs_module))
			{
				echo "<tr height='25px'>";
				/*if($_GET['equip_id']==$rows['equip_id'])
					echo "<td class='labelcenter'><input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value='" . $row . "' checked /></td>";
				else
					echo "<td class='labelcenter'><input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value='" . $row . "' /></td>";
				*/
				if( ($rows['type']=='Rate') || ($rows['type']=='Value'))
					echo "<td class='labelcenter'><input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value='" . $row . "' disabled /></td>";
				else
				{
					if($_GET['equip_id']==$rows['equip_id'])
					echo "<td class='labelcenter'><input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value='" . $row . "' checked /></td>";
				else
					echo "<td class='labelcenter'><input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value='" . $row . "' /></td>";
					//echo "<td class='labelcenter'><input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value='" . $row . "' /></td>";
				}
				//echo "<td class='labelcenter'>" . $rows['equip_id'] . "</td>";
				echo "<td class='labelcenter'>" . ($row+1) . "</td>";
				echo "<td class='label'>&nbsp;" . $rows['description'] . "&nbsp;</td>";
				echo "<td class='label'>&nbsp;" . $rows['type'] . "&nbsp;</td>";
				
				if($rows['rate_hour']!='')
					echo "<td class='label'>&nbsp;" . $rows['rate_hour'] . "&nbsp;</td>";
				else
					echo "<td class='labelcenter'>-</td>";
				
				echo "<input type='hidden' name='equip_id" . $row. "' id='equip_id" . $row. "' value='" . $rows['equip_id'] . "' />";
				echo "<input type='hidden' name='row_no" . $row . "' id='row_no" . $row . "' value='" . $row . "' />";
				echo "</tr>";
				$row++;	
			}
		?>
		<input type="hidden" name="equip_code" id="equip_code" value="" />
		<input type="hidden" name="equip_rows" id="equip_rows" value="<?php echo ($row-1); ?>"  />
	</table>
							
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
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
});
</script>
