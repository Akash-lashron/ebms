<?php
@ob_start();
require_once 'library/config.php';
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
$MastId = $_POST['TrNum'];

$SelectQuery 	= "SELECT * FROM emd_master WHERE tr_id = '$MastId'";
$SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$List = mysqli_fetch_array($SelectSql);
		$Emid       = $List['emid'];
		$Result1[]	= $List;
	}
}

if(($SelectSql == true)&&(mysqli_num_rows($SelectSql)>0)){
	$RowSpanContArr = array();
	$EmddetailQuery 	= "SELECT emd_detail.*, contractor.name_contractor FROM emd_detail JOIN contractor ON emd_detail.contid = contractor.contid 
							WHERE emd_detail.emid = '$Emid' AND emd_detail.status != 'R' ORDER BY contid ";
	$SelectSql 	 	= mysqli_query($dbConn,$EmddetailQuery);	
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			while($List = mysqli_fetch_array($SelectSql)){
				$ContID       =       $List['contid'];
				if(isset($RowSpanContArr[$ContID])){
					$RowSpanContArr[$ContID] = $RowSpanContArr[$ContID] + 1;
				}else{
					$RowSpanContArr[$ContID] = 1;
				}
				$WmddeID       =     $List['emdtid'];
				$Insttye       =     $List['inst_type'];
				$BankName      =     $List['bank_name'];
				$Contname      =     $List['name_contractor'];
				$Instnum       =     $List['inst_no'];
				$BankAddress   =     $List['branch_addr'];
				$Emdamount     =     $List['emd_amt'];
				$Emdamount1    =     round($Emdamount);
				$Issueddt      =     $List['issue_dt'];
				$Issueddt1     =     dt_display($Issueddt);
				$validddt      =     $List['valid_dt'];
				$validddt1     =     dt_display($validddt);
				$List['issue_dt'] =  $Issueddt1;
				$List['valid_dt'] =  $validddt1;
				$List['emd_amt']  =  $Emdamount1;
				$Result2[] =        $List;
			}
		}
	}
	$rows = array('row1'=>$Result1,'row2'=>$Result2,'row3'=>$RowSpanContArr);
}else{
	$rows = null;
}
//print_r($Result2);exit;
           
			
	


echo json_encode($rows);
?> 