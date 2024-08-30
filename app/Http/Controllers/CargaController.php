<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\fuentefinan;
use App\models\registrobancos;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CargaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $fuentes = fuentefinan::all();
        $periodos = registrobancos::select('ejercicio')->distinct()->get();

        return view('carga', compact('fuentes', 'periodos'));
    }

    public function uploadReport(Request $request)
    {
        $request->validate([
            'archivo' => 'required', // El archivo es obligatorio
            'source' => 'required', // El campo 'source' es obligatorio
            'periodo' => 'required', // El campo 'periodo' es obligatorio
        ]);

        //validate the request
        $file = $request->file('archivo');
        $source = $request->input('source');
        $periodo = $request->input('periodo');

        if($source == 4){
            if ($file->getClientOriginalExtension() === 'json') {
                $data = json_decode(file_get_contents($file->getRealPath()), true);
    
                array_shift($data);
    
                $ckp = [];
    
                try{
                    Log::info("Iniciando transaccion");
    
                    DB::beginTransaction();
    
                    foreach($data as $row){
    
                        if($row[0] == null || $row[0] == '_' || $row[0] == ' ' || $row[0] == '' ){
                            continue;
                        }
                        $registro = [
                            'fechas' => $row[0], 
                            'mes' => $row[1],
                            'metodo_pago' => $row[2], 
                            'rfc' => $row[3],
                            'proveedor' => $row[4],
                            'factura' => $row[5],
                            'parcial' => $row[6],
                            'depositos' => $row[7],
                            'retiros' => $row[8],
                            'saldo' => $row[9],
                            'r' => $row[10],
                            'partida' => $row[11],
                            'fecha_factura' => $row[12],
                            'folio_fiscal' => $row[13],
                            'tipo_adjudicacion' => $row[14],
                            'num_adj_contrato' => $row[15],
                            //'num_techo_financiero' => $row[16],
                            //'num_suficiencia_presupuestal' => $row[16],
                            'orden_servicio_compra' => $row[17],
                            'clc' => $row[18],
                            'poliza' => $row[19],
                            'numero_cuenta_proovedor' => $row[20],
                            'referencia_bancaria' => $row[21],
                            'clue' => $row[22],
                            'nombre_clue' => $row[23],
                            'nombrepartida' => $row[24] ?? null, // Evitar errores si falta índice
                            'mes_servicio' => $row[25] ?? null,
                            //'metodo_pago' => $row[26] ?? null,
                            'idfuente' => $source,
                            'ejercicio' => $periodo,
                        ];
                        $ckp = $registro;
                        Log::info("Registro", $registro);
                        //registrobancos::create($registro);
                    }
                    //DB::commit();
                    echo "Datos insertados correctamente.";
                    Log::info("Datos ingresados correctamente");
                }catch(\Exception $ex){
                    Log::info("Errorsote: ".$ex->getMessage());
                    Log::info("",$ckp);
                    echo "Error al insertar los datos: " + $ex->getMessage();
                }
            } 
        }

        if($source == 5){ //E001
            if ($file->getClientOriginalExtension() === 'json') {
                $data = json_decode(file_get_contents($file->getRealPath()), true);
    
                array_shift($data);
    
                $ckp = [];
    
                try{
                    Log::info("Iniciando transaccion");
    
                    DB::beginTransaction();
    
                    foreach($data as $row){
    
                        if($row[0] == null || $row[0] == '_' || $row[0] == ' ' || $row[0] == '' ){
                            continue;
                        }
                        $registro = [
                            'fechas' => $row[0], 
                            'mes' => $row[1],
                            'forma_pago' => $row[2], 
                            'rfc' => $row[3],
                            'proveedor' => $row[4],
                            'factura' => $row[5],
                            'parcial' => $row[6],
                            'depositos' => $row[8],
                            'retiros' => $row[7],
                            'saldo' => $row[9],
                            'r' => $row[10],
                            'partida' => $row[11],
                            'fecha_factura' => $row[12],
                            'folio_fiscal' => $row[13],
                            'tipo_adjudicacion' => $row[14],
                            'num_adj_contrato' => $row[15],
                            'num_suficiencia_presupuestal' => $row[16],
                            'orden_servicio_compra' => $row[17],
                            'clc' => $row[18],
                            'poliza' => $row[19],
                            'numero_cuenta_proovedor' => $row[20],
                            'referencia_bancaria' => $row[21],
                            'clue' => $row[22],
                            'nombre_clue' => $row[23],
                            'nombrepartida' => $row[24] ?? null, // Evitar errores si falta índice
                            'mes_servicio' => $row[25] ?? null,
                            'metodo_pago' => $row[26] ?? null,
                            'idfuente' => $source,
                            'ejercicio' => $periodo,
                        ];
                        $ckp = $registro;
                        Log::info("Registro", $registro);
                        //registrobancos::create($registro);
                    }
                    //DB::commit();
                    echo "Datos insertados correctamente.";
                    Log::info("Datos ingresados correctamente");
                }catch(\Exception $ex){
                    Log::info("Errorsote: ".$ex->getMessage());
                    Log::info("",$ckp);
                    echo "Error al insertar los datos: " + $ex->getMessage();
                }
            } 
        }

        return response()->json('Subido exitosamente');
    }

}
