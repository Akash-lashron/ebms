<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
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
$view = 0;
if($_POST["submit"] == " Validate "){
	$sheetid 	= $_POST['cmb_work_no'];
	$workName	= $_POST['workname'];
	$rbn 		= $_POST['txt_rbn'];
	$view 		= 1; 
	$count1 = 0; $count2 = 0; $count3 = 0; $count4 = 0; $count5 = 0; $count6 = 0;
	
	$select_meas_book_query = "select distinct * from measurementbook where sheetid = '$sheetid' and rbn >= '$rbn'";
	$select_meas_book_sql 	= mysql_query($select_meas_book_query);
	if($select_meas_book_sql == true){
		$count1 	= mysql_num_rows($select_meas_book_sql);
	}
	
	
	
	$select_send_acc_query 	= "select distinct * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_send_acc_sql 	= mysql_query($select_send_acc_query);
	if($select_send_acc_sql == true){
		$count2 	= mysql_num_rows($select_send_acc_sql);
		while($SAList = mysql_fetch_object($select_send_acc_sql)){
			if($SAList->mb_ac == "SC"){
				$count3++;
			}
			if($SAList->sa_ac == "SC"){
				$count3++;
			}
			if($SAList->ab_ac == "SC"){
				$count3++;
			}	
		}
	}
	if($count1 >0){
		/// Do not allow to reset
		$reset = 0;
	}else{
		if(($count2 >0)&&($count3 == 0)){
			/// Do not allow to reset
			$reset = 0;
		}else{
			/// Allow to reset
			$reset = 1;
		}
	}
}

if($_POST["btn_reset"] == " Reset "){
	$sheetid 	= $_POST['cmb_work_no'];
	$workName	= $_POST['workname'];
	$rbn 		= $_POST['txt_rbn'];
	$view 		= 1; 
	$count1 = 0; $count2 = 0; $count3 = 0; 
	
	$select_meas_book_query = "select distinct * from measurementbook where sheetid = '$sheetid' and rbn >= '$rbn'";
	$select_meas_book_sql 	= mysql_query($select_meas_book_query);
	if($select_meas_book_sql == true){
		$count1 	= mysql_num_rows($select_meas_book_sql);
	}
	
	
	
	$select_send_acc_query 	= "select distinct * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_send_acc_sql 	= mysql_query($select_send_acc_query);
	if($select_send_acc_sql == true){
		$count2 	= mysql_num_rows($select_send_acc_sql);
		while($SAList = mysql_fetch_object($select_send_acc_sql)){
			if($SAList->mb_ac == "SC"){
				$count3++;
			}
			if($SAList->sa_ac == "SC"){
				$count3++;
			}
			if($SAList->ab_ac == "SC"){
				$count3++;
			}	
		}
	}
	if($count1 >0){
		/// Do not allow to reset
		$reset = 0;
	}else{
		if(($count2 >0)&&($count3 == 0)){
			/// Do not allow to reset
			$reset = 0;
		}else{
			/// Allow to reset
			$reset = 1;
		}
	}
	if($reset == 1){
		$DeleteQuery1 	= "delete from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$rbn'";
		$DeleteSql1 	= mysql_query($DeleteQuery1);
		
		$DeleteQuery2 = "delete from mbookgenerate where sheetid = '$sheetid' and rbn = '$rbn'";
		$DeleteSql2 	= mysql_query($DeleteQuery2);
		
		$DeleteQuery3 = "delete from measurementbook_temp where sheetid = '$sheetid' and rbn = '$rbn'";
		$DeleteSql3 	= mysql_query($DeleteQuery3);
		
		$DeleteQuery4 = "delete from abstractbook where sheetid = '$sheetid' and rbn = '$rbn'";
		$DeleteSql4 	= mysql_query($DeleteQuery4);
		
		$DeleteQuery5 = "delete from mymbook where sheetid = '$sheetid' and rbn = '$rbn'";
		$DeleteSql5 	= mysql_query($DeleteQuery5);
		
		$DeleteQuery6 = "delete a.*, b.* from mbookheader_temp a, mbookdetail_temp b where a.sheetid = '$sheetid' and a.mbheaderid = b.mbheaderid";
		$DeleteSql6 	= mysql_query($DeleteQuery6);
		
		$DeleteQuery7 = "delete from generate_electricitybill where sheetid = '$sheetid' and rbn = '$rbn'";
		$DeleteSql7 	= mysql_query($DeleteQuery7);
		
		
		$DeleteQuery8 = "delete from generate_otherrecovery where sheetid = '$sheetid' and rbn = '$rbn'";
		$DeleteSql8 	= mysql_query($DeleteQuery8);
		
		
		$DeleteQuery9 = "delete from generate_waterbill where sheetid = '$sheetid' and rbn = '$rbn'";
		$DeleteSql9 	= mysql_query($DeleteQuery9);
		
		
		$DeleteQuery10 = "delete from recovery_release where sheetid = '$sheetid' and rbn = '$rbn'";
		$DeleteSql10 	= mysql_query($DeleteQuery10);
		
		$msg = "RAB Reset Successfully";
		$success = 1;
	}else{
		$msg = "Unable to Reset RAB";
		$success = 0;
	}
}

?>
<?php require_once "Header.html"; ?>
<script>
	function find_workname(){		
		var xmlHttp;
		var data;
		var i,j;
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_workname.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				if(data=="")
				{
					alert("No Records Found");
					document.form.workname.value='';	
				}
				else
				{	
					document.form.workname.value			=	name[0].trim();
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function goBack(){
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack(){ 
		window.history.forward(); 
	}
</script>
<style>
.table1{
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:13px;
}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->

<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
<div class="title">RAB Reset</div>
<div class="container_12">
<div class="grid_12">
<blockquote class="bq1" style="overflow:auto">
<form name="form" id="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div class="container">
<table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
	<tr><td width="22%">&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td> 
		<td  class="label">Work Short Name</td>
		<td  class="labeldisplay">
			<select name="cmb_work_no" id="cmb_work_no" onChange="find_workname()" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
			<option value="">---------------------- Select ----------------------</option>
			<?php echo $objBind->BindWorkOrderNo($sheetid); ?>
			</select>
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td  class="label">Name of the Work </td>
		<td  class="labeldisplay">
		<textarea name="workname" class="textboxdisplay txtarea_style" style="width: 400px; pointer-events: none; background-color:#E8E8E8" rows="5" readonly="readonly"><?php if($view == 1){ echo $workName; } ?></textarea>
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td  class="label">RAB </td>
		<td  class="labeldisplay">
		<input type="text" name="txt_rbn" id="txt_rbn" class="textboxdisplay" style="width:396px; height:25px;" value="<?php echo $rbn; ?>">
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr><td>&nbsp;</td><td></td><td id="val_rbn" style="color:red"></td></tr>
</table>
</div>
<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
	<div class="buttonsection">
		<input type="submit" data-type="submit" value=" Validate " name="submit" id="submit"   />
	</div>
</div>
<div align="center" <?php if($view == 0){ ?> style="display:none" <?php } ?>>
	<br/>
	<table width="90%"  bgcolor="#E8E8E8" class="table1" align="center">
		<tr class="label">
		<?php if($reset == 1){ ?>
			<td align="center" style="height:30px" valign="middle">Click Reset button to Reset RAB <span style="color:#F20024">(Please Confirm RAB before click Reset Button)</span></td>
			<td align="center" valign="middle" style="width:150px"><input type="submit" name="btn_reset" id="btn_reset" value=" Reset "></td>
		<?php }else{ ?>
			<td align="center" style="height:30px" valign="middle" colspan="2">This RAB already closed, unable to Reset.</td>
		<?php } ?>	
		</tr>
	</table>
</div>
</form>
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
	if(msg != ""){
		if(success == 1){
			swal("", msg, "success");
		}else{
			swal(msg, "", "");
		}
	}
};


$(function() {
	$.fn.validateworkorder = function(event) { 
		if($("#cmb_work_no").val()==""){ 
			var a="Please select the work order number";
			$('#val_work').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_work').text(a);
		}
	}
	$.fn.validateRAB = function(event) { 
		if($("#txt_rbn").val()==""){ 
			var a="Please Enter your RAB Number";
			$('#val_rbn').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else if($("#txt_rbn").val()==0){ 
			var a="Please Enter valid RAB Number";
			$('#val_rbn').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_rbn').text(a);
		}
	}
	$("#top").submit(function(event){
		$(this).validateworkorder(event);
		$(this).validateRAB(event);
	});
	$("#cmb_work_no").change(function(event){
    	$(this).validateworkorder(event);
    });
	$("#txt_rbn").keyup(function(event){
    	$(this).validateRAB(event);
    });
	
	/*$("#btn_reset").click(function(event){
    	//var x = BootstrapDialog.confirm('I want banana!');
		//alert(x);
		BootstrapDialog.show({
            title: 'Reset Confirmation',
            message: 'Are you sure want to Reset RAB !',
            buttons: [{
                label: 'OK',
                action: function(dialog) {
                    //dialog.close();
					$('#form1').submit();//event.returnValue = true;
                }
            },{
                label: 'Cancel',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
		event.preventDefault();
		event.returnValue = false;
    });*/
});
</script>
</body>
</html>

