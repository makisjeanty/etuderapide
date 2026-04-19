<?php

namespace App\Http\Controllers;

use App\Mail\AuditDiagnosticMail;
use App\Models\Lead;
use App\Services\AiPipelineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class AiAnalysisController extends Controller
{
    protected AiPipelineService $pipeline;

    public function __construct(AiPipelineService $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * Handle the incoming request to analyze text via AI.
     */
    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|max:50000',
            'command' => 'nullable|string',
            'email' => 'nullable|email',
            'name' => 'nullable|string',
        ]);

        $command = $request->input('command', 'analyze');

        // Se for uma auditoria de negócio, capturamos o lead primeiro
        if ($command === 'business_audit' && $request->filled('email')) {
            Lead::updateOrCreate(
                ['email' => $request->input('email')],
                [
                    'name' => $request->input('name', 'Visitante Auditoria'),
                    'message' => 'Solicitou auditoria de IA para: '.$request->input('text'),
                    'service_interest' => 'Auditoria IA',
                    'status' => 'new',
                ]
            );
        }

        $result = $this->pipeline->analyze([
            'text' => $request->input('text'),
            'command' => $command,
        ]);

        if ($result) {
            if (isset($result['error'])) {
                return response()->json(['error' => $result['error']], 400);
            }
            if ($command === 'business_audit' && $request->filled('email')) {
                $lead = Lead::where('email', $request->input('email'))->latest()->first();
                if ($lead) {
                    $result['report_url'] = URL::temporarySignedRoute(
                        'audit.report.download',
                        now()->addDays(7),
                        ['lead' => $lead->id]
                    );

                    // Enviar e-mail com o PDF em anexo
                    try {
                        Mail::to($lead->email)->send(new AuditDiagnosticMail($lead, $result));
                    } catch (\Exception $e) {
                        \Log::error('Erro ao enviar e-mail de diagnóstico: '.$e->getMessage());
                    }
                }
            }

            return response()->json($result);
        }

        return response()->json(['error' => 'Serviço de IA Indisponível no momento.'], 503);
    }
}
