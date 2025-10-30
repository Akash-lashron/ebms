<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
checkUser();
$DonutStr = "";
/****************************** DONUT MBOOK CHART ****************************/
/*$SelectSheetQuery = "select count(sacid) as mb_count, DATE_FORMAT(modifieddate,'%M') as mb_month from send_accounts_and_civil where YEAR(modifieddate) = YEAR(CURDATE()) GROUP BY MONTH(modifieddate)";
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
}*/

$CurrYear = date('Y');
$NextYear = date('Y', strtotime('+1 year'));
$PrevYear = date('Y', strtotime('-1 year'));
$CurrMonth = date('n');
if($CurrMonth > 3){
	$BudFinYear = $CurrYear."-".$NextYear;
	$FinFromDate = $CurrYear."-04-01";
	$FinToDate = $NextYear."-03-31";
}else{
	$BudFinYear = $PrevYear."-".$CurrYear;
	$FinFromDate = $PrevYear."-04-01";
	$FinToDate = $CurrYear."-03-31";
}

$FinFromDate 	= "2013-04-01";
$FinToDate 		= "2014-03-31";
$SelectSheetQuery = "select sum(vr_amt) as vr_amt, DATE_FORMAT(vr_dt,'%b-%Y') as vr_month from voucher_upt where vr_dt >= '$FinFromDate' AND vr_dt <= '$FinToDate' GROUP BY MONTH(vr_dt)";
$SelectSheetSql = mysqli_query($dbConn,$SelectSheetQuery);
if($SelectSheetSql == true){
	if(mysqli_num_rows($SelectSheetSql)>0){
		while($MBList = mysqli_fetch_object($SelectSheetSql)){
			$VrAmt   = $MBList->vr_amt;
			$VrMonth = $MBList->vr_month;
			$DonutStr .= '{ "Month": "'.$VrMonth.'", "Amount(Rs.)": '.$VrAmt.' },';
		}
	}
}
if($DonutStr != ""){
	$DonutStr = rtrim($DonutStr,",");
}
//echo $DonutStr;exit;
$LineChartArr = array();
$SelectQuery1 = "select * from discipline where active = 1";
$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1)>0){
		while($DisList = mysqli_fetch_object($SelectSql1)){
			$Discipline = $DisList->discipline_name;
			$AmountArr = array(4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,1=>0,2=>0,3=>0);
			$SelectSheetQuery = "select sum(vr_amt) as vr_amt, DATE_FORMAT(vr_dt,'%c') as vr_month from voucher_upt where grp_div_sec = '$DisList->discipline_code' AND vr_dt >= '$FinFromDate' AND vr_dt <= '$FinToDate' GROUP BY MONTH(vr_dt)";
			$SelectSheetSql = mysqli_query($dbConn,$SelectSheetQuery);
			if($SelectSheetSql == true){
				if(mysqli_num_rows($SelectSheetSql)>0){
					while($MBList = mysqli_fetch_object($SelectSheetSql)){
						$VrAmt   = $MBList->vr_amt;
						$VrMonth = $MBList->vr_month;
						$AmountArr[$VrMonth] = round(($VrAmt/100000),2);
						
					}
				}
			}
			$ExpAmtArr = implode(",",$AmountArr);
			$LineChartStr = '{ name: "'.$Discipline.'", data: ['.$ExpAmtArr.'] }';
			array_push($LineChartArr,$LineChartStr);
		}
	}
}
$LineChartData = implode(",",$LineChartArr);


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
		/*box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);*/
		box-shadow: 0 4px 7px 1px rgba(0, 0, 0, 0.2);
		background-color:#fff;
		margin-bottom: 10px;
	}.panel-primary {
	  	border-color: #e3e3f7;
	}.well{
		margin:0px;
		margin-top:5px;
		margin-bottom:5px;
		padding: 5px;
	}.well-content{
		padding-top:2px;
		padding-bottom:2px;
		border:1px solid #EEF1F2;/*#F1F3F4;*/
		text-align:left;
		color:#0253A4;
		padding-left:10px;
		font-weight:500;
		margin:2px 0px;
		background:#fff;
		border-radius:0px;
	}.well-content:hover{
		background-color:#10478A;
		color:#FFFFFF;
		cursor:pointer;
		border-radius:0px;
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
	  margin-top: 8px;
	  margin-bottom: 8px;
	  padding: 6px 0px 6px 0px;
	  color: darkslategray;
	  box-shadow: 1px 2px 1px -1px #777;
	  transition: background 200ms ease-in-out;
	  color:#FFFFFF;
	  cursor:pointer;
	  font-size:13px;
	  font-weight:500;
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
	.panel-body {
 		padding: 4px;
	}
	.p-5{
		padding:5px;
	}
	.panel-primary > .panel-heading{
		background-color:#029ABC;
		color:#fff;
		font-weight:bold;
		border-bottom:0px solid #EBEDF1;
		padding: 4px 15px;
		font-size:12px;
	}
	.panel-default > .panel-heading{
		background-color:#fff;
		color:#10478A;
		font-weight:bold;
		border-bottom:0px solid #EBEDF1;
		padding: 4px 15px;
		font-size:12px;
	}
	.badge-p1{
		background:#035BCC; 
		font-size:11px;
	}
	.badge-p2{
		background:#03CC98; 
		font-size:11px;
	}
	.badge-p3{
		background:#6135D9; 
		font-size:11px;
	}
	.badge-p4{
		background:#D31F54; 
		font-size:11px;
	}
	.badge-p5{
		background:#069145; 
		font-size:11px;
	}
	.badge-p6{
		background:#AD9902; 
		font-size:11px;
	}
	.badge-box{
		float:right; 
		margin-right:8px; 
	}
	
</style>


<script>
$(function () { 
	/*$('#LineChart').highcharts({
		chart: {
			backgroundColor: '#ffffff'
           
        },
		exporting: {
         enabled: false
		},
        title: {
            text: ''
        },
		subtitle: {
            text: '',
        },
		yAxis: {
			title: {
				text: 'Billed Values in Lakhs ( Rs ) ',
				style: {
                   color: '#221F1F',
					fontSize: "12px",
					fontWeight: "bold"
                }
			}
		},
        xAxis: {
            categories: ['RAB-2','RAB-3'] ,
        },
		
        labels: {
            items: [{
                html: 'Total fruit consumption',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'red'
                }
            }]
        },
        series: [{
            type: 'column',
            name: 'General',
			color: '#2874C2',
            data: [240,419] 
        }, {
            type: 'column',
            name: 'Steel',
			color: '#4CAC2A',
            data: [58,106] 
        }, {
            type: 'column',
            name: 'Structural Steel',
			color: '#F73731',
            data: [78,196] 
        }, 
		]
    });*/
	
	Highcharts.chart('LineChart', {
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
		xAxis: {
			categories: [ 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec','Jan', 'Feb', 'Mar']
		},
        yAxis: {
            title: {
                text: 'Amount (in Lakhs)'
            }
        },
        legend: {
		enabled: true,
			itemWidth: 200,
		width:400,
		align: 'center'
	  },
		exporting: {
        	enabled: false
		},
        plotOptions: {
        	series: {
				label: {
					connectorAllowed: false
				}
         	}
       	},
       	series: [<?php echo $LineChartData; ?>]
	});
	
});
</script>
<link rel="stylesheet" href="css/HoverList.css">
<script src="dashboard/highcharts.js"></script>
<script src="dashboard/highcharts-3d.js"></script>
<script src="dashboard/modules/exporting.js"></script>
<!--<script src="dashboard/lib/amcharts.js"></script>
<script src="dashboard/lib/pie.js"></script>-->
<!--<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>-->
<script src="dashboard/lib/amcharts.js"></script>
<script src="dashboard/lib/serial.js"></script>
<script src="dashboard/lib/pie.js"></script>
<script> 
	var chart = AmCharts.makeChart( "chartdiv", {
		"type": "pie",
		"theme": "light",
		"dataProvider": [ <?php echo $DonutStr; ?> ],
		"titleField": "Month",
		"valueField": "Amount(Rs.)",
		"startEffect": "elastic",
		"startDuration": 2,
		"labelRadius": 15,
		"innerRadius": "50%",
		"depth3D": 10,
		"fontSize": 9,
		"color": "#00008B",
		"balloonText": "<b><span style='font-size:11px'>[[title]]</font></b><br><span style='font-size:11px'><b> Rs. [[value]]</b></span>",
		"labelText": "[[title]]",
		"export": {
			"enabled": true
		}
	});
	
	
	
	var chart2 = AmCharts.makeChart("CylindChart", {
    "theme": "none",
    "type": "serial",
    "startDuration": 2,
    "dataProvider": [{
        "country": "USA",
        "visits": 10,
        "color": "#FF0F00"
    }, {
        "country": "China",
        "visits": 11,
        "color": "#FF6600"
    }, {
        "country": "Japan",
        "visits": 12,
        "color": "#FF9E01"
    }, {
        "country": "Germany",
        "visits": 10,
        "color": "#FCD202"
    }, {
        "country": "UK",
        "visits": 12,
        "color": "#F8FF01"
    }, {
        "country": "India",
        "visits": 12,
        "color": "#04D215"
    }],
    "valueAxes": [{
        "position": "left",
        "axisAlpha":0,
        "gridAlpha":0
    }],
    "graphs": [{
        "balloonText": "[[category]]: <b>[[value]]</b>",
        "colorField": "color",
        "fillAlphas": 0.85,
        "lineAlpha": 0.1,
        "type": "column",
        "topRadius":1,
        "valueField": "visits"
    }],
    "depth3D": 40,
	"angle": 30,
    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "country",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha":0,
        "gridAlpha":0

    }

}, 0);
	
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
							<div class="div4 p-5">
								<div class="grid_12" align="center">
									<div class="panel panel-default">
										<div class="panel-heading" align="left">
											Notifications for verifications & approval
										</div>
										<div class="panel-body border">
										
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
											<!--<a href="MeasurementBookPrint_staff_Accounts.php?view=r">
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
											</a>-->
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">Mobilization Recommendation - nos.</span>
											  	</div>
											</div>
											
											<!--<a href="PGEntryViewAccounts.php">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">PG Expired List - <?php echo $PGRelCount; ?> nos.</span>
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
											</div>-->
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
									
									
									<div class="panel panel-primary">
										<div class="panel-heading">
											Live Works <!--<br/>Division w.e.f.  08.09.2016<br/>-->
										</div>
										<div class="panel-body border">
											<div id="CylindChart" style="margin:0px; margin-top:1px; height:220px"></div>
											<!--<div class="well-content">Civil <span class="badge-box"><span class="badge badge-p1">10</span> Nos.</span></div>
											<div class="well-content">Electrical <span class="badge-box"><span class="badge badge-p2">10</span> Nos.</span></div>
											<div class="well-content">Mechnaical <span class="badge-box"><span class="badge badge-p3">10</span> Nos.</span></div>
											<div class="well-content">ACV <span class="badge-box"><span class="badge badge-p4">10</span> Nos.</span></div>
											<div class="well-content">MHE <span class="badge-box"><span class="badge badge-p5">10</span> Nos.</span></div>
											<div class="well-content">C&I <span class="badge-box"><span class="badge badge-p6">10</span> Nos.</span></div>-->
										</div>
									</div>
									
								</div>
							</div>
							<div class="div3 p-5">
								<div class="grid_12" align="center">
									<div class="panel panel-primary">
										<div class="panel-heading" align="left">
											PG Expiry BG/FDR / Release Remainders 
										</div>
										<div class="panel-body border">
											<a data-url="DashPGBGExpiredRemainder"><div class="well-content" >Bank Guarantee Expiry List - <span class="badge badge-p4" id="DboardPGBGEXPLISTCount"></span> Nos. </div></a>
											<a data-url="DashPGFDRExpiredRemainder"><div class="well-content">FDR Expiry List - <span class="badge badge-p5" id="DboardPGFDREXPLISTCount"></span> Nos. </div></a>
											<a data-url="DashPGReleaseList"><div class="well-content">Performance Guarantee Release List - <span class="badge badge-p6" id="DboardPGRELEASELISTCount"></span> Nos. </div></a>
										</div>
									</div>
									<div class="panel panel-primary">
										<div class="panel-heading" align="left">
											SD Expiry BG / Release Remainders 
										</div>
										<div class="panel-body border">
											<a data-url="DashSDBGExpiredRemainder"><div class="well-content">Bank Guarantee Expiry List - <span class="badge badge-p1" id="DboardSDBGEXPLISTCount"></span> Nos. </div></a>
											<a data-url="DashSDReleaseList"><div class="well-content">Security Deposit Release List - <span class="badge badge-p2" id="DboardSDRELEASELISTCount"></span> Nos. </div></a>
										</div>
									</div>
									<div class="panel panel-primary">
										<div class="panel-heading" align="left">
											Mobilization Advance BG Expiry Remainders
										</div>
										<div class="panel-body border">
											<div class="well-content">Bank Guarantee Expiry List - <span class="badge badge-p3">10</span> Nos. </div>
										</div>
									</div>
									<div class="panel panel-primary">
										<div class="panel-heading" align="left">
											EMD  Return Remainders 
										</div>
										<div class="panel-body border">
											<a data-url="DashEMDDDReturnRemainder"><div class="well-content">DD/Banker's Cheque List - <span class="badge badge-p1" id="DboardEMDDDRETURNLISTCount"></span> Nos. </div></a>
											<a data-url="DashEMDFDRReturnRemainder"><div class="well-content">TDR/FDR List - <span class="badge badge-p2" id="DboardEMDFDRRETURNLISTCount"></span> Nos. </div></a>
											<a data-url="DashEMDBGReturnRemainder"><div class="well-content">Bank Guarantee List - <span class="badge badge-p6" id="DboardEMDBGRRETURNLISTCount"></span> Nos. </div></a>
										</div>
									</div>
									<!--<div class="panel panel-primary">
										<div class="panel-heading">
											EMD  Return Remainders 
										</div>
										<div class="panel-body border">
											<div class="well-content">DD/Banker's Cheque List - 0 Nos. </div>
											<div class="well-content">TDR/FDR List - 0 Nos. </div>
											<div class="well-content">Bank Guarantee List - 0 Nos. </div>
										</div>
									</div>-->
								</div>
							</div>
							
							
							<div class="div5 p-5">
							
								<div class="grid_12" align="center">
									<div class="panel panel-primary">
										<div class="panel-heading">
											Bill payment occurred on FY 2013-2014 (Discipline Wise) <!--<br/>Division w.e.f.  08.09.2016<br/>-->
										</div>
										<div class="panel-body border">
											<div id="LineChart" style="margin:0px; margin-top:1px; height:220px"></div>
										</div>
									</div>
									<div class="panel panel-primary">
										<div class="panel-heading" style="font-size:11px;">
											Bill payment occurred on FY 2013-2014<?php //echo date("Y"); ?>
										</div>
										<div class="panel-body border">
											<div id="chartdiv" style="margin:0px; margin-top:1px; height:220px"></div>
										</div>
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
	$(window).load(function() {
        $.ajax({
			type:'GET',
			url: 'ajax/GetDashboardDetails.php',
			data: {page: 'PGBGEXPLIST'},
			success:function(data){
				if(data){// alert(data);
                    if(data != null){
					    $("#DboardPGBGEXPLISTCount").text(data);
                    }else{
                        $("#DboardPGBGEXPLISTCount").text('0');
                    }
				}
			}
		});
    });
	$(window).load(function() {
        $.ajax({
			type:'GET',
			url: 'ajax/GetDashboardDetails.php',
			data: {page: 'PGFDREXPLIST'},
			success:function(data){
				if(data){ //alert(data);
                    if(data != null){
					    $("#DboardPGFDREXPLISTCount").text(data);
                    }else{
                        $("#DboardPGFDREXPLISTCount").text('0');
                    }
				}
			}
		});
    });
	$(window).load(function() {
        $.ajax({
			type:'GET',
			url: 'ajax/GetDashboardDetails.php',
			data: {page: 'PGRELEASELIST'},
			success:function(data){
				if(data){ //alert(data);
                    if(data != null){
					    $("#DboardPGRELEASELISTCount").text(data);
                    }else{
                        $("#DboardPGRELEASELISTCount").text('0');
                    }
				}
			}
		});
    });
	$(window).load(function() {
        $.ajax({
			type:'GET',
			url: 'ajax/GetDashboardDetails.php',
			data: {page: 'SDBGEXPLIST'},
			success:function(data){
				if(data){ //alert(data);
                    if(data != null){
					    $("#DboardSDBGEXPLISTCount").text(data);
                    }else{
                        $("#DboardSDBGEXPLISTCount").text('0');
                    }
				}
			}
		});
    });
	$(window).load(function() {
        $.ajax({
			type:'GET',
			url: 'ajax/GetDashboardDetails.php',
			data: {page: 'SDRELEASELIST'},
			success:function(data){
				if(data){ //alert(data);
                    if(data != null){
					    $("#DboardSDRELEASELISTCount").text(data);
                    }else{
                        $("#DboardSDRELEASELISTCount").text('0');
                    }
				}
			}
		});
    });
	$(window).load(function() {
        $.ajax({
			type:'GET',
			url: 'ajax/GetDashboardDetails.php',
			data: {page: 'EMDDDRETURNLIST'},
			success:function(data){
				if(data){ //alert(data);
                    if(data != null){
					    $("#DboardEMDDDRETURNLISTCount").text(data);
                    }else{
                        $("#DboardEMDDDRETURNLISTCount").text('0');
                    }
				}
			}
		});
    });
	$(window).load(function() {
        $.ajax({
			type:'GET',
			url: 'ajax/GetDashboardDetails.php',
			data: {page: 'EMDFDRRETURNLIST'},
			success:function(data){
				if(data){ //alert(data);
                    if(data != null){
					    $("#DboardEMDFDRRETURNLISTCount").text(data);
                    }else{
                        $("#DboardEMDFDRRETURNLISTCount").text('0');
                    }
				}
			}
		});
    });
	$(window).load(function() {
        $.ajax({
			type:'GET',
			url: 'ajax/GetDashboardDetails.php',
			data: {page: 'EMDBGRRETURNLIST'},
			success:function(data){
				if(data){ //alert(data);
                    if(data != null){
					    $("#DboardEMDBGRRETURNLISTCount").text(data);
                    }else{
                        $("#DboardEMDBGRRETURNLISTCount").text('0');
                    }
				}
			}
		});
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