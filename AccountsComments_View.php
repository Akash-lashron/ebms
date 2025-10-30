<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
include "library/common.php";
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
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
$sheetid = $_GET['workno'];
$sheet_data 		= 	getsheetdata($sheetid);
$exp_sheet_data 	= 	explode("@#*#@",$sheet_data);
$short_name			= 	$exp_sheet_data[0];
/*$select_comment_query = "select subdivid, accounts_remarks from measurementbook_temp where sheetid = '$sheetid' and accounts_remarks != ''";
$select_comment_sql = mysql_query($select_comment_query);

$sheet_count = 0;
$select_sheet_query = "select distinct(measurementbook_temp.rbn), sheet.short_name from measurementbook_temp
INNER JOIN sheet ON (measurementbook_temp.sheetid = sheet.sheet_id) where sheetid = '$sheetid'";
$select_sheet_sql = mysql_query($select_sheet_query);
if($select_sheet_sql == true)
{
	if(mysql_num_rows($select_sheet_sql)>0)
	{
		$SheetList = mysql_fetch_object($select_sheet_sql);
		$work_short_name = $SheetList->short_name;
		$rbn = $SheetList->rbn;
		$sheet_count = 1;
	}
}*/

?>
<?php require_once "Header.html"; ?>
<script>
     
	function find_workname()
	{		
		
		var xmlHttp;
		var data;
		var i,j;
			
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_workname.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				if(data=="")
				{
					alert("No Records Found");
					document.form.workname.value='';	
				}
				else
				{	
					document.form.workname.value			=	name[0].trim();
					document.form.txt_workorder_no.value	=	name[2].trim();
					document.form.txt_book_no1.value		=	Number(name[1]) + Number(1);
					document.form.txt_book_no.value			=	Number(name[1]) + Number(1);
					document.form.txt_bookpage_no1.value	=	Number(name[2]) + Number(1);
					document.form.txt_bookpage_no.value		=	Number(name[2]) + Number(1);
					document.form.txt_rab_no1.value			=	Number(name[3]) + Number(1);
					document.form.txt_rab_no.value			=	Number(name[3]) + Number(1);
	
							}
			}
		}
		xmlHttp.send(strURL);	
	}
	function goBack()
	{
	   	url = "AccountsComments.php";
		window.location.replace(url);
	}

</script>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
</SCRIPT>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->

<?php include "Menu.php"; ?>
 <!--==============================Content=================================-->
<div class="content">
     <div class="title">Accounts Comments</div>
    <div class="container_12">
        <div class="grid_12">
             <blockquote class="bq1">
                  <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <div class="container" style="text-align:center">
				  <br/>
 <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="4" cellspacing="4" align="center" class="label table1">
 	 <tr>
		<td colspan="5" align="left">&nbsp;&nbsp;&nbsp;Work Short Name : &nbsp;&nbsp;&nbsp;<?php echo $short_name; ?></td>
	</tr>
 	<tr>
		<td>Sl.No.</td><td>Date</td><td>Item No.</td><td>MBook No.</td><td>Accounts Comments</td>
	</tr>
<?php
$slno = 1;
$select_abstract_comment_query = "select subdivid, rbn, abstmbookno, accounts_remarks from measurementbook_temp where sheetid = '$sheetid' and accounts_remarks != ''";
$select_abstract_comment_sql = mysql_query($select_abstract_comment_query);
if($select_abstract_comment_sql == true)
{
	if(mysql_num_rows($select_abstract_comment_sql)>0)
	{
		while($AList = mysql_fetch_object($select_abstract_comment_sql))
		{
			$rbn_abs 				= $AList->rbn;
			$accounts_remarks_abs 	= $AList->accounts_remarks;
			$abstmbookno 			= $AList->abstmbookno;
			$item_no_abs			= getsubdivname($AList->subdivid);
?>
			<tr>
				<td><?php echo $slno; ?></td>
				<td>&nbsp;</td>
				<td><?php echo $item_no_abs; ?></td>
				<td><?php echo $abstmbookno; ?></td>
				<td align="left">&nbsp;<?php echo $accounts_remarks_abs; ?></td>
				
			</tr>
<?php
			$slno++;
		}
	}
}

$select_sub_abstract_comment_query = "select mbookgenerate_staff.subdivid, mbookgenerate_staff.rbn, mbookgenerate.mbno, 
mbookgenerate_staff.accounts_remarks from mbookgenerate_staff 
INNER JOIN mbookgenerate ON (mbookgenerate_staff.subdivid = mbookgenerate.subdivid)
where mbookgenerate_staff.sheetid = '$sheetid' and mbookgenerate_staff.accounts_remarks != '' and  mbookgenerate.sheetid = '$sheetid'";
$select_sub_abstract_comment_sql = mysql_query($select_sub_abstract_comment_query);
if($select_sub_abstract_comment_sql == true)
{
	if(mysql_num_rows($select_sub_abstract_comment_sql)>0)
	{
		while($SAList = mysql_fetch_object($select_sub_abstract_comment_sql))
		{
			$rbn_sa					= $SAList->rbn;
			$accounts_remarks_sa 	= $SAList->accounts_remarks;
			$mbookno_sa 			= $SAList->mbno;
			$item_no_sa 			= getsubdivname($SAList->subdivid);
?>
			<tr>
				<td><?php echo $slno; ?></td>
				<td>&nbsp;</td>
				<td><?php echo $item_no_sa; ?></td>
				<td><?php echo $mbookno_sa; ?></td>
				<td align="left">&nbsp;<?php echo $accounts_remarks_sa; ?></td>
				
			</tr>
<?php
			$slno++;
		}
	}
}

$select_date_query = "Select min(DATE_FORMAT(fromdate,'%Y-%m-%d')) as fromdate, max(DATE_FORMAT(todate,'%Y-%m-%d')) as todate from measurementbook_temp where sheetid = '$sheetid'";
$select_date_sql = mysql_query($select_date_query);
if($select_date_sql == true)
{
	if(mysql_num_rows($select_date_sql)>0)
	{
		$DateList = mysql_fetch_object($select_date_sql);
		$min_fromdate = $DateList->fromdate;
		$max_todate = $DateList->todate;
		
		$select_mbremark_query = "select mbookdetail.accounts_remarks, mbookheader.date, mbookdetail.subdiv_name from mbookdetail 
							INNER JOIN mbookheader ON (mbookheader.mbheaderid = mbookdetail.mbheaderid) where mbookheader.sheetid = '$sheetid' 
							and mbookheader.date  >= '$min_fromdate' AND mbookheader.date  <= '$max_todate' and mbookdetail.accounts_remarks != ''";
		$select_mbremark_sql = mysql_query($select_mbremark_query);
		if($select_mbremark_sql == true)
		{
			if(mysql_num_rows($select_mbremark_sql)>0)
			{
				while($MBList = mysql_fetch_object($select_mbremark_sql))
				{
					$remarks_str 			= $MBList->accounts_remarks;
					$Expremarks_str 		= explode("@R@",$remarks_str);
					$accounts_remarks_mb 	= $Expremarks_str[0];
					$mbookno_mb 			= $Expremarks_str[1];
					$item_no_mb 			= $MBList->subdiv_name;
					$item_date_mb 			= dt_display($MBList->date);
?>
					<tr>
						<td><?php echo $slno; ?></td>
						<td><?php echo $item_date_mb; ?></td>
						<td><?php echo $item_no_mb; ?></td>
						<td><?php echo $mbookno_mb; ?></td>
						<td align="left">&nbsp;<?php echo $accounts_remarks_mb; ?></td>
					</tr>
<?php
					$slno++;
				}
			}
		}
		
	}
}
?>
</table>
					<!--<tr>
						<td align="left" colspan="2">Work Short Name</td>
						<td align="left" ><?php echo $work_short_name; ?></td>
					</tr>
					<tr>
						<td align="left" colspan="2">RAB No.</td>
						<td align="left" >&nbsp;<?php echo $rbn; ?></td>
					</tr>-->
				  
     			  </div>
			   	<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
					<div class="buttonsection">
					<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> 
					</div>
					</div>
     </form>
   </blockquote>
  </div>

  </div>
 </div>
         <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
<script>
    $(function() {
	$.fn.validatembooktype = function(event) {	
				if($("#cmb_mbook_type").val()==""){ 
					var a="Please select the Measurement Type";
					$('#val_mbooktype').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_mbooktype').text(a);
				}
			}
	$.fn.validateworkorder = function(event) { 
					if($("#cmb_work_no").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
	$("#top").submit(function(event){
            $(this).validatembooktype(event);
			$(this).validateworkorder(event);
         });
	$("#cmb_work_no").change(function(event){
           $(this).validateworkorder(event);
         });
    $("#cmb_mbook_type").change(function(event){
           $(this).validatembooktype(event);
         });
			
	 });
</script>
    </body>
</html>

