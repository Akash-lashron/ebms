<?php
session_start();
@ob_start();
require_once 'library/config.php';
$mbno=$_POST[mbno];
$Exist = 0;
$SelectMBNoQuery = "select mbno from agreementmbookallotment where mbno = '$mbno'";// AND sheetid='$workorderno'"; 
$SelectMBNoSql	 = mysql_query($SelectMBNoQuery);
if($SelectMBNoSql == true){
	
	if(mysql_num_rows($SelectMBNoSql)>0){
		$Exist = 1;
		$MBNOList = mysql_fetch_object($SelectMBNoSql);
		$MBNO = $MBNOList->mbno;
	}
}
echo $Exist;
?>