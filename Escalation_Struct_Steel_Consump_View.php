<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
$msg = '';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
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
$sheetid  	= $_SESSION['escal_sheetid'];
$from_rbn  	= $_SESSION['escal_from_rbn'];
$to_rbn  	= $_SESSION['escal_to_rbn'];
$escal_data_old_bn_query = 	"SELECT DATE(measurementbook.measurementbookdate) as mdate, measurementbook.subdivid, DATE(measurementbook.fromdate) as fromdate, 
							DATE(measurementbook.todate) as todate, measurementbook.mbno, measurementbook.mbpage, measurementbook.mbtotal, measurementbook.rbn, 
							schdule.sno, schdule.tc_unit, schdule.measure_type, schdule.subdiv_id, schdule.per, schdule.decimal_placed, schdule.description,
							schdule.shortnotes 
							FROM measurementbook
							INNER JOIN schdule ON (measurementbook.subdivid = schdule.subdiv_id)
							WHERE schdule.measure_type = 'st' AND measurementbook.sheetid = '$sheetid' 
							AND measurementbook.rbn >= '$from_rbn' AND measurementbook.rbn <= '$to_rbn' AND schdule.sheet_id = '$sheetid'
							ORDER BY measurementbook.rbn ASC, measurementbook.subdivid ASC";
$escal_data_old_bn_sql = mysql_query($escal_data_old_bn_query);
//echo $escal_data_old_bn_query;

$escal_data_new_bn_query = 	"SELECT DATE(measurementbook_temp.measurementbookdate) as mdate, measurementbook_temp.subdivid, 
							DATE(measurementbook_temp.fromdate) as fromdate, DATE(measurementbook_temp.todate) as todate, measurementbook_temp.mbno, 
							measurementbook_temp.mbpage, measurementbook_temp.mbtotal, measurementbook_temp.rbn, schdule.sno, schdule.tc_unit, 
							schdule.measure_type, schdule.subdiv_id, schdule.per, schdule.decimal_placed, schdule.description, schdule.shortnotes 
							FROM measurementbook_temp
							INNER JOIN schdule ON (measurementbook_temp.subdivid = schdule.subdiv_id)
							WHERE schdule.measure_type = 'st' AND measurementbook_temp.sheetid = '$sheetid' 
							AND measurementbook_temp.rbn = '$to_rbn' AND schdule.sheet_id = '$sheetid' 
							ORDER BY measurementbook_temp.rbn ASC, measurementbook_temp.subdivid ASC";
$escal_data_new_bn_sql = mysql_query($escal_data_new_bn_query);
//echo $escal_data_query."<br/>";

?>
<?php require_once "Header.html"; ?>
<style>
    
</style>
<script>
	function goBack()
	{
		url = "Escalation_Struct_Steel_Consump.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
input[type="date"]:before {
    content: attr(placeholder) !important;
    color: #aaa;
    margin-right: 0.5em;
  }
  input[type="date"]:focus:before,
  input[type="date"]:valid:before {
    content: "";
  }
.extraItemTextbox {
    height: 30px;
    position: relative;
    outline: none;
    border: 1px solid #98D8FE;
    background-color: white;
	color:#0000cc;
	width:98%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	text-align:center;
}
.extraItemTextArea
{
    position: relative;
    outline: none;
	border: 1px solid #98D8FE;
    background-color: white;
	color:#0000cc;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
}
.extraItemTextboxDisable {
    height: 30px;
    position: relative;
    outline: none;
    border:none;
    background-color: #EAEAEA;
	color:#0000cc;
	width:98%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	text-align:center;
	vertical-align:middle;
	cursor:default;
}
.gradientbg {
 	background-color: #014D62;
  	width:90%; height:25px; color:#FFFFFF; vertical-align:middle;
  	background: url(images/linear_bg_2.png);
  	background-repeat: repeat-x;
  	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#037595), to(#0A9CC5));
  	background: -webkit-linear-gradient(top, #0A9CC5, #037595);
  	background: -moz-linear-gradient(top, #0A9CC5, #037595);
  	background: -ms-linear-gradient(top, #0A9CC5, #037595);
  	background: -o-linear-gradient(top, #0A9CC5, #037595);
}
.buttonstyle
{
	background-color:#0A9CC5;
	height:25px;
	color:#FFFFFF;
	-moz-box-shadow: 0px 1px 0px 0px #0A9CC5;
	-webkit-box-shadow: 0px 1px 0px 0px #0A9CC5;
	box-shadow: 0px 1px 0px 0px #0A9CC5;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0080FF), color-stop(1, #0A9CC5));
	background:-moz-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-webkit-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-o-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-ms-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:linear-gradient(to bottom, #0080FF 5%, #0A9CC5 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0080FF', endColorstr='#0A9CC5',GradientType=0);
	border:1px solid #0080FF;
	display:inline-block;
	cursor:pointer;
	font-weight:bold;
}
.buttonstyle:hover
{
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E80017;
	border:1px solid #E80017;
}
.buttonstyledisable
{
	background-color:#CECECE;
	color:#A0A0A0;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #E6E6E6), color-stop(1, #CECECE));
	background:-moz-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-webkit-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-o-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-ms-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:linear-gradient(to bottom, #E6E6E6 5%, #CECECE 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#E6E6E6', endColorstr='#CECECE',GradientType=0);
	border:1px solid #CECECE;
}
.buttonstyledisable:hover
{
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E6E6E6;
	border:1px solid #E6E6E6;
}
.hide
{
	display:none;
}
sub {font-size:xx-small; vertical-align:sub;}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="AgreementEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1">
                            <div class="title">Escalation - Structural Steel Consumption View</div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
							<div style="width:100%;" align="center" id="myDiv">
								<table width="100%" class="table1" id="table1">
									<tr class="label" style="height:35px;">
										<td align="center" valign="middle">Sl.No.</td>
										<td align="center" valign="middle">Date</td>
										<td align="center" valign="middle">page</td>
										<td align="center" valign="middle">MBook No</td>
										<td align="center" valign="middle">RAB No.</td>
										<td align="center" valign="middle">Item No.</td>
										<td align="center" valign="middle">Description of Item No.</td>
										<td align="center" valign="middle">Qty </td>
										<td align="center" valign="middle">Unit </td>
										<td align="center" valign="middle">Theoritical <br/>Cement <br/>Consump. </td>
										<td align="center" valign="middle">Total <br/>Cement <br/>Consump. </td>
										<td align="center" valign="middle">Unit </td>
									</tr>
						<?php
						if($escal_data_old_bn_sql == true)
						{
							if(mysql_num_rows($escal_data_old_bn_sql)>0)
							{
								$slno = 1; $total_cem_consum = 0;
								while($OldRABList = mysql_fetch_object($escal_data_old_bn_sql))
								{
									$mdate 			 = dt_display($OldRABList->mdate);
									$mbpage 		 = $OldRABList->mbpage;
									$mbno 			 = $OldRABList->mbno;
									$rbn 			 = $OldRABList->rbn;
									$itemno 		 = $OldRABList->sno;
									$description 	 = $OldRABList->description;
									$shortnotes 	 = $OldRABList->shortnotes;
									$qty 			 = $OldRABList->mbtotal;
									$itemunit 		 = $OldRABList->per;
									$tc_unit 		 = $OldRABList->tc_unit;
									$decimal_placed  = $OldRABList->decimal_placed;
									$item_cem_consum = $tc_unit*$qty;
									if($tc_unit == 0)
									{
										$item_cem_consum = $qty;
									}
									if($shortnotes != "")
									{
										$description = $shortnotes;
									}
									echo '<tr class="labeldisplay">';
									echo '<td align="center" valign="middle">'.$slno.'</td>';
									echo '<td align="center" valign="middle">'.$mdate.'</td>';
									echo '<td align="center" valign="middle">'.$mbpage.'</td>';
									echo '<td align="center" valign="middle">'.$mbno.'</td>';
									echo '<td align="center" valign="middle">'.$rbn.'</td>';
									echo '<td align="center" valign="middle">'.$itemno.'</td>';
									echo '<td align="left" valign="middle">'.$description.'</td>';
									echo '<td align="right" valign="middle">&nbsp;'.number_format($qty,$decimal_placed,".",",").'&nbsp;</td>';
									echo '<td align="center" valign="middle">'.$itemunit.'</td>';
									if($tc_unit == 0)
									{
									echo '<td align="right" valign="middle">&nbsp;</td>';
									}
									else
									{
									echo '<td align="right" valign="middle">&nbsp;'.number_format($tc_unit,$decimal_placed,".",",").'&nbsp;</td>';
									}
									echo '<td align="right" valign="middle">&nbsp;'.number_format($item_cem_consum,$decimal_placed,".",",").'&nbsp;</td>';
									echo '<td align="center" valign="middle">&nbsp;</td>';
									echo '</tr>';
									$total_cem_consum = $total_cem_consum + $item_cem_consum;
									$slno++;
								}
							}
						}
						if($escal_data_new_bn_sql == true)
						{
							if(mysql_num_rows($escal_data_new_bn_sql)>0)
							{
								$slno = 1;
								while($NewRABList = mysql_fetch_object($escal_data_new_bn_sql))
								{
									$mdate 			 = dt_display($NewRABList->mdate);
									$mbpage 		 = $NewRABList->mbpage;
									$mbno 			 = $NewRABList->mbno;
									$rbn 			 = $NewRABList->rbn;
									$itemno 		 = $NewRABList->sno;
									$description 	 = $NewRABList->description;
									$shortnotes 	 = $NewRABList->shortnotes;
									$qty 			 = $NewRABList->mbtotal;
									$itemunit 		 = $NewRABList->per;
									$tc_unit 		 = $NewRABList->tc_unit;
									$decimal_placed  = $NewRABList->decimal_placed;
									$item_cem_consum = $tc_unit*$qty;
									if($tc_unit == 0)
									{
										$item_cem_consum = $qty;
									}
									if($shortnotes != "")
									{
										$description = $shortnotes;
									}
									echo '<tr class="labeldisplay">';
									echo '<td align="center" valign="middle">'.$slno.'</td>';
									echo '<td align="center" valign="middle">'.$mdate.'</td>';
									echo '<td align="center" valign="middle">'.$mbpage.'</td>';
									echo '<td align="center" valign="middle">'.$mbno.'</td>';
									echo '<td align="center" valign="middle">'.$rbn.'</td>';
									echo '<td align="center" valign="middle">'.$itemno.'</td>';
									echo '<td align="left" valign="middle">'.$description.'</td>';
									echo '<td align="right" valign="middle">&nbsp;'.number_format($qty,$decimal_placed,".",",").'&nbsp;</td>';
									echo '<td align="center" valign="middle">'.$itemunit.'</td>';
									if($tc_unit == 0)
									{
									echo '<td align="right" valign="middle">&nbsp;</td>';
									}
									else
									{
									echo '<td align="right" valign="middle">&nbsp;'.number_format($tc_unit,$decimal_placed,".",",").'&nbsp;</td>';
									}
									echo '<td align="right" valign="middle">&nbsp;'.number_format($item_cem_consum,$decimal_placed,".",",").'&nbsp;</td>';
									echo '<td align="center" valign="middle">&nbsp;</td>';
									echo '</tr>';
									$total_cem_consum = $total_cem_consum + $item_cem_consum;
									$slno++;
								}
							}
						}
						$total_cem_consum_mt = round($total_cem_consum,$decimal_placed);
						echo '<tr class="label">';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="left" valign="middle">&nbsp;</td>';
						echo '<td align="right" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle" colspan="2">&nbsp;Qst</td>';
						echo '<td align="right" valign="middle">&nbsp;'.number_format($total_cem_consum_mt,$decimal_placed,".",",").'&nbsp;</td>';
						echo '<td align="center" valign="middle">mt</td>';
						echo '</tr>';
						/*$total_cem_consum_mt = ($total_cem_consum_kg/1000);
						$total_cem_consum_mt = round($total_cem_consum_mt,$decimal_placed);
						echo '<tr class="label">';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle">&nbsp;</td>';
						echo '<td align="left" valign="middle">&nbsp;</td>';
						echo '<td align="right" valign="middle">&nbsp;</td>';
						echo '<td align="center" valign="middle" colspan="2">&nbsp;Qc</td>';
						echo '<td align="right" valign="middle">&nbsp;'.number_format($total_cem_consum_mt,$decimal_placed,".",",").'&nbsp;</td>';
						echo '<td align="center" valign="middle">mt</td>';
						echo '</tr>';*/
						?>
								</table>
							</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<!--<div class="buttonsection">
									<input type="submit" name="submit" id="submit" value=" Submit "/>
								</div>-->
							</div>
                        </blockquote>
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal("", msg, "success");
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
			</script>
        </form>
    </body>
</html>
