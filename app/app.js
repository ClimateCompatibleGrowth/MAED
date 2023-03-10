$(document).ready(function() {
    changeLang(Cookies.get('langCookie'));
    var maedtype=Cookies("maedtype");
    if(!maedtype)
        Cookies("maedtype","maedd");

    getPageTitle();
	
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
})

function getTabs(tabs, translates) {
    var html = "";
    if(tabs!=null){
        html += '<ul class="nav nav-tabs">';
        for (var i = 0; i < tabs.length; i++) {
            html += '<li role="presentation"><a href="#/GetData/'+ tabs[i] +'" id="' + tabs[i] + '"><span lang="en">' + translates[i] + '</span></a></li>';
        }
        html += '</ul>';
        $('#tabs').html(html);
    }
    return html;
}

function exportExcel(g, id) {
    showloader();
   
    if (g == 'gridResults') {
        var activelink=$("#title").html().replace("/","-");
        $("#" + g).jqxGrid('exportdata', 'xls', activelink, true, null, true, 'references/jqwidgets/save-file.php');
    } else {
        var activelink = $("#gridTitle").html().replace("/","-");
        var grid = wijmo.Control.getControl("#" + g);
        var chart = grid.columns.getColumn('chart');
        if (chart !== null)
            chart.visible = false;

        var result = wijmo.grid.ExcelConverter.export(grid, {
            includeColumnHeader: true
        });

        if (navigator.msSaveBlob) {
            var blob = new Blob([result.base64Array]);
            navigator.msSaveBlob(blob, activelink);
        } else {
            $(id).attr("download", activelink);
            id[0].href = result.href();
        }

        if (chart !== null)
            chart.visible = true;
    }
    hideloader();
}

function decUp(g){
    if(window.d>=0)
    window.d++;
    var grid = wijmo.Control.getControl("#"+g);
    var maedtype = Cookies('maedtype');
    var column=1;
    if(maedtype=="maedd"){
        column=4;
    }
    SetDecimalPlaces(grid, window.d, column, 'right');
}

function decDown(g){
    if(window.d>0)
        window.d--;
    var grid = wijmo.Control.getControl("#"+g);
    var maedtype = Cookies('maedtype');
    var column=1;
    if(maedtype=="maedd"){
        column=4;
    }
    if(window.d>=0){
        for (var i = 0; i < grid.columns.length-1; i++) {
            if (i >= column){
                grid.columns[i].format ='f'+window.d;
            }
        }
    }
}

function SetDecimalPlaces(grid, precision, fromcolumn, align) {
    if (precision >= 0) {
        for (var i = 0; i < grid.columns.length; i++) {
            if (i >= fromcolumn) {
                grid.columns[i].format = 'f' + precision;
            }
        }
    }
}

function ShowSuccessMessage(message = "Data saved successfully") {
    $('#msgcontainer').html('');
    divmessage = document.createElement('div');
    var classsuccess = "alert alert-success alert-dismissable box-shadow--2dp";
    $(divmessage).addClass(classsuccess)
        .attr('id', 'msg')
        .html('<b>'+window.lang.translate("Success")+'</b></br>' + window.lang.translate(message))
        .appendTo($("#msgcontainer"))
    $('#msg').delay(3000).fadeOut('slow');
}

function ShowErrorMessage(message) {
    $('#msgcontainer').html('');
    divmessage = document.createElement('div');
    $(divmessage).addClass("alert alert-danger box-shadow--2dp")
        .attr('id', 'msg')
        .html('<b>Error !</b></br>' + message)
        .appendTo($("#msgcontainer"))
    $('#msg').delay(5000).fadeOut('slow');
}

function ShowWarningMessage(message) {
    $('#msgcontainer').html('');
    divmessage = document.createElement('div');
    $(divmessage).addClass("alert alert-warning alert-dismissable box-shadow--2dp")
        .attr('id', 'msg')
        .html('<b>Warning !</b></br>' + message)
        .appendTo($("#msgcontainer"))
}

function getPageTitle() {
    var login=Cookies("l");
    if(login=="0"){
        $("#loginAbout").show();
    }
    if(login=="1"){
        $("#loginMenu").show();
    }

    $("#pageTitle").html("");
    $("#subtitle").show();
    $('.nav-list li.active').removeClass('active');
    var maedtype = Cookies.get('maedtype');
    if(maedtype=="maedel"){
        $("#activeMAED").html("MAED EL");
    }else{
        $("#activeMAED").html("MAED D");
    }
    var lng=Cookies.get("langCookie");
    var id = Cookies.get('id');
    var title = $('#'+id+' span').html();
    var group=getGroup(id);

    switch(group) {
        case "home":
            $("#subtitle").hide();    
        break;
        case "coefficient":
            title="Coefficients"; 
            var idsector=Cookies.get("idsector");
            $("#"+idsector).parent().addClass('active'); 
          break;

        case "result":
        case "electricity":
        case "economic":
          title=$('#'+group+' span').html();
          break;

        case "industry":
        case "transport":
        case "household":
        case "service":
            title=$('#energy').html();
            break;
        case "study":
            if(maedtype=="maedd"){
                title='MAED D CASE STUDIES';
            }else{
                title='MAED EL CASE STUDIES';
            }
            
            $("#subtitle").hide();
            break;
        case "about":
            title="About";
            $("#subtitle").hide();
            break;
        case "accounts":
            title="Accounts";
            $("#subtitle").hide();
        break;

        default:
            break;       
    }
    $("#pageTitle").html(title);
    var titlecs=Cookies("titlecs");
    $("#studyNameTitle").html(titlecs);
    $('#' + id).parent().addClass('active');
    if(group!="")
    $('#' + group).parent().addClass('active');
    $("#"+maedtype).addClass("active activemaedtype").siblings().removeClass("active activemaedtype");
    $("#"+lng).addClass("active").siblings().removeClass("active");
}

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function showloader() {
    $('#preloader').show();
}

function hideloader() {
    $('#preloader').hide();
}

function loadXMLDoc(dname) {
    var xmlDoc;
    try {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open('GET', dname, false);
        xmlhttp.setRequestHeader('Content-Type', 'text/xml');
        xmlhttp.setRequestHeader("Cache-Control", "no-cache, no-store, must-revalidate");
        xmlhttp.send('');
        xmlDoc = xmlhttp.responseXML;
    } catch (e) {
        try {
            xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
        } catch (e) {
            console.error(e.message);
        }
    }
    return xmlDoc;
}


function getYears(years, coefftype) {
    var html = "";
    var idsector = Cookies.get('idsector');
    var idclient = Cookies.get('idclient');
    var year = Cookies.get('year');
    for (var i = 0; i < years.length; i++) {
        html += "<li><a href=\"#/GetDataCoefficients/" + coefftype + "/" + idsector + "/" + idclient + "/" + years[i] + "\">" + years[i] + "</a></li>";
    }
    $('#yearscoefflist').html(html);
    $('#yearscoeff').show();
    $('#changeyear').html(year);
    //$('#' + idsector).addClass('active').siblings().removeClass('active');
    //$('#' + coefftype).parent().addClass('active').siblings().removeClass('active');
}

function exportChart() {
    var title=$("#titlechart").html();
    if(title==undefined){
        title=$("#gridTitle").html();
    }
    $('#chartResults').jqxChart('saveAsJPEG', title+'.jpeg', 'references/jqwidgets/export.php');
}

function removeModal() {
    $('.modal').remove();
    $('.modal-backdrop').remove();
    $('body').removeClass("modal-open");
}

function InArray(number, array) {
    if (jQuery.inArray(number, array) != -1) {
        return true;
    } else {
        return false;
    }
}

function CreateGrid(allyear, cv, grid) {
    var cols = [{ header: 'conf', binding: 'conf', width: 50, visible: false },
        { header: 'id', binding: 'id', width: 50, visible: false },
        { header: item_header, binding: 'item', width: 270, isReadOnly: true },
        { header: unit_header, binding: 'unit', width: 100, isReadOnly: true, align: 'right' }];

    for (var y = 0; y < allyear.length; y++) {
        cols.push({ header: ' ' + allyear[y], binding: ' ' + allyear[y], width: 90, dataType: 2, format: 'f' + getdecimal() });
    }
    cols.push({ header: window.lang.translate('Chart'), binding: 'chart', dataType: 3, width: 50 });
    grid.initialize({
        autoGenerateColumns: false,
        columns: cols,
        itemsSource: cv,
        allowSorting: false,
        frozenColumns: 4
    })
    FormatGrid(grid);
    ReadOnlyRow(grid);
    grid.pasted.addHandler(function(s, e) {
        FormatGrid(grid);
    });
}

function ReadOnlyRow(grid) {
    grid.beginningEdit.addHandler(function(s, e) {
        if ((e.col > 3 && e.col < s.columns.length - 1 && grid.itemsSource.items[e.row]['readonly'] == true)) {
            e.cancel = true;
        }
    });
}

function FormatGrid(grid) {
    jQuery.each(grid.itemsSource.items, function(i, val) {
        if (val['css'] != undefined && val['css'] != null) {
            grid.rows[i].cssClass = val['css'];
        }
    });
    grid.refresh();
}

function isNumber(number) {
    return isNaN(parseFloat(number)) ? '' : parseFloat(number);
}

function check100(number) {
    var ret = false;
    if (!isNaN(number) && number !== '') {
        if (number.toFixed(2) == 100.00) {
            ret = true;
        }
    }
    return ret;
}

function getClients() {
    $.ajax({
        url: 'app/geninf/maedel_geninf.php',
        type: 'POST',
        data: {
            action: "select"
        },
        success: function(results) {
            var html = "";
            var res = JSON.parse(results);
            var sectors = res['sectors'];
            var firstyear = res['allyear'][0];
            for (var i = 0; i < sectors.length; i++) {
                var numberofclients = 0;
                if (sectors[i]['clients'] != null) {
                    numberofclients = sectors[i]['clients'].length;
                    html += "<li><a id=" + sectors[i]['id'] + " href='#/GetDataCoefficients/coefficient_weekly/"+sectors[i]['id'] +"/"+sectors[i]['clients'][0]["id"]+"/"+firstyear+"'><i class='menu-icon material-icons'>play_arrow</i>" + sectors[i]['item'] + " (" + numberofclients + ")</a></li>";
                }
            }
            $('#coefficients').html(html);
        //    var idsector = Cookies.get('idsector');
        }
    })
}

function getClientsList(sectors, coefftype) {
    var html = "";
    var clientname = "";
    var idsector = Cookies.get('idsector');
    var idclient = Cookies.get('idclient');
    var year = Cookies.get('year');
    for (var i = 0; i < sectors.length; i++) {
        if (sectors[i]['id'] == idsector) {
            for (j = 0; j < sectors[i]['clients'].length; j++) {
                var client = sectors[i]['clients'][j];
                if (client['id'] == idclient) {
                    clientname = client['clientname'];
                }
                html += "<li><a href=\"#/GetDataCoefficients/"+ coefftype + "/" + idsector + "/" + client['id'] + "/" + year + "\">" + client['clientname'] + "</a></li>";
            }
        }
    }
    $('#clientscoeff').show();
    $('#clientscoefflist').html(html);
    $('#changeclient').html(clientname);
   // $('#' + idsector).addClass('activelink').siblings().removeClass('activelink');
}

function getdecimal() {
    var dec = Cookies('decimal');
    if (dec === undefined) {
        dec = 3;
    }
    return dec;
}

function getGroup(id){
    var ret="";
    if(id){
        ret=id.split("_")[0];
    }
    return ret;
}

var clearName = function(name) {
    return name.match(/[^a-z0-9 _-]/gi, '') ? true:false; 
};

var tabs=[];
//D
tabs['economic']=['economic_demography', 'economic_gdp'];
tabs['service']=['service_factors', 'service_intensity', 'service_penetration'];
tabs['household']=['household_factors_urban', 'household_factors_rural'];
tabs['transport']=['transport_freight_generation', 'transport_freight_modal', 'transport_freight_intensity', 'transport_intercity_factors', 'transport_intercity_modal', 'transport_intercity_intensity',
    'transport_urban_factors', 'transport_urban_modal', 'transport_urban_intensity', 'transport_international'];
tabs['industry']=['industry_intensity_motivepower', 'industry_intensity_electricity', 'industry_intensity_thermal',
    'industry_efficiency_penetration', 'industry_efficiency_factors',
    'industry_manufacturing_factors', 'industry_manufacturing_penetration', 'industry_manufacturing_ratio'];
//EL
tabs['electricity']=['electricity_final_demand', 'electricity_supplied_from_grid', 'electricity_demand_clients', 'electricity_losses'];
tabs['coefficient']=['coefficient_weekly', 'coefficient_daily', 'coefficient_hourly'];

var translates=[];
//D
translates['economic']=['Demography', 'GDP'];
translates['economic_title']=['Demography', 'GDP'];
translates['service']=[ 'Basic Data& Factors', 'Energy intensities', 'Penetration & Efficiencies'];
translates['household']=['Urban', 'Rural'];
translates['transport']=['Freight-Demand', 'Freight-Modal split', 'Freight-EI', 'Intercity-Load Factors', 'Intercity-Modal split', 'Intercity-EI', 'Urban-Load Factors', 'Urban-Modal split', 'Urban-EI', 'International'];
translates['transport_title']=['Freight Transportation: Generation of freight-kilometers', 'Modal split of freight transportation', 'Energy intensities of freight transportation',  'Intercity Passenger Transportation : Load Factors', 'Modal split of cars intercity transportation', 'Energy intensities of intercity transportation', 'Load factors (person per mode type)', 'Modal split of intracity passenger transportation', 'Energy intensities of urban transportation', 'Energy consumption of international transportation'];
translates['industry']=['EI-Motive Power', 'EI-Specific Electricity use', 'EI-Thermal use', 'Penetration of Energy Forms in ACM', 'Efficiencies in ACM', 'Temperature level in Manufacturing', 'Penetration of Energy Forms in Manufacturing', 'Efficiencies in Manufacturing'];
translates['industry_title']=['Energy intensities of Motive Power (final energy per unit of value added)', 'Energy intensities of Specific Electricity use (final energy per unit of value added)', 'Energy intensities of Thermal uses (useful energy per unit of value added)', 'Penetrations of energy forms into useful thermal energy in Agriculture, Construction and Mining',  'Average Efficiencies and Factors of energy forms in Thermal uses in Agriculture, Construction and Mining', 'Share of useful thermal energy demand by temperature level in Manufacturing', 'Penetration of Energy Carriers into Useful Thermal Energy Demand in Manufacturing', 'Average Efficiencies of energy forms in Thermal uses, Ratios and Factors in Manufacturing'];
//EL
translates['electricity']=['Annual electricity demand', 'Electricity supplied from the grid', 'Electricity demand per client', 'Transmission and distribution losses'];
translates['coefficient']=['Weekly coefficients', 'Daily coefficients', 'Hourly coefficients'];