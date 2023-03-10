var lang = new Lang();
//lang.dynamic('en', 'references/jquery-lang/langpack/en.json');
lang.dynamic('es', 'references/jquery-lang/langpack/es.json');
lang.dynamic('fr', 'references/jquery-lang/langpack/fr.json');

lang.init({
	defaultLang: 'en'
});

function changeLang(lng){
    Cookies("langCookie", lng);
    window.lang.change(lng);
    $("#"+lng).addClass("active").siblings().removeClass("active");
}