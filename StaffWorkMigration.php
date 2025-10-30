<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
require_once 'library/common.php';
checkUser();
$msg = '';
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
$popupwindow =0;
/*if(isset($_POST["submit"])) 
{
	$sheetid 	= $_POST['cmb_shortname'];
	$staff 		= $_POST['txt_multi_staff'];
	$level 		= $_POST['txt_level'];
	if($staff != ""){
		$update_sheet_query = "update sheet set assigned_staff = '$staff' where sheet_id = '$sheetid'";
		$update_sheet_sql = mysql_query($update_sheet_query );
		//echo $update_sheet_query;
	}
	$rbn = 0;
	$SelectRbnQuery  = "select distinct(rbn) from measurementbook_temp where sheetid = '$sheetid'";
	$SelectRbnSql 	 = mysql_query($SelectRbnQuery);
	if($SelectRbnSql == true){
		if(mysql_num_rows($SelectRbnSql)>0){
			$RBNList = mysql_fetch_object($SelectRbnSql);
			$rbn 	 = $RBNList->rbn;
		}
	}
	if($rbn == 0){
		$SelectRbnQuery1  = "select max(rbn) as rab from measurementbook where sheetid = '$sheetid'";
		$SelectRbnSql1 	  = mysql_query($SelectRbnQuery1);
		if($SelectRbnSql1 == true){
			if(mysql_num_rows($SelectRbnSql1)>0){
				$RBNList1 = mysql_fetch_object($SelectRbnSql1);
				$rbn 	  = $RBNList1->rab;
			}
		}
	}
	if($level != ""){
		$InsertLevelQuery 	= "insert into check_measure_level_assign set sheetid = '$sheetid', rbn = '$rbn', check_meas_level = '$level', assign_date = NOW(), active = 1, staffid = ".$_SESSION['sid'];
		$InsertLevelSql 	= mysql_query($InsertLevelQuery);
	}//echo $InsertLevelQuery ;
	if(($update_sheet_sql == true)&&($InsertLevelSql == true)){
		$msg = "Staff and Level Assigned Successfully";
		$success = 1;
	}else{
		$msg = "Staff and Level Not Assigned";
		$success = 0;
	}
}
*/
$StaffListArr = array();
$SelectQuery3 	= "select a.staffid, a.staffcode, a.staffname, b.designationname from staff a inner join designation b on (a.designationid = b.designationid) where a.active = '1'";
$SelectSql3 	= mysql_query($SelectQuery3);
if($SelectSql3 == true){
	if(mysql_num_rows($SelectSql3)>0){
		while($StaffList = mysql_fetch_object($SelectSql3)){
			$StaffListArr[$StaffList->staffid][0] = $StaffList->staffcode;
			$StaffListArr[$StaffList->staffid][1] = $StaffList->staffname;
			$StaffListArr[$StaffList->staffid][2] = $StaffList->designationname;
		}
	}
}
if(isset($_POST["submit"])) 
{
    $sheetid 	= $_POST['cmb_shortname'];
	$ext_staff 	= $_POST['cmb_ext_staff'];
	$new_staff  = $_POST['cmb_change_staff'];
	 $ResArr =array();
	 $SelectSidQuery1  = "select * from sheet where sheet_id = '$sheetid' ";
		$SelectSidSql1 	  = mysql_query($SelectSidQuery1);
		if($SelectSidSql1 == true){
			if(mysql_num_rows($SelectSidSql1)>0){
				$staffList = mysql_fetch_object($SelectSidSql1);
				$staff_id 	      = $staffList->assigned_staff;
				$ExpAssignedStaff = explode(',',$staff_id);
				foreach($ExpAssignedStaff as $Key => $Value){
				   if($Value!=$ext_staff){
					  array_push($ResArr,$Value);
				   }
				}
				array_push($ResArr,$new_staff);
			    $AssignedStaff= array($ResArr);
				$AssignedStaffStr = implode(",",$ResArr);
				$update_sheet_query = "update sheet set assigned_staff = '$AssignedStaffStr' where sheet_id = '$sheetid'";
		        $update_sheet_sql = mysql_query($update_sheet_query);
			}
		}
		$select_meas_gen_staff_query = "select distinct rbn from mbookgenerate_staff where sheetid = '$sheetid' and rbn = (select max(rbn) from mbookgenerate_staff where sheetid = '$sheetid') ";
	    $select_meas_gen_staff_sql 	 = mysql_query($select_meas_gen_staff_query);
		if($select_meas_gen_staff_sql == true){
		   $rbnList 		= mysql_fetch_object($select_meas_gen_staff_sql);
		   $rbn 	        = $rbnList->rbn;
	     }
		$update_staff_mig_query = "insert into staff_migration set sheetid = '$sheetid',from_staffid='$ext_staff',to_staffid='$new_staff',
		                           which_rbn='$rbn',migrated_on= NOW(), staffid = ".$_SESSION['sid'];
		$update_staff_mig_sql = mysql_query($update_staff_mig_query);
		
		$update_staff_query = "update abstractbook set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update abstractbook_dt set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update check_measurement_master set staffid = '$new_staff' where sheetid ='$sheetid' and staffid ='$ext_staff'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update generate_waterbill set staffid = '$new_staff',userid = '$userid' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update mbookallotment set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update mbookgenerate set staffid = '$new_staff',userid = '$userid' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update mbookgenerate_staff set staffid = '$new_staff',userid = '$userid' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update measurementbook set staffid = '$new_staff',userid = '$userid' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update measurementbook_temp set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update mymbook set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update secured_advance set staffid = '$new_staff',userid = '$userid' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update mbookheader set staffid = '$new_staff',userid = '$userid' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update mbookheader_temp set staffid = '$new_staff',userid = '$userid' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		
		$update_staff_query = "update  base_index set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update electricity_recovery set staffid = '$new_staff' where sheetid ='$sheetid' ";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update generate_waterbill set staffid = '$new_staff' where sheetid ='$sheetid' ";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update escalation set staffid = '$new_staff' where sheetid ='$sheetid' ";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update escalation_10ca_details set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update escalation_revised set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update escalation_tcc set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update escalation_tcc_details set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update esc_consumption_10ca set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update esc_consumption_10ca_master set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update generate_otherrecovery set staffid = '$new_staff' where sheetid ='$sheetid'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update price_index set staffid = '$new_staff' where sheetid ='$sheetid' and staffid ='$ext_staff'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update recovery_release set staffid = '$new_staff' where sheetid ='$sheetid' and staffid ='$ext_staff'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update bill_register set sent_by = '$new_staff' where sheetid ='$sheetid' and sent_by ='$ext_staff'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update mobilization_advance set staffid = '$new_staff' where sheetid ='$sheetid' and staffid ='$ext_staff'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update send_acc_supp_doc set staffid = '$new_staff' where sheetid ='$sheetid' and staffid ='$ext_staff'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update zone set staffid = '$new_staff' where sheetid ='$sheetid' and staffid ='$ext_staff'";
		$update_staff_sql = mysql_query($update_staff_query);
		$update_staff_query = "update water_recovery set staffid = '$new_staff' where sheetid ='$sheetid' and staffid ='$ext_staff'";
		$update_staff_sql = mysql_query($update_staff_query);
		
		
		if(($update_staff_sql == true)&&($update_staff_sql == true)){
		    $msg = "Staff Updated Successfully";
		    $success = 1;
			$FromStaff 	= $StaffListArr[$ext_staff][1]." - ".$StaffListArr[$ext_staff][2];
			$ToStaff 	= $StaffListArr[$new_staff][1]." - ".$StaffListArr[$new_staff][2];
			$WorkTransActStatusStr = "Work Migrated from ".$FromStaff." to ".$ToStaff;
			//UpdateWorkTransaction($sheetid,0,"W",$WorkTransActStatusStr,"");
	    }else{
		    $msg = "Staff Not Updated";
		    $success = 0;
	    }
	
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "MyView.php";
		window.location.replace(url);
	}
    function workorderdetail()
    { 
		var xmlHttp;
        var data;
        var i, j;
		document.form.txt_multi_staff.value = "";
        if (window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
            xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "find_worder_details.php?workorderno=" + document.form.cmb_shortname.value;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
            if (xmlHttp.readyState == 4)
            {
                data = xmlHttp.responseText
                if (data == "")
                {
                    //alert("No Records Found");
                }
                else
                {
                   	var name = data.split("*");
					document.form.txt_workname.value 		= name[3];
					document.form.txt_workorder.value 		= name[5];
					document.form.txt_multi_staff.value 	= name[6];
                }
            }
        }
		xmlHttp.send(strURL);
	}
	function CheckMeasureLevel()
    { 
		var xmlHttp;
        var data;
        var i, j;
		document.getElementById("level_check1").setAttribute('data-check','N');
		document.getElementById("level_check2").setAttribute('data-check','N');
		document.getElementById("level_check3").setAttribute('data-check','N');
		document.getElementById("level_check4").setAttribute('data-check','N');
		
		document.getElementById("level_check1").classList.remove('active');
		document.getElementById("level_check2").classList.remove('active');
		document.getElementById("level_check3").classList.remove('active');
		document.getElementById("level_check4").classList.remove('active');
		
        if (window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
            xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "find_check_measure_level.php?workorderno=" + document.form.cmb_shortname.value;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
            if (xmlHttp.readyState == 4)
            {
                data = xmlHttp.responseText;
				
                if (data != "")
                {
					var Str 		= data.split("*");
					var LevelStr	= Str[0];
					var rbn 		= Str[1];
					//alert(rbn);
					if(LevelStr != ""){
						var Level 		= LevelStr.split(",");
						for(i=0; i<Level.length; i++ ){
							var levelid = Level[i];
							document.getElementById("level_check"+levelid).setAttribute('data-check','Y');
							document.getElementById("level_check"+levelid).classList.add('active');	
						}
					}
					document.form.txt_rbn.value = rbn;
					
					//GetRbn(rbn);
                }
            }
        }
		xmlHttp.send(strURL);
	}
	
	 function GetStaffNames()
    { 
	//alert()
    	var xmlHttp;
        var data;
		var i, j;
		
       document.form.cmb_ext_staff.value = "";
        if (window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
            xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "find_ext_staff.php?sheetid=" + document.form.cmb_shortname.value;
		//alert(strURL)
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
                	//alert("No Records Found");
					$('#cmb_ext_staff').chosen('destroy');
					document.form.cmb_ext_staff.length = 0;
					var optn = document.createElement("option");
					optn.value = "";
					optn.text = "--------- Select ---------";
					document.form.cmb_ext_staff.options.add(optn);
					$('#cmb_ext_staff').chosen();
                }
                else
                {
                    var name 		= data.split("*");
					$('#cmb_ext_staff').chosen('destroy');
					document.form.cmb_ext_staff.length = 0;
					var optn = document.createElement("option");
					optn.value = "";
					optn.text = "--------- Select ---------";
					document.form.cmb_ext_staff.options.add(optn);
					document.form.txt_ext_staffid.value 		= name[1];
                   /* for(i = 0; i < name.length; i++)
                    {*/
						var optn = document.createElement("option")
					    optn.value = name[1];
						optn.text  = name[0];
						document.form.cmb_ext_staff.options.add(optn)  
						document.form.txt_ext_staffid.value= name[1];
                   	//}
					$('#cmb_ext_staff').chosen();
                }
            }
        }
        xmlHttp.send(strURL);
    }
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	table{
		margin-top:15px;
		color:#0053A6;
	}
	.note{
		text-decoration: none;
		padding: 2px 14px;
		color: #fff;
		border: none;
		background-color: transparent;
		font-size: 13px;
		outline:none;
	}
	.col-status{
		float: left;
		position: relative;
		min-height: 1px;
		padding-right: 2px;
		padding-left: 2px;
		width:24%;
	}
	.well-A{
		background-color:#F4F5F7;/*#038BCF*/
		border: 2px solid #055DAB;/*038BCF*/
		color:#032FAD;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		cursor:pointer;
		border-radius:8px;
		margin-right:2px;
		padding:8px 8px;
	}
	.well-A.active{
		background-color:#055DAB;
		border: 2px solid #055DAB;
		color:#fff;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Staff - Work Migration</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="container">
                                <table width="100%" align="center" >
                                    <tr>
										<td width="23%">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td> 
									 	<td class="label">Work Short Name </td>
									 	<td class="labeldisplay">
											<select name="cmb_shortname" id="cmb_shortname" onChange="workorderdetail();GetStaffNames();CheckMeasureLevel();" class="textboxdisplay" style="width:465px;" tabindex="7">
												<option value="">--------------- Select ---------------</option>
												<?php echo $objBind->BindWorkOrderNoListStaff(0);?>
											</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
								 	<tr>
										<td>&nbsp;</td>
										<td></td>
										<td id="val_work" style="color:red" colspan="3"></td>
									</tr>
									<tr>
									   	<td>&nbsp;</td>
									   	<td class="label">Work Order No. </td>
									   	<td class="labeldisplay"><input type="text" name="txt_workorder" id="txt_workorder" readonly="" rows="6" class="textboxdisplay" style="width: 465px;"></td>
									   	<td>&nbsp;</td>
									   	<td>&nbsp;</td>
									</tr>
                                    <tr>
										<td>&nbsp;</td>
										<td></td>
										<td id="val_work" style="color:red" colspan="3"></td>
									</tr>
								 	<tr>
									   	<td>&nbsp;</td>
									   	<td class="label">Name of the Work </td>
									   	<td class="labeldisplay"><textarea name="txt_workname" id="txt_workname" readonly="" rows="6" class="textboxdisplay" style="width: 465px;"></textarea></td>
									   	<td>&nbsp;</td>
									   	<td>&nbsp;</td>
									</tr>
                                    <tr>
										<td>&nbsp;</td>
										<td></td>
										<td id="val_work" style="color:red" colspan="3"></td>
									</tr>
									<tr>
										<td>&nbsp;</td> 
									 	<td class="label">Existing Staff</td>
									 	<td class="labeldisplay">
											<select name="cmb_ext_staff" id="cmb_ext_staff"  class="textboxdisplay" style="width:250px;height:22px;" tabindex="7">
												<option value="">--------- Select ---------</option>
												<?php //echo $objBind->BindWorkOrderNoListStaff(0);?>
											</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
									 <tr>
										<td>&nbsp;</td>
										<td></td>
										<td id="val_ext" style="color:red" colspan="3"></td>
									</tr>
									<tr>
										<td>&nbsp;</td> 
									 	<td class="label">Migrate to </td>
									 	<td class="labeldisplay">
											<select name="cmb_change_staff" id="cmb_change_staff"  class="textboxdisplay" style="width:250px;height:22px;" tabindex="7" >
												<option value="">--------- Select ---------</option>
												<?php echo $objBind->BindStaff($Mstaffid,1);?>
											</select>
										</td>
									 	<td>&nbsp;</td>
										<td>&nbsp;</td>
								 	</tr>
									 <tr>
										<td>&nbsp;</td>
										<td></td>
										<td id="val_new" style="color:red" colspan="3"></td>
									</tr>
									<!--<tr>
										<td>&nbsp;</td>
										<td colspan="4">
											<div class="col-md-3">&nbsp;</div>
											<div class="col-md-3 well-A active" align="center" id="AssignStaff">Click here to assign staff </div>
											<div class="col-md-4">&nbsp;</div>
										</td>
									</tr>-->
									<tr>
										<td colspan="5">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="5">&nbsp;</td>
									</tr>
									<!--<tr>
										<td>&nbsp;</td>
										<td colspan="4">
											<div class="col-md-2 well-A level" id="level_check1" data-level='1' data-check='N' align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> Scientific Assistant </div>
											<div class="col-md-2 well-A level" id="level_check2" data-level='2' data-check='N' align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> Site Engineer</div>
											<div class="col-md-2 well-A level" id="level_check3" data-level='3' data-check='N' align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> Engineer Incharge</div>
											<div class="col-md-3 well-A level" id="level_check4" data-level='4' data-check='N' align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> Superintendent Engineer</div>
										</td>
									</tr>-->
									<tr>
										<td colspan="5">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="5" align="center">
											<div class="col-md-12" align="center">
											<input type="submit" data-type="submit" value=" Save " name="submit" id="submit"/> 
											<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
											</div>
										</td>
									</tr>
                                </table>
                            </div>
							<input type="hidden" name="txt_ext_staffid" id="txt_ext_staffid">
							<input type="hidden" name="txt_multi_staff" id="txt_multi_staff" value="">
							<input type="hidden" name="txt_multi_section" id="txt_multi_section" value="">
							<input type="hidden" name="txt_level" id="txt_level" value="">
					</form>
          		</blockquote>
			</div>
		</div>
	</div>
<style>
.smallbox{
	background:#fff; 
	color:#FF061F; 
	padding-right:5px; 
	padding-left:5px; 
	font-weight:bold; 
	font-size:13px;
	border:1px solid #B5B5B5;
	cursor:pointer;
	border-radius:8px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
.smallbox:hover{
	background:#FF061F; 
	color:#fff;
	border:1px solid #FF061F;
}
.smallbox1{
	background:#E11069; 
	color:#fff; 
	padding-right:5px; 
	padding-left:5px; 
	font-weight:bold; 
	font-size:12px;
	border:1px solid #E11069;
	cursor:pointer;
	border-radius:8px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
.smallbox1:hover{
	background:#E11069; 
	color:#fff;
	border:1px solid #E11069;
}
.smallbox2{
	background:#E3C414; 
	color:#fff; 
	padding-right:5px; 
	padding-left:5px; 
	font-weight:bold; 
	font-size:12px;
	border:1px solid #E3C414;
	cursor:pointer;
	border-radius:8px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
.smallbox2:hover{
	background:#E3C414; 
	color:#fff;
	border:1px solid #E3C414;
}
.searchbox{
	border:1px solid #359AFF;
	color:#004DFF;
	border-radius:5px;
}
</style>
<?php 
$count = 0;
$select_staff_query = "select * from staff where sectionid = 1 ORDER BY staffid ASC";
$select_staff_sql 	= mysql_query($select_staff_query);
if($select_staff_sql == true){
	if(mysql_num_rows($select_staff_sql)>0){
		$count = 1;
	}
}
?>
<div id="staff_list" style="display:none">
	<div class="blank-page-content">
		<div class="outer-w3-agile mt-3 margin-t1">
			<p class="paragraph-agileits-w3layouts">
				<div class="card-body" align="right" style="padding-top:0px;">
					<div class="list-group">
						<div class="row staff_list">
							<div class="col-md-12 padding-1">
								<div class="col-md-12 padding-1" style="text-align:left">
									<?php
									$Alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"); 
									foreach ($Alpha as $Alphabets){
										echo "<span class='smallbox' id='".$Alphabets."'>".$Alphabets."</span>&nbsp;";
									} 
									?>
									<input type="text" name="txt_search" id="txt_search" class="searchbox" placeholder=' Search Name here...' value=""/>
									<span style="height:10px; font-size:12px;;">
										<span class="smallbox1">Selected</span>
										<span class="smallbox2">Highlighted</span>
									</span>
								</div>
							</div>
						<?php $slno = 1; while($list = mysql_fetch_object($select_staff_sql)){ 
						 $select_role_query ="select role_name from staffrole where sroleid='$list->sroleid'";
						 //echo $select_role_query;
						 $select_role_sql 	= mysql_query($select_role_query);
                            if($select_role_sql == true){
								if(mysql_num_rows($select_role_sql)>0){
					                 $ROList = mysql_fetch_object($select_role_sql);
					                 $role_name 	 = $ROList->role_name;
									// echo $role_name;
								}
							
                            }
						?>
							<div class="col-md-2 padding-1 multi-mark" align="left">
								<a class="list-group-item media d-flex justify-content-between align-items-center outer-w3-agile col no-box-shaddow font-1" style="padding:5px;">
									<div class="media-body d-flex justify-content-between align-items-center">
										<div class="lg-item-heading">
											<input type="checkbox" id="IC<?php echo $list->staffid; ?>" class='staff_icno' value="<?php echo $list->staffid; ?>" style="display:none" name="checkbox[]" data-section="<?php echo $list->sectionid; ?>" data-sname="<?php echo $list->staffname; ?>" data-desig="<?php echo $role_name; ?>"/>
											<i style="font-size:16px; color:#E2E2E2; padding-top:3px;" class="fa">&#xf058;</i>
											<?php echo $list->staffname; ?><br/><?php echo $role_name; ?>
										</div>
									</div>
								</a>
							</div>
						<?php $slno++; } ?>
						<script>
							$('.multi-mark').click(function(){
								$('#txt_multi_staff').val(''); 
								var $checks = $(this).find('input:checkbox[class=staff_icno]');
									$checks.prop("checked", !$checks.is(":checked"));
								var selected = []; var section = [];
								$('#modal-content input:checked').each(function() {   
									if($(this).is(':checked')){
										selected.push($(this).val());
										var sec = $(this).attr('data-section');
										section.push(sec);
									}
								});
								$('#txt_multi_staff').val(selected.join(","));
								$('#txt_multi_section').val(section.join(","));
																	
								if($checks.is(":checked")){ //alert("checked");
									$(this).find("div").css("background", "#E11069");
									$(this).find("a").css("background", "#E11069");
									$(this).find("div").css("color", "white");
									$(this).find("a").css("color", "white");
								}else{ //alert("not checked");
									$(this).find("div").css("background", "white");
									$(this).find("a").css("background", "white");
									$(this).find("div").css("color", "#015BB6");
									$(this).find("a").css("color", "#015BB6");
								}
							});
																
							$('.smallbox').click(function(){
								var SelectAlpha = $(this).attr('id');
								$("input:checkbox[name='checkbox[]']").each(function(){   
									var StaffName = $(this).attr('data-sname');
										StaffName = $.trim(StaffName);
									var FirstAlpha = StaffName.charAt(0);
									var RoleName = $(this).attr('data-desig');
										RoleName = $.trim(RoleName);
									var FirstAlpha2 = RoleName.charAt(0);
									var BgColor = $(this).parents(':eq(2)').css("backgroundColor");
									if(SelectAlpha == FirstAlpha){
										if((BgColor != 'rgb(227, 196, 20)')&&(BgColor != 'rgb(225, 16, 105)')){
											$(this).parents(':eq(2)').css("background", "#E3C414");
											$(this).parents(':eq(1)').css("background", "#E3C414");
											$(this).parents(':eq(0)').css("background", "#E3C414");
											$(this).find("a").css("background", "#E3C414");
											$(this).parents(':eq(2)').css("color", "#222221");
											$(this).find("a").css("color", "#222221");
										}
									}
									if(SelectAlpha == FirstAlpha2){
										if((BgColor != 'rgb(227, 196, 20)')&&(BgColor != 'rgb(225, 16, 105)')){
											$(this).parents(':eq(2)').css("background", "#E3C414");
											$(this).parents(':eq(1)').css("background", "#E3C414");
											$(this).parents(':eq(0)').css("background", "#E3C414");
											$(this).find("a").css("background", "#E3C414");
											$(this).parents(':eq(2)').css("color", "#222221");
											$(this).find("a").css("color", "#222221");
										}
									}else{
										if((BgColor == 'rgb(227, 196, 20)')||(BgColor == 'rgb(225, 16, 105')){
											$(this).parents(':eq(2)').css("background", "white");
											$(this).parents(':eq(1)').css("background", "white");
											$(this).parents(':eq(0)').css("background", "white");
											$(this).find("a").css("background", "white");
											$(this).parents(':eq(2)').css("color", "#015BB6");
											$(this).find("a").css("color", "#015BB6");
									}
								}
							});
						});
																
																
						$('.searchbox').keyup(function(){
							var SearchName = $(this).val();
							
							if(SearchName != ""){
								SearchName = SearchName.toUpperCase();
							}
							$("input:checkbox[name='checkbox[]']").each(function(){ 
								var BgColor = $(this).parents(':eq(2)').css("backgroundColor");
								if(SearchName != ""){
									var StaffName = $(this).attr('data-sname');
										StaffName = $.trim(StaffName);
									var FirstAlpha = StaffName.toUpperCase();
									var RoleName = $(this).attr('data-desig');
										RoleName = $.trim(RoleName);
									var FirstAlpha2 = RoleName.toUpperCase();
									if(FirstAlpha.toUpperCase().indexOf(SearchName) > -1){
										if((BgColor != 'rgb(227, 196, 20)')&&(BgColor != 'rgb(225, 16, 105)')){
											$(this).parents(':eq(2)').css("background", "#E3C414");
											$(this).parents(':eq(1)').css("background", "#E3C414");
											$(this).parents(':eq(0)').css("background", "#E3C414");
											$(this).find("a").css("background", "#E3C414");
											$(this).parents(':eq(2)').css("color", "#222221");
											$(this).find("a").css("color", "#222221");
										}
									}
									if(FirstAlpha2.toUpperCase().indexOf(SearchName) > -1){
										if((BgColor != 'rgb(227, 196, 20)')&&(BgColor != 'rgb(225, 16, 105)')){
											$(this).parents(':eq(2)').css("background", "#E3C414");
											$(this).parents(':eq(1)').css("background", "#E3C414");
											$(this).parents(':eq(0)').css("background", "#E3C414");
											$(this).find("a").css("background", "#E3C414");
											$(this).parents(':eq(2)').css("color", "#222221");
											$(this).find("a").css("color", "#222221");
										}
									}else{
										if((BgColor == 'rgb(227, 196, 20)')||(BgColor == 'rgb(225, 16, 105')){
											$(this).parents(':eq(2)').css("background", "white");
											$(this).parents(':eq(1)').css("background", "white");
											$(this).parents(':eq(0)').css("background", "white");
											$(this).find("a").css("background", "white");
											$(this).parents(':eq(2)').css("color", "#015BB6");
											$(this).find("a").css("color", "#015BB6");
										}
									}
																		
								}else{ //alert(BgColor);
									if(BgColor == 'rgb(227, 196, 20)'){
										$(this).parents(':eq(2)').css("background", "white");
										$(this).parents(':eq(1)').css("background", "white");
										$(this).parents(':eq(0)').css("background", "white");
										$(this).find("a").css("background", "white");
										$(this).parents(':eq(2)').css("color", "#015BB6");
										$(this).find("a").css("color", "#015BB6");
									}
								}
							});
						});
																
					</script>
						</div>
					</div>
				</div>
			</p>
		</div>
	</div>
</div>	
	
	
<!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
	$("#cmb_shortname").chosen();
	$("#cmb_ext_staff").chosen();
	$("#cmb_change_staff").chosen();
    $(function() {
		$.fn.validateworkorder = function(event) { 
			if($("#cmb_shortname").val()==""){ 
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
		$.fn.validatestaff = function(event) { 
			if($("#cmb_ext_staff").val()==""){ 
				var a="Please select staff name";
				$('#val_ext').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else{
				var a="";
				$('#val_staff').text(a);
			}
		}
		$.fn.validatestaffnew = function(event) { 
			if($("#cmb_change_staff").val()==""){
				var a="Please select any one of staff from Staff List";
				$('#val_new').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else{
				var a="";
				$('#val_new').text(a);
			}
		}
		$("#top").submit(function(event){
			$(this).validateworkorder(event);
			$(this).validatestaff(event);
			$(this).validatestaffnew(event);
        });
		$("#cmb_shortname").change(function(event){
           	$(this).validateworkorder(event);
     	});
		$("#cmb_ext_staff").change(function(event){
           	$(this).validatestaff(event);
     	});
		$("#cmb_change_staff").change(function(event){
           	$(this).validatestaffnew(event);
     	});
	});
	 
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
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
    </body>
</html>
<link rel="stylesheet" href="../bootstrap-dialog/css/bootstrap-dialog.min.css">
<script src="../bootstrap-dialog/js/bootstrap.min.js"></script> <!---IMP-->
<script src="../bootstrap-dialog/js/bootstrap-dialog.min.js"></script>
<script src="../bootstrap-dialog/js/run_prettify.min.js"></script>
<script>
	$(function(){
		$('#AssignStaff').click(function(){ 
			BootstrapDialog.show({
				title: 'Work - Staff Assign',
				cssClass: 'login-dialog',
				//message: $('<div></div>').html($('#staff_list').html()),
				 message: function(dialog) {
					$("input:checkbox[name='checkbox[]']").each(function(){   
						//$(this).prop("checked",false);
						$(this).removeAttr('checked');
						$(this).parents(':eq(2)').css("background", "white");
						$(this).parents(':eq(2)').css("color", "#015BB6");
						$(this).find("a").css("background", "white");
						$(this).parents(':eq(1)').css("background", "white");
						$(this).parents(':eq(0)').css("background", "white");
					});
					var Stafflist = $('#txt_multi_staff').val();
					var SplitStafflist = Stafflist.split(",");
					var i;
					for(i=0; i<SplitStafflist.length; i++){
						var icno = SplitStafflist[i];  //alert();
						$("#IC"+icno).attr('checked','checked');
						$("#IC"+icno).parents(':eq(2)').css("background", "#E11069");
						$("#IC"+icno).parents(':eq(2)').css("color", "white");
						
						$("#IC"+icno).find("a").css("background", "#E11069");
						$("#IC"+icno).parents(':eq(1)').css("background", "#E11069");
						$("#IC"+icno).parents(':eq(0)').css("background", "#E11069");
					}
					var $content = $('<div id="modal-content"></div>').html($('#staff_list').html());
					return $content;
            	},
				closable: false,
				buttons: [{
                	label: 'CANCEL',
					action: function(dialogRef){
						dialogRef.close();
					}
				
				},{
                	label: 'OK',
					action: function(dialogRef){
						dialogRef.close();
					}
				
				}]
			});
			
		});
		
		$('.level').click(function(event){
			var level = $(this).attr('data-level');
			var check = $(this).attr('data-check');
			if(check == 'N'){
				$(this).addClass("active");
				$(this).attr('data-check','Y');
			}else{
				$(this).removeClass("active");
				$(this).attr('data-check','N');
			}
			var Level = [];
			var check1 = $('#level_check1').attr('data-check');
			var check2 = $('#level_check2').attr('data-check');
			var check3 = $('#level_check3').attr('data-check');
			var check4 = $('#level_check4').attr('data-check');
			if(check1 == 'Y'){ Level.push(1); }
			if(check2 == 'Y'){ Level.push(2); }
			if(check3 == 'Y'){ Level.push(3); }
			if(check4 == 'Y'){ Level.push(4); }
			$('#txt_level').val(Level.join(","));
		});
	});
</script>
<style>
   	.modal-dialog {
		width: 90%;
	}
  	.small{
		font-weight:normal;
	}
	.pignose-calender {
		max-width: 450px;
	}
	.multi-mark{
		cursor:pointer;
	}
	.multi-mark .outer-w3-agile:hover{
		background:#DFDFDF;
	}
	.padding-1 {
   	 	padding: 1px 0px;
	}
  	.modal-dialog {
    	width: 90%;
	}
 </style>