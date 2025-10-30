<?php 
$SuppRebateArr = array();
$SupAgmtRebateQuery = "select * from sheet_supplementary where sheetid = '$abstsheetid'";
$SupAgmtRebateSql = mysql_query($SupAgmtRebateQuery);
if($SupAgmtRebateSql == true){
	if(mysql_num_rows($SupAgmtRebateSql)>0){
		while($SUPRbateList = mysql_fetch_object($SupAgmtRebateSql)){
			$SuppRebateArr[$SUPRbateList->supp_sheet_id] = $SUPRbateList->rebate_percent;
		}
	}
}
?>