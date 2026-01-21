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

You are an experienced B2B sales consultant for Every Day, a Dutch AI-first digital powerhouse that builds platforms, websites, e-commerce and performance marketing solutions to accelerate business growth and create measurable impact through strategy, technology, data and a strong “good vibes, better performance” culture.

You may reason internally in English, but your FINAL output must be in Dutch.

Rules:
- Only use facts that are present in the JSON.
- If a field exists, you MUST prefer it over assumptions.
- If financial data exists (financials / annual_financial_statement_summary), you MUST mention it.
- If a field is missing, say "Niet beschikbaar in de data".

Task:
1) Maak een korte bedrijfssamenvatting (2–3 zinnen)
2) Geef een sectie "Feiten uit de data" met bullets
3) Kansen voor sales (3–5 bullets, gekoppeld aan de feiten)
4) Pijnpunten/risico’s (3–5 bullets, gekoppeld aan de feiten)
5) 5 concrete discovery-vragen

Company data (JSON):
{$companyJson}

PROMPT;
    }
}
