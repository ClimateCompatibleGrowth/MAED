
function showresults(results){
    $("#chart").hide();
    $("#decDown").hide();
    $("#decUp").hide();
    $("#savedata").hide();
    $("#exportgrid").hide();
    $('#info').hide();
    $('#exportChart').show();
    $("#ldc").show();
    $("#hours").show();
    var allyear = results['allyear'];
    var hourlydata=results['data'];
    var ldc=Cookies.get('ldc');
    var series = [];
    for (i = 0; i < allyear.length; i++) {
        series.push({ dataField: allyear[i], displayText: allyear[i] });
    }
    var title=window.lang.translate("Hourly data");
    var description=window.lang.translate("Load curve");
    $('#gridTitle').html(title);

    if(ldc==='1'){
        title=window.lang.translate("LDC");
        description="";
    var s=hourlydata;
    var a={};
      for (i = 0; i < allyear.length; i++) {
          var year=allyear[i]
            a[year]=[];
      }
    
        for (var i = 0; i < s.length; i++) {
            for (var j = 0; j < allyear.length; j++) {
            var year=allyear[j];
            var b={'h':i,  year:s[i][allyear[j]] }
            a[allyear[j]].push(b);
            }
        }

        for (i = 0; i < allyear.length; i++) {
            var year=allyear[i];
              a[year]=sortByKeyDesc(a[year],'year');
        }

        var hourlydata=[];
        for (var i = 0; i < s.length; i++) {
            var aa={};
            for (var j = 0; j < allyear.length; j++) {
                 aa[allyear[j]]=a[allyear[j]][i]['year'];
            }
            hourlydata.push(aa);
        }
    }


    var settings = {
        title: title,
        description: description,
        showLegend: true,
        padding: { left: 10, top: 5, right: 10, bottom: 5 },
        titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
        source: hourlydata,
        enableCrosshairs: true,
        categoryAxis:
        {

            //dataField: 'hour',
            type: 'basic',
            // minValue: 175,
            // maxValue: 550,
            showGridLines: false,
            valuesOnTicks: true,
            rangeSelector: {
                size: 100,
                padding: { top: 10, bottom: 0 },
                backgroundColor: 'white',
                gridLinesColor: '#f0f0f0'
            }
        },
        colorScheme: 'scheme04',
        seriesGroups:
        [
            {
                type: 'line',
                useGradient: false,
                valueAxis:
                {
                    minValue: 0,
                    displayValueAxis: true,
                    description: 'GW',
                    axisSize: 'auto',
                    tickMarksColor: '#000',
                    gridLinesColor: '#ccc'
                },
                series: series
            }
        ]
    };
    $('#gsFlexGrid').removeClass('gridwijmocustom');
    $('#gsFlexGrid').height('500px');
    $('#gsFlexGrid').jqxChart(settings);
}

function sortByKeyAsc(array, key) {
    return array.sort(function (a, b) {
        var x = a[key]; var y = b[key];
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}

function sortByKeyDesc(array, key) {
    return array.sort(function (a, b) {
        var x = a[key]; var y = b[key];
        return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    });
}

function getChart(id){
    Cookies('ldc', '0'); 
    if(id=='ldc'){
        Cookies('ldc', '1'); 
    }
    getContent('results','result_chart', 'results');
}

function exportChartHourlyData(){
    $('#gsFlexGrid').jqxChart('saveAsJPEG', 'Chart.jpeg', 'references/jqwidgets/export.php');
}