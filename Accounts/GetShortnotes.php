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

   $SelectTSQuery =  "SELECT * FROM shortcode_master WHERE fin_year ='" . $Date1 ."'  AND active = 1 ORDER BY shortcode_id ASC";
    //echo  $SelectTSQuery; exit;    
    $SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);     
        if($SelectTSSql == true){
            if(mysqli_num_rows($SelectTSSql)>0){
                while($List = mysqli_fetch_array($SelectTSSql)){
                    
                        $shortIDID       =   $List['shortcode_id'];
                        $Shortcode       =   $List['shortcode'];
                      
                        $Result1[] = $List;
                }
                }
            }
        //     $SelectTSQuery = "SELECT a.*,b.fin_year,b.new_hoa_no FROM object_head a INNER JOIN hoa_master b ON (a.ohid =  b.obj_head_id)  WHERE b.fin_year ='" . $Date1 ."'-1 AND b.pin_id='$Pin' ORDER BY a.ohid ASC";
        //   //echo  $SelectTSQuery; exit;  
        //     //$SelectTSQuery = "SELECT * FROM object_head ORDER BY ohid ASC";
        //     $SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);     
        //     if($SelectTSSql == true){
        //         if(mysqli_num_rows($SelectTSSql)>0){
        //             while($List = mysqli_fetch_array($SelectTSSql)){
        //                     $Ojectid         =   $List['ohid'];
        //                     $OjectHead       =   $List['ojectname'];
        //                     $Hoanew          =   $List['new_hoa_no'];
                         
        //                     $Result2[] = $List;
        //             }
        //             }
        //         }   
			

$rows = array('row1'=>$Result1);

echo json_encode($rows);
?> 