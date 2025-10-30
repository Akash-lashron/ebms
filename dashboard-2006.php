<?php
require_once 'library/config.php';
require_once 'library/functions.php';
include "library/common.php";
checkUser();
$_SESSION['login_return_url'] = $_SERVER['REQUEST_URI'];
//echo $_SESSION['login_return_url'];
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '-' . $mm . '-' . $yy; 
}

$minmax_level_str 		= getstaff_minmax_level();
$exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
$min_levelid 			= $exp_minmax_level_str[0];
$max_levelid 			= $exp_minmax_level_str[1];
	
	
$staffid_acc 			= $_SESSION['sid_acc'];
$staff_level_str 		= getstafflevel($staffid_acc);
$exp_staff_level_str 	= explode("@#*#@",$staff_level_str);
$staff_roleid 			= $exp_staff_level_str[0];
$staff_levelid 			= $exp_staff_level_str[1];

//echo $_SESSION['staff_section'];exit;
$user_design_sql = "select username from users WHERE userid = '$userid' AND active = 1";
$user_design_query = mysql_query($user_design_sql);
$userList = mysql_fetch_object($user_design_query);
$username = $userList->username;
$staff_sql = "select  staff.staffcode, staff.staffname, designation.designationname, staff.email, staff.designationid, staff.mobile, staff.intercom, staff.DOJ, staff.DOB, staff.image from staff 
INNER JOIN designation ON (designation.designationid = staff.designationid) 
WHERE staff.staffid = '$staffid' AND staff.active = 1 AND designation.active = 1";
//echo $staff_sql;
$staff_query = mysql_query($staff_sql);
$staffList = mysql_fetch_object($staff_query);
$staffname = $staffList->staffname;
//echo $staffname;
$icno = $staffList->staffcode;
$email = $staffList->email;
$designationname  = $staffList->designationname;
$designationid = $staffList->designationid;
$mobile = $staffList->mobile;
$intercom = $staffList->intercom;
$DOB = dt_display($staffList->DOB);
$DOJ = dt_display($staffList->DOJ);
$image = $staffList->image;
if($staffid == 0)
{
	$image = "profile_default.png";
}
$directory = "uploads/";
$staffimage = $directory.$image;

$wocheck = 0;
$WorkOrderQuery = "select sheet_id, work_order_no, work_name, short_name, tech_sanction, agree_no, name_contractor, worktype, rbn, assigned_staff from sheet where active = '1' ORDER BY sheet_id ASC";
$WorkOrderSql = mysql_query($WorkOrderQuery);
if($WorkOrderSql == true)
{
	$wocheck = 1;
}

$itemcheck = 0;
if($_GET['sheetid'] != "")
{
	$sheetid = $_GET['sheetid'];
}
else
{
	//$sheetid = 1;
	$DefaultQuery = "select sheet_id, assigned_staff from sheet where active = '1' ORDER BY sheet_id ASC";
	$DefaultSQL = mysql_query($DefaultQuery);
	if($DefaultSQL == true)
	{
		while($DSList = mysql_fetch_object($DefaultSQL))
		{
			$assigned_staff = $DSList->assigned_staff;
			$AssignStaff = explode(",",$assigned_staff);
			if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1))
			{
				$sheetid = $DSList->sheet_id;
			}
		}
	}
	//$DSList = mysql_fetch_object($WorkOrderSql);
	//$sheetid = $DSList->sheet_id;
}

	$ItemNoQuery = "select sno, subdiv_id, total_quantity, deviate_qty_percent, per, measure_type from schdule where sheet_id = '$sheetid' and subdiv_id != '0'";	
	$ItemNoSql = mysql_query($ItemNoQuery);
	if($ItemNoSql == true)
	{
		$itemcheck = 1;
	}

/*$RbnAmtQuery = "select measurementbook.subdivid, measurementbook.mbtotal, measurementbook.pay_percent, measurementbook.rbn, schdule.rate, schdule.sno, 
schdule.total_quantity, schdule.measure_type from measurementbook 
INNER JOIN schdule ON (schdule.subdiv_id = measurementbook.subdivid) where measurementbook.sheetid = '$sheetid' and  schdule.sheet_id = '$sheetid' and ((measurementbook.part_pay_flag = 0) OR (measurementbook.part_pay_flag = 1))  
ORDER BY measurementbook.rbn ASC, measurementbook.subdivid ASC";*/
$RbnAmtQuery = "select measurementbook.subdivid, measurementbook.mbtotal, measurementbook.pay_percent, measurementbook.rbn, schdule.rate, schdule.sno, 
schdule.total_quantity, schdule.measure_type, measurementbook.part_pay_flag from measurementbook 
INNER JOIN schdule ON (schdule.subdiv_id = measurementbook.subdivid) where measurementbook.sheetid = '$sheetid' and schdule.sheet_id = '$sheetid' and schdule.subdiv_id != 0 
and (measurementbook.part_pay_flag = '0'  OR measurementbook.part_pay_flag = '1' )ORDER BY measurementbook.rbn ASC, measurementbook.subdivid ASC";
//echo $RbnAmtQuery;
$RbnAmtSql = mysql_query($RbnAmtQuery);
$RbnSteelAmt = 0; $RbnStructAmt = 0; $RbnGeneralAmt = 0; $PrevRbn = ""; $PrevType = ""; $Prevamount = 0; $DonutAmount = 0; $UsedWoCost = 0;
$RbnData = "[";
$SteelAmtData = "[";
$StructAmtData = "[";
$GeneralAmtData = "[";//['RAB-1', 'RAB-2', 'RAB-3', 'RAB-4', 'RAB-5', 'RAB-6'],
$subdividList = "";
$DonutData = "";
$DonutDataArr = array();
if($RbnAmtSql == true)
{
	if(mysql_num_rows($RbnAmtSql)>0)
	{
		while($RbnAmtList = mysql_fetch_object($RbnAmtSql))
		{
			$amount = ($RbnAmtList->mbtotal) * ($RbnAmtList->rate) * ($RbnAmtList->pay_percent)/100;
			$CurrRbn = $RbnAmtList->rbn;
			$CurrType = $RbnAmtList->measure_type;
			if(($RbnAmtList->part_pay_flag == 0) || ($RbnAmtList->part_pay_flag == 1))
			{
				$subdividList .= $RbnAmtList->subdivid."*".$RbnAmtList->sno."*".$RbnAmtList->mbtotal."*".$RbnAmtList->total_quantity."@";
			}
			
			if($PrevRbn != "")
			{
				if($CurrRbn != $PrevRbn)
				{
					$DonutDataArr[] = array('RBN' => 'RBN'."$PrevRbn".' - Total Amount' ,'Amount' => "$DonutAmount");
					$UsedWoCost = $UsedWoCost+$DonutAmount;
					$DonutAmount = 0;
					
					$SteelAmtData = $SteelAmtData.(round($RbnSteelAmt/100000,2)).",";
					$RbnSteelAmt = 0;
					$StructAmtData = $StructAmtData.(round($RbnStructAmt/100000,2)).",";
					$RbnStructAmt = 0;
					$GeneralAmtData = $GeneralAmtData.(round($RbnGeneralAmt/100000,2)).",";
					$RbnGeneralAmt = 0;
					
				}
			}
			$DonutAmount = $DonutAmount + $amount;
			
			if(($PrevRbn != "") && ($PrevRbn != $CurrRbn))
			{
				$RbnData = $RbnData."'RAB-".$PrevRbn."',";
			}
			
			
			if($CurrType == 's')
			{
				$RbnSteelAmt = ($RbnSteelAmt + $amount);
			}
			else if($CurrType == 'st')
			{
				$RbnStructAmt = ($RbnStructAmt + $amount);
			}
			else
			{
				$RbnGeneralAmt = ($RbnGeneralAmt + $amount);
			}
			
			
			
			/*if($PrevType == 's')
			{
				$RbnSteelAmt = ($RbnSteelAmt + $Prevamount);
				if(($PrevRbn != "") && ($PrevRbn != $CurrRbn))
				{
					$SteelAmtData = $SteelAmtData.($RbnSteelAmt/100000).",";
					$RbnSteelAmt = 0;
				}
			}
			else if($PrevType == 'st')
			{
				$RbnStructAmt = ($RbnStructAmt + $Prevamount);
				if(($PrevRbn != "") && ($PrevRbn != $CurrRbn))
				{
					$StructAmtData = $StructAmtData.($RbnStructAmt/100000).",";
					$RbnStructAmt = 0;
				}
				$StructRbn = CurrRbn;
			}
			else
			{
				$RbnGeneralAmt = ($RbnGeneralAmt + $Prevamount);
				if(($PrevRbn != "") && ($PrevRbn != $CurrRbn))
				{
					$GeneralAmtData = $GeneralAmtData.($RbnGeneralAmt/100000).",";
					$RbnGeneralAmt = 0;
				}
				$GeneralRbn = CurrRbn;
			}*/
			$PrevRbn = $CurrRbn;
			$Prevamount = $amount;
			$PrevType = $CurrType;
			$PrevDonutAmount = $DonutAmount;
		}
		$RbnData = $RbnData."'RAB-".$PrevRbn."',";
		$DonutDataArr[] = array('RBN' => 'RBN'."$PrevRbn".' - Total Amount' ,'Amount' => "$PrevDonutAmount");
		$UsedWoCost = $UsedWoCost+$DonutAmount;
		
		//if($PrevType == 's')
		//{
			//$RbnSteelAmt = ($RbnSteelAmt + $Prevamount);
		//}
		//else if($PrevType == 'st')
		//{
			//$RbnStructAmt = ($RbnStructAmt + $Prevamount);
		//}
		//else
		//{
			//$RbnGeneralAmt = ($RbnGeneralAmt + $Prevamount);
		//}
		$SteelAmtData = $SteelAmtData.(round($RbnSteelAmt/100000,2)).",";
		$StructAmtData = $StructAmtData.(round($RbnStructAmt/100000,2)).",";
		$GeneralAmtData = $GeneralAmtData.(round($RbnGeneralAmt/100000,2)).",";
			
		$RbnData = rtrim($RbnData,',')."]";
		//echo $RbnData;
		$SteelAmtData = rtrim($SteelAmtData,',')."]";
		$StructAmtData = rtrim($StructAmtData,',')."]";
		$GeneralAmtData = rtrim($GeneralAmtData,',')."]";
		//echo $RbnGeneralAmt."<br/>";
	}
	else
	{
		$RbnData = rtrim($RbnData,',')."0]";
		$SteelAmtData = rtrim($SteelAmtData,',')."0]";
		$StructAmtData = rtrim($StructAmtData,',')."0]";
		$GeneralAmtData = rtrim($GeneralAmtData,',')."0]";
	}
}

//echo $subdividList."<br/>";exit;
//print_r($DonutData)."<br/>";
if($subdividList != "")
{
	$Previtemid = "";
	$PieChartdata = "[";
	$explodeSubdivid = explode("@",$subdividList);
	natsort($explodeSubdivid);
	$implodeSubdivid = implode("*",$explodeSubdivid);
//echo $implodeSubdivid."<br/>";exit;
	$PieChartList = explode("*",trim($implodeSubdivid,"*"));
	for($x1=0; $x1<count($PieChartList); $x1+=4)
	{
		$itemid = $PieChartList[$x1+0]; 
		$itemname = $PieChartList[$x1+1];
		$itemqty = $PieChartList[$x1+2];
		$itemOverallQty = $PieChartList[$x1+3];
		if($Previtemid != "")
		{
			if($itemid != $Previtemid)
			{
				$usedPercent = round(($itmqtyTotal*100/$PrevitemOverallqty),2);
				$PieChartdata = $PieChartdata."['".$Previtemname." - ".$usedPercent."%',".$usedPercent."],";
				$itmqtyTotal = 0;
			}
			
		}
		$itmqtyTotal = $itmqtyTotal + $itemqty;
		$Previtemid = $itemid;
		$Previtemname = $itemname;
		$Previtemqty = $itemqty;
		$PrevitemOverallqty = $itemOverallQty;
	}
	$usedPercent = round(($itmqtyTotal*100/$PrevitemOverallqty),2);
	$PieChartdata = $PieChartdata."['".$Previtemname." - ".$usedPercent."%',".$usedPercent."],";
	$itmqtyTotal = 0;
	$PieChartdata = rtrim($PieChartdata,',')."]";
}
else
{
	$PieChartdata = "[]";	
}
//echo $PieChartdata;
$TotalCostQuery ="SELECT DISTINCT schdule.sno,schdule.sch_id, schdule.subdiv_id, schdule.sheet_id, schdule.description, schdule.total_quantity, 
schdule.rate, schdule.per, schdule.total_amt, schdule.subdiv_id, schdule.page_no, sheet.rebate_percent  
FROM schdule INNER JOIN sheet ON (schdule.sheet_id = sheet.sheet_id)
where schdule.sheet_id = '$sheetid' AND schdule.sno != '0' ";
//echo $TotalCostQuery;
$TotalCostSql=mysql_query($TotalCostQuery);
$TotalWoCost = 0;
if($TotalCostSql == true)
{
	if(mysql_num_rows($TotalCostSql)>0)
	{
		while($CostList = mysql_fetch_object($TotalCostSql))
		{
			$ItemCost = $CostList->rate * $CostList->total_quantity;
			
			$TotalWoCost = $TotalWoCost + $ItemCost;
			$RebatePerc = $CostList->rebate_percent;
			//echo $ItemCost."<br/>";
		}
		$RebateAmt = $TotalWoCost * $RebatePerc/100;
		$TotalWoCost = $TotalWoCost - $RebateAmt;
	}
}
//echo $RebateAmt;
$RemainCost = $TotalWoCost - $UsedWoCost;
if($UsedWoCost>$TotalWoCost)
{
	$Overall_dev_qty_cost = $UsedWoCost - $TotalWoCost;
	$Dev_qty_data = "Deviated Qty Cost is Rs. ".number_format($Overall_dev_qty_cost,2);
	$tot_cost_data = "Total Cost is Rs. ".number_format($UsedWoCost,2);
}
else
{
	$Dev_qty_data = "";
	$tot_cost_data = "";
}
$DonutDataArr[] = array('RBN' => 'Balance Amount'."" ,'Amount' => "$RemainCost");
$DonutJsonData =  json_encode($DonutDataArr);
//print_r($DonutDataArr);
//echo $TotalWoCost." - ".$UsedWoCost." = ".$RemainCost;
function getGeneralItemQtyPercent($subdivid)
{
	/*$qty = 0;
	$QtyQuery = "select measurement_contentarea from mbookdetail where subdivid = '$subdivid' and mbdetail_flag != 'd'";
	$QtySql = mysql_query($QtyQuery);
	if($QtySql == true)
	{
		if(mysql_num_rows($QtySql)>0)
		{
			while($QtyList = mysql_fetch_object($QtySql))
			{
				$qty = $qty+$QtyList->measurement_contentarea;
			}
		}
	}*/
	$qty = 0;
	$QtyQuery = "select mbtotal from mbookgenerate_staff where subdivid = '$subdivid'";
	$QtySql = mysql_query($QtyQuery);
	if($QtySql == true)
	{
		if(mysql_num_rows($QtySql)>0)
		{
			while($QtyList = mysql_fetch_object($QtySql))
			{
				$qty = $qty+$QtyList->mbtotal;
			}
		}
	}
	return $qty;
}
function getSteelItemQtyPercent($subdivid)
{
	$qty = 0; $totalweight = 0;
	$total_8 = 0;$total_10 = 0;$total_12 = 0;$total_16 = 0;$total_20 = 0;$total_25 = 0;$total_28 = 0;$total_32 = 0;$total_36 = 0;
	$totalweight_8 = 0;$totalweight_10 = 0;$totalweight_12 = 0;$totalweight_16 = 0;$totalweight_20 = 0;$totalweight_25 = 0;$totalweight_28 = 0;$totalweight_32 = 0;$totalweight_36 = 0;
	$QtyQuery = "select measurement_contentarea, measurement_dia from mbookdetail where subdivid = '$subdivid' and mbdetail_flag != 'd'";
	$QtySql = mysql_query($QtyQuery);
	if($QtySql == true)
	{
		if(mysql_num_rows($QtySql)>0)
		{
			while($QtyList = mysql_fetch_object($QtySql))
			{
				$CArea = $QtyList->measurement_contentarea;
				$dia = $QtyList->measurement_dia;
						if($dia == 8){ $total_8 = $total_8 + $CArea; }
						if($dia == 10){ $total_10 = $total_10 + $CArea; }
						if($dia == 12){ $total_12 = $total_12 + $CArea; }
						if($dia == 16){ $total_16 = $total_16 + $CArea; }
						if($dia == 20){ $total_20 = $total_20 + $CArea; }
						if($dia == 25){ $total_25 = $total_25 + $CArea; }
						if($dia == 28){ $total_28 = $total_28 + $CArea; }
						if($dia == 32){ $total_32 = $total_32 + $CArea; }
						if($dia == 36){ $total_36 = $total_36 + $CArea; }
			}
			$totalweight_8 = round(($total_8 * 0.395),3);
			$totalweight_10 = round(($total_10 * 0.617),3);
			$totalweight_12 = round(($total_12 * 0.888),3);
			$totalweight_16 = round(($total_16 * 1.578),3);
			$totalweight_20 = round(($total_20 * 2.466),3);
			$totalweight_25 = round(($total_25 * 3.853),3);
			$totalweight_28 = round(($total_28 * 4.834),3);
			$totalweight_32 = round(($total_32 * 6.313),3);
			$totalweight_36 = round(($total_36 * 7.990),3);
			$totalweight = $totalweight+round(($totalweight_8+$totalweight_10+$totalweight_12+$totalweight_16+$totalweight_20+$totalweight_25+$totalweight_28+$totalweight_32+$totalweight_36),3);
			$TotQty_mt = round(($totalweight/1000),2);
			$qty = $TotQty_mt;
		}
	}
	return $qty;
}

/*if($_SESSION['staff_section'] != 2)
{
	$accounts_comments_query 	= "select distinct(measurementbook_temp.subdivid), measurementbook_temp.sheetid, measurementbook_temp.accounts_remarks , 
	 sheet.short_name, measurementbook_temp.rbn from measurementbook_temp 
	INNER JOIN sheet ON (measurementbook_temp.sheetid = sheet.sheet_id)
	where measurementbook_temp.accounts_remarks != ''";
	$accounts_comments_sql 		= mysql_query($accounts_comments_query);
	$A_C_Count = 0; $ac_prev_sheetid = ""; $A_C_Sheet_Count = 0; $A_C_Count_1 = 0;
	if($accounts_comments_sql == true)
	{
		if(mysql_num_rows($accounts_comments_sql)>0)
		{
			while($ACList = mysql_fetch_object($accounts_comments_sql))
			{
				$ac_curr_sheetid = $ACList->sheetid;
				if($ac_curr_sheetid != $ac_prev_sheetid)
				{
					$AccountsCommentData .= $ACList->sheetid."@#*#@".$ACList->rbn."@#*#@".$ACList->short_name."@#*#@";
					$A_C_Sheet_Count++;
					
				}
				$ac_prev_sheetid = $ac_curr_sheetid;
				$A_C_Count++; $A_C_Count_1++;
			}
			$AccountsCommentData = rtrim($AccountsCommentData,"@#*#@");
		}
		else
		{
			$AccountsCommentData = "";
		}
	}
	$A_C_Count_2 = 0;
	$select_sheetid_query = "select distinct(sheetid) from measurementbook_temp";
	$select_sheetid_sql = mysql_query($select_sheetid_query);
	if($select_sheetid_sql == true)
	{
		if(mysql_num_rows($select_sheetid_sql)>0)
		{
			while($SheetIdList = mysql_fetch_object($select_sheetid_sql))
			{
				$accept_sheetid = $SheetIdList->sheetid;
				$selct_accept_query = "select mbookno, zone_id, mtype, genlevel
				from send_accounts_and_civil where (mb_ac = 'AC' OR sa_ac = 'AC' OR ab_ac = 'AC') and sheetid = '$accept_sheetid'";
				$selct_accept_sql = mysql_query($selct_accept_query);
				if($selct_accept_sql == true)
				{
					if(mysql_num_rows($selct_accept_sql)>0)
					{
						while($AcceptMbook = mysql_fetch_object($selct_accept_sql))
						{
							$accept_mbook_no = $AcceptMbook->mbookno;
							$accept_zone_id = $AcceptMbook->zone_id;
							$accept_mtype = $AcceptMbook->mtype;
							$accept_genlevel = $AcceptMbook->genlevel;
							if($accept_zone_id != 0)
							{
								$accept_zone_name = getzonename($accept_sheetid,$accept_zone_id);
							}
							else
							{
								$accept_zone_name = "";
							}
							if($accept_genlevel == 'abstract')
							{
								$accept_msg = "Abstract";
							}
							else if($accept_genlevel == 'composite')
							{
								$accept_msg = "Sub-Abstract";
							}
							else
							{
								if($accept_mtype == 'S')
								{
									$accept_msg = "Steel (".$accept_zone_name.") ";
								}
								else
								{
									$accept_msg = "General (".$accept_zone_name.") ";
								}
							}
							
							$accept_msg_mbookno .= $accept_msg." - MBook No. ".$accept_mbook_no." - Accepted."."@#*#@";
							$A_C_Count++;
							$A_C_Count_2++;
						}
					}
				}
			}
		}
	}
	
}*/
//echo $DonutJsonData;exit;
?>

<link rel="stylesheet" href="dashboard/css/bootstrap.min.css">
<!--<link type='text/css' href='css/basic.css' rel='stylesheet' media='screen' />
<script type='text/javascript' src='js/basic_model_jquery.js'></script>
<script type='text/javascript' src='js/jquery.simplemodal.js'></script>-->
<?php include "Header.html"; ?>
<!--<link rel="stylesheet" href="dashboard/css/font-awesome.css">
<link rel="stylesheet" href="dashboard/css/font-awesome.min.css">-->
<script src="dashboard/js/bootstrap.min.js"></script>
<script src="dashboard/js/jquery.min.js"></script>
<style>
	.dashboardheader
	{
		height:25px;
		background-color:#fcfcfc;
		border:1px solid #F7F7F7;
		color:#03a9f4;
		vertical-align:middle;
		line-height:25px;
	}
	.leftsection
	{
		height:700px;
		width:250px;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
	}
	.contenttsection
	{
		height:700px;
		width:588px;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-left:1px;
		margin-top:2px;
		margin-right:1px;
	}
	.rightsection
	{
		height:700px;
		width:235px;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
	}
	.topcontentarea
	{
		height:320px;
		width:588px;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
	}
	.leftsectionheader
	{
		height:25px;
		background-color:#ef535e;
		border:1px solid #ef535e;
		color:#ffffff;
		vertical-align:middle;
		line-height:25px;
		text-align:center;
	}
	.contenttopheader
	{
		height:25px;
		background-color:#1fc08e;
		border:1px solid #1fc08e;
		color:#ffffff;
		vertical-align:middle;
		line-height:25px;
		text-align:center;
	}
	.rightsectionheader
	{
		height:25px;
		background-color:#f39c12;
		border:1px solid #f39c12;
		color:#ffffff;
		vertical-align:middle;
		line-height:25px;
		text-align:center;
	}
	.contentbottompheader
	{
		height:25px;
		width:588px;
		background-color:#39b5b9;/*#fe6672;*/
		border:1px solid #39b5b9;
		float:left;
		color:#ffffff;
		vertical-align:middle;
		line-height:25px;
		text-align:center;
	}
	.bottomcontentarea
	{
		height:320px;
		width:588px;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
	}
	.leftdivmenuhead
	{
		height:47px;
		background-color:#3198db;
		margin-top:0px;
		line-height:47px;
		text-align:center;
		color:white;
	}
	.leftdivmenuhead1
	{
		height:35px;
		background-color:#3598db;/*#63a8eb;*/
		margin-top:1px;
		line-height:35px;
		text-align:center;
		color:#ffffff;
	}
	.leftdivmenu
	{
		/*height:47px;*/
		min-height:35px;
		background-color:#FFFFFF;
		border-bottom:1px solid #E4E4E4;
		vertical-align:middle;
		/*line-height:35px;*/
		line-height:25px;
		text-align:center;
		cursor:pointer;
		color:#0E02EA;
		font-weight:bold;
		font-size:11px;
	}
	.leftdivmenu:hover
	{
		background-color:#EFEFEF;
		color:#062086;
	}
	.stackbarchart
	{
		/*height:240px;*/
		height:330px;
		overflow:scroll;
	}
</style>
<script>
	function changeData(obj)
	{
		var sheetid = obj.id;
		//var workname = document.getElementById("txt_shortname_"+sheetid).value ;
		var url = "dashboard.php?sheetid="+sheetid;//+"&workname="+workname;
		window.location.replace(url);
	}
</script>
<script type="text/javascript">
$(function () {
    $('#barchart').highcharts({
        title: {
            text: ''
        },
		subtitle: {
            text: 'BAR Chart',
			/*style: {
					 color: '#F00',
					 font: 'Verdana'
				  }*/
        },
        xAxis: {
            categories: <?php echo $RbnData; ?>,
        },
		
        labels: {
            items: [{
                html: 'Total fruit consumption',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'red'
                }
            }]
        },
        series: [{
            type: 'column',
            name: 'General',
			color: '#3C6C9D',
            data: <?php echo $GeneralAmtData; ?>
        }, {
            type: 'column',
            name: 'Steel',
			color: '#6EA45B',
            data: <?php echo $SteelAmtData; ?>
        }, {
            type: 'column',
            name: 'Structural Steel',
			color: '#CB423E',
            data: <?php echo $StructAmtData; ?>
        }, 
		]
    });
	
	$('#piechart').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: ''
        },
        subtitle: {
            text: 'PIE Chart'
        },
        plotOptions: {
            pie: {
                innerSize: 0,
                depth: 45
            }
        },
        series: [{
            name: 'Used %',
            data: <?php echo $PieChartdata; ?>
        }]
    });
});
</script>
<script src="dashboard/highcharts.js"></script>
<script src="dashboard/highcharts-3d.js"></script>
<script src="dashboard/modules/exporting.js"></script>
<script src="dashboard/lib/amcharts.js"></script>
<script src="dashboard/lib/pie.js"></script>
<script>
var chartData = <?php echo $DonutJsonData; ?>/*[ { "RBN":"RBN1","Amount":"3419859.7" },{ "RBN":"RBN2","Amount":"14069496.5" },{ "RBN":"RBN3","Amount":"13554093.5" }];*/
/*var chartData = [ {
    "country": "a",
    "visits": 7252
  }, {
    "country": "b",
    "visits": 8082
  }, {
    "country": "c",
    "visits": 18009
  }, {
    "country": "d",
    "visits": 13022
  }, {
    "country": "e",
    "visits": 11202
  }, {
    "country": "f",
    "visits": 9104
  }, {
    "country": "g",
    "visits": 6804
  }, {
    "country": "h",
    "visits": 7101
  } ];	*/
 //alert(chartData) 
var chart = AmCharts.makeChart( "chartdiv", {
  "type": "pie",
  "theme": "none",
  "titles": [ {
    "text": "<?php echo "Work Order Cost is Rs. ".number_format($TotalWoCost,2); ?>",
    "size": 11
  },{
    "text": "<?php echo $Dev_qty_data; ?>",
    "size": 11,
	"color": "red",
  },{
    "text": "<?php echo $tot_cost_data; ?>",
    "size": 11,
	"color": "darkgreen",
  }],
  "dataProvider": chartData,
  "valueField": "Amount",
  "titleField": "RBN",
  "startEffect": "elastic",
  "startDuration": 2,
  "labelRadius": 15,
  "innerRadius": "50%",
  "depth3D": 10,
  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
  "labelText": "",
  "angle": 15,
  "export": {
    "enabled": true
  }
} );
</script>

<style>
.popuptitle
{
	background-color:#0A9CC5;
	font-weight:bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFFFFF;
	line-height:25px;
	border:1px solid #9b9da0;
}
.transparent_class {
  /* IE 8 */
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";

  /* IE 5-7 */
  filter: alpha(opacity=50);

  /* Netscape */
  -moz-opacity: 0.5;

  /* Safari 1.x */
  -khtml-opacity: 0.5;

  /* Good browsers */
  opacity: 0.5;
}
</style>
<body class="page1" id="top">
 <!--==============================header=================================-->
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
  <?php include_once("Menu.php"); ?>
  <!--==============================Content=================================-->
  <div class="content">
    <div class="container_12">
       <div class="grid_12">
         <blockquote id="bq1" class="bq1 message" style="border:1px solid #FFFFFF; background-color:#FFFFFF">
		 	<!--<div class="dashboardheader">
				&nbsp;Dashboard
			</div>-->
<input type="hidden" name="txt_ac_count" id="txt_ac_count" value="<?php echo $A_C_Count; ?>">
<?php 
//echo "ferwgrg".$_SESSION['staff_section'];
if($_SESSION['staff_section'] == 2)
{
?>
	<!--<div style="text-align:right; font-weight:normal;">&nbsp;<a href="ChangePassword.php"><u>Change Password</u></a>&nbsp;&nbsp;&nbsp;</div>-->
	<div class="label" style="text-align:center">Welcome Account Section User - Shri. / Smt. <?php echo $_SESSION['username']; ?></div>
	<div style="width:100%; height:97%" class="">
		<img src="images/accounts_bg.jpg" height="100%" width="100%">
	</div>
<?php
	$count_sa = 0;  $alet_msg_acc = ""; $ac2 = 1;
	$alet_msg_acc .= "<table width='650px' bgcolor='red' style='border: 1px solid #A9A9A9' class='label'>";
	$select_sheet_sa_query = "select distinct(sheetid), rbn from measurementbook_temp";
	//echo $select_sheet_sa_query;
	$select_sheet_sa_sql = mysql_query($select_sheet_sa_query);
	if($select_sheet_sa_sql == true)
	{
		if(mysql_num_rows($select_sheet_sa_sql)>0)
		{
			while($SaSheetList = mysql_fetch_object($select_sheet_sa_sql))
			{
				$sheetid_sa = $SaSheetList->sheetid;
				$rbn_sa 	= $SaSheetList->rbn;
				if($staff_levelid == $min_levelid)
				{
				$select_send_account_query = "select COUNT(*) as count_sa from send_accounts_and_civil where (mb_ac = 'SA' OR sa_ac = 'SA' OR ab_ac = 'SA') and sheetid = '$sheetid_sa' and rbn = '$rbn_sa'";
				}
				else
				{
				$select_send_account_query = "select COUNT(*) as count_sa from send_accounts_and_civil where level_status = 'P' and level = '$staff_levelid' and sheetid = '$sheetid_sa' and rbn = '$rbn_sa'";
				}
				//echo $select_send_account_query."<br/>";
				$select_send_account_sql = mysql_query($select_send_account_query );
				if($select_send_account_sql == true)
				{
					$SaList = mysql_fetch_object($select_send_account_sql);
					$count_sa_temp = $SaList->count_sa;
					$count_sa = $count_sa+$count_sa_temp;
					if($count_sa_temp>0)
					{
						$sheet_data_sa 		= 	getsheetdata($sheetid_sa);
						$exp_sheet_data_sa 	= 	explode("@#*#@",$sheet_data_sa);
						$short_name_sa 		= 	$exp_sheet_data_sa[0];
						$tech_sanct_sa 		= 	$exp_sheet_data_sa[1];
						$aggre_no_sa 		= 	$exp_sheet_data_sa[3];
//$alet_msg_acc .= "<tr><td colspan='4' align='center' style='background-color:#E6E6FA;'>".($ac2).") ".$short_name_sa."</td></tr>";
//$alet_msg_acc .= "<tr><td colspan='4' align='center'>".$short_name_sa." (".$aggre_no_sa.") ".$count_sa." - MBooks are waiting for Accounts Approval </td></tr>";
$alet_msg_acc .= "<tr><td colspan='4' align='center'>".$short_name_sa." : <font style='color:#D9045E;'>".$count_sa_temp." - MBooks are waiting for Accounts Approval <font></td></tr>";
						$ac2++;
					}
				}
				//echo $select_send_account_query;
			}
		}
	}
	$alet_msg_acc .= "</table>";
	//echo $count_sa;
}
else
{
?>
			<div class="leftsection">
				<div class="leftsectionheader">
					Dashboard
				</div>
				<!--<div class="leftdivmenuhead1">&nbsp;Major Works</div>-->
<?php 
	$MajorWorkListStr = "";
	$MinorWorkListStr = "";
	$AssignedWorkArr = array();
	if($wocheck == 1)
	{
		if(mysql_num_rows($WorkOrderSql)>0)
		{
			while($WOList = mysql_fetch_object($WorkOrderSql))
			{
				//if($sheetid == $WOList->sheet_id)
				//{ 
					//$backcolor = "style='background-color:#EFEFEF;'";
					//$workname = $WOList->short_name;
				//} 
				//else 
				//{ 
					//$backcolor = ""; 
				//} 
				//echo "<div class='leftdivmenu' id='".$WOList->sheet_id."' onClick='changeData(this)' ".$backcolor.">".$WOList->short_name."</div>";
				$assigned_staff = $WOList->assigned_staff;
				$AssignStaff = explode(",",$assigned_staff);
				if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1))
				{
					array_push($AssignedWorkArr,$WOList->sheet_id);
					if($WOList->worktype == 1)
					{
						$MajorWorkListStr .= $WOList->sheet_id."@@@".$WOList->short_name."@@@";
					}
					if($WOList->worktype == 2)
					{
						$MinorWorkListStr .= $WOList->sheet_id."@@@".$WOList->short_name."@@@";
					}
				}
			}
		}
	}
?>
<div class="leftdivmenuhead1">&nbsp;Major Works</div>
<div style="height:100px; overflow:scroll">

<?php
	$ExpMajorStr = explode("@@@",$MajorWorkListStr);
	for($x2=0; $x2<count($ExpMajorStr); $x2+=2)
	{
		$MajorWorkId = $ExpMajorStr[$x2+0];
		$MajorWorkName = $ExpMajorStr[$x2+1];
		if($MajorWorkId != "")
		{
			if($sheetid == $MajorWorkId)
			{ 
				$workname = $MajorWorkName;
				$Majorbackcolor = "style='background-color:#EFEFEF;'";
			} 
			else 
			{ 
				$Majorbackcolor = ""; 
			} 
			echo "<div class='leftdivmenu' id='".$MajorWorkId."' onClick='changeData(this)' ".$Majorbackcolor.">".$MajorWorkName."</div>";
		}
	}
?>
</div>
<div class="leftdivmenuhead1">&nbsp;Minor Works</div>
<div style="height:100px; overflow:scroll">
<?php	
	$ExpMinorStr = explode("@@@",$MinorWorkListStr);
	for($x3=0; $x3<count($ExpMinorStr); $x3+=2)
	{
		$MinorWorkId = $ExpMinorStr[$x3+0];
		$MinorWorkName = $ExpMinorStr[$x3+1];
		if($MinorWorkId != "")
		{
			if($sheetid == $MinorWorkId)
			{ 
				$workname = $MinorWorkName;
				$Minorbackcolor = "style='background-color:#EFEFEF;'";
			} 
			else 
			{ 
				$Minorbackcolor = ""; 
			} 
			echo "<div class='leftdivmenu' id='".$MinorWorkId."' onClick='changeData(this)' ".$Minorbackcolor.">".$MinorWorkName."</div>";
		}
	}
?>
</div>
				<!--<div class="leftdivmenu" onClick="getdata()">Work No6</div>
				<div class="leftdivmenuhead1">&nbsp;Minor Works</div>
				<div class='leftdivmenu' id='' onClick='changeData(this)'>gbdfghdthdth</div>-->
				<div class="leftdivmenuhead1">Analysis of RAB's Total Amount</div>
				<div class="stackbarchart" id="chartdiv">
					
				</div>
			</div>
			<div class="contenttsection">
				<div class="contenttopheader">
					Analysis of <?php echo $workname; ?>
				</div>
				<div class="topcontentarea" id="barchart">
					<!--<img src="images/chart1.png" width="588" height="318">-->
				</div>
				<div class="contentbottompheader">
					Analysis of Item No's ( Qty. - % Completed - Total of RABs )
				</div>
				<div class="bottomcontentarea" id="piechart">
					<!--<img src="images/chart2.jpg" width="588" height="318">-->
				</div>
			</div>
			<div class="rightsection">
				<div class="rightsectionheader">
					Analysis of Item No's<font style="font-size:9px;">&nbsp;(% Completed)</font>
				</div>
				<div style="height:20px; text-align:right; color:#0E02EA; font-size:10px;">( As of now )&nbsp;&nbsp;&nbsp;
				</div>
				<div style="overflow:scroll; height:649px; margin-left:3px">
<?php
	if($itemcheck == 1)
	{
		if(mysql_num_rows($ItemNoSql)>0)
		{
			$ic = 1;
			while($ItemList = mysql_fetch_object($ItemNoSql))
			{
				$itemQty = 0;
				if($ic == 1){ $status = "active"; }
				if($ic == 2){ $status = "success"; }
				if($ic == 3){ $status = "info"; }
				if($ic == 4){ $status = "warning"; }
				if($ic == 5){ $status = "danger"; $ic = 0;}
				$measure_type = $ItemList->measure_type;
				$DeviatePerc = $ItemList->deviate_qty_percent;
				$DeviateQty = $ItemList->total_quantity * $DeviatePerc/100;
				if($measure_type == 's')
				{
					//$itemQty = getSteelItemQtyPercent($ItemList->subdiv_id);
					//echo $itemQty."<br/>";
					$itemQty = getGeneralItemQtyPercent($ItemList->subdiv_id);
				}
				else
				{
					$itemQty = getGeneralItemQtyPercent($ItemList->subdiv_id);
				}
				//echo $itemQty;  
				if($measure_type == 'st')
				{
					$itemQty = $itemQty/1000;
				}
				$PercQty  = $itemQty/$ItemList->total_quantity*100;
				
				//$PercQty = 50;
				
				if($PercQty<30){ $color = "color:black;";}else { $color = ""; }
?>
					<label style="font-size:10px; color:#CE1A00">&nbsp;<?php echo $ItemList->sno; ?> </label>
					<label style="font-size:10px;"><?php echo " - Total W.O. Qty. ".$ItemList->total_quantity."&nbsp;".$ItemList->per; ?>.</label>
					<div class="progress">
						<div class="<?php echo "progress-bar progress-bar-".$status." progress-bar-striped active"; ?>" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style=" <?php echo $color; ?>width:<?php if($PercQty>100){ echo "100.00"; } else{ echo number_format($PercQty,2); } ?>%">
						 <?php echo number_format($PercQty,2)."%"; ?>
						</div>
					</div>
<?php			
				$ic++;	
			}
		}
	}
?>
				</div>
			</div>
		
		<?php
		$prev_sheetid_acc = ""; $RCount = 0;  $acc_remark_cnt = 0;// $prev_fromdate_acc = ""; $prev_todate_acc = "";
		$Acc_Sheet = array(); $Acc_FromDate = array(); $Acc_ToDate = array(); $Acc_Rbn = array(); $Acc_RConut = array();
		$alet_msg = ""; 
		$alet_msg .= "<table width='650px' style='border: 1px solid #A9A9A9' class='label'>";
		$select_sheet_acc_query = "select sheetid, DATE_FORMAT(fromdate,'%Y-%m-%d') as fromdate, DATE_FORMAT(todate,'%Y-%m-%d') as todate, rbn, accounts_remarks from measurementbook_temp";
		$select_sheet_acc_sql = mysql_query($select_sheet_acc_query);
		if($select_sheet_acc_sql == true)
		{
			if(mysql_num_rows($select_sheet_acc_sql)>0)
			{
			
//=================>//=================>//=================>//=================>//=================>//=================>//=================>			
				while($AccSheetList = mysql_fetch_object($select_sheet_acc_sql))
				{
					
					$sheetid_acc 	= $AccSheetList->sheetid;
					if(in_array($sheetid_acc,$AssignedWorkArr))
					{// || ($_SESSION['isadmin'] == 1))
						$fromdate_acc 	= $AccSheetList->fromdate;
						$todate_acc 	= $AccSheetList->todate;
						$rbn_acc 		= $AccSheetList->rbn;
						$remarks_acc 	= $AccSheetList->accounts_remarks;
						//if($fromdate_acc<$prev_fromdate_acc)
						//{
							//$min_from_date_acc = $fromdate_acc;
						//}
						//else
						//{
							//$min_from_date_acc = $prev_fromdate_acc;
						//}
						//echo "NOW = ".$fromdate_acc." ::: MIN = ".$min_from_date_acc."<br/>";
						if($sheetid_acc != $prev_sheetid_acc)
						{
							array_push($Acc_Sheet,$sheetid_acc);
							$Acc_FromDate[$sheetid_acc] = $fromdate_acc;
							$Acc_ToDate[$sheetid_acc] 	= $todate_acc;
							$Acc_Rbn[$sheetid_acc] 		= $rbn_acc;
						}
						if(($sheetid_acc != $prev_sheetid_acc) && ($prev_sheetid_acc != ""))
						{
							$Acc_RConut[$prev_sheetid_acc]	= $RCount;
							$RCount = 0;
						}
						if($remarks_acc != "")
						{
							$RCount++; 
							$acc_remark_cnt++; 		//=================> This is very very important for open dialog window
						}
						$prev_sheetid_acc 	= $sheetid_acc;
						//$prev_fromdate_acc 	= $fromdate_acc;
						//$prev_todate_acc 	= $todate_acc;
					}
				}
				
//=================>//=================>//=================>//=================>//=================>//=================>//=================>				
				$Acc_RConut[$prev_sheetid_acc]	= $RCount;
				$RCount = 0;
				//print_r($Acc_FromDate);
				//print_r($Acc_ToDate);
				$sht_c = 1;
				for($ac1 = 0; $ac1<count($Acc_Sheet); $ac1++)
				{
					$sheetid_status 	= 	$Acc_Sheet[$ac1];
					$rbn_status 		= 	$Acc_Rbn[$sheetid_status];
					$sheet_data 		= 	getsheetdata($sheetid_status);
					$exp_sheet_data 	= 	explode("@#*#@",$sheet_data);
					$short_name_status 	= 	$exp_sheet_data[0];
					$memo_pay_edit = 0;
					$select_edit_memopayment_query = "select * from memo_payment_accounts_edit where edit_flag = 'EDITED' and sheetid = '$sheetid_status' and rbn = '$rbn_status'";
					$select_edit_memopayment_sql = mysql_query($select_edit_memopayment_query);
					if($select_edit_memopayment_sql == true)
					{
						if(mysql_num_rows($select_edit_memopayment_sql)>0)
						{
							$memo_pay_edit = 1;
						}
					}
					$select_date_query = "Select min(DATE_FORMAT(fromdate,'%Y-%m-%d')) as fromdate, max(DATE_FORMAT(todate,'%Y-%m-%d')) as todate from measurementbook_temp where sheetid = '$sheetid_status'";
					$select_date_sql = mysql_query($select_date_query);
					if($select_date_sql == true)
					{
						if(mysql_num_rows($select_date_sql)>0)
						{
							$DateList = mysql_fetch_object($select_date_sql);
							$min_fromdate = $DateList->fromdate;
							$max_todate = $DateList->todate;
							/*$select_mbremark_query = "select COUNT(mbookdetail.accounts_remarks) as mbcount from mbookdetail 
							INNER JOIN mbookheader ON (mbookheader.mbheaderid = mbookdetail.mbheaderid) where mbookheader.sheetid = '$sheetid_status' 
							and mbookheader.date  >= '$min_fromdate' AND mbookheader.date  <= '$max_todate' and mbookdetail.accounts_remarks != ''";
							$select_mbremark_sql = mysql_query($select_mbremark_query);
							if($select_mbremark_sql == true)
							{
								$MbRemark = mysql_fetch_object($select_mbremark_sql);
								$MbRemark_Count = $MbRemark->mbcount;			//=================> This is very very important for open dialog window
								$acc_remark_cnt = $acc_remark_cnt+$MbRemark_Count;
							}*/
							$select_mbremark_query = "select mbookdetail.accounts_remarks from mbookdetail 
							INNER JOIN mbookheader ON (mbookheader.mbheaderid = mbookdetail.mbheaderid) where mbookheader.sheetid = '$sheetid_status' 
							and mbookheader.date  >= '$min_fromdate' AND mbookheader.date  <= '$max_todate' and mbookdetail.accounts_remarks != ''";
							$select_mbremark_sql = mysql_query($select_mbremark_query);
							$MBRArr = array();
							if($select_mbremark_sql == true)
							{
								if(mysql_num_rows($select_mbremark_sql)>0)
								{
									while($MbRemark = mysql_fetch_object($select_mbremark_sql))
									{
										$MbRemark_St 		= $MbRemark->accounts_remarks;			
										$Exp_acc_remarks 	= explode("@R@",$MbRemark_St);
										$mbookremarks 		= $Exp_acc_remarks[0];
										$mbookremarks_mbook = $Exp_acc_remarks[1];
										array_push($MBRArr,$mbookremarks_mbook);
										$acc_remark_cnt++; $mb_c++;						//=================> This is very very important for open dialog window
									}
								}
							}
						}
					}
					// print_r($MBRArr);//echo "hai";
					$select_subabstract_query = "select COUNT(accounts_remarks) as sacount from mbookgenerate_staff where sheetid = '$sheetid_status' and rbn = '$rbn_status' and accounts_remarks != ''";
					$select_subabstract_sql = mysql_query($select_subabstract_query);
					if($select_subabstract_sql == true)
					{
						$SaRemark = mysql_fetch_object($select_subabstract_sql);
						$SaRemark_Count = $SaRemark->sacount;					//=================> This is very very important for open dialog window
						$acc_remark_cnt = $acc_remark_cnt+$SaRemark_Count;
					}
					

//$alet_msg .= "<tr><td colspan='2' align='center'> MBook: </td><td colspan='2' align='center' style='background-color:#F5F5F5; color:red;'><a href='AccountsComments_View.php?workno=".$sheetid_status."'>".$MbRemark_Count." Comments "."</a></td></tr>";
//$alet_msg .= "<tr><td colspan='2' align='center'> Sub-Abstract: </td><td colspan='2' align='center' style='background-color:#F5F5F5; color:red;'><a href='AccountsComments_View.php?workno=".$sheetid_status."'>".$SaRemark_Count." Comments "."</a></td></tr>";
//$alet_msg .= "<tr><td colspan='2' align='center'> Abstract: </td><td colspan='2' align='center' style='background-color:#F5F5F5; color:red;'><a href='AccountsComments_View.php?workno=".$sheetid_status."'>".$Acc_RConut[$sheetid_status]." Comments "."</a></td></tr>";

//$alet_msg .= "<tr style='background-color:#F5F5F5;'><td align='center'>MBook No.</td><td align='center'>Desciption</td><td>Status</td></tr>";
					$select_status_mbook_query 	= "select mbookno, zone_id, mtype, genlevel, mb_ac, sa_ac, ab_ac from send_accounts_and_civil 
													where sheetid = '$sheetid_status' and rbn = '$rbn_status' ORDER BY mbookno";
					$select_status_mbook_sql 	= mysql_query($select_status_mbook_query);
					if($select_status_mbook_sql == true)
					{
						if(mysql_num_rows($select_status_mbook_sql)>0)
						{
$alet_msg .= "<tr><td colspan='3' align='left' style='background-color:#ECECEC; font-size:13px;'>&nbsp;&nbsp;".$sht_c.") ".$short_name_status."</td></tr>";
							if($memo_pay_edit == 1)
							{
$alet_msg .= "<tr><td colspan='3' align='center' style='color:red; font-size:13px;'> Memo of Payment has been edited by Accounts. Please check your Pass Order. </td></tr>";
							}
							$sht_c++;
							while($StatusList = mysql_fetch_object($select_status_mbook_sql))
							{
								$mb_ac = $StatusList->mb_ac;
								$sa_ac = $StatusList->sa_ac;
								$ab_ac = $StatusList->ab_ac;
								$mbookno_status 	= $StatusList->mbookno;
								$mtype_status 		= $StatusList->mtype;
								$genlevel_status 	= $StatusList->genlevel;
								$zone_id_status 	= $StatusList->zone_id;
								
								//$MBremark_count = $MBRArr
								
								if($mtype_status == "A")
								{
									$mtype_status_print = " Abstract MBook";
								}
								else if($mtype_status == "S")
								{
									$mtype_status_print = " - Steel MBook ";
								}
								else if($mtype_status == "G")
								{
									$mbook_uniq_count = array_count_values($MBRArr);  //   This is used to get count remarks for each mbook
									//print_r($mbook_uniq_count); echo "<br/>";
									if($genlevel_status == "staff")
									{
										$mtype_status_print = " - General MBook ";
									}
									if($genlevel_status == "composite")
									{
										$mtype_status_print = " Sub-Abstract MBook";
									}
								}
								else
								{
									$mtype_status_print = "";
								}
								
								$Mb_Remark_Count = $mbook_uniq_count[$mbookno_status];
								if(($Mb_Remark_Count != 0) && ($Mb_Remark_Count != ""))
								{
									$Mbook_comment_print = "<a href='AccountsComments_View.php?workno=".$sheetid_status."'><u>".$Mb_Remark_Count." Comment/s </u></a>";
								}
								else
								{
									$Mbook_comment_print = "";
								}
								
								if(($SaRemark_Count != 0) && ($SaRemark_Count != ""))
								{
									$SubAbs_comment_print = "<a href='AccountsComments_View.php?workno=".$sheetid_status."'><u>".$SaRemark_Count." Comment/s </u></a>";
								}
								else
								{
									$SubAbs_comment_print = "";
								}
								
								
								$Abs_Remarks_Count = $Acc_RConut[$sheetid_status];
								if(($Abs_Remarks_Count != 0) && ($Abs_Remarks_Count != ""))
								{
									$Abstract_comment_print = "<a href='AccountsComments_View.php?workno=".$sheetid_status."'><u>".$Abs_Remarks_Count." Comment/s </u></a>";
								}
								else
								{
									$Abstract_comment_print = "";
								}
								
								
									if($mb_ac == 'AC')
									{ 
										if($Mbook_comment_print != "")
										{
											$mb_status = "Accepted with ";
										}
										else
										{
											$mb_status = "Accepted";
										}
										
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:green;'>".$mb_status.$Mbook_comment_print."</td></tr>";
										$acc_remark_cnt++; 
									}
									else if($mb_ac == 'SC')
									{  
										if($Mbook_comment_print != "")
										{
											$mb_status = "Accounts Returned ";
										}
										else
										{
											$mb_status = "";
										}
										//$mb_status = "";
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:red;'>".$mb_status.$Mbook_comment_print."</td></tr>";									
										$acc_remark_cnt++; 
									}
									else
									{  
										$mb_status = "";
									}
									
									if($sa_ac == 'AC')
									{ 
									
										if($SubAbs_comment_print != "")
										{
											$sa_status = "Accepted with ";
										}
										else
										{
											$sa_status = "Accepted";
										}
										
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:green;'>".$sa_status.$SubAbs_comment_print."</td></tr>";									
										$acc_remark_cnt++; 
									}
									else if($sa_ac == 'SC')
									{  
										$sa_status = "Accounts Returned";
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:red;'>".$sa_status.$SubAbs_comment_print."</td></tr>";									
										$acc_remark_cnt++; 
									}
									else
									{  
										$sa_status = "";
									}
									
									if($ab_ac == 'AC')
									{ 
										if($Abstract_comment_print != "")
										{
											$ab_status = "Accepted with ";
										}
										else
										{
											$ab_status = "Accepted";
										}
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:green;'>".$ab_status.$Abstract_comment_print."</td></tr>";									
										$acc_remark_cnt++; 
									}
									else if($ab_ac == 'SC')
									{  
										$ab_status = "Accounts Returned";
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:red;'>".$ab_status.$Abstract_comment_print."</td></tr>";									
										$acc_remark_cnt++; 
									}
									else
									{  
										$ab_status = "";
									}
							}
						}
					}
					
				}
			}
		}
		$alet_msg .= "</table>";
 } ?>			
         </blockquote>
       </div>
    </div>
  </div>
 <!--==============================footer=================================-->
 <?php   include "footer/footer.html"; ?>
 
 <script>
	var htmlval_for_civil = "<?php echo $alet_msg; ?>";
	var remark_count_for_civil = "<?php echo $acc_remark_cnt; ?>";
	if(remark_count_for_civil>0)
	{
		swal({
			title: "<b>Accounts Comment Notification</b>",
			text: "<small><div style='height:400px; overflow:scroll'>"+htmlval_for_civil+"</div></small>",
			html: true
		});
	}
	
	var htmlval_for_accounts = "<?php echo $alet_msg_acc; ?>";
	var send_to_acco = "<?php echo $count_sa; ?>";
	//alert(send_to_acco)
	if(send_to_acco>0)
	{
		swal({
			title: "<b>MBooks Waiting for Approval - Notification</b>",
			text: "<small>"+htmlval_for_accounts+"</small>",
			html: true,
			confirmButtonColor: '#0080FF'
		});
	}
	
 </script>
 <style>
 	.sweet-alert
	{
		width:650px;
		left:52%;
		margin-left:-380px;
		top:40%;
		padding:5px;
	}
	.sweet-alert h2
	{
		font-weight:bold;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:14px;
		color:#FFFFFF;
		line-height:30px;
		/* Safari 4-5, Chrome 1-9 */
		background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#037595), to(#0A9CC5));
		/* Safari 5.1, Chrome 10+ */
		background: -webkit-linear-gradient(top, #0A9CC5, #037595);
		/* Firefox 3.6+ */
		background: -moz-linear-gradient(top, #0A9CC5, #037595);
		/* IE 10 */
		background: -ms-linear-gradient(top, #0A9CC5, #037595);
		/* Opera 11.10+ */
		background: -o-linear-gradient(top, #0A9CC5, #037595);
	}
	div.sweet-alert tr, div.sweet-alert td
	{
		background-color:#F8F8FF;
		height:25px;
		border:1px solid #F9F9F9;
		vertical-align:middle;
		font-size:11px;
	}

 </style>
 </form>
</body>
</html>
