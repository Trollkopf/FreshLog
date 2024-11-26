<?php

namespace App\Http\Controllers;

use App\Models\CleaningLog;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
class AdminController extends Controller
{

    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get(); // Excluir el usuario autenticado
        return view('admin.dashboard', compact('users'));
    }

    public function createUser()
    {
        return view('admin.create_user');
    }

    public function generatePDF()
    {
        // Obtener datos para el PDF, por ejemplo, lista de usuarios
        $users = User::all();

        // Renderizar una vista para el PDF
        $pdf = Pdf::loadView('pdf.users', compact('users'));

        // Descargar el PDF
        return $pdf->download('usuarios.pdf');
    }

    public function generateDailyReport()
    {
        // Obtener los registros de limpieza del día
        $todayCleanings = CleaningLog::whereDate('cleaned_at', Carbon::today())->with('user')->get();

        // Renderizar la vista con los registros de limpieza
        $pdf = Pdf::loadView('pdf.daily_report', compact('todayCleanings'));

        // Descargar el PDF con un nombre específico
        return $pdf->download('informe_diario_registros_limpieza.pdf');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Crear un nuevo usuario con el nombre proporcionado y un rol predeterminado
        User::create([
            'name' => $request->name,
            'email' => Str::random(10) . '@example.com', // Genera un email temporal
            'password' => Hash::make(Str::random(10)), // Genera una contraseña aleatoria
            'role' => 'user', // Rol predeterminado
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Usuario creado exitosamente.');
    }

    // Método para eliminar usuario
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Evitar que se eliminen administradores
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'No puedes eliminar un administrador.');
        }

        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Usuario eliminado exitosamente.');
    }

    public function storePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        // Guardar o actualizar la contraseña en la tabla `settings`
        Setting::updateOrCreate(
            ['key' => 'operario_password'], // Clave única
            ['value' => bcrypt($request->input('password'))] // Almacenar la contraseña cifrada
        );

        return redirect()->back()->with('success', 'Contraseña actualizada exitosamente.');
    }

    // Método para cambiar el rol de usuario a admin
    public function changeRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $newRole = ($user->role === 'worker') ? 'manager' : 'worker';

        $user->role = $newRole;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', "Usuario ahora es $newRole.");
    }
}
