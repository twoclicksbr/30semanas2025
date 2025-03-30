<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Listar todos os registros com filtros, paginação e ordenação.
     */
    public function index(Request $request)
    {
        try {
            $ids = $request->query('id', null);
            $idCredential = $request->query('id_credential', null);
            $name = $request->query('name', null);
            
            $cpf = $request->query('cpf', null);
            $id_church = $request->query('id_church', null);
            $birthdate = $request->query('birthdate', null);
            $id_gender = $request->query('id_gender', null);
            $eklesia = $request->query('eklesia', null);

            $active = $request->query('active', null);

            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');

            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            // Criar a query base
            $query = Person::orderBy($sortBy, $sortOrder);

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

            if (!is_null($name)) {
                $query->where('name', 'LIKE', "%{$name}%");
                $appliedFilters['name'] = $name;
            }

            if (!is_null($cpf)) {
                $query->where('cpf', $cpf);
                $appliedFilters['cpf'] = $cpf;
            }
            
            if (!is_null($id_church)) {
                $idChurchArray = explode(',', $id_church); // Converte string em array
                $query->whereIn('id_church', $idChurchArray);
                $appliedFilters['id_church'] = $idChurchArray;
            }
            
            if (!is_null($birthdate)) {
                $query->where('birthdate', $birthdate);
                $appliedFilters['birthdate'] = $birthdate;
            }
            
            if (!is_null($id_gender)) {
                $idGenderhArray = explode(',', $id_gender); // Converte string em array
                $query->whereIn('id_gender', $idGenderhArray);
                $appliedFilters['id_gender'] = $idGenderhArray;
            }
            
            if (!is_null($eklesia)) {
                $eklesiaArray = explode(',', $eklesia); // Converte string em array
                $query->whereIn('eklesia', $eklesiaArray);
                $appliedFilters['eklesia'] = $eklesiaArray;
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

            $persons = $query->paginate($perPage);

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
                    'person',
                    null,
                    $appliedFilters,
                    null,
                    $idPerson,
                    $idCredentialLog
                );
            }

            return response()->json([
                'persons' => $persons,
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',
                        'name' => 'Filter by person name using LIKE',

                        'cpf' => 'Filter by CPF (exact match, format: 999.999.999-99)',
                        'id_church' => 'Filter by church ID, allowing multiple values separated by commas',
                        'birthdate' => 'Filter by birthdate (format: Y-m-d)',
                        'id_gender' => 'Filter by gender ID, allowing multiple values separated by commas',
                        'eklesia' => 'Filter by eklesia number, allowing multiple values separated by commas',

                        'active' => 'Filter by status (0 = inactive, 1 = active)',
                        
                        'created_at_start' => 'Filter records created from this date (Y-m-d H:i:s)',
                        'created_at_end' => 'Filter records created until this date (Y-m-d H:i:s)',
                        'updated_at_start' => 'Filter records updated from this date (Y-m-d H:i:s)',
                        'updated_at_end' => 'Filter records updated until this date (Y-m-d H:i:s)',
                    ],
                    'sorting' => [
                        'sort_by' => 'Sort by id, name, active, created_at, updated_at',
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

            $person = Person::where('id', $id)
                ->where('id_credential', $idCredential)
                ->first();

            if (!$person) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Person not found'
                ], 404);
            }

            // Log da ação
            LogHelper::store(
                'show',
                'person',
                $person->id,
                null,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json(['person' => $person], 200);

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
                'name' => 'required|string',
                'cpf' => 'required|string|unique:person,cpf', // CPF válido e único
                'id_church' => 'required|exists:church,id', // Igreja vinculada deve existir
                'birthdate' => 'nullable|date|date_format:Y-m-d', // Data de nascimento no formato correto
                'id_gender' => 'nullable|exists:gender,id', // Gênero deve existir na tabela gender
                'eklesia' => [
                    'nullable',
                    'regex:/^\d+$/', // Apenas números
                    function ($attribute, $value, $fail) {
                        if ($value !== '0' && \App\Models\Person::where('eklesia', $value)->exists()) {
                            $fail('The eklesia number must be unique.');
                        }
                    },
                ],
                'active' => 'sometimes|integer|in:0,1',
            ]);
            
            $validatedData['cpf'] = preg_replace('/\D/', '', $validatedData['cpf']);

            // Adicionar automaticamente o id_credential autenticado
            $validatedData['id_credential'] = $idCredential;

            $person = Person::create($validatedData);

            // Log da ação
            LogHelper::store(
                'created',
                'person',
                $person->id,
                null,
                $person,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Person created successfully',
                'person' => $person,
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

            $person = Person::find($id);

            if (!$person) {
                return response()->json(['error' => 'Not Found', 'details' => 'Person not found'], 404);
            }

            // Remover pontos e traços do CPF antes de validar
            $request->merge(['cpf' => preg_replace('/\D/', '', $request->cpf)]);

            $validatedData = $request->validate([
                'name' => 'required|string',
                'cpf' => "required|string|size:11|unique:person,cpf,{$id}", // Agora o CPF será armazenado apenas com números
                'id_church' => 'required|exists:church,id',
                'birthdate' => 'nullable|date|date_format:Y-m-d',
                'id_gender' => 'nullable|exists:gender,id',
                'eklesia' => [
                    'nullable',
                    'regex:/^\d+$/',
                    function ($attribute, $value, $fail) use ($id) {
                        if ($value !== '0' && \App\Models\Person::where('eklesia', $value)->where('id', '!=', $id)->exists()) {
                            $fail('The eklesia number must be unique unless it is "0".');
                        }
                    },
                ],
                'active' => 'sometimes|integer|in:0,1',
            ]);

            $oldData = $person->toArray();
            $person->update($validatedData);

            // Log da ação
            LogHelper::store(
                'updated',
                'person',
                $person->id,
                $oldData,
                $person,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Person updated successfully',
                'person' => $person,
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

            $person = Person::find($id);

            if (!$person) {
                return response()->json(['error' => 'Not Found', 'details' => 'Person not found'], 404);
            }

            $oldData = $person->toArray();
            $person->delete();

            // Log da ação
            LogHelper::store(
                'deleted',
                'person',
                $id,
                $oldData,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json(['message' => 'Person deleted successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error', 'details' => $e->getMessage()], 500);
        }
    }
}
