<?php

namespace App\Http\Controllers;

use App\models\capitulos;
use App\models\clues;
use App\models\partidas;
use App\models\proveedor;
use Illuminate\Http\Request;
use App\models\fuentefinan;
use App\models\registrobancos;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CatalogosController extends Controller
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
        $partidas = partidas::all();
        $capitulos = capitulos::all();
        $clues = clues::all();
        $proveedores = proveedor::all();

        return view('catalogos', compact('partidas', 'capitulos', 'clues', 'proveedores'));
    }

    public function saveEditedPartidas(Request $request)
    {
        $editedRecords = json_decode($request->input('records'), true);

        if (is_array($editedRecords)) {
            foreach ($editedRecords as $record) {
                partidas::where('id', $record['id'])->update($record);
            }

            return response()->json('Partida programada actualizada exitosamente');
        } else {
            return response()->json('Error al guardar la partida programada', 400);
        }
    }

    public function getPartidas(){
        $partidas = partidas::all();

        return response()->json($partidas);
    }
    public function saveNewPartida(Request $request){
        $partida = $request->input('partida');
        $descripcion = $request->input('descripcion');
        $capitulo = $request->input('capitulo');
        

        partidas::create([
            'partida' => $partida,
            'descripcion' => $descripcion,
            'aportacion_federal' => 0,
            'aportacion_estatal' => 0,
            'req_auth_aimb' => 0,
            'req_auth_af' => 0,
            'req_auth_aeg' => 0,
            'remuneracionPersonal' => 0,
            'adminInsumosMed' => 0,
            'gastosOperacion' => 0,
            'idcapitulo' => $capitulo,
        ]);
    }

    public function deletePartida(Request $request)
    {
        partidas::where('id', $request->input('id'))->delete();

        return response()->json('Partida programada eliminada exitosamente');
    }

    public function saveNewClue(Request $request){
        $clue = $request->input('clue');
        $clue_homologada = $request->input('clue_homologada');
        $nombre_clue = $request->input('nombre_clue');
        
        Log::info($clue_homologada);
        clues::create([
            'clue' => $clue,
            'clue_homologada' => $clue_homologada,
            'nombre_clue' => $nombre_clue,
        ]);
    }

    public function saveEditedClues(Request $request)
    {
        $editedRecords = json_decode($request->input('records'), true);

        if (is_array($editedRecords)) {
            foreach ($editedRecords as $record) {
                clues::where('id', $record['id'])->update($record);
            }

            return response()->json('Clue programada actualizada exitosamente');
        } else {
            return response()->json('Error al guardar la clue programada', 400);
        }
    }

    public function deleteClue(Request $request)
    {
        clues::where('id', $request->input('id'))->delete();

        return response()->json('Clue eliminada exitosamente');
    }

    public function saveNewProveedor(Request $request){
        $proveedor = $request->input('proveedor');
        $rfc = $request->input('rfc');
        $numero_cuenta_proovedor = $request->input('numero_cuenta_proovedor');

        proveedor::create([
            'rfc' => $rfc,
            'proveedor' => $proveedor,
            'numero_cuenta' => $numero_cuenta_proovedor,
        ]);
    }

    public function saveEditedProveedores(Request $request)
    {
        $editedRecords = json_decode($request->input('records'), true);

        if (is_array($editedRecords)) {
            foreach ($editedRecords as $record) {
                proveedor::where('id', $record['id'])->update($record);
            }

            return response()->json('Proveedor programada actualizada exitosamente');
        } else {
            return response()->json('Error al guardar la proveedor programada', 400);
        }
    }

    public function deleteProveedor(Request $request)
    {
        proveedor::where('id', $request->input('id'))->delete();

        return response()->json('Proveedor eliminada exitosamente');
    }
}
