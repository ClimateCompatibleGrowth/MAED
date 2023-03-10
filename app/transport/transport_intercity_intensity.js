function getdataenergyintensitypinter(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var bcdata = results['bcData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var defaultene=results['defaultene'];
    var datar = [];
    jQuery.each(maintype, function(i, val) {
        //sector
        if (val['id'] == 'Trp') {
            var data = new Array();
            data['id'] = val['id'];
            data['item'] = val['value'];
            data['unit'] = '';
            data['css']='readonly bold';
            data['readonly']=true;
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bcdata[val['id'] + '_' + allyear[y]]); }
            datar.push(data);
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(val['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            //clients
            for (j = 0; j < typechunk.length; j++) {
                var typechunk_ps = doc_clients_data.getElementsByTagName(typechunk[j] + '_ps')[0].childNodes[0];
                var row_client_ps = '0';
                if (typechunk_ps != undefined) {
                    row_client_ps = typechunk_ps.nodeValue;
                }
                var item = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                var airplane = doc_clients_data.getElementsByTagName(typechunk[j]+'_plane')[0].childNodes[0];
                var airplanevalue='N';
                if (airplane != undefined) {
                    airplanevalue = airplane.nodeValue;
                }
                var unit = '100km';
                if (airplanevalue=='Y') {
                    unit = '1000seatkm';
                }

                if (row_client_ps == 'Y') {
                    var data = new Array();
                    data['id'] = typechunk[j];
                    data['item'] = item;
                    data['unit'] = defaultene + '/' + unit;
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bcdata[typechunk[j] + '_' + allyear[y]]); }
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
    var cv = new wijmo.collections.CollectionView(getdataenergyintensitypinter(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');

    CreateGrid(allyear,cv,grid);
}