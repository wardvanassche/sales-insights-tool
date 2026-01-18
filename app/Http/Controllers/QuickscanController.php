<?php

namespace App\Http\Controllers;

use App\Services\CompanyInfo\CompanyInfoClient;
use App\Services\Llm\Ollama;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuickscanController extends Controller
{
    public function __invoke(
        Request           $request,
        Ollama            $llm,
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

        // LLM analyse
        $analysis = $llm->analyze($companyProfile);

        // Response to UI
        return Inertia::render('Quickscan', [
            'analysis' => $analysis,
        ]);
    }
}
