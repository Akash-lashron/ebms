<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
$msg = '';
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
function getMeasureshortnote()
{
        $shortnotesquery = "SELECT description FROM schdule WHERE subdiv_id = 7 AND sno = '1.6.a'";
        $sqlshortnotesquery = mysql_query($shortnotesquery);
        if($sqlshortnotesquery == true)
        {
            while($res = mysql_fetch_array($sqlshortnotesquery))
            {
                $result = $res['description'];
            }
            return $result;
        }
}
function getMeasurementDate()
{
        $measuredatequery = "SELECT * FROM mbookheader WHERE subdivid = 7";
        $sqlmeasuredatequery = mysql_query($measuredatequery);
        if($sqlmeasuredatequery == true)
        {
            while($res = mysql_fetch_array($sqlmeasuredatequery))
            {
                $result = $res['date'];
            }
            return $result;
        }
}
function getSubdivision()
{
        $subdividquery = "SELECT * FROM subdivision WHERE subdiv_id = 7";
        $subdividquerysql = mysql_query($subdividquery);
        if($subdividquerysql == true)
        {
            while($res = mysql_fetch_array($subdividquerysql))
            {
                echo $res['subdiv_name'];
            }
            
        }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>General</title>
        <link rel="stylesheet" href="script/font.css" />
        
    </head>
    <script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
    <script language="javascript" type="text/javascript" src="script/validfn.js"></script>
    <body bgcolor="FFFFFF" >
        <form name="form" method="post">
<?php
            $table = "<table width='875' border='1'  cellpadding='0' cellspacing='0' align='left'>";
$table =$table;
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold'>Name of work:</td>";
            $table = $table . "<td width='220' class='label' colspan='2'>" . $work_name . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold' valign='top'>Technical Sanction No.</td>";
            $table = $table . "<td class='label' colspan='2'> " . $tech_sanction . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold' valign='top'>Name of the contractor</td>";
            $table = $table . "<td class='label' colspan='2'>" . $name_contractor . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold' valign='top'>Agreement No.</td>";
            $table = $table . "<td class='label' colspan='2'>" . $agree_no . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold' valign='top'>Work Order No.</td>";
            $table = $table . "<td class='label' colspan='2'>" . $work_order_no . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold' valign='top'>Running Account bill No.</td>";
            $table = $table . "<td class='label' colspan='2'>" . $runn_acc_bill_no . "</td>";
            $table = $table . "</tr>";
            $table = $table . "</table>";
            $table = $table . "<table width='875' border='1'  BORDERCOLOR='gray' cellpadding='0' cellspacing='0' align='left'>";
            $table = $table . "<tr height='25'>";
            $table = $table . "<td width='81' rowspan='2' class='labelcenter'>Date of Measurment</td>";
            $table = $table . "<td width='48' rowspan='2' class='labelcenter'>BOQ</td>";
            $table = $table . "<td width='150' rowspan='2' class='labelcenter'>Description of work</td>";
            $table = $table . "<td width='40' rowspan='2' class='labelcenter'>Dia of Rod [mm]</td>";
            $table = $table . "<td width='40' rowspan='2' class='labelcenter'>Nos</td>";
            $table = $table . "<td width='60' rowspan='2' class='labelcenter'>Length in meters</td>";
            $table = $table . "<td colspan='8' width='' class='labelcenter'>Total Length in Meters</td>";
            $table = $table . "<td width='29' rowspan='2' class='labelcenter'>Remarks</td>";  //Remarks Field changed into Per.
            $table = $table . "</tr>";
            $table = $table . "<tr height='25'>";
            $table = $table . "<td width='60' class='labelcenter'>8</td>";
            $table = $table . "<td width='60' class='labelcenter'>10</td>";
            $table = $table . "<td width='65' class='labelcenter'>12</td>";
            $table = $table . "<td width='65' class='labelcenter'>16</td>";
            $table = $table . "<td width='65' class='labelcenter'>20</td>";
            $table = $table . "<td width='65' class='labelcenter'>25</td>";
            $table = $table . "<td width='65' class='labelcenter'>28</td>";            
            $table = $table . "<td width='65' class='labelcenter'>32</td>";   
            $table = $table . "</tr>";
            $table = $table . "</table>";
            ?>
            <?php echo $table; ?>

            <table width="875" border="1" cellpadding="0" cellspacing="0" align="left">
           <tr height="25">
                   <td width='81' class="label" align="center"><?php echo getMeasurementDate(); ?></td>
                   <td width='' class="label" align="center"><?php echo getSubdivision(); ?></td>
                   <td width='150' colspan="6"  class="label" align="center"><?php echo getMeasureshortnote(); ?></td>
                   <td colspan="7" width='81' class="label" align="center"></td>
                   
               </tr>
                <?php 
               $measurequery = "SELECT  * FROM mbookdetail WHERE subdivid = 7 AND remarks = 'Dia'";
               $sqlmeasurequery = mysql_query($measurequery);     
               if($sqlmeasurequery == true)
               {
                while ($List = mysql_fetch_object($sqlmeasurequery)) 
                {
                    $measurementdia=$List->measurement_dia;
                    $NOS=chop($List->measurement_no);
                    $LOM=chop($List->measurement_l);
                    $totaldia=trim($NOS*$LOM);
                    ?>
                
                    <tr height='25'>
                    <td width='81' class='labelcenter'></td>
                    <td width='48' class='labelcenter'></td>
                    <td width='150' class='labelcenter'><?php echo $List->descwork; ?></td>
                    <td width='40' class='labelcenter'><?php echo $List->measurement_dia; ?></td>
                    <td width='40' class='labelcenter'><?php echo $List->measurement_no; ?></td>
                    <td width='60' class='labelcenter'><?php echo $List->measurement_l; ?></td>
                    <?php
        if($measurementdia == 8){ ?><td width='60' class='labelcenter' bgcolor="grey"><?php echo $totaldia; ?></td><?php $totaldiaeight+=$totaldia; }
                else { ?><td width='60' class='labelcenter'></td> <?php }
        if($measurementdia == 10){ ?><td width='60' class='labelcenter' bgcolor="lightblue"><?php echo $totaldia; ?></td><?php $totaldiaten+=$totaldia; }    
                else { ?><td width='60' class='labelcenter'></td> <?php }           
        if($measurementdia == 12){ ?><td width='65' class='labelcenter' bgcolor="pink"><?php echo $totaldia; ?></td><?php $totaldiatwelve+=$totaldia; }                
                else { ?><td width='65' class='labelcenter'></td> <?php }         
        if($measurementdia == 16){ ?><td width='65' class='labelcenter'><?php echo $totaldia; ?></td><?php $totaldiasixteen+=$totaldia; }  
                else { ?><td width='65' class='labelcenter'></td> <?php }    
        if($measurementdia == 20){ ?><td width='65' class='labelcenter'><?php echo $totaldia; ?></td><?php $totaldiatwenty+=$totaldia; }      
                else { ?><td width='65' class='labelcenter'></td> <?php }      
        if($measurementdia == 25){ ?><td width='65' class='labelcenter'><?php echo $totaldia; ?></td><?php $totaldiatwentyfive+=$totaldia; }     
                else { ?><td width='65' class='labelcenter'></td> <?php }  
        if($measurementdia == 28){ ?><td width='65' class='labelcenter'><?php echo $totaldia; ?></td><?php $totaldiatwentyeight+=$totaldia; }     
                else { ?><td width='65' class='labelcenter'></td> <?php }   
        if($measurementdia == 32){ ?><td width='65' class='labelcenter'><?php echo $totaldia; ?></td><?php $totaldiathirtytwo+=$totaldia; }             
                else { ?><td width='65' class='labelcenter'></td> <?php }                
                  ?> 
                     <td width='29' class='labelcenter'><?php echo $List->remarks; ?></td>
                    </tr>
                <?php
                }?>
                <tr height='25'>
                    <td width='29' class='labelcenter'></td>
                    <td width='29' class='labelcenter'></td>
                    <td width='29' class='labelcenter'></td>
                    <td width='29' class='labelcenter'></td>
                    <td width='29' class='labelcenter'></td>
                    <td width='29' class='labelcenter'></td>
                    <td width='35' class='labelcenter' bgcolor="orange"><?php echo $totaldiaeight; ?></td>
                    <td width='60' class='labelcenter'bgcolor="orange"><?php echo $totaldiaten; ?></td>
                    <td width='65' class='labelcenter'bgcolor="orange"><?php echo $totaldiatwelve; ?></td>
                    <td width='65' class='labelcenter'bgcolor="orange"><?php echo $totaldiasixteen; ?></td>
                    <td width='65' class='labelcenter'bgcolor="orange"><?php echo $totaldiatwenty; ?></td>
                    <td width='65' class='labelcenter'bgcolor="orange"><?php echo $totaldiatwentyfive; ?></td>
                    <td width='65' class='labelcenter'bgcolor="orange"><?php echo $totaldiatwentyeight; ?></td>
                    <td width='65' class='labelcenter'bgcolor="orange"><?php echo $totaldiathirtytwo; ?></td>
                    <td width='65' class='labelcenter'></td>
                                   
                </tr>
                 
                </table>
            <?php
               }
               ?>
                
<?php /*  $No++; $pages++;
$subid='';$grandtotal =0;
$explodevalues = explode("@", $summary);
//echo $summary."<br>";
 $pagevalues = explode(",", $explodevalues[6]);
 sort($explodevalues); 
 for ($i=1; $i<count($explodevalues); $i++) {
      $subvalues = explode(",", $explodevalues[$i]); 
    for ($j=1; $j<count($explodevalues); $j++) {
        $subvalues1 = explode(",", $explodevalues[$j]); 
        if($subvalues[0] == $subvalues1[0])
        {
            if($subvalues[1] < $subvalues1[1])    {
                $temp = $explodevalues[$i];
            $explodevalues[$i]=$explodevalues[$j];
            $explodevalues[$j]=$temp;
            }
        }
    }
 }
// for($i=0;$i<count($explodevalues);$i++){
//   echo $explodevalues[$i]."<br>\n";
// }
//echo "<br>";
$is_first = TRUE;
echo $table;
echo "<table width='875' border='1'  BORDERCOLOR='gray' cellpadding='0' cellspacing='0' align='left'><tr height='25'>";
echo "<td colspan='9' class='label'>Summary</td></tr></table>";
 echo "<table width='875' border='1' BORDERCOLOR='gray' cellpadding='0' cellspacing='0' align='left'>";
 echo "<script type='text/javascript'> showpage('pageline0','". $pages ."','". $pagevalues[3] ."');</script>";
  echo "<div class='right'> <p><b>page: $pages </b></p>     </div>";
  
for($init = 1; $init < count($explodevalues); $init++){
     $subvalues = explode(",", $explodevalues[$init]); 
      if(trim($subid) != trim($subvalues[0])){
          if($subid !='') {
            echo "<tr height='25'>";
            echo  "<td width='129' class='label'></td>";
            echo  "<td width='383' class='label'>Total</td>";
            echo  "<td width='329'class='label'>".  $grandtotal."</td></tr>";
            mbookinsert($id,$sheetid,1,getsubdivid($subvalues[0]),1,"P".$pages."MB ".$subvalues[3],$grandtotal);
            echo "<tr height='25'>";
            echo  "<td width='129' class='label'></td>";
            echo  "<td width='383' class='label'></td>";
            echo  "<td width='329'class='label'>C/o to P ".$copages."/MB ".$subvalues[3]. "</td></tr>";
          $grandtotal=0; }
        }
        echo "<tr height='25'>";
        echo "<script type='text/javascript'> showpage('pageline". $init ."','". $pages ."','". $pagevalues[3] ."');52</script>";
        echo  "<td width='129' class='label'>".  $subvalues[0]."</td>";
        echo  "<td width='383' class='label'>B/f from P ".$subvalues[1]."/MB ".$subvalues[3] ."</td>";
        echo  "<td width='329'class='label'>".  $subvalues[2]."</td></tr>";
        $grandtotal = $grandtotal + $subvalues[2];
        $copages =$subvalues[1];
       
       
     $subid =$subvalues[0];
}
echo "<tr height='25'>";
        echo  "<td width='129' class='label'>Total</td>";
        echo  "<td width='383' class='label'></td>";
        echo  "<td width='329'class='label'>".  $grandtotal."</td></tr>";
         mbookinsert($id,$sheetid,1,getsubdivid($subid),1,"P".$pages."MB ".$subvalues[3],$grandtotal);
        echo "<tr height='25'>";
        echo  "<td width='129' class='label'></td>";
        echo  "<td width='383' class='label'></td>";
        echo  "<td width='329'class='label'>C/o to P ".$copages."/MB ".$subvalues[3]."</td></tr>";
        $grandtotal=0;
  echo "</table>";
 // $RowCount =0;
//  $BookPageNo =0;
// $updatecheckquery = "SELECT  	mb_page, endpage FROM mbookgenerate  WHERE active =1  AND endpage=100"; //echo $updatecheckquery."<br>";
// $updatechecksqlquery = mysql_query($updatecheckquery); 
// if ($updatechecksqlquery == false) {  } else {        $RowCount = mysql_num_rows($updatechecksqlquery);    }
// if ($updatechecksqlquery == true && $RowCount > 0) {    $checklists = mysql_fetch_object($updatechecksqlquery);   
//    $BookPageNo = ($checklists->endpage -  $checklists->mb_page) +1; 
//  } 
// if ($BookPageNo != 0) { $pages = $pages - $BookPageNo; }    
// $querystrs = "UPDATE  mbookgenerate  SET endpage ='$pages' WHERE active =1     AND sheet_id ='$sheetid'  AND mb_id = '$id'"; //echo $querystr;
// $sqlquerystrs = mysql_query($querystrs);
 $updateendpagequery = "UPDATE  mbookgenerate SET mb_endpage = 2 WHERE  mb_id = '$mb_id'";
$updateendpagesqlquery = mysql_query($updateendpagequery);
  ?><br>
   <?php 
//$subid='';$grandtotal =0; $kg=300;$kg1=400;
//$explodevalues = explode("@", $summary);
//echo "<table width='875' border='1' cellpadding='0' cellspacing='0' align='center'><tr height='25'>";
//echo "<td colspan='9' class='label'>CEMENT consumption FOR THE MONTH Mar'11</td></tr></table>";
// echo "<table width='875' border='1' cellpadding='0' cellspacing='0' align='center'>";
//   echo "<tr height='25'>";
//        echo  "<td width='41' class='label'>Item No.</td>";
//        echo  "<td width='41' class='label'>co from</td>";
//        echo  "<td width='41'class='label'>Reference</td>";
//        echo  "<td width='41' class='label'>Quantity</td>";
//        echo  "<td width='41' class='label'>Constant in Kg/Cum</td>";
//        echo  "<td width='41'class='label'>Constant in Kg/Cum</td></tr>";
//for($init = 1; $init < count($explodevalues); $init++){
//     $subvalues = explode(",", $explodevalues[$init]);        
//        //if($explodevalues[0] !='')
//        //{
//            echo "<tr height='25'>";
//        echo  "<td width='41' class='label'>".  $subvalues[0]."</td>";
//        echo  "<td width='41' class='label'>".$pages."/TMB</td>";
//        echo  "<td width='41' class='label'>PCC - M15 Grade-Pumpable</td>";
//        echo  "<td width='41'class='label'>".$subvalues[2]."</td>";
//                 echo  "<td width='41' class='label'>".$kg."</td>";
//        echo  "<td width='41'class='label'>". $subvalues[2] * $kg." </td></tr>";
//        $grandtotal = $grandtotal + $subvalues[2];
//        $copages =$subvalues[1];
//        if($subid == $subvalues[0]){
//        
//         echo "<tr height='25'>";
//        echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41' class='label'>Total</td>";
//        echo  "<td width='41'class='label'>".  $grandtotal."</td>";
//                   echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41'class='label'></td></tr>";
//        echo "<tr height='25'>";
//        echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41'class='label'>C/o to P ".$copages."/MB79</td>";
//            echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41'class='label'></td></tr>";
//        $grandtotal=0;
//        }
//        //}
//     $subid =$subvalues[0];
//}
//echo "<tr height='25'>";
//        echo  "<td width='41' class='label'>Total</td>";
//        echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41'class='label'>".  $grandtotal."</td>";
//         echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41'class='label'></td></tr>";
//            
//        echo "<tr height='25'>";
//        echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41'class='label'>C/o to P ".$copages."/MB79</td>";
//         echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41' class='label'></td>";
//        echo  "<td width='41'class='label'></td></tr>";
//        $grandtotal=0;
//  echo "</table>";

 */
?>     
        </form>
    </body>
</html>