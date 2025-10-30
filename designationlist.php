<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
$msg = '';
function check_designation($designation_id)
{
	$check_designation_sql = "select * from staff where active = 1 AND designationid = '$designation_id'";
	$check_designation_query = mysql_query($check_designation_sql);
	if(mysql_num_rows($check_designation_query)>0)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}
$staffid="select designationid from staff";
$rstaffid=mysql_query($staffid);

$descid='';
while($rows=mysql_fetch_assoc($rstaffid))
{
	$descid=$descid.','.$rows['designationid'];
}
$Descid=ltrim($descid,',');
if($_GET['delete']!='')
{
   $designationdel="update designation set active = 0 where designationid='" . $_GET['delete'] . "'";
   $deleteresult=mysql_query($designationdel);
   if($deleteresult==true)
   {
   		$msg = "Sucessfully Deleted";
		$success = 1;
   }
   else
   {
   		$msg = "Unable to Delete";
   }
}
$RowCount = 0;
$SelectDesignQuery = "SELECT  designationid, designationname, active, userid FROM designation where active=1 AND sectionid != 2 ORDER BY designationid ASC  ";
$SelectDesignSql   = mysql_query($SelectDesignQuery);
if($SelectDesignSql == true){
	if(mysql_num_rows($SelectDesignSql)>0){
		$RowCount = 1;
	}
}
?>
<?php include "Header.html"; ?>

        <script language="JavaScript" type="text/javascript" src="script/Date_Calendar.js"></script>
        <script language="JavaScript" type="text/javascript" src="script/validfn.js"></script>
        <script type="text/javascript" src="js/menuscripts.js"></script>
		<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
		<script type="text/javascript" language="javascript">
		flag=0;
		function Delete(designationid)
		{	
			flag=0;
			id=document.phuploader.txtdescid.value;
			if(id!='') 
			{
				id=$('#txtdescid').val();
				var val=id.split(',');	
				var unique=val.filter(function(itm,i,a){
				return i==val.indexOf(itm);
				});
				
				var g=unique;
				var uniquevalue=g.length;
				for(i=0;i<uniquevalue;i++)
				{
					
					if(g[i] == designationid)
					{
						flag=0;
					}
					else 
					{ 
						flag=1; 
					}
				 }
				if(flag==0) 
				{ 
					alert("Designation Already Assigned to Engineer"); 
				}
				 else 
				{
					swal({   title: "Are you sure?",   text: "You will not be able to recover this designation!",     showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   cancelButtonText: "No, cancel plz!",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (!isConfirm) {     swal("Cancelled", "Your data is safe :)", "");   } else { window.location.href='designationlist.php?delete='+designationid; } });
					
					/*if(confirm("Do You Want to Delete this Designation?"))               
					{
						window.location.href='designationlist.php?delete='+designationid;	 
					}*/
				}
		  	}
			else 
			{
				swal({   title: "Are you sure?",   text: "You will not be able to recover this designation!",     showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   cancelButtonText: "No, cancel plz!",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (!isConfirm) {     swal("Cancelled", "Your data is safe :)", "");   } else { window.location.href='designationlist.php?delete='+designationid; } });
				/*if(confirm("Do You Want to Delete this Designation?"))               
				{
					window.location.href='designationlist.php?delete='+designationid;	 
				}*/
			}
		 }
		 function goBack()
		 {
			var url = "dashboard.php";
			window.location.replace(url);
		 }
		</script>	
		<SCRIPT type="text/javascript">
			window.history.forward();
			function noBack() { window.history.forward(); }
		</SCRIPT>
		<SCRIPT type="text/javascript">
		 function Add_New()
		 {
			var url = "designation.php";
			window.location.replace(url);
		 }
		</script>	

    </head>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<script src="dashboard/MyView/bootstrap.min.js"></script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<div class="title">View Designation Details</div>
				<input type="hidden" name="txtdescid" id="txtdescid" value="<?php echo $Descid; ?>"/>
				<div class="container_12">
					<div class="grid_12" align="center">
						<blockquote class="bq1" id="bq1" style="overflow:auto">
								<br/>
								<table class="table-bordered table1" align="center" id="dataTable">
									<thead>
										<tr>
											<th>SNo.</th>
											<th>Designation Name</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php 
									$sno = 1; 
									if($RowCount == 1){ while($DesigList = mysql_fetch_object($SelectDesignSql)){ 
										$check_design = check_designation($DesigList->designationid);
									?>
										<tr>
											<td align="center"><?php echo $sno; ?></td>
											<td><?php echo $DesigList->designationname; ?></td>
											<td align="center" style="width:250px" nowrap="nowrap">
											<?php if($check_design == 0){ ?>
												<a href='designation.php?designationid=<?php echo $DesigList->designationid;?>' <?php echo ModuleRights('DESE'); ?> class="oval-btn-edit">
												<i style="font-size:12px; padding-top:5px;" class="fa">&#xf044;</i> Edit
												</a>
												&nbsp;
												<a href="javascript:Delete(<?php echo  $DesigList->designationid; ?>)" <?php echo ModuleRights('DESD'); ?> class="oval-btn-delete">
												<i style="font-size:12px; padding-top:5px; font-weight:100" class="fa">&#xf00d;</i> Delete
												</a>
												
											<?php }else{ ?>
												<a class="tooltipwarning oval-btn-edit" title="This designation is assigned to staff. Unable to Edit." <?php echo ModuleRights('DESE'); ?>>
												<i style="font-size:12px; padding-top:5px;" class="fa">&#xf044;</i> Edit
												</a>
												&nbsp;
												<a class="tooltipwarning oval-btn-delete" title="This designation is assigned to staff. Unable to Delete." <?php echo ModuleRights('DESD'); ?>>
												<i style="font-size:12px; padding-top:5px; font-weight:100" class="fa">&#xf00d;</i> Delete
												</a>
											<?php } ?>
											</td>
										</tr>
									<?php $sno++; } }else{ ?>
										<tr>
											<td colspan="3" align="center"> No Records Found</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection"><input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/></div>
								<?php if(in_array('DESA', $_SESSION['ModuleRights'])){ ?>
								<div class="buttonsection"><input type="button" class="backbutton" <?php echo ModuleRights('DESA'); ?> name="AddNew" id="AddNew" value="AddNew" onClick="Add_New();"/></div>
								<?php } ?>
							</div>
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
            <?php include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
			<script>
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					/*swal({
						title: titletext,
						text: msg,
						//timer: 4000,
						showConfirmButton: true
					});*/
					//swal("", "Sucessfully Deleted...!", "success");
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
        </form>
		<script src="js/Resize-Page-Auto.js"></script>
    </body>
</html>
<script>
	$(document).ready(function() {
		
		$('#dataTable').DataTable({
			responsive: true,
			paging: true, 
		});
	});
</script>
<style>
	.dataTables_wrapper{
		width:70% !important;
	}
</style>

