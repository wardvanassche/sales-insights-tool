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

IMPORTANT: Format your response using clear Markdown with:
- Headers for sections (use ## for main sections)
- Bullet points for lists (use - for bullets, not numbers unless specified)
- Empty lines between sections
- Bold text using **text** for emphasis

Rules:
- Only use facts that are present in the JSON.
- If a field exists, you MUST prefer it over assumptions.
- If financial data exists (financials / annual_financial_statement_summary), you MUST mention it.
- If a field is missing, say "Niet beschikbaar in de data".

Task:
Format your response exactly as follows:

## Bedrijfssamenvatting
[2-3 sentences here]

## Feiten uit de data
- [fact 1]
- [fact 2]
- [fact 3]

## Kansen voor sales
- [opportunity 1]
- [opportunity 2]
- [opportunity 3]

## Pijnpunten & Risico's
- [risk 1]
- [risk 2]
- [risk 3]

## Discovery-vragen
1. [question 1]
2. [question 2]
3. [question 3]
4. [question 4]
5. [question 5]

Company data (JSON):
{$companyJson}

PROMPT;
    }
}
