<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
checkUser();
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMbIncr = 0; $UsedMBArr = array();
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
function check_line($title,$table,$page,$mbookno,$newmbookno,$table1,$gen_version)
{
	if($page == 100) { $mbookno = $newmbookno; }
	$row = '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">&nbsp;<br/>Page '.$page.'&nbsp;&nbsp</td></tr>';
	$row = $row."</table>";
	$row = $row."<p  style='page-break-after:always;'></p>";
	$row = $row.'<table width="875" border="0"  cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="center" style="border:none;">General M.Book No. '.$mbookno.' (Print version : '.$gen_version.')&nbsp;&nbsp;<br/>&nbsp;</td></tr>
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
$selectmbook_detail 	= 	"select DISTINCT fromdate, todate, rbn, abstmbookno, is_finalbill FROM mbookgenerate WHERE sheetid = '$sheetid'";// AND flag = '1'";
$selectmbook_detail_sql = 	mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail 		= 	mysql_fetch_object($selectmbook_detail_sql);
	$fromdate 			= 	$Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $rbn = $Listmbdetail->rbn; $abstmbookno = $Listmbdetail->abstmbookno;
	$is_finalbill 		= 	$Listmbdetail->is_finalbill;
}
//echo $selectmbook_detail;exit;
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
	//echo $mbookpage;
	$mbookpage_sql 		= 	mysql_query($mbookpage);
	$mbookpageno 		= 	@mysql_result($mbookpage_sql,'mbpage')+1;
}
$mbookpageno 			= 	$objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw',$rbn,$staffid);
//echo "Page = ".$mbookpageno;


$mbookpageNew 			= 	"select startpage, gen_version from mymbook WHERE sheetid = '$sheetid' AND rbn = '$rbn' AND mbno = '$mbookno' and genlevel = 'composite' and mtype = 'G'";
$mbookpageNew_sql 		= 	mysql_query($mbookpageNew);
$mbookpageno 			= 	@mysql_result($mbookpageNew_sql,'mbpage');//+1;
$gen_version 			= 	@mysql_result($mbookpageNew_sql,'gen_version');//+1;
$mbookpageno 			= 	$mbookpageno;//+1;
//$mbookpageno 			= 	$mbookpageno+1;

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
//echo $runn_acc_bill_no;exit;
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
	$select_abs_page_query 	= 	"select abstmbookno, abstmbpage from measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
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
$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array(); $NextMBFlag = 1;
$SelectMBookQuery = "select * from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and mtype = 'G' and genlevel = 'composite' order by mbookorder asc";
$SelectMBookSql = mysql_query($SelectMBookQuery);
if($SelectMBookSql == true){
	if(mysql_num_rows($SelectMBookSql)>0){
		while($MBList = mysql_fetch_object($SelectMBookSql)){
			if($MBList->mbookorder == 1){ 
				$mbookno = $MBList->mbno; //echo "1 = ".$abstmbno."<br/>";
				$mpage = $MBList->startpage;
			}else{
				$SelectMB 		= $MBList->mbno; 
				$SelectMBPage 	= $MBList->startpage;
				if($SelectMBPage != ''){
					array_push($NextMBList,$SelectMB); //echo $SelectMBPage."SS<br/>";
					array_push($NextMBPageList,$SelectMBPage);
				}
			}
		}
	}
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
	table{ 
		border-collapse: collapse; 
	}
	td{ 
		border: 1px solid #A0A0A0; 
		padding-top:5px;
		padding-bottom:5px;
	}
	@media screen 
	{
        div.divFooter{
            display: none;
        }
    }
    @media print 
	{
        div.divFooter{
            position: fixed;
            bottom: 0;
        } 
		.header{
			display: none !important;
		}
		.printbutton{
			display: none !important;
		}
	}
	.ui-dialog > .ui-widget-header {background: #20b2aa; font-size:12px;}
	.breakAfter{
		page-break-before: always;
	}
	.labelcontent{
		font-family:Microsoft New Tai Lue;
		font-size:12pt;
		color:#000000;
	}
	.ui-dialog-titlebar-close{
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
	.submit_btn:hover{
		 color:#000000;
		 -moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
		 -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
		  box-shadow:0px 1px 4px rgba(0,0,0,5);
		  padding: 0.3em 1em;
	}
	.cancel_btn:hover{
		color:#000000;
		-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
		-webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
		 box-shadow:0px 1px 4px rgba(0,0,0,5);
		 padding: 0.3em 1em;
	}
	.cobffont{
		font-size:11px;
	}
	.label, .labelcenter, .labelheadblue{
		font-size:13px;
	}
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack(){ 
		window.history.forward(); 
	}
	function goBack(){
		url = "MeasurementBookPrint_composite.php";
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
					<td width="230" align="right" colspan="4" class="">
					<?php //echo "C/o to page ".($page+1)." /General MB No ".$mbookno; ?>
					C/o to page <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/General MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/General MB No.<?php echo $mbookno; } ?>
					</td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
				<?php echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1,$gen_version); ?>
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
				/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
				if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
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
					<td width="230" align="right" colspan="4" class="">
					<?php //echo "C/o to page ".($page+1)." /General MB No ".$mbookno; ?>
					C/o to page <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/General MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/General MB No.<?php echo $mbookno; } ?>
					</td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
				<?php echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1,$gen_version); ?>
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
				/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
				if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
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
							//echo "<br/>";
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