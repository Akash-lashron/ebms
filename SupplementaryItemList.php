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
	
	$schdulesql ="SELECT DISTINCT sno, sch_id, subdiv_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no, item_flag FROM schdule where sheet_id= '$sheetid' AND sno != '0' and supp_sheet_id = '$supp_sheetid'";// and item_flag = 'DI'";
	//echo $schdulesql;
	$schdule=mysql_query($schdulesql);
	$rebate_percent = 0;
	$rebate_perc_sql = "SELECT rebate_percent FROM sheet_supplementary WHERE supp_sheet_id = '$supp_sheetid' AND active = 1";
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
function CheckmeasurementEntered($subdivid){
	$Exist1 = 0; $Exist2 = 0;
	$SelectQuery1 = "select mbheaderid from mbookheader where subdivid = '$subdivid' order by mbheaderid limit 1";
	$SelectSql1 = mysql_query($SelectQuery1);
	if(mysql_num_rows($SelectSql1)>0){
		$Exist1 = 1;
	}
	$SelectQuery2 = "select mbheaderid from mbookheader_temp where subdivid = '$subdivid' order by mbheaderid limit 1";
	$SelectSql2 = mysql_query($SelectQuery2);
	if(mysql_num_rows($SelectSql2)>0){
		$Exist2 = 1;
	}
	if(($Exist1 == 1)||($Exist2 == 1)){
		$Exist = 1;
	}else{
		$Exist = 0;
	}
	return $Exist;
}
//echo $rebate_percent;exit;
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
	function goBack()
	{
	   	url = "SupplementaryItemView.php";
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
                <div class="title">View Supplementary Agreement Sheet</div>
                <div class="container_12">  
                    <div class="grid_12"> 
                        <blockquote class="bq1" id="bq1" style="overflow:auto;">
							<div align="center">&nbsp;</div>
							<div align="center">
							<table width="90%" class="table1 table2">
								<tr class="heading">
									<th class="colhead" nowrap="nowrap" valign="middle">Item No.</th>
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
										if($List->subdiv_id != 0){
											$CheckEditDelete = CheckmeasurementEntered($List->subdiv_id);
										}else{
											$CheckEditDelete = "";
										}
                                  ?>
									<tr>
										<td class="col" align="center" nowrap="nowrap">
										<span class="<?php echo $type_class; ?>">
										<?php if($prev_item_no != $List->sno) { echo $List->sno; } ?>
										</span>
										</td>
										<td class="col labelprint" align="justify"><?php echo $List->description; ?></td>
										<td class="col" align="right"><?php echo $List->total_quantity; ?>&nbsp;</td>
										<td class="col">&nbsp;<?php echo $unit; ?>&nbsp;</td>
										<td class="col" align="right"><?php if($List->rate != 0){ echo $rate; } ?>&nbsp;</td>
										<td class="col" align="right"><?php if($total_amt != 0){ echo $total; } ?>&nbsp;</td>
										<td class="col" align="right" nowrap="nowrap">
											<?php if(($CheckEditDelete == 0)&&($List->subdiv_id != 0)){ ?>
											<a class="oval-btn-delete Delete" data-id="<?php echo $List->sch_id; ?>" data-sid="<?php echo $List->subdiv_id; ?>">
												<i style="font-size:12px; padding-top:5px; font-weight:100" class="fa">&#xf00d;</i> Delete
											</a>
											<?php }else if(($CheckEditDelete == 1)&&($List->subdiv_id != 0)){ ?>
											<a class="oval-btn-disable">
												<i style="font-size:12px; padding-top:5px; font-weight:100" class="fa">&#xf00d;</i> Delete
											</a>
											<?php } ?>
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
							</div>
							<div style="text-align:center; height:50px; line-height:30px;" class="printbutton">
								<div class="buttonsection">
									<input type="hidden" name="workorderno" id="workorderno" value="<?php echo $sheetid; ?>">
									<input type="hidden" name="workorderno_supp" id="workorderno_supp" value="<?php echo $supp_sheetid; ?>">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();">
									<input type="submit" class="backbutton" name="submit" id="submit" value="submit" style="display:none">
								</div>
							</div>	
                        </blockquote>
                    </div> 

                </div> 
                
            </div> 
            
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
</html>
<script>
	$(document).ready(function() {	
		$('body').on("click",".Delete", function(event){ 
			var id = $(this).attr("data-id");
			var sid = $(this).attr("data-sid");
			$.ajax({ 
				type: 'POST', 
				url: 'SupplementaryItemDelete.php', 
				data: { id: id, sid: sid }, 
				success: function (data) { //alert(data);
					if(data == 1){
						BootstrapDialog.show({
							title: 'Information',
							message: 'Successfully Deleted',
							buttons: [{
								label: ' OK ',
								action: function(dialog) {
									$("#submit").trigger( "click" );
								}
							}]
						});
					}else{
						BootstrapDialog.show({
							title: 'Information',
							message: 'Unable to Delete. Please try again.',
							buttons: [{
								label: ' OK ',
								action: function(dialog) {
									$("#submit").trigger( "click" );
								}
							}]
						});
					}
				}
			})
		});
   	});
</script>