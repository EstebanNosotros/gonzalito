<?php
 
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Modules\Categorias\Models\Categoria;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use File;
use ZipArchive;
 
class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();
     
        return response()->json([
            "success" => true,
            "message" => "Lista de Categorias",
            "data"    => $categorias
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
            'nombre'       => ['required', 'string', 'max:255']
            ,'nombre_web'  => ['nullable', 'string', 'max:255']
            ,'imagen'      => ['nullable', 'string']
            ,'icono'       => ['nullable', 'string']
            ,'referencia'  => ['nullable', 'string', 'max:255']
            ,'mostrar'     => ['sometimes', 'boolean']
            ,'destacar'    => ['sometimes', 'boolean']
            //,'imagen_file' => ['sometimes', 'max:10000', 'mimes:jpg,jpeg,png,gif,bmp'] 
        ]);
    
        if($validator->fails()){
            //return $this->sendError('Error de validación.', $validator->errors());
            return response()->json([ 
                "success" => false,
                "message" => "Error de validación.",
                "errors"  => $validator->errors()
            ]);
        }
    
        //$categoria = Categoria::create($input);
        $categoria = new Categoria;
        $categoria->nombre     = $input['nombre'];
        $categoria->nombre_web = $input['nombre_web'] ? $input['nombre_web'] : null;
        $categoria->imagen     = $input['imagen'] ? $input['imagen'] : null;
        $categoria->icono      = $input['icono'] ? $input['icono'] : null;
        $categoria->referencia = $input['referencia'] ? $input['referencia'] : null;
        $categoria->mostrar    = $input['mostrar'] ? $input['mostrar'] : true;
        $categoria->destacar   = $input['destacar'] ? $input['destacar'] : false;
        $categoria->save();

        // $imagenes = $request->file('imagen_file');
        // if(!empty($imagenes)) {
        //     foreach($imagenes as $imagen) {
        //         Storage::put($imagen->getClientOriginalName(),file_get_contents($imagen));
        //     }
        // }
 
        return response()->json([
            "success" => true,
            "message" => "Categoría creada exitosamente.",
            "data"    => $categoria
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
        $categoria = Categoria::find($id);
   
        if (is_null($categoria)) {
            //return $this->sendError('Categoría no encontrada.');
            return response()->json([
                "success" => false,
                "message" => "Error al cargar Categoría."
            ]);
        }
         
        return response()->json([
            "success" => true,
            "message" => "Categoría cargada exitosamente.",
            "data" => $categoria
        ]);
 
    }
     
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        $input = $request->all();
    
        $validator = Validator::make($input, [
            'nombre'      => ['required', 'string', 'max:255']
            ,'nombre_web' => ['nullable', 'string', 'max:255']
            ,'imagen'     => ['sometimes', 'nullable', 'string']
            ,'icono'      => ['nullable', 'string']
            ,'referencia' => ['nullable', 'string', 'max:255']
            ,'mostrar'    => ['sometimes', 'boolean']
            ,'destacar'   => ['sometimes', 'boolean']
        ]);
    
        if($validator->fails()){
            //return $this->sendError('Error de validación.', $validator->errors());       
            return response()->json([ 
                "success" => false,
                "message" => "Error de validación.",
                "errors" => $validator->errors()
            ]);
        }
    
        $categoria->nombre     = $input['nombre'] ? $input['nombre'] : $categoria->nombre;
        $categoria->nombre_web = $input['nombre_web'] ? $input['nombre_web'] : null;
        $categoria->imagen     = $input['imagen'] ? $input['imagen'] : null;
        $categoria->icono      = $input['icono'] ? $input['icono'] : null;
        $categoria->referencia = $input['referencia'] ? $input['referencia'] : null;
        $categoria->mostrar    = $input['mostrar'] ? $input['mostrar'] : false;
        $categoria->destacar   = $input['destacar'] ? $input['destacar'] : false;
        $categoria->save();
    
        return response()->json([
            "success" => true,
            "message" => "Categoría actualizada exitosamente.",
            "data"    => $categoria
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        if (file_exists(public_path($categoria->imagen))) {
            unlink(public_path($categoria->imagen));
        }
        if (file_exists(public_path($categoria->icono))) {
            unlink(public_path($categoria->icono));
        }
        $categoria->delete();
    
        return response()->json([
            "success" => true,
            "message" => "Categoría borrada exitosamente.",
            "data" => $categoria
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
                Storage::put('public/categorias/'.$imagen->getClientOriginalName(),file_get_contents($imagen));
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
        $categorias = Categoria::all();
        $zip = new ZipArchive;
        $zipName = 'imagenes_categorias.zip';
        if (file_exists(public_path($zipName))) {
            unlink(public_path($zipName));
        }
        if ($zip->open(public_path($zipName), ZipArchive::CREATE) === TRUE)
        {
            foreach ($categorias as $categoria)
            {
                if(file_exists(public_path($categoria->imagen)))
                {
                    $file = File::get(public_path($categoria->imagen));
                    //EventLogger::info('##### Downloading the file#### ' );
                    $fileName = basename($categoria->imagen);
                    $zip->addFile(public_path($categoria->imagen), $fileName);
                    //$extension = pathinfo(storage_path('/uploads/my_image.jpg'), PATHINFO_EXTENSION);
                    //$headers = array('Content-Type: image/'.$extension);
                    //return response()->download($categoria->imagen,$filename,$headers);
                }

                if(file_exists(public_path($categoria->icono)))
                {
                    $file = File::get(public_path($categoria->icono));
                    //EventLogger::info('##### Downloading the file#### ' );
                    $fileName = basename($categoria->icono);
                    $zip->addFile(public_path($categoria->icono), $fileName);
                    //$extension = pathinfo(storage_path('/uploads/my_image.jpg'), PATHINFO_EXTENSION);
                    //$headers = array('Content-Type: image/'.$extension);
                    //return response()->download($categoria->imagen,$filename,$headers);
                }
            }
            $zip->close();
        }

        return response()->download(public_path($zipName));
 
    }

    public function getImagen($idCategoria)
    {
        $categoria = Categoria::find($idCategoria);
        if($categoria->imagen) {
            if (file_exists(public_path($categoria->imagen))) {
                // $file = File::get(public_path($producto->imagen_principal));
                // return "hay";
                // \Log::info('existe la imagen principal');
                return response()->file(public_path($categoria->imagen));
            }else {
                return null;//response()->file(public_path('storage/productos/logo-gonzalito-placeholder.jpg'));
            }
        }else {
            return null;//response()->file(public_path('storage/productos/logo-gonzalito-placeholder.jpg'));
        }
    }

    public function getIcono($idCategoria)
    {
        $categoria = Categoria::find($idCategoria);
        if($categoria->icono) {
            if (file_exists(public_path($categoria->icono))) {
                // $file = File::get(public_path($producto->imagen_principal));
                // return "hay";
                // \Log::info('existe la imagen principal');
                return response()->file(public_path($categoria->icono));
            }else {
                return null;//response()->file(public_path('storage/productos/logo-gonzalito-placeholder.jpg'));
            }
        }else {
            return null;//response()->file(public_path('storage/productos/logo-gonzalito-placeholder.jpg'));
        }
    }

    /// GuzzleHTTP

    // GET
    public function httpGet()
    {
        $username='p4nt4L1to';
        $password='305pr15mA';
        $httpClient = new \GuzzleHttp\Client();
        $req = $httpClient->get('http://190.128.136.242:7575/catalogserv/categorias', ['auth' => [$username, $password]]);
        $res = $req->getBody();
        return json_decode($res, true);
        //return count(json_decode($res,true));
        //dd($res);
    }

    //Synchronize
    public function synchronize()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            exit;
        }
        $username='p4nt4L1to';
        $password='305pr15mA';
        $httpClient = new \GuzzleHttp\Client();
        $req = $httpClient->get('http://190.128.136.242:7575/catalogserv/categorias', ['auth' => [$username, $password]]);
        $res = $req->getBody();
        //return json_decode($res, true);
        //return count(json_decode($res,true));
        //dd($res);
        $categorias = json_decode($res, true);
        $existe = null;
        foreach ($categorias as $categoria) {
            $existe = Categoria::where('referencia', $categoria['id_categoria'])->first();
            if($existe == null) {
                Categoria::create([
                    'nombre'     => $categoria['nombre'],
                    'referencia' => $categoria['id_categoria']
                ]);                    
            }
            $existe = null;
        }
        $totalCategorias = Categoria::count();
        return "Ahora hay ".$totalCategorias." categorias en el sistema <a href=\"back();\">Volver</a>";

    }

    // POST
    public function httpPost()
    {
        $httpClient = new \GuzzleHttp\Client();
        $api = "http://endpoint.com/api/categoria";
    
        $contentBody['nombre'] = "Categoria Demo";
        $req = $httpClient->post($api,  ['body'=>$contentBody]);
        $res = $req->send();
        
        dd($res);
    }

    // PUT
    public function httpPut()
    {
        $httpClient = new \GuzzleHttp\Client();
        $api = "http://endpoint.com/api/categoria/1";
        $contentBody['name'] = "Categoria Demo Demo";
        $req = $httpClient->put($api,  ['body'=>$contentBody]);
        $res = $req->send();
       
        dd($res);
    }

    // DELETE
    public function httpDelete()
    {
        $httpClient = new \GuzzleHttp\Client();
        $api = "http://endpoint.com/api/categoria/1";
        $req = $httpClient->delete($api);
        $res = $req->send();
    
        dd($res);
    }

}