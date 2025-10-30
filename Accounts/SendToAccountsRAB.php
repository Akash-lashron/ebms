<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
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
//$check_accounts_sheetid = checkSendAccounts();
if($_POST["submit"] == " Send to Accounts ") 
{
	$staffid 	= $_SESSION['sid'];
	$sheetid 	= $_POST['cmb_work_no'];
	$rbn 		= $_POST['txt_rbn'];
	$staffid 	= $_SESSION['sid'];
	
	//$minmax_level_str 		= getstaff_minmax_level();
	//$exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
	//$min_levelid 			= $exp_minmax_level_str[0];
	//$max_levelid 			= $exp_minmax_level_str[1];
	
	/******* ACCOUNTS LEVEL ASSIGN PARTS BASED ON WORK ORDER COST  STARTS******************/
	$AlAsCount = 0;
	$select_acal_query = "select * from al_as where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_acal_sql = mysqli_query($dbConn,$select_acal_query);
	if($select_acal_sql == true){
		$AlAsCount = mysqli_num_rows($select_acal_sql);
	}
	
	if($AlAsCount == 0){ 
		if(($rbn != "")&&($rbn != 0)&&($sheetid != "")&&($sheetid != 0)){  
			$select_wo_cost_query 	= "select work_order_cost from sheet where sheet_id = '$sheetid'";
			$select_wo_cost_sql 	= mysqli_query($dbConn,$select_wo_cost_query);
			if($select_wo_cost_sql == true){
				$WoCoList 			= mysqli_fetch_object($select_wo_cost_sql);
				$work_order_cost 	= round($WoCoList->work_order_cost);
				
				$FBCount = 0;
				$select_final_bill_query 	= "select is_finalbill from measurementbook_temp where sheetid = '$sheetid' and rbn = '$rbn' and is_finalbill = 'Y'";
				$select_final_bill_sql 		= mysqli_query($dbConn,$select_final_bill_query);
				if($select_final_bill_sql == true){
					$FBCount = mysqli_num_rows($select_final_bill_sql);
				}
				if($FBCount > 0){
					$is_finalbill 	= "Y";
					$bill_type 		= "F";
				}else{
					$is_finalbill 	= "";
					$bill_type 		= "R";
				}
				
				$select_levl_query 	= "select level from wol_al where wo_val_from <= '$work_order_cost' and wo_val_to >= '$work_order_cost' and bill_type = '$bill_type'";
				$select_levl_sql 	= mysqli_query($dbConn,$select_levl_query);
				if($select_levl_sql == true){
					$WoAclist 		= mysqli_fetch_object($select_levl_sql);
					$WoAcLevel 		= $WoAclist->level;
					$expWoAcLevel 	= explode(",",$WoAcLevel);
					$WoAcStatus 	= $expWoAcLevel[0];
				}
			}
			//echo $WoAcStatus;exit;
		
			$insert_alas_query 	= "insert into al_as set sheetid = '$sheetid', rbn = '$rbn', al_level = '$WoAcLevel', is_finalbill = '$is_finalbill', status = '$WoAcStatus', createddate = NOW()";
			$insert_alas_sql 	= mysqli_query($dbConn,$insert_alas_query);
			//$AlAsid 			= mysqli_insert_id();
			//$insert_al_as_query = "insert into al_as_dt set alasid = '$AlAsid', sheetid = '$sheetid', rbn = '$rbn', level = '$WoAcStatus', action = 'FW', staffid = '".$_SESSION['sid']."', section = '".$_SESSION['staff_section']."', createddate = NOW()";
			//$insert_al_as_sql 	= mysqli_query($dbConn,$insert_al_as_query);
			
		}
	}
	else if($AlAsCount > 0){ 
		$AlAsList 		= mysqli_fetch_object($select_acal_sql);
		$AlAsid 		= $AlAsList->alasid;
		$AlAsLevel 		= $AlAsList->al_level;
		$expWoAcLevel 	= explode(",",$AlAsLevel);
		$WoAcStatus 	= $expWoAcLevel[0];
		$is_finalbill 	= $AlAsList->is_finalbill;
		$update_alas_query 	= "update al_as set status = '$WoAcStatus', createddate = NOW() where sheetid = '$sheetid' and rbn = '$rbn'";
		$update_alas_sql 	= mysqli_query($dbConn,$update_alas_query);
		//$insert_al_as_query = "insert into al_as_dt set alasid = '$AlAsid', sheetid = '$sheetid', rbn = '$rbn', level = '$WoAcStatus', action = 'FW', staffid = '".$_SESSION['sid']."', section = '".$_SESSION['staff_section']."', createddate = NOW()";
		//$insert_al_as_sql 	= mysqli_query($dbConn,$insert_al_as_query); 
		//echo $WoAcStatus; exit;
	}
	/******* ACCOUNTS LEVEL ASSIGN PARTS BASED ON WORK ORDER COST  ENDS******************/
	
	
	
	//exit;
	/******* ACCOUNTS LEVEL ASSIGN PARTS BASED ON WORK ORDER COST  STARTS******************/
	$count = 0; $AccountsMbArr = array();
	$select_sent_mb_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_sent_mb_sql = mysqli_query($dbConn,$select_sent_mb_query);
	if($select_sent_mb_sql == true)
	{
		if(mysqli_num_rows($select_sent_mb_sql)>0)
		{
			$count = 1;
			while($SAList = mysqli_fetch_object($select_sent_mb_sql)){
				array_push($AccountsMbArr,$SAList->mbookno);
			}
		}
	}
	$update = 0; 
	//if($count == 0)
	//{
		$select_mbook_query = "select distinct(mbno), zone_id, genlevel, mtype, staffid from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and mtype != 'E' and active = 1";
		$select_mbook_sql = mysqli_query($dbConn,$select_mbook_query); 
		if($select_mbook_sql == true)
		{
			if(mysqli_num_rows($select_mbook_sql)>0)
			{
				while($ZoneList = mysqli_fetch_object($select_mbook_sql))
				{
					$mbno = $ZoneList->mbno;
					$zone_id = $ZoneList->zone_id;
					$genlevel = $ZoneList->genlevel;
					$mtype = $ZoneList->mtype;
					$generated_staff = $ZoneList->staffid;
					if($genlevel == 'staff')
					{
						$mb_ac = "SA";
						$sa_ac = "";
						$ab_ac = "";
						$flag  = "RAB";
					}
					if($genlevel == 'composite')
					{
						$mb_ac = "";
						$sa_ac = "SA";
						$ab_ac = "";
						$flag  = "RAB";
					}
					if($genlevel == 'abstract')
					{
						$mb_ac = "";
						$sa_ac = "";
						$ab_ac = "SA";
						$flag  = "RAB";
					}
					if($genlevel == 'cem_consum')
					{
						$mb_ac = "SA";
						$sa_ac = "";
						$ab_ac = "";
						$flag  = "ESC";
					}
					if($genlevel == 'stl_consum')
					{
						$mb_ac = "SA";
						$sa_ac = "";
						$ab_ac = "";
						$flag  = "ESC";
					}
					if($genlevel == 'escalation')
					{
						$mb_ac = "SA";
						$sa_ac = "";
						$ab_ac = "";
						$flag  = "ESC";
					}
					$ExistCount = 0;
					$SelectQuery1 = "select sacid from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and mbookno = '$mbno' and mtype = '$mtype' and genlevel = '$genlevel' and zone_id = '$zone_id'";
					$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
					if($SelectSql1 == true){
						if(mysqli_num_rows($SelectSql1)>0){
							$ExistCount = 1;
						}
					}
					
					if($ExistCount == 0){
						$insert_query = "insert into send_accounts_and_civil set sheetid = '$sheetid', rbn = '$rbn', 
										mbookno = '$mbno', mb_ac = '$mb_ac', sa_ac = '$sa_ac', ab_ac = '$ab_ac', zone_id = '$zone_id', 
										mtype = '$mtype', genlevel = '$genlevel', level='$WoAcStatus', level_status = 'P', send_civil_staff_ids = '".$_SESSION['sid']."',  
										civil_staffid  = '$generated_staff', userid = '$userid', modifieddate = NOW(), flag = '$flag', active = 1";				
						$insert_sql = mysqli_query($dbConn,$insert_query);

						$log_linkid = mysqli_insert_id();
						$linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sheetid', rbn = '$rbn', log_date = NOW(), mbookno = '$mbno', 
											zone_id = '$zone_id', mtype = '$mtype', genlevel = '$genlevel', status = 'SA',
											comment = 0, levelid = '$WoAcStatus', sectionid = 2,
											rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END ";
						$linsert_log_sql = mysqli_query($dbConn,$linsert_log_query);
					}else{
						$update_sent_mb_query = "update send_accounts_and_civil set mb_ac = '$mb_ac', sa_ac = '$sa_ac', ab_ac = '$ab_ac', level = '$WoAcStatus', level_status = 'P', userid  = '$userid', send_civil_staff_ids = CONCAT(send_civil_staff_ids, ',', '".$_SESSION['sid']."') where sheetid = '$sheetid' and rbn = '$rbn' and mbookno = '$mbno' and mtype = '$mtype' and genlevel = '$genlevel' and zone_id = '$zone_id'";
						$update_sent_mb_sql = mysqli_query($dbConn,$update_sent_mb_query);
						
						$update_acc_log_query 	= "update acc_log set status = 'SA', AC_status = '', levelid = '$WoAcStatus', rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END where sheetid = '$sheetid' and rbn = '$rbn' and mbookno = '$mbno' and mtype = '$mtype' and genlevel = '$genlevel' and zone_id = '$zone_id'";// and status = 'SC'";// and levelid = 0";
						$update_acc_log_sql 	= mysqli_query($dbConn,$update_acc_log_query);
						if($update_sent_mb_sql == true)
						{
							$update++;
						}
					}
				}
			}
		}
		if(($insert_sql == true)||($update > 0))
		{
			$msg = "RAB Sucessfully sent to Accounts";
			$success = 1;
			if($update == 0){
				$RABTranActStatusStr = "RAB Sent to Accounts";
			}else{
				$RABTranActStatusStr = "RAB Re-sent to Accounts";
			}
			//UpdateWorkTransaction($sheetid,$rbn,"R",$RABTranActStatusStr,"");
		}
		else
		{
			$msg = "Error";
		}
	//}
	/*else if($count == 1)
	{
		$update = 0;
		$update_sent_mb_query = "update send_accounts_and_civil set mb_ac = 'SA', level='$WoAcStatus', level_status = 'P', userid  = '$userid', send_civil_staff_ids = CONCAT(send_civil_staff_ids, ',', '".$_SESSION['sid']."') where sheetid = '$sheetid' and rbn = '$rbn' and mb_ac != ''";
		$update_sent_mb_sql = mysqli_query($dbConn,$update_sent_mb_query);
		
		if($update_sent_mb_sql == true)
		{
			$update++;
		}
		
		$update_sent_sa_query = "update send_accounts_and_civil set sa_ac = 'SA', level='$WoAcStatus', level_status = 'P', userid  = '$userid', send_civil_staff_ids = CONCAT(send_civil_staff_ids, ',', '".$_SESSION['sid']."') where sheetid = '$sheetid' and rbn = '$rbn' and sa_ac != ''";
		$update_sent_sa_sql = mysqli_query($dbConn,$update_sent_sa_query);
		if($update_sent_sa_sql == true)
		{
			$update++;
		}
		
		$update_sent_ab_query = "update send_accounts_and_civil set ab_ac = 'SA', level='$WoAcStatus', level_status = 'P', userid  = '$userid', send_civil_staff_ids = CONCAT(send_civil_staff_ids, ',', '".$_SESSION['sid']."') where sheetid = '$sheetid' and rbn = '$rbn' and ab_ac != ''";
		$update_sent_ab_sql = mysqli_query($dbConn,$update_sent_ab_query);
		if($update_sent_ab_sql == true)
		{
			$update++;
		}
		
		$update_acc_log_query 	= "update acc_log set status = 'SA', AC_status = '', levelid = '$WoAcStatus', rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END where sheetid = '$sheetid' and rbn = '$rbn'";// and status = 'SC'";// and levelid = 0";
		$update_acc_log_sql 	= mysqli_query($dbConn,$update_acc_log_query);
		
		if($update>0)
		{
			$msg = "RAB Sucessfully sent to Accounts";
			UpdateWorkTransaction($sheetid,$rbn,"R","RAB Re-sent to Accounts","");
			$success = 1;
		}
		else
		{
			$msg = "Error";
		}
	}
	else
	{
		$msg = "Error";
	}*/
}

?>
<?php require_once "Header.html"; ?>
<script>
     
	function find_workname()
	{		
		
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
					document.form.txt_workorder_no.value	=	name[2].trim();
					document.form.txt_book_no1.value		=	Number(name[1]) + Number(1);
					document.form.txt_book_no.value			=	Number(name[1]) + Number(1);
					document.form.txt_bookpage_no1.value	=	Number(name[2]) + Number(1);
					document.form.txt_bookpage_no.value		=	Number(name[2]) + Number(1);
					document.form.txt_rab_no1.value			=	Number(name[3]) + Number(1);
					document.form.txt_rab_no.value			=	Number(name[3]) + Number(1);
	
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	function check_bill_confirm()
	{		
		
		var xmlHttp;
		var data;
		var i,j;
		document.form.txt_rbn.value	= "";
		document.form.txt_empty_page.value	= "";	
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_bill_confirm.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				//alert(data);
				if((data == "")||(data == 0))
				{
					swal("No MBook available to send Accounts", "");
				}
				else
				{
					document.form.txt_rbn.value	= name[0];
					document.form.txt_empty_page.value	= name[1];
				}
			}
		}
		xmlHttp.send(strURL);	
	}
</script>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->

         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
                        <div class="title">Running Account Bill - Send Accounts</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                       
                            <div class="container">
					<br/>
                 <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                 <tr><td width="22%">&nbsp;</td></tr>
                 <tr>
					<td>&nbsp;</td> 
					<td  class="label">Work Short Name</td>
					<td  class="labeldisplay">
					<select name="cmb_work_no" id="cmb_work_no" onChange="find_workname();check_bill_confirm();" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
					<option value="">---------------------- Select ----------------------</option>
						<?php echo $objBind->BindWorkOrderNoSendAcc(0); ?>
						<?php //echo $objBind->BindWorkOrderNo_CIVIL(0); ?>
					</select>
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
				<tr>
                    <td>&nbsp;</td>
                    <td  class="label">Work Order No.</td>
                    <td  class="labeldisplay">
					<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplay" style="width:397px;" disabled="disabled">
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
                <tr><td>&nbsp;</td><td></td><td id="val_workorder" style="color:red"></td></tr>			
				<tr>
                    <td>&nbsp;</td>
                    <td  class="label">Name of the Work </td>
                    <td  class="labeldisplay">
					<textarea name="workname" class="textboxdisplay txtarea_style" style="width: 400px;" rows="5" disabled="disabled"></textarea>
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
                <tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
				<tr>
                    <td>&nbsp;</td>
                    <td  class="label">RAB No.</td>
                    <td  class="labeldisplay">
					<input type="text" name="txt_rbn" id="txt_rbn" readonly="" value="" class="textboxdisplay" style="width:100px;"/>
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
                <tr><td>&nbsp;</td><td></td><td id="val_rbn" style="color:red"></td></tr>
					
                <input type="hidden" name="txt_empty_page" id="txt_empty_page" value="" class="textboxdisplay" style="width:100px;"/>
               <!-- <tr> 
                    <td>&nbsp;</td> 
                    <td  class="label">Measurement Book Type </td>
                    <td  class="labeldisplay">
                      <select name="cmb_mbook_type" id="cmb_mbook_type" class="textboxdisplay" style="width:400px;height:22px;" size="" tabindex="7">
                        <option value="">---------------------------------Select---------------------------------</option>
						<option value="G">General M.Book</option>
						<option value="S">Steel M.Book</option>
                      </select>
					</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
				<tr>
                   <td>&nbsp;&nbsp;</td><td width="25%" class="label"></td>
                   <td id="val_mbooktype" style="color:red">
                </tr>-->
                <tr>
                   <td colspan="6">
                        <input type="hidden" class="text" name="submit" value="true" />
						<input  type="hidden" class="text" name="runningbilltext" id="runningbilltext" value=""/>
                       <!-- <input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"   />&nbsp;&nbsp;&nbsp;
						<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> -->
					</td>
                </tr>
                <tr><td></td></tr>
         </table>
     </div>
   		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
			</div>
			<div class="buttonsection" id="view_btn_section">
			<input type="submit" class="btn" data-type="submit" value=" Send to Accounts " name="submit" id="submit"/>
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
	/*$.fn.validatembooktype = function(event) {	
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
			}*/
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
            //$(this).validatembooktype(event);
			$(this).validateworkorder(event);
         });
	$("#cmb_work_no").change(function(event){
           $(this).validateworkorder(event);
         });
    /*$("#cmb_mbook_type").change(function(event){
           $(this).validatembooktype(event);
         });*/
			
	 });
</script>
			<script>
				$("#cmb_work_no").chosen();
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

