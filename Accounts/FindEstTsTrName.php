<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
$EstTsTrId 	= $_POST['Id'];
$Page 	 	= $_POST['Page'];
$OutputArr = array(); 
if($Page == "TS"){
	$SelectQuery2 	= "select * from technical_sanction where ts_id = '$EstTsTrId'";
	$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 = mysqli_fetch_assoc($SelectSql2);
			$OutputArr = $List2;
		}
	}
}else if($Page == "TR"){
	$SelectQuery2 	= "select * from tender_register where tr_id = '$EstTsTrId'";
	$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 = mysqli_fetch_assoc($SelectSql2);
			$OutputArr = $List2;
		}
	}
}else if($Page == "EST"){
	$SelectQuery2 	= "select * from partab_master where mastid = '$EstTsTrId'";
	$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 = mysqli_fetch_assoc($SelectSql2);
			$OutputArr = $List2;
		}
	}
}else if($Page == "PGD"){
	$SelectQuery2 	= "select * from sheet  where sheet_id = '$EstTsTrId'";
	$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 = mysqli_fetch_assoc($SelectSql2);
			$OutputArr = $List2;
		}
	}
}else if($Page == "SD"){
	
	$SelectQuery2 	="select sheet.*, contractor.name_contractor from  sheet
						JOIN contractor ON sheet.contid = contractor.contid 
						where sheet.sheet_id = '$EstTsTrId'";
	$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 = mysqli_fetch_assoc($SelectSql2);
			$OutputArr = $List2;
		}
	}
}else if($Page == "MOB"){
	
	$SelectQuery2 	="select sheet.*, contractor.name_contractor from  sheet
						JOIN contractor ON sheet.contid = contractor.contid 
						where sheet.sheet_id = '$EstTsTrId'";
	$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 = mysqli_fetch_assoc($SelectSql2);
			$OutputArr = $List2;
		}
	}

	
}else if($Page == "TSTR"){
	$SelectQuery2 	= "select * from technical_sanction where ts_id = '$EstTsTrId'";
	$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$ListTsData = mysqli_fetch_assoc($SelectSql2);
			//$OutputArr = $List2;
			$EstId 	= $ListTsData['est_id'];
		}
	}
	// $SelectTrNumQuery 	= "select MAX( tr_no ) AS max from tender_register";
	// $SelectTrNumQuerySql 	= mysqli_query($dbConn,$SelectTrNumQuery);
	// if($SelectTrNumQuerySql == true){
	// 	if(mysqli_num_rows($SelectTrNumQuerySql)>0){
	// 		$List2 = mysqli_fetch_assoc($SelectTrNumQuerySql);
	// 		$OutputArr = $List2;
	// 	}
	// }
	$SelectPartABQuery 	= "select partA_amount from partab_master where mastid = '$EstId'";
	$SelectPartABSql 	= mysqli_query($dbConn,$SelectPartABQuery);
	if($SelectPartABSql == true){
		if(mysqli_num_rows($SelectPartABSql)>0){
			$ListpartABData = mysqli_fetch_assoc($SelectPartABSql);
			$ListpartAB = $ListpartABData['partA_amount'];
		}
	}

	$SelectEMDQuery 	= "SELECT * FROM emd_values"; 	 //"select tr_no from tender_register";
	$SelectEMDQuerySql 	= mysqli_query($dbConn,$SelectEMDQuery);
	if($SelectEMDQuerySql == true){
		if(mysqli_num_rows($SelectEMDQuerySql)>0){
			$Listemd = mysqli_fetch_object($SelectEMDQuerySql);
			// while($List2 = mysqli_fetch_object($SelectTrNumQuerySql)){
			//$LastTrNo = $Listtr->tr_no;
			// }
		}
	}
	$OutputArr["TSData"] 	= $ListTsData;
	$OutputArr["EmdData"] 	= $Listemd;
	$OutputArr["PartABData"] 	= $ListpartAB;
	//$OutputArr["Trnum"] 	= $LastTrNo;
}

echo json_encode($OutputArr);
?>
