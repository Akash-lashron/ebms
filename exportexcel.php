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
$zonename = $_SESSION['zonename'];

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
$selectmbook_detail = " select DISTINCT fromdate, todate, abstmbookno FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1' AND rbn = '$rbn'";
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno;
}
$selectmbookno = "select mbname, old_id from oldmbook WHERE mbook_type = 'G' AND sheetid = '$sheetid' AND staffid = '$staffid'";
$selectmbookno_sql = mysql_query($selectmbookno);
if(mysql_num_rows($selectmbookno_sql)>0)
{
	$Listmbookno = mysql_fetch_object($selectmbookno_sql);
	$mbookno = $Listmbookno->mbname; $oldmbookid = $Listmbookno->old_id;
	
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
	
	$selectnewmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '1' AND mbno != '$mbookno' AND staffid = '$staffid' AND rbn = '$rbn'";
	$selectnewmbookno_sql = mysql_query($selectnewmbookno);
	$newmbookno = @mysql_result($selectnewmbookno_sql,'mbno');
	
	$newmbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
	$newmbookpage_sql = mysql_query($newmbookpage);
	$newmbookpageno = @mysql_result($newmbookpage_sql,'mbpage')+1;
}
else
{
	$selectmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '1' AND staffid = '$staffid' AND rbn = '$rbn'";
	$selectmbookno_sql = mysql_query($selectmbookno);
	$mbookno = @mysql_result($selectmbookno_sql,'mbno');
	
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
}
$mpage = $mbookpageno;
$query = "SELECT sheet_id, sheet_name, work_order_no, work_name, tech_sanction, name_contractor, computer_code_no, agree_no, rbn FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery = mysql_query($query);
if ($sqlquery == true) {
    $List = mysql_fetch_object($sqlquery);
    $work_name = $List->work_name;    $tech_sanction = $List->tech_sanction;
    $name_contractor = $List->name_contractor;    $agree_no = $List->agree_no; $work_order_no = $List->work_order_no; 
	$ccno = $List->computer_code_no;
	$runn_acc_bill_no = $rbn;
}

$length = strlen($work_name);
$start_line = ceil($length/87);
function getabstractpage($sheetid,$subdivid)
{
	$select_abs_page_query = "select abstmbookno, abstmbpage from measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql = mysql_query($select_abs_page_query);
	$abstmbookno = @mysql_result($select_abs_page_sql,0,'abstmbookno');
	$abstractpage = @mysql_result($select_abs_page_sql,0,'abstmbpage');
	return "C/o to Page ".$abstractpage." /Abstract MB No. ".$abstmbookno;
}
function getcompositepage($sheetid,$subdivid)
{
	$select_abs_page_query = "select mbno, mbpage from mbookgenerate WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql = mysql_query($select_abs_page_query);
	$mbookno_compo = @mysql_result($select_abs_page_sql,0,'mbno');
	$mbookpageno_compo = @mysql_result($select_abs_page_sql,0,'mbpage');
	return "C/o to Page ".$mbookpageno_compo." /General MB No. ".$mbookno_compo;
}

require_once dirname(__FILE__) . '\PHPExcel.php';
$objPHPExcel = new PHPExcel();

 // 1-based index
//$col = 1;
$cellrow = 1;
$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);	
	
$objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, 'General M.Book No.'.$mbookno);
$cellrow++;
$objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, 'Name of Work');
$objPHPExcel->getActiveSheet()->mergeCells('C2:I2');
$objPHPExcel->getActiveSheet()->getStyle('C2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C2:I2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, $work_name);
$objPHPExcel->getActiveSheet()->getStyle('C2:I2')->getAlignment()->setWrapText(true);

$cellrow++;
$objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, 'Technical Sanction No.');
$objPHPExcel->getActiveSheet()->mergeCells('C3:I3');
$objPHPExcel->getActiveSheet()->getStyle('C3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, $tech_sanction);
$objPHPExcel->getActiveSheet()->getStyle('C3:I3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('C3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$cellrow++;
$objPHPExcel->getActiveSheet()->mergeCells('A4:B4');
$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, 'Name of the contractor.');
$objPHPExcel->getActiveSheet()->mergeCells('C4:I4');
$objPHPExcel->getActiveSheet()->getStyle('C4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, $name_contractor);
$objPHPExcel->getActiveSheet()->getStyle('C4:I4')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('C4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$cellrow++;
$objPHPExcel->getActiveSheet()->mergeCells('A5:B5');
$objPHPExcel->getActiveSheet()->getStyle('A5:B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A5:B5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, 'Agreement No.');
$objPHPExcel->getActiveSheet()->mergeCells('C5:I5');
$objPHPExcel->getActiveSheet()->getStyle('C5:I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, $agree_no);
$objPHPExcel->getActiveSheet()->getStyle('C5:I5')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('C5:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$cellrow++;
$objPHPExcel->getActiveSheet()->mergeCells('A6:B6');
$objPHPExcel->getActiveSheet()->getStyle('A6:B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A6:B6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, 'Work Order No.');
$objPHPExcel->getActiveSheet()->mergeCells('C6:I6');
$objPHPExcel->getActiveSheet()->getStyle('C6:I6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, $work_order_no);
$objPHPExcel->getActiveSheet()->getStyle('C6:I6')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('C6:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$cellrow++;
$objPHPExcel->getActiveSheet()->mergeCells('A7:B7');
$objPHPExcel->getActiveSheet()->getStyle('A7:B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A7:B7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, 'Running Account bill No.');
$objPHPExcel->getActiveSheet()->mergeCells('C7:I7');
$objPHPExcel->getActiveSheet()->getStyle('C7:I7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, $runn_acc_bill_no);
$objPHPExcel->getActiveSheet()->getStyle('C7:I7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('C7:I7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$cellrow++;
$objPHPExcel->getActiveSheet()->mergeCells('A8:A9');
$objPHPExcel->getActiveSheet()->getStyle('A8:A9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, 'Date of Measurment');
$objPHPExcel->getActiveSheet()->getStyle('A8:A9')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A'.$cellrow.':A'.$cellrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'.$cellrow.':A'.$cellrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->mergeCells('B8:B9');
$objPHPExcel->getActiveSheet()->getStyle('B8:B9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $cellrow, 'Item No.');
$objPHPExcel->getActiveSheet()->getStyle('B'.$cellrow.':B'.$cellrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B'.$cellrow.':B'.$cellrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->mergeCells('C8:C9');
$objPHPExcel->getActiveSheet()->getStyle('C8:C9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, 'Description of work');
$objPHPExcel->getActiveSheet()->getStyle('C'.$cellrow.':C'.$cellrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C'.$cellrow.':C'.$cellrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->mergeCells('D8:H8');
$objPHPExcel->getActiveSheet()->getStyle('D8:H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('D8:H8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $cellrow, 'Measurements Upto Date.');


$objPHPExcel->getActiveSheet()->mergeCells('I8:I9');
$objPHPExcel->getActiveSheet()->getStyle('I8:I9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('I8:I9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $cellrow, 'Remarks');

$cellrow++;
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $cellrow, 'No');
$objPHPExcel->getActiveSheet()->getStyle('D'.$cellrow.':D'.$cellrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E'.$cellrow.':E'.$cellrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F'.$cellrow.':F'.$cellrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G'.$cellrow.':G'.$cellrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('D'.$cellrow.':D'.$cellrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E'.$cellrow.':E'.$cellrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F'.$cellrow.':F'.$cellrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G'.$cellrow.':G'.$cellrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $cellrow, 'L.');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $cellrow, 'B.');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $cellrow, 'D.');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $cellrow, 'Contents of Area');
$objPHPExcel->getActiveSheet()->getStyle('H'.$cellrow.':H'.$cellrow)->getAlignment()->setWrapText(true);

$cellrow++; $slno = 1; 


$prev_subdivid = ""; $prev_contentarea = 0; $currentline = $start_line + 10; $line = $currentline; $prev_date = "";$page = $mpage; $txtboxid = 1;
$query = "SELECT DATE_FORMAT( mbookheader.date , '%d/%m/%Y' ) AS date ,  mbookdetail.subdivid , subdivision.subdiv_name , subdivision. div_id, 
mbookdetail.descwork, mbookdetail.measurement_no , mbookdetail.measurement_l , mbookdetail.measurement_b, mbookdetail.structdepth_unit, 
mbookdetail.measurement_d , mbookdetail.measurement_contentarea , mbookdetail.remarks, schdule.measure_type, schdule.shortnotes, schdule.description, mbookheader.sheetid   
FROM mbookheader
INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) WHERE  mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND schdule.measure_type != 's' AND mbookdetail.mbdetail_flag != 'd' AND mbookheader.sheetid = '$sheetid' AND mbookheader.staffid = '$staffid' ORDER BY mbookheader.date, mbookdetail.subdivid, mbookheader.mbheaderid, mbookdetail.mbdetail_id ASC" ;
$sqlquery = mysql_query($query);
if ($sqlquery == true) 
{
	while ($List = mysql_fetch_object($sqlquery)) 
	{
		$decimal = get_decimal_placed($List->subdivid,$sheetid);
		if($page > 100)
		{
			$currentline = $start_line + 7;
			$prevpage = 100;
			$page = $newmbookpageno;
			$mbookno = $newmbookno;
						
		}
		if($currentline>40)
		{ 
			$objPHPExcel->getActiveSheet()->mergeCells('C'.$cellrow.':G'.$cellrow);
			if($page == 100)
			{ 
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, 'C/o to page '.(0+1).' /General MB No.'.$newmbookno);
			}
			else 
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, 'C/o to page '.($page+1).' /General MB No.'.$mbookno);
			}
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $cellrow, $contentarea);
			
			$cellrow++;
			//echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1);
		
			$objPHPExcel->getActiveSheet()->mergeCells('C'.$cellrow.':G'.$cellrow);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, 'B/f from page '.$page.' /General MB No.'.$mbookno);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $cellrow, $contentarea);
			
			$cellrow++;
			$currentline = $start_line + 7; $page++;
		}
		if($List->subdivid != $prev_subdivid)
		{
			$querycount = "SELECT COUNT(DISTINCT date) FROM mbookheader WHERE mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND subdivid = '$List->subdivid' AND mbookheader.staffid = '$staffid'";
			$querycount_sql = mysql_query($querycount);
			$res = mysql_fetch_array($querycount_sql); 
			$rowcount = $res[0];
			if($prev_subdivid != "")
			{
				if($prev_measure_type != 'st')
				{
					if($prev_rowcount>1)
					{ 
					
						/*<input type="text" class="labelbold" name="txt_page"  style="width:398px; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />*/
					 
					} 
					else 
					{ 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, getcompositepage($sheetid,$prev_subdivid));
					}
				}
				if($prev_measure_type != 'st')
				{
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $cellrow, 'Total');
				}
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $cellrow, $contentarea);
				if($prev_measure_type != 'st')
				{
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $cellrow, $prev_remarks);
				}
				{
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $cellrow, $prev_struct_unit);
				}
				$cellrow++;
							
				if($prev_measure_type == 'st')
				{
					$contentarea = ($contentarea/1000);
					if($prev_rowcount>1)
					{ 
						/*<input type="text" class="labelbold" name="txt_page"  style="width:398px; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />*/
					} 
					else 
					{ 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, getcompositepage($sheetid,$prev_subdivid));
					}
					$objPHPExcel->getActiveSheet()->mergeCells('E'.$cellrow.':G'.$cellrow);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $cellrow, 'Total');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $cellrow, $contentarea);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $cellrow, $prev_remarks);
					$cellrow++;
				}
				if(($prev_date != $List->date) && ($prev_date != ""))
				{
					$cellrow++;
					$cellrow++;
					$objPHPExcel->getActiveSheet()->mergeCells('A'.$cellrow.':C'.$cellrow);
					$objPHPExcel->getActiveSheet()->mergeCells('D'.$cellrow.':F'.$cellrow);
					$objPHPExcel->getActiveSheet()->mergeCells('G'.$cellrow.':I'.$cellrow);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, 'Approved By');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $cellrow, 'Checked By');
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $cellrow, 'Prepared By');
					$currentline+=3;
					$cellrow++;
				}
				$sum1 .= $prev_subdivname.",".$prev_date.",".$prev_subdivid.",".$prev_divid.",".$contentarea.",".$prev_rowcount.",".$page.",".$txtboxid.",".$prev_decimal.",".$prev_remarks."@"; 
				$prev_contentarea = 0;
				$currentline++;	
			}
			if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
			$len1 = strlen($List->shortnotes);
			$line_cnt1 = ceil($len1/96);
			$snotes = $List->shortnotes;
			$degcelsius = "&#8451";
			$shortnotes = str_replace("DEGCEL","$degcelsius",$snotes);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, $List->date);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $cellrow, $List->subdiv_name);
			$objPHPExcel->getActiveSheet()->mergeCells('C'.$cellrow.':G'.$cellrow);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, $List->shortnotes);
			$cellrow++;
			$currentline = $currentline+$line_cnt1+1;
			$len2 = strlen($List->descwork);
			$line_cnt2 = ceil($len2/55);
			/*THE BELOW ROW IS FOR PRINT EACH RECORD */
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, $List->descwork);
			if($List->measurement_no != 0) 
			{ 
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $cellrow, $List->measurement_no);
			} 
			if($List->measurement_l != 0) 
			{ 
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $cellrow, $List->measurement_l);
			}
			if($List->measurement_b != 0) 
			{ 
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $cellrow, $List->measurement_b); 
			}
			if($List->measurement_d != 0) 
			{ 
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $cellrow, $List->measurement_d,$decimal); 
			} 
			if($List->measurement_contentarea != 0) 
			{ 
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $cellrow, $List->measurement_contentarea);
			} 
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
		}
		$cellrow++;
		$contentarea = ($prev_contentarea + $List->measurement_contentarea);
		$prev_subdivid = $List->subdivid; $prev_subdivname = $List->subdiv_name; $prev_divid = $List->div_id; $prev_contentarea = $contentarea;
		$prev_date = $List->date; $prev_rowcount = $rowcount; $prevpage = $page; $prev_mbookno = $mbookno; $prev_struct_unit = $List->structdepth_unit;
		$currentline = $currentline+$line_cnt2; $prev_measure_type = $List->measure_type; $prev_remarks = $List->remarks; $prev_decimal = $decimal;
		$txtboxid++;
	}
	/*<input type="hidden" name="txt_textboxcount" id="txt_textboxcount" value="<?php echo $txtboxid; ?>" />*/
	if($prev_measure_type != 'st')
	{
		if($prev_rowcount>1)
		{ 
			/*<input type="text" class="labelbold" name="txt_page"  style="width:398px; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" /> */
		} 
		else 
		{ 
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, getcompositepage($sheetid,$prev_subdivid));
		}
	}
	if($prev_measure_type != 'st')
	{
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $cellrow, 'Total');
	}
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $cellrow, $contentarea);
	if($prev_measure_type != 'st')
	{
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $cellrow, $prev_remarks);
	}
	{
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $cellrow, $prev_struct_unit);
	}
	$cellrow++;
	if($prev_measure_type == 'st')
	{
		$contentarea = ($contentarea/1000);
		if($prev_rowcount>1)
		{ 
			/*<input type="text" class="labelbold" name="txt_page"  style="width:398px; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />*/
		} 
		else 
		{ 
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $cellrow, getcompositepage($sheetid,$prev_subdivid)); 
		}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $cellrow, 'Total');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $cellrow, $contentarea);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $cellrow, $prev_remarks);
		$cellrow++;
	}
					
	$cellrow++;
	$cellrow++;
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$cellrow.':C'.$cellrow);
	$objPHPExcel->getActiveSheet()->mergeCells('D'.$cellrow.':F'.$cellrow);
	$objPHPExcel->getActiveSheet()->mergeCells('G'.$cellrow.':I'.$cellrow);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $cellrow, 'Approved By');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $cellrow, 'Checked By');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $cellrow, 'Prepared By');
	$cellrow++;
	$currentline+=3;
	$currentline+=3;
	$sum2 .= $prev_subdivname.",".$prev_date.",".$prev_subdivid.",".$prev_divid.",".$contentarea.",".$rowcount.",".$page.",".$txtboxid.",".$prev_decimal.",".$prev_remarks."@"; 
}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="sample.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objPHPExcel->setActiveSheetIndex(0); 
$objWriter->save('php://output');
//echo $rowval;
exit;