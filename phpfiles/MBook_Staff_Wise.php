<?php
@ob_start();
require_once 'library/config.php'; 
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
checkUser();
$msg = '';
$newmbookno='';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
$mbooktype = "G";
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
    return $dd . '/' . $mm . '/' . $yy;
}
function getWordWrapCount($description,$char)
{
	$wrap_cnt 	= 0; 
	$descwork 	= "";
	$char_no 	= $char;
	$work_desc 	= $description;
	$desc 		= wordwrap($work_desc,$char_no,'<br>');
	$exp_line 	= explode('<br>', $desc);
	$wlcnt 		= count($exp_line);
	for($xc=0; $xc<$wlcnt; $xc++)
	{
		if($exp_line[$xc] != "")
		{
			$wrap_cnt++;
			$descwork .= $exp_line[$xc]."<br/> ";
		}
	}
	return array($descwork, $wrap_cnt);
}
function check_line($title,$table,$page,$mbookno,$newmbookno,$table1)
{
	if($page == 100) { $mbookno = $newmbookno; }
	$row = '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">&nbsp;<br/>Page '.$page.'&nbsp;&nbsp</td></tr>';
	$row = $row."</table>";
	$row = $row."<p  style='page-break-after:always;'></p>";
	$row = $row.'<table width="875" border="0"  cellpadding="1" cellspacing="1" align="center" class="label" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="center" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
	$row = $row.$table;
	$row = $row.'<table width="875" border="0" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
	$row = $row.$table1;
	echo $row;
}
$staff_design_sql = "select staff.staffname, designation.designationname from staff INNER JOIN designation ON (designation.designationid = staff.designationid) WHERE staff.staffid = '$staffid' AND staff.active = 1 AND designation.active = 1";
//echo $staff_design_sql;
$staff_design_query = mysql_query($staff_design_sql);
$staffList = mysql_fetch_object($staff_design_query);
$staffname = $staffList->staffname;
$designation = $staffList->designationname;
/*if($_POST["Back"] == " Back ")
{
     header('Location: Generate_Staff_Wise.php');
}*/
$sheetid		=	$_SESSION["sheet_id"]; 
$fromdate 		= 	$_SESSION['fromdate'];
$todate 		= 	$_SESSION['todate'];
$mbookno 		= 	$_SESSION["mb_no"];    
$mpage 			= 	$_SESSION["mb_page"];
$mbno_id 		= 	$_SESSION["mbno_id"];
$rbn 			= 	$_SESSION["rbn"];
$abstmbookno 	= 	$_SESSION["abs_mbno"];
$zone_id 		= 	$_SESSION["zone_id"];
if(($zone_id != "") && ($zone_id != "all"))
{
	$zone_clause = " AND mbookheader.zone_id = '".$zone_id."'";
}
else
{
	$zone_clause = "";
}
//echo $zone_clause."<br/>";
$oldmbookno 	=	$mbookno;
$oldmbookpage 	=	$mpage;
$query 			= "SELECT sheet_id, sheet_name, work_order_no, work_name, tech_sanction, name_contractor, computer_code_no, agree_no, rbn FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery 		= mysql_query($query);
if ($sqlquery == true) 
{
    $List 				= 	mysql_fetch_object($sqlquery);
    $work_name 			= 	$List->work_name;    
	$tech_sanction 		= 	$List->tech_sanction;
    $name_contractor 	= 	$List->name_contractor;    
	$agree_no 			= 	$List->agree_no; 
	$work_order_no 		= 	$List->work_order_no; 
	$ccno 				= 	$List->computer_code_no;
    //if($List->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$List->rbn + 1;}
	$runn_acc_bill_no 	= 	$rbn;
    $_SESSION["currentrbn"]=$runn_acc_bill_no;
}

$length = strlen($work_name);
//echo $length."<br/>";
$start_line = ceil($length/87);
//echo $start_line;
$mbookgeneratedelsql = "DELETE FROM mbookgenerate_staff WHERE flag =1 AND sheetid = '$sheetid' AND staffid = '$staffid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
$result = dbQuery($mbookgeneratedelsql);
if($_GET['varid'] == 1)
{
	$deletequery	=	mysql_query("DELETE FROM oldmbook WHERE sheetid = '$sheetid' AND mbook_type = 'G' AND staffid = '$staffid' AND zone_id = '$zone_id'");
	$deletequery_1	=	mysql_query("DELETE FROM mbookgenerate WHERE sheetid = '$sheetid'");
}
if($_GET['newmbook'] != "")
{
$newmbookno = $_GET['newmbook'];
$newmbookpage_query = "select mbpage, allotmentid from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
$newmbookpage_sql = mysql_query($newmbookpage_query);
$newmbookpage = @mysql_result($newmbookpage_sql,0,'mbpage')+1;

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>General M.Book</title>
        <link rel="stylesheet" href="script/font.css" />
</head>
		<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
		<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
		<link rel="stylesheet" href="css/button_style.css"></link>
	 	<link rel="stylesheet" href="js/jquery-ui.css">
	  	<script src="js/jquery-1.10.2.js"></script>
	  	<script src="js/jquery-ui.js"></script>
	  	<link rel="stylesheet" href="/resources/demos/style.css">
<script>
	function goBack()
	{
	   	url = "Generate_Staff_Wise.php";
		window.location.replace(url);
	}
  </script>
<style type="text/css" media="print,screen" >
	table
	{ 
		border-collapse: collapse; 
	}
	td 
	{ 
		border: 1px solid #CACACA;
		padding-top:5px;
		padding-bottom:5px; 
	}
	@media screen {
        div.divFooter {
            display: none;
        }
    }
    @media print {
        div.divFooter {
            position: fixed;
            bottom: 0;
        } 
		.header{
		display: none !important;
		}
		.printbutton{
		display: none !important;
		}}
.ui-dialog > .ui-widget-header {background: #20b2aa; font-size:12px;}
.breakAfter {
	page-break-before: always;
}
.labelcontent
{
	font-family:Microsoft New Tai Lue;
	font-size:12pt;
	color:#000000;
}
.labelheadblue{
color:#0000CD;
font-weight:bold;
font-size:12px;
}
.labelcontentblue
{
color:#0000CD;
font-weight:bold;
/*font-size:12pt;	*/
}
.label
{
	color:#0000CD;
}
.ui-dialog-titlebar-close {
  visibility: hidden;
}
.submit_btn{
position:absolute;
border:none;
top:110px;
left:80px;
font-weight:bold;
font-size:12px;
}
.cancel_btn{
position:absolute;
border:none;
top:110px;
left:160px;
font-weight:bold;
font-size:12px;
}
.submit_btn:hover {
     color:#000000;
     -moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
     -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
      box-shadow:0px 1px 4px rgba(0,0,0,5);
	  padding: 0.3em 1em;
  }
  .cancel_btn:hover {
     color:#000000;
     -moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
     -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
      box-shadow:0px 1px 4px rgba(0,0,0,5);
	  padding: 0.3em 1em;
  }
  .textboxcobf
  {
  	width:398px; 
	border:none; 
	text-align:right;
	font-weight:bold;
	color:#0000CD;
  }
 .title
{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	color:#FFFFFF; 
	border:none; 
	font-size:16px;
	font-weight:bold;
}

</style>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
<body bgcolor="#000000" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="875" style=" text-align:center; left:198px;" height="56px" align="center" bgcolor="#1babd3" class=''>
	<tr style="position:fixed;">
		<td class="title" width="874" height="56px" align="center" bgcolor="#1babd3">General Measurement Book</td>
	</tr>
</table>
<form name="form" id="form" method="post">
			<?php
			eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

            ?>
            <?php echo $table2; ?>
<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid."*".$zone_id; ?>" id="txt_mbno_id" />
<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class='label'>
<?php echo $table1; ?>
<?php

eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
                //echo $query;
$sqlquery = mysql_query($query);
 if ($sqlquery == true) 
{
	while ($List = mysql_fetch_object($sqlquery)) 
	{
		$decimal = get_decimal_placed($List->subdivid,$sheetid);
					//if($sheetid == 2)
					//{
						$measurement_contentarea = round($List->measurement_contentarea,$decimal);
					//}
					//else
					//{
						//$measurement_contentarea = $List->measurement_contentarea;
					//}
		if($currentline>40)
		{ 
		
		?>
		<tr height="" class="label labelbold">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right" class="">
				<?php 
				if($page == 100){ echo "C/o to page ".(0+1)." /General MB No.".$newmbookno; }
				else { echo "C/o to page ".($page+1)." /General MB No.".$mbookno; }
				?>
				</td>
				<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$prev_decimal,".",","); } ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
		</tr>
	<?php
		echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1);
?>
			<tr height="" class="label labelbold">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right"><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
				<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$prev_decimal,".",","); } ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
			</tr>
<?php 			
			$currentline = $start_line + 7; $page++;
		}
		if($List->subdivid != $prev_subdivid)// THIS IS FOR PRINT DATE, SHORTNOTE AND ITEM NAME
		{
		$querycount = "SELECT COUNT(DISTINCT date) FROM mbookheader WHERE mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND subdivid = '$List->subdivid' AND mbookheader.staffid = '$staffid' ".$zone_clause;
		//echo $querycount;
		$querycount_sql = mysql_query($querycount);
		$res = mysql_fetch_array($querycount_sql); 
		$rowcount = $res[0];
				if($prev_subdivid != "")
				{
				
					
					?>
					<tr height="">
						<td width="81"><?php echo "&nbsp"; ?></td>
						<td width="48" align="center"><?php echo "&nbsp"; ?></td>
						<td width="390" align="center" class="label labelbold">
						<?php 
						if($prev_rowcount>1)
						{ 
							if($prev_measure_type != 'st')
							{
							?>
								<input type="text" style="width:100%; border:none; text-align:right" class="label labelbold" name="txt_page" class="textboxcobf" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							}
						} 
						else 
						{ 
							echo "&nbsp"; 
						}
						?>
						</td>
						<td width="35" align="center"><?php echo "&nbsp"; ?></td>
						<td width="65" colspan="3" align="center" class="labelcontentblue">
						<?php 
						if($prev_measure_type != 'st')
						{
							echo "Total";
						}
						?>
						</td>
						<td width="65" align="right" class="labelcontentblue">
						<?php echo number_format($contentarea,$prev_decimal,".",","); ?>
						</td>
						<td width="32" align="center">
						<?php 
						if($prev_measure_type != 'st')
						{
							echo $prev_remarks;
						}
						{
							echo $prev_struct_unit;
						}
						?>
						</td>
					</tr>
					<?php 
					if($prev_measure_type == 'st')
					{
					$contentarea = ($contentarea/1000);
					?>
						<tr height="" class="label labelbold">
							<td width="81"><?php echo "&nbsp"; ?></td>
							<td width="48" align="center"><?php echo "&nbsp"; ?></td>
							<td width="390" align="center" class="">
							<?php if($prev_rowcount>1){ ?><input type="text" name="txt_page" class="textboxcobf" id="txt_page<?php echo $txtboxid; ?>" /><?php } else { echo "&nbsp"; }?>
							</td>
							<td width="35" align="center"><?php echo "&nbsp"; ?></td>
							<td width="65" colspan="3" align="center" class="labelcontentblue">Total</td>
							<td width="65" align="right" class="labelcontentblue">
							<?php echo number_format($contentarea,$prev_decimal,".",","); ?>
							</td>
							<td width="32" align="center"><?php echo $prev_remarks; ?></td>
						</tr>
					<?php
					}
					if(($prev_date != $List->date) && ($prev_date != ""))
					{
						echo "<tr style='border:none'><td style='border:none' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='right' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='center' colspan='3'></td><td style='border:none' align='left' colspan='3'>Prepared By</td><td style='border:none' align='center' colspan='3'>Checked By</td></tr>";
						$currentline+=3;
					}
					$sum1 .= $prev_subdivname.",".$prev_date.",".$prev_subdivid.",".$prev_divid.",".$contentarea.",".$prev_rowcount.",".$page.",".$txtboxid.",".$prev_decimal.",".$prev_remarks."@";
					$prev_contentarea = 0;
					$currentline++;	
				}
				if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
				//$len1 = strlen($List->shortnotes);
				//echo $length."<br/>";
				//$line_cnt1 = ceil($len1/96);
				//echo $List->subdiv_name." = ".$line_cnt1."<br/>";
				$snotes = $List->shortnotes;
				$degcelsius = "&#8451";
				$shortnotes = str_replace("DEGCEL","$degcelsius",$snotes);
				//  find the number of lines in item description
				$wrap_cnt1 = 0;
				$WrapReturn1 = getWordWrapCount($shortnotes,90);
				$shortnotes = $WrapReturn1[0];
				$wrap_cnt1 = $WrapReturn1[1];
		?>
			<!--<tr height="">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5"></td>
				<td width="65">&nbsp;</td>
				<td width="32">&nbsp;</td>
			</tr>-->
			<tr height="">
				<td width="81" align="center"><?php echo $List->date; ?></td>
				<td width="48" align="center"><?php echo $List->subdiv_name; ?></td>
				<td colspan="5"><?php echo $List->shortnotes; ?></td>
				<td width="65"><?php echo "&nbsp"; ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
			</tr>
		<?php
		$currentline = $currentline+$wrap_cnt1+1;
		}
		
		
		//88888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888//		
		if(($List->subdivid == $prev_subdivid) && ($prev_date != $List->date))
		{
		$querycount = "SELECT COUNT(DISTINCT date) FROM mbookheader WHERE mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND subdivid = '$List->subdivid' AND mbookheader.staffid = '$staffid'";
		//echo $querycount."<br/>";
		$querycount_sql = mysql_query($querycount);
		$res = mysql_fetch_array($querycount_sql); 
		$rowcount = $res[0];
				if($prev_subdivid != "")
				{
		?>
					<tr height="" class="label labelbold">
						<td width="81"><?php echo "&nbsp"; ?></td>
						<td width="48" align="center"><?php echo "&nbsp"; ?></td>
						<td width="390" align="right">
						<?php 
						if($prev_measure_type != 'st')
						{
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="label labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
							echo getcompositepage($sheetid,$prev_subdivid); 
							//echo $prev_subdivid;
							}
						}
						?>
						</td>
						<td width="35" align="center"><?php echo "&nbsp"; ?></td>
						<td width="65" colspan="3" align="center">
						<?php 
						if($prev_measure_type != 'st')
						{
							echo "Total";
						}
						?>
						</td>
						<td width="65" align="right"><?php echo number_format($contentarea,$prev_decimal,".",","); ?></td>
						<td width="32" align="center">
						<?php 
							if($prev_measure_type != 'st')
							{
								echo $prev_remarks;
							}
							{
								echo $prev_struct_unit;
							}
						?>
						</td>
					</tr>
					<?php 
					if($prev_measure_type == 'st')
					{
						$contentarea = ($contentarea/1000);
					?>
						<tr height="" class="label labelbold">
							<td width="81"><?php echo "&nbsp"; ?></td>
							<td width="48" align="center"><?php echo "&nbsp"; ?></td>
							<td width="390" align="right">
							<?php 
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="label labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
							echo getcompositepage($sheetid,$prev_subdivid); 
							}
							?>
							</td>
							<td width="35" align="center"><?php echo "&nbsp"; ?></td>
							<td width="65" colspan="3" align="center" class="label labelbold">Total</td>
							<td width="65" align="right" class="labelcontentblue">
							<?php echo number_format($contentarea,$prev_decimal,".",","); ?>
							</td>
							<td width="32" align="center"><?php echo $prev_remarks; ?></td>
						</tr>
					<?php
					}
					if(($prev_date != $List->date) && ($prev_date != ""))
					{
						echo "<tr style='border:none'><td style='border:none' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='right' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='center' colspan='3'></td><td style='border:none' align='left' colspan='3'>Prepared By</td><td style='border:none' align='center' colspan='3'>Checked By</td></tr>";
						$currentline+=3;
					}
					/*if($prev_rowcount == 1)
					{
						mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
					}*/
					$sum1 .= $prev_subdivname.",".$prev_date.",".$prev_subdivid.",".$prev_divid.",".$contentarea.",".$prev_rowcount.",".$page.",".$txtboxid.",".$prev_decimal.",".$prev_remarks."@"; 
					$prev_contentarea = 0;
					$currentline++;	
				}
				if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
				//$len1 = strlen($List->shortnotes);
				//echo $length."<br/>";
				//$line_cnt1 = ceil($len1/96);
				//echo $List->subdiv_name." = ".$line_cnt1."<br/>";
				$snotes = $List->shortnotes;
				$degcelsius = "&#8451";
				$shortnotes = str_replace("DEGCEL","$degcelsius",$snotes);
				
				//  find the number of lines in item description
				$wrap_cnt2 = 0;
				$WrapReturn2 = getWordWrapCount($shortnotes,90);
				$shortnotes = $WrapReturn2[0];
				$wrap_cnt2 = $WrapReturn2[1];
		?>
			<!--<tr height="">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5"></td>
				<td width="65">&nbsp;</td>
				<td width="32">&nbsp;</td>
			</tr>-->
			<tr height="">
				<td width="81" align="center"><?php echo $List->date; ?></td>
				<td width="48" align="center"><?php echo $List->subdiv_name; ?></td>
				<td colspan="5"><?php echo $shortnotes; ?></td>
				<td width="65"><?php echo "&nbsp"; ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
			</tr>
		<?php
		$currentline = $currentline+$wrap_cnt2+1;
		}
		
		
		
		
//88888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888//			

		
		
		
				//$len2 = strlen($List->descwork);
				//echo $length."<br/>";
				//$line_cnt2 = ceil($len2/55);
				//echo $List->subdiv_name." = ".$line_cnt2."<br/>";
				
				$wrap_cnt3 = 0;	
				$WrapReturn3 = getWordWrapCount($List->descwork,50);
				$descwork = $WrapReturn3[0];
				$wrap_cnt3 = $WrapReturn3[1];
				
		?>
		<!---  THE BELOW ROW IS FOR PRINT EACH RECORD ------>
			<tr height="">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48"><?php echo "&nbsp"; ?></td>
				<td width="390"><?php echo $List->descwork; ?></td>
				<td width="35" align="center"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?></td>
				<td width="65" align="right"><?php if($List->measurement_l != 0) { echo number_format($List->measurement_l,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($List->measurement_b != 0) { echo number_format($List->measurement_b,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($List->measurement_d != 0) { echo number_format($List->measurement_d,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($measurement_contentarea != 0) { echo number_format($measurement_contentarea,$decimal,".",","); } ?></td>
				<td width="32" align="center">
				<?php if($List->measurement_no != 0) 
				{ 
					if($List->measure_type == 'st')
					{
						//echo $List->structdepth_unit;
					}
					else
					{
						//echo $List->remarks; 
					}
				} 
				?>
				</td>
			</tr>
		<?php
		$contentarea = ($prev_contentarea + $measurement_contentarea);
		$prev_subdivid = $List->subdivid; $prev_subdivname = $List->subdiv_name; $prev_divid = $List->div_id; $prev_contentarea = $contentarea;
		$prev_date = $List->date; $prev_rowcount = $rowcount; $prevpage = $page; $prev_mbookno = $mbookno; $prev_struct_unit = $List->structdepth_unit;
		$currentline = $currentline+$wrap_cnt3; 
		$prev_measure_type = $List->measure_type; $prev_remarks = $List->remarks; $prev_decimal = $decimal;
		$txtboxid++; 
	} 
	?>
		<input type="hidden" name="txt_textboxcount" id="txt_textboxcount" value="<?php echo $txtboxid; ?>" />
		<!----  THIS ROW IS FOR PRINT TOTAL OF THE LAST ROW IN WHILE LOOP ----->
			<tr height="">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48" align="center"><?php echo "&nbsp"; ?></td>
				<td width="390" class="labelheadblue">
				<?php 
						if($prev_measure_type != 'st')
						{
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="label labelbold" style="width:100%; border:none; text-align:right;" name="txt_page" class="textboxcobf" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
							echo "&nbsp"; 
							//echo $prev_subdivid;
							}
						}
						?>
				</td>
				<td width="35" align="center"><?php echo "&nbsp"; ?></td>
				<td width="65" colspan="3" align="center" class="labelcontentblue">
				<?php 
						if($prev_measure_type != 'st')
						{
							echo "Total";
						}
						?>
				</td>
				<td width="65" align="right"  class="labelcontentblue"><?php echo number_format($contentarea,$prev_decimal,".",","); ?></td>
				<td width="32" align="center">
				<?php 
					if($prev_measure_type != 'st')
					{
						echo $prev_remarks;
					}
					{
						echo $prev_struct_unit;
					}
				?>
				</td>
			</tr> 
			<?php 
			if($prev_measure_type == 'st')
			{
				$contentarea = ($contentarea/1000);
				
					?>
						<tr height="">
							<td width="81"><?php echo "&nbsp"; ?></td>
							<td width="48" align="center"><?php echo "&nbsp"; ?></td>
							<td width="390" align="center">
							<?php if($prev_rowcount>1){ ?><input type="text" class="label labelbold" name="txt_page" class="textboxcobf" style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" /><?php } else { echo "&nbsp"; }?>
							</td>
							<td width="35" align="center"><?php echo "&nbsp"; ?></td>
							<td width="65" colspan="3" align="center" class="label labelbold">Total</td>
							<td width="65" align="right" class="label labelbold">
							gf<?php echo number_format($contentarea,$prev_decimal,".",","); ?>
							</td>
							<td width="32" align="center"><?php echo $prev_remarks; ?></td>
						</tr>
					<?php
					}
					
						echo "<tr style='border:none'><td style='border:none' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='right' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='center' colspan='3'></td><td style='border:none' align='left' colspan='3'>Prepared By</td><td style='border:none' align='center' colspan='3'>Checked By</td></tr>";
						$currentline+=3;
					?>
</table> 
 
<?php  
		$currentline+=3;
		$sum2 .= $prev_subdivname.",".$prev_date.",".$prev_subdivid.",".$prev_divid.",".$contentarea.",".$rowcount.",".$page.",".$txtboxid.",".$prev_decimal.",".$prev_remarks."@"; 
}

$sum = $sum1.$sum2;
$split_sum = explode('@',$sum);
natsort($split_sum);
// THIS "FOR EACH" STATEMENT IS FOR GENERATE STRING AFTER "SORTING"........
foreach($split_sum as $key => $summ)
{
   if($summ != "")
   {
      $summary .= $summ.",";
   }
}
//echo $summary;exit;
$summary1 = explode(',',rtrim($summary,","));
$prev_val = "";$count = 0;
// THIS IS FOR LOOP IS FOR CHECK WHETHER SUMMARY PART HAS TO BE PRINT OR NOT...
for($i=0;$i<count($summary1);$i+=10)
{
	if($summary1[$i+5]>1)
	{
		$count++;
	}
	$prev_val = $summary1[$i+5];
}
if($count>0)
{
					
	
	
	if($currentline>27)
	{
	if($page == 100) { $mbookno = $newmbookno; }
	echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
	echo '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">Page '.$page.'&nbsp;&nbsp</td></tr>';
	echo '</table>';
	echo "<p  style='page-break-after:always;'></p>";
		echo '<table width="875" border="0"  cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="right" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
		echo $table;
		$currentline = $start_line + 8;$page++;
	}
	echo '<table width="875" border="0" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
	echo '<tr><td colspan="9" align="center"  style="color:#0000CD; font-weight:bold;">Summary</td></tr>';
	$contentarea = 0;$prev_subdivid = "";
	for($i=0;$i<count($summary1);$i+=10)
	{
		//if($sheetid == 2)
		//{
		//$sum_qty = round(sum_qty,$summary1[$i+8]);
			$sum_qty = round($summary1[$i+4],$summary1[$i+8]);
		//}
		//else
		//{
			//$sum_qty = $summary1[$i+4];
		//}
		//echo $sum_qty."<br/>";
		if($page > 100)
					{ 
						$currentline = $start_line + 7;
						$prevpage = 100;
						$page = $newmbookpage;
						//$prevpage = $mpage;
						//$oldmbookno = $mbookno;
						$mbookno = $newmbookno;
						
					}	
		if($currentline>40)
		{
?>
<tr height="" class="label labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right" class=""><?php echo "C/o to page ".($page+1)." /General MB No.".$mbookno; ?></td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1); ?>
<tr height="" class="label labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right" class=""><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php 
			$currentline = $start_line + 8;$page++;
		}
		//echo "PRE ID".$prev_subdivid."<br/>";
		if($summary1[$i+5]>1)
		{
			if(($summary1[$i+2] != $prev_subdivid) && ($prev_subdivid != ""))
			{
?>
			<tr height="">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48" align="center"><?php echo "&nbsp"; ?></td>
				<td width="390" align="right" class="label labelbold">Total&nbsp;&nbsp;</td>
				<td width="35" align="center"><?php echo "&nbsp"; ?></td>
				<td width="65" align="center"><?php echo "&nbsp"; ?></td>
				<td width="65" align="center"><?php echo "&nbsp"; ?></td>
				<td width="65" align="center"><?php echo "&nbsp"; ?></td>
				<td width="65" align="right" class="label labelbold"><?php echo number_format($contentarea,$pre_decimal,".",","); ?></td>
				<td width="32" align="center"><?php echo $pre_remarks; ?></td>
			</tr>
<?php 		
	//$summary_b .= $summary1[$i+7].",".$page."*";
			$contentarea = 0;	$currentline++;		
			}
?>
		<tr height="">
			<td width="81"><?php echo $summary1[$i+1]; ?></td>
			<td width="48" align="center"><?php echo $summary1[$i]; ?></td>
			<td width="390"><?php echo "B/f from page no ".$summary1[$i+6]; ?></td>
			<td width="35" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="right"><?php echo number_format($sum_qty,$summary1[$i+8],".",","); ?></td>
			<td width="32" align="center"><?php echo "&nbsp"; ?></td>
		</tr>
<?php	
			$summary_b .= $summary1[$i+7].",".$page.",".$summary1[$i].",";
			$contentarea = $contentarea + $sum_qty;	$currentline++;
			$prev_subdivid = $summary1[$i+2]; $prev_subdivname = $summary1[$i]; $prev_divid = $summary1[$i+3];	$prev_textboxid = $summary1[$i+7];
			$pre_page = $page; $pre_decimal = $summary1[$i+8]; $pre_remarks = $summary1[$i+9];
		}
	}
?>
		<tr height="" border="1px" style="border-bottom:solid; border-bottom-color:#CACACA;">
			<td width="81"><?php echo "&nbsp"; ?></td>
			<td width="48" align="center"><?php echo "&nbsp"; ?></td>
			<td width="390" align="right" class="labelcontentblue">Total&nbsp;&nbsp;</td>
			<td width="35" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="right" class="labelcontentblue"><?php echo number_format($contentarea,$pre_decimal,".",","); ?></td>
			<td width="32" align="center"><?php echo $pre_remarks; ?></td>
		</tr>
<?php 
echo '</table>';
echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" width="53%" align="right">&nbsp;<br/><br/><br/><br/>Page '.$page.'<br/><br/><br/><br/>&nbsp;&nbsp</td>
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '</table>';
}
else
{
echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" colspan="9" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" width="53%" align="right">&nbsp;Page '.$page.'&nbsp;&nbsp</td>
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '</table>';
}
?>
<input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($summary_b,","); ?>"  />
<table border="0" width="875" style="border-style:none" align="center" bgcolor="#000000" class='labelcontent printbutton'>
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none">&nbsp;
		</td>
	</tr>
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none" align="center">
			<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
		</td>
	</tr>
</table>  
</form>
    </body>
</html>