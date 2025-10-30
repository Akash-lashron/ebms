<?php	
	/*mysqli_select_db($dbName2,$conn); /// $dbName2 = 'ecms_sequence';
	$sql_ref_id		= "SELECT nextval_datasheet_master('ref_id') as next_sequence";
	$rs_ref_id		= mysqli_query($dbConn,$sql_ref_id,$conn);
	$ref_id			= mysqli_result($rs_ref_id,0,'next_sequence');
	mysqli_select_db($dbName,$conn); /// $dbName2 = 'ecms';*/
	$Edit = $_POST['txt_edit'];
	if($Edit != ""){
		//$DeleteQuery1 = "DELETE FROM datasheet_master WHERE ref_id = '$Edit'";
		//$DeleteSql1   = mysqli_query($dbConn,$DeleteQuery1);
		$DeleteQuery2 = "DELETE FROM datasheet_a1_details WHERE ref_id = '$Edit'";
		$DeleteSql2   = mysqli_query($dbConn,$DeleteQuery2,$conn);
	}
	if($DSType == ""){ /// REFER common.php => declaration.php
		$DSType = "MD"; /// Other Data Sheet Create
	}else{
		$DSType = "SD"; /// Sub Data Create
	}
	$GroupList 		= $_POST['cmb_group'];
	$GroupDesc 		= $_POST['txt_group_desc'];
	$CalcType 		= $_POST['calc_type'];
	$CostDt 		= $_POST['txt_cost_det'];
	$GroupCnt 		= count($GroupList);
	$GroupId 		= $GroupList[$GroupCnt-1];
	if($GroupCnt > 0){
		foreach($GroupList as $GrKey => $GrID){
			if(($GrID != "")&&($GrID != "Select")){
				$group_id		= $GrID;
			}
		}
	}
	//$group_id		= $GroupId;//$_POST['cmb_group3'];
	//$group3_desc	= $GroupDesc;//$_POST['txt_group3_desc'];
	$IsDispQty 		= $_POST['disposal_qty'];
	if($IsDispQty == 'Y'){
		$DispQtyPerc = $_POST['txt_disp_qty_prec'];
	}else{
		$DispQtyPerc = 0;
	}
	$ToUnit 		= $_POST['hid_to_unit'];
	$FinalUnit 		= $_POST['cmb_final_unit'];
	$SelectIdQuery 	= "select group_desc, id, par_id, type from group_datasheet where id = '$group_id'";
	$SelectIdSql 	= mysqli_query($dbConn,$SelectIdQuery);
	if($SelectIdSql == true){
		if(mysqli_num_rows($SelectIdSql)>0){
			$IDList = mysqli_fetch_object($SelectIdSql);
			$ID 	= $IDList->id;
			$PAR_ID = $IDList->par_id;
			$TYPE 	= $IDList->type;
			$DESC 	= $IDList->group_desc;
		}
	}
	$group3_desc	= $DESC;
	if($_POST['rad_type'] == 'N'){
		$UpdateQuery1 	= "update datasheet_master set group_id = '', group3_description = '', group4_description = '', quantity = '', unit = '', id = '', par_id = '', type = '',
						   new_merge = '', calc_type = '', cost_dt = '', ds_type = '', disp_qty_perc = '', to_unit = '', final_unit = '' where ref_id = '$Edit'";
		$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1,$conn);
		
		$UpdateQuery2 	= "update datasheet_master set group_id = '$group_id', group3_description = '$group3_desc', group4_description = '".$_POST['txt_group4_desc']."', 
						   quantity = '".$_POST['txt_cost']."', unit = '".$_POST['cmb_unit']."', id = '$ID', par_id = '$PAR_ID', type = '$TYPE',
						   new_merge = 'N', calc_type = '$CalcType', cost_dt = '$CostDt', ds_type = '$DSType', disp_qty_perc = '$DispQtyPerc', to_unit = '$ToUnit', 
						   final_unit = '$FinalUnit' where ref_id = '$Edit'";
		$UpdateSql2 	= mysqli_query($dbConn,$UpdateQuery2,$conn);
		
		/*$sql_insert	= "insert into datasheet_master(group_id,group3_description,group4_description,quantity,unit,id,par_id,type,new_merge,calc_type,cost_dt,ds_type,disp_qty_perc,to_unit,final_unit)
						values('" . $group_id . "' ,
							   '" . $group3_desc . "' ,
							   '" . $_POST['txt_group4_desc'] . "' ,
							   '" . $_POST['txt_cost'] . "' ,
							   '" . $_POST['cmb_unit'] . "' ,
							   '" . $ID . "' ,
							   '" . $PAR_ID . "' ,
							   '" . $TYPE . "' ,
							   'N',
							   '" . $CalcType . "' ,
							   '" . $CostDt . "' , 
							   '" . $DSType . "' ,
							   '" . $DispQtyPerc . "' ,
							   '" . $ToUnit . "' ,
							   '" . $FinalUnit . "'
							   )";
		$rs_insert	= mysqli_query($dbConn,$sql_insert,$conn);*/
		$ref_id = $Edit;
		if($UpdateSql2 == true){
			$tmp1 = 1;
		}else{
			$tmp1 = 0;
		}
		
		$ItemCodeArr 		= $_POST['txt_code']; 
		$ItemIdArr 			= $_POST['txt_item_id'];
		$ItemQtyArr 		= $_POST['txt_quantity'];
		$ItemDescArr 		= $_POST['txt_desc'];
		
		$CalcDescArr 		= $_POST['txt_curr_calc_desc'];
		$CurrQtyDescArr 	= $_POST['txt_curr_qty_desc_alt'];
		$CurrItemAltDescArr = $_POST['txt_curr_item_desc_alt']; 
		$CurrActArr 		= $_POST['txt_curr_action'];
		$CurrFactArr 		= $_POST['txt_curr_factor'];
		
		$CalcTypeArr 		= $_POST['hid_calc_type'];
		$AmtTypeArr 		= $_POST['hid_amt_type'];
		$CurrTitleArr 		= $_POST['txt_curr_title'];
		
		$RefIdArr 		= $_POST['hid_ref_id'];
		$tmp2 = 0; $tmpX = 0; $tmpY = 0;
		for($c=0;$c<count($ItemCodeArr);$c++){
			$ItemCode 		= $ItemCodeArr[$c];
			$ItemId 		= $ItemIdArr[$c];
			$ItemDesc 		= $ItemDescArr[$c];
			$CalcDesc 		= $CalcDescArr[$c];
			$RefId 			= $RefIdArr[$c];
			$CurrTitle 		= $CurrTitleArr[$c];
			if($ItemId != ""){
				$Type = "I"; /// This is Item from Item Master
				$MergeItemCode = '';
				$MergeItemRefid = '';
			}else{
				$Type = "D"; /// This is Data Sheet from Data Sheet Master
				$MergeItemCode = $ItemCode;
				$MergeItemRefid = $RefId;
			}
			
			$CurrQtyDesc 	= $CurrQtyDescArr[$c];
			$CurrItemAltDesc= $CurrItemAltDescArr[$c];
			$CurrAct 		= $CurrActArr[$c];
			$CurrFact 		= $CurrFactArr[$c];
			
			$CalcType 		= $CalcTypeArr[$c];
			$AmtType 		= $AmtTypeArr[$c];
			
			if($ItemCode != ""){
				$tmpX++;
				$ItemQty 	= $ItemQtyArr[$c];
				$x		= $a1_rec[$c];
				$sno	= $c+1;
				$sql_insert_details = "insert into datasheet_a1_details(sno,ref_id,item_ds_type,item_id,title,item_desc,calc_desc,qty_desc,item_alt_desc,calc_actions,actions_factors,calc_type,amt_type,quantity,merge_item_code,merge_ref_id,SI,new_merge)
							  values('" . $sno . "' ,
									 '" . $ref_id . "' ,
									 '" . $Type . "' ,
									 '" . $ItemId . "' ,
									 '" . $CurrTitle . "' ,
									 '" . $ItemDesc . "' ,
									 '" . $CalcDesc . "' ,
									 '" . $CurrQtyDesc . "' ,
									 '" . $CurrItemAltDesc . "' ,
									 '" . $CurrAct . "' ,
									 '" . $CurrFact . "' ,
									 '" . $CalcType . "' ,
									 '" . $AmtType . "' ,
									 '" . $ItemQty . "' ,
									 '" . $MergeItemCode . "' ,
									 '" . $MergeItemRefid . "' ,
									 '" . $_POST['cmb_SI'.$x] . "' ,
									 'N')";
				$rs_insert_details = mysqli_query($dbConn,$sql_insert_details,$conn);
				//echo $sql_insert_details ."<br/>";
				if($rs_insert_details == true){
					$tmpY++;
				}
			}
		}
	}else{
		$UpdateQuery1 	= "update datasheet_master set group_id = '', group3_description = '', group4_description = '', quantity = '', unit = '', id = '', par_id = '', type = '',
						   new_merge = '', calc_type = '', is_average = '', cost_dt = '', ds_type = '', disp_qty_perc = '', to_unit = '', final_unit = '' where ref_id = '$Edit'";
		$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1,$conn);
		
		$UpdateQuery2 	= "update datasheet_master set group_id = '$group_id', group3_description = '$group3_desc', group4_description = '".$_POST['txt_group4_desc']."', 
						   quantity = '".$_POST['txt_cost']."', unit = '".$_POST['cmb_unit']."', id = '$ID', par_id = '$PAR_ID', type = '$TYPE',
						   new_merge = 'M', calc_type = '$CalcType', is_average = '".$_POST['is_average']."', cost_dt = '$CostDt', ds_type = '$DSType', 
						   disp_qty_perc = '$DispQtyPerc', to_unit = '$ToUnit', final_unit = '$FinalUnit' where ref_id = '$Edit'";
		$UpdateSql2 	= mysqli_query($dbConn,$UpdateQuery2,$conn);
		
		/*$sql_insert	= "insert into datasheet_master(ref_id,group_id,group3_description,group4_description,quantity,unit,id,par_id,type,new_merge,calc_type,is_average,cost_dt,ds_type,disp_qty_perc,to_unit,final_unit)
						values('" . $ref_id . "' ,
							   '" . $group_id . "' ,
							   '" . $group3_desc . "' ,
							   '" . $_POST['txt_group4_desc'] . "' ,
							   '" . $_POST['txt_cost'] . "' ,
							   '" . $_POST['cmb_unit'] . "' ,
							   '" . $ID . "' ,
							   '" . $PAR_ID . "' ,
							   '" . $TYPE . "' ,
							   'M', 
							   '" . $CalcType . "' ,
							   '" . $_POST['is_average'] . "' ,
							   '" . $CostDt . "' , 
							   '" . $DSType . "' ,
							   '" . $DispQtyPerc . "' ,
							   '" . $ToUnit . "' ,
							   '" . $FinalUnit . "'
							   )";
		$rs_insert	= mysqli_query($dbConn,$sql_insert,$conn);*/
		$ref_id = $Edit;
		if($UpdateSql2 == true){
			$tmp1 = 1;
		}else{
			$tmp1 = 0;
		}
		
		$ItemIdArr 			= $_POST['txt_item_id_sd'];
		$RefIdArr 			= $_POST['txt_refid_sd'];
		$ItemDescArr 		= $_POST['txt_desc_sd'];
		$CalcDescArr 		= $_POST['txt_curr_calc_desc_sd'];
		
		$CurrItemAltDescArr = $_POST['txt_curr_item_desc_alt_sd']; 
		$CurrActArr 		= $_POST['txt_curr_action_sd'];
		$CurrFactArr 		= $_POST['txt_curr_factor_sd'];
		$CurrTitleArr 		= $_POST['txt_curr_title_sd'];
		
		//$RefIdArr 			= $_POST['hid_ref_id_sd'];
		//print_r($ItemIdArr);exit;
		$tmp2 = 0; $tmpX = 0; $tmpY = 0;
		for($c=0;$c<count($ItemIdArr);$c++){
			$ItemId 		= $ItemIdArr[$c];
			$RefId 			= $RefIdArr[$c];
			$ItemDesc 		= $ItemDescArr[$c];
			$CalcDesc 		= $CalcDescArr[$c];
			$CurrItemAltDesc= $CurrItemAltDescArr[$c];
			$CurrAct 		= $CurrActArr[$c];
			$CurrFact 		= $CurrFactArr[$c];
			$CurrTitle 		= $CurrTitleArr[$c];
			//echo $RefId."<br/>";
			if((ItemId != "")&&($RefId != "")){
				$tmpX++;
				$sno = $c+1;
				$sql_insert_details = "insert into datasheet_a1_details(sno,ref_id,item_ds_type,item_id,title,item_desc,calc_desc,item_alt_desc,calc_actions,actions_factors,quantity,SI,merge_item_code,merge_ref_id,new_merge)
							  values('" . $sno . "' ,
									 '" . $ref_id . "' ,
									 'D' ,
									 '' ,
									 '" . $CurrTitle . "' ,
									 '" . $ItemDesc . "' ,
									 '" . $CalcDesc . "' ,
									 '" . $CurrItemAltDesc . "' ,
									 '" . $CurrAct . "' ,
									 '" . $CurrFact . "' ,
									 '' ,
									 '" . $_POST['cmb_SI'.$x] . "' ,
									 '" . $ItemId . "' ,
									 '" . $RefId . "' ,
									 'M')";
									 
				$rs_insert_details = mysqli_query($dbConn,$sql_insert_details,$conn);
				if($rs_insert_details == true){
					$tmpY++;
				}
			}
		}
	}//exit;
	if(($tmp1 == 1)&&($tmpX == $tmpY)){
		$msg = "Data Sheet Created Successfully.";
	}else{
		$msg = "Error: Data Sheet not Created. Please Try Again.";
	}
?>