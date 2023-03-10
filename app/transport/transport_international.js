function getdataenergyconsumptionpinter(results) {
    var bddata = results['bdData'];
    var allyear = results['allyear'];
    var defaultene=results['defaultene'];
    var defaultcurrency=results['defaultcurrency'];
    var mainunit= results['mainunit'];
    var datar=[];
    var data = new Array();
    data['id']='CT';
    data['item']='Constant';
    data['unit']=mainunit;
    for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(bddata['CT_'+allyear[y]]);}
    data['chart']=false;
    datar.push(data); 
      
    var data = new Array();
    data['id']='VA';
    data['item']='Variable';
    data['unit']=defaultene+'/'+defaultcurrency;
    for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(bddata['VA_'+allyear[y]]);}
    data['chart']=false;
    datar.push(data); 
    return datar;
}

function showgrid(results){
    var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdataenergyconsumptionpinter(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);
}