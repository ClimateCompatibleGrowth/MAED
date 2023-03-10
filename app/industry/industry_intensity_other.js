//get data
function getdataindustryintensityother(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var bkdata = results['bkData'];
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
                if (item != undefined && bjdata['OT_' + typechunk[j]] == 'Y') {
                    var data = new Array();
                    data['conf'] = 1;
                    data['id'] = maintypes['id'];
                    data['item'] = maintypes['value'];
                    data['unit'] = '';
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                    datar.push(data);
                }

                j = typechunk.length;
            }

            for (j = 0; j < typechunk.length; j++) {
                var item = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                if (item != undefined && bjdata['OT_' + typechunk[j]] == 'Y') {

                    var bname = 'OT_' + typechunk[j] + '_OT';
                    var bunit = '';
                    var otname = '';
                    if (bjdata[bname] == 'AP') {
                        bunit = "kWh";
                        otname = " - Other Elec Spec use";
                    } else if (bjdata[bname] == 'MP') {
                        bunit = defaultene;
                        otname = " - Other Motive Power";
                    } else if (bjdata[bname] == 'TU') {
                        bunit = defaultene;
                        otname = " - Other Thermal use";
                    }

                    var data = new Array();
                    data['conf'] = 0;
                    data['id'] = typechunk[j];
                    data['item'] = item + otname;
                    data['unit'] = bunit + '/' + defaultcurrency;
                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bkdata[typechunk[j] + '_' + allyear[y]]);}
                    data['chart']=false;
                    datar.push(data);
                }
            }
        }
    });
    return datar;
}

function showgrid(results) {
    var maintype=results['maintype'];
    var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdataindustryintensityother(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);
 
    grid.cellEditEnded.addHandler(function(s, e) {
        //calculate value
        var year = grid.columns[e.col].header;
        var cv = grid.itemsSource.items;

        jQuery.each(maintype, function(i, maintypes) {
            var totalrow;
            var sum = 0;
            jQuery.each(cv, function(j, valuefromgrid) {
                    if (maintypes['id'] == valuefromgrid['id']) {
                        totalrow = j;
                    }

                    if (valuefromgrid['id'].indexOf(maintypes['id'] + '_') != -1) {
                        if (valuefromgrid[year] != null && valuefromgrid[year] != "") {
                            sum += parseFloat(valuefromgrid[year]);
                        }
                    }
                })
                //set total
            if (totalrow != null) {
                grid.setCellData(totalrow, e.col, sum);
            }
        })

    });
}