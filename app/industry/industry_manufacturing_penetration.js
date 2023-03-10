function getdatapenmanuf(results) {
    var user_path=results['user_path'].split("/");
    var path=user_path[user_path.length-2];
    var asdata = results['asData'];
    var bjdata=results['bjData'];
    var casestudyid = results['casestudyid'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
    var facmtype=results['facmtype'];
    var pentype=results['pentype'];
    var datar=[];
    jQuery.each( maintype, function( i, maintypes ) {
        //sector
    if (maintypes['id']=='Man'){
        var doc_clients_data = loadXMLDoc(storagePath+path+'/' + casestudyid + '/sectors_data.xml');
        var typechunk= doc_clients_data.getElementsByTagName(maintypes['id']+'_A')[0].childNodes[0].nodeValue.split(',');      

        //clients
        for(j = 0; j < typechunk.length; j++){
        var item=doc_clients_data.getElementsByTagName(typechunk[j])[0].childNodes[0].nodeValue;
            if (bjdata['TU_'+typechunk[j]]=='Y'){
            var data = new Array();
            data['conf']='00';
            data['id']=typechunk[j];
            data['item']=item;
            data['unit']='';
            data['css']='readonly1 bold'; 
            data['readonly']=true; 
           // for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]='';} 
            datar.push(data); 
          
            
              jQuery.each(facmtype, function( i, facmtypes ) {
                    if(bjdata['TU_'+typechunk[j]]=='Y' && facmtypes['PE']=='N' && bjdata[facmtypes['id']+'_'+typechunk[j]]=='Y'){	
                        var data = new Array();
                        data['conf']='01';
                        data['id']=facmtypes['id']+'_'+typechunk[j];
                        data['item']=facmtypes['value'];
                        data['unit']='';  
                        data['css']='readonly bold';
                        data['readonly']=true;
                        for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(asdata[facmtypes['id']+'_'+typechunk[j]+'_'+allyear[y]]); }
                        data['chart']=false;
                        datar.push(data); 
                    
              jQuery.each(pentype, function( i, pentypes ) {
                    if(pentypes['Man_'+facmtypes['id']]=='Y'){
                        if(bjdata['TU_'+typechunk[j]]=='Y' && (pentypes['Man_SWH']=='Y' ||  pentypes['Man_SG']=='Y' || pentypes['Man_FDH']=='Y')){
                        var data = new Array();
                        data['conf']=facmtypes['id']+'_'+typechunk[j]+'_'+pentypes['id'];
                        data['id']=typechunk[j]+'_'+pentypes['id']+'_'+facmtypes['id'];
                        data['item']=pentypes['value'];
                        data['unit']='%';  
                        for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(asdata[typechunk[j]+'_'+pentypes['id']+'_'+facmtypes['id']+'_'+allyear[y]]);} 
                        data['chart']=false;
                        datar.push(data); 
                    
                    if(pentypes['id']=='EL' && (facmtypes['id']=='SG' || facmtypes['id']=='SWH')){
                        var data = new Array();
                        data['conf']='1';
                        data['id']='H_'+typechunk[j]+'_'+pentypes['id']+'_'+facmtypes['id'];
                        data['item']='(of which Heat Pumps)';
                        data['unit']='%';  
                        for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(asdata['H_'+typechunk[j]+'_'+pentypes['id']+'_'+facmtypes['id']+'_'+allyear[y]]);} 
                        data['chart']=false;
                        datar.push(data); 
                        }
                    }
                    }
              });
                    
                    
                    }
                    else if(bjdata['TU_'+typechunk[j]]=='Y' && facmtypes['PE']=='N' && bjdata['FDH_'+typechunk[j]]!='Y' && bjdata['SG_'+typechunk[j]]!='Y' && bjdata['SWH_'+typechunk[j]]!='Y' && facmtypes['id']=='SG'){
                        var data = new Array();
                        data['conf']='01';
                        data['id']=facmtypes['id']+typechunk[j];
                        data['item']='';
                        data['unit']='';  
                        data['css']='readonly bold';
                        data['readonly']=true;
                        for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(asdata[facmtypes['id']+'_'+typechunk[j]+'_'+allyear[y]]); } 
                        data['chart']=false;
                        datar.push(data);   
                        
                    jQuery.each(pentype, function( i, pentypes ) {
                    if(pentypes['Man_'+facmtypes['id']]=='Y'){
                        if(bjdata['TU_'+typechunk[j]]=='Y' && (pentypes['Man_SWH']=='Y' ||  pentypes['Man_SG']=='Y' || pentypes['Man_FDH']=='Y')){
                        var data = new Array();
                        data['conf']=typechunk[j]+'_'+facmtypes['id']+pentypes['id'];
                        data['id']=typechunk[j]+'_'+pentypes['id']+'_'+facmtypes['id'];
                        data['item']=pentypes['value'];
                        data['unit']='%';  
                        for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(asdata[typechunk[j]+'_'+pentypes['id']+'_'+facmtypes['id']+'_'+allyear[y]]);} 
                        data['chart']=false;
                        datar.push(data); 
                    
                    if(pentypes['id']=='EL' && (facmtypes['id']=='SG' || facmtypes['id']=='SWH')){
                        var data = new Array();
                        data['conf']='1';
                        data['id']='H_'+typechunk[j]+'_'+pentypes['id'];
                        data['item']='(of which Heat Pumps)';
                        data['unit']='%';  
                        for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(asdata['H_'+typechunk[j]+'_'+pentypes['id']+'_'+facmtypes['id']+'_'+allyear[y]]); } 
                        data['chart']=false;
                        datar.push(data); 
                        }                   
                    }
                    }
              });     
                        
                   }
            });
          }
          // Others & Thermal
         if(bjdata['OT_'+typechunk[j]]=='Y' && bjdata['OT_'+typechunk[j]+'_OT']=='TU'){
             var data = new Array();
             data['conf']=1;
             data['id']='OT_'+typechunk[j];
             data['item']=item+' - Other Thermal Uses';
             data['unit']='';  
             for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(asdata['OT_'+typechunk[j]+'_'+allyear[y]]);} 
             data['chart']=false;
             datar.push(data); 
             
             var data = new Array();
             data['conf']=0;
             data['id']='OT_'+typechunk[j]+'_SG';
             data['item']=item+' - Other TU';
             data['unit']='';  
             for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(asdata['OT_'+typechunk[j]+'_SG_'+allyear[y]]);} 
            data['chart']=false;
            datar.push(data); 
         }
          
        }
    }
});
return datar;
}

function CalculateTotal(cv,year,maintype){
    var total=0;
     jQuery.each(cv, function( j, valuefromgrid ) {
            if(valuefromgrid['conf'].indexOf(maintype+'_')!=-1){
                if (valuefromgrid[year]!=null && valuefromgrid[year]!=""){
                    total+=parseFloat(valuefromgrid[year]);
                }
            }
     })
     return total;
}

function showgrid(results) {
    var allyear = results['allyear'];
			var cv = new wijmo.collections.CollectionView(getdatapenmanuf(results));
			var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');
            CreateGrid(allyear,cv,grid);

            grid.cellEditEnded.addHandler(function (s, e) {
            //calculate value
                var year=grid.columns[e.col].header;
                var cv=grid.itemsSource.items;
                jQuery.each(cv, function( j, valuefromgrid ) {
                    if (valuefromgrid['conf']=='01'){
                        grid.setCellData(j,e.col,CalculateTotal(cv,year.toString(),valuefromgrid['id']));
                    }
                })        
            }); 

            grid.itemFormatter = function (panel, r, c, cell) {
            if (panel.cellType === wijmo.grid.CellType.Cell && c > 3 && c<grid.columns.length-1 ) {
                var cellName='';                
                if (grid.itemsSource.items[r]['conf']=='01')
                {
                var cellData = panel.getCellData(r,c);
                if (!check100(cellData)) {
                    $(cell).addClass('pink');
                    cellName=panel.getCellData(r,2);      
                    ShowErrorMessage(cellName+' <>100');            
                }
                                 
                }
               
            }
        }   
}