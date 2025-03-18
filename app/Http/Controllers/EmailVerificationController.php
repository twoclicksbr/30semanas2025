<?php

namespace App\Http\Controllers;

use App\Models\PersonUser; // Importa o model PersonUser
use Illuminate\Http\Request;
use App\Models\Person;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;

class EmailVerificationController extends Controller
{
    /**
     * Verificar o código de e-mail enviado para o usuário.
     */
    public function verify(Request $request)
    {
        try {
            // 1️⃣ Validação dos dados recebidos na requisição
            $validatedData = $request->validate([
                'email' => 'required|email|exists:person_user,email', // O e-mail é obrigatório, deve ser válido e existir na tabela
                'verification_code' => 'required|digits:6' // O código deve ter exatamente 6 dígitos
            ]);

            // 2️⃣ Buscar o usuário pelo e-mail e código de verificação
            $personUser = PersonUser::where('email', $validatedData['email'])
                ->where('verification_code', $validatedData['verification_code'])
                ->first();

            // 3️⃣ Se não encontrar, retorna erro informando código inválido
            if (!$personUser) {
                return response()->json([
                    'error' => 'Invalid verification code or email' // Código inválido ou e-mail não cadastrado
                ], 400);
            }

            // 4️⃣ Atualiza o registro para marcar o e-mail como verificado
            $personUser->update([
                'email_verified' => true, // Define como verificado
                'verification_code' => null // Apaga o código de verificação para evitar reutilização
            ]);

            // 5️⃣ Retorna uma resposta de sucesso
            return response()->json([
                'message' => 'Email verified successfully', // Mensagem de sucesso
                'email_verified' => true // Indica que o e-mail agora está verificado
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // 6️⃣ Retorna erro de validação se os dados estiverem incorretos
            return response()->json([
                'error' => 'Validation error',
                'fields' => $e->errors() // Retorna os erros específicos dos campos inválidos
            ], 422);
        } catch (\Exception $e) {
            // 7️⃣ Captura erros inesperados e retorna erro 500
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function resendVerificationCode(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|exists:person_user,email',
            ]);

            // Busca o usuário pelo e-mail
            $personUser = PersonUser::where('email', $validatedData['email'])->first();

            if (!$personUser) {
                return response()->json(['error' => 'Email not found'], 404);
            }

            // Busca o nome da pessoa na tabela `person`
            $person = Person::find($personUser->id_person);

            if (!$person) {
                return response()->json(['error' => 'Person not found'], 404);
            }

            // Gera um novo código de verificação (6 dígitos)
            $newVerificationCode = mt_rand(100000, 999999);
            $personUser->update(['verification_code' => $newVerificationCode]);

            // Envia o e-mail com o nome correto
            Mail::to($personUser->email)->send(new EmailVerificationMail($person->name, $newVerificationCode));

            return response()->json([
                'message' => 'Verification code sent successfully. Please check your email.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation error',
                'fields' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage()
            ], 500);
        }
    }

}
