<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Modules\Banners\Models\Banner;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use File;
use ZipArchive;

class BannersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all();
     
        return response()->json([
            "success" => true,
            "message" => "Lista de Banners",
            "data"    => $banners
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
            'nombre'          => ['required', 'string', 'max:255']
            ,'imagen_desktop' => ['nullable', 'string', 'max:255']
            ,'imagen_mobile'  => ['nullable', 'string', 'max:255']
            ,'referencia'     => ['nullable', 'string', 'max:255']
            ,'link'           => ['nullable', 'string', 'max:255']
            ,'mostrar'        => ['nullable', 'boolean']
            ,'destacar'       => ['nullable', 'boolean']
            ,'tipo'           => ['sometimes', Rule::in(['Principal','Secundario'])]
        ]);
    
        if($validator->fails()){
            //return $this->sendError('Error de validación.', $validator->errors());       
            return response()->json([ 
                "success" => false,
                "message" => "Error de validación.",
                "errors"  => $validator->errors()
            ]);
        }
    
        $banner = Banner::create($input);
 
        return response()->json([
            "success" => true,
            "message" => "Banner creado exitosamente.",
            "data" => $banner
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
        $banner = Banner::find($id);
   
        if (is_null($banner)) {
            //return $this->sendError('Producto no encontrado.');
            return response()->json([
                "success" => false,
                "message" => "Error al cargar Banner."
            ]);
        }
         
        return response()->json([
            "success" => true,
            "message" => "Banner cargado exitosamente.",
            "data"    => $banner
        ]);
 
    }
     
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $input = $request->all();
    
        $validator = Validator::make($input, [
            'nombre'          => ['required', 'string', 'max:255']
            ,'imagen_desktop' => ['nullable', 'string', 'max:255']
            ,'imagen_mobile'  => ['nullable', 'string', 'max:255']
            ,'referencia'     => ['nullable', 'string', 'max:255']
            ,'link'           => ['nullable', 'string', 'max:255']
            ,'mostrar'        => ['nullable', 'boolean']
            ,'destacar'       => ['nullable', 'boolean']
            ,'tipo'           => ['sometimes', Rule::in(['Principal','Secundario'])]
        ]);
    
        if($validator->fails()){
            //return $this->sendError('Error de validación.', $validator->errors());
            return response()->json([ 
                "success" => false,
                "message" => "Error de validación.",
                "errors" => $validator->errors()
            ]);
        }
    
        $banner->update([
            'nombre'          => $request->nombre
            ,'imagen_desktop' => $request->imagen_desktop
            ,'imagen_mobile'  => $request->imagen_mobile
            ,'referencia'     => $request->referencia
            ,'link'           => $request->link
            ,'mostrar'        => ($request->mostrar ? $request->mostrar : false)
            ,'destacar'       => ($request->destacar ? $request->destacar : false)
            ,'tipo'           => $request->tipo
        ]);
    
        return response()->json([
            "success" => true,
            "message" => "Banner actualizado exitosamente.",
            "data" => $banner
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        if (file_exists(public_path($banner->imagen_desktop))) {
            unlink(public_path($banner->imagen_desktop));
        }
        if (file_exists(public_path($banner->imagen_mobile))) {
            unlink(public_path($banner->imagen_mobile));
        }
        $banner->delete();
    
        return response()->json([
            "success" => true,
            "message" => "Banner borrado exitosamente.",
            "data" => $banner
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
                Storage::put('public/banners/'.$imagen->getClientOriginalName(),file_get_contents($imagen));
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
        $banners = Banner::all();
        $zip = new ZipArchive;
        $zipName = 'imagenes_banners.zip';
        if (file_exists(public_path($zipName))) {
            unlink(public_path($zipName));
        }
        if ($zip->open(public_path($zipName), ZipArchive::CREATE) === TRUE)
        {
            foreach ($banners as $banner)
            {
                if(file_exists(public_path($banner->imagen_desktop)))
                {
                    $file = File::get(public_path($banner->imagen_desktop));
                    //EventLogger::info('##### Downloading the file#### ' );
                    $fileName = basename($banner->imagen_desktop);
                    $zip->addFile(public_path($banner->imagen_desktop), $fileName);
                    //$extension = pathinfo(storage_path('/uploads/my_image.jpg'), PATHINFO_EXTENSION);
                    //$headers = array('Content-Type: image/'.$extension);
                    //return response()->download($categoria->imagen,$filename,$headers);
                }

                if(file_exists(public_path($banner->imagen_mobile)))
                {
                    $file = File::get(public_path($banner->imagen_mobile));
                    //EventLogger::info('##### Downloading the file#### ' );
                    $fileName = basename($banner->imagen_mobile);
                    $zip->addFile(public_path($banner->imagen_mobile), $fileName);
                    //$extension = pathinfo(storage_path('/uploads/my_image.jpg'), PATHINFO_EXTENSION);
                    //$headers = array('Content-Type: image/'.$extension);
                    //return response()->download($categoria->imagen,$filename,$headers);
                }
            }
            $zip->close();
        }

        return response()->download(public_path($zipName));
 
    }

    public function getImagenDesktop($idBanner)
    {
        $banner = Banner::find($idBanner);
        if($banner->imagen_desktop) {
            if (file_exists(public_path($banner->imagen_desktop))) {
                // $file = File::get(public_path($producto->imagen_principal));
                // return "hay";
                // \Log::info('existe la imagen principal');
                return response()->header('Access-Control-Allow-Origin', '*')->file(public_path($banner->imagen_desktop));
            }else {
                return null;//response()->file(public_path('storage/productos/logo-gonzalito-placeholder.jpg'));
            }
        }else {
            return null;//response()->file(public_path('storage/productos/logo-gonzalito-placeholder.jpg'));
        }
        /*const headers = {'Content-Type':'application/json',
                    'Access-Control-Allow-Origin':'*',
                    'Access-Control-Allow-Methods':'POST,PATCH,OPTIONS'};
        const response = {
        statusCode: 200,
        headers:headers,
        body: $file
        };*/
        // return $file;// response($file, 200)
                  //     ->header('Access-Control-Allow-Origin','*');

        //return response()->download(public_path($zipName));
 
    }

    public function getImagenMobile($idBanner)
    {
        $banner = Banner::find($idBanner);
        if($banner->imagen_mobile) {
            if (file_exists(public_path($banner->imagen_mobile))) {
                // $file = File::get(public_path($producto->imagen_principal));
                // return "hay";
                // \Log::info('existe la imagen principal');
                return response()->file(public_path($banner->imagen_mobile));
            }else {
                return null;//response()->file(public_path('storage/productos/logo-gonzalito-placeholder.jpg'));
            }
        }else {
            return null;//response()->file(public_path('storage/productos/logo-gonzalito-placeholder.jpg'));
        }
        /*const headers = {'Content-Type':'application/json',
                    'Access-Control-Allow-Origin':'*',
                    'Access-Control-Allow-Methods':'POST,PATCH,OPTIONS'};
        const response = {
        statusCode: 200,
        headers:headers,
        body: $file
        };*/
        // return $file;// response($file, 200)
                  //     ->header('Access-Control-Allow-Origin','*');

        //return response()->download(public_path($zipName));
 
    }
}
