<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
function get_workorderno($sheetid)
{
	$workorderno_query = "select work_order_no, short_name, active from sheet WHERE sheet_id = '$sheetid'";// AND active=1";
	$workorderno_sql = mysql_query($workorderno_query);
	$workorderno = @mysql_result($workorderno_sql,0,'work_order_no');
	$short_name = @mysql_result($workorderno_sql,0,'short_name');
	$active = @mysql_result($workorderno_sql,0,'active');
	return $workorderno."*".$active."*".$short_name;
}
$staffid = $_SESSION['sid'];
if($_GET['sheetid'] != ""){
	$sheetid = $_GET['sheetid'];
	if($_SESSION['isadmin'] == 1){
    	$distinctsheet 	= "select DISTINCT staffid,sheetid from mbookallotment where sheetid='$sheetid' order by mballotmentid";
	}else{
    	$distinctsheet 	= "select DISTINCT staffid,sheetid from mbookallotment where sheetid='$sheetid' and staffid = '$staffid' order by mballotmentid";
	}
}else{
	if($_SESSION['isadmin'] == 1){
    	$distinctsheet	= "select DISTINCT staffid, sheetid from mbookallotment order by mballotmentid";
	}else{
    	$distinctsheet	= "select DISTINCT staffid, sheetid from mbookallotment where staffid = '$staffid' order by mballotmentid";
	}
}
//echo $distinctsheet;
$rsdistinct=mysql_query($distinctsheet);
$RowCount =0;
if($_GET['msg'] != "")
{
	$msgflag = $_GET['msg'];
	if($msgflag == 1)
	{
		$msg = "Selected MBook removed sucessfully..!!";
		$success = 1;
	}
	else
	{
		$msg = "Error..!!";
	}
}
?>
<?php include "Header.html"; ?>
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
		font-size:11px;
		}
        </style>
    <script type="text/javascript" language="javascript">
   	   function goBack()
	   {
	   		url = "MBookAllotment.php";
			window.location.replace(url);
	   }
	   function goBackUser()
	   {
	   		url = "MyWorks.php";
			window.location.replace(url);
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
    		<div class="title">Staff - Wise MBook Allotment List</div>
          	<div class="container_12">
				<div class="grid_12">
					<blockquote class="bq1" style="overflow:auto">
              		<div align="right"><a href="MBookAllotment.php?" <?php echo ModuleRights('MSTA'); ?>>AddNew</a>&nbsp;&nbsp;</div>
                    	<div class="container" align="center" >
					
					
							<table width="99%" class="table1 table2" id="example">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Work Order No.</th>
										<th>Work Name</th>
										<th>Engineer Name</th>
										<th>General MBook</th>
										<th>Steel MBook</th>
										<th>Abstract MBook</th>
										<th>Escalation MBook</th>
										<th>Operation</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$slno = 1; 
								if($rsdistinct == false){  }else{ $RowCount = mysql_num_rows($rsdistinct); }
								if($rsdistinct == true && $RowCount > 0){
									$rsdistinct=mysql_query($distinctsheet);
									while($List = mysql_fetch_object($rsdistinct)){ 
										$staffname_sql 	= "select DISTINCT sheet.work_order_no,staff.staffname from sheet,staff INNER JOIN mbookallotment ON(mbookallotment.staffid = staff.staffid) where mbookallotment.active = 1 AND staff.active = 1 AND mbookallotment.sheetid ='$List->sheetid' and staff.staffid='$List->staffid'";
										$rstaffname 	= mysql_query($staffname_sql);
										$mbNo = '';
										$mbno 		= "select agreementmbookallotment.mbno,agreementmbookallotment.mbooktype from agreementmbookallotment INNER JOIN mbookallotment ON (mbookallotment.allotmentid = agreementmbookallotment.allotmentid) where agreementmbookallotment.active = 1 AND mbookallotment.active = 1 AND agreementmbookallotment.sheetid='$List->sheetid' and mbookallotment.staffid='$List->staffid' order by agreementmbookallotment.mbno";
										$rsgetmbno 	= mysql_query($mbno);
										$GMBArr = array(); $SMBArr = array(); $AMBArr = array(); $EMBArr = array();
										while($res=mysql_fetch_object($rsgetmbno)){
											if($res->mbooktype == "G"){
												 array_push($GMBArr,$res->mbno);
											}
											if($res->mbooktype == "S"){
												 array_push($SMBArr,$res->mbno);
											}
											if($res->mbooktype == "A"){
												 array_push($AMBArr,$res->mbno);
											}
											if($res->mbooktype == "E"){
												 array_push($EMBArr,$res->mbno);
											}
										}
										$sheetresult 	= get_workorderno($List->sheetid);
										$exploderesult 	= explode("*",$sheetresult);
										$workorderno 	= $exploderesult[0];
										$active 		= $exploderesult[1];
										$short_name 	= $exploderesult[2];
								?>
									<tr>
										<td align="center"><?php echo $slno; ?></td>
										<td align="center"><?php echo $workorderno; ?></td>
										<td><?php echo $short_name; ?></td>
										<td nowrap="nowrap"><?php echo @mysql_result($rstaffname,0,'staffname'); ?></td>
										<td align="center"><?php echo implode(', ',$GMBArr); ?></td>
										<td align="center"><?php echo implode(', ',$SMBArr); ?></td>
										<td align="center"><?php echo implode(', ',$AMBArr); ?></td>
										<td align="center"><?php echo implode(', ',$EMBArr); ?></td>
										<td align="center">
											<?php if($active == 1){ ?>
												<?php if($_SESSION['isadmin'] == 1){ ?>
													<a href='MBookAllotmentEditPage.php?sheetid=<?php echo $List->sheetid;?>&staffid=<?php echo $List->staffid; ?>' <?php //echo ModuleRights('MASE'); ?> class="oval-btn-delete">Remove</a>
												<?php }else{ ?>
													<a  style="pointer-events:none; color:#666666;" class="oval-btn-delete">Remove</a>
												<?php } ?>
											<?php }else{ ?>
												<a class="tooltipwarning oval-btn-disable" title="This workorder is Inactive">Remove</a>
											<?php } ?>
										</td>
									</tr>
								<?php $slno++; $GMbNo = "";$SMbNo = ""; $AMbNo = ""; } }else{ ?>
									<tr><td colspan="9">No Records Found</td></tr>
								<?php } ?>
								</tbody>
							</table>
					
					
						<?php 
						/*if ($rsdistinct == false) {  } else { $RowCount = mysql_num_rows($rsdistinct);    }
						if ($rsdistinct == true && $RowCount > 0) {
						?>
						<div class="heading">
						<div class="col labelcontenthead" style=" width: 5%">S.No</div>
						<div class="col labelcontenthead" align="left" >&nbsp;Work Order No.</div>
						<div class="col labelcontenthead" align="left">&nbsp;Work Name</div>
						<div class="col labelcontenthead" align="left">&nbsp;Engineer Name</div>
						<div class="col labelcontenthead"> Mbook No.
											<div>
												<table width="100%">
													<tr>
														<td class="col labelcontenthead" width="33%">General</td>
														<td class="col labelcontenthead" width="33%">Steel</td>
														<td class="col labelcontenthead" width="33%">Abstract</td>
													</tr>
												</table>
											</div>
											</div>
						<div class="col labelcontenthead" style=" width: 8%">Operation</div>
						</div>
						<?php 
						$rsdistinct=mysql_query($distinctsheet);
						while ($List = mysql_fetch_object($rsdistinct)) { 
						$staffname_sql="select DISTINCT sheet.work_order_no,staff.staffname from sheet,staff INNER JOIN mbookallotment ON(mbookallotment.staffid = staff.staffid) where mbookallotment.active = 1 AND staff.active = 1 AND mbookallotment.sheetid ='$List->sheetid' and staff.staffid='$List->staffid'";
						$rstaffname=mysql_query($staffname_sql);
											
											//$MBOOK='<SPAN ></SPAN>'
						$mbNo='';
						$mbno="select agreementmbookallotment.mbno,agreementmbookallotment.mbooktype from agreementmbookallotment INNER JOIN mbookallotment ON (mbookallotment.allotmentid = agreementmbookallotment.allotmentid) where agreementmbookallotment.active = 1 AND mbookallotment.active = 1 AND agreementmbookallotment.sheetid='$List->sheetid' and mbookallotment.staffid='$List->staffid' order by agreementmbookallotment.mbno";
						$rsgetmbno=mysql_query($mbno);
										   // echo $mbno;
											
						while($res=mysql_fetch_object($rsgetmbno)) 
						 {
							if($res->mbooktype == "G")
							{
								 $GMbNo .= $res->mbno.', ';
							}
							if($res->mbooktype == "S")
							{
								 $SMbNo .= $res->mbno.', ';
							}
							if($res->mbooktype == "A")
							{
								 $AMbNo .= $res->mbno.', ';
							}
						}
						$sheetresult = get_workorderno($List->sheetid);
						$exploderesult = explode("*",$sheetresult);
						$workorderno = $exploderesult[0];
						$active = $exploderesult[1];
						$short_name = $exploderesult[2];
						?>
						<div class="table-row label"><?php $sno++; ?>
						<div class="col label"><center><?php echo $sno.'.'; ?></center></div>
						<div class="col label">&nbsp;<?php echo $workorderno; ?></div>
						<div class="col label">&nbsp;<?php echo $short_name; ?></div>
						<div class="col label" align="left">&nbsp; <?php echo @mysql_result($rstaffname,0,'staffname'); ?></div>
						<div class="col">
												<?php //echo trim($mbNo,','); ?>
												<div>
													<table width="100%">
														<tr>
															<td class="col label" width="33%"><center><?php echo trim($GMbNo,','); ?></center></td>
															<td class="col label" width="33%"><center><?php echo trim($SMbNo,','); ?></center></td>
															<td class="col label" width="33%"><center><?php echo trim($AMbNo,','); ?></center></td>
														</tr>
													</table>
												</div>
											</div>
						<div class="col">
						<center>
						<?php
						if($active == 1)
						{
						?>
							<a href='MBookAllotmentEditPage.php?sheetid=<?php echo $List->sheetid;?>&staffid=<?php echo $List->staffid; ?>' <?php echo ModuleRights('MASE'); ?>>&nbsp;&nbsp;Edit</a>
						<?php
						}
						else
						{
						?>
							<a class="tooltipwarning" title="This workorder is Inactive">&nbsp;&nbsp;Edit</a>
						<?php
						}
						?>
						</center>
						</div>
						</div>
						<?php
											$GMbNo = "";$SMbNo = ""; $AMbNo = "";
						}
						?>
						<?php
						}
						else { echo "<center class='message'><br/>No Records Found..</center>"; }*/ ?>	
				
				
				
				
				
						</div>
						<table width="100%">
							<tr>
								<td align="center">&nbsp;</td>
							</tr>
							<tr>
								<td align="center">
									<?php if($_SESSION['isadmin'] == 1) { ?>
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
									<?php }else{ ?>
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBackUser();"/>
									<?php } ?>
								</td>
							</tr>
						</table>
						<div class="row clearrow"></div>
				  	</blockquote>
				</div>
			</div>
   		</div>
<!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
	$(document).ready(function() { 
		$('#example').DataTable(); 
	} );
</script>
<script>
	/*var msg = "<?php echo $msg; ?>";
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
	};*/
</script>
        </form>
    </body>
</html>