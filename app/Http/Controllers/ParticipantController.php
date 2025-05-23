<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Person;
use App\Models\PersonUser;
use App\Models\Contact;
use Illuminate\Support\Facades\Log as LogFacade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    public function store(Request $request)
    {
        // Força resposta JSON
        if (!$request->expectsJson()) {
            $request->headers->set('Accept', 'application/json');
        }

        $request->merge([
            'cpf' => preg_replace('/\D/', '', $request->cpf),
            'whatsapp' => preg_replace('/\D/', '', $request->whatsapp),
            'address' => array_merge($request->address, [
                'cep' => preg_replace('/\D/', '', $request->address['cep']),
            ])
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'cpf' => 'required|unique:person,cpf',
            'id_gender' => 'required|integer|exists:gender,id',
            'id_group' => 'required|integer|exists:group,id',
            'email' => 'required|email|unique:person_user,email',
            'password' => 'required|string|min:6',
            
            'whatsapp' => 'required|string',

            'address.cep' => 'required|string',
            'address.logradouro' => 'required|string',
            'address.numero' => 'required|string',
            'address.bairro' => 'required|string',
            'address.localidade' => 'required|string',
            'address.uf' => 'required|string|size:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed.',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $person = Person::create([
                'id_credential' => session('id_credential'),
                'name' => ucfirst($request->name),
                'cpf' => $request->cpf,
                'id_gender' => $request->id_gender,
                'id_group' => $request->id_group,
                'id_church' => $request->id_church,
                'birthdate' => $request->birthdate,
                'eklesia' => $request->eklesia,
                'active' => 1
            ]);

            $personUser = PersonUser::create([
                'id_credential' => session('id_credential'),
                'id_person' => $person->id,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'active' => 1
            ]);

            $verificationCode = rand(100000, 999999);

            $personUser->verification_code = $verificationCode;
            $personUser->email_verified = 0;
            $personUser->save();

            Mail::send('emails.verify_email_code', [
                'userName' => $request->name,
                'token' => $verificationCode,
            ], function ($message) use ($request) {
                $message->to($request->email)->subject('Código de Verificação de E-mail');
            });
            

            Contact::create([
                'id_credential' => session('id_credential'),
                'id_parent' => $person->id,
                'route' => 'person',
                'id_type_contact' => 1,
                'value' => $request->whatsapp,
                'active' => 1
            ]);

            Address::create([
                'id_credential' => session('id_credential'),
                'id_parent' => $person->id,
                'route' => 'person',
                'cep' => $request->address['cep'],
                'logradouro' => $request->address['logradouro'],
                'numero' => $request->address['numero'],
                'complemento' => $request->address['complemento'] ?? '',
                'bairro' => $request->address['bairro'],
                'localidade' => $request->address['localidade'],
                'uf' => $request->address['uf'],
                'active' => 1
            ]);

            DB::commit();

            LogFacade::info('Participant salvo com ID: ' . $person->id);

            // return response()->json(['message' => 'Participant created successfully.'], 201);

            return response()->json([
                'redirect' => url('/verify_email_code?email=' . $request->email)
            ], 201);
            

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create participant.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
