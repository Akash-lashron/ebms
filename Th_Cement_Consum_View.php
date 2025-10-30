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
$schdulesql ="SELECT DISTINCT sno, sch_id, subdiv_id, sheet_id, tc_unit, description, total_quantity, rate, per, total_amt FROM schdule where sheet_id= '$sheetid' AND sno != '0' AND  tc_unit != 0";
$schdule=mysql_query($schdulesql);
 $RowCount =0;
 if(isset($_POST['back']))
 {
     header('Location: Th_Cement_Consum.php');
 }
 if(isset($_POST['edit']))
 {
 	$edit_id_list 		= $_POST['ch_edit'];
	$sheetid 		= $_POST['hid_sheetid'];
	
	$cnt = count($edit_id_list);
	if($cnt>0)
	{
		$_SESSION['edit_id_list'] = $edit_id_list;
  		header('Location: Th_Cement_Consum_Edit_Multiple.php?sheetid='.$sheetid);
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
		function get_decimal_val(hid_id, sch_id, obj)
        {
           	var decimal_val = obj.value;
			var old_value = obj.defaultValue;
			var textboxid = obj.id;
			if(decimal_val<1)
			{
			   alert("Entered decimal value is not valid.");
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
                            <div class="title">Theoritical Cement Value - View & Edit</div>
                <div class="container_12"> 
                    <div class="grid_12" align="center">
                        <blockquote class="bq1" style="height:1px; overflow:auto;">
                            <div class="container" align="center">
                                <div class="heading" style="position:fixed; top:139px; width:1062px">
									<div class="col labelcontenthead" style="width:30px; height:35px; vertical-align:middle" align="center">
										<input type="checkbox" name="check_all" id="check_all">
									</div>
                                    <div class="col labelcontenthead" style="width:65px; height:35px; vertical-align:middle" align="center">Item No.</div>
                                    <div class="col labelcontenthead" style="width:800px; vertical-align:middle">Description</div>
                                    <div class="col labelcontenthead" style="width:163px; vertical-align:middle">Theoritical Cement<br/> in kg </div>
                                </div>
                               
                                <?php 
                                if ($schdule == false) {  } else {        $RowCount = mysql_num_rows($schdule);    }
                            if ($schdule == true && $RowCount > 0) {
                                  ?>
                             	<div style=" padding-top:66px;">
									
									<?php 
									$divid_incr = 1; $x1 = 1;
									while ($List = mysql_fetch_object($schdule)) 
									{ 
										 ?>
										<div class="table-row">
											<div class="col labelhead" style="width:30px; height:35px; vertical-align:middle" align="center">
												<input type="checkbox" name="ch_edit[]" id="ch_edit" value="<?php echo $List->sch_id; ?>">
											</div>
											<div class="col labelhead" align="center" style="width:65px; vertical-align:middle">
											<a href="" class="tooltip" title="Click here to Edit.">
											<?php echo $List->sno; ?>
											</a>
											</div>
											<div class="col labelhead" style="width:800px;" align="left"><?php echo $List->description; ?></div>
											<div class="col labelhead" align="right" style="width:163px;"><?php echo $List->tc_unit; ?>&nbsp;&nbsp;</div>
									   </div>
									<?php 
									$sheetid = $List->sheet_id;
									} 
									?>
								</div>
								<?php }else{?>
								<div style="padding-top:100px; text-align:center; vertical-align:middle; height:50px;">
									No Records Found
								</div>
								<?php } ?>
                            </div>
							<input type="hidden" name="hid_txtboxcount" id="hid_txtboxcount" value="<?php echo $divid_incr; ?>" >
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php echo $sheetid; ?>" >
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="submit" name="back" value=" Back ">
								</div>
								<div class="buttonsection">
								<input type="submit" name="edit" id="edit" value=" Edit " />
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
	$("#check_all").click(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
	});
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