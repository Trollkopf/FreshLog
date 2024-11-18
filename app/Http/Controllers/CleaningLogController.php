<?php

namespace App\Http\Controllers;

use App\Models\CleaningLog;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CleaningLogController extends Controller
{
    public function index(Request $request)
    {
        // Obtener la contraseña almacenada en la configuración
        $storedPassword = Setting::where('key', 'operario_password')->value('value');
    
        $password = $request->input('password');
    
        // Verificar la contraseña
        if ($storedPassword && Hash::check($password, $storedPassword)) {
            $users = User::where('role', '!=', 'admin')->get();
            return view('cleaning.index', compact('users'));
        }
    
        return redirect()->back()->with('error', 'Contraseña incorrecta.');
    }

    public function store(Request $request)
    {
        // Validamos que el user_id esté presente en la solicitud
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Creamos el registro de limpieza
        $log = new CleaningLog();
        $log->bathroom_id = 'ID_DEL_BAÑO'; // Puedes ajustar esto para obtener el ID dinámicamente más adelante
        $log->user_id = $request->user_id;
        $log->cleaned_at = Carbon::now(); // Timestamp actual
        $log->save();

        return redirect()->back()->with('success', 'Registro de limpieza guardado.');
    }
}
