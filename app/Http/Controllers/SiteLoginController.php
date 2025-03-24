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

        // dd($endpoint);

        // Faz a requisição à API
        $response = Http::withHeaders([
            'username' => $username,
            'token' => $token,
        ])->post($endpoint, [
            'email' => $request->email,
            'password' => $request->password,
        ]);
        
        // Verifica se a requisição falhou
        if ($response->failed()) {
            return back()->with('error', 'E-mail de aceso ou Senha Inválido.');
            // return back()->with('error', 'E-mail de aceso ou Senha Inválido.' . $response->body());
        }
        
        $data = $response->json();

        // Verifica se a API retornou o campo id_person
        if (isset($data['id_person'])) {

            $id_person = $data['id_person'];

            // Busca o nome do usuário na API
            $personResponse = Http::withHeaders([
                'username' => $username,
                'token' => $token,
            ])->get("$url/api/v1/person/{$id_person}");

            if ($personResponse->successful()) {
                $personData = $personResponse->json();
                $fullName = $personData['person']['name'] ?? '';

                // Salva na sessão
                Session::put('auth_id_person', $id_person);
                Session::put('auth_name', $fullName);
            }

            // Buscar restrições do usuário
            $restrictionsResponse = Http::withHeaders([
                'username' => $username,
                'token' => $token,
            ])->get("$url/api/v1/person_restriction", [
                'id_person' => $id_person
            ]);            

            // dd($restrictionsResponse);

            if ($restrictionsResponse->successful()) {
                $restrictionsData = $restrictionsResponse->json();

                // dd($restrictionsData);
                
                $restrictions = $restrictionsData['restrictions']['data'] ?? []; // Pegamos os registros corretos

                // dd($restrictions);
            
                foreach ($restrictions as $restriction) {
                    $idTypeUser = $restriction['id_type_user'];

                    // dd($idTypeUser);
            
                    // Buscar o nome do tipo de pessoa
                    $typePersonResponse = Http::withHeaders([
                        'username' => $username,
                        'token' => $token,
                    ])->get("$url/api/v1/type_user/{$idTypeUser}");

                    // dd($typePersonResponse);
            
                    if ($typePersonResponse->successful()) {
                        $typePersonData = $typePersonResponse->json();

                        // dd($typePersonData);
            
                        // Agora acessamos 'result.name' corretamente
                        if (isset($typePersonData['type_user']['name'])) {
                            $sessionName = 'auth_' . strtolower(str_replace(
                                [' ', 'á', 'é', 'í', 'ó', 'ú', 'ã', 'õ', 'ç'], 
                                ['_', 'a', 'e', 'i', 'o', 'u', 'a', 'o', 'c'], 
                                $typePersonData['type_user']['name']));
            
                            // Criar sessão dinâmica com "auth_" antes do nome
                            Session::put($sessionName, true);

                            // dd($sessionName);

                        } else {
                            dd('Erro: A API type_person não retornou um nome válido.', $typePersonData);
                        }
                    } else {
                        dd('Erro: Falha ao buscar type_person.', $typePersonResponse->body());
                    }
                }
            }
            
            return redirect()->route('home')->with('success', 'Login realizado com sucesso!');

        } else {
            return back()->with('error', 'Erro na API: Resposta inesperada: ' . json_encode($data));
        }
    }
    
}
