<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$sheetid = $_SESSION['Sheetid'];
//$schdulesql ="SELECT      DISTINCT sno,sch_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno <> '' AND subdiv_id !=0 ";
$schdulesql ="SELECT DISTINCT sno,sch_id, subdiv_id, sheet_id,  description, total_quantity, rate, rebate_percent, per, decimal_placed, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno != 0 ";
$schdule=mysql_query($schdulesql);
 $RowCount =0;
 if(isset($_POST['back']))
 {
     header('Location: RebateOffer.php');
 }
 if(isset($_POST['update']))
 {
 	$cnt = count($_POST['hide_result']);
	$sheetid = $_POST['hid_sheetid'];
	//print_r($_POST['hide_result']);
	$temp = 0;
	for($i=0; $i<$cnt; $i++)
	{
		$res = $_POST['hide_result'];
		$result = explode("@", $res[$i]);
		//echo $res[$i];
		$update_rebate_query = "update schdule set rebate_percent = '$result[1]' WHERE sch_id = '$result[0]' AND sheet_id = '$sheetid'";
		//echo $update_decimal_query."<br/>";
		$update_rebate_sql = mysql_query($update_rebate_query);
		if($update_rebate_sql != true){ $temp++; }
	}//exit;
     //header('Location: ViewDecimalAssign.php');
	 if($temp>0) { $msg = 'Data Updation Error ...!!!'; }
	 if($temp==0){ 
	 $msg = "Sucessfully Updated..."; 
	 echo "<script>alert('Sucessfully Updated..!!')</script>"; 
	 echo "<script>window.location.href='RebateOffer.php'</script>";
	 //header('Location: DecimalAssigning.php'); 
	 }
 }
?>
<?php require_once "Header.html"; ?>
<style>
	.container{
		display:table;
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
	.textboxstyle
	{
		text-align:center;
		
	/*	
   -moz-box-shadow:    inset 0 0 1px #003399;
   -webkit-box-shadow: inset 0 0 1px #003399;
   box-shadow:         inset 0 0 1px #003399;*/
	}
</style>
<script>
function get_decimal_val(hid_id, sch_id, deci)
{
var decimal_val = deci;
var schdule_id = sch_id;
var result_txtbox_id = hid_id;
//alert(hid_id);
document.getElementById("hide_result"+hid_id).value = schdule_id+"@"+decimal_val;
//alert(decimal_val+" === "+schdule_id+" ===== "+result_txtbox_id);
	
}
</script>
    <body class="page1" id="top">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content"> 
                <div class="container_12"> 
                    <div class="grid_12">
                        <blockquote class="bq1" style="height:1px; overflow:scroll;">
                            <div class="title" style="position:fixed; width:1062px;">Rebate Offer</div>
                            <div class="container" >
                                <?php 
                                if ($schdule == false) {  } else {        $RowCount = mysql_num_rows($schdule);    }
                            if ($schdule == true && $RowCount > 0) {
                                  ?>
                                <div class="heading" style="position:fixed; top:139px; width:1062px">
                                    <div class="col labelcontenthead" style="width:60px; height:35px;" align="center">Item No.</div>
                                    <div class="col labelcontenthead" style="padding-top:7px; width:877px;">Description</div>
                                    <div class="col labelcontenthead" style="padding-top:7px; width:71px">Rate </div>
									<div class="col labelcontenthead" style="padding-top:7px; width:70px">Rebate</br>( % ) </div>
                                </div>
                               
                             	<div style=" padding-top:72px;">
									
                         <?php 
						 		$divid_incr = 1; $x1 = 1;
						 		while ($List = mysql_fetch_object($schdule)) 
								{ 
									 $total_amt = ($List->rate * $List->total_quantity); 
									 if($List->subdiv_id == 0){ $List->rate = "";$List->total_quantity = "";$total_amt = ""; }
									 ?>
									<div class="table-row">
									<div class="col labelhead" align="center" style="width:60px;"><?php echo $List->sno; ?> </div>
									<div class="col labelhead" style="width:882px;" align="left" id="<?php if($List->per != ""){ echo $divid_incr; }else { echo "divid".$divid_incr; } ?>">
									<?php echo $List->description; ?> </div>
									<div class="col labelhead" align="center" style="width:70px;">
									<?php echo $List->rate; ?> </div>
									<div class="col labelhead" align="right">
									<?php if($List->rate != "") 
									{ ?>
									<input type="text" class="textboxdisplay textboxstyle" style="color:#003399; width:65px" name="txt_rebate_percent" id="txt_rebate_percent<?php echo $divid_incr; ?>" value="<?php echo $List->rebate_percent ; ?>" onBlur="get_decimal_val(<?php echo $x1; ?>,<?php echo $List->sch_id; ?>,this.value);" >
									<input type="hidden" name="hide_result[]" id="hide_result<?php echo $x1; ?>" value="<?php echo $List->sch_id."@".$List-> rebate_percent; ?>" >
									<?php 
									$divid_incr++; $x1++;
									} 
									?>
									</div>
		                       </div>
                                <?php 
								$sheetid = $List->sheet_id;
								} 
								?>
								</div>
								<?php
								}?>
                            </div>
							<input type="hidden" name="hid_txtboxcount" id="hid_txtboxcount" value="<?php echo $divid_incr; ?>" >
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php echo $sheetid; ?>" >
                        </blockquote>
                        <div style="width:1074px;">
							<center>
								<table align="centre" width="1074px">
								   <tr>
								   <td align="right" width="57%">
									  <input type="submit" name="back" value=" Back ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									  <input type="submit" name="update" value=" Update ">
								   </td>
								   <td align="right">
								   <?php if($msg != '') { echo $msg; $msg = "";} ?>
								   </td>
								   </tr>
								</table>
							</center>
						</div>
                        </form>
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
	var txtboxcount = $("#hid_txtboxcount").val();
	var x;
	for(x=1; x<=txtboxcount; x++)
	{
		var div_height = document.getElementById(x).clientHeight;
		document.getElementById("txt_rebate_percent"+x).style.height = div_height+"px";
		//var valu = document.getElementById("txt_decimal_placed"+x).value;
		//alert(valu);
	}
	
});
</script>