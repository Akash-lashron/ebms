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
	
	$schdulesql ="SELECT DISTINCT sno, sch_id, subdiv_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no, escalation_flag FROM schdule where sheet_id= '$sheetid' AND sno != '0' and supp_sheet_id = '$supp_sheetid'";// and item_flag = 'DI'";
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
if(isset($_POST['btn_save']))
{
	$sheetid 		= $_POST['txt_sheetid'];
	$supp_sheetid 	= $_POST['txt_supp_sheetid'];
	$EscItems 		= $_POST['ch_escalation'];
	
	$UpdateQuery = "update schdule set escalation_flag = '' where sheet_id = '$sheetid' and supp_sheet_id = '$supp_sheetid'";
	$UpdateSql = mysql_query($UpdateQuery);
	//print_r($EscItems);exit;
	if($EscItems != "")
	{
		foreach ($EscItems as $EItems)
		{
			$sch_id = $EItems;
			$UpdateEscflagQuery = "update schdule set escalation_flag = 'Y' where sch_id = '$sch_id'";
			$UpdateEscflagSql = mysql_query($UpdateEscflagQuery);
		}
		//echo $_POST['cmb_username']."<br/>";exit;
	}
	if($UpdateEscflagSql == true)
	{
		$msg = "Esacation Item Saved Sucessfully.";
		$success = 1;
	}
	else
	{
		$msg = "Error..!";
	}
	
	
	
	$schdulesql ="SELECT DISTINCT sno, sch_id, subdiv_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no, escalation_flag FROM schdule where sheet_id= '$sheetid' AND sno != '0' and supp_sheet_id = '$supp_sheetid'";// and item_flag = 'DI'";
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
	//print_r($EscItems);exit;
}

?>
<?php require_once "Header.html"; ?>
<style>
.container
{
    display:table;
    width:100%;
    border-collapse: collapse;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
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
	padding:2px;
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
	   	url = "SupplementaryEscalationItemAssign.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">  
                            <div class="title">Supplementary - Escalation Item Assign</div>
                <div class="container_12">  
                    <div class="grid_12"> 
                        <blockquote class="bq1" id="bq1"  style="overflow:scroll;">
                            <div class="container">
								<br/>
								<table width="100%" class="table1">
									<tr class="label">
										<td align="center" valign="middle">Item No.</td>
										<td align="center" valign="middle">Description</td>
										<td align="center" valign="middle">Total Quantity</td>
										<td align="center" valign="middle">Unit</td>
										<td align="center" valign="middle">Rate <br/><i class="fa fa-inr"></i></td>
										<td align="center" valign="middle">Escalation<br/>( YES / NO ) <br/> <input type="checkbox" name="check_all" id="check_all"></td>
									</tr>
                                <?php 
                                if ($schdule == false) {  } else {        $RowCount = mysql_num_rows($schdule);    }
                            	if ($schdule == true && $RowCount > 0) {
                                  ?>
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
										$esc_flag = $List->escalation_flag;
                                  ?>
								  	<tr>
										<td align="center" valign="middle"><?php if($prev_item_no != $List->sno) { echo $List->sno; } ?></td>
										<td>&nbsp;<?php echo $List->description; ?></td>
										<td align="right" valign="middle"><?php echo $List->total_quantity; ?>&nbsp;</td>
										<td align="center" valign="middle"><?php echo $unit; ?></td>
										<td align="right" valign="middle"><?php if($List->rate != 0){ echo $rate; } ?>&nbsp;</td>
										<td align="center" valign="middle"><input type="checkbox" name="ch_escalation[]" id="ch_escalation" value="<?php echo $List->sch_id; ?>" <?php if($esc_flag == "Y"){ echo 'checked="checked"'; } ?>></td>
									</tr>
                                <?php 
									$overall_total_amt = $overall_total_amt + $total_amt; 
									$rebate_amount = $overall_total_amt*$rebate_percent/100;
									$net_amount = $overall_total_amt-$rebate_amount;
									$prev_item_no = $List->sno;
								} }
								?>
								</table>
								<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>">
								<input type="hidden" name="txt_supp_sheetid" id="txt_supp_sheetid" value="<?php echo $supp_sheetid; ?>">
                            </div>
							<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();">
								</div>
								<div class="buttonsection">
									<input type="submit" class="backbutton" name="btn_save" id="btn_save" value="Save">
								</div>
							</div>	
                        </blockquote>
                    </div> 
                </div> 
            </div> 
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
						if(success == 1)
						{
							swal("", msg, "success");
						}
						else
						{
							swal(msg, "", "");
						}
				}
				};
				
				$("#check_all").click(function(){
					$('input:checkbox').not(this).prop('checked', this.checked);
				});
			</script>
        </form>
    </body>
</html>
