<?php

namespace App\Http\Controllers;

use App\models\capitulos;
use App\models\partidas;
use App\models\programacionrubros;
use App\models\rubros;
use Arr;
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

    public function getPartidasProgramadas(Request $request)
    {
        $ejercicio = $request->input('ejercicio');
        $source = $request->input('source');

        //find partidas programadas for the given source and ejercicio
        $partidasProgramadas = programacionpartidas::where('ejercicio', $ejercicio)
            ->where('idfuente', $source)
            ->get();

        //get description for idpartida, idcapitulo from partidasProgramadas
        foreach ($partidasProgramadas as $partidaProgramada) {
            $partida = partidas::where('id', $partidaProgramada->idpartida)->first();
            $partidaProgramada->descripcion = $partida->descripcion;
            $capitulo = capitulos::where('id', $partidaProgramada->idcapitulo)->first();
            $partidaProgramada->nombre_capitulo = $capitulo->descripcion;
        }

        return response()->json($partidasProgramadas);
    }

    public function deletePartidaProgramada(Request $request)
    {
        programacionpartidas::where('id', $request->input('id'))->delete();

        return response()->json('Partida programada eliminada exitosamente');
    }

    public function editPartidaProgramada(Request $request)
    {
        //begin a transaction
        DB::beginTransaction();

        $editedRecords = json_decode($request->input('records'), true);

        if (is_array($editedRecords)) {
            foreach ($editedRecords as $record) {
                $partidaProgramada = programacionpartidas::where('id', $record['id'])->first();
                $partidaProgramada->monto_programado = $record['monto_programado'];
                $partidaProgramada->save();
            }

            DB::commit();
            return response()->json('Partida programada actualizada exitosamente');
        } else {
            return response()->json('Error al editar la partida programada', 400);
        }
    }

    public function getRubros(){
        $rubros = rubros::all();
        return response()->json($rubros);
    }

    public function generateProgramacionRubros(Request $request)
    {
        $rubrosProgramados = $request->input('rubros');

        // Begin a transaction
        DB::beginTransaction();

        try
        {
            foreach ($rubrosProgramados as $rubroProgramado) {
                Log::info("-------------------------------------------------------------------------------------------------------------");
            
                // Get idrubro instead of id
                $idrubro = rubros::where('descripcion', $rubroProgramado['descripcion'])->first()->id;
                

                // Remove the 'id' key to avoid conflict
                $rubroProgramado = Arr::except($rubroProgramado, ['id']);

                // Add the idrubro key
                $rubroProgramado['idrubro'] = $idrubro;
                
                // Search for rubro where idfuente and ejercicio and idrubro match
                $rubro = programacionrubros::where('idrubro', $idrubro)
                    ->where('ejercicio', $rubroProgramado['ejercicio'])
                    ->where('idfuente', $rubroProgramado['idfuente'])
                    ->first();
                
                Log::info($rubroProgramado['ejercicio']);

                if ($rubro) {
                    $rubro->monto_programado = $rubroProgramado['monto_programado'];
                    $rubro->save();
                } else {
                    programacionrubros::create($rubroProgramado);
                }
            }
            DB::commit();
            return response()->json('Programación generada exitosamente');
        } catch (\Exception $ex) {
            Log::info($ex->getMessage());
            DB::rollBack();
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }

    public function getProgramacionRubros(Request $request)
    {
        $ejercicio = $request->input('ejercicio');
        $source = $request->input('source');

        $rubrosProgramados = programacionrubros::where('ejercicio', $ejercicio)
            ->where('idfuente', $source)
            ->get();

        foreach( $rubrosProgramados as $rubroprogramado){
            $rubro = rubros::where('id', $rubroprogramado->idrubro)->first();
            $fuente = fuentefinan::where('id', $rubroprogramado->idfuente)->first();

            //add rubro.descripcion to rubroProgramado
            $rubroprogramado->descripcion = $rubro->descripcion;
            $rubroprogramado->nombre_fuente = $fuente->nombre_fuente;
        }
        
        return response()->json($rubrosProgramados);
    }

    public function deleteProgramacionRubro(Request $request)
    {
        programacionrubros::where('id', $request->input('id'))->delete();

        return response()->json('Programación por rubro eliminada exitosamente');
    }
}
