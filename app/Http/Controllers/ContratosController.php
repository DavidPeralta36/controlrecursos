<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\fuentefinan;
use App\models\registrobancos;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ContratosController extends Controller
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

        return view('contratos', compact('fuentes', 'periodos'));
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
