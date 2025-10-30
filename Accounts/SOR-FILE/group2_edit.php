<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
if (isset($_POST['btn_update_x']))
{
	$sql_update="update group_datasheet  set group_desc ='" . $_POST['txt_group_II']    . "',type ='" . $_POST['txt_type']    . "' where    id   ='" . $_POST['txt_groups_II']   . "'";
	$rs_update=mysqli_query($dbConn,$sql_update,$conn);
	if($rs_update!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Updated")
		</script>
		<?php
	}
	$rs_update='';
}
if (isset($_POST['btn_add_x']))
{
   	$cmb_group1=$_POST["cmb_group1"];
	$txt_group_II=$_POST["txt_group_II"];
	$txt_type=$_POST["txt_type"];
	$txt_groupid=$_POST["txt_groupid"];
	$Display=$_POST["ch_display"];
	if($Display != 'Y'){
		$Display = 'N';
	}
	//$rs_update=mysqli_query($dbConn,$sql_update,$conn);
	/*$sql_max_group2="select max(id) as id from group_datasheet where 
							id like '".$cmb_group1 ."%' and char_length(id) = '4'";
		$rs_max_group2=mysqli_query($dbConn,$sql_max_group2,$conn);
		
		
		$group_id_2=@mysqli_result($rs_max_group2,0,'id');
		if($group_id_2 == ""){
			$group_id_2 = $cmb_group1."01";
		}else{
			$group_id_2 = $group_id_2 + 1;
		}
		//echo $sql_max_group2;exit;
		if (strlen($group_id_2)==3){
			$group_id_2 = '0'.$group_id_2;		
		}else{
			$group_id_2 = $group_id_2;
		}*/
		//echo $group_id_2;
	if($rs_update!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Updated")
			window.location="group2_edit.php";
		</script>
		<?php
	}
	/*$ParId = 0;
	$SelectparIdQuery 	= "select id from group_datasheet where id = '$cmb_group1'";
	$SelectparIdSql 	= mysqli_query($dbConn,$SelectparIdQuery,$conn);
	if($SelectparIdSql == true){
		if(mysqli_num_rows($SelectparIdSql)>0){
			$IdList = mysqli_fetch_object($SelectparIdSql);
			$ParId 	= $IdList->id;
		}
	}*/
	//echo $ParId;exit;
	$rs_update='';
	$insert_query="insert into group_datasheet set group_desc='$txt_group_II', type='$txt_type', par_id = '$cmb_group1', disp = '$Display'";
   //echo $insert_query;exit;
	$insert_sql=mysqli_query($dbConn,$insert_query,$conn);
	if($insert_sql!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Updated")
			window.location="group2_edit.php";
		</script>
		<?php
	}
	$insert_sql='';
}
if(isset($_POST['btn_delete_x']))
{
    $id     = $_POST["txt_id"];
    $par_id = $_POST["txt_par_id"];
	$sel_query="select id,ref_id,par_id,id from datasheet_master where id='$id' and par_id ='$par_id'  ";
	$select_sql 	 = mysqli_query($dbConn,$sel_query,$conn);
	if($select_sql == true){
		 $ref_count = mysqli_num_rows($select_sql);
		 if(mysqli_num_rows($select_sql)>0){
			while($List = mysqli_fetch_object($select_sql)){
			$ref_id= $List->ref_id;
			}
		 }
		 if ($ref_count>0){?>
			<script type="text/javascript" language="javascript">
				var x= <?php echo $ref_count; ?>;
				alert(("Under this Item ") + x +  (" dependencies are there. If you need to Delete, First you have to delete the dependencies under this Item "));
			</script> <?php
		}else{
		$sql_delete="update group_datasheet set delete_In='D' where id = '" . $_POST['txt_groups_II'] . "'";
		$rs_delete=mysqli_query($dbConn,$sql_delete,$conn);
		if($rs_delete!="")
		{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Deleted")
		</script>
		<?php
		}
		}
	}
}
if($_GET['gp_id']!='') 
{
    $GlobGr1Ids = $_GET['gp_id'];
	$select_query ="select * from group_datasheet where id='" . $_GET['gp_id'] . "'";
	$select_sql=mysqli_query($dbConn,$select_query,$conn);
	
	$view 		= 1;  $count1 =0; $slno=0;
	$select_parId_query = "select * from group_datasheet where id ='$GlobGr1Ids' ";
	$SelectparIdSql 	= mysqli_query($dbConn,$select_parId_query);
	if($SelectparIdSql == true){
	$count1 	= mysqli_num_rows($SelectparIdSql);
		if(mysqli_num_rows($SelectparIdSql)>0){
			$IdList = mysqli_fetch_object($SelectparIdSql);
			$GlobGr1Id 	= $IdList->par_id;
		}
	}
	
}
$view = 0;
if (isset($_POST['btn_view_list_x']))
{
    $group1_id 	= $_POST['cmb_group1'];
	$view 		= 1;  $count1 =0; $slno=0;
	$select_parId_query = "select id from group_datasheet where id ='$group1_id' ";
	$SelectparIdSql 	= mysqli_query($dbConn,$select_parId_query);
	if($SelectparIdSql == true){
	$count1 	= mysqli_num_rows($SelectparIdSql);
		if(mysqli_num_rows($SelectparIdSql)>0){
			$IdList = mysqli_fetch_object($SelectparIdSql);
			$ParId 	= $IdList->id;
		}
	}
}	
if($_GET['group_id_II']!="")
{
	$GlobGr1Id  = $_GET['group_id_II'];
	$view 		= 1;  $count1 =0; $slno=0;
	$select_parId_query = "select * from group_datasheet where id ='" . $_GET['group_id_II'] . "'";
	$SelectparIdSql 	= mysqli_query($dbConn,$select_parId_query);
	if($SelectparIdSql == true){
	$count1 	= mysqli_num_rows($SelectparIdSql);
		if(mysqli_num_rows($SelectparIdSql)>0){
			$IdList = mysqli_fetch_object($SelectparIdSql);
			$ParId 	= $IdList->id;
		}
	}
	
}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<link href="font.css" rel="stylesheet" type="text/css" />
</head>
<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
 .labelboldleft {
     font-weight: normal;
 }
</style>	
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
							<table width="825" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr class="heading">
									<td width="20" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
									<td width="825" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Group II - Create</td>
									<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
								</tr>
							</table>
							<table width="825" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
								<tr><td width="18" style="line-height:13px;">&nbsp;</td>
								</tr>
								<tr>
									<td width="50">&nbsp;</td>
									<td class="labelbold" width="138">Group I</td>
									<td colspan="2">
									    <select class="text group" style="width:400px;height:21px;" name="cmb_group1" ID="cmb_group1" data-group = "1">
										    <option value="Select">-----------------Select Group I -----------------</option>
										    <?php echo $objBind->BindGroupI($GlobGr1Id,"ALL"); ?>
									    </select>
									</td>
								</tr>
								<tr><td style="line-height:13px;">&nbsp;&nbsp;</td></tr>
								
								<tr>
									<td width="50">&nbsp;</td>
									<td class="labelbold" width="138">Group II</td>
									<td width="357" align="left" class="text" colspan="3"><input type="text" name="txt_group_II" id="txt_group_II" value="<?php if($_GET['gp_id']!="") { echo @mysqli_result($select_sql,0,'group_desc');}elseif($_GET['gp_id']!=""){ echo @mysqli_result($SelectparIdSql,0,'group_desc');}else{ echo ''; }?>" size="53" /></td>
								</tr>
								<tr><td style="line-height:13px;">&nbsp;&nbsp;</td></tr>
								<tr>
									<td width="50">&nbsp;</td>
									
									<td class="labelbold" width="138">Type</td>
									<td width="357" align="left" class="text" colspan="3"><input type="text" name="txt_type" id="txt_type" value="<?php if($_GET['gp_id']!="") echo @mysqli_result($select_sql,0,'type'); else echo ''; ?>" size="53" /></td>
								</tr>
								<!--<tr><td>&nbsp;&nbsp;</td></tr>-->
								<tr>
									<td width="50">&nbsp;</td>
										
									<td class="labelbold" width="138">&nbsp;</td>
									<td width="357" align="left" class="text labelbold" colspan="3"><input type="checkbox" name="ch_display" id="ch_display" value="Y" checked="checked"/> &emsp;Display Group Code</td>
								</tr>
								<input type="hidden" name="txt_groups_II" id="txt_groups_II" value="<?php if($_GET['gp_id']!="") echo @mysqli_result($select_sql,0,'id'); else echo ''; ?>"/>										
								<tr><td>&nbsp;&nbsp;</td></tr>
								<tr align="center">
									<td colspan="5">
								<?php if($_GET['gp_id']!=""){ ?>
										<input type="hidden" name="txt_id" id="txt_id" value="<?php if($_GET['gp_id']!="") echo @mysqli_result($select_sql,0,'id');else echo ''; ?>" />	
										<input type="hidden" name="txt_par_id" id="txt_par_id" value="<?php if($_GET['gp_id']!="") echo @mysqli_result($select_sql,0,'par_id');else echo ''; ?>" />
										<center><input type="image" name="btn_back" id="btn_back" value="Back"  src="Buttons/Back_Normal.png" onMouseOver="this.src='Buttons/Back_Over.png'" onMouseOut="this.src='Buttons/Back_Normal.png'"  onclick="func_back()"/> &nbsp;&nbsp;&nbsp;<input type="image" name="btn_view_list" id="btn_view_list" value="View" src="Buttons/View_Normal.png" onMouseOver="this.src='Buttons/View_Over.png'" onMouseOut="this.src='Buttons/View_Normal.png'" onClick="return func_group_id2()"/> &nbsp;&nbsp;&nbsp; <input type="image" name="btn_update" id="btn_update" value="Update" src="Buttons/Update_Normal.png" onMouseOver="this.src='Buttons/Update_Over.png'" onMouseOut="this.src='Buttons/Update_Normal.png'" onClick="return update_validation()"/>&nbsp;&nbsp;&nbsp;<input type="image" name="btn_delete" id="btn_delete" value="Delete" src="Buttons/Delete_Normal.png" onMouseOver="this.src='Buttons/Delete_Over.png'" onMouseOut="this.src='Buttons/Delete_Normal.png'" onClick="return func_group_id2_del()"/></center>
								<?php }else{ ?>
										<center/><input type="image" name="btn_add" id="btn_add" value="View" src="Buttons/Add_Normal.png" onMouseOver="this.src='Buttons/Add_Over.png'" onMouseOut="this.src='Buttons/Add_Normal.png'" onClick="return func_group_id()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="image" name="btn_view_list" id="btn_view_list" value="View" src="Buttons/View_Normal.png" onMouseOver="this.src='Buttons/View_Over.png'" onMouseOut="this.src='Buttons/View_Normal.png'" onClick="return func_group_id2()"/>
										<!--<input type="image" name="btn_view" id="btn_view" value="View" src="Buttons/View_Normal.png" onMouseOver="this.src='Buttons/View_Over.png'" onMouseOut="this.src='Buttons/View_Normal.png'" onClick="return func_group_id()"/></center>-->
								<?php } ?>
									</td>
								</tr>
							   <tr><td>&nbsp;&nbsp;</td></tr>
							</table></br>
							<div align="center" <?php if($view == 0){ ?> style="display:none" <?php } ?>>
							<table width="825" border="0" align="center" cellpadding="0" cellspacing="0">
								 <tr class="heading">
									 <td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
									 <td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Group II List</td>
									 <td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
								 </tr>
							</table>
							<table width="825" border="1" align="center" cellpadding="0" cellspacing="0" class="color2">
								 <tr>
									 <td class="labelboldcenter" align="center">Sl.no.</td>
									 <td class="labelboldcenter" align="left"> Group Description</td>
									 <td class="labelboldcenter" align="center">Code</td>
									 <td class="labelboldcenter" align="center">Action</td>
								 </tr>
								 <?php $slno = 1; $count2 =0; if($count1 > 0){ 
									   $select_group2_query  = "select * from group_datasheet where par_id	='$ParId' and delete_In !='D'";
									   $select_group2_sql 	 = mysqli_query($dbConn,$select_group2_query);
										   if($select_group2_sql == true){
											$count2 	= mysqli_num_rows($select_group2_sql);
											if($count1 > 0){ 
											 if(mysqli_num_rows($select_group2_sql)>0){
												while($List = mysqli_fetch_object($select_group2_sql)){
								  ?>
								 <tr>
									 <td class="labelboldleft" style="text-align:center"><?php  echo $slno; ?></td>
									 <td class="labelboldleft" style="text-align:left"><?php  echo $List->group_desc; ?></td>
									 <td class="labelboldleft" style="text-align:center"><?php  echo $List->type; ?></td>
									 <td align="center" width="100" height="28" valign="middle"><a href="group2_edit.php?gp_id=<?php echo $List->id;?>" class="btn-primary" style="padding:3px 15px; border-radius:5px">Edit</a>
										<!--<input type="image" name="btn_edit" id="btn_edit" value="Edit" src="Buttons/Edit_Normal.png" onMouseOver="this.src='Buttons/Edit_Over.png'" onMouseOut="this.src='Buttons/Edit_Normal.png'" onClick="return func_group_id2()"/>-->
									 </td>
								 </tr>
								 <?php $slno++; } } } if($count2 == 0){ ?>
								 <tr><td colspan="6" align="center">No Records Found</td></tr>
								 <?php } ?> 
								 <?php }}?>
							</table><br/><br/>
							</div>
					        <?php if($_GET['gp_id']!=""){ ?>
							<div align="center">
							<table width="825" border="0" align="center" cellpadding="0" cellspacing="0">
								 <tr class="heading">
									 <td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
									 <td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Group II List</td>
									 <td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
								 </tr>
							</table>
							<table width="825" border="1" align="center" cellpadding="0" cellspacing="0" class="color2">
								 <tr>
									 <td class="labelboldcenter" align="center">Sl.no.</td>
									 <td class="labelboldcenter" align="left"> Group Description</td>
									 <td class="labelboldcenter" align="center">Code</td>
									 <td class="labelboldcenter" align="center">Action</td>
								 </tr>
								 <?php  ?>
								 <?php $slno = 1; $count2 =0;if($count1 > 0){ 
									   $select_group2_query  = "select * from group_datasheet where par_id	='$GlobGr1Id' and delete_In !='D'";
									   $select_group2_sql 	 = mysqli_query($dbConn,$select_group2_query);
										   if($select_group2_sql == true){
											$count2 	= mysqli_num_rows($select_group2_sql);
											if($count1 > 0){ 
											 if(mysqli_num_rows($select_group2_sql)>0){
												while($List = mysqli_fetch_object($select_group2_sql)){
								  ?>
								 <tr>
									 <td class="labelboldleft" style="text-align:center"><?php  echo $slno; ?></td>
									 <td class="labelboldleft" style="text-align:left"><?php  echo $List->group_desc; ?></td>
									 <td class="labelboldleft" style="text-align:center"><?php  echo $List->type; ?></td>
									 <td align="center" width="100" height="28" valign="middle"><a href="group2_edit.php?gp_id=<?php echo $List->id;?>" class="btn-primary" style="padding:3px 15px; border-radius:5px">Edit</a>
										<!--<input type="image" name="btn_edit" id="btn_edit" value="Edit" src="Buttons/Edit_Normal.png" onMouseOver="this.src='Buttons/Edit_Over.png'" onMouseOut="this.src='Buttons/Edit_Normal.png'" onClick="return func_group_id2()"/>-->
									 </td>
								 </tr>
								 <?php $slno++; } } } if($count2 == 0){ ?>
								 <tr><td colspan="6" align="center">No Records Found</td></tr>
								 <?php } ?> 
								 <?php }}?>
							</table>
							<br/><br/>
							</div>
					        <?php }?>
	                  </blockquote>
					</div>
				</div>
			</div>
			<?php include "footer/footer.html"; ?>
        </form>
<script type="text/javascript" language="javascript">
function func_group_id()
{
	
	if(document.form.cmb_group1.value == "Select")
	{
		alert("Please Select Group I");
		document.form.cmb_group1.focus();
		return false;
	}
	x=alltrim(document.form.txt_group_II.value)
	if(x.length==0)
	{
		alert("Please Enter the Group II")
		document.form.txt_group_II.value="";
		document.form.txt_group_II.focus();
		return false
	}
	
	document.form.method="post";
	document.form.action="group2_edit.php?group_id_I="+document.form.cmb_group1.value
	document.form.submit();
}

function update_validation()
{
    if(document.form.cmb_group1.value == "Select")
	{
		alert("Please Select Group I");
		document.form.cmb_group1.focus();
		return false;
	}
	x=alltrim(document.form.txt_group_II.value)
	if(x.length==0)
	{
		alert("Please Enter the Group II")
		document.form.txt_group_II.value="";
		document.form.txt_group_II.focus();
		return false
	}
	document.form.method="post";
	document.form.action="group2_edit.php?group_id_II="+document.form.cmb_group1.value
	document.form.submit();
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
	document.form.action="group2_edit.php?gp_id="+document.form.gp_code.value
	document.form.submit();
}
function list_search(id,value)
{
	var found=false;
	cnt=document.getElementById(id).length
	alert(cnt)
	for(x=0; x<cnt; x++ )
	{
		if ( document.getElementById(id).options[x].value == value)
		{
			document.getElementById(id).options[x].selected=true
			found=true;
			break;
		}
	}
	
	if (found==false)
		document.getElementById(id).options[cnt-1].selected=true
	
	return found;
}


function func_back()
{
	document.form.method="post";
	document.form.action="group2_edit.php";
	document.form.submit();
}
function func_group_id2()
{
	if(document.form.cmb_group1.value == "Select")
	{
		alert("Please Select Group I");
		document.form.cmb_group1.focus();
		return false;
	}
	
	document.form.method="post";
	document.form.action="group2_edit.php?group_id_II="+document.form.cmb_group1.value
	document.form.submit();
}
function func_group_id2_del()
{
    document.form.method="post";
	document.form.action="group2_edit.php?group_id_II="+document.form.cmb_group1.value
	document.form.submit();
	
}
</script>
</body>
</html>
