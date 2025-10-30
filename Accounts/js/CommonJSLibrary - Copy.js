$(document).ready(function(){
	$('.group').chosen();
	$('.dropdown-submenu a.test').on("click", function(e){
    	$(this).next('ul').toggle();
    	e.stopPropagation();
    	e.preventDefault();
  	});
	
	/// FOR GROUP CHANGE - FIND NEXT LEVEL GORUP BASED ON CHAGES
	$('body').on("change",".group", function(e){ 
		var groupid  = $(this).val();
		var id 		 = $("option:selected", this).attr('data-id');
		var parid 	 = $("option:selected", this).attr('data-parid');
		var maxgroup = $("option:selected", this).attr('data-group');
		var curgroup = maxgroup;
			maxgroup = Number(maxgroup)+1;
		$(".G").each(function() {
			var classname = Number($(this).attr('class').split(' ')[0]);
			if(classname >= maxgroup){
				$(this).closest("tr").remove();
			}
		});
		if(groupid == 'Select'){
			return false;
			exit();
		}
		$.ajax({ 
			type: 'POST', 
			url: 'find_group_common.php', 
			data: { groupid: groupid, id: id, parid: parid }, 
			dataType: 'json',
			success: function (data) {   
				if(data != null){
					var Result1 = data['Result1'];
					var Result2 = data['Result2'];
					//alert(Result1);alert(Result2);
					if(Result1 != null){
						var title = "Group "+maxgroup;
						var Content  = '<tr class="'+maxgroup+' G"><td width="50">&nbsp;</td><td class="labelbold" width="138">'+title+'</td><td colspan="2">';
							Content += '<select name="cmb_group[]" id="cmb_group'+maxgroup+'" data-group="'+maxgroup+'" class="labelfield group" style="width:648px;height:21px;">';
							Content += '<option value="Select">----------------------- Select -----------------------</option>';
							Content += '</select></td></tr><tr class="'+maxgroup+' G"><td colspan="5">&nbsp;</td></tr>';
						$("#group tbody").append(Content);
						$.each(Result1, function(index, element) {
							$("#cmb_group"+maxgroup).append('<option data-id="'+element.id+'" data-parid="'+element.par_id+'" data-group="'+maxgroup+'" value="'+element.group_id+'">'+element.type+" - "+element.group_desc+'</option>');
						});
					}else{
						var title = "Group "+curgroup+" Description";
						$.each(Result2, function(index, element) {
							var Content  = '<tr class="'+maxgroup+' G"><td width="50">&nbsp;</td><td class="labelbold" width="138">'+title+'</td><td colspan="2">';
								Content += '<textarea name="txt_group_desc" id="txt_group_desc" class="labelfield" rows="5" cols="87" readonly="">'+element.group_desc+'</textarea>';
								Content += '</td></tr><tr class="'+maxgroup+' G"><td colspan="5">&nbsp;</td></tr>';
							$("#group tbody").append(Content);
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
		}else if(type == 'M'){
			$(".new").addClass("hide");
			$(".merge").removeClass("hide");
		}
	});
	
	
	
	/// FOR NEW MAIN DATA - STARTS HERE //////
	var index = 1;
	$("#btn_add").click(function(event){ 
		var itemCode 	= $("#txt_code0").val();
		var itemDesc 	= $("#txt_desc0").val();
		var itemId 	 	= $("#txt_item_id0").val();
		var itemRate 	= $("#txt_rate0").val();
		var itemUnit 	= $("#txt_unit0").val();
		var itemQty 	= $("#txt_quantity0").val();
		var itemAmt 	= $("#txt_amount0").val();
		var calcDesc 	= $("#txt_curr_calc_desc0").val();
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
			$('#tab_a1_material').find('tr:last').prev().after('<tr><td class="labelcenter" valign="middle"><input type="text" class="labelfield icode" list="ItemCodeListNew'+index+'" size="10" style="width:100%;" name="txt_code[]" id="txt_code'+index+'" data-index="'+index+'" value="'+itemCode+'" /><datalist id="ItemCodeListNew'+index+'" style="color:#C80B5B; font-size:16px"></datalist></td><td class="labelcenter" valign="middle" nowrap="nowrap"><input type="text" class="labelfield idesc" list="ItemDescListNew'+index+'" data-index="'+index+'" size="25" style="width:90%;" name="txt_desc[]" id="txt_desc'+index+'" value="'+itemDesc+'" /><datalist id="ItemDescListNew'+index+'" style="color:#C80B5B; font-size:16px"></datalist>&nbsp;<i class="fa fa fa-paperclip cmd-box cmd-box-m" id="CmdBox'+index+'" data-index="'+index+'"></i><input type="hidden" class="labelfield" size="9" style="width:100%" name="txt_item_id[]" id="txt_item_id'+index+'" value="'+itemId+'" readonly="" /><textarea name="txt_curr_calc_desc[]" id="txt_curr_calc_desc'+index+'" style="display:none;">'+calcDesc+'</textarea></td><td class="labelcenter" valign="middle"><input type="text" class="labelfieldright disable" size="5" style="width:100%" name="txt_rate[]" id="txt_rate'+index+'" value="'+itemRate+'" readonly="" /></td><td class="labelcenter" valign="middle"><input type="text" class="labelfield disable" size="5" style="width:100%" name="txt_unit[]" id="txt_unit'+index+'" value="'+itemUnit+'" readonly="" /></td><td class="labelcenter" valign="middle"><input type="text" class="labelfield Qty" size="5" style="width:100%" data-index="'+index+'" name="txt_quantity[]" id="txt_quantity'+index+'" value="'+itemQty+'" /></td><td class="labelcenter" valign="middle"><input type="text" class="labelfieldright disable NewAmt" size="8" style="width:100%" name="txt_amount[]" id="txt_amount'+index+'" value="'+itemAmt+'" readonly="" /></td><td class="labelcenter" valign="middle"><input type="button" class="delete" name="btn_delete" id="btn_delete'+index+'" value=" Delete " style="width:45%"/></td></tr>'); //add input box
			$('#ItemCodeListNew0').find('option').clone().appendTo('#ItemCodeListNew'+index);
			$('#ItemDescListNew0').find('option').clone().appendTo('#ItemDescListNew'+index);
			index++;
			ClearNew();
			FindTotalAmountNew();
			CalulateAddDeduct();
			
		}
	});
	
	$("#tab_a1_material").on("change", ".icode", function() {
		var itemCode 	= $(this).val(); 
		var RowID 		= $(this).attr("data-index");
		var itemId 		= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('item_id');//alert(parid);
		var itemDesc 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('desc');//alert(id);
		var itemUnit 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('unit');//alert(refid);
		var itemRate 	= $('#ItemCodeListNew'+RowID+' [value="' + itemCode + '"]').data('price');//alert(groupid);
		$("#txt_item_id"+RowID).val(itemId);
		$("#txt_desc"+RowID).val(itemDesc);
		$("#txt_unit"+RowID).val(itemUnit);	
		$("#txt_rate"+RowID).val(itemRate);
		$("#txt_quantity"+RowID).val('');
		$("#txt_amount"+RowID).val('');
		$("#txt_curr_calc_desc"+RowID).val('');
	});
	$("#tab_a1_material").on("change", ".idesc", function() {
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
		$("#txt_quantity"+RowID).val('');
		$("#txt_amount"+RowID).val('');
		$("#txt_curr_calc_desc"+RowID).val('');
	});
	$("#tab_a1_material").on("change", ".Qty", function() {
		var Qty 	= $(this).val(); 
		var RowID 	= $(this).attr("data-index"); 
		var Rate 	= $('#txt_rate'+RowID).val();
		var Amount 	= Number(Qty)*Number(Rate);
			Amount = Amount.toFixed(2);
		 $("#txt_amount"+RowID).val(Amount);
		 FindTotalAmountNew();
	});
	$("#tab_a1_material").on("click", ".delete", function() {
    	$(this).closest("tr").remove();
		FindTotalAmountNew();
    });
	$("#tab_a1_material").on("click", "#btn_clear", function() {
		ClearNew();
	});
	/// FOR NEW MAIN DATA - ENDS HERE //////
	
	
	/// FOR MERGE WITH SUB DATA - STARTS HERE //////
	
	//$('#txt_item_id_sd0').change(function(){
	$("#tab_sd_material").on("change", ".icodesd", function() {
		var itemCode = $(this).val();
		$("#txt_refid_sd0").val('');
		$("#txt_curr_calc_desc_sd0").val('');
		var RowID 	= $(this).attr("data-index"); //alert()
		var parid 	= $('#ItemCodeListSD'+RowID+' [value="' + itemCode + '"]').data('par_id');//alert(parid);
		var id 		= $('#ItemCodeListSD'+RowID+' [value="' + itemCode + '"]').data('id');//alert(id);
		var refid 	= $('#ItemCodeListSD'+RowID+' [value="' + itemCode + '"]').data('ref_id');//alert(refid);
		var groupid = $('#ItemCodeListSD'+RowID+' [value="' + itemCode + '"]').data('group_id');//alert(groupid);
		$.ajax({ 
			type: 'POST', 
			url: 'find_rate_calculation.php', 
			data: { groupid: groupid, id: id, parid: parid, refid: refid }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){
					var TSRate 		= data['TSRate'];
					var IGCARRate 	= data['IGCARRate1'];
					var MasterDesc 	= data['MasterDesc'];
					$("#txt_ts_rate_sd"+RowID).val(TSRate);
					$("#txt_igc_rate_sd"+RowID).val(IGCARRate);
					$("#txt_desc_sd"+RowID).val(MasterDesc);
					$("#txt_refid_sd"+RowID).val(refid);
					if(RowID != '0'){
						FindTotalAmountSD();
					}
				}
			}
		});
	});
	
	var index2 = 1;
	$("#btn_add_sd").click(function(event){ 
		var itemId 		= $("#txt_item_id_sd0").val();
		var itemRefId 	= $("#txt_refid_sd0").val();
		var itemDesc 	 = $("#txt_desc_sd0").val();
		var TsRate 		= $("#txt_ts_rate_sd0").val();
		var IgcRate 	= $("#txt_igc_rate_sd0").val();
		var calcDesc 	= $("#txt_curr_calc_desc_sd0").val();
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
			$('#tab_sd_material').find('tr:last').prev().after('<tr><td><input type="text" class="labelfield icodesd" size="25" style="width:100%;" name="txt_item_id_sd[]" id="txt_item_id_sd'+index2+'" value="'+itemId+'" data-index="'+index2+'" list="ItemCodeListSD'+index2+'"/><datalist id="ItemCodeListSD'+index2+'" style="color:#C80B5B; font-size:16px"></datalist><input type="hidden" class="labelfield" size="9" style="width:100%" name="txt_refid_sd[]" id="txt_refid_sd'+index2+'" value="'+itemRefId+'" readonly="" /></td><td><input type="text" class="labelfield disable" size="5" style="width:100%" name="txt_desc_sd[]" id="txt_desc_sd'+index2+'" value="'+itemDesc+'" readonly="" />&nbsp;<i class="fa fa fa-paperclip cmd-box cmd-box-sd" id="CmdBox'+index2+'" data-index="'+index2+'"></i><textarea name="txt_curr_calc_desc_sd[]" id="txt_curr_calc_desc_sd'+index2+'" style="display:none;">'+calcDesc+'</textarea></td><td><input type="text" class="labelfieldright tsAmt disable" size="5" style="width:100%" name="txt_ts_rate_sd[]" id="txt_ts_rate_sd'+index2+'" value="'+TsRate+'" readonly="" /></td><td><input type="text" class="labelfieldright igcAmt disable" size="5" style="width:100%" data-index="'+index2+'" name="txt_igc_rate_sd[]" id="txt_igc_rate_sd'+index2+'" value="'+IgcRate+'" /></td><td align="center"><input type="button" class="delete" name="btn_delete" id="btn_delete'+index2+'" value=" Delete " style="width:45%"/></td></tr>'); //add input box
			$('#ItemCodeListSD0').find('option').clone().appendTo('#ItemCodeListSD'+index2);
			index2++;
			ClearSD();
			FindTotalAmountSD();
		}
	});
	$("#tab_sd_material").on("click", ".delete", function() {
    	$(this).closest("tr").remove();
		FindTotalAmountSD();
    });
	$("#tab_sd_material").on("click", "#btn_clear", function() {
		ClearSD();
	});
	$("body").on("click",".cmd-box-m",function(){
		var RowID 	= $(this).attr("data-index");
		var ExistVal = $("#txt_curr_calc_desc"+RowID).val();
		//alert(RowID); alert(ExistVal);
		BootstrapDialog.show({
            title: 'Description of Calculation',
            message: $('<textarea class="form-control" placeholder="Add your description here..." id="txt_calc_content" rows="6">'+ExistVal+'</textarea>'),
            buttons: [{
                label: ' SAVE ',
                cssClass: 'btn-primary',
                action: function(dialogRef) {
					var CalcDesc = $("#txt_calc_content").val();
					$("#txt_curr_calc_desc"+RowID).val(CalcDesc);
                   dialogRef.close();
                }
            }]
        });
	});
	$("body").on("click",".cmd-box-sd",function(){
		var RowID 	= $(this).attr("data-index");
		var ExistVal = $("#txt_curr_calc_desc_sd"+RowID).val();
		//alert(RowID); alert(ExistVal);
		BootstrapDialog.show({
            title: 'Description of Calculation',
            message: $('<textarea class="form-control" placeholder="Add your description here..." id="txt_calc_content_sd" rows="6">'+ExistVal+'</textarea>'),
            buttons: [{
                label: ' SAVE ',
                cssClass: 'btn-primary',
                action: function(dialogRef) {
					var CalcDesc = $("#txt_calc_content_sd").val();
					$("#txt_curr_calc_desc_sd"+RowID).val(CalcDesc);
                   dialogRef.close();
                }
            }]
        });
	});							
	
	
	/// FOR MERGE WITH SUB DATA - ENDS HERE //////
});
function ClearNew(){
	$("#txt_code0").val('');
	$("#txt_item_id0").val('');
	$("#txt_desc0").val('');
	$("#txt_unit0").val('');
	$("#txt_rate0").val('');
	$("#txt_quantity0").val('');
	$("#txt_amount0").val('');
	$("#txt_curr_calc_desc0").val('');
}
function ClearSD(){
	$("#txt_item_id_sd0").val('');
	$("#txt_refid_sd0").val('');
	$("#txt_desc_sd0").val('');
	$("#txt_ts_rate_sd0").val('');
	$("#txt_igc_rate_sd0").val('');
	$("#txt_curr_calc_desc_sd0").val('');
}
function FindTotalAmountNew(){ 
	var TotalAmt = 0;
	$(".NewAmt").each(function(){ 
		var Amt = $(this).val();
		TotalAmt = Number(TotalAmt)+Number(Amt);
		$('#txt_total_amount').val(TotalAmt.toFixed(2));
	});
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

///// VALIDATION WHEN SAVE BUTTON CLICKED
$("#top").submit(function(event){
	var Level = 0; var CurrLevel = ""; var GrError = 0;
	var Cost = $("#txt_cost").val();
	var Unit = $("#cmb_unit").val(); 
	var Type = $('input[type=radio][name=rad_type]:checked').val(); 
	$('select[name="cmb_group[]"]').each(function(){ 
		var groupId = $(this).val(); 
		if(groupId == "Select"){
			GrError = 1;
			CurrLevel = Level;
		}
		Level++;
	});
	if(Type == "M"){
		var Row = $('#tab_sd_material tr').length;
	}else{
		var Row = $('#tab_a1_material tr').length;
	}
	if(Cost == ""){
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
	}else if(Row <= 3){
		BootstrapDialog.alert("Please Add Atleast One Row");
		event.preventDefault();
		event.returnValue = false;
	}
});

///// TO STOP DOUBLE TIME INSERT IF REFRESH OR RELOAD
if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}

function CalulateAddDeduct(){ 
	var TotalAmt = $("#txt_total_amount").val();
	var APerc 	 = $("#txt_a_per").val();
	var BPerc 	 = $("#txt_b_per").val();
	var CPerc 	 = $("#txt_c_per").val();
	var DPerc 	 = $("#txt_d_per").val();
	var EPerc 	 = $("#txt_e_per").val();
	var GPerc 	 = $("#txt_g_per").val();
	var Qty 	 = $("#txt_cost").val();
	if((Qty != '')&&(TotalAmt != "")){
		var W 	= TotalAmt;
		var A 	= Number(W) * Number(APerc) / 100;
			if(A != 0){ A = A.toFixed(2); }
		var WC 	= Number(W) + Number(A);
			if(WC != 0){WC = WC.toFixed(2); } 
		var B 	= Number(WC) * Number(BPerc);
			if(B != 0){B = B.toFixed(2); } 
		var X 	= Number(WC) + Number(B);
			if(X != 0){X = X.toFixed(2); } 
		var C 	= Number(X) * Number(CPerc) / 100;
			if(C != 0){C = C.toFixed(2); } 
		var Y 	= Number(X) + Number(C);
			if(Y != 0){Y = Y.toFixed(2); } 
		var D 	= Number(Y) * Number(DPerc) / 100;
			if(D != 0){D = D.toFixed(2); } 
		var E 	= Number(W) * Number(EPerc) / 100;
			if(E != 0){E = E.toFixed(2); } 
		var F 	= Number(Y) + Number(D) + Number(E);
			if(F != 0){F = F.toFixed(2); } 
		var ForOneUnit 	= Number(F) / Number(Qty);
			if(ForOneUnit != 0){ForOneUnit = ForOneUnit.toFixed(2); }
		var TSRate 		= ForOneUnit;
			//if(TSRate != 0){TSRate = TSRate.toFixed(2); }
		var G 	= Number(W) * Number(GPerc) / (100 * Number(Qty));
			if(G != 0){G = G.toFixed(2); }
		var IGRate1 	= Number(ForOneUnit) + Number(G);
			if(IGRate1 != 0){IGRate1 	= IGRate1.toFixed(2); }
		var IGRate2 	= Number(IGRate1) * Number(Qty);
			if(IGRate2 != 0){IGRate2 	= IGRate2.toFixed(2); }
		$("#txt_w").val(W);
		$("#txt_a").val(A);
		$("#txt_wc").val(WC);
		$("#txt_b").val(B);
		$("#txt_x").val(X);
		$("#txt_c").val(C);
		$("#txt_y").val(Y);
		$("#txt_d").val(D);
		$("#txt_e").val(E);
		$("#txt_f").val(F);
		$("#txt_cost_one").val(ForOneUnit);
		$("#txt_township_amt").val(TSRate);
		$("#txt_g").val(G);
		$("#txt_igcar1_amt").val(IGRate1);
		//$("#txt_igcar2_amt").val(IGRate2);
	}
}

$("#txt_cost").change(function(event){  
	var Type = $('input[type=radio][name=rad_type]:checked').val();
	if(Type == 'N'){
		CalulateAddDeduct();
	}
});























