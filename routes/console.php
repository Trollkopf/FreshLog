<?php 

use App\Models\CleaningLog;
use App\Mail\DailyCleaningReport;
use App\Models\Configuration;
use App\Models\Email;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

Artisan::command('send:daily-cleaning-report', function () {
    // Verificar si las tablas necesarias están disponibles
    if (!Schema::hasTable('configurations') || !Schema::hasTable('emails') || !Schema::hasTable('cleaning_logs')) {
        $this->info('Las tablas necesarias no están disponibles. Abortando el comando.');
        return;
    }

    // Obtener horarios de apertura y cierre desde la base de datos
    $openingTime = Configuration::where('key', 'opening_time')->value('value');
    $closingTime = Configuration::where('key', 'closing_time')->value('value');

    if (!$openingTime || !$closingTime) {
        $this->info('Los horarios de apertura o cierre no están configurados. Abortando el comando.');
        return;
    }

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

Artisan::command('send:daily-cleaning-report', function () {
    // Verificar si las tablas están disponibles
    if (!Schema::hasTable('configurations')) {
        $this->info('La tabla de configuraciones no está disponible. Abortando el comando.');
        return;
    }

    // Obtener la hora de reporte configurada
    $reportTime = Configuration::where('key', 'report_time')->value('value');

    if (!$reportTime) {
        $this->info('La hora de reporte no está configurada. Abortando el comando.');
        return;
    }

    // Verificar si la hora actual coincide con la hora configurada
    $currentHour = Carbon::now()->format('H:i');
    if ($currentHour !== $reportTime) {
        $this->info('No es la hora configurada para enviar el informe.');
        return;
    }

    // Aquí continúa la lógica para enviar el informe diario
    $this->info('Enviando informe diario...');
});

Schedule::command('send:daily-cleaning-report')->everyMinute();
