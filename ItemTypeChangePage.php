<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
if($_GET['sheetid'] != ""){
	$sheetid = $_GET['sheetid'];
	$msgtemp = 	$_GET['temp'];
	if($msgtemp == 0){
		$msg = "Sucessfully Updated..."; 
		$success = 1;
	}else{
		$msg = 'Error ...!!!';
	}
}
$ItemTypeArr = array(""=>"General", "s"=>"Steel", "st"=>"Structural Steel");
if(isset($_POST['back'])){
	header('Location: ItemTypeChange.php');
}
if(isset($_POST['update'])){
 	$cnt = count($_POST['hide_result']);
	$sheetid = $_POST['hid_sheetid'];
	$temp = 0;
	for($i=0; $i<$cnt; $i++){
		$res = $_POST['hide_result'];
		$result = explode("@", $res[$i]);
		$update_item_type_query = "update schdule set measure_type = '$result[1]' WHERE sch_id = '$result[0]' AND sheet_id = '$sheetid'";
		$update_item_type_sql = mysql_query($update_item_type_query);
		if($update_item_type_sql == true){ $temp++; }
		//echo $update_item_type_query;exit;
	}
	if($temp > 0){
		$msg = "Sucessfully Updated..."; 
		$success = 1;
	}else{
		$msg = 'Error ...!!!';
	}
	//echo $update_item_type_query;
	//exit;
	//header('Location: ItemTypeChangePage.php'); 
}
if(isset($_POST["submit"]) == ' View '){
	$sheetid = $_POST['cmb_work_no'];
}
$RowCount =0; $UseditemIdArr = array();
if($sheetid != ''){
	$SelectItemIdQuery = "select distinct subdivid from mbookheader where sheetid = '$sheetid'";
	$SelectItemIdSql = mysql_query($SelectItemIdQuery);
	if($SelectItemIdSql == true){
		if(mysql_num_rows($SelectItemIdSql)>0){
			while($ItemIdList = mysql_fetch_object($SelectItemIdSql)){
				array_push($UseditemIdArr,$ItemIdList->subdivid);
			}
		}
	}
	$schdulesql = "SELECT DISTINCT sno,sch_id, subdiv_id, sheet_id,  description, total_quantity, rate, per, decimal_placed, total_amt, subdiv_id, page_no, measure_type FROM  schdule   where sheet_id= '$sheetid' AND  sno != '0' ";
	$schdule	= mysql_query($schdulesql);
}
 //echo "M1".$msg;
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
	}
	.colhead
	{
		display:table-cell;
		border: 1px solid #CCC;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:9pt;
		vertical-align:middle;
		padding:3px;
		color:#025fa4;
	}
	.textboxstyle
	{
		text-align:center;
		width:150px;
		
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
	.disable-input{
		border:1px solid #D4D5D6;
		background:#DFE1E3;
		color:#666666;
	}
</style>
<script>
function get_item_type(hid_id, sch_id, obj)
{
	var item_type_val = obj.value;
	var txtbox_id 	= obj.id;
	//if(Number(item_type_val)>100)
	//{
		//swal("Entered Quantity should be less than or equal to 100 %", "", "");
		//obj.value = 0;
	//}
	//else
	//{
		var schdule_id = sch_id;
		var result_txtbox_id = hid_id;
		document.getElementById("hide_result"+hid_id).value = schdule_id+"@"+item_type_val;
	//}
}
</script>
<!--<script>
/*function get_item_type(hid_id, sch_id, deci)
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

		function get_item_type(hid_id, sch_id, obj)
        {
           	var item_val = obj.value;
			var old_value = obj.defaultValue;
			var textboxid = obj.id;
			/*if(item_val=='')
			{
			   alert("Entered iteam value is not valid.");
			   return false;
			}*/
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
					  	document.getElementById("hide_result"+hid_id).value = schdule_id+"@"+item_val;
					  }
						
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("workorderno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_work_no.value = strUser;
        }
</script>-->
<script>
	var specialKeys = new Array();
	specialKeys.push(8); //Backspace
	specialKeys.push(9); //Tab
	specialKeys.push(46); //Delete
	specialKeys.push(36); //Home
	specialKeys.push(35); //End
	specialKeys.push(37); //Left
	specialKeys.push(39); //Right
	function IsAlphaNumeric(evt) {
		var keyCode = (evt.which)? evt.which :evt.keyCode
		//var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
		//return ret;
		if((keyCode<65||keyCode > 90)&&(keyCode < 97 || keyCode > 123) && keyCode !=32)
		return false;
		 return true;
	}
	/*function isDecimalNumber(evt, c) {
			var charCode = (evt.which) ? evt.which : event.keyCode;
			var dot1 = c.value.indexOf('.');
			var dot2 = c.value.lastIndexOf('.');

			if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
			else if (charCode == 46 && (dot1 == dot2) && dot1 != -1 && dot2 != -1)
				return false;

			return true;
		}*/
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
                            <div class="title">Item Type Edit</div>
                <div class="container_12"> 
                    <div class="grid_12" align="center">
                        <blockquote class="bq1" style="height:1px; overflow:scroll;">
                            <div class="container" > 
							<br/>
								<table width="90%" class="table1 table2">
									<tr class="heading">
										<th class="colhead" nowrap="nowrap">Item No.</th>
										<th class="colhead">Description</th>
										<th class="colhead">Unit</th>
										<th class="colhead">Item Type</th>
									</tr>
                                <?php 
                                if($schdule == false){  }else{ $RowCount = mysql_num_rows($schdule); }
                            	if($schdule == true && $RowCount > 0){  
						 		$divid_incr = 1; $x1 = 1;
						 		while ($List = mysql_fetch_object($schdule)) 
								{ 
									$total_amt = ($List->rate * $List->total_quantity); 
									if($List->subdiv_id == 0){ $List->rate = "";$List->total_quantity = "";$total_amt = ""; }
									if(in_array($List->subdiv_id, $UseditemIdArr)){
										$ItemChangeFlag = 0; 
										$Disable = 'disabled="disabled"';
										$DisableClass = "disable-input";
									}else{
										$ItemChangeFlag = 1; 
										$Disable = '';
										$DisableClass = '';
									}
								?>
									 
									<tr class="table-row">
										<td class="col" align="center" nowrap="nowrap"><?php echo $List->sno; ?></td>
										<td class="col" id="<?php if($List->per != ""){ echo $divid_incr; }else { echo "divid".$divid_incr; } ?>"><?php echo $List->description; ?></td>
										<td class="col"><?php echo $List->per; ?></td>
										<td class="col">
										<?php if(($List->per != "")&&($List->per != '0')) { ?>
										<!--<input type="text" class="textboxdisplay textboxstyle" <?php echo ModuleRights("DEAC"); ?> style="color:#003399; width:65px" name="txt_measure_type" id="txt_measure_type<?php echo $divid_incr; ?>" value="<?php echo $List->measure_type; ?>" onKeyPress="return IsAlphaNumeric(event,this);"  onBlur="get_item_type(<?php echo $x1; ?>,<?php echo $List->sch_id; ?>,this);"  >-->
										
										<select name="txt_measure_type" <?php echo $Disable; ?> class="textboxdisplay textboxstyle <?php echo $DisableClass; ?>" <?php echo ModuleRights("DEAC"); ?> id="txt_measure_type<?php echo $divid_incr; ?>"   onBlur="get_item_type(<?php echo $x1; ?>,<?php echo $List->sch_id; ?>,this);"  >
											<?php 
											foreach($ItemTypeArr as $Key=>$Value){
												if($Key == $List->measure_type){ $Sel = ' selected="selected"'; }else{ $Sel = ''; } 
												echo '<option value="'.$Key.'" '.$Sel.'>'.$Value.'</option>';
											}
											?>
											<!--<option value="">General</option>
											<option value="s">Steel</option>
											<option value="st">Structural Steel</option>-->
										</select>
										
										<input type="hidden" name="hide_result[]" id="hide_result<?php echo $x1; ?>" value="<?php echo $List->sch_id."@".$List->measure_type; ?>" >
										<input type="hidden" id="hid_subdivid<?php echo $x1; ?>" name="hid_subdivid" value="<?php echo $List->subdiv_id; ?>">
										<?php $divid_incr++; $x1++; } 
										?>	
										</td>
									</tr> 
									 
                                <?php $sheetid = $List->sheet_id; } } ?>
								</table>
                            </div>
							<input type="hidden" name="hid_txtboxcount" id="hid_txtboxcount" value="<?php echo $divid_incr; ?>" >
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php echo $sheetid; ?>" >
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="submit" name="back" value=" Back ">
								</div>
								<?php if(in_array('DEAC', $_SESSION['ModuleRights'])){ ?>
								<div class="buttonsection">
								<input type="submit" name="update" value=" Update ">
								</div>
								<?php } ?>
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
		document.getElementById("txt_measure_type"+x).style.height = div_height+"px";
		//var valu = document.getElementById("txt_measure_type"+x).value;
		//alert(valu);
	}
});
</script>
<script>
 function onkeydown(){
 alert()
    var ch= String.fromCharCode(event.keyCode);
    var filter=/[a-zA-Z]/;
    if(!filter.test(ch)){
       event.returnValue=false;
    }
 }

</script>
