<?php
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Work Confirmation';
checkUser();
$staffid  = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];
function dt_format($ddmmyyyy){
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	return $yy .'-'. $mm .'-'.$dd;
}
function dt_display($ddmmyyyy){
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}
//require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$IsMeasGen = 0;
$RowCount = 0;
if(isset($_POST['btn_save']) == ' Save '){
	$CheckPt 	= 0;
	$InQueryCon = 0;
	$SheetID		= $_POST["hidd_sheetID"];
	$GlobTsID		= $_POST["hidd_GlobID"];
	$TrNo			= $_POST["cmb_tr_no"];
	$WrkName		= $_POST["txt_workname"];
	$WrkShortName	= $_POST["txt_shortname"];
	$WrkOrderNo		= $_POST["txt_workorderno"];
	$ContName		= trim($_POST["txt_cont_name"]);
	$ContID		    = $_POST["txt_cont_id"];
	$WrkOrderAmt	= $_POST["txt_workorderamt"];
	$AggrementNo	= $_POST["txt_agreementno"];
	$AggrementDt	= dt_format($_POST["txt_aggrementdate"]);
	$HoaIdStr		= $_POST["cmb_hoa"];
	$HoaId   	    = implode(",",$HoaIdStr);
	$contbid		= $_POST["bank_checkbox"];
	if(count($contbid)>0){
		$BankDetChkBox  = implode(",",$contbid);
	}else{
		$BankDetChkBox  = "";
	}
	$GlobID			= $_POST["txt_hid_glob_id"];
	$GSTRateonWO	= $_POST["txt_gst_value"];
	$LCESSAppRad	= $_POST["lcesapp"];
	$GSTIncExRad	= $_POST["gstincexc"];
	$IsGSTRad		= $_POST["gstapplicable"];
	$EnggId	        = trim($_POST['txt_staffid']);
	$CheckPt		= $_POST["txt_hid_checkpt"];
	$CompCodeNo		= $_POST["txt_computercodeno"];
	$WrkOrderDate	= dt_format($_POST["workorderdate"]);
	//0echo $WrkOrderDate;
	$WrkCommDate	= dt_format($_POST["workcommencedate"]);
	//echo $WrkCommDate;
	$WrkDuration	= $_POST["workduration"];
	$RebPercentage	= $_POST["txt_rebatepercent"];
	$DOCompletion	= dt_format($_POST["txt_dateofcompletion"]);
	//echo $DOCompletion;exit;
	$SDper 	      = trim($_POST['txt_sd_per']);
	$SDValue       = trim($_POST['txt_hid_sd_value']);
	$WorkTypeRad	= $_POST["worktype"];
	//echo $CheckPt;exit;

	if($TrNo == NULL){
		$msg = "Please Select Project Title..!!";
	}else if($WrkName == NULL){
		$msg = "Please Enter Work Name..!!";
	}else if($WrkShortName == NULL){
		$msg = "Please Enter Technical Sanction Number..!!";
	}else if($WrkOrderNo == NULL){
		$msg = "Please Enter Technical Sanction Amount..!!";
	}else if($WrkOrderAmt == NULL){
		$msg = "Please Enter Work Order Amount..!!";
	}else if($CompCodeNo == NULL){
		$msg = "Please Enter Computer Code Number..!!";
	}else if($AggrementNo == NULL){
		$msg = "Please Enter Agreement Number..!!";
	}else if($WrkOrderDate == NULL){
		$msg = "Please Enter Work Order Date..!!";
	//}//else if($WorkTypeRad == NULL){
		//$msg = "Please Select Work Type..!!";
	//}
	// else if($GSTRateonWO == NULL){
	// 	$msg = "Please Enter GST Rate Percentage on Work Order..!!";
	// }else if($GSTIncExRad == NULL){
	// 	$msg = "Please Select GST Incusive/Exclusive..!!";
	}else if($WrkDuration == NULL){
		$msg = "Please Enter Work Duration..!!";
	}else if($LCESSAppRad == NULL){
		$msg = "Please Select LCESS Applicable Yes/No..!!";
	}else if($WrkCommDate == NULL){
		$msg = "Please Enter Work Commencement Date..!!";
	}else if($DOCompletion == NULL){
		$msg = "Please Enter Scheduled Work Commencement Date..!!";
	}//else if($RebPercentage == NULL){
		//$msg = "Please Enter Rebate Percentage..!!";
	//}
	else{
		$InQueryCon = 1;
	}
	//echo $InQueryCon;exit;
	if($InQueryCon == 1){
		$Checkpt 	= 0;
		$CheckVal 	= 0;
		$SheetCheckVal	= 0;
		$WorkCheckVal	= 0;

		$SelectTrQuery2 	= "SELECT * FROM tender_register WHERE tr_id = '$TrNo' AND active = 1";
		$SelectTrQuery2Sql2 	= mysqli_query($dbConn,$SelectTrQuery2);
		if($SelectTrQuery2Sql2 == true){
			if(mysqli_num_rows($SelectTrQuery2Sql2)>0){
				$Checkpt = 1;
				$List2 = mysqli_fetch_assoc($SelectTrQuery2Sql2);
				$GlobID = $List2['globid'];
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
					$SheetCheckVal	= 1;
				}
			}
			$TechSancNo = '';
			$SelectQuery2 	= "SELECT globid,sheetid,ts_no FROM works WHERE globid = '$GlobID'";
			$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2) > 0){
					$List3 = mysqli_fetch_assoc($SelectSql2);
					$WorkCheckVal = 1;
					$SheetID	= $List3['sheetid'];
					$TechSancNo = $List3['ts_no'];
				}
			}
		}
		//echo $WorkCheckVal;exit; 
		// 	if($WorkCheckVal == 1){
		// 		$UpdateWorkQuery	= "UPDATE works SET work_name='$WrkName', wo_no='$WrkOrderNo', agmt_no='$AggrementNo',agmt_date='$AggrementDt', 
		// 		wo_amount='$WrkOrderAmt', wo_date='$WrkOrderDate', work_commence_date='$WrkCommDate', work_status='WO', work_duration='$WrkDuration', 
		// 		sch_comp_date='$DOCompletion', is_gst_appl='$IsGSTRad', gst_perc_rate='$GSTRateonWO', is_less_appl='$LCESSAppRad', sd_perc = '$SDper',sd_amt = '$SDValue',
		// 		gst_inc_exc='$GSTIncExRad', is_wo_rel='Y', active = '1' WHERE globid = '$GlobID'";
		// 		$UpdateWorkQuery_sql = mysqli_query($dbConn,$UpdateWorkQuery);
		// 	}else if($WorkCheckVal == 0){
		// 		$InsWorksQuery	= "INSERT INTO works SET tr_id = '$TrNo', work_name = '$WrkName', short_name = '$WrkShortName', wo_no = '$WrkOrderNo', 
		// 		agmt_no = '$AggrementNo', agmt_date = '$AggrementDt', ccno = '$CompCodeNo', wo_date = '$WrkOrderDate', wo_amount = '$WrkOrderAmt',
		// 		work_commence_date = '$WrkCommDate',hoaid = '$HoaId', work_duration = '$WrkDuration', eic='$EnggId', sch_comp_date	 = '$DOCompletion', 
		// 		sd_perc = '$SDper', cbdtid = '$BankDetChkBox', contid='$ContID', name_contractor='$ContName', is_gst_appl = '$IsGSTRad', 
		// 		gst_perc_rate = '$GSTRateonWO', is_less_appl = '$LCESSAppRad', gst_inc_exc = '$GSTIncExRad',active = '1', userid = '$userid', 
		// 		is_wo_rel='Y', created_date = NOW()";
		// 		$InsWorksQuery_sql = mysqli_query($dbConn,$InsWorksQuery);
		// 		$GlobID = mysqli_insert_id($dbConn);
		// 	}
		// }
		$WorkSheetId = $SheetID;
		if($SheetCheckVal == 1){ 
				$UptSheetQuery	= "UPDATE sheet SET tr_id='$TrNo', globid ='$GlobTsID', work_name='$WrkName', short_name='$WrkShortName', work_order_no='$WrkOrderNo', 
				tech_sanction = '$TechSancNo', agree_no='$AggrementNo', agree_date='$AggrementDt', computer_code_no='$CompCodeNo', work_order_date='$WrkOrderDate', work_order_cost='$WrkOrderAmt', 
				work_commence_date='$WrkCommDate', hoaid='$HoaId', eic='$EnggId', work_duration='$WrkDuration', date_of_completion='$DOCompletion', 
				cbdtid='$BankDetChkBox', sd_perc = '$SDper', contid='$ContID', assigned_staff = '$EnggId', is_gst_appl='$IsGSTRad', 
				gst_perc_rate='$GSTRateonWO', is_less_appl='$LCESSAppRad', gst_inc_exc='$GSTIncExRad', name_contractor='$ContName', active = '1', 
				userid = '$userid', created_date = NOW() WHERE sheet_id ='$SheetID'AND globid = '$GlobTsID'";	echo $UptSheetQuery;exit;
				$UptSheetQuery_sql = mysqli_query($dbConn,$UptSheetQuery);

				$UpdateWorkQuery	= "UPDATE works SET work_name='$WrkName', wo_no='$WrkOrderNo', agmt_no='$AggrementNo',agmt_date='$AggrementDt', 
				wo_amount='$WrkOrderAmt', wo_date='$WrkOrderDate', work_commence_date='$WrkCommDate', work_status='WO', work_duration='$WrkDuration', 
				sch_comp_date='$DOCompletion', is_gst_appl='$IsGSTRad', gst_perc_rate='$GSTRateonWO', is_less_appl='$LCESSAppRad', sd_perc = '$SDper',sd_amt = '$SDValue',
				gst_inc_exc='$GSTIncExRad', is_wo_rel='Y', active = '1' WHERE globid = '$GlobTsID'";
				$UpdateWorkQuery_sql = mysqli_query($dbConn,$UpdateWorkQuery);
				if($UpdateWorkQuery_sql > 0){
					UpdateWorkTransaction($GlobID,0,0,"W","Work Order Uodated by ".$UserId,"");
					$msg = "Work order details updated successfully..!! & Confirm this SOQ for billing process";
					$success = 1;
				}else{
					$msg = "Error :Work Order Not Updated..!!. Please Try Again.";
					$success = 0;
				}
		
		}else{ 
			$SheetQuery	= "INSERT INTO sheet SET tr_id = '$TrNo', globid = '$GlobID', work_name = '$WrkName', short_name = '$WrkShortName', 
			tech_sanction = '$TechSancNo', work_order_no = '$WrkOrderNo', agree_no = '$AggrementNo', agree_date = '$AggrementDt', computer_code_no = '$CompCodeNo', 
			work_order_date = '$WrkOrderDate', work_order_cost = '$WrkOrderAmt', work_commence_date = '$WrkCommDate',hoaid = '$HoaId', 
			work_duration = '$WrkDuration', eic='$EnggId', date_of_completion = '$DOCompletion', sd_perc = '$SDper', cbdtid = '$BankDetChkBox', 
			contid='$ContID', assigned_staff = '$EnggId',  name_contractor='$ContName', is_gst_appl = '$IsGSTRad', gst_perc_rate = '$GSTRateonWO', 
			is_less_appl = '$LCESSAppRad', lbcess_rate = '1', gst_inc_exc = '$GSTIncExRad',active = '1', userid = '$userid', created_date = NOW()";
			$SheetQuery_sql = mysqli_query($dbConn,$SheetQuery);
			$LastInsertId 	 = mysqli_insert_id($dbConn);
			$UpdateWorkQuery		="UPDATE works SET  sheetid='$LastInsertId', work_name='$WrkName', wo_no='$WrkOrderNo', agmt_no='$AggrementNo',agmt_date='$AggrementDt', 
			wo_amount='$WrkOrderAmt', wo_date='$WrkOrderDate', work_commence_date='$WrkCommDate', work_status='WO', work_duration='$WrkDuration', 
			sch_comp_date='$DOCompletion', is_gst_appl='$IsGSTRad', gst_perc_rate='$GSTRateonWO', is_less_appl='$LCESSAppRad', lbcess_rate = '1', sd_perc = '$SDper',sd_amt = '$SDValue',
			gst_inc_exc='$GSTIncExRad', is_wo_rel='Y', active = '1' WHERE globid = '$GlobID'";
			$UpdateWorkQuery_sql = mysqli_query($dbConn,$UpdateWorkQuery);
			if($UpdateWorkQuery_sql > 0){
				UpdateWorkTransaction($GlobID,0,0,"W","Work Order Uodated by ".$UserId,"");
				$msg = "Work Order Successfully Created..!!";
				$success = 1;
			}else{
				$msg = "Error :Work Order Not Created..!!. Please Try Again.";
				$success = 0;
			}
			$WorkSheetId = $LastInsertId;
		}
		$SelectMeasCheckQuery = "SELECT * FROM mbookheader WHERE sheetid = '$WorkSheetId' ORDER BY mbheaderid ASC LIMIT 1";
		$SelectMeasCheckSql = mysqli_query($dbConn,$SelectMeasCheckQuery);
		if($SelectMeasCheckSql == true){
			if(mysqli_num_rows($SelectMeasCheckSql)>0){
				$IsMeasGen = 1;
				$msg = "Work order details updated successfully..!! & Measurements already started unable to change SOQ";
			}
		}
	}
}


	$MastId 	 = $_POST['cmb_tr_no'];
	$ContractId  = $_POST['txt_cont_id'];
	//echo $ContractId;exit;
	$GlobID= '';
	$GlobIDQuery = "SELECT globid,work_name FROM tender_register WHERE tr_id = '$MastId'";
	$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobID = $List->globid;
			$WorkName = $List->work_name;
		}
	}//echo $GlobID;exit;

	$ContNameQuery = "SELECT * FROM contractor WHERE contid = '$ContractId'";
	$ContNameQuerySql 	= mysqli_query($dbConn,$ContNameQuery);
	if($ContNameQuerySql == true){
		if(mysqli_num_rows($ContNameQuerySql)>0){
			$ContList = mysqli_fetch_object($ContNameQuerySql);
			$SelContName = $ContList->name_contractor;
		}
	}

	$WorksTableQuery = "SELECT wo_no FROM works WHERE globid = '$GlobID'";
	$WorksTableQuerySql 	= mysqli_query($dbConn,$WorksTableQuery);
	if($WorksTableQuerySql == true){
		if(mysqli_num_rows($WorksTableQuerySql)>0){
			$WorksList = mysqli_fetch_object($WorksTableQuerySql);
			$WoNumber = $WorksList->wo_no;
		}
	}
	//echo $SelContName;exit;

	//$SelectQuery1 	= "SELECT * FROM bidder_bid_master WHERE globid = '$GlobID' AND is_negotiate = 'Y'";
	//echo $SelectQuery1;exit;
	// $SelectQuerySql1 	= mysqli_query($dbConn,$SelectQuery1);
	// if($SelectQuerySql1 == true){
	// 	if(mysqli_num_rows($SelectQuerySql1)>0){
	// 		$List1 = mysqli_fetch_object($SelectQuerySql1);
	// 		$Isnego = $List1->is_negotiate;
	// 		$NegoRebPerc = $List1->negotiate_rebate_perc;
	// 		$AmtAftReb 	= $List1->quoted_amt_af_neg;
	// 	}
	// }

	//echo $NegoRebPerc;
	$BidderRateArr  = array();
	$SelectQuery = "SELECT a.*,b.* FROM bidder_bid_master a INNER JOIN bidder_bid_details b ON (a.bmid = b.bmid) 
	WHERE a.tr_id = '$MastId' AND a.contid = '$ContractId' ORDER BY b.bdid ASC";
	$SelectSql = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$RowCount = 1;
		}
	}
	if(isset($_POST['btn_confirm']) == ' Save '){
		$CheckPt 	= 0;
		$InQueryCon = 0;
		$work_order_cost = 0;
		$first = 0;$prev_item ='';$subdivisionlast_id =0; $sheetCnt = 0;  $Exectemp = 0; $InsertTemp = 0;
		$slno = '';
		$TRid				= trim($_POST["hid_tr_id"]);
		$Rebate				= $_POST["txt_nego_rebate_perc"];
		$itemstr			= $_POST["txt_item_no"];
		$descriptionstr		= $_POST["txt_desc"]; 
		$qtystr				= $_POST["txt_qty"];  
		$unitstr			= $_POST["txt_unit"];
		$ratestr	        = $_POST["txt_nego_rate"];
		$mtypestr			= $_POST["cmb_item_type"];
		$amtstr				= $_POST["txt_nego_amt"];
		$ProfitRebateType	= $_POST["txt_profit_rebate"];
		//print_r($itemstr);exit;
		$GlobID= '';
		$GlobIDQuery = "SELECT globid,work_name FROM tender_register WHERE tr_id = '$TRid'";
		$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
		if($GlobIDSql == true){
			if(mysqli_num_rows($GlobIDSql)>0){
				$List = mysqli_fetch_object($GlobIDSql);
				$GlobID = $List->globid;
				$WorkName = $List->work_name;
			}
		}
		$SheetID= '';
		$GlobIdQuery = "SELECT sheet_id FROM sheet where globid = '$GlobID'";
		$GlobIdSql 	= mysqli_query($dbConn,$GlobIdQuery);
		if($GlobIdSql == true){
			if(mysqli_num_rows($GlobIdSql)>0){
				$List = mysqli_fetch_object($GlobIdSql);
				$SheetID = $List->sheet_id;
				//echo $SheetID;exit;
			}
		}

		$UpdateWorkQuery	= "UPDATE sheet SET rebate_profit = '$ProfitRebateType', rebate_percent ='$Rebate' WHERE globid = '$GlobID' and sheet_id  = '$SheetID'";
		$UpdateWorkQuery_sql = mysqli_query($dbConn,$UpdateWorkQuery);
		if(count($descriptionstr)>0){ 
			$DeleteQuery1 	= "delete from division where sheet_id ='$SheetID'";
			$DeleteSql1 	= mysqli_query($dbConn,$DeleteQuery1);
			//echo $DeleteQuery1; exit;
			$DeleteQuery2 	= "delete from subdivision  where sheet_id ='$SheetID'";
			$DeleteSql2 	= mysqli_query($dbConn,$DeleteQuery2);
			$DeleteQuery3 	= "delete from schdule where sheet_id ='$SheetID'";
			$DeleteSql3 	= mysqli_query($dbConn,$DeleteQuery3);
			if($itemstr != null){
				foreach($itemstr as $Key => $Value){
					$item     	        = $itemstr[$Key];
					$description    	= $descriptionstr[$Key];
					$patterns           = array();
					$patterns[0]        = '/"/';
					$patterns[1]        = "/'/";
					$patterns[2]        = '/�/';
			
					$replacements       = array();
					$replacements[0]    = '"';
					$replacements[1]    = "\'";
					$replacements[2]    = '�';
					$description 	    = preg_replace($patterns, $replacements, $description);
					$description 	    = str_replace("'", "", $description);
					$description 	    = str_replace('"', '', $description);
					$qty   	            = $qtystr[$Key];
					$per      	        = $unitstr[$Key];
					$rate       	    = $ratestr[$Key];
					$amt        	    = $amtstr[$Key];
					$mtype           	= $mtypestr[$Key];
					if(($amt > 0)&&($amt != "")){
						$work_order_cost = $work_order_cost + $amt;
					}
							
					$prevsplit 		= $prev_item;
					$prevfound 		= explode(".", $prevsplit);
					if ($item != '' && $description != ''){
						$found = explode(".", $item);
						if($found[0] != $prevfound[0]){
							$divname = $found[0];
							$sql_sheetdivision = "insert into division set sheet_id ='$SheetID',userid ='$userid', div_name='$divname', active='1'";
							$rs_sheetdivision = mysqli_query($dbConn,$sql_sheetdivision);
							//

							$divisionlast_id = mysqli_insert_id($dbConn);
							//if(count($found) == 1)
							//{
							if(($qty != "") && ($qty != 0)){
								$sql_sheetsubdivision = "insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', sheet_id = '$SheetID', active='1'";
								$rs_sheetsubdivision = mysqli_query($dbConn,$sql_sheetsubdivision);
								$subdivisionlast_id = mysqli_insert_id($dbConn);
							}
							//}
						}else{
							if(($qty != "") && ($qty != 0))
							{
								$sql_sheetsubdivision = "insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', sheet_id = '$SheetID', active='1'";
								$rs_sheetsubdivision  =  mysqli_query($dbConn,$sql_sheetsubdivision);
								$subdivisionlast_id   =   mysqli_insert_id($dbConn);
							}
						}
						$prev_item = $item;
						if(($qty == "") || ($qty == 0)){ 
							$subdivisionlastid = 0; 
						}else{ 
							$subdivisionlastid = $subdivisionlast_id; 
						}
						if($mtype=='g'){
							$sql_schedule = "insert into schdule set sheet_id='$SheetID', sno='$item', description='$description', total_quantity='$qty', rate='$rate', rebate_percent = '0', decimal_placed = '3', per='$per', total_amt='$amt', measure_type='', item_flag = 'NI', escalation_flag = 'Y', subdiv_id ='$subdivisionlastid', active='1',create_dt=NOW(),user_id='$userid'";
							$rs_schedule = mysqli_query($dbConn,$sql_schedule);
							//echo $sql_schedule; exit;
						}else{
							$sql_schedule = "insert into schdule set sheet_id='$SheetID', sno='$item', description='$description', total_quantity='$qty', rate='$rate', rebate_percent = '0', decimal_placed = '3', per='$per', total_amt='$amt', measure_type='$mtype', item_flag = 'NI', escalation_flag = 'Y', subdiv_id ='$subdivisionlastid', active='1',create_dt=NOW(),user_id='$userid'";
							$rs_schedule = mysqli_query($dbConn,$sql_schedule);
						}
						if($rs_schedule == true){
							$InsertTemp++;
						}
					}
					$Exectemp++;
				}
				if($rs_schedule == true){
					$msg = "SOQ Confirmed Successfully";
					$success = 1;
				}
			}
		}
	}
	//exit;
		// if(mysqli_num_rows($SelectSql)>0){
			
		// 	while($List = mysqli_fetch_object($SelectSql)){ 
		// 		$RowCount = 1;
		// 		$Negoiate = $List ->is_negotiate;
		// 		$Itemdes   =$List->item_desc;
		// 		$Itemqty   =$List->item_qty;
		// 		if($Negoiate =='Y'){
		// 			$Rate = $List ->negotiate_rate;
		// 			$Amount =
		// 			$Rebate =$List ->rebate_perc;
		// 			$Total =$List ->quoted_amt_af_reb;
		// 		}else{
		// 			$Rate = $List ->item_rate;
		// 			$Amount =$List ->negotiate_value;
		// 			$Rebate =$List ->negotiate_rebate_perc;
		// 			$Total = $List ->quoted_amt_af_neg;
		// 		}
		// }

// if(isset($_POST['back'])){
//      header('Location: NegotiationEntryGenerate.php');
// }
// if(isset($_POST['btn_save'])){

// 	$hid_tr_id			= $_POST["hid_tr_id"];
// 	$hid_cont_id		= $_POST["hid_cont_id"];
// 	$ItemNoArr			= $_POST["txt_item_no"];
// 	$ItemRateArr		= $_POST["txt_nego_rate"];
// 	$ItemAmountArr		= $_POST["txt_nego_amt"];
// 	$txt_nego_tot_amt	= $_POST["txt_nego_tot_amt"];
	
// 	$txt_nego_rebate_perc	= $_POST["txt_nego_rebate_perc"];
// 	$txt_amt_after_rebate	= $_POST["txt_amt_after_rebate"];
// 	$Execute = 0;
// 	if($ItemRateArr == NULL){
// 		$msg = "Please Enter Negotiation Rate..!!";
// 	}else if($ItemAmountArr == NULL){
// 		$msg = "Please Enter Negotiation Amount..!!";
// 	}else{
// 		$InQueryCon = 1;
// 	}
	 
// 	$GlobID= '';
// 	$GlobIDQuery = "SELECT globid FROM tender_register WHERE tr_id = '$hid_tr_id'";
// 	$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
// 	if($GlobIDSql == true){
// 		if(mysqli_num_rows($GlobIDSql)>0){
// 			$List = mysqli_fetch_object($GlobIDSql);
// 			$GlobID = $List->globid;
// 		}
// 	}
// 	//$cmb_approve_auth	= $_POST["cmb_approve_auth"];
// 	if($InQueryCon == 1){
// 		foreach($ItemRateArr as $ArrKey => $ArrValue){
// 			$ItemNo			= $ItemNoArr[$ArrKey];
// 			$NegItemRate 	= $ItemRateArr[$ArrKey];
// 			$NegItemAmt 	= $ItemAmountArr[$ArrKey];
// 			//echo $NegItemRate;exit;
// 			$UpdateQuery 	= "UPDATE bidder_bid_details SET globid='$GlobID', negotiate_rate = '$NegItemRate', negotiate_value = '$NegItemAmt' WHERE tr_id = '$hid_tr_id' AND contid = '$hid_cont_id' AND item_no = '$ItemNo'";
// 			$UpdateSql 		= mysqli_query($dbConn,$UpdateQuery);
// 			if($UpdateSql == true){
// 				$Execute++;
// 			}
// 		}
// 		if($UpdateSql == true){
// 			if($txt_nego_rebate_perc == ''){
// 				$rebatepec = 0;
// 				$rebateamt = 0;
// 			}else{
// 				$rebatepec = $txt_nego_rebate_perc;
// 				$rebateamt = $txt_amt_after_rebate;
// 			}
// 			//echo $rebatepec;exit;

// 			$UpdateQuery1 	= "UPDATE bidder_bid_master SET globid='$GlobID', is_negotiate = 'Y', negotiate_rebate_perc = '$rebatepec', quoted_amt_af_neg = '$rebateamt' WHERE tr_id = '$hid_tr_id' AND contid = '$hid_cont_id'";
// 			$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1);
// 			if($UpdateSql1 == true){
// 				$Execute++;
// 			}
// 		}
// 	}
// 	if($Execute > 0){
// 		$msg = "Negotiation Details Saved Successfully";
// 		$success = 1;
// 	}else{
// 		$msg = "Error : Negotiation Details Not Saved. Please Try Again.";
// 		$success = 0;
// 	}
// 	//header('Location: NegotiationViewGenerate.php');
// }
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<style>
.DispTable{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
	}
	.DispTable th, .DispTable td{
		border:1px solid #BCBEBF;
		border-collapse:collapse;
		padding:2px 3px;
	}
	.DispTable th{
		background-color:#035a85;
		color:#fff;
		vertical-align:middle;
		text-align:center;
	}
	.DispTable td{
		color:#062C73;
	}
	.HideDesc{
		max-width : 868px; 
	  	white-space : nowrap;
	  	overflow : hidden;
	  	text-overflow: ellipsis;
	}
	.DispSelectBox{
		border:1px solid #0195D5;
		font-size:11px;
		padding:4px 4px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		width:100%;
		margin-top:2px;
		margin-bottom:2px;
		color:#03447E;
		font-weight:600;
	}
	.dataTable {
        line-height: 16px !important;
        font-weight: 700 !important;
        color: #74048C;
       font-size: 12px;
	   border-collapse: collapse;
       text-shadow: none;
       text-transform: none;
       font-family: Verdana, Arial, Helvetica, sans-serif;
       line-height: 17px;
}
</style>
<script type="text/javascript">
		function goBack()
	{
		url = "WorkOrder.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">  
				<?php include "MainMenu.php"; ?>
                <div class="container_12">  
                    <div class="grid_12" align="center"> 
						<!--<div align="right" class="users-icon-part">&nbsp;</div>-->
                        <blockquote class="bq1" id="bq1" style="overflow:auto;">
						<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Contractor's Quote</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="div12" align="left">
																	<b>
																		<div class="div12 namebox">
																			<table class="nborder">
																				<tr>
																					<td nowrap="nowrap">Work Order Number &emsp;: </td>
																					<td><?php if(isset($WoNumber)){ echo $WoNumber; } ?></td>
																				</tr>
																				<tr>
																					<td nowrap="nowrap">Name Of Work &emsp;&emsp;&emsp;&emsp;: </td>
																					<td><?php if(isset($WorkName)){ echo $WorkName; } ?></td>
																				</tr>
																				<tr>
																					<td nowrap="nowrap">Contractor Name&emsp;&emsp;&emsp;: </td>
																					<td><?php if(isset($SelContName)){ echo $SelContName; } ?></td>
																				</tr>
																			</table>
																		</div>
																		<div class="row smclearrow"></div>
																	</b> 
																</div>
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																		<tr>
																			<th nowrap="nowrap">Item No.</th>
																			<th>Description</th>
																			<th>Qty</th>
																			<th>Unit</th>
																			<th nowrap="nowrap">Rate</br>( &#8377 )</th>
																			<th nowrap="nowrap">Amount</br>( &#8377 )</th>
																			<th nowrap="nowrap">Item Type</th>
																			
																		</tr>
																	</thead>
																	<tbody>
																	<?php
																	$IndexQty = 0;
																	$TotalAmount = 0;
																	$TotalNegoAmount = 0;
																	if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
																		<tr>
																			<td align="center"><?php echo $List->item_no; ?>
																					<input type="hidden" readonly name="txt_item_no[]" id="txt_item_no" class="tboxsmclass" value="<?php if($List->item_no != ''){ echo $List->item_no; } ?>" style="width:70px">
																				
																			</td>
																			<td align="justify"><?php echo $List->item_desc; ?>
																			<input type="hidden"  readonly="" name="txt_desc[]" id="txt_desc"  class="tboxsmclass itqty" value="<?php  echo $List->item_desc;  ?>" >

																		   </td>
																			<td align="right" readonly><?php if($List->item_qty != 0){ echo $List->item_qty; } ?>
																			<input type="hidden"  readonly="" name="txt_qty[]" id="txt_qty"  class="tboxsmclass itqty" value="<?php echo $List->item_qty;  ?>" style="width:100px">
																			</td>
																			<td align="center" readonly><?php echo $List->item_unit; ?>
																			<input type="hidden"  readonly="" name="txt_unit[]" id="txt_unit"  class="tboxsmclass itqty" value="<?php echo $List->item_unit;  ?>" style="width:100px">

																		   </td>

																			<!-- <td align="right" readonly><?php if($List->item_rate != 0){ echo IndianMoneyFormat($List->item_rate); } ?></td>
																			<td align="right">
																			
																			</td> -->
																			<td align="right">
																			<?php if($List->is_negotiate == 'Y'){ echo IndianMoneyFormat($List->negotiate_rate); }else{echo IndianMoneyFormat($List->item_rate);} ?>
																				<input type="hidden" readonly name="txt_nego_rate[]" id="txt_nego_rate"  onKeyPress="return isNumberWithTwoDecimal(event,this);" class="tboxsmclass negorate" value="<?php if($List->is_negotiate == 'Y'){ echo $List->negotiate_rate; }else{ echo $List->item_rate; } ?>" data-index="<?php echo $IndexQty; ?>" data-qty="<?php if($List->item_qty != 0){ echo $List->item_qty; } ?>" style="text-align:right; width:70px">
																			</td>
																			<?php 
																				if($List->is_negotiate == 'Y'){
																					$NegAmount = $List->negotiate_value;
																					$TotalNegoAmount = $TotalNegoAmount + $NegAmount;
																					$Rebate =$List->negotiate_rebate_perc;
																					$Totalcost =$List->quoted_amt_af_neg;
																					$Trid =$List->tr_id;
																					$Contid =$List->contid;
																					$ProfitRebate = $List->rebate_profit;
																				}else{
																					$Amount = round(($List->item_qty * $List->item_rate),2);
																					$TotalNegoAmount =   $TotalNegoAmount +  $Amount;
																					//$TotalNegoAmount =$List->quoted_amt;
																					$Rebate =$List->rebate_perc;
																					$Totalcost =$List->quoted_amt_af_reb;
																					$Trid =$List->tr_id;
																					$Contid =$List->contid;
																					$ProfitRebate = $List->rebate_profit;
																				}
																				if($ProfitRebate == "PR"){
																					$ProfitRebateStr = "PROFIT";
																				}else if($ProfitRebate == "RE"){
																					$ProfitRebateStr = "REBATE";
																				}else{
																					$ProfitRebateStr = "PROFIT / REBATE";
																				}
																				
																				?>
																			<td align="right">
																			<?php if($List->is_negotiate == 'Y'){ echo IndianMoneyFormat($List->negotiate_value); }else{echo IndianMoneyFormat($Amount);} ?>
																				<input type="hidden" readonly="" name="txt_nego_amt[]" id="txt_nego_amt<?php echo $IndexQty; ?>" class="tboxsmclass negoamt" value="<?php if($List->is_negotiate == 'Y'){ echo $List->negotiate_value; }else{ echo $Amount; } ?>" style="text-align:right; width:100px">
																			
																			</td>
																			<td align="center">
																				<select name="cmb_item_type[]" id ="cmb_item_type" class="tboxsmclass itemtype">  
																					<option value="g">General</option>
																					<option value="s">Steel</option>
																					<option value="st">Structural Steel</option>
																				</select>
																			 </td>
																		</tr>
																	<?php $IndexQty++; } ?> 
																		<tr>
																			<td align="right">&nbsp;</td>
																			<td align="right"><b>TOTAL AMOUNT ( &#8377 ) &nbsp;</b></td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">
																			<?php echo IndianMoneyFormat($TotalNegoAmount);?>
																				<input type="hidden" readonly="" name="txt_nego_tot_amt" id="txt_nego_tot_amt" class="tboxsmclass" value="<?php echo IndianMoneyFormat($TotalNegoAmount);?>" style="text-align:right; width:100px">
																			</td>
																			<td align="right">&nbsp;</td>
																		</tr>
																		<tr>
																			<td align="right">&nbsp;</td>
																			<td align="right"><b><?php echo $ProfitRebateStr; ?> ( % ) &nbsp;</b></td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">
																			<?php echo $Rebate;?>
																				<input type="hidden" name="txt_nego_rebate_perc" onKeyPress="return isPercentageValue(event,this);" id="txt_nego_rebate_perc" class="tboxsmclass" value="<?php echo $Rebate;?>" style="text-align:right; width:100px">
																			</td>
																			<td align="right">&nbsp;</td>
																		</tr>
																		<tr>
																			<td align="right">&nbsp;</td>
																			<td align="right"><b>TOTAL AMOUNT AFTER <?php echo $ProfitRebateStr; ?> ( &#8377 ) &nbsp;</b></td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">
																			<?php echo IndianMoneyFormat($Totalcost); ?>
																				<input type="hidden" readonly="" name="txt_amt_after_rebate" id="txt_amt_after_rebate" class="tboxsmclass" value="<?php echo IndianMoneyFormat($Totalcost); ?>" style="text-align:right; width:100px">
																			</td>
																			<td align="right">&nbsp;</td>
																			<input type="hidden" name="txt_profit_rebate" id="txt_profit_rebate" class="tboxsmclass" value="<?php echo $ProfitRebate; ?>" style="width:100px">
																			<input type="hidden" name="hid_tr_id" id="hid_tr_id" class="tboxsmclass" value="<?php echo $Trid; ?>" style="width:100px">
																			<input type="hidden" name="hid_cont_id" id="hid_cont_id" class="tboxsmclass" value="<?php echo $Contid; ?>" style="width:100px">
																		</tr>
																	<?php } ?>
																	</table>
																</div>
															</div>
																<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
																	<div class="buttonsection">
																		<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
																	</div>
																	<!--<div class="buttonsection">
																		<input type="button" name="exportToExcel" id="exportToExcel" value="Export To Excel" class="btn btn-info">
																	</div>-->
																	<?php if($IsMeasGen == 0){ ?>
																	<div class="buttonsection">
																		<input type="submit" name="btn_confirm" id="btn_confirm" class="btn btn-info" value=" Save ">
																	</div>
																	<?php } ?>
																</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
										<!-- <div class="div1">&nbsp;</div> -->
					 				</div>
								</div>		
									
							</div>
							<!--<div align="center">&nbsp;</div>-->
						</blockquote>
				        </div>
				  </div>
			</div>
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
	<script>
		var KillEvent = 0;
		$(document).ready(function(){ 
			$("body").on("click","#btn_confirm", function(event){ 
				if(KillEvent == 0){
					var StlCnt = 0; var StrStlCnt = 0;
					$('.itemtype').each(function(){ 
					 	var ItemType = $(this).val();
					 	if(ItemType == "s"){ 
							StlCnt++
						}
						if(ItemType == "st"){
							StrStlCnt++
						}
					}); 
					var ItemTypeAlert = "";
					if((StlCnt == 0)&&(StrStlCnt == 0)){
						ItemTypeAlert = "You have not selected any Steel / Structural item in Item Type. ";
						event.preventDefault();
						BootstrapDialog.confirm({   
							title: 'Confirmation Message',
							message: ItemTypeAlert+'Are you sure want to save with this Item Type ?',
							closable: false, 				// <-- Default value is false
							draggable: false, 			// <-- Default value is false
							btnCancelLabel: 'Cancel', 	// <-- Default value is 'Cancel',
							btnOKLabel: 'Ok', 			// <-- Default value is 'OK',
							callback: function(result) {
								if(result){
									KillEvent = 1; 
									$("#btn_confirm").trigger( "click" );
								}else {
									KillEvent = 0;
								}
							}
			         });
				   }else if((StlCnt != 0)&&(StrStlCnt != 0)){
						event.preventDefault();
						BootstrapDialog.confirm({   
							title: 'Confirmation Message',
							message:'Are you sure want to save with this Item Type ?',
							closable: false, 				// <-- Default value is false
							draggable: false, 			// <-- Default value is false
							btnCancelLabel: 'Cancel', 	// <-- Default value is 'Cancel',
							btnOKLabel: 'Ok', 			// <-- Default value is 'OK',
							callback: function(result) {
								if(result){
									KillEvent = 1; 
									$("#btn_confirm").trigger( "click" );
								}else {
									KillEvent = 0;
								}
							}
			         });
				   }
				}
			});
		});
	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			BootstrapDialog.alert(msg);
		}
	};
	</script>
</html>
<style>
.table1 td{
	background:#fff;
}
</style>
