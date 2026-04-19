<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiPipelineService
{
    /**
     * Envia dados para análise na pipeline de IA.
     *
     * @param  array  $data  Dados a serem processados pelo Python.
     * @return array|null Retorna o JSON processado ou null em caso de falha.
     */
    public function analyze(array $data): ?array
    {
        try {
            $response = Http::acceptJson()
                ->asJson()
                ->connectTimeout(5)
                ->timeout(120)
                ->retry(2, 200, throw: false)
                ->post($this->endpoint('/analyze'), $data);

            if ($response->successful()) {
                $payload = $response->json();

                if (is_array($payload)) {
                    return $payload;
                }

                Log::error('AI Pipeline Invalid JSON Shape', [
                    'body' => $response->body(),
                ]);

                return ['error' => 'Resposta inválida da pipeline de IA.'];
            }

            Log::error('AI Pipeline HTTP Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['error' => 'Pipeline de IA indisponível no momento.'];
        } catch (\Exception $e) {
            Log::error('AI Pipeline Connection Failed', [
                'message' => $e->getMessage(),
            ]);

            return ['error' => 'Falha de conexão com a pipeline de IA.'];
        }
    }

    protected function endpoint(string $path): string
    {
        $baseUrl = rtrim((string) config('services.ai_pipeline.url', 'http://localhost:3001'), '/');

        return $baseUrl.$path;
    }
}
