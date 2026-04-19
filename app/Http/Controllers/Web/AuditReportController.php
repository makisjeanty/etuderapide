<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AuditReportController extends Controller
{
    /**
     * Gera e faz o download do relatório em PDF para um lead específico.
     */
    public function download(Request $request, Lead $lead)
    {
        // Mock de dados para o relatório (em produção viriam da análise salva)
        $data = [
            'lead_name' => $lead->name,
            'analysis_text' => $lead->message ?? 'Análise estratégica pendente de processamento detalhado.',
            'score' => rand(75, 95),
            'recommendations' => [
                'Implementar automação de atendimento via WhatsApp.',
                'Otimizar funil de conversão para tráfego pago.',
                'Integrar sistema de IA para triagem de leads.',
            ],
        ];

        $pdf = Pdf::loadView('pdf.audit-report', $data);

        return $pdf->download("Diagnostico_Makis_Digital_{$lead->id}.pdf");
    }
}
