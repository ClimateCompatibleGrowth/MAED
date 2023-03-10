function getdataenergyintensityss(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var bhdata = results['bhData'];
    var bjdata = results['bjData'];
    var serendtype=results['serendtype'];
    var casestudyid = results['casestudyid'];
    var defaultene = results['defaultene'];
    var defaultcurrency=results['defaultcurrency'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var datar = [];
    jQuery.each(maintype, function(i, val) {
        //sector
        if (val['id'] == 'Ser') {
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(val['id'] + '_A')[0].childNodes[0].nodeValue.split(',');
            jQuery.each(serendtype, function(i, sval) {
                if (sval['id'] != 'SH' && sval['id'] != 'AC') {
                    var data = new Array();
                    data['id'] = '0';
                    data['item'] = sval['value'];
                    data['unit'] = '';
                    data['css']='readonly bold';
                    data['readonly']=true;
                    //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                    datar.push(data);

                    for (j = 0; j < typechunk.length; j++) {
                        if (bjdata[sval['id'] + '_' + typechunk[j]] == 'Y') {
                            var abdata = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                            var data = new Array();
                            data['id'] = sval['id'] + '_' + typechunk[j];
                            data['item'] = abdata + ' ('+sval['id']+')';
                            data['unit'] = defaultene + '/' + defaultcurrency;
                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bhdata[sval['id'] + '_' + typechunk[j] + '_' + allyear[y]]);}
                            data['chart']=false;
                            datar.push(data);
                            if (bjdata['OT_' + typechunk[j]] == 'Y' && bjdata['OT_' + typechunk[j] + '_OT'] == sval['id']) {
                                var data = new Array();
                                data['id'] = 'OT_' + sval['id'] + '_' + typechunk[j];
                                data['item'] = abdata + '-Others';
                                data['unit'] = defaultene + '/' + defaultcurrency;
                                for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bhdata['OT_' + sval['id'] + '_' + typechunk[j] + '_' + allyear[y]]);}
                                data['chart']=false;
                                datar.push(data);
                            }
                        }
                    }
                }
            });
        }
    });

    return datar;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdataenergyintensityss(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear, cv,grid);
}