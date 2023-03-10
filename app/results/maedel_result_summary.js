function getdataresultsummary(results) {
	var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var cddata = results['cdData'];
    var cedata = results['ceData'];
    var crdata = results['crData'];
    var cqdata = results['cqData'];
    var casestudyid = results['casestudyid'];
    var allyear = results['allyear'];

    var datar = [];
    for (var y = 0; y < allyear.length; y++) {
    //year
    var data = new Array();
    data['item'] = allyear[y];
    data['total'] = window.lang.translate('Total');
    for (var i = 1; i <= cddata.nseason; i++) {
        data[cedata['seasonname_'+i]]=cedata['seasonname_'+i];
    }
    datar.push(data);
    
    //maximum load
    var data = new Array();
    data['item'] = window.lang.translate('Maximum load (MW)');
    data['total'] = isNumber(cqdata['MD_'+allyear[y]]);

    for (var i = 1; i <= cddata.nseason; i++) {
        data[cedata['seasonname_'+i]]=isNumber(cqdata['MDse_'+allyear[y]+'_'+i]);
    }
    datar.push(data);

     //relation to annual peak
     var data = new Array();
     data['item'] = window.lang.translate('Relation to annual peak');
     data['total'] = '';
     
     for (var i = 1; i <= cddata.nseason; i++) {
        data[cedata['seasonname_'+i]]=isNumber(cqdata['RelAP_'+allyear[y]+'_'+i]);
     }
     datar.push(data); 
     
    //Energy (GWh)
    var data = new Array();
    data['item'] = window.lang.translate('Energy (GWh)');
    data['total'] = isNumber(cqdata['EL_'+allyear[y]]);
    
    for (var i = 1; i <= cddata.nseason; i++) {
        data[cedata['seasonname_'+i]]=isNumber(cqdata['ELse_'+allyear[y]+'_'+i]);
    }
    datar.push(data); 

    //Load factor (%)
    var data = new Array();
    data['item'] = window.lang.translate('Load factor (%)');
    data['total'] = isNumber(cqdata['LF_'+allyear[y]]);

    for (var i = 1; i <= cddata.nseason; i++) {
        data[cedata['seasonname_'+i]]=isNumber(cqdata['LFse_'+allyear[y]+'_'+i]);
    }
    datar.push(data); 

    //Numbers of hours
    var data = new Array();
    data['item'] = window.lang.translate('Numbers of hours');
    data['total'] = isNumber(crdata['yr_'+allyear[y]]);
    
    for (var i = 1; i <= cddata.nseason; i++) {
        data[cedata['seasonname_'+i]]=isNumber(crdata['HH_'+allyear[y]+'_'+i]);
    }
    datar.push(data); 

    //Difference to annual demand (GWh)
    var data = new Array();
    data['item'] = window.lang.translate('Difference to annual demand (GWh)');
    data['total'] = isNumber(cqdata['DifAD_'+allyear[y]]);

    for (var i = 1; i <= cddata.nseason; i++) {
        data[cedata['seasonname_'+i]]='';
    }
    datar.push(data);

    //% difference to annual demand
    var data = new Array();
    data['item'] = window.lang.translate('% difference to annual demand');
    data['total'] = isNumber(cqdata['DifADp_'+allyear[y]]);

    for (var i = 1; i <= cddata.nseason; i++) {
        data[cedata['seasonname_'+i]]='';
    }
    datar.push(data);

    }   
    return datar;
}


function showresults(results) {
    $('#wasp').show();
    $('#chart').hide();
    $('#gridTitle').html("Summary");
    var cv = new wijmo.collections.CollectionView(getdataresultsummary(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');

    grid.itemsSource = cv;
    grid.SelectionMode = wijmo.grid.SelectionMode.Row;
    grid.allowSorting = false;
    grid.isReadOnly=true;
    grid.autoSizeColumn();
    grid.headersVisibility = wijmo.grid.HeadersVisibility.Row;

    for (var i = 1; i < grid.columns.length; i++) {
        grid.columns[i].dataType = 'Number';
        grid.columns[i].format = 'f10';
        grid.columns[i].minWidth = 120;
    }

    grid.formatItem.addHandler((s, e) => {
        s.rows.forEach(r => {
            if(r.index%8==0){
            r.cssClass = 'readonly1';
            }
        })
    });

}