<?php
require_once 'library/config.php';

$SelectQuery1 = "SELECT contid, gst_perc_rate, is_less_appl, gst_inc_exc FROM sheet WHERE sheet_id = '".$_GET['workorderno']."'";
$SelectSql1 = mysql_query($SelectQuery1);
if($SelectSql1 == true){
	if(mysql_num_rows($SelectSql1)>0){
		$List = mysql_fetch_object($SelectSql1);
		$GstPercRate  = $List->gst_perc_rate;
		$IsLessApplic = $List->is_less_appl;
		$ContractorId = $List->contid;
		$GstIncExc 	  = $List->gst_inc_exc;
		
		$SelectQuery2 = "SELECT state_contractor, pan_type, gst_type, is_ldc_appl, ldc_rate FROM contractor WHERE contid = '$ContractorId'";
		$SelectSql2   = mysql_query($SelectQuery2);
		if($SelectSql2 == true){
			if(mysql_num_rows($SelectSql2)>0){
				$List2 = mysql_fetch_object($SelectSql2);
				$PanType  	= $List2->pan_type;
				$GstType  	= $List2->gst_type;
				$ContState  = $List2->state_contractor;
				$IsLdcAppl  = $List2->is_ldc_appl;
				$LdcRate  	= $List2->ldc_rate;
			}
		}
		
	}
}
//echo $SelectQuery2;exit;

$SelectQuery3 = "SELECT * FROM gst_rate_master";
$SelectSql3   = mysql_query($SelectQuery3);
if($SelectSql3 == true){
	if(mysql_num_rows($SelectSql3)>0){
		while($List3 = mysql_fetch_object($SelectSql3)){
			$GstDesc  	= $List3->gst_desc;
			if($GstDesc == "CGST"){
				$Cgst 	= $List3->gst_rate;
			}
			if($GstDesc == "SGST"){
				$Sgst 	= $List3->gst_rate;
			}
			if($GstDesc == "CGST"){
				$Igst 	= $List3->gst_rate;
			}
			$GstType  	= $List2->gst_type;
			$ContState  = $List2->state_contractor;
		}
	}
}

$SelectQuery4 = "SELECT * FROM it_rate_master";
$SelectSql4   = mysql_query($SelectQuery4);
if($SelectSql4 == true){
	if(mysql_num_rows($SelectSql4)>0){
		while($List4 = mysql_fetch_object($SelectSql4)){
			if($List4->pan_type == "I"){
				$IndItRate 	= $List4->it_rate;
			}
			if($List4->pan_type == "O"){
				$OthItRate 	= $List4->it_rate;
			}
		}
	}
}
if($IsLdcAppl == 'Y'){
	$ITaxPerc = $LdcRate;
}else{
	if(isset($PanType)){
		if($PanType == "I"){
			$ITaxPerc = $IndItRate;
		}else{
			$ITaxPerc = $OthItRate;
		}
	}else{
		$ITaxPerc = 0;
	}
}
if(isset($ContState)){
	if($ContState != "TN"){
		$IsIGst = "Y";
	}else{
		$IsIGst = "N";
	}
}else{
	$IsIGst = "N";
}

$select_recovery_query 	= 	"SELECT r1.* FROM recoveries r1 WHERE r1.rid = (select max(r2.rid) from recoveries r2)";
$select_recovery_sql	=	mysql_query($select_recovery_query);
if($select_recovery_sql == true) 
{
	$List = mysql_fetch_object($select_recovery_sql);
	$wctnoncivil 		= 	$List->wct_noncivil;
	$wctcivil 			= 	$List->wct_civil;
	$mobadvance 		= 	$List->mob_advance;
	$labourwelfare 		= 	$List->labour_welfare;
	if($ITaxPerc != 0){
		$incometax 		=	$ITaxPerc;
	}else{
		$incometax 		= 	$List->incometax;
	}
	$sd 				= 	$List->sd;
	$sdrbn 				= 	$List->sd_rbn;
	$watercharge 		= 	$List->water_charge;
	$watermaxlevel 		= 	$List->water_maxlevel;
	$electricitycharge 	= 	$List->electricity_charge;
	$landrent 			= 	$List->land_rent;
	$liquiddamage 		= 	$List->liquid_damage;
	$interestma 		= 	$List->interest_ma;
	$otherrecovery 		= 	$List->other_recovery;
	$vat_noncivil 		= 	$List->vat_noncivil;
	$vat_civil 			= 	$List->vat_civil;
	$it_cess 			= 	$List->it_cess;
	$it_edu_cess 		= 	$List->it_edu_cess;
	$recoverydata = $wctnoncivil."*".$wctcivil."*".$mobadvance."*".$labourwelfare."*".$incometax."*".$sd."*".$sdrbn."*".$watercharge."*".$watermaxlevel."*".$electricitycharge."*".$landrent."*".$liquiddamage."*".$interestma."*".$otherrecovery."*".$vat_noncivil."*".$vat_civil."*".$it_cess."*".$it_edu_cess."*".$GstPercRate."*".$IsIGst."*".$Sgst."*".$Cgst."*".$Igst."*".$PanType."*".$IsLdcAppl."*".$GstIncExc; 
}
echo $recoverydata;
	
?>
