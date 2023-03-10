var url="app/geninf/maedel_geninf.php";
$(document).ready(function () {
    getDataGenInf();
});

function addRow(){
   var id=getlastvalue('tblSectors', 'tbox');
   var idvalue='S'+id;
   var row=addRowToSectorTable(idvalue, true, id, id);
   $('#bodySectors').append(row);
}

function addRowClient(id){
    var doc = document;
    var id1=getlastvalue('tblclient'+id, 'tboxclient');
    var idvalue=id+'_'+id1;
    var row=addRowToClientTable(id, idvalue,idvalue, doc, true);
     
    $('#tblclient'+id+' tbody').append(row);
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
        $.ajax
            ({
                type: "POST",
                url: url,
                data:{
                      id:$('#id').val(),
                      studyName: studyName,
                      Year: year,
                      Desc:$('#Desc').val(),
                      action: "update"
                },
                success: function () {ShowSuccessMessage('Data saved successfully'); $('#fgstudyname').removeClass('has-error'); },
                failure: function () { ShowErrorMessage("Error!"); }
            });
        }
}

function saveDataSectors(){
    var sectors=[];
    var tbox = $('#tblSectors').find('.tbox');
    var sectors=[];
    tbox.each(function() {
        var sector={};
        sector['id']=this.id;
        sector['value']=this.value;
        sector['sub1']='N';
        var sub1=$('#MP_' + this.id).is(":checked");
        if(sub1){
            sector['sub1']='Y';
        }

        sector['fname']='';

        var ida='';
        var clients={};
        var tboxclient = $('#tblclient'+this.id).find('.tboxclient');
        tboxclient.each(function() {
            ida=ida+','+this.id;    
            clients[this.id]=this.value;
        })
        ida=ida.substring(1);
        if(ida!='' && ida!=undefined){
        clients[this.id+'_A']=ida;
        clients[this.id+'_H']=tboxclient.length+2;
        clients['SID']=this.id;
        clients['id']=$('#'+this.id+'_autoid').val();
        sector['clients']=clients;
    }
        sectors.push(sector);
    });

    $.ajax
    ({
        type: "POST",
        url: url,
        data:{
              datasectors:sectors,
              action: "updatesector"
        },
        success: function () { getDataGenInf(); getClients(); ShowSuccessMessage('Data saved successfully') },
        failure: function () { ShowErrorMessage("Error!"); }
    });
}

function getDataGenInf(){
    $.ajax({
        url: url,
        type: 'POST',
        data:{
            action: "select"
      },
        success: function (results) {
            var res = JSON.parse(results);
            var sectors=res['sectors'];
            var geninf=res['geninf'];
            $('#studyNameTitle').html(geninf['studyName']);
            $('#id').val(geninf['id']);
            $('#studyName').val(geninf['studyName']);
            $('#Year').val(geninf['Year']);
            $('#Desc').val(geninf['Desc']);
            $('#bodySectors').html('');
            $('#bodySectors').html("<tr><td colspan='4'> \
            <div style='width:220px'> \
            <a onclick='addRow()' style='padding-right:10px;'> \
                <i class='material-icons btngreen large' data-toggle='tooltip' title='Add new sector' lang='en' data-lang-content='false'>add_box</i><div style='float:right; padding-top:7px;' lang='en'>Add new sector</div></a> \
            </div> \
            </td> \
            </tr> \
            <tr class='silver silverborder bold'> \
                <td lang='en'>Sectors</span></td> \
                <td lang='en'>Coeficient of the base year</td> \
                <td></td> \
                <td lang='en'>Add new client</td> \
                <td lang='en'>Clients</td> \
            </tr><tr><td colspan='5' style='height:5px'></td></tr>")
            for(var i=0; i<sectors.length;i++){
                addRowToSectorTable(sectors[i], false, i);
            }
        }
    })
}

function addRowToSectorTable(sector, newrow, i){

var value='';
var id='';
var data=[];
if(newrow==false){
    value=sector['item'];
    id=sector['id'];
    data=sector['clients'];
}else{
    value=sector+'_value';
    id=sector;
}

var doc = document;
var fragment = doc.createDocumentFragment();

var autoid = document.createElement("input");
autoid.setAttribute("type", "hidden");
autoid.setAttribute("id", id+'_autoid');
if(sector['autoid']==undefined){
    var autoidval=$('input[type=hidden]').last().val();
    autoid.setAttribute("value", autoidval*1+1);
}else{
    autoid.setAttribute("value", sector['autoid']);
}

fragment.appendChild(autoid);

var checked='';
if(sector['sub1']=='Y'){
    checked='checked';
}

//input
var trinput = doc.createElement("tr");
trinput.classList="silverborder";
var td = doc.createElement("td");
var input = document.createElement("input");
input.type = "text";
input.id=id;

input.value=value;
input.autocomplete="off";
input.className = "form-control tbox";
td.appendChild(input); 
trinput.appendChild(td);
//

var td = doc.createElement("td");
td.innerHTML="<span class='pure-checkbox' style='padding-top:7px'><input type='checkbox' class='basic' " +checked+" id='MP_"+ id +"'/><label for='MP_"+ id +"'></label></span>";
td.classList="td100";
trinput.appendChild(td);

var td = doc.createElement("td");
if(i>0 && (data==undefined || data==null)){
    td.innerHTML="<a class='deleteRow' onclick='deleteRow(this)'><i class='material-icons btnred' data-lang-content='false' data-toggle='tooltip' title='Delete sector' lang='en'>close</i></a>";
    td.classList="td50";
}
trinput.appendChild(td);

var td = doc.createElement("td");
td.innerHTML="<a style='padding-right:10px'><i class='material-icons btngreen large' lang='en' data-toggle='tooltip' data-lang-content='false' title='Add new client' onclick=addRowClient(\""+id+"\")>add_box</i></a>";
td.classList="td100";
trinput.appendChild(td);

 var tdclient = doc.createElement("td");
 var tbl = doc.createElement("table");
 tbl.id='tblclient'+sector['id'];
 tbl.className="table";
 var tbody = document.createElement("tbody");

if(data!==undefined && data!=null){
    for (i = 0; i < data.length; i++) {
        tbody.appendChild(addRowToClientTable(id, data[i]['id'], data[i]['clientname'], doc, sector, i));
    }
}
tbl.appendChild(tbody);
tdclient.appendChild(tbl)
trinput.appendChild(tdclient);
fragment.appendChild(trinput);
//add extra space after sector
var trspace = doc.createElement("tr");
var tdspace = doc.createElement("td");
tdspace.colSpan=5;
tdspace.classList="h25";
trspace.appendChild(tdspace);
fragment.appendChild(trspace);
doc.getElementById("bodySectors").appendChild(fragment);
}

function addRowToClientTable(idsector, idvalue, idclientname, doc, sector, itrp,){
    var id=idsector;
    var doc = document;
    var fragment = doc.createDocumentFragment();
    var tr = doc.createElement("tr");
    var td = doc.createElement("td");
    var input = document.createElement("input");
    input.type = "text";
	input.id=idvalue;
    input.value=idclientname;
    input.className = "form-control tboxclient";
    input.autocomplete="off";
    td.appendChild(input); 
    tr.appendChild(td);

    var td = doc.createElement("td");
    td.innerHTML="<a class='deleteRow' onclick='deleteRow(this)'><i class='material-icons btnred' data-lang-content='false' data-toggle='tooltip' title='Delete client' lang='en'>close</i></a>";
    td.classList="td50";
    tr.appendChild(td);

    fragment.appendChild(tr);
    return tr;
}


function getlastvalue(tablename, css){
    var id=$('#'+tablename+' input.'+css+':last').attr('id');
    if(tablename=="tblSectors"){
        if(id=="AAA"){return 1;}else{ return (id.substring(1, id.length) *1)+1;}
    }else{
        if(id==undefined){
            return 2;
        }else{
            return id.split('_')[1]*1+1;
        }
        
    }
}

function validYears(n){
    $('#'+n.id).val(n.value.replace(/[^\d,]+/g, ''));
 }