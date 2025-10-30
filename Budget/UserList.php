<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
checkUser();
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'User Management'.$PTIcon."User Creation";
$staffid 	 = $_SESSION['sid'];
$RowCount 	 = 0;


if($_GET['reset']!='')
{
	$staffrecord="update users set password = md5(username) where userid='" . $_GET['reset'] . "'";
	$rsrecord=mysqli_query($dbConn,$staffrecord);
	if($rsrecord == true)
	{
		$msg="User Reset Successfully";
		$success = 1;
	}
	else
	{
		$msg="Unable to reset User. Try again";
		$success = 0;
	}	
}


$SelectQuery = "SELECT a.*, b.*, c.* FROM staff a INNER JOIN designation b ON (a.designationid = b.designationid) INNER JOIN users c ON (a.staffid = c.staffid) WHERE a.active = 1 AND c.active = 1 AND a.sectionid != 2 ORDER BY a.staffname ASC";
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$RowCount = 1;
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">

<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function ResetUser(staffid)
	{	
		swal({
			title: "Are you sure?",
			text: "If you reset, UserID and Password will be ICNO",
			type: "",
			showCancelButton: true,
			confirmButtonColor: '#035a85',
			cancelButtonColor: '#E53004',
			confirmButtonText: ' Reset ',
			cancelButtonText: " Cancel ",
			closeOnConfirm: false,
			closeOnCancel: true
		},
		function(isConfirm) {
			if (isConfirm) {
			   window.location.href='UserList.php?reset='+staffid; 
			}
		});
	}
</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="EstimateView.php" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Users List - View</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table class="dataTable">
																	<thead>
																		<tr>
																			<th>SNo.</th>
																			<th>ICNO.</th>
																			<th>Staff Name</th>
																			<th>E-Mail</th>
																			<th>Designation</th>
																			<th>Action</th>
																		</tr>
																	</thead>
																	<tbody>
																	<?php $Sno = 1; if($RowCount == 1) { while($List = mysqli_fetch_object($SelectSql)){ ?>
																		<tr>
																			<td align="center"><?php echo $Sno; ?></td>
																			<td align="center"><?php echo $List->staffcode; ?></td>
																			<td><?php echo $List->staffname; ?></td>
																			<td><?php echo $List->email; ?></td>
																			<td align="center"><?php echo $List->designationname; ?></td>
																			<td align="center">
																			<a data-url="UserCreate?userid=<?php echo $List->userid; ?>" id="<?php echo $List->staffid; ?>"><button type="button" title="Edit" class="btn fa-btn-e"><i class="fa fa-edit"></i></button></a>
																			<a id="<?php echo $List->userid; ?>" class="delete"><button type="button" title="Delete" class="btn fa-btn-d gDelete" data-id="<?php echo $DetailList->id; ?>"><i class="fa fa-trash-o"></i></button></a>
																			<a href="javascript:ResetUser(<?php echo  $List->userid; ?>)"><button type="button" title="Reset Password" class="btn fa-btn-e"><i class="fa fa-refresh"></i></button></a>
																			</td>
																		</tr>
																	<?php $Sno++; } } ?>
																	</tbody>
																</table>
																<div class="row">
																	<a data-url="Administrator" class="btn btn-info">Back</a>
																	<!--<a data-url="UserCreate" class="btn btn-primary">Create New User</a>-->
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="div2">&nbsp;</div>
								</div>
							</div>
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
$(document).ready(function(){ 
	$('.dataTable').DataTable({"paging":true,"ordering": true});
	$(window).load(function() {
		$("#DataTables_Table_0_wrapper").prepend('<button type="button" data-url="UserCreate" class="AddNewBtn" id="AddNewBtn" style=""><i class="fa fa-plus" style="font-size:13px; padding-top:2px;"></i> Add New User </button>');
	});
	$('body').on("click","#AddNewBtn", function(event){ 
		var DatUrl = $(this).attr("data-url");
		$(location).attr("href",DatUrl+".php");
		event.preventDefault();
		return false;
	});
	$(".delete").click(function(){ 
    	var deleteid = $(this).attr('id'); 
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DeleteData.php', 
			data: { deleteid: deleteid, pageid : "USER_DEL" }, 
			dataType: 'json',
			success: function (data) { 
				if(data == 1){ 
					var msg = "successfully deleted !";
					BootstrapDialog.show({
						message: msg,
						buttons: [{
							label: ' OK ',
							action: function(){
								$(location).attr('href', 'UserList.php')
							}
						}]
					});
						//$(location).attr('href', 'ContractorEntryView.php');
				}else{
					var msg = "Sorry, unable to delete. Please try again !";
					BootstrapDialog.alert(msg);
				}
			}
		});
	});
});
</script>
<script>
	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
	if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						dialog.close();
						window.location.replace('UserList.php');
					}
				}]
			});
		}
	};
</script>
