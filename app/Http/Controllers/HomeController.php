<?php

namespace App\Http\Controllers;

use App\models\capitulos;
use Illuminate\Http\Request;
use App\models\fuentefinan;
use App\models\registrobancos;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
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

        return view('home', compact('fuentes', 'periodos'));
    }

    public function getReport(Request $request)
    {
        Log::info('se disparo el metodo getReport');
        
        $beginingDate = $request->beginingDate;
        $endDate = $request->endDate;
        $source = $request->source;

        Log::info('beginingDate: ' . $beginingDate);
        Log::info('endDate: ' . $endDate);

        $registros = registrobancos::whereBetween('fechas', [$beginingDate, $endDate]) 
            ->whereIn('idfuente', [$source])
            ->get();

        
        return response()->json($registros);
    }

    public function getReportByPeriod(Request $request)
    {
        $source = $request->source;
        $period = $request->period;

        $registros = registrobancos::where('ejercicio', $period)
        ->where('idfuente', $source) 
        #->where('mes', $month)
        ->get();

        return response()->json($registros);
    }

    public function getCapitulos(Request $request)
    {
        $capitulos = capitulos::all();

        return response()->json($capitulos);
    }
}
