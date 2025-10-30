<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
$MastId 	  = $_POST['MastId'];
$ContractArr  = array();
//echo $MastId;

$SelectQuery1 = "select distinct contid from bidder_bid_master where tr_id = '$MastId' and is_negotiate IS NULL and quoted_amt = (SELECT MIN(quoted_amt) FROM bidder_bid_master WHERE tr_id = '$MastId' AND is_negotiate IS NULL)";
$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1)>0){
		while($List = mysqli_fetch_object($SelectSql1)){
			$ContId = $List->contid;
			$ContName = ""; $List = array();
			$SelectQuery2 = "select name_contractor from contractor where contid = '$ContId'";
			$SelectSql2 = mysqli_query($dbConn,$SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					$List2 = mysqli_fetch_object($SelectSql2);
					$List['contid'] = $ContId;
					$List['contname'] = $List2->name_contractor;
				}
			}
			$ContractArr[] = $List;
		}
	}
}
echo json_encode($ContractArr);
?>
