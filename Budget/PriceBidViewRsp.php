<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Financial Bid View';
checkUser();
$msg = ''; $success = '';
$userid = $_SESSION['userid'];
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
$RowCount = 0; $ProfitRebate = '';
if(isset($_GET['id'])){   
	$MastId		= $_GET['id'];
	$ContractId = $_GET['contid'];
	//echo $ContractId;exit;
	$Rebper ='';
	$QuotedAmt ='';
	

	$SelectContNameQuery = "SELECT contid,name_contractor FROM contractor WHERE contid = '$ContractId' AND active = 1 ORDER BY contid ASC";
	$SelectContNameQuerySql 	= mysqli_query($dbConn,$SelectContNameQuery);
	if($SelectContNameQuerySql == true){
		if(mysqli_num_rows($SelectContNameQuerySql) > 0){
			$ContListA = mysqli_fetch_object($SelectContNameQuerySql);
			$SelContName = $ContListA->name_contractor;
			//$ContNameId = $ContListA->contid;
			//$ContNameArr[$ContNameId] = $ContNameA;
		}
	}

	$SelectWorkNameQuery = "SELECT work_name FROM tender_register WHERE tr_id = '$MastId' ORDER BY tr_id ASC";
	$SelectMastIdQuerySql = mysqli_query($dbConn,$SelectWorkNameQuery);
	if($SelectMastIdQuerySql == true){
		while($List = mysqli_fetch_object($SelectMastIdQuerySql)){
			$WorkName = $List->work_name;
		}
	}


	$SelectQuery1 	= "SELECT * FROM bidder_bid_master where tr_id = '$MastId' and contid = '$ContractId'";
	$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$List = mysqli_fetch_object($SelectSql1);
				$Rebper = $List->rebate_perc;
				$QuotedAmt = $List->quoted_amt_af_reb;
				$ProfitRebate = $List->rebate_profit;
		}
	}
	$BidderRateArr  = array();
	$SelectQuery 	= "SELECT * FROM bidder_bid_details where tr_id = '$MastId' and contid = '$ContractId' ORDER BY bdid ASC";
	$SelectSql 		= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$RowCount = 1;
		}
	}
}
if(isset($_POST['back'])){
     header('Location: PriceBidViewEdit.php');
}
if($ProfitRebate = "PR"){
	$ProfitRebateStr = "PROFIT";
}else{
	$ProfitRebateStr = "REBATE";
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<style>
.DispTable{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
	}
	.DispTable th, .DispTable td{
		border:1px solid #BCBEBF;
		border-collapse:collapse;
		padding:2px 3px;
	}
	.DispTable th{
		background-color:#035a85;
		color:#fff;
		vertical-align:middle;
		text-align:center;
	}
	.DispTable td{
		color:#062C73;
	}
	.HideDesc{
		max-width : 868px; 
	  	white-space : nowrap;
	  	overflow : hidden;
	  	text-overflow: ellipsis;
	}
	.dataTable {
        line-height: 16px !important;
        font-weight: 700 !important;
        color: #74048C;
       font-size: 12px;
	   border-collapse: collapse;
       text-shadow: none;
       text-transform: none;
       font-family: Verdana, Arial, Helvetica, sans-serif;
       line-height: 17px;
}

	.DispSelectBox{
		border:1px solid #0195D5;
		font-size:11px;
		padding:4px 4px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		width:100%;
		margin-top:2px;
		margin-bottom:2px;
		color:#03447E;
		font-weight:600;
	}
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
				<?php include "MainMenu.php"; ?>
				<div class="container_12">  
					<div class="grid_12" align="center"> 
						<!--<div align="right" class="users-icon-part">&nbsp;</div>-->
						<blockquote class="bq1" id="bq1" style="overflow:auto;">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center"> Financial Bid - View </div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
														   <div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="div12" align="left">
																	<b>
																		<div class="div12 namebox">
																			<table class="nborder">
																				<tr>
																					<td nowrap="nowrap">Name Of Work : </td>
																					<td><?php if(isset($WorkName)){ echo $WorkName; } ?></td>
																				</tr>
																				<tr>
																					<td nowrap="nowrap">Bidder Name &emsp;: </td>
																					<td><?php if(isset($SelContName)){ echo $SelContName; } ?></td>
																				</tr>
																			</table>
																		</div>
																		<div class="row smclearrow"></div>
																	</b> 
																</div>
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																		<tr class='labeldisplay'>
																			<th valign="middle" nowrap="nowrap">Item No.</th>
																			<th valign="middle">Description</th>
																			<th valign="middle">Qty</th>
																			<th valign="middle">Unit</th>
																			<th valign="middle" nowrap="nowrap">Rate ( &#8377 )</th>
																			<th valign="middle" nowrap="nowrap">Amount ( &#8377 )</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		$TotalAmount = 0;
																		if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
																		
																			<tr>
																				<td   valign='middle' class='tdrow'align="center"><?php echo $List->item_no; ?></td>
																				<td   valign='middle' class='tdrow' align="justify"><?php echo $List->item_desc; ?></td>
																				<td   valign='middle' class='tdrow' align="right"><?php if($List->item_qty != 0){ echo $List->item_qty; } ?></td>
																				<td   valign='middle' class='tdrow'align="center"><?php echo $List->item_unit; ?></td>
																				<td   valign='middle' class='tdrow' align="right"><?php if($List->item_rate != 0){ echo moneyFormat($List->item_rate); } ?></td>
																				<td   valign='middle' class='tdrow'align="right">
																				<?php 
																					$Amount = round(($List->item_qty * $List->item_rate),2);
																					$TotalAmount = $TotalAmount + $Amount;
																					if($Amount != 0){
																						round($Amount); 
																					}
																					if($Amount != 0){ echo moneyFormat($Amount); }
																				?>
																				</td>
																			</tr>
																		<?php } ?> 
																		<tr class='labeldisplay'>
																			<td  align="right">&nbsp;</td>
																			<td class='tdrowbold' align="right"><b>TOTAL AMOUNT ( &#8377 ) &nbsp;</b></td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">
																			<?php 
																				$Amount = round($TotalAmount,2);
																				if($TotalAmount != 0){
																					echo '<b>'.moneyFormat($TotalAmount).'</b>'; 
																				}
																			?>
																			</td>
																		</tr>
																		<tr class='labeldisplay'>
																			<td  align="right">&nbsp;</td>
																			<td class='tdrowbold' align="right">
																				<b><?php echo $ProfitRebateStr; ?> ( % ) &nbsp;</b>
																			</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td  class='tdrowbold' align="right">
																			<b><?php 
																			echo $Rebper; 
																			?>
																			</b></td>
																		</tr>
																		<?php  
																			$Totalamt = $TotalAmount;
																			$Rebpert = $Rebper;
																			$RebateValue = $Totalamt * $Rebpert/100;
																		?>
																		<tr class='labeldisplay'>
																			<td  align="right">&nbsp;</td>
																			<td class='tdrowbold' align="right"><b><?php echo $ProfitRebateStr; ?> VALUE ( &#8377 ) &nbsp;</b></td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td  class='tdrowbold' align="right"><b>
																			<?php 
																			echo moneyFormat($RebateValue); 
																			?>
																			</b></td>
																		</tr>
																		<tr class='labeldisplay'>
																			<td  align="right">&nbsp;</td>
																			<td class='tdrowbold' align="right"><b>TOTAL AMOUNT AFTER <?php echo $ProfitRebateStr; ?> ( &#8377 ) &nbsp;</b></td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td  class='tdrowbold' align="right"><b>
																			<?php 
																			echo moneyFormat($QuotedAmt); 
																			?>
																			</b></td>
																		</tr>
																		<?php } ?>
																	</tbody>
																</table>
												   	   </div>
															<div align="center">
																<div class="buttonsection">
																	<a data-url="PriceBidViewEdit" class="btn btn-info" name="view" id="view">Back</a>
																</div>
																<!-- <div class="buttonsection">
																	<input type="button" name="exportToExcel" id="exportToExcel" value="Export To Excel" class="btn btn-info">
																</div> -->
															</div>
												   	</div>
											  		</div>
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="div1">&nbsp;</div> -->
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
	<script>
		$(document).ready(function(){ 
			$("#exportToExcel").click(function(e){ 
				var table = $('body').find('.table2excel');
				if(table.length){ 
					$(table).table2excel({
						exclude: ".xlTable",
						name: "SOQ",
						filename: "PriceBid -" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
						fileext: ".xls",
						exclude_img: true,
						exclude_links: true,
						exclude_inputs: true
						//preserveColors: preserveColors
					});
				}
			});
		});
	</script>
</html>
<style>
.table1 td{
	background:#fff;
}
</style>
