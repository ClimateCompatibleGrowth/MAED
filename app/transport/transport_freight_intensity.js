function getdataenergyintesityft(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var azdata = results['azData'];
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
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(azdata[val['id'] + '_' + allyear[y]]); }
            datar.push(data);
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var row_clients_data = doc_clients_data.getElementsByTagName(val['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            //clients
            for (j = 0; j < row_clients_data.length; j++) {
                var row_clients1_data = doc_clients_data.getElementsByTagName(row_clients_data[j] + '_fr')[0].childNodes[0];
                if (row_clients1_data != undefined) {
                    if (row_clients1_data.nodeValue = 'Y') {
                        var data = new Array();
                        data['id'] = row_clients_data[j];
                        data['item'] = doc_clients_data.getElementsByTagName(row_clients_data[j])[0].childNodes[0].nodeValue;
                        data['unit'] = defaultene + '/100tkm';
                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(azdata[row_clients_data[j] + '_' + allyear[y]]); }
                        data['chart']=false;
                        datar.push(data);
                    }
                }
            }
        }
    });
    return datar;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdataenergyintesityft(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');

    CreateGrid(allyear, cv, grid);
}