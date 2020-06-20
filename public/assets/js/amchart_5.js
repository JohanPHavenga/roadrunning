// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chart_5", am4charts.XYChart);

chart.dataSource.url = "./result/my_data/5";

// Set input format for the dates
chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";
chart.durationFormatter.durationFormat = "hh:mm:ss";

// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
var durationAxis = chart.yAxes.push(new am4charts.DurationAxis());
dateAxis.dateFormats.setKey("day", "d MMM, yyyy");

// Create series
var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.valueY = "time";
series.dataFields.dateX = "date";
series.tooltipText = "{race} \n\r [bold]{time.formatDuration()}[/]";
series.stroke = am4core.color("#dc3545");
series.strokeWidth = 3;
series.minBulletDistance = 15;

// Drop-shaped tooltips
series.tooltip.background.cornerRadius = 20;
series.tooltip.background.strokeOpacity = 0;
series.tooltip.pointerOrientation = "vertical";
series.tooltip.label.minWidth = 40;
series.tooltip.label.minHeight = 40;
series.tooltip.label.textAlign = "middle";
series.tooltip.label.textValign = "middle";

// Make bullets grow on hover
var bullet = series.bullets.push(new am4charts.CircleBullet());
bullet.circle.strokeWidth = 2;
bullet.circle.radius = 4;
bullet.circle.fill = am4core.color("#fff");

var bullethover = bullet.states.create("hover");
bullethover.properties.scale = 1.3;

// Make a panning cursor
chart.cursor = new am4charts.XYCursor();
chart.cursor.behavior = "panXY";
chart.cursor.xAxis = dateAxis;
chart.cursor.snapToSeries = series;