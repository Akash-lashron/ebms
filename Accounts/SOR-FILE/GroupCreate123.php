<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";

if (isset($_POST['btn_add_x']))
{
   	$cmb_group1=$_POST["cmb_group1"];
    $cmb_group2=$_POST["cmb_group2"];
	$cmb_group3=$_POST["cmb_group3"];
	$txt_group_4=$_POST["txt_group_4"]; //echo $txt_group_4;exit;
	$txt_type=$_POST["txt_type"];
	$txt_groupid=$_POST["txt_groupid"];
	$Display=$_POST["ch_display"];
	if($Display != 'Y'){
		$Display = 'N';
	}
	//$rs_update=mysqli_query($dbConn,$sql_update,$conn);
	$sql_max_group4="select max(group_id) as group_id from group_datasheet where 
							group_id like '".$cmb_group3. "%' and char_length(group_id) = '8'";
		$rs_max_group4=mysqli_query($dbConn,$sql_max_group4,$conn);
		
		
		$group_id_4=@mysqli_result($rs_max_group4,0,'group_id');
		if($group_id_4 == ""){
			$group_id_4 = $cmb_group3."01";
		}else{
			$group_id_4 = $group_id_4 + 1;
		}
		//echo $group_id_4;exit;
		
		
		if (strlen($group_id_4)==7){
			$group_id_4 = '0'.$group_id_4;		
		}else{
			$group_id_4 = $group_id_4;
		}
		//echo $group_id_2;
	
	
	$ParId = 0;
	$SelectparIdQuery 	= "select id from group_datasheet where group_id = '$cmb_group3'";
	$SelectparIdSql 	= mysqli_query($dbConn,$SelectparIdQuery,$conn);
	if($SelectparIdSql == true){
		if(mysqli_num_rows($SelectparIdSql)>0){
			$IdList = mysqli_fetch_object($SelectparIdSql);
			$ParId 	= $IdList->id;
		}
	}
	
	$rs_update='';
	$insert_query="insert into group_datasheet set group_id='$group_id_4', group_desc='$txt_group_4', type='$txt_type', par_id = '$ParId', disp = '$Display'";
			//echo $insert_query;exit;
	$insert_sql=mysqli_query($dbConn,$insert_query,$conn);
	if($insert_sql!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Saved")
			window.location="group4_edit.php";
		</script>
		<?php
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
									<td width="664" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Group - Create</td>
									<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
								</tr>
							</table>
							<input type="hidden" name="max_group" id="max_group" value="1" />
							<table width="725" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
								<tr><td width="18">&nbsp;</td></tr>
								<tr class="GR1">
									<td width="50">&nbsp;</td>
									<td class="labelbold" width="138">Group 1</td>
									<td colspan="2">
										<select class="text group" style="width:405px;height:21px;" name="cmb_group[]" id="cmb_group1" data-group = "1">
											<option value=""> ------------------------ Select ------------------------ </option>
											<option value="NEW">ADD NEW GROUP</option>
											<?php echo $objBind->BindGroupI($group_I_Id,'ALL'); ?>
										</select>
									</td>
								</tr>
								<tr class="GR1"><td width="18">&nbsp;</td></tr>		
								<tr>
									<td width="50">&nbsp;</td>
									<td class="labelbold" width="138">Type</td>
									<td width="357" align="left" class="text" colspan="3">
									<input type="text" name="txt_type" id="txt_type" value="<?php if($_GET['gp_id']!="") echo @mysqli_result($rs_modify,0,'type'); else echo ''; ?>" style="width:400px;height:21px;" />
									</td>
								</tr>
								<tr><td>&nbsp;&nbsp;</td></tr>
								<tr>
									<td width="50">&nbsp;</td>
									<td class="labelbold" width="138">&nbsp;</td>
									<td width="357" align="left" class="text labelbold" colspan="3"><input type="checkbox" name="ch_display" id="ch_display" value="Y" checked="checked"/> &emsp;Display Group Code</td>
								</tr>
								<input type="hidden" name="txt_groups_II" id="txt_groups_II" value="<?php echo @mysqli_result($rs_modify,0,'group_id'); ?>"/>										
								<tr><td>&nbsp;&nbsp;</td></tr>
								<tr align="center">
									<td colspan="5">
									<?php if($_GET['gp_id']!=""){ ?>	
											<center><input type="image" name="btn_back" id="btn_back" value="Back"  src="Buttons/Back_Normal.png" onMouseOver="this.src='Buttons/Back_Over.png'" onMouseOut="this.src='Buttons/Back_Normal.png'"  onclick="func_back()"/> &nbsp;&nbsp;&nbsp;<input type="image" name="btn_view" id="btn_view" value="View" src="Buttons/View_Normal.png" onMouseOver="this.src='Buttons/View_Over.png'" onMouseOut="this.src='Buttons/View_Normal.png'" onClick="return func_group_id()"/> &nbsp;&nbsp;&nbsp;<input type="image" name="btn_update" id="btn_update" value="Update" src="Buttons/Update_Normal.png" onMouseOver="this.src='Buttons/Update_Over.png'" onMouseOut="this.src='Buttons/Update_Normal.png'" onClick="return update_validation()"/>&nbsp;&nbsp;&nbsp;<input type="image" name="btn_delete" id="btn_delete" value="Delete" src="Buttons/Delete_Normal.png" onMouseOver="this.src='Buttons/Delete_Over.png'" onMouseOut="this.src='Buttons/Delete_Normal.png'"/></center>
									<?php }else{ ?>
											<input type="image" name="btn_add" id="btn_add" value="View" src="Buttons/Add_Normal.png" onMouseOver="this.src='Buttons/Add_Over.png'" onMouseOut="this.src='Buttons/Add_Normal.png'" onClick="return func_group_id()"/>
											<!--<input type="image" name="btn_view" id="btn_view" value="View" src="Buttons/View_Normal.png" onMouseOver="this.src='Buttons/View_Over.png'" onMouseOut="this.src='Buttons/View_Normal.png'" onClick="return func_group_id()"/></center>-->
									<?php } ?>
									</td>
								</tr>
								<tr><td>&nbsp;&nbsp;</td></tr>
							</table>
							</form>
						</blockquote>
					</div>
				</div>
			</div>
           <?php   include "footer/footer.html"; ?>
<script type="text/javascript" language="javascript">
$(function(){
	$("#cmb_group1").chosen();
	$('body').on("change",".group", function(e){ 
		var groupid = $(this).val();
		var level = $(this).attr('data-group');
		var nextLevel = Number(level)+1;
		var MaxLevel = $("#max_group").val(); //alert(level); alert(nextLevel); alert(MaxLevel);
		for(var i = nextLevel; i <= MaxLevel; i++){
			$('.GR'+i).remove();
		}
		if(groupid != ""){
			if(groupid == "NEW"){
				$('.GR'+level).last().after('<tr class="GR'+nextLevel+'"><td width="50">&nbsp;</td><td class="labelbold" width="138">New Group '+level+'</td><td colspan="2"><input type="text" name="new_group[]" id="new_group'+level+'" style="width:400px;height:21px;" data-group="'+level+'" /></td></tr><tr class="GR'+nextLevel+'"><td width="18">&nbsp;</td></tr>');
			}else{
				$.ajax({ 
					type: 'POST', 
					url: 'find_groups.php', 
					data: { groupid: groupid, level: level }, 
					dataType: 'json',
					success: function (data) {  
						if(data != null){
							var OptionList = '<option value=""> ------------------------ Select ------------------------ </option>';
								OptionList += '<option value="NEW">ADD NEW GROUP</option>';
							$.each(data, function(index, element) {
								//$("#cmb_group"+nextLevel).append('<option data-id="'+element.id+'" data-parid="'+element.par_id+'" value="'+element.group_id+'">'+element.group_desc+'</option>');
								OptionList += '<option data-id="'+element.id+'" data-parid="'+element.par_id+'" value="'+element.group_id+'">'+element.group_desc+'</option>';
							});
							$('.GR'+level).last().after('<tr class="GR'+nextLevel+'"><td width="50">&nbsp;</td><td class="labelbold" width="138">Group '+nextLevel+'</td><td colspan="2"><select class="text group" name="cmb_group[]" id="cmb_group'+nextLevel+'" style="width:405px;height:21px;" data-group="'+nextLevel+'">'+OptionList+'</select></td></tr><tr class="GR'+nextLevel+'"><td width="18">&nbsp;</td></tr>');
							$("#cmb_group"+nextLevel).chosen();
						}else{
							$('.GR'+level).last().after('<tr class="GR'+nextLevel+'"><td width="50">&nbsp;</td><td class="labelbold" width="138">New Group '+nextLevel+'</td><td colspan="2"><input type="text" name="new_group[]" id="new_group'+nextLevel+'" style="width:400px;height:21px;" data-group="'+nextLevel+'" /></td></tr><tr class="GR'+nextLevel+'"><td width="18">&nbsp;</td></tr>');
						}
					}
				});
			}
		}
		$("#max_group").val(nextLevel);
	});
});
</script>
</body>
</html>
