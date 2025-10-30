<?php
ob_start();
require_once 'library/config.php';

function IND_money_format($fullmoney){
	$expfullmoney = explode(".",$fullmoney);
	$money = $expfullmoney[0];
	$paise = $expfullmoney[1];
    $len = strlen($money);
    $m = '';
    $money = strrev($money);
    for($i=0;$i<$len;$i++){
        if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$len){
            $m .=',';
        }
        $m .=$money[$i];
    }
	if($m == ""){ $m = 0; } if( $paise == ""){$paise = '00'; }
    return strrev($m).".".$paise;
}
function IndianMoneyFormat($amount){
	$amt1 = number_format($amount, 2, '.', '');
	$amt2 = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $amt1);
	return $amt2;
}
function GetNextFinYearWorkTotalCommitted($globid,$pinid,$fin_year,$dbConn2,$dbName2){
	$TotalAmount = 0; //echo $fin_year; exit;
	$SelectQuery = "select april, may, june, july, aug, sep, oct, nov, dece, jan, feb, march from budget_expenditure where globid = '$globid' and pinid = '$pinid' and fin_year = '$fin_year'";
	$SelectSql	 =	mysql_query($SelectQuery);
	if($SelectSql == true){
		if(mysql_num_rows($SelectSql)>0){
			$List 	= mysql_fetch_object($SelectSql); 
			$april 	= $List->april; $may 	= $List->may; $june = $List->june; 	$july 	= $List->july;
			$aug 	= $List->aug; 	$sep 	= $List->sep; $oct 	= $List->oct; 	$nov 	= $List->nov;
			$dece 	= $List->dece; 	$jan 	= $List->jan; $feb 	= $List->feb; 	$march 	= $List->march;
			$TotalAmount = round(($april+$may+$june+$july+$aug+$sep+$oct+$nov+$dece+$jan+$feb+$march),2);
		}
	}
	return $TotalAmount;
}
function GetNextFinYearWorkTotalActual($sheetid,$PrevFY){
	$TotalAmount = 0; //echo $fin_year; exit;
	$ExpFinaYears 	= explode("-",$PrevFY);
	$StartYr 		= $ExpFinaYears[0];
	$EndYr 			= $ExpFinaYears[1];
	$FinaFDate 		= $StartYr."-04-01";
	$FinaTDate 		= $EndYr."-03-31";
	$SelectAbstBookQuery = "select * from abstractbook where sheetid = '$sheetid' and pass_order_date >= '$FinaFDate' and pass_order_date <= '$FinaTDate'";
	$SelectAbstBookSql 	= mysql_query($SelectAbstBookQuery);
	if($SelectAbstBookSql == true){
		if(mysql_num_rows($SelectAbstBookSql)>0){
			while($AList = mysql_fetch_object($SelectAbstBookSql)){
				$rbn 		= $AList->rbn;
				$amountA 	= $AList->slm_total_amount;// * $MList->rate * $MList->pay_percent / 100;
				$amountB 	= $AList->slm_total_amount_esc;
				$SAAmount 	= 0;
				$SelectSecuredAdvAmtQuery = "select sec_adv_amount from secured_advance where sheetid = '$sheetid' and rbn = '$rbn'";
				$SelectSecuredAdvAmtSql = mysql_query($SelectSecuredAdvAmtQuery);
				if($SelectSecuredAdvAmtSql == true){
					if(mysql_num_rows($SelectSecuredAdvAmtSql)>0){
						$SAList = mysql_fetch_object($SelectSecuredAdvAmtSql);
						$SAAmount = $SAList->sec_adv_amount;
					}
				}
				$amount 	  = round(($amountA + $amountB + $SAAmount),2);
				$TotalAmount  = $TotalAmount  + $amount;
			}
		}
	}
	return round(($TotalAmount/100000),2);
}

function getAssignedStaffWithName($sheetid)
{
	$StaffArr = array();
	$SelectQuery   	= "select assigned_staff from sheet where sheet_id = '$sheetid'";// and sheetid = '$sheetid'";
	$SelectSql 		= mysql_query($SelectQuery);
	$StaffIdList 	= mysql_fetch_object($SelectSql);
	$AssignedStaff 	= $StaffIdList->assigned_staff;
	$ExpAssignedStaff = explode(",",$AssignedStaff);
	if(count($ExpAssignedStaff)>0){
		foreach($ExpAssignedStaff as $staffid){
			$SelectStaffQuery = "select a.*, b.* from staff a inner join designation b on (a.designationid = b.designationid) where a.staffid = '$staffid'";
			$SelectStaffSql = mysql_query($SelectStaffQuery);
			if($SelectStaffSql == true){
				if(mysql_num_rows($SelectStaffSql)>0){
					while($List = mysql_fetch_object($SelectStaffSql)){
						$StaffName = $List->staffname;
						$DesigName = $List->designationname;
						array_push($StaffArr,$StaffName." - ".$DesigName);
					}
				}
			}
		}
	}
	return $StaffArr;
}

?>