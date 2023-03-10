function getdataweek(results) {
    var datar = [];
    var chdata=results['chData'];
    var allyear=results['allyear'];
    var idclient = results['idclient'];

    for (var i = 1; i < 54; i++) {
        var data = new Array(1);
        data["id"] = i;
        for(var j=0;j<allyear.length;j++){
            var year=allyear[j];
            data[' '+year]=isNumber(chdata[year][idclient+ '_'+ year+'_'+i]);  
        }
        datar.push(data);
    }
    //set total
    var data = new Array(1);
    data["id"] = window.lang.translate('Total');
    for(var j=0;j<allyear.length;j++){
        var year=allyear[j];
        data[' '+year]=isNumber(chdata[year][idclient+ '_'+ year]);  
    }
    datar.push(data);
    return datar;
}

function showgrid(results) {
    $('#chartGrid').hide();
    getClientsList(results['sectors'], 'coefficient_weekly');
    var cv = new wijmo.collections.CollectionView(getdataweek(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    var allyear=results['allyear'];

    var cols=[{ header: window.lang.translate('Week'), binding: 'id', width:50, isReadOnly:true, align:'center'}];
    
      for (var y = 0; y < allyear.length; y++) {
          cols.push({ header:  ' ' + allyear[y], binding: ' ' + allyear[y], width:100, dataType: 2, format: 'f'+getdecimal(0)});
      }
    
      grid.initialize({
          autoGenerateColumns: false,   
          columns: cols,
          itemsSource: cv,
          allowSorting:false,
          frozenColumns : 1
      })
    
      grid.rows[53].isReadOnly = true;
      grid.cellEditEnded.addHandler(function(s, e) {
          var year = grid.columns[e.col].header;
          totalbackground = CalculateTotal(grid.itemsSource.items, year.toString());
          grid.setCellData(53, e.col, totalbackground);
          if (totalbackground < 52.999999 || totalbackground > 53.0000001) {
              ShowErrorMessage("Sum = " + totalbackground + " (year "+year.toString()+")!");
          }
      });
  
      grid.pasted.addHandler(function(s, e) {
          grid.rows[53].isReadOnly = true;
      });

    grid.itemFormatter = function(panel, r, c, cell) {
        var flex = panel.grid;
        var cellData = flex.getCellData(53, 1);
        if (panel.cellType == wijmo.grid.CellType.ColumnHeader) {
            if (cellData < 52.99999 || cellData > 53) {
                $(cell).addClass('pink');
            } else {
                $(cell).addClass('silver');
            }
        }

        if (panel.cellType === wijmo.grid.CellType.Cell && r == 53) {
            if (cellData < 52.99999 || cellData > 53) {
                $(cell).addClass('pink');
            } else {
                $(cell).addClass('blue');
            }
        }
    }
}

function CalculateTotal(a, year) {
    var coef = 0;
    var total = 0;
    for (var i = 0; i < a.length; i++) {
        if (a[i][year] != "") {
            if (i < 53) {
                coef = a[i][year];
                total += parseFloat(coef);
            }
        }
    }
    return total;
}