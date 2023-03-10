function getdatamodalsplitpinter(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var bbdata = results['bbData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var datar = [];
    jQuery.each(maintype, function(i, val) {
        //sector
        if (val['id'] == 'Trp') {
            var data = new Array();
            data['conf'] = '01';
            data['id'] = 'M';
            data['item'] = 'Modal split of cars intercity transportation';
            data['unit'] = '';
            data['css']='readonly bold';
            data['readonly']=true;
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bbdata['M_' + allyear[y]]); }
            datar.push(data);
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(val['id'] + '_A')[0].childNodes[0].nodeValue.split(',');
            //clients
            for (j = 0; j < typechunk.length; j++) {
                var typechunk_ps = doc_clients_data.getElementsByTagName(typechunk[j] + '_ps')[0].childNodes[0];
                var typechunk_psp = doc_clients_data.getElementsByTagName(typechunk[j] + '_psp')[0].childNodes[0];
                var row_client_ps = '0';
                var row_client_psp = '0';
                if (typechunk_ps != undefined) {
                    row_client_ps = typechunk_ps.nodeValue;
                }
                if (typechunk_psp != undefined) {
                    row_client_psp = typechunk_psp.nodeValue;
                }
                if (row_client_ps == 'Y' && row_client_psp != 'Y') {
                    var data = new Array();
                    data['conf'] = '1';
                    data['id'] = typechunk[j];
                    data['item'] = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                    data['unit'] = '%';
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bbdata[typechunk[j] + '_' + allyear[y]]);}
                    data['chart']=false;
                    datar.push(data);
                }
            }

            var data = new Array();
            data['conf'] = '02';
            data['id'] = 'P';
            data['item'] = 'Modal split of public intercity transportation';
            data['unit'] = '';
            data['css']='readonly bold';
            data['readonly']=true;
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bbdata['M_' + allyear[y]]);}
            data['chart']=false;
            datar.push(data);

            //clients
            for (j = 0; j < typechunk.length; j++) {
                var typechunk_ps = doc_clients_data.getElementsByTagName(typechunk[j] + '_ps')[0].childNodes[0];
                var typechunk_psp = doc_clients_data.getElementsByTagName(typechunk[j] + '_psp')[0].childNodes[0];
                var row_client_ps = '0';
                var row_client_psp = '0';
                if (typechunk_ps != undefined) {
                    row_client_ps = typechunk_ps.nodeValue;
                }
                if (typechunk_psp != undefined) {
                    row_client_psp = typechunk_psp.nodeValue;
                }
                if (row_client_ps == 'Y' && row_client_psp == 'Y') {
                    var data = new Array();
                    data['conf'] = '2';
                    data['id'] = typechunk[j];
                    data['item'] = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                    data['unit'] = '%';
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bbdata[typechunk[j] + '_' + allyear[y]]); }
                    data['chart']=false;
                    datar.push(data);
                }
            }
        }
    });
    return datar;
}

function CalculateTotal(cv, year, sumindicator) {
    var total = 0;
    jQuery.each(cv, function(j, valuefromgrid) {
        if (valuefromgrid[year] != null && valuefromgrid[year] != "" && valuefromgrid['conf'] == sumindicator) {
            total += parseFloat(valuefromgrid[year]);
        }
    })
    return total;
}

function showgrid(results) {
    var allyear = results['allyear'];    
    var cv = new wijmo.collections.CollectionView(getdatamodalsplitpinter(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    
    CreateGrid(allyear,cv,grid);

    grid.cellEditEnded.addHandler(function(s, e) {
        var year = grid.columns[e.col].header;
        jQuery.each(cv.items, function(j, valuefromgrid) {
            if (valuefromgrid['conf'] == '01' || valuefromgrid['conf'] == '02') {
                totalrow = j;
                grid.setCellData(j, e.col, CalculateTotal(grid.itemsSource.items, year.toString(), valuefromgrid['conf'].slice(-1)));
            }
        });
    });

    grid.itemFormatter = function(panel, r, c, cell) {
        if (panel.cellType === wijmo.grid.CellType.Cell && c > 3 && c<grid.columns.length-1) {
            var cellName = '';
            if (grid.itemsSource.items[r]['conf']=='01' || grid.itemsSource.items[r]['conf']=='02') {
                var cellData = panel.getCellData(r, c);
                if (!check100(cellData)) {
                    $(cell).addClass('pink');
                    cellName = panel.getCellData(r, 2);
                    ShowErrorMessage(cellName + ' <>100');
                }
            }
        }
    }
}
