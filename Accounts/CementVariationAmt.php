<?php 
$SLMCemVarArr = array(); $DPMCemVarArr = array(); $CVItemArr = array();
$SLMCemVarQtyArr = array(); $DPMCemVarQtyArr = array();
$CemVarMasterArr = array(); 
$SelectQuery = "select * from cement_temp_variation_dt where sheetid = '$abstsheetid' and variat_type = 'C' and active = 1";
//echo $rbn;exit;
$SelectSql = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		while($CVList = mysqli_fetch_object($SelectSql)){  
			if(in_array($CVList->subdivid, $CVItemArr)){
				// already exist
			}else{
				array_push($CVItemArr,$CVList->subdivid);
			}
			//// For Cement Var Rate , Unit etc
			$CemVarMasterArr[$CVList->subdivid][0] = $CVList->rate;
			$CemVarMasterArr[$CVList->subdivid][1] = $CVList->difference_wt;
			
			if($rbn == $CVList->rbn){ //echo $CVList->rbn;exit;
				/// SLM Amount Stored in Array
				if($SLMCemVarArr[$CVList->subdivid] == ''){
					$SLMCemVarArr[$CVList->subdivid] = $CVList->variat_amt;
				}else{
					$SLMCemVarArr[$CVList->subdivid] = $SLMCemVarArr[$CVList->subdivid] + $CVList->variat_amt;
				}
				/// SLM Qty Stored in Array
				if($SLMCemVarQtyArr[$CVList->subdivid] == ''){ 
					$SLMCemVarQtyArr[$CVList->subdivid] = $CVList->utz_qty;  
				}else{
					$SLMCemVarQtyArr[$CVList->subdivid] = $SLMCemVarQtyArr[$CVList->subdivid] + $CVList->utz_qty;
				}
			}else{
				/// DPM Amount Stored in Array
				if($DPMCemVarArr[$CVList->subdivid] == ''){
					$DPMCemVarArr[$CVList->subdivid] = $CVList->variat_amt;
				}else{
					$DPMCemVarArr[$CVList->subdivid] = $DPMCemVarArr[$CVList->subdivid] + $CVList->variat_amt;
				}
				/// DPM Qty Stored in Array
				if($DPMCemVarQtyArr[$CVList->subdivid] == ''){
					$DPMCemVarQtyArr[$CVList->subdivid] = $CVList->utz_qty;
				}else{
					$DPMCemVarQtyArr[$CVList->subdivid] = $DPMCemVarQtyArr[$CVList->subdivid] + $CVList->utz_qty;
				}
			}
		}
	}
}
//print_r($SLMCemVarArr);exit;
?>