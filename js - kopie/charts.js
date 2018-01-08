google.charts.load('current', {packages: ['corechart', 'line', 'bar']});
LoadAllCharts(); // TODO Add this to a different element so i can reload it on stock edit / product edt

function LoadAllCharts() {
    // google.charts.setOnLoadCallback(drawOmzet);
    // google.charts.setOnLoadCallback(drawTop5Products);
    // google.charts.setOnLoadCallback(drawOrderStatus);
}

function drawOmzet() {

    var jsonData = $.ajax({
        url: "php/charts/getOmzet.php",
        dataType: "json",
        async: false
        }).responseText;

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);

    var options = {
        chartArea: {width: '50%'},
        hAxis: {
        title: 'Annual Turnover',
        minValue: 0
        },
        vAxis: {
        title: 'Customer'
        }
    };

    var chart = new google.visualization.BarChart(document.getElementById('chart_div_omzet'));

    chart.draw(data, options);
};

function drawTop5Products() {
    var jsonData = $.ajax({
        url: "php/charts/getTop5Products.php",
        dataType: "json",
        async: false
        }).responseText;

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);

    var chart = new google.visualization.PieChart(document.getElementById('chart_div_circle'));
    chart.draw(data, {});
}

function drawOrderStatus() {
    var jsonData = $.ajax({
        url: "php/charts/getOrderStatus.php",
        dataType: "json",
        async: false
        }).responseText;

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);

    var chart = new google.visualization.PieChart(document.getElementById('chart_div_circle2'));
    chart.draw(data, {});
}

/*********************************************************
 * JAVASCRIPT FOR ALL THE CHARTS NEEDED FOR STOCK.PHP
 *********************************************************/
function ShowChartsStock(el) {
    $('#productcharts').toggle()
    google.charts.setOnLoadCallback(drawStockInfo(el));
};

function drawStockInfo(el) {
    var jsonData;
    var id = $(el).attr('id');

    $.ajax({
        type: 'post', // Type = post
        data: {id: id}, // Given variable
        url: "/php/charts/getStockInfo.php",
        dataType: 'json',
        async: false,
        success: function(result){
            jsonData = result;
        }});
           
    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);

    var options = {
        hAxis: {
        title: 'Time'
        },
        vAxis: {
        title: 'Stock'
        },
        series: {
        1: {curveType: 'function'}
        }
    };
       
    var chart = new google.visualization.LineChart(document.getElementById('stockchart'));
    chart.draw(data, options);
}