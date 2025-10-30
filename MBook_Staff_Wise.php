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

/*if($_SESSION['NotGenerate'] > 0){
	$_SESSION['NotGenerate'] = $_SESSION['NotGenerate'] - 1;
}*/
if(in_array($_SESSION["zone_id"],$_SESSION['GenTotalGenZoneArr'])){
	/// Already Exist So no need to push
}else{
	array_push($_SESSION['GenTotalGenZoneArr'],$_SESSION["zone_id"]);
}
$NotGenerate = count($_SESSION['GenTotalMeasZoneArr']) - count($_SESSION['GenTotalGenZoneArr']); 


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
$UsedMBArr[$mbookno][0] = $mpage;
//echo $zone_clause."<br/>";
$oldmbookno 	=	$mbookno;
$oldmbookpage 	=	$mpage;
$query 			= "SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
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
	$adoc 				= 	$List->act_doc;
	// $sdoc			= 	$List->date_of_completion;
	if($List->work_orders_ext != '0000-00-00' && $List->work_orders_ext != 0000-00-00 && $List->work_orders_ext != '' && $List->work_orders_ext != NULL){
		$sdoc = $List->work_orders_ext;
	}else{
		$sdoc = $List->date_of_completion;
	}
    //if($List->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$List->rbn + 1;}
	$runn_acc_bill_no 	= 	$rbn;
    $_SESSION["currentrbn"]=$runn_acc_bill_no;
}

$length = strlen($work_name);
//echo $length."<br/>";
$start_line = ceil($length/87);
// echo $fromdate." - ".$todate." - ".$zone_clause." - ".$zone_id;exit;
$mbookgeneratedelsql = "DELETE FROM mbookgenerate_staff WHERE flag =1 AND sheetid = '$sheetid' AND staffid = '$staffid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
//	echo $length;exit;
$result = dbQuery($mbookgeneratedelsql);
if($_GET['varid'] == 1)
{
	$deletequery	=	mysql_query("DELETE FROM oldmbook WHERE sheetid = '$sheetid' AND mbook_type = 'G' AND staffid = '$staffid' AND zone_id = '$zone_id'");
	$deletequery_1	=	mysql_query("DELETE FROM mbookgenerate WHERE sheetid = '$sheetid'");
}
function mbookgenerateinsert_staff($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$mpage,$contentarea,$abstmbookno,$rbn,$userid,$zone_id)
{ 
   $querys="INSERT INTO mbookgenerate_staff set sheetid='$sheetid',zone_id='$zone_id',divid='$prev_divid',subdivid='$prev_subdivid',
       fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=1,rbn='$rbn', abstmbookno = '$abstmbookno',
            mbgeneratedate=NOW(), staffid='$staffid', mbpage='$mpage', mbtotal='$contentarea', active=1, userid='$userid', is_finalbill = '".$_SESSION["final_bill"]."'";
 //echo $querys."<br>";
   $sqlquerys = mysql_query($querys);
}
if($_GET['newmbook'] != "")
{
$newmbookno = $_GET['newmbook'];
$newmbookpage_query = "select mbpage, allotmentid from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
$newmbookpage_sql = mysql_query($newmbookpage_query);
$newmbookpage = @mysql_result($newmbookpage_sql,0,'mbpage')+1;

}
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array();
if($_POST["modal_btn_next_mb"] == " NEXT "){
	$NextMBFlag = 1;
	$TotalNoList 	= $_POST['txt_no']; //echo $_POST['txt_next_mb2']."<br/>";//print_r($TotalNoList);exit;
	//rsort($TotalNoList);
	foreach($TotalNoList as $NoKey => $NoValue){ 
		//$UsedMBArr[$MBStartVal][0] = $NextMBPageList[$MBStartKey];
		$SelectMB 		= $_POST['txt_next_mb'.$NoValue]; 
		$SelectMBPage 	= $_POST['txt_next_mbpage'.$NoValue];
		if($SelectMBPage != ''){
			array_push($NextMBList,$SelectMB); //print_r($NextMBList);//echo $SelectMBPage."SS<br/>";
			array_push($NextMBPageList,$SelectMBPage);
			$UsedMBArr[$SelectMB][0] = $SelectMBPage;
		}
		
	}
}
	//print_r($NextMBList);
	//exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>General M.Book</title>
        <link rel="stylesheet" href="script/font.css" />
</head>
		<!--<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
		<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
		<link rel="stylesheet" href="css/button_style.css"></link>
	 	<link rel="stylesheet" href="js/jquery-ui.css">
	  	<script src="js/jquery-1.10.2.js"></script>
	  	<script src="js/jquery-ui.js"></script>
	  	<link rel="stylesheet" href="/resources/demos/style.css">-->
		<!--<link rel="stylesheet" href="css/button_style.css"></link>-->
	  	<script src="js/jquery-1.10.2.js"></script>
		<link rel="stylesheet" href="css/chosen.min.css">
   	 	<script src="js/chosen.jquery.min.js"></script>
		<link href="bootstrap-dialog/css/bootstrap-min.css" rel="stylesheet" type="text/css" />
		<script src="bootstrap-dialog/js/bootstrap.min.js"></script> <!---IMP-->
		<link href="bootstrap-dialog/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
		<script src="bootstrap-dialog/js/bootstrap-dialog.min.js"></script>
	<script>
	function goBack(){
	   	url = "Generate_Staff_Wise.php";
		window.location.replace(url);
	}
  	/*$(function() {
   		$(".dialogwindow").dialog({ autoOpen: false,
			minHeight: 200,
			maxHeight:200,
			minWidth: 300,
			maxWidth: 300,
			modal: true,
		});
        $(".dialogwindow").dialog("open");
        $( ".dialogwindow" ).dialog( "option", "draggable", false );
		
		$('#btn_cancel').click(function(){
			$(".dialogwindow").dialog("close");
			window.location.href="Generate_Staff_Wise.php";
		});
        $('#btn').click(function(){
			var x = $('#newmbooklist option:selected').val();
			if(x == ""){
				var a="* Please select Next Mbook number";
				$('#error_msg').text(a);
				event.preventDefault();
				event.returnValue = false;
			}else{
				$(".dialogwindow").dialog("close");       
				var newmbookvalue = $("#newmbooklist option:selected").text(); //alert(newmbookvalue);
				var oldmbookdetails = document.form.txt_mbno_id.value;
				$.post("GetOldMbookNo.php", {oldmbook: oldmbookdetails}, function (data) {
					window.location.href="MBook_Staff_Wise.php?newmbook="+newmbookvalue;
					return false; // avoid to execute the actual submit of the form.
				});
			 }
		});
		$.fn.validatenewmbook = function(event){ 
			if($('#newmbooklist option:selected').val()==""){ 
				var a="Please select Next Mbook number";
				$('#error_msg').text(a);
				event.preventDefault();
				event.returnValue = false;
			}else{
				var a="";
				$('#error_msg').text(a);
			}
		}
		$("#newmbooklist").change(function(event){
			$(this).validatenewmbook(event);
		});
  });*/
  </script>
<style type="text/css" media="print,screen" >
	table{ 
		border-collapse: collapse; 
	}
	td { 
		border: 1px solid #CACACA;
		padding-top:5px;
		padding-bottom:5px; 
	}
	.breakAfter {
		page-break-before: always;
	}
	.labelcontent{
		font-family:Microsoft New Tai Lue;
		font-size:12pt;
		color:#000000;
	}
	.labelheadblue{
		color:#0000CD;
		font-weight:bold;
		font-size:12px;
	}
	.labelcontentblue{
		color:#0000CD;
		font-weight:bold;
		/*font-size:12pt;	*/
	}
	.label{
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
	/*.submit_btn:hover {
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
  }*/
  	.textboxcobf{
		width:398px; 
		border:none; 
		text-align:right;
		font-weight:bold;
		color:#0000CD;
  	}
 	.title{
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

//eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
                //echo $query;exit;
				
$prev_subdivid = ""; $prev_contentarea = 0; $currentline = $start_line + 10; $line = $currentline; $prev_date = "";$page = $mpage; $txtboxid = 1;
$query = "SELECT DATE_FORMAT( mbookheader.date , '%d/%m/%Y' ) AS date ,  mbookdetail.subdivid , mbookheader.subdiv_name,   
mbookdetail.descwork, mbookdetail.measurement_no , mbookdetail.measurement_l , mbookdetail.measurement_b,  mbookdetail.structdepth_unit, 
mbookdetail.measurement_d , mbookdetail.measurement_contentarea , mbookdetail.remarks, schdule.measure_type, schdule.shortnotes, schdule.description, mbookheader.sheetid    
FROM schdule
INNER JOIN mbookheader ON (mbookheader.subdivid = schdule.subdiv_id)
INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
WHERE  mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND schdule.measure_type != 's' AND mbookdetail.mbdetail_flag != 'd' AND mbookheader.sheetid = '$sheetid' AND mbookheader.staffid = '$staffid' ".$zone_clause." ORDER BY mbookheader.date, mbookdetail.subdivid, mbookheader.mbheaderid, mbookdetail.mbdetail_id ASC" ;				
$sqlquery = mysql_query($query);
if ($sqlquery == true) 
{
	while ($List = mysql_fetch_object($sqlquery)) 
	{
		$decimal = get_decimal_placed($List->subdivid,$sheetid);
		$measurement_contentarea = round($List->measurement_contentarea,$decimal);
		//if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
		//if($page > 100){
						/*if($_GET['varid'] == 1)
						{
							?>
							<div id="dialog" class="dialogwindow" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
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
						}
						$currentline = $start_line + 7;
						$prevpage = 100;
						$page = $newmbookpage;
						//$prevpage = $mpage;
						//$oldmbookno = $mbookno;
						$mbookno = $newmbookno;*/
						
		//}
		if($currentline>40)
		{ 
		
		?>
		<tr height="" class="label labelbold">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right" class="">
				<?php /*if($page == 100){ echo "C/o to page ".(0+1)." /General MB No.".$newmbookno; }else { echo "C/o to page ".($page+1)." /General MB No.".$mbookno; }*/ ?>
				C/o to page <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/General MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/General MB No.<?php echo $mbookno; } ?>
				</td>
				<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$prev_decimal,".",","); } ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
		</tr>
	<?php
		echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1);
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
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
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
								<input type="text" style="width:100%; border:none; text-align:right" class="label labelbold textboxcobf" name="txt_page" id="txt_page<?php echo $txtboxid; ?>" />
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
					if($prev_rowcount == 1)
					{
						mbookgenerateinsert_staff($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid,$zone_id);
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
							<?php if($prev_rowcount>1){ ?><input type="text" class="label labelbold textboxcobf" name="txt_page" style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" /><?php } else { echo "&nbsp"; }?>
							</td>
							<td width="35" align="center"><?php echo "&nbsp"; ?></td>
							<td width="65" colspan="3" align="center" class="label labelbold">Total</td>
							<td width="65" align="right" class="label labelbold">
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
		if($prev_rowcount == 1)
		{
			mbookgenerateinsert_staff($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid,$zone_id);
		}
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
			/*if($_GET['varid'] == 1)
			{
		?>
				<div id="dialog" class="dialogwindow" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
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
			}
			$currentline = $start_line + 7;
			$prevpage = 100;
			$page = $newmbookpage;
			//$prevpage = $mpage;
			//$oldmbookno = $mbookno;
			$mbookno = $newmbookno;*/
		}
			
		if($currentline>40)
		{
?>
<tr height="" class="label labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right" class="">
	<?php /*if($page == 100){ echo "C/o to page ".(0+1)." /General MB No.".$newmbookno; }else { echo "C/o to page ".($page+1)." /General MB No.".$mbookno; }*/ ?>
	C/o to page <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/General MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/General MB No.<?php echo $mbookno; } ?>
	</td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1); ?>
<tr height="" class="label labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right" class=""><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php 
			$currentline = $start_line + 8;
			//if($page == 100){ $page = $newmbookpage;  $mbookno = $newmbookno; }else{ $page++; }
			$page++;
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
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
	mbookgenerateinsert_staff($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid,$zone_id);
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
mbookgenerateinsert_staff($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid,$zone_id);
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
		<td style="border-style:none;" colspan="9" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" width="53%" align="right">&nbsp;Page '.$page.'&nbsp;&nbsp</td>
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '</table>';
}*/
if($_SESSION["final_bill"] == "Y"){
	if($currentline>20){
		echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
		echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1);
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
			 <td align="center" class="label labelbold">HEAD,FRFCF</td>
			 <td></td>
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
//$GenVersion = getPrintVersion($sheetid,$rbn,'G','staff',$zone_id);
$UsedMBArr[$mbookno][1] = $page;
$UsedMBArr[$mbookno][2] = 1;
$delete_mymbook_sql = "delete from mymbook where rbn = '$rbn' and sheetid = '$sheetid' and staffid = '$staffid' and zone_id = '$zone_id' and mtype = 'G' and genlevel = 'staff'";
$delete_mymbook_query = mysql_query($delete_mymbook_sql);
/*if($newmbookno == "")
{
	$insert_mymbook_sql = "insert into mymbook set mbno = '$oldmbookno', startpage = '$oldmbookpage', endpage = '$page', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'G', zone_id = '$zone_id', genlevel = 'staff', mbookorder = 1, active = 1, gen_version = '$GenVersion'";
	$insert_mymbook_query = mysql_query($insert_mymbook_sql);
}
else
{
	$insert_mymbook_sql1 = "insert into mymbook set mbno = '$oldmbookno', startpage = '$oldmbookpage', endpage = '100', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'G', zone_id = '$zone_id', genlevel = 'staff', mbookorder = 1, active = 1, gen_version = '$GenVersion'";
	$insert_mymbook_query1 = mysql_query($insert_mymbook_sql1);
	$insert_mymbook_sql2 = "insert into mymbook set mbno = '$newmbookno', startpage = '$newmbookpage', endpage = '$page', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'G', zone_id = '$zone_id', genlevel = 'staff', mbookorder = 2, active = 1, gen_version = '$GenVersion'";
	$insert_mymbook_query2 = mysql_query($insert_mymbook_sql2);
}*/

/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$MBord = 1;
//print_r($UsedMBArr);
foreach($UsedMBArr as $UsedMB => $UsedMbDet){
	$UsedMBStartpage = $UsedMbDet[0];
	$UsedMBEndpage 	 = $UsedMbDet[1];
	$UsedMBStatus 	 = $UsedMbDet[2];
	if(($UsedMBStartpage != '')&&($UsedMBEndpage != '')){
		//echo $UsedMB." = ".$UsedMBStartpage." = ".$UsedMBEndpage." = ".$UsedMBStatus."<br/>";
		$insert_mymbook_sql2 = "insert into mymbook set mbno = '$UsedMB', startpage = '$UsedMBStartpage', endpage = '$UsedMBEndpage', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'G', zone_id = '$zone_id', genlevel = 'staff', mbookorder = '$MBord', active = 1, gen_version = '$GenVersion', generatedate = NOW()";
		$insert_mymbook_query2 = mysql_query($insert_mymbook_sql2);
		//echo $insert_mymbook_sql2."<br/>";
		$MBord++;
	}
}

?>
<input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($summary_b,","); ?>"  />
<!--<table border="0" width="875" style="border-style:none" align="center" bgcolor="#000000" class='labelcontent printbutton'>
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none">&nbsp;
		</td>
	</tr>
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none" align="center">
			<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
		</td>
	</tr>
</table>-->
<input type="hidden" name="hid_not_generate" id="hid_not_generate" value="<?php echo $NotGenerate;//$_SESSION['NotGenerate']; ?>" />
<input type="button" name="btn_next" id="btn_next" class="BottomContent1" value="Next" onclick="Nextpage()" style="cursor:pointer;">

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">MBook List</h4>
      </div>
      <div class="modal-body" style="min-height:150px">
       <div style="width:55%; display:inline; float:left;">
	   	 <div style="height:20px">
		 	Click below box to select multiple Mbooks :
		 </div>
	   	 <div>
		 	<select id="NextMB" class="NextMB" multiple="multiple" style="width:400px;">
			   <option value=""> ------ Select Next MBook Nos ------</option>
			   <?php echo $objBind->BindNextMBlist($sheetid,'G',$mbookno); ?>
			</select>
			<br/><br/><br/>
			<div style="color:#FB133C; font-size:12px; font-weight:normal">* To select more than one mbook click again the above text box</div>
			<div style="color:#FB133C" align="center"><br />OR</div>
			<div style="color:#FB133C; font-size:12px; font-weight:normal"><br />* Press [Ctrl key] and Click the above text box </div>
		 </div>
	    </div>
		<div style="width:30%; display:inline; float:right">
			<div class="mbpgdiv" style="height:18px; color:#009ED2">&nbsp;Selected MBook No. & Page No.</div>
			<div id="MBPageRefSec"></div>
		</div>
      </div>

      <div class="modal-footer" style="text-align:center !important;">
        <button type="button" class="modal-btn-c" data-dismiss="modal">CLOSE</button>
        <input type="submit" name="modal_btn_next_mb" id="modal_btn_next_mb" class="modal-btn-n" value=" NEXT " />
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<input type="hidden" name="txt_sheet_id" id="txt_sheet_id" value="<?php echo $sheetid; ?>" />
<input type="hidden" name="txt_staffid" id="txt_staffid" value="<?php echo $_SESSION['sid']; ?>" />
<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>" />

</form>

<?php if($NextMBOption > 0){ ?>
	<script>
		var NoOfMB = "<?php echo $NextMBOption; ?>";
		BootstrapDialog.alert("You need to select next "+NoOfMB+" MBook to generate General MBook");
		$('#myModal').modal({backdrop:'static', keyboard:true, show:true});
		$(function(){
			$('#NextMB').change(function(event){ 
				var sheetid 		= 	$("#txt_sheet_id").val();
				var staffid			=	$("#txt_staffid").val();
				var rbn				=	$("#txt_rbn").val();
				var generatetype 	= 	"sw";
				$("#MBPageRefSec").html('');
				var x = 1;
				$.each($("#NextMB option:selected"), function(){   //alert($(this).text())
					var mbid 			= 	 $(this).val();//$("#NextMB option:selected").attr('value');//alert(currentmbooknovalue);
					var mbno 			= 	 $(this).text();//$("#NextMB option:selected").text();
					if(mbid != ""){
						$.post("MBookNoService.php", {currentmbook: mbid, currentbmookname: mbno, sheetid: sheetid, generatetype: generatetype, staffid: staffid, currentrbn: rbn}, function (data) { //alert(data);
							var pageno = data;
							var OutStr = "<div class='mbpgdiv'>MBook No. <input type='text' name='txt_next_mb"+x+"' class='mbtxt' value='"+mbno+"' readonly=''> &nbsp;Page : <input type='text' name='txt_next_mbpage"+x+"' class='mbtxt' value='"+pageno+"' readonly=''><input type='hidden' name='txt_no[]' class='mbtxt' value='"+x+"' readonly=''></div>";
							$("#MBPageRefSec").append(OutStr);
							x++;
						});
					}
				});
				
			});
		});
	</script>
<?php } ?>
<br/>
</body>
<script type="text/javascript">
   	$(function(){
		
	   	var getstr = document.getElementById("txt_boxid_str").value;
	   	var splitval = getstr.split(","); //alert(splitval.length);
	   	var x=0;
	   	for(x=0;x<splitval.length;x+=3){
			document.getElementById("txt_page"+splitval[x]).value = "C/o to page "+splitval[x+1]+"/General MB No."+"<?php echo $mbookno; ?>"; 
	   	}
		/*$('#myModal').modal({backdrop:'static', keyboard:true, show:true});
			$('#NextMB').change(function(event){ 
				var sheetid 		= 	$("#txt_sheet_id").val();
				var staffid			=	$("#txt_staffid").val();
				var rbn				=	$("#txt_rbn").val();
				var generatetype 	= 	"sw";
				$("#MBPageRefSec").html('');
				var x = 1;
				$.each($("#NextMB option:selected"), function(){   //alert($(this).text())
					var mbid 			= 	 $(this).val();//$("#NextMB option:selected").attr('value');//alert(currentmbooknovalue);
					var mbno 			= 	 $(this).text();//$("#NextMB option:selected").text();
					if(mbid != ""){
						$.post("MBookNoService.php", {currentmbook: mbid, currentbmookname: mbno, sheetid: sheetid, generatetype: generatetype, staffid: staffid, currentrbn: rbn}, function (data) { //alert(data);
							var pageno = data;
							var OutStr = "<div class='mbpgdiv'>MBook No. <input type='text' name='txt_next_mb"+x+"' class='mbtxt' value='"+mbno+"' readonly=''> &nbsp;Page : <input type='text' name='txt_next_mbpage"+x+"' class='mbtxt' value='"+pageno+"' readonly=''><input type='hidden' name='txt_no[]' class='mbtxt' value='"+x+"' readonly=''></div>";
							$("#MBPageRefSec").append(OutStr);
							x++;
						});
					}
				});
				
			});*/
		
   	});
	function Nextpage(){
		var NotGenerate  = document.getElementById("hid_not_generate").value;
		if(NotGenerate <= 0){
			/*BootstrapDialog.show({
				title: 'Alert',
				message: 'General Measurements Completed, Click below button to go to respective page.',
				buttons: [{
					label: 'General',
					action: function(dialog) {
						dialog.close();
						window.location.replace("MBookGenerateSection1.php");
					}
				}, {
					label: 'Steel',
					action: function(dialog) {
						dialog.close();
						window.location.replace("MBookGenerateSection2.php");
					}
				}]
			});*/
			url = "MBookGenerateSection2.php";
			window.location.replace(url);
		}else{
			url = "MBookGenerateSection1.php";
			window.location.replace(url);
		}
		//url = "MBookGenerateSection2.php";
		//window.location.replace(url);
   	}
	$(".NextMB").chosen();
</script>
   <style>
   		.chosen-container{
			width:400px !important;
		}
		/*.bootstrap-dialog-title{
			font-family:Verdana, Arial, Helvetica, sans-serif;
		}
		.bootstrap-dialog-footer-buttons > .btn-default{
			padding:8px;
			font-family:Verdana, Arial, Helvetica, sans-serif;
			font-weight:bold;
			background-color:#D9044F;
			color:#FFFFFF;
			border:1px solid #D9044F;
			cursor:pointer;
			padding-left:10px;
			padding-right:10px;
			font-size:14px;
		}
		.bootstrap-dialog-footer-buttons > .btn-default:hover{
			background-color:#B1013F;
		}*/
	.BottomContent1{
		position: fixed;
		bottom: 2px;
		right: 30px;
		z-index: 99;
		border: none;
		outline: none;
		background-color: #f24343;
		color: white;
		padding: 5px;
		border-radius: 10px;
		width: 100px;
		font-weight: bold;
		text-align: center;
		vertical-align: middle;
		pointer-events: none;
		cursor:pointer;
		pointer-events:auto;
		background-color:#009ff4;
		font-size:14px;
		letter-spacing:1px;
		padding:6px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-weight:bold;
		background-color:#D9044F;
		color:#FFFFFF;
		border:1px solid #D9044F;
		cursor:pointer;
		padding-left:10px;
		padding-right:10px;
		font-size:14px;
	}
	.BottomContent1:hover{
		background-color:#C90133;
	}
	.modal-body {
		font-size: 12px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		color:#002D95;
		font-weight:600;
	}
	.mbpgdiv{
		padding:5px;
		border:1px solid #EBEBEB;
	}
	.mbtxt{
		padding:1px 5px 1px 5px;
		width:30px;
		border:1px solid #4096FF;
		text-align:right
	}
	.modal-btn-n{
		padding:8px;
		border:1px solid #0156E4;
		background:#0156E4;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
	.modal-btn-n:hover{
		background:#0048CE;
		border:1px solid #0048CE;
	}
	.modal-btn-c{
		padding:8px;
		border:1px solid #F1015B;
		background:#F1015B;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
	.modal-btn-c:hover{
		background:#D5004A;
		border:1px solid #D5004A;
	}
	.bootstrap-dialog-footer-buttons > .btn-default{
		padding:8px;
		border:1px solid #0156E4;
		background:#0156E4;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
	</style>
</html>