<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
checkUser();
$userid 	= 	$_SESSION['userid'];
$sheet_id 	= 	$_POST['sheetid'];
$staffid 	= 	$_SESSION['sid'];
$type 		= 	$_POST['type'];
$patterns = array();
$patterns[0] = '/"/';
$patterns[1] = "/'/";
$patterns[2] = '/°/';

$replacements = array();
$replacements[0] = '"';
$replacements[1] = "\'";
$replacements[2] = '°';
if($type == "g")
{
	$mbheaderidStr = "";
	$mbookheader_sql = "select mbheaderid, date, sheetid, divid, subdivid,  subdiv_name, zone_id, staffid from mbookheader_temp where measure_type != 's' and sheetid = '$sheet_id' and staffid = '$staffid' ORDER BY mbheaderid ASC";
	$mbookheader_query = mysql_query($mbookheader_sql);
	if($mbookheader_query == true)
	{
		if(mysql_num_rows($mbookheader_query)>0)
		{
			while($MBHeadList = mysql_fetch_object($mbookheader_query))
			{
				$mbheaderid 	= $MBHeadList->mbheaderid;
				$mdate 			= $MBHeadList->date;
				$divid 			= $MBHeadList->divid;
				$subdivid 		= $MBHeadList->subdivid;
				$subdiv_name 	= $MBHeadList->subdiv_name;
				$staffid 		= $MBHeadList->staffid;
				$zone_id 		= $MBHeadList->zone_id;
				$mbheadflag = 0;
				$mbheaderidStr .= $mbheaderid."*"; 
				$mbookdetail_sql = "select mbdetail_id, mbheaderid, subdivid, subdiv_name, descwork, measurement_no, measurement_no2, measurement_l, measurement_b, measurement_d, structdepth_unit, measurement_dia, measurement_contentarea, remarks, mbdetail_flag from mbookdetail_temp where mbheaderid = '$mbheaderid' ORDER BY mbdetail_id ASC";
				$mbookdetail_query = mysql_query($mbookdetail_sql);
				if($mbookdetail_query == true)
				{
					if(mysql_num_rows($mbookdetail_query)>0)
					{
						while($MBDetailList = mysql_fetch_object($mbookdetail_query))
						{
							$ErrorFlag = $MBDetailList->mbdetail_flag;
							$str .= $ErrorFlag."#########"."<br/>";
							$expError = explode("@@@",$ErrorFlag);
							$ErrItemNo = $expError[0];
							$DateFlag = $expError[1];
							$ItemFlag = $expError[2];
							$QtyFlag = $expError[3];
							$MaxDtFlag = $expError[4];
							
							$descwork = $MBDetailList->descwork;
							$descwork = preg_replace($patterns, $replacements, $descwork);
							$descwork = str_replace("'", "'", $descwork);
							$measurement_no = $MBDetailList->measurement_no;
							$measurement_no2 = $MBDetailList->measurement_no2;
							$measurement_l = $MBDetailList->measurement_l;
							$measurement_b = $MBDetailList->measurement_b;
							$measurement_d = $MBDetailList->measurement_d;
							$structdepth_unit = $MBDetailList->structdepth_unit;
							$measurement_dia = $MBDetailList->measurement_dia;
							$measurement_contentarea = $MBDetailList->measurement_contentarea;
							$remarks = $MBDetailList->remarks;
							$mbdetail_id = $MBDetailList->mbdetail_id;
							
							if(($DateFlag == "") && ($ItemFlag == "") && ($QtyFlag == "") && ($MaxDtFlag == ""))
							{
								if($mbheadflag == 0)
								{
									$mbheadinsert_sql 	= 	"insert into mbookheader set date = '$mdate', sheetid ='$sheet_id', divid = '$divid', subdivid = '$subdivid', subdiv_name = '$subdiv_name', zone_id = '$zone_id', active = '1', staffid = '$staffid', userid = '$userid'";
									$mbheadinsert_query = 	mysql_query($mbheadinsert_sql);
									$new_mbheader_id 	= 	mysql_insert_id();
									$mbheadflag++;
									$HeadStr .= $mbheadinsert_sql."@@@@@</br>";
								}
								//$new_mbheader_id = 
								//if($mbheadinsert_query == true)
								//{
									$mbdetailinsert_sql 	= 	"insert into mbookdetail set mbheaderid = '$new_mbheader_id', subdivid ='$subdivid', subdiv_name = '$subdiv_name', descwork = '$descwork', measurement_no = '$measurement_no', measurement_no2 = '$measurement_no2', measurement_l = '$measurement_l',  measurement_b = '$measurement_b', measurement_d = '$measurement_d', structdepth_unit = '$structdepth_unit', measurement_dia = '$measurement_dia', measurement_contentarea = '$measurement_contentarea', remarks = '$remarks', zone_id = '$zone_id', entry_date = NOW()";
									$mbdetailinsert_query 	= 	mysql_query($mbdetailinsert_sql);
									$DetStr .= $mbdetailinsert_sql."@@@@@</br>";
									
									$mbdetail_delete_sql = "delete from mbookdetail_temp where mbdetail_id = '$mbdetail_id'";
									$mbdetail_delete_query = mysql_query($mbdetail_delete_sql);
	
								//}
								
							}
							
							
						}
					}
				}
				
			}
			$expmbid = explode("*",$mbheaderidStr);
			for($i=0; $i<count($expmbid); $i++)
			{
				$delid = $expmbid[$i];
				$select_mbookhead_sql = "select * from mbookdetail_temp where mbheaderid = '$delid'";
				$select_mbookhead_query = mysql_query($select_mbookhead_sql);
				if($select_mbookhead_query == true)
				{
					if(mysql_num_rows($select_mbookhead_query) == 0)
					{
						$delete_mbhead_sql = "delete from mbookheader_temp where mbheaderid = '$delid'";
						$delete_mbhead_query = mysql_query($delete_mbhead_sql);
						$del .= $delete_mbhead_sql."@@";
					}
				}
			}
		}
	}
}
if($type == "s")
{
	$mbheaderidStr = "";
	$mbookheader_sql = "select mbheaderid, date, sheetid, divid, subdivid, zone_id,  subdiv_name, staffid from mbookheader_temp where measure_type = 's' and sheetid = '$sheet_id' and staffid = '$staffid' ORDER BY mbheaderid ASC";
	$mbookheader_query = mysql_query($mbookheader_sql);
	if($mbookheader_query == true)
	{
		if(mysql_num_rows($mbookheader_query)>0)
		{
			while($MBHeadList = mysql_fetch_object($mbookheader_query))
			{
				$mbheaderid 	= $MBHeadList->mbheaderid;
				$mdate 			= $MBHeadList->date;
				$divid 			= $MBHeadList->divid;
				$subdivid 		= $MBHeadList->subdivid;
				$subdiv_name 	= $MBHeadList->subdiv_name;
				$staffid 		= $MBHeadList->staffid;
				$zone_id 		= $MBHeadList->zone_id;
				$mbheadflag = 0;
				$mbheaderidStr .= $mbheaderid."*"; 
				$mbookdetail_sql = "select mbdetail_id, mbheaderid, subdivid, subdiv_name, descwork, measurement_no, measurement_no2, measurement_l, measurement_b, measurement_d, structdepth_unit, measurement_dia, measurement_contentarea, remarks, mbdetail_flag from mbookdetail_temp where mbheaderid = '$mbheaderid' ORDER BY mbdetail_id ASC";
				$mbookdetail_query = mysql_query($mbookdetail_sql);
				if($mbookdetail_query == true)
				{
					if(mysql_num_rows($mbookdetail_query)>0)
					{
						while($MBDetailList = mysql_fetch_object($mbookdetail_query))
						{
							$ErrorFlag = $MBDetailList->mbdetail_flag;
							$str .= $ErrorFlag."#########"."<br/>";
							$expError = explode("@@@",$ErrorFlag);
							$ErrItemNo = $expError[0];
							$DateFlag = $expError[1];
							$ItemFlag = $expError[2];
							$QtyFlag = $expError[3];
							$MaxDtFlag = $expError[4];
							
							$descwork = $MBDetailList->descwork;
							$descwork = preg_replace($patterns, $replacements, $descwork);
							$descwork = str_replace("'", "'", $descwork);
							$measurement_no = $MBDetailList->measurement_no;
							$measurement_no2 = $MBDetailList->measurement_no2;
							$measurement_l = $MBDetailList->measurement_l;
							$measurement_b = $MBDetailList->measurement_b;
							$measurement_d = $MBDetailList->measurement_d;
							$structdepth_unit = $MBDetailList->structdepth_unit;
							$measurement_dia = $MBDetailList->measurement_dia;
							$measurement_contentarea = $MBDetailList->measurement_contentarea;
							$remarks = $MBDetailList->remarks;
							$mbdetail_id = $MBDetailList->mbdetail_id;
							
							if(($DateFlag == "") && ($ItemFlag == "") && ($QtyFlag == "") && ($MaxDtFlag == ""))
							{
								if($mbheadflag == 0)
								{
									$mbheadinsert_sql 	= 	"insert into mbookheader set date = '$mdate', sheetid ='$sheet_id', divid = '$divid', subdivid = '$subdivid', subdiv_name = '$subdiv_name', zone_id = '$zone_id', active = '1', staffid = '$staffid', userid = '$userid'";
									$mbheadinsert_query = 	mysql_query($mbheadinsert_sql);
									$new_mbheader_id 	= 	mysql_insert_id();
									$mbheadflag++;
									$HeadStr .= $mbheadinsert_sql."@@@@@</br>";
								}
								//$new_mbheader_id = 
								//if($mbheadinsert_query == true)
								//{
									$mbdetailinsert_sql 	= 	"insert into mbookdetail set mbheaderid = '$new_mbheader_id', subdivid ='$subdivid', subdiv_name = '$subdiv_name', descwork = '$descwork', measurement_no = '$measurement_no', measurement_no2 = '$measurement_no2', measurement_l = '$measurement_l', measurement_dia = '$measurement_dia', measurement_contentarea = '$measurement_contentarea', remarks = '$remarks', zone_id = '$zone_id', entry_date = NOW()";
									$mbdetailinsert_query 	= 	mysql_query($mbdetailinsert_sql);
									$DetStr .= $mbdetailinsert_sql."@@@@@</br>";
									
									$mbdetail_delete_sql = "delete from mbookdetail_temp where mbdetail_id = '$mbdetail_id'";
									$mbdetail_delete_query = mysql_query($mbdetail_delete_sql);
	
								//}
								
							}
							
							
						}
					}
				}
				
			}
			$expmbid = explode("*",$mbheaderidStr);
			for($i=0; $i<count($expmbid); $i++)
			{
				$delid = $expmbid[$i];
				$select_mbookhead_sql = "select * from mbookdetail_temp where mbheaderid = '$delid'";
				$select_mbookhead_query = mysql_query($select_mbookhead_sql);
				if($select_mbookhead_query == true)
				{
					if(mysql_num_rows($select_mbookhead_query) == 0)
					{
						$delete_mbhead_sql = "delete from mbookheader_temp where mbheaderid = '$delid'";
						$delete_mbhead_query = mysql_query($delete_mbhead_sql);
						$del .= $delete_mbhead_sql."@@";
					}
				}
			}
		}
	}
}
if($mbdetailinsert_sql == true) { $msg = "Sucessfully Updated."; }
else { $msg = "Sorry, Unable to update."; }
echo $msg;//.$DetStr;
//echo $str;
?>
