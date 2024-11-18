<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyCleaningReport extends Mailable
{
    use Queueable, SerializesModels;

    protected $pdfPath;

    public function __construct($pdfPath)
    {
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->view('emails.daily_cleaning_report')
                    ->subject('Informe Diario de Registros de Limpieza')
                    ->attach($this->pdfPath, [
                        'as' => 'informe_diario_registros_limpieza.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}