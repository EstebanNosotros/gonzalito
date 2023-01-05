@extends('admin.layouts.master')
@section('content')
    <style>
        #mostrar, #destacar, #u_mostrar, #u_destacar {transform: scale(1.5);}
        #breadcrumb_inicio {color:black !important;}
        .page-link, .btn-perfil {color:inherit !important; text-decoration: underline !important;}
    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $title }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#" id="breadcrumb_inicio">Inicio</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            {{--@can('create catalogo_auditorias')--}}
                            <div class="card-header">
                                <h4>Filtros de Busqueda</h4><br/>
                                <div class="row">
                                    <div style="width: fit-content; margin-right: 15px;">
                                        <table>
                                            <tr>
                                                <td><label for="searchByDesde">Desde:</label></td> <td><input type='date' id='searchByDesde' data-date-format='yyyy-mm-dd' placeholder='Escriba fecha de inicio de b&uacute;squeda'></td>
                                            </tr>
                                            <tr>
                                                <td><label for="searchByHasta">Hasta:</label></td> <td><input type='date' id='searchByHasta' placeholder='Escriba fecha de fin de b&uacute;squeda'></td>
                                            </tr>
                                            <tr>
                                                <td><label for="searchByName">Vendedor:</label></td> <td><input type='text' id='searchByName' placeholder='Buscar por nombre de vendedor'></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div style="width: fit-content; margin-right: 15px;">
                                        <table>
                                            <tr>
                                                <td><label for="searchByRol">Rol:</label></td> <td><select id='searchByRol'><option value="">Todos</option><option value="Vendedor">Vendedor</option><option value="Cliente">Cliente</option><option value="Desconocido">Desconocido</option></select></td>
                                            </tr>
                                            <tr>
                                                <td><label for="searchByObjeto">Objeto:</label></td> <td><select id='searchByObjeto'><option value="">Todos</option><option value="Login">Login</option><option value="Home">Home</option><option value="Categoria">Categoria</option><option value="Ofertas">Oferta</option><option value="Producto">Producto</option><option value="Busqueda">Busqueda</option><option value="Compartir">Compartir</option><option value="Otro">Otro</option></select></td>
                                            </tr>
                                            <tr>
                                                <td><label for="searchByReferencia">Referencia:</label></td> <td><input type='text' id='searchByReferencia' placeholder='Buscar por referencia'></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div style="width: fit-content; margin-right: 15px;">
                                        <table>
                                            <tr>
                                                <td><label for="searchByUrl">URL:</label></td> <td><input type='text' id='searchByUrl' placeholder='Buscar por URL'></td>
                                            </tr>
                                            <tr>
                                                <td><label for="searchByIp">Ip:</label></td> <td><input type='text' id='searchByIp' placeholder='Buscar por direcci&oacute;n de IP'></td>
                                            </tr>
                                            <tr>
                                                <td><label for="searchByDispositivo">Dispositivo:</label></td> <td><input type='text' id='searchByDispositivo' placeholder='Buscar por dispositivo'></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                {{--<div class="row" style="margin-bottom: -15px;"> <!-- display: flex; justify-content: center; align-items: center;"-->
                                    <label for="searchByDesde">Desde: <input type='date' id='searchByDesde' data-date-format='yyyy-mm-dd' placeholder='Escriba fecha de inicio de b&uacute;squeda'>&nbsp; <label for="searchByHasta">Hasta: <input type='date' id='searchByHasta' placeholder='Escriba fecha de fin de b&uacute;squeda'>&nbsp; <label for="searchByRol">Rol: <select id='searchByRol'><option value="">Todos</option><option value="Vendedor">Vendedor</option><option value="Cliente">Cliente</option><option value="Desconocido">Desconocido</option></select>&nbsp; <label for="searchByObjeto">Objeto: <select id='searchByObjeto'><option value="">Todos</option><option value="Login">Login</option><option value="Home">Home</option><option value="Categoria">Categoria</option><option value="Ofertas">Oferta</option><option value="Producto">Producto</option><option value="Busqueda">Busqueda</option><option value="Compartir">Compartir</option><option value="Otro">Otro</option></select>
                                </div>
                                <div class="row" style="margin-bottom: -30px;"> <!-- display: flex; justify-content: center; align-items: center;"-->
                                    <label for="searchByName">Vendedor: <input type='text' id='searchByName' placeholder='Buscar por nombre de vendedor'>&nbsp; <label for="searchByIp">Ip: <input type='text' id='searchByIp' placeholder='Buscar por direcci&oacute;n de IP'>&nbsp; <label for="searchByDispositivo">Dispositivo: <input type='text' id='searchByDispositivo' placeholder='Buscar por dispositivo'>&nbsp; <label for="searchByUrl">URL: <input type='text' id='searchByUrl' placeholder='Buscar por URL'>&nbsp; <label for="searchByReferencia">Referencia: <input type='text' id='searchByReferencia' placeholder='Buscar por referencia'>
                                </div>--}}
                                {{--<h3 class="card-title">
                                    <form method="GET" action="{{ route('catalogo_auditorias.getCatalogoAuditorias') }}">
                                        <label for="param_fec_ini"> Fecha de Inicio: </label><input type="text" id="param_fec_ini" name="param_fec_ini"/>
                                        <label for="param_fec_fin"> Fecha de Fin: </label><input type="text" id="param_fec_fin" name="param_fec_fin"/>
                                        <label for="param_ip"> IP: </label><input type="text" id="param_ip" name="param_ip"/>
                                        <button type="submit">Filtrar</button>
                                    </form>
                                </h3>
                                <h3 class="card-title" style="margin-left: 20px;">
                                    <a href="#" class="btn btn-sm btn-secondary" data-backdrop="static" data-keyboard="false"><i class="fa-solid fa-arrows-rotate"></i> Actualizar</a>
                                </h3>--}}
                            </div>
                            {{--@endcan--}}
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover" id="mainTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>IP</th>
                                            <th>Vendedor</th>
                                            <th>Rol</th>
                                            <th>Dispositivo</th>
                                            <th>URL</th>
                                            <th>Objeto</th>
                                            <th>Referencia</th>
                                            {{--@canany(['update catalogo_auditorias', 'delete catalogo_auditorias'])--}}
                                                <th>Acciones</th>
                                            {{--@endcanany--}}
                                        </tr>
                                    </thead>
                                    {{--<tbody>
                                        @foreach ($data as $i)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $i->created_at }}</td>
                                                <td>{{ $i->ip }}</td>
                                                <td>{{ $i->vendedor }}</td>
                                                <td>{{ $i->user_role }}</td>
                                                <td>{{ $i->user_agent }}</td>
                                                <td>{{ $i->url }}</td>
                                                <td>{{ $i->object }}</td>
                                                <td>{{ $i->object_reference }}</td>
                                                {{--@canany(['update catalogo_auditorias', 'delete catalogo_auditorias'])--}-}
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-info btn-show" data-id="{{ $i->id }}"><i class="fas fa-eye"></i></button>
                                                           {{-- @can('update catalogo_auditorias')
                                                                <button class="btn btn-sm btn-primary btn-edit" data-id="{{ $i->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                            @endcan
                                                            @can('delete catalogo_auditorias')
                                                                <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $i->id }}" data-name="{{ $i->nombre }}"><i class="fas fa-trash"></i></button>
                                                            @endcan --}}
                                                        </div>
                                                    </td>
                                                {{--@endcanany--}-}
                                            </tr>
                                        @endforeach
                                    </tbody>--}}
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // DataTable
            var dataTable = $('#mainTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                // ajax: "{{ route('getCatalogoAuditorias') }}",
                //'searching': false, // Remove default Search Control
                ajax: {
                    'url':"{{ route('getCatalogoAuditorias') }}",
                    'data': function(data){
                        // Read values
                        // var gender = $('#searchByGender').val();
                        var name        = $('#searchByName').val();
                        var ip          = $('#searchByIp').val();
                        var dispositivo = $('#searchByDispositivo').val();
                        var desde       = $('#searchByDesde').val();
                        var hasta       = $('#searchByHasta').val();
                        var url         = $('#searchByUrl').val();
                        var referencia  = $('#searchByReferencia').val();
                        var rol         = $('#searchByRol').val();
                        var objeto      = $('#searchByObjeto').val();
                        // Append to data
                        // data.searchByGender = gender;
                        data.searchByName        = name;
                        data.searchByIp          = ip;
                        data.searchByDispositivo = dispositivo;
                        data.searchByDesde       = desde;
                        data.searchByHasta       = hasta;
                        data.searchByUrl         = url;
                        data.searchByReferencia  = referencia;
                        data.searchByRol         = rol;
                        data.searchByObjeto      = objeto;
                    }
                },
                columns: [
                    { data: 'iteration' },
                    { data: 'fecha' },
                    { data: 'ip' },
                    { data: 'vendedor' },
                    { data: 'rol' },
                    { data: 'dispositivo' },
                    { data: 'url' },
                    { data: 'objeto' },
                    { data: 'referencia' },
                    { data: null ,
                      render: function ( data, type, row ) {
                        var field;
                        field = '<div class="btn-group">';
                        field = field + '<button class="btn btn-sm btn-info btn-show" data-id="'+data.id+'"><i class="fas fa-eye"></i></button>';
                        {{-- @can('update productos')
                            field = field + '<button class="btn btn-sm btn-primary btn-edit" data-id="'+data.id+'"><i class="fas fa-pencil-alt"></i></button>';
                        @endcan
                        @can('delete productos')
                            field = field + '<button class="btn btn-sm btn-danger btn-delete" data-id="'+data.id+'" data-name="'+data.nombre+'"><i class="fas fa-trash"></i></button>';
                        @endcan --}}
                        field = field + '</div>';
                        return field;
                      }
                    },
                ]
            });

            $('#searchByName').keyup(function(){
                dataTable.draw();
            });

            $('#searchByIp').keyup(function(){
                dataTable.draw();
            });

            $('#searchByDispositivo').keyup(function(){
                dataTable.draw();
            });

            $('#searchByDesde').change(function(){
                dataTable.draw();
            });

            $('#searchByHasta').change(function(){
                dataTable.draw();
            });

            $('#searchByUrl').keyup(function(){
                dataTable.draw();
            });

            $('#searchByReferencia').keyup(function(){
                dataTable.draw();
            });

            $('#searchByRol').change(function(){
                dataTable.draw();
            });

            $('#searchByObjeto').change(function(){
                dataTable.draw();
            });

            ///Modal edit
            $(document).on("click", '.btn-edit', function() {
                let id = $(this).attr("data-id");
                $('#modal-loading').modal({backdrop: 'static', keyboard: false, show: true});
                $.ajax({
                    url: "{{ route('catalogo_auditorias.show') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        var data = data.data;
                        $("#u_nombre").val(data.nombre);
                        $("#id").val(data.id);
                        $('#modal-loading').modal('hide');
                        $('#modal-edit').modal({backdrop: 'static', keyboard: false, show: true});
                    },
                });
            });
            ///Modal show
            $(document).on("click", '.btn-show', function() {
                let id = $(this).attr("data-id");
                $('#modal-loading').modal({backdrop: 'static', keyboard: false, show: true});
                $.ajax({
                    url: "{{ route('catalogo_auditorias.show') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        console.log(data.data);
                        var data = data.data;
                        $("#s_fecha").html(data.created_at);
                        $("#s_ip").html(data.ip);
                        $("#s_vendedor").html(data.vendedor);
                        $("#s_rol").html(data.user_role);
                        $("#s_dispositivo").html(data.user_agent);
                        $("#s_url").html(data.url);
                        $("#s_objeto").html(data.object);
                        $("#s_referencia").html(data.object_reference);
                        $('#modal-loading').modal('hide');
                        $('#modal-show').modal({backdrop: 'static', keyboard: false, show: true});
                    },
                });
            });
            ///Modal delete
            $(document).on("click", '.btn-delete', function() {
                let id = $(this).attr("data-id");
                let nombre = $(this).attr("data-nombre");
                $("#did").val(id);
                $("#delete-data").html(nombre);
                $('#modal-delete').modal({backdrop: 'static', keyboard: false, show: true});
            });
        });
    </script>
@endsection

@section('modal')
    {{-- Modal Nuevo --}}
    <div class="modal fade" id="modal-nuevo">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo Dato</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('catalogo_auditorias.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <label>Nombre</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" placeholder="Nombre" name="nombre" value="{{ old('nombre') }}">
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- Modal Update --}}
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Dato</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('catalogo_auditorias.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="input-group">
                            <label>Nombre</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('u_nombre') is-invalid @enderror" placeholder="Nombre" name="u_nombre" id="u_nombre" value="{{ old('u_nombre') }}">
                                @error('u_nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" name="id" id="id">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- Modal Show --}}
    <div class="modal fade" id="modal-show">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Datos de {{ $title }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <style>
                    .show_label {
                        margin-right: 10px;
                        margin-left: 10px;
                    }
                </style>
                <div class="modal-body">
                    <div class="input-group">
                        <label>Registro de Auditor&iacute;a</label>
                        <div class="input-group">
                            <table class="table-striped">
                                <tr>
                                    <td>
                                        <label class="show_label">Fecha: </label>
                                    </td>
                                    <td>
                                        <span id="s_fecha"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Ip: </label>
                                    </td>
                                    <td>
                                        <span id="s_ip"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Vendedor: </label>
                                    </td>
                                    <td>
                                        <span id="s_vendedor"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Rol: </label>
                                    </td>
                                    <td>
                                        <span id="s_rol"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Dispositivo: </label>
                                    </td>
                                    <td>
                                        <span id="s_dispositivo"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Url: </label>
                                    </td>
                                    <td>
                                        <span id="s_url"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Objeto: </label>
                                    </td>
                                    <td>
                                        <span id="s_objeto"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Referencia: </label>
                                    </td>
                                    <td>
                                        <span id="s_referencia"></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- Modal delete --}}
    <div class="modal fade" id="modal-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar Dato</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('catalogo_auditorias.destroy') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('DELETE')
                        <p class="modal-text">Seguro que desea borrar este registro? <b id="delete-data"></b></p>
                        <input type="hidden" name="id" id="did">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
