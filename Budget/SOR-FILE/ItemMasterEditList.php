<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'Item Master';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];

$DataSheetArr = array();
$SelectDataSheetQuery	= "select distinct item_id from datasheet_a1_details where item_id != 0";
$SelectDataSheetSql 	= mysqli_query($dbConn,$SelectDataSheetQuery);
if($SelectDataSheetSql == true){
	if(mysqli_num_rows($SelectDataSheetSql)>0){
		while($DSList = mysqli_fetch_object($SelectDataSheetSql)){
			array_push($DataSheetArr,$DSList->item_id);
		}
	}
}

$SelectMasterGroupQuery	= "select * from item_master where par_id = 0 and active = 1 order by item_desc asc";
$SelectMasterGroupSql 	= mysqli_query($dbConn,$SelectMasterGroupQuery);
$MasterGroupCnt 	 	= 0;
if($SelectMasterGroupSql == true){
	if(mysqli_num_rows($SelectMasterGroupSql)>0){
		$MasterGroupCnt = 1;
	}
}
if((isset($_GET['Action']) == "D")&&(isset($_GET['id']))){
	$DeleteId = $_GET['id'];
	$DeleteQuery = "update group_datasheet set active = 0 where id = '$DeleteId'";
	$DeleteSql 	 = mysqli_query($dbConn,$DeleteQuery);
}
//echo $SelectMasterGroupQuery;exit;
//print_r($DataSheetArr);exit;
?>

<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
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
							<div class="row">
								<div class="div12" align="center"></div>
							</div>
							<div class="row">
								<div class="div1" align="center">&nbsp;</div>
								<div class="div10" align="center">

									<?php if($MasterGroupCnt == 1){ while($MasterList = mysqli_fetch_object($SelectMasterGroupSql)){ ?>
									<div class='widget'>
										<div id='TAB<?php echo $MasterList->item_id; ?>' data-title='<?php echo $MasterList->item_desc; ?>' class="tab-content">
											<table class="group-table table itemtable formtable">
												<caption>List of groups in <?php echo $MasterList->item_desc; ?></caption>
												<thead>
													<tr>
														<th>SNo.</th>
														<th nowrap="nowrap">Group Code</th>
														<th>Group Description</th>
														<th colspan="2">Action</th>
													</tr>
												</thead>
												<tbody>
													<?php 
													$SNo = 1; $DetailCnt = 0;
													$SelectDetailGroupQuery = "select * from item_master where item_type = '$MasterList->item_type' and par_id != 0 and par_id = '$MasterList->item_id' and active = 1 and item_code != '' order by item_code asc";
													$SelectDetailGroupSql 	= mysqli_query($dbConn,$SelectDetailGroupQuery);
													if($SelectDetailGroupSql == true){
														if(mysqli_num_rows($SelectDetailGroupSql)>0){
															$DetailCnt = 1;
															while($DetailList = mysqli_fetch_object($SelectDetailGroupSql)){
													?>
																<tr>
																	<td align="center"><?php echo $SNo; $SNo++; ?></td>
																	<td align="center">
																		<span class="staticSpan" id="SType<?php echo $DetailList->item_id; ?>"><?php echo $DetailList->item_code; ?></span>
																		<span class="dynamicSpan gCode hide" id="DType<?php echo $DetailList->item_id; ?>" data-id="<?php echo $DetailList->item_id; ?>" contenteditable="true"><?php echo $DetailList->item_code; ?></span>
																	</td>
																	<td align="justify">
																		<span class="staticSpan" id="SDesc<?php echo $DetailList->item_id; ?>"><?php echo $DetailList->item_desc; ?></span>
																		<span class="dynamicSpan hide" id="DDesc<?php echo $DetailList->item_id; ?>" contenteditable="true"><?php echo $DetailList->item_desc; ?></span>
																	</td>
																	<td class="cboxlabel">
																	<?php if (in_array($DetailList->item_id, $DataSheetArr)){ ?>
																	<button type="button" title="Data sheet already created. You can't able to edit." class="btn fa-btn-e" id="EBtn<?php echo $DetailList->item_id; ?>" data-id="<?php echo $DetailList->item_id; ?>"><i class="fa fa-edit"></i></button>
																	<?php }else{ ?>
																	<button type="button" title="Edit" class="btn fa-btn-e gEdit" id="EBtn<?php echo $DetailList->item_id; ?>" data-id="<?php echo $DetailList->item_id; ?>"><i class="fa fa-edit"></i></button>
																	<?php } ?>
																	<button type="button" title="Save" class="btn fa-btn-o gSave hide" id="SBtn<?php echo $DetailList->item_id; ?>" data-id="<?php echo $DetailList->item_id; ?>"><i class="fa fa-check-circle-o"></i></button>
																	<div class="gDiv hide" id="GDiv<?php echo $DetailList->item_id; ?>"></div>
																	<button type="button" title="Cancel" class="btn fa-btn-c gCancel hide" id="CBtn<?php echo $DetailList->item_id; ?>" data-id="<?php echo $DetailList->item_id; ?>"><i class="fa fa-times-circle-o"></i></button>
																	</td>
																	<td class="cboxlabel">
																	<?php if (in_array($DetailList->item_id, $DataSheetArr)){ ?>
																	<button type="button" title="Data sheet already created. You can't able to delete." class="btn fa-btn-d" data-id="<?php echo $DetailList->item_id; ?>"><i class="fa fa-trash-o"></i></button>
																	<?php }else{ ?>
																	<button type="button" title="Delete" class="btn fa-btn-d gDelete" data-id="<?php echo $DetailList->item_id; ?>"><i class="fa fa-trash-o"></i></button>
																	<?php } ?>
																	</td>
																</tr>
													<?php			
															}
														}
													}
													if($DetailCnt == 0){
													?>
														<tr><td colspan="5" align="center">No Records Found</td></tr>
													<?php
													}
													?>
												</tbody>
											</table>
										</div>
 	 								</div>
								<?php } } ?>
								</div>
								<div class="div1" align="center">&nbsp;</div>
								<div class="div12" align="center">
									<a data-url="Administrator" class="btn btn-info">Back</a>
								</div>
								<div class="div12" align="center">&nbsp;</div>
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
<script>
$(document).ready(function(){
	$('.dropdown-submenu a.test').on("click", function(e){
    	$(this).next('ul').toggle();
    	e.stopPropagation();
    	e.preventDefault();
  	});
  	$('#btn_view_single').click(function(event){ 
  		$(location).attr("href","ItemMasterView.php");
		event.preventDefault();
		return false;
  	});
	//('.gEdit').click(function(event){ 
	$('body').on("click",".gEdit", function(event){ 
		var id = $(this).attr("data-id");
		$("#SType"+id).addClass("hide");
		$("#SDesc"+id).addClass("hide");
		$("#EBtn"+id).addClass("hide");
		
		$("#DType"+id).removeClass("hide");
		$("#DDesc"+id).removeClass("hide");
		$("#SBtn"+id).removeClass("hide");
		$("#CBtn"+id).removeClass("hide");
		$("#GDiv"+id).removeClass("hide");
		event.preventDefault();
		return false;
  	});
	$('body').on("blur",".gCode", function(e){  
		var newICode = $(this).text();
		var ItemId 	 = $(this).attr("data-id");
		$.ajax({ 
			type: 'POST', 
			url: 'find_item_code_exist.php', 
			data: { newICode: newICode, ItemId: ItemId }, 
			success: function (data) { 
				if(data > 0){
					BootstrapDialog.alert("Error: Item Code - "+newICode+" already exist. Please try another item Code");
					var OldICode = $("#SType"+ItemId).text();
					$("#DType"+ItemId).text(OldICode);
				}
			}
		});
	});
	$('body').on("click",".gSave", function(event){ 
		var id 	 	  = $(this).attr("data-id");
		var GroupDesc = $("#DDesc"+id).text();
		var GroupCode = $("#DType"+id).text();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/ItemUpdate.php', 
			data: { id: id, GroupDesc: GroupDesc, GroupCode: GroupCode }, 
			success: function (data) {
				if(data == 1){
					$("#SDesc"+id).text(GroupDesc);
					$("#SType"+id).text(GroupCode);
					$("#SType"+id).removeClass("hide");
					$("#SDesc"+id).removeClass("hide");
					$("#EBtn"+id).removeClass("hide");
					$("#DType"+id).addClass("hide");
					$("#DDesc"+id).addClass("hide");
					$("#SBtn"+id).addClass("hide");
					$("#CBtn"+id).addClass("hide");
					$("#GDiv"+id).addClass("hide");
					BootstrapDialog.alert("Item details updated successfully");
				}else{
					BootstrapDialog.alert("Error : Item details not updated. Please try again");
				}
			}
		});
		event.preventDefault();
		return false;
  	});
	$('body').on("click",".gCancel", function(event){ 
		var id 	 	  = $(this).attr("data-id");
		var GroupDesc = $("#SDesc"+id).text();
		var GroupCode = $("#SType"+id).text();
		$("#DDesc"+id).text(GroupDesc);
		$("#DType"+id).text(GroupCode);
		$("#SType"+id).removeClass("hide");
		$("#SDesc"+id).removeClass("hide");
		$("#EBtn"+id).removeClass("hide");
		$("#DType"+id).addClass("hide");
		$("#DDesc"+id).addClass("hide");
		$("#SBtn"+id).addClass("hide");
		$("#CBtn"+id).addClass("hide");
		$("#GDiv"+id).addClass("hide");
		event.preventDefault();
		return false;
  	});
	//$('.gDelete').click(function(event){ 
	/*$('body').on("click",".gDelete", function(event){ 
		var id = $(this).attr("data-id");
  		$(location).attr("href","HorticultureGroupView.php?Action=D&id="+id);
		event.preventDefault();
		return false;
  	});*/
	
	$('body').on("click",".gDelete", function(event){ 
		var id = $(this).attr("data-id");
		BootstrapDialog.confirm('Are you sure want to delete ?', function(result){
			if(result) {
				$(location).attr("href","ItemMasterHCEditList.php?Action=D&id="+id);
			}else {
				//return false;
			}
		});
	});
	
	var newWidget="<div class='widget-wrapper'> <ul class='tab-wrapper'></ul> <div class='new-widget'></div></div>";
    $(".widget").hide();
    $(".widget:first").before(newWidget);
    $(".widget > div").each(function(){
		var title = $(this).attr("data-title");
        $(".tab-wrapper").append("<li class='tab' id='"+this.id+"'>"+title+"</li>");
        $(this).appendTo(".new-widget");
    });
    $(".tab").click(function(){
        $(".new-widget > div").hide();
		var liId = $(this).attr('id');
        $('.new-widget #'+liId).show();//alert(liId);
        $(".tab").removeClass("active-tab");
        $(this).addClass("active-tab");
    });
    $(".tab:first").click();
	
});
</script>
