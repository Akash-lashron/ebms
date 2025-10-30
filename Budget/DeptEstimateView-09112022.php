<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Price Bid View';
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
$RowCount = 0;
if(isset($_POST['View'])){   
	$MastId 	 = $_POST['cmb_shortname'];
	$SelectQuery 	= "SELECT * FROM parta_details where mastid = '$MastId'";
	$SelectSql 		= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$RowCount = 1;
		}
	}
}
if(isset($_POST['back'])){
     header('Location: DeptEstimateViewGenerate.php');
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
						<div align="right" class="users-icon-part">&nbsp;</div>
                        <blockquote class="bq1" id="bq1" style="overflow:auto;">
                            <div class="container" align="center">
								<div class="smediv">&nbsp;</div>
								<div class="row">
								<div class="div1">&nbsp;</div>
								<div class="div10">
								<table class="table itemtable rtable table2excel" width="100%">
									<thead>
										<tr>
											<th nowrap="nowrap">Item No.</th>
											<th>Description</th>
											<th>Qty</th>
											<th>Unit</th>
											<th nowrap="nowrap">Rate ( &#8377 )</th>
											<th nowrap="nowrap">Amount ( &#8377 )</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$TotalAmount = 0;
									if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
										<tr>
											<td align="center"><?php echo $List->sno; ?></td>
											<td align="justify"><?php echo $List->description; ?></td>
											<td align="right"><?php if($List->quantity != 0){ echo $List->quantity; } ?></td>
											<td align="center"><?php echo $List->unit; ?></td>
											<td align="right"><?php if($List->supply != 0){ echo moneyFormat($List->supply); } ?></td>
											<td align="right">
											<?php 
												$Amount = round(($List->quantity * $List->supply),2);
												$TotalAmount = $TotalAmount + $Amount;
												if($Amount != 0){
													echo moneyFormat($Amount); 
												}
											?>
											</td>
										</tr>
									<?php } ?> 
										<tr>
											<td align="right">&nbsp;</td>
											<td align="right"><b>TOTAL AMOUNT ( &#8377 ) &nbsp;</b></td>
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
									<?php } ?>
									</tbody>
								</table>
								</div>
								<div class="div1">&nbsp;</div>
								</div>
                            </div>
							<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
								<div class="buttonsection">
									<a data-url="DeptEstimateViewGenerate" class="btn btn-info" name="view" id="view">Back</a>
								</div>
								<div class="buttonsection">
									<input type="button" name="exportToExcel" id="exportToExcel" value="Export To Excel" class="btn btn-info">
								</div>
							</div>
							<div class="div12">&nbsp;</div>
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
