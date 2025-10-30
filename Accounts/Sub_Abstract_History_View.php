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
	$row = $row.'<table width="875" border="0"  cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="right" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
	$row = $row.$table;
	$row = $row.'<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">';
	$row = $row.$table1;
	echo $row;
}
if($_GET['sheetid'] != "")
{
	$sheetid = $_GET['sheetid'];
	$rbn = $_GET['rbn'];
}
if($_POST["Back"] == " Back ")
{
     header('Location: MBookGenerate_History_Composite.php');
}
$selectmbook_detail 	= 	"select DISTINCT fromdate, todate, rbn, abstmbookno FROM measurementbook WHERE sheetid = '$sheetid' AND flag = '1' AND rbn = '$rbn'";
$selectmbook_detail_sql = 	mysqli_query($dbConn,$dbConn,$selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail 		= 	mysqli_fetch_object($selectmbook_detail_sql);
	$fromdate 			= 	$Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $rbn = $Listmbdetail->rbn; $abstmbookno = $Listmbdetail->abstmbookno;
}
$selectmbookno 			= 	"select mbname, old_id from oldmbook WHERE mbook_type = 'G' AND sheetid = '$sheetid'";
$selectmbookno_sql 		= 	mysqli_query($dbConn,$dbConn,$selectmbookno);
if(mysqli_num_rows($selectmbookno_sql)>0)
{
	$Listmbookno 		= 	mysqli_fetch_object($selectmbookno_sql);
	$mbookno 			= 	$Listmbookno->mbname; 	$oldmbookid 	= 	$Listmbookno->old_id;
	
	$mbookpage 			= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql 		= 	mysqli_query($dbConn,$dbConn,$mbookpage);
	$mbookpageno 		= 	@mysqli_result($mbookpage_sql,'mbpage')+1;
	
	$selectnewmbookno 	= 	"select DISTINCT mbno from mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1' AND mbno != '$mbookno' AND rbn = '$rbn'";
	$selectnewmbookno_sql 	= 	mysqli_query($dbConn,$dbConn,$selectnewmbookno);
	$newmbookno 		= 	@mysqli_result($selectnewmbookno_sql,'mbno');
	
	$newmbookpage 		= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
	$newmbookpage_sql 	= 	mysqli_query($dbConn,$dbConn,$newmbookpage);
	$newmbookpageno 	= 	@mysqli_result($newmbookpage_sql,'mbpage')+1;
	
$newmbookpageno 		= 	$objBind->DisplayPageDetails($newmbookno,$newmbookno,$sheetid,'cw',$rbn,$staffid);
$newmbookpageno 		= 	$newmbookpageno +1;	
}
else
{
	$selectmbookno 		= 	"select DISTINCT mbno from mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1' AND rbn = '$rbn'";
	$selectmbookno_sql 	= 	mysqli_query($dbConn,$dbConn,$selectmbookno);
	$mbookno 			= 	@mysqli_result($selectmbookno_sql,'mbno');
	
	$mbookpage 			= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	//echo $mbookpage;
	$mbookpage_sql 		= 	mysqli_query($dbConn,$dbConn,$mbookpage);
	$mbookpageno 		= 	@mysqli_result($mbookpage_sql,'mbpage')+1;
}
$mbookpageno 			= 	$objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw',$rbn,$staffid);
//echo "Page = ".$mbookpageno;
$mbookpageno 			= 	$mbookpageno+1;
/*echo "NEW MB ".$newmbookno."<br/>";
echo "NEW MB PAGE ".$newmbookpageno."<br/>";
echo "OLD MB ".$mbookno."<br/>";
echo "OLD MB PAGE ".$mbookpageno."<br/>";
exit;*/
$mpage = $mbookpageno;
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
$sqlquery 	= 	mysqli_query($dbConn,$dbConn,$query);
if ($sqlquery == true) 
{
    $List 				= 	mysqli_fetch_object($sqlquery);
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
   $sqlquerys = mysqli_query($dbConn,$dbConn,$querys);
}*/
function getabstractpage($sheetid,$subdivid)
{
	global $dbConn;
	$select_abs_page_query 	= 	"select abstmbookno, abstmbpage from measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql 	= 	mysqli_query($dbConn,$dbConn,$select_abs_page_query);
	$abstmbookno 			= 	@mysqli_result($select_abs_page_sql,0,'abstmbookno');
	$abstractpage 			= 	@mysqli_result($select_abs_page_sql,0,'abstmbpage');
	echo "C/o to Page ".$abstractpage." /Abstract MB No. ".$abstmbookno;
}
function stafflist($subdivid,$date,$sheetid)
{
	global $dbConn;
	$date = dt_format($date);
	$staff_design_sql = "select  DISTINCT staff.staffname, designation.designationname, mbookheader.date from staff 
	INNER JOIN designation ON (designation.designationid = staff.designationid) 
	INNER JOIN mbookheader ON (mbookheader.staffid = staff.staffid)
	WHERE staff.staffid = mbookheader.staffid AND staff.active = 1 AND designation.active = 1 AND mbookheader.date = '$date' AND mbookheader.sheetid = '$sheetid' AND mbookheader.subdivid = '$subdivid'";
	$staff_design_query = mysqli_query($dbConn,$dbConn,$staff_design_sql);
	while($staffList = mysqli_fetch_object($staff_design_query))
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
			eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

            ?>
            <?php echo $table2; ?>
<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid; ?>" id="txt_mbno_id" />
<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">

<?php echo $table1; ?>
<!--<tr height="" bgcolor="" class="label">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center"></td>
					<td width="230" align="left" colspan="" class="">Sub Abstract</td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>-->
<?php
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
if($mbook_compo_query == true)
{
	while($CompoList = mysqli_fetch_object($mbook_compo_query))
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
		$zone_id		=	$CompoList->zone_id;
		$createDate 	= 	new DateTime($todate);
				$description1 = getscheduledescription_new($CompoList->subdivid);
				$snotes = $description1;
				$degcelsius = "&#8451";
				$description = str_replace("DEGCEL","$degcelsius",$snotes);
		$zonename = getzonename($sheetid,$zone_id);
		if($zonename != ""){ $zonename = "( ".$zonename." )"; }
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
			if($currentline>35)
			{
?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class=""><?php echo "C/o to page ".($page+1)." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
				<?php echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1); ?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class=""><?php echo "B/f from page ".$page." /General MB No ".$mbookno; ?></td>
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
					<td width="230" align="right" nowrap="nowrap" colspan="4" class="cobffont"><?php echo getabstractpage($sheetid,$pre_subdivid); ?></td>
					<td width="65" 	align="right"><?php echo number_format($QtySum, $decimal, '.', ''); ?></td>
					<td width="32" 	align="left"><?php echo $pre_ItemUnit; ?></td>
				</tr>	
<?php
				$OutPutStr1 .=  $pre_divid."*".$pre_subdivid."*".$pre_fromdate."*".$pre_todate."*".$page."*".$mbookno."*".$QtySum."@";
				$QtySum = 0; $temp = 0; $currentline++;
			}
		}
		if($currentline>35)
		{
?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class=""><?php echo "C/o to page ".($page+1)." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
				<?php echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1); ?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class=""><?php echo "B/f from page ".$page." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
<?php	
				$currentline = $start_line + 8; $page++;
		}
		if($pre_subdivid != $CompoList->subdivid)
		{
			$WrapReturn1 = getWordWrapCount($description,80);
			$description = $WrapReturn1[0];
			$wrap_cnt1 = $WrapReturn1[1];
			$currentline = $currentline + $wrap_cnt1;
			?>
				<tr height="" bgcolor="" class="">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center"><?php echo $subdivname; ?></td>
					<td width="230" align="left" colspan="5" class=""><?php echo $description; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
			<?php
		}
		/*if($CompoList->mbno == 1000)
		{
			$zone = "(Zone-II)";
		}
		if($CompoList->mbno == 1001)
		{
			$zone = "(SWB)";
		}
		if($CompoList->mbno == 1003)
		{
			$zone = "(Zone-V)";
		}
		if($CompoList->mbno == 1004)
		{
			$zone = "(Zone-III)";
		}
		if($CompoList->mbno == 1021)
		{
			$zone = "(Zone-II)";
		}
		if($CompoList->mbno == 1022)
		{
			$zone = "(SWB)";
		}*/
?>
			
				<tr height="" bgcolor="">
					<td width="81" 	align="center"><?php //echo dt_display($todate); ?></td>
					<td width="48" 	align="center"><?php //echo $subdivname; ?></td>
					<td width="390" align="center"><?php echo "B/f ".$zonename." from page no ".$CompoList->mbpage." Mbook No.".$CompoList->mbno; ?></td>
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
						for($x2=4; $x2<12; $x2++)
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
$DeleteQuery	=	mysqli_query($dbConn,$dbConn,$dbConn,$DeleteSql);
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
	$insertMbgenerate_query	=	mysqli_query($dbConn,$dbConn,$insertMbgenerate_sql);
}*/
?>
</form>
</body>
</html>