<?php
if($PostForm  == 1){ 
	$PuId = "";
	$SelectQuery1 = "select * from pu_master where is_confirmed != 'Y' and puid = (select max(a.puid) from pu_master a)";
	$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$List1 	= mysqli_fetch_object($SelectSql1);
			$PuId 	= $List1->puid;
		}
	}
	//echo $PuId;exit;
	if($PuId != ""){
		$SelectQuery2 	= "select * from item_master where active = 1";
		$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
		if($SelectSql2 == true){
			if(mysqli_num_rows($SelectSql2)>0){
				while($List2 = mysqli_fetch_object($SelectSql2)){
					$ItemDesc 	 	= mysqli_real_escape_string($dbConn,$List2->item_desc);
					$Description 	= mysqli_real_escape_string($dbConn,$List2->description);
					$InsertQuery1 	= "insert into pru_detail set puid = '$PuId', item_id = '$List2->item_id', item_id_1 = '$List2->item_id_1', item_code = '$List2->item_code', item_desc = '$ItemDesc', description = '$Description', par_id = '$List2->par_id', item_type = '$List2->item_type', unit = '$List2->unit', price = '$List2->price', valid_from = '$List2->valid_from', valid_to = '$List2->valid_to', active = 1";
					$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
				}
			}
		}
		
		$SelectQuery3 	= "select * from default_master";
		$SelectSql3 	= mysqli_query($dbConn,$SelectQuery3);
		if($SelectSql3 == true){
			if(mysqli_num_rows($SelectSql3)>0){
				while($List3 = mysqli_fetch_object($SelectSql3)){
					$TaxDesc 	 	= mysqli_real_escape_string($dbConn,$List3->de_name);
					$InsertQuery2 	= "insert into pdm_detail set puid = '$PuId', de_id = '$List3->de_id', de_name = '$TaxDesc', de_perc = '$List3->de_perc', de_code = '$List3->de_code', valid_from = '$List3->valid_from'";
					$InsertSql2 	= mysqli_query($dbConn,$InsertQuery2);
				}
			}
		}
		
		include "PrevSORUpdate.php";
		
		$SelectQuery4 	= "select * from item_master_temp where active = 1";
		$SelectSql4 	= mysqli_query($dbConn,$SelectQuery4);
		if($SelectSql4 == true){
			if(mysqli_num_rows($SelectSql4)>0){
				while($List4 = mysqli_fetch_object($SelectSql4)){
					$UpdateQuery1 	= "update item_master set price = '$List4->price', valid_from = '$List4->valid_from' where item_id = '$List4->item_id'";
					$UpdatetSql1 	= mysqli_query($dbConn,$UpdateQuery1);
				}
			}
		}
		
		$SelectQuery5 	= "select * from default_master_temp";
		$SelectSql5 	= mysqli_query($dbConn,$SelectQuery5);
		if($SelectSql5 == true){
			if(mysqli_num_rows($SelectSql5)>0){
				while($List5 = mysqli_fetch_object($SelectSql5)){
					$UpdateQuery2 	= "update default_master set de_perc = '$List5->de_perc', valid_from = '$List5->valid_from' where de_id = '$List5->de_id'";
					$UpdatetSql2 	= mysqli_query($dbConn,$UpdateQuery2);
				}
			}
		}
		
		$UpdateQuery3 	= "update pu_master set is_confirmed = 'Y' where puid = '$PuId'";
		$UpdateSql3 	= mysqli_query($dbConn,$UpdateQuery3);
		
		$DeleteQuery1	= "TRUNCATE TABLE item_master_temp";
		$DeleteSql1 	= mysqli_query($dbConn,$DeleteQuery1);
		
		$DeleteQuery2 	= "TRUNCATE TABLE default_master_temp";
		$DeleteSql2 	= mysqli_query($dbConn,$DeleteQuery2);
		if($UpdateQuery3 == true){
			$msg = "Item Rate and Taxes Overheads confirmed successfully";
		}else{
			$msg = "Item Rate and Taxes Overheads not confirmed. Please try again";
		}
	}
	$Confirm = 1;
}
?>