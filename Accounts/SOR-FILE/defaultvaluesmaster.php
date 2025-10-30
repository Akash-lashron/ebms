<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
/*if (isset($_POST['btn_update_x']))
{
	$sql_update="update group_datasheet  set group_desc ='" . $_POST['txt_group_II']    . "'
									where    group_id   ='" . $_POST['txt_groups_II']   . "'";
	$rs_update=mysqli_query($dbConn,$sql_update,$conn);
	echo $sql_update;exit;
	if($rs_update!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Updated")
			window.location="defaultvaluesmaster.php";
		</script>
		<?php
	}
	$rs_update='';
}*/

if($_GET['group_id_I']!="")
{
	$sql_group2="select group_id,group_desc from group_datasheet where group_id like '" . $_GET['group_id_I'] . "%'
							and char_length( group_id ) = '4' and delete_In='' order by group_id";
	$rs_group2=mysqli_query($dbConn,$sql_group2,$conn);
	
	if(@mysqli_result($rs_group2,0,'group_id')=='')
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("No Records Found");
			window.location="EW_edit.php";
		</script>
	<?php
	}
	
}

if($_GET['gp_id']!="")
{
	$group_I_Id=substr($_GET['gp_id'],0,2);
	
	$sql_modify="select * from group_datasheet where group_id='" . $_GET['gp_id'] . "'";
	$rs_modify=mysqli_query($dbConn,$sql_modify,$conn);
}

$sql_count="select count(ref_id) as ref_id from datasheet_master where group_id like '" . $_GET['gp_id'] . "%'";
$rs_count=mysqli_query($dbConn,$sql_count,$conn);
$ref_count=@mysqli_result($rs_count,0,'ref_id');
		
if(isset($_POST['btn_delete_x']))
{	
	if ($ref_count>0)
	{
		?>
			<script type="text/javascript" language="javascript">
				var x= <?php echo $ref_count; ?>;
				alert(("Under this Item ") + x +  (" dependencies are there. If you need to Delete, First you have to delete the dependencies under this Item in Group 3"));
			</script>
			<?php
	}
	else
	{
		$sql_delete="update group_datasheet set delete_In='D' where group_id like '" . $_GET['gp_id'] . "%'";
		$rs_delete=mysqli_query($dbConn,$sql_delete,$conn);

		if($rs_delete!="")
		{
 
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Deleted")
			window.location="EW_edit.php"
		</script>
		<?php
		}
	}
	
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
function func_group_id()
{
	if(document.form.cmb_group1.value == "Select")
	{
		alert("Please Select Group I");
		document.form.cmb_group1.focus();
		return false;
	}
	
	document.form.method="post";
	document.form.action="EW_group2_edit.php?group_id_I="+document.form.cmb_group1.value
	document.form.submit();
}

function update_validation()
{
	x=alltrim(document.form.txt_group_II.value)
	if(x.length==0)
	{
		alert("Please Enter the Group II")
		document.form.txt_group_II.value="";
		document.form.txt_group_II.focus();
		return false
	}
}
function fn_default_code()
{
	var rows=document.getElementById("gp_rows").value
	var x=0;
	if (rows>0)
	{
		for(x=0;x<=rows;x++)
		{
			if(document.form.rad[x].checked==true )
			{	
				document.form.gp_code.value=alltrim(document.getElementById("gp_id"+x).value);
			}
		}
	}
	else
	{
		document.form.gp_code.value=document.getElementById("gp_id"+x).value;
	}
	func_default_id()
}

function func_default_id()
{
	document.form.method="post";
	document.form.action="EW_group2_edit.php?gp_id="+document.form.gp_code.value
	document.form.submit();
}
function list_search(id,value)
{
	var found=false;
	cnt=document.getElementById(id).length
	
	for(x=0; x<cnt; x++ )
	{
		if ( document.getElementById(id).options(x).value == value)
		{
			document.getElementById(id).options(x).selected=true
			found=true;
			break;
		}
	}
	
	if (found==false)
		document.getElementById(id).options(cnt-1).selected=true
	
	return found;
}


function func_back()
{
	document.form.method="post";
	document.form.action="EW_group2_edit.php";
	document.form.submit();
}

</script>
<?php 
//checkUser();
$msg = ""; $del = 0;
$RowCount =0;
$staffid = $_SESSION['sid'];
if (isset($_POST['btn_save_x']))
{
	mysqli_select_db('estimator_sequence',$conn);
	$sql_de_id="SELECT nextval_default_master('de_id') as next_sequence";
	$rs_de_id=mysqli_query($dbConn,$sql_de_id,$conn);
	$de_id=@mysqli_result($rs_de_id,0,'next_sequence');
	
	mysqli_select_db('estimator',$conn);
	$sql_insert="insert into default_master(de_id,de_name,de_perc)
					values('" . $de_id . "' ,
						   '" . $_POST['txt_de_name'] . "' ,
						   '" . $_POST['txt_de_perc'] . "' )";
	$rs_insert=mysqli_query($dbConn,$sql_insert,$conn);
	//echo $sql_insert.'<br />';
}

if (isset($_POST['btn_update_x']))
{
	$sql_update="update default_master set de_perc='" . $_POST['txt_de_perc'] . "'
					where de_id='" . $_POST['default_del_id'] . "'"; // de_name='" . $_POST['txt_de_name'] . "' ,
	$rs_update=mysqli_query($dbConn,$sql_update,$conn);
	//echo $sql_update;exit;
	if($rs_update!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Updated")
			window.location="defaultvaluesmaster.php"
		</script>
		<?php
	}
	//unset($_GET['de_id']);
	//$rs_update='';
}

/*if (isset($_POST['btn_delete_x']))
{
	?>
	<script type="text/javascript" language="javascript">
	x=confirm("Are You sure you want Delete?")
	if (x==true)
	{
		url="defaultvaluesmaster.php?action=delete&de_id=<?php echo $_GET['de_id']; ?>"
		//alert(url)
		window.location.href="defaultvaluesmaster.php?action=delete&de_id=<?php echo $_GET['de_id']; ?>"
	}
	</script>
	<?php
}

if ($_GET['action']=="delete")
{
	$sql_del="delete from default_master where de_id='" . $_GET['de_id'] . "'";
	//echo $sql_del;
	$rs_del=mysqli_query($dbConn,$sql_del,$conn);
	if ($rs_del!="")
	{
		?>
		<script type="text/javascript" language="javascript">	
			alert("Successfully Deleted")
		</script>
		<?php
	}
	unset($_GET['de_id']);
	$rs_update='';
}*/


if($_GET['de_id']!="")
{
	$sql_modify="select * from default_master where de_id='" . $_GET['de_id'] . "'";
	//echo $sql_modify;
	$rs_modify=mysqli_query($dbConn,$sql_modify,$conn);
}

?>
<?php include "Header.html"; ?>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
<script type="text/javascript" language="javascript">
function save_validation()
{
	x=alltrim(document.form.txt_de_name.value)
	if(x.length==0)
	{
		alert("Please Enter the Name")
		document.form.txt_de_name.value="";
		document.form.txt_de_name.focus();
		return false
	}
	
	x=alltrim(document.form.txt_de_perc.value)
	if(x.length==0)
	{
		alert("Please Enter the Percentage")
		document.form.txt_de_perc.value="";
		document.form.txt_de_perc.focus();
		return false
	}
}


function fn_default_code()
{
	var rows=document.getElementById("default_rows").value
	var x=0;
	if (rows>0)
	{
		for(x=0;x<=rows;x++)
		{
			if(document.form.rad[x].checked==true )
			{	
				document.form.default_code.value=alltrim(document.getElementById("de_id"+x).value);
			}
		}
	}
	else
	{
		document.form.default_code.value=document.getElementById("de_id"+x).value;
	}
	func_default_id()
}

function func_default_id()
{
	document.form.method="post";
	document.form.action="defaultvaluesmaster.php?de_id="+document.form.default_code.value
	document.form.submit();
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
							<form name="form" method="post">
	<table width="725" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="heading">
			<td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
			<td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Taxes &amp; Overheads</td>
			<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
		</tr>
	</table>
	
	<table width="725" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
		<tr><td width="34">&nbsp;</td></tr>
		
		<?php
		if($_GET['de_id']!="")
		{
			?>
			<tr>
				<td>&nbsp;&nbsp;</td>
				<td width="142" class="label">Description</td>
				<td width="549" align="left" class="text"><input type="text" name="txt_de_name" id="txt_de_name" value="<?php echo @mysqli_result($rs_modify,0,'de_name'); ?>" size="25" class="label" readonly="" /></td>
			</tr>
			<?php
		}
		else
		{
			?>
			<tr>
				<td>&nbsp;&nbsp;</td>
				<td width="142" class="label">Description</td>
				<td width="549" align="left" class="text"><input type="text" name="txt_de_name" id="txt_de_name" value="" size="25" class="label" /></td>
			</tr>
			<?php
		}
		?>
		
		<tr><td>&nbsp;&nbsp;</td></tr>
		<tr>
			<td>&nbsp;</td>
			<td class="label">Percentage/Rate </td>
			<td class="text" align="left"><input type="text" name="txt_de_perc" id="txt_de_perc" value="<?php if($_GET['de_id']!="") echo @mysqli_result($rs_modify,0,'de_perc'); else echo ''; ?>" size="10" class="label" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		
		
		<tr align="center">
			<td>&nbsp;</td>
			<td colspan="3">
				<?php 
				if($_GET['de_id']!="")
				{
					?>
					<input type="hidden" name="default_del_id" id="default_del_id" value="<?php if($_GET['de_id']!="") echo @mysqli_result($rs_modify,0,'de_id'); else echo ''; ?>"/>
					<input type="image" name="btn_update" id="btn_update" value="Update" src="Buttons/Update_Normal.png" onMouseOver="this.src='Buttons/Update_Over.png'" onMouseOut="this.src='Buttons/Update_Normal.png'" />&nbsp;&nbsp;
					<!--<input type="image" name="btn_delete" id="btn_delete" value="Delete" src="Buttons/Delete_Normal.png" onMouseOver="this.src='Buttons/Delete_Over.png'" onMouseOut="this.src='Buttons/Delete_Normal.png'" />-->
					<?php
				}
				/*else
				{
					?>
					<input type="image" name="btn_save" id="btn_save" value="Save" src="Buttons/Save_Normal.jpg" onMouseOver="this.src='Buttons/Save_Over.jpg'" onMouseOut="this.src='Buttons/Save_Normal.jpg'" onClick="save_validation()" />&nbsp;&nbsp;
					<?php
				}*/
				?>
			</td>
		</tr>
		
	</table>
	<br />
	<br />
	<table width="725" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="heading">
			<td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
			<td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">List - Taxes &amp; Overheads</td>
			<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
		</tr>
	</table>
	<table width="725" border="1" align="center" cellpadding="0" cellspacing="0" class="color2">
		<tr>
			<td>&nbsp;</td>
			<td class="labelboldcenter">Ref ID</td>
			<td class="labelboldcenter">Description</td>
			<td class="labelboldcenter">Percentage</td>
		</tr>
		<?php
			$sql_module="select * from default_master order by de_id";
			$rs_module=mysqli_query($dbConn,$sql_module,$conn);
			$row=0;
			while($rows=mysqli_fetch_assoc($rs_module))
			{
				echo "<tr height='25px'>";
				if($_GET['de_id']==$rows['de_id'])
					echo "<td class='labelcenter'><input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value='" . $row . "' checked /></td>";
				else
					echo "<td class='labelcenter'><input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value='" . $row . "' /></td>";
				
				echo "<td class='labelcenter'>" . $rows['de_id'] . "</td>";
				//echo "<td class='label'><a onClick=func_ref_id() href='itemratemaster.php?de_id=" . $rows['de_id'] . "'>" . $rows['de_name'] . "</a></td>";
				echo "<td class='labelleft'>&nbsp;" . $rows['de_name'] . "&nbsp;</td>";
				echo "<td class='labelleft'>&nbsp;" . $rows['de_perc'] . "&nbsp;</td>";
				echo "<input type='hidden' name='de_id" . $row. "' id='de_id" . $row. "' value='" . $rows['de_id'] . "' />";
				echo "<input type='hidden' name='row_no" . $row . "' id='row_no" . $row . "' value='" . $row . "' />";
				echo "</tr>";
				$row++;	
			}
		?>
		<input type="hidden" name="default_code" id="default_code" value="" />
		<input type="hidden" name="default_rows" id="default_rows" value="<?php echo ($row-1); ?>"  />
	</table>
	</form>
							
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
