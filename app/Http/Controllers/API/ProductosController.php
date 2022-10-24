<?php
 
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
//use App\Models\Product;
use Modules\Productos\Models\Producto;
use Modules\Productos\Models\ProductoCuota;
use Illuminate\Http\Request; 
use Validator;
use Illuminate\Support\Facades\Storage;
use File;
use ZipArchive;
 
class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::with(['categoria', 'imagenes'])->get();
     
        return response()->json([
            "success" => true,
            "message" => "Lista de Productos",
            "data" => $productos
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'nombre'                  => ['required', 'string', 'max:255']
            ,'nombre_web'             => ['nullable', 'string', 'max:255']
            ,'descripcion'            => ['nullable', 'string', 'max:4000']
            ,'codigo'                 => ['nullable', 'string']
            ,'precio'                 => ['nullable', 'numeric']
            ,'marca'                  => ['nullable', 'string']
            ,'categoria_id'           => ['required', 'integer', 'exists:categorias,id']
            ,'tags'                   => ['nullable', 'string', 'max:4000']
            ,'imagen_principal'       => ['nullable', 'string']
            ,'cuotas'                 => ['nullable', 'array']
            ,'cuotas.*.cant_cuota'    => ['sometimes', 'numeric']
            ,'cuotas.*.monto_cuota'   => ['sometimes', 'numeric']
            ,'productos_relacionados' => ['nullable', 'string']
            ,'referencia'             => ['nullable', 'string']
            ,'mostrar'                => ['sometimes', 'boolean']
            ,'destacar'               => ['sometimes', 'boolean']
            ,'en_stock'               => ['sometimes', 'boolean']
        ]);
    
        if($validator->fails()){
            //return $this->sendError('Error de validación.', $validator->errors());       
            return response()->json([ 
                "success" => false,
                "message" => "Error de validación.",
                "errors"  => $validator->errors()
            ]);
        }
    
        $producto = Producto::create($input);
        /*$idProducto = $producto->id;
        \Log::info($producto->id);
        if($producto) {
            foreach($input['cuotas'] as $cuota) {
                $productoCuota = ProductoCuota::create([
                    'cuotas'       => $cuota["cantidad"]
                    ,'monto'       => $cuota["monto"]
                    ,'producto_id' => $idProducto
                ]);
            }
        }*/
 
        return response()->json([
            "success" => true,
            "message" => "Producto creado exitosamente.",
            "data" => $producto
        ]);
 
    } 
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::with(['categoria', 'imagenes'])->find($id);
   
        if (is_null($producto)) {
            //return $this->sendError('Producto no encontrado.');
            return response()->json([
                "success" => false,
                "message" => "Error al cargar Producto."
            ]);
        }
         
        return response()->json([
            "success" => true,
            "message" => "Producto cargado exitosamente.",
            "data"    => $producto
        ]);
 
    }
     
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $input = $request->all();
    
        $validator = Validator::make($input, [
            'nombre'                  => ['required', 'string', 'max:255']
            ,'nombre_web'             => ['nullable', 'string', 'max:255']
            ,'descripcion'            => ['nullable', 'string', 'max:4000']
            ,'codigo'                 => ['nullable', 'string']
            ,'precio'                 => ['nullable', 'numeric']
            ,'marca'                  => ['nullable', 'string']
            ,'categoria_id'           => ['required', 'integer', 'exists:categorias,id']
            ,'tags'                   => ['nullable', 'string', 'max:4000']
            ,'imagen_principal'       => ['nullable', 'string']
            ,'cuotas'                 => ['nullable', 'array']
            ,'cuotas.*.cant_cuota'    => ['sometimes', 'numeric']
            ,'cuotas.*.monto_cuota'   => ['sometimes', 'numeric']
            ,'productos_relacionados' => ['nullable', 'string']
            ,'referencia'             => ['nullable', 'string']
            ,'mostrar'                => ['sometimes', 'boolean']
            ,'destacar'               => ['sometimes', 'boolean']
            ,'en_stock'               => ['sometimes', 'boolean']
        ]);
    
        if($validator->fails()){
            //return $this->sendError('Error de validación.', $validator->errors());
            return response()->json([ 
                "success" => false,
                "message" => "Error de validación.",
                "errors" => $validator->errors()
            ]);
        }
    
        $producto->update([
            'nombre'                  => $request->nombre
            ,'nombre_web'             => $request->nombre_web
            ,'descripcion'            => $request->descripcion
            ,'codigo'                 => $request->codigo
            ,'precio'                 => $request->precio
            ,'marca'                  => $request->marca
            ,'categoria_id'           => $request->categoria_id
            ,'tags'                   => $request->tags
            ,'imagen_principal'       => $request->imagen_principal
            ,'productos_relacionados' => $request->productos_relacionados
            ,'referencia'             => $request->referencia
            ,'mostrar'                => ($request->mostrar ? $request->mostrar : false)
            ,'destacar'               => ($request->destacar ? $request->destacar : false)
            ,'en_stock'               => ($request->en_stock ? $request->en_stock : false)
            ,'cuotas'                 => $request->cuotas
        ]);

        /* $cuotas = $producto->cuotas;

        foreach($cuotas as $cuota) {
            $cuota->delete();
        }

        /*foreach($input['cuotas'] as $cuota) {
            $productoCuota = ProductoCuota::create([
                'cuotas'       => $cuota["cantidad"]
                ,'monto'       => $cuota["monto"]
                ,'producto_id' => $producto->id
            ]);
        }*/ ///cuando habia tabla de cuotas

        $producto = Producto::with(['categoria'])->find($producto->id);
    
        return response()->json([
            "success" => true,
            "message" => "Producto actualizado exitosamente.",
            "data" => $producto
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        if (file_exists(public_path($producto->imagen_principal))) {
            unlink(public_path($producto->imagen_principal));
        }
        $producto->delete();
    
        return response()->json([
            "success" => true,
            "message" => "Producto borrado exitosamente.",
            "data" => $producto
        ]);
    }

    public function uploadImages(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'imagenes_file'   => ['required', 'array'],
            'imagenes_file.*' => ['max:10000', 'mimes:jpg,jpeg,png,gif,bmp']
        ]);
    
        if($validator->fails()){
            //return $this->sendError('Error de validación.', $validator->errors());
            return response()->json([ 
                "success" => false,
                "message" => "Error de validación.",
                "errors"  => $validator->errors()
            ]);
        }
        $nombre = '';
        $imagenes = $request->file('imagenes_file');
        if(!empty($imagenes)) {
            foreach($imagenes as $imagen) {
                Storage::put('public/productos/'.$imagen->getClientOriginalName(),file_get_contents($imagen));
                $nombre = $imagen->getClientOriginalName();
            }
        }
 
        return response()->json([
            "success" => true,
            "message" => "Imagenes cargadas exitosamente."
        ]);
 
    }

    public function downloadImages()
    {
        $productos = Producto::all();
        $zip = new ZipArchive;
        $zipName = 'imagenes_productos.zip';
        if (file_exists(public_path($zipName))) {
            unlink(public_path($zipName));
        }
        if ($zip->open(public_path($zipName), ZipArchive::CREATE) === TRUE)
        {
            foreach ($productos as $producto)
            {
                if(file_exists(public_path($producto->imagen_principal)))
                {
                    $file = File::get(public_path($producto->imagen_principal));
                    //EventLogger::info('##### Downloading the file#### ' );
                    $fileName = basename($producto->imagen_principal);
                    $zip->addFile(public_path($producto->imagen_principal), $fileName);
                    //$extension = pathinfo(storage_path('/uploads/my_image.jpg'), PATHINFO_EXTENSION);
                    //$headers = array('Content-Type: image/'.$extension);
                    //return response()->download($categoria->imagen,$filename,$headers);
                }
            }
            $zip->close();
        }

        return response()->download(public_path($zipName));
 
    }
}