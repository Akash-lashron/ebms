<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
//checkUser();
$msg = ""; $del = 0;
$RowCount =0;
$staffid = $_SESSION['sid'];
if (isset($_POST['btn_save_single_x']))
{
	$txt_groupname = $_POST["txt_groupname"];
	$txt_type      = $_POST["txt_type"];
	$txt_groupid   = $_POST["txt_groupid"];
		$insert_query="insert into group_datasheet set type='$txt_type',group_desc='$txt_groupname'";
		$insert_sql=mysqli_query($dbConn,$insert_query,$conn);
		if($insert_sql!="")
		{
			?>
			<script type="text/javascript" language="javascript">
				alert("Successfully Saved")
				window.location="group1.php";
			</script>
			<?php
		}
		$insert_sql='';
}
if($_GET['grpid']!='') 
{
    $group_I_Id=$_GET['grpid'];
	$select_query ="select * from group_datasheet where id='" . $_GET['grpid'] . "'";
	$select_sql=mysqli_query($dbConn,$select_query,$conn);
	
	$desc 	= $_POST['txt_groupname'];
	$view 		= 1;  $count2 =0; $slno=0;
	$select_group2_query = "select * from group_datasheet where par_id='' and delete_In	!='D'";
	$select_group2_sql 	= mysqli_query($dbConn,$select_group2_query);
	if($select_group2_sql == true){
		$count2 	= mysqli_num_rows($select_group2_sql);
	}
}
if (isset($_POST['btn_update_x']))
{
	$sql_update="update group_datasheet  set group_desc ='" . $_POST['txt_groupname']    . "',type ='" . $_POST['txt_type']    . "' where    id   ='" . $_POST['txt_groupid']   . "'";
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
$view = 0;
if (isset($_POST['btn_view_list_x']))
{
    $desc 	= $_POST['txt_groupname'];
	$view 		= 1;  $count1 =1; $slno=0;
	$select_group1_query = "select * from group_datasheet where par_id='' and delete_In	!='D'";
	//echo $select_group1_query;
	$select_group1_sql 	= mysqli_query($dbConn,$select_group1_query);
	if($select_group1_sql == true){
		$count1 	= mysqli_num_rows($select_group1_sql);
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
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
							<table width="725" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr class="heading">
									<td width="20" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
									<td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Group I - Create</td>
									<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
								</tr>
							</table>
							<table width="725" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
								<tr><td width="18">&nbsp;</td>
								</tr>
								<tr>
									<td width="50">&nbsp;</td>
									<td class="labelbold" width="148">Group 1</td>
									<td colspan="2">
                                        <input type="text" id="txt_groupname" name="txt_groupname" value="<?php if($_GET['grpid']!="") echo @mysqli_result($select_sql,0,'group_desc'); else echo ''; ?>">
                                    </td>
								
									<td width="50">&nbsp;</td>
									<td class="labelbold" width="148">Type</td>
									<td width="357" align="left" class="text" colspan="3">
										  <input type="text" id="txt_type" name="txt_type" value="<?php if($_GET['grpid']!="") echo @mysqli_result($select_sql,0,'type'); else echo ''; ?>">
										  <input type="hidden" id="txt_groupid" name="txt_groupid" value="<?php if($_GET['grpid']!="") echo @mysqli_result($select_sql,0,'id'); else echo ''; ?>">
										  <input type="hidden" id="text_up_id" name="text_up_id" value="<?php if($_GET['grpid']!="") echo @mysqli_result($select_sql,0,'id'); else echo ''; ?>">
									</td>
									<td width="50">&nbsp;</td>
								</tr>
								<tr><td>&nbsp;&nbsp;</td></tr>
							</table><br/>
							<?php if($_GET['grpid']!=""){?>	
									<center><input type="image" name="btn_back" id="btn_back" value="Back"  src="Buttons/Back_Normal.png" onMouseOver="this.src='Buttons/Back_Over.png'" onMouseOut="this.src='Buttons/Back_Normal.png'"  onclick="func_back()"/> &nbsp;&nbsp;&nbsp;<input type="image" name="btn_update" id="btn_update" value="Update" src="Buttons/Update_Normal.png" onMouseOver="this.src='Buttons/Update_Over.png'" onMouseOut="this.src='Buttons/Update_Normal.png'" onClick="return update_validation()"/>&nbsp;&nbsp;&nbsp;</center>
							<?php }else{?>
							     <input type="image" name="btn_save_single" id="btn_save_single" value="Save" src="Buttons/Save_Normal.jpg" onMouseOver="this.src='Buttons/Save_Over.jpg'" onMouseOut="this.src='Buttons/Save_Normal.jpg'"  onClick="return validation()"/>
							     <input type="image" name="btn_view_list" id="btn_view_list" value="View" src="Buttons/View_Normal.png" onMouseOver="this.src='Buttons/View_Over.png'" onMouseOut="this.src='Buttons/View_Normal.png'" onClick="return func_group_id2()"/>
							<?php }?>
							<div align="center" <?php if($view == 0){ ?> style="display:none" <?php } ?>>
								<table width="725" border="0" align="center" cellpadding="0" cellspacing="0">
									 <tr class="heading">
										 <td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
										 <td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Group I List</td>
										 <td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
									 </tr>
								</table>
								<table width="725" border="1" align="center" cellpadding="0" cellspacing="0" class="color2">
									 <tr>
										 <td class="labelboldcenter" align="center">Slno.</td>
										 <td class="labelboldcenter" style="text-align:left">Group Description</td>
										 <td class="labelboldcenter" align="center">Code</td>
										 <td class="labelboldcenter" align="center">Action</td>
									 </tr>
									 <?php //if($count1 > 0){ ?>
									 <?php $slno = 1; if($count1 > 0){ while($List = mysqli_fetch_object($select_group1_sql)){ ?>
									 <tr>
										 <td class="labelboldleft" style="text-align:center"><?php  echo $slno; ?></td>
										 <td class="labelboldleft" style="text-align:left"><?php  echo $List->group_desc; ?></td>
										 <td class="labelboldleft"style="text-align:center"><?php  echo $List->type; ?></td>
										  <td align="center" width="100" height="28" style="vertical-align:middle"><a href="group1.php?grpid=<?php echo $List->id;?>" class="btn-primary" style="padding:3px 15px; border-radius:5px">Edit</a>
										  <!--<input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value="<?php echo $List->id;?>"  />-->
											<!--<input type="image" name="btn_edit" id="btn_edit" value="Edit" class="edit" data-grpid="<?php  echo $List->id;  ?>" src="Buttons/Edit_Normal.png" onMouseOver="this.src='Buttons/Edit_Over.png'" onMouseOut="this.src='Buttons/Edit_Normal.png'" onClick="return func_group_1()"/>-->
											<input type="hidden" name="group_code" id="group_code" value="" />
											<input type="hidden" id="edit_groupid" name="edit_groupid" value="<?php  echo $List->id;?>">
							
										 </td>
									 </tr>
									 <?php $slno++; } }else{ ?>
									 <tr><td colspan="6" align="center">No Records Found</td></tr>
									 <?php }?>
								</table>
							</div>
							<?php if($_GET['grpid']!=""){ ?>
							<div align="center">
								<table width="725" border="0" align="center" cellpadding="0" cellspacing="0">
									 <tr class="heading">
										 <td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
										 <td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Group I List</td>
										 <td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
									 </tr>
								</table>
								<table width="725" border="1" align="center" cellpadding="0" cellspacing="0" class="color2">
									 <tr>
										 <td class="labelboldcenter" align="center">Slno.</td>
										 <td class="labelboldcenter" style="text-align:left">Group Description</td>
										 <td class="labelboldcenter" align="center">Code</td>
										 <td class="labelboldcenter" align="center">Action</td>
									 </tr>
									 <?php //if($count1 > 0){ ?>
									 <?php $slno = 1; if($count2 > 0){ while($List = mysqli_fetch_object($select_group2_sql)){ //echo $select_group1_query; ?>
									 <tr>
										 <td class="labelboldleft" style="text-align:center"><?php  echo $slno; ?></td>
										 <td class="labelboldleft" style="text-align:left"><?php  echo $List->group_desc; ?></td>
										 <td class="labelboldleft"style="text-align:center"><?php  echo $List->type; ?></td>
										  <td align="center" width="100" height="28" style="vertical-align:middle"><a href="group1.php?grpid=<?php echo $List->id;?>" class="btn-primary" style="padding:3px 15px; border-radius:5px">Edit</a>
										  <!--<input type='radio' name='rad'  id='rad' onclick='fn_default_code()' value="<?php echo $List->id;?>"  />-->
											<!--<input type="image" name="btn_edit" id="btn_edit" value="Edit" class="edit" data-grpid="<?php  echo $List->id;  ?>" src="Buttons/Edit_Normal.png" onMouseOver="this.src='Buttons/Edit_Over.png'" onMouseOut="this.src='Buttons/Edit_Normal.png'" onClick="return func_group_1()"/>-->
											<input type="hidden" name="group_code" id="group_code" value="" />
											<input type="hidden" id="edit_groupid" name="edit_groupid" value="<?php  echo $List->id;  ?>">
							
										 </td>
									 </tr>
									 <?php $slno++; } }else{ ?>
									 <tr><td colspan="6" align="center">No Records Found</td></tr>
									 <?php }?>
								</table>
							</div>
							<?php }?>
								</div>
		                 </blockquote>
					</div>
				</div>
			</div>
        </form>
        <!--==============================footer=================================-->
        <?php   include "footer/footer.html"; ?>
        <script src="js/jquery.hoverdir.js"></script>
<script>
$(document).ready(function(){
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});
function func_group_1()
{
	var id=document.getElementById("edit_groupid").value
	alert(id)
	document.form.method="post";
	document.form.action="group1.php?gp_id="+document.form.edit_groupid.value
	document.form.submit();
}
function fn_default_code()
{
	
	var rows=document.getElementById("edit_groupid").value
	//alert(rows)
	var x=0;
	if (rows>0)
	{
		for(x=0;x<=rows;x++)
		{
			if(document.form.rad[x].checked==true )
			{	
				document.form.group_code.value=alltrim(document.getElementById("grpid"+x).value);
			}
		}
	}
	
	else
	{
		document.form.group_code.value=document.getElementById("grpid"+x).value;
	}
	func_default_id()
}

function func_default_id()
{
	
	document.form.method="post";
	document.form.action="group1.php?grpid="+document.form.group_code.value
	document.form.submit();
}

</script>
<script>
function validation()
{
	if(document.form.txt_groupname.value == "")
	{
		alert("Please Enter Group Name ");
		document.form.txt_groupname.focus();
		return false;
	}
	x=alltrim(document.form.txt_type.value)
	if(x.length==0)
	{
		alert("Please Enter Group Type ")
		document.form.txt_type.value="";
		document.form.txt_type.focus();
		return false
	}
}
/*function update_validation()
{
    if(document.form.txt_groupname.value == "")
	{
		alert("Please Enter Group Name ");
		document.form.txt_groupname.focus();
		return false;
	}
	x=alltrim(document.form.txt_type.value);
	if(x.length==0)
	{
		alert("Please Enter the group Name")
		document.form.txt_type.value="";
		document.form.txt_type.focus();
		return false
	}
	document.form.method="post";
	document.form.action="group1.php?grpid="+document.form.text_up_id.value
	document.form.submit();
}*/
</script>
</body>
</html>
