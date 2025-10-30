<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
checkUser();
$msg = '';

function dt_format($ddmmyyyy){
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy){
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
if(isset($_POST["save"])){
	$MaterialDesc 	= $_POST['txt_mat_desc'];
	$MaterialCata 	= $_POST['txt_mat_cata'];
	$MaterialType 	= $_POST['txt_mat_type'];
	$InsertQuery 	= "INSERT INTO material set mat_category = '$MaterialCata', mat_desc = '$MaterialDesc', mat_type = '$MaterialType', active='1', created_on = NOW(), created_by = '".$_SESSION['sid']."'";
	$InsertSql 		= mysql_query($InsertQuery);
	if($InsertSql == true){
		$msg = "Material Created Successfully";
	}else{
		$msg = "Error : Material Not Created. Please try again.";
	}
}
if(isset($_POST["view"])){
	header('Location: MaterialView.php');
}
?>
<?php require_once "Header.html"; ?>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->

         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Materials</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                       
					   		<div class="row clearrow"></div>
					   		<div class="container">
								<div class="row ">
									<div class="div3">&nbsp;</div>
									<div class="div6">
										<div class="row"><div class="div12" style="margin-top:0px;"><div class="row divhead" align="center">Material Creation</div></div></div>
										<div class="row innerdiv">
											
											<div class="row">
												<div class="div2">&nbsp;</div>
												<div class="div4">
													<label for="fname">Material Description</label>
												</div>
												<div class="div4">
													<input type="text" name="txt_mat_desc" id="txt_mat_desc"  class="DispSelectBox">
												</div>
												<div class="div2">&nbsp;</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div2">&nbsp;</div>
												<div class="div4">
													<label for="fname">Material Category</label>
												</div>
												<div class="div4">
													<select name="txt_mat_cata" id="txt_mat_cata" class="DispSelectBox">
														<option value="">-------- Select --------</option>
													 	<option value="10CA">10CA</option>
													 	<option value='10CC'>10CC</option>
												   </select>
												</div>
												<div class="div2">&nbsp;</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div2">&nbsp;</div>
												<div class="div4">
													<label for="fname">Material Type</label>
												</div>
												<div class="div4">
													<select name="txt_mat_type" id="txt_mat_type" class="DispSelectBox">
														<option value="">-------- Select --------</option>
													 	<option value="G">General</option>
													 	<option value='S'>Steel</option>
														<option value='ST'>Structural Steel</option>
														<option value="OTH">Others</option>
												   </select>
												</div>
												<div class="div2">&nbsp;</div>
											</div>
											<div class="smediv">&nbsp;</div>
										</div>
										<div class="smediv">&nbsp;</div>
									</div>
									<div class="div3">&nbsp;</div>
								</div>
								
								   
								<div class="row">
									<div class="div12" align="center">
										<input type="submit" data-type="submit" value=" Save " name="save" id="save"/>
										<input type="submit"  value=" View " name="view" id="view"/>
									</div>
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
	$("#cmb_work_no").chosen();
	if(window.history.replaceState ) {
		window.history.replaceState( null, null, window.location.href );
	}
    $(document).ready(function () {
		var msg = "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		if(msg != ""){
			BootstrapDialog.show({
				title: 'Information',
				closable: false,
				message: msg,
				buttons: [{
					label: ' OK ',
					cssClass: 'btn-primary',
					action: function(dialogRef) {
						dialogRef.close();
					}
				}]
			});
		}
		var KillEvent = 0;
		$('body').on("click","#save", function(event){ 
			if(KillEvent == 0){
				var MatDesc = $('#txt_mat_desc').val();
				var MatCata = $('#txt_mat_cata').val();
				var MatType = $('#txt_mat_type').val();
				if(MatDesc == ""){
					BootstrapDialog.alert("Please Enter Material Description");
					event.preventDefault();
					event.returnValue = false;
				}else if(MatCata == ""){
					BootstrapDialog.alert("Please Select Material Category");
					event.preventDefault();
					event.returnValue = false;
				}else if(MatType == ""){
					BootstrapDialog.alert("Please Select Material Type");
					event.preventDefault();
					event.returnValue = false;
				}else{
					event.preventDefault();
					BootstrapDialog.confirm('Are you sure want to Save ?', function(result){
						if(result) {
							KillEvent = 1;
							$("#save").trigger( "click" );
						}
					});
				}
			}
		});		
	});
</script>
    </body>
</html>

