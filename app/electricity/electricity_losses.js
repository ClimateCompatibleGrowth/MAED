function getdatatransdistloss(results) {
    $('#gridTitle').html("Transmission and distribution losses");
    var cgdata = results['cgData'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
	
    var datar = [];
    //transmision losses
    var data = new Array();
    data['id'] = 'transloss';
    data['item'] = window.lang.translate('Transmission losses');
    data['unit'] = '%';
    for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(cgdata['transloss_' + allyear[y]]);}  
    data['css']='bold';
    datar.push(data);
    //distribution losses
    var data = new Array();
    data['id'] = 'disloss';
    data['item'] = window.lang.translate('Distribution losses');
    data['readonly']=true;
    data['css']='bold';
    datar.push(data);
    jQuery.each(maintype, function(i, val) {
        if (val['id'] != null) {
            var data = new Array();
            data['id'] = val['id'];
            data['item'] = val['value'];
            data['unit'] = '%';
            for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(cgdata[val['id'] + '_' + allyear[y]]);}  
            data['chart']=true;
            datar.push(data);
        }
    });
    return datar;

}


function showgrid(results) {
	var allyear = results['allyear'];
	var max='Max';
    var cv = new wijmo.collections.CollectionView(getdatatransdistloss(results));
    var grid = new wijmo.grid.FlexGrid('#gsFlexGrid');

    CreateGrid(allyear,cv,grid);    

    grid.itemFormatter = function(panel, r, c, cell) {
        if (panel.cellType === wijmo.grid.CellType.Cell && c > 2) {
            var cellData = panel.getCellData(r, c);
            if (cellData > 100) {
                cell.style.backgroundColor = 'Pink';
                ShowErrorMessage(max + ':100');
            }
        }
    }
}