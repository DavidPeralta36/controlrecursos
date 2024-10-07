<?php

namespace App\Http\Controllers;

use App\models\capitulos;
use App\models\partidas;
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

        return view('catalogos', compact('partidas', 'capitulos'));
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
}
