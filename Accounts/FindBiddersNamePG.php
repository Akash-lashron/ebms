<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
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
$CCnoEnt	 = $_POST['CCnoVal'];
$PgConfCheck = 0;
$OutputArr = array();
$SelectWorkQuery = "SELECT globid,ccno,work_name FROM works WHERE ccno = '$CCnoEnt' AND active = 1";
//echo $SelectWorkQuery;exit;
$SelectWorkQuerySql = mysqli_query($dbConn,$SelectWorkQuery);
if($SelectWorkQuerySql == true){
	if(mysqli_num_rows($SelectWorkQuerySql)>0){
		$WrkList = mysqli_fetch_object($SelectWorkQuerySql);
		$CCNO 	= $WrkList->ccno;
		$GlobID 	= $WrkList->globid;
		$WorkName 	= $WrkList->work_name;
		if($GlobID != null){
			$SelectQuery1 = "SELECT loi_entry.*,contractor.name_contractor FROM loi_entry JOIN contractor ON loi_entry.contid = contractor.contid WHERE globid = '$GlobID'";
			$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					$List = mysqli_fetch_object($SelectSql1);
					$TenderID = $List->tr_id;
					$LOID 	 = $List->loa_pg_id;
					$ContId   = $List->contid;
					$ContName = $List->name_contractor;
					$LoaNo    = $List->loa_no;
					$LoaDate  = dt_display($List->loa_dt);
					$Pgper    = $List->pg_per;
					$PgAmt	 = round(($List->pg_amt),0);
					//$OutputArr[] = $List;
				}
			}
			if($LOID != null){
				$PgdetailQuery = "SELECT * FROM bg_fdr_details WHERE master_id = '$LOID' AND inst_purpose = 'PG'";
				$SelectSql 	 	= mysqli_query($dbConn,$PgdetailQuery);	
				if($SelectSql == true){
					if(mysqli_num_rows($SelectSql)>0){
						$OutputArr['contid'] 	= $ContId;
						$OutputArr['TenderID'] 	= $TenderID;
						$OutputArr['name_contractor'] = $ContName;
						$OutputArr['master_id'] = $LOID;
						$OutputArr['loa_no'] = $LoaNo;
						$OutputArr['loa_dt'] = $LoaDate;
						$OutputArr['pg_per'] = $Pgper;
						$OutputArr['pg_amt'] = $PgAmt;
						$OutputArr['WorkName'] = $WorkName;
						while($List1 = mysqli_fetch_array($SelectSql)){
							//echo 1;
							$PgId          =	$List1['master_id'];
							$PgDTID        =  $List1['bfdid'];
							$Pgtype        =  $List1['inst_type'];
							$BankName      =  $List1['inst_bank_name'];
							$BankSerial    =  $List1['inst_serial_no'];
							$PgDate        =  $List1['inst_date'];
							$PgDate1       =	dt_display($PgDate);
							$PgExpDate     = 	$List1['inst_exp_date'];
							$PgExpDate1    = 	dt_display($PgExpDate);
							$PgExtenDate   =	$List1['inst_ext_date'];
							$PgExtenDate1  = 	dt_display($PgExtenDate);
							$PgCreatedby   =	$List1['createdby'];
							$PgApprovSess	=	$List1['approved_session'];
							if($PgApprovSess == 'ACC'){
								$PgConfCheck = 1;
							}
							$PgCreatedsess =	$List1['created_section'];
							$PgCrearedon   =	$List1['createdon'];
							$PgCrearedon1  =	dt_display($PgCrearedon);
							$Pgamt         =	$List1['inst_amt'];
							$Pgamt1        =	round(($Pgamt),0);
							$List1['inst_date']     =  $PgDate1;
							$List1['inst_exp_date'] =  $PgExpDate1;
							$List1['inst_amt']      =  $Pgamt1;
							$List1['inst_ext_date'] =  $PgExtenDate1;
							$List1['createdon']     =  $PgCrearedon1; 
							$OutputArr['BankData'][] = $List1;
						}
						$OutputArr['ApprovStat']  =  $PgConfCheck; 
					}
				}
			}
		}
	}
}
echo json_encode($OutputArr);
?>
