<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "common.php";
checkUser();
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$count = 0; $SecuredAdvance = 0;
$i=0;

if(isset($_POST["btn_skip"]) == "Skip >"){ 
    $sheetid 			= 	trim($_POST['txt_sheetid']);
    $rbn 				= 	trim($_POST['txt_rbn']);
}
if(isset($_POST["btn_next"]) == "Next"){ 
    $sheetid 			= 	trim($_POST['txt_sheetid']);
    $rbn 				= 	trim($_POST['txt_rbn']);
}
if(($_GET["sheetid"] != "")&&($_GET["rbn"] != "")){ 
    $sheetid 			= 	$_GET['sheetid'];
    $rbn 				= 	$_GET['rbn'];
}
if($sheetid != ""){
	$query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
	$sqlquery 	= 	mysqli_query($dbConn,$query);
	if($sqlquery == true){
		$List 					= 	mysqli_fetch_object($sqlquery);
		$work_name 				= 	$List->work_name; 
		$short_name 			= 	$List->short_name;   
		$tech_sanction 			= 	$List->tech_sanction;  
		$name_contractor 		= 	$List->name_contractor; 
		$ccno 					= 	$List->computer_code_no;    
		$agree_no 				= 	$List->agree_no; 
		$overall_rebate_perc 	= 	$List->rebate_percent; 
		$work_order_no 			= 	$List->work_order_no; /*  if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
		$work_order_date 		= 	$List->work_order_date;
		$work_commence_date 	= 	$List->work_commence_date;
		$sch_doc 				= 	$List->date_of_completion;
		$act_doc 				= 	$List->act_doc;
		$workcost 				= 	$List->work_order_cost;
		$workcomplete 		    = 	$List->date_of_completion;
		//$rbn 		            = 	$List->rbn;
	}
}
if(($sheetid != "")&&($rbn != "")){
	$SelectAbstQuery = "select * from abstractbook where sheetid = '$sheetid' and rbn = '$rbn'";
	$SelectAbstSql = mysqli_query($dbConn,$SelectAbstQuery);
	if($SelectAbstSql == true){
		if(mysqli_num_rows($SelectAbstSql)>0){
			$AbstList = mysqli_fetch_object($SelectAbstSql);
			$upto_date_total_amount = $AbstList->upto_date_total_amount;
			$dpm_total_amount = $AbstList->dpm_total_amount;
			$slm_total_amount = $AbstList->slm_total_amount;
			$Uptombookno = $AbstList->mbookno;
			$Uptombookpage = $AbstList->mbookpage;
		}
	}
}
if(($sheetid != "")&&($rbn != "")){
	$Acc = 0;
	$AccSelectQuery = "select * from memo_payment_accounts_edit where sheetid = '$sheetid' and rbn = '$rbn'";
	$AccSelectSql 	= mysqli_query($dbConn,$AccSelectQuery);
	if($AccSelectSql == true){
		if(mysqli_num_rows($AccSelectSql)>0){
			$AccList = mysqli_fetch_object($AccSelectSql);
			$abstract_net_amt = $AccList->abstract_net_amt;
			$cgst_percent = $AccList->cgst_tds_perc;
			$cgst_amt = $AccList->cgst_tds_amt;
			$sgst_percent = $AccList->sgst_tds_perc;
			$sgst_amt = $AccList->sgst_tds_amt;
			$sd_percent = $AccList->sd_percent;
			$sd_amt = $AccList->sd_amt;
			$wct_percent = $AccList->wct_percent;
			$wct_amt = $AccList->wct_amt;
			$vat_percent = $AccList->vat_percent;
			$vat_amt = $AccList->vat_amt;
			$mob_adv_percent = $AccList->mob_adv_percent;
			$mob_adv_amt = $AccList->mob_adv_amt;
			$lw_cess_percent = $AccList->lw_cess_percent;
			$lw_cess_amt = $AccList->lw_cess_amt;
			$incometax_percent = $AccList->incometax_percent;
			$incometax_amt = $AccList->incometax_amt;
			$it_cess_percent = $AccList->it_cess_percent;
			$it_cess_amt = $AccList->it_cess_amt;
			$it_edu_percent = $AccList->it_edu_percent;
			$it_edu_amt = $AccList->it_edu_amt;
			$land_rent = $AccList->land_rent;
			$liquid_damage = $AccList->liquid_damage;
			$other_recovery_1_desc = $AccList->other_recovery_1_desc;
			$other_recovery_1_amt = $AccList->other_recovery_1_amt;
			$other_recovery_2_desc = $AccList->other_recovery_2_desc;
			$other_recovery_2_amt = $AccList->other_recovery_2_amt;
			$non_dep_machine_equip = $AccList->non_dep_machine_equip;
			$non_dep_man_power = $AccList->non_dep_man_power;
			$nonsubmission_qa = $AccList->nonsubmission_qa;
			
			$sec_adv_amount = $AccList->sec_adv_amount;
			$electricity_cost = $AccList->electricity_cost;
			$water_cost = $AccList->water_cost;
			
			$edit_flag = $AccList->edit_flag;
			
			
			$Acc = 1;
		}
	}
	//echo $lw_cess_amt;exit;
	if($Acc == 0){
		$AccSelectQuery = "select * from generate_otherrecovery where sheetid = '$sheetid' and rbn = '$rbn'";
		$AccSelectSql 	= mysqli_query($dbConn,$AccSelectQuery);
		if($AccSelectSql == true){
			if(mysqli_num_rows($AccSelectSql)>0){
				$AccList = mysqli_fetch_object($AccSelectSql);
				$abstract_net_amt = $AccList->abstract_net_amt;
				$cgst_percent = $AccList->cgst_percent;
				$cgst_amt = $cgst_percent->cgst_amt;
				$sgst_percent = $AccList->sgst_percent;
				$sgst_amt = $AccList->sgst_amt;
				$sd_percent = $AccList->sd_percent;
				$sd_amt = $AccList->sd_amt;
				$wct_percent = $AccList->wct_percent;
				$wct_amt = $AccList->wct_amt;
				$vat_percent = $AccList->vat_percent;
				$vat_amt = $AccList->vat_amt;
				$mob_adv_percent = $AccList->mob_adv_percent;
				$mob_adv_amt = $AccList->mob_adv_amt;
				$lw_cess_percent = $AccList->lw_cess_percent;
				$lw_cess_amt = $AccList->lw_cess_amt;
				$incometax_percent = $AccList->incometax_percent;
				$incometax_amt = $AccList->incometax_amt;
				$it_cess_percent = $AccList->it_cess_percent;
				$it_cess_amt = $AccList->it_cess_amt;
				$it_edu_percent = $AccList->it_edu_percent;
				$it_edu_amt = $AccList->it_edu_amt;
				$land_rent = $AccList->land_rent;
				$liquid_damage = $AccList->liquid_damage;
				$other_recovery_1_desc = $AccList->other_recovery_1_desc;
				$other_recovery_1_amt = $AccList->other_recovery_1;
				$other_recovery_2_desc = $AccList->other_recovery_2_desc;
				$other_recovery_2_amt = $AccList->other_recovery_2;
				$non_dep_machine_equip = $AccList->non_dep_machine_equip;
				$non_dep_man_power = $AccList->non_dep_man_power;
				$nonsubmission_qa = $AccList->nonsubmission_qa;
				$edit_flag = $AccList->edit_flag;
				$Acc = 1;
			}
		}
		$AccSelectSAQuery = "select * from secured_advance where sheetid = '$sheetid' and rbn = '$rbn'";
		$AccSelectSASql = mysqli_query($dbConn,$AccSelectSAQuery);
		if($AccSelectSASql == true){
			if(mysqli_num_rows($AccSelectSASql)>0){
				$AccSAList = mysqli_fetch_object($AccSelectSASql);
				$sec_adv_amount = $AccSAList->sec_adv_amount;
			}
		}
		
		$electricity_cost = 0;
		$AccSelectEBQuery = "select electricity_cost from generate_electricitybill where sheetid = '$sheetid' and rbn = '$rbn'";
		$AccSelectEBSql = mysqli_query($dbConn,$AccSelectEBQuery);
		if($AccSelectEBSql == true){
			if(mysqli_num_rows($AccSelectEBSql)>0){
				while($AccEBList = mysqli_fetch_object($AccSelectSASql)){
					$electricity_cost = $electricity_cost + $AccEBList->electricity_cost;
				}
			}
		}
		
		$water_cost = 0;
		$AccSelectWBQuery = "select water_cost from generate_waterbill where sheetid = '$sheetid' and rbn = '$rbn'";
		$AccSelectWBSql = mysqli_query($dbConn,$AccSelectWBQuery);
		if($AccSelectWBSql == true){
			if(mysqli_num_rows($AccSelectWBSql)>0){
				while($AccWBList = mysqli_fetch_object($AccSelectWBSql)){
					$water_cost = $water_cost + $AccWBList->water_cost;
				}
			}
		}
		
	}
	$SCodeRecArr = array();
	$AccSelectSCodeQuery = "select a.*, b.shortcode from mop_rec_dt a inner join shortcode_master b on (a.shortcode_id = b.shortcode_id) where a.sheetid = '$sheetid' and a.rbn = '$rbn'";
	$AccSelectSCodeSql = mysqli_query($dbConn,$AccSelectSCodeQuery);
	if($AccSelectSCodeSql == true){
		if(mysqli_num_rows($AccSelectSCodeSql)>0){
			while($AccSCList = mysqli_fetch_object($AccSelectSCodeSql)){
				$SCodeRecArr[$AccSCList->rec_code] = $AccSCList;
			}
		}
	}
	
}
$NetTotal = $slm_total_amount + $sec_adv_amount;
//print_r($SCodeRecArr);exit;
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack(){
	   	url = "PageGenerate.php";
		window.location.replace(url);
	}
	function PrintBook(){
	   var printContents 		= document.getElementById('printSection').innerHTML;
		var originalContents 	= document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
	window.history.forward();
	function noBack(){ 
		window.history.forward(); 
	}
</script>
<script src="stepWizard/bootstrap.min.js"></script>
<script>
var page = "MBKG";
var page_url = "AccountsGenerateSection4.php";
var bg = bg || false;
if(!bg){
	if(typeof jQuery != 'undefined'){
		bg = jQuery;
	} else if(typeof angular != 'undefined'){
		bg = angular.element;
		
		(function(){
			bg.extend = angular.extend;
			bg.isFunction = angular.isFunction;
		
			bg.prototype.is = function (selector){
				for(var i=0;i<this.length;i++){
					var el = this[i];
					if((el.matches || el.matchesSelector || el.msMatchesSelector || el.mozMatchesSelector || el.webkitMatchesSelector || el.oMatchesSelector).call(el, selector)) return true;
				}
				return false;
			}
		
			bg.prototype.find = function (selector){
				var context = this[0],matches = [];
				// Early return if context is not an element or document
				if (!context || (context.nodeType !== 1 && context.nodeType !== 9) || typeof selector != 'string') {
					return [];
				}
				
				for(var i=0;i<this.length;i++){
					var elm = this[i],
					nodes = bg(elm.querySelectorAll(selector));
					matches.push.apply(matches, nodes.slice());
				}
				
				return bg(matches);
			};
			
			bg.prototype.outerWidth = function () {
				var el = this[0];
				if(typeof el == 'undefined') return null;
				return el.offsetWidth;
			};
			
			bg.prototype.width = function () {
				var el = this[0];
				if(typeof el == 'undefined') return null;
				var computedStyle = getComputedStyle(el);
				var width = el.offsetWidth;
				if (computedStyle)
					width -= parseFloat(computedStyle.paddingLeft) + parseFloat(computedStyle.paddingRight);
				return width;
			};
		
		})();
	}
}
 
;(function ($, document, window){

	"use strict";
	
    var pluginName = "wizardByGiro",
    // the name of using in .data()
	dataPlugin = "plugin_" + pluginName,
	defaults = {
		currentStep: 0,
		checkStep: false,
		onCompleted: false,
		bottomButtons: true,
		topButtons: true,
		autoSubmit: false,
		keyboard: false,
		btnClass: 'btn',
		btnClassDefault: 'btn-default',
		btnClassCompleted: 'btn-success',
		text:{
			finished: 'Complete',
			next: 'Next',
			previous: 'Previous'
		}
	},
	
	attachEventsHandler = function(){
		var that = this,
		opts = this.options;
		
		/*that.$element.find('.btn-next, .btn-prev').on('click', function(e){
			if($(this).attr('disabled') || $(this).hasClass('disabled') || !$(this).is(':visible')) return;

			var type = $(this).hasClass('btn-next') ? 'next' : 'previous';
			e.stopPropagation();
			that[type].call(that,true,e);
		});*/
		
		that.$element.find('.steps > li').on('click', function(e){
			e.stopPropagation();
			var step = $(this).attr('data-step'),
			isCompleted = $(this).hasClass('completed');
			if(!isCompleted) return true;
			
			that.setStep.call(that,step,e);
		});
		
		$(document).on('keydown', function(e){
			if(!that.$element.is(':visible')) return;
			
			// arrow left
			if(e.ctrlKey && e.keyCode == 37){
				e.stopPropagation();
				e.preventDefault();
				that.previous.call(that,true,e);
			}			

			// arrow right
			if(e.ctrlKey && e.keyCode == 39){
				e.stopPropagation();
				e.preventDefault();
				that.next.call(that,true,e);
			}
		});

	},
	
	checkStatus = function(){
		var that = this,
			currentWidth,
			stepsWidth = 0,
			stepPosition = false,
			steps = that.$element.find('.steps'),
			stepsItems = that.$element.find('.steps > li'),
			opts = that.options;
			
		if(!this.currentStep) this.currentStep = 1;
		
		stepsItems.removeClass('active');
		that.$element
			.find('.steps > li[data-step="'+ that.currentStep +'"]')
			.addClass('active');
			
		that.$element.find('.steps-content .step-pane').removeClass('active');
		var current = that.$element.find('.steps-content .step-pane[data-step="'+ that.currentStep +'"]');
			current.addClass('active');

		for(var i=0;i<stepsItems.length;i++){
			var stepLi = $(stepsItems[i]);
			if(that.currentStep > (i+1)){
				stepLi.addClass('completed');
			} else {
				stepLi.removeClass('completed');
			}
			
			currentWidth = stepLi.outerWidth();
			if(!stepPosition && stepLi.hasClass('active')){				
				stepPosition = stepsWidth + (currentWidth / 2);
			}
			
			stepsWidth += currentWidth;			
		}
		
		// set buttons based on current step
		that.$element.find('.btn-next').removeClass('final-step '+ opts.btnClassCompleted).addClass(opts.btnClassDefault);
		that.$element.find('.btn-prev').removeClass('disabled hidden');
		if(that.currentStep == stepsItems.length){
			// we are in the last step
			that.$element.find('.btn-next').removeClass(opts.btnClassDefault).addClass('final-step '+ opts.btnClassCompleted);
		} else if(that.currentStep == 1){
			that.$element.find('.btn-prev').addClass('disabled hidden');
		}		
		
		// move steps view if needed
		var totalWidth = that.$element.width() - that.$element.find('.actions').outerWidth();
		
		if(stepsWidth < totalWidth) return;
		
		var offsetDiff = stepPosition - (totalWidth / 2);
		if(offsetDiff > 0){
			// move it forward
			steps.css('left',-offsetDiff);
		} else {
			// move it backward
			steps.css('left',0);
		}
	},
	
	moveStep = function(step, direction, event, checkStep){		
		var that = this, canMove = true,
		steps = that.$element.find('.steps > li'),
		triggerEnd = false;
		
		checkStep = checkStep === false ? false : true;

		// check we can move
		if(checkStep && typeof that.options.checkStep == 'function'){
			canMove = that.options.checkStep(that,direction,event);
		}
		
		if(!canMove) return;
		
		if(step){
			that.currentStep = parseInt(step);
		} else {
			if(direction){
				that.currentStep++;
			} else {
				that.currentStep--;
			}
		}
		
		that.$element.triggerHandler('step_changed.wizardByGiro');
		
		if(that.currentStep < 0) that.currentStep = 0;
		if(that.currentStep > steps.length){
			that.currentStep = steps.length;
			triggerEnd = true;
		}
		
		checkStatus.call(that);
		
		if(triggerEnd){
			if(typeof that.options.onCompleted == 'function'){
				that.options.onCompleted(that);
			} else if(that.options.autoSubmit) {
				// search if wizard is inside a form and submit it.
				var form = that.$element.closest('form');
				if(form.length)	form.submit();
			}
		}
	},
		
	methods = {
		init: function (element, options) {
			var that = this;
			this.$element = $(element);
			this.options = $.extend({},	defaults, options);
			
			var opts = this.options;

			this.$element.addClass('wizard');
						
			// add the buttons
			var stepsBar = this.$element.find('.steps'),
			topActions = this.$element.find('.top-actions'),
			bottomActions = this.$element.find('.bottom-actions'),
			progressBar = this.$element.find('.progress-bar'),
			html = '';
			
			// wrap steps in a container with hidden overflow, if it doesn't have a container
			if(stepsBar.parent().hasClass('wizard')){
				// let's add a container
				stepsBar.wrap('<div class="steps-index-container"></div>');				
			} else {
				stepsBar.parent().addClass('steps-index-container');
			}
			
			if(opts.topButtons && stepsBar.length && !topActions.length){
				html += '<div class="top-actions"><div class="btn-group">';
				
				
				html += '<span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-prev"><input type="button" name="btn_previous" id="btn_previous1" value="Previous" style="background:none; border:none; padding:0px; box-shadow:none; height:23px; color:#ffffff; font-size:13px;"></span>';
				//html += '<span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-next"><span class="next-text">123'+ opts.text.next +'</span><span class="finished-text">'+ opts.text.finished +'</span></span>';
				html += '<span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-next"><input type="submit" name="btn_next" id="btn_next1" value="Next" style="background:none; border:none; padding:0px; box-shadow:none; height:23px;"><span class="finished-text">'+ opts.text.finished +'</span></span>';
				
				html += '</div></div>';
				
				stepsBar.after(html);
			}
			
			html = '';
			if(opts.bottomButtons && !bottomActions.length){
				html += '<div class="bottom-actions">';
				//html += '<div class="left-actions"><span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-prev"><input type="button" name="btn_previous" id="btn_previous2" value="Previous" style="background:none; border:none; padding:0px; box-shadow:none; height:25px; color:#ffffff; font-weight:bold;"></span></div>';
				//html += '<div class="right-actions"><span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-next"><span class="next-text">123'+ opts.text.next +'</span><span class="finished-text">'+ opts.text.finished +'</span></span></div>';
				//html += '<div class="right-actions"><span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-next"><input type="submit" name="btn_next" id="btn_next2" value="Next" style="background:none; border:none; padding:0px; box-shadow:none; height:25px;"><span class="finished-text">'+ opts.text.finished +'</span></span><input type="button" class="backbutton" name="btn_skip" id="btn_skip" value="Skip >">&nbsp;</div>';
				html += '<div class="right-actions"><input type="button" name="btn_print" value="Print" id="btn_print" class="backbutton printbutton" onClick="PrintBook();" /></div>';
				html += '<div class="right-actions"><input type="submit" class="backbutton printbutton" name="btn_skip" id="btn_skip" value="Skip >">&nbsp;<br/>&nbsp;</div>';
				html += '</div>';
				
				that.$element.find('.steps-content').append(html);
			}

			// add arrows to btn
			this.$element.find('.btn-prev').prepend('<i class="wiz-icon-arrow-left"></i>');
			this.$element.find('.btn-next').append('<i class="wiz-icon-arrow-right"></i>');
			
			// get steps and prepare them
			var stepsLi = this.$element.find('.steps > li');
			for(var i=0;i<stepsLi.length;i++){
				var step = $(stepsLi[i]),
				target = step.attr('data-step'),
				content = '<span class="step-text">'+ step.html() +'</span>';
				
				step.empty().html('<span class="step-index"><span class="label">'+ (i+1) +'</span></span>'+ content + '<span class="wiz-icon-chevron-right colorA"></span><span class="wiz-icon-chevron-right colorB"></span>');
				
				that.$element.find('.steps-content [data-step="'+ target +'"]').addClass('step-pane');
				
				// detect currentStep
				if(step.hasClass('active') && !that.currentStep){
					that.currentStep = i+1;
				}				
			}

			this.$element.find('.steps > li:last-child').addClass('final');
			
			//attachEventsHandler.call(this);
			
			var callbacks = ['checkStep','onCompleted'],cb;
			for(var i=0;i<callbacks.length;i++){
				cb = callbacks[i];
				if(typeof this.options[cb] == 'string' && typeof window[this.options[cb]] == 'function'){
					this.options[cb] = window[this.options[cb]];
				}
			}
		
			checkStatus.call(this);
		},

		next: function(checkStep,event){
			moveStep.call(this,false,true,event,checkStep);
		},
		
		previous: function(checkStep,event){
			moveStep.call(this,false,false,event,checkStep);
		},
		
		setStep: function(step, checkStep, event){
			moveStep.call(this,step,null,event,checkStep);
		}
	};
		
    var main = function (method) {
        var thisPlugin = this.data(dataPlugin);
        if (thisPlugin) {
            if (typeof method === 'string' && thisPlugin[method]) {
                return thisPlugin[method].apply(thisPlugin, Array.prototype.slice.call(arguments, 1));
            }
            return console.log('Method ' + arg + ' does not exist on jQuery / jqLite' + pluginName);
        } else {
            if (!method || typeof method === 'object') {
				thisPlugin = $.extend({}, methods);
				thisPlugin.init(this, method);
				this.data(dataPlugin, thisPlugin);

				return this;
            }
            return console.log( pluginName +' is not instantiated. Please call $("selector").'+ pluginName +'({options})');
        }
    };

	// plugin integration
	if($.fn){
		$.fn[ pluginName ] = main;
	} else {
		$.prototype[ pluginName ] = main;
	}

	$(document).ready(function(){
		var mySelector = document.querySelector('[data-wizard-init]');
		$(mySelector)[ pluginName ]({});				
	});
}(bg, document, window));


function ValidateForm(id)
{
	if(id == "txt_fromdate")
	{	var dt=document.form.txt_fromdate.value; }
	if(id == "txt_todate")
	{	var dt=document.form.txt_todate.value; }
	if (isDate(dt)==false)
	{
		var a="Date format should be dd/mm/yyyy";
		$('#date_format').text(a);
		return false
	}
	if (isDate(dt)==true)
	{
		var a="";
		$('#date_format').text(a);
		return true;
	}
}
function checkmeasurement()
{
	var workorderno 	= document.form.txt_sheetid.value;
	var fromdate 		= document.form.txt_fromdate.value;
	var todate 			= document.form.txt_todate.value;
	var measure_type 	= "S";//document.form.rad_measurementtype.value;
	var current_rbn 	= document.form.txt_rbn.value;
	if((workorderno != "") && (fromdate != "") && (todate != "") && (measure_type != ""))
	{
		var xmlHttp;
		var data;
		if (window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) // For Internet Explorer
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL = "check_measurement_generate.php?sheetid=" + workorderno + "&fromdate=" + fromdate + "&todate=" + todate + "&measure_type=" + measure_type;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function ()
		{
			if(xmlHttp.readyState == 4)
			{
				data = xmlHttp.responseText;
				if (data != "")
				{
					if(data == 0)
					{
						document.getElementById("btn_next1").disabled = true;
						document.getElementById("btn_next2").disabled = true;
						swal("No measurements available for this period","","");
						document.getElementById("check_date").innerHTML = "No measurements available for this period";
						return false;
					}
					else
					{
						document.getElementById("btn_next1").disabled = false;
						document.getElementById("btn_next2").disabled = false;
						document.getElementById("check_date").innerHTML = "";
						return true;
					}
				}
			}
		}
	}
	xmlHttp.send(strURL);
	//return false;
}
</script>
<link href="stepWizard/jquery.wizard.css" rel="stylesheet">
<style>
	.table1{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
	}
	.label{
		font-size:13px;
		font-weight:normal;
	}
	.table1 td{
	padding:3px;
	}
	.labellarge{
		font-size:18px;
	}
	.labelmedium{
		font-size:13px;
	}
	@media print {
		#printSection{
			padding-top:2px;
			text-align:center;
		}
	} 
</style>
<style type="text/css">
	
	[data-wizard-init] {
	margin: auto;
	width: 100%;
	overflow:auto;
	}
	.container-fluid{
	margin-top:10px;
	}
	p {
    margin-top: 20px;
    color: #072FEB;
    font-weight: normal;
	}
	.container{
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	table.label, .badge {
        padding: 0px 0px;
		background-color:#FFFFFF;
		font-weight: normal;
	}
	.table1 {
   	 border: 0px solid #D3D3D3;
	}
	span.label, .badge{
		background:#0099C6;
		padding-left:4px;
		padding-right:4px;
		padding-top:1px;
		padding-bottom:1px;
		font-weight:bold;
		color:#b5b5b5;
	}
	span.badge{
		background:#189de8;
		padding-left:6px;
		padding-right:6px;
		padding-top:3px;
		padding-bottom:3px;
		font-weight:bold;
		color:#FFFFFF;
	}
	.table1 td{
		vertical-align:middle;
	}
	.labelprint
	{
		font-weight:normal;
		color:#0B29B9;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:10pt;
	}
	.labelbold{
		font-weight:bold;
	}
	.btn-default, .btn-next{
		background:#B90250;
		color:#FFFFFF;
		text-shadow:none;
	}
	.btn-default:hover, .btn-next:hover{
		background:#dd025d;
		color:#FFFFFF;
		text-shadow:none;
	}
	.btn-prev{
		background:#0466B7;
		color:#FFFFFF;
		text-shadow:none;
	}
	.btn-prev:hover{
		background:#047ee2;
		color:#FFFFFF;
		text-shadow:none;
	}
	.final-step, .btn-success{
		background:#298E0D;
		color:#FFFFFF;
		text-shadow:none;
	}
	.final-step:hover, .btn-success:hover{
		background:#2ebc03;
		color:#FFFFFF;
		text-shadow:none;
	}
	.table1 td{
		background:#FFFFFF;
		/*color:#005BB7;*/
	}
	.labelbold{
		color:#00008B;
		font-size: 13px;
	}
	.labeldisplay{
		color:#0000CC;
		font-size: 13px;
	}
	.wiz-icon-arrow-right:before {
		padding-top:0px;
	}
	.wiz-icon-arrow-left::before {
		padding-top:0px;
	}
	.sweet-alert fieldset input[type="text"] {
		display: none;
	}
	.ui-datepicker select.ui-datepicker-month, .ui-datepicker select.ui-datepicker-year{
		z-index:99999999999999;
	}
	/*padding: 37px 20px 0 38px;*/
	.btn{
	 	padding: 0px 12px;
	 	height: 25px;
		border: 0px solid #ccc;
	}
	#btn_previous1, #btn_next1{
		font-size:12px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-weight:normal;
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <link rel="stylesheet" href="css/timeline.css">
  <!--==============================Content=================================-->
	<div class="content">
    	<?php include "MainMenu.php"; ?>
        <div class="container_12">
        	<div class="grid_12">
            	 <blockquote class="bq1" style="overflow:auto">
                	<form name="form" method="post" action="AccountsGenerateSection4.php">
                    	<div class="container">
							<div class="div1">&nbsp;</div>
							<div class="div10">
							<div class="container-fluid">
								<div data-wizard-init>
								  <ul class="steps">
									<li data-step="1">Memo Payment</li>
									<li data-step="2" >Abstract - B</li>
									<li data-step="3" class="active">Recovery</li>
									<li data-step="4">Accounts Works</li>
									<li data-step="5">Bill Miscellaneous</li>
									<!--<li data-step="6">Certificate Of Deduction</li>-->
								  </ul>
								  <div class="steps-content" align="center" id="printSection">
								  <style>
									@media print {
										.printbutton{
											display:none;
										}
									} 
									</style>
									<div data-step="3" align="center">
										<table width="100%" class="tableA" align="center">
											<tr>
												<td colspan="4" align="center"> <b>Recovery Particulars - <?php echo $rbn; ?> <?php echo $RabText; ?></b></td>
											</tr>
											<tr class="label">
												<td colspan="4">
													<span style="float:left">CCODE : <?php echo $ccno; ?></span>
													<span style="float:right">S.No.<?php echo ++$i;?>&nbsp;&nbsp;&nbsp;</span>
												</td>
											</tr>
											<tr class="label">
												<td align="left" colspan="1">1. Name of Contractor </td>
												<td align="left" colspan="3"><?php echo $name_contractor; ?> </td>
											</tr>
											
											
											
											<tr class="label">
												<td align="left" colspan="1">2. Name of Work </td>
												<td align="left" colspan="3" style="white-space:normal"><?php echo $work_name; ?> </td>
											</tr>
											<tr class="label">
												<td align="left" colspan="1">3. Contract Value </td>
												<td align="left" colspan="3">Rs. <?php echo IND_money_format($workcost); ?> </td>
											</tr>
											<tr class="label">
												<td align="left" colspan="1">4. Bill Value </td>
												<td align="left" colspan="3">Rs. <?php echo IND_money_format($NetTotal); $ChequeAmt = $NetTotal; ?> </td>
											</tr>
											
											<tr class="label">
												<td align="left" colspan="2">5. [A] Creadited to works</td>
												<td align="left" colspan="2">5. [B] Creadited to other works</td>
											</tr>
											<tr class="label">
												<td align="left">[1] Labour welfare cess </td>
												<td align="right" width="200px"><span style="float:left">Rs.</span><?php echo IND_money_format($lw_cess_amt); $ChequeAmt = $ChequeAmt - $lw_cess_amt; ?></td>
												<td align="left">[1] Income tax</td>
												<td align="right" width="200px"><span style="float:left">Rs.</span><?php echo IND_money_format($incometax_amt); $ChequeAmt = $ChequeAmt - $incometax_amt; ?></td>
											</tr>
											<tr class="label">
												<td align="left">[2] Secured Advance</td>
												<td align="right"><span style="float:left">Rs.</span><?php echo IND_money_format($SecuredAdvance); $ChequeAmt = $ChequeAmt - $SecuredAdvance; ?></td>
												<td align="left">[2] Interest on mobilization Advance</td>
												<td align="right"><span style="float:left">Rs.</span> 0.00</td>
											</tr>
											<tr class="label">
												<td align="left">[3] Ele. Charge</td>
												<td align="right"><span style="float:left">Rs.</span><?php echo IND_money_format($electricitycost); $ChequeAmt = $ChequeAmt - $electricitycost; ?></td>
												<td align="left">[3] Security Deposit</td>
												<td align="right"><span style="float:left">Rs.</span><?php echo IND_money_format($sd_amt); $ChequeAmt = $ChequeAmt - $sd_amt; ?></td>
											</tr>
											<tr class="label">
												<td align="left">[4] mobilization Advance Recovery </td>
												<td align="right"><span style="float:left">Rs.</span><?php echo IND_money_format($mob_adv_amt); $ChequeAmt = $ChequeAmt - $mob_adv_amt; ?></td>
												<td align="left">[4] CGST</td>
												<td align="right"><span style="float:left">Rs.</span><?php echo IND_money_format($cgst_amt); $ChequeAmt = $ChequeAmt - $cgst_amt; ?></td>
											</tr>
											<tr class="label">
												<td align="right">&nbsp;</td>
												<td align="right">&nbsp;</td>
												<td align="left">[5] SGST</td>
												<td align="right"><span style="float:left">Rs.</span><?php echo IND_money_format($sgst_amt);?></td>
											</tr>
											<tr class="label">
												<td align="left" colspan="2">5. [C]- By cheque :<br><br></td>
												<td align="right" colspan="2"><?php echo IND_money_format($ChequeAmt); ?></td>
											</tr>
											<tr class="label">
												<td colspan="4">
													<span style="float:left">kalpakkam - 603102 <br/> Date : </span>
													<span style="float:right">Asst.Accts. Officer<br> FRFCF, NRB, BARCF</span>
												</td>
											</tr>
											<tr class="label">
												<td align="left" colspan="4">CCODE : <?php echo $ccno; ?></td>
											</tr>
											<tr class="label">
												<td align="center" colspan="4">Accounts III  Coding Sheet</td>
											</tr>
											<tr class="label">
												<td align="center" colspan="4">Month : <?php echo date("F"); ?> - <?php echo date("Y"); ?> & Voucher No. :______________ & Date : ________________</td>
											</tr>
											<tr class="label">
												<td align="left" colspan="4">1. Name of Contractor :&nbsp;<?php echo $name_contractor; ?> </td>
											</tr>
										</table>
										
										<table width="100%" class="tableA" align="center">
											<tr class="label">
												<td align ="center">Particulars</td>
												<td align ="center">Project No.</td>
												<td align ="center">Budget wise Item</td>
												<td align ="right">Amount (Rs.)</td>
											</tr>
											<tr class="label">
												<td align ="left">Debitable to</td>
												<td align ="center"></td>
												<td align ="center"></td>
												<td align ="right"></td>
											</tr>
											<?php 
											if(count($SCodeRecArr)>0){ 
												foreach($SCodeRecArr as $SCodeRecKey => $SCodeRecValue){ 
													$SCodeId = $SCodeRecValue->shortcode_id;
													$SCodeHoaNo = "";
													$SelectQuery = "select new_hoa_no from hoa_master where (shortcode_id = '$SCodeId' OR shortcode_id LIKE '$SCodeId,%' OR shortcode_id LIKE '%,$SCodeId' OR shortcode_id LIKE '%,$SCodeId,%')";
													$SelectSql = mysqli_query($dbConn,$SelectQuery);
													if($SelectSql == true){
														if(mysqli_num_rows($SelectSql)>0){
															$SCodeHoaList = mysqli_fetch_object($SelectSql);
															$SCodeHoaNo = $SCodeHoaList->new_hoa_no;
														}
													}
											?>
											<tr class="label">
												<td align ="left"><?php echo $SCodeRecKey; ?></td>
												<td align ="center"><?php echo $SCodeRecValue->shortcode;  ?></td>
												<td align ="center"><?php echo $SCodeHoaNo; ?></td>
												<td align ="right"><?php echo IND_money_format($SCodeRecValue->rec_amt);  ?></td>
											</tr>
											<?php 
												} 
											}
											?>
											<!--<tr class="label">
												<td align ="left">SGST</td>
												<td align ="center"></td>
												<td align ="center"></td>
												<td align ="right"><?php echo $sgst_amt  ?></td>
											</tr>
											<tr class="label">
												<td align ="left">Income tax</td>
												<td align ="center"></td>
												<td align ="center"></td>
												<td align ="right"><?php echo $incometax_amt  ?></td>
											</tr>
											<tr class="label">
												<td align ="left">Surcharge on IT</td>
												<td align ="center"></td>
												<td align ="center"></td>
												<td align ="right"></td>
											</tr>
											<tr class="label">
												<td align ="left">Pr. CESS on IT & SC</td>
												<td align ="center"></td>
												<td align ="center"></td>
												<td align ="right"></td>
											</tr>
											<tr class="label">
												<td align ="left">Hr. CESS on IT & SC</td>
												<td align ="center"></td>
												<td align ="center"></td>
												<td align ="right"></td>
											</tr>
											<tr class="label">
												<td align ="left">Int. ON Mob. Adv</td>
												<td align ="center"></td>
												<td align ="center"></td>
												<td align ="right"></td>
											</tr>
											<tr class="label">
												<td align ="left">Elects. Charge</td>
												<td align ="center"></td>
												<td align ="center"></td>
												<td align ="right"><?php echo $electricitycost  ?></td>
											</tr>
											<tr class="label">
												<td align ="left">Security Deposit</td>
												<td align ="center"></td>
												<td align ="center"></td>
												<td align ="right"><?php echo $sd_amt ?></td>
											</tr>-->
										</table>
										<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>"> 
										<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>"> 
									</div>
									</div>
								</div>
					   		</div>
							</div>
							<div class="1">&nbsp;</div>
						   </div>
       					</form>
      				</blockquote>
    			</div>	
   			</div>
		</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<style>
	.timeline__post {
		-webkit-box-shadow: 0 0px 0px 0 rgba(0, 0, 0, .12), 0 0px 1px 0 rgba(0, 0, 0, .24);
		box-shadow: 0 0px 0px 0 rgba(0, 0, 0, .12), 0 0px 1px 0 rgba(0, 0, 0, .24);
	}
	.timeline__post{
		margin-bottom:3px;
	}
	select, textarea, input[type="text"]{
		margin-bottom:1px;
		color:#0000CC;
	}
</style>
<script>
$(function() {
	$("#btn_previous1").click(function(){
		var sheetid = $('#txt_sheetid').val();
		var rbn = $('#txt_rbn').val();
		$(location).attr('href', "AccountsGenerateSection2.php?sheetid="+sheetid+"&rbn="+rbn)
	});
	$("#btn_previous2").click(function(){
		var sheetid = $('#txt_sheetid').val();
		var rbn = $('#txt_rbn').val();
		$(location).attr('href', "AccountsGenerateSection2.php?sheetid="+sheetid+"&rbn="+rbn)
	});	
});
</script>
<script>
	$("#cmb_shortname").chosen();
	//$("#cmb_rbn").chosen();
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	document.querySelector('#top').onload = function(){
		if(msg != "")
		{
			if(success == 1)
			{
				swal("", msg, "success");
			}
			else
			{
				swal(msg, "", "");
			}
						
		}
	};
</script>
</body>
</html>

