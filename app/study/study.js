var studyUrl="app/study/study.php";
$(document).ready(function () {
    getCaseStudies();
    $("#maedmenu").html("");
})

function getCaseStudies() {
    var cs= Promise.resolve(
        $.ajax({
            url: studyUrl,
            type: 'POST',
            data: { action: 'get' }
        })
    );
    cs.then(data => showCaseStudies(JSON.parse(data)));
}

function showCaseStudies(cs) {
    $("#accordionstudy").html('');
    var html = [];
    html.push('<div class="box-shadow card ">');
    $.each(cs, function (index, value) {
        htmlstring = "";
        htmlstring += '<div class="panel panel-default">\
            <div class="panel-heading" style="padding-right: 0px !important;">\
            <table style="width: 100%;">\
                <tr><td>\
                    <b><a href="#/GeneralInformation/'+value['studyName']+'" style="display:block; width:100%" data-cs="' + value['studyName'] + '">\
                    <i class="material-icons link btngreen">folder_open</i> ' + value['studyName'] + '</a></b>\
                </td>\
                <td style="width:150px"><a href="#/GeneralInformation/'+value['studyName']+'" style="display:block; width:100%" data-cs="' + value['studyName'] + '">' + value['createdDate'] + '</a>\
                </td>\
                <td style="width:40px; text-align:center"><a onclick="copyCaseStudy(\'' + value['studyName'] + '\')">\
                    <i class="material-icons btnblue" data-lang-content="false" data-toggle="tooltip" lang="en" data-placement="top" title="Copy case">content_copy</i></a>\
                </td>\
                <td style="width:40px; text-align:center"><span data-toggle="modal" data-target="#modalbackup">\
                    <span class="backupCS" onclick="backupCaseStudy(\'' + value['studyName'] + '\')" data-cs="' + value['studyName'] + '">\
                    <i class="material-icons btngreen" data-toggle="tooltip" title="Backup case" lang="en" data-lang-content="false">file_download</i></span>\
                    </span>\
                </td>\
                <td style="width:40px; text-align:center"><a onclick="deleteCaseStudy(\'' + value['studyName'] + '\')">\
                    <i class="material-icons btnred" data-lang-content="false" data-toggle="tooltip" lang="en" data-placement="top" title="Delete case">close</i></a>\
                </td></tr>\
            </table>\
            </div></div>';
        html.push(htmlstring);
    })
    html.push('</div>')
    $("#accordionstudy").html(html.join(""));
    $("#studyNameTitle").html("");
}

function backupCaseStudy(studyname) {
    $("#studynameoriginal").val(studyname);
}

$('#fileupload').fileupload({
    url: 'app/study/study.php',
    add: function (e, data) {
        var uploadErrors = [];
        var acceptFileTypes= /(\.|\/)(zip|rar)$/i;
        var ext = data.originalFiles[0].name.split('.').pop().toLowerCase();
        if(data.originalFiles[0]['type'].length && !acceptFileTypes.test("."+ext)) {
            uploadErrors.push('Not an accepted file type!');
        }
        data.context = $('#selected-files').html(data.files[0].name);
        $("#btnrestore").off('click').on('click', function () {
            data.formData = { action: 'restore' };
            if(uploadErrors.length > 0) {
                ShowErrorMessage(uploadErrors.join("\n"));
                $('#selected-files').html('');
            } else {
                data.submit();
            }
        });
    },
    done: function (e, data) {
        try {
            if(data.result=="wrongformat"){
                ShowErrorMessage("Wrong case study format!");
            }else{
            showCaseStudies(JSON.parse(data.result));
            ShowSuccessMessage('Case successfuly restored');
        }
        hideloader();
        $('#selected-files').html('');
        } catch (e) {
            hideloader();
        }
    },
    fail: function (e, data) {
        console.log(data);
        hideloader();
        if (data.jqXHR.responseText=="Invalid case"){
            ShowErrorMessage('Invalid case!');
        }
    },
    progressall: function (e, data) {
        showloader();
    }
}).prop('disabled', !$.support.fileInput)
  .parent()
  .addClass($.support.fileInput ? undefined : 'disabled');

//Add new case  
function createCaseStudy() {
    var studyname = $("#studyname").val();
    var desc = $('#desc').val();
    if (studyname == '') {
        $('#fgstudyname').addClass('has-error');
        ShowErrorMessage("Study name is required!");
        return false;
    }
    if (clearName(studyname)) {
        $('#fgstudyname').addClass('has-error');
        ShowErrorMessage("Allowed characters [a-Z] [0-9] [-_]!");
        return false;
    }else{
    $.ajax({
        url: studyUrl,
        data: { action: 'create', studyname: studyname, desc:desc },
        type: 'POST',
        success: function (result) {
            if (result === 'exists') {
                ShowErrorMessage('Case with same name already exists!');
            } else {
                ShowSuccessMessage('Case successfuly added');
                showCaseStudies(JSON.parse(result));
                $('#studyname').val('');
                $('#fgstudyname').removeClass('has-error');
            }
        },
        error: function (xhr, status, error) {
            ShowErrorMessage(error);
        }
    });
}
}

function copyCaseStudy(studyname) {
    $.ajax({
        url: studyUrl,
        data: { action: 'copy', studyname: studyname },
        type: 'POST',
        success: function (result) {
            showCaseStudies(JSON.parse(result));
            ShowSuccessMessage('Case successfuly copied');
        },
        error: function (xhr, status, error) {
            ShowErrorMessage(error);
        }
    });
}

function deleteCaseStudy(studyname) {
    bootbox.confirm({
        title: "MESSAGE",
        message: "Are You sure that You want to DELETE Case " + studyname + "?",
        buttons: {
            cancel: { label: '<i class="material-icons btnred link mti17">close</i> Close'},
            confirm: { label: '<i class="material-icons link mti17">done</i> Confirm'}
        },
        callback: function (resultcs) {
            if (resultcs) {
                $.ajax({
                    url: studyUrl,
                    data: { studyname: studyname, action: 'delete' },
                    type: 'POST',
                    success: function (result) {
                        showCaseStudies(JSON.parse(result));
                        ShowSuccessMessage('Case successfuly deleted');
                    },
                    error: function (xhr, status, error) {
                        ShowErrorMessage(error);
                    }
                });
            }
        }
    });
}

