function getdatahour(results) {
    var datar = [];
    var cddata=results['cdData'];
    var cedata=results['ceData'];
    var cjdata=results['cjData'];
    var year=results['year'];
    var idclient = results['idclient'];
    $('#nseason').val(cddata['nseason']);
    $('#ntday').val(cddata['ntday']);
    for (var i = 0; i < 24; i++) {
        var data = new Array();
        for (j = 1; j <= cddata['nseason']; j++) {
            for (k = 1; k <= cddata['ntday']; k++) {
                data['id'] = i;
                data['Season' + j + ' ' + cedata['daytype_' + k]] = isNumber(cjdata[idclient + '_'+year+'_' + j + '_' + k + '_' + i]);
            }
        }
        datar.push(data);
    }
    //total
    var data = new Array();
    for (j = 1; j <= cddata['nseason']; j++) {
        for (k = 1; k <= cddata['ntday']; k++) {
            data['id'] = window.lang.translate('Total');
            data['Season' + j + ' ' + cedata['daytype_' + k]] = isNumber(cjdata[idclient + '_'+year+'_' + j + '_' + k]);
        }
    }
    datar.push(data);
    return datar;
}

function CalculateTotal(a, column) {
    var coef = 0;
    var total = 0;
    for (var i = 0; i < a.length; i++) {
        if (a[i][column] != "") {
            if (i < 24) {
                coef = a[i][column];
                total += parseFloat(coef);
            }
        }
    }
    return total;
}

    function showgrid(results) {
        $("#chartGrid").hide();
        getClientsList(results['sectors'], 'coefficient_hourly');
        getYears(results['allyear'], 'coefficient_hourly');
        var cv = new wijmo.collections.CollectionView(getdatahour(results));
        var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
        
        var cddata=results['cdData'];
        var cedata=results['ceData'];

        var cols=[{ header: window.lang.translate('Hour'), binding: 'id', width:50, isReadOnly:true, align:'center'}];
  
        for (j = 1; j <= cddata['nseason']; j++) {

            for (var i = 1; i <= cddata['ntday']; i++) {
                cols.push({ header:  cedata['daytype_'+i], binding: 'Season'+j+' '+cedata['daytype_'+i], width:90, dataType: 2, format: 'f'+getdecimal()});
            }
         }
        grid.initialize({
            autoGenerateColumns: false,   
            columns: cols,
            itemsSource: cv,
            allowSorting:false,
            frozenColumns : 1,
            allowMerging : wijmo.grid.AllowMerging.AllHeaders
        })
        grid.rows[24].isReadOnly = true;
        var hr = new wijmo.grid.Row();
        grid.columnHeaders.rows.push(hr);
        grid.columnHeaders.rows[0].allowMerging = true;
        grid.columnHeaders.setCellData(0, 'id', '');

        for (j = 1; j <= cddata['nseason']; j++) {
            var seasonname = cedata['seasonname_' + j];
            for (k = 1; k <= cddata['ntday']; k++) {
                grid.columnHeaders.setCellData(0, 'Season' + j + ' ' + cedata['daytype_' + k], seasonname);
                grid.columnHeaders.setCellData(1, 'Season' + j + ' ' + cedata['daytype_' + k], cedata['daytype_' + k]);
                var coln = grid.columns.getColumn('Season' + j + ' ' + cedata['daytype_' + k]);
                coln.maxWidth = 93;
            }
        }

        grid.cellEditEnded.addHandler(function(s, e) {
            var column = grid.columns[e.col]['_binding']['_parts'][0];
            totalbackground = CalculateTotal(grid.itemsSource.items, column);
            grid.setCellData(24, e.col, totalbackground);
            if (totalbackground < 23.999999 || totalbackground > 24.0000001) {
                ShowErrorMessage("Sum column " + column + " = " + totalbackground + "!");
            }
        });
    
        grid.pasted.addHandler(function(s, e) {
            grid.rows[24].isReadOnly = true;
        });
    
        grid.itemFormatter = function(panel, r, c, cell) {
            if (panel.cellType === wijmo.grid.CellType.Cell && r == 24) {
                var cellData = panel.getCellData(r, c);
                if (cellData < 23.999999 || cellData > 24.0000001) {
                    $(cell).addClass('pink');
                } else {
                    $(cell).addClass('blue');
                }
    
            }
        }
    
    }