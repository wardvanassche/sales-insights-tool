<?php

namespace App\Services\Llm;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class Ollama
{
    // Analyse company based on API data
    public function analyze(array $companyProfile): string
    {
        if (empty($companyProfile)) {
            throw new RuntimeException("No company data provided to LLM");
        }

        $companyJson = json_encode(
            $companyProfile,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        $prompt = $this->buildPrompt($companyJson);

        $response = Http::timeout(60)
            ->post(config('services.ollama.base_url'), [
            'model'  => config('services.ollama.model'),
            'prompt' => $prompt,
            'stream' => false,
        ]);

        if ($response->failed()) {
            throw new RuntimeException('Ollama request failed');
        }

        // Log response from Ollama in laravel.log for debugging
        logger()->debug('Ollama raw response', [
            'body' => $response->json(),
        ]);

        return $response->json('response') ?? '';
    }

    // Prompt engineering
    private function buildPrompt(string $companyJson): string
    {
        return <<<PROMPT

You are an experienced B2B sales consultant.

You are given structured company data from official business registries
(Chamber of Commerce, establishment data, legal information).

The data may be incomplete or not contain financial figures such as revenue.
Do NOT invent facts that are not present in the data.

Your task:
1. Summarize the company in 2–3 sentences
2. Give technical data if provided such as revenue and number of employees.
3. Identify potential commercial opportunities
4. Identify potential pain points or risks
5. Suggest concrete sales angles or questions to explore

Company data (JSON):
{$companyJson}

You may analyze and reason internally in English to ensure high-quality insights.
However, your FINAL ANSWER must be written entirely in Dutch.

Respond clearly and practically, aimed at a B2B sales professional.

PROMPT;
    }
}
