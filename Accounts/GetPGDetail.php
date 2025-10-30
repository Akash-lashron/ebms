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
$SelectQuery 	= "SELECT * FROM loi_entry WHERE tr_id = '$MastId'";
$SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$CList = mysqli_fetch_object($SelectSql);
			$LOID =  $CList->loa_pg_id;
	}
}

$PgdetailQuery 	= "select * from bg_fdr_details where master_id = '$LOID' ";	
$SelectSql 	 	= mysqli_query($dbConn,$PgdetailQuery);	
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		while($List = mysqli_fetch_array($SelectSql)){
		    $PgId               =      $List['master_id'];
			$PgDTID             =     $List['bfdid'];
	        $Pgtype             =      $List['inst_type'];
			$BankName           =     $List['inst_bank_name'];
			$BankSerial         =      $List['inst_serial_no'];
			$PgDate             =     $List['inst_date'];
			$PgDate1            =     dt_display($PgDate);
			$PgExpDate          =     $List['inst_exp_date'];
			$PgExpDate1         =     dt_display($PgExpDate);
			$PgExtenDate        =     $List['inst_ext_date'];
			$PgExtenDate1       =     dt_display($PgExtenDate);
			$PgCreatedby        =     $List['createdby'];
			$PgCreatedsess      =     $List['created_section'];
			$PgCrearedon         =     $List['createdon'];
			$PgCrearedon1        =     dt_display($PgCrearedon);
			$Pgamt               =     $List['inst_amt'];
			$Pgamt1              =     round(($Pgamt),0);
			$List['inst_date']                  =  $PgDate1;
			$List['inst_exp_date']              =  $PgExpDate1;
			$List['inst_amt']                   =  $Pgamt1;
			$List['inst_ext_date']              =  $PgExtenDate1;
			$List['createdon']                  =  $PgCrearedon1; 

			$Result1[] = $List;
		}

	}
}
echo json_encode($Result1);
?> 