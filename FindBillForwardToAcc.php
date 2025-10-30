<?php
require_once 'library/config.php';
$WorkId = $_POST['WorkId'];
$List = array();
if($WorkId != ''){
	$SelectQuery = "SELECT * FROM abstractbook WHERE sheetid = '$WorkId' AND rab_status = 'P'";
	$SelectSql   = mysql_query($SelectQuery);
	if($SelectSql == true){
		if(mysql_num_rows($SelectSql)){
			$List = mysql_fetch_array($SelectSql);
			$Rbn = $List['rbn'];
			$MeasGen = 0;
			$SelectQuery2 = "SELECT measurementbookid FROM measurementbook_temp WHERE sheetid = '$WorkId' AND rbn = '$Rbn' LIMIT 1";
			$SelectSql2   = mysql_query($SelectQuery2);
			if($SelectSql2 == true){
				if(mysql_num_rows($SelectSql2)>0){
					$MeasGen = 1;
				}
			}
			//echo $MeasGen;exit;
			$SaGen = 0;
			$SelectQuery3 = "SELECT said FROM secured_advance WHERE sheetid = '$WorkId' AND rbn = '$Rbn' LIMIT 1";
			$SelectSql3   = mysql_query($SelectQuery3);
			if($SelectSql3 == true){
				if(mysql_num_rows($SelectSql3)>0){
					$SaGen = 1;
				}
			}
			$EscGen = 0;
			$SelectQuery4 = "SELECT esc_id FROM escalation WHERE sheetid = '$WorkId' AND rbn = '$Rbn' LIMIT 1";
			$SelectSql4   = mysql_query($SelectQuery4);
			if($SelectSql4 == true){
				if(mysql_num_rows($SelectSql4)>0){
					$EscGen = 1;
				}
			}
			$List['MeasGen'] = $MeasGen;
			$List['SaGen'] 	 = $SaGen;
			$List['EscGen']  = $EscGen;
			$List['MobAdvGen']  = $EscGen;
		}
	}
	
	
}
echo json_encode($List);


/*$PinOutPutList = "";
$select_rbn_query	=	"SELECT DISTINCT rbn FROM measurementbook_temp where sheetid='" . $_GET['sheetid'] . "'";
$select_rbn_sql		=	mysql_query($select_rbn_query);
if($select_rbn_sql == true)
{
	if(mysql_num_rows($select_rbn_sql)>0)
	{
		//while($RBNList = mysql_fetch_object($select_rbn_sql))
		//{
			//$RBN .= $RBNList->rbn."*";
		//}
		//echo rtrim($RBN,"*");
		$RBNList = mysql_fetch_object($select_rbn_sql);
		$RBN = $RBNList->rbn;
		//$RbnData = rtrim($RBN,"*");
		$empty_page_update_sql = "select emptypage, endpage, mbno from mymbook where sheetid = '" . $_GET['sheetid'] . "' and mtype = 'A' and rbn = '$RBN' and genlevel = 'abstract' order by mbookorder desc limit 1";
		//echo $empty_page_update_sql;exit;
		$empty_page_update_query = mysql_query($empty_page_update_sql);
		if($empty_page_update_query == true)
		{
			if(mysql_num_rows($empty_page_update_query) == 1)
			{
				$EPageList = mysql_fetch_object($empty_page_update_query);
				$EPage = $EPageList->emptypage;
				$LPage = $EPageList->endpage;
				$MBNo = $EPageList->mbno;
			}
		}
		
		$SelectPOAmtQuery = "select a.slm_total_amount, a.slm_total_amount_esc, b.sec_adv_amount from abstractbook a left join secured_advance b on (a.sheetid = b.sheetid and a.rbn = b.rbn) where a.rbn = '$RBN' and a.sheetid = '".$_GET['sheetid']."'";
		$SelectPOAmtSql = mysql_query($SelectPOAmtQuery);
		if($SelectPOAmtSql == true){
			if(mysql_num_rows($SelectPOAmtSql)){
				$POAmtList = mysql_fetch_object($SelectPOAmtSql);
				$AbstractAmt = $POAmtList->slm_total_amount;
				$EscalationAmt = $POAmtList->slm_total_amount_esc;
				$SecAdvanceAmt = $POAmtList->sec_adv_amount;
				$TotalAmt = round(($AbstractAmt + $EscalationAmt + $SecAdvanceAmt),2);
			}
		}
		
		$DataList = $RBN."*".$EPage."*".$LPage."*".$MBNo."*".$PinOutPutList."*".$TotalAmt;
		echo $DataList;
	}
	else
	{
		echo 0;
		//$RBN = "";
	}
}
else
{
	echo 0;
	//$RBN = "";
}
*/
?>
