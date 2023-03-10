var urlEResult='app/results/maedel_results.php';
var charttype='line';
var item_header = window.lang.translate('Item');
var unit_header = window.lang.translate('Unit');
    $(document).ready(function () {
        showloader();
        var id = Cookies('id');
        $.getScript('app/results/maedel_' + id + '.js', function () { 
            $.ajax({
                url: urlEResult,
                data: { id: id, action: 'get' },
                type: 'POST',
                success: function (res) {
                    var results = jQuery.parseJSON(res);
                    $('#allyears').val(results['allyear'].join(","));
                    showresults(results);
                    $('#'+id).parent().addClass('active').siblings().removeClass('active');
                    hideloader();
                },
                error: function (xhr, status, error) {
                    ShowErrorMessage(error);
                    hideloader();
                }
            });
        });

        $('#export').attr('download', id);
        $(".changeChart").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.charttype=$(this).attr('id');

            var chart1 = $('#chartResults').jqxChart('getInstance');
            chart1.seriesGroups[0].type = window.charttype;
            chart1.update();
        });
    });

    // function loadTabResults(id){
    //     Cookies('content', id);
    //     $('#content').load('maedel/views/results.html');
    // }

    function exportLoadsy() {
        showloader();
        var maedtype = Cookies.get('maedtype');
        $.ajax({
            url: urlEResult,
            data: {
                id: "result_wasp",
                action: 'get'
            },
            type: 'POST',
            success: function() {
                window.location = 'app/results/maedel_wasp.php';
                ShowSuccessMessage("WASP Loadsy file successfully created")
                hideloader();
            },
            error: function(xhr, status, error) {
                ShowErrorMessage(error);
                hideloader();
            }
        });
    }