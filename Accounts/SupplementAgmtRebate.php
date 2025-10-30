<?php 
$SuppRebateArr = array();
$SupAgmtRebateQuery = "select * from sheet_supplementary where sheetid = '$abstsheetid'";
$SupAgmtRebateSql = mysqli_query($dbConn,$SupAgmtRebateQuery);
if($SupAgmtRebateSql == true){
	if(mysqli_num_rows($SupAgmtRebateSql)>0){
		while($SUPRbateList = mysqli_fetch_object($SupAgmtRebateSql)){
			$SuppRebateArr[$SUPRbateList->supp_sheet_id] = $SUPRbateList->rebate_percent;
		}
	}
}
?>