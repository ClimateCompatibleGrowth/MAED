crossroads.addRoute('/', function() {
    Cookies("id", "home");
    $(".page-content").load('layout/home.html');
    getPageTitle();
});

crossroads.addRoute('/ChangeMaed/{id}', function(id) {
    crossroads.ignoreState = true;
    Cookies("maedtype", id);
    Cookies("id", "study");
    $(".page-content").load('app/study/study.html');
    getPageTitle();
});

crossroads.addRoute('/ManageStudy', function() {
    crossroads.ignoreState = true;
    Cookies("id", "study");
    $(".page-content").load('app/study/study.html');
    getPageTitle();
});

crossroads.addRoute('/GeneralInformation/', function() {
    Cookies("id", "geninf");
    crossroads.ignoreState = true;
    let maedtype=Cookies("maedtype");
    $(".page-content").load('app/geninf/'+maedtype+'_geninf.html');
    getPageTitle();
});

crossroads.addRoute('/GeneralInformation/{id}', function(id) {
    crossroads.ignoreState = true;
    Cookies("id", "geninf");
    Cookies("titlecs", decodeURI(id));
    let maedtype=Cookies("maedtype");
    $(".page-content").load('app/geninf/'+maedtype+'_geninf.html');
    $("#maedmenu").load("layout/"+maedtype+"_menu.html", function() {
        getPageTitle();
        if(maedtype=="maedel")
            getClients();
    }).addClass("nav-list");
});

crossroads.addRoute('/GetData/{id}', function(id) {
    Cookies("id", id);
    $(".page-content").load('app/data/data.html');
    getPageTitle();
});

crossroads.addRoute('/GetDataCoefficients/{id}/{sector}/{client}/{year}', function(id, sector, client, year) {
    Cookies("id", id);
    Cookies('idsector', sector);
    Cookies('idclient', client);
    Cookies('year', year);
     $(".page-content").load('app/data/data.html');
     getPageTitle();
 });

crossroads.addRoute('/Calendar', function() {
    Cookies("id", "calendar");
    $(".page-content").load('app/calendar/maedel_calendar.html');
    getPageTitle();
});

crossroads.addRoute('/Calculation', function() {
    showloader();
    var maedtype = Cookies.get('maedtype');
    if(maedtype=="maedel"){
        Cookies("id","result_summary");     
    }else{
        Cookies("id","results");
    }
    $.ajax({
        url: 'app/calculation/'+maedtype+'_calculation.php',
        type: 'POST',
        success: function(result) {
            ShowSuccessMessage("Calculation finished succesfuly");
            $(".page-content").load('app/results/'+maedtype+'_results.html');
            hideloader();
        },
        error: function(xhr, status, error) {
            ShowErrorMessage(error);
            hideloader();
        }
    });
    getPageTitle();
});

crossroads.addRoute('/Results', function() {
    Cookies("id", "results");
    $(".page-content").load('app/results/maedd_results.html');
    getPageTitle();
});

crossroads.addRoute('/Results/{id}', function(id) {
    Cookies("id", id);
    $(".page-content").load('app/results/maedel_results.html');
    getPageTitle();
});

crossroads.addRoute('/Logout', function() {
    $.ajax({
        url: "auth/login/logout.php",
        async: true,
        type: 'POST',
        success: function (data) {
            if ($.trim(data) === "1") {
                window.location = 'index.html';
            }
        }
    });
});

crossroads.addRoute('/Data', function() {
    crossroads.ignoreState = false;
    localStorage.setItem("activePage",  null);
    $(".page-content").load('app/data/data.html');
});

crossroads.addRoute('/Users', function() {
    Cookies("id", "accounts");
    $(".page-content").load('auth/users/users.html');
    getPageTitle();
});
crossroads.addRoute('/Info', function() {
    Cookies("id", "about");
    $(".page-content").load('layout/info.html');
    getPageTitle();
});

crossroads.bypassed.add(function(request) {
    console.error(request + ' seems to be a dead end...');
});

//Listen to hash changes
window.addEventListener("hashchange", function() {
    var route = '/';
    var hash = window.location.hash;
    if (hash.length > 0) {
        route = hash.split('#').pop();
    }
    crossroads.parse(route);
});

// trigger hashchange on first page load
window.dispatchEvent(new CustomEvent("hashchange"));
