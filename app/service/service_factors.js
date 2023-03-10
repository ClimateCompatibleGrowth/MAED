function getdatafactorss(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var bgdata = results['bgData'];
    var bjdata = results['bjData'];
    var casestudyid = results['casestudyid'];
    var defaultpop = results['defaultpop'];
    var defaultene = results['defaultene'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var unittypeKU = results['unittypeKU'];
    var unittypeNU = results['unittypeNU'];

    var datar = [];

    var data = new Array();
    data['id'] = '0';
    data['item'] = 'Basic data for useful energy demand';
    data['unit'] = '';
    data['css']='readonly bold';
    data['readonly']=true;
    //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
    datar.push(data);

    var data = new Array();
    data['id'] = 'B_LF';
    data['item'] = 'Labour force in Service Sector';
    data['unit'] = '%';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bgdata['B_LF_' + allyear[y]]);}
    data['chart']=false;
    datar.push(data);

    var data = new Array();
    data['id'] = 'B_FA';
    data['item'] = 'Floor area per employee';
    data['unit'] = unittypeKU;
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bgdata['B_FA_' + allyear[y]]);}
    data['chart']=false;
    datar.push(data);

    var data = new Array();
    data['id'] = 'T_LF';
    data['item'] = 'Total labour force in Service Sector';
    data['unit'] = defaultpop;
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bgdata['T_LF_' + allyear[y]]);}
    data['css']='readonly';
    data['readonly']=true;
    data['chart']=false;
    datar.push(data);

    var data = new Array();
    data['id'] = 'T_FA';
    data['item'] = 'Total Floor Area';
    data['unit'] = 'Million m2';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bgdata['T_FA_' + allyear[y]]); }
    data['css']='readonly';
    data['readonly']=true;
    data['chart']=false;
    datar.push(data);

    jQuery.each(maintype, function(i, val) {
        //sector
        if (val['id'] == 'Ser') {
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(val['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            //clients
            for (j = 0; j < typechunk.length; j++) {
                if (bjdata['SH_' + typechunk[j]] == 'Y') {
                    var data = new Array();
                    data['id'] = '0';
                    data['item'] = 'Factors for Space Heating';
                    data['unit'] = '';
                    data['css']='readonly bold';
                    data['readonly']=true;
                //    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = ''; }
                    datar.push(data);

                    var data = new Array();
                    data['id'] = 'F_SA';
                    data['item'] = 'Share of area requiring SH';
                    data['unit'] = '%';
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bgdata['F_SA_' + allyear[y]]);}
                    datar.push(data);
                    data['chart']=false;

                    var data = new Array();
                    data['id'] = 'F_AA';
                    data['item'] = 'Area actually heated';
                    data['unit'] = '%';
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bgdata['F_AA_' + allyear[y]]);}
                    data['chart']=false;
                    datar.push(data);

                    var data = new Array();
                    data['id'] = 'F_SS';
                    data['item'] = 'Specific energy requirements';
                    data['unit'] = defaultene + unittypeNU;
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bgdata['F_SS_' + allyear[y]]);}
                    data['chart']=false;
                    datar.push(data);
                    j = typechunk.length;
                }

            }
        }
    });

    jQuery.each(maintype, function(i, val) {
        //sector
        if (val['id'] == 'Ser') {
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(val['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            //clients
            for (j = 0; j < typechunk.length; j++) {
                if (bjdata['AC_' + typechunk[j]] == 'Y') {
                    var data = new Array();
                    data['id'] = '0';
                    data['item'] = 'Factors for Air conditioning';
                    data['unit'] = '';
                    data['css']='readonly bold';
                    data['readonly']=true;
                    //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                    datar.push(data);

                    var data = new Array();
                    data['id'] = 'F_AC';
                    data['item'] = 'Floor area with AC';
                    data['unit'] = '%';
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bgdata['F_AC_' + allyear[y]]);}
                    data['chart']=false;
                    datar.push(data);

                    var data = new Array();
                    data['id'] = 'F_SC';
                    data['item'] = 'Specific cooling requirements';
                    data['unit'] = defaultene + unittypeNU;;
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bgdata['F_SC_' + allyear[y]]);}
                    data['chart']=false;
                    datar.push(data);
                    j = typechunk.length;
                }

            }
        }
    });
    return datar;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var acdata=results['acData'];
    var cv = new wijmo.collections.CollectionView(getdatafactorss(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');

    CreateGrid(allyear, cv,grid);

    grid.cellEditEnded.addHandler(function(s, e) {
        //calculate value
        var year = grid.columns[e.col].header;
        var alb = acdata['ALF_' + year.trim()];
        var cv = grid.itemsSource.items;
        var TLF = 0;
        var TFA = 0;
        jQuery.each(cv, function(j, valcv) {
            if (valcv['id'] == 'T_LF') {
                totalcoltlf = j;
            }
            if (valcv['id'] == 'T_FA') {
                totalcoltfa = j;
            }

            if (valcv['id'].indexOf('B_LF') != -1) {
                if (valcv[year] != null && valcv[year] != "") {
                    TLF = parseFloat(alb) * parseFloat(valcv[year]) / 100;
                }
            }

            if (valcv['id'].indexOf('B_FA') != -1) {
                if (valcv[year] != null && valcv[year] != "") {
                    TFA = parseFloat(TLF) * parseFloat(valcv[year]);
                }
            }
        })

        grid.setCellData(totalcoltlf, e.col, TLF);
        grid.setCellData(totalcoltfa, e.col, TFA);
    });
}