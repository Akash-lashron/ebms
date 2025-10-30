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
$RowCount =0;
$staffid = $_SESSION['sid'];
if($_GET['id']!=""){
	$ItemId	=	$_GET['id'];
	$SelectItemQuery = "select * from item_master where item_id = '$ItemId'";
	$SelectItemSql	 = mysqli_query($dbConn,$SelectItemQuery);
	if(mysqli_num_rows($SelectItemSql)>0){
		$List=mysqli_fetch_object($SelectItemSql);
		$ItemCode	=	$List->item_code;
		$ItemDesc	=	$List->item_desc;
		$ItemRate	=	$List->price;
		$ItemUnit	=	$List->unit;
		$MainItem	=	$List->par_id;
	}
}
if(isset($_POST['btn_save']) == " Save "){
	$POSTMainItem	=	$_POST['cmb_main_item'];
	$POSTItemCode	=	$_POST['txt_item_code'];
	$POSTItemDesc	=	$_POST['txt_item_desc'];
	$POSTItemUnit	=	$_POST['cmb_item_unit'];
	$POSTItemRate	=	$_POST['txt_item_rate'];
	$POSTItemId		=	$_POST['txt_item_id'];
	$POSTItemDesc  	= addslashes($POSTItemDesc );
	$ItemType = "";
	$SelectItemQuery = "select item_type from item_master where item_id = '$POSTMainItem'";
	$SelectItemSql	 = mysqli_query($dbConn,$SelectItemQuery);
	if(mysqli_num_rows($SelectItemSql)>0){
		$List		 = mysqli_fetch_object($SelectItemSql);
		$ItemType	 = $List->item_type;
	}
	
	if($POSTItemId == ""){
		$InsertQuery = "insert into item_master set item_code = '$POSTItemCode', item_desc = '$POSTItemDesc', item_type = '$ItemType', unit = '$POSTItemUnit', price = '$POSTItemRate', par_id = '$POSTMainItem', active = 1";
		$InsertQuery = mysqli_query($dbConn,$InsertQuery);
		if($InsertQuery == true){
			$$msg = "Item created successfully.";
		}else{
			$$msg = "Error : Item not created. Please try again.";
		}
	}else{
		$InsertQuery = "update item_master set item_code = '$POSTItemCode', item_desc = '$POSTItemDesc', item_type = '$ItemType', unit = '$POSTItemUnit', price = '$POSTItemRate', par_id = '$POSTMainItem', active = 1 where item_id = '$POSTItemId'";
		$InsertQuery = mysqli_query($dbConn,$InsertQuery);
		if($InsertQuery == true){
			$$msg = "Item updated successfully.";
		}else{
			$$msg = "Error : Item not created. Please try again.";
		}
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
							<div class="row">
								<div class="div12" align="center">&nbsp;</div>
							</div>
							<div class="row">
								<div class="div3" align="center">&nbsp;</div>
								<div class="div6" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-sb" align="center">Item Master<!-- - Horticulture--></div>
										<div class="row innerdiv group-div" align="center">
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lgboxlabel">Main Item</div>
												<div class="div8 bbox">
													<select class="selectlgbox" name="cmb_main_item" id="cmb_main_item">
														<option value=""> --------------------- Select Main Item --------------------- </option>
														<?php echo $objBind->BindMainItem($MainItem); ?>
													</select>
													<input type="hidden" name="txt_item_id" id="txt_item_id" value="<?php if($_GET['id']!=""){ echo $ItemId; } ?>">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lgboxlabel">Item Code</div>
												<div class="div8">
													<div class="div6">
														<input type="text" name="txt_item_code" id="txt_item_code" class="tboxclass" value="<?php if($_GET['id']!=""){ echo $ItemCode; } ?>" maxlength="15" required>
														<input type="hidden" name="txt_code_err" id="txt_code_err" value="">
													</div>
													<div class="div6" align="left">
														<span class="hide lgboxlabel" id="ICAvail">
															&nbsp;<i class="fa fa-check-circle-o" style="font-size:26px; font-weight:300; color:#01873C"></i>
														</span>
														<span class="hide lgboxlabel" id="ICNotAvail">
															&nbsp;<i class="fa fa-times-circle-o" style="font-size:27px; font-weight:300; color:#EA0245"></i> 
															<span style="line-height:25px; font-weight:bold; color:#EA0245"> Item Code Already Exists</span>
														</span>
													</div>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lgboxlabel">Item Description</div>
												<div class="div8">
													<input type="text" name="txt_item_desc" id="txt_item_desc" class="tboxclass" value="<?php if($_GET['id']!=""){ echo $ItemDesc; } ?>" required>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lgboxlabel">Item Unit</div>
												<div class="div8 bbox">
													<select class="selectlgbox" name="cmb_item_unit" id="cmb_item_unit">
														<option value=""> --------------------- Select Item Unit --------------------- </option>
														<?php echo $objBind->BindUnit($ItemUnit); ?>
													</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<!--<div class="row">
												<div class="div4 lgboxlabel">Item Rate</div>
												<div class="div8">
													<input type="text" name="txt_item_rate" id="txt_item_rate" class="tboxclass" value="<?php if($_GET['id']!=""){ echo $ItemRate; } ?>">
												</div>
											</div>-->
											<div class="row" align="center">
												<a data-url="Administrator" class="btn btn-info">Back</a>
												<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
											</div>
										</div>
									</div>
								</div>
								<div class="div3" align="center">&nbsp;</div>
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
	$("#cmb_main_item").chosen();
	$("#cmb_item_unit").chosen();
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
	$('body').on("change","#txt_item_code", function(e){ 
		var newICode = $(this).val();
		var ItemId = $("#txt_item_id").val();
		$("#txt_code_err").val('');
		$.ajax({ 
			type: 'POST', 
			url: 'find_item_code_exist.php', 
			data: { newICode: newICode, ItemId: ItemId }, 
			success: function (data) { 
				if(data > 0){
					$("#ICNotAvail").removeClass("hide");
					$("#ICAvail").addClass("hide");
					$("#txt_code_err").val(1);
				}else if(data == 0){
					$("#ICNotAvail").addClass("hide");
					$("#ICAvail").removeClass("hide");
				}else{
					$("#ICNotAvail").addClass("hide");
					$("#ICAvail").addClass("hide");
				}
			}
		});
	});
	$('body').on("click","#btn_save", function(event){ 
		var MainItem 	= $("#cmb_main_item").val();
		var CodeErr 	= $("#txt_code_err").val();
		var ItemUnit 	= $("#cmb_item_unit").val();
		if(MainItem == ""){
			BootstrapDialog.alert("Error : Please select main Item");
			event.preventDefault();
			event.returnValue = false;
		}else if(CodeErr == 1){
			BootstrapDialog.alert("Error : Item Code already exists. Please enter different code.");
			event.preventDefault();
			event.returnValue = false;
		}else if(ItemUnit == ""){
			BootstrapDialog.alert("Error : Please select Item Unit");
			event.preventDefault();
			event.returnValue = false;
		}
	});
});
</script>
