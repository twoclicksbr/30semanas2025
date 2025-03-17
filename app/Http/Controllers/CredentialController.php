<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credential;
use Illuminate\Support\Str;

class CredentialController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Capturar parâmetros opcionais
            $ids = $request->query('id', null);
            $username = $request->query('username', null);
            $active = $request->query('active', null);

            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');

            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            // Validar o campo active (deve ser 0 ou 1)
            if (!is_null($active) && !in_array($active, ['0', '1'], true)) {
                return response()->json([
                    'error' => 'Invalid active parameter',
                    'details' => 'Allowed values: 0 (inactive), 1 (active)'
                ], 400);
            }

            // Criar a query base
            $query = Credential::orderBy($sortBy, $sortOrder);

            // Array para armazenar filtros aplicados
            $appliedFilters = [
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
                'per_page' => $perPage,
                'page' => $request->query('page', 1),
            ];

            // Aplicar filtros
            if (!is_null($ids)) {
                $idArray = explode(',', $ids);
                $query->whereIn('id', $idArray);
                $appliedFilters['id'] = $idArray;
            }

            if (!is_null($active)) {
                $query->where('active', $active);
                $appliedFilters['active'] = $active;
            }

            if (!is_null($username)) {
                $query->where('username', 'LIKE', "%{$username}%");
                $appliedFilters['username'] = $username;
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

            // Paginar os resultados
            $credentials = $query->paginate($perPage);

            // Incluir opções disponíveis no retorno
            return response()->json([
                'credentials' => $credentials,
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',
                        'username' => 'Filter by username using LIKE',
                        'active' => 'Filter by status (0 = inactive, 1 = active)',
                        'created_at_start' => 'Filter records created from this date (Y-m-d H:i:s)',
                        'created_at_end' => 'Filter records created until this date (Y-m-d H:i:s)',
                        'updated_at_start' => 'Filter records updated from this date (Y-m-d H:i:s)',
                        'updated_at_end' => 'Filter records updated until this date (Y-m-d H:i:s)',
                    ],
                    'sorting' => [
                        'sort_by' => 'Sort by id, username, active, created_at, updated_at',
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
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            // Buscar a credencial específica pelo ID
            $requestedCredential = Credential::find($id);

            if (!$requestedCredential) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Credential not found'
                ], 404);
            }

            return response()->json([
                'credential' => $requestedCredential,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validar os dados enviados
            $validatedData = $request->validate([
                'username' => 'required|string|unique:credential',
                'active' => 'sometimes|integer|in:0,1'
            ]);

            // Gerar um novo token automaticamente
            $validatedData['token'] = Str::random(60);

            // Criar a credencial
            $newCredential = Credential::create($validatedData);

            return response()->json([
                'message' => 'Credential created successfully',
                'credential' => $newCredential,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation error',
                'fields' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Buscar a credencial específica pelo ID
            $requestedCredential = Credential::find($id);

            if (!$requestedCredential) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Credential not found'
                ], 404);
            }

            // Definir mensagens personalizadas para a validação
            $messages = [
                'active.in' => 'The active field must be 1 (active) or 0 (inactive).'
            ];

            // Validar os dados que podem ser atualizados
            $validatedData = $request->validate([
                'username' => 'sometimes|string|unique:credential,username,' . $id,
                'active' => 'sometimes|integer|in:0,1'
            ], $messages);

            // Se o usuário quiser um novo token, geramos um novo
            if ($request->has('generate_new_token') && $request->generate_new_token) {
                $validatedData['token'] = Str::random(60); // Gera um novo token
            }

            // Atualizar a credencial
            $requestedCredential->update($validatedData);

            return response()->json([
                'credential' => $requestedCredential,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation error',
                'fields' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            // Impedir a exclusão do ID 1
            if ($id == 1) {
                return response()->json([
                    'error' => 'Forbidden',
                    'details' => 'The credential with ID 1 cannot be deleted'
                ], 403);
            }

            // Buscar a credencial específica pelo ID
            $requestedCredential = Credential::find($id);

            if (!$requestedCredential) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Credential not found'
                ], 404);
            }

            // Excluir do banco
            $requestedCredential->delete();

            return response()->json([
                'message' => 'Credential deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
