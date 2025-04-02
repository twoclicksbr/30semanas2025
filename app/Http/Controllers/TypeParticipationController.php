<?php

namespace App\Http\Controllers;

use App\Models\TypeParticipation;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;


class TypeParticipationController extends Controller
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
            $active = $request->query('active', null);

            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');

            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            // Criar a query base
            $query = TypeParticipation::orderBy($sortBy, $sortOrder);

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
            
            $typeParticipations = $query->paginate($perPage);

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
                    'type_participation',
                    null,
                    $appliedFilters,
                    null,
                    $idPerson,
                    $idCredentialLog
                );
            }

            return response()->json([
                'type_participations' => $typeParticipations,
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',
                        'name' => 'Filter by type_participation name using LIKE',
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
                'name' => 'required|string|unique:type_participation,name',
                'active' => 'sometimes|integer|in:0,1',
            ]);

            $validatedData['id_credential'] = $idCredential;

            $typeParticipation = TypeParticipation::create($validatedData);

            // Log da ação
            LogHelper::store(
                'created',
                'type_participation',
                $typeParticipation->id,
                null,
                $typeParticipation,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'TypeParticipation created successfully',
                'type_participation' => $typeParticipation,
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

            $typeParticipation = TypeParticipation::where('id', $id)
                ->where('id_credential', $idCredential)
                ->first();

            if (!$typeParticipation) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Registros criados pela matriz não podem ser alterados por outras credenciais.'
                ], 404);
            }

            // Log da ação
            LogHelper::store(
                'show',
                'type_participation',
                $typeParticipation->id,
                null,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json(['type_participation' => $typeParticipation], 200);

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

            




            $typeParticipation = TypeParticipation::find($id);
            if (!$typeParticipation) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Registros criados pela matriz não podem ser alterados por outras credenciais.'
                ], 404);
            }

            // Bloqueia edição de registros criados pela matriz
            if ($typeParticipation->id_credential == 1 && $idCredential != 1) {
                return response()->json([
                    'error' => 'Forbidden',
                    'details' => 'Registros criados pela matriz não podem ser alterados por outras credenciais.'
                ], 403);
            }

            // Impede edição de registros que não pertencem à própria credencial
            if ($typeParticipation->id_credential != $idCredential) {
                return response()->json([
                    'error' => 'Forbidden',
                    'details' => 'Você não tem permissão para editar este registro.'
                ], 403);
            }






            $validatedData = $request->validate([
                'name' => 'required|string|unique:type_participation,name,' . $id,
                'active' => 'sometimes|integer|in:0,1',
            ]);

            $oldData = $typeParticipation->toArray();
            $typeParticipation->update($validatedData);

            // Log da ação
            LogHelper::store(
                'updated',
                'type_participation',
                $typeParticipation->id,
                $oldData,
                $typeParticipation,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'TypeParticipation updated successfully',
                'type_participation' => $typeParticipation,
            ]);

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




            $typeParticipation = TypeParticipation::find($id);
            if (!$typeParticipation) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Registros criados pela matriz não podem ser excluídos por outras credenciais.'
                ], 404);
            }

            // Bloqueia exclusão de registros da matriz
            if ($typeParticipation->id_credential == 1 && $idCredential != 1) {
                return response()->json([
                    'error' => 'Forbidden',
                    'details' => 'Registros criados pela matriz não podem ser excluídos por outras credenciais.'
                ], 403);
            }

            if ($typeParticipation->id_credential != $idCredential) {
                return response()->json([
                    'error' => 'Forbidden',
                    'details' => 'Você não tem permissão para excluir este registro.'
                ], 403);
            }





            $oldData = $typeParticipation->toArray();
            $typeParticipation->delete();

            // Log da ação
            LogHelper::store(
                'deleted',
                'type_participation',
                $id,
                $oldData,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'TypeParticipation deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage()
            ], 500);
        }
    }

}
