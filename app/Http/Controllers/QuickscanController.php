<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuickscanController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['required', 'string'],
            'company_url'  => ['required', 'url'],
        ]);

        // 🔍 tijdelijke check
        // logger()->info('Quickscan input', $data);

        return response()->json([
            'status' => 'ok',
            'input' => $data,
        ]);
    }
}
