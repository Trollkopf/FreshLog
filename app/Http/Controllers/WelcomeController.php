<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CleaningLog;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function index()
    {
        // Obtener las limpiezas realizadas el día actual
        $todayCleanings = CleaningLog::whereDate('cleaned_at', Carbon::today())->get();

        // Convertir `cleaned_at` de cada elemento a un objeto Carbon si es una cadena
        $todayCleanings->transform(function ($cleaning) {
            $cleaning->cleaned_at = Carbon::parse($cleaning->cleaned_at);
            return $cleaning;
        });

        return view('welcome', compact('todayCleanings'));
    }
}