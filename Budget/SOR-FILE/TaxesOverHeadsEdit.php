<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'Taxes & Overheads';
//checkUser();
$msg = ""; $del = 0;
$RowCount =0;
$staffid = $_SESSION['sid'];
if($_GET['id']!=""){
	$DeId	=	$_GET['id'];
	$SelectItemQuery = "select * from default_master where de_id = '$DeId'";
	$SelectItemSql	 = mysqli_query($dbConn,$SelectItemQuery);
	if(mysqli_num_rows($SelectItemSql)>0){
		$List=mysqli_fetch_object($SelectItemSql);
		$DeName	=	$List->de_name;
		$DePerc	=	$List->de_perc;
		$DeCode	=	$List->de_code;
	}
}
if(isset($_POST['btn_save']) == " Save "){
	$POSTDeName	=	$_POST['txt_de_name'];
	$POSTDePerc	=	$_POST['txt_de_perc'];
	$POSTDeCode	=	$_POST['txt_de_code'];
	$POSTDeId	=	$_POST['txt_de_id'];
	
	$UpdateQuery = "update default_master set de_name = '$POSTDeName', de_perc = '$POSTDePerc', de_code = '$POSTDeCode' where de_id = '$POSTDeId'";
	$UpdateSql = mysqli_query($dbConn,$UpdateQuery);
	if($UpdateSql == true){
		$$msg = "Item updated successfully.";
	}else{
		$$msg = "Error : Item not created. Please try again.";
	}
}
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
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="div12" align="center">&nbsp;</div>
							</div>
							<div class="row">
								<div class="div3" align="center">&nbsp;</div>
								<div class="div6" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-sb" align="center">Taxes & Over Heads</div>
										<div class="row innerdiv group-div" align="center">
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lgboxlabel">Taxes / Over Heads Desc.</div>
												<div class="div8">
													<input type="text" name="txt_de_name" id="txt_de_name" class="tboxclass ronly-tbox" value="<?php if($_GET['id']!=""){ echo $DeName; } ?>" readonly="">
													<input type="hidden" name="txt_de_id" id="txt_de_id" value="<?php if($_GET['id']!=""){ echo $DeId; } ?>">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lgboxlabel">Taxes / Over Heads Perc</div>
												<div class="div2">
													<input type="text" name="txt_de_perc" id="txt_de_perc" class="tboxclass" value="<?php if($_GET['id']!=""){ echo $DePerc; } ?>">
												</div>
												<div class="div6 lgboxlabel">
													&nbsp;( % )
												</div>
											</div>
											<div class="row" align="center">
												<a data-url="TaxesOverHeadsEditList" class="btn btn-info">Back</a>
												<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
											</div>
										</div>
									</div>
								</div>
								<div class="div3" align="center">&nbsp;</div>
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
	$('.dropdown-submenu a.test').on("click", function(e){
    	$(this).next('ul').toggle();
    	e.stopPropagation();
    	e.preventDefault();
  	});
  	$('#btn_view_single').click(function(event){ 
  		$(location).attr("href","ItemMasterView.php");
		event.preventDefault();
		return false;
  	});
});
</script>
