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
if(isset($_POST['submit']))
{
	$sheetid 		= $_POST['workorderno'];
	$supp_sheetid 	= $_POST['workorderno_supp'];
	
	$schdulesql ="SELECT DISTINCT sno, sch_id, subdiv_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no FROM schdule where sheet_id= '$sheetid' AND sno != '0' and supp_sheet_id = '$supp_sheetid' and item_flag = 'EI'";
	//echo $schdulesql;
	$schdule=mysql_query($schdulesql);
	$rebate_percent = 0;
	$rebate_perc_sql = "SELECT rebate_percent FROM sheet_supplementary WHERE supp_sheetid = '$supp_sheet_id' AND active = 1";
	$rebate_perc_query = mysql_query($rebate_perc_sql);
	if($rebate_perc_query == true)
	{
		if(mysql_num_rows($rebate_perc_query)>0)
		{
			$RList = mysql_fetch_object($rebate_perc_query);
			$rebate_percent = $RList->rebate_percent;
		}
	}
	$RowCount =0;
}
?>
<?php require_once "Header.html"; ?>
<style>
.container
{
    display:table;
    width:100%;
    border-collapse: collapse;
}
.table-row
{  
    display:table-row;
    text-align: left;
}
.col
{
	display:table-cell;
	border: 1px solid #CCC;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:9pt;
}
sub, sup 
{
	font-size: 75%;
	line-height: 0;
	position: relative;
	vertical-align: baseline;
}
sup 
{
	top: -0.5em;
}
sub 
{
	bottom: -0.25em;
}

/*.colfont{
  font-family:Constantia; 
  font-size: 11pt;
}*/
</style>
<script type="text/javascript">
	function goBack()
	{
	   	url = "SupplementaryExtraItemView.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">  
                <div class="container_12">  
                    <div class="grid_12"> 
                        <blockquote class="bq1" id="bq1"  style="overflow:scroll;">
                            <div class="title" style="position:fixed; width:1062px;">View Agreement Sheet</div>
                            <div class="container">
                                <?php 
                                if ($schdule == false) {  } else {        $RowCount = mysql_num_rows($schdule);    }
                            if ($schdule == true && $RowCount > 0) {
                                  ?>
                                <div class="heading" style="position:fixed; top:139px;">
                                    <div class="col" style="width:63px; padding-top:5px">Item <br/>No.</div>
                                    <div class="col" style="padding-top:10px; width:600px;">Description</div>
									<div class="col" style="width:75px">Total Quantity</div>
									<div class="col" style="padding-top:10px; width:50px">Unit </div>
                                    <div class="col" style=" width:118px;">
										<!--Total <br/>Amt<br/><font style="font-size:11px">(Rs . Ps)</font>-->
										<div style=" height:24px; border-top:none;">
											<div style="width:100%">Rate</div>
											<!--<div style="width:100%">(Rs . Ps)</div>-->
									   </div>
									   <div style=" text-align:center; border-top:none;">
											<!--<div style="width:100%">Total Amt</div>-->
											<div style="width:100%"><i class="fa fa-inr"></i></div>
									   </div>
									</div>
                                    <div class="col" style=" width:149px;">
										<div style=" height:24px; border-top:none;">
											<div style="width:100%">Total Amt</div>
									   </div>
									   <div style=" text-align:center; border-top:none;">
											<div style="width:100%"><i class="fa fa-inr"></i></div>
									   </div>
									</div>
                                </div>
                               
                            <!-- <div class="table-row">
								 <div class="col"></div>
								<!-- <div class="col"><?php //echo "&nbsp;EARTHWORK"; ?></div>
								 <div class="col"></div>
								 <div class="col"></div>
								 <div class="col"></div>
								 <div class="col"></div></div>-->
								<div style=" padding-top:77px;"> 
                                <?php 
								$overall_total_amt = 0;
								while ($List = mysql_fetch_object($schdule)) 
								{ 
										
										if(trim($List->per) == "cum"){ $unit = $List->per; /*$unit = "m<sup>3</sup>";*/  }
										else if(trim($List->per) == "sqm"){ $unit = $List->per; /*$unit = "m<sup>2</sup>";*/ }
										else { $unit = $List->per; }
                                        $total_amt = ($List->rate * $List->total_quantity); 
										$rate = moneyFormat($List->rate);
										$total = moneyFormat($total_amt);
                                        if($List->subdiv_id == 0){ $List->rate = "";$List->total_quantity = "";$total_amt = ""; }
                                  ?>
									<div class="table-row">
										<div class="col labelhead" align="center" style="width:63px;"><?php if($prev_item_no != $List->sno) { echo $List->sno; } ?> </div>
										<div class="col labelhead" align="left" style="width:601px"><?php echo $List->description; ?> </div>
										<div class="col labelhead" align="right" style="width:75px"><?php echo $List->total_quantity; ?> &nbsp;</div>
										<div class="col labelhead" align="center" style="width:50px"><?php echo $unit; ?> </div>
										<div class="col labelhead" align="right" style="width:118px"><?php if($List->rate != 0){ echo $rate; } ?> &nbsp;</div>
										<div class="col labelhead" align="right" style="width:149px;"><?php if($total_amt != 0){ echo $total; } ?> &nbsp;</div>
                    	                
		                           </div>
                                <?php 
									$overall_total_amt = $overall_total_amt + $total_amt; 
									$rebate_amount = $overall_total_amt*$rebate_percent/100;
									$net_amount = $overall_total_amt-$rebate_amount;
									$prev_item_no = $List->sno;
								} 
								?>
									<div class="table-row" style="font-weight:bold;">
										<div class="col labelhead" align="center" style="width:63px;"></div>
										<div class="col labelhead" align="right" style="width:601px"> Over All Total Amount&nbsp;&nbsp;</div>
										<div class="col labelhead" align="right" style="width:75px"></div>
										<div class="col labelhead" align="center" style="width:50px"></div>
										<div class="col labelhead" align="right" style="width:118px"></div>
										<div class="col labelhead" align="right" style="width:149px;"><?php if($overall_total_amt != 0){ echo "<i class='fa fa-inr' style='line-height: 2; width:6px; height:5px;'></i>&nbsp;".moneyFormat($overall_total_amt); } ?> &nbsp;</div>
                    	                
		                           </div>
								   <div class="table-row" style="font-weight:bold;">
										<div class="col labelhead" align="center" style="width:63px;"></div>
										<div class="col labelhead" align="right" style="width:601px"> Over All Rebate&nbsp;&nbsp;(<?php echo $rebate_percent; ?>%)&nbsp;&nbsp;</div>
										<div class="col labelhead" align="right" style="width:75px"></div>
										<div class="col labelhead" align="center" style="width:50px"></div>
										<div class="col labelhead" align="right" style="width:118px"></div>
										<div class="col labelhead" align="right" style="width:149px;"><?php $rebate_amt = $overall_total_amt*$rebate_percent/100; echo moneyFormat($rebate_amt); ?>&nbsp;&nbsp;</div>
                    	                
		                           </div>
								   <div class="table-row" style="font-weight:bold;">
										<div class="col labelhead" align="center" style="width:63px;"></div>
										<div class="col labelhead" align="right" style="width:601px"> Net Amount&nbsp;&nbsp;</div>
										<div class="col labelhead" align="right" style="width:75px"></div>
										<div class="col labelhead" align="center" style="width:50px"></div>
										<div class="col labelhead" align="right" style="width:118px"></div>
										<div class="col labelhead" align="right" style="width:149px;"><?php if($overall_total_amt != 0){ $net_amount = $overall_total_amt-$rebate_amt; echo "<i class='fa fa-inr' style='line-height: 2; width:6px; height:5px;'></i>&nbsp;".moneyFormat($net_amount); } ?> &nbsp;</div>
                    	                
		                           </div>
								</div>
								<?php
								}else{?>
								 <div class="heading" style="position:fixed; top:139px;">
                                    <div class="col" style="width:63px; padding-top:5px">Item <br/>No.</div>
                                    <div class="col" style="padding-top:10px; width:600px;">Description</div>
									<div class="col" style="width:75px">Total Quantity</div>
									<div class="col" style="padding-top:10px; width:50px">Unit </div>
                                    <div class="col" style=" width:118px;">
										<!--Total <br/>Amt<br/><font style="font-size:11px">(Rs . Ps)</font>-->
										<div style=" height:24px; border-top:none;">
											<div style="width:100%">Rate</div>
											<!--<div style="width:100%">(Rs . Ps)</div>-->
									   </div>
									   <div style=" text-align:center; border-top:none;">
											<!--<div style="width:100%">Total Amt</div>-->
											<div style="width:100%"><i class="fa fa-inr"></i></div>
									   </div>
									</div>
                                    <div class="col" style=" width:149px;">
										<div style=" height:24px; border-top:none;">
											<div style="width:100%">Total Amt</div>
									   </div>
									   <div style=" text-align:center; border-top:none;">
											<div style="width:100%"><i class="fa fa-inr"></i></div>
									   </div>
									</div>
                                </div>
								<?php } ?>
                            </div>
                        </blockquote>
                        <div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
							<div class="buttonsection">
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();">
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
