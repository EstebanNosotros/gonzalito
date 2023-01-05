<?php

namespace Modules\Catalogo_auditorias\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\Catalogo_auditorias\Models\Catalogo_auditoria;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;
use DB;

class Catalogo_auditoriasController extends Controller
{
    public function index()
    {
        $x['title'] = "Auditoria";
        $x['data']  = DB::connection('mysql_catalogo')
                        ->select('SELECT a.*, u.nombre AS vendedor
                                    FROM audits a, users u
                                   WHERE u.id = a.user_id');

        return view('catalogo_auditorias::index', $x);
    }

    /*public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'      => ['required', 'string', 'max:255']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $catalogo_auditoria = Catalogo_auditoria::create([
                'nombre'      => $request->nombre
            ]);
            Alert::success('Aviso', 'Dato <b>' . $catalogo_auditoria->nombre . '</b> registrado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $catalogo_auditoria->nombre . '</b> error al registrar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }*/

    public function show(Request $request)
    {
        // \Log::info($request->id);
        $catalogo_auditoria = DB::connection('mysql_catalogo')
                                ->select('SELECT a.*, u.nombre AS vendedor
                                            FROM audits a, users u
                                           WHERE a.id = :idAuditoria
                                             AND u.id = a.user_id', ['idAuditoria' => $request->id])[0];
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato catalogo_auditoria por id',
            'data'      => $catalogo_auditoria
        ], Response::HTTP_OK);
    }

    // Fetch DataTable data
    public function getCatalogoAuditorias(Request $request)
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
        $columnSortOrder = strtoupper($order_arr[0]['dir']); // asc or desc
        $searchValue     = $search_arr['value']; // Search value

        // Custom search filter 
        /// no usamos esto dejo por si acaso //ahora vamos a usar... deberia borrar esta linea
        /*$searchCity = $request->get('searchCity');
        $searchGender = $request->get('searchGender');*/
        $searchByName        = $request->get('searchByName');
        $searchByIp          = $request->get('searchByIp');
        $searchByDispositivo = $request->get('searchByDispositivo');
        $searchByDesde       = $request->get('searchByDesde');
        $searchByHasta       = $request->get('searchByHasta');
        $searchByUrl         = $request->get('searchByUrl');
        $searchByReferencia  = $request->get('searchByReferencia');
        $searchByRol         = $request->get('searchByRol');
        $searchByObjeto      = $request->get('searchByObjeto');

        \Log::info('searchByDesde '.$searchByDesde);
       
                                                //  AND (a.created_at >= :param_fec_ini OR :param_fec_fin = NULL) 
                                                //  AND (a.created_at <= :param_fec_fin OR :param_fec_fin = NULL)
                                                //  AND (a.ip = :param_ip OR :param_ip = NULL)', ['param_fec_ini'  => $request->param_fec_ini
                                                //                                                ,'param_fec_fin' => $request->param_fec_fin
                                                //                                                ,'param_ip'      => $request->ip]));

        // $totalRecordswithFilter = count(DB::connection('mysql_catalogo')
        //                             ->select('SELECT a.*, u.nombre AS vendedor
        //                                         FROM audits a, users u
        //                                        WHERE u.id = a.user_id
        //                                          AND (a.created_at >= :param_fec_ini OR :param_fec_fin = NULL) 
        //                                          AND (a.created_at <= :param_fec_fin OR :param_fec_fin = NULL)
        //                                          AND (a.ip = :param_ip OR :param_ip = NULL)', ['param_fec_ini'  => $request->param_fec_ini
        //                                                                                        ,'param_fec_fin' => $request->param_fec_fin
        //                                                                                        ,'param_ip'      => $request->ip]));

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
        // DB::connection('mysql_catalogo')->select('SELECT a.*, u.nombre AS vendedor, u.id AS iteration FROM audits a, users u WHERE u.id = a.user_id ORDER BY iteration asc');
        //DB::connection('mysql_catalogo')->select(DB::raw("SELECT a.*, u.nombre AS vendedor, @row_num:= @row_num + 1 AS iteration FROM audits a, users u WHERE u.id = a.user_id ORDER BY $columnName $columnSortOrder"));
        $lineaSearchByName        = '';
        $lineaSearchByIp          = '';
        $lineaSearchByDispositivo = '';
        $lineaSearchByDesde       = '';
        $lineaSearchByHasta       = '';
        $lineaSearchByUrl         = '';
        $lineaSearchByReferencia  = '';
        $lineaSearchByRol         = '';
        $lineaSearchByObjeto      = '';
        if($searchByName) {
            $lineaSearchByName = "AND u.nombre LIKE '%$searchByName%'";
        }
        if($searchByIp) {
            $lineaSearchByIp = "AND a.ip LIKE '%$searchByIp%'";
        }
        if($searchByDispositivo) {
            $lineaSearchByDispositivo = "AND a.user_agent LIKE '%$searchByDispositivo%'";
        }
        if($searchByDesde) {
            // $searchByDesde = strval( date("Y-m-d", strtotime($searchByDesde)));
            $searchByDesde = $searchByDesde.' 00:00:00';
            $lineaSearchByDesde = "AND a.created_at >= '$searchByDesde'";
        }
        if($searchByHasta) {
            // $searchByHasta = strval( date("Y-m-d", strtotime($searchByHasta)));
            $searchByHasta = $searchByHasta.' 00:00:00';
            $lineaSearchByHasta = "AND a.created_at <= '$searchByHasta'";
        }
        if($searchByUrl) {
            $lineaSearchByUrl = "AND a.url LIKE '%$searchByUrl%'";
        }
        if($searchByReferencia) {
            $lineaSearchByReferencia = "AND a.object_reference LIKE '%$searchByReferencia%'";
        }
        if($searchByRol) {
            $lineaSearchByRol = "AND a.user_role = '$searchByRol'";
        }
        if($searchByObjeto) {
            $lineaSearchByObjeto = "AND a.object = '$searchByObjeto'";
        }
        \Log::info('lineaSearchByDesde '.$lineaSearchByDesde);
         // Total records
         $totalRecords           = count(DB::connection('mysql_catalogo')
                                            ->select('SELECT a.*, u.nombre AS vendedor
                                                        FROM audits a, users u
                                                        WHERE u.id = a.user_id'));
        $totalRecordswithFilter = count(DB::connection('mysql_catalogo')
                                        ->select(DB::raw("SELECT a.*, u.nombre AS vendedor, @row_num:= @row_num + 1 AS iteration
                                                    FROM audits a, users u
                                                WHERE u.id = a.user_id
                                                $lineaSearchByName
                                                $lineaSearchByIp
                                                $lineaSearchByDispositivo
                                                $lineaSearchByDesde
                                                $lineaSearchByHasta
                                                $lineaSearchByUrl
                                                $lineaSearchByReferencia
                                                $lineaSearchByRol
                                                $lineaSearchByObjeto")));
        // Records
        $records = DB::connection('mysql_catalogo')
                     ->select(DB::raw("SELECT a.*, u.nombre AS vendedor, @row_num:= @row_num + 1 AS iteration
                                 FROM audits a, users u
                                WHERE u.id = a.user_id
                                $lineaSearchByName
                                $lineaSearchByIp
                                $lineaSearchByDispositivo
                                $lineaSearchByDesde
                                $lineaSearchByHasta
                                $lineaSearchByUrl
                                $lineaSearchByReferencia
                                $lineaSearchByRol
                                $lineaSearchByObjeto
                                ORDER BY $columnName $columnSortOrder
                                LIMIT $rowperpage
                               OFFSET $start"));
                                //   AND (a.created_at >= :param_fec_ini OR :param_fec_fin = NULL) 
                                //   AND (a.created_at <= :param_fec_fin OR :param_fec_fin = NULL)
                                //   AND (a.ip = :param_ip OR :param_ip = NULL)
                                // ', ['param_fec_ini'   => $request->param_fec_ini
                                //                                         ,'param_fec_fin'   => $request->param_fec_fin
                                //                                         ,'param_ip'        => $request->ip
                                //                                         ,'columnName'      => $columnName
                                //                                         ,'columnSortOrder' => $columnSortOrder]);
        // $records = Producto::with(['categoria', 'imagenes'])
        //                    ->orderBy($columnName,$columnSortOrder)
        //                    ->where('productos.nombre', 'like', '%' .$searchValue . '%')
        //                    ->orWhere('productos.nombre_web', 'like', '%' .$searchValue . '%')
        //                    ->orWhere('productos.descripcion', 'like', '%' .$searchValue . '%')
        //                    ->orWhere('productos.codigo', 'like', '%' .$searchValue . '%')
        //                    ->orWhere('productos.precio', 'like', '%' .$searchValue . '%')
        //                 //    ->orWhere('productos.precio_oferta', 'like', '%' .$searchValue . '%')
        //                    ->orWhere('productos.marca', 'like', '%' .$searchValue . '%')
        //                    //->orWhere('productos.categoria.nombre_web', 'like', '%' .$searchValue . '%')
        //                    ->orWhere('productos.tags', 'like', '%' .$searchValue . '%')
        //                    ->orWhere('productos.referencia', 'like', '%' .$searchValue . '%')
        //                    ->orWhereHas('categoria', function ($query) use($searchValue) {
        //                                 $query->where('nombre', 'like', '%' .$searchValue . '%');
        //                             })
        //                    ->select('productos.*', DB::raw("@row_num:= @row_num + 1 AS iteration"))
        //                    ->skip($start)
        //                    ->take($rowperpage)
        //                    ->get();
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
            $fecha                 = $record->created_at;
            $ip                    = $record->ip;
            $vendedor              = $record->vendedor;
            $dispositivo           = $record->user_agent;
            $url                   = $record->url;
            $objeto                = $record->object;
            $referencia            = $record->object_reference;
            $rol                   = $record->user_role;
            $id                    = $record->id;

            $data_arr[] = array(
                "iteration"             => $iteration,
                "fecha"                 => $fecha,
                "ip"                    => $ip,
                "vendedor"              => $vendedor,
                "dispositivo"           => $dispositivo,
                "url"                   => $url,
                "objeto"                => $objeto,
                "referencia"            => $referencia,
                "rol"                   => $rol,
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

    /*public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'      => ['required', 'string', 'max:255']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $catalogo_auditoria = Catalogo_auditoria::find($request->id);
            $catalogo_auditoria->update([
                'nombre'  => $request->nombre
            ]);
            Alert::success('Aviso', 'Dato <b>' . $catalogo_auditoria->nombre . '</b> actualizado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $catalogo_auditoria->nombre . '</b> error al actualizar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $catalogo_auditoria = Catalogo_auditoria::find($request->id);
            $catalogo_auditoria->delete();
            Alert::success('Aviso', 'Dato <b>' . $catalogo_auditoria->nombre . '</b> eliminado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $catalogo_auditoria->nombre . '</b> error al eliminar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }*/
}
