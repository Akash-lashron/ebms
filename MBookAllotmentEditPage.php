<?php
session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
$msg = '';
$Allotid = $_GET['allotid'];

if(isset($_POST['Submit'])){
	$sheetid   = chop($_POST['workorderno']);
	$RemoveG  = $_POST['RemoveG'];
	$GenCheck = explode(",",$RemoveG);
	$RemoveS  = $_POST['RemoveS'];
	$StlCheck = explode(",",$RemoveS);
	$RemoveA  = $_POST['RemoveA'];
	$AbsCheck = explode(",",$RemoveA);
	$RemoveE  = $_POST['RemoveE'];
	$EscCheck = explode(",",$RemoveE);
	
	$userid	   = $_SESSION['userid'];
	$staffname = $_POST['staffname'];
	//echo $AbsCheck;exit;
	$TempG1 = 0; $TempG2 = 0; $TempS1 = 0; $TempS2 = 0; $TempA1 = 0; $TempA2 = 0; $TempE1 = 0; $TempE2 = 0; $Error = 0;
	if(count($GenCheck) > 0){
		$TempG1 = count($GenCheck);
		foreach($GenCheck as $Value){
			$GMBStr 	= $Value;
			$ExpGMBStr 	= explode("*@*",$GMBStr);
			$MbId 		= $ExpGMBStr[0];
			$Mbno 		= $ExpGMBStr[1];
			if($Mbno != ''){
				$InsertQuery = "delete from mbookallotment where sheetid='$sheetid' and allotmentid='$MbId'";
				$InsertSql	 = mysql_query($InsertQuery);
				if($InsertSql == true){ $TempG2++; }
			}else{
				$TempG1 = $TempG1 - 1;
			}
		}
	}
	if($TempG1 != $TempG2){ $Error++; }
	//echo $Mbno;exit;
	if(count($StlCheck) > 0){
		$TempS1 = count($StlCheck);
		foreach($StlCheck as $Value){
			$STLStr 	= $Value;
			$ExpSTLStr 	= explode("*@*",$STLStr);
			$MbId 		= $ExpSTLStr[0];
			$Mbno 		= $ExpSTLStr[1];
			if($Mbno != ''){
				$InsertQuery = "delete from mbookallotment where sheetid='$sheetid' and allotmentid='$MbId'";
				$InsertSql	 = mysql_query($InsertQuery);
				if($InsertSql == true){ $TempS2++; }
			}else{
				$TempS1 = $TempS1 - 1;
			}
		}
	}
	if($TempG1 != $TempG2){ $Error++; }
	 
	if(count($AbsCheck) > 0){
		$TempA1 = count($AbsCheck);
		foreach($AbsCheck as $Value){
			$AMBStr 	= $Value;
			$ExpAMBStr 	= explode("*@*",$AMBStr);
			$MbId 		= $ExpAMBStr[0];
			$Mbno 		= $ExpAMBStr[1];
			if($Mbno != ''){
				$InsertQuery = "delete from mbookallotment where sheetid='$sheetid' and allotmentid='$MbId'";
				$InsertSql	 = mysql_query($InsertQuery);
				if($InsertSql == true){ $TempA2++; }
			}else{
				$TempA1 = $TempA1 - 1;
			}
		}
	}
	if($TempA1 != $TempA2){ $Error++; }
	
	if(count($EscCheck) > 0){
		$TempE1 = count($EscCheck);
		foreach($EscCheck as $Value){
			$ESCStr 	= $Value;
			$ExpESCStr 	= explode("*@*",$ESCStr);
			$MbId 		= $ExpESCStr[0];
			$Mbno 		= $ExpESCStr[1];
			if($Mbno != ''){
				$InsertQuery = "delete from mbookallotment where sheetid='$sheetid' and allotmentid='$MbId'";
				$InsertSql	 = mysql_query($InsertQuery);
				if($InsertSql == true){ $TempE2++; }
			}else{
				$TempE1 = $TempE1 - 1;
			}
		}
	}
	if($TempE1 != $TempE2){ $Error++; }
	
	if($Error == 0){
		$msg = "MBook Deleted Sucessfully..!!";
		$success = 1;
	}else{
		$msg = "Error: Sorry Mbook not Deleted. Please try again..!";
	}
}	

if(($_GET['sheetid'] != '')&&($_GET['staffid'] != '')){
	$GMBArr = array(); $SMBArr = array(); $AMBArr = array(); $EMBArr = array();
	$SelectQuery = "select a.allotmentid, a.mbno, b.mbooktype from mbookallotment a inner join agreementmbookallotment b on (a.allotmentid = b.allotmentid) where 
					a.sheetid = '".$_GET['sheetid']."' and b.sheetid = '".$_GET['sheetid']."' and a.active = 1 and a.staffid = '".$_GET['staffid']."' and 
					b.active = 1 and NOT EXISTS (select c.mbno from mymbook c where c.mbno = a.mbno and c.sheetid = '".$_GET['sheetid']."') order by b.mbno";
	$SelectSql		= mysql_query($SelectQuery); 
	if($SelectSql == true){
		if(mysql_num_rows($SelectSql)>0){
			while($List = mysql_fetch_object($SelectSql)){
				if($List->mbooktype == 'G'){
					$GMBArr[$List->allotmentid] = $List->mbno;
				}elseif($List->mbooktype == 'S'){
					$SMBArr[$List->allotmentid] = $List->mbno;
				}elseif($List->mbooktype == 'A'){
					$AMBArr[$List->allotmentid] = $List->mbno;
				}elseif($List->mbooktype == 'E'){
					$EMBArr[$List->allotmentid] = $List->mbno;
				}
			}
		}
	}
	$staffid = $_GET['staffid'];
	$SelectSheetQuery 	= "select * from sheet where sheet_id = '".$_GET['sheetid']."'";
	$SelectSheetSql 	= mysql_query($SelectSheetQuery);
	if($SelectSheetSql == true){
		if(mysql_num_rows($SelectSheetSql)>0){
			$SheetList 		= mysql_fetch_object($SelectSheetSql);
			$WorkOrderNo 	= $SheetList->work_order_no;
			$WorkName 		= $SheetList->work_name;
			$ShortName 		= $SheetList->short_name;
		}
	}
	
	$SelectStaffQuery 	= "select * from staff where staffid = '".$_GET['staffid']."'";
	$SelectStaffSql 	= mysql_query($SelectStaffQuery);
	if($SelectStaffSql == true){
		if(mysql_num_rows($SelectStaffSql)>0){
			$StaffList 	= mysql_fetch_object($SelectStaffSql);
			$StaffName 	= $StaffList->staffname;
		}
	}
}
//print_r($GMBArr);exit;
?>
<?php require_once "Header.html"; ?>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
<script type="text/javascript">
	function workorderdetail()
    { 
    	var xmlHttp;
        var data;
        var i, j; //alert();
		document.form.txt_workname.value = '';
		document.form.txt_workorder_no.value = ''; 
        if (window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
        	xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "find_worder_details.php?workorderno=" + document.form.workorderno.value;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
        	if (xmlHttp.readyState == 4)
            {
            	data = xmlHttp.responseText

                if (data == "")
                {
                	alert("No Records Found");
                }
                else
                {
                    var name = data.split("*");
                    for(i = 0; i < name.length; i++)
                    {
                    	document.form.txt_workname.value 		= name[3];
						document.form.txt_workorder_no.value 	= name[5];
                    }

                }
            }
        }
        xmlHttp.send(strURL);
	}
 	function GetAllStaffList()
 	{ 
		var xmlHttp;
		var data;
		var i, j;
		$("#staffname").chosen("destroy");
		document.form.staffname.length = 0;
		var optn1 = document.createElement("option");
			optn1.value = "";
			optn1.text = " ------ Select Staff Name ------ ";
		document.form.staffname.options.add(optn1);
		
		if (window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) // For Internet Explorer
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL = "find_staff_list.php?workorderno=" + document.form.workorderno.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function ()
		{
			if (xmlHttp.readyState == 4)
			{
				data = xmlHttp.responseText
				//alert(data)
				if (data == "")
				{
					alert("No Records Found");
					$("#staffname").chosen();
				}
				else
				{
					var name 		= data.split("@*@");
					document.form.staffname.length = 0;
					var optn2 = document.createElement("option");
						optn2.value = "";
						optn2.text = " ------ Select Staff Name ------ ";
					document.form.staffname.options.add(optn2);
					for(i = 0; i < name.length; i+=2)
					{
						var optn3 = document.createElement("option")
							optn3.value = name[i];
							optn3.text = name[i+1];
						document.form.staffname.options.add(optn3)  
					}
					$("#staffname").chosen();
				}
			}
		}
		xmlHttp.send(strURL);
 	}
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
	function goBack()
	{
		url = "MBookAllotmentEdit.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
.chosen-container-multi .chosen-choices li.search-choice{
	/*background-image: -webkit-gradient(linear,50% 0,50% 100%,color-stop(20%,#0D4493),color-stop(50%,#115AC4),color-stop(52%,#1D6BDA),color-stop(100%,#043F94));
	background-image: -webkit-linear-gradient(#0D4493 20%,#115AC4 50%,#1D6BDA 52%,#043F94 100%);
	background-image: -moz-linear-gradient(#0D4493 20%,#115AC4 50%,#1D6BDA 52%,#043F94 100%);
	background-image: -o-linear-gradient(#0D4493 20%,#115AC4 50%,#1D6BDA 52%,#043F94 100%);
	background-image: linear-gradient(
	#0D4493 20%,#115AC4 50%,#1D6BDA 52%,#043F94 100%);*/
	/*background:#0750B7;
	color:#FFFFFF;
	border:1px solid #013278;
	box-shadow:none;*/
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Staff-Wise MBook Allotment</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto"> 
						<div align="right"><a href="MBookAllotment.php">Add New</a></div>
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="container">
								<div class="row ">
									<div class="div2">&nbsp;</div>
									
									<div class="div8">
										<div class="row"><div class="div12" style="margin-top:0px;"><div class="row divhead" align="center">Staff-Wise General / Steel / Abstract / Escalation MBook Allotment</div></div></div>
										<div class="row innerdiv">
											<div class="row">
												<div class="div4">
													<label for="fname">Work Short Name</label>
												</div>
												<div class="div8">
													<select id="workorderno" name="workorderno" class="tboxclass" onchange='workorderdetail();GetAllStaffList()'>
														<?php if($_GET['sheetid'] != ''){ ?>
														<option value="<?php echo $_GET['sheetid']; ?>" selected="selected"><?php echo $ShortName; ?></option>
														<?php }else{ ?>
														<option value=""> ------ Select Work Short Name ------ </option>
														<?php echo $objBind->BindWorkOrderNo($_GET['sheetid']);?>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4">
													<label for="fname">Work Order No.</label>
												</div>
												<div class="div8">
													<input type="text" name='txt_workorder_no' id='txt_workorder_no' class="tboxclass" readonly="" value="<?php if($_GET['sheetid'] != ''){ echo $WorkOrderNo; } ?>">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4">
													<label for="fname">Name of Work</label>
												</div>
												<div class="div8">
													<textarea name='txt_workname' id='txt_workname' class="tboxclass" readonly="" rows="2"><?php if($_GET['sheetid'] != ''){ echo $WorkName; } ?></textarea>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4">
													<label for="fname">Staff Name</label>
												</div>
												<div class="div8">
													<select id="staffname" name="staffname" class="tboxclass">
														<?php if(($_GET['staffid'] != '')&&($_GET['sheetid'] != '')){ ?>
															<option value="<?php echo $_GET['staffid']; ?>" selected="selected"><?php echo $StaffName; ?></option>
														<?php }else{ ?>
															<option value=""> ------ Select Staff Name ------ </option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div3" align="center">
													<div class="innerdiv2">
														<div class="row divhead" align="center">General</div>
														<div class="row innerdiv" align="center">
															<select id="mbookG" name="mbookG[]" class="tboxclass mbno" multiple="multiple">
															<?php 
															if($_GET['sheetid'] != ''){
																if(isset($GMBArr)){
																	foreach($GMBArr as $Key => $Value){
																		$MbValueStr = $Key."*@*".$Value;
																		echo '<option value="'.$MbValueStr.'" selected="selected">'.$Value.'</option>';
																	}
																}
															} 
															?>
															</select>
														</div>
													</div>
												</div>
												<div class="div3" align="center">
													<div class="innerdiv2">
														<div class="row divhead" align="center">Steel</div>
														<div class="row innerdiv" align="center">
															<select id="mbookS" name="mbookS[]" class="tboxclass mbno" multiple="multiple">
															<?php 
															if($_GET['sheetid'] != ''){
																if(isset($SMBArr)){
																	foreach($SMBArr as $Key => $Value){
																		$MbValueStr = $Key."*@*".$Value;
																		echo '<option value="'.$MbValueStr.'" selected="selected">'.$Value.'</option>';
																	}
																}
															} 
															?>
															</select>
														</div>
													</div>
												</div>
												<div class="div3" align="center">
													<div class="innerdiv2">
														<div class="row divhead" align="center">Abstract</div>
														<div class="row innerdiv" align="center">
															<select id="mbookA" name="mbookA[]" class="tboxclass mbno" multiple="multiple">
															<?php 
															if($_GET['sheetid'] != ''){
																if(isset($AMBArr)){
																	foreach($AMBArr as $Key => $Value){
																		$MbValueStr = $Key."*@*".$Value;
																		echo '<option value="'.$MbValueStr.'" selected="selected">'.$Value.'</option>';
																	}
																}
															} 
															?>
															</select>
														</div>
													</div>
												</div>
												<div class="div3" align="center">
													<div class="innerdiv2">
														<div class="row divhead" align="center">Escalation</div>
														<div class="row innerdiv" align="center">
															<select id="mbookE" name="mbookE[]" class="tboxclass mbno" multiple="multiple">
															<?php 
															if($_GET['sheetid'] != ''){
																if(isset($EMBArr)){
																	foreach($EMBArr as $Key => $Value){
																		$MbValueStr = $Key."*@*".$Value;
																		echo '<option value="'.$MbValueStr.'" selected="selected">'.$Value.'</option>';
																	}
																}
															} 
															?>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row"><span style="color:#FA055B; font-weight:bold; font-size:11px;"> * Unused MBook only displayed in above list.</span></div>
									</div>
									
								  	<div class="div2">&nbsp;</div>
								</div>
							</div>
							<input type="hidden" name="RemoveG" id="RemoveG" class="remove">
							<input type="hidden" name="RemoveS" id="RemoveS" class="remove">
							<input type="hidden" name="RemoveA" id="RemoveA" class="remove">
							<input type="hidden" name="RemoveE" id="RemoveE" class="remove">
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<!--<div class="buttonsection">
									<input type="submit" class="backbutton" name="btn_view" id="btn_view" value="View"/>
								</div>-->
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<?php if(in_array('MWOA', $_SESSION['ModuleRights'])){ ?>
								<div class="buttonsection">
									<input type="submit" data-type="submit" value=" Submit " name="Submit" id="Submit" onClick="return validation()"/>
								</div>
								<?php } ?>
							</div>           
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
         <!--==============================footer=================================-->
		<?php include "footer/footer.html"; ?>
        <script>
		$(document).ready(function() {
			$("#workorderno").chosen();
			$("#staffname").chosen();
			$("#mbookG").chosen();
			$("#mbookS").chosen();
			$("#mbookA").chosen();
			$("#mbookE").chosen();
			var msg = "<?php echo $msg; ?>";
			var success = "<?php echo $success; ?>";
			var titletext = "";
			document.querySelector('#top').onload = function(){
			if(msg != "")
			{
				if(success == 1)
				{
					BootstrapDialog.alert(msg);
				}
				else
				{
					BootstrapDialog.alert(msg);
				}
					
			}
			};
			$("#btn_view").click(function(e){ 
			    var sheetid 	= $('#workorderno').val();
				if(sheetid!==""){
					$(location).attr('href','MBookAllotmentEdit.php?sheetid='+sheetid);
				}else{
				    BootstrapDialog.alert('Please Select Work Name ');
					e.preventDefault();
					return false; 
				}
			});
			
			$.fn.Validation = function(event) { 
				var ch = 0;
				$(".remove").each(function(index){
					var remove = $(this).val();
					if(remove != 0){
						ch++;
					}
				});
				if($("#workorderno").val() == ''){
					var AlertMsg = "Please Select Work Order No.";
					BootstrapDialog.alert(AlertMsg);
					event.preventDefault();
					event.returnValue = false;
				}else if(ch == 0){
					var AlertMsg = "Please Select Atleast One MBook to Remove";
					BootstrapDialog.alert(AlertMsg);
					event.preventDefault();
					event.returnValue = false;
				}
			}
			$("#top").submit(function(event){
				var GMB = []; var SMB = []; var AMB = []; var EMB = [];
				$("#mbookG option").each(function(){
					if(this.selected == false){ GMB.push($(this).val()); }
				});
				$("#mbookS option").each(function(){
					if(this.selected == false){ SMB.push($(this).val()); }
				});
				$("#mbookA option").each(function(){
					if(this.selected == false){ AMB.push($(this).val()); }
				});
				$("#mbookE option").each(function(){
					if(this.selected == false){ EMB.push($(this).val()); }
				});
				var GMBStr = GMB.toString();
				var SMBStr = SMB.toString();
				var AMBStr = AMB.toString();
				var EMBStr = EMB.toString();
				$("#RemoveG").val(GMBStr); $("#RemoveS").val(SMBStr); $("#RemoveA").val(AMBStr); $("#RemoveE").val(EMBStr);
				$(this).Validation(event);
			});
			$("#workorderno").change(function(event){
				var sheetid = $(this).val();
				$("#mbookG").chosen('destroy');
				$("#mbookS").chosen('destroy');
				$("#mbookA").chosen('destroy');
				$("#mbookE").chosen('destroy');
				$('#mbookG').empty();
				$('#mbookS').empty();
				$('#mbookA').empty();
				$('#mbookE').empty();
				$.ajax({ 
					type: 'POST', 
					url: 'find_mbookno_staff.php', 
					data: { sheetid: sheetid }, 
					dataType: 'json',
					success: function (data) {   //alert(data);
						if(data != null){
							var GMB = data['GMB'];
							var SMB = data['SMB'];
							var AMB = data['AMB'];
							var EMB = data['EMB']; 
							$.each(GMB, function(index, element) {
								var MBNoValue = element.allotmentid+"*@*"+element.mbno;
								$("#mbookG").append('<option value="'+MBNoValue+'">'+element.mbno+'</option>');
							});
							$.each(SMB, function(index, element) {
								var MBNoValue = element.allotmentid+"*@*"+element.mbno;
								$("#mbookS").append('<option value="'+MBNoValue+'">'+element.mbno+'</option>');
							});
							$.each(AMB, function(index, element) {
								var MBNoValue = element.allotmentid+"*@*"+element.mbno;
								$("#mbookA").append('<option value="'+MBNoValue+'">'+element.mbno+'</option>');
							});
							$.each(EMB, function(index, element) {
								var MBNoValue = element.allotmentid+"*@*"+element.mbno;
								$("#mbookE").append('<option value="'+MBNoValue+'">'+element.mbno+'</option>');
							});
							$("#mbookG").chosen();
							$("#mbookS").chosen();
							$("#mbookA").chosen();
							$("#mbookE").chosen();
						}
					}
				});
			});
			
		});
		</script>
    </body>
</html>

