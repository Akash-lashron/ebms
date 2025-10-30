<?php
require_once 'library/config.php';
require_once 'library/functions.php';
include "library/common.php";
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
function moneyFormat($amt)
{
	/* 
	1. IMPORTANT NOTES: Plz use this result of this funtion for only print output in following format. 
	2. DONT USE addtion, subtraction, multiplication and division function using this result. 
	3. Because it gives the result in string - data type. If we use this output will be wrong.
	*/
	$amount = number_format($amt, 2, '.', '');
	$explodeRes = explode(".",$amount);
	$ratePart = $explodeRes[0];
	$decimalPart = $explodeRes[1];
	$length = strlen($ratePart);
	if($length>3)
	{
		$getArray = str_split($ratePart);
		$count = count($getArray);
		if(($count%2) == 0)
		{
			$i = 0;
			while($i<$count)
			{
				if($i == ($count-3))
				{
					$result .= $getArray[$i].$getArray[$i+1].$getArray[$i+2];
					$i = $count-1;
				}
				else if($i == 0)
				{
					$result .= $getArray[$i].",";
				}
				else
				{
					$result .= $getArray[$i].$getArray[$i+1].",";
					$i++;
				}
				$i++;
			}
		}
		else
		{
			$i = 0;
			while($i<$count)
			{
				if($i == ($count-3))
				{
					$result .= $getArray[$i].$getArray[$i+1].$getArray[$i+2];
					$i = $count-1;
				}
				else
				{
					$result .= $getArray[$i].$getArray[$i+1].",";
					$i++;
				}
				$i++;
			}
		}
		$result = $result.".".$decimalPart;
	}
	else
	{
		$result = $amount;
	}
	return $result;
}

function getEncryptedParam($argument,$Page){
	$parameter 	= $argument;
	$salt 		= "Dae@Nrb@Frfcf@Ebms-Lashron@Kalpakkam";
	$hashed 	= md5($salt.$parameter);
	$retUrl 	= $Page.".php?id=".$parameter."&hash=".$hashed;
	return $retUrl;
}

$sheetid = $_SESSION['Sheetid'];
//$schdulesql ="SELECT      DISTINCT sno,sch_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno <> '' AND subdiv_id !=0 ";
$schdulesql ="SELECT DISTINCT sno,sch_id, subdiv_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no, measure_type FROM  schdule   where sheet_id= '$sheetid' AND  sno != '0' ";
$schdule=mysql_query($schdulesql);
//echo $schdulesql;
$rebate_perc_sql = "SELECT rebate_percent FROM sheet WHERE sheet_id = '$sheetid' AND active = 1";
$rebate_perc_query = mysql_query($rebate_perc_sql);
$rebate_percent = mysql_result($rebate_perc_query,0,'rebate_percent');
 $RowCount =0;
 if(isset($_POST['submit']))
 {
     header('Location: ViewAgreementSheet.php');
 }
?>
<?php require_once "Header.html"; ?>
<style>

.container{
    width:100%;
    border-collapse: collapse;
    }
.table-row{  
     display:table-row;
     text-align: left;
}
.col{
display:table-cell;
border: 1px solid #CCC;
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:9pt;
vertical-align:middle;
padding:3px;
color:#00008b;
/*background:#f5f5f5;*/
}
.colhead
{
display:table-cell;
border: 1px solid #CCC;
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:9pt;
vertical-align:middle;
padding:3px;
color:#025FA4;
}
sub,
sup {
font-size: 75%;
line-height: 0;
position: relative;
vertical-align: baseline;
}
sup {
top: -0.5em;
}
sub {
bottom: -0.25em;
}
.general{
	padding:1px;
	background-color:#057C79;
	border-radius:9px;
}
.steel{
	padding:1px;
	background-color:#F95774;
	border-radius:9px;
}
.st-steel{
	padding:1px;
	background-color:#53A9FF;
	border-radius:9px;
}

.GI{
	padding:5px;
	background-color:#057C79;
	font-weight:bold;
	color:#FFFFFF;
}
.SI{
	padding:5px;
	background-color:#F95774;
}
.SSI{
	padding:5px;
	background-color:#53A9FF;
}
/*.colfont{
  font-family:Constantia; 
  font-size: 11pt;
}*/
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">  
                            <div class="title">View Agreement Sheet</div>
                <div class="container_12">  
                    <div class="grid_12" align="center"> 
                        <blockquote class="bq1" id="bq1" style="overflow:scroll; position:relative">
                            <div class="container">
                                <?php 
                                if ($schdule == false) {  } else {        $RowCount = mysql_num_rows($schdule);    }
                            if ($schdule == true && $RowCount > 0) {
                                  ?>
								  <div align="left" style="width:90%; font-family:Verdana, Arial, Helvetica, sans-serif; color:#007BB7; font-size:11px; font-weight:bold;">
								  	<!--<span class="general">&nbsp;&nbsp;&nbsp;&nbsp;</span> General &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
									<span class="steel">&nbsp;&nbsp;&nbsp;&nbsp;</span> Steel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<span class="st-steel">&nbsp;&nbsp;&nbsp;&nbsp;</span> Structural Steel 
								  </div>
							<table width="90%" class="table1 table2">
								<tr class="heading">
									<th class="colhead" nowrap="nowrap">Item No.</th>
									<th class="colhead">Description</th>
									<th class="colhead">Total Quantity</th>
									<th class="colhead">Unit</th>
									<th class="colhead">Rate <i class="fa fa-inr" style="padding-top:7px;"></i></th>
									<th class="colhead" nowrap="nowrap">Total Amt <i class="fa fa-inr" style="padding-top:7px;"></i></th>
									<th class="colhead">Action</th>
								</tr>
                               
                                <?php 
								$overall_total_amt = 0;
								while ($List = mysql_fetch_object($schdule)) 
								{ 
										
										if(trim($List->per) == "cum"){ $unit = $List->per; /*$unit = "m<sup>3</sup>";*/  }
										else if(trim($List->per) == "sqm"){ $unit = $List->per; /*$unit = "m<sup>2</sup>";*/ }
										else { $unit = $List->per; }
										$UnitFactor = findNumericFromString($unit);
										if($UnitFactor == "" or $UnitFactor == 0){
											$UnitFactor = 1;
										}
                                        $total_amt = round(($List->rate * $List->total_quantity / $UnitFactor),2); 
										
										$rate = moneyFormat($List->rate);
										$total = moneyFormat($total_amt);
                                        if($List->subdiv_id == 0){ $List->rate = "";$List->total_quantity = "";$total_amt = ""; }
										$mtype = $List->measure_type;
										if($mtype == "s"){
											$type_class = "SI";
										}elseif($mtype == "st"){
											$type_class = "SSI";
										}else{
											$type_class = "";
										}
                                  ?>
									<tr>
										<td class="col" align="center" nowrap="nowrap">
										<span class="<?php echo $type_class; ?>">
										<?php if($prev_item_no != $List->sno) { echo $List->sno; } ?>
										</span>
										</td>
										<td class="col labelprint"><?php echo $List->description; ?></td>
										<td class="col" align="right"><?php echo $List->total_quantity; ?>&nbsp;</td>
										<td class="col">&nbsp;<?php echo $unit; ?>&nbsp;</td>
										<td class="col" align="right"><?php if($List->rate != 0){ echo $rate; } ?>&nbsp;</td>
										<td class="col" align="right"><?php if($total_amt != 0){ echo $total; } ?>&nbsp;</td>
										<td class="col" align="center">
										<?php $editUrl = getEncryptedParam($List->sch_id,'SoqEdit'); ?>
										<a href="<?php echo $editUrl; ?>"><i class='fa fa-edit' style='font-size:14px; cursor:pointer; color:#0285C4;'></i></a>
										</td>
									</tr>
								  
                                <?php 
									$overall_total_amt = $overall_total_amt + $total_amt; 
									$prev_item_no = $List->sno;
								} 
									$rebate_amount = $overall_total_amt*$rebate_percent/100;
									$net_amount = $overall_total_amt-$rebate_amount;
								?>
									<tr>
										<td class="col"></td>
										<td class="col label" align="right">Over All Total Amount&nbsp;&nbsp;</td>
										<td class="col"></td>
										<td class="col"></td>
										<td class="col"></td>
										<td class="col label" nowrap="nowrap" align="right"><?php if($overall_total_amt != 0){ echo "<i class='fa fa-inr' style='line-height: 2; width:6px; height:5px;'></i>&nbsp;".moneyFormat($overall_total_amt); } ?> &nbsp;&nbsp;</td>
										<td class="col"></td>
									</tr>
									<tr>
										<td class="col"></td>
										<td class="col label" align="right">Over All Rebate&nbsp;&nbsp;(<?php echo $rebate_percent; ?>%)&nbsp;&nbsp;</td>
										<td class="col"></td>
										<td class="col"></td>
										<td class="col"></td>
										<td class="col label" nowrap="nowrap" align="right"><?php $rebate_amt = $overall_total_amt*$rebate_percent/100; echo moneyFormat($rebate_amt); ?> &nbsp;&nbsp;</td>
										<td class="col"></td>
									</tr>
									<tr>
										<td class="col"></td>
										<td class="col label" align="right">Net Amount&nbsp;&nbsp;</td>
										<td class="col"></td>
										<td class="col"></td>
										<td class="col"></td>
										<td class="col label" nowrap="nowrap" align="right"><?php if($overall_total_amt != 0){ $net_amount = $overall_total_amt-$rebate_amt; echo "<i class='fa fa-inr' style='line-height: 2; width:6px; height:5px;'></i>&nbsp;".moneyFormat($net_amount); } ?> &nbsp;&nbsp;</td>
										<td class="col"></td>
									</tr>
								</table>
								<?php
									$update_query 	= "update sheet set work_order_cost = '$net_amount' where sheet_id = '$sheetid'";
									$update_sql 	= mysql_query($update_query);
								}
								?>
                            </div>
                        </blockquote>
                        <div style="text-align:center; height:45px; line-height:30px;" class="printbutton">
							<div class="buttonsection">
								<input type="submit" name="submit" value="Back">
							</div>
							
						</div>	
                    </div> 

                </div> 
                
            </div> 
            
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
</html>
