<?php
@ob_start();
require_once '../../library/config.php';
$Status = 0;
$ContName 		= trim($_POST['txt_modal_entry_cont_name']);
$ContAddress	= trim($_POST['txt_modal_entry_cont_addr']);
$ContState	    = trim($_POST['txt_modal_entry_cont_state']);  
$ContAccNo 		= trim($_POST['txt_modal_entry_acc_no']);
$ContBankName	= trim($_POST['txt_modal_entry_bank_name']);
$ContBrName 	= trim($_POST['txt_modal_entry_br_name']);
$ContPanNo		= trim($_POST['txt_modal_entry_pan_no']);
$ContGstNo		= trim($_POST['txt_modal_entry_gst_no']);
$ContIFSC		= trim($_POST['txt_modal_entry_ifsc']);

$InsertQuery = "insert into contractor set name_contractor = '$ContName', addr_contractor = '$ContAddress', state_contractor='$ContState', bank_acc_no = '$ContAccNo', bank_name = '$ContBankName', branch_name = '$ContBrName', pan_no = '$ContPanNo', gst_no = '$ContGstNo', ifsc_code='$ContIFSC', active = 1, createddate = NOW(), userid = ".$_SESSION['sid'];
$InsertSql = mysql_query($InsertQuery);
$Status = mysql_insert_id();
echo $Status;
?>