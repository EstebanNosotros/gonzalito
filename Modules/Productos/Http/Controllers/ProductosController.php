<?php

namespace Modules\Productos\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Productos\Models\Producto;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;
use Modules\Categorias\Models\Categoria;
use Modules\Productos\Models\ProductoCuota;
use Modules\Productos\Models\ProductoImagen;
use stdClass;
use Carbon\Carbon;
use DB;
use DataTables;
use DateTime;

class ProductosController extends Controller
{
    public function index()
    {
        $x['title']      = "Productos";
        $x['data']       = Producto::get();//->take(1000);
        $x['categorias'] = Categoria::get();

        return view('productos::index', $x);
    }

    // Fetch DataTable data
    public function getProductos(Request $request)
    {
        ## Read value
        $draw            = $request->get('draw');
        $start           = $request->get("start");
        $rowperpage      = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');

        $columnIndex     = $columnIndex_arr[0]['column']; // Column index
        $columnName      = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue     = $search_arr['value']; // Search value

        // Custom search filter 
        /// no usamos esto dejo por si acaso
        /*$searchCity = $request->get('searchCity');
        $searchGender = $request->get('searchGender');
        $searchName = $request->get('searchName');*/

        // Total records
        $totalRecords           = Producto::select('count(*) as allcount')
                                          ->count();
        $totalRecordswithFilter = Producto::select('count(*) as allcount')
                                          ->where('nombre', 'like', '%' .$searchValue . '%')
                                          ->count();

        /// $records = Producto::select('count(*) as allcount'); //changed

        ## Add custom filter conditions
        /*if(!empty($searchCity)){
            $records->where('city',$searchCity);
        }
        if(!empty($searchGender)){
            $records->where('gender',$searchGender);
        }
        if(!empty($searchName)){
            $records->where('name','like','%'.$searchName.'%');
        }*/
        /// $totalRecords = $records->count(); //changed

        // Total records with filter
        /// $records = Producto::select('count(*) as allcount')->where('nombre', 'like', '%' .$searchValue . '%'); //changed
        ## Add custom filter conditions
       /* if(!empty($searchCity)){
        $records->where('city',$searchCity);
        }
        if(!empty($searchGender)){
        $records->where('gender',$searchGender);
        }
        if(!empty($searchName)){
        $records->where('name','like','%'.$searchName.'%');
        }*/
        /// $totalRecordswithFilter = $records->count(); //changed

        // Fetch records
        $records = Producto::with(['categoria', 'imagenes'])
                           ->orderBy($columnName,$columnSortOrder)
                           ->where('productos.nombre', 'like', '%' .$searchValue . '%')
                           ->orWhere('productos.nombre_web', 'like', '%' .$searchValue . '%')
                           ->orWhere('productos.descripcion', 'like', '%' .$searchValue . '%')
                           ->orWhere('productos.codigo', 'like', '%' .$searchValue . '%')
                           ->orWhere('productos.precio', 'like', '%' .$searchValue . '%')
                           ->orWhere('productos.precio_oferta', 'like', '%' .$searchValue . '%')
                           ->orWhere('productos.marca', 'like', '%' .$searchValue . '%')
                           //->orWhere('productos.categoria.nombre_web', 'like', '%' .$searchValue . '%')
                           ->orWhere('productos.tags', 'like', '%' .$searchValue . '%')
                           ->orWhere('productos.referencia', 'like', '%' .$searchValue . '%')
                           ->orWhereHas('categoria', function ($query) use($searchValue) {
                                        $query->where('nombre', 'like', '%' .$searchValue . '%');
                                    })
                           ->select('productos.*', DB::raw("@row_num:= @row_num + 1 AS iteration"))
                           ->skip($start)
                           ->take($rowperpage)
                           ->get();
                           /// para cuando podamos poner los numeros de fila otra vez /// DB::raw("ROW_NUMBER() OVER (ORDER BY id ASC) AS iteration")
        //\Log::info($records);
        ## Add custom filter conditions
        /*if(!empty($searchCity)){
        $records->where('city',$searchCity);
        }
        if(!empty($searchGender)){
        $records->where('gender',$searchGender);
        }
        if(!empty($searchName)){
        $records->where('name','like','%'.$searchName.'%');
        }*/
        /*$productos = $records->skip($start)
                    ->take($rowperpage)
                    ->get();*/   // changed

        $data_arr = array();
        /// foreach($productos as $producto){ //changed
        foreach($records as $record){
            $iteration             = $record->iteration;
            $nombre                = $record->nombre;
            $nombre_web            = $record->nombre_web;
            /*$codigo                = $record->codigo;
            $marca                 = $record->marca;*/
            $categoria             = $record->categoria->nombre_web ? $record->categoria->nombre_web : $record->categoria->nombre;
            $referencia            = $record->referencia;
            $mostrar               = $record->mostrar == 1 ? "Sí" : "No";
            $destacar              = $record->destacar == 1 ? "Sí" : "No";
            $catalogo              = $record->catalogo == 1 ? "Sí" : "No";
            $ultima_sincronizacion = $record->ultima_sincronizacion;
            $en_oferta             = $record->en_oferta == 1 ? "Sí" : "No";
            // $precio_oferta         = $record->precio_oferta;
            $id                    = $record->id;

            $data_arr[] = array(
                "iteration"             => $iteration,
                "nombre"                => $nombre,
                "nombre_web"            => $nombre_web,
               /* "codigo"                => $codigo,
                "marca"                 => $marca, */
                "categoria"             => $categoria,
                "referencia"            => $referencia,
                "mostrar"               => $mostrar,
                "destacar"              => $destacar,
                "catalogo"              => $catalogo,
                "ultima_sincronizacion" => $ultima_sincronizacion,
                "en_oferta"             => $en_oferta,
                // "precio_oferta"         => $precio_oferta,
                "id"                    => $id,
            );
        }

        $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordswithFilter,
        "aaData" => $data_arr
        );

        $user = auth()->user();

        return response()->json($response);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'                  => ['required', 'string', 'max:255']
            ,'nombre_web'             => ['nullable', 'string', 'max:255']
            ,'descripcion'            => ['nullable', 'string', 'max:4000']
            ,'codigo'                 => ['nullable', 'string']
            ,'precio'                 => ['nullable', 'numeric']
            ,'marca'                  => ['nullable', 'string']
            ,'categoria_id'           => ['required', 'integer', 'exists:categorias,id']
            ,'tags'                   => ['nullable', 'string', 'max:4000']
            ,'imagen_principal'       => ['nullable', 'string']
           // ,'cuotas'                 => ['nullable', 'array']
           // ,'cuotas.*.cantidad'      => ['sometimes', 'numeric']
           // ,'cuotas.*.monto'         => ['sometimes', 'numeric']
            ,'productos_relacionados' => ['nullable', 'array']
            ,'referencia'             => ['nullable', 'string']
            ,'mostrar'                => ['sometimes', 'boolean']
            ,'destacar'               => ['sometimes', 'boolean']
            ,'en_stock'               => ['sometimes', 'boolean']
            ,'catalogo'               => ['sometimes', 'boolean']
            ,'en_oferta'              => ['sometimes', 'boolean']
            ,'precio_oferta'          => ['nullable', 'numeric']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        $producto = new stdClass;
        $producto->nombre = $request->nombre;
        try {
            $tope   = $request->crear_cuotas_tope;
            $cuotas = [];
            for ($i = 1; $i <= $tope; $i++) {
                if (isset($_POST["crear_cuotas_cantidad".$i])) {
                    $cuota = new stdClass;
                    $cuota->cant_cuota  = intval($_POST["crear_cuotas_cantidad".$i]);
                    $monto_cuota = intval($_POST["crear_cuotas_monto".$i]);
                    $cuota->monto_cuota = $monto_cuota;
                    array_push($cuotas, $cuota);
                    /*$productoCuota = ProductoCuota::create([
                        'cuotas'       => $_POST["crear_cuotas_cantidad".$i]
                        ,'monto'       => $_POST["crear_cuotas_monto".$i]
                        ,'producto_id' => $producto->id
                    ]);*/ ///cuando habia tabla de cuotas
                }
            }
            $producto = Producto::create([
                'nombre'                  => $request->nombre
                ,'nombre_web'             => $request->nombre_web
                ,'descripcion'            => $request->descripcion
                ,'codigo'                 => $request->codigo
                ,'precio'                 => $request->precio
                ,'marca'                  => $request->marca
                ,'categoria_id'           => $request->categoria_id
                ,'tags'                   => $request->tags
                ,'imagen_principal'       => $request->imagen_principal
                ,'productos_relacionados' => $request->productos_relacionados ? implode(',', $request->productos_relacionados) : ''
                ,'referencia'             => $request->referencia
                ,'mostrar'                => ($request->mostrar ? $request->mostrar : false)
                ,'destacar'               => ($request->destacar ? $request->destacar : false)
                ,'en_stock'               => ($request->en_stock ? $request->en_stock : false)
                ,'catalogo'               => ($request->catalogo ? $request->catalogo : false)
                ,'cuotas'                 => json_encode($cuotas, JSON_NUMERIC_CHECK)
                ,'en_oferta'              => ($request->en_oferta ? $request->en_oferta : false)
                ,'precio_oferta'          => $request->precio_oferta
            ]);
            $topeImagenes = $request->crear_imagenes_tope;
            for ($i = 1; $i <= $topeImagenes; $i++) {
                if (isset($_POST["crear_imagenes_imagen".$i])) {
                    $productoImagen = ProductoImagen::create([
                        'imagen'       => $_POST["crear_imagenes_imagen".$i]
                      //  ,'referencia'  => $_POST["crear_imagenes_imagen".$i]
                      //  ,'mostrar'     => isset($_POST["crear_imagenes_mostrar".$i]) ? $_POST["crear_imagenes_mostrar".$i] : false
                      //  ,'destacar'    => isset($_POST["crear_imagenes_destacar".$i]) ? $_POST["crear_imagenes_destacar".$i] : false
                        ,'producto_id' => $producto->id
                    ]);
                    Log::info($productoImagen);
                }
            }
            Log::info('fin de creacion');
            Alert::success('Aviso', 'Dato <b>' . $producto->nombre . '</b> registrado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $producto->nombre . '</b> error al registrar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        $producto = Producto::with(['categoria', 'imagenes'])->where('id', $request->id)->first();
        //\Log::info($request->id);
        //dd($producto);
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato producto por id',
            'data'      => $producto
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_nombre'                  => ['required', 'string', 'max:255']
            ,'u_nombre_web'             => ['nullable', 'string', 'max:255']
            ,'u_descripcion'            => ['nullable', 'string', 'max:4000']
            ,'u_codigo'                 => ['nullable', 'string']
            ,'u_precio'                 => ['nullable', 'numeric']
            ,'u_marca'                  => ['nullable', 'string']
            ,'u_categoria_id'           => ['required', 'integer', 'exists:categorias,id']
            ,'u_tags'                   => ['nullable', 'string', 'max:4000']
            ,'u_imagen_principal'       => ['nullable', 'string']
            //,'u_cuotas'                 => ['nullable', 'array']
            //,'u_cuotas.*.cantidad'      => ['sometimes', 'numeric']
            //,'u_cuotas.*.monto'         => ['sometimes', 'numeric']
            ,'u_productos_relacionados' => ['nullable', 'array']
            ,'u_referencia'             => ['nullable', 'string']
            ,'u_mostrar'                => ['sometimes', 'boolean']
            ,'u_destacar'               => ['sometimes', 'boolean']
            ,'u_en_stock'               => ['sometimes', 'boolean']
            ,'u_catalogo'               => ['sometimes', 'boolean']
            ,'u_en_oferta'              => ['sometimes', 'boolean']
            ,'u_precio_oferta'          => ['nullable', 'numeric']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        $producto = new stdClass;
        $producto->nombre = $request->u_nombre;
        try {
            $indice = $request->actualizar_cuotas_indice;
            $tope   = $request->actualizar_cuotas_tope;
            $cuotas = [];
            for ($i = 0; $i < $tope; $i++) {
                if(isset($_POST["actualizar_cuotas_cantidad".($i+1)])) {
                    if($_POST["actualizar_cuotas_cantidad".($i+1)] > 1 && $_POST["actualizar_cuotas_monto".($i+1)] > 0) {
                        $cuota = new stdClass;
                        $cuota->cant_cuota  = intval($_POST["actualizar_cuotas_cantidad".($i+1)]);
                        $monto_cuota        = intval($_POST["actualizar_cuotas_monto".($i+1)]);
                        $cuota->monto_cuota = $monto_cuota;
                        array_push($cuotas, $cuota);
                        /*ProductoCuota::create([
                            'cuotas'       => $_POST["actualizar_cuotas_cantidad".($i+1)]
                            ,'monto'       => $_POST["actualizar_cuotas_monto".($i+1)]
                            ,'producto_id' => $producto->id
                        ]);*/ ///cuando cuotas tenia su propia tabla
                    }
                }
            }
            $producto = Producto::find($request->id);
            $producto->update([
                'nombre'                  => $request->u_nombre
                ,'nombre_web'             => $request->u_nombre_web
                ,'descripcion'            => $request->u_descripcion
                ,'codigo'                 => $request->u_codigo
                ,'precio'                 => $request->u_precio
                ,'marca'                  => $request->u_marca
                ,'categoria_id'           => $request->u_categoria_id
                ,'tags'                   => $request->u_tags
                ,'imagen_principal'       => $request->u_imagen_principal
                ,'productos_relacionados' => $request->u_productos_relacionados ? implode(',', $request->u_productos_relacionados) : ''
                ,'referencia'             => $request->u_referencia
                ,'mostrar'                => ($request->u_mostrar ? $request->u_mostrar : false)
                ,'destacar'               => ($request->u_destacar ? $request->u_destacar : false)
                ,'en_stock'               => ($request->u_en_stock ? $request->u_en_stock : false)
                ,'catalogo'               => ($request->u_catalogo ? $request->u_catalogo : false)
                ,'cuotas'                 => json_encode($cuotas, JSON_NUMERIC_CHECK)
                ,'en_oferta'              => ($request->u_en_oferta ? $request->u_en_oferta : false)
                ,'precio_oferta'          => $request->u_precio_oferta
            ]);
            $topeImagenes = $request->actualizar_imagenes_tope;
            $imagenes_existentes = ProductoImagen::where('producto_id', $producto->id)->pluck('id')->toArray();
            $imagenes_entrantes  = [];
            for ($i = 0; $i < $topeImagenes; $i++) {
                if(isset($_POST["actualizar_imagenes_imagen".($i+1)])) {
                    if($_POST["actualizar_imagenes_id".($i+1)]) {
                        array_push($imagenes_entrantes, $_POST["actualizar_imagenes_id".($i+1)]);
                        $imagen = ProductoImagen::find($_POST["actualizar_imagenes_id".($i+1)]);
                        $imagen->update([
                            'imagen'      => $_POST["actualizar_imagenes_imagen".($i+1)]
                           // ,'referencia' => $_POST["actualizar_imagenes_imagen".($i+1)]
                           // ,'mostrar'    => isset($_POST["actualizar_imagenes_mostrar".($i+1)]) ? $_POST["actualizar_imagenes_mostrar".($i+1)] : false  
                           // ,'destacar'   => isset($_POST["actualizar_imagenes_destacar".($i+1)]) ? $_POST["actualizar_imagenes_destacar".($i+1)] : false
                        ]);
                    }else{
                        ProductoImagen::create([
                            'imagen'       => $_POST["actualizar_imagenes_imagen".($i+1)]
                            ,'producto_id' => $producto->id
                           // ,'referencia'  => $_POST["actualizar_imagenes_imagen".($i+1)]
                           // ,'mostrar'     => isset($_POST["actualizar_imagenes_mostrar".($i+1)])
                           // ,'destacar'    => isset($_POST["actualizar_imagenes_destacar".($i+1)])
                        ]);
                    }
                }
            }

            $imagenes_borrar = array_diff($imagenes_existentes, $imagenes_entrantes);
            foreach($imagenes_borrar as $borrar) {
                $imagen               = ProductoImagen::find($borrar);
                /*$existeProducto       = Producto::where('imagen_principal', $imagen->imagen)
                                                ->get();
                $existeImagen         = ProductoImagen::where('imagen', $imagen->imagen)
                                                      ->where('id', '<>', $imagen->id)
                                                      ->get();
                $existeCategoria      = Categoria::where('imagen', $imagen->imagen)
                                            ->orWhere('icono', $imagen->imagen)
                                            ->get();
                $imagenDirectorioBase = strpos($imagen->imagen, '/', 8);
                if(!$existeProducto && !$existeImagen && !$existeCategoria && !$imagenDirectorioBase) {*/
                    if(file_exists(public_path($imagen->imagen))) {
                        unlink(public_path($imagen->imagen));
                    }
                //}
                $imagen->delete();
            }
            
            // $cuotas = $producto->cuotas; ///cuando cuotas tenia su propia tabla

            ///Cuotas
            /*foreach($cuotas as $cuota) {
                $cuota->delete();   
            }*/ ///cuando cuotas tenia su propia tabla
            
            Alert::success('Aviso', 'Dato <b>' . $producto->nombre . '</b> actualizado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $producto->nombre . '</b> error al actualizar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $producto = Producto::find($request->id);
            $imagenes = ProductoImagen::where('producto_id', $producto->id)->get();
            foreach($imagenes as $imagen) {
                if (file_exists(public_path($imagen->imagen))) {
                    unlink(public_path($imagen->imagen));
                }
                $imagen->delete();
            }
            if ($producto->imagen_principal) {
                if (file_exists(public_path($producto->imagen_principal))) {
                    unlink(public_path($producto->imagen_principal));
                }
            }
            $producto->delete();
            Alert::success('Aviso', 'Dato <b>' . $producto->nombre . '</b> eliminado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $producto->nombre . '</b> error al eliminar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    //Synchronize
    public function synchronize()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            exit;
        }
        Log::info('Proceso entero de sincronizacion de productos, Inicio');
        $username              ='p4nt4L1to';
        $password              ='305pr15mA';
        $httpClient            = new \GuzzleHttp\Client();
        $fecha_inicio_con_pro  = Carbon::now();
        $req                   = $httpClient->get('http://190.128.136.242:7575/catalogserv/categorias/productos', ['auth' => [$username, $password]]);
        $fecha_fin_con_pro     = Carbon::now(); //test
        $tiempo_total_con_pro  = $fecha_inicio_con_pro->diffForHumans($fecha_fin_con_pro); //test
        Log::info("Proceso para consultar productos de BD de origen terminado, inicio ".$tiempo_total_con_pro); //test
        $fecha_sincronizacion  = Carbon::now();
        $res                   = $req->getBody();
        $productos             = json_decode($res, true);
        $contador_nuevos       = 0;
        foreach ($productos as $producto) {
            $existe           = null;
            $fecha_inicio_con = Carbon::now(); //test
            $existe           = Producto::where('referencia', $producto['id_producto'])->first();
            $fecha_fin_con    = Carbon::now(); //test
            $tiempo_total_con = $fecha_inicio_con->diffForHumans($fecha_fin_con); //test
            Log::info("Producto: ".$producto['nombre']." proceso para consultar sus datos terminado, inicio ".$tiempo_total_con); //test
            if($existe == null) {
                Log::info('no existe se va a crear');
                $fecha_inicio_con_cat = Carbon::now();
                $categoria            = Categoria::where('referencia', $producto['id_categoria'])->first();
                $fecha_fin_con_cat    = Carbon::now(); //test
                $tiempo_total_con_cat = $fecha_inicio_con_cat->diffForHumans($fecha_fin_con_cat); //test
                Log::info("Proceso para consultar referencia de categoria de BD terminado, inicio ".$tiempo_total_con_cat); //test
                if($categoria){
                    $categoriaId       = $categoria->id; 
                    $fecha_inicio_ins  = Carbon::now(); //test
                    $producto_catalogo = Producto::create([
                                                'nombre'                      => $producto['nombre']
                                                ,'descripcion'                => $producto['descripcion']
                                                ,'codigo'                     => $producto['id_producto']
                                                ,'precio'                     => $producto['precio_contado']
                                                ,'marca'                      => $producto['marca']
                                                ,'categoria_id'               => $categoriaId
                                                ,'referencia'                 => $producto['id_producto']
                                                ,'en_stock'                   => $producto['disponible']
                                                ,'ultima_sincronizacion'      => $fecha_sincronizacion
                                                ,'cuotas'                     => json_encode($producto['precio_credito'], JSON_NUMERIC_CHECK)
                                                ,'ultima_modificacion_origen' => $producto['ultima_mod']
                                                ,'catalogo'                   => $producto['catalogo'] == 'S' ? 1 : 0 
                                                ,'en_oferta'                  => $producto['en_oferta'] == true ? 1 : 0 
                                                ,'precio_oferta'              => $producto['precio_oferta']
                                            ]);
                    $fecha_fin_ins     = Carbon::now(); //test
                    $tiempo_total_ins  = $fecha_inicio_ins->diffForHumans($fecha_fin_ins); //test
                    $contador_nuevos++;
                    Log::info("Producto: ".$producto['nombre']." proceso para crearlo terminado, inicio ".$tiempo_total_ins); //test
                }
            } else {
                /// Si queremos comparar las fechas antes de actualizar pero como
                /// esto es algo que fuerza la actualizacion, omito esta parte.
                /// (por ahora)
                /// $b = Carbon::createFromFormat('Y-m-d h:i:s', $a->ultima_sincronizacion)->format('Y-m-d'); /// esto sera muy util... espero
                /*$datetime1 = new DateTime($existe->ultima_sincronizacion);
                $datetime2 = new DateTime($fecha_sincronizacion);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');
                if(($existe->ultima_sincronizacion == null) || (intval($days) > 6) ) {

                }*/
                $fecha_inicio_con_cat = Carbon::now();
                $categoria            = Categoria::where('referencia', $producto['id_categoria'])->first();
                $fecha_fin_con_cat    = Carbon::now(); //test
                $tiempo_total_con_cat = $fecha_inicio_con_cat->diffForHumans($fecha_fin_con_cat); //test
                Log::info("Proceso para consultar referencia de categoria de BD terminado, inicio ".$tiempo_total_con_cat); //test
                if($categoria) {
                    $categoriaId      = $categoria->id;
                    $fecha_inicio_act = Carbon::now(); //test
                    $existe->update([
                        'nombre'                      => $producto['nombre']
                        ,'descripcion'                => $producto['descripcion']
                        //,'codigo'                   => $producto['id_producto']
                        ,'precio'                     => $producto['precio_contado']
                        ,'marca'                      => $producto['marca']
                        ,'categoria_id'               => $categoriaId
                        //,'referencia'               => $producto['id_producto']
                        ,'en_stock'                   => $producto['disponible']
                        ,'ultima_sincronizacion'      => $fecha_sincronizacion
                        ,'cuotas'                     => $producto['precio_credito']
                        ,'ultima_modificacion_origen' => $producto['ultima_mod']
                        ,'catalogo'                   => $producto['catalogo'] == 'S' ? 1 : 0
                        ,'en_oferta'                  => $producto['en_oferta'] == true ? 1 : 0
                        ,'precio_oferta'              => $producto['precio_oferta']
                    ]);
                    $fecha_fin_act    = Carbon::now(); //test
                    $tiempo_total_act = $fecha_inicio_act->diffForHumans($fecha_fin_act); //test
                    Log::info("Producto: ".$producto['nombre']." proceso para actualizarlo terminado, inicio ".$tiempo_total_act); //test
                }
            }
            // $contador_productos++;
        }
        $totalProductos       = Producto::count();
        $fecha_fin_proceso    = Carbon::now();
        $tiempo_total_proceso = $fecha_sincronizacion->diffForHumans($fecha_fin_proceso);
        Log::info('Proceso entero de sincronizacion de productos, Fin');
        return "Ahora hay ".$totalProductos." productos en el sistema, se insertaron ".$contador_nuevos." productos al sistema, el proceso se inició ".$tiempo_total_proceso." <a href=\"".route('productos.index')."\">Volver</a>";
    }

    public function synchronizeDirect()
    {
        Log::info('Proceso entero de sincronizacion de productos, Inicio');
        $username              ='p4nt4L1to';
        $password              ='305pr15mA';
        $httpClient            = new \GuzzleHttp\Client();
        $fecha_inicio_con_pro  = Carbon::now();
        $req                   = $httpClient->get('http://190.128.136.242:7575/catalogserv/categorias/productos', ['auth' => [$username, $password]]);
        $fecha_fin_con_pro     = Carbon::now(); //test
        $tiempo_total_con_pro  = $fecha_inicio_con_pro->diffForHumans($fecha_fin_con_pro); //test
        Log::info("Proceso para consultar productos de BD de origen terminado, inicio ".$tiempo_total_con_pro); //test
        $fecha_sincronizacion  = Carbon::now();
        $res                   = $req->getBody();
        $productos             = json_decode($res, true);
        $contador_nuevos       = 0;
        foreach ($productos as $producto) {
            $existe           = null;
            $fecha_inicio_con = Carbon::now(); //test
            $existe           = Producto::where('referencia', $producto['id_producto'])->first();
            $fecha_fin_con    = Carbon::now(); //test
            $tiempo_total_con = $fecha_inicio_con->diffForHumans($fecha_fin_con); //test
            Log::info("Producto: ".$producto['nombre']." proceso para consultar sus datos terminado, inicio ".$tiempo_total_con); //test
            if($existe == null) {
                Log::info('no existe se va a crear');
                $fecha_inicio_con_cat = Carbon::now();
                $categoria            = Categoria::where('referencia', $producto['id_categoria'])->first();
                $fecha_fin_con_cat    = Carbon::now(); //test
                $tiempo_total_con_cat = $fecha_inicio_con_cat->diffForHumans($fecha_fin_con_cat); //test
                Log::info("Proceso para consultar referencia de categoria de BD terminado, inicio ".$tiempo_total_con_cat); //test
                if($categoria){
                    $categoriaId       = $categoria->id; 
                    $fecha_inicio_ins  = Carbon::now(); //test
                    $producto_catalogo = Producto::create([
                                                'nombre'                      => $producto['nombre']
                                                ,'descripcion'                => $producto['descripcion']
                                                ,'codigo'                     => $producto['id_producto']
                                                ,'precio'                     => $producto['precio_contado']
                                                ,'marca'                      => $producto['marca']
                                                ,'categoria_id'               => $categoriaId
                                                ,'referencia'                 => $producto['id_producto']
                                                ,'en_stock'                   => $producto['disponible']
                                                ,'ultima_sincronizacion'      => $fecha_sincronizacion
                                                ,'cuotas'                     => json_encode($producto['precio_credito'], JSON_NUMERIC_CHECK)
                                                ,'ultima_modificacion_origen' => $producto['ultima_mod']
                                                ,'catalogo'                   => $producto['catalogo'] == 'S' ? 1 : 0 
                                                ,'en_oferta'                  => $producto['en_oferta'] == true ? 1 : 0 
                                                ,'precio_oferta'              => $producto['precio_oferta']
                                            ]);
                    $fecha_fin_ins     = Carbon::now(); //test
                    $tiempo_total_ins  = $fecha_inicio_ins->diffForHumans($fecha_fin_ins); //test
                    $contador_nuevos++;
                    Log::info("Producto: ".$producto['nombre']." proceso para crearlo terminado, inicio ".$tiempo_total_ins); //test
                }
            } else {
                /// Si queremos comparar las fechas antes de actualizar pero como
                /// esto es algo que fuerza la actualizacion, omito esta parte.
                /// (por ahora)
                /// $b = Carbon::createFromFormat('Y-m-d h:i:s', $a->ultima_sincronizacion)->format('Y-m-d'); /// esto sera muy util... espero
                /*$datetime1 = new DateTime($existe->ultima_sincronizacion);
                $datetime2 = new DateTime($fecha_sincronizacion);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');
                if(($existe->ultima_sincronizacion == null) || (intval($days) > 6) ) {

                }*/
                $fecha_inicio_con_cat = Carbon::now();
                $categoria            = Categoria::where('referencia', $producto['id_categoria'])->first();
                $fecha_fin_con_cat    = Carbon::now(); //test
                $tiempo_total_con_cat = $fecha_inicio_con_cat->diffForHumans($fecha_fin_con_cat); //test
                Log::info("Proceso para consultar referencia de categoria de BD terminado, inicio ".$tiempo_total_con_cat); //test
                if($categoria) {
                    $categoriaId      = $categoria->id;
                    $fecha_inicio_act = Carbon::now(); //test
                    $existe->update([
                        'nombre'                      => $producto['nombre']
                        ,'descripcion'                => $producto['descripcion']
                        //,'codigo'                   => $producto['id_producto']
                        ,'precio'                     => $producto['precio_contado']
                        ,'marca'                      => $producto['marca']
                        ,'categoria_id'               => $categoriaId
                        //,'referencia'               => $producto['id_producto']
                        ,'en_stock'                   => $producto['disponible']
                        ,'ultima_sincronizacion'      => $fecha_sincronizacion
                        ,'cuotas'                     => $producto['precio_credito']
                        ,'ultima_modificacion_origen' => $producto['ultima_mod']
                        ,'catalogo'                   => $producto['catalogo'] == 'S' ? 1 : 0
                        ,'en_oferta'                  => $producto['en_oferta'] == true ? 1 : 0
                        ,'precio_oferta'              => $producto['precio_oferta']
                    ]);
                    $fecha_fin_act    = Carbon::now(); //test
                    $tiempo_total_act = $fecha_inicio_act->diffForHumans($fecha_fin_act); //test
                    Log::info("Producto: ".$producto['nombre']." proceso para actualizarlo terminado, inicio ".$tiempo_total_act); //test
                }
            }
            // $contador_productos++;
        }
        $totalProductos       = Producto::count();
        $fecha_fin_proceso    = Carbon::now();
        $tiempo_total_proceso = $fecha_sincronizacion->diffForHumans($fecha_fin_proceso);
        Log::info('Proceso entero de sincronizacion de productos, Fin');
        return "Ahora hay ".$totalProductos." productos en el sistema, se insertaron ".$contador_nuevos." productos al sistema, el proceso se inició ".$tiempo_total_proceso." <a href=\"".route('productos.index')."\">Volver</a>";
    }

    public function synchronize_test() {
        Log::info('synchronize de productos');
        return;
    }

    //Synchronize Original por categorias (DEPRECADO)
    public function synchronize_OLD()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            exit;
        }
        Log::info('Proceso entero de sincronizacion de productos, Inicio');
        $username             ='p4nt4L1to';
        $password             ='305pr15mA';
        $httpClient           = new \GuzzleHttp\Client();
        $fecha_inicio_con_cat = Carbon::now();
        $categorias           = Categoria::where('referencia', '003')->get();
        $fecha_fin_con_cat = Carbon::now(); //test
        $tiempo_total_con_cat = $fecha_inicio_con_cat->diffForHumans($fecha_fin_con_cat); //test
        Log::info("Proceso para consultar categorias de BD terminado, inicio ".$tiempo_total_con_cat); //test
        $contador_categorias  = 0;
        $fecha_sincronizacion = Carbon::now();
        foreach ($categorias as $categoria) {
            $fecha_inicio_cat = Carbon::now();
            $req                = $httpClient->get('http://190.128.136.242:7575/catalogserv/categorias/productos/'.$categoria->referencia, ['auth' => [$username, $password]]);
            $fecha_fin_req = Carbon::now(); //test
            $tiempo_total_req = $fecha_inicio_cat->diffForHumans($fecha_fin_req); //test
            Log::info("Categoria: ".$categoria->nombre." proceso para consultar sus datos terminado, inicio ".$tiempo_total_req); //test
            $res                = $req->getBody();
            $productos          = json_decode($res, true);
            $contador_productos = 0;
            foreach ($productos as $producto) {
                $existe = null;
                $fecha_inicio_con = Carbon::now(); //test
                $existe = Producto::where('referencia', $producto['id_producto'])->first();
                $fecha_fin_con = Carbon::now(); //test
                $tiempo_total_con = $fecha_inicio_con->diffForHumans($fecha_fin_con); //test
                Log::info("Producto: ".$producto['nombre']." proceso para consultar sus datos terminado, inicio ".$tiempo_total_con); //test
                if($existe == null) {
                    Log::info('no existe se va a crear');
                    $fecha_inicio_ins = Carbon::now(); //test
                    $producto_catalogo = Producto::create([
                                             'nombre'                 => $producto['nombre']
                                             ,'descripcion'           => $producto['descripcion']
                                             ,'codigo'                => $producto['id_producto']
                                             ,'precio'                => $producto['precio_contado']
                                             ,'marca'                 => $producto['marca']
                                             ,'categoria_id'          => $categoria->id
                                             ,'referencia'            => $producto['id_producto']
                                             ,'en_stock'              => $producto['disponible']
                                             ,'ultima_sincronizacion' => $fecha_sincronizacion
                                             ,'cuotas'                => json_encode($producto['precio_credito'], JSON_NUMERIC_CHECK)
                                         ]);
                    $fecha_fin_ins = Carbon::now(); //test
                    $tiempo_total_ins = $fecha_inicio_ins->diffForHumans($fecha_fin_ins); //test
                    Log::info("Producto: ".$producto['nombre']." proceso para crearlo terminado, inicio ".$tiempo_total_ins); //test
                    /*$fecha_inicio_cuo = Carbon::now(); //test
                    foreach ($producto['precio_credito'] as $cuota) {
                        $fecha_inicio_ins_cuo = Carbon::now(); //test
                        ProductoCuota::create([
                            'cuotas'       => $cuota["cant_cuota"]
                            ,'monto'       => $cuota["monto_cuota"]
                            ,'producto_id' => $producto_catalogo->id
                        ]);
                        $fecha_fin_ins_cuo = Carbon::now(); //test
                        $tiempo_total_ins_cuo = $fecha_inicio_ins_cuo->diffForHumans($fecha_fin_ins_cuo); //test
                        \Log::info("Producto: ".$producto['nombre']." proceso para insertar una cuota terminado, inicio ".$tiempo_total_ins_cuo); //test
                    }
                    $fecha_fin_cuo = Carbon::now(); //test
                    $tiempo_total_cuo = $fecha_inicio_cuo->diffForHumans($fecha_fin_cuo); //test
                    \Log::info("Producto: ".$producto['nombre']." proceso para insertar todas sus cuotas terminado, inicio ".$tiempo_total_cuo); //test*/
                } else {
                    /// Si queremos comparar las fechas antes de actualizar pero como
                    /// esto es algo que fuerza la actualizacion, omito esta parte.
                    /// (por ahora)
                    /*$datetime1 = new DateTime($existe->ultima_sincronizacion);
                    $datetime2 = new DateTime($fecha_sincronizacion);
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->format('%a');
                    if(($existe->ultima_sincronizacion == null) || (intval($days) > 6) ) {

                    }*/
                    $fecha_inicio_act = Carbon::now(); //test
                    $existe->update([
                        'nombre'                 => $producto['nombre']
                        ,'descripcion'           => $producto['descripcion']
                        //,'codigo'                => $producto['id_producto']
                        ,'precio'                => $producto['precio_contado']
                        ,'marca'                 => $producto['marca']
                        //,'categoria_id'          => $categoria->id
                        //,'referencia'            => $producto['id_producto']
                        ,'en_stock'              => $producto['disponible']
                        ,'ultima_sincronizacion' => $fecha_sincronizacion
                        ,'cuotas'                => $producto['precio_credito']
                    ]);
                    $fecha_fin_act = Carbon::now(); //test
                    $tiempo_total_act = $fecha_inicio_act->diffForHumans($fecha_fin_act); //test
                    Log::info("Producto: ".$producto['nombre']." proceso para actualizarlo terminado, inicio ".$tiempo_total_act); //test
                    ///Cuotas
                    /*$fecha_inicio_bor_cuo = Carbon::now(); //test
                    $cuotas = $existe->cuotas;
                    foreach($cuotas as $cuota) {
                        $cuota->delete();   
                    }
                    foreach ($producto['precio_credito'] as $cuota) {
                        ProductoCuota::create([
                            'cuotas'       => $cuota["cant_cuota"]
                            ,'monto'       => $cuota["monto_cuota"]
                            ,'producto_id' => $existe->id
                        ]);
                    }
                    $fecha_fin_bor_cuo = Carbon::now(); //test
                    $tiempo_total_bor_cuo = $fecha_inicio_bor_cuo->diffForHumans($fecha_fin_bor_cuo); //test
                    \Log::info("Producto: ".$producto['nombre']." proceso para borrar y reinsertar todas sus cuotas terminado, inicio ".$tiempo_total_bor_cuo); //test*/
                }
                $contador_productos++;
            }
            $fecha_fin_cat = Carbon::now();
            $tiempo_total_cat = $fecha_inicio_cat->diffForHumans($fecha_fin_cat);
            $contador_categorias++;
            Log::info("Categoria: ".$categoria->nombre." terminada, ".$contador_productos." productos relacionados a la misma y procesados. ".$contador_categorias." categorias procesadas, este proceso se inicio ".$tiempo_total_cat);
        }
        $totalProductos = Producto::count();
        $fecha_fin_proceso = Carbon::now();
        $tiempo_total_proceso = $fecha_sincronizacion->diffForHumans($fecha_fin_proceso);
        Log::info('Proceso entero de sincronizacion de productos, Fin');
        return "Ahora hay ".$totalProductos." productos en el sistema, el proceso se inició ".$tiempo_total_proceso." <a href=\"".route('productos.index')."\">Volver</a>";

    }
}
