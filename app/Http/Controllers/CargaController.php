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

        if($source == 1){//U013
            if ($file->getClientOriginalExtension() === 'json') {
                $data = json_decode(file_get_contents($file->getRealPath()), true);
    
                array_shift($data);
    
                $ckp = [];
    
                try{
                    Log::info("Iniciando transaccion");
    
                    DB::beginTransaction();
    
                    foreach($data as $row){

                        if($row[1] == null || $row[1] == '_' || $row[1] == ' ' || $row[1] == '' ){
                            continue;
                        }
                        $registro = [
                            'fechas' => $row[1], 
                            'mes' => $row[2],
                            'forma_pago' => $row[3], 
                            'metodo_pago' => $row[4], 
                            'rfc' => $row[5],
                            'proveedor' => $row[6],
                            'factura' => $row[7],
                            'parcial' => $row[8],
                            'depositos' => $row[9],
                            'retiros' => $row[10],
                            'saldo' => $row[11],
                            'r' => $row[12],
                            'partida' => $row[13],
                            'fecha_factura' => $row[14],
                            'folio_fiscal' => $row[15],
                            'tipo_adjudicacion' => $row[16],
                            'num_adj_contrato' => $row[17],
                            //'num_techo_financiero' => $row[17],
                            'num_suficiencia_presupuestal' => $row[18],
                            'orden_servicio_compra' => $row[19],
                            'clc' => $row[20],
                            'poliza' => $row[21],
                            'numero_cuenta_proovedor' => $row[22],
                            'referencia_bancaria' => $row[23],
                            'clue' => $row[24],
                            'nombre_clue' => $row[25],
                            'nombrepartida' => $row[26] ?? null, // Evitar errores si falta índice
                            'mes_servicio' => $row[27] ?? null,
                            //'metodo_pago' => $row[26] ?? null,
                            'idfuente' => $source,
                            'ejercicio' => $periodo,
                        ];
                        $ckp = $registro;
                        
                        registrobancos::create($registro);
                    }
                    DB::commit();
                    echo "Datos insertados correctamente.";
                }catch(\Exception $ex){
                    Log::info("Errorsote: ".$ex->getMessage());
                    Log::info("",$ckp);
                    echo "Error al insertar los datos: " + $ex->getMessage();
                }
            } 
        }

        if($source == 4){//ASLE
            if ($file->getClientOriginalExtension() === 'json') {
                $data = json_decode(file_get_contents($file->getRealPath()), true);
    
                array_shift($data);
    
                $ckp = [];
    
                try{
                    Log::info("Iniciando transaccion");
    
                    DB::beginTransaction();
    
                    foreach($data as $row){

                        if($row[1] == null || $row[1] == '_' || $row[1] == ' ' || $row[1] == '' ){
                            continue;
                        }
                        $registro = [
                            'fechas' => $row[1], 
                            'mes' => $row[2],
                            'metodo_pago' => $row[3], 
                            'rfc' => $row[4],
                            'proveedor' => $row[5],
                            'factura' => $row[6],
                            'parcial' => $row[7],
                            'depositos' => $row[8],
                            'retiros' => $row[9],
                            'saldo' => $row[10],
                            'r' => $row[11],
                            'partida' => $row[12],
                            'fecha_factura' => $row[13],
                            'folio_fiscal' => $row[14],
                            'tipo_adjudicacion' => $row[15],
                            'num_adj_contrato' => $row[16],
                            //'num_techo_financiero' => $row[17],
                            //'num_suficiencia_presupuestal' => $row[16],
                            'orden_servicio_compra' => $row[18],
                            'clc' => $row[19],
                            'poliza' => $row[20],
                            'numero_cuenta_proovedor' => $row[21],
                            'referencia_bancaria' => $row[22],
                            'clue' => $row[23],
                            'nombre_clue' => $row[24],
                            'nombrepartida' => $row[25] ?? null, // Evitar errores si falta índice
                            'mes_servicio' => $row[26] ?? null,
                            //'metodo_pago' => $row[26] ?? null,
                            'idfuente' => $source,
                            'ejercicio' => $periodo,
                        ];
                        $ckp = $registro;
                        registrobancos::create($registro);
                    }
                    DB::commit();
                    echo "Datos insertados correctamente.";
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
                        registrobancos::create($registro);
                    }
                    DB::commit();
                    echo "Datos insertados correctamente.";
                }catch(\Exception $ex){
                    Log::info("Errorsote: ".$ex->getMessage());
                    Log::info("",$ckp);
                    echo "Error al insertar los datos: " + $ex->getMessage();
                }
            } 
        }

        return response()->json('Subido exitosamente');
    }

    public function edit_records(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $records = json_decode($request->input('records'), true);

            if (is_array($records)) {
                foreach ($records as $record) {
                    
                    $id = $record['id'];
                    unset($record['id']); 

                    registrobancos::where('id', $id)->update($record);
                }

                DB::commit();
                return response()->json('Registros actualizados exitosamente');
            } else {
                return response()->json('Error al editar los registros', 400);
            }
            
        }catch(\Exception $ex)
        {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
