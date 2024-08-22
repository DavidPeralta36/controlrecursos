<?php

namespace App\Http\Controllers;

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
        return view('home', compact('fuentes'));
    }

    public function getReport(Request $request)
    {
        //Log::info('se disparo el metodo getReport');
        
        $beginingDate = $request->beginingDate;
        $endDate = $request->endDate;
        $sources = $request->sources;

        $registros = registrobancos::whereBetween('fechas', [$beginingDate, $endDate]) 
            ->whereIn('idfuente', [1])
            ->get();

        
        return response()->json($registros);
    }
}
