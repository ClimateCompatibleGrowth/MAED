var total_text=window.lang.translate('Total');

function getdatagdp(results) {
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var addata = results['adData'];
    var acdata = results['acData'];
    var defaultcurrency = results['defaultcurrency'];
    var defaultgdp = results['defaultgdp'];
 
    var gdp_text=window.lang.translate('GDP');
    var gdp_rate= window.lang.translate('GDP Growth rate');
    var gdp_per_capita=window.lang.translate('GDP per capita');
    var sectorial_shares=window.lang.translate('Sectorial shares of GDP');

    var doc = document
    var fragment = doc.createDocumentFragment();
    for (var y = 0; y < allyear.length; y++) {
        var population = document.createElement("input");
        population.setAttribute("type", "hidden");
        population.setAttribute("id", 'Pop_'+ allyear[y]);
        population.setAttribute("value", acdata['Pop_' + allyear[y]]);
        fragment.appendChild(population);
    }
    doc.getElementById('hiddenfields').appendChild(fragment);

    var datar = [];
    var data = new Array();
    data['conf'] = '';
    data['id'] = 'GDP';
    data['item'] = gdp_text;
    data['unit'] = defaultcurrency + ' ' + defaultgdp;
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(addata['GDP_' + allyear[y]]); }
    data['chart']=false;
    datar.push(data);

    var data = new Array();
    data['conf'] = '';
    data['id'] = 'GR';
    data['item'] = gdp_rate;
    data['unit'] = '% p.a.';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(addata['GR_' + allyear[y]]); } 
    data[' ' + allyear[0]] = '-';
    data['chart']=false;
    datar.push(data);

    var data = new Array();
    data['conf'] = '';
    data['id'] = 'CAP';
    data['item'] = gdp_per_capita;
    data['unit'] = defaultcurrency + '/Cap';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(addata['CAP_' + allyear[y]]); } 
    data['chart']=false;
    data['css']="readonly";
    data['readonly']=true;
    datar.push(data);

    var data = new Array();
    data['conf'] = '';
    data['id'] = '';
    data['item'] = sectorial_shares;
    data['unit'] = '';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = ''; }
    data['readonly']=true;
    datar.push(data);

    jQuery.each(maintype, function (i, maintypes) {
        if (maintypes['gdp'] == 'Y') {
            var data = new Array();
            data['conf'] = 'MT';
            data['id'] = maintypes['id'];
            data['item'] = maintypes['value'];
            data['unit'] = '%';
            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(addata[maintypes['id'] + '_' + allyear[y]]); }
            data['chart']=false;
            if(maintypes['id']=="Ene"){
                data['css']="readonly";
                data['readonly']=true;
            }
            datar.push(data);

        }
    });

    var data = new Array();
    data['conf'] = '';
    data['id'] = 'MT';
    data['item'] = total_text;
    data['unit'] = '%';
    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(addata['MT_' + allyear[y]]); }
    data['chart']=false;
    data['css']="readonly bold";
    data['readonly']=true;
    datar.push(data);

    return datar;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var fill_demography_data=results['fill_demography_data'];
    var ene = 0;
    var defaultgdp = results['defaultgdp'];

    var cv = new wijmo.collections.CollectionView(getdatagdp(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);

    grid.beginningEdit.addHandler(function (s, e) {
        if ((e.col > 4 && e.col<s.columns.length-1 && e.row == 0) || 
        (e.col == 4 && e.row == 1  && e.col<s.columns.length-1) || 
        (e.row ==  3  && e.col<s.columns.length-1) ||
        (e.col > 3 && grid.itemsSource.items[e.row]['readonly'] == true && e.col<s.columns.length-1))
        {
            e.cancel = true;
        }
    });

    grid.cellEditEnded.addHandler(function (s, e) {
        if(e.col<s.columns.length-1){
        var year = grid.columns[e.col].header;
        var yeartrim = $.trim(year);
        var start = allyear.indexOf(yeartrim);
        var Pop = [];
        var GDP = [];
        var CAP = [];
        var GR = [];
        var PopGroRate = [];
        var GDP_Prev = [];

        jQuery.each(cv.items, function (j, valuefromgrid) {
            
            if(valuefromgrid['id'] == 'Ene'){ ene=j;}
            var i = 0;
            for (var y = start; y < allyear.length; y++) {
                //get values
                var yearheader = ' ' + allyear[y];
                var yearpreviousheader = ' ' + allyear[y - 1];
                var multiple = 0;
                if (defaultgdp == 'Million') {
                    multiple = 1;
                } else if (defaultgdp == 'Billion') {
                    multiple = 1000;
                } else if (defaultgdp == 'Trillion') {
                    multiple = 1000000;
                }
                Pop[y] = $('#Pop_' + allyear[y]).val();

                if (valuefromgrid['id'] == 'GDP') {
                    GDP[y] = valuefromgrid[yearheader];
                    GDP_Prev[y] = valuefromgrid[yearpreviousheader];
                } else if (valuefromgrid['id'] == 'GR') {
                    GR[y] = valuefromgrid[yearheader];
                } else if (valuefromgrid['id'] == 'CAP') {
                    CAP[y] = valuefromgrid[yearheader];
                }

                //GDP for next year          
                if (valuefromgrid['id'] == 'GDP') {

                    var PopGroRate1 = grid.getCellData(1, e.col + i);
                    if (PopGroRate1 == '' || PopGroRate1 == '-') {
                        PopGroRate1 = 0;
                    }
                    if (e.col == 4) {
                        if (PopGroRate1 == 0) {
                            var gdpstart = grid.getCellData(j, e.col);
                            grid.setCellData(j, e.col + i, gdpstart);
                        } else {
                            var yeardiff = allyear[y] - allyear[y - 1];
                            var growthrate = 1 + (PopGroRate1 * 1 / 100);
                            growthrate = (GDP_Prev[y] * 1) * (Math.pow(growthrate, yeardiff));
                            grid.setCellData(j, e.col + i, growthrate);
                        }
                    } else if (e.col > 4) {
                        var yeardiff = allyear[y] - allyear[y - 1];
                        var growthrate = 1 + (PopGroRate1 * 1 / 100);
                        growthrate = (GDP_Prev[y] * 1) * (Math.pow(growthrate, yeardiff));
                        grid.setCellData(j, e.col + i, growthrate);

                    }
                }
                
                if (valuefromgrid['id'] == 'CAP') {
                    if (e.col >= 4) {
                        if (Pop[y]) {
                            var gdpvalue = grid.getCellData(0, e.col + i);
                            gdpvalue = gdpvalue / (Pop[y] * 1);
                            gdpvalue = gdpvalue * (multiple * 1);
                            grid.setCellData(j, e.col + i, gdpvalue);
                        }
                    }
                }

                i++;
            }
   
            //total
            if (valuefromgrid['id'] == 'MT') {
                var total = CalculateTotal(grid.itemsSource.items, year.toString(), valuefromgrid['id']);
                var enevalue = 100 - total;
                grid.setCellData(ene, e.col, 100 - total);
                grid.setCellData(j, e.col, total + enevalue);
            }
        });
    }
    });

    grid.itemFormatter = function (panel, r, c, cell) {
        if ((panel.cellType === wijmo.grid.CellType.Cell && c > 4 && r == 0) || (panel.cellType === wijmo.grid.CellType.Cell && c == 4 && r == 1)) {
            $(cell).addClass('readonly');
        }
    }
}

function CalculateTotal(cv, year, sumindicator) {
    var total = 0;
    jQuery.each(cv, function (j, valuefromgrid) {
        if (valuefromgrid[year] != null && valuefromgrid[year] != "" && valuefromgrid['conf'] == sumindicator && valuefromgrid['id'] != 'Ene') {
            total += parseFloat(valuefromgrid[year]);
        }
    })
    return total;
}

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

function CalculateTotal1(cv, year, sumindicator) {
    var total = 0;
    jQuery.each(cv, function (j, valuefromgrid) {
        if (valuefromgrid[year] != null && valuefromgrid[year] != "" && valuefromgrid['conf'] == sumindicator.slice(0, -1)) {
            total += parseFloat(valuefromgrid[year]);
        }
    })
    return total;
}

function showgrid1(results) {
    var allyear = results['allyear'];
    var data=getdatagdpsubsectors(results);
    var counttypechunk=data[data.length-1];
    data.splice(-1,1);
    var cv = new wijmo.collections.CollectionView(data);
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid1');

    CreateGrid(allyear,cv,grid);

    grid.cellEditEnded.addHandler(function (s, e) {
        if(e.col<s.columns.length-1){
        var year = grid.columns[e.col].header;
        jQuery.each(cv.items, function (j, valuefromgrid) {

            if (valuefromgrid['conf'] == 'Total') {
                var total = CalculateTotal1(grid.itemsSource.items, year.toString(), valuefromgrid['id']);
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
