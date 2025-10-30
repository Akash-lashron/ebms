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
$Date1	= $_POST['TitleFinYear'];
$Date2	= $_POST['sp1'];
$Pin	= $_POST['Pinid'];
$month1 = 04;
$month2 = 04;

// 		$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE MONTH(payment_dt)=" . $month . " AND YEAR(payment_dt) =" . $sp ." ORDER BY payment_dt ASC;";
// 		$sql_date= "SELECT * FROM memo_payment_accounts_edit WHERE payment_dt BETWEEN '" . $fromdt . "' and '" . $todt . "' ORDER BY payment_dt ASC;";
$RowCnt = 0;
$SelectTSQuery  = "SELECT a.*,b.obj_head as ojectname FROM hoa_master a INNER JOIN object_head b ON (a.obj_head_id =  b.ohid)  WHERE a.fin_year ='" . $Date1 ."' AND a.pin_id='$Pin' ORDER BY b.ohid ASC";

$SelectTSQuery  = "SELECT a.*,b.obj_head as ojectname FROM hoa_master a LEFT JOIN object_head b ON (a.obj_head_id =  b.ohid)  WHERE (a.fin_year ='" . $Date1 ."' OR a.fin_year IS NULL) AND (a.pin_id='$Pin' OR a.pin_id IS NULL) ORDER BY b.ohid ASC";

$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);     
if($SelectTSSql == true){
	if(mysqli_num_rows($SelectTSSql)>0){
		while($List = mysqli_fetch_array($SelectTSSql)){ 
			$RowCnt = 1;
			$HoamastID       =   $List['hoamast_id'];
			$Ojectid         =   $List['ohid'];
			$OjectHead       =   $List['ojectname'];
			$OldHOA          =   $List['old_hoa_no'];
			$NewHoa          =   $List['new_hoa_no'];
			$BePropose       =   $List['be_prop_amt'];
			$BeApprove       =   $List['be_appr_amt'];
			$RePropose       =   $List['re_prop_amt'];
			$ReApprove       =   $List['re_appr_amt'];
			$Result1[] = $List;
		}
	}
}
if($RowCnt == 0){
	//$SelectTSQuery  = "SELECT a.*,b.fin_year,b.new_hoa_no FROM object_head a INNER JOIN hoa_master b ON (a.ohid =  b.obj_head_id)  WHERE b.fin_year ='" . $Date1 ."'-1 AND b.pin_id='$Pin' ORDER BY a.ohid ASC";

	$SelectTSQuery  = "SELECT a.*,b.fin_year,b.new_hoa_no FROM object_head a LEFT JOIN hoa_master b ON (a.ohid =  b.obj_head_id) WHERE (b.fin_year ='" . $Date1 ."'-1 OR b.fin_year IS NULL) AND (b.pin_id='$Pin' OR b.pin_id IS NULL) ORDER BY a.ohid ASC";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);     
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			while($List = mysqli_fetch_array($SelectTSSql)){  
				$RowCnt = 1;
				$Ojectid    = $List['ohid'];
				$OjectHead  = $List['ojectname'];
				$Hoanew     = $List['new_hoa_no'];
				$Result2[] 	= $List;
			}
		}
	}   
}
if($RowCnt == 0){
	$SelectTSQuery  = "SELECT a.*, a.obj_head as ojectname FROM object_head a ORDER BY a.ohid ASC";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);  
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			while($List = mysqli_fetch_array($SelectTSSql)){ 
				$RowCnt = 1;
				$Ojectid    = $List['ohid'];
				$OjectHead  = $List['ojectname'];
				$Hoanew     = '';//$List['new_hoa_no'];
				
				$List['new_hoa_no'] = '';
				$List['old_hoa_no'] = '';
				$Result2[] 	= $List;
			}
		}
	}   
}
$rows = array('row1'=>$Result1, 'row2'=>$Result2);

echo json_encode($rows);
?> 