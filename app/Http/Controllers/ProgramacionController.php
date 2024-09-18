<?php

namespace App\Http\Controllers;

use App\models\partidas;
use App\models\rubros;
use Illuminate\Http\Request;
use App\models\fuentefinan;
use App\models\registrobancos;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\models\programacionpartidas;

class ProgramacionController extends Controller
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
        $partidas = partidas::all();

        return view('programacion', compact('fuentes', 'periodos', 'partidas'));
    }


    public function saveProgramacionPartidas(Request $request)
    {
        $partidasProgramadas = json_decode($request->input('partidas'), true);
        
        //begin a transaction
        DB::beginTransaction();
        try
        {
            foreach ($partidasProgramadas as $partidaProgramada) {
                Log::info($partidaProgramada);
            
                // Search for partida where idpartida and ejercicio match
                $partida = programacionpartidas::where('idpartida', $partidaProgramada['idpartida'])
                    ->where('ejercicio', $partidaProgramada['ejercicio'])
                    ->first();
                Log::info($partida);
            
                // Check if 'idcapitulo' exists in $partidaProgramada array
                if (isset($partidaProgramada['idcapitulo'])) {
                    $partidaProgramada['idrubro'] = rubros::where('id', $partidaProgramada['idcapitulo'])->first()->id;
                    Log::info($partidaProgramada);
            
                    if ($partida) {
                        $partida->update($partidaProgramada);
                    } else {
                        programacionpartidas::create($partidaProgramada);
                    }
                } else {
                    Log::error("La clave 'idcapitulo' no existe en \$partidaProgramada.");
                }
            }

            DB::commit();
            return response()->json('Programacion guardada exitosamente');
        } catch (\Exception $ex) {
            Log::info($ex->getMessage());
            DB::rollBack();
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }
}
