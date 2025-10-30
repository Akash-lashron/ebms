<?php
require_once 'library/config.php';
$WorkId = $_POST['WorkId'];
$WorkAbsStatus = ""; 
if($WorkId != ''){


	$Rbn = ""; $RbnStatus = ""; $BRAccStatus = ""; $BRCivilStatus = ""; $IsBillReg = 0;
	
	$SelectQuery = "select sheet_id, under_civil_sheetid from where sheet_id = '$WorkId'";
	$SelectSql   = mysql_query($SelectQuery);
	if($SelectSql == true){
		if(mysql_num_rows($SelectSql) > 0){
			$List = mysql_fetch_object($SelectSql);
			$CompoSheetId = $List->under_civil_sheetid;
			if(($CompoSheetId != 0)&&($CompoSheetId != '')&&($CompoSheetId != NULL)){
				$WorkId = $CompoSheetId;
			}
		}
	}
	
	$SelectQuery1 = "SELECT * FROM abstractbook WHERE sheetid = '$WorkId' ORDER BY rbn DESC LIMIT 1";
	$SelectSql1   = mysql_query($SelectQuery1);
	if($SelectSql1 == true){
		if(mysql_num_rows($SelectSql1) > 0){
			$List1 = mysql_fetch_object($SelectSql1);
			$RbnStatus = $List1->rab_status;
			$Rbn = $List1->rbn;
		}
	}
	//echo $SelectQuery1;exit;
	
	if(($Rbn != "")&&($Rbn != NULL)){
		$SelectQuery2 = "SELECT * FROM bill_register WHERE sheetid = '$WorkId' AND rbn = '$Rbn'";
		$SelectSql2   = mysql_query($SelectQuery2); 
		if($SelectSql2 == true){  
			if(mysql_num_rows($SelectSql2) > 0){ 
				$IsBillReg = 1;
				$List2 = mysql_fetch_object($SelectSql2);
				$BRAccStatus 	= $List2->acc_status;
				$BRCivilStatus 	= $List2->civil_status;
			}
		}
	}
	if($BRAccStatus == NULL){
		$BRAccStatus = "";
	}
	if($BRCivilStatus == NULL){
		$BRCivilStatus = "";
	}
	
	if($RbnStatus == "C"){
		//Latest RAB - $Rbn is Closed 
		$RbnStatus = "C";
	}else if($IsBillReg == 1){
		if($BRAccStatus == "C"){
			//Latest RAB - $Rbn is under process in accounts section
			$RbnStatus = "PA";
		}else if($BRAccStatus == "P"){
			//Latest RAB - $Rbn is under process in accounts section
			$RbnStatus = "PA";
		}else if($BRAccStatus == "R"){
			//Latest RAB - $Rbn is under process in user side
			$RbnStatus = "PU";
		}else if($BRCivilStatus == "C"){
			//Latest RAB - $Rbn is under process in accounts section
			$RbnStatus = "PA";
		}else if($BRCivilStatus == "C"){
			//Latest RAB - $Rbn is under process in user side
			$RbnStatus = "PU";
		}
	}else{
		if($RbnStatus == "P"){
			//Latest RAB - $Rbn is under process in user side
			$RbnStatus = "PU";
		}else{
			//No RAB Found - Allow New RAB
			$RbnStatus = "N";
			$Rbn = "N";
		}
	}
	
	
	
	/*$SelectQuery = "SELECT * FROM abstractbook WHERE sheetid = '$WorkId' ORDER BY rbn DESC LIMIT 1";
	$SelectSql   = mysql_query($SelectQuery);
	if($SelectSql == true){
		if(mysql_num_rows($SelectSql) > 0){
			$List1 = mysql_fetch_object($SelectSql);
			$WorkAbsStatus = $List1->rab_status;
			$WorkAbsRbn = $List1->rbn;
		}else{
			$WorkAbsStatus = "";
			$WorkAbsRbn = "N";
		}
	}
	if($WorkAbsStatus == "C"){
		$RbnStatus = "C";
		$LatestRbn = $WorkAbsRbn;
	}
	if($WorkAbsStatus == "P"){
		$LatestRbn = $WorkAbsRbn; 
		$SelectQuery = "SELECT * FROM bill_register WHERE sheetid = '$WorkId' AND rbn = '$WorkAbsRbn'";
		$SelectSql   = mysql_query($SelectQuery); 
		if($SelectSql == true){  
			if(mysql_num_rows($SelectSql) > 0){ 
				$List2 = mysql_fetch_object($SelectSql);
				if($List2->acc_status == "P"){
					$RbnStatus = "PA"; // Under process in Accounts
				}else if(($List2->civil_status == "C")&&($List2->acc_status == "")){
					$RbnStatus = "PA"; // Under process in Accounts
				}else if(($List2->civil_status == "C")&&($List2->acc_status == "C")){
					$RbnStatus = "PA"; // Under process in Accounts
				}else if(($List2->civil_status == "C")&&($List2->acc_status == "R")){
					$RbnStatus = "PU"; // Under process in User side
				}
			}
		}
	}
	
	if($RbnStatus == "P"){
		
	}
	
	
	
	
	if($WorkAbsStatus == "C"){
		$RbnStatus = "C";
		$LatestRbn = $WorkAbsRbn;
	}else if($WorkAbsStatus == "P"){
		$LatestRbn = $WorkAbsRbn; 
		$SelectQuery = "SELECT * FROM bill_register WHERE sheetid = '$WorkId' AND rbn = '$WorkAbsRbn'";
		$SelectSql   = mysql_query($SelectQuery); 
		if($SelectSql == true){  
			if(mysql_num_rows($SelectSql) > 0){ 
				$List2 = mysql_fetch_object($SelectSql);
				if($List2->acc_status == "P"){
					$RbnStatus = "PA"; // Under process in Accounts
				}else if(($List2->civil_status == "C")&&($List2->acc_status == "")){
					$RbnStatus = "PA"; // Under process in Accounts
				}else if(($List2->civil_status == "C")&&($List2->acc_status == "C")){
					$RbnStatus = "PA"; // Under process in Accounts
				}else if(($List2->civil_status == "C")&&($List2->acc_status == "R")){
					$RbnStatus = "PU"; // Under process in User side
				}
				exit;
			}
		}
	}else{
		$RbnStatus = "";
		$LatestRbn = "";
	}*/
}
$OutputArr = array('LastestRbn'=>$Rbn,'RbnStatus'=>$RbnStatus);
echo json_encode($OutputArr);
?>
