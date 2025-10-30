<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$report=0; $msg=""; $success = 0;
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'User Management'.$PTIcon."Staff List";


if($_GET['delete']!='')
{
	$staffdelete="update staff set active = 0 where staffid='" . $_GET['delete'] . "'";
	$rsstaffdelete=mysqli_query($dbConn,$staffdelete);
	
	$staffrecord="update users set active = 0 where staffid='" . $_GET['delete'] . "'";
	$rsrecord=mysqli_query($dbConn,$staffrecord);	
	
	if($rsstaffdelete == true) 
	{
		$msg = "Sucessfully Deleted..!";
		$success = 1;
	}
	else
	{
		$msg = "Unable to Delete..!!!";
	}
}
$staffsql = "SELECT staff.staffid, staff.staffcode, staff.staffname, staff.image, staff.email, staff.intercom, staff.sroleid, 
staffrole.sroleid, staffrole.levelid, staffrole.role_name, staff.designationid, staff.active, designation.designationname, 
designation.designationid, designation.active 
FROM staff 
INNER JOIN designation ON(designation.designationid = staff.designationid) 
INNER JOIN staffrole ON(staffrole.sroleid = staff.sroleid) 
WHERE staff.active = 1 AND staff.sectionid != 2 AND designation.active = 1 order by staff.staffname asc";
//echo $staffsql;exit;
$staff=mysqli_query($dbConn,$staffsql);
$RowCount =0;

if($_GET['msg']!=''){ $msg="2"; }
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<style type="text/css">
    a.fancybox img {
        border: none;
		/*  OLD STYLE
		box-shadow: 0 1px 7px rgba(0,0,0,0.6); 
		 -o-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;
		*/
        box-shadow: 0 0px 0px rgba(0,0,0,0.6);
        -o-transform: scale(1,1); -ms-transform: scale(1,1); -moz-transform: scale(1,1); -webkit-transform: scale(1,1); transform: scale(1,1); -o-transition: all 0s ease-in-out; -ms-transition: all 0s ease-in-out; -moz-transition: all 0s ease-in-out; -webkit-transition: all 0s ease-in-out; transition: all 0s ease-in-out;
    } 
    a.fancybox:hover img {
        position: relative; z-index: 999; -o-transform: scale(1.03,1.03); -ms-transform: scale(1.03,1.03); -moz-transform: scale(1.03,1.03); -webkit-transform: scale(1.03,1.03); transform: scale(1.03,1.03);
    }
</style>



<script type="text/javascript" language="javascript">
	function check_staff_mbook(staffid)
	{
		var xmlHttp;
		var data;
        var i, j;
		var ret_val = 0;
        if (window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
        	xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "check_staff_mbook.php?staffid=" + staffid;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
        	if (xmlHttp.readyState == 4)
            {
            	data = xmlHttp.responseText; 
				document.form.hid_delete_flag.value = "";
                if(data != "")
                {
                	document.form.hid_delete_flag.value = data;
                }
            }
        }
        xmlHttp.send(strURL);
	}
		
	function Delete(staffid)
	{	
		swal({   
			title: "Are you sure?",   
			text: "You will not be able to recover this Staff's data!",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes, delete it!",   
			cancelButtonText: "No, cancel plz!",   
			closeOnConfirm: false,   
			closeOnCancel: false 
		}, 
		function(isConfirm)
		{   
			if (!isConfirm) 
			{     
				swal("Cancelled", "Your data is safe :)", "");   
			} 
			else 
			{ 
				var delete_flag = document.form.hid_delete_flag.value;
				if(delete_flag == 1)
				{
					//alert(" Still MBook is active for this staff \n First delete the mbook and then delete staff..\n");
					swal(" Still MBook is active for this staff \n First delete the mbook and then delete staff..\n", "", "");
				}
				else
				{
					window.location.href='engineerlist.php?delete='+staffid;
				}
			} 
		});
	}
	function goBack()
	{
		url = "MyView.php";
		window.location.replace(url);
	}
	function Add_New()
	{
		var url = "engineer.php";
		window.location.replace(url);
	}
</script>
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
	}
</style>
</head>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
				<div class="content">
					<?php include "MainMenu.php"; ?>
					<div class="container_12">
						<div class="grid_12">
						  <div align="right"><?php /*if(in_array('ENGA', $_SESSION['ModuleRights'])){ ?><a href="engineer.php?page=engineer">AddNew&nbsp;&nbsp;</a><?php }*/ ?></div>
							<blockquote class="bq1" style="overflow:auto">
								<div class="row">
									<div class="box-container box-container-lg" align="center">
										<div class="div1">&nbsp;</div>
										<div class="div10">
											<div class="card cabox">
												<div class="face-static">
													<div class="card-header inkblue-card" align="center">Staff List - View</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
							
																	<table class="dataTable table-bordered table1" align="center" id="dataTable">
																		<thead>
																			<tr>
																				<th>&nbsp;</th>
																				<th>IC No.</th>
																				<th>Staff Name</th>
																				<th>Role Name</th>
																				<th>Designation</th>
																				<th>Intercom No.</th>
																				<th>Action</th>
																			</tr>
																		</thead>
																		<tbody>
																			<?php 
																			if($staff == false) {  } else { $RowCount = mysqli_num_rows($staff); }
																			if($staff == true && $RowCount > 0) {
																			while($List = mysqli_fetch_object($staff)) { 
																				$target_dir = "uploads/";
																				$target_file = $target_dir . basename($List->image);
																				if (file_exists($target_file)){
																					$staffimage = $List->image;
																				}else{
																					$staffimage = "profile_default.png";
																				}
																			?>
																				<tr>
																					<td align="center">
																					<img class="fancybox" title="<?php echo $List->staffname; ?>" src="uploads/<?php echo $staffimage; ?>" width="30px" height="25px"/>
																					</td>
																					<td align="center"><?php echo $List->staffcode; ?></td>
																					<td align="left"><?php echo $List->staffname; ?></td>
																					<td align="left"><?php echo $List->role_name; ?></td>
																					<td align="center"><?php echo $List->designationname; ?></td>
																					<td align="center"><?php echo $List->intercom; ?></td>
																					<td align="center">
																						<a href="engineer.php?staffid=<?php echo $List->staffid;?>" class="oval-btn-edit">
																							<button type="button" title="Edit" class="btn fa-btn-e"><i class="fa fa-edit"></i></button>
																						</a>
																						&nbsp;
																						<a href="javascript:Delete(<?php echo  $List->staffid; ?>)" class="oval-btn-delete">
																							<button type="button" title="Delete" class="btn fa-btn-d">
																								<i class="fa fa-trash-o"></i>
																							</button>
																						</a>
																					</td>
																				</tr>
																			<?php } } ?>
																		</tbody>
																	</table>
																	<input type="hidden" name="hid_delete_flag" id="hid_delete_flag">
																	<div class="row">
																		<a data-url="Administrator" class="btn btn-info">Back</a>
																		<!--<a data-url="UserCreate" class="btn btn-primary">Create New User</a>-->
																	</div>

														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="div1">&nbsp;</div>
									</div>
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
    </body>
</html>
<script>
	$(document).ready(function() {
		$('#dataTable').DataTable({ "ordering": true, "paging": true, });
		$(window).load(function() {
			$("#dataTable_wrapper").prepend('<button type="button" data-url="engineer" class="AddNewBtn" id="AddNewBtn" style=""><i class="fa fa-plus" style="font-size:13px; padding-top:2px;"></i> Add New Staff </button>');
		});
	});
	$('body').on("click","#AddNewBtn", function(event){ 
		var DatUrl = $(this).attr("data-url");
		$(location).attr("href",DatUrl+".php");
		event.preventDefault();
		return false;
	});
</script>
<style>
	.dataTables_wrapper{
		width:95% !important;
	}
</style>
