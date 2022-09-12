@extends('admin.layouts.master')
@section('content')
    <style>
        .select2-selection__choice__display{color:black;}
        #mostrar, #destacar, #u_mostrar, #u_destacar {transform: scale(1.5);}
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
                            @can('create productos')
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
                                            <th>Nombre</th>
                                            <th>Nombre Web</th>
                                            <th>Descripción</th>
                                            <th>Código</th>
                                            <th>Precio</th>
                                            <th>Marca</th>
                                            <th>Categoría</th>
                                            <th>Tags</th>
                                            <th>Imagen Principal</th>
                                            <th>Cuotas</th>
                                            <th>Productos Relacionados</th>
                                            <th>Referencia</th>
                                            <th>Mostrar</th>
                                            <th>Destacar</th>
                                            <th>Última Modificación</th>
                                            @canany(['update productos', 'delete productos'])
                                                <th>Acciones</th>
                                            @endcanany
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $i)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $i->nombre }}</td>
                                                <td>{{ $i->nombre_web }}</td>
                                                <td>{{ $i->descripcion }}</td>
                                                <td>{{ $i->codigo }}</td>
                                                <td>{{ $i->precio }}</td>
                                                <td>{{ $i->marca }}</td>
                                                <td>{{ ($i->categoria->nombre_web ? $i->categoria->nombre_web : $i->categoria->nombre) }}</td>
                                                <td>{{ $i->tags }}</td>
                                                <td><img src="{{ asset($i->imagen_principal) }}" alt="{{ $i->imagen_principal }}" width="100%"></td>
                                                <td>{{ $i->cuotas }}</td>
                                                <td>{{ $i->productos_relacionados }}</td>
                                                <td>{{ $i->referencia }}</td>
                                                <td>{{ $i->mostrar }}</td>
                                                <td>{{ $i->destacar }}</td>
                                                <td>{{ $i->updated_at }}</td>
                                                @canany(['update productos', 'delete productos'])
                                                    <td>
                                                        <div class="btn-group">
                                                            @can('update productos')
                                                                <button class="btn btn-sm btn-primary btn-edit" data-id="{{ $i->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                            @endcan
                                                            @can('delete productos')
                                                                <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $i->id }}" data-name="{{ $i->nombre }}"><i class="fas fa-trash"></i></button>
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
        $(function(){
            $("#productos_relacionados").select2();
            //$("#u_productos_relacionados").select2();
        }); 
   </script>
    <script>
        $(document).ready(function() {
            let oculto;
            $(document).on("click", '.btn-edit', function() {
                let id = $(this).attr("data-id");
                $('#modal-loading').modal({backdrop: 'static', keyboard: false, show: true});
                $.ajax({
                    url: "{{ route('productos.show') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        console.log(data.data);
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
                        $('#u_imagen_principal').val(data.imagen_principal);
                        $('#u_imagen_principal-image').attr('src', '{{ env("APP_URL") }}'+'/'+data.imagen_principal);
                        $('#u_imagen_principal-image').attr('alt', data.imagen_principal);
                        $('#u_referencia').val(data.referencia);
                        $('#u_descripcion').val(data.descripcion);
                        $('#u_codigo').val(data.codigo);
                        $('#u_precio').val(data.precio);
                        $('#u_marca').val(data.marca);
                        $('#u_categoria').val(data.categoria_id);
                        $('#u_tags').val(data.tags);
                        $('#u_cuotas').val(data.cuotas);
                        var productos_relacionados_data = data.productos_relacionados;
                        var productos_relacionados_array = productos_relacionados_data.split(",");
                        $('#u_productos_relacionados').select2().val( productos_relacionados_array);
                        $("#u_productos_relacionados option[value='"+data.codigo+"']").remove();
                        oculto = data;
                        $('#modal-loading').modal('hide');
                        $('#modal-edit').modal({backdrop: 'static', keyboard: false, show: true});
                    },
                });
            });
            
            $('#modal-edit').on('hide.bs.modal', function (e) {
                //$("#u_productos_relacionados-select option[value='"+oculto+"']").css('display: inline;');
                var o = new Option((oculto.nombre_web ? oculto.nombre_web : oculto.nombre), oculto.id);
                /// jquerify the DOM object 'o' so we can use the html method
                $(o).html((oculto.nombre_web ? oculto.nombre_web : oculto.nombre));
                $("#u_productos_relacionados-select").append(o);
            });

            $(document).on("click", '.btn-delete', function() {
                let id = $(this).attr("data-id");
                let nombre = $(this).attr("data-nombre");
                $("#did").val(id);
                $("#delete-data").html(nombre);
                $('#modal-delete').modal({backdrop: 'static', keyboard: false, show: true});
            });

            // Productos relacionados create
            /*var sel = document.getElementsByName('productos_relacionados-select')[0];
            var chosen = [];
            sel.onclick = function () {
                var is_there = !!~chosen.indexOf(this.value);
                if(is_there){return false;};
                chosen.push(this.value);
                if (document.getElementsByName('productos_relacionados')[0].value) {
                    document.getElementsByName('productos_relacionados')[0].value += ',' + this.value;
                } else {
                    document.getElementsByName('productos_relacionados')[0].value = this.value;
                }
            }
            var limpiar = document.getElementById('limpiar_productos_relacionados');
            limpiar.onclick = function () {
                var productos_relacionados = document.getElementById('productos_relacionados');
                chosen = [];
                productos_relacionados.value = '';
            }*/

            // Productos relacionados edit
            /*var u_sel = document.getElementsByName('u_productos_relacionados-select')[0];
            var u_chosen = [];
            u_sel.onclick = function () {
                var u_is_there = !!~u_chosen.indexOf(this.value);
                if(u_is_there){return false;};
                u_chosen.push(this.value);
                if (document.getElementsByName('u_productos_relacionados')[0].value) {
                    document.getElementsByName('u_productos_relacionados')[0].value += ',' + this.value;
                } else {
                    document.getElementsByName('u_productos_relacionados')[0].value = this.value;
                }
            }
            var u_limpiar = document.getElementById('u_limpiar_productos_relacionados');
            u_limpiar.onclick = function () {
                var u_productos_relacionados = document.getElementById('u_productos_relacionados');
                u_chosen = [];
                u_productos_relacionados.value = '';
            }*/

            // para subir imagenes
            let inputId = '';
            function fmSetLink($url) {
                $(inputId).val($url.substring(1));
                $(inputId+'-image').attr("src", "{{ asset(null) }}"+$url.substring(1));
            }
            
            window.fmSetLink = fmSetLink;

            $(document).on("click", '#imagen_principal, #u_imagen_principal', function(event) {
                event.preventDefault();
                inputId = '#'+event.target.id;
                window.open('/file-manager/fm-button', 'fm', 'width=800,height=600');
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
                    <h4 class="modal-title">Nuevo Producto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label>Descripción</label>
                            <div class="input-group">
                                <textarea id="descripcion" name="descripcion" rows="4" cols="50" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Resumen de las características del producto">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="display: inline-block; width: 48%;">
                                <label>Código</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('codigo') is-invalid @enderror" placeholder="Código único identificador del producto" name="codigo" value="{{ old('codigo') }}">
                                    @error('codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div style="display: inline-block; width: 48%; margin-left: 4%;">
                                <label>Precio</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('precio') is-invalid @enderror" placeholder="Precio del producto" name="precio" value="{{ old('precio') }}">
                                    @error('precio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div style="display: inline-block; width: 48%;">
                                <label>Marca</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('marca') is-invalid @enderror" placeholder="Marca del producto" name="marca" value="{{ old('marca') }}">
                                    @error('marca')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div style="display: inline-block; width: 48%; margin-left: 4%;">
                                <label>Categoría</label>
                                <div class="input-group">
                                    <select id="categoria_id" name="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror" value="{{ old("categoria_id") }}" required>
                                        <option value="" disabled selected>Seleccione Categoría</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre_web ? $categoria->nombre_web : $categoria->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <label>Tags</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('tags') is-invalid @enderror" placeholder="Etiquetas que mejor describen al producto" name="tags" value="{{ old('tags') }}">
                                <!--textarea id="tags" name="tags" rows="4" cols="50" class="form-control @error('tags') is-invalid @enderror" placeholder="Etiquetas que mejor describen al producto">
                                    {{ old('tags') }}
                                </textarea-->
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="width: 100%;">
                                <label>Imagen Principal</label>
                                    <img src="" alt="" name="imagen_principal-image" id="imagen_principal-image" width="8%">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Imagen para mostrar inicialmente en el visor del producto" name="imagen_principal" id="imagen_principal" value="{{ old('imagen_principal') }}" readonly required><br/>
                                    @error('imagen_principal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-primary">Click para cargar desde archivo</small>
                            </div>
                            <label>Cuotas</label>
                            <div class="input-group">
                                <textarea id="cuotas" name="cuotas" rows="4" cols="50" class="form-control @error('cuotas') is-invalid @enderror" placeholder="Planes de pago del producto">
                                    {{ old('cuotas') }}
                                </textarea>
                                @error('cuotas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Productos Relacionados</label>
                            <div class="input-group">
                                <!--input type="text" id="productos_relacionados" name="productos_relacionados">
                                <input type="button" value="Limpiar" id="limpiar_productos_relacionados"-->
                                <select id="productos_relacionados" name="productos_relacionados[]" class="form-control @error('productos_relacionados') is-invalid @enderror" style="width: 100%;" value="{{ old("productos_relacionados") }}" multiple required>
                                    <option value="" disabled>Seleccione Productos Relacionados</option>
                                    @foreach($data as $producto)
                                        <option value="{{ $producto->codigo }}">{{ $producto->nombre_web ? $producto->nombre_web.'-'.$producto->codigo : $producto->nombre.'-'.$producto->codigo }}</option>
                                    @endforeach
                                </select>
                                @error('productos_relacionados[]')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Referencia</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('referencia') is-invalid @enderror" placeholder="Id de referencia de la base de datos oficial" name="referencia" value="{{ old('referencia') }}">
                                @error('referencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="mostrar" style="vertical-align: middle;">Mostrar</label>
                                <input type="checkbox" class="@error('mostrar') is-invalid @enderror" name="mostrar" id="mostrar" style="margin-left: 17px;" value="1" checked>
                                @error('mostrar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="destacar" style="vertical-align: middle;">Destacar</label>
                                <input type="checkbox" class="@error('destacar') is-invalid @enderror" name="destacar" id="destacar" style="margin-left: 10px;" value="1">
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
                    <h4 class="modal-title">Editar Dato</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('productos.update') }}" method="POST" enctype="multipart/form-data">
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
                            <label>Descripción</label>
                            <div class="input-group">
                                <textarea id="u_descripcion" name="u_descripcion" rows="4" cols="50" class="form-control @error('u_descripcion') is-invalid @enderror" placeholder="Resumen de las características del producto">
                                    {{ old('u_descripcion') }}
                                </textarea>
                                @error('u_descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="display: inline-block; width: 48%;">
                                <label>Código</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('u_codigo') is-invalid @enderror" placeholder="Código único identificador del producto" name="u_codigo" id="u_codigo" value="{{ old('u_codigo') }}">
                                    @error('u_codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div style="display: inline-block; width: 48%; margin-left: 4%;">
                                <label>Precio</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('u_precio') is-invalid @enderror" placeholder="Precio del producto" name="u_precio" id="u_precio" value="{{ old('u_precio') }}">
                                    @error('u_precio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div style="display: inline-block; width: 48%;">
                                <label>Marca</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('u_marca') is-invalid @enderror" placeholder="Marca del producto" name="u_marca" id="u_marca" value="{{ old('u_marca') }}">
                                    @error('u_marca')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div style="display: inline-block; width: 48%; margin-left: 4%;">
                                <label>Categoría</label>
                                <div class="input-group">
                                    <select id="u_categoria_id" name="u_categoria_id" class="form-control @error('u_categoria_id') is-invalid @enderror" value="{{ old("u_categoria_id") }}" required>
                                        <option value="" disabled>Seleccione Categoría</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre_web ? $categoria->nombre_web : $categoria->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('u_categoria_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <label>Tags</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('u_tags') is-invalid @enderror" placeholder="Etiquetas que mejor describen al producto" name="tags" value="{{ old('u_tags') }}">
                                <!--textarea id="u_tags" name="u_tags" rows="4" cols="50" class="form-control @error('u_tags') is-invalid @enderror" placeholder="Etiquetas que mejor describen al producto">
                                    {{ old('u_tags') }}
                                </textarea-->
                                @error('u_tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="width: 100%;">
                                <label>Imagen Principal</label>
                                    <img src="" alt="" name="u_imagen_principal-image" id="u_imagen_principal-image" width="8%">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Imagen para mostrar inicialmente en el visor del producto" name="u_imagen_principal" id="u_imagen_principal" value="{{ old('u_imagen_principal') }}" readonly required><br/>
                                </div>
                                <small class="text-primary">Click para cargar desde archivo</small>
                            </div>
                            <label>Cuotas</label>
                            <div class="input-group">
                                <textarea id="u_cuotas" name="u_cuotas" rows="4" cols="50" class="form-control @error('u_cuotas') is-invalid @enderror" placeholder="Planes de pago del producto">
                                    {{ old('u_cuotas') }}
                                </textarea>
                                @error('u_cuotas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Productos Relacionados</label>
                            <div class="input-group">
                                <!--input type="text" id="u_productos_relacionados" name="u_productos_relacionados">
                                <input type="button" value="Limpiar" id="u_limpiar_productos_relacionados"-->
                                <select id="u_productos_relacionados" name="u_productos_relacionados[]" class="form-control @error('u_productos_relacionados') is-invalid @enderror" style="width: 100%;" multiple required>
                                    <option value="" disabled>Seleccione Productos Relacionados</option>
                                    @foreach($data as $producto)
                                        <option value="{{ $producto->codigo }}">{{ $producto->nombre_web ? $producto->nombre_web.'-'.$producto->codigo : $producto->nombre.'-'.$producto->codigo }}</option>
                                    @endforeach
                                </select>
                                @error('u_productos_relacionados[]')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>Referencia</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('u_referencia') is-invalid @enderror" placeholder="Referencia" name="u_referencia" id="u_referencia" value="{{ old('u_referencia') }}">
                                @error('u_referencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="u_mostrar">Mostrar</label>
                                <input type="checkbox" class="@error('u_mostrar') is-invalid @enderror" name="u_mostrar" id="u_mostrar" style="margin-left: 17px;" value="{{ old('u_mostrar') }}">
                                @error('u_mostrar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="u_destacar">Destacar</label>
                                <input type="checkbox" class="@error('u_destacar') is-invalid @enderror" name="u_destacar" id="u_destacar" style="margin-left: 10px;" value="{{ old('u_destacar') }}">
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
                    <h4 class="modal-title">Eliminar Dato</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('productos.destroy') }}" method="POST" enctype="multipart/form-data">
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