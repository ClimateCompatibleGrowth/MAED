var calendarUrl="app/data/maedel_data.php";
var result = loadData();
result.then(result => getData(result));

function getData(result){
    fields = [];
    cols = [];
    var res = JSON.parse(result);
    $('#fday').val(res['calendar']['fday']);

    var days = [];
    var daytypesarray=Object.entries(res['daytypes']);
        for (var x = 0; x < daytypesarray.length; x++) {
        if (daytypesarray[x][1] == 'Y') {
             $('#'+daytypesarray[x][0]).attr('checked',true);
        }
    }

    days.push({id:'Mon', value_en:window.lang.translate('Monday')});
    days.push({id:'Tue', value_en:window.lang.translate('Tuesday')});
    days.push({id:'Wed', value_en:window.lang.translate('Wednesday')});
    days.push({id:'Thu', value_en:window.lang.translate('Thursday')});
    days.push({id:'Fri', value_en:window.lang.translate('Friday')});
    days.push({id:'Sat', value_en:window.lang.translate('Saturday')});
    days.push({id:'Sun', value_en:window.lang.translate('Sunday')});
    days.push({id:'Wd',  value_en:window.lang.translate('Working days')});
    days.push({id:'SSH', value_en:window.lang.translate('Saturday-Sunday-Holiday')});
    days.push({id:'SH',  value_en:window.lang.translate('Sunday-Holiday')});
    days.push({id:'Ad',  value_en:window.lang.translate('Any day')});

    var seasons = [];
    var daytypes=[];
    for (var x = 1; x <= res['calendar']['nseason']; x++) {
        seasons.push({season:window.lang.translate('Season')+x, name:res['seasons']['seasonname_'+x], startingdate:res['seasons']['season_'+x]});
    }

    for (var x = 1; x <= res['calendar']['ntday']; x++) {
        daytypes.push({daytype:window.lang.translate('Day type')+x, value:res['seasons']['daytype_'+x]});
    }

    fields.push({ name: 'season', map: 'season', type: 'string' });
    fields.push({ name: 'name', map: 'name', type: 'string' });
    fields.push({ name: 'startingdate', map: 'startingdate', type: 'string' });
    cols.push({ text: window.lang.translate('Season'), datafield: 'season', editable: false, width: '30%', cellclassname: 'metro-column' });
    cols.push({ text: window.lang.translate('Season name'), datafield: 'name', width: '35%' });
    cols.push({ text: window.lang.translate('Starting date (yyyy-mm-dd)'), datafield: 'startingdate', width: '35%' });

    var sourceseasons =
    {
        datafields: fields,
        cache: false,
        async: false,
        localdata: seasons
    };


    var daseasons = new $.jqx.dataAdapter(sourceseasons);
    daseasons.dataBind();

    $("#jqxseasons").jqxGrid(
        {
            width: '100%',
            theme: 'metro',
            source: daseasons,
            altrows: false,
            enabletooltips: true,
            editable: true,
            columns: cols,
            autoheight: true
        });

    //day types   
    var sourcedays =
    {
        localdata: days,
        datatype: "array"
    };
    var dadays = new $.jqx.dataAdapter(sourcedays);
    dadays.dataBind();

    var sourcedaysinweek =
    {
        localdata: days,
        datatype: "array"
    };
    var dadaysinweek = new $.jqx.dataAdapter(sourcedaysinweek);
    dadaysinweek.dataBind();


    var sourcedaytypes =
    {
        datafields: [
            { name: 'daytype', map: 'daytype', type: 'string' },
            { name: 'value', map: 'value', type: 'string' },
            { name: 'value_en', value: 'value', values: { source: dadays.records, value: 'id', name: 'value_en' } }

        ],
        cache: false,
        async: false,
        localdata: daytypes
    };


    var dadaytypes = new $.jqx.dataAdapter(sourcedaytypes);
    dadaytypes.dataBind();

    $("#jqxdaytypes").jqxGrid(
        {
            width: '100%',
            theme: 'metro',
            source: dadaytypes,
            altrows: false,
            enabletooltips: true,
            editable: true,
            autoheight: true,
            columns: [
                { text: ' ', datafield: 'daytype', width: '40%', editable: false, cellclassname: 'metro-column' },
                {
                    text: window.lang.translate('Day type'), datafield: 'value', displayfield: 'value_en', columntype: 'dropdownlist', width: '60%',
                    createeditor: function (row, value, editor) {
                        editor.jqxDropDownList({ source: dadays, displayMember: 'value_en', valueMember: 'id' });
                    }
                }
            ]
        });
}

$(document).ready(function () {

    $("#addRowDayTypes").on('click', function () {
        var id = $("#jqxdaytypes").jqxGrid('getdatainformation').rowscount;
        if (id < 7) {
            var datarow = { daytype: window.lang.translate('Day type') + (id + 1).toString(), id: 'Mon' };
            $("#jqxdaytypes").jqxGrid('addrow', null, datarow);
        } else {
            ShowErrorMessage("Maximun number of day types is 7");
        }
    });

    $("#addRowSeasons").on('click', function () {
        var id = $("#jqxseasons").jqxGrid('getdatainformation').rowscount;
        if (id < 12) {
            var datarow = { season: window.lang.translate('Season') + (id + 1).toString() };
            $("#jqxseasons").jqxGrid('addrow', null, datarow);
        } else {
            ShowErrorMessage("Maximun number of seasons is 12");
        }
    });
})

function loadData() {
    return Promise.resolve(
        $.ajax({
            url: calendarUrl,
            type: 'POST',
            data: { 
                id:"calendar",
                action:"get"
             },
        }));
}

function saveData(){
    var ntdaygrid = $("#jqxdaytypes").jqxGrid('getrows');
    var nseasongrid = $("#jqxseasons").jqxGrid('getrows');
    var fday=$("#fday").val();
    var ntday=ntdaygrid.length;
    var nseason=nseasongrid.length;
    var calendar={fday:fday, ntday:ntday,nseason:nseason, SID:1};

    var calendar_def={};
    var typedaydef={};
    for(var i=1; i<=nseason; i++){
        calendar_def['season_'+i]=nseasongrid[i-1]['startingdate'];
        calendar_def['seasonname_'+i]=nseasongrid[i-1]['name'];
    }

    for(var i=1; i<=ntday; i++){
        calendar_def['daytype_'+i]=ntdaygrid[i-1]['value'];
    }
    calendar_def['SID']=1;

    typedaydef['Mon_Mon']='Y';
    typedaydef['Tue_Tue']='Y';
    typedaydef['Wed_Wed']='Y';
    typedaydef['Thu_Thu']='Y';
    typedaydef['Fri_Fri']='Y';
    typedaydef['Sat_Sat']='Y';
    typedaydef['Sun_Sun']='Y';

    $("input:checkbox:checked").each(function() { 
        typedaydef[$(this).attr('id').toString()]='Y'; 
    });
    typedaydef['SID']=1;

    $.ajax({
        url: calendarUrl,
        type: 'POST',
        data: { 
            id:"calendar",
            action:"save",
            calendar:JSON.stringify(calendar),
            calendar_def:JSON.stringify(calendar_def),
            typedaydef:JSON.stringify(typedaydef)
         },
        success: function () {ShowSuccessMessage('Data saved successfully'); },
        failure: function () { ShowErrorMessage("Error!"); }
    });
}

function deleteRow(grid){
    bootbox.confirm({
        title: "<span lang='en'>MESSAGE</span>",
        message: "<span lang='en'>Are you sure?</span>",
        buttons: {
            cancel: {
                label: '<i class="material-icons btnred link mti17">close</i> <span lang="en">Close</span>'
            },
            confirm: {
                label: '<i class="material-icons link mti17">done</i> <span lang="en">Confirm</span>'
            }
        },
        callback: function (resultcs) {
            if (resultcs) {
                var selectedrowindex = $("#"+grid).jqxGrid('getselectedrowindex');
                var rowscount = $("#"+grid).jqxGrid('getdatainformation').rowscount;
                if (selectedrowindex >= 0 && selectedrowindex < rowscount) {
                    var id = $("#"+grid).jqxGrid('getrowid', selectedrowindex);
                    var commit = $("#"+grid).jqxGrid('deleterow', id);
                }
            }
        }
    });

}