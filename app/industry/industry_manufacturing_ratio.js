function getdataeratiomanuf(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var atdata = results['atData'];
    var bjdata=results['bjData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var facmtype=results['facmtype'];
    var pentype=results['pentype'];
    var unitsut=results['unitsut'];
    var datar = [];
    var data = new Array();
    data['conf'] = '00';
    data['id'] = '00';
    data['item'] = 'Efficiencies';
    data['unit'] = '';
    data['css']='readonly1 bold';
    data['readonly']=true;
    //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = ''; }
    datar.push(data);
    jQuery.each(maintype, function(i, maintypes) {
        //sector
        if (maintypes['id'] == 'Man') {
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(maintypes['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            //clients
            for (j = 0; j < typechunk.length; j++) {
                var item = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                if (bjdata['TU_' + typechunk[j]] == 'Y') {
                    var data = new Array();
                    data['conf'] = '00';
                    data['id'] = '00';
                    data['item'] = item;
                    data['unit'] = '';
                    data['css']='readonly1 bold';
                    data['readonly']=true;
                    //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                    datar.push(data);


                    jQuery.each(facmtype, function(i, facmtypes) {
                        if (bjdata['TU_' + typechunk[j]] == 'Y' && facmtypes['TY'] == 'TUM' && bjdata[facmtypes['id'] + '_' + typechunk[j]] == 'Y') {
                            var data = new Array();
                            data['conf'] = '01';
                            data['id'] = '00';
                            data['item'] = facmtypes['value'];
                            data['unit'] = '';
                            data['css']='readonly bold';
                            data['readonly']=true;
                            //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                            datar.push(data);

                            jQuery.each(pentype, function(i, pentypes) {
                                if (pentypes['id'] == 'FF' || pentypes['id'] == 'TF' || pentypes['id'] == 'MB') {
                                    if (pentypes['Man_' + facmtypes['id']] == 'Y') {
                                        var data = new Array();
                                        data['conf'] = facmtypes['id'] + '_' + typechunk[j] + '_' + pentypes['id'];
                                        data['id'] = typechunk[j] + '_' + pentypes['id'] + '_' + facmtypes['id'];
                                        data['item'] = pentypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(atdata[typechunk[j] + '_' + pentypes['id'] + '_' + facmtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    }
                                }
                            });
                        } else if (bjdata['TU_' + typechunk[j]] == 'Y' && facmtypes['TY'] == 'TUM' && bjdata['FDH_' + typechunk[j]] != 'Y' && bjdata['SG_' + typechunk[j]] != 'Y' && bjdata['SWH_' + typechunk[j]] != 'Y' && facmtypes['id'] == 'SG') {
                            jQuery.each(pentype, function(i, pentypes) {
                                if (pentypes['id'] == 'FF' || pentypes['id'] == 'TF' || pentypes['id'] == 'MB') {
                                    if (pentypes['Man_' + facmtypes['id']] == 'Y') {
                                        var data = new Array();
                                        data['conf'] = typechunk[j] + '_' + facmtypes['id'] + pentypes['id'];
                                        data['id'] = typechunk[j] + '_' + pentypes['id'] + '_' + facmtypes['id'];
                                        data['item'] = pentypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(atdata[typechunk[j] + '_' + pentypes['id'] + '_' + facmtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    }
                                }
                            });

                        }
                    });
                }
                // Others Thermal
                if (bjdata['OT_' + typechunk[j]] == 'Y' && bjdata['OT_' + typechunk[j] + '_OT'] == 'TU') {
                    var data = new Array();
                    data['conf'] = 1;
                    data['id'] = 'OT_' + typechunk[j];
                    data['item'] = item + ' - Other Thermal Uses';
                    data['unit'] = '';
                    //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = ''; }
                    datar.push(data);
                    jQuery.each(facmtype, function(i, facmtypes) {
                        if (facmtypes['id'] == 'SG') {
                            jQuery.each(pentype, function(i, pentypes) {
                                if (pentypes['id'] == 'FF' || pentypes['id'] == 'TF' || pentypes['id'] == 'MB') {
                                    if (pentypes['Man_' + facmtypes['id']] == 'Y') {
                                        var data = new Array();
                                        data['conf'] = typechunk[j] + '_' + facmtypes['id'] + pentypes['id'];
                                        data['id'] = 'OT_' + typechunk[j] + '_' + pentypes['id'] + '_' + facmtypes['id'];
                                        data['item'] = pentypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(atdata['OT_' + typechunk[j] + '_' + pentypes['id'] + '_' + facmtypes['id'] + '_' + allyear[y]]); }
                                        data['chart']=false;
                                        datar.push(data);
                                    }
                                }
                                //end pentypes
                            });

                        }
                        //end facmtypes
                    });

                }

            }
        }
    });

    var data = new Array();
    data['conf'] = '00';
    data['id'] = '00';
    data['item'] = 'Factors and ratios in Manufacturing';
    data['unit'] = '';
    data['css']='readonly1 bold';
    data['readonly']=true;
   // for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = ''; }
    datar.push(data);

    jQuery.each(maintype, function(i, maintypes) {
        if (maintypes['id'] == 'Man') {
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(maintypes['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            //clients
            for (j = 0; j < typechunk.length; j++) {
                var item = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                if (bjdata['TU_' + typechunk[j]] == 'Y' || (bjdata['OT_' + typechunk[j]] == 'TU' && bjdata['OT_' + typechunk[j] + '_OT'] == 'TU')) {
                    var data = new Array();
                    data['conf'] = '01';
                    data['id'] = '0';
                    data['item'] = item;
                    data['unit'] = '';
                    data['css']='readonly bold';
                    data['readonly']=true;
                   // for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                    datar.push(data);

                    jQuery.each(facmtype, function(i, facmtypes) {
                        if (facmtypes['PE'] == 'P') {
                            var data = new Array();
                            data['conf'] = typechunk[j] + '_' + facmtypes['id'];
                            data['id'] = typechunk[j] + '_' + facmtypes['id'];
                            data['item'] = facmtypes['value'];
                            data['unit'] = unitsut[facmtypes['id']];
                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(atdata[typechunk[j] + '_' + facmtypes['id'] + '_' + allyear[y]]); }
                            data['chart']=false;
                            datar.push(data);
                        }
                        //end facmtypes
                    });

                }
            }
            //end typechunk
        }
        //end maintypes
    });

    var data = new Array();
    data['conf'] = '00';
    data['id'] = '00';
    data['item'] = 'Factors for Pig Iron Prod & Feedstock';
    data['unit'] = '';
    data['css']='readonly1 bold';
    data['readonly']=true;
   // for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = ''; }
    datar.push(data);

    jQuery.each(facmtype, function(i, facmtypes) {
        if (facmtypes['PE'] == 'F' && (facmtypes['id'] == 'CA' || facmtypes['id'] == 'CB')) {
            //calculation with gdp is here    
            var data = new Array();
            data['conf'] = 'CH';
            data['id'] = facmtypes['id'];
            data['item'] = facmtypes['value'];
            data['unit'] = unitsut[facmtypes['id']];
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(atdata[facmtypes['id'] + '_' + allyear[y]]); }
            data['chart']=false;
            datar.push(data);
        } else if (facmtypes['PE'] == 'F' && (facmtypes['id'] == 'CC' || facmtypes['id'] == 'CD' || facmtypes['id'] == 'CE')) {
            var data = new Array();
            data['conf'] = '0'
            data['id'] = facmtypes['id'];
            data['item'] = facmtypes['value'];
            data['unit'] = unitsut[facmtypes['id']];
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(atdata[facmtypes['id'] + '_' + allyear[y]]); }
            data['chart']=false;
            datar.push(data);
        } else if (facmtypes['PE'] == 'F' && (facmtypes['id'] == 'CF' || facmtypes['id'] == 'CG')) {
            //calculation with gdp is here    
            var data = new Array();
            data['conf'] = 'CI'
            data['id'] = facmtypes['id'];
            data['item'] = facmtypes['value'];
            data['unit'] = unitsut[facmtypes['id']];
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(atdata[facmtypes['id'] + '_' + allyear[y]]); }
            data['chart']=false;
            datar.push(data);
        } else if (facmtypes['PE'] == 'F' && (facmtypes['id'] == 'CH' || facmtypes['id'] == 'CI')) {
            //calculation with gdp is here    
            var data = new Array();
            data['conf'] = '02'
            data['id'] = facmtypes['id'];
            data['item'] = facmtypes['value'];
            data['unit'] = unitsut[facmtypes['id']];
            data['css']='readonly bold';
            data['readonly']=true;
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(atdata[facmtypes['id'] + '_' + allyear[y]]);}
            data['chart']=false;
            datar.push(data);
        }
        //end facmtypes
    });
    return datar;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var addata=results['adData'];
    var cv = new wijmo.collections.CollectionView(getdataeratiomanuf(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);

    grid.cellEditEnded.addHandler(function(s, e) {
        //calculate value
        if(e.col<grid.columns.length-1){
        var year = grid.columns[e.col].header;
        var gdp = (addata['GDP_' + $.trim(year)] * 1) * (addata['Man_' + $.trim(year)] * 1) / 100 * (addata['Man_1_' + $.trim(year)] * 1) / 100;

        var cv = grid.itemsSource.items;
        var CH = 0;
        var CI = 0;
        var CA = 0;
        var CB = 0;
        var CF = 0;
        var CG = 0;
        jQuery.each(cv, function(j, valcv) {
            if (valcv['id'] == 'CH') {
                totalrowCH = j;
            }
            if (valcv['id'] == 'CI') {
                totalrowCI = j;
            }

            if (valcv['id'] == 'CA') {
                if (valcv[year] != null && valcv[year] != "") {
                    CA = valcv[year];
                }
            }

            if (valcv['id'] == 'CB') {
                if (valcv[year] != null && valcv[year] != "") {
                    CB = valcv[year];
                }
            }
            if (valcv['id'] == 'CF') {
                if (valcv[year] != null && valcv[year] != "") {
                    CF = valcv[year];
                }
            }
            if (valcv['id'] == 'CG') {
                if (valcv[year] != null && valcv[year] != "") {
                    CG = valcv[year];
                }
            }

        });
        CH = (CA * 1) + (CB * gdp);
        CI = (CF * 1) + (CG * gdp);
        grid.setCellData(totalrowCH, e.col, CH);
        grid.setCellData(totalrowCI, e.col, CI);
    }
    });
}