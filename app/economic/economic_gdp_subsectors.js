var total_text=window.lang.translate('Total');

//--end first grid

//--start second grid
function getdatagdpsubsectors(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var addata = results['adData'];
    var counttypechunk=[];
    var datar = [];
    jQuery.each(maintype, function (i, maintypes) {
        if (maintypes['gdp'] == 'Y') {
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(maintypes['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            var data = new Array();
            data['conf'] = '0';
            data['id'] = maintypes['id'];
            data['item'] = maintypes['value'];
            data['unit'] = '';
            data['css']='readonly bold';
            data['readonly']=true;
            for (var y = 0; y < allyear.length; y++) {data[' ' + allyear[y]] = '';}
            datar.push(data);

            for (j = 0; j < typechunk.length; j++) {
                var item = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                var data = new Array();
                if (j == typechunk.length - 1 && typechunk.length > 0) {
                    data['conf'] = '000' + typechunk[j];
                    data['css']='readonly';
                    data['readonly']=true;
                } else {
                    data['conf'] = maintypes['id'];
                }

                data['id'] = typechunk[j];
                data['item'] = item;
                data['unit'] = '%';
                data['chart']=false;
                if (typechunk.length == 1) {
                    for (var y = 0; y < allyear.length; y++) {data[' ' + allyear[y]] = isNumber('100');}
                } else {
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(addata[typechunk[j] + '_' + allyear[y]]);}
                }
                datar.push(data);


            }
            counttypechunk[maintypes['id'] + 'T'] = typechunk.length;
            var data = new Array();
            data['conf'] = 'Total'
            data['id'] = maintypes['id'] + 'T';
            data['item'] = total_text;
            data['unit'] = '%';
            data['css']='readonly bold';
            data['readonly']=true;
            if (typechunk.length == 1) {
                for (var y = 0; y < allyear.length; y++) {data[' ' + allyear[y]] = isNumber('100');}
            } else {
                for (var y = 0; y < allyear.length; y++) {data[' ' + allyear[y]] = isNumber(addata[maintypes['id'] + 'T' + allyear[y]]);}
            }
            datar.push(data);

        }
    });
    datar.push(counttypechunk);
    return datar;
}

function CalculateTotal(cv, year, sumindicator) {
    var total = 0;
    jQuery.each(cv, function (j, valuefromgrid) {
        if (valuefromgrid[year] != null && valuefromgrid[year] != "" && valuefromgrid['conf'] == sumindicator.slice(0, -1)) {
            total += parseFloat(valuefromgrid[year]);
        }
    })
    return total;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var data=getdatagdpsubsectors(results);
    var counttypechunk=data[data.length-1];
    data.splice(-1,1);
    var cv = new wijmo.collections.CollectionView(data);
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');

    CreateGrid(allyear,cv,grid);

    grid.cellEditEnded.addHandler(function (s, e) {
        if(e.col<s.columns.length-1){
        var year = grid.columns[e.col].header;
        jQuery.each(cv.items, function (j, valuefromgrid) {

            if (valuefromgrid['conf'] == 'Total') {
                var total = CalculateTotal(grid.itemsSource.items, year.toString(), valuefromgrid['id']);
                var lasttypechunk = 0;
                if (counttypechunk[valuefromgrid['id']] > 0) {
                    lasttypechunk = 100 - total;

                    if (lasttypechunk >= 0) {
                        grid.setCellData(j - 1, e.col, lasttypechunk);
                    } else {
                        grid.setCellData(j - 1, e.col, 0);
                        ShowErrorMessage('Total percent is grater then 100!')
                    }
                }
                //grid.setCellData(lastchunkindex,e.col,100-total);
                if (lasttypechunk >= 0) {
                    grid.setCellData(j, e.col, total + lasttypechunk);
                } else {
                    grid.setCellData(j, e.col, 100 - lasttypechunk);
                }
            }

        });
    }
    });
}
