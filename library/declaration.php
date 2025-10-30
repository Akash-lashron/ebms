<?php
$pagetitle = ':: e-Measurement System ::';
$CHD0 = "Check Measurement is completed and ready to sent to Accounts";
$CHD1 = "Check Measurement Already Done by You and waiting for next level checking";
$CHD2 = "Check Measurement Already Done by You and Returned to previous level checking";


$CHD3 = "Previous Level Not Completed the Check Measurement";
$CHD4 = "Check Measurement Process is in Lower Level";
$CHD5 = "Check Measurement Process is in Lower Level";
//$CHD6 = "Check Measurement Already Done by You. Waiting for higher level checking";
$CHD7 = "Check Measurement Forwarded to Next Level";
$CHD8 = "Check Measurement Backawrd to Higher Level";
$CHD9 = "Check Measurement Forward to Next Level";
$CHD10 = "Check Measurement Returned to Previous Level";

$CHMStatArr = array('ER000'=>$CHD0, 'ER001'=>$CHD1, 'ER002'=>$CHD2, 'ER003'=>$CHD3, 'ER004'=>$CHD4, 'ER005'=>$CHD5, 'ER006'=>"", 'ER007'=>$CHD7, 'ER008'=>$CHD8, 'ER009'=>$CHD9, 'ER0010'=>$CHD10);

////////// FOR ACCOUNTS MBOOK VERFICATION /////////////////
// 1. Here Minimum Higher Level is AAO
// 2. Maximium Higher Level is DCA

// SO Totally Allowed Higher Level Checking is 
// a) AAO Level - Level ID - 3
// b) AO Level  - Level ID - 4
// c) DCA Level - Level ID - 5
$DecMinHighLevel = 3;
$DecMaxHighLevel = 5;
?>