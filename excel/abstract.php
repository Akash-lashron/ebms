<?php 
//session_start();ob_start();
include "db_connect.php";
include "library/common.php";
include "date_format.php";
//include "Excel_Tool.php";
//echo "Sheet ID" . " " .$_GET['sheet_id'].'<Br>';
$sheetid=$_GET['sheetid'];
$id=$_GET['id'];

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');
//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
if (PHP_SAPI == 'cli')die('This example should only be run from a Web Browser');
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php'; /** Include PHPExcel */

$objPHPExcel = new PHPExcel();// Create new PHPExcel object

// Set Orientation, size and scaling
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

$sqlsheet="select work_name,work_order_no from sheet where active=1";
$rssheet=mysql_query($sqlsheet);
if($rssheet == true){
	 $worksheet= mysql_fetch_object($rssheet);
	 $workorderno=trim($worksheet->work_order_no);
	 $wrkname=trim($worksheet->work_name);
	 $workname=str_replace("Name of Work :", "", $wrkname);}
	 
$sqlmbookheader="select mbook_header.mbheader_id,mbook_header.tech_sanction,mbook_header.subdiv_id,mbook_header.name_contractor,mbook_header.agree_no,mbook_header.runn_acc_bill_no 
							from mbook_header INNER JOIN mbook_detail ON (mbook_detail.mbheader_id = mbook_header.mbheader_id)
							WHERE mbook_header.subdiv_id = mbook_detail.subdiv_id";
$rsmbookheader=mysql_query($sqlmbookheader,$conn);

if($rsmbookheader == true){
	$rsmbookheader_records=mysql_fetch_object($rsmbookheader);
	$techsanction=trim($rsmbookheader_records->tech_sanction);
	$contractorname=trim($rsmbookheader_records->name_contractor);
	$agreeno=trim($rsmbookheader_records->agree_no);
	$billno=trim($rsmbookheader_records->runn_acc_bill_no);}	
	
$objPHPExcel->setActiveSheetIndex(0);// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1","Name of work:");
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1",$workname);
$objPHPExcel->getActiveSheet()->getStyle("B1:F1")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B1:F1')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('B1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:F1');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2","Technical Sanction No.");
$objPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B2",$techsanction);
$objPHPExcel->getActiveSheet()->getStyle("B2:F2")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B2:F2')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('B2:F2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:F2');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A3","Work Order No.");
$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B3:F3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B3",$workorderno);
$objPHPExcel->getActiveSheet()->getStyle("B3:F3")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B3:F3')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('B3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G1","Name of the contractor");
$objPHPExcel->getActiveSheet()->getStyle("G1")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H1",$contractorname);
$objPHPExcel->getActiveSheet()->getStyle("H1:L1")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('H1:L1')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('H1:L1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H1:L1');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G2","Agreement No.");
$objPHPExcel->getActiveSheet()->getStyle("G2")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H2:L2');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H2",$agreeno);
$objPHPExcel->getActiveSheet()->getStyle("H2:L2")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('H2:L2')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('H2:L2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G3","Running Account bill No.");
$objPHPExcel->getActiveSheet()->getStyle("G3")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H3:L3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H3",$billno);
$objPHPExcel->getActiveSheet()->getStyle("H3:L3")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('H3:L3')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('H3:L3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(9.29);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17.43);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8.57);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16.29);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(4.14);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(4.65);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8.14);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12.14);

$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(55.50);// SET CELLS HEIGHT
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(25);

cellColor('A1:A3','C0C0C0');
cellColor('G1:G3','C0C0C0');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:L4');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A4","Abstract Cost for Engineering Hall-IV for the month Jan'11");
$objPHPExcel->getActiveSheet()->getStyle("A4:L4")->getFont()->setBold(true)->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A4:L4')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A4:L4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A1:L7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:A7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B5:B7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C6:C7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D5:D6');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E5:E7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F5:F6');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G6:G7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H6:H7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J5:K5');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J6:J7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('L5:L7');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A5',"Boq")
     		->setCellValue('B5',"Description of work")
			->setCellValue('C5',"")
			->setCellValue('C6',"Contents or Area")
			->setCellValue('D5',"Rate")
			->setCellValue('D7',"Rs. P.")
			->setCellValue('E5',"Per")
			->setCellValue('F5',"Total value to Date")
			->setCellValue('F7',"Rs. P.")
			->setCellValue('G5',"Deduct previous Measurements")
			->setCellValue('G6',"Page")
			->setCellValue('H6',"Quantity")
			->setCellValue('I6',"Amount")
			->setCellValue('I7',"Rs. P.")
			->setCellValue('J5',"Since Last Measurement")
			->setCellValue('J6',"Quantity")
			->setCellValue('K6',"Value")
			->setCellValue('K7',"Rs.P.")
			->setCellValue('L5',"Remarks");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10.14);			
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(5);			
$objPHPExcel->getActiveSheet()->getStyle('C6')->getAlignment()->setWrapText(true);				
$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setWrapText(true);			
cellColor('A5:L7','C0C0C0');
$objPHPExcel->getActiveSheet()->getStyle('E5:E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//$objPHPExcel->getActiveSheet()->getStyle('G5:I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A5:L7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A5:L7")->getFont()->setBold(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('I7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('K7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('G6:G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H6:H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A5:A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C6:C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('K6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('J6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('L5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$querys = "SELECT mb_id, sheet_id, mb_date, fromdate, todate, mb_no, mb_page, rbn, active FROM mbookgenerate WHERE active =1
        AND sheet_id ='$sheetid'  AND mb_id ='$id'";
//echo $querys;
$sqlquerys = mysql_query($querys);
//$Lists = mysql_fetch_object($sqlquerys);
 //$mb_page = $Lists->mb_page; 
 $mb_page=@mysql_result($sqlquerys,0,'mb_page');
 


function cellColor($cells,$color){
    global $objPHPExcel;
    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,	
        'startcolor' => array(
             'rgb' => $color)));}
$col=10;$sdivid = '';$cell=10; $currentcell=8;$name=0; $First=1; $Total =0;$colname=array("A","B","C","D","E","F","G","H","I","J","K","L");
$pagebreak =38;$tabledisplay=0; $page=1; $summary='';$break=0;$len=0;
		
$pageline=30; $pagecount=1;  $brdisplay=0; $pages = $mb_page; $tablevisible = 0; 
$TotalValue =0;$TotalDeduct=0;$TotalSinceLast=0;$CarryS=0;
		
 $query = "SELECT    DISTINCT mbook.subdiv_id,mbook.sheet_id,mbook.mbdate,mbook.mb_page,mbook.mb_total
                            FROM  mbook    INNER JOIN mbookgenerate  ON (mbook.mb_id = mbookgenerate.mb_id)
                            WHERE mbook.sheet_id ='$sheetid' AND mbook.mb_id ='$id' GROUP BY mbook.subdiv_id ASC";					
 $sqlquery = mysql_query($query);
 $grandtotal='';
  if ($sqlquery == true)
  {
    while ($List = mysql_fetch_object($sqlquery)) 
	{
        if ($sdivid != $List->subdiv_id) 
		{
			$objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':L'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$currentcell,"Sample");
			$mergeCells='B'.$currentcell.':'.'L'.$currentcell;
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($mergeCells);	
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,"getscheduledescription");			
			$objPHPExcel->getActiveSheet()->getStyle('B'.$currentcell)->getAlignment()->setWrapText(true);		
			$objPHPExcel->getActiveSheet()->getRowDimension('26')->setRowHeight(60);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$currentcell++;
			$First=3;
			$Total = number_format($List->mb_total, 3, '.', '');
			
			if($First == 3)
			{
				$objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':L'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$currentcell,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,"Qty Vide ".$List->mb_page);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,$List->mb_total);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$currentcell,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$currentcell,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$currentcell,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$currentcell,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$currentcell,"");
				$currentcell++;				
				$First=4;
				$BR =$Total;//$Total =0;
				$TotalValue =$TotalValue +$BR;$TotalDeduct =$TotalDeduct +$BR;
			}
			$grandtotal =$grandtotal."*".$TotalValue;
			
			/************************** CARRY FORWARD ***************************/
				if($currentcell	== 23)
				{	
					$currentcell=$currentcell;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$currentcell,"");
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,"C/O");
					//$currentcell++;
				}	
					
			if($First == 4)
			{
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':L'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$schduledetails ="getschduledetails";//($sheetid,$id);
				$schduledt = explode("*", $schduledetails); 
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$currentcell,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,"Total");
				cellColor('B'.$currentcell, '00CCFF');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,$Total);
				$schduledetails =getschduledetails($sheetid,$id);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,$schduledt[0]);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$currentcell,$schduledt[1]);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$currentcell,number_format($Total, 2, '.', ''));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,$List->mb_page);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,number_format("0.000",3,'.',''));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$currentcell,number_format("0.00",2,'.',''));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,$BR);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$currentcell,number_format($BR, 2, '.', ''));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$currentcell,"");
				$currentcell++;
			}				
		}	
	
	}
	
	$currentcell  = $currentcell-1;
	if($currentcell == 31)
	{
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$currentcell,"");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,"");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,"");
		$schduledetails =getschduledetails($sheetid,$id);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,"");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$currentcell,"");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$currentcell,"");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,"");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,"");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$currentcell,"");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$currentcell,"");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$currentcell,"");
	}
	
	$tot=explode("*", $grandtotal);	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':L'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,"Total");
	cellColor('B'.$currentcell, '00CCFF');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,$Total);
	$schduledetails =getschduledetails($sheetid,$id); 
	$schduledt = explode("*", $schduledetails); 
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,$schduledt[0]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$currentcell,$schduledt[1]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$currentcell,number_format($tot[7], 2, '.', ''));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$currentcell,number_format("0.00",2,'.',''));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$currentcell,number_format($tot[7], 2, '.', ''));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$currentcell,"");
	$currentcell++;
	
	/************ A *****************/
	$objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':L'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,"TOTAL COST (Rs.)");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$currentcell,number_format($tot[7], 2, '.', ''));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$currentcell,number_format("0.00",2,'.',''));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$currentcell,number_format($tot[7], 2, '.', ''));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$currentcell,"");
	$currentcell++;
	
	/************ B *****************/
	$objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':L'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,"Less Overall Rebate = 0.5 %");
	$objPHPExcel->getActiveSheet()->getStyle('B'.$currentcell)->getAlignment()->setWrapText(true);	
	$objPHPExcel->getActiveSheet()->getStyle('B'.$currentcell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$currentcell,number_format($tot[7], 2, '.', '')*0.005);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$currentcell,number_format("0.00",2,'.',''));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$currentcell,number_format($tot[7], 2, '.', '')*0.005);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$currentcell,"");
	$currentcell++;
	
	$LessOverallRebateDeduct = number_format($tot[7], 2, '.', '')*0.005;
	$TotalValueCost= number_format($tot[7], 2, '.', '');
	
	/************ C *****************/
	$objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':L'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,"Gross Amount (Rs.)");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$currentcell,number_format($TotalValueCost - $LessOverallRebateDeduct, 2,'.',''));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$currentcell,"0.00");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$currentcell,"");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$currentcell,number_format($TotalValueCost - $LessOverallRebateDeduct, 2,'.',''));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$currentcell,"");
	$objPHPExcel->getActiveSheet()->getStyle('B'.$currentcell)->getAlignment()->setWrapText(true);	
	$currentcell++;
	$objPHPExcel->getActiveSheet()->getRowDimension('33')->setRowHeight(29.50);
 }			
			 
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Abstract.xls"');
header('Cache-Control: max-age=0');// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1'); // If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objPHPExcel->setActiveSheetIndex(0); 
$objWriter->save('php://output');
exit;
?>
