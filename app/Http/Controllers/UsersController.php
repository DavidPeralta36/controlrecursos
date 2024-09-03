<?php

namespace App\Http\Controllers;

use App\User;
use Hash;
use Illuminate\Http\Request;
use App\models\fuentefinan;
use App\models\registrobancos;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
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
        //$fuentes = fuentefinan::all();
        //$periodos = registrobancos::select('ejercicio')->distinct()->get();

        //return view('carga', compact('fuentes', 'periodos'));
        return view('carga');
    }

    public function createNewUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data) {

            // Verifica si $data es un array con clave '_value'
            if (isset($data['_value']) && is_array($data['_value'])) {
                $data = $data['_value']; // Redefinir $data al nivel correcto
            }

            Log::info($data);
            
            $username = $data['name'] ?? 'no definido';
            $correo = $data['email'] ?? 'no definido';
            $password = $data['password'] ?? 'no definido';
            $password_confirmation = $data['password_confirmation'] ?? null;
            $role = $data['role'] ?? 'no definido'; 

            if($password != $password_confirmation){
                return response()->json(['error' => 'Las contraseñas no coinciden'], 400);
            }

            User::create([
                'name' => $username,
                'email' => $correo,
                'password' => Hash::make($password),
                'role' => $role,
            ]);

            
            return response()->json(['status' => 'completado']);
        } else {
            echo $data;
            // Retornar una respuesta de error si no se pudo decodificar el JSON
            return response()->json(['error' => 'Datos JSON no válidos'], 400);
        }
    }

}
