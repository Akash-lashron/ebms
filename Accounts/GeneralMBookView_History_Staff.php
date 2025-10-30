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
			<tr style="border:none;"><td align="right" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
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
if(($_GET['sheetid'] != "") && ($_GET['rbn'] != ""))
{
	$sheetid = $_GET['sheetid'];
	$rbn = $_GET['rbn'];
}
/*if($_POST["Back"] == " Back ")
{
     header('Location: MeasurementBookPrint_staff.php');
}*/
//$select_rbn_query = "select DISTINCT rbn FROM measurementbook WHERE sheetid = '$sheetid' AND flag = '1'";
//$select_rbn_query = "select DISTINCT rbn FROM mbookgenerate WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1'";
//$select_rbn_sql = mysql_query($select_rbn_query);
//echo $select_rbn_query."<br>";
//$Rbnresult = mysql_fetch_object($select_rbn_sql);
//$rbn = $Rbnresult->rbn;
$selectmbook_detail = " select DISTINCT fromdate, todate, abstmbookno FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1' AND rbn = '$rbn' AND zone_id = '$zone_id'";
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
//echo $selectmbook_detail;
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno;
}
//$fromdate = "2018-05-01"; $todate = "2018-05-31";
$selectmbookno_sql = "select mbno, startpage, endpage, mbookorder from mymbook WHERE mtype = 'G' AND sheetid = '$sheetid' AND staffid = '$staffid' AND rbn = '$rbn' AND genlevel = 'staff' AND zone_id = '$zone_id'";
//echo $selectmbookno_sql;
$selectmbookno_query = mysql_query($selectmbookno_sql);
$cnt = mysql_num_rows($selectmbookno_query);
if(mysql_num_rows($selectmbookno_query) == 1)
{
	$mbookno = @mysql_result($selectmbookno_query,0,'mbno');
	$mpage = @mysql_result($selectmbookno_query,0,'startpage');
}
if(mysql_num_rows($selectmbookno_query) == 2)
{
	while($MBookList = mysql_query($selectmbookno_query))
	{
		if($MBookList->mbookorder == 1)
		{
			$mbookno = $MBookList->mbno;
			$mpage = $MBookList->startpage;
		}
		if($MBookList->mbookorder == 2)
		{
			$newmbookno = $MBookList->mbno;
			$newmbookpageno = $MBookList->startpage;
		}
	}
}
//echo $mpage;
/*if($selectmbookno_query == true)
{
	while($MBookList = mysql_query($selectmbookno_query))
	{
		
	}
}*/
//$selectmbookno = "select mbname, old_id from oldmbook WHERE mbook_type = 'G' AND sheetid = '$sheetid' AND staffid = '$staffid'";
//$selectmbookno_sql = mysql_query($selectmbookno);
//if(mysql_num_rows($selectmbookno_sql)>0)
//{
//	$Listmbookno = mysql_fetch_object($selectmbookno_sql);
//	$mbookno = $Listmbookno->mbname; $oldmbookid = $Listmbookno->old_id;
//	
//	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
//	$mbookpage_sql = mysql_query($mbookpage);
//	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
//	
//	$selectnewmbookno = "select DISTINCT mbno from measurementbook WHERE sheetid = '$sheetid' AND flag = '1' AND mbno != '$mbookno' AND staffid = '$staffid' AND rbn = '$rbn'";
//	$selectnewmbookno_sql = mysql_query($selectnewmbookno);
//	$newmbookno = @mysql_result($selectnewmbookno_sql,'mbno');
//	
//	$newmbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
//	$newmbookpage_sql = mysql_query($newmbookpage);
//	$newmbookpageno = @mysql_result($newmbookpage_sql,'mbpage')+1;
//	
////$newmbookpageno = $objBind->DisplayPageDetails($newmbookno,$newmbookno,$sheetid,'cw');
////$newmbookpageno = $newmbookpageno +1;
//}
//else
//{
//	$selectmbookno = "select DISTINCT mbno from measurementbook WHERE sheetid = '$sheetid' AND flag = '1' AND staffid = '$staffid' AND rbn = '$rbn'";
//	$selectmbookno_sql = mysql_query($selectmbookno);
//	$mbookno = @mysql_result($selectmbookno_sql,'mbno');
//	
//	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
//	$mbookpage_sql = mysql_query($mbookpage);
//	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
//}
//$mbookpageno = $objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw');
//$mbookpageno = $mbookpageno+1;

/*echo "NEW MB ".$newmbookno."<br/>";
echo "NEW MB PAGE ".$newmbookpageno."<br/>";
echo "OLD MB ".$mbookno."<br/>";
echo "OLD MB PAGE ".$mbookpageno."<br/>";
exit;*/
//$mpage = $mbookpageno;
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
	$select_abs_page_query = "select abstmbookno, abstmbpage from measurementbook WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql = mysql_query($select_abs_page_query);
	$abstmbookno = @mysql_result($select_abs_page_sql,0,'abstmbookno');
	$abstractpage = @mysql_result($select_abs_page_sql,0,'abstmbpage');
	return "C/o to Page ".$abstractpage." /Abstract MB No. ".$abstmbookno;
}
function getcompositepage($sheetid,$subdivid)
{
	$select_abs_page_query = "select mbno, mbpage from measurementbook WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
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
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() 
	{ 
		window.history.forward(); 
	}
	function goBack()
	{
		url = "MBookGenerate_History_Staff.php";
		window.location.replace(url);
	}
</script>
<body bgcolor="" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--<table width="875" style="position:fixed; text-align:center; left:194px;" height="60px" align="center" bgcolor="#20b2aa" class='header'>
<tr>
<td style="color:#FFFFFF; border:none; font-weight:bold; font-size:20px;">GENERAL MEASUREMENT BOOK</td>
</tr>
</table><br/><br/><br/>-->
<form name="form" id="form" method="post">
			<?php
			eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

            ?>
            <?php echo $table2; ?>
<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid; ?>" id="txt_mbno_id" />
<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class='label'>
<?php echo $table1; ?>
<?php

eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
 if ($sqlquery == true) 
{
	while ($List = mysql_fetch_object($sqlquery)) 
	{
					$decimal = get_decimal_placed($List->subdivid,$sheetid);
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
		$currentline = $currentline+$wrap_cnt2	+1;
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
				<td width="35" align="right"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?></td>
				<td width="65" align="right"><?php if($List->measurement_l != 0) { echo number_format($List->measurement_l,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($List->measurement_b != 0) { echo number_format($List->measurement_b,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($List->measurement_d != 0) { echo number_format($List->measurement_d,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($List->measurement_contentarea != 0) { echo number_format($List->measurement_contentarea,$decimal,".",","); } ?></td>
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
		//$contentarea = round(($prev_contentarea + $List->measurement_contentarea),3);
		$contentarea = ($prev_contentarea + $List->measurement_contentarea);
		$prev_subdivid = $List->subdivid; $prev_subdivname = $List->subdiv_name; $prev_divid = $List->div_id; $prev_contentarea = $contentarea;
		$prev_date = $List->date; $prev_rowcount = $rowcount; $prevpage = $page; $prev_mbookno = $mbookno; $prev_struct_unit = $List->structdepth_unit;
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
						echo "<tr style='border:none'><td style='border:none' align='center' colspan='3'></td><td style='border:none' align='left' colspan='3'>Prepared By</td><td style='border:none' align='center' colspan='3'>Checked By</td></tr>";
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
			<td width="65" align="right"><?php echo number_format($summary1[$i+4],$summary1[$i+8],".",","); ?></td>
			<td width="32" align="center"><?php echo "&nbsp"; ?></td>
		</tr>
<?php	
			$summary_b .= $summary1[$i+7].",".$page.",".$summary1[$i].",";
			$contentarea = $contentarea + $summary1[$i+4];	$currentline++;
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
<input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($summary_b,","); ?>"  />
<div align="center" class="btn_outside_sect printbutton">
	<div class="btn_inside_sect"><input type="button" class="backbutton"  name="back" id="back" onclick="goBack();" value=" Back " /> </div>
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
</form>
<style>

</style>
    </body>
	 <script type="text/javascript">
   $(function(){ 
   var getstr = document.getElementById("txt_boxid_str").value;
   var splitval = getstr.split(","); //alert(splitval.length);
   var x=0;
   for(x=0;x<splitval.length;x+=3)
   {
   		document.getElementById("txt_page"+splitval[x]).value = "C/o to page "+splitval[x+1]+"/General MB No. "+"<?php echo $mbookno; ?>"; 
   }
   });
   </script>
</html>