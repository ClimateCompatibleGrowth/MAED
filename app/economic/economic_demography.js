//get data
function getdatademography(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var acdata = results['acData'];
    var casestudyid = results['casestudyid'];
    var defaultpop = results['defaultpop'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var datar = [];
    var data = new Array();
    data['conf'] = '';
    data['id'] = 'Pop';
    data['item'] = 'Population *';
    data['unit'] = defaultpop;
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(acdata['Pop_' + allyear[y]]); }
    data['chart']=false;
    datar.push(data);

    var data = new Array();
    data['conf'] = '';
    data['id'] = 'Pop_Gro_Rat';
    data['item'] = 'Population growth rate *';
    data['unit'] = '% per annum';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(acdata['Pop_Gro_Rat_' + allyear[y]]);}
    data[' ' + allyear[0]] = '-';
    data['chart']=false;
    datar.push(data);

    jQuery.each(maintype, function(i, maintypes) {
        if (maintypes['id'] == 'Hou') {
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            var typechunk = doc_clients_data.getElementsByTagName(maintypes['id'] + '_A')[0].childNodes[0].nodeValue.split(',');
            //clients
            for (j = 0; j < typechunk.length; j++) {
                var item = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                var data = new Array();
                data['conf'] = 'Pop' + j;
                data['id'] = typechunk[j];
                data['item'] = item + ' Population';
                data['unit'] = '%';
                for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(acdata[typechunk[j] + '_' + allyear[y] + '_Pop']); }
                data['chart']=false;
                if(j==1){
                    data['css']='readonly';
                    data['readonly']=true;
                }
                datar.push(data);

                var data = new Array();
                data['conf'] = 'Cap' + j;
                data['id'] = typechunk[j];
                data['item'] = 'Person/ '+item.toLowerCase()+' Household';
                data['unit'] = 'cap';
                for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(acdata[typechunk[j] + '_' + allyear[y] + '_Cap']); } 
                data['chart']=false;
                datar.push(data);

                var data = new Array();
                data['conf'] = 'NH' + j;
                data['id'] = typechunk[j];
                data['item'] = 'Number of '+item.toLowerCase()+' Households';
                data['unit'] = defaultpop;
                for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(acdata[typechunk[j] + '_' + allyear[y] + '_NH']); }
                data['chart']=false;
                data['css']='readonly';
                data['readonly']=true;
                datar.push(data);

            }
        }
    });

    var data = new Array();
    data['conf'] = '';
    data['id'] = 'POL';
    data['item'] = 'Potential Labour Force';
    data['unit'] = '%';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(acdata['POL_' + allyear[y]]); } 
    data['chart']=false;
    datar.push(data);


    var data = new Array();
    data['conf'] = '';
    data['id'] = 'PAL';
    data['item'] = 'Participating Labour Force';
    data['unit'] = '%';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(acdata['PAL_' + allyear[y]]); }
    data['chart']=false;
    datar.push(data);


    var data = new Array();
    data['conf'] = '';
    data['id'] = 'ALF';
    data['item'] = 'Active Labour Force';
    data['unit'] = defaultpop;
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(acdata['ALF_' + allyear[y]]); } 
    data['chart']=false;
    data['css']='readonly';
    data['readonly']=true;
    datar.push(data);


    var data = new Array();
    data['conf'] = '';
    data['id'] = 'SLP';
    data['item'] = 'Population in cities with public transport';
    data['unit'] = '%';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(acdata['SLP_' + allyear[y]]); } 
    data['chart']=false;
    datar.push(data);


    var data = new Array();
    data['conf'] = '';
    data['id'] = 'PLC';
    data['item'] = 'Population inside Large Cities';
    data['unit'] = defaultpop;
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(acdata['PLC_' + allyear[y]]); }
    data['chart']=false;
    data['css']='readonly';
    data['readonly']=true;
    datar.push(data);   

    return datar;
    
}

function showgrid(results) {
    var allyear = results['allyear'];
	var cv = new wijmo.collections.CollectionView(getdatademography(results));
	var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);

    grid.beginningEdit.addHandler(function(s, e) {
        if ((e.col > 4 && e.col<s.columns.length-1 && e.row == 0) || (e.col == 4 && e.row == 1) ||
        (e.col > 3 && grid.itemsSource.items[e.row]['readonly'] == true && e.col<s.columns.length-1)) {
            e.cancel = true;
        }
    });

    grid.cellEditEnded.addHandler(function(s, e) {
        if(e.col<s.columns.length-1){
        var year = grid.columns[e.col].header;
        var yeartrim = $.trim(year);
        var start = allyear.indexOf(yeartrim);
        var Pop = [];
        var POL = [];
        var PAL = [];
        var SLP = [];
        var Urban = [];
        var UrbanRural = [];
        var PersonUrbanRural = [];
        var PopGroRate = [];
        var Pop_Prev = [];

        jQuery.each(cv.items, function(j, valuefromgrid) {
            var i = 0;
            for (var y = start; y < allyear.length; y++) {
                //get values
                var yearheader = ' ' + allyear[y];
                var yearpreviousheader = ' ' + allyear[y - 1];
                if (valuefromgrid['id'] == 'Pop') {
                    Pop[y] = valuefromgrid[yearheader];
                    Pop_Prev[y] = valuefromgrid[yearpreviousheader];
                } else if (valuefromgrid['id'] == 'POL') {
                    POL[y] = valuefromgrid[yearheader];
                } else if (valuefromgrid['id'] == 'PAL') {
                    PAL[y] = valuefromgrid[yearheader];
                } else if (valuefromgrid['id'] == 'SLP') {
                    SLP[y] = valuefromgrid[yearheader];
                } else if (valuefromgrid['id'] == 'Pop_Gro_Rat') {
                    PopGroRate[y] = valuefromgrid[yearheader];
                } else if (valuefromgrid['conf'].slice(0, -1) == 'Pop') {
                    if (valuefromgrid['conf'] == 'Pop0') {
                        Urban[y] = valuefromgrid[yearheader];
                        if (Urban[y] == 0) {
                            grid.setCellData(j, e.col + i, 0);
                        }
                    }
                    UrbanRural[y] = valuefromgrid[yearheader];
                } else if (valuefromgrid['conf'].slice(0, -1) == 'Cap') {
                    PersonUrbanRural[y] = valuefromgrid[yearheader];
                }

                //Population
                if (valuefromgrid['conf'] == 'Pop1') {
                    grid.setCellData(j, e.col + i, parseFloat(100 - Urban[y]));
                }

                //Households
                if (valuefromgrid['conf'].slice(0, -1) == 'NH') {
                    if (Pop[y] > 0 && UrbanRural[y] > 0 && PersonUrbanRural[y] > 0) {
                        grid.setCellData(j, e.col + i, parseFloat((Pop[y] * 1 * UrbanRural[y] * 1 / 100) / PersonUrbanRural[y] * 1));
                    } else {
                        grid.setCellData(j, e.col + i, 0);
                    }
                }
                //Active LF
                if (valuefromgrid['id'] == 'ALF') {
                    grid.setCellData(j, e.col + i, parseFloat((Pop[y] * (POL[y] * 1) / 100) * ((PAL[y] * 1) / 100)));
                }
                //Popuation inside LC
                if (valuefromgrid['id'] == 'PLC') {
                    grid.setCellData(j, e.col + i, parseFloat((SLP[y] * 1) / 100) * Pop[y]);
                }

                //Population for next year          
                if (valuefromgrid['id'] == 'Pop') {

                    var PopGroRate1 = grid.getCellData(1, e.col + i);
                    if (PopGroRate1 == '' || PopGroRate1 == '-') {
                        PopGroRate1 = 0;
                    }
                    if (e.col == 4) {
                        if (PopGroRate1 == 0) {
                            var popstart = grid.getCellData(j, e.col);
                            grid.setCellData(j, e.col + i, popstart);
                        } else {
                            var yeardiff = allyear[y] - allyear[y - 1];
                            var growthrate = 1 + (PopGroRate1 * 1 / 100);
                            growthrate = (Pop_Prev[y] * 1) * (Math.pow(growthrate, yeardiff));
                            grid.setCellData(j, e.col + i, growthrate);
                        }
                    } else if (e.col > 4) {
                        var yeardiff = allyear[y] - allyear[y - 1];
                        var growthrate = 1 + (PopGroRate1 * 1 / 100);
                        growthrate = (Pop_Prev[y] * 1) * (Math.pow(growthrate, yeardiff));
                        grid.setCellData(j, e.col + i, growthrate);
                    }
                }
                i++;
            }

        });
    }
    });

    grid.itemFormatter = function(panel, r, c, cell) {
        if ((panel.cellType === wijmo.grid.CellType.Cell && c > 4 && r == 0) || (panel.cellType === wijmo.grid.CellType.Cell && c == 4 && r == 1)) {
            $(cell).addClass('readonly');
        }
    }
}