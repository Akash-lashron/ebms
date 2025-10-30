<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';
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
$staffid 			= $_SESSION['sid'];
$fromdate 			= ""; $todate = "";
$sheetid 			= $_SESSION['sheet-id'];
$divid 				= $_SESSION['item'];
$subdiv_id 			= $_SESSION['sub-item'];
$measurementtype 	= $_SESSION['measurementtype'];
$zone_name 			= $_SESSION['view_zone_name'];
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
?>
<?php require_once "Header.html"; ?>
<style>
.hide
{
	display:none;
}
.gradientbg {
  /* fallback */
  background-color: #014D62;
  width:90%; height:25px; color:#FFFFFF; vertical-align:middle;
  background: url(images/linear_bg_2.png);
  background-repeat: repeat-x;

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
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<body class="page1" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="top">
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
	<div class="container_12">
		<div class="grid_12">
			<blockquote class="bq1" style="overflow:scroll">
				<div class="title">Cement Consumption - View</div>		
				<div class="container">	
					<div style="width:100%;" align="center" id="myDiv">
						<table width="100%" class="table1" id="table1">
						<?php 
						if($measurementtype == "G")
						{
						?>
							<tr class="label heading" style="color:#FFFFFF; height:35px;">
								<td align="center" valign="middle" nowrap="nowrap">Sl.No.</td>
								<td align="center" valign="middle" nowrap="nowrap">Date</td>
								<td align="center" valign="middle">Item No.</td>
								<td align="center" valign="middle">Description</td>
								<td align="center" valign="middle">No.</td>
								<td align="center" valign="middle">Length</td>
								<td align="center" valign="middle">Depth</td>
								<td align="center" valign="middle">Breadth</td>
								<td align="center" valign="middle">Contents <br/>of <br/>Area</td>
								<td align="center" valign="middle">Unit</td>
								<td align="center" valign="middle" nowrap="nowrap">Th.Cem.Con.</td>
								<td align="center" valign="middle" nowrap="nowrap">Amount</td>
							</tr>
						<?php
						$itementeredsql = "SELECT c.date , b.measure_type, b.tc_unit, b.shortnotes, b.per, b.decimal_placed, a.subdiv_name, d.subdivid , d.mbdetail_id, d.mbheaderid, d.descwork, 
						d.measurement_no , d.measurement_l , d.measurement_b, d.mbdetail_flag, d.measurement_d , d.structdepth_unit, 
						d.measurement_contentarea , d.measurement_dia,d.remarks FROM subdivision a, schdule b, mbookheader c, mbookdetail d 
						WHERE c.sheetid = '$sheetid' AND c.staffid = '$staffid' AND d.mbdetail_flag != 'd' AND c.mbheaderid = d.mbheaderid 
						AND a.subdiv_id = c.subdivid AND b.subdiv_id = c.subdivid ".$where_clause." ".$where_clause_meastype.$zone_clause." 
						AND b.tc_unit != 0 ORDER BY c.date, c.mbheaderid, d.mbdetail_id ASC";
						//echo $itementeredsql;
                          $rs_itementeredsql = mysql_query($itementeredsql);
						  if($rs_itementeredsql == true)
						  {
						  	if(mysql_num_rows($rs_itementeredsql)>0)
							{
								$slno = 1; $prev_subdivid = ""; $prev_date = ""; $Total_qty = 0; $over_all_cem_consum = 0;
								while($List = mysql_fetch_object($rs_itementeredsql))
								{
									$temp 		= 0; $temp1 = 0;
									$subdivid 	= $List->subdivid;
									$mdate 		= $List->date;
									$item_unit 	= $List->per;
									$tc_unit 	= $List->tc_unit;
									$item_qty 	= round($List->measurement_contentarea,$List->decimal_placed);
									if(($prev_subdivid != "")&&($subdivid != $prev_subdivid))
									{
										$temp = 1;
									}
									else if(($prev_date != "")&&($mdate != $prev_date))
									{
										$temp = 1;
									}
									else
									{
										$temp = 0;
									}
									if($subdivid != $prev_subdivid)
									{
										$temp1 = 1;
									}
									else if($mdate != $prev_date)
									{
										$temp1 = 1;
									}
									else
									{
										$temp1 = 0;
									}
									if($temp == 1)
									{
										$tc_consum = round($prev_tc_unit*$Total_qty,$List->decimal_placed);
										echo "<tr class='labelsmall'>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class=''>&nbsp;</td>";
										echo "<td colspan='4' class='' align='right'>Total&nbsp;</td>";
										echo "<td class='' align='right'>".$Total_qty."&nbsp;</td>";
										echo "<td class='' align='center'>".$prev_item_unit."</td>";
										echo "<td class='' align='right'>".$prev_tc_unit."</td>";
										echo "<td class='' align='right'>".$tc_consum."</td>";
										echo "</tr>";
										/*echo "<tr class='labelsmall'>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td colspan='5' class='' align='right'>Theoritical Cement Consumption&nbsp;</td>";
										echo "<td class='' align='right'>".$prev_tc_unit."&nbsp;</td>";
										echo "<td class=''>".$prev_item_unit."</td>";
										echo "</tr>";
										$tc_consum = round($prev_tc_unit*$Total_qty,$List->decimal_placed);
										echo "<tr class='label'>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td colspan='5' class='' align='right'>Total Cement Consumption&nbsp;</td>";
										echo "<td class='' align='right'>".$tc_consum."&nbsp;</td>";
										echo "<td class=''>".$prev_item_unit."</td>";
										echo "</tr>";*/
										$over_all_cem_consum = $over_all_cem_consum+$tc_consum;
										$Total_qty = 0;
									}
									if($temp1 == 1)
									{
										echo "<tr class='labeldisplay'>";
										echo "<td class='' align='center'>".$slno."</td>";
										echo "<td class='' align='center'>".dt_display($List->date)."</td>";
										echo "<td class='' align='center'>".$List->subdiv_name."</td>";
										echo "<td class='' colspan='6'>".$List->shortnotes."</td>";
										echo "<td class=''>&nbsp;</td>";
										echo "<td class=''>&nbsp;</td>";
										echo "<td class=''>&nbsp;</td>";
										echo "</tr>";
									}
									echo "<tr class='labeldisplay'>";
									echo "<td class='' align='center'>&nbsp;</td>";
									echo "<td class='' align='center'>&nbsp;</td>";
									echo "<td class='' align='center'>&nbsp;</td>";
									echo "<td class=''>".$List->descwork."</td>";
									echo "<td class='' align='right'>".$List->measurement_no."&nbsp;</td>";
									echo "<td class='' align='right'>".number_format($List->measurement_l,$List->decimal_placed,".",",")."&nbsp;</td>";
									echo "<td class='' align='right'>".number_format($List->measurement_b,$List->decimal_placed,".",",")."&nbsp;</td>";
									echo "<td class='' align='right'>".number_format($List->measurement_d,$List->decimal_placed,".",",")."&nbsp;</td>";
									echo "<td class='' align='right'>".number_format($item_qty,$List->decimal_placed,".",",")."&nbsp;</td>";
									echo "<td class=''>".$item_unit."</td>";
									echo "<td class=''>&nbsp;</td>";
									echo "<td class=''>&nbsp;</td>";
									echo "</tr>";
									$Total_qty 			 = $Total_qty + $item_qty;
									$prev_subdivid 		 = $subdivid; 
									$prev_date 			 = $mdate;
									$prev_item_unit 	 = $item_unit;
									$prev_tc_unit 		 = $tc_unit;
									$prev_decimal_placed = $List->decimal_placed;
									$slno++;
								}
								$tc_consum = round($prev_tc_unit*$Total_qty,$prev_decimal_placed);
								echo "<tr class='labelsmall'>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class=''>&nbsp;</td>";
								echo "<td colspan='4' class='' align='right'>Total&nbsp;</td>";
								echo "<td class='' align='right'>".$Total_qty."&nbsp;</td>";
								echo "<td class='' align='center'>".$prev_item_unit."</td>";
								echo "<td class='' align='right'>".$prev_tc_unit."</td>";
								echo "<td class='' align='right'>".$tc_consum."</td>";
								echo "</tr>";
								/*echo "<tr class='labelsmall'>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td colspan='5' class='' align='right'>Theoritical Cement Consumption&nbsp;</td>";
								echo "<td class='' align='right'>".$prev_tc_unit."&nbsp;</td>";
								echo "<td class=''>".$prev_item_unit."</td>";
								echo "</tr>";
								$tc_consum = round($prev_tc_unit*$Total_qty,$prev_decimal_placed);
								echo "<tr class='label'>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td colspan='5' class='' align='right'>Total Cement Consumption&nbsp;</td>";
								echo "<td class='' align='right'>".$tc_consum."&nbsp;</td>";
								echo "<td class=''>".$prev_item_unit."</td>";
								echo "</tr>";*/
								$over_all_cem_consum = $over_all_cem_consum+$Total_qty;
								echo "<tr class='labelsmall'>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class=''>&nbsp;</td>";
								echo "<td class=''>&nbsp;</td>";
								echo "<td class=''>&nbsp;</td>";
								echo "<td class=''>&nbsp;</td>";
								echo "<td class='' align='right'>Total&nbsp;</td>";
								echo "<td class='' align='right'>&nbsp;&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='right'>&nbsp;</td>";
								echo "<td class='' align='right'>".$over_all_cem_consum."</td>";
								echo "</tr>";
								$Total_qty = 0;
							}
						  }
						}
						if($measurementtype == "S")
						{
						?>
							<tr class="label heading" style="color:#FFFFFF; height:35px;">
								<td align="center" valign="middle" nowrap="nowrap">Sl.No.</td>
								<td align="center" valign="middle" nowrap="nowrap">Date</td>
								<td align="center" valign="middle">Item No.</td>
								<td align="center" valign="middle">Description</td>
								<td align="center" valign="middle">No.</td>
								<td align="center" valign="middle">Length</td>
								<td align="center" valign="middle">Depth</td>
								<td align="center" valign="middle">Breadth</td>
								<td align="center" valign="middle">Contents <br/>of <br/>Area</td>
								<td align="center" valign="middle">Unit</td>
								<td align="center" valign="middle" nowrap="nowrap">Th.Cem.Con.</td>
								<td align="center" valign="middle" nowrap="nowrap">Amount</td>
							</tr>
						<?php
						$itementeredsql = "SELECT c.date , b.measure_type, b.tc_unit, b.shortnotes, b.per, b.decimal_placed, a.subdiv_name, d.subdivid , d.mbdetail_id, d.mbheaderid, d.descwork, 
						d.measurement_no , d.measurement_l , d.measurement_b, d.mbdetail_flag, d.measurement_d , d.structdepth_unit, 
						d.measurement_contentarea , d.measurement_dia,d.remarks FROM subdivision a, schdule b, mbookheader c, mbookdetail d 
						WHERE c.sheetid = '$sheetid' AND c.staffid = '$staffid' AND d.mbdetail_flag != 'd' AND c.mbheaderid = d.mbheaderid 
						AND a.subdiv_id = c.subdivid AND b.subdiv_id = c.subdivid ".$where_clause." ".$where_clause_meastype.$zone_clause." 
						AND b.tc_unit != 0 ORDER BY c.date, c.mbheaderid, d.mbdetail_id ASC";
						//echo $itementeredsql;
                          $rs_itementeredsql = mysql_query($itementeredsql);
						  if($rs_itementeredsql == true)
						  {
						  	if(mysql_num_rows($rs_itementeredsql)>0)
							{
								$slno = 1; $prev_subdivid = ""; $prev_date = ""; $Total_qty = 0; $over_all_cem_consum = 0;
								while($List = mysql_fetch_object($rs_itementeredsql))
								{
									$temp 		= 0; $temp1 = 0;
									$subdivid 	= $List->subdivid;
									$mdate 		= $List->date;
									$item_unit 	= $List->per;
									$tc_unit 	= $List->tc_unit;
									$item_qty 	= round($List->measurement_contentarea,$List->decimal_placed);
									if(($prev_subdivid != "")&&($subdivid != $prev_subdivid))
									{
										$temp = 1;
									}
									else if(($prev_date != "")&&($mdate != $prev_date))
									{
										$temp = 1;
									}
									else
									{
										$temp = 0;
									}
									if($subdivid != $prev_subdivid)
									{
										$temp1 = 1;
									}
									else if($mdate != $prev_date)
									{
										$temp1 = 1;
									}
									else
									{
										$temp1 = 0;
									}
									if($temp == 1)
									{
										$tc_consum = round($prev_tc_unit*$Total_qty,$List->decimal_placed);
										echo "<tr class='labelsmall'>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class=''>&nbsp;</td>";
										echo "<td colspan='4' class='' align='right'>Total&nbsp;</td>";
										echo "<td class='' align='right'>".$Total_qty."&nbsp;</td>";
										echo "<td class='' align='center'>".$prev_item_unit."</td>";
										echo "<td class='' align='right'>".$prev_tc_unit."</td>";
										echo "<td class='' align='right'>".$tc_consum."</td>";
										echo "</tr>";
										/*echo "<tr class='labelsmall'>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td colspan='5' class='' align='right'>Theoritical Cement Consumption&nbsp;</td>";
										echo "<td class='' align='right'>".$prev_tc_unit."&nbsp;</td>";
										echo "<td class=''>".$prev_item_unit."</td>";
										echo "</tr>";
										$tc_consum = round($prev_tc_unit*$Total_qty,$List->decimal_placed);
										echo "<tr class='label'>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td class='' align='center'>&nbsp;</td>";
										echo "<td colspan='5' class='' align='right'>Total Cement Consumption&nbsp;</td>";
										echo "<td class='' align='right'>".$tc_consum."&nbsp;</td>";
										echo "<td class=''>".$prev_item_unit."</td>";
										echo "</tr>";*/
										$over_all_cem_consum = $over_all_cem_consum+$tc_consum;
										$Total_qty = 0;
									}
									if($temp1 == 1)
									{
										echo "<tr class='labeldisplay'>";
										echo "<td class='' align='center'>".$slno."</td>";
										echo "<td class='' align='center'>".dt_display($List->date)."</td>";
										echo "<td class='' align='center'>".$List->subdiv_name."</td>";
										echo "<td class='' colspan='6'>".$List->shortnotes."</td>";
										echo "<td class=''>&nbsp;</td>";
										echo "<td class=''>&nbsp;</td>";
										echo "<td class=''>&nbsp;</td>";
										echo "</tr>";
									}
									echo "<tr class='labeldisplay'>";
									echo "<td class='' align='center'>&nbsp;</td>";
									echo "<td class='' align='center'>&nbsp;</td>";
									echo "<td class='' align='center'>&nbsp;</td>";
									echo "<td class=''>".$List->descwork."</td>";
									echo "<td class='' align='right'>".$List->measurement_no."&nbsp;</td>";
									echo "<td class='' align='right'>".number_format($List->measurement_l,$List->decimal_placed,".",",")."&nbsp;</td>";
									echo "<td class='' align='right'>".number_format($List->measurement_b,$List->decimal_placed,".",",")."&nbsp;</td>";
									echo "<td class='' align='right'>".number_format($List->measurement_d,$List->decimal_placed,".",",")."&nbsp;</td>";
									echo "<td class='' align='right'>".number_format($item_qty,$List->decimal_placed,".",",")."&nbsp;</td>";
									echo "<td class=''>".$item_unit."</td>";
									echo "<td class=''>&nbsp;</td>";
									echo "<td class=''>&nbsp;</td>";
									echo "</tr>";
									$Total_qty 			 = $Total_qty + $item_qty;
									$prev_subdivid 		 = $subdivid; 
									$prev_date 			 = $mdate;
									$prev_item_unit 	 = $item_unit;
									$prev_tc_unit 		 = $tc_unit;
									$prev_decimal_placed = $List->decimal_placed;
									$slno++;
								}
								$tc_consum = round($prev_tc_unit*$Total_qty,$prev_decimal_placed);
								echo "<tr class='labelsmall'>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class=''>&nbsp;</td>";
								echo "<td colspan='4' class='' align='right'>Total&nbsp;</td>";
								echo "<td class='' align='right'>".$Total_qty."&nbsp;</td>";
								echo "<td class='' align='center'>".$prev_item_unit."</td>";
								echo "<td class='' align='right'>".$prev_tc_unit."</td>";
								echo "<td class='' align='right'>".$tc_consum."</td>";
								echo "</tr>";
								/*echo "<tr class='labelsmall'>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td colspan='5' class='' align='right'>Theoritical Cement Consumption&nbsp;</td>";
								echo "<td class='' align='right'>".$prev_tc_unit."&nbsp;</td>";
								echo "<td class=''>".$prev_item_unit."</td>";
								echo "</tr>";
								$tc_consum = round($prev_tc_unit*$Total_qty,$prev_decimal_placed);
								echo "<tr class='label'>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td colspan='5' class='' align='right'>Total Cement Consumption&nbsp;</td>";
								echo "<td class='' align='right'>".$tc_consum."&nbsp;</td>";
								echo "<td class=''>".$prev_item_unit."</td>";
								echo "</tr>";*/
								$over_all_cem_consum = $over_all_cem_consum+$Total_qty;
								echo "<tr class='labelsmall'>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class=''>&nbsp;</td>";
								echo "<td class=''>&nbsp;</td>";
								echo "<td class=''>&nbsp;</td>";
								echo "<td class=''>&nbsp;</td>";
								echo "<td class='' align='right'>Total&nbsp;</td>";
								echo "<td class='' align='right'>&nbsp;&nbsp;</td>";
								echo "<td class='' align='center'>&nbsp;</td>";
								echo "<td class='' align='right'>&nbsp;</td>";
								echo "<td class='' align='right'>".$over_all_cem_consum."</td>";
								echo "</tr>";
								$Total_qty = 0;
							}
						  }
						}
						 ?>
						</table>
					</div>
					<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
						<div class="buttonsection">
							<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
						</div>
						<div class="buttonsection">
							<input type="submit" name="submit" value=" View " id="submit" onClick="getlength();"/>
						</div>
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
    $(function() {});
</script>
</body>
</html>