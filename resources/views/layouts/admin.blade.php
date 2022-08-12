<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'RPC Bulacan - Administration Panel') }}</title>
        <link rel="stylesheet" href="{{ asset('admin/vendors/feather/feather.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/ti-icons/css/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/css/vendor.bundle.base.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/ti-icons/css/themify-icons.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/js/select.dataTables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/mdi/css/materialdesignicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/vertical-layout-light/style.css') }}">
        <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}" />
        <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-select.css') }}" rel="stylesheet">
        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
        @livewireStyles
    </head>
    <body>
        <div class="container-scroller">
            @include('partials.admin.header')
            <div class="container-fluid page-body-wrapper">
                @include('partials.admin.sidebar')
                <div class="main-panel">
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                    @include('partials.admin.footer')
                </div>
            </div>
        </div>
        @livewireScripts
        <script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>
        <script src="{{ asset('admin/vendors/chart.js/Chart.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/datatables.net/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset('admin/js/dataTables.select.min.js') }}"></script>
        <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
        <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
        <script src="{{ asset('admin/js/template.js') }}"></script>
        <script src="{{ asset('admin/js/settings.js') }}"></script>
        <script src="{{ asset('admin/js/todolist.js') }}"></script>
        <script src="{{ asset('admin/js/dashboard.js') }}"></script>
        <script src="{{ asset('admin/js/Chart.roundedBarCharts.js') }}"></script>
        <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
        <script src="{{ asset('js/select2.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
        <script type="text/javascript">
            document.addEventListener('livewire:load', function () {
               function showSwal(event) {
                   swal(event.detail.title, event.detail.msg, event.detail.type);
               }
               $("#birth_year").datepicker({
                    format: "yyyy",
                    viewMode: "years", 
                    minViewMode: "years",
                    autoclose:true 
               });
               window.addEventListener('download-id', event => {
                   window.location = event.detail.rt;
               });
               window.addEventListener('download-failed', event => {
                   showSwal(event);
               });
               $('#multi-municipality').selectpicker();
               window.addEventListener('init-birth-year', event => {
                    $("#birth_year_edit").datepicker({
                        format: "yyyy",
                        viewMode: "years", 
                        minViewMode: "years",
                        autoclose:true 
                    });
               });
               window.addEventListener('getMunVal', event => {
                    var val = $('#multi-municipality').val();
                    var rname = event.detail.role.name;
                    var rid = event.detail.role.id;
                    if(rname == "admin" || rname == "site lead" || rname == "superadmin") {
                        Livewire.emit('setMunicipality', val, event.detail.data, rid, rname);
                    } else {
                        if(val == null || val == "") {
                            swal("Ooopss!", "You must select at least one municipality.", "error");
                        } else {
                            Livewire.emit('setMunicipality', val, event.detail.data, rid, rname);
                        }
                    }
               });
               window.addEventListener('getMunVal2', event => {
                    var mun = event.detail.mun;
                    var rname = event.detail.role.name;
                    var rid = event.detail.role.id;
                    if(rname == "admin" || rname == "site lead" || rname == "superadmin") {
                        Livewire.emit('updateSaved', '', event.detail.data, rid, rname, event.detail.id);
                    } else {
                        if(mun.length == 0) {
                            swal("Ooopss!", "You must select at least one municipality.", "error");
                        } else {
                            Livewire.emit('updateSaved', mun, event.detail.data, rid, rname, event.detail.id);
                        }
                    }
               });
               window.addEventListener('initiate-select', event => {
                    $('#multi-municipality').selectpicker();
               });
               window.addEventListener('roleSaved', event => {
                    $('#addRole').modal('hide');
                    swal("Done!", "Role was created successfully.", "success");
               });
               window.addEventListener('roleUpdated', event => {
                    $('#editRole').modal('hide');
                    swal("Done!", "Role was updated successfully.", "success");
               });
               window.addEventListener('roleDeleted', event => {
                    swal("Sorry!", "Role was deleted successfully.", "success");
               });
               window.addEventListener('roleFailed', event => {
                    $('#addRole').modal('hide');
                    $('#editRole').modal('hide');
                    swal("Ooopss!", event.detail.msg, "error");
               });
               window.addEventListener('userCreated', event => {
                    $('#addUser').modal('hide');
                    swal("Done!", "User was created successfully.", "success");
               });
               window.addEventListener('userUpdated', event => {
                    $('#editUser').modal('hide');
                    $('#municipality-edit').val(null).trigger('change');
                    swal({
                        title: "Done!",
                        text: "User was updated successfully.",
                        type: "success",
                        confirmButtonClass: "btn-success"
                    }).then((result) => {
                        if(result.value) {
                            window.location = "{{ route('users') }}";
                        }
                    });
               });
               window.addEventListener('userFailed', event => {
                    $('#addUser').modal('hide');
                    $('#editUser').modal('hide');
                    swal("Ooopss!", event.detail.msg, "error");
               });
               window.addEventListener('userDeleted', event => {
                    swal("", "User was deleted successfully.", "success");
               });
               window.addEventListener('user-create', event => {
                    swal({
                        position: 'top-start',
                        title: event.detail.title, 
                        text: event.detail.msg, 
                        showCancelButton: true,
                        customClass: 'swal-size',
                        confirmButtonClass: "btn-success"
                    }).then((result) => {
                        if(result.value) {
                            window.livewire.emit('save', event.detail.info);
                        }
                    });
                });
                window.addEventListener('id-exist', event => {
                    swal({
                        title: event.detail.title, 
                        text: event.detail.msg, 
                        type: event.detail.type
                    }).then((result) => {
                        if(result.value) {
                            window.location = event.detail.rt;
                        }
                    });
                });
                window.addEventListener('user-exist', event => {
                    swal({
                        title: event.detail.title, 
                        text: event.detail.msg, 
                        type: event.detail.type,
                        showCancelButton: true,
                        confirmButtonClass: "btn-success"
                    }).then((result) => {
                        if(result.value) {
                            window.livewire.emit('save', event.detail.info);
                        } else {
                            window.location = event.detail.rt;
                        }
                    });
                });
                window.addEventListener('user-limit', event => {
                    swal({
                        title: event.detail.title, 
                        text: event.detail.msg, 
                        type: event.detail.type
                    }).then((result) => {
                        if(result.value) {
                            window.location = event.detail.rt;
                        }
                    });
                });
                window.addEventListener('update-birth-year', event => {
                    var byear = $("#birth_year").data('datepicker').getFormattedDate('yyyy');
                    var byear2 = $("#birth_year_edit").data('datepicker').getFormattedDate('yyyy');
                    $("#birth_year").val(byear);
                    $("#birth_year_edit").val(byear2);
                });
                window.addEventListener('registrantUpdate', event => {
                    var byear = $("#birth_year_edit").data('datepicker').getFormattedDate('yyyy');
                    if(byear != "" || byear != null) {
                        $('#editRegistrant').modal('hide');
                        Livewire.emit('changed', event.detail.info, byear, event.detail.id);
                    } else {
                        swal("Oopss!", "You must provide a birth year.", "error");
                    }
                });
                window.addEventListener('registrant-create', event => {
                    var byear = $("#birth_year").data('datepicker').getFormattedDate('yyyy');
                    if(byear != "" || byear != null) {
                        $('#addRegistrant').modal('hide');
                        Livewire.emit('saved', event.detail.info, event.detail.msg, byear);
                    } else {
                        swal("Oopss!", "You must provide a birth year.", "error");
                    }
                });
                window.addEventListener('user-saved', event => {
                    swal(event.detail.title, event.detail.msg, event.detail.type);
                });
                window.addEventListener('user-failed', event => {
                    swal(event.detail.title, event.detail.msg, event.detail.type);
                });
                window.addEventListener('registrantDeleted', event => {
                    swal("", "Registrant was deleted successfully.", "success");
                });
                window.addEventListener('registrantUpdated', event => {
                    swal("Done!", "Registrant was updated successfully.", "success");
                });
                window.addEventListener('passwordUpdated', event => {
                    swal({
                        title: "Done!",
                        text: "Password was updated successfully.",
                        type: "success"
                    }).then((result) => {
                        if(result.value) {
                            window.location = event.detail.rt;
                        }
                    });
                });
                window.addEventListener('passwordUpdateFailed', event => {
                    swal("Ooopss!!", event.detail.msg, "error");
                });
                window.addEventListener('passwordNotFound', event => {
                    swal("Ooopss!", "Password not found.", "error");
                });
                window.addEventListener('passUpdated', event => {
                    $('#changePassword').modal('hide');
                    swal("Done!", "Password was changed successfully.", "success");
                });
                window.addEventListener('passFailed', event => {
                    $('#changePassword').modal('hide');
                    swal("Ooopss!!", "There was an error during the process.", "error");
                });
                window.addEventListener('rolePermissionAdded', event => {
                    $('#addPermission').modal('hide');
                    swal("Done!", "Permission was added for a role successfully.", "success");
                });
                window.addEventListener('permissionSaved', event => {
                    $('#addPerm').modal('hide');
                    swal("Done!", "Permission was saved successfully.", "success");
                });
                window.addEventListener('permissionFailed', event => {
                    $('#addPerm').modal('hide');
                    swal("Ooopss!!", "There was an error during the process.", "error");
                });
                window.addEventListener('permissionUpdated', event => {
                    $('#editPermission').modal('hide');
                    $('#addPerm').modal('hide');
                    swal("Done!", "Permission was updated successfully.", "success");
                });
                window.addEventListener('statusUpdated', event => {
                    $('#changeStatus').modal('hide');
                    var msg = "User was de-activated successfully.";
                    if(event.detail.stat == "active") {
                        msg = "User was activated successfully.";
                    } 
                    swal("Done!", msg, "success");
                });
                window.addEventListener('populate-municipality', event => {
                    var cVal = event.detail.mid;
                    if ($('#muns' + cVal).is(":checked")) {
                        var val = $('#muns' + cVal).val();
                        Livewire.emit('addMunCode', val, 'checked');
                    } else {
                        Livewire.emit('removeMunCode', cVal);
                    }
                });
                window.addEventListener('initialize-checkbox', event => {
                    var mun = event.detail.mun;
                    if(mun != null) {
                        Livewire.emit('addMunCode', mun, 'initialized');
                    }
                });
            });
        </script>
    </body>
</html>