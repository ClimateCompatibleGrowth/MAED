function getdatagenfreight(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var audata = results['auData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var unittype=results['unittype'];
    var unittype2=results['unittype2'];
    var defaultcurrency=results['defaultcurrency'];
    var datar = [];
    jQuery.each(maintype, function(i, val) {
        //sector
        if (val['gdp'] == 'Y') {
            var data = new Array();
            data['id'] = val['id'];
            data['item'] = val['value'];
            data['unit'] = '';
            data['css']='readonly bold';
            data['readonly']=true;
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(audata[val['id'] + '_' + allyear[y]]);}
           // data['chart']=false;
            datar.push(data);
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var row_clients_data = doc_clients_data.getElementsByTagName(val['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            //clients
            for (j = 0; j < row_clients_data.length; j++) {
                var data = new Array();
                data['id'] = row_clients_data[j];
                data['item'] = doc_clients_data.getElementsByTagName(row_clients_data[j])[0].childNodes[0].nodeValue;
                data['unit'] = unittype.replace('US$',defaultcurrency);
                for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(audata[row_clients_data[j] + '_' + allyear[y]]); }
                data['chart']=false;
                datar.push(data);
            }
        }
    });
    var data = new Array();
    data['id'] = 'Base';
    data['item'] = 'Base value';
    data['unit'] = unittype2;
    data['css']='bold';
    datar.push(data);
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(audata['Base_' + allyear[y]]);}
    return datar;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdatagenfreight(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');

    CreateGrid(allyear,cv,grid);
}