<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$sheetid = $_SESSION['Sheetid'];
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
function check_workorder_measurements($sheetid)
{
	//$SelectItemIdQuery  = "select a.subdiv_id from subdivision a inner join mbookheader b on (a.subdiv_id = b.subdivid) where a.sheet_id = '$sheetid' and b.sheetid = '$sheetid'";
	$SelectItemIdQuery  = "select distinct subdivid from mbookheader where sheetid = '$sheetid'";
	$SelectItemIdSql 	= mysql_query($SelectItemIdQuery);
	if(mysql_num_rows($SelectItemIdSql)>0){
		return 1;
	}else{
		return 0;
	}

	/*$check_workorder_measurements_sql = "select mbookheader.mbheaderid, mbookheader.sheetid, mbookdetail.mbdetail_id  from mbookheader 
	INNER JOIN mbookdetail ON (mbookdetail.mbheaderid = mbookheader.mbheaderid) 
	WHERE mbookheader.sheetid = '$sheetid' AND mbookheader.active = 1 AND   mbookdetail.mbdetail_flag != 'd'";
	$check_workorder_measurements_query = mysql_query($check_workorder_measurements_sql);
	if(mysql_num_rows($check_workorder_measurements_query)>0)
	{
		return 1;
	}
	else
	{
		return 0;
	}*/
}
//$schdulesql ="SELECT      DISTINCT sno,sch_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno <> '' AND subdiv_id !=0 ";
$schdulesql ="SELECT * FROM sheet";
$schdulequery=mysql_query($schdulesql);
$RowCount =0;
if($_GET['msg'] != "")
{
	$msg = $_GET['msg'];
}
if(isset($_POST['back']))
{
	header('Location: AgreementSheetEntry.php');
}
   if(isset($_POST['delete']))
   {
  	$del_sheeid = $_POST['chck_worder'];
	$temp = 1;
    $c = count($del_sheeid);
    for($i = 0; $i<$c; $i++)
    {
       	$delete_sheet_sql 				= 	"DELETE FROM sheet WHERE sheet_id = '$del_sheeid[$i]'" ;
       	$delete_sheet_query 			= 	mysql_query($delete_sheet_sql);
	   	if($delete_sheet_query == false) { $temp = 0; }
	   
	   	//$select_schdule_sql 			= 	"SELECT * FROM schdule WHERE sheet_id = '$del_sheeid[$i]'";
	   	//$select_schdule_query 			= 	mysql_query($select_schdule_sql);
	   	//if(mysql_num_rows($select_schdule_query)>0)
	   	//{
		   $delete_schdule_sql 			= 	"DELETE FROM schdule WHERE sheet_id = '$del_sheeid[$i]'" ;
		   $delete_schdule_query 		= 	mysql_query($delete_schdule_sql); 
		   if($delete_schdule_query == false) { $temp = 0; }
	   	//}
	   
	   	//$select_division_sql 			= 	"SELECT * FROM division WHERE sheet_id = '$del_sheeid[$i]'";
	   	//$select_division_query 			= 	mysql_query($select_division_sql);
	   	//if(mysql_num_rows($select_division_query)>0)
	   	//{
		   $delete_division_sql 		= 	"DELETE FROM division WHERE sheet_id = '$del_sheeid[$i]'" ;
		   $delete_division_query 		= 	mysql_query($delete_division_sql); 
		   if($delete_division_query == false) { $temp = 0; }
		//}
	   	//$select_subdivision_sql 		= 	"SELECT * FROM subdivision WHERE sheet_id = '$del_sheeid[$i]'";
	   	//$select_subdivision_query 		= 	mysql_query($select_subdivision_sql);
	   	//if(mysql_num_rows($select_subdivision_query)>0)
	   	//{
		   $delete_subdivision_sql 		= 	"DELETE FROM subdivision WHERE sheet_id = '$del_sheeid[$i]'" ;
		   $delete_subdivision_query 	= 	mysql_query($delete_subdivision_sql);
		   if($delete_subdivision_query == false) { $temp = 0; }
		//}
	 }
	header('Location: AgreementEntryView.php?msg='.$temp);
  }
?>
<?php require_once "Header.html"; ?>
     <style>
            .container{
    display:table;
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
<script type="text/javascript" language="javascript">
    $(function() {
		$("#delete").click(function() {  // triggred submit
			var count_checked = $("[name='chck_worder[]']:checked").length; // count the checked
			if(count_checked == 0) 
			{
				//alert("Please select a row to delete.");
				sweetAlert("Please select a row to delete", "", "");
				return false;
			}
			if(count_checked == 1) 
			{
				return confirm("Are you sure you want to delete these row?");
			} 
			else 
			{
				return confirm("Are you sure you want to delete these rows?");
			}
		});
    });
	
	/*$(function() {
		$("#delete").click(function() {  // triggred submit
			var count_checked = $("[name='chck_worder[]']:checked").length; // count the checked
			if(count_checked == 0) 
			{
				//alert("Please select a row to delete.");
				sweetAlert("Please select a row to delete", "", "error");
				return false;
			}
			if(count_checked == 1) 
			{
				return confirm("Are you sure you want to delete these row?");
			} 
			else 
			{
				return confirm("Are you sure you want to delete these rows?");
			}
		});
    });*/
/*$(function () {
	$.fn.validatedeleterow = function(event) { 
		var count_checked = $("[name='chck_worder[]']:checked").length;
		if(count_checked == 0) 
		{
				//alert("Please select a row to delete.");
			sweetAlert("Please select a row to delete", "", "error");
			return false;
		}
		if(count_checked == 1) 
		{
			//return confirm("Are you sure you want to delete these row?");
			swal({
				  title: "Are you sure?",
				  text: "You will not be able to recover this imaginary file!",
				  type: "warning",
				  showCancelButton: true,
				  confirmButtonColor: "#DD6B55",
				  confirmButtonText: "Yes, delete it!",
				  cancelButtonText: "No, cancel plx!",
				  closeOnConfirm: false,
				  closeOnCancel: false
				},
				function(isConfirm){
				  if (isConfirm) 
				  {
					//swal("Deleted!", "Your imaginary file has been deleted.", "success");
					var x = 10;
				  } 
				  else 
				  {
					swal("Cancelled", "Your imaginary file is safe :)", "error");
					event.preventDefault();
					event.returnValue = false;
				  }
			});
		} 
		else 
		{
			return confirm("Are you sure you want to delete these rows?");
		}
	}
	$("#delete").click(function(event){
		$(this).validatedeleterow(event);
	});
	$("#top").submit(function(event){
		$(this).validatedeleterow(event);
	});
});*/
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
                <div class="title">View Agreement Sheet</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow:auto">
							<div class="container">
								<div>&nbsp;</div>
								<table width="100%" border="0" align="center" class="table1 table2" id="dataTable">
									<thead>
										<tr class="note" style="background-color:#E5E5E5;">
											<th colspan="9" align="center">List of Works </th>
										</tr>
										<tr class="note heading">
											<th>&nbsp;</th>
											<th align="center" valign="middle">SNo.</th>
											<th align="center" valign="middle">Work Order No.</th>
											<th align="center" valign="middle">Work ShortName</th>
											<th align="center" valign="middle">T.S. No.</th>
											<th align="center" valign="middle">Name of Contractor</th>
											<th align="center" valign="middle">Agreement No.</th>
											<th align="center" valign="middle">C.C.No.</th>
											<th align="center" valign="middle">W.O.Date</th>
										</tr>
									</thead>
									<tbody>
									<?php $RowCount = mysql_num_rows($schdulequery); if($RowCount == 0){ ?>
										<tr>
											<td colspan="9">No Records Found</td>
										</tr>
									<?php }else{ $sno = 1; while($List = mysql_fetch_object($schdulequery)){ 
											$assigned_staff = $List->assigned_staff;
											$AssignStaff = explode(",",$assigned_staff);
											if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1)){
									?>
										<tr>
											<td>
												<?php
												$check = check_workorder_measurements($List->sheet_id);
												if($check == 0){ ?>
													<input type="checkbox" name="chck_worder[]" id="chck_worder" value="<?php echo $List->sheet_id; ?>">
												<?php }else{ ?>
													<input type="checkbox" name="chck_worder[]" id="chck_worder" disabled="disabled" value="<?php echo $List->sheet_id; ?>">
												<?php } ?>
											</td>
											<td align="center"><?php echo $sno; ?></td>
											<td>
												<?php
												if($check == 0){ ?>
													<a href="AgreementSheetEntry.php?sheet_id=<?php echo $List->sheet_id; ?>"><u><?php echo $List->work_order_no; ?></u> </a>
												<?php }else{ ?>
													<a class="tooltipwarning" title="Already Measurements Entered for this work order. Unable to Edit."><?php echo $List->work_order_no; ?></a>
												<?php } ?>
											</td>
											<td><?php echo $List->short_name; ?></td>
											<td><?php echo $List->tech_sanction; ?></td>
											<td><?php echo $List->name_contractor; ?></td>
											<td><?php echo $List->agree_no; ?></td>
											<td><?php echo $List->computer_code_no; ?></td>
											<td><?php echo dt_display($List->work_order_date); ?></td>
										</tr>
										<?php $sno++; } } } ?>
									</tbody>
								</table>
                            </div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="submit" name="back" value="Back">
								</div>
								<div class="buttonsection">
									<input type="submit" name="delete" id="delete" value=" Delete " />
								</div>
							</div>
                        </blockquote>
						
                        <div>&nbsp;</div>
					</div>
				</div>	
			</div>
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
				var msg = "<?php echo $msg; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
					if(msg != "")
					{
						if(msg == "1")
						{
							swal("", "Sucessfully Deleted...!!!", "success");
							/*swal({
								title: titletext,
								text: "Sucessfully Deleted...!!!",
								//timer: 4000,
								showConfirmButton: true
							});*/
						}
						if(msg == "0")
						{
							sweetAlert("Something Error...!!!", "", "");
						}
					}
				};
			</script>
        </form>
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
		width:98% !important;
	}
</style>
