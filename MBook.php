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
	$row = $row.'<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF"  class="label">';
	$row = $row.$table1;
	echo $row;
}

if($_POST["Back"] == " Back ")
{
     header('Location: Generate_Composite.php');
}
$sheetid=$_SESSION["sheet_id"]; 
$fromdate = $_SESSION['fromdate'];
$todate = $_SESSION['todate'];
$mbookno = $_SESSION["mb_no"];    
$mpage = $_SESSION["mb_page"];
$mbno_id = $_SESSION["mbno_id"];
$rbn = $_SESSION["rbn"];
$abstmbookno = $_SESSION["abs_mbno"];
$query = "SELECT sheet_id, sheet_name, work_order_no, work_name, tech_sanction, name_contractor, computer_code_no, agree_no, rbn FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery = mysql_query($query);
if ($sqlquery == true) {
    $List = mysql_fetch_object($sqlquery);
    $work_name = $List->work_name;    $tech_sanction = $List->tech_sanction;
    $name_contractor = $List->name_contractor;    $agree_no = $List->agree_no; $work_order_no = $List->work_order_no; 
	$ccno = $List->computer_code_no;
    //if($List->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$List->rbn + 1;}
	$runn_acc_bill_no = $rbn;
    $_SESSION["currentrbn"]=$runn_acc_bill_no;
}

$length = strlen($work_name);
//echo $length."<br/>";
$start_line = ceil($length/87);
//echo $start_line;

$Mbsteelgeneratedelsql		= 	"DELETE FROM mbookgenerate WHERE flag = 1 AND sheetid = '$sheetid'";
$Mbsteelgeneratedelsql_qry 	= 	mysql_query($Mbsteelgeneratedelsql);
$MbsteelmbookTempdelsql 	= 	"DELETE FROM measurementbook_temp WHERE flag = 1 AND sheetid = '$sheetid'";
$MbsteelmbookTempdelsql_qry = 	mysql_query($MbsteelmbookTempdelsql);

if($_GET['varid'] == 1)
{
	$deletequery=mysql_query("DELETE FROM oldmbook WHERE sheetid = '$sheetid' AND mbook_type = 'G'");
}
function mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$mpage,$contentarea,$abstmbookno,$rbn,$userid)
{ 
   $querys="INSERT INTO mbookgenerate set sheetid='$sheetid',divid='$prev_divid',subdivid='$prev_subdivid',
       fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=1,rbn='$rbn', abstmbookno = '$abstmbookno',
            mbgeneratedate=NOW(), staffid='$staffid', mbpage='$mpage', mbtotal='$contentarea', active=1, userid='$userid'";
 //echo $querys."<br>";
   $sqlquerys = mysql_query($querys);
}
if($_GET['newmbook'] != "")
{
$newmbookno = $_GET['newmbook'];
$newmbookpage_query = "select mbpage, allotmentid from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
$newmbookpage_sql = mysql_query($newmbookpage_query);
$newmbookpage = @mysql_result($newmbookpage_sql,0,'mbpage');
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
		$staffname = $staffList->staffname;
		$designation = $staffList->designationname;
		$result .= $staffname."*".$designation."*";
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
<script>
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
  </script>
<style type="text/css" media="print,screen" >
	table{ border-collapse: collapse; }
	td { border: 1px solid #CACACA; }
	@media screen {
        div.divFooter {
            display: none;
        }
    }
    @media print {
        div.divFooter {
            position: fixed;
            bottom: 0;
        } 
		.header{
		display: none !important;
		}
		.printbutton{
		display: none !important;
		}}
.ui-dialog > .ui-widget-header {background: #20b2aa; font-size:12px;}
.breakAfter {
	page-break-before: always;
}
.labelcontent
{
	font-family:Microsoft New Tai Lue;
	font-size:12pt;
	color:#000000;
}
.labelheadblue{
color:#0000CD;
font-weight:bold;
font-size:12px;
}
.labelcontentblue
{
color:#0000CD;
font-weight:bold;
/*font-size:12pt;	*/
}
.label
{
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
.submit_btn:hover {
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
  }
  .textboxcobf
  {
  	width:398px; 
	border:none; 
	text-align:right;
	font-weight:bold;
	color:#0000CD;
  }
</style>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
<body bgcolor="#000000" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="875" style=" text-align:center; left:198px;" height="56px" align="center" bgcolor="#20b2aa" class='header label'>
<tr style="position:fixed;">
<td style="color:#FFFFFF; border:none; font-size:18px;" width="874" height="56px" align="center" bgcolor="#20b2aa">GENERAL MEASUREMENT BOOK - COMPOSITE</td>
</tr>
</table>
<form name="form" id="form" method="post">
			<?php
			$title = '<table width="875" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="right" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
            echo $title;
            $table = "<table width='875' border='0'  cellpadding='2' cellspacing='2' align='center' bgcolor='#ffffff'>";
			$table = $table . "<tr>";;
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue'>Name of work:</td>";
            $table = $table . "<td width='' class='labelbold labelheadblue' colspan='3'>" . $work_name . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue' valign='top'>Technical Sanction No.</td>";
            $table = $table . "<td class='labelbold labelheadblue' colspan='3'> " . $tech_sanction . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue' valign='top'>Name of the contractor</td>";
            $table = $table . "<td class='labelbold labelheadblue' colspan='3'>" . $name_contractor . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue' valign='top'>Agreement No.</td>";
            $table = $table . "<td class='labelbold labelheadblue' colspan='3'>" . $agree_no . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue' valign='top'>Work Order No.</td>";
            $table = $table . "<td class='labelbold labelheadblue' colspan='3'>" . $work_order_no . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='200' nowrap='nowrap' class='labelbold labelheadblue' valign='top'>Running Account bill No.</td>";
            $table = $table . "<td class='labelbold labelheadblue' width = '150px'>" . $runn_acc_bill_no . "</td>";
			$table = $table . "<td class='labelbold labelheadblue' width='150px'>CC No.</td>";
			$table = $table . "<td class='labelbold labelheadblue'>" . $ccno . "</td>";
            $table = $table . "</tr>";
            $table = $table . "</table>";
           
            //$table1 = $table1 . "<table width='875' border='0'  bgcolor='#ffffff' cellpadding='2' cellspacing='2' align='center'>";
            $table1 = $table1 . "<tr height='25' bgcolor='#BDBDBD'>";
            $table1 = $table1 . "<td width='81' rowspan='2' class='labelcenter labelheadblue'>Date of Measurment</td>";
            $table1 = $table1 . "<td width='48' rowspan='2' class='labelcenter labelheadblue'>Item No.</td>";
            $table1 = $table1 . "<td width='390' rowspan='2' class='labelcenter labelheadblue'>Description of work</td>";
            $table1 = $table1 . "<td colspan='5' width='' class='labelcenter labelheadblue'>Measurements Upto Date</td>";
            $table1 = $table1 . "<td width='32' rowspan='2' class='labelcenter labelheadblue'>Per</td>";  //Remarks Field changed into Per.
            $table1 = $table1 . "</tr>";
            $table1 = $table1 . "<tr height='25' bgcolor='#BDBDBD'>";
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
$prev_subdivid = ""; $prev_contentarea = 0; $currentline = $start_line + 10; $line = $currentline; $prev_date = "";$page = $mpage; $txtboxid = 1;
$query = "SELECT DATE_FORMAT( mbookheader.date , '%d/%m/%Y' ) AS date ,  mbookdetail.subdivid , subdivision.subdiv_name , subdivision. div_id, 
mbookdetail.descwork, mbookdetail.measurement_no , mbookdetail.measurement_l , mbookdetail.measurement_b,  mbookdetail.structdepth_unit, 
mbookdetail.measurement_d , mbookdetail.measurement_contentarea , mbookdetail.remarks, schdule.measure_type, schdule.shortnotes, schdule.description, mbookheader.sheetid    
FROM mbookheader
INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) WHERE  mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND schdule.measure_type != 's' AND mbookdetail.mbdetail_flag != 'd' AND mbookheader.sheetid = '$sheetid' ORDER BY mbookheader.date, mbookdetail.subdivid ASC" ;
                //echo $query ;exit;
$sqlquery = mysql_query($query);
 if ($sqlquery == true) 
{
	while ($List = mysql_fetch_object($sqlquery)) 
	{
		$decimal = get_decimal_placed($List->subdivid,$sheetid);
					if($page > 100)
					{
						if($_GET['varid'] == 1)
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
						}
						$currentline = $start_line + 7;
						$prevpage = 100;
						$page = $newmbookpage;
						//$prevpage = $mpage;
						//$oldmbookno = $mbookno;
						$mbookno = $newmbookno;
						
					}
		if($currentline>40)
		{ 
		
		?>
		<tr height="">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right" class="labelheadblue">
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
			<tr height="">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right" class="labelheadblue"><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
				<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$prev_decimal,".",","); } ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
			</tr>
<?php 			
			$currentline = $start_line + 7; $page++;
		}
		if($List->subdivid != $prev_subdivid)// THIS IS FOR PRINT DATE, SHORTNOTE AND ITEM NAME
		{
		$querycount = "SELECT COUNT(DISTINCT date) FROM mbookheader WHERE mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND subdivid = '$List->subdivid'";
		$querycount_sql = mysql_query($querycount);
		$res = mysql_fetch_array($querycount_sql); 
		$rowcount = $res[0];
				if($prev_subdivid != "")
				{
				
					
					?>
					<tr height="">
						<td width="81"><?php echo "&nbsp"; ?></td>
						<td width="48" align="center"><?php echo "&nbsp"; ?></td>
						<td width="390" align="center" class="labelheadblue">
						<?php 
						if($prev_rowcount>1)
						{ 
							if($prev_measure_type != 'st')
							{
							?>
								<input type="text" name="txt_page" class="textboxcobf" id="txt_page<?php echo $txtboxid; ?>" />
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
						<tr height="">
							<td width="81"><?php echo "&nbsp"; ?></td>
							<td width="48" align="center"><?php echo "&nbsp"; ?></td>
							<td width="390" align="center" class="labelheadblue">
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
						$stafflist = stafflist($prev_subdivid,$prev_date,$sheetid);
						$explode_stafflist = explode("*",$stafflist);
					?>	
						<tr style='border:none'>
						<td style='border:none' colspan='9' align="right"><br/>
						<?php
						for($x2 = 0; $x2<count($explode_stafflist); $x2+=2)
						{
							echo $explode_stafflist[$x2]." - ".$explode_stafflist[$x2+1]."&emsp;";
							//echo "&emsp;";
						}
						?>
						</td>
						</tr>
						<tr style='border:none'><td style='border:none' colspan='9' align="right">Prepared by&emsp;&emsp;</td></tr>
					<?php	
						$currentline+=3;
					}
					if($prev_rowcount == 1)
					{
						mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
					}
					$sum1 .= $prev_subdivname.",".$prev_date.",".$prev_subdivid.",".$prev_divid.",".$contentarea.",".$prev_rowcount.",".$page.",".$txtboxid.",".$prev_decimal.",".$prev_remarks."@";
					$prev_contentarea = 0;
					$currentline++;	
				}
				if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
				$len1 = strlen($List->shortnotes);
				//echo $length."<br/>";
				$line_cnt1 = ceil($len1/96);
				//echo $List->subdiv_name." = ".$line_cnt1."<br/>";
		?>
			<tr height="">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5"></td>
				<td width="65">&nbsp;</td>
				<td width="32">&nbsp;</td>
			</tr>
			<tr height="">
				<td width="81" align="center"><?php echo $List->date; ?></td>
				<td width="48" align="center"><?php echo $List->subdiv_name; ?></td>
				<td colspan="5"><?php echo $List->shortnotes; ?></td>
				<td width="65"><?php echo "&nbsp"; ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
			</tr>
		<?php
		$currentline = $currentline+$line_cnt1+1;
		}
				$len2 = strlen($List->descwork);
				//echo $length."<br/>";
				$line_cnt2 = ceil($len2/55);
				//echo $List->subdiv_name." = ".$line_cnt2."<br/>";
		?>
		<!---  THE BELOW ROW IS FOR PRINT EACH RECORD ------>
			<tr height="">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48"><?php echo "&nbsp"; ?></td>
				<td width="390"><?php echo $List->descwork; ?></td>
				<td width="35" align="center"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?></td>
				<td width="65" align="center"><?php if($List->measurement_l != 0) { echo $List->measurement_l; } ?></td>
				<td width="65" align="center"><?php if($List->measurement_b != 0) { echo $List->measurement_b; } ?></td>
				<td width="65" align="center"><?php if($List->measurement_d != 0) { echo $List->measurement_d; } ?></td>
				<td width="65" align="right"><?php if($List->measurement_contentarea != 0) { echo number_format($List->measurement_contentarea,$decimal,".",","); } ?></td>
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
		$contentarea = round(($prev_contentarea + $List->measurement_contentarea),3);
		$prev_subdivid = $List->subdivid; $prev_subdivname = $List->subdiv_name; $prev_divid = $List->div_id; $prev_contentarea = $contentarea;
		$prev_date = $List->date; $prev_rowcount = $rowcount; $prevpage = $page; $prev_mbookno = $mbookno; $prev_struct_unit = $List->structdepth_unit;
		$currentline = $currentline+$line_cnt2; $prev_measure_type = $List->measure_type; $prev_remarks = $List->remarks; $prev_decimal = $decimal;
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
							<input type="text" name="txt_page" class="textboxcobf" id="txt_page<?php echo $txtboxid; ?>" />
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
							<td width="390" align="center" class="labelheadblue">
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
				$stafflist = stafflist($prev_subdivid,$prev_date,$sheetid);
						$explode_stafflist = explode("*",$stafflist);
					?>	
						<tr style='border:none'>
						<td style='border:none' colspan='9' align="right"><br/>
						<?php
						for($x2 = 0; $x2<count($explode_stafflist); $x2+=2)
						{
							echo $explode_stafflist[$x2]." - ".$explode_stafflist[$x2+1]."&emsp;";
							//echo "&emsp;";
						}
						?>
						</td>
						</tr>
						<tr style='border:none'><td style='border:none' colspan='9' align="right">Prepared by&emsp;&emsp;</td></tr>
						
</table> 
 
<?php  
		if($prev_rowcount == 1)
		{
			mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
		}
		$currentline+=3;
		$sum2 .= $prev_subdivname.",".$prev_date.",".$prev_subdivid.",".$prev_divid.",".$contentarea.",".$prev_rowcount.",".$page.",".$txtboxid.",".$prev_decimal.",".$prev_remarks."@"; 
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
	echo '<table width="875" style="border-style:none;" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF"  class="label">';
	echo '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">Page '.$page.'&nbsp;&nbsp</td></tr>';
	echo '</table>';
	echo "<p  style='page-break-after:always;'></p>";
		echo '<table width="875" border="0"  cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="right" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
		echo $table;
		$currentline = $start_line + 8;$page++;
	}
	echo '<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF"  class="label">';
	echo '<tr><td colspan="9" align="center"  style="color:#0000CD; font-weight:bold;">Summary</td></tr>';
	$contentarea = 0;$prev_subdivid = "";
	for($i=0;$i<count($summary1);$i+=10)
	{
		if($currentline>40)
		{
?>
<tr height="">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right" class="labelheadblue"><?php echo "C/o to page ".($page+1)." /General MB No.".$mbookno; ?></td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1); ?>
<tr height="">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right" class="labelheadblue"><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
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
			<tr height="">
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
	//$summary_b .= $summary1[$i+7].",".$page."*";
	mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
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
mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
echo '</table>';
echo '<table width="875" style="border-style:none;" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" width="53%" align="right">&nbsp;<br/><br/><br/><br/>Page '.$page.'<br/><br/><br/><br/>&nbsp;&nbsp</td>
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '</table>';
}
else
{
echo '<table width="875" style="border-style:none;" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" width="53%" align="right">&nbsp;<br/><br/><br/><br/>Page '.$page.'<br/><br/><br/><br/>&nbsp;&nbsp</td>
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '</table>';
}
?>
<input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($summary_b,","); ?>"  />
<table border="0" width="875" style="border-style:none" align="center" bgcolor="#000000" class='labelcontent printbutton'>
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none">&nbsp;
		</td>
	</tr>
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none" align="center">
			<input type="submit" name="Back" value=" Back " /> 
		</td>
	</tr>
</table>  
</form>
    </body>
	 <script type="text/javascript">
   $(function(){ 
   var getstr = document.getElementById("txt_boxid_str").value;
   var splitval = getstr.split(","); //alert(splitval.length);
   var x=0;
   for(x=0;x<splitval.length;x+=3)
   {
   		document.getElementById("txt_page"+splitval[x]).value = "C/o to page "+splitval[x+1]+"/General MB No."+"<?php echo $mbookno; ?>"; 
   }
   });
   </script>
</html>