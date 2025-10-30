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
$GlobID= '';
$SelectTSQuery = "SELECT globid FROM sheet where sheet_id = '$MastId'";
$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
if($SelectTSSql == true){
	if(mysqli_num_rows($SelectTSSql)>0){
		$CList = mysqli_fetch_object($SelectTSSql);
		$GlobID = $CList->globid;
  }
}
$RowSpanContArr = array();
$PGdetailQuery 	= "select * from bg_fdr_details  where master_id = '$MastId' and inst_status != 'R'and  globid = '$GlobID'   order by contid ";	
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
			$ContID         =     $List['contid'];
			$BGDetID        =     $List['bfdid']; 
			$SheetID        =     $List['master_id']; 
			$InstPurID      =     $List['inst_purpose'];
			$Insttye       =     $List['inst_type'];
			$BankName      =     $List['inst_bank_name'];
			$Instnum       =     $List['inst_serial_no'];
			$Instamount    =     $List['inst_amt'];
			$Emdamount1    =     round($Instamount);
			$Issueddt      =     $List['inst_date'];
			$Issueddt1     =     dt_display($Issueddt);
			$validddt      =     $List['inst_exp_date'];
			$validddt1     =     dt_display($validddt);
			$List['inst_date']               =  $Issueddt1;
			$List['inst_exp_date']           =  $validddt1;
			$List['inst_amt']                =  $Emdamount1;
		
			$Result1[] =        $List;
		}
		
	}
}
           
			
	

$rows = array('row1'=>$Result1,'row2'=>$RowSpanContArr);

echo json_encode($rows);
?> 