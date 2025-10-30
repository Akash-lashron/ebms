<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
$getmaxdate = dt_format($_POST['getmaxdate']);
$getfromdate = dt_format($_POST['getfromdate']);
$workorderno = $_POST['wordorderno'];
//$sql2="SELECT max(todate) FROM measurementbook WHERE sheetid =".$_GET['sheetid'];
$sql2="SELECT * FROM mbookheader WHERE  sheetid = '$workorderno' AND date >= '$getmaxdate' AND date <= '$getfromdate' AND mbheader_flag
 != 'd' AND active = '1'";
$rs2 = mysql_query($sql2);
if(mysql_num_rows($rs2)>0)
{ echo "1"; }
else
{ echo "0"; }

?>
