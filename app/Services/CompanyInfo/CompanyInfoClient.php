<?php

namespace App\Services\CompanyInfo;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class CompanyInfoClient
{
    private string $endpoint;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->endpoint = config('services.companyinfo.endpoint');
        $this->username = config('services.companyinfo.username');
        $this->password = config('services.companyinfo.password');
    }

    public function getCompanyProfile(
        ?string $companyName = null,
        ?string $companyUrl = null,
    ): ?array
    {
        // Search company
        $results = $this->search($companyName, $companyUrl);

        // Get main establishment
        $company = $this->findMainEstablishment($results);

        if (!$company) {
            throw new RuntimeException('No business found');
        }

        // Get dossier
        $dossier = $this->getDossierV3(
            $company['dossier_number'],
            $company['establishment_number']
        );

        // Merge search + dossier
        return array_merge(
            $company,
            [
                'dossier' => $dossier,
            ]
        );
    }

    // Search with method dutchBusinessSearch
    private function search(string $companyName = null, string $companyUrl = null): array
    {
        $params = [
            '',                     // dossier_number
            $companyName ?? '',     // trade_name
            '',                     // city
            '',                     // street
            '',                     // postcode
            '',                     // house_number
            '',                     // house_number_addition
            '',                     // telephone_number
            $companyUrl ?? '',      // domain_name (as-is)
            'false',                // strict_search
            '1',                    // page
        ];

        return $this->call('dutchBusinessSearch', $params);
    }

    // Call api
    private function call(string $method, array $params): array
    {
        $url = $this->buildUrl(
            $this->endpoint,
            $method,
            array_merge([$this->username, $this->password], $params)
        );

        $response = Http::timeout(15)->get($url);

        if ($response->failed()) {
            throw new RuntimeException('CompanyInfo request failed');
        }

        if ($errorCode = $response->header('X-WS-ErrorCode')) {
            throw new RuntimeException("CompanyInfo error: $errorCode");
        }

        // Test CompanyInfo client > log raw XML
        logger()->debug('CompanyInfo RAW XML', [
            'method' => $method,
            'url' => $url,
            'response' => $response->body(),
        ]);

        return match ($method) {
            'dutchBusinessSearch' =>
            $this->parseDutchBusinessSearch($response->body()),

            'dutchBusinessGetDossierV3' =>
            $this->parseDutchBusinessGetDossierV3($response->body()),

            default =>
            throw new RuntimeException("No parser for method {$method}"),
        };
    }

    // Build url
    private function buildUrl(string $endpoint, string $method, array $segments): string
    {
        $encoded = array_map(
            fn($segment) => rawurlencode((string)$segment),
            $segments
        );

        return rtrim($endpoint, '/') . '/' . $method . '/' . implode('/', $encoded);
    }

    // Parse xml results
    private function parseDutchBusinessSearch(string $xmlString): array
    {
        $xml = new \SimpleXMLElement($xmlString);

        if (!isset($xml->results->entry)) {
            return [];
        }

        $results = [];

        foreach ($xml->results->entry as $entry) {
            $results[] = [
                'dossier_number' => (string)($entry->dossier_number ?? ''),
                'establishment_number' => (string)($entry->establishment_number ?? ''),

                'legal_name' => (string)$entry->legal_name,
                'trade_name' => (string)$entry->trade_name,
                'match_type' => (string)$entry->match_type,

                'establishment_city' => (string)$entry->establishment_city,
                'establishment_street' => (string)$entry->establishment_street,

                'correspondence_city' => (string)$entry->correspondence_city,
                'correspondence_street' => (string)$entry->correspondence_street,

                'is_main_establishment' => (bool)((int)$entry->indication_main_establishment === 1),
            ];
        }

        return $results;
    }

    // Filter on main establishment
    public function findMainEstablishment(array $results): ?array
    {
        if (empty($results)) {
            return null;
        }

        // Filter on main establishment
        $main = collect($results)
            ->firstWhere('is_main_establishment', true);

        // Fallback: first entry
        return $main ?? $results[0];
    }

    // GetDossierV3 method
    private function getDossierV3(
        string $dossierNumber,
        string $establishmentNumber
    ): array
    {
        $params = [
            $dossierNumber,
            $establishmentNumber,
        ];

        return $this->call('dutchBusinessGetDossierV3', $params);
    }

    private function parseDutchBusinessGetDossierV3(string $xmlString): array
    {
        $xml = new \SimpleXMLElement($xmlString);

        if (!isset($xml->result)) {
            return [];
        }

        $result = $xml->result;

        return [
            'dossier_number' => (string) ($xml->dossier_number ?? ''),
            'establishment_number' => (string) ($xml->establishment_number ?? ''),
            'legal_name' => (string) ($xml->legal_name ?? ''),
            'trade_name_full' => (string) ($xml->trade_name_full ?? ''),
            'domain_name' => (string) ($xml->domain_name ?? ''),

            'legal_form_text' => (string) ($xml->legal_form_text ?? ''),
            'status' => (string) ($xml->status ?? ''),

            'foundation_date' => (string) ($result->foundation_date ?? ''),

            'personnel' => [
                'personnel_ci' => (int) ($xml->personnel_ci ?? 0),
                'reference_year' => (string) ($xml->personnel_reference_date->year ?? ''),
            ],

            // SBI / Branche
            'sbi_codes' => $this->parseSbiCodes($result->sbi_codes ?? null),
        ];
    }

    private function parseSbiCodes($sbiNode): array
    {
        if (!$sbiNode || !isset($sbiNode->sbi)) {
            return [];
        }

        $codes = [];

        foreach ($sbiNode->sbi as $sbi) {
            $codes[] = [
                'code'        => (string) $sbi->code,
                'description' => (string) $sbi->description,
            ];
        }

        return $codes;
    }
}
