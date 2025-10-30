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
function check_line($title,$table,$page,$mbookno,$newmbookno,$table1)
{
	if($page == 100) { $mbookno = $newmbookno; }
	$row = '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">&nbsp;<br/>Page '.$page.'&nbsp;&nbsp</td></tr>';
	$row = $row."</table>";
	$row = $row."<p  style='page-break-after:always;'></p>";
	$row = $row.'<table width="875" border="0"  cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="right" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
	$row = $row.$table;
	$row = $row.'<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">';
	$row = $row.$table1;
	echo $row;
}
if($_GET['workno'] != "")
{
	$sheetid = $_GET['workno'];
}
if($_POST["Back"] == " Back ")
{
     header('Location: MeasurementBookPrint_composite.php');
}
$selectmbook_detail 	= 	"select DISTINCT fromdate, todate, rbn, abstmbookno FROM mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1'";
$selectmbook_detail_sql = 	mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail 		= 	mysql_fetch_object($selectmbook_detail_sql);
	$fromdate 			= 	$Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $rbn = $Listmbdetail->rbn; $abstmbookno = $Listmbdetail->abstmbookno;
}
$selectmbookno 			= 	"select mbname, old_id from oldmbook WHERE mbook_type = 'G' AND sheetid = '$sheetid'";
$selectmbookno_sql 		= 	mysql_query($selectmbookno);
if(mysql_num_rows($selectmbookno_sql)>0)
{
	$Listmbookno 		= 	mysql_fetch_object($selectmbookno_sql);
	$mbookno 			= 	$Listmbookno->mbname; 	$oldmbookid 	= 	$Listmbookno->old_id;
	
	$mbookpage 			= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql 		= 	mysql_query($mbookpage);
	$mbookpageno 		= 	@mysql_result($mbookpage_sql,'mbpage')+1;
	
	$selectnewmbookno 	= 	"select DISTINCT mbno from mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1' AND mbno != '$mbookno'";
	$selectnewmbookno_sql 	= 	mysql_query($selectnewmbookno);
	$newmbookno 		= 	@mysql_result($selectnewmbookno_sql,'mbno');
	
	$newmbookpage 		= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
	$newmbookpage_sql 	= 	mysql_query($newmbookpage);
	$newmbookpageno 	= 	@mysql_result($newmbookpage_sql,'mbpage')+1;
	
$newmbookpageno 		= 	$objBind->DisplayPageDetails($newmbookno,$newmbookno,$sheetid,'cw',$rbn,$staffid);
$newmbookpageno 		= 	$newmbookpageno +1;	
}
else
{
	$selectmbookno 		= 	"select DISTINCT mbno from mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1'";
	$selectmbookno_sql 	= 	mysql_query($selectmbookno);
	$mbookno 			= 	@mysql_result($selectmbookno_sql,'mbno');
	
	$mbookpage 			= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql 		= 	mysql_query($mbookpage);
	$mbookpageno 		= 	@mysql_result($mbookpage_sql,'mbpage')+1;
}
$mbookpageno 			= 	$objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw',$rbn,$staffid);
$mbookpageno 			= 	$mbookpageno+1;
$mpage = $mbookpageno;
$query 		= 	"SELECT sheet_id, sheet_name, work_order_no, work_name, tech_sanction, name_contractor, computer_code_no, agree_no, rbn FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery 	= 	mysql_query($query);
if ($sqlquery == true) 
{
    $List 				= 	mysql_fetch_object($sqlquery);
    $work_name 			= 	$List->work_name;    
	$tech_sanction 		= 	$List->tech_sanction;
    $name_contractor 	= 	$List->name_contractor;    
	$agree_no 			= 	$List->agree_no; 
	$work_order_no 		= 	$List->work_order_no; 
	$ccno 				= 	$List->computer_code_no;
	$runn_acc_bill_no = $rbn;
}

$length 	= 	strlen($work_name);
$start_line = 	ceil($length/87);
function getabstractpage($sheetid,$subdivid)
{
	$select_abs_page_query 	= 	"select abstmbookno, abstmbpage from measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql 	= 	mysql_query($select_abs_page_query);
	$abstmbookno 			= 	@mysql_result($select_abs_page_sql,0,'abstmbookno');
	$abstractpage 			= 	@mysql_result($select_abs_page_sql,0,'abstmbpage');
	//echo "C/o to Page ".$abstractpage." /Abstract MB No. ".$abstmbookno;
	return $abstractpage."/".$abstmbookno;
}
/*function stafflist($subdivid,$date,$sheetid)
{
	$date = dt_format($date);
	$staff_design_sql = "select  DISTINCT staff.staffname, designation.designationname, mbookheader.date from staff 
	INNER JOIN designation ON (designation.designationid = staff.designationid) 
	INNER JOIN mbookheader ON (mbookheader.staffid = staff.staffid)
	WHERE staff.staffid = mbookheader.staffid AND staff.active = 1 AND designation.active = 1 AND mbookheader.date = '$date' AND mbookheader.sheetid = '$sheetid' AND mbookheader.subdivid = '$subdivid'";
	$staff_design_query = mysql_query($staff_design_sql);
	while($staffList = mysql_fetch_object($staff_design_query))
	{
		$staffname 		= 	$staffList->staffname;
		$designation 	= 	$staffList->designationname;
		$result 	   .= 	$staffname."*".$designation."*";
	}
	return rtrim($result,"*");
}
*/
$ZoneArr = array();
$ZnameArr = array();
$ItemIdArr = array();
$ItemNoArr = array();
$select_zone_query 	= 	"select zone_id, zone_name from zone where sheetid = '$sheetid' ORDER BY zone_name ASC";
$select_zone_sql 	= 	mysql_query($select_zone_query);
if($select_zone_sql == true)
{
	if(mysql_num_rows($select_zone_sql)>0)
	{
		while($ZoneList = mysql_fetch_object($select_zone_sql))
		{
			$zone = $ZoneList->zone_id;
			$zname = $ZoneList->zone_name;
			array_push($ZoneArr,$zone);
			array_push($ZnameArr,$zname);
		}
	}
}
$zone_count = count($ZoneArr);
if($zone_count>0)
{
	$col_span_count_1 = $zone_count+1;
	$col_span_count_2 = $zone_count;
}
/*$select_item_query	= "select DISTINCT mbookgenerate_staff.subdivid, subdivision.subdiv_name FROM mbookgenerate_staff 
							INNER JOIN subdivision ON (subdivision.subdiv_id = mbookgenerate_staff.subdivid)
							WHERE 
							mbookgenerate_staff.sheetid = '$sheetid' AND mbookgenerate_staff.flag = '1' AND 
							mbookgenerate_staff.fromdate = '$fromdate' AND mbookgenerate_staff.rbn = '$rbn' AND 
							mbookgenerate_staff.todate = '$todate' AND subdivision.sheet_id = '$sheetid'";*/
$select_item_query	= "select DISTINCT mbookgenerate_staff.subdivid, subdivision.subdiv_name FROM mbookgenerate_staff 
							INNER JOIN subdivision ON (subdivision.subdiv_id = mbookgenerate_staff.subdivid)
							WHERE 
							mbookgenerate_staff.sheetid = '$sheetid' AND 
							mbookgenerate_staff.fromdate = '$fromdate' AND mbookgenerate_staff.rbn = '$rbn' AND 
							mbookgenerate_staff.todate = '$todate' AND subdivision.sheet_id = '$sheetid' ORDER BY mbookgenerate_staff.subdivid ASC";
$select_item_sql = mysql_query($select_item_query);
if($select_item_sql == true)
{
	if(mysql_num_rows($select_item_sql)>0)
	{
		while($ItemList = mysql_fetch_object($select_item_sql))
		{
			$itemid = $ItemList->subdivid;
			$itemname = $ItemList->subdiv_name;
			array_push($ItemIdArr,$itemid);
			array_push($ItemNoArr,$itemname);
		}
	}
}
$item_count = count($ItemIdArr);
//echo $selectmbook_detail;
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
<style type="text/css" media="print,screen" >
	table{ border-collapse: collapse; }
	td { border: 1px solid #A0A0A0; height:20px; }
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
.cobffont
{
	font-size:11px;
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
			url = "MeasurementBookPrint_composite_column.php";
			window.location.replace(url);
		}
		
</script>
<body bgcolor="" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<form name="form" id="form" method="post">
			<?php
			//$title = '<table width="1075" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			//<tr style="border:none;"><td align="right" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			//</table>';
			$title = '<table width="" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="right" style="border:none;">&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
            echo $title;
            $table2 = "<table width='1264' border='0'  cellpadding='3' cellspacing='3' align='center' bgcolor='#ffffff'>";
			$table2 = $table2 . "<tr>";;
            $table2 = $table2 . "<td width='200' nowrap='nowrap' class='label'>Name of work:</td>";
            $table2 = $table2 . "<td width='' class='label' colspan='3'>" . $work_name . "</td>";
            $table2 = $table2 . "</tr>";
            $table2 = $table2 . "<tr>";
            $table2 = $table2 . "<td width='200' nowrap='nowrap' class='label' valign='top'>Technical Sanction No.</td>";
            $table2 = $table2 . "<td class='label' colspan='3'> " . $tech_sanction . "</td>";
            $table2 = $table2 . "</tr>";
            $table2 = $table2 . "<tr>";
            $table2 = $table2 . "<td width='200' nowrap='nowrap' class='label' valign='top'>Name of the contractor</td>";
            $table2 = $table2 . "<td class='label' colspan='3'>" . $name_contractor . "</td>";
            $table2 = $table2 . "</tr>";
            $table2 = $table2 . "<tr>";
            $table2 = $table2 . "<td width='200' nowrap='nowrap' class='label' valign='top'>Agreement No.</td>";
            $table2 = $table2 . "<td class='label' colspan='3'>" . $agree_no . "</td>";
            $table2 = $table2 . "</tr>";
            $table2 = $table2 . "<tr>";
            $table2 = $table2 . "<td width='200' nowrap='nowrap' class='label' valign='top'>Work Order No.</td>";
            $table2 = $table2 . "<td class='label' colspan='3'>" . $work_order_no . "</td>";
            $table2 = $table2 . "</tr>";
            $table2 = $table2 . "<tr>";
            $table2 = $table2 . "<td width='200' nowrap='nowrap' class='label' valign='top'>Running Account bill No.</td>";
            $table2 = $table2 . "<td class='label' width = '150px'>" . $runn_acc_bill_no . " ( Sub Abstract )</td>";
			$table2 = $table2 . "<td class='label' align='right' width='150px'>CC No.</td>";
			$table2 = $table2 . "<td class='label'>" . $ccno . "</td>";
            $table2 = $table2 . "</tr>";
            $table2 = $table2 . "</table>";
			
			$table = "<table width='1264' border='0'  cellpadding='3' cellspacing='3' align='center' bgcolor='#ffffff'>";
			$table = $table . "<tr>";;
            $table = $table . "<td width='200' nowrap='nowrap' class='label'>Name of work:</td>";
            $table = $table . "<td width='' class='label' colspan='3'>" . $work_name . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='label' valign='top'>Name of the contractor</td>";
            $table = $table . "<td class='label' colspan='3'>" . $name_contractor . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='label' valign='top'>Agreement No.</td>";
            $table = $table . "<td class='label' colspan='3'>" . $agree_no . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='label' valign='top'>Running Account bill No.</td>";
            $table = $table . "<td class='label' width = '150px'>" . $runn_acc_bill_no . " ( Sub Abstract )</td>";
			$table = $table . "<td class='label' align='right' width='150px'>CC No.</td>";
			$table = $table . "<td class='label'>" . $ccno . "</td>";
            $table = $table . "</tr>";
            $table = $table . "</table>";
           
            $table1 = $table1 . "<tr height='25' bgcolor='#E5E5E5' class=''>";
            $table1 = $table1 . "<td width='81' rowspan='2' class='labelcenter labelheadblue'>Date of <br/>Measur<br/>ment</td>";
            $table1 = $table1 . "<td width='48' rowspan='2' class='labelcenter labelheadblue'>Item No.</td>";
            $table1 = $table1 . "<td width='390' rowspan='2' class='labelcenter labelheadblue'>Description of work</td>";
            $table1 = $table1 . "<td colspan='".$col_span_count_1."' width='' class='labelcenter labelheadblue'>Measurements Upto Date</td>";
			$table1 = $table1 . "<td width='32' rowspan='2' class='labelcenter labelheadblue'>Per</td>";
            //$table1 = $table1 . "<td width='32' rowspan='2' class='labelcenter labelheadblue'>C/O to P/MB</td>";  //Remarks Field changed into Per.
            $table1 = $table1 . "</tr>";
            $table1 = $table1 . "<tr height='25' bgcolor='#E5E5E5' class=''>";
			for($zc1=0; $zc1<$zone_count; $zc1++)
			{
				$zc2=0;
				$table1 = $table1 . "<td width='35' class='labelcenter labelheadblue'>".$ZnameArr[$zc1]."</td>";
				//$table1 = $table1 . "<td width='35' class='labelcenter labelheadblue'>P/MB</td>";
			}
            //$table1 = $table1 . "<td width='35' class='labelcenter labelheadblue'>SWB</td>";
            //$table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>P/MB</td>";
            //$table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>Zone-II</td>";
            //$table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>P/MB</td>";
            //$table1 = $table1 . "<td width='35' class='labelcenter labelheadblue'>Zone-III</td>";
            //$table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>P/MB</td>";
            //$table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>Zone-V</td>";
            //$table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>P/MB</td>";
            $table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>Total Qty</td>";
           
            $table1 = $table1 . "</tr>";
            ?>
            <?php echo $table2; ?>
<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid; ?>" id="txt_mbno_id" />
<table width="1264" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">
<?php echo $table1; ?>
<?php
	for($zc3=0; $zc3<$item_count; $zc3++)
	{
		$subdivid = $ItemIdArr[$zc3];
		$subdivname = $ItemNoArr[$zc3];
		$select_desc_query = "select description, shortnotes, per from schdule where sheet_id = '$sheetid' and subdiv_id = '$subdivid'";
		$select_desc_sql = mysql_query($select_desc_query);
		if($select_desc_sql == true)
		{
			if(mysql_num_rows($select_desc_sql)>0)
			{
				$DescList = mysql_fetch_object($select_desc_sql);
				$item_description = $DescList->description;
				$item_shortnotes = $DescList->shortnotes;
				$item_unit = $DescList->per;
				if($item_shortnotes != "")
				{
					$item_description = $item_shortnotes;
				}
			}
		}
		/*echo "<tr>";
		echo "<td>&nbsp;</td>";
		echo "<td>".$subdivname."</td>";
		echo "<td>".$item_description."</td>";
		for($zc5=0; $zc5<$col_span_count_1-1; $zc5++)
		{
			echo "<td style=''>&nbsp;</td>";
		}
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		//echo "<td>&nbsp;</td>";
		echo "</tr>";*/
		echo "<tr>";
		echo "<td>&nbsp;</td>";
		echo "<td>".$subdivname."</td>";
		echo "<td>".$item_description."</td>";
		$total_item_qty = 0;
		$QtyArr = array();
		$MBookCurrArr = array();
		$MBPageCurrArr = array();
		$select_item_detail_query = "select * from mbookgenerate_staff where sheetid = '$sheetid' and subdivid = '$subdivid' and fromdate = '$fromdate' and todate = '$todate' and rbn = '$rbn'";
		$select_item_detail_sql = mysql_query($select_item_detail_query);
		if($select_item_detail_sql == true)
		{
			if(mysql_num_rows($select_item_detail_sql)>0)
			{
				while($ItemData = mysql_fetch_object($select_item_detail_sql))
				{
					$zone_id = $ItemData->zone_id;
					$item_qty = $ItemData->mbtotal;
					$mbno = $ItemData->mbno;
					$mbpage = $ItemData->mbpage;
					$total_item_qty = $total_item_qty+$item_qty;
					$QtyArr[$zone_id] = $item_qty;
					$MBookCurrArr[$zone_id] = $mbno;
					$MBPageCurrArr[$zone_id] = $mbpage;
				}
				for($zc4=0; $zc4<$zone_count; $zc4++)
				{
					$zone_curr = $ZoneArr[$zc4];
					echo "<td align='right'>".$QtyArr[$zone_curr]."</td>";
					if($QtyArr[$zone_curr] != "")
					{
						$mb_page_format = $MBPageCurrArr[$zone_curr]."/".$MBookCurrArr[$zone_curr];
					}
					else
					{
						$mb_page_format = "";
					}
					//echo "<td>".$mb_page_format."</td>";
				}
			}
			
		}
		$schduledetails = getschduledetails($sheetid,$subdivid);
		$rateandremarks = explode('*',$schduledetails);
		echo "<td align='right'><b>".$total_item_qty."</b></td>";
		echo "<td>".$rateandremarks[1]."</td>";
		//echo "<td>".getabstractpage($sheetid,$subdivid)."</td>";
		echo "</tr>";
	}
?>
</table>
<input type="hidden" name="hid_result" id="hid_result" value="<?php echo $OutPutStr; ?>" />
		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
			</div>
			<div class="buttonsection">
			<input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" />
			</div>
		</div>
</form>
</body>
</html>