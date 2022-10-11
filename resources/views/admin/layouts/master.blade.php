
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title." - ".Setting::getValue('app_name') }}</title>
        <link rel="icon" href="{{ asset(Setting::getValue('app_favicon')) }}" type="image/png" />
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <!--ink rel="stylesheet" href="{{ asset('template/admin/plugins/fontawesome-free/css/all.min.css') }}"-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('template/admin/dist/css/adminlte.min.css') }}">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <!-- PWA  -->
        <meta name="theme-color" content="#6777ef"/>
        <link rel="apple-touch-icon" href="{{ asset('logo.PNG') }}">
        <link rel="manifest" href="{{ asset('/manifest.json') }}">
        <!-- Estilos personalizados de cada pagina -->
        @stack('style')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        @php
            if (!$errors->isEmpty()) {
                alert()->error('Aviso', implode('<br>', $errors->all()))->toToast()->toHtml();
            }
        @endphp
        <div class="wrapper">
            <!-- Preloader -->
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="{{ asset(Setting::getValue('app_loading_gif')) }}" alt="{{ Setting::getName('app_name') }}" height="60" width="60">
            </div>

            <!-- Navbar -->
            @include('admin.layouts.navbar')
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            @include('admin.layouts.sidebar')

            <!-- Content Wrapper. Contains page content -->
            @yield('content')
            <!-- /.content-wrapper -->
            @yield('modal')
            @include('admin.layouts.modal')
            @include('sweetalert::alert')
            @include('admin.layouts.footer')
        </div>
        <!-- ./wrapper -->
        <!-- jQuery -->
        <script src="{{ asset('template/admin/plugins/jquery/jquery.min.js') }}"></script>
        <!-- SELECT 2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @yield('js')
        @include('admin.layouts.script')
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset('template/admin/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <!-- DataTables  & Plugins -->
        <script src="{{ asset('template/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('template/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('template/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- overlayScrollbars -->
        <script src="{{ asset('template/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('template/admin/dist/js/adminlte.js') }}"></script>
        <script>
            //Datatable Language Spanish
            $.fn.dataTable.ext.errMode = 'none';
            $('.datatable').DataTable({
                "language": {
                    "sProcessing":    "Procesando...",
                    "sLengthMenu":    "Mostrar _MENU_ registros",
                    "sZeroRecords":   "No se encontraron resultados",
                    "sEmptyTable":    "Ningún dato disponible en esta tabla",
                    "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":   "",
                    "sSearch":        "Buscar:",
                    "sUrl":           "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":    "Último",
                        "sNext":    "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        </script>
        @stack('script')
        <!-- PWA -->
        <script src="{{ asset('/sw.js') }}"></script>
        <script>
            if (!navigator.serviceWorker.controller) {
                navigator.serviceWorker.register("/sw.js").then(function (reg) {
                    console.log("Service worker has been registered for scope: " + reg.scope);
                })
                .catch(function(err) {
                    console.log("Service worker failed : ", err);
                });
            }
        </script>
    </body>
</html>