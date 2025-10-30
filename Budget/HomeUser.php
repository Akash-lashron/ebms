<?php
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
	  margin-bottom: 5px;
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
		background-color:#10478A;
		color:#fff;
		font-weight:600;
		border-bottom:0px solid #EBEDF1;
		padding: 4px 15px;
		font-size:11px;
	}
	.panel-default > .panel-heading{
		background-color:#fff;
		color:#10478A;
		font-weight:600;
		border-bottom:0px solid #EBEDF1;
		padding: 4px 15px;
		font-size:11px;
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
	
	
	.3dCheck {
  opacity: 0;
  position: absolute;
}

.ChLable {
  position: relative;
  display: block;
  background: #fff;/*#f8f8f8;*/
  border: 1px solid #f0f0f0;
  border-radius: 2em;
  padding: 0.8em 1em 0.8em 1em;
  box-shadow: 0 1px 2px rgba(100, 100, 100, 0.5) inset, 0 0 10px rgba(100, 100, 100, 0.1) inset;
  cursor: pointer;
  text-shadow: 0 2px 2px #fff;
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:13px;
  font-weight:500;
  box-shadow: 0 4px 7px 1px rgba(0, 0, 0, 0.2);
  border: 0.5px solid #00bcd4 !important;
  border-bottom: 2px solid #00bcd4 !important;
}
.ChLable::before {
  content: "";
  position: absolute;
  top: 50%;
  right: 0.7em;
  width: 3em;
  height: 1.2em;
  border-radius: 0.6em;
  background: #eee;
  transform: translateY(-50%);
  box-shadow: 0 1px 3px rgba(100, 100, 100, 0.5) inset, 0 0 10px rgba(100, 100, 100, 0.2) inset;
}
.ChLable::after {
  content: "";
  position: absolute;
  top: 48%;
  right: 2.6em;
  width: 1.4em;
  height: 1.4em;
  border: 0.25em solid #fafafa;
  border-radius: 50%;
  box-sizing: border-box;
  background-color: #ddd;
  background-image: linear-gradient(to top, #fff 0%, #fff 40%, transparent 100%);
  transform: translateY(-50%);
  box-shadow: 0 3px 3px rgba(0, 0, 0, 0.5);
}
.ChLable, .ChLable::before, .ChLable::after {
  transition: all 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.ChLable:hover, input:focus + .ChLable {
  color: black;
}
.ChLable:hover::after, input:focus + .ChLable::after {
  background-color: #ccc;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}

input:checked {
  counter-increment: total;
}
input:checked + .ChLable::before {
  background: #1CE;
}
input:checked + .ChLable::after {
  transform: translateX(2em) translateY(-50%);
}
.Btn-3Check{
  margin: 1em 0;
  /*font: 1.5em/1.4 "Open Sans Condensed", sans-serif;*/
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:17px;
  font-weight:400;
  color: #2F373E;
  width:100%;
  text-align:left;
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
	
	/*Highcharts.chart('LineChart', {
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
                text: 'Rs. (in Lakhs)'
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
       	series: [<?php //echo $LineChartData; ?>]
	});*/
	
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
	/*var chart = AmCharts.makeChart( "chartdiv", {
		"type": "pie",
		"theme": "light",
		"dataProvider": [ <?php //echo $DonutStr; ?> ],
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
	});*/
	
	
	
	/*var chart2 = AmCharts.makeChart("CylindChart", {
    "theme": "none",
    "type": "serial",
    "startDuration": 2,
	"titles": [ {
		"text": "No. of live works as of now",
		"size": 11
	  }
	 ],
    "dataProvider": [<?php //echo $CylindChartData; ?>],
    "valueAxes": [{
        "position": "left",
        "axisAlpha":0,
        "gridAlpha":0
    }],
    "graphs": [{
        "balloonText": "[[category]]: <b>[[value]] Works</b>",
        "colorField": "color",
        "fillAlphas": 0.85,
        "lineAlpha": 0.1,
        "type": "column",
        "topRadius":1,
        "valueField": "works"
    }],
    "depth3D": 40,
	"angle": 30,
    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "discipline",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha":0,
        "gridAlpha":0

    }

}, 0);*/
	
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
										<!--<div class="panel-heading" align="left">
											Notifications for verifications & approval
										</div>-->
										<div class="panel-body border">
											<?php $MarginStr = 'margin-top:2px;'; ?>
											<a data-url="WorksCSTList">
											<div class="box1 shadow1" style=" <?php echo $MarginStr; ?>">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
												 	<span style="margin-top:25px; line-height:30px;">Waiting for CCNO. assign & CST Confirm - <span id="DboardCSTWaitingCount"></span> nos.</span>
											  	</div>
											</div>
											</a>
											<a data-url="WorksNegoCSTList">
											<div class="box1 shadow1" style="">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
												 	<span style="margin-top:25px; line-height:30px;">Waiting for Negotiation CST Confirm - <span id="DboardNEGOCSTWaitingCount"></span> nos.</span>
											  	</div>
											</div>
											</a>
											<?//php if($_SESSION['levelid'] == 1){ ?>
											<!-- <a data-url="WorkRegistration">
											<div class="box1 shadow1" style="">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">Bill waiting for Registration - <span id="DboardBILLRCount"></span></span>
											  	</div>
											</div>
											</a> -->
											<?php //} ?>
											<a>
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:14px;">RAB waiting for verifcation - <?php echo 0;//$WorkCnt; ?> nos.</span>
													<div class="repstext">(with/without Secured advance & Escalation)</div>
											  	</div>
											</div>
											</a>
											<!-- <a data-url="BiddersBankWaitfrConfList">
												<div class="box1 shadow1">
													<div class="grid_2" align="center">
														<div class="circle1"></div>
													</div>
													<div class="grid_10" align="left">
														<span style="margin-top:25px; line-height:30px;">Contractor Bank Detail's Waiting For Confirm</span>
													</div>
												</div>
											</a> -->
											<a data-url="DashBGExpiredRemainder">
											<div class="box1 shadow1" style="">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
												 	<span style="margin-top:25px; line-height:30px;">BG Expiry List (PG/SD/MOB.ADV.) - <span id="DboardBGEXPLISTCount"></span> nos.</span>
											  	</div>
											</div>
											</a>
											<a data-url="DashFDRExpiredRemainder">
											<div class="box1 shadow1" style="">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
												 	<span style="margin-top:25px; line-height:30px;">FDR Expiry List (PG/SD/MOB.ADV.) - <span id="DboardFDREXPLISTCount"></span> nos.</span>
											  	</div>
											</div>
											</a>
											<a data-url="PGregisterReleaseList">
											<div class="box1 shadow1" style="">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
												 	<span style="margin-top:25px; line-height:30px;">PG Release List - <span id="DboardPGRELEASELISTCount"></span> nos.</span>
											  	</div>
											</div>
											</a>
											<a data-url="SDregisterReleaseListStatement">
											<div class="box1 shadow1" style="">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
												 	<span style="margin-top:25px; line-height:30px;">SD Release List - <span id="DboardSDRELEASELISTCount"></span> nos.</span>
											  	</div>
											</div>
											</a>
											<a data-url="EMDReturnRemainder">
											<div class="box1 shadow1" style="">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
												 	<span style="margin-top:25px; line-height:30px;">EMD Release Reminders - <span id="DboardEMDDDRETURNLISTCount"></span> nos.</span>
											  	</div>
											</div>
											</a>
											
											
											<?php /*$MarginStr = 'margin-top:2px;'; if($_SESSION['levelid'] > 3){ ?>
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
											<?php  $MarginStr = ''; }*/ ?>
											
											
											<!--<a href="MeasurementBookPrint_staff_Accounts.php?view=r">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">Mbook Returned to Civil - <?php //echo $MBRetCount; ?> nos.</span>
											  	</div>
											</div>
											</a>-->
											<!--<a href="MeasurementBookPrint_staff_Accounts.php?view=r">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:14px;">Secured Advance Verification List - <?php //echo $MBRetCount; ?> nos.</span>
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
											  		<span style="margin-top:25px; line-height:14px;">Escalation Verification List - <?php //echo $MBRetCount; ?> nos.</span>
													<div class="repstext">(with zero measurements)</div>
											  	</div>
											</div>
											</a>-->
											<!-- <a data-url="WorkStatusList">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">Work Status</span>
											  	</div>
											</div>
											</a> -->
											
											<!--<a data-url="WorkStatusList">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">Work Status</span>
											  	</div>
											</div>
											</a>-->
											<!--<a href="PGEntryViewAccounts.php">
											<div class="box1 shadow1">
												<div class="grid_2" align="center">
											  		<div class="circle1"></div>
											 	</div>
											 	<div class="grid_10" align="left">
											  		<span style="margin-top:25px; line-height:30px;">PG Expired List - <?php //echo $PGRelCount; ?> nos.</span>
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
									
									
									<!--<div class="panel panel-primary">
										<div class="panel-heading">
											Live Works (Discipline Wise) 
										</div>
										<a data-url="LiveWorksList">
											<div class="panel-body border">
												<div id="CylindChart" style="margin:0px; margin-top:1px; height:220px; cursor:pointer;"></div>
											</div>
										</a>
									</div>-->
								</div>
							</div>
							<div class="div4 p-5">
								<div class="grid_12" align="center">
									<div class="Btn-3Check" style="margin-top:2px;">
										<input name="PriorAppln[]" id="DeptEstFormat" type="checkbox" class="3dCheck FileFormat" style="display:none" value="DEST" data-url="DeptEstimateSampleFileFormat" checked="checked"/>
										<label class="ChLable" for="DeptEstFormat">Click here to view Dept. Estimate file format</label>
									</div>
									
									<div class="Btn-3Check">
										<input name="PriorAppln[]" id="DeptEstExcel" type="checkbox" class="3dCheck" style="display:none" value="PA" disabled="disabled" checked="checked"/>
										<label class="ChLable" for="DeptEstExcel">
											<a href="download.php?filename=DeptEstExcel.xlsx" style="color:#2F373E" title="Click here to download Department Estimate template file">
												Click here to download Dept. Estimate template
											</a>
										</label>
									</div>
									
									<div class="Btn-3Check">
										<input name="PriorAppln[]" id="FinBidFormat" type="checkbox" class="3dCheck FileFormat" style="display:none" value="FBID" data-url="FinancialBidSampleFileFormat" checked="checked"/>
										<label class="ChLable" for="FinBidFormat">Click here to view Financial Bid file format</label>
									</div>
									
									<div class="Btn-3Check">
										<input name="PriorAppln[]" id="FinBidExcel" type="checkbox" class="3dCheck" style="display:none" value="PA" disabled="disabled" checked="checked"/>
										<label class="ChLable" for="FinBidExcel">
											<a href="download.php?filename=FinBidExcel.xlsx" style="color:#2F373E" title="Click here to download Financial Bid template file">
												Click here to download Financial Bid template
											</a>
										</label>
									</div>
									<?php if(in_array("BIL", $_SESSION['ModuleAccArr'])){ ?>
									<div class="Btn-3Check" style="margin-top:2px;">
										<input name="PriorAppln[]" id="GenMeasFormat" type="checkbox" class="3dCheck FileFormat" style="display:none" value="GNL" data-url="GeneralMeasSampleFormat" checked="checked"/>
										<label class="ChLable" for="GenMeasFormat">Click here to view Gen. Measurement file format</label>
									</div>
									
									<div class="Btn-3Check">
										<input name="PriorAppln[]" id="GenMeasExcel" type="checkbox" class="3dCheck" style="display:none" value="PA" disabled="disabled" checked="checked"/>
										<label class="ChLable" for="GenMeasExcel">
											<a href="download.php?filename=GMTemplate.xlsx" style="color:#2F373E" title="Click here to download Department Estimate template file">
												Click here to download Gen. Measure. template
											</a>
										</label>
									</div>
									
									<div class="Btn-3Check">
										<input name="PriorAppln[]" id="StlMeasFormat" type="checkbox" class="3dCheck FileFormat" style="display:none" value="STL" data-url="SteelMeasSampleFormat" checked="checked"/>
										<label class="ChLable" for="StlMeasFormat">Click here to view Steel Measurement file format</label>
									</div>
									
									<div class="Btn-3Check">
										<input name="PriorAppln[]" id="StlMeasExcel" type="checkbox" class="3dCheck" style="display:none" value="PA" disabled="disabled" checked="checked"/>
										<label class="ChLable" for="StlMeasExcel">
											<a href="download.php?filename=SMTemplate.xlsx" style="color:#2F373E" title="Click here to download Financial Bid template file">
												Click here to download Steel Measure. template
											</a>
										</label>
									</div>
									<?php } ?>
									
								</div>
							</div>
							
							
							<div class="div4 p-5">
							
								
								
								<div class="grid_12" align="center">
									<div class="panel panel-primary">
										<div class="panel-heading">
											Tendering Flow (Click below steps to view details)
										</div>
										<div class="panel-body border fchart">
											<?php include("WorkFlowChart.php"); ?>
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
<link rel="stylesheet" type="text/css" href="Dashboard/FlowChart/flow-chart.css">
<script src="Dashboard/FlowChart/flow-chart.js" type="text/javascript"></script>
<link rel="stylesheet" href="bootstrap-dialog/css/bootstrap-dialog.min.css">
<script src="bootstrap-dialog/js/bootstrap.min.js"></script> <!---IMP-->
<script src="bootstrap-dialog/js/bootstrap-dialog.min.js"></script>
<script src="bootstrap-dialog/js/run_prettify.min.js"></script>
<script> 
$(function(){

	var ep = new Vue({
		el: "#ep-flowchart",
		data: {
			selected: ""
		},
		methods: {}
	});
	$(window).load(function() {
		$.ajax({
			type:'GET',
			url: 'ajax/GetDashboardDetails.php',
			data: {page: 'BILLR'},
			success:function(data){
				if(data){ 
					if(data != null){
						$("#DboardBILLRCount").text(data);
					}else{
						$("#DboardBILLRCount").text(0);
					}
				}
			}
		});
	});	
	$(window).load(function() {
		$.ajax({
			type:'GET',
			url: '../Accounts/ajax/GetDashboardDetails.php',
			data: {page: 'CSTWAITINGLIST'},
			success:function(data){
				if(data){ //
					if(data != null){ //alert(data);
						$("#DboardCSTWaitingCount").text(data);
					}else{
						$("#DboardCSTWaitingCount").text('0');
					}
				}
			}
		});
	});
	$(window).load(function() {
		$.ajax({
			type:'GET',
			url: '../Accounts/ajax/GetDashboardDetails.php',
			data: {page: 'NEGOCSTWAITINGLIST'},
			success:function(data){
				if(data){ //
					if(data != null){ //alert(data);
						$("#DboardNEGOCSTWaitingCount").text(data);
					}else{
						$("#DboardNEGOCSTWaitingCount").text('0');
					}
				}
			}
		});
	});
	$(window).load(function() {
		$.ajax({
			type:'GET',
			url: '../Accounts/ajax/GetDashboardDetails.php',
			data: {page: 'BGEXPLIST'},
			success:function(data){
				if(data){// alert(data);
					if(data != null){
						$("#DboardBGEXPLISTCount").text(data);
					}else{
						$("#DboardBGEXPLISTCount").text('0');
					}
				}
			}
		});
	});
	$(window).load(function() {
		$.ajax({
			type:'GET',
			url: '../Accounts/ajax/GetDashboardDetails.php',
			data: {page: 'FDREXPLIST'},
			success:function(data){
				if(data){ //alert(data);
					if(data != null){
						$("#DboardFDREXPLISTCount").text(data);
					}else{
						$("#DboardFDREXPLISTCount").text('0');
					}
				}
			}
		});
	});
	$(window).load(function() {
		$.ajax({
			type:'GET',
			url: '../Accounts/ajax/GetDashboardDetails.php',
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
			url: '../Accounts/ajax/GetDashboardDetails.php',
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
			url: '../Accounts/ajax/GetDashboardDetails.php',
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


	/*	$(window).load(function() {
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
	});		*/
	function ConvertIndianRsFormat(Amount){
		var AmountStr = Number(Amount).toLocaleString('en-IN');
		var AmountStrSplit = AmountStr.split(".");
		if(AmountStrSplit.length == 2){
			var Rupees = AmountStrSplit[0];
			var Paise  = AmountStrSplit[1];
			if(Paise.length == 0){
				var PaiseStr = "00";
			}else if(Paise.length == 1){
				var PaiseStr = Paise+"0";
			}else{
				var PaiseStr = Paise;
			}
			Amount = Rupees+"."+PaiseStr;
		}else{
			Amount = AmountStr+".00";
		}
		return Amount;
		
	}
	
	$("body").on("click",".FileFormat", function(event){
		var url = $(this).attr("data-url");
		var win = window.open(url+'.php', '_blank');
	  	win.focus();
	});
	var StmtCode = "";
	$("body").on("click",".Stmt", function(event){
		$(this).prop("checked", true);
		StmtCode = "";
		StmtCode = $(this).attr("id");
		let StartYr = 2013;
		const SysDate 	= new Date();
		let SysYear 	= SysDate.getFullYear();
		var OptionStr   = '';
		for(StartYr = 2013; StartYr <= SysYear; StartYr++){
			OptionStr += '<option value="'+StartYr+'">'+StartYr+'</option>';
		}
		if(StmtCode == "ITSTMT"){
			var TitlePart1 = "IT";
			var Colspan = 9;
		}else if(StmtCode == "GSTSTMT"){
			var TitlePart1 = "GST";
			var Colspan = 13;
		}else if(StmtCode == "LCESSSTMT"){
			var TitlePart1 = "Labour CESS";
			var Colspan = 8;
		}else if(StmtCode == "SDRCSTMT"){
			var TitlePart1 = "SD Recovery";
			var Colspan = 11;
		}else if(StmtCode == "PSDBRSH"){
			var TitlePart1 = "Performance Guarantee BroadSheet";
			var Colspan = 12;
		}else if(StmtCode == "SDBRSH"){
			var TitlePart1 = "SD BroadSheet";
			var Colspan = 9;
		}else if(StmtCode == "VOUCH"){
			var TitlePart1 = "Voucher Expenditure";
			var Colspan = 8;
		}
		var DropDwnStr = '<select name="cmb_modal_yr" id="cmb_modal_yr" class="ModalYr">';
			DropDwnStr += OptionStr;
			DropDwnStr += '</select>&nbsp;';
		var ButtonStr  = '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth01" value="01"><span>Jan</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth02" value="02"><span>Feb</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth03" value="03"><span>Mar</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth04" value="04"><span>Apr</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth05" value="05"><span>May</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth06" value="06"><span>Jun</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth07" value="07"><span>Jul</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth08" value="08"><span>Aug</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth09" value="09"><span>Sep</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth10" value="10"><span>Oct</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth11" value="11"><span>Nov</span></label></div>';
			ButtonStr += '<div class="cat action"><label><input type="radio" name="month" class="ModMonth" id="ModMonth12" value="12"><span>Dec</span></label></div>';
		var TableStr   = '';
			TableStr += '<table class="table dataTable rtable table2excel" id="StmtTable" border="1" width="100%" align="center">';
			TableStr += '<tr><td colspan="'+Colspan+'" style="text-align:left">'+DropDwnStr+ButtonStr+'</td></tr>';
			TableStr += '</table>';
		
		BootstrapDialog.show({
			message: TableStr,
			title:TitlePart1+' Statement for the Month of <span id="ModalTitle"></span>',
			size:'LARGE',
			onshown: function(dialogRef){
				$(".modal-dialog").css('width','90%');
				GetMonthlyData(StmtCode,"PREV")
			}
		});
		
		
	});
	$("body").on("change","#cmb_modal_yr", function(event){
		$(".ModMonth").prop("checked", false);
	});
	$("body").on("click",".ModMonth", function(event){
		var ModMonth = $(this).val();
		var ModYear = $("#cmb_modal_yr").val();
		if((ModMonth != '')&&(ModYear != '')){
			var ModMonYr = ModYear+"-"+ModMonth+"-01";
			GetMonthlyData(StmtCode,ModMonYr)
		}
	});
	function GetMonthlyData(StmtCode,MonthYr){
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataReports.php', 
			data: { StmtCode: StmtCode, MonthYr: MonthYr }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){
					var TableStr = "";
					var Sno = 1; var TotalItAmt = 0;
					var MonthYrStr = data['month_yr_dp'];
					var MonthStr = data['month_str'];
					var YearStr  = data['year_str'];
					$("#ModalTitle").html(MonthYrStr);
					$("#cmb_modal_yr").val(YearStr);
					$("#ModMonth"+MonthStr).prop("checked", true);
					
					if(StmtCode == "ITSTMT"){													////////////////////// ------ FOR IT STATEMENT ------ //////////////////////
						TableStr += '<tr>';
						TableStr += '<th class="colhead">S.No</th>';
						TableStr += '<th class="colhead">PAN</th>';
						TableStr += '<th class="colhead">Name of Contractor</th>';
						TableStr += '<th class="colhead">Bill Value (&#x20b9;)</th>';
						TableStr += '<th class="colhead">Bill Value For IT (&#x20b9;)</th>';
						TableStr += '<th class="colhead">Date of Payment</th>';
						TableStr += '<th class="colhead">Percent (%)</th>';
						TableStr += '<th class="colhead">IT Amount (&#x20b9;)</th>';
						TableStr += '<th class="colhead" style="font-weight:bold;">Remarks</th>';
						TableStr += '</tr>';
						var StmtData = data['data'];
						$.each(StmtData, function(index, element) { 
							var AbstNetAmt = element.abstract_net_amt;
							AbstNetAmt 		= Number(AbstNetAmt).toFixed(2);
							var BillAmtIt 	= element.bill_amt_it;
							BillAmtIt 		= Number(BillAmtIt).toFixed(2);
							var ItAmt 		= element.incometax_amt;
							ItAmt 			= Number(ItAmt).toFixed(2);
							var AbstNetAmtStr	= ConvertIndianRsFormat(AbstNetAmt);
							var BillAmtItStr 	= ConvertIndianRsFormat(BillAmtIt);
							var ItAmtStr  		= ConvertIndianRsFormat(ItAmt);

							TotalItAmt = Number(TotalItAmt) + Number(ItAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter">'+Sno+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap"></td>';
							TableStr += '<td class="labelleft" nowrap="nowrap"></td>';
							TableStr += '<td class="labelright">'+AbstNetAmtStr+'</td>';
							TableStr += '<td class="labelright">'+BillAmtItStr+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+element.payment_dt+'</td>';
							TableStr += '<td class="labelright" nowrap>'+element.incometax_percent+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+ItAmtStr+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap"></td>';
							TableStr += '</tr>';
							Sno++;
						});
						if(TotalItAmt > 0){
							TotalItAmt 	= Number(TotalItAmt).toFixed(2);
							var TotalItAmtStr  	= ConvertIndianRsFormat(TotalItAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelright mod-totrow" colspan="7"><b> Total IT Amount (&#x20b9;)</b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b>'+TotalItAmtStr+'</b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"></td>';
							TableStr += '</tr>';
						}else{
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter" colspan="9">No Records Found</td>';
							TableStr += '</tr>';
						}
					}else if(StmtCode == "GSTSTMT"){									////////////////////// ------ FOR GST STATEMENT ------ //////////////////////
						TableStr += '<tr>';
						TableStr += '<th class="colhead">S.No</th>';
						TableStr += '<th class="colhead">CCNO.</th>';
						TableStr += '<th class="colhead">RAB NO.</th>';
						TableStr += '<th class="colhead">Name of Contractor</th>';
						TableStr += '<th class="colhead">PAN NO.</th>';
						TableStr += '<th class="colhead">GST NO.</th>';
						TableStr += '<th class="colhead">Date of Payment</th>';
						TableStr += '<th class="colhead">Bill Value (&#x20b9;)</th>';
						TableStr += '<th class="colhead">IGST TDS</th>';
						TableStr += '<th class="colhead">CGST TDS</th>';
						TableStr += '<th class="colhead">SGST TDS</th>';
						TableStr += '<th class="colhead">GST Amount (&#x20b9;)</th>';
						TableStr += '<th class="colhead" style="font-weight:bold;">Remarks</th>';
						TableStr += '</tr>';
						var TotalBillAmt = 0;
						var TotalGstAmt = 0;
						var StmtData = data['data'];	
						var CCnoData = data['ccnodata'];	//alert(JSON.stringify(CCnoData));
						var ContNameData 	= data['contnamedata'];	//alert(JSON.stringify(CCnoData));
						var ContPanData 	= data['contpandata'];	//alert(JSON.stringify(CCnoData));
						var ContGstData 	= data['contgstdata'];	//alert(JSON.stringify(CCnoData));
						$.each(StmtData, function(index, element) { 
							var RbnVal = element.rbn;
							var sheet_idVal = element.sheetid;
							var CCnoVal 	= CCnoData[sheet_idVal];
							var ContName 	= ContNameData[sheet_idVal];
							var ContPan 	= ContPanData[sheet_idVal];
							var ContGst 	= ContGstData[sheet_idVal];
							var GstAmt 		= element.gst_amount;
							GstAmt 			= Number(GstAmt).toFixed(2);
							var GstAmtMonForm = ConvertIndianRsFormat(GstAmt);
							var AbstNetAmt = element.abstract_net_amt;
							AbstNetAmt 		= Number(AbstNetAmt).toFixed(2);
							var AbstNetAmtMonForm = ConvertIndianRsFormat(AbstNetAmt);
							var SGSTAmt	= element.sgst_tds_amt;
							SGSTAmt 		= Number(SGSTAmt).toFixed(2);
							var SGSTAmtMonForm = ConvertIndianRsFormat(SGSTAmt);
							var CGSTAmt	= element.cgst_tds_amt;
							CGSTAmt 		= Number(CGSTAmt).toFixed(2);
							var CGSTAmtMonForm = ConvertIndianRsFormat(CGSTAmt);
							var IGSTAmt	= element.igst_tds_amt;
							IGSTAmt 		= Number(IGSTAmt).toFixed(2);
							var IGSTAmtMonForm = ConvertIndianRsFormat(IGSTAmt);
							
							TotalBillAmt = Number(TotalBillAmt) + Number(AbstNetAmt);
							TotalGstAmt = Number(TotalGstAmt) + Number(GstAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter">'+Sno+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+CCnoVal+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+RbnVal+'</td>';
							TableStr += '<td class="labelleft">'+ContName+'</td>';
							TableStr += '<td class="labelleft">'+ContPan+'</td>';
							TableStr += '<td class="labelleft" nowrap="nowrap">'+ContGst+'</td>';
							TableStr += '<td class="labelcenter" nowrap>'+element.payment_dt+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+AbstNetAmtMonForm+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+IGSTAmtMonForm+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+CGSTAmtMonForm+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+SGSTAmtMonForm+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+GstAmtMonForm+'</td>';
							TableStr += '<td class="labelleft" nowrap="nowrap"> </td>';
							TableStr += '</tr>';
							Sno++;
						});
						if(TotalBillAmt > 0){
							TotalBillAmt 	= Number(TotalBillAmt).toFixed(2);
							var TotalBillAmtStr = ConvertIndianRsFormat(TotalBillAmt);
							TotalGstAmt 	= Number(TotalGstAmt).toFixed(2);
							var TotalGstAmtStr = ConvertIndianRsFormat(TotalGstAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelright mod-totrow" colspan="11"><b> Total GST Amount (&#x20b9;)</b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b>'+TotalGstAmtStr+'</b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"></td>';
							TableStr += '</tr>';
						}else{
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter" colspan="13">No Records Found</td>';
							TableStr += '</tr>';
						}
					}else if(StmtCode == "LCESSSTMT"){									////////////////////// ------ FOR LCESS STATEMENT ------ //////////////////////
						TableStr += '<tr>';
						TableStr += '<th class="colhead">S.No</th>';
						TableStr += '<th class="colhead">CCNO.</th>';
						TableStr += '<th class="colhead">RAB NO.</th>';
						TableStr += '<th class="colhead">Name of Contractor</th>';
						TableStr += '<th class="colhead">Name of Work</th>';
						TableStr += '<th class="colhead">Date of Payment</th>';
						TableStr += '<th class="colhead">Bill Value (&#x20b9;)</th>';
						TableStr += '<th class="colhead">LCESS Amount (&#x20b9;)</th>';
						TableStr += '</tr>';
						var TotalBillAmt = 0;
						var TotalLcessAmt = 0;
						var StmtData = data['data'];
						var CCnoData = data['ccnodata'];
						var ContNameData 	= data['contnamedata'];
						var WorkNameData 	= data['worknamedata'];	//alert(JSON.stringify(CCnoData));
						$.each(StmtData, function(index, element) {
							var RbnVal 		= element.rbn;
							var sheet_idVal = element.sheetid;
							var CCnoVal 	= CCnoData[sheet_idVal];
							var ContName 	= ContNameData[sheet_idVal];
							var WorkName 	= WorkNameData[sheet_idVal];
							var LcessAmt	= element.lw_cess_amt;
								LcessAmt 	= Number(LcessAmt).toFixed(2);
							var LcessAmtMonForm = ConvertIndianRsFormat(LcessAmt);
							var AbstNetAmt = element.abstract_net_amt;
								AbstNetAmt 	= Number(AbstNetAmt).toFixed(2);
							var AbstNetAmtMonForm = ConvertIndianRsFormat(AbstNetAmt);
							
							TotalBillAmt 	= Number(TotalBillAmt) + Number(AbstNetAmt);
							TotalLcessAmt 	= Number(TotalLcessAmt) + Number(LcessAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter">'+Sno+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+CCnoVal+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+RbnVal+'</td>';
							TableStr += '<td class="labelleft">'+ContName+'</td>';
							TableStr += '<td class="labelleft">'+WorkName+'</td>';
							TableStr += '<td class="labelcenter" nowrap>'+element.payment_dt+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+AbstNetAmtMonForm+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+LcessAmtMonForm+'</td>';
							TableStr += '</tr>';
							Sno++;
						});
						if(TotalBillAmt > 0){
							TotalBillAmt 	= Number(TotalBillAmt).toFixed(2);
							var TotalBillAmtStr = ConvertIndianRsFormat(TotalBillAmt);
							TotalLcessAmt 	= Number(TotalLcessAmt).toFixed(2);
							var TotalLcessAmtStr = ConvertIndianRsFormat(TotalLcessAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelright mod-totrow" colspan="7"><b> Total LCESS Amount (&#x20b9;)</b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b>'+TotalLcessAmtStr+'</b></td>';
							TableStr += '</tr>';
						}else{
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter" colspan="8">No Records Found</td>';
							TableStr += '</tr>';
						}
					}else if(StmtCode == "SDRCSTMT"){									////////////////////// ------ FOR SD RECOVERY STATEMENT ------ //////////////////////
						TableStr += '<tr>';
						TableStr += '<th class="colhead">S.No</th>';
						TableStr += '<th class="colhead">CCNO.</th>';
						TableStr += '<th class="colhead">Work Order No.</th>';
						TableStr += '<th class="colhead">Name of Contractor</th>';
						TableStr += '<th class="colhead">Name of Work</th>';
						TableStr += '<th class="colhead">Engineer In Charge</th>';
						TableStr += '<th class="colhead">RAB NO.</th>';
						TableStr += '<th class="colhead">Date of Payment</th>';
						TableStr += '<th class="colhead">Gross Bill Amount</br>(&#x20b9;)</th>';
						TableStr += '<th class="colhead">SD Recovery Amount</br>(&#x20b9;)</th>';
						TableStr += '<th class="colhead" style="font-weight:bold;">Remarks</th>';
						TableStr += '</tr>';
						var TotalBillAmt = 0;
						var TotalSDAmt = 0;
						var StmtData 	= data['data'];	
						var CCnoData 	= data['ccnodata'];
						var WoNumData 	= data['wonumdata'];
						var ContNameData 	= data['contnamedata'];
						var EicNameData 	= data['eicnamedata'];
						var WorkNameData 	= data['worknamedata'];	//alert(JSON.stringify(CCnoData));
						$.each(StmtData, function(index, element) { 
							var RbnVal 		= element.rbn;
							var sheet_idVal = element.sheetid;
							var CCnoVal 	= CCnoData[sheet_idVal];
							var ContName 	= ContNameData[sheet_idVal];
							var WorkName 	= WorkNameData[sheet_idVal];
							var EicName 	= EicNameData[sheet_idVal];
							if(EicName == null){
								EicName = "";
							}else{
								EicName = EicName;
							}
							var WorkNumber	= WoNumData[sheet_idVal];
							var SDAmt		= element.sd_amt;
							SDAmt 			= Number(SDAmt).toFixed(2);
							var SDAmtMonForm = ConvertIndianRsFormat(SDAmt);
							var AbstNetAmt = element.abstract_net_amt;
							AbstNetAmt 		= Number(AbstNetAmt).toFixed(2);
							var AbstNetAmtMonForm = ConvertIndianRsFormat(AbstNetAmt);
							
							TotalBillAmt = Number(TotalBillAmt) + Number(AbstNetAmt);
							TotalSDAmt = Number(TotalSDAmt) + Number(SDAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter">'+Sno+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+CCnoVal+'</td>';
							TableStr += '<td class="labelleft" nowrap="nowrap">'+WorkNumber+'</td>';
							TableStr += '<td class="labelleft">'+ContName+'</td>';
							TableStr += '<td class="labelleft">'+WorkName+'</td>';
							TableStr += '<td class="labelleft">'+EicName+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+RbnVal+'</td>';
							TableStr += '<td class="labelcenter" nowrap>'+element.payment_dt+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+AbstNetAmtMonForm+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+SDAmtMonForm+'</td>';
							TableStr += '<td class="labelleft" nowrap="nowrap"></td>';
							TableStr += '</tr>';
							Sno++;
						});
						if(TotalBillAmt > 0){
							TotalBillAmt 	= Number(TotalBillAmt).toFixed(2);
							var TotalBillAmtStr = ConvertIndianRsFormat(TotalBillAmt);
							TotalSDAmt 	= Number(TotalSDAmt).toFixed(2);
							var TotalSDAmtStr = ConvertIndianRsFormat(TotalSDAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelright mod-totrow" colspan="9"><b> Total SD Recovery Amount (&#x20b9;)</b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b>'+TotalSDAmtStr+'</b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b></b></td>';
							TableStr += '</tr>';
						}else{
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter" colspan="11">No Records Found</td>';
							TableStr += '</tr>';
						}
					}else if(StmtCode == "PSDBRSH"){									////////////////////// ------ FOR PG SD BROAD SHEET ------ //////////////////////
						TableStr += '<tr>';
						TableStr += '<th class="colhead">S.No</th>';
						TableStr += '<th class="colhead">CCNO.</th>';
						TableStr += '<th class="colhead">Name of Contractor</th>';
						TableStr += '<th class="colhead">Name of Work</th>';
						TableStr += '<th class="colhead">Credit/Debit</br>Date</th>';
						TableStr += '<th class="colhead">Opening Balance</th>';
						TableStr += '<th class="colhead">Credit</th>';
						TableStr += '<th class="colhead">Debit</th>';
						//TableStr += '<th class="colhead">Total Of</th>';
						//TableStr += '<th class="colhead">PTA IN</th>';
						//TableStr += '<th class="colhead">PTA OUT</th>';
						TableStr += '<th class="colhead">Closing Balance</th>';
						TableStr += '<th class="colhead" style="font-weight:bold;">Remarks</th>';
						TableStr += '</tr>';
						var TotalDebAmt = 0;
						var TotalCreAmt = 0;
						var DebitAmt = 0;
						var CreditAmt = 0;
						var EchoDate = "";
						var StmtData = data['data'];	
						var CCnoData = data['ccnodata'];
						var ContNameData 	= data['contnamedata'];	
						var WorkNameData 	= data['worknamedata'];	//alert(JSON.stringify(CCnoData));
						$.each(StmtData, function(index, element) { 
							var globid 		= element.globid;
							var CCnoVal 	= CCnoData[globid];
							var ContName 	= ContNameData[globid];
							var WorkName 	= WorkNameData[globid];
							var CreatedDt	= element.createdon;
							var RelasedDt	= element.released_date;
							var InstStat	= element.inst_status;
							var InstAmt		= element.inst_amt;
								InstAmt 		= Number(InstAmt).toFixed(2);
							var InstAmtMonForm = ConvertIndianRsFormat(InstAmt);
							if(InstStat == 'R'){
								EchoDate = RelasedDt;
								DebitAmt 	= InstAmt;
								CreditAmt 	= "";
							var DebitAmtRound = Number(DebitAmt).toFixed(2);
							var DebitAmtRoundMonForm = ConvertIndianRsFormat(DebitAmtRound);
							var CreditAmtRoundMonForm = "";
							}else{
								EchoDate = CreatedDt;
								CreditAmt 	= InstAmt;
								DebitAmt 	= "";
							var CreditAmtRound = Number(CreditAmt).toFixed(2);
							var CreditAmtRoundMonForm = ConvertIndianRsFormat(CreditAmtRound);
							var DebitAmtRoundMonForm = "";
							}

							TotalDebAmt = Number(TotalDebAmt) + Number(DebitAmt);
							TotalCreAmt = Number(TotalCreAmt) + Number(CreditAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter">'+Sno+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+CCnoVal+'</td>';
							TableStr += '<td class="labelleft">'+ContName+'</td>';
							TableStr += '<td class="labelleft">'+WorkName+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+EchoDate+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap"> </td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+CreditAmtRoundMonForm+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+DebitAmtRoundMonForm+'</td>';
							//TableStr += '<td class="labelcenter" nowrap> </td>';
							//TableStr += '<td class="labelright" nowrap="nowrap"> </td>';
							//TableStr += '<td class="labelright" nowrap="nowrap"> </td>';
							TableStr += '<td class="labelright" nowrap="nowrap"> </td>';
							TableStr += '<td class="labelleft" nowrap="nowrap"></td>';
							TableStr += '</tr>';
							Sno++;
						});
						if(Sno > 1){
							TotalDebAmt = Number(TotalDebAmt).toFixed(2);
							var TotalDebAmtStr = ConvertIndianRsFormat(TotalDebAmt);
							TotalCreAmt = Number(TotalCreAmt).toFixed(2);
							var TotalCreAmtStr = ConvertIndianRsFormat(TotalCreAmt);
							if(TotalDebAmtStr == 0){
								TotalDebAmtStr = "";
							}
							if(TotalCreAmtStr == 0){
								TotalCreAmtStr = "";
							}
							TableStr += '<tr>';
							TableStr += '<td class="labelright mod-totrow" colspan="5"><b> Total (&#x20b9;)</b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b> </b></td>'; 						//// Total Opening Balance
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b>'+TotalCreAmtStr+'</b></td>'; 	//// Total Credit Amount
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b>'+TotalDebAmtStr+'</b></td>'; 	//// Total Debit Amount
							//TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b></b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b> </b></td>'; 						//// Total Closing Balance
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"></td>';
							TableStr += '</tr>';
						}else{
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter" colspan="11">No Records Found</td>';
							TableStr += '</tr>';
						}
					}else if(StmtCode == "SDBRSH"){									////////////////////// ------ FOR SD BROAD SHEET ------ //////////////////////    
						TableStr += '<tr>';
						TableStr += '<th class="colhead">S.No</th>';
						TableStr += '<th class="colhead">CCNo.</th>';
						TableStr += '<th class="colhead">Work Order No.</th>';
						TableStr += '<th class="colhead">Name of Contractor</th>';
						TableStr += '<th class="colhead">Name of Work</th>';
						TableStr += '<th class="colhead">Opening Balance</th>';
						TableStr += '<th class="colhead">Credit</th>';
						TableStr += '<th class="colhead">Debit</th>';
						//TableStr += '<th class="colhead">Total Of</th>';
						//TableStr += '<th class="colhead">PTA IN</th>';
						//TableStr += '<th class="colhead">PTA OUT</th>';
						TableStr += '<th class="colhead">Closing Balance</th>';
						TableStr += '<th class="colhead" style="font-weight:bold;">Remarks</th>';
						TableStr += '</tr>';
						var TotalBillAmt = 0;
						var TotalSDAmt = 0;
						var StmtData 	= data['data'];	
						var CCnoData 	= data['ccnodata'];
						var WoNumData 	= data['wonumdata'];
						var ContNameData 	= data['contnamedata'];	
						var WorkNameData 	= data['worknamedata'];	//alert(JSON.stringify(CCnoData));
						$.each(StmtData, function(index, element) { 
							var RbnVal 		= element.rbn;
							var sheet_idVal = element.sheetid;
							var CCnoVal 	= CCnoData[sheet_idVal];
							var ContName 	= ContNameData[sheet_idVal];
							var WoNumber 	= WoNumData[sheet_idVal];
							var WorkName 	= WorkNameData[sheet_idVal];
							var SDAmt		= element.sd_amt;
							SDAmt 			= Number(SDAmt).toFixed(2);
							var SDAmtMonForm = ConvertIndianRsFormat(SDAmt);
							var AbstNetAmt = element.abstract_net_amt;
							AbstNetAmt 		= Number(AbstNetAmt).toFixed(2);
							var AbstNetAmtMonForm = ConvertIndianRsFormat(AbstNetAmt);
							
							TotalBillAmt = Number(TotalBillAmt) + Number(AbstNetAmt);
							TotalSDAmt = Number(TotalSDAmt) + Number(SDAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter">'+Sno+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+CCnoVal+'</td>';
							TableStr += '<td class="labelleft" nowrap="nowrap">'+WoNumber+'</td>';
							TableStr += '<td class="labelleft">'+ContName+'</td>';
							TableStr += '<td class="labelleft">'+WorkName+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap"> </td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+SDAmtMonForm+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap"> </td>';
							//TableStr += '<td class="labelcenter" nowrap> </td>';
							//TableStr += '<td class="labelright" nowrap="nowrap"> </td>';
							//TableStr += '<td class="labelright" nowrap="nowrap"> </td>';
							TableStr += '<td class="labelright" nowrap="nowrap"> </td>';
							TableStr += '<td class="labelleft" nowrap="nowrap"></td>';
							TableStr += '</tr>';
							Sno++;
						});
						if(Sno > 1){
							TotalSDAmt 	= Number(TotalSDAmt).toFixed(2);
							var TotalSDAmtStr = ConvertIndianRsFormat(TotalSDAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelright mod-totrow" colspan="5"><b> Total (&#x20b9;)</b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b> </b></td>'; 						//// Total Opening Balance
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b>'+TotalSDAmtStr+'</b></td>'; 	//// Total Credit Amount
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b> </b></td>'; 						//// Total Debit Amount
							// TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b></b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b> </b></td>'; 						//// Total Closing Balance
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"></td>';
							TableStr += '</tr>';
						}else{
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter" colspan="9">No Records Found</td>';
							TableStr += '</tr>';
						}
					}else if(StmtCode == "VOUCH"){									////////////////////// ------ FOR VOUCHER EXPENDITURE ------ //////////////////////
						TableStr += '<tr>';
						TableStr += '<th class="colhead">S.No</th>';
						TableStr += '<th class="colhead">Voucher No.</th>';
						TableStr += '<th class="colhead">Voucher Date</th>';
						TableStr += '<th class="colhead">Description of the Work</th>';
						TableStr += '<th class="colhead">Name of Contractor</th>';
						TableStr += '<th class="colhead">Voucher Amount</br>(&#x20b9; in Lakhs)</th>';
						TableStr += '<th class="colhead">CCNo.</th>';
						TableStr += '<th class="colhead">HOA</th>';
						TableStr += '</tr>';
						var TotalVouchAmt = 0;
						var StmtData = data['data'];		//alert(JSON.stringify(StmtData));
						$.each(StmtData, function(index, element) { 
							var VouchNum 		= element.vr_no;
							var VouchDate 		= element.vr_dt;
							var WorkName 		= element.item; 
							var ContName 		= element.indentor;
							var VouchAmt		= element.vr_amt;
							var VouchCCno		= element.ccno;
							var VouchHoa		= element.new_hoa;

							VouchAmt 			= Number(VouchAmt).toFixed(2);
							var VouchAmtMonForm = ConvertIndianRsFormat(VouchAmt);
							
							TotalVouchAmt = Number(TotalVouchAmt) + Number(VouchAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter">'+Sno+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+VouchNum+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+VouchDate+'</td>';
							TableStr += '<td class="labelleft">'+WorkName+'</td>';
							TableStr += '<td class="labelleft">'+ContName+'</td>';
							TableStr += '<td class="labelright" nowrap="nowrap">'+VouchAmtMonForm+'</td>';
							TableStr += '<td class="labelcenter" nowrap="nowrap">'+VouchCCno+'</td>';
							TableStr += '<td class="labelleft" nowrap="nowrap">'+VouchHoa+'</td>';
							TableStr += '</tr>';
							Sno++;
						});
						if(Sno > 1){
							TotalVouchAmt 	= Number(TotalVouchAmt).toFixed(2);
							var TotalVouchAmtStr = ConvertIndianRsFormat(TotalVouchAmt);
							TableStr += '<tr>';
							TableStr += '<td class="labelright mod-totrow" colspan="5"><b> Total (&#x20b9;)</b></td>';
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap"><b>'+TotalVouchAmtStr+'</b></td>'; 	//// Total Credit Amount
							TableStr += '<td class="labelright mod-totrow" nowrap="nowrap" colspan="2"><b></b></td>';
							TableStr += '</tr>';
						}else{
							TableStr += '<tr>';
							TableStr += '<td class="labelcenter" colspan="8">No Records Found</td>';
							TableStr += '</tr>';
						}
					}
										
					
					$("#StmtTable tr:gt(0)").remove();
					$("#StmtTable tr:last").after(TableStr);
				}
			}
		});
	}
});	
</script>
 
<style>
.bootstrap-dialog .bootstrap-dialog-title{
	font-size:12px;
}
.modal-header {
  	min-height: 16.43px;
  	padding: 8px 15px 8px 15px;
	border-bottom: 1px solid #e5e5e5;
}
.modal-body{
	padding: 8px 15px 15px 15px;
}
.bootstrap-dialog .bootstrap-dialog-title, .modal-body{
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
.fchart div{
	box-sizing: border-box !important;
}
</style>