<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
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
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <!--==============================Content=================================-->
  
        <div class="content">
            			<div class="title">Measurements Book Generate</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="RABGenerateInitiate.php">
                            <div class="container" align="center">
								<br/>
								<div class="page">
								  <div class="page__demo">
									<div class="main-container page__container">
									  <div class="timeline">
										<div class="timeline__group">
										  <span class="timeline__year">STEPS</span>
										   <div class="timeline__box">
											<div class="timeline__date">
											  <span class="timeline__day">1</span>
											  <span class="timeline__month">&emsp;MBook</span>
											</div>
											<div class="timeline__post">
											  <div class="timeline__content">
												<p>
													General MBook and Steel MBook Generate
												</p>
											  </div>
											</div>
										  </div>
										  <div class="timeline__box">
											<div class="timeline__date">
											  <span class="timeline__day">2</span>
											  <span class="timeline__month">Sub-Abstract</span>
											</div>
											<div class="timeline__post">
											  <div class="timeline__content">
												<p>
													Sub-Abstract Generate
												</p>
											  </div>
											</div>
										  </div>
										  <div class="timeline__box">
											<div class="timeline__date">
											  <span class="timeline__day">3</span>
											  <span class="timeline__month">Abstract&nbsp;</span>
											</div>
											<div class="timeline__post">
											  <div class="timeline__content">
												<p>
													Abstract Generate
												</p>
											  </div>
											</div>
										  </div>
										</div>
									  </div>
									</div>
								  </div>
								</div>
							</div>
						<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
							<div class="buttonsection">
							<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
							</div>
							<div class="buttonsection" id="view_btn_section">
							<input type="submit" class="btn-hover color-9" value=" GO " name="btn_view" id="btn_view"/>
							</div>
						</div>
       				</form>
      			</blockquote>
    		</div>
   		</div>
	</div>
	<link rel="stylesheet" href="css/timeline.css">
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
    $(function() {
			$.fn.validateworkorder = function(event) { 
					if($("#cmb_shortname").val()==""){ 
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
				$(this).validateworkorder(event);
         	});
			$("#cmb_shortname").change(function(event){
           		$(this).validateworkorder(event);
         	});
	 });
</script>
<script>
	$("#cmb_shortname").chosen();
	//$("#cmb_rbn").chosen();
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

