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

$MastId		= $_POST['MastId'];
// $SelectQuery 	= "SELECT sd_perc FROM works WHERE sheetid = '$MastId'";
// $SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
// if($SelectSql == true){
// 	if(mysqli_num_rows($SelectSql)>0){
// 		$CList = mysqli_fetch_object($SelectSql);
// 			$LOID =  $CList->loa_pg_id;
// 	}
// }

$PgdetailQuery = "SELECT * FROM bg_fdr_details WHERE master_id = '$MastId' AND inst_purpose='SD'";
$SelectSql 	 	= mysqli_query($dbConn,$PgdetailQuery);	
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		while($List = mysqli_fetch_array($SelectSql)){
		    $SDId               =      $List['master_id'];
			$SDDTID             =     $List['bfdid'];
	        $SDtype             =      $List['inst_type'];	//echo $SDDTID;exit;
			$BankName           =     $List['inst_bank_name'];
			$BankSerial         =      $List['inst_serial_no'];
			$SDDate             =     $List['inst_date'];
			$SDDate1            =     dt_display($SDDate);
			$SDExpDate          =     $List['inst_exp_date'];
			$SDExpDate1         =     dt_display($SDExpDate);
			$SDExtenDate        =     $List['inst_ext_date'];
			$SDExtenDate1       =     dt_display($SDExtenDate);
			$SDCreatedby        =     $List['createdby'];
			$SDCreatedsess      =     $List['created_section'];
			$SDCrearedon         =     $List['createdon'];
			$SDCrearedon1        =     dt_display($SDCrearedon);
			$SDamt               =     $List['inst_amt'];
			$SDamt1              =     round(($SDamt),0);
			$List['inst_date']                  =  $SDDate1;
			$List['inst_exp_date']              =  $SDExpDate1;
			$List['inst_amt']                   =  $SDamt1;
			$List['inst_ext_date']              =  $SDExtenDate1;
			$List['createdon']                  =  $SDCrearedon1; 

			$Result1[] = $List;
		}

	}
}
echo json_encode($Result1);
?> 