<?php
@ob_start();
require_once '../library/config.php';
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
$MastId		= $_POST['MastId'];
$SelectQuery 	=  "select emd_master.*,tender_register.work_name from emd_master 
					JOIN tender_register ON emd_master.tr_id = tender_register.tr_id  where emd_master.tr_id = '$MastId'";
              
$SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$List = mysqli_fetch_array($SelectSql);
		$Workname    =     $List['work_name'];
		$EmdAmt      =     $List['emd_lot_amt'];
		$Emid        =     $List['emid'];
		$GlobId      =     $List['globid'];
		$Result1[]   =     $List;
	}
}//print_r($Result1);exit;

$RowSpanContArr = array();
$EmddetailQuery 	= "select emd_detail.*, contractor.name_contractor from emd_detail
					   JOIN contractor ON emd_detail.contid = contractor.contid       
					   where emd_detail.emid = '$Emid' and emd_detail.status !='R' order by emd_detail.contid ";	
$SelectSql 	 	= mysqli_query($dbConn,$EmddetailQuery);	
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		while($List = mysqli_fetch_array($SelectSql)){
			$ContID       =       $List['contid'];
			//echo($ContID);
			if(isset($RowSpanContArr[$ContID])){
				$RowSpanContArr[$ContID] = $RowSpanContArr[$ContID] + 1;
			}else{
				$RowSpanContArr[$ContID] = 1;
			}
			$EMdDID       =      $List['emid'];
			$EmddeID       =     $List['emdtid'];
			$Insttye       =     $List['inst_type'];
			$BankName      =     $List['bank_name'];
			$Contname      =     $List['name_contractor'];
			$Instnum       =     $List['inst_no'];
			$BankAcc       =     $List['bank_acc_no'];
			$BankAddress   =     $List['branch_addr'];
			$Emdamount     =     $List['emd_amt'];
			$Emdamount1    =     round($Emdamount);
			$Issueddt      =     $List['issue_dt'];
			$Issueddt1     =     dt_display($Issueddt);
			$validddt      =     $List['valid_dt'];
			$validddt1     =     dt_display($validddt);
			$List['issue_dt']               =  $Issueddt1;
			$List['valid_dt']               =  $validddt1;
			$List['emd_amt']                =  $Emdamount1;
		
			$Result2[] =        $List;
		}
		
	}
}
           
			

$rows = array('row1'=>$Result1,'row2'=>$Result2,'row3'=>$RowSpanContArr);

echo json_encode($rows);
?> 