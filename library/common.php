<?php
ob_start();
//require_once 'library/config.php';
function getsubdivname($id) {
$Query = "SELECT subdiv_name FROM subdivision where active=1  and subdiv_id ='$id'";
$SQLQuery = mysql_query($Query);
    if ($SQLQuery == true) {
        $subdiv = mysql_fetch_object($SQLQuery);
        $subdivname= $subdiv->subdiv_name;
    }
    else
    {
        $subdivname='';
    }
    return $subdivname;
}
function getscheduledescription($id) {
$shortnotes_sql = "select shortnotes from schdule WHERE subdiv_id = '$id' AND  active  = 1";
$shortnotes_query = mysql_query($shortnotes_sql);
if(mysql_num_rows($shortnotes_query)>0)
{
	 $scheduleshortnotes= mysql_fetch_object($shortnotes_query);
	 $description= $scheduleshortnotes->shortnotes;
	 
}
else
{
	$Query = "SELECT    DISTINCT mbookdetail.subdivid, schdule.description
	FROM    mbookdetail
	 INNER JOIN schdule   ON (mbookdetail.subdivid = schdule.subdiv_id) WHERE schdule.sno  <> '' AND mbookdetail.subdivid ='$id'";
	$SQLQuery = mysql_query($Query);
		if ($SQLQuery == true) {
			 $scheduledescription= mysql_fetch_object($SQLQuery);
			$description= $scheduledescription->description;
		}
		else
		{
			$description='';
		}
}
    return $description;
}
function getscheduledescriptionname($id) {
$Query = "
SELECT    schdule.description
FROM    subdivision
INNER JOIN schdule   ON (subdivision.subdiv_id = schdule.subdiv_id) WHERE schdule.sno <> '' AND subdivision.subdiv_name ='$id'";

$SQLQuery = mysql_query($Query);
    if ($SQLQuery == true) {
         $scheduledescription= mysql_fetch_object($SQLQuery);
        $description= $scheduledescription->description;
    }
    else
    {
        $description='';
    }
    return $description;
}
function getrunnaccbillno($id) {
$Query = "SELECT rbn FROM mbookgenerate WHERE sheet_id ='$id'";
$SQLQuery = mysql_query($Query);
    if ($SQLQuery == true) {
         $runnaccbill= mysql_fetch_object($SQLQuery);
        $runnaccbillno= $runnaccbill->rbn;
    }
    else
    {
        $runnaccbillno='';
    }
    return $runnaccbillno;
}
function getsubdivid($id) {
$Query = "SELECT subdiv_id FROM subdivision where active=1  and subdiv_name ='$id'";
$SQLQuery = mysql_query($Query);
    if ($SQLQuery == true) {
        $subdiv = mysql_fetch_object($SQLQuery);
        $subdivid= $subdiv->subdiv_id;
    }
    else
    {
        $subdivid='';
    }
    return $subdivid;
}
function getschduledetails ($id,$subid) {
	/*$Query = "SELECT schdule.rate, schdule.rebate_percent, schdule.rebate_percent, schdule.item_flag, schdule.supp_sheet_id, mbookdetail.remarks FROM schdule
		INNER JOIN mbookdetail ON (schdule.subdiv_id = mbookdetail.subdivid)
		INNER JOIN mbookheader ON (mbookheader.mbheaderid = mbookdetail.mbheaderid) 
		where mbookheader.active=1 and schdule.sheet_id='$id' AND schdule.subdiv_id='$subid'";*/
		$Query = "SELECT schdule.rate, schdule.rebate_percent, schdule.rebate_percent, schdule.item_flag, schdule.supp_sheet_id, schdule.per FROM schdule
		where schdule.sheet_id='$id' AND schdule.subdiv_id='$subid'";
	$SQLQuery = mysql_query($Query);
    if ($SQLQuery == true) {
        $List = mysql_fetch_object($SQLQuery);
		$actual_rate = $List->rate;
		$rebate_perc = $List->rebate_percent;
		$rebate_rate = $actual_rate * $rebate_perc / 100;
		$rate_with_rebate = $actual_rate - $rebate_rate;
        $result= $List->rate."*".$List->per."*".$List->rebate_percent."*".$rate_with_rebate."*".$List->item_flag."*".$List->supp_sheet_id;
    }
    else
    {
        $result='';
    }
    return $result;
}
/*function getschduledetails ($id,$subid) {
$Query = "SELECT  rate, rebate_percent, per FROM schdule
         where sheet_id='$id' AND subdiv_id='$subid'";
$SQLQuery = mysql_query($Query);
    if ($SQLQuery == true) {
        $List = mysql_fetch_object($SQLQuery);
		$actual_rate = $List->rate;
		$rebate_perc = $List->rebate_percent;
		$rebate_rate = $actual_rate * $rebate_perc / 100;
		$rate_with_rebate = $actual_rate - $rebate_rate;
        $result= $List->rate."*".$List->per."*".$List->rebate_percent."*".$rate_with_rebate;
    }
    else
    {
        $result='';
    }
    return $result;
}*/
function getdescriptions($id) {
$Querys = "SELECT    description
FROM    schdule   WHERE  subdiv_id ='$id'";
$SQLQuerys = mysql_query($Querys);
    if ($SQLQuerys == true) {
         $scheduledescriptions= mysql_fetch_object($SQLQuerys);
         $descriptiondata= $scheduledescriptions->description;
    }
    else
    {
        $descriptiondata='';
    }
    return $descriptiondata;
    
}
function getabsttotal($sheetid,$subdivid,$rbn) {
$AbstQuerys = "SELECT    abstquantity FROM    measurementbook   WHERE  sheetid ='$sheetid' AND subdivid ='$subdivid' AND rbn='$rbn' ";
$AbstSQLQuerys = mysql_query($AbstQuerys);
    if ($AbstSQLQuerys == true) {
         $AbsSQLQuerys= mysql_fetch_object($AbstSQLQuerys);
         $absttotal= $AbsSQLQuerys->abstquantity;
    }
    else
    {
        $absttotal='';
    }
    return $absttotal;
    
}
function getactual_rebaterate($sheetid,$subdivid) {
$Query = "SELECT rebate_percent FROM schdule where active=1 and subdiv_id ='$subdivid' and sheet_id = '$sheetid'";
$SQLQuery = mysql_query($Query);
    if ($SQLQuery == true) {
        $result = mysql_fetch_object($SQLQuery);
        $actual_rebate = $result->rebate_percent;
    }
    else
    {
        $actual_rebate='';
    }
    return (100-$actual_rebate);
}
function get_decimal_placed($subdivid,$sheetid)
{
	$Query = "SELECT  decimal_placed FROM schdule where active=1 and subdiv_id ='$subdivid' and sheet_id = '$sheetid'";
	$SQLQuery = mysql_query($Query);
	if ($SQLQuery == true)
	{
		$result = mysql_fetch_object($SQLQuery);
		$decimal_placed = $result->decimal_placed;
	}
	else
	{
		$decimal_placed = 3;
	}
	return $decimal_placed;
}
function get_mbook_startpage($mbookno,$sheetid)
{
	$Query = "SELECT allotmentid, mbpage FROM mbookallotment where sheetid ='$sheetid' and mbno = '$mbookno'";
	$SQLQuery = mysql_query($Query);
	if ($SQLQuery == true)
	{
		$result = mysql_fetch_object($SQLQuery);
		$mbook_page = $result->mbpage;
		$allotmentid = $result->allotmentid;
		$mbook_start_page = $mbook_page +1;
	}
	return $mbook_start_page."*".$allotmentid;
}
function getusername($staffid)
{
	$Query = "SELECT username FROM users where staffid ='$staffid' and active = 1";
	$SQLQuery = mysql_query($Query);
	if ($SQLQuery == true)
	{
		$result = mysql_fetch_object($SQLQuery);
		$username = $result->username;
	}
	return $username;
}
function getscheduledescription_new($id)
{
	$select_desc_sql = "select description, shortnotes from schdule where subdiv_id = '$id'";
	$select_desc_query = mysql_query($select_desc_sql);
	$result = mysql_fetch_object($select_desc_query);
	$itemdesc = $result->description;
	$shortnotes = $result->shortnotes;
	if($shortnotes == ""){ $description = $itemdesc; }
	else{ $description = $shortnotes; }
	return $description;
}
function getItemDetails($sheetid,$subdivid)
{
	$Itemdetails_sql 	= 	"SELECT sno, description, shortnotes, per, decimal_placed, rate, rebate_percent, deviate_qty_percent FROM schdule WHERE sheet_id = '$sheetid' AND subdiv_id = '$subdivid'";
	$Itemdetails_query 	= 	mysql_query($Itemdetails_sql);
	$ItemData			=	mysql_fetch_object($Itemdetails_query);
	$subdivname 		= 	$ItemData->sno;
	$Description 		= 	$ItemData->description;
	$Shortnotes 		= 	$ItemData->shortnotes;
	$ItemUnit 			= 	$ItemData->per;
	$ItemDecimalPlace 	= 	$ItemData->decimal_placed;
	$ItemRate 			= 	$ItemData->rate;
	$ItemRebatePercent	= 	$ItemData->rebate_percent;
	$ItemQtyDevPercent	= 	$ItemData->deviate_qty_percent;
	if($Shortnotes == "")
	{
		$ItemDescription	=	$Description;
	}
	else
	{
		$ItemDescription 	= 	$Shortnotes;
	}
	$OutPutStr	=	$subdivname."##@**@##".$ItemDescription."##@**@##".$ItemUnit."##@**@##".$ItemDecimalPlace."##@**@##".$ItemRate."##@**@##".$ItemRebatePercent."##@**@##".$ItemQtyDevPercent;
	return $OutPutStr;
}
function getdivisionname($sheetid,$divid)
{
	$select_div_sql = "select div_name from division where div_id = '$divid' and sheet_id = '$sheetid'";
	$select_div_query = mysql_query($select_div_sql);
	$result1 = mysql_fetch_object($select_div_query);
	$divname = $result1->div_name;
	
	$select_desc_sql = "select description, shortnotes from schdule where sno = '$divname' and sheet_id = '$sheetid'";
	$select_desc_query = mysql_query($select_desc_sql);
	$result2 = mysql_fetch_object($select_desc_query);
	$description = $result2->description;
	$shortnotes = $result2->shortnotes;
	if($shortnotes != "")
	{
		$description = $shortnotes;
	}
	
	return $divname."##*@*##".$description;
	//return $shortnotes;
}
function getzonename($sheetid,$zone_id)
{
	$select_zonename_sql = "select zone_name from zone where zone_id = '$zone_id' and sheetid = '$sheetid'";
	$select_zonename_query = mysql_query($select_zonename_sql);
	$result1 = mysql_fetch_object($select_zonename_query);
	$zone_name = $result1->zone_name;
	return $zone_name;
}
function getsheetdata($sheetid)
{
	$select_sheet_sql 	= "select short_name, tech_sanction, work_order_no, agree_no, computer_code_no from sheet where sheet_id = '$sheetid'";
	$select_sheet_query = mysql_query($select_sheet_sql);
	$result1 = mysql_fetch_object($select_sheet_query);
	$short_name 		= $result1->short_name;
	$tech_sanction 		= $result1->tech_sanction;
	$work_order_no 		= $result1->work_order_no;
	$agree_no 			= $result1->agree_no;
	$computer_code_no 	= $result1->computer_code_no;
	return $short_name."@#*#@".$tech_sanction."@#*#@".$work_order_no."@#*#@".$agree_no."@#*#@".$computer_code_no;
}
function getstafflevel($staffid)
{
	$select_staff_query 	= "select sroleid, levelid, staffname from staff where staffid = '$staffid' and active = 1";
	$select_staff_sql = mysql_query($select_staff_query);
	$result1 = mysql_fetch_object($select_staff_sql);
	$sroleid 		= $result1->sroleid;
	$levelid 		= $result1->levelid;
	$staffname 		= $result1->staffname;
	return $sroleid."@#*#@".$levelid."@#*#@".$staffname;
}
function getstaff_minmax_level()
{
	$select_staff_query = "select min(levelid) as minlevel, max(levelid) as maxlevel from staffrole where active = 1 and sectionid = 2";
	$select_staff_sql 	= mysql_query($select_staff_query);
	$result1 			= mysql_fetch_object($select_staff_sql);
	$minlevel 			= $result1->minlevel;
	$maxlevel 			= $result1->maxlevel;
	return $minlevel."@#*#@".$maxlevel;
}
function GetRoleName($levelid,$sectionid){
	$RoleName = "";
	$select_role_name_query	= "select role_name from staffrole where levelid = '$levelid' and sectionid = '$sectionid'";
	$select_role_name_sql	=	mysql_query($select_role_name_query);
	if($select_role_name_sql == true){
		if(mysql_num_rows($select_role_name_sql)>0){
			$List 	= mysql_fetch_object($select_role_name_sql);
			$RoleName 	= $List->role_name;
		}
	}
	return $RoleName;
}
function AccVerificationCheck($sheetid,$rbn,$mbookno,$genlevel,$levelid,$type)// Already Level 2 checked and Again The button will not enable.
{
	$check = 0;
	if($type == 'MB')
	{
	$select_check_query = "select * from send_accounts_and_civil where 
	sheetid='$sheetid' and rbn = '$rbn' and mbookno = '$mbookno' and genlevel = '$genlevel' and mb_ac = 'AC' and level_status = 'F'";
	}
	else if($type == 'SA')
	{
	$select_check_query = "select * from send_accounts_and_civil where 
	sheetid='$sheetid' and rbn = '$rbn' and mbookno = '$mbookno' and genlevel = '$genlevel' and sa_ac = 'AC' and level_status = 'F'";
	}
	else
	{
	$select_check_query = "select * from send_accounts_and_civil where 
	sheetid='$sheetid' and rbn = '$rbn' and mbookno = '$mbookno' and genlevel = '$genlevel' and ab_ac = 'AC' and level_status = 'F'";
	}
	$select_check_sql 	= mysql_query($select_check_query);
	$result1 			= mysql_fetch_object($select_check_sql);
	if($select_check_sql == true)
	{
		if(mysql_num_rows($select_check_sql)>0)
		{
			$check = 1;
		}
		else
		{
			$check = 0;
		}
	}
	else
	{
		$check = 0;
	}
	return $check;
}

/*function checkSendAccounts()
{
	$sheetArr = array();
	$select_sheet_query = "select distinct(sheetid) from send_accounts_and_civil where (mb_ac = 'SA' OR  sa_ac = 'SA' OR  ab_ac = 'SA')";
	$select_sheet_sql = mysql_query($select_sheet_query);
	if($select_sheet_sql == true)
	{
		if(mysql_num_rows($select_sheet_sql)>0)
		{
			while($SheetList = mysql_fetch_object($select_sheet_sql))
			{
				array_push($sheetArr,$SheetList->sheetid);
			}
		}
	}
	return $sheetArr;
}*/
function GetSupplementaryWorkTitle($supp_sheetid,$runn_acc_bill_no)
{
	$query 		= 	"SELECT supp_sheet_id, sheetid, sheet_name, work_order_no, work_name, short_name, tech_sanction, computer_code_no, name_contractor, agree_no, rbn, rebate_percent FROM sheet_supplementary WHERE supp_sheet_id ='$supp_sheetid' ";
	//echo $query;exit;
	$sqlquery 	= 	mysql_query($query);
	if ($sqlquery == true) 
	{
		$List 					= 	mysql_fetch_object($sqlquery);
		$work_name 				= 	$List->work_name; 
		$short_name 			= 	$List->short_name;   
		$tech_sanction 			= 	$List->tech_sanction;  
		$name_contractor 		= 	$List->name_contractor;   
		$ccno 					= 	$List->computer_code_no;  
		$agree_no 				= 	$List->agree_no; 
		$overall_rebate_perc 	= 	$List->rebate_percent; 
		//$runn_acc_bill_no 		= 	$rbn;
		$work_order_no 			= 	$List->work_order_no; 
		$table = "";
		$table = $table . "<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >";
		$table = $table . "<tr>";
		$table = $table . "<td width='17%' class=''>Name of work</td>";
		$table = $table . "<td width='43%' style='word-wrap:break-word' class=''>" .$work_name."</td>";
		$table = $table . "<td width='18%' class=''>Name of the contractor</td>";
		$table = $table . "<td width='22%' class='' colspan='3'>" . $name_contractor . "</td>";
		$table = $table . "</tr>";
		$table = $table . "<tr>";
		$table = $table . "<td class=''>Technical Sanction No.</td>";
		$table = $table . "<td class=''>" . $tech_sanction . "</td>";
		$table = $table . "<td class=''>Agreement No.</td>";
		$table = $table . "<td class='' colspan='3'>" . $agree_no . "</td>";
		$table = $table . "</tr>";
		$table = $table . "<tr>";
		$table = $table . "<td class=''>Work order No.</td>";
		$table = $table . "<td class=''>" . $work_order_no . "</td>";
		$table = $table . "<td class=''>Running Account bill No. </td>";
		$table = $table . "<td class=''>" . $runn_acc_bill_no . "</td>";
		$table = $table . "<td class='' align='right'>CC No. </td>";
		$table = $table . "<td class=''>" . $ccno . "</td>";
		$table = $table . "</tr>";
		//$table = $table . "<tr>";
		//$table = $table . "<td colspan ='4' class='labelprint' align='center'>Abstract Cost for ".$short_name." for the period of ".date("d/m/Y", strtotime($fromdate))." to ".date("d/m/Y", strtotime($todate))."</td>";
		//$table = $table . "</tr>";
		$table = $table . "</table>";
	}
	return $table;
}
function getSuppAggNoOf($sheetid,$supp_sheet_id)
{
	$select_supp_sql = "select no_of_supp_agree from sheet_supplementary where sheetid = '$sheetid' and supp_sheet_id = '$supp_sheet_id'";
	$select_supp_query = mysql_query($select_supp_sql);
	$result1 = mysql_fetch_object($select_supp_query);
	$supp_no_name = $result1->no_of_supp_agree;
	return $supp_no_name;
}

function getSecuredAdvanceAmt($sheetid,$rbn)
{
	$select_query = "select sec_adv_amount from secured_advance where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_sql = mysql_query($select_query);
	$result1 = mysql_fetch_object($select_sql);
	$sec_adv_amount = $result1->sec_adv_amount;
	return $sec_adv_amount;
}
function getEscalationAmt($sheetid,$rbn)
{
	$EscAmt = 0; $RevEscAmt = 0;
	$select_query1 = "select esc_total_amt from escalation where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_sql1 = mysql_query($select_query1);
	while($result1 = mysql_fetch_object($select_sql1)){
		$esc_amount = $result1->esc_total_amt;
		$EscAmt = $EscAmt + $esc_amount;
	}
	
	
	$select_query2 = "select esc_total_amt from revised_escalation where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_sql2 = mysql_query($select_query2);
	while($result2 = mysql_fetch_object($select_sql2)){
		$rev_esc_amount = $result2->esc_total_amt;
		$RevEscAmt = $RevEscAmt + $rev_esc_amount;
	}
	$TotalAmt = round(($EscAmt + $RevEscAmt),2);
	return $TotalAmt;
}
function getOtheSectionRABAmt($sheetid,$rbn)
{
	$RABAmt = 0; 
	$select_query1 = "select slm_total_amount from abstractbook where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_sql1 = mysql_query($select_query1);
	while($result1 = mysql_fetch_object($select_sql1)){
		$net_amount = $result1->slm_total_amount;
		$RABAmt = $RABAmt + $net_amount;
	}
	return $RABAmt;
}

function findNumericFromString($unit){
	$str 		= $unit;
	preg_match_all('!\d+!', $str, $matches);
	$OutStr 	= implode("",$matches[0]);
	if($OutStr == ""){
		$factor = 1;
	}else{
		$factor = $OutStr;
	}
	return $factor;
}

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

function getICNO($staffid)
{
	$select_icno_sql = "select staffcode from staff where staffid = '$staffid'";
	$select_icno_query = mysql_query($select_icno_sql);
	$result1 = mysql_fetch_object($select_icno_query);
	$icno = $result1->staffcode;
	return $icno;
}
function getWorkOrderValue($sheetid)
{
	$WOValue = 0;
	$select_soq_query 	= "SELECT total_quantity, rate, per FROM schdule where sheet_id= '$sheetid' AND sno != '0' and total_quantity > 0";
	$select_soq_sql		= mysql_query($select_soq_query);
	if($select_soq_sql == true){
		while($List = mysql_fetch_object($select_soq_sql)){
			$qty = $List->total_quantity;
			$rate = $List->rate;
			$unit = $List->per;
			$unitFactor = 1;
			$unitFactor = findNumericFromString($unit);
			$amount = round(($qty * $rate / $unitFactor),2);
			//$str .= $qty."*".$rate."*".$amount."@@";
			$WOValue = $WOValue + $amount;
		}
	}
	$WOValue = round($WOValue,2);
	return $WOValue;
}


function AccountsLevelAction($sheetid,$rbn,$level,$action){
	$select_al_as_query 	= "select * from al_as where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_al_as_sql 		= mysql_query($select_al_as_query);
	if($select_al_as_sql == true){
		$ALASList 	= mysql_fetch_object($select_al_as_sql);
		$AlLevel 	= $ALASList->al_level;
		//$AlStatus 	= $ALASList->status;
		$AlAsid 	= $ALASList->alasid;
	}
	$AlStatus = $level;
	$expAlLevel 	= explode(",",$AlLevel);
	$MinLevel 		= min($expAlLevel); 
	$MaxLevel 		= max($expAlLevel);
	 
	$index = array_search($AlStatus,$expAlLevel);
	if($index !== FALSE){
	 	$NextLevel 	= $expAlLevel[$index + 1];
	  	$PrevLevel 	= $expAlLevel[$index - 1];
	}
	if($action == "FW"){ 
		if($NextLevel == ""){
			$Status = "C";
		}else{
			$Status = $NextLevel;
			
		}
	}
	if($action == "BW"){ 
		if($PrevLevel == ""){
			$Status = "";
		}else{
			$Status = $PrevLevel;
		}
	}
	//if($Status != ""){
		
		//$insert_al_as_query = "insert into al_as_dt set alasid = '$AlAsid', sheetid = '$sheetid', rbn = '$rbn', level = '$level', action = '$action', staffid = '".$_SESSION['sid_acc']."', section = '".$_SESSION['staff_section']."', createddate = NOW()";
		//echo $insert_al_as_query;exit;
		//$insert_al_as_sql 	= mysql_query($insert_al_as_query);
	//}
	//$update_al_as_query = "update al_as set status = '$Status', createddate = NOW() where sheetid = '$sheetid' and rbn = '$rbn' and alasid = '$AlAsid'";
	$update_al_as_query = "update al_as set status = '$Status' where sheetid = '$sheetid' and rbn = '$rbn' and alasid = '$AlAsid'";
	//echo $update_al_as_query;exit;
	$update_al_as_sql 	= mysql_query($update_al_as_query);
	return $Status;
}

function AccountsLevelStatus($sheetid,$rbn,$mbookno,$zone_id,$mtype,$genlevel){
	$AlStatus = array();//"";
	$SABVeriCheck = 0;
	$ABSTVeriCheck = 0;
	/*$select_al_as_query 	= "select status from al_as where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_al_as_sql 		= mysql_query($select_al_as_query);
	if($select_al_as_sql == true){
		$ALASList 	= mysql_fetch_object($select_al_as_sql);
		$AlLevel 	= $ALASList->al_level;
		$AlStatus 	= $ALASList->status;
	}*/
	
	/*$select_al_as_query 	= "select AC_status, levelid from acc_log where sheetid = '$sheetid' and rbn = '$rbn' and mbookno = '$mbookno' 
							  and zone_id = '$zone_id' and mtype = '$mtype' and genlevel = '$genlevel'";
	$select_al_as_sql 		= mysql_query($select_al_as_query);
	if($select_al_as_sql == true){
		$ALASList 	= mysql_fetch_object($select_al_as_sql);
		$ACLevel 	= $ALASList->levelid;
		$ACStatus 	= $ALASList->AC_status;
		array_push($AlStatus,$ACLevel);
		array_push($AlStatus,$ACStatus);
	}*/
	
	$select_al_as_query 	= "select * from acc_log where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_al_as_sql 		= mysql_query($select_al_as_query);
	if($select_al_as_sql == true){
		while($ALASList 	= mysql_fetch_object($select_al_as_sql)){
			$ACLevel 	= $ALASList->levelid;
			$ACStatus 	= $ALASList->AC_status;
			$ACLevelids = $ALASList->staff_levelids;
			$expACLevelids = explode(",",$ACLevelids);
			$EndLevel = end($expACLevelids);
			if(($ALASList->mbookno == $mbookno)&&($ALASList->zone_id == $zone_id)&&($ALASList->mtype == $mtype)&&($ALASList->genlevel == $genlevel)){
				array_push($AlStatus,$ACLevel);
				array_push($AlStatus,$ACStatus);
				array_push($AlStatus,$EndLevel);
			}
			if(($ALASList->genlevel == 'staff')&&($ALASList->AC_status != 'A')&&($EndLevel != $ACLevel)){
				$SABVeriCheck++;
			}
			if(($ALASList->genlevel != 'abstract')&&($ALASList->AC_status != 'A')&&($EndLevel != $ACLevel)){
				$ABSTVeriCheck++;
			}
		}
		array_push($AlStatus,$SABVeriCheck);
		array_push($AlStatus,$ABSTVeriCheck);
	}
	
	return $AlStatus;
	//echo $ACLevelids;
}

function AccountsLevelTransaction($sheetid,$rbn,$level){
	$Result = array();
	$select_al_as_query 	= "select * from al_as where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_al_as_sql 		= mysql_query($select_al_as_query);
	if($select_al_as_sql == true){
		$ALASList 	= mysql_fetch_object($select_al_as_sql);
		$AlLevel 	= $ALASList->al_level;
		$AlAsid 	= $ALASList->alasid;
		$AlCurr 	= $ALASList->status;
	}
	$AlStatus = $level;
	$expAlLevel 	= explode(",",$AlLevel);
	$MinLevel 		= min($expAlLevel); 
	$MaxLevel 		= max($expAlLevel);
	 
	$index = array_search($AlStatus,$expAlLevel);
	if($index !== FALSE){
	 	$NextLevel 	= $expAlLevel[$index + 1];
	  	$PrevLevel 	= $expAlLevel[$index - 1];
	}
	$Result['Next'] = $NextLevel;
	$Result['Prev'] = $PrevLevel;
	$Result['Min'] 	= $MinLevel;
	$Result['Max'] 	= $MaxLevel;
	$Result['Curr'] = $AlCurr;
	if(($NextLevel != "")||($PrevLevel != "")){
		$Result['Check'] = 1;
	}else{
		if(count($expAlLevel) == 1){
			$Result['Check'] = 1;
		}else{
			$Result['Check'] = 0;
		}
	}
	return $Result;
}

function AccountsMbookStatus($WoAccLevelStr,$LevelStr,$CurrLevel,$Status){
	$WoAccLevelArr 	= explode(",",$WoAccLevelStr);
	$MinLevel 		= min($WoAccLevelArr); 
	$MaxLevel 		= max($WoAccLevelArr);
	
	//$LevelStr 		= "1,2,3,2,1,3,4,3";
	//$CurrLevel 		= 4;
	$LoginLevel 	= $_SESSION['levelid'];
	//echo $LoginLevel;exit;
	//$s = "";
	//$Status 		= "A";
	$expLevelStr 	= explode(",",$LevelStr);
	$EndLevel		= end($expLevelStr);
	if(in_array($LoginLevel,$expLevelStr)){ //$s="HI"; 
		if($EndLevel == $LoginLevel){ 
			if(($EndLevel == $MinLevel)&&($EndLevel != $MaxLevel)){ //NO
				if($Status == "R"){
					//return "Rejected To CIVIL";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
				}else if($Status == "A"){
					//return "Forwared to Next Level";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Forwared to Next Level</span>";
				}else{
					if($EndLevel == $CurrLevel){
						return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified</span>";
					}else{
						return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Forwared to Next Level</span>";
					}
				}
			}else if(($EndLevel == $MaxLevel)&&($EndLevel != $MinLevel)){ //NO
				if($Status == "R"){
					//return "Rejected to Previous level Accounts";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Return to Previous level</span>";
				}else if($Status == "A"){
					//return "Overall Accepted by Higher Level";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified & Accepted</span>";
				}else{
					//return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified</span>";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified & Accepted</span>";
				}
			}else if(($EndLevel == $MaxLevel)&&($EndLevel == $MinLevel)){ //NO
				if($Status == "R"){
					//return "Rejected To CIVIL";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
				}else if($Status == "A"){
					//return "Overall Accepted by Higher Level";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Accepted</span>";
				}else{
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified</span>";
				}
			}else if(($EndLevel > $MinLevel)&&($EndLevel < $MaxLevel)){ 
				if($EndLevel > $CurrLevel){ 
				//echo $CurrLevel;exit;
					//return "Rejected to Previous level Accounts";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Return to Previous level</span>";
				}else if($EndLevel < $CurrLevel){ 
					//return "Forwared to Next Level";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Forwared to Next Level</span>";
				}else{
					if($Status == "R"){
						//return "Rejected to Previous level Accounts";
						return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Return to Previous level</span>";
					}else if($Status == "A"){
						//return "Forwared to Next Level";
						return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Forwared to Next Level</span>";
					}else{
					
						if($EndLevel == $CurrLevel){
							return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Forwared to Next Level</span>";
						}else{
							return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified</span>";
						}
					
					}
				}
			}
		}else if($EndLevel > $LoginLevel){ 
			if($LoginLevel < $CurrLevel){
				//return "Forwared to Next Level";
				return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Forwared to Next Level</span>";
			}else if($LoginLevel == $CurrLevel){
				if($Status == "R"){
					if($LoginLevel == $MinLevel){
						//return "Rejected To CIVIL";
						return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Return from Higer Level</span>";//Rejected To CIVIL</span>";
					}else{
						//return "Rejected to Previous level Accounts";
						return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Return from Higer Level</span>";//Return to Previous level</span>";
					}
				}else if($Status == "A"){
					//return "Forwared to Next Level";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Forwared to Next Level</span>";
				}else{
				
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified</span>";
				
				
				}
			}else{
				return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Return to Previous level</span>";
			}
		}else if($EndLevel < $LoginLevel){  
			if($LoginLevel > $CurrLevel){
				//return "Rejected to Previous level Accounts";
				return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Return to Previous level</span>";
			}else if($LoginLevel == $CurrLevel){
				if($Status == "R"){
					//return "Rejected to Previous level Accounts";
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Return to Previous level</span>";
				}else if($Status == "A"){
					if($LoginLevel == $MaxLevel){
						//return "Overall Accepted by Higher Level";
						return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Accepted</span>";
					}else{
						//return "Forwared to Next Level";
						return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Forwared to Next Level</span>";
					}
				}else{
					return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified</span>";
				}
			}
		}
	}else{
		return "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified</span>";
	}
	
}
function CheckEBWBBillModify($sheetid,$rbn){
	$Modify = 1;
	$SelectQuery1	= "select * from measurementbook where sheetid = '$sheetid' and rbn = '$rbn'";
	$SelectSql1		=  mysql_query($SelectQuery1);
	if($SelectSql1 == true){
		if(mysql_num_rows($SelectSql1)>0){
			$Modify = 0;
		}
	}
	if($Modify == 1){
		$SelectQuery2	= "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn'";// and (mb_ac = 'SC' OR sa_ac = 'SC' OR sa_ac = 'SC')";
		$SelectSql2		= mysql_query($SelectQuery2);
		if($SelectSql2 == true){
			if(mysql_num_rows($SelectSql2)>0){
				$Modify = 0;
				while($List = mysql_fetch_object($SelectSql2)){
					$MbAc 	= $List->mb_ac;
					$SaAc 	= $List->sa_ac;
					$AbAc 	= $List->sa_ac;
					if($MbAc == "SC"){ $Modify = 1; }
					if($SaAc == "SC"){ $Modify = 1; }
					if($AbAc == "SC"){ $Modify = 1; }
				}
			}
		}
	}
	return $Modify;
}

function UpdateCivilViewlevel($sheetid, $rbn){
	$ViewLevelArr 	= array();
	$SelectQuery1	= "select send_civil_staff_ids from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn'";// and (mb_ac = 'SC' OR sa_ac = 'SC' OR sa_ac = 'SC')";
	$SelectSql1		= mysql_query($SelectQuery1);
	if($SelectSql1 == true){
		if(mysql_num_rows($SelectSql1)>0){
			$List1 			= mysql_fetch_object($SelectSql1);
			$SendStaff 		= $List1->send_civil_staff_ids;
			$ExpSendStaff 	= explode(",",$SendStaff);
			$SendStaffArr 	= array_unique($ExpSendStaff);
			foreach($SendStaffArr as $key1 => $Value){
				$SendStaff = $Value;
				$SelectQuery2 	= "select levelid from staff where staffid = '$SendStaff'";
				$SelectSql2 	= mysql_query($SelectQuery2);
				if($SelectSql2 == true ){
					if(mysql_num_rows($SelectSql2)>0){
						$List2 		= mysql_fetch_object($SelectSql2);
						$StaffLevel = $List2->levelid;
						array_push($ViewLevelArr,$StaffLevel);
					}
				}
				
				$SelectQuery3 	= "select check_meas_level from check_measure_level_assign where sheetid = '$sheetid' and active = 1 order by laid desc limit 1";
				$SelectSql3 	= mysql_query($SelectQuery3);
				if($SelectSql3 == true ){
					if(mysql_num_rows($SelectSql3)>0){
						$List3 			= mysql_fetch_object($SelectSql3);
						$StaffLevel2 	= $List3->check_meas_level;
						$ExpStaffLevel2 = explode(",",$StaffLevel2);
						$StaffLevel 	= max($ExpStaffLevel2);
						if(in_array($StaffLevel, $ViewLevelArr)){
							// Already Exists
						}else{
							array_push($ViewLevelArr,$StaffLevel);
						}
					}
				}
				
			}
		}
	}
	
	if(count($ViewLevelArr)>0){
		$ViewLevelStr = implode(",",$ViewLevelArr);
	}else{
		$ViewLevelStr = "";
	}
	$UpdateQuery = "update al_as set civil_view_level = '$ViewLevelStr' where sheetid = '$sheetid' and rbn = '$rbn'";
	$UpdateSql = mysql_query($UpdateQuery);
}

?>