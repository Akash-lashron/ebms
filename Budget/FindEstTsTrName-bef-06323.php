<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
$EstTsTrId 	= $_POST['Id'];
$Page 	 	= $_POST['Page'];
$OutputArr = array(); 
if($Page == "TS"){
	$SelectQuery2 	= "SELECT * FROM technical_sanction WHERE ts_id = '$EstTsTrId' AND active = 1";
	$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 = mysqli_fetch_assoc($SelectSql2);
			$EicID = $List2['eic'];
			$OutputArr['TsAMT'] 	= $List2['ts_amount'];
			$OutputArr['WROKNAME'] 	= $List2['ts_work_name'];
		}
		
	}
	$SelectQuery7	= "SELECT a.staffid,a.staffname,a.staffcode,a.email,b.designationname FROM staff a INNER JOIN designation b ON (a.designationid = b.designationid) WHERE a.staffid = '$EicID'";
		$SelectQuery7Sql 	= mysqli_query($dbConn,$SelectQuery7);
		if(mysqli_num_rows($SelectQuery7Sql)>0){
			$List8 = mysqli_fetch_array($SelectQuery7Sql);
			$OutputArr['ENGGID'] 	= $List8['staffid'];
			$OutputArr['ENGGNAME'] 	= $List8['staffname'];
			$OutputArr['ENGGICNO']  = $List8['staffcode'];
			$OutputArr['ENGGDESIG'] = $List8['designationname'];
			$OutputArr['ENGGEmail'] = $List8['email'];
		}
}elseif($Page == "TR"){
	$SelectQuery2 	= "SELECT * FROM tender_register WHERE tr_id = '$EstTsTrId' AND active = 1";
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
}elseif($Page == "EST"){
	$SelectQuery2 	= "select * from partab_master where mastid = '$EstTsTrId'";
	$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 = mysqli_fetch_assoc($SelectSql2);
			$OutputArr = $List2;
		}
	}
}elseif($Page == "WOENT"){
	$CheckVal = 0;
	$Checkpt = 0;
	$SelectTrQuery2 	= "SELECT * FROM tender_register WHERE tr_id = '$EstTsTrId' AND active = 1";
	$SelectTrQuery2Sql2 	= mysqli_query($dbConn,$SelectTrQuery2);
	if($SelectTrQuery2Sql2 == true){
		if(mysqli_num_rows($SelectTrQuery2Sql2)>0){
			$Checkpt = 1;
			$List2 = mysqli_fetch_assoc($SelectTrQuery2Sql2);
			$GlobID = $List2['globid'];
			$EicID = $List2['eic'];
			$WrkDuration = $List2['time_month'];
			$OutputArr['CCNUM']	 = $List2['ccno'];
			$OutputArr['TrRegData'] = $List2;
		}
	}
	//echo count($List2);exit;
	if($Checkpt == 1){
		$SelectQuery8 	= "SELECT * FROM sheet WHERE globid = '$GlobID'";
		//echo $SelectQuery8;
		$SelectQuery8Sql 	= mysqli_query($dbConn,$SelectQuery8);
		if($SelectQuery8Sql == true){
			if(mysqli_num_rows($SelectQuery8Sql)>0){
				$CheckVal = 1;
				$List9 = mysqli_fetch_array($SelectQuery8Sql);
				$ContId 								= $List9['contid'];
				$HoaID1 								= $List9['hoaid'];
				$ContBankDtId						    = $List9['cbdtid'];
				$SheetID 							    = $List9['sheet_id'];
				//echo $SelectQuery8;exit;
				$OutputArr['CCNUM']	 			= $List9['computer_code_no'];
				$OutputArr['CONTNAME'] 			= $List9['name_contractor'];
				$OutputArr['contid'] 			= $List9['contid'];
				$OutputArr['WORKFULLNAME']      = $List9['work_name'];
				$OutputArr['WORKSHNAME']	 	= $List9['short_name'];
				$OutputArr['AGGRENUM']	 		= $List9['agree_no'];
				$OutputArr['AGGREDATE']	 		= $List9['agree_date'];
				$OutputArr['WORKORDDATE'] 		= $List9['work_order_date'];
				$OutputArr['WORKORDNO'] 		= $List9['work_order_no'];
				$OutputArr['WORKCOMMENCDATE']   = $List9['work_commence_date'];
				$OutputArr['WORKCOMPDATE'] 	    = $List9['date_of_completion'];
				$OutputArr['ISLCESS'] 			= $List9['is_less_appl'];
				$OutputArr['INCEXC'] 			= $List9['gst_inc_exc'];
				$OutputArr['GSTPERRATE'] 		= $List9['gst_perc_rate'];
				$OutputArr['ISGST'] 			= $List9['is_gst_appl'];
			}
		}		
		if($CheckVal == 0){
			$SelectQuery2 	= "SELECT ccno,globid,sheetid,hoaid,cbdtid,contid FROM works WHERE globid = '$GlobID'";
			$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					$List3 = mysqli_fetch_assoc($SelectSql2);
					$CCno		     = $List3['ccno'];
					$SheetID 		= $List3['sheetid'];
					$HoaID1		 	= $List3['hoaid'];
					$ContBankDtId	= $List3['cbdtid'];
					$ContId	        = $List3['contid'];
					$OutputArr['WorkData'] = $List3;
				}
			}
			$SelectQuery4 	= "SELECT contid FROM loi_entry WHERE globid = '$GlobID' AND tr_id = '$EstTsTrId'";
			$SelectQuery4Sql 	= mysqli_query($dbConn,$SelectQuery4);
			if($SelectQuery4Sql == true){
				if(mysqli_num_rows($SelectQuery4Sql)>0){
					$List5 = mysqli_fetch_assoc($SelectQuery4Sql);
					$ContId = $List5['contid'];
				}
			}
			
			$SelectQuery5 	= "SELECT name_contractor,contid FROM contractor WHERE contid = '$ContId' AND active = 1";
			$SelectQuery5Sql 	= mysqli_query($dbConn,$SelectQuery5);
			if(mysqli_num_rows($SelectQuery5Sql)>0){
				$List6 = mysqli_fetch_assoc($SelectQuery5Sql);
				$ContName = $List6['name_contractor'];
				$ContID    = $List6['contid'];
			}
			$OutputArr['WORKFULLNAME'] 	    = $List2['work_name'];
			$OutputArr['CCNUM']	 			= $List3['ccno'];
			$OutputArr['CONTNAME'] 			= $ContName;
			$OutputArr['contid'] 			= $ContID;
		}
		// /echo $CheckVal;exit; 
		$OutputArr['CHECKVAL'] 	= $CheckVal;
		
		$SelectHoaQuery 	= "SELECT new_hoa_no,hoamast_id FROM hoa_master WHERE hoamast_id IN ($HoaID1) AND active = 1";
		//echo $SelectHoaQuery;exit;
		$SelectHoaQuerySql 	= mysqli_query($dbConn,$SelectHoaQuery);
		if($SelectHoaQuerySql == true){
			if(mysqli_num_rows($SelectHoaQuerySql)>0){
				while($List10 = mysqli_fetch_assoc($SelectHoaQuerySql)){
					$HoaArr[] = $List10;
				}
			}
		}
		$HoaID = explode(",",$HoaID1);
		$OutputArr['SELHOAID'] 		 = $HoaID;
		$OutputArr['HOANUMBER'] 	 = $HoaArr;
		//print_r($HoaArr);exit;
	

		$OutputArr['CONTBANKDETID'] = $ContBankDtId;
	
		$SelectQuery3 	= "SELECT is_negotiate,quoted_amt_af_neg,negotiate_rebate_perc,quoted_amt_af_reb,rebate_perc FROM bidder_bid_master WHERE globid = '$GlobID' AND tr_id = '$EstTsTrId' AND contid='$ContId'";
		
		$SelectQuery3Sql 	= mysqli_query($dbConn,$SelectQuery3);
		if($SelectQuery3Sql == true){
			if(mysqli_num_rows($SelectQuery3Sql)>0){
				$List4 = mysqli_fetch_assoc($SelectQuery3Sql);
				if($List4['is_negotiate'] == 'Y'){
					$OutputArr['WOAmount'] 	= $List4['quoted_amt_af_neg'];
					$OutputArr['WOReb'] 		= $List4['negotiate_rebate_perc'];
				}else{
					$OutputArr['WOAmount'] 	= $List4['quoted_amt_af_reb'];
					$OutputArr['WOReb'] 		= $List4['rebate_perc'];
				}
			}
		}
	
		$SelectSheetQuery4 	= "SELECT mbheaderid FROM mbookheader WHERE sheetid = '$SheetID' LIMIT 1";
		$SelectSheetQuery4Sql 	= mysqli_query($dbConn,$SelectSheetQuery4);
		if(mysqli_num_rows($SelectSheetQuery4Sql)>0){
			$OutputArr['WORKPROCESS'] = 1;
		}else{
			$OutputArr['WORKPROCESS'] = 0;
		}
		// $SelectQuery6 	= "SELECT * FROM contractor_bank_detail WHERE contid = '$ContId' AND active = 1";
		$SelectQuery6 	=  "SELECT a.*,b.name_contractor  FROM contractor_bank_detail a 
							INNER JOIN contractor b ON (a.contid = b.contid) 
							WHERE a.contid='$ContId' AND a.active = 1";
		$SelectQuery6Sql 	= mysqli_query($dbConn,$SelectQuery6);
		if(mysqli_num_rows($SelectQuery6Sql)>0){
			while($List7 = mysqli_fetch_array($SelectQuery6Sql)){
				$RowArr[] = $List7;
			}
		}
		$OutputArr['CONTBANKDET'] = $RowArr;
	
		$SelectQuery7	= "SELECT a.staffid,a.staffname,a.staffcode,b.designationname,c.section_name FROM staff a INNER JOIN designation b ON (a.designationid = b.designationid)
		INNER JOIN section_name c on (a.sectionid = c.secid) WHERE a.staffid = '$EicID'";
		$SelectQuery7Sql 	= mysqli_query($dbConn,$SelectQuery7);
		if(mysqli_num_rows($SelectQuery7Sql)>0){
			$List8 = mysqli_fetch_array($SelectQuery7Sql);
			$OutputArr['ENGGID'] 	= $List8['staffid'];
			$OutputArr['ENGGNAME'] 	= $List8['staffname'];
			$OutputArr['ENGGICNO']  = $List8['staffcode'];
			$OutputArr['ENGGDESIG'] = $List8['designationname'];
			$OutputArr['ENGGSEC'] 	= $List8['section_name'];
		}
	}
	
}

echo json_encode($OutputArr);
?>
