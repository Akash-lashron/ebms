<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";

$modify=false;

if ($_GET['page']=='external_general_create.php')
{
	$url='external_general_create.php';
}
if ($_GET['page']=='external_general_edit.php')
{
	$url='external_general_edit.php';
}
if ($_GET['page']=='external_general_view.php')
{
	$url='external_general_view.php';
}

if ($_GET['page']=='external_retrieval_create.php')
{
	$url='external_retrieval_create.php';
}
if ($_GET['page']=='external_retrieval_edit.php')
{
	$url='external_retrieval_edit.php';
}
if ($_GET['page']=='external_retrieval_view.php')
{
	$url='external_retrieval_view.php';
}


if ($_GET['page']=='external_retrieval_relaying_create.php')
{
	$url='external_retrieval_relaying_create.php';
}
if ($_GET['page']=='external_retrieval_relaying_edit.php')
{
	$url='external_retrieval_relaying_edit.php';
}
if ($_GET['page']=='external_retrieval_relaying_view.php')
{
	$url='external_retrieval_relaying_view.php';
}

if ($_GET['page']=='internal_wiring_create.php')
{
	$url='internal_wiring_create.php';
}
if ($_GET['page']=='internal_wiring_edit.php')
{
	$url='internal_wiring_edit.php';
}
if ($_GET['page']=='internal_wiring_view.php')
{
	$url='internal_wiring_view.php';
}

if ($_GET['page']=='internal_conduit_laying_create.php')
{
	$url='internal_conduit_laying_create.php';
}
if ($_GET['page']=='internal_conduit_laying_edit.php')
{
	$url='internal_conduit_laying_edit.php';
}
if ($_GET['page']=='internal_conduit_laying_view.php')
{
	$url='internal_conduit_laying_view.php';
}

if ($_GET['page']=='internal_mcb_create.php')
{
	$url='internal_mcb_create.php';
}
if ($_GET['page']=='internal_mcb_edit.php')
{
	$url='internal_mcb_edit.php';
}
if ($_GET['page']=='internal_mcb_view.php')
{
	$url='internal_mcb_view.php';
}

if ($_GET['page']=='internal_without_supply_create.php')
{
	$url='internal_without_supply_create.php';
}
if ($_GET['page']=='internal_without_supply_edit.php')
{
	$url='internal_without_supply_edit.php';
}
if ($_GET['page']=='internal_without_supply_view.php')
{
	$url='internal_without_supply_view.php';
}


if ($_GET['page']=='maintenance_create.php')
{
	$url='maintenance_create.php';
}
if ($_GET['page']=='maintenance_edit.php')
{
	$url='maintenance_edit.php';
}
if ($_GET['page']=='maintenance_view.php')
{
	$url='maintenance_view.php';
}


if ($_GET['page']=='maintenance_type2_create.php')
{
	$url='maintenance_type2_create.php';
}
if ($_GET['page']=='maintenance_type2_edit.php')
{
	$url='maintenance_type2_edit.php';
}
if ($_GET['page']=='maintenance_type2_view.php')
{
	$url='maintenance_type2_view.php';
}

if ($_GET['page']=='EW_create.php')
{
	$url='EW_create.php';
}


if($_POST['btn_search']=='Search')
{
	$modify=true;
	
	if ($_GET['page']=='maintenance_type2_create.php')
	{
		$type='maintenance_type2';
	}
	else if ($_GET['page']=='maintenance_type2_edit.php')
	{
		$type='maintenance_type2';
	}
	else if ($_GET['page']=='maintenance_type2_view.php')
	{
		$type='maintenance_type2';
	}
	
	else if ($_GET['page']=='external_retrieval_create.php')
	{
	//echo "else if";
		$type='external_retrieval';
	}
	else if ($_GET['page']=='external_retrieval_edit.php')
	{
		$type='external_retrieval';
	}
	else if ($_GET['page']=='external_retrieval_view.php')
	{
		$type='external_retrieval';
	}

	else if ($_GET['page']=='external_retrieval_relaying_create.php')
	{
		$type='external_retrieval_relaying';
	}
	else if ($_GET['page']=='external_retrieval_relaying_edit.php')
	{
		$type='external_retrieval_relaying';
	}
	else if ($_GET['page']=='external_retrieval_relaying_view.php')
	{
		$type='external_retrieval_relaying';
	}
	else if ($_GET['page']=='EW_create.php')
	{
		$type='EWT1';
	}
	else if ($_GET['page']=='EW_edit.php')
	{
		$type='EWT1';
	}
	else if ($_GET['page']=='EW_view.php')
	{
		$type='EWT1';
	}
	else
	{
	
		$type=$_GET['type'];
	}
	
	$sql_select="select * from datasheet_master where group_id  like '" . $_POST['cmb_group2'] . "%' and 
					type='" . $type . "' order by group_id";
	/*$sql_select="select * from datasheet_master where group_id  like '" . $_POST['cmb_group2'] . "%' and 
					type='" . $type . "' and group4_description=''  order by group_id";*/
	$rs_select=mysqli_query($dbConn,$sql_select,$conn);
	//echo "sql_select-".$sql_select.'</br>';exit;
	
	$sql_select_group4="select distinct(group_id) from datasheet_master where group_id  like '" . $_POST['cmb_group2'] . "%' and 
					type='" . $type . "' and group4_description!=''  order by group_id";
	$rs_select_group4=mysqli_query($dbConn,$sql_select_group4,$conn);
	//echo "sql_select_group4-".$sql_select_group4.'</br>';
}


?>

<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ECMS</title>
<link rel="stylesheet" href="font.css" />
</head>

<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
<script language="javascript" type="text/javascript">

function func_group2()
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
	//alert("find_group2.php?group_id="+document.form.cmb_group1.value)
	strURL="find_group2.php?group_id="+document.form.cmb_group1.value
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
				document.form.cmb_group2.value='Select';	
			}
			else
			{
				var name=data.split("*");
				document.form.cmb_group2.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_group2.options.add(optn)
				
				var c= name.length 
				var a=c/2;
				var b=a+1;
				
				for(i=1,j=b;i<a,j<c;i++,j++)
				{
					var optn=document.createElement("option")
					optn.value=name[i];
					optn.text=name[j];
					document.form.cmb_group2.options.add(optn)
				}
			}
		}
	}
	xmlHttp.send(strURL);	
}

function code_link()
{
	//netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserWrite"); --- For Chrome
	//window.open('','_self',''); 
	window.close(); 
}

</script>


<body>
<form name="form" method="post">

<?php
if($modify==false)
{
	?>
	<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#b3b2b2">
		<tr class="heading">
			<td width="20" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
			<td width="444" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Select Item</td>
			<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
		</tr>
	</table>
	
	<table width="500" border="0" cellpadding="0" cellspacing="0" bgcolor="#c2c1c1" align="center">
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td width="91" class="label">&nbsp;&nbsp;Group 1</td>
			<td width="409">
			<?php
			$sql_group1="select * from group_datasheet where char_length( group_id ) = '2' and 
							type='" . $_GET['type'] . "' order by group_id";
			//echo "1-----".$sql_group1;				
			$rs_group1=mysqli_query($dbConn,$sql_group1,$conn);
			?>
			<select class="text" style="width:400px;height:21px;" name="cmb_group1" ID="cmb_group1" onChange="func_group2()">
				<option value="Select">Select</option>
				<?php
				while($row=mysqli_fetch_assoc($rs_group1))
				{
					?>
						<option value="<?php echo $row['group_id'];?>"><?php echo $row['group_desc'];?></option>
					<?php
				}
			?>
			</select>		  </td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		
		<tr>
			<td width="91" class="label">&nbsp;&nbsp;Group 2</td>
			<td width="409">
				<select class="text" style="width:400px;height:21px;" name="cmb_group2" ID="cmb_group2">
					<option value="Select">Select</option>
				</select>		  </td>
		</tr>
		<tr><td>&nbsp;</td></tr>
				
		<tr align="center">
		  	<td colspan="2"><input type="submit" class="text" name="btn_search" id="btn_search" value="Search" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<?php
}

else
{
	?>
	<table width="500" border="1" bordercolor="#CCCCCC" align="center" cellpadding="0" cellspacing="0" bgcolor="#c2c1c1">
		<tr class="labelbold" height="25">
			<td width="69" class="labelboldcenter">S.No</td>
			<td width="69" class="labelboldcenter">Ref ID -EW</td>
			<td width="425" class="labelboldcenter">Item Description</td>
		</tr>
		<?php
		$sno=1;
		while ($rows=mysqli_fetch_assoc($rs_select))
		{
			//$description=$rows['group3_description'];
			
			$sql_group3_desc="select group_desc from group_datasheet where group_id='" . $rows['group_id'] . "' and delete_In=''";	
			$rs_group3_desc=mysqli_query($dbConn,$sql_group3_desc,$conn);
			//echo "sql_group3_desc=".$sql_group3_desc.'</br>';
			echo "<tr height='25'>";
			echo "<td class='labelcenter'>" . $sno . '.' . "</td>";
			echo "<td class='labelcenter'>" . $rows['ref_id'] . "</td>";
			//echo "<td class='label'><a target='links' onClick=code_link() href='$url?ref_id=" . $rows['ref_id'] . "'><font color='black'>" . $description . "</font></a></td>";
			echo "<td class='label'><a target='links' onClick=code_link() href='$url?ref_id=" . $rows['ref_id'] . "'><font color='black'>" . @mysqli_result($rs_group3_desc,0,'group_desc') . "</font></a></td>";
			echo "</tr>";
			$sno++;
		}
		
		/*while ($rows=mysqli_fetch_assoc($rs_select_group4))
		{
			$sql_group3="select * from group_datasheet where group_id='" . $rows['group_id'] . "' and delete_In=''";
			$rs_group3=mysqli_query($dbConn,$sql_group3,$conn);
			//echo "sql_group3=".$sql_group3.'</br>';
			$description=@mysqli_result($rs_group3,0,'group_desc');
				
			echo "<tr height='25'>";
			echo "<td class='labelcenter'>" . $sno . '.' . "</td>";
			echo "<td class='labelcenter'>&nbsp;</td>";
			echo "<td class='label'>" . @mysqli_result($rs_group3,0,'group_desc') . "</td>";
			echo "</tr>";
			
			$sql_select_1="select * from datasheet_master where group_id='" . $rows['group_id'] . "' and
							group4_description!='' and ds_release ='Y' order by ref_id";
							
			//echo $sql_select_1;				
			$rs_select_1=mysqli_query($dbConn,$sql_select_1,$conn);
			
			
			
			$sno_2=1;
			while ($rows=mysqli_fetch_assoc($rs_select_1))
			{
				$sno_2_dis=$sno . '.' . $sno_2;
				$description=$rows['group4_description'];
				
				echo "<tr height='25'>";
				echo "<td class='labelcenter'>" . $sno_2_dis . '.' . "</td>";
				echo "<td class='labelcenter'>" . $rows['ref_id'] . "</td>";
				echo "<td class='label'><a target='links' onClick=code_link() href='$url?ref_id=" . $rows['ref_id'] . "'><font color='black'>" . $description . "</font></a></td>";
				echo "</tr>";
				$sno_2++;
			}
			$sno++;
		}*/
		?>
		
		<tr><td>&nbsp;</td></tr>
	</table>
	<br />

	<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr align="center"><td><input type="image" name="btn_back" id="btn_back" value="Back" src="Buttons/Back_Normal.png"  onmouseover="this.src='Buttons/Back_Over.png'" onMouseOut="this.src='Buttons/Back_Normal.png'" /></td></tr>
	</table>
	<?php
}
?>

</form>
</body>
</html>
