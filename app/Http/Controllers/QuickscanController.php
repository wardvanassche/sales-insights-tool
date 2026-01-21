<?php

namespace App\Http\Controllers;

use App\Services\CompanyInfo\CompanyInfoClient;
use App\Services\Llm\Ollama;
use App\Services\Llm\OpenAI;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuickscanController extends Controller
{
    public function __invoke(
        Request           $request,
        CompanyInfoClient $companyInfo,
    )
    {
        // Input validatie
        $data = $request->validate([
            'company_name' => [
        'nullable',
        'string',
        'required_without:company_url'
    ],
            'company_url' => [
        'nullable',
        'url',
        'required_without:company_name'
    ],
            'llm_model' => [
        'required',
        'string',
        'in:openai,ollama'
    ],
        ]);

        // Search (CompanyInfo)
        $companyProfile = $companyInfo->getCompanyProfile(
            $data['company_name'] ?? null,
            $data['company_url'] ?? null,
        );

        // Return error when no result is found
        if (!$companyProfile) {
            return Inertia::render('Quickscan', [
                'error' => 'Geen bedrijf gevonden met deze input.',
            ]);
        }

        // Select the appropriate LLM based on user choice
        $llm = match ($data['llm_model']) {
            'openai' => app(OpenAI::class),
            'ollama' => app(Ollama::class),
            default => app(OpenAI::class),
        };

        // LLM analyse
        $analysis = $llm->analyze($companyProfile);

        // Response to UI
        return Inertia::render('Quickscan', [
            'analysis' => $analysis,
        ]);
    }
}
