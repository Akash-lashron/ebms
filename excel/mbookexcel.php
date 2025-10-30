<?php 
//session_start();ob_start();
include "db_connect.php";
include "library/common.php";
include "date_format.php";
//include "Excel_Tool.php";

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');
//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
if (PHP_SAPI == 'cli')die('This example should only be run from a Web Browser');
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php'; /** Include PHPExcel */
$objPHPExcel = new PHPExcel();// Create new PHPExcel object

function headerdisplay($workname,$techsanction,$contractorname,$agreeno,$workorderno,$billno)
{
    $objPHPExcel = new PHPExcel();// Create new PHPExcel object

    $objPHPExcel->setActiveSheetIndex(0);// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1","Name of work:");
$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:B1');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C1:I1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1",$workname);
$objPHPExcel->getActiveSheet()->getStyle("C1:I1")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C1:I1')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:B2');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2","Technical Sanction No.");
$objPHPExcel->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:I2');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C2",$techsanction);
$objPHPExcel->getActiveSheet()->getStyle("C2:I2")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C2:I2')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C2:I2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:B3');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A3","Name of the contractor");
$objPHPExcel->getActiveSheet()->getStyle("A3:B3")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:I3');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C3",$contractorname);
$objPHPExcel->getActiveSheet()->getStyle("C3:I3")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C3:I3')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C3:I3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:B4');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A4","Agreement No.");
$objPHPExcel->getActiveSheet()->getStyle("A4:B4")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C4:I4');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C4",$agreeno);
$objPHPExcel->getActiveSheet()->getStyle("C4:I4")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C4:I4')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:B5');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A5","Work Order No.");
$objPHPExcel->getActiveSheet()->getStyle("A5:B5")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A5:B5')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A5:B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C5:I5');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C5",$workorderno);
$objPHPExcel->getActiveSheet()->getStyle("C5:I5")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C5:I5')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C5:I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A6:B6');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A6","Running Account bill No.");
$objPHPExcel->getActiveSheet()->getStyle("A6:B6")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A6:B6')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A6:B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C6:I6');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C6",$billno);
$objPHPExcel->getActiveSheet()->getStyle("C6:I6")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C6:I6')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C6:I6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

//$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(28);// SET CELLS HEIGHT
//$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);
//$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(25);
//$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(25);

$objPHPExcel->getActiveSheet()->getStyle('A1:I6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
// SET BORDER COLOR $objPHPExcel->getActiveSheet()->getStyle('A1:J6')->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLUE));
cellColor('A1:A6', 'C0C0C0');	
	

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:A8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B7:B8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C7:C8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D7:H7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I7:I8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:D9');
//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
//$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
//$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
//$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
//$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
//$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
//$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(5);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A7',"Date of Measure")
            ->setCellValue('B7',"Boq")
			->setCellValue('C7',"Description of work")
			->setCellValue('D7',"Measurements Upto Date")
			->setCellValue('D8',"No.")
			->setCellValue('E8',"L.")
			->setCellValue('F8',"B.")
			->setCellValue('G8',"D.")
			->setCellValue('H8',"Contents or Area")
			->setCellValue('I7',"Remarks")
			->setCellValue('B9','Earth work Excavation  for sump');
cellColor('A7:I8','C0C0C0');

}

function getRowcount($text, $width=55) {
    $rc = 0;
    $line = explode("\n", $text);
    foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
    }
    return $rc;
}
$sqlsheet="select work_name,work_order_no from sheet where active=1";
$rssheet=mysql_query($sqlsheet);
if($rssheet == true){
	 $worksheet= mysql_fetch_object($rssheet);
	 $workorderno=trim($worksheet->work_order_no);
	 $wrkname=trim($worksheet->work_name);
	 $workname=str_replace("Name of Work :", "", $wrkname);}
	 
$sqlmbookheader="select mbook_header.mbheader_id,mbook_header.tech_sanction,mbook_header.subdiv_id,mbook_header.name_contractor,mbook_header.agree_no,mbook_header.runn_acc_bill_no 
							from mbook_header INNER JOIN mbook_detail ON (mbook_detail.mbheader_id = mbook_header.mbheader_id)
							WHERE mbook_header.subdiv_id = mbook_detail.subdiv_id LIMIT 0 , 1";
$rsmbookheader=mysql_query($sqlmbookheader,$conn);



if($rsmbookheader == true){
	$rsmbookheader_records=mysql_fetch_object($rsmbookheader);
	$techsanction=trim($rsmbookheader_records->tech_sanction);
	$contractorname=trim($rsmbookheader_records->name_contractor);
	$agreeno=trim($rsmbookheader_records->agree_no);
	$billno=trim($rsmbookheader_records->runn_acc_bill_no);}	
	
	 //headerdisplay($wrkname,$techsanction,$contractorname,$agreeno,$workorderno,$billno);	
        $objPHPExcel = new PHPExcel();// Create new PHPExcel object

    $objPHPExcel->setActiveSheetIndex(0);// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1","Name of work:");
$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:B1');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C1:I1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1",$workname);
$objPHPExcel->getActiveSheet()->getStyle("C1:I1")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C1:I1')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:B2');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2","Technical Sanction No.");
$objPHPExcel->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:I2');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C2",$techsanction);
$objPHPExcel->getActiveSheet()->getStyle("C2:I2")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C2:I2')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C2:I2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:B3');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A3","Name of the contractor");
$objPHPExcel->getActiveSheet()->getStyle("A3:B3")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:I3');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C3",$contractorname);
$objPHPExcel->getActiveSheet()->getStyle("C3:I3")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C3:I3')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C3:I3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:B4');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A4","Agreement No.");
$objPHPExcel->getActiveSheet()->getStyle("A4:B4")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C4:I4');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C4",$agreeno);
$objPHPExcel->getActiveSheet()->getStyle("C4:I4")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C4:I4')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:B5');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A5","Work Order No.");
$objPHPExcel->getActiveSheet()->getStyle("A5:B5")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A5:B5')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A5:B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C5:I5');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C5",$workorderno);
$objPHPExcel->getActiveSheet()->getStyle("C5:I5")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C5:I5')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C5:I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A6:B6');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A6","Running Account bill No.");
$objPHPExcel->getActiveSheet()->getStyle("A6:B6")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A6:B6')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('A6:B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C6:I6');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C6",$billno);
$objPHPExcel->getActiveSheet()->getStyle("C6:I6")->getFont()->setName('Arial')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C6:I6')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('C6:I6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(28);// SET CELLS HEIGHT
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(25);
$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(25);

$objPHPExcel->getActiveSheet()->getStyle('A1:I6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
// SET BORDER COLOR $objPHPExcel->getActiveSheet()->getStyle('A1:J6')->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLUE));
cellColor('A1:A6', 'C0C0C0');	
	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:A8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B7:B8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C7:C8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D7:H7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I7:I8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:D9');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A7',"Date of Measure")
            ->setCellValue('B7',"Boq")
			->setCellValue('C7',"Description of work")
			->setCellValue('D7',"Measurements Upto Date")
			->setCellValue('D8',"No.")
			->setCellValue('E8',"L.")
			->setCellValue('F8',"B.")
			->setCellValue('G8',"D.")
			->setCellValue('H8',"Contents or Area")
			->setCellValue('I7',"Remarks")
			->setCellValue('B9','Earth work Excavation  for sump');
cellColor('A7:I8','C0C0C0');
	$col=10;$sdivid = '';$cell=10; $currentcell=10;$name=0; $First=1; $Total =0;$colname=array("A","B","C","D","E","F","G","H","I");
        $pagebreak =38;$tabledisplay=0; $page=1; $summary='';$break=0;$len=0;$BrTotal=0;
$query = "SELECT mbook_header.date, mbook_header.div_id , mbook_header.subdiv_id
        , subdivision.subdiv_name , mbook_detail.desc_work, mbook_detail.measurement_no
        , mbook_detail.measurement_l , mbook_detail.measurement_b, mbook_detail.measurement_d
        , mbook_detail.measurement_contentarea        , mbook_detail.remarks
        FROM mbook_header
    INNER JOIN mbook_detail    ON (mbook_header.mbheader_id = mbook_detail.mbheader_id) AND (mbook_header.subdiv_id = mbook_detail.subdiv_id)
    INNER JOIN subdivision     ON (mbook_header.subdiv_id = subdivision.subdiv_id)";
                $sqlquery = mysql_query($query);
                 while ($List = mysql_fetch_object($sqlquery)) {
                     $objPHPExcel->getActiveSheet()->getRowDimension($currentcell)->setRowHeight(-1);
				 $objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':I'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                 if($break == 1) { $pagebreak=$currentcell+41;$page =$page+1;$break=0;
                                 if($page == 4){$pagebreak=157;}}
                                 if($page == 6){  $pagebreak=213;$break=0;}
                     if ($sdivid != $List->subdiv_id) {
                          if($First == 2) {
                            cellColor('C'.$currentcell,'F5A9A9');
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,"Total");
                            cellColor('H'.$currentcell,'F5A9A9');
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,$Total);
                            if($BrTotal == 1)  { 
                                $summary =$summary."-----".getsubdivname($sdivid).",".$page.",".$Total;       $BrTotal=0;
                                }
                            $currentcell=$currentcell+1;$Total =0;$First=1;
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':I'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $mergeCells='G'.$currentcell.':'.'H'.$currentcell;
                            $objPHPExcel->getActiveSheet()->getStyle('G'.$currentcell)->getFont()->setBold(true)->setName('Arial')->setSize(11);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,"");
                            if($page == 4){//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"Page :". $page);
                            $page =$page+1;}
                            else if($page == 5){//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"Page :". $page);
                            $currentcell=$currentcell+1; }
                            else if($page == 7){if($BrTotal == 1)  { 
                                $summary =$summary."-----".getsubdivname($sdivid).",".$page.",".$Total;       $BrTotal=0;
                                }  }
                            $currentcell=$currentcell+1;
                        }
                         if($First == 1) {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':I'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $dateofmeasure=dt_display($List->date);
                            $scheduledescription =getscheduledescription($List->subdiv_id);
                            $len =strlen($scheduledescription);  
                            if($len == 431) { $pagebreak= $currentcell + 5;}
                            else if($page == 5) { $currentcell =$currentcell+2;}
                             //else if($page == 6) { $currentcell =$currentcell+6;}
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$currentcell,$dateofmeasure);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,$List->subdiv_name);
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':B'.$currentcell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                            $mergeCells='C'.$cell.':'.'H'.$cell;
                            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($mergeCells);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,$scheduledescription);
                            $objPHPExcel->getActiveSheet()->getStyle('C'.$currentcell)->getAlignment()->setWrapText(true);	
                            $objPHPExcel->getActiveSheet()->getStyle('C'.$currentcell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                            $First =2;
                            $currentcell=$currentcell+1; 
                            //$currentcell=$currentcell+1;
                         }
                     }
                     
                      // if($currentcell==181){$objPHPExcel->setActiveSheetIndex(0)->setBreak('A210', PHPExcel_Worksheet::BREAK_ROW);}
                     if($currentcell == $pagebreak)
                     {
                         if($page == 5){}else {
                        $CR=$Total;
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,"C/o");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,number_format($Total, 3, '.', ''));
                        //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"Page :". $page);
                        $summary =$summary."-----".$List->subdiv_name.",".$page.",".$CR;
                        $currentcell++;
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,"B/f");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,number_format($CR, 3, '.', ''));
                         $currentcell++;
                         $BrTotal=1;
                         }
                        $Total=0;$break =1;
                     }
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,$List->desc_work," ".$currentcell );
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,$List->measurement_no);
                         $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$currentcell,$List->measurement_l);
                         $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$currentcell,$List->measurement_b);
                         $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,$List->measurement_d);
                         $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,$List->measurement_contentarea);
                         $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$currentcell,$List->remarks);
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':I'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                     	 $currentcell=$currentcell+1;
    	                 $Total =$Total +$List->measurement_contentarea;
        	         $sdivid = $List->subdiv_id;
            	         $cell =$cell+11;
                	 $name++;
                 }
                   $currentcell=$currentcell+1;
                 cellColor('C'.$currentcell,'F5A9A9');
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,"Total");
                            cellColor('H'.$currentcell,'F5A9A9');
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,$Total);
                            $currentcell=$currentcell+1;$Total =0;$First=1;
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':I'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $mergeCells='G'.$currentcell.':'.'H'.$currentcell;
                            $objPHPExcel->getActiveSheet()->getStyle('G'.$currentcell)->getFont()->setBold(true)->setName('Arial')->setSize(11);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$currentcell,"");
                            if($page == 4){//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"Page :". $page);
                            $page =$page+1;}
                            else if($page == 5){//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$currentcell,"Page :". $page);
                            $currentcell=$currentcell+1; }
                            //else if($page == 6){  $pagebreak=210;$page =$page+1;$break=0;}
                            $currentcell=$currentcell+1;
                 
                 $currentcell=$currentcell+26;
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,"Summary");
                  $currentcell=$currentcell+1;
                 //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,$summary);
                   $currentcell=$currentcell+1;
                   
$subid='';$grandtotal =0;
$explodevalues = explode("-----", $summary);
for($init = 1; $init < count($explodevalues); $init++){
     $subvalues = explode(",", $explodevalues[$init]); 
        $currentcell=$currentcell+1;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$currentcell,$subvalues[0]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,"B/f from P".$subvalues[1]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,$subvalues[2]);
        $grandtotal = $grandtotal + $subvalues[2];
        $copages =$subvalues[1];
        if($subid == $subvalues[0] && $init !=7){
            $currentcell=$currentcell+1;    
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,"Total");    
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,$grandtotal);   
            $currentcell=$currentcell+1;    
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$currentcell,"C/o to P ".$copages."/MB79");   
            $grandtotal=0;
        }
     $subid =$subvalues[0];
     
}

//$objPHPExcel->getActiveSheet()->getStyle('A'.$currentcell.':I'.$currentcell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
//cellColor('C'.$currentcell,'F5A9A9');
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$currentcell,"Total");
//cellColor('H'.$currentcell,'F5A9A9');
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$currentcell,$Total);
$objPHPExcel->getActiveSheet()->getStyle('A7:A8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);			
$objPHPExcel->getActiveSheet()->getStyle('A7:A8')->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle('B7:B8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B7:B8')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('C7:C8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C7:C8')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
$objPHPExcel->getActiveSheet()->getStyle('D7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
$objPHPExcel->getActiveSheet()->getStyle('D8')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
$objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
$objPHPExcel->getActiveSheet()->getStyle('F8')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('G8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
$objPHPExcel->getActiveSheet()->getStyle('G8')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
$objPHPExcel->getActiveSheet()->getStyle('H8')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('I7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);			
$objPHPExcel->getActiveSheet()->getStyle('I7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('J7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);			
$objPHPExcel->getActiveSheet()->getStyle('J7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A7:I9')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("A7:I8")->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle("B9:C9")->getFont()->setBold(true)->setName('Arial')->setSize(12);
//$objPHPExcel->getActiveSheet()->getStyle('A10:I46')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
cellColor('A7:I8','C0C0C0');

function cellColor($cells,$color){
global $objPHPExcel;
$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,	
    'startcolor' => array(
         'rgb' => $color)));}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=EH-1V RAB 8.xls"');
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
