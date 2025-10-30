<?php
session_start();ob_start();
require_once('tcpdf_include.php');
$stmt=$_SESSION['report_values']; //Session Variables
$Stmt_PartB=$_SESSION['report_values_C'];
$PartA_amt=$_SESSION['report_header4'][0];
$Addition_amt=$_SESSION['report_header5'][0];
$PartA_conti_amt=$_SESSION['report_header6'][0];
$PartB_amt=$_SESSION['report_header7'][0];
$Service_taxamt=$_SESSION['report_header9'][0];
$Total_amt=$_SESSION['report_header8'][0];
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR); // set document information
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Tender Estimation');
$pdf->SetSubject('Schedule of Quantities');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN)); // set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); // set default monospaced font
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); // set margins
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);// set auto page breaks
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);// set image scale factor
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) { // set some language-dependent strings (optional)
require_once(dirname(__FILE__).'/lang/eng.php');
$pdf->setLanguageArray($l);}
//$pdf->setFontSubsetting(false);
$pdf->SetFont('helvetica', '', 25, '', false);
$pdf->AddPage('L','A3');
$pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(255, 255, 200), 'strokeColor'=>array(255, 128, 128)));
$pdf->SetFont('helvetica', 'B', 25);
$pdf->Cell(0, 5, 'Schedule of Quantities (Tender Estimate)', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(35, 5, 'Tender No.'. ' ' . ' ' . ' '. ' ' . ' '  . ' ' . ' ' . ' ' . ' '.':' . ' ' . ' ' . $_SESSION['report_header'][0]);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Cell(35, 5, 'Name of Work'.  ' ' . ' '  . ' ' . ' '. ':' . ' ' . ' ' .  $_SESSION['report_header1'][0]);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Cell(35, 5, 'Part A Details');
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 15);
$header = array('S.No','Description of Item','Quantity Unit','RATE','Erection Charges','RATE','Unit','Amount');
$pdf->SetFillColor(255, 0, 0);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(128, 0, 0);
//$pdf->setCellHeightRatio(3);
$pdf->SetLineWidth(2);
$w = array(13,100,40,40,50,45,50,45);
$num_headers = count($header);
for($i = 0; $i < $num_headers; $i++) {
$pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);}
$pdf->Ln();
$pdf->SetFillColor(224, 235, 255);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 20, '', 'false');
$fill = 0;
$total=0;
$row=0;
//$pdf->setCellHeightRatio(3);
/*foreach($stmt as $row) {
$total=$total+$row[8];
$pdf->SetFont('helvetica',true);
$pdf->SetFontSize(20,true);
//$pdf->setCellHeightRatio(3);
$pdf->MultiCell($w[0],8,$row[0],1,'C',$fill,0,'','',true,0,false,true,15,'M',true);
$pdf->MultiCell($w[1],8,$row[1],1,'LR',$fill,0,'','',true,0,false,true,15,'M',true);
$pdf->MultiCell($w[2],5,$row[2]. ' ' .$row[3],1,'C',$fill,0,'','',true,0,false,true,15,'M',true);
$pdf->MultiCell($w[3],5,$row[4],1,'R',$fill,0,'','',true,0,false,true,15,'M',true);
$pdf->MultiCell($w[4],8,$row[5],1,'R',$fill,0,'','',true,0,false,true,15,'M',true);
$pdf->MultiCell($w[5],5,$row[6],1,'R',$fill,0,'','',true,0,false,true,15,'M',true);
$pdf->MultiCell($w[6],5,$row[7],1,'C',$fill,0,'','',true,0,false,true,15,'M',true);
$pdf->MultiCell($w[7],5,$row[8],1,'R',$fill,0,'','',true,0,false,true,15,'M',true);
$pdf->Ln();
$fill=!$fill;}
$pdf->MultiCell($w[0],4,'',1,'C',$fill,0,'','',true,0,false,true,10,'M',true);
$pdf->MultiCell($w[1],4,'',1,'C',$fill,0,'','',true,0,false,true,10,'M',true);
$pdf->MultiCell($w[2],5,'',1,'C',$fill,0,'','',true,0,false,true,10,'M',true);
$pdf->MultiCell($w[3],4,'',1,'C',$fill,0,'','',true,0,false,true,10,'M',true);
$pdf->MultiCell($w[4],4,'',1,'C',$fill,0,'','',true,0,false,true,10,'M',true);
$pdf->MultiCell($w[5],4,'',1,'C',$fill,0,'','',true,0,false,true,10,'M',true);
$pdf->SetFont('helvetica',true);
$pdf->SetFontSize(20,true);
$pdf->MultiCell($w[6],4,'Total',1,'C',$fill,0,'','',true,0,false,true,10,'M',true);
$pdf->MultiCell($w[7],4,$total,1,'R',$fill,0,'','',true,0,false,true,10,'M',true);
$pdf->Ln();
$pdf->Ln(6);*/
//$d = array(40,40);
$header_list = array('Part A Amount(a)'. ' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.'-' .  ' ' .' ' .' ' .' ' .' ' .round($PartA_amt),
					 'Additional Item Amount (b)'. ' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.'-' .  ' ' .' ' .' ' .' ' .' ' .round($Addition_amt),'Contingency Amount (c)'. ' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' '.' '.' ' .' ' .' ' .' '.' '.' ' .' ' .' ' .' '.'-' .  ' ' .' ' .' ' .' ' .' ' .round($PartA_conti_amt),'Service Tax Amount (d)'. ' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' '.' '.' '.' '.' ' .'-' .  ' ' .' ' .' ' .' ' .' ' .round($Service_taxamt),'Part A Total (a+b+c+d)'. ' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' '.' '.' '.'-' .  ' ' .' ' .' ' .' ' .' ' .round($PartA_amt+$Addition_amt+$PartA_conti_amt+$Service_taxamt),'Part B Amount'. ' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' ' .' ' .' '.' ' .' ' .' '.' '.'-' .  ' ' .' ' .' ' .' ' .' ' .round($PartB_amt),'Total Amount (Part A Total + Part B )'. ' ' .' ' .' ' .' ' .' ' .' ' .' ' .' ' .' '.'-' .  ' ' .' ' .' ' .' ' .' ' .round($PartA_amt+$Addition_amt+$PartA_conti_amt+$Service_taxamt+$PartB_amt));
for($h=0;$h<sizeof($header_list);$h++)
{
	$pdf->Cell(0,5,$header_list[$h], 0, 1, 'LR');
	$pdf->Ln(5);
}
$pdf->MultiCell(array_sum($w),0, '', 'T');
$pdf->Output('Export_Tender.pdf', 'I'); //Output Generated