<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\PersonUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PersonUserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $ids = $request->query('id', null);
            $idCredential = $request->query('id_credential', null);
            $idPerson = $request->query('id_person', null);
            $email = $request->query('email', null);
            $emailVerified = $request->query('email_verified', null);
            $active = $request->query('active', null);

            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');

            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            // Criar a query base
            $query = PersonUser::orderBy($sortBy, $sortOrder);

            $appliedFilters = [
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
                'per_page' => $perPage,
                'page' => $request->query('page', 1),
            ];

            if (!is_null($ids)) {
                $idArray = explode(',', $ids);
                $query->whereIn('id', $idArray);
                $appliedFilters['id'] = $idArray;
            }

            if (!is_null($idCredential)) {
                $query->where('id_credential', $idCredential);
                $appliedFilters['id_credential'] = $idCredential;
            }

            if (!is_null($idPerson)) {
                $query->where('id_person', $idPerson);
                $appliedFilters['id_person'] = $idPerson;
            }

            if (!is_null($email)) {
                $query->where('email', 'LIKE', "%{$email}%");
                $appliedFilters['email'] = $email;
            }

            if (!is_null($emailVerified)) {
                if (!in_array($emailVerified, ['0', '1'], true)) {
                    return response()->json([
                        'error' => 'Invalid email_verified parameter',
                        'details' => 'Allowed values: 0 (not verified), 1 (verified)'
                    ], 400);
                }
                $query->where('email_verified', $emailVerified);
                $appliedFilters['email_verified'] = $emailVerified;
            }

            if (!is_null($createdStart)) {
                $query->where('created_at', '>=', $createdStart);
                $appliedFilters['created_at_start'] = $createdStart;
            }
            if (!is_null($createdEnd)) {
                $query->where('created_at', '<=', $createdEnd);
                $appliedFilters['created_at_end'] = $createdEnd;
            }

            if (!is_null($updatedStart)) {
                $query->where('updated_at', '>=', $updatedStart);
                $appliedFilters['updated_at_start'] = $updatedStart;
            }
            if (!is_null($updatedEnd)) {
                $query->where('updated_at', '<=', $updatedEnd);
                $appliedFilters['updated_at_end'] = $updatedEnd;
            }

            if (!is_null($active)) {
                if (!in_array($active, ['0', '1'], true)) {
                    return response()->json([
                        'error' => 'Invalid active parameter',
                        'details' => 'Allowed values: 0 (inactive), 1 (active)'
                    ], 400);
                }
                $query->where('active', $active);
                $appliedFilters['active'] = $active;
            }

            $users = $query->paginate($perPage);

            $idPerson = $request->header('id_person');
            if (!$idPerson) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person in headers'
                ], 401);
            }

            $idCredentialLog = session('id_credential');

            if ($idPerson && $idCredentialLog) {
                LogHelper::store(
                    'viewed',
                    'person_user',
                    null,
                    $appliedFilters,
                    null,
                    $idPerson,
                    $idCredentialLog
                );
            }

            return response()->json([
                'users' => $users,
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',
                        'id_person' => 'Filter by person ID',
                        'email' => 'Filter by email using LIKE',
                        'email_verified' => 'Filter by email verification status (0 = not verified, 1 = verified)',
                        'active' => 'Filter by status (0 = inactive, 1 = active)',
                        'created_at_start' => 'Filter records created from this date (Y-m-d H:i:s)',
                        'created_at_end' => 'Filter records created until this date (Y-m-d H:i:s)',
                        'updated_at_start' => 'Filter records updated from this date (Y-m-d H:i:s)',
                        'updated_at_end' => 'Filter records updated until this date (Y-m-d H:i:s)',
                    ],
                    'sorting' => [
                        'sort_by' => 'Sort by id, email, email_verified, active, created_at, updated_at',
                        'sort_order' => 'Sorting order: asc (ascending) or desc (descending, default)',
                    ],
                    'pagination' => [
                        'per_page' => 'Number of records per page (default: 10)',
                        'page' => 'Specify the page number',
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibir um registro específico.
     */
    public function show(Request $request, $id)
    {
        try {
            $idPerson = $request->header('id_person');
            $idCredential = session('id_credential');

            if (!$idPerson || !$idCredential) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person or invalid session'
                ], 401);
            }

            $personUser = PersonUser::where('id', $id)
                ->where('id_credential', $idCredential)
                ->first();

            if (!$personUser) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'PersonUser not found'
                ], 404);
            }

            // Log da ação
            LogHelper::store(
                'show',
                'person_user',
                $personUser->id,
                null,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json(['person_user' => $personUser], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Criar um novo registro.
     */
    public function store(Request $request)
    {
        try {
            $idPerson = $request->header('id_person');
            if (!$idPerson) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person in headers'
                ], 401);
            }
            
            $idCredential = session('id_credential');
            if (!$idCredential) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Invalid session. Please authenticate again.'
                ], 401);
            }

            $validatedData = $request->validate([
                'id_person' => 'required|exists:person,id|unique:person_user,id_person', // Deve existir na tabela person e ser unico
                'email' => 'required|email|unique:person_user,email', // E-mail único e válido
                'password' => 'required|string|min:6', // Senha com no mínimo 6 caracteres
                'active' => 'sometimes|integer|in:0,1',
            ]);

            // Criptografar a senha antes de salvar
            $validatedData['password'] = bcrypt($validatedData['password']);

            // Gerar código de verificação de 6 dígitos
            $validatedData['verification_code'] = mt_rand(100000, 999999);
            $validatedData['email_verified'] = false;
            $validatedData['id_credential'] = $idCredential;

            $personUser = PersonUser::create($validatedData);

            // Log da ação
            LogHelper::store(
                'created',
                'personUser',
                $personUser->id,
                null,
                $personUser,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'PersonUser created successfully. Please use the verification endpoint to confirm your email.',
                'person_user' => $personUser, // Agora a variável está sendo usada na resposta
            ], 201);

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

    /**
     * Atualizar um registro específico.
     */
    public function update(Request $request, $id)
    {
        try {
            $idPerson = $request->header('id_person');
            if (!$idPerson) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person in headers'
                ], 401);
            }

            $idCredential = session('id_credential');
            if (!$idCredential) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Invalid session. Please authenticate again.'
                ], 401);
            }

            $personUser = PersonUser::find($id);

            if (!$personUser) {
                return response()->json(['error' => 'Not Found', 'details' => 'PersonUser not found'], 404);
            }

            $validatedData = $request->validate([
                'id_person' => "sometimes|exists:person,id|unique:person_user,id_person,{$id}", // Pode ser atualizado, mas deve existir na tabela person, Permite manter o mesmo ID ao atualizar
                'email' => "sometimes|email|unique:person_user,email,{$id}", // Permite manter o mesmo e-mail
                'password' => 'sometimes|string|min:6', // Senha só é alterada se for enviada
                'active' => 'sometimes|integer|in:0,1',
            ]);

            // Se o campo password for enviado, criptografa antes de salvar
            if (isset($validatedData['password'])) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }

            $oldData = $personUser->toArray();
            $personUser->update($validatedData);

            // Log da ação
            LogHelper::store(
                'updated',
                'person_user',
                $personUser->id,
                $oldData,
                $personUser,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'PersonUser updated successfully',
                'person_user' => $personUser,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation error', 'fields' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Excluir um registro específico.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $idPerson = $request->header('id_person');
            if (!$idPerson) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person in headers'
                ], 401);
            }

            $idCredential = session('id_credential');
            if (!$idCredential) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Invalid session. Please authenticate again.'
                ], 401);
            }

            $personUser = PersonUser::find($id);

            if (!$personUser) {
                return response()->json(['error' => 'Not Found', 'details' => 'PersonUser not found'], 404);
            }

            $oldData = $personUser->toArray();
            $personUser->delete();

            // Log da ação
            LogHelper::store(
                'deleted',
                'person_user',
                $id,
                $oldData,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'PersonUser deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $user = PersonUser::where('email', $validated['email'])
                ->where('active', 1)
                ->where('email_verified', 1)
                ->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {

                // Log da tentativa de login falha
                LogHelper::store(
                    'login_failed',
                    'person_user',
                    null,
                    ['email' => $validated['email']],
                    null,
                    null,
                    null
                );

                return response()->json([
                    'error' => 'Incorrect email or password, if the error persists, check if the email has been confirmed.'
                ], 401);
            }

            // Log da tentativa de login bem-sucedida
            LogHelper::store(
                'login_success',
                'person_user',
                $user->id,
                null,
                null,
                $user->id_person,
                $user->id_credential
            );

            return response()->json([
                'id_person' => $user->id_person,
                'id_credential' => $user->id_credential
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


    public function logout(Request $request)
    {
        try {
            $idPerson = $request->header('id_person');
            $idCredential = session('id_credential');

            if (!$idPerson || !$idCredential) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person or invalid session'
                ], 401);
            }

            // Log da ação de logout
            LogHelper::store(
                'logout_success',
                'person_user',
                null,
                null,
                null,
                $idPerson,
                $idCredential
            );

            // Aqui você pode limpar a sessão se for necessário
            session()->flush();

            return response()->json(['message' => 'Logout successful'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage()
            ], 500);
        }
    }





}
