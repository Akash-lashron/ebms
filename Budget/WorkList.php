<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Work List';


checkUser();
$success = 0;
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
$AllDispArr = array();
$SelDisciplineQuery = "select discipline_code, discipline_name from discipline where active = 1";
$SelDisciplineQuerySql = mysqli_query($dbConn,$SelDisciplineQuery);
if($SelDisciplineQuerySql == true){
	if(mysqli_num_rows($SelDisciplineQuerySql)>0){
		while($DispList = mysqli_fetch_object($SelDisciplineQuerySql)){
			$AllDispArr[$DispList->discipline_code] =  $DispList->discipline_name;
		}
	}
}
if(isset($_GET['list']) != ""){
	$Type = $_GET['list'];
	//print_r($AllDispArr);exit;
	
	if(array_key_exists($Type,$AllDispArr)){
		//echo $Type;exit;
		$WhereClause = " and a.grp_div_sec = '$Type'";
		$View = $Type;
	}else{
		//echo $Type;exit;
		$WhereClause = "";
		$View = 'A';
	}
}else{
	$WhereClause = "";
	$View = 'A';
}
$AllStaffArr = array();
$SelectStaffListQuery = "select staffid, staffname, levelid from staff where active = 1 and sectionid != 2";
$SelectStaffListSql = mysqli_query($dbConn,$SelectStaffListQuery);
if($SelectStaffListSql == true){
	if(mysqli_num_rows($SelectStaffListSql)>0){
		while($AllStaffList = mysqli_fetch_object($SelectStaffListSql)){
			$AllStaffArr[$AllStaffList->staffid][0] =  $AllStaffList->staffname;
			$AllStaffArr[$AllStaffList->staffid][1] =  $AllStaffList->levelid;
		}
	}
}

//print_r($AllDispArr);
$SheetRABArr = array(); $SheetCCNoArr = array(); $SheetDataArr = array();
//$SelectWorkQuery 	= "select a.*, b.* from sheet a left join abstractbook b on (a.sheet_id = b.sheetid) where a.active = 1 ".$WhereClause." order by a.computer_code_no asc";
$SelectWorkQuery 	= "SELECT a.*, b.*, c.* FROM works a LEFT JOIN sheet b ON (a.globid = b.globid) LEFT JOIN abstractbook c ON (b.sheet_id = c.sheetid) WHERE a.active = 1 ".$WhereClause." ORDER BY a.ccno ASC";
//echo $SelectWorkQuery;exit;
$SelectWorkSql 		= mysqli_query($dbConn,$SelectWorkQuery);
if($SelectWorkSql == true){
	if(mysqli_num_rows($SelectWorkSql)>0){
		while($WoList = mysqli_fetch_object($SelectWorkSql)){
			$AssignedStaff 	= $WoList->assigned_staff;
			$ExpAssignedStaff = explode(",",$AssignedStaff);
			//if((in_array($_SESSION['sid'],$ExpAssignedStaff))||($_SESSION['isadmin'] == 1)){
				$SheetRABArr[$WoList->sheet_id] 	= $WoList->rbn;//rab;
				$SheetCCNoArr[$WoList->sheet_id] 	= $WoList->computer_code_no;
				
				$SheetDataArr[$WoList->sheet_id][0] = $WoList->work_name;
				$SheetDataArr[$WoList->sheet_id][1] = $WoList->short_name;
				$SheetDataArr[$WoList->sheet_id][2] = $WoList->work_order_no;
				$SheetDataArr[$WoList->sheet_id][3] = $WoList->agree_no;
				/*$SheetDataArr[$WoList->sheetid][4] = $WoList->createddate;
				$Rdate 		= date_create($SheetDataArr[$WoList->sheetid][4]);
				$RecDate 	= date_format($Rdate,"d/m/Y");*/
				$SheetDataArr[$WoList->sheet_id][5] = $WoList->work_order_cost;
				$SheetDataArr[$WoList->sheet_id][6] = $WoList->upto_date_total_amount;
				$SheetDataArr[$WoList->sheet_id][7] = $WoList->slm_total_amount;
				$SheetDataArr[$WoList->sheet_id][8] = $WoList->secured_adv_amt;
				$SheetDataArr[$WoList->sheet_id][9] = $WoList->total_rec_rel_amt;
				$SheetDataArr[$WoList->sheet_id][10] = $WoList->name_contractor;
				$SheetDataArr[$WoList->sheet_id][11] = $WoList->work_duration;
				$SheetDataArr[$WoList->sheet_id][12] = $WoList->rbn;
				$AssignedStaff = $WoList->assigned_staff;
				$ExpAssignedStaff = explode(",",$AssignedStaff);
				foreach($ExpAssignedStaff as $StaffKey => $StaffVal){
					//echo $StaffVal;
					$StaffLevelCheck = $AllStaffArr[$StaffVal][1];
					if($StaffLevelCheck == 4){
						$SuppEngg = $AllStaffArr[$StaffVal][0];
					}
					if($StaffLevelCheck == 3){
						$EnggInCh = $AllStaffArr[$StaffVal][0];
					}
				}
				if($EnggInCh == ""){
					$SheetDataArr[$WoList->sheet_id][13] = $SuppEngg;
				}else{
					$SheetDataArr[$WoList->sheet_id][13] = $EnggInCh;
				}
				$SheetDataArr[$WoList->sheet_id][14] = dt_display($WoList->work_order_date);
				$SheetDataArr[$WoList->sheet_id][15] = dt_display($WoList->date_of_completion);
			//}
		}
	}
}
if(isset($_POST['btn_back'])){
	header("Location:Home.php");
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<link rel="stylesheet" type="text/css" media="screen" href="css/fancybox.css" />
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
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.2.1.js"></script>
<script type="text/javascript" src="js/image_enlarge_style_js.js"></script>
<script type="text/javascript">
    $(function($){
        var addToAll = false;
        var gallery = false;
        var titlePosition = 'inside';
        $(addToAll ? 'img' : 'img.fancybox').each(function(){
            var $this = $(this);
            var title = $this.attr('title');
            var src = $this.attr('data-big') || $this.attr('src');
            var a = $('<a href="#" class="fancybox"></a>').attr('href', src).attr('title', title);
            $this.wrap(a);
        });
        if (gallery)
            $('a.fancybox').attr('rel', 'fancyboxgallery');
        $('a.fancybox').fancybox({
            titlePosition: titlePosition
        });
    });
    $.noConflict();
</script>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<style>
	table{
		margin-top:15px;
		color:#0053A6;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
	}
	.note{
		text-decoration: none;
		padding: 2px 14px;
		color: #fff;
		border: none;
		background-color: transparent;
		font-size: 13px;
		outline:none;
	}
	.col-status{
		float: left;
		position: relative;
		min-height: 1px;
		padding-right: 2px;
		padding-left: 2px;
		/*width:16%;*/
		width:120px;
	}
	.well-A{
		background-color:#fff;/*#038BCF*/
		border: 2px solid #02B9E2;/*038BCF*/
		color:#032FAD;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		cursor:pointer;
		border-radius:20px;
		padding:5px 8px 5px 8px;
		font-size:12px;
	}
	.well-A:hover{
		background-color:#02B9E2;
		border: 2px solid #02B9E2;
		color:#fff;
	}
	.well.active{
		background-color:#02B9E2;
		border: 2px solid #02B9E2;/*#055DAB;*/
		color:#fff;
		pointer-events:none;
	}
	.rlable-pink1{
		padding:5px;
		padding-left:6px;
		padding-right:6px;
		border:1px solid #EC94A2;
		border-radius:15px;
		white-space:nowrap;
		line-height:36px;
		background:#fff;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
		color:#024FC5;
		font-weight:600;
	}
	.round-slno{
		padding:4px;
		padding-left:7px;
		padding-right:7px;
		border:1px solid #02A9DA;
		border-radius:15px;
		white-space:nowrap;
		line-height:20px;
		background:#02A9DA;
		font-size:11px;
		font-weight:bold;
		color:#fff;
	}
	.accordionTitle::before{
    	float: right !important;
	}
	.accordionTitle.is-expanded, dd.is-expanded{
		border:1px solid #035a85;
	}
</style>
    </head>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
<link rel="stylesheet" href="dashboard/css/verticalTab.css">
<script src="dashboard/js/verticalTab.js"></script>

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
						<blockquote class="bq1 stable" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Work Master</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<!--<div class="div12 no-padding-lr">
																	<div class="div2">
																		<div class="dropdown">
																			<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" data-url="BudgetExpenditureObjectHead">Total Work List</button>
																		</div>
																	</div>
																	<div class="div2">
																		<div class="dropdown">
																			<button class="btn btn-info dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="BudgetObjectHeadCommitActual">Major Construction</button>
																		</div>
																	</div>
																	<div class="div2">
																		<div class="dropdown">
																			<button class="btn btn-info dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="BudgetObjectHeadBeRe">Major Maintenance</button>
																		</div>
																	</div>
																	<div class="div2">
																		<div class="dropdown">
																			<button class="btn btn-info dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="BudgetObjectHeadBeRe">Minor Construction</button>
																		</div>
																	</div>
																	<div class="div2">
																		<div class="dropdown">
																			<button class="btn btn-info dropdown-toggle BtnBox sorBtn" type="button" data-toggle="dropdown" data-url="BudgetObjectHeadBeRe">Minor Maintenance</button>
																		</div>
																	</div>
																</div>-->
																<div align="center">
																<!--<div class="row clearrow"></div> -->
																	<div class="div12 no-padding-lr" align="center">
																	<div class="col-status" data-id='A'><div class="well well-sm well-A <?php if($View == "A"){ ?> active <?php } ?>"><span class="rlable-pink">Total Work List</span></div></div>
																		
																			<div class="AddNewBtn BtnHref" data-url='WorkMaster' style="float:right"><div class="A"><span class="rlable-pink"><i class="fa fa-plus" style="font-size:13px; padding-top:2px;"></i> Create New</span></div></div>
																	</div>
																</div>
																<!--<div class="row clearrow"></div>-->

																<!--<div align="center">
																<div class="row clearrow"></div> 
																	<div class="div12 no-padding-lr" align="center">
																		<div class="col-status div2" data-id='A'><div class="well well-sm well-A <?php if($View == "A"){ ?> active <?php } ?>"><span class="rlable-pink">Total Work List</span></div></div>
																		<div class="col-status div2" data-id='1'><div class="well well-sm well-A <?php if($View == "1"){ ?> active <?php } ?>"><span class="rlable-pink">Major Construction</span></div></div>
																		<div class="col-status div2" data-id='2'><div class="well well-sm well-A <?php if($View == "2"){ ?> active <?php } ?>"><span class="rlable-pink">Major Maintenance</span></div></div>
																		<div class="col-status div2" data-id='3'><div class="well well-sm well-A <?php if($View == "3"){ ?> active <?php } ?>"><span class="rlable-pink">Minor Construction</span></div></div>
																		<div class="col-status div2" data-id='4'><div class="well well-sm well-A <?php if($View == "4"){ ?> active <?php } ?>"><span class="rlable-pink">Minor Maintenance</span></div></div>
																	</div>
																</div>
																<div class="row clearrow"></div>-->
																
																<div class="col-md-12 no-padding-lr" align="center">
																<?php $x = 1; foreach($SheetRABArr as $sheetid=>$rbn){ $CCNO = $SheetCCNoArr[$sheetid]; ?>
																	<div class="accordion">
																		<dl>
																			<dt>
																				<a href="#accordion<?php echo $sheetid; ?>" id="sheet-<?php echo $sheetid; ?>" aria-expanded="false" aria-controls="accordion<?php echo $sheetid; ?>" class="accordion-title accordionTitle js-accordionTrigger blue-bg <?php if($x == 1){ ?> is-collapsed is-expanded <?php } ?>">
																					<span class="round-slno"><?php echo $x; ?></span>
																					&nbsp;&nbsp;<font style="color:#DF0979; font-weight:bold; background:#edeaea; border-radius:7px; padding:2px;">CCNo. : <?php echo $CCNO; ?></font>
																					&nbsp;&nbsp;<?php echo $SheetDataArr[$sheetid][0]; ?>&nbsp;&nbsp;
																					<?php if($SheetDataArr[$sheetid][12] == ""){ $Content = "no"; }else{ $Content = "yes"; } ?>
																					<font style="color:#DF0979; font-weight:bold; background:#edeaea; border-radius:7px; padding:2px;" class="Chart" data-id="<?php echo $sheetid; ?>" data-content="<?php echo $Content; ?>">
																						&nbsp; <i class="fa fa-bar-chart" style="font-size:15px; padding-top:2px;"></i>&nbsp; Charts &nbsp;
																					</font>
																				</a>
																			</dt>
																			<dd class="accordion-content accordionItem <?php if($x == 1){ ?> is-expanded animateIn <?php } else{ ?> is-collapsed <?php } ?>" id="accordion<?php echo $sheetid; ?>" aria-hidden="true" style="overflow:auto;">
																				<div align="left" style="padding:5px 5px; background:#fff;">
																					<span class="rlable-pink1">Work Order No : <?php echo $SheetDataArr[$sheetid][2]; ?></span>
																					<span class="rlable-pink1">Agreement No : <?php echo $SheetDataArr[$sheetid][3]; ?></span>
																					<span class="rlable-pink1">Work Order Date : <?php echo $SheetDataArr[$sheetid][14]; ?></span>
																					<span class="rlable-pink1">Work Order Cost : <?php echo $SheetDataArr[$sheetid][5]; ?></span>
																					<span></br></span>
																					<span class="rlable-pink1">Schedule D.O.C. : <?php echo $SheetDataArr[$sheetid][15]; ?></span>
																					<span class="rlable-pink1">Work Duration : <?php echo $SheetDataArr[$sheetid][11]." - Months"; ?></span>
																					<span class="rlable-pink1">Completed RAB : RAB - <?php echo $SheetDataArr[$sheetid][12]; ?></span>
																					<span class="rlable-pink1">Upto Paid Amount : <?php echo $SheetDataArr[$sheetid][6]; ?></span>
																					<?php $BalanceAmount = $SheetDataArr[$sheetid][5] - $SheetDataArr[$sheetid][6]; ?>
																					<span class="rlable-pink1">Balance Amount : <?php echo $BalanceAmount; ?></span>
																					<span></br></span>	
																					<span class="rlable-pink1">Contractor Name : <?php echo $SheetDataArr[$sheetid][10]; ?></span>
																					<span class="rlable-pink1">Engg. Inc. : <?php echo $SheetDataArr[$sheetid][13]; ?></span>
																				</div>
																			</dd>
																		</dl>
																	</div>
																<?php $x++; } ?>
																</div>

															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div align="center">
								<input type="submit" class="btn btn-info" name="btn_back" id="btn_back" value="Back" />
							</div>
							<div align="center">&nbsp;</div>
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

	$("#exportToExcel").click(function(e){ 
		var table = $('body').find('.table2excel');
		if(table.length){ 
			$(table).table2excel({
				exclude: ".noExl",
				name: "Excel Document Name",
				filename: "SingleLineAbstract-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
				fileext: ".xls",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true
			});
		}
	});
	$('.col-status').click(function(event){
		var id = $(this).attr('data-id');
		if((id != undefined)&&(id != '')){
			$(location).attr('href', 'LiveWorksList.php?list='+id);
		}
	});
	//$('.Chart').click(function(event){
	$('body').on('click', '.Chart', function() {
		var id = $(this).attr('data-id');
		var content = $(this).attr('data-content'); 
		if((id != undefined)&&(id != '')){ 
			$(location).attr('href', 'Home.php?sheetid='+id+'&content='+content);
		}else{
			BootstrapDialog.alert("RAB not yet generated. Unable to view Chart.");
		}
	});
	$('#back').click(function(){
		$(location).attr('href', 'LiveWorksList.php')
	});
	//$('#example').DataTable();
});
</script>
<script>
	var msg = "<?php echo $msg; ?>";
	var titletext = "";
		document.querySelector('#top').onload = function(){
		if(msg != "")
		{
			swal({
				 title: "",
				 text: msg,
				 confirmButtonColor: "#3dae38",
				 type:"success",
				 confirmButtonText: " OK ",
				 closeOnConfirm: false,
			},
			function(isConfirm){
				 if (isConfirm) {
					url = "ShortDescCreate.php";
					window.location.replace(url);
				 }
			});
		}
	};
</script>