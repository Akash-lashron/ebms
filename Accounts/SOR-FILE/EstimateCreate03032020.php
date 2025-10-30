<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$GlobGr1Id = 2; $GlobGr2Id = 3;
include "DefaultMaster.php";
$msg = ""; $del = 0; $RowCount =0;
$staffid = $_SESSION['sid'];
if(isset($_POST['btn_save']) == ' Save '){
	$WorkName 		= $_POST['txt_work_name'];
	$TotalAmt 		= $_POST['txt_total_amount'];
	$ItemNoArr 		= $_POST['txt_item_no'];
	$ItemIdArr 		= $_POST['txt_item_id'];
	$ItemCodeArr 	= $_POST['txt_code'];
	$ItemDescArr 	= $_POST['txt_desc'];
	$ItemUnitArr 	= $_POST['txt_unit'];
	$ItemQtyArr 	= $_POST['txt_quantity'];
	$InsertMasterQuery 	= "insert into partab_master set work_name = '$WorkName', partA_amount = '$TotalAmt'";
	$InsertMasterSql 	= mysqli_query($dbConn,$InsertMasterQuery);
	foreach($ItemNoArr as $key => $Value){
		$InsertMasterQuery 	= "insert into partab_master set work_name = '$WorkName', partA_amount = '$TotalAmt'";
		$InsertMasterSql 	= mysqli_query($dbConn,$InsertMasterQuery);
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
								<div class="div1" align="center">
									&nbsp;
								</div>
								<div class="div10" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-b" align="center">Estimate Create</div>
										<div class="row innerdiv" align="center">
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div2 cboxlabel" style="padding-top:17px">Name of Work</div>
												<div class="div10">
													<textarea name="txt_work_name" id="txt_work_name" class="tboxsmclass" style="width:100%;"></textarea>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<table align="center" id="tab_a1_material" class="itemtable" width="100%">
													<tr>
														<th nowrap="nowrap">Item No</th>
														<th nowrap="nowrap">Item Code</th>
														<th nowrap="nowrap">Main-Data / Sub-Data Description</th>
														<th>Unit</th>
														<th>Qty</th>
														<th>&nbsp;</th>
													</tr>
													<tr>
														<td class="labelcenter" valign="middle">
															<input type="text" class="tboxsmclass" style="width:100%" name="txt_item_no[]" id="txt_item_no0" value="" data-index="0" autocomplete="off" />
														</td>
														<td class="labelcenter" nowrap="nowrap" valign="middle">
															<input type="text" class="tboxsmclass icode" list="ItemCodeListNew0" style="width:100%" name="txt_code[]" id="txt_code0" data-index="0" value="" autocomplete="off" placeholder=" &#61442; Search "/>
															<datalist id="ItemCodeListNew0" style="color:#C80B5B; font-size:16px">
																<?php echo $objBind->BindAllGroups(0); ?>
															</datalist>
														</td>
														<td class="labelcenter" nowrap="nowrap" valign="middle" width="60%">
															<input type="text" class="tboxsmclass idesc" list="ItemDescListNew0" style="width:100%;" name="txt_desc[]" id="txt_desc0" data-index="0" value="" autocomplete="off" placeholder=" &#61442; Search "/>
															<datalist id="ItemDescListNew0" style="color:#C80B5B; font-size:16px">
																<?php echo $objBind->BindItemCodeDesc(0); ?>
															</datalist>
															<input type="hidden" class="tboxsmclass" style="width:100%" name="txt_item_id[]" id="txt_item_id0" value="" readonly=""  data-index="0" />
														</td>
														<td class="labelcenter" valign="middle">
															<input type="text" class="tboxsmclass ctext" style="width:100%" name="txt_unit[]" id="txt_unit0" value="" readonly="" />
															<input type="hidden" class="tboxsmclass rtext" style="width:100%" name="txt_rate[]" id="txt_rate0" value="" readonly="" />
														</td>
														<td class="labelcenter" nowrap="nowrap" valign="middle">
															<input type="text" class="tboxsmclass rtext Qty" style="width:100%" data-index="0" name="txt_quantity[]" id="txt_quantity0" value="" />
															<input type="hidden" class="tboxsmclass rtext NewAmt" style="width:100%" name="txt_amount[]" id="txt_amount0" value="" readonly="" />
														</td>
														<td class="labelcenter" align='center' valign="middle" nowrap="nowrap">
															<!--<input type="button" name="btn_add"   id="btn_add" value="Add" style="width:45%" onClick="addrow()" />-->
															<i style="font-size:21px" class="fa faicon-add" name="btn_add" id="btn_add" onClick="addrow()">&#xf01a;</i>
															<i style="font-size:21px" class="fa faicon-clr" name="btn_clear" id="btn_clear" onClick="cleartxt()">&#xf05c;</i>
															<!--<input type="button" name="btn_clear" id="btn_clear" value="  Clear  " style="width:45%" onClick="cleartxt()"/>	-->
														</td>
													</tr>
													<tr>
														<td colspan="4" class="labelboldright rboxlabel" valign="middle">Total Amount<!-- of A1-->&nbsp;&nbsp;</td>
														<td class="labelboldcenter" colspan="2"><input type="text" class="labelfieldright disable" style="width:100%" name="txt_total_amount" id="txt_total_amount" value="" readonly="" /></td>
													</tr>
												</table>
											</div>
											<div class="row clearrow">&nbsp;</div>
											<div class="row" align="center">
												<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
											</div>
										</div>
									</div>
								</div>
								<div class="div1" align="center">
									
								</div>
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
	function roundOff(value, decimals) {
	  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
	}
	$("#tab_a1_material").on("change", "input[name='txt_item_no[]']", function(event) {
		var ItemNo = $(this).val();
		var DuplicateError = 0;
		$('input[name="txt_item_no[]"]').each(function(){ 
			var CurrItemNo = $(this).val(); 
			var CurrIndex  = $(this).attr("data-index");
			if(CurrIndex != 0){
				if(CurrItemNo == ItemNo){
					DuplicateError++;
				}
			}
		}); 
		if(DuplicateError > 0){
			ClearNew();
			BootstrapDialog.alert("Duplicate Error :  Item No. already used please try another one");
			event.preventDefault();
			event.returnValue = false;
		}
	});
	
	$("#tab_a1_material").on("change", ".icode", function(event) {
		var itemCode 	= $(this).val(); 
		var RowID 		= $(this).attr("data-index");
		$("#txt_item_id"+RowID).val('');
		$("#txt_desc"+RowID).val('');
		$("#txt_unit"+RowID).val('');	
		$("#txt_rate"+RowID).val('');
		$("#txt_quantity"+RowID).val('');
		$("#txt_amount"+RowID).val('');
		
		
		var itemId 		= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('item_id');//alert(parid);
		var itemDesc 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('desc');//alert(id);
		var itemUnit 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('unit');//alert(refid);
		var itemRate 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('price');//alert(groupid);
		var Type 		= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('type');
		var CalcType 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('calc_type');
		
		var parid 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('par_id');//alert(parid);
		var id 		= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('id');//alert(id);
		var refid 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('ref_id');//alert(refid);
		var groupid = $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('group_id');//alert(groupid);
		var refid 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('ref_id');//alert(refid);
		
		var DuplicateError = 0;
		$('input[name="txt_code[]"]').each(function(){ 
			var CurrItemCode = $(this).val(); 
			var CurrIndex  = $(this).attr("data-index");
			if(CurrIndex != 0){
				if(CurrItemCode == itemCode){
					DuplicateError++; 
				}
			}
		});
		if(DuplicateError > 0){
			ClearNew();
			BootstrapDialog.alert("Duplicate Error :  Item Code already used please try another one");
			event.preventDefault();
			event.returnValue = false;
		}
		
		$.ajax({ 
			type: 'POST', 
			url: 'find_rate_calculation.php', 
			data: { groupid: groupid, id: id, parid: parid, refid: refid }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					var TSRate 		= data['TSRate'];
					var IGCARRate 	= data['IGCARRate1'];
					var ItemAmount 	= data['TotalAmount'];
					var MasterUnit 	= data['MasterUnit'];
					var MasterDesc 	= data['MasterDesc'];
					$("#txt_item_id"+RowID).val(itemId);
					$("#txt_desc"+RowID).val(MasterDesc);
					$("#txt_unit"+RowID).val(MasterUnit);	
					$("#txt_rate"+RowID).val(ItemAmount.toFixed(2));
					$("#txt_quantity"+RowID).val('');
					$("#txt_amount"+RowID).val(ItemAmount.toFixed(2));
				}
			}
		});
		
		
		/*if(Type == "SD"){
			if(CalcType == "WC"){
				BootstrapDialog.show({
					message: '<input type="radio" name="modal_amount_type" id="modal_gross_val'+RowID+'" class="modal_gross_val" value="GAMT"> Base Value ( W ) &emsp;&emsp;&emsp;<input type="radio" name="modal_amount_type" id="modal_net_val'+RowID+'" class="modal_net_val" value="NAMT"> Cost of Work',
					onhide: function(dialogRef){
						var AmtType = dialogRef.getModalBody().find("input[name=modal_amount_type]:checked").val();
						if(AmtType == undefined) {
							BootstrapDialog.alert('Please select any one option !');
							return false;
						}else{
							
							var parid 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('par_id');//alert(parid);
							var id 		= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('id');//alert(id);
							var refid 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('ref_id');//alert(refid);
							var groupid = $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('group_id');//alert(groupid);
							var refid 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('ref_id');//alert(refid);
							$.ajax({ 
								type: 'POST', 
								url: 'find_rate_calculation.php', 
								data: { groupid: groupid, id: id, parid: parid, refid: refid }, 
								dataType: 'json',
								success: function (data) {  
									if(data != null){
										//var TSRate 		= data['TSRate'];
										//var IGCARRate 	= data['IGCARRate1'];
										var MasterUnit 	= data['MasterUnit'];
										$("#hid_calc_type"+RowID).val('WC');
										if(AmtType == "GAMT"){
											var ItemAmount 	= data['TotalAmount'];
											$("#hid_amt_type"+RowID).val('GAMT');
										}else{
											$("#hid_amt_type"+RowID).val('NAMT');
										}
										var MasterDesc 	= data['MasterDesc'];
										$("#txt_item_id"+RowID).val(itemId);
										$("#txt_desc"+RowID).val(MasterDesc);
										$("#txt_unit"+RowID).val(MasterUnit);	
										$("#txt_rate"+RowID).val(ItemAmount.toFixed(2));
										$("#txt_quantity"+RowID).val('-');
										$("#txt_amount"+RowID).val(ItemAmount.toFixed(2));
										
										if(RowID != '0'){
											FindTotalAmountSD();
											if($("#is_average").is(':checked')){
												FindAverageAmountSD();
											}
										}
									}
								}
							});
							
						}
					},
					buttons: [{
						label: 'OK',
						action: function(dialogRef) {
							dialogRef.close();
						}
					}]
				});
			}else{ 
				var parid 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('par_id');//alert(parid);
				var id 		= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('id');//alert(id);
				var refid 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('ref_id');//alert(refid);
				var groupid = $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('group_id');//alert(groupid);
				var refid 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('ref_id');//alert(refid);
				$.ajax({ 
					type: 'POST', 
					url: 'find_rate_calculation.php', 
					data: { groupid: groupid, id: id, parid: parid, refid: refid }, 
					dataType: 'json',
					success: function (data) {  
						if(data != null){ 
							var TSRate 		= data['TSRate'];
							var IGCARRate 	= data['IGCARRate1'];
							var ItemAmount 	= data['TotalAmount'];
							var MasterUnit 	= data['MasterUnit'];
							
							var MasterDesc 	= data['MasterDesc'];
							
							$("#txt_item_id"+RowID).val(itemId);
							$("#txt_desc"+RowID).val(MasterDesc);
							$("#txt_unit"+RowID).val(MasterUnit);	
							$("#txt_rate"+RowID).val(ItemAmount.toFixed(2));
							$("#txt_quantity"+RowID).val('-');
							$("#txt_amount"+RowID).val(ItemAmount.toFixed(2));
						}
					}
				});
			}
		}else{
			$("#txt_item_id"+RowID).val(itemId);
			$("#txt_desc"+RowID).val(itemDesc);
			$("#txt_unit"+RowID).val(itemUnit);	
			$("#txt_rate"+RowID).val(itemRate);
			$("#txt_quantity"+RowID).val('');
			$("#txt_amount"+RowID).val('');
		}*/
	});
	var index = 1;
	$("#btn_add").click(function(event){ 
		var itemNo 		= $("#txt_item_no0").val(); 
		var itemCode 	= $("#txt_code0").val();
		var itemDesc 	= $("#txt_desc0").val();
		var itemId 	 	= $("#txt_item_id0").val();
		var itemRate 	= $("#txt_rate0").val();
		var itemUnit 	= $("#txt_unit0").val();
		var itemQty 	= $("#txt_quantity0").val();
		var itemAmt 	= $("#txt_amount0").val();
		var Error = 1;
		if(itemNo == ""){ 
			BootstrapDialog.alert("Please Enter Item No.");
			event.preventDefault();
			event.returnValue = false;
		}else if(itemCode == ""){ 
			BootstrapDialog.alert("Please Enter Item Code");
			event.preventDefault();
			event.returnValue = false;
		}else if(itemDesc == ""){ 
			BootstrapDialog.alert("Please Enter Item Description");
			event.preventDefault();
			event.returnValue = false;
		}else if(itemUnit == ""){ 
			BootstrapDialog.alert("Please Enter Item Unit");
			event.preventDefault();
			event.returnValue = false;
		}else if(itemQty == ""){ 
			BootstrapDialog.alert("Please Enter Item Quantity");
			event.preventDefault();
			event.returnValue = false;
		}else{
			$('#tab_a1_material').find('tr:last').prev().after('<tr><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass" size="10" style="width:100%;" name="txt_item_no[]" id="txt_item_no'+index+'" data-index="'+index+'" value="'+itemNo+'" /></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass icode" list="ItemCodeListNew'+index+'" size="10" style="width:100%;" name="txt_code[]" id="txt_code'+index+'" data-index="'+index+'" value="'+itemCode+'" /><datalist id="ItemCodeListNew'+index+'" style="color:#C80B5B; font-size:16px"></datalist><input type="hidden" class="labelfield" size="9" style="width:100%" name="txt_item_id[]" id="txt_item_id'+index+'" data-index="'+index+'" value="'+itemId+'" readonly="" /></td><td class="labelcenter" valign="middle" nowrap="nowrap"><input type="text" class="tboxsmclass idesc" list="ItemDescListNew'+index+'" data-index="'+index+'" size="25" style="width:100%;" name="txt_desc[]" id="txt_desc'+index+'" value="'+itemDesc+'" /><datalist id="ItemDescListNew'+index+'" style="color:#C80B5B; font-size:16px"></datalist></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass ctext disable" size="5" style="width:100%" name="txt_unit[]" id="txt_unit'+index+'" value="'+itemUnit+'" readonly="" /><input type="hidden" class="tboxsmclass rtext disable" size="5" style="width:100%" name="txt_rate[]" id="txt_rate'+index+'" value="'+itemRate+'" readonly="" /></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext Qty" size="5" style="width:100%" data-index="'+index+'" name="txt_quantity[]" id="txt_quantity'+index+'" value="'+itemQty+'" /><input type="hidden" class="tboxsmclass rtext disable NewAmt" size="8" style="width:100%" name="txt_amount[]" id="txt_amount'+index+'" value="'+itemAmt+'" readonly="" /></td><td class="labelcenter" valign="middle"><i style="font-size:21px" class="fa faicon-del delete" name="btn_delete" id="btn_delete'+index+'">&#xf057;</i></td></tr>'); //add input box
			$('#ItemCodeListNew0').find('option').clone().appendTo('#ItemCodeListNew'+index);
			$('#ItemDescListNew0').find('option').clone().appendTo('#ItemDescListNew'+index);
			index++;
			ClearNew();
			FindTotalAmountNew();
		}
	});
	$("#tab_a1_material").on("change", ".Qty", function() { 
		var Qty 	= $(this).val(); 
		var RowID 	= $(this).attr("data-index"); 
		var Rate 	= $('#txt_rate'+RowID).val(); 
		var Amount 	= Number(Qty)*Number(Rate); 
			Amount = roundOff(Amount,2); 
		 $("#txt_amount"+RowID).val(Amount); 
	});
	$("#tab_a1_material").on("click", ".delete", function() {
    	$(this).closest("tr").remove();
		FindTotalAmountNew();
    });
	$("#tab_a1_material").on("click", "#btn_clear", function() {
		ClearNew();
	});
	function ClearNew(){
		$("#txt_item_no0").val('');
		$("#txt_code0").val('');
		$("#txt_item_id0").val('');
		$("#txt_desc0").val('');
		$("#txt_unit0").val('');
		$("#txt_rate0").val('');
		$("#txt_quantity0").val('');
		$("#txt_amount0").val('');
	}
	function FindTotalAmountNew(){ 
		var TotalAmt = 0; var x = 0;
		$('#txt_total_amount').val('');
		$(".NewAmt").each(function(){ 
			var Amt = $(this).val();
			if(Amt!= ''){
				TotalAmt = Number(TotalAmt)+Number(Amt);
				x++;
			}
		});
		if(Number(x) > 1){
			$('#txt_total_amount').val(TotalAmt.toFixed(2));
		}
	}
});

</script>
<script>
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
</script>