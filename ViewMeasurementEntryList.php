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
  $sheetid 			= $_SESSION['sheet-id']; //print $sheetid;
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
 
/* if(isset($_POST['delete1'])){
 	echo "Hi 1";exit;
 }*/
 $DeleteRes = 0; 	$DeleteResCount = 0;
 if(isset($_POST['delete1']))
 {
    $delete_id_list = $_POST['ch_deleteid'];
	$mbtype 	= $_POST['txt_measurementtype'];
	//print_r($delete_id_list);
    $cnt = count($delete_id_list);
	$prev_mbheaderid = ""; $header_id_arr = array(); $ZoneIdArr = array();
    for($i = 0; $i<$cnt; $i++)
    {
		$deleteid = $delete_id_list[$i];
		//echo $cnt[$i];
		$exp_delete_id = explode("*",$deleteid);
		$mbdetail_id = $exp_delete_id[0];
		$mbheader_id = $exp_delete_id[1];
		$del_zone_id = $exp_delete_id[2];
		if($mbheader_id != $prev_mbheaderid)
		{
			array_push($header_id_arr,$mbheader_id);
		}
		$delete_mbdetail_query = "delete from mbookdetail where mbdetail_id = '$mbdetail_id'";
		$delete_mbdetail_sql = mysql_query($delete_mbdetail_query);
		if($delete_mbdetail_sql == true){
			$DeleteResCount++;
		}
		if(in_array($del_zone_id,$ZoneIdArr)){
			/// Already Exist
		}else{
			array_push($ZoneIdArr,$del_zone_id);
		}
		//echo $delete_mbdetail_query;
		//exit;
      // $delete_sql = "UPDATE mbookdetail SET mbdetail_flag = 'd' WHERE mbdetail_id = '$mbdetaild[$i]'" ;
       //$delete_query = mysql_query($delete_sql); 
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
	$sheetid 	= $_SESSION['sheet-id'];
	if($mbtype == "S"){
		$flag = 2;
	}else{
		$flag = 1;
	}
	
	$MaxRbn1 = 0; $MaxRbn2 = 0; $CurreRbn = 0; 
	$MaxRbnQuery 	= "SELECT max(rbn) as maxrbn FROM measurementbook WHERE sheetid = '$sheetid'";
	$MaxRbnSql 		= mysql_query($MaxRbnQuery);
	if($MaxRbnSql == true){
		if(mysql_num_rows($MaxRbnSql)>0){
			$MaxRbnList = mysql_fetch_object($MaxRbnSql);
			$MaxRbn1 = $MaxRbnList->maxrbn;
		}
	}
	
	$MaxRbnQuery1 = "SELECT max(rbn) as maxrbn1 FROM mbookgenerate_staff WHERE sheetid = '$sheetid' and rbn > '$MaxRbn1'";
	$MaxRbnSql1 = mysql_query($MaxRbnQuery1);
	if($MaxRbnSql1 == true){
		if(mysql_num_rows($MaxRbnSql1)>0){
			$MaxRbnList1 = mysql_fetch_object($MaxRbnSql1);
			$MaxRbn2 = $MaxRbnList1->maxrbn1;
		}
	}
	
	//print_r($ZoneIdArr);exit;
	
	if($MaxRbn2 != 0){
		$DeleteQuery2 	= "delete from mbookgenerate where sheetid = '$sheetid' and rbn = '$MaxRbn2'";
		$DeleteSql2 	= mysql_query($DeleteQuery2);
				
		$DeleteQuery3 	= "delete from measurementbook_temp where sheetid = '$sheetid' and rbn = '$MaxRbn2'";
		$DeleteSql3 	= mysql_query($DeleteQuery3);
				
		$DeleteQuery4 	= "delete from abstractbook where sheetid = '$sheetid' and rbn = '$MaxRbn2'";
		$DeleteSql4 	= mysql_query($DeleteQuery4);
			
		//$ZoneList = $_POST['txt_zone_list'];
		//$ExpZoneList = explode(",",$ZoneList);
		for($j=0; $j<count($ZoneIdArr); $j++){
			$mbzoneid = $ZoneIdArr[$j];
			$DeleteQuery1 	= "delete from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$MaxRbn2' and zone_id = '$mbzoneid' and flag = '$flag'";
			$DeleteSql1 	= mysql_query($DeleteQuery1);
			$DeleteQuery5 	= "delete from mymbook where sheetid = '$sheetid' and rbn = '$MaxRbn2' and ((zone_id = '$mbzoneid' and mtype = '$mbtype') OR genlevel = 'composite' OR genlevel = 'abstract') ";
			$DeleteSql5 	= mysql_query($DeleteQuery5);
			//echo $DeleteQuery1."<br/>";
			//echo $DeleteQuery5."<br/>";
		}
	}
	 
	 
 }
 if(isset($_POST['edit1']))
 {
 	$edit_id_list 		= $_POST['ch_deleteid'];
	$measurementtype 	= $_POST['txt_measurementtype'];
	$cnt = count($edit_id_list);
	if($cnt>0)
	{
		$_SESSION['edit_id_list'] = $edit_id_list;
		//echo $_SESSION['edit_id_list']; exit;
  		header('Location: Measurement_Edit_Multiple.php?edit=1&type='.$measurementtype);
	}
 }
 
$get_mindate_sql     =  "select min(fromdate) from measurementbook WHERE sheetid = '$sheetid'";
$get_mindate_query   =  mysql_query($get_mindate_sql);
$min_fromdate        =  @mysql_result($get_mindate_query,'fromdate');
$get_maxdate_sql     =  "select max(todate) from measurementbook WHERE sheetid = '$sheetid'";
$get_maxdate_query   =  mysql_query($get_maxdate_sql);
$max_todate 		 =  @mysql_result($get_maxdate_query,'todate');

$start_ts 			 =  strtotime($min_fromdate);
$end_ts 			 =  strtotime($max_todate);

$get_maxrbn_sql     =  "select distinct(rbn) as curr_rbn from measurementbook_temp WHERE sheetid = '$sheetid'";
$get_maxrbn_query   =  mysql_query($get_maxrbn_sql);
$rbn       			=  @mysql_result($get_maxrbn_query,'curr_rbn');


$DeleteEdit = 0;
$check_send_acc_sql = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn'";// and (mb_ac = 'SA'";
$check_send_acc_query = mysql_query($check_send_acc_sql);
if($check_send_acc_query == true){
	if(mysql_num_rows($check_send_acc_query)>0){
		while($SAList = mysql_fetch_object($check_send_acc_query)){
			if($SAList->mb_ac == 'SC'){
				$DeleteEdit = 1;
			}if($SAList->sa_ac == 'SC'){
				$DeleteEdit = 1;
			}if($SAList->ab_ac == 'SC'){
				$DeleteEdit = 1;
			}
		}
	}else{
		$DeleteEdit = 1;
	}
}

//echo $DeleteEdit;

//echo $max_todate;exit;
/*if($send_acc > 0){
	$send_acc_check = 0;
}else{
	$send_acc_check = 1;
}*/

//arg1 =====> check for pass order confirmed date / Previous RAB date
// arg1 = 1 we can allow to edit
// arg1 = 0 dont allow to edit
//arg2 =====> check for sent to accounts status
// arg2 = 1 we can allow to edit
// arg2 = 0 dont allow to edit
function CheckEditDelete($arg1,$arg2){
	if($arg1 == 0){
		$Allow = "N";
	}else{
		/*if(($arg1 == 0)&&($arg2 == 0)){
			$Allow = "N";
		}elseif(($arg1 == 1)&&($arg2 == 0)){
			$Allow = "N";
		}elseif(($arg1 == 1)&&($arg2 == 1)){
			$Allow = "Y";
		}else{
			$Allow = "Y";
		}*/
		if($arg2 == 0){
			$Allow = "N";
		}elseif($arg2 == 1){
			$Allow = "Y";
		}else{
			$Allow = "Y";
		}
	}
	return $Allow;
}
$MaxRbn = "";
if($max_todate != ""){
  	$sheetid 		= $_SESSION['sheet-id'];
	$max_date_check = date('Y-m-d',strtotime($max_todate));
	$meas_gen_query1 = "select max(rbn) as rbn from mbookgenerate_staff where sheetid = '$sheetid' and DATE_FORMAT(fromdate,'%Y-%m-%d') > '$max_date_check' group by rbn";
	$meas_gen_sql = mysql_query($meas_gen_query1);
	if($meas_gen_sql == true){
		$meas_gen_alrdy = mysql_num_rows($meas_gen_sql);
		if(mysql_num_rows($meas_gen_sql)>0){
			$MsList = mysql_fetch_object($meas_gen_sql);
			$MaxRbn = $MsList->rbn;
		}	
	}
}
//echo $MaxRbn;
$_SESSION['reset_sheetid'] = $_SESSION['sheet-id'];
$_SESSION['reset_max_rbn'] = $MaxRbn;
?>
<?php require_once "Header.html"; ?>
<script type="text/javascript" language="javascript">
    $(function() {
    	$("#delete").click(function(event){  // triggred submit
			var mtype = $('#txt_measurementtype').val();
			var mbsid = $('#hid_sheetid').val();
			var mbzid = $('#hid_zoneid').val();
			var mbhid = "";
			var mbdid = "";
			var urlRdr = $(this).attr("href");
			event.preventDefault();
			var count_checked = $("[name='ch_deleteid[]']:checked").length;
			if(count_checked == 0){
            	BootstrapDialog.alert("Please select a row to delete.");
            	event.preventDefault();
				event.returnValue = false;
        	}
			if(count_checked > 0){
				if(count_checked == 1){
					var WarnMsg = 'Are you sure you want to delete these row ?'
				}else{
					var WarnMsg = 'Are you sure you want to delete these rows ?'
				}
				BootstrapDialog.confirm({
					message: WarnMsg,
					buttons: [{
						label: 'Cancel',
						cssClass: 'backbutton',
						action: function(dialog) {
							dialog.close();
						}
					},{
						label: 'Continue',
						cssClass: 'btn-primary',
					}],
					callback: function(result) {
						if(result) {
							BootstrapDialog.confirm({
								message: "If you edit this measurement you need to generate MBook, Sub Abstract and Abstract once again !",
								buttons: [{
									label: 'Cancel',
									cssClass: 'backbutton',
									action: function(dialog) {
										dialog.close();
									}
								},{
									label: 'Continue',
									cssClass: 'btn-primary',
								}],
								callback: function(result) {
									if(result) {
										 ResetGeneratedMB(mbsid,mbhid,mbdid,mbzid,mtype);
										 $("#delete1").trigger( "click" );
									}
								}
							});
						}
					}
				});
			}
    	});
		
		$("#edit").click(function(event){ alert(); // triggred submit
			var mtype = $('#txt_measurementtype').val();
			var mbsid = $('#hid_sheetid').val();
			var mbzid = $('#hid_zoneid').val();
			var mbhid = "";
			var mbdid = "";
			event.preventDefault();
			var count_checked = $("[name='ch_deleteid[]']:checked").length;
			if(count_checked == 0){
            	BootstrapDialog.alert("Please select a row to edit.");
            	event.preventDefault();
				event.returnValue = false;
        	}
			if(count_checked > 0){
				if(count_checked == 1){
					var WarnMsg = 'Are you sure you want to edit these row ?'
				}else{
					var WarnMsg = 'Are you sure you want to edit these rows ?'
				}
				BootstrapDialog.confirm({
					message: WarnMsg,
					buttons: [{
						label: 'Cancel',
						cssClass: 'btn-primary',
						action: function(dialog) {
							dialog.close();
						}
					},{
						label: 'Continue',
						cssClass: 'btn-primary',
					}],
					callback: function(result) {
						if(result) {
							 BootstrapDialog.confirm({
								message: "If you edit this measurement you need to generate MBook, Sub Abstract and Abstract once again !",
								buttons: [{
									label: 'Cancel',
									cssClass: 'btn-primary',
									action: function(dialog) {
										dialog.close();
									}
								},{
									label: 'Continue',
									cssClass: 'btn-primary',
								}],
								callback: function(result) {
									if(result) {
										 ResetGeneratedMB(mbsid,mbhid,mbdid,mbzid,mtype);
										 $("#edit1").trigger( "click" );
									}
								}
							});
						}
					}
				});
			}
    	});
		
		
		$(".single-edit").click(function(event){  // triggred submit
			var mbhid = $(this).attr("data-hid");
			var mbdid = $(this).attr("data-did");
			var mbzid = $(this).attr("data-zid");
			var mbsid = $(this).attr("data-sid");
			var mtype = $(this).attr("data-mtype");
			var urlRdr = $(this).attr("href");
			//alert(mbhid); alert(mbdid); alert(mtype); alert(urlRdr);
			event.preventDefault();
			var WarnMsg = 'Are you sure you want to edit these row ?'
			BootstrapDialog.confirm({
				message: WarnMsg,
				buttons: [{
					label: 'Cancel',
					cssClass: 'btn-primary',
					action: function(dialog) {
						dialog.close();
					}
				},{
					label: 'Continue',
					cssClass: 'btn-primary',
				}],
				callback: function(result) {
					if(result) {
						//$("#edit1").trigger( "click" );
						BootstrapDialog.confirm({
							message: "If you edit this measurement you need to generate MBook, Sub Abstract and Abstract once again !",
							buttons: [{
								label: 'Cancel',
								cssClass: 'btn-primary',
								action: function(dialog) {
									dialog.close();
								}
							},{
								label: 'Continue',
								cssClass: 'btn-primary',
							}],
							callback: function(result) {
								if(result) {
									//$("#edit1").trigger( "click" );
									ResetGeneratedMB(mbsid,mbhid,mbdid,mbzid,mtype);
									//BootstrapDialog.alert();
									window.location.replace(urlRdr);
								}
							}
						});
					}
				}
			});
    	});
		function ResetGeneratedMB(mbsid,mbhid,mbdid,mbzid,mtype) {
            $.post("find_reset_generate.php", {mbsid: mbsid, mbhid:mbhid, mbdid:mbdid, mbzid:mbzid, mtype:mtype }, function (data) { 
                 BootstrapDialog.alert("Sucessfully Reset");
            });
        }
		
		$("#check_all").click(function(){
			$('input:checkbox').not(this).prop('checked', this.checked);
		});
    });
	function goBack(){
	   	url = "ViewMeasurementEntry.php";
		window.location.replace(url);
	}
</script>
<style>
	.container{
    	display:table;
    	width:100%;
    	border-collapse: collapse;
    }/*.table-row{  
		 display:table-row;
		 text-align: left;
	}.col{
		display:table-cell;
		border: 1px solid #CCC;
		padding:1px;
	}*/.chbox-style{
		height: 12px;   
		width: 15px;
	}
	.table-bordered > thead > tr > th, .table-bordered > tbody > tr > td{
		color:#0241D2;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
	}
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<script src="dashboard/MyView/bootstrap.min.js"></script>

    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">View Measurements List</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style=" height:500px;overflow:scroll;">
                            <div class="container">
						<?php if($measurementtype == "S"){ ?>
								<table class="table table-bordered" id="dataTable">
									<thead>
										<tr>
											<th><input type="checkbox" name="check_all" id="check_all"></th>
											<th>S.No.</th>
											<th>Date</th>
											<th nowrap="nowrap">Item No.</th>
											<th>Description</th>
											<th>No.1</th>
											<th>No.2</th>
											<th>Dia</th>
											<th>Length</th>
											<th nowrap="nowrap">Contents of Area</th>
											<th>Unit</th>
										</tr>
									</thead>
									<tbody>
						<?php }else{ ?>
								<table class="table table-bordered" id="dataTable">
									<thead>
										<tr>
											<th><input type="checkbox" name="check_all" id="check_all"></th>
											<th>S.No.</th>
											<th>Date</th>
											<th nowrap="nowrap">Item No.</th>
											<th>Description</th>
											<th>No</th>
											<th>Length</th>
											<th>Breadth</th>
											<th>Depth</th>
											<th nowrap="nowrap">Contents of Area</th>
											<th>Unit</th>
										</tr>
									</thead>
									<tbody>
							<?php
							}
                            $itementeredsql = "SELECT  c.sheetid, c.date, c.zone_id, b.measure_type, a.subdiv_name, d.subdivid , d.mbdetail_id, d.mbheaderid, d.descwork, d.measurement_no , d.measurement_no2 , d.measurement_l , d.measurement_b, d.mbdetail_flag, 
      											d.measurement_d , d.structdepth_unit, d.measurement_contentarea , d.measurement_dia,d.remarks FROM subdivision a, schdule b, mbookheader c, mbookdetail d WHERE c.sheetid = '$sheetid' AND c.staffid = '$staffid' AND d.mbdetail_flag != 'd' AND c.mbheaderid = d.mbheaderid AND a.subdiv_id = c.subdivid AND b.subdiv_id = c.subdivid ".$where_clause." ".$where_clause_meastype.$zone_clause." ORDER BY c.date, c.mbheaderid, d.mbdetail_id ASC";
                            $rs_itementeredsql = mysql_query($itementeredsql);
                                     // echo $itementeredsql;
									  //exit;
							$slno = 1; $total = 0;
                            if(mysql_num_rows($rs_itementeredsql)>0){
								while($List = mysql_fetch_object($rs_itementeredsql)){  
									$decimal = get_decimal_placed($List->subdivid,$sheetid);
									
									$meas_date 	= strtotime($List->date);
									//if(($meas_date >= $start_ts) && ($meas_date <= $end_ts)){
									if(($meas_date <= $end_ts)&&($List->date <= $max_todate)){
										$check_date = 0;
									}else{
										$check_date = 1;
									}
									$check_result = CheckEditDelete($check_date,$DeleteEdit);
												
									if($measurementtype == "S"){
										//$check_result = check_measurement_date($sheetid,$List->date);
										$RemDP = 0;
										if($List->measurement_no2 != 0){ $RemDP++; }
										if($List->measurement_no != 0){ $RemDP++; }
										if($List->measurement_dia != 0){ $RemDP++; }
										if($List->measurement_l != 0){ $RemDP++; }
										if($List->measurement_contentarea != 0){ $RemDP++; }
										?>
										<tr>
											<td align="center">
												<?php if($check_result == 'Y'){ ?>
													<input type="checkbox" class="chbox-style" name="ch_deleteid[]" id="ch_deleteid" value="<?php echo $List->mbdetail_id."*".$List->mbheaderid."*".$List->zone_id; ?>"/>
												<?php } ?>
											</td>
											<td align="center"><?php echo $slno; ?></td>
											<td><?php echo "&nbsp".dt_display($List->date)."&nbsp"; ?></td>
											<td align="center" nowrap="nowrap">
												<?php if($check_result == 'Y'){ ?>
													<a href="Measurement_Edit.php?mbdetail_id=<?php echo $List->mbdetail_id; ?>&type=S&sheetid=<?php echo $sheetid; ?>" class="tooltipwarning single-edit" data-hid="<?php echo $List->mbheaderid; ?>" data-did="<?php echo $List->mbdetail_id; ?>" data-sid="<?php echo $List->sheetid; ?>" data-zid="<?php echo $List->zone_id; ?>" data-mtype="S" title="Click here to edit"><u><?php echo $List->subdiv_name; ?></u></a>
												<?php }else{ ?>
													<a class="tooltipwarning" title="Measurements already generated for this date. Unable to Edit."><?php echo $List->subdiv_name; ?></a>
												<?php } ?>
											</td>
											<td><?php if($RemDP == 0){ echo "<b>".$List->descwork."</b>"; }else{ echo $List->descwork; } ?></td>
											<td align="right"><?php if($List->measurement_no2 != 0){ echo $List->measurement_no2; } ?></td>
											<td align="right"><?php if($List->measurement_no != 0){ echo $List->measurement_no; } ?></td>
											<td align="right"><?php if($List->measurement_dia != 0){ echo $List->measurement_dia; } ?></td>
											<td align="right"><?php if($List->measurement_l != 0){ echo number_format($List->measurement_l,$decimal,".",","); } ?></td>
											<td align="right"><?php if($List->measurement_contentarea != 0){ echo number_format($List->measurement_contentarea,$decimal,".",","); } ?></td>
											<td><?php if($RemDP > 0){ echo $List->remarks; } ?></td>
										</tr>
										<?php }else {
												//$check_result = check_measurement_date($sheetid,$List->date);
												$total = $total + $List->measurement_contentarea;
												$RemDP = 0;
												if($List->measurement_no != 0){ $RemDP++; }
												if($List->measurement_l != 0){ $RemDP++; }
												if($List->measurement_b != 0){ $RemDP++; }
												if($List->measurement_d != 0){ $RemDP++; }
												if($List->measurement_contentarea != 0){ $RemDP++; }
										?>
											<tr>
												<td align="center">
													<?php if($check_result == 'Y'){ ?>
														<input type="checkbox" class="chbox-style" name="ch_deleteid[]" id="ch_deleteid" value="<?php echo $List->mbdetail_id."*".$List->mbheaderid."*".$List->zone_id; ?>"/>
													<?php } ?>
												</td>
												<td align="center"><?php echo $slno; ?></td>
												<td><?php echo "&nbsp".dt_display($List->date)."&nbsp"; ?></td>
												<td align="center" nowrap="nowrap">
													<?php if($check_result == 'Y'){ ?>
														<a href="Measurement_Edit.php?mbdetail_id=<?php echo $List->mbdetail_id; ?>&type=G&sheetid=<?php echo $sheetid; ?>" class="tooltipwarning single-edit" data-hid="<?php echo $List->mbheaderid; ?>" data-did="<?php echo $List->mbdetail_id; ?>" data-sid="<?php echo $List->sheetid; ?>" data-zid="<?php echo $List->zone_id; ?>" data-mtype="G" title="Click here to edit"><u><?php echo $List->subdiv_name; ?></u></a>
													<?php }else { ?>
														<a class="tooltipwarning" title="Measurements already generated for this date. Unable to Edit."><?php echo $List->subdiv_name; ?></a>
													<?php } ?>
												</td>
												<td><?php if($RemDP == 0){ echo "<b>".$List->descwork."</b>"; }else{ echo $List->descwork; } ?></td>
												<td align="right"><?php if($List->measurement_no != 0){ echo $List->measurement_no; } ?></td>
												<td align="right"><?php if($List->measurement_l != 0){ echo number_format($List->measurement_l,$decimal,".",","); } ?></td>
												<td align="right"><?php if($List->measurement_b != 0){ echo number_format($List->measurement_b,$decimal,".",","); } ?></td>
												<td align="right"><?php if($List->measurement_d != 0){ echo number_format($List->measurement_d,$decimal,".",",")." ".$List->structdepth_unit; } ?></td>
												<td align="right"><?php if($List->measurement_contentarea != 0){ echo number_format($List->measurement_contentarea,$decimal,".",","); } ?></td>
												<td><?php if($RemDP > 0){ echo $List->remarks; } ?></td>
											</tr>
									<?php
											}
											$slno++; $remarks = $List->remarks;
										} 
                                    }else{ ?>
                                    	<tr><td colspan="11">No records Found</td></tr>
									<?php } ?>
									</tbody>
								</table>
                            </div>
							<input type="hidden" name="txt_measurementtype" id="txt_measurementtype" value="<?php echo $measurementtype; ?>">
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php echo $sheetid; ?>">
							<input type="hidden" name="hid_zoneid" id="hid_zoneid" value="<?php echo $zone_name; ?>">
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
								<input type="submit" name="edit" id="edit" value=" Edit " />
								<input type="submit" name="edit1" id="edit1" value=" Edit " class="hide" />
								</div>
								<div class="buttonsection">
								<input type="submit" name="delete" id="delete" value=" Delete " />
								<input type="submit" name="delete1" id="delete1" value=" Delete " class="hide" />
								</div>
							</div>
                        </blockquote>
                    </div>
                </div>
            </div>
			<?php if($DeleteResCount>0){ ?>
				<script>
					BootstrapDialog.show({
						title: 'Default Title',
						message: 'Successfully Deleted',
						buttons: [{
							label: ' OK ',
							action: function(dialog) {
								dialog.close();
								location.replace("ViewMeasurementEntryList.php");
							}
						}]
					});
				</script> 
			<?php } ?>
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
</html>
<script>
	$(document).ready(function() {
		$('#dataTable').DataTable({
			responsive: true,
			paging: false, 
		});
	});
</script>
<style>
	.bootstrap-dialog-footer-buttons > .btn-default{
		color:#fff;
		background-color:#FA5B45;
	}
	.dataTables_wrapper{
		width:99% !important;
	}
	#dataTable td, #dataTable th{
		font-size:11px !important;
	}
	input[type="checkbox"], input[type="radio"] {
    	margin: 0px 0 0;
	}
</style>
