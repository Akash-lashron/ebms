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
$MastId		= $_POST['Id'];
//$SelectTSQuery = "SELECT * FROM mob_master where sheetid = '$MastId'";
//$SelectTSQuery = "SELECT a.*,b.*,c.amt_rec FROM mob_master a INNER JOIN bg_fdr_details b ON (a.mob_adv_no =  b.mob_adv_no) INNER JOIN mob_adv_rec c ON (c.mobmid =  a.mobmid) WHERE a.sheetid = '$MastId' AND b.inst_purpose='MOB'";
$SelectTSQuery = "SELECT a.*,b.* FROM mob_master a INNER JOIN bg_fdr_details b ON (a.mob_adv_no =  b.mob_adv_no) WHERE a.sheetid = '$MastId' AND b.inst_purpose='MOB'";
//echo $SelectTSQuery;
$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
if($SelectTSSql == true){
	if(mysqli_num_rows($SelectTSSql)>0){
		while($List = mysqli_fetch_array($SelectTSSql)){
				$PgExpDate        =   $List['createdon'];
				$PgExpDate1       =   dt_display($PgExpDate);
				$List['showcreatedon']  =   $PgExpDate1;
				$Result1[] = $List;
         }
		}
     }

           
			
	

$rows = array('row1'=>$Result1);

echo json_encode($rows);
?> 