<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
if (isset($_POST['btn_update_x']))
{
	$sql_update="update hr_master set hr_id='" . $_GET['hr_id'] . "' ,
					hr_desc='" . $_POST['txt_hr_name'] . "' ,
					rate_day='" . $_POST['txt_rate_day'] . "'
					where hr_id='" . $_GET['hr_id'] . "'";
	$rs_update=mysqli_query($dbConn,$sql_update,$conn);
	//rate_hour='" . $_POST['txt_rate_hour'] . "' ,

	
	if($rs_update!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Updated")
			window.location="humanresourcemaster.php"
		</script>
		<?php
	}
	unset($_GET['hr_id']);
	$rs_update='';
}

/*if(isset($_POST['btn_delete_x']))
{
	?>
	<script type="text/javascript" language="javascript">
	x=confirm("Are you sure want to delete?")
	if(x==true)
	{
		url="humanresourcemaster.php?action=delete&hr_id=<?php echo $_GET['hr_id'] ?>"
		window.location.href="humanresourcemaster.php?action=delete&hr_id=<?php echo $_GET['hr_id'] ?>"
	}
	</script>
	<?php 
}

if($_GET['action']=="delete")
{
	$sql_delete="delete from hr_master where hr_id='" . $_GET['hr_id'] . "'";
	$rs_delete=mysqli_query($dbConn,$sql_delete,$conn);
	if($rs_delete!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Deleted")
			window.location="humanresourcemaster.php"
		</script>
		<?php
	}
	unset($_GET['hr_id']);
	unset($_GET['action']);
	//$_GET['hr_id']='';
	//$_GET['action']='';
	$rs_delete='';
}*/

if (isset($_POST['btn_save_x']))
{
	$sql_insert="insert into hr_master(hr_id,hr_desc,rate_day) 
					values('" . $_POST['txt_hr_id'] . "',
						   '" . $_POST['txt_hr_name'] . "',
						   '" . $_POST['txt_rate_day'] . "' )";
	$rs_insert=mysqli_query($dbConn,$sql_insert,$conn);
	//echo $sql_insert.'<br />';
	if($rs_insert!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Saved")
			window.location="humanresourcemaster.php"
		</script>
		<?php
	}
	$rs_insert='';
}


if($_GET['hr_id']!="")
{
	$sql_modify="select * from hr_master where hr_id='" . $_GET['hr_id'] . "'";
	//echo $sql_modify;
	$rs_modify=mysqli_query($dbConn,$sql_modify,$conn);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="font.css" rel="stylesheet" type="text/css" />
</head>
<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
<script type="text/javascript" language="javascript">
function save_validation()
{	
	x=alltrim(document.form.txt_hr_id.value)
	if(x.length==0)
	{
		alert("Please Enter the Name")
		document.form.txt_hr_id.value="";
		document.form.txt_hr_id.focus();
		return false
	}
	
	
}

function fn_default_code()
{
	var rows=document.getElementById("hr_rows").value
	var x=0;
	if (rows>0)
	{
		for(x=0;x<=rows;x++)
		{
			if(document.form.rad[x].checked==true )
			{	
				document.form.hr_code.value=alltrim(document.getElementById("hr_id"+x).value);
			}
		}
	}
	else
	{
		document.form.hr_code.value=document.getElementById("hr_id"+x).value;
	}
	func_default_id()
}

function func_default_id()
{
	document.form.method="post";
	document.form.action="humanresourcemaster.php?hr_id="+document.form.hr_code.value
	document.form.submit();
}
</script>
//checkUser();
$msg = ""; $del = 0;
$RowCount =0;
$staffid = $_SESSION['sid'];
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
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
			<td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Human Resourse Master</td>
			<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
		</tr>
	</table>
	<table width="725" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
		<tr><td width="18">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td width="97" class="labelbold">HR ID</td>
			<td width="138" align="left" class="text"><input type="text" name="txt_hr_id" id="txt_hr_id" value="<?php if($_GET['hr_id']!="") echo @mysqli_result($rs_modify,0,'hr_id'); else echo ''; ?>" size="10" class="label" /></td>
			<td width="115" class="labelbold">HR Name</td>
		  <td width="357" align="left" class="text"><input type="text" name="txt_hr_name" id="txt_hr_name" value="<?php if($_GET['hr_id']!="") echo @mysqli_result($rs_modify,0,'hr_desc'); else echo ''; ?>" size="35" class="label" /></td>
		</tr>
		
		<tr><td>&nbsp;&nbsp;</td></tr>
		
		<tr>
			<td>&nbsp;&nbsp;</td>
			<!--<td width="97" class="labelbold">Rate/hour</td>
			<td width="138" align="left" class="text"><input type="text" name="txt_rate_hour" id="txt_rate_hour" value="<?php if($_GET['hr_id']!="") echo @mysqli_result($rs_modify,0,'rate_hour'); else echo ''; ?>" size="10" /></td>-->
			<td width="115" class="labelbold">Rate/day</td>
		  	<td width="357" align="left" class="text" colspan="3"><input type="text" name="txt_rate_day" id="txt_rate_day" value="<?php if($_GET['hr_id']!="") echo @mysqli_result($rs_modify,0,'rate_day'); else echo ''; ?>" size="10" class="label" /></td>
		</tr>
		
		<tr><td>&nbsp;&nbsp;</td></tr>
		
		<tr align="center">
			<td colspan="5">
				<?php 
				if($_GET['hr_id']!="")
				{
					?>
					<input type="image" name="btn_update" id="btn_update" value="Update" src="Buttons/Update_Normal.png" onMouseOver="this.src='Buttons/Update_Over.png'" onMouseOut="this.src='Buttons/Update_Normal.png'" />&nbsp;&nbsp;&nbsp;&nbsp;
					<!-- <input type="image" name="btn_delete" id="btn_delete" value="Delete" src="Buttons/Delete_Normal.png" onMouseOver="this.src='Buttons/Delete_Over.png'" onMouseOut="this.src='Buttons/Delete_Normal.png'" />-->
					<?php
				}
				else
				{
					?>
					<input type="image" name="btn_save" id="btn_save" value="Save" src="Buttons/Save_Normal.jpg" onMouseOver="this.src='Buttons/Save_Over.jpg'" onMouseOut="this.src='Buttons/Save_Normal.jpg'" onClick="save_validation()" />&nbsp;&nbsp;
					<?php
				}
				?>
			</td>
	
	</table>
	<br  />
	<table width="725" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="heading">
			<td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
			<td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Human Resourse Master</td>
			<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
		</tr>
	</table>
	<table width="725" border="1" align="center" cellpadding="0" cellspacing="0" class="color2">
		<tr>
			<td>&nbsp;</td>
			<td class="labelboldcenter">HR Id</td>
			<td class="labelboldcenter">HR Name</td>
			<!--<td class="labelboldcenter">Rate/hour</td>-->
			<td class="labelboldcenter">Rate/Day</td>
		</tr>
		<?php
			$sql_module="select * from hr_master order by hr_id";
			$rs_module=mysqli_query($dbConn,$sql_module,$conn);
			$row=0;
			while($rows=mysqli_fetch_assoc($rs_module))
			{
				echo "<tr height='25px'>";
				
				if($_GET['hr_id']==$rows['hr_id'])
					echo "<td class='labelcenter'><input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value='" . $row . "' checked /></td>";
				else
					echo "<td class='labelcenter'><input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value='" . $row . "' /></td>";
				
				echo "<td class='labelcenter'>" . $rows['hr_id'] . "</td>";
				echo "<td class='label'>&nbsp;" . $rows['hr_desc'] . "&nbsp;</td>";
				//echo "<td class='label'>&nbsp;" . $rows['rate_hour'] . "&nbsp;</td>";
				echo "<td class='label'>&nbsp;" . $rows['rate_day'] . "&nbsp;</td>";
				echo "<input type='hidden' name='hr_id" . $row. "' id='hr_id" . $row. "' value='" . $rows['hr_id'] . "' />";
				echo "<input type='hidden' name='row_no" . $row . "' id='row_no" . $row . "' value='" . $row . "' />";
				echo "</tr>";
				$row++;	
			}
		?>
		<input type="hidden" name="hr_code" id="hr_code" value="" />
		<input type="hidden" name="hr_rows" id="hr_rows" value="<?php echo ($row-1); ?>"  />
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
