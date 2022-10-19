@extends('admin.layouts.master')
@section('content')
    <style>
        .select2-selection__choice__display{color:black;}
        #mostrar, #destacar, #u_mostrar, #u_destacar, #en_stock, #u_en_stock, #en_catalogo, #u_catalogo {transform: scale(1.5);}
        #breadcrumb_inicio {color:black !important;}
        .page-link, .btn-perfil {color:inherit !important; text-decoration: underline !important;}
        .button-submit-loader {
            position: relative;
            border: none;
            outline: none;
            border-radius: 2px;
            cursor: pointer;
        }
        .btn-secondary:disabled,
        .btn_secondary[disabled] {
            opacity: 1;
        }
        .button__text {
            color: #ffffff;
            transition: all 0.2s;
        }

        .button--loading .button__text {
            visibility: hidden;
            opacity: 0;
        }

        .button--loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            border: 4px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: button-loading-spinner 1s ease infinite;
        }

        @keyframes button-loading-spinner {
            from {
                transform: rotate(0turn);
            }

            to {
                transform: rotate(1turn);
            }
        }
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
                            @can('create productos')
                            <div class="card-header">
                                <h3 class="card-title">
                                    <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-nuevo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus"></i> Nuevo</a>
                                </h3>
                                <form action="{{ route('productos.synchronize') }}" method="POST" enctype="multipart/form-data" id="form_sincronizar_productos">
                                    @csrf
                                    <h3 class="card-title" style="margin-left: 20px;">
                                        <button type="submit" class="btn btn-sm btn-secondary button-submit-loader" data-backdrop="static" data-keyboard="false" id="btn_sincronizar_productos"><span class="button__text"><i class="fa-solid fa-arrows-rotate"></i> Actualizar Registros </span></button>
                                    </h3>
                                </form>
                            </div>
                            @endcan
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover" id="mainTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Nombre Web</th>
                                            <!--th>Descripción</th-->
                                            <th>Código</th>
                                            <!--th>Precio</th-->
                                            <th>Marca</th>
                                            <th>Categoría</th>
                                            <!--th>Tags</th>
                                            <th>Imagen Principal</th>
                                            <th>Cuotas</th>
                                            <th>Productos Relacionados</th-->
                                            <th>Referencia</th>
                                            <th>Mostrar</th>
                                            <th>Destacar</th>
                                            <th>Cat&aacute;logo</th-->
                                            <th>Sincronizado</th>
                                            {{--@canany(['update productos', 'delete productos'])--}}
                                                <th>Acciones</th>
                                            {{--@endcanany--}}
                                        </tr>
                                    </thead>
                                    {{--<tbody>
                                        @foreach ($data as $i)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $i->nombre }}</td>
                                                <td>{{ $i->nombre_web }}</td>
                                                <!--td>{{ $i->descripcion }}</td-->
                                                <td>{{ $i->codigo }}</td>
                                                <!--td>{{ $i->precio }}</td-->
                                                <td>{{ $i->marca }}</td>
                                                <td>{{ ($i->categoria->nombre_web ? $i->categoria->nombre_web : $i->categoria->nombre) }}</td>
                                                <!--td>{{ $i->tags }}</td>
                                                <td><img src="{{ asset($i->imagen_principal) }}" alt="{{ $i->imagen_principal }}" width="100%"></td>
                                                <td>@if( count($i->cuotas) > 0  )
                                                        <ul>
                                                       @forEach($i->cuotas as $cuota)
                                                            <li>{{ $cuota->cuotas }} cuotas de {{ number_format($cuota->monto, 0, ',', '.') }} Guaran&iacute;es</li>
                                                        @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td>{{ $i->productos_relacionados }}</td-->
                                                <td>{{ $i->referencia }}</td>
                                                <td>{{ $i->mostrar }}</td>
                                                <td>{{ $i->destacar }}</td>
                                                <!--td>{{ $i->updated_at }}</td-->
                                                <td>{{ $i->ultima_sincronizacion }}</td>
                                                {{--@canany(['update productos', 'delete productos'])--}-}
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-info btn-show" data-id="{{ $i->id }}"><i class="fas fa-eye"></i></button>
                                                            @can('update productos')
                                                                <button class="btn btn-sm btn-primary btn-edit" data-id="{{ $i->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                            @endcan
                                                            @can('delete productos')
                                                                <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $i->id }}" data-name="{{ $i->nombre }}"><i class="fas fa-trash"></i></button>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                {{--@endcanany--}-}
                                            </tr>
                                        @endforeach
                                    </tbody> --}}
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
            // DataTable
            $('#mainTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('getProductos')}}",
                columns: [
                    { data: 'iteration' },
                    { data: 'nombre' },
                    { data: 'nombre_web' },
                    { data: 'codigo' },
                    { data: 'marca' },
                    { data: 'categoria' },
                    { data: 'referencia' },
                    { data: 'mostrar' },
                    { data: 'destacar' },
                    { data: 'catalogo' },
                    { data: 'ultima_sincronizacion' },
                    { data: null,
                      render: function ( data, type, row ) {
                        var field;
                        field = '<div class="btn-group">';
                        field = field + '<button class="btn btn-sm btn-info btn-show" data-id="'+data.id+'"><i class="fas fa-eye"></i></button>';
                        @can('update productos')
                            field = field + '<button class="btn btn-sm btn-primary btn-edit" data-id="'+data.id+'"><i class="fas fa-pencil-alt"></i></button>';
                        @endcan
                        @can('delete productos')
                            field = field + '<button class="btn btn-sm btn-danger btn-delete" data-id="'+data.id+'" data-name="'+data.nombre+'"><i class="fas fa-trash"></i></button>';
                        @endcan
                        field = field + '</div>';
                        return field;
                      }
                    },
                ]
            });
            /// Loader para Sincronizar
            $('#form_sincronizar_productos').on('submit', function(){
                var respuesta = confirm("El siguiente proceso puede tardar varios minutos y no puede ser interrumpido, ¿Está seguro que desea ejecutarlo?");
                if(respuesta) {
                    $('#btn_sincronizar_productos').attr('disabled', true);
                    $('#btn_sincronizar_productos').addClass('button--loading');
                    // this.classList.toggle('button--loading');
                    // this.form.submit();
                }else {
                    // this.preventDefault();
                    return false;
                }
            });
            let oculto;
            ///Modal Edit
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
                        if (data.en_stock == 1) {
                            $('#u_en_stock').prop('checked', true);
                            $('#u_en_stock').val(1);
                        }else {
                            $('#u_en_stock').prop('checked', false);
                            $('#u_en_stock').val(1);
                        }
                        if (data.catalogo == 1) {
                            $('#u_catalogo').prop('checked', true);
                            $('#u_catalogo').val(1);
                        }else {
                            $('#u_catalogo').prop('checked', false);
                            $('#u_catalogo').val(1);
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

                        ///Cuotas
                        $("#u_tabla_cuotas").find("tr:not(:first)").remove();
                        var i = 0;
                        if(data.cuotas != null) {
                            var cuotas = JSON.parse(data.cuotas);
                            $('#u_tabla_cuotas').css('display', 'block');
                            cuotas.forEach(recorreCuotas);
                            function recorreCuotas(item) {
                                i++;
                                var fila = '<tr id="u_cuota'+i+'"><td><input type="text" id="actualizar_cuotas_cantidad'+i+'" name="actualizar_cuotas_cantidad'+i+'" value="'+item.cant_cuota+'" required></td><td><input type="text" id="actualizar_cuotas_monto'+i+'" name="actualizar_cuotas_monto'+i+'" value="'+item.monto_cuota+'" required></td><td><small><button type="button" id="btn_u_eliminar_cuota'+i+'" style="background-color: red; color:white; border-radius:50%;"><i class="fas fa-minus"></i></button>Eliminar Cuota</small></td></tr>';
                                $('#u_tabla_cuotas tbody').append(fila);
                                $('#btn_u_eliminar_cuota'+i).click(function() {
                                    $('#actualizar_cuotas_indice').attr('value', parseInt($('#actualizar_cuotas_indice').attr('value')) - 1);
                                    $(this).parent().parent().parent().remove();
                                    var count = $("#u_tabla_cuotas > tbody > tr").length;
                                    if (count < 1) {
                                        $('#u_tabla_cuotas').css('display', 'none');
                                    } 
                                });
                            }
                        }else {
                            $('#u_tabla_cuotas').css('display', 'none');
                        }
                        $('#actualizar_cuotas_indice').attr('value', i);
                        $('#actualizar_cuotas_tope').attr('value', i);

                        if(data.productos_relacionados != null) {
                            var productos_relacionados_data = data.productos_relacionados;
                            var productos_relacionados_array = productos_relacionados_data.split(",");
                            $('#u_productos_relacionados').select2().val(productos_relacionados_array);
                            $("#u_productos_relacionados option[value='"+data.codigo+"']").remove();
                            oculto = data;
                        }else {
                            $('#u_productos_relacionados').select2().val([]);
                            $("#u_productos_relacionados option[value='"+data.codigo+"']").remove();
                            oculto = data;
                        }

                        $('#modal-loading').modal('hide');
                        $('#modal-edit').modal({backdrop: 'static', keyboard: false, show: true});
                    },
                });
            });
            
            $('#modal-edit').on('hide.bs.modal', function (e) {
                //$("#u_productos_relacionados-select option[value='"+oculto+"']").css('display: inline;');
                var o = new Option((oculto.nombre_web ? oculto.nombre_web : oculto.nombre), oculto.codigo);
                /// jquerify the DOM object 'o' so we can use the html method
                $(o).html((oculto.nombre_web ? oculto.nombre_web+'-'+oculto.codigo : oculto.nombre+'-'+oculto.codigo));
                $("#u_productos_relacionados").append(o);
            });
            ///Modal Show
            $(document).on("click", '.btn-show', function() {
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
                        console.log(data);
                        $("#s_nombre").html(data.data.nombre);
                        $("#s_nombre_web").html(data.data.nombre_web);
                        if (data.data.mostrar == 1) {
                            $('#s_mostrar').html('Si');
                        }else {
                            $('#s_mostrar').html('No');
                        }
                        if (data.data.destacar == 1) {
                            $('#s_destacar').html('Si');
                        }else {
                            $('#s_destacar').html('No');
                        }
                        if (data.data.en_stock == 1) {
                            $('#s_en_stock').html('Si');
                        }else {
                            $('#s_en_stock').html('No');
                        }
                        if (data.data.catalogo == 1) {
                            $('#s_catalogo').html('Si');
                        }else {
                            $('#s_catalogo').html('No');
                        }
                        $('#s_imagen_principal').attr('src', '{{ env("APP_URL") }}'+'/'+data.data.imagen_principal);
                        $('#s_imagen_principal').attr('alt', data.data.imagen_principal);
                        $('#s_referencia').html(data.data.referencia);
                        $('#s_descripcion').html(data.data.descripcion);
                        $('#s_codigo').html(data.data.codigo);
                        $('#s_precio').html((data.data.precio).toLocaleString('es'));
                        $('#s_marca').html(data.data.marca);
                        $('#s_categoria').html(data.data.categoria.nombre_web ? data.data.categoria.nombre_web : data.data.categoria.nombre);
                        $('#s_tags').html(data.data.tags);
                        $('#s_ultima_modificacion_origen').html(data.data.ultima_modificacion_origen);
                        $('#s_ultima_sincronizacion').html(data.data.ultima_sincronizacion);

                        ///Cuotas
                        $("#s_tabla_cuotas").find("tr").remove();
                        var i = 0;
                        if(data.data.cuotas != null) {
                            var cuotas = JSON.parse(data.data.cuotas);
                            $('#s_tabla_cuotas').css('display', 'block');
                            cuotas.forEach(recorreCuotas);
                            function recorreCuotas(item) {
                                i++;
                                var fila = '<tr id="s_cuota'+i+'"><td><span>'+item.cant_cuota+' cuotas de '+parseFloat(item.monto_cuota).toLocaleString('es')+' Guaraníes</span></td></tr>';
                                $('#s_tabla_cuotas tbody').append(fila);
                            }
                        }else {
                            $('#s_tabla_cuotas').css('display', 'none');
                        }

                        if(data.data.productos_relacionados != '') {
                            $('#s_productos_relacionados').html(data.data.productos_relacionados);
                        }else {
                            $('#s_productos_relacionados').html(data.data.productos_relacionados);
                        }

                        $('#modal-loading').modal('hide');
                        $('#modal-show').modal({backdrop: 'static', keyboard: false, show: true});
                    },
                });
            });
            ///Modal Delete
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

            //Cuotas Nuevo
            function eliminar_cuota(cuota) {
                $('#crear_cuotas_indice').attr('value', parseInt($('#crear_cuotas_indice').attr('value')) - 1);
                $('#cuota'+cuota).remove();
            }

            $('#btn_agregar_cuota').click(function(){
                $('#tabla_cuotas').css('display', 'block');
                $('#crear_cuotas_indice').attr('value', parseInt($('#crear_cuotas_indice').attr('value',)) + 1);
                $('#crear_cuotas_tope').attr('value', parseInt($('#crear_cuotas_tope').attr('value')) + 1);
                var fila = '<tr id="cuota'+$('#crear_cuotas_tope').val()+'"><td><input type="text" id="crear_cuotas_cantidad'+$('#crear_cuotas_tope').val()+'" name="crear_cuotas_cantidad'+$('#crear_cuotas_tope').val()+'" required></td><td><input type="text" id="crear_cuotas_monto'+$('#crear_cuotas_tope').val()+'" name="crear_cuotas_monto'+$('#crear_cuotas_tope').val()+'" required></td><td><small><button type="button" id="btn_eliminar_cuota'+$('#crear_cuotas_tope').val()+'" style="background-color: red; color:white; border-radius:50%;"><i class="fas fa-minus"></i></button>Eliminar Cuota</small></td></tr>';
                $('#tabla_cuotas tbody').append(fila);
                //alert('#btn_eliminar_cuota'+$('#crear_cuotas_tope').val());
                //$('#btn_eliminar_cuota'+$('#crear_cuotas_tope').val()).on('click', eliminar_cuota($('#crear_cuotas_tope').val()));
                $('#btn_eliminar_cuota'+$('#crear_cuotas_tope').val()).click(function() {
                    $('#crear_cuotas_indice').attr('value', parseInt($('#crear_cuotas_indice').attr('value')) - 1);
                    $(this).parent().parent().parent().remove();
                    var count = $("#tabla_cuotas > tbody > tr").length;
                    if (count < 1) {
                        $('#tabla_cuotas').css('display', 'none');
                    } 
                });
            });

            //Cuotas Actualizar
            function u_eliminar_cuota(cuota) {
                $('#actualizar_cuotas_indice').attr('value', parseInt($('#actualizar_cuotas_indice').attr('value')) - 1);
                $('#u_cuota'+cuota).remove();
            }

            $('#btn_u_agregar_cuota').click(function(){
                $('#u_tabla_cuotas').css('display', 'block');
                $('#actualizar_cuotas_indice').attr('value', parseInt($('#actualizar_cuotas_indice').attr('value',)) + 1);
                $('#actualizar_cuotas_tope').attr('value', parseInt($('#actualizar_cuotas_tope').attr('value')) + 1);
                var fila = '<tr id="u_cuota'+$('#actualizar_cuotas_tope').val()+'"><td><input type="text" id="actualizar_cuotas_cantidad'+$('#actualizar_cuotas_tope').val()+'" name="actualizar_cuotas_cantidad'+$('#actualizar_cuotas_tope').val()+'" required></td><td><input type="text" id="actualizar_cuotas_monto'+$('#actualizar_cuotas_tope').val()+'" name="actualizar_cuotas_monto'+$('#actualizar_cuotas_tope').val()+'" required></td><td><small><button type="button" id="btn_u_eliminar_cuota'+$('#actualizar_cuotas_tope').val()+'" style="background-color: red; color:white; border-radius:50%;"><i class="fas fa-minus"></i></button>Eliminar Cuota</small></td></tr>';
                $('#u_tabla_cuotas tbody').append(fila);
                //alert('#btn_eliminar_cuota'+$('#crear_cuotas_tope').val());
                //$('#btn_eliminar_cuota'+$('#crear_cuotas_tope').val()).on('click', eliminar_cuota($('#crear_cuotas_tope').val()));
                $('#btn_u_eliminar_cuota'+$('#actualizar_cuotas_tope').val()).click(function() {
                    $('#actualizar_cuotas_indice').attr('value', parseInt($('#actualizar_cuotas_indice').attr('value')) - 1);
                    $(this).parent().parent().parent().remove();
                    var count = $("#u_tabla_cuotas > tbody > tr").length;
                    if (count < 1) {
                        $('#u_tabla_cuotas').css('display', 'none');
                    } 
                });
            });

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

            //para sincronizar
            $("#sincronizar_productos").submit( function(){
                $(this).find(':input[type=submit]').prop('disabled', true);
                $("#pageloader").fadeIn();
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
                                <label>Precio Contado</label>
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
                                <input type="text" class="form-control @error('tags') is-invalid @enderror" placeholder="Etiquetas que mejor describen al producto" id="tags" name="tags" value="{{ old('tags') }}">
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
                            <label>Cuotas <small style="margin-left: 10px;"><button type="button" id="btn_agregar_cuota" style="background-color: darkgreen; color: white; border-radius:50%;"><i class="fas fa-plus"></i></button>Agregar Cuota</small></label>
                            <div class="input-group">
                                <table id="tabla_cuotas" style="display: none;">
                                    <thead id="thead_cuotas">
                                      <tr>
                                        <th>Cantidad de Cuotas</th>
                                        <th>Monto de Cuota</th>
                                        <th></th>
                                      </tr>
                                    </thead>
                                    <tbody id="tbody_cuotas">
                                    </tbody>
                                </table>
                            </div>
                            <label>Productos Relacionados</label>
                            <div class="input-group">
                                <!--input type="text" id="productos_relacionados" name="productos_relacionados">
                                <input type="button" value="Limpiar" id="limpiar_productos_relacionados"-->
                                <select id="productos_relacionados" name="productos_relacionados[]" class="form-control @error('productos_relacionados') is-invalid @enderror" style="width: 100%;" value="{{ old("productos_relacionados") }}" multiple>
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
                                <input type="checkbox" class="@error('mostrar') is-invalid @enderror" name="mostrar" id="mostrar" style="margin-left: 32px;" value="1" checked>
                                @error('mostrar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="destacar" style="vertical-align: middle;">Destacar</label>
                                <input type="checkbox" class="@error('destacar') is-invalid @enderror" name="destacar" id="destacar" style="margin-left: 25px;" value="1">
                                @error('destacar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="en_stock" style="vertical-align: middle;">Disponible</label>
                                <input type="checkbox" class="@error('en_stock') is-invalid @enderror" name="en_stock" id="en_stock" style="margin-left: 11px;" value="1">
                                @error('en_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="catalogo" style="vertical-align: middle;">Cat&aacute;logo</label>
                                <input type="checkbox" class="@error('catalogo') is-invalid @enderror" name="catalogo" id="en_stock" style="margin-left: 24px;" value="1">
                                @error('catalogo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="crear_cuotas_indice" name="crear_cuotas_indice" value="0">
                    <input type="hidden" id="crear_cuotas_tope" name="crear_cuotas_tope" value="0">
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
                                <input type="text" class="form-control @error('u_tags') is-invalid @enderror" placeholder="Etiquetas que mejor describen al producto" id="u_tags" name="u_tags" value="{{ old('u_tags') }}">
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
                            <label>Cuotas <small style="margin-left: 10px;"><button type="button" id="btn_u_agregar_cuota" style="background-color: darkgreen; color: white; border-radius:50%;"><i class="fas fa-plus"></i></button>Agregar Cuota</small></label>
                            <div class="input-group">
                                <table id="u_tabla_cuotas" style="display: none;">
                                    <thead id="u_thead_cuotas">
                                        <th>Cantidad de Cuotas</th>
                                        <th>Monto de Cuota</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="u_tbody_cuotas">
                                    </tbody>
                                </table>
                            </div>
                            <label>Productos Relacionados</label>
                            <div class="input-group">
                                <!--input type="text" id="u_productos_relacionados" name="u_productos_relacionados">
                                <input type="button" value="Limpiar" id="u_limpiar_productos_relacionados"-->
                                <select id="u_productos_relacionados" name="u_productos_relacionados[]" class="form-control @error('u_productos_relacionados') is-invalid @enderror" style="width: 100%;" multiple>
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
                                <input type="checkbox" class="@error('u_mostrar') is-invalid @enderror" name="u_mostrar" id="u_mostrar" style="margin-left: 32px;" value="{{ old('u_mostrar') }}">
                                @error('u_mostrar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="u_destacar">Destacar</label>
                                <input type="checkbox" class="@error('u_destacar') is-invalid @enderror" name="u_destacar" id="u_destacar" style="margin-left: 25px;" value="{{ old('u_destacar') }}">
                                @error('u_destacar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="u_en_stock">Disponible</label>
                                <input type="checkbox" class="@error('u_en_stock') is-invalid @enderror" name="u_en_stock" id="u_en_stock" style="margin-left: 11px;" value="{{ old('u_en_stock') }}">
                                @error('u_en_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="u_catalogo">Cat&aacute;logo</label>
                                <input type="checkbox" class="@error('u_catalogo') is-invalid @enderror" name="u_catalogo" id="u_catalogo" style="margin-left: 24px;" value="{{ old('u_catalogo') }}">
                                @error('u_catalogo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="actualizar_cuotas_indice" name="actualizar_cuotas_indice" value="0">
                    <input type="hidden" id="actualizar_cuotas_tope" name="actualizar_cuotas_tope" value="0">
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
                    <h4 class="modal-title mx-auto">Datos de Producto</h4>
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
                            <table class="table-striped">
                                <tr>
                                    <td>
                                        <label class="show_label">Nombre: </label>
                                    </td>
                                    <td>
                                        <span id="s_nombre"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Nombre Web: </label>
                                    </td>
                                    <td>
                                        <span id="s_nombre_web"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Descripción: </label>
                                    </td>
                                    <td>
                                        <span id="s_descripcion"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Código: </label>
                                    </td>
                                    <td>
                                        <span id="s_codigo"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Precio: </label>
                                    </td>
                                    <td>
                                        <span id="s_precio"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Marca</label>
                                    </td>
                                    <td>
                                        <span id="s_marca"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Categoría: </label>
                                    </td>
                                    <td>
                                        <span id="s_categoria"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Tags: </label>
                                    </td>
                                    <td>
                                        <span id="s_tags"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Imagen Principal: </label>
                                    </td>
                                    <td>
                                        <img src="" alt="" name="s_imagen_principal" id="s_imagen_principal" width="100%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Cuotas: </label>
                                    </td>
                                    <td>
                                        <table id="s_tabla_cuotas" style="display: none;">
                                            <tbody id="s_tbody_cuotas">
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Productos Relacionados: </label>
                                    </td>
                                    <td>
                                        <span id="s_productos_relacionados"></span>
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
                                <tr>
                                    <td>
                                        <label class="show_label">Mostrar: </label>
                                    </td>
                                    <td>
                                        <span id="s_mostrar"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Destacar: </label>
                                    </td>
                                    <td>
                                        <span id="s_destacar"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Disponible: </label>
                                    </td>
                                    <td>
                                        <span id="s_en_stock"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">Cat&aacute;logo: </label>
                                    </td>
                                    <td>
                                        <span id="s_catalogo"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">&Uacute;ltima Modificaci&oacute;n en Origen: </label>
                                    </td>
                                    <td>
                                        <span id="s_ultima_modificacion_origen"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="show_label">&Uacute;ltima Sincronizaci&oacute;n: </label>
                                    </td>
                                    <td>
                                        <span id="s_ultima_sincronizacion"></span>
                                    </td>
                                </tr>
                            </table>
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
