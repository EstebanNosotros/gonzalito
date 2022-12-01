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
                           {{-- @can('create dispositivos')
                            <div class="card-header">
                                <h3 class="card-title">
                                    <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-nuevo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus"></i> Nuevo</a>
                                </h3>
                                <h3 class="card-title" style="margin-left: 20px;">
                                    <a href="#" class="btn btn-sm btn-secondary" data-backdrop="static" data-keyboard="false"><i class="fa-solid fa-arrows-rotate"></i> Actualizar</a>
                                </h3>
                            </div>
                            @endcan--}}
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Usuario</th>
                                            <th>Hash</th>
                                            <th>User Agent</th>
                                            <th>Creado</th>
                                            @can('delete dispositivos')
                                                <th>Acciones</th>
                                            @endcanany
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $i)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $i->nombre }}</td>
                                                <td>{{ $i->hash }}</td>
                                                <td>{{ $i->user_agent }}</td>
                                                <td>{{ $i->created_at }}</td>
                                                @can('delete dispositivos')
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $i->id }}" data-name="{{ $i->nombre }}"><i class="fas fa-trash"></i></button>
                                                        </div>
                                                    </td>
                                                @endcan
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
            // $(document).on("click", '.btn-edit', function() {
            //     let id = $(this).attr("data-id");
            //     $('#modal-loading').modal({backdrop: 'static', keyboard: false, show: true});
            //     $.ajax({
            //         url: "{{ route('dispositivos.show') }}",
            //         type: "POST",
            //         dataType: "JSON",
            //         data: {
            //             id: id,
            //             _token: "{{ csrf_token() }}"
            //         },
            //         success: function(data) {
            //             var data = data.data;
            //             $("#u_nombre").val(data.nombre);
            //             $("#id").val(data.id);
            //             $('#modal-loading').modal('hide');
            //             $('#modal-edit').modal({backdrop: 'static', keyboard: false, show: true});
            //         },
            //     });
            // });
            ///Modal show
            // $(document).on("click", '.btn-show', function() {
            //     let id = $(this).attr("data-id");
            //     $('#modal-loading').modal({backdrop: 'static', keyboard: false, show: true});
            //     $.ajax({
            //         url: "{{ route('dispositivos.show') }}",
            //         type: "POST",
            //         dataType: "JSON",
            //         data: {
            //             id: id,
            //             _token: "{{ csrf_token() }}"
            //         },
            //         success: function(data) {
            //             var data = data.data;
            //             $("#s_nombre").val(data.nombre);
            //             $('#modal-loading').modal('hide');
            //             $('#modal-show').modal({backdrop: 'static', keyboard: false, show: true});
            //         },
            //     });
            // });
            ///Modal delete
            $(document).on("click", '.btn-delete', function() {
                let id = $(this).attr("data-id");
                let usuario = $(this).attr("data-name");
                $("#did").val(id);
                $("#delete-data").html(usuario);
                $('#modal-delete').modal({backdrop: 'static', keyboard: false, show: true});
            });
        });
    </script>
@endsection

@section('modal')
    {{-- Modal Nuevo --}-}
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
                    <form action="{{ route('dispositivos.store') }}" method="POST" enctype="multipart/form-data">
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
    {{-- Modal Update --}-}
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
                    <form action="{{ route('dispositivos.update') }}" method="POST" enctype="multipart/form-data">
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
    {{-- Modal Show --}-}
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
                    <form action="{{ route('dispositivos.destroy') }}" method="POST" enctype="multipart/form-data">
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
