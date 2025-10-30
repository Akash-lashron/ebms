<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
checkUser();
include "sysdate.php";
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
if(isset($_POST["btn_save"]) == " Save "){/*
    $sheetid 			= 	trim($_POST['txt_sheetid']);
    $rbn 				= 	trim($_POST['txt_rbn']);
	$abstract_net_amt 	= 	trim($_POST['txt_abstract_amt']);
	$sec_adv_amount 	= 	trim($_POST['txt_sec_adv']);
    $sd_percent 		= 	trim($_POST['txt_sd_perc']);
    $sd_amt 			= 	trim($_POST['txt_sd']);
    $wct_percent 		= 	trim($_POST['txt_wct_perc']);
    $wct_amt 			= 	trim($_POST['txt_wct']);
	$vat_percent 		= 	trim($_POST['txt_vat_perc']);
    $vat_amt 			= 	trim($_POST['txt_vat']);
	$mob_adv_percent 	= 	trim($_POST['txt_mob_adv_perc']);
	$mob_adv_amt 		= 	trim($_POST['txt_mob_adv']);
	$lw_cess_percent 	= 	trim($_POST['txt_lw_cess_perc']);
	$lw_cess_amt 		= 	trim($_POST['txt_lw_cess']);
	$incometax_percent 	= 	trim($_POST['txt_incometax_perc']);
	$incometax_amt 		= 	trim($_POST['txt_incometax']);
	$it_cess_percent 	= 	trim($_POST['txt_ITcess_perc']);
	$it_cess_amt 		= 	trim($_POST['txt_ITcess']);
	$it_edu_percent 	= 	trim($_POST['txt_ITEcess_perc']);
	$it_edu_amt 		= 	trim($_POST['txt_ITEcess']);
	$land_rent 			= 	trim($_POST['txt_rent_land']);
	$liquid_damage 		= 	trim($_POST['txt_liquid_damage']);
	$other_recovery_1 	= 	trim($_POST['txt_other_recovery_1']);
	$other_recovery_2 	= 	trim($_POST['txt_other_recovery_2']);
	$other_recovery_1_desc 	= 	trim($_POST['txt_other_recovery_1_desc']);
	$other_recovery_2_desc	= 	trim($_POST['txt_other_recovery_2_desc']);
	$nodep_machine 		= 	trim($_POST['txt_nodep_machine']);
	$nodep_mp 			= 	trim($_POST['txt_nodep_mp']);
	$nonsubmission_qa	=	trim($_POST['txt_nonsubmission_qa']);
	
    $cgst_percent 			= 	trim($_POST['txt_cgst_perc']);
    $cgst_amt 				= 	trim($_POST['txt_cgst']);
    $sgst_percent 			= 	trim($_POST['txt_sgst_perc']);
    $sgst_amt 				= 	trim($_POST['txt_sgst']);
	
	$DeleteQuery = "delete from memo_payment_accounts_edit where sheetid = '$sheetid' and rbn = '$rbn'";
	$DeleteSql = mysqli_query($dbConn,$DeleteQuery);
	
    $InsertQuery 		= 	"insert into memo_payment_accounts_edit set "
											. "sheetid = '$sheetid',  rbn = '$rbn', "
                                            . "abstract_net_amt = '$abstract_net_amt', "
											. "sec_adv_amount = '$sec_adv_amount', "
											. "cgst_percent = '$cgst_percent', "
                                            . "cgst_amt = '$cgst_amt', "
                                            . "sgst_percent = '$sgst_percent', "
                                            . "sgst_amt = '$sgst_amt', "
                                            . "sd_percent = '$sd_percent', "
                                            . "sd_amt = '$sd_amt', "
                                            . "wct_percent = '$wct_percent', "
                                            . "wct_amt = '$wct_amt', "
											. "vat_percent = '$vat_percent', "
                                            . "vat_amt = '$vat_amt', "
											. "mob_adv_percent = '$mob_adv_percent', "
											. "mob_adv_amt = '$mob_adv_amt', "
											. "lw_cess_percent = '$lw_cess_percent', "
											. "lw_cess_amt = '$lw_cess_amt', "
											. "incometax_percent = '$incometax_percent', "
											. "incometax_amt = '$incometax_amt', "
											. "it_cess_percent = '$it_cess_percent', "
											. "it_cess_amt = '$it_cess_amt', "
											. "it_edu_percent = '$it_edu_percent', "
											. "it_edu_amt = '$it_edu_amt', "
											. "land_rent = '$land_rent', "
											. "liquid_damage = '$liquid_damage', "
											. "other_recovery_1_amt = '$other_recovery_1', "
											. "other_recovery_1_desc = '$other_recovery_1_desc', "
											. "other_recovery_2_amt = '$other_recovery_2', "
											. "other_recovery_2_desc = '$other_recovery_2_desc', "
											. "non_dep_machine_equip = '$nodep_machine', "
											. "non_dep_man_power = '$nodep_mp', "
											. "nonsubmission_qa = '$nonsubmission_qa', "
											. "staffid = '$staffid', "
											. "userid = '$userid', "
											. "modifieddate = NOW(), "
                                            . "active = 1";
											//echo $InsertQuery;
    $InsertSql 	= 	mysqli_query($dbConn,$InsertQuery);
    if($InsertSql == true){
        $msg = "Recovery Details Saved Successfully ";
		$success = 1;
    }
	else
	{
		$msg = " Recovery Details Not Saved...!!! ";
	}
*/} 
if(isset($_POST["btn_skip"]) == " Skip "){
    $sheetid 			= 	trim($_POST['txt_sheetid']);
    $rbn 				= 	trim($_POST['txt_rbn']);
	//$HOA 				= 	trim($_POST['txt_hoa']);
}
if(($_GET["sheetid"] != "")&&($_GET["rbn"] != "")){ 
    $sheetid 			= 	$_GET['sheetid'];
    $rbn 				= 	$_GET['rbn'];
	$HOA 				= 	$_GET['hoa'];
}
if(isset($_POST["btn_go"]) ==  'GO '){
    $sheetid 			= 	trim($_POST['cmb_work_no']);
    $rbn 				= 	trim($_POST['cmb_rbn']);
	$CCNo 				= 	trim($_POST['txt_ccno']);
	//$HOA 				= 	trim($_POST['txt_hoa']);
	
}
//echo $rbn;exit;
if($sheetid != ""){
	$query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
	$sqlquery 	= 	mysqli_query($dbConn,$query);
	if($sqlquery == true){
		$List 					= 	mysqli_fetch_object($sqlquery);
		$work_name 				= 	$List->work_name; 
		$short_name 			= 	$List->short_name;   
		$tech_sanction 			= 	$List->tech_sanction;  
		$name_contractor 		= 	$List->name_contractor; 
		$ContId 				= 	$List->contid; 
		$ContBankId 			= 	$List->cbdtid; 
		if($ContId != 0){
			$SelectContQuery = "select * from contractor where contid = '$ContId'";
			$SelectContSql = mysqli_query($dbConn,$SelectContQuery);
			if($SelectContSql == true){
				if(mysqli_num_rows($SelectContSql)>0){
					$ContList = mysqli_fetch_object($SelectContSql);
					$name_contractor = $ContList->name_contractor;
					$pan_no = $ContList->pan_no;
					$gst_no = $ContList->gst_no;
				}
			}
		}
		if($ContBankId != 0){
			$SelectBankQuery = "select * from contractor_bank_detail where cbdtid = '$ContBankId'";
			$SelectBankSql = mysqli_query($dbConn,$SelectBankQuery);
			if($SelectBankSql == true){
				if(mysqli_num_rows($SelectBankSql)>0){
					$BankList = mysqli_fetch_object($SelectBankSql);
					$ContAccHolder = $BankList->bank_acc_hold_name;
					$ContAccNo = $BankList->bank_acc_no;
					$ContBankName = $BankList->bank_name;
					$ContBankBrAddr = $BankList->branch_address;
					$ContBankIfsc = $BankList->ifsc_code;
				}
			}
		}
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
		$contid 		        = 	$List->contid;
		$hoa 		            = 	$List->hoa;
		$HoaArr = array();
		$GlobId = $List->globid;
		$HoaId  = $List->hoaid;
		if(($List->hoaid != '')&&($List->hoaid != NULL)){
			$SelectQuery1 = "select * from hoa where hoa_id IN ($HoaId)";
			$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					while($List1 = mysqli_fetch_object($SelectSql1)){
						$HoaNo = $List1->hoa_no;
						array_push($HoaArr,$HoaNo);
					}
				}
			}
		}else{
			$SelectQuery2 = "select * from works where globid = '$GlobId'";
			$SelectSql2 = mysqli_query($dbConn,$SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					$List2 = mysqli_fetch_object($SelectSql2);
					$HoaNo = $List2->hoa;
					if(($HoaNo != '')&&($HoaNo != NULL)){
						array_push($HoaArr,$HoaNo);
					}else{
						$SelectQuery3 = "select * from hoa where hoa_id IN ($List2->hoaid)";
						$SelectSql3 = mysqli_query($dbConn,$SelectQuery3);
						if($SelectSql3 == true){
							if(mysqli_num_rows($SelectSql3)>0){
								while($List3 = mysqli_fetch_object($SelectSql3)){
									$HoaNo = $List3->hoa_no;
									array_push($HoaArr,$HoaNo);
								}
							}
						}
					}
				}
			}
		}
		if(count($HoaArr)>0){
			$HoaStr = implode(",<br/> ",$HoaArr);
			$hoa = $HoaStr;
		}
	}//echo $query;exit;
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
			$pl_mac_adv_amt = $AccList->pl_mac_adv_amt;
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
			$pass_order_dt = dt_display($AccList->pass_order_dt);
			$pay_order_dt = dt_display($AccList->pay_order_dt);
			$voucher_dt = dt_display($AccList->payment_dt);
			
			
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
}
    //$contid=0;
   /* $ContSelectSAQuery = "select * from contractor where contid='$contid' ";
    $ContSelectSASql = mysqli_query($dbConn,$ContSelectSAQuery);
	if($ContSelectSASql == true){
		if(mysqli_num_rows($ContSelectSASql)>0){
			$ContAList = mysqli_fetch_object($ContSelectSASql);
			//$contid         = $ContAList->contid;
			$Acc_Num        = $ContAList->bank_acc_no;
			$Bank_Name      = $ContAList->bank_name;
			$Branch_Name    = $ContAList->branch_name;
			$Pan_No         = $ContAList->pan_no;
			$Gst_No         = $ContAList->gst_no;
			$IFSC           = $ContAList->ifsc_code;
		}
	}//echo $IFSC;exit;*/

$BFpage = 1;
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack(){
	   	url = "MemoPaymentView.php";
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
	function noBack() { window.history.forward(); }
</script>
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
	@page{
			size: A4 portrait;
			margin: 6mm 6mm 6mm 6mm;
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
<script src="stepWizard/bootstrap.min.js"></script>
<!--<script src="stepWizard/jquery.wizard.js"></script>-->
<script>
var page = "MBKG";
var page_url = "AccountsGenerateSection2.php";
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
				html += '<span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-next"><input type="submit" name="btn_next" id="btn_next1" value="Next" style="background:none; border:none; padding:0px; box-shadow:none; height:23px;"><span class="finished-text">'+ opts.text.finished +'</span></span>';
				
				html += '</div></div>';
				
				stepsBar.after(html);
			}
			
			html = '';
			if(opts.bottomButtons && !bottomActions.length){
				html += '<div class="bottom-actions">';
				//html += '<div class="left-actions"><span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-prev"><span class="previous-text">'+ opts.text.previous +'</span></span></div>';
				//html += '<div class="right-actions"><span class="'+ opts.btnClass +' '+ opts.btnClassDefault +' btn-next"><input type="submit" name="btn_next" id="btn_next2" value="Next" style="background:none; border:none; padding:0px; box-shadow:none; height:25px;"><span class="finished-text">'+ opts.text.finished +'</span></span><input type="button" class="backbutton" name="btn_skip" id="btn_skip" value="Skip >">&nbsp;</div>';
				//html += '<div class="center-actions"><input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />&nbsp;&nbsp;</div>';
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
		color:#10478A;
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
                	<form name="form" method="post" action="AccountsGenerateSection2.php">
                    	<div class="container">
							<div class="div1">&nbsp;</div>
							<div class="div10">
							<div class="container-fluid">
								<div data-wizard-init>
								  <ul class="steps">
									<li data-step="1">Memo For Payment</li>
									<li data-step="2">Abstract - B</li>
									<li data-step="3">Recovery</li>
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
									<div data-step="1" align="center">
										<table width="100%" class="tableA" align="center">
											<tr class="">
												<td colspan="8" align="center" class="cboxlabel">
												   <span style="float:center"><b>Memo of Payment</b></span>
												   <span style="float:right">C.CODE &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp; <?php echo $ccno; ?></span>
												</td>
											</tr>
											<tr>
												<td colspan="8" class="cboxlabel">
													<span style="float:left">Bill No. : RAB -  <?php echo $rbn; ?></span>
													<span style="float:right">Sl No.&nbsp;&nbsp;:&nbsp; <?php echo ++$i;?> &nbsp;&nbsp;Date :&nbsp;&nbsp; <?php echo date('d/m/Y');//$work_order_date; ?></span>
												</td>
											</tr>
											
											
											
											<tr class="label">
												<td align="left" width="250px">Name of Contractor</td>
												<td colspan="7"><?php echo $name_contractor; ?> </td>
											</tr>
											<tr class="label">
												<td align="left">Name of Work</td>
												<td colspan="7" style="white-space:normal"><?php echo $work_name; ?> </td>
											</tr>
											<tr class="label">
												<td align="left">Contract Value</td>
												<td colspan="3"><?php echo IND_money_format($workcost); ?> </td>
												<td align="left">Contract Valid Upto</td>
												<td colspan="3"><?php echo dt_display($workcomplete); ?> </td>
											</tr>
											<tr class="label">
												<td align="left">Agreement No.</td>
												<td colspan="7"><?php echo $agree_no; ?> </td>
											</tr>
											<tr class="label">
												<td align="left">Work Order No.</td>
												<td colspan="7"><?php echo $work_order_no; ?> </td>
											</tr>
											<tr class="label">
												<td align="left">Tecnical Sanction No.</td>
												<td colspan="3"><?php echo $tech_sanction; ?> </td>
												<td align="left">HOA</td>
												<td colspan="3"><?php echo $hoa; ?> </td>
											</tr>
											<!--<tr class="label">
												<td align="left" colspan="8">
												A. Upto Date Value of Work Done - Page No. <?php echo $Uptombookpage; ?> MB No. <?php echo $Uptombookno; ?> : 
												<?php echo $upto_date_total_amount; ?>
												</td>
											</tr>-->
											<!--<tr class="label">
												<td align="left" colspan="8">
												B. ADD/DEDUCT Secure Advance : 
												<?php //echo $sec_adv_amount; ?>
												</td>
											</tr>-->
											<tr class="label">
												<td align="left" colspan="2" style="border-right:none;" nowrap="nowrap">A. Upto Date Value of Work Done - Page No. <?php echo $Uptombookpage; ?> MB No. <?php echo $Uptombookno; ?> <span style="float:right">:&nbsp; RS.</span> </td>
												<td align="left" colspan="6" style="border-left:none;"><div style="width:175px; text-align:right"><?php echo IND_money_format($upto_date_total_amount); ?></div></td>
											</tr>
											
											<tr class="label">
												<td align="left" colspan="2" style="border-right:none;">B. ADD/DEDUCT Secure Advance :&nbsp;&nbsp;&nbsp;<span style="float:right">:&nbsp; RS.</span> </td>
												<td align="left" colspan="6" style="border-left:none;"><div style="width:175px; text-align:right"><?php echo IND_money_format($sec_adv_amount); ?></div></td>
											</tr>
											<!--<tr class="label">
												<td align="left" colspan="8">B.ADD: Secure Advance &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp; RS.&nbsp;<?php echo $SecuredAdvance; ?></td>
											</tr>-->
											<?php $GrandTotal = round(($upto_date_total_amount + $sec_adv_amount),2); ?>
											<tr class="label">
												<td align="left" colspan="2" style="border-right:none;">C. Grand Total &nbsp;&nbsp;&nbsp;&nbsp;<span style="float:right">:&nbsp; RS.</span> </td>
												<td align="left" colspan="6" style="border-left:none;"><div style="width:175px; text-align:right"><?php echo IND_money_format($GrandTotal); ?></div></td>
											</tr>
											<tr class="label">
												<td align="left" colspan="2" style="border-right:none;">D. Less: Previous PMT Page No. <?php echo $Uptombookpage; ?> MB No.<?php echo $Uptombookno; ?> (-)&nbsp;&nbsp;&nbsp;&nbsp;<span style="float:right">:&nbsp; RS.</span> </td> 
												<td align="left" colspan="6" style="border-left:none;"><div style="width:175px; text-align:right"><?php echo IND_money_format($dpm_total_amount); ?></div></td>
											</tr>
											<?php $NetTotal = round(($GrandTotal - $dpm_total_amount),2); ?>
											<tr class="label">
												<td align="left" colspan="2" style="border-right:none;">Net Total  &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;[C-D]<span style="float:right">:&nbsp;&nbsp;RS.</span></td>
												<td align="left" colspan="6" style="border-left:none;"><div style="width:175px; text-align:right"><?php echo IND_money_format($NetTotal); ?></div></td>
											</tr>
		
		
											<tr class="label">
												<td align="left" colspan="8">8. Recoveries :</td>
												
											</tr>
										</table>
										<table width="100%" class="tableA" align="center">
											<tr class="label">
												<td align="left">&nbsp;[a]</td>
												<td align="left" colspan="2" class="labelmedium">Recoveries Creditable Under Works</td>
											</tr>
											<?php $rca = 1; $TotalRecovery = 0; if($lw_cess_amt > 0){ $TotalRecovery  = $TotalRecovery + $lw_cess_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rca; $rca++; ?>]  C.L.CESS @ <?php echo $lw_cess_percent; ?> % On (<?php echo $NetTotal; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;  </td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $lw_cess_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($mob_adv_amt > 0){ $TotalRecovery  = $TotalRecovery + $mob_adv_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rca; $rca++; ?>]  Mobilisation Advance (Recovery) &nbsp;&nbsp;&nbsp;&nbsp;  </td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $mob_adv_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($pl_mac_adv_amt > 0){ $TotalRecovery  = $TotalRecovery + $pl_mac_adv_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rca; $rca++; ?>]  Plant & Machinery Advance &nbsp;&nbsp;&nbsp;&nbsp;  </td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $pl_mac_adv_amt; ?></td>
											</tr>
											<?php } ?>
											<tr class="label">
												<td align="left">&nbsp;[b]</td>
												<td align="left" colspan="2" class="labelmedium">Recoveries Creditable To Other Head of Acctount</td>
											</tr>
											<?php $rcb = 1; if($cgst_amt > 0){  $TotalRecovery  = $TotalRecovery + $cgst_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rcb; $rcb++; ?>]  CGST @ <?php echo $cgst_percent; ?> % On (<?php echo $NetTotal; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $cgst_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($sgst_amt > 0){  $TotalRecovery  = $TotalRecovery + $sgst_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rcb; $rcb++; ?>]  SGST @ <?php echo $sgst_percent; ?> % On (<?php echo $NetTotal; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $sgst_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($sd_amt > 0){  $TotalRecovery  = $TotalRecovery + $sd_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rcb; $rcb++; ?>]  S.D. @ <?php echo $sd_percent; ?> % On (<?php echo $NetTotal; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp;  <?php echo $sd_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($wct_amt > 0){  $TotalRecovery  = $TotalRecovery + $wct_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rcb; $rcb++; ?>]  WCT @ <?php echo $wct_percent; ?> % On (<?php echo $NetTotal; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp;  <?php echo $wct_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($vat_amt > 0){  $TotalRecovery  = $TotalRecovery + $vat_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rcb; $rcb++; ?>]  VAT @ <?php echo $vat_percent; ?> % On (<?php echo $NetTotal; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp;<?php echo $vat_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($lw_cess_amt > 0){  $TotalRecovery  = $TotalRecovery + $lw_cess_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rcb; $rcb++; ?>]  L.W CESS AMOUNT @ <?php echo $lw_cess_percent; ?> % On (<?php echo $NetTotal; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;</td> 
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $lw_cess_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($incometax_amt > 0){  $TotalRecovery  = $TotalRecovery + $incometax_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rcb; $rcb++; ?>]  INCOMETAX @ <?php echo $incometax_percent; ?> % On (<?php echo $NetTotal; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp;  <?php echo $incometax_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($it_cess_amt > 0){  $TotalRecovery  = $TotalRecovery + $it_cess_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rcb; $rcb++; ?>]  IT CESS @ <?php echo $it_cess_percent; ?> % On (<?php echo $NetTotal; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $it_cess_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($it_edu_amt > 0){  $TotalRecovery  = $TotalRecovery + $it_edu_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rcb; $rcb++; ?>]  IT EDUCATION @ <?php echo $it_edu_percent; ?> % On (<?php echo $NetTotal; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $it_edu_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($land_rent > 0){ $TotalRecovery  = $TotalRecovery + $land_rent; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rcb; $rcb++; ?>]  LAND RENT &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp;   <?php echo $land_rent; ?></td>
											</tr>
											<?php } ?>
											<?php if($liquid_damage > 0){ $TotalRecovery  = $TotalRecovery + $liquid_damage; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rca; $rca++; ?>]  LIQUID DAMAGE &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp;  <?php echo $liquid_damage; ?></td>
											</tr>
											<?php } ?>
											<?php if($other_recovery_1_amt > 0){ $TotalRecovery  = $TotalRecovery + $other_recovery_1_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rca; $rca++; ?>]  OTHER_RECOVERY_1_DESC &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $other_recovery_1_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($other_recovery_2_amt > 0){ $TotalRecovery  = $TotalRecovery + $other_recovery_2_amt; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rca; $rca++; ?>]  OTHER_RECOVERY_2_DESC &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $other_recovery_2_amt; ?></td>
											</tr>
											<?php } ?>
											<?php if($non_dep_machine_equip > 0){ $TotalRecovery  = $TotalRecovery + $non_dep_machine_equip; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rca; $rca++; ?>]  NON DEP MACHINE EUIP &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp;<?php echo $non_dep_machine_equip; ?></td>
											</tr>
											<?php } ?>
											<?php if($non_dep_man_power > 0){ $TotalRecovery  = $TotalRecovery + $non_dep_man_power; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rca; $rca++; ?>]  NON DEP MAN POWER &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $non_dep_man_power; ?></td>
											</tr>
											<?php } ?>
											<?php if($nonsubmission_qa > 0){ $TotalRecovery  = $TotalRecovery + $nonsubmission_qa; ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $rca; $rca++; ?>]  NON SUBMISSION  &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $nonsubmission_qa; ?></td>
											</tr>
											<?php } ?>
											
											<?php if($TotalRecovery > 0){ ?>
											<tr class="label">
												<td align="left">&nbsp;</td>
												<td align="left" class="labelmedium"> &nbsp;&nbsp;&nbsp;&nbsp;Total Recovery &nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left">:&nbsp;&nbsp;&nbsp; RS.&nbsp; <?php echo $TotalRecovery; ?></td>
											</tr>
											<?php } ?>
											<td colspan="8" align="center">
												<span style="float:center"> Cod. Amt. ................. <?php //echo $rbn; ?></span>
											</td>
											<!--<tr class="label">
												<td align ="center">Assitantsp[I]<br> 10.4.2018</td>
												<td align ="center">Ducument Prepared</td>
												<td align ="center">Registed Entries</td>
											</tr>
											<tr class="label">
												<td align ="center"></td>
												<td align ="center">1. MB P.No. ___________</td>
												<td align ="center">1. C.L.  P.No</td>
											</tr>
											<tr class="label">
												<td align ="center">Assitantsp[II]</td>
												<td align ="center">2. Cod. Amt. 3,81,64,816</td>
												<td align ="center">2. B.C  P.No</td>
											</tr>
											<tr class="label">
												<td align ="center"></td>
												<td align ="center">3. I.T.C  ______________</td>
												<td align ="center">3. S.D  P.No</td>
											</tr>
											<tr class="label">
												<td align ="center">A.A.O</td>
												<td align ="center">4. W.C.T _____________</td>
												<td align ="center">4. MRR  P.No</td>
											</tr>
											<tr class="label">
												<td align ="center"></td>
												<td align ="center">5. Recovery St  ________</td>
												<td align ="center">5. S.C P .No</td>
											</tr>
											<tr class="label">
												<td align ="center">A.O</td>
												<td align ="center"></td>
												<td align ="center"></td>
											</tr>
											<tr class="label">
												<td align ="center">D.C.A</td>
												<td align ="center"></td>
												<td align ="center"></td>
											</tr>-->
										</table>
										<?php $ChequeAmount = round($NetTotal - $TotalRecovery); ?>
										<p style='page-break-after:always; background-color:#f1f1f1; text-align:center' align="center"></p>
										<table width="100%" class="tableA" align="center">
											<tr class="label">
												<td colspan="3" align="center">Government Of India<br>Deportment Of Atomic Energy<br>FRFCF, NRB, BARCF</td>
											</tr>
											<tr class="label" align="center">
												<td colspan="3">
													<span style="float:center"><b>ACCOUNTS (Works)</b></span>
												</td>
											</tr>
											<tr class="label">
												<td colspan="3">
													<span style="float:right">&nbsp;&nbsp;Date :&nbsp;&nbsp; <?php echo date('d/m/Y'); ?></span>
												</td>
											</tr>
											<tr class="label">
											    <td align="left">&nbsp;</td>
												<td align="left"class="labelmedium">Name Of Payee</td>
												<td align="left"> :&nbsp;&nbsp;<?php echo $name_contractor; ?></td>
											</tr>
											<tr class="label">
											    <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td align="left"class="labelmedium">Account Number </td>
												<td align="left">:&nbsp;&nbsp;<?php echo $ContAccNo; ?></td>
											</tr>
											
											<tr class="label">
											    <td align="left">A.A.O.</td>
												<td align="left">Bank/Branch/Code </td>
												<td align="left">:&nbsp;&nbsp;<?php echo $ContBankName; ?> / <?php echo $ContBankBrAddr; ?></td>
											</tr>
											
											<tr class="label">
											    <td align="left"></td>
												<td align="left">Mode Of Payment </td>
												<td align="left">:&nbsp;&nbsp;<?php echo "CHEQUE"; ?></td>
											</tr>
											<tr class="label">
											    <td align="left">A.O.</td>
												<td align="left">IFSC Code </td>
												<td align="left">:&nbsp;&nbsp;<?php echo $ContBankIfsc; ?></td>
											</tr>
											
											<tr class="label">
											    <td align="left">&nbsp;</td>
												<td align="left">Amount (RS.)</td>
												<td align="left">:&nbsp;&nbsp;<?php echo IND_money_format($ChequeAmount); ?></td>
											</tr>
											
											<tr class="label">
											    <td align="left">D.C.A.</td>
												<td align="left">Payment Passed On</td>
												<td align="left">:&nbsp;&nbsp;<?php echo $pass_order_dt; ?></td>
											</tr>
											<tr class="label">
												<td colspan="3">
													<span style="float:right"><br><br>&nbsp;&nbsp;<br>Assistant Accounts Officer<br><br></span>
												</td>
											</tr>
										</table>
										<p style='page-break-after:always; background-color:#f1f1f1; text-align:center' align="center"></p>
										<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>"> 
										<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>"> 
										<input type="hidden" name="txt_hoa" id="txt_hoa" value="<?php echo $HOA; ?>">
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
	.sweet-alert h2{
		line-height:25px;
	}
	.sweet-alert p{
		line-height:25px;
		font-weight:bold;
	}
</style>
<script>
$(function() {
			
	//$(".next-text").click(function(){
		//var target = $(this).parent().attr('data-step');
		//var stepsLi = this.$element.find('.steps > li');
		//alert();
	//});
	/*$(".btn-next").click(function(){
		//$(location).attr('href', page_url)
		$("form[name='form']").submit();
	});*/
});
</script>
<script>
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

