<?php

namespace App\Services\Llm;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenAI
{
    private const API_URL = 'https://api.openai.com/v1/chat/completions';
    private const MODEL = 'gpt-4o-mini';

    /**
     * Analyse company based on API data using OpenAI GPT-4o mini
     */
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

    /**
     * Prompt engineering - same as Ollama for consistency
     */
    private function buildPrompt(string $companyJson): string
    {
        return <<<PROMPT

Geef een samenvatting van de bedrijfsdata die je gekregen hebt.

Company data (JSON):
{$companyJson}

PROMPT;
    }
}

