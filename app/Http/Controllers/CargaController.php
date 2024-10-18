<?php

namespace App\Http\Controllers;

use App\models\clues;
use App\models\lastbank;
use App\models\partidas;
use App\models\proveedor;
use Illuminate\Http\Request;
use App\models\fuentefinan;
use App\models\registrobancos;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    function getExpectedSourceDesc($source){
        $desc = "";
        if($source == 1){
            $desc = "u013";
        }
        if($source == 2){
            $desc = "s200";
        }
        if($source == 3){
            $desc = "sanas";
        }
        if($source == 4){
            $desc = "asle";
        }
        if($source == 5){
            $desc = "e001";
        }

        return $desc;
    }

    public function uploadReport(Request $request)
    {
        $allOk = true;
        $erroresClaveForanea = [];
        $request->validate([
            'archivo' => 'required', // El archivo es obligatorio
            'source' => 'required', // El campo 'source' es obligatorio
            'periodo' => 'required', // El campo 'periodo' es obligatorio
        ]);

        //validate the request
        $file = $request->file('archivo');
        $source = $request->input('source');
        $periodo = $request->input('periodo');

        if ($file->getClientOriginalExtension() === 'json') {
            $data = json_decode(file_get_contents($file->getRealPath()), true);

            if (isset($data[1][27])) {
                $sourceDesc = strtolower($data[1][27]);

                // Replace getSourceDesc($source) with a correct function or logic to obtain the description
                $expectedSourceDesc = $this->getExpectedSourceDesc($source);

                if($sourceDesc != $expectedSourceDesc)
                {
                    return response()->json([
                        'status' => 'error_source',
                        'message' => 'No se cargo el arhcivo correcto'
                    ], 200);
                }



            } else {
                return response()->json([
                    'status' => 'error_emptySource',
                    'message' => 'No se recibio la fuente en el archivo.'
                ], 200);
            }

            array_shift($data);

            $ckp = [];

            try {
                //Obtein the last id of registrobancos
                $lastIdBegin = registrobancos::max('id') + 1;

                DB::beginTransaction();

                foreach ($data as $row) {

                    if ($row[1] == null || $row[1] == '_' || $row[1] == ' ' || $row[1] == '') {
                        continue;
                    }
                    $registro = [
                        'fechas' => $row[0],
                        'mes' => $row[1],
                        'forma_pago' => $row[2],
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
                        //'num_techo_financiero' => $row[15],
                        'num_suficiencia_presupuestal' => $row[17],
                        'orden_servicio_compra' => $row[18],
                        'clc' => $row[19],
                        'poliza' => $row[20],
                        'numero_cuenta_proovedor' => $row[21],
                        'referencia_bancaria' => $row[22],
                        'clue' => $row[23],
                        'nombre_clue' => $row[24],
                        'nombrepartida' => $row[25] ?? null,
                        'mes_servicio' => $row[26] ?? null,
                        'fuente' => $row[27] ?? null,
                        //'metodo_pago' => $row[24] ?? null,
                        'idfuente' => $source,
                        'ejercicio' => $periodo,
                    ];
                    $ckp = $registro;

                    if (strpos($registro['clue'], '-') !== false) {
                        $registro['clue'] = str_replace('-', '', $registro['clue']);
                    }
                    try {
                        registrobancos::create($registro);
                    } 
                    catch (\Exception $ex) 
                    {
                        $allOk = false;
                        Log::info("Error al insertar registro: " . $ex->getMessage());
                        if (strpos($ex->getMessage(), 'a foreign key constraint fails') !== false) {
                            if (preg_match('/FOREIGN KEY \(`(.*?)`\)/', $ex->getMessage(), $matches)) {
                                $columna = $matches[1];

                                if ($columna == 'clue') {
                                    //buscar en clues por clue y asigna por la propiedad clue
                                    $clue = clues::where('clue_homologada', $registro['clue'])->first();
                                    if ($clue) {
                                        $registro['clue'] = $clue->clue;

                                        try {
                                            registrobancos::create($registro);
                                            $allOk = true;
                                        } catch (\Exception $ex) {
                                            Log::info("Error al reintentar insertar el registro corregido: " . $ex->getMessage());
                                            $allOk = false;
                                        }
                                    }
                                }

                                if (!isset($erroresClaveForanea[$columna])) {
                                    $erroresClaveForanea[$columna] = [];
                                }
                                $erroresClaveForanea[$columna][] = $registro;
                                Log::info("Errores", $erroresClaveForanea);
                                $mensajeError = "Error de clave foránea en la columna: $columna.";
                            } else {
                                $mensajeError = "Error de clave foránea, pero no se pudo determinar la columna.";
                            }
                        } else {
                            $mensajeError = "Error inesperado: " . $ex->getMessage();
                        }

                    }
                }
                if ($allOk) {
                    DB::commit();

                    $lastIdSave = registrobancos::max('id');

                    // Verifica si existe un registro
                    $lastBankRecord = lastbank::first();
                    
                    if ($lastBankRecord) {
                        // Si existe, actualiza el registro
                        $lastBankRecord->update([
                            'idinicio' => $lastIdBegin,
                            'idfin' => $lastIdSave,
                            'fuente' => $source,
                            'ejercicio' => $periodo,
                        ]);
                    } else {
                        // Si no existe, crea uno nuevo
                        lastbank::create([
                            'idinicio' => $lastIdBegin,
                            'idfin' => $lastIdSave,
                            'fuente' => $source,
                            'ejercicio' => $periodo,
                        ]);
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Datos insertados correctamente.'
                    ], 200);
                } else {
                    Log::info("", $erroresClaveForanea);
                    DB::rollBack();
                    Log::info("", $erroresClaveForanea);
                    return response()->json([
                        'status' => 'error',
                        'message' => $mensajeError,
                        'errores' => $erroresClaveForanea  // Retorna la lista de errores agrupados por columna
                    ], 200);  // Puedes usar el código 400 Bad Request para indicar que hubo un error
                }

            } catch (\Exception $ex) {
                Log::info("Errorsote: " . $ex->getMessage());
                Log::info("", $ckp);
                echo "Error al insertar los datos: " + $ex->getMessage();
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Formato de archivo no soportado.'
        ], 400);
    }

    public function edit_records(Request $request)
    {
        DB::beginTransaction();
        try {
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

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function saveNewPartidas(Request $request){
        $newPartidas = json_decode($request->input('newProveedores'), true);

        if(is_array($newPartidas)){
            foreach($newPartidas as $partida){
                partidas::create([
                    'partida' => $partida['partida'],
                    'aportacion_federal' => $partida['aportacion_federal'],
                    'aportacion_estatal' => $partida['aportacion_estatal'],
                    'descripcion' => $partida['descripcion'],
                    'req_auth_a_imb' => $partida['req_auth_a_imb'],
                    'req_auth_af' => $partida['req_auth_af'],
                    'remuneracionPersonal' => $partida['remuneracionPersonal'],
                    'adminInsumosMed' => $partida['adminInsumosMed'],
                    'gastosOperacion' => $partida['gastosOperacion'],
                    'idcapitulo' => $partida['idcapitulo']
                ]);
            }
        }else{
            return response()->json('Error al guardar nuevas partidas', 400);
        }
    }

    public function saveNewProveedores(Request $request){
        $newProveedores = json_decode($request->input('newProveedores'), true);

        if(is_array($newProveedores)){
            foreach($newProveedores as $proveedor){
                proveedor::create([
                    'rfc' => $proveedor['rfc'],
                    'proveedor' => $proveedor['proveedor'],
                    'numero_cuenta' => $proveedor['numero_cuenta_proovedor'],
                ]);
            }
        }else{
            return response()->json('Error al guardar nuevas partidas', 400);
        }
    }

    public function saveNewClues(Request $request){
        $newClues = json_decode($request->input('newClues'), true);

        if(is_array($newClues)){
            foreach($newClues as $clue){
                clues::create([
                    'clue' => $clue['clue'],
                    'nombre_clue' => $clue['nombre_clue'], 
                ]);
            }
        }else{
            return response()->json('Error al guardar nuevas partidas', 400);
        }
    }

    public function downloadPlantilla(Request $request)
    {
        // Crear una nueva hoja de cálculo
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $formatFile = [
            "FECHA",
            "MES",
            "FORMA DE PAGO",
            "METODO DE PAGO",
            "RFC",
            "PROVEDOR",
            "FACTURA",
            "PARCIAL",
            "DEPOSITOS",
            "RETIROS",
            "SALDO",
            "R",
            "PARTIDA PRESUPUESTAL",
            "FECHA DE FACTURA",
            "FOLIO FISCAL",
            "TIPO DE ADJUDICACION",
            "NUMERO DE ADJUDICACION O CONTRATO",
            "NUMERO DE SUFICIENCIA PRESUPUESTAL",
            "ORDEN DE SERVICIO O COMPRA",
            "CLC",
            "POLIZA",
            "NUMERO DE CUENTA DEL PROVEEDOR",
            "REFERENCIA BANCARIA",
            "CLUE",
            "APLICA EN:",
            "NOMBRE DE LA PARTIDA",
            "MES DE SERVICIO",
            "FUENTE"
        ];


        foreach ($formatFile as $index => $columnName) {
            $sheet->setCellValueByColumnAndRow($index + 1, 1, $columnName);
        }

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        // Configurar los encabezados de la respuesta
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="plantilla.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function getLastBank(Request $request)
    {
        $lastBank = lastbank::first();

        return response()->json($lastBank);
    }

    public function deshacerUltimaCarga(Request $request)
    {
        DB::beginTransaction();

        try {
            $lastBank = lastbank::first();

            if($lastBank){
                $idInicio = $lastBank->idinicio;
                $idFin = $lastBank->idfin;

                registrobancos::where('id', '>=', $idInicio)
                    ->where('id', '<=', $idFin)
                    ->delete();

                lastbank::where('id', $lastBank->id)->delete();
            }

            DB::commit();

            return response()->json('Ultima carga deshecha exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json('Error al deshacer la ultima carga', 500);
        }
    }
}
