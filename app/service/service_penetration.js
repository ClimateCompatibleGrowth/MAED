function getdataservicepenet(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var bidata = results['biData'];
    var bjdata = results['bjData'];
    var serendtype=results['serendtype'];
    var sertype=results['sertype'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var datar = [];
    jQuery.each(serendtype, function(i, serendtypes) {
        jQuery.each(maintype, function(i, maintypes) {
            //sector
            if (maintypes['id'] == 'Ser') {
                var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
                var typechunk = doc_clients_data.getElementsByTagName(maintypes['id'] + '_A')[0].childNodes[0].nodeValue.split(',');
                for (j = 0; j < typechunk.length; j++) {
                    if (serendtypes['pen'] == 'Y' && bjdata[serendtypes['id'] + '_' + typechunk[j]] == 'Y') {
                        j = typechunk.length;
                        var data = new Array();
                        data['conf'] = '';
                        data['id'] = '0';
                        data['item'] = 'Factors for ' + serendtypes['value'];
                        data['unit'] = '';
                        data['css']='readonly1 bold';
                        data['readonly']=true;
                        //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                        datar.push(data);


                        var data = new Array();
                        data['conf'] = 'sum';
                        data['id'] = serendtypes['id'];
                        data['item'] = 'Penetration';
                        data['unit'] = '';
                        data['css']='readonly bold';
                        data['readonly']=true;
                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bidata[serendtypes['id'] + '_' + allyear[y]]);}
                        data['chart']=false;
                        datar.push(data);

                        jQuery.each(sertype, function(i, sertypes) {

                            if (sertypes[[serendtypes['id']]] == 'Y') {

                                if (sertypes['id'] == 'EL' && serendtypes['id'] == 'SH') {
                                    var data = new Array();
                                    data['conf'] = serendtypes['id'];
                                    data['id'] = 'P_' + serendtypes['id'] + '_' + sertypes['id'];
                                    data['item'] = sertypes['value'];
                                    data['unit'] = '%';
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bidata['P_' + serendtypes['id'] + '_' + sertypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);

                                    var data = new Array();
                                    data['conf'] = '';
                                    data['id'] = 'PH_' + serendtypes['id'] + '_' + sertypes['id'];
                                    data['item'] = '-- thereof: heat pump';
                                    data['unit'] = '%';
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bidata['PH_' + serendtypes['id'] + '_' + sertypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);
                                } else {
                                    var data = new Array();
                                    data['conf'] = serendtypes['id'];
                                    data['id'] = 'P_' + serendtypes['id'] + '_' + sertypes['id'];
                                    data['item'] = sertypes['value'];
                                    data['unit'] = '%';
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bidata['P_' + serendtypes['id'] + '_' + sertypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);
                                }

                            }

                        });

                        if (serendtypes['eff'] == 'Y') {
                            var data = new Array();
                            data['conf'] = '';
                            data['id'] = '00';
                            data['item'] = 'Efficiencies';
                            data['unit'] = '';
                            data['css']='readonly bold';
                            data['readonly']=true;
                            //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                            datar.push(data);

                            jQuery.each(sertype, function(i, sertypes) {
                                if (sertypes[serendtypes['id']] == 'Y' && sertypes['id'] != 'DH' && sertypes['id'] != 'SO' && sertypes['id'] != 'EL' && serendtypes['id'] != 'AC') {
                                    var data = new Array();
                                    data['conf'] = '';
                                    data['id'] = 'E_' + serendtypes['id'] + '_' + sertypes['id'];
                                    data['item'] = sertypes['eff'];
                                    data['unit'] = '%';
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bidata['E_' + serendtypes['id'] + '_' + sertypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);
                                }
                                if (sertypes[serendtypes['id']] == 'Y' && sertypes['id'] == 'FF' && serendtypes['id'] == 'AC') {
                                    var data = new Array();
                                    data['conf'] = '';
                                    data['id'] = 'E_' + serendtypes['id'] + '_' + sertypes['id'];
                                    data['item'] = 'COP non-electric AC';
                                    data['unit'] = 'ratio';
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bidata['E_' + serendtypes['id'] + '_' + sertypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);

                                }

                                if (sertypes[serendtypes['id']] == 'Y' && sertypes['id'] == 'EL' && serendtypes['id'] == 'AC') {
                                    var data = new Array();
                                    data['conf'] = '';
                                    data['id'] = 'E_' + serendtypes['id'] + '_' + sertypes['id'];
                                    data['item'] = 'COP electric AC';
                                    data['unit'] = 'ratio';
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bidata['E_' + serendtypes['id'] + '_' + sertypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);

                                }

                                //for use later
                                other = sertypes['id'];
                            });

                            if (serendtypes['id'] != 'AC') {
                                var data = new Array();
                                data['conf'] = '';
                                data['id'] = '00';
                                data['item'] = 'Others';
                                data['unit'] = '';
                                data['css']='readonly bold';
                                data['readonly']=true;
                                //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                                datar.push(data);

                                var data = new Array();
                                data['conf'] = '';
                                data['id'] = 'L_E_' + serendtypes['id'] + '_' + other;
                                data['item'] = 'Low Rise Buildings';
                                data['unit'] = '%';
                                for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bidata['L_E_' + serendtypes['id'] + '_' + other + '_' + allyear[y]]);}
                                data['chart']=false;
                                datar.push(data);

                                jQuery.each(sertype, function(i, sertypes) {
                                    if (sertypes[serendtypes['id']] == 'Y' && (sertypes['id'] == 'SO' || sertypes['id'] == 'EL')) {
                                        var unit = "%";
                                        if (sertypes['eff'].indexOf("COP") >= 0) {
                                            unit = 'ratio';
                                        }
                                        var data = new Array();
                                        data['conf'] = '';
                                        data['id'] = 'E_' + serendtypes['id'] + '_' + sertypes['id'];
                                        data['item'] = sertypes['eff'];
                                        data['unit'] = unit;
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bidata['E_' + serendtypes['id'] + '_' + sertypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);

                                    }
                                });

                            }

                        }

                    }

                }
            }
        });
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
    var cv = new wijmo.collections.CollectionView(getdataservicepenet(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');

    CreateGrid(allyear, cv,grid);

    grid.cellEditEnded.addHandler(function(s, e) {
        var year = grid.columns[e.col].header;
        var totalrow;
        jQuery.each(cv.items, function(j, valuefromgrid) {
            if (valuefromgrid['conf'] == 'sum') {
                totalrow = j;
                total = CalculateTotal(grid.itemsSource.items, year.toString(), valuefromgrid['id']);
                grid.setCellData(totalrow, e.col, total);
            }
        });
    });

    grid.itemFormatter = function(panel, r, c, cell) {
        if (panel.cellType === wijmo.grid.CellType.Cell && c > 3 && c<grid.columns.length-1) {
            var cellName = '';
            if (grid.itemsSource.items[r]['conf']=='sum') {
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

