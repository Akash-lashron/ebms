<?php
@ob_start();
require_once '../../library/config.php';
$Status = 0;
$userid         = $_SESSION['userid'];
$ContName 		= trim($_POST['txt_modal_entry_cont_name']);
$ContAddress	= trim($_POST['txt_modal_entry_cont_addr']);
$ContState	    = trim($_POST['txt_modal_entry_cont_state']);  
$ContAccNo 		= trim($_POST['txt_modal_entry_acc_no']);
$ContBankName	= trim($_POST['txt_modal_entry_bank_name']);
$ContBrName 	= trim($_POST['txt_modal_entry_br_name']);
$ContPanNo		= trim($_POST['txt_modal_entry_pan_no']);
$ContGstNo		= trim($_POST['txt_modal_entry_gst_no']);
$ContIFSC		= trim($_POST['txt_modal_entry_ifsc']);
// $SelectQuery 	    = "select name_contractor from contractor where name_contractor = '$ContName' ";
// echo $SelectQuery;exit;
// $SelectSql 	 	    = mysqli_query($dbConn,$SelectQuery);
// if($SelectSql == true){
//             $Status = 'A';
//     }else{
            $InsertQuery = "insert into contractor set name_contractor = '$ContName', addr_contractor = '$ContAddress', state_contractor='$ContState', pan_no = '$ContPanNo', gst_no = '$ContGstNo', active = '1', createddate = NOW(), userid ='$userid'";
            $InsertSql = mysqli_query($dbConn,$InsertQuery);
            $LastInsertid = mysqli_insert_id($dbConn);
            $InsertQuery2 = "insert into contractor_bank_detail set contid = '$LastInsertid',bank_acc_hold_name = '$ContName',  bank_acc_no = '$ContAccNo', bank_name = '$ContBankName', branch_address = '$ContBrName',  ifsc_code='$ContIFSC', active = '1', status ='1'";
            $InsertSql2 = mysqli_query($dbConn,$InsertQuery2);
                if($InsertSql2 == true){
                    $Status = 1;
                }
       // }

echo $Status;
?>
