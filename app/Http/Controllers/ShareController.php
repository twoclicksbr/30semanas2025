<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Share;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function index(Request $request)
    {
        try {
            $ids = $request->query('id', null);
            $idCredential = $request->query('id_credential', null);
            $name = $request->query('name', null);
            $idGender = $request->query('id_gender', null);
            $idChurch = $request->query('id_church', null);
            $idTypeParticipation = $request->query('id_type_participation', null);
            $active = $request->query('active', null);

            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');

            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            // Criar a query base
            $query = Share::orderBy($sortBy, $sortOrder);

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

            if (!is_null($idGender)) {
                $idGenderArray = explode(',', $idGender);
                $query->whereIn('id_gender', $idGenderArray);
                $appliedFilters['id_gender'] = $idGenderArray;
            }

            if (!is_null($idChurch)) {
                $idChurchArray = explode(',', $idChurch);
                $query->whereIn('id_church', $idChurchArray);
                $appliedFilters['id_church'] = $idChurchArray;
            }

            if (!is_null($idTypeParticipation)) {
                $idTypeParticipationArray = explode(',', $idTypeParticipation);
                $query->whereIn('id_type_participation', $idTypeParticipationArray);
                $appliedFilters['id_type_participation'] = $idTypeParticipationArray;
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

            $shares = $query->paginate($perPage);

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
                    'share',
                    null,
                    $appliedFilters,
                    null,
                    $idPerson,
                    $idCredentialLog
                );
            }

            return response()->json([
                'shares' => $shares,
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',
                        'name' => 'Filter by share name using LIKE',
                        'id_gender' => 'Filter by gender ID, allowing multiple values separated by commas',
                        'id_church' => 'Filter by church ID, allowing multiple values separated by commas',
                        'id_type_participation' => 'Filter by participation type ID, allowing multiple values separated by commas',
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

            $share = Share::where('id', $id)
                ->where('id_credential', $idCredential)
                ->first();

            if (!$share) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Share not found'
                ], 404);
            }

            // Log da ação
            LogHelper::store(
                'show',
                'share',
                $share->id,
                null,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json(['share' => $share], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage()
            ], 500);
        }
    }

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

            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'name' => 'required|string|unique:share,name',
                'id_gender' => 'required|exists:gender,id',
                'id_church' => 'required|exists:church,id',
                'id_type_participation' => 'required|exists:type_participation,id',
                'link_meet' => 'nullable|url',
                'active' => 'sometimes|integer|in:0,1',
            ]);

            // Adicionar automaticamente o id_credential autenticado
            $validatedData['id_credential'] = $idCredential;

            $share = Share::create($validatedData);

            // Log da ação
            LogHelper::store(
                'created',
                'share',
                $share->id,
                null,
                $share,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Share created successfully',
                'share' => $share,
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

            $share = Share::find($id);

            if (!$share) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Share not found'
                ], 404);
            }

            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'name' => "sometimes|string|unique:share,name,{$id}",
                'id_gender' => 'sometimes|exists:gender,id',
                'id_church' => 'sometimes|exists:church,id',
                'id_type_participation' => 'sometimes|exists:type_participation,id',
                'link_meet' => 'nullable|url',
                'active' => 'sometimes|integer|in:0,1',
            ]);

            $oldData = $share->toArray();
            $share->update($validatedData);

            // Log da ação
            LogHelper::store(
                'updated',
                'share',
                $share->id,
                $oldData,
                $share,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Share updated successfully',
                'share' => $share,
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

            $share = Share::find($id);

            if (!$share) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Share not found'
                ], 404);
            }

            $oldData = $share->toArray();
            $share->delete();

            // Log da ação
            LogHelper::store(
                'deleted',
                'share',
                $id,
                $oldData,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Share deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
