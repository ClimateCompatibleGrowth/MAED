function getdatafinaldemand(results) {
    $('#gridTitle').html("Annual electricity demand");
    var ccdata = results['ccData'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var datar = [];
    jQuery.each(maintype, function(i, val) {
        if (val['id'] != null) {
            var data = new Array();
            data['id'] = val['id'];
            data['item'] = val['value'];
            data['unit'] = 'GWh';
            for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(ccdata[val['id'] + '_' + allyear[y]]);} 
            data['chart']=true;
            datar.push(data);
        }
    });

    //set total
    var data = new Array();
    data['id'] = 'Tot';
    data['item'] = window.lang.translate('Total');
    data['unit'] = 'GWh';
    for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(ccdata['Tot_' + allyear[y]]);} 
    data['readonly']=true;
    data['css']='readonly bold';
    data['chart']=true;
    datar.push(data);
    return datar;

}

function CalculateTotal(a, year) {
    var coef = 0;
    var total = 0;
    for (var i = 0; i < a.length - 1; i++) {
        if (a[i][year] != "") {
            coef = a[i][year];
            total += parseFloat(coef);
        }
    }
    return total;
}

function showgrid(results) {
	var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdatafinaldemand(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');

    CreateGrid(allyear,cv,grid);

    grid.cellEditEnded.addHandler(function(s, e) {
        var year = grid.columns[e.col].header;
        totalbackground = CalculateTotal(grid.itemsSource.items, year.toString());
        grid.setCellData(grid.rows.length-1, e.col, totalbackground);
        totalcolumn = e.col;
        endEdit = true;

    });

}