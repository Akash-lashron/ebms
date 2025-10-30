<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "library/common.php";

$Sheetid			= $_POST['Sheetid'];
$Rbn				= $_POST['Rbn'];
$Quarter			= $_POST['Quarter'];
$MonthNo			= $_POST['MonthNo'];
$Type				= $_POST['Type'];
$MonthYearArrStr 	= json_decode(stripslashes($_POST['MonthYearArrStr']));
$YearArr 			= array();
if(count($MonthYearArrStr )>0){
	foreach($MonthYearArrStr as $key => $Value){
		$PostMonYear = $Value;
		$ExpPostMonYear = explode("-",$PostMonYear);
		$PostYear = $ExpPostMonYear[1];
		if(in_array($PostYear,$YearArr)){
			
		}else{
			array_push($YearArr,$PostYear);
		}
	}
}
$YearStr = implode(",",$YearArr);
$Arr1 				= array(); 
if($Type == "TCC"){
	$WhereClause = " and a.mat_category = '10CC'";
}else if($Type == "TCA"){
	$WhereClause = " and a.mat_category = '10CA'";
}else{
	$WhereClause = "";
}
$rows = array();
$SelectQuery 	= "select a.*, b.mat_pi_code from monthly_index a inner join material b on (a.mat_code = b.mat_code) 
				   where a.year IN ($YearStr)".$WhereClause;
$SelectSql 	 	= mysql_query($SelectQuery);
if($SelectSql == true){
	if(mysql_num_rows($SelectSql)>0){
		while($List = mysql_fetch_array($SelectSql)){
			$IndexYear 	= $List['year'];
			
			$IndexMon1 	= $List['jan'];
			if($IndexMon1 > 0){ 
				$MonthCode1 = "Jan-".$IndexYear.$List['mat_pi_code'];
				$rows[$MonthCode1] = $List['jan'];
			}
			$IndexMon2 	= $List['feb'];
			if($IndexMon2 > 0){ 
				$MonthCode2 = "Feb-".$IndexYear.$List['mat_pi_code'];
				$rows[$MonthCode2] = $List['feb'];
			}
			$IndexMon3 	= $List['mar'];
			if($IndexMon3 > 0){ 
				$IndexMon3 = "Mar-".$IndexYear.$List['mat_pi_code'];
				$rows[$IndexMon3] = $List['mar'];
			}
			$IndexMon4 	= $List['apr'];
			if($IndexMon4 > 0){ 
				$IndexMon4 = "Apr-".$IndexYear.$List['mat_pi_code'];
				$rows[$IndexMon4] = $List['apr'];
			}
			$IndexMon5 	= $List['may'];
			if($IndexMon5 > 0){ 
				$IndexMon5 = "May-".$IndexYear.$List['mat_pi_code'];
				$rows[$IndexMon5] = $List['may'];
			}
			$IndexMon6 	= $List['jun'];
			if($IndexMon6 > 0){ 
				$IndexMon6 = "Jun-".$IndexYear.$List['mat_pi_code'];
				$rows[$IndexMon6] = $List['jun'];
			}
			$IndexMon7 	= $List['jul'];
			if($IndexMon7 > 0){ 
				$IndexMon7 = "Jul-".$IndexYear.$List['mat_pi_code'];
				$rows[$IndexMon7] = $List['jul'];
			}
			$IndexMon8 	= $List['aug'];
			if($IndexMon8 > 0){ 
				$IndexMon8 = "Aug-".$IndexYear.$List['mat_pi_code'];
				$rows[$IndexMon8] = $List['aug'];
			}
			$IndexMon9 	= $List['sep'];
			if($IndexMon9 > 0){ 
				$IndexMon9 = "Sep-".$IndexYear.$List['mat_pi_code'];
				$rows[$IndexMon9] = $List['sep'];
			}
			$IndexMon10 = $List['oct'];
			if($IndexMon10 > 0){ 
				$IndexMon10 = "Oct-".$IndexYear.$List['mat_pi_code'];
				$rows[$IndexMon10] = $List['oct'];
			}
			$IndexMon11 = $List['nov'];
			if($IndexMon11 > 0){ 
				$IndexMon11 = "Nov-".$IndexYear.$List['mat_pi_code'];
				$rows[$IndexMon11] = $List['nov'];
			}
			$IndexMon12 = $List['dece'];
			if($IndexMon12 > 0){ 
				$IndexMon12 = "Dec-".$IndexYear.$List['mat_pi_code'];
				$rows[$IndexMon12] = $List['dece'];
			}
		}
	}
}
//print_r($YearArr);exit;
//$rows = array();
$SelectQuery 	= "select a.avg_pi_code, a.avg_pi_rate, a.esc_id, a.esc_rbn, b.pi_month, b.pi_rate from price_index a inner join price_index_detail b on (a.pid = b.pid) 
				   where a.sheetid = '$Sheetid' and a.esc_rbn = '$Rbn' and a.quarter = '$Quarter' and a.type = '$Type'";
$SelectSql 	 	= mysql_query($SelectQuery);
if($SelectSql == true){
	if(mysql_num_rows($SelectSql)>0){
		while($List = mysql_fetch_array($SelectSql)){
			//$rows[] = $List;
			$MonthYear 	= $List['pi_month'];
			$PiCode 	= $List['avg_pi_code'];
			$MonthCode 	= $MonthYear.$PiCode;
			$rows[$MonthCode] = $List['pi_rate'];
			//$Arr1[]	= $rows;
		}
	}
}
$OutPutArr = array("MonthCode"=>$Arr1);
echo json_encode($rows);
//echo $SelectQuery;
?> 