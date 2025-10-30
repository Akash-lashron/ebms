<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
include "sysdate.php";
$msg = '';
$_SESSION["newmbookno"]='';
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
$popupwindow =0;
if($_POST["btn_next"] == "Next") 
{
    $staffid 	= $_SESSION['sid'];
    $mb_date 	= dt_format($_POST['txt_date']);
    $fromdate 	= dt_format($_POST['txt_fromdate']);
    $todate 	= dt_format($_POST['txt_todate']);
	$zone_id 	= $_POST['cmb_zone_name'];
    $mb_no 		= trim($_POST['currentmbook']);
	$mb_page	= trim($_POST['bookpageno']);
    $count 		= trim($_POST['count']);
	$mbno_id 	= trim($_POST['currentmbookno']);
	$_SESSION["mbno_id"] 	= $mbno_id;
	$_SESSION["fromdate"] 	= $fromdate;
	$_SESSION["todate"] 	= $todate;      		
	$_SESSION["mb_no"] 		= $mb_no;             
	$_SESSION["mb_page"] 	= $mb_page;
	$_SESSION["zone_id"] 	= $zone_id;
	//echo $fromdate;exit;
	header('Location: MBook_Staff_Wise.php?varid=1');
} 

if(isset($_SESSION['sheet_id']) && !empty($_SESSION['sheet_id']) && isset($_SESSION['rbn']) && !empty($_SESSION['rbn'])){
	$sheetid = $_SESSION['sheet_id']; //print $sheetid;
	$rbn = $_SESSION['rbn'];
	$select_work_query = "select * from sheet where sheet_id = '$sheetid'";
	$select_work_sql = mysql_query($select_work_query);
	if($select_work_sql == true){
		if(mysql_num_rows($select_work_sql)>0){
			$SheetList 		= mysql_fetch_object($select_work_sql);
			$WorkName 		= $SheetList->work_name;
			$WorkShortName 	= $SheetList->short_name;
			$WorkOrderNo 	= $SheetList->work_order_no;
			$ContractorName = $SheetList->name_contractor;
			$CCNo 			= $SheetList->computer_code_no;
		}
	}
	$MaxDate = $_SESSION["MaxdateDB"];
	//$rbn = 8;
	$MCount1 = 0;
	$select_meas_query1 = "select COUNT(a.mbheaderid) as meas_count1 from mbookheader a inner join schdule b on (a.subdivid = b.subdiv_id) where a.sheetid = '$sheetid' and b.sheet_id = '$sheetid' and b.measure_type != 's' and a.date > '$MaxDate' and a.staffid =".$_SESSION['sid'];
	$select_meas_sql1 	= mysql_query($select_meas_query1);
	if($select_meas_sql1 == true){
		$CntList1 = mysql_fetch_object($select_meas_sql1);
		$MCount1 = $CntList1->meas_count1;
	}
	
	$MCount2 = 0;
	$select_meas_query2	= "select COUNT(mbgenerateid) as meas_count2 from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$rbn' and flag = 1 and staffid =".$_SESSION['sid'];
	$select_meas_sql2 	= mysql_query($select_meas_query2);
	if($select_meas_sql2 == true){
		$CntList2 = mysql_fetch_object($select_meas_sql2);
		$MCount2  = $CntList2->meas_count2;
	}
	
	if(($MCount1 > 0)&&($MCount2 == 0)){
		$skip = "N";
	}else{
		$skip = "Y";
	}
	//echo $MCount1;exit;
	
	$SelectDatesQuery	= "select fromdate, todate from abstractbook where sheetid='$sheetid' and rbn = '$rbn'";
	$SelectDatesSql		= mysql_query($SelectDatesQuery);
	if($SelectDatesSql == true){
		if(mysql_num_rows($SelectDatesSql)>0){
			$DateList = mysql_fetch_object($SelectDatesSql);
			$fromdate = dt_display($DateList->fromdate);
			$todate = dt_display($DateList->todate);
		}
	}
}else{
	$sheetid = "";
	$rbn = "";
}
?>
<?php require_once "Header.html"; ?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack(){
	   	url = "PageGenerate.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack(){ 
		window.history.forward(); 
	}
	$(function(){
		/*$( "#txt_fromdate" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd/mm/yy",
			yearRange: "2010:+15",
			maxDate: new Date,
			defaultDate: new Date,
		});
		$( "#txt_todate" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd/mm/yy",
			yearRange: "2010:+15",
			defaultDate: new Date,
		});*/
		$.fn.validatefromdate = function(event) { 
			if($("#txt_fromdate").val()=="")
			{ 
				var a="Please Select From Date";
				$('#val_fromdate').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				var a="";
				$('#val_fromdate').text(a);
			}
		}
		$.fn.validatetodate = function(event) { 
			if($("#txt_todate").val()=="")
			{ 
				var a="Please Select To Date";
				$('#val_todate').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				var a="";
				$('#val_todate').text(a);
			}
		}
		$.fn.validatembno = function(event) { 
			if($("#currentmbookno").val()=="")
			{ 
				var a="Please Select MBook No.";
				$('#val_mbook').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				var a="";
				$('#val_mbook').text(a);
			}
		}
		$.fn.validatembpage = function(event) { 
			if($("#bookpageno1").val()=="")
			{ 
				var a="Invalid MBook Page No.";
				$('#val_mbookpage').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				var a="";
				$('#val_mbookpage').text(a);
			}
		}
		
		$.fn.validatefromtodate = function(event) { 
			var from = $("#txt_fromdate").val();
			var to = $("#txt_todate").val();
			var d1 = from.split("/");
			var d2 = to.split("/");
			var fromdate = new Date(d1[2], d1[1]-1, d1[0]);
			var todate = new Date(d2[2], d2[1]-1, d2[0]);
			if(fromdate>todate)
			{
				var a="From date should be less than To date \n";
				$('#val_date').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				var a="";
				$('#val_date').text(a);
			}
		}
		
		$.fn.validateMeasurements = function(event) { 
			if(($("#txt_fromdate").val()!="") && ($("#txt_todate").val()!=""))
			{ 
				//alert();
				checkmeasurement();
			}
			else
			{
				event.preventDefault();
				event.returnValue = false;
			}
		}
		
		$.fn.checkdate = function(event) { 
			//var wordordernovalue = $("#txt_sheetid").val();//document.form.wordorderno.value;
			var maxdate = $("#hid_maxdate").val();
			var fromdate = $("#txt_fromdate").val();
			var dt1 = maxdate.split("/");
			var dt2 = fromdate.split("/");
			var max_date = new Date(dt1[2], dt1[1]-1, dt1[0]);  // -1 because months are from 0 to 11
			var from_date   = new Date(dt2[2], dt2[1]-1, dt2[0]);
			if(max_date>from_date)
			{
				var a="Already measurement generated for this date\n";
				$('#check_date2').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				var a="";
				$('#check_date2').text(a);
			}
		}
		
		$("#txt_fromdate").change(function(event){
			$(this).validatefromdate(event);
			$(this).validatefromtodate(event);
			$(this).validateMeasurements(event);
			$(this).checkdate(event);
		});
		
		$("#txt_todate").change(function(event){
			$(this).validatetodate(event);
			$(this).validatefromtodate(event);
			$(this).validateMeasurements(event);
			$(this).checkdate(event);
		});
		
		$("#currentmbookno").change(function(event){
			$(this).validatembno(event);
		});	
		
		
		$("#top").submit(function(event){
			$(this).validatefromdate(event);
			$(this).validatetodate(event);
			$(this).validatembno(event);
			$(this).validatembpage(event);
			$(this).validatefromtodate(event);
			$(this).validateMeasurements(event);
			$(this).checkdate(event);
		});
	});
</script>

<script src="stepWizard/bootstrap.min.js"></script>
<!--<script src="stepWizard/jquery.wizard.js"></script>-->
<script>
var page = "MBKG";
var page_url = "MBookGenerateSection2.php";
	/*
 * jQuery / jqLite Wizard Plugin
 * version: 0.0.7
 * Author: Girolamo Tomaselli http://bygiro.com
 *
 * Copyright (c) 2013 G. Tomaselli
 * Licensed under the MIT license.
 */

// compatibility for jQuery / jqLite
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
				
				
				html += '<span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-prev"><span class="previous-text">'+ opts.text.previous +'</span></span>';
				html += '<span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-next"><input type="submit" name="btn_next" id="btn_next1" value="Next" style="background:none; border:none; padding:0px; box-shadow:none; height:16px; margin: 5px;"><span class="finished-text">'+ opts.text.finished +'</span></span>';
				
				html += '</div></div>';
				
				stepsBar.after(html);
			}
			
			html = '';
			if(opts.bottomButtons && !bottomActions.length){
				html += '<div class="bottom-actions">';
				//html += '<div class="left-actions"><span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-prev"><span class="previous-text">'+ opts.text.previous +'</span></span></div>';
				//html += '<div class="right-actions"><span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-next"><input type="submit" name="btn_next" id="btn_next2" value="Next" style="background:none; border:none; padding:0px; box-shadow:none; height:25px;"><span class="finished-text">'+ opts.text.finished +'</span></span><input type="button" class="backbutton" name="btn_skip" id="btn_skip" value="Skip >">&nbsp;</div>';
				html += '<div class="right-actions"><input type="button" class="backbutton" name="btn_skip" id="btn_skip" value="Skip >">&nbsp;<br/>&nbsp;</div>';
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
	var workorderno = document.form.txt_sheetid.value;
	var fromdate 	= document.form.txt_fromdate.value;
	var todate 		= document.form.txt_todate.value;
	var final_bill 	= document.form.hid_final_bill.value;
	var mcount1 	= document.form.hid_mcount1.value;
	//alert(document.form.hid_final_bill.value);
	//alert(document.form.hid_mcount1.value);
	var measure_type 	= "G";//document.form.rad_measurementtype.value;
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
						if((final_bill == 'Y')&&(mcount1 == 0)){
							document.getElementById("btn_next1").disabled = false;
							BootstrapDialog.alert("No measurements available for this period. Click OK to generate Completion Certifiate");
						}else{
							document.getElementById("btn_next1").disabled = true;
							BootstrapDialog.alert("No measurements available for this period");
						}
						//document.getElementById("btn_next2").disabled = true;alert();
						document.getElementById("check_date").innerHTML = "No measurements available for this period \n";
						return false;
					}
					else
					{
						document.getElementById("btn_next1").disabled = false;
						//document.getElementById("btn_next2").disabled = false;
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
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<script src="dashboard/MyView/bootstrap.min.js"></script>
<link href="stepWizard/jquery.wizard.css" rel="stylesheet">
<link href="css/pager.css" rel="stylesheet">
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <link rel="stylesheet" href="css/timeline.css">
  <!--==============================Content=================================-->
	<div class="content">
    	<div class="title">General MBook Generation</div>
        <div class="container_12">
        	<div class="grid_12">
            	<blockquote class="bq1" style="">
                	<form name="form" method="post" action="">
                    	<div class="container">
							
							<div class="container-fluid">
								<div data-wizard-init>
								  <ul class="steps">
									<li data-step="1">MBook - General</li>
									<li data-step="2">MBook - Steel</li>
									<li data-step="3">Sub Abstract</li>
									<li data-step="4">Abstract</li>
								  </ul>
								  <div class="steps-content" align="center">
									<div data-step="1" class="mb-sec">
										
										<div class="timeline__post">
											<div class="timeline__content">
												<p>Name of Work<!--<span class='badge'>Name of Work</span>-->&nbsp; : <?php echo $WorkShortName; ?></p>
											 </div>
										</div>
										<!--<div class="timeline__post">
											<div class="timeline__content">
												<p><span class='badge'>Work Order No</span> : <?php echo $WorkOrderNo; ?></p>
											 </div>
										</div>-->
										<div class="timeline__post">
											<div class="timeline__content">
												<p>RAB<!--<span class='badge'>RAB</span>-->&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;: <?php echo $rbn; ?></p>
											 </div>
										</div>
									<?php 
									$WorkMBType = "NZW";
									$SelectWorkZoneQuery = "select zone_id from zone where sheetid = '$sheetid'";
									$SelectWorkZoneSql = mysql_query($SelectWorkZoneQuery);
									if($SelectWorkZoneSql == true){
										if(mysql_num_rows($SelectWorkZoneSql)>0){
											$WorkMBType = "ZW";
										}
									}
									
									$ZoneArr = array(); $MBDetArr = array(); $TotalMeasZoneArr = array(); $TotalGenZoneArr = array();
									$SelectGenListQuery1 = "select a.zone_id, b.decimal_placed, b.per, GROUP_CONCAT(CAST(a.mbtotal AS CHAR(15)) SEPARATOR '*') as itemqty, 
															GROUP_CONCAT(CAST(b.sno AS CHAR(15)) order by b.subdiv_id asc SEPARATOR '*') as itemno, 
															GROUP_CONCAT(CAST(a.subdivid AS CHAR(15)) order by b.subdiv_id asc SEPARATOR '*') as itemid
															from mbookgenerate_staff a inner join schdule b on (a.subdivid = b.subdiv_id) where a.sheetid = '$sheetid' and 
															a.rbn = '$rbn' and a.flag = 1 and b.sheet_id = '$sheetid' group by a.zone_id order by a.subdivid asc";
									$SelectGenListSql1 = mysql_query($SelectGenListQuery1);
									if($SelectGenListSql1 == true){
										if(mysql_num_rows($SelectGenListSql1)>0){
											while($ZloneList1 = mysql_fetch_object($SelectGenListSql1)){
												//echo $ZloneList1->itemno."@@@".$ZloneList1->itemqty."@@@".$ZloneList1->per."@@@".$ZloneList1->zone_id."<br/>";
												array_push($ZoneArr,$ZloneList1->zone_id);
												$MBDetArr[$ZloneList1->zone_id][0] = $ZloneList1->itemno;
												$MBDetArr[$ZloneList1->zone_id][1] = $ZloneList1->itemqty;
												$MBDetArr[$ZloneList1->zone_id][2] = $ZloneList1->per;
												$MBDetArr[$ZloneList1->zone_id][3] = $ZloneList1->itemid;
												if(in_array($ZloneList1->zone_id,$TotalGenZoneArr)){
													/// ALready Exist So Don't push
												}else{
													array_push($TotalGenZoneArr,$ZloneList1->zone_id);
												}
											}
										}
									}						
															
									//print_r($MBDetArr);						
									//echo $SelectGenListQuery1;
									/*$SelectGenListQuery = "select distinct zone_id from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$rbn' and flag = 1";
									$SelectGenListSql = mysql_query($SelectGenListQuery);
									if($SelectGenListSql == true){
										if(mysql_num_rows($SelectGenListSql)>0){
											while($ZloneList = mysql_fetch_object($SelectGenListSql)){
												array_push($ZoneArr,$ZloneList->zone_id);
											}
										}
									}*/
									$MeasZoneArr = array();
									$SelectZoneQuery = "select distinct a.zone_id, b.zone_name from mbookheader a left join zone b on (a.zone_id = b.zone_id) inner join schdule c on (a.subdivid = c.subdiv_id) where a.date > '$MaxDate' and a.sheetid = '$sheetid' and c.sheet_id = '$sheetid' and c.measure_type != 's' order by b.zone_name asc";
									$SelectZoneSql = mysql_query($SelectZoneQuery);
									if($SelectZoneSql == true){
										if(mysql_num_rows($SelectZoneSql)>0){
									?>
										<div class="timeline__post">
											<div class="timeline__content" style="text-align:left">
									<?php
											while($ZList = mysql_fetch_object($SelectZoneSql)){
												if(in_array($ZList->zone_id,$ZoneArr)){
													$CheckColor = "#00BBBB";
													$CheckStr = 'check';
												}else{
													$CheckColor = "red";
													$CheckStr = 'times';
												}
												if(($ZList->zone_id == 0)&&($ZList->zone_name == "")){
													$ZoneName = "General MBook";
												}else{
													$ZoneName = $ZList->zone_name;
												}
												$MeasZoneArr[$ZList->zone_id] = $ZList->zone_name;
												
												$GenItemNoStr 		= $MBDetArr[$ZList->zone_id][0];
												$GenItemQtyStr 		= $MBDetArr[$ZList->zone_id][1];
												$GenItemUnit		= $MBDetArr[$ZList->zone_id][2];
												$GenItemId 			= $MBDetArr[$ZList->zone_id][3];
												$ExpGenItemNoStr 	= explode("*",$GenItemNoStr);
												$ExpGenItemQtyStr 	= explode("*",$GenItemQtyStr);
												$GenItemNoCnt = count($ExpGenItemNoStr);
												//echo $ZList->zone_id." = ".$GenItemNoStr."<br/>";
												$TitleStr = "<div><table class='tooltiptable table table-bordered ttiptable' style='margin-bottom: 0px;'><tr><td>Item No.</td><td>Item Qty.</td></tr>";
												if($GenItemNoStr != ""){
													for($x1=0; $x1<$GenItemNoCnt; $x1++){
														$GenItemNo 	= $ExpGenItemNoStr[$x1];
														$GenItemQty = $ExpGenItemQtyStr[$x1];
														$TitleStr .= "<tr><td style='text-align:left'>".$GenItemNo."</td><td>".$GenItemQty." ".$GenItemUnit."</td> </tr>";
													}
												}else{
														$TitleStr .= "<tr><td style='text-align:center' colspan='2'>MBook Not Generated</td></tr>";
												}
												$TitleStr .= "</table></div>";
												if(in_array($ZList->zone_id,$TotalMeasZoneArr)){
													/// ALready Exist So Don't push
												}else{
													array_push($TotalMeasZoneArr,$ZList->zone_id);
												}
									?>
												<button type="button" class="btnA btn-defaultA btn-smA ptr tooltip-new"> <span class="tooltiptext"><?php echo $TitleStr; ?></span>
												  <?php echo $ZoneName; ?>&emsp; <i class="fa fa-<?php echo $CheckStr; ?>-circle" style="font-size:20px; color:<?php echo $CheckColor; ?>"></i>
												</button>
									<?php			
											}
									?>
											 </div>
										</div>
									<?php	
										}
									}
									if(count($MeasZoneArr) == 0){
									?>
										<div class="timeline__post">
											<div class="timeline__content">
												<button type="button" class="btnA btn-defaultA btn-smA" style="color:red">
													<i class="fa fa-times-circle" style="font-size:20px; color:red"></i>&emsp;General Measurements Not Uploaded
												</button>
											</div>
										</div>
									<?php
									}
									$MeasZoneCnt = count($MeasZoneArr);
									$GeneZoneCnt = count($ZoneArr);
									if($MeasZoneCnt == $GeneZoneCnt){
										$skip = "Y";
									}else{
										$skip = "N";
									}
									/*if(($MeasZoneCnt == 1)&&($MeasZoneArr[0] == 0)){
										$WorkMBType = "NZW";// None Zone Wise
									}else{
										$WorkMBType = "ZW";// Zone Wise
									}*/
									$NotGenerate = abs($MeasZoneCnt - $GeneZoneCnt);
									//echo $MeasZoneCnt."<br/>";
									//echo $answer."<br/>";
									$_SESSION['GGenerate'] = $MeasZoneCnt;
									$_SESSION['NotGenerate'] = $NotGenerate;
									//print_r($MeasZoneArr);
									//print_r($ZoneArr); 
									//print_r($TotalMeasZoneArr);echo "<br/>";
									//print_r($TotalGenZoneArr);
									$_SESSION['GenTotalMeasZoneArr'] = $TotalMeasZoneArr;
									$_SESSION['GenTotalGenZoneArr'] = $TotalGenZoneArr;
									?>
										<div class="timeline__post" style="background:none;">
											<!--<div class="timeline__content" style="width:40%">
												From Date
											 </div>-->
											 
											 <table width="100%">
											 	<tr>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>
												<?php if($WorkMBType == "ZW"){ ?>
											 	<tr>
													<td class="labelbold" nowrap="nowrap">Zone Name&emsp;&emsp;</td>
													<td colspan="3">
														<select name='cmb_zone_name' id='cmb_zone_name' class="textboxdisplay" style="width:102%;">
															<option value=""> --------------------------- Select Zone Name ----------------------------- </option>
															<?php foreach ($MeasZoneArr as $ZoneId => $ZoneName){ ?>
															<option value="<?php echo $ZoneId; ?>"><?php echo $ZoneName; ?></option>
															<?php } ?>
														</select>
													</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>
												<?php } ?>
											 	<tr>
													<td class="labelbold">From Date</td>
													<td>
														<input type="text" name="txt_fromdate" id="txt_fromdate" class="textboxdisplay" readonly="" onChange="return ValidateForm('txt_fromdate');" value="<?php echo $fromdate; ?>"/>
													</td>
													<td class="labelbold">To Date</td>
													<td>
														<input type="text" name="txt_todate" id="txt_todate" class="textboxdisplay" readonly="" onChange="return ValidateForm('txt_todate');" value="<?php echo $todate; ?>"/>
													</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td id="val_fromdate" style="color:red"></td>
													<td>&nbsp;</td>
													<td id="val_todate" style="color:red"></td>
												</tr>
												<tr>
													<td class="labelbold">MBook No</td>
													<td>
														<select name="currentmbookno" id="currentmbookno" class="labeldisplay">
															<option value="">---------- Select----------</option>
															<?php
															$select_mbno_query = "select a.mbno, a.mbpage, a.allotmentid, b.mbooktype from mbookallotment a 
																				inner join agreementmbookallotment b on (a.allotmentid = b.allotmentid)
																				where a.sheetid = '$sheetid' and b.sheetid = '$sheetid' and a.staffid = '$staffid' 
																				and a.active = 1 and b.mbooktype = 'G'";
															$select_mbno_sql = mysql_query($select_mbno_query);
															if($select_mbno_sql == true){
																if(mysql_num_rows($select_mbno_sql)>0){
																	while($List = mysql_fetch_object($select_mbno_sql)){
																		$mbno 			= $List->mbno;
																		$allotmentid 	= $List->allotmentid;
																		$mbpage 		= $List->mbpage;
																		$mbpage 		= $mbpage+1;
																		echo '<option value="'.$allotmentid.'" data-page="'.$mbpage.'">'.$mbno.'</option>';
																	}
																}
															}
															?>
														</select>
													</td>
													<td class="labelbold">MBook Page</td>
													<td>
														<input type="hidden" name="currentmbook" id="currentmbook" />
														<input type="text" name="bookpageno1" id="bookpageno1" class="textboxdisplay labeldisplay" readonly=""/>
														<input type="hidden" name="bookpageno" id="bookpageno" />
														<input type="hidden" name="count" id="count" />
														
														<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>">
														<input type="hidden" name="txt_staffid" id="txt_staffid" value="<?php echo $staffid; ?>">
														<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>">
														<input type="hidden" name="hid_maxdate" id="hid_maxdate" value="<?php echo $_SESSION["MaxdateDPL"]; ?>">
														<input type="hidden" name="hid_skip" id="hid_skip" value="<?php echo $skip; ?>">
														<input type="hidden" name="hid_final_bill" id="hid_final_bill" value="<?php echo $_SESSION["final_bill"]; ?>">
														
														<input type="hidden" name="hid_mcount1" id="hid_mcount1" value="<?php echo $MCount1; ?>">
														<input type="hidden" name="hid_mcount2" id="hid_mcount2" value="<?php echo $MCount2; ?>">
														
													</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td id="val_mbook" style="color:red">&nbsp;</td>
													<td>&nbsp;</td>
													<td id="val_mbookpage" style="color:red">&nbsp;</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td colspan="3">
													<span id="val_date" style="color:red"></span>
													<span id="check_date" style="color:red"></span>
													<span id="check_date2" style="color:red"></span>
													</td>
												</tr>
											 </table>
											 
											 
											<!-- <div style="text-align:center;" class="printbutton">
												<div class="buttonsection labelbold" style="width:150px">
													From Date
												</div>
												<div class="buttonsection" style="width:150px">
													<input type="text" name="txt_fromdate" id="txt_fromdate" class="textboxdisplay labeldisplay">
												</div>
												<div class="buttonsection labelbold" style="width:150px">
													To Date
												</div>
												<div class="buttonsection labeldisplay" style="width:150px">
													<input type="text" name="txt_todate" id="txt_todate" class="textboxdisplay labeldisplay">
												</div>
											</div>-->
											
											 <!--<div style="text-align:center;" class="printbutton">
												<div class="buttonsection labelbold" style="width:150px">
													MBook No
												</div>
												<div class="buttonsection" style="width:150px">
													<select name="currentmbookno" id="currentmbookno" class="labeldisplay">
														<option value="">---------- Select----------</option>
														<?php
														/*$select_mbno_query = "select a.mbno, a.allotmentid, b.mbooktype from mbookallotment a 
																			inner join agreementmbookallotment b on (a.allotmentid = b.allotmentid)
																			where a.sheetid = '$sheetid' and b.sheetid = '$sheetid' and a.staffid = '$staffid' 
																			and a.active = 1 and b.mbooktype = 'G'";
														$select_mbno_sql = mysql_query($select_mbno_query);
														if($select_mbno_sql == true){
															if(mysql_num_rows($select_mbno_sql)>0){
																while($List = mysql_fetch_object($select_mbno_sql)){
																	$mbno 			= $List->mbno;
																	$allotmentid 	= $List->allotmentid;
																	echo '<option value="'.$allotmentid.'">'.$mbno.'</option>';
																}
															}
														}*/
														?>
													</select>
												</div>
												<div class="buttonsection labelbold"  style="width:150px">
													&emsp;MBook Page
												</div>
												<div class="buttonsection labeldisplay" style="width:150px">
													<input type="hidden" name="currentmbook" id="currentmbook" />
													<input type="text" name="bookpageno1" id="bookpageno1" class="textboxdisplay labeldisplay" readonly=""/>
													<input type="hidden" name="bookpageno" id="bookpageno" />
													<input type="hidden" name="count" id="count" />
													
													<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>">
													<input type="hidden" name="txt_staffid" id="txt_staffid" value="<?php echo $staffid; ?>">
													<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>">
												</div>
											</div>-->
										</div>
									</div>
									<!--<div data-step="2">
									</div>
									<div data-step="3">
									</div>-->
								  </div>
								</div>
							</div>
                 
     					</div>
						<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
							<div class="buttonsection">
								<!--<input type="hidden" name="txt_page" id="txt_page" value="0">-->
								<!--<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />-->
							</div>
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
		/*border:1px solid #ABD1E8;
		background:#F6F7F7;*/
		border:1px solid #0078F0;
		background:#F4F4F4;
	}
	.timeline__post{
		margin-bottom:3px;
	}
	select, textarea, input[type="text"]{
		margin-bottom:1px;
		color:#0000CC;
		width:95%;
	}
	.sweet-alert h2{
		line-height:25px;
	}
	.sweet-alert p{
		line-height:25px;
		font-weight:bold;
	}
	
.btnA, .btn-smA {

    padding: 5px 10px;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 3px;

}
btn-defaultA {

    color: #333;
    background-color: #fff;
    border-color: #ccc;

}
.btnA {

    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: auto;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
        border-top-color: transparent;
        border-right-color: transparent;
        border-bottom-color: transparent;
        border-left-color: transparent;
    border-radius: 4px;
	margin-left:0px;
	margin-right:10px;
	margin-bottom:4px;
	color:#005EBB;
	background:#fff;
	border:2px solid #1babd3;
}


.tooltip-new {
    position: relative;
    display: inline-block;
    /*border-bottom: 1px dotted black;*/
}

.tooltip-new .tooltiptext {
    /*visibility: hidden;*/
	display:none;
    width:auto;
    background-color:#000;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding:10px;
    position: absolute;
    z-index: 1;
    top: 125%;
    left: 6%;
    margin-left: -60px;
}

.tooltip-new .tooltiptext::after {
    content: "";
    position: absolute;
    bottom: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent transparent black transparent;
}

.tooltip-new:hover .tooltiptext {
    visibility: visible;  display:block;
	letter-spacing:1.1px;
}
</style>
<script>
$(function() {
	$.fn.validateworkorder = function(event) { 
		if($("#cmb_shortname").val()==""){ 
			var a="Please select the work order number";
			$('#val_work').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			var a="";
			$('#val_work').text(a);
		}
	}
	$("#top").submit(function(event){
		$(this).validateworkorder(event);
	});
	$("#cmb_shortname").change(function(event){
    	$(this).validateworkorder(event);
    });
			
	//$(".next-text").click(function(){
		//var target = $(this).parent().attr('data-step');
		//var stepsLi = this.$element.find('.steps > li');
		//alert();
	//});
	/*$(".btn-next").click(function(){
		//$(location).attr('href', page_url)
		$("form[name='form']").submit();
	});*/
	$("#btn_skip").click(function(){
		//alert("Are you sure want to skip from General MBook and go to Steel MBook..?");
		//$(location).attr('href', "MBookGenerateSection2.php")
		var skip 		= $("#hid_skip").val();
		var final_bill 	= $("#hid_final_bill").val();
		var mcount1 	= $("#hid_mcount1").val();
		var mcount2 	= $("#hid_mcount2").val();
		if(skip == 'N'){
			BootstrapDialog.alert("General Measurement Exist for this RAB. You can't able to skip !", "", "");
			event.preventDefault();
			event.returnValue = false;
		}else{
			if(final_bill == "Y"){
				if(mcount1 == 0){
					var alertTitle = "This is Your Final Bill, You need to generate Completion Certifiate. You can't able to skip !";
					var alertText = "Click OK to generate Completion Certificate";
				}else{
					var alertTitle = "Are you sure want to skip from General MBook and go to Steel MBook..?";
					var alertText = "";
				}
				
				/*swal({
					title: alertTitle,
					text: alertText,
					type: "",
					showCancelButton: false,
					confirmButtonColor: '#026CE3',
					confirmButtonText: 'OK',
					closeOnConfirm: true,
				 },
				 function(isConfirm){
					if(isConfirm){
						if(mcount1 == 0){
							var nextUrl = "MBook_Staff_Wise.php?varid=1";
							$(location).attr('href', nextUrl)
						}else{
							$("input[name = 'btn_next']").trigger( "click" );
						}
					}
				});*/
				BootstrapDialog.confirm(alertTitle, function(result){
					if(result){
						if(mcount1 == 0){
							var nextUrl = "MBook_Staff_Wise.php?varid=1";
							$(location).attr('href', nextUrl)
						}else{
							//$("input[name = 'btn_next']").trigger( "click" );
							var nextUrl = "MBookGenerateSection2.php";
							$(location).attr('href', nextUrl)
						}
					}
				});
				
				
			}else{
				 /*swal({
					title: "Are you sure?",
					text: "Are you sure want to skip from General MBook and go to Steel MBook..?",
					type: "",
					showCancelButton: true,
					confirmButtonColor: '#026CE3',
					 cancelButtonColor: "#DD6B55",
					confirmButtonText: 'Yes, I am sure!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: true
				 },
				 function(isConfirm){
					if(isConfirm){
						var nextUrl = "MBookGenerateSection2.php";
						$(location).attr('href', nextUrl)
					}
				});*/
				BootstrapDialog.confirm('Are you sure want to skip from General MBook and go to Steel MBook..?', function(result){
					if(result){
						var nextUrl = "MBookGenerateSection2.php";
						$(location).attr('href', nextUrl)
					}
				});
				
				
			}
		}
		
	});
			
			
			
	function DisplayPageDetails() {
		var currentmbooknovalue 	= 	$("#currentmbookno option:selected").attr('value');
		var currentmbooknotext 		= 	$("#currentmbookno option:selected").text();
		var sheetid 				= 	$("#txt_sheetid").val();//$("#wordorderno option:selected").attr('value');
		var staffid					=	$("#txt_staffid").val();
		var currentrbn				=	$("#txt_rbn").val();
		var generatetype 			= 	"sw";
		$("#bookpageno1").val('');
		$("#bookpageno").val('');
		$("#currentmbook").val('');
		$.post("MBookNoService.php", {currentmbook: currentmbooknovalue, currentbmookname: currentmbooknotext, sheetid: sheetid, generatetype: generatetype, staffid: staffid, currentrbn: currentrbn}, function (data) {
			//$("#bookpageno1").val(Number(data) + 1);$("#bookpageno").val(Number(data) + 1);
			if(currentmbooknovalue != ""){
				$("#bookpageno1").val(data);
				$("#bookpageno").val(data);
				$("#currentmbook").val(currentmbooknotext);
			} 
					
		});
	}
	/*function DisplayAbsPageDetails() {
		var currentmbooknoabsvalue 	= $("#currentmbookno_abs option:selected").attr('value');//alert(currentmbooknovalue);
		var currentmbooknoabstext 	= $("#currentmbookno_abs option:selected").text();
		$.post("MBookNoService.php", {currentmbook: currentmbooknoabsvalue}, function (data) { //alert(data);
			//$("#bookpageno_abs_1").val(Number(data) + 1);$("#bookpageno_abs").val(Number(data) + 1);
			$("#bookpageno_abs_1").val(data);$("#bookpageno_abs").val(data);
			$("#currentmbook_abs").val(currentmbooknoabstext); 
					
		});
	}*/
	$("#currentmbookno").bind("change", function () {   
		//DisplayPageDetails();
		$("#bookpageno1").val('');
		$("#bookpageno").val('');
		$("#currentmbook").val('');
		var currentmbooknovalue = $("#currentmbookno option:selected").attr('value');
		var currentmbooknotext 	= $("#currentmbookno option:selected").text();
		var currentmbpageno 	= $("#currentmbookno option:selected").attr('data-page');
		$("#bookpageno1").val(currentmbpageno);
		$("#bookpageno").val(currentmbpageno);
		$("#currentmbook").val(currentmbooknotext);
	});
	/*$("#currentmbookno_abs").bind("change", function () {   
		DisplayAbsPageDetails();
	});*/
});
</script>
<script>
	$("#cmb_zone_name").chosen();
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

