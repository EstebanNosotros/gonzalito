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
                            @can('create avisos')
                            <div class="card-header">
                                <h3 class="card-title">
                                    <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-nuevo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus"></i> Nuevo</a>
                                </h3>
                            </div>
                            @endcan
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>T&iacute;tulo</th>
                                            <th>Creado</th>
                                            @canany(['update avisos', 'delete avisos'])
                                                <th>Acciones</th>
                                            @endcanany
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $i)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $i->titulo }}</td>
                                                <td>{{ $i->created_at }}</td>
                                                @canany(['update avisos', 'delete avisos'])
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-info btn-show" data-id="{{ $i->id }}"><i class="fas fa-eye"></i></button>
                                                            @can('update avisos')
                                                                <button class="btn btn-sm btn-primary btn-edit" data-id="{{ $i->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                            @endcan
                                                            @can('delete avisos')
                                                                <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $i->id }}" data-name="{{ $i->titulo }}"><i class="fas fa-trash"></i></button>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                @endcanany
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
            ///Modal edit
            $(document).on("click", '.btn-edit', function() {
                let id = $(this).attr("data-id");
                $('#modal-loading').modal({backdrop: 'static', keyboard: false, show: true});
                $.ajax({
                    url: "{{ route('avisos.show') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        var data = data.data;
                        $("#u_titulo").val(data.titulo);
                        $("#u_cuerpo").val(data.cuerpo);
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
                    url: "{{ route('avisos.show') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        var data = data.data;
                        console.log(data);
                        $("#s_titulo").html(data.titulo);
                        $("#s_cuerpo").html(data.cuerpo);
                        if(data.usuarios) {
                            $('#s_celda_leido').html('');
                            var leidos = '<ul>';
                            data.usuarios.forEach(recorreLeidos);
                            function recorreLeidos(item) {
                                leidos += '<li>'+item+'</li>';
                            }
                            leidos += '</ul>';
                            $('#s_celda_leido').html(leidos);
                        }
                        $('#modal-loading').modal('hide');
                        $('#modal-show').modal({backdrop: 'static', keyboard: false, show: true});
                    },
                });
            });
            ///Modal delete
            $(document).on("click", '.btn-delete', function() {
                let id = $(this).attr("data-id");
                let nombre = $(this).attr("data-name");
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
                    <h4 class="modal-title">Nuevo Aviso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('avisos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <label>T&iacute;tulo</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" placeholder="T&iacute;tulo del aviso" name="titulo" value="{{ old('titulo') }}">
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Cuerpo</label>
                            <div class="input-group">
                                <!--input type="text" class="form-control @error('cuerpo') is-invalid @enderror" placeholder="Cuerpo del aviso" name="cuerpo" value="{{ old('cuerpo') }}"-->
                                <textarea id="cuerpo" name="cuerpo" rows="4" cols="50" class="form-control @error('cuerpo') is-invalid @enderror" placeholder="Cuerpo del aviso">
                                    {{ old('cuerpo') }}
                                </textarea>
                                @error('cuerpo')
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
                    <h4 class="modal-title">Editar Aviso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('avisos.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="input-group">
                            <label>T&iacute;tulo</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('u_titulo') is-invalid @enderror" placeholder="T&iacute;tulo del aviso" name="u_titulo" id="u_titulo" value="{{ old('u_titulo') }}">
                                @error('u_titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Cuerpo</label>
                            <div class="input-group">
                                <!--input type="text" class="form-control @error('cuerpo') is-invalid @enderror" placeholder="Cuerpo del aviso" name="cuerpo" value="{{ old('cuerpo') }}"-->
                                <textarea id="u_cuerpo" name="u_cuerpo" rows="4" cols="50" class="form-control @error('u_cuerpo') is-invalid @enderror" placeholder="Cuerpo del aviso">
                                    {{ old('u_cuerpo') }}
                                </textarea>
                                @error('u_cuerpo')
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
                    <h4 class="modal-title">Datos de Aviso</h4>
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
                        <div class="input-group">
                            <table class="table-striped">
                                <tr>
                                    <td>
                                        <label class="show_label">T&iacute;tulo: </label>
                                    </td>
                                    <td>
                                        <span id="s_titulo"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Cuerpo: </label>
                                    </td>
                                    <td>
                                        <span id="s_cuerpo"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Le&iacute;do por: </label>
                                    </td>
                                    <td id="s_celda_leido">
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
                    <form action="{{ route('avisos.destroy') }}" method="POST" enctype="multipart/form-data">
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
