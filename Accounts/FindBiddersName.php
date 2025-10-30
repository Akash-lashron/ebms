<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
$MastId      = $_POST['MastId'];
$QuotedAmt =0;
$ContractArr = array(); $NegContractArr = array(); $TempCont = 0; $TempAmt = 0;
$SelectQuery1 = "select * from bidder_bid_master where tr_id = '$MastId'";
$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1)>0){
		while($List = mysqli_fetch_object($SelectSql1)){
			$ContId = $List->contid;
			$IsNego = $List->is_negotiate;
			$QuoteAmt = $List->quoted_amt;
			$RebatePerc = $List->rebate_perc;
			$NegoRebatePerc = $List->negotiate_rebate_perc;
			$RebateAmt = $QuoteAmt * $RebatePerc / 100;
			$TotalQuote = round(($QuoteAmt - $RebateAmt),2);
			$NegRebateAmt = $TotalQuote * $NegoRebatePerc / 100;
			$TotalamtafterNeg= round(($TotalQuote - $NegRebateAmt),2);
			//$NegoRebatePerc = $List->negotiate_rebate_perc;
			//$TotalamtafterNeg = $List->quoted_amt_af_neg;
			$ContName = ""; $ListArr = array();
			$SelectQuery2 = "select name_contractor from contractor where contid = '$ContId'";
			$SelectSql2 = mysqli_query($dbConn,$SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					$List2 = mysqli_fetch_object($SelectSql2);
					$ListArr['contid'] = $ContId;
					$ListArr['contname'] = $List2->name_contractor;
				}
			}
			$ContractArr[] = $ListArr;
			if($IsNego == 'Y'){
				$NegContractArr[] = $ListArr;
				$BidNegL1 = $ContId;
				$Totalnegamt=  $TotalamtafterNeg;
			}
			if($TempAmt == 0){
				$BidL1 = $ContId;
			}else{
				$QuoteAmt = $List->quoted_amt;
				$RebatePerc = $List->rebate_perc;
				$RebateAmt = $QuoteAmt * $RebatePerc / 100;
				$TotalQuote = round(($QuoteAmt - $RebateAmt),2);
				if($TotalQuote < $TempAmt){
					$BidL1 = $ContId;
					$QuotedAmt= $TotalQuote;
					//echo $QuotedAmt; exit;
				}
			}
			$TempAmt = $TotalQuote;
		}
	}
}
if(count($NegContractArr)>0){
   $ContArr = $NegContractArr;
   $ContL1  =  $BidNegL1;
   $QuotedAmt= $Totalnegamt;
}else{
   $ContArr = $ContractArr;
   $ContL1 = $BidL1;
   $QuotedAmt= $QuotedAmt;
}
$ResultArr = array('ContArr'=>$ContArr,'ContL1'=>$ContL1);
echo json_encode($ResultArr);
?>