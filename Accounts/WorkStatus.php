<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'common.php';
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
if(isset($_GET["id"])) {
   $sheet_id = $_GET['id'];
   $select_sheet_query = "select * from sheet WHERE sheet_id='$sheet_id' AND (active = 1 OR active = 2 )";
   $select_sheet_sql 		= mysqli_query($dbConn,$select_sheet_query);
   if($select_sheet_sql == true){
	  if(mysqli_num_rows($select_sheet_sql)>0){
	     $count=1;
	  }
   }	  
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	window.history.forward();
	function noBack(){ 
		window.history.forward(); 
	}
</script>
<style>
	.cclable{
		padding:4px;
		border:1px solid #055DAB;
		border-radius:6px;
		margin:4px;
		color:#0839D5;
		/*font-weight:bold;*/
		cursor:pointer;
		min-height:64px;
		max-height:64px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		overflow: hidden;
		font-size:12px;
	}
	.cclable:hover{
		background-color: #055DAB;
		color:#FFFFFF;
	}
	.panel-primary > .panel-heading {
		color: #fff;
		background-color: #055DAB;
		border-color: #055DAB;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:14px;
	}
	.rlable-pink{
		padding:5px;
		padding-left:6px;
		padding-right:6px;
		border:1px solid #EC94A2;
		border-radius:15px;
		white-space:nowrap;
		line-height:30px;
	}
	.well{
		margin-bottom:2px;
		color:#0705C3;
		font-size:11px;
		overflow-x:auto;
	}
	.well-sm {
    	padding: 6px;
	}
	.well:nth-child(2){
		margin-bottom:9px;
	}
	.col-sm-4{
		width:31%;
	}
	.table>tbody>tr>td{
   	 	padding: 5px;
		vertical-align:middle;
	}
	.table > thead > tr > th,td {
    	padding: 1px;
		color:#30343C;
		font-size:10px;
		text-align:left;
		vertical-align:middle;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		background:#fff;
		font-weight:bold;
		vertical-align:middle;
	}
	.table-bordered > tr >th,td {
		color:#023692;/*#0240BA;*//*:#0705C3;*/
		padding: 1px;
		font-size:11px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-weight:bold;
		vertical-align:middle;
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
<?php include "MainMenu.php"; ?>
  <div class="container_12">
    <div class="grid_12">
    <blockquote class="bq1" style="min-height:1040px; padding-left:36px">
	 <div class="height">&nbsp;</div>
       <div class="leftsection">
		 <div class="leftsectionheader">Work Details</div>
		    <?php if ($count==1) { while($SList = mysqli_fetch_object($select_sheet_sql)){ 
					  $worktype  = $SList->worktype;
					  $sheet_id  = $SList->sheet_id;
					  $_SESSION['Sheet_Id'] 	= $sheet_id;
					  $sheetid			= $_SESSION['Sheet_Id']; 
					  if($worktype=1){
						 $WORK_TYPE ="Major Construction";
					 }else if($worktype=2){
						 $WORK_TYPE ="Minor Construction";
					 }else if($worktype=3){
						 $WORK_TYPE ="Major Maintenance";
					 }else if($worktype=4){
						 $WORK_TYPE ="Minor Maintenance";
					 }
					 $StaffId          = $SList->assigned_staff;
					 $ExpAssignedStaff = explode(',',$StaffId);
					 $ActiveStatus     = $SList->active;
					 //$_SESSION['ActiveStatus'] = $ActiveStatus;
					 //$ActiveStatus			   = $_SESSION['ActiveStatus']; 
		    ?>
		 <table align="center" class="table table-bordered" style="border:0px; margin:0%;">
		      <!--<thead>
			      <tr><th colspan="2" style="text-align:center">Work Details</th><tr> 
			  </thead>-->
				  <tr><td>CCNO</td><td rowspan="1"><?php echo $SList->computer_code_no; ?></td><tr>
				  <!--<tr><td>Ref.Id</td><td rowspan="1"><?php echo $SList->sheet_id; ?></td><tr>-->
				  <tr><td>Name Of Work</td><td><?php echo $SList->short_name; ?></td></tr>
				  <tr><td>Work Order No</td><td><?php echo $SList->work_order_no; ?></td></tr>
				  <tr><td>Agreement No</td><td><?php echo $SList->agree_no; ?></td></tr>
				  <tr><td>Work Order Cost</td><td><?php echo $SList->work_order_cost; ?></td></tr>
				  <tr><td>Work Order Date</td><td><?php echo dt_display($SList->work_order_date); ?></td></tr>
				  <tr><td>Schedule D.O.C.</td><td><?php echo dt_display($SList->date_of_completion); ?></td></tr>
				  <tr><td>Contractor Name</td><td><?php echo $SList->name_contractor; ?></td></tr>
				  <tr><td>Assigned staff</td>
				  <td>
				  <?php  foreach($ExpAssignedStaff as $Key => $Value){
					     $staff_id = $Value;
					     $select_staff_name   = "select a.*,b.designationid,b.designationname from staff a INNER JOIN designation b on (a.designationid=b.designationid) where a.staffid ='$staff_id' ";
						 $select_staff_sql 	  =  mysqli_query($dbConn,$select_staff_name);
							if($select_staff_sql == true){
							    while($SNList = mysqli_fetch_object($select_staff_sql)){
									 $StaffId .= $SNList->staffid.', ';
									 $_SESSION['Staff_id'] 	= $staff_id;
									 $Staffid		= $_SESSION['Staff_id'];
									 $StaffNames    = $SNList->staffname;
									 $StaffCodes    = $SNList->staffcode;
									 $DesName       = $SNList->designationname;
									 // echo $DesName;
									 //$StaffNameStr    = explode(',',$StaffNames);
									//echo $StaffNames; 
							    }
							}
				  ?>
				  <?php if($StaffNames !=""){ echo trim($StaffNames); echo trim("[$DesName,$StaffCodes]".'<br/> ');  } } ?></td></tr>
				  <tr>
				     <td>CheckMeasurement <br/>Level</td><td>
					  <?php  $select_SS_query = "select a.sheet_id,a.assigned_staff,b.sheetid,b.laid,b.check_meas_level from sheet a INNER JOIN 
												check_measure_level_assign b ON (a.sheet_id=b.sheetid) WHERE a.sheet_id='$sheet_id'
												and b.laid = (select max(c.laid) from check_measure_level_assign c where c.sheetid = '$sheetid') ";
							 $select_SS_sql  =  mysqli_query($dbConn,$select_SS_query);
								 if($select_SS_sql == true){
									 while($SSNList = mysqli_fetch_object($select_SS_sql)){
										   $C_Staff_Ids = $SSNList ->assigned_staff;
										   $C_ExpAssignedStaffs = explode(',',$C_Staff_Ids);
									 foreach($C_ExpAssignedStaffs as $Key => $Value){
											 $C_STAff_ID = $Value;
											 $select_staff_name = "select a.staffid,a.staffname,a.staffcode,b.sroleid,b.role_name from staff a INNER JOIN 
																   staffrole b ON (a.sroleid=b.sroleid) where staffid ='$C_STAff_ID' ";
											 $select_staff_sql 	=  mysqli_query($dbConn,$select_staff_name);
												if($select_staff_sql == true){
													while($SRNList = mysqli_fetch_object($select_staff_sql)){	
														$StaffName_Chk = $SRNList->staffname;  
														$RoleName_Chk  = $SRNList->role_name;
														$staffid_Chk   = $SRNList->staffid;	
													}
												}	  
					   ?>
				       <?php echo $StaffName_Chk; echo "($RoleName_Chk)".'<br/>'; 
								 } } } ?>
				     </td>
				  </tr>
				  <tr><td>Work Type</td><td><?php echo $WORK_TYPE; ?></td></tr>
				  <tr><td>Rebate % </td><td><?php if($List->rebate_percent!=""){ echo $List->rebate_percent; }else{echo "0"; } ?></td></tr>
				  <tr><td>Updated On</td><td><?php echo dt_display($SList->date_upt); ?></td></tr>
		    </table>
			<div class="bottomsectionheader"> Mbooks Details</div>
	        <?php $mbno=""; $MBCount=0;
			$Select_mbno_query = "select a.mbno, a.staffid,b.allotmentid,b.mbooktype from mbookallotment a INNER JOIN  agreementmbookallotment b ON (a.allotmentid=b.allotmentid)
								  WHERE a.sheetid ='$sheetid'";
			$select_mbno_sql   = mysqli_query($dbConn,$Select_mbno_query);
			$MBCount        = mysqli_num_rows($select_mbno_sql);
			   while($MBLIST=mysqli_fetch_object($select_mbno_sql)){
				   $mbooktype =$MBLIST->mbooktype;
				   $staffidMB =$MBLIST->staffid;
					 if($mbooktype == "G")
					 {
						$GMbNo .= $MBLIST->mbno.', ';
					 }
					 if($mbooktype == "S")
					 {
						$SMbNo .= $MBLIST->mbno.', ';
					 }
					 if($mbooktype == "A")
					 {
						$AMbNo .= $MBLIST->mbno.', ';
					 }
					 if($mbooktype == "E")
					 {
						$EMbNo .= $MBLIST->mbno.', ';
					 }
					 $mballotmentdate =$MBLIST->mbdate;
						 $select_staff_name="select a.staffname,a.staffcode,b.sroleid,b.role_name from staff a INNER JOIN 
						 staffrole b ON (a.sroleid=b.sroleid) where staffid ='$staffidMB' ";
						 //echo $select_staff_name;
						 $select_staff_sql 		= mysqli_query($dbConn,$select_staff_name);
						 if($select_staff_sql == true){
						   $MSNList 		= mysqli_fetch_object($select_staff_sql);
						   //$STAFF_NAME      = $MSNList->staffname;
						   //echo $STAFF_NAME ;
						 }
			   }
		    ?>
		    <table align="left" class="table table-bordered" style="border:0px; margin:0%;">
			     <tr>
					<td style="text-align:left">Staff Name</td>
					<td style="text-align:center"><?php echo $MSNList->staffname ;?></td>
			     </tr>
				 <tr><td style="text-align:center" colspan="2">General MBook</td></tr>
				 <td colspan="2"><?php echo rtrim($GMbNo,','); ?></td></tr>
				 <tr><td style="text-align:center" colspan="2">Steel MBook</td></tr>
				 <td colspan="2"><?php echo rtrim($SMbNo,','); ?></td></tr>
				 <tr><td style="text-align:center" colspan="2">Abstract MBook</td></tr>
				 <tr><td colspan="2"><?php echo rtrim($AMbNo,','); ?></td></tr>
				 <tr><td style="text-align:center" colspan="2">Escalation MBook</td></tr>
				 <tr><td colspan="2"><?php echo rtrim($EMbNo,','); ?></td></tr>
		    </table>
			
			
       
		    <?php } } ?>
			</div>
       <div class="contenttsection">
		 <div class="contenttopheader">Current RAB Details</div>
			 <?php  $RCount1=0; $RCount2=0; $RCount3=0; $RCount4=0; $RCount5=0;$RCount6=0; 
			        $select_meas_gen_staff_query = "select sheetid,mbgenerateid,rbn from mbookgenerate  where sheetid = '$sheetid'";
				    //echo $select_meas_gen_staff_query;
					$select_meas_gen_staff_sql 	 = mysqli_query($dbConn,$select_meas_gen_staff_query);
					  if($select_meas_gen_staff_sql == true){
					     $RCount1 = mysqli_num_rows($select_meas_gen_staff_sql);
						// echo  $RCount1;
					     $RList 		= mysqli_fetch_object($select_meas_gen_staff_sql);
						 $RBN           = $RList->rbn;
					     $RSTATUS = "RAB Generated";
					  }
					// echo  $RCount1;  
				    $select_meas_gen_query = "select distinct * from measurementbook_temp where sheetid = '$sheetid' and rbn = '$RList->rbn'";
				    $select_meas_gen_sql 	 = mysqli_query($dbConn,$select_meas_gen_query);
					   if($select_meas_gen_sql == true){
					      $RCount2 = mysqli_num_rows($select_meas_gen_sql);
					      $MGList 		     = mysqli_fetch_object($select_meas_gen_sql);
					   } 
				    $select_bill_val__query = "select * from abstractbook  where sheetid = '$sheetid' AND rbn='$RList->rbn'";
					//echo  $select_bill_val__query;
				    $select_bill_val_sql 	 = mysqli_query($dbConn,$select_bill_val__query);
				      if($select_bill_val_sql == true){
						 $BVLIST 		= mysqli_fetch_object($select_bill_val_sql);
						 //$RBN           = $BVLIST->rbn;
						 $UP_TO_AMT     = $BVLIST->upto_date_total_amount;
						 $BILL_VALUE    = $BVLIST->slm_total_amount;
					  }   
					$SAMBArr = array();	$SAMBStatusArr = array(); $SAMBTempStatusArr = array();
					$select_send_acc_query 	= "select distinct * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$RList->rbn'";
					//echo $select_send_acc_query;
					$select_send_acc_sql 	= mysqli_query($dbConn,$select_send_acc_query);
					if($select_send_acc_sql == true){
						$count9 	= mysqli_num_rows($select_send_acc_sql);
						while($SAList = mysqli_fetch_object($select_send_acc_sql)){
							//// For General MBook
							if($SAList->mtype == "G" && $SAList->genlevel == "staff"){
								array_push($SAMBArr,"General : ".$SAList->mbookno);
								if($SAList->mb_ac == "SA"){
									array_push($SAMBStatusArr,"  Waiting for Accounts Approval");
									array_push($SAMBTempStatusArr,"SA");
								}elseif($SAList->mb_ac == "AC"){
									array_push($SAMBStatusArr,"  Verified & Accounts Accepted /Waiting for Pass Order");
									array_push($SAMBTempStatusArr,"AC");
								}elseif($SAList->mb_ac == "SC"){
									array_push($SAMBStatusArr,"  Accounts Rejected");
									array_push($SAMBTempStatusArr,"SC");
								}elseif($SAList->mb_ac == ""){
									array_push($SAMBStatusArr,"  Accounts Accepted");
									array_push($SAMBTempStatusArr,"CF");
								}else{
									array_push($SAMBStatusArr,"  Not Found");
									array_push($SAMBTempStatusArr,"NF");
								}
							}
							//// For Steel MBook
							if($SAList->mtype == "S" && $SAList->genlevel == "staff"){
								array_push($SAMBArr,"Steel : ".$SAList->mbookno);
								if($SAList->mb_ac == "SA"){
									array_push($SAMBStatusArr,"  Waiting for Accounts Approval");
									array_push($SAMBTempStatusArr,"SA");
								}elseif($SAList->mb_ac == "AC"){
									array_push($SAMBStatusArr,"  Verified & Accounts Accepted /Waiting for Pass Order");
									array_push($SAMBTempStatusArr,"AC");
								}elseif($SAList->mb_ac == "SC"){
									array_push($SAMBStatusArr,"  Accounts Rejected");
									array_push($SAMBTempStatusArr,"SC");
								}elseif($SAList->mb_ac == ""){
									array_push($SAMBStatusArr,"  Accounts Accepted");
									array_push($SAMBTempStatusArr,"CF");
								}else{
									array_push($SAMBStatusArr,"  Not Found");
									array_push($SAMBTempStatusArr,"NF");
								}
							}
							//// For Sub Abstract MBook
							if($SAList->mtype == "G" && $SAList->genlevel == "composite"){
								array_push($SAMBArr,"Sub - Abstract : ".$SAList->mbookno);
								if($SAList->sa_ac == "SA"){
									array_push($SAMBStatusArr,"  Waiting for Accounts Approval");
									array_push($SAMBTempStatusArr,"SA");
								}elseif($SAList->sa_ac == "AC"){
									array_push($SAMBStatusArr," Verified &  Accounts Accepted /Waiting for Pass Order");
									array_push($SAMBTempStatusArr,"AC");
								}elseif($SAList->sa_ac == "SC"){
									array_push($SAMBStatusArr,"  Accounts Rejected");
									array_push($SAMBTempStatusArr,"SC");
								}elseif($SAList->sa_ac == ""){
									array_push($SAMBStatusArr,"  Accounts Accepted");
									array_push($SAMBTempStatusArr,"CF");
								}else{
									array_push($SAMBStatusArr,"  Not Found");
									array_push($SAMBTempStatusArr,"NF");
								}
							}
							//// For Sub Abstract MBook
							if($SAList->mtype == "A" && $SAList->genlevel == "abstract"){
								array_push($SAMBArr,"Abstract : ".$SAList->mbookno);
								if($SAList->ab_ac == "SA"){
									array_push($SAMBStatusArr,"  Waiting for Accounts Approval");
									array_push($SAMBTempStatusArr,"SA");
								}elseif($SAList->ab_ac == "AC"){
									array_push($SAMBStatusArr," Verified & Accounts Accepted /Waiting for Pass Order");
									array_push($SAMBTempStatusArr,"AC");
								}elseif($SAList->ab_ac == "SC"){
									array_push($SAMBStatusArr,"  Accounts Rejected");
									array_push($SAMBTempStatusArr,"SC");
								}elseif($SAList->ab_ac == ""){
									array_push($SAMBStatusArr,"  Accounts Accepted");
									array_push($SAMBTempStatusArr,"CF");
								}else{
									array_push($SAMBStatusArr,"  Not Found");
									array_push($SAMBTempStatusArr,"NF");
								}
							}
						}
					}
					$SACount = count($SAMBArr);
					if($SACount<=0){
					$WorkLevel = "";
					$select_max_query = "select laid, check_meas_level from check_measure_level_assign where sheetid = '$sheetid' and laid = (select max(laid) from check_measure_level_assign where sheetid = '$sheetid')";
					$select_max_sql = mysqli_query($dbConn,$select_max_query);
					if($select_max_sql == true){
						$MaxList = mysqli_fetch_object($select_max_sql);
						$WorkLevel = $MaxList->check_meas_level;
						if($WorkLevel != ""){
						$expWorkLevel = explode(",",$WorkLevel);
						$MinLevel = min($expWorkLevel);
						$MaxLevel = max($expWorkLevel);
						}
					}
					$CHKCount2=0;
					$select_check_measure_query 	= "select *  from check_measurement_master where sheetid = '$sheetid' and cmid = (select max(cmid) from check_measurement_master  where sheetid = '$sheetid')";
					//echo $select_check_measure_query;
					$select_check_measure_sql 	= mysqli_query($dbConn,$select_check_measure_query);
						if($select_check_measure_sql == true){
						$CHKCount2 	= mysqli_num_rows($select_check_measure_sql);
						//echo $CHKCount2;
					}
					$levelid =='';
					  while($SMList = mysqli_fetch_object($select_check_measure_sql)){
						$levelid        = $SMList->levelid;
							$forward_to     = $SMList->forward_to;
							$forward_flag   = $SMList->forward_flag;
							if($levelid == "1" && $forward_flag == "FW" && $forward_to == "2"){
							   $Check_levels=" Scientific Assistant Forward To Site Engineer ";
							}
							else if($levelid == "2" && $forward_flag == "BW" && $forward_to == "1"){
							   $Check_levels=" Site Engineer BackWard To Scientific Assistant";
							}
							else if($levelid == "2" && $forward_flag == "FW" && $forward_to == "3"){
							   $Check_levels=" Site Engineer Forward To Engieer Incharge ";
							}
							else if($levelid == "3" && $forward_flag == "BW" && $forward_to == "2"){
							   $Check_levels=" Enginner Incharge BackWard To Site Engineer";
							}
							else if($levelid == "3" && $forward_flag == "FW" && $forward_to == "4"){
							   $Check_levels=" Enginner Incharge Forward To Superintendent Engineer";
							}
							else if($levelid == "$MaxLevel" && $forward_flag == "BW" && $forward_to == "3"){
							   $Check_levels=" Superintendent Engineer Backward To Enginner incharge";
							}
							else if($levelid == "$MaxLevel" && $forward_flag == "FW" && $forward_to == "0"){
							   $Check_levels=" Check Measurement Completed";
							}
							else if($levelid == "" && $forward_flag == "" && $forward_to == ""){
								$Check_levels="Checkmeasuremnet Not Done";
						   }
					  }	
					  if($WorkLevel == ""){
							  $CSTATUS="Check Measurement Level Not Assigned.";
					  }else if($CHKCount2>0){ $CSTATUS= $Check_levels;
					  }else if($CHKCount2 == 0){$CSTATUS= "Check Measurement Not Done";}				
					}	 
			 ?>
			 <table cellspacing="0" cellpadding="0" align="center" class="table table-bordered" style="border:0px; margin:0%;" >
				<tr style="border:0px !important">
					<td style="padding:0px; border:0px !important">
					    
						<table class="table table-bordered" style="margin-bottom: 0px;">
						<?php if($ActiveStatus == 2){ ?>
						<tr><td colspan="3" style="text-align:center; color:#D50752;"><?php echo " Final Bill Completed "; ?> </td></tr>
						<?php }else{?>
						<!--<tr><td width="30%">RAB</td><td colspan="2"><?php echo $RBN; ?></td><tr>
				        <tr><td>Upto Paid Amount</td><td colspan="2"><?php echo $UP_TO_AMT; ?></td></tr>
				        <tr><td> This Bill Value</td><td colspan="2"><?php echo $BILL_VALUE; ?></td></tr>
						<tr>
						<?php $SACount = count($SAMBArr); ?>
						   <td rowspan="<?php echo $SACount+1; ?>" style="vertical-align:middle">Current Status</td>  
						   <td width="25%" style="color:#333333;vertical-align:middle">MBook No.</td>
						   <td style="text-align:center;color:#333333;">Status</td>
						</tr>
						<tr>-->
						
						<?php if(count($SAMBArr) > 0){ 
			                     for($x5=0; $x5<count($SAMBArr); $x5++){  ?>
								 <td>
								     <?php  echo $SAMBArr[$x5]; ?></td><td><?php echo $SAMBStatusArr[$x5];echo "<br/>";
								 
							   ?>
							   
							   <?php 
							   if($SACount < 0) {  echo " RAB Not Sent to Accounts ";echo "<br/>"; }
							   if($SACount <= 0){ echo $CSTATUS; }
						?>
						</td>
			           </tr>
					   <?php }	  
							   } ?>
						<tr><td colspan="3" style="text-align:center"><?php if($RCount1 == 0){ echo " RAB Not Generated. "; }  ?> </td></tr>
						<?php }?>
						</table>
						
					</td>
				</tr>
				<?php if($ActiveStatus == 1){ $WorkLevel = "";
					  $select_max_query = "select laid, check_meas_level from check_measure_level_assign where sheetid = '$sheetid' and laid = (select max(laid) from check_measure_level_assign where sheetid = '$sheetid')";
					  $select_max_sql = mysqli_query($dbConn,$select_max_query);
					     if($select_max_sql == true){
							$MaxList = mysqli_fetch_object($select_max_sql);
							$WorkLevel = $MaxList->check_meas_level;
							if($WorkLevel != ""){
								$expWorkLevel = explode(",",$WorkLevel);
								$MinLevel = min($expWorkLevel);
								$MaxLevel = max($expWorkLevel);
							}
					     }
					     $select_check_measure_tab_query 	= "select *  from check_measurement_master where sheetid = '$sheetid' and rbn = '$RList->rbn' ";
						 $select_check_measure_tab_sql 	= mysqli_query($dbConn,$select_check_measure_tab_query);
						 if($select_check_measure_tab_sql == true){
						     $CHKCount 	= mysqli_num_rows($select_check_measure_tab_sql);
						 }
					  	$levelid =='';$slno=1;
				      	while($CHKList = mysqli_fetch_object($select_check_measure_tab_sql)){
							$levelid        = $CHKList->levelid;
							$forward_to     = $CHKList->forward_to;
							$forward_flag   = $CHKList->forward_flag;
							if($levelid == "1" && $forward_flag == "FW" && $forward_to == "2"){
							   $Check_levels=" Scientific Assistant Forward To Site Engineer ";
							}
							if($levelid == "2" && $forward_flag == "BW" && $forward_to == "1"){
							   $Check_levels=" Site Engineer BackWard To Scientific Assistant";
							}
							if($levelid == "2" && $forward_flag == "FW" && $forward_to == "3"){
							   $Check_levels=" Site Engineer Forward To Engieer Incharge ";
							}
							if($levelid == "2" && $forward_flag == "FW" && $forward_to == "4"){
							   $Check_levels=" Site Engineer Forward To Superintendent Engineer ";
							}
							if($levelid == "3" && $forward_flag == "BW" && $forward_to == "2"){
							   $Check_levels=" Enginner Incharge BackWard To Site Engineer";
							}
							if($levelid == "3" && $forward_flag == "FW" && $forward_to == "4"){
							   $Check_levels=" Enginner Incharge Forward To Superintendent Engineer";
							}
							if($levelid == "$MaxLevel" && $forward_flag == "BW" && $forward_to == "3"){
							   $Check_levels=" Superintendent Engineer Backward To Enginner incharge";
							}
							if($levelid == "$MaxLevel" && $forward_flag == "FW" && $forward_to == "0"){
							   $Check_levels=" Check Measurement Completed";
							}
							 if($levelid == "" && $forward_flag == "" && $forward_to == ""){
								$Check_levels="Checkmeasuremnet Not Done";
						   }
						   $TrTDStrCHK .= "<tr>";
						   $TrTDStrCHK .= "<td align='center'>".$slno."</td>";
						   if($WorkLevel == ""){
							  $TrTDStrCHK .= "<td align='center'>'Check Measurement level Not Assign'</td>";
							  $TrTDStrCHK .= "<td align='center'><i class='fa fa-times-circle' style='font-size:16px;color:red'></i></td>";
						   }else if($CHKCount > 0){
							  $TrTDStrCHK .= "<td align='center'>".$Check_levels."</td>";
							  $TrTDStrCHK .= "<td align='center'><i class='fa fa-check-circle' style='font-size:16px; color:green'></i></td>";
						   }else{
							  $TrTDStrCHK .= "<td align='center'>'Check Measurement Not Done'</td>";
							  $TrTDStrCHK .= "<td align='center'><i class='fa fa-times-circle' style='font-size:16px;color:red'></i></td>";
						   }
						   $TrTDStrCHK .= "</tr>";
						   $slno++;
				      	}
					  	$TrTDStrCHK .= "<tr>";
						if($CHKCount==0){ $TrTDStrCHK .= "<td style='text-align:center;padding: 0;' colspan='3'>No Records Found</td>"; }
					  	$TrTDStrCHK .= "</tr>";
					}else{
					  	$TrTDStrCHK .= "<tr>";
						if($CHKCount==0){ $TrTDStrCHK .= "<td style='text-align:center; color:#D50752;' colspan='3'>Final Bill Completed</td>"; }
					  	$TrTDStrCHK .= "</tr>";
					}
				?>
				
				<tr style="border:0px !important;">
					<td style="padding:0px; border:0px !important">
					<!--<div class="centersectionheader">Check Measurement Status RAB: <?php echo $RBN; ?></div>-->
						<table cellspacing="0" cellpadding="0" class="table table-bordered" style="margin-bottom: 0px;">
							<thead>
							    <tr><td colspan="3" style="text-align:center; background:#10478A; height:25px; vertical-align:middle; color:#fff; padding:0px;">Current RAB Check Measurement Status <?php if($RBN != ''){ echo "- RAB : ".$RBN; } ?></td></tr>
								<tr>
									<th>SNo.</th>
									<th style="text-align:center">Particulars</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
							   <?php echo $TrTDStrCHK; ?>
							</tbody>
						</table>
					</td>
				</tr>		   
				<?php  if($ActiveStatus == 1){  if(count($SAMBArr) > 0){ 
						$TrTDStr = ""; $CivilTempDate = '0000-00-00';
						$SelectStatusQuery 	= "select a.*, DATE(b.modifieddate) as civildate  from acc_log a inner join send_accounts_and_civil b on (a.linkid = b.sacid) where a.sheetid = '$sheetid' and b.sheetid = '$sheetid' and a.rbn = '$RList->rbn' and b.rbn = '$RList->rbn' ";
						$SelectStatusSql 	= mysqli_query($dbConn,$SelectStatusQuery);
						if($SelectStatusSql == true){
						 // $ACCCount 	= mysqli_num_rows($SelectStatusQuery);
							if(mysqli_num_rows($SelectStatusSql)>0){
								while($SList = mysqli_fetch_object($SelectStatusSql)){
									$MBookNo 		= $SList->mbookno;
									$MBAcStatus 	= $SList->AC_status;
									$MBLevelIds 	= $SList->staff_levelids;
									$MBLevel 		= $SList->levelid;
									$MBType 		= $SList->mtype;
									$MBGenLevel 	= $SList->genlevel;
									$MBRecDtList 	= $SList->rec_dt_list;
									$MBCompDtList	= $SList->comp_dt_list;
									$MBTypeStr 		= "";
									$MBFromCivilDt 	= $SList->civildate;
									//array_push($CivilDtArr,$MBFromCivilDt);
									if($MBFromCivilDt > $CivilTempDate){
										$MaxCivilDate = $MBFromCivilDt;
									}else{
										$MaxCivilDate = $CivilTempDate;
									}
									$CivilTempDate = $MBFromCivilDt;
									
									if($MBType == 'G'){
										if($MBGenLevel == 'staff'){
											$MBTypeStr = "General";
										}else if($MBGenLevel == "composite"){
											$MBTypeStr = "Sub-Abstract";
										}
									}else if($MBType == 'S'){
										$MBTypeStr = "Steel";
									}else if($MBType == 'A'){
										$MBTypeStr = "Abstract";
									}
									$ExpMBLevelIds = explode(",",$MBLevelIds);
									$TrTDStr .= "<tr>";
									//$TrTDStr .= "<td align='center'>".$rbn."</td>";
									$TrTDStr .= "<td align='center'>".$MBookNo."</td>";
									$TrTDStr .= "<td nowrap='nowrap'>".$MBTypeStr."</td>";
									for($i=1; $i<=5; $i++){
										if($i < $MBLevel){
											$TrTDStatusStr = "<td align='center'><i class='fa fa-check-circle' style='font-size:20px; color:green'></i></td>";
										}else if($i == $MBLevel){
											if($MBAcStatus == 'A'){
												$TrTDStatusStr = "<td align='center'><i class='fa fa-check-circle' style='font-size:16px; color:green'></i></td>";
											}else if($MBAcStatus == 'R'){
												$TrTDStatusStr = "<td align='center'><i class='fa fa-refresh' style='font-size:16px; color:#F7B506'></i></td>";
											}else{
												$TrTDStatusStr = "<td align='center'><i class='fa fa-times-circle' style='font-size:16px; color:red'></i></td>";
											}
										}else if($i > $MBLevel){
											if(in_array($i,$ExpMBLevelIds)){
												$TrTDStatusStr = "<td align='center'><i class='fa fa-check-circle' style='font-size:16px; color:green'></i></td>";
											}else{
												$TrTDStatusStr = "<td align='center'>&nbsp;</td>";
											}
										}
										$MaxPosition = "";
										/// To find a Maximum poisition of level (for last transaction of particular level)
										if(in_array($i,$ExpMBLevelIds)){
											foreach($ExpMBLevelIds as $KeyA=>$ValueA){
												if($i == $ValueA){
													$MaxPosition = $KeyA;
												}
											}
										}
										$ExpMBRecDtList 	= explode(",",$MBRecDtList);
										$ExpMBCompDtList 	= explode(",",$MBCompDtList);
										
										$RecDate 	= $ExpMBRecDtList[$MaxPosition];
										$CompDate 	= $ExpMBCompDtList[$MaxPosition];
										
										if($RecDate == ""){
											if($i == $MBLevel){
												$TemCnt = count($ExpMBRecDtList)-1;
												$RecDate = $ExpMBRecDtList[$TemCnt];
											}
										}
										if($RecDate != ""){
											$Rdate 		= date_create($RecDate);
											$RecDate 	= date_format($Rdate,"d/m/Y");
										}
										if($CompDate != ""){
											$Cdate		= date_create($CompDate);
											$CompDate 	= date_format($Cdate,"d/m/Y");
										}
										
										//$TrTDStr .= "<td>".$RecDate.$d."</td>";
										//$TrTDStr .= "<td>".$CompDate."</td>";
										$TrTDStr .= $TrTDStatusStr;
									}
									
									$TrTDStr .= "</tr>";
								}
							}
						} 
				     }else{
					   $TrTDStr .= "<tr>";
						    $TrTDStr .= "<td style='text-align:center;padding: 0;' colspan='7'>No Records Found</td>"; 
					   $TrTDStr .= "</tr>";
					 }
					 }elseif($ActiveStatus == 2){
					   $TrTDStr .= "<tr>";
						    $TrTDStr .= "<td style='text-align:center; color:#D50752;' colspan='7'>Final Bill Completed</td>"; 
					   $TrTDStr .= "</tr>";
					 }		
				?>
				<tr style="border:0px !important">
					<td style="padding:0px; border:0px !important">
						<table class="table table-bordered" style="margin-bottom: 0px;">
							<thead>
							    <tr><td colspan="7" style="text-align:center; background:#10478A; height:25px; vertical-align:middle; color:#fff; padding:0px;">Current RAB Accounts Status <?php if($RBN != ''){ echo "- RAB : ".$RBN; } ?></td></tr>
								<tr>
									<th rowspan="2">MBook No.</th>
									<th rowspan="2">MBook Type</th>
									<th colspan="1">Dealing Assistant</th>
									<th colspan="1">Accountant</th>
									<th colspan="1">AAO</th>
									<th colspan="1">AO</th>
									<th colspan="1">DCA</th>
								</tr>
								<tr>
									<th>Status</th>
									<th>Status</th>
									<th>Status</th>
									<th>Status</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
							<?php echo $TrTDStr; ?>
							</tbody>
						</table>
					</td>
				</tr>
			 </table>
			 <div class="centersectionheader">Completed RAB's List</div>
		     <?php  $count1=0;
				/*$select_sheet_rbn_query = "select rbn,sheet_id  from sheet where sheet_id = '$sheetid' ";
				echo $select_sheet_rbn_query;
				$select_sheet_rbn_sql 	= mysqli_query($dbConn,$select_sheet_rbn_query);
				if($select_sheet_rbn_sql == true){
					$SRList 		= mysqli_fetch_object($select_sheet_rbn_sql);
					$RAB            = $CRList->rbn;
					//echo $RAB;exit;
					if($RAB="NULL"){ echo "<center class='message'><br/>RAB EMPTY</center>";}
				}
				$select_meas_book_query = "select distinct a.rbn,a.sheetid,b.* from measurementbook a INNER JOIN abstractbook b ON (a.sheetid=b.sheetid)  where a.sheetid = '$sheetid' AND
										   a.rbn != '$RCount1List->rbn' ";*/
				if($ActiveStatus == 2){	
				$select_meas_book_query ="select * from abstractbook a where a.rbn != '$RBN' and a.sheetid = '$sheetid' ORDER BY a.rbn DESC";							   				
				}else{	
				$select_meas_book_query ="select * from abstractbook a where a.rbn != '$RBN' and a.sheetid = '$sheetid' ORDER BY a.rbn DESC";							   				
				}
				$select_meas_book_sql 	= mysqli_query($dbConn,$select_meas_book_query);
				if($select_meas_book_sql == true){
					$count1 	= mysqli_num_rows($select_meas_book_sql);
			 ?>
			 <table align="center" class="table table-bordered" style="border:0px; margin:0%;">
				<tr style="border:0px !important">
					<td style="padding:0px; border:0px !important">
						<table class="table table-bordered ctable">
							<thead>
								<tr>
									<th style="text-align:center; background:#DBDEDD;">RAB</th>
									<th style="text-align:center; background:#DBDEDD;">Upto Amount &#x20b9;</th>
									<th style="text-align:center; background:#DBDEDD;">Deduct Previous &#x20b9;</th>
									<th style="text-align:center; background:#DBDEDD;">Since Last &#x20b9;</th>
									<th style="text-align:center; background:#DBDEDD;">Esc Amount &#x20b9;</th>
									<th style="text-align:center; background:#DBDEDD;">Pass Order Date</th>
								</tr>
							</thead>
							<tbody>
							<?php  if($count1>0){	
								while($CRList = mysqli_fetch_object($select_meas_book_sql)){
								//$List1 		= mysqli_fetch_object($select_meas_book_sql);
								   $fromdate1 	= $CRList->fromdate;
								   $todate1 	= $CRList->todate;
								   $RAB         = $CRList->rbn;
								   $UP_TO_AMT   = $CRList->upto_date_total_amount;
								   $DPM_TO_AMT 	= $CRList->dpm_total_amount	;
								   $SLM_TO_AMT 	= $CRList->slm_total_amount;
								   $ESC_TO_AMT 	= $CRList->upto_date_total_amount_esc;
								   $PO_DATE 	= $CRList->pass_order_date;
								   if($count1>0){
									 $status1="RAB colsed";
								   }
							 ?>
							<tr>
								 <td align="center"> <?php echo $RAB; ?></td>
								 <td style="text-align:right"><?php echo $UP_TO_AMT; ?></td>
								 <td style="text-align:right"><?php echo $DPM_TO_AMT; ?></td>
								 <td style="text-align:right"><?php echo $SLM_TO_AMT; ?></td>
								 <td style="text-align:right"><?php echo $ESC_TO_AMT; ?></td>
								<td align="center"> <?php  if($PO_DATE!=00-00-000){ echo dt_display($PO_DATE); }?></td>
							</tr>
							<?php }
							}else{ $select_sheet_rbn_query = "select rbn,sheet_id  from sheet where sheet_id = '$sheetid' ";
								   $select_sheet_rbn_sql 	= mysqli_query($dbConn,$select_sheet_rbn_query);
									if($select_sheet_rbn_sql == true){
										$SRList 		= mysqli_fetch_object($select_sheet_rbn_sql);
										$RAB            = $CRList->rbn;
										if($RAB="NULL"){ ?>
							 <tr>
								 <td style="text-align:center"colspan="6"> <?php echo "No Records Found"; ?></td>
							 </tr>
							 <?php }
									}            
							} }
							?>
							</tbody>
						</table>
					</td>
				</tr>
			 </table>
       </div>
	   <div class="rightsection">
		 <div class="rightsectionheader">Quantity Details</div>
		 
		   <?php  $count1=0;  $qty_count=0; $slm_measurement_qty=0;
				  
			  /* $qty_query ="SELECT sum(a.mbtotal) as used_qty,a.sheetid,a.subdivid,b.sno, b.subdiv_id,b.sheet_id,b.total_quantity,b.deviate_qty_percent from  measurementbook_temp a INNER JOIN schdule b ON (a.subdivid=b.subdiv_id)
						   where b.sheet_id= '$sheetid' AND b.sno != '0' AND (a.part_pay_flag = '0' OR a.part_pay_flag = '1') group by a.subdivid";			   
			  
			  //echo $qty_query;
			   $qty_sql=mysqli_query($dbConn,$qty_query);
				if($qty_sql == true){
				   $qty_count 	= mysqli_num_rows($qty_sql);
				}
			 if($qty_count ==0){
				 $qty_query ="SELECT sum(a.mbtotal) as used_qty,a.sheetid,a.subdivid,b.sno, b.subdiv_id,b.sheet_id,b.total_quantity,b.deviate_qty_percent from  measurementbook a INNER JOIN schdule b ON (a.subdivid=b.subdiv_id)
				               where b.sheet_id= '$sheetid' AND b.sno != '0' AND (a.part_pay_flag = '0' OR a.part_pay_flag = '1') group by a.subdivid";			   
				 echo  $qty_query;exit;
				  $qty_sql=mysqli_query($dbConn,$qty_query);
				    if($qty_sql == true){
					   $qty_count 	= mysqli_num_rows($qty_sql);
				    }		
				}*/
				$qty_query 	= "SELECT * from schdule where sheet_id= '$sheetid' AND sno != '0' and subdiv_id!='0'";			   
			    $qty_sql	= mysqli_query($dbConn,$qty_query);
				if($qty_sql == true){
				   $qty_count 	= mysqli_num_rows($qty_sql);
				}
				
			  ?>
			 <table align="center" class="table table-bordered" style="border:0px; margin:0%;">
				<tr style="border:0px !important">
					<td style="padding:0px; border:0px !important">
						<table class="table table-bordered ltable">
							<thead>
								<tr>
									<th>Item No.</th>
									<th style="text-align:center">Agmt. Qty</th>
									<th style="text-align:center">Used Qty</th>
									<th style="text-align:center">Deviate %</th>
								</tr>
							</thead>
							<tbody>
							<?php while ($QTYList = mysqli_fetch_object($qty_sql)){ ?>
							<tr>
								<td align="center"> <?php echo $QTYList->sno; ?></td>
								<td style="text-align:right"><?php if ($QTYList->total_quantity != ""){ echo $QTYList->total_quantity; }else{ echo "0.00"; }?></td>
								
								<?php
								$TotalUsedQty = 0; 
								$use_qty_query 	= "SELECT sum(mbtotal) as used_qty, sheetid, subdivid from measurementbook where sheetid = '$sheetid' and subdivid ='$QTYList->subdiv_id' and (part_pay_flag = 0 OR part_pay_flag = 1) group by subdivid";
								$use_qty_sql 	= mysqli_query($dbConn,$use_qty_query);
								$use_qty_count 	= mysqli_num_rows($use_qty_sql);
								if($use_qty_sql == true){
									$qtyList 	= mysqli_fetch_object($use_qty_sql);
									$UsedQty    = $qtyList->used_qty;
									$TotalUsedQty = $TotalUsedQty + $UsedQty;
								}
								
								$use_qty_query 	= "SELECT sum(mbtotal) as used_qty, sheetid, subdivid from measurementbook_temp where sheetid='$sheetid' and subdivid ='$QTYList->subdiv_id' and (part_pay_flag = 0 OR part_pay_flag = 1) group by subdivid";
								$use_qty_sql	= mysqli_query($dbConn,$use_qty_query);
								if($use_qty_sql == true){
									$qtyList 	= mysqli_fetch_object($use_qty_sql);
									$UsedQty    = $qtyList->used_qty;
									$TotalUsedQty = $TotalUsedQty + $UsedQty;
								}
								$TotalUsedQty = round($TotalUsedQty,$QTYList->decimal_placed);
								?> 
								
								<td style="text-align:right"><?php if($TotalUsedQty!=""){ echo $TotalUsedQty; }else{ echo "0.00"; } ?>&nbsp;</td>
								<td style="text-align:right">
								<?php
									$UsedQtyPercent = ($TotalUsedQty * 100 / $QTYList->total_quantity);
									$UsedQtyPercent = round($UsedQtyPercent,2);
									$PercentDeviate = $UsedQtyPercent - 100;
									echo $PercentDeviate;
								?>
								</td>
							</tr>
							<?php }if($qty_count==0){ ?>
							<tr>
								<td style="text-align:center" colspan="4"><?php echo "No Records Found";?> </td>
							</tr>
							<?php } ?>
							</tbody>
						</table>
					</td>
				</tr>
			 </table>
	   </div>
	   <!--<div class="bottomsection">
		</div>
		<div class="centersection">
	   </div>-->
	   
	   <div class="div12" align="center">
			<div class="buttonsection">
			<input type="button" name="back" value="Back" id="back" class="btn btn-info" onClick="goBack();" />
			</div>
	   </div>
	   <div class="row clearrow"></div>
    </blockquote>
    </div>
  </div>
</div>
<!--==============================footer=================================-->
<footer>
	<div class="container_12" style="background:#035a85">
    	<div class="grid_12">
        	<div class="copy">
            	<a rel="nofollow" style="color:#C6C7C7; font-size:11px; font-weight:600; padding:2px 0px;">&copy; Lashron Technologies</a>
           	</div>
        </div>
   	</div>
</footer>
		
	<link rel="stylesheet" type="text/css" media="screen" href="dataTable/jquery.dataTables.min.css" />
	<script type="text/javascript" src="dataTable/jquery.dataTables.min.js"></script>
	<style>
		.dataTable th{
			text-align:center !important;
		}
		.dataTables_wrapper{
			font-family:Verdana, Arial, Helvetica, sans-serif !important;
		}
	</style>
</body>
</html>
<!--<link rel="stylesheet" href="../bootstrap-dialog/css/bootstrap-dialog.min.css">-->
<!--<script src="../bootstrap-dialog/js/bootstrap.min.js"></script>
<script src="../bootstrap-dialog/js/bootstrap-dialog.min.js"></script>
<script src="../bootstrap-dialog/js/run_prettify.min.js"></script>-->
<!------------- RECENTLY ADDED FOR DATA TABLE --------------------->	
  <link rel="stylesheet" type="text/css" media="screen" href="dataTable/jquery.dataTables.min.css" />
  <script type="text/javascript" src="dataTable/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
    /*$(document).ready(function(){ 
        $('.myTable').DataTable();
    });*/
	$('#back').click(function(){
	    $(location).attr('href', 'WorkStatusList.php')
	});
</script>
<!---------------------- DATA TABLE ENDS HERE --------------------->
<style>
	.modal-dialog {
    	width: 80%;
	}
	.modal-body{
		overflow-x:auto;
	}
	.bootstrap-dialog .bootstrap-dialog-title{
		font-weight:normal;
	}
	.content{
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	.dashboardheader
	{
		height:25px;
		background-color:#fcfcfc;
		border:1px solid #F7F7F7;
		color:#03a9f4;
		vertical-align:middle;
		line-height:25px;
	}
	.circle1 {
	  /*position: absolute;*/
	  margin-top: 7px;
	  left: 15px;
	  border-radius: 50%;
	  box-shadow: inset 1px 1px 1px 0px rgba(0, 0, 0, 0.5), inset 0 0 0 25px #03a9f4;
	  width: 20px;
	  height: 20px;
	  display: inline-block;
	}
	.text-content {
		padding-top: 3px;
		padding-bottom: 3px;
		border: 1px solid #FBFBFB;
		text-align: left;
		color: #0253A4;
		padding-left: 25px;
    }
	.height
	{
	    height:3px;
	}
	.leftsection
	{
		/*height:350px;*/
		height:1000px;
		width:37%;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
		border-right:1px solid #CCCCCC;
	}
	.contenttsection
	{
		/*height:350px;*/
		height:1000px;
		width:40%;
		color:#03a9f4;
		overflow-x: auto;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-left:1px;
		margin-top:2px;
		margin-right:1px;
		border-right:1px solid #CCCCCC;
	}
	.centersection
	{
		/*height:350px;*/
		height:500px;
		width:40%;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
	}
	.rightsection
	{
		/*height:350px;*/
		height:1000px;
		width:22%;
		color:#03a9f4;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
		overflow:auto;
		
	}
	.topcontentarea
	{
		/*height:320px;*/
		height:270px;
		width:98%;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
		
	}
	.leftsectionheader
	{
		height:20px;
		background-color:#10478A;
		border:1px solid #10478A;
		color:#ffffff;
		vertical-align:middle;
		line-height:18px;
		text-align:center;
		font-size:11px;
		font-weight:bold;
	}
	/*.workheader {
		background-color: #fff;
		border: 1px solid #02B9E2;
		color: #032FAD;
		font-family: Verdana, Arial, Helvetica, sans-serif;
		cursor: pointer;
		border-radius: 20px;
		padding: 2px;
		font-size: 11px;
		text-align:center;
    }*/
	
	.contenttopheader
	{
		height:20px;
		background-color:#10478A;
		border:1px solid #10478A;
		color:#ffffff;
		vertical-align:middle;
		line-height:18px;
		text-align:center;
		font-size:11px;
		font-weight:bold;
	}
	.centersectionheader
	{
		height:20px;
		background-color:#10478A;
		border:1px solid #10478A;
		color:#ffffff;
		vertical-align:middle;
		line-height:18px;
		text-align:center;
		font-size:11px;
		font-weight:bold;
		padding:0px;
	}
	.rightsectionheader
	{
		height:20px;
		background-color:#10478A;
		border:1px solid #10478A;
		color:#ffffff;
		vertical-align:middle;
		line-height:18px;
		text-align:center;
		font-size:11px;
		font-weight:bold;
	}
	.bottomsection
	{
		/*height:350px;*/
		height:500px;
		width:37%;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
		border-right:1px solid #CCCCCC;
	}
	.bottomsectionheader
	{
		height:20px;
		background-color:#10478A;
		border:1px solid #10478A;
		color:#ffffff;
		vertical-align:middle;
		line-height:18px;
		text-align:center;
		font-size:11px;
		font-weight:bold;

	}
	.contentbottompheader
	{
		height:20px;
		width:37%;
		background-color:#10478A;/*#39b5b9;*/
		/*background:url(images/head_bg.png);
		background-repeat:repeat-x;
		background-size:2%;*/
		border:1px solid #10478A;
		float:left;
		color:#ffffff;
		vertical-align:middle;
		line-height:18px;
		text-align:center;
	}
	.bottomcontentarea
	{
		/*height:320px;*/
		height:270px;
		width:100%;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
	}
	.leftdivmenuhead
	{
		height:47px;
		background-color:#3198db;
		margin-top:0px;
		line-height:47px;
		text-align:center;
		color:white;
	}
	.leftdivmenuhead1
	{
		height:35px;
		background-color:#3598db;/*#63a8eb;*/
		margin-top:1px;
		line-height:35px;
		text-align:center;
		color:#ffffff;
	}
	.leftdivmenu
	{
		/*height:47px;*/
		min-height:35px;
		background-color:#FFFFFF;
		border-bottom:1px solid #E4E4E4;
		vertical-align:middle;
		/*line-height:35px;*/
		line-height:25px;
		text-align:center;
		cursor:pointer;
		color:#0E02EA;
		font-weight:bold;
		font-size:11px;
	}
	.leftdivmenu:hover
	{
		background-color:#EFEFEF;
		color:#062086;
	}
	.stackbarchart
	{
		/*height:240px;*/
		/*height:330px;*/
		height:273px;
		overflow:scroll;
	}
	
	
	.stackbarchart-modal-section
	{
		/*height:240px;*/
		height:90%;
		width:100%;
	}
	.stackbarchart-modal
	{
		/*height:240px;*/
		height:90%;
		width:99%;
	}
	.stackbarchartHead
	{
		background-color:#0B79B5;
		padding-top:5px;
		padding-bottom:5px;
		padding-left:5px;
		width:99%;
		border:1px solid #FFFFFF;
		font-size:15px;
		font-weight:bold;
		color:#FFFFFF;
	}
	.contentbottom
	{
		width:100%;
		height:250px;
		padding-top:330px;
	}
	.stacked-barchart
	{
		width:99%;
		height:250px;
	}
	.workname-title
	{
		font-weight:bold;
		color:#C70360;
		width:99%;
		padding-left:3px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		
	}
	.ltable tr td, .ltable tr th, .ctable tr td, .ltable > thead > tr > th, td, .ctable > thead > tr > th, td{
		font-size:10px;
	}
</style>
