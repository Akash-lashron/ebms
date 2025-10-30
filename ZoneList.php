<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
$msg = '';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
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
    return $dd . '-' . $mm . '-' . $yy;
}
$ZoneCount = 0;
$UsedZoneArr = array();
if(isset($_POST['submit']) == ' View '){
	$sheetid = $_POST['cmb_shortname'];
}
if(($_GET['zoneid']!='')&&($_GET['sheetid']!='')){
	$ZoneDeleteQuery = "update zone set active = 0 where zone_id='" . $_GET['zoneid'] . "'";
	$ZoneDeleteSql	 = mysql_query($ZoneDeleteQuery);
	//echo $ZoneDeleteQuery;exit;
	if($ZoneDeleteSql == true){
		$msg = "Sucessfully Deleted..!";
		$success = 1;
	}else{
		$msg = "Unable to Delete..!!!";
	}
	$sheetid = $_GET['sheetid'];
}
if($sheetid != ''){
	$SelectQuery = "select * from zone where sheetid = '$sheetid' and active = 1";
	$SelectSql 	 = mysql_query($SelectQuery);
	if($SelectSql == true){
		if(mysql_num_rows($SelectSql)>0){
			$ZoneCount = 1;
		}
	}
	$SelectDistZoneQuery = "select distinct zone_id from mbookheader where sheetid = '$sheetid'";
	$SelectDistZoneSql = mysql_query($SelectDistZoneQuery);
	if($SelectDistZoneSql == true){
		if(mysql_num_rows($SelectDistZoneSql)>0){
			while($ZoneList = mysql_fetch_object($SelectDistZoneSql)){
				array_push($UsedZoneArr,$ZoneList->zone_id);
			}
		}
	}
}
//print_r($UsedZoneArr);exit;
?>

<?php require_once "Header.html"; ?>
<script>
  	function goBack(){
		url = "ZoneView.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack(){ window.history.forward(); }
	function Delete(zoneid,sheetid){	
		swal({   
			title: "Are you sure?",   
			text: "You will not be able to recover this Zone's data!",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes, delete it!",   
			cancelButtonText: "No, cancel plz!",   
			closeOnConfirm: false,   
			closeOnCancel: false 
			}, 
			function(isConfirm){   
				if(!isConfirm){     
					swal("Cancelled", "Your data is safe :)", "");   
				}else{ 
					window.location.href='ZoneList.php?zoneid='+zoneid+'&sheetid='+sheetid;
				} 
			}
		);
	}
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">Zone - List</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1">
							<div>&nbsp;</div>
							<div class="container" align="center">
								<table class="table-bordered table1" align="center" id="dataTable">
									<thead>
										<tr>
											<th>SlNo.</th>
											<th>Zone Name</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
							<?php 
								$slno = 1; if($ZoneCount == 1){ while($List = mysql_fetch_object($SelectSql)){ 
								if(in_array($List->zone_id, $UsedZoneArr)){
									$ModifyFlag = 0;
								}else{
									$ModifyFlag = 1;
								}
							?>
									  	<tr>
											<td align="center"><?php echo $slno; ?></td>
											<td align="left"><?php echo $List->zone_name; ?></td>
											<td align="center" width="200px">
												<a <?php if($ModifyFlag == 1){ ?>href="ZoneCreate.php?zoneid=<?php echo $List->zone_id;?>" class="oval-btn-edit"<?php }else{ ?> class="oval-btn-disable" <?php } ?>>
													<i style="font-size:12px; padding-top:5px;" class="fa">&#xf044;</i> Edit	
												</a>
												&nbsp;
												<a <?php if($ModifyFlag == 1){ ?>href="javascript:Delete(<?php echo $List->zone_id; ?>,<?php echo $sheetid; ?>)" class="oval-btn-delete"<?php }else{ ?> class="oval-btn-disable" <?php } ?>>
													<i style="font-size:12px; padding-top:5px; font-weight:100" class="fa">&#xf00d;</i> Delete
											   	</a>
											</td>
										</tr>
							<?php $slno++; } }else{ ?>
										<tr><td colspan="3" align="center">No Records Found</td></tr>
							<?php } ?>
									</tbody>
								</table>	
							</div>
							
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
									<input type="submit" name="submit" id="submit" value=" Submit "/>
								</div>
							</div>
                        </blockquote>
                    </div>
                </div>
            </div>
            <!--==============================footer=================================-->
           <?php include "footer/footer.html"; ?>
        </form>
    </body>
</html>
<script>
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			if(success == 1){
				swal("", msg, "success");
			}else{
				swal(msg, "", "");
			}
		}
	};
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


