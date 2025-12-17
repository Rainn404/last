<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::post('/wa/send', function (Request $req) {
    $response = Http::post('http://localhost:5000/send', [
        'number' => $req->number,
        'message' => $req->message
    ]);

    return $response->json();
});
