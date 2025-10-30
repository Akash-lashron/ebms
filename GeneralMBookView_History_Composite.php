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
if(($_GET['sheetid'] != "") && ($_GET['rbn'] != ""))
{
	$sheetid = $_GET['sheetid'];
	$rbn = $_GET['rbn'];
}
$selectmbook_detail 	= 	"select DISTINCT fromdate, todate, abstmbookno FROM measurementbook WHERE sheetid = '$sheetid' AND flag = '1' AND rbn = '$rbn'";
$selectmbook_detail_sql = 	mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail 		= 	mysql_fetch_object($selectmbook_detail_sql);
	$fromdate 			= 	$Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno;
}
$selectmbookno_sql = "select mbno, startpage, endpage, mbookorder from mymbook WHERE mtype = 'G' AND sheetid = '$sheetid' AND staffid = '$staffid' AND rbn = '$rbn' AND genlevel = 'composite'";
$selectmbookno_query = mysql_query($selectmbookno_sql);
//echo $selectmbookno_sql;
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
//echo "grg".$mpage;
//$selectmbookno 			= 	"select mbname, old_id from oldmbook WHERE mbook_type = 'G' AND sheetid = '$sheetid'";
//$selectmbookno_sql 		= 	mysql_query($selectmbookno);
//if(mysql_num_rows($selectmbookno_sql)>0)
//{
//	$Listmbookno 		= 	mysql_fetch_object($selectmbookno_sql);
//	$mbookno 			= 	$Listmbookno->mbname; 	$oldmbookid 	= 	$Listmbookno->old_id;
//	
//	$mbookpage 			= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
//	$mbookpage_sql 		= 	mysql_query($mbookpage);
//	$mbookpageno 		= 	@mysql_result($mbookpage_sql,'mbpage')+1;
//	
//	$selectnewmbookno 	= 	"select DISTINCT mbno from mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1' AND mbno != '$mbookno'";
//	$selectnewmbookno_sql 	= 	mysql_query($selectnewmbookno);
//	$newmbookno 		= 	@mysql_result($selectnewmbookno_sql,'mbno');
//	
//	$newmbookpage 		= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
//	$newmbookpage_sql 	= 	mysql_query($newmbookpage);
//	$newmbookpageno 	= 	@mysql_result($newmbookpage_sql,'mbpage')+1;
//	
//$newmbookpageno 		= 	$objBind->DisplayPageDetails($newmbookno,$newmbookno,$sheetid,'cw',$rbn,$staffid);
//$newmbookpageno 		= 	$newmbookpageno +1;	
//}
//else
//{
//	$selectmbookno 		= 	"select DISTINCT mbno from mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1'";
//	$selectmbookno_sql 	= 	mysql_query($selectmbookno);
//	$mbookno 			= 	@mysql_result($selectmbookno_sql,'mbno');
//	
//	$mbookpage 			= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
//	//echo $mbookpage;
//	$mbookpage_sql 		= 	mysql_query($mbookpage);
//	$mbookpageno 		= 	@mysql_result($mbookpage_sql,'mbpage')+1;
//}
//$mbookpageno 			= 	$objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw',$rbn,$staffid);
//echo "Page = ".$mbookpageno;
//$mbookpageno 			= 	$mbookpageno+1;
/*echo "NEW MB ".$newmbookno."<br/>";
echo "NEW MB PAGE ".$newmbookpageno."<br/>";
echo "OLD MB ".$mbookno."<br/>";
echo "OLD MB PAGE ".$mbookpageno."<br/>";
exit;*/
//echo $mpage;



//$mpage = $mbookpageno;




//echo $mpage;
//echo $mpage;
//$sheetid=$_SESSION["sheet_id"]; 
//$fromdate = $_SESSION['fromdate'];
//$todate = $_SESSION['todate'];
//$mbookno = $_SESSION["mb_no"];    
//$mpage = $_SESSION["mb_page"];
//$mbno_id = $_SESSION["mbno_id"];
//$rbn = $_SESSION["rbn"];
//$abstmbookno = $_SESSION["abs_mbno"];
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
    //if($List->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$List->rbn + 1;}
	$runn_acc_bill_no = $rbn;
    //$_SESSION["currentrbn"]=$runn_acc_bill_no;
}

$length 	= 	strlen($work_name);
//echo $length."<br/>";
$start_line = 	ceil($length/87);
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
	$select_abs_page_query 	= 	"select abstmbookno, abstmbpage from measurementbook WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql 	= 	mysql_query($select_abs_page_query);
	$abstmbookno 			= 	@mysql_result($select_abs_page_sql,0,'abstmbookno');
	$abstractpage 			= 	@mysql_result($select_abs_page_sql,0,'abstmbpage');
	echo "C/o to Page ".$abstractpage." /Abstract MB No. ".$abstmbookno;
}
function stafflist($subdivid,$date,$sheetid)
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
	//echo $staff_design_sql."<br/";
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
	table{ border-collapse: collapse; }
	td { border: 1px solid #CACACA; }
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

</style>
<script type="text/javascript">
		window.history.forward();
		function noBack() 
		{ 
			window.history.forward(); 
		}
		function goBack()
		{
			url = "MBookGenerate_History_Composite.php";
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
			$title = '<table width="875" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="right" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
            echo $title;
            $table = "<table width='875' border='0'  cellpadding='3' cellspacing='3' align='center' bgcolor='#ffffff'>";
			$table = $table . "<tr>";;
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue'>Name of work:</td>";
            $table = $table . "<td width='' class='label' colspan='3'>" . $work_name . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue' valign='top'>Technical Sanction No.</td>";
            $table = $table . "<td class='label' colspan='3'> " . $tech_sanction . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue' valign='top'>Name of the contractor</td>";
            $table = $table . "<td class='label' colspan='3'>" . $name_contractor . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue' valign='top'>Agreement No.</td>";
            $table = $table . "<td class='label' colspan='3'>" . $agree_no . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue' valign='top'>Work Order No.</td>";
            $table = $table . "<td class='label' colspan='3'>" . $work_order_no . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue' valign='top'>Running Account bill No.</td>";
            $table = $table . "<td class='label' width = '150px'>" . $runn_acc_bill_no . "</td>";
			$table = $table . "<td class='labelbold labelheadblue' width='150px'>CC No.</td>";
			$table = $table . "<td class='label'>" . $ccno . "</td>";
            $table = $table . "</tr>";
            $table = $table . "</table>";
           
            //$table1 = $table1 . "<table width='875' border='0'  bgcolor='#ffffff' cellpadding='2' cellspacing='2' align='center'>";
            $table1 = $table1 . "<tr height='25' bgcolor='#E5E5E5' class='labelbold'>";
            $table1 = $table1 . "<td width='81' rowspan='2' class='labelcenter labelheadblue labelbold'>Date of Measurment</td>";
            $table1 = $table1 . "<td width='48' rowspan='2' class='labelcenter labelheadblue'>Item No.</td>";
            $table1 = $table1 . "<td width='390' rowspan='2' class='labelcenter labelheadblue'>Description of work</td>";
            $table1 = $table1 . "<td colspan='5' width='' class='labelcenter labelheadblue'>Measurements Upto Date</td>";
            $table1 = $table1 . "<td width='32' rowspan='2' class='labelcenter labelheadblue'>Remarks</td>";  //Remarks Field changed into Per.
            $table1 = $table1 . "</tr>";
            $table1 = $table1 . "<tr height='25' bgcolor='#E5E5E5' class='labelbold'>";
            $table1 = $table1 . "<td width='35' class='labelcenter labelheadblue'>No.</td>";
            $table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>L.</td>";
            $table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>B.</td>";
            $table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>D.</td>";
            $table1 = $table1 . "<td width='65' class='labelcenter labelheadblue'>Contents of Area</td>";
           
            $table1 = $table1 . "</tr>";
            //$table = $table . "</table>";
            ?>
            <?php echo $table; ?>
<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid; ?>" id="txt_mbno_id" />
<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">
<?php echo $table1; ?>
<?php
$currentline 		= 	$start_line + 8; 	$page 			= 	$mpage;		$pre_subdivid 		= 	""; 
$pre_staffid 		= 	""; 				$QtySum 		= 	0; 			$temp 				= 	0;
$OutPutStr			=	"";					$OutPutStr1 	=	"";			$OutPutStr2			=	"";

$mbook_compo_sql 	= 	"select * from mbookgenerate_staff where sheetid = '$sheetid' AND rbn = '$rbn' AND flag = 1 ORDER BY subdivid ASC, staffid ASC";
//echo $mbook_compo_sql;
$mbook_compo_query 	= 	mysql_query($mbook_compo_sql);
if($mbook_compo_query == true)
{
	while($CompoList = mysql_fetch_object($mbook_compo_query))
	{
		//$subdivname 	= 	getsubdivname($CompoList->subdivid);
		//$decimal		=	get_decimal_placed($CompoList->subdivid,$sheetid);
		$ItemData		=	getItemDetails($sheetid,$CompoList->subdivid);
		$ExplodeData	=	explode("##@**@##",$ItemData);
		$subdivname		=	$ExplodeData[0];
		$ItemUnit		=	$ExplodeData[2];
		$decimal		=	$ExplodeData[3];
		$fromdate 		=	$CompoList->fromdate;
		$todate 		=	$CompoList->todate;
		$createDate 	= 	new DateTime($todate);
		$todate 		= 	$createDate->format('Y-m-d');
		//echo $todate."<br/>";
		if(($pre_subdivid != "") && ($pre_staffid != ""))
		{
			if($pre_subdivid != $CompoList->subdivid)
			{
				$temp = 1;
			}
			/*if($pre_subdivid == $CompoList->subdivid)
			{
				if($pre_staffid != $CompoList->staffid)
				{
					$temp = 1;
				}
			}*/
			if($currentline>40)
			{
?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class="cobffont"><?php echo "C/o to page ".($page+1)." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
				<?php echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1); ?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class="cobffont"><?php echo "B/f from page ".$page." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
<?php	
				$currentline = $start_line + 8; $page++;
			}
			if($temp == 1)
			{
?>
				<tr height="" bgcolor="" class="labelbold">
					<td width="81" 	align="center"><?php //echo $Line; ?></td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">Total&nbsp;</td>
					<td width="230" align="right" colspan="4" class="cobffont"><?php echo getabstractpage($sheetid,$pre_subdivid); ?></td>
					<td width="65" 	align="right"><?php echo number_format($QtySum, $decimal, '.', ''); ?></td>
					<td width="32" 	align="left"><?php echo $pre_ItemUnit; ?></td>
				</tr>	
<?php
				$OutPutStr1 .=  $pre_divid."*".$pre_subdivid."*".$pre_fromdate."*".$pre_todate."*".$page."*".$mbookno."*".$QtySum."@";
				$QtySum = 0; $temp = 0; $currentline++;
			}
		}
		//echo $OutPutStr1."<br/>";
		if($currentline>40)
		{
?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class="cobffont"><?php echo "C/o to page ".($page+1)." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
				<?php echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1); ?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class="cobffont"><?php echo "B/f from page ".$page." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
<?php	
				$currentline = $start_line + 8; $page++;
		}
?>
				<tr height="" bgcolor="">
					<td width="81" 	align="center"><?php echo dt_display($todate); ?></td>
					<td width="48" 	align="center"><?php echo $subdivname; ?></td>
					<td width="390" align="center"><?php echo "B/f from page no ".$CompoList->mbpage." Mbook No.".$CompoList->mbno; ?></td>
					<td width="35" 	align="center">&nbsp;<?php //echo $currentline; ?></td>
					<td width="65" 	align="center">&nbsp;</td>
					<td width="65" 	align="center">&nbsp;</td>
					<td width="65" 	align="center">&nbsp;</td>
					<td width="65" 	align="right"><?php echo number_format($CompoList->mbtotal, $decimal, '.', ''); ?></td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>	
<?php
				$currentline++;
				$pre_divid 		= 	$CompoList->divid;
				$pre_subdivid 	= 	$CompoList->subdivid;
				$pre_staffid 	= 	$CompoList->staffid;
				$pre_mbpage 	= 	$CompoList->mbpage;
				$pre_mbno 		= 	$CompoList->mbno;
				$pre_mbtotal 	= 	$CompoList->mbtotal;
				$pre_fromdate 	= 	$CompoList->fromdate;
				$pre_todate 	= 	$CompoList->todate;
				$pre_ItemUnit	=	$ItemUnit;
				$QtySum			=	$QtySum + $CompoList->mbtotal;
				$pre_QtySum 	= 	$QtySum;
	}
				$OutPutStr2 	=  	$pre_divid."*".$pre_subdivid."*".$pre_fromdate."*".$pre_todate."*".$page."*".$mbookno."*".$QtySum;
				$OutPutStr		=	$OutPutStr1.$OutPutStr2;
?>
				<tr height="" bgcolor="" class="labelbold">
					<td width="81" 	align="center"><?php //echo $Line; ?></td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">Total&nbsp;</td>
					<td width="230" align="right" colspan="4" class="cobffont"><?php echo getabstractpage($sheetid,$pre_subdivid); ?></td>
					<td width="65" 	align="right"><?php echo number_format($QtySum, $decimal, '.', ''); ?></td>
					<td width="32" 	align="left"><?php echo $pre_ItemUnit; ?></td>
				</tr>
<?php
	$currentline++;
	$lineTemp = 35-$currentline;
?>
				<tr style="border-style:none">
					<td colspan="9" style="border-style:none" align="center">
					<?php 
						for($x2=$lineTemp; $x2<38; $x2++)
						{
							echo "<br/>";
						}
					?>
					<?php echo "Page ".$page; ?>
					</td>
				</tr>
<?php 

}
?>
</table>
<input type="hidden" name="hid_result" id="hid_result" value="<?php echo $OutPutStr; ?>" />

		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
			</div>
			<!--<div class="buttonsection">
			<input type="submit" name="submit" value=" Submit " id="submit"/>
			</div>-->
			<div class="buttonsection">
			<input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" />
			</div>
		</div>

 
<?php
/*$DeleteSql		=	"DELETE FROM mbookgenerate WHERE sheetid = '$sheetid' AND flag = 1";
$DeleteQuery	=	mysql_query($DeleteSql);
$ExplodeResult	=	explode("@",$OutPutStr);
for($x1=0; $x1<count($ExplodeResult); $x1++)
{
	$Res1		=	$ExplodeResult[$x1];
	$ExpRes1	=	explode("*",$Res1);
	$divid 		= 	$ExpRes1[0];
	$subdivid 	= 	$ExpRes1[1];
	$fromdate 	= 	$ExpRes1[2];
	$todate 	= 	$ExpRes1[3];
	$mbookpage 	= 	$ExpRes1[4];
	$mbookno 	= 	$ExpRes1[5];
	$ItemQty 	= 	$ExpRes1[6];
	$insertMbgenerate_sql 	= 	"insert into mbookgenerate (mbgeneratedate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, pay_percent, flag, rbn, active, userid) 
													values (NOW(), '$staffid', '$sheetid', '$divid', '$subdivid', '$fromdate', '$todate', '$mbookno', '$mbookpage', '$ItemQty', '0', '1', '$rbn', '1', '$userid')";
	$insertMbgenerate_query	=	mysql_query($insertMbgenerate_sql);
}*/
?>
</form>
</body>
</html>