<?php
require_once 'library/config.php';
$pagesno=0;
/*$select_rbn_query	=	"SELECT DISTINCT rbn FROM measurementbook_temp where sheetid='" . $_GET['sheetid'] . "'";
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
		$empty_page_update_sql = "select emptypage from mymbook where sheetid = '" . $_GET['sheetid'] . "' and mtype = 'A' and rbn = '$RBN' and genlevel = 'abstract'";
		$empty_page_update_query = mysql_query($empty_page_update_sql);
		if($empty_page_update_query == true)
		{
			if(mysql_num_rows($empty_page_update_query) == 1)
			{
				$EPageList = mysql_fetch_object($empty_page_update_query);
				$EPage = $EPageList->emptypage;
			}
		}
		$DataList = $RBN."*".$EPage;
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
}*/
$PinOutPutList = "";
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

?>
