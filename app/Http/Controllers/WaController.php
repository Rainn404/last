<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WaController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'message' => 'required',
        ]);

        $response = Http::post('http://localhost:5000/send', [
            'number' => $request->number,
            'message' => $request->message
        ]);

        return $response->json();
    }
}
