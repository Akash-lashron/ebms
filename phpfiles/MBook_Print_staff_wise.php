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
	$row = $row.'<table width="875" border="0"  cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="center" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
	$row = $row.$table;
	$row = $row.'<table width="875" border="0" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
	$row = $row.$table1;
	echo $row;
}
$staff_design_sql = "select staff.staffname, designation.designationname from staff INNER JOIN designation ON (designation.designationid = staff.designationid) WHERE staff.staffid = '$staffid' AND staff.active = 1 AND designation.active = 1";
$staff_design_query = mysql_query($staff_design_sql);
$staffList = mysql_fetch_object($staff_design_query);
$staffname = $staffList->staffname;
$designation = $staffList->designationname;
$zone_id = $_SESSION['zone_id'];
if(($zone_id != "") && ($zone_id != "all"))
{
	$zone_clause = " AND mbookheader.zone_id = '".$zone_id."'";
}
else
{
	$zone_clause = "";
}
if($_GET['workno'] != "")
{
	$sheetid = $_GET['workno'];
}
if($_POST["Back"] == " Back ")
{
     header('Location: MeasurementBookPrint_staff.php');
}
$select_rbn_query = "select DISTINCT rbn FROM mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1'";
//$select_rbn_query = "select DISTINCT rbn FROM mbookgenerate WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1'";
$select_rbn_sql = mysql_query($select_rbn_query);
$Rbnresult = mysql_fetch_object($select_rbn_sql);
$rbn = $Rbnresult->rbn;
$selectmbook_detail = " select DISTINCT fromdate, todate, abstmbookno FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1' AND rbn = '$rbn' AND zone_id = '$zone_id'";
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno;
}

//$fromdate = '2016-05-30';
//$todate = '2016-09-30';
$selectmbookno = "select mbname, old_id from oldmbook WHERE mbook_type = 'G' AND sheetid = '$sheetid' AND staffid = '$staffid'";
$selectmbookno_sql = mysql_query($selectmbookno);
if(mysql_num_rows($selectmbookno_sql)>0)
{
	$Listmbookno = mysql_fetch_object($selectmbookno_sql);
	$mbookno = $Listmbookno->mbname; $oldmbookid = $Listmbookno->old_id;
	
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
	
	$selectnewmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '1' AND mbno != '$mbookno' AND staffid = '$staffid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
	$selectnewmbookno_sql = mysql_query($selectnewmbookno);
	$newmbookno = @mysql_result($selectnewmbookno_sql,'mbno');
	
	$newmbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
	$newmbookpage_sql = mysql_query($newmbookpage);
	$newmbookpageno = @mysql_result($newmbookpage_sql,'mbpage')+1;
	
//$newmbookpageno = $objBind->DisplayPageDetails($newmbookno,$newmbookno,$sheetid,'cw');
//$newmbookpageno = $newmbookpageno +1;
}
else
{
	$selectmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '1' AND staffid = '$staffid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
	$selectmbookno_sql = mysql_query($selectmbookno);
	$mbookno = @mysql_result($selectmbookno_sql,'mbno');
	
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
}
//$mbookpageno = $objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw');
//$mbookpageno = $mbookpageno+1;

/*echo "NEW MB ".$newmbookno."<br/>";
echo "NEW MB PAGE ".$newmbookpageno."<br/>";
echo "OLD MB ".$mbookno."<br/>";
echo "OLD MB PAGE ".$mbookpageno."<br/>";
exit;*/
$mpage = $mbookpageno;
//$sheetid=$_SESSION["sheet_id"]; 
//$fromdate = $_SESSION['fromdate'];
//$todate = $_SESSION['todate'];
//$mbookno = $_SESSION["mb_no"];    
//$mpage = $_SESSION["mb_page"];
//$mbno_id = $_SESSION["mbno_id"];
//$rbn = $_SESSION["rbn"];
//$abstmbookno = $_SESSION["abs_mbno"];
$query = "SELECT sheet_id, sheet_name, work_order_no, work_name, tech_sanction, name_contractor, computer_code_no, agree_no, rbn FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery = mysql_query($query);
if ($sqlquery == true) {
    $List = mysql_fetch_object($sqlquery);
    $work_name = $List->work_name;    $tech_sanction = $List->tech_sanction;
    $name_contractor = $List->name_contractor;    $agree_no = $List->agree_no; $work_order_no = $List->work_order_no; 
	$ccno = $List->computer_code_no;
    //if($List->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$List->rbn + 1;}
	$runn_acc_bill_no = $rbn;
    //$_SESSION["currentrbn"]=$runn_acc_bill_no;
}

$length = strlen($work_name);
//echo $length."<br/>";
$start_line = ceil($length/87);
//echo $start_line;
/*$mbookgeneratedelsql = "DELETE FROM mbookgenerate WHERE flag =1";
$result = dbQuery($mbookgeneratedelsql);
function mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$mpage,$contentarea,$abstmbookno,$rbn,$userid)
{ 
   $querys="INSERT INTO mbookgenerate set sheetid='$sheetid',divid='$prev_divid',subdivid='$prev_subdivid',
       fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=1,rbn='$rbn', abstmbookno = '$abstmbookno',
            mbgeneratedate=NOW(), staffid='$staffid', mbpage='$mpage', mbtotal='$contentarea', active=1, userid='$userid'";
 //echo $querys."<br>";
   $sqlquerys = mysql_query($querys);
}*/
function getabstractpage($sheetid,$subdivid)
{
	$select_abs_page_query = "select abstmbookno, abstmbpage from measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql = mysql_query($select_abs_page_query);
	$abstmbookno = @mysql_result($select_abs_page_sql,0,'abstmbookno');
	$abstractpage = @mysql_result($select_abs_page_sql,0,'abstmbpage');
	return "C/o to Page ".$abstractpage." /Abstract MB No. ".$abstmbookno;
}
function getcompositepage($sheetid,$subdivid)
{
	$select_abs_page_query = "select mbno, mbpage from mbookgenerate WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql = mysql_query($select_abs_page_query);
	$mbookno_compo = @mysql_result($select_abs_page_sql,0,'mbno');
	$mbookpageno_compo = @mysql_result($select_abs_page_sql,0,'mbpage');
	return "C/o to Page ".$mbookpageno_compo." /General MB No. ".$mbookno_compo;
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
		<script src="js/printPage.js"></script>
<!--<script>
  $(function() {
   $("#dialog").dialog({ autoOpen: false,
        minHeight: 200,
        maxHeight:200,
        minWidth: 300,
        maxWidth: 300,
        modal: true,});
        $("#dialog").dialog("open");
        $( "#dialog" ).dialog( "option", "draggable", false );
		$('#btn_cancel').click(function(){
		$("#dialog").dialog("close");
		window.location.href="Generate.php";
		});
        $('#btn').click(function(){
		var x = $('#newmbooklist option:selected').val();
		//alert(x);
			if(x == "")
			{
				var a="* Please select Next Mbook number";
				$('#error_msg').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				$("#dialog").dialog("close");       
				var newmbookvalue = $("#newmbooklist option:selected").text(); //alert(newmbookvalue);
				var oldmbookdetails = document.form.txt_mbno_id.value;
				$.post("GetOldMbookNo.php", {oldmbook: oldmbookdetails}, function (data) {
				window.location.href="Mbook.php?newmbook="+newmbookvalue;
				return false; // avoid to execute the actual submit of the form.
				});
			 }
         });
				$.fn.validatenewmbook = function(event) 
				{ 
					if($('#newmbooklist option:selected').val()=="")
					{ 
						var a="Please select Next Mbook number";
						$('#error_msg').text(a);
						event.preventDefault();
						event.returnValue = false;
						//return false;
					}
					else
					{
						var a="";
						$('#error_msg').text(a);
					}
				}
				$("#newmbooklist").change(function(event){
				   $(this).validatenewmbook(event);
				 });

  });
  </script>-->
  
<style type="text/css" media="print,screen" >
	table
	{ 
		border-collapse: collapse;
	}
	td 
	{ 
		border: 1px solid #A0A0A0;
		padding-top:5px;
		padding-bottom:5px;
	}
	@media screen 
	{
        div.divFooter 
		{
            display: none;
        }
    }
    @media print 
	{
        div.divFooter 
		{
            position: fixed;
            bottom: 0;
        } 
		.header
		{
			display: none !important;
		}
		.printbutton
		{
			display: none !important;
		}
	}
	.ui-dialog > .ui-widget-header {background: #20b2aa; font-size:12px;}
	.breakAfter 
	{
		page-break-before: always;
	}
	.labelcontent
	{
		font-family:Microsoft New Tai Lue;
		font-size:12pt;
		color:#000000;
	}
	.ui-dialog-titlebar-close 
	{
	  visibility: hidden;
	}
	.submit_btn
	{
		position:absolute;
		border:none;
		top:110px;
		left:80px;
		font-weight:bold;
		font-size:12px;
	}
	.cancel_btn
	{
		position:absolute;
		border:none;
		top:110px;
		left:160px;
		font-weight:bold;
		font-size:12px;
	}
	.submit_btn:hover 
	{
		color:#000000;
		-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
		-webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
		box-shadow:0px 1px 4px rgba(0,0,0,5);
		padding: 0.3em 1em;
	}
	.cancel_btn:hover 
	{
		color:#000000;
		-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
		-webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
		box-shadow:0px 1px 4px rgba(0,0,0,5);
		padding: 0.3em 1em;
	}
	.label, .labelcenter, .labelheadblue
	{
		font-size:13px;
	}
	.divclass
	{
		/*background-color:#009999;*/
		width:875px;
		padding:0px;
		border:1px solid #000000;
	}
</style>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
<body bgcolor="" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--<table width="875" style="position:fixed; text-align:center; left:194px;" height="60px" align="center" bgcolor="#20b2aa" class='header'>
<tr>
<td style="color:#FFFFFF; border:none; font-weight:bold; font-size:20px;">GENERAL MEASUREMENT BOOK</td>
</tr>
</table><br/><br/><br/>-->
<form name="form" id="form" method="post">
			<?php
			$title = '<table width="875" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="center" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;<br/>&nbsp;</td></tr>
			</table>';
            echo $title;
			
			eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

            //$table = $table . "</table>";
            ?>
            <?php echo $table2; ?>
<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid; ?>" id="txt_mbno_id" />
<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class='label'>
<?php echo $table1; ?>
<?php

eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
                //echo $query ;exit;
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
					if($page > 100)
					{
						/*if($_GET['varid'] == 1)
						{
							?>
							<div id="dialog" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
							<p style="font-size:12px; font-weight:bold; color:#911200;">Select Next MBook Number</p>
							<select id="newmbooklist" name="mb" style="width:275px;">
							<option value="">---------------------Select--------------------</option>
							<?php echo $objBind->BindMBookList($mbookno,$sheetid,$staffid,$mbooktype); ?>
							</select>
							<br/>
							<span id="error_msg" style="color:#FF0000; font-weight:bold;"></span>
							<input type="button" class="submit_btn" id="btn" style="color:#FFFFFF;background-color:#9c27b0;border:none;" name="btn" value="Submit"/>
							<input type="button" class="cancel_btn" id="btn_cancel" style="color:#FFFFFF;background-color:#e51c23;border:none;" name="btn_cancel" value="Cancel"/>
							</div>
							<?php
						}*/
						$currentline = $start_line + 7;
						$prevpage = 100;
						$page = $newmbookpageno;
						//$prevpage = $mpage;
						//$oldmbookno = $mbookno;
						$mbookno = $newmbookno;
						
					}
		if($currentline>40)
		{ 
		
		?>
		<tr height="" class="labelbold">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right">
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
			<tr height="" class="labelbold">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right"><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
				<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$prev_decimal,".",","); } ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
			</tr>
<?php 			
			$currentline = $start_line + 7; $page++;
		}
		if($List->subdivid != $prev_subdivid) // THIS IS FOR PRINT DATE, SHORTNOTE AND ITEM NAME
		//if(($List->subdivid != $prev_subdivid) || (($List->subdivid != $prev_subdivid) &&($prev_date != $List->date) && ($prev_date != "")))// THIS IS FOR PRINT DATE, SHORTNOTE AND ITEM NAME
		{
		$querycount = "SELECT COUNT(DISTINCT date) FROM mbookheader WHERE mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND subdivid = '$List->subdivid' AND mbookheader.staffid = '$staffid' ".$zone_clause;
		//echo $querycount."<br/>";
		$querycount_sql = mysql_query($querycount);
		$res = mysql_fetch_array($querycount_sql); 
		$rowcount = $res[0];
				if($prev_subdivid != "")
				{
		?>
					<tr height="" class="labelbold">
						<td width="81"><?php echo "&nbsp"; ?></td>
						<td width="48" align="center"><?php echo "&nbsp"; ?></td>
						<td width="390" align="right">
						<?php 
						if($prev_measure_type != 'st')
						{
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
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
						<td width="65" colspan="3" align="right">
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
						<tr height="" class="labelbold">
							<td width="81"><?php echo "&nbsp"; ?></td>
							<td width="48" align="center"><?php echo "&nbsp"; ?></td>
							<td width="390" align="right">
							<?php 
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
							echo getcompositepage($sheetid,$prev_subdivid); 
							}
							?>
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
						echo "<tr style='border:none'><td style='border:none' align='center' colspan='3'></td><td style='border:none' align='left' colspan='3'>Checked By</td><td style='border:none' align='center' colspan='3'>Prepared By</td></tr>";
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
				$wrap_cnt1 = 0;
				$WrapReturn1 = getWordWrapCount($shortnotes,90);
				$shortnotes = $WrapReturn1[0];
				$wrap_cnt1 = $WrapReturn1[1];
				//echo $wrap_cnt1;
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
		$currentline = $currentline+$wrap_cnt1+1;
		}
		
//88888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888//		
		if(($List->subdivid == $prev_subdivid) && ($prev_date != $List->date))
		{
		$querycount = "SELECT COUNT(DISTINCT date) FROM mbookheader WHERE mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND subdivid = '$List->subdivid' AND mbookheader.staffid = '$staffid' ".$zone_clause;
		//echo $querycount."<br/>";
		$querycount_sql = mysql_query($querycount);
		$res = mysql_fetch_array($querycount_sql); 
		$rowcount = $res[0];
				if($prev_subdivid != "")
				{
		?>
					<tr height="" class="labelbold">
						<td width="81"><?php echo "&nbsp"; ?></td>
						<td width="48" align="center"><?php echo "&nbsp"; ?></td>
						<td width="390" align="right">
						<?php 
						if($prev_measure_type != 'st')
						{
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
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
						<td width="65" colspan="3" align="right">
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
						<tr height="" class="labelbold">
							<td width="81"><?php echo "&nbsp"; ?></td>
							<td width="48" align="center"><?php echo "&nbsp"; ?></td>
							<td width="390" align="right">
							<?php 
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
							echo getcompositepage($sheetid,$prev_subdivid); 
							}
							?>
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
						echo "<tr style='border:none'><td style='border:none' align='center' colspan='3'></td><td style='border:none' align='left' colspan='3'>Checked By</td><td style='border:none' align='center' colspan='3'>Prepared By</td></tr>";
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
				/*$work_desc = $List->descwork;
				$desc = wordwrap($work_desc,50,'<br>');
				$wwl = explode('<br>', $desc);
				$wwlcount = count($wwl);
				$wrap_cnt = 0; $descwork = "";
				for($xc=0; $xc<$wwlcount; $xc++)
				{
					if($wwl[$xc] != "")
					{
						$wrap_cnt++;
						$descwork .= $wwl[$xc]."<br/> ";
						//echo $xc." = ".$wwl[$xc]."<br/>";
					}
				}*/
				//  find the number of lines in work description
				$wrap_cnt3 = 0;	
				$WrapReturn3 = getWordWrapCount($List->descwork,50);
				$descwork = $WrapReturn3[0];
				$wrap_cnt3 = $WrapReturn3[1];
				
				//$descri = preg_replace('/[\n\r]+/', '', $desc);
				//echo $ccc."<br/>";
				//echo $desc."<br/>";
				//echo $wwlcount;exit;
				//print_r($wwl); echo "<br/>";
				
				
				
				//$len2 = strlen($List->descwork);
				//echo $length."<br/>";
				//$line_cnt2 = ceil($len2/55);
				//echo $List->subdiv_name." = ".$line_cnt2."<br/>";
		?>
		<!---  THE BELOW ROW IS FOR PRINT EACH RECORD ------>
			<tr height="">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48"><?php //echo $currentline;//echo $wrap_cnt1."-".$wrap_cnt2."-".$ccc; ?></td>
				<td width="390"><?php echo $descwork; ?></td>
				<td width="35" align="right"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?></td>
				<td width="65" align="right"><?php if($List->measurement_l != 0) { echo number_format($List->measurement_l,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($List->measurement_b != 0) { echo number_format($List->measurement_b,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($List->measurement_d != 0) { echo number_format($List->measurement_d,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($measurement_contentarea != 0) { echo number_format($measurement_contentarea,$decimal,".",","); } ?></td>
				<td width="32" align="center">
				<?php 
				 if($List->measurement_no != 0) 
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
		//$contentarea = round(($prev_contentarea + $measurement_contentarea),3);
		$contentarea = ($prev_contentarea + $measurement_contentarea);
		$prev_subdivid = $List->subdivid; $prev_subdivname = $List->subdiv_name; $prev_divid = $List->div_id; $prev_contentarea = $contentarea;
		$prev_date = $List->date; $prev_rowcount = $rowcount; $prevpage = $page; $prev_mbookno = $mbookno; $prev_struct_unit = $List->structdepth_unit;
		
		//$currentline = $currentline+$line_cnt2; 
		$currentline = $currentline+$wrap_cnt3; 
		
		$prev_measure_type = $List->measure_type; $prev_remarks = $List->remarks; $prev_decimal = $decimal;
		$txtboxid++; 
	} 
	?>
		<input type="hidden" name="txt_textboxcount" id="txt_textboxcount" value="<?php echo $txtboxid; ?>" />
		<!----  THIS ROW IS FOR PRINT TOTAL OF THE LAST ROW IN WHILE LOOP ----->
			<tr height="" class="labelbold">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48" align="center"><?php echo "&nbsp"; ?></td>
				<td width="390" align="right">
				<?php 
						if($prev_measure_type != 'st')
						{
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
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
				<td width="65" colspan="3" align="right">
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
						<tr height="" class="labelbold">
							<td width="81"><?php echo "&nbsp"; ?></td>
							<td width="48" align="center"><?php echo "&nbsp"; ?></td>
							<td width="390" align="right">
							<?php 
							if($prev_rowcount>1)
							{ 
							?>
								<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
								echo getcompositepage($sheetid,$prev_subdivid);  
							}
							?>
							</td>
							<td width="35" align="center"><?php echo "&nbsp"; ?></td>
							<td width="65" colspan="3" align="right" class="labelcontentblue">Total</td>
							<td width="65" align="right" class="labelcontentblue">
							<?php echo number_format($contentarea,$prev_decimal,".",","); ?>
							</td>
							<td width="32" align="center"><?php echo $prev_remarks; ?></td>
						</tr>
					<?php
					}
					
						echo "<tr style='border:none'><td style='border:none' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='right' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='center' colspan='3'></td><td style='border:none' align='left' colspan='3'>Checked By</td><td style='border:none' align='center' colspan='3'>Prepared By</td></tr>";
						$currentline+=3;
					?>
</table> 
 
<?php  
		/*if($prev_rowcount == 1)
		{
			mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
		}*/
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
	echo '<table width="875" border="0" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF"  class="label" style="border-style:none;">';
	echo '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">Summary</td></tr>';
	$contentarea = 0;$prev_subdivid = "";
	for($i=0;$i<count($summary1);$i+=10)
	{
		//if($sheetid == 2)
		//{
		//sum_qty = round(sum_qty,$summary1[$i+8]);
			$sum_qty = round($summary1[$i+4],$summary1[$i+8]);
		//}
		//else
		//{
			//$sum_qty = $summary1[$i+4];
		//}
		if($currentline>40)
		{
		//if($page == 100) { $mbookno = $newmbookno; }
?>
<tr height="" class="labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right"><?php echo "C/o to page ".($page+1)." /General MB No.".$mbookno; ?></td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1); ?>
<tr height="" class="labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right"><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
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
			<tr height="" class="labelbold">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48" align="center"><?php echo "&nbsp"; ?></td>
				<td width="390" align="right"><?php echo getcompositepage($sheetid,$prev_subdivid); ?></td>
				<td width="35" align="center"><?php echo "&nbsp"; ?></td>
				<!--<td width="65" align="center"></td>
				<td width="65" align="center"></td>-->
				<td width="195" colspan="3" align="right"><?php echo "Total"; ?></td>
				<td width="65" align="right"><?php echo number_format($contentarea,$pre_decimal,".",","); ?></td>
				<td width="32" align="center"><?php echo $pre_remarks; ?></td>
			</tr>
<?php 		
	//$summary_b .= $summary1[$i+7].",".$page."*";echo getabstractpage($sheetid,$prev_subdivid);
	//mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
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
		<tr height="" border="1px" style="border-bottom:solid; border-bottom-color:#CACACA;" class="labelbold">
			<td width="81"><?php echo "&nbsp"; ?></td>
			<td width="48" align="center"><?php echo "&nbsp"; ?></td>
			<td width="390" align="right"><?php echo getcompositepage($sheetid,$prev_subdivid); ?></td>
			<td width="35" align="center"><?php echo "&nbsp"; ?></td>
<!--		<td width="65" align="center"></td>
			<td width="65" align="center"></td>-->
			<td width="195" colspan="3" align="right"><?php echo "Total"; ?></td>
			<td width="65" align="right"><?php echo number_format($contentarea,$pre_decimal,".",","); ?></td>
			<td width="32" align="center"><?php echo $pre_remarks; ?></td>
		</tr>
<?php 
//mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
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
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp;&nbsp</td>
		</tr>';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" width="53%" align="right">Page '.$page.'&nbsp;&nbsp</td>
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>&nbsp;</td>
		</tr>';
echo '</table>';
}
?>
<div align="center" class="btn_outside_sect printbutton">
	<div class="btn_inside_sect"><input type="submit" name="Back" value=" Back " /> </div>
	<div class="btn_inside_sect"><input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" /></div>
	<div class="btn_inside_sect">
		<a href="exportexcel.php?workno=<?php echo $sheetid;?>" style="text-decoration:none">
			<input type="button" class="backbutton" name="export_excel" value="Excel" />
		</a>
	</div>
</div>
<!--<table border="0" width="875" style="border-style:none" align="center" bgcolor="#000000" class='labelcontent printbutton'>
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none">&nbsp;
		</td>
		<td border="0" style="border-style:none">&nbsp;
		</td>
		<td border="0" style="border-style:none">&nbsp;
		</td>
	</tr>
	<tr border="0" style="border-style:none" height="35px;">
		<td border="0" style="border-style:none" align="right">
			<input type="submit" name="Back" value=" Back " /> 
		</td>
		<td border="0" style="border-style:none" width="20px">&nbsp;
		</td>
		<td border="0" style="border-style:none" align="left">
			<input type="button" class="backbutton" name="print" value=" Print " /> 
		</td>
	</tr>
</table>  -->
</div>
<style>

</style>
    </body>
</html>