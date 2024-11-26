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
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->input('user_id');
        $now = Carbon::now();

        $lastRecord = CleaningLog::latest('cleaned_at')->first();

        $lastRecordParsed = Carbon::parse($lastRecord->cleaned_at);

        if ($lastRecordParsed->diffInMinutes(Carbon::parse($now)) < 30) {
            return response()->json([
                'error' => true,
                'lastUserName' => $lastRecord->user->name,
                'lastRecordTime' => Carbon::parse($lastRecord->cleaned_at)->format('H:i:s'),
            ]);
        }

        CleaningLog::create([
            'bathroom_id' => 'ID_DEL_BAÑO',
            'user_id' => $userId,
            'cleaned_at' => $now,
        ]);

        return response()->json(['success' => true]);
    }
}
