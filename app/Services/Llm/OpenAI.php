<?php

namespace App\Services\Llm;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenAI
{
    private const API_URL = 'https://api.openai.com/v1/chat/completions';
    private const MODEL = 'gpt-4o-mini';

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
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type' => 'application/json',
            ])
            ->post(self::API_URL, [
                'model' => self::MODEL,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);

        if ($response->failed()) {
            logger()->error('OpenAI request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new RuntimeException('OpenAI request failed: ' . $response->body());
        }

        // Log response from OpenAI in laravel.log for debugging
        logger()->debug('OpenAI response', [
            'model' => self::MODEL,
            'usage' => $response->json('usage'),
        ]);

        $content = $response->json('choices.0.message.content');

        if (empty($content)) {
            throw new RuntimeException('No content returned from OpenAI');
        }

        return $content;
    }

    private function buildPrompt(string $companyJson): string
    {
        return <<<PROMPT

You are an experienced B2B sales consultant for a creative digital agency.

You may reason internally in English, but your FINAL output must be in Dutch.

Rules:
- Only use facts that are present in the JSON.
- If a field exists, you MUST prefer it over assumptions.
- If financial data exists (financials / annual_financial_statement_summary), you MUST mention it.
- If a field is missing, say "Niet beschikbaar in de data".

Task:
1) Maak een korte bedrijfssamenvatting (2–3 zinnen)
2) Geef een sectie "Feiten uit de data" met bulletpoints
3) Kansen voor sales (3–5 bulletpoints, gekoppeld aan de feiten)
4) Pijnpunten/risico’s (3–5 bulletpoints, gekoppeld aan de feiten)
5) 5 concrete discovery-vragen

Company data (JSON):
{$companyJson}

PROMPT;
    }
}

