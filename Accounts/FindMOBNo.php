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
$SheetId 	 = $_POST['Id'];
$OutputArr = array();
$MaxRbn = 0;
$SelectTSQuery = "SELECT  max(CAST(rbn AS UNSIGNED)) as max_rbn FROM abstractbook where sheetid = '$SheetId'";
//echo $SelectTSQuery; exit;
$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
if($SelectTSSql == true){
    if(mysqli_num_rows($SelectTSSql)>0){
        $CList = mysqli_fetch_object($SelectTSSql);
        $MaxRbn = $CList->max_rbn;
        if($MaxRbn == NULL){
            $MaxRbn = 0;
        }
  }
}
$MaxRbn++;

$MobAdvNo = 0;
$SelectTSQuery = "SELECT  max(CAST(mob_adv_no AS UNSIGNED)) as max_mob_no, mobmid FROM mob_master where sheetid = '$SheetId' AND amt_issused_dt != '0000-00-00' AND amt_issused_dt IS NOT NULL";
$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
if($SelectTSSql == true){
    if(mysqli_num_rows($SelectTSSql)>0){
        $CList = mysqli_fetch_object($SelectTSSql);
        $MobAdvNo = $CList->max_mob_no;
        $MobMid = $CList->mobmid ;
        if($MobAdvNo == NULL){
            $MobAdvNo = 0;
        }
  }
}
$MobAdvNo++;




// $SelectTSQuery = "select max(CAST(mob_no AS UNSIGNED)) as max_mobno, max(CAST(rbn AS UNSIGNED)) as max_rbn, mobmid, amt_issused_dt from mob_master where sheetid = '$SheetId' ";
// $SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
// if($SelectTSSql == true){
// 	if(mysqli_num_rows($SelectTSSql)>0){
// 		$CList = mysqli_fetch_object($SelectTSSql);
//         $MobMid = $CList->mobmid ;
// 		$Mobno = $CList->max_mobno;
//         $RBNno = $CList->max_rbn;
//         $Issueddate = $CList->amt_issused_dt;
//             if($Issueddate != Null){ 
//                 $Mobno = $Mobno +1;
//                 $RBNno = $RBNno +1;
//             }else{
//                 $Mobno = $Mobno;
//                 $RBNno = $RBNno;
//             } 
//             if($MobMid == Null){
//                 $Mobno =1;
//                 $SelectTSQuery = "SELECT  max(CAST(rbn AS UNSIGNED)) as max_rbn FROM abstractbook where sheetid = '$SheetId'";
//                 //echo $SelectTSQuery; exit;
//                 $SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
//                 if($SelectTSSql == true){
//                     if(mysqli_num_rows($SelectTSSql)>0){
//                         $CList = mysqli_fetch_object($SelectTSSql);
//                         $RBNno = $CList->max_rbn;
//                   }
//                }

//             }
//       }
// }


$OutputArr['mob_adv_no'] = $MobAdvNo;
$OutputArr['rbn'] = $MaxRbn;
$OutputArr['mobmid'] = $MobMid;
echo json_encode($OutputArr);
?>
