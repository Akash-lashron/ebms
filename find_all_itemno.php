<?php
/*require_once 'library/config.php';
$sheetid 	= $_GET['sheetid'];
$OutPutStr = "";
//$selectQuery = "select sno, subdiv_id, decimal_placed, rate, description, shortnotes, per from schdule where sheet_id = '$sheetid' and subdiv_id != 0";
$selectQuery = "select sno, subdiv_id, decimal_placed, rate, description, shortnotes, per from schdule where sheet_id = '$sheetid' and subdiv_id != 0 and total_quantity != 0 ORDER BY CAST(sno AS UNSIGNED) ASC";
$selectSql = mysql_query($selectQuery);
if($selectSql == true){
	while($List = mysql_fetch_object($selectSql)){
		if($List->shortnotes != ""){
			$desc = $List->shortnotes;
		}else{
			$desc = $List->description;
		}
		$OutPutStr .= $List->subdiv_id."@#*#@".$List->sno."@#*#@".$List->rate."@#*#@".$List->decimal_placed."@#*#@".$List->per."@#*#@".$desc."@#*#@";
	}
	$OutPutStr = rtrim($OutPutStr ,"@#*#@");
}
echo $OutPutStr;*/
require_once 'library/config.php';
$sheetid 	= $_GET['sheetid'];
$OutPutStr = "";
$selectQuery = "select sno, subdiv_id, decimal_placed, rate, description, shortnotes, per, base_rate, total_quantity, deviate_qty_percent from schdule where sheet_id = '$sheetid' and subdiv_id != 0";
$selectSql = mysql_query($selectQuery);
if($selectSql == true){
	while($List = mysql_fetch_object($selectSql)){
		if($List->shortnotes != ""){
			$desc = $List->shortnotes;
		}else{
			$desc = $List->description;
		}
		$ItemQty 	 = $List->total_quantity;
		$DeviatePerc = $List->deviate_qty_percent;
		$DeviateQty  = round(($ItemQty * $DeviatePerc /100),2);
		$TotalItemQty = round(($ItemQty + $DeviateQty),2);
		
		$OutPutStr .= $List->subdiv_id."@#*#@".$List->sno."@#*#@".$List->rate."@#*#@".$List->decimal_placed."@#*#@".$List->per."@#*#@".$desc."@#*#@".$List->base_rate."@#*#@".$TotalItemQty."@#*#@";
	}
	$OutPutStr = rtrim($OutPutStr ,"@#*#@");
}
echo $OutPutStr;
?>