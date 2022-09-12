@extends('admin.layouts.master')
@section('content')
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
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
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
                            @can('create categorias')
                            <div class="card-header">
                                <h3 class="card-title">
                                    <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-nuevo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus"></i> Actualizar</a>
                                </h3>
                            </div>
                            @endcan
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Nombre Web</th>
                                            <th>Imagen</th>
                                            <th>Icono</th>
                                            <th>Referencia</th>
                                            <th>Mostrar</th>
                                            <th>Destacar</th>
                                            <th>Actualizado</th>
                                            @canany(['update categorias', 'delete categorias'])
                                                <th>Accciones</th>
                                            @endcanany
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $i)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $i->nombre }}</td>
                                                <td>{{ $i->nombre_web }}</td>
                                                <td><img src="{{ asset($i->imagen) }}" alt="{{ $i->imagen }}" width="8%"></td>
                                                <td><img src="{{ asset($i->icono) }}" alt="{{ $i->icono }}" width="8%"></td>
                                                <td>{{ $i->referencia }}</td>
                                                <td>{{ $i->mostrar }}</td>
                                                <td>{{ $i->destacar }}</td>
                                                <td>{{ $i->updated_at }}</td>
                                                @canany(['update categorias', 'delete categorias'])
                                                    <td>
                                                        <div class="btn-group">
                                                            @can('update categorias')
                                                                <button class="btn btn-sm btn-primary btn-edit" data-id="{{ $i->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                            @endcan
                                                            @can('delete categorias')
                                                                <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $i->id }}" data-name="{{ $i->name }}"><i class="fas fa-trash"></i></button>
                                                            @endcan                                                        </div>
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
            $(document).on("click", '.btn-edit', function() {
                let id = $(this).attr("data-id");
                $('#modal-loading').modal({backdrop: 'static', keyboard: false, show: true});
                $.ajax({
                    url: "{{ route('categorias.show') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        var data = data.data;
                        $("#u_nombre").val(data.nombre);
                        $("#u_nombre_web").val(data.nombre_web);
                        $("#id").val(data.id);
                        if (data.mostrar == 1) {
                            $('#u_mostrar').prop('checked', true);
                            $('#u_mostrar').val(1);
                        }else {
                            $('#u_mostrar').prop('checked', false);
                            $('#u_mostrar').val(1);
                        }
                        if (data.destacar == 1) {
                            $('#u_destacar').prop('checked', true);
                            $('#u_destacar').val(1);
                        }else {
                            $('#u_destacar').prop('checked', false);
                            $('#u_destacar').val(1);
                        }
                        $('#u_imagen').val(data.imagen);
                        $('#u_icono').val(data.icono);
                        $('#u_imagen-image').attr('src', '{{ env("APP_URL") }}'+'/'+data.imagen);
                        $('#u_icono-image').attr('src', '{{ env("APP_URL") }}'+'/'+data.icono);
                        $('#u_imagen-image').attr('alt', data.imagen);
                        $('#u_icono-image').attr('alt', data.icono);
                        $('#u_referencia').val(data.referencia);
                        $('#modal-loading').modal('hide');
                        $('#modal-edit').modal({backdrop: 'static', keyboard: false, show: true});
                    },
                });
            });
            
            $(document).on("click", '.btn-delete', function() {
                let id = $(this).attr("data-id");
                let nombre = $(this).attr("data-nombre");
                $("#did").val(id);
                $("#delete-data").html(name);
                $('#modal-delete').modal({backdrop: 'static', keyboard: false, show: true});
            });

            // input
            let inputId = '';
            function fmSetLink($url) {
                $(inputId).val($url.substring(1));
                $(inputId+'-image').attr("src", "{{ asset(null) }}"+$url.substring(1));
            }
            
            window.fmSetLink = fmSetLink;

            $(document).on("click", '#imagen, #icono, #u_imagen, #u_icono', function(event) {
                event.preventDefault();
                inputId = '#'+event.target.id;
                window.open('/file-manager/fm-button', 'fm', 'width=800,height=600');
            });
        });
    </script>
@endsection

@section('modal')
    {{-- Modal nuevo --}}
    <div class="modal fade" id="modal-nuevo">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo Registro</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categorias.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <label>Nombre</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" placeholder="Nombre" name="nombre" value="{{ old('nombre') }}">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Nombre Web</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('nombre_web') is-invalid @enderror" placeholder="Dejar vacío para usar sólo el nombre" name="nombre_web" value="{{ old('nombre_web') }}">
                                @error('nombre_web')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="width: 100%;">
                                <label>Imagen</label>
                                    <img src="" alt="" name="imagen-image" id="imagen-image" width="8%">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Imagen para menu de categoria" name="imagen" id="imagen" value="{{ old('imagen') }}" readonly required><br/>
                                    @error('imagen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-primary">Click para cargar desde archivo</small>
                            </div>
                            <div style="width: 100%;">
                                <label>Icono</label>
                                    <img src="" alt="" name="icono-image" id="icono-image" width="8%">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Icono para menu de categoria" name="icono" id="icono" value="{{ old('icono') }}" readonly required><br/>
                                    @error('icono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-primary">Click para cargar desde archivo</small>
                            </div>
                            <label>Referencia</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('referencia') is-invalid @enderror" placeholder="Id de referencia de la base de datos oficial" name="referencia" value="{{ old('referencia') }}">
                                @error('referencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Mostrar</label>
                            <div class="input-group">
                                <input type="checkbox" class="form-control @error('mostrar') is-invalid @enderror" name="mostrar" id="mostrar" value="1" checked>
                                @error('mostrar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Destacar</label>
                            <div class="input-group">
                                <input type="checkbox" class="form-control @error('destacar') is-invalid @enderror" name="destacar" id="destacar" value="1">
                                @error('destacar')
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
                    <h4 class="modal-title">Editar Datos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categorias.update') }}" method="POST" enctype="multipart/form-data">
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
                            <label>Nombre Web</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('u_nombre_web') is-invalid @enderror" placeholder="Nombre Web" name="u_nombre_web" id="u_nombre_web" value="{{ old('u_nombre_web') }}">
                                @error('u_nombre_web')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="width: 100%;">
                                <label>Imagen</label>
                                    <img src="" alt="" name="u_imagen-image" id="u_imagen-image" width="8%">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Imagen para categoría" name="u_imagen" id="u_imagen" value="{{ old('u_imagen') }}" readonly required><br/>
                                </div>
                                <small class="text-primary">Click para cargar desde archivo</small>
                            </div>
                            <div style="width: 100%;">
                                <label>Icono</label>
                                    <img src="" alt="" name="u_icono-image" id="u_icono-image" width="8%">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Icono para categoría" name="u_icono" id="u_icono" value="{{ old('u_icono') }}" readonly required><br/>
                                </div>
                                <small class="text-primary">Click para cargar desde archivo</small>
                            </div>
                            <label>Referencia</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('u_referencia') is-invalid @enderror" placeholder="Referencia" name="u_referencia" id="u_referencia" value="{{ old('u_referencia') }}">
                                @error('u_referencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Mostrar</label>
                            <div class="input-group">
                                <input type="checkbox" class="form-control @error('u_mostrar') is-invalid @enderror" name="u_mostrar" id="u_mostrar" value="{{ old('u_mostrar') }}">
                                @error('u_mostrar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Destacar</label>
                            <div class="input-group">
                                <input type="checkbox" class="form-control @error('u_destacar') is-invalid @enderror" name="u_destacar" id="u_destacar" value="{{ old('u_destacar') }}">
                                @error('u_destacar')
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
    {{-- Modal delete --}}
    <div class="modal fade" id="modal-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar Registro</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categorias.destroy') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('DELETE')
                        <p class="modal-text">Seguro que desea eliminar este registro? <b id="delete-data"></b></p>
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
    <script>
        
    </script>
@endsection