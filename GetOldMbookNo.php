<?php
session_start();
@ob_start();
require_once 'library/config.php';
 $getdata=$_POST['oldmbook'];
 $oldmbookdetails = explode('*',$getdata);
 $mbookid = $oldmbookdetails[0];
 $mbookno = $oldmbookdetails[1];
 $mbooktype = $oldmbookdetails[2];
 $staffid = $oldmbookdetails[3];
 $sheetid = $oldmbookdetails[4];
 $zone_id = $oldmbookdetails[5];
 	$deletequery=mysql_query("DELETE FROM oldmbook WHERE sheetid = '$sheetid' AND mbook_type = '$mbooktype' AND zone_id = '$zone_id'");
    $insertquery=mysql_query("INSERT INTO oldmbook(old_id ,mbname,mbook_type,staffid,sheetid,zone_id) VALUES('$mbookid','$mbookno','$mbooktype','$staffid','$sheetid','$zone_id')");
   if($insertquery){
       echo "1";
    }
    else { echo "0"; }
 
?>