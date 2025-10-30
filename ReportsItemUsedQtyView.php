<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
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
if($_POST["submit"] == " View ") 
{
	$sheetid 	= $_POST['cmb_work_no'];
	$select_qty_query 	= "select sum(mbtotal) as item_qty, subdivid from mbookgenerate_staff where sheetid = '$sheetid' group by subdivid";
	$select_qty_sql 	= mysql_query($select_qty_query);
	$ItemIdArr 			= array(); 
	$ItemQtyArr			= array(); 
	if($select_qty_sql == true)
	{
		if(mysql_num_rows($select_qty_sql)>0)
		{
			while($ItemList = mysql_fetch_object($select_qty_sql))
			{
				$itemid = $ItemList->subdivid;
				$itemQty = $ItemList->item_qty;
				array_push($ItemIdArr,$itemid);
				array_push($ItemQtyArr,$itemQty);
			}
		}
	}
	$ItemCnt = count($ItemIdArr);
}
//echo $select_qty_query;
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack()
	{
	   	url = "ReportsItemUsedQty.php";
		window.location.replace(url);
	}
	function printPage()
	{
		var printContents = document.getElementById('printSection').innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	.table1 tr td {
		padding:3px;
		vertical-align:middle;
		font-family:Verdana;
		font-size:12px;
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->

<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
				<div class="title">Item Wise Report</div>
	<div class="container_12">
		<div class="grid_12">
			<blockquote class="bq1" style="overflow:scroll">
				<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<br/>
					<div id="printSection">
						<table width="100%" class="table1">
							<tr>
								<td class="label" colspan="6" align="center">Item Report</td>
							</tr>
							<tr class="label">
								<td align="center">Item No.</td>
								<td align="center">Item Description</td>
								<td align="center">Agreement Qty.</td>
								<td align="center">Used Qty.</td>
								<td align="center">Balance Qty.</td>
								<td align="center">Unit</td>
							</tr>
					<?php 
					if($ItemCnt > 0)
					{
						for($i=0; $i<$ItemCnt; $i++)
						{
							$Item_id = $ItemIdArr[$i];
							$UsedQty = $ItemQtyArr[$i];
							$select_item_details_query = "select sno, description, shortnotes, total_quantity, per, decimal_placed, item_flag, supp_sheet_id from schdule where subdiv_id = '$Item_id' and sheet_id = '$sheetid'";
							$select_item_details_sql = mysql_query($select_item_details_query);
							if($select_item_details_sql == true)
							{
								if(mysql_num_rows($select_item_details_sql)>0)
								{
									$List = mysql_fetch_object($select_item_details_sql);
									$itemno 		= $List->sno;
									$description 	= $List->description;
									$shortnotes 	= $List->shortnotes;
									$AggrementQty 	= $List->total_quantity;
									$unit 			= $List->per;
									$decimal_placed = $List->decimal_placed;
									$BlanceQty 		= $AggrementQty - $UsedQty;
									$BalanceQty = round($BlanceQty,$decimal_placed);
									if($shortnotes != "")
									{
										$item_description = $shortnotes;
									}
									else
									{
										$item_description = $description;
									}
								}
							}
						?>
							<tr>
								<td align="center"><?php echo $itemno; ?></td>
								<td align="left">  <?php echo $item_description; ?></td>
								<td align="right"> <?php echo number_format($AggrementQty, $decimal_placed, '.', ''); ?></td>
								<td align="right"> <?php echo number_format($UsedQty, $decimal_placed, '.', ''); ?></td>
								<td align="right"> <?php echo number_format($BalanceQty, $decimal_placed, '.', ''); ?></td>
								<td align="center"><?php echo $unit; ?></td>
							</tr>
						<?php
							
						}
					}
					?>
						</table>
					</div>
					<div>&nbsp;</div>
					<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
						<div class="buttonsection">
							<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> 
						</div>
						<div class="buttonsection">
							<input type="button" class="backbutton" value=" Print " name="btn_print" id="btn_print" onClick="printPage();"/>
						</div>
					</div>
				</form>
			</blockquote>
		</div>
	</div>
</div>
         <!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
    $(function() {
	$.fn.validatembooktype = function(event) {	
				if($("#cmb_mbook_type").val()==""){ 
					var a="Please select the Measurement Type";
					$('#val_mbooktype').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_mbooktype').text(a);
				}
			}
	$.fn.validateworkorder = function(event) { 
					if($("#cmb_work_no").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
	$("#top").submit(function(event){
            $(this).validatembooktype(event);
			$(this).validateworkorder(event);
         });
	$("#cmb_work_no").change(function(event){
           $(this).validateworkorder(event);
         });
    $("#cmb_mbook_type").change(function(event){
           $(this).validatembooktype(event);
         });
			
	 });
</script>
    </body>
</html>

