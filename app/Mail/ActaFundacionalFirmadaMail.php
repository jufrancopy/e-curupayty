<?php

namespace App\Mail;

use App\Models\FirmanteActa;
use App\Services\ActaImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActaFundacionalFirmadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public FirmanteActa $firmante;
    public string $imagePath;
    public ?string $temporaryPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(FirmanteActa $firmante, ?string $temporaryPassword = null)
    {
        $this->firmante = $firmante;
        $this->temporaryPassword = $temporaryPassword;
        
        // Generar o recuperar la imagen de alta resolución del Acta
        $service = new ActaImageService();
        $this->imagePath = $service->generate($firmante);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "📜 Acta Fundacional Firmada — Ensamble Curupayty · Atypu [{$this->firmante->codigo_verificacion}]",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.acta_firmada',
            with: [
                'firmante' => $this->firmante,
                'imagePath' => $this->imagePath,
                'temporaryPassword' => $this->temporaryPassword,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if (file_exists($this->imagePath)) {
            $attachments[] = Attachment::fromPath($this->imagePath)
                ->as("Acta_Fundacional_Curupayty_{$this->firmante->codigo_verificacion}.png")
                ->withMime('image/png');
        }

        return $attachments;
    }
}
