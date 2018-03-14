<?php 
$logPath = "/var/speedlog.txt";
$logopen = fopen($logPath, "r") or die("Die Statistikdaten konnten nicht geladen werden!");
$log = fread($logopen,filesize($logPath));

fclose($logopen);
$patternTime = "(?P<thetime>(?P<tag>\d\d)\.(?P<monat>\d\d)\.(?P<jahr>\d\d\d\d)\s(?P<stunde>\d\d)\:(?P<minute>\d\d)\:(?P<sekunde>\d\d))";
$pattern = "/(" . $patternTime . "(\s*(Ping: )(?P<pingvalue>.+)( ms)\s*(Download: )(?P<downvalue>.+)( Mbit\/s)\s*(Upload: )(?P<upvalue>.+)( Mbit\/s)\s*)?)/" ;
preg_match_all($pattern, $log, $matches);
$pings = $matches[pingvalue];
$downs = $matches[downvalue];
$ups = $matches[upvalue] ;
$theTimes = $matches[thetime] ;
$jahre = $matches[jahr] ;
$monate = $matches[monat] ;
$tage = $matches[tag] ;
$stunden = $matches[stunde] ;
$minuten = $matches[minute] ;
$sekunden = $matches[sekunde] ;
?>

<!-- Styles -->
<style>

body{
	background-color: #000207;
	background: url('back.jpg') no-repeat top center fixed; 
	background-width: 100% ;
}
h1{
	color: #FFFFEF;
    vertical-align: middle;
    text-align: center;
	font-family: "Lucida Console";
	background-color: rgba(0,7,14,0.5);
}
#charts {
    top: 50%;
    left: 50%;
}

#chartdiv {
	height	: 737px;
	width: 1037px;
    vertical-align: middle;
	background-color: rgba(255,255,237,0.91);
}

#chartPunktediv {
	height	: 737px;
	width: 1037px;
    vertical-align: middle;
	background-color: rgba(255,255,237,0.91);
}
										
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<script src="http://www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
<script src="http://www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
<script src="https://www.amcharts.com/lib/3/themes/dark.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.js"></script>

<!-- Chart code -->
<script>
var chartData = getChartData();
var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
	"theme": "light",
    "marginRight": 40,
    "marginLeft": 40,
    "dataProvider": chartData,
    "autoMarginOffset": 20,
    "mouseWheelZoomEnabled":true,
	"parseDates": true,
	"valueAxes": [{
        "id": "v1",
        "axisAlpha": 0,
        "position": "left",
        "ignoreAxisWidth":true
    }],
    "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
    },
    "graphs": [{
		"legendValueText": "Ping",
        "id": "ping",
		"connect": true,
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
        },
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "ping line",
        "useLineColorForBulletBorder": true,
        "valueField": "ping",
        "balloonText": "<span style='font-size:13px;'>[[ping]]</span>"
    }, {
        "id": "down",
		"connect": true,
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
        },
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "download line",
        "useLineColorForBulletBorder": true,
        "valueField": "down",
        "balloonText": "<span style='font-size:13px;'>[[down]]</span>"
		}, {
        "id": "up",
		"connect": true,
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
        },
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "upload line",
        "useLineColorForBulletBorder": true,
        "valueField": "up",
        "balloonText": "<span style='font-size:13px;'>[[up]]</span>"
		}],
    "chartScrollbar": {
        "graph": "down",
		"connect": true,
        "oppositeAxis":false,
        "offset":150,
        "scrollbarHeight": 200,
        "backgroundAlpha": 0.1,
        "selectedBackgroundAlpha": 0.3,
        "selectedBackgroundColor": "#AAAAAA",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount":true,
        "color":"#DDDDDD"
    },
    "chartCursor": {
        "pan": true,
        "valueLineEnabled": true,
		"oneBalloonOnly" : true,
        "cursorAlpha":1,
        "cursorColor":"#258cbb",
        "valueLineAlpha":0.2,
        "valueZoomable":true,
        "categoryBalloonDateFormat": "JJ:NN, DD MMMM"
    },
    "valueScrollbar":{
      "oppositeAxis":false,
      "offset":50,
      "scrollbarHeight":10
    },
    "categoryField": "valueDatetime",
    "categoryAxis": {
		"parseDates": true,
        "minPeriod": "mm",
        "dashLength": 100,
        "minorGridEnabled": true,
		"labelRotation": "90"
    },
    "export": {
        "enabled": true
    }
	
});

chart.addListener("rendered", zoomChart);

var chartPunkte = AmCharts.makeChart("chartPunktediv", {
    "type": "serial",
	"theme": "light",
    "marginRight": 40,
    "marginLeft": 40,
    "dataProvider": chartData,
    "autoMarginOffset": 20,
    "mouseWheelZoomEnabled":true,
	"parseDates": true,
	"valueAxes": [{
        "id": "v1",
        "axisAlpha": 0,
        "position": "left",
        "ignoreAxisWidth":true
    }],
    "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
    },
    "graphs": [{
		"legendValueText": "Ping",
        "id": "ping",
		"connect": true,
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
        },
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "ping line",
        "useLineColorForBulletBorder": true,
        "valueField": "ping",
        "balloonText": "<span style='font-size:13px;'>[[ping]]</span>"
    }, {
        "id": "down",
		"connect": true,
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
        },
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "download line",
        "useLineColorForBulletBorder": true,
        "valueField": "down",
        "balloonText": "<span style='font-size:13px;'>[[down]]</span>"
		}, {
        "id": "up",
		"connect": true,
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
        },
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "upload line",
        "useLineColorForBulletBorder": true,
        "valueField": "up",
        "balloonText": "<span style='font-size:13px;'>[[up]]</span>"
		}],
    "chartScrollbar": {
        "graph": "down",
		"connect": true,
        "oppositeAxis":false,
        "offset":150,
        "scrollbarHeight": 200,
        "backgroundAlpha": 0.1,
        "selectedBackgroundAlpha": 0.3,
        "selectedBackgroundColor": "#AAAAAA",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount":true,
        "color":"#DDDDDD"
    },
    "chartCursor": {
        "pan": true,
        "valueLineEnabled": true,
		"oneBalloonOnly" : true,
        "cursorAlpha":1,
        "cursorColor":"#258cbb",
        "valueLineAlpha":0.2,
        "valueZoomable":true,
        "categoryBalloonDateFormat": "JJ:NN, DD MMMM"
    },
    "valueScrollbar":{
      "oppositeAxis":false,
      "offset":50,
      "scrollbarHeight":10
    },
    "categoryField": "valueDatetime",
    "categoryAxis": {
		"parseDates": true,
        "minPeriod": "mm",
        "dashLength": 100,
        "minorGridEnabled": true,
		"labelRotation": "90"
    },
    "export": {
        "enabled": true
    }
	
});

chartPunkte.addListener("rendered", zoomChart);

zoomChart();

function zoomChart() {
    chart.zoomToIndexes(chart.dataProvider.length - 100, chart.dataProvider.length - 1);
}
function getChartData() {
    var chartData = [];
	
	<?php
		for ($x = 0; $x < count($theTimes); $x++) {
			echo '
			chartData.push({
			' ;	
			$dateParmas = $jahre[$x] . ',' . (($monate[$x]) - 1) . ',' . $tage[$x] . ',' . $stunden[$x] . ',' . $minuten[$x] . ',' . $sekunden[$x] ;
			echo '	valueDatetime: new Date(' . $dateParmas . '),
			';
			if (($pings[$x]) != "") {
				echo '	ping: ' . ($pings[$x]) . ',
				';
				echo '	down: ' . $downs[$x] . ',
				';
				echo '	up: ' . $ups[$x] . ' ';
				echo '
				});';
				
			} else {
				echo '	ping: 0.0,
				';
				echo '	down: 0.0,
				';
				echo '	up: 0.0 ';
				echo '
				});';
								
			}
		}		
	?>
	
    return chartData;
}
</script>

<!-- HTML -->
<div>
	<br>
	<h1>ISP-CO</h1>
	<div id="charts"  align="center">
		<div id="chartdiv"></div>	
	
		<div id="chartPunktediv"></div>
	</div>	
</div>	
	