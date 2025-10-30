<?php
require_once 'library/config.php';
$sheetid 	= $_GET['sheetid'];
$OutPutStr = "";
$selectQuery = "select DISTINCT a.subdivid, a.as_agmt_wt, a.as_used_wt, a.difference_wt, a.rate, b.subdiv_name from cement_temp_variation a inner join subdivision b on (a.subdivid = b.subdiv_id) where a.sheetid = '$sheetid' and b.sheet_id = '$sheetid'";
$selectSql = mysql_query($selectQuery);
if($selectSql == true){
	while($List = mysql_fetch_object($selectSql)){
		if($List->shortnotes != ""){
			$desc = $List->shortnotes;
		}else{
			$desc = $List->description;
		}
		$OutPutStr .= $List->subdivid."@#*#@".$List->as_agmt_wt."@#*#@".$List->as_used_wt."@#*#@".$List->difference_wt."@#*#@".$List->rate."@#*#@".$List->subdiv_name."@#*#@";
	}
	$OutPutStr = rtrim($OutPutStr ,"@#*#@");
}
echo $OutPutStr;
?>
