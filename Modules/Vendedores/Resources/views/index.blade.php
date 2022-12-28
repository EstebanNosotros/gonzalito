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
                            @can('create vendedores')
                            <div class="card-header">
                                <!--h3 class="card-title">
                                    <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-nuevo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus"></i> Nuevo</a>
                                </h3-->
                                <form action="{{ route('vendedores.synchronize') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <h3 class="card-title" style="margin-left: 20px;">
                                        {{--<input type="checkbox" id="chkSelectAll"> &nbsp; <span style="font-weight: 400;" id="selectSelectAll">Seleccionar Todos</span> &nbsp;--}}  <button type="submit" class="btn btn-sm btn-success" data-backdrop="static" data-keyboard="false"><i class="fa-solid fa-arrows-rotate"></i> Sincronizar</button>
                                    </h3>
                                </form>
                            </div>
                            @endcan
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre Completo</th>
                                            <th>C.I.</th>
                                            <th>Referencia</th>
                                            <th>Tel&eacute;fono</th>
                                            <th>Clave</th>
                                            <th>Última Modificación</th>
                                            {{--@canany(['update vendedores', 'delete vendedores'])--}}
                                                <!--th>Acciones</th-->
                                            {{--@endcanany--}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $i)
                                            <tr>
                                                <td>{{--<input type="checkbox" class="chkUpdate" data-id="{{ $i->id }}"> &nbsp; --}}{{ $loop->iteration }}</td>
                                                <td>{{ $i->nombre }}</td>
                                                <td>{{ $i->ci }}</td>
                                                <td>{{ $i->referencia }}</td>
                                                <td>{{ $i->contacto }}</td>
                                                <td>{{ $i->key }}</td>
                                                <td>{{ $i->updated_at }}</td>
                                                {{--@canany(['update vendedores', 'delete vendedores'])-}}
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-info btn-show" data-id="{{ $i->id }}"><i class="fas fa-eye"></i></button>
                                                            @can('update vendedores')
                                                                <button class="btn btn-sm btn-primary btn-edit" data-id="{{ $i->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                            @endcan
                                                            @can('delete vendedores')
                                                                <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $i->id }}" data-name="{{ $i->nombre }}"><i class="fas fa-trash"></i></button>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                {{--@endcanany--}}
                                            </tr>
                                        @endforeach
                                    </tbody>
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
            $("#chkSelectAll").on('click', function(){
                this.checked ? $(".chkUpdate").prop("checked",true) : $(".chkUpdate").prop("checked",false);  
            });
            $("#selectSelectAll").click( function() {
                // alert('existo');
                $("#chkSelectAll").click(); // prop("checked") ? $("#chkSelectAll").prop("checked", false) : $("#chkSelectAll").prop("checked", true);
            });
            ///Modal edit
            $(document).on("click", '.btn-edit', function() {
                let id = $(this).attr("data-id");
                $('#modal-loading').modal({backdrop: 'static', keyboard: false, show: true});
                $.ajax({
                    url: "{{ route('vendedores.show') }}",
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
                    url: "{{ route('vendedores.show') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        var data = data.data;
                        $("#s_nombre").val(data.nombre);
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
                    <form action="{{ route('vendedores.store') }}" method="POST" enctype="multipart/form-data">
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
                    <form action="{{ route('vendedores.update') }}" method="POST" enctype="multipart/form-data">
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
                        <label>Nombre</label>
                        <div class="input-group">
                            <table class="table-striped">
                                <tr>
                                    <td>
                                        <label class="show_label">Nombre: </label>
                                    </td>
                                    <td>
                                        <span id="s_nombre"></span>
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
                    <form action="{{ route('vendedores.destroy') }}" method="POST" enctype="multipart/form-data">
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
