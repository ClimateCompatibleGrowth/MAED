function getdataloadfactorpt(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var axdata = results['axData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var datar = [];
    var data = new Array();
    data['id'] = 'Dist';
    data['item'] = 'Distance travelled';
    data['unit'] = 'km/cap/day';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(axdata['Dist_' + allyear[y]]); }
    data['chart']=false;
    datar.push(data);
    jQuery.each(maintype, function(i, val) {
        //sector
        if (val['id'] == 'Trp') {
            var data = new Array();
            data['id'] = val['id'];
            data['item'] = val['value'];
            data['unit'] = '';
            data['css']='readonly bold';
            data['readonly']=true;
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(axdata[val['id'] + '_' + allyear[y]]); }
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
                    data['unit'] = 'cap';
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(axdata[typechunk[j] + '_' + allyear[y]]); }
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
    var cv = new wijmo.collections.CollectionView(getdataloadfactorpt(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);
}