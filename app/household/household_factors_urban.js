function getdatadwellingfactorhh(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var bedata = results['beData'];
    var bjdata = results['bjData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var houtype=results['houtype'];
    var houendtype=results['houendtype'];
    var dweltype=results['dweltype'];
    var allyear = results['allyear'];
    var unit_degree_days=results['unit_degree_days'];
    var defaultene=results['defaultene'];
    var defaulteneh=results['defaulteneh'];
    var datar = [];

    jQuery.each(maintype, function(i, maintypes) {
        //sector
        if (maintypes['id'] == 'Hou') {
            var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
            //TypeChunk
            var typechunk = doc_clients_data.getElementsByTagName(maintypes['id'] + '_A')[0].childNodes[0].nodeValue.split(',');

            for (j = 0; j < 1; j++) {
                if (doc_clients_data != undefined && doc_clients_data != '') {
                    var item = doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
                    var data = new Array();
                    data['conf'] = '';
                    data['id'] = '0';
                    data['item'] = item;
                    data['unit'] = '';
                    data['css']='readonly1 bold';
                    data['readonly']=true;
                 //   for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}

                    datar.push(data);
                    
                    //SubChunk
                    var subtypechunk = doc_clients_data.getElementsByTagName(typechunk[j] + '_A')[0].childNodes[0].nodeValue.split(',');
                    //dweltype g or h
                    jQuery.each(dweltype, function(i, dweltypes) {
                        if (dweltypes['ftype'] == 'SH' && (dweltypes['id'] == 'g' || dweltypes['id'] == 'h')) {
                            var data = new Array();
                            data['id'] = '0';
                            data['item'] = dweltypes['value'];
                            data['unit'] = '';
                            data['css']='readonly1 bold';
                            data['readonly']=true;
                           // for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                            datar.push(data);


                            for (k = 0; k < subtypechunk.length; k++) {
                                if (subtypechunk != undefined && subtypechunk != '') {
                                    var item = doc_clients_data.getElementsByTagName(subtypechunk[k])[0].childNodes[0].nodeValue;
                                    var data = new Array();
                                    data['id'] = subtypechunk[k] + '_' + dweltypes['id'];
                                    data['item'] = item;
                                    data['unit'] = dweltypes['unit'];
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata[subtypechunk[k] + '_' + dweltypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);
                                }
                            }
                        }
                    });

                    //houendtype
                    jQuery.each(houendtype, function(i, houendtypes) {
                        if (houendtypes['id'] == 'SH' && bjdata['SH_' + typechunk[j]] == 'Y') {
                            var data = new Array();
                            data['id'] = '0';
                            data['item'] = 'Dwelling Factors for ' + houendtypes['value'];
                            data['unit'] = '';
                            data['css']='readonly1 bold';
                            data['readonly']=true;
                            //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                            datar.push(data);

                            var data = new Array();
                            data['id'] = 'Shr_' + typechunk[j];
                            data['item'] = 'Share of dwelling requiring SH';
                            data['unit'] = '%';
                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['Shr_' + typechunk[j] + '_' + allyear[y]]);}
                            data['chart']=false;
                            datar.push(data);

                            var data = new Array();
                            data['id'] = 'Deg_' + typechunk[j];
                            data['item'] = 'Degree-days';
                            data['unit'] = unit_degree_days + '\u00B0C';
                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['Deg_' + typechunk[j] + '_' + allyear[y]]);}
                            data['chart']=false;
                            datar.push(data);


                            //dweltype not g or h
                            jQuery.each(dweltype, function(i, dweltypes) {
                                if (dweltypes['ftype'] == 'SH' && dweltypes['id'] != 'g' && dweltypes['id'] != 'h') {
                                    var data = new Array();
                                    data['id'] = '00';
                                    data['item'] = dweltypes['value'];
                                    data['unit'] = '';
                                    data['css']='readonly bold';
                                    data['readonly']=true;
                                //    for (var y = 0; y < allyear.length; y++) {data[' ' + allyear[y]] = '';}
                                    datar.push(data);


                                    for (k = 0; k < subtypechunk.length; k++) {
                                        if (subtypechunk != undefined && subtypechunk != '') {
                                            var item = doc_clients_data.getElementsByTagName(subtypechunk[k])[0].childNodes[0].nodeValue;
                                            var unit = '';

                                            if (dweltypes['id'] == 'j') {

                                                unit = defaulteneh;
                                            }

                                            var data = new Array();
                                            data['id'] = subtypechunk[k] + '_' + dweltypes['id'];
                                            data['item'] = item;
                                            data['unit'] = unit + dweltypes['unit'];
                                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata[subtypechunk[k] + '_' + dweltypes['id'] + '_' + allyear[y]]);}
                                            data['chart']=false;
                                            datar.push(data);
                                        }
                                    }
                                }
                            });

                            //Penetration
                            var data = new Array();
                            data['conf'] = 'sum';
                            data['id'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                            data['item'] = 'Penetration of energy forms';
                            data['unit'] = '';
                            data['css']='readonly bold';
                            data['readonly']=true;
                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                            data['chart']=false;
                            datar.push(data);

                            //houtype
                            jQuery.each(houtype, function(i, houtypes) {
                                if (houtypes[houendtypes['id']] == 'Y') {
                                    if (houtypes['id'] == 'EL' && houendtypes['id'] != 'CO' && houendtypes['id'] != 'AC') {
                                        var data = new Array();
                                        data['conf'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                                        data['id'] = 'P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);

                                        var data = new Array();
                                        data['conf'] = '';
                                        data['id'] = 'PH_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = '-- thereof: heat pump';
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['PH_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    } else {
                                        var data = new Array();
                                        data['conf'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                                        data['id'] = 'P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    }

                                }
                            });

                            var data = new Array();
                            data['id'] = '00';
                            data['item'] = 'Efficiencies and other factors';
                            data['unit'] = '';
                            data['css']='readonly bold';
                            data['readonly']=true;
                         //   for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                            datar.push(data);

                            jQuery.each(houtype, function(i, houtypes) {
                                if (houtypes[houendtypes['id']] == 'Y' && houtypes['id'] != 'DH') {
                                    if (houendtypes['id'] == 'CO' && houtypes['id'] == 'EL') {} else {
                                        var unit = '%';
                                        if (houtypes['eff'].indexOf("COP") >= 0 || houendtypes['id'] == 'AC') {
                                            unit = 'ratio';
                                        }

                                        var data = new Array();
                                        data['id'] = 'E_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['eff'];
                                        data['unit'] = unit;
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['E_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    }
                                }
                            });
                        } else if (houendtypes['id'] == 'WH' && bjdata['WH_' + typechunk[j]] == 'Y') {

                            //dweltype
                            jQuery.each(dweltype, function(i, dweltypes) {
                                if (dweltypes['ftype'] == 'WH' && dweltypes['id'] == 'c') {
                                    var data = new Array();
                                    data['id'] = '0';
                                    data['item'] = 'Dwelling Factors for ' + houendtypes['value'];
                                    data['unit'] = '';
                                    data['css']='readonly1 bold';
                                    data['readonly']=true;
                                    //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                                    datar.push(data);

                                    var data = new Array();
                                    data['id'] = typechunk[j] + '_' + dweltypes['id'];
                                    data['item'] = dweltypes['value'];
                                    data['unit'] = defaultene + '/cap/y';
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata[typechunk[j] + '_' + dweltypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);
                                }

                                if (dweltypes['ftype'] == 'WH' && dweltypes['id'] != 'c') {

                                    var data = new Array();
                                    data['id'] = '00';
                                    data['item'] = dweltypes['value'];
                                    data['unit'] = '';
                                    data['css']='readonly bold';
                                    data['readonly']=true;
                                //    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                                    datar.push(data);


                                    for (k = 0; k < subtypechunk.length; k++) {
                                        if (subtypechunk != undefined && subtypechunk != '') {
                                            var item = doc_clients_data.getElementsByTagName(subtypechunk[k])[0].childNodes[0].nodeValue;
                                            var data = new Array();
                                            data['id'] = subtypechunk[k] + '_' + dweltypes['id'];
                                            data['item'] = item;
                                            data['unit'] = dweltypes['unit'];
                                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata[subtypechunk[k] + '_' + dweltypes['id'] + '_' + allyear[y]]);}
                                            data['chart']=false;
                                            datar.push(data);
                                        }
                                    }

                                }


                            });


                            //Penetration
                            var data = new Array();
                            data['conf'] = 'sum';
                            data['id'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                            data['item'] = 'Penetration of energy forms';
                            data['unit'] = '';
                            data['css']='readonly bold';
                            data['readonly']=true;
                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                            data['chart']=false;
                            datar.push(data);

                            //houtype
                            jQuery.each(houtype, function(i, houtypes) {
                                if (houtypes[houendtypes['id']] == 'Y') {
                                    if (houtypes['id'] == 'EL' && houendtypes['id'] != 'CO' && houendtypes['id'] != 'AC') {
                                        var data = new Array();
                                        data['conf'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                                        data['id'] = 'P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);

                                        var data = new Array();
                                        data['conf'] = '';
                                        data['id'] = 'PH_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = '-- thereof: heat pump';
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) {data[' ' + allyear[y]] = isNumber(bedata['PH_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]); }
                                        data['chart']=false;
                                        datar.push(data);
                                    } else {
                                        var data = new Array();
                                        data['conf'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                                        data['id'] = 'P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) {data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    }

                                }
                            });


                            var data = new Array();
                            data['id'] = '00';
                            data['item'] = 'Efficiencies and other factors';
                            data['unit'] = '';
                            data['css']='readonly bold';
                            data['readonly']=true;
                        //    for (var y = 0; y < allyear.length; y++) {data[' ' + allyear[y]] = '';}
                            datar.push(data);

                            jQuery.each(houtype, function(i, houtypes) {
                                if (houtypes[houendtypes['id']] == 'Y' && houtypes['id'] != 'DH') {
                                    if (houendtypes['id'] == 'CO' && houtypes['id'] == 'EL') {} else {
                                        var unit = '%';
                                        if (houtypes['eff'].indexOf("COP") >= 0 || houendtypes['id'] == 'AC') {
                                            unit = 'ratio';
                                        }

                                        var data = new Array();
                                        data['id'] = 'E_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['eff'];
                                        data['unit'] = unit;
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['E_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    }
                                }
                            });

                            //begin CO
                        } else if (houendtypes['id'] == 'CO' && bjdata['CO_' + typechunk[j]] == 'Y') {
                            jQuery.each(dweltype, function(i, dweltypes) {
                                if (dweltypes['ftype'] == 'CO') {
                                    var data = new Array();
                                    data['id'] = '0';
                                    data['item'] = 'Dwelling Factors for ' + houendtypes['value'];
                                    data['unit'] = '';
                                    data['css']='readonly1 bold';
                                    data['readonly']=true;
                                //    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = ''; }
                                    datar.push(data);

                                    //dweltype
                                    var data = new Array();
                                    data['id'] = '00';
                                    data['item'] = dweltypes['value'];
                                    data['unit'] = '';
                                    data['css']='readonly bold';
                                    data['readonly']=true;
                                //    for (var y = 0; y < allyear.length; y++) {data[' ' + allyear[y]] = '';}
                                    datar.push(data);
                                    for (k = 0; k < subtypechunk.length; k++) {
                                        if (subtypechunk != undefined && subtypechunk != '') {
                                            var item = doc_clients_data.getElementsByTagName(subtypechunk[k])[0].childNodes[0].nodeValue;
                                            var unit = dweltypes['unit'];
                                            if (dweltypes['id'] == 'a') {
                                                unit = defaultene;
                                            }
                                            var data = new Array();
                                            data['id'] = subtypechunk[k] + '_' + dweltypes['id'];
                                            data['item'] = item;
                                            data['unit'] = unit + dweltypes['unit'];
                                            for (var y = 0; y < allyear.length; y++) {data[' ' + allyear[y]] = isNumber(bedata[subtypechunk[k] + '_' + dweltypes['id'] + '_' + allyear[y]]);}
                                            data['chart']=false;
                                            datar.push(data);
                                        }
                                    }
                                }
                            });


                            //Penetration
                            var data = new Array();
                            data['conf'] = 'sum';
                            data['id'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                            data['item'] = 'Penetration of energy forms';
                            data['unit'] = '';
                            data['css']='readonly bold';
                            data['readonly']=true;
                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                            data['chart']=false;
                            datar.push(data);

                            //houtype
                            jQuery.each(houtype, function(i, houtypes) {
                                if (houtypes[houendtypes['id']] == 'Y') {
                                    if (houtypes['id'] == 'EL' && houendtypes['id'] != 'CO' && houendtypes['id'] != 'AC') {
                                        var data = new Array();
                                        data['conf'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                                        data['id'] = 'P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);

                                        var data = new Array();
                                        data['conf'] = '';
                                        data['id'] = 'PH_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = '-- thereof: heat pump';
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['PH_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    } else {
                                        var data = new Array();
                                        data['conf'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                                        data['id'] = 'P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    }
                                }
                            });

                            var data = new Array();
                            data['id'] = '00';
                            data['item'] = 'Efficiencies and other factors';
                            data['unit'] = '';
                            data['css']='readonly bold';
                            data['readonly']=true;
                            //for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                            datar.push(data);

                            jQuery.each(houtype, function(i, houtypes) {
                                if (houtypes[houendtypes['id']] == 'Y' && houtypes['id'] != 'DH') {
                                    if (houendtypes['id'] == 'CO' && houtypes['id'] == 'EL') {} else {
                                        var unit = '%';
                                        if (houtypes['eff'].indexOf("COP") >= 0 || houendtypes['id'] == 'AC') {
                                            unit = 'ratio';
                                        }
                                        var data = new Array();
                                        data['id'] = 'E_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['eff'];
                                        data['unit'] = unit;
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['E_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    }
                                }
                            });

                            //end CO

                        } else if (houendtypes['id'] == 'AC' && bjdata['AC_' + typechunk[j]] == 'Y') {

                            var data = new Array();
                            data['id'] = '0';
                            data['item'] = 'Dwelling Factors for ' + houendtypes['value'];
                            data['unit'] = '';
                            data['css']='readonly1 bold';
                            data['readonly']=true;
                        //    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = ''; }
                            datar.push(data);
                            //dweltype
                            jQuery.each(dweltype, function(i, dweltypes) {
                                if (dweltypes['ftype'] == 'AC') {
                                    var data = new Array();
                                    data['id'] = '00';
                                    data['item'] = dweltypes['value'];
                                    data['unit'] = '';
                                    data['css']='readonly bold';
                                    data['readonly']=true;
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata[typechunk[j] + '_' + dweltypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);

                                    for (k = 0; k < subtypechunk.length; k++) {
                                        if (subtypechunk != undefined && subtypechunk != '') {
                                            var item = doc_clients_data.getElementsByTagName(subtypechunk[k])[0].childNodes[0].nodeValue;
                                            var unit = '';
                                            if (dweltypes['id'] == 'l') {
                                                unit = defaultene;
                                            }
                                            var data = new Array();
                                            data['id'] = subtypechunk[k] + '_' + dweltypes['id'];
                                            data['item'] = item;
                                            data['unit'] = unit + dweltypes['unit'];
                                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata[subtypechunk[k] + '_' + dweltypes['id'] + '_' + allyear[y]]);}
                                            data['chart']=false;
                                            datar.push(data);
                                        }
                                    }
                                }
                            });


                            //Penetration
                            var data = new Array();
                            data['conf'] = 'sum';
                            data['id'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                            data['item'] = 'Penetration of energy forms';
                            data['unit'] = '';
                            data['css']='readonly bold';
                            data['readonly']=true;
                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                            data['chart']=false;
                            datar.push(data);

                            //houtype
                            jQuery.each(houtype, function(i, houtypes) {
                                if (houtypes[houendtypes['id']] == 'Y') {
                                    if (houtypes['id'] == 'EL' && houendtypes['id'] != 'CO' && houendtypes['id'] != 'AC') {
                                        var data = new Array();
                                        data['conf'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                                        data['id'] = 'P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);

                                        var data = new Array();
                                        data['conf'] = '';
                                        data['id'] = 'PH_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = '-- thereof: heat pump';
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['PH_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    } else {
                                        var data = new Array();
                                        data['conf'] = 'P_' + typechunk[j] + '_' + houendtypes['id'];
                                        data['id'] = 'P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['value'];
                                        data['unit'] = '%';
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['P_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    }
                                }
                            });

                            var data = new Array();
                            data['id'] = '00';
                            data['item'] = 'Efficiencies and other factors';
                            data['unit'] = '';
                            data['css']='readonly bold';
                            data['readonly']=true;
                        //    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                            datar.push(data);

                            jQuery.each(houtype, function(i, houtypes) {
                                //new conditions for exclude COP heat pumps and Eff. Fosil Fuels
                                if (houtypes[houendtypes['id']] == 'Y' && houtypes['id'] != 'DH' && houtypes['id'] != 'EL' && houtypes['id'] != 'FF') {
                                    if (houendtypes['id'] == 'CO' && houtypes['id'] == 'EL') {} else {
                                        var unit = '%';
                                        if (houtypes['eff'].indexOf("COP") >= 0 || houendtypes['id'] == 'AC') {
                                            unit = 'ratio';
                                        }
                                        var data = new Array();
                                        data['id'] = 'E_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                        data['item'] = houtypes['eff'];
                                        data['unit'] = unit;
                                        for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['E_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                        data['chart']=false;
                                        datar.push(data);
                                    }
                                }

                            //COP non electric AC and COP electric AC
                                if (houtypes[houendtypes['id']] == 'Y' && houtypes['id'] == 'FF' && houendtypes['id'] == 'AC') {
                                    var data = new Array();
                                    data['conf'] = '';
                                    data['id'] = 'E_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                    data['item'] = 'COP non-electric AC';
                                    data['unit'] = 'ratio';
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['E_' + typechunk[j] +'_'+ houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);
                                }

                                if (houtypes[houendtypes['id']] == 'Y' && houtypes['id'] == 'EL' && houendtypes['id'] == 'AC') {
                                    var data = new Array();
                                    data['bold'] = '';
                                    data['id'] = 'E_' + typechunk[j] + '_' + houtypes['id'] + '_' + houendtypes['id'];
                                    data['item'] = 'COP electric AC';
                                    data['unit'] = 'ratio';
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata['E_' + typechunk[j] +'_'+  houtypes['id'] + '_' + houendtypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);
                                }
                            //--------
                            });
                        } else if (houendtypes['id'] == 'AP' && bjdata['AP_' + typechunk[j]] == 'Y') {
                            var data = new Array();
                            data['id'] = '0';
                            data['item'] = 'Dwelling Factors for ' + houendtypes['value'];
                            data['unit'] = '';
                            data['css']='readonly1 bold';
                            data['readonly']=true;
                        //    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                            datar.push(data);
                            //dweltype
                            jQuery.each(dweltype, function(i, dweltypes) {
                                if (dweltypes['ftype'] == 'AP' && dweltypes['id'] == 'e') {
                                    var data = new Array();
                                    data['id'] = typechunk[j] + '_' + dweltypes['id'];
                                    data['item'] = dweltypes['value'];
                                    data['unit'] = dweltypes['unit'];
                                    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata[typechunk[j] + '_' + dweltypes['id'] + '_' + allyear[y]]);}
                                    data['chart']=false;
                                    datar.push(data);
                                }
                                if (dweltypes['ftype'] == 'AP' && (dweltypes['id'] == 'd' || dweltypes['id'] == 'f')) {
                                    var data = new Array();
                                    data['id'] = '00';
                                    data['item'] = dweltypes['value'];
                                    data['unit'] = '';
                                    data['css']='readonly bold';
                                    data['readonly']=true;
                                //    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                                    datar.push(data);


                                    for (k = 0; k < subtypechunk.length; k++) {
                                        if (subtypechunk != undefined && subtypechunk != '') {
                                            var item = doc_clients_data.getElementsByTagName(subtypechunk[k])[0].childNodes[0].nodeValue;
                                            // var unit = dweltypes['unit'];
                                            // if (dweltypes['id'] == 'l') {
                                            //     unit = defaultene;
                                            // }
                                            var data = new Array();
                                            data['id'] = subtypechunk[k] + '_' + dweltypes['id'];
                                            data['item'] = item;
                                            data['unit'] = defaultene + dweltypes['unit'];
                                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata[subtypechunk[k] + '_' + dweltypes['id'] + '_' + allyear[y]]);}
                                            data['chart']=false;
                                            datar.push(data);
                                        }
                                    }
                                }
                            });
                        } else if (houendtypes['id'] == 'LH' && bjdata['LH_' + typechunk[j]] == 'Y') {
                            var data = new Array();
                            data['id'] = '00';
                            data['item'] = 'Dwelling Factors for Lighting';
                            data['unit'] = '';
                            data['css']='readonly1 bold';
                            data['readonly']=true;
                        //    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                            datar.push(data);

                            jQuery.each(dweltype, function(i, dweltypes) {
                                if (dweltypes['ftype'] == 'AP' && dweltypes['id'] != 'e') {
                                    var data = new Array();
                                    data['id'] = '0';
                                    data['item'] = dweltypes['value'];
                                    data['unit'] = '';
                                    data['css']='readonly bold';
                                    data['readonly']=true;
                                //    for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = '';}
                                    datar.push(data);

                                    for (k = 0; k < subtypechunk.length; k++) {
                                        if (subtypechunk != undefined && subtypechunk != '') {
                                            var item = doc_clients_data.getElementsByTagName(subtypechunk[k])[0].childNodes[0].nodeValue;
                                            var unit = dweltypes['unit'];
                                            if (dweltypes['id'] == 'd' || dweltypes['id'] == 'f') {
                                                unit = defaultene;
                                            } else {
                                                unit = '';
                                            }
                                            var data = new Array();
                                            data['id'] = subtypechunk[k] + '_LH_' + dweltypes['id'];
                                            data['item'] = item;
                                            data['unit'] = unit + dweltypes['unit'];
                                            for (var y = 0; y < allyear.length; y++) { data[' ' + allyear[y]] = isNumber(bedata[subtypechunk[k] + '_LH_' + dweltypes['id'] + '_' + allyear[y]]);}
                                            data['chart']=false;
                                            datar.push(data);
                                        }
                                    }
                                }
                            });
                        }
                        //end houendtype 
                    });
                }
            }
        }
    });

    return datar;
}

function CalculateTotal(cv, year, sumindicator) {
    var total = 0;
    jQuery.each(cv, function(j, valuefromgrid) {
        if (valuefromgrid[year] != null && valuefromgrid[year] != "" && sumindicator == valuefromgrid['conf']) {
            total += parseFloat(valuefromgrid[year]);
        }
    })
    return total;
}

function showgrid(results) {
    var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdatadwellingfactorhh(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);

    grid.cellEditEnded.addHandler(function(s, e) {
        var year = grid.columns[e.col].header;
        var totalrow;
        jQuery.each(cv.items, function(j, valuefromgrid) {
            if (valuefromgrid['conf'] == 'sum') {
                totalrow = j;
                total = CalculateTotal(grid.itemsSource.items, year.toString(), valuefromgrid['id']);
                grid.setCellData(totalrow, e.col, total);
            }
        });
    });

    grid.itemFormatter = function(panel, r, c, cell) {
        if (panel.cellType === wijmo.grid.CellType.Cell && c > 3 && c<grid.columns.length-1) {
            var cellName = '';
            if (grid.itemsSource.items[r]['conf']=='sum') {
                var cellData = panel.getCellData(r, c);
                if (!check100(cellData)) {
                    $(cell).addClass('pink');
                    cellName = panel.getCellData(r, 2);
                    ShowErrorMessage(cellName + ' <>100');
                }
            }
        }
    }
}