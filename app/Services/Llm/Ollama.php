<?php

namespace App\Services\Llm;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class Ollama
{
    // Analyse company based on API data
    public function analyze(array $company): string
    {
        if (empty($company)) {
            throw new RuntimeException("No company data provided to LLM");
        }

        $prompt = $this->buildPrompt($company);

        $response = Http::timeout(60)
            ->post(config('services.ollama.base_url'), [
            'model'  => config('services.ollama.model'),
            'prompt' => $prompt,
            'stream' => false,
        ]);

        if ($response->failed()) {
            throw new RuntimeException('Ollama request failed');
        }

        return $response->json('response') ?? '';
    }

    // Prompt engineering
    private function buildPrompt(array $company): string
    {
        $context = collect($company)
            ->map(fn ($value, $key) => strtoupper($key) . ': ' . $value)
            ->implode("\n");

        return <<<PROMPT

You are an experienced B2B sales consultant.

Analyze the company described below and provide:

1. What is the name of the company?
2. What is the dossier number of the company?
3. What is the establishment number of this company?
4. Is this a main establishment or not?
5. In which city is the company located?
6. What type of company is it?
7. In which industry does the company operate?

Company data:
{$context}

Respond concisely and practically. Focus on insights that are directly useful for a B2B sales professional.

PROMPT;
    }
}
