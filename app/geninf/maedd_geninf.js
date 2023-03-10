var url="app/geninf/maedd_geninf.php";
var activeTab = "#Agr";
var ftype=[];
ftype.push({id:'EL', name:'Electricity'});
ftype.push({id:'CK', name:'Steam Coal'});
ftype.push({id:'MF', name:'Motor Fuel'});

$(document).ready(function () {
$(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
    activeTab = $(e.target).attr('href');
})
    getDataGenInf();
   //$("#menuBottom").show();
});

function addRow(){

   var id= activeTab.substr(1);
   var doc = document;
   var enduse=[];
   var endtype=[];
   var newvalue=(findLastValue('tbl'+id)*1+1);
   idvalue=id+'_'+newvalue;

   if(id=="Trp"){
    $.ajax({
        url: url,
        type: 'POST',
        data:{
            action: "fueltype"
      },
        success: function (results) {
            var res = JSON.parse(results);
            var fueltypes=res['fueltypes'];
            fueltypes.unshift({'id':0, 'value':'---'});
            var row=addRowToTable(id, idvalue, idvalue, doc, enduse,'','', fueltypes, endtype);
            $('#tbl'+id +' tbody').append(row);
        }
    })
   }else{

    $.ajax({
        url: url,
        type: 'POST',
        data:{
            action: "endtype"
      },
        success: function (results) {
            var res = JSON.parse(results);
            var endtype=res['endtype'];
            var row=addRowToTable(id, idvalue, idvalue, doc, enduse, '', '', '', endtype);
            $(activeTab +' tbody').append(row);
        }
    })
   }

}

function addRowSubClient(id){
    var doc = document;
    var newvalue=(findLastValue('tbl'+id)*1+1);
    idvalue=id+'_'+newvalue;
     var row=addRowToSubClientTable(id, idvalue, doc, true);
     id='tbl'+id;
     $('#'+id +' tbody').append(row);
 }

function addRowFuelType(){
    var doc = document;
    var lastid=$('#fueltypelastid').val()*1+1;
    var row=addRowToTableFuelType(ftype, doc, lastid, lastid+'_value', 0 );
    $('#fueltypelastid').val(lastid);
    $('#fueltypetable tbody').append(row);
 }

function deleteRow(btn){
    bootbox.confirm({
        title: "<span lang='en'>DELETE ROW</span>",
        message: "<span lang='en'>Are you sure?</span>",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (typeof(btn) == "object") {
                $(btn).closest("tr").remove();
            } else {
                return false;
            }
        }
    });

}

function saveData(){
    var studyName=$('#studyName').val();
    if(!studyName){
        $('#studyName').parent().addClass('has-error');
        ShowErrorMessage("Study name is required!");
        return false;
    }
    var year=$('#Year').val();
    if(!year){
        $('#Year').parent().addClass('has-error');
        ShowErrorMessage("Year is required!");
        return false;
    }
    if (clearName(studyName)) {
        $('#fgstudyname').addClass('has-error');
        ShowErrorMessage("Allowed characters [a-Z] [0-9] [-_]!");
        return false;
    }else{
        $.ajax({
            type: "POST",
            url: url,
            data:{
                  id:$('#id').val(),
                  studyName: studyName,
                  Year: year,
                  populationunit:$('input[name=populationunit]:checked').val(),
                  Gdpunit:$('input[name=Gdpunit]:checked').val(),
                  Currency:$('#Currency').val(),
                  energyunit:$('input[name=energyunit]:checked').val(),
                  punit:$('input[name=punit]:checked').val(),
                  funit:$('input[name=funit]:checked').val(),
                  Unit:$('input[name=Unit]:checked').val(),
                  Desc:$('#Desc').val(),
                  action: "update"
            },
            success: function () {ShowSuccessMessage('Data saved successfully'); Cookies('titlecs', studyName); getDataGenInf(); $('#fgstudyname').removeClass('has-error');  },
            failure: function () { ShowErrorMessage("Error!"); }
        });
    }
}

function saveDataSectors(){
    var textboxes={};
    var id=activeTab;
    var mid=activeTab.substr(1);
    var idh=findLastValue('tbl'+mid);
    var autoid=$(id+'_autoid').val();
    textboxes['mid']=mid;
    textboxes['id']=autoid;
    textboxes[mid+'_H']=idh;

    var tbox = $(id).find('.tbox');
    var ida='';
    tbox.each(function() {
        textboxes[this.id]=this.value;
        ida=ida+','+this.id;
    });

    ida=ida.substr(1);
    textboxes[mid+'_A']=ida;

    var checkboxes={};
    $("input:checkbox[class=basic]:checked").each(function() { 
        checkboxes[$(this).attr('id').toString()]='Y'; 
    }); 

    if(mid=="Hou"){
        for(i=1;i<3;i++){
            var idhsub=findLastValue('tbl'+mid+'_'+i);
            textboxes[mid+'_'+i+'_H']=idhsub;
            var tboxsub = $('#tbl'+mid+'_'+i).find('.tboxsub');
            var idasub='';
            tboxsub.each(function() {
                textboxes[this.id]=this.value;
                idasub=idasub+','+this.id;
            });
        
            idasub=idasub.substr(1);
            textboxes[mid+'_'+i+'_A']=idasub;
        }
    }

    checkboxes['SID']=1;

    if(mid=="Trp"){
        var tselect = $(id).find('.fttransport');
        tselect.each(function() {
            if(this.value!=0){
                textboxes[this.id]=this.value;
                textboxes[this.id.toString().substring(0, this.id.length-3)]='Y';
            }else{
                textboxes[this.id]='';
            }

            $("input:checkbox[class=basictr]:checked").each(function() { 
                textboxes[$(this).attr('id').toString()]='Y'; 
            }); 
            
        });
    }

    $.ajax
    ({
        type: "POST",
        url: url,
        data:{
              dataenduse:checkboxes,
              datasectors:textboxes,
              fueltypes:ftype,
              action: "updatesector"
        },
        success: function () { getDataGenInf(); ShowSuccessMessage('Data saved successfully'); },
        failure: function () { ShowErrorMessage("Error!"); }
    });
}

function saveDataFuelTypes(){
        var ftype=[];
        var fid = $('#fueltypetable').find('.fhidden');
        fid.each(function() {
            var fueltypes={};
            fueltypes['id']=this.value;
            fueltypes['value']=$('#fbox_'+this.value).val();
            fueltypes['ftype']=$('#fselect_'+this.value).val();
            ftype.push(fueltypes);
        });

    $.ajax
    ({
        type: "POST",
        url: url,
        data:{
              fueltypes:ftype,
              action: "updatefueltype"
        },
        success: function () { getDataGenInf(); ShowSuccessMessage('Data saved successfully') },
        failure: function () { ShowErrorMessage("Error!"); }
    });
}

function getDataGenInf(){
    $.ajax({
        url: url,
        type: 'POST',
        cache:false,
        data:{
            action: "select"
      },
        success: function (results) {
            var res = JSON.parse(results);
            var sectors=res['sectors'];
            var geninf=res['geninf'];
            var enduse=res['enduse'];
            var fueltypes=res['fueltypes'];
            var endtype=res['endtype'];
            var ftypearray=[];
            if (fueltypes.id!==undefined){
                ftypearray.push(fueltypes);
            }else{
                ftypearray=fueltypes;
            }
            var currencies=res['currency'];
            $('#id').val(geninf['id']);
            $('#studyName').val(geninf['studyName']);
            $('#Year').val(geninf['Year']);
            $('#Desc').val(geninf['Desc']);
            $( "input[name='populationunit'][value=" +geninf['populationunit']+ "]" ).prop( 'checked' , true );
            $( "input[name='punit'][value=" +geninf['punit']+ "]" ).prop( 'checked' , true );
            $( "input[name='funit'][value=" +geninf['funit']+ "]" ).prop( 'checked' , true );
            $( "input[name='Gdpunit'][value=" +geninf['Gdpunit']+ "]" ).prop( 'checked' , true );
            $( "input[name='energyunit'][value=" +geninf['energyunit']+ "]" ).prop( 'checked' , true );
            $("#ultabs").html('');
            for(var i=0; i<sectors.length;i++){
                createTab(sectors[i]);
                createTableSectors(sectors[i], enduse, ftypearray, endtype);
            }

            for(var i=0; i<currencies.length;i++){
                $('#Currency').append("<option value="+currencies[i]['id']+">"+currencies[i]['value']+"</option>"); 
            }
            $('#Currency').val(geninf['Currency']);
            $('#studyNameTitle').html(geninf['studyName']);
        }
    })
}


function createTab(sectors){
    var active='';
    if("#"+sectors['id']==activeTab)
        active='active';
    $('#ultabs').append("<li role='presentation' class='"+active+"' id='li"+sectors['id']+"'><a data-toggle='tab' href='#"+sectors['id']+"'><span lang='en'>"+sectors['item']+"</span></a></li>"); 
}

function createTableSectors(sector, enduse, fueltypes, endtype){

var id=sector['id'];
$('#'+id).html('');
var data=sector['clients'];
var hsec=sector['h'];
var doc = document;
var lng=Cookies('langCookie');
var fragment = doc.createDocumentFragment();

var autoid = document.createElement("input");
autoid.setAttribute("type", "hidden");
autoid.setAttribute("id", id+'_autoid');
autoid.setAttribute("value", sector['autoid']);
fragment.appendChild(autoid);

var tr = doc.createElement("tr");
var td = doc.createElement("td");
if(id!=="Hou" && id!=="Ene")
    td.innerHTML="<a style='padding-right:10px;'><i class='material-icons btngreen large' data-toggle='tooltip' title='"+window.lang.translate("Add new")+"' onclick='addRow()'>add_box</i></a>";
    
    tr.appendChild(td);

    if(id=="Hou"){
        var td = doc.createElement("td");
        td.innerHTML=window.lang.translate("Add new");
        tr.appendChild(td);
    }

for (var i=0; i<endtype.length;i++){
    if (endtype[i][id]=='Y' && endtype[i]['id']!='OT'){
        var td = doc.createElement("td");
        td.innerHTML=window.lang.translate(endtype[i]['value']);
        tr.appendChild(td);
    }
}

if (id=="Trp"){
//fuel types
$('#divfueltype').html('');
var fragmentfueltype = doc.createDocumentFragment();
var hfueltype = document.createElement("input");
hfueltype.setAttribute("type", "hidden");
hfueltype.setAttribute("id", 'fueltypelastid');
hfueltype.setAttribute("value", fueltypes[fueltypes.length-1]['id']);

fragmentfueltype.appendChild(hfueltype);
 var a = document.createElement('a');
 a.setAttribute("class", "pointer");
 a.setAttribute('data-toggle','modal');
 a.setAttribute('data-target','#fuelTypesModal');
 a.innerHTML=window.lang.translate("FUEL TYPES DEFINITION");
 a.classList="btn btn-primary pull-right";

var tablefueltypes = doc.createElement("table");
tablefueltypes.id='fueltypetable';
tablefueltypes.className="table";

var tbodyfueltypes = document.createElement("tbody");

var tr0 = doc.createElement("tr");
var td0 = doc.createElement("td");
td0.classList="td20";
td0.innerHTML="<a style='padding-right:10px' class='pointer'><i class='material-icons btngreen large' data-toggle='tooltip' title='Add new' lang='en' onclick='addRowFuelType()'>add_box</i></a>";
tr0.appendChild(td0);

var td = doc.createElement("td");
td.innerHTML="<span lang='en'>Fuel Name</span>";
tr0.appendChild(td);

var td = doc.createElement("td");
td.innerHTML="<span lang='en'>Fuel Type</span>";
tr0.appendChild(td);

var td = doc.createElement("td");
td.innerHTML="";
tr0.appendChild(td);

fragmentfueltype.appendChild(tr0);
for (var i = 0; i < fueltypes.length; i++) {
    fragmentfueltype.appendChild(addRowToTableFuelType(ftype, doc, fueltypes[i]['id'], fueltypes[i]['value'], fueltypes[i]['ftype']));
  }
  tbodyfueltypes.appendChild(fragmentfueltype);
  tablefueltypes.appendChild(tbodyfueltypes);
  document.getElementById('divfueltype').appendChild(tablefueltypes);
  document.getElementById('Trp').appendChild(a);
    // end fuel types

    var td = doc.createElement("td");
    td.innerHTML=window.lang.translate("Freight");
    tr.appendChild(td);

    var td = doc.createElement("td");
    td.innerHTML=window.lang.translate("Passenger InterCity");
    tr.appendChild(td);

    var td = doc.createElement("td");
    td.innerHTML=window.lang.translate("Public Passenger InterCity");
    td.classList='td100';
    tr.appendChild(td);

    var td = doc.createElement("td");
    td.innerHTML=window.lang.translate("Passenger Urban");
    tr.appendChild(td);

    var td = doc.createElement("td");
    td.innerHTML=window.lang.translate("Car");
    td.classList='tdcenter';
    tr.appendChild(td);

    var td = doc.createElement("td");
    td.innerHTML=window.lang.translate("Air plane");
    td.classList='tdcenter';
    tr.appendChild(td);

    fueltypes.unshift({'id':0, 'value':'---'});
}

fragment.appendChild(tr);
for (i = 0; i < data.length; i++) {
  fragment.appendChild(addRowToTable(id, data[i]['id'], data[i]['clientname'], doc, enduse, sector, i, fueltypes, endtype));
  if(data[i]['subclients']!==undefined || data[i]["id"].substring(0,3)=="Hou"){
    var tbl = doc.createElement("table");
    tbl.id='tbl'+data[i]['id'];
    tbl.className="table";

    var tbody = document.createElement("tbody");
    if(data[i]['subclients']!==undefined){
        for(j=0; j<data[i]['subclients'].length; j++){
            tbody.appendChild(addRowToSubClientTable(data[i]['subclients'][j]['id'], data[i]['subclients'][j]['subclientname'], doc, false));
        }
    }
    tbl.appendChild(tbody);
    fragment.appendChild(tbl);
  }
}

var table = doc.createElement("table");
table.id='tbl'+id;
table.className="table";

var tbody = document.createElement("tbody");
tbody.appendChild(fragment);
table.appendChild(tbody);

if(doc.getElementById(id)!==null)
    doc.getElementById(id).appendChild(table);
}

function addRowToSubClientTable(id, value, doc, newrow){
    var tr = doc.createElement("tr");
    var td = doc.createElement("td");
    var input = document.createElement("input");
    input.type = "text";
    if (newrow){
        input.id=value;
    }else{
        input.id=id;
    }
	
    input.value=value;
    input.className = "form-control tboxsub";
    td.appendChild(input); 
    tr.appendChild(td);

    var td = doc.createElement("td");
    td.innerHTML="<a class='deleteRow' onclick='deleteRow(this)'><i class='material-icons btnred' data-toggle='tooltip' title='"+window.lang.translate("Delete")+"'>close</i></a>";

    td.classList="td100";
    tr.appendChild(td);

    return tr;

}

function addRowToTable(idsector, idvalue, idclientname, doc, enduse, sector, itrp, fueltypes, endtype){
    var id=idsector;
    var tr = doc.createElement("tr");
    var td = doc.createElement("td");
    var input = document.createElement("input");
    input.type = "text";
	input.id=idvalue;
    input.value=idclientname;
    input.className = "form-control tbox";
    td.appendChild(input); 
    tr.appendChild(td);

    if (id=="Hou"){
        var td = doc.createElement("td");
        td.classList="td100";
        td.innerHTML="<a style='padding-right:10px'><i class='material-icons btngreen large' data-toggle='tooltip' title='"+window.lang.translate("Add new")+"' onclick=addRowSubClient(\""+idvalue+"\")>add_box</i></a>";
        tr.appendChild(td);
    }

    for (var i=0; i<endtype.length;i++){
        if (endtype[i][id]=='Y' && endtype[i]['id']!='OT'){
            var idendtype=endtype[i]['id'];
            var ap=enduse[idendtype+'_'+idvalue];
            var apchecked='';
            if(ap=='Y'){
                apchecked='checked';
            }
        
            var td = doc.createElement("td");
            td.classList="td100";
            td.innerHTML="<div class='pure-checkbox' style='padding-top:7px'><input type='checkbox' "+apchecked+" class='basic' id='"+idendtype+'_'+ idvalue +"'/><label for='"+idendtype+'_'+idvalue +"'></label></div>";
            tr.appendChild(td);
        }
    }

    if(id=='Trp'){
        var selected_fr_fl=0;

        if(sector!==''){
            selected_fr_fl=sector['clients'][itrp][idvalue+'_fr_fl'];
        }

        var td = doc.createElement("td");
        td.className="td200";

        var list_fr_fl = document.createElement("select");
        list_fr_fl.id = idvalue+'_fr_fl';
        list_fr_fl.classList='form-control fttransport';
        td.appendChild(list_fr_fl);

        for (var i = 0; i < fueltypes.length; i++) {
            var option = document.createElement("option");
            option.value = fueltypes[i]['id'];
            option.text = fueltypes[i]['value'];
            option.defaultSelected=(selected_fr_fl==fueltypes[i]['id']? true: false);
            list_fr_fl.appendChild(option);
        }

        tr.appendChild(td);

        var td = doc.createElement("td");
        td.classList="td200";
        var selected_ps_fl=0;
        if(sector!==''){
            selected_ps_fl=sector['clients'][itrp][idvalue+'_ps_fl'];
        }
        var list_ps_fl = document.createElement("select");
        list_ps_fl.id = idvalue+'_ps_fl';
        list_ps_fl.classList='form-control fttransport';
        td.appendChild(list_ps_fl);
        for (var i = 0; i < fueltypes.length; i++) {
            var option = document.createElement("option");
            option.value = fueltypes[i]['id'];
            option.text = fueltypes[i]['value'];
            option.defaultSelected=(selected_ps_fl==fueltypes[i]['id']? true: false);
            list_ps_fl.appendChild(option);
        }

        tr.appendChild(td);

        var td = doc.createElement("td");
        td.classList="td100";
        var psp='N';
        if(sector!==''){
            psp=sector['clients'][itrp][idvalue+'_psp'];
        }
        var pspchecked='';
        if(psp=='Y'){
            pspchecked='checked';
        }
        td.innerHTML="<div class='pure-checkbox' style='padding-top:7px'><input type='checkbox' class='basictr' "+pspchecked+" id='"+ idvalue +"_psp'/><label for='"+ idvalue +"_psp'></label></div>";
        tr.appendChild(td);

        var td = doc.createElement("td");
        td.classList="td200";
        var selected_psi_fl=0;
        if(sector!==''){
            selected_psi_fl=sector['clients'][itrp][idvalue+'_psi_fl'];
        }
        var list_psi_fl = document.createElement("select");
        list_psi_fl.id = idvalue+'_psi_fl';
        list_psi_fl.classList='form-control fttransport';
        td.appendChild(list_psi_fl);
        for (var i = 0; i < fueltypes.length; i++) {
            var option = document.createElement("option");
            option.value = fueltypes[i]['id'];
            option.text = fueltypes[i]['value'];
            option.defaultSelected=(selected_psi_fl==fueltypes[i]['id']? true: false);
            list_psi_fl.appendChild(option);
        }

        tr.appendChild(td);

        var td = doc.createElement("td");
        td.className="td100";
        var car='N';
        if(sector!==''){
            car=sector['clients'][itrp][idvalue+'_car'];
        }
        var carchecked='';
        if(car=='Y'){
            carchecked='checked';
        }
        td.innerHTML="<div class='pure-checkbox' style='padding-top:7px'><input type='checkbox' class='basictr' "+carchecked+" id='"+ idvalue +"_car'/><label for='"+ idvalue +"_car'></label></div>";
        tr.appendChild(td);

        var td = doc.createElement("td");
        td.classList="td100";
        var plane='N';
        if(sector!==''){
            plane=sector['clients'][itrp][idvalue+'_plane'];
        }
        var planechecked='';
        if(plane=='Y'){
            planechecked='checked';
        }
        td.innerHTML="<div class='pure-checkbox' style='padding-top:7px'><input type='checkbox' class='basictr' "+planechecked+" id='"+ idvalue +"_plane'/><label for='"+ idvalue +"_plane'></label></div>";
        tr.appendChild(td);

    }

    
    var td = doc.createElement("td");
    if((id!=="Hou" && itrp>0) || id=="Trp" )
    td.innerHTML="<a class='deleteRow' onclick='deleteRow(this)'><i class='material-icons btnred' data-toggle='tooltip' title='"+window.lang.translate("Delete")+"'>close</i></a>";
    td.classList="td100";
    tr.appendChild(td);

    return tr;
    
}

function addRowToTableFuelType(ftype, doc, fueltypeid, fueltypevalue, ftypevalue){
   
    var tr1 = doc.createElement("tr");
    var tdid = doc.createElement("td");
    tdid.classList="td20";
    tdid.innerHTML=fueltypeid;

    var h = document.createElement("input");
    h.setAttribute("type", "hidden");
    h.setAttribute("id", fueltypeid);
    h.setAttribute("class", 'fhidden');
    h.setAttribute("value", fueltypeid);
    tdid.appendChild(h);

    tr1.appendChild(tdid);



    var td1 = doc.createElement("td");
    td1.classList="td100";
    td1.innerHTML="<input type='text' id='fbox_"+fueltypeid+"' class='form-control fbox' value='"+fueltypevalue+"'/>";
    tr1.appendChild(td1);
   // fragmentfueltype.appendChild(tr);

    //select
    var td2 = doc.createElement("td");
    td2.className="td100";

    var list_fuel = document.createElement("select");
    list_fuel.id = 'fselect_'+fueltypeid;
    list_fuel.classList='form-control fselect';
    td2.appendChild(list_fuel);

    for (var j = 0; j < ftype.length; j++) {
        var option = document.createElement("option");
        option.value = ftype[j]['id'];
        option.text = window.lang.translate(ftype[j]['name']);
        option.defaultSelected=(ftypevalue==ftype[j]['id']? true: false);
        list_fuel.appendChild(option);
    }

    tr1.appendChild(td2);

    var td3 = doc.createElement("td");
    td3.innerHTML="<a class='deleteRow' id='deleteFuelType_"+fueltypeid+"'+ onclick='deleteRow(this)'><i class='material-icons btnred' data-toggle='tooltip' title='"+window.lang.translate("Delete")+"'>close</i></a>";
    td3.classList="td100";
    tr1.appendChild(td3);
    return tr1;
}

function findLastValue(tablename){
    var id=$('#'+tablename+' tr:last input').attr('id');
    if(id!==undefined){
        var res=id.split('_');
        return res[res.length - 1];
    }else{
        return 0;
    }
}

function validYears(n){
    $('#'+n.id).val(n.value.replace(/[^\d,]+/g, ''));
 }