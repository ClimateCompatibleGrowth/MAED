var maedtype = Cookies('maedtype');
var urlData="app/data/"+maedtype+"_data.php";
var charttype='line';
var d = getdecimal();
var storagePath="storage/"+maedtype+'/data/projects/';
var item_header = window.lang.translate('Item');
var unit_header = window.lang.translate('Unit');
    $(document).ready(function () {
        showloader();
        var id = Cookies('id');
        var folder=getGroup(id);
        $.getScript('app/'+folder+'/' + id + '.js', function () { 
            $.ajax({
                url: urlData,
                data: { id: id, action: 'get' },
                type: 'POST',
                success: function (res) {
                    var results = jQuery.parseJSON(res);
                    $('#allyears').val(results['allyear'].join(","));
                    showgrid(results);
                    if (id == 'economic_gdp') {
                        $('#gdp_subsectors').show();
                        $('#notes').show();
                        showgrid1(results);
                    }
                    if(id=='economic_demography'){
                        $('#demographynotes').show();
                    }

                    $('#dataNotes').val(results['datanotes']);
                    hideloader();
                },
                error: function (xhr, status, error) {
                    ShowErrorMessage(error);
                    hideloader();
                }
            });
        });

        let group=getGroup(id);
        getTabs(tabs[group], translates[group]);

        if(group=='transport' || group=='industry' || group=='economic'){
            $('#gridTitle').html(translates[group+'_title'][tabs[group].indexOf(id)]);
        }
        else
        {
            $('#gridTitle').html(translates[group][tabs[group].indexOf(id)]);
        }

        $('#' + id).parent().addClass('active').siblings().removeClass('active');

        $('#export').attr('download', id);

        $(".changeChart").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.charttype=$(this).attr('id');

            var chart1 = $('#chartResults').jqxChart('getInstance');
            chart1.seriesGroups[0].type = window.charttype;
            chart1.update();
        });
    });

    function showChart(g){
        $("#chartModalInput").modal('show');
            $('#chartModalInput').on('shown.bs.modal', function (e) {
                getchartresults(g);
        })
    }

    function loadInfo() {
        var infoelement = Cookies("id");
        $.ajax({
            type: "GET",
            url: "app/info.xml",
            dataType: "xml",
            success: function (xml) {
                $("#infotext").html($(xml).find(infoelement).text());
                $('#infoModal').appendTo("body").modal('show');
            }
        });
    }

    function saveData() {
        var maedtype = Cookies('maedtype');
        var grid = wijmo.Control.getControl("#gsFlexGrid");
        var a = grid.itemsSource.items;
        var allyear = $('#allyears').val().split(",");
        var object = {};
        var id = Cookies('id');
        object['SID'] = '1';
        //D
        if (id == 'economic_demography') {
            for (var i = 0; i < a.length; i++) {
                for (var y = 0; y < allyear.length; y++) {
                    if (a[i]['conf'] != '') {
                        object[a[i]['id'] + '_' + allyear[y] + '_' + a[i]['conf'].slice(0, -1)] = a[i][" " + allyear[y]];
                    } else {
                        object[a[i]['id'] + '_' + allyear[y]] = a[i][" " + allyear[y]];
                    }

                }
            }
        } else if (id == 'economic_gdp') {
            for (var i = 0; i < a.length; i++) {
                for (var y = 0; y < allyear.length; y++) {
                    if (a[i]['id'] != '') {
                        object[a[i]['id'] + '_' + allyear[y]] = a[i][" " + allyear[y]];
                    }
                }
            }
            var grid1 = wijmo.Control.getControl("#gsFlexGrid1");
            var b = grid1.itemsSource.items;
            for (var i = 0; i < b.length; i++) {
                for (var y = 0; y < allyear.length; y++) {
                    if (b[i]['conf'] != 0) {
                        if (b[i]['conf'] == 'Total') {
                            object[b[i]['id'] + allyear[y]] = b[i][" " + allyear[y]];
                        } else {
                            object[b[i]['id'] + '_' + allyear[y]] = b[i][" " + allyear[y]];
                        }
                    }
                }
            }
        } else

        //EL
        if(id=='coefficient_weekly'){
            var idclient=Cookies.get('idclient');
            for (var j=0; j<allyear.length; j++){
                var data={};
                for (var i = 0; i < a.length; i++) {
                    if (i < 53) {
                        data[idclient + '_'+allyear[j]+'_' + a[i]["id"]] = a[i][" " + allyear[j]];
                    } else {
                        //total 
                        data[idclient + '_'+allyear[j]] = a[i][" " + allyear[j]];
                    }
                }
                data['SID'] = idclient + '_'+allyear[j];
                object[allyear[j]]=data;
            }
        }else if(id=='coefficient_daily')
        {
            var columns=grid.columns;
            var idclient=Cookies.get('idclient');
            var year=Cookies.get('year');
            var daytype=['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

            for (var i = 0; i < a.length; i++) {
                for (var j = 0; j < daytype.length; j++) {
                    object[idclient + '_' + year + '_' + daytype[j] + '_' + a[i]["id"]] = a[i][columns[j+1]["header"]];
                }
                object[idclient + '_' + a[i]["id"] + '_' + year] = a[i]['Total'];
            }
            object['SID']=idclient + '_'+year;

        }else if(id=='coefficient_hourly')
        {
            var columns=grid.columns;
            var idclient=Cookies.get('idclient');
            var year=Cookies.get('year');
            for (var i = 0; i < a.length; i++) {
                for (j = 1; j <= $('#nseason').val(); j++) {
                    for (k = 1; k <= $('#ntday').val(); k++) {
                        if (i < 24) {
                            object[idclient+'_'+year + '_' + j + '_' + k + '_' + i] = a[i]['Season' + j + ' ' + columns[k]['header']];
                        } else {
                            //total
                            object[idclient +'_'+year+ '_' + j + '_' + k] = a[i]['Season' + j + ' ' + columns[k]['header']];
                        }
                    }
                }
            }
            object['SID']=idclient+'_'+year;
        }
        else {
            for (var i = 0; i < a.length; i++) {
                for (var y = 0; y < allyear.length; y++) {
                    object[a[i]['id'] + '_' + allyear[y]] = a[i][" " + allyear[y]];
                }
            }
        }

        if (id == 'service_factors' || id == 'service_intensity') {
            jQuery.each(a, function (i, val) {
                //sectors
                if (val['id'] == '0') {
                    for (var y = 0; y < allyear.length; y++) {
                        delete object[val['id'] + '_' + allyear[y]];
                    }
                }
            })
        }

        if (id == 'service_penetration' || id == 'household_factors_urban' || id == 'household_factors_rural') {
            for (var y = 0; y < allyear.length; y++) {
                delete object['0_' + allyear[y]];
                delete object['00_' + allyear[y]];
            }
        }

        if (id == 'transport_freight_generation' || id == 'transport_freight_intensity'
            || id == 'transport_intercity_factors' || id == 'transport_intercity_intensity'
            || id == 'transport_urban_factors' || id == 'transport_urban_intensity') {
            jQuery.each(a, function (i, val) {
                //sectors
                if (val['unit'] == '') {
                    for (var y = 0; y < allyear.length; y++) {
                        delete object[val['id'] + '_' + allyear[y]];
                    }
                }
            })
        }
        datanotes=$('#dataNotes').val();
        $.ajax({
            url: urlData,
            data: {
                'data': JSON.stringify(object),
                'datanotes':datanotes,
                'id': id,
                'action': 'edit'
            },
            type: 'POST',
            success: function (result) {
                ShowSuccessMessage("Data saved successfully");
            },
            error: function (xhr, status, error) {
                ShowErrorMessage(error);
            }
        });
    }

    function getchartresults(g) {
        showloader();
        $('#titlechartcard').text($('.modultitle').html());
        var grid = wijmo.Control.getControl("#"+g);
        var data = grid.itemsSource.items;
        var series=[];
        var allyears = $('#allyears').val().split(",");
        var unit='';
        var datachart = [];
        var max = 0;
        for (var i = 0; i < allyears.length; i++) {
            var row = { 'item': allyears[i] };
            for (var j = 0; j < data.length; j++) {
                row[data[j]['item']] = data[j][" "+allyears[i]];
                if(i==0 && data[j]['chart']){
                    series.push(data[j]['item']);
                }
                if (max < data[j][allyears[i]]) {
                    max = data[j][allyears[i]];
                }
            }
            datachart.push(row);
        }
        var series1 = [];
        for (var k = 0; k < series.length; k++) {
            series1.push({ dataField: series[k], displayText: series[k] });
        }
        var settings = {
            title: $('#gridTitle').html(),
            description: "",
            enableAnimations: true,
            showLegend: true,
            padding: { left: 20, top: 5, right: 20, bottom: 5 },
            titlePadding: { left: 90, top: 10, right: 0, bottom: 10 },
            source: datachart,
            xAxis:
            {
                type: 'basic',
                textRotationAngle: 0,
                dataField: 'item',
                showTickMarks: true,
                tickMarksInterval: 1,
                tickMarksColor: '#888888',
                unitInterval: 1,
                showGridLines: false,
                gridLinesInterval: 1,
                gridLinesColor: '#888888',
                axisSize: 'auto'
            },
            colorScheme: 'scheme01',
            seriesGroups:
                [
                    {
                        type: 'line',
                        columnsGapPercent: 50,
                        seriesGapPercent: 0,
                        valueAxis:
                        {
                            visible: true,
                            title: { text: unit }
                        },
                        series: series1
                    }
                ]
        };
        $('#chartResults').jqxChart(settings);
        hideloader();
    }