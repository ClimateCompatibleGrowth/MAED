function getdataelectricitysupplied(results) {
    $('#gridTitle').html("Electricity supplied from the grid");
    var ckdata = results['ckData'];
    var maintype = results['maintype'];
    var allyear = results['allyear'];
	
    var datar = [];
    jQuery.each(maintype, function(i, val) {
        if (val['id'] != null) {
            var data = new Array();
            data['id'] = val['id'];
            data['item'] = val['value'];
            data['unit'] = '%';
            for(var y=0; y<allyear.length; y++){ data[' '+allyear[y]]=isNumber(ckdata[val['id'] + '_' + allyear[y]]);} 
            data['chart']=true;
            datar.push(data);
        }
    });
    return datar;
}


function showgrid(results) {
	var allyear = results['allyear'];
    var cv = new wijmo.collections.CollectionView(getdataelectricitysupplied(results));
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