<?php
ob_start();
require_once 'library/config.php';
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
	$Query = "SELECT schdule.rate, schdule.rebate_percent, schdule.rebate_percent, schdule.item_flag, schdule.supp_sheet_id, mbookdetail.remarks FROM schdule
		INNER JOIN mbookdetail ON (schdule.subdiv_id = mbookdetail.subdivid)
		INNER JOIN mbookheader ON (mbookheader.mbheaderid = mbookdetail.mbheaderid) 
		where mbookheader.active=1 and schdule.sheet_id='$id' AND schdule.subdiv_id='$subid'";
	$SQLQuery = mysql_query($Query);
    if ($SQLQuery == true) {
        $List = mysql_fetch_object($SQLQuery);
		$actual_rate = $List->rate;
		$rebate_perc = $List->rebate_percent;
		$rebate_rate = $actual_rate * $rebate_perc / 100;
		$rate_with_rebate = $actual_rate - $rebate_rate;
        $result= $List->rate."*".$List->remarks."*".$List->rebate_percent."*".$rate_with_rebate."*".$List->item_flag."*".$List->supp_sheet_id;
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
?>