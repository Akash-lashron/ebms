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
//$sheetid  = $_SESSION['sheet-id'];
  /*$fromdate 		= ""; $todate = "";
  $sheetid 			= $_SESSION['sheet-id'];
  $divid 			= $_SESSION['item'];
  $subdiv_id 		= $_SESSION['sub-item'];
  $measurementtype 	= $_SESSION['measurementtype'];
  $where_clause = "";
  if(($_SESSION['from-date'] != "") && ($_SESSION['to-date'] != ""))
  {
    $fromdate = dt_format($_SESSION['from-date']);
    $todate = dt_format($_SESSION['to-date']);
  }*/
if($_GET['msg'] != "")
{
	$msg = $_GET['msg'];
	$_GET['msg'] = "";
}
$viewdata = $_SESSION['viewdata'];
if($_GET['viewdata'] != "")
{
  	$viewdata = $_GET['viewdata'];
	$_SESSION['viewdata'] = $viewdata;
}
	$explode_val = explode("*",$viewdata);
	$sheetid 			= $explode_val[0];  $_SESSION['sheet-id'] = $sheetid;
	$itemno				= $explode_val[1]; 
	$subitemno			= $explode_val[2];
	$subsubitemno		= $explode_val[3];
	$subsubsubitemno	= $explode_val[4];
	$measurementtype 	= $explode_val[5];  
	$divid = $itemno;
	 if(($subitemno != 0) && ($subitemno != ""))
     {
         $subdiv_id = $subitemno;
     }
     else if(($subsubitemno != 0) && ($subsubitemno != ""))
     {
         $subdiv_id = $subsubitemno;
     }
	 else if(($subsubsubitemno != 0) && ($subsubsubitemno != ""))
     {
         $subdiv_id = $subsubsubitemno;
     }
     else
     {
         //$divid= trim($_POST['itemno']);
         $sql_selectsubid = "select subdiv_id from subdivision WHERE div_id = '$divid' AND sheet_id = '$sheetid'";
         $res_subid = mysql_query($sql_selectsubid);
         $subdiv_id = @mysql_result($res_subid,0,'subdiv_id');
     }
  $where_clause = "";
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
            if($measurementtype == "s")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = "";
                    $where_clause_meastype = " AND b.measure_type = 's' ";
                }
                else
                {
                   $where_clause = " AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type = 's' ";
                }
            }
            if($measurementtype == "g")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = "";
                    $where_clause_meastype = " AND b.measure_type != 's' ";
                }
                else
                {
                   $where_clause = " AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type != 's' ";
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
            if($measurementtype == "s")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = "";
                    $where_clause_meastype = " AND b.measure_type = 's' ";
                }
                else
                {
                   $where_clause = " AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type = 's' ";
                }
            }
            if($measurementtype == "g")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = "";
                    $where_clause_meastype = " AND b.measure_type != 's' ";
                }
                else
                {
                   $where_clause = " AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type != 's' ";
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
            if($measurementtype == "s")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = " AND c.subdivid= ".$subdiv_id;
                    $where_clause_meastype = " AND b.measure_type = 's' ";
                }
                else
                {
                   $where_clause = " AND c.subdivid = ".$subdiv_id." AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type = 's' ";
                }
            }
            if($measurementtype == "g")
            {
                if(($fromdate == "") &&($todate == ""))
                {
                    $where_clause = " AND c.subdivid= ".$subdiv_id;
                    $where_clause_meastype = " AND b.measure_type != 's' ";
                }
                else
                {
                   $where_clause = " AND c.subdivid = ".$subdiv_id." AND c.date >="."'".$fromdate."'". " AND c.date <="."'".$todate."'"; 
                   $where_clause_meastype = " AND b.measure_type != 's' ";
                }
            }
        }
    }
  }

 if(isset($_POST['back']))
 {
     header('Location: MeasurementEntry.php');
 }
 if(isset($_POST['delete']))
 {
    $mbdetaild = $_POST['ch_deleteid'];
    $c = count($mbdetaild);
    for($i = 0; $i<$c; $i++)
    {
       $delete_sql = "UPDATE mbookdetail SET mbdetail_flag = 'd' WHERE mbdetail_id = '$mbdetaild[$i]'" ;
       $delete_query = mysql_query($delete_sql); 
       
    }
    if($delete_query == true)
    {
       header('Location: ViewMeasurementEntryList_Edit.php');
    }
 }
?>
<?php require_once "Header.html"; ?>
<script type="text/javascript" language="javascript">
    $(function() {
    $("#delete").click(function() {  // triggred submit
        var count_checked = $("[name='ch_deleteid[]']:checked").length; // count the checked
        if(count_checked == 0) {
            alert("Please select a row to delete.");
            return false;
        }
        if(count_checked == 1) {
            return confirm("Are you sure you want to delete these row?");
        } else {
            return confirm("Are you sure you want to delete these rows?");
          }
    });
    });
</script>
     <style>
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
.chbox-style
{
    height: 12px;   
    width: 15px;
}
        </style>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">
					<div align="right" style="color:#0000cd; font-weight:bold; font-size:13px;"><b> <font color="#E6061D">*</font> To Edit click Item No. &nbsp; &nbsp;&nbsp;</b></div>
                        <blockquote class="bq1" style=" height:500px;overflow:scroll;">
                          <div class="title">
						  View Measurement Entry List
						 
						  </div>
                            <div class="container" >
							<?php 
							if($measurementtype == "s")
							{
							?>
                                    <div class="heading">
                                      		<div class="col labelcontenthead"></div>
                                            <div class="col labelcontenthead">Date</div>
                                            <div class="col labelcontenthead">Item</div>
                                            <div class="col labelcontenthead" style=" width: 40%;">Description</div>
                                            <div class="col labelcontenthead">No</div>
                                            <div class="col labelcontenthead">Dia</div>
                                            <div class="col labelcontenthead">Length</div>
                                            <div class="col labelcontenthead" style=" line-height: 90%;">Contents<br/>of<br/>Area</div>
                                            <div class="col labelcontenthead">Unit</div>
                                    </div>
                                    <div class="table-row">
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                    </div>
							<?php
							}
							else
							{
							?>
									<div class="heading">
                                      		<div class="col labelcontenthead"></div>
                                            <div class="col labelcontenthead">Date</div>
                                            <div class="col labelcontenthead">Item No</div>
                                            <div class="col labelcontenthead" style=" width: 40%;">Description</div>
                                            <div class="col labelcontenthead">No</div>
                                            <div class="col labelcontenthead">Length</div>
                                            <div class="col labelcontenthead">Breadth</div>
                                            <div class="col labelcontenthead">Depth</div>
                                            <div class="col labelcontenthead" style=" line-height: 90%;">Contents<br/>of<br/>Area</div>
                                            <div class="col labelcontenthead">Unit</div>
                                    </div>
                                    <div class="table-row">
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                    </div>
							<?php
							}
							?>
                                        <?php 
//                                         $itementeredsql = "SELECT mbookheader.date ,  mbookdetail.subdivid , subdivision.subdiv_name , schdule.measure_type, mbookdetail.mbdetail_id, mbookdetail.mbheaderid,
//        mbookdetail.descwork, mbookdetail.measurement_no , mbookdetail.measurement_l , mbookdetail.measurement_b, mbookdetail.mbdetail_flag, 
//        mbookdetail.measurement_d , mbookdetail.measurement_contentarea , mbookdetail.measurement_dia,mbookdetail.remarks
//        FROM mbookheader
//        INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid) 
//		INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
//		INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) WHERE mbookdetail.mbdetail_flag != 'd' AND mbookheader.sheetid = ".$sheetid." AND mbookheader.userid= ".$userid." ".$where_clause." ".$where_clause_meastype;
//                                        $itementeredsql = "SELECT c.date ,  d.subdivid , a.subdiv_name , b.measure_type, d.mbdetail_id, d.mbheaderid, d.descwork, d.measurement_no , d.measurement_l , d.measurement_b, d.mbdetail_flag, 
//      d.measurement_d , d.measurement_contentarea , d.measurement_dia,d.remarks FROM subdivision a, schdule b, mbookheader c, mbookdetail d WHERE c.mbheaderid = d.mbheaderid AND d.mbdetail_flag != 'd' AND c.sheetid = ".$sheetid." AND c.userid= ".$userid." ".$where_clause." ".$where_clause_meastype;
                                        $itementeredsql = "SELECT c.date , b.measure_type, a.subdiv_name, a.div_id, d.subdivid , d.mbdetail_id, d.mbheaderid, d.descwork, d.measurement_no , d.measurement_l , d.measurement_b, d.mbdetail_flag, 
      d.measurement_d , d.structdepth_unit, d.measurement_contentarea , d.measurement_dia,d.remarks FROM subdivision a, schdule b, mbookheader c, mbookdetail d WHERE c.sheetid = '$sheetid' AND c.staffid = '$staffid' AND d.mbdetail_flag != 'd' AND c.mbheaderid = d.mbheaderid AND a.subdiv_id = c.subdivid AND b.subdiv_id = c.subdivid ".$where_clause." ".$where_clause_meastype." ORDER BY c.date DESC, d.subdivid ASC";
                                        $rs_itementeredsql = mysql_query($itementeredsql);
                                      //echo $itementeredsql;exit;
                                        if(mysql_num_rows($rs_itementeredsql)>0)
                                        {
											while($List = mysql_fetch_object($rs_itementeredsql)) 
											{  
												$decimal = get_decimal_placed($List->subdivid,$sheetid);
												if($measurementtype == "s")
												{
													$check_result = check_measurement_date($sheetid,$List->date);
												?>
			<!--                                    <div class="table-row">
														<div class="col"></div>
														<div class="col"></div>
														<div class="col"></div>
														<div class="col"></div>
														<div class="col"></div>
														<div class="col"></div>
														<div class="col"></div>
														<div class="col"></div>
														<div class="col"></div>  
													</div>-->
													<div class="table-row">
															<div class="col" style="text-align:center"><center><input type="checkbox" class="chbox-style" name="ch_deleteid[]" id="ch_deleteid" value="<?php echo $List->mbdetail_id; ?>"/></center></div>
															<div class="col labelhead" style="text-align:center"><?php echo "&nbsp".dt_display($List->date)."&nbsp"; ?></div>
															<div class="col col3" style="width:80px; color:#0000cd; text-align:center;">
															&nbsp;
															<?php 
															if($check_result == 1)
															{
															?>
																<a href="MeasurementEntry.php?mbdetailid=<?php echo $List->mbdetail_id; ?>& mbheaderid=<?php echo $List->mbheaderid; ?>& subdivid=<?php echo $List->subdivid; ?>& sheetid=<?php echo $sheetid; ?>& divid=<?php echo $List->div_id; ?>" class="tooltip" title="Click here to edit"><u><?php echo $List->subdiv_name; ?></u> 
																</a>
															<?php
															}
															else
															{
															?>
																<a href="" class="tooltip" title="Measurements already generated for this date. Unable to Edit."><?php echo $List->subdiv_name; ?> 
																</a>
															<?php
															}
															?>
															</div>
															<div class="col labelhead"><?php echo $List->descwork; ?></div>
															<div class="col labelhead" style="text-align:center"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?> </div>
															<div class="col labelhead" style="text-align:center"><?php if($List->measurement_dia != 0) { echo $List->measurement_dia; } ?> </div>
															<div class="col labelhead" style="text-align:right"><?php if($List->measurement_l != 0) { echo number_format($List->measurement_l,$decimal,".",","); } ?> </div>
															<div class="col labelhead" style="text-align:right"><?php if($List->measurement_contentarea != 0) { echo number_format($List->measurement_contentarea,$decimal,".",","); } ?></div>
															<div class="col labelhead" style="text-align:center"><?php echo $List->remarks; ?></div>
													</div>
												<?php 
												}
												else
												{
													$check_result = check_measurement_date($sheetid,$List->date);
												?>
													<div class="table-row">
															<div class="col" style="text-align:center"><center><input type="checkbox" class="chbox-style" name="ch_deleteid[]" id="ch_deleteid" value="<?php echo $List->mbdetail_id."*".$List->mbheaderid; ?>"/></center></div>
															<div class="col labelhead" style="text-align:center"><?php echo "&nbsp".dt_display($List->date)."&nbsp"; ?></div>
															<div class="col col3" style="width:80px; color:#0000cd; text-align:center;">
															&nbsp;
															<?php 
															if($check_result == 1)
															{
															?>
																<a href="MeasurementEntry.php?mbdetailid=<?php echo $List->mbdetail_id; ?>& mbheaderid=<?php echo $List->mbheaderid; ?>& subdivid=<?php echo $List->subdivid; ?>& sheetid=<?php echo $sheetid; ?>& divid=<?php echo $List->div_id; ?>" class="tooltip" title="Click here to edit"><u><?php echo $List->subdiv_name; ?></u> 
																</a>
															<?php
															}
															else
															{
															?>
																<a href="" class="tooltip" title="Measurements already generated for this date. Unable to Edit."><?php echo $List->subdiv_name; ?> 
																</a>
															<?php
															}
															?>
															</div>
															<div class="col labelhead"><?php echo $List->descwork; ?></div>
															<div class="col labelhead" style="text-align:center"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?> </div>
															<div class="col labelhead" style="text-align:right"><?php if($List->measurement_l != 0) { echo number_format($List->measurement_l,$decimal,".",","); } ?> </div>
															<div class="col labelhead" style="text-align:right"><?php if($List->measurement_b != 0) { echo number_format($List->measurement_b,$decimal,".",","); } ?> </div>
															<div class="col labelhead" style="text-align:right"><?php if($List->measurement_d != 0) { echo number_format($List->measurement_d,$decimal,".",",") ." ".$List->structdepth_unit; } ?> </div>
															<div class="col labelhead" style="text-align:right"><?php if($List->measurement_contentarea != 0) { echo number_format($List->measurement_contentarea,$decimal,".",","); } ?></div>
															<div class="col labelhead" style="text-align:center"><?php echo $List->remarks; ?></div>
													</div>
												<?php
												}
											} 
                                        }
                                        else
                                        {
                                            $msg = "<br/><br/>No Records Found.....";
                                        }
                                        ?>
                            </div>
                            <div class="col2">
                               <?php 
                               if ($msg != '') 
                                   {
                                    //echo $msg;
                                   } 
                               ?>
                            </div>
                        </blockquote>
                        <!--<div><center>
						<table align="centre">
						<tr><td height="5px"></td></tr>
						<tr><td height="27px">
						   <input type="submit" name="back" id="back" value=" Back " />&nbsp;&nbsp;
						   <input type="submit" name="delete" id="delete" value=" Delete " />
						</td></tr>
						</table></center></div>-->
                       <div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
							<div class="buttonsection">
							<input type="submit" name="back" id="back" value=" Back " />
							</div>
							<div class="buttonsection">
							<input type="submit" name="delete" id="delete" value=" Delete " />
							</div>
						</div> 
                    </div>

                </div>
                
            </div>
            
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
	<script>
				var msg = "<?php echo $msg; ?>";
				var titletext = "Hi";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(msg == "F")
					{
						sweetAlert("", "Something went wrong!", "error");
					}
					if(msg == "S")
					{
						swal("", "Sucessfully Updated...!", "success");
					}
					/*swal({
						title: titletext,
						text: msg,
						timer: 4000,
						showConfirmButton: true
					});*/
				}
			};
	</script>
</html>
