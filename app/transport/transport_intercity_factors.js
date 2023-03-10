function getdataloadfactorpinter(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var badata = results['baData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var unituy=results['unituy'];
    var unituz=results['unituz'];

    var datar = [];
    var data = new Array();
    data['id'] = '0';
    data['item'] = 'Factor for cars intercity transportation';
    data['unit'] = '';
    data['css']='readonly bold';
    data['readonly']=true;
  //  for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
    datar.push(data);

    var data = new Array();
    data['id'] = 'Dist';
    data['item'] = 'Distance travelled';
    data['unit'] = 'km/cap/yr';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(badata['Dist_' + allyear[y]]); }
    data['chart']=false;
    datar.push(data);

    var data = new Array();
    data['id'] = 'CWN';
    data['item'] = 'Car Ownership';
    data['unit'] = unituy;
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(badata['CWN_' + allyear[y]]); }
    data['chart']=false;
    datar.push(data);

    var data = new Array();
    data['id'] = 'CKM';
    data['item'] = 'Distance travelled by car';
    data['unit'] = unituz;
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(badata['CKM_' + allyear[y]]); }
    data['chart']=false;
    datar.push(data);

    var data = new Array();
    data['id'] = '0';
    data['item'] = 'Load factors (person per mode type)';
    data['unit'] = '';
    data['css']='readonly bold';
    data['readonly']=true;
    //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
    datar.push(data);

    jQuery.each(maintype, function(i, val) {
        //sector
        if (val['id'] == 'Trp') {
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(val['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            //clients
            for (j = 0; j < typechunk.length; j++) {
                var typechunk_ps = doc_clients_data.getElementsByTagName(typechunk[j] + '_ps')[0].childNodes[0];
                var row_client_ps = '0';
                if (typechunk_ps != undefined) {
                    row_client_ps = typechunk_ps.nodeValue;
                }
                var item=doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                var airplane = doc_clients_data.getElementsByTagName(typechunk[j]+'_plane')[0].childNodes[0];
                var airplanevalue='N';
                if (airplane != undefined) {
                    airplanevalue = airplane.nodeValue;
                }
                var unit = 'cap';
                //Air planes
                if (airplanevalue=='Y') {
                    unit = '%occupied';
                }
                
                var cars = doc_clients_data.getElementsByTagName(typechunk[j]+'_car')[0].childNodes[0];
                var carsvalue='N';
                if (cars != undefined) {
                    carsvalue = cars.nodeValue;
                }

                if (row_client_ps == 'Y' && carsvalue != 'Y') {
                    var data = new Array();
                    data['id'] = typechunk[j];
                    data['item'] = item;
                    data['unit'] = unit;
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(badata[typechunk[j] + '_' + allyear[y]]); }
                    data['chart']=false;
                    datar.push(data);
                }
            }
        }
    });
    
    var data = new Array();
    data['id'] = 'Car';
    data['item'] = 'Cars';
    data['unit'] = 'cap';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(badata['Car_' + allyear[y]]); }
    data['chart']=false;
    datar.push(data);
    return datar;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdataloadfactorpinter(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    
    CreateGrid(allyear,cv,grid);
}
