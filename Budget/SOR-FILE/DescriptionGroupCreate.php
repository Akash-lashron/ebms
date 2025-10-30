<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'Item Description';
$msg = "";
if(isset($_POST['btn_save']) == " Save "){
	$ParGroupArr 	= $_POST['cmb_group'];
	$NewGroupArr 	= $_POST['new_group'];
	$GroupType 		= $_POST['txt_type'];
	$CheckDisplay 	= $_POST['ch_display'];
	$ParCount 	 	= count($ParGroupArr);
	$ChiCount 	 	= count($NewGroupArr);
	$ParentId 	 	= $ParGroupArr[$ParCount-1];
	
	if($ParentId == "NEW"){
		if($ParCount == 1){
			$ParentId = 0;
		}else{
			$ParentId = $ParGroupArr[$ParCount-2];
		}
	}
	
	$NewGroup  	 	= $NewGroupArr[$ChiCount-1];
	$InsertQuery 	= "insert into group_datasheet set group_desc = '$NewGroup', type = '$GroupType', par_id = '$ParentId', disp = '$CheckDisplay', active = 1";
	$InsertSql 	 	= mysqli_query($dbConn,$InsertQuery);
	if($InsertSql == true){
		$msg = "New Group Created Successfully";
	}else{
		$msg = "Error : Group not created. Please try again.";
	}
}
//print_r($ChildArr);exit;
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
							<div class="row">&nbsp;</div>
							<div class="row">
								<input type="hidden" name="max_group" id="max_group" value="1" />
								<table align="center" class="formtable" width="60%">
									<thead>
										<tr>
											<th colspan="3" class="fhead" style="text-align:center; padding:5px">Item Description Create<!-- - Horticulture--></th>
										</tr>
									</thead>
									<tbody>
										<tr><td colspan="3">&nbsp;</td></tr>
										<tr class="GR1">
											<td>&nbsp;</td>
											<td class="lgboxlabel">Group 1</td>
											<td>
												<select class="group selectlgbox" name="cmb_group[]" id="cmb_group1" data-group = "1" >
													<option value=""> ------------------------ Select ------------------------ </option>
													<option value="NEW">ADD NEW GROUP</option>
													<?php echo $objBind->BindGroupI($group_I_Id,'ALL'); ?>
												</select>
											</td>
										</tr>
										<tr class="GR1"><td colspan="3" class="erow">&nbsp;</td></tr>		
										<tr>
											<td>&nbsp;</td>
											<td class="lgboxlabel">Group Code</td>
											<td align="left" class="text">
												<div class="div6">
													<input type="text" name="txt_type" id="txt_type" class="tboxclass" maxlength="15" value="<?php if($_GET['gp_id']!=""){ echo $Type; } ?>" required />
													<input type="hidden" name="txt_code_err" id="txt_code_err" value="">
												</div>
												<div class="div6">
													<span class="hide" id="GCAvail">
														<i class="fa fa-check-circle-o" style="font-size:26px; font-weight:300; color:#01873C"></i>
													</span>
													<span class="hide" id="GCNotAvail">
														<i class="fa fa-times-circle-o" style="font-size:27px; font-weight:300; color:#EA0245"></i> 
														<span style="line-height:25px; font-weight:bold; color:#EA0245"> Group Code Already Exists</span>
													</span>
												</div>
											</td>
										</tr>
										<tr><td colspan="3" class="erow">&nbsp;&nbsp;</td></tr>
										<tr>
											<td>&nbsp;</td>
											<td class="labelbold" width="138">&nbsp;</td>
											<td align="left" class="lgboxlabel">
												<input type="checkbox" name="ch_display" id="ch_display" value="Y" checked="checked"/> &emsp;Display Group Code
											</td>
										</tr>
										<tr>
											<td colspan="3" align="center">
												<a data-url="Administrator" class="btn btn-info">Back</a>
												<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
											</td>
										</tr>
										<tr><td colspan="3" class="erow">&nbsp;&nbsp;</td></tr>
									</tbody>
								</table>
							</div>
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
<script type="text/javascript" language="javascript">
$(function(){
	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			//BootstrapDialog.alert(msg);
			BootstrapDialog.show({
				title: 'Information',
				closable: false,
				message: msg,
				buttons: [{
					label: '&nbsp; OK &nbsp;',
					action: function(dialog) {
						$(location).attr("href","DescriptionGroupCreate.php");
					}
				}]
			});
		}
	};
	$("#cmb_group1").chosen();
	$('body').on("change",".group", function(e){ 
		var groupid = $(this).val();
		var level = $(this).attr('data-group');
		var nextLevel = Number(level)+1;
		var MaxLevel = $("#max_group").val(); //alert(level); alert(nextLevel); alert(MaxLevel);
		for(var i = nextLevel; i <= MaxLevel; i++){
			$('.GR'+i).remove();
		} //alert(groupid);
		if(groupid != ""){
			if(groupid == "NEW"){
				$('.GR'+level).last().after('<tr class="GR'+nextLevel+'"><td>&nbsp;</td><td class="lgboxlabel">New Group '+level+'</td><td colspan="2"><input type="text" class="tboxclass"  name="new_group[]" id="new_group'+level+'" data-group="'+level+'" required /></td></tr><tr class="GR'+nextLevel+'"><td colspan="3" class="erow">&nbsp;</td></tr>');
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
								OptionList += '<option data-id="'+element.id+'" data-parid="'+element.par_id+'" value="'+element.id+'">'+element.group_desc+'</option>';
							});
							$('.GR'+level).last().after('<tr class="GR'+nextLevel+'"><td>&nbsp;</td><td class="lgboxlabel">Group '+nextLevel+'</td><td><select class="group selectlgbox2" name="cmb_group[]" id="cmb_group'+nextLevel+'" data-group="'+nextLevel+'">'+OptionList+'</select></td></tr><tr class="GR'+nextLevel+'"><td colspan="3" class="erow">&nbsp;</td></tr>');
							$("#cmb_group"+nextLevel).chosen();
						}else{
							$('.GR'+level).last().after('<tr class="GR'+nextLevel+'"><td>&nbsp;</td><td class="lgboxlabel">New Group '+nextLevel+'</td><td><input type="text" class="tboxclass"  name="new_group[]" id="new_group'+nextLevel+'" data-group="'+nextLevel+'" required /></td></tr><tr class="GR'+nextLevel+'"><td colspan="3" class="erow">&nbsp;</td></tr>');
						}
					}
				});
			}
		}
		$("#max_group").val(nextLevel);
	});
	$('body').on("change","#txt_type", function(e){ 
		var newGroup = $(this).val();
		$("#txt_code_err").val('');
		var GroupId = '';
		$.ajax({ 
			type: 'POST', 
			url: 'find_group_code_exist.php', 
			data: { newGroup: newGroup, GroupId: GroupId }, 
			success: function (data) {  //alert(data);
				if(data > 0){
					$("#GCNotAvail").removeClass("hide");
					$("#GCAvail").addClass("hide");
					$("#txt_code_err").val(1);
				}else if(data == 0){
					$("#GCNotAvail").addClass("hide");
					$("#GCAvail").removeClass("hide");
				}else{
					$("#GCNotAvail").addClass("hide");
					$("#GCAvail").addClass("hide");
				}
			}
		});
	});
	$('body').on("click","#btn_save", function(event){ 
		var GrpCnt = 0;
		$(".group").each(function() {
			var Grp = $(this).val();
			if(Grp == ""){
				GrpCnt++;
			}
		}); 
		var CodeErr = $("#txt_code_err").val();
		if(GrpCnt > 0){
			BootstrapDialog.alert("Error : Group Name in drop down box should not be empty");
			event.preventDefault();
			event.returnValue = false;
		}else if(CodeErr == 1){
			BootstrapDialog.alert("Error : Group Code already exists. please enter different code.");
			event.preventDefault();
			event.returnValue = false;
		}
	});
});
if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
</script>
<style>
	.tboxclass{
		width:99%;
	}
	.chosen-container-single .chosen-single{
		padding: 7px 4px;
	}
</style>
