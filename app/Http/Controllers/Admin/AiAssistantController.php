<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AiPipelineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function generate(Request $request, AiPipelineService $pipelineService): JsonResponse
    {
        $validated = $request->validate([
            'command' => ['required', 'string', 'in:seo_title,seo_description,summary,description,cta'],
            'context' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $result = $pipelineService->analyze([
                'command' => $validated['command'],
                'text' => $validated['context'],
            ]);

            if (! $result || isset($result['error'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => $result['error'] ?? 'Falha ao processar a solicitação na pipeline de IA.',
                ], 502);
            }

            return response()->json([
                'status' => 'success',
                'result' => $result['analysis'] ?? 'Falha ao processar.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
