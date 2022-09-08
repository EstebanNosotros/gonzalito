<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Categoria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('app.categorias.create') }}" method="post">
                    @csrf
                        <input class="border" name="nombre" value="{{ old('nombre') }}" />
                        <input class="border" name="nombre_web" value="{{ old('nombre_web') }}" />
                        <img src="" alt="" id="imagen-imagen" width="8%">
                        <div class="input-group">
                            <!--input type="hidden" name="imagen-clave" value=""-->
                            <input type="text" class="form-control" placeholder="" name="imagen" id="imagen" value="">
                            {{-- <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="{{ $set->key }}button">Pilih Foto</button>
                            </div> --}}
                        </div>
                        <small class="text-primary">Click para cargar desde archivo</small>
                        <img src="" alt="" id="icono-imagen" width="8%">
                        <div class="input-group">
                            <!--input type="hidden" name="icono-clave" value=""-->
                            <input type="text" class="form-control" placeholder="" name="icono" id="imagen" value="">
                            {{-- <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="{{ $set->key }}button">Pilih Foto</button>
                            </div> --}}
                        </div>
                        <small class="text-primary">Click para cargar desde archivo</small>
                        <input class="border" name="referencia" value="{{ old('referencia') }}" />
                        <input type="checkbox" id="mostrar" name="mostrar" value="true">
                        <input type="checkbox" id="destacar" name="destacar" value="false">
                        <button>Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
