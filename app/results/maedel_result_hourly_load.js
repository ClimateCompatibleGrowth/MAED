function getdataresulthourlyload(results) {
    var allyear = results['allyear'];
    var sectors=results['sectors'];
    var hours=results['hours'];
    var res=results['data'];

    var datar=[]; 
    for (var i = 0; i < allyear.length; i++) {
        for (var j=0; j<hours[allyear[i]]; j++) {
        var data = new Array();
        data['year'] = allyear[i];
        data['hour']=j+1;
        var total=0;
        for (var k = 0; k < sectors.length; k++) {
            var clients=sectors[k]['clients'];  
            var sumsector=0; 
            for (var l = 0; l < clients.length; l++) {
                    data[clients[l]['id']] = res[clients[l]['id']+'_'+allyear[i]+'_'+[j]]*1000;
                    sumsector=sumsector+data[clients[l]['id']];
                }
                data[sectors[k]['item']]=sumsector;
                total=total+sumsector;
            }
            data['total']=total;
            datar.push(data);
        }
        
    }
    return datar;
}

function showresults(results) {
    $('#savedata').hide();
    $('#chart').hide();
    $('#info').hide();
    $('#gridTitle').html("Hourly load (MW)");
    var cv = new wijmo.collections.CollectionView(getdataresulthourlyload(results));
    $("#gsFlexGrid").addClass("hourly_load");
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    var groupDesc = new wijmo.collections.PropertyGroupDescription('year');
	cv.groupDescriptions.push(groupDesc);
    var sectors=results['sectors'];
    var cols=[
        { header: window.lang.translate('Year'), binding: 'year', width:100},
        { header: window.lang.translate('Hour'), binding: 'hour', width:100, dataType: 2, format: 'f0', align:"center"}
    ];

    for (var k = 0; k < sectors.length; k++) {
        var clients=sectors[k]['clients'];   
        for (var l = 0; l < clients.length; l++) {
            cols.push({ header:  clients[l]['clientname'], binding: clients[l]['id'], width:100, dataType: 2, format: 'f7'});
        }
        cols.push({ header:window.lang.translate('Total'), binding: sectors[k]['item'], width:100, dataType: 2, format: 'f7'});
    }
    cols.push({ header:window.lang.translate('Total'), binding: 'total', width:100, dataType: 2, format: 'f7'});
    grid.initialize({
        autoGenerateColumns: false,   
        columns: cols,
        itemsSource: cv,
        allowSorting:false,
        isReadOnly :true,
        frozenColumns : 2,
        allowMerging : wijmo.grid.AllowMerging.AllHeaders
    })
    grid.collapseGroupsToLevel(0);
    var hr = new wijmo.grid.Row();
    grid.columnHeaders.rows.push(hr);
    grid.columnHeaders.rows[0].allowMerging = true;
    for (var i = 0; i < grid.columns.length; i++) {
        grid.columns[i].allowMerging=true;
    }
    grid.columnHeaders.setCellData(0, 0, window.lang.translate('Year'));
    grid.columnHeaders.setCellData(0, 1, window.lang.translate('Hour'));

    var x=1;
    for (var k = 0; k < sectors.length; k++) {
        var clients=sectors[k]['clients']; 
        for (var l = 0; l < clients.length; l++) {
            x++;
            grid.columnHeaders.setCellData(0, x, sectors[k]['item']);
        }
        x++;
       grid.columnHeaders.setCellData(0, x, sectors[k]['item']);
    }
    grid.columnHeaders.setCellData(0, x+1, window.lang.translate('Total'));
}