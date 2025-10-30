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

if($_GET["newmbookno"] !='')
{
    $newmbookno =$_GET["newmbookno"]; $_SESSION["newmbookno"] =$_GET["newmbookno"];
}
 else {
 //   $newmbookno ='';
}
$sheetid=$_SESSION["sheet_id"];
$fromdate = ($_SESSION['fromdate']);
$todate = ($_SESSION['todate']);
$mbookno = $_SESSION["mb_no"];     
$mpage = $_SESSION["mb_page"];  
$query = "SELECT    sheet_id    , sheet_name    , work_order_no  ,work_name , tech_sanction    , name_contractor    , agree_no    , rbn
FROM   sheet WHERE sheet_id ='$sheetid' ";
//echo $query;
$sqlquery = mysql_query($query);
if ($sqlquery == true) {
    $List = mysql_fetch_object($sqlquery);
    $work_name = $List->work_name;    $tech_sanction = $List->tech_sanction;
    $name_contractor = $List->name_contractor;    $agree_no = $List->agree_no; $work_order_no = $List->work_order_no;  
    if($List->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$List->rbn + 1;} 
}
$mbookgeneratedelsql = "DELETE FROM mbookgenerate WHERE flag =1";
$result = dbQuery($mbookgeneratedelsql);
function mbookinsert ($mb_id, $sheet_id, $div_id, $subdivid,$mbdate, $mb_page, $mb_total)
{  
         $values = explode("MB", $mb_page); 
         echo $values[1];
   $querys="INSERT INTO mbookgenerate set sheetid='$sheet_id',divid='$div_id',subdivid='$subdivid',
       fromdate ='$fromdate',todate ='$todate' ,mbno='$values[1]',flag=1,
            mbgeneratedate=NOW(), mbnopages='$mb_page', mbtotal='$mb_total', active=1, userid=1";
 //  echo $querys."<br>";
   $sqlquerys = mysql_query($querys);
}
function func_getshortnote($sno)
{
 $sql_sel = "SELECT shortnotes FROM schdule WHERE sno = '$sno'";
	$result = dbQuery($sql_sel);
        while($res = mysql_fetch_array($result))
        {
            echo $res['shortnotes'];
        }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>General</title>
        <link rel="stylesheet" href="script/font.css" />
        
       <script type="text/javascript">
           
        </script>
        <style>
.right {
    position: absolute;
    right: 0px;
    width: 0px;
/*    background-color: #b0e0e6;*/
}
@media all {
	.page-break	{ display: none; }
}

@media print {
	.page-break	{ display: block; page-break-before: always; }
}
</style>
        <script type="text/javascript">
   $(document).ready(function(){
      $("#rightdiv").hide();
      
      }); 
    </script>
    </head>
    <script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
    <script language="javascript" type="text/javascript" src="script/validfn.js"></script>
    <script type="text/javascript" language="javascript">
        function pnr()
        {
            x = confirm("NOTE: Set Paper Size A4, and 0.5 inch Margin to TOP,BOTTOM,LEFT,RIGHT.\n\nReady to Print-out ?")
            if (x == true)
            {
                document.getElementById("btn_print").style.display = 'none';
                window.print();
            }
        }
    </script>
    
    <body bgcolor="FFFFFF" >
        <form name="form" method="post">
<!--            <a href="Generate.php">Back</a> -->
            <?php
            function getRowcount($text, $width=55) {
    $rc = 0;
    $line = explode("\n", $text);
    foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
    }
    return $rc;
}

//echo $_SESSION['newmbook'];
            $table = "<table width='875' border='0'  cellpadding='0' cellspacing='0' align='left'>";
$table =$table;
//            $table = $table . "<tr> <td width='33'>&nbsp;&nbsp;</td>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold'>Name of work:</td>";
            $table = $table . "<td width='220' class='label' colspan='2'>" . $work_name . "</td>";
            $table = $table . "</tr>";
//            $table = $table . "<tr>";
////$table =$table . "<td colspan='4'>&nbsp;</td>";
//            $table = $table . "</tr>";
            $table = $table . "<tr>";
//            $table = $table . "<td width='33'>&nbsp;&nbsp;</td>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold' valign='top'>Technical Sanction No.</td>";
            $table = $table . "<td class='label' colspan='2'> " . $tech_sanction . "</td>";
            $table = $table . "</tr>";
//            $table = $table . "<tr>";
////$table =$table . "<td colspan='4'>&nbsp;</td>";
//            $table = $table . "</tr>";
            $table = $table . "<tr>";
//            $table = $table . "<td width='33'>&nbsp;&nbsp;</td>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold' valign='top'>Name of the contractor</td>";
            $table = $table . "<td class='label' colspan='2'>" . $name_contractor . "</td>";
            $table = $table . "</tr>";
//            $table = $table . "<tr>";
////$table =$table . "<td colspan='4'>&nbsp;</td>";
//            $table = $table . "</tr>";
            $table = $table . "<tr>";
//            $table = $table . " <td width='33'>&nbsp;&nbsp;</td>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold' valign='top'>Agreement No.</td>";
            $table = $table . "<td class='label' colspan='2'>" . $agree_no . "</td>";
            $table = $table . "</tr>";
//            $table = $table . "<tr>";
////$table =$table . "<td colspan='4'>&nbsp;</td>";
//            $table = $table . "</tr>";
            $table = $table . "<tr>";
//            $table = $table . "<td width='33'>&nbsp;&nbsp;</td>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold' valign='top'>Work Order No.</td>";
            $table = $table . "<td class='label' colspan='2'>" . $work_order_no . "</td>";
            $table = $table . "</tr>";
//            $table = $table . "<tr>";
////$table =$table . "<td colspan='4'>&nbsp;</td>";
//            $table = $table . "</tr>";
            $table = $table . "<tr>";
//            $table = $table . "<td width='33'>&nbsp;&nbsp;</td>";
            $table = $table . "<td width='119' nowrap='nowrap' class='labelbold' valign='top'>Running Account bill No.</td>";
            $table = $table . "<td class='label' colspan='2'>" . $runn_acc_bill_no . "</td>";
            $table = $table . "</tr>";
//            $table = $table . "<tr>";
////$table =$table . "<td colspan='4'>&nbsp;</td>";
//            $table = $table . "</tr>";
            $table = $table . "</table>";
            
            $table = $table . "<table width='875' border='1'  BORDERCOLOR='gray' cellpadding='0' cellspacing='0' align='left'>";
            $table = $table . "<tr height='25'>";
            $table = $table . "<td width='81' rowspan='2' class='labelcenter'>Date of Measurment</td>";
            $table = $table . "<td width='48' rowspan='2' class='labelcenter'>Item No</td>";
            $table = $table . "<td width='390' rowspan='2' class='labelcenter'>Description of work</td>";
            $table = $table . "<td colspan='5' width='' class='labelcenter'>Measurements Upto Date</td>";
            $table = $table . "<td width='29' rowspan='2' class='labelcenter'>Per</td>";  //Remarks Field changed into Per.
            $table = $table . "</tr>";
            $table = $table . "<tr height='25'>";
            $table = $table . "<td width='35' class='labelcenter'>No.</td>";
            $table = $table . "<td width='65' class='labelcenter'>L.</td>";
            $table = $table . "<td width='65' class='labelcenter'>B.</td>";
            $table = $table . "<td width='65' class='labelcenter'>D.</td>";
            $table = $table . "<td width='65' class='labelcenter'>Contents or Area</td>";
            
            $table = $table . "</tr>";
            $table = $table . "</table>";
            ?>
            <?php echo $table; ?>
<input type="text"  name='newmbook' id='newmbook' value="100"/>
            <table width="875" border="1" cellpadding="0" cellspacing="0" align="left">
                <?php
               $currentline=8; $pageline=44; $pagecount=1; $No = 1;   $pages = $mpage;  $sdivid = '';$Total = 0; $First = 1;  $tablevisible = 0; $line=0; $summary='';
               $booknolist='';$pwin = 0;
                $query = "SELECT DATE_FORMAT( mbookheader.date , '%d/%m/%Y' ) AS date ,  mbookdetail.subdivid , subdivision.subdiv_name , 
mbookdetail.descwork, mbookdetail.measurement_no , mbookdetail.measurement_l , mbookdetail.measurement_b, 
mbookdetail.measurement_d , mbookdetail.measurement_contentarea , mbookdetail.remarks 
FROM mbookheader 
INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid) 
INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) ";//WHERE  mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate'";
                //echo $query;
               
                $sqlquery = mysql_query($query);
                if ($sqlquery == true) {
                    /*if($pages == 101)
                    {
                       $mb = "<script type='text/javascript'>document.write(getMBValue())</script>"; 
                       $mbookno = $mb;
                    }*/
                    $pageno = 1;
                    while ($List = mysql_fetch_object($sqlquery)) {
                        
                        if($pages == 101) { $pages=1;
                       
			 if($newmbookno =='') {
                            if( $_SESSION["newmbookno"]=='') {
                ?>
                <?php  //$abc = "<script>document.getElementByID('newmbook').value</script>";?>
                  <script type="text/javascript">
function test()
{
    alert("text")
    var pt = 030;
    
}
<?php //add($abc);?>
 <?php //echo "<script language='text/javascript'>function test() { alert('Just wanted to say $hello!'); }</script>"; ?>
</script>
<script type="text/javascript"> 
        
        
                            var value = prompt("Available Mbook Are\n<?php $mbookquery = "SELECT mballotmentid,mbno FROM  mbookallotment WHERE mbno != '$mbookno' ORDER BY mballotmentid ASC";
            $mbooksqlquery = mysql_query($mbookquery);
            $displaymbookno='';          
           	if ($mbooksqlquery == true ) {
                while($row = mysql_fetch_array($mbooksqlquery))   {   $displaymbookno =",".$row['mbno'].$displaymbookno;  }  }
            echo substr($displaymbookno, 1);     
?>\n", "");
                if(value == false){alert("You Must Choose One MBook.!"); window.location="MBook.php";	exit();				}
                if(value == null)	{window.location="Generate.php";	exit();			}
                var tmpmbook = parseInt(value);
                if(isNaN(tmpmbook)){		alert("You Must Enter Numeric Value.!"); window.location="MBook.php";	exit();		}
                //var pageUrl="http://localhost/bm/MBook.php?newmbookno="+value;
                //alert(window.location +",,"+pageUrl)
//                if (pageUrl != window.location) {
//                    window.history.replaceState({ path: pageUrl }, '', pageUrl); 
//                  }
                //window.location.href = "MBook.php?newmbookno="+value;
                //  window.history.pushState(null, null, "MBook.php?newmbookno="+value);
                  //window.history.replaceState(null, null, "MBook.php?newmbookno="+value);
                 test();
            
</script>
              

<?php

function add($param)
{
    echo "Adddddddddddddddddddddddddddd".$param;
}  
?>
<!--                <script type='text/javascript'>window.history.pushState(null, null, "MBook.php")</script>-->
                <?php
                            }	
                            else { echo "<script type='text/javascript'>alert('else'');</script>"; }
				/*echo "<script type='text/javascript'>window.location='MBook.php';</script>";*/
				
				//header("Location:MBook.php");
				
				 //if($_SESSION['newmbook']!="")
				$mbookno= $_SESSION["newmbookno"];
                        }
                        else {   if($_SESSION["newmbookno"] !='')  { $mbookno =$_SESSION["newmbookno"]; } else { $_SESSION["newmbookno"] ='';} }             
                    }
                    if ($tablevisible == 1) { $currentline =8;$pagecount++; $pageline =47;}
                        if ($pageno == 1) {
                            ?>
                            <tr height="25">
                                <td colspan="9" class="label" align="center">Earth work Excavation  for sump</td>
                            </tr>
                        <?php } ?>
                        <?php
                        //echo $sdivid ."--".$List->subdivid;
                        if ($sdivid != $List->subdivid) {
                            if ($First == 2) {
                                ?>
                                <tr height="25">
                                    <td class="label">&nbsp;</td>
                                    <td class="label" bgcolor="#00CCFF" width="48">Total</td>
                                    <td class="label"width=''>&nbsp;</td>
                                    <td class="label">&nbsp;</td>
                                    <td class="label">&nbsp;</td>
                                    <td class="label">&nbsp;</td>
                                    <td class="label"></td>
                                    <td class="label" bgcolor="#00CCFF"><?php echo $Total;
                                    $pagetotals ="P".$pages."MB ".$mbookno;
                                        $BR =$Total;$Total =0;$currentline++;
                                        if($BrTotal == 1) {
                                          $summary =$summary."@".getsubdivname($sdivid).",".$pages.",".$BR.",".$mbookno;
                                        }
                                        else { mbookinsert($id,$sheetid,1,$sdivid,1,$pagetotals,$BR);}
                                    ?></td>
                                    <td class="label"><?php //echo getsubdivname($sdivid)."-" .$pages ."-".$BR;?></td>
                                </tr>
                                <tr height="25">
                                    <td class="label"><?php $currentline++; ?></td>
                                    <td class="label"></td>
                                    <td class="label"width=''></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    
                                    <td colspan="3" class="label"><?php echo "C/o to P".$pages."/ MB".$mbookno; ?></td>
                                    </tr>
                                    
                                <?php //echo "<div class='page-break'></div>";
                                
                                        }
                                 
                            ?>


                            <?php $First = 2; ?>
                            <tr height="25">
                                <?php $currentline++; ?>
                                <td class="label" width="81"><?php echo $List->date; ?></td>
                                <td class="label" width='48'><?php
                                $len =strlen(getscheduledescription($List->subdivid));    if($len > 400) { $f=1;$currentline= $currentline +3; } else {$f=0; }
                                 echo " ".$List->subdiv_name."&nbsp;"; 
                                ?></td>
                                <td colspan="6" class="label"><?php echo func_getshortnote($List->subdiv_name); ?></td>
                                <td class="label"><?php //echo $List->subdivid;?></td>
                            </tr>
                        <?php } ?>
                
                        <?php
                        if ($tablevisible == 1) {
                             $summary =$summary."@".getsubdivname($sdivid).",".$pages.",".$CR.",".$mbookno;
                                 $tablevisible = 0;?>
                <div id ="rightdiv" class="right" style="visibility: hidden"><p><b>page:<?php echo $pages."--".$No; ?> </b></p> <a href="Generate.php">Back</a>  </br>   </div>
                  <?php $pages++; $mbpage++; $No++;?>
                
                           <?php echo $table;?> 

                            <table width="875" border="1" cellpadding="0" cellspacing="0" align="left">
                                <tr height="25">
                                    <td colspan="9" align="right" class="label"><?php echo "BR" . $CR;       
                                    $Total =0;    $BrTotal=1;$currentline++; 
                                    ?>
                                     
                                </tr>
                            <?php } ?>         
                            <tr height="25">
                                <td class="label"><?php $currentline++; ?><?php echo $List->date; ?></td>
                                <td class="label"></td>
                                <td class="label" width="390" style="word-break:break-all"><?php 
                                    //$len =strlen($List->descwork);    if(len > 30) { $currentline= $currentline +4; }
                                echo $List->descwork; ?></td>
                                <td class="label"  width='35' style="word-break:break-all"><?php echo $List->measurement_no; ?></td>
                                <td class="label" width='65'><?php 
                                    if ($List->measurement_l != 0) {
                                        echo $List->measurement_l;
                                    }
                                    ?></td>
                                <td class="label" width='65'><?php
                                    if ($List->measurement_b != 0) {
                                        echo $List->measurement_b;
                                    }
                                    ?></td>
                                <td class="label" width='65'><?php echo $List->measurement_d; ?></td>
                                <td class="label" width='65'><?php echo number_format($List->measurement_contentarea, 3, '.', ''); ?></td>
                                <td class="label" width='29'><?php echo $List->remarks; ?></td>
                            </tr>
                            <?php
                            if ($currentline == $pageline) {
                                $tablevisible = 1;
                                $CR = $Total;
                                ?>
                                <tr height="25">
                                    <td colspan="9" align="right" class="label"><?php echo "C/o " . $CR;  ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php //echo "currentline  ".$currentline++; ?>
                                    </td>
                                
                                    <?php $currentline++; ?>
                                </tr>
                                <tr height="25">
                                    <td colspan="9" class="label" align="right">
                                        <input type="text"  name='pageline<?php echo $line; ?>' id='pageline<?php echo $line; ?>' class="textboxdisplay" size="40"/>
                                        <?php $currentline++; ?>
                                    </td>
                                </tr>
                            <?php $line++; 
                            } ?>
                            <?php
                            $Total = $Total + number_format($List->measurement_contentarea, 3, '.', '');
                            $sdivid = $List->subdivid;
                            $pageno++;
                           
                        }?>
                          <tr height="25">
                                    <td class="label">&nbsp;</td>
                                    <td class="label" bgcolor="#00CCFF">Total &nbsp;</td>
                                    <td class="label"width="">&nbsp;</td>
                                    <td class="label">&nbsp;</td>
                                    <td class="label">&nbsp;</td>
                                    <td class="label">&nbsp;</td>
                                    <td class="label"></td>
                                    <td class="label" bgcolor="#00CCFF"><?php echo $Total;
                                    $pagetotals ="P".$pages."MB .$mbookno";
                                        $BR =$Total;$Total =0;$currentline++;
                                        if($BrTotal == 1) {
                                          $summary =$summary."@".getsubdivname($sdivid).",".$pages.",".$BR.",".$mbookno;
                                        }
                                        else { mbookinsert($id,$sheetid,1,$sdivid,1,$pagetotals,$BR);}
                                    ?></td>
                                    <td class="label"><?php //echo getsubdivname($sdivid)."-" .$pages ."-".$BR;?></td>
                                </tr>
                                <tr height="25">
                                    <td class="label"><?php $currentline++; ?></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    
                                    <td colspan="3" class="label"></td>
                                    </tr>
                                <tr height="25">
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td colspan="3" class="label"><?php echo "C/o to P".$pages."/MB".$mbookno; ?></td>
                                    </tr><tr height="25">
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td colspan="3" class="label"></td>
                                    </tr><tr height="25">
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td colspan="3" class="label"></td>
                                    </tr><tr height="25">
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td colspan="3" class="label"></td>
                                    </tr><tr height="25">
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td colspan="3" class="label"></td>
                                    </tr><tr height="25">
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td colspan="3" class="label"></td>
                                    </tr>
                                <tr height="25">
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td class="label"></td>
                                    <td colspan="3" class="label"></td>
                                    </tr>
                                  
                 <?php 
                    }
                    ?>
                </table>
                
<?php  $No++; $pages++;
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
?>     
        </form>
    </body>
</html>