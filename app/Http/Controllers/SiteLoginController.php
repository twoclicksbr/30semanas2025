<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class SiteLoginController extends Controller
{

    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $url = config('api.base_url');
        $username = Config::get('api.username');
        $token = Config::get('api.token');
        $endpoint = "$url/api/v1/person_user/login";

        $response = Http::withHeaders([
            'username' => $username,
            'token' => $token,
        ])->post($endpoint, [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->failed()) {
            return back()->with('error', 'E-mail de acesso ou Senha inválido.');
        }

        $data = $response->json();

        if (!isset($data['id_person'], $data['id_credential'])) {
            return back()->with('error', 'Erro na API: resposta incompleta.');
        }

        $id_person = $data['id_person'];
        $id_credential = $data['id_credential'];

        // Armazenar na sessão
        Session::put('auth_id_person', $id_person);
        Session::put('auth_id_credential', $id_credential);
        Session::put('id_credential', $id_credential); // necessário para a API

        // Buscar nome do usuário
        $personResponse = Http::withHeaders([
            'username' => $username,
            'token' => $token,
            'id-person' => $id_person,
            'id-credential' => $id_credential,
        ])->get("$url/api/v1/person/{$id_person}");

        // dd($personResponse);

        if ($personResponse->successful()) {
            $personData = $personResponse->json();

            // dd($personData);

            $fullName = $personData['person']['name'] ?? '';
            Session::put('auth_name', $fullName);
        }

        // Buscar restrições do usuário
        $restrictionsResponse = Http::withHeaders([
            'username' => $username,
            'token' => $token,
            'id-person' => $id_person,
            'id-credential' => $id_credential,
        ])->get("$url/api/v1/person_restriction", [
            'id_person' => $id_person
        ]);

        // dd($restrictionsResponse);

        if ($restrictionsResponse->successful()) {
            $restrictionsData = $restrictionsResponse->json();
            $restrictions = $restrictionsData['restrictions']['data'] ?? [];

            foreach ($restrictions as $restriction) {
                $idTypeUser = $restriction['id_type_user'];

                // dd([
                //     'username' => $username,
                //     'token' => $token,
                //     'id_person' => $id_person,
                //     'id_credential' => $id_credential,
                // ]);

                $typeResponse = Http::withHeaders([
                    'username' => $username,
                    'token' => $token,
                    'id-person' => $id_person,
                    'id-credential' => $id_credential,
                ])->get("$url/api/v1/type_user/{$idTypeUser}");

                // dd($typeResponse);

                if ($typeResponse->successful()) {
                    $typeData = $typeResponse->json();
                    $typeName = $typeData['type_user']['name'] ?? null;

                    if ($typeName) {
                        $sessionName = 'auth_' . strtolower(str_replace(
                            [' ', 'á', 'é', 'í', 'ó', 'ú', 'ã', 'õ', 'ç'],
                            ['_', 'a', 'e', 'i', 'o', 'u', 'a', 'o', 'c'],
                            $typeName
                        ));

                        Session::put($sessionName, true);
                    }
                }
            }
        }

        // dd(session()->all());

        return redirect()->route('home')->with('success', 'Login realizado com sucesso!');
    }

    
}
