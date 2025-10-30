<?php
require_once 'library/config.php';
$sheetid 	= 	$_GET['work_no'];
$item_text 	= 	$_GET['item_text'];
$item_val 	= 	$_GET['item_val'];
$item_id 	= 	$_GET['itemid'];
$temp 		= 	$_GET['temp'];
//$len1 = $_GET['len1'];
//$len2 = $_GET['len2'];
//$len3 = $_GET['len3'];
/*if(($len1 == 1) && ($len2 == 1) && ($len3 == 1))
{
	$get_subdiv_id_sql = "SELECT subdiv_id FROM subdivision WHERE div_id = '$itemid'";
	$get_subdiv_id = mysql_query($get_subdiv_id_sql);
	$itemid = @mysql_result($get_subdiv_id,0,'subdiv_id');
}*/
if($temp>0)
{
	if($temp == 1)
	{
		$get_subdiv_id_sql = "SELECT subdiv_id FROM subdivision WHERE div_id = '$item_id' AND subdiv_name = '$item_text'";
		$get_subdiv_id = mysql_query($get_subdiv_id_sql);
		$itemid = @mysql_result($get_subdiv_id,0,'subdiv_id');
	}
	if($temp == 2)
	{
		$itemid = $item_val;
	}
	/*if(($item_val != "") && ($item_val != 0))
	{
		$get_subdiv_id_sql = "SELECT subdiv_id FROM subdivision WHERE div_id = '$item_val' AND subdiv_name = '$item_text'";
		$get_subdiv_id = mysql_query($get_subdiv_id_sql);
		$itemid = @mysql_result($get_subdiv_id,0,'subdiv_id');
		if(($itemid != "") && ($itemid != 0))*/
		{
			$sql_workorder_qty="SELECT schdule.total_quantity, deviate_qty_percent FROM schdule where schdule.sheet_id = '$sheetid' AND  schdule.subdiv_id = '$itemid' AND schdule.sno = '$item_text'";
			$rs_workorder_qty=mysql_query($sql_workorder_qty);
			if($rs_workorder_qty == true)
			{
				$workorder_qty			=	@mysql_result($rs_workorder_qty,0,'total_quantity');
				$deviate_qty_percent	=	@mysql_result($rs_workorder_qty,0,'deviate_qty_percent');
				$sql_used_qty="SELECT measurement_contentarea FROM mbookdetail where subdivid = '$itemid' AND  mbdetail_flag  != 'd'";
				$rs_used_qty=mysql_query($sql_used_qty);
				if($rs_used_qty == true)
				{
					$used_qty = 0;
					if(mysql_num_rows($rs_used_qty)>0)
					{
						while($result = mysql_fetch_array($rs_used_qty))
						{
							$used_qty = $used_qty + $result['measurement_contentarea'];
						}
					}
				}
				echo $workorder_qty."*".$used_qty."*".$deviate_qty_percent;
			}
		}
		/*else
		{
			
		}*/
	//}
}
else
{
	$workorder_qty = ""; $used_qty = ""; $deviate_qty_percent = "";
	echo $workorder_qty."*".$used_qty."*".$deviate_qty_percent;
}
//echo $get_subdiv_id_sql;


/*if($temp>0)
{
	if($temp == 1)
	{
		$get_subdiv_id_sql = "SELECT subdiv_id FROM subdivision WHERE div_id = '$item_val' AND subdiv_name = '$item_text'";
		$get_subdiv_id = mysql_query($get_subdiv_id_sql);
		$itemid = @mysql_result($get_subdiv_id,0,'subdiv_id');
	}
	if($temp == 2)
	{
		$itemid = $item_val;
	}
		{
			$sql_workorder_qty="SELECT schdule.total_quantity, deviate_qty_percent FROM schdule where schdule.sheet_id = '$sheetid' AND  schdule.subdiv_id = '$itemid' AND schdule.sno = '$item_text'";
			$rs_workorder_qty=mysql_query($sql_workorder_qty);
			if($rs_workorder_qty == true)
			{
				$workorder_qty			=	@mysql_result($rs_workorder_qty,0,'total_quantity');
				$deviate_qty_percent	=	@mysql_result($rs_workorder_qty,0,'deviate_qty_percent');
				$sql_used_qty="SELECT measurement_contentarea FROM mbookdetail where subdivid = '$itemid' AND  mbdetail_flag  != 'd'";
				$rs_used_qty=mysql_query($sql_used_qty);
				if($rs_used_qty == true)
				{
					$used_qty = 0;
					if(mysql_num_rows($rs_used_qty)>0)
					{
						while($result = mysql_fetch_array($rs_used_qty))
						{
							$used_qty = $used_qty + $result['measurement_contentarea'];
						}
					}
				}
				echo $workorder_qty."*".$used_qty."*".$deviate_qty_percent;
			}
		}
}
else
{
	$workorder_qty = ""; $used_qty = ""; $deviate_qty_percent = "";
	echo $workorder_qty."*".$used_qty."*".$deviate_qty_percent;
}
*/
?>
