<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
include "spellnumber.php";
checkUser();
$msg = ''; $Line = 0;
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
if($_POST["btn_next"] == "Complete") {
	if(isset($_SESSION['sheet_id']) && !empty($_SESSION['sheet_id']) && isset($_SESSION['rbn']) && !empty($_SESSION['rbn'])){
    	header('Location: AbstMBook_Partpay.php'); 
	}
}
	
if(isset($_SESSION['sheet_id']) && !empty($_SESSION['sheet_id']) && isset($_SESSION['rbn']) && !empty($_SESSION['rbn'])){
	$sheetid 	= $_SESSION['sheet_id'];
	$rbn 		= $_SESSION['rbn'];
    $_SESSION["abstsheetid"] 	= $sheetid; 
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
	/*$select_fdate_query = "select date(min(fromdate)) as fdate from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_fdate_sql 	= mysql_query($select_fdate_query);
	if($select_fdate_sql == true)
	{
		if(mysql_num_rows($select_fdate_sql)>0)
		{
			$FList 		= mysql_fetch_object($select_fdate_sql);
			$fdate 		= $FList->fdate;
			$fromdate 	= date('d/m/Y',strtotime($fdate));
			$_SESSION['fromdate'] 	= date($fdate); 
		}
	}
	$select_tdate_query = "select date(max(todate)) as tdate from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_tdate_sql 	= mysql_query($select_tdate_query);
	if($select_tdate_sql == true)
	{
		if(mysql_num_rows($select_tdate_sql)>0)
		{
			$TList 	= mysql_fetch_object($select_tdate_sql);
			$tdate 	= $TList->tdate;
			$todate = date('d/m/Y',strtotime($tdate));
			$_SESSION['todate'] 	= date($tdate);
		}
	}*/
	
	$abstarctmbook_query 	= "select DISTINCT abstmbookno from mbookgenerate WHERE sheetid = '$sheetid'";
	$abstarctmbook_sql 		= mysql_query($abstarctmbook_query);
	if($abstarctmbook_sql == true)
	{
		if(mysql_num_rows($abstarctmbook_sql)>0)
		{
			$ABSList1 			= mysql_fetch_object($abstarctmbook_sql);
			$abstractmbookno 	= $ABSList1->abstmbookno;
			$_SESSION["abs_mbno"] 	= $abstractmbookno;
		}
	}
	
	if(($rbn != "") && ($abstractmbookno != ""))
	{
		$abstractmbookpage_query 	= "select mbpage, allotmentid, mbookmode from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$abstractmbookno'";
		$abstractmbookpage_sql 		= mysql_query($abstractmbookpage_query);
		if($abstractmbookpage_sql == true)
		{
			if(mysql_num_rows($abstractmbookpage_sql)>0)
			{
				$ABSList2 			= mysql_fetch_object($abstractmbookpage_sql);
				$abstractmbookpage 	= $ABSList2->mbpage + 1;
				$abstractmbookid 	= $ABSList2->allotmentid;
				$mbookmode 			= $ABSList2->mbookmode;
				if($mbookmode == "SINMB"){
					$SelectMbPageQuery	= "select max(endpage) as maxpage from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and mbno = '$abstractmbookno' and (genlevel = 'staff' OR genlevel = 'composite') and mtype = 'G'  GROUP BY mbno";
					$SelectMbPageSql	= mysql_query($SelectMbPageQuery);
					if($SelectMbPageSql == true){
						if(mysql_num_rows($SelectMbPageSql)>0){
							$PageList = mysql_fetch_object($SelectMbPageSql);
							$abstractmbookpage = $PageList->maxpage+1;
						}
					}
				}
				$_SESSION["abs_page"] 		= $abstractmbookpage;
				$_SESSION["abs_mbno_id"] 	= $abstractmbookid;
				
			}
		}
	}
	
	$SelectDatesQuery	= "select fromdate, todate from abstractbook where sheetid='$sheetid' and rbn = '$rbn'";
	$SelectDatesSql		= mysql_query($SelectDatesQuery);
	if($SelectDatesSql == true){
		if(mysql_num_rows($SelectDatesSql)>0){
			$DateList = mysql_fetch_object($SelectDatesSql);
			$fromdate = dt_display($DateList->fromdate);
			$todate = dt_display($DateList->todate);
			$_SESSION['fromdate'] 	= $DateList->fromdate;
			$_SESSION['todate'] 	= $DateList->todate;
		}
	}

	
}else{
	$sheetid 			= "";
	$rbn 				= "";
	$fromdate 			= "";
	$todate 			= "";
	$abstractmbookno 	= "";
	$abstractmbookpage 	= "";
	$abstractmbookid 	= "";
}
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack(){
	   	url = "PageGenerate.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack(){ 
		window.history.forward(); 
	}
</script>

<script src="stepWizard/bootstrap.min.js"></script>
<!--<script src="stepWizard/jquery.wizard.js"></script>-->
<script>
var page = "ABSG";
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
				
				
				html += '<span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-prev"><input type="button" name="btn_previous" id="btn_previous1" value="Previous" style="background:none; border:none; padding:0px; box-shadow:none; height:17px; color:#ffffff;"></span>';
				html += '<span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-next"><span class="next-text">'+ opts.text.next +'</span><input type="submit" name="btn_next" id="btn_next1" value="Complete" style="background:none; border:none; padding:0px; box-shadow:none; text-shadow:none; height:18px; margin: 2px;"></span>';
				
				
				html += '</div></div>';
				
				stepsBar.after(html);
			}
			
			html = '';
			if(opts.bottomButtons && !bottomActions.length){
				html += '<div class="bottom-actions">';
				//html += '<div class="left-actions"><span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-prev"><input type="button" name="btn_previous" id="btn_previous1" value="Previous" style="background:none; border:none; padding:0px; box-shadow:none; height:25px; color:#ffffff; font-weight:bold; font-size:13px;"></span></div>';
				//html += '<div class="right-actions"><span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-next"><span class="next-text">'+ opts.text.next +'</span><input type="submit" name="btn_next" id="btn_next1" value="Complete" style="background:none; border:none; padding:0px; box-shadow:none; height:25px;"></span></div>';
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

</script>


<link href="stepWizard/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="stepWizard/jquery.wizard.css" rel="stylesheet">
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
    color: #005EBB;
    font-weight: normal; font-weight:600;
	}
	.bfont{
    color: #005EBB;
    font-weight: normal; font-weight:600;
	font-family:Verdana, Arial, Helvetica, sans-serif;
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
		padding-top:8px;
	}
	.wiz-icon-arrow-left::before {
		padding-top:8px;
	}
	.sweet-alert fieldset input[type="text"] {
		display: none;
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
	.labelbold{
		color:#005EBB;
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <link rel="stylesheet" href="css/timeline.css">
  <!--==============================Content=================================-->
	<div class="content">
    				<div class="title">Abstract Generate</div>
        <div class="container_12">
        	<div class="grid_12">
            	<blockquote class="bq1" style="overflow:auto">
                	<form name="form" method="post" action="">
                    	<div class="container">
							
							<div class="container-fluid">
								<div data-wizard-init>
								  <ul class="steps">
									<li data-step="1">MBook - General</li>
									<li data-step="2">MBook - Steel</li>
									<li data-step="3">Sub Abstract</li>
									<li data-step="4" class="active">Abstract</li>
								  </ul>
								  <div class="steps-content" align="center">
									<div data-step="4" class="mb-sec">
										
										<!--<div class="timeline__post">
											<div class="timeline__content">
												<div class="grid_3"><span class='badge'>Name of Work&nbsp;</span> </div> 
												<div class="grid_1"> : </div>
												<div class="grid_8" style="float:none"><p><?php echo $WorkName; ?></p></div>
											 </div>
										</div>-->
										<div class="timeline__post">
											<div class="timeline__content bfont">
												<div class="grid_2">Name of Work</div> :<!--<span class='badge'>Name of Work</span>--> <?php echo $WorkShortName; ?>
											 </div>
										</div>
										<!--<div class="timeline__post">
											<div class="timeline__content">
												<div class="grid_3"><span class='badge'>Work Order No&nbsp;</span> </div> 
												<div class="grid_1"> : </div>
												<div class="grid_8" style="float:none"><p><?php echo $WorkOrderNo; ?></p></div>
											 </div>
										</div>-->
										<div class="timeline__post">
											<div class="timeline__content bfont">
												<div class="grid_2">Work Order No</div><!--<span class='badge'>Name of Work</span>-->: <?php echo $WorkOrderNo; ?>
											 </div>
										</div>
										<!--<div class="timeline__post">
											<div class="timeline__content">
												<div class="grid_3"><span class='badge'>RAB&nbsp;</span></div>
												<div class="grid_1"> : </div>
												<div class="grid_8" style="float:none"><p><?php echo $rbn; ?></p></div>
											 </div>
										</div>-->
										<div class="timeline__post">
											<div class="timeline__content bfont">
												<div class="grid_2">RAB</div><!--<span class='badge'>Name of Work</span>-->: <?php echo $rbn; ?>
											 </div>
										</div>
										<!--<div class="timeline__post">
											<div class="timeline__content">
												<div class="grid_3"><span class='badge'>From Date&nbsp;</span></div> 
												<div class="grid_1"> : </div>
												<div class="grid_8" style="float:none"><p><?php echo $fromdate; ?></p></div>
											 </div>
										</div>-->
										<div class="timeline__post">
											<div class="timeline__content bfont">
												<div class="grid_2">From Date</div><!--<span class='badge'>Name of Work</span>-->: <?php echo $fromdate; ?>
											 </div>
										</div>
										<!--<div class="timeline__post">
											<div class="timeline__content">
												<div class="grid_3"><span class='badge'>To Date&nbsp;</span></div> 
												<div class="grid_1"> : </div>
												<div class="grid_8" style="float:none"><p><?php echo $todate; ?></p></div>
											 </div>
										</div>-->
										<div class="timeline__post">
											<div class="timeline__content bfont">
												<div class="grid_2">To Date</div><!--<span class='badge'>Name of Work</span>-->: <?php echo $todate; ?>
											 </div>
										</div>
										<!--<div class="timeline__post">
											<div class="timeline__content">
												<div class="grid_3"><span class='badge'>Abstract MBook No&nbsp;</span></div> 
												<div class="grid_1"> : </div>
												<div class="grid_8" style="float:none"><p><?php echo $abstractmbookno; ?></p></div>
											 </div>
										</div>-->
										<div class="timeline__post">
											<div class="timeline__content bfont">
												<div class="grid_2">Abstract MBook No</div><!--<span class='badge'>Name of Work</span>-->: <?php echo $abstractmbookno; ?>
											 </div>
										</div>
										<!--<div class="timeline__post">
											<div class="timeline__content">
												<div class="grid_3"><span class='badge'>Abstract MBook page&nbsp;</span></div>
												<div class="grid_1"> : </div>
												<div class="grid_8" style="float:none"><p><?php echo $abstractmbookpage; ?></p></div>
											 </div>
										</div>-->
										<div class="timeline__post">
											<div class="timeline__content bfont">
												<div class="grid_2">Abstract MBook page</div><!--<span class='badge'>Name of Work</span>--> : <?php echo $abstractmbookpage; ?>
											 </div>
										</div>
									</div>
								  </div>
								</div>
							</div>
     					</div>
						<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
							<div class="buttonsection">
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
		background:#D8D8D8;
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
		$.fn.validateworkorder = function(event) { 
			if($("#cmb_shortname").val()==""){ 
				var a="Please select the work order number";
				$('#val_work').text(a);
				event.preventDefault();
				event.returnValue = false;
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
		$("#btn_previous1").click(function(){
			$(location).attr('href', "MBookGenerateSection3.php")
		});
		$("#btn_previous2").click(function(){
			$(location).attr('href', "MBookGenerateSection3.php")
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

