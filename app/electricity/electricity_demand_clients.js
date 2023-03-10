function getdataelectricitydemand(results) {
    $('#gridTitle').html("Electricity demand per client");
	var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var cbdata = results['cbData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var datar=[];

    //return false;
    jQuery.each( maintype, function( i, val ) {
        //sector
    if (val['id']!=null){
        var data = new Array();
        data['id']=val['id'];
        data['item']=val['value'];
        data['unit']='%'; 
        for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(cbdata[val['id'] + '_' + allyear[y]]);}  
        data['readonly']=true;
        data['css']='readonly bold';
      //  data['chart']=false;
        datar.push(data); 
        var doc_clients_data = loadXMLDoc('storage/maedel/data/projects/'+path+'/'+casestudyid+'/sectors_data.xml');
        var clients_data=doc_clients_data.getElementsByTagName(val['id']+'_A')[0];

        if(clients_data!==undefined){
        var row_clients_data= doc_clients_data.getElementsByTagName(val['id']+'_A')[0].childNodes[0].nodeValue.split(',');      

        //clients
        for(j = 0; j < row_clients_data.length; j++){
            var data = new Array();
            data['id']=row_clients_data[j];
            data['item']=doc_clients_data.getElementsByTagName(row_clients_data[j])[0].childNodes[0].nodeValue;
            data['unit']='%';  
            for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(cbdata[row_clients_data[j]+'_'+allyear[y]]);} 
            data['chart']=true;
            datar.push(data); 
        }
    }
    }
});
return datar;
}

function showgrid(results) {
	var allyear = results['allyear'];
	var maintype = results['maintype'];
    var sectorsid=[];
    
    var cv = new wijmo.collections.CollectionView(getdataelectricitydemand(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear,cv,grid);    
    
    grid.cellEditEnded.addHandler(function (s, e) {
        //calculate value
        var year=grid.columns[e.col].header;
        var cv=grid.itemsSource.items;
        
        jQuery.each( maintype, function( i, valmt ) {
            var totalcol;
            var sum=0;
            var sectorname;
            jQuery.each(cv, function( j, valcv ) {
            if (valmt['id']==valcv['id']){
                totalcol=j;
                sectorname=valmt['value'];
            }
            
            if(valcv['id'].indexOf(valmt['id']+'_')!=-1){
                if (valcv[year]!=null && valcv[year]!=""){
                sum+=parseFloat(valcv[year]);
                }
            }
        })
        //set total
        if (totalcol!=null){
            if (sum!=100)
            {
                 ShowErrorMessage(sectorname+'<>100');
            }
            grid.setCellData(totalcol,e.col,sum);
        }
        })

    }); 
    grid.itemFormatter = function (panel, r, c, cell) {
    jQuery.each( sectorsid, function( i, val ) {
    if (panel.cellType === wijmo.grid.CellType.Cell && c > 2 && r==val) {
        var cellData = panel.getCellData(r,c);
        if (cellData != 100) {
            $(cell).removeClass('blue');
            $(cell).addClass('pink');
        }
    }
    })
    }
}