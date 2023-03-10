//get data
function getdataacmefficiency(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var aqdata = results['aqData'];
    var bjdata=results['bjData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var pentype=results['pentype'];

    var datar=[];
    jQuery.each( maintype, function( i, val ) {
        //sector
    if (val['fac']=='Y'){
            var data = new Array();
            data['id']='0';
            data['item']=val['value'];
            data['unit']='';  
            data['css']='readonly1 bold';
            data['readonly']=true;
            //for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=''; } 
            datar.push(data); 
        var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
        var typechunk= doc_clients_data.getElementsByTagName(val['id']+'_A')[0].childNodes[0].nodeValue.split(',');      

        //clients
        for(j = 0; j < typechunk.length; j++){
        var item=doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
            if (bjdata['TU_'+typechunk[j]]=='Y'){
            var data = new Array();
            data['id']='00';
            data['item']=item;
            data['unit']='';  
            data['css']='readonly bold';
            data['readonly']=true;
            //for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=''; } 
            datar.push(data); 
              jQuery.each(pentype, function( i, valpt ) {
                if(valpt['ave']=='Y'){	
                    var data = new Array();
                    data['id']=typechunk[j]+'_'+valpt['id'];
                    data['item']=valpt['value'];
                    data['unit']='%';  
                    for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(aqdata[typechunk[j]+'_'+valpt['id']+'_'+allyear[y]]);}
                    data['chart']=false;
                    datar.push(data);     
                }
            });
            
            }
            if(bjdata['TU_'+typechunk[j]]=='Y' && bjdata['OT_'+typechunk[j]]=='Y' && bjdata['OT_'+typechunk[j]+'_OT']=='TU'){
                var data = new Array();
                data['id']='00';
                data['item']=item+'-Other thermal use';
                data['unit']='';  
                data['css']='readonly bold';
                data['readonly']=true;
               // for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]='';} 
                datar.push(data); 

                jQuery.each(pentype, function( i, valpt1 ) {
                    if(valpt1['ave']=='Y'){	
                        var data = new Array();
                        data['id']='OT_'+typechunk[j]+'_'+valpt1['id'];
                        data['item']=valpt1['value'];
                        data['unit']='%';  
                        for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(aqdata['OT_'+typechunk[j]+'_'+valpt1['id']+'_'+allyear[y]]);} 
                        datar.push(data); 
                    }
              });
            }
        }
    }
});
return datar;
}

function showgrid(results) {
    var allyear = results['allyear'];
	var cv = new wijmo.collections.CollectionView(getdataacmefficiency(results));
	var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
    CreateGrid(allyear, cv, grid);

    grid.itemFormatter = function (panel, r, c, cell) {
    if (panel.cellType === wijmo.grid.CellType.Cell && c > 3 && c<grid.columns.length-1 ) {
        var cellData = panel.getCellData(r,c);
        if (cellData!=undefined && cellData > 100.000) {
            cell.style.backgroundColor='Pink';
            ShowErrorMessage('Max: 100');
        }
    }
}
}