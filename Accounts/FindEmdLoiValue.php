<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
$TrId 	= $_POST['TrId'];
$BidderId 	= $_POST['BidderId'];
$Page 	 	= $_POST['Page'];
$OutputArr = array(); 
$TotalValue = 0; $PgAmount = 0;
if($Page == "LOI"){
	$SelectQuery 	= "select * from bidder_bid_master where tr_id = '$TrId' and contid='$BidderId'";
	$SelectSql 	= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			while($List= mysqli_fetch_object($SelectSql)){
				$BidMastId = $Result->bmid;
				$ContId = $List->contid;
				$IsNego = $List->is_negotiate;
				$QuoteAmt = $List->quoted_amt;
				$RebatePerc = $List->rebate_perc;
				$RebateAmt = $QuoteAmt * $RebatePerc / 100;
				$TotalQuote = round(($QuoteAmt - $RebateAmt),2);
				$NegoRebatePerc = $List->negotiate_rebate_perc;
				$NegRebateAmt = $TotalQuote * $NegoRebatePerc / 100;
				$TotalamtafterNeg= round(($TotalQuote - $NegRebateAmt),2);
				if($IsNego == 'Y'){
					$TotalValue=  $TotalamtafterNeg;
				}else{
					$TotalValue = $TotalQuote;
					}
					
			 }
			 
		}
	
	}	
	$PgPercent = 0;
	$SelectQuery1 	= "select pg_per from tender_register where tr_id = '$TrId'";
	$SelectSql1 	= mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$Result1 = mysqli_fetch_object($SelectSql1);
			$PgPercent = $Result1->pg_per;		
		}
	}
	$PgAmount = round(($TotalValue * $PgPercent / 100),0);
	
	
}

//print_r( $PgAmount);exit;
$OutputArr['bid_amt'] = $TotalValue;
$OutputArr['pg_amt'] = $PgAmount;
$OutputArr['pg_per'] = $PgPercent;
echo json_encode($OutputArr);
?>
