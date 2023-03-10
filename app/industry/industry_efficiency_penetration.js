function getdatafactorpen(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var apdata = results['apData'];
    var bjdata=results['bjData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var pentype=results['pentype'];
    var datar = [];
    jQuery.each(maintype, function(i, maintypes) {
        //sector
        if (maintypes['fac'] == 'Y') {
            var data = new Array();
            data['conf'] = '00';
            data['id'] = maintypes['id'];
            data['item'] = maintypes['value'];
            data['unit'] = '';
            data['css']='readonly1 bold';
            data['readonly']=true;
          //  for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
            datar.push(data);


            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(maintypes['id'] + '_A')[0].childNodes[0].nodeValue.split(',');
            //clients
            for (j = 0; j < typechunk.length; j++) {
                var item = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                if (bjdata['TU_' + typechunk[j]] == 'Y') {
                    var data = new Array();
                    data['conf'] = '01';
                    data['id'] = typechunk[j];
                    data['item'] = item;
                    data['unit'] = '';
                    data['css']='readonly bold';
                    data['readonly']=true;
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(apdata[typechunk[j] + '_' + allyear[y]]);}
                    data['chart']=false;
                    datar.push(data);

                    jQuery.each(pentype, function(i, pentypes) {
                        if (pentypes[maintypes['id'] + '_TU'] == 'Y') {
                            var data = new Array();
                            data['conf'] = '1';
                            data['id'] = typechunk[j] + '_' + pentypes['id'];
                            data['item'] = pentypes['value'];
                            data['unit'] = '%';
                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(apdata[typechunk[j] + '_' + pentypes['id'] + '_' + allyear[y]]);}
                            data['chart']=false;
                            datar.push(data);
                        }
                    });

                }
                if (bjdata['TU_' + typechunk[j]] == 'Y' && bjdata['OT_' + typechunk[j]] == 'Y' && bjdata['OT_' + typechunk[j] + '_OT'] == 'TU') {
                    var data = new Array();
                    data['conf'] = '02';
                    data['id'] = 'OT_' + typechunk[j];
                    data['item'] = item + '-Other thermal use';
                    data['unit'] = '';
                    data['css']='readonly bold';
                    data['readonly']=true;
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(apdata['OT_' + typechunk[j] + '_' + allyear[y]]);}
                    data['chart']=false;
                    datar.push(data);

                    jQuery.each(pentype, function(i, pentypes) {
                        if (pentypes[maintypes['id'] + '_TU'] == 'Y') {
                            var data = new Array();
                            data['conf'] = '2';
                            data['id'] = 'OT_' + typechunk[j] + '_' + pentypes['id'];
                            data['item'] = pentypes['value'];
                            data['unit'] = '%';
                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(apdata['OT_' + typechunk[j] + '_' + pentypes['id'] + '_' + allyear[y]]);}
                            data['chart']=false;
                            datar.push(data);
                        }
                    });
                }
            }

        }
    });
    return datar;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdatafactorpen(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);

    grid.cellEditEnded.addHandler(function(s, e) {
        var year = grid.columns[e.col].header;
        var totalrow;
        jQuery.each(cv.items, function(j, valuefromgrid) {
            if (valuefromgrid['conf'] == '01' || valuefromgrid['conf'] == '02') {
                totalrow = j;
                total = CalculateTotal(grid.itemsSource.items, year.toString(), valuefromgrid['id'], valuefromgrid['conf']);
                grid.setCellData(totalrow, e.col, total);
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

function CalculateTotal(cv, year, sumindicator, sumindicator1) {
    var total = 0;
    jQuery.each(cv, function(j, valuefromgrid) {
        if (valuefromgrid[year] != null && valuefromgrid[year] != "" && valuefromgrid['id'].indexOf(sumindicator + '_') != -1 && valuefromgrid['conf'] == sumindicator1.slice(-1)) {
            total += parseFloat(valuefromgrid[year]);
        }
    })
    return total;
}

