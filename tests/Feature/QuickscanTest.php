<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    config([
        'services.companyinfo.endpoint' => 'https://companyinfo.test',
        'services.companyinfo.username' => 'user',
        'services.companyinfo.password' => 'secret',
        'services.openai.api_key' => 'test-key',
    ]);
});

test('guests cannot run a quickscan', function () {
    Http::fake();

    $this->post(route('quickscan.store'), [
        'company_name' => 'Every Day',
        'llm_model' => 'openai',
    ])->assertRedirect(route('login'));

    Http::assertNothingSent();
});

test('a company name or url is required', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('quickscan.store'), ['llm_model' => 'openai'])
        ->assertSessionHasErrors(['company_name', 'company_url']);
});

test('unknown companies show an error instead of failing', function () {
    Http::fake([
        'companyinfo.test/dutchBusinessSearch/*' => Http::response('<response><results></results></response>'),
    ]);

    $this->actingAs(User::factory()->create())
        ->post(route('quickscan.store'), [
            'company_name' => 'Bestaat Niet BV',
            'llm_model' => 'openai',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Quickscan')
            ->where('error', 'Geen bedrijf gevonden met deze input.')
        );
});

test('api failures show an error instead of failing', function () {
    Http::fake([
        'companyinfo.test/*' => Http::response('', 500),
    ]);

    $this->actingAs(User::factory()->create())
        ->post(route('quickscan.store'), [
            'company_name' => 'Every Day',
            'llm_model' => 'openai',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Quickscan')
            ->has('error')
            ->missing('analysis')
        );
});

test('a found company returns an analysis', function () {
    Http::fake([
        'companyinfo.test/dutchBusinessSearch/*' => Http::response(
            '<response><results><entry>'
            .'<dossier_number>12345678</dossier_number>'
            .'<establishment_number>000012345678</establishment_number>'
            .'<legal_name>Every Day B.V.</legal_name>'
            .'<indication_main_establishment>1</indication_main_establishment>'
            .'</entry></results></response>'
        ),
        'companyinfo.test/dutchBusinessGetDossierV3/*' => Http::response(
            '<response><dossier_number>12345678</dossier_number><legal_name>Every Day B.V.</legal_name></response>'
        ),
        'api.openai.com/*' => Http::response([
            'choices' => [['message' => ['content' => '## Bedrijfssamenvatting']]],
        ]),
    ]);

    $this->actingAs(User::factory()->create())
        ->post(route('quickscan.store'), [
            'company_name' => 'Every Day',
            'llm_model' => 'openai',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Quickscan')
            ->where('analysis', '## Bedrijfssamenvatting')
        );
});
