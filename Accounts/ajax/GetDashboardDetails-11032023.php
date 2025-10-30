<?php
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/declaration.php';

$IsBeExist = 0;
$FrPage 	= $_GET['page'];
$Output = 0;
if($FrPage == 'CSTWAITINGLIST'){
	$TrArr1 = array();
	$CurrDate = date('Y-m-d');
	//echo $MasterQuery1;exit;
	if($_SESSION['staff_section'] == 2){
		$MasterQuery1 = "SELECT * FROM tender_register WHERE cst_status  = 'A' ORDER BY tr_id ASC";
	}else if($_SESSION['isadmin'] == 1){
		$MasterQuery1 = "SELECT * FROM tender_register WHERE cst_status  = 'A' ORDER BY tr_id ASC";
	}else{
		$MasterQuery1 = "SELECT * FROM tender_register WHERE cst_status  = 'A' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
	}
	$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
	$CountVal = mysqli_num_rows($MasterQuery1sql);
	if($CountVal == null){
		$Output = 0;
	}else{
		$Output = $CountVal;
	}
}
if($FrPage == 'NEGOCSTWAITINGLIST'){
	$TrArr1 = array();
	$CurrDate = date('Y-m-d');
	//echo $MasterQuery1;exit;
		//$MasterQuery1 = "SELECT * FROM tender_register WHERE nego_status = 'A' ORDER BY tr_id ASC";
	if($_SESSION['staff_section'] == 2){
		$MasterQuery1 = "SELECT * FROM tender_register WHERE nego_status  = 'A' ORDER BY tr_id ASC";
	}else if($_SESSION['isadmin'] == 1){
		$MasterQuery1 = "SELECT * FROM tender_register WHERE nego_status  = 'A' ORDER BY tr_id ASC";
	}else{
		$MasterQuery1 = "SELECT * FROM tender_register WHERE nego_status  = 'A' AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
	}
	$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
	$CountVal = mysqli_num_rows($MasterQuery1sql);
	if($CountVal == null){
		$Output = 0;
	}else{
		$Output = $CountVal;
	}
}
if($FrPage == 'BGEXPLIST'){
	$ExpArr1 	= array();
	$TrArr1 		= array();
	$DetIdArr1 	= array();
	$RowSpanArr = array();
	$PGDataArr 	= array();
	$CurrDate 	= date('Y-m-d');
	if($_SESSION['staff_section'] == 2){
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date,b.inst_type, b.inst_purpose FROM loi_entry a INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
		INNER JOIN tender_register c ON (a.tr_id = c.tr_id) WHERE b.inst_type = 'BG' AND b.inst_status != 'R' AND b.inst_exp_date < '$CurrDate'";
	}else if($_SESSION['isadmin'] == 1){
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date,b.inst_type, b.inst_purpose FROM loi_entry a INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
		INNER JOIN tender_register c ON (a.tr_id = c.tr_id) WHERE b.inst_type = 'BG' AND b.inst_status != 'R' AND b.inst_exp_date < '$CurrDate'";
	}else{
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date,b.inst_type, b.inst_purpose FROM loi_entry a INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
		INNER JOIN tender_register c ON (a.tr_id = c.tr_id) WHERE b.inst_type = 'BG' AND b.inst_status != 'R' AND b.inst_exp_date < '$CurrDate' AND (c.eic = '".$_SESSION['sid']."' OR c.created_by = '".$_SESSION['userid']."')";
	}
	
	//echo $MasterQuery1;exit;
	$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
	if($MasterQuery1sql == true){
		if(mysqli_num_rows($MasterQuery1sql)>0){
			while($List10 = mysqli_fetch_object($MasterQuery1sql)){
				$TrId 	  = $List10->tr_id;
				$insdetId = $List10->bfdid;
				$ExpDate = $List10->inst_exp_date;
				// /echo $ExpDate;
				if($CurrDate > $ExpDate){
					$ExpArr1[$insdetId] = $ExpDate;
					array_push($TrArr1,$TrId);
				}	
			}
		}
	}
	$ImpTrIds = implode(',',$TrArr1);
	if($ImpTrIds != ''){
		$WorkQuery = "SELECT sch_comp_date,work_ext_date FROM works WHERE tr_id IN ($ImpTrIds) AND '$CurrDate' <= sch_comp_date + INTERVAL 8 MONTH OR '$CurrDate' <= work_ext_date + INTERVAL 8 MONTH";
		//echo $WorkQuery;exit;
		$WorkQuerysql = mysqli_query($dbConn,$WorkQuery);
		if($WorkQuerysql == true){
			if(mysqli_num_rows($WorkQuerysql)>0){
				while($List2 = mysqli_fetch_object($WorkQuerysql)){
					$TrId1 	   = $List2->tr_id;
					$SchCompDt = $List2->sch_comp_date;
					$ExtenDt   = $List2->work_ext_date;
					if ($ExtenDt == '' ){	
						$NewSchComdate =   date('Y-m-d', strtotime($SchCompDt. ' + 8 months'));
						foreach($ExpArr1 as $key=>$ExpDtVal){
							if($NewSchComdate > $ExpDtVal){
								array_push($DetIdArr1,$key);
							}
						}
					}else{
						$NewSchComdate =   date('Y-m-d', strtotime($ExtenDt. ' + 8 months'));
						foreach($ExpArr1 as $key=>$ExpDtVal){
							if($NewSchComdate > $ExpDtVal){
								array_push($DetIdArr1,$key);
							}
						}
					}
				
					
				}
			}
		}
	}
	$Output = count($TrArr1);
}
if($FrPage == 'PGBGEXPLIST'){
	$ExpArr1 = array();
	$TrArr1 = array();
	$DetIdArr1 = array();
	$RowSpanArr = array();
	$PGDataArr = array();
	$CurrDate = date('Y-m-d');
	if($_SESSION['staff_section'] == 2){
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date, c.sch_comp_date FROM loi_entry a 
					INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
					INNER JOIN works c ON (a.tr_id = c.tr_id) 
					INNER JOIN tender_register d ON (a.tr_id = d.tr_id) 
					WHERE b.inst_purpose = 'PG' AND b.inst_type = 'BG' AND b.inst_status != 'R' AND inst_exp_date < '$CurrDate'
					AND '$CurrDate' <= c.sch_comp_date + INTERVAL 8 MONTH";
	}else if($_SESSION['isadmin'] == 1){
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date, c.sch_comp_date FROM loi_entry a 
					INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
					INNER JOIN works c ON (a.tr_id = c.tr_id) 
					INNER JOIN tender_register d ON (a.tr_id = d.tr_id) 
					WHERE b.inst_purpose = 'PG' AND b.inst_type = 'BG' AND b.inst_status != 'R' AND inst_exp_date < '$CurrDate'
					AND '$CurrDate' <= c.sch_comp_date + INTERVAL 8 MONTH";
	}else{
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date, c.sch_comp_date FROM loi_entry a 
					INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
					INNER JOIN works c ON (a.tr_id = c.tr_id) 
					INNER JOIN tender_register d ON (a.tr_id = d.tr_id) 
					WHERE b.inst_purpose = 'PG' AND b.inst_type = 'BG' AND b.inst_status != 'R' AND inst_exp_date < '$CurrDate'
					AND '$CurrDate' <= c.sch_comp_date + INTERVAL 8 MONTH AND (d.eic = '".$_SESSION['sid']."' OR d.created_by = '".$_SESSION['userid']."')";
	}

	$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
	if($MasterQuery1sql == true){
		if(mysqli_num_rows($MasterQuery1sql)>0){
			while($List10 = mysqli_fetch_object($MasterQuery1sql)){
				$TrId 	  = $List10->tr_id;
				$insdetId = $List10->bfdid;
				$ExpDate = $List10->inst_exp_date;
				if($CurrDate > $ExpDate){
					$ExpArr1[$insdetId] = $ExpDate;
					array_push($TrArr1,$TrId);
					//print_r($TrArr1);exit;
				}
			}
		}
	}
	$Output = count($TrArr1);
}
if($FrPage == 'FDREXPLIST'){
	$ExpArr1 	= array();
	$TrArr1 		= array();
	$DetIdArr1 	= array();
	$RowSpanArr = array();
	$PGDataArr 	= array();
	$CurrDate 	= date('Y-m-d');
	$userid = $_SESSION['userid'];
	if($_SESSION['staff_section'] == 2){
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date,b.inst_type, b.inst_purpose FROM loi_entry a INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
		INNER JOIN tender_register c ON (a.tr_id = c.tr_id) WHERE b.inst_type = 'FDR' AND b.inst_status != 'R' b.inst_exp_date < '$CurrDate'";
	}else if($_SESSION['isadmin'] == 1){
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date,b.inst_type, b.inst_purpose FROM loi_entry a INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
		INNER JOIN tender_register c ON (a.tr_id = c.tr_id) WHERE b.inst_type = 'FDR' AND b.inst_status != 'R'  AND b.inst_exp_date < '$CurrDate'";
	}else{
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date,b.inst_type, b.inst_purpose FROM loi_entry a INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
		INNER JOIN tender_register c ON (a.tr_id = c.tr_id) WHERE b.inst_type = 'FDR' AND b.inst_status != 'R' AND b.inst_exp_date < '$CurrDate' AND (c.eic = '".$_SESSION['sid']."' OR c.created_by = '".$_SESSION['userid']."')";
	}
	//echo $MasterQuery1;exit;
	$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
	if($MasterQuery1sql == true){
		if(mysqli_num_rows($MasterQuery1sql)>0){
			while($List10 = mysqli_fetch_object($MasterQuery1sql)){
				$TrId 	  = $List10->tr_id;
				$insdetId = $List10->bfdid;
				$ExpDate = $List10->inst_exp_date;
				// /echo $ExpDate;
				if($CurrDate > $ExpDate){
					$ExpArr1[$insdetId] = $ExpDate;
					array_push($TrArr1,$TrId);
				}	
			}
		}
	}
	$ImpTrIds = implode(',',$TrArr1);
	if($ImpTrIds != ''){
		$WorkQuery = "SELECT sch_comp_date,work_ext_date FROM works WHERE tr_id IN ($ImpTrIds) AND '$CurrDate' <= sch_comp_date + INTERVAL 8 MONTH OR  '$CurrDate' <= work_ext_date + INTERVAL 8 MONTH";
		//echo $WorkQuery;exit;
		$WorkQuerysql = mysqli_query($dbConn,$WorkQuery);
		if($WorkQuerysql == true){
			if(mysqli_num_rows($WorkQuerysql)>0){
				while($List2 = mysqli_fetch_object($WorkQuerysql)){
					$TrId1 	   = $List2->tr_id;
					$SchCompDt = $List2->sch_comp_date;
					$ExtenDt   = $List2->work_ext_date;
					if($ExtenDt == '' ){	
						$NewSchComdate =   date('Y-m-d', strtotime($SchCompDt. ' + 8 months'));
						foreach($ExpArr1 as $key=>$ExpDtVal){
							if($NewSchComdate > $ExpDtVal){
								array_push($DetIdArr1,$key);
							}
						}
					}else{
						$NewSchComdate =   date('Y-m-d', strtotime($ExtenDt. ' + 8 months'));
						foreach($ExpArr1 as $key=>$ExpDtVal){
							if($NewSchComdate > $ExpDtVal){
								array_push($DetIdArr1,$key);
							}
						}
					}
					// /echo $NewSchComdate."<br>";
				}
			}
		}
	}
	$Output = count($DetIdArr1);
}

if($FrPage == 'PGFDREXPLIST'){

	$ExpArr1 	= array();
	$TrArr1 		= array();
	$DetIdArr1 	= array();
	$RowSpanArr = array();
	$PGDataArr 	= array();
	$CurrDate 	= date('Y-m-d');
	if($_SESSION['staff_section'] == 2){
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date, c.sch_comp_date FROM loi_entry a 
			INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
			INNER JOIN works c ON (a.tr_id = c.tr_id) 
			INNER JOIN tender_register d ON (a.tr_id = d.tr_id)
			WHERE b.inst_purpose = 'PG' AND b.inst_type = 'FDR' AND b.inst_status != 'R' AND inst_exp_date < '$CurrDate'
			AND '$CurrDate' <= c.sch_comp_date + INTERVAL 8 MONTH";	
	}else if($_SESSION['isadmin'] == 1){
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date, c.sch_comp_date FROM loi_entry a 
			INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
			INNER JOIN works c ON (a.tr_id = c.tr_id) 
			INNER JOIN tender_register d ON (a.tr_id = d.tr_id)
			WHERE b.inst_purpose = 'PG' AND b.inst_type = 'FDR' AND b.inst_status != 'R' AND inst_exp_date < '$CurrDate'
			AND '$CurrDate' <= c.sch_comp_date + INTERVAL 8 MONTH";	
	}else{
		$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date, c.sch_comp_date FROM loi_entry a 
			INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
			INNER JOIN works c ON (a.tr_id = c.tr_id) 
			INNER JOIN tender_register d ON (a.tr_id = d.tr_id)
			WHERE b.inst_purpose = 'PG' AND b.inst_type = 'FDR' AND b.inst_status != 'R' AND inst_exp_date < '$CurrDate'
			AND '$CurrDate' <= c.sch_comp_date + INTERVAL 8 MONTH AND (d.eic = '".$_SESSION['sid']."' OR d.created_by = '".$_SESSION['userid']."')";	
	}
		$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
	if($MasterQuery1sql == true){
		if(mysqli_num_rows($MasterQuery1sql)>0){
			while($List10 = mysqli_fetch_object($MasterQuery1sql)){
				$TrId 	  	= $List10->tr_id;
				$insdetId 	= $List10->bfdid;
				$ExpDate 	= $List10->inst_exp_date;
				if($CurrDate > $ExpDate){
					$ExpArr1[$insdetId] = $ExpDate;
					array_push($TrArr1,$TrId);
					//print_r($TrArr1);exit;
				}
			}
		}
	}
	$ImpTrIds = implode(',',$TrArr1);
	if($ImpTrIds != ''){
		$WorkQuery = "SELECT sch_comp_date FROM works WHERE tr_id IN ($ImpTrIds)";
		$WorkQuerysql = mysqli_query($dbConn,$WorkQuery);
		if($WorkQuerysql == true){
			if(mysqli_num_rows($WorkQuerysql)>0){
				while($List2 = mysqli_fetch_object($WorkQuerysql)){
					$TrId1 	   = $List2->tr_id;
					$SchCompDt 	= $List2->sch_comp_date;
					$NewSchComdate =   date('Y-m-d', strtotime($SchCompDt. ' + 8 months'));
					foreach($ExpArr1 as $key=>$ExpDtVal){
						if($NewSchComdate > $ExpDtVal){
							array_push($DetIdArr1,$key);
						}//print_r($DetIdArr1);exit;
					}
					
				}
			}
		}
	}
	$Output = count($DetIdArr1);
	
}
	if($FrPage == 'PGRELEASELIST'){
		$ExpArr1 = array();
		$TrArr1 = array();
		$DetIdArr1 = array();
		$RowSpanArr = array();
		$PGDataArr = array();
		$CurrDate = date('Y-m-d');
		if($_SESSION['staff_section'] == 2){
			$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date FROM loi_entry a 
				INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
				INNER JOIN tender_register c ON ( a.tr_id = c.tr_id )
				WHERE b.inst_purpose = 'PG'  AND b.inst_status = 'R'";
		} else if($_SESSION['isadmin'] == 1){
			$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date FROM loi_entry a 
				INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
				INNER JOIN tender_register c ON ( a.tr_id = c.tr_id )
				WHERE b.inst_purpose = 'PG'  AND b.inst_status = 'R'";
		}else{
			$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date FROM loi_entry a 
				INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
				INNER JOIN tender_register c ON ( a.tr_id = c.tr_id )
				WHERE b.inst_purpose = 'PG'  AND b.inst_status = 'R' AND (c.eic = '".$_SESSION['sid']."' OR c.created_by = '".$_SESSION['userid']."')";
		}
		$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
		if($MasterQuery1sql == true){
			
			$Output=	mysqli_num_rows($MasterQuery1sql);
		}	
	
	}
	if($FrPage == 'SDBGEXPLIST'){
			$ExpArr1 = array();
			$TrArr1 = array();
			$DetIdArr1 = array();
			$RowSpanArr = array();
			$PGDataArr = array();
			$CurrDate = date('Y-m-d');
			if($_SESSION['staff_section'] == 2){
				$MasterQuery1 = "SELECT a.sheet_id, b.bfdid, b.inst_exp_date FROM sheet a 
					INNER JOIN bg_fdr_details b ON ( a.sheet_id = b.master_id ) 
					WHERE b.inst_purpose = 'SD' AND b.inst_type = 'BG' AND  b.inst_status != 'R' AND inst_exp_date < '$CurrDate' 
					AND '$CurrDate' <= a.date_of_completion + INTERVAL 8 MONTH";
			}else if($_SESSION['isadmin'] == 1){
				$MasterQuery1 = "SELECT a.sheet_id, b.bfdid, b.inst_exp_date FROM sheet a 
					INNER JOIN bg_fdr_details b ON ( a.sheet_id = b.master_id ) 
					WHERE b.inst_purpose = 'SD' AND b.inst_type = 'BG' AND  b.inst_status != 'R' AND inst_exp_date < '$CurrDate' 
					AND '$CurrDate' <= a.date_of_completion + INTERVAL 8 MONTH";
			}else{
				$MasterQuery1 = "SELECT a.sheet_id, b.bfdid, b.inst_exp_date FROM sheet a 
					INNER JOIN bg_fdr_details b ON ( a.sheet_id = b.master_id ) 
					WHERE b.inst_purpose = 'SD' AND b.inst_type = 'BG' AND  b.inst_status != 'R' AND inst_exp_date < '$CurrDate' 
					AND '$CurrDate' <= a.date_of_completion + INTERVAL 8 MONTH AND (a.eic = '".$_SESSION['sid']."' OR FIND_IN_SET(".$_SESSION['sid'].",a.assigned_staff))";
			}	
				
			$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
			if($MasterQuery1sql == true){
				if(mysqli_num_rows($MasterQuery1sql)>0){
					while($List10 = mysqli_fetch_object($MasterQuery1sql)){
						$TrId 	  = $List10->sheet_id;
						$insdetId = $List10->bfdid;
						$ExpDate = $List10->inst_exp_date;
							array_push($TrArr1,$insdetId);
						}
					}
			}
	
			$Output = count($TrArr1);
       }    
if($FrPage == 'SDRELEASELIST'){
		$ExpArr1 = array();
		$TrArr1 = array();
		$DetIdArr1 = array();
		$RowSpanArr = array();
		$PGDataArr = array();
		$CurrDate = date('Y-m-d');
		if($_SESSION['staff_section'] == 2){
			$MasterQuery1 ="SELECT a.sheet_id, b.bfdid, b.inst_exp_date FROM sheet a 
			INNER JOIN bg_fdr_details b ON ( a.sheet_id = b.master_id ) 
			WHERE b.inst_purpose = 'SD'  AND b.inst_status = 'R'";
		}else if($_SESSION['isadmin'] == 1){
			$MasterQuery1 ="SELECT a.sheet_id, b.bfdid, b.inst_exp_date FROM sheet a 
			INNER JOIN bg_fdr_details b ON ( a.sheet_id = b.master_id ) 
			WHERE b.inst_purpose = 'SD'  AND b.inst_status = 'R'";
		}else{
			$MasterQuery1 ="SELECT a.sheet_id, b.bfdid, b.inst_exp_date FROM sheet a 
			INNER JOIN bg_fdr_details b ON ( a.sheet_id = b.master_id ) 
			WHERE b.inst_purpose = 'SD'  AND b.inst_status = 'R' AND (a.eic = '".$_SESSION['sid']."' OR FIND_IN_SET(".$_SESSION['sid'].",a.assigned_staff))";
		}
		$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
		if($MasterQuery1sql == true){
		$Output=	mysqli_num_rows($MasterQuery1sql);
		}
}
if($FrPage == 'EMDDDRETURNLIST'){
		$RowSpanArr=array(); $EMDataArr=array();
		$TRArr=array();
		if($_SESSION['staff_section'] == 2){
			$MasterQuery1 = "SELECT a.tr_id, a.globid
				FROM emd_master a INNER JOIN loi_entry b ON ( a.globid = b.globid ) WHERE a.tr_id = b.tr_id";
		}else if($_SESSION['isadmin'] == 1){
			$MasterQuery1 = "SELECT a.tr_id, a.globid
				FROM emd_master a INNER JOIN loi_entry b ON ( a.globid = b.globid ) WHERE a.tr_id = b.tr_id";
		}else{
			$MasterQuery1 = "SELECT a.tr_id, a.globid
				FROM emd_master a INNER JOIN loi_entry b ON ( a.globid = b.globid ) WHERE a.tr_id = b.tr_id 
				AND (a.eic = '".$_SESSION['sid']."' OR a.created_by = '".$_SESSION['userid']."')";
		}
		$MasterResult1 = mysqli_query($dbConn,$MasterQuery1);
		if($MasterResult1 == true){
			if(mysqli_num_rows($MasterResult1)>0){
				while($List1 = mysqli_fetch_object($MasterResult1)){
					$GlobID = $List1->globid; 
					$TRID 	= $List1->tr_id; 
					array_push($TRArr,$TRID);
				}
			}
		}
		$TrIdVal =implode(',',$TRArr);
		if($TrIdVal != ''){
			$MasterQuery = "SELECT a.*, b.* FROM emd_master a 
			INNER JOIN emd_detail b ON (a.emid = b.emid) 
			WHERE b.status != 'R' AND b.inst_type = 'DD'
			AND a.tr_id IN ($TrIdVal)";
			$MasterResult = mysqli_query($dbConn,$MasterQuery);
			if($MasterResult == true){
				if(mysqli_num_rows($MasterResult)>0){
					$RowCount = 1;
					while($List = mysqli_fetch_object($MasterResult)){
					
						$EMDataArr[] = $List;
				}
			}
		  }
	   }
	$Output = count($EMDataArr);
			
  }
	if($FrPage == 'EMDFDRRETURNLIST'){
		$RowSpanArr=array(); $EMDataArr=array();
		$TRArr=array();
		$MasterQuery1 = "SELECT a.tr_id, a.globid
		FROM emd_master a
		INNER JOIN loi_entry b ON ( a.globid = b.globid )
		WHERE a.tr_id = b.tr_id";
		$MasterResult1 = mysqli_query($dbConn,$MasterQuery1);
		if($MasterResult1 == true){
			if(mysqli_num_rows($MasterResult1)>0){
				while($List1 = mysqli_fetch_object($MasterResult1)){
					$GlobID = $List1->globid; 
					$TRID 	= $List1->tr_id; 
					array_push($TRArr,$TRID);
				}
			}
		}
		$TrIdVal =implode(',',$TRArr);
		if($TrIdVal != ''){
			$MasterQuery = "SELECT a.*, b.* FROM emd_master a 
			INNER JOIN emd_detail b ON (a.emid = b.emid) 
			WHERE b.status != 'R' AND b.inst_type = 'FDR'
			AND a.tr_id IN ($TrIdVal)";
			$MasterResult = mysqli_query($dbConn,$MasterQuery);
			if($MasterResult == true){
				if(mysqli_num_rows($MasterResult)>0){
					$RowCount = 1;
					while($List = mysqli_fetch_object($MasterResult)){
					
						$EMDataArr[] = $List;
				}
			  }
		   }
		}
	$Output = count($EMDataArr);
			
 }
	 if($FrPage == 'EMDBGRRETURNLIST'){
		$RowSpanArr=array(); $EMDataArr=array();
		$TRArr=array();
		$MasterQuery1 = "SELECT a.tr_id, a.globid
		FROM emd_master a
		INNER JOIN loi_entry b ON ( a.globid = b.globid )
		WHERE a.tr_id = b.tr_id";
		$MasterResult1 = mysqli_query($dbConn,$MasterQuery1);
		if($MasterResult1 == true){
			if(mysqli_num_rows($MasterResult1)>0){
				while($List1 = mysqli_fetch_object($MasterResult1)){
					$GlobID = $List1->globid; 
					$TRID 	= $List1->tr_id; 
					array_push($TRArr,$TRID);
				}
			}
		}
		$TrIdVal =implode(',',$TRArr);
		if($TrIdVal != ''){
			$MasterQuery = "SELECT a.*, b.* FROM emd_master a 
			INNER JOIN emd_detail b ON (a.emid = b.emid) 
			WHERE b.status != 'R' AND b.inst_type = 'BG'
			AND a.tr_id IN ($TrIdVal)";
			$MasterResult = mysqli_query($dbConn,$MasterQuery);
			if($MasterResult == true){
				if(mysqli_num_rows($MasterResult)>0){
					$RowCount = 1;
					while($List = mysqli_fetch_object($MasterResult)){
					
						$EMDataArr[] = $List;
				   }
			   }
		   }
		}
	$Output = count($EMDataArr);
			
 }
 
 if($FrPage == 'BILLR'){
	$MasterQuery1 = "SELECT COUNT(brid) as brcnt FROM bill_register WHERE reg_status = '' OR reg_status IS NULL";
	$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
	if($MasterQuery1sql == true){
		if(mysqli_num_rows($MasterQuery1sql)){
			$List = mysqli_fetch_array($MasterQuery1sql);
			echo $List['brcnt'];exit;
		}
	}
}
echo json_encode($Output);
?>