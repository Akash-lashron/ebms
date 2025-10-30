	/*
	* Add edit delete rows dynamically using jquery and php
	* http://www.amitpatil.me/
	*
	* @version
	* 2.0 (4/19/2014)
	* 
	* @copyright
	* Copyright (C) 2014-2015 
	*
	* @Auther
	* Amit Patil
	* Maharashtra (India)
	*
	* @license
	* This file is part of Add edit delete rows dynamically using jquery and php.
	* 
	* Add edit delete rows dynamically using jquery and php is freeware script. you can redistribute it and/or 
	* modify it under the terms of the GNU Lesser General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	* 
	* Add edit delete rows dynamically using jquery and php is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	* 
	* You should have received a copy of the GNU General Public License
	* along with this script.  If not, see <http://www.gnu.org/copyleft/lesser.html>.
	*/

// init variables
var trcopy;
var editing = 0;
var tdediting = 0;
var editingtrid = 0;
var editingtdcol = 0;
var inputs = ':checked,:selected,:text,textarea';
function GetTableName()
{
	var menutype = $("#cmb_menu_type").val();
	if(menutype == 1){ table1 = "mainmenu"; }
	if(menutype == 2){ table1 = "sublevel1"; }
	if(menutype == 3){ table1 = "sublevel2"; }
	if(menutype == 4){ table1 = "sublevel3"; }
	return table1;
}
$(document).ready(function(){
	// set images for edit and delete 
	$(".eimage").attr("src",editImage);
	$(".dimage").attr("src",deleteImage);
	// init table
	//table = "sublevel1";
	for(j=0;j<table1.length;j++)
	{
		blankrow = '<tr valign="top" class="inputform" height="25px"><td></td>';
		for(i=0;i<columns.length;i++){
			// Create input element as per the definition
			input = createInput(i,"");
			blankrow += '<td class="ajaxReq" align="center">'+input+'</td>';
		}
		<!--blankrow += '<td><a href="javascript:;" class="'+savebutton+'"><img src="'+saveImage+'"></a></td></tr>';-->
		blankrow += '<td align="center" width="100px"><input type="button" value=" ADD " class="addbtnstyle '+savebutton+'"></td></tr>';
		// append blank row at the end of table
			$("."+table1[j]).prepend(blankrow);
	}
	/*
	$(document).on("change","#cmb_menu_type",function(){
		var menutype = $("#cmb_menu_type").val();
		if(menutype == 1){ table = "mainmenu"; }
		if(menutype == 2){ table = "sublevel1"; }
		if(menutype == 3){ table = "sublevel2"; }
		if(menutype == 4){ table = "sublevel3"; }
		});
		alert(table);*/
	// Delete record
	
	$("#cmb_menu_type").change(function() {
	  var menutypeid = $("#cmb_menu_type").val();
	  ajax("menutypeid="+menutypeid,"get");
	});
	
	$("#cmb_mainmenu_L2").change(function() {
	  var menucode = $("#cmb_mainmenu_L2").val();
	  //alert(menucode);
	  ajax("menucode="+menucode,"getsingle");
	});
	
	$(document).on("click","."+deletebutton,function(){
		var id = $(this).attr("id");
		if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; }
		if(id){
			if(confirm("Do you really want to delete record ?"))
				ajax("rid="+id,"del");
		}
	});

	// Add new record
	//$("."+savebutton).on("click",function(){
		$(document).on("click","."+savebutton,function(){ 
		if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; } 								  
		var validation = 1;
		var $inputs =
		$(document).find("."+table).find(inputs).filter(function() {
			// check if input element is blank ??
			if($.trim( this.value ) == ""){
				$(this).addClass("error");
				validation = 0;
			}else{
				$(this).addClass("success");
			}
			return $.trim( this.value );
		});
//alert (JSON.stringify(inputs));
		var array = $inputs.map(function(){
			return this.value;
		}).get();
	//alert (JSON.stringify(array));
	//alert(array)
		var menutype = $("#cmb_menu_type").val();
		if(menutype != "")
		{
			if(menutype == 2)
			{
				var pcode = $("#cmb_mainmenu_L1").val();
				if(pcode != "")
				{
					var parentcode = pcode;
					//alert(parentcode)
				}
			}
			else if(menutype == 3)
			{
				var pcode = $("#cmb_submenu_1_L2").val();
				if(pcode != "")
				{
					var parentcode = pcode;
					//alert(parentcode)
				}
			}
			else
			{
				var parentcode = 0;
				//alert(parentcode)
			}
		}
		
		var serialized = $inputs.serialize();
		if(validation == 1){
			//alert (JSON.stringify(serialized));
			ajax(serialized+"&pcode="+parentcode,"save");
		}
	});

	// Add new record
	$(document).on("click","."+editbutton,function(){ 
		var id = $(this).attr("id");
		if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; } 
		if(id && editing == 0 && tdediting == 0){
			// hide editing row, for the time being
			/*$("."+table+" tr:first-child").fadeOut("fast");*/
			var html;
			html += "<td>"+$("."+table+" tr[id="+id+"] td:first-child").html()+"</td>";
			for(i=0;i<columns.length;i++){
				// fetch value inside the TD and place as VALUE in input field
				//alert(table);alert(id);alert(columns[i]);//alert();
				var val = $(document).find("."+table+" tr[id="+id+"] td[class='"+columns[i]+"']").html();
				input = createInput(i,val);
				html +='<td>'+input+'</td>';
			}
			//html += '<td><a href="javascript:;" id="'+id+'" class="'+updatebutton+'"><img src="'+updateImage+'"></a> <a href="javascript:;" id="'+id+'" class="'+cancelbutton+'"><img src="'+cancelImage+'"></a></td>';
			html += '<td align="center"><input type="button" value=" OK " id="'+id+'" class="'+updatebutton+' editbtnstyle">&nbsp;<input type="button" value=" CAN " " id="'+id+'" class="'+cancelbutton+' delbtnstyle"></td>';
			//alert(html);
			// Before replacing the TR contents, make a copy so when user clicks on 
			trcopy = $("."+table+" tr[id="+id+"]").html();
			$("."+table+" tr[id="+id+"]").html(html);	
			
			// set editing flag
			editing = 1;
		}
	});

	$(document).on("click","."+cancelbutton,function(){
		if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; } 											 
		var id = $(this).attr("id");
		$("."+table+" tr[id='"+id+"']").html(trcopy);
		$("."+table+" tr:last-child").fadeIn("fast");
		editing = 0;
	});

	$(document).on("click","."+updatebutton,function(){
		//if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; } 											 
		id = $(this).attr("id");alert(table);
		serialized = $("."+table+" tr[id='"+id+"']").find(inputs).serialize();
		//alert (JSON.stringify(serialized));
		ajax(serialized+"&rid="+id,"update");
		return;
		// clear editing flag
		editing = 0;
	});

	// td doubleclick event
	$(document).on("dblclick","."+table+" td",function(e){
		// check if any other TD is in editing mode ? If so then dont show editing box
		//alert(tdediting+"==="+editing);
		if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; } 
		var isEditingform = $(this).closest("tr").attr("class");
		if(tdediting == 0 && editing == 0 && isEditingform != "inputform"){
			editingtrid = $(this).closest('tr').attr("id");
			editingtdcol = $(this).attr("class");
			if(editingtdcol != undefined){
				var text = $(this).html();
				var tr = $(this).parent();
				var tbody = tr.parent();
				for (var i = 0; i < tr.children().length; i++) {
					if (tr.children().get(i) == this) {
						var column = i;
						break;
					}
				}
				
				// decrement column value by one to avoid sr no column
				column--; 
				//alert(column+"==="+placeholder[column]);
				if(column <= columns.length){
					var text = $(this).html();
					//alert(text);
					input = createInput(column,text);
					$(this).html(input);
					$(this).find(inputs).focus();
					tdediting = 1;
				}
			}
		}
	});
	
	// td lost focus event
	
	$(document).on("blur","."+table+" td",function(e){
		if(tdediting == 1){
			//if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; }
			var newval = $("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").find(inputs).val();
			ajax(editingtdcol+"="+newval+"&rid="+editingtrid,"updatetd");
		}
	});
	
});

createInput = function(i,str){
	str = typeof str !== 'undefined' ? str : null;
	if(inputType[i] == "text"){
		input = '<input type='+inputType[i]+' class="dynamictextbox" name='+columns[i]+' placeholder="'+placeholder[i]+'" value="'+str+'" >';
	}else if(inputType[i] == "textarea"){
		input = '<textarea name='+columns[i]+' placeholder="'+placeholder[i]+'">'+str+'</textarea>';
	}
	return input;
}

ajax = function (params,action){
	var menutype = $("#cmb_menu_type").val();
	$.ajax({ 
		type: "POST", 
		url: "ajax.php", 
		data : params+"&action="+action+"&temp="+temp+"&menutype="+menutype,//here temp is global variable, it will differentiate the function for role create and menu create.
		dataType: "json",
		success: function(response){//alert(response)
		  switch(action){ 
			case "save":
				//if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; } 
				var seclastRow = $("."+table+" tr").length;
				if(response.success == 1){
					var html = "";
					
					html += "<td>"+parseInt(seclastRow - 1)+"</td>";
					for(i=0;i<columns.length;i++){
						html +='<td class="'+columns[i]+'">'+response[columns[i]]+'</td>';
					}
					//html += '<td><a href="javascript:;" id="'+response["id"]+'" class="ajaxEdit"><img src="'+editImage+'"></a> <a href="javascript:;" id="'+response["id"]+'" class="'+deletebutton+'"><img src="'+deleteImage+'"></a></td>';
					html += '<td align="center"><input type="button" id="'+response["id"]+'" class="editbtnstyle ajaxEdit" value=" EDIT "> <input type="button" id="'+response["id"]+'" class="delbtnstyle '+deletebutton+'" value=" DEL "></td>';
					// Append new row as a second last row of a table
					//$("."+table+" tr").last().before('<tr id="'+response.id+'">'+html+'</tr>');
					//$("."+table+" tr").last().before('<tr id="'+response.id+'">'+html+'</tr>');
					
					$("."+table+" tr").eq(seclastRow-seclastRow+2).before('<tr id="'+response.id+'">'+html+'</tr>');
					
					if(effect == "slide"){
						// Little hack to animate TR element smoothly, wrap it in div and replace then again replace with td and tr's ;)
						$("."+table+" tr:nth-child("+seclastRow+")").find('td')
						 .wrapInner('<div style="display: none;" />')
						 .parent()
						 .find('td > div')
						 .slideDown(700, function(){
							  var $set = $(this);
							  $set.replaceWith($set.contents());
						});
					}
					else if(effect == "flash")
					{
					   //$("."+table+" tr:nth-child("+seclastRow+")").effect("highlight",{color: '#acfdaa'},100);
					   $("."+table+" tr").eq(seclastRow-seclastRow+2).effect("highlight",{color: '#acfdaa'},1000);
					}else
					   //$("."+table+" tr:nth-child("+seclastRow+")").effect("highlight",{color: '#acfdaa'},1000);
					   $("."+table+" tr").eq(seclastRow-seclastRow+2).effect("highlight",{color: '#acfdaa'},1000);

					// Blank input fields
					$(document).find("."+table).find(inputs).filter(function() {
						// check if input element is blank ??
						this.value = "";
						$(this).removeClass("success").removeClass("error");
					});
				}
			break;
			case "del":
				//if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; }  alert("name="+table)
				var seclastRow = $("."+table+" tr").length;
				if(response.success == 1){
					$("."+table+" tr[id='"+response.id+"']").effect("highlight",{color: '#f4667b'},500,function(){
						$("."+table+" tr[id='"+response.id+"']").remove();
					});
				}
			break;
			
			case "get":
				//alert (JSON.stringify(response));
				if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; }
				
				var seclastRow = $("."+table+" tr").length;
				for(x2=0 ; x2 < seclastRow-2 ; x2++ ){
					$("."+table+" tr:last").remove();
				}
				var x1=1,x3=1,x4=1,x5=1; var tablerow = "";
				
				$.each(response, function(index, element) {
									 
					if(element.moduleid != "")
					{
						if(element.type == menutype)
						{
							var moduleid = element.moduleid;
							var modulename = element.moduledname;
							var modulecode = element.modulecode;
							var moduletype = element.type;
							tablerow += '<tr id="'+moduleid+'">';
							tablerow += '<td>'+x1+'</td>';
							tablerow += '<td class="menuname">'+modulename+'</td>';
							tablerow += '<td class="menucode">'+modulecode+'</td>';
							tablerow += '<td align="center"><input type="button" id="'+moduleid+'" class="editbtnstyle ajaxEdit" value=" EDIT ">&nbsp;';
							tablerow += '<input type="button" id="'+moduleid+'" class="delbtnstyle '+deletebutton+'" value=" DEL "></td>';
							tablerow += '</tr>';
							x1++;
						}
/// THIS IS FOR SECTION SUB LEVEL 1 SELECTION
						if(menutype == 2)
						{
							if(element.type == 1)
							{
								var modulename = element.moduledname;
								var modulecode = element.modulecode;
								//Remove all the option for main menu for the first time in loop					
								if(x3 == 1)
								{
								$('#cmb_mainmenu_L1')
									.find('option:not(:first)')
									.remove()
									.end();
								}
								//Append all the option for main menu						
								$('#cmb_mainmenu_L1')
											 .append($("<option></option>")
											 .attr("value",modulecode)
											 .text(modulename));
								x3++;
							}
						}
/// THIS IS FOR SECTION SUB LEVEL 2 SELECTION
						if(menutype == 3)
						{
							if(element.type == 1)
							{
								var modulename = element.moduledname;
								var modulecode = element.modulecode;
								//Remove all the option for main menu for the first time in loop						
								if(x4 == 1)
								{
								$('#cmb_mainmenu_L2')
									.find('option:not(:first)')
									.remove()
									.end();
								}
								//Append all the option for main menu						
								$('#cmb_mainmenu_L2')
											 .append($("<option></option>")
											 .attr("value",modulecode)
											 .text(modulename));
								x4++;
							}
							/*if(element.type == 2)
							{
								var modulename = element.moduledname;
								//Remove all the option for sub menu level 1 for the first time in loop						
								if(x5 == 1)
								{
								$('#cmb_submenu_1_L2')
									.find('option:not(:first)')
									.remove()
									.end();
								}
								//Append all the option for sub menu leve 1						
								$('#cmb_submenu_1_L2')
											 .append($("<option></option>")
											 .attr("value",moduleid)
											 .text(modulename));
								x5++;
							}*/
						}
					}
					//}));
				});
				//$("."+table+" tr").eq(seclastRow-seclastRow+2).after(tablerow);
				//$("."+table+" tr:last").append(tablerow);
				if(response.success != 0){
				$("."+table+" tbody").append(tablerow);
				}
			break;
			
			case "getsingle":
			//alert (JSON.stringify(response));
					var y1=1,y2=1,y3=1,y4=1; var tablerow = "";
					if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; }
					$('#cmb_submenu_1_L2')
										.find('option:not(:first)')
										.remove()
										.end();
										
					$.each(response, function(index, element) {
						var moduleid = element.moduleid;
						if(element.moduleid)
						{
							var modulename = element.moduledname;
							var modulecode = element.modulecode;				
							$('#cmb_submenu_1_L2')
											.append($("<option></option>")
											.attr("value",modulecode)
											.text(modulename));	
						}
						
					});
					//var seclastRow = $("."+table+" tr").length;
					//for(x2=0 ; x2 < seclastRow-2 ; x2++ ){
						//("."+table+" tr:last").remove();
					//}
			break;
			
			case "update":
				//if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; } alert(table);
				$("."+cancelbutton).trigger("click");
				for(i=0;i<columns.length;i++){
					$("tr[id='"+response.id+"'] td[class='"+columns[i]+"']").html(response[columns[i]]);
				}
			break;
			case "updatetd":
				//if(temp == "menu"){ table = GetTableName(); } else { table = table1[0]; } 
				//$("."+cancelbutton).trigger("click");
				var newval = $("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").find(inputs).val();
				
				//alert($("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").html());
				$("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").html(newval);

				//$("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").html(newval);
				// remove editing flag
				tdediting = 0;
				$("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").effect("highlight",{color: '#acfdaa'},1000);
			break;
		  }
		},
		error: function(){
			alert("Unexpected error, Please try again");
		}
	});
}