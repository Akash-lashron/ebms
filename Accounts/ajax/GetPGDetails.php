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
$SelectQuery 	=  "select loi_entry.*,tender_register.work_name, contractor.name_contractor from loi_entry 
					JOIN tender_register ON loi_entry.tr_id = tender_register.tr_id 
					JOIN contractor ON loi_entry.contid = contractor.contid 
				    where loi_entry.tr_id = '$MastId'";
              
$SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$List = mysqli_fetch_array($SelectSql);
		$PGLOID                   =     $List['loa_pg_id'];
		$Workname                 =     $List['work_name'];
		$ContID                   =     $List['contid'];
		$Contname                 =     $List['name_contractor'];
		$Pgper                    =     $List['pg_per'];
		$PGAmt                    =     $List['pg_amt'];
		$LoaNo                    =     $List['loa_no']; 
		$LOiDate                  =     $List['loa_dt'];   
		$LOiDate1                 =     dt_display($LOiDate);
		$GlobId                   =     $List['globid'];
		$List['loa_dt']           =     $LOiDate1;
		$Result1[]                =     $List;
	}
}//print_r($Result1);exit;

$RowSpanContArr = array();
$PGdetailQuery 	= "select * from bg_fdr_details  where master_id = '$PGLOID' and  inst_status != 'R' order by contid ";	
$SelectSql 	 	= mysqli_query($dbConn,$PGdetailQuery);	
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		while($List = mysqli_fetch_array($SelectSql)){
			$InstPurID       =       $List['inst_purpose'];
			if(isset($RowSpanContArr[$InstPurID])){
				$RowSpanContArr[$InstPurID] = $RowSpanContArr[$InstPurID] + 1;
			}else{
				$RowSpanContArr[$InstPurID] = 1;
			}
			$ContID          =     $List['contid'];
			$LOIID           =     $List['master_id']; 
			$BGDetID        =      $List['bfdid']; 
			$InstPurID      =      $List['inst_purpose'];
			$Insttye       =       $List['inst_type'];
			$BankName      =       $List['inst_bank_name'];
			$Instnum       =       $List['inst_serial_no'];
			$Instamount    =       $List['inst_amt'];
			$Emdamount1    =     round($Instamount);
			$Issueddt      =      $List['inst_date'];
			$Issueddt1     =      dt_display($Issueddt);
			$validddt      =      $List['inst_exp_date'];
			$validddt1     =      dt_display($validddt);
			$List['inst_date']               =  $Issueddt1;
			$List['inst_exp_date']           =  $validddt1;
			$List['inst_amt']                =  $Emdamount1;
		
			$Result2[] =        $List;
		}
		
	}
}
           
			

$rows = array('row1'=>$Result1,'row2'=>$Result2,'row3'=>$RowSpanContArr);

echo json_encode($rows);
?> 