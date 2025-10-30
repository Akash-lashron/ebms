<?php
require_once '../library/config.php';;
//Total_amt= "";
$SaSdId= $_POST['id'];
$Deleted = 0;
$SelectUploadQuery 	= "select * from send_acc_supp_doc where sasdid = '$SaSdId'";
$SelectUploadSql 	= mysql_query($SelectUploadQuery);
if($SelectUploadSql == true){
	if(mysql_num_rows($SelectUploadSql)>0){
		$UpList 	= mysql_fetch_object($SelectUploadSql);
		$DocName 	= $UpList->doc_name;
		$DeleteDir 	= "BillSupportingDoc/".$DocName;
		if(file_exists($DeleteDir)){ unlink($DeleteDir); }
		$DeleteQuery = "DELETE FROM send_acc_supp_doc WHERE sasdid = '$SaSdId'";
		$DeleteSql 	 = mysql_query($DeleteQuery);
		if($DeleteSql == true){
			$Deleted = 1;
		}
	}
}

echo $Deleted;