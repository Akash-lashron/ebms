<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
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
 // $sheetid = $_SESSION['Sheetid'];
  $fromdate = ""; $todate = "";
  $sheetid = $_SESSION['sheet-id'];
  $divid = $_SESSION['item'];
  $subdiv_id = $_SESSION['sub-item'];
  $measurementtype = $_SESSION['measurementtype'];
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
     header('Location: ViewMeasurementEntry_Inactive.php');
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
       header('Location: ViewMeasurementEntryList.php');
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
.heading{
     font-weight: bold;
     display:table-row;
     background-color: #D64242; /*SELECTED*/
    // background-color:#C91622; /*ORIGINAL*/
     text-align: center;
     line-height: 20px;
     font-size: 14px;
     font-family:georgia;
     color:#fff;
    
}
.table-row{  
     display:table-row;
     text-align: left;
}
.col{
display:table-cell;
border: 1px solid #CCC;

//font-weight: bold;

}
.col3
{
   font-family:Bookman Old Style;
	font-size:11pt;
	color:#3A2D2C;
   // color: #200000;
 // color: #945050;
  
}
.chbox-style
{
    height: 15px;   
    width: 15px;
}
        </style>
    <body class="page1" id="top">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">
					<div align="right" style="color:#0000cd; font-weight:bold; font-size:13px;"></div>
                        <blockquote class="bq1" style=" height:500px;overflow:scroll;">
                          <div class="title">View Measurement Entry List - History</div>
                            <div class="container" >
                            <?php 
							if($measurementtype == "S")
							{
							?>
                                    <div class="heading">
                                      		<div class="col"></div>
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
                                      		<div class="col"></div>
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
							 
//                                         $itementeredsql = "SELECT mbookheader.date ,  mbookdetail.subdivid , subdivision.subdiv_name , schdule.measure_type, mbookdetail.mbdetail_id, mbookdetail.mbheaderid,
//        mbookdetail.descwork, mbookdetail.measurement_no , mbookdetail.measurement_l , mbookdetail.measurement_b, mbookdetail.mbdetail_flag, 
//        mbookdetail.measurement_d , mbookdetail.measurement_contentarea , mbookdetail.measurement_dia,mbookdetail.remarks
//        FROM mbookheader
//        INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid) 
//		INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
//		INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) WHERE mbookdetail.mbdetail_flag != 'd' AND mbookheader.sheetid = ".$sheetid." AND mbookheader.userid= ".$userid." ".$where_clause." ".$where_clause_meastype;
//                                        $itementeredsql = "SELECT c.date ,  d.subdivid , a.subdiv_name , b.measure_type, d.mbdetail_id, d.mbheaderid, d.descwork, d.measurement_no , d.measurement_l , d.measurement_b, d.mbdetail_flag, 
//      d.measurement_d , d.measurement_contentarea , d.measurement_dia,d.remarks FROM subdivision a, schdule b, mbookheader c, mbookdetail d WHERE c.mbheaderid = d.mbheaderid AND d.mbdetail_flag != 'd' AND c.sheetid = ".$sheetid." AND c.userid= ".$userid." ".$where_clause." ".$where_clause_meastype;
                                        $itementeredsql = "SELECT c.date , b.measure_type, a.subdiv_name, d.subdivid , d.mbdetail_id, d.mbheaderid, d.descwork, d.measurement_no , d.measurement_l , d.measurement_b, d.mbdetail_flag, 
      d.measurement_d , d.structdepth_unit, d.measurement_contentarea , d.measurement_dia,d.remarks FROM subdivision a, schdule b, mbookheader c, mbookdetail d WHERE c.sheetid = '$sheetid' AND c.userid = '$userid' AND d.mbdetail_flag != 'd' AND c.mbheaderid = d.mbheaderid AND a.subdiv_id = c.subdivid AND b.subdiv_id = c.subdivid ".$where_clause." ".$where_clause_meastype;
                                        $rs_itementeredsql = mysql_query($itementeredsql);
                                      //echo $itementeredsql;exit;
                                        if(mysql_num_rows($rs_itementeredsql)>0)
                                        {
											while($List = mysql_fetch_object($rs_itementeredsql)) 
											{ 
											  	if($measurementtype == "S")
											  	{
											 	?>
													<div class="table-row">
															<div class="col"><center><input type="checkbox" class="chbox-style" name="ch_deleteid[]" id="ch_deleteid" value="<?php echo $List->mbdetail_id; ?>"/></center></div>
															<div class="col labelhead"><?php echo "&nbsp".dt_display($List->date)."&nbsp"; ?></div>
															<div class="col labelhead" style="width:50px; color:#0000cd">
															<?php echo $List->subdiv_name; ?>
															</div>
															<div class="col labelhead"><?php echo $List->descwork; ?></div>
															<div class="col labelhead"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?> </div>
															<div class="col labelhead"><?php if($List->measurement_dia != 0) { echo $List->measurement_dia; } ?> </div>
															<div class="col labelhead"><?php if($List->measurement_l != 0) { echo $List->measurement_l; } ?> </div>
															<div class="col labelhead"><?php if($List->measurement_contentarea != 0) { echo round($List->measurement_contentarea,3); } ?></div>
															<div class="col labelhead"><?php echo $List->remarks; ?></div>
													</div>
												<?php 
												}
												else
												{
												?>
													<div class="table-row">
															<div class="col"><center><input type="checkbox" class="chbox-style" name="ch_deleteid[]" id="ch_deleteid" value="<?php echo $List->mbdetail_id; ?>"/></center></div>
															<div class="col labelhead"><?php echo "&nbsp".dt_display($List->date)."&nbsp"; ?></div>
															<div class="col labelhead" style="width:50px; color:#0000cd">
															<?php echo $List->subdiv_name; ?>
															</div>
															<div class="col labelhead"><?php echo $List->descwork; ?></div>
															<div class="col labelhead"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?> </div>
															<div class="col labelhead"><?php if($List->measurement_l != 0) { echo $List->measurement_l; } ?> </div>
															<div class="col labelhead"><?php if($List->measurement_b != 0) { echo $List->measurement_b; } ?> </div>
															<div class="col labelhead"><?php if($List->measurement_d != 0) { echo $List->measurement_d ." ".$List->structdepth_unit; } ?> </div>
															<div class="col labelhead"><?php if($List->measurement_contentarea != 0) { echo round($List->measurement_contentarea,3); } ?></div>
															<div class="col labelhead"><?php echo $List->remarks; ?></div>
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
                                    echo $msg;
                                   } 
                               ?>
                            </div>
                        </blockquote>
                        <div><center>
						<table align="centre">
						<tr><td height="5px"></td></tr>
						<tr>
						<td height="27px">
							 <input type="submit" name="back" id="back" value=" Back " />&nbsp;&nbsp;
                        </td>
						</tr>
						</table>
						</center></div>
                        
                    </div>

                </div>
                
            </div>
            
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
</html>
