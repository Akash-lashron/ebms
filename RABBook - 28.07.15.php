<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
include "library/common.php";
$msg = '';
$abstsheetid= 1;/*$_SESSION["abstsheetid"]; $abstmbno=$_SESSION["abstmbno"];$abstmbpage=$_SESSION["abstmbpage"];
if($abstsheetid =='') { echo "<script>alert('Please try again...') </script>"; header('Location: AbsGenerate.php'); }*/
$rbn=$_SESSION['RBN'];
$sheetid=$_SESSION['Sheetid'];

//echo $sheetid;exit;
/*if($_POST["Submit"] == "Submit") 
{
    $currentquantity = trim($_POST['currentquantity']);
    $mbookquery="INSERT INTO measurementbook  (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbtotal, flag, rbn, active, userid,abstquantity,abstmbookno,abstmbpage) 
                            SELECT  mbgeneratedate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbtotal, flag, rbn, active, userid,(abstquantity+mbtotal), $abstmbno,$abstmbpage FROM mbookgenerate WHERE flag =1 "; //AND STAFFID
 //  echo $mbookquery;
    $mbooksql = mysql_query($mbookquery);    
    $mbookgeneratedelsql = "DELETE FROM mbookgenerate WHERE flag =1"; //AND STAFFID
    $result = dbQuery($mbookgeneratedelsql);
    $sheetquery = "UPDATE sheet SET rbn = rbn+1 WHERE sheet_id ='$abstsheetid'";//AND STAFFID
    //echo $sheetquery;
    $sheetsql = dbQuery($sheetquery);
     header('Location: AbsGenerate.php');    
}*/
if($_POST["Back"] == "Back") 
{
     header('Location: RunningbillView.php');
}
$query = "SELECT    sheet_id    , sheet_name    , work_order_no  ,work_name , tech_sanction    , name_contractor    , agree_no    , rbn
FROM   sheet WHERE sheet_id ='$sheetid' ";
//echo $query;
$sqlquery = mysql_query($query);
if ($sqlquery == true) {
    $List = mysql_fetch_object($sqlquery);
    $work_name = $List->work_name;    $tech_sanction = $List->tech_sanction;   
    $name_contractor = $List->name_contractor;    $agree_no = $List->agree_no;
$work_order_no = $List->work_order_no;$runn_acc_bill_no=$rbn;    //if($List->rbn == 1){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn -1;} ;
    
//    $querys = "SELECT mb_id, sheet_id, mb_date, fromdate, todate, mb_no, mb_page, rbn, active FROM mbookgenerate WHERE active =1
//        AND sheet_id ='$abstsheetid'  AND mb_id in(1,2)";//'$id'";
////echo $querys;
//$sqlquerys = mysql_query($querys);
//$Lists = mysql_fetch_object($sqlquerys);
// $mb_page = $Lists->mb_page; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title></title>
        <link rel="stylesheet" href="font.css" />
        <script type="text/javascript">

   function showpage(textvalue,txtvalue)
   {
       //alert("text   " +textvalue +"  value   " +txtvalue)
       document.getElementById(textvalue).value = "B/f from P"+txtvalue +" TMS"; 
       
   }
   </script>
        <style>
.right {
    position: absolute;
    right: 0px;
    width: 300px;
    background-color: #b0e0e6;
}
</style>
    </head>

    <script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
    <script language="javascript" type="text/javascript" src="script/validfn.js"></script>
    <script type="text/javascript" language="javascript">

        function pnr()
        {
            x = confirm("NOTE: Set Paper Size A4, and 0.5 inch Margin to TOP,BOTTOM,LEFT,RIGHT.\n\nReady to Print-out ?")
            if (x == true)
            {
                document.getElementById("btn_print").style.display = 'none'
                window.print();
            }
        }

    </script>

    <body bgcolor="FFFFFF">
        <form name="form" method="post">
<?php
$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' >";
$table = $table . '<tr>';
$table = $table . "<td>Name of work:</td>";
$table = $table . "<td>" . $work_name . "</td>";
$table = $table . "<td>Name of the contractor</td>";
$table = $table . "<td>" . $name_contractor . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td>Technical Sanction No.</td>";
$table = $table . "<td>" . $tech_sanction . "</td>";
$table = $table . "<td>Agreement No.</td>";
$table = $table . "<td>" . $agree_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td>Work order No.</td>";
$table = $table . "<td>" . $work_order_no . "</td>";
$table = $table . "<td>Running Account bill No.</td>";
$table = $table . "<td>" . $runn_acc_bill_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td colspan ='4' align='center'>Abstract Cost for Engineering Hall-IV for the month Jan'11</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' id='mbookdetail'>";
$table = $table . "<tr>";
$table = $table . "<td  align='center' class='labelsmall' width='64' rowspan='2'>Boq</td>";
$table = $table . "<td  align='center' class='labelsmall' width='160' rowspan='2'>Description of work</td>";
$table = $table . "<td  align='center' class='labelsmall' width='70' rowspan='2'>Contents or Area</td>";
$table = $table . "<td  align='center' class='labelsmall' width='72' rowspan='2'>Rate<br />Rs.  P.</td>";
$table = $table . "<td  align='center' class='labelsmall' width='45' rowspan='2'>Per</td>";
$table = $table . "<td  align='center' class='labelsmall' width='100' rowspan='2'>Total value to Date<br />Rs.  P.</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='3'>Deduct previous Measurements</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='2'>Since Last Measurement</td>";
$table = $table . "<td  align='center' class='labelsmall' width='73' rowspan='2'>Remarks</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td width='100' align='center' class='labelsmall'>Page</td>";
$table = $table . "<td width='100' align='center' class='labelsmall'>Quantity</td>";
$table = $table . "<td  width='98'align='center' class='labelsmall'>Amount<br />Rs.  P.</td>";
$table = $table . "<td width='83' align='center' class='labelsmall'>Quantity</td>";
$table = $table . "<td  width='83' align='center' class='labelsmall'>Value<br />Rs.  P.</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
?>
            <?php echo $table; ?>
            <table width='100%'   border='1' cellpadding='0' cellspacing='0' align='left'id='mbookdetail'>
                <?php
               $currentline=16; $pageline=30; $pagecount=1;  $brdisplay=0; $pages = $mb_page;  $sdivid = '';$Total = 0; $First = 1;  $tablevisible = 0; $summary='';
               $TotalValue =0;$TotalDeduct=0;$TotalSinceLast=0;$linenumber=0; $Contents=0;$DeductpreviousAmt=0;$SinceLast=0;
                	/*$query = " SELECT DISTINCT mbookgenerate.mbgeneratedate,mbookgenerate.staffid,mbookgenerate.sheetid,
                    mbookgenerate.divid, mbookgenerate.subdivid,mbookgenerate.fromdate,mbookgenerate.todate,
                    mbookgenerate.mbno,mbookgenerate.mbnopages,mbookgenerate.mbpage,mbookgenerate.mbremainpage,
                    mbookgenerate.mbtotalpages,mbookgenerate.mbtotal,mbookgenerate.flag,mbookgenerate.rbn
                    FROM mbookgenerate ";*/
					$query = " SELECT DISTINCT measurementbook.measurementbookdate,measurementbook.staffid,measurementbook.sheetid,
                    measurementbook.divid, measurementbook.subdivid,measurementbook.fromdate,measurementbook.todate,
                    measurementbook.mbno,measurementbook.mbnopages,measurementbook.mbpage,measurementbook.mbremainpage,
                    measurementbook.mbtotalpages,measurementbook.mbtotal,measurementbook.flag,measurementbook.rbn
                    FROM measurementbook WHERE measurementbook.rbn ='$rbn' and measurementbook.sheetid='$sheetid' ";
               // echo $query;
                $sqlquery = mysql_query($query);
                if ($sqlquery == true) {
                    $pageno = 1;
                    while ($List = mysql_fetch_object($sqlquery)) {
                    if ($tablevisible == 1) { $currentline =11;$pagecount++; $pageline =47;}
                        ?>
                        <?php
                        //echo $sdivid ."--".$List->subdiv_id;
                        if ($sdivid != $List->subdivid) {
                            if ($First == 2) {
                                ?>
                
                <tr>
  <td  align='left' width='60'   class='labelboldright '>&nbsp;</td>
  <td  align='left' width='160'   class='labelboldright' bgcolor="#00CCFF">Total</td>
  <td  align='right'  width='40' class='labelboldright'><?php echo $lastMeasurement+$previousquantity; $schduledetails =getschduledetails($abstsheetid,$sdivid);       $schduledt = explode("*", $schduledetails); ?></td>
  <td  align='right' width='75'  class='labelboldright'><?php echo $schduledt[0];?></td>
  <td  align='left' width='50' class='labelboldright'><?php echo $schduledt[1];?></td>
  <td  align='right' width='90' class='labelboldright'><?php echo number_format((($lastMeasurement+$previousquantity)* $schduledt[0]), 2, '.', '');   $Contents=$Contents+number_format((($lastMeasurement+$previousquantity)* $schduledt[0]), 2, '.', '');?></td>
  <td  align='left' width='80' class='labelboldright'><?php  echo $previouspage;$BR =($lastMeasurement+$previousquantity);$Total =0;$currentline++;
  $TotalValue =$TotalValue +$BR;
                                        //if($BrTotal == 1) {  $summary =$summary."-----".getsubdivname($sdivid).",".$pages.",".$BR;  }
                                       //echo $List->mb_page;?> </td>
  <td  align='right' width='100' class='labelboldright'><?php if($runn_acc_bill_no  > 1) { echo $previousquantity; } ?></td>
  <td  align='right' width='100' class='labelboldright'><?php if($runn_acc_bill_no  > 1) { echo $previousquantity * $schduledt[0]; $DeductpreviousAmt = $DeductpreviousAmt+($previousquantity * $schduledt[0]); }?></td>
  <td  align='right' width='80' class='labelboldright'> <?php echo $currentqty;?><?php //echo number_format($mbtotal, 3, '.', '');?></td>
  <td  align='right' width='80' class='labelboldright'><?php echo number_format(($lastMeasurement * $schduledt[0]), 2, '.', ''); ?><?php $SinceLast=$SinceLast + number_format(($lastMeasurement * $schduledt[0]), 3, '.', '');?></td>
  <td  align='left' width='80' class='labelboldright'><?php //echo $SinceLast;?></td>
</tr>
                 <?php if ($tablevisible == 1) { ?>
<?php $currentline++; ?>
<tr>
  <td  align='left' width='60'   class='labelsmall'>&nbsp;</td>
  <td  align='left' width='120'   class='labelsmall'><?php echo "C/0 ";?></td>
  <td  align='right'  width='40' class='labelsmall'><?php echo  number_format($TotalValue, 2, '.', '');  ?></td>
  <td  align='left' width='75'  class='labelsmall'>&nbsp;</td>
  <td  align='left' width='50' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='90' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='100' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='100' class='labelsmall'><?php echo "0.00"; ?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='80' class='labelsmall'><?php echo  number_format($TotalDeduct, 2, '.', '');  ?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
</tr> 
                <tr><td colspan="12" class="labelboldright">&nbsp;  </td>    </tr>
                <tr><td colspan="12" class="labelboldright">&nbsp;  </td>    </tr>
                <tr><td colspan="12" class="labelboldright">&nbsp;  </td>    </tr>
                  <?php echo $table;$brdisplay=1;?> 
                
                 <?php  }?>
    <?php 
    
                                        } ?>
    <?php $First = 2; ?>
                <?php if ($brdisplay == 1) { $brdisplay=0;?>
                  <table width='100%'   border='1' cellpadding='0' cellspacing='0' align='left' id='mbookdetail'>
                <tr>
                                    <td colspan="12" class="labelboldright"><?php echo "B/f " . $TotalValue;       
                                    $Total =0;    $BrTotal=1;$currentline++; 
                                    ?>
                                     
                                </tr>
                <?php } ?>
                            <tr>
                                <?php $currentline++; ?>
                                <td  align='center' width='61'  class='labelsmall'><?php echo getsubdivname($List->subdivid);
                             //   $len =strlen(getscheduledescription($List->subdiv_id));    if($len > 400) { $f=1;$currentline= $currentline +2; } else {$f=0; }
                                 //echo getsubdivname($List->subdivid); 
                                ?></td>
  <td colspan="11"  align='left' class='labelsmall'><?php echo getscheduledescription($List->subdivid); ?></td>
                                
                                
                            </tr>
                        <?php } ?>
                
                        <?php
                        if ($tablevisible == 1) {
                            // $summary =$summary."-----".getsubdivname($sdivid).",".$pages.",".$CR;
                                 $tablevisible = 0;?>
<!--                  <div class="right"> <p><b>page:<?php //echo $pages; ?> </b></p>     </div>-->
                  <?php $pages++; $mbpage++; $currentline++;?>
                           <?php //echo $table;?> 
  <?php $currentline++; ?>
                            <table width='100%'  border='1' cellpadding='0' cellspacing='0' align='left'id='mbookdetail'>
                                
                            <?php } ?>      
                                
                                <?php if($runn_acc_bill_no  > 1) { 
                                     $runbillno =$runn_acc_bill_no-1;
                                    $mbookrbnquery="SELECT measurementbookid, measurementbookdate, staffid, sheetid, 
                                            divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, abstmbookno,abstmbpage,abstquantity,
                                            mbtotalpages, mbtotal, flag, rbn, active, userid FROM measurementbook WHERE sheetid ='$List->sheetid' AND subdivid =$List->subdivid AND rbn ='$runbillno'";
                                 //   echo $mbookrbnquery;
                                     $mbookrbnsql = mysql_query($mbookrbnquery);
                                        while ($RBNList = mysql_fetch_object($mbookrbnsql)) {
                                    ?>
                                <tr>
  <td  align='left' width='60'   class='labelsmall'></td>
  <td  align='left' width='160'   class='labelsmall'><?php echo "Qty Vide Page".$RBNList->abstmbpage."MB".$RBNList->abstmbookno; ?><?php $currentline++; $previouspage="MB".$RBNList->abstmbookno."Page".$RBNList->abstmbpage;  ?></td>
  <td  align='right'  width='40' class='labelsmall'><?php  if($runbillno == 1){echo $RBNList->mbtotal;} else { echo $RBNList->abstquantity; } ?></td>
  <td  align='left' width='75'  class='labelsmall'> <?php  if($runbillno == 1){$previousquantity= $RBNList->mbtotal;} else { $previousquantity= $RBNList->abstquantity; }  ?>    </td>
  <td  align='left' width='50' class='labelsmall'></td>
  <td  align='left' width='90' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='100' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='100' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
</tr>
                                        <?php } } ?>     
                                <tr>
  <td  align='left' width='60'   class='labelsmall'></td>
  <td  align='left' width='160'   class='labelsmall'><?php echo "Qty Vide ".$List->mbnopages; ?><?php $currentline++; ?></td>
  <td  align='right'  width='40' class='labelsmall'><?php $currentqty =$List->mbtotal;echo $List->mbtotal; ?></td>
  <td  align='left' width='75'  class='labelsmall'><?php $lastMeasurement = $List->mbtotal;?></td>
  <td  align='left' width='50' class='labelsmall'></td>
  <td  align='left' width='90' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='100' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='100' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='80' class='labelsmall'></td>
</tr>             <?php
                            if ($currentline == $pageline) {
                                $tablevisible = 1;
                                $CR = $Total;
                                ?>
                              
                            <?php } ?>
                            <?php
                            $Total = $Total + number_format($List->mbtotal, 3, '.', '');
                            $T =$T + number_format((($lastMeasurement+$previousquantity)) ,  3, '.', '');
                            // = $SinceLast +($lastMeasurement * $schduledt[0]);
                            $sdivid = $List->subdivid;
                            $mbtotal = $List->mbtotal;
                            $pageno++; $linenumber++;
                            $DeductpreviousQty =$DeductpreviousQty+$previousquantity;
                           //  =$DeductpreviousAmt+($previousquantity * $schduledt[0]);
                        }
                    }
                    ?>
                                  <tr>
  <td  align='left' width='61'   class='labelboldright '><?php  $total =$mbtotal+$previousquantity;?></td>
  <td  align='left' width='160'   class='labelboldright'bgcolor="#00CCFF">Total</td>
  <td  align='right'  width='40' class='labelboldright' ><?php echo $total; $schduledetails =getschduledetails($abstsheetid,$sdivid);                                    $schduledt = explode("*", $schduledetails);                                    ?></td>
  <td  align='right' width='75'  class='labelboldright'><?php echo $schduledt[0];?></td>
  <td  align='left' width='50' class='labelboldright'><?php echo $schduledt[1];?></td>
  <td  align='right' width='90' class='labelsmall'><?php echo  number_format($total * $schduledt[0], 2, '.', '');  $toalAmts=number_format($total * $schduledt[0], 2, '.', ''); ?></td>
  <td  align='left' width='100' class='labelsmall'><?php echo $previouspage;?></td>
  <td  align='right' width='100'  class='labelsmall'><?php echo $previousquantity;?></td>
  <td  align='right' width='100' class='labelsmall'><?php echo  number_format($previousquantity * $schduledt[0], 2, '.', '');  $prevAmt= number_format($previousquantity * $schduledt[0], 2, '.', '');?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='80' class='labelsmall'><?php echo  number_format($mbtotal * $schduledt[0], 2, '.', '');  $tot=number_format($mbtotal * $schduledt[0], 2, '.', '');?></td>
  <td  align='left' width='80' class='labelsmall'><?php   $Contents =$Contents + $toalAmts;?></td>
</tr>
                 <tr>
  <td  align='left' width='60'   class='labelsmall'><?php $SinceLast =$SinceLast + $tot;?></td>
  <td  align='left' width='160'   class='labelsmall' bgcolor="#F781F3"><i><?php echo "TOTAL COST (Rs.)";?></i></td>
  <td  align='right'  width='40' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='75'  class='labelsmall'>&nbsp;</td>
  <td  align='left' width='50' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='90' class='labelsmall' bgcolor="#F781F3"><?php  $TotalValueCost= number_format($Contents, 2, '.', ''); echo number_format($Contents, 2, '.', '');  ?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='100' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='100' class='labelsmall'><?php $DeductpreviousAmt =$DeductpreviousAmt+$prevAmt; echo number_format($DeductpreviousAmt, 2, '.', '');  $TotalValueDeduct= number_format($DeductpreviousAmt, 2, '.', '');?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='80' class='labelsmall' bgcolor="#F781F3"><?php $TotalDeductCost =number_format($SinceLast, 2, '.', ''); echo  number_format($SinceLast, 2, '.', '');  ?></td>
  <td  align='left' width='80' class='labelsmall'>  <?php //echo $TotalValueDeduct;?></td>
</tr>
                                 <tr>'
  <td  align='left' width='60'   class='labelsmall'>&nbsp;</td>
  <td  align='left' width='160'   class='labelsmall'  bgcolor="#F5A9A9"><i><?php echo "Less Overall Rebate  = 0.5%";?></i></td>
  <td  align='right'  width='40' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='75'  class='labelsmall'>&nbsp;</td>
  <td  align='left' width='50' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='90' class='labelsmall' bgcolor="#F5A9A9"><?php $LessOverallRebateValue =number_format($Contents, 2, '.', '') * 0.005; echo  $LessOverallRebateValue;  ?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='100' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='100' class='labelsmall'><?php  $LessOverallDeduct = number_format($DeductpreviousAmt, 2, '.', '') * 0.005;  echo  $LessOverallDeduct; ?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='80' class='labelsmall' bgcolor="#F5A9A9"><?php  $LessOverallRebateDeduct = number_format($SinceLast, 2, '.', '') * 0.005;  echo  $LessOverallRebateDeduct; ?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
</tr>
                                 <tr>
  <td  align='left' width='60'   class='labelsmall'>&nbsp;</td>
  <td  align='left' width='160'   class='labelsmall'  bgcolor="#A9D0F5"><i><?php echo "Gross Amount (Rs.)";?></i></td>
  <td  align='right'  width='40' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='75'  class='labelsmall'>&nbsp;</td>
  <td  align='left' width='50' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='90' class='labelsmall'  bgcolor="#A9D0F5"><?php echo  number_format($TotalValueCost - $LessOverallRebateValue, 2, '.', '');  ?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='left' width='100' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='100' class='labelsmall'><?php echo  number_format($TotalValueDeduct - $LessOverallDeduct, 2, '.', '');?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
  <td  align='right' width='80' class='labelsmall'  bgcolor="#A9D0F5"><?php echo  number_format($TotalDeductCost - $LessOverallRebateValue, 2, '.', '');  ?></td>
  <td  align='left' width='80' class='labelsmall'>&nbsp;</td>
</tr>
<!--                                <tr><td colspan="15" align="center"><input type="submit" name="btn_excel" id="btn_excel" value="Excel" /></td></tr>-->
                </table>
                      <br/>
                      <table align="center">
      <tr>
          <td  colspan="6">
          
                   <input type="hidden" class="text" name="submit" value="true" />
            <input type="submit" class="btn" data-type="submit" value="Submit" name="Submit" id="Submit"   />&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" class="btn"   data-type="submit" value="Back" name="Back" id="Back"   />
                  </td>
<!--  style="display: none;"-->
     </tr></table>
                 
        </form>
    </body>
</html>