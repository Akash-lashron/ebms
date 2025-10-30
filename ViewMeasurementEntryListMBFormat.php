<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
include "sysdate.php";
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy.'-'.$mm.'-'.$dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
function check_measurement_date($sheetid,$get_date)
{
	$get_mindate_sql     =  "select min(fromdate) from measurementbook WHERE sheetid = '$sheetid'";
	$get_mindate_query   =  mysql_query($get_mindate_sql);
	$min_fromdate        =  @mysql_result($get_mindate_query,'fromdate');
	$get_maxdate_sql     =  "select max(todate) from measurementbook WHERE sheetid = '$sheetid'";
	$get_maxdate_query   =  mysql_query($get_maxdate_sql);
	$max_todate 		 =  @mysql_result($get_maxdate_query,'fromdate');
	$start_ts 			 =  strtotime($min_fromdate);
	$end_ts 			 =  strtotime($max_todate);
	$user_ts 			 =  strtotime($get_date);
	if(($user_ts >= $start_ts) && ($user_ts <= $end_ts))
	{
		return 0;
	}
	else
	{
		return 1;
	}
}
if($_GET['TempVal'] != "")
{
	$temp = $_GET['TempVal'];
}
  $fromdate 		= ""; $todate = "";
  $sheetid 			= $_SESSION['sheet-id'];
  $divid 			= $_SESSION['item'];
  $subdiv_id 		= $_SESSION['sub-item'];
  $measurementtype 	= $_SESSION['measurementtype'];
  $zone_name 	= $_SESSION['view_zone_name'];
  if($zone_name != "")
  {
  	$zone_clause = " AND c.zone_id = ".$zone_name . " ";
  }
  else
  {
  	$zone_clause = "";
  }
  if($zone_name == "all")
  {
	$zone_clause = "";
  }
  $where_clause = "";
  if(($_SESSION['from-date'] != "") && ($_SESSION['to-date'] != ""))
  {
    $fromdate = dt_format($_SESSION['from-date']);
    $todate = dt_format($_SESSION['to-date']);
  }
  if($sheetid != "")
  {
    if(($divid == 0))
    {
        if($measurementtype == "")
        {
            if(($fromdate == "") &&($todate == ""))
            {
                $where_clause = "";
                $where_clause_meastype = "";
            }
            else
            {
               $where_clause = " AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
               $where_clause_meastype = "";
            }
        }
        else
        {
            if($measurementtype == "S")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = "";
                    $where_clause_meastype = " AND b.measure_type = 'S' ";
                }
                else
                {
                   $where_clause = " AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type = 'S' ";
                }
            }
            if($measurementtype == "G")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = "";
                    $where_clause_meastype = " AND b.measure_type != 'S' ";
                }
                else
                {
                   $where_clause = " AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type != 'S' ";
                }
            }
        }
    }
  if(($divid == "all"))
    {
        if($measurementtype == "")
        {
            if(($fromdate == "") &&($todate == ""))
            {
                $where_clause = "";
                $where_clause_meastype = "";
            }
            else
            {
               $where_clause = " AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
               $where_clause_meastype = "";
            }
        }
        else
        {
            if($measurementtype == "S")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = "";
                    $where_clause_meastype = " AND b.measure_type = 'S' ";
                }
                else
                {
                   $where_clause = " AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type = 'S' ";
                }
            }
            if($measurementtype == "G")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = "";
                    $where_clause_meastype = " AND b.measure_type != 'S' ";
                }
                else
                {
                   $where_clause = " AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type != 'S' ";
                }
            }
        }
    }
    if(($divid != "all") && ($divid != 0))
    {
        if($measurementtype == "")
        {
            if(($fromdate == "") &&($todate == ""))
            {
                $where_clause = " AND c.subdivid= ".$subdiv_id;
                $where_clause_meastype = "";
            }
            else
            {
               $where_clause = " AND c.subdivid = ".$subdiv_id." AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'";
               $where_clause_meastype = "";
            }
        }
        else
        {
            if($measurementtype == "S")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = " AND c.subdivid= ".$subdiv_id;
                    $where_clause_meastype = " AND b.measure_type = 'S' ";
                }
                else
                {
                   $where_clause = " AND c.subdivid = ".$subdiv_id." AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type = 'S' ";
                }
            }
            if($measurementtype == "G")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = " AND c.subdivid= ".$subdiv_id;
                    $where_clause_meastype = " AND b.measure_type != 'S' ";
                }
                else
                {
                   $where_clause = " AND c.subdivid = ".$subdiv_id." AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type != 'S' ";
                }
            }
        }
    }
  }

 if(isset($_POST['back']))
 {
     header('Location: ViewMeasurementEntry.php');
 }
 if(isset($_POST['delete']))
 {
    $delete_id_list = $_POST['ch_deleteid'];
    $cnt = count($delete_id_list);
	$prev_mbheaderid = ""; $header_id_arr = array();
    for($i = 0; $i<$cnt; $i++)
    {
		$deleteid = $delete_id_list[$i];
		$exp_delete_id = explode("*",$deleteid);
		$mbdetail_id = $exp_delete_id[0];
		$mbheader_id = $exp_delete_id[1];
		if($mbheader_id != $prev_mbheaderid)
		{
			array_push($header_id_arr,$mbheader_id);
		}
		$delete_mbdetail_query = "delete from mbookdetail where mbdetail_id = '$mbdetail_id'";
		$delete_mbdetail_sql = mysql_query($delete_mbdetail_query);
    }
	for($j=0; $j<count($header_id_arr); $j++)
	{
		$mbheader_id_curr = $header_id_arr[$j];
		if($mbheader_id_curr != "")
		{
			$select_mbdetail_query = "select * from mbookdetail where mbheaderid = '$mbheader_id_curr'";
			$select_mbdetail_sql = mysql_query($select_mbdetail_query);
			if($select_mbdetail_sql == true)
			{
				if(mysql_num_rows($select_mbdetail_sql) == 0)
				{
					$delete_mbheader_query = "delete from mbookheader where mbheaderid = '$mbheader_id_curr'";
					$delete_mbheader_sql = mysql_query($delete_mbheader_query);
				}
			}
		}
	}
	header('Location: ViewMeasurementEntryList.php');
 }
 if(isset($_POST['edit']))
 {
 	$edit_id_list 		= $_POST['ch_deleteid'];
	$measurementtype 	= $_POST['txt_measurementtype'];
	$cnt = count($edit_id_list);
	if($cnt>0)
	{
		$_SESSION['edit_id_list'] = $edit_id_list;
  		header('Location: Measurement_Edit_Multiple.php?edit=1&type='.$measurementtype);
	}
 }
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<script src="dashboard/MyView/bootstrap.min.js"></script>
<link rel="stylesheet" href="dashboard/MyView/TreeLabelStyle.css">
<?php require_once "Header.html"; ?>
<!--<style>
	.container{
		display:table;
		width:100%;
		border-collapse: collapse;
    }
	.table-row{  
		display:table-row;
		text-align: left;
	}
	.col{
		display:table-cell;
		border: 1px solid #CCC;
		padding:1px;
	}
	.chbox-style{
		height: 12px;   
		width: 15px;
	}
</style>-->
	<script type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</script>

    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow:auto;">
                          	<div class="title">View Measurements - MBook Format</div>
                            <div class="container" style="padding:8px;">
									<a href="#demo1" class="btn btn-info title" data-toggle="collapse" style="width:100%">
										<div class="col-sm-1" style="text-align:center; line-height:40px;">Item No</div>
										<div class="col-sm-5" style="line-height:40px;">
											Item Description / Shortnotes
										</div>
										<div class="col-sm-2" style="line-height:40px;text-align:right">Since Last Qty.</div>
										<div class="col-sm-1" style="line-height:40px;">Unit</div>
										<div class="col-sm-1" style="line-height:40px;">Rate (Rs)</div>
									</a>
												
									<div id="demo1" class="collapse" style="width:100%;">
										<div class="test" style="width:100%">
											<div class="col-sm-12">
												<div class="inst-content">
													<div class="commentBoxSection"> rgtrehgththjt</div>
												</div>
											</div>
										</div>
									</div>
                            </div>
							<br/>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
								<input type="submit" name="edit" id="edit" value=" Edit " />
								</div>
								<div class="buttonsection">
								<input type="submit" name="delete" id="delete" value=" Delete " />
								</div>
							</div>
                        </blockquote>
                    </div>
                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
		   	 $(function () {
			 	//BootstrapDialog.alert();
			 });
		   </script>
        </form>
    </body>
</html>
