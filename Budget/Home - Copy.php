<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName 	= $PTPart1.$PTIcon.'Home';
$msg 		= ""; $del = 0;
$RowCount 	= 0;
$staffid 	= $_SESSION['sid'];
$UnconfirmSor = 0; $UnconfirmRate = 0;
/*$SelectDatasheetQuery = "select count(ref_id) as UnconfirmSor from datasheet_master where (ds_release = '' OR ds_release IS NULL)";
$SelectDatasheetSql   = mysqli_query($dbConn,$SelectDatasheetQuery);
if($SelectDatasheetSql == true){
	if(mysqli_num_rows($SelectDatasheetSql)>0){
		$UCSList = mysqli_fetch_object($SelectDatasheetSql);
		$UnconfirmSor = $UCSList->UnconfirmSor;
	}
}
$SelectItemRateQuery = "select count(item_id) as UnconfirmRate from item_master_temp where is_edited = 'Y'";
$SelectItemRateSql   = mysqli_query($dbConn,$SelectItemRateQuery);
if($SelectItemRateSql == true){
	if(mysqli_num_rows($SelectItemRateSql)>0){
		$UCIList = mysqli_fetch_object($SelectItemRateSql);
		$UnconfirmRate = $UCIList->UnconfirmRate;
	}
}*/
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
.box-container .card .face-static {
	border-radius:10px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
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
							<div class="box-container box-container-lg">
								<div class="div8">
									<div class="div6">
										<div class="card">
											<div class="face-static" style="height:130px;">
												<div class="card-header seagreen-card ft12">Physical & Financial Progress - 2013 to upto date <span id="CourseChartDuration">in Crore</span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<!--<div class="padd2" style="font-size:11px; text-align:left; padding-left:15px;">
														<div class="div3">
															<div class="drounded">&#8377;</div>
														</div>
														<div class="div9">
															<div>Project Sanctioned Amount</div>
															<div>Total Action Taken</div>
															<div>Total Committed amount</div>
															<div>Actual Expenditure up to date</div>
														</div>
													</div>-->  
													<div class="div8 dcardbox">
														<div class="dcardlabel">Project Sanctioned Amount - <span class="sm-badge ft11">6000</span></div>
														<div class="dcardlabel">Total Action Taken - <span class="sm-badge ft11">3000</span></div>
														<div class="dcardlabel">Total Committed amount - <span class="sm-badge ft11">2500</span></div>
														<div class="dcardlabel">Actual Expenditure up to date - <span class="sm-badge ft11">2400</span></div>
													</div>
													<div class="div4 tooltip-new">
														<div class="tooltiptext" style="width:200px">
															<table class='table-bordered'>
																<tr><td>TEST 1</td></tr>
																<tr><td>TEST 2</td></tr>
																<tr><td>TEST 3</td></tr>
																<tr><td>TEST 4</td></tr>
															</table>
														</div>
														<div id="GaugeChart" class="padd2" style="height:100px;"></div>   
													</div>   
												</div>
											</div>
										</div>
									</div>
									<div class="div6">
										<div class="card">
											<div class="face-static" style="height:130px;">
												<div class="card-header seagreen-card ft12">MEP and actual expenditure variation - FY 2022-23 <span id="CourseChartDuration">in Crore</span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="padd2 dcardbox">
														<div class="div8 dcardbox">
															<div class="dcardlabel">Approved BE/RE - <span class="sm-badge ft11">600</span></div>
															<div class="dcardlabel">Committed Expenditure (A) - <span class="sm-badge ft11">700</span></div>
															<div class="dcardlabel">Actual Expenditure (B) - <span class="sm-badge ft11">100</span></div>
															<div class="dcardlabel">Variation between (A) and (B) - <span class="sm-badge ft11">500</span></div>
														</div>
														<div class="div4">
															<div id="LolipopChart" class="padd2" style="height:100px;"></div>   
														</div> 
													</div>                             
												</div>
											</div>
										</div>
									</div>
									<div class="div6">
										<div class="card">
											<div class="face-static">
												<div class="card-header seagreen-card ft12">Total Expenditure 2013 to upto date (Year wise) <span id="CourseChartDuration">in Crore</span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div id="DonutChart" class="padd2" style="height:320px;"></div>                                
												</div>
											</div>
										</div>
									</div>
									<div class="div6">
										<div class="card">
											<div class="face-static">
												<div class="card-header seagreen-card ft12">Object Head wise Expenditure - 2013 to upto date <span id="CourseChartDuration">in Crore</span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class='buttons'>
													  <button id='2000' type="button">
														2000
													  </button>
													  <button id='2004' type="button">
														2004
													  </button>
													  <button id='2008' type="button">
														2008
													  </button>
													  <button id='2012' type="button">
														2012
													  </button>
													  <button id='2016' type="button">
														2016
													  </button>
													  <button id='2020' class='active' type="button">
														2020
													  </button>
													</div>
													<div id="HorizBarChart" class="padd2" style="height:300px;"></div>                                
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="div4">
									<div class="div12">
										<div class="card">
											<div class="face-static" style="height:130px">
												<div class="card-header seagreen-card ft12">Today's Expenditure (<?php echo date("d/m/Y"); ?>) <span id="CourseChartDuration">in Crore</span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<!--<div class="padd2" style="font-size:11px; text-align:left; padding-left:15px;">
														<div>All Major Works</div>
														<div>Miscellaneous</div>
														<div>Total Expenditure</div>
													</div>  -->   
													<div id="MinHorizBarChart" class="padd2" style="height:100px"></div>   
												</div>
											</div>
										</div>
									</div>
									<div class="div12">
										<div class="card">
											<div class="face-static" style="height:352px">
												<div class="card-header seagreen-card ft12">All Major Works Expenditiure - 2013 to upto date <span id="CourseChartDuration">in Crore</span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div id="DrillDownBarChart" class="padd2" style="height:320px;"></div>                                 
												</div>
											</div>
										</div>
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
	$('.notBtn').click(function(event){ 
		var PageUrl = $(this).attr("data-url");
  		$(location).attr("href",PageUrl+".php");
		event.preventDefault();
		return false;
  	});
});
</script>
<script src="dashboard/lib/amcharts.js"></script>
<script src="dashboard/lib/serial.js"></script>
<script src="dashboard/lib/pie.js"></script>

<script src="dashboard/lib/index.js"></script>
<script src="dashboard/lib/percent.js"></script>
<script src="dashboard/lib/xy.js"></script>
<script src="dashboard/lib/radar.js"></script>
<script src="dashboard/lib/themes/Animated.js"></script>

<script src="dashboard/Highchart/highcharts.js"></script>
<script src="dashboard/Highchart/modules/data.js"></script>
<script src="dashboard/Highchart/modules/drilldown.js"></script>
<script src="dashboard/Highchart/highcharts-more.js"></script>
<script src="dashboard/Highchart/modules/dumbbell.js"></script>
<script src="dashboard/Highchart/modules/lollipop.js"></script>
<script src="dashboard/Highchart/modules/exporting.js"></script>
<script src="dashboard/Highchart/modules/export-data.js"></script>
<script src="dashboard/Highchart/modules/accessibility.js"></script>
<script src="dashboard/Highchart/highcharts-3d.js"></script>

<script>
function GaugeChart(){
	/**
 * ---------------------------------------
 * This demo was created using amCharts 5.
 * 
 * For more information visit:
 * https://www.amcharts.com/
 * 
 * Documentation is available at:
 * https://www.amcharts.com/docs/v5/
 * ---------------------------------------
 */

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("GaugeChart");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);
root._logo.dispose();
// Create chart
// https://www.amcharts.com/docs/v5/charts/radar-chart/
var chart = root.container.children.push(am5radar.RadarChart.new(root, {
  panX: false,
  panY: false,
  wheelX: "panX",
  wheelY: "zoomX",
  innerRadius: am5.percent(20),
  startAngle: -90,
  endAngle: 180,
  paddingTop: -5,
  paddingBottom: 0
}));


// Data
var data = [{
  category: "Research",
  value: 80,
  full: 100,
  columnSettings: {
    fill: chart.get("colors").getIndex(0)
  }
}, {
  category: "Marketing",
  value: 35,
  full: 100,
  columnSettings: {
    fill: chart.get("colors").getIndex(1)
  }
}, {
  category: "Distribution",
  value: 92,
  full: 100,
  columnSettings: {
    fill: chart.get("colors").getIndex(2)
  }
}, {
  category: "Human Resources",
  value: 68,
  full: 100,
  columnSettings: {
    fill: chart.get("colors").getIndex(3)
  }
}];

// Add cursor
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Cursor
var cursor = chart.set("cursor", am5radar.RadarCursor.new(root, {
  behavior: "zoomX"
}));

cursor.lineY.set("visible", false);

// Create axes and their renderers
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_axes
var xRenderer = am5radar.AxisRendererCircular.new(root, {
  //minGridDistance: 50
});

xRenderer.labels.template.setAll({
  radius: 10,
   fontSize: 0,
   forceHidden: true
});

xRenderer.grid.template.setAll({
  forceHidden: true
});

var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
  renderer: xRenderer,
  min: 0,
  max: 100,
  strictMinMax: true,
  numberFormat: "#'%'",
  tooltip: ''
}));


var yRenderer = am5radar.AxisRendererRadial.new(root, {
  minGridDistance: 20
});

yRenderer.labels.template.setAll({
  centerX: am5.p100,
  fontWeight: "500",
  fontSize: 8,
  templateField: "columnSettings"
});

yRenderer.grid.template.setAll({
  forceHidden: true
});

var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
  categoryField: "category",
  renderer: yRenderer
}));

yAxis.data.setAll(data);


// Create series
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_series
var series1 = chart.series.push(am5radar.RadarColumnSeries.new(root, {
  xAxis: xAxis,
  yAxis: yAxis,
  clustered: false,
  valueXField: "full",
  categoryYField: "category",
  fill: root.interfaceColors.get("alternativeBackground")
}));

series1.columns.template.setAll({
  width: am5.p100,
  fillOpacity: 0.08,
  strokeOpacity: 0,
  cornerRadius: 20
});

series1.data.setAll(data);


var series2 = chart.series.push(am5radar.RadarColumnSeries.new(root, {
  xAxis: xAxis,
  yAxis: yAxis,
  clustered: false,
  valueXField: "value",
  categoryYField: "category"
}));

series2.columns.template.setAll({
  strokeOpacity: 0,
  tooltipText: "{category}:{valueX}%",
  fontSize : 2,
  cornerRadius: 20,
  templateField: "columnSettings"
});
series2.data.setAll(data);

// Animate chart and series in
// https://www.amcharts.com/docs/v5/concepts/animations/#Initial_animation
series1.appear(1000);
series2.appear(1000);
chart.appear(1000, 100);
if(chart.logo){ chart.logo.disabled = true;}
}
GaugeChart();




function DonutChart(){
	var chart = AmCharts.makeChart( "DonutChart", {
		"type": "pie",
		"theme": "light",
		"dataProvider":[ {
		"country": "2013",
		"visits": 213
	  }, {
		"country": "2014",
		"visits": 225
	  }, {
		"country": "2015",
		"visits": 260
	  }, {
		"country": "2016",
		"visits": 290
	  }, {
		"country": "2017",
		"visits": 310
	  }, {
		"country": "2018",
		"visits": 320
	  }, {
		"country": "2019",
		"visits": 286
	  }, {
		"country": "2020",
		"visits": 400
	  }, {
		"country": "2021",
		"visits": 410
	  }, {
		"country": "2022",
		"visits": 300
	  }, {
		"country": "Balance",
		"visits": 1200
	  } ],
		"titleField": "country",
		"valueField": "visits",
		"startEffect": "elastic",
		"startDuration": 2,
		"labelRadius": 5,
		"innerRadius": "50%",
		"depth3D": 10,
		"fontSize": 9,
		"color": "#00008B",
		"balloonText": "<b><span style='font-size:11px'>[[title]]</font></b><br><span style='font-size:11px'><b> Rs. [[value]] Crores</b></span>",
		"labelText": "[[title]]",
		"export": {
			"enabled": true
		}
	  /*"type": "pie",
	  "theme": "none",
	  "titles": [ {
		"text": "Visitors countries",
		"size": 16
	  } ],
	  "dataProvider": [ {
		"country": "Machinery & Eqpt.",
		"visits": 7252
	  }, {
		"country": "Prof. Services",
		"visits": 3882
	  }, {
		"country": "D.Travel Expenses",
		"visits": 1809
	  }, {
		"country": "Office Expenses",
		"visits": 1322
	  }, {
		"country": "United Kingdom",
		"visits": 1122
	  }, {
		"country": "France",
		"visits": 414
	  }, {
		"country": "India",
		"visits": 384
	  }, {
		"country": "Spain",
		"visits": 211
	  } ],
	  "valueField": "visits",
	  "titleField": "country",
	  "startEffect": "elastic",
	  "startDuration": 2,
	  "labelRadius": 15,
	  "innerRadius": "50%",
	  "depth3D": 10,
	  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
	  "angle": 15,
	  "export": {
		"enabled": true
	  }*/
	});
}
DonutChart();
function HorizontalBarChart(){
	const dataPrev = {
    2020: [
        ['Salaries', 9],
        ['D.Travel Expenses', 12],
        ['F.Travel Expenses', 8],
        ['Office Expenses', 17],
        ['Supplies & Mat.', 19],
        ['Prof. Services', 26],
        ['Motor Vehicles', 27],
        ['Machinery & Eqpt.', 46]
    ],
    2016: [
        ['Salaries', 13],
        ['D.Travel Expenses', 7],
        ['F.Travel Expenses', 8],
        ['Office Expenses', 11],
        ['Supplies & Mat.', 20],
        ['Prof. Services', 38],
        ['Motor Vehicles', 29],
        ['Machinery & Eqpt.', 47]
    ],
    2012: [
        ['Salaries', 13],
        ['D.Travel Expenses', 9],
        ['F.Travel Expenses', 14],
        ['Office Expenses', 16],
        ['Supplies & Mat.', 24],
        ['Prof. Services', 48],
        ['Motor Vehicles', 19],
        ['Machinery & Eqpt.', 36]
    ],
    2008: [
        ['Salaries', 9],
        ['D.Travel Expenses', 17],
        ['F.Travel Expenses', 18],
        ['Office Expenses', 13],
        ['Supplies & Mat.', 29],
        ['Prof. Services', 33],
        ['Motor Vehicles', 9],
        ['Machinery & Eqpt.', 37]
    ],
    2004: [
        ['Salaries', 8],
        ['D.Travel Expenses', 5],
        ['F.Travel Expenses', 16],
        ['Office Expenses', 13],
        ['Supplies & Mat.', 32],
        ['Prof. Services', 28],
        ['Motor Vehicles', 11],
        ['Machinery & Eqpt.', 37]
    ],
    2000: [
        ['Salaries', 7],
        ['D.Travel Expenses', 3],
        ['F.Travel Expenses', 9],
        ['Office Expenses', 20],
        ['Supplies & Mat.', 26],
        ['Prof. Services', 16],
        ['Motor Vehicles', 1],
        ['Machinery & Eqpt.', 44]
    ]
};

const data = {
    2020: [
        ['Salaries', 6],
        ['D.Travel Expenses', 27],
        ['F.Travel Expenses', 17],
        ['Office Expenses', 10],
        ['Supplies & Mat.', 20],
        ['Prof. Services', 38],
        ['Motor Vehicles', 22],
        ['Machinery & Eqpt.', 39]
    ],
    2016: [
        ['Salaries', 9],
        ['D.Travel Expenses', 12],
        ['F.Travel Expenses', 8],
        ['Office Expenses', 17],
        ['Supplies & Mat.', 19],
        ['Prof. Services', 26],
        ['Motor Vehicles', 27],
        ['Machinery & Eqpt.', 46]
    ],
    2012: [
        ['Salaries', 13],
        ['D.Travel Expenses', 7],
        ['F.Travel Expenses', 8],
        ['Office Expenses', 11],
        ['Supplies & Mat.', 20],
        ['Prof. Services', 38],
        ['Motor Vehicles', 29],
        ['Machinery & Eqpt.', 47]
    ],
    2008: [
        ['Salaries', 13],
        ['D.Travel Expenses', 9],
        ['F.Travel Expenses', 14],
        ['Office Expenses', 16],
        ['Supplies & Mat.', 24],
        ['Prof. Services', 48],
        ['Motor Vehicles', 19],
        ['Machinery & Eqpt.', 36]
    ],
    2004: [
        ['Salaries', 9],
        ['D.Travel Expenses', 17],
        ['F.Travel Expenses', 18],
        ['Office Expenses', 13],
        ['Supplies & Mat.', 29],
        ['Prof. Services', 33],
        ['Motor Vehicles', 9],
        ['Machinery & Eqpt.', 37]
    ],
    2000: [
        ['Salaries', 8],
        ['D.Travel Expenses', 5],
        ['F.Travel Expenses', 16],
        ['Office Expenses', 13],
        ['Supplies & Mat.', 32],
        ['Prof. Services', 28],
        ['Motor Vehicles', 11],
        ['Machinery & Eqpt.', 37]
    ]
};

const countries = [{
    name: 'Salaries',
    flag: 'SAL',
    color: 'rgb(201, 36, 39)'
}, {
    name: 'D.Travel Expenses',
    flag: 'DTR',
    color: 'rgb(201, 36, 39)'
}, {
    name: 'F.Travel Expenses',
    flag: 'FTR',
    color: 'rgb(0, 82, 180)'
}, {
    name: 'Office Expenses',
    flag: 'OFE',
    color: 'rgb(0, 0, 0)'
}, {
    name: 'Supplies & Mat.',
    flag: 'S&M',
    color: 'rgb(240, 240, 240)'
}, {
    name: 'Prof. Services',
    flag: 'PRS',
    color: 'rgb(255, 217, 68)'
}, {
    name: 'Motor Vehicles',
    flag: 'MOV',
    color: 'rgb(0, 82, 180)'
}, {
    name: 'Machinery & Eqpt.',
    flag: 'MHE',
    color: 'rgb(215, 0, 38)'
}];


const getData = data => data.map((country, i) => ({
    name: country[0],
    y: country[1],
    color: countries[i].color
}));

const chart = Highcharts.chart('HorizBarChart', {
    chart: {
        type: 'column'
    },
    title: {
        text: '',
        align: 'left'
    },
    subtitle: {
        text: ' ',
        align: 'left'
    },
    plotOptions: {
        series: {
            grouping: false,
            borderWidth: 0
        }
    },
    legend: {
        enabled: false
    },
	credits: {
		enabled: false
	},
    tooltip: {
        shared: true,
        headerFormat: '<span style="font-size: 15px">{point.point.name}</span><br/>',
        pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} Crores</b><br/>'
    },
    xAxis: {
        type: 'category',
        accessibility: {
            description: 'Countries'
        },
        max: 7,
        labels: {
            useHTML: true,
            animate: true,
            formatter: ctx => {
                let flag;

                countries.forEach(function (country) {
                    if (country.name === ctx.value) {
                        flag = country.flag;
                    }
                });

                return `${flag.toUpperCase()}<br><span class="f32">
                    <span class="flag ${flag}"></span>
                </span>`;
            },
            style: {
                textAlign: 'center'
            }
        }
    },
    yAxis: [{
        title: {
            text: 'Expenses (in Crores)'
        },
        showFirstLabel: false
    }],
    series: [{
        color: 'rgba(158, 159, 163, 0.5)',
        pointPlacement: -0.2,
        linkedTo: 'main',
        data: dataPrev[2020].slice(),
        name: '2016'
    }, {
        name: '2020',
        id: 'main',
        dataSorting: {
            enabled: true,
            matchByName: true
        },
        dataLabels: [{
            enabled: true,
            inside: true,
            style: {
                fontSize: '16px'
            }
        }],
        data: getData(data[2020]).slice()
    }],
    exporting: {
        allowHTML: true
    }
});

const locations = [
    {
        city: 'Tokyo',
        year: 2020
    }, {
        city: 'Rio',
        year: 2016
    }, {
        city: 'London',
        year: 2012
    }, {
        city: 'Beijing',
        year: 2008
    }, {
        city: 'Athens',
        year: 2004
    }, {
        city: 'Sydney',
        year: 2000
    }
];

locations.forEach(location => {
    const btn = document.getElementById(location.year);

    btn.addEventListener('click', () => {

        document.querySelectorAll('.buttons button.active')
            .forEach(active => {
                active.className = '';
            });
        btn.className = 'active';

        chart.update({
            title: {
                text: ' '
            },
            subtitle: {
                text: 'Comparing to '
            },
            series: [{
                name: location.year - 4,
                data: dataPrev[location.year].slice()
            }, {
                name: location.year,
                data: getData(data[location.year]).slice()
            }]
        }, true, false, {
            duration: 800
        });
    });
});
}
HorizontalBarChart();


function MinHorizontalBarChart(){
	
	Highcharts.chart('MinHorizBarChart', {
		chart: {
			type: 'bar'
		},
		title: {
			text: '',
			align: 'left'
		},
		subtitle: {
			text: '',
			align: 'left'
		},
		xAxis: {
			categories: ['All Major Works', 'Miscelleneous', 'Total'],
			title: {
				text: null
			}
		},
		yAxis: {
			min: 0,
			title: {
				text: '',
				align: 'high'
			},
			labels: {
				 enabled: false
			}
		},
		tooltip: {
			valueSuffix: ' Lakhs'
		},
		plotOptions: {
			bar: {
				dataLabels: {
					enabled: true
				}
			},
			series: {
				pointPadding: 0.2
			}
		},
		legend: {
			enabled: false
		},
		credits: {
			enabled: false
		},
		exporting: {
         enabled: false
		},
		 series: [{
			name: 'Expenditures',
			/*data: [500, 520, 530]*/
			data: [
				{
					name: 'All Major Works',
					y: 230,
					color:'#06E5A5'
				},
				{
					name: 'Miscelleneous',
					y: 240,
					color:'#069BE5'
				},
				{
					name: 'Total',
					y: 300,
					color:'#F04879'
				}
			]
		}]
	});

	/*// Create root element
	// https://www.amcharts.com/docs/v5/getting-started/#Root_element
	var root = am5.Root.new("MinHorizBarChart");
	
	
	// Set themes
	// https://www.amcharts.com/docs/v5/concepts/themes/
	root.setThemes([am5themes_Animated.new(root)]);
	
	
	// Create chart
	// https://www.amcharts.com/docs/v5/charts/xy-chart/
	var chart = root.container.children.push(
	  am5xy.XYChart.new(root, {
		panX: false,
		panY: false,
		wheelX: "none",
		wheelY: "none"
	  })
	);
	
	
	// Create axes
	// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
	var yRenderer = am5xy.AxisRendererY.new(root, { minGridDistance: 30 });
	
	var yAxis = chart.yAxes.push(
	  am5xy.CategoryAxis.new(root, {
		maxDeviation: 0,
		categoryField: "country",
		renderer: yRenderer
	  })
	);
	
	var xAxis = chart.xAxes.push(
	  am5xy.ValueAxis.new(root, {
		maxDeviation: 0,
		min: 0,
		renderer: am5xy.AxisRendererX.new(root, {})
	  })
	);
	
	
	// Create series
	// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
	var series = chart.series.push(
	  am5xy.ColumnSeries.new(root, {
		name: "Series 1",
		xAxis: xAxis,
		yAxis: yAxis,
		valueXField: "value",
		sequencedInterpolation: true,
		categoryYField: "country"
	  })
	);
	
	
	
	var columnTemplate = series.columns.template;
	
	columnTemplate.setAll({
	  draggable: false,
	  cursorOverStyle: "pointer",
	  cornerRadiusBR: 10,
	  cornerRadiusTR: 10,
	   height: 5
	});
	columnTemplate.adapters.add("fill", (fill, target) => {
	  return chart.get("colors").getIndex(series.columns.indexOf(target));
	});
	
	columnTemplate.adapters.add("stroke", (stroke, target) => {
	  return chart.get("colors").getIndex(series.columns.indexOf(target));
	});
	
	columnTemplate.events.on("dragstop", () => {
	  sortCategoryAxis();
	});
	
	
	
	// Get series item by category
	function getSeriesItem(category) {
	  for (var i = 0; i < series.dataItems.length; i++) {
		var dataItem = series.dataItems[i];
		if (dataItem.get("categoryY") == category) {
		  return dataItem;
		}
	  }
	}
	
	
	// Axis sorting
	function sortCategoryAxis() {}
	
	// Set data
	var data = [{
	  country: "USA",
	  value: 7
	}, {
	  country: "Prof. Services",
	  value: 8
	}, {
	  country: "D.Travel Expenses",
	  value: 9
	}];
	
	yAxis.data.setAll(data);
	series.data.setAll(data);
	
	// Make stuff animate on load
	// https://www.amcharts.com/docs/v5/concepts/animations/
	series.appear(1000);
	chart.appear(1000, 100);*/
	
	
}
MinHorizontalBarChart();


function DrilldownColumnChart(){
	Highcharts.chart('DrillDownBarChart', {
		chart: {
			type: 'column',
			options3d: {
				enabled: true,
				alpha: 7,
				beta: 8,
				depth: 50,
				viewDistance: 25
			}
			
		},
		title: {
			align: 'left',
			text: ''
		},
		subtitle: {
			align: 'left',
			text: 'Click the columns to view years. '
		},
		accessibility: {
			announceNewData: {
				enabled: true
			}
		},
		xAxis: {
			type: 'category'
		},
		yAxis: {
			title: {
				text: 'Total Expenses'
			}
	
		},
		legend: {
			enabled: false
		},
		credits: {
			enabled: false
		},
		plotOptions: {
			series: {
				borderWidth: 0,
				dataLabels: {
					enabled: true,
					format: '{point.y:.2f}'
				}
			}
		},
	
		tooltip: {
			headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
			pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f} Crores</b><br/>'
		},
	
		series: [
			{
				name: 'Discipline',
				colorByPoint: true,
				data: [
					{
						name: 'Civil',
						y: 3.06,
						color:'#079ED5',
						drilldown: 'Civil'
					},
					{
						name: 'Electrical',
						y: 2.84,
						color:'#674BA0',
						drilldown: 'Electrical'
					},
					{
						name: 'Mechanical',
						y: 4.18,
						color:'#EA4679',
						drilldown: 'Mechanical'
					},
					{
						name: 'MHE',
						y: 4.12,
						color:'#F54040',
						drilldown: 'MHE'
					},
					{
						name: 'ACV',
						y: 2.33,
						color:'#F16B24',
						drilldown: 'ACV'
					},
					{
						name: 'I&C',
						y: 0.45,
						color:'#DC9E04',
						drilldown: 'I&C'
					},
					{
						name: 'Miscelleneous',
						y: 1.582,
						color:'#1AC498',
						drilldown: null
					}
				]
			}
		],
		drilldown: {
			breadcrumbs: {
				position: {
					align: 'right'
				}
			},
			series: [
				{
					name: 'Civil',
					id: 'Civil',
					data: [
						[
							'2013',
							0.1
						],
						[
							'2014',
							1.3
						],
						[
							'2015',
							53.02
						],
						[
							'2016',
							1.4
						],
						[
							'2017',
							0.88
						],
						[
							'2018',
							0.56
						],
						[
							'2019',
							0.45
						],
						[
							'2020',
							0.49
						],
						[
							'2021',
							0.32
						],
						[
							'2022',
							0.29
						]
					]
				},
				{
					name: 'Electrical',
					id: 'Electrical',
					data: [
						[
							'2013',
							1.02
						],
						[
							'2014',
							7.36
						],
						[
							'2015',
							0.35
						],
						[
							'2016',
							0.11
						],
						[
							'2017',
							0.1
						],
						[
							'2018',
							0.95
						],
						[
							'2019',
							0.15
						],
						[
							'2020',
							0.1
						],
						[
							'2021',
							0.31
						],
						[
							'2022',
							0.12
						]
					]
				},
				{
					name: 'Mechanical',
					id: 'Mechanical',
					data: [
						[
							'2019',
							6.2
						],
						[
							'2020',
							0.29
						],
						[
							'2021',
							0.27
						],
						[
							'2022',
							0.47
						]
					]
				},
				{
					name: 'MHE',
					id: 'MHE',
					data: [
						[
							'2017',
							3.39
						],
						[
							'2018',
							0.96
						],
						[
							'2019',
							0.36
						],
						[
							'2020',
							0.54
						],
						[
							'2021',
							0.13
						],
						[
							'2022',
							0.2
						]
					]
				},
				{
					name: 'ACV',
					id: 'ACV',
					data: [
						[
							'2019',
							2.6
						],
						[
							'2020',
							0.92
						],
						[
							'2021',
							0.4
						],
						[
							'2022',
							0.1
						]
					]
				},
				{
					name: 'I&C',
					id: 'I&C',
					data: [
						[
							'2020',
							0.96
						],
						[
							'2021',
							0.82
						],
						[
							'2022',
							0.14
						]
					]
				}
			]
		}
	});
}
DrilldownColumnChart();

function LolipopChart(){
	// Data retrieved from https://worldpopulationreview.com/countries
	Highcharts.chart('LolipopChart', {
	
		chart: {
			type: 'lollipop'
		},
	
		accessibility: {
			point: {
				valueDescriptionFormat: '{index}. {xDescription}, {point.y}.'
			}
		},
	
		legend: {
			enabled: false
		},
	
		subtitle: {
			text: ''
		},
	
		title: {
			text: ''
		},
	
		tooltip: {
			shared: true
		},
	
		xAxis: {
			type: '',
			title: {
				text: ''
			},
			labels: {
				enabled: false
			}
		},
	
		yAxis: {
			title: {
				text: ''
			},
			labels: {
				enabled: false
			}
		},
		credits: {
			enabled: false
		},
		exporting: {
         enabled: false
		},
	
		series: [{
			name: 'Rs. ',
			data: [{
				name: 'Approved BE/RE',
				y: 12,
				color:'#DC0443'
			}, {
				name: 'Committed Expenditure',
				y: 13,
				color:'#04C617'
			}, {
				name: 'Actual Expenditure',
				y: 14,
				color:'#046AC6'
			}, {
				name: 'Variations',
				y: 15,
				color:'#8E04C6'
			}]
		}]
	
	});

}
LolipopChart();
</script>
<style>
</style>

