var index = 1;
var index2 = 1;
var cindexSD = 1
var cindex = 1;

$(document).ready(function(){
	function roundOff(value, decimals) {
	  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
	}

	$('.group').chosen();
	$('.dropdown-submenu a.test').on("click", function(e){
    	$(this).next('ul').toggle();
    	e.stopPropagation();
    	e.preventDefault();
  	});
	function CheckDataSheet(id,parid){
		$("#hid_check_ds").val(''); $("#hid_check_ds_name").val('');
		$.ajax({ 
			type: 'POST', 
			url: 'find_check_datasheet_hc.php', 
			data: { id: id, parid: parid }, 
			dataType: 'json',
			success: function (data) {   
				if(data != null){
					var Result1 = data['AlreadyCreated'];
					var Type 	= data['Type'];
					$("#hid_check_ds").val(Result1);
					$("#hid_check_ds_name").val(Type);
					if(Result1 == 1){
						BootstrapDialog.alert("Error : "+Type+" - Datasheet already Created.");
					}
				}
			}
		});
	}
	function CheckDataSheetWoAlert(id,parid){
		$("#hid_check_ds").val(''); $("#hid_check_ds_name").val('');
		$.ajax({ 
			type: 'POST', 
			url: 'find_check_datasheet_hc.php', 
			data: { id: id, parid: parid }, 
			dataType: 'json',
			success: function (data) {   
				if(data != null){
					var Result1 = data['AlreadyCreated'];
					var Type 	= data['Type'];
					$("#hid_check_ds").val(Result1);
					$("#hid_check_ds_name").val(Type);
				}
			}
		});
	}
	
	/// FOR GROUP CHANGE - FIND NEXT LEVEL GORUP BASED ON CHAGES
	$('body').on("change",".group", function(e){ 
		var groupid  = $(this).val(); $("#hid_check_ds").val(''); $("#hid_check_ds_name").val('');
		var id 		 = $("option:selected", this).attr('data-id');
		var parid 	 = $("option:selected", this).attr('data-parid');
		var maxgroup = $("option:selected", this).attr('data-group');
		var curgroup = maxgroup;
			maxgroup = Number(maxgroup)+1;
		$(".G").each(function() {
			var classname = Number($(this).attr('class').split(' ')[0]);
			if(classname >= maxgroup){
				$(this).closest("div").remove();
			}
		});
		if(groupid == 'Select'){
			return false;
			exit();
		}
		$.ajax({ 
			type: 'POST', 
			url: 'find_group_common_hc.php', 
			data: { groupid: groupid, id: id, parid: parid }, 
			dataType: 'json',
			success: function (data) {   
				if(data != null){
					var Result1 = data['Result1'];
					var Result2 = data['Result2'];
					//alert(Result1);alert(Result2);
					if(Result1 != null){
						var title = "Group "+maxgroup;
						/*var Content  = '<tr class="'+maxgroup+' G"><td width="50">&nbsp;</td><td class="labelbold" width="138">'+title+'</td><td colspan="2">';
							Content += '<select name="cmb_group[]" id="cmb_group'+maxgroup+'" data-group="'+maxgroup+'" class="labelfield group" style="width:648px;height:21px;">';
							Content += '<option value="Select">----------------------- Select -----------------------</option>';
							Content += '</select></td></tr><tr class="'+maxgroup+' G"><td colspan="5">&nbsp;</td></tr>';*/
							
						var Content  = '<div class="'+maxgroup+' G row clearrow"></div>';
							Content += '<div class="'+maxgroup+' G row">';
							Content += '<div class="'+maxgroup+' G div3 lboxlabel">'+title+'</div>';
							Content += '<div class="'+maxgroup+' G div9" align="center"><select name="cmb_group[]" id="cmb_group'+maxgroup+'" data-group="'+maxgroup+'" class="sboxsmclass group">';
							Content += '<option value="Select">----------- Select -----------</option>';
							Content += '</select></div>';
							Content += '</div>';
							
						//$("#gr-append").after(Content);
						$(".group-div .row:last").after(Content);
						
						$.each(Result1, function(index, element) {
							$("#cmb_group"+maxgroup).append('<option data-id="'+element.id+'" data-parid="'+element.par_id+'" data-group="'+maxgroup+'" value="'+element.id+'">'+element.type+" - "+element.group_desc+'</option>');
						});
						CheckDataSheetWoAlert(id,parid);
					}else{
						var title = "Group "+curgroup+" Desc.";
						$.each(Result2, function(index, element) {
							/*var Content  = '<tr class="'+maxgroup+' G"><td width="50">&nbsp;</td><td class="labelbold" width="138">'+title+'</td><td colspan="2">';
								Content += '<textarea name="txt_group_desc" id="txt_group_desc" class="labelfield" rows="5" cols="87" readonly="">'+element.group_desc+'</textarea>';
								Content += '</td></tr><tr class="'+maxgroup+' G"><td colspan="5">&nbsp;</td></tr>';*/
								
							var Content  = '<div class="'+maxgroup+' G row clearrow"></div>';
								Content += '<div class="'+maxgroup+' G row">';
								Content += '<div class="'+maxgroup+' G div3 lboxlabel">'+title+'</div>';
								Content += '<div class="'+maxgroup+' G div9" align="center"><textarea name="txt_group_desc" id="txt_group_desc" class="tboxsmclass" rows="10" style="width:96%" readonly="">'+element.group_desc+'</textarea></div>';
								Content += '</div>';
							//$("#gr-append").after(Content);
							$(".group-div .row:last").after(Content);
							
							CheckDataSheet(id,parid);
						});
					}
					$("#cmb_group"+maxgroup).chosen();	
				}
			}
		});
		$("#max_group").val(maxgroup);
	});
	
	
	///// SHOW AND HIDE OF MAIN DATA / MERGE WITH SUB DATA CONTENT
	$(".rad_type").click(function(event){ 
		var type = $(this).val(); 
		if(type == 'N'){
			$(".new").removeClass("hide");
			$(".merge").addClass("hide");
			$("#with_calc").prop("checked", true);
			$("#without_calc").prop("checked", false);
			
			$(".add-ded div").css({"color":"#04498E"});
			$(".add-ded .tboxs2mclass").css({"background-color":"#FFFFFF"});
			$(".add-ded .tboxs2mclass").css({"color":"#001BC6"});
			$(".add-ded .tboxs2mclass").css("border","1px solid #6CABF7");
		}else if(type == 'M'){
			$(".new").addClass("hide");
			$(".merge").removeClass("hide");
			$("#with_calc").prop("checked", false);
			$("#without_calc").prop("checked", true);
			
			$(".add-ded div").css({"color":"#C1C2C4"});
			$(".add-ded .tboxs2mclass").css({"background-color":"#E0E1E3"});
			$(".add-ded .tboxs2mclass").css({"color":"#C1C2C4"});
			$(".add-ded .tboxs2mclass").css("border","1px solid #ABABAB");
			$(".disprow").addClass("hide");
			$('#disposal_qty').prop("checked",false);
		}
	});
	$("#is_average").click(function(event){ 
		if($(this).is(':checked')){ 
			$("#txt_total_amount_is_avg").removeClass("disable");
			$("#txt_total_amount_igc_avg").removeClass("disable");
			FindAverageAmountSD();
		}else{
			$("#txt_total_amount_is_avg").addClass("disable");
			$("#txt_total_amount_igc_avg").addClass("disable");
			$("#txt_total_amount_is_avg").val("")
			$("#txt_total_amount_igc_avg").val("")
		}
	});
	
	
	///// SHOW AND HIDE OF WITH CALCULATION / WITHOUT CALCULATION CONTENT
	$(".calc_type").click(function(event){ 
		var type = $(this).val(); 
		if(type == 'WC'){
			//$(".calc").removeClass("hide");
			$(".add-ded div").css({"color":"#04498E"});
			$(".add-ded .tboxs2mclass").css({"background-color":"#FFFFFF"});
			$(".add-ded .tboxs2mclass").css({"color":"#001BC6"});
			$(".add-ded .tboxs2mclass").css("border","1px solid #6CABF7");
			$(".disprow").removeClass("hide");
			if($('#disposal_qty').is(':checked')){
				$(".disposal").removeClass("hide");
			}else{
				$(".disposal").addClass("hide");
			}
			CalulateAddDeduct();
		}else if(type == 'WOC'){
			//$(".calc").addClass("hide");
			$('#disposal_qty').prop("checked",false);
			$(".add-ded div").css({"color":"#C1C2C4"});
			$(".add-ded .tboxs2mclass").css({"background-color":"#E0E1E3"});
			$(".add-ded .tboxs2mclass").css({"color":"#C1C2C4"});
			$(".add-ded .tboxs2mclass").css("border","1px solid #ABABAB");
			$(".disprow").addClass("hide");
		}
	});
	
	
	
	/// FOR NEW MAIN DATA - STARTS HERE //////
	//var index = 1;
	$(".AddIcon").click(function(event){ 
		var ParTabId 	= $(this).parents('table').attr('id');
		var RowID 		= $(this).attr("data-index");
		var itemCode 	= $("#txt_code"+RowID).val();
		var itemDesc 	= $("#txt_desc"+RowID).val();
		var itemId 	 	= $("#txt_item_id"+RowID).val();
		var itemRate 	= $("#txt_rate"+RowID).val();
		var origRate 	= $("#hid_rate"+RowID).val();
		var itemUnit 	= $("#txt_unit"+RowID).val();
		var itemQty 	= $("#txt_quantity"+RowID).val();
		var itemAmt 	= $("#txt_amount"+RowID).val();
		var calcDesc 	= $("#txt_curr_calc_desc"+RowID).val();
		var altItemDesc = $("#txt_curr_item_desc_alt"+RowID).val();
		var qtyDesc 	= $("#txt_curr_qty_desc_alt"+RowID).val();
		var rowTitle 	= $("#txt_curr_title"+RowID).val();
		var Actions 	= $("#txt_curr_action"+RowID).val();
		var Factors 	= $("#txt_curr_factor"+RowID).val();
		var Indexes 	= $("#txt_curr_calc_index"+RowID).val();
		var calcType 	= $("#hid_calc_type"+RowID).val();
		var amtType 	= $("#hid_amt_type"+RowID).val();
		var refId 		= $("#hid_ref_id"+RowID).val();
		var itemtype 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('item_type'); 
		var Error = 1;
		if(itemCode == ""){ 
			BootstrapDialog.alert("Please Enter Item Code");
			event.preventDefault();
			event.returnValue = false;
		}else if(itemDesc == ""){ 
			BootstrapDialog.alert("Please Enter Item Description");
			event.preventDefault();
			event.returnValue = false;
		}else if(itemRate == ""){ 
			BootstrapDialog.alert("Please Enter Item Rate");
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
		}else if(itemAmt == ""){ 
			BootstrapDialog.alert("Please Enter Item Amount");
			event.preventDefault();
			event.returnValue = false;
		}else{
			if(itemAmt != ''){
				itemAmt = Number(itemAmt).toFixed(2);
			}
			if(ParTabId == 'MT'){
				$('#MT').find('tr:last').prev().after('<tr><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass icode" list="ItemCodeListNew'+index+'" size="10" style="width:100%;" name="txt_code[]" id="txt_code'+index+'" data-index="'+index+'" value="'+itemCode+'" /><datalist id="ItemCodeListNew'+index+'" style="color:#C80B5B; font-size:16px"></datalist></td><td class="labelcenter" valign="middle" nowrap="nowrap"><input type="text" class="tboxsmclass idesc" list="ItemDescListNew'+index+'" data-index="'+index+'" size="25" style="width:90%;" name="txt_desc[]" id="txt_desc'+index+'" value="'+itemDesc+'" /><datalist id="ItemDescListNew'+index+'" style="color:#C80B5B; font-size:16px"></datalist>&nbsp;<i class="fa fa fa-paperclip cmd-box cmd-box-m" id="CmdBox'+index+'" data-index="'+index+'"></i><input type="hidden" class="labelfield" size="9" style="width:100%" name="txt_item_id[]" id="txt_item_id'+index+'" value="'+itemId+'" readonly="" /><textarea name="txt_curr_calc_desc[]" id="txt_curr_calc_desc'+index+'" style="display:none;">'+calcDesc+'</textarea><textarea name="txt_curr_qty_desc_alt[]" id="txt_curr_qty_desc_alt'+index+'" style="display:none;">'+qtyDesc+'</textarea><textarea name="txt_curr_item_desc_alt[]" id="txt_curr_item_desc_alt'+index+'" style="display:none;">'+altItemDesc+'</textarea><input type="hidden" name="txt_curr_action[]" id="txt_curr_action'+index+'" value="'+Actions+'"><input type="hidden" name="txt_curr_factor[]" id="txt_curr_factor'+index+'" value="'+Factors+'"><input type="hidden" name="txt_curr_calc_index[]" id="txt_curr_calc_index'+index+'" value="'+Indexes+'"><input type="hidden" name="hid_rate[]" id="hid_rate'+index+'" value="'+origRate+'"><input type="hidden" name="hid_calc_type[]" id="hid_calc_type'+index+'" value="'+calcType+'"><input type="hidden" name="hid_amt_type[]" id="hid_amt_type'+index+'" value="'+amtType+'"><input type="hidden" name="hid_ref_id[]" id="hid_ref_id'+index+'" value="'+refId+'"><input type="hidden" name="txt_curr_title[]" id="txt_curr_title'+index+'" value="'+rowTitle+'"></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext disable" size="5" style="width:100%" name="txt_rate[]" id="txt_rate'+index+'" value="'+itemRate+'" readonly="" /></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass ctext disable" size="5" style="width:100%" name="txt_unit[]" id="txt_unit'+index+'" value="'+itemUnit+'" readonly="" /></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext Qty" size="5" style="width:100%" data-index="'+index+'" name="txt_quantity[]" id="txt_quantity'+index+'" value="'+itemQty+'" /></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext disable NewAmtMat" size="8" style="width:100%" name="txt_amount[]" id="txt_amount'+index+'" value="'+itemAmt+'" readonly="" /></td><td class="labelcenter DataRow'+index+'" valign="middle"><i style="font-size:21px" class="fa faicon-del delete" name="btn_delete" id="btn_delete'+index+'" data-index="'+index+'">&#xf057;</i><input type="hidden" name="txt_item_type[]" id="txt_item_type'+index+'" value="'+itemtype+'"></td></tr>'); 
				//add input box
				$('#ItemCodeListNew0').find('option').clone().appendTo('#ItemCodeListNew'+index);
				$('#ItemDescListNew0').find('option').clone().appendTo('#ItemDescListNew'+index);
				FindTotalAmountNewMat();
			}else{
				$('#LT').find('tr:last').prev().after('<tr><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass icode" list="ItemCodeListNew'+index+'" size="10" style="width:100%;" name="txt_code[]" id="txt_code'+index+'" data-index="'+index+'" value="'+itemCode+'" /><datalist id="ItemCodeListNew'+index+'" style="color:#C80B5B; font-size:16px"></datalist></td><td class="labelcenter" valign="middle" nowrap="nowrap"><input type="text" class="tboxsmclass idesc" list="ItemDescListNew'+index+'" data-index="'+index+'" size="25" style="width:90%;" name="txt_desc[]" id="txt_desc'+index+'" value="'+itemDesc+'" /><datalist id="ItemDescListNew'+index+'" style="color:#C80B5B; font-size:16px"></datalist>&nbsp;<i class="fa fa fa-paperclip cmd-box cmd-box-m" id="CmdBox'+index+'" data-index="'+index+'"></i><input type="hidden" class="labelfield" size="9" style="width:100%" name="txt_item_id[]" id="txt_item_id'+index+'" value="'+itemId+'" readonly="" /><textarea name="txt_curr_calc_desc[]" id="txt_curr_calc_desc'+index+'" style="display:none;">'+calcDesc+'</textarea><textarea name="txt_curr_qty_desc_alt[]" id="txt_curr_qty_desc_alt'+index+'" style="display:none;">'+qtyDesc+'</textarea><textarea name="txt_curr_item_desc_alt[]" id="txt_curr_item_desc_alt'+index+'" style="display:none;">'+altItemDesc+'</textarea><input type="hidden" name="txt_curr_action[]" id="txt_curr_action'+index+'" value="'+Actions+'"><input type="hidden" name="txt_curr_factor[]" id="txt_curr_factor'+index+'" value="'+Factors+'"><input type="hidden" name="txt_curr_calc_index[]" id="txt_curr_calc_index'+index+'" value="'+Indexes+'"><input type="hidden" name="hid_rate[]" id="hid_rate'+index+'" value="'+origRate+'"><input type="hidden" name="hid_calc_type[]" id="hid_calc_type'+index+'" value="'+calcType+'"><input type="hidden" name="hid_amt_type[]" id="hid_amt_type'+index+'" value="'+amtType+'"><input type="hidden" name="hid_ref_id[]" id="hid_ref_id'+index+'" value="'+refId+'"><input type="hidden" name="txt_curr_title[]" id="txt_curr_title'+index+'" value="'+rowTitle+'"></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext disable" size="5" style="width:100%" name="txt_rate[]" id="txt_rate'+index+'" value="'+itemRate+'" readonly="" /></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass ctext disable" size="5" style="width:100%" name="txt_unit[]" id="txt_unit'+index+'" value="'+itemUnit+'" readonly="" /></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext Qty" size="5" style="width:100%" data-index="'+index+'" name="txt_quantity[]" id="txt_quantity'+index+'" value="'+itemQty+'" /></td><td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext disable NewAmtLab" size="8" style="width:100%" name="txt_amount[]" id="txt_amount'+index+'" value="'+itemAmt+'" readonly="" /></td><td class="labelcenter DataRow'+index+'" valign="middle"><i style="font-size:21px" class="fa faicon-del delete" name="btn_delete" id="btn_delete'+index+'" data-index="'+index+'">&#xf057;</i><input type="hidden" name="txt_item_type[]" id="txt_item_type'+index+'" value="'+itemtype+'"></td></tr>'); 
				//add input box
				$('#ItemCodeListNew00').find('option').clone().appendTo('#ItemCodeListNew'+index);
				$('#ItemDescListNew00').find('option').clone().appendTo('#ItemDescListNew'+index);
				FindTotalAmountNewLab();
			}
			index++;
			ClearNew();
			FindTotalAmount();
			CalulateAddDeduct();
			
		}
	});
	
	$(".DataSheetTable").on("change", ".icode", function() {
		var ParTabId 	= $(this).parents('table').attr('id');
		var itemCode 	= $(this).val(); 
		var RowID 		= $(this).attr("data-index");
		$("#txt_item_id"+RowID).val('');
		$("#txt_desc"+RowID).val('');
		$("#txt_unit"+RowID).val('');	
		$("#txt_rate"+RowID).val('');
		$("#hid_rate"+RowID).val('');
		$("#txt_quantity"+RowID).val('');
		$("#txt_amount"+RowID).val('');
		$("#txt_curr_calc_desc"+RowID).val('');
		$("#hid_calc_type"+RowID).val('');
		$("#hid_amt_type"+RowID).val('');
		$("#hid_ref_id"+RowID).val('');
		
		var itemId 		= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('item_id');//alert(itemId);
		var itemDesc 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('desc');//alert(id);
		var itemUnit 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('unit');//alert(refid);
		var itemRate 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('price');//alert(groupid);
		var Type 		= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('type');
		var CalcType 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('calc_type');
		
		if(Type == "SD"){
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
								url: 'find_rate_calculation_hc.php', 
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
										$("#hid_rate"+RowID).val(ItemAmount.toFixed(2));
										$("#txt_quantity"+RowID).val('-');
										$("#txt_amount"+RowID).val(ItemAmount.toFixed(2));
										$("#txt_curr_calc_desc"+RowID).val('');
										$("#hid_ref_id"+RowID).val(refid);
										
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
					url: 'find_rate_calculation_hc.php', 
					data: { groupid: groupid, id: id, parid: parid, refid: refid }, 
					dataType: 'json',
					success: function (data) {  
						if(data != null){ 
							var TSRate 		= data['TSRate'];
							var IGCARRate 	= data['IGCARRate1'];
							var ItemAmount 	= data['TotalAmount'];
							var MasterUnit 	= data['MasterUnit'];
							
							var MasterDesc 	= data['MasterDesc'];
							$("#hid_calc_type"+RowID).val('WOC');
							$("#hid_amt_type"+RowID).val('GAMT');
							
							$("#txt_item_id"+RowID).val(itemId);
							$("#txt_desc"+RowID).val(MasterDesc);
							$("#txt_unit"+RowID).val(MasterUnit);	
							$("#txt_rate"+RowID).val(ItemAmount.toFixed(2));
							$("#hid_rate"+RowID).val(ItemAmount.toFixed(2));
							$("#txt_quantity"+RowID).val('-');
							$("#txt_amount"+RowID).val(ItemAmount.toFixed(2));
							$("#txt_curr_calc_desc"+RowID).val('');
							$("#hid_ref_id"+RowID).val(refid);
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
		}else{
			$("#txt_item_id"+RowID).val(itemId);
			$("#txt_desc"+RowID).val(itemDesc);
			$("#txt_unit"+RowID).val(itemUnit);	
			$("#txt_rate"+RowID).val(itemRate);
			$("#hid_rate"+RowID).val(itemRate);
			$("#txt_quantity"+RowID).val('');
			$("#txt_amount"+RowID).val('');
			$("#txt_curr_calc_desc"+RowID).val('');
		}
		/*$("#txt_item_id"+RowID).val(itemId);
		$("#txt_desc"+RowID).val(itemDesc);
		$("#txt_unit"+RowID).val(itemUnit);	
		$("#txt_rate"+RowID).val(itemRate);
		$("#txt_quantity"+RowID).val('');
		$("#txt_amount"+RowID).val('');
		$("#txt_curr_calc_desc"+RowID).val('');*/
	});
	$(".DataSheetTable").on("change", ".idesc", function() {
		var ParTabId 	= $(this).parents('table').attr('id');
		var itemDesc 	= $(this).val(); 
		var RowID 		= $(this).attr("data-index");
		var itemId 		= $('#ItemDescListNew'+RowID+' [value="' + itemDesc + '"]').data('item_id');//alert(parid);
		var itemCode 	= $('#ItemDescListNew'+RowID+' [value="' + itemDesc + '"]').data('item_code');//alert(id);
		var itemUnit 	= $('#ItemDescListNew'+RowID+' [value="' + itemDesc + '"]').data('unit');//alert(refid);
		var itemRate 	= $('#ItemDescListNew'+RowID+' [value="' + itemDesc + '"]').data('price');//alert(groupid);
		$("#txt_item_id"+RowID).val(itemId);
		$("#txt_code"+RowID).val(itemCode);
		$("#txt_unit"+RowID).val(itemUnit);	
		$("#txt_rate"+RowID).val(itemRate);
		$("#hid_rate"+RowID).val(itemRate);
		$("#txt_quantity"+RowID).val('');
		$("#txt_amount"+RowID).val('');
		$("#txt_curr_calc_desc"+RowID).val('');
	});
	$(".DataSheetTable").on("change", ".Qty", function() {
		var ParTabId 	= $(this).parents('table').attr('id');
		var Qty 	= $(this).val(); 
		var RowID 	= $(this).attr("data-index"); 
		var Rate 	= $('#txt_rate'+RowID).val();
		var Amount 	= Number(Qty)*Number(Rate); 
			Amount = roundOff(Amount,2);
			if(Amount != ''){ Amount = Amount.toFixed(2); }
		 $("#txt_amount"+RowID).val(Amount);
		 if(RowID != 0){ 
		 	if(ParTabId == 'MT'){
		 		FindTotalAmountNewMat();
			}else{
				FindTotalAmountNewLab();
		 	}
			FindTotalAmount();
		 	CalulateAddDeduct();
		 }
	});
	$(".DataSheetTable").on("click", ".delete", function() {
		//var trClass = $(this).closest("tr").attr("class");
		var ParTabId 	= $(this).parents('table').attr('id');
		var RowID 	= $(this).attr("data-index"); 
		$(".DataRow"+RowID).closest("tr").remove();
    	//$(this).closest("tr").remove();
		if(ParTabId == 'MT'){
		 	FindTotalAmountNewMat();
		}else{
			FindTotalAmountNewLab();
		 }
		 FindTotalAmount();
		 CalulateAddDeduct();
    });
	$(".DataSheetTable").on("click", "#btn_clear", function() {
		var ParTabId 	= $(this).parents('table').attr('id');
		ClearNew();
	});
	/// FOR NEW MAIN DATA - ENDS HERE //////
	
	
	/// FOR MERGE WITH SUB DATA - STARTS HERE //////
	
	//$('#txt_item_id_sd0').change(function(){
	$("#tab_sd_material").on("change", ".icodesd", function() {
		var ParTabId 	= $(this).parents('table').attr('id');
		var itemCode 	= $(this).val();
		var RowID 		= $(this).attr("data-index"); //alert()
		$("#txt_refid_sd"+RowID).val('');
		$("#hid_igc_rate_sd"+RowID).val('');
		$("#hid_ts_rate_sd"+RowID).val('');
		$("#txt_curr_calc_desc_sd"+RowID).val('');
		var parid 	= $('#ItemCodeListSD'+RowID+' [value="' + itemCode + '"]').data('par_id');//alert(parid);
		var id 		= $('#ItemCodeListSD'+RowID+' [value="' + itemCode + '"]').data('id');//alert(id);
		var refid 	= $('#ItemCodeListSD'+RowID+' [value="' + itemCode + '"]').data('ref_id');//alert(refid);
		var groupid = $('#ItemCodeListSD'+RowID+' [value="' + itemCode + '"]').data('group_id');//alert(groupid);
		$.ajax({ 
			type: 'POST', 
			url: 'find_rate_calculation_hc.php', 
			data: { groupid: groupid, id: id, parid: parid, refid: refid }, 
			dataType: 'json',
			success: function (data) {  //alert(data);
				if(data != null){
					var TSRate 		= data['TSRate']; 
					var IGCARRate 	= data['IGCARRate1']; 
					var MasterDesc 	= data['MasterDesc'];
					var DisQtyPerc 	= Number(data['DisQtyPerc']);
					if(DisQtyPerc != 0){
						var TSRateTemp 	  = Number(TSRate) * Number(DisQtyPerc) / 100;
							TSRate 		  = TSRateTemp.toFixed(2);
						var IGCARRateTemp = Number(IGCARRate) * Number(DisQtyPerc) / 100;
							IGCARRate 	  = IGCARRateTemp.toFixed(2);
					}
					$("#txt_ts_rate_sd"+RowID).val(TSRate);
					$("#txt_igc_rate_sd"+RowID).val(IGCARRate);
					$("#txt_desc_sd"+RowID).val(MasterDesc);
					$("#txt_refid_sd"+RowID).val(refid);
					$("#hid_igc_rate_sd"+RowID).val(IGCARRate);
					$("#hid_ts_rate_sd"+RowID).val(TSRate);
					if(RowID != '0'){
						FindTotalAmountSD();
						if($("#is_average").is(':checked')){
							FindAverageAmountSD();
						}
					}
				}
			}
		});
	});
	
	//var index2 = 1;
	$("#btn_add_sd").click(function(event){ 
		var ParTabId 	= $(this).parents('table').attr('id');
		var itemId 		= $("#txt_item_id_sd0").val();
		var itemRefId 	= $("#txt_refid_sd0").val();
		var itemDesc 	= $("#txt_desc_sd0").val();
		var TsRate 		= $("#txt_ts_rate_sd0").val();
		var IgcRate 	= $("#txt_igc_rate_sd0").val();
		var OrigIgcRate = $("#hid_igc_rate_sd0").val();
		var OrigTsRate 	= $("#hid_ts_rate_sd0").val();
		var calcDesc 	= $("#txt_curr_calc_desc_sd0").val();
		var altItemDesc = $("#txt_curr_item_desc_alt_sd0").val();
		var qtyDesc 	= $("#txt_curr_qty_desc_sd0").val();
		var rowTitle 	= $("#txt_curr_title_sd0").val();
		
		var calcDesc 	= $("#txt_curr_calc_desc_sd0").val();
		var altItemDesc = $("#txt_curr_item_desc_alt_sd0").val();
		var Actions 	= $("#txt_curr_action_sd0").val();
		var Factors 	= $("#txt_curr_factor_sd0").val();
		var Indexes 	= $("#txt_curr_calc_index_sd0").val();
		//var calcType 	= $("#hid_calc_type_sd0").val();
		//var amtType 	= $("#hid_amt_type_sd0").val();
		var refId 		= $("#hid_ref_id_sd0").val();
		var Error = 1;
		if(itemId == ""){ 
			BootstrapDialog.alert("Please Enter Item Code");
			event.preventDefault();
			event.returnValue = false;
		}else if(itemDesc == ""){ 
			BootstrapDialog.alert("Please Enter Item Description");
			event.preventDefault();
			event.returnValue = false;
		}else if(TsRate == ""){ 
			BootstrapDialog.alert("Please Enter Township Rate");
			event.preventDefault();
			event.returnValue = false;
		}else if(IgcRate == ""){ 
			BootstrapDialog.alert("Please Enter IGCAR Rate");
			event.preventDefault();
			event.returnValue = false;
		}else{
			$('#tab_sd_material').find('tr:last').prev().prev().after('<tr><td><input type="text" class="tboxsmclass icodesd" style="width:100%;" name="txt_item_id_sd[]" id="txt_item_id_sd'+index2+'" value="'+itemId+'" data-index="'+index2+'" list="ItemCodeListSD'+index2+'"/><datalist id="ItemCodeListSD'+index2+'" style="color:#C80B5B; font-size:16px"></datalist><input type="hidden" class="tboxsmclass" style="width:100%" name="txt_refid_sd[]" id="txt_refid_sd'+index2+'" value="'+itemRefId+'" readonly="" /></td><td><input type="text" class="tboxsmclass disable" style="width:92%" name="txt_desc_sd[]" id="txt_desc_sd'+index2+'" value="'+itemDesc+'" readonly="" />&nbsp;<i class="fa fa fa-paperclip cmd-box cmd-box-sd" id="CmdBox'+index2+'" data-index="'+index2+'"></i><textarea name="txt_curr_calc_desc_sd[]" id="txt_curr_calc_desc_sd'+index2+'" style="display:none;">'+calcDesc+'</textarea><textarea name="txt_curr_qty_desc_sd[]" id="txt_curr_qty_desc_sd'+index2+'" style="display:none;">'+qtyDesc+'</textarea><textarea name="txt_curr_item_desc_alt_sd[]" id="txt_curr_item_desc_alt_sd'+index2+'" style="display:none;">'+altItemDesc+'</textarea><input type="hidden" name="hid_igc_rate_sd[]" id="hid_igc_rate_sd'+index2+'" value="'+OrigIgcRate+'"><input type="hidden" name="hid_ts_rate_sd[]" id="hid_ts_rate_sd'+index2+'" value="'+OrigTsRate+'"><input type="hidden" name="txt_curr_action_sd[]" id="txt_curr_action_sd'+index2+'" value="'+Actions+'"><input type="hidden" name="txt_curr_factor_sd[]" id="txt_curr_factor_sd'+index2+'" value="'+Factors+'"><input type="hidden" name="txt_curr_calc_index_sd[]" id="txt_curr_calc_index_sd'+index2+'" value="'+Indexes+'"><input type="hidden" name="txt_curr_title_sd[]" id="txt_curr_title_sd'+index2+'" value="'+rowTitle+'"></td><td><input type="text" class="tboxsmclass tsAmt disable rtext" style="width:100%" name="txt_ts_rate_sd[]" id="txt_ts_rate_sd'+index2+'" value="'+TsRate+'" readonly="" /></td><td><input type="text" class="tboxsmclass igcAmt disable rtext" style="width:100%" data-index="'+index2+'" name="txt_igc_rate_sd[]" id="txt_igc_rate_sd'+index2+'" value="'+IgcRate+'" /></td><td align="center"><i style="font-size:21px" class="fa faicon-del delete" name="btn_delete" id="btn_delete'+index2+'">&#xf057;</i></td></tr>'); //add input box
			$('#ItemCodeListSD0').find('option').clone().appendTo('#ItemCodeListSD'+index2);
			index2++;
			ClearSD();
			FindTotalAmountSD();
			if($("#is_average").is(':checked')){
				FindAverageAmountSD();
			}
		}
	});
	$("#tab_sd_material").on("click", ".delete", function() {
		var ParTabId 	= $(this).parents('table').attr('id');
    	$(this).closest("tr").remove();
		FindTotalAmountSD();
		if($("#is_average").is(':checked')){
			FindAverageAmountSD();
		}
    });
	$("#tab_sd_material").on("click", "#btn_clear", function() {
		var ParTabId 	= $(this).parents('table').attr('id');
		ClearSD();
	});
	/*$("body").on("click",".cmd-box-m",function(){
		var RowID 			 = $(this).attr("data-index");
		var ExistCalcVal 	 = $("#txt_curr_calc_desc"+RowID).val();
		var ExistItemDescVal = $("#txt_curr_item_desc_alt"+RowID).val();
		//alert(RowID); alert(ExistVal);
		BootstrapDialog.show({
            title: 'Description of Calculation',
            message: $('<div>Item Description</div><textarea class="form-control" placeholder="Add your description here..." id="txt_item_desc_alt" rows="2">'+ExistItemDescVal+'</textarea><div>&nbsp;</div><div>Calculation Description</div><textarea class="form-control" placeholder="Add your calculation description here..." id="txt_calc_content" rows="6">'+ExistCalcVal+'</textarea>'),
            buttons: [{
                label: ' SAVE ',
                cssClass: 'btn-primary',
                action: function(dialogRef) {
					var CalcDesc = $("#txt_calc_content").val();
					var AltItemDesc = $("#txt_item_desc_alt").val();
					$("#txt_curr_calc_desc"+RowID).val(CalcDesc);
					$("#txt_curr_item_desc_alt"+RowID).val(AltItemDesc);
                   dialogRef.close();
                }
            }]
        });
	});*/
	
	$("body").on("click","#CostDtBox",function(){
		var ExistVal = $("#txt_cost_det").val();
		//alert(RowID); alert(ExistVal);
		BootstrapDialog.show({
            title: 'Description of Cost Details',
            message: $('<textarea class="form-control" placeholder="Add your description here..." id="txt_modal_cost_det" rows="6">'+ExistVal+'</textarea>'),
            buttons: [{
                label: ' SAVE ',
                cssClass: 'btn-primary',
                action: function(dialogRef) {
					var CalcDesc = $("#txt_modal_cost_det").val();
					$("#txt_cost_det").val(CalcDesc);
                   dialogRef.close();
                }
            }]
        });
	});
	
	$("body").on("click",".cmd-box-sd",function(){
		$('.R-ROW-SD').remove();
		$("#cmb_action_modal_sd0").val('');
		$("#txt_action_factor_modal_sd0").val('');
		$("#txt_igc_amount_modal_sd0").val('');
		$("#txt_ts_amount_modal_sd0").val('');
		$("#txt_w_igc_amt_modal0").val('');
		$("#txt_w_ts_amt_modal0").val('');
		$("#txt_new_igc_final_rate_sd").val('');
		$("#txt_new_ts_final_rate_sd").val('');
		$("#ItemCodeDescModalSD").html('');
		var RowID 	= $(this).attr("data-index");
		var ItemCode = $("#txt_item_id_sd"+RowID).val(); 
		var ItemDesc = $("#txt_desc_sd"+RowID).val(); 
		var ItemIgcRate = $("#hid_igc_rate_sd"+RowID).val();
		var ItemTwRate = $("#hid_ts_rate_sd"+RowID).val();
		$("#modal_index_sd").val('');
		//var Rate 	= $("#hid_rate_sd"+RowID).val();
		if((ItemCode != '')&&(ItemCode != "")){
			$("#ItemCodeDescModalSD").html("<span class='titlebox'>"+ItemCode+" - "+ItemDesc+"</span>");
		}
		//alert(RowID); alert(ItemIgcRate); alert(ItemTwRate);
		$("#txt_w_igc_amt_modal0").val(Number(ItemIgcRate).toFixed(2));
		$("#txt_w_ts_amt_modal0").val(Number(ItemTwRate).toFixed(2));
		//}
		$("#modal_index_sd").val(RowID);
		
		var CurrItemDesc 	= $("#txt_curr_item_desc_alt_sd"+RowID).val();
		var CurrQtyDesc 	= $("#txt_curr_qty_desc_alt_sd"+RowID).val();
		var CurrCalcDesc 	= $("#txt_curr_calc_desc_sd"+RowID).val();
		var CurrTitle 		= $("#txt_curr_title_sd"+RowID).val();
			
		$("#txt_item_desc_alt_sd").val(CurrItemDesc);
		$("#txt_qty_dest_alt_sd").val(CurrQtyDesc);
		$("#txt_calc_desc_sd").val(CurrCalcDesc);
		$("#txt_title_sd").val(CurrTitle);
		
		var CurrAction 	= $("#txt_curr_action_sd"+RowID).val();
		var Currfactor 	= $("#txt_curr_factor_sd"+RowID).val();
		var CurrIndex 	= $("#txt_curr_calc_index_sd"+RowID).val();
		//alert(CurrAction); 
		var IgcAmount = Number(ItemIgcRate);
		var TwAmount = Number(ItemTwRate); 
		if(CurrIndex != ''){
			var SplitAction = CurrAction.split(",");
			var SplitFactor = Currfactor.split(",");
			var SplitIndex 	= CurrIndex.split(",");
			var AppendContent = '';
			for( var i = 0; i<SplitIndex.length; i++ ) {
				var IndexX  = SplitIndex[i];
				var ActionX = SplitAction[i];
				var FactorX = SplitFactor[i];
				var CurrIgcAmt = IgcAmount;
				var CurrTwAmt = TwAmount;
				var Asel = ''; var Ssel = ''; var Msel = ''; var Dsel = ''; var Psel = '';
				if((ActionX != '')&&(FactorX != '')){
					if(ActionX == "A"){
						IgcAmount = Number(CurrIgcAmt)+Number(FactorX);
						TwAmount = Number(CurrTwAmt)+Number(FactorX);
						Asel = ' selected="selected"';
					}else if(ActionX == "S"){
						IgcAmount = Number(CurrIgcAmt)-Number(FactorX);
						TwAmount = Number(CurrTwAmt)-Number(FactorX);
						Ssel = ' selected="selected"';
					}else if(ActionX == "M"){
						IgcAmount = Number(CurrIgcAmt)*Number(FactorX);
						TwAmount = Number(CurrTwAmt)*Number(FactorX);
						Msel = ' selected="selected"';
					}else if(ActionX == "D"){
						IgcAmount = Number(CurrIgcAmt)/Number(FactorX);
						TwAmount = Number(CurrTwAmt)/Number(FactorX);
						Dsel = ' selected="selected"';
					}else if(ActionX == "P"){
						IgcAmount = Number(CurrIgcAmt)*Number(FactorX)/100;
						TwAmount = Number(CurrTwAmt)*Number(FactorX)/100;
						Psel = ' selected="selected"';
					}
					CurrIgcAmt = Number(CurrIgcAmt.toFixed(2));
					CurrTwAmt = Number(CurrTwAmt.toFixed(2));
				}
				
				if(i == 0){
					$("#cmb_action_modal_sd0").val(ActionX);
					$("#txt_action_factor_modal_sd0").val(FactorX);
					$("#txt_igc_amount_modal_sd0").val(IgcAmount.toFixed(2));
					$("#txt_ts_amount_modal_sd0").val(TwAmount.toFixed(2));
				}else{ 
					AppendContent += '<div class="row ModalCalcRowSD R-ROW-SD" id="ModalCalcRowSD'+IndexX+'">';
					AppendContent += '<div class="div2" align="center"><input type="text" name="txt_w_igc_amt_modal0[]" id="txt_w_igc_amt_modal0'+IndexX+'" class="sboxsmclass rtext" data-index="'+IndexX+'" readonly="" value="'+CurrIgcAmt+'"/></div>';
					AppendContent += '<div class="div2" align="center"><input type="text" name="txt_w_ts_amt_modal0[]" id="txt_w_ts_amt_modal0'+IndexX+'" class="sboxsmclass rtext" data-index="'+IndexX+'" readonly="" value="'+CurrTwAmt+'"/></div>';
					AppendContent += '<div class="div2" align="center"><select name="cmb_action_modal_sd[]" id="cmb_action_modal_sd'+IndexX+'" class="sboxsmclass ModalActionSD" data-index="'+IndexX+'">';
					AppendContent += '<option value="">------ Select Action ------</option>';
					AppendContent += '<option value="A" '+Asel+'>Addition</option>';
					AppendContent += '<option value="M" '+Msel+'>Multiplication</option>';
					AppendContent += '<option value="S" '+Ssel+'>Subtraction</option>';
					AppendContent += '<option value="D" '+Dsel+'>Division</option>';
					AppendContent += '<option value="P" '+Psel+'>Percentage</option>';
					AppendContent += '</select></div>';
					AppendContent += '<div class="div1" align="center"><input type="text" name="txt_action_factor_modal_sd[]" id="txt_action_factor_modal_sd'+IndexX+'" class="sboxsmclass rtext ModalFactorSD" data-index="'+IndexX+'" value="'+FactorX+'"/></div>';
					AppendContent += '<div class="div2" align="center"><input type="text" name="txt_igc_amount_modal_sd[]" id="txt_igc_amount_modal_sd'+IndexX+'" class="sboxsmclass rtext" data-index="'+IndexX+'" readonly="" value="'+IgcAmount+'"/></div>';
					AppendContent += '<div class="div2" align="center"><input type="text" name="txt_ts_amount_modal_sd[]" id="txt_ts_amount_modal_sd'+IndexX+'" class="sboxsmclass rtext" data-index="'+IndexX+'" readonly="" value="'+TwAmount+'"/></div>';
					AppendContent += '<div class="div1" align="center">';
					AppendContent += '<i style="font-size:24px" class="fa faicon-add modal-add-row-sd" name="btn_add_modal_sd" id="btn_add_modal_sd'+IndexX+'" data-index="'+IndexX+'">&#xf01a;</i>&nbsp;';
					AppendContent += '<i style="font-size:24px" class="fa faicon-clr modal-clr-row-sd" name="btn_clear_modal_sd" id="btn_clear_modal_sd'+IndexX+'" data-index="'+IndexX+'">&#xf05c;</i>';
					AppendContent += '</div></div>';
				}
			}
			$('#ModalCalcRowSD0').after(AppendContent);
		}
		$("#txt_new_igc_final_rate_sd").val(roundOff(IgcAmount,2));//(Amount.toFixed(2));
		$("#txt_new_ts_final_rate_sd").val(roundOff(TwAmount,2));//(Amount.toFixed(2));
		
		
		$('#myModalSD').modal();
	});		
	
	$("body").on("click",".cmd-box-m",function(){
											   
		$('.R-ROW').remove();
		$("#cmb_action_modal0").val('');
		$("#txt_action_factor_modal0").val('');
		$("#txt_amount_modal0").val('');
		$("#txt_w_amt_modal0").val('');
		$("#txt_new_final_rate").val('');
		$("#ItemCodeDescModal").html('');
		var RowID 	= $(this).attr("data-index");
		
		var itemCodeVal = $("#txt_code"+RowID).val();
		var itemDescVal = $("#txt_desc"+RowID).val();
		if(itemCodeVal == ""){ 
			BootstrapDialog.alert("Please Enter Item Code");
			event.preventDefault();
			event.returnValue = false;
			exit();
		}else if(itemDescVal == ""){ 
			BootstrapDialog.alert("Please Enter Item Description");
			event.preventDefault();
			event.returnValue = false;
			exit();
		}
		
		
		var ItemCode = $("#txt_code"+RowID).val();
		var ItemDesc = $("#txt_desc"+RowID).val();
		var ItemRate = $("#txt_rate"+RowID).val();
		$("#modal_index").val('');
		var Rate 	= $("#hid_rate"+RowID).val();//$("#txt_rate"+RowID).val();
		if((ItemCode != '')&&(ItemCode != "")){
			$("#ItemCodeDescModal").html("<span class='titlebox'>"+ItemCode+" - "+ItemDesc+" - <span style='color:#072FEB'>&#8377 "+Rate+"</span></span>");
		}
		//$("#txt_w_amt_modal"+RowID).val(Rate);
		//if(Rate != ''){
		$("#txt_w_amt_modal0").val(Number(Rate).toFixed(2));
		//}
		$("#modal_index").val(RowID);
		
		var CurrItemDesc 	= $("#txt_curr_item_desc_alt"+RowID).val();
		var CurrQtyDesc 	= $("#txt_curr_qty_desc_alt"+RowID).val();
		var CurrCalcDesc 	= $("#txt_curr_calc_desc"+RowID).val();
		var CurrTitle 		= $("#txt_curr_title"+RowID).val();
			
		$("#txt_item_desc_alt").val(CurrItemDesc);
		$("#txt_qty_dest_alt").val(CurrQtyDesc);
		$("#txt_calc_desc").val(CurrCalcDesc);
		$("#txt_title").val(CurrTitle);
		
		var CurrAction 	= $("#txt_curr_action"+RowID).val();
		var Currfactor 	= $("#txt_curr_factor"+RowID).val();
		var CurrIndex 	= $("#txt_curr_calc_index"+RowID).val();
		var Amount = Number(Rate);
		if(CurrIndex != ''){
			var SplitAction = CurrAction.split(",");
			var SplitFactor = Currfactor.split(",");
			var SplitIndex 	= CurrIndex.split(",");
			var AppendContent = '';
			for( var i = 0; i<SplitIndex.length; i++ ) {
				var IndexX  = SplitIndex[i];
				var ActionX = SplitAction[i];
				var FactorX = SplitFactor[i];
				var CurrAmt = Amount;
				var Asel = ''; var Ssel = ''; var Msel = ''; var Dsel = ''; var Psel = '';
				if((ActionX != '')&&(FactorX != '')){
					if(ActionX == "A"){
						Amount = Number(CurrAmt)+Number(FactorX);
						Asel = ' selected="selected"';
					}else if(ActionX == "S"){
						Amount = Number(CurrAmt)-Number(FactorX);
						Ssel = ' selected="selected"';
					}else if(ActionX == "M"){
						Amount = Number(CurrAmt)*Number(FactorX);
						Msel = ' selected="selected"';
					}else if(ActionX == "D"){
						Amount = Number(CurrAmt)/Number(FactorX);
						Dsel = ' selected="selected"';
					}else if(ActionX == "P"){
						Amount = Number(CurrAmt)*Number(FactorX)/100;
						Psel = ' selected="selected"';
					}
				}
				
				if(i == 0){
					$("#cmb_action_modal0").val(ActionX);
					$("#txt_action_factor_modal0").val(FactorX);
					$("#txt_amount_modal0").val(Amount);
				}else{
					AppendContent += '<div class="row ModalCalcRow R-ROW" id="ModalCalcRow'+IndexX+'">';
					AppendContent += '<div class="div3" align="center"><input type="text" name="txt_w_amt_modal[]" id="txt_w_amt_modal'+IndexX+'" class="sboxsmclass rtext" data-index="'+IndexX+'" readonly="" value="'+CurrAmt+'"/></div>';
					AppendContent += '<div class="div3" align="center"><select name="cmb_action_modal[]" id="cmb_action_modal'+IndexX+'" class="sboxsmclass ModalAction" data-index="'+IndexX+'">';
					AppendContent += '<option value="">------ Select Action ------</option>';
					AppendContent += '<option value="A" '+Asel+'>Addition</option>';
					AppendContent += '<option value="M" '+Msel+'>Multiplication</option>';
					AppendContent += '<option value="S" '+Ssel+'>Subtraction</option>';
					AppendContent += '<option value="D" '+Dsel+'>Division</option>';
					AppendContent += '<option value="P" '+Psel+'>Percentage</option>';
					AppendContent += '</select></div>';
					AppendContent += '<div class="div2" align="center"><input type="text" name="txt_action_factor_modal[]" id="txt_action_factor_modal'+IndexX+'" class="sboxsmclass rtext ModalFactor" data-index="'+IndexX+'" value="'+FactorX+'"/></div>';
					AppendContent += '<div class="div3" align="center"><input type="text" name="txt_amount_modal[]" id="txt_amount_modal'+IndexX+'" class="sboxsmclass rtext" data-index="'+IndexX+'" readonly="" value="'+Amount+'"/></div>';
					AppendContent += '<div class="div1" align="center">';
					AppendContent += '<i style="font-size:24px" class="fa faicon-add modal-add-row" name="btn_add_modal" id="btn_add_modal'+IndexX+'" data-index="'+IndexX+'">&#xf01a;</i>&nbsp;';
					AppendContent += '<i style="font-size:24px" class="fa faicon-clr modal-clr-row" name="btn_clear_modal" id="btn_clear_modal'+IndexX+'" data-index="'+IndexX+'">&#xf05c;</i>';
					AppendContent += '</div></div>';
				}
			}
			$('#ModalCalcRow0').after(AppendContent);
		}
		$("#txt_new_final_rate").val(roundOff(Amount,2));//(Amount.toFixed(2));
		$('#myModal').modal();
	});
	//var cindex = 1;
	$("body").on("click",".modal-add-row",function(){
		var RowID = $(this).attr("data-index");
		var AppendContent  = '<div class="row ModalCalcRow R-ROW" id="ModalCalcRow'+cindex+'">';
			AppendContent += '<div class="div3" align="center"><input type="text" name="txt_w_amt_modal[]" id="txt_w_amt_modal'+cindex+'" class="sboxsmclass rtext" data-index="'+cindex+'" readonly=""/></div>';
			AppendContent += '<div class="div3" align="center"><select name="cmb_action_modal[]" id="cmb_action_modal'+cindex+'" class="sboxsmclass ModalAction" data-index="'+cindex+'">';
		 	AppendContent += '<option value="">------ Select Action ------</option>';
		 	AppendContent += '<option value="A">Addition</option>';
		 	AppendContent += '<option value="M">Multiplication</option>';
		 	AppendContent += '<option value="S">Subtraction</option>';
		 	AppendContent += '<option value="D">Division</option>';
		 	AppendContent += '<option value="P">Percentage</option>';
		 	AppendContent += '</select></div>';
		 	AppendContent += '<div class="div2" align="center"><input type="text" name="txt_action_factor_modal[]" id="txt_action_factor_modal'+cindex+'" class="sboxsmclass rtext ModalFactor" data-index="'+cindex+'"/></div>';
		 	AppendContent += '<div class="div3" align="center"><input type="text" name="txt_amount_modal[]" id="txt_amount_modal'+cindex+'" class="sboxsmclass rtext" data-index="'+cindex+'" readonly=""/></div>';
		 	AppendContent += '<div class="div1" align="center">';
		 	AppendContent += '<i style="font-size:24px" class="fa faicon-add modal-add-row" name="btn_add_modal" id="btn_add_modal'+cindex+'" data-index="'+cindex+'">&#xf01a;</i>&nbsp;';
		 	AppendContent += '<i style="font-size:24px" class="fa faicon-clr modal-clr-row" name="btn_clear_modal" id="btn_clear_modal'+cindex+'" data-index="'+cindex+'">&#xf05c;</i>';
			AppendContent += '</div></div>';
			$('#ModalCalcRow'+RowID).after(AppendContent);
			var Rate = $("#txt_amount_modal"+RowID).val();
			$("#txt_w_amt_modal"+cindex).val(Rate);
			cindex++;
	});
	//var cindexSD = 1;
	$("body").on("click",".modal-add-row-sd",function(){
		var RowID = $(this).attr("data-index");
		var AppendContent  = '<div class="row ModalCalcRowSD R-ROW-SD" id="ModalCalcRowSD'+cindexSD+'">';
			AppendContent += '<div class="div2" align="center"><input type="text" name="txt_w_igc_amt_modal[]" id="txt_w_igc_amt_modal'+cindexSD+'" class="sboxsmclass rtext" data-index="'+cindexSD+'" readonly=""/></div>';
			AppendContent += '<div class="div2" align="center"><input type="text" name="txt_w_ts_amt_modal[]" id="txt_w_ts_amt_modal'+cindexSD+'" class="sboxsmclass rtext" data-index="'+cindexSD+'" readonly=""/></div>';
			AppendContent += '<div class="div2" align="center"><select name="cmb_action_modal_sd[]" id="cmb_action_modal_sd'+cindexSD+'" class="sboxsmclass ModalActionSD" data-index="'+cindexSD+'">';
		 	AppendContent += '<option value="">---- Select ----</option>';
		 	AppendContent += '<option value="A">Addition</option>';
		 	AppendContent += '<option value="M">Multiplication</option>';
		 	AppendContent += '<option value="S">Subtraction</option>';
		 	AppendContent += '<option value="D">Division</option>';
		 	AppendContent += '<option value="P">Percentage</option>';
		 	AppendContent += '</select></div>';
		 	AppendContent += '<div class="div1" align="center"><input type="text" name="txt_action_factor_modal_sd[]" id="txt_action_factor_modal_sd'+cindexSD+'" class="sboxsmclass rtext ModalFactorSD" data-index="'+cindexSD+'"/></div>';
		 	AppendContent += '<div class="div2" align="center"><input type="text" name="txt_igc_amount_modal_sd[]" id="txt_igc_amount_modal_sd'+cindexSD+'" class="sboxsmclass rtext" data-index="'+cindexSD+'" readonly=""/></div>';
		 	AppendContent += '<div class="div2" align="center"><input type="text" name="txt_ts_amount_modal_sd[]" id="txt_ts_amount_modal_sd'+cindexSD+'" class="sboxsmclass rtext" data-index="'+cindexSD+'" readonly=""/></div>';
			AppendContent += '<div class="div1" align="center">';
		 	AppendContent += '<i style="font-size:24px" class="fa faicon-add modal-add-row-sd" name="btn_add_modal_sd" id="btn_add_modal_sd'+cindexSD+'" data-index="'+cindexSD+'">&#xf01a;</i>&nbsp;';
		 	AppendContent += '<i style="font-size:24px" class="fa faicon-clr modal-clr-row-sd" name="btn_clear_modal_sd" id="btn_clear_modal_sd'+cindexSD+'" data-index="'+cindexSD+'">&#xf05c;</i>';
			AppendContent += '</div></div>';
			$('#ModalCalcRowSD'+RowID).after(AppendContent);
			var IgcRate = $("#txt_igc_amount_modal_sd"+RowID).val();
			var TwRate = $("#txt_ts_amount_modal_sd"+RowID).val();
			$("#txt_w_igc_amt_modal"+cindexSD).val(IgcRate);
			$("#txt_w_ts_amt_modal"+cindexSD).val(TwRate);
			cindexSD++;
	});
	$("body").on("click",".modal-clr-row",function(){
		var RowID = $(this).attr("data-index");
		if(RowID == 0){
			BootstrapDialog.alert("You cant't able to remove first row.");
		}else{
			$('#ModalCalcRow'+RowID).remove();
			CalculateWorkSheetAction();
		}
	});
	$("body").on("click",".modal-clr-row-sd",function(){
		var RowID = $(this).attr("data-index");
		if(RowID == 0){
			BootstrapDialog.alert("You cant't able to remove first row.");
		}else{
			$('#ModalCalcRowSD'+RowID).remove();
			CalculateWorkSheetActionSD();
		}
	});
	$("body").on("change",".ModalAction",function(){
		var RowID = $(this).attr("data-index");										  
		CalculateWorkSheetAction();
	});
	$("body").on("change",".ModalActionSD",function(){
		var RowID = $(this).attr("data-index");										  
		CalculateWorkSheetActionSD();
	});
	$("body").on("change",".ModalFactor",function(){
		var RowID = $(this).attr("data-index");											  
		CalculateWorkSheetAction();
	});
	$("body").on("change",".ModalFactorSD",function(){
		var RowID = $(this).attr("data-index");											  
		CalculateWorkSheetActionSD();
	});
	$("body").on("click","#modal_save",function(){
		var RowID = $("#modal_index").val();
		var ModalItemDesc = $("#txt_item_desc_alt").val();
		var ModalQtyDesc  = $("#txt_qty_dest_alt").val();
		var ModalCalcDesc = $("#txt_calc_desc").val();
		var ModalTitle 	  = $("#txt_title").val();
		
		$("#txt_curr_item_desc_alt"+RowID).val(ModalItemDesc);
		$("#txt_curr_qty_desc_alt"+RowID).val(ModalQtyDesc);
		$("#txt_curr_calc_desc"+RowID).val(ModalCalcDesc);
		$("#txt_curr_title"+RowID).val(ModalTitle);
		var ActionArr = []; var FactorArr = []; var RowIdArr = [];
		$('.ModalAction').each(function() { 
			var RowIdAct = $(this).attr('data-index');
			var Action 	 = $(this).val(); ;
			var Factor 	 = $("#txt_action_factor_modal"+RowIdAct).val();
			if((Action != '')&&(Factor != '')){
				ActionArr.push(Action);
				FactorArr.push(Factor);
				RowIdArr.push(RowIdAct);
			}
		});
		var ActionArrStr = ActionArr.join(",");
		var FactorArrStr = FactorArr.join(",");
		var RowIdArrStr = RowIdArr.join(",");
		$("#txt_curr_action"+RowID).val(ActionArrStr);
		$("#txt_curr_factor"+RowID).val(FactorArrStr);
		$("#txt_curr_calc_index"+RowID).val(RowIdArrStr);
		
		var NewRate = $("#txt_new_final_rate").val();
		var Qty 	= $("#txt_quantity"+RowID).val(); 
		//if((NewRate != '')&&(NewRate != '')){
		if(NewRate != ''){
			if(Qty == "-"){
				var Amount  = Number(NewRate);
			}else{
				var Amount 	= Number(Qty)*Number(NewRate);
			}
			Amount = Amount.toFixed(2);
		 	$("#txt_amount"+RowID).val(Amount);
		 	FindTotalAmountNewMat();
		}
		$("#txt_rate"+RowID).val(NewRate);
		$('.R-ROW').remove();
		$("#ItemCodeDescModal").html('');
	});
	
	$("body").on("click","#modal_save_sd",function(){
		var RowID = $("#modal_index_sd").val();
		var ModalItemDesc = $("#txt_item_desc_alt_sd").val();
		var ModalQtyDesc  = $("#txt_qty_dest_alt_sd").val();
		var ModalCalcDesc = $("#txt_calc_desc_sd").val();
		var ModalTitle 	  = $("#txt_title_sd").val();
		
		$("#txt_curr_item_desc_alt_sd"+RowID).val(ModalItemDesc);
		$("#txt_curr_qty_desc_alt_sd"+RowID).val(ModalQtyDesc);
		$("#txt_curr_calc_desc_sd"+RowID).val(ModalCalcDesc);
		$("#txt_curr_title_sd"+RowID).val(ModalTitle);
		var ActionArr = []; var FactorArr = []; var RowIdArr = [];
		$('.ModalActionSD').each(function() { 
			var RowIdAct = $(this).attr('data-index');
			var Action 	 = $(this).val(); ;
			var Factor 	 = $("#txt_action_factor_modal_sd"+RowIdAct).val();
			if((Action != '')&&(Factor != '')){
				ActionArr.push(Action);
				FactorArr.push(Factor);
				RowIdArr.push(RowIdAct);
			}
		});
		var ActionArrStr = ActionArr.join(",");
		var FactorArrStr = FactorArr.join(",");
		var RowIdArrStr = RowIdArr.join(",");
		$("#txt_curr_action_sd"+RowID).val(ActionArrStr);
		$("#txt_curr_factor_sd"+RowID).val(FactorArrStr);
		$("#txt_curr_calc_index_sd"+RowID).val(RowIdArrStr);
		
		var NewIgcRate = $("#txt_new_igc_final_rate_sd").val();
		var NewTwRate = $("#txt_new_ts_final_rate_sd").val();
		
		$("#txt_ts_rate_sd"+RowID).val(NewTwRate);
		$("#txt_igc_rate_sd"+RowID).val(NewIgcRate);
		$('.R-ROW-SD').remove();
		$("#ItemCodeDescModalSD").html('');
		$("#txt_title_sd").val('');
	});
	
	$("body").on("click","#disposal_qty",function(){
		if($(this).is(':checked')){
			$(".disposal").removeClass("hide");
		}else{
			$(".disposal").addClass("hide");
		}
	});
	$("body").on("change","#txt_disp_qty_prec",function(){
		CalulateAddDeduct();
	});
	/// FOR MERGE WITH SUB DATA - ENDS HERE //////
	
	$("body").on("click",".cmd-box-unit",function(){
		
		var F 			= $("#txt_f").val();
		var ToUnit 		= $("#hid_to_unit").val();
		var ForOneUnit 	= $("#txt_cost_one").val();
		
		var FromVal 	= $("#txt_modal_from_val_UC").val();
		var FromUnit 	= $("#cmb_modal_from_unit_UC").val();
		var FinalRate 	= $("#txt_new_final_rate_UC").val();
		var CostFor 	= $("#txt_cost").val();
		var CostUnit 	= $("#cmb_unit").val();
		
		$("#txt_modal_from_val_UC").val(CostFor);
		$("#cmb_modal_from_unit_UC").val(CostUnit);
		$("#txt_modal_to_val_UC").val(1);
		if(ToUnit != ""){
			$("#txt_new_final_rate_UC").val(ForOneUnit)
			$("#cmb_modal_to_unit_UC").val(ToUnit);
			UnitConversionCalc();
		}
		
		$('#myModalUC').modal();
	});
	$("body").on("change","#cmb_modal_to_unit_UC",function(){
		var F 			= $("#txt_f").val();
		var FromVal 	= $("#txt_modal_from_val_UC").val();
		var FromUnit 	= $("#cmb_modal_from_unit_UC").val();
		var ToUnit 		= $("#cmb_modal_to_unit_UC").val();
		var FinalRate 	= $("#txt_new_final_rate_UC").val();
		var CostFor 	= $("#txt_cost").val();
		var CostUnit 	= $("#cmb_unit").val();
		
		var x = 0;
		if($('#txt_'+FromUnit+ToUnit+'_factor').length){
			x++;
		}
		if($('#txt_'+FromUnit+ToUnit+'_action').length){
			x++;
		}
		if(x == 2){
			$("#txt_modal_from_val_UC").val(CostFor);
			$("#cmb_modal_from_unit_UC").val(CostUnit);
			$("#txt_modal_to_val_UC").val(1);
			var UnitFactor = $('#txt_'+FromUnit+ToUnit+'_factor').val();
			var UnitAction = $('#txt_'+FromUnit+ToUnit+'_action').val();
			if(UnitAction == "A"){
				var AfterConvVal = Number(F)+ Number(UnitFactor) / Number(CostFor);
			}else if(UnitAction == "S"){
				var AfterConvVal = Number(F)- Number(UnitFactor) / Number(CostFor);
			}else if(UnitAction == "M"){
				var AfterConvVal = Number(F)* Number(UnitFactor) / Number(CostFor);
			}else if(UnitAction == "D"){
				var AfterConvVal = Number(F)/ Number(UnitFactor) / Number(CostFor);
			}else if(UnitAction == "P"){
				var AfterConvVal = Number(F)* (Number(UnitFactor)/100) / Number(CostFor);
			}
			$("#txt_new_final_rate_UC").val(AfterConvVal.toFixed(2));
		}else{
			$("#cmb_modal_to_unit_UC").val('');
			$("#txt_new_final_rate_UC").val('');
			BootstrapDialog.alert("Error: Unit Conversion formula not assigned for this type of conversion");
		}
		
	});
	
	$("body").on("click","#modal_save_UC",function(){ 
		UnitConversionCalc();
		$("#txt_modal_from_val_UC").val('');
		$("#cmb_modal_from_unit_UC").val('');
		$("#cmb_modal_to_unit_UC").val('');
		$("#txt_new_final_rate_UC").val('');
	});
	
	
	$("#exportToExcel").click(function(e){ 
		var table = $('body').find('.table2excel');
		if(table.length){ 
			$(table).table2excel({
				exclude: ".noExl",
				name: "Excel Document Name",
				filename: "Datasheet-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
				fileext: ".xls",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true
				//preserveColors: preserveColors
			});
		}
	});
	
});
	
	
	
	
	
	
function UnitConversionCalc(){
	var FromVal 	= $("#txt_modal_from_val_UC").val();
	var FromUnit  	= $("#cmb_modal_from_unit_UC").val();
	var ToVal 		= $("#txt_modal_to_val_UC").val();
	var ToUnit 	  	= $("#cmb_modal_to_unit_UC").val();
	var FinalRate 	= $("#txt_new_final_rate_UC").val();
	var CostFor 	= $("#txt_cost").val();
	var CostUnit 	= $("#cmb_unit").val();
	var GPerc 	 	= $("#txt_g_per").val(); 
	var G 			= $("#txt_g").val();
	var W 			= $("#txt_total_amount").val();
		
	var ForOneUnit 	= Number($("#txt_new_final_rate_UC").val()); 
		if(ForOneUnit != 0){ ForOneUnit = ForOneUnit.toFixed(2); }
	var TSRate 		= ForOneUnit; 
	var G1 	= Number(W) * Number(GPerc) / 100;
	
	var x = 0;
	if($('#txt_'+FromUnit+ToUnit+'_factor').length){
		x++;
	}
	if($('#txt_'+FromUnit+ToUnit+'_action').length){
		x++;
	}
	if(x == 2){
		var UnitFactor = $('#txt_'+FromUnit+ToUnit+'_factor').val();
		var UnitAction = $('#txt_'+FromUnit+ToUnit+'_action').val();
		if(UnitAction == "A"){
			var G = Number(G1)+ Number(UnitFactor) / Number(CostFor);
		}else if(UnitAction == "S"){
			var G = Number(G1)- Number(UnitFactor) / Number(CostFor);
		}else if(UnitAction == "M"){
			var G = Number(G1)* Number(UnitFactor) / Number(CostFor);
		}else if(UnitAction == "D"){
			var G = Number(G1)/ Number(UnitFactor) / Number(CostFor);
		}else if(UnitAction == "P"){
			var G = Number(G1)* (Number(UnitFactor)/100) / Number(CostFor);
		}
	}
	
	//var G   = Number(G1) * 10 / Number(CostFor);
	
	
		if(G != 0){G = G.toFixed(2); }
	var IGRate1 	= Number(ForOneUnit) + Number(G); 
		if(IGRate1 != 0){IGRate1 	= IGRate1.toFixed(2); }
	var IGRate2 	= Number(IGRate1) * Number(CostFor);
		if(IGRate2 != 0){IGRate2 	= IGRate2.toFixed(2); }
			
	$("#txt_cost_one").val(ForOneUnit); 
	$("#txt_township_amt").val(TSRate);
	$("#txt_g").val(G);
	$("#txt_igcar1_amt").val(IGRate1);
	$("#hid_to_unit").val(ToUnit);
}
function ClearNew(){
	$(".ClrInput").val('');
	/*$("#txt_code0").val('');
	$("#txt_item_id0").val('');
	$("#txt_desc0").val('');
	$("#txt_unit0").val('');
	$("#txt_rate0").val('');
	$("#hid_rate0").val('');
	$("#txt_quantity0").val('');
	$("#txt_amount0").val('');
	$("#txt_curr_calc_desc0").val('');
	$("#txt_curr_item_desc_alt0").val('');
	$("#txt_curr_qty_desc_alt0").val('');
	$("#txt_curr_title0").val('');
	$("#txt_title").val('');
	$("#txt_curr_action0").val('');
	$("#txt_curr_factor0").val('');
	$("#txt_curr_calc_index0").val('');
	$("#hid_calc_type0").val('');
	$("#hid_amt_type0").val('');
	$("#hid_ref_id0").val('');*/
}
function ClearSD(){
	$("#txt_item_id_sd0").val('');
	$("#txt_refid_sd0").val('');
	$("#txt_desc_sd0").val('');
	$("#txt_ts_rate_sd0").val('');
	$("#txt_igc_rate_sd0").val('');
	$("#hid_ts_rate_sd0").val('');
	$("#hid_igc_rate_sd0").val('');
	$("#txt_curr_calc_desc_sd0").val('');
	$("#txt_curr_qty_desc_alt_sd0").val('');
	$("#txt_curr_calc_desc_sd0").val('');
	$("#txt_curr_item_desc_alt_sd0").val('');
	$("#txt_curr_title_sd0").val('');
	$("#txt_title_sd").val('');
	$("#txt_curr_action_sd0").val('');
	$("#txt_curr_factor_sd0").val('');
	$("#txt_curr_calc_index_sd0").val('');
	$("#hid_ref_id_sd0").val('');
}
function FindTotalAmountNewMat(){ 
	var TotalAmt = 0; 
	$(".NewAmtMat").each(function(){ 
		var Amt = $(this).val();
		TotalAmt = Number(TotalAmt)+Number(Amt);
	});
	if(TotalAmt > 0){ 
		TotalAmt = TotalAmt.toFixed(2); 
	}
	$("#txt_mat_total_amount").val(TotalAmt);
}
function FindTotalAmountNewLab(){ 
	var TotalAmt = 0;
	$(".NewAmtLab").each(function(){ 
		var Amt = $(this).val();
		TotalAmt = Number(TotalAmt)+Number(Amt);
	});
	if(TotalAmt > 0){ 
		TotalAmt = TotalAmt.toFixed(2); 
	}
	$("#txt_lab_total_amount").val(TotalAmt);
}
function FindTotalAmount(){
	var MatTotAmt = $("#txt_mat_total_amount").val();
	var LabTotAmt = $("#txt_lab_total_amount").val();
	if(MatTotAmt == ""){
		MatTotAmt = 0;
	}
	if(LabTotAmt == ""){
		LabTotAmt = 0;
	}
	var TotalAmt = Number(MatTotAmt) + Number(LabTotAmt);
	if(TotalAmt != 0){
		TotalAmt = TotalAmt.toFixed(2);
	}
	$("#txt_total_amount").val(TotalAmt);
}

function FindTotalAmountSD(){ 
	var TotalTsAmt = 0; var TotalIgcAmt = 0;
	$(".tsAmt").each(function(){ 
		var TsAmt = $(this).val();
		TotalTsAmt = Number(TotalTsAmt)+Number(TsAmt);
	});
	$(".igcAmt").each(function(){
		var IgcAmt = $(this).val();
		TotalIgcAmt = Number(TotalIgcAmt)+Number(IgcAmt);
	});
	//alert(TotalTsAmt); alert(TotalIgcAmt);
	$('#txt_total_amount_is').val(TotalTsAmt.toFixed(2));
	$('#txt_total_amount_igc').val(TotalIgcAmt.toFixed(2));
}
function FindAverageAmountSD(){ 
	var TotalTsAmt = 0; var TotalIgcAmt = 0; var TsRow =0; var IgcRow =0;
	$(".tsAmt").each(function(){ 
		var TsAmt = $(this).val();
		if(TsAmt != ''){
			TsRow++;
			TotalTsAmt = Number(TotalTsAmt)+Number(TsAmt);
		}
	});
	$(".igcAmt").each(function(){
		var IgcAmt = $(this).val();
		if(IgcAmt != ''){
			IgcRow++;
			TotalIgcAmt = Number(TotalIgcAmt)+Number(IgcAmt);
		}
	});
	//alert(TsRow); alert(IgcRow);
	var AvgTsAmt  = 0; var AvgIgcAmt  = 0; 
	if((TsRow == 0)&&(TotalTsAmt == 0)){
		AvgTsAmt = 0;
	}else{
		AvgTsAmt  = Number(TotalTsAmt)/Number(TsRow);
	}
	if((IgcRow == 0)&&(TotalIgcAmt == 0)){
		AvgIgcAmt = 0;
	}else{
		AvgIgcAmt  = Number(TotalIgcAmt)/Number(IgcRow);
	}
	
	$('#txt_total_amount_is_avg').val(AvgTsAmt.toFixed(2));
	$('#txt_total_amount_igc_avg').val(AvgIgcAmt.toFixed(2));
}
///// VALIDATION WHEN SAVE BUTTON CLICKED
var KillEvent = 0;
$("#top").submit(function(event){
	if(KillEvent == 0){
		var Level = 0; var CurrLevel = ""; var GrError = 0; var GrTemp = 0; var ChGr = 0;
		var Cost 	= $("#txt_cost").val();
		var Unit 	= $("#cmb_unit").val(); 
		var Dsheet 	= $("#hid_check_ds").val();
		var Type = $('input[type=radio][name=rad_type]:checked').val(); 
		$('select[name="cmb_group[]"]').each(function(){ 
			var groupId = $(this).val(); 
			if(groupId == "Select"){
				GrTemp = 1;
				CurrLevel = Level;
			}
			Level++;
		});
		if((Level == 3)&&(GrTemp == 1)){
			GrError = 1;	
		}else if((Level > 3)&&(GrTemp == 1)){
			ChGr = 1;
		}
		if(Type == "M"){
			var Row = $('#tab_sd_material tr').length;
			//var Row1 = $('#MT tr').length; alert(Row1);
			//var Row2 = $('#LT tr').length; alert(Row2);
			
		}else{
			//var Row = $('.DataSheetTable tr').length;
			var Row1 = $('#MT tr').length; //alert(Row1);
			var Row2 = $('#LT tr').length; //alert(Row2);
		}
		if(Dsheet == 1){
			var DsType = $("#hid_check_ds_name").val();
			BootstrapDialog.alert("Error : "+DsType+" - Datasheet already Created.");
			event.preventDefault();
			event.returnValue = false;
		}else if(Cost == ""){
			BootstrapDialog.alert("Please Enter Cost. ");
			event.preventDefault();
			event.returnValue = false;
		}else if(Unit == ""){
			BootstrapDialog.alert("Please Select Unit. ");
			event.preventDefault();
			event.returnValue = false;
		}else if(GrError == 1){
			BootstrapDialog.alert("Please Select Group - "+CurrLevel);
			event.preventDefault();
			event.returnValue = false;
		}else if((Row1 <= 7)&&(Row2 <= 4)){
			BootstrapDialog.alert("Please Add Atleast One Row");
			event.preventDefault();
			event.returnValue = false;
		}else{
			event.preventDefault();
			if(ChGr == 1){
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Selected group contains sub group. Are you sure want to create datasheet for selected main group ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
							// result will be true if button was click, while it will be false if users close the dialog directly.
						if(result){
							BootstrapDialog.confirm({
								title: 'Confirmation Message',
								message: 'Are you sure want to save and create datasheet ?',
								closable: false, // <-- Default value is false
								draggable: false, // <-- Default value is false
								btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
								btnOKLabel: 'Ok', // <-- Default value is 'OK',
								callback: function(result) {
										// result will be true if button was click, while it will be false if users close the dialog directly.
									if(result){
										KillEvent = 1;
										$("#btn_save").trigger( "click" );
									}else {
											//alert('Nope.');
										KillEvent = 0;
									}
								}
							});
						}else {
								//alert('Nope.');
							KillEvent = 0;
						}
					}
				});
			}else{
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to save and create datasheet ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
							// result will be true if button was click, while it will be false if users close the dialog directly.
						if(result){
							KillEvent = 1;
							$("#btn_save").trigger( "click" );
						}else {
								//alert('Nope.');
							KillEvent = 0;
						}
					}
				});
			}
		}
	}
});
$("#cmb_unit").change(function(event){
	ChangeUnit();
	var CostUnit = $(this).val();
	$("#cmb_final_unit").val(CostUnit);
});
///// TO STOP DOUBLE TIME INSERT IF REFRESH OR RELOAD
/*if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}*/
function ChangeUnit(){ 
	var CostUnit = $("#cmb_unit").val();
	$("#one_unit").html('');
	if(CostUnit !== ""){
		$("#one_unit").html(CostUnit);	
	}
}
function CalulateAddDeduct(){ 
	var TotalAmt 	= $("#txt_total_amount").val(); 
	var TpPerc 	 	= $("#txt_tp_perc").val(); 
	var Safetyperc 	= $("#txt_safety_perc").val(); 
	var ContPrfperc = $("#txt_cont_prf_perc").val(); 
	var SecResPerc 	= $("#txt_sec_res_perc").val(); 
	var Qty 	 	= $("#txt_cost").val(); 
	
	if((Qty != '')&&(TotalAmt != "")){
		var W 	= TotalAmt;
		var TpAmt 	= Number(W) * Number(TpPerc) / 100;
			if(TpAmt != 0){ TpAmt = TpAmt.toFixed(2); }
			
		var SafetyAmt 	= Number(W) * Number(Safetyperc) / 100;
			if(SafetyAmt != 0){ SafetyAmt = SafetyAmt.toFixed(2); }
			
		var ContPrfAmt 	= Number(W) * Number(ContPrfperc) / 100;
			if(ContPrfAmt != 0){ ContPrfAmt = ContPrfAmt.toFixed(2); }
			
		var TotalCost 	= Number(W) + Number(TpAmt) + Number(SafetyAmt) + Number(ContPrfAmt);
			if(TotalCost != 0){ TotalCost = TotalCost.toFixed(2); }
			
		var ForOneUnit 	= Number(TotalCost) / Number(Qty);
			if(ForOneUnit != 0){ ForOneUnit = ForOneUnit.toFixed(2); }
		var TSRate 		= ForOneUnit;
		
		var SecResAmt 	= Number(W) * Number(SecResPerc) / ( 100 * Number(Qty) );
			if(SecResAmt != 0){ SecResAmt = SecResAmt.toFixed(2); }
			
			
		var IGCARRate 	= Number(TSRate) + Number(SecResAmt);
			if(IGCARRate != 0){ IGCARRate = IGCARRate.toFixed(2); }
		var IGRate1 = IGCARRate;
		
		/*var FromUnit  	= $("#cmb_modal_from_unit_UC").val();
		var ToUnit 	  	= $("#cmb_modal_to_unit_UC").val();
		var tx = 0;
		if($('#txt_'+FromUnit+ToUnit+'_factor').length){
			tx++;
		}
		if($('#txt_'+FromUnit+ToUnit+'_action').length){
			tx++;
		}
		if(tx == 2){
			var G1 = G; var CostFor = Qty;
			var UnitFactor = $('#txt_'+FromUnit+ToUnit+'_factor').val();
			var UnitAction = $('#txt_'+FromUnit+ToUnit+'_action').val();
			if(UnitAction == "A"){
				var G = Number(G1)+ Number(UnitFactor) / Number(CostFor);
			}else if(UnitAction == "S"){
				var G = Number(G1)- Number(UnitFactor) / Number(CostFor);
			}else if(UnitAction == "M"){
				var G = Number(G1)* Number(UnitFactor) / Number(CostFor);
			}else if(UnitAction == "D"){
				var G = Number(G1)/ Number(UnitFactor) / Number(CostFor);
			}else if(UnitAction == "P"){
				var G = Number(G1)* (Number(UnitFactor)/100) / Number(CostFor);
			}
		}
		if(G != 0){G = G.toFixed(2); }
		var IGRate1 	= Number(ForOneUnit) + Number(G);
			if(IGRate1 != 0){IGRate1 	= IGRate1.toFixed(2); }
		var IGRate2 	= Number(IGRate1) * Number(Qty);
			if(IGRate2 != 0){IGRate2 	= IGRate2.toFixed(2); }*/
			
			
		$("#txt_w").val(W);
		$("#txt_tp_amt").val(TpAmt);
		$("#txt_safety_amt").val(SafetyAmt);
		$("#txt_cont_prf_amt").val(ContPrfAmt);
		$("#txt_total_cost").val(TotalCost);
		$("#txt_one_unit_cost").val(ForOneUnit);
		$("#txt_tw_amt").val(ForOneUnit);
		$("#txt_sec_res_amt").val(SecResAmt);
		$("#txt_igcar_amt").val(IGCARRate);
		
		//$("#txt_igcar2_amt").val(IGRate2);
		//$(".add-ded").css("color", "red");
		
		/*$("#txt_ts_rate_view1").val('');
		$("#txt_igc_rate_view1").val('');
		$("#txt_ts_rate_view2").val('');
		$("#txt_igc_rate_view2").val('');
		
		$("#txt_ts_rate_view1").val(TSRate);
		$("#txt_igc_rate_view1").val(IGRate1);
		if($('#disposal_qty').is(':checked')){
			var DisposalPerc = $("#txt_disp_qty_prec").val();
			if(DisposalPerc != ''){
				var DisposalTsRate 	= Number(TSRate) * Number(DisposalPerc) / 100;
				var DisTsRate 		= Number(DisposalTsRate).toFixed(2);
				var DisposalIgcRate = Number(IGRate1) * Number(DisposalPerc) / 100;
				var DisIgcRate 		= Number(DisposalIgcRate).toFixed(2);
				$("#txt_ts_rate_view2").val(DisTsRate);
				$("#txt_igc_rate_view2").val(DisIgcRate);
			}
		}*/
	}
	ChangeUnit();
}

$("#txt_cost").change(function(event){  
	var Type = $('input[type=radio][name=rad_type]:checked').val();
	if(Type == 'N'){
		CalulateAddDeduct();
	}
});

function CalculateWorkSheetAction(){
	var Index 	= $("#modal_index").val();
	var Rate 	= $("#hid_rate"+Index).val(); //$("#txt_rate"+Index).val(); 
	var Amount 	= Number(Rate);
	if(Rate != ''){
		Amount = Amount.toFixed(2);
		$('.ModalAction').each(function() { 
			var RowId 	= $(this).attr('data-index');
			var CurrAmt = Amount;
			$("#txt_w_amt_modal"+RowId).val(Amount);
			var Action 	= $(this).val(); ;
			var Factor 	= $("#txt_action_factor_modal"+RowId).val();
			if((Action != '')&&(Factor != '')){
				if(Action == "A"){
					Amount = Number(CurrAmt)+Number(Factor);
				}else if(Action == "S"){
					Amount = Number(CurrAmt)-Number(Factor);
				}else if(Action == "M"){
					Amount = Number(CurrAmt)*Number(Factor);
				}else if(Action == "D"){
					Amount = Number(CurrAmt)/Number(Factor);
				}else if(Action == "P"){
					Amount = Number(CurrAmt)*Number(Factor)/100;
				}
			}
			Amount = Amount.toFixed(2);
			$("#txt_amount_modal"+RowId).val(Amount);
		});
	}
	$("#txt_new_final_rate").val(Amount);
}


function CalculateWorkSheetActionSD(){ 
	var Index 		= $("#modal_index_sd").val();
	var IgcRate 	= $("#hid_igc_rate_sd"+Index).val(); //$("#txt_rate"+Index).val(); 
	var TwRate 		= $("#hid_ts_rate_sd"+Index).val(); 
	var IgcAmount 	= Number(IgcRate);
	var TwAmount 	= Number(TwRate);
	if((IgcRate != '')&&(TwRate != '')){ 
		IgcAmount 	= IgcAmount.toFixed(2);
		TwAmount 	= TwAmount.toFixed(2); 
		$('.ModalActionSD').each(function() { 
			var RowId 		= $(this).attr('data-index');
			var CurrIgcAmt 	= IgcAmount;
			var CurrTwAmt 	= TwAmount;
			$("#txt_w_igc_amt_modal"+RowId).val(IgcAmount);
			$("#txt_w_ts_amt_modal"+RowId).val(TwAmount);
			var Action 	= $(this).val(); ;
			var Factor 	= $("#txt_action_factor_modal_sd"+RowId).val();
			if((Action != '')&&(Factor != '')){
				if(Action == "A"){
					IgcAmount 	= Number(CurrIgcAmt)+Number(Factor);
					TwAmount 	= Number(CurrTwAmt)+Number(Factor);
				}else if(Action == "S"){
					IgcAmount 	= Number(CurrIgcAmt)-Number(Factor);
					TwAmount 	= Number(CurrTwAmt)-Number(Factor);
				}else if(Action == "M"){
					IgcAmount 	= Number(CurrIgcAmt)*Number(Factor);
					TwAmount 	= Number(CurrTwAmt)*Number(Factor);
				}else if(Action == "D"){
					IgcAmount 	= Number(CurrIgcAmt)/Number(Factor);
					TwAmount 	= Number(CurrTwAmt)/Number(Factor);
				}else if(Action == "P"){
					IgcAmount 	= Number(CurrIgcAmt)*Number(Factor)/100;
					TwAmount 	= Number(CurrTwAmt)*Number(Factor)/100;
				}
			}
			IgcAmount 	= IgcAmount.toFixed(2);
			TwAmount 	= TwAmount.toFixed(2);
			$("#txt_igc_amount_modal_sd"+RowId).val(IgcAmount);
			$("#txt_ts_amount_modal_sd"+RowId).val(TwAmount);
		});
	}
	$("#txt_new_igc_final_rate_sd").val(IgcAmount);
	$("#txt_new_ts_final_rate_sd").val(TwAmount);
}
//history.replaceState( {} , 'CIVIL-SOR', '/CIVIL-SOR/' );
