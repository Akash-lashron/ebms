<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
if($_GET['sheetid'] != "")
{
	$sheetid = $_GET['sheetid'];
	$msgtemp = 	$_GET['temp'];
	if($msgtemp == 0)
	{
		$msg = "Sucessfully Updated..."; 
		$success = 1;
	}
	else
	{
		$msg = 'Error ...!!!';
	}
	
}
else
{
	$sheetid = $_SESSION['Sheetid'];
}
//$schdulesql ="SELECT      DISTINCT sno,sch_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno <> '' AND subdiv_id !=0 ";
$schdulesql ="SELECT DISTINCT sno,sch_id, subdiv_id, sheet_id,  description, total_quantity, rate, per, decimal_placed, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno != '0' ";
$schdule=mysql_query($schdulesql);
 $RowCount =0;
 if(isset($_POST['back']))
 {
     header('Location: STax_Assign.php');
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
		$update_decimal_query = "update schdule set after_GST_rate = '$result[1]' WHERE sch_id = '$result[0]' AND sheet_id = '$sheetid'";
		//echo $update_decimal_query."<br/>";
		$update_decimal_sql = mysql_query($update_decimal_query);
		if($update_decimal_sql != true){ $temp++; }
	}//exit;
     //header('Location: ViewDecimalAssign.php');
	 /*if($temp>0) 
	 { 
	 	$msg = 'Data Updation Error ...!!!'; 
	 }
	 if($temp==0)
	 { 
		 $msg = "Sucessfully Updated..."; 
	 }*/
	header('Location: STax_Assigning_Page.php?sheetid='.$sheetid.'&temp='.$temp); 
 }
 //echo "M1".$msg;
?>
<?php require_once "Header.html"; ?>
<style>
	.container{
		display:table;
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
	}
	.textboxstyle
	{
		text-align:center;
		
	/*	
   -moz-box-shadow:    inset 0 0 1px #003399;
   -webkit-box-shadow: inset 0 0 1px #003399;
   box-shadow:         inset 0 0 1px #003399;*/
	}
	.textboxstyle:focus
	{
		border:1px solid #0FA9CA;
		box-shadow: 0 0 10px #9ecaed;
		-webkit-box-shadow: 0 0 10px #9ecaed;
		-moz-box-shadow: 0 0 10px #9ecaed;
	}
</style>
<script>
/*function get_decimal_val(hid_id, sch_id, deci)
{
var decimal_val = deci;
var schdule_id = sch_id;
var result_txtbox_id = hid_id;
var subdivid = document.getElementById("hid_subdivid"+hid_id).value;
alert(subdivid);

document.getElementById("hide_result"+hid_id).value = schdule_id+"@"+decimal_val;
//alert(decimal_val+" === "+schdule_id+" ===== "+result_txtbox_id);
	
}
*/		
		var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        specialKeys.push(9); //Tab
        specialKeys.push(46); //Delete
        specialKeys.push(36); //Home
        specialKeys.push(35); //End
        specialKeys.push(37); //Left
        specialKeys.push(39); //Right
		function IsAlphaNumeric(e) {
            var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
            var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
            return ret;
        }

		function get_decimal_val(hid_id, sch_id, obj)
        {
           	var decimal_val = obj.value;
			var old_value = obj.defaultValue;
			var textboxid = obj.id;
			if(decimal_val<1)
			{
			   alert("Entered STAX value is not valid.");
			   return false;
			}
			var schdule_id = sch_id;
			var result_txtbox_id = hid_id;
			var subdivid = document.getElementById("hid_subdivid"+hid_id).value;
            var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "check_measurement.php?subdivid=" + subdivid;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
                    if (data != "")
                    {
                      if(data == 0)
					  {
					  	//alert("Measurement already entered for this item. Unable to Edit..! ");
						document.getElementById(textboxid).value = old_value;
						swal("Measurement already entered for this item. Unable to Edit...!", "", "error");
						//document.getElementById("hide_result"+hid_id).value = "";
						
					  }
					  else
					  {
					  	document.getElementById("hide_result"+hid_id).value = schdule_id+"@"+decimal_val;
					  }
						
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("workorderno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_work_no.value = strUser;
        }
</script>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content"> 
                <div class="container_12"> 
                    <div class="grid_12">
                        <blockquote class="bq1" style="height:1px; overflow:scroll;">
                            <div class="title" style="position:fixed; width:1062px;">STax_Assigning_Page</div>
                            <div class="container" >
                                <?php 
                                if ($schdule == false) {  } else {        $RowCount = mysql_num_rows($schdule);    }
                            if ($schdule == true && $RowCount > 0) {
                                  ?>
                                <div class="heading" style="position:fixed; top:139px; width:1062px">
                                    <div class="col labelcontenthead" style="width:60px; height:35px;" align="center">Item No.</div>
                                    <div class="col labelcontenthead" style="padding-top:7px; width:877px;">Description</div>
                                    <div class="col labelcontenthead" style="padding-top:7px; width:50px">Unit </div>
									<div class="col labelcontenthead" style="padding-top:7px; width:70px">STax </div>
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
									<div class="col labelhead" align="center" style="width:50px;">
									<?php echo $List->per; ?> </div>
									<div class="col labelhead" align="right">
									<?php if(($List->per != "")&&($List->per != '0'))
									{ ?>
									<input type="text" class="textboxdisplay textboxstyle" style="color:#003399; width:65px" name="txt_decimal_placed" id="txt_decimal_placed<?php echo $divid_incr; ?>" value="<?php echo $List->after_GST_rate; ?>"  onkeypress="return IsAlphaNumeric(event);" onBlur="get_decimal_val(<?php echo $x1; ?>,<?php echo $List->sch_id; ?>,this);"  >
									<input type="hidden" name="hide_result[]" id="hide_result<?php echo $x1; ?>" value="<?php echo $List->sch_id."@".$List->after_GST_rate; ?>" >
									<input type="hidden" id="hid_subdivid<?php echo $x1; ?>" name="hid_subdivid" value="<?php echo $List->subdiv_id; ?>">
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
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="submit" name="back" value=" Back ">
								</div>
								<div class="buttonsection">
							<input type="submit" name="update" value=" Update ">
						</div>
							</div>
                        </blockquote>
						
						
						
                        <!--<div style="width:1074px;">
							<center>
								<table align="centre" width="1074px">
								   <tr>
								   <td align="center" width="57%" height="27px">
									  <input type="submit" name="back" value=" Back ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									  <input type="submit" name="update" value=" Update ">
								   </td>
								   </tr>
								</table>
							</center>
						</div>-->
                        </form>
                    </div>

                </div>
                
            </div>
            
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   		
        </form>
    </body>
				<script>
				
					var msg = "<?php echo $msg; ?>";
					var success = "<?php echo $success; ?>";
					var titletext = "";
					//alert(msg);
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
				</script>
</html>
<script>
$( document ).ready(function(){
	var txtboxcount = $("#hid_txtboxcount").val();
	var x;
	for(x=1; x<=txtboxcount; x++)
	{
		var div_height = document.getElementById(x).clientHeight;
		document.getElementById("txt_decimal_placed"+x).style.height = div_height+"px";
		//var valu = document.getElementById("txt_decimal_placed"+x).value;
		//alert(valu);
	}
});
</script>