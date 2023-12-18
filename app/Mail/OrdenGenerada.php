<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrdenGenerada extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfData;

    /**
     * Create a new message instance.
     *
     * @param mixed $pdfData Contenido del PDF generado
     */
    public function __construct($pdfData, $noOrden)
    {
        $this->pdfData = $pdfData;
        $this->noOrden = $noOrden;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('diegoalexisayala27@gmail.com')
            ->subject('Orden Generada')
            ->view('emails.ordenGenerada') // Aquí se indica la vista a utilizar
            ->attachData($this->pdfData, $this->pdfFileName(), [
                'mime' => 'application/pdf',
            ]);
    }

    public function pdfFileName()
    {
        return $this->noOrden . '.pdf';
    }
    
}
