//get data
function getdataindustryintensity(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var afdata = results['afData'];
    var bjdata=results['bjData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var defaultene=results['defaultene'];
    var defaultcurrency=results['defaultcurrency'];

    var datar = [];
    jQuery.each(maintype, function(i, maintypes) {
        //sector
        if (maintypes['ind'] == 'Y') {
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(maintypes['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            //clients
            for (j = 0; j < typechunk.length; j++) {
                var item = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                if (item != undefined && bjdata['MP_' + typechunk[j]] == 'Y') {
                    var data = new Array();
                    data['conf'] = 1;
                    data['id'] = maintypes['id'];
                    data['item'] = maintypes['value'];
                    data['unit'] = '';
                    data['css']='readonly bold';
                    data['readonly']=true;
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                    datar.push(data);
                }
                j = typechunk.length;
            }

            for (j = 0; j < typechunk.length; j++) {
                var item = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                if (item != undefined && bjdata['MP_' + typechunk[j]] == 'Y') {
                    var data = new Array();
                    data['conf'] = 0;
                    data['id'] = typechunk[j];
                    data['item'] = item;
                    data['unit'] = defaultene + '/' + defaultcurrency;
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(afdata[typechunk[j] + '_' + allyear[y]]);}
                    data['chart']=false;
                    datar.push(data);
                }
            }
        }
    });
    return datar;
}

//show data
function showgrid(results) {
    var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdataindustryintensity(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);
}