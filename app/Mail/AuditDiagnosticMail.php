<?php

namespace App\Mail;

use App\Models\Lead;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuditDiagnosticMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Lead $lead, public array $analysisData) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Seu Diagnóstico Estratégico IA - Makis Digital',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.leads.audit-diagnostic',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.audit-report', [
            'lead_name' => $this->lead->name,
            'analysis_text' => $this->analysisData['verdict'] ?? 'Análise concluída.',
            'score' => $this->analysisData['score'] ?? 85,
            'recommendations' => $this->analysisData['opportunities'] ?? [],
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Diagnostico_Makis_Digital.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
