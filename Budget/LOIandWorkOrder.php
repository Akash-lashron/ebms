<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'CST & Negotiation';
$msg = ""; $del = 0;
$RowCount =0;
$staffid = $_SESSION['sid'];
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script src="dashboard/MyView/bootstrap.min1.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
<style>
	.dropdown{
		display:block;
	}
	.dropdown-menu{
		width:99%;
		background:url(images/bgA.jpg);
		background-size:100% 100%;
		
		padding: 0px 0;
		margin: 0px 0 0;
		border:2px solid #000;
	}
	.BtnBox{
		padding:10px 15px;
		background:#fff;
		color:#01389F;
		font-size:13px;
		width:95%;
		text-align:left;
	}
	.BtnBox:hover, .BtnBox:focus, .BtnBox:active{
		background:#10478A !important;
		border:1px solid #10478A !important;
	}
	.box-container{
		display:block;
	}
	.open > .dropdown-toggle.btn-primary,
	.open > .dropdown-toggle.btn-primary:hover, 
	.open > .dropdown-toggle.btn-primary:focus,
	.open > .dropdown-toggle.btn-primary:active{
		background:#10478A;
		border:1px solid #10478A;
	}
	.dropdown-menu > li > a{
		font-size:13px;
		padding:8px 4px 8px 4px;
		border:1px solid #06528A;
		/*border-style:inset;*/
		color: #1F57AB;
		/*font-weight:600;
		font-size:11px;*/
		border-collapse:collapse;
		color:#fff;
		border-top:none;
	}
	.dropdown-menu > li{
		/*rgba(51, 170, 51, .3)*/ 
		/*background-color: rgba(235, 237, 240, 1);*/
		padding:0px;
		
	}
	.dropdown-menu > li > a:focus, .dropdown-menu > li > a:hover {
		/*color: #262626;
		text-decoration: none;
		color:#fff;
		-moz-opacity: 0;
		opacity: 0;*/
		/*background-color: rgba(1, 23, 46, 0.8);*/
		/*background-color: rgba(1, 35, 135, 0.8);*/
		/*-moz-opacity: 1;
		opacity: 1;
		color:#fff;
		box-shadow: 0px 1px 2px -2px #333;*/
		/*background-color: rgba(16,46,91,0.4);*/
		background-color: rgba(6,25,46,0.2);
		color:#fff;
		border:1px solid #01080E;
		border-top:none;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div class="box-container">
							<div>&nbsp;</div>
								<div class="div12">
									&nbsp;
								</div>
								<div class="div12">
									<div class="div2">
										&nbsp;
									</div>
									<div class="div4">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="LOIEntry">LOI Entry</button>
										</div>
									</div>
									<div class="div4">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="WorkOrder">Work Order Entry</button>
										</div>
									</div>
									<div class="div2">
										&nbsp;
									</div>
								</div>
								<div class="div12">
									&nbsp;
								</div>
								
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
	$('.sorBtn').click(function(event){ 
		var DatUrl = $(this).attr("data-url");
		var SplitUrl = DatUrl.split("?");
		var Len = SplitUrl.length;
		if(Len > 0){
			if(Len == 1){
				var Url = SplitUrl[0]+".php";
			}else{
				var Url = SplitUrl[0]+".php?"+SplitUrl[1];
			}
			window.location.href = Url;
		}
		event.preventDefault();
		return false;
  	});
});
</script>