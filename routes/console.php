<?php

use App\Models\CleaningLog;
use App\Mail\DailyCleaningReport;
use App\Models\Configuration;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

// Comando para enviar el informe diario de limpieza
Artisan::command('send:daily-cleaning-report', function () {
    // Obtener horarios de apertura y cierre desde la base de datos
    $openingTime = Configuration::where('key', 'opening_time')->value('value');
    $closingTime = Configuration::where('key', 'closing_time')->value('value');

    // Convertir horarios a Carbon
    $openingHour = Carbon::createFromFormat('H:i', $openingTime);
    $closingHour = Carbon::createFromFormat('H:i', $closingTime);

    // Definir rango de tiempo según horarios de apertura y cierre
    $startTime = Carbon::yesterday()->setHour($openingHour->hour)->setMinute($openingHour->minute)->setSecond(0);
    $endTime = Carbon::today()->setHour($closingHour->hour)->setMinute($closingHour->minute)->setSecond(0);

    // Obtener registros de limpieza en el rango
    $cleanings = CleaningLog::whereBetween('cleaned_at', [$startTime, $endTime])
        ->with('user')
        ->get();

    if ($cleanings->isEmpty()) {
        $this->info('No hay registros de limpieza en el rango especificado.');
        return;
    }

    // Generar el PDF con los registros
    $pdf = Pdf::loadView('pdf.daily_report', compact('cleanings', 'startTime', 'endTime'));
    $pdfPath = storage_path('app/public/informe_diario_registros_limpieza.pdf');
    $pdf->save($pdfPath);

    // Obtener los emails configurados
    $emails = Email::pluck('email')->toArray();

    if (empty($emails)) {
        $this->info('No hay emails configurados para enviar el informe.');
        return;
    }

    // Enviar el informe a cada email configurado
    foreach ($emails as $email) {
        Mail::to($email)->send(new DailyCleaningReport($pdfPath));
    }

    $this->info('Informe diario de registros de limpieza enviado exitosamente.');
});

// Programar comandos en el horario configurado
Schedule::command('send:daily-cleaning-report')->dailyAt(Configuration::where('key', 'report_time')->value('value'));
