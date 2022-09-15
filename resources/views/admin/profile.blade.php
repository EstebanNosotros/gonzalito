@extends('admin.layouts.master')
@section('content')
    <style>
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
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" id="breadcrumb_inicio">Inicio</a></li>
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
                            <style>
                                .show_label {
                                    margin-right: 10px;
                                }
                            </style>
                            <div class="input-group">
                                <table class="table-striped mx-auto" style="width: 100%;">
                                    <tr>
                                        <td>
                                            <label class="show_label">Nombre: </label>
                                        </td>
                                        <td>
                                            <span id="s_nombre">{{ auth()->user()->name; }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="show_label">Email: </label>
                                        </td>
                                        <td>
                                            <span id="s_email">{{ auth()->user()->email; }}</span>
                                        </td>
                                    </tr>
                                    <!--tr>
                                        <td>
                                            <label class="show_label">Imagen Escritorio: </label>
                                        </td>
                                        <td>
                                            <img src="" alt="" name="s_imagen_desktop" id="s_imagen_desktop" width="100%">
                                        </td>
                                    </tr-->
                                    <tr>
                                        <td>
                                            <label class="show_label">Rol</label>
                                        </td>
                                        <td>
                                            <span id="s_rol">{{ implode(",", auth()->user()->getRoleNames()->toArray()) }}</span>
                                    </tr>
                                    <!--tr>
                                        <td>
                                            <label class="show_label">Contraseña</label>
                                        </td>
                                        <td>
                                            <span id="s_contrasena"><a href="{{ 'direccion o metodo para cambiar contrasena' }}">Cambiar Contraseña</a></span>
                                    </tr-->
                                </table>
                            </div>
                            <!-- /.input-group -->
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