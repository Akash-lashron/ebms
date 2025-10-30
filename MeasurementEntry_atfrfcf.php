<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
require_once 'library/common.php';
checkUser();
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
$username = getusername($staffid);
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);

    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}

function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '-' . $mm . '-' . $yy;
}
if(($_GET['mbdetailid'] != "") && ($_GET['mbheaderid'] != ""))
{ 
	$sheetid 		= 	$_GET['sheetid'];
	$mbheaderid 	= 	$_GET['mbheaderid'];
	$mbdetailid 	= 	$_GET['mbdetailid'];
	//$subdivid		=	$_GET['subdivid'];
	//$divid		=	$_GET['divid'];
	//$sheetid = $_SESSION['sheet-id'];
	$sql_selectmbdetail = "select mbookdetail.mbdetail_id, mbookdetail.mbheaderid, mbookdetail.descwork, mbookdetail.measurement_no, mbookdetail.measurement_l, mbookdetail.measurement_b, mbookdetail.measurement_d,  mbookdetail.measurement_dia, measurement_contentarea, mbookdetail.remarks, mbookdetail.structdepth_unit, mbookheader.date, subdivision.subdiv_name, sheet.work_order_no, schdule.description, schdule.shortnotes, schdule.measure_type, mbookheader.divid ,mbookheader.subdivid FROM mbookheader
							INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
							INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
							INNER JOIN sheet ON (mbookheader.sheetid = sheet.sheet_id)
							INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) 
							WHERE  mbookheader.sheetid = ".$sheetid." AND mbookheader.userid = '$userid' AND mbookdetail.mbdetail_id = ".$_GET['mbdetailid']." AND mbookdetail.mbheaderid = ".$_GET['mbheaderid'];
//echo $sql_selectmbdetail;exit;
$rs_sql_selectmbdetail = mysql_query($sql_selectmbdetail);
while($List = mysql_fetch_object($rs_sql_selectmbdetail))
{
	$descwork 					= 	$List->descwork;
	$measurement_no 			= 	$List->measurement_no;
	$measurement_l 				= 	$List->measurement_l;
	$measurement_b 				= 	$List->measurement_b;
	$measurement_d 				= 	$List->measurement_d;
	$measurement_dia 			= 	$List->measurement_dia;
	$measurement_contentarea 	= 	$List->measurement_contentarea;
	$date 						= 	$List->date;
	$subdiv_name 				= 	$List->subdiv_name;
	$mbdetail_id 				= 	$List->mbdetail_id;
	$work_order_no 				= 	$List->work_order_no;
	$description 				= 	$List->description;
	$short_notes 				= 	$List->shortnotes;
	$div_id 					= 	$List->divid;
	$subdiv_id 					= 	$List->subdivid;
	$remarks 					= 	$List->remarks;
	$struct_depth_unit 			= 	$List->structdepth_unit;
	$measure_type 				= 	$List->measure_type;
	$measure_date 				=  	dt_display($List->date);
	
	if($measurement_no == 0) { $measurement_no = ""; }
	if($measurement_l == 0) { $measurement_l = ""; }
	if($measurement_b == 0) { $measurement_b = ""; }
	if($measurement_d == 0) { $measurement_d = ""; }
	if(($measurement_dia == 0) || ($measurement_dia == "")) { $measurement_dia = ""; }
	if($measurement_contentarea == 0) { $measurement_contentarea = ""; }
	//echo $measure_date;
	if(($measure_type != "s") && ($measure_type != "st")){ $measure_type = "g"; } 
	$subdiv = explode('.' , $subdiv_name);
	if(count($subdiv) == 1){ $divname = $subdiv[0]; $subdivname = "----None---"; $subsubdivname = "----None---"; $subsubsubdivname = "----None---"; $divid = $subdiv_id; $subdivid = 0; $subsubdivid = 0; $subsubsubdivid = 0;}
	if(count($subdiv) == 2){ $divname = $subdiv[0]; $subdivname = $subdiv[0].".".$subdiv[1]; $subsubdivname = "----None---"; $subsubsubdivname = "----None---"; $divid = $div_id; $subdivid = $subdiv_id; $subsubdivid = 0; $subsubsubdivid = 0;}
	if(count($subdiv) == 3){ $divname = $subdiv[0]; $subdivname = $subdiv[0].".".$subdiv[1]; $subsubdivname = $subdiv[0].".".$subdiv[1].".".$subdiv[2]; $subsubsubdivname = "----None---"; $divid = $div_id; $subdivid = 0; $subsubdivid = $subdiv_id; $subsubsubdivid = 0;}
	if(count($subdiv) == 4){ $divname = $subdiv[0]; $subdivname = $subdiv[0].".".$subdiv[1]; $subsubdivname = $subdiv[0].".".$subdiv[1].".".$subdiv[2]; $subsubsubdivname = $subdiv[0].".".$subdiv[1].".".$subdiv[2].".".$subdiv[3]; $divid = $div_id; $subdivid = 0; $subsubdivid = 0; $subsubsubdivid = $subdiv_id; }
	//echo $divname."<br/>";echo $subdivname."<br/>";echo $subsubdivname."<br/>";exit;
	if(($short_notes != "null") && ($short_notes != ""))
	{ $shortnotes = $short_notes; }
	else 
	{ $shortnotes = $description; }
}

$sql_workorder_qty	=	"SELECT schdule.total_quantity, deviate_qty_percent FROM schdule where schdule.sheet_id = '$sheetid' AND  schdule.subdiv_id = '$subdiv_id'";
$rs_workorder_qty	=	mysql_query($sql_workorder_qty);
$workorder_qty 		= 	@mysql_result($rs_workorder_qty,0,'total_quantity');
$deviateqty_percent = 	@mysql_result($rs_workorder_qty,0,'deviate_qty_percent');
//echo $deviateqty_percent;
$deviate_qty 		=	$workorder_qty * $deviateqty_percent/100;
$total_qty 			= 	$workorder_qty + $deviate_qty;
$total_qty_percent	=	100+$deviateqty_percent;
//echo $deviate_qty;
$sql_used_qty		=	"SELECT measurement_contentarea FROM mbookdetail where subdivid = '$subdiv_id' AND  mbdetail_flag != 'd'";
$rs_used_qty		=	mysql_query($sql_used_qty);
	if($rs_used_qty == true)
	{
		$used_qty = 0;
		if(mysql_num_rows($rs_used_qty)>0)
		{
			while($result = mysql_fetch_array($rs_used_qty))
			{
				$used_qty 	= $used_qty + $result['measurement_contentarea'];
			}
		}
	}
	if(($measure_type == "s") || ($measure_type == "st"))
	{
		$used_qty 			= 	($used_qty/1000);
		$current_item_qty 	= 	($measurement_contentarea/1000);
	}
	else
	{
		$current_item_qty = $measurement_contentarea;
	}
	$used_qty_percent		= 	$used_qty * 100/ $workorder_qty;
	$remaining_qty 			= 	($workorder_qty + $deviate_qty - $used_qty);
	$remaining_qty_percent	=	$remaining_qty * 100/$total_qty;
	
}
if($_POST["update"] == " Update ")
{
	$sheet_id			=	$_POST['hid_sheetid'];
	$div_id 			= 	$_POST['hid_divid'];
    $subdiv_id 			= 	$_POST['hid_subdivid'];
	$mbheaderid			=	$_POST['hid_mbheaderid'];
	$mbdetailid			=	$_POST['hid_mbdetailid'];
	$measurement_type 	= 	$_POST['txt_measure_type'];
	$measure_date		=	$_POST['datepicker'];
		$pieces 		= 	explode("-", $measure_date);
		$arr 			= 	array($pieces[2],$pieces[1],$pieces[0]);
		$mes_date		=	implode("-",$arr);
		$currentdate	=	date("Y-m-d");
		
	$deleteflag 		= 	$_POST['hid_deleteflag'];
    $newrow_g 			= 	$_POST['add_set_a1'];
	$newrow_s 			= 	$_POST['add_set_a2'];
    $newrow_st 			= 	$_POST['add_set_a3'];
	$f = 1;
	$mbookdetail_sql	=	"select * from mbookdetail WHERE mbheaderid = '$mbheaderid' AND subdivid = '$subdiv_id' AND mbdetail_flag != 'd'";
	$mbookdetail_query	=	mysql_query($mbookdetail_sql);
	if(mysql_num_rows($mbookdetail_query)<= 1)
	{
		$delete_mbheaderold_sql 	= 	"delete from mbookheader WHERE mbheaderid = '$mbheaderid' AND sheetid = '$sheet_id'";
		$delete_mbheaderold_query	=	mysql_query($delete_mbheaderold_sql);
		if($delete_mbheaderold_query == false){ $f = 0; }
		
		$delete_mbdetailold_sql 	= 	"delete from mbookdetail WHERE mbheaderid = '$mbheaderid' AND mbdetail_id = '$mbdetailid'";
		$delete_mbdetailold_query	=	mysql_query($delete_mbdetailold_sql);
		if($delete_mbdetailold_query == false){ $f = 0; }
		
		$insert_mbheader_sql		=	"insert into mbookheader (date, sheetid, allotmentid, divid, subdivid, active, staffid, userid) values ('$mes_date', '$sheet_id', 1, '$div_id', '$subdiv_id', 1, '$staffid', '$userid')";
		$insert_mbheader_query		=	mysql_query($insert_mbheader_sql);
		if($insert_mbheader_query == false){ $f = 0; }
		
		$mbookheaderid_new 			= 	mysql_insert_id();

	}
	else
	{
		$delete_mbdetailold_sql 	= 	"delete from mbookdetail WHERE mbheaderid = '$mbheaderid' AND mbdetail_id = '$mbdetailid'";
		$delete_mbdetailold_query	=	mysql_query($delete_mbdetailold_sql);
		if($delete_mbdetailold_query == false){ $f = 0; }
		
		$insert_mbheader_sql		=	"insert into mbookheader (date, sheetid, allotmentid, divid, subdivid, active, staffid, userid) values ('$mes_date', '$sheet_id', 1, '$div_id', '$subdiv_id', 1, '$staffid', '$userid')";
		$insert_mbheader_query		=	mysql_query($insert_mbheader_sql);
		if($insert_mbheader_query == false){ $f = 0; }
		
		$mbookheaderid_new 			= 	mysql_insert_id();
	}
	
	if($measurement_type == 's')
	{
		$rec 	= 	explode(".", $newrow_s);
		for ($c = 0; $c < count($rec); $c++) 
		{
			$x 	= 	$rec[$c];
			if($x != 2) 
			{
				$mbookdetailsquery	=	"INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_dia`, `measurement_contentarea`, `entry_date`, `remarks`) VALUES 
												('" . $mbookheaderid_new . "' ,
												 '" . $subdiv_id . "',
												 '" . $_POST['txt_dec_wk_mt' . $x] . "' ,
												 '" . $_POST['txt_no_mt' . $x] . "' ,
												 '" . $_POST['txt_l_mt' . $x] . "' ,
												 '" . $_POST['sel_dia_mt' . $x] . "' ,
												 '" . $_POST['txt_ca_mt' . $x] . "' ,
												 '" . $currentdate . "' ,										 
												 '" . $_POST['remarks'] . "')";
				$mbookdetailssql 	= 	mysql_query($mbookdetailsquery);
				if($mbookdetailssql == false){ $f = 0; }
			}
		}
	}
	elseif($measurement_type == 'st')
	{
		$rec = explode(".", $newrow_st);
		for ($c = 0; $c < count($rec); $c++) 
		{
			$x = $rec[$c];
			if($x!=2) 
			{
			
				$mbookdetailsquery	=	"INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_b`, `measurement_d`, `structdepth_unit`, `measurement_contentarea`, `entry_date`, `remarks`) VALUES 
											('" . $mbookheaderid_new . "' ,
											 '" . $subdiv_id . "',
											 '" . $_POST['txt_dec_wk_st' . $x] . "' ,
											 '" . $_POST['txt_no_st' . $x] . "' ,
											 '" . $_POST['txt_l_st' . $x] . "' ,
											 '" . $_POST['txt_b_st' . $x] . "' ,
											 '" . $_POST['txt_d_st' . $x] . "' ,
											 '" . $_POST['cmb_depthunit_st' . $x] . "' ,
											 '" . $_POST['txt_ca_st' . $x] . "' ,	
											 '" . $currentdate . "' ,									 
											 '" . $_POST['remarks'] . "')";
				$mbookdetailssql	= 	mysql_query($mbookdetailsquery);
				if($mbookdetailssql == false){ $f = 0; }
			}
		}
	}
	else
	{
		$rec = explode(".", $newrow_g);
		for ($c = 0; $c < count($rec); $c++) 
		{
			$x = $rec[$c];
			if($x!=2)
			{
				$mbookdetailsquery	=	"INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_b`, `measurement_d`, `measurement_contentarea`, `entry_date`, `remarks`) VALUES 
											('" . $mbookheaderid_new . "' ,
											 '" . $subdiv_id . "',
											 '" . $_POST['txt_dec_wk' . $x] . "' ,
											 '" . $_POST['txt_no' . $x] . "' ,
											 '" . $_POST['txt_l' . $x] . "' ,
											 '" . $_POST['txt_b' . $x] . "' ,
											 '" . $_POST['txt_d' . $x] . "' ,
											 '" . $_POST['txt_ca' . $x] . "' ,	
											 '" . $currentdate . "' ,									 
											 '" . $_POST['remarks'] . "')";
				$mbookdetailssql 	= 	mysql_query($mbookdetailsquery);
				if($mbookdetailssql == false){ $f = 0; }
			}
		}
	}
	if($f == 0)
	{ 
		$msg = "F"; 
	}
	else
	{ 
		$msg = "S"; 
	}
	header('Location: ViewMeasurementEntryList_Edit.php?msg='.$msg);
	
/*	$measure_date	=	$_POST['datepicker'];
	$sheet_id 		= trim($_POST['workorderno']);
	 //$dateformat = date_parse_from_format("d-m-Y", $date);
	$pieces = explode("-", $measure_date);
	$arr = array($pieces[2],$pieces[1],$pieces[0]);
	$mes_date=implode("-",$arr);
	$measure_date_query = "update mbookheader set date = '$mes_date' WHERE sheetid = '$sheet_id' AND mbheaderid = ".$_POST['hid_mbheaderid'];
	$measure_date_sql = mysql_query($measure_date_query);
	//echo $measure_date_query;exit;
    $div_id = $_POST['itemno'];
    $sub_divid = $_POST['subitemno'];
    $subsub_divid = $_POST['subsubitemno'];
    if(($subsub_divid == 0) && ($sub_divid == 0)) { $subdiv_id = $div_id; }
    if(($subsub_divid == 0) && ($sub_divid != 0)) { $subdiv_id = $sub_divid; }
    if(($subsub_divid != 0) && ($sub_divid != 0)) { $subdiv_id = $subsub_divid; }
    $measurement_type = $_POST['txt_measure_type'];
    $deleteflag = $_POST['hid_deleteflag'];
	$newrow_mt = $_POST['add_set_a2'];
        $newrow_st = $_POST['add_set_a3'];
        $newrow_g = $_POST['add_set_a1'];
       
            if($deleteflag != "")
            {
               $deletequery = "UPDATE mbookdetail SET mbdetail_flag = 'd' WHERE mbdetail_id = ".$_POST['txt_mbdetailid'];
               $delete_sql = mysql_query($deletequery);
            }
            if($measurement_type == "s")
            { //exit;
                if($newrow_mt == "")
                {
                   $mbookentryupdate_sql = "UPDATE mbookdetail SET descwork = '".$_POST['txt_dec_wk_mt']."' ,   
                                                                        measurement_dia = '".trim($_POST['sel_dia_mt'])."' , 
                                                                        measurement_no = '".trim($_POST['txt_no_mt'])."' ,
                                                                        measurement_l = '".trim($_POST['txt_l_mt'])."' ,
                                                                        measurement_contentarea  = '".trim($_POST['txt_ca_mt'])."' WHERE  mbdetail_id = '".$_POST['txt_mbdetailid']."'";
                    //echo $mbookentryupdate_sql;exit;
                   $mbookentryupdate_query = mysql_query($mbookentryupdate_sql); 
				   if (!$mbookentryupdate_query) { $f = 0; }
                   //header('Location: ViewMeasurementEntryList.php'); 
                }
                else
                {
                 $result = explode(".", $_POST['add_set_a2']);
                 for ($c = 0; $c < count($result); $c++)
                 {
                   $x = $result[$c];
                   if($x != 2)
                   {
                        if($x == 3)
                        {
                            if($deleteflag == "")
                            {
                                $mbookentryupdate_sql = "UPDATE mbookdetail SET descwork = '".$_POST['txt_dec_wk_mt'.$x]."' ,   
                                                                               measurement_dia = '".trim($_POST['sel_dia_mt'.$x])."' , 
                                                                               measurement_no = '".trim($_POST['txt_no_mt'.$x])."' ,
                                                                               measurement_l = '".trim($_POST['txt_l_mt'.$x])."' ,
                                                                               measurement_contentarea  = '".trim($_POST['txt_ca_mt'.$x])."' WHERE  mbdetail_id = '".$_POST['txt_mbdetailid']."'";
                               //echo $mbookentryupdate_sql;exit;
                               $mbookentryupdate_query = mysql_query($mbookentryupdate_sql);
							    if (!$mbookentryupdate_query) { $f = 0; }
                            }
                            else
                            {
                               $mbookdetailsquery="INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_dia`, `measurement_contentarea`, `remarks`) VALUES ('" .$_POST['hid_mbheaderid']. "' ,
                                                                                    '" . $subdiv_id . "',
                                                                                    '" . $_POST['txt_dec_wk_mt' . $x] . "' ,
                                                                                    '" . $_POST['txt_no_mt' . $x] . "' ,
                                                                                    '" . $_POST['txt_l_mt' . $x] . "' ,
                                                                                    '" . $_POST['sel_dia_mt' . $x] . "' ,
                                                                                    '" . $_POST['txt_ca_mt' . $x] . "' ,										 
                                                                                    '" . $_POST['remarks'] . "')";
                               $mbookdetailssql = mysql_query($mbookdetailsquery); 
							   if (!$mbookdetailssql) { $f = 0; }
                            }
                        }
                        else
                        {
                           $mbookdetailsquery="INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_dia`, `measurement_contentarea`, `remarks`) VALUES ('" .$_POST['hid_mbheaderid']. "' ,
                                                                                    '" . $subdiv_id . "',
                                                                                    '" . $_POST['txt_dec_wk_mt' . $x] . "' ,
                                                                                    '" . $_POST['txt_no_mt' . $x] . "' ,
                                                                                    '" . $_POST['txt_l_mt' . $x] . "' ,
                                                                                    '" . $_POST['sel_dia_mt' . $x] . "' ,
                                                                                    '" . $_POST['txt_ca_mt' . $x] . "' ,										 
                                                                                    '" . $_POST['remarks'] . "')";
                           $mbookdetailssql = mysql_query($mbookdetailsquery);
						   if (!$mbookdetailssql) { $f = 0; }
                        }
                   } 
                 }
                }
                
            }
            else if($measurement_type == "st")
            {    
                if($newrow_st == "")
                {  // echo $_POST['txt_mbdetailid']; echo "IF TYPE ".$measurement_type;exit;
                   $mbookentryupdate_sql = "UPDATE mbookdetail SET descwork = '".$_POST['txt_dec_wk_st']."' ,   
                                                                        measurement_no = '".trim($_POST['txt_no_st'])."' , 
                                                                        measurement_l = '".trim($_POST['txt_l_st'])."' ,
                                                                        measurement_b = '".trim($_POST['txt_b_st'])."' ,
                                                                        measurement_d = '".trim($_POST['txt_d_st'])."' ,
                                                                        structdepth_unit = '".trim($_POST['cmb_depthunit_st'])."' ,
                                                                        measurement_contentarea  = '".trim($_POST['txt_ca_st'])."' WHERE  mbdetail_id = '".$_POST['txt_mbdetailid']."'";
                
                $mbookentryupdate_query = mysql_query($mbookentryupdate_sql);
				if (!$mbookentryupdate_query) { $f = 0; }  
               // header('Location: ViewMeasurementEntryList.php'); 
                }
                else
                {  // echo "ELSE TYPE ".$measurement_type;exit;
                    $result = explode(".", $_POST['add_set_a3']);
                    for ($c = 0; $c < count($result); $c++)
                    {
                        $x = $result[$c];
                        if($x != 2)
                        {
                        if($x == 3)
                        {
                            if($deleteflag == "")
                            {   
                                $mbookentryupdate_sql = "UPDATE mbookdetail SET descwork = '".$_POST['txt_dec_wk_st'.$x]."' ,   
                                                                            measurement_no = '".trim($_POST['txt_no_st'.$x])."' , 
                                                                            measurement_l = '".trim($_POST['txt_l_st'.$x])."' ,
                                                                            measurement_b = '".trim($_POST['txt_b_st'.$x])."' ,
                                                                            measurement_d = '".trim($_POST['txt_d_st'.$x])."' ,
                                                                            structdepth_unit = '".trim($_POST['cmb_depthunit_st'.$x])."' ,
                                                                            measurement_contentarea  = '".trim($_POST['txt_ca_st'.$x])."' WHERE  mbdetail_id = '".$_POST['txt_mbdetailid']."'";
//                                 echo $mbookentryupdate_sql;exit;
                                $mbookentryupdate_query = mysql_query($mbookentryupdate_sql);
								if (!$mbookentryupdate_query) { $f = 0; } 
                            }
                            else
                            {   
                               $mbookdetailsquery="INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_b`, `measurement_d`, `structdepth_unit`, `measurement_contentarea`, `remarks`) VALUES ('".$_POST['hid_mbheaderid']."' ,
								  		 '" . $subdiv_id . "',
										 '" . $_POST['txt_dec_wk_st' . $x] . "' ,
										 '" . $_POST['txt_no_st' . $x] . "' ,
										 '" . $_POST['txt_l_st' . $x] . "' ,
										 '" . $_POST['txt_b_st' . $x] . "' ,
										 '" . $_POST['txt_d_st' . $x] . "' ,
										 '" . $_POST['cmb_depthunit_st' . $x] . "' ,
										 '" . $_POST['txt_ca_st' . $x] . "' ,										 
										 '" . $_POST['remarks'] . "')";
                              // echo $mbookdetailsquery;exit;
                                $mbookdetailssql = mysql_query($mbookdetailsquery); 
								if (!$mbookdetailssql) { $f = 0; }
                            }
                        }
                        else
                        {
                            $mbookdetailsquery="INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_b`, `measurement_d`, `structdepth_unit`, `measurement_contentarea`, `remarks`) VALUES ('".$_POST['hid_mbheaderid']."' ,
								  		 '" . $subdiv_id . "',
										 '" . $_POST['txt_dec_wk_st' . $x] . "' ,
										 '" . $_POST['txt_no_st' . $x] . "' ,
										 '" . $_POST['txt_l_st' . $x] . "' ,
										 '" . $_POST['txt_b_st' . $x] . "' ,
										 '" . $_POST['txt_d_st' . $x] . "' ,
										 '" . $_POST['cmb_depthunit_st' . $x] . "' ,
										 '" . $_POST['txt_ca_st' . $x] . "' ,										 
										 '" . $_POST['remarks'] . "')";
                           // echo "Empty = ".$mbookdetailsquery;exit;
                            $mbookdetailssql = mysql_query($mbookdetailsquery);
							if (!$mbookdetailssql) { $f = 0; }
                            
                        }
                        }
                    }
                }

                
            }
            else
            { //exit;//echo "General";exit;
                if($newrow_g == "")
                {
                    $mbookentryupdate_sql = "UPDATE mbookdetail SET descwork = '".$_POST['txt_dec_wk']."' ,   
                                                                        measurement_no = '".trim($_POST['txt_no'])."' , 
                                                                        measurement_l = '".trim($_POST['txt_l'])."' ,
                                                                        measurement_b = '".trim($_POST['txt_b'])."' ,
                                                                        measurement_d = '".trim($_POST['txt_d'])."' ,
                                                                        measurement_contentarea  = '".trim($_POST['txt_ca'])."' WHERE  mbdetail_id = '".$_POST['txt_mbdetailid']."'";
                    // echo $mbookentryupdate_sql;exit;
                    $mbookentryupdate_query = mysql_query($mbookentryupdate_sql);  
					if (!$mbookentryupdate_query) { $f = 0; }
                   // header('Location: ViewMeasurementEntryList.php');
                }
                else
                {
                    $result = explode(".", $_POST['add_set_a1']);
                    for ($c = 0; $c < count($result); $c++)
                    {
                        $x = $result[$c];
                     if($x != 2)
                     {
                        if($x == 3)
                        {
                            if($deleteflag == "")
                            {
                            $mbookentryupdate_sql = "UPDATE mbookdetail SET descwork = '".$_POST['txt_dec_wk' . $x]."' ,   
                                                                        measurement_no = '".trim($_POST['txt_no' . $x])."' , 
                                                                        measurement_l = '".trim($_POST['txt_l' . $x])."' ,
                                                                        measurement_b = '".trim($_POST['txt_b' . $x])."' ,
                                                                        measurement_d = '".trim($_POST['txt_d' . $x])."' ,
                                                                        measurement_contentarea  = '".trim($_POST['txt_ca' . $x])."' WHERE  mbdetail_id = '".$_POST['txt_mbdetailid']."'";
                             //echo $mbookentryupdate_sql;exit;
                            $mbookentryupdate_query = mysql_query($mbookentryupdate_sql);
							if (!$mbookentryupdate_query) { $f = 0; }
                            }
                            else
                            {
                               $mbookdetailsquery="INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_b`, `measurement_d`, `measurement_contentarea`, `remarks`) VALUES ('" . $_POST['hid_mbheaderid'] . "' ,
								  		 '" . $subdiv_id . "',
										 '" . $_POST['txt_dec_wk' . $x] . "' ,
										 '" . $_POST['txt_no' . $x] . "' ,
										 '" . $_POST['txt_l' . $x] . "' ,
										 '" . $_POST['txt_b' . $x] . "' ,
										 '" . $_POST['txt_d' . $x] . "' ,
										 '" . $_POST['txt_ca' . $x] . "' ,										 
										 '" . $_POST['remarks'] . "')";
                            $mbookdetailssql = mysql_query($mbookdetailsquery);  
							if (!$mbookdetailssql) { $f = 0; }
                            }
                        }
                        else
                        {
                            $mbookdetailsquery="INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_b`, `measurement_d`, `measurement_contentarea`, `remarks`) VALUES ('" . $_POST['hid_mbheaderid'] . "' ,
								  		 '" . $subdiv_id . "',
										 '" . $_POST['txt_dec_wk' . $x] . "' ,
										 '" . $_POST['txt_no' . $x] . "' ,
										 '" . $_POST['txt_l' . $x] . "' ,
										 '" . $_POST['txt_b' . $x] . "' ,
										 '" . $_POST['txt_d' . $x] . "' ,
										 '" . $_POST['txt_ca' . $x] . "' ,										 
										 '" . $_POST['remarks'] . "')";
                            $mbookdetailssql = mysql_query($mbookdetailsquery); 
							if (!$mbookdetailssql) { $f = 0; }
                        }
                      }
                    }
                }
            }*/
		/*if($f == 0){ $msg = "F"; }
		else{ $msg = "S"; }
		header('Location: ViewMeasurementEntryList_Edit.php?msg='.$msg);*/
}
if($_POST["submit"] == " Submit ") 
{

	 $lock_value 		= 	$_POST['hid_lock_unlock'];
	 $unit 				= 	$_POST['remarks'];
     $sheet_id 			= 	trim($_POST['workorderno']);
	 if((trim($_POST['subsubsubitemno']) != 0) && (trim($_POST['subsubsubitemno']) != ""))
     {
         $subdiv_id 	= 	trim($_POST['subsubsubitemno']);
     }
	 else if((trim($_POST['subsubitemno']) != 0) && (trim($_POST['subsubitemno']) != ""))
     {
         $subdiv_id 	= 	trim($_POST['subsubitemno']);
     }
     else if((trim($_POST['subitemno']) != 0) && (trim($_POST['subitemno']) != ""))
     {
         $subdiv_id 	= 	trim($_POST['subitemno']);
     }
    
	 
     else
     {
         $divid			= 	trim($_POST['itemno']);
         $sql_selectsubid = "select subdiv_id from subdivision where div_id='$divid'";
         $res_subid 	= 	mysql_query($sql_selectsubid);
         $subdiv_id 	= 	@mysql_result($res_subid,0,'subdiv_id');
     }
	$date				=	$_POST['datepicker'];
	$pieces 			= 	explode("-", $date);
	$arr 				= 	array($pieces[2],$pieces[1],$pieces[0]);
	$date				=	implode("-",$arr);
    $mbookdetailquery 	= 	"SELECT  subdiv_id,shortnotes FROM schdule WHERE subdiv_id='$subdiv_id'";
    $mbookdetailsql 	= 	dbQuery($mbookdetailquery);
    $mbookdetaillist 	= 	dbFetchAssoc($mbookdetailsql);
    $div_id 			= 	trim($_POST['itemno']);
    $unit 				= 	trim($_POST['txt_unit']);
    $billno 			= 	trim($_POST['workorderno']);
    $agreeno 			= 	trim($_POST['txt_agmt_no']);
    $conname 			= 	trim($_POST['txt_cont_name']);
    $techsanction 		= 	trim($_POST['txt_tech_san']);
    $wrkname 			= 	trim($_POST['txt_wk_name']);
    
	$mbookheaderquery 	= 	"INSERT INTO `mbookheader`(`date`, `sheetid`, `allotmentid`,`divid`,`subdivid`, `active`, `staffid`, `userid`) VALUES ('$date','$sheet_id',1,'$div_id','$subdiv_id',1,'$staffid','$userid')";
    $mbookheadersql 	= 	mysql_query($mbookheaderquery);
    $lastmbookheaderid 	= 	mysql_insert_id();
	if($_POST['txt_measure_type']=="s")
	{
		$rec = explode(".", $_POST['add_set_a2']);
	
		for ($c = 0; $c < count($rec); $c++) 
		{
			$x = $rec[$c];
			if($x!=2) 
			{
			
			$mbookdetailsquery	=	"INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_dia`, `measurement_contentarea`, `entry_date`, `remarks`) VALUES ('" . $lastmbookheaderid . "' ,
											 '" . $subdiv_id . "',
											 '" . $_POST['txt_dec_wk_mt' . $x] . "' ,
											 '" . $_POST['txt_no_mt' . $x] . "' ,
											 '" . $_POST['txt_l_mt' . $x] . "' ,
											 '" . $_POST['sel_dia_mt' . $x] . "' ,
											 '" . $_POST['txt_ca_mt' . $x] . "' ,
											 '" . $date . "' ,										 
											 '" . $_POST['remarks'] . "')";
			$mbookdetailssql 	= 	mysql_query($mbookdetailsquery);
			}
		}
	}
        
        
    elseif($_POST['txt_measure_type']=="st")
	{
		$rec = explode(".", $_POST['add_set_a3']);
		for ($c = 0; $c < count($rec); $c++) 
		{
			$x = $rec[$c];
			if($x!=2) 
			{
			
			$mbookdetailsquery	=	"INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_b`, `measurement_d`, `structdepth_unit`, `measurement_contentarea`, `entry_date`, `remarks`) VALUES ('" . $lastmbookheaderid . "' ,
											 '" . $subdiv_id . "',
											 '" . $_POST['txt_dec_wk_st' . $x] . "' ,
											 '" . $_POST['txt_no_st' . $x] . "' ,
											 '" . $_POST['txt_l_st' . $x] . "' ,
											 '" . $_POST['txt_b_st' . $x] . "' ,
											 '" . $_POST['txt_d_st' . $x] . "' ,
											 '" . $_POST['cmb_depthunit_st' . $x] . "' ,
											 '" . $_POST['txt_ca_st' . $x] . "' ,	
											 '" . $date . "' ,									 
											 '" . $_POST['remarks'] . "')";
			$mbookdetailssql 	= 	mysql_query($mbookdetailsquery);
			}
		}
	}
        
	else
	{
		$rec = explode(".", $_POST['add_set_a1']);
		for ($c = 0; $c < count($rec); $c++) 
		{
			$x = $rec[$c];
			if($x!=2)
			{
			$mbookdetailsquery	=	"INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_b`, `measurement_d`, `measurement_contentarea`, `entry_date`, `remarks`) VALUES ('" . $lastmbookheaderid . "' ,
											 '" . $subdiv_id . "',
											 '" . $_POST['txt_dec_wk' . $x] . "' ,
											 '" . $_POST['txt_no' . $x] . "' ,
											 '" . $_POST['txt_l' . $x] . "' ,
											 '" . $_POST['txt_b' . $x] . "' ,
											 '" . $_POST['txt_d' . $x] . "' ,
											 '" . $_POST['txt_ca' . $x] . "' ,	
											 '" . $date . "' ,									 
											 '" . $_POST['remarks'] . "')";
			$mbookdetailssql 	= 	mysql_query($mbookdetailsquery);
			}
		}
	}
    if ($mbookdetailssql == true && $mbookheadersql == true ) 
	{
        $msg = "Data Submitted Successfully";
    }
	else
	{
		echo "error";
	} 

}//submit 
/*if($_POST["view_x"])
{
	exit;
	header('Location: ViewMeasurementEntryList.php');
}
*/?>
<?php require_once "Header.html"; ?>

    <script type="text/javascript"  language="JavaScript">
		
		
		function clear()
		{
		alert("clear");
		document.getElementById("remarks").value = 0;
		}
         function validation()
        {
            if (document.form.workorderno.value == 0)
            {
                alert("Select the Work Order No");
                return false;
            }
            if (document.form.itemno.value == 0)
            {
                alert("Select the Item No");
                return false;
            }
            if (document.form.subitemno.value == 0)
            {
                alert("Select the Sub Item No");
                return false;
            }
            if (document.form.add_set_a1.value == "")
            {
                alert("Add atleast one row");
                return false;
            }
        }
        
        function func_items()
        {
            var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            //alert(document.form.workorderno.value);
            strURL = "find_items.php?item_no=" + document.form.workorderno.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText

                    if (data == "")
                    {
                        alert("No Records Found");
                        document.form.itemno.value = '';
                    }
                    else
                    {
                        
                        var name 	= 	data.split("*");
                        document.form.itemno.length = 0
                        var optn 	= 	document.createElement("option")
                        optn.value 	= 	0;
                        optn.text 	= 	"Item No";
                        document.form.itemno.options.add(optn)

                        var c = name.length
                        var a = c / 2;
                        var b = a + 1;
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
                            var optn 	= 	document.createElement("option")
                            optn.value 	= 	name[i];
                            optn.text 	= 	name[j];
                            document.form.itemno.options.add(optn)
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
        function func_item_no()
        {
            func_items()
			document.form.txt_workorder_no.value = "";
            var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_item_no.php?item_no=" + document.form.workorderno.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
                    if (data == "")
                    {
                        alert("No Records Found");
                        document.form.itemno.value = '';
                    }
                    else
                    {
                        var wrkname = data.split("##");
						document.form.txt_workorder_no.value 		= wrkname[6];
						document.form.txt_measurement_date.value 	= wrkname[5];
                        document.form.txt_wk_name.value 			= wrkname[0];
                        document.form.txt_cont_name.value 			= wrkname[2];
                        document.form.txt_tech_san.value 			= wrkname[1];
                        document.form.txt_agmt_no.value 			= wrkname[3];
                        document.form.txt_runn_bill_no.value 		= wrkname[4];
						
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("workorderno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_work_no.value = strUser;
        }
        function func_subitem_no()
        { 
            //var item = selitem.options[selitem.selectedIndex].text;
            var xmlHttp;
            var data;
            var i, j;
			document.form.txt_workorderqty.value 		= "";
			document.form.txt_remainingqty.value 		= "";
			document.form.hid_remainingqty.value 		= "";
			document.form.txt_deviatedqty.value 		= "";
			document.form.hid_deviatedqty.value 		= "";
			document.form.txt_deviatedqty_percent.value = "";
			document.form.hid_deviatedqty_percent.value = "";
			document.form.txt_totalqty.value 			= "";
			document.form.hid_totalqty.value 			= "";
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_subitem_no.php?work_no=" + document.form.workorderno.value + "&div_id=" + document.form.itemno.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
                    if (data == "")
                    {
                        alert("No Records Found");
                        document.form.subitemno.value = '';
                        document.form.subsubitemno.value = '';
                    }
                    else
                    {
                        var name 	= 	data.split("*");
                        document.form.subitemno.length = 0;
                        var optn 	= 	document.createElement("option");
                        optn.value 	= 	0;
                        optn.text 	= 	"Sub Item 1";
                        document.form.subitemno.options.add(optn);
                        
                        document.form.subsubitemno.length = 0;
                        var optn1 	= 	document.createElement("option");
                        optn1.value = 	0;
                        optn1.text 	= 	"Sub Item 2";
                        document.form.subsubitemno.options.add(optn1);
						
						document.form.subsubsubitemno.length = 0;
                        var optn1 	= 	document.createElement("option");
                        optn1.value = 	0;
                        optn1.text 	= 	"Sub Item 3";
                        document.form.subsubsubitemno.options.add(optn1);
                        
                        
                        var cont = 1;
                        var c = name.length;
                        var a = c / 2;
                        var b = a + 1;
                        var pre_opt_text = "";
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
                            var spl_sitem = name[j].split(".");
                           /* if(c == 4)
                            //if(spl_sitem.length == 1)
                            //if((spl_sitem.length == 1) && (item == name[j]))
                            {
                                var optn = document.createElement("option")
                                optn.value = 0;
                                optn.text = "--None---";
                                document.form.subitemno.options.add(optn)
                            }*/
                           
                                if(spl_sitem.length == 2)
                                {
                                    var optn 	= document.createElement("option")
                                    optn.value 	= name[i];
                                    optn.text 	= name[j];
                                    document.form.subitemno.options.add(optn)
									pre_opt_text = optn.text;
                                    cont++;
                                }
                                if(spl_sitem.length > 2)
                                {
                                    var optn 	= document.createElement("option")
                                    //optn.value = name[i];
                                    optn.value 	= "";
                                    optn.text 	=  spl_sitem[0] +"."+ spl_sitem [1];
                                    if(optn.text != pre_opt_text)
                                    {
                                        document.form.subitemno.options.add(optn)
                                    }
                                    pre_opt_text = optn.text;
                                    cont++;
                                }
                                
                            
                        } 
                        if(cont <= 1)
                        {
                           // alert("count="+cont);
                            document.getElementById("subitemno").disabled = true;
                            document.getElementById("subsubitemno").disabled = true;
							document.getElementById("subsubsubitemno").disabled = true;
                        }
                        else
                        {
                            document.getElementById("subitemno").disabled = false;
                            document.getElementById("subsubitemno").disabled = false;
							document.getElementById("subsubsubitemno").disabled = false;
                        }
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("itemno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_item_no.value = strUser;
        }
        function find_subsubitem(selsubitem)
        {
           	var ssitem = selsubitem.options[selsubitem.selectedIndex].text;
			var ssitemvalue = selsubitem.options[selsubitem.selectedIndex].value;
			if((ssitemvalue == 0) && (ssitemvalue != ""))
			{
				return false;
				exit();
			}
            var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_subitem_no.php?work_no=" + document.form.workorderno.value + "&div_id=" + document.form.itemno.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
                    if (data == "")
                    {
                        alert("No Records Found");
                        document.form.subsubitemno.value = '';
                    }
                    else
                    {   
                       // var subitem = ssitem.split(".");
                       // var subitem_part1 = subitem[0];
                       // alert(subitem);
                        //alert(subitem_part1);
                        var name = data.split("*");
						
                        document.form.subsubitemno.length = 0
                        var optn 	= document.createElement("option")
                        optn.value 	= 0;
                        optn.text 	= "Sub Item 2";
                        document.form.subsubitemno.options.add(optn)
						
						document.form.subsubsubitemno.length = 0;
                        var optn1 	= document.createElement("option");
                        optn1.value = 0;
                        optn1.text 	= "Sub Item 3";
                        document.form.subsubsubitemno.options.add(optn1);
						
                        var cont = 1;
                        var c = name.length
                        var a = c / 2;
                        var b = a + 1;
						var pre_opt_text = "";
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
                           // if(name[j].length > 2)
                            //{
                                var sitem = name[j].split(".");
                                //var sub = sitem[0] +"."+ sitem[1];
								var subsubitem = sitem[0] +"."+ sitem[1] +"."+ sitem[2];
								var ssitemtemp 	= 	sitem[0] +"."+ sitem[1];
								if(ssitem == ssitemtemp)
								{
									if(sitem.length == 3)
									{
										var optn 	= document.createElement("option")
										optn.value 	= name[i];
										optn.text 	= subsubitem;
										document.form.subsubitemno.options.add(optn)
										pre_opt_text = optn.text;
										cont++;
									}
									if(sitem.length > 3)
									{
									   // if(ssitem == sub)
									   // {
											var subsubitem = sitem[0] +"."+ sitem[1] +"."+ sitem[2];
											var optn 	= document.createElement("option")
											optn.value 	= "";
											optn.text 	= subsubitem;
											if(optn.text != pre_opt_text)
											{
												document.form.subsubitemno.options.add(optn)
											}
											pre_opt_text = optn.text;
											cont ++;
									   // }
									}
								}
                            //}
                        }
                     if(cont <= 1)
                        {
                            document.getElementById("subsubitemno").disabled = true;
							document.getElementById("subsubsubitemno").disabled = true;
                        }
                        else
                        {
                            document.getElementById("subsubitemno").disabled = false;
							document.getElementById("subsubsubitemno").disabled = false;
                        }   
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("itemno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_item_no.value = strUser;
        }
		
		function find_subsubsubitem(selsubsubitem)
        {
            var sssitem = selsubsubitem.options[selsubsubitem.selectedIndex].text;
			var sssitemvalue = selsubsubitem.options[selsubsubitem.selectedIndex].value;
			if((sssitemvalue == 0) && (sssitemvalue != ""))
			{
				return false;
				exit();
			}
            var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_subitem_no.php?work_no=" + document.form.workorderno.value + "&div_id=" + document.form.itemno.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
                    if (data == "")
                    {
                        alert("No Records Found");
                        document.form.subsubsubitemno.value = '';
                    }
                    else
                    {   
                        var name 	= data.split("*");
                        document.form.subsubsubitemno.length = 0
                        var optn 	= document.createElement("option")
                        optn.value 	= 0;
                        optn.text 	= "Sub Item 3";
                        document.form.subsubsubitemno.options.add(optn)
                        var cont 	= 1;
                        var c 		= name.length
                        var a 		= c / 2;
                        var b 		= a + 1;
						var pre_opt_text = "";
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
							//alert(name[j]);
                           // if(name[j].length > 2)
                            //{
                                var sitem 			= 	name[j].split(".");
                                var sssitemtemp 	= 	sitem[0] +"."+ sitem[1] +"."+ sitem[2];
                                if(sitem.length > 3)
                                {
                                   if(sssitem == sssitemtemp)
                                   {
                                        var subsubsubitem 	= 	sitem[0] +"."+ sitem[1] +"."+ sitem[2] +"."+ sitem[3];
                                        var optn 			= 	document.createElement("option")
                                        optn.value 			= 	name[i];
                                        optn.text 			= 	subsubsubitem;
										if(optn.text 		!= 	pre_opt_text)
                                   		{
                                        document.form.subsubsubitemno.options.add(optn);
										}
										pre_opt_text 		= 	optn.text;
                                        cont ++;
										//pre_opt_text = optn.text;
                                   }
                                }
                            //}
                        }
                     if(cont <= 1)
                        {
                            document.getElementById("subsubsubitemno").disabled = true;
                        }
                        else
                        {
                            document.getElementById("subsubsubitemno").disabled = false;
                        }   
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("itemno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_item_no.value = strUser;
        }
		
        function getitem_desc(itemval)
        {
            var item_name = itemval.options[itemval.selectedIndex].text;
            var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_item_descrip.php?div_name=" + item_name + "&div_id=" + document.form.itemno.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
            if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
                    if (data == "")
                    {
                        alert("No Records Found");
                        document.form.txt_desc.value = '';
                    }
                    else
                    {  
                        var name = data.split("*");
                        document.getElementById("remarks").value = name[1];
                        document.getElementById("label_unit").innerHTML = name[1];
			if(name[2]=="")
                            { 
				document.form.descriptionnotes.value=name[0];
                            }
			else
                            {
				document.form.descriptionnotes.value=name[2];
                            } 
			//document.form.txt_unit.value = name[1];
                        
                        if((name[3] == 's') || (name[3] == 'st'))
                        {
                            document.getElementById("txt_measure_type").value = name[3];
                        }
                        else
                        {
                           document.getElementById("txt_measure_type").value = "g"; 
                        }
                    }
                }
            }   
            xmlHttp.send(strURL);
        }
        function find_desc(subitemid)
        {
            var sub_item_id = subitemid.options[subitemid.selectedIndex].value;
            if(sub_item_id != "")
            {
                var xmlHttp;
                var data;
                var i, j;
                if (window.XMLHttpRequest) // For Mozilla, Safari, ...
                {
                    xmlHttp = new XMLHttpRequest();
                }
                else if (window.ActiveXObject) // For Internet Explorer
                {
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
               // strURL = "find_desc.php?subitem_no=" + document.form.subitemno.value;
                strURL = "find_desc.php?subitem_no=" + sub_item_id;
                xmlHttp.open('POST', strURL, true);
                xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xmlHttp.onreadystatechange = function ()
                {
                    if (xmlHttp.readyState == 4)
                    {
                        data = xmlHttp.responseText

                        var name = data.split("*");
                       if (data == "")
                        {
                            alert("No Records Found");
                            document.form.txt_desc.value = '';
                        }
                        else
                        { 
                                                    document.getElementById("remarks").value = name[1];
                                                    document.getElementById("label_unit").innerHTML = name[1];
                                                    if(name[2]=="")
                                                    {var j = name[1];

                                                    document.form.descriptionnotes.value=name[0];
                                                    }
                                                    else
                                                    {
                                                    document.form.descriptionnotes.value=name[2];}
                                                    //document.form.txt_unit.value = name[1];
                                                  if((name[3] == 's') || (name[3] == 'st'))
                                                    {
                                                        document.getElementById("txt_measure_type").value = name[3];
                                                    }
                                                    else
                                                    {
                                                       document.getElementById("txt_measure_type").value = "g"; 
                                                    }

                                                    //document.form.text_desc.value = name[0];                      
                        }


                    }
                }
                xmlHttp.send(strURL);

                var e = document.getElementById("subitemno");
                var strUser = e.options[e.selectedIndex].text;
                document.form.txt_boq.value = strUser;
             }
        }
		
		function get_itemquantity(obj)
		{
			var xmlHttp;
            var data;
            var i, j, itemid = "";
			var id 				= 	obj.id;
			var temp			=	0;
			var item_text 		= 	obj.options[obj.selectedIndex].text;
			var item_val 		= 	obj.options[obj.selectedIndex].value;
			var itemno 			= 	document.form.itemno.value;
			var subitemno 		= 	document.form.subitemno.value;
			var subsubitemno 	= 	document.form.subsubitemno.value;
			var subsubsubitemno = 	document.form.subsubsubitemno.value;
			if((subsubsubitemno != "") && (subsubsubitemno != 0))
			{
					itemid 	= 	subsubsubitemno;
				var temp 	= 	2;
			}
			else if((subsubitemno != "") && (subsubitemno != 0))
			{
					itemid 	= 	subsubitemno;
				var temp 	= 	2;
			}
			else if((subitemno != "") && (subitemno != 0))
			{
					itemid 	= 	subitemno;
				var temp 	= 	2;
			}
			else
			{
				if((itemno != 0) && (itemno != ""))
				{
						itemid 	= 	itemno;
					var temp 	= 	1;
				}
				
			}
			//alert(itemid)
			//alert(temp)
			/*if(id == "itemno")
			{
				var len1 = document.form.subitemno.length;
				if(len1 == 1)
				{
					var temp = 1;
				}
				else
				{
					var temp = 0;
				}
			}
			else if(id == "subitemno")
			{
				var len2 = document.form.subsubitemno.length;
				if(len2 == 1)
				{
					var temp = 2;
				}
				else
				{
					var temp = 0;
				}
			}
			else if(id == "subsubitemno")
			{
				var len3 = document.form.subsubsubitemno.length;
				if(len3 == 1)
				{
					var temp = 2;
				}
				else
				{
					var temp = 0;
				}
			}
			else
			{
				var temp = 2;
			}*/
			
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_item_quantity.php?work_no=" + document.form.workorderno.value + "&item_val=" + item_val + "&item_text=" + item_text + "&temp=" + temp + "&itemid=" + itemid;// + "&len2=" + len2 + "&len3=" + len3;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
                    if (data != "")
                    {
                        var qty = data.split("*");
						if((qty[0] != 0) && (qty[0] != ""))
						{
							var measure_type 			= document.form.txt_measure_type.value;
							var workorder_qty 			= Number(qty[0]);
							var deviated_qty_percent 	= Number(qty[2]);
							var deviated_qty 			= Number(workorder_qty)*Number(deviated_qty_percent)/100;
							var total_qty 				= Number(workorder_qty) + Number(deviated_qty);
							var total_percent			= 100 + Number(deviated_qty_percent);
							if(measure_type == 's')
							{
								var used_qty = Number((qty[1]/1000));
							}
							else if(measure_type == 'st')
							{
								var used_qty = Number((qty[1]/1000));
							}
							else
							{
								var used_qty = Number(qty[1]);
							}
							var remaining_qty 		= 	Number(workorder_qty) + Number(deviated_qty) - Number(used_qty);
							var usedqty_percent 	= 	Number(used_qty) * 100/ Number(workorder_qty);
							var remainqty_percent 	= 	Number(remaining_qty) * 100/ Number(workorder_qty);
							
							document.form.txt_workorderqty.value 			= workorder_qty.toFixed(3);
							
							document.form.txt_remainingqty.value 			= remaining_qty.toFixed(3);
							document.form.hid_remainingqty.value 			= remaining_qty.toFixed(3);
							document.form.txt_remainingqty_percent.value 	= remainqty_percent.toFixed(2);
							document.form.hid_remainingqty_percent.value 	= remainqty_percent.toFixed(2);
							
							document.form.txt_deviatedqty.value 			= deviated_qty.toFixed(3);
							document.form.hid_deviatedqty.value 			= deviated_qty.toFixed(3);
							document.form.txt_deviatedqty_percent.value 	= deviated_qty_percent.toFixed(2);
							document.form.hid_deviatedqty_percent.value 	= deviated_qty_percent.toFixed(2);
							
							document.form.txt_totalqty.value 				= total_qty.toFixed(3);
							document.form.hid_totalqty.value 				= total_qty.toFixed(3);
							document.form.txt_totalqty_percent.value 		= total_percent.toFixed(2);
							document.form.hid_totalqty_percent.value 		= total_percent.toFixed(2);
							
							document.form.txt_usedqty.value 				= used_qty.toFixed(3);
							document.form.hid_usedqty.value 				= used_qty.toFixed(3);
							document.form.txt_usedqty_percent.value 		= usedqty_percent.toFixed(2);
							document.form.hid_usedqty_percent.value 		= usedqty_percent.toFixed(2);
							//alert(workorder_qty);
							//alert(qty[1])
						}
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("workorderno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_work_no.value = strUser;
			
		}
		function get_current_entered_qty()
		{
			var workorder_qty 		= 	document.form.txt_workorderqty.value;
			var deviateqty_percent 	= 	document.form.txt_deviatedqty_percent.value;
			var deviate_qty 		= 	Number(workorder_qty) * Number(deviateqty_percent) / 100;
			var total_qty 			= 	document.form.txt_totalqty.value;
			var measure_type 		= 	document.form.txt_measure_type.value; 
			var content_area 		= 	0;
			if(measure_type == 'g')
			{
				var txt_box_id_str = document.form.add_set_a1.value;
				var txt_box_id = txt_box_id_str.split(".");
				for(var i=0; i<txt_box_id.length; i++)
				{
					if(txt_box_id[i] != 2)
					{
						var ca = document.getElementById("txt_ca"+txt_box_id[i]).value;
						content_area = Number(content_area) + Number(ca);
					}
				}
			}
			if(measure_type == 's')
			{
			
				var txt_box_id_str = document.form.add_set_a2.value;
				var txt_box_id = txt_box_id_str.split(".");
				var unit_weight;
				for(var i=0; i<txt_box_id.length; i++)
				{
					if(txt_box_id[i] != 2)
					{
						var dia = document.getElementById("sel_dia_mt"+txt_box_id[i]).value;
						if((dia != "") && (dia != 0))
						{
							if(dia == 8) { unit_weight = 0.395; }
							if(dia == 10) { unit_weight = 0.617; }
							if(dia == 12) { unit_weight = 0.888; }
							if(dia == 16) { unit_weight = 1.580; }
							if(dia == 20) { unit_weight = 2.470; }
							if(dia == 25) { unit_weight = 3.860; }
							if(dia == 28) { unit_weight = 4.830; }
							if(dia == 32) { unit_weight = 6.313; }
							if(dia == 36) { unit_weight = 8.000; }
						}
						var ca_in_kgs = document.getElementById("txt_ca_mt"+txt_box_id[i]).value;
						var ca = (Number(ca_in_kgs) * Number(unit_weight)/1000);
						content_area = Number(content_area) + Number(ca);
					}
				}
			}
			if(measure_type == 'st')
			{
				var txt_box_id_str = document.form.add_set_a3.value;
				var txt_box_id = txt_box_id_str.split(".");
				for(var i=0; i<txt_box_id.length; i++)
				{
					if(txt_box_id[i] != 2)
					{
						var ca = document.getElementById("txt_ca_st"+txt_box_id[i]).value;
						content_area = Number(content_area) + (Number(ca)/1000);
					}
				}
			}
			var remain_qty = document.form.hid_remainingqty.value;
			
			var new_remain_qty 		= 	Number(remain_qty) - Number(content_area);
			var remainqty_percent 	= 	Number(new_remain_qty) * 100/ Number(total_qty);
			
			var used_qty			= 	Number(workorder_qty) + Number(deviate_qty) - Number(new_remain_qty);
			var usedqty_percent 	= 	Number(used_qty) * 100/ Number(workorder_qty);
			
			document.form.txt_usedqty.value 				= used_qty.toFixed(3);
			document.form.txt_usedqty_percent.value 		= usedqty_percent.toFixed(2);
			document.form.txt_remainingqty_percent.value 	= remainqty_percent.toFixed(2);
			
			//alert(used_qty);
			if(used_qty > workorder_qty)
			{
				alert("Entered measurement quantity is exceeded than Work Order quantity..")
				//return false;
				//exit();
			}
			document.form.txt_remainingqty.value = new_remain_qty.toFixed(3);
		}
		
  		function display()
		{
			if(document.form.txt_measure_type.value=="s")
			{   
				document.getElementById("table1").className = "hide";
				document.getElementById("table2").className = "";
                document.getElementById("table3").className = "hide";
				
			}
			else if(document.form.txt_measure_type.value=="st")
			{
				document.getElementById("table1").className = "hide";
				document.getElementById("table2").className = "hide";
                document.getElementById("table3").className = "";
			}
             else
			{
				document.getElementById("table1").className = "";
				document.getElementById("table2").className = "hide";
                document.getElementById("table3").className = "hide";
			}
		}
			
			
		function valid()
		{
			if(document.form.txt_measure_type.value=="s")
			{
				if(document.form.txt_dec_wk_mt.value=="")
				{
					alert("Enter Description");
					exit;
				}
				if(document.form.txt_no_mt.value=="")
				{
					if((document.form.txt_dec_wk_mt.value!="") && (document.form.sel_dia_mt.value!=""))
					{
					alert("Enter Number");
					exit;
					}
				}
				if(document.form.sel_dia_mt.value=="")
				{
					if((document.form.txt_dec_wk_mt.value!="") && (document.form.txt_no_mt.value!=""))
					{
					alert("Select Dia of rod");
					exit;
					}
				}
				if(document.form.txt_l_mt.value=="")
				{
					if((document.form.txt_dec_wk_mt.value!="") && (document.form.sel_dia_mt.value!=""))
					{
					alert("Enter Length");
					exit;
					}
				}
				
			}
            else if(document.form.txt_measure_type.value=="st")
			{
				if(document.form.txt_dec_wk_st.value=="")
				{
					alert("Enter Description");
					exit;
				}
				if(document.form.txt_no_st.value=="")
				{
					if(document.form.txt_d_st.value!="" || document.form.txt_b_st.value!= "" || document.form.txt_l_st.value!="" || document.form.cmb_depthunit_st.value!="")
					{
						alert("Enter number");
						exit;
					}
				}
				if((document.form.txt_d_st.value!="") && (document.form.cmb_depthunit_st.value==""))
				{
					alert("Select Unit of the Depth");
					exit;
				}
				if((document.form.txt_d_st.value=="") && (document.form.cmb_depthunit_st.value!=""))
				{
					alert("Selected Depth Unit is Invalid");
					exit;
				}
			}
			else 
			{
				if(document.form.txt_dec_wk.value=="")
						{alert("Enter Description");exit;}
				if(document.form.txt_no.value=="")
				{
					if(document.form.txt_d.value!="" || document.form.txt_b.value!= "" || document.form.txt_l.value!="")
					{
						alert("Enter number");
						exit;
					}
				}
			}
							
			/*if(document.form.remarks.value=="Cum" || document.form.remarks.value=="cum")
			{
				if(document.form.txt_l.value=="" || document.form.txt_d.value=="" || document.form.txt_b.value== "")
				{alert("Enter Length Breadth and Depth");exit;}
			}
			if(document.form.remarks.value=="Sqm")
			{   
				if(document.form.txt_l.value=="" || document.form.txt_d.value=="")
				{alert("Enter Length and Depth");exit;}
				
			}*/
			
			
	}
	
	function cls(obj)
	{
		document.form.descriptionnotes.value="";
		document.form.remarks.value="";
	 	document.getElementById("label_unit").innerHTML = "";
		var cmb_id = obj.id;
		var x = "dummy variable";
		if(cmb_id == "workorderno")
		{
			document.form.itemno.length = 1;
			document.form.subitemno.length = 1;
			document.form.subsubitemno.length = 1;
			document.form.subsubsubitemno.length = 1;
		}
		else if (cmb_id == "itemno")
		{
			document.form.subitemno.length = 1;
			document.form.subsubitemno.length = 1;
			document.form.subsubsubitemno.length = 1;
		}
		else if (cmb_id == "subitemno")
		{
			document.form.subsubitemno.length = 1;
			document.form.subsubsubitemno.length = 1;
		}
		else if (cmb_id == "subsubitemno")
		{
			document.form.subsubsubitemno.length = 1;
		}
		else
		{
			x = "";
		}
	 //document.getElementById("label_unit").outerHTML="";
	}
    //.......Multiple  Row Add Function........//
	
	 //......................FOR GENERAL SECTION STARTS HERE..............................//
        var add_row_s = 3;
        var prev_edit_row = 0
        function addrow()
        {	
            valid();
			var workorder_qty 		= 	document.form.txt_workorderqty.value;
			var deviateqty_percent 	= 	document.form.txt_deviatedqty_percent.value;
			var deviate_qty 		= 	Number(workorder_qty) * Number(deviateqty_percent) / 100;
			var total_qty 			= 	document.form.txt_totalqty.value;
			var content_area = 0;
			var txt_box_id_str 		= 	document.form.add_set_a1.value.trim();
			var current_ca 			= 	document.form.txt_ca.value;
			if(current_ca == "")
			{
				current_ca = 0;
			}
			content_area = (Number(content_area) + Number(current_ca));
			if(txt_box_id_str != "")
			{
				var txt_box_id = txt_box_id_str.split(".");
				for(var i=0; i<txt_box_id.length; i++)
				{
					if(txt_box_id[i] != 2)
					{
						var ca 			= 	document.getElementById("txt_ca"+txt_box_id[i]).value;
						content_area 	= 	Number(content_area) + Number(ca);
					}
				}
									
			}
			var remain_qty 			= 	document.form.hid_remainingqty.value;
			var new_remain_qty 		= 	Number(remain_qty) - Number(content_area);
			var remainqty_percent 	= 	Number(new_remain_qty) * 100/ Number(total_qty);
			var used_qty			= 	Number(workorder_qty) + Number(deviate_qty) - Number(new_remain_qty);
			var usedqty_percent 	= 	Number(used_qty) * 100/ Number(workorder_qty);
			
			document.form.txt_usedqty.value 				= used_qty.toFixed(3);
			document.form.txt_usedqty_percent.value 		= usedqty_percent.toFixed(2);
			document.form.txt_remainingqty_percent.value 	= remainqty_percent.toFixed(2);
			
			if(used_qty > workorder_qty)
			{
				alert("Entered measurement quantity is exceeded than Work Order quantity..")
				//cleartxt();
				//return false;
				//exit();
			}
			//else
			//{
				document.form.txt_remainingqty.value = new_remain_qty.toFixed(3);
				
				var new_row = document.getElementById("mbookdetail").insertRow(add_row_s);
				new_row.setAttribute("id", "row_" + add_row_s)
				new_row.className = "labelcenter labelhead";
	
				var c1 = new_row.insertCell(0);
				c1.align = "center";c1.style.border = "thin solid lightgray";
				var c2 = new_row.insertCell(1);
				c2.align = "center";c2.style.border = "thin solid lightgray";
				var c3 = new_row.insertCell(2);
				c3.align = "center";c3.style.border = "thin solid lightgray";
				var c4 = new_row.insertCell(3);
				c4.align = "center";c4.style.border = "thin solid lightgray";
				var c5 = new_row.insertCell(4);
				c5.align = "center";c5.style.border = "thin solid lightgray";
				var c6 = new_row.insertCell(5);
				c6.align = "center";c6.style.border = "thin solid lightgray";
				var c7 = new_row.insertCell(6);
				c7.align = "center";c7.style.border = "thin solid lightgray";
				var c8 = new_row.insertCell(7);
				c8.align = "center";c8.style.border = "thin solid lightgray";c8.style.display="none";
				var c9 = new_row.insertCell(8);
				c9.align = "center";c9.style.border = "thin solid lightgray";
			
				//c1.innerText = document.form.txt_boq.value;
				c1.innerText = c1.textContent = document.form.sno.value;
				c2.innerText = c2.textContent = document.form.txt_dec_wk.value;
				c3.innerText = c3.textContent = document.form.txt_no.value;
				c4.innerText = c4.textContent = document.form.txt_l.value;
				c5.innerText = c5.textContent = document.form.txt_b.value;
				c6.innerText = c6.textContent = document.form.txt_d.value;
				c7.innerText = c7.textContent = document.form.txt_ca.value
				c8.innerText = c8.textContent = document.form.remarks.value; 
				c9.innerHTML = c9.textContent = "<input type='button' class='editbtnstyle' name='btn_edit_" + add_row_s + "' id='btn_edit_" + add_row_s + "' title = 'Edit' value='EDIT' onClick=editrow(" + add_row_s + ",'n')><input class='delbtnstyle' type='button'  name='btn_del_" + add_row_s + "'  id='btn_del_" + add_row_s + "' title = 'DELETE' value=' DEL ' onClick=delrow(" + add_row_s + ");get_current_entered_qty();>";
				var hide_values = "";
				hide_values = "<input type='hidden' value='" + c1.innerText + "' name='sno" + add_row_s + "' id='sno" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c2.innerText + "' name='txt_dec_wk" + add_row_s + "' id='txt_dec_wk" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c3.innerText + "' name='txt_no" + add_row_s + "' id='txt_no" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c4.innerText + "' name='txt_l" + add_row_s + "' id='txt_l" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c5.innerText + "' name='txt_b" + add_row_s + "' id='txt_b" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c6.innerText + "' name='txt_d" + add_row_s + "' id='txt_d" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c7.innerText + "' name='txt_ca" + add_row_s + "' id='txt_ca" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c8.innerText + "' name='remarks" + add_row_s + "' id='remarks" + add_row_s + "' >";
				document.getElementById("add_hidden").innerHTML = document.getElementById("add_hidden").innerHTML + hide_values; 
	
				if (document.getElementById("add_set_a1").value == "")
					document.getElementById("add_set_a1").value = add_row_s;
				else
					document.getElementById("add_set_a1").value = document.getElementById("add_set_a1").value + "." + add_row_s; 
					 document.form.sno.value=parseInt(document.form.sno.value)+1;
				add_row_s++;
				cleartxt();
       		//}
        }
        function editrow(rowno, update)
        {   
		//alert(rowno)
			var temp = 0;		
            var total;
            var net_value;
            var edit_row = document.getElementById("mbookdetail").rows[rowno].cells;
			var sno=document.form.sno.value;
			//gdocument.form.txt_dec_wk.focus();
			if(document.form.sno_hide.value=="")
			{
			document.form.sno_hide.value=document.form.sno.value;
			}
            
			if (update == 'y') // transfer controls to table row   // THIS PART IS FOR CLICK OK BUTTON AFTER FOR SET NEW VALUE EDIT
            {	
				valid();
				
				var workorder_qty 		= 	document.form.txt_workorderqty.value;
				var deviateqty_percent 	= 	document.form.txt_deviatedqty_percent.value;
				var deviate_qty 		= 	Number(workorder_qty) * Number(deviateqty_percent) / 100;
				var total_qty 			= 	document.form.txt_totalqty.value;
				var content_area 		= 	0;
				var txt_box_id_str 		= 	document.form.add_set_a1.value.trim();
				var current_ca 			= 	document.form.txt_ca.value;
				if(current_ca == "")
				{
					current_ca = 0;
				}
				content_area = (Number(content_area) + Number(current_ca));
				if(txt_box_id_str != "")
				{
					var txt_box_id = txt_box_id_str.split(".");
					for(var i=0; i<txt_box_id.length; i++)
					{
						if((txt_box_id[i] != 2) && (txt_box_id[i] != rowno))
						{
							var ca 			= 	document.getElementById("txt_ca"+txt_box_id[i]).value;
							content_area 	= 	Number(content_area) + Number(ca);
						}
					}
										
				}
				var remain_qty 			= 	document.form.hid_remainingqty.value;
				var new_remain_qty 		= 	Number(remain_qty) - Number(content_area);
				var remainqty_percent 	= 	Number(new_remain_qty) * 100/ Number(total_qty);
				var used_qty 			= 	Number(workorder_qty) + Number(deviate_qty) - Number(new_remain_qty);
				var usedqty_percent 	= 	Number(used_qty) * 100/ Number(workorder_qty);
				
				document.form.txt_usedqty.value 				= used_qty.toFixed(3);
				document.form.txt_usedqty_percent.value 		= usedqty_percent.toFixed(2);
				document.form.txt_remainingqty_percent.value 	= remainqty_percent.toFixed(2);

				if(used_qty > workorder_qty)
				{
					alert("Entered measurement quantity is exceeded than Work Order quantity..")
					//cleartxt();
					//return false;
					//exit();
				}
				//else
				//{
				document.form.txt_remainingqty.value = new_remain_qty.toFixed(3);
				edit_row[0].innerText = edit_row[0].textContent = document.form.sno.value;
                edit_row[1].innerText = edit_row[1].textContent = document.form.txt_dec_wk.value;
                edit_row[2].innerText = edit_row[2].textContent= document.form.txt_no.value;
                edit_row[3].innerText = edit_row[3].textContent= document.form.txt_l.value;
                edit_row[4].innerText = edit_row[4].textContent= document.form.txt_b.value;
                edit_row[5].innerText = edit_row[5].textContent= document.form.txt_d.value;
                edit_row[6].innerText = edit_row[6].textContent= document.form.txt_ca.value;
                edit_row[7].innerText = edit_row[7].textContent= document.form.remarks.value;

                document.getElementById("sno" + rowno).value = edit_row[0].innerText = edit_row[0].textContent
                document.getElementById("txt_dec_wk" + rowno).value = edit_row[1].innerText = edit_row[1].textContent
                document.getElementById("txt_no" + rowno).value = edit_row[2].innerText = edit_row[2].textContent
                document.getElementById("txt_l" + rowno).value = edit_row[3].innerText = edit_row[3].textContent
                document.getElementById("txt_b" + rowno).value = edit_row[4].innerText = edit_row[4].textContent
                document.getElementById("txt_d" + rowno).value = edit_row[5].innerText = edit_row[5].textContent
                document.getElementById("txt_ca" + rowno).value = edit_row[6].innerText = edit_row[6].textContent
                document.getElementById("remarks" + rowno).value = edit_row[7].innerText = edit_row[7].textContent
				//}
            }//update=='y'

            else  //transfer table row to controls   //// THIS PART IS FOR FIRST TIME CLICK EDIT BUTTON
            {
				document.form.sno.value = edit_row[0].innerText = edit_row[0].textContent
                document.form.txt_dec_wk.value = edit_row[1].innerText = edit_row[1].textContent
                document.form.txt_no.value = edit_row[2].innerText = edit_row[2].textContent
                document.form.txt_l.value = edit_row[3].innerText = edit_row[3].textContent
                document.form.txt_b.value = edit_row[4].innerText = edit_row[4].textContent
                document.form.txt_d.value = edit_row[5].innerText = edit_row[5].textContent
                document.form.txt_ca.value = edit_row[6].innerText = edit_row[6].textContent
                document.form.remarks.value = edit_row[7].innerText = edit_row[7].textContent
            }

            if (prev_edit_row == 0)//first time edit the row  //// THIS PART IS FOR FIRST TIME CLICK EDIT BUTTON
            {
                document.getElementById("row_" + rowno).style.color = "red";
                document.getElementById("btn_edit_" + rowno).className = "editbtnstyle";
                
                document.getElementById("btn_add").outerHTML = "<input type='button' title='Accept' class='updatebtnstyle' name='btn_add' id='btn_add' value=' OK ' onClick=\"editrow(" + rowno + ",'y');\"><input type='button' class='delbtnstyle' title='Reset' name='btn_clr' id='btn_clr' value='RESET' onClick=\"cancel_gen(" + rowno + ",'c')\">";
                prev_edit_row = rowno;
            }
            else
            {	
				//set
				
                if (rowno == prev_edit_row)
                {
                    document.getElementById("row_" + prev_edit_row).style.color = "#770000";
                    document.getElementById("btn_edit_" + rowno).className = "editbtnstyle";
                    document.getElementById("btn_clr").style.display="none";
                    document.getElementById("btn_add").outerHTML = "<input type='button' title='Add' class='addbtnstyle' name='btn_add' id='btn_add' value='ADD' onClick='addrow();'>";
                    prev_edit_row = 0;
                    cleartxt();
                }

                else
                {   document.getElementById("sno").value=document.getElementById("sno_hide").value;
                    document.getElementById("sno_hide").value="";
                    document.getElementById("row_" + prev_edit_row).style.color = "#770000";
                    document.getElementById("btn_edit_" + prev_edit_row).className = "editbtnstyle";
                    document.getElementById("row_" + rowno).style.color = "red";
                    document.getElementById("btn_edit_" + rowno).className = "delbtnstyle";
                    document.getElementById("btn_add").outerHTML = "<input type='button' title='Accept' claas='updatebtnstyle' name='btn_add' id='btn_add' value=' OK ' onClick=\"editrow(" + rowno + ",'y');\">";
                    prev_edit_row = rowno;
                }document.getElementById("sno").value=document.getElementById("sno_hide").value;document.getElementById("sno_hide").value="";
            }
        }

        function delrow(rownum)
        {	
            if(rownum == 3) { document.getElementById("hid_deleteflag").value = "d"; }
            var no=document.getElementById("sno_hide").value=document.getElementById("sno").value;
            var src_row = (rownum + 1)
            var tar_row = rownum
            var noofadd = (add_row_s - 1)
			
            for (x = rownum; x < noofadd; x++)
            {	
				document.getElementById("sno" + tar_row).value= document.getElementById("sno" + src_row).value;
                document.getElementById("txt_dec_wk" + tar_row).value = document.getElementById("txt_dec_wk" + src_row).value
                document.getElementById("txt_no" + tar_row).value = document.getElementById("txt_no" + src_row).value
                document.getElementById("txt_l" + tar_row).value = document.getElementById("txt_l" + src_row).value
                document.getElementById("txt_b" + tar_row).value = document.getElementById("txt_b" + src_row).value
                document.getElementById("txt_d" + tar_row).value = document.getElementById("txt_d" + src_row).value
                document.getElementById("txt_ca" + tar_row).value = document.getElementById("txt_ca" + src_row).value;
                document.getElementById("remarks" + tar_row).value = document.getElementById("remarks" + src_row).value;
                tar_row++;
                src_row++;
                var trow = document.getElementById("mbookdetail").rows[x].cells;
                var srow = document.getElementById("mbookdetail").rows[x + 1].cells;
				trow[0].innerText = trow[0].textContent = srow[0].innerText = srow[0].textContent 
                trow[1].innerText = trow[1].textContent  = srow[1].innerText = srow[1].textContent
                trow[2].innerText = trow[2].textContent  = srow[2].innerText = srow[2].textContent
                trow[3].innerText = trow[3].textContent  = srow[3].innerText = srow[3].textContent
                trow[4].innerText = trow[4].textContent  = srow[4].innerText = srow[4].textContent
                trow[5].innerText = trow[5].textContent  = srow[5].innerText = srow[5].textContent
                trow[6].innerText = trow[6].textContent  = srow[6].innerText = srow[6].textContent
                trow[7].innerText = trow[7].textContent  = srow[7].innerText = srow[7].textContent
            }
			document.getElementById("sno" + tar_row).outerHTML = ""
            document.getElementById("txt_dec_wk" + tar_row).outerHTML = ""
            document.getElementById("txt_no" + tar_row).outerHTML = ""
            document.getElementById("txt_l" + tar_row).outerHTML = ""
            document.getElementById("txt_b" + tar_row).outerHTML = ""
            document.getElementById("txt_d" + tar_row).outerHTML = ""
            document.getElementById("txt_ca" + tar_row).outerHTML = ""
            document.getElementById("remarks" + tar_row).outerHTML = ""

            document.getElementById('mbookdetail').deleteRow(noofadd)
            document.getElementById("add_set_a1").value = "";

            for (x = 2; x < noofadd; x++)
            {
                if (document.getElementById("add_set_a1").value == "")
                    {document.getElementById("add_set_a1").value = x;
					document.getElementById("sno").value=x-1;
					}
                else
				{
                    document.getElementById("add_set_a1").value += ("." + x);
					document.getElementById("sno").value=x-1;
				}
            }
		
            add_row_s = noofadd++; 
			for(i=1;i<no-1;i++)
			{
			var trow = document.getElementById("mbookdetail").rows[i+2].cells; 
			trow[0].innerText = trow[0].textContent = i;
			}
			document.getElementById("sno_hide").value="";
			//var txt_box_id_str = document.form.add_set_a1.value;
			//alert(txt_box_id_str);
        }
	function cancel_gen(rowno,can)
        {

            /*var edit_row = document.getElementById("mbookstruct").rows[rowno].cells;
			document.form.sno_st.value = edit_row[0].innerText = edit_row[0].textContent*/
            document.getElementById("txt_dec_wk").value = "";
            document.getElementById("txt_no").value = "";
            document.getElementById("txt_l").value = "";
            document.getElementById("txt_b").value = "";
            document.getElementById("txt_d").value = "";
            document.getElementById("txt_ca").value = "";
           
            document.getElementById("row_" + rowno).style.color = "#3A2D2C";
            document.getElementById("btn_edit_" + rowno).className = "editbtnstyle";
            document.getElementById("btn_clr").style.display="none";
            document.getElementById("btn_add").outerHTML = "<input type='button' class='addbtnstyle' name='btn_add' id='btn_add' value='ADD' onClick='addrow()'>";
            document.getElementById("sno").value=document.getElementById("sno_hide").value;document.getElementById("sno_hide").value="";
        }    	
        function cleartxt()
        {
			//document.getElementById("sno").value = "";
            document.getElementById("txt_dec_wk").value = "";
            document.getElementById("txt_no").value = "";
            document.getElementById("txt_l").value = "";
            document.getElementById("txt_b").value = "";
            document.getElementById("txt_d").value = "";
            document.getElementById("txt_ca").value = "";
            //document.getElementById("remarks").value = "";
        }

        function contentorarea()
        {
            var no = alltrim(document.form.txt_no.value);
            var l = alltrim(document.form.txt_l.value);
            var b = alltrim(document.form.txt_b.value);
            var d = alltrim(document.form.txt_d.value);
			if((no == '') && (l == '') && (b == '') && (d == ''))
			{
				var ca = "";
				document.form.txt_ca.value = ca;
			}
			else
			{
				if (no != '')
				{
					no = Number(no);
				}
				else
				{
					no = 1;
				}
				if (l != '')
				{
					l = Number(l);
				}
				else
				{
					l = 1;
				}
				if (b != '')
				{
					b = Number(b);
				}
				else
				{
					b = 1;
				}
				if (d != '')
				{
					d = Number(d);
				}
				else
				{
					d = 1;
				}
				//alert(no);
				//alert(l);
				//alert(b);
				//alert(d);
				
				ca = (Number(no) * Number(l) * Number(b) * Number(d));
				//alert((parseFloat(no) * parseFloat(l) * parseFloat(b) * parseFloat(d)));
            	//var ca =Math.round(parseFloat(no) * parseFloat(l) * parseFloat(b) * parseFloat(d));
				//alert(ca);
				//ca = ca.;
				//Math.round(num * 100) / 100
				//alert(ca)
				//alert(ca.toFixed(3))
            	document.form.txt_ca.value = ca.toFixed(3);
			}
        }
		
		function isNumber(evt) 
                {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) 
                        {
				return false;
			}
			return true;
		}
                function isNumber_deduct(evt) 
                {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57) && (charCode != 45)) 
                        {
				return false;
			}
			return true;
		}
		
		 function calculate()
		 {
		 var dia = alltrim(document.form.sel_dia_mt.value);
		 var no  = alltrim(document.form.txt_no_mt.value);
         var l   = alltrim(document.form.txt_l_mt.value);
		 var result;
		 if((no == "") && (dia == ""))
		 {
		 	document.form.txt_ca_mt.value = "";
			result = "";
		 }
		 /*if(no == "")
		 {no=1;}
		 if(l=="")
		 {l=1;}*/
		 else
		 {
		 	if(no == "")
			{
				no= 1;
			}
			else
			{
				no = no;
			}
			if(l == "")
			{
				l = 1;
			}
			else
			{
				l = l;
			}
		 	 result = Math.round((parseFloat(no) * parseFloat(l))*1000)/1000;
			 //result = Number(no) * Number(l);
			// result = parseFloat(result.toFixed(3));
			 document.form.txt_ca_mt.value = result;
		 }
		 if(dia=="")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";
		 document.form.txt_36.value = "";
		 }
		 if(dia=="8")
		 {document.form.txt_8.value = result;
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";
		 document.form.txt_36.value = "";
		 }
		  if(dia=="10")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = result;
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";
		 document.form.txt_36.value = "";}
		 if(dia=="12")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = result;
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";
		 document.form.txt_36.value = "";}
		 if(dia=="16")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = result;
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";
		 document.form.txt_36.value = "";}
		 if(dia=="20")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = result;
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";
		 document.form.txt_36.value = "";}
		 if(dia=="25")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = result;
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";
		 document.form.txt_36.value = "";}
		 if(dia=="28")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = result;
		 document.form.txt_32.value = "";
		 document.form.txt_36.value = "";}
		 if(dia=="32")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = result;
		 document.form.txt_36.value = "";}
		 if(dia=="36")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";
		 document.form.txt_36.value = result;}
		 }
		 
		/* add row for steel */ 
		var add_row_s = 3;
        var prev_edit_row = 0
        function addrow_mt()
        {	
			valid();
			var workorder_qty 		= 	document.form.txt_workorderqty.value;
			var deviateqty_percent 	= 	document.form.txt_deviatedqty_percent.value;
			var deviate_qty 		= 	Number(workorder_qty) * Number(deviateqty_percent) / 100;
			var total_qty 			= 	document.form.txt_totalqty.value;
			var content_area 		= 	0;
			var unit_weight 		= 	0;
			var txt_box_id_str 		= 	document.form.add_set_a2.value.trim();
			var current_ca_in_kgs 	= 	document.form.txt_ca_mt.value;
			var current_dia 		= 	document.form.sel_dia_mt.value;
			if((current_dia != "") && (current_dia != 0))
			{
				if(current_dia == 8) { unit_weight = 0.395; }
				if(current_dia == 10) { unit_weight = 0.617; }
				if(current_dia == 12) { unit_weight = 0.888; }
				if(current_dia == 16) { unit_weight = 1.580; }
				if(current_dia == 20) { unit_weight = 2.470; }
				if(current_dia == 25) { unit_weight = 3.860; }
				if(current_dia == 28) { unit_weight = 4.830; }
				if(current_dia == 32) { unit_weight = 6.313; }
				if(current_dia == 36) { unit_weight = 8.000; }
			}
			if(current_ca_in_kgs == "")
			{
				current_ca_in_kgs = 0;
			}
			var current_ca = (Number(current_ca_in_kgs) * Number(unit_weight)/1000);
			content_area = (Number(content_area) + Number(current_ca));
			unit_weight = 0;
			if(txt_box_id_str != "")
			{
				var txt_box_id = txt_box_id_str.split(".");
				for(var i=0; i<txt_box_id.length; i++)
				{
					if(txt_box_id[i] != 2)
					{
						var dia = document.getElementById("sel_dia_mt"+txt_box_id[i]).value;
						if((dia != "") && (dia != 0))
						{
							if(dia == 8) { unit_weight = 0.395; }
							if(dia == 10) { unit_weight = 0.617; }
							if(dia == 12) { unit_weight = 0.888; }
							if(dia == 16) { unit_weight = 1.580; }
							if(dia == 20) { unit_weight = 2.470; }
							if(dia == 25) { unit_weight = 3.860; }
							if(dia == 28) { unit_weight = 4.830; }
							if(dia == 32) { unit_weight = 6.313; }
							if(dia == 36) { unit_weight = 8.000; }
						}
						var ca_in_kgs = document.getElementById("txt_ca_mt"+txt_box_id[i]).value;
						var ca = (Number(ca_in_kgs) * Number(unit_weight)/1000);
						content_area = Number(content_area) + Number(ca);
					}
				}
			}
			var remain_qty 			= 	document.form.hid_remainingqty.value;
			var new_remain_qty 		= 	Number(remain_qty) - Number(content_area);
			var remainqty_percent 	= 	Number(new_remain_qty) * 100/ Number(total_qty);
			var used_qty 			= 	Number(workorder_qty) + Number(deviate_qty) - Number(new_remain_qty);
			var usedqty_percent 	= 	Number(used_qty) * 100/ Number(workorder_qty);
			
			document.form.txt_usedqty.value 				= used_qty.toFixed(3);
			document.form.txt_usedqty_percent.value 		= usedqty_percent.toFixed(2);
			document.form.txt_remainingqty_percent.value 	= remainqty_percent.toFixed(2);
			if(used_qty > workorder_qty)
			{
				alert("Entered measurement quantity is exceeded than Work Order quantity..")
				//cleartxt_mt();
				//return false;
				//exit();
							//exit();
			}
			//else
			//{
				document.form.txt_remainingqty.value = new_remain_qty.toFixed(3);
				var new_row = document.getElementById("mbookmetal").insertRow(add_row_s);
				new_row.setAttribute("id", "row_" + add_row_s)
				new_row.className = "labelcenter labelhead";
	
				var c1 = new_row.insertCell(0);
				c1.align = "center";c1.style.border = "thin solid lightgray";
				var c2 = new_row.insertCell(1);
				c2.align = "center";c2.style.border = "thin solid lightgray";
				var c3 = new_row.insertCell(2);
				c3.align = "center";c3.style.border = "thin solid lightgray";
				var c4 = new_row.insertCell(3);
				c4.align = "center";c4.style.border = "thin solid lightgray";
				var c5 = new_row.insertCell(4);
				c5.align = "center";c5.style.border = "thin solid lightgray";
				var c6 = new_row.insertCell(5);
				c6.align = "center";c6.style.border = "thin solid lightgray";
				var c7 = new_row.insertCell(6);
				c7.align = "center";c7.style.border = "thin solid lightgray";
				var c8 = new_row.insertCell(7);
				c8.align = "center";c8.style.border = "thin solid lightgray";
				var c9 = new_row.insertCell(8);
				c9.align = "center";c9.style.border = "thin solid lightgray";
				var c10 = new_row.insertCell(9);
				c10.align = "center";c10.style.border = "thin solid lightgray";
				var c11 = new_row.insertCell(10);
				c11.align = "center";c11.style.border = "thin solid lightgray";
				var c12 = new_row.insertCell(11);
				c12.align = "center";c12.style.border = "thin solid lightgray";
				var c13 = new_row.insertCell(12);
				c13.align = "center";c13.style.border = "thin solid lightgray";
				var c16 = new_row.insertCell(13);
				c16.align = "center";c16.style.border = "thin solid lightgray";
				var c14 = new_row.insertCell(14);
				c14.align = "center";c14.style.border = "thin solid lightgray";
				var c15 = new_row.insertCell(15);
				c15.align = "center";c15.style.border = "thin solid lightgray";
				c14.style.display="none";
				//c1.innerText = document.form.txt_boq.value;
				c1.innerText = c1.textContent = document.form.sno_mt.value;
				c2.innerText = c2.textContent = document.form.txt_dec_wk_mt.value;
				c3.innerText = c3.textContent = document.form.sel_dia_mt.value;
				c4.innerText = c4.textContent = document.form.txt_no_mt.value;
				c5.innerText = c5.textContent = document.form.txt_l_mt.value;
				c6.innerText = c6.textContent = document.form.txt_8.value;
				c7.innerText = c7.textContent = document.form.txt_10.value;
				c8.innerText = c8.textContent = document.form.txt_12.value;
				c9.innerText = c9.textContent = document.form.txt_16.value;
				c10.innerText = c10.textContent = document.form.txt_20.value;
				c11.innerText = c11.textContent = document.form.txt_25.value;
				c12.innerText = c12.textContent = document.form.txt_28.value;
				c13.innerText = c13.textContent = document.form.txt_32.value;
				c16.innerText = c16.textContent = document.form.txt_36.value;
				c14.innerText = c14.textContent = document.form.txt_ca_mt.value;
				c15.innerHTML = c15.textContent = "<input type='button' class='editbtnstyle' name='btn_mt_edit_" + add_row_s + "' id='btn_mt_edit_" + add_row_s + "' value='EDIT' title='Edit' style='width: 40px' onClick=editrow_mt(" + add_row_s + ",'n')><input type='button' class='delbtnstyle' style='width: 40px'  name='btn_mt_del_" + add_row_s + "'  id='btn_mt_del_" + add_row_s + "' value='X' title='Cancel' onClick=delrow_mt(" + add_row_s + ");get_current_entered_qty();>";
				var hide_values = "";
				hide_values = "<input type='hidden' value='" + c1.innerText + "' name='sno_mt" + add_row_s + "' id='sno_mt" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c2.innerText + "' name='txt_dec_wk_mt" + add_row_s + "' id='txt_dec_wk_mt" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c3.innerText + "' name='sel_dia_mt" + add_row_s + "' id='sel_dia_mt" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c4.innerText + "' name='txt_no_mt" + add_row_s + "' id='txt_no_mt" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c5.innerText + "' name='txt_l_mt" + add_row_s + "' id='txt_l_mt" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c6.innerText + "' name='txt_8" + add_row_s + "' id='txt_8" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c7.innerText + "' name='txt_10" + add_row_s + "' id='txt_10" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c8.innerText + "' name='txt_12" + add_row_s + "' id='txt_12" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c9.innerText + "' name='txt_16" + add_row_s + "' id='txt_16" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c10.innerText + "' name='txt_20" + add_row_s + "' id='txt_20" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c11.innerText + "' name='txt_25" + add_row_s + "' id='txt_25" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c12.innerText + "' name='txt_28" + add_row_s + "' id='txt_28" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c13.innerText + "' name='txt_32" + add_row_s + "' id='txt_32" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c16.innerText + "' name='txt_36" + add_row_s + "' id='txt_36" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c14.innerText + "' name='txt_ca_mt" + add_row_s + "' id='txt_ca_mt" + add_row_s + "' >";
				document.getElementById("add_hidden_mt").innerHTML = document.getElementById("add_hidden_mt").innerHTML + hide_values; 
	
				if (document.getElementById("add_set_a2").value == "")
					document.getElementById("add_set_a2").value = add_row_s;
				else
					document.getElementById("add_set_a2").value = document.getElementById("add_set_a2").value + "." + add_row_s; 
					 document.form.sno_mt.value=parseInt(document.form.sno_mt.value)+1;
				calculate();	
				add_row_s++;
				
				cleartxt_mt();
			//}
       
        }
	function cleartxt_mt()
            {
				document.form.txt_dec_wk_mt.value="";
                document.form.sel_dia_mt.value="";
                document.form.txt_no_mt.value="";
                document.form.txt_l_mt.value="";
                document.form.txt_8.value="";
                document.form.txt_10.value="";
				document.form.txt_12.value="";
				document.form.txt_16.value="";
				document.form.txt_20.value="";
				document.form.txt_25.value="";
				document.form.txt_28.value="";
				document.form.txt_32.value="";
				document.form.txt_36.value="";
            }
		
		function editrow_mt(rowno, update)
        {   
			
            var total;
            var net_value;
            var edit_row = document.getElementById("mbookmetal").rows[rowno].cells;
            var sno=document.form.sno_mt.value;
            if(document.form.sno_hide.value=="")
            {
                document.form.sno_hide.value=document.form.sno_mt.value;
            }

            if (update == 'y') // transfer controls to table row
            {	
				valid();
				var workorder_qty 		= 	document.form.txt_workorderqty.value;
				var deviateqty_percent 	= 	document.form.txt_deviatedqty_percent.value;
				var deviate_qty 		= 	Number(workorder_qty) * Number(deviateqty_percent) / 100;
				var total_qty 			= 	document.form.txt_totalqty.value;
				var content_area 		= 	0;
				var unit_weight 		= 	0;
				var txt_box_id_str 		= 	document.form.add_set_a2.value.trim();
				var current_ca_in_kgs 	= 	document.form.txt_ca_mt.value;
				var current_dia 		= 	document.form.sel_dia_mt.value;
				if((current_dia != "") && (current_dia != 0))
				{
					if(current_dia == 8) { unit_weight = 0.395; }
					if(current_dia == 10) { unit_weight = 0.617; }
					if(current_dia == 12) { unit_weight = 0.888; }
					if(current_dia == 16) { unit_weight = 1.580; }
					if(current_dia == 20) { unit_weight = 2.470; }
					if(current_dia == 25) { unit_weight = 3.860; }
					if(current_dia == 28) { unit_weight = 4.830; }
					if(current_dia == 32) { unit_weight = 6.313; }
					if(current_dia == 36) { unit_weight = 8.000; }
				}
				if(current_ca_in_kgs == "")
				{
					current_ca_in_kgs = 0;
				}
				var current_ca = (Number(current_ca_in_kgs) * Number(unit_weight)/1000);
				content_area = (Number(content_area) + Number(current_ca));
				unit_weight = 0;
				if(txt_box_id_str != "")
				{
					var txt_box_id = txt_box_id_str.split(".");
					for(var i=0; i<txt_box_id.length; i++)
					{
						if((txt_box_id[i] != 2) && (txt_box_id[i] != rowno))
						{
							var dia = document.getElementById("sel_dia_mt"+txt_box_id[i]).value;
							if((dia != "") && (dia != 0))
							{
								if(dia == 8) { unit_weight = 0.395; }
								if(dia == 10) { unit_weight = 0.617; }
								if(dia == 12) { unit_weight = 0.888; }
								if(dia == 16) { unit_weight = 1.580; }
								if(dia == 20) { unit_weight = 2.470; }
								if(dia == 25) { unit_weight = 3.860; }
								if(dia == 28) { unit_weight = 4.830; }
								if(dia == 32) { unit_weight = 6.313; }
								if(dia == 36) { unit_weight = 8.000; }
							}
							var ca_in_kgs = document.getElementById("txt_ca_mt"+txt_box_id[i]).value;
							var ca = (Number(ca_in_kgs) * Number(unit_weight)/1000);
							content_area = Number(content_area) + Number(ca);
						}
					}
				}
				var remain_qty 			= 	document.form.hid_remainingqty.value;
				var new_remain_qty 		= 	Number(remain_qty) - Number(content_area);
				var remainqty_percent 	= 	Number(new_remain_qty) * 100/ Number(total_qty);
				var used_qty 			= 	Number(workorder_qty) + Number(deviate_qty) - Number(new_remain_qty);
				var usedqty_percent 	= 	Number(used_qty) * 100/ Number(workorder_qty);
				document.form.txt_usedqty.value 				= used_qty.toFixed(3);
				document.form.txt_usedqty_percent.value 		= usedqty_percent.toFixed(2);
				document.form.txt_remainingqty_percent.value 	= remainqty_percent.toFixed(2);
				if(used_qty > workorder_qty)
				{
					alert("Entered measurement quantity is exceeded than Work Order quantity..")
					//cleartxt_mt();
					//return false;
					//exit();
								//exit();
				}
				//else
				//{ 
					document.form.txt_remainingqty.value = new_remain_qty.toFixed(3);
					edit_row[0].innerText = edit_row[0].textContent = document.form.sno_mt.value;
					edit_row[1].innerText = edit_row[1].textContent = document.form.txt_dec_wk_mt.value;
					edit_row[2].innerText = edit_row[2].textContent= document.form.sel_dia_mt.value;
					edit_row[3].innerText = edit_row[3].textContent= document.form.txt_no_mt.value;
					edit_row[4].innerText = edit_row[4].textContent= document.form.txt_l_mt.value;
					edit_row[5].innerText = edit_row[5].textContent= document.form.txt_8.value;
					edit_row[6].innerText = edit_row[6].textContent= document.form.txt_10.value;
					edit_row[7].innerText = edit_row[7].textContent= document.form.txt_12.value;
					edit_row[8].innerText = edit_row[8].textContent= document.form.txt_16.value;
					edit_row[9].innerText = edit_row[9].textContent= document.form.txt_20.value;
					edit_row[10].innerText = edit_row[10].textContent= document.form.txt_25.value;
					edit_row[11].innerText = edit_row[11].textContent= document.form.txt_28.value;
					edit_row[12].innerText = edit_row[12].textContent= document.form.txt_32.value;
					edit_row[13].innerText = edit_row[13].textContent= document.form.txt_36.value;
					edit_row[14].innerText = edit_row[14].textContent= document.form.txt_ca_mt.value;
					
					document.getElementById("sno_mt" + rowno).value = edit_row[0].innerText = edit_row[0].textContent
					document.getElementById("txt_dec_wk_mt" + rowno).value = edit_row[1].innerText = edit_row[1].textContent
					document.getElementById("sel_dia_mt" + rowno).value = edit_row[2].innerText = edit_row[2].textContent
					document.getElementById("txt_no_mt" + rowno).value = edit_row[3].innerText = edit_row[3].textContent
					document.getElementById("txt_l_mt" + rowno).value = edit_row[4].innerText = edit_row[4].textContent
					document.getElementById("txt_8" + rowno).value = edit_row[5].innerText = edit_row[5].textContent
					document.getElementById("txt_10" + rowno).value = edit_row[6].innerText = edit_row[6].textContent
					document.getElementById("txt_12" + rowno).value = edit_row[7].innerText = edit_row[7].textContent
					document.getElementById("txt_16" + rowno).value = edit_row[8].innerText = edit_row[8].textContent
					document.getElementById("txt_20" + rowno).value = edit_row[9].innerText = edit_row[9].textContent
					document.getElementById("txt_25" + rowno).value = edit_row[10].innerText = edit_row[10].textContent
					document.getElementById("txt_28" + rowno).value = edit_row[11].innerText = edit_row[11].textContent
					document.getElementById("txt_32" + rowno).value = edit_row[12].innerText = edit_row[12].textContent
					document.getElementById("txt_36" + rowno).value = edit_row[13].innerText = edit_row[13].textContent
					document.getElementById("txt_ca_mt" + rowno).value = edit_row[14].innerText = edit_row[14].textContent
				//}
            }//update=='y'

            else  //transfer table row to controls
            {
				document.form.sno_mt.value = edit_row[0].innerText = edit_row[0].textContent
                document.form.txt_dec_wk_mt.value = edit_row[1].innerText = edit_row[1].textContent
                document.form.sel_dia_mt.value = edit_row[2].innerText = edit_row[2].textContent
                document.form.txt_no_mt.value = edit_row[3].innerText = edit_row[3].textContent
                document.form.txt_l_mt.value = edit_row[4].innerText = edit_row[4].textContent
                document.form.txt_8.value = edit_row[5].innerText = edit_row[5].textContent
                document.form.txt_10.value = edit_row[6].innerText = edit_row[6].textContent
                document.form.txt_12.value = edit_row[7].innerText = edit_row[7].textContent
				document.form.txt_16.value = edit_row[8].innerText = edit_row[8].textContent
				document.form.txt_20.value = edit_row[9].innerText = edit_row[9].textContent
				document.form.txt_25.value = edit_row[10].innerText = edit_row[10].textContent
				document.form.txt_28.value = edit_row[11].innerText = edit_row[11].textContent
				document.form.txt_32.value = edit_row[12].innerText = edit_row[12].textContent
				document.form.txt_36.value = edit_row[13].innerText = edit_row[13].textContent
				document.form.txt_ca_mt.value = edit_row[14].innerText = edit_row[14].textContent
            }

            if (prev_edit_row == 0)//first time edit the row
            {
                document.getElementById("row_" + rowno).style.color = "red";
                document.getElementById("btn_mt_edit_" + rowno).className = "editbtnstyle";
                document.getElementById("btn_mt_add").outerHTML = "<input type='button' class='updatebtnstyle' title='Accept' style='width: 40px' name='btn_mt_add' id='btn_mt_add' value=' OK ' onClick=\"editrow_mt(" + rowno + ",'y');\"><input type='button' title='Reset' class='delbtnstyle' style='width: 40px' name='btn_mt_clr' id='btn_mt_clr' value='RESET' onClick=\"cancel_mt(" + rowno + ",'c')\">";
                prev_edit_row = rowno;
            }
            else
            {	
				//set
				
                if (rowno == prev_edit_row)
                {
                    document.getElementById("row_" + prev_edit_row).style.color = "#770000";
                    document.getElementById("btn_mt_edit_" + rowno).className = "editbtnstyle";
                    document.getElementById("btn_mt_add").outerHTML = "<input type='button' class='addbtnstyle' style='width: 80px' name='btn_mt_add' id='btn_mt_add' value='ADD' onClick='addrow_mt();'>";
                    document.getElementById("btn_mt_clr").style.display="none";
                    prev_edit_row = 0;
					
                    cleartxt_mt();
                }

                else
                {	
                    document.getElementById("sno_mt").value=document.getElementById("sno_hide").value;
                    document.getElementById("sno_hide").value="";
                    document.getElementById("row_" + prev_edit_row).style.color = "#770000";
                    document.getElementById("btn_edit_" + prev_edit_row).className  = "editbtnstyle";
                    document.getElementById("row_" + rowno).style.color = "red";
                    document.getElementById("btn_mt_edit_" + rowno).className  = "editbtnstyle";
                    document.getElementById("btn_mt_add").outerHTML = "<input type='button' title='Accept' name='btn_mt_add' class='updatebtnstyle' style='width: 80px' id='btn_mt_add' value=' OK ' onClick=\"editrow_mt(" + rowno + ",'y');\">";
                    prev_edit_row = rowno;
                }document.getElementById("sno_mt").value = document.getElementById("sno_hide").value;document.getElementById("sno_hide").value="";
            }
        }
		
		
	function delrow_mt(rownum)
         {	
            if(rownum == 3) { document.getElementById("hid_deleteflag").value = "d"; }
            var no=document.getElementById("sno_hide").value=document.getElementById("sno_mt").value;
            var src_row = (rownum + 1)
            var tar_row = rownum
            var noofadd = (add_row_s - 1)
			
            for (x = rownum; x < noofadd; x++)
            {	
				document.getElementById("sno_mt" + tar_row).value= document.getElementById("sno_mt" + src_row).value;
                document.getElementById("txt_dec_wk_mt" + tar_row).value = document.getElementById("txt_dec_wk_mt" + src_row).value
                document.getElementById("sel_dia_mt" + tar_row).value = document.getElementById("sel_dia_mt" + src_row).value
                document.getElementById("txt_no_mt" + tar_row).value = document.getElementById("txt_no_mt" + src_row).value
                document.getElementById("txt_l_mt" + tar_row).value = document.getElementById("txt_l_mt" + src_row).value
                document.getElementById("txt_8" + tar_row).value = document.getElementById("txt_8" + src_row).value
                document.getElementById("txt_10" + tar_row).value = document.getElementById("txt_10" + src_row).value;
                document.getElementById("txt_12" + tar_row).value = document.getElementById("txt_12" + src_row).value;
				document.getElementById("txt_16" + tar_row).value = document.getElementById("txt_16" + src_row).value;
                document.getElementById("txt_20" + tar_row).value = document.getElementById("txt_20" + src_row).value;
				document.getElementById("txt_25" + tar_row).value = document.getElementById("txt_25" + src_row).value;
                document.getElementById("txt_28" + tar_row).value = document.getElementById("txt_28" + src_row).value;
				document.getElementById("txt_32" + tar_row).value = document.getElementById("txt_32" + src_row).value;
				document.getElementById("txt_36" + tar_row).value = document.getElementById("txt_36" + src_row).value;
                tar_row++;
                src_row++;
                var trow = document.getElementById("mbookmetal").rows[x].cells;
                var srow = document.getElementById("mbookmetal").rows[x + 1].cells;
				trow[0].innerText = trow[0].textContent = srow[0].innerText = srow[0].textContent 
                trow[1].innerText = trow[1].textContent  = srow[1].innerText = srow[1].textContent
                trow[2].innerText = trow[2].textContent  = srow[2].innerText = srow[2].textContent
                trow[3].innerText = trow[3].textContent  = srow[3].innerText = srow[3].textContent
                trow[4].innerText = trow[4].textContent  = srow[4].innerText = srow[4].textContent
                trow[5].innerText = trow[5].textContent  = srow[5].innerText = srow[5].textContent
                trow[6].innerText = trow[6].textContent  = srow[6].innerText = srow[6].textContent
                trow[7].innerText = trow[7].textContent  = srow[7].innerText = srow[7].textContent
				trow[8].innerText = trow[8].textContent  = srow[8].innerText = srow[8].textContent
                trow[9].innerText = trow[9].textContent  = srow[9].innerText = srow[9].textContent
                trow[10].innerText = trow[10].textContent  = srow[10].innerText = srow[10].textContent
				trow[11].innerText = trow[11].textContent  = srow[11].innerText = srow[11].textContent
                trow[12].innerText = trow[12].textContent  = srow[12].innerText = srow[12].textContent
                trow[13].innerText = trow[13].textContent  = srow[13].innerText = srow[13].textContent
				trow[14].innerText = trow[14].textContent  = srow[14].innerText = srow[14].textContent
                            }
            document.getElementById("sno_mt" + tar_row).outerHTML = ""
            document.getElementById("txt_dec_wk_mt" + tar_row).outerHTML = ""
            document.getElementById("sel_dia_mt" + tar_row).outerHTML = ""
            document.getElementById("txt_no_mt" + tar_row).outerHTML = ""
            document.getElementById("txt_l_mt" + tar_row).outerHTML = ""
            document.getElementById("txt_8" + tar_row).outerHTML = ""
            document.getElementById("txt_10" + tar_row).outerHTML = ""
            document.getElementById("txt_12" + tar_row).outerHTML = ""
            document.getElementById("txt_16" + tar_row).outerHTML = ""
            document.getElementById("txt_20" + tar_row).outerHTML = ""
            document.getElementById("txt_25" + tar_row).outerHTML = ""
            document.getElementById("txt_28" + tar_row).outerHTML = ""
            document.getElementById("txt_32" + tar_row).outerHTML = ""
			document.getElementById("txt_36" + tar_row).outerHTML = ""
            document.getElementById('mbookmetal').deleteRow(noofadd)
            document.getElementById("add_set_a2").value = "";

            for (x = 2; x < noofadd; x++)
            {
                if (document.getElementById("add_set_a2").value == "")
                    {document.getElementById("add_set_a2").value = x;
					document.getElementById("sno_mt").value=x-1;
					}
                else
				{
                    document.getElementById("add_set_a2").value += ("." + x);
					document.getElementById("sno_mt").value=x-1;
				}
            }
		
            add_row_s = noofadd++; 
			for(i=1;i<no-1;i++)
			{
			var trow = document.getElementById("mbookmetal").rows[i+2].cells; 
			trow[0].innerText = trow[0].textContent = i;
			}
			document.getElementById("sno_hide").value="";
        }
        
        function cancel_mt(rowno,can)
        {

            /*var edit_row = document.getElementById("mbookstruct").rows[rowno].cells;
			document.form.sno_st.value = edit_row[0].innerText = edit_row[0].textContent*/
            document.form.txt_dec_wk_mt.value="";
                document.form.sel_dia_mt.value="";
                document.form.txt_no_mt.value="";
                document.form.txt_l_mt.value="";
                document.form.txt_8.value="";
                document.form.txt_10.value="";
				document.form.txt_12.value="";
				document.form.txt_16.value="";
				document.form.txt_20.value="";
				document.form.txt_25.value="";
				document.form.txt_28.value="";
				document.form.txt_32.value="";
				document.form.txt_36.value="";
            document.getElementById("row_" + rowno).style.color = "#3A2D2C";
            document.getElementById("btn_mt_edit_" + rowno).className = "editbtnstyle";
            document.getElementById("btn_mt_clr").style.display="none";
            document.getElementById("btn_mt_add").outerHTML = "<input type='button' class='addbtnstyle' style='width: 40px' name='btn_mt_add' id='btn_mt_add' value='Add' onClick='addrow_mt()'>";
            document.getElementById("sno_mt").value=document.getElementById("sno_hide").value;document.getElementById("sno_hide").value="";
        }   

var add_row_s = 3;
var prev_edit_row = 0;


// THIS FOR STRUCTURAL STEEL WHICH STARTS HERE.....

function addrow_st()
        {	
            valid();
			var workorder_qty 		= 	document.form.txt_workorderqty.value;
			var deviateqty_percent 	= 	document.form.txt_deviatedqty_percent.value;
			var deviate_qty 		= 	Number(workorder_qty) * Number(deviateqty_percent) / 100;
			var total_qty 			= 	document.form.txt_totalqty.value;
			var content_area 		= 	0;
			var txt_box_id_str 		= 	document.form.add_set_a3.value.trim();
			var current_ca 			= 	document.form.txt_ca_st.value;
			if(current_ca == "")
			{
				current_ca = 0;
			}
			content_area = (Number(content_area) + (Number(current_ca)/1000));
			if(txt_box_id_str != "")
			{
				var txt_box_id = txt_box_id_str.split(".");
				for(var i=0; i<txt_box_id.length; i++)
				{
					if(txt_box_id[i] != 2)
					{
						var ca = document.getElementById("txt_ca_st"+txt_box_id[i]).value;
						content_area = Number(content_area) + (Number(ca)/1000);
					}
				}
												
			}
			var remain_qty 			= 	document.form.hid_remainingqty.value;
			var new_remain_qty 		= 	Number(remain_qty) - Number(content_area);
			var remainqty_percent 	= 	Number(new_remain_qty) * 100/ Number(total_qty);
			var used_qty 			= 	Number(workorder_qty) + Number(deviate_qty) - Number(new_remain_qty);
			var usedqty_percent 	= 	Number(used_qty) * 100/ Number(workorder_qty);
			document.form.txt_usedqty.value 				= used_qty.toFixed(3);
			document.form.txt_usedqty_percent.value 		= usedqty_percent.toFixed(2);
			document.form.txt_remainingqty_percent.value 	= remainqty_percent.toFixed(2);
			if(used_qty > workorder_qty)
			{
				alert("Entered measurement quantity is exceeded than Work Order quantity..")
				//cleartxt_st();
				//return false;
				//exit();
			}
			//else
			//{
				document.form.txt_remainingqty.value = new_remain_qty.toFixed(3);
				var new_row = document.getElementById("mbookstruct").insertRow(add_row_s);
				new_row.setAttribute("id", "row_" + add_row_s)
				new_row.className = "labelcenter labelhead";
				var c1 = new_row.insertCell(0);
				c1.align = "center";c1.style.border = "thin solid lightgray";
				var c2 = new_row.insertCell(1);
				c2.align = "center";c2.style.border = "thin solid lightgray";
				var c3 = new_row.insertCell(2);
				c3.align = "center";c3.style.border = "thin solid lightgray";
				var c4 = new_row.insertCell(3);
				c4.align = "center";c4.style.border = "thin solid lightgray";
				var c5 = new_row.insertCell(4);
				c5.align = "center";c5.style.border = "thin solid lightgray";
				var c6 = new_row.insertCell(5);
				c6.align = "center";c6.style.border = "thin solid lightgray";c6.style.borderRight = "none";
				var c10 = new_row.insertCell(6);
				c10.align = "center";c10.style.border = "thin solid lightgray";
				c10.style.borderLeft = "none";
				c10.style.borderRight = "none";
				c10.style.align = "none";
				var c7 = new_row.insertCell(7);
				c7.align = "center";c7.style.border = "thin solid lightgray";
				var c8 = new_row.insertCell(8);
				c8.align = "center";c8.style.border = "thin solid lightgray";c8.style.display="none";
				var c9 = new_row.insertCell(9);
				c9.align = "center";c9.style.border = "thin solid lightgray";
				
			
				//c1.innerText = document.form.txt_boq.value;cmb_depthunit_st
				c1.innerText = c1.textContent = document.form.sno_st.value;
				c2.innerText = c2.textContent = document.form.txt_dec_wk_st.value;
				c3.innerText = c3.textContent = document.form.txt_no_st.value;
				c4.innerText = c4.textContent = document.form.txt_l_st.value;
				c5.innerText = c5.textContent = document.form.txt_b_st.value;
				c6.innerText = c6.textContent = document.form.txt_d_st.value;
				c7.innerText = c7.textContent = document.form.txt_ca_st.value;
				c8.innerText = c8.textContent = document.form.remarks.value; 
				c10.innerText = c10.textContent = document.form.cmb_depthunit_st.value;
				c9.innerHTML = c9.textContent = "<input type='button' class='editbtnstyle' name='btn_st_edit_" + add_row_s + "' id='btn_st_edit_" + add_row_s + "' value='EDIT' title='Edit' onClick=editrow_st(" + add_row_s + ",'n')><input class='delbtnstyle' type='button'  name='btn_st_del_" + add_row_s + "'  id='btn_st_del_" + add_row_s + "' value='X' title='Cancel' onClick=delrow_st(" + add_row_s + ");get_current_entered_qty();>";
				var hide_values = ""; 
				hide_values = "<input type='hidden' value='" + c1.innerText + "' name='sno_st" + add_row_s + "' id='sno_st" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c2.innerText + "' name='txt_dec_wk_st" + add_row_s + "' id='txt_dec_wk_st" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c3.innerText + "' name='txt_no_st" + add_row_s + "' id='txt_no_st" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c4.innerText + "' name='txt_l_st" + add_row_s + "' id='txt_l_st" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c5.innerText + "' name='txt_b_st" + add_row_s + "' id='txt_b_st" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c6.innerText + "' name='txt_d_st" + add_row_s + "' id='txt_d_st" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c7.innerText + "' name='txt_ca_st" + add_row_s + "' id='txt_ca_st" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c8.innerText + "' name='remarks" + add_row_s + "' id='remarks" + add_row_s + "' >";
				hide_values += "<input type='hidden' value='" + c10.innerText + "' name='cmb_depthunit_st" + add_row_s + "' id='cmb_depthunit_st" + add_row_s + "' >";
				document.getElementById("add_hidden_st").innerHTML = document.getElementById("add_hidden_st").innerHTML + hide_values; 
	
				if (document.getElementById("add_set_a3").value == "")
					document.getElementById("add_set_a3").value = add_row_s;
				else
					document.getElementById("add_set_a3").value = document.getElementById("add_set_a3").value + "." + add_row_s; 
					 document.form.sno_st.value=parseInt(document.form.sno_st.value)+1;
				add_row_s++;
				cleartxt_st();
			//}
        }


		function editrow_st(rowno, update)
        {   
			
            var total;
            var net_value;
            var edit_row = document.getElementById("mbookstruct").rows[rowno].cells;
			var sno=document.form.sno_st.value;
			//gdocument.form.txt_dec_wk.focus();
			if(document.form.sno_hide.value=="")
			{
			document.form.sno_hide.value=document.form.sno_st.value;
			}
            
			if (update == 'y') // transfer controls to table row
            {	
				valid();
				var workorder_qty 		= 	document.form.txt_workorderqty.value;
				var deviateqty_percent 	= 	document.form.txt_deviatedqty_percent.value;
				var deviate_qty 		= 	Number(workorder_qty) * Number(deviateqty_percent) / 100;
				var total_qty 			= 	document.form.txt_totalqty.value;
				var content_area 		= 	0;
				var txt_box_id_str 		= 	document.form.add_set_a3.value.trim();
				var current_ca 			= 	document.form.txt_ca_st.value;
				if(current_ca == "")
				{
					current_ca = 0;
				}
				content_area = (Number(content_area) + Number(current_ca));
				if(txt_box_id_str != "")
				{
					var txt_box_id = txt_box_id_str.split(".");
					for(var i=0; i<txt_box_id.length; i++)
					{
						if(txt_box_id[i] != 2)
						{
							var ca = document.getElementById("txt_ca_st"+txt_box_id[i]).value;
							content_area = Number(content_area) + Number(ca);
						}
					}
													
				}
				var remain_qty 			= 	document.form.hid_remainingqty.value;
				var new_remain_qty 		= 	Number(remain_qty) - Number(content_area);
				var remainqty_percent 	= 	Number(new_remain_qty) * 100/ Number(total_qty);
				var used_qty 			= 	Number(workorder_qty) + Number(deviate_qty) - Number(new_remain_qty);
				var usedqty_percent 	= 	Number(used_qty) * 100/ Number(workorder_qty);
				document.form.txt_usedqty.value 				= used_qty.toFixed(3);
				document.form.txt_usedqty_percent.value 		= usedqty_percent.toFixed(2);
				document.form.txt_remainingqty_percent.value 	= remainqty_percent.toFixed(2);
				if(used_qty > workorder_qty)
				{
					alert("Entered measurement quantity is exceeded than Work Order quantity..")
					//cleartxt_st();
					//return false;
					//exit();
				}
				//else
				//{
					document.form.txt_remainingqty.value = new_remain_qty.toFixed(3);
					edit_row[0].innerText = edit_row[0].textContent = document.form.sno_st.value;
					edit_row[1].innerText = edit_row[1].textContent = document.form.txt_dec_wk_st.value;
					edit_row[2].innerText = edit_row[2].textContent= document.form.txt_no_st.value;
					edit_row[3].innerText = edit_row[3].textContent= document.form.txt_l_st.value;
					edit_row[4].innerText = edit_row[4].textContent= document.form.txt_b_st.value;
					edit_row[5].innerText = edit_row[5].textContent= document.form.txt_d_st.value;
					edit_row[6].innerText = edit_row[6].textContent= document.form.cmb_depthunit_st.value;
					edit_row[7].innerText = edit_row[7].textContent= document.form.txt_ca_st.value;
					edit_row[8].innerText = edit_row[8].textContent= document.form.remarks.value;
	
					document.getElementById("sno_st" + rowno).value = edit_row[0].innerText = edit_row[0].textContent
					document.getElementById("txt_dec_wk_st" + rowno).value = edit_row[1].innerText = edit_row[1].textContent
					document.getElementById("txt_no_st" + rowno).value = edit_row[2].innerText = edit_row[2].textContent
					document.getElementById("txt_l_st" + rowno).value = edit_row[3].innerText = edit_row[3].textContent
					document.getElementById("txt_b_st" + rowno).value = edit_row[4].innerText = edit_row[4].textContent
					document.getElementById("txt_d_st" + rowno).value = edit_row[5].innerText = edit_row[5].textContent
					document.getElementById("cmb_depthunit_st" + rowno).value = edit_row[6].innerText = edit_row[6].textContent
					document.getElementById("txt_ca_st" + rowno).value = edit_row[8].innerText = edit_row[7].textContent
					document.getElementById("remarks" + rowno).value = edit_row[8].innerText = edit_row[8].textContent
				//}
            }//update=='y'
			else  //transfer table row to controls
            {
				document.form.sno_st.value = edit_row[0].innerText = edit_row[0].textContent
                document.form.txt_dec_wk_st.value = edit_row[1].innerText = edit_row[1].textContent
                document.form.txt_no_st.value = edit_row[2].innerText = edit_row[2].textContent
                document.form.txt_l_st.value = edit_row[3].innerText = edit_row[3].textContent
                document.form.txt_b_st.value = edit_row[4].innerText = edit_row[4].textContent
                document.form.txt_d_st.value = edit_row[5].innerText = edit_row[5].textContent
				document.form.cmb_depthunit_st.value = edit_row[6].innerText = edit_row[6].textContent
                document.form.txt_ca_st.value = edit_row[7].innerText = edit_row[7].textContent
                document.form.remarks.value = edit_row[8].innerText = edit_row[8].textContent
            }

            if (prev_edit_row == 0)//first time edit the row
            { 
                document.getElementById("row_" + rowno).style.color = "red";
                document.getElementById("btn_st_edit_" + rowno).className = "editbtnstyle";
                document.getElementById("btn_st_add").outerHTML = "<input type='button' class='updatebtnstyle' title='Accept' name='btn_st_add' id='btn_st_add' value=' OK ' onClick=\"editrow_st(" + rowno + ",'y');\"><input type='button' class='delbtnstyle' title='Reset' name='btn_st_clr' id='btn_st_clr' value='RESET' onClick=\"cancel_st(" + rowno + ",'c')\">";
                prev_edit_row = rowno;
            }
            else
            {	
				//set
                if (rowno == prev_edit_row)
                { 
                    document.getElementById("row_" + prev_edit_row).style.color = "#770000";
                    document.getElementById("btn_st_edit_" + rowno).className = "editbtnstyle";
					document.getElementById("btn_st_clr").style.display="none";
                    document.getElementById("btn_st_add").outerHTML = "<input type='button' class='addbtnstyle' name='btn_st_add' id='btn_st_add' value='ADD' onClick='addrow_st();'>";
                    prev_edit_row = 0;
                    cleartxt_st();
                }

                else
                {	alert("after edit else part");
					document.getElementById("sno_st").value=document.getElementById("sno_hide").value;
					document.getElementById("sno_hide").value="";
                    document.getElementById("row_" + prev_edit_row).style.color = "#770000";
                    document.getElementById("btn_st_edit_" + prev_edit_row).className = "editbtnstyle";
                    document.getElementById("row_" + rowno).style.color = "red";
                    document.getElementById("btn_st_edit_" + rowno).className = "editbtnstyle";
                    document.getElementById("btn_st_add").outerHTML = "<input type='button' title='Accept' claas='updatebtnstyle' name='btn_st_add' id='btn_st_add' value=' OK ' onClick=\"editrow_st(" + rowno + ",'y');\">";
                    prev_edit_row = rowno;
                }document.getElementById("sno_st").value=document.getElementById("sno_hide").value;document.getElementById("sno_hide").value="";
            }
        }

     function delrow_st(rownum)
        {	if(rownum == 3) { document.getElementById("hid_deleteflag").value = "d"; }
			var no=document.getElementById("sno_hide").value=document.getElementById("sno_st").value;
            var src_row = (rownum + 1)
            var tar_row = rownum
            var noofadd = (add_row_s - 1)
			
            for (x = rownum; x < noofadd; x++)
            {	
				document.getElementById("sno_st" + tar_row).value= document.getElementById("sno_st" + src_row).value;
                document.getElementById("txt_dec_wk_st" + tar_row).value = document.getElementById("txt_dec_wk_st" + src_row).value
                document.getElementById("txt_no_st" + tar_row).value = document.getElementById("txt_no_st" + src_row).value
                document.getElementById("txt_l_st" + tar_row).value = document.getElementById("txt_l_st" + src_row).value
                document.getElementById("txt_b_st" + tar_row).value = document.getElementById("txt_b_st" + src_row).value
                document.getElementById("txt_d_st" + tar_row).value = document.getElementById("txt_d_st" + src_row).value
                document.getElementById("txt_ca_st" + tar_row).value = document.getElementById("txt_ca_st" + src_row).value;
                document.getElementById("remarks" + tar_row).value = document.getElementById("remarks" + src_row).value;
                tar_row++;
                src_row++;
                var trow = document.getElementById("mbookstruct").rows[x].cells;
                var srow = document.getElementById("mbookstruct").rows[x + 1].cells;
				trow[0].innerText = trow[0].textContent = srow[0].innerText = srow[0].textContent 
                trow[1].innerText = trow[1].textContent  = srow[1].innerText = srow[1].textContent
                trow[2].innerText = trow[2].textContent  = srow[2].innerText = srow[2].textContent
                trow[3].innerText = trow[3].textContent  = srow[3].innerText = srow[3].textContent
                trow[4].innerText = trow[4].textContent  = srow[4].innerText = srow[4].textContent
                trow[5].innerText = trow[5].textContent  = srow[5].innerText = srow[5].textContent
                trow[6].innerText = trow[6].textContent  = srow[6].innerText = srow[6].textContent
                trow[7].innerText = trow[7].textContent  = srow[7].innerText = srow[7].textContent
            }
			document.getElementById("sno_st" + tar_row).outerHTML = ""
            document.getElementById("txt_dec_wk_st" + tar_row).outerHTML = ""
            document.getElementById("txt_no_st" + tar_row).outerHTML = ""
            document.getElementById("txt_l_st" + tar_row).outerHTML = ""
            document.getElementById("txt_b_st" + tar_row).outerHTML = ""
            document.getElementById("txt_d_st" + tar_row).outerHTML = ""
			document.getElementById("cmb_depthunit_st" + tar_row).outerHTML = ""
            document.getElementById("txt_ca_st" + tar_row).outerHTML = ""
            document.getElementById("remarks" + tar_row).outerHTML = ""

            document.getElementById('mbookstruct').deleteRow(noofadd)
            document.getElementById("add_set_a3").value = "";

            for (x = 2; x < noofadd; x++)
            {
                if (document.getElementById("add_set_a3").value == "")
                    {document.getElementById("add_set_a3").value = x;
					document.getElementById("sno_st").value=x-1;
					}
                else
				{
                    document.getElementById("add_set_a3").value += ("." + x);
					document.getElementById("sno_st").value=x-1;
				}
            }
		
            add_row_s = noofadd++; 
			for(i=1;i<no-1;i++)
			{
			var trow = document.getElementById("mbookstruct").rows[i+2].cells; 
			trow[0].innerText = trow[0].textContent = i;
			}
			document.getElementById("sno_hide").value="";
        }
	function cleartxt_st()
        {
			//document.getElementById("sno").value = "";
            document.getElementById("txt_dec_wk_st").value = "";
            document.getElementById("txt_no_st").value = "";
            document.getElementById("txt_l_st").value = "";
            document.getElementById("txt_b_st").value = "";
            document.getElementById("txt_d_st").value = "";
            document.getElementById("txt_ca_st").value = "";
			document.getElementById("cmb_depthunit_st").value = "";
            //document.getElementById("remarks").value = "";
        }    

    function cancel_st(rowno,can)
        {

            /*var edit_row = document.getElementById("mbookstruct").rows[rowno].cells;
			document.form.sno_st.value = edit_row[0].innerText = edit_row[0].textContent*/
            document.getElementById("txt_dec_wk_st").value = "";
            document.getElementById("txt_no_st").value = "";
            document.getElementById("txt_l_st").value = "";
            document.getElementById("txt_b_st").value = "";
            document.getElementById("txt_d_st").value = "";
            document.getElementById("txt_ca_st").value = "";
            document.getElementById("cmb_depthunit_st").value = "";
            document.getElementById("row_" + rowno).style.color = "#3A2D2C";
            document.getElementById("btn_st_edit_" + rowno).className = "editbtnstyle";
            document.getElementById("btn_st_clr").style.display="none";
            document.getElementById("btn_st_add").outerHTML = "<input type='button' class='addbtnstyle' name='btn_st_add' id='btn_st_add' value='ADD' onClick='addrow_st()'>";
            document.getElementById("sno_st").value=document.getElementById("sno_hide").value;document.getElementById("sno_hide").value="";
        }    
        function contentorarea_st()
        {
            var no = alltrim(document.form.txt_no_st.value)
            var l = alltrim(document.form.txt_l_st.value)
            var b = alltrim(document.form.txt_b_st.value)
            var d = alltrim(document.form.txt_d_st.value);
			//alert(no);alert(l);alert(b);alert(d);
			if((no == '') && (l == '') && (b == '') && (d == ''))
			{
				var ca = "";
				document.form.txt_ca_st.value = ca;
			}
			else
			{
				if (no != '')
				{
					no = no; 
				}
				else
				{
					no = 1;
				}
				if (l != '')
				{
					l = l; 
				}
				else
				{
					l = 1;
				}
				if (b != '')
				{
					b = b; 
				}
				else
				{
					b = 1;
				}
				if (d != '')
				{
					d = d; 
				}
				else
				{
					d = 1;
				}
				//alert(no);alert(l);alert(b);alert(d);
				/*var ca = Number(no) * Number(l) * Number(b) * Number(d);
				ca=parseFloat(ca.toFixed(3));*/
				ca = Math.round((parseFloat(no) * parseFloat(l) * parseFloat(b) * parseFloat(d))*1000)/1000;
				document.form.txt_ca_st.value = ca;
			}
        }
	function lock_unlock(obj)
	{
		var img_id = obj.id;
		var work_order_no = document.form.workorderno.value;
		
		if(img_id == "lock")
		{
			document.getElementById("lock").style.display = "none";
		 	document.getElementById("unlock").style.display = "";
			document.form.hid_lock_unlock_worder.value = "";
			document.form.hid_lock_unlock.value = "";
		}
		if((work_order_no != "") && (work_order_no != 0))
		{
			if(img_id == "unlock")
			{
				document.getElementById("lock").style.display = "";
				document.getElementById("unlock").style.display = "none";
				var work_order_no = document.form.workorderno.value;
				document.form.hid_lock_unlock.value = "locked";
				document.form.hid_lock_unlock_worder.value = work_order_no;
			}
		}
	}
	
	/*window.onload = function(){
	alert()
		var work_order_no = document.form.hid_lock_unlock_worder.value;
		if((work_order_no != "") && (work_order_no != 0))
		{
			var lock_value = document.form.hid_lock_unlock.value;
			if(lock_value == "locked")
			{
				document.getElementById("lock").style.display = "";
				document.getElementById("unlock").style.display = "none";
			}
			else
			{
				document.getElementById("lock").style.display = "none";
				document.getElementById("unlock").style.display = "";
			}
			var lock_value = document.form.hid_lock_unlock.value;
			if(lock_value == "locked")
			{
				document.form.workorderno.value = work_order_no;
				func_item_no();
			}
		}
	}*/
	function set_tab_index()
	{
		document.getElementById("txt_dec_wk").focus();
		//var focus_element_id = document.activeElement.id;
		//var obj = document.getElementById(focus_element_id);
		//alert(focus_element_id)
		//obj.style.backgroundColor = 'lightblue';
		document.getElementById("txt_dec_wk").tabIndex = 1;
		document.getElementById("txt_no").tabIndex = 2;
		document.getElementById("txt_l").tabIndex = 3;
		document.getElementById("txt_b").tabIndex = 4;
		document.getElementById("txt_d").tabIndex = 5;
		document.getElementById("btn_add").tabIndex = 6;
	}
	function set_tab_index_steel()
	{
		document.getElementById("txt_dec_wk_mt").focus();
		//var focus_element_id = document.activeElement.id;
		//var obj = document.getElementById(focus_element_id);
		//alert(focus_element_id)
		//obj.style.backgroundColor = 'lightblue';
		document.getElementById("txt_dec_wk_mt").tabIndex = 1;
		document.getElementById("sel_dia_mt").tabIndex = 2;
		document.getElementById("txt_no_mt").tabIndex = 3;
		document.getElementById("txt_l_mt").tabIndex = 4;
		document.getElementById("btn_mt_add").tabIndex = 5;
	}
	function set_tab_index_struct()
	{
		document.getElementById("txt_dec_wk_st").focus();
		//var focus_element_id = document.activeElement.id;
		//var obj = document.getElementById(focus_element_id);
		//alert(focus_element_id)
		//obj.style.backgroundColor = 'lightblue';
		document.getElementById("txt_dec_wk_st").tabIndex = 1;
		document.getElementById("txt_no_st").tabIndex = 2;
		document.getElementById("txt_l_st").tabIndex = 3;
		document.getElementById("txt_b_st").tabIndex = 4;
		document.getElementById("txt_d_st").tabIndex = 5;
		document.getElementById("cmb_depthunit_st").tabIndex = 6;
		document.getElementById("btn_st_add").tabIndex = 7;
	}
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	/*function confirmSubmit()
	{
		
	}*/
    </script>
	<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="pageload" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="top" onSubmit="return confirm('Do you really want to submit the form?');">
<?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1">
                            <div class="title">Measurement Sheet Entry (M Book) </div>		
                          
                                <input type="hidden" name="txt_item_no" id="txt_item_no" value="">
                                <input type="hidden" name="txt_work_no" id="txt_work_no" value="">
								<input type="hidden" name="txt_measurement_date" id="txt_measurement_date" >
                                <div class="container">
                                    <table width="100%"  bgcolor="#E8E8E8"  border="1" cellpadding="0" cellspacing="0" align="center" >
                                        <tr>
										<td>&nbsp;</td>
										<td colspan="4">&nbsp;</td>
                                        </tr>
			<!------ NEW  FORMAT STARTS  HERE  ------------->			
										<tr>
											<td colspan="3" align="">
												<table style="display: inline-block;" width="">
													<tr>
														<td class="label" nowrap="nowrap" width="130px">&nbsp;&nbsp;&nbsp;Short Name</td>
														<td>
															<select id="workorderno" name="workorderno" onChange="func_item_no();cls()" class="textboxdisplaysmall" style="width:484px;height:22px;">
															<?php if($_GET['mbheaderid'] != "") { ?>
																<option value="<?php echo $sheetid; ?>"> <?php echo $work_order_no; ?> </option><?php } else { ?>
																<option value=""> ----------------------------- Select Work Order No -------------------------------- </option>
																<?php echo $objBind->BindWorkOrderNo(0); }?>
															</select>
														</td>
														<td>
															<a title="Unlocked" class="tooltip"><img src="Buttons/unlock_1.png" style="background-color:#FDFDFD; border:solid 1px #CCCCCC;" height="19px" width="19px" id="unlock" class="unlock" onClick="lock_unlock(this)"></a>
															<a title="Locked" class="tooltip"><img src="Buttons/lock_2.png" height="19px" width="19px" id="lock" class="lock" style="display:none; background-color:#FDFDFD; border:solid 1px #CCCCCC" onClick="lock_unlock(this);"></a> 
															<input type="hidden" name="hid_lock_unlock" id="hid_lock_unlock" value="<?php echo $lock_value; ?>">
															<input type="hidden" name="hid_lock_unlock_worder" id="hid_lock_unlock_worder" value="<?php echo $sheet_id; ?>">
														</td>
													</tr>
													<tr><td>&nbsp;</td><td colspan="2" id="val_work" style="color:red"></td></tr>
													<tr>
														<td class="label" nowrap="nowrap" width="130px">&nbsp;&nbsp;&nbsp;Work Order No.</td>
														<td>
															<?php if($_GET['mbheaderid'] != "") { ?>
															<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplaysmall" style="width:479px;" value="<?php echo $work_order_no; ?>">
															<?php }else { ?>
															<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplaysmall" style="width:479px;">
															<?php } ?>
														</td>
														<td>&nbsp;</td>
													</tr>
													<tr><td>&nbsp;</td><td colspan="2" id="" style="color:red"></td></tr>
													<tr>
														<td class="label" nowrap="nowrap">&nbsp;&nbsp;&nbsp;Item No.</td>
														<td>
															<?php 
																if($_GET['mbheaderid'] != "") 
																{ ?>
																	<select name="itemno" id="itemno" class="textboxdisplaysmall" style="width:95px;height:22px;">
																		<option value="<?php echo $divid; ?>"><?php echo $divname; ?></option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<?php 
																} 
																else 
																{?>
																	<select onBlur="display();get_itemquantity(this);" name="itemno" id="itemno" class="textboxdisplaysmall" onChange="cls(this);func_subitem_no();getitem_desc(this);" style="width:95px;height:22px;">
																		<option value="0">Item No</option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<?php 
																} ?>
															   <!-- &nbsp;&nbsp;&nbsp;&nbsp;Sub Item No.-->
																<?php 
																if($_GET['mbheaderid'] != "") 
																{ ?>
																	<select name="subitemno" id="subitemno" class="textboxdisplaysmall" style="width:100px;height:22px;">
																		<option value="<?php echo $subdivid; ?>"><?php echo $subdivname; ?></option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															   <?php 
															   	} 
															   	else 
															   	{?>
																	<select onBlur="display();get_itemquantity(this);" name="subitemno" id="subitemno" class="textboxdisplaysmall" style="width:100px;height:22px;" onChange="cls(this);find_desc(this); find_subsubitem(this);">
																		<option value="0">Sub Item 1</option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															   	<?php 
															   	} ?>
															   	<?php 
															   	if($_GET['mbheaderid'] != "") 
															   	{ ?>
																	<select name="subsubitemno" id="subsubitemno" class="textboxdisplaysmall" style="width:105px;height:22px;">
																		<option value="<?php echo $subsubdivid; ?>"><?php echo $subsubdivname; ?></option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<?php 
																} 
																else 
																{?>
																	<select onBlur="display();get_itemquantity(this);" name="subsubitemno" id="subsubitemno" class="textboxdisplaysmall" style="width:105px;height:22px;"  onChange="cls(this);find_subsubsubitem(this);find_desc(this);">
																		<option value="0">Sub Item 2</option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<?php 
																} ?>
																<?php 
																if($_GET['mbheaderid'] != "") 
																{ ?>
																	<select name="subsubsubitemno" id="subsubsubitemno" class="textboxdisplaysmall" style="width:107px;height:22px;">
																		<option value="<?php echo $subsubsubdivid; ?>"><?php echo $subsubsubdivname; ?></option>
																	</select>
																<?php 
																} 
																else 
																{?>
																	<select onBlur="display();get_itemquantity(this);" name="subsubsubitemno" id="subsubsubitemno" class="textboxdisplaysmall" style="width:107px;height:22px;"  onChange="find_desc(this);">
																		<option value="0">Sub Item 3</option>
																	</select>
																<?php 
																} ?>
														</td>
														<td>&nbsp;</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
														<td style="color:red">
															<span id="val_item"></span>
															<span id="val_sub"></span>
															<span id="val_subsub"></span>
															<span id="val_subsubsub"></span>
														</td>
														<td>&nbsp;</td>
													</tr>
													<tr>
														<td class="label">&nbsp;&nbsp;&nbsp;Short Notes</td>
														<td class="labeldisplay">
															<textarea name="descriptionnotes" id="descriptionnotes" class="textboxdisplaysmall txtarea_style" rows="2" style="width:482px;" disabled="disabled"><?php if($_GET['mbheaderid'] != "") { echo $shortnotes; } ?></textarea>
														</td>
														<td>&nbsp;</td>
													</tr>
													<tr style="height:7px;">
														<td colspan="3">
															<?php if($_GET['mbheaderid'] != "") { ?> <input type="hidden" id="txt_mbdetailid" name="txt_mbdetailid" value="<?php echo $_GET['mbdetailid']; ?>" /> <?php } ?>
														</td>
													</tr>
													<tr>
														<td class="label">&nbsp;&nbsp;&nbsp;Date</td>
														<td>
															<?php if($_GET['mbheaderid'] != "") { ?>
															<input type="text" id="datepicker" name="datepicker" class="textboxdisplaysmall" value="<?php echo $measure_date; ?>" />
															<?php } else { ?>
															<input type="text" id="datepicker" name="datepicker" class="textboxdisplaysmall" value="<?php //echo date('d-m-Y'); ?>" />
															<?php } ?>
															<span id="val_date" style="color:red;"></span>&nbsp;
														</td>
														<td>&nbsp;</td>
													</tr>
													<tr style="height:7px;">
														<td></td>
														<td></td>
														<td></td>
													</tr>
												</table>
												
												<table style="display: inline-block;" width="">
													<!--<tr>
														<td class="label" width="68px">Date</td>
														<td>
															<?php if($_GET['mbheaderid'] != "") { ?>
															<input type="text" id="datepicker" name="datepicker" class="textboxdisplaysmall" size="11" value="<?php echo $measure_date; ?>" />
															<?php } else { ?>
															<input type="text" id="datepicker" name="datepicker" class="textboxdisplaysmall" size="11" value="<?php //echo date('d-m-Y'); ?>" />
															<?php } ?>
														</td>
														<td class="label" width="80px">&nbsp;&nbsp;&nbsp;&nbsp;Unit</td>
														<td>
															<input type="text" name="remarks" value="<?php if($_GET['mbheaderid'] != "") { echo $remarks; } else { echo ""; }?>" id="remarks" class="textboxdisplaysmall" size="19" readonly="" />
															<span id="label_unit" style="display:none;"></span>
														</td>
													</tr>
													<tr><td colspan="2" style="color:red;"><span id="val_date"></span>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>-->
													<tr>
														<td class="label" colspan="2" width="154px">Work Order Qty.</td>
														<td>&nbsp;&nbsp;&nbsp;
															<?php 
																if($_GET['mbheaderid'] != "") 
																{ ?>
																<input type="text" name="txt_workorderqty" id="txt_workorderqty" style="text-align:right; width:96px;" class="textboxdisplaysmall textboxwidth2"  value="<?php echo number_format($workorder_qty, 3, '.', ''); ?>">
																<?php 
																} 
																else 
																{ ?>
																<input type="text" name="txt_workorderqty" id="txt_workorderqty" style="text-align:right; width:96px;" class="textboxdisplaysmall textboxwidth2">
																<?php 
																} ?>
																 <label class="label">&nbsp;&nbsp;Unit</label>
															<input type="text" name="remarks" value="<?php if($_GET['mbheaderid'] != "") { echo $remarks; } else { echo ""; }?>" id="remarks" class="textboxdisplaysmall textboxwidth1" readonly="" />
															<span id="label_unit" style="display:none;"></span>
														</td>
													</tr>
													<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
													<tr>
														<td colspan="2" class="label">Deviated &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;( % ) </td>
														<td colspan="2">&nbsp;&nbsp;&nbsp;
															<?php if($_GET['mbheaderid'] != "") { ?>
															
															<!--<label class="label">&nbsp;( % )</label>-->
															<input type="text" 		name="txt_deviatedqty_percent" id="txt_deviatedqty_percent" class="textboxdisplaysmall textboxwidth3" value="<?php echo number_format($deviateqty_percent, 2, '.', ''); ?>">
															<input type="hidden" 	name="hid_deviatedqty_percent" id="hid_deviatedqty_percent" value="<?php echo $deviateqty_percent; ?>">
															<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;Qty.&nbsp;</label>
															<input type="text" 		name="txt_deviatedqty" id="txt_deviatedqty" style="text-align:right" class="textboxdisplaysmall textboxwidth2" value="<?php echo number_format($deviate_qty, 3, '.', ''); ?>">
															<input type="hidden" 	name="hid_deviatedqty" id="hid_deviatedqty" value="<?php echo $deviate_qty; ?>">
															
															<?php } else { ?>
															<!--<label class="label">&nbsp;( % )</label>-->
															<input type="text" 		name="txt_deviatedqty_percent" id="txt_deviatedqty_percent" class="textboxdisplaysmall textboxwidth3">
															<input type="hidden" 	name="hid_deviatedqty_percent" id="hid_deviatedqty_percent">
															<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;Qty.&nbsp;</label>
															<input type="text" 		name="txt_deviatedqty" id="txt_deviatedqty"  style="text-align:right;"class="textboxdisplaysmall textboxwidth2">
															<input type="hidden" 	name="hid_deviatedqty" id="hid_deviatedqty">
															
															<?php } ?>
														</td>
													</tr>
													<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
													<tr>
														<td colspan="2" class="label">Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;( % )</td>
														<td colspan="2">&nbsp;&nbsp;&nbsp;
															<?php if($_GET['mbheaderid'] != "") { ?>
															<input type="text" 		name="txt_totalqty_percent" id="txt_totalqty_percent" class="textboxdisplaysmall textboxwidth3" value="<?php echo number_format($total_qty_percent, 2, '.', ''); ?>">
															<input type="hidden" 	name="hid_totalqty_percent" id="hid_totalqty_percent" value="<?php echo number_format($total_qty_percent, 3, '.', ''); ?>">
															<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;Qty.&nbsp;</label>
															<input type="text" 		name="txt_totalqty" id="txt_totalqty" style="text-align:right" class="textboxdisplaysmall textboxwidth2" value="<?php echo number_format($total_qty, 3, '.', ''); ?>">
															<input type="hidden" 	name="hid_totalqty" id="hid_totalqty" value="<?php echo $total_qty; ?>">
															
															<?php } else { ?>
															<input type="text" 		name="txt_totalqty_percent" id="txt_totalqty_percent" class="textboxdisplaysmall textboxwidth3">
															<input type="hidden" 	name="hid_totalqty_percent" id="hid_totalqty_percent">
															<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;Qty.&nbsp;</label>
															<input type="text" 		name="txt_totalqty" id="txt_totalqty" style="text-align:right" class="textboxdisplaysmall textboxwidth2">
															<input type="hidden" 	name="hid_totalqty" id="hid_totalqty">
															
															<?php } ?>
														</td>
													</tr>
													<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
													<tr>
														<td colspan="2" class="label">Executed &nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;( % )</td>
														<td colspan="2">&nbsp;&nbsp;&nbsp;
															<?php if($_GET['mbheaderid'] != "") { ?>
															<input type="text" 		name="txt_usedqty_percent" id="txt_usedqty_percent" class="textboxdisplaysmall textboxwidth3" value="<?php echo number_format($used_qty_percent, 2, '.', ''); ?>">
															<input type="hidden" 	name="hid_usedqty_percent" id="hid_usedqty_percent" value="<?php echo $used_qty_percent; ?>">
															<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;Qty.&nbsp;</label>
															<input type="text" 		name="txt_usedqty" id="txt_usedqty" style="text-align:right" class="textboxdisplaysmall textboxwidth2" value="<?php echo number_format($used_qty, 3, '.', ''); ?>">
															<input type="hidden" 	name="hid_usedqty" id="hid_usedqty" value="<?php echo $used_qty; ?>">
															
															<?php } else { ?>
															<input type="text" 		name="txt_usedqty_percent" id="txt_usedqty_percent" class="textboxdisplaysmall textboxwidth3">
															<input type="hidden" 	name="hid_usedqty_percent" id="hid_usedqty_percent">
															<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;Qty.&nbsp;</label>
															<input type="text" 		name="txt_usedqty" id="txt_usedqty" style="text-align:right" class="textboxdisplaysmall textboxwidth2">
															<input type="hidden" 	name="hid_usedqty" id="hid_usedqty">
															
															<?php } ?>
														</td>
													</tr>
													<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
													<tr>
														<td colspan="2" class="label">Remaining&nbsp;: &nbsp;&nbsp;&nbsp;( % )</td>
														<td colspan="2">&nbsp;&nbsp;&nbsp;
															<?php if($_GET['mbheaderid'] != "") { ?>
															<input type="text" 		name="txt_remainingqty_percent" id="txt_remainingqty_percent" class="textboxdisplaysmall textboxwidth3" value="<?php echo number_format($remaining_qty_percent, 2, '.', ''); ?>">
															<input type="hidden" 	name="hid_remainingqty_percent" id="hid_remainingqty_percent" value="<?php echo $remaining_qty_percent; ?>">
															<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;Qty.&nbsp;</label>
															<input type="text" 		name="txt_remainingqty" id="txt_remainingqty" style="text-align:right" class="textboxdisplaysmall textboxwidth2" value="<?php echo number_format($remaining_qty, 3, '.', ''); ?>">
															<input type="hidden" 	name="hid_remainingqty" id="hid_remainingqty" value="<?php echo ($remaining_qty+$current_item_qty); ?>">
															
															<?php } else { ?>
															<input type="text" 		name="txt_remainingqty_percent" id="txt_remainingqty_percent" class="textboxdisplaysmall textboxwidth3">
															<input type="hidden" 	name="hid_remainingqty_percent" id="hid_remainingqty_percent">
															<label class="label">&nbsp;&nbsp;&nbsp;&nbsp;Qty.&nbsp;</label>
															<input type="text" 		name="txt_remainingqty" id="txt_remainingqty" style="text-align:right" class="textboxdisplaysmall textboxwidth2">
															<input type="hidden" 	name="hid_remainingqty" id="hid_remainingqty">
															
															<?php } ?>
														</td>
													</tr>
													<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
												</table>
											</td>
										</tr>
		<!------  NEW FORMAT ENDS HERE -------------------->					
										
										<!--<tr>
												<td>
														<table style="" width="100%" align="center">		
															<tr>
																<td width="15px">&nbsp;</td>
																<td  class="label" nowrap="nowrap" width="130px">Work Order No.</td>
																<td class="label" style="width:510px;" bgcolor="">
																	<select id="workorderno" name="workorderno" onChange="func_item_no();cls()" class="textboxdisplaysmall" style="width:484px;height:22px;">
																	<?php if($_GET['mbheaderid'] != "") { ?>
																	<option value="<?php echo $sheetid; ?>"> <?php echo $work_order_no; ?> </option><?php } else { ?>
																			<option value=""> ----------------------------- Select Work Order No -------------------------------- </option>
																			<?php echo $objBind->BindWorkOrderNo(0); }?>
																			
																  </select> 
																  <a title="Unlocked" class="tooltip"><img src="Buttons/unlock.png" style="background-color:#FDFDFD; border:solid 1px #CCCCCC;" height="19px" width="19px" id="unlock" class="unlock" onClick="lock_unlock(this)"></a>
																  <a title="Locked" class="tooltip"><img src="Buttons/lock.png" height="19px" width="19px" id="lock" class="lock" style="display:none; background-color:#FDFDFD; border:solid 1px #CCCCCC" onClick="lock_unlock(this);"></a> 
																  <input type="hidden" name="hid_lock_unlock" id="hid_lock_unlock" value="<?php echo $lock_value; ?>">
																  <input type="hidden" name="hid_lock_unlock_worder" id="hid_lock_unlock_worder" value="<?php echo $sheet_id; ?>">
																</td>
																<td class="label" width="80px" align="center">Date&nbsp;&nbsp;&nbsp;&nbsp;</td>
																<td  class="label">
																<?php if($_GET['mbheaderid'] != "") { ?>
																<input type="text" id="datepicker" name="datepicker" class="textboxdisplaysmall" size="13" value="<?php echo $measure_date; ?>" />
																<?php } else { ?>
																<input type="text" id="datepicker" name="datepicker" class="textboxdisplaysmall" size="13" value="<?php echo date('d-m-Y'); ?>" />
																<?php } ?>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unit
																 &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="remarks" value="<?php if($_GET['mbheaderid'] != "") { echo $remarks; } else { echo ""; }?>" id="remarks" class="textboxdisplaysmall" size="16" readonly="" />
																						<span id="label_unit" style="display:none;"></span>
																</td>
															</tr>
					
															<tr><td>&nbsp;</td><td></td><td colspan="3" id="val_work" style="color:red"></td></tr>
					
					
					
															<tr>
																<td>&nbsp;</td>
																<td  class="label" nowrap="nowrap">Item No.</td>
																<td class="label"  colspan="3">
																<?php 
																if($_GET['mbheaderid'] != "") 
																{ ?>
																	<select name="itemno" id="itemno" class="textboxdisplaysmall" style="width:100px;height:22px;">
																		<option value="<?php echo $divid; ?>"><?php echo $divname; ?></option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<?php 
																} 
																else 
																{?>
																	<select onBlur="display();get_itemquantity(this);" name="itemno" id="itemno" class="textboxdisplaysmall" onChange="cls(this);func_subitem_no();getitem_desc(this);" style="width:100px;height:22px;">
																		<option value="0">Item No</option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<?php 
																} ?>
																<?php 
																if($_GET['mbheaderid'] != "") 
																{ ?>
																	<select name="subitemno" id="subitemno" class="textboxdisplaysmall" style="width:100px;height:22px;">
																		<option value="<?php echo $subdivid; ?>"><?php echo $subdivname; ?></option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															   <?php 
															   	} 
															   	else 
															   	{?>
																	<select onBlur="display();get_itemquantity(this);" name="subitemno" id="subitemno" class="textboxdisplaysmall" style="width:100px;height:22px;" onChange="cls(this);find_desc(this); find_subsubitem(this);">
																		<option value="0">Sub Item 1</option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															   	<?php 
															   	} ?>
															   	<?php 
															   	if($_GET['mbheaderid'] != "") 
															   	{ ?>
																	<select name="subsubitemno" id="subsubitemno" class="textboxdisplaysmall" style="width:100px;height:22px;">
																		<option value="<?php echo $subsubdivid; ?>"><?php echo $subsubdivname; ?></option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<?php 
																} 
																else 
																{?>
																	<select onBlur="display();get_itemquantity(this);" name="subsubitemno" id="subsubitemno" class="textboxdisplaysmall" style="width:100px;height:22px;"  onChange="cls(this);find_subsubsubitem(this);find_desc(this);">
																		<option value="0">Sub Item 2</option>
																	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<?php 
																} ?>
																<?php 
																if($_GET['mbheaderid'] != "") 
																{ ?>
																	<select name="subsubsubitemno" id="subsubsubitemno" class="textboxdisplaysmall" style="width:100px;height:22px;">
																		<option value="<?php echo $subsubsubdivid; ?>"><?php echo $subsubsubdivname; ?></option>
																	</select>
																<?php 
																} 
																else 
																{?>
																	<select onBlur="display();get_itemquantity(this);" name="subsubsubitemno" id="subsubsubitemno" class="textboxdisplaysmall" style="width:100px;height:22px;"  onChange="find_desc(this);">
																		<option value="0">Sub Item 3</option>
																	</select>
																<?php 
																} ?>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Work Order Quantity 
																&nbsp;&nbsp;&nbsp;&nbsp;
																<?php 
																if($_GET['mbheaderid'] != "") 
																{ ?>
																<input type="text" name="txt_workorderqty" id="txt_workorderqty" class="textboxdisplaysmall" size="28" value="<?php echo number_format($workorder_qty, 3, '.', ''); ?>">
																<?php 
																} 
																else 
																{ ?>
																<input type="text" name="txt_workorderqty" id="txt_workorderqty" class="textboxdisplaysmall" size="28">
																<?php 
																} ?>
																</td>
															</tr>
					
															<tr>
																<td>&nbsp;</td>
																<td>&nbsp;</td>
																<td colspan="2" style="color:red">
																	<span id="val_item"></span>
																	<span id="val_sub"></span>
																	<span id="val_subsub"></span>
																	<span id="val_subsubsub"></span>
																</td>
															</tr>
					
										
															<tr>
																<td>&nbsp;</td>
																<td  class="label">Short Notes</td>
																<td  class="labeldisplay">
																	<textarea name="descriptionnotes" id="descriptionnotes" class="textboxdisplaysmall txtarea_style" rows="3" style="width: 483px;" disabled="disabled"><?php if($_GET['mbheaderid'] != "") { echo $shortnotes; } ?></textarea>
																</td>
																<td colspan="2" class="label" align="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remaining Quantity
																  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
																  <?php if($_GET['mbheaderid'] != "") { ?>
																  <input type="text" name="txt_remainingqty" id="txt_remainingqty" class="textboxdisplaysmall" size="28" value="<?php echo number_format($remaining_qty, 3, '.', ''); ?>">
																  <input type="hidden" name="hid_remainingqty" id="hid_remainingqty" value="<?php echo ($remaining_qty+$current_item_qty); ?>">
																  <?php } else { ?>
																  <input type="text" name="txt_remainingqty" id="txt_remainingqty" class="textboxdisplaysmall" size="28">
																  <input type="hidden" name="hid_remainingqty" id="hid_remainingqty">
																  <?php } ?>
																</td>
																
															</tr>
															<tr>
																<td colspan="5">
																	&nbsp;<?php if($_GET['mbheaderid'] != "") { ?> <input type="hidden" id="txt_mbdetailid" name="txt_mbdetailid" value="<?php echo $_GET['mbdetailid']; ?>" /> <?php } ?>
																</td>
															</tr>
														</table>
												</td>
										</tr>-->
                		                 <tr>
                                          <td colspan="5" style="height: 404px">
																		
<div style="width:100%; overflow-x:hidden; overflow-y: auto;" id="table2" class="hide">
<div style="height:404px; width:96%; padding-left: 0.999em">
<table style="width: 1040px; height: 104px;" id="mbookmetal">
    <tr style="height:32px;">
		  <td style="border-left: thin solid lightgray; border-right: thin solid lightgray; border-top: thin solid lightgray;color:#003399; width: 23px;  border-bottom-style: none; border-bottom-color: inherit; border-bottom-width: medium;" class="labelsmall" align="center" >Sno</td>
		  <td style="width: 200px; color:#003399;border:thin lightgray solid;" class="labelsmall" align="center">Description of Work</td>
		  <td style="width: 46px; color:#003399;border:thin lightgray solid;line-height:128%" class="labelsmall" align="center">Dia of rod</td>
		  <td style="width: 33px; color:#003399;border:thin lightgray solid" class="labelsmall" align="center">Nos</td>
		  <td style="width: 54px; color:#003399;border:thin lightgray solid;line-height:88%" class="labelsmall" align="center">Length in metre</td>
		  <td colspan="10" class="labelsmall" style="color:#003399;border:thin lightgray solid" align="center">Total length in metre</td>
    </tr>
    <tr style="height:20px;">
		  <td style="border-left: thin solid lightgray; border-right: thin solid lightgray; border-bottom: thin solid lightgray; width: 23px; color:#003399; border-top-style: none; border-top-color: inherit; border-top-width: medium;">&nbsp;
                    </td>
		  <td style="border-left: thin solid lightgray; border-right: thin solid lightgray; border-bottom: thin solid lightgray; width: 200px; color:#003399; border-top-style: none; border-top-color: inherit; border-top-width: medium;" >&nbsp;
		  </td>
		  <td style="border-left: thin solid lightgray; border-right: thin solid lightgray; border-bottom: thin solid lightgray; width: 46px; color:#003399; border-top-style: none; border-top-color: inherit; border-top-width: medium;">&nbsp;
		  </td>
		  <td style="border-left: thin solid lightgray; border-right: thin solid lightgray; border-bottom: thin solid lightgray; width: 33px; color:#003399; border-top-style: none; border-top-color: inherit; border-top-width: medium;">&nbsp;
		  </td>
		  <td style="border-left: thin solid lightgray; border-right: thin solid lightgray; border-bottom: thin solid lightgray; width: 54px; color:#003399; border-top-style: none; border-top-color: inherit; border-top-width: medium;">&nbsp;
		  </td>
		  <td class="labelsmall" style="color:#003399;border:thin lightgray solid" align="center">8</td>
		  <td style="width: 27px; color:#003399;border:thin lightgray solid" class="labelsmall" align="center">10</td>
		  <td style="width: 26px; color:#003399;border:thin lightgray solid" class="labelsmall" align="center">12</td>
		  <td class="labelsmall" style="color:#003399;border:thin lightgray solid" align="center">16</td>
		  <td style="width: 35px; color:#003399;border:thin lightgray solid" class="labelsmall" align="center">20</td>
		  <td class="labelsmall" style="color:#003399;border:thin lightgray solid" align="center">25</td>
		  <td style="width: 2px; color:#003399;border:thin lightgray solid" class="labelsmall" align="center">28</td>
		  <td style="width: 33px; color:#003399;border:thin lightgray solid" class="labelsmall" align="center">32</td>
		  <td style="width: 33px; color:#003399;border:thin lightgray solid" class="labelsmall" align="center">36</td>
		  <td style="width: 80px; color:#003399;border:thin lightgray solid" class="labelsmall">&nbsp;
		  </td>
    </tr>
    <tr style="height:20px;">
                  <td style="width: 23px; border:thin lightgray solid">
	            <input name="sno_mt" id="sno_mt" value="1" type="text" style="width: 23px;"  class="textboxdisplaysmall" readonly="" /></td>
		  <td style="width: 200px; color:#003399;border:thin lightgray solid">
		    <input name="txt_dec_wk_mt" id="txt_dec_wk_mt" type="text" style="width: 200px" class="textboxdisplaysmall" value="<?php if($_GET['mbheaderid'] != "") { echo $descwork; } else { echo ""; } ?>" tabindex="1"/></td>
		  <td style="width: 46px; color:#003399;border:thin lightgray solid" align="center">
		    <select style="width: 46px; height:20px;" class="textboxdisplaysmall" name="sel_dia_mt" id="sel_dia_mt" onBlur="calculate();" tabindex="2">
			<?php if(($_GET['mbheaderid'] != "") &&($measurement_dia != ""))
			{
			 ?>
			 <option value="<?php echo $measurement_dia; ?>">
			 <?php 
			 echo $measurement_dia; 
			 } 
			 ?>
			 </option>
		    <option value="">Dia</option>
			<option value="8">8</option>
		    <option value="10">10</option>
		    <option value="12">12</option>
		    <option value="16">16</option>
		    <option value="20">20</option>
		    <option value="25">25</option>
		    <option value="28">28</option>
		    <option value="32">32</option>
			<option value="36">36</option>
		    </select></td>
		  <td style="width: 33px; color:#003399;border:thin lightgray solid">
                      <?php if($_GET['mbheaderid'] != "") { ?>
		    <input class="textboxdisplaysmall" name="txt_no_mt" id="txt_no_mt" type="text"  tabindex="3" style="width: 33px" size="20" onBlur="calculate();" onKeyPress="return isNumber_deduct(event)" value="<?php echo $measurement_no; ?>" />
                  <?php } else { ?>
		    <input class="textboxdisplaysmall" name="txt_no_mt" id="txt_no_mt" type="text"  tabindex="3" style="width: 33px" size="20" onBlur="calculate();" onKeyPress="return isNumber_deduct(event)" />
                  <?php } ?>
                  </td>
		  <td style="width: 54px; color:#003399;border:thin lightgray solid">
                    <?php if($_GET['mbheaderid'] != "") { ?>
		    <input class="textboxdisplaysmall" name="txt_l_mt" type="text"  tabindex="4" value="<?php echo $measurement_l; ?>" style="width: 54px" onBlur="calculate();" onKeyPress="return isNumber(event)" /></td>
                    <?php } else { ?>
                  <input class="textboxdisplaysmall" name="txt_l_mt" type="text" tabindex="4" style="width: 50px" onBlur="calculate();" onKeyPress="return isNumber(event)" /></td>
                    <?php } ?>
		  <td style="color:#003399;border:thin lightgray solid">
		    <input class="textboxdisplaysmall" name="txt_8" type="text" style="width: 60px" readonly="" /></td>
		  <td style="width: 60px; color:#003399;border:thin lightgray solid">
		    <input class="textboxdisplaysmall" name="txt_10" type="text" style="width: 60px"  readonly=""/></td>
		  <td style="width: 60px; color:#003399;border:thin lightgray solid">
		    <input class="textboxdisplaysmall" name="txt_12" type="text" style="width: 60px" readonly="" /></td>
		  <td style="color:#003399;border:thin lightgray solid">
		    <input class="textboxdisplaysmall" name="txt_16" type="text" style="width: 60px" readonly="" /></td>
		  <td style="width: 60px; color:#003399;border:thin lightgray solid">
		    <input class="textboxdisplaysmall" name="txt_20" type="text" style="width: 60px" readonly="" /></td>
		  <td style="color:#003399;border:thin lightgray solid">
		    <input class="textboxdisplaysmall" name="txt_25" type="text" style="width: 60px" readonly="" /></td>
		  <td style="width: 60px; color:#003399;border:thin lightgray solid">
		    <input class="textboxdisplaysmall" name="txt_28" type="text" style="width: 60px" readonly=""/></td>
		  <td style="width: 60px;color:#003399;border:thin lightgray solid">
		    <input class="textboxdisplaysmall" name="txt_32" type="text" style="width: 60px" readonly="" /></td>
			<td style="width: 60px;color:#003399;border:thin lightgray solid">
		    <input class="textboxdisplaysmall" name="txt_36" type="text" style="width: 60px" readonly="" /></td>
		  <td style="width: 80px;color:#003399;border:thin lightgray solid;" align="center">
		  <?php if($_GET['mbheaderid'] == "") { ?>
		  	<input type="button" class='addbtnstyle' name="btn_mt_add" tabindex="5"   id="btn_mt_add" value="ADD" onClick="addrow_mt();set_tab_index_steel();" style="width: 78px"/>
			<?php } else { ?>
			<input type="button" class='addbtnstyle' name="btn_mt_add" tabindex="5"   id="btn_mt_add" value="ADD" onClick="addrow_mt();set_tab_index_steel();" style="width: 78px"/>
			<?php } ?>
			</td>
    </tr>
    <tr height="20px">
		                                     
		        <td><span id="add_hidden_mt"></span></td>
                        <input type="hidden" value="" name="add_set_a2" id="add_set_a2"/>
                        <?php if($_GET['mbheaderid'] == "") { ?>
			<input type="hidden" name="txt_ca_mt" id="txt_ca_mt" />	
                        <?php }  else { ?>
                        <input type="hidden" name="txt_ca_mt" id="txt_ca_mt" value = "<?php echo $measurement_contentarea; ?>"/>
                        <?php } ?>
    </tr>
</table>
</div>
</div>
									   
<div style="width:100%; overflow-x:hidden; overflow-y: auto;" id="table1">
<div style="height:404px; width:96%; padding-left: 0.999em">
    <table border="1" cellpadding="0" cellspacing="0" align="center" id="mbookdetail" style="width: 1040px; height: 85px;">
							
    <tr style="border:thin solid;height:20px;">
	<td class="labelsmall" style="color:#003399;width: 2%;border:thin lightgray solid">S.no</td>
        <td align="center" class="labelsmall" style="color:#003399;width: 170px;border:thin lightgray solid; border-bottom:none">Description of work</td>
        <td  align="center" class="labelsmall" colspan="5" style="color:#003399;border:thin lightgray solid">Measurements Upto Date</td>
        <td style="border:thin lightgray solid; border-left:hidden"></td>
        <!--<td width="11%" rowspan="2"  align="center" class="labelsmall">&nbsp;</td>-->
    </tr>
    <tr style="border:thin lightgray solid; height:20px;">
	<td style=" width: 2%; border:thin lightgray solid;color:#003399" class="labelsmall"></td>
	<td style="width: 170px; border:thin lightgray solid"></td>
        <td align="center" class="labelsmall" style=" width: 70px;border:thin lightgray solid;color:#003399">No.</td>
        <td align="center" class="labelsmall" style=" width: 70px;border:thin lightgray solid;color:#003399">L.</td>
        <td align="center" class="labelsmall" style=" width: 70px;border:thin lightgray solid;color:#003399">B.</td>
        <td align="center" class="labelsmall" style=" width: 70px;border:thin lightgray solid;color:#003399">D.</td>
        <td align="left" style="width: 70px;border:thin lightgray solid;color:#003399" class="labelsmall">Contents of Area</td>
        <td style="border:thin lightgray solid"></td>
    </tr>
													
    <tr style="height:20px;">
	<td style="width: 2% ;border:thin lightgray solid">
	<input type="text" name="sno" id="sno" class="textboxdisplaysmall" size="4" readonly="" value="1" style="width: 26px" /></td>
        <!--<td  style="display: none;">--><input type="hidden" name="txt_boq" id="txt_boq" class="textboxdisplaysmall" size="90" readonly=""/><!--</td>-->
        <td style="width: 141px;border:thin lightgray solid">
							<input type="text" name="txt_dec_wk" id="txt_dec_wk" style="word-break:break-all;width: 430px;" class="textboxdisplaysmall" tabindex="1" value="<?php if($_GET['mbheaderid'] != "") { echo $descwork; } else { echo ""; } ?>"/></td>
                                                      <td style="width: 66px;border:thin lightgray solid">
                                                       <?php  if($_GET['mbheaderid'] != "") { ?>
							<input type="text" name="txt_no" id="txt_no" class="textboxdisplaysmall" size="10" onBlur="contentorarea()" style="width: 66px" tabindex="2" value="<?php echo $measurement_no; ?>" onKeyPress="return isNumber_deduct(event)" /></td>
                                                      <td style="width: 84px;border:thin lightgray solid">
							<input type="text" name="txt_l" id="txt_l" class="textboxdisplaysmall" size="10" onBlur="contentorarea()" style="width: 84px" tabindex="3" value="<?php echo $measurement_l; ?>" onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 84px;border:thin lightgray solid">
							<input type="text" name="txt_b" id="txt_b" class="textboxdisplaysmall" size="10" onBlur="contentorarea()" style="width: 84px" tabindex="4" value="<?php echo $measurement_b; ?>" onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 84px;border:thin lightgray solid">
							<input type="text" name="txt_d" id="txt_d" class="textboxdisplaysmall" size="10" onBlur="contentorarea()" style="width: 84px" tabindex="5" value="<?php echo $measurement_d; ?>" onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 130px;border:thin lightgray solid">
							<input type="text" name="txt_ca" id="txt_ca" class="textboxdisplaysmall" size="10" readonly="" value="<?php echo $measurement_contentarea; ?>" style="width: 130px"/></td>
                                                      <?php } else { ?>
                                                      <input type="text" name="txt_no" id="txt_no" class="textboxdisplaysmall" size="10" onBlur="contentorarea()" tabindex="2" style="width: 66px" onKeyPress="return isNumber_deduct(event)" /></td>
                                                      <td style="width: 84px;border:thin lightgray solid">
							<input type="text" name="txt_l" id="txt_l" class="textboxdisplaysmall" size="10" onBlur="contentorarea()" style="width: 84px" tabindex="3" onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 84px;border:thin lightgray solid">
							<input type="text" name="txt_b" id="txt_b" class="textboxdisplaysmall" size="10" onBlur="contentorarea()" style="width: 84px" tabindex="4" onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 84px;border:thin lightgray solid">
							<input type="text" name="txt_d" id="txt_d" class="textboxdisplaysmall" size="10" onBlur="contentorarea()" style="width: 84px" tabindex="5" onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 130px;border:thin lightgray solid">
							<input type="text" name="txt_ca" id="txt_ca" class="textboxdisplaysmall" size="10" readonly="" style="width: 130px"/></td>
                                                       <?php } ?>
                                                        
                                                        <td width="100px" style="border:thin lightgray solid" align="center">
							<?php if($_GET['mbheaderid'] == "") 
									{ ?>
                            <input type="button" class="addbtnstyle" name="btn_add" id="btn_add" value="ADD" onClick="addrow();set_tab_index();" tabindex="6"/>
							<?php } else 
									{ ?>
							<input type="button" class="addbtnstyle" name="btn_add" id="btn_add" value="ADD" onClick="addrow();set_tab_index();" tabindex="6"/>
							<?php } ?>
							</td>
    </tr>
    <tr style="height:20px;">
                                                            
                                                          <td><span id="add_hidden"></span></td>
                                                            <input type="hidden" value="" name="add_set_a1" id="add_set_a1"/>	
                                                     
    </tr>
												
</table>
</div>
</div>
<div style="width:100%; overflow-x:hidden; overflow-y: auto;" id="table3" class="hide">
<div style="height:404px; width:96%; padding-left: 0.999em">
 <table border="1" cellpadding="0" cellspacing="0" align="center" id="mbookstruct" style="width: 1040px; height: 85px;">
							
    <tr style="border:thin solid;height:20px;">
	<td class="labelsmall" style="width: 2%;color:#003399;border:thin lightgray solid">S.no</td>
        <td align="center" class="labelsmall" style="color:#003399;width: 170px;border:thin lightgray solid; border-bottom:none">Description of work</td>
        <td  align="center" class="labelsmall" colspan="6" style="color:#003399;border:thin lightgray solid">Measurements Upto Date</td>
        <td style="border:thin lightgray solid; border-left:hidden"></td>
        <!--<td width="11%" rowspan="2"  align="center" class="labelsmall">&nbsp;</td>-->
    </tr>
    <tr style="border:thin lightgray solid;height:20px;">
	<td style="width: 2%; border:thin lightgray solid;color:#003399" class="labelsmall"></td>
	<td style="width: 100px;border:thin lightgray solid"></td>
        <td align="center" class="labelsmall" style="width: 30px;border:thin lightgray solid;color:#003399">No.</td>
        <td align="center" class="labelsmall" style="width: 50px;border:thin lightgray solid;color:#003399">L.</td>
        <td align="center" class="labelsmall" style="width: 70px;border:thin lightgray solid;color:#003399">B.</td>
        <td align="center" colspan="2" class="labelsmall" style="width: 120px;border:thin lightgray solid;color:#003399">D.</td>
        <td align="center" style="width: 70px;border:thin lightgray solid;color:#003399; line-height:80%" class="labelsmall">Contents of Area</td>
        <td style="border:thin lightgray solid"></td>
    </tr>
													
    <tr style="height:20px;">
	<td style="width: 2% ;border:thin lightgray solid">
            <input type="text" name="sno_st" id="sno_st" class="textboxdisplaysmall" size="4" readonly="" value="1" style="width: 26px" /></td>
        <!--<td  style="display: none;">--><input type="hidden" name="txt_boq" id="txt_boq" class="textboxdisplaysmall" size="90" readonly=""/><!--</td>-->
        <td style="width: 355px;border:thin lightgray solid">
            <input type="text" name="txt_dec_wk_st" id="txt_dec_wk_st" tabindex="1" style="word-break:break-all;width: 355px;" class="textboxdisplaysmall" value="<?php if($_GET['mbheaderid'] != "") { echo $descwork; }/* else { echo ""; }*/ ?>" size="53"/></td>
        <td style="width: 66px;border:thin lightgray solid">
        <?php  if($_GET['mbheaderid'] != "") { ?>
            <input type="text" name="txt_no_st" id="txt_no_st" tabindex="2" class="textboxdisplaysmall" size="10" onBlur="contentorarea_st()" style="width: 66px" value="<?php echo $measurement_no; ?>" onKeyPress="return isNumber_deduct(event)" /></td>
        <td style="width: 84px;border:thin lightgray solid">
            <input type="text" name="txt_l_st" id="txt_l_st" tabindex="3" class="textboxdisplaysmall" size="10" onBlur="contentorarea_st()" style="width: 84px" value="<?php echo $measurement_l; ?>" onKeyPress="return isNumber(event)" /></td>
        <td style="width: 84px;border:thin lightgray solid">
            <input type="text" name="txt_b_st" id="txt_b_st" tabindex="4" class="textboxdisplaysmall" size="10" onBlur="contentorarea_st()" style="width: 84px" value="<?php echo $measurement_b; ?>" onKeyPress="return isNumber(event)" /></td>
        <td style="width: 84px;border:thin lightgray solid;border-right:none;">
            <input type="text" name="txt_d_st" id="txt_d_st" tabindex="5" class="textboxdisplaysmall" size="10" onBlur="contentorarea_st()" style="width: 84px" value="<?php echo $measurement_d; ?>" onKeyPress="return isNumber(event)" />
			</td>
		<td style="width: 84px;border:thin lightgray solid; border-left:none; border-right:none; border-top:none">	
		<input type="hidden" name="hide_depth_unit" id="hide_depth_unit" tabindex="6" value="<?php echo $struct_depth_unit; ?>" >
            <select name="cmb_depthunit_st" id="cmb_depthunit_st" style="width:100px; height:21px;">
                <option value="">-Unit-</option>
                <!--<option value="cum">cum</option>-->
                <option value="kg">kg</option>
                <!--<option value="mt">mt</option>
                <option value="sqm">sqm</option>-->
            </select>
        </td>
        <td style="width:120px;border:thin lightgray solid">
            <input type="text" name="txt_ca_st" id="txt_ca_st" class="textboxdisplaysmall" size="10" readonly="" value="<?php echo $measurement_contentarea; ?>" style="width:120px"/></td>
            <?php } else { ?>
            <input type="text" name="txt_no_st" id="txt_no_st"  tabindex="2"class="textboxdisplaysmall" size="10" onBlur="contentorarea_st()" style="width: 66px" onKeyPress="return isNumber_deduct(event)" /></td>
        <td style="width: 84px;border:thin lightgray solid">
            <input type="text" name="txt_l_st" id="txt_l_st" tabindex="3" class="textboxdisplaysmall" size="10" onBlur="contentorarea_st()" style="width: 84px" onKeyPress="return isNumber(event)" /></td>
        <td style="width: 84px;border:thin lightgray solid">
            <input type="text" name="txt_b_st" id="txt_b_st" tabindex="4" class="textboxdisplaysmall" size="10" onBlur="contentorarea_st()" style="width: 84px" onKeyPress="return isNumber(event)" /></td>
        <td style="width: 84px;border:thin lightgray solid;border-right:none;">
            <input type="text" name="txt_d_st" id="txt_d_st" tabindex="5" class="textboxdisplaysmall" size="10" onBlur="contentorarea_st()" style="width: 84px" onKeyPress="return isNumber(event)" />
		</td>
		<td style="width: 84px;border:thin lightgray solid; border-left:none; border-right:none; border-top:none">
        <select name="cmb_depthunit_st" id="cmb_depthunit_st" tabindex="6" style="width:84px; height:21px;">
            <option value="">-Unit-</option>
               <!-- <option value="cum">cum</option>-->
                <option value="kg">kg</option>
                <!--<option value="mt">mt</option>
                <option value="sqm">sqm</option>-->
            </select>
        </td>
        <td style="width: 120px;border:thin lightgray solid">
            <input type="text" name="txt_ca_st" id="txt_ca_st" class="textboxdisplaysmall" size="10" readonly="" style="width: 120px"/></td>
        <?php } ?>
                                                        
        <td width="100px" style="border:thin lightgray solid" align="center">
	<?php if($_GET['mbheaderid'] == "") { ?>
            <input type="button" class="addbtnstyle" name="btn_st_add"  tabindex="7"  id="btn_st_add" value="ADD" onClick="addrow_st();set_tab_index_struct();"/>
	<?php } else { ?>
            <input type="button" class="addbtnstyle" name="btn_st_add" tabindex="7"   id="btn_st_add" value="ADD" onClick="addrow_st();set_tab_index_struct();"/>
	<?php } ?>
	</td>
    </tr>
    <tr>
                                                            
        <td><span id="add_hidden_st"></span></td>
        <input type="hidden" value="" name="add_set_a3" id="add_set_a3"/>	
		
                                                     
    </tr>
												
</table>   
</div>
</div>
		<input type="hidden" name="hid_deleteflag" id="hid_deleteflag"/>
        <?php if($_GET['mbheaderid'] != "") { ?>
		<input type="hidden" value="<?php echo $_GET['mbheaderid']; ?>" name="hid_mbheaderid" 	id="hid_mbheaderid"/>
		<input type="hidden" value="<?php echo $_GET['mbdetailid']; ?>" name="hid_mbdetailid" 	id="hid_mbdetailid"/>
		<input type="hidden" value="<?php echo $_GET['sheetid']; 	?>" name="hid_sheetid" 		id="hid_sheetid"/>
		<input type="hidden" value="<?php echo $_GET['subdivid']; 	?>" name="hid_subdivid" 	id="hid_subdivid"/>
		<input type="hidden" value="<?php echo $_GET['divid']; 		?>" name="hid_divid" 		id="hid_divid"/>
		
		<?php } ?>
                                            </td>
                                        </tr>

                                        <tr><td><input type="hidden" value="<?php if($_GET['mbheaderid'] != "") { echo $measure_type; } else { echo ""; } ?>" name="txt_measure_type" id="txt_measure_type"/></td></tr>
										 <tr>
                                            <td colspan="5" align="center" height="32px">
											
                                            <input type="hidden" class="text" name="submit" value="true" />
											<input type="hidden"  id="sno_hide" name="sno_hide">
											<!--<button type="button" class="btn" id="submit" data-type="submit" value=" Submit ">Submit</button>-->
											<?php if($_GET['mbheaderid'] == "") { ?>
											
											<div style="text-align:center">
												<div class="buttonsection">
													<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
												</div>
														 
												<div class="buttonsection">
													<input type="submit" name="submit" value=" Submit " id="submit"/>
												</div>
														 
												<div class="buttonsection">
													<input type="button" name="view" value=" View " id="view" class="backbutton"/>
												</div>
															
												<div class="buttonsection">
													<input type="reset" name="reset" value=" Reset " id="reset"/>
												</div>	
											</div>
											<?php 
											} 
											else 
											{ ?>
											<input type="submit" name="update" value=" Update " />	&nbsp;&nbsp;
											<a href="ViewMeasurementEntryList_Edit.php"><input type="button" class="backbutton" name="back_home" value=" Back " id="back_home"/></a>
											<!--<input type="submit" name="back" value=" Back " />	-->
                                                                                        <!--<input type="image" src="Buttons/Delete_Normal.png" onMouseOver="this.src='Buttons/Delete_Over.png';" onMouseOut="this.src='Buttons/Delete_Normal.png';" class="btn" name="delete" value=" Submit " onclick="delete_entry();" />-->	
											<?php 
											} 
											?>
                                        </td>
                                        </tr>
										 
                                  </table>
							<div id="measurements" title="Measurements Type">
							<p><label><input type="radio" name="rad_measure_type" id="rad_measure_type" value="g">General</label>
							&nbsp;&nbsp;&nbsp;<label><input type="radio" name="rad_measure_type" id="rad_measure_type" value="s">Steel</label></p>
							</div>
                         
                            <div class="col2">
							<?php 
							if ($msg != '') 
							{
								//echo $msg;
                            } 
							?>
							</div>
                                </div>
                        </blockquote>
                    </div>

                </div>
            </div>
    </form>
            <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
              <script>
				var msg = "<?php echo $msg; ?>";
				var titletext = "";
				document.querySelector('#pageload').onload = function(){
				if(msg != "")
				{
					swal({
						title: titletext,
						text: msg,
						timer: 4000,
						showConfirmButton: true
					});
				}
				var work_order_no = document.form.hid_lock_unlock_worder.value;
		if((work_order_no != "") && (work_order_no != 0))
		{
			var lock_value = document.form.hid_lock_unlock.value;
			if(lock_value == "locked")
			{
				document.getElementById("lock").style.display = "";
				document.getElementById("unlock").style.display = "none";
			}
			else
			{
				document.getElementById("lock").style.display = "none";
				document.getElementById("unlock").style.display = "";
			}
			var lock_value = document.form.hid_lock_unlock.value;
			if(lock_value == "locked")
			{
				document.form.workorderno.value = work_order_no;
				func_item_no();
			}
		}
			};
    	$(function() { 
        
		/*$("#unlock").click(function() { 
		   var _this = $(this);
		   var current = _this.attr("src");
		   var swap = _this.attr("data-swap");     
		 _this.attr('src', swap).attr("data-swap",current);   
		});*/
        
        $("#txt_no").keyup(function(){
            var numb = $("#txt_no").val();
            var i=""; var splitval = "";
            var splitval = numb.split("");
            for(i=1;i<splitval.length;i++)
            {
                if(numb.charAt(i) == "-")
                {
                    //numb.charAt(i) = "";
                    alert("invalid input");
                    document.form.txt_no.focus();
                   // document.form.txt_no.value = numb.slice(0,i);
                    return false;
                }

            }
        });
        $("#txt_no_st").keyup(function(){
            var numb = $("#txt_no_st").val();
            var i=""; var splitval = "";
            var splitval = numb.split("");
            for(i=1;i<splitval.length;i++)
            {
                if(numb.charAt(i) == "-")
                {
                    alert("invalid input");
                    document.form.txt_no_st.focus();
                    return false;
                }

            }
        });
        $("#txt_no_mt").keyup(function(){
            var numb = $("#txt_no_mt").val();
            var i=""; var splitval = "";
            var splitval = numb.split("");
            for(i=1;i<splitval.length;i++)
            {
                if(numb.charAt(i) == "-")
                {
                    alert("invalid input");
                    document.form.txt_no_mt.focus();
                    return false;
                }

            }
        });
        
        $("#datepicker").change(function(){
            var measure_date = $("#txt_measurement_date").val();
			var new_date = $("#datepicker").val();
            var d1 = measure_date.split("-");
			var d2 = new_date.split("-");
			var m_date = new Date(d1[2], d1[1]-1, d1[0]);
			var c_date = new Date(d2[2], d2[1]-1, d2[0]);
			if(m_date>c_date)
			{
				//alert("Bill already generated for this date. Please Choose other date.");
				sweetAlert("Bill already generated for this date. Please Choose other date.", "", "error");
				var month_names = new Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
				var d = new Date(); 
				var curr_date = d.getDate();
				var curr_month = d.getMonth();
				var curr_year = d.getFullYear();
				//var today_date = curr_date+"-"+month_names[curr_month]+"-"+curr_year;
				var today_date = "";
				//$("#datepicker").val(today_date);
				$("#datepicker").val(today_date);
				return false;
			}
        });
		var measure_type = "<?php echo $measure_type; ?>";
		var mbheaderid = "<?php echo $_GET['mbheaderid']; ?>";
		var measurement_contentarea = "<?php echo $measurement_contentarea; ?>";
		var measurement_dia = "<?php echo $measurement_dia; ?>";
		if(mbheaderid != "")
		{ 
			if(measure_type=="s")
			{   
				document.getElementById("table1").className = "hide";
				document.getElementById("table2").className = "";
                                document.getElementById("table3").className = "hide";
				if(measurement_dia != "")
				{
					if(measurement_dia == 8){ document.form.txt_8.value = measurement_contentarea; }
					if(measurement_dia == 10){ document.form.txt_10.value = measurement_contentarea; }
					if(measurement_dia == 12){ document.form.txt_12.value = measurement_contentarea; }
					if(measurement_dia == 16){ document.form.txt_16.value = measurement_contentarea; }
					if(measurement_dia == 20){ document.form.txt_20.value = measurement_contentarea; }
					if(measurement_dia == 25){ document.form.txt_25.value = measurement_contentarea; }
					if(measurement_dia == 28){ document.form.txt_28.value = measurement_contentarea; }
					if(measurement_dia == 32){ document.form.txt_32.value = measurement_contentarea; }
					if(measurement_dia == 36){ document.form.txt_36.value = measurement_contentarea; }
				}
				else
				{
					if(measurement_dia == 8){ document.form.txt_8.value = ""; }
					if(measurement_dia == 10){ document.form.txt_10.value = ""; }
					if(measurement_dia == 12){ document.form.txt_12.value = ""; }
					if(measurement_dia == 16){ document.form.txt_16.value = ""; }
					if(measurement_dia == 20){ document.form.txt_20.value = ""; }
					if(measurement_dia == 25){ document.form.txt_25.value = ""; }
					if(measurement_dia == 28){ document.form.txt_28.value = ""; }
					if(measurement_dia == 32){ document.form.txt_32.value = ""; }
					if(measurement_dia == 36){ document.form.txt_36.value = ""; }
				}
			}
			else if(measure_type=="st")
			{
				var depthunit_st = $("#hide_depth_unit").val();
				$("#cmb_depthunit_st").val(depthunit_st)
				document.getElementById("table1").className = "hide";
				document.getElementById("table2").className = "hide";
                document.getElementById("table3").className = "";
				
			}
            else
			{
				document.getElementById("table1").className = "";
				document.getElementById("table2").className = "hide";
                document.getElementById("table3").className = "hide";
			}

		}
		 $( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: "dd-mm-yy",
	   maxDate: new Date,
	   defaultDate: new Date,
    });	
	
     	function DisplayPer(){
            var subitemnoValue = $("#subitemno option:selected").attr('value');
            $.post("PerService.php", {subitemno:subitemnoValue}, function(data){
            $('#remarks').val(data);
            });
        }
		
	
        $("#subitemno").bind("change", function() {
            DisplayPer();
         });
         $("#itemno").bind("change", function() {
            $('#remarks').val('');
         });
		 
		$.fn.validateworkorder = function(event) { 
					if($("#workorderno").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
		$.fn.validateDate = function(event) {	
				if($("#datepicker").val()==0){ 
					var a="Please select date.";
					$('#val_date').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_date').text(a);
				}
			}
			
		$.fn.validateitemno = function(event) {	
			if($("#itemno").val()==0){ 
				var a="Please select the item number";
				$('#val_item').text(a);
				event.preventDefault();
				event.returnValue = false;
					//return false;
				}
				else{
				var a="";
				$('#val_item').text(a);
				}
			}
			
		$.fn.validatesubitemno = function(event) {	
				var itemno = $("#itemno").val();
				if((itemno != "") && (itemno != 0))
				{
					var length = $('#subitemno > option').length;
					if(length > 1)
					{ 
						var subitemno = $("#subitemno").val();
						if((subitemno == 0) && (subitemno != ""))
						{
							$('#val_sub').css('padding-left', '9em');
							var a = "Select Sub item no.";
							$('#val_sub').text(a);
							event.returnValue = false;//for ie
							event.preventDefault();//for chrome
							//return false;//for firefox
						}
						else
						{
							var a="";
							$('#val_sub').css('padding-left', '0em');
							$('#val_sub').text(a);		
						}
					}
				}
		}
			
		$.fn.validatesubsubitemno = function(event) {	
				var subitemno = $("#subitemno").val();
				if(subitemno == "")
				{
					var length = $('#subsubitemno > option').length;
					if(length > 1)
					{ 
						var subsubitemno = $("#subsubitemno").val();
						if((subsubitemno == 0) && (subsubitemno != ""))
						{
							$('#val_subsub').css('padding-left', '19em');
							var a = "Select Sub item 2.";
							$('#val_subsub').text(a);
							event.returnValue = false;//for ie
							event.preventDefault();//for chrome
							//return false;//for firefox
						}
						else
						{
							var a="";
							$('#val_subsub').css('padding-left', '0em');
							$('#val_subsub').text(a);		
						}
					}
				}
		}
		$.fn.validatesubsubsubitemno = function(event) {	
				var subsubitemno = $("#subsubitemno").val();
				if(subsubitemno == "")
				{
					var length = $('#subsubsubitemno > option').length;
					if(length > 1)
					{ 
						var subsubsubitemno = $("#subsubsubitemno").val();
						if((subsubsubitemno == 0) && (subsubsubitemno != ""))
						{
							$('#val_subsubsub').css('padding-left', '29em');
							var a = "Select Sub item 3.";
							$('#val_subsubsub').text(a);
							event.returnValue = false;//for ie
							event.preventDefault();//for chrome
							//return false;//for firefox
						}
						else
						{
							var a="";
							$('#val_subsubsub').css('padding-left', '0em');
							$('#val_subsubsub').text(a);		
						}
					}
				}
		}
			
        //if(mbheaderid == "")
        //{
		$.fn.validaterow = function(event) 
			{	
				if($("#txt_measure_type").val() == "s")
				{
					if($("#add_set_a2").val()=="" || $("#add_set_a2").val()==2 )
					{ 
						//var a="Please select the sub item number";
						//$('#val_sub').text(a);
						alert("Enter atleast one row");
						event.returnValue = false;//for ie
						event.preventDefault();//for chrome
					}                                           
				}
				
				else if($("#txt_measure_type").val() == "st")
				{
				if($("#add_set_a3").val()=="" || $("#add_set_a3").val()==2 )
					{ 
						//var a="Please select the sub item number";
						//$('#val_sub').text(a);
						alert("Enter atleast one row");
						event.returnValue = false;//for ie
						event.preventDefault();//for chrome
					}      
				} 
				else
				{
				if($("#add_set_a1").val()=="" || $("#add_set_a1").val()==2 )
					{ 
						//var a="Please select the sub item number";
						//$('#val_sub').text(a);
						alert("Enter atleast one row");
						event.returnValue = false;//for ie
						event.preventDefault();//for chrome
					}      
				}
		 }
         //}
		$("#submit").click(function(event){
            $(this).validateitemno(event);
			$(this).validateDate(event);
			$(this).validatesubitemno(event);
			$(this).validatesubsubitemno(event);
			$(this).validatesubsubsubitemno(event);
			$(this).validateworkorder(event);
			$(this).validaterow(event);
         });
		 
		$("#workorderno").change(function(event){
           $(this).validateworkorder(event);
         });
		 $("#datepicker").change(function(event){
           $(this).validateDate(event);
         });
		  $("#subitemno").change(function(event){
           $(this).validatesubitemno(event);
         });
		  $("#subsubitemno").change(function(event){
           $(this).validatesubsubitemno(event);
         });
		  $("#subsubsubitemno").change(function(event){
           $(this).validatesubsubsubitemno(event);
         });
		 $("#itemno").change(function(event){
           $(this).validateitemno(event);
         });
		 
		var buttons = {
				View: save,
                Cancel: cancel,
        };

        $('#view').click(function() {
			var workorderno = $("#workorderno").val();
			var temp = 0;
			if((workorderno == "") || (workorderno == 0))
			{
           		swal("Please Select Work Name...!", "", "error");
		   	}
			else
			{
				temp = 2;
			}
			var itemno = $("#itemno").val();
			var subitemno = $("#subitemno").val();
			var subsubitemno = $("#subsubitemno").val();
			var subsubsubitemno = $("#subsubsubitemno").val();
			var length1 = $('#subitemno > option').length;
			var length2 = $('#subsubitemno > option').length;
			var length3 = $('#subsubsubitemno > option').length;
			if(length1>1)
			{
				if((subitemno == 0) && (subitemno != ""))
				{
					swal("Please Select Sub Item 1...!", "", "error");
					temp = 0;
				}
				else
				{
					temp = 1;
				}
			}
			if(length2>1)
			{
				if((subsubitemno == 0) && (subsubitemno != ""))
				{
					swal("Please Select Sub Item 2...!", "", "error");
					temp = 0;
				}
				else
				{
					temp = 1;
				}
			}
			if(length3>1)
			{
				if((subsubsubitemno == 0) && (subsubsubitemno != ""))
				{
					swal("Please Select Sub Item 3...!", "", "error");
					temp = 0;
				}
				else
				{
					temp = 1;
				}
			}
			if((length1<=1) && ((length2<=1)) && ((length3<=1)))
			{
				if((itemno != 0) && (itemno != ""))
				{
					temp = 1;
				}
			}
			if(temp == 2)
			{
				openDialog('#measurements');
			}
			if(temp == 1)
			{
				save(temp);
			}
        });

        $('#measurements').dialog({
                autoOpen: false,
                modal:    true,
                buttons:  buttons
        }); 
	  
	  });
			 
	
	
 </script>
		      
 <script type="text/javascript">

function openDialog(sel) {
    $(sel).dialog('open');
}

function cancel() {
    $(this).dialog('close');
}

function save(x) 
{
	var selectedType = "", itemno = "", subitemno = "", subsubitemno = "",subsubsubitemno = "";
	if(x != 1)
	{
		var selectedType = $('input:radio[name=rad_measure_type]:checked').val();
		if($('input:radio[name=rad_measure_type]:checked').length == 0)
		{
			swal("Please Select Measurement Type...!", "", "error");
			event.returnValue = false;//for ie
						event.preventDefault();//for chrome
		}
	}
	var itemno = $("#itemno").val();
	var subitemno = $("#subitemno").val();
	var subsubitemno = $("#subsubitemno").val();
	var subsubsubitemno = $("#subsubsubitemno").val();
	var workorderno = $("#workorderno").val();
	/*if((subsubsubitemno != "") && (subsubsubitemno != 0))
	{
		subdivid = subsubsubitemno;
	}
	else if((subsubitemno != "") && (subsubitemno != 0))
	{
		subdivid = subsubitemno;
	}
	else if((subitemno != "") && (subitemno != 0))
	{
		subdivid = subitemno;
	}
	else
	{
		if((itemno != "") && (itemno != 0))
		{
			divid = itemno;
		}
		else
		{
			subdivid = 0;
			divid = 0;
		}
		
	}*/
	var inputStr = workorderno+"*"+itemno+"*"+subitemno+"*"+subsubitemno+"*"+subsubsubitemno+"*"+selectedType;
	//var inputStr = workorderno+"*"+divid+"*"+subdivid+"*"+selectedType;
	url = "ViewMeasurementEntryList_Edit.php?viewdata=" + inputStr;
	window.location.replace(url);
   	$(this).dialog('close');
}

</script>   

<style>
.hide
{
height:0px; 
width:98%; 
visibility:hidden; 
line-height:0px; 
font-size:0px;
}
.hide_icon
{
	visibility:hidden;
}

</style>
</body>
</html>