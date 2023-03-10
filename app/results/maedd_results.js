var urlDResults="app/results/maedd_results.php";
var d = Cookies('decimal');
if(d===undefined){
    d=3;
}
var decimal='d'+d.toString();
var charttype='line';
var content=getcontenttable();
$(document).ready(function() { 
    getcontentresults();
            
    $("input[name='charttype']").change(function () {
        var val = $(this).attr('id').slice(-1);
        basicr[0]['iopsim'] = val;
    });

    $("#reportType").on('change', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        generateresults(this.value, $('#reportTypeSector').val());
    });

    $("#reportTypeSector").on('change', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        getTables(this.value);
        generateresults($("#reportType").val(), this.value);
    });

    $("#decUp").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.d++;
            window.decimal = 'd' + parseInt(window.d);
            $('#gridResults').jqxGrid('updateBoundData', 'cells');
        });
        $("#decDown").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.d--;
            window.decimal = 'd' + parseInt(window.d);
            $('#gridResults').jqxGrid('updateBoundData', 'cells');
        });

        $(".changeChart").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.charttype=$(this).attr('id');

            var chart1 = $('#chartResults').jqxChart('getInstance');
            chart1.seriesGroups[0].type = window.charttype;
            chart1.update();
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") // activated tab
            if (target=="#chart"){
                generatechart($("#reportType").val(), $('#change').html());
            }
          });
});


function getcontentresults(){
            $('#reportTypeSector').html('');
            htmlstring = "";
            var htmlarr = [];
            for (i = 0; i < content.length; i++) {
                htmlstring = "";
        htmlstring += '<div class="panel panel-default">\
            <div class="panel-heading" style="padding-right: 0px !important;">\
            <table style="width: 100%;">\
            <tr>\
            <td style="width:50px"><b>' + content[i]['id'] + '</b></td>\
            <td>\
            <b><a data-toggle="collapse" class="pstitle" style="display:block; width:100%" data-parent="#accordion" id="psid_' + content[i]['title'] + '" href="#collapse_' + content[i]['title'].replace(/[^A-Z0-9]/ig, "") + '">\
            <span lang="en">' + content[i]['title'] + '</span> </a></b>\
            </td>\
            </tr>\
            </table>\
            </div>\
            <div id="collapse_' + content[i]['title'].replace(/[^A-Z0-9]/ig, "") + '" class="panel-collapse collapse">\
            <div class="panel-body" style="border: 0 !important;">\
            <table class="table table-hover" style="width: 100%;">';

            $('#reportTypeSector').append('<option value='+content[i]['id']+ '>'+content[i]['id']+' '+ window.lang.translate(content[i]['title']) + '</option>');

            $.each(content[i]['tables'], function (index, value) {
                htmlstring += '<tr>\
                    <td style="width: 50px;"></td>\
                    <td style="width: 50px;">'+value['id']+'</td>\
                    <td>\
                    <a style="display:block; cursor: pointer;"  onclick="generateresults(\'' + value['id'] + '\',\''+content[i]['id']+'\')" lang="en" data-lang-token="'+value['title']+'">' + value['title'] + '</a>\
                    </td>\
                    <td style="width:70px; text-align:center"> \
                    <a  class="'+ value['notexisticons'] + '" onclick="generateresults(\'' + value['id'] + '\',\''+content[i]['id']+'\')">\
                    <i class="material-icons btnblue" data-toggle="tooltip"  title="TABLE" lang="en" data-lang-content="false">view_module</i></a></td>\
                    <td style="width:70px; text-align:center">\
                    <a  class="'+ value['notexisticons'] + '" data-toggle="tooltip"  title="CHART" lang="en" data-lang-content="false" onclick="generateresults(\'' + value['id'] + '\',\''+content[i]['id']+'\')">\
                    <i class="material-icons btnorange">equalizer</i></a></td>\
                    </tr>';
            })
    
            htmlstring += '</table>\
                </div>\
                </div>\
                </div>';
            htmlarr.push(htmlstring);
            }
            $("#accordionresults").html(htmlarr.join(""));

}
function generateresults(id, idsector, unit) {
    showloader();
    $.ajax({
        url: urlDResults,
        data: {
            action: 'table',
            id: id,
            unit:unit
        },
        type: 'POST',
        success: function (result) {
            $('#resultModal').modal('show');
            $('#reportTypeSector').val(idsector);
            getTables(idsector, id);
            result=jQuery.parseJSON(result);
            $('#allyears').val(result['allyears'].join(","));
            $('#tabs a[href="#table"]').tab('show');
            showDataGrid(result);
            if(id=='6.1.'){
                $('#units').show(); 
                $("#change").html(result['unit']);
            }else{
                $('#units').hide();
            }
            hideloader();
        },
        error: function (xhr, status, error) {
            hideloader();
            ShowErrorMessage(error);
        }
    });
}

function generatechart(id, unit) {
    showloader();
    $.ajax({
        url: urlDResults,
        data: {
            action: 'table',
            id: id,
            unit:unit
        },
        type: 'POST',
        success: function (result) {
                $('#reportType').val(id);
                results=jQuery.parseJSON(result);
                $("#chartResults").html("");
                var data=results['result']; 
                var series=results['series']; 
                var allyears=results['allyears'];
                var unit=results['unit']; 
                var datachart=[];
                var max=0;
                for(var i=0; i<allyears.length;i++){
                    var row={'item':allyears[i]};
                    for(var j=0;j<data.length;j++){
                        if(data[j]['chart']){
                        row[data[j]['item']]=data[j][allyears[i]];
                        if(max<data[j][allyears[i]]){
                            max=data[j][allyears[i]];
                        }
                    }
                    }
                    datachart.push(row);
                }

                var series1=[];
                for(var k=0;k<series.length;k++){
                    series1.push({ dataField: series[k], displayText: series[k]});
                }
                    var settings = {
                        title: $('#title').text().replace(id,""),
                        description:"",
                        enableAnimations: true,
                        showLegend: true,
                        padding: { left: 20, top: 5, right: 20, bottom: 5 },
                        titlePadding: { left: 90, top: 10, right: 0, bottom: 10 },
                        source: datachart,
                        borderLineColor: '#ffffff',
                        xAxis:
                            {
                                type:'basic',
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
                                    type: window.charttype,
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
        },
        error: function (xhr, status, error) {
            hideloader();
            ShowErrorMessage(error);
        }
    });
}

//get data
function showDataGrid(result) {
var allyears=result['allyears'];
var datastructure = [];
datastructure.push({ name: 'item', map: 'item', type: 'string' });
datastructure.push({ name: 'unit', map: 'unit', type:'string' });
datastructure.push({ name: 'css', map: 'css', type:'string' });
for (var i = 0; i < allyears.length; i++) {
    datastructure.push({ name: allyears[i], map: allyears[i], type: 'number' });
}
var source =
{
    localdata: result['result'],
    datatype: "array",
    datafields:datastructure
};
var dataAdapter = new $.jqx.dataAdapter(source);
var cellclassname = function (row, column, value, data) {
        return data.css;
};

let cellsrenderer = function(row, columnfield, value, defaulthtml, columnproperties) {
    if(value!='' || value=='0'){ 
    var formattedValue = $.jqx.dataFormat.formatnumber(value, window.decimal);
    return '<span style="margin: 4px; float:right; ">' + formattedValue + '</span>';
    }
}; 

var plcolumns = [];
plcolumns.push({ text: result['unit'], datafield: 'item', align: 'right', cellsalign: 'left', width: '300px', editable: false, cellclassname: cellclassname });
for (i = 0; i < allyears.length; i++) {
    plcolumns.push({
        text: allyears[i], datafield: allyears[i], cellsalign: 'center', align: 'right', editable: false, cellsformat: decimal, 
        cellsrenderer:cellsrenderer, 
        cellclassname: cellclassname 
    });
}

$("#gridResults").jqxGrid(
    {
        width: '100%',
        theme: 'metro',
        source: dataAdapter,
        selectionmode: 'multiplecellsadvanced',
        pageable: false,
        autoheight: true,
        sortable: false,
        altrows: true,
        enabletooltips: true,
        editable: true,
        columns: plcolumns

    });
}

function ArrayToObject(arr){
    var obj = {};
    for (var i = 0;i < arr.length;i++){
        obj[arr[i]] = arr[i];
    }
    return obj
}

function recalc(unit){
    showloader();
    $('#change').html(unit);
    generateresults('6.1.', '6.', unit);
    hideloader();
}

function exportResults(){
    $.ajax({
        url: 'app/results/maedd_results_export.php',
        type: 'POST',
        success: function (result) {
            window.location = 'app/results/maedd_results_excel.php';
           ShowSuccessMessage("Excel file successfully created")
            hideloader();
        },
        error: function (xhr, status, error) {
            hideloader();
            ShowErrorMessage(error);
        }
    });
}

function getcontenttable(){
    var content=[];
    var row=new Array();
    row['id']='1.';
    row['title']='GDP';
    
    row['tables']=[
        {'id':'1.1.', 'title':'GDP formation by sector/subsector (absolute values)'},
        {'id':'1.2.', 'title':'Per Capita GDP by sector'},
        {'id':'1.3.', 'title':'GDP formation by sector/subsectors (growth rates)'}
    ];
     content.push(row);

    //INDUSTRY
  
    row=new Array();
    row['id']='2.1.';
    row['title']='INDUSTRY - Useful Energy';
    
    row['tables']=[
        {'id':'2.1.1.', 'title':'Useful energy demand for Motive Power'},
        {'id':'2.1.2.', 'title':'Useful energy demand for Electricity specific uses'},
        {'id':'2.1.3.', 'title':'Useful energy demand for Thermal uses'},
        {'id':'2.1.4.', 'title':'Total useful energy demand in Industry'}
    ];
    content.push(row);

    row=new Array();
    row['id']='2.2.';
    row['title']='INDUSTRY - Energy Demand ACM';
    
    row['tables']=[
        {'id':'2.2.1.', 'title':'Total final energy demand for thermal uses in Agriculture, Construction & Mining'},
        {'id':'2.2.2.', 'title':'Total final energy demand (absolute) in Agriculture, Construction & Mining'},
        {'id':'2.2.3.', 'title':'Total final energy demand (shares) in Agriculture, Construction & Mining'},
        {'id':'2.2.4.', 'title':'Total Final Energy Demand per Value Added in Agriculture, Construction & Mining'}
    ];
    content.push(row);

    row=new Array();
    row['id']='2.3.';
    row['title']='INDUSTRY - Final Demand Manufacturing';
    
    row['tables']=[
        {'id':'2.3.1.', 'title':'Useful Thermal Energy Demand in Manufacturing'},
        {'id':'2.3.2.', 'title':'Penetration of Energy Carriers into Useful Thermal Energy Demand in Manufacturing'},
        {'id':'2.3.3.', 'title':'Total final energy demand for thermal uses in Manufacturing'},
        {'id':'2.3.4.', 'title':'Total Final Energy Demand in Manufacturing (absolute)'},
        {'id':'2.3.5.', 'title':'Total Final Energy Demand in Manufacturing (shares)'},
        {'id':'2.3.6.', 'title':'Total Final Energy Demand per Value Added in Manufacturing'},
    ];
    content.push(row);

    row=new Array();
    row['id']='2.4.';
    row['title']='INDUSTRY - Demand Industry';
    
    row['tables']=[
        {'id':'2.4.1.', 'title':'Total Final Energy Demand in Industry (absolute)'},
        {'id':'2.4.2.', 'title':'Total Final Energy Demand in Industry (shares)'},
        {'id':'2.4.3.', 'title':'Total Final Energy Demand Per Value Added in Industry'}
    ];
    content.push(row);


    row=new Array();
    row['id']='3.1.';
    row['title']='TRANSPORT - Freight';
    
    row['tables']=[
        {'id':'3.1.1.', 'title':'Energy Demand of Freight Transportation'},
        {'id':'3.1.2.', 'title':'Energy Demand of Freight Transportation (by fuel)'},
        {'id':'3.1.3.', 'title':'Energy Demand of Freight Transportation (by fuel group)'},
        {'id':'3.1.4.', 'title':'Energy intensities of freight transportation'},
        {'id':'3.1.5.', 'title':'Total freight-kilometers'}
    ];
    content.push(row); 

    row=new Array();
    row['id']='3.2.';
    row['title']='TRANSPORT - Intercity';
    
    row['tables']=[
        {'id':'3.2.1.', 'title':'Intercity Transportation by mode'},
        {'id':'3.2.2.', 'title':'Energy Intensity of Intercity Passenger Transportation(energy units)'},
        {'id':'3.2.3.', 'title':'Energy Demand of Intercity Passenger Transportation(by mode)'},
        {'id':'3.2.4.', 'title':'Energy Demand of Intercity Passenger Transportation (by fuel)'},
        {'id':'3.2.5.', 'title':'Energy Demand of Intercity Passenger Transportation (by fuel group)'}
    ];
    content.push(row); 

    row=new Array();
    row['id']='3.3.';
    row['title']='TRANSPORT - Urban';
    
    row['tables']=[
        {'id':'3.3.1.', 'title':'Urban transport Passenger by mode'},
        {'id':'3.3.2.', 'title':'Energy Intensity of Urban transport (energy units)'},
        {'id':'3.3.3.', 'title':'Energy Demand of Urban transport (by mode)'},
        {'id':'3.3.4.', 'title':'Energy Demand of Urban transport (by fuel)'},
        {'id':'3.3.5.', 'title':'Energy Demand of Urban transport (by fuel group)'}
    ];
    content.push(row); 

    row=new Array();
    row['id']='3.4.';
    row['title']='TRANSPORT - Final Demand Transport';
    
    row['tables']=[
        {'id':'3.4.1.', 'title':'Final energy demand in Transportation sector (by fuels)'},
        {'id':'3.4.2.', 'title':'Final energy demand in Transportation sector (shares)'},
        {'id':'3.4.3.', 'title':'Final energy demand in Transportation sector (by fuel groups)'},
        {'id':'3.4.4.', 'title':'Final energy demand in Transportation sector (shares by fuel groups)'},
        {'id':'3.4.5.', 'title':'Final energy demand in Transportation sector (by subsector)'},
        {'id':'3.4.6.', 'title':'Final energy demand in Transportation sector (shares by subsector)'},
        {'id':'3.4.7.', 'title':'Energy Demand of Urban + Intercity + International Passenger Transportation (by fuel group)'}      
    ];
    content.push(row); 

    row=new Array();
    row['id']='4.';
    row['title']='HOUSEHOLD';
    
    row['tables']=[
        {'id':'4.1.', 'title':'Final Energy Demand in Household Sector'},
        {'id':'4.2.', 'title':'Useful Energy Demand in Household Sector'},
        {'id':'4.3.', 'title':'Total Final Energy Demand in Household'} 
    ];
    content.push(row); 

    row=new Array();
    row['id']='5.';
    row['title']='SERVICES';
    
    row['tables']=[
        {'id':'5.1.', 'title':'Useful Energy Demand in Service Sector'},
        {'id':'5.2.', 'title':'Final Energy Demand in Service Sector'},
        {'id':'5.3.', 'title':'Total Final Energy Demand in Service Sector (by energy forms)'} 
    ];
    content.push(row); 
    row=new Array();
    row['id']='6.';
    row['title']='TOTAL FINAL ENERGY Demand';
    
    row['tables']=[
        {'id':'6.1.', 'title':'Final Energy Demand by Energy Form, Capita, GDP and Sector'}
    ];
    content.push(row); 

    return content;
}

function getTables(idsector, idtable){
    $('#reportType').html('');
    for (i = 0; i < content.length; i++) {
        if(content[i]['id']==idsector){
            $.each(content[i]['tables'], function (index, value) {
                var title=value['id']+' '+ window.lang.translate(value['title']);
                var selected=''
                if(value['id']==idtable){
                    selected='selected';
                    $('#title').html(title);
                    $('#titlechart').html(title);
                }
                $('#reportType').append('<option value='+value['id']+ ' '+selected+'>'+ title + '</option>');
            })
        }
    }
}
