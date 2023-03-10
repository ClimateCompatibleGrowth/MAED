function getdatamodalsplitpt(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var awdata = results['awData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var datar = [];
    jQuery.each(maintype, function(i, val) {
        //sector
        if (val['id'] == 'Trp') {
            var data = new Array();
            data['id'] = 'M';
            data['item'] = val['value'];
            data['unit'] = '';
            data['css']='readonly bold';
            data['readonly']=true;
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(awdata['M_' + allyear[y]]); }
            data['chart']=false;
            datar.push(data);
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(val['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            //clients
            for (j = 0; j < typechunk.length; j++) {
                var typechunk_psi = doc_clients_data.getElementsByTagName(typechunk[j] + '_psi')[0].childNodes[0];
                var row_client_psi = '0';
                if (typechunk_psi != undefined) {
                    row_client_psi = typechunk_psi.nodeValue;
                }
                if (row_client_psi == 'Y') {
                    var data = new Array();
                    data['id'] = typechunk[j];
                    data['item'] = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                    data['unit'] = '%';
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(awdata[typechunk[j] + '_' + allyear[y]]); }
                    data['chart']=false;
                    datar.push(data);
                }
            }
        }
    });
    return datar;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdatamodalsplitpt(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);

    grid.cellEditEnded.addHandler(function(s, e) {
        var year = grid.columns[e.col].header;
        total = CalculateTotal(grid.itemsSource.items, year.toString());
        grid.setCellData(0, e.col, total);
    });

    grid.itemFormatter = function(panel, r, c, cell) {
        if (panel.cellType === wijmo.grid.CellType.Cell && c > 3 && c<grid.columns.length-1 && r == 0) {
            var cellName = '';
            var cellData = panel.getCellData(r, c);
            if (!check100(cellData)) {
                $(cell).addClass('pink');
                cellName = panel.getCellData(0, 1);
                ShowErrorMessage(cellName + ' <>100');
            }
        }
    }
}

function CalculateTotal(cv, year) {
    var total = 0;
    jQuery.each(cv, function(j, valuefromgrid) {
        if (valuefromgrid[year] != null && valuefromgrid[year] != "" && valuefromgrid['id'] != 'M') {
            total += parseFloat(valuefromgrid[year]);
        }
    })
    return total;
}