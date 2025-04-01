<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Gender;
use Illuminate\Http\Request;

class GenderController extends Controller
{
    /**
     * Listar todos os registros com filtros, paginação e ordenação.
     */
    public function index(Request $request)
    {
        try {

            $idCredential = session('id_credential');

            $query = Gender::query();

            // Se for filial, só mostra os registros da matriz (id_credential = 1)
            if ($idCredential != 1) {
                $query->where('id_credential', 1);
            } else {
                $query->where('id_credential', $idCredential);
            }

            $ids = $request->query('id', null);
            $name = $request->query('name', null);
            $active = $request->query('active', null);

            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');

            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            $query->orderBy($sortBy, $sortOrder);

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

            // $idCredential = session('id_credential');
            // if (!is_null($idCredential)) {
            //     $query->where('id_credential', $idCredential);
            //     $appliedFilters['id_credential'] = $idCredential;
            // }

            if (!is_null($name)) {
                $query->where('name', 'LIKE', "%{$name}%");
                $appliedFilters['name'] = $name;
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

            $genders = $query->paginate($perPage);

            // $idPerson = $request->header('id_person');
            $idPerson = (int) $request->header('id-person');
            if (!$idPerson) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person in headers'
                ], 401);
            }

            if ($idPerson && $idCredential) {
                LogHelper::store(
                    'viewed',
                    'gender',
                    null,
                    $appliedFilters,
                    null,
                    $idPerson,
                    $idCredential
                );
            }

            return response()->json([
                'genders' => $genders,
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',
                        'name' => 'Filter by gender name using LIKE',
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
                'name' => 'required|string|unique:gender,name',
                'active' => 'sometimes|integer|in:0,1',
            ]);

            // Adicionar automaticamente o id_credential autenticado
            $validatedData['id_credential'] = $idCredential;

            $gender = Gender::create($validatedData);

            // Log da ação
            LogHelper::store(
                'created',
                'gender',
                $gender->id,
                null,
                $gender,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Gender created successfully',
                'gender' => $gender,
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

            $gender = Gender::where('id', $id)
                ->where('id_credential', $idCredential)
                ->first();

            if (!$gender) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Gender not found'
                ], 404);
            }

            // Log da ação
            LogHelper::store(
                'show',
                'gender',
                $gender->id,
                null,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json(['gender' => $gender], 200);

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

            $gender = Gender::find($id);

            if (!$gender) {
                return response()->json(['error' => 'Not Found', 'details' => 'Gender not found'], 404);
            }

            $validatedData = $request->validate([
                'name' => 'sometimes|string|unique:gender,name,' . $id,
                'active' => 'sometimes|integer|in:0,1',
            ]);

            $oldData = $gender->toArray();
            $gender->update($validatedData);

            // Log da ação
            LogHelper::store(
                'updated',
                'gender',
                $gender->id,
                $oldData,
                $gender,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Gender updated successfully',
                'gender' => $gender,
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

            $gender = Gender::find($id);

            if (!$gender) {
                return response()->json(['error' => 'Not Found', 'details' => 'Gender not found'], 404);
            }

            $oldData = $gender->toArray();
            $gender->delete();

            // Log da ação
            LogHelper::store(
                'deleted',
                'gender',
                $id,
                $oldData,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json(['message' => 'Gender deleted successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error', 'details' => $e->getMessage()], 500);
        }
    }
}
