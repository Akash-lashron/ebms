<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Home'.$PTIcon.'SOR Confirm List';

include "DefaultMaster.php";
$msg = ""; $del = 0; $RowCount =0;
$staffid = $_SESSION['sid'];
if(isset($_POST['btn_confirm']) == ' Confirm '){
	$CHeckAll = $_POST['checkDS'];
	$CountCheck = count($CHeckAll);
	$Updated = 0;
	if($CountCheck > 0){
		for($i=0; $i<$CountCheck; $i++){
			$DataSheetId = $CHeckAll[$i];
			$UpdateQuery = "update datasheet_master set ds_release = 'Y' where ref_id = '$DataSheetId'";
			$UpdateSql 	 = mysqli_query($dbConn,$UpdateQuery);
			if($UpdateSql == true){
				$Updated++;
			}
		}
	}
	if($Updated > 0){
		if($Updated == $CountCheck){
			$msg = "Data sheet confirmed successfully.";
		}else{
			$msg = "Data sheet not confirmed. Please try again.";
		}
	}
}
$RowCount = 0;
//$SelectQuery = "select a.*, b.* from datasheet_master a inner join datasheet_a1_details b on (a.ref_id = b.ref_id) where a.group_id LIKE '01%'";
$SelectQuery = "select * from datasheet_master where ds_release IS NULL OR ds_release = ''";
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$RowCount = 1;
	}
}
$_SESSION['ViewDSUrl'] = basename($_SERVER['REQUEST_URI']);
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">

<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<blockquote class="bq1 stable" style="overflow:auto">
							<div class="div12" align="center" style="height:4px"></div>
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1 dataTable">
										<thead>
											<tr>
												<th align="center" colspan="6" style="background:#10478A; color:#ffffff; border-color:#10478A">Data Sheet - Area Cleaning and Jungle Clearance - View</th>
											</tr>
											<tr>
												<th nowrap="nowrap">&nbsp;SNo.&nbsp;&nbsp;</th>
												<th nowrap="nowrap">&nbsp;Item Code&nbsp;</th>
												<th>Item Desc.</th>
												<th nowrap="nowrap">&nbsp;Item Qty.&nbsp;</th>
												<th nowrap="nowrap">&nbsp;Item Unit&nbsp;</th>
												<th align="center" nowrap="nowrap">&nbsp;<input type="checkbox" name="checkAll" id="checkAll" value="ALL">&nbsp;</th>
											</tr>
										</thead>
										<tbody>
										<?php $Slno = 1; if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
											<tr>
												<td align="center"><?php echo $Slno; ?></td>
												<td align="center">
												<?php if($List->new_merge == "N"){ ?>
												<a data-url="DataSheetViewNew?refid=<?php echo $List->ref_id; ?>&Action=C"><u><?php echo $List->type; ?></u></a>
												<?php }else if($List->new_merge == "M"){ ?>
												<a data-url="DataSheetViewMerge?refid=<?php echo $List->ref_id; ?>&Action=C"><u><?php echo $List->type; ?></u></a>
												<?php }else{ ?>
												
												<?php } ?>
												</td>
												<td align="justify"><?php echo $List->group3_description; ?></td>
												<td align="right"><?php echo $List->quantity; ?></td>
												<td align="center"><?php echo $List->unit; ?></td>
												<td align="center">
													<input type="checkbox" class="checkDS" name="checkDS[]" id="checkDS<?php echo $List->ref_id; ?>" value="<?php echo $List->ref_id; ?>">
												</td>
											</tr>
										<?php $Slno++; } }else{ ?>
											<tr>
												<td colspan="6" align="center"> No Records Found </td>
											</tr>
										<?php } ?>
										</tbody>
									</table>
								</div>
								<div class="div2" align="center">&nbsp;</div>
							</div>
							<div class="div12" align="center">
								<a data-url="Home" class="btn btn-info">Back</a>
								<input type="submit" name="btn_confirm" id="btn_confirm" class="btn btn-info" value=" Confirm ">
							</div>
							<div class="div12" align="center">&nbsp;</div>
						</blockquote>
						<div class="div12" align="center" style="height:4px"></div>
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
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
$(document).ready(function(){ 
	$('.dataTable').DataTable({'aoColumnDefs': [{ 'bSortable': false,'aTargets':[5] }],"paging":false});
	$("#checkAll").click(function(){
		$('.checkDS').prop('checked', this.checked);
	});
	$(".checkDS").click(function(){
        if($(this).is(":checked")){
        	var isAllChecked = 0;
            $(".checkDS").each(function(){
                if(!this.checked){
            		isAllChecked = 1;
				}
            });
            if(isAllChecked == 0){
               $("#checkAll").prop("checked", true);
            }     
        }else{
            $("#checkAll").prop("checked", false);
        }
    });
	var KillEvent = 0;
	$("#btn_confirm").click(function(){
		if(KillEvent == 0){
			var isOneChecked = 0;
			$(".checkDS").each(function(){
				if(this.checked){
					isOneChecked = 1;
				}
			});
			if(isOneChecked == 0){
				$("#checkAll").prop("checked", true);
				BootstrapDialog.alert("Error : Please select atleast one data sheet to confirm !");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.show({
					title: 'Authentication',
					message: "Click below '<span>OTP Generate</span>' button to generate One Time Password (OTP)",
					closable: false,
					buttons: [{
						label: '&nbsp; Cancel &nbsp;',
						action: function(dialog) {
							dialog.close();
						}
					}, {
						label: '&nbsp; OTP Generate &nbsp;',
						action: function(dialog) {
							$.ajax({ 
								type: 'POST', 
								url: 'ajax/OTPGenerate.php', 
								data: { Page: 'PRCA' }, 
								success: function (data) {  
									if(data != 0){
										dialog.close();
										BootstrapDialog.show({
											message: '<div style="padding:20px 10px 40px 10px"><span style="float:left;">Enter Your One Time Password '+data+': &nbsp;</span> <span style="float:left; padding: 0px 5px;"><input type="text" class="form-control" style="width:150px; border:2px solid #171B20; border-radius:8px;"></span></div><div style="color:#E81645; font-size:11px; font-weight:bold; padding:2px 10px 10px 10px">* Please check your email for OTP. </div><div style="color:#E81645; font-size:11px; font-weight:bold; padding:2px 10px 40px 10px">** If you click Cancel button again you need to generate OTP </div>',
											closable: false,
											buttons: [{
													label: '&nbsp; Cancel &nbsp;',
													action: function(dialogRef) {
														dialogRef.close();
													}
												}, {
												label: '&nbsp; Next &nbsp;',
												action: function(dialogRef) {
													var otp = dialogRef.getModalBody().find('input').val();
													if($.trim(otp) !== $.trim(data)) {
														BootstrapDialog.alert('Invalid OTP. Please try again !');
														dialogRef.close();
														return false;
													}else{
														KillEvent = 1;
														$("#btn_confirm").trigger( "click" );
														dialogRef.close();
													}
												}
											}]
										});
									}else{
										BootstrapDialog.alert('Sorry, OTP Not Generated please try again !');
									}
								}
							});
						}
					}]
				});
			}
		} 
	});
});

</script>
<style>
#DataTables_Table_0_wrapper {
    width: 100% !important;
}
.dataTables_wrapper .dataTables_filter input{
	margin:0px;
}
</style>