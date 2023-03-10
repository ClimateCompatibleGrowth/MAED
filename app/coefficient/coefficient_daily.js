function getdataday(results) {
    var lng = Cookies.get('langCookie');
    var datar = [];
    var daytype=results['daytype'];
    var cidata=results['ciData'];
    var year=results['year'];
    var idclient = results['idclient'];

    for (var i = 1; i < 54; i++) {
        var data = new Array(10);
        data["id"] = i;
        for (var j = 0; j < daytype.length; j++)
        if (daytype[j]["type"] == "D") {
            data[daytype[j]["value_"+lng]]=isNumber(cidata[idclient + '_' + year + '_' + daytype[j]["id"] + '_' + i]);  
        }
        //set total
        data['Total']=isNumber(cidata[idclient+ '_'+i+'_'+ year]); 
        datar.push(data); 
    }
    return datar;
}


function showgrid(results) {
    $('#chartGrid').hide();
    getClientsList(results['sectors'], 'coefficient_daily');
    getYears(results['allyear'], 'coefficient_daily');
    var daytype=results['daytype'];
    var lng = Cookies.get('langCookie');
    var cv = new wijmo.collections.CollectionView(getdataday(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    var allyear=results['allyear'];

    var cols=[{ header: window.lang.translate('Week'), binding: 'id', width:50, isReadOnly:true, align:'center'}];
    
    for (var j = 0; j < daytype.length; j++){
      if (daytype[j]["type"] == "D") {
        cols.push({ header:  daytype[j]["value_"+lng], binding: daytype[j]["value_"+lng], width:90, dataType: 2, format: 'f'+getdecimal()});
      }
    }
    cols.push({ header: window.lang.translate('Total'), binding: 'Total', width:50, align:'center'});
    //cols.push({ header: 'Chart', binding: 'chart', dataType:3, width:50 });

    grid.initialize({
        autoGenerateColumns: false,   
        columns: cols,
        itemsSource: cv,
        allowSorting:false,
        frozenColumns : 1
    })
    
    grid.cellEditEnded.addHandler(function(s, e) {
        totalbackground = CalculateTotal(grid.itemsSource.items, e.row, daytype);
        grid.setCellData(e.row, 8, totalbackground);
        var row = parseFloat(e.row) + 1;
        if (totalbackground < 6.999999 || totalbackground > 7.0000001) {
            ShowErrorMessage("Sum row " + row + " = " + totalbackground + "!");
        }
        endEdit = true;
    });

    grid.itemFormatter = function(panel, r, c, cell) {
        if (panel.cellType === wijmo.grid.CellType.Cell && c == 8) {
            var cellData = panel.getCellData(r, c);
            if (cellData < 6.99999 || cellData > 7) {
                $(cell).addClass('pink');
            } else {
                $(cell).addClass('blue');
            }
        }
    }
}

function CalculateTotal(a, row, daytype) {
    var lng = Cookies.get('langCookie');
    var coef = 0;
    var total = 0;
    for (var j = 0; j < daytype.length; j++) {
        if (daytype[j]["type"] == "D") {
            if (a[row][daytype[j]["value_"+lng]] != "") {
                coef = a[row][daytype[j]["value_"+lng]];
                total += parseFloat(coef);
            }
        }
    }
    return total;
}