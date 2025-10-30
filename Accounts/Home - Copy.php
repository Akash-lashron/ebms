<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
checkUser();
$DonutStr = "";
/****************************** DONUT MBOOK CHART ****************************/
$SelectSheetQuery = "select count(sacid) as mb_count, DATE_FORMAT(modifieddate,'%M') as mb_month from send_accounts_and_civil where YEAR(modifieddate) = YEAR(CURDATE()) GROUP BY MONTH(modifieddate)";
//echo $SelectSheetQuery ;exit;
$SelectSheetSql = mysqli_query($dbConn,$SelectSheetQuery);
if($SelectSheetSql == true){
	if(mysqli_num_rows($SelectSheetSql)>0){
		while($MBList = mysqli_fetch_object($SelectSheetSql)){
			$MBCount = $MBList->mb_count;
			$MBMonth = $MBList->mb_month;
			$DonutStr .= '{ "Month": "'.$MBMonth.'", "No": '.$MBCount.' },';
		}
	}
}
if($DonutStr != ""){
	$DonutStr = rtrim($DonutStr,",");
}
//echo $DonutStr;exit;

/****************************** FOR MBOOK REJECT TO CIVIL COUNT ****************************/
$RetSheetArr = array(); $MBWaiListLevlArr = array();
$MBCount = 0; $MBRetCount = 0;
$SelectQuery1 	= "select distinct a.sheetid, a.rbn, c.worktype from measurementbook_temp a inner join send_accounts_and_civil b on (a.sheetid = b.sheetid) 
				  inner join sheet c on (a.sheetid = c.sheet_id) where a.rbn = b.rbn and c.worktype IN (".$_SESSION['WorkSection'].")
				  and (b.mb_ac = 'SA' || b.sa_ac = 'SA' || b.ab_ac = 'SA' || b.mb_ac = 'SC' || b.sa_ac = 'SC' || b.ab_ac = 'SC') 
				  order by a.sheetid asc";
				  //echo $SelectQuery1;exit;
$SelectSql1 	= mysqli_query($dbConn,$SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1)>0){
		while($MBCList1 = mysqli_fetch_object($SelectSql1)){
			$MBCsheetid = $MBCList1->sheetid;
			$MBCrbn 	= $MBCList1->rbn;
			$MBCmbookno = $MBCList1->mbookno;
			
			$SelectQuery2 = "select * from acc_log where sheetid = '$MBCsheetid' and rbn = '$MBCrbn'";
			//echo $SelectQuery2."<br/>";
			$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					while($MBCList2 = mysqli_fetch_object($SelectSql2)){
						$MBClevelid 	= $MBCList2->levelid;
						$MBCAC_status 	= $MBCList2->AC_status;
						if($MBCList2->status == 'SC'){
							$MBRetCount++;
							if(in_array($MBCList1->sheetid, $RetSheetArr)){
								//exist
							}else{
								array_push($RetSheetArr,$MBCList1->sheetid);
							}
							$RetSheetStr .= $MBCList1->sheetid.",";
						}else{
							if(($MBClevelid == $_SESSION['levelid'])&&($MBCAC_status == "")){
								$MBCount++;
							}
							
							if(($MBCAC_status == "")&&($MBCList2->status == 'SA')){
								//echo $MBClevelid."@@@".$MBCsheetid."<br/>";
								if($MBWaiListLevlArr[$MBClevelid] == ""){
									$MBWaiListLevlArr[$MBClevelid] = 1;
								}else{
									$MBWaiListLevlArr[$MBClevelid] = $MBWaiListLevlArr[$MBClevelid]+1;
								}
							}
						}
					}
				}
			}
		}
	}
}
if(count($RetSheetArr)>0){
	$ImpRetSheetArr = implode(",",$RetSheetArr);
	$_SESSION['RetCivilSheet'] = $ImpRetSheetArr;
}else{
	$_SESSION['RetCivilSheet'] = "";
}
//print_r($MBWaiListLevlArr);//
//exit;
/****************************** FOR PG EXPIRED DATE CHECKING ****************************/
$PGRelCount = 0;
$SelectPGQuery = "select a.*, b.* from sheet a inner join bg_release b on (a.sheet_id = b.sheetid) where b.bg_status = 'L' 
				and b.bg_exp_date - INTERVAL 30 DAY <= CURDATE() and a.worktype IN (".$_SESSION['WorkSection'].")
				and b.bgid = (select max(c.bgid) from bg_release c where c.sheetid = b.sheetid)";
$SelectPGSql = mysqli_query($dbConn,$SelectPGQuery);
if($SelectPGSql == true){
	$PGRelCount = mysqli_num_rows($SelectPGSql);
}
ksort($MBWaiListLevlArr);
//echo $SelectPGQuery;exit;
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	#chartdiv {
		/*width: 99%;*/
		height: 365px;
		font-size: 11px;
	}
	.list-group-item{
	 	padding: 5px 15px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
		color:#0270DD;
	}.panel{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:13px;
	}.well{
		margin:0px;
		margin-top:5px;
		margin-bottom:5px;
		padding: 5px;
	}.well-content{
		padding-top:6px;
		padding-bottom:6px;
		border:1px solid #A3B0BB;
		text-align:left;
		color:#0253A4;
		padding-left:25px;
		font-weight:500;
		margin:5px 0px;
		background:#fff;
		border-radius:6px;
	}.well-content:hover{
		background-color:#10478A;
		color:#FFFFFF;
		cursor:pointer;
		border-radius:6px;
	}.well-content2{
		padding-top:4px;
		padding-bottom:4px;
		text-align:left;
		color:#0253A4;
		padding-left:10px;
		font-size:14px;
		font-weight:500;
	}.well-content-head{
		background-color:#10478A;
		color:#FFFFFF;
		padding-left:5px;
		cursor:default !important;
	}
	.bwelal{
		border:1px solid #0B86D0;
	}
	.box1 {
	  width: 98%;
	  /*min-width: 150px;*/
	  display: block;
	  min-height: 35px;
	  position: relative;
	  border-radius: 5px;
	  background: linear-gradient(to right, #035BB4 35%, #0A7BEF 100%);
	  /*background: linear-gradient(to right, #abbd73 35%, #d6e2ad 100%);*/
	  margin-top: 15px;
	  margin-bottom: 15px;
	  padding: 6px 0px 6px 0px;
	  color: darkslategray;
	  box-shadow: 1px 2px 1px -1px #777;
	  transition: background 200ms ease-in-out;
	  color:#FFFFFF;
	  cursor:pointer;
	  font-size:11px;
	}
	
	.shadow1 {
	  position: relative;
	}
	.shadow1:before {
	  z-index: -1;
	  position: absolute;
	  content: "";
	  bottom: 13px;
	  right: 7px;
	  width: 75%;
	  top: 0;
	  box-shadow: 0 15px 10px #777;
	  -webkit-transform: rotate(4deg);
			  transform: rotate(4deg);
	  transition: all 150ms ease-in-out;
	}
	
	.box1:hover {
	  background: linear-gradient(to right, #0A7BEF 0%, #035BB4 100%);
	}
	
	.shadow1:hover::before {
	  -webkit-transform: rotate(0deg);
			  transform: rotate(0deg);
	  bottom: 20px;
	  z-index: -10;
	}
	
	.circle1 {
	  /*position: absolute;*/
	  margin-top: 7px;
	  left: 15px;
	  border-radius: 50%;
	  box-shadow: inset 1px 1px 1px 0px rgba(0, 0, 0, 0.5), inset 0 0 0 25px antiquewhite;
	  width: 20px;
	  height: 20px;
	  display: inline-block;
	}
	.box2{
		background: linear-gradient(to right, #CC1C4C 35%, #F647DB 100%);
	}
	.box2:hover{
		background: linear-gradient(to right, #F647DB 0%, #CC1C4C 100%);
	}
	
</style>

<link rel="stylesheet" href="css/HoverList.css">
<script src="dashboard/highcharts.js"></script>
<script src="dashboard/highcharts-3d.js"></script>
<script src="dashboard/modules/exporting.js"></script>
<script src="dashboard/lib/amcharts.js"></script>
<script src="dashboard/lib/pie.js"></script>
<script> 
	var chart = AmCharts.makeChart( "chartdiv", {
		"type": "pie",
		"theme": "light",
		"dataProvider": [ <?php echo $DonutStr; ?> ],
		"titleField": "Month",
		"valueField": "No",
		"startEffect": "elastic",
		"startDuration": 2,
		"labelRadius": 15,
		"innerRadius": "50%",
		"depth3D": 10,
		"fontSize": 15,
		"color": "#00008B",
		"balloonText": "<b><span style='font-size:14px'>[[title]] month</font></b><br><span style='font-size:14px'>total Mbook Verified : <b>[[value]]</b></span>",
		"labelText": "[[title]]",
		"export": {
			"enabled": true
		}
	});
</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<blockquote class="bq1" style="overflow:auto">
							<!--<div class="grid_12 no-padding-lr">&nbsp;</div>-->
							<div class="div12" style="padding-left:40px">
							<div class="div4 no-padding-lr">
								<div class="grid_12" align="center">
									<div class="panel panel-body border no-padding-lr">
										<div class="client-title">
											Notifications & Alerts<!--<br/><br/>-->
										</div>
										<!--<div class="client"><span class="logo"><br/>DEALING ASSISTANT</span></div>
										<div class="client"><span class="logo"><br/>ACCOUNTANT</span></div>
										<div class="client"><span class="logo"><br/>ASSISTANT ACCOUNTS OFFICER</span></div>
										<div class="client"><span class="logo"><br/>ACCOUNTS OFFICER</span></div>
										<div class="client"><span class="logo"><br/>DEPUTY CONTROLLER OF ACCOUNTS</span></div>-->
										<div class="well">
											<?php $MarginStr = 'margin-top:2px;'; if($_SESSION['levelid'] > 3){ ?>
											<a href="RABStatusAccounts.php">
											<div class="box1 box2 shadow1" style=" <?php echo $MarginStr; ?>">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">MBook / Bill Status</span>
											  	</div>
											</div>
											</a>
											<?php  $MarginStr = ''; } ?>
											<a data-url="WorksCSTList">
											<div class="box1 shadow1" style=" <?php echo $MarginStr; ?>">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">Works waiting for CST Confirm & CCNO. assign</span>
											  	</div>
											</div>
											</a>
											<a href="javascript:void(0)" id="mb_waiting_list">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:14px;">RAB waiting for verifcation - <?php echo $MBCount; ?> nos.</span>
													<div class="repstext">(with/without Secured advance & Escalation)</div>
											  	</div>
											</div>
											</a>
											<!--<a href="MeasurementBookPrint_staff_Accounts.php?view=r">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">Mbook Returned to Civil - <?php echo $MBRetCount; ?> nos.</span>
											  	</div>
											</div>
											</a>-->
											<a href="MeasurementBookPrint_staff_Accounts.php?view=r">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:14px;">Secured Advance Verification List - <?php echo $MBRetCount; ?> nos.</span>
													<div class="repstext">(with zero measurements)</div>
											  	</div>
											</div>
											</a>
											<a href="MeasurementBookPrint_staff_Accounts.php?view=r">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:14px;">Escalation Verification List - <?php echo $MBRetCount; ?> nos.</span>
													<div class="repstext">(with zero measurements)</div>
											  	</div>
											</div>
											</a>
											<div class="box1 shadow1">
												<!--<div class="circle1" style="left:5px; top:5px; left:5px; right:5px; float:right; margin-top:-19px; margin-right:-4px; background-color:#FF0000; color:#FFFFFF">dfdf</div>-->
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">Mobilization Recommendation - nos.</span>
											  	</div>
											</div>
											<a href="PGEntryViewAccounts.php">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">PG Expired List - <?php echo $PGRelCount; ?> nos.</span>
													<!--<div class="repstext">(PBG,SD,Mobilization Advance)</div>-->
											  	</div>
											</div>
											</a>
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:14px;">FDR Expired List nos.</span>
													<div class="repstext">(PBG,SD,Mobilization Advance)</div>
											  	</div>
											</div>
											<!--<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:14px;">SD Release List nos.</span>
													<div class="repstext">(Deducted in RAB's)</div>
											  	</div>
											</div>-->
											
										</div>
									</div>
								</div>
							</div>
							<div class="div3 no-padding-lr">
								<div class="grid_12" align="center">
									<div class="panel panel-body border no-padding-lr">
										<div class="client-title">
											Live & Completed Works <!--<br/>Division w.e.f.  08.09.2016<br/>-->
										</div>
										<div class="well bwell">
											<div class="well-content well-content-head">Live Works</div>
											<div class="well-content">Civil</div>
											<div class="well-content">Electrical</div>
											<div class="well-content">Mechnaical</div>
											<div class="well-content">ACV</div>
											<div class="well-content">MHE</div>
											<div class="well-content">C&I</div>
										</div>
										<!--<div class="well bwell">
											<div class="well-content">Completed Works</div>
											<div class="well-content">Vendors List</div>
										</div>-->
										
									</div>
								</div>
							</div>
							
							
							<div class="div5 no-padding-lr">
								<div class="grid_12" align="center">
									<div class="panel panel-body border no-padding-lr">
										<div class="client-title">
											MBOOK Verified Chart for the Year of <?php echo date("Y"); ?>
										</div>
										<div class="well" id="chartdiv" style="margin:0px; margin-top:5px; height:150px"></div>
									</div>
								</div>
							</div>
							</div>
						</blockquote>
					</div>
				</div>
			</div>
			
			
			<div style="display:none" id="mb_waiting_list_modal">
			<?php if(count($MBWaiListLevlArr) > 0){ foreach ($MBWaiListLevlArr as $MBLevel => $MBCount){ //$LevelName = GetRoleName($MBLevel,$_SESSION['staff_section']); ?>
				<?php if($MBLevel == $_SESSION['levelid']){ ?>
				<a href="MeasurementBookPrint_staff_Accounts.php" class="active-well"><div class="well"><div class="well-content2"><i style="font-size:24px" class="fa">&#xf058;</i> MBooks waiting in <?php echo $LevelName; ?> level <span class="badge badge-danger"><?php echo $MBCount; ?> nos.</span></div></div></a>
				<?php } else{ ?>
				<div class="well"><div class="well-content2"><i style="font-size:24px; color:#CDCDCD" class="fa">&#xf058;</i> MBooks waiting in <?php echo $LevelName; ?> level <span class="badge badge-danger"><?php echo $MBCount; ?> nos.</span></div></div>
				<?php } } }else{ ?>
				<div class="well"><div class="well-content2">No MBook Waiting for Accounts Approval</div></div>
			<?php } ?>
			</div>
			
			
            <!--==============================footer=================================-->
            <!--<script src="js/jquery.hoverdir.js"></script>-->
        </form>
           <?php include "footer/footer.html"; ?>
    </body>
</html>
<!--<script src="notificationAlert/jquery.min-3.2.1.js"></script>
<script src="notificationAlert/bootstrap-notify.js"></script>
<script src="notificationAlert/bootstrap-notify.min.js"></script>-->
<link rel="stylesheet" href="bootstrap-dialog/css/bootstrap-dialog.min.css">
<script src="bootstrap-dialog/js/bootstrap.min.js"></script> <!---IMP-->
<script src="bootstrap-dialog/js/bootstrap-dialog.min.js"></script>
<script src="bootstrap-dialog/js/run_prettify.min.js"></script>
<script> 
	$("#mb_waiting_list").click(function(event){
		var dialog = new BootstrapDialog({
			title: 'List of MBooks Waiting for Accounts Approval',
			message: $('<div></div>').html($('#mb_waiting_list_modal').html()),
			buttons: [{
				label: 'CLOSE',
				action: function(dialogRef){
					dialogRef.close();
				}
			}]
		});
		dialog.open();
	});
</script>
<script> 
	/*setInterval(function(){    
    $.ajax({
        type : "POST",
        url : "notificationAlert/GetAllUpdateForNotifyMesaage.php",
        success : function(data){ //alert(data);
			var splitData = data.split("@*@");
			var count 		= splitData[0];
			var staffid 	= splitData[1];
			var Staffname 	= splitData[2];
			var designation = splitData[3];
			var Froms 		= splitData[4];
			var image 		= splitData[5];
			var Tos 		= splitData[6];
            if (Number(count) > old_count) { 
				//$.notify("Number of records in your table is : "+data);
				$.notify({
					icon: 'uploads/'+image,//https://randomuser.me/api/portraits/med/men/77.jpg',
					title: 'Shri.'+Staffname+' '+designation+' - '+Froms,
					message: 'Check Measurement Done by '+Froms+' and waiting for the approval of '+Tos
				},{
					type: 'minimalist',
					//animate: {
						//enter: 'animated fadeInRight',
						//exit: 'animated fadeOutRight'
					//},
					offset: {
						x: 30,
						y: 10
					},
					//delay: 5000,
					icon_type: 'image',
					template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
						'<img data-notify="icon" class="img-circle pull-left">' +
						'<span data-notify="title">{1}</span>' +
						'<span data-notify="message">{2}</span>' +
					'</div>'
				});
                old_count = count;
            }
        }
    });
},1000);*/
</script> 
<style>

</style>