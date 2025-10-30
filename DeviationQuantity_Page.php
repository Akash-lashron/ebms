<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$sheetid = $_SESSION['Sheetid'];
//$schdulesql ="SELECT      DISTINCT sno,sch_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno <> '' AND subdiv_id !=0 ";
$schdulesql ="SELECT DISTINCT sno,sch_id, subdiv_id, sheet_id,  description, total_quantity, rate, rebate_percent, deviate_qty_percent, per, decimal_placed, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno != '0' ";
//echo $schdulesql;
$schdule=mysql_query($schdulesql);
 $RowCount =0;
 if(isset($_POST['back']))
 {
     header('Location: DeviationQuantity.php');
 }
 if(isset($_POST['update']))
 {
 	$cnt = count($_POST['hide_result']);
	$sheetid = $_POST['hid_sheetid'];
	$temp = 0;
	for($i=0; $i<$cnt; $i++)
	{
		$res = $_POST['hide_result'];
		$result = explode("@", $res[$i]);
		$update_rebate_query = "update schdule set deviate_qty_percent = '$result[1]' WHERE sch_id = '$result[0]' AND sheet_id = '$sheetid'";
		$update_rebate_sql = mysql_query($update_rebate_query);
		if($update_rebate_sql != true){ $temp++; }
	}
	 if($temp>0) 
	 { 
	 	$msg = 'Data Updation Error ...!!!'; 
	 }
	 if($temp==0)
	 { 
	 	$msg = "Sucessfully Updated...!!!"; 
		$success = 1;
	 }
 }
?>
<?php require_once "Header.html"; ?>
<style>
	.container{
		width:100%;
		border-collapse: collapse;
	}
	.heading{
		display:table-row;
		background-color:#C91622;
		text-align: center;
		line-height: 20px;
		color:#fff;
		font-weight:bold;
	}
	.table-row{  
		display:table-row;
		text-align: left;
	}
	.col{
		display:table-cell;
		border: 1px solid #CCC;
	}
	.textboxstyle{
		text-align:center;
		/*-moz-box-shadow:    inset 0 0 1px #003399;
	    -webkit-box-shadow: inset 0 0 1px #003399;
	    box-shadow:         inset 0 0 1px #003399;*/
	}
	.textboxstyle:hover, .textboxstyle:focus{
		border:1px solid #2aade4;
		box-shadow:inset 0 0 7px #2aade4;
	}
</style>
<script>
function get_decimal_val(hid_id, sch_id, obj){
	var decimal_val = obj.value;
	var txtbox_id 	= obj.id;
	/*if(Number(decimal_val)>100){
		swal("Entered Quantity should be less than or equal to 100 %", "", "");
		obj.value = 0;
	}else{*/
		var schdule_id = sch_id;
		var result_txtbox_id = hid_id;
		document.getElementById("hide_result"+hid_id).value = schdule_id+"@"+decimal_val;
	//}
}
</script>
    <body class="page1" id="top">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content"> 
               <div class="title">Quantity Deviation Percentage</div>
                <div class="container_12"> 
                    <div class="grid_12">
                        <blockquote class="bq1" style="height:1px; overflow:scroll;">
                            <div class="container">
							
								<table class="table-bordered table1 labeldisplay" align="center" id="dataTable">
									<thead>
										<tr>
											<th>Item No.</th>
											<th>Description</th>
											<th nowrap="nowrap">Agreement Qty</th>
											<th nowrap="nowrap">Rate <i class='fa fa-inr' style=' width:4px; height:5px;'></i></th>
											<th nowrap="nowrap">Qty. Deviated (%)</th>
										</tr>
									</thead>
									<tbody>
                                  	<?php 
								  		if($schdule == false){  }else{ $RowCount = mysql_num_rows($schdule); }
                            		   	if($schdule == true && $RowCount > 0){ 
						 				$divid_incr = 1; $x1 = 1;
						 				while($List = mysql_fetch_object($schdule)){ 
									 		$total_amt = ($List->rate * $List->total_quantity); 
									 		if($List->subdiv_id == 0){ $List->rate = "";$List->total_quantity = "";$total_amt = ""; }
								 	?>
										<tr>
											<td align="center"><?php echo $List->sno; ?></td>
											<td align="justify" id="<?php if($List->per != ""){ echo $divid_incr; }else { echo "divid".$divid_incr; } ?>"><?php echo trim($List->description); ?></td>
											<td align="center"><?php echo $List->total_quantity." ".$List->per; ?></td>
											<td align="center"><?php echo $List->rate; ?></td>
											<td align="center">
												<?php if($List->rate != ""){ ?>
												<input type="text" class="textboxdisplay textboxstyle" style="color:#003399;" name="txt_deviateqty_percent" id="txt_deviateqty_percent<?php echo $divid_incr; ?>" value="<?php if($List->deviate_qty_percent == 0){ echo 0; } else { echo $List->deviate_qty_percent;} ?>" onBlur="get_decimal_val(<?php echo $x1; ?>,<?php echo $List->sch_id; ?>,this);" >
												<input type="hidden" name="hide_result[]" id="hide_result<?php echo $x1; ?>" value="<?php echo $List->sch_id."@".$List->deviate_qty_percent; ?>" >
												<?php $divid_incr++; $x1++; } ?>
											</td>
										</tr>
                                <?php $sheetid = $List->sheet_id; } }else{ ?>
										<tr>
											<td align="center" colspan="4">No Records Found</td>
										</tr>
								<?php } ?>
									</tbody>
								</table>
							
							
							
								<input type="hidden" name="hid_txtboxcount" id="hid_txtboxcount" value="<?php echo $divid_incr; ?>" >
								<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php echo $sheetid; ?>" >
								<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
									<div class="buttonsection">
									<input type="submit" name="back" value=" Back ">
									</div>
									<div class="buttonsection">
									<input type="submit" name="update" value=" Update ">
									</div>
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
$( document ).ready(function(){
	/*var txtboxcount = $("#hid_txtboxcount").val();
	var x;
	for(x=1; x<=txtboxcount; x++)
	{
		var div_height = document.getElementById(x).clientHeight;
		document.getElementById("txt_deviateqty_percent"+x).style.height = div_height+"px";
		//var valu = document.getElementById("txt_decimal_placed"+x).value;
		//alert(valu);
	}*/
	$('#dataTable').DataTable({
		responsive: true,
		paging: false, 
	});
});
var msg = "<?php echo $msg; ?>";
var titletext = "";
	document.querySelector('#top').onload = function(){
	if(msg != ""){
		swal({
			  title: "",
			  text: msg,
			  confirmButtonColor: "#3dae38",
			  type:"success",
			  confirmButtonText: " OK ",
			  closeOnConfirm: false,
			},
			function(isConfirm){
			  if (isConfirm) {
				url = "DeviationQuantity_Page.php";
				window.location.replace(url);
			}
		});
	}
};
</script>
<style>
	.dataTables_wrapper{
		width:98% !important;
	}
</style>
