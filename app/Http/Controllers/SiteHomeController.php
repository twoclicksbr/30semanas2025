<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class SiteHomeController extends Controller
{
    public function index()
    {
        $url = config('api.base_url');

        $username = Config::get('api.username');
        $token = Config::get('api.token');
        $endpoint = "$url/api/v1/celebration/?sort_by=dt_celebration&sort_order=desc&per_page=1";

        $response = Http::withHeaders([
            'username' => $username,
            'token' => $token,
        ])->get($endpoint);

        $featuredVideo = $response->successful()
            ? ($response->json()['celebrations']['data'][0] ?? null)
            : null;
            
        return view('home', compact('featuredVideo'));

    }
}
