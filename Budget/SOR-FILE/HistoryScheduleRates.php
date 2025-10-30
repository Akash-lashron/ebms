<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'History'.$PTIcon.'Schedule of Rates';
$msg = ""; $del = 0;
$RowCount =0;
$staffid = $_SESSION['sid'];
if(isset($_POST['btn_save']) == " View "){
	$Pruid = $_POST['cmb_history_year']; 
	$_SESSION['PeriodicalId'] = $Pruid;
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script src="dashboard/MyView/bootstrap.min1.js"></script>
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
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div class="box-container">
								<div>&nbsp;</div>
								<div class="div12">
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=386&Level=1">Aluminium Works</button>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=77&Level=1">Antitermite Treatment</button>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=766&Level=1">Dismantling & Demolision</button>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=137&Level=1">Expansion Joint</button>
										</div>
									</div>
								</div>
								<div>&nbsp;</div>
								<div class="div12">
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=401&Level=1">False Ceiling</button>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=390&Level=1">Glazing</button>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=493&Level=1">Pipe Line Works</button>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=726&Level=1">Road Works</button>
										</div>
									</div>
								</div>
								<div>&nbsp;</div>
								<div class="div12">
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=379&Level=1">Rolling Shutter</button>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=407&Level=1">Sanitary Installations</button>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=147&Level=1">Water / Weather Proofing</button>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=299&Level=1">Wood Works</button>
										</div>
									</div>
								</div>
								<div>&nbsp;</div>
								<div class="div12">
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox" type="button" data-toggle="dropdown">Clearing And Earth Work
											<span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a data-url="HistoryDataSheetEditList?dsid=3&Level=2">Area Cleaning & Jungle Clearance</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=4&Level=2">Tree Cutting</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=5&Level=2">Earthwork Excavation</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=6&Level=2">Filling Works</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=7&Level=2">Conveying</a></li>
											</ul>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox" type="button" data-toggle="dropdown">Concrete Items
											<span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a data-url="HistoryDataSheetEditList?dsid=82&Level=2">Plain Cement Concrete</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=97&Level=2">Controlled RCC</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=107&Level=2">Precast RCC</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=114&Level=2">Steel Reinforcement</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=118&Level=2">Form Work & Scaffoldings</a></li>
											</ul>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox" type="button" data-toggle="dropdown">Flooring Items - I
											<span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a data-url="HistoryDataSheetEditList?dsid=198&Level=2">Soiling</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=200&Level=2">Damp Proof Course</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=202&Level=2">Cement Concrete Floorings</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=238&Level=2">PVC sheet flooring</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=246&Level=2">Paver block & Wall tile cladding</a></li>
											</ul>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox" type="button" data-toggle="dropdown">Flooring Items - II
											<span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a data-url="HistoryDataSheetEditList?dsid=210&Level=2">Marble flooring</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=216&Level=2">Kota stone flooring</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=222&Level=2">Granite flooring</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=229&Level=2">Ceramic flooring & Dado</a></li>
											</ul>
										</div>
									</div>
								</div>
								<div>&nbsp;</div>
								<div class="div12">
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox" type="button" data-toggle="dropdown">Masonry Works
											<span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a data-url="HistoryDataSheetEditList?dsid=163&Level=2">Brick work</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=179&Level=2">Solid Block Masonry</a></li>
											</ul>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox" type="button" data-toggle="dropdown">Plastering & Painting Works
											<span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a data-url="HistoryDataSheetEditList?dsid=252&Level=2">Plastering</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=265&Level=2">Painting</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=296&Level=2">Polishing & Varnishing</a></li>
											</ul>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox" type="button" data-toggle="dropdown">Structural Steel Items
											<span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a data-url="HistoryDataSheetEditList?dsid=123&Level=2">Structural Steel Works</a></li>
												<li><a data-url="HistoryDataSheetEditList?dsid=134&Level=2">Galvalume Sheet Roofing</a></li>
											</ul>
										</div>
									</div>
									<div class="div3">
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="HistoryDataSheetEditList?dsid=937&Level=1">Sub Data</button>
										</div>
									</div>
								</div>
								<div class="div12">&nbsp;</div>
								<div class="div12" align="center"><a data-url="HistorySorGenerate" class="btn btn-info">Back</a></div>

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
<style>
	.dropdown{
		display:block;
	}
	.dropdown-menu{
		width:99%;
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
	}
</style>
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