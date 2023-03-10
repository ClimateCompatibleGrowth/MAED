$(document).ready(function () {
    access();
    $(document).on('click', '[data-dismiss="modal"]', function(){clearform();})
})

// change password
function changepasswordstart() {
    $('#content').load('auth/changepassword/changepassword.html');
    $('.titlebox').html('<div class="modultitle" style="padding-left:15px">CHANGE PASSWORD</div>');

}

function changepassword() {
    $.ajax({
        url: "auth/users/users.php",
        async: true,
        type: 'POST',
        data: { action: 'changepassword', currentpassword: $('#currentpassword').val(), newpassword: $('#newpassword').val() },
        success: function (data) {
            if ($.trim(data) === "success") {
                ShowSuccessMessage('Password is changed successfully')
            } else {
                ShowErrorMessage(data);
            }
        }
    });
}

//user management
function users() {
    $('#content').load('auth/users/users.html');
    $('.titlebox').html('<div class="modultitle" style="padding-left:15px">USERS</div>');
}

function adduser() {

    if ($('#username').val() == '') {
        ShowErrorMessage('Username required');
        return false;
    }

    if ($('#password').val() == '') {
        ShowErrorMessage('Password required');
        return false;
    }
    var isadmin=false;
    if ($('#isadmin').is(':checked')) {
        isadmin=true;
    }

    $.ajax({
        url: "auth/users/users.php",
        async: true,
        type: 'POST',
        data: { action: 'adduser', username: $('#username').val(), password: $('#password').val(), usergroup: $("#usergroup").val(), isadmin:isadmin, language:$('#language').val(), maedtype:$('#maedtype').val(), decimal:
        $('#decimalplaces').val()  },
        success: function (data) {
            if ($.trim(data) === "success") {
                ShowSuccessMessage('User added successfully')
                getusers();
                $('#addUserModal').modal('hide');
            } else {
                ShowErrorMessage(data);
            }
        }
    });
}

function edituser(){
    var getselectedrowindexes = $('#gridUsers').jqxGrid('getselectedrowindexes');
    if (getselectedrowindexes.length > 0) {
        var selectedRowData = $('#gridUsers').jqxGrid('getrowdata', getselectedrowindexes[0]);
        $('#usergroup').val(selectedRowData['usergroup']);
        $('#isadmin').prop("checked", selectedRowData['isadmin']);
        $('#username').val(selectedRowData['username']);
        $('#language').val(selectedRowData['language']);
        $('#maedtype').val(selectedRowData['maedtype']);
        $('#username').prop("readonly", true);
        $('#password').hide();
        $('#adduser').hide();
        $('#passwordpolicy').hide();
        $('#labelpassword').hide();
        $('#labelusername').hide();
        $('#updateuser').show();
        $('#labelusertitle').html("EDIT USER");
        $('#addUserModal').modal('show');
    }
}

function updateuser(){
    var isadmin=false;
    if ($('#isadmin').is(':checked')) {
        isadmin=true;
    }
    $.ajax({
        url: "auth/users/users.php",
        async: true,
        type: 'POST',
        data: { action: 'updateuser', username: $('#username').val(), usergroup: $("#usergroup").val(), isadmin:isadmin, language:$('#language').val(), maedtype:$('#maedtype').val(), decimal:$('#decimalplaces').val()  },
        success: function (data) {
            if ($.trim(data) === "success") {
                ShowSuccessMessage('User updated successfully');
              //  clearform();
                getusers();
            } else {
                ShowErrorMessage(data);
            }
        }
    });
    
}

//delete user
function deleteuser() {
    bootbox.confirm({
        title: "MESSAGE",
        message: "Are You sure that You want to DELETE user?",
        buttons: {
            cancel: {
                label: '<i class="material-icons btnred link mti17">close</i> Close'
            },
            confirm: {
                label: '<i class="material-icons link mti17">done</i> Confirm'
            }
        },
        callback: function (resultdr) {
            if (resultdr) {

                var getselectedrowindexes = $('#gridUsers').jqxGrid('getselectedrowindexes');
                if (getselectedrowindexes.length > 0) {
                    var selectedRowData = $('#gridUsers').jqxGrid('getrowdata', getselectedrowindexes[0]);
                    $.ajax({
                        url: "auth/users/users.php",
                        async: true,
                        type: 'POST',
                        data: { action: 'deleteuser', username: selectedRowData['username'] },
                        success: function (data) {
                            if ($.trim(data) === "success") {
                                ShowSuccessMessage('User deleted successfully')
                                getusers();
                            } else {
                                ShowErrorMessage(data);
                            }
                        }
                    });
                }
            }
        }
    })

}

function getusers() {
        $.ajax({
            url: 'auth/users/users.php',
            type: 'POST',
            data: { action: 'getusers'},
            success: function(results) {
                //fields and columns for grids
                fields = [];
                fields.push({ name: 'username', map: 'username', type: 'string' });
                fields.push({ name: 'usergroup', map: 'usergroup', type: 'string' });
                fields.push({ name: 'language', map: 'language', type: 'string' });
                fields.push({ name: 'maedtype', map: 'maedtype', type: 'string' });
                fields.push({ name: 'isadmin', map: 'isadmin', type: 'boolean' });
                fields.push({ name: 'decimal', map: 'decimal', type: 'number' });
            
                cols = [];
                cols.push({ text: 'Username', datafield: 'username', width: '30%' });
                cols.push({ text: 'Group', datafield: 'usergroup', width: '20%' });
                cols.push({ text: 'Language', datafield: 'language', width: '10%' });
                cols.push({ text: 'Default maed', datafield: 'maedtype', width: '20%' });
                cols.push({ text: 'Manage users', datafield: 'isadmin', width: '10%' });
                cols.push({ text: 'Decimal places', datafield: 'decimal', width: '10%' });

                var res = JSON.parse(results);
                var users =
                {
                  //  datatype: "json",
                    datafields: fields,
                  //  root: 'users',
                    localdata: res
                };
                var dataAdapter = new $.jqx.dataAdapter(users);

                $("#gridUsers").jqxGrid(
                    {
                        width: '100%',
                        theme: 'metro',
                        source: dataAdapter,
                        altrows: true,
                        enabletooltips: true,
                        columns: cols
                    });
            }
        })
}

function getgroups() {
        $.ajax({
            url: 'auth/users/users.php',
            type: 'POST',
            data: { action: 'getgroups'},
            success: function(results) {
                //fields and columns for grids
                fields = [];
                fields.push({ name: 'name', map: 'name', type: 'string' });

                cols = [];
                cols.push({ text: 'Name', datafield: 'name', width: '100%' });

                var res = JSON.parse(results);
                var users =
                {
                  //  datatype: "json",
                    datafields: fields,
                  //  root: 'users',
                    localdata: res
                };
                var dataAdapter = new $.jqx.dataAdapter(users, {
                    loadComplete: function (records) {
                        var $dropdown = $("#usergroup");
                        $dropdown
                            .find('option')
                            .remove()
                            .end();
                        $.each(records, function () {
                            $dropdown.append($("<option />").val(this.name).text(this.name));
                        });
            
                    }
                });

                $("#gridGroups").jqxGrid(
                    {
                        width: '100%',
                        theme: 'metro',
                        source: dataAdapter,
                        altrows: true,
                        enabletooltips: true,
                        columns: cols
                    });
            }
        })

}

function addgroup() {

    if ($('#name').val() == '') {
        ShowErrorMessage('Group name is required');
        return false;
    }

    $.ajax({
        url: "auth/users/users.php",
        type: 'POST',
        data: { action: 'addgroup', name: $('#name').val() },
        success: function (data) {
            if ($.trim(data) === "success") {
                ShowSuccessMessage('Group added successfully')
                getgroups();
                $('#addGroupModal').modal('hide');
            } else {
                ShowErrorMessage(data);
            }
        }
    });
}

//delete group
function deletegroup() {
    bootbox.confirm({
        title: "MESSAGE",
        message: "Are You sure that You want to DELETE group?",
        buttons: {
            cancel: {
                label: '<i class="material-icons btnred link mti17">close</i> Close'
            },
            confirm: {
                label: '<i class="material-icons link mti17">done</i> Confirm'
            }
        },
        callback: function (resultdr) {
            if (resultdr) {

                var getselectedrowindexes = $('#gridGroups').jqxGrid('getselectedrowindexes');
                if (getselectedrowindexes.length > 0) {
                    var selectedRowData = $('#gridGroups').jqxGrid('getrowdata', getselectedrowindexes[0]);
                }
                $.ajax({
                    url: "auth/users/users.php",
                    async: true,
                    type: 'POST',
                    data: { action: 'deletegroup', name: selectedRowData['name'] },
                    success: function (data) {
                        if ($.trim(data) === "success") {
                            ShowSuccessMessage('Group deleted successfully')
                            getgroups();
                        } else {
                            ShowErrorMessage(data);
                        }
                    }
                });

            }
        }
    })

}

// logout
function logout() {
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
}

// check access
function access() {
    $.ajax({
        url: "auth/login/access.php",
        async: true,
        type: 'POST',
        success: function (data) {
            if ($.trim(data) === "-1") {
                window.location = 'index.html';
            } else {
                arr = data.split('|');
                $('#user').html(arr[0]);
                if (arr[2] == "true") {
                    $('#manageusers').show();
                }
            }
        }
    });
}

function clearform(){
    $('#usergroup').val('admin');
    $('#isadmin').prop("checked", false);
    $('#username').prop("readonly", false);
    $('#username').val('');
    $('#language').val('en');
    $('#maedtype').val('maedd');
    $('#decimal').val(3);
    $('#password').show();
    $('#adduser').show();
    $('#updateuser').hide();
    $('#passwordpolicy').show();
    $('#labelpassword').show();
    $('#labelusername').show();
    $('#labelusertitle').html("ADD USER");
}